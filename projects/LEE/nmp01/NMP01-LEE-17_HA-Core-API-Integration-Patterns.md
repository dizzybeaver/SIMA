# File: NMP01-LEE-17_HA-Core-API-Integration-Patterns.md

# NMP01-LEE-17: HA Core - API Integration Patterns

**Project:** Lambda Execution Engine (SUGA-ISP)  
**Project Code:** LEE  
**Category:** Home Assistant Integration  
**Component:** ha_core.py  
**Priority:** 🔴 CRITICAL  
**Status:** Active  
**Created:** 2025-10-29  
**Last Updated:** 2025-10-29

---

## Summary

Complete documentation of Home Assistant REST API integration patterns in ha_core.py, including authentication, entity state management, service calls, caching strategies, and error handling.

---

## Context

ha_core.py is the central module for Home Assistant integration in SUGA-ISP Lambda. It provides application-level functions for HA API interaction, distinct from infrastructure-level HTTP operations (see NMP01-LEE-01 for separation rationale).

---

## Core Functions

### Authentication

#### get_ha_token()

**Purpose:** Retrieve and cache HA long-lived access token

**Implementation:**
```python
def get_ha_token():
    """Get HA token from cache or Parameter Store."""
    # Try cache first
    token = gateway.cache_get("config_ha_token")
    if token:
        return token
    
    # Get from Parameter Store (encrypted)
    token = gateway.get_parameter("ha_token", decrypt=True)
    
    # Validate
    if not gateway.validate_ha_token(token):
        raise SecurityError("Invalid HA token format")
    
    # Cache (no expiration - valid for Lambda lifetime)
    gateway.cache_set("config_ha_token", token)
    return token
```

**Performance:**
- Cached: < 1ms
- Uncached: 50-100ms (Parameter Store call)
- Hit rate: 95-99%

---

#### get_ha_url()

**Purpose:** Retrieve HA instance URL

**Implementation:**
```python
def get_ha_url():
    """Get HA instance URL from configuration."""
    url = gateway.cache_get("config_ha_url")
    if url:
        return url
    
    url = gateway.get_parameter("ha_url")
    gateway.cache_set("config_ha_url", url)
    return url
```

---

### Entity State Management

#### get_entity_state(entity_id)

**Purpose:** Get current state of HA entity

**Implementation:**
```python
def get_entity_state(entity_id):
    """
    Get entity state with caching.
    
    Cache TTL: 5 minutes (300 seconds)
    Cache hit rate: 75-85%
    """
    # Validate entity ID
    if not gateway.validate_entity_id(entity_id):
        raise ValueError(f"Invalid entity ID: {entity_id}")
    
    # Check cache
    cache_key = f"ha_entity_{entity_id}"
    cached = gateway.cache_get(cache_key)
    if cached:
        gateway.log_debug("Entity cache hit", entity_id=entity_id)
        return cached
    
    # Fetch from API
    url = f"{get_ha_url()}/api/states/{entity_id}"
    headers = {
        "Authorization": f"Bearer {get_ha_token()}",
        "Content-Type": "application/json"
    }
    
    response = gateway.http_get(url, headers=headers)
    
    if response.status_code != 200:
        gateway.log_error("Failed to get entity state",
                          entity_id=entity_id,
                          status=response.status_code)
        return None
    
    state = response.json()
    
    # Cache for 5 minutes
    gateway.cache_set(cache_key, state, ttl=300)
    return state
```

**Performance:**
- Cached: < 1ms
- Uncached: 50-200ms (API call)
- Typical speedup: 50-200x

---

#### set_entity_state(entity_id, new_state, attributes=None)

**Purpose:** Update entity state via service call

**Implementation:**
```python
def set_entity_state(entity_id, new_state, attributes=None):
    """
    Set entity state by calling appropriate service.
    
    Example:
        set_entity_state("light.bedroom", "on", {"brightness": 255})
    """
    domain, object_id = entity_id.split('.')
    
    # Determine service based on domain and state
    if domain == 'light':
        service = 'turn_on' if new_state == 'on' else 'turn_off'
    elif domain == 'switch':
        service = 'turn_on' if new_state == 'on' else 'turn_off'
    elif domain == 'cover':
        service = 'open_cover' if new_state == 'open' else 'close_cover'
    else:
        service = f'set_{new_state}'
    
    # Call service
    result = call_service(domain, service, {
        'entity_id': entity_id,
        **(attributes or {})
    })
    
    # Invalidate cache immediately
    invalidate_entity_cache(entity_id)
    
    return result
```

---

### Service Calls

#### call_service(domain, service, service_data=None)

**Purpose:** Call HA service

**Implementation:**
```python
def call_service(domain, service, service_data=None):
    """
    Call Home Assistant service.
    
    Args:
        domain: Service domain (e.g., 'light', 'switch')
        service: Service name (e.g., 'turn_on', 'toggle')
        service_data: Service data dict
        
    Returns:
        Response from HA API
    """
    url = f"{get_ha_url()}/api/services/{domain}/{service}"
    headers = {
        "Authorization": f"Bearer {get_ha_token()}",
        "Content-Type": "application/json"
    }
    
    payload = service_data or {}
    
    gateway.log_info("Calling HA service",
                     domain=domain,
                     service=service)
    
    response = gateway.http_post(url, 
                                 json=payload, 
                                 headers=headers)
    
    if response.status_code not in [200, 201]:
        gateway.log_error("Service call failed",
                          domain=domain,
                          service=service,
                          status=response.status_code)
        raise APIError(f"Service call failed: {response.status_code}")
    
    return response.json()
```

**Performance:** 50-200ms (synchronous API call)

---

### Bulk Operations

#### get_all_states()

**Purpose:** Get states of all entities

**Implementation:**
```python
def get_all_states():
    """
    Get all entity states with caching.
    
    Cache TTL: 10 minutes (600 seconds)
    Use case: Alexa device discovery
    """
    cache_key = "ha_api_states"
    cached = gateway.cache_get(cache_key)
    if cached:
        gateway.log_debug("States cache hit")
        return cached
    
    url = f"{get_ha_url()}/api/states"
    headers = {
        "Authorization": f"Bearer {get_ha_token()}",
        "Content-Type": "application/json"
    }
    
    response = gateway.http_get(url, headers=headers)
    
    if response.status_code != 200:
        gateway.log_error("Failed to get all states",
                          status=response.status_code)
        return []
    
    states = response.json()
    
    # Cache for 10 minutes
    gateway.cache_set(cache_key, states, ttl=600)
    return states
```

**Performance:**
- Cached: < 1ms
- Uncached: 100-500ms (large response)
- Typical use: Alexa discovery (infrequent)

---

#### get_entities_by_domain(domain)

**Purpose:** Get all entities in a domain

**Implementation:**
```python
def get_entities_by_domain(domain):
    """
    Get all entities in domain with caching.
    
    Example: get_entities_by_domain('light')
    Cache TTL: 10 minutes
    """
    cache_key = f"ha_domain_{domain}"
    cached = gateway.cache_get(cache_key)
    if cached:
        return cached
    
    # Get all states and filter
    all_states = get_all_states()
    domain_states = [
        state for state in all_states
        if state['entity_id'].startswith(f"{domain}.")
    ]
    
    gateway.cache_set(cache_key, domain_states, ttl=600)
    return domain_states
```

---

## Caching Strategies

### Entity State Caching

**Pattern:** Individual entity caching with 5-minute TTL

**Key Format:** `ha_entity_{entity_id}`

**Benefits:**
- 50-200x speedup for repeated access
- Reduces HA API load
- 75-85% hit rate typical

**Invalidation:**
- Manual: After state change
- Automatic: TTL expiration (5 minutes)
- WebSocket: Real-time state updates

---

### Domain Caching

**Pattern:** Bulk domain entity caching with 10-minute TTL

**Key Format:** `ha_domain_{domain}`

**Benefits:**
- Single API call for all domain entities
- Reduces discovery latency
- Used for Alexa device discovery

**Invalidation:**
- Automatic: TTL expiration (10 minutes)
- Manual: On entity added/removed

---

### Configuration Caching

**Pattern:** Persistent configuration caching (no TTL)

**Key Format:** `config_{setting_name}`

**Benefits:**
- Avoid Parameter Store calls (50-100ms)
- 95-99% hit rate
- Valid for Lambda lifetime

**Invalidation:**
- Never (Lambda lifetime only)

---

## Cache Invalidation Functions

### invalidate_entity_cache(entity_id)

**Purpose:** Invalidate single entity cache

```python
def invalidate_entity_cache(entity_id):
    """Invalidate entity cache after state change."""
    cache_key = f"ha_entity_{entity_id}"
    gateway.cache_delete(cache_key)
    gateway.log_debug("Entity cache invalidated", entity_id=entity_id)
```

---

### invalidate_domain_cache(domain)

**Purpose:** Invalidate domain cache

```python
def invalidate_domain_cache(domain):
    """Invalidate domain cache after entity add/remove."""
    cache_key = f"ha_domain_{domain}"
    gateway.cache_delete(cache_key)
    gateway.log_debug("Domain cache invalidated", domain=domain)
```

---

### warm_ha_cache(entity_ids)

**Purpose:** Pre-warm cache for frequently accessed entities

```python
def warm_ha_cache(entity_ids):
    """
    Pre-warm cache for entity list.
    
    Use case: Cold start optimization
    """
    for entity_id in entity_ids:
        try:
            get_entity_state(entity_id)  # Fetches and caches
        except Exception as e:
            gateway.log_warning("Failed to warm cache",
                                entity_id=entity_id,
                                error=str(e))
```

**Usage:**
```python
# In lambda_preload.py
ha_core.warm_ha_cache([
    'light.bedroom',
    'light.living_room',
    'switch.tv'
])
```

---

## Error Handling

### Connection Errors

```python
def get_entity_state(entity_id):
    try:
        response = gateway.http_get(url, headers=headers)
    except ConnectionError as e:
        gateway.log_error("HA connection failed",
                          error=str(e))
        # Return cached value if available (stale ok)
        return gateway.cache_get(cache_key, default=None)
```

---

### Authentication Errors

```python
def call_service(domain, service, data):
    response = gateway.http_post(url, json=data, headers=headers)
    
    if response.status_code == 401:
        gateway.log_error("HA authentication failed")
        # Clear token cache, force refresh
        gateway.cache_delete("config_ha_token")
        raise AuthenticationError("Invalid HA token")
```

---

### Rate Limiting

```python
def get_entity_state(entity_id):
    if _is_rate_limited():
        gateway.log_warning("Rate limit hit, using cache")
        # Force cache return (stale ok)
        return gateway.cache_get(cache_key, default=None)
    
    # Proceed with API call
    response = gateway.http_get(url, headers=headers)
```

---

## Performance Metrics

### API Call Latency

| Operation | Cached | Uncached | Speedup |
|-----------|--------|----------|---------|
| get_entity_state() | < 1ms | 50-200ms | 50-200x |
| get_all_states() | < 1ms | 100-500ms | 100-500x |
| get_entities_by_domain() | < 1ms | 100-500ms | 100-500x |
| call_service() | N/A | 50-200ms | N/A |

### Cache Hit Rates (Production)

| Operation | Hit Rate |
|-----------|----------|
| Entity state | 75-85% |
| Domain list | 80-90% |
| Configuration | 95-99% |
| All states | 70-80% |

---

## Related Documentation

**Separation Rationale:**
- NMP01-LEE-01: Application vs infrastructure cache (why ha_core, not INT-01)

**Generic Patterns:**
- INT-01: CACHE interface (used by ha_core)
- INT-08: HTTP_CLIENT interface (used by ha_core)

**Integration:**
- NMP01-LEE-18: HA Alexa integration
- NMP01-LEE-20: HA WebSocket real-time updates

---

## Keywords

home-assistant, ha-core, api-integration, entity-state, service-calls, caching-strategy, authentication, performance, rest-api, SUGA-ISP, LEE

---

**END OF FILE**

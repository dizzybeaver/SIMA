# File: NMP01-LEE-02_INT-01-CACHE-Function-Catalog.md

# NMP01-LEE-02: INT-01 CACHE - Function Catalog

**Project:** Lambda Execution Engine (SUGA-ISP)  
**Project Code:** LEE  
**Category:** Interface Catalog  
**Interface:** INT-01 (CACHE)  
**Priority:** 🔴 CRITICAL  
**Status:** Active  
**Created:** 2025-10-29  
**Last Updated:** 2025-10-29

---

## Summary

Complete function catalog for INT-01 (CACHE) interface in SUGA-ISP Lambda project, documenting all cache operations, actual usage patterns, performance characteristics, and Home Assistant integration specifics.

---

## Context

This catalog documents the SUGA-ISP implementation of the generic CACHE interface pattern (INT-01). Unlike the generic pattern documentation, this focuses on actual implementation details, real usage patterns from the project, and integration with Home Assistant caching strategies.

---

## Function Catalog

### Core Functions

#### cache_set(key, value, ttl=None)
**Gateway:** `gateway.cache_set()`  
**Router:** `interface_cache.cache_set()`  
**Implementation:** `cache_core.set_value()`

**Purpose:** Store value in cache with optional TTL

**Parameters:**
- `key` (str): Cache key (project uses format: `{domain}_{identifier}`)
- `value` (Any): Value to cache (JSON-serializable)
- `ttl` (int, optional): Time-to-live in seconds (default: no expiration)

**Returns:** `bool` - True if stored successfully

**Usage in Project:**
```python
# Home Assistant entity caching (5 minute TTL)
gateway.cache_set("ha_entity_light.bedroom", entity_state, ttl=300)

# Configuration caching (no expiration)
gateway.cache_set("config_ha_url", ha_url)

# API response caching (10 minute TTL)
gateway.cache_set("ha_api_states", states_list, ttl=600)
```

**Performance:** < 1ms for cache writes

---

#### cache_get(key, default=None)
**Gateway:** `gateway.cache_get()`  
**Router:** `interface_cache.cache_get()`  
**Implementation:** `cache_core.get_value()`

**Purpose:** Retrieve value from cache

**Parameters:**
- `key` (str): Cache key to lookup
- `default` (Any, optional): Return value if key not found or expired

**Returns:** Cached value or default

**Usage in Project:**
```python
# Check entity cache before API call
cached_state = gateway.cache_get("ha_entity_light.bedroom")
if cached_state:
    return cached_state

# Get configuration with default
ha_url = gateway.cache_get("config_ha_url", default="http://localhost:8123")

# Get API response cache
states = gateway.cache_get("ha_api_states", default=[])
```

**Performance:** < 1ms for cache reads (O(1) lookup)

---

#### cache_delete(key)
**Gateway:** `gateway.cache_delete()`  
**Router:** `interface_cache.cache_delete()`  
**Implementation:** `cache_core.delete_value()`

**Purpose:** Remove specific key from cache

**Parameters:**
- `key` (str): Cache key to delete

**Returns:** `bool` - True if key existed and was deleted

**Usage in Project:**
```python
# Invalidate entity cache after state change
gateway.cache_delete("ha_entity_light.bedroom")

# Clear configuration cache after update
gateway.cache_delete("config_ha_url")

# Invalidate domain cache
gateway.cache_delete("ha_domain_light")
```

**Performance:** < 1ms for deletion

---

#### cache_clear()
**Gateway:** `gateway.cache_clear()`  
**Router:** `interface_cache.cache_clear()`  
**Implementation:** `cache_core.clear_all()`

**Purpose:** Clear entire cache

**Returns:** `bool` - True if cache was cleared

**Usage in Project:**
```python
# Clear cache on reconnection
if reconnected:
    gateway.cache_clear()
    
# Clear cache after configuration change
if config_changed:
    gateway.cache_clear()
```

**Performance:** < 5ms for full cache clear

---

### Utility Functions

#### cache_has(key)
**Gateway:** `gateway.cache_has()`  
**Router:** `interface_cache.cache_has()`  
**Implementation:** `cache_core.has_key()`

**Purpose:** Check if key exists in cache (without retrieving value)

**Parameters:**
- `key` (str): Cache key to check

**Returns:** `bool` - True if key exists and not expired

**Usage in Project:**
```python
# Check before expensive operation
if not gateway.cache_has("ha_api_states"):
    states = fetch_states_from_api()
    gateway.cache_set("ha_api_states", states, ttl=600)
```

**Performance:** < 0.5ms for existence check

---

#### cache_get_stats()
**Gateway:** `gateway.cache_get_stats()`  
**Router:** `interface_cache.cache_get_stats()`  
**Implementation:** `cache_core.get_statistics()`

**Purpose:** Get cache performance statistics

**Returns:** `dict` with keys:
- `total_keys`: Number of keys in cache
- `hits`: Cache hit count
- `misses`: Cache miss count
- `hit_rate`: Percentage hit rate
- `memory_estimate`: Estimated memory usage

**Usage in Project:**
```python
# Debug performance
stats = gateway.cache_get_stats()
gateway.log_info(f"Cache hit rate: {stats['hit_rate']:.1f}%")
```

**Performance:** < 1ms

---

## Home Assistant Integration Patterns

### Entity State Caching

**Pattern:** Cache HA entity states to reduce API calls

**Key Format:** `ha_entity_{entity_id}`

**Example:**
```python
def get_entity_state(entity_id):
    cache_key = f"ha_entity_{entity_id}"
    
    # Try cache first
    cached = gateway.cache_get(cache_key)
    if cached:
        return cached
    
    # Fetch from HA API
    state = ha_api_get_state(entity_id)
    
    # Cache for 5 minutes
    gateway.cache_set(cache_key, state, ttl=300)
    return state
```

**TTL:** 300 seconds (5 minutes)  
**Hit Rate:** 75-85% (typical)  
**Speedup:** 50-200x (< 1ms vs 50-200ms API call)

---

### Domain Caching

**Pattern:** Cache all entities in a domain (e.g., all lights)

**Key Format:** `ha_domain_{domain}`

**Example:**
```python
def get_all_lights():
    cache_key = "ha_domain_light"
    
    cached = gateway.cache_get(cache_key)
    if cached:
        return cached
    
    lights = ha_api_get_states(domain="light")
    gateway.cache_set(cache_key, lights, ttl=600)
    return lights
```

**TTL:** 600 seconds (10 minutes)  
**Use Case:** List all entities in Alexa discovery  
**Benefit:** Single API call instead of per-entity calls

---

### Configuration Caching

**Pattern:** Cache HA connection configuration

**Key Format:** `config_{setting_name}`

**Example:**
```python
def get_ha_url():
    cached = gateway.cache_get("config_ha_url")
    if cached:
        return cached
    
    url = get_parameter_store_value("ha_url")
    gateway.cache_set("config_ha_url", url)  # No TTL
    return url
```

**TTL:** None (persists for Lambda execution)  
**Benefit:** Avoid repeated Parameter Store calls (50-100ms each)

---

### API Response Caching

**Pattern:** Cache HA API responses

**Key Format:** `ha_api_{endpoint}`

**Example:**
```python
def get_states():
    cache_key = "ha_api_states"
    
    cached = gateway.cache_get(cache_key)
    if cached:
        return cached
    
    states = ha_api_call("/api/states")
    gateway.cache_set(cache_key, states, ttl=300)
    return states
```

**TTL:** 300-600 seconds  
**Use Case:** Batch operations, discovery  
**Benefit:** Reduce API load on HA instance

---

## Performance Characteristics

### Operation Timing

| Operation | Cached | Uncached | Speedup |
|-----------|--------|----------|---------|
| Entity State | < 1ms | 50-200ms | 50-200x |
| Domain List | < 1ms | 100-500ms | 100-500x |
| Configuration | < 1ms | 50-100ms | 50-100x |
| API States | < 1ms | 100-300ms | 100-300x |

### Cache Hit Rates (Production)

| Pattern | Expected Hit Rate |
|---------|-------------------|
| Entity State | 75-85% |
| Domain List | 80-90% |
| Configuration | 95-99% |
| API Responses | 70-80% |

### Memory Usage

| Cache Size | Memory Est. |
|------------|-------------|
| 100 entities | ~1MB |
| 1,000 entities | ~10MB |
| 5,000 entities | ~50MB |

**Constraint:** Lambda has 128MB total memory

---

## Cache Invalidation Strategies

### Entity State Change

**Trigger:** HA state change event via WebSocket

**Action:**
```python
def on_state_changed(entity_id, new_state):
    # Invalidate single entity cache
    gateway.cache_delete(f"ha_entity_{entity_id}")
    
    # Invalidate domain cache if needed
    domain = entity_id.split('.')[0]
    gateway.cache_delete(f"ha_domain_{domain}")
```

---

### Configuration Change

**Trigger:** User updates configuration

**Action:**
```python
def on_config_updated():
    # Clear all configuration cache
    gateway.cache_clear()
```

---

### Reconnection

**Trigger:** HA WebSocket reconnects

**Action:**
```python
def on_reconnect():
    # Clear all HA state cache (may be stale)
    for key in gateway.cache_get_keys():
        if key.startswith("ha_"):
            gateway.cache_delete(key)
```

---

## Common Cache Keys (Project-Specific)

### Home Assistant

- `ha_entity_{entity_id}` - Entity state
- `ha_domain_{domain}` - All entities in domain
- `ha_api_states` - Full state list
- `ha_api_services` - Service list
- `ha_connection_status` - Connection state

### Configuration

- `config_ha_url` - HA instance URL
- `config_ha_token` - HA access token
- `config_alexa_client_id` - Alexa client ID
- `config_log_level` - Logging level

### Alexa Integration

- `alexa_discovery_{user_id}` - Device discovery cache
- `alexa_token_{user_id}` - User token cache
- `alexa_capability_{device_id}` - Device capabilities

---

## Integration with Other Interfaces

### CACHE + LOGGING

**Pattern:** Log cache operations for debugging

```python
def cache_set(key, value, ttl=None):
    result = _cache_set_impl(key, value, ttl)
    gateway.log_debug(f"Cache set: {key}, TTL: {ttl}")
    return result
```

---

### CACHE + SECURITY

**Pattern:** Sanitize sentinels before returning cached values

```python
def cache_get(key, default=None):
    value = _cache_get_impl(key)
    
    # Sanitize sentinel objects (BUG-01 prevention)
    if gateway.is_sentinel(value):
        return default
    
    return value
```

---

### CACHE + METRICS

**Pattern:** Track cache hit/miss rates

```python
def cache_get(key, default=None):
    value = _cache_get_impl(key)
    
    if value is not None:
        gateway.increment_counter("cache_hit")
    else:
        gateway.increment_counter("cache_miss")
    
    return value if value is not None else default
```

---

## Known Issues and Workarounds

### Issue 1: Sentinel Leakage (BUG-01)

**Problem:** Sentinel objects stored in cache caused comparison failures

**Solution:** Sanitize at router layer
```python
# In interface_cache.py
def cache_get(key, default=None):
    from gateway import is_sentinel
    value = _get_from_core(key)
    
    if is_sentinel(value):
        return default
    return value
```

**REF:** BUG-01, LESS-08

---

### Issue 2: Memory Pressure

**Problem:** Cache grows too large in long-running Lambda

**Solution:** Implement TTL and size limits
```python
MAX_CACHE_SIZE = 5000  # Entries
MAX_CACHE_MEMORY = 50 * 1024 * 1024  # 50MB

def cache_set(key, value, ttl=None):
    if len(_CACHE) >= MAX_CACHE_SIZE:
        _evict_oldest()
    
    _CACHE[key] = (value, ttl, time.time())
```

---

## Related Documentation

**Generic Patterns:**
- INT-01: Generic CACHE interface pattern
- ARCH-07: LMMS (memory management)

**Project Decisions:**
- DEC-05: Cache sanitization requirements
- DEC-07: Lambda memory constraints

**Lessons:**
- LESS-08: ISP and cache separation
- NMP01-LEE-01: Application vs infrastructure cache

**Anti-Patterns:**
- AP-01: Direct core imports
- AP-19: Sentinel leakage

---

## Keywords

cache, INT-01, SUGA-ISP, LEE, function-catalog, home-assistant, performance, entity-caching, domain-caching, configuration-caching, ttl, hit-rate, memory-management

---

**END OF FILE**

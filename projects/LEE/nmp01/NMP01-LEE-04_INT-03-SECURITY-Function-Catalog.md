# File: NMP01-LEE-04_INT-03-SECURITY-Function-Catalog.md

# NMP01-LEE-04: INT-03 SECURITY - Function Catalog

**Project:** Lambda Execution Engine (SUGA-ISP)  
**Project Code:** LEE  
**Category:** Interface Catalog  
**Interface:** INT-03 (SECURITY)  
**Priority:** 🟡 HIGH  
**Status:** Active  
**Created:** 2025-10-29  
**Last Updated:** 2025-10-29

---

## Summary

Complete function catalog for INT-03 (SECURITY) interface, documenting validation, sanitization, sentinel detection, and Home Assistant token management.

---

## Function Catalog

### Validation Functions

#### validate_string(value, max_length=None, pattern=None)
**Gateway:** `gateway.validate_string()`

**Purpose:** Validate string input with optional constraints

**Parameters:**
- `value`: String to validate
- `max_length` (int, optional): Maximum allowed length
- `pattern` (str, optional): Regex pattern to match

**Returns:** `bool` - True if valid

**Usage in Project:**
```python
# Validate entity ID format
if gateway.validate_string(entity_id, pattern=r'^[a-z_]+\.[a-z0-9_]+$'):
    process_entity(entity_id)

# Validate token length
if gateway.validate_string(token, max_length=512):
    store_token(token)
```

---

#### validate_dict(value, required_keys=None)
**Gateway:** `gateway.validate_dict()`

**Purpose:** Validate dictionary structure

**Parameters:**
- `value`: Dictionary to validate
- `required_keys` (list, optional): Required keys

**Usage in Project:**
```python
# Validate Alexa directive
if gateway.validate_dict(directive, required_keys=['header', 'endpoint', 'payload']):
    process_directive(directive)

# Validate HA state object
if gateway.validate_dict(state, required_keys=['entity_id', 'state']):
    cache_state(state)
```

---

### Sentinel Detection

#### is_sentinel(value)
**Gateway:** `gateway.is_sentinel()`

**Purpose:** Detect sentinel objects that shouldn't leave boundaries

**Critical:** Prevents BUG-01 (sentinel leak)

**Usage in Project:**
```python
# Check before caching
if not gateway.is_sentinel(value):
    gateway.cache_set(key, value)

# Check before returning
def cache_get(key):
    value = _get_from_cache(key)
    if gateway.is_sentinel(value):
        return None
    return value
```

**Performance:** < 0.05ms

---

#### sanitize_for_log(value)
**Gateway:** `gateway.sanitize_for_log()`

**Purpose:** Remove sensitive data before logging

**Usage in Project:**
```python
# Sanitize tokens
gateway.log_info("Request received",
                 token=gateway.sanitize_for_log(token))

# Sanitize state objects
safe_state = gateway.sanitize_for_log(state)
gateway.log_debug("State cached", state=safe_state)
```

---

### Token Management (HA Integration)

#### validate_ha_token(token)
**Purpose:** Validate Home Assistant long-lived access token

**Implementation:**
```python
def validate_ha_token(token):
    if not gateway.validate_string(token, min_length=100, max_length=512):
        return False
    
    # Token format check
    if not token.startswith('eyJ'):  # JWT format
        return False
    
    return True
```

---

## Home Assistant Security Patterns

### Token Storage

**Pattern:** Secure token storage and retrieval

```python
def get_ha_token():
    # Try cache first
    token = gateway.cache_get("config_ha_token")
    if token:
        return token
    
    # Get from Parameter Store (encrypted)
    token = gateway.get_parameter("ha_token", decrypt=True)
    
    # Validate before caching
    if gateway.validate_ha_token(token):
        gateway.cache_set("config_ha_token", token)
        return token
    
    raise SecurityError("Invalid HA token")
```

---

### Request Validation

**Pattern:** Validate incoming Alexa directives

```python
def validate_alexa_directive(directive):
    # Structure validation
    if not gateway.validate_dict(directive, 
                                  required_keys=['directive']):
        return False
    
    directive_obj = directive['directive']
    
    # Header validation
    if not gateway.validate_dict(directive_obj, 
                                  required_keys=['header', 'endpoint']):
        return False
    
    # Endpoint validation
    endpoint = directive_obj['endpoint']
    if not gateway.validate_string(endpoint.get('endpointId')):
        return False
    
    return True
```

---

### Entity ID Validation

**Pattern:** Validate HA entity IDs

```python
def validate_entity_id(entity_id):
    # Format: domain.object_id
    if not gateway.validate_string(entity_id, 
                                    pattern=r'^[a-z_]+\.[a-z0-9_]+$'):
        gateway.log_warning("Invalid entity ID format",
                            entity_id=entity_id)
        return False
    
    return True
```

---

## Performance Characteristics

| Operation | Latency | Notes |
|-----------|---------|-------|
| validate_string() | < 0.1ms | Simple checks |
| validate_dict() | < 0.2ms | Depends on depth |
| is_sentinel() | < 0.05ms | Type check only |
| sanitize_for_log() | < 0.1ms | String operations |

---

## Related Documentation

**Generic Patterns:**
- INT-03: Generic SECURITY pattern

**Bugs:**
- BUG-01: Sentinel leak (fixed by is_sentinel)

**Lessons:**
- LESS-08: ISP and security separation

---

## Keywords

security, INT-03, validation, sentinel, sanitization, token-management, home-assistant, authentication, input-validation

---

**END OF FILE**

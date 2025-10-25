# NM02-Dependencies-Layers_DEP-02.md - DEP-02

# DEP-02: Layer 1 - Core Utilities

**Category:** NM02 - Dependencies
**Topic:** Dependency Layers
**Priority:** 🟡 High
**Status:** Active
**Created:** 2024-04-15
**Last Updated:** 2025-10-24

---

## Summary

Layer 1 provides core utility functions that depend only on LOGGING (Layer 0). This includes SECURITY (validation/encryption), UTILITY (helper functions), and SINGLETON (state management) - foundational services needed by many interfaces.

---

## Context

Layer 1 sits above LOGGING but below storage and service layers. These are fundamental utilities that don't require complex infrastructure but provide essential functionality for higher layers.

**Layer 1 Philosophy:**
- Simple, focused responsibilities
- Minimal dependencies (LOGGING only)
- Broadly useful across interfaces
- Foundation for Layers 2-4

---

## Content

### Layer 1 Interfaces

#### SECURITY
```
SECURITY
├─ Dependencies: LOGGING
├─ Purpose: Validation, encryption, sanitization
├─ Used by: HTTP_CLIENT, CONFIG, WEBSOCKET
└─ Why Layer 1: Security is fundamental, needed by many interfaces
```

**Functions:**
- `validate_input()` - Input validation
- `sanitize_string()` - String sanitization
- `validate_url()` - URL validation
- `encrypt_token()` - Token encryption
- `hash_password()` - Password hashing

**Dependency Pattern:**
```python
# In security_core.py
from gateway import log_warning  # Only depends on Layer 0

def validate_input(value):
    if not is_valid(value):
        log_warning(f"Invalid input: {value}")
        return False
    return True
```

#### UTILITY
```
UTILITY
├─ Dependencies: None (optionally LOGGING)
├─ Purpose: Helper functions, common utilities
├─ Used by: All interfaces
└─ Why Layer 1: Utilities are foundational helpers
```

**Functions:**
- `safe_get()` - Safe dictionary access
- `format_timestamp()` - Time formatting
- `parse_response()` - Response parsing
- `calculate_ttl()` - TTL calculations

**Dependency Pattern:**
```python
# In utility_core.py
# Minimal dependencies, pure utility functions

def safe_get(dictionary, key, default=None):
    """Safe dictionary access with default"""
    return dictionary.get(key, default)

def format_timestamp(dt):
    """Format datetime for consistent output"""
    return dt.strftime("%Y-%m-%d %H:%M:%S UTC")
```

#### SINGLETON
```
SINGLETON
├─ Dependencies: LOGGING
├─ Purpose: Singleton storage for stateful objects
├─ Used by: Various interfaces
└─ Why Layer 1: Simple storage, minimal dependencies
```

**Functions:**
- `get_singleton()` - Retrieve singleton instance
- `set_singleton()` - Store singleton instance
- `clear_singleton()` - Clear singleton instance

**Dependency Pattern:**
```python
# In singleton_core.py
from gateway import log_info  # Only depends on Layer 0

_SINGLETONS = {}

def get_singleton(key):
    log_info(f"Singleton access: {key}")
    return _SINGLETONS.get(key)

def set_singleton(key, value):
    log_info(f"Singleton set: {key}")
    _SINGLETONS[key] = value
```

### Why Layer 1 Depends Only on LOGGING

**1. Simplicity:**
Core utilities should be simple:
- Validation doesn't need cache
- Encryption doesn't need HTTP
- Helpers don't need complex infrastructure

**2. Broad Usability:**
Layer 1 is used by many interfaces:
- HTTP_CLIENT uses SECURITY for validation
- CONFIG uses UTILITY for parsing
- CACHE uses SINGLETON for state

**3. Initialization Order:**
```
Cold start sequence:
1. Layer 0: LOGGING (< 5ms)
2. Layer 1: SECURITY, UTILITY, SINGLETON (< 15ms)
3. Layer 2+: Can use Layer 1 utilities
```

### Usage Examples

**SECURITY Used by HTTP_CLIENT:**
```python
# In http_client_core.py
from gateway import log_info, validate_url  # Layer 0 + Layer 1

def http_get(url):
    log_info(f"HTTP GET: {url}")
    
    # Layer 1 validation
    if not validate_url(url):
        raise ValueError(f"Invalid URL: {url}")
    
    response = _perform_request(url)
    return response
```

**UTILITY Used by CACHE:**
```python
# In cache_core.py
from gateway import log_info, safe_get  # Layer 0 + Layer 1

def cache_get(key):
    log_info(f"Cache lookup: {key}")
    
    # Layer 1 helper
    value = safe_get(_CACHE_STORE, key)
    return value
```

**SINGLETON Used by HTTP_CLIENT:**
```python
# In http_client_core.py
from gateway import get_singleton, set_singleton  # Layer 1

def get_http_client():
    # Reuse singleton HTTP session
    client = get_singleton("http_client")
    if not client:
        client = _create_http_client()
        set_singleton("http_client", client)
    return client
```

### Real-World Impact

**Security Checks Early:**
- Validation happens at Layer 1 (early)
- Prevents invalid data from reaching Layer 2+
- Reduces error propagation

**Utility Functions Available:**
- All interfaces can use helpers
- Reduces code duplication
- Consistent patterns across interfaces

**Singleton State Management:**
- Efficient resource reuse
- Single HTTP client instance
- Reduced initialization overhead

### Performance Characteristics

**Initialization:**
- SECURITY: < 5ms
- UTILITY: < 2ms
- SINGLETON: < 3ms
- Total Layer 1: < 15ms

**Operation:**
- Validation: < 0.5ms
- Utility calls: < 0.1ms
- Singleton access: < 0.2ms

---

## Related Topics

- **DEP-01**: Layer 0 - LOGGING (foundation)
- **DEP-03**: Layer 2 - Storage & Monitoring (depends on Layer 1)
- **NM01-INT-03**: SECURITY interface definition
- **NM01-INT-11**: UTILITY interface definition
- **NM01-INT-06**: SINGLETON interface definition

---

## Keywords

dependency layer 1, core utilities, SECURITY, UTILITY, SINGLETON, validation, helpers, state management

---

## Version History

- **2025-10-24**: Atomized from NM02-CORE, migrated to v3.1.0 format
- **2024-04-15**: Original content in NM02-CORE-Dependency Layers

---

**File:** `NM02-Dependencies-Layers_DEP-02.md`
**Location:** `/nmap/NM02/`
**End of Document**

# INT-01-CACHE-Interface.md

**Version:** 1.0.0  
**Date:** 2025-11-07  
**Purpose:** SUGA CACHE interface pattern definition  
**Architecture:** SUGA (Gateway Pattern)

---

## INTERFACE OVERVIEW

**Interface:** INT-01 CACHE  
**Category:** Data Management  
**Layer Position:** Interface Layer  
**Gateway Required:** Yes

### Purpose

Provides caching operations through SUGA three-layer pattern with mandatory gateway access, lazy imports, and proper layer separation.

### Key Principle

**Gateway → Interface → Core** with lazy imports at each transition.

---

## THREE-LAYER IMPLEMENTATION

### Layer 1: Gateway Entry (gateway_wrappers_cache.py)

**Public API - Lazy Import to Interface**

```python
def cache_get(key, default=None):
    """
    Get value from cache via SUGA gateway pattern.
    
    Args:
        key: Cache key
        default: Default value if key not found
        
    Returns:
        Cached value or default
        
    Pattern: Gateway → Interface (lazy import)
    """
    import interface_cache
    return interface_cache.get(key, default)

def cache_set(key, value, ttl=None):
    """
    Set cache value via SUGA gateway pattern.
    
    Args:
        key: Cache key
        value: Value to cache
        ttl: Time to live in seconds
        
    Returns:
        bool: Success status
        
    Pattern: Gateway → Interface (lazy import)
    """
    import interface_cache
    return interface_cache.set(key, value, ttl)

def cache_delete(key):
    """
    Delete cache key via SUGA gateway pattern.
    
    Args:
        key: Cache key to delete
        
    Returns:
        bool: Success status
        
    Pattern: Gateway → Interface (lazy import)
    """
    import interface_cache
    return interface_cache.delete(key)

def cache_clear():
    """
    Clear all cache via SUGA gateway pattern.
    
    Pattern: Gateway → Interface (lazy import)
    """
    import interface_cache
    return interface_cache.clear()

def cache_exists(key):
    """
    Check if key exists in cache via SUGA gateway pattern.
    
    Args:
        key: Cache key to check
        
    Returns:
        bool: True if exists
        
    Pattern: Gateway → Interface (lazy import)
    """
    import interface_cache
    return interface_cache.exists(key)
```

### Layer 2: Interface Routing (interface_cache.py)

**Routing Layer - Lazy Import to Core**

```python
def get(key, default=None):
    """
    Route cache get to core implementation.
    
    Pattern: Interface → Core (lazy import)
    """
    import cache_core
    return cache_core.get_impl(key, default)

def set(key, value, ttl=None):
    """
    Route cache set to core implementation.
    
    Pattern: Interface → Core (lazy import)
    """
    import cache_core
    return cache_core.set_impl(key, value, ttl)

def delete(key):
    """
    Route cache delete to core implementation.
    
    Pattern: Interface → Core (lazy import)
    """
    import cache_core
    return cache_core.delete_impl(key)

def clear():
    """
    Route cache clear to core implementation.
    
    Pattern: Interface → Core (lazy import)
    """
    import cache_core
    return cache_core.clear_impl()

def exists(key):
    """
    Route cache exists check to core implementation.
    
    Pattern: Interface → Core (lazy import)
    """
    import cache_core
    return cache_core.exists_impl(key)
```

### Layer 3: Core Implementation (cache_core.py)

**Implementation Layer - Business Logic**

```python
# In-memory cache storage
_cache = {}
_cache_ttl = {}

def get_impl(key, default=None):
    """
    Core cache get implementation.
    
    Handles:
    - Key lookup
    - TTL validation
    - Default return
    - Sentinel sanitization
    """
    import time
    
    # Check if key exists
    if key not in _cache:
        return default
    
    # Check TTL
    if key in _cache_ttl:
        if time.time() > _cache_ttl[key]:
            # Expired
            delete_impl(key)
            return default
    
    value = _cache[key]
    
    # Sanitize sentinels (DEC-05)
    if hasattr(value, '__name__') and value.__name__ in ['_CacheMiss', '_NotFound']:
        return default
    
    return value

def set_impl(key, value, ttl=None):
    """
    Core cache set implementation.
    
    Handles:
    - Value storage
    - TTL tracking
    - Memory management
    """
    import time
    
    _cache[key] = value
    
    if ttl is not None:
        _cache_ttl[key] = time.time() + ttl
    elif key in _cache_ttl:
        del _cache_ttl[key]
    
    return True

def delete_impl(key):
    """
    Core cache delete implementation.
    
    Handles:
    - Key removal
    - TTL cleanup
    """
    if key in _cache:
        del _cache[key]
    
    if key in _cache_ttl:
        del _cache_ttl[key]
    
    return True

def clear_impl():
    """
    Core cache clear implementation.
    
    Handles:
    - Full cache cleanup
    - TTL cleanup
    """
    _cache.clear()
    _cache_ttl.clear()
    return True

def exists_impl(key):
    """
    Core cache exists implementation.
    
    Handles:
    - Key existence check
    - TTL validation
    """
    import time
    
    if key not in _cache:
        return False
    
    # Check if expired
    if key in _cache_ttl:
        if time.time() > _cache_ttl[key]:
            delete_impl(key)
            return False
    
    return True
```

---

## USAGE PATTERNS

### Pattern 1: Basic Cache Operations

```python
# CORRECT - Always via gateway
import gateway

# Set cache
gateway.cache_set('user:123', {'name': 'John'}, ttl=300)

# Get cache
user = gateway.cache_get('user:123')

# Check existence
if gateway.cache_exists('user:123'):
    print("User cached")

# Delete
gateway.cache_delete('user:123')
```

### Pattern 2: Cache with Default

```python
# CORRECT - With default value
import gateway

user = gateway.cache_get('user:123', default={'name': 'Unknown'})
```

### Pattern 3: Cache Clear

```python
# CORRECT - Clear all cache
import gateway

gateway.cache_clear()
```

---

## ANTI-PATTERNS

### Anti-Pattern 1: Direct Core Import

```python
# WRONG - Bypasses gateway
from cache_core import get_impl
value = get_impl('key')

# CORRECT - Via gateway
import gateway
value = gateway.cache_get('key')
```

### Anti-Pattern 2: Direct Interface Import

```python
# WRONG - Bypasses gateway
from interface_cache import get
value = get('key')

# CORRECT - Via gateway
import gateway
value = gateway.cache_get('key')
```

### Anti-Pattern 3: Module-Level Import

```python
# WRONG - Module level (increases cold start)
import gateway

def my_function():
    return gateway.cache_get('key')

# CORRECT - Function level if rarely used
def my_function():
    import gateway
    return gateway.cache_get('key')
```

---

## DESIGN DECISIONS

### DEC-01: Gateway Mandatory

All cache operations MUST go through gateway layer.

**Rationale:**
- Enforces SUGA pattern
- Prevents circular imports
- Enables lazy loading
- Single entry point

### DEC-02: Lazy Imports

Each layer uses lazy (function-level) imports.

**Rationale:**
- Reduces cold start time
- Prevents circular dependencies
- Only loads when needed
- LMMS pattern compliance

### DEC-03: Sentinel Sanitization

Cache must sanitize sentinel objects before returning.

**Rationale:**
- Prevents sentinel leakage (BUG-01)
- Avoids JSON serialization errors
- Router layer protection
- Clean boundary crossing

### DEC-04: TTL Optional

TTL parameter optional with None = no expiration.

**Rationale:**
- Flexibility for different use cases
- Some data doesn't expire
- Explicit cleanup available
- Memory management control

---

## VERIFICATION

### Pre-Implementation Checklist

```
[ ] Gateway wrapper created (gateway_wrappers_cache.py)
[ ] Interface routing created (interface_cache.py)
[ ] Core implementation created (cache_core.py)
[ ] All functions use lazy imports
[ ] No direct core imports anywhere
[ ] Sentinel sanitization implemented
[ ] TTL handling implemented
[ ] Documentation complete
```

### Testing Checklist

```
[ ] Gateway functions callable
[ ] Interface routing works
[ ] Core implementation correct
[ ] Lazy imports verified
[ ] TTL expiration works
[ ] Sentinel sanitization works
[ ] Clear function works
[ ] Exists check works
```

---

## CROSS-REFERENCES

**Related Architecture:**
- ARCH-01: Gateway Trinity (three-layer pattern)
- ARCH-02: Layer Separation (no direct core)
- ARCH-03: Interface Pattern (routing layer)

**Related Gateways:**
- GATE-01: Gateway Entry Pattern
- GATE-02: Lazy Import Pattern
- GATE-03: Cross-Interface Communication

**Related Decisions:**
- DEC-01: SUGA Choice
- DEC-03: Gateway Mandatory
- DEC-05: Sentinel Sanitization

**Related Anti-Patterns:**
- AP-01: Direct Core Import
- AP-02: Module-Level Heavy Imports
- AP-19: Sentinel Leakage (generic)

**Related Lessons:**
- LESS-03: Gateway Entry Point
- LESS-06: Pay Small Costs Early
- LESS-07: Base Layers No Dependencies

---

## KEYWORDS

CACHE, interface, SUGA, gateway, lazy-import, three-layer, TTL, sentinel, in-memory, key-value

---

## RELATED TOPICS

- Interface patterns
- Gateway architecture
- Lazy loading
- Memory management
- Cold start optimization
- Sentinel handling
- Data caching strategies

---

**END OF FILE**

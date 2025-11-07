# ARCH-02-Layer-Separation.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** SUGA layer separation and responsibilities  
**Category:** Python Architecture - SUGA

---

## PRINCIPLE

Each SUGA layer has distinct responsibilities. Mixing responsibilities creates architectural debt.

---

## LAYER DEFINITIONS

### Gateway Layer
**Location:** `/src/gateway_wrappers*.py`

**ONLY Does:**
- Exposes public functions
- Lazy imports to interface
- Parameter pass-through
- Returns interface result

**NEVER Does:**
- Implementation logic
- Data manipulation
- Error handling (except logging)
- Business rules

### Interface Layer
**Location:** `/src/interface_*.py`

**ONLY Does:**
- Routes to core
- Lazy imports to core
- Parameter pass-through
- Returns core result

**NEVER Does:**
- Implementation logic
- Data manipulation
- Business rules
- Direct core-to-core calls

### Core Layer
**Location:** `/src/*_core.py`

**ONLY Does:**
- Implementation logic
- Business rules
- Data manipulation
- Error handling
- State management

**NEVER Does:**
- Expose public API directly
- Import other cores directly (use gateway)

---

## CORRECT SEPARATION

### Example: Cache Get

**Gateway (gateway_wrappers_cache.py):**
```python
def cache_get(key, default=None):
    """Get from cache (gateway)."""
    import interface_cache
    return interface_cache.get(key, default)
```

**Interface (interface_cache.py):**
```python
def get(key, default=None):
    """Get from cache (interface)."""
    import cache_core
    return cache_core.get_impl(key, default)
```

**Core (cache_core.py):**
```python
def get_impl(key, default=None):
    """Get from cache (implementation)."""
    if key in _cache:
        return _cache[key]
    return default
```

---

## VIOLATION EXAMPLES

### Violation 1: Logic in Gateway
```python
# ❌ WRONG: Logic in gateway layer
def cache_get(key, default=None):
    import interface_cache
    if not key:  # Logic doesn't belong here
        return None
    result = interface_cache.get(key)
    if result is None:  # Logic doesn't belong here
        return default
    return result
```

**Fix:** Move logic to core
```python
# ✅ CORRECT
def cache_get(key, default=None):
    import interface_cache
    return interface_cache.get(key, default)  # Just route
```

### Violation 2: Implementation in Interface
```python
# ❌ WRONG: Implementation in interface
def get(key, default=None):
    import cache_core
    value = cache_core.get_impl(key)
    # Processing doesn't belong here
    if value is _CacheMiss:
        value = default
    return value
```

**Fix:** Move to core
```python
# ✅ CORRECT
def get(key, default=None):
    import cache_core
    return cache_core.get_impl(key, default)  # Just route
```

### Violation 3: Direct Core Import in Core
```python
# ❌ WRONG: Direct core-to-core
def cache_with_logging(key, value):
    import logging_core  # Violates SUGA
    logging_core.info(f"Caching {key}")
    _cache[key] = value
```

**Fix:** Use gateway
```python
# ✅ CORRECT
def cache_with_logging(key, value):
    import gateway
    gateway.log_info(f"Caching {key}")  # Via gateway
    _cache[key] = value
```

---

## RESPONSIBILITIES BY LAYER

### What Goes in Gateway
- Public function signature
- Docstring (complete)
- Lazy import statement
- Function call to interface
- Return statement

**That's it. Nothing else.**

### What Goes in Interface
- Interface function signature
- Docstring (brief)
- Lazy import statement
- Function call to core
- Return statement

**That's it. Nothing else.**

### What Goes in Core
- Implementation function signature
- Docstring (detailed)
- All imports needed
- Business logic
- Data manipulation
- Error handling
- Validation
- State management

**Everything else goes here.**

---

## CROSS-LAYER COMMUNICATION

### Gateway → Interface
**Always:** Lazy import, direct call
```python
import interface_cache
return interface_cache.get(key)
```

### Interface → Core
**Always:** Lazy import, direct call
```python
import cache_core
return cache_core.get_impl(key)
```

### Core → Core
**Always:** Via gateway
```python
import gateway
gateway.log_info("message")
```

**Never direct:**
```python
import logging_core  # ❌ WRONG
logging_core.info("message")
```

---

## VERIFICATION

**Gateway Layer Check:**
- [ ] No business logic
- [ ] Only lazy imports
- [ ] Only routes to interface
- [ ] Complete docstrings

**Interface Layer Check:**
- [ ] No business logic
- [ ] Only lazy imports
- [ ] Only routes to core
- [ ] Brief docstrings

**Core Layer Check:**
- [ ] All business logic here
- [ ] Uses gateway for cross-core
- [ ] No direct core imports
- [ ] Detailed docstrings

---

## BENEFITS

**Clear separation provides:**
- Easy to find code (know which layer)
- Easy to test (mock interfaces)
- Easy to maintain (changes isolated)
- Prevents circular imports (lazy loading)
- Faster cold start (load on demand)

---

## ANTI-PATTERNS

**AP-01:** Direct core import  
**AP-02:** Module-level imports  
**AP-06:** Logic in gateway layer  
**AP-07:** Implementation in interface layer

---

**Related:**
- ARCH-01: Gateway trinity
- ARCH-03: Interface pattern
- GATE-01: Gateway entry
- AP-01: Direct core import
- AP-06: Logic in wrong layer

**Version History:**
- v1.0.0 (2025-11-06): Initial layer separation

---

**END OF FILE**

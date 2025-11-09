# SUGA-Architecture.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** SUGA (Single Universal Gateway Architecture) pattern reference  
**Location:** `/sima/shared/`

---

## OVERVIEW

**SUGA** = Single Universal Gateway Architecture

**Purpose:** 3-layer pattern preventing circular imports, enabling lazy loading, providing single entry point

**Applicability:** Python projects requiring modular architecture with clear separation

---

## THREE LAYERS

### Gateway Layer
**File:** `gateway.py`, `gateway_wrappers.py`  
**Purpose:** Public API, single entry point  
**Pattern:** Lazy imports to interfaces

```python
# gateway_wrappers.py
def cache_get(key):
    """Get value from cache."""
    import interface_cache  # Lazy import
    return interface_cache.get(key)
```

### Interface Layer
**Files:** `interface_*.py` (one per interface)  
**Purpose:** Routing to core implementations  
**Pattern:** Lazy imports to cores

```python
# interface_cache.py
def get(key):
    """Route cache get to core."""
    import cache_core  # Lazy import
    return cache_core.get_impl(key)
```

### Core Layer
**Files:** `*_core.py` (one per interface)  
**Purpose:** Actual implementation logic  
**Pattern:** No imports of higher layers

```python
# cache_core.py
def get_impl(key):
    """Implementation of cache get."""
    # Pure implementation
    return cached_value
```

---

## GOLDEN RULES

### RULE-01: Always Import Via Gateway
**Never:** `from cache_core import get_value`  
**Always:** `import gateway; gateway.cache_get(key)`

**Why:** Prevents circular imports, enables lazy loading

### RULE-02: Lazy Imports at Function Level
**Never:** `import heavy_module` (at module level)  
**Always:** `def func(): import heavy_module` (at function level)

**Why:** Reduces cold start time, loads only when needed

### RULE-03: Respect Layer Dependencies
**Direction:** Gateway → Interface → Core (downward only)  
**Never:** Core → Interface, Interface → Gateway (upward)

**Why:** Prevents circular dependencies, clear architecture

---

## INTERFACE TEMPLATES

### Gateway Function Template
```python
def action_object(param1, param2, **kwargs):
    """
    [Brief description]
    
    Args:
        param1: [Description]
        param2: [Description]
        **kwargs: Additional options
        
    Returns:
        [Type]: [Description]
    """
    import interface_category
    return interface_category.action_object(param1, param2, **kwargs)
```

### Interface Function Template
```python
def action_object(param1, param2, **kwargs):
    """
    [Brief description - routing]
    
    Routes to: category_core.action_object_impl
    """
    import category_core
    return category_core.action_object_impl(param1, param2, **kwargs)
```

### Core Function Template
```python
def action_object_impl(param1, param2, **kwargs):
    """
    [Detailed implementation description]
    
    Args:
        param1: [Type and description]
        param2: [Type and description]
        **kwargs: Additional options
        
    Returns:
        [Type]: [Description]
        
    Raises:
        [Exception]: [When and why]
        
    Example:
        result = action_object_impl(val1, val2)
    """
    # Implementation here
    try:
        result = process(param1, param2)
        return result
    except SpecificError as e:
        # Never bare except
        log_error(f"Error: {e}")
        raise
```

---

## COMMON PATTERNS

### Adding New Function
1. Add to gateway wrapper
2. Add to interface
3. Add to core
4. Use lazy imports at each layer
5. Verify no circular imports

### Cross-Interface Communication
**Never:** Direct interface-to-interface imports  
**Always:** Via gateway

```python
# ❌ WRONG
def cache_function():
    import interface_logging  # Direct cross-interface
    interface_logging.log_info("message")

# ✅ CORRECT
def cache_function():
    import gateway  # Via gateway
    gateway.log_info("message")
```

### Error Handling Pattern
```python
# Core layer
def impl():
    try:
        # Implementation
        return result
    except SpecificError as e:
        import gateway
        gateway.log_error(f"Error: {e}")
        raise  # Re-raise for handling
```

---

## BENEFITS

**1. No Circular Imports**
- Lazy imports break cycles
- One-way dependencies
- Clean module structure

**2. Fast Cold Starts**
- Load only what's needed
- Heavy imports deferred
- Optimized startup

**3. Clear Architecture**
- Three distinct layers
- Single entry point
- Easy to understand

**4. Maintainable**
- Add features systematically
- Clear responsibilities
- Easy to test

**5. Scalable**
- Add interfaces without refactor
- Extend core without breaking
- Grow systematically

---

## ANTI-PATTERNS

### AP-01: Direct Core Import
❌ `from cache_core import get_value`  
✅ `import gateway; gateway.cache_get(key)`

### AP-02: Module-Level Imports
❌ `import heavy_lib` (module level)  
✅ `def func(): import heavy_lib` (function level)

### AP-03: Circular References
❌ Core imports Interface, Interface imports Core  
✅ One-way: Gateway → Interface → Core

### AP-04: Skipping Interface Layer
❌ Gateway directly imports Core  
✅ Gateway → Interface → Core

### AP-05: Upward Dependencies
❌ Core depends on Interface  
✅ Higher layers depend on lower only

---

## VERIFICATION

**Before deploying code using SUGA:**

1. ✅ All imports via gateway?
2. ✅ Lazy imports at function level?
3. ✅ All 3 layers present?
4. ✅ No circular imports?
5. ✅ Dependencies flow downward?

---

## RELATED

**Architecture Files:**
- ARCH-01: Gateway Trinity
- ARCH-02: Layer Separation
- ARCH-03: Interface Pattern

**Gateway Files:**
- GATE-01: Gateway Entry Pattern
- GATE-02: Lazy Import Pattern
- GATE-03: Cross-Interface Communication

**Location:** `/sima/languages/python/architectures/suga/`

---

**END OF FILE**

**Summary:** SUGA provides 3-layer architecture (Gateway → Interface → Core) with lazy imports preventing circular dependencies and enabling fast cold starts.

# NM02-RULES-Import_RULE-01.md - RULE-01

# RULE-01: Cross-Interface Imports via Gateway Only

**Category:** NM02 - Dependencies
**Topic:** Import Rules
**Priority:** 🔴 Critical
**Status:** Active
**Created:** 2024-04-15
**Last Updated:** 2025-10-24

---

## Summary

All cross-interface operations MUST route through gateway.py. Direct imports between core modules are prohibited to prevent circular dependencies and maintain the SUGA architectural pattern.

---

## Context

In the SUGA (Single Universal Gateway Architecture) pattern, interfaces are isolated from each other. When one interface needs functionality from another (e.g., cache needs logging), it must access it through the gateway layer, not through direct imports.

**Why This Rule Exists:**
- Prevents circular import dependencies
- Enforces architectural boundaries
- Enables lazy loading (LMMS system)
- Makes testing easier (mock at gateway level)
- Provides centralized control point

---

## Content

### The Rule

**✅ CORRECT Pattern:**
```python
# In cache_core.py
import gateway

def cache_operation(key):
    gateway.log_info(f"Cache operation for {key}")  # LOGGING via gateway
    gateway.record_metric("cache_ops", 1.0)         # METRICS via gateway
    return _CACHE_STORE.get(key)
```

**❌ WRONG Pattern:**
```python
# In cache_core.py
from logging_core import log_info        # VIOLATION!
from metrics_core import record_metric   # VIOLATION!

def cache_operation(key):
    log_info(f"Cache operation for {key}")
    record_metric("cache_ops", 1.0)
    return _CACHE_STORE.get(key)
```

### Why Gateway-Only?

**1. Prevents Circular Imports:**
```
Without gateway:
cache_core.py → logging_core.py → metrics_core.py → cache_core.py ❌ CIRCULAR!

With gateway:
cache_core.py → gateway.py → logging_core.py ✅ NO CIRCLES
metrics_core.py → gateway.py → cache_core.py ✅ NO CIRCLES
```

**2. Architectural Integrity:**
```
Gateway Layer (gateway.py, gateway_core.py, gateway_wrappers.py)
    ↓
Interface Layer (interface_*.py)
    ↓
Implementation Layer (*_core.py)

Enforcement: Implementation can only see gateway, never other implementations
```

**3. Testing Benefits:**
```python
# Mock at gateway level - affects all implementations
gateway.log_info = MagicMock()
gateway.cache_get = MagicMock(return_value="test")

# Without gateway, must mock N×M combinations
# With gateway, mock once at entry point
```

**4. Performance Optimization:**
```python
# Gateway enables ZAPH (Zero-Abstraction Fast Path)
def cache_get(key):  # Hot path
    return _FAST_PATH['cache_get'](key)  # 97% faster

# Direct imports cannot be optimized centrally
```

### Exception: Internal Imports

**Within same interface, internal imports are allowed:**
```python
# ✅ OK - Same interface
# In cache_core.py
from cache_types import CacheEntry

# ✅ OK - Same interface
# In cache_operations.py  
from cache_core import _CACHE_STORE
```

**But cross-interface must use gateway:**
```python
# ❌ WRONG - Cross-interface
# In cache_core.py
from logging_operations import log_cache_event

# ✅ CORRECT - Cross-interface
# In cache_core.py
import gateway
gateway.log_info("Cache event")
```

### Enforcement

**Code Review Checklist:**
- [ ] No `from *_core import` across interfaces
- [ ] All cross-interface calls use `gateway.*`
- [ ] Internal imports stay within interface
- [ ] No circular dependency warnings

**Automated Check (planned):**
```python
# Static analysis tool
def check_imports(file_path):
    interface = extract_interface(file_path)
    imports = extract_imports(file_path)
    
    for imp in imports:
        if is_core_module(imp) and imp.interface != interface:
            raise ImportViolation(f"{file_path} imports {imp} directly")
```

---

## Related Topics

- **DEC-01**: SUGA Pattern Choice - explains why gateway pattern
- **ARCH-01**: Gateway Trinity - the 3-file gateway structure
- **AP-01**: Direct Cross-Interface Imports - anti-pattern this rule prevents
- **LESS-01**: Gateway Pattern Proven - lessons from using this rule
- **DEP-01 to DEP-08**: Dependency layers - import hierarchy

---

## Keywords

gateway-only imports, cross-interface imports, import rules, SUGA pattern, circular dependencies, architectural boundaries, dependency management

---

## Version History

- **2025-10-24**: Phase 5 - Terminology corrected (SIMA → SUGA), migrated to v3.1.0 format
- **2025-10-18**: Updated with ZAPH performance benefits
- **2024-04-15**: Original rule documented in NM02-RULES-Import.md

---

**File:** `NM02-RULES-Import_RULE-01.md`
**End of Document**

# File: BUG-02.md

**REF-ID:** BUG-02  
**Category:** Project Lessons  
**Type:** Critical Bug  
**Project:** LEE (SUGA-ISP)  
**Version:** 1.0.0  
**Created:** 2025-10-18  
**Updated:** 2025-10-30  
**Status:** Resolved

---

## Summary

Circular import dependencies in early architecture caused import failures and module initialization errors. Fixed by implementing SUGA gateway pattern which makes circular imports architecturally impossible. This bug led to the fundamental architectural decision that defines the project.

---

## Bug Details

**Symptom:**
- `ImportError: cannot import name 'X' from partially initialized module`
- Different behavior depending on which module imported first
- Errors only appeared in certain execution paths
- Difficult to reproduce consistently

**Root Cause:**
```python
# Original flawed architecture (before SUGA)
# cache_module.py
from logging_module import log_info  # Import from logging

def cache_get(key):
    log_info(f"Cache get: {key}")  # Use logging
    return _cache.get(key)

# logging_module.py
from cache_module import cache_get  # Import from cache

def log_info(msg):
    if cache_get(f"log:{msg}"):  # Use cache
        return
    print(msg)

# The cycle: cache imports logging → logging imports cache → ImportError
```

**Discovery Method:**
Intermittent import failures during development and production deployments.

---

## Solution

**Implemented SUGA gateway pattern:**
```python
# gateway.py (single entry point)
from cache_core import _execute_get_implementation as _cache_get
from logging_core import _execute_info_implementation as _log_info

def cache_get(key):
    return _cache_get(key)

def log_info(msg):
    return _log_info(msg)

# cache_core.py (NO imports of other interfaces)
def _execute_get_implementation(key):
    # Pure cache logic, no logging
    return _CACHE_STORE.get(key)

# logging_core.py (NO imports of other interfaces)
def _execute_info_implementation(msg):
    # Pure logging logic, no cache
    print(msg)
```

**New Rule (RULE-01):**
```python
# ✅ CORRECT - Always import gateway
import gateway
value = gateway.cache_get(key)
gateway.log_info("message")

# ❌ WRONG - Never import core directly
from cache_core import get_value  # Violates RULE-01
```

**Why This Works:**
- Gateway is the ONLY module that imports core modules
- All other code imports ONLY gateway
- Circular imports become architecturally impossible
- Dependency graph becomes a tree (not a web)

---

## Impact

**Development:**
- Eliminated all import errors
- Code became more maintainable
- Clear dependency rules

**Architecture:**
- Led to SUGA pattern (foundational decision)
- Established gateway as central hub
- Created scalable architecture

**Team:**
- New developers onboard faster
- Code review easier
- Refactoring safer

---

## Prevention Strategies

1. **Use gateway pattern**
   - All cross-interface operations go through gateway
   - Enforced by architecture, not discipline

2. **Establish import rules early**
   - Define what can import what
   - Document and enforce rules

3. **Monitor dependency graph**
   - Visualize dependencies regularly
   - Detect cycles early

4. **Code review for imports**
   - Every direct core import triggers review
   - Question: "Should this go through gateway?"

5. **Automated checks**
   - Lint rules to prevent direct imports
   - CI/CD pipeline validation

---

## Related References

**Decisions:**
- DEC-01: SUGA pattern choice (the solution to this bug)

**Architecture:**
- ARCH-01: SUGA Pattern (gateway trinity)

**Lessons:**
- LESS-01: Gateway pattern prevents problems

**Anti-Patterns:**
- AP-01: Direct cross-interface imports
- AP-04: Circular dependencies

**Wisdom:**
- WISD-01: Architecture prevents problems

---

## Keywords

circular-import, import-error, gateway-pattern, SUGA, architecture, dependencies, initialization, SUGA-ISP

---

## Cross-References

**Inherits From:** None (root bug)  
**Related To:** BUG-01 (architecture solution), BUG-03 (error isolation)  
**Referenced By:** DEC-01, ARCH-01, WISD-01, LESS-01, AP-01, AP-04

---

**End of BUG-02**

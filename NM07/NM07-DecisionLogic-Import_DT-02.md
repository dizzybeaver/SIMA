# NM07-DecisionLogic-Import_DT-02.md - DT-02

# DT-02: Where Should This Function Go?

**Category:** Decision Logic
**Topic:** Import Decisions  
**Priority:** High
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Decision tree for determining the correct architectural location for new functions in SUGA - whether in core files, routers, wrappers, or utility modules.

---

## Context

SUGA architecture has clear organizational patterns for different types of functions. Placing functions correctly ensures maintainability and prevents architectural drift.

---

## Content

### Decision Tree

```
START: Have a function to implement
│
├─ Q: Is it interface-specific logic?
│  ├─ YES → Put in <interface>_core.py
│  │      Example: Cache validation → cache_core.py
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Is it generic utility (used by >3 interfaces)?
│  ├─ YES → Put in utility_core.py
│  │      Example: String formatting
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Is it routing/dispatch logic?
│  ├─ YES → Put in interface_<n>.py (router)
│  │      Example: Operation dispatch
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Is it gateway wrapper (user-facing)?
│  ├─ YES → Put in gateway_wrappers.py
│  │      Example: cache_get() wrapper
│  │      → END
│  │
│  └─ NO → Continue
│
└─ Q: Is it core gateway logic?
   └─ YES → Put in gateway_core.py
          Example: execute_operation()
          → END
```

### Function Placement Matrix

| Function Type | Location | Example |
|---------------|----------|---------|
| Cache business logic | cache_core.py | _execute_get_implementation |
| Cache routing | interface_cache.py | execute_cache_operation |
| Cache wrapper | gateway_wrappers.py | cache_get() |
| Generic utility | utility_core.py | format_timestamp() |
| Gateway core | gateway_core.py | execute_operation() |

### Real-World Usage Pattern

**User Query:** "Where should I put my new validation function?"

**Search Terms:** "where function goes"

**Decision Flow:**
1. Check: Is it interface-specific? (e.g., validates cache keys)
   - YES → Put in <interface>_core.py
2. Check: Is it generic utility? (e.g., validates emails)
   - YES → Put in utility_core.py
3. **Answer:** Based on specificity of validation logic

### Examples by Type

**Interface-Specific Logic:**
```python
# cache_core.py
def _validate_cache_key(key: str) -> bool:
    """Validates cache key format."""
    return isinstance(key, str) and len(key) > 0
```

**Generic Utility:**
```python
# utility_core.py
def format_timestamp(dt: datetime) -> str:
    """Formats datetime for logging."""
    return dt.strftime('%Y-%m-%d %H:%M:%S')
```

**Routing Logic:**
```python
# interface_cache.py
def execute_cache_operation(op: str, params: dict):
    """Routes cache operations to implementations."""
    return _OPERATION_DISPATCH[op](**params)
```

**Gateway Wrapper:**
```python
# gateway_wrappers.py
def cache_get(key: str):
    """User-facing cache get wrapper."""
    return execute_operation(GatewayInterface.CACHE, "get", {"key": key})
```

**Gateway Core:**
```python
# gateway_core.py
def execute_operation(interface, operation, params):
    """Core operation execution logic."""
    return _interface_handlers[interface](operation, params)
```

---

## Related Topics

- **ARCH-04**: Three-file interface pattern (router, core, types)
- **DT-13**: New interface or extend existing
- **DEC-01**: SUGA pattern structure
- **DT-01**: How to import functions

---

## Keywords

function placement, code organization, architecture, interface structure, SUGA layers

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-Import_DT-02.md`
**End of Document**

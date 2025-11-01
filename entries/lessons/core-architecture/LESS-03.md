# File: LESS-03.md

**REF-ID:** LESS-03  
**Category:** Lessons Learned  
**Topic:** Core Architecture  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Status:** Production

---

## Title

Infrastructure vs Business Logic Separation

---

## Priority

HIGH

---

## Summary

Router layer handles infrastructure concerns (validation, sanitization, logging, monitoring), while core layer handles pure business logic. This separation makes testing easier and responsibilities clear.

---

## Context

When fixing data leak bugs, we realized fixes belonged at the router layer, not the core layer. This revealed a fundamental principle about where different concerns should live.

---

## Lesson

### Layer Responsibilities

**Core Layer (Business Logic):**
- Algorithms and data transformations
- Domain-specific logic
- Pure functions when possible
- ❌ NO validation/sanitization
- ❌ NO logging/monitoring
- ❌ NO error handling (except domain errors)

**Router Layer (Infrastructure):**
- Validation and sanitization
- Logging and monitoring
- Error handling and recovery
- Performance measurement
- ❌ NO business logic
- ❌ NO algorithm implementation

**Gateway Layer (Orchestration):**
- Aggregating modules
- Exposing clean API
- Cross-module coordination
- ❌ NO implementation details
- ❌ NO business logic

### Example: Cache Operations

```python
# Core (business logic):
def _execute_get_implementation(key: str) -> Any:
    return _store.get(key, _MISS_SENTINEL)

# Router (infrastructure):
def execute_cache_operation(operation: str, **kwargs) -> Any:
    # Logging (infrastructure)
    log_debug(f"Cache operation: {operation}")
    
    # Business logic (delegated to core)
    result = _execute_get_implementation(**kwargs)
    
    # Sanitization (infrastructure)
    if _is_sentinel_object(result):
        return None
    
    # Monitoring (infrastructure)
    _record_metric('cache_hit' if result else 'cache_miss')
    
    return result
```

### Testing Benefits

**Core testing (pure business logic):**
```python
def test_cache_get_hit():
    # No mocks, no infrastructure
    _store['key'] = 'value'
    result = _execute_get_implementation('key')
    assert result == 'value'
```

**Router testing (infrastructure):**
```python
@patch('cache_core._execute_get_implementation')
def test_sanitization(mock_get):
    # Test infrastructure concern
    mock_get.return_value = _MISS_SENTINEL
    result = execute_cache_operation('get', key='test')
    assert result is None  # Sentinel sanitized to None
```

### Key Principle

**Separation of concerns:**
- Core = What the system does (business rules)
- Router = How the system does it (infrastructure)
- Gateway = Where the system does it (coordination)

**Benefits:**
- Core testing requires no mocks
- Infrastructure changes don't affect business logic
- Business logic changes don't affect infrastructure
- Clear responsibilities

---

## Related

**Cross-References:**
- ARCH-01: Three-layer gateway architecture
- LESS-01: Gateway pattern prevents problems
- LESS-07: Base layers have no dependencies

**Keywords:** layer separation, infrastructure, business logic, router responsibilities, testing isolation

---

## Version History

- **4.0.0** (2025-10-30): Genericized for SIMAv4
- **3.0.0** (2025-10-23): Initial SIMAv3 format

---

**END OF FILE**

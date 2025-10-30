# NM06-Lessons-CoreArchitecture_LESS-03.md - LESS-03

# LESS-03: Infrastructure vs Business Logic

**Category:** Lessons  
**Topic:** CoreArchitecture  
**Priority:** HIGH  
**Status:** Active  
**Created:** 2025-10-23  
**Last Updated:** 2025-10-23

---

## Summary

Router layer handles infrastructure concerns (validation, sanitization, logging, monitoring), while core layer handles pure business logic. This separation makes testing easier and responsibilities clear.

---

## Context

When fixing the sentinel leak bug, we realized the fix belonged at the router layer, not the core layer. This revealed a fundamental principle about where different concerns should live.

---

## Content

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
- Aggregating interfaces
- Exposing clean API
- Cross-interface coordination
- ❌ NO implementation details
- ❌ NO business logic

### Example: Cache Operations

```python
# Core (business logic):
def _execute_get_implementation(key: str) -> Any:
    return _CACHE_STORE.get(key, _CACHE_MISS)

# Router (infrastructure):
def execute_cache_operation(operation: str, **kwargs) -> Any:
    # Logging (infrastructure)
    gateway.log_debug(f"Cache operation: {operation}")
    
    # Business logic (delegated to core)
    result = _execute_get_implementation(**kwargs)
    
    # Sanitization (infrastructure)
    if _is_sentinel_object(result):
        return None
    
    # Monitoring (infrastructure)
    _record_cache_hit() if result else _record_cache_miss()
    
    return result
```

### Testing Benefits

**Core testing (pure business logic):**
```python
def test_cache_get_hit():
    # No mocks, no infrastructure
    _CACHE_STORE['key'] = 'value'
    result = _execute_get_implementation('key')
    assert result == 'value'
```

**Router testing (infrastructure):**
```python
@patch('cache_core._execute_get_implementation')
def test_sanitization(mock_get):
    # Test infrastructure concern
    mock_get.return_value = _CACHE_MISS
    result = execute_cache_operation('get', key='test')
    assert result is None  # Sentinel sanitized to None
```

---

## Related Topics

- **BUG-01**: Sentinel leak (taught this lesson)
- **DEC-02**: Layered architecture
- **ARCH-01**: Gateway trinity
- **LESS-07**: Base layers have no dependencies

---

## Keywords

infrastructure, business logic, layer separation, router responsibilities, core layer

---

## Version History

- **2025-10-23**: Created - Migrated to SIMA v3 individual file format
- **2025-10-15**: Original documentation in NM06-LESSONS-Core Architecture Lessons.md

---

**File:** `NM06-Lessons-CoreArchitecture_LESS-03.md`  
**Directory:** NM06/  
**End of Document**

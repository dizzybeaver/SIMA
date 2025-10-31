# File: LESS-08.md

**REF-ID:** LESS-08  
**Category:** Lessons Learned  
**Topic:** Core Architecture  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Status:** Production

---

## Title

Test Failure Paths

---

## Priority

HIGH

---

## Summary

Most bugs hide in error handling and edge cases, not the happy path. Test cache misses, invalid inputs, timeouts, and error conditions - that's where bugs live.

---

## Context

Most tests only validated the "happy path" (success cases). Bugs in error handling went unnoticed until production because failure paths weren't tested.

---

## Lesson

### The Problem

**Typical incomplete test:**
```python
def test_cache_get():
    cache_set("key", "value")
    result = cache_get("key")
    assert result == "value"
    
    # ❌ Doesn't test:
    # - What if key doesn't exist?
    # - What if cache is unavailable?
    # - What if value is None vs missing?
```

**What this misses:**
- Cache miss behavior (bugs often hide here!)
- Error handling robustness
- Edge cases
- Graceful degradation paths

### The Solution

**Comprehensive test coverage:**
```python
# Test 1: Success path
def test_cache_get_hit():
    cache_set("key", "value")
    result = cache_get("key")
    assert result == "value"

# Test 2: Failure path (cache miss)
def test_cache_get_miss():
    result = cache_get("nonexistent_key")
    assert result is None

# Test 3: Error path (cache broken)
def test_cache_get_error():
    with patch('cache_core._store', side_effect=Exception()):
        result = cache_get("key")
        assert result is None  # Should not raise

# Test 4: Edge case (None value stored)
def test_cache_get_none_value():
    cache_set("key", None)
    result = cache_get("key")
    assert result is None

# Test 5: Edge case (empty string)
def test_cache_get_empty_string():
    cache_set("key", "")
    result = cache_get("key")
    assert result == ""  # Not None!
```

### Failure Path Categories

**1. Missing Data**
```python
def test_missing_required_field():
    with pytest.raises(ValueError, match="user_id required"):
        process_request({})
```

**2. Invalid Data**
```python
def test_invalid_type():
    with pytest.raises(TypeError, match="Expected dict"):
        process_request("not a dict")
```

**3. External Failures**
```python
def test_database_unavailable():
    with patch('db_query', side_effect=ConnectionError()):
        result = process_request({'user_id': '123'})
        assert result['status'] == 'degraded'
```

**4. Edge Cases**
```python
def test_boundary_values():
    assert process_value(0) == 0
    assert process_value(100) == 100
    with pytest.raises(ValueError):
        process_value(101)
```

### The Testing Checklist

**For every function, test:**
- ✅ Success path (normal operation)
- ✅ Failure path (error handling)
- ✅ Edge cases (boundary conditions)
- ✅ Invalid inputs (type/value errors)
- ✅ External failures (timeout, unavailable)
- ✅ None/empty values (distinguish between missing and empty)

### Real-World Impact

**Example:**
- If we had tested cache miss: Bug caught BEFORE production
- Coverage before (success-only): 80% code, 40% bugs
- Coverage after (including failures): 95% code, 85% bugs

### Key Principle

"If you only test the happy path, you'll only catch happy bugs"

Most bugs hide in:
- Error handling code
- Edge cases
- Failure scenarios
- Unexpected inputs

---

## Related

**Cross-References:**
- AP-24: Testing only success paths anti-pattern
- LESS-05: Graceful degradation requires testing
- BUG-01: Example of bug found via failure testing

**Keywords:** test failures, error path testing, failure scenarios, comprehensive tests, edge cases

---

## Version History

- **4.0.0** (2025-10-30): Genericized for SIMAv4
- **3.0.0** (2025-10-23): Initial SIMAv3 format

---

**END OF FILE**

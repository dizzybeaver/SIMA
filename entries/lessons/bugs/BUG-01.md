# File: BUG-01.md

**REF-ID:** BUG-01  
**Category:** Project Lessons  
**Type:** Critical Bug  
**Project:** LEE (SUGA-ISP)  
**Version:** 1.0.0  
**Created:** 2025-10-19  
**Updated:** 2025-10-30  
**Status:** Resolved

---

## Summary

Sentinel object (_CacheMiss) leaked through router layer causing 535ms cold start performance degradation. Bug fixed by sanitizing sentinels at router boundary. Represents 62% improvement in cold start performance.

---

## Bug Details

**Symptom:**
- Cold starts ~535ms slower than expected (855ms vs 320ms)
- Cache operations slow on first call after container starts
- Mysterious performance degradation with no obvious cause

**Root Cause:**
```python
# In cache_core.py
_CACHE_MISS = object()  # Sentinel for cache miss

def _execute_get_implementation(key):
    return _CACHE_STORE.get(key, _CACHE_MISS)  # Returns sentinel!
```

Sentinel leaked to user code → Cache invalidation loops → 535ms penalty.

**Discovery Method:**
Timing logs revealed exact bottleneck:
```python
gateway.log_info(f"Init time: {init*1000:.1f}ms")  # 45ms ✓
gateway.log_info(f"Cache time: {cache*1000:.1f}ms")  # 535ms ← Found it!
```

---

## Solution

**Implementation:**
```python
# In interface_cache.py (router layer)
def execute_cache_operation(operation, **kwargs):
    if operation == 'get':
        result = _execute_get_implementation(**kwargs)
        
        # Sanitize sentinel before returning
        if _is_sentinel_object(result):
            return None  # Convert sentinel to None
        
        return result

def _is_sentinel_object(value):
    """Detect if value is object() sentinel."""
    return (
        type(value).__name__ == 'object' and
        not hasattr(value, '__dict__')
    )
```

**Why This Works:**
- Router layer handles infrastructure concerns (sanitization)
- Core layer focuses on business logic (cache operations)
- Clear responsibility boundary
- Users always get None for cache miss, never sentinel

---

## Impact

**Performance:**
- Before: 855ms cold start
- After: 320ms cold start
- Improvement: 535ms (62% faster)

**Reliability:**
- Cache became reliable for all use patterns
- Predictable behavior with None values
- Easy to debug and test

---

## Prevention Strategies

1. **Always sanitize at router layer**
   - Infrastructure concerns belong in router
   - Never let internal objects leak to users

2. **Define clear API contracts**
   - Document what users receive
   - Test that users get expected types

3. **Test failure paths**
   - Test cache miss scenarios
   - Test edge cases (None values, empty values)

4. **Add timing logs**
   - Measure operation times
   - Detect performance anomalies early

5. **Use verification protocol**
   - Follow verification checklist before deployment
   - Check for sentinel leaks

---

## Related References

**Decisions:**
- DEC-05: Sentinel sanitization at router layer (solution)

**Lessons:**
- LESS-06: Pay small costs early (~0.1ms sanitization vs 535ms penalty)
- LESS-02: Measure don't guess (how bug was discovered)
- LESS-03: Infrastructure vs business logic (separation principle)
- LESS-15: File verification mandatory (prevention protocol)

**Anti-Patterns:**
- AP-19: Sentinel objects crossing boundaries

**Wisdom:**
- WISD-02: Measure don't guess
- WISD-03: Small costs early prevent large costs later

---

## Keywords

sentinel, cache, performance, cold-start, leak, router, boundary, sanitization, _CacheMiss, SUGA-ISP

---

## Cross-References

**Inherits From:** None (root bug)  
**Related To:** BUG-02 (architecture issue), BUG-03 (error boundaries)  
**Referenced By:** WISD-02, WISD-03, LESS-02, LESS-06, DEC-05, AP-19

---

**End of BUG-01**

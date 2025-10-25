# NM06-Bugs-Critical_BUG-01.md - BUG-01

# BUG-01: Sentinel Leak (535ms Performance Cost)

**Category:** Lessons
**Topic:** Critical Bugs
**Priority:** Critical
**Status:** Resolved
**Date Discovered:** 2025-10-19
**Fixed In:** interface_cache.py v2025.10.19.21
**Created:** 2025-10-19
**Last Updated:** 2025-10-23

---

## Summary

Sentinel object (_CacheMiss) leaked through router layer causing 535ms cold start performance degradation. Bug fixed by sanitizing sentinels at router boundary per DEC-05. This represents a 62% improvement in cold start performance.

---

## Context

During routine cold start performance monitoring, timing logs revealed an unexpected 535ms penalty. Normal cold starts were completing in ~320ms, but observed cold starts were taking ~855ms with no obvious cause. The cache interface was the primary suspect due to the timing pattern.

---

## Content

### The Problem

**Symptom:**
- Cold starts taking ~535ms longer than expected
- Cache operations slow on first call after container starts
- Mysterious performance degradation with no obvious cause
- Memory usage seemed normal, but execution was sluggish

**The Code Issue:**

```python
# In cache_core.py
_CACHE_MISS = object()  # Sentinel for cache miss

def _execute_get_implementation(key):
    return _CACHE_STORE.get(key, _CACHE_MISS)  # Returns sentinel!
```

**What Went Wrong:**
- Sentinel object leaked to user code through the interface layer
- User code patterns like `if cached is not None` didn't work (sentinel is not None)
- User code patterns like `if cached` also didn't work (sentinel is truthy)
- This caused cache invalidation loops where code kept re-checking cache
- Each loop added ~5-10ms, compounding to 535ms total penalty

### Root Cause

**Architectural Issue:**
- Sentinel was internal implementation detail of cache_core
- Router layer (interface_cache.py) didn't sanitize internal objects
- Infrastructure concerns (sentinels) leaked into business logic
- No separation between "cache miss" (internal) and "None" (external API)

**Why It Happened:**
- Initially thought sentinel was elegant solution for distinguishing None values from missing keys
- Didn't anticipate sentinel would leak through interface boundary
- Lacked clear responsibility: who sanitizes internal objects?

### Impact

**Performance:**
- 535ms cold start penalty (62% slower)
- Affected every cold start invocation
- Compounding effect in cache invalidation loops

**Reliability:**
- Cache became unreliable for certain use patterns
- Edge cases with None values became unpredictable
- Debugging was difficult (sentinel object not obviously wrong)

**User Experience:**
- Slower response times
- Inconsistent behavior
- Hard to reproduce (cold start specific)

### Solution

**Implementation:**

Sanitization added at the interface router layer:

```python
# In interface_cache.py (router layer)
def execute_cache_operation(operation, **kwargs):
    if operation == 'get':
        result = _execute_get_implementation(**kwargs)
        
        # Sanitize sentinel before returning
        if _is_sentinel_object(result):
            return None  # Convert sentinel to None
        
        return result
```

Helper function to detect sentinel:

```python
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

### Prevention

**How to Prevent Similar Issues:**

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
   - Follow LESS-15 (5-step verification)
   - Check for sentinel leaks before deployment

---

## Related Topics

- **DEC-05**: Sentinel sanitization at router layer (solution decision)
- **LESS-06**: Pay small costs early (lesson learned from this bug)
- **LESS-02**: Measure don't guess (how bug was discovered)
- **AP-19**: Sentinel objects crossing boundaries (anti-pattern this created)
- **LESS-15**: File verification mandatory (prevention protocol)
- **LESS-03**: Infrastructure vs business logic (separation principle)

---

## Keywords

sentinel, cache, performance, cold-start, leak, router, boundary, sanitization, _CacheMiss

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2025-10-19**: Bug documented in NM06-BUGS-Critical.md
- **2025-10-19**: Bug discovered and fixed

---

**File:** `NM06-Bugs-Critical_BUG-01.md`
**End of Document**

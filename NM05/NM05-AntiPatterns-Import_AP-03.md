# NM05-AntiPatterns-Import_AP-03.md - AP-03

# AP-03: Gateway for Same-Interface Operations

**Category:** Anti-Patterns
**Topic:** Import
**Severity:** ⚪ Low
**Status:** Active
**Created:** 2024-10-15
**Last Updated:** 2025-10-23

---

## Summary

Using gateway for operations within the same interface when direct calls are allowed and more efficient. This works correctly but adds unnecessary overhead.

---

## The Anti-Pattern

**What's suboptimal:**
```python
# In cache_helper.py (part of CACHE interface)
import gateway

def cache_complex_operation(key):
    # Using gateway for same-interface operation
    value = gateway.cache_get(key)  # ⚠️ Inefficient
    if value:
        return gateway.cache_process(value)  # ⚠️ Adds overhead
    return None
```

**Why it's suboptimal (but not wrong):**
1. **Extra Function Calls**: Gateway → Interface → Core (3 hops instead of 1)
2. **Slight Performance Cost**: ~1-2µs overhead per call
3. **Unnecessary Indirection**: Already in same interface, can call directly
4. **Code Verbosity**: More imports than needed

**Important:** This is LOW severity because it works correctly and doesn't break anything.

---

## What to Do Instead

**More efficient approach:**
```python
# In cache_helper.py (part of CACHE interface)
from cache_core import get_value, process_value

def cache_complex_operation(key):
    # Direct calls within same interface ✅
    value = get_value(key)
    if value:
        return process_value(value)
    return None
```

**Why this is better:**
- Fewer function calls (more efficient)
- Clearer that operations are in same domain
- No gateway overhead
- Still maintains architecture (intra-interface calls allowed)

---

## The Rule

**Gateway requirement applies ONLY to cross-interface operations:**

```python
# CACHE interface calling LOGGING interface
import gateway  # ✅ Required - different interfaces
gateway.log_info("Cache operation")

# CACHE interface calling CACHE operations
from cache_core import get_value  # ✅ Allowed - same interface
value = get_value(key)
```

**Guideline:**
- **Cross-interface**: MUST use gateway
- **Intra-interface**: SHOULD use direct imports (for efficiency)
- **When in doubt**: Using gateway always works (just slightly slower)

---

## Real-World Example

**Context:** Cache helper functions within cache interface

**Original code (suboptimal but working):**
```python
# In cache_helper.py
import gateway

def get_or_compute(key, compute_fn):
    cached = gateway.cache_get(key)
    if gateway.cache_is_sentinel(cached):
        value = compute_fn()
        gateway.cache_set(key, value)
        return value
    return cached
```

**Optimized code:**
```python
# In cache_helper.py
from cache_core import get_value, set_value, _CACHE_MISS

def get_or_compute(key, compute_fn):
    cached = get_value(key)
    if cached is _CACHE_MISS:
        value = compute_fn()
        set_value(key, value)
        return value
    return cached
```

**Impact:**
- Original: ~15µs per call (3 gateway hops)
- Optimized: ~5µs per call (direct calls)
- Savings: ~10µs per call
- For 1000 calls: saved 10ms

Not huge, but adds up in hot paths.

---

## How to Identify

**Code smells:**
- `import gateway` in files that are part of a core interface
- Gateway calls that could be direct
- Performance profiling shows gateway overhead in hot paths

**When it matters:**
- Hot code paths (called 100+ times per request)
- Performance-critical sections
- Inner loops

**When it doesn't matter:**
- Code called once per request
- Initialization code
- Error handling paths

---

## Balancing Act

**Use gateway when:**
- Uncertain if same interface (gateway always safe)
- Code might move between interfaces later
- Readability more important than 1µs
- Consistency valued over micro-optimization

**Use direct calls when:**
- Certain it's same interface
- Performance matters
- Code structure stable
- Following established patterns in that interface

---

## Related Topics

- **RULE-01**: Gateway-only imports (clarifies "cross-interface")
- **AP-01**: Direct cross-interface imports (the critical violation)
- **LESS-02**: Measure don't guess (profile before optimizing)
- **NM02-CORE**: Dependency layers (what's same vs different interface)

---

## Keywords

same-interface, intra-interface, performance, micro-optimization, gateway overhead

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2024-10-15**: Anti-pattern documented in NM05-Anti-Patterns_Part_1.md

---

**File:** `NM05-AntiPatterns-Import_AP-03.md`
**End of Document**

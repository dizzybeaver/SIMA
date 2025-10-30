# NM06-Lessons-Performance_LESS-02.md - LESS-02

# LESS-02: Measure, Don't Guess

**Category:** Lessons  
**Topic:** Performance  
**Priority:** CRITICAL  
**Status:** Active  
**Created:** 2025-10-18  
**Last Updated:** 2025-10-23 (added filename header v3.1.0)

---

## Summary

Data-driven debugging with measurements finds exact problems in minutes, while guessing can take days or weeks and often leads to optimizing the wrong thing.

---

## Context

When investigating slow cold starts, the initial guess was "Python imports are slow." Measurement revealed the actual cause (sentinel leak) was completely different—535ms penalty from cache operations, not imports.

---

## Content

### The Wrong Approach

```python
# Typical developer thinking:
"Cold starts are slow, Python imports must be the problem"
→ Try to optimize imports
→ Add lazy loading
→ Complicate code
→ Problem persists (because wrong diagnosis)
```

### The Right Approach

```python
# Data-driven debugging:
import time

def lambda_handler(event, context):
    start = time.time()
    
    # Measure initialization
    init_start = time.time()
    initialize_system()
    init_time = time.time() - init_start
    print(f"Init: {init_time*1000:.1f}ms")
    
    # Measure cache operation
    cache_start = time.time()
    result = gateway.cache_get('key')
    cache_time = time.time() - cache_start
    print(f"Cache: {cache_time*1000:.1f}ms")
    
    total_time = time.time() - start
    print(f"Total: {total_time*1000:.1f}ms")
```

**Output revealed the truth:**
```
Init: 45ms     (expected ~50ms)
Cache: 535ms   (expected ~5ms) ← THE PROBLEM!
Total: 855ms
```

### What Measurement Revealed

**Guess:** "Imports are slow"  
**Reality:** "Cache sentinel leak causing 535ms penalty"

**Without measurement:**
- Would have optimized the wrong thing
- Added unnecessary complexity (lazy imports)
- Problem would have persisted
- Wasted hours on wrong solution

**With measurement:**
- Found exact problem in minutes
- Fixed root cause
- Simple solution (sanitization)
- 535ms improvement immediately

### Key Principles

**1. Measure Before Optimizing**
```python
# ❌ Don't do this:
"This seems slow, let me optimize"

# ✅ Do this:
start = time.time()
result = operation()
elapsed = time.time() - start
print(f"Operation took {elapsed*1000:.1f}ms")
# Now you know if optimization is needed
```

**2. Measure Multiple Points**
```python
# ✅ Multiple measurements:
├─ Import time: 45ms
├─ Initialize time: 120ms
├─ Cache time: 535ms ← Found it!
└─ Process time: 155ms
```

**3. Compare Against Baselines**
```python
# Compare against expected:
expected_cache = 5ms
actual_cache = 535ms
penalty = 535 - 5 = 530ms ← Problem identified!
```

### Real-World Impact

**Sentinel bug discovery:**
- Time to find with guessing: Could have been days/weeks
- Time to find with measurement: 15 minutes
- Accuracy: 100% (found exact cause)

**General pattern:**
```
Guessing approach:
├─ Try solution 1 (doesn't work)
├─ Try solution 2 (doesn't work)  
├─ Try solution 3 (doesn't work)
└─ Eventually give up or brute-force fix

Measurement approach:
├─ Measure (find exact problem)
├─ Fix exact problem
└─ Verify fix worked
```

### Implementation Template

```python
# Standard measurement pattern:
def measured_operation(name, func, *args, **kwargs):
    """Execute function with timing measurement."""
    start = time.time()
    try:
        result = func(*args, **kwargs)
        elapsed = time.time() - start
        print(f"{name}: {elapsed*1000:.1f}ms")
        return result
    except Exception as e:
        elapsed = time.time() - start
        print(f"{name} failed after {elapsed*1000:.1f}ms: {e}")
        raise

# Usage:
result = measured_operation(
    "Cache Get", 
    gateway.cache_get,
    'my-key'
)
```

---

## Related Topics

- **BUG-01**: Sentinel leak (discovered via measurement)
- **LESS-10**: Cold start monitoring (applies measurement principle)
- **DEC-05**: Sentinel sanitization (the fix measurement led to)
- **LESS-06**: Pay small costs early (cost-benefit analysis requires measurement)

---

## Keywords

measure don't guess, performance measurement, data-driven debugging, timing, profiling

---

## Version History

- **2025-10-23**: Added filename header (v3.1.0), updated "Last Updated" date
- **2025-10-18**: Created - Migrated to SIMA v3 individual file format
- **2025-10-15**: Original documentation in NM06-LESSONS-Core Architecture Lessons.md

---

**File:** `NM06-Lessons-Performance_LESS-02.md`  
**Directory:** NM06/  
**End of Document**

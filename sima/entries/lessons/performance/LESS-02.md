# File: LESS-02.md

**REF-ID:** LESS-02  
**Category:** Lessons Learned  
**Topic:** Performance  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Status:** Production

---

## Title

Measure, Don't Guess

---

## Priority

CRITICAL

---

## Summary

Data-driven debugging with measurements finds exact problems in minutes, while guessing can take days or weeks and often leads to optimizing the wrong thing.

---

## Context

When investigating slow startup times, the initial guess was "imports are slow." Measurement revealed the actual cause was completely different—data processing penalty, not imports.

---

## Lesson

### The Wrong Approach

```python
# Typical developer thinking:
"Startup is slow, imports must be the problem"
→ Try to optimize imports
→ Add lazy loading
→ Complicate code
→ Problem persists (because wrong diagnosis)
```

### The Right Approach

```python
# Data-driven debugging:
import time

def main():
    start = time.time()
    
    # Measure initialization
    init_start = time.time()
    initialize_system()
    init_time = time.time() - init_start
    print(f"Init: {init_time*1000:.1f}ms")
    
    # Measure data operation
    op_start = time.time()
    result = process_data('key')
    op_time = time.time() - op_start
    print(f"Operation: {op_time*1000:.1f}ms")
    
    total_time = time.time() - start
    print(f"Total: {total_time*1000:.1f}ms")
```

**Output revealed the truth:**
```
Init: 45ms     (expected ~50ms)
Operation: 535ms   (expected ~5ms) ← THE PROBLEM!
Total: 855ms
```

### What Measurement Revealed

**Guess:** "Imports are slow"  
**Reality:** "Data processing bug causing 535ms penalty"

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
├─ Process time: 535ms ← Found it!
└─ Finalize time: 155ms
```

**3. Compare Against Baselines**
```python
# Compare against expected:
expected = 5ms
actual = 535ms
penalty = 535 - 5 = 530ms ← Problem identified!
```

### Real-World Impact

**Bug discovery:**
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
    cache_get,
    'my-key'
)
```

---

## Related

**Cross-References:**
- LESS-06: Pay small costs early (cost-benefit requires measurement)
- WISD-02: Measure don't guess wisdom
- BUG-01: Example of measurement-driven debugging

**Keywords:** measure don't guess, performance measurement, data-driven debugging, timing, profiling

---

## Version History

- **4.0.0** (2025-10-30): Genericized for SIMAv4
- **3.1.0** (2025-10-23): SIMAv3 format
- **3.0.0** (2025-10-18): Initial creation

---

**END OF FILE**

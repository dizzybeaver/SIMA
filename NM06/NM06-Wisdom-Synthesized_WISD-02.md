# NM06-Wisdom-Synthesized_WISD-02.md - WISD-02

# WISD-02: Measure, Don't Guess

**Category:** Lessons
**Topic:** Synthesized Wisdom
**Priority:** High
**Status:** Active
**Created:** 2025-10-23
**Last Updated:** 2025-10-23

---

## Summary

Data-driven decisions beat intuition every time. Measurement reveals truth that guessing obscures. When facing performance issues or debugging problems, measure first, optimize second.

---

## Context

This wisdom crystallized from BUG-01 discovery, where timing measurements revealed a 535ms penalty that intuition would never have found. Multiple experiences showed that measurement consistently outperforms guessing in identifying problems, validating solutions, and guiding decisions.

---

## Content

### The Pattern

**Guessing Approach:**
```
Problem occurs → Guess root cause → Try solution → 
Might work? → Try another → Eventually give up
```

**Measurement Approach:**
```
Problem occurs → Measure system → Identify exact cause → 
Apply targeted fix → Measure to verify → Done
```

**The Key Insight:** Measurement saves time and guarantees correctness.

### Why It Matters

**Human Intuition is Limited:**
- Can't perceive milliseconds accurately
- Biased by recent experiences
- Influenced by expectations
- Often wrong about performance

**Measurement is Objective:**
- Reveals actual behavior
- Quantifies impact precisely
- Detects subtle issues
- Provides proof of improvement

### When to Apply

**Always measure when:**
- Debugging performance issues
- Optimizing code
- Validating fixes
- Choosing between approaches
- Detecting regressions

**Never rely on intuition for:**
- Timing (humans perceive it wrong)
- Performance bottlenecks (surprising locations)
- Memory usage (invisible to eye)
- Error rates (sampling bias)

### Examples

**Example 1: Sentinel Leak Discovery (BUG-01)**
```python
# Without measurement:
"Lambda seems slow... maybe it's the cache?"
"Let's optimize the cache algorithm"
[Wastes time on wrong problem]

# With measurement:
gateway.log_info(f"Init time: {init*1000:.1f}ms")
gateway.log_info(f"Cache time: {cache*1000:.1f}ms")

# Output reveals:
# Init: 45ms ✓
# Cache: 535ms ← Found it!
```

**Example 2: Optimization Decisions**
```python
# Without measurement (guessing):
"Dictionary lookup is probably faster than list search"
[Optimizes dictionary, gains 0.01ms]

# With measurement (data-driven):
Measured: List search takes 0.01ms for 10 items
Measured: Dict lookup takes 0.005ms
Measured: List creation overhead: 0.1ms
Decision: Use list (total time lower)
```

**Example 3: Regression Detection**
```python
# Without measurement:
"Code change looks fine, should be good"
[Deploys 20% slower code unknowingly]

# With measurement:
Before: Cold start 320ms
After: Cold start 385ms
Alert: "Regression detected: +65ms"
[Investigate before deploying]
```

### Universal Principle

**"In God we trust, all others bring data"**

Steps for data-driven debugging:
1. Add timing logs to measure operations
2. Identify slowest operation (data shows truth)
3. Focus optimization effort there
4. Measure improvement (verify it worked)
5. Repeat until performance acceptable

### Measurement Techniques

**Performance:**
```python
start = time.time()
result = operation()
elapsed = time.time() - start
gateway.log_info(f"Operation: {elapsed*1000:.1f}ms")
```

**Memory:**
```python
import sys
size = sys.getsizeof(object)
gateway.log_info(f"Size: {size} bytes")
```

**Error Rates:**
```python
gateway.metrics_record('operation.success', 1)
gateway.metrics_record('operation.failure', 0)
```

---

## Related Topics

- **LESS-02**: Measure don't guess (primary source)
- **BUG-01**: Sentinel leak (discovered through measurement)
- **DEC-05**: Sentinel sanitization (fix validated by measurement)
- **LESS-10**: Cold start monitoring (continuous measurement)
- **WISD-03**: Small costs early (ROI requires measurement)

---

## Keywords

measurement, data-driven, performance, debugging, timing, metrics, profiling

---

## Version History

- **2025-10-23**: Created from synthesis of LESS-02 and BUG-01 experience

---

**File:** `NM06-Wisdom-Synthesized_WISD-02.md`
**End of Document**

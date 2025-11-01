# File: WISD-02.md

**REF-ID:** WISD-02  
**Category:** Generic Lessons  
**Type:** Synthesized Wisdom  
**Version:** 1.0.0  
**Created:** 2025-10-23  
**Updated:** 2025-10-30  
**Status:** Active

---

## Summary

Data-driven decisions beat intuition every time. Measurement reveals truth that guessing obscures. When facing performance issues or debugging problems, measure first, optimize second.

---

## The Pattern

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

**Key Insight:** Measurement saves time and guarantees correctness.

---

## Why It Matters

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

---

## When to Apply

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

---

## Examples

### Example 1: Sentinel Leak Discovery
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

### Example 2: Optimization Decisions
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

### Example 3: Regression Detection
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

---

## Universal Principle

**"In God we trust, all others bring data"**

**Steps for data-driven debugging:**
1. Add timing logs to measure operations
2. Identify slowest operation (data shows truth)
3. Focus optimization effort there
4. Measure improvement (verify it worked)
5. Repeat until performance acceptable

---

## Measurement Techniques

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

## Application Guidelines

1. **Instrument early**
   - Add timing logs from the start
   - Measure key operations
   - Track critical metrics

2. **Measure before optimizing**
   - Profile to find bottlenecks
   - Don't guess at problems
   - Verify assumptions with data

3. **Validate improvements**
   - Measure before and after
   - Quantify gains
   - Ensure no regressions

4. **Continuous monitoring**
   - Track performance over time
   - Detect anomalies early
   - Alert on degradation

---

## Related References

**Bugs:**
- BUG-01: Sentinel leak (discovered through measurement)

**Decisions:**
- DEC-05: Sentinel sanitization (fix validated by measurement)

**Lessons:**
- LESS-02: Measure don't guess
- LESS-10: Cold start monitoring
- LESS-20: Performance measurement

**Wisdom:**
- WISD-03: Small costs early (ROI requires measurement)

---

## Keywords

measurement, data-driven, performance, debugging, timing, metrics, profiling, instrumentation

---

## Cross-References

**Synthesizes From:** LESS-02, LESS-10, LESS-20, BUG-01, DEC-05  
**Related To:** WISD-03 (measuring ROI), WISD-01 (architectural measurement)  
**Applied In:** All performance optimization, debugging

---

**End of WISD-02**

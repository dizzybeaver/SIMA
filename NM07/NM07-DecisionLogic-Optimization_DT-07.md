# NM07-DecisionLogic-Optimization_DT-07.md - DT-07

# DT-07: Should I Optimize This Code?

**Category:** Decision Logic
**Topic:** Optimization
**Priority:** Medium
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Decision tree for optimization decisions - ensuring measurements are taken first, code is on hot path, and optimization provides significant benefit without excessive complexity.

---

## Context

"Premature optimization is the root of all evil" - Donald Knuth. Optimization should be driven by measurements, not assumptions, and focused on hot paths with significant impact.

---

## Content

### Decision Tree

```
START: Considering optimization
│
├─ Q: Have you measured it?
│  ├─ NO → Measure first
│  │      Tools: time.time(), cProfile
│  │      Don't optimize without data
│  │      → END
│  │
│  └─ YES → Continue
│
├─ Q: Is it on critical path (hot path)?
│  ├─ NO → Don't optimize
│  │      Rationale: Not worth complexity
│  │      → END
│  │
│  └─ YES → Continue
│
├─ Q: What % of total time is it?
│  ├─ <5% → Don't optimize
│  │      Rationale: Small impact
│  │      → END
│  │
│  └─ >5% → Continue
│
├─ Q: Will optimization significantly complicate code?
│  ├─ YES → Reconsider
│  │      Trade-off: Complexity vs performance
│  │      Document if proceeding
│  │      → END or Continue
│  │
│  └─ NO → Continue
│
└─ Decision: Optimize
   Steps:
   1. Document current performance
   2. Implement optimization
   3. Measure improvement
   4. Document trade-offs
   5. Add performance test
   → END
```

### Optimization Priority Guide

| Optimization | When to Do | Complexity | Impact |
|--------------|-----------|------------|--------|
| Dispatch dicts vs if/elif | Always | Low | Medium |
| Lazy loading | Always | Low | Medium |
| Fast path caching | Hot operations | Medium | High |
| Algorithm improvement | Measured bottleneck | Varies | High |
| Micro-optimizations | Never | High | Low |

### Key Principles

**1. Measure First, Optimize Second**
```python
# ✅ Correct: Measure before optimizing
import time

start = time.time()
result = slow_function()
duration = time.time() - start
print(f"Function took {duration:.3f}s")

# If >5% of total time and >50ms → Consider optimization
```

**2. Focus on Hot Paths**
```python
# ✅ Optimize: Called 1000x per request
def hot_path_function():
    # This is worth optimizing
    pass

# ❌ Don't optimize: Called once per request
def cold_path_function():
    # Not worth complexity
    pass
```

**3. Avoid Premature Optimization**
```python
# ❌ Wrong: Optimizing without measurement
def calculate_value(x):
    # Spend hours micro-optimizing this
    # Without knowing if it matters
    pass

# ✅ Right: Profile first, then optimize
# 1. Run profiler
# 2. Find actual bottleneck
# 3. Optimize that
```

### Optimization Workflow

**Step 1: Profile**
```python
import cProfile
import pstats

profiler = cProfile.Profile()
profiler.enable()

# Run code
execute_operation()

profiler.disable()
stats = pstats.Stats(profiler)
stats.sort_stats('cumulative')
stats.print_stats(10)  # Top 10 functions
```

**Step 2: Identify Bottleneck**
```
Function took 500ms total:
- function_a: 400ms (80%) ← OPTIMIZE THIS
- function_b: 50ms (10%)
- function_c: 50ms (10%)
```

**Step 3: Optimize**
```python
def optimized_function_a():
    """Optimized version with 100ms improvement."""
    # Document what changed and why
    # OPTIMIZATION: Changed from O(n²) to O(n)
    # Before: 400ms, After: 300ms
    pass
```

**Step 4: Measure Improvement**
```python
# Before optimization: 400ms
# After optimization: 300ms
# Improvement: 100ms (25% faster)
# Trade-off: Slightly more complex algorithm
```

**Step 5: Add Performance Test**
```python
def test_function_a_performance():
    """Ensure optimization remains effective."""
    import time
    
    start = time.time()
    result = optimized_function_a()
    duration = time.time() - start
    
    assert duration < 0.35  # 350ms with buffer
```

### Real-World Usage Pattern

**User Query:** "Should I optimize this function?"

**Search Terms:** "should optimize decision"

**Decision Flow:**
1. Have you measured? If NO → Measure first
2. On hot path? If NO → Don't optimize
3. >5% of time? If NO → Don't optimize
4. Adds complexity? If YES → Reconsider
5. **Response:** "Measure first. If <5% of total time or not on hot path, don't optimize."

### Optimization Examples

**Worth Optimizing:**
```python
# Hot path, measured 200ms, 40% of total time
# Simple optimization available
def process_request(request):
    # Change: List comprehension → Generator
    # Before: results = [process(item) for item in items]
    # After: results = (process(item) for item in items)
    # Improvement: 200ms → 50ms
    pass
```

**Not Worth Optimizing:**
```python
# Cold path, measured 10ms, 1% of total time
# Would require complex refactoring
def rarely_called_function():
    # Leave as-is, not worth complexity
    pass
```

---

## Related Topics

- **DEC-13**: Fast path optimization design
- **LESS-02**: Measure first, don't guess
- **AP-12**: Premature optimization anti-pattern
- **FW-01**: Cache vs compute framework
- **DT-10**: Should refactor decision

---

## Keywords

should optimize, optimize code, performance decision, hot path, measurement, premature optimization

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-Optimization_DT-07.md`
**End of Document**

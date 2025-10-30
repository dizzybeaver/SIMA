# NM07-DecisionLogic-Optimization_FW-02.md - FW-02

# FW-02: Optimize or Document Trade-off Framework

**Category:** Decision Logic
**Topic:** Optimization
**Priority:** Framework
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Framework for deciding between optimizing slow code vs documenting why it's slow - balancing performance gains against code complexity increases.

---

## Context

Not all slow code should be optimized. Sometimes documenting the slowness and explaining the trade-offs is the better choice than adding complexity.

---

## Content

### Framework Formula

```
Decision: Should I optimize slow code or document why it's slow?

Calculate complexity:
- Hours to optimize: O hours
- Performance gain: G%
- Code complexity increase: C (1-10 scale)

If (G > 20% AND C < 5) → Optimize
If (G < 10%) → Document, don't optimize
Else → Consider case-by-case
```

### Decision Matrix

| Gain (G%) | Complexity (C) | Hours (O) | Decision |
|-----------|----------------|-----------|----------|
| 50% | 2 | 4 | Optimize |
| 30% | 3 | 6 | Optimize |
| 25% | 5 | 8 | Consider |
| 20% | 6 | 10 | Document |
| 15% | 4 | 5 | Document |
| 10% | 2 | 2 | Document |
| 5% | 1 | 1 | Document |

### Documentation Template

**If NOT optimizing, document:**

```python
# DESIGN DECISION: This loop is intentionally slow
# 
# Current Performance: 50ms for 100 items (0.5ms per item)
# 
# Why Not Optimized:
# - Performance impact acceptable for use case (cold path)
# - Optimization would require complex data structures
# - Trade-off: 20ms savings not worth 200 lines of complexity
# 
# Alternative Considered:
# - Hash table lookup: 30ms (40% faster)
# - Complexity increase: 7/10
# - Decision: Not worth it
# 
# Revisit if:
# - Becomes hot path (>100 calls/request)
# - Performance degrades (>100ms)
# - Simpler optimization discovered

def intentionally_slow_function(items):
    """Process items with linear search (documented trade-off)."""
    results = []
    for item in items:
        for candidate in candidates:  # O(n*m) - acceptable here
            if item == candidate:
                results.append(item)
    return results
```

### Example Scenarios

**Scenario 1: Should Optimize**
```
Context: Hot path function
- Current: 100ms (20% of total time)
- Optimized: 50ms (50% faster)
- Complexity: 3/10 (moderate refactoring)
- Hours: 4

Decision: OPTIMIZE
Rationale: High gain (50%), low complexity, reasonable time
```

**Scenario 2: Should Document**
```
Context: Cold path function
- Current: 50ms (2% of total time)
- Optimized: 40ms (20% faster)
- Complexity: 7/10 (significant complexity)
- Hours: 12

Decision: DOCUMENT
Rationale: Low gain relative to complexity, rarely called
```

**Scenario 3: Borderline**
```
Context: Medium frequency function
- Current: 80ms (15% of total time)
- Optimized: 60ms (25% faster)
- Complexity: 5/10 (moderate complexity)
- Hours: 8

Decision: CONSIDER
- If time available: Optimize
- If time constrained: Document
- Re-evaluate in 3 months
```

### Complexity Scale Reference

**1-2: Trivial**
- Variable renaming
- Simple algorithm swap
- Using built-in instead of custom

**3-4: Simple**
- Adding cache layer
- Refactoring loop structure
- Using better data structure

**5-6: Moderate**
- Algorithm redesign
- Adding new abstractions
- Significant refactoring

**7-8: Complex**
- Multiple file changes
- New dependencies
- Architectural changes

**9-10: Very Complex**
- System redesign
- Breaking changes
- Multi-week effort

### Real-World Usage Pattern

**User Query:** "This function is slow but optimization is complex. What should I do?"

**Analysis:**
```
Given:
- Current: 60ms
- After optimization: 40ms (33% gain)
- Complexity: 7/10
- Hours: 15

Formula:
- G = 33% (> 20%, good)
- C = 7 (> 5, high)

Decision: DOCUMENT
```

**Response:** "33% gain is good but complexity 7 is high. Document the trade-off and revisit if becomes hot path."

### Documentation Best Practices

**Include:**
1. Current measured performance
2. Why it's slow (algorithm, data structure, etc.)
3. Optimization considered
4. Why optimization rejected
5. Trade-off explanation
6. Conditions for revisiting

**Format:**
```python
# PERFORMANCE NOTE: Intentionally O(n²)
# Measured: 45ms for typical input (n=100)
# 
# Why slow: Nested loop for pattern matching
# Optimization: Could use regex (15ms)
# Rejected: Regex complexity for maintainers
# Trade-off: 30ms slower but 10x more readable
# 
# Revisit if: Input size grows to n>500
```

---

## Related Topics

- **DT-07**: Should I optimize decision tree
- **DT-10**: Should I refactor decision tree
- **LESS-02**: Measure first, don't guess
- **AP-20**: Unnecessary complexity anti-pattern

---

## Keywords

optimize vs document, performance decision, complexity trade-off, code simplicity, technical debt

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-Optimization_FW-02.md`
**End of Document**

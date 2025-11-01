# LESS-50.md

# Interface Starting Points Vary Dramatically

**REF-ID:** LESS-50  
**Category:** Lessons → Optimization  
**Priority:** 🟡 HIGH  
**Created:** 2025-10-30  
**Status:** Active

---

## Summary

Always assess component current state before estimating effort - starting points can vary by 85%, dramatically affecting work required. A 5-minute assessment can reveal 60-90% time savings.

---

## Context

**Universal Pattern:**
Components have vastly different optimization starting points, requiring different effort levels despite same end goal.

**Why This Matters:**
Never assume uniform starting points across similar components. Assessment prevents wasted effort and enables accurate planning.

---

## Content

### Starting Point Variance Example

| Component | Starting State | Completion | Effort Needed |
|-----------|---------------|------------|---------------|
| **Component A** | 30% | 100% | Very High (full work) |
| **Component B** | 70% | 90% | Low (additions only) |
| **Component C** | 85% | 95% | Very Low (minor additions) |

### Assessment Protocol (5 minutes)

```python
# Quick check for each component:
✓ Threading locks present?
✓ Pattern registration?
✓ Rate limiting?
✓ Memory limits?
✓ Security validations?
✓ Reset operation?
✓ Operations implemented?

Score: Count ✓ marks
0-2: Need everything (100% effort)
3-4: Need most (60-80% effort)
5-6: Need some (20-40% effort)
7: Need minimal (5-15% effort)
```

### Estimation Adjustment

| Score | Effort Multiplier | Example |
|-------|------------------|---------|
| 0-2 (Low) | 1.0× | 2 hours baseline |
| 3-4 (Medium) | 0.6-0.8× | 1.2-1.6 hours |
| 5-6 (High) | 0.2-0.4× | 0.4-0.8 hours |
| 7 (Very High) | 0.05-0.15× | 0.1-0.3 hours |

### Real Example

```
Initial Plan (no assessment):
- Component B: 2 hours
- Component C: 2 hours
- Total: 4 hours

After Assessment:
- Component B: 70% done → 0.6 hours
- Component C: 85% done → 0.4 hours
- Total: 1 hour (75% time savings!)

Actual:
- Component B: 1 hour
- Component C: 0.5 hours
- Total: 1.5 hours (62% time savings)
```

### Key Insight

**Never assume uniform starting points across similar components. A 5-minute assessment can reveal 60-90% time savings.**

---

## Related Topics

- **LESS-02**: Measure, don't guess
- **LESS-49**: Reference implementation accelerates replication
- **LESS-51**: Phase 2 often unnecessary if already optimized

---

## Keywords

assessment-protocol, starting-points, effort-estimation, time-savings, variance-management, pre-flight-check

---

**File:** `/sima/entries/lessons/optimization/LESS-50.md`  
**Lines:** ~90  
**Status:** Complete

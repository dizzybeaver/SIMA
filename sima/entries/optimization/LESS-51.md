# LESS-51.md

# Phase 2 Often Unnecessary If Already Optimized

**REF-ID:** LESS-51  
**Category:** Lessons → Optimization  
**Priority:** 🟡 HIGH  
**Created:** 2025-10-30  
**Status:** Active

---

## Summary

Phase 2 (performance optimizations) should be data-driven and optional, not mandatory. Skipping unnecessary optimizations saves 15-20 hours per component. "Don't optimize what's not broken" - spend 8 minutes assessing to avoid 15-20 hours unnecessary work.

---

## Context

**Universal Pattern:**
Not all components need performance optimization. Components already performant should skip Phase 2, saving significant time.

**Why This Matters:**
Saved 30-40 hours across 2 components by recognizing when optimization is unnecessary.

---

## Content

### When to Skip Phase 2

**✅ Skip Phase 2 if:**
- No performance bottlenecks identified
- Already has fast paths or caching
- Memory management already optimal
- Code is clean and maintainable
- Metrics show acceptable performance
- Would add complexity for marginal gain

**❌ Do Phase 2 if:**
- Bottlenecks identified in profiling
- Duplicated code (250+ LOC removable)
- No fast paths for common operations
- Metrics show poor performance
- Clear optimization opportunities
- Data shows 30%+ potential improvement

### Time Investment

| Phase | Time | Value If Needed | Value If Not Needed |
|-------|------|----------------|---------------------|
| Phase 1 | 2-4 hours | High (critical) | High (critical) |
| Phase 2 | 15-20 hours | High (if bottleneck) | **Low (waste)** |
| Phase 3 | 2-3 hours | High (observability) | High (observability) |

### Decision Protocol

```
Before starting component optimization:

1. Assess current performance (5 min)
   - Run existing benchmarks
   - Check for obvious bottlenecks
   - Review code for duplication
   - Check memory patterns

2. Decide Phase 2 necessity (2 min)
   - Skip if: 5/7 checks pass
   - Do if: <3/7 checks pass
   - Defer if: 3-4/7 checks pass (revisit later)

3. Communicate decision (1 min)
   - Document why skipped
   - Note conditions that would trigger
   - Set metrics to monitor

Total: 8 minutes to save 15-20 hours
```

### ROI

- Assessment time: 8 min
- Phase 2 time if done: 15-20 hours
- ROI: 112-150× return on assessment time

### Key Insight

**"Don't optimize what's not broken" - spend 8 minutes assessing to avoid 15-20 hours unnecessary work.**

---

## Related Topics

- **LESS-02**: Measure, don't guess
- **LESS-50**: Starting points vary dramatically
- **LESS-17**: Performance optimization patterns

---

## Keywords

performance-assessment, optimization-decisions, time-savings, data-driven, phase-skipping, roi-calculation

---

**File:** `/sima/entries/lessons/optimization/LESS-51.md`  
**Lines:** ~90  
**Status:** Complete

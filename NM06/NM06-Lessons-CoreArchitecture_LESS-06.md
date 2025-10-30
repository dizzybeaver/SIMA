# NM06-Lessons-CoreArchitecture_LESS-06.md - LESS-06

# LESS-06: Pay Small Costs Early

**Category:** Lessons  
**Topic:** CoreArchitecture  
**Priority:** HIGH  
**Status:** Active  
**Created:** 2025-10-23  
**Last Updated:** 2025-10-23

---

## Summary

Small preventive costs (like 0.5ms sanitization) are almost always worth paying to prevent large bug costs (like 535ms penalty + debugging hours).

---

## Context

When deciding whether to add sentinel sanitization (0.5ms overhead), we calculated the cost-benefit ratio and found that small preventive costs are almost always worth it compared to bug costs.

---

## Content

### The Calculation

**Cost of Sentinel Sanitization:**
- Per operation: 0.5ms
- Per request (20 ops): 10ms
- Overhead: 6.7%

**Cost of NOT Sanitizing:**
- Bug penalty: 535ms per cold start
- Amortized: 0.535ms per request
- Plus: Hours of debugging time

**Break-even:** After first cold start  
**Conclusion:** Pay the 0.5ms, prevent the bug

### General Principle

```python
# 1. Measure prevention cost
prevention_cost = measure_overhead_of_fix()

# 2. Estimate bug cost
bug_cost = frequency × impact × debug_time

# 3. Compare
if prevention_cost < bug_cost:
    implement_prevention()
```

### Real Examples

**Example 1: Input Validation (0.1-0.5ms)**
- Prevents type errors, missing field errors
- Debugging those: 15-30 minutes each
- Break-even: After preventing just ONE bug

**Example 2: Error Logging (1-2ms)**
- Cost: 1-2ms per error
- Benefit: 30 seconds vs 30 minutes to debug
- ROI: Massive

### When NOT to Pay Costs

**Premature optimization:**
- Don't add complexity for unmeasured problems
- Measure first, optimize if needed

**Excessive validation:**
- Validate at boundaries only
- Internal calls don't need re-validation

### The ROI Matrix

```
              Low Bug Cost    High Bug Cost
Low Prevention  Maybe          Definitely
High Prevention No             Probably
```

---

## Related Topics

- **BUG-01**: Sentinel leak (example of paying cost)
- **LESS-02**: Measure don't guess (measure both costs)

---

## Keywords

pay costs early, prevent debt, early investment, performance trade-offs, cost-benefit

---

## Version History

- **2025-10-23**: Created - Migrated to SIMA v3 individual file format
- **2025-10-15**: Original documentation in NM06-LESSONS-Core Architecture Lessons.md

---

**File:** `NM06-Lessons-CoreArchitecture_LESS-06.md`  
**Directory:** NM06/  
**End of Document**

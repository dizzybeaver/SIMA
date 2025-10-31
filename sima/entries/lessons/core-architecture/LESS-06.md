# File: LESS-06.md

**REF-ID:** LESS-06  
**Category:** Lessons Learned  
**Topic:** Core Architecture  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Status:** Production

---

## Title

Pay Small Costs Early

---

## Priority

HIGH

---

## Summary

Small preventive costs (like 0.5ms sanitization) are almost always worth paying to prevent large bug costs (like hours of debugging time or system failures).

---

## Context

When deciding whether to add data sanitization (small overhead), we calculated the cost-benefit ratio and found that small preventive costs are almost always worth it compared to bug costs.

---

## Lesson

### The Calculation

**Cost of Prevention:**
- Per operation: 0.5ms
- Per request (20 ops): 10ms
- Overhead: 6.7%

**Cost of NOT Preventing:**
- Bug penalty: 535ms per occurrence
- Amortized impact: 0.535ms per request
- Plus: Hours of debugging time

**Break-even:** After first bug occurrence  
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

**Example 3: Data Sanitization (0.5ms)**
- Cost: 0.5ms per operation
- Benefit: Prevents data leak bugs
- Break-even: First bug occurrence

### When NOT to Pay Costs

**Premature optimization:**
- Don't add complexity for unmeasured problems
- Measure first, optimize if needed

**Excessive validation:**
- Validate at boundaries only
- Internal calls don't need re-validation

**Over-engineering:**
- Don't add prevention for theoretical problems
- Focus on actual risks

### The ROI Matrix

```
              Low Bug Cost    High Bug Cost
Low Prevention  Maybe          Definitely
High Prevention No             Probably
```

### Key Principles

**1. Prevention is cheaper than cure**
- Small upfront cost prevents large downstream cost
- Debugging time is expensive
- System failures are very expensive

**2. Measure both sides**
- Don't guess prevention cost
- Don't guess bug cost
- Make data-driven decisions

**3. Start with high-ROI prevention**
- Input validation: Very high ROI
- Error logging: Very high ROI
- Type hints: Medium ROI
- Extensive testing: Medium to high ROI

---

## Related

**Cross-References:**
- LESS-02: Measure don't guess (calculate costs)
- WISD-03: Small costs early wisdom
- BUG-01: Example of prevention value

**Keywords:** pay costs early, prevent debt, early investment, performance trade-offs, cost-benefit

---

## Version History

- **4.0.0** (2025-10-30): Genericized for SIMAv4
- **3.0.0** (2025-10-23): Initial SIMAv3 format

---

**END OF FILE**

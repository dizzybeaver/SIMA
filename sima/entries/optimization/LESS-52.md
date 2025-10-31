# LESS-52.md

# Artifact Template Creation Accelerates Future Work

**REF-ID:** LESS-52  
**Category:** Lessons → Optimization  
**Priority:** 🟡 HIGH  
**Created:** 2025-10-30  
**Status:** Active

---

## Summary

Investing extra time creating high-quality templates in first implementation yields 244-375% ROI by accelerating all subsequent similar work. Accept 33% overhead on first implementation to gain 68% savings on all subsequent implementations.

---

## Context

**Universal Pattern:**
Creating reusable templates during first implementation dramatically accelerates all future similar work.

**Why This Matters:**
Time invested in creating reusable templates pays massive dividends across all future similar work.

---

## Content

### Template Reuse Velocity

| Session | First Time | With Template | Time Saved |
|---------|-----------|---------------|------------|
| 1 | 60-80 min | N/A (creating) | Baseline |
| 2 | N/A | 25-30 min | 55-60 min |
| 3 | N/A | 20-25 min | 55-60 min |
| 4 | N/A | 20 min | 60 min |
| 5 | N/A | 20 min | 60 min |

### Cumulative Savings

```
Sessions 2-6: 5 sessions × 55-60 min = 275-300 minutes saved
Time investment creating templates: 60-80 minutes
Net savings: 195-220 minutes (3.25-3.7 hours)
ROI: 244-375% return
```

### Template Quality Factors

**Good Template Characteristics:**
- ✅ Clear structure (easy to understand)
- ✅ Consistent patterns (predictable)
- ✅ Comprehensive (covers all cases)
- ✅ Well-commented (explains reasoning)
- ✅ Error handling (robust)
- ✅ Parameterized (easy to adapt)

**Poor Template Characteristics:**
- ❌ Magic numbers (hard to customize)
- ❌ Hard-coded values (not reusable)
- ❌ Missing documentation (unclear)
- ❌ No error handling (fragile)
- ❌ Component-specific logic (not generic)

### Time Allocation

```
First implementation:
- Functional code: 50 min
- Template extraction: +10 min
- Documentation: +10 min
- Testing template reuse: +10 min
Total: 80 min (33% overhead)

Each subsequent use:
- Adapt template: 15 min
- Customize: 5 min
- Test: 5 min
Total: 25 min (68% time savings)
```

### Key Insight

**Accept 33% overhead on first implementation to gain 68% savings on all subsequent implementations.**

### Best Practices

- Create template after first success, not during
- Test template with immediate second use
- Refine template if second use reveals issues
- Document customization points clearly
- Version templates as patterns evolve

---

## Related Topics

- **LESS-49**: Reference implementation accelerates replication
- **LESS-45**: First independent pattern application
- **LESS-28**: Pattern mastery accelerates development

---

## Keywords

template-creation, reusable-artifacts, roi-calculation, time-savings, pattern-extraction, velocity-acceleration

---

**File:** `/sima/entries/lessons/optimization/LESS-52.md`  
**Lines:** ~110  
**Status:** Complete

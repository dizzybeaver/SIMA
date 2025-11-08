# lmms-index-main.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Main index for LMMS architecture  
**Architecture:** LMMS (Python)

## LMMS ARCHITECTURE INDEX

**LMMS (Lazy Module Management System):** Performance optimization pattern that defers module imports until needed, reducing cold start time in resource-constrained environments.

---

## CORE CONCEPTS

### LMMS-01: Core Concept
**Location:** `/sima/languages/python/architectures/lmms/core/LMMS-01-Core-Concept.md`  
**Summary:** Foundation of lazy module management - import at function level instead of module level  
**Key Topics:** Lazy imports, cold start optimization, hot/cold paths  
**Status:** Complete

### LMMS-02: Cold Start Optimization
**Location:** `/sima/languages/python/architectures/lmms/core/LMMS-02-Cold-Start-Optimization.md`  
**Summary:** Strategies for optimizing cold start performance through selective lazy loading  
**Key Topics:** Performance measurement, import profiling, optimization techniques  
**Status:** Complete

### LMMS-03: Import Strategy
**Location:** `/sima/languages/python/architectures/lmms/core/LMMS-03-Import-Strategy.md`  
**Summary:** Decision framework and classification system for import optimization  
**Key Topics:** Tier system, decision tree, profiling workflow  
**Status:** Complete

---

## DECISIONS

### LMMS-DEC-01: Function-Level Imports
**Location:** `/sima/languages/python/architectures/lmms/decisions/LMMS-DEC-01-Function-Level-Imports.md`  
**Summary:** Decision to use function-level imports for >100ms or <20% usage modules  
**Rationale:** 60% cold start improvement, 39% memory reduction  
**Alternatives:** All module-level (rejected), all function-level (rejected), hybrid (chosen)  
**Status:** Complete

### LMMS-DEC-02: Hot Path Exceptions
**Location:** `/sima/languages/python/architectures/lmms/decisions/LMMS-DEC-02-Hot-Path-Exceptions.md`  
**Summary:** Allow module-level for >100ms modules if >80% usage frequency  
**Rationale:** Total performance > cold start alone, 9× better for frequent use  
**Alternatives:** Strict function-level (rejected), usage-based exceptions (chosen)  
**Status:** Complete

### LMMS-DEC-03: Import Profiling Required
**Location:** `/sima/languages/python/architectures/lmms/decisions/LMMS-DEC-03-Import-Profiling-Required.md`  
**Summary:** All LMMS optimizations must be based on actual profiling data  
**Rationale:** Assumptions unreliable, profiling prevents wasted effort  
**Alternatives:** Intuition-based (rejected), optimize everything (rejected), profile-driven (chosen)  
**Status:** Complete

### LMMS-DEC-04: Fast Path File Required
**Location:** `/sima/languages/python/architectures/lmms/decisions/LMMS-DEC-04-Fast-Path-File-Required.md`  
**Summary:** All projects must create fast_path.py with only hot path imports  
**Rationale:** Clear boundaries, prevents regressions, single source of truth  
**Alternatives:** Scattered imports (rejected), fast_path.py (chosen)  
**Status:** Complete

---

## LESSONS LEARNED

### LMMS-LESS-01: Profile First Always
**Location:** `/sima/languages/python/architectures/lmms/lessons/LMMS-LESS-01-Profile-First-Always.md`  
**Summary:** Never optimize without profiling - assumptions fail  
**Impact:** Wasted 10 hours optimizing wrong modules, only 15% improvement instead of 60%  
**Prevention:** Always profile before optimizing, verify measurements  
**Status:** Complete

### LMMS-LESS-02: Measure Impact Always
**Location:** `/sima/languages/python/architectures/lmms/lessons/LMMS-LESS-02-Measure-Impact-Always.md`  
**Summary:** Always measure before/after - unmeasured optimizations often have zero impact  
**Impact:** 12 hours spent, 0.1s improvement (2%), expected 2-3 seconds  
**Prevention:** Establish baselines, set targets, verify results  
**Status:** Complete

### LMMS-LESS-03: Hot Path Worth Cost
**Location:** `/sima/languages/python/architectures/lmms/lessons/LMMS-LESS-03-Hot-Path-Worth-Cost.md`  
**Summary:** Module-level for frequently-used heavy modules improves total performance  
**Impact:** 0.5s slower cold start but 0.7s faster P95, better user experience  
**Prevention:** Calculate total impact, not just cold start  
**Status:** Complete

### LMMS-LESS-04: Fast Path File Essential
**Location:** `/sima/languages/python/architectures/lmms/lessons/LMMS-LESS-04-Fast-Path-File-Essential.md`  
**Summary:** fast_path.py prevents performance regressions through visibility and review  
**Impact:** Prevented gradual degradation from 2.1s to 3.2s over 3 months  
**Prevention:** Create fast_path.py, enforce review process, automate tests  
**Status:** Complete

---

## ANTI-PATTERNS

### LMMS-AP-01: Premature Optimization
**Location:** `/sima/languages/python/architectures/lmms/anti-patterns/LMMS-AP-01-Premature-Optimization.md`  
**Description:** Optimizing imports without profiling data  
**Why Wrong:** Wastes effort, optimizes wrong targets, can make performance worse  
**Correct:** Profile first, use thresholds, data-driven decisions  
**Status:** Complete

### LMMS-AP-02: Over-Lazy-Loading
**Location:** `/sima/languages/python/architectures/lmms/anti-patterns/LMMS-AP-02-Over-Lazy-Loading.md`  
**Description:** Making all imports function-level, including fast/frequent ones  
**Why Wrong:** Overhead exceeds benefit, code complexity, testing complexity  
**Correct:** Apply selectively, use clear criteria, hot path exceptions  
**Status:** Complete

### LMMS-AP-03: Ignoring Metrics
**Location:** `/sima/languages/python/architectures/lmms/anti-patterns/LMMS-AP-03-Ignoring-Metrics.md`  
**Description:** Implementing LMMS without measuring before/after impact  
**Why Wrong:** Can't verify improvement, regressions unnoticed, wasted effort  
**Correct:** Always measure, continuous monitoring, track metrics  
**Status:** Complete

### LMMS-AP-04: Hot Path Heavy Imports
**Location:** `/sima/languages/python/architectures/lmms/anti-patterns/LMMS-AP-04-Hot-Path-Heavy-Imports.md`  
**Description:** Adding >100ms imports to fast_path.py without justification  
**Why Wrong:** Defeats LMMS purpose, no cost-benefit, silent degradation  
**Correct:** Strict discipline, document exceptions, enforce with tests  
**Status:** Complete

---

## QUICK REFERENCE

### When to Use LMMS

✅ **Use LMMS when:**
- Cold start time matters (user-facing APIs)
- Memory constrained (AWS Lambda 128MB)
- Modules are heavy (>100ms import time)
- Usage is sparse (cold path operations)

❌ **Don't use LMMS when:**
- Cold start doesn't matter (background jobs)
- Memory is abundant (EC2, containers)
- All modules lightweight (<10ms)
- All code runs every execution

### Classification Quick Guide

| Import Time | Usage | Strategy |
|-------------|-------|----------|
| <10ms | Any | Module level |
| 10-100ms | >80% | Module level (hot path) |
| 10-100ms | <80% | Function level |
| >100ms | >80% | Module level (exception)* |
| >100ms | <80% | Function level |

\* Requires documentation and approval

### Performance Targets

| Metric | Target | Excellent |
|--------|--------|-----------|
| Cold start | <3 seconds | <2 seconds |
| Hot path imports | <200ms | <100ms |
| Module-level imports | <10 | <5 |
| Improvement | >40% | >60% |

---

## CROSS-REFERENCES

### Related Architectures
- **SUGA:** Gateway pattern using lazy imports (GATE-02)
- **ZAPH:** Hot path optimization complements LMMS
- **DD-2:** Dependency disciplines help avoid circular imports

### Related Patterns
- **GATE-02:** Lazy Import Pattern (SUGA architecture)
- **ARCH-07:** Performance optimization patterns
- **DEC-07:** Dependencies <128MB constraint

### Related Lessons
- **LESS-02:** Measure Don't Guess (universal)
- **LESS-17:** Performance profiling (universal)
- **BUG-04:** Cold Start Spike (bug requiring LMMS)

---

## IMPLEMENTATION CHECKLIST

```
[ ] Profile all imports (LMMS-DEC-03)
[ ] Create fast_path.py (LMMS-DEC-04)
[ ] Classify imports by tier (LMMS-03)
[ ] Implement function-level for cold path (LMMS-DEC-01)
[ ] Document hot path exceptions (LMMS-DEC-02)
[ ] Measure baseline cold start
[ ] Apply optimizations
[ ] Verify >40% improvement (LMMS-LESS-02)
[ ] Add automated tests
[ ] Setup continuous monitoring
```

---

## KEYWORDS

lazy loading, import optimization, cold start, performance, AWS Lambda, serverless, memory management, profiling, hot path, cold path

---

**END OF FILE**

**Architecture:** LMMS (Lazy Module Management System)  
**Type:** Main Index  
**Files Indexed:** 15  
**Status:** Complete

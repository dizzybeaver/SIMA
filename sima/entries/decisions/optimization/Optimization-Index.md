# Optimization-Index.md

**Category:** Decision Logic  
**Subcategory:** Optimization  
**Files:** 3  
**Last Updated:** 2024-10-30

---

## Overview

Decision trees and frameworks for optimization decisions - when to optimize code, cache vs compute trade-offs, and optimize vs document decisions.

---

## Files in This Category

### DT-07: Should I Optimize This Code

**File:** `DT-07.md`  
**REF-ID:** DT-07  
**Priority:** Medium  
**Status:** Active

**Summary:** Decision tree for optimization decisions ensuring measurements are taken first, code is on hot path, and optimization provides significant benefit without excessive complexity.

**Key Decision Points:**
- Have you measured it?
- Is it on hot path?
- What % of total time?
- Will it complicate code?

**Use When:**
- Considering performance optimization
- Code seems slow
- Weighing complexity vs performance

### FW-01: Cache vs Compute Trade-off Framework

**File:** `FW-01.md`  
**REF-ID:** FW-01  
**Priority:** Framework  
**Status:** Active

**Summary:** Mathematical framework for cache vs compute performance trade-offs, calculating expected benefit based on computation cost, cache overhead, and hit rates.

**Formula:** Benefit = (C - L) × H
- C = Computation cost (ms)
- L = Lookup cost (ms)
- H = Hit rate (0-1)

**Use When:**
- Deciding whether to cache
- Evaluating caching benefit
- Quantifying performance trade-offs

### FW-02: Optimize or Document Trade-off Framework

**File:** `FW-02.md`  
**REF-ID:** FW-02  
**Priority:** Framework  
**Status:** Active

**Summary:** Framework for deciding between optimizing slow code versus documenting why it's slow, balancing performance gains against code complexity increases.

**Key Factors:**
- Performance gain % (G)
- Complexity increase (C, 1-10 scale)
- Hours to optimize (H)

**Use When:**
- Slow code but complex optimization
- Weighing maintainability vs speed
- Deciding technical debt priorities

---

## Quick Decision Guide

### Scenario 1: Code is Slow - What to Do?

**Path:**
1. Check **DT-07**: Should I Optimize This Code
   - Measure first
   - Verify hot path
   - Check % of total time
2. If optimization considered, check **FW-02**
   - Calculate gain vs complexity
   - Decide: Optimize or Document

### Scenario 2: Considering Caching

**Path:**
1. Check **FW-01**: Cache vs Compute Trade-off
   - Calculate: (C - L) × H
   - If benefit > 1ms → Cache
   - If benefit < 0.5ms → Don't cache

### Scenario 3: Complex Optimization Available

**Path:**
1. Check **FW-02**: Optimize or Document
   - Measure gain (G)
   - Assess complexity (C)
   - Estimate hours (H)
   - Decide based on matrix

---

## Related Categories

**Within Decision Logic:**
- **Feature Addition** (DT-04: Should This Be Cached)
- **Refactoring** (DT-10: Should I Refactor This Code)
- **Testing** (DT-08: What Should I Test)

**Other REF-IDs:**
- **DEC-13**: Fast Path Optimization (architecture decisions)
- **LESS-02**: Measure First Don't Guess (lessons)
- **LESS-17**: Performance Monitoring Patterns (lessons)
- **AP-12**: Premature Optimization (anti-pattern)
- **AP-20**: Unnecessary Complexity (anti-pattern)

---

## Common Questions

**Q: When should I optimize?**
**A:** See **DT-07** - Measure first, verify hot path, ensure >5% impact, low complexity.

**Q: Should I cache this 10ms operation?**
**A:** See **FW-01** - Calculate benefit using formula. If hit rate 60%, benefit ≈ 6ms → Cache.

**Q: Optimization is complex but gives 25% gain. What to do?**
**A:** See **FW-02** - If complexity > 5, consider documenting instead unless it's critical path.

**Q: How do I document when NOT optimizing?**
**A:** See **FW-02** examples for documentation template.

---

## Keywords

optimization, performance, caching, cache vs compute, hit rate, complexity trade-off, hot path, measurement, premature optimization, code efficiency, performance analysis, technical debt

---

## Navigation

**Parent:** Decision Logic Master Index  
**Siblings:** Import, Feature Addition, Error Handling, Testing, Refactoring, Deployment, Architecture, Meta

**Location:** `/sima/entries/decisions/optimization/`

---

**End of Index**

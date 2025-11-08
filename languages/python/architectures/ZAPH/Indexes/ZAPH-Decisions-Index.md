# File: ZAPH-Decisions-Index.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Category:** Python/Architectures/ZAPH/Indexes  
**Status:** Active

---

## ZAPH DECISIONS INDEX

Quick reference to all ZAPH architecture decisions.

---

## DECISION DIRECTORY

### DEC-01: Three-Tier Access Classification

**File:** `ZAPH-DEC-01-Tiered-Access-System.md`  
**Decision:** Implement three-tier access classification system

**Summary:** All operations classified by access frequency:
- Tier 1 (Hot): >80% access
- Tier 2 (Warm): 20-80% access
- Tier 3 (Cold): <20% access

**When to Use:** Every new operation needs tier assignment  
**Key Benefit:** Clear optimization priorities  
**Related:** ZAPH-DEC-02, ZAPH-DEC-03, ZAPH-DEC-04

---

### DEC-02: Zero-Abstraction Hot Paths

**File:** `ZAPH-DEC-02-Zero-Abstraction-Hot-Path.md`  
**Decision:** Tier 1 operations use zero-abstraction implementation

**Summary:** Hot paths (>80% access) must eliminate all overhead:
- No validation
- No error handling
- No logging
- No abstraction layers
- Direct implementation only

**When to Use:** Operations with sustained >80% access frequency  
**Key Benefit:** 40% latency reduction in hot paths  
**Related:** ZAPH-DEC-01, ZAPH-LESS-04 (wrapper pattern)

---

### DEC-03: Tier Promotion and Demotion Rules

**File:** `ZAPH-DEC-03-Tier-Promotion-Demotion.md`  
**Decision:** Systematic tier movement rules with measurable triggers

**Summary:** Hysteresis + stability periods prevent thrashing:
- Promotion: >80% for 1 week (Tier 2→1)
- Demotion: <75% for 4 weeks (Tier 1→2)
- Lock periods: 4-8 weeks after changes
- Different thresholds up/down (hysteresis)

**When to Use:** Monitoring tier boundaries, considering tier changes  
**Key Benefit:** 95% reduction in tier churn  
**Related:** ZAPH-DEC-01, ZAPH-LESS-03, ZAPH-AP-03

---

### DEC-04: Mandatory Performance Profiling

**File:** `ZAPH-DEC-04-Performance-Profiling-Requirement.md`  
**Decision:** All tier assignments must be based on profiling data

**Summary:** Data-driven tier assignment requirements:
- 1,000+ requests minimum sample
- Production-like traffic patterns
- Statistical confidence >95%
- Re-profiling on tier changes

**When to Use:** Before any tier assignment or change  
**Key Benefit:** 95%+ classification accuracy  
**Related:** ZAPH-DEC-01, ZAPH-LESS-01, ZAPH-AP-01

---

## DECISION WORKFLOW

```
New Operation
â†"
Deploy with Tier 3 (default)
â†"
Collect profiling data (1,000+ requests) ← DEC-04
â†"
Calculate access frequency
â†"
Assign tier based on thresholds ← DEC-01
â†"
Apply tier-appropriate patterns
- Tier 1: Zero-abstraction ← DEC-02
- Tier 2: Standard patterns
- Tier 3: Heavy abstraction
â†"
Monitor for tier changes ← DEC-03
â†"
Promote/demote with stability checks ← DEC-03
```

---

## QUICK REFERENCE

**Need to assign a tier?** → Start with DEC-01, DEC-04  
**Hot path optimization?** → Read DEC-02  
**Tier change consideration?** → Check DEC-03  
**Profiling requirements?** → Reference DEC-04

---

## CROSS-REFERENCE MATRIX

| Decision | Inherits From | Related To | Used In |
|----------|---------------|------------|---------|
| DEC-01 | - | DEC-02, DEC-03, DEC-04 | All tier assignments |
| DEC-02 | DEC-01 | DEC-03, DEC-04 | All Tier 1 implementations |
| DEC-03 | DEC-01 | DEC-02, DEC-04 | Tier monitoring systems |
| DEC-04 | DEC-01 | DEC-02, DEC-03 | All tier assignments |

---

## DECISION TIMELINE

1. **DEC-01:** Three-Tier System (Foundation)
2. **DEC-04:** Profiling Requirement (Data Foundation)
3. **DEC-02:** Zero-Abstraction (Tier 1 Implementation)
4. **DEC-03:** Promotion/Demotion (Tier Management)

**Logical Order:** Establish tiers → Require measurements → Define Tier 1 patterns → Manage tier changes

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial index creation
- 4 decisions documented
- Cross-references established
- Decision workflow defined

---

**END OF DECISIONS INDEX**

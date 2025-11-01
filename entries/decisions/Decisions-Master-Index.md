# Decisions-Master-Index.md

**Version:** 1.1.0  
**REF-ID:** INDEX-DECISIONS-MASTER  
**Category:** Decision Logic  
**Total Entries:** 33 (17 DEC + 13 DT + 2 FW + 1 META)  
**Created:** 2024-10-30  
**Last Updated:** 2025-11-01

---

## Overview

Master index for all decision logic in SIMA - architectural decisions (DEC-##), decision trees (DT-##), frameworks (FW-##), and meta-frameworks (META-##). This is the primary navigation hub for understanding system decisions.

**Structure:**
- **Architecture Decisions** (DEC-01 to DEC-05): 5 entries - Foundation
- **Technical Decisions** (DEC-12 to DEC-19): 8 entries - Implementation
- **Operational Decisions** (DEC-20 to DEC-23): 4 entries - Runtime
- **Decision Trees** (DT-01 to DT-13): 13 entries - Applied Logic
- **Frameworks** (FW-01 to FW-02): 2 entries - Trade-off Analysis
- **Meta** (META-01): 1 entry - Decision Methodology

---

## Architecture Decisions (DEC-01 to DEC-05)

**From NM04 - Phase 10.1**

### DEC-01: SUGA Pattern Choice
**File:** `/sima/entries/decisions/architecture/` (from NM04)  
**Priority:** Critical  
**Summary:** Why three-layer gateway architecture - addresses Lambda constraints, enables evolution  
**Impact:** Foundation of entire architecture

### DEC-02: Gateway Centralization
**File:** `/sima/entries/decisions/architecture/` (from NM04)  
**Priority:** Critical  
**Summary:** All cross-interface imports via gateway - prevents circular dependencies  
**Impact:** Enforces RULE-01, enables clean testing

### DEC-03: Dispatch Dictionary Pattern
**File:** `/sima/entries/decisions/architecture/` (from NM04)  
**Priority:** High  
**Summary:** Dictionary routing over if/elif - O(1) lookups, clean extension  
**Impact:** 90% code reduction, predictable performance

### DEC-04: No Threading Locks
**File:** `/sima/entries/decisions/architecture/` (from NM04)  
**Priority:** High  
**Summary:** No locks in Lambda - single-threaded execution, YAGNI  
**Impact:** Simplicity, zero deadlock risk

### DEC-05: Sentinel Sanitization
**File:** `/sima/entries/decisions/architecture/` (from NM04)  
**Priority:** High  
**Summary:** Sanitize sentinels at router boundaries - prevents API pollution  
**Impact:** Prevented BUG-01 (535ms penalty)

---

## Technical Decisions (DEC-12 to DEC-19)

**From NM04 - Phase 10.1**

### DEC-12: Memory Management Strategy
**File:** `/sima/entries/decisions/technical/` (from NM04)  
**Priority:** High  
**Summary:** Multi-tier config loading - balance speed vs memory  
**Impact:** Optimal resource usage under 128MB limit

### DEC-13: Fast Path Optimization
**File:** `/sima/entries/decisions/technical/` (from NM04)  
**Priority:** High  
**Summary:** Cache hot paths - preload dispatch routes  
**Impact:** 40% operation speedup

### DEC-14: Lazy Module Loading
**File:** `/sima/entries/decisions/technical/` (from NM04)  
**Priority:** Medium  
**Summary:** Load heavy modules only when needed - reduce cold start  
**Impact:** 60ms cold start improvement

### DEC-15: Router Exception Handling
**File:** `/sima/entries/decisions/technical/` (from NM04)  
**Priority:** High  
**Summary:** Catch and log at router boundaries - error visibility  
**Impact:** 100% error visibility, graceful degradation

### DEC-16: Import Error Protection
**File:** `/sima/entries/decisions/technical/` (from NM04)  
**Priority:** Medium  
**Summary:** Protect against import failures - graceful degradation  
**Impact:** System stability with partial failures

### DEC-17: Flat File Structure
**File:** `/sima/entries/decisions/technical/` (from NM04)  
**Priority:** Medium  
**Summary:** Flat structure except home_assistant/ - simplicity  
**Impact:** Easy navigation, clear organization

### DEC-18: Standard Library Preference
**File:** `/sima/entries/decisions/technical/` (from NM04)  
**Priority:** Medium  
**Summary:** Favor stdlib over external dependencies - reduce package size  
**Impact:** Under 128MB Lambda limit

### DEC-19: Extension Pattern
**File:** `/sima/entries/decisions/technical/` (from NM04)  
**Priority:** Low  
**Summary:** Extension pattern for Home Assistant - separate concerns  
**Impact:** Clean HA integration

---

## Operational Decisions (DEC-20 to DEC-23)

**From NM04 - Phase 10.1**

### DEC-20: Environment-First Configuration
**File:** `/sima/entries/decisions/operational/` (from NM04)  
**Priority:** High  
**Summary:** LAMBDA_MODE env variable - extensible operational modes  
**Impact:** Flexible deployment strategies

### DEC-21: SSM Token-Only Optimization
**File:** `/sima/entries/decisions/operational/` (from NM04)  
**Priority:** Critical  
**Summary:** Load token only from SSM - massive cold start improvement  
**Impact:** 3,000ms (92%) cold start reduction

### DEC-22: DEBUG_MODE Flow Visibility
**File:** `/sima/entries/decisions/operational/` (from NM04)  
**Priority:** Medium  
**Summary:** Runtime debug toggle - instant debugging without redeploy  
**Impact:** Fast troubleshooting capability

### DEC-23: DEBUG_TIMINGS Performance Tracking
**File:** `/sima/entries/decisions/operational/` (from NM04)  
**Priority:** Medium  
**Summary:** Runtime timing toggle - data-driven optimization  
**Impact:** Performance profiling in production

---

## Decision Trees (DT-01 to DT-13)

**From NM07 - Phase 10.4**

### Import Decisions

#### DT-01: How to Import Functionality
**File:** `/sima/entries/decisions/import/DT-01.md`  
**Priority:** Critical  
**Summary:** Gateway vs direct import decision tree - enforces RULE-01  
**Use When:** Adding imports, enforcing architecture

#### DT-02: Where Should Function Go
**File:** `/sima/entries/decisions/import/DT-02.md`  
**Priority:** High  
**Summary:** Function placement decision tree - layer and interface selection  
**Use When:** Deciding code organization

### Feature Addition

#### DT-03: User Wants Feature X
**File:** `/sima/entries/decisions/feature-addition/DT-03.md`  
**Priority:** High  
**Summary:** Feature request decision tree - exists, fits existing, new interface  
**Use When:** Handling feature requests

#### DT-04: Should This Be Cached
**File:** `/sima/entries/decisions/feature-addition/DT-04.md`  
**Priority:** High  
**Summary:** Caching decision tree - cost, frequency, volatility, size  
**Use When:** Cache vs compute decisions

### Error Handling

#### DT-05: How to Handle This Error
**File:** `/sima/entries/decisions/error-handling/DT-05.md`  
**Priority:** High  
**Summary:** Error handling strategy decision tree - log, raise, return, sanitize  
**Use When:** Implementing error handling

#### DT-06: What Exception Type to Raise
**File:** `/sima/entries/decisions/error-handling/DT-06.md`  
**Priority:** Medium  
**Summary:** Exception type selection decision tree - standard vs custom  
**Use When:** Choosing exception types

### Optimization

#### DT-07: Should I Optimize This Code
**File:** `/sima/entries/decisions/optimization/DT-07.md`  
**Priority:** Medium  
**Summary:** Optimization decision tree - measure first, hot path, impact assessment  
**Use When:** Considering performance optimization

### Testing

#### DT-08: What Should I Test
**File:** `/sima/entries/decisions/testing/DT-08.md`  
**Priority:** High  
**Summary:** Test coverage decision tree - what to test first  
**Use When:** Planning test coverage

#### DT-09: How Much to Mock
**File:** `/sima/entries/decisions/testing/DT-09.md`  
**Priority:** Medium  
**Summary:** Mocking strategy decision tree - mock external, test behavior  
**Use When:** Designing test strategy

### Refactoring

#### DT-10: Should I Refactor This Code
**File:** `/sima/entries/decisions/refactoring/DT-10.md`  
**Priority:** Medium  
**Summary:** Refactoring decision tree - readability, duplication, violations  
**Use When:** Considering code improvements

#### DT-11: Extract to Function or Leave Inline
**File:** `/sima/entries/decisions/refactoring/DT-11.md`  
**Priority:** Low  
**Summary:** Function extraction decision tree - reuse, size, purpose  
**Use When:** Deciding function boundaries

### Deployment

#### DT-12: Should I Deploy This Change
**File:** `/sima/entries/decisions/deployment/DT-12.md`  
**Priority:** High  
**Summary:** Deployment readiness decision tree - tests, review, compatibility, rollback  
**Use When:** Before production deployment

### Architecture

#### DT-13: New Interface or Extend Existing
**File:** `/sima/entries/decisions/architecture/DT-13.md`  
**Priority:** High  
**Summary:** Interface architecture decision tree - new vs extend vs utility  
**Use When:** Growing system architecture

---

## Frameworks (FW-01 to FW-02)

**From NM07 - Phase 10.4**

### FW-01: Cache vs Compute Trade-off Framework
**File:** `/sima/entries/decisions/optimization/FW-01.md`  
**Priority:** Framework  
**Summary:** Mathematical framework for cache vs compute - (C - L) × H formula  
**Use When:** Quantifying caching benefit

### FW-02: Optimize or Document Trade-off Framework
**File:** `/sima/entries/decisions/optimization/FW-02.md`  
**Priority:** Framework  
**Summary:** Framework for optimization vs documentation - gain vs complexity  
**Use When:** Deciding whether to optimize

---

## Meta Framework (META-01)

**From NM07 - Phase 10.4**

### META-01: Meta Decision-Making Framework
**File:** `/sima/entries/decisions/meta/META-01.md`  
**Priority:** Framework  
**Summary:** 6-step decision methodology - context, data, alternatives, trade-offs, decision, learn  
**Use When:** Novel decisions, training, creating new decision trees

---

## Quick Reference by Category

### By Category

**Import:** DT-01, DT-02  
**Feature Addition:** DT-03, DT-04  
**Error Handling:** DT-05, DT-06  
**Optimization:** DT-07, FW-01, FW-02  
**Testing:** DT-08, DT-09  
**Refactoring:** DT-10, DT-11  
**Deployment:** DT-12  
**Architecture:** DEC-01 to DEC-05, DT-13  
**Technical:** DEC-12 to DEC-19  
**Operational:** DEC-20 to DEC-23  
**Meta:** META-01

### By Priority

**Critical:** DEC-01, DEC-02, DEC-21, DT-01  
**High:** DEC-03, DEC-04, DEC-05, DEC-12, DEC-13, DEC-15, DEC-20, DT-02, DT-03, DT-04, DT-05, DT-08, DT-12, DT-13  
**Medium:** DEC-14, DEC-16, DEC-17, DEC-18, DEC-22, DEC-23, DT-06, DT-07, DT-09, DT-10  
**Low:** DEC-19, DT-11  
**Framework:** FW-01, FW-02, META-01

---

## Navigation

**Category Indexes:**
- Architecture-Index.md (DEC-01 to DEC-05, DT-13)
- Technical-Index.md (DEC-12 to DEC-19)
- Operational-Index.md (DEC-20 to DEC-23)
- Import-Index.md (DT-01, DT-02)
- FeatureAddition-Index.md (DT-03, DT-04)
- ErrorHandling-Index.md (DT-05, DT-06)
- Optimization-Index.md (DT-07, FW-01, FW-02)
- Testing-Index.md (DT-08, DT-09)
- Refactoring-Index.md (DT-10, DT-11)
- Deployment-Index.md (DT-12)
- Meta-Index.md (META-01)

**Location:** `/sima/entries/decisions/`

---

## Keywords

decisions, decision-logic, decision-trees, frameworks, architecture, technical, operational, import, feature-addition, error-handling, optimization, testing, refactoring, deployment, meta-framework, SUGA, master-index

---

## Version History

- **2025-11-01 (v1.1.0):** Updated date to current
- **2024-10-30:** Updated with Phase 10.4 decision trees (DT-##, FW-##, META-##)
- **2024-10-30:** Created master index with Phase 10.1 decisions (DEC-##)
- **2024-10-24:** Initial SIMAv4 structure

---

**File:** `Decisions-Master-Index.md`  
**Location:** `/sima/entries/decisions/`  
**End of Master Index**

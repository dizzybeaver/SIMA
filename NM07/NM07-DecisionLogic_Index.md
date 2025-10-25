# NM07-DecisionLogic_Index.md

# NM07 - Decision Logic Index

**Category Number:** NM07
**Topics:** 9
**Individual Files:** 16 (13 DT + 2 FW + 1 META)
**Last Updated:** 2025-10-24

---

## Category Overview

**Purpose:** Documents WHEN TO DO WHAT in SUGA-ISP - decision trees, choice frameworks, and situational guidance for making good architectural and implementation decisions.

**Scope:** Applied wisdom for contextual decision-making across imports, features, errors, testing, optimization, refactoring, deployment, and architecture.

---

## Topics in This Category

### Import Decisions
- **Description:** How to import functionality and where to place functions
- **Items:** 2 (DT-01, DT-02)
- **Index:** `NM07-DecisionLogic-Import_Index.md`
- **Priority Items:** 
  - DT-01: How to import X (CRITICAL)
  - DT-02: Where function goes (HIGH)

### Feature Addition
- **Description:** When to add features and how to structure them
- **Items:** 2 (DT-03, DT-04)
- **Index:** `NM07-DecisionLogic-FeatureAddition_Index.md`
- **Priority Items:**
  - DT-03: User wants feature (HIGH)
  - DT-04: Should cache X (HIGH)

### Error Handling
- **Description:** How to handle errors and choose exception types
- **Items:** 2 (DT-05, DT-06)
- **Index:** `NM07-DecisionLogic-ErrorHandling_Index.md`
- **Priority Items:**
  - DT-05: Handle errors (HIGH)
  - DT-06: Exception types (MEDIUM)

### Testing
- **Description:** What to test and mocking strategies
- **Items:** 2 (DT-08, DT-09)
- **Index:** `NM07-DecisionLogic-Testing_Index.md`
- **Priority Items:**
  - DT-08: What to test (HIGH)
  - DT-09: How much mock (MEDIUM)

### Optimization
- **Description:** When to optimize and performance trade-offs
- **Items:** 3 (DT-07, FW-01, FW-02)
- **Index:** `NM07-DecisionLogic-Optimization_Index.md`
- **Priority Items:**
  - DT-07: Should optimize (MEDIUM)
  - FW-01: Cache vs compute (FRAMEWORK)

### Refactoring
- **Description:** When to refactor and extract functions
- **Items:** 2 (DT-10, DT-11)
- **Index:** `NM07-DecisionLogic-Refactoring_Index.md`
- **Priority Items:**
  - DT-10: Should refactor (MEDIUM)
  - DT-11: Extract function (LOW)

### Deployment
- **Description:** When to deploy changes to production
- **Items:** 1 (DT-12)
- **Index:** `NM07-DecisionLogic-Deployment_Index.md`
- **Priority Items:**
  - DT-12: Deploy decision (HIGH)

### Architecture
- **Description:** When to create new interfaces vs extending existing
- **Items:** 1 (DT-13)
- **Index:** `NM07-DecisionLogic-Architecture_Index.md`
- **Priority Items:**
  - DT-13: New interface decision (HIGH)

### Meta Decision-Making
- **Description:** Framework for making good decisions
- **Items:** 1 (META-01)
- **Index:** `NM07-DecisionLogic-Meta_Index.md`
- **Priority Items:**
  - META-01: Decision framework (FRAMEWORK)

---

## Quick Access

**Most Frequently Accessed:**
1. **DT-01**: How to import X (CRITICAL - import decisions)
2. **DT-03**: User wants feature (HIGH - feature addition)
3. **DT-05**: Handle errors (HIGH - error handling)
4. **DT-08**: What to test (HIGH - testing strategy)
5. **DT-12**: Deploy decision (HIGH - deployment)

---

## Cross-Category Relationships

**Decision Logic → Rules:**
- DT-01 (Import) ← RULE-01 (Gateway imports)
- DT-02 (Where function) ← RULE-02 (Interface isolation)

**Decision Logic → Decisions:**
- DT-04 (Cache) ← DEC-09 (Cache design)
- DT-07 (Optimize) ← DEC-13 (Fast path)
- DT-13 (New interface) ← DEC-01 (SUGA pattern)

**Decision Logic → Anti-Patterns:**
- DT-01 (Import) → AP-01 (Direct imports)
- DT-05 (Errors) → AP-14 (Bare except)
- DT-12 (Deploy) → AP-27 (Skip verification)

**Decision Logic → Lessons:**
- DT-07 (Optimize) → LESS-02 (Measure first)
- DT-10 (Refactor) → LESS-01 (Read complete)
- DT-12 (Deploy) → LESS-09 (Deploy checklist)

---

## Usage Patterns

**Common User Queries:**
- "How do I import X?" → DT-01
- "Where should this function go?" → DT-02
- "Should I add a new interface?" → DT-13
- "Should I cache this data?" → DT-04
- "What should I test?" → DT-08
- "Can I deploy this?" → DT-12

**Decision Workflows:**
1. Add feature: DT-03 → DT-02 → DT-13 → DT-08 → DT-12
2. Optimize code: DT-07 → DT-10 → DT-08 → DT-12
3. Handle error: DT-05 → DT-06 → DT-08

---

## Navigation

- **Up:** Master Index (NM00A-Master_Index.md)
- **Quick Lookup:** Quick Index (NM00-Quick_Index.md)
- **Related Categories:**
  - NM02 (Dependencies - import rules)
  - NM04 (Decisions - design rationale)
  - NM05 (Anti-Patterns - what not to do)
  - NM06 (Lessons - learned wisdom)

---

## Keywords

decision trees, frameworks, when to do what, imports, features, errors, testing, optimization, refactoring, deployment, architecture, meta-decisions

---

## Version History

- **2025-10-24**: Created SIMA v3 structure - migrated from v2 monolith

---

**End of Index**

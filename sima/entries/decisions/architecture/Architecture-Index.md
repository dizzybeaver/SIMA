# Architecture-Index.md

**Category:** Decision Logic  
**Subcategory:** Architecture  
**Files:** 6 (5 DEC + 1 DT)  
**Last Updated:** 2024-10-30

---

## Overview

Architecture decisions and decision trees - fundamental choices about system structure, interfaces, and architectural patterns.

---

## Files in This Category

### From NM04 (Architecture Decisions - DEC-##):

#### DEC-01: SUGA Pattern Choice

**File:** From NM04 - `NM04-Decisions-Architecture_DEC-01.md`  
**REF-ID:** DEC-01  
**Priority:** Critical  
**Status:** Active

**Summary:** Why SUGA (three-layer gateway architecture) was chosen over alternatives - addresses Lambda constraints, enables clean boundaries, and supports evolution.

**Use When:** Understanding overall architecture philosophy

#### DEC-02: Gateway Centralization

**File:** From NM04 - `NM04-Decisions-Architecture_DEC-02.md`  
**REF-ID:** DEC-02  
**Priority:** Critical  
**Status:** Active

**Summary:** Why all cross-interface imports go through gateway - prevents circular dependencies, enables clean testing, enforces boundaries.

**Use When:** Questions about import patterns, interface isolation

#### DEC-03: Dispatch Dictionary Pattern

**File:** From NM04 - `NM04-Decisions-Architecture_DEC-03.md`  
**REF-ID:** DEC-03  
**Priority:** High  
**Status:** Active

**Summary:** Why dispatch dictionaries over if/elif chains - O(1) lookups, clean extension, predictable performance.

**Use When:** Implementing operation routing, extending interfaces

#### DEC-04: No Threading Locks

**File:** From NM04 - `NM04-Decisions-Architecture_DEC-04.md`  
**REF-ID:** DEC-04  
**Priority:** High  
**Status:** Active

**Summary:** Why no locks in Lambda - single-threaded execution, YAGNI principle, simplicity over premature optimization.

**Use When:** Concurrency questions, thread safety concerns

#### DEC-05: Sentinel Sanitization

**File:** From NM04 - `NM04-Decisions-Architecture_DEC-05.md`  
**REF-ID:** DEC-05  
**Priority:** High  
**Status:** Active

**Summary:** Why sentinels must be sanitized at router boundaries - prevents API pollution, avoids BUG-01 (535ms penalty), clean external interface.

**Use When:** Internal vs external value handling

### From NM07 (Decision Trees - DT-##):

#### DT-13: New Interface or Extend Existing

**File:** From NM07 - `DT-13.md`  
**REF-ID:** DT-13  
**Priority:** High  
**Status:** Active

**Summary:** Decision tree for when new functionality warrants a new interface versus extending existing interface versus adding to utilities.

**Key Decision Points:**
- Does functionality fit existing interface?
- Is functionality substantial (>200 lines)?
- Does functionality have its own state?
- Is functionality domain-specific?

**Use When:**
- Adding new functionality
- Deciding interface architecture
- Growing the system
- Feature requests

---

## Quick Decision Guide

### Scenario 1: Should I Create New Interface?

**Decision Path:**
1. Check **DT-13**: New Interface or Extend Existing
   - Fits existing interface? → Extend it
   - >200 lines + state + domain-specific? → New interface
   - Otherwise → Add to UTILITY

### Scenario 2: Understanding Architecture Patterns

**Reading Path:**
1. Start with **DEC-01**: SUGA Pattern (overall philosophy)
2. Then **DEC-02**: Gateway Centralization (import rules)
3. Then **DEC-03**: Dispatch Pattern (operation routing)
4. Reference **DEC-04** and **DEC-05** for specific patterns

### Scenario 3: Adding New Feature

**Analysis Framework:**
```
1. Use DT-13 to determine placement:
   - New interface?
   - Extend existing?
   - Add to utilities?

2. Apply architectural patterns:
   - DEC-02: Import via gateway
   - DEC-03: Use dispatch dict
   - DEC-05: Sanitize sentinels

3. Follow SUGA pattern (DEC-01):
   - Gateway layer (router)
   - Interface layer (operations)
   - Core layer (implementation)
```

---

## Architectural Principles

### From DEC-## Decisions:

**SUGA Pattern (DEC-01):**
- Three layers: Gateway → Interface → Core
- Clean separation of concerns
- Lambda-optimized architecture

**Gateway Centralization (DEC-02):**
- All cross-interface imports via gateway
- No direct core-to-core dependencies
- RULE-01 enforcement

**Dispatch Pattern (DEC-03):**
- Dictionary-based operation routing
- O(1) lookup performance
- Clean extension model

**Simplicity (DEC-04):**
- No threading locks (single-threaded Lambda)
- YAGNI: You Aren't Gonna Need It
- Avoid premature optimization

**Clean Boundaries (DEC-05):**
- Sanitize internal values at boundaries
- External API stays clean
- Prevent implementation leaks

### From DT-13 Decision Tree:

**Interface Creation Criteria:**
- Substantial (>200 lines)
- Has own state
- Domain-specific
- Used by multiple interfaces

**Otherwise:**
- Extend existing interface (natural fit)
- Add to utilities (generic helpers)

---

## Related Categories

**Within Decision Logic:**
- **Import** (DT-01, DT-02: How to import, where to place)
- **Feature Addition** (DT-03: User wants feature)
- **Refactoring** (DT-10: Should I refactor)

**Other Categories:**
- **NM01-Architecture** (ARCH-## patterns implement these decisions)
- **NM02-Dependencies** (dependency rules shaped by DEC-01, DEC-02)
- **NM05-AntiPatterns** (AP-01, AP-08 prevented by these decisions)
- **NM06-Lessons** (LESS-01, LESS-06 reinforce these patterns)

---

## Key Relationships

**DEC-01 (SUGA) enables:**
- DEC-02: Gateway centralization
- RULE-01: Cross-interface imports via gateway
- ARCH-01: Gateway trinity pattern

**DEC-02 (Gateway) requires:**
- All interfaces route through gateway
- No direct cross-interface dependencies
- Clean import paths

**DEC-03 (Dispatch) provides:**
- O(1) operation routing
- Clean extension without modifying existing code
- Predictable performance

**DT-13 (Interface Growth) applies:**
- When adding functionality
- Growing system architecturally
- Maintaining clean boundaries

---

## Common Questions

**Q: When should I create a new interface?**
**A:** See **DT-13** - If >200 lines + has state + domain-specific + used by multiple → New interface.

**Q: Why can't I import directly from other cores?**
**A:** See **DEC-02** - Gateway centralization prevents circular dependencies, enables testing, enforces boundaries.

**Q: Why dispatch dicts instead of if/elif?**
**A:** See **DEC-03** - O(1) performance, clean extension, no cascading changes.

**Q: Should I add threading locks for safety?**
**A:** See **DEC-04** - No. Lambda is single-threaded. YAGNI applies.

**Q: What are sentinels and why sanitize them?**
**A:** See **DEC-05** - Internal markers that must not leak to external API. Prevents BUG-01.

---

## Keywords

architecture, SUGA pattern, gateway, interfaces, dispatch, threading, sentinels, new interface, interface design, architectural decisions, system structure, boundary enforcement

---

## Navigation

**Parent:** Decision Logic Master Index  
**Siblings:** Import, Feature Addition, Error Handling, Testing, Optimization, Refactoring, Deployment, Meta

**Related:** NM04 Decisions (DEC-01 through DEC-05)

**Location:** `/sima/entries/decisions/architecture/`

---

**End of Index**

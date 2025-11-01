# Anti-Patterns-Master-Index.md

**Version:** 1.0.0  
**Date:** 2025-11-01  
**Purpose:** Master index for all anti-pattern entries

**REF-ID:** INDEX-ANTIPATTERNS-MASTER  
**Category:** Anti-Patterns  
**Total Entries:** 28 (AP-01 through AP-28)  
**Total Categories:** 11  
**Created:** 2025-11-01  
**Last Updated:** 2025-11-01

---

## Overview

Master index for all anti-patterns in SIMA - patterns and practices that should be avoided. Anti-patterns document common mistakes, their consequences, and correct alternatives. This is the primary navigation hub for understanding what NOT to do.

**Purpose:** Prevent known mistakes, document failed approaches, guide toward better solutions.

**Structure:** 11 categories covering import, concurrency, error handling, security, testing, quality, and more.

---

## Anti-Pattern Categories

### Import Anti-Patterns (AP-01 to AP-05)
**Category:** `/sima/entries/anti-patterns/import/`  
**Index:** `Import-Index.md`  
**Items:** 5 anti-patterns  
**Focus:** Import violations, circular dependencies, gateway bypass

**Entries:**
- **AP-01:** Skipping Gateway Layer
- **AP-02:** Direct Core Imports  
- **AP-03:** Circular Import Dependencies
- **AP-04:** Importing from Wrong Layer
- **AP-05:** Cross-Interface Direct Imports

**Common Theme:** Always import through gateway, never bypass layers

---

### Implementation Anti-Patterns (AP-06 to AP-07)
**Category:** `/sima/entries/anti-patterns/implementation/`  
**Index:** `Implementation-Index.md`  
**Items:** 2 anti-patterns  
**Focus:** Implementation mistakes, architectural violations

**Entries:**
- **AP-06:** Mixing Concerns in Single Function
- **AP-07:** Violating Single Responsibility

**Common Theme:** Keep functions focused, respect separation of concerns

---

### Concurrency Anti-Patterns (AP-08, AP-11, AP-13)
**Category:** `/sima/entries/anti-patterns/concurrency/`  
**Index:** `Concurrency-Index.md`  
**Items:** 3 anti-patterns  
**Focus:** Threading mistakes in single-threaded Lambda environment

**Entries:**
- **AP-08:** Threading Locks in Lambda (🔴 Critical)
- **AP-11:** Race Condition Worries (🟠 High)
- **AP-13:** Multiprocessing in Lambda (🟠 High)

**Common Theme:** Lambda is single-threaded, no threading primitives needed

---

### Dependencies Anti-Patterns (AP-09)
**Category:** `/sima/entries/anti-patterns/dependencies/`  
**Index:** `Dependencies-Index.md`  
**Items:** 1 anti-pattern  
**Focus:** Dependency management mistakes

**Entries:**
- **AP-09:** Heavy Unnecessary Dependencies

**Common Theme:** Minimize dependencies, respect 128MB Lambda limit

---

### Critical Anti-Patterns (AP-10)
**Category:** `/sima/entries/anti-patterns/critical/`  
**Index:** `Critical-Index.md`  
**Items:** 1 anti-pattern  
**Focus:** Critical system failures

**Entries:**
- **AP-10:** Sentinel Object Leakage (🔴 Critical)

**Common Theme:** Sanitize internal objects at boundaries (see DEC-05, BUG-01)

---

### Performance Anti-Patterns (AP-12)
**Category:** `/sima/entries/anti-patterns/performance/`  
**Index:** `Performance-Index.md`  
**Items:** 1 anti-pattern  
**Focus:** Performance mistakes

**Entries:**
- **AP-12:** Premature Optimization

**Common Theme:** Measure first (LESS-02), optimize hot paths only

---

### Error Handling Anti-Patterns (AP-14 to AP-16)
**Category:** `/sima/entries/anti-patterns/error-handling/`  
**Index:** `ErrorHandling-Index.md`  
**Items:** 3 anti-patterns  
**Focus:** Error handling mistakes

**Entries:**
- **AP-14:** Bare Except Clauses (🔴 Critical)
- **AP-15:** Swallowing Exceptions Silently
- **AP-16:** Catching Wrong Exception Types

**Common Theme:** Be specific with exceptions, never hide errors

---

### Security Anti-Patterns (AP-17 to AP-19)
**Category:** `/sima/entries/anti-patterns/security/`  
**Index:** `Security-Index.md`  
**Items:** 3 anti-patterns  
**Focus:** Security mistakes

**Entries:**
- **AP-17:** Hardcoded Secrets (🔴 Critical)
- **AP-18:** Logging Sensitive Data (🔴 Critical)
- **AP-19:** Sentinel Objects Crossing Boundaries (🔴 Critical)

**Common Theme:** Keep secrets in SSM, sanitize at boundaries, protect PII

---

### Quality Anti-Patterns (AP-22)
**Category:** `/sima/entries/anti-patterns/quality/`  
**Index:** `Quality-Index.md`  
**Items:** 1 anti-pattern  
**Focus:** Code quality issues

**Entries:**
- **AP-22:** Inconsistent Naming Conventions

**Common Theme:** Consistency over cleverness (LESS-04)

---

### Testing Anti-Patterns (AP-23 to AP-24)
**Category:** `/sima/entries/anti-patterns/testing/`  
**Index:** `Testing-Index.md`  
**Items:** 2 anti-patterns  
**Focus:** Testing mistakes

**Entries:**
- **AP-23:** Testing Implementation Details
- **AP-24:** Ignoring Edge Cases

**Common Theme:** Test behavior, test failures (LESS-08)

---

### Documentation Anti-Patterns (AP-25 to AP-26)
**Category:** `/sima/entries/anti-patterns/documentation/`  
**Index:** `Documentation-Index.md`  
**Items:** 2 anti-patterns  
**Focus:** Documentation mistakes

**Entries:**
- **AP-25:** Outdated Documentation
- **AP-26:** Missing Decision Rationale

**Common Theme:** Document "why" not just "what" (DEC-19)

---

### Process Anti-Patterns (AP-27 to AP-28)
**Category:** `/sima/entries/anti-patterns/process/`  
**Index:** `Process-Index.md`  
**Items:** 2 anti-patterns  
**Focus:** Development process mistakes

**Entries:**
- **AP-27:** Skipping Code Review
- **AP-28:** Deploying Without Tests

**Common Theme:** Follow process, maintain quality gates

---

## Quick Reference by Severity

### 🔴 Critical Anti-Patterns (Fix Immediately)
- **AP-08:** Threading Locks in Lambda
- **AP-10:** Sentinel Object Leakage (535ms penalty)
- **AP-14:** Bare Except Clauses
- **AP-17:** Hardcoded Secrets
- **AP-18:** Logging Sensitive Data
- **AP-19:** Sentinel Objects Crossing Boundaries

### 🟠 High Priority Anti-Patterns
- **AP-01:** Skipping Gateway Layer
- **AP-02:** Direct Core Imports
- **AP-03:** Circular Dependencies
- **AP-11:** Race Condition Worries
- **AP-13:** Multiprocessing in Lambda
- **AP-15:** Swallowing Exceptions

### 🟡 Medium Priority Anti-Patterns
- **AP-04:** Importing from Wrong Layer
- **AP-05:** Cross-Interface Direct Imports
- **AP-06:** Mixing Concerns
- **AP-07:** Violating Single Responsibility
- **AP-09:** Heavy Unnecessary Dependencies
- **AP-12:** Premature Optimization
- **AP-16:** Catching Wrong Exception Types
- **AP-22:** Inconsistent Naming
- **AP-23:** Testing Implementation Details
- **AP-24:** Ignoring Edge Cases
- **AP-25:** Outdated Documentation
- **AP-26:** Missing Decision Rationale
- **AP-27:** Skipping Code Review
- **AP-28:** Deploying Without Tests

---

## Quick Reference by Category

**Architecture/Import:** AP-01, AP-02, AP-03, AP-04, AP-05  
**Implementation:** AP-06, AP-07  
**Concurrency:** AP-08, AP-11, AP-13  
**Dependencies:** AP-09  
**Critical System:** AP-10  
**Performance:** AP-12  
**Error Handling:** AP-14, AP-15, AP-16  
**Security:** AP-17, AP-18, AP-19  
**Quality:** AP-22  
**Testing:** AP-23, AP-24  
**Documentation:** AP-25, AP-26  
**Process:** AP-27, AP-28

---

## Most Common Anti-Patterns Encountered

### Top 5 by Frequency
1. **AP-01:** Skipping Gateway Layer (architectural violations)
2. **AP-14:** Bare Except Clauses (error handling)
3. **AP-08:** Threading Locks (concurrency misunderstanding)
4. **AP-12:** Premature Optimization (performance mistakes)
5. **AP-26:** Missing Decision Rationale (documentation gaps)

### Top 5 by Impact
1. **AP-10:** Sentinel Object Leakage (535ms penalty, BUG-01)
2. **AP-17:** Hardcoded Secrets (security vulnerability)
3. **AP-08:** Threading Locks (potential deadlocks)
4. **AP-03:** Circular Dependencies (system instability)
5. **AP-18:** Logging Sensitive Data (compliance violation)

---

## Related Content

### Related Decisions
- **DEC-01:** SUGA Pattern (prevents AP-01, AP-02, AP-03)
- **DEC-02:** Gateway Centralization (prevents AP-01, AP-05)
- **DEC-04:** No Threading Locks (documents why AP-08 is wrong)
- **DEC-05:** Sentinel Sanitization (prevents AP-10, AP-19)
- **DEC-19:** Neural Map Documentation (prevents AP-25, AP-26)

### Related Lessons
- **LESS-01:** Gateway Pattern Prevents Problems (explains why AP-01 fails)
- **LESS-02:** Measure Don't Guess (explains why AP-12 is wrong)
- **LESS-04:** Consistency Over Cleverness (explains AP-22)
- **LESS-08:** Test Failure Paths (explains why AP-24 is dangerous)

### Related Bugs
- **BUG-01:** Sentinel Leakage (caused by AP-10, AP-19)
- **BUG-02:** Circular Import Deadlock (caused by AP-03)
- **BUG-03:** Silent Failure (caused by AP-15)

### Related Architecture
- **ARCH-SUGA:** Gateway pattern prevents AP-01 through AP-05
- **GATE-01:** Gateway structure (correct alternative to AP-01)
- **INT-XX:** Interface patterns (prevent AP-04, AP-05)

---

## Usage Guide

### When Should I Check Anti-Patterns?

**Before Writing Code:**
- Review relevant category (import, concurrency, etc.)
- Check critical anti-patterns (🔴)
- Apply correct patterns instead

**During Code Review:**
- Use **TOOL-03-Anti-Pattern-Checklist.md**
- Scan for common violations
- Focus on critical severity first

**When Debugging:**
- If strange behavior → Check AP-10 (sentinel leakage)
- If slow performance → Check AP-12 (premature optimization)
- If import errors → Check AP-01 through AP-05

**When Optimizing:**
- Read AP-12 FIRST (measure don't guess)
- Apply LESS-02 (data-driven optimization)
- Avoid premature optimization

---

## Prevention Strategy

### Architectural Prevention (Build It Right)
- **Use SUGA pattern** → Prevents AP-01 through AP-05
- **Gateway centralization** → Prevents AP-01, AP-02, AP-05
- **Layer boundaries** → Prevents AP-04, AP-06, AP-07

### Operational Prevention (Run It Right)
- **Code review checklist** → Catches AP-27, AP-28
- **Linting rules** → Catches AP-14, AP-22
- **Security scanning** → Catches AP-17, AP-18

### Educational Prevention (Know It Right)
- **Read anti-patterns** → Understand why they're wrong
- **Study alternatives** → Know correct patterns
- **Review lessons** → Learn from past mistakes

---

## Navigation

**Category Indexes:**
- Import-Index.md (AP-01 to AP-05)
- Implementation-Index.md (AP-06 to AP-07)
- Concurrency-Index.md (AP-08, AP-11, AP-13)
- Dependencies-Index.md (AP-09)
- Critical-Index.md (AP-10)
- Performance-Index.md (AP-12)
- ErrorHandling-Index.md (AP-14 to AP-16)
- Security-Index.md (AP-17 to AP-19)
- Quality-Index.md (AP-22)
- Testing-Index.md (AP-23 to AP-24)
- Documentation-Index.md (AP-25 to AP-26)
- Process-Index.md (AP-27 to AP-28)

**Related Master Indexes:**
- Decisions-Master-Index.md (correct patterns)
- Lessons-Master-Index.md (learned experiences)
- Core-Architecture-Quick-Index.md (architectural patterns)

**Location:** `/sima/entries/anti-patterns/`

---

## Keywords

anti-patterns, mistakes, violations, what-not-to-do, common-errors, architectural-violations, security-issues, performance-mistakes, import-problems, error-handling, testing-mistakes, master-index

---

## Version History

- **2025-11-01:** Created master index for anti-patterns (28 entries, 11 categories)

---

**Total Anti-Patterns:** 28  
**Total Categories:** 11  
**Critical Severity:** 6  
**High Severity:** 6  
**Medium Severity:** 16

**End of Master Index**

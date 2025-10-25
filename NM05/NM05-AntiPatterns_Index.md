# NM05-AntiPatterns_Index.md

# NM05 - Anti-Patterns Index

**Category Number:** NM05
**Topics:** 12
**Individual Files:** 28
**Last Updated:** 2025-10-24

---

## Category Overview

**Purpose:** Documents what NOT to do in SUGA-ISP development. Anti-patterns are proven failures - patterns that seem reasonable but cause problems in production. Learning from these mistakes prevents repeating them.

**Scope:** Comprehensive catalog of architectural, implementation, security, quality, testing, documentation, and process anti-patterns discovered through production experience and lessons learned.

**Philosophy:** Every anti-pattern exists because someone made that mistake and we learned from it. These aren't theoretical - they're battle-tested failures that we've documented to prevent recurrence.

---

## Topics in This Category

### Import (5 items)
- **Description:** Violations of SUGA import rules and gateway pattern
- **Items:** AP-01 to AP-05
- **Index:** `NM05-AntiPatterns-Import_Index.md`
- **Priority Items:** AP-01 (Direct cross-interface imports), AP-05 (Subdirectories)
- **Status:** ✅ Complete

### Implementation (2 items)
- **Description:** Delegation and implementation boundary violations
- **Items:** AP-06, AP-07
- **Index:** `NM05-AntiPatterns-Implementation_Index.md`
- **Priority Items:** AP-06 (Partial delegation)
- **Status:** ✅ Complete

### Concurrency (3 items)
- **Description:** Threading and concurrency mistakes in Lambda
- **Items:** AP-08, AP-11, AP-13
- **Index:** `NM05-AntiPatterns-Concurrency_Index.md`
- **Priority Items:** AP-08 (Threading locks - CRITICAL)
- **Status:** ✅ Complete

### Dependencies (1 item)
- **Description:** Library and package size management
- **Items:** AP-09
- **Index:** `NM05-AntiPatterns-Dependencies_Index.md`
- **Priority Items:** AP-09 (Adding without measurement)
- **Status:** ✅ Complete

### Critical (1 item)
- **Description:** Highest-impact architectural flaw (sentinel leak)
- **Items:** AP-10
- **Index:** `NM05-AntiPatterns-Critical_Index.md`
- **Priority Items:** AP-10 (Sentinel objects crossing boundaries - 535ms cost)
- **Status:** ✅ Complete

### Performance (1 item)
- **Description:** Cold start and initialization optimization failures
- **Items:** AP-12
- **Index:** `NM05-AntiPatterns-Performance_Index.md`
- **Priority Items:** AP-12 (Heavy imports at module level)
- **Status:** ✅ Complete

### Error Handling (3 items)
- **Description:** Exception handling and error propagation mistakes
- **Items:** AP-14, AP-15, AP-16
- **Index:** `NM05-AntiPatterns-ErrorHandling_Index.md`
- **Priority Items:** AP-14 (Bare except clauses)
- **Status:** ✅ Complete

### Security (3 items)
- **Description:** Critical security vulnerabilities and failures
- **Items:** AP-17, AP-18, AP-19
- **Index:** `NM05-AntiPatterns-Security_Index.md`
- **Priority Items:** All three (AP-17 No validation, AP-18 Hardcoded secrets, AP-19 SQL injection)
- **Status:** ✅ Complete

### Code Quality (3 items)
- **Description:** Maintainability and readability degradation
- **Items:** AP-20, AP-21, AP-22
- **Index:** `NM05-AntiPatterns-Quality_Index.md`
- **Priority Items:** AP-20 (God functions), AP-21 (Magic numbers)
- **Status:** ✅ Complete

### Testing (2 items)
- **Description:** Testing process and assertion failures
- **Items:** AP-23, AP-24
- **Index:** `NM05-AntiPatterns-Testing_Index.md`
- **Priority Items:** Both (No tests, Tests without assertions)
- **Status:** ✅ Complete

### Documentation (2 items)
- **Description:** Documentation absence and maintenance failures
- **Items:** AP-25, AP-26
- **Index:** `NM05-AntiPatterns-Documentation_Index.md`
- **Priority Items:** AP-26 (Outdated comments - worse than none)
- **Status:** ✅ Complete

### Process (2 items)
- **Description:** Development workflow and deployment safety failures
- **Items:** AP-27, AP-28
- **Index:** `NM05-AntiPatterns-Process_Index.md`
- **Priority Items:** Both (No version control, Deploying untested code - CRITICAL)
- **Status:** ✅ Complete

---

## Quick Access by Priority

### 🔴 Critical (7 items)
Must never violate - immediate production risk:
1. **AP-08**: Threading Locks - Lambda is single-threaded
2. **AP-10**: Sentinel Objects Crossing Boundaries - 535ms performance penalty
3. **AP-17**: No Input Validation - Security vulnerability
4. **AP-18**: Hardcoded Secrets - Credential exposure
5. **AP-19**: SQL Injection Patterns - Database compromise
6. **AP-27**: No Version Control - Work loss, no rollback
7. **AP-28**: Deploying Untested Code - Production failures

### 🟡 High (6 items)
Important to avoid - significant technical debt:
- **AP-01**: Direct Cross-Interface Imports
- **AP-06**: Partial Delegation in Extension Facades
- **AP-09**: Adding Dependencies Without Measurement
- **AP-11**: Race Conditions in Shared State
- **AP-12**: Heavy Imports at Module Level
- **AP-14**: Bare Except Clauses

### 🟠 Medium (11 items)
Should avoid - creates maintenance burden:
- AP-02, AP-03, AP-04 (Import patterns)
- AP-07 (Direct function calls)
- AP-13 (Thread-unsafe operations)
- AP-15, AP-16 (Error handling)
- AP-20, AP-21 (Code quality)
- AP-23, AP-24 (Testing)

### ⚪ Low (4 items)
Good practice - improves quality:
- AP-05 (Subdirectories)
- AP-22 (Naming conventions)
- AP-25, AP-26 (Documentation)

---

## Quick Access by Category Theme

### Architecture & Design
- Import (AP-01 to AP-05)
- Implementation (AP-06, AP-07)
- Critical (AP-10)
- Dependencies (AP-09)

### Runtime & Performance
- Concurrency (AP-08, AP-11, AP-13)
- Performance (AP-12)

### Safety & Security
- Error Handling (AP-14, AP-15, AP-16)
- Security (AP-17, AP-18, AP-19)

### Quality & Maintenance
- Code Quality (AP-20, AP-21, AP-22)
- Testing (AP-23, AP-24)
- Documentation (AP-25, AP-26)

### Process & Workflow
- Process (AP-27, AP-28)

---

## Statistics

**Total Anti-Patterns:** 28
**Priority Distribution:**
- 🔴 Critical: 7 (25%)
- 🟡 High: 6 (21%)
- 🟠 Medium: 11 (39%)
- ⚪ Low: 4 (14%)

**Topics with Most Items:**
1. Import: 5 items
2. Error Handling: 3 items
2. Security: 3 items
2. Concurrency: 3 items
2. Code Quality: 3 items

**Most Referenced:**
- AP-01 (Direct imports) - Core architectural pattern
- AP-08 (Threading locks) - Lambda-specific constraint
- AP-10 (Sentinel leak) - Highest production impact (535ms)
- AP-14 (Bare except) - Error handling foundation

---

## Integration with Other Categories

### NM01 - Architecture
Anti-patterns are violations of architectural principles:
- AP-01 to AP-05 violate ARCH-01 (Gateway Trinity)
- AP-10 led to DEC-05 (Sentinel sanitization)
- AP-12 prevented by ARCH-07 (LMMS system)

### NM02 - Dependencies
Import anti-patterns enforce dependency rules:
- AP-01 violates RULE-01 (Gateway-only imports)
- AP-09 relates to DEP-01 to DEP-08 (Dependency layers)

### NM03 - Operations
Anti-patterns affect operational flows:
- Error handling patterns (AP-14 to AP-16) relate to ERR-02
- Performance patterns (AP-12) relate to PATH-01 (Cold start)

### NM04 - Decisions
Many anti-patterns led directly to design decisions:
- AP-08 → DEC-04 (No threading locks)
- AP-10 → DEC-05 (Sentinel sanitization)
- AP-05 → DEC-08 (Flat file structure)
- AP-09 → DEC-07 (128MB limit)

### NM06 - Lessons
Anti-patterns inform lessons learned:
- AP-10 → BUG-01 (Sentinel leak)
- Process patterns → LESS-15 (Verification protocol)
- All patterns → WISD-## (Synthesized wisdom)

### NM07 - Decision Logic
Anti-patterns appear in decision trees:
- "Can I use X?" → Check anti-patterns first
- Pre-deployment checklist → Scan all anti-patterns
- Code review → Anti-patterns as rejection criteria

---

## Usage Guidelines

### For Developers
**Before implementing anything, ask:**
1. Does this violate any anti-pattern?
2. Check relevant topic index (Import, Security, etc.)
3. If unsure, search this index

**During code review:**
1. Scan code against anti-patterns checklist
2. Reject code that violates Critical or High priority items
3. Flag Medium priority items for fix
4. Document Low priority items for cleanup

### For AI Assistants (Claude)
**MANDATORY checks before suggesting code:**
1. Review relevant anti-patterns for the change type
2. Explicitly verify no Critical patterns violated
3. Mention if approaching High priority pattern boundaries
4. Cite specific AP-## in explanations

**Red flags that should trigger anti-pattern check:**
- "import [core_module]" → Check AP-01
- "threading.Lock()" → REJECT (AP-08)
- "except:" with no type → REJECT (AP-14)
- "def function(...): [200 lines]" → Flag AP-20
- No docstring → Note AP-25
- Deployment without tests → REJECT (AP-28)

### For Project Evolution
**Adding new anti-patterns:**
1. Identify pattern in production or code review
2. Assign next AP-## number
3. Create individual file
4. Add to appropriate topic index
5. Update this category index
6. Cross-reference to relevant lessons/decisions

**Pattern typically emerges from:**
- Production bug (like AP-10/BUG-01)
- Code review discovery
- Performance analysis
- Security audit

---

## Related Topics

**Other Categories:**
- **NM01**: Architecture principles these patterns violate
- **NM02**: Import rules these patterns break
- **NM04**: Design decisions motivated by these patterns
- **NM06**: Bugs and lessons that identified these patterns
- **NM07**: Decision trees that check for these patterns

**Gateway Layer:**
- **NM00-Quick_Index.md**: Quick lookup for anti-patterns
- **NM00B-ZAPH.md**: Top 20 frequently accessed (includes AP-01, AP-08, AP-10, AP-14)

**Supporting Tools:**
- **Anti-Patterns Checklist**: Scannable verification tool
- **Workflows Playbook**: Workflow #5 (Can I questions)
- **SESSION-START-Quick-Context**: RED FLAGS section

---

**Navigation:**
- **Up:** Master Index (NM00A)
- **Gateway:** Quick Index (NM00), ZAPH (NM00B)
- **Related Categories:** [NM01, NM02, NM03, NM04, NM06, NM07]

---

**End of Category Index**

**Status:** ✅ 100% COMPLETE (28 individual files + 12 topic indexes + 1 category index = 41 files)
**Next:** Quality verification and final deployment

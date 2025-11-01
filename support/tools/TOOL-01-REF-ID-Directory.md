# TOOL-01: REF-ID Directory

**REF-ID:** TOOL-01  
**Category:** Tool  
**Type:** Reference Directory  
**Purpose:** Complete directory of all REF-IDs in use and available  
**Version:** 1.0.0  
**Last Updated:** 2025-11-01

---

## ðŸŽ¯ TOOL PURPOSE

**What:** Central registry of all Reference IDs (REF-IDs) across SIMA system

**When to Use:**
- Creating new neural map entry (need next available ID)
- Looking up existing entry by ID
- Validating cross-references
- Auditing REF-ID usage

**How It Helps:**
- Prevents ID collisions
- Enables quick lookup
- Maintains ID consistency
- Tracks ID usage

---

## 📋 REF-ID SYSTEM OVERVIEW

### ID Format

```
{TYPE}-{##}

Where:
- TYPE = 2-5 letter category code
- ## = Sequential number (01-99)
```

**Examples:**
- ARCH-01 (Architecture entry #1)
- LESS-42 (Lesson #42)
- INT-07 (Interface #7)
- AP-25 (Anti-Pattern #25)

### ID Categories

| Type | Full Name | Range | Current Max | Next Available |
|------|-----------|-------|-------------|----------------|
| **ARCH** | Architecture | 01-99 | 09 | **10** |
| **GATE** | Gateway | 01-99 | 03 | **04** |
| **INT** | Interface | 01-99 | 12 | **13** |
| **PAT** | Pattern | 01-99 | 05 | **06** |
| **LANG** | Language | 01-99 | 08 | **09** |
| **DEP** | Dependency | 01-99 | 05 | **06** |
| **RULE** | Import Rule | 01-99 | 04 | **05** |
| **LESS** | Lesson | 01-99 | 54 | **55** |
| **AP** | Anti-Pattern | 01-99 | 28 | **29** |
| **DEC** | Decision | 01-99 | 23 | **24** |
| **BUG** | Bug Report | 01-99 | 04 | **05** |
| **WISD** | Wisdom | 01-99 | 05 | **06** |
| **DT** | Decision Tree | 01-99 | 13 | **14** |
| **FW** | Framework | 01-99 | 02 | **03** |
| **META** | Meta | 01-99 | 01 | **02** |

---

## ðŸ—ºï¸ ARCHITECTURE (ARCH)

**Range:** ARCH-01 to ARCH-99  
**Current Count:** 9  
**Next Available:** ARCH-10

### In Use

| REF-ID | Name | Status | Location |
|--------|------|--------|----------|
| ARCH-01 | Three-Layer Gateway Pattern | âœ… Active | NM01/Architecture |
| ARCH-02 | Lambda Runtime Constraints | âœ… Active | NM01/Architecture |
| ARCH-03 | Import Flow Architecture | âœ… Active | NM01/Architecture |
| ARCH-04 | Error Handling Architecture | âœ… Active | NM01/Architecture |
| ARCH-05 | State Management Pattern | âœ… Active | NM01/Architecture |
| ARCH-06 | Cache Architecture | âœ… Active | NM01/Architecture |
| ARCH-07 | Security Architecture | âœ… Active | NM01/Architecture |
| ARCH-08 | Observability Architecture | âœ… Active | NM01/Architecture |
| ARCH-09 | Module Size Limits | âœ… Active | NM01/Architecture |

---

## ðŸš€ GATEWAY (GATE)

**Range:** GATE-01 to GATE-99  
**Current Count:** 3  
**Next Available:** GATE-04

### In Use

| REF-ID | Name | Status | Location |
|--------|------|--------|----------|
| GATE-01 | Gateway Core Pattern | âœ… Active | NM01/Gateway |
| GATE-02 | Gateway Wrapper Functions | âœ… Active | NM01/Gateway |
| GATE-03 | Gateway Initialization | âœ… Active | NM01/Gateway |

---

## 🔌 INTERFACES (INT)

**Range:** INT-01 to INT-99  
**Current Count:** 12  
**Next Available:** INT-13

### In Use

| REF-ID | Name | Status | Location |
|--------|------|--------|----------|
| INT-01 | Cache Interface | âœ… Active | NM01/Interfaces |
| INT-02 | Logging Interface | âœ… Active | NM01/Interfaces |
| INT-03 | Security Interface | âœ… Active | NM01/Interfaces |
| INT-04 | HTTP Interface | âœ… Active | NM01/Interfaces |
| INT-05 | Initialization Interface | âœ… Active | NM01/Interfaces |
| INT-06 | Config Interface | âœ… Active | NM01/Interfaces |
| INT-07 | Metrics Interface | âœ… Active | NM01/Interfaces |
| INT-08 | Debug Interface | âœ… Active | NM01/Interfaces |
| INT-09 | Singleton Interface | âœ… Active | NM01/Interfaces |
| INT-10 | Utility Interface | âœ… Active | NM01/Interfaces |
| INT-11 | WebSocket Interface | âœ… Active | NM01/Interfaces |
| INT-12 | Circuit Breaker Interface | âœ… Active | NM01/Interfaces |

---

## ðŸ"š LANGUAGES (LANG)

**Range:** LANG-{LANG}-01 to LANG-{LANG}-99  
**Current Count:** 8  
**Next Available:** LANG-PY-09

### Python (LANG-PY)

| REF-ID | Name | Status | Location |
|--------|------|--------|----------|
| LANG-PY-01 | Python Import System | âœ… Active | NM01/Languages |
| LANG-PY-02 | Python Type System | âœ… Active | NM01/Languages |
| LANG-PY-03 | Python Error Handling | âœ… Active | NM01/Languages |
| LANG-PY-04 | Python Decorators | âœ… Active | NM01/Languages |
| LANG-PY-05 | Python Context Managers | âœ… Active | NM01/Languages |
| LANG-PY-06 | Python Generators | âœ… Active | NM01/Languages |
| LANG-PY-07 | Python Async/Await | âœ… Active | NM01/Languages |
| LANG-PY-08 | Python Performance | âœ… Active | NM01/Languages |

---

## 🔗 DEPENDENCIES (DEP)

**Range:** DEP-01 to DEP-99  
**Current Count:** 5  
**Next Available:** DEP-06

### In Use

| REF-ID | Name | Status | Location |
|--------|------|--------|----------|
| DEP-01 | Layer Dependencies | âœ… Active | NM02/Dependencies |
| DEP-02 | Gateway Dependencies | âœ… Active | NM02/Dependencies |
| DEP-03 | Interface Dependencies | âœ… Active | NM02/Dependencies |
| DEP-04 | Core Dependencies | âœ… Active | NM02/Dependencies |
| DEP-05 | External Dependencies | âœ… Active | NM02/Dependencies |

---

## 📜 IMPORT RULES (RULE)

**Range:** RULE-01 to RULE-99  
**Current Count:** 4  
**Next Available:** RULE-05

### In Use

| REF-ID | Name | Status | Location |
|--------|------|--------|----------|
| RULE-01 | Gateway-Only Core Imports | âœ… Active | NM02/Rules |
| RULE-02 | No Circular Dependencies | âœ… Active | NM02/Rules |
| RULE-03 | Interface Import Restrictions | âœ… Active | NM02/Rules |
| RULE-04 | Module-Level Import Rules | âœ… Active | NM02/Rules |

---

## ðŸ"š LESSONS (LESS)

**Range:** LESS-01 to LESS-99  
**Current Count:** 54  
**Next Available:** LESS-55

### High-Priority Lessons (Selected)

| REF-ID | Name | Category | Status |
|--------|------|----------|--------|
| LESS-01 | Always Fetch Current Files | Architecture | âœ… Active |
| LESS-02 | Verify Performance Impact | Performance | âœ… Active |
| LESS-03 | No Direct Core Imports | Architecture | âœ… Active |
| LESS-04 | Layer Boundary Enforcement | Architecture | âœ… Active |
| LESS-05 | Gateway as Single Entry | Architecture | âœ… Active |
| LESS-15 | Verification Before Suggestion | Operations | âœ… Active |
| LESS-25 | Batch Metric Publishing | Optimization | âœ… Active |
| LESS-42 | Sanitize Sentinels at Boundaries | Architecture | âœ… Active |

**Full List:** See NM06/Lessons directory

---

## âš ï¸ ANTI-PATTERNS (AP)

**Range:** AP-01 to AP-99  
**Current Count:** 28  
**Next Available:** AP-29

### Critical Anti-Patterns (Selected)

| REF-ID | Name | Category | Status |
|--------|------|----------|--------|
| AP-01 | Skipping Gateway Layer | Import | âœ… Active |
| AP-02 | Direct Core Imports | Import | âœ… Active |
| AP-03 | Circular Dependencies | Import | âœ… Active |
| AP-08 | Threading in Single-Thread Runtime | Concurrency | âœ… Active |
| AP-10 | Sentinel Leakage | Critical | âœ… Active |
| AP-14 | Bare Except Clauses | Error Handling | âœ… Active |
| AP-25 | Threading Locks in Lambda | Concurrency | âœ… Active |

**Full List:** See NM05/Anti-Patterns directory

---

## ðŸŽ¯ DECISIONS (DEC)

**Range:** DEC-01 to DEC-99  
**Current Count:** 23  
**Next Available:** DEC-24

### Key Decisions (Selected)

| REF-ID | Name | Category | Status |
|--------|------|----------|--------|
| DEC-01 | Gateway Pattern Adoption | Architecture | âœ… Active |
| DEC-02 | Boundary Sanitization | Architecture | âœ… Active |
| DEC-03 | Three-Layer Structure | Architecture | âœ… Active |
| DEC-12 | Module Size Limits | Technical | âœ… Active |
| DEC-15 | Singleton Pattern Usage | Technical | âœ… Active |
| DEC-20 | Metric Batching Strategy | Operational | âœ… Active |

**Full List:** See NM04/Decisions directory

---

## ðŸ› BUGS (BUG)

**Range:** BUG-01 to BUG-99  
**Current Count:** 4  
**Next Available:** BUG-05

### In Use

| REF-ID | Name | Status | Location |
|--------|------|--------|----------|
| BUG-01 | Sentinel Object Leakage | âœ… Active | NM06/Bugs |
| BUG-02 | Threading Lock Deadlock | âœ… Active | NM06/Bugs |
| BUG-03 | Circular Import Error | âœ… Active | NM06/Bugs |
| BUG-04 | Cache Serialization Failure | âœ… Active | NM06/Bugs |

---

## ðŸ'¡ WISDOM (WISD)

**Range:** WISD-01 to WISD-99  
**Current Count:** 5  
**Next Available:** WISD-06

### In Use

| REF-ID | Name | Status | Location |
|--------|------|--------|----------|
| WISD-01 | Verification Over Assumption | âœ… Active | NM06/Wisdom |
| WISD-02 | Simplicity Over Cleverness | âœ… Active | NM06/Wisdom |
| WISD-03 | Explicit Over Implicit | âœ… Active | NM06/Wisdom |
| WISD-04 | Constraints Enable Creativity | âœ… Active | NM06/Wisdom |
| WISD-05 | Boundaries Prevent Complexity | âœ… Active | NM06/Wisdom |

---

## ðŸŒ³ DECISION TREES (DT)

**Range:** DT-01 to DT-99  
**Current Count:** 13  
**Next Available:** DT-14

### Categories

| REF-IDs | Category | Count |
|---------|----------|-------|
| DT-01 to DT-02 | Import Decisions | 2 |
| DT-03 to DT-04 | Feature Addition | 2 |
| DT-05 to DT-06 | Error Handling | 2 |
| DT-07 | Optimization | 1 |
| DT-08 to DT-09 | Testing | 2 |
| DT-10 to DT-11 | Refactoring | 2 |
| DT-12 | Deployment | 1 |
| DT-13 | Architecture | 1 |

**Full List:** See NM07/Decision-Logic directory

---

## 🔧 USAGE INSTRUCTIONS

### Finding Next Available ID

**Step 1:** Identify entry type (LESS, AP, DEC, etc.)

**Step 2:** Check "Next Available" in directory above

**Step 3:** Use that ID for new entry

**Step 4:** Update this directory after creation

### Looking Up Existing Entry

**By ID:**
1. Find type category above
2. Scan "In Use" table
3. Note location
4. Navigate to file

**By Name:**
1. Use Ctrl+F / Cmd+F
2. Search for name
3. Find REF-ID
4. Navigate to location

### Validating Cross-References

**Step 1:** Extract all REF-IDs from entry

**Step 2:** Look up each ID in this directory

**Step 3:** Verify all exist and are active

**Step 4:** Correct any invalid references

---

## 📊 STATISTICS

### ID Usage by Category

| Category | In Use | Available | Usage % |
|----------|--------|-----------|---------|
| Architecture | 9 | 90 | 10% |
| Gateway | 3 | 96 | 3% |
| Interfaces | 12 | 87 | 14% |
| Languages | 8 | 91 | 9% |
| Dependencies | 5 | 94 | 5% |
| Rules | 4 | 95 | 4% |
| **Lessons** | **54** | **45** | **55%** |
| **Anti-Patterns** | **28** | **71** | **28%** |
| Decisions | 23 | 76 | 23% |
| Bugs | 4 | 95 | 4% |
| Wisdom | 5 | 94 | 5% |
| Decision Trees | 13 | 86 | 13% |
| Frameworks | 2 | 97 | 2% |
| Meta | 1 | 98 | 1% |

**Total IDs in Use:** 171  
**Total Available:** 1,229  
**Overall Usage:** 12%

---

## âš ï¸ MAINTENANCE WARNINGS

### High Usage Categories

**LESSONS (55% used):**
- Approaching capacity concerns at 70%
- Consider subcategorization if reaches 80%
- Currently healthy but monitor

**ANTI-PATTERNS (28% used):**
- Healthy growth rate
- No capacity concerns

### ID Collision Prevention

**Never reuse IDs** even if entry deprecated. Mark as:
- ⚠️ Deprecated (still referenced)
- ðŸ›' Retired (no longer valid)
- 🔄 Superseded by {NEW-ID}

### Audit Schedule

- **Weekly:** Check for new entries, update counts
- **Monthly:** Validate all cross-references
- **Quarterly:** Full audit, identify retired IDs

---

## 🔗 RELATED TOOLS

- **TOOL-02:** Quick Answer Index - Fast lookup for common questions
- **TOOL-03:** Anti-Pattern Checklist - Validation tool
- **TOOL-04:** Verification Protocol - Quality checks
- **TMPL-01:** Neural Map Entry Template - Creating new entries

---

## 🎓 BEST PRACTICES

**DO:**
âœ… Always check this directory before creating entry  
âœ… Use next sequential number  
âœ… Update directory after creation  
âœ… Validate cross-references  

**DON'T:**
❌ Reuse retired IDs  
❌ Skip numbers (use sequential)  
❌ Create entries without checking  
❌ Leave directory outdated  

---

**END OF TOOL**

**Tool Version:** 1.0.0  
**Last Updated:** 2025-11-01  
**Maintained By:** SIMA Team  
**Total REF-IDs Tracked:** 171  
**Next Update:** Weekly
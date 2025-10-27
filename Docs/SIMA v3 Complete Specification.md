# SIMA v3 Complete Specification

**Version:** 3.1.0  
**Date:** 2025-10-23  
**Status:** Active - Phase 1 Complete  
**Purpose:** Single source of truth for SIMA v3 architecture

---

## 📋 Quick Navigation

- [Architecture Overview](#architecture-overview)
- [File Structure](#file-structure)
- [Naming Conventions](#naming-conventions)
- [Templates (v3.1.0)](#templates)
- [ZAPH Specification](#zaph-specification)
- [Project-Specific Maps (NMP##)](#project-specific-maps)
- [Migration Phases](#migration-phases)
- [Quality Gates](#quality-gates)

---

## Architecture Overview

### The Vision

SIMA v3 transforms neural maps from **monolithic files** to **modular knowledge atoms**, applying the same architectural principles that made SUGA-ISP successful.

### Core Principles

**4-Layer Architecture:**
```
Gateway Layer (3 files)
    ↓ Routes to
Category Layer (7-10 indexes)
    ↓ Organizes into
Topic Layer (~25-40 indexes)
    ↓ Links to
Individual Files (130+ atoms)
```

**Success Metrics:**
- ✅ Time to add new memory: < 5 minutes
- ✅ Search precision: Direct file access
- ✅ Truncation risk: Eliminated (files < 200 lines)
- ✅ Hot path access (ZAPH): < 5 seconds
- ✅ Maintenance burden: Linear growth

---

## File Structure

### Gateway Layer (3 files - Root Level)

```
NM00-Quick_Index.md          - Keyword routing
NM00A-Master_Index.md        - Complete navigation
NM00B-ZAPH.md                - Hot path (frequently accessed)
```

### Category Layer (7 indexes - In NM##/ subdirectories)

```
NM01/NM01-Architecture_Index.md
NM02/NM02-Dependencies_Index.md
NM03/NM03-Operations_Index.md
NM04/NM04-Decisions_Index.md
NM05/NM05-AntiPatterns_Index.md
NM06/NM06-Lessons_Index.md           ← Phase 1 Complete
NM07/NM07-DecisionLogic_Index.md
```

**Future:**
- NMP01/ - Project-specific category (Lambda Execution Engine)

### Topic Layer (Variable per category)

**Example - NM06/Lessons (Phase 1):**
```
NM06/NM06-Lessons-CoreArchitecture_Index.md
NM06/NM06-Lessons-Performance_Index.md
NM06/NM06-Lessons-Operations_Index.md
NM06/NM06-Lessons-Documentation_Index.md
NM06/NM06-Lessons-Evolution_Index.md
```

### Individual Files (130+ knowledge atoms)

**Format:** `NM##/NM##-Category-Topic_REF-ID.md`

**Examples:**
```
NM06/NM06-Lessons-CoreArchitecture_LESS-01.md
NM06/NM06-Lessons-Performance_LESS-02.md
NM04/NM04-Decisions-Technical_DEC-04.md
NM05/NM05-AntiPatterns-Import_AP-01.md
```

### Directory Structure

```
neural-maps/
├── NM00-Quick_Index.md
├── NM00A-Master_Index.md
├── NM00B-ZAPH.md
├── File-Server-Configuration.md
│
├── NM01/
│   └── [Architecture files]
├── NM02/
│   └── [Dependencies files]
├── NM03/
│   └── [Operations files]
├── NM04/
│   └── [Decisions files]
├── NM05/
│   └── [Anti-Patterns files]
├── NM06/
│   ├── NM06-Lessons_Index.md
│   ├── NM06-Lessons-CoreArchitecture_Index.md
│   ├── NM06-Lessons-Performance_Index.md
│   ├── NM06-Lessons-Operations_Index.md
│   ├── NM06-Lessons-Documentation_Index.md
│   ├── NM06-Lessons-Evolution_Index.md
│   ├── NM06-Lessons-CoreArchitecture_LESS-01.md
│   └── [...21 total LESS-## files]
└── NM07/
    └── [Decision Logic files]
```

---

## Naming Conventions

### File Naming Pattern

```
Format: NM##-Category-Topic_REF-ID.md

Components:
- NM##: Neural Map + 2-digit category (01-99)
- Category: PascalCase category name
- Topic: PascalCase topic name
- REF-ID: Standard reference identifier

Examples:
✅ NM06-Lessons-CoreArchitecture_LESS-01.md
✅ NM04-Decisions-Technical_DEC-04.md
✅ NM05-AntiPatterns-Import_AP-01.md

Index files:
✅ NM06-Lessons_Index.md (Category)
✅ NM06-Lessons-CoreArchitecture_Index.md (Topic)
```

### REF-ID Prefixes

| Prefix | Category | Example Range | Growth Rate |
|--------|----------|---------------|-------------|
| ARCH | Architecture | 01-08 | Slow (1-2/year) |
| RULE | Rules | 01-04 | Slow (1/year) |
| DEC | Decisions | 01-24+ | Medium (5-10/year) |
| INT | Interfaces | 01-12 | Stable (complete) |
| PATH | Pathways | 01-03 | Slow (1-2/year) |
| ERR | Errors | 01-02 | Slow (1-2/year) |
| DEP | Dependencies | 01-08 | Stable (complete) |
| AP | Anti-Patterns | 01-28 | Medium (3-5/year) |
| BUG | Bugs | 01-02+ | Medium (5-10/year) |
| LESS | Lessons | 01-21+ | High (20-30/year) |
| WISD | Wisdom | 01-05+ | Medium (5-10/year) |

---

## Templates

### Template 1: Individual Knowledge Atom (v3.1.0)

**With Filename Header:**

```markdown
# filename.md - REF-ID

# REF-ID: Title

**Category:** [Category Name]
**Topic:** [Topic Name]
**Priority:** [Critical/High/Medium/Low]
**Status:** Active
**Created:** [YYYY-MM-DD]
**Last Updated:** [YYYY-MM-DD]

---

## Summary

[1-2 sentence summary]

---

## Context

[Why this knowledge exists, what problem it solves]

---

## Content

[Main content - the actual knowledge]

### [Subsection 1]
[Content]

---

## Related Topics

- **REF-ID**: [Description]
- **REF-ID**: [Description]

---

## Keywords

[keyword1, keyword2, keyword3, keyword4]

---

## Version History

- **[YYYY-MM-DD]**: Created/Updated - [What changed]

---

**File:** `NM##-Category-Topic_REF-ID.md`
**End of Document**
```

**Rules:**
- Line 1: Filename header `# filename.md - REF-ID`
- Keep files < 200 lines
- 4-8 keywords
- 3-7 related topics

---

### Template 2: Topic Index

```markdown
# filename.md

# Category - Topic Index

**Category:** NM## - [Category Name]
**Topic:** [Topic Name]
**Items:** [Count]
**Last Updated:** [YYYY-MM-DD]

---

## Topic Overview

**Description:** [2-3 sentences]
**Keywords:** [keyword1, keyword2, keyword3]

---

## Individual Files

### REF-ID: Title
- **File:** `NM##-Category-Topic_REF-ID.md`
- **Summary:** [One-line description]
- **Priority:** [Critical/High/Medium/Low]

[Repeat for each item]

---

## Related Topics

- [Other topics in category]
- [Topics in other categories]

---

**Navigation:**
- **Up:** [Category Index]
- **Siblings:** [Other topic indexes]

---

**End of Index**
```

---

### Template 3: Category Index

```markdown
# filename.md

# NM## - Category Name Index

**Category Number:** NM##
**Topics:** [Count]
**Individual Files:** [Count]
**Last Updated:** [YYYY-MM-DD]

---

## Category Overview

**Purpose:** [2-3 sentences]
**Scope:** [What belongs here]

---

## Topics in This Category

### [Topic Name]
- **Description:** [One sentence]
- **Items:** [Count]
- **Index:** `NM##-Category-Topic_Index.md`
- **Priority Items:** [REF-ID, REF-ID]

[Repeat for each topic]

---

## Quick Access

**Most Frequently Accessed:**
1. [REF-ID]: [Title]
2. [REF-ID]: [Title]

---

**Navigation:**
- **Up:** Master Index (NM00A)
- **Related Categories:** [List]

---

**End of Index**
```

---

## ZAPH Specification

### What is ZAPH?

**ZAPH = Zero-Abstraction Fast Path**

Direct access to frequently used knowledge, inspired by Lambda's ZAPH optimization pattern.

**Tier System:**

| Tier | Frequency | Action |
|------|-----------|--------|
| Tier 1: Critical | 50+ uses/30 days | Always in ZAPH |
| Tier 2: High | 20-49 uses/30 days | Rotate into ZAPH |
| Tier 3: Moderate | 10-19 uses/30 days | Watch for promotion |

### Initial ZAPH Population (Top 20)

Based on usage patterns:

1. DEC-04: No threading locks
2. RULE-01: Gateway-only imports
3. BUG-01: Sentinel leak (535ms cost)
4. LESS-15: 5-step verification protocol
5. LESS-01: Read complete files first
6. AP-01: Direct cross-interface imports
7. AP-08: Threading primitives
8. AP-14: Bare except clauses
9. DEC-01: SIMA pattern choice
10. INT-01: CACHE interface
11. ARCH-01: Gateway trinity
12. ARCH-07: LMMS system
13. LESS-02: Measure don't guess
14. DEC-05: Sentinel sanitization
15. DEC-08: Flat file structure
16. BUG-02: _CacheMiss validation
17. PATH-01: Cold start pathway
18. ERR-02: Error propagation
19. DEC-21: SSM token-only
20. AP-27: Skipping verification

### Counter Update Protocol

**At end of each session:**

1. List items accessed
2. Update `NM00B-ZAPH.md` counters
3. Check for tier promotions/demotions
4. Time investment: +1-2 minutes per session

**Example:**
```markdown
## Session Summary: 2025-10-23

**Items Accessed:**
- DEC-04: +1 (now 74)
- RULE-01: +1 (now 69)
- LESS-15: +2 (now 40)
- AP-01: +1 (now 43)
```

---

## Project-Specific Maps

### NMP## Category System

**Purpose:** Separate project-specific knowledge from generic patterns

**Philosophy:**
- **NM##** = Transferable across projects
- **NMP##** = Specific to current project

### NMP01 - Lambda Execution Engine

**Topics (~20):**

**Interfaces (12 topics):**
- NMP01-Interfaces-Cache_Index.md
- NMP01-Interfaces-Logging_Index.md
- NMP01-Interfaces-Security_Index.md
- [... 9 more interface topics]

**Gateway (3 topics):**
- NMP01-Gateway-Core_Index.md
- NMP01-Gateway-Wrappers_Index.md
- NMP01-Gateway-FastPath_Index.md

**Extensions:**
- NMP01-Extensions-HomeAssistant_Index.md
- [Future extensions]

**Failsafe:**
- NMP01-Failsafe-Independent_Index.md

**Diagnostics:**
- NMP01-Diagnostics-Tools_Index.md

**What Goes in NMP01:**
- Interface function catalogs
- Project-specific design rationale
- Lambda-specific implementation details
- Integration patterns unique to this project

**What Stays in NM##:**
- Generic patterns (SUGA, SIMA)
- Universal lessons
- Generic anti-patterns
- Architectural principles

### File Server Configuration

**Configurable Base URLs:**

```markdown
# File-Server-Configuration.md

## Server Base URLs

**SRC:** https://claude.dizzybeaver.com/src/
**NMAP:** https://claude.dizzybeaver.com/nmap/
**MD:** https://claude.dizzybeaver.com/md/

## Usage Pattern

{BASE_URL}/{filename}

Examples:
SRC:  https://claude.dizzybeaver.com/src/gateway.py
NMAP: https://claude.dizzybeaver.com/nmap/NM06/NM06-Lessons_Index.md
```

**Benefits:**
- Single point of configuration
- Easy server migration
- Project portability

---

## Migration Phases

### Phase 1: NM06-Lessons ✅ COMPLETE

**Status:** 27/27 files complete (100%)
- ✅ Category Index (1/1)
- ✅ Topic Indexes (5/5)
- ✅ Individual Files (21/21)

**Time:** ~15 minutes actual (estimated 8-10 hours, but many files already existed)

---

### Phase 2: NM06-Bugs + Wisdom (NEXT)

**Goal:** Complete NM06 atomization

**Current State:**
- NM06-BUGS-Critical.md (BUG-01, BUG-02)
- NM06-WISDOM-Development.md (various wisdom)

**Target State:**
- NM06-Bugs-Critical_Index.md
- Individual BUG-## files (~3-5)
- NM06-Wisdom-Synthesized_Index.md
- Individual WISD-## files (~5)

**Estimated Time:** 5-6 hours

---

### Phase 3: NM04-Decisions

**Goal:** Atomize all design decisions

**Files:** 24+ decisions across Technical, Design, Security, Operational topics

**Estimated Time:** 10-12 hours

---

### Phase 4: NM05-AntiPatterns

**Goal:** Atomize all 28 anti-patterns

**Files:** 28 individual AP-## files across Import, Threading, ErrorHandling, etc.

**Estimated Time:** 8-9 hours

---

### Phase 5: Master Index + Quick Index + ZAPH

**Goal:** Create complete gateway layer

**Estimated Time:** 11 hours

---

### Phase 6: Tool Updates

**Goal:** Update supporting tools for v3

**Estimated Time:** 8 hours

---

### Phase 7: Validation & Optimization

**Goal:** System test and first optimization

**Estimated Time:** 6 hours + 1 week usage

---

### Phase 8: NMP01 Project-Specific (Optional)

**Goal:** Document Lambda Execution Engine specifics

**Files:** Interface catalogs, failsafe rationale, extension docs

**Estimated Time:** 25-30 hours

---

## Quality Gates

### Per-Phase Quality Check

**After each migration phase:**

✅ **Content Verification:**
- All REF-IDs accounted for
- All content migrated (no loss)
- Files follow naming convention
- Files use correct template

✅ **Structure Verification:**
- Category Index complete
- Topic Indexes complete
- Individual Files complete
- Navigation works

✅ **Cross-Reference Verification:**
- All Related Topics valid
- All file paths correct
- No broken links

✅ **Metadata Verification:**
- Proper headers (v3.1.0 with filename)
- REF-IDs present
- Keywords present (4-8)
- Related topics present (3-7)

✅ **Usability Test:**
- Can find items via indexes
- Can navigate without getting lost
- Files load without truncation (< 200 lines)

---

## Key Decisions & Rationale

### What Gets Atomized?

**ATOMIZE:**
- ✅ Lessons (LESS-##) - High growth, independent
- ✅ Bugs (BUG-##) - Isolated incidents
- ✅ Decisions (DEC-##) - Independent choices
- ✅ Anti-Patterns (AP-##) - Discrete items
- ✅ Wisdom (WISD-##) - Standalone insights

**KEEP GROUPED:**
- ✅ Architecture Core (ARCH-01 to ARCH-06) - Coherent story
- ✅ Dependencies (DEP-01 to DEP-08) - Interconnected layers
- ✅ Operation Flows (PATH-##, ERR-##) - Sequential processes
- ✅ Rules (RULE-01, RULE-02) - Foundational pairs

**Rationale:** Some knowledge is inherently narrative. SIMA v3 is pragmatic, not dogmatic.

---

### Topic Organization

**Topics are fluid, not fixed.** They emerge organically from content.

**Rules:**
- Minimum 3 items to justify a topic
- Maximum 15 items before considering split
- Can reorganize anytime without breaking system

---

## Future Evolution

### LIGS: Lazy Import Gateway System

**When:** File count > 300
**Purpose:** Selective loading (don't load all at session start)

### LUGS: Lazy Unload Gateway System

**When:** Context pressure regular
**Purpose:** Memory management (unload least recently used)

---

## Quick Reference

### File Size Limits

- Individual Files: < 200 lines
- Topic Indexes: < 300 lines
- Category Indexes: < 250 lines
- Gateway Files: < 400 lines

### File Count Estimates

**Current (Phase 1 complete):** ~35 files
**Phase 2 complete:** ~50 files
**All phases complete:** ~175 files
**18 months:** ~590 files

---

## Summary

**SIMA v3 is the foundation for scalable knowledge management.**

**Key Innovations:**
1. Modular atomization (like gateway_wrappers refactoring)
2. ZAPH hot path (Lambda optimization → knowledge)
3. Living architecture (evolves based on usage)
4. Scalable foundation (ready for LIGS/LUGS)

**Success = Adding knowledge is trivial, finding knowledge is instant.**

---

**End of Complete Specification**

**Version:** 3.1.0  
**Last Updated:** 2025-10-23  
**Status:** Active - Phase 1 Complete, Phase 2 Ready

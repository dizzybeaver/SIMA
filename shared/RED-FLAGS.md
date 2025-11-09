# RED-FLAGS.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Never-suggest patterns across all contexts  
**Location:** `/sima/shared/`

---

## UNIVERSAL RED FLAGS

**These apply to ALL projects, ALL modes**

---

### 🚫 Code in Chat

**Never:** Output code in chat messages  
**Always:** Create complete file artifacts

**Why:** Token waste, fragments, errors

**REF:** Artifact-Standards.md

---

### 🚫 File Fragments

**Never:** Partial code, "add to line X"  
**Always:** Complete files, all existing code

**Why:** Incomplete, not deployable, breaks context

**REF:** Artifact-Standards.md

---

### 🚫 Files > 400 Lines

**Never:** Create files exceeding 400 lines  
**Always:** Split into focused separate files

**Why:** project_knowledge_search truncation, readability

**REF:** File-Standards.md, SPEC-LINE-LIMITS.md

---

### 🚫 Missing Headers

**Never:** Files without version/date/purpose  
**Always:** Include proper headers

**Why:** Organization, tracking, documentation

**REF:** File-Standards.md, SPEC-HEADERS.md

---

### 🚫 Broken Encoding

**Never:** Non-UTF-8, broken emoji/charts  
**Always:** UTF-8 encoding, test rendering

**Why:** Display issues, data corruption

**REF:** Encoding-Standards.md

---

### 🚫 Skip File Fetch

**Never:** Modify without fetching current version  
**Always:** Fetch via fileserver.php URLs first

**Why:** Working against stale/cached code, errors

**REF:** WISD-06, LESS-01

---

### 🚫 Bare Except Clauses

**Never:** `except:` without exception type  
**Always:** `except SpecificError as e:`

**Why:** Swallows errors, hides bugs

**REF:** AP-14, ERR-02

---

## PLATFORM-SPECIFIC RED FLAGS

### AWS Lambda

**🚫 Threading Locks**
- Never: `threading.Lock()`, `threading.Thread()`
- Always: Atomic operations, single-threaded design
- Why: Lambda is single-threaded
- REF: DEC-04, AP-08

**🚫 Heavy Dependencies**
- Never: Libraries exceeding 128MB
- Always: Built-in AWS modules, justified dependencies
- Why: Lambda memory limit
- REF: DEC-07

**🚫 Stateful Operations**
- Never: Rely on instance state between invocations
- Always: Stateless design
- Why: Lambda execution model
- REF: AWS-Lambda-DEC-04

---

## ARCHITECTURE-SPECIFIC RED FLAGS

### SUGA Architecture

**🚫 Direct Core Imports**
- Never: `from cache_core import get_value`
- Always: `import gateway; gateway.cache_get(key)`
- Why: Circular imports, breaks pattern
- REF: RULE-01, AP-01

**🚫 Skip Gateway**
- Never: Direct interface-to-interface imports
- Always: Via gateway for cross-interface
- Why: Violates architecture, circular imports
- REF: GATE-03, AP-04

**🚫 Module-Level Imports**
- Never: `import heavy_lib` at module level
- Always: `def func(): import heavy_lib` at function level
- Why: Cold start impact
- REF: ARCH-07, AP-02

**🚫 Subdirectories**
- Never: Create subdirectories (except `home_assistant/`)
- Always: Flat structure
- Why: Proven simpler, easier navigation
- REF: DEC-08, AP-05

**🚫 Sentinel Objects Crossing Boundaries**
- Never: Let `_CacheMiss`, `_NotFound` leak to router
- Always: Sanitize at interface boundary
- Why: JSON serialization fails
- REF: DEC-05, AP-19, BUG-01

---

## PROCESS RED FLAGS

**🚫 Skip Verification**
- Never: Deploy without checklist (LESS-15)
- Always: Run verification protocol
- Why: Prevents 90% of mistakes
- REF: AP-27

**🚫 Guess Without Logs**
- Never: Assume root cause
- Always: Check logs, measure, verify
- Why: Wastes time, wrong fixes
- REF: LESS-02, LESS-09

**🚫 Change Multiple Things**
- Never: Modify several variables at once
- Always: Change one thing, test, iterate
- Why: Can't identify what fixed/broke
- REF: Debug-Mode principles

---

## QUICK REFERENCE

**Before suggesting code, check:**
1. ❌ Threading locks? (Lambda)
2. ❌ Direct core import? (SUGA)
3. ❌ Bare except? (Universal)
4. ❌ Sentinel leak? (SUGA)
5. ❌ >128MB dependency? (Lambda)
6. ❌ Subdirectories? (SUGA)
7. ❌ Skip verification? (Process)
8. ❌ Code in chat? (Universal)
9. ❌ File fragment? (Universal)
10. ❌ >400 lines? (Universal)
11. ❌ Missing header? (Universal)
12. ❌ Skip fileserver.php? (Universal)

**If ANY ❌ triggered: DO NOT SUGGEST**

---

## RELATED DOCUMENTS

**Anti-Patterns:** `/sima/entries/anti-patterns/`  
**Decisions:** `/sima/entries/decisions/`  
**Lessons:** `/sima/entries/lessons/`  
**Specifications:** `/sima/entries/specifications/`

---

**END OF FILE**

**Summary:** Never suggest threading (Lambda), direct imports (SUGA), bare except, code in chat, fragments, >400 lines, skip verification.

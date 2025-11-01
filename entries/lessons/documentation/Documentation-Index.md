# File: Documentation-Index.md

**Category:** Lessons Learned  
**Topic:** Documentation  
**Version:** 4.1.0  
**Date:** 2025-11-01  
**Items:** 4

---

## Topic Overview

Documentation practices, knowledge management, and architectural teaching lessons. Covers decision documentation, code comments vs external docs, making architecture teachable, and technical constraints on documentation files.

**Keywords:** documentation, knowledge management, teaching, maintainability, design decisions, code comments, file-size-limits, neural-maps

---

## Individual Files

### LESS-11: Design Decisions Must Be Documented
- **File:** `LESS-11.md`
- **Priority:** CRITICAL
- **Summary:** Document WHY decisions were made, not just WHAT
- **Related:** DEC-01, LESS-13, AP-25

### LESS-12: Code Comments vs External Documentation
- **File:** `LESS-12.md`
- **Priority:** MEDIUM
- **Summary:** Separate docs by purpose: docstrings for API, comments for tricky logic, external docs for architecture
- **Related:** LESS-11, LESS-13, AP-26

### LESS-13: Architecture Must Be Teachable
- **File:** `LESS-13.md`
- **Priority:** HIGH
- **Summary:** If you can't teach the architecture in 5 minutes, it's too complex
- **Related:** LESS-11, ARCH-01, DEC-01

### LESS-54: 400-Line Limit Constraint
- **File:** `LESS-54.md`
- **Priority:** CRITICAL
- **Summary:** All neural map files MUST be ≤400 lines due to project_knowledge_search truncation behavior
- **Related:** SIMAv4-Developer-Guide, WISD-01
- **Impact:** Files >400 lines lose middle content (lines 201-N-200 disappear)

---

## Cross-Topic Relationships

**Related Topics:**
- Core Architecture (documents the principles being taught)
- Operations (verification requires understanding docs)
- Evolution (evolution needs documented rationale)
- Learning (file size constraints affect knowledge capture)

**Frequently Accessed Together:**
- When writing docs: LESS-11, LESS-12, LESS-13, LESS-54
- When onboarding: LESS-13, LESS-01
- When making decisions: LESS-11
- When creating neural maps: LESS-54 (ALWAYS check line count)

---

## Usage Patterns

**When documenting system:**
1. Start with LESS-11 (document decisions WHY)
2. Follow LESS-12 (right place for each type)
3. Verify LESS-13 (is it teachable?)
4. Check LESS-54 (file ≤400 lines?)

**When onboarding:**
1. Use LESS-13 (teach in 5 minutes)
2. Reference LESS-11 (decision docs)
3. Clarify with LESS-12 (where to find what)

**When refactoring:**
1. Update per LESS-11 (document changes)
2. Clean per LESS-12 (remove stale comments)
3. Verify per LESS-13 (still teachable?)

**When creating neural maps (CRITICAL):**
1. Check LESS-54 BEFORE finalizing
2. Split if approaching 400 lines
3. NEVER exceed 400-line limit
4. Verify line count: `wc -l filename.md`

---

## Documentation Hierarchy

```
Level 1: Decision Docs (LESS-11)
- Why decisions made
- Alternatives considered
- Trade-offs accepted

Level 2: Architecture Docs (LESS-13)
- Pattern explanations
- Teaching materials
- Examples

Level 3: API Docs (LESS-12)
- Docstrings
- Function contracts
- Usage examples

Level 4: Code Comments (LESS-12)
- Tricky logic only
- Non-obvious behavior
- WHY not WHAT

Level 5: File Size Constraints (LESS-54)
- Technical limitation (≤400 lines)
- Split strategy
- Accessibility requirement
```

---

## Navigation

- **Up:** Lessons Master Index
- **Sibling Topics:** Core Architecture, Performance, Operations, Evolution

---

**Version History:**
- **4.1.0 (2025-11-01):** Added LESS-54 (400-line limit constraint)
- **4.0.0 (2025-10-30):** Initial SIMAv4 format

---

**END OF INDEX**

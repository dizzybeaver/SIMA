# File-Standards.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** File organization and size standards  
**Location:** `/sima/shared/`

---

## SIZE LIMITS

### Strict Limits

**Source Code:**
- Maximum: 400 lines
- Reason: Readability, maintainability
- Action: Split if exceeded

**Neural Maps:**
- Maximum: 400 lines
- Reason: project_knowledge_search truncation
- Critical: Files >400 lines partially inaccessible

**Summaries:**
- Maximum: 100 lines
- Reason: Quick reference documents

**Plans/Prompts:**
- Maximum: 50 lines
- Reason: Session continuation files

---

## FILE HEADERS

### Required Elements

**Markdown files:**
```markdown
# filename.md

**Version:** 1.0.0
**Date:** 2025-11-08
**Purpose:** [Brief description]
```

**Python files:**
```python
# filename.py
"""
Brief description.

Version: 1.0.0
Date: 2025-11-08
"""
```

---

## STRUCTURE STANDARDS

### Standard Sections

**Neural Map Files:**
1. Header (version, date, purpose)
2. Overview/Summary
3. Main Content
4. Examples (if applicable)
5. Related Items
6. References

**Source Files:**
1. Header (docstring)
2. Imports
3. Constants
4. Functions/Classes
5. Main logic

---

## NAMING CONVENTIONS

### File Naming

**Neural Maps:**
- `CATEGORY-##-Brief-Description.md`
- Example: `LESS-01-Read-Complete-Files.md`

**Source Code:**
- `category_type.py`
- Example: `cache_core.py`, `interface_cache.py`

**Support Files:**
- `Purpose-Description.md`
- Example: `Quick-Reference-Card.md`

---

## SPLITTING FILES

### When to Split

**Triggers:**
- File exceeds 400 lines
- Multiple distinct topics
- Unrelated functionality
- Complex navigation

### How to Split

**By Topic:**
```
Large-File.md (600 lines)
→ Topic-A.md (300 lines)
→ Topic-B.md (300 lines)
```

**By Category:**
```
All-Decisions.md (800 lines)
→ Architecture-Decisions.md (300 lines)
→ Technical-Decisions.md (300 lines)
→ Operational-Decisions.md (200 lines)
```

**By Function:**
```
utilities.py (500 lines)
→ utility_validation.py (200 lines)
→ utility_transformation.py (200 lines)
→ utility_formatting.py (100 lines)
```

---

## RELATED SPECIFICATIONS

**Complete Standards:**
- SPEC-FILE-STANDARDS.md - Full details
- SPEC-LINE-LIMITS.md - Size enforcement
- SPEC-HEADERS.md - Header requirements
- SPEC-NAMING.md - Naming conventions
- SPEC-STRUCTURE.md - Organization patterns

**Location:** `/sima/entries/specifications/`

---

**END OF FILE**

**Summary:** All files ≤400 lines, proper headers, split when exceeded, clear naming.

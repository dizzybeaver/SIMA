# File-Standards.md

**Version:** 1.0.0  
**Date:** 2025-11-10  
**Purpose:** File organization and size standards  
**Location:** `/sima/context/shared/`

---

## SIZE LIMITS

### Strict Limits

**Source Code:**
- Maximum: 400 lines
- Reason: Readability, maintainability
- Action: Split if exceeded

**Neural Maps:**
- Maximum: 400 lines
- Reason: AI processing constraints
- Critical: Files >400 lines may be truncated

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
**Date:** 2025-11-10
**Purpose:** [Brief description]
```

**Source files:**
```python
# filename.py
"""
Brief description.

Version: 1.0.0
Date: 2025-11-10
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
- `category_type.ext`
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
â†' Topic-A.md (300 lines)
â†' Topic-B.md (300 lines)
```

**By Category:**
```
All-Decisions.md (800 lines)
â†' Architecture-Decisions.md (300 lines)
â†' Technical-Decisions.md (300 lines)
â†' Operational-Decisions.md (200 lines)
```

**By Function:**
```
utilities.py (500 lines)
â†' utility_validation.py (200 lines)
â†' utility_transformation.py (200 lines)
â†' utility_formatting.py (100 lines)
```

---

## RELATED SPECIFICATIONS

**Complete Standards:**
- SPEC-FILE-STANDARDS.md - Full details
- SPEC-LINE-LIMITS.md - Size enforcement
- SPEC-HEADERS.md - Header requirements
- SPEC-NAMING.md - Naming conventions
- SPEC-STRUCTURE.md - Organization patterns

**Location:** `/sima/generic/specifications/`

---

**END OF FILE**

**Summary:** All files â‰¤400 lines, proper headers, split when exceeded, clear naming.
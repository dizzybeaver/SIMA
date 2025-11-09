# PROJECT-MODE-SIMA.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** SIMA project-specific development context  
**Project:** SIMA (Structured Intelligence Memory Architecture)  
**Type:** Project Extension

---

## PROJECT: SIMA

**Name:** SIMA (Structured Intelligence Memory Architecture)  
**Type:** Knowledge Management System  
**Platform:** Claude.ai (Documentation)  
**Language:** Markdown  
**Architecture:** Domain-based organization

---

## CONSTRAINTS

**File Standards:**
- Markdown files only (.md)
- â‰¤400 line limit per file (strict)
- UTF-8 encoding required
- LF line endings only
- No trailing whitespace
- Final newline required

**Version Control:**
- Git repository
- Semantic versioning
- Change logs required
- Commit messages descriptive

**Structure:**
- Domain separation (generic/platform/language/project)
- Flat files within categories
- No excessive nesting
- Clear navigation paths

---

## ARCHITECTURE

### Domain Separation
- **/entries/** - Universal generic knowledge
- **/platforms/** - Platform-specific (AWS, Azure, GCP)
- **/languages/** - Language-specific (Python, JavaScript)
- **/projects/** - Project implementations (LEE, etc.)
- **/shared/** - Shared reference knowledge

### Knowledge Organization
- Hierarchical 4-layer system
- Atomic files (≤400 lines)
- Categories: lessons, decisions, anti-patterns, bugs, wisdom
- Cross-reference with REF-IDs
- Index maintenance required

### Mode System
- General Mode: Q&A and guidance
- Learning Mode: Extract knowledge
- Maintenance Mode: Update indexes
- Project Mode: Build features (for projects)
- Debug Mode: Troubleshoot (for projects)

---

## PATTERNS

### Neural Map Entry
```markdown
# TYPE-##.md

**Version:** X.Y.Z
**Date:** YYYY-MM-DD
**Purpose:** [Brief description]
**Category:** [Category name]

[Entry content]

**Keywords:** k1, k2, k3, k4
**Related:** REF-ID1, REF-ID2
```

### Index Entry
```markdown
- [TYPE-## - Title](/path/to/TYPE-##.md) - Brief description
```

### Cross-Reference
```markdown
**REF:** TYPE-ID1, TYPE-ID2, TYPE-ID3
```

---

## WORKFLOWS

### Add Neural Map Entry
1. Identify entry type (LESS, DEC, AP, BUG, WISD)
2. Check for duplicates (via fileserver.php)
3. Genericize content
4. Create entry (≤400 lines)
5. Update indexes
6. Add cross-references

### Create New Domain
1. Define domain structure
2. Create directory hierarchy
3. Generate index files
4. Document organization
5. Update master indexes

### Update Index
1. Scan directory for entries
2. Compare with current index
3. Add missing entries
4. Remove deleted entries
5. Sort alphabetically/numerically
6. Output updated index

### Migrate Entry Format
1. Fetch entry via fileserver.php
2. Check against SPEC-* standards
3. Fix header, encoding, naming
4. Split if >400 lines
5. Output migrated entry
6. Update indexes

---

## RED FLAGS

**SIMA-Specific:**
- âŒ Project-specifics in generic entries
- âŒ Files exceeding 400 lines
- âŒ Condensed multi-topic files
- âŒ Missing cross-references
- âŒ Broken REF-IDs
- âŒ Outdated indexes
- âŒ Duplicate entries
- âŒ Missing headers
- âŒ Wrong encoding (not UTF-8)
- âŒ Wrong line endings (CRLF)

---

## EXAMPLES

### Generic Lesson
```markdown
# LESS-15.md

**Version:** 1.0.0
**Date:** 2025-11-08
**Purpose:** Verification protocol

LESS-15: Always verify before implementation

Context: Unverified code causes 90% of errors
Prevention: Complete 5-step checklist
Impact: 3x reduction in mistakes

Keywords: verification, checklist, quality
Related: LESS-01, AP-27
```

### Domain Organization
```
/entries/lessons/core-architecture/LESS-01.md (generic)
/platforms/aws/lambda/AWS-Lambda-LESS-01.md (platform)
/languages/python/LANG-PY-01.md (language)
/projects/lee/LEE-LESS-01.md (project)
```

---

**END OF SIMA PROJECT EXTENSION**

**Version:** 1.0.0  
**Lines:** 100 (target achieved)  
**Combine with:** PROJECT-MODE-Context.md (base)

# SPEC-HEADERS.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Header requirements for all SIMA files  
**Category:** Specifications

---

## PURPOSE

Define mandatory header format for all SIMA files to ensure consistency, traceability, and discoverability.

---

## MANDATORY HEADER FORMAT

```markdown
# filename.md

**Version:** X.Y.Z  
**Date:** YYYY-MM-DD  
**Purpose:** [Brief description]  
**Category:** [Category name]
```

---

## HEADER FIELDS

### Filename (Required)
**Format:** `# filename.md`  
**Rules:**
- H1 heading (single #)
- Must match actual filename
- Include file extension
- First line of file

**Example:**
```markdown
# LESS-15-Verification-Protocol.md
```

### Version (Required)
**Format:** `**Version:** X.Y.Z`  
**Rules:**
- Semantic versioning (MAJOR.MINOR.PATCH)
- Bold label, regular version number
- Start at 1.0.0 for new files
- Increment according to change type

**Examples:**
```markdown
**Version:** 1.0.0  (Initial)
**Version:** 1.0.1  (Patch/fix)
**Version:** 1.1.0  (Minor addition)
**Version:** 2.0.0  (Breaking change)
```

### Date (Required)
**Format:** `**Date:** YYYY-MM-DD`  
**Rules:**
- ISO 8601 format
- Date of last significant update
- Bold label, regular date
- Use UTC/local consistently

**Examples:**
```markdown
**Date:** 2025-11-06
**Date:** 2025-01-15
```

### Purpose (Required)
**Format:** `**Purpose:** [Brief description]`  
**Rules:**
- Max 100 characters
- Describes what file does
- Action-oriented language
- No period at end

**Examples:**
```markdown
**Purpose:** Define verification protocol for code changes
**Purpose:** Document cache sanitization bug and fix
**Purpose:** Gateway pattern implementation guide
```

### Category (Required)
**Format:** `**Category:** [Category name]`  
**Rules:**
- Identifies file type/domain
- Helps with organization
- Used for filtering/search

**Categories:**
- Specifications
- Core Architecture
- Gateways
- Interfaces
- Lessons
- Decisions
- Anti-Patterns
- Bugs
- Wisdom
- Platform Specific
- Language Specific
- Project Specific
- Support
- Tools
- Workflows

**Examples:**
```markdown
**Category:** Specifications
**Category:** Core Architecture
**Category:** Lessons - Operations
```

---

## OPTIONAL HEADER FIELDS

### Status (Optional)
**Format:** `**Status:** [Status value]`  
**Values:**
- Draft
- Active
- Deprecated
- Archived
- Under Review

**When to use:**
- Draft: Work in progress
- Deprecated: Superseded by newer entry
- Archived: Historical reference only

### Author (Optional)
**Format:** `**Author:** [Name]`  
**When to use:** Attribution needed

### Related (Optional)
**Format:** `**Related:** REF-ID-1, REF-ID-2`  
**When to use:** Strong dependencies on other entries

---

## SPACING

```markdown
# filename.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Brief description  
**Category:** Category name

---

## FIRST SECTION
```

**Rules:**
- Blank line after H1
- Two spaces after each field for alignment
- Horizontal rule (---) after header
- Blank line before first section

---

## VALIDATION

### Checklist
- [ ] H1 heading with filename
- [ ] Version in X.Y.Z format
- [ ] Date in YYYY-MM-DD format
- [ ] Purpose under 100 chars
- [ ] Category specified
- [ ] Proper spacing
- [ ] Horizontal rule present

### Automated Checks
```python
def validate_header(file_content):
    lines = file_content.split('\n')
    
    # Check H1
    if not lines[0].startswith('# '):
        return False, "Missing H1 heading"
    
    # Check version
    if '**Version:**' not in lines[2]:
        return False, "Missing version"
    
    # Check date format
    if '**Date:**' not in lines[3]:
        return False, "Missing date"
    
    # Check purpose
    if '**Purpose:**' not in lines[4]:
        return False, "Missing purpose"
    
    # Check category
    if '**Category:**' not in lines[5]:
        return False, "Missing category"
    
    return True, "Header valid"
```

---

## VERSION UPDATES

### When to Update Version

**PATCH (X.Y.Z → X.Y.Z+1):**
- Typo fixes
- Clarifications
- Formatting improvements
- Minor corrections

**MINOR (X.Y.Z → X.Y+1.0):**
- New sections added
- Significant examples added
- Related content expanded
- Non-breaking enhancements

**MAJOR (X.Y.Z → X+1.0.0):**
- Complete restructuring
- Breaking changes
- Different approach
- Incompatible updates

### Update Both
When updating version, also update date:
```markdown
**Version:** 1.2.3  
**Date:** 2025-11-06
```

---

## EXAMPLES

### Neural Map Entry
```markdown
# LESS-15-Verification-Protocol.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** 5-step verification checklist for code changes  
**Category:** Lessons - Operations

---
```

### Specification File
```markdown
# SPEC-HEADERS.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Header requirements for all SIMA files  
**Category:** Specifications

---
```

### Decision Entry
```markdown
# DEC-04-No-Threading.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Why threading is prohibited in Lambda  
**Category:** Decisions - Architecture

---
```

### With Optional Fields
```markdown
# ARCH-SUGA.md

**Version:** 2.1.0  
**Date:** 2025-11-06  
**Purpose:** Complete SUGA architecture pattern documentation  
**Category:** Core Architecture  
**Status:** Active  
**Related:** GATE-01, GATE-02, INT-01

---
```

---

## BENEFITS

**Consistent headers provide:**
- Quick identification (filename visible)
- Version tracking (know what changed)
- Freshness indicator (date shows currency)
- Clear purpose (understand at glance)
- Proper categorization (filtering/search)
- Professional appearance

---

**Related:**
- SPEC-FILE-STANDARDS.md
- SPEC-MARKDOWN.md
- SPEC-CHANGELOG.md

**Version History:**
- v1.0.0 (2025-11-06): Initial header specification

---

**END OF FILE**

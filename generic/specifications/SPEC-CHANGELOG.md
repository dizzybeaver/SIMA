# SPEC-CHANGELOG.md

**Version:** 4.2.2-blank  
**Date:** 2025-11-10  
**Purpose:** Changelog format and content standards  
**Category:** Specifications

---

## PURPOSE

Define changelog format to track document changes clearly and concisely while respecting token budgets.

---

## FORMAT

### Location
**In document header:**
```markdown
**Changelog:**
- v1.2.0: Added section X, enhanced Y
- v1.1.0: Fixed bug in Z
- v1.0.0: Initial creation
```

**OR separate CHANGELOG.md:**
```
/directory/CHANGELOG.md
```

---

## ENTRY FORMAT

### Single Line Per Version
```markdown
- vX.Y.Z: Description (max 80 chars)
```

### Rules
- One line per version
- Max 80 characters total
- Version + colon + description
- Newest first
- No multi-line entries

---

## CONTENT GUIDELINES

### What to Include
- Major additions
- Breaking changes
- Bug fixes
- Significant refactoring
- Deprecations

### What to Omit
- Typo fixes
- Minor formatting
- Comment updates
- Whitespace changes

---

## DESCRIPTION RULES

### Max 4 Lines
**If more than 4 versions:**
- Keep 4 most recent
- Link to full CHANGELOG.md
- Archive old versions

### Single-Line Descriptions
**Good:**
```markdown
- v1.2.0: Added ZAPH tier system, updated indexes
```

**Bad (too long):**
```markdown
- v1.2.0: Added complete ZAPH tier system with all three tiers including hot path optimization and comprehensive documentation plus updated all related indexes
```

**Fixed:**
```markdown
- v1.2.0: Added ZAPH tiers, updated docs
```

---

## DESCRIPTION STYLE

### Action Words
**Use clear action verbs:**
- Added: New content
- Fixed: Bug correction
- Updated: Modified existing
- Removed: Deleted content
- Enhanced: Improved existing
- Refactored: Restructured

### Examples
```markdown
- v2.0.0: Refactored for SIMAv4 structure
- v1.3.0: Added fileserver.php integration
- v1.2.1: Fixed broken cross-references
- v1.2.0: Enhanced examples with code
- v1.1.0: Updated for Python 3.11
- v1.0.1: Removed outdated section
```

---

## ABBREVIATED CHANGELOG

### In-Header Format
```markdown
**Changelog:**
- v1.3.0: Added fileserver.php support
- v1.2.0: SIMAv4 path corrections
- v1.1.0: Enhanced with examples
- v1.0.0: Initial creation
```

### When Over 4 Entries
```markdown
**Changelog:**
- v1.5.0: Latest changes here
- v1.4.0: Recent changes
- v1.3.0: More changes
- [Full history in CHANGELOG.md]
```

---

## SEPARATE CHANGELOG FILE

### When to Create
- More than 4 major versions
- Complex version history
- Multiple contributors
- Long-lived documents

### Format (CHANGELOG.md)
```markdown
# CHANGELOG

## v1.3.0 (2025-11-06)
### Added
- fileserver.php integration
- Cache-busting URLs

### Changed
- Updated all file paths
- Enhanced documentation

### Fixed
- Broken cross-references

## v1.2.0 (2025-11-02)
### Added
- SIMAv4 structure support

### Changed
- Directory paths updated

## v1.0.0 (2025-10-25)
### Added
- Initial specification
```

---

## VERSION HISTORY ALTERNATIVE

### Simple List
```markdown
**Version History:**
- v1.2.0 (2025-11-06): Description
- v1.1.0 (2025-11-01): Description
- v1.0.0 (2025-10-25): Initial
```

### Rules
- Max 80 chars per line
- Date in ISO format
- Brief description
- Newest first

---

## TOKEN OPTIMIZATION

### Compress Descriptions
**Before:**
```markdown
- v1.2.0: Added complete fileserver.php integration system with cache-busting URLs and updated all documentation
```

**After:**
```markdown
- v1.2.0: Added fileserver.php, cache-busting
```

### Abbreviations
- Config → cfg
- Documentation → docs
- Implementation → impl
- Reference → ref
- Function → fn
- Examples → ex

**Use sparingly - clarity first**

---

## EXAMPLES BY FILE TYPE

### Neural Map Entry
```markdown
**Changelog:**
- v1.1.0: Enhanced with code examples
- v1.0.1: Fixed REF-ID typo
- v1.0.0: Initial lesson
```

### Specification
```markdown
**Changelog:**
- v1.2.0: Added validation section
- v1.1.0: Enhanced examples
- v1.0.0: Initial spec
```

### Context File
```markdown
**Changelog:**
- v3.4.0: fileserver.php integration
- v3.3.0: SIMAv4 paths
- v3.2.0: Cache-busting
- [Full history: 15 versions]
```

---

## BREAKING CHANGES

### Highlight in Changelog
```markdown
**Changelog:**
- v2.0.0: BREAKING - New directory structure
- v1.5.0: Added new features
```

### When Major Version
**Always explain breaking change:**
```markdown
- v2.0.0: BREAKING - Moved to /sima/entries/
```

---

## QUALITY CHECKLIST

- [ ] One line per version
- [ ] Max 80 chars per line
- [ ] Max 4 entries in header
- [ ] Newest first
- [ ] Clear action verbs
- [ ] Date if using version history format
- [ ] Breaking changes marked
- [ ] Link to full changelog if >4 versions

---

## VALIDATION

### Line Length Check
```python
for line in changelog:
    if len(line) > 80:
        print(f"Too long: {line}")
```

### Entry Count
```python
entries = [l for l in changelog if l.startswith('- v')]
if len(entries) > 4:
    print("Consider CHANGELOG.md")
```

---

## MIGRATION

### Converting Verbose Changelogs
1. Identify key changes only
2. Compress descriptions
3. Keep newest 4 entries
4. Create CHANGELOG.md for rest
5. Update cross-references

---

**Related:**
- SPEC-HEADERS.md
- SPEC-FILE-STANDARDS.md
- SPEC-LINE-LIMITS.md

**Version History:**
- v4.2.2-blank (2025-11-10): Blank slate version
- v1.0.0 (2025-11-06): Initial changelog spec

---

**END OF FILE**
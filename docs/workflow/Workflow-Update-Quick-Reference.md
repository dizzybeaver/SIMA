# Workflow-Update-Quick-Reference.md

**Purpose:** Quick reference for updating workflows to SIMAv4  
**Use:** Keep open while editing workflow files  
**Session:** Workflow Migration

---

## 🎯 QUICK PATH REPLACEMENTS

### Find and Replace Patterns

```
# OLD PATHS → NEW PATHS

NM##/                           → /sima/entries/
NMP##-[PROJECT]-##/             → /sima/projects/[project]/
/neural-maps/                   → /sima/entries/
/function-catalogs/             → /sima/projects/[project]/function-references/

# Specific examples:
NM01-LESS-01                    → /sima/entries/lessons/core-architecture/LESS-01.md
NMP01-LEE-02                    → /sima/projects/lee/function-references/...
```

---

## 📋 STANDARD SECTIONS TO ADD

### 1. fileserver.php Requirement

Add this section early in workflow:

```markdown
## 🔧 SESSION REQUIREMENTS

### Critical: Fresh File Access

**Before any file operations:**
1. Ensure fileserver.php fetched at session start
2. Use cache-busted URLs for all file access
3. Verify fetching fresh content (not cached)

**Why:** Anthropic caches files for weeks. fileserver.php 
bypasses cache with random ?v= parameters.

**REF:** `/sima/entries/lessons/wisdom/WISD-06.md`
```

### 2. Shared Knowledge References

Add this section near end of workflow:

```markdown
## 🔗 RELATED RESOURCES

**Standards:**
- `/sima/shared/Artifact-Standards.md` - Complete file requirements
- `/sima/shared/File-Standards.md` - Size limits, headers
- `/sima/shared/Encoding-Standards.md` - UTF-8, line endings, ≤400 lines
- `/sima/shared/RED-FLAGS.md` - Never-suggest patterns

**Architecture:**
- `/sima/shared/SUGA-Architecture.md` - 3-layer pattern
- `/sima/shared/Common-Patterns.md` - Universal code patterns

**[Add workflow-specific resources]**
```

---

## 🔄 HEADER UPDATES

### Update Version Block

```markdown
# Workflow-##-[Name].md

**Version:** 2.0.0  
**Date:** 2025-11-10  
**Category:** Support Tools  
**Type:** Workflow Template  
**Purpose:** [Original purpose]  
**Updated:** SIMAv4 paths, fileserver.php, shared knowledge references
```

---

## 🎯 REF-ID PATH UPDATES

### Full Path Format

```markdown
# BEFORE (SIMAv3):
LESS-01
DEC-04
AP-08
INT-01

# AFTER (SIMAv4):
/sima/entries/lessons/core-architecture/LESS-01.md
/sima/entries/decisions/architecture/DEC-04.md
/sima/entries/anti-patterns/concurrency/AP-08.md
/sima/entries/interfaces/INT-01_CACHE-Interface-Pattern.md
```

### Common Categories

```markdown
LESS-##  → /sima/entries/lessons/[category]/LESS-##.md
DEC-##   → /sima/entries/decisions/[category]/DEC-##.md
AP-##    → /sima/entries/anti-patterns/[category]/AP-##.md
BUG-##   → /sima/entries/lessons/bugs/BUG-##.md
WISD-##  → /sima/entries/lessons/wisdom/WISD-##.md
INT-##   → /sima/entries/interfaces/INT-##.md
GATE-##  → /sima/entries/gateways/GATE-##.md
```

---

## 🔍 SEARCH PATTERNS

### Code Block Updates

```python
# BEFORE:
project_knowledge_search: "[topic] NMP"

# AFTER:
project_knowledge_search: "[topic] [type]"
# Then search appropriate domain:
# - /sima/entries/ (generic)
# - /sima/projects/[project]/ (project-specific)
```

### File Fetch Examples

```python
# BEFORE:
# Fetch: cache_core.py

# AFTER:
# Fetch via fileserver.php:
# https://claude.dizzybeaver.com/src/cache_core.py?v=XXXXXXXXXX
# (URL from fileserver.php session fetch)
```

---

## ⚠️ RED FLAGS IN WORKFLOWS

### Issues to Fix

```markdown
❌ "Check NM## entries"
✅ "Check /sima/entries/[category]/ for existing patterns"

❌ "Create NMP entry"
✅ "Create project-specific entry in /sima/projects/[project]/"

❌ "Update NMP Quick Index"
✅ "Update /sima/projects/[project]/indexes/[project]-Index-Main.md"

❌ "Reference to neural maps"
✅ "Reference to knowledge base" or "documentation entries"

❌ No fileserver.php mention
✅ Explicit fileserver.php requirement in workflow steps
```

---

## ✅ QUALITY CHECKLIST

Copy-paste for each workflow update:

```
- [ ] All NM##/ → /sima/entries/
- [ ] All NMP##/ → /sima/projects/
- [ ] fileserver.php section added
- [ ] Shared knowledge section added
- [ ] All REF-IDs have full paths
- [ ] Version → 2.0.0
- [ ] Date → 2025-11-10
- [ ] "Updated:" note in header
- [ ] Examples updated
- [ ] Code blocks updated
- [ ] File ≤400 lines
- [ ] Filename in header
- [ ] Cross-references valid
```

---

## 📝 EXAMPLE CONVERSIONS

### Example 1: Reference Update

```markdown
# BEFORE:
See LESS-01 for file fetching requirements

# AFTER:
See `/sima/entries/lessons/core-architecture/LESS-01.md` 
for file fetching requirements
```

### Example 2: Search Update

```markdown
# BEFORE:
Search neural maps: "cache interface NMP"

# AFTER:
Search knowledge base: "cache interface"
Check:
- /sima/entries/interfaces/ (generic patterns)
- /sima/projects/[project]/function-references/ (usage)
```

### Example 3: File Creation Update

```markdown
# BEFORE:
Create: NMP01-LEE-02_CACHE-Catalog.md

# AFTER:
Create: /sima/projects/lee/function-references/LEE-CACHE-Usage.md
(Following SIMAv4 project structure)
```

---

## 🎯 COMMON WORKFLOW PATTERNS

### Pattern 1: Fetch Before Modify

```markdown
# Update to include fileserver.php:

### Step X.X: Fetch Current Files

**CRITICAL: Use fileserver.php URLs**

1. Ensure fileserver.php fetched at session start
2. Locate file in fileserver.php output
3. Use cache-busted URL: 
   `https://claude.dizzybeaver.com/path/file.py?v=XXXXXXXXXX`
4. Read complete content
5. Then modify

**REF:** `/sima/entries/lessons/wisdom/WISD-06.md`
```

### Pattern 2: Domain Classification

```markdown
# Update documentation creation sections:

### Step X.X: Classify Domain

**Determine correct location:**

- Generic pattern? → `/sima/entries/[category]/`
- Platform-specific? → `/sima/platforms/[platform]/`
- Language-specific? → `/sima/languages/[language]/`
- Project-specific? → `/sima/projects/[project]/`

**REF:** `/sima/entries/specifications/SPEC-STRUCTURE.md`
```

### Pattern 3: Cross-Reference Addition

```markdown
# Update reference sections to include full paths:

**Related Resources:**
- `/sima/entries/lessons/core-architecture/LESS-01.md`
- `/sima/entries/decisions/architecture/DEC-04.md`
- `/sima/shared/SUGA-Architecture.md`
```

---

## 🚀 QUICK START STEPS

### For Each Workflow File:

1. **Fetch fresh version** via fileserver.php
2. **Copy header** section, update version/date
3. **Find/replace** old paths with new paths
4. **Add** fileserver.php requirement section
5. **Add** shared knowledge resources section
6. **Update** all REF-ID references to full paths
7. **Update** code examples with new paths
8. **Verify** ≤400 lines (split if needed)
9. **Check** quality checklist
10. **Create artifact** with complete updated file

---

## 💡 REMEMBER

**Core Principles:**
- ✅ Always fetch via fileserver.php
- ✅ Full paths for all references
- ✅ Shared knowledge > duplication
- ✅ Domain separation (generic ≠ project)
- ✅ Complete files only (≤400 lines)
- ✅ UTF-8 encoding, LF line endings

**Document Everything:**
- Track changes in transition doc
- Note any issues found
- Record decisions made
- Update completion status

---

**END OF QUICK REFERENCE**

**Use:** Keep open during updates  
**Update:** As new patterns discovered  
**Purpose:** Speed and consistency

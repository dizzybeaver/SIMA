# Workflow-Index.md

**Version:** 2.0.0  
**Date:** 2025-11-10  
**Category:** Index  
**Purpose:** Catalog of SIMA process workflows  
**Updated:** SIMAv4 paths and references  
**Total Workflows:** 6 (core workflows)

---

## 🎯 WORKFLOW CATEGORIES

### Development Workflows
**Purpose:** Building and modifying code  
**Count:** 2

### Documentation Workflows  
**Purpose:** Creating and maintaining knowledge  
**Count:** 2

### Troubleshooting Workflows
**Purpose:** Debugging and fixing issues  
**Count:** 2

---

## 📋 AVAILABLE WORKFLOWS

### Workflow-01: Add Feature
**File:** `Workflow-01-Add-Feature.md`  
**Version:** 2.0.0 (SIMAv4)  
**Use:** Implementing new functionality in SUGA architecture  
**Time:** 15-45 minutes  
**Steps:** Plan → Fetch (via fileserver.php) → Implement → Verify → Document  
**Key Updates:** fileserver.php integration, shared knowledge refs, SIMAv4 paths

---

### Workflow-02: Debug Issue
**File:** `Workflow-02-Debug-Issue.md`  
**Use:** Troubleshooting errors and performance problems  
**Time:** 20-60 minutes  
**Steps:** Reproduce → Fetch Fresh Code → Trace → Fix → Prevent → Document  
**Requirements:** fileserver.php for fresh code access

---

### Workflow-03: Update Interface
**File:** `Workflow-03-Update-Interface.md`  
**Use:** Modifying existing interface patterns  
**Time:** 15-30 minutes  
**Steps:** Fetch Current → Understand → Modify → Verify → Update Docs  
**Critical:** Always fetch via fileserver.php before modifying

---

### Workflow-04: Add Gateway Function
**File:** `Workflow-04-Add-Gateway-Function.md`  
**Use:** Adding new gateway wrapper functions  
**Time:** 10-20 minutes  
**Steps:** Identify Interface → Create Wrapper → Add Export → Test → Document  
**Pattern:** Gateway → Interface → Core (SUGA)

---

### Workflow-05: Create Documentation Entry
**File:** `Workflow-05-Create-Documentation-Entry.md`  
**Version:** 2.0.0 (SIMAv4)  
**Use:** Creating SIMA knowledge base entries (LESS, DEC, AP, BUG, WISD)  
**Time:** 15-30 minutes  
**Steps:** Classify Domain → Check Duplicates → Create → Integrate → Validate  
**Key Updates:** Domain separation, SIMAv4 hierarchy, fileserver.php duplicate checking  
**Replaces:** Old NMP entry workflow

---

### Workflow-06: Optimize Performance
**File:** `Workflow-06-Optimize-Performance.md`  
**Use:** Improving execution speed and resource usage  
**Time:** 30-90 minutes  
**Steps:** Measure → Analyze → Profile → Optimize → Verify → Document  
**Principle:** Measure don't guess (LESS-02)

---

## 🎯 QUICK SELECTION GUIDE

### By Task Type

| Need | Workflow | File |
|------|----------|------|
| Add new feature | 01 | Add-Feature |
| Fix bug/error | 02 | Debug-Issue |
| Change interface | 03 | Update-Interface |
| Add gateway function | 04 | Add-Gateway-Function |
| Document knowledge | 05 | Create-Documentation-Entry |
| Improve performance | 06 | Optimize-Performance |

### By Role

| Role | Primary Workflows |
|------|------------------|
| Developer | 01, 02, 03, 04 |
| Architect | 01, 05, 06 |
| Documenter | 05 |
| Debugger | 02, 06 |

### By Complexity

| Complexity | Workflows | Time |
|------------|-----------|------|
| Simple | 04 | 10-20 min |
| Medium | 01, 03, 05 | 15-45 min |
| Complex | 02, 06 | 20-90 min |

---

## 🔑 CRITICAL REQUIREMENTS

### All Workflows Require:

**1. fileserver.php Access**
```
MANDATORY at session start:
1. Upload File Server URLs.md
2. Claude fetches fileserver.php automatically
3. All file access uses cache-busted URLs
4. Ensures fresh content (not week-old cached versions)
```
**REF:** `/sima/entries/lessons/wisdom/WISD-06.md`

**2. Shared Knowledge Awareness**
```
Location: /sima/shared/
- Artifact-Standards.md (complete file requirements)
- File-Standards.md (size limits, headers, encoding)
- Encoding-Standards.md (UTF-8, line endings, line limits)
- SUGA-Architecture.md (3-layer pattern)
- RED-FLAGS.md (never-suggest patterns)
- Common-Patterns.md (universal code patterns)
```

**3. SIMAv4 Path Structure**
```
Generic:    /sima/entries/[category]/
Platform:   /sima/platforms/[platform]/
Language:   /sima/languages/[language]/
Project:    /sima/projects/[project]/
Shared:     /sima/shared/
```

---

## 📊 WORKFLOW RELATIONSHIPS

### Complementary Workflows

**Development Flow:**
```
01 (Add Feature) → 02 (Debug if issues) → 05 (Document lessons)
                 → 06 (Optimize if slow)
```

**Documentation Flow:**
```
01 (Implementation) → 05 (Document patterns)
02 (Debug session) → 05 (Document bug/fix)
06 (Optimization) → 05 (Document optimization)
```

**Maintenance Flow:**
```
03 (Update Interface) → 01 (Add Features) → 05 (Update Docs)
```

---

## 🎓 WORKFLOW SELECTION EXAMPLES

### Example 1: New Feature Request
```
Scenario: Add cache invalidation functionality
Primary: Workflow-01 (Add Feature)
Secondary: Workflow-05 (Document if pattern unique)
Time: 15-45 min + 15-30 min documentation
```

### Example 2: Production Bug
```
Scenario: Lambda returning 500 errors
Primary: Workflow-02 (Debug Issue)
Secondary: Workflow-05 (Document bug + fix as BUG-##)
Time: 20-60 min + 15-30 min documentation
```

### Example 3: Performance Problem
```
Scenario: Cold start taking 5+ seconds
Primary: Workflow-06 (Optimize Performance)
Support: Workflow-02 (Debug to find bottleneck)
Secondary: Workflow-05 (Document optimization as LESS-##)
Time: 30-90 min + 15-30 min documentation
```

### Example 4: Interface Enhancement
```
Scenario: Add new functions to existing interface
Primary: Workflow-03 (Update Interface)
Then: Workflow-01 (Add Feature implementation)
Secondary: Workflow-05 (Update interface docs)
Time: 15-30 min + 15-45 min + 15-30 min
```

---

## ⚠️ COMMON MISTAKES

### Mistake 1: Skipping fileserver.php
```
❌ WRONG:
Modify files without fetching fresh versions

✅ CORRECT:
1. Ensure fileserver.php fetched at session start
2. Use cache-busted URLs for all file access
3. Always fetch before modifying
```

### Mistake 2: Wrong Domain Classification
```
❌ WRONG:
Put project-specific details in /sima/entries/ (generic)

✅ CORRECT:
- Generic patterns → /sima/entries/
- Project specifics → /sima/projects/[project]/
- Platform specifics → /sima/platforms/[platform]/
```

### Mistake 3: Incomplete Documentation
```
❌ WRONG:
Implement feature but skip Workflow-05

✅ CORRECT:
After Workflow-01, run Workflow-05 to document:
- New patterns discovered
- Decisions made
- Lessons learned
```

### Mistake 4: Exceeding Line Limits
```
❌ WRONG:
Create 600-line workflow or documentation file

✅ CORRECT:
Keep ALL files ≤400 lines
Split into multiple files if needed
```

---

## 🔗 RELATED RESOURCES

**Shared Standards:**
- `/sima/shared/Artifact-Standards.md`
- `/sima/shared/File-Standards.md`
- `/sima/shared/Encoding-Standards.md`
- `/sima/shared/RED-FLAGS.md`

**Architecture:**
- `/sima/shared/SUGA-Architecture.md`
- `/sima/languages/python/architectures/`

**Lessons:**
- `/sima/entries/lessons/core-architecture/LESS-01.md` (Fetch before modify)
- `/sima/entries/lessons/operations/LESS-15.md` (Verification checklist)
- `/sima/entries/lessons/performance/LESS-02.md` (Measure don't guess)
- `/sima/entries/lessons/wisdom/WISD-06.md` (Cache-busting)

**Structure:**
- `/sima/entries/specifications/SPEC-STRUCTURE.md` (Domain organization)
- `/sima/docs/SIMAv4-Directory-Structure.md` (Complete hierarchy)

**Other Support:**
- `/sima/support/templates/` - Entry templates
- `/sima/support/tools/` - Helper tools
- `/sima/support/checklists/` - Quality checklists

---

## 📈 VERSION HISTORY

**v2.0.0 (2025-11-10):**
- Complete SIMAv4 update
- Added fileserver.php requirements
- Updated Workflow-01 (SIMAv4 paths, shared knowledge)
- Completely rewrote Workflow-05 (domain separation, hierarchy)
- Added shared knowledge references throughout
- Updated path structure (NM##/ → /sima/entries/)
- Removed deprecated workflows
- Streamlined to 6 core workflows
- Added workflow relationships and examples

**v1.0.0 (2025-11-01):**
- Initial workflow index
- 11 workflows (pre-SIMAv4)
- Old path structure

---

## 🎯 FUTURE WORKFLOWS

**Planned additions:**
- Workflow-07: Migrate Entry Format (SIMAv3 → SIMAv4)
- Workflow-08: Update Index (Systematic index maintenance)
- Workflow-09: Cross-Reference Validation (Verify all REF-IDs)
- Workflow-10: Domain Classification Review (Ensure correct placement)

---

**END OF WORKFLOW INDEX**

**Version:** 2.0.0 (SIMAv4)  
**Workflows Indexed:** 6 core workflows  
**Location:** `/sima/support/workflows/`  
**Master Index:** `/sima/support/Support-Master-Index.md`

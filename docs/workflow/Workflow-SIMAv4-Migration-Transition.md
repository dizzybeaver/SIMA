# Workflow-SIMAv4-Migration-Transition.md

**Date:** 2025-11-10  
**Purpose:** Track workflow file migration from SIMAv3 to SIMAv4  
**Status:** In Progress (3 of 6+ completed)  
**Session:** Workflow Migration Session 1

---

## 📊 MIGRATION SUMMARY

### What Was Done

**Completed Workflows (3):**

1. **Workflow-01-Add-Feature.md** (v2.0.0)
   - Updated: SIMAv3 paths → SIMAv4 hierarchy
   - Added: fileserver.php cache-busting instructions
   - Added: Shared knowledge references (/sima/shared/)
   - Updated: Path examples (NM##/ → /sima/entries/)
   - Updated: REF-ID references to SIMAv4 structure
   - Status: ✅ Complete, ready for deployment

2. **Workflow-05-Create-Documentation-Entry.md** (v2.0.0)
   - Complete rewrite from "Create-NMP-Entry"
   - Added: Domain classification system (generic/platform/language/project)
   - Added: fileserver.php duplicate checking
   - Updated: Path structure for all domains
   - Added: Comprehensive domain-specific guidelines
   - Updated: Templates for each domain type
   - Renamed: Better reflects actual purpose
   - Status: ✅ Complete, major improvement

3. **Workflow-Index.md** (v2.0.0)
   - Updated: All workflow references
   - Added: fileserver.php requirements section
   - Added: Shared knowledge requirements
   - Updated: SIMAv4 path structure documentation
   - Added: Workflow relationships and examples
   - Streamlined: Focus on 6 core workflows
   - Status: ✅ Complete, ready for deployment

---

## 🎯 KEY CHANGES MADE

### Path Structure Updates

**Before (SIMAv3):**
```
NM##/[category]/          → Generic entries
NMP##-[PROJECT]-##/       → Project entries
```

**After (SIMAv4):**
```
/sima/entries/[category]/              → Generic
/sima/platforms/[platform]/            → Platform-specific
/sima/languages/[language]/            → Language-specific
/sima/projects/[project]/              → Project-specific
/sima/shared/                          → Shared knowledge
```

### fileserver.php Integration

**Added to all workflows:**
```
1. Session start requirement
2. Cache-busted URL usage
3. Fresh file access before modifications
4. Duplicate checking via fresh content
```

**References:**
- WISD-06 (Cache-Busting Platform Limitation)
- File Server URLs.md upload process

### Shared Knowledge References

**Added throughout:**
```
/sima/shared/Artifact-Standards.md    → Complete file requirements
/sima/shared/File-Standards.md        → Size limits, headers
/sima/shared/Encoding-Standards.md    → UTF-8, line endings, ≤400 lines
/sima/shared/SUGA-Architecture.md     → 3-layer pattern
/sima/shared/RED-FLAGS.md             → Never-suggest patterns
/sima/shared/Common-Patterns.md       → Universal code patterns
```

### Domain Separation

**New in Workflow-05:**
- Clear classification system (4 domains)
- Domain-specific guidelines
- Proper cross-referencing between domains
- Prevention of knowledge contamination

---

## 📋 REMAINING WORK

### Workflows Still Needing Updates (3+)

**Priority 1: Core Workflows**

1. **Workflow-02-Debug-Issue.md**
   - Update paths (NM##/ → /sima/entries/)
   - Add fileserver.php requirement
   - Update REF-ID references
   - Add shared knowledge refs
   - Estimated: 30-45 minutes

2. **Workflow-03-Update-Interface.md**
   - Update paths
   - Add fileserver.php integration
   - Update interface path refs (/sima/entries/interfaces/)
   - Add shared knowledge refs
   - Estimated: 20-30 minutes

3. **Workflow-04-Add-Gateway-Function.md**
   - Update paths
   - Add fileserver.php requirement
   - Update gateway pattern refs
   - Add shared knowledge refs
   - Estimated: 20-30 minutes

**Priority 2: Specialized Workflows (if they exist)**

Based on original Workflow-Index.md listing, these may exist:
- Workflow-06-Optimize.md
- Workflow-07-ImportIssues.md
- Workflow-08-ColdStart.md
- Workflow-09-DesignQuestions.md
- Workflow-10-ArchitectureOverview.md
- Workflow-11-FetchFiles.md

**Action:** 
1. Verify which files actually exist via fileserver.php
2. Assess which are still relevant
3. Update or deprecate as appropriate

---

## 🔧 UPDATE PATTERN TO FOLLOW

### Standard Workflow Update Process

For remaining workflows, apply these changes:

**1. Path Updates**
```markdown
# Find and replace:
NM##/                    → /sima/entries/
NMP##-[PROJECT]-##/      → /sima/projects/[project]/
/neural-maps/            → /sima/entries/
/function-catalogs/      → /sima/projects/[project]/function-references/
```

**2. Add fileserver.php Section**
```markdown
## 🔧 SESSION REQUIREMENTS

### Critical: Fresh File Access

**Before any file operations:**
1. Ensure fileserver.php fetched at session start
2. Use cache-busted URLs for all file access
3. Verify fetching fresh content (not cached)

**Why:** Anthropic caches files for weeks. fileserver.php 
bypasses cache with random ?v= parameters.

**REF:** /sima/entries/lessons/wisdom/WISD-06.md
```

**3. Add Shared Knowledge References**
```markdown
## 🔗 RELATED RESOURCES

**Standards:**
- /sima/shared/Artifact-Standards.md
- /sima/shared/File-Standards.md
- /sima/shared/Encoding-Standards.md
- /sima/shared/RED-FLAGS.md

**Architecture:**
- /sima/shared/SUGA-Architecture.md
[... other relevant shared resources ...]
```

**4. Update REF-ID References**
```markdown
# Update all references to:
LESS-## → /sima/entries/lessons/[category]/LESS-##.md
DEC-##  → /sima/entries/decisions/[category]/DEC-##.md
AP-##   → /sima/entries/anti-patterns/[category]/AP-##.md
INT-##  → /sima/entries/interfaces/INT-##.md
```

**5. Version Update**
```markdown
**Version:** 2.0.0  
**Date:** 2025-11-10  
**Updated:** SIMAv4 paths, fileserver.php, shared knowledge references
```

---

## ✅ QUALITY CHECKLIST

### For Each Updated Workflow

Apply this checklist to verify completeness:

- [ ] All NM##/ paths → /sima/entries/
- [ ] All NMP##/ paths → /sima/projects/
- [ ] fileserver.php requirement added
- [ ] Shared knowledge section added
- [ ] All REF-ID references updated with full paths
- [ ] Version bumped to 2.0.0
- [ ] Date updated to 2025-11-10
- [ ] "Updated:" note in header
- [ ] File ≤400 lines (split if needed)
- [ ] Examples updated with new paths
- [ ] Cross-references validated
- [ ] Filename in header
- [ ] UTF-8 encoding
- [ ] LF line endings

---

## 📁 FILE LOCATIONS

### Original Files (Need Updating)
```
/sima/support/workflows/Workflow-02-Debug-Issue.md
/sima/support/workflows/Workflow-03-Update-Interface.md
/sima/support/workflows/Workflow-04-Add-Gateway-Function.md
[... others TBD based on fileserver.php listing ...]
```

### Updated Files (Created This Session)
```
Workflow-01-Add-Feature.md                    ✅ Artifact created
Workflow-05-Create-Documentation-Entry.md     ✅ Artifact created
Workflow-Index.md                             ✅ Artifact created
```

### Deployment Steps
```
1. Review artifacts in Claude conversation
2. Save to local repository
3. Commit with message: "Update workflows to SIMAv4 (session 1 of N)"
4. Upload to server
5. Verify via fileserver.php (fresh URLs)
6. Test workflows in practice
```

---

## 🎯 NEXT SESSION PROMPT

**To continue this work, start next session with:**

```
Start Project Mode for SIMA

We're migrating workflow files from SIMAv3 to SIMAv4. Session 1 
completed updates to:
- Workflow-01-Add-Feature.md (v2.0.0) ✅
- Workflow-05-Create-Documentation-Entry.md (v2.0.0) ✅  
- Workflow-Index.md (v2.0.0) ✅

Please continue with:
1. Fetch remaining workflow files via fileserver.php
2. Update Workflow-02-Debug-Issue.md following the pattern in 
   Workflow-SIMAv4-Migration-Transition.md
3. Update Workflow-03-Update-Interface.md
4. Update Workflow-04-Add-Gateway-Function.md

Apply the standard update pattern:
- Paths: NM##/ → /sima/entries/
- Add: fileserver.php requirements
- Add: Shared knowledge references
- Update: All REF-ID paths
- Version: Bump to 2.0.0
```

---

## 📊 PROGRESS TRACKING

### Overall Migration Status

**Completed:** 3 workflows  
**Remaining:** 3+ workflows  
**Completion:** ~50% (core workflows)

**Timeline:**
- Session 1: 2025-11-10 (3 workflows completed)
- Session 2: TBD (3+ remaining workflows)
- Session 3: TBD (verification and testing)

---

## 🔍 VERIFICATION STEPS

### After All Workflows Updated

**1. Path Verification**
```bash
# Check for old path patterns
grep -r "NM##/" /sima/support/workflows/
grep -r "NMP##-" /sima/support/workflows/
grep -r "/neural-maps/" /sima/support/workflows/

# Should return: No results
```

**2. Reference Verification**
```bash
# Check all REF-IDs have full paths
grep -r "LESS-[0-9]" /sima/support/workflows/
grep -r "DEC-[0-9]" /sima/support/workflows/

# Verify each has /sima/entries/ or similar path
```

**3. Shared Knowledge Check**
```bash
# Verify all workflows reference shared standards
grep -r "/sima/shared/" /sima/support/workflows/

# Each workflow should have shared knowledge section
```

**4. fileserver.php Check**
```bash
# Verify all workflows mention cache-busting
grep -r "fileserver.php" /sima/support/workflows/
grep -r "WISD-06" /sima/support/workflows/

# Each workflow should reference fileserver.php
```

---

## 💡 LESSONS LEARNED

### What Worked Well

1. **Pattern-based updates** - Identifying common changes first
2. **Complete rewrites** - Workflow-05 benefited from fresh start
3. **Shared knowledge refs** - Reduces duplication, easier maintenance
4. **Domain classification** - Clear separation in Workflow-05

### What to Improve

1. **Batch verification** - Check all files exist before starting
2. **Cross-references** - Validate between workflows during updates
3. **Testing plan** - Need practical workflow testing after updates

---

## 🎓 TEMPLATE FOR FUTURE MIGRATIONS

### When Migrating Other Documentation Sets

Use this workflow migration as template for:
- Template files migration
- Tool files migration  
- Checklist files migration
- Any other SIMAv3 → SIMAv4 conversions

**Key principles:**
1. Identify all path patterns that changed
2. Add new requirements (fileserver.php, shared knowledge)
3. Update cross-references systematically
4. Maintain quality standards (≤400 lines, encoding, etc.)
5. Track progress with transition docs
6. Verify completeness before closing

---

**END OF TRANSITION DOCUMENT**

**Status:** Ready for Session 2  
**Next:** Continue with Workflow-02, 03, 04  
**Contact:** Use this document to resume workflow migration  
**Version:** 1.0.0 (Transition tracking)

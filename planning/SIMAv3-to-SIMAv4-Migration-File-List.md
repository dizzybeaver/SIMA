# File: SIMAv3-to-SIMAv4-Migration-File-List.md

**Version:** 1.0.0  
**Date:** 2025-10-29  
**Purpose:** Complete list of SIMAv3 files and their migration status to SIMAv4  
**Reference:** File Server URLs.md inventory

---

## 🎯 MIGRATION OVERVIEW

**Current System:** SIMAv3 (270 files)  
**New System:** SIMAv4 (99 files)  
**Migration Approach:** Selective migration, not 1:1 replacement

**Migration Categories:**
1. **✅ MIGRATE** - Keep and move to SIMAv4 structure
2. **🔄 SUPERSEDED** - Replaced by SIMAv4 equivalent
3. **📦 ARCHIVE** - Keep for reference, not active
4. **🗑️ DEPRECATE** - No longer needed
5. **⚠️ REVIEW** - Manual review required

---

## 📂 AWS00 DIRECTORY (2 files)

### AWS00-Master_Index.md
- **Current Location:** `/nmap/AWS/AWS00/AWS00-Master_Index.md`
- **Status:** 📦 ARCHIVE
- **Reason:** AWS-specific, not part of core SIMA
- **Action:** Keep in AWS directory, not migrated to core SIMA

### AWS00-Quick_Index.md
- **Current Location:** `/nmap/AWS/AWS00/AWS00-Quick_Index.md`
- **Status:** 📦 ARCHIVE
- **Reason:** AWS-specific, not part of core SIMA
- **Action:** Keep in AWS directory, not migrated to core SIMA

**Directory Summary:** Both files remain in `/nmap/AWS/AWS00/` - AWS-specific content

---

## 📂 AWS06 DIRECTORY (12 files)

All AWS06 LESS files are AWS Lambda-specific lessons learned.

### AWS06-LESS-01.md
- **Current Location:** `/nmap/AWS/AWS06/`
- **Status:** 📦 ARCHIVE
- **Reason:** AWS-specific operational lessons
- **Action:** Keep in AWS directory

### AWS06-LESS-03, 05, 06, 07, 08, 09, 10, 11, 12 (9 files)
- **Current Location:** `/nmap/AWS/AWS06/`
- **Status:** 📦 ARCHIVE
- **Reason:** AWS-specific lessons
- **Action:** Keep in AWS directory

**Directory Summary:** All 12 files remain in `/nmap/AWS/AWS06/` - AWS-specific content

---

## 📂 CONTEXT DIRECTORY (8 files)

### Custom Instructions for SUGA-ISP Development.md
- **Current Location:** `/nmap/Context/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/context/Custom-Instructions.md`
- **Action:** Replace with new version

### DEBUG-MODE-Context.md
- **Current Location:** `/nmap/Context/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** `/sima/context/DEBUG-MODE-Context.md`
- **Action:** Update and migrate

### MODE-SELECTOR.md
- **Current Location:** `/nmap/Context/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** Part of Custom-Instructions.md
- **Action:** Merged into new Custom Instructions

### PROJECT-MODE-Context.md
- **Current Location:** `/nmap/Context/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** `/sima/context/PROJECT-MODE-Context.md`
- **Action:** Update and migrate

### SERVER-CONFIG.md
- **Current Location:** `/nmap/Context/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** `/sima/context/SERVER-CONFIG.md`
- **Action:** Migrate as-is

### SESSION-START-Quick-Context.md
- **Current Location:** `/nmap/Context/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** `/sima/context/SESSION-START-Quick-Context.md`
- **Action:** Update and migrate

### SIMA-LEARNING-SESSION-START-Quick-Context.md
- **Current Location:** `/nmap/Context/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** `/sima/context/SIMA-LEARNING-SESSION-START-Quick-Context.md`
- **Action:** Update and migrate

### URL-GENERATOR-Template.md
- **Current Location:** `/nmap/Context/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/projects/tools/` web tools
- **Action:** Replaced by web-based tools

**Directory Summary:**
- Migrate: 5 files (mode contexts, server config)
- Superseded: 3 files (custom instructions, mode selector, URL generator)

---

## 📂 NM00 DIRECTORY (7 files)

### NM00-Quick_Index.md
- **Current Location:** `/nmap/NM00/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** Multiple quick indexes in `/sima/entries/*/` subdirectories
- **Action:** Replaced by category-specific quick indexes

### NM00A-Master_Index.md
- **Current Location:** `/nmap/NM00/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/planning/SIMAv4-Master-Control-Implementation.md`
- **Action:** Replaced by master control document

### NM00B-ZAPH.md, ZAPH-Tier1.md, ZAPH-Tier2.md, ZAPH-Tier3.md
- **Current Location:** `/nmap/NM00/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/entries/core/ARCH-04-ZAPH-Pattern.md`
- **Action:** Consolidated into single ARCH-04 entry

### NM00B - ZAPH Reorganization.md
- **Current Location:** `/nmap/NM00/`
- **Status:** 📦 ARCHIVE
- **Reason:** Historical reorganization document
- **Action:** Keep for reference, not migrated

**Directory Summary:** All superseded or archived - SIMAv4 has better organization

---

## 📂 NM01 DIRECTORY (20 files)

### NM01-Architecture-CoreArchitecture_Index.md
- **Current Location:** `/nmap/NM01/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/entries/core/Core-Architecture-Quick-Index.md`
- **Action:** Replaced

### NM01-Architecture-InterfacesCore_Index.md
- **Current Location:** `/nmap/NM01/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/entries/interfaces/Interface-Patterns-Quick-Index.md`
- **Action:** Replaced

### NM01-Architecture-InterfacesAdvanced_Index.md
- **Current Location:** `/nmap/NM01/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** Part of `/sima/entries/interfaces/` structure
- **Action:** Replaced

### NM01-Architecture-InterfacesCore_INT-01.md through INT-06.md (6 files)
- **Current Location:** `/nmap/NM01/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/entries/interfaces/INT-01.md` through `INT-06.md`
- **Action:** Rewritten in SIMAv4 format

### NM01-Architecture-InterfacesAdvanced_INT-07.md through INT-12.md (6 files)
- **Current Location:** `/nmap/NM01/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/entries/interfaces/INT-07.md` through `INT-12.md`
- **Action:** Rewritten in SIMAv4 format

### NM01-Architecture_ARCH-09.md
- **Current Location:** `/nmap/NM01/`
- **Status:** ⚠️ REVIEW
- **Reason:** Need to determine if content covered by ARCH-01 through ARCH-04
- **Action:** Review and potentially archive

### NM01-INDEX-Architecture.md
- **Current Location:** `/nmap/NM01/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** Multiple index files in SIMAv4
- **Action:** Replaced

### SUGA-Module-Size-Limits.md
- **Current Location:** `/nmap/NM01/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** Could go in `/sima/entries/core/` or `/sima/support/`
- **Action:** Migrate to appropriate SIMAv4 location

**Directory Summary:**
- Migrate: 1 file (module size limits)
- Superseded: 18 files (replaced by SIMAv4 entries)
- Review: 1 file (ARCH-09)

---

## 📂 NM02 DIRECTORY (17 files)

All NM02 files are dependency and import rule documentation.

### Import Rules (RULE-01 through RULE-04)
- **Current Location:** `/nmap/NM02/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/entries/languages/python/LANG-PY-02-Import-Organization.md`
- **Action:** Consolidated into language patterns

### Dependency Layers (DEP-01 through DEP-05)
- **Current Location:** `/nmap/NM02/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** Could enhance existing interface entries or standalone
- **Action:** Review and selectively migrate valuable content

### Interface Detail Files (CACHE-DEP, CONFIG-DEP, HTTP-DEP)
- **Current Location:** `/nmap/NM02/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** Integrated into INT-01, INT-02, INT-04 entries
- **Action:** Content absorbed into interface entries

### NM02-Dependencies_Index.md
- **Current Location:** `/nmap/NM02/`
- **Status:** 🔄 SUPERSEDED
- **Action:** Replaced by SIMAv4 index structure

**Directory Summary:**
- Migrate: 5 files (dependency layers - review for enhancement)
- Superseded: 12 files (rules and interface details)

---

## 📂 NM03 DIRECTORY (5 files)

Operations and flow documentation.

### NM03-Operations-ErrorHandling.md
- **Current Location:** `/nmap/NM03/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/entries/languages/python/LANG-PY-03-Exception-Handling.md`
- **Action:** Replaced

### NM03-Operations-Flows.md
- **Current Location:** `/nmap/NM03/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** Could enhance gateway or integration entries
- **Action:** Review and selectively migrate

### NM03-Operations-Pathways.md
- **Current Location:** `/nmap/NM03/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** Could enhance gateway entries
- **Action:** Review and selectively migrate

### NM03-Operations-Tracing.md
- **Current Location:** `/nmap/NM03/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** Could enhance debug interface or create new entry
- **Action:** Review and migrate

### NM03-Operations_Index.md
- **Current Location:** `/nmap/NM03/`
- **Status:** 🔄 SUPERSEDED
- **Action:** Replaced by SIMAv4 structure

**Directory Summary:**
- Migrate: 3 files (flows, pathways, tracing - valuable operational content)
- Superseded: 2 files (error handling, index)

---

## 📂 NM04 DIRECTORY (22 files)

Decision documentation (architectural, technical, operational).

### All DEC Files (DEC-01 through DEC-23)
- **Current Location:** `/nmap/NM04/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** `/sima/entries/decisions/` (new directory needed)
- **Action:** Migrate all - valuable historical decisions
- **Note:** These are project-specific decisions, should stay as-is

**Directory Summary:**
- Migrate: All 22 files - valuable decision history
- Create new directory: `/sima/entries/decisions/` or `/sima/nmp/decisions/`

---

## 📂 NM05 DIRECTORY (41 files)

Anti-patterns documentation.

### All AP Files (AP-01 through AP-28)
- **Current Location:** `/nmap/NM05/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** `/sima/entries/anti-patterns/` (new directory)
- **Action:** Migrate all - critical for avoiding mistakes
- **Note:** Generic anti-patterns, highly valuable

**Directory Summary:**
- Migrate: All 41 files - essential anti-pattern library
- Create new directory: `/sima/entries/anti-patterns/`

---

## 📂 NM06 DIRECTORY (69 files)

Lessons learned (LESS), bugs (BUG), and wisdom (WISD).

### BUG Files (BUG-01 through BUG-04)
- **Current Location:** `/nmap/NM06/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** `/sima/nmp/bugs/` (project-specific)
- **Action:** Migrate as project-specific bugs

### LESS Files (LESS-01 through LESS-54)
- **Current Location:** `/nmap/NM06/`
- **Status:** ⚠️ REVIEW
- **SIMAv4 Action:** 
  - Generic lessons → migrate to `/sima/entries/lessons/`
  - Project-specific → migrate to `/sima/nmp/lessons/`
- **Note:** Need to categorize each lesson

### WISD Files (WISD-01 through WISD-05)
- **Current Location:** `/nmap/NM06/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** `/sima/entries/wisdom/` (new directory)
- **Action:** Migrate all - synthesized wisdom

**Directory Summary:**
- Migrate: 69 files total (all valuable)
- Review needed: Categorize LESS files as generic vs project-specific
- Create new directories: `/sima/entries/lessons/`, `/sima/entries/wisdom/`, `/sima/nmp/bugs/`

---

## 📂 NM07 DIRECTORY (26 files)

Decision logic and frameworks.

### All DT and FW Files
- **Current Location:** `/nmap/NM07/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** `/sima/entries/decision-logic/` (new directory)
- **Action:** Migrate all - decision frameworks are valuable

### META-01
- **Current Location:** `/nmap/NM07/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** `/sima/entries/decision-logic/`
- **Action:** Migrate - meta-patterns valuable

**Directory Summary:**
- Migrate: All 26 files - decision logic frameworks
- Create new directory: `/sima/entries/decision-logic/`

---

## 📂 DOCS DIRECTORY (5 files)

### Deployment Guide - SIMA Mode System.md
- **Current Location:** `/nmap/Docs/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/deployment/SIMAv4-Deployment-Plan.md`
- **Action:** Replaced by comprehensive deployment suite

### Performance Metrics Guide.md
- **Current Location:** `/nmap/Docs/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** `/sima/documentation/` or `/sima/support/`
- **Action:** Migrate - still valuable

### SIMA v3 Complete Specification.md
- **Current Location:** `/nmap/Docs/`
- **Status:** 📦 ARCHIVE
- **Reason:** Historical v3 specification
- **Action:** Keep for reference, superseded by SIMAv4 documentation

### SIMA v3 Support Tools - Quick Reference Card.md
- **Current Location:** `/nmap/Docs/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/support/quick-reference/QRC-*.md` (3 files)
- **Action:** Replaced by SIMAv4 QRCs

### User Guide_ SIMA v3 Support Tools.md
- **Current Location:** `/nmap/Docs/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/documentation/SIMAv4-User-Guide.md`
- **Action:** Replaced

**Directory Summary:**
- Migrate: 1 file (performance metrics)
- Archive: 1 file (v3 specification)
- Superseded: 3 files (deployment, support tools, user guide)

---

## 📂 SUPPORT DIRECTORY (31 files)

### Anti-Patterns Checklists (5 files)
- **Current Location:** `/nmap/Support/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/support/checklists/Checklist-01-Code-Review.md`
- **Action:** Consolidated into comprehensive code review checklist

### REF-ID Directories (7 files)
- **Current Location:** `/nmap/Support/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/support/tools/Tool-01-REF-ID-Lookup.md`
- **Action:** Replaced by searchable tool

### File Server URLs.md
- **Current Location:** `/nmap/Support/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** Root or `/sima/` directory
- **Action:** Update with SIMAv4 URLs and migrate

### SIMA v3 Complete Specification.md
- **Current Location:** `/nmap/Support/`
- **Status:** 📦 ARCHIVE
- **Reason:** Duplicate of Docs version
- **Action:** Archive

### SESSION-START-Quick-Context.md
- **Current Location:** `/nmap/Support/`
- **Status:** ✅ MIGRATE
- **SIMAv4 Location:** `/sima/context/`
- **Action:** Already migrated

### Workflow Files (11 files)
- **Current Location:** `/nmap/Support/`
- **Status:** 🔄 SUPERSEDED
- **SIMAv4 Replacement:** `/sima/support/workflows/Workflow-*.md` (5 files)
- **Action:** Replaced by enhanced v4 workflows

**Directory Summary:**
- Migrate: 1 file (File Server URLs - update)
- Superseded: 23 files (workflows, checklists, REF-ID directories)
- Archive: 1 file (duplicate specification)

---

## 📂 TESTING DIRECTORY (12 files)

All testing files are project phase documentation.

### Phase Completion Certificates, Status Reports, etc.
- **Current Location:** `/nmap/Testing/`
- **Status:** 📦 ARCHIVE
- **Reason:** Historical phase documentation
- **Action:** Keep for project history, not migrated
- **Note:** SIMAv4 has its own phase certificates

**Directory Summary:** All 12 files archived - historical project tracking

---

## 📊 MIGRATION SUMMARY BY CATEGORY

### ✅ MIGRATE (Must Move to SIMAv4)
**Total:** ~160 files

**High Priority:**
- Context files: 5 files
- Decision documentation: 22 files (NM04)
- Anti-patterns: 41 files (NM05)
- Lessons learned: ~50 files (NM06 - generic ones)
- Decision logic: 26 files (NM07)
- File Server URLs: 1 file (update)
- Performance Metrics: 1 file
- Operational docs: 3 files (NM03)
- Module size limits: 1 file
- Project-specific bugs: 4 files (NM06)
- Wisdom: 5 files (NM06)

**Medium Priority:**
- Dependency layers: 5 files (NM02 - review)

### 🔄 SUPERSEDED (Replaced by SIMAv4)
**Total:** ~70 files

- Interface entries: 18 files (NM01 - rewritten)
- Import rules: 4 files (NM02 - consolidated)
- Interface detail: 3 files (NM02 - integrated)
- Error handling: 1 file (NM03 - replaced)
- Master indexes: 5 files (NM00 - new structure)
- ZAPH documentation: 4 files (NM00 - consolidated)
- Support files: 23 files (checklists, workflows, REF-ID)
- Documentation: 4 files (user guides, deployment)
- Mode selector: 1 file (merged)
- URL generator: 1 file (web tool)

### 📦 ARCHIVE (Keep for Reference)
**Total:** ~30 files

- AWS documentation: 14 files (AWS00, AWS06)
- ZAPH reorganization: 1 file
- v3 specifications: 2 files
- Testing/phase docs: 12 files
- Historical decisions: Various (if not migrated)

### 🗑️ DEPRECATE (No Longer Needed)
**Total:** ~10 files

- Duplicate files
- Obsolete workflows
- Outdated indexes

---

## 📁 NEW DIRECTORIES NEEDED IN SIMAV4

Based on migration, create these new directories:

```
/sima/entries/
├── anti-patterns/           # 41 files from NM05
├── decisions/               # 22 files from NM04
├── decision-logic/          # 26 files from NM07
├── lessons/                 # ~30 generic files from NM06
└── wisdom/                  # 5 files from NM06

/sima/nmp/
├── bugs/                    # 4 files from NM06
└── lessons/                 # ~20 project-specific from NM06
```

---

## 🎯 MIGRATION PRIORITIES

### Phase 1: Critical Files (Week 1)
1. Context files (5 files)
2. File Server URLs (1 file, updated)
3. Anti-patterns (41 files)
4. Decision documentation (22 files)

### Phase 2: High-Value Content (Week 2)
1. Decision logic (26 files)
2. Wisdom entries (5 files)
3. Project bugs (4 files)
4. Performance metrics (1 file)

### Phase 3: Review and Categorize (Week 3)
1. Lessons learned (50 files)
   - Categorize as generic vs project-specific
   - Migrate to appropriate directories
2. Operational docs (3 files)
3. Dependency layers (5 files - review)

### Phase 4: Archive and Cleanup (Week 4)
1. Archive AWS files (14 files)
2. Archive historical docs (12 files)
3. Deprecate obsolete files (~10 files)
4. Verify all migrations complete

---

## ✅ MIGRATION CHECKLIST

### Pre-Migration
- ⬜ Backup entire SIMAv3 system
- ⬜ Create new SIMAv4 directories
- ⬜ Update File Server URLs document
- ⬜ Review migration priorities with team

### During Migration
- ⬜ Migrate Phase 1 files (critical)
- ⬜ Update all REF-IDs to SIMAv4 format
- ⬜ Verify cross-references
- ⬜ Migrate Phase 2 files (high-value)
- ⬜ Categorize and migrate Phase 3 files
- ⬜ Archive and cleanup Phase 4 files

### Post-Migration
- ⬜ Verify all migrated files accessible
- ⬜ Test all cross-references
- ⬜ Update Custom Instructions
- ⬜ Test all mode contexts
- ⬜ Verify search functionality
- ⬜ Update documentation
- ⬜ Train users on new structure
- ⬜ Monitor for issues

---

## 📝 SPECIAL NOTES

### Files Requiring REF-ID Updates
All migrated files will need REF-IDs updated to match SIMAv4 naming:
- NM01 → INT (interface entries)
- NM04 → DEC (decisions)
- NM05 → AP (anti-patterns)
- NM06 → LESS, BUG, WISD
- NM07 → DT, FW (decision trees, frameworks)

### Cross-Reference Updates
After migration, run cross-reference validator to ensure:
- All REF-IDs valid
- No broken links
- All inherits chains correct
- No circular dependencies

### Testing After Migration
1. Test each mode context loads correctly
2. Verify project_knowledge_search finds new files
3. Test all workflows with new structure
4. Validate all checklists reference correct files
5. Verify all tools functional

---

## 📞 MIGRATION SUPPORT

**Migration Questions:**
- Refer to: `/sima/documentation/SIMAv4-Migration-Guide.md`
- Contact: [Migration support contact]

**Technical Issues:**
- Refer to: `/sima/deployment/SIMAv4-Deployment-Troubleshooting-Guide.md`
- Contact: [Technical support contact]

---

**END OF MIGRATION FILE LIST**

**Total Files to Process:** ~270 files  
**Estimated Migration Duration:** 4 weeks  
**Priority:** High-value content first  
**Quality:** Maintain 100% accuracy during migration

**Use this document to systematically migrate SIMAv3 to SIMAv4.**

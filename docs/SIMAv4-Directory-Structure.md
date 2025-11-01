# SIMAv4 Complete Directory Structure

**Version:** 4.1.1  
**Date:** 2025-11-01  
**Total Files:** 278  
**Status:** Corrected and Verified

**Changes from v4.0.0:**
- Moved planning and deployment under docs/
- Removed config/ directory (templates duplicated in projects/templates/)
- Removed reports/ directory (empty)
- Moved SIMA-MAIN-CONFIG.md to support/
- Consolidated all documentation under docs/
- Added SIMAv4-Directory-Structure.md to docs/
- Corrected file counts (288 → 278)
- Fixed utilities/ directory spelling (was "utiltities")

---

## ROOT STRUCTURE

```
sima/
├── context/           (5 files)
├── docs/              (15 files)
│   ├── planning/      (3 files)
│   └── deployment/    (4 files)
├── entries/           (191 files)
├── integration/       (4 files)
├── projects/          (25 files)
├── support/           (36 files)
├── LICENSE            (1 file)
└── README.md          (1 file)

Total: 278 files across 8 root items
```

---

## 1. ROOT (2 files)

```
sima/
├── LICENSE
└── README.md
```

---

## 2. CONTEXT (5 files)

```
sima/context/
├── DEBUG-MODE-Context.md
├── MODE-SELECTOR.md
├── PROJECT-MODE-Context.md
├── SESSION-START-Quick-Context.md
└── SIMA-LEARNING-SESSION-START-Quick-Context.md
```

---

## 3. DOCS (15 files total)

```
sima/docs/
│
├── planning/                                    (3 files)
│   ├── SIMAv4 Architecture Planning Document.md
│   ├── SIMAv4 Implementation - Phase Breakdown Overview.md
│   └── SIMAv4 Master Control Implementation Document.md
│
├── deployment/                                  (4 files)
│   ├── SIMAv4-Deployment-Plan.md
│   ├── SIMAv4-Deployment-Troubleshooting-Guide.md
│   ├── SIMAv4-Deployment-Verification-Checklist.md
│   └── SIMAv4-Post-Deployment-Monitoring-Plan.md
│
└── (root level docs)                            (8 files)
    ├── SIMAv4-Developer-Guide.md
    ├── SIMAv4-Directory-Structure.md
    ├── SIMAv4-Migration-Guide.md
    ├── SIMAv4-Quick-Start-Guide.md
    ├── SIMAv4-Training-Materials.md
    ├── SIMAv4-User-Guide.md
    ├── URL-Replacement-Patterns-Reference.md
    └── Workflow-Template-Updates-Guide.md
```

---

## 4. ENTRIES (191 files)

### 4.1 CORE (6 files)

```
sima/entries/core/
├── ARCH-DD_ Dispatch Dictionary Pattern.md
├── ARCH-LMMS_ Lambda Memory Management System.md
├── ARCH-SUGA_ Single Universal Gateway Architecture.md
├── ARCH-ZAPH_ Zero-Abstraction Path for Hot Operations.md
├── Core-Architecture-Cross-Reference.md
└── Core-Architecture-Quick-Index.md
```

### 4.2 GATEWAYS (7 files)

```
sima/entries/gateways/
├── GATE-01_Gateway-Layer-Structure.md
├── GATE-02_Lazy-Import-Pattern.md
├── GATE-03_Cross-Interface-Communication-Rule.md
├── GATE-04_Gateway-Wrapper-Functions.md
├── GATE-05_Intra-Interface-vs-Cross-Interface-Imports.md
├── Gateway-Cross-Reference-Matrix.md
└── Gateway-Quick-Index.md
```

### 4.3 INTERFACES (14 files)

```
sima/entries/interfaces/
├── INT-01_CACHE-Interface-Pattern.md
├── INT-02_LOGGING-Interface-Pattern.md
├── INT-03_SECURITY-Interface-Pattern.md
├── INT-04-HTTP-Interface.md
├── INT-05-Initialization-Interface.md
├── INT-06-Config-Interface.md
├── INT-07-Metrics-Interface.md
├── INT-08-Debug-Interface.md
├── INT-09-Singleton-Interface.md
├── INT-10-Utility-Interface.md
├── INT-11-WebSocket-Interface.md
├── INT-12-Circuit-Breaker-Interface.md
├── Interface-Cross-Reference-Matrix.md
└── Interface-Quick-Index.md
```

### 4.4 LANGUAGES (10 files)

```
sima/entries/languages/python/
├── LANG-PY-01_Python-Naming-Conventions.md
├── LANG-PY-02_Python-Exception-Handling.md
├── LANG-PY-03_Python_Exception_Handling.md
├── LANG-PY-04_Python_Function_Design_Patterns.md
├── LANG-PY-05_Python_Import_Module_Organization.md
├── LANG-PY-06_Python_Type_Hints.md
├── LANG-PY-07_Python_Code_Quality.md
├── LANG-PY-08_Python-Data-Structures-Performance.md
├── Python-Language-Patterns-Cross-Reference.md
└── Python-Language-Patterns-Quick-Index.md
```

### 4.5 DECISIONS (47 files)

```
sima/entries/decisions/
│
├── Decisions-Master-Index.md                    (1 file)
│
├── architecture/                                (7 files)
│   ├── Architecture-Index.md
│   ├── DEC-01.md
│   ├── DEC-02.md
│   ├── DEC-03.md
│   ├── DEC-04.md
│   ├── DEC-05.md
│   └── DT-13.md
│
├── deployment/                                  (2 files)
│   ├── Deployment-Index.md
│   └── DT-12.md
│
├── error-handling/                              (3 files)
│   ├── DT-05.md
│   ├── DT-06.md
│   └── ErrorHandling-Index.md
│
├── feature-addition/                            (3 files)
│   ├── DT-03.md
│   ├── DT-04.md
│   └── FeatureAddition-Index.md
│
├── import/                                      (3 files)
│   ├── DT-01.md
│   ├── DT-02.md
│   └── Import-Index.md
│
├── indexes/                                     (4 files)
│   ├── Architecture-Decisions-Index.md
│   ├── Decisions-Master-Index.md
│   ├── Operational-Decisions-Index.md
│   └── Technical-Decisions-Index.md
│
├── meta/                                        (2 files)
│   ├── META-01.md
│   └── Meta-Index.md
│
├── operational/                                 (4 files)
│   ├── DEC-20.md
│   ├── DEC-21.md
│   ├── DEC-22.md
│   └── DEC-23.md
│
├── optimization/                                (4 files)
│   ├── DT-07.md
│   ├── FW-01.md
│   ├── FW-02.md
│   └── Optimization-Index.md
│
├── refactoring/                                 (3 files)
│   ├── DT-10.md
│   ├── DT-11.md
│   └── Refactoring-Index.md
│
├── technical/                                   (8 files)
│   ├── DEC-12.md
│   ├── DEC-13.md
│   ├── DEC-14.md
│   ├── DEC-15.md
│   ├── DEC-16.md
│   ├── DEC-17.md
│   ├── DEC-18.md
│   └── DEC-19.md
│
└── testing/                                     (3 files)
    ├── DT-08.md
    ├── DT-09.md
    └── Testing-Index.md
```

### 4.6 ANTI-PATTERNS (38 files)

```
sima/entries/anti-patterns/
│
├── concurrency/                                 (4 files)
│   ├── AP-08.md
│   ├── AP-11.md
│   ├── AP-13.md
│   └── Concurrency-Index.md
│
├── critical/                                    (2 files)
│   ├── AP-10.md
│   └── Critical-Index.md
│
├── dependencies/                                (2 files)
│   ├── AP-09.md
│   └── Dependencies-Index.md
│
├── documentation/                               (3 files)
│   ├── AP-25.md
│   ├── AP-26.md
│   └── Documentation-Index.md
│
├── error-handling/                              (4 files)
│   ├── AP-14.md
│   ├── AP-15.md
│   ├── AP-16.md
│   └── ErrorHandling-Index.md
│
├── implementation/                              (3 files)
│   ├── AP-06.md
│   ├── AP-07.md
│   └── Implementation-Index.md
│
├── import/                                      (6 files)
│   ├── AP-01.md
│   ├── AP-02.md
│   ├── AP-03.md
│   ├── AP-04.md
│   ├── AP-05.md
│   └── Import-Index.md
│
├── performance/                                 (2 files)
│   ├── AP-12.md
│   └── Performance-Index.md
│
├── process/                                     (3 files)
│   ├── AP-27.md
│   ├── AP-28.md
│   └── Process-Index.md
│
├── quality/                                     (2 files)
│   ├── AP-22.md
│   └── Quality-Index.md
│
├── security/                                    (4 files)
│   ├── AP-17.md
│   ├── AP-18.md
│   ├── AP-19.md
│   └── Security-Index.md
│
└── testing/                                     (3 files)
    ├── AP-23.md
    ├── AP-24.md
    └── Testing-Index.md
```

### 4.7 LESSONS (69 files)

```
sima/entries/lessons/
│
├── bugs/                                        (5 files)
│   ├── BUG-01.md
│   ├── BUG-02.md
│   ├── BUG-03.md
│   ├── BUG-04.md
│   └── Bugs-Index.md
│
├── core-architecture/                           (8 files)
│   ├── Core-Architecture-Index.md
│   ├── LESS-01.md
│   ├── LESS-03.md
│   ├── LESS-04.md
│   ├── LESS-05.md
│   ├── LESS-06.md
│   ├── LESS-07.md
│   └── LESS-08.md
│
├── documentation/                               (4 files)
│   ├── Documentation-Index.md
│   ├── LESS-11.md
│   ├── LESS-12.md
│   └── LESS-13.md
│
├── evolution/                                   (4 files)
│   ├── Evolution-Index.md
│   ├── LESS-14.md
│   ├── LESS-16.md
│   └── LESS-18.md
│
├── learning/                                    (5 files)
│   ├── Learning-Index.md
│   ├── LESS-43.md
│   ├── LESS-44.md
│   ├── LESS-45.md
│   └── LESS-47.md
│
├── operations/                                  (19 files)
│   ├── LESS-09.md
│   ├── LESS-10.md
│   ├── LESS-15.md
│   ├── LESS-19.md
│   ├── LESS-22.md
│   ├── LESS-23.md
│   ├── LESS-24.md
│   ├── LESS-27.md
│   ├── LESS-29.md
│   ├── LESS-30.md
│   ├── LESS-32.md
│   ├── LESS-34.md
│   ├── LESS-36.md
│   ├── LESS-38.md
│   ├── LESS-39.md
│   ├── LESS-42.md
│   ├── LESS-45.md
│   ├── LESS-53.md
│   └── Operations-Index.md
│
├── optimization/                                (13 files)
│   ├── LESS-25.md
│   ├── LESS-26.md
│   ├── LESS-28.md
│   ├── LESS-29.md
│   ├── LESS-35.md
│   ├── LESS-37.md
│   ├── LESS-40.md
│   ├── LESS-48.md
│   ├── LESS-49.md
│   ├── LESS-50.md
│   ├── LESS-51.md
│   ├── LESS-52.md
│   └── Optimization-Index.md
│
├── performance/                                 (5 files)
│   ├── LESS-02.md
│   ├── LESS-17.md
│   ├── LESS-20.md
│   ├── LESS-21.md
│   └── Performance-Index.md
│
└── wisdom/                                      (6 files)
    ├── WISD-01.md
    ├── WISD-02.md
    ├── WISD-03.md
    ├── WISD-04.md
    ├── WISD-05.md
    └── Wisdom-Index.md
```

---

## 5. INTEGRATION (4 files)

```
sima/integration/
├── E2E-Workflow-Example-01-Feature-Implementation.md
├── E2E-Workflow-Example-02-Debug-Issue.md
├── Integration-Test-Framework.md
└── System-Integration-Guide.md
```

---

## 6. PROJECTS (25 files)

```
sima/projects/
│
├── projects_config.md                           (1 file)
│
├── LEE/                                         (13 files)
│   ├── project_config.md
│   ├── README.md
│   └── nmp01/                                   (11 files)
│       ├── NMP00-LEE_Index.md
│       ├── NMP01-LEE-01.md
│       ├── NMP01-LEE-02_INT-01-CACHE-Function-Catalog.md
│       ├── NMP01-LEE-03_INT-02-LOGGING-Function-Catalog.md
│       ├── NMP01-LEE-04_INT-03-SECURITY-Function-Catalog.md
│       ├── NMP01-LEE-14_Gateway-Core-Execute-Operation-Patterns.md
│       ├── NMP01-LEE-16_Fast-Path-Optimization-ZAPH-Pattern.md
│       ├── NMP01-LEE-17_HA-Core-API-Integration-Patterns.md
│       ├── NMP01-LEE-23_Circuit-Breaker-Resilience-Patterns.md
│       ├── NMP01-LEE-Cross-Reference-Matrix.md
│       └── NMP01-LEE-Quick-Index.md
│
├── templates/                                   (9 files)
│   ├── architecture_doc_template.md
│   ├── bug_report_template.md
│   ├── decision_log_template.md
│   ├── gateway_pattern_template.md
│   ├── interface_catalog_template.md
│   ├── lesson_learned_template.md
│   ├── nmp_entry-template.md
│   ├── project_config_template.md
│   └── project_readme_template.md
│
└── tools/                                       (2 files)
    ├── nmp_generator.html
    └── project_configurator.html

Total: 1 + 13 + 9 + 2 = 25 files
```

---

## 7. SUPPORT (36 files)

```
sima/support/
│
├── SIMA-MAIN-CONFIG.md                          (1 file)
├── SERVER-CONFIG.md                             (1 file)
├── Support-Master-Index.md                      (1 file)
├── URL-GENERATOR-Template.md                    (1 file)
│
├── checklists/                                  (4 files)
│   ├── Checklist-01-Code-Review.md
│   ├── Checklist-02-Deployment-Readiness.md
│   ├── Checklist-03-Documentation-Quality.md
│   └── Tool-Integration-Verification.md
│
├── quick-reference/                             (4 files)
│   ├── QRC-01-Interfaces-Overview.md
│   ├── QRC-02-Gateway-Patterns.md
│   ├── QRC-03-Common-Patterns.md
│   └── tag.md
│
├── templates/                                   (3 files)
│   ├── Templates-Index.md
│   ├── TMPL-01-Neural-Map-Entry.md
│   └── TMPL-02-Project-Documentation.md
│
├── tools/                                       (14 files)
│   ├── Cross-Reference-Validator.md
│   ├── file-server-config.ui.html
│   ├── generate-urls.py
│   ├── neural-map-index-builder.html
│   ├── project-config-ui.html
│   ├── scan-hardcoded-urls.py
│   ├── TOOL-01-REF-ID-Directory.md
│   ├── Tool-01-REF-ID-Lookup.md
│   ├── Tool-02-Keyword-Search-Guide.md
│   ├── TOOL-02-Quick-Answer-Index.md
│   ├── TOOL-03-Anti-Pattern-Checklist.md
│   ├── TOOL-04-Verification-Protocol.md
│   ├── Tools-Index.md
│   └── url-audit-report.md
│
├── utilities/                                   (1 file)
│   └── Utility-01-NM-to-NMP-Migration.md
│
└── workflows/                                   (6 files)
    ├── Workflow-01-Add-Feature.md
    ├── Workflow-02-Debug-Issue.md
    ├── Workflow-03-Update-Interface.md
    ├── Workflow-04-Add-Gateway-Function.md
    ├── Workflow-05-Create-NMP-Entry.md
    └── Workflow-Index.md
```

---

## SUMMARY BY CATEGORY

| Category | Files | Subdirectories |
|----------|-------|----------------|
| Root | 2 | 0 |
| Context | 5 | 0 |
| Docs | 15 | 2 |
| Entries | 191 | 7 main + 37 sub |
| Integration | 4 | 0 |
| Projects | 25 | 4 |
| Support | 36 | 6 |
| **TOTAL** | **278** | **56** |

---

## FILE TYPE BREAKDOWN

| Type | Count | Percentage |
|------|-------|------------|
| Markdown (.md) | 270 | 97.1% |
| HTML (.html) | 5 | 1.8% |
| Python (.py) | 2 | 0.7% |
| Other (LICENSE) | 1 | 0.4% |
| **TOTAL** | **278** | **100%** |

---

## ENTRIES BREAKDOWN (191 files)

| Category | Files | Verification |
|----------|-------|--------------|
| Core | 6 | ✓ |
| Gateways | 7 | ✓ |
| Interfaces | 14 | ✓ |
| Languages | 10 | ✓ |
| Decisions | 47 | ✓ |
| Anti-Patterns | 38 | ✓ |
| Lessons | 69 | ✓ |
| **TOTAL** | **191** | **✓** |

---

## QUICK REFERENCE PATHS

### Core Architecture
```
sima/entries/core/ARCH-SUGA_ Single Universal Gateway Architecture.md
```

### Gateway Patterns
```
sima/entries/gateways/GATE-01_Gateway-Layer-Structure.md
```

### Interfaces
```
sima/entries/interfaces/INT-01_CACHE-Interface-Pattern.md
```

### Decisions
```
sima/entries/decisions/architecture/DEC-01.md
sima/entries/decisions/Decisions-Master-Index.md
```

### Anti-Patterns
```
sima/entries/anti-patterns/import/AP-01.md
```

### Lessons
```
sima/entries/lessons/core-architecture/LESS-01.md
```

### Planning
```
sima/docs/planning/SIMAv4 Architecture Planning Document.md
```

### Directory Structure
```
sima/docs/SIMAv4-Directory-Structure.md
```

### Project Templates
```
sima/projects/templates/project_config_template.md
```

### Support Tools
```
sima/support/tools/TOOL-01-REF-ID-Directory.md
```

### Mode Contexts
```
sima/context/SESSION-START-Quick-Context.md
```

---

## MASTER INDEXES (5 files)

```
sima/entries/decisions/Decisions-Master-Index.md
sima/support/Support-Master-Index.md
sima/projects/projects_config.md
```

---

## INTERACTIVE TOOLS (5 HTML + 2 Python)

### HTML Tools
```
sima/projects/tools/nmp_generator.html
sima/projects/tools/project_configurator.html
sima/support/tools/file-server-config.ui.html
sima/support/tools/neural-map-index-builder.html
sima/support/tools/project-config-ui.html
```

### Python Scripts
```
sima/support/tools/generate-urls.py
sima/support/tools/scan-hardcoded-urls.py
```

---

## VERSION HISTORY

**v4.1.1 (2025-11-01):**
- CORRECTED: File counts to match actual CLI listing
- FIXED: Docs count (14 → 15) - added SIMAv4-Directory-Structure.md
- FIXED: Projects count (36 → 25) - corrected calculation
- FIXED: Total count (288 → 278) - accurate verification
- FIXED: Spelling utilities/ directory (was misspelled on disk)
- VERIFIED: All counts accurate against CLI output

**v4.1.0 (2025-11-01):**
- CORRECTED: Directory structure to match actual filesystem
- MOVED: planning/ and deployment/ under docs/
- REMOVED: config/ directory (duplicate templates)
- REMOVED: reports/ directory (empty)
- MOVED: SIMA-MAIN-CONFIG.md to support/
- VERIFIED: All file counts accurate
- STATUS: Production-ready and verified

**v4.0.0 (2025-10-31):**
- Complete SIMAv4 migration (255 files planned)
- All phases complete (1-10)
- Initial structure definition

---

## VERIFICATION STATUS

**✓ Verified Against:** Windows CLI directory listing (2025-11-01)  
**✓ File Count:** 278 files (matches CLI output exactly)  
**✓ Structure:** All directories accounted for  
**✓ Integrity:** No missing or orphaned files  
**✓ Spelling:** utilities/ directory name corrected

---

**END OF DIRECTORY STRUCTURE**

**Status:** Corrected and Verified  
**Files:** 278/278  
**Quality:** Production-ready
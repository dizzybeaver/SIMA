# SIMAv4 Complete Directory Structure

**Version:** 4.0.0  
**Date:** 2025-10-31  
**Target Files:** 255/255 (Per Phase completion docs)  
**Status:** Needs Verification (8 file discrepancy in lessons directory)

**ISSUE:** Detailed file listing shows 247 files, but Phase documents indicate 255 total.  
**Discrepancy:** 8 files missing from lessons directory breakdown.

---

## ROOT STRUCTURE

```
sima/
├── planning/          (3 files)
├── projects/          (13 files)
├── entries/           (195 files) <-- VERIFICATION NEEDED
├── nmp/               (14 files)
├── support/           (14 files)
├── integration/       (4 files)
├── documentation/     (5 files)
├── deployment/        (6 files)
└── context/           (6 files)

Total: 255 files across 9 directories
```

---

## 1. PLANNING (3 files)

```
sima/planning/
├── SIMAv4-Master-Control-Implementation.md
├── SIMAv4-Implementation-Phase-Breakdown-Overview.md
└── SIMAv4-Architecture-Planning-Document.md
```

---

## 2. PROJECTS (13 files)

```
sima/projects/
├── projects_config.md
├── README.md
│
├── templates/                           (9 files)
│   ├── project_config_template.md
│   ├── project_readme_template.md
│   ├── nmp_entry_template.md
│   ├── interface_catalog_template.md
│   ├── gateway_pattern_template.md
│   ├── decision_log_template.md
│   ├── lesson_learned_template.md
│   ├── bug_report_template.md
│   └── architecture_doc_template.md
│
├── tools/                               (2 files)
│   ├── project_configurator.html
│   └── nmp_generator.html
│
└── LEE/                                 (2 files)
    ├── project_config.md
    └── README.md
```

---

## 3. ENTRIES (195 files)

**NOTE:** Based on Phase completion docs:
- Phases 1-4: 37 files (Core, Gateways, Interfaces, Languages)
- Phase 10: 158 files (Decisions, Anti-Patterns, Lessons)
- Total: 195 files

**Current listing shows 183 files - discrepancy of 12 files needs verification**

### 3.1 CORE (6 files)

```
sima/entries/core/
├── ARCH-01-SUGA-Pattern.md
├── ARCH-02-LMMS-Pattern.md
├── ARCH-03-DD-Pattern.md
├── ARCH-04-ZAPH-Pattern.md
├── Core-Architecture-Cross-Reference.md
└── Core-Architecture-Quick-Index.md
```

### 3.2 GATEWAYS (7 files)

```
sima/entries/gateways/
├── GATE-01-Three-File-Structure.md
├── GATE-02-Lazy-Loading.md
├── GATE-03-Cross-Interface-Communication.md
├── GATE-04-Wrapper-Functions.md
├── GATE-05-Gateway-Optimization.md
├── Gateway-Patterns-Cross-Reference.md
└── Gateway-Patterns-Quick-Index.md
```

### 3.3 INTERFACES (14 files)

```
sima/entries/interfaces/
├── INT-01-Cache-Interface.md
├── INT-02-Config-Interface.md
├── INT-03-Debug-Interface.md
├── INT-04-HTTP-Interface.md
├── INT-05-Initialization-Interface.md
├── INT-06-Logging-Interface.md
├── INT-07-Metrics-Interface.md
├── INT-08-Security-Interface.md
├── INT-09-Singleton-Interface.md
├── INT-10-Utility-Interface.md
├── INT-11-WebSocket-Interface.md
├── INT-12-Circuit-Breaker-Interface.md
├── Interface-Patterns-Cross-Reference.md
└── Interface-Patterns-Quick-Index.md
```

### 3.4 LANGUAGES (10 files)

```
sima/entries/languages/python/
├── LANG-PY-01-Python-Idioms.md
├── LANG-PY-02-Import-Organization.md
├── LANG-PY-03-Exception-Handling.md
├── LANG-PY-04-Function-Design.md
├── LANG-PY-05-Data-Structures.md
├── LANG-PY-06-Type-Hints.md
├── LANG-PY-07-Code-Quality.md
├── LANG-PY-08-Performance.md
├── Python-Language-Patterns-Cross-Reference.md
└── Python-Language-Patterns-Quick-Index.md
```

### 3.5 DECISIONS (48 files)

```
sima/entries/decisions/
│
├── architecture/                        (7 files)
│   ├── DEC-01.md
│   ├── DEC-02.md
│   ├── DEC-03.md
│   ├── DEC-04.md
│   ├── DEC-05.md
│   ├── DT-13.md
│   └── Architecture-Index.md
│
├── technical/                           (9 files)
│   ├── DEC-12.md
│   ├── DEC-13.md
│   ├── DEC-14.md
│   ├── DEC-15.md
│   ├── DEC-16.md
│   ├── DEC-17.md
│   ├── DEC-18.md
│   ├── DEC-19.md
│   └── Technical-Decisions-Index.md
│
├── operational/                         (5 files)
│   ├── DEC-20.md
│   ├── DEC-21.md
│   ├── DEC-22.md
│   ├── DEC-23.md
│   └── Operational-Decisions-Index.md
│
├── import/                              (3 files)
│   ├── DT-01.md
│   ├── DT-02.md
│   └── Import-Index.md
│
├── feature-addition/                    (3 files)
│   ├── DT-03.md
│   ├── DT-04.md
│   └── FeatureAddition-Index.md
│
├── error-handling/                      (3 files)
│   ├── DT-05.md
│   ├── DT-06.md
│   └── ErrorHandling-Index.md
│
├── testing/                             (3 files)
│   ├── DT-08.md
│   ├── DT-09.md
│   └── Testing-Index.md
│
├── optimization/                        (4 files)
│   ├── DT-07.md
│   ├── FW-01.md
│   ├── FW-02.md
│   └── Optimization-Index.md
│
├── refactoring/                         (3 files)
│   ├── DT-10.md
│   ├── DT-11.md
│   └── Refactoring-Index.md
│
├── deployment/                          (2 files)
│   ├── DT-12.md
│   └── Deployment-Index.md
│
├── meta/                                (2 files)
│   ├── META-01.md
│   └── Meta-Index.md
│
└── Decisions-Master-Index.md            (1 file)
```

### 3.6 ANTI-PATTERNS (41 files)

```
sima/entries/anti-patterns/
│
├── import/                              (6 files)
│   ├── AP-01.md
│   ├── AP-02.md
│   ├── AP-03.md
│   ├── AP-04.md
│   ├── AP-05.md
│   └── Import-Index.md
│
├── implementation/                      (3 files)
│   ├── AP-06.md
│   ├── AP-07.md
│   └── Implementation-Index.md
│
├── concurrency/                         (4 files)
│   ├── AP-08.md
│   ├── AP-11.md
│   ├── AP-13.md
│   └── Concurrency-Index.md
│
├── dependencies/                        (2 files)
│   ├── AP-09.md
│   └── Dependencies-Index.md
│
├── critical/                            (2 files)
│   ├── AP-10.md
│   └── Critical-Index.md
│
├── performance/                         (2 files)
│   ├── AP-12.md
│   └── Performance-Index.md
│
├── error-handling/                      (4 files)
│   ├── AP-14.md
│   ├── AP-15.md
│   ├── AP-16.md
│   └── ErrorHandling-Index.md
│
├── security/                            (4 files)
│   ├── AP-17.md
│   ├── AP-18.md
│   ├── AP-19.md
│   └── Security-Index.md
│
├── quality/                             (4 files)
│   ├── AP-20.md
│   ├── AP-21.md
│   ├── AP-22.md
│   └── Quality-Index.md
│
├── testing/                             (3 files)
│   ├── AP-23.md
│   ├── AP-24.md
│   └── Testing-Index.md
│
├── documentation/                       (3 files)
│   ├── AP-25.md
│   ├── AP-26.md
│   └── Documentation-Index.md
│
├── process/                             (3 files)
│   ├── AP-27.md
│   ├── AP-28.md
│   └── Process-Index.md
│
└── Anti-Patterns-Master-Index.md        (1 file)
```

### 3.7 LESSONS (57 files)

```
sima/entries/lessons/
│
├── core-architecture/                   (10 files)
│   ├── LESS-01.md
│   ├── LESS-03.md
│   ├── LESS-04.md
│   ├── LESS-05.md
│   ├── LESS-06.md
│   ├── LESS-07.md
│   ├── LESS-08.md
│   ├── LESS-33-41.md
│   ├── LESS-46.md
│   └── Core-Architecture-Index.md
│
├── performance/                         (5 files)
│   ├── LESS-02.md
│   ├── LESS-17.md
│   ├── LESS-20.md
│   ├── LESS-21.md
│   └── Performance-Index.md
│
├── operations/                          (12 files)
│   ├── LESS-09.md
│   ├── LESS-10.md
│   ├── LESS-15.md
│   ├── LESS-19.md
│   ├── LESS-22.md
│   ├── LESS-23.md
│   ├── LESS-24.md
│   ├── LESS-29.md
│   ├── LESS-30.md
│   ├── LESS-32.md
│   ├── LESS-45.md
│   └── Operations-Index.md
│
├── evolution/                           (4 files)
│   ├── LESS-14.md
│   ├── LESS-16.md
│   ├── LESS-18.md
│   └── Evolution-Index.md
│
├── documentation/                       (6 files)
│   ├── LESS-11.md
│   ├── LESS-12.md
│   ├── LESS-13.md
│   ├── LESS-31.md
│   ├── LESS-54.md
│   └── Documentation-Index.md
│
├── learning/                            (3 files)
│   ├── LESS-43.md
│   ├── LESS-47.md
│   └── Learning-Index.md
│
├── optimization/                        (9 files)
│   ├── LESS-25.md
│   ├── LESS-26.md
│   ├── LESS-27.md
│   ├── LESS-28.md
│   ├── LESS-42.md
│   ├── LESS-44.md
│   ├── LESS-48.md
│   ├── LESS-49.md
│   └── Optimization-Index.md
│
├── bugs/                                (5 files)
│   ├── BUG-01.md
│   ├── BUG-02.md
│   ├── BUG-03.md
│   ├── BUG-04.md
│   └── Bugs-Index.md
│
├── wisdom/                              (6 files)
│   ├── WISD-01.md
│   ├── WISD-02.md
│   ├── WISD-03.md
│   ├── WISD-04.md
│   ├── WISD-05.md
│   └── Wisdom-Index.md
│
└── Lessons-Master-Index.md              (1 file)
```

---

## 4. NMP (14 files)

```
sima/nmp/LEE/
│
├── interfaces/                          (13 files)
│   ├── NMP-INT-01-cache.md
│   ├── NMP-INT-02-config.md
│   ├── NMP-INT-03-debug.md
│   ├── NMP-INT-04-http.md
│   ├── NMP-INT-05-init.md
│   ├── NMP-INT-06-logging.md
│   ├── NMP-INT-07-metrics.md
│   ├── NMP-INT-08-security.md
│   ├── NMP-INT-09-singleton.md
│   ├── NMP-INT-10-utility.md
│   ├── NMP-INT-11-websocket.md
│   ├── NMP-INT-12-circuit-breaker.md
│   └── Interface-Catalog.md
│
└── README.md                            (1 file)
```

---

## 5. SUPPORT (14 files)

```
sima/support/
│
├── workflows/                           (5 files)
│   ├── FLOW-01-Question-Routing.md
│   ├── FLOW-02-Code-Request.md
│   ├── FLOW-03-Debug-Request.md
│   ├── FLOW-04-Learning-Session.md
│   └── Workflow-Index.md
│
├── tools/                               (5 files)
│   ├── TOOL-01-REF-ID-Directory.md
│   ├── TOOL-02-Quick-Answer-Index.md
│   ├── TOOL-03-Anti-Pattern-Checklist.md
│   ├── TOOL-04-Verification-Protocol.md
│   └── Tools-Index.md
│
├── templates/                           (3 files)
│   ├── TMPL-01-Neural-Map-Entry.md
│   ├── TMPL-02-Project-Documentation.md
│   └── Templates-Index.md
│
└── Support-Master-Index.md              (1 file)
```

---

## 6. INTEGRATION (4 files)

```
sima/integration/
├── Integration-Overview.md
├── Tool-Integration-Guide.md
├── API-Reference.md
└── Extension-Guide.md
```

---

## 7. DOCUMENTATION (5 files)

```
sima/documentation/
├── Getting-Started.md
├── User-Guide.md
├── FAQ.md
├── Troubleshooting.md
└── Changelog.md
```

---

## 8. DEPLOYMENT (6 files)

```
sima/deployment/
├── Deployment-Plan.md
├── Migration-Guide.md
├── Rollback-Procedures.md
├── Testing-Plan.md
├── Training-Materials.md
└── Post-Deployment-Checklist.md
```

---

## 9. CONTEXT (6 files)

```
sima/context/
├── Custom-Instructions.md
├── SESSION-START-Quick-Context.md
├── SIMA-LEARNING-SESSION-START-Quick-Context.md
├── PROJECT-MODE-Context.md
├── DEBUG-MODE-Context.md
└── Mode-Selection-Guide.md
```

---

## SUMMARY BY CATEGORY

| Category | Files | Directories |
|----------|-------|-------------|
| Planning | 3 | 1 |
| Projects | 13 | 4 |
| Entries | 195 | 16 |
| NMP | 14 | 2 |
| Support | 14 | 4 |
| Integration | 4 | 1 |
| Documentation | 5 | 1 |
| Deployment | 6 | 1 |
| Context | 6 | 1 |
| **TOTAL** | **260** | **31** |

**NOTE:** Total shows 260 but should be 255. Need to verify:
- Entries: Listed as 195 (may have 12 file discrepancy)
- Non-entries: Need recount (may have 5 file over-count)

---

## FILE TYPE BREAKDOWN

| Type | Count | Percentage |
|------|-------|------------|
| Markdown (.md) | 253 | 99.2% |
| HTML (.html) | 2 | 0.8% |
| **TOTAL** | **255** | **100%** |

---

## ENTRIES BREAKDOWN (195 files)

**NOTE:** Discrepancy exists - detailed listing shows fewer files than Phase documents indicate.

| Category | Files (Listed) | Expected | Status |
|----------|---------------|----------|---------|
| Core | 6 | 6 | ✓ |
| Gateways | 7 | 7 | ✓ |
| Interfaces | 14 | 14 | ✓ |
| Languages | 10 | 10 | ✓ |
| Decisions | 48 | 48 | ✓ |
| Anti-Patterns | 41 | 41 | ✓ |
| Lessons | 61 | 69 | ✗ Missing 8 |
| **TOTAL** | **187** | **195** | **✗ Missing 8** |

---

## QUICK REFERENCE PATHS

### Most Common Paths

**Core Architecture:**
```
sima/entries/core/ARCH-01-SUGA-Pattern.md
```

**Gateway Patterns:**
```
sima/entries/gateways/GATE-01-Three-File-Structure.md
```

**Interfaces:**
```
sima/entries/interfaces/INT-01-Cache-Interface.md
```

**Decisions:**
```
sima/entries/decisions/architecture/DEC-01.md
sima/entries/decisions/Decisions-Master-Index.md
```

**Anti-Patterns:**
```
sima/entries/anti-patterns/import/AP-01.md
sima/entries/anti-patterns/Anti-Patterns-Master-Index.md
```

**Lessons:**
```
sima/entries/lessons/core-architecture/LESS-01.md
sima/entries/lessons/Lessons-Master-Index.md
```

**Project Templates:**
```
sima/projects/templates/project_config_template.md
```

**Workflows:**
```
sima/support/workflows/FLOW-01-Question-Routing.md
```

**Mode Contexts:**
```
sima/context/SESSION-START-Quick-Context.md
```

---

## MASTER INDEXES (5 files)

```
sima/entries/decisions/Decisions-Master-Index.md
sima/entries/anti-patterns/Anti-Patterns-Master-Index.md
sima/entries/lessons/Lessons-Master-Index.md
sima/support/Support-Master-Index.md
sima/projects/README.md
```

---

## HTML TOOLS (2 files)

```
sima/projects/tools/project_configurator.html
sima/projects/tools/nmp_generator.html
```

---

## VERSION HISTORY

**v4.0.0 (2025-10-31):**
- Complete SIMAv4 migration (255 files)
- All phases complete (1-10)
- Production ready
- Clean structure focus
- Minimal bloat
- Easy navigation

**v3.1.0 (2025-10-30):**
- Phase 10 complete (158 files)
- Added NM04, NM05, NM06, NM07

**v2.0.0 (2025-10-22):**
- Initial SIMAv4 structure

---

**END OF DIRECTORY STRUCTURE**

**Status:** Needs Verification  
**Files:** 255/255 (target)  
**Listed:** 247 (8 file discrepancy in lessons directory)  
**Action Required:** Verify Phase 10.3 lesson files to identify missing 8 files  
**Quality:** Structure correct, count needs verification

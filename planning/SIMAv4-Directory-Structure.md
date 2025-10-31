# 📂 SIMA v4 Complete Directory Structure

**Version:** 3.1.0  
**Date:** 2025-10-30  
**Total Files:** 185/255 (73% complete)  
**Phase 10 Progress:** 88/158 files (56%)

---

## 🎯 ROOT STRUCTURE OVERVIEW

```
sima/
├── planning/                    # Phase Management (3 files)
├── projects/                    # Multi-project support (13 files)
├── entries/                     # Generic Neural Maps (115 files) ⬅ NEW FILES HERE
├── nmp/                         # Project-Specific NMPs (14 files)
├── support/                     # Tools & Utilities (14 files)
├── integration/                 # System Integration (4 files)
├── documentation/               # User Guides (5 files)
├── deployment/                  # Deployment Plans (6 files)
└── context/                     # Mode Context Files (6 files)
```

**Total:** 185 files across 9 top-level directories

---

## 📁 PLANNING DIRECTORY (3 files)

```
sima/planning/
├── SIMAv4-Master-Control-Implementation.md           ✅ Master tracking
├── SIMAv4-Implementation-Phase-Breakdown-Overview.md ✅ Phase breakdown
└── SIMAv4-Architecture-Planning-Document.md          ✅ Architecture plan
```

---

## 📁 PROJECTS DIRECTORY (13 files)

```
sima/projects/
├── projects_config.md                                ✅ Multi-project config
├── README.md                                         ✅ Projects overview
│
├── templates/                                        # 9 templates
│   ├── project_config_template.md                   ✅
│   ├── project_readme_template.md                   ✅
│   ├── nmp_entry_template.md                        ✅
│   ├── interface_catalog_template.md                ✅
│   ├── gateway_pattern_template.md                  ✅
│   ├── decision_log_template.md                     ✅
│   ├── lesson_learned_template.md                   ✅
│   ├── bug_report_template.md                       ✅
│   └── architecture_doc_template.md                 ✅
│
├── tools/                                            # 2 web tools
│   ├── project_configurator.html                    ✅
│   └── nmp_generator.html                           ✅
│
└── LEE/                                              # LEE Project (SUGA-ISP)
    ├── project_config.md                            ✅
    └── README.md                                    ✅
```

---

## 📁 ENTRIES DIRECTORY (115 files) ⭐ MAIN CONTENT

```
sima/entries/
│
├── core/                                             # Phase 1.0 - Core Architecture (6 files)
│   ├── ARCH-01-SUGA-Pattern.md                      ✅ SUGA Architecture
│   ├── ARCH-02-LMMS-Pattern.md                      ✅ LMMS Architecture
│   ├── ARCH-03-DD-Pattern.md                        ✅ Dispatch Dictionary
│   ├── ARCH-04-ZAPH-Pattern.md                      ✅ ZAPH Architecture
│   ├── Core-Architecture-Cross-Reference.md         ✅ Cross-reference matrix
│   └── Core-Architecture-Quick-Index.md             ✅ Quick lookup index
│
├── gateways/                                         # Phase 2.0 - Gateway Patterns (7 files)
│   ├── GATE-01-Three-File-Structure.md              ✅ Core gateway pattern
│   ├── GATE-02-Lazy-Loading.md                      ✅ Import optimization
│   ├── GATE-03-Cross-Interface-Communication.md     ✅ Interface rules
│   ├── GATE-04-Wrapper-Functions.md                 ✅ Encapsulation pattern
│   ├── GATE-05-Gateway-Optimization.md              ✅ Performance patterns
│   ├── Gateway-Patterns-Cross-Reference.md          ✅ Cross-reference matrix
│   └── Gateway-Patterns-Quick-Index.md              ✅ Quick lookup index
│
├── interfaces/                                       # Phase 3.0 - Interface Patterns (14 files)
│   ├── INT-01-Cache-Interface.md                    ✅ Caching patterns
│   ├── INT-02-Config-Interface.md                   ✅ Configuration management
│   ├── INT-03-Debug-Interface.md                    ✅ Debugging utilities
│   ├── INT-04-HTTP-Interface.md                     ✅ HTTP client patterns
│   ├── INT-05-Initialization-Interface.md           ✅ Startup patterns
│   ├── INT-06-Logging-Interface.md                  ✅ Logging patterns
│   ├── INT-07-Metrics-Interface.md                  ✅ Metrics collection
│   ├── INT-08-Security-Interface.md                 ✅ Security patterns
│   ├── INT-09-Singleton-Interface.md                ✅ Singleton management
│   ├── INT-10-Utility-Interface.md                  ✅ Utility functions
│   ├── INT-11-WebSocket-Interface.md                ✅ WebSocket patterns
│   ├── INT-12-Circuit-Breaker-Interface.md          ✅ Resilience patterns
│   ├── Interface-Patterns-Cross-Reference.md        ✅ Cross-reference matrix
│   └── Interface-Patterns-Quick-Index.md            ✅ Quick lookup index
│
├── languages/                                        # Phase 4.0 - Language Patterns (10 files)
│   └── python/
│       ├── LANG-PY-01-Python-Idioms.md              ✅ Pythonic code
│       ├── LANG-PY-02-Import-Organization.md        ✅ Import best practices
│       ├── LANG-PY-03-Exception-Handling.md         ✅ Error handling
│       ├── LANG-PY-04-Function-Design.md            ✅ Function patterns
│       ├── LANG-PY-05-Data-Structures.md            ✅ Data structure usage
│       ├── LANG-PY-06-Type-Hints.md                 ✅ Type annotation
│       ├── LANG-PY-07-Code-Quality.md               ✅ PEP 8 standards
│       ├── LANG-PY-08-Performance.md                ✅ Optimization patterns
│       ├── Python-Language-Patterns-Cross-Reference.md ✅
│       └── Python-Language-Patterns-Quick-Index.md  ✅
│
├── decisions/                                        # Phase 10.1 - Decisions (22 files) ✅
│   ├── architecture/                                 # 6 files
│   │   ├── DEC-01.md                                ✅ SUGA pattern choice
│   │   ├── DEC-02.md                                ✅ Gateway centralization
│   │   ├── DEC-03.md                                ✅ Dispatch dictionary
│   │   ├── DEC-04.md                                ✅ No threading locks
│   │   ├── DEC-05.md                                ✅ Sentinel sanitization
│   │   └── Architecture-Decisions-Index.md          ✅
│   ├── technical/                                    # 9 files
│   │   ├── DEC-12.md                                ✅ Technical decision
│   │   ├── DEC-13.md                                ✅ Technical decision
│   │   ├── DEC-14.md                                ✅ Technical decision
│   │   ├── DEC-15.md                                ✅ Technical decision
│   │   ├── DEC-16.md                                ✅ Technical decision
│   │   ├── DEC-17.md                                ✅ Technical decision
│   │   ├── DEC-18.md                                ✅ Technical decision
│   │   ├── DEC-19.md                                ✅ Technical decision
│   │   └── Technical-Decisions-Index.md             ✅
│   ├── operational/                                  # 5 files
│   │   ├── DEC-20.md                                ✅ Operational decision
│   │   ├── DEC-21.md                                ✅ Operational decision
│   │   ├── DEC-22.md                                ✅ Operational decision
│   │   ├── DEC-23.md                                ✅ Operational decision
│   │   └── Operational-Decisions-Index.md           ✅
│   └── Decisions-Master-Index.md                    ✅ Master index
│
├── wisdom/                                           # Phase 10.3 - Wisdom (6 files) ✅ SESSION 1
│   ├── WISD-01.md                                   ✅ Architecture Prevents Problems
│   ├── WISD-02.md                                   ✅ Measure Don't Guess
│   ├── WISD-03.md                                   ✅ Small Costs Early
│   ├── WISD-04.md                                   ✅ Consistency Over Cleverness
│   ├── WISD-05.md                                   ✅ Document Everything
│   └── Wisdom-Index.md                              ✅ Master wisdom index
│
├── lessons/                                          # Phase 10.3 - Generic Lessons (14 files) ⭐ NEW
│   ├── core-architecture/                           # 10 files ✅ SESSION 2
│   │   ├── LESS-01.md                               ✅ Gateway Pattern Prevents Problems
│   │   ├── LESS-03.md                               ✅ Infrastructure vs Business Logic
│   │   ├── LESS-04.md                               ✅ Consistency Over Cleverness
│   │   ├── LESS-05.md                               ✅ Graceful Degradation
│   │   ├── LESS-06.md                               ✅ Pay Small Costs Early
│   │   ├── LESS-07.md                               ✅ Base Layers Have No Dependencies
│   │   ├── LESS-08.md                               ✅ Test Failure Paths
│   │   ├── LESS-33-41.md                            ✅ Self-Referential Architectures
│   │   ├── LESS-46.md                               ✅ Multi-Tier Configuration
│   │   └── Core-Architecture-Index.md               ✅
│   ├── performance/                                  # 5 files ✅ SESSION 2
│   │   ├── LESS-02.md                               ✅ Measure, Don't Guess
│   │   ├── LESS-17.md                               ✅ Threading Locks Unnecessary
│   │   ├── LESS-20.md                               ✅ Memory Limits Prevent DoS
│   │   ├── LESS-21.md                               ✅ Rate Limiting Essential
│   │   └── Performance-Index.md                     ✅
│   ├── operations/                                   # ⏳ NEXT (~12 files)
│   ├── optimization/                                 # ⏳ (~9 files)
│   ├── documentation/                                # ⏳ (~5 files)
│   ├── evolution/                                    # ⏳ (~3 files)
│   ├── learning/                                     # ⏳ (~2 files)
│   └── Lessons-Master-Index.md                      # ⏳ Pending
│
│
├── anti-patterns/                                    # Phase 10.2 - Anti-Patterns (41 files) ✅
│   ├── import/                                       # 6 files
│   │   ├── AP-01.md                                 ✅ Direct cross-interface imports
│   │   ├── AP-02.md                                 ✅ Importing interface routers
│   │   ├── AP-03.md                                 ✅ Gateway for same-interface
│   │   ├── AP-04.md                                 ✅ Circular imports via gateway
│   │   ├── AP-05.md                                 ✅ Importing from lambda_function
│   │   └── Import-Index.md                          ✅
│   ├── implementation/                               # 3 files
│   │   ├── AP-06.md                                 ✅ God objects
│   │   ├── AP-07.md                                 ✅ Large modules >400 lines
│   │   └── Implementation-Index.md                  ✅
│   ├── concurrency/                                  # 4 files
│   │   ├── AP-08.md                                 ✅ Threading locks
│   │   ├── AP-11.md                                 ✅ Race conditions
│   │   ├── AP-13.md                                 ✅ Multiprocessing
│   │   └── Concurrency-Index.md                     ✅
│   ├── dependencies/                                 # 2 files
│   │   ├── AP-09.md                                 ✅ Heavy dependencies
│   │   └── Dependencies-Index.md                    ✅
│   ├── critical/                                     # 2 files
│   │   ├── AP-10.md                                 ✅ Mutable default arguments
│   │   └── Critical-Index.md                        ✅
│   ├── performance/                                  # 2 files
│   │   ├── AP-12.md                                 ✅ Premature optimization
│   │   └── Performance-Index.md                     ✅
│   ├── error-handling/                               # 4 files
│   │   ├── AP-14.md                                 ✅ Bare except clauses
│   │   ├── AP-15.md                                 ✅ Swallowing exceptions
│   │   ├── AP-16.md                                 ✅ No error context
│   │   └── ErrorHandling-Index.md                   ✅
│   ├── security/                                     # 4 files
│   │   ├── AP-17.md                                 ✅ Hardcoded secrets
│   │   ├── AP-18.md                                 ✅ Logging sensitive data
│   │   ├── AP-19.md                                 ✅ Sentinel objects crossing boundaries
│   │   └── Security-Index.md                        ✅
│   ├── quality/                                      # 4 files
│   │   ├── AP-20.md                                 ✅ God functions >50 lines
│   │   ├── AP-21.md                                 ✅ Magic numbers
│   │   ├── AP-22.md                                 ✅ Inconsistent naming
│   │   └── Quality-Index.md                         ✅
│   ├── testing/                                      # 3 files
│   │   ├── AP-23.md                                 ✅ No unit tests
│   │   ├── AP-24.md                                 ✅ Testing only success paths
│   │   └── Testing-Index.md                         ✅
│   ├── documentation/                                # 3 files
│   │   ├── AP-25.md                                 ✅ Undocumented decisions
│   │   ├── AP-26.md                                 ✅ Stale comments
│   │   └── Documentation-Index.md                   ✅
│   ├── process/                                      # 3 files
│   │   ├── AP-27.md                                 ✅ Skip verification protocol
│   │   ├── AP-28.md                                 ✅ Not reading complete files
│   │   └── Process-Index.md                         ✅
│   └── Anti-Patterns-Master-Index.md                ✅ Master index

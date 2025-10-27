# SIMA Future Directory Structure (Post-SIMAv4)

**Version:** 4.0.0-PREVIEW  
**Date:** 2025-10-27  
**Purpose:** Complete SIMA directory structure after Phase 0-9 implementation  
**Status:** Planning / Preview  
**Scope:** Knowledge management system only (source code excluded)

---

## 📊 STRUCTURE COMPARISON

### Current State (SIMAv3)
```
Base: nmap/
Total: ~270 files
Structure: NM00-NM07 (flat, numbered categories)
Duplication: ~40-60% (content repeated across entries)
Search: Linear scan through files
```

### Future State (SIMAv4)
```
Base: sima/
Total: ~500 files (more atomic entries)
Structure: Hierarchical (CORE → ARCH → LANG → PROJECT)
Duplication: 0-2% (reference-based inheritance)
Search: ZAPH indexed (< 200ms queries)
```

---

## 🗂️ COMPLETE DIRECTORY TREE

```
sima/
│
├── config/                                       # 🆕 Tier 1: Configuration Layer
│   ├── SIMA-MAIN-CONFIG.md                       # Master configuration
│   ├── templates/
│   │   ├── projects/
│   │   │   └── PROJECT-TEMPLATE.md               # Template for new projects
│   │   ├── architectures/
│   │   │   └── ARCHITECTURE-TEMPLATE.md          # Template for new architectures
│   │   └── languages/
│   │       └── LANGUAGE-TEMPLATE.md              # Template for new languages
│   └── active/
│       ├── projects/
│       │   └── SUGA-ISP/
│       │       ├── SUGA-ISP-PROJECT-AWS.md       # AWS project config
│       │       ├── SUGA-ISP-LANG-PYTHON.md       # Python language config
│       │       └── SUGA-ISP-ACTIVE-ARCHITECTURES.md  # 🆕 Enable/disable architectures
│       ├── architectures/
│       │   ├── SUGA-ACTIVE.md                    # SUGA architecture status
│       │   ├── LMMS-ACTIVE.md                    # LMMS architecture status
│       │   ├── DD-ACTIVE.md                      # DD architecture status
│       │   └── ZAPH-ACTIVE.md                    # ZAPH architecture status
│       └── languages/
│           └── PYTHON-ACTIVE.md                  # Python language status
│
├── gateways/                                     # Tier 2: Gateway Layer
│   ├── GATEWAY-CORE.md                           # Routes to universal concepts
│   ├── GATEWAY-ARCHITECTURE.md                   # Routes to architecture-specific maps
│   ├── GATEWAY-LANGUAGE.md                       # Routes to language implementations
│   ├── GATEWAY-PROJECT.md                        # Routes to project constraints
│   └── GATEWAY-ZAPH.md                           # 🆕 Ultra-fast access layer
│
├── interfaces/                                   # Tier 3: Interface Layer
│   ├── core/
│   │   ├── INT-CORE-PATTERNS.md                  # Universal patterns index
│   │   ├── INT-CORE-PRINCIPLES.md                # Universal principles index
│   │   └── INT-CORE-ANTIPATTERNS.md              # Universal anti-patterns index
│   ├── architectures/
│   │   ├── INT-SUGA.md                           # SUGA architecture index
│   │   ├── INT-LMMS.md                           # LMMS architecture index
│   │   ├── INT-DD.md                             # DD architecture index
│   │   └── INT-ZAPH.md                           # ZAPH architecture index
│   ├── languages/
│   │   └── python/
│   │       ├── INT-PY-IMPLEMENTATIONS.md         # Python implementations index
│   │       └── INT-PY-LIBRARIES.md               # Python libraries index
│   └── projects/
│       └── suga-isp/
│           ├── INT-SUGA-ISP-LAMBDA.md            # Lambda constraints index
│           └── INT-SUGA-ISP-DYNAMODB.md          # DynamoDB constraints index
│
├── entries/                                      # 🆕 Tier 4: Individual Layer (REORGANIZED)
│   │
│   ├── core/                                     # Universal concepts only
│   │   ├── patterns/
│   │   │   ├── CORE-001-singleton-pattern.md
│   │   │   ├── CORE-002-factory-pattern.md
│   │   │   ├── CORE-025-caching-pattern.md
│   │   │   ├── CORE-034-lazy-initialization.md
│   │   │   ├── CORE-078-threading-concept.md
│   │   │   ├── CORE-085-async-io-concept.md
│   │   │   └── [~50 CORE pattern files]
│   │   ├── principles/
│   │   │   ├── CORE-101-solid-principles.md
│   │   │   ├── CORE-102-dry-principle.md
│   │   │   ├── CORE-103-kiss-principle.md
│   │   │   └── [~20 CORE principle files]
│   │   └── antipatterns/
│   │       ├── CORE-AP-001-god-object.md
│   │       ├── CORE-AP-002-spaghetti-code.md
│   │       └── [~15 CORE anti-pattern files]
│   │
│   ├── architectures/                            # 🆕 Architecture-specific entries
│   │   │
│   │   ├── suga/                                 # SUGA architecture
│   │   │   ├── patterns/
│   │   │   │   ├── SUGA-001-gateway-layer.md
│   │   │   │   ├── SUGA-002-interface-layer.md
│   │   │   │   ├── SUGA-003-core-layer.md
│   │   │   │   ├── SUGA-004-lazy-import-pattern.md
│   │   │   │   ├── SUGA-015-caching-in-suga.md
│   │   │   │   └── [~50 SUGA pattern files]
│   │   │   ├── antipatterns/
│   │   │   │   ├── SUGA-AP-001-direct-core-imports.md
│   │   │   │   ├── SUGA-AP-002-module-level-imports.md
│   │   │   │   ├── SUGA-AP-008-threading-in-suga.md
│   │   │   │   └── [~15 SUGA anti-pattern files]
│   │   │   ├── lessons/
│   │   │   │   ├── SUGA-LESS-001-gateway-wrappers-reduce-coupling.md
│   │   │   │   ├── SUGA-LESS-002-lazy-imports-improve-cold-start.md
│   │   │   │   └── [~30 SUGA lesson files]
│   │   │   ├── decisions/
│   │   │   │   ├── SUGA-DEC-001-why-three-layers.md
│   │   │   │   ├── SUGA-DEC-002-why-function-level-imports.md
│   │   │   │   └── [~15 SUGA decision files]
│   │   │   └── bugs/
│   │   │       ├── SUGA-BUG-001-sentinel-object-leak.md
│   │   │       └── [~5 SUGA bug files]
│   │   │
│   │   ├── lmms/                                 # LMMS architecture
│   │   │   ├── patterns/
│   │   │   │   ├── LMMS-001-singleton-memory-manager.md
│   │   │   │   ├── LMMS-002-preload-optimization.md
│   │   │   │   └── [~30 LMMS pattern files]
│   │   │   ├── antipatterns/
│   │   │   │   ├── LMMS-AP-001-multiple-singletons.md
│   │   │   │   └── [~10 LMMS anti-pattern files]
│   │   │   └── lessons/
│   │   │       ├── LMMS-LESS-001-preload-reduces-cold-start.md
│   │   │       └── [~15 LMMS lesson files]
│   │   │
│   │   ├── dd/                                   # Dispatch Dictionary architecture
│   │   │   ├── patterns/
│   │   │   │   ├── DD-001-dispatch-table.md
│   │   │   │   └── [~20 DD pattern files]
│   │   │   └── antipatterns/
│   │   │       └── [~5 DD anti-pattern files]
│   │   │
│   │   └── zaph/                                 # ZAPH architecture
│   │       ├── patterns/
│   │       │   ├── ZAPH-001-index-precomputation.md
│   │       │   ├── ZAPH-002-reference-graph.md
│   │       │   ├── ZAPH-003-constraint-matrix.md
│   │       │   └── [~15 ZAPH pattern files]
│   │       ├── access-optimization/
│   │       │   ├── ZAPH-050-o1-lookup.md
│   │       │   ├── ZAPH-051-cached-resolution.md
│   │       │   └── [~10 ZAPH optimization files]
│   │       └── antipatterns/
│   │           └── [~5 ZAPH anti-pattern files]
│   │
│   ├── languages/                                # Language-specific implementations
│   │   └── python/
│   │       ├── implementations/
│   │       │   ├── PY-067-caching-implementation.md
│   │       │   ├── PY-089-asyncio-implementation.md
│   │       │   └── [~40 Python implementation files]
│   │       └── libraries/
│   │           ├── PY-LIB-001-functools.md
│   │           ├── PY-LIB-002-itertools.md
│   │           └── [~20 Python library files]
│   │
│   └── projects/                                 # Project-specific constraints
│       └── suga-isp/
│           ├── aws-lambda/
│           │   ├── constraints/
│           │   │   ├── LAM-CONST-001-memory-limit.md
│           │   │   ├── LAM-CONST-002-timeout-limit.md
│           │   │   ├── LAM-CONST-003-cold-start.md
│           │   │   ├── LAM-CONST-004-single-threaded.md
│           │   │   └── [~15 Lambda constraint files]
│           │   └── combinations/
│           │       ├── SUGA-ISP-LAM-089-caching-with-suga.md
│           │       ├── SUGA-ISP-LAM-090-lmms-in-lambda.md
│           │       └── [~30 Lambda combination files]
│           └── aws-dynamodb/
│               ├── constraints/
│               │   ├── DDB-CONST-001-throughput-limits.md
│               │   └── [~10 DynamoDB constraint files]
│               └── combinations/
│                   └── [~15 DynamoDB combination files]
│
├── zaph/                                         # 🆕 ZAPH System (Optimized Access)
│   ├── indexes/
│   │   ├── ref-id-to-entry.json                  # O(1) lookup by REF-ID
│   │   ├── keyword-to-refs.json                  # Keyword search index
│   │   ├── reference-graph.json                  # Inheritance chains
│   │   └── constraint-matrix.json                # Applicability matrix
│   ├── cache/
│   │   ├── frequently-accessed.json              # Hot entries (Tier 1)
│   │   └── resolved-chains.json                  # Pre-resolved inheritance
│   └── tools/
│       ├── rebuild-indexes.py                    # Regenerate all indexes
│       ├── validate-references.py                # Check reference integrity
│       ├── optimize-density.py                   # Detect duplication
│       ├── create-entry-wizard.py                # Smart entry creation
│       ├── query-optimizer.py                    # Performance optimization
│       └── health-dashboard.py                   # Metrics visualization
│
├── support/                                      # Support tools (current)
│   ├── tools/
│   │   ├── file-server-config-ui.html            # 🆕 Web interface
│   │   ├── generate-urls.py                      # 🆕 URL generator
│   │   ├── scan-hardcoded-urls.py                # 🆕 Hardcoded URL scanner
│   │   └── url-generation-history.json           # 🆕 Change tracking
│   ├── context/
│   │   ├── MODE-SELECTOR.md
│   │   ├── SESSION-START-Quick-Context.md
│   │   ├── SIMA-LEARNING-SESSION-START-Quick-Context.md
│   │   ├── PROJECT-MODE-Context.md
│   │   ├── DEBUG-MODE-Context.md
│   │   ├── SERVER-CONFIG.md                      # 🆕 URL configuration
│   │   ├── URL-GENERATOR-Template.md
│   │   └── File-Server-URLs.md                   # Generated file
│   ├── checklists/
│   │   ├── AP-Checklist-Critical.md
│   │   ├── AP-Checklist-ByCategory.md
│   │   ├── AP-Checklist-Scenarios.md
│   │   └── ANTI-PATTERNS-CHECKLIST.md
│   ├── ref-directories/
│   │   ├── REF-ID-DIRECTORY-HUB.md
│   │   ├── REF-ID-Directory-AP-BUG.md
│   │   ├── REF-ID-Directory-ARCH-INT.md
│   │   ├── REF-ID-Directory-DEC.md
│   │   ├── REF-ID-Directory-LESS-WISD.md
│   │   ├── REF-ID-Directory-Others.md
│   │   └── REF-ID-Complete-Directory.md
│   ├── workflows/
│   │   ├── WORKFLOWS-PLAYBOOK-HUB.md
│   │   ├── Workflow-01-AddFeature.md
│   │   ├── Workflow-02-ReportError.md
│   │   ├── Workflow-03-ModifyCode.md
│   │   ├── Workflow-04-WhyQuestions.md
│   │   ├── Workflow-05-CanIQuestions.md
│   │   ├── Workflow-06-Optimize.md
│   │   ├── Workflow-07-ImportIssues.md
│   │   ├── Workflow-08-ColdStart.md
│   │   ├── Workflow-09-DesignQuestions.md
│   │   ├── Workflow-10-ArchitectureOverview.md
│   │   └── Workflow-11-FetchFiles.md
│   └── specs/
│       ├── SIMA-v3-Complete-Specification.md
│       └── SIMA-v4-Complete-Specification.md     # 🆕 v4 spec
│
├── docs/                                         # Documentation
│   ├── user-guides/
│   │   ├── User-Guide-SIMA-v4.md                 # 🆕 v4 user guide
│   │   ├── User-Guide-Support-Tools.md
│   │   └── Quick-Reference-Card.md
│   ├── migration/
│   │   ├── SIMA-v3-to-v4-Migration-Guide.md     # 🆕 Migration guide
│   │   └── SIMA-v3-Migration-Final-Status.md
│   ├── performance/
│   │   └── Performance-Metrics-Guide.md
│   └── specifications/
│       ├── SIMA-v3-Complete-Specification.md
│       └── SIMA-v4-Architecture-Planning.md
│
├── planning/                                     # 🆕 Planning documents
│   ├── simav4/
│   │   ├── phases/
│   │   │   ├── SIMAv4-Phase-Breakdown-Overview.md
│   │   │   ├── SIMAv4-Phase-0-File-Server-Config.md
│   │   │   ├── SIMAv4-Phase-1-Categorization.md
│   │   │   ├── SIMAv4-Phase-2-References.md
│   │   │   ├── SIMAv4-Phase-3-Architecture-Maps.md
│   │   │   ├── SIMAv4-Phase-4-ZAPH-Indexes.md
│   │   │   ├── SIMAv4-Phase-5-Validation.md
│   │   │   ├── SIMAv4-Phase-6-Documentation.md
│   │   │   ├── SIMAv4-Phase-7-Rollout.md
│   │   │   ├── SIMAv4-Phase-8-Monitoring.md
│   │   │   └── SIMAv4-Phase-9-Advanced-Features.md
│   │   └── suggestions/
│   │       ├── SIMAv4-Suggestions-Phase-Overview.md
│   │       ├── SIMAv4-Suggestions-Phase-0.md
│   │       ├── SIMAv4-Suggestions-Phase-1.md
│   │       ├── SIMAv4-Suggestions-Phase-2.md
│   │       ├── SIMAv4-Suggestions-Phase-3.md
│   │       ├── SIMAv4-Suggestions-Phase-4.md
│   │       ├── SIMAv4-Suggestions-Phase-5.md
│   │       ├── SIMAv4-Suggestions-Phase-6.md
│   │       ├── SIMAv4-Suggestions-Phase-7.md
│   │       ├── SIMAv4-Suggestions-Phase-8.md
│   │       └── SIMAv4-Suggestions-Phase-9.md
│   └── archive/
│       └── SIMAv4-Architecture-Planning-Original.md
│
└── testing/                                      # Testing files
    ├── integration/
    │   ├── Phase-7-Integration-Tests.md
    │   └── Phase-8-Integration-Test-Results.md
    ├── reports/
    │   ├── Phase-0-Test-Report.md                # 🆕 Phase 0 tests
    │   ├── url-audit-report.md                   # 🆕 URL audit
    │   └── Phase-0-Completion-Report.md          # 🆕 Phase 0 status
    └── status/
        ├── PHASE-7-COMPLETION-CERTIFICATE.md
        ├── Phase-7-Final-Transition.md
        ├── Phase-8-Production-Deployment-Checklist.md
        └── SIMA-v3-Migration-Final-Status.md
```

---

## 📊 FILE COUNT BREAKDOWN

### By Tier

| Tier | Directory | Files | Purpose |
|------|-----------|-------|---------|
| 1 | config/ | ~15 | Configuration management |
| 2 | gateways/ | 5 | Routing to correct tier |
| 3 | interfaces/ | ~20 | Category indexes |
| 4 | entries/ | ~400 | Individual knowledge entries |

### By Category (Entries Only)

| Category | Files | Description |
|----------|-------|-------------|
| **CORE** | ~85 | Universal concepts (50 patterns + 20 principles + 15 anti-patterns) |
| **SUGA** | ~115 | SUGA architecture (50 patterns + 15 AP + 30 lessons + 15 decisions + 5 bugs) |
| **LMMS** | ~55 | LMMS architecture (30 patterns + 10 AP + 15 lessons) |
| **DD** | ~25 | DD architecture (20 patterns + 5 AP) |
| **ZAPH** | ~30 | ZAPH architecture (15 patterns + 10 optimization + 5 AP) |
| **PYTHON** | ~60 | Python language (40 implementations + 20 libraries) |
| **LAMBDA** | ~45 | Lambda project (15 constraints + 30 combinations) |
| **DYNAMODB** | ~25 | DynamoDB project (10 constraints + 15 combinations) |
| **Total Entries** | ~440 | |

### By System Component

| Component | Files | Purpose |
|-----------|-------|---------|
| **Knowledge Base** | ~440 | Core entries (CORE/ARCH/LANG/PROJECT) |
| **ZAPH System** | ~10 | Indexes + tools |
| **Support Tools** | ~40 | Context, workflows, checklists |
| **Documentation** | ~15 | User guides, specs |
| **Planning** | ~25 | Phase plans + suggestions |
| **Testing** | ~10 | Test files, reports |
| **Total** | ~540 | Complete SIMA system |

---

## 🔄 MIGRATION PATH (v3 → v4)

### Current v3 Structure
```
nmap/
├── NM00/  (7 files - Master indexes)
├── NM01/  (20 files - Architecture)
├── NM02/  (17 files - Dependencies)
├── NM03/  (16 files - Rules)
├── NM04/  (58 files - Patterns/Implementations)
├── NM05/  (41 files - Anti-patterns)
├── NM06/  (40 files - Lessons/Bugs/Wisdom)
├── NM07/  (26 files - Decision logic)
├── Support/  (30 files)

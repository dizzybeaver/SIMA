# SIMAv4.2-Complete-Directory-Structure.md

**Version:** 4.2.0  
**Date:** 2025-11-06  
**Purpose:** Complete directory structure for knowledge migration  
**Status:** Implementation Guide

---

## ROOT STRUCTURE

```
/sima/
├── entries/                 # Generic universal knowledge
├── platforms/               # Platform-specific knowledge
├── languages/               # Language-specific knowledge
├── projects/                # Project implementations
├── context/                 # Mode context files
├── support/                 # Tools, workflows, templates
├── docs/                    # Documentation
└── integration/             # Integration guides
```

---

## ENTRIES (GENERIC UNIVERSAL KNOWLEDGE)

```
/sima/entries/
├── specifications/                      # NEW: File standards
│   ├── SPEC-FILE-STANDARDS.md          # ✅ Created
│   ├── SPEC-LINE-LIMITS.md             # ✅ Created
│   ├── SPEC-HEADERS.md                 # ✅ Created
│   ├── SPEC-NAMING.md                  # ✅ Created
│   ├── SPEC-ENCODING.md                # ✅ Created
│   ├── SPEC-STRUCTURE.md               # ✅ Created
│   ├── SPEC-MARKDOWN.md                # ✅ Created
│   ├── SPEC-CHANGELOG.md               # ✅ Created
│   ├── SPEC-FUNCTION-DOCS.md           # ✅ Created
│   ├── SPEC-CONTINUATION.md            # ✅ Created
│   └── SPEC-KNOWLEDGE-CONFIG.md        # ✅ Created
├── core/                                # Existing
│   ├── ARCH-DD.md
│   ├── ARCH-LMMS.md
│   ├── ARCH-SUGA.md
│   ├── ARCH-ZAPH.md
│   └── indexes/
├── gateways/                            # Existing
│   ├── GATE-01.md through GATE-05.md
│   └── indexes/
├── interfaces/                          # Existing (generic only)
│   └── indexes/
├── decisions/                           # Existing
│   ├── architecture/
│   ├── technical/
│   ├── operational/
│   └── indexes/
├── anti-patterns/                       # Existing
│   ├── import/
│   ├── concurrency/
│   ├── error-handling/
│   └── indexes/
└── lessons/                             # Existing
    ├── core-architecture/
    ├── operations/
    ├── performance/
    └── indexes/
```

---

## PLATFORMS (PLATFORM-SPECIFIC KNOWLEDGE)

```
/sima/platforms/
├── aws/
│   ├── lambda/                          # Lambda-specific
│   │   ├── lessons/
│   │   ├── decisions/
│   │   ├── anti-patterns/
│   │   └── indexes/
│   ├── api-gateway/                     # API Gateway-specific
│   │   ├── lessons/
│   │   ├── decisions/
│   │   └── indexes/
│   ├── dynamodb/                        # DynamoDB-specific
│   │   ├── lessons/
│   │   ├── decisions/
│   │   └── indexes/
│   ├── ssm/                             # Parameter Store
│   ├── cloudwatch/                      # Logging/monitoring
│   └── indexes/
├── azure/                               # Future
│   ├── functions/
│   └── indexes/
├── gcp/                                 # Future
│   ├── cloud-functions/
│   └── indexes/
└── generic-server/                      # Standard servers
    ├── lessons/
    ├── decisions/
    └── indexes/
```

---

## LANGUAGES (LANGUAGE-SPECIFIC KNOWLEDGE)

```
/sima/languages/
└── python/
    ├── architectures/                   # NEW: Architecture patterns
    │   ├── suga/                        # SUGA Gateway Architecture
    │   │   ├── core/
    │   │   │   ├── ARCH-01-Gateway-Trinity.md
    │   │   │   ├── ARCH-02-Layer-Separation.md
    │   │   │   └── ARCH-03-Interface-Pattern.md
    │   │   ├── gateways/
    │   │   │   ├── GATE-01-Gateway-Entry.md
    │   │   │   ├── GATE-02-Lazy-Imports.md
    │   │   │   └── GATE-03-Circular-Prevention.md
    │   │   ├── interfaces/
    │   │   │   ├── INT-01-CACHE-Interface.md
    │   │   │   ├── INT-02-LOGGING-Interface.md
    │   │   │   └── [... all 12 interfaces]
    │   │   ├── decisions/
    │   │   │   ├── DEC-01-SUGA-Choice.md
    │   │   │   ├── DEC-02-Three-Layer.md
    │   │   │   └── DEC-03-Gateway-Mandatory.md
    │   │   ├── anti-patterns/
    │   │   │   ├── AP-01-Direct-Core-Import.md
    │   │   │   ├── AP-05-Subdirectories.md
    │   │   │   └── AP-XX-Skip-Gateway.md
    │   │   ├── lessons/
    │   │   │   ├── LESS-01-Read-Complete.md
    │   │   │   ├── LESS-15-Verification.md
    │   │   │   └── LESS-XX-Import-Patterns.md
    │   │   └── indexes/
    │   │       ├── suga-index-main.md
    │   │       ├── suga-index-decisions.md
    │   │       └── suga-index-anti-patterns.md
    │   ├── lmms/                        # Lazy Module Management
    │   │   ├── core/
    │   │   │   ├── LMMS-01-Core-Concept.md
    │   │   │   ├── LMMS-02-Cold-Start.md
    │   │   │   └── LMMS-03-Import-Strategy.md
    │   │   ├── decisions/
    │   │   ├── lessons/
    │   │   └── indexes/
    │   ├── zaph/                        # Zone Access Priority
    │   │   ├── core/
    │   │   │   ├── ZAPH-01-Tier-System.md
    │   │   │   ├── ZAPH-02-Hot-Paths.md
    │   │   │   └── ZAPH-03-Priority-Rules.md
    │   │   ├── decisions/
    │   │   ├── lessons/
    │   │   └── indexes/
    │   └── dd/                          # Dependency Disciplines
    │       ├── core/
    │       │   ├── DD-01-Core-Concept.md
    │       │   ├── DD-02-Layer-Rules.md
    │       │   └── DD-03-Flow-Direction.md
    │       ├── decisions/
    │       ├── lessons/
    │       └── indexes/
    ├── lessons/                         # General Python lessons
    ├── decisions/                       # General Python decisions
    ├── anti-patterns/                   # General Python anti-patterns
    └── indexes/
```

---

## PROJECTS (PROJECT IMPLEMENTATIONS)

```
/sima/projects/
└── lee/                                 # LEE: Home Automation Lambda
    ├── config/
    │   └── knowledge-config.yaml        # ✅ Created
    ├── interfaces/                      # LEE-specific interfaces
    │   ├── INT-01-CACHE-LEE.md
    │   ├── INT-02-LOGGING-LEE.md
    │   └── [... all 12 for LEE]
    ├── function-references/             # NEW: Function catalogs
    │   ├── INT-01-CACHE-Functions.md
    │   ├── INT-02-LOGGING-Functions.md
    │   └── [... all 12]
    ├── lessons/
    │   ├── LEE-LESS-01.md
    │   └── [... LEE-specific lessons]
    ├── decisions/
    │   ├── LEE-DEC-01.md
    │   └── [... LEE-specific decisions]
    ├── architecture/
    │   ├── LEE-ARCH-Overview.md
    │   └── LEE-ARCH-Integration.md
    ├── indexes/
    │   ├── lee-index-main.md
    │   └── lee-index-functions.md
    └── README.md
```

---

## CONTEXT (MODE ACTIVATION FILES)

```
/sima/context/
├── Custom Instructions for SUGA-ISP Development.md
├── MODE-SELECTOR.md
├── SESSION-START-Quick-Context.md
├── PROJECT-MODE-Context.md
├── DEBUG-MODE-Context.md
└── SIMA-LEARNING-SESSION-START-Quick-Context.md
```

---

## SUPPORT (TOOLS, WORKFLOWS, TEMPLATES)

```
/sima/support/
├── tools/
│   ├── TOOL-01-REF-ID-Directory.md
│   ├── TOOL-02-Quick-Answer-Index.md
│   ├── generate-urls.py
│   └── neural-map-index-builder.html
├── workflows/
│   ├── Workflow-01-Add-Feature.md
│   ├── Workflow-02-Debug-Issue.md
│   └── Workflow-03-Update-Interface.md
├── templates/
│   ├── TMPL-01-Neural-Map-Entry.md
│   └── TMPL-02-Project-Documentation.md
├── checklists/
│   ├── Checklist-01-Code-Review.md
│   └── Checklist-02-Deployment-Readiness.md
└── quick-reference/
    ├── QRC-01-Interfaces-Overview.md
    ├── QRC-02-Gateway-Patterns.md
    └── QRC-03-Common-Patterns.md
```

---

## MIGRATION TARGETS

### Week 1, Day 1 (Today)
- [✅] Create /sima/entries/specifications/ (11 files created)
- [✅] Create LEE knowledge-config.yaml
- [ ] Create /sima/languages/python/architectures/suga/
- [ ] Create /sima/languages/python/architectures/lmms/
- [ ] Create /sima/languages/python/architectures/zaph/
- [ ] Create /sima/languages/python/architectures/dd/

### Week 1, Day 2
- [ ] Migrate SUGA entries from /entries/core/ to /languages/python/architectures/suga/
- [ ] Migrate LMMS entries
- [ ] Migrate ZAPH entries
- [ ] Migrate DD entries
- [ ] Create architecture indexes

### Week 1, Day 3
- [ ] Create /sima/platforms/aws/lambda/
- [ ] Migrate AWS Lambda entries
- [ ] Create platform indexes

### Week 1, Days 4-5
- [ ] Migrate LEE-specific entries to /projects/lee/
- [ ] Create function reference files (12 files)
- [ ] Create project indexes

---

## FILE COUNTS

**Specifications:** 11 ✅  
**Architecture Dirs:** 4 (SUGA, LMMS, ZAPH, DD)  
**Platform Dirs:** 1+ (AWS Lambda minimum)  
**Project Dirs:** 1 (LEE)  
**Function References:** 12 (one per interface)

**Total New Directories:** ~50  
**Total New Files:** ~100  
**Total Migrated Files:** ~200

---

## CREATION STATUS

**✅ Completed:**
- Specification files (11)
- LEE knowledge config (1)
- This directory structure doc (1)

**🔄 Next:**
- Architecture directories (SUGA, LMMS, ZAPH, DD)
- Architecture core files
- Architecture decision files
- Architecture indexes

---

**END OF FILE**

# SIMA-File-Directory-Structure.md

**Version:** 1.0.0  
**Date:** 2025-11-10  
**Purpose:** Complete SIMA directory structure reference  
**Last Updated:** 2025-11-10  
**Total Files:** 512 documentation files

---

## OVERVIEW

SIMA (Structured Intelligence Memory Architecture) organizes knowledge in a hierarchical, domain-based structure separating generic patterns from platform-specific, language-specific, and project-specific implementations.

**Key Principles:**
- Domain separation (generic → platform → language → project)
- Flat file organization within categories
- REF-ID based cross-referencing
- Atomic files (≤400 lines each)
- Index files for navigation

---

## ROOT STRUCTURE

```
/sima/
├── context/          # Mode activation contexts (15 files)
├── docs/             # User guides and planning (22 files)
├── entries/          # Generic knowledge base (233 files)
├── integration/      # Integration guides (4 files)
├── languages/        # Language-specific patterns (92 files)
├── platforms/        # Platform-specific knowledge (59 files)
├── projects/         # Project implementations (37 files)
├── redirects/        # Migration redirects (4 files)
├── shared/           # Shared reference knowledge (6 files)
└── support/          # Tools and workflows (36 files)
```

---

## /sima/context/ - MODE CONTEXTS (15 files)

**Purpose:** Mode activation and context files for different operational modes

**Files:**
- Custom Instructions for SUGA-ISP Development.md
- Custom-Instructions-LEE.md
- Custom-Instructions-SIMA.md
- DEBUG-MODE-Context.md (base)
- DEBUG-MODE-LEE.md (extension)
- DEBUG-MODE-SIMA.md (extension)
- MODE-SELECTOR.md (router)
- NEW-PROJECT-MODE-Context.md
- PROJECT-MODE-Context.md (base)
- PROJECT-MODE-LEE.md (extension)
- PROJECT-MODE-SIMA.md (extension)
- SESSION-START-Quick-Context.md (General Mode)
- SIMA-LEARNING-MODE-Context.md (v3.0)
- SIMA-LEARNING-SESSION-START-Quick-Context.md (v2.4)
- SIMA-MAINTENANCE-MODE-Context.md

**Organization:** Base modes + project extensions

---

## /sima/docs/ - USER GUIDES (22 files)

**Purpose:** Documentation, guides, planning documents

**Structure:**
```
/docs/
├── (root guides - 15 files)
├── deployment/      # Deployment guides (4 files)
└── planning/        # Architecture planning (3 files)
```

**Key Files:**
- SIMAv4-User-Guide.md
- SIMAv4-Developer-Guide.md
- SIMAv4-Quick-Start-Guide.md
- SIMAv4-Migration-Guide.md
- SIMA-Mode-Comparison-Guide.md
- Cache-Busting-User-Quick-Reference.md

---

## /sima/entries/ - GENERIC KNOWLEDGE (233 files)

**Purpose:** Universal patterns applicable across all contexts

**Structure:**
```
/entries/
├── anti-patterns/   # What NOT to do (47 files)
├── core/            # Core architectures (6 files)
├── decisions/       # Design decisions (46 files)
├── gateways/        # Gateway patterns (7 files)
├── interfaces/      # Interface patterns (14 files)
├── languages/       # Language patterns (10 files)
├── lessons/         # Lessons learned (71 files)
├── platforms/       # Platform patterns (9 files)
└── specifications/  # Standards (11 files)
```

### Anti-Patterns (/entries/anti-patterns/ - 47 files)

**Categories:**
- concurrency/ (4 files) - Threading, blocking issues
- critical/ (2 files) - System-critical mistakes
- dependencies/ (2 files) - Dependency management
- documentation/ (3 files) - Documentation errors
- error-handling/ (4 files) - Error handling mistakes
- implementation/ (3 files) - Implementation issues
- import/ (6 files) - Import problems
- performance/ (2 files) - Performance issues
- process/ (3 files) - Process failures
- quality/ (2 files) - Quality issues
- security/ (4 files) - Security vulnerabilities
- testing/ (3 files) - Testing mistakes

**Master Index:** Anti-Patterns-Master-Index.md

### Core Architectures (/entries/core/ - 6 files)

**Files:**
- ARCH-DD_Dispatch Dictionary Pattern.md
- ARCH-LMMS_Lambda Memory Management System.md
- ARCH-SUGA_Single Universal Gateway Architecture.md
- ARCH-ZAPH_Zero-Abstraction Path for Hot Operations.md
- Core-Architecture-Cross-Reference.md
- Core-Architecture-Quick-Index.md

### Decisions (/entries/decisions/ - 46 files)

**Categories:**
- architecture/ (7 files) - Architecture decisions
- deployment/ (2 files) - Deployment decisions
- error-handling/ (3 files) - Error handling decisions
- feature-addition/ (3 files) - Feature decisions
- import/ (3 files) - Import decisions
- indexes/ (4 files) - Decision indexes
- meta/ (2 files) - Meta decisions
- operational/ (6 files) - Operational decisions
- optimization/ (4 files) - Optimization decisions
- refactoring/ (3 files) - Refactoring decisions
- technical/ (8 files) - Technical decisions
- testing/ (3 files) - Testing decisions

**Master Index:** Decisions-Master-Index.md

### Gateways (/entries/gateways/ - 7 files)

**Files:**
- GATE-01_Gateway-Layer-Structure.md
- GATE-02_Lazy-Import-Pattern.md
- GATE-03_Cross-Interface-Communication-Rule.md
- GATE-04_Gateway-Wrapper-Functions.md
- GATE-05_Intra-Interface-vs-Cross-Interface-Imports.md
- Gateway-Cross-Reference-Matrix.md
- Gateway-Quick-Index.md

### Interfaces (/entries/interfaces/ - 14 files)

**12 Interface Patterns:**
- INT-01_CACHE-Interface-Pattern.md
- INT-02_LOGGING-Interface-Pattern.md
- INT-03_SECURITY-Interface-Pattern.md
- INT-04-HTTP-Interface.md
- INT-05-Initialization-Interface.md
- INT-06-Config-Interface.md
- INT-07-Metrics-Interface.md
- INT-08-Debug-Interface.md
- INT-09-Singleton-Interface.md
- INT-10-Utility-Interface.md
- INT-11-WebSocket-Interface.md
- INT-12-Circuit-Breaker-Interface.md

**Navigation:**
- Interface-Cross-Reference-Matrix.md
- Interface-Quick-Index.md

### Lessons (/entries/lessons/ - 71 files)

**Categories:**
- bugs/ (5 files) - Bug reports and fixes
- core-architecture/ (8 files) - Architecture lessons
- documentation/ (5 files) - Documentation lessons
- evolution/ (4 files) - System evolution
- learning/ (5 files) - Learning meta-lessons
- operations/ (20 files) - Operational lessons
- optimization/ (13 files) - Optimization lessons
- performance/ (5 files) - Performance lessons
- wisdom/ (7 files) - Profound insights

**Master Index:** Lessons-Master-Index.md

### Specifications (/entries/specifications/ - 11 files)

**SPEC Files:**
- SPEC-CHANGELOG.md
- SPEC-CONTINUATION.md
- SPEC-ENCODING.md
- SPEC-FILE-STANDARDS.md
- SPEC-FUNCTION-DOCS.md
- SPEC-HEADERS.md
- SPEC-KNOWLEDGE-CONFIG.md
- SPEC-LINE-LIMITS.md
- SPEC-MARKDOWN.md
- SPEC-NAMING.md
- SPEC-STRUCTURE.md

---

## /sima/integration/ - INTEGRATION (4 files)

**Purpose:** End-to-end workflows and integration testing

**Files:**
- E2E-Workflow-Example-01-Feature-Implementation.md
- E2E-Workflow-Example-02-Debug-Issue.md
- Integration-Test-Framework.md
- System-Integration-Guide.md

---

## /sima/languages/ - LANGUAGE PATTERNS (92 files)

**Purpose:** Language-specific implementations and patterns

**Structure:**
```
/languages/
└── python/
    └── architectures/
        ├── cr-1/    # Cache Registry (6 files)
        ├── dd-1/    # Dictionary Dispatch (7 files)
        ├── dd-2/    # Dependency Disciplines (7 files)
        ├── lmms/    # Lazy Module Management (15 files)
        ├── suga/    # SUGA Implementation (44 files)
        └── zaph/    # Zero Abstraction Path (13 files)
```

### CR-1: Cache Registry (6 files)

**Structure:**
```
/cr-1/
├── core/ (3 files)
├── decisions/ (1 file)
├── indexes/ (1 file)
└── lessons/ (1 file)
```

### DD-1: Dictionary Dispatch (7 files)

**Structure:**
```
/dd-1/
├── core/ (3 files)
├── decisions/ (2 files)
├── indexes/ (1 file)
└── lessons/ (2 files)
```

### DD-2: Dependency Disciplines (7 files)

**Structure:**
```
/dd-2/
├── anti-patterns/ (1 file)
├── core/ (3 files)
├── decisions/ (2 files)
├── indexes/ (1 file)
└── lessons/ (2 files)
```

### LMMS: Lazy Module Management (15 files)

**Structure:**
```
/lmms/
├── anti-patterns/ (4 files)
├── core/ (3 files)
├── decisions/ (4 files)
├── indexes/ (2 files)
└── lessons/ (4 files)
```

### SUGA: Gateway Architecture (44 files)

**Structure:**
```
/suga/
├── anti-patterns/ (5 files)
├── core/ (3 files)
├── decisions/ (5 files)
├── gateways/ (3 files)
├── indexes/ (2 files)
├── interfaces/ (17 files)
└── lessons/ (9 files)
```

### ZAPH: Zero Abstraction Path (13 files)

**Structure:**
```
/zaph/
├── Anti-Patterns/ (4 files)
├── Decisions/ (4 files)
├── Indexes/ (1 file)
└── Lessons/ (4 files)
```

---

## /sima/platforms/ - PLATFORM KNOWLEDGE (59 files)

**Purpose:** Platform-specific implementations

**Structure:**
```
/platforms/
└── aws/
    ├── api-gateway/  (10 files)
    ├── dynamodb/     (13 files)
    └── lambda/       (36 files)
```

### AWS API Gateway (10 files)

**Structure:**
```
/api-gateway/
├── anti-patterns/ (1 file)
├── core/ (1 file)
├── decisions/ (2 files)
├── lessons/ (3 files)
└── AWS-APIGateway-Master-Index.md
```

### AWS DynamoDB (13 files)

**Structure:**
```
/dynamodb/
├── anti-patterns/ (4 files)
├── core/ (1 file)
├── decisions/ (3 files)
├── indexes/ (1 file)
└── lessons/ (5 files)
```

### AWS Lambda (36 files)

**Structure:**
```
/lambda/
├── anti-patterns/ (6 files)
├── core/ (5 files)
├── decisions/ (9 files)
└── lessons/ (16 files)
```

---

## /sima/projects/ - PROJECT IMPLEMENTATIONS (37 files)

**Purpose:** Project-specific knowledge and configurations

**Structure:**
```
/projects/
├── LEE/            # Lambda Execution Engine (25 files)
├── templates/      # Project templates (10 files)
├── tools/          # Project tools (2 files)
└── project config files
```

### LEE Project (25 files)

**Structure:**
```
/LEE/
├── config/         # knowledge-config.yaml
├── decisions/      # LEE-specific decisions (3 files)
├── indexes/        # LEE-Index-Main.md
├── lessons/        # LEE-specific lessons (5 files)
├── nmp01/          # Neural maps (10 files)
├── LEE-Architecture-Integration-Patterns.md
├── LEE-Architecture-Overview.md
├── project_config.md
└── README.md
```

### Templates (10 files)

**Available Templates:**
- architecture_doc_template.md
- bug_report_template.md
- decision_log_template.md
- gateway_pattern_template.md
- interface_catalog_template.md
- lesson_learned_template.md
- nmp_entry-template.md
- project_config_template.md
- project_readme_template.md

### Tools (2 files)

**HTML Tools:**
- nmp_generator.html
- project_configurator.html

---

## /sima/redirects/ - MIGRATION SUPPORT (4 files)

**Purpose:** SIMAv3 to SIMAv4 migration support

**Files:**
- REF-ID-Mapping.md
- Redirect-Index.md
- tools/check-file-sizes.py
- tools/validate-links.py

---

## /sima/shared/ - SHARED KNOWLEDGE (6 files)

**Purpose:** Reference knowledge available to all modes

**Files:**
- Artifact-Standards.md
- Common-Patterns.md
- Encoding-Standards.md
- File-Standards.md
- RED-FLAGS.md
- SUGA-Architecture.md

**Usage:** Referenced by all context modes for consistent standards

---

## /sima/support/ - TOOLS & WORKFLOWS (36 files)

**Purpose:** Operational support, tools, and workflows

**Structure:**
```
/support/
├── checklists/       # Verification checklists (4 files)
├── quick-reference/  # Quick reference cards (3 files)
├── templates/        # Entry templates (3 files)
├── tools/           # Utility tools (13 files)
├── utilities/        # Migration utilities (1 file)
├── workflows/        # Standard workflows (6 files)
└── configuration files (6 files)
```

### Checklists (4 files)

- Checklist-01-Code-Review.md
- Checklist-02-Deployment-Readiness.md
- Checklist-03-Documentation-Quality.md
- Tool-Integration-Verification.md

### Quick Reference (3 files)

- QRC-01-Interfaces-Overview.md
- QRC-02-Gateway-Patterns.md
- QRC-03-Common-Patterns.md

### Tools (13 files)

**Reference Tools:**
- TOOL-01-REF-ID-Directory.md
- TOOL-02-Quick-Answer-Index.md
- TOOL-03-Anti-Pattern-Checklist.md
- TOOL-04-Verification-Protocol.md

**HTML Tools:**
- file-server-config.ui.html
- neural-map-index-builder.html
- project-config-ui.html

**Python Scripts:**
- generate-urls.py
- scan-hardcoded-urls.py

### Workflows (6 files)

- Workflow-01-Add-Feature.md
- Workflow-02-Debug-Issue.md
- Workflow-03-Update-Interface.md
- Workflow-04-Add-Gateway-Function.md
- Workflow-05-Create-Documentation-Entry.md
- Workflow-Index.md

---

## FILE COUNT SUMMARY

| Directory | Files | Purpose |
|-----------|-------|---------|
| /context | 15 | Mode contexts |
| /docs | 22 | User guides |
| /entries | 233 | Generic knowledge |
| /integration | 4 | Integration guides |
| /languages | 92 | Language patterns |
| /platforms | 59 | Platform knowledge |
| /projects | 37 | Project implementations |
| /redirects | 4 | Migration support |
| /shared | 6 | Shared references |
| /support | 36 | Tools & workflows |
| **Total** | **512** | **Documentation files** |

---

## NAVIGATION INDEXES

**Master Indexes:**
- /entries/Master-Index-of-Indexes.md
- /entries/SIMA-Navigation-Hub.md
- /entries/SIMA-Quick-Reference-Card.md

**Category Indexes:**
- Anti-Patterns-Master-Index.md
- Decisions-Master-Index.md
- Lessons-Master-Index.md
- Platforms-Master-Index.md

**Cross-Reference Tools:**
- Master-Cross-Reference-Matrix.md
- Index-Verification-Checklist.md

---

## KEY ORGANIZATIONAL PRINCIPLES

### 1. Domain Separation
```
Generic (entries/) 
  ↓
Platform (platforms/aws/)
  ↓
Language (languages/python/)
  ↓
Project (projects/LEE/)
```

### 2. File Standards
- Markdown format (.md)
- ≤400 lines per file
- UTF-8 encoding
- Headers required
- REF-ID cross-references

### 3. Naming Conventions
- TYPE-##.md for entries (LESS-01, DEC-01, AP-01, etc.)
- Category-Index.md for indexes
- Descriptive names for documentation

### 4. Index Hierarchy
- Master indexes at top level
- Category indexes per directory
- Cross-reference matrices
- Quick indexes for common access

---

## VERSION HISTORY

**v1.0.0 (2025-11-10):**
- Initial complete directory structure
- 512 total documentation files
- 15 context files
- 6 architecture patterns documented
- 3 AWS services covered
- 2 active projects (LEE, SIMA)

---

**END OF DIRECTORY STRUCTURE**

**Purpose:** Complete SIMA file organization reference  
**Lines:** 395 (within 400 limit)  
**Maintained:** As files added/removed  
**Usage:** Navigation, understanding structure, planning additions

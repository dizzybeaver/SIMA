# File: project_config_template.md

**Template Version:** 1.0.0  
**Purpose:** Multi-project configuration template  
**Location:** `/sima/projects/templates/`

---

## ðŸ"‹ PROJECT CONFIGURATION TEMPLATE

Copy this template to create new project configurations.

---

## ðŸ" PROJECT IDENTITY

```yaml
project:
  id: "PROJECT_CODE"              # e.g., "LEE", "ATLAS", "NOVA"
  name: "Project Full Name"       # e.g., "Lambda Event Engine"
  description: "Brief project description (1-2 sentences)"
  
  status: "active"                # active, maintenance, archived
  priority: "high"                # critical, high, medium, low
  
  created: "YYYY-MM-DD"
  last_updated: "YYYY-MM-DD"
  
  owner: "Team/Person Name"
  contacts:
    - "Primary contact email/name"
    - "Secondary contact email/name"
```

---

## ðŸŽ¯ PROJECT SCOPE

### Primary Purpose
[What this project does - 2-3 sentences]

### Key Technologies
- Technology 1 (version)
- Technology 2 (version)
- Technology 3 (version)

### Architecture Pattern
- **Primary Pattern:** [e.g., SUGA, Microservices, Monolith]
- **Supporting Patterns:** [e.g., LMMS, ZAPH, Event-Driven]

---

## ðŸ"‚ NMP STRUCTURE

### NMP Prefix
```yaml
nmp_prefix: "NMP##"              # e.g., "NMP01", "NMP02"
project_code: "PROJECT"          # e.g., "LEE", "ATLAS"
```

### NMP Categories

**Interface Catalogs:**
- `NMP##-PROJECT-02` - Cache Interface Functions
- `NMP##-PROJECT-06` - Logging Interface Functions
- `NMP##-PROJECT-08` - Security Interface Functions
- `NMP##-PROJECT-##` - [Other Interface] Functions

**Gateway Patterns:**
- `NMP##-PROJECT-15` - Gateway Execute Operation
- `NMP##-PROJECT-16` - Gateway Fast Path
- `NMP##-PROJECT-##` - [Other Gateway] Pattern

**Integration Patterns:**
- `NMP##-PROJECT-20` - [External System] Integration
- `NMP##-PROJECT-##` - [Other Integration] Pattern

**Specialized Patterns:**
- `NMP##-PROJECT-23` - Circuit Breaker Pattern
- `NMP##-PROJECT-##` - [Other Pattern]

**Project Documentation:**
- `NMP##-PROJECT-Cross-Reference-Matrix.md`
- `NMP##-PROJECT-Quick-Index.md`

---

## ðŸ—‚ï¸ DIRECTORY STRUCTURE

```
/sima/nmp/[project_code]/
â"œâ"€â"€ interfaces/                  # Interface catalogs
â"‚   â"œâ"€â"€ NMP##-PROJECT-02-Cache.md
â"‚   â"œâ"€â"€ NMP##-PROJECT-06-Logging.md
â"‚   â""â"€â"€ NMP##-PROJECT-08-Security.md
â"‚
â"œâ"€â"€ gateways/                    # Gateway patterns
â"‚   â"œâ"€â"€ NMP##-PROJECT-15-Execute.md
â"‚   â""â"€â"€ NMP##-PROJECT-16-FastPath.md
â"‚
â"œâ"€â"€ integrations/                # External integrations
â"‚   â""â"€â"€ NMP##-PROJECT-20-Integration.md
â"‚
â"œâ"€â"€ patterns/                    # Specialized patterns
â"‚   â""â"€â"€ NMP##-PROJECT-23-CircuitBreaker.md
â"‚
â"œâ"€â"€ bugs/                        # Project-specific bugs
â"‚   â""â"€â"€ BUG-##.md
â"‚
â"œâ"€â"€ lessons/                     # Project-specific lessons
â"‚   â""â"€â"€ LESS-##.md
â"‚
â"œâ"€â"€ NMP##-PROJECT-Cross-Reference-Matrix.md
â"œâ"€â"€ NMP##-PROJECT-Quick-Index.md
â"œâ"€â"€ project_config.md           # This file
â""â"€â"€ README.md                    # Project overview
```

---

## ðŸ"Š NMP ENTRY GUIDELINES

### Naming Convention
```
NMP##-PROJECT-##-Description.md

Where:
- NMP## = NMP prefix (e.g., NMP01)
- PROJECT = Project code (e.g., LEE)
- ## = Entry number (01-99)
- Description = Short descriptor (3-4 words)
```

### Entry Requirements
- âœ… REF-ID follows format: `NMP##-PROJECT-##`
- âœ… Category specified
- âœ… Status: Active/Maintenance/Archived
- âœ… Inherits from generic SIMA entries where applicable
- âœ… Project-specific implementation details
- âœ… Cross-references to related NMPs
- âœ… Max 400 lines per file

### Generic vs Project-Specific

**Use Generic SIMA Entries For:**
- Universal patterns (SUGA, LMMS, ZAPH)
- Language patterns (Python idioms)
- Interface patterns (general cache, logging)
- Anti-patterns (import violations)

**Use Project NMPs For:**
- Specific function catalogs
- Project-specific implementations
- Integration details
- Custom patterns
- Project bugs and lessons

---

## ðŸ"— CROSS-REFERENCE RULES

### Inherits Relationships
```yaml
# NMP inherits from generic entry
inherits:
  - REF-ID: "INT-01"
    note: "Extends cache interface with project-specific functions"
```

### Related Relationships
```yaml
# NMP relates to other NMPs
related:
  - REF-ID: "NMP##-PROJECT-##"
    note: "Used together for [pattern]"
```

### Integration Points
```yaml
# NMP integrates with external systems
integrates_with:
  - system: "External System Name"
    nmp: "NMP##-PROJECT-##"
```

---

## ðŸ"§ PROJECT-SPECIFIC CONVENTIONS

### Code Style
- **Language:** [Primary language]
- **Style Guide:** [e.g., PEP 8, ESLint config]
- **Linting:** [Tools used]
- **Formatting:** [Auto-formatter]

### Documentation
- **Format:** Markdown
- **Location:** `/sima/nmp/[project]/`
- **Updates:** [Frequency/triggers]

### Testing
- **Framework:** [Testing framework]
- **Coverage Target:** [e.g., 80%]
- **CI/CD:** [Pipeline info]

---

## ðŸ" INTERFACE CATALOG TRACKING

### Implemented Interfaces
| Interface | NMP Entry | Status | Functions Count |
|-----------|-----------|--------|-----------------|
| Cache | NMP##-PROJECT-02 | Active | ## |
| Config | - | Not Used | 0 |
| Debug | - | Planned | 0 |
| HTTP | - | Not Used | 0 |
| Init | - | Planned | 0 |
| Logging | NMP##-PROJECT-06 | Active | ## |
| Metrics | - | Planned | 0 |
| Security | NMP##-PROJECT-08 | Active | ## |
| Singleton | - | Not Used | 0 |
| Utility | - | Planned | 0 |
| WebSocket | - | Not Used | 0 |
| Circuit Breaker | NMP##-PROJECT-23 | Active | ## |

---

## ðŸ—ºï¸ GATEWAY PATTERN TRACKING

### Implemented Gateways
| Pattern | NMP Entry | Status | Coverage |
|---------|-----------|--------|----------|
| Three-File Structure | All | Active | 100% |
| Lazy Loading | All | Active | 100% |
| Cross-Interface Comm | NMP##-PROJECT-15 | Active | Full |
| Wrapper Functions | All | Active | 100% |
| Optimization | NMP##-PROJECT-16 | Active | Full |

---

## 🎨 SPECIALIZED PATTERNS

### Custom Patterns Implemented
1. **Pattern Name:**
   - NMP: `NMP##-PROJECT-##`
   - Purpose: [What it does]
   - Status: [Active/Planned]

2. **Pattern Name:**
   - NMP: `NMP##-PROJECT-##`
   - Purpose: [What it does]
   - Status: [Active/Planned]

---

## ðŸ"Š METRICS & MONITORING

### Key Metrics
- **Metric 1:** [Description] - Target: [Value]
- **Metric 2:** [Description] - Target: [Value]
- **Metric 3:** [Description] - Target: [Value]

### Monitoring Tools
- Tool 1: [Purpose]
- Tool 2: [Purpose]

---

## ðŸ" DEPLOYMENT INFO

### Environments
- **Development:** [URL/location]
- **Staging:** [URL/location]
- **Production:** [URL/location]

### Deployment Process
1. [Step 1]
2. [Step 2]
3. [Step 3]

### Rollback Procedure
1. [Step 1]
2. [Step 2]

---

## ðŸ"… MAINTENANCE SCHEDULE

### Regular Tasks
- **Daily:** [Tasks]
- **Weekly:** [Tasks]
- **Monthly:** [Tasks]
- **Quarterly:** [Tasks]

### Review Cycles
- **NMP Review:** [Frequency]
- **Architecture Review:** [Frequency]
- **Security Review:** [Frequency]

---

## ðŸ"š RESOURCES

### Documentation
- **Project Docs:** [Location]
- **API Docs:** [Location]
- **Runbooks:** [Location]

### External Links
- **Repository:** [URL]
- **CI/CD:** [URL]
- **Monitoring:** [URL]

---

## ðŸ" VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0.0 | YYYY-MM-DD | [Name] | Initial configuration |

---

**END OF TEMPLATE**

**Next Steps:**
1. Copy this template
2. Replace all placeholders with project values
3. Save as `project_config.md` in project directory
4. Update `projects_config.md` registry
5. Create project README using `project_readme_template.md`

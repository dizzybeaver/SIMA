# SIMA Projects Configuration

**Version:** 1.0.0  
**Last Updated:** 2025-10-28  
**Purpose:** Central registry of all projects using SIMA  
**Location:** `/sima/config/projects_config.md`

---

## ðŸŽ¯ OVERVIEW

This file maintains the registry of all projects that use SIMA for knowledge management, documentation, and architectural guidance. Each project has its own isolated structure under `/projects/[PROJECT_NAME]/`.

**Key Responsibilities:**
- Central project discovery
- Project status tracking
- Configuration location mapping
- Multi-project coordination
- Preventing cross-contamination

---

## ðŸ"‹ REGISTERED PROJECTS

### Project: SUGA-ISP
**Status:** âœ… Active  
**Full Name:** Serverless Unified Gateway Architecture - Internet Service Provider  
**Location:** `/projects/SUGA-ISP/`  
**Config:** `/projects/SUGA-ISP/sima/config/SUGA-ISP-PROJECT-CONFIG.md`  
**Source:** `/projects/SUGA-ISP/src/`  
**Description:** AWS Lambda-based ISP service implementing SUGA architecture pattern with DynamoDB backend  

**Architectures Used:**
- SUGA (Serverless Unified Gateway Architecture)
- LMMS (Lazy Module Management System)
- DD (Dependency Declaration)
- ZAPH (Zero-to-Answer Path Heuristic)

**Languages:** Python 3.10  
**Platform:** AWS Lambda, DynamoDB, API Gateway, Parameter Store  
**Deployment:** CloudFormation, SAM  

**Neural Maps Location:** `/projects/SUGA-ISP/sima/nmp/`
- Constraints: `/projects/SUGA-ISP/sima/nmp/constraints/`
- Combinations: `/projects/SUGA-ISP/sima/nmp/combinations/`
- Lessons: `/projects/SUGA-ISP/sima/nmp/lessons/`
- Decisions: `/projects/SUGA-ISP/sima/nmp/decisions/`
- Master Index: `/projects/SUGA-ISP/sima/nmp/NMP00/NMP00-SUGA-ISP-Master-Index.md`

**Last Updated:** 2025-10-28  
**Team Contact:** [Primary maintainer]

---

### Project: LEE
**Status:** âœ… Active  
**Full Name:** Lambda Execution Engine  
**Location:** `/projects/LEE/`  
**Config:** `/projects/LEE/sima/config/LEE-PROJECT-CONFIG.md`  
**Source:** `/projects/LEE/src/`  
**Description:** Core execution engine for AWS Lambda functions with optimized cold start and runtime performance

**Architectures Used:**
- SUGA (Serverless Unified Gateway Architecture)
- ZAPH (Zero-to-Answer Path Heuristic)

**Languages:** Python 3.10  
**Platform:** AWS Lambda  
**Deployment:** Direct Lambda deployment  

**Neural Maps Location:** `/projects/LEE/sima/nmp/`
- Constraints: `/projects/LEE/sima/nmp/constraints/`
- Combinations: `/projects/LEE/sima/nmp/combinations/`
- Lessons: `/projects/LEE/sima/nmp/lessons/`
- Decisions: `/projects/LEE/sima/nmp/decisions/`
- Master Index: `/projects/LEE/sima/nmp/NMP00/NMP00-LEE-Master-Index.md`

**Last Updated:** 2025-10-28  
**Team Contact:** [Primary maintainer]

---

### Project: [TEMPLATE_FOR_FUTURE_PROJECTS]
**Status:** ðŸ"¹ Template  
**Full Name:** [Project Full Name]  
**Location:** `/projects/[PROJECT_NAME]/`  
**Config:** `/projects/[PROJECT_NAME]/sima/config/[PROJECT_NAME]-PROJECT-CONFIG.md`  
**Source:** `/projects/[PROJECT_NAME]/src/`  
**Description:** [Brief project description]

**Architectures Used:**
- [List architectures: SUGA, LMMS, DD, ZAPH, others]

**Languages:** [Programming language(s)]  
**Platform:** [Deployment platform]  
**Deployment:** [Deployment method]  

**Neural Maps Location:** `/projects/[PROJECT_NAME]/sima/nmp/`

**Last Updated:** [Date]  
**Team Contact:** [Primary maintainer]

---

## ðŸ"Š PROJECT STATISTICS

**Total Projects:** 2 active, unlimited capacity  
**Active Architectures:** SUGA, LMMS, DD, ZAPH  
**Primary Language:** Python 3.10  
**Primary Platform:** AWS Lambda  
**Total Neural Map Entries:** [Tracked per project]

**By Status:**
- âœ… Active: 2 (SUGA-ISP, LEE)
- ðŸ"¹ Template: 1 (for future projects)
- ðŸ"¶ Planned: 0
- ðŸ›' Archived: 0

---

## ðŸ"§ ADDING NEW PROJECTS

### Quick Start Guide

**Step 1: Copy Template**
```bash
cp -r /sima/config/templates/project_template/ /projects/[PROJECT_NAME]/
```

**Step 2: Configure Project**
- Edit `/projects/[PROJECT_NAME]/sima/config/[PROJECT_NAME]-PROJECT-CONFIG.md`
- Update project details, architectures, languages
- Set up neural map structure

**Step 3: Register in This File**
- Add new project entry to "REGISTERED PROJECTS" section
- Update project statistics
- Set status to âœ… Active

**Step 4: Generate File Server URLs**
```bash
cd /projects/[PROJECT_NAME]/sima/support/
# Edit SERVER-CONFIG.md with your base URL
python3 generate-urls.py
```

**Step 5: Deploy to File Server**
- Upload project structure to file server
- Verify File-Server-URLs.md works
- Test Claude session loading

**Complete Guide:** See `/sima/docs/Adding-New-Projects.md`

---

## ðŸ—ºï¸ PROJECT DIRECTORY STRUCTURE

### Standard Project Layout

```
/projects/[PROJECT_NAME]/
├── src/                          # Source code
│   ├── gateway.py               # Gateway layer
│   ├── interface_*.py           # Interface layers
│   └── *_core.py                # Core implementations
│
├── sima/                         # SIMA configuration
│   ├── config/
│   │   ├── [PROJECT]-PROJECT-CONFIG.md
│   │   ├── [PROJECT]-ARCHITECTURES.md
│   │   └── [PROJECT]-LANGUAGE-*.md
│   │
│   ├── nmp/                     # Neural maps (project-specific)
│   │   ├── NMP00/               # Master indexes
│   │   ├── constraints/         # LAM-CONST-*, LANG-CONST-*
│   │   ├── combinations/        # COMB-*
│   │   ├── lessons/             # LESS-*, BUG-*
│   │   └── decisions/           # DEC-*
│   │
│   └── support/
│       ├── SERVER-CONFIG.md     # Project file server config
│       ├── File-Server-URLs.md  # Generated URLs
│       └── tools/               # Project-specific tools
│
├── tests/                        # Test files
├── docs/                         # Project documentation
└── README.md                     # Project overview
```

---

## ðŸ"€ CROSS-PROJECT REFERENCES

### ❌ Never Reference Projects Directly

**WRONG - Hard-coded project reference:**
```markdown
See: /projects/SUGA-ISP/sima/nmp/constraints/LAM-CONST-001.md
```

**RIGHT - Base SIMA reference:**
```markdown
See: /sima/entries/architectures/suga/SUGA-001-gateway-layer.md
```

**RIGHT - Generic pattern:**
```markdown
For Lambda constraints, see your project's NMP/constraints/ directory
```

### Sharing Knowledge Between Projects

**Use Base SIMA for:**
- Generic architecture patterns
- Language features
- Design principles
- Reusable concepts

**Keep in Projects for:**
- Implementation specifics
- AWS configurations
- Deployment details
- Environment settings
- Project-specific lessons

---

## ðŸ"' PROJECT ISOLATION RULES

### Critical Principles

1. **No Cross-Contamination**
   - SUGA-ISP content stays in `/projects/SUGA-ISP/`
   - LEE content stays in `/projects/LEE/`
   - Generic content goes in `/sima/entries/`

2. **No Direct Dependencies**
   - Projects cannot import from other projects
   - Each project is self-contained
   - Share via Base SIMA abstractions only

3. **Independent Versioning**
   - Each project versions independently
   - No synchronized releases required
   - Projects evolve at their own pace

4. **Separate File Servers**
   - Each project can have its own base URL
   - Or use subdirectories of shared server
   - File-Server-URLs.md per project

---

## ðŸ"Š PROJECT HEALTH METRICS

### Per-Project Tracking

**For each active project, track:**
- Neural map entry count
- Last update date
- Documentation completeness
- Architecture compliance
- Test coverage
- Deployment frequency

**Health Indicators:**
- 🟢 Green: Active development, up-to-date
- 🟡 Yellow: Stable, maintenance mode
- 🔴 Red: Outdated, needs attention
- ðŸ›' Archived: No longer maintained

**Current Project Health:**
- SUGA-ISP: 🟢 Green (active development)
- LEE: 🟢 Green (active development)

---

## ðŸ"„ PROJECT LIFECYCLE

### States

1. **ðŸ"¹ Template** - Base structure, not yet active
2. **ðŸ"¶ Planned** - Approved, not yet started
3. **🚧 In Progress** - Initial development
4. **âœ… Active** - Production, actively maintained
5. **🟡 Maintenance** - Stable, minimal changes
6. **ðŸ›' Archived** - No longer maintained, read-only

### State Transitions

```
Template â†' Planned â†' In Progress â†' Active â†' Maintenance â†' Archived
         â†--Rejected          â†--Cancelled    â†--Revived--↲
```

---

## ðŸ" MAINTENANCE

### Regular Tasks

**Weekly:**
- [ ] Verify all projects load correctly
- [ ] Check File-Server-URLs.md validity
- [ ] Update "Last Updated" dates

**Monthly:**
- [ ] Review project health metrics
- [ ] Archive inactive projects
- [ ] Update project statistics
- [ ] Clean up obsolete entries

**Quarterly:**
- [ ] Full project audit
- [ ] Cross-contamination check
- [ ] Documentation completeness review
- [ ] Architecture compliance verification

### Contact for Issues

**File Location Issues:** See `/sima/support/tools/`  
**Project Structure Questions:** See `/sima/docs/Project-Structure-Guide.md`  
**New Project Requests:** See `/sima/docs/Adding-New-Projects.md`  

---

## ðŸ"– DOCUMENTATION LINKS

**Related Documents:**
- `/sima/docs/Project-Structure-Guide.md` - Complete structure documentation
- `/sima/docs/Adding-New-Projects.md` - Step-by-step new project guide
- `/sima/docs/Cross-Project-Guidelines.md` - Sharing knowledge rules
- `/sima/config/templates/project_template/` - Base template files
- `SIMAv4-Master-Control-Implementation.md` - Phase tracking

**Support Tools:**
- `/sima/support/tools/project-config-generator.html` - UI for project setup
- `/sima/support/tools/neural-map-index-builder.html` - Index generation
- `/sima/support/tools/cross-reference-validator.py` - Reference checking

---

## ðŸ"' VERSION HISTORY

**v1.0.0 (2025-10-28)**
- Initial creation
- Registered SUGA-ISP project
- Registered LEE project
- Defined project structure standards
- Established isolation rules
- Created template for future projects

---

**END OF PROJECTS CONFIGURATION**

**Maintained By:** SIMA Team  
**Next Review:** 2025-11-28  
**Status:** âœ… Active  
**Projects Registered:** 2 active

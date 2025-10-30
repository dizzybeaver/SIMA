# SIMAv4 Project Structure Addendum - Multi-Project Organization

**Version:** 1.0.0  
**Date:** 2025-10-27  
**Purpose:** Define multi-project organization structure for SIMAv4  
**Status:** Planning / Architecture Decision  
**Applies To:** All SIMAv4 phases

---

## ðŸŽ¯ CORE PRINCIPLE

**Base SIMA is generic knowledge only. Project-specific knowledge lives with the project.**

This ensures:
- âœ… Clear separation of concerns
- âœ… No confusion between generic and project-specific
- âœ… Easy project addition/removal
- âœ… Independent project lifecycle management
- âœ… Scalable to unlimited projects

---

## ðŸ—‚ï¸ NEW DIRECTORY STRUCTURE

### Root Level Organization

```
/                                        # Repository root
â"‚
â"œâ"€â"€ sima/                               # ðŸ†• Base SIMA (generic knowledge only)
â"‚   â"œâ"€â"€ config/
â"‚   â"‚   â"œâ"€â"€ SIMA-MAIN-CONFIG.md
â"‚   â"‚   â"œâ"€â"€ projects_config.md         # ðŸ†• Lists all registered projects
â"‚   â"‚   â""â"€â"€ templates/
â"‚   â"‚       â"œâ"€â"€ project_template/       # ðŸ†• Complete project starter
â"‚   â"‚       â"œâ"€â"€ architecture_template/
â"‚   â"‚       â""â"€â"€ language_template/
â"‚   â"œâ"€â"€ gateways/
â"‚   â"œâ"€â"€ interfaces/
â"‚   â"œâ"€â"€ entries/
â"‚   â"‚   â"œâ"€â"€ core/                      # Universal concepts only
â"‚   â"‚   â"œâ"€â"€ architectures/            # Generic architectures (SUGA, LMMS, DD, ZAPH)
â"‚   â"‚   â""â"€â"€ languages/                # Generic language knowledge (Python)
â"‚   â"œâ"€â"€ zaph/
â"‚   â"œâ"€â"€ support/
â"‚   â"œâ"€â"€ docs/
â"‚   â""â"€â"€ planning/
â"‚
â""â"€â"€ projects/                            # ðŸ†• All individual projects
    â"‚
    â"œâ"€â"€ SUGA-ISP/                        # Example: SUGA-ISP project
    â"‚   â"œâ"€â"€ src/                         # Project source code
    â"‚   â"‚   â"œâ"€â"€ gateway.py
    â"‚   â"‚   â"œâ"€â"€ lambda_function.py
    â"‚   â"‚   â""â"€â"€ ... (all Python files)
    â"‚   â"‚
    â"‚   â""â"€â"€ sima/                        # Project SIMA configuration
    â"‚       â"œâ"€â"€ config/
    â"‚       â"‚   â"œâ"€â"€ SUGA-ISP-PROJECT-CONFIG.md   # Master config
    â"‚       â"‚   â"œâ"€â"€ SUGA-ISP-ARCHITECTURES.md    # Enabled architectures
    â"‚       â"‚   â""â"€â"€ SUGA-ISP-LANGUAGE-PYTHON.md  # Language config
    â"‚       â"‚
    â"‚       â"œâ"€â"€ nmp/                        # Project-specific neural maps
    â"‚       â"‚   â"œâ"€â"€ NMP00/
    â"‚       â"‚   â"‚   â"œâ"€â"€ NMP00-SUGA-ISP-Master-Index.md
    â"‚       â"‚   â"‚   â""â"€â"€ NMP00-SUGA-ISP-Quick-Index.md
    â"‚       â"‚   â"œâ"€â"€ constraints/
    â"‚       â"‚   â"‚   â"œâ"€â"€ LAM-CONST-001-memory-limit.md
    â"‚       â"‚   â"‚   â"œâ"€â"€ LAM-CONST-002-timeout-limit.md
    â"‚       â"‚   â"‚   â""â"€â"€ ... (Lambda constraints)
    â"‚       â"‚   â"œâ"€â"€ combinations/
    â"‚       â"‚   â"‚   â"œâ"€â"€ SUGA-ISP-LAM-089-caching-with-suga.md
    â"‚       â"‚   â"‚   â""â"€â"€ ... (architecture combinations)
    â"‚       â"‚   â"œâ"€â"€ lessons/
    â"‚       â"‚   â"‚   â""â"€â"€ ... (project-specific lessons)
    â"‚       â"‚   â""â"€â"€ decisions/
    â"‚       â"‚       â""â"€â"€ ... (project-specific decisions)
    â"‚       â"‚
    â"‚       â""â"€â"€ support/
    â"‚           â"œâ"€â"€ SERVER-CONFIG.md            # Project file server config
    â"‚           â"œâ"€â"€ File-Server-URLs.md         # Generated URLs
    â"‚           â""â"€â"€ tools/
    â"‚
    â"œâ"€â"€ LEE/                             # Example: Lambda Execution Engine
    â"‚   â"œâ"€â"€ src/                         # LEE source code
    â"‚   â"‚   â""â"€â"€ ... (LEE Python files)
    â"‚   â"‚
    â"‚   â""â"€â"€ sima/                        # LEE SIMA configuration
    â"‚       â"œâ"€â"€ config/
    â"‚       â"‚   â"œâ"€â"€ LEE-PROJECT-CONFIG.md
    â"‚       â"‚   â"œâ"€â"€ LEE-ARCHITECTURES.md
    â"‚       â"‚   â""â"€â"€ LEE-LANGUAGE-PYTHON.md
    â"‚       â"‚
    â"‚       â"œâ"€â"€ nmp/
    â"‚       â"‚   â"œâ"€â"€ NMP00/
    â"‚       â"‚   â"‚   â"œâ"€â"€ NMP00-LEE-Master-Index.md
    â"‚       â"‚   â"‚   â""â"€â"€ NMP00-LEE-Quick-Index.md
    â"‚       â"‚   â"œâ"€â"€ constraints/
    â"‚       â"‚   â"œâ"€â"€ combinations/
    â"‚       â"‚   â"œâ"€â"€ lessons/
    â"‚       â"‚   â""â"€â"€ decisions/
    â"‚       â"‚
    â"‚       â""â"€â"€ support/
    â"‚
    â""â"€â"€ [FUTURE_PROJECTS]/               # Additional projects as needed
        â""â"€â"€ ... (same structure)
```

---

## ðŸ"‹ BASE SIMA: projects_config.md

### Purpose

Central registry of all projects using SIMA. This file enables:
- Project discovery
- Project status tracking
- Configuration location mapping
- Multi-project coordination

### File Location

`/sima/config/projects_config.md`

### File Structure

```markdown
# SIMA Projects Configuration

**Version:** 1.0.0  
**Last Updated:** 2025-10-27  
**Purpose:** Central registry of all projects using SIMA

---

## REGISTERED PROJECTS

### Project: SUGA-ISP
**Status:** âœ… Active  
**Location:** `/projects/SUGA-ISP/`  
**Config:** `/projects/SUGA-ISP/sima/config/SUGA-ISP-PROJECT-CONFIG.md`  
**Source:** `/projects/SUGA-ISP/src/`  
**Description:** AWS Lambda ISP service with SUGA architecture  
**Architectures:** SUGA, LMMS, DD, ZAPH  
**Languages:** Python 3.10  
**Platform:** AWS Lambda + DynamoDB

### Project: LEE
**Status:** âœ… Active  
**Location:** `/projects/LEE/`  
**Config:** `/projects/LEE/sima/config/LEE-PROJECT-CONFIG.md`  
**Source:** `/projects/LEE/src/`  
**Description:** Lambda Execution Engine  
**Architectures:** SUGA, ZAPH  
**Languages:** Python 3.10  
**Platform:** AWS Lambda

### Project: [FUTURE_PROJECT]
**Status:** ðŸ"‹ Planned  
**Location:** `/projects/[NAME]/`  
**Config:** Not yet created  
**Source:** Not yet created  
**Description:** TBD  
**Architectures:** TBD  
**Languages:** TBD  
**Platform:** TBD

---

## PROJECT STATISTICS

**Total Projects:** 2 active, 1+ planned  
**Active Architectures:** SUGA, LMMS, DD, ZAPH  
**Active Languages:** Python  
**Platforms:** AWS Lambda, DynamoDB

---

## ADDING NEW PROJECTS

See: `/sima/config/templates/project_template/README.md`

**Quick Steps:**
1. Copy project template to `/projects/[PROJECT_NAME]/`
2. Update config files with project details
3. Register project in this file
4. Generate file server URLs
5. Deploy to file server
```

---

## ðŸ"¦ PROJECT TEMPLATE SYSTEM

### Complete Project Starter Template

**Location:** `/sima/config/templates/project_template/`

### Template Contents

```
project_template/
â"‚
â"œâ"€â"€ README.md                          # Template usage instructions
â"‚
â"œâ"€â"€ src/                               # Source code placeholder
â"‚   â""â"€â"€ .gitkeep
â"‚
â""â"€â"€ sima/                              # SIMA configuration structure
    â"‚
    â"œâ"€â"€ config/
    â"‚   â"œâ"€â"€ PROJECT-CONFIG-TEMPLATE.md  # Master config (fill in)
    â"‚   â"œâ"€â"€ ARCHITECTURES-TEMPLATE.md   # Architecture selection
    â"‚   â""â"€â"€ LANGUAGE-TEMPLATE.md        # Language configuration
    â"‚
    â"œâ"€â"€ nmp/                            # Neural map structure
    â"‚   â"œâ"€â"€ NMP00/
    â"‚   â"‚   â"œâ"€â"€ NMP00-Master-Index-TEMPLATE.md
    â"‚   â"‚   â""â"€â"€ NMP00-Quick-Index-TEMPLATE.md
    â"‚   â"œâ"€â"€ constraints/
    â"‚   â"‚   â""â"€â"€ CONSTRAINT-TEMPLATE.md
    â"‚   â"œâ"€â"€ combinations/
    â"‚   â"‚   â""â"€â"€ COMBINATION-TEMPLATE.md
    â"‚   â"œâ"€â"€ lessons/
    â"‚   â"‚   â""â"€â"€ LESSON-TEMPLATE.md
    â"‚   â""â"€â"€ decisions/
    â"‚       â""â"€â"€ DECISION-TEMPLATE.md
    â"‚
    â""â"€â"€ support/
        â"œâ"€â"€ SERVER-CONFIG-TEMPLATE.md
        â"œâ"€â"€ URL-GENERATOR-TEMPLATE.md
        â""â"€â"€ tools/
            â""â"€â"€ .gitkeep
```

### Template Files (Blank but SIMAv4 Compliant)

#### PROJECT-CONFIG-TEMPLATE.md

```markdown
# [PROJECT_NAME] - Project Configuration

**Version:** 1.0.0  
**Created:** [DATE]  
**Project Type:** [Web Service / CLI Tool / Library / etc.]  
**Platform:** [AWS Lambda / EC2 / Kubernetes / etc.]

---

## PROJECT OVERVIEW

**Name:** [PROJECT_NAME]  
**Description:** [One-line project description]  
**Repository:** [Git URL]  
**Production URL:** [If applicable]

---

## ARCHITECTURE CONFIGURATION

**Active Architectures:**
- [ ] SUGA (Gateway â†' Interface â†' Core)
- [ ] LMMS (Lazy Module Memory Singleton)
- [ ] DD (Dispatch Dictionary)
- [ ] ZAPH (Zero-Access-Time Pre-computed Hash)

See: `ARCHITECTURES-TEMPLATE.md` for details

---

## LANGUAGE CONFIGURATION

**Primary Language:** [Python / JavaScript / etc.]  
**Version:** [3.10 / etc.]  

See: `LANGUAGE-TEMPLATE.md` for details

---

## PLATFORM CONSTRAINTS

**Platform:** [AWS Lambda / etc.]  

**Key Constraints:**
- Memory: [128MB / etc.]
- Timeout: [3s / etc.]
- Cold Start: [< 3s / etc.]
- Concurrency: [Single-threaded / etc.]

See: `/nmp/constraints/` for detailed constraint files

---

## SIMA STRUCTURE

**Neural Maps Location:** `/projects/[PROJECT_NAME]/sima/nmp/`  
**Support Tools Location:** `/projects/[PROJECT_NAME]/sima/support/`  
**Base SIMA Reference:** `/sima/`

---

## FILE SERVER CONFIGURATION

**Server Config:** `support/SERVER-CONFIG.md`  
**Generated URLs:** `support/File-Server-URLs.md`  
**Base URL:** [https://your-domain.com/projects/[PROJECT_NAME]/]

---

## QUICK START

**For Development:**
1. Set up source code in `/src/`
2. Configure architectures in `config/ARCHITECTURES.md`
3. Create constraint files in `nmp/constraints/`
4. Generate file server URLs
5. Deploy to file server

**For Claude Sessions:**
1. Load: `SESSION-START-Quick-Context.md` (from base SIMA)
2. Reference: This project config
3. Use: Project-specific neural maps as needed

---

## MAINTENANCE

**Last Updated:** [DATE]  
**Next Review:** [DATE + 3 months]  
**Status:** [Active / In Development / Archived]
```

#### NMP00-Master-Index-TEMPLATE.md

```markdown
# [PROJECT_NAME] Neural Map - Master Index

**Project:** [PROJECT_NAME]  
**Version:** 1.0.0  
**Created:** [DATE]  
**Purpose:** Master index for all [PROJECT_NAME] neural map entries

---

## OVERVIEW

This directory contains project-specific neural maps for [PROJECT_NAME].

**Base SIMA Location:** `/sima/entries/`  
**Project SIMA Location:** `/projects/[PROJECT_NAME]/sima/nmp/`

**Relationship:**
- Base SIMA = Generic, reusable knowledge
- Project SIMA = Project-specific constraints and combinations

---

## CATEGORIES

### Constraints (Platform/Technology Limitations)
**Location:** `nmp/constraints/`  
**Count:** 0  
**Purpose:** Document platform-specific limitations

**Files:**
- (None yet - add as discovered)

### Combinations (Architecture + Constraint Integration)
**Location:** `nmp/combinations/`  
**Count:** 0  
**Purpose:** Document how architectures work within constraints

**Files:**
- (None yet - add as discovered)

### Lessons (Project-Specific Learnings)
**Location:** `nmp/lessons/`  
**Count:** 0  
**Purpose:** Capture project-specific lessons learned

**Files:**
- (None yet - add as discovered)

### Decisions (Project-Specific Design Choices)
**Location:** `nmp/decisions/`  
**Count:** 0  
**Purpose:** Document why project-specific decisions were made

**Files:**
- (None yet - add as discovered)

---

## STATISTICS

**Total Entries:** 0  
**Last Updated:** [DATE]  
**Next Review:** [DATE + 1 month]

---

## ADDING ENTRIES

1. Determine category (constraint, combination, lesson, decision)
2. Use appropriate template from this directory
3. Create entry file with REF-ID
4. Update this index
5. Update Quick Index

---

## RELATED INDEXES

**Base SIMA:**
- Master Index: `/sima/gateways/GATEWAY-CORE.md`
- Architecture Index: `/sima/gateways/GATEWAY-ARCHITECTURE.md`

**Project Quick Access:**
- Quick Index: `NMP00-[PROJECT_NAME]-Quick-Index.md`
```

---

## ðŸ› ï¸ HTML CONFIGURATION TOOLS

### Tool 1: Project Initial Configuration

**File:** `/sima/support/tools/project-config-generator.html`

**Purpose:**
- Create new project configuration
- Fill in all template files
- Generate initial directory structure
- Register project in projects_config.md

**Features:**
- âœ… Project metadata input (name, description, platform)
- âœ… Architecture selection (checkboxes for SUGA, LMMS, DD, ZAPH)
- âœ… Language configuration
- âœ… Platform constraint selection
- âœ… Generate all config files button
- âœ… Copy directory structure script
- âœ… Auto-fill templates with project details

**Workflow:**
1. Enter project name
2. Select architectures
3. Configure language
4. Define platform constraints
5. Click "Generate Project"
6. Download zip with all files
7. Extract to `/projects/[PROJECT_NAME]/`

---

### Tool 2: NMP Directory Manager

**File:** `/sima/support/tools/nmp-directory-manager.html`

**Purpose:**
- Add new categories to NMP structure
- Generate template files for new categories
- Update indexes automatically
- Maintain SIMA compliance

**Features:**
- âœ… Add new constraint files
- âœ… Add new combination files
- âœ… Add new lesson files
- âœ… Add new decision files
- âœ… Auto-update Master Index
- âœ… Auto-update Quick Index
- âœ… Validate REF-ID uniqueness
- âœ… Generate proper file structure

**Workflow:**
1. Select project
2. Choose category (constraint/combination/lesson/decision)
3. Enter entry details
4. Click "Generate Entry"
5. Download entry file
6. Download updated indexes
7. Place files in correct locations

---

## ðŸŽ¯ BENEFITS OF THIS STRUCTURE

### 1. Clear Separation

**Base SIMA:**
- Universal patterns (caching, lazy loading, etc.)
- Generic architectures (SUGA layers)
- Language fundamentals (Python idioms)
- Reusable across ALL projects

**Project SIMA:**
- Platform constraints (Lambda limits)
- Architecture combinations (SUGA + Lambda)
- Project-specific lessons
- Project-specific decisions

**No confusion possible** - location determines scope.

---

### 2. Independent Lifecycle

**Each project can:**
- âœ… Be added without affecting others
- âœ… Be removed without affecting others
- âœ… Have different SIMA versions
- âœ… Evolve independently
- âœ… Be archived independently

---

### 3. Scalability

**Add unlimited projects:**
- Each follows same template
- Each self-contained
- No base SIMA changes needed
- No cross-project conflicts

---

### 4. Clear Ownership

**Base SIMA team maintains:**
- `/sima/` directory
- Generic knowledge
- Architecture patterns
- Language fundamentals

**Project teams maintain:**
- `/projects/[PROJECT]/` directory
- Project-specific knowledge
- Constraint documentation
- Combination patterns

---

### 5. Deployment Simplicity

**Each project has:**
- Own file server config
- Own File-Server-URLs.md
- Own URL generation tools
- Independent deployment

---

## ðŸ"„ MIGRATION FROM OLD STRUCTURE

### Phase 0 Already Addresses This

Phase 0 (File Server Configuration) already:
- Genericizes file server URLs âœ…
- Creates configuration tools âœ…
- Supports multiple base URLs âœ…

**Additional work needed:**
- Move `/sima/entries/projects/` to `/projects/[NAME]/sima/nmp/`
- Create projects_config.md
- Generate project templates
- Build HTML configuration tools

**Estimate:** 1 additional week (after Phase 0)

---

## ðŸš€ IMPLEMENTATION PLAN

### Phase 0.5: Project Structure (New)

**Duration:** 1 week  
**After:** Phase 0 (File Server Config)  
**Before:** Phase 1 (Categorization)

**Tasks:**
1. Create `/projects/` directory structure
2. Move existing project-specific entries
3. Create projects_config.md
4. Build project template system
5. Create HTML configuration tools
6. Update Phase 1+ to use new structure
7. Document migration process

**Deliverables:**
- `/projects/SUGA-ISP/` fully populated
- `/projects/LEE/` fully populated
- projects_config.md complete
- Template system complete
- HTML tools functional

---

## âš ï¸ CRITICAL NOTES

### File Server URLs

**Each project needs:**
- Own SERVER-CONFIG.md
- Own File-Server-URLs.md
- Own base URL (or subdirectory)

**Example URLs:**
```
Base SIMA:
https://your-domain.com/sima/support/SESSION-START-Quick-Context.md

SUGA-ISP:
https://your-domain.com/projects/SUGA-ISP/sima/support/File-Server-URLs.md

LEE:
https://your-domain.com/projects/LEE/sima/support/File-Server-URLs.md
```

### Cross-Project References

**Never reference:**
```markdown
# WRONG - Hard-coded project reference
See: /projects/SUGA-ISP/sima/nmp/constraints/LAM-CONST-001.md
```

**Instead reference:**
```markdown
# RIGHT - Base SIMA reference
See: /sima/entries/architectures/suga/SUGA-001-gateway-layer.md

# Or generic pattern
For Lambda constraints, see your project's NMP/constraints/ directory
```

### Template Maintenance

**Templates must:**
- Stay SIMAv4 compliant
- Be updated when SIMA evolves
- Include all required sections
- Have clear [PLACEHOLDER] markers
- Be self-documenting

---

## ðŸ" EXAMPLE: Creating New Project

### Step 1: Copy Template

```bash
cp -r /sima/config/templates/project_template/ /projects/MyNewProject/
```

### Step 2: Configure Project

Open `project-config-generator.html`:
- Enter: "MyNewProject"
- Select: SUGA, ZAPH
- Language: Python 3.10
- Platform: AWS Lambda
- Click: "Generate Project"

### Step 3: Deploy Structure

```bash
# Generated files go to /projects/MyNewProject/sima/
# Source code goes to /projects/MyNewProject/src/
```

### Step 4: Register Project

Edit `/sima/config/projects_config.md`:
- Add MyNewProject entry
- Set status to "Active"
- Document location and details

### Step 5: Generate URLs

```bash
cd /projects/MyNewProject/sima/support/
python3 generate-urls.py
```

### Step 6: Deploy to File Server

Upload to: `https://your-domain.com/projects/MyNewProject/`

**Done!** Project is now SIMA-enabled.

---

**END OF ADDENDUM**

**Version:** 1.0.0  
**Impact:** All SIMAv4 phases  
**New Phase:** 0.5 (Project Structure)  
**Additional Time:** 1 week

**Next Action:** Review and approve structure â†' Implement Phase 0.5

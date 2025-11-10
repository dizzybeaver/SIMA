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
├── context/          # Mode activation contexts 
├── docs/             # User guides and planning 
├── entries/          # Generic knowledge base 
├── integration/      # Integration guides 
├── languages/        # Language-specific patterns 
├── platforms/        # Platform-specific knowledge 
├── projects/         # Project implementations 
├── redirects/        # Migration redirects 
└── support/          # Tools and workflows 
```

## Expanded STRUCTURE

/sima/
├── context/                # Mode activation contexts 
|     ├── ai/               # AI Loader files for various AI made by the AI to load SIMA Context
|     ├── general/          # General Context loader files
|     ├── shared/           # Shared context and custom instructions for AI
|     ├── sima/             # SIMA Context loader files and common custom instructions
|     ├── debug/            # debug Context loader files and common custom instructions
|     ├── projects/         # project Context loader files and common custom instructions
|     ├── workflows/        # Non-Project or platform specific workflows
|     └── new/              # Initialize new project and platforms context loader files and common custom instructions
├── docs/             # User guides and planning 
|     ├── user/             # User documentation
|     ├── deployment/       # deployment documentation
|     ├── planning/         # planning documentation
|     ├── install/          # New Install documentation
|     └── migration/        # Migration documentation
├── generic/          # Generic knowledge base 

├── integration/      # Integration guides 
├── languages/        # Language-specific patterns 
├── platforms/        # Platform-specific knowledge 
├── projects/         # Project implementations 
├── redirects/        # Migration redirects 
└── support/          # Tools and workflows 
```

---

## /sima/context/ - BASE MODE CONTEXTS (3 Files)

**Purpose:** Base Mode indexes context files for different operational modes

**Files:**
- context-Master-Index-of-Indexes.md - Master Index of indexes context files
- context-Quick-Index-of-Indexes.md - Quick Index of indexes context files
- context-MODE-SELECTOR.md - Base Context Router - initial starting point to other context routers in SIMA

**Organization:** Indexes for context files

---

## /sima/context/ai/ - AI LOADER FILES (3 Files)

**Purpose:** AI Loader files made by the AI's to be able to load SIMA Context - Only modified by the AI that made it.
**Activation: "Update AI Loaders" - Updates the AI Loaders index file and other indexes as needed

**Files:**
- deepseek-SIMA-Mode-Loader.md - deepseek ai made loader for SIMA
- le_chat-SIMA-Mode-Loader.md - le_chat ai made loader for SIMA
- context-ai-Index.md - Index of ai loader files

**Organization:** Ai Loader Files made by the individual ai's

---

## /sima/context/new/ - NEW MODE CONTEXTS (9 Files)

**Purpose:** Base Mode indexes context files for different operational modes
**Activation: "Create New Project with (Project Name)" - Adds a new project in the projects directory the name specified with (Project name) and then creates base configuration file and then creates all the base index files for each directory.
**Activation: "Create New Platform with (Platform Name)" - Adds a new platform in the platforms directory the name specified with (Platform name) and then creates all the base index files for each directory.
**Activation: "Create New Language with (Language Name)" - Adds a new programming language in the languages directory the name specified with (Language name) and then creates all the base index files for each directory.

**Files:**
- context-new-Context.md - New Mode base Context - Context used by all new modes
- context-new-Project-Context.md - New Mode Project Full Context - Used in the creation of new projects in SIMA
- context-new-Project-Quick-Context.md - New Mode Project Quick Context - Used in the creation of new projects in SIMA
- context-new-Platform-Context.md - New Mode Platform Full Context - Used in the creation of new platforms in SIMA
- context-new-Platform-Quick-Context.md - New Mode Platform Quick Context - Used in the creation of new platforms in SIMA
- context-new-Languages-Context.md - New Mode Platform Full Context - Used in the creation of new programming languages in SIMA
- context-new-Languages-Quick-Context.md - New Mode Platform Quick Context - Used in the creation of new programming languages in SIMA
- context-new-Index.md - Index of context files in this directory
- context-new-MODE-SELECTOR.md - New Mode Context Router - Loads base New Mode COntext then routes to selected mode context files

**Organization:** New Mode context files and index

---

## /sima/context/shared/ - SHARED KNOWLEDGE (7 files)

**Purpose:** Reference knowledge available to all modes

**Files:**
- Custom-Instructions-for-AI-assistant.md - Custom Instruction for AI Assistants to help enable SIMAv4 usage
- Artifact-Standards.md
- Common-Patterns.md
- Encoding-Standards.md
- File-Standards.md
- RED-FLAGS.md
- context-shared-Index.md - Index of shared context files

**Usage:** Referenced by all context modes for consistent standards

---

## /sima/context/general - General Mode CONTEXTS (4 Files)

**Purpose:** General Mode activation and context files for different operational modes
**Activation: "Please Load Context" - Loads general context of all Knowledge in SIMA - Where you go to ask about something in knowledge

**Files:**

- context-General-Mode-Context.md - Full General Mode Context File - For utilizing or researching all SIMA knowledge files.
- context-General-Mode-START-Quick-Context.md - SIMA Learning Mode Quick Context File
- context-general-MODE-SELECTOR.md - General Context router
- context-General-Index.md - Index of General context files

**Organization:** Base general modes

---

## /sima/context/sima - SIMA MODE CONTEXTS (13 Files)

**Purpose:** SIMA Mode activation and context files for different operational modes
**Activation: "Start SIMA Project Mode" - For working on new features or revisions of SIMA
**Activation: "Start SIMA Learning Mode" - For learning new knowledge and importing it into SIMA
**Activation: "Start SIMA EXPORT Mode" - For Exporting a section of knowledge and setting it up in directory order with a export MD, with import prompt inside of it, with all needed ifnformation so it can be able to be imported into another SIMA
**Activation: "Start SIMA IMPORT Mode" - For importing a previously exported section of knowledge using the md file generated at export and integrating it into the existing directory order and updating all nessary indexes and quick references in the new SIMA
**Activation: "Start SIMA Maintenance Mode" - For maintenancing the SIMA system keeping all indexes and references up to date and for adding files in SIMA not listed or removing no longer needed files that are old and outdated or duplicate.

**Files:**
- context-SIMA-BASE-MODE.md - SIMA Base Context File used all other modes
- context-SIMA-PROJECT-MODE.md - SIMA Project Mode Context File - For working on or revising the SIMA System
- context-SIMA-PROJECT-MODE-START-Quick-Context.md - SIMA project Mode Quick Context File - For working on or revising the SIMA System
- context-SIMA-LEARNING-MODE-Context.md - SIMA Learning Mode Full Context File - For learning and integrating new knowledge from external files, not exports from other SIMAs, into the SIMA system looking any recognized patterns for unique addtions
- context-SIMA-LEARNING-MODE-START-Quick-Context.md - SIMA Learning Mode Quick Context File - For learning and integrating new knowledge from external files, not exports from other SIMAs, into the SIMA system looking any recognized patterns for unique addtions
- context-SIMA-EXPORT-MODE-Context.md - SIMA Export Mode Full Context File - Used to export knowledge files so they can be imported into other SIMAs
- context-SIMA-EXPORT-MODE-START-Quick-Context.md - SIMA Export Mode Quick Context File - Used to export knowledge files so they can be imported into other SIMAs
- context-SIMA-IMPORT-MODE-Context.md - SIMA Export Mode Full Context File - Used to import knowledge files from other SIMAs into the current SIMA, with duplication check
- context-SIMA-IMPORT-MODE-START-Quick-Context.md - SIMA Maintenance Mode Quick Context File - Used to import knowledge files from other SIMAs into the current SIMA, with duplication check
- context-SIMA-MAINTENANCE-MODE-Context.md - SIMA Maintenance Mode Full Context File - SIMA Maintenance Mode checks all indexes and references to ensure they are up to date and while doing that checking for missing, extra, or duplicate files for addtion or removal
- context-SIMA-MAINTENANCE-MODE-START-Quick-Context.md - SIMA Export Mode Quick Context File - SIMA Maintenance Mode checks all indexes and references to ensure they are up to date and while doing that checking for missing, extra, or duplicate files for addtion or removal
- context-SIMA-MODE-SELECTOR.md - SIMA Context router - Used to load the base SIMA Context all modes use then the specific context of the mode selected.
- context-SIMA-Index.md - Index of SIMA context files

**Organization:** SIMA modes

---

## /sima/context/debug/ - Debug MODE CONTEXTS

**Purpose:** Debug Mode activation and context files for different operational modes
**Activation: Start Debug Mode for (Project or SIMA)

**Files:**
- context-DEBUG-MODE-Context.md - Base Debug Mode fullContext
- context-DEBUG-MODE-SELECTOR.md - Debug Mode Router to load base context and then the debug mode context of specificed project or SIMA
- context-DEBUG-MODE-START-Quick-Context.md - Base Debug mode quick context
- context-DEBUG-Index.md - Index of base DEBUG context files

**Organization:** Debug modes + index

---

## /sima/context/projects/ - Project MODE CONTEXTS

**Purpose:** Project Mode activation and context files for different operational modes
**Activation: Start Project Mode for (Project or SIMA)

**Files:**
- context-PROJECT-MODE-Context.md - Base project Mode full Context
- context-PROJECT-MODE-SELECTOR.md - Project Mode Router to load base context and then the project mode context of specificed project or SIMA
- context-PROJECT-MODE-START-Quick-Context.md - Base project mode quick context
- context-PROJECT-Index.md - Index of base project context files

**Organization:** Project mode contexts + index

---

## /sima/context/workflows/ - SIMA Workflows that are not geared towards any specific project or platform

---



## /sima/docs/ - Documentation File Indexes (2 files)

**Purpose:** Documentation, guides, planning documents index files

**Key Files:**
- docs-Master-Index-of-Indexes.md - Master docs Index of indexes context files
- docs-Quick-Index-of-Indexes.md - Quick docs Index of indexes context files

---

## /sima/docs/user - USER GUIDES (6 files)

**Purpose:** User Documentation and guides

**Key Files:**
- SIMAv4.2.2-User-Guide.md - Expanded how to use SIMA
- SIMAv4.2.2-Quick-Start-Guide.md - Brief how to use SIMA
- SIMAv4.2.2-File-Server-URLs-Guide.md - Why and how to update the file server URLs file to enable fresh content always whether by script of by text editor
- SIMAv4.2.2-Mode-Comparison-Guide.md - Expanded explanation of all the different Modes of SIMA
- SIMAv4.2.2-Mode-Comparison-Quick-Guide.md - Brief Explanation of all the different Modes of SIMA
- docs-user-Index.md - Index of user documentation files

---

## /sima/docs/developer - USER GUIDES (2 files)

**Purpose:** Developement documents

**Key Files:**
- SIMAv4-Developer-Guide.md - How to expand SIMA with more knowledge, new features - To integrate it into SIMA seamlessly
- docs-developer-Index.md - Index of developer documentation files

---

## /sima/generic/ - GENERIC KNOWLEDGE (Varies Files)

**Purpose:** Universal generic patterns, wisdom, and knowledge applicable across all contexts with no direct programming language, platform, or project only knowledge. Just generic knowledge that is good to be referenced
**Example: "Continiuosly working non-stop with minimal chatter get work done alot faster while using less tokens." -- This is generic in general and not specific to any other parts of the knowledge.
**Note: Each directory gets it own index file and router context file for navigation in progressive order, a Domain navigation file would not include a sub-domain name in it. But a sub-domain navigation file would include the domain. A catagory would have the domain and then subdomain and then catagory in the file name.
** Index files naming: generic-(Sub-Domain)-(Catagory)-(Router or Index).md
** Each Catagory File Naming: generic-(Catagory)-###-(Very Brief Description).md

**Structure:**
```
/generic/
├── anti-patterns/   # What NOT to do
├── core/            # Core work pratices that work very good
├── decisions/       # Generic decisions about what not to do and what to do to do a better job overall
├── lessons/         # Lessons learned in general about good practices and bad practices
├── workflows/       # Generic workflows to increase productivity and performance of SIMA
└── specifications/  # Basic Standards that apply to almost everything buy are no specific to any 1 thing
```

### Anti-Patterns (/generic/anti-patterns/)

**Categories:**
- critical/ - generic critical mistakes
- documentation/ - generic Documentation errors
- error-handling/ - generic Error handling mistakes
- implementation/ - generic Implementation issues
- import/ - generic Import problems
- export/ - generic Export problems
- performance/ - generic Performance issues
- process/ - generic Process failures
- quality/ - generic Quality issues
- security/ - generic Security vulnerabilities
- testing/ - Testing mistakes

### Core Work Practices (/generic/core/)

**Files:**
- Just Index and router initially

### Decisions (/generic/decisions/)

**Categories:**
- deployment/ - Deployment decisions
- error-handling/ - Error handling decisions
- feature-addition/ - Feature decisions
- import/ - Import decisions
- export/ - Export decisions
- meta/ - Meta decisions
- operational/ - Operational decisions
- refactoring/ - Refactoring decisions
- technical/ - Technical decisions
- testing/ - Testing decisions

### Lessons (/entries/lessons/ - 71 files)

**Categories:**
- bugs/ - SIMA Bug reports and fixes
- documentation/ - SIMA Documentation lessons
- evolution/ - SIMA System evolution
- learning/ - SIMA Learning meta-lessons
- operations/ - SIMA Operational lessons
- optimization/ - SIMA Optimization lessons
- performance/SIMA - Performance lessons
- wisdom/ - SIMA Profound insights


### Specifications (/generic/specifications/ - 11 files)

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

## /sima/languages/ - LANGUAGE PATTERNS (92 files)
**Purpose:** Language-specific implementations and patterns
**Note: Each directory gets it own index file and router context file for navigation in progressive order, a Domain navigation file would not include a sub-domain name in it. But a sub-domain navigation file would include the domain. A catagory would have the domain and then subdomain and then catagory in the file name.
** Index files naming: generic-(Sub-Domain)-(Catagory)-(Router or Index).md
** Each Catagory File Naming: generic-(Catagory)-###-(Very Brief Description).md

**Structure:**
```
/languages/
└── (Language)/
    ├── anti-patterns/
    ├── decisions/
    ├── lessons/
    ├── wisdom/
    ├── workflows/       # (Language) workflows to increase productivity and performance
    └── frameworks/ - Frameworks are also archtectures. All framework information is kept under framework and not spread outside of its directory.
          └── (framework name)/
                       ├── anti-patterns/
                       ├── decisions/
                       ├── lessons/
                       ├── wisdom/
                       └── (other directory name that goes with framework)

---

## /sima/platforms/ - PLATFORM KNOWLEDGE (59 files)

**Purpose:** Platform-specific implementations

**Structure:**
```
/platforms/
 └── (platform name)/
    ├── (sub-platform underneathe main platform domain if needed)/
    ├── (sub-platform underneathe main platform domain if needed)/
    └── (sub-platform underneathe main platform domain if needed)/
 └── (platform name with no sub-platforms)/

```

### Single Platform with no sub-platform and Sub-platform directory structure

**Structure:**
```
/(Single Platform)/
├── anti-patterns/ 
├── core/ 
├── decisions/ 
├── lessons/
├── workflows/       # (Single Platform) workflows to increase productivity and performance
├── (Single Platform)-Router.md
└── (Single Platform)-Index.md
```

**Structure:**
```
/(Platform)/
   ├── (Platform)-Router.md
   ├── (Platform)-Index.md
   └── (Sub-platform)/
          ├── anti-patterns/ 
          ├── core/ 
          ├── decisions/ 
          ├── lessons/
          ├── workflows/       # (Sub-Platform) workflows to increase productivity and performance
          ├── (Platform)-(Sub-Platform)-Router.md
          └── (Platform)-(Sub-Platform)-Index.md
```

## /sima/projects/ - PROJECT IMPLEMENTATIONS 

**Purpose:** Project-specific knowledge and configurations
**Note: Each directory gets it own index file and router context file for navigation in progressive order, a Domain navigation file would not include a sub-domain name in it. But a sub-domain navigation file would include the domain. A catagory would have the domain and then subdomain and then catagory in the file name.
** Index files naming: generic-(Sub-Domain)-(Catagory)-(Router or Index).md
** Each Catagory File Naming: generic-(Catagory)-###-(Very Brief Description).md


**Structure:**
```
/projects/
├── (Project Name)/
         ├── config/         # (Project)-knowledge-config.yaml, project_config.md, and Context files for project
         ├── anti-patterns/  # Project-specific anti-patterns
         ├── core/           # Project-specific core frameworks or architectures
         ├── decisions/      # Project-specific decisions 
         ├── lessons/        # Project-specific lessons
         ├── workflows/      # Project-specific workflows to increase productivity and performance
         ├── (Project)-Architecture-Integration-Patterns.md
         ├── (Project)-Architecture-Overview.md
         └── README.md
```

## /sima/templates/ - Templates

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
- /Master-Index-of-Indexes.md
- /SIMA-Navigation-Hub.md
- /SIMA-Quick-Reference-Card.md
- /context/context-Master-Index-of-Indexes.md
- /generic/generic-Master-Index-of-Indexes.md
- /languages/languages-Master-Index-of-Indexes.md
- /platforms/platforms-Master-Index-of-Indexes.md
- /projects/projects-Master-Index-of-Indexes.md
- /support/support-Master-Index-of-Indexes.md
- /template/templates-Master-Index-of-Indexes.md

**Category Indexes:**
- (Domain)-(Sub-Domain)-Anti-Patterns-Master-Index.md
- (Domain)-(Sub-Domain)-Decisions-Master-Index.md
- (Domain)-(Sub-Domain)-Lessons-Master-Index.md
- (Domain)-(Sub-Domain)-Platforms-Master-Index.md

**Cross-Reference Tools:**
- Master-Cross-Reference-Matrix.md
- Index-Verification-Checklist.md

---

## KEY ORGANIZATIONAL PRINCIPLES

### 1. Domain Separation
```
Generic (generic/) 
  ↓
Platform (platforms/)
  ↓
Language (languages/)
  ↓
Project (projects/)
```

### 2. File Standards
- Markdown format (.md)
- ≤400 lines per file
- UTF-8 encoding
- Headers required
- REF-ID cross-references

### 3. Naming Conventions
- (Domain)-(Sub-domain)-TYPE-##-(Description).md for entries (Domain-subdomain-LESS-01-knowledge_description, Domain-subdomain-DEC-01-knowledge_description, Domain-subdomain-AP-01-knowledge_description, etc.)
- Domain-Index.md for indexes
- Domain-subdomain-Index.md for indexes
- Domain-subdomain-catagory-Index.md for indexes
- Descriptive names for documentation

### 4. Index Hierarchy
- Master indexes at top level
- Domain indexes at domain level
- Sub-Domain indexes at sub-domain level
- Category indexes per directory
- Cross-reference matrices
- Quick indexes for common access

---

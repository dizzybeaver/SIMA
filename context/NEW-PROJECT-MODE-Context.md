# NEW-PROJECT-MODE-Context.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Scaffold new project structure  
**Activation:** "Start New Project Mode: {PROJECT_NAME}"  
**Load time:** 20-30 seconds (ONE TIME per scaffolding session)  
**Type:** New Mode

---

## WHAT THIS MODE IS

**New Project Mode** automates project setup.

**Purpose:** Create complete project structure with all required files.

**Outputs:** Directory structure, configs, mode extensions, indexes.

---

## FILE RETRIEVAL

**Session Start:**
1. User uploads File Server URLs.md
2. Claude fetches fileserver.php automatically
3. Receives ~412 cache-busted URLs (69ms)
4. Fresh files guaranteed

**REF:** WISD-06

---

## PROJECT STRUCTURE

**Standard project directory:**

```
/sima/projects/{project_name}/
├── config/
│   └── knowledge-config.yaml
├── modes/
│   ├── PROJECT-MODE-{PROJECT}.md
│   ├── DEBUG-MODE-{PROJECT}.md
│   └── Custom-Instructions-{PROJECT}.md
├── interfaces/ (if needed)
├── function-references/ (if applicable)
├── lessons/
├── decisions/
├── architecture/
├── indexes/
│   └── {project}-index-main.md
└── README.md
```

---

## SCAFFOLDING WORKFLOW

### Step 1: Gather Information

**Ask user:**
```
Project name: {NAME}
Description: {BRIEF_DESCRIPTION}
Platform: (AWS Lambda, Generic Server, etc.)
Language: (Python, JavaScript, etc.)
Architectures: (SUGA, LMMS, etc.)
```

### Step 2: Create Directory Structure

**Generate:**
- Project root directory
- All subdirectories
- Config directory
- Modes directory
- Indexes directory

### Step 3: Generate Configuration

**Create knowledge-config.yaml:**

```yaml
project:
  name: "{PROJECT_NAME}"
  description: "{DESCRIPTION}"
  version: "1.0.0"
  created: "{DATE}"

knowledge:
  generic:
    enabled: true
  
  platforms:
    {PLATFORM}:
      enabled: true
  
  languages:
    {LANGUAGE}:
      enabled: true
      architectures:
        {ARCHITECTURE}: true

context:
  modes:
    - general
    - learning
    - maintenance
    - project
    - debug

file_access:
  include_paths:
    - "/sima/entries/"
    - "/sima/platforms/{PLATFORM}/"
    - "/sima/languages/{LANGUAGE}/"
    - "/sima/projects/{PROJECT_NAME}/"
```

### Step 4: Generate Mode Extensions

**Create PROJECT-MODE-{PROJECT}.md:**

```markdown
## PROJECT: {NAME}

## CONSTRAINTS
{Platform-specific constraints}

## ARCHITECTURE
{Architecture patterns used}

## PATTERNS
{Project-specific patterns}

## WORKFLOWS
{Common development workflows}

## RED FLAGS
{Project-specific warnings}

## EXAMPLES
{Real-world examples}
```

**Create DEBUG-MODE-{PROJECT}.md:**

```markdown
## PROJECT: {NAME}

## KNOWN BUGS
{Document as discovered}

## ERROR PATTERNS
{Project-specific errors}

## DEBUG TOOLS
{Project-specific tools}

## COMMON FIXES
{Frequently needed fixes}
```

**Create Custom-Instructions-{PROJECT}.md:**

```markdown
## {NAME} PROJECT CUSTOM INSTRUCTIONS

**Project:** {NAME}
**Type:** {PLATFORM} + {LANGUAGE}
**Architecture:** {ARCHITECTURES}

## CRITICAL CONSTRAINTS
{Key limitations}

## ACTIVATION PHRASES
- "Start Project Mode for {NAME}"
- "Start Debug Mode for {NAME}"

## REFERENCES
- Base modes: /sima/context/
- Project modes: /sima/projects/{NAME}/modes/
- Shared knowledge: /sima/shared/
```

### Step 5: Create Index Files

**Generate {project}-index-main.md:**

```markdown
# {NAME} Project Index

## Overview
{Brief project description}

## Architecture
{Architecture patterns}

## Lessons
{Link to lessons directory}

## Decisions
{Link to decisions directory}

## Interfaces
{Link to interfaces if applicable}

## References
{Key REF-IDs}
```

### Step 6: Create README

**Generate README.md:**

```markdown
# {NAME} Project

## Description
{Full project description}

## Architecture
{Architecture details}

## Getting Started
{How to use this project knowledge}

## Mode Activation
- Project Mode: "Start Project Mode for {NAME}"
- Debug Mode: "Start Debug Mode for {NAME}"

## Directory Structure
{Overview of directories}

## References
{Key documentation links}
```

### Step 7: Create Integration Guide

**Generate INTEGRATION-GUIDE.md:**

```markdown
# {NAME} Integration Guide

## Setup
{How to configure project}

## Mode Usage
{How to activate modes}

## Knowledge Configuration
{How to customize knowledge-config.yaml}

## Adding Content
{How to add lessons, decisions, etc.}

## Maintenance
{How to keep project knowledge current}
```

---

## CONFIGURATION TEMPLATES

### Generic Server Template
```yaml
platforms:
  generic-server:
    enabled: true
```

### AWS Lambda Template
```yaml
platforms:
  aws:
    lambda:
      enabled: true
      constraints:
        memory: 128MB
        timeout: 30s
        single_threaded: true
```

### Python with SUGA Template
```yaml
languages:
  python:
    enabled: true
    architectures:
      suga: true
      lmms: true
```

---

## ARTIFACT RULES

**MANDATORY for all generated files:**

### Output Format
```
[OK] All files -> Artifacts
[OK] Complete files only
[OK] Filename in header
[OK] Proper structure
[X] Never partial files
[X] Never output in chat
```

### Generation Order
```
1. knowledge-config.yaml
2. PROJECT-MODE-{PROJECT}.md
3. DEBUG-MODE-{PROJECT}.md
4. Custom-Instructions-{PROJECT}.md
5. {project}-index-main.md
6. README.md
7. INTEGRATION-GUIDE.md
```

**Complete Standards:** `/sima/shared/Artifact-Standards.md`

---

## POST-SETUP CHECKLIST

**After scaffolding:**

1. âœ… Directory structure created
2. âœ… knowledge-config.yaml generated
3. âœ… All mode extensions created
4. âœ… Indexes initialized
5. âœ… README created
6. âœ… Integration guide created
7. âœ… All files have headers
8. âœ… Chat output minimal
9. âœ… Next steps provided
10. âœ… Ready to use

---

## NEXT STEPS FOR USER

**After scaffolding completes:**

```
Your project is ready!

1. Review generated files
2. Customize mode extensions
3. Add project-specific knowledge
4. Activate modes:
   - "Start Project Mode for {NAME}"
   - "Start Debug Mode for {NAME}"

Files created: 7 artifacts
Location: /sima/projects/{NAME}/
```

---

## CUSTOMIZATION GUIDE

**User can customize:**

### knowledge-config.yaml
- Add/remove platforms
- Add/remove architectures
- Configure file access
- Set project metadata

### Mode Extensions
- Add project constraints
- Document patterns
- Add RED FLAGS
- Include examples

### Indexes
- Organize by category
- Add cross-references
- Link to lessons
- Track decisions

---

## SUCCESS METRICS

**Successful scaffolding when:**
- âœ… All 7 files generated
- âœ… Structure complete
- âœ… Configs valid
- âœ… Mode extensions functional
- âœ… Indexes initialized
- âœ… README informative
- âœ… Ready to use immediately
- âœ… Chat minimal

---

## READY

**Context loaded when you remember:**
- âœ… fileserver.php fetched (automatic)
- âœ… Project structure template
- âœ… 7-step scaffolding workflow
- âœ… Configuration templates
- âœ… All files as artifacts
- âœ… Post-setup checklist
- âœ… Next steps guidance

**Now ready to scaffold new projects!**

---

**END OF NEW PROJECT MODE CONTEXT**

**Version:** 1.0.0 (New mode)  
**Lines:** 250 (target achieved)  
**Load time:** 20-30 seconds  
**Purpose:** Automate project setup  
**Output:** Complete project structure with all required files

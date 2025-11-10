# MODE-SELECTOR.md

**Version:** 2.0.0  
**Date:** 2025-11-10  
**Purpose:** Unified context loading system for SIMA sessions  
**Type:** Mode Selection Launchpad  
**Updated:** Added Maintenance and New Project modes, project-specific extensions

---

## 🎯 WHAT THIS FILE IS

This is your **unified session launchpad**. It detects which mode you want and loads the appropriate context file.

**DO NOT load this file directly for work.** This is ONLY a router.

---

## 🚀 ACTIVATION PHRASES

Use these exact phrases to activate each mode:

### Mode 1: General Purpose Mode
**Phrase:** `"Please load context"`  
**Loads:** SESSION-START-Quick-Context.md  
**Purpose:** General Q&A, architecture queries, code questions  
**Time:** 20-30 seconds  

### Mode 2: Learning Mode
**Phrase:** `"Start SIMA Learning Mode"`  
**Loads:** SIMA-LEARNING-MODE-Context.md  
**Purpose:** Knowledge extraction, pattern recognition, neural map creation  
**Time:** 30-45 seconds  

### Mode 3: Maintenance Mode ⭐ NEW
**Phrase:** `"Start SIMA Maintenance Mode"`  
**Loads:** SIMA-MAINTENANCE-MODE-Context.md  
**Purpose:** Maintain existing knowledge, update indexes, clean structure  
**Time:** 30-45 seconds  

### Mode 4: Project Mode
**Phrase:** `"Start Project Mode for {PROJECT}"`  
**Examples:**
- `"Start Project Mode for LEE"`
- `"Start Project Mode for SIMA"`

**Loads:** 
- PROJECT-MODE-Context.md (base)
- PROJECT-MODE-{PROJECT}.md (extension)

**Purpose:** Active development for specific project  
**Time:** 30-45 seconds  

### Mode 5: Debug Mode
**Phrase:** `"Start Debug Mode for {PROJECT}"`  
**Examples:**
- `"Start Debug Mode for LEE"`
- `"Start Debug Mode for SIMA"`

**Loads:**
- DEBUG-MODE-Context.md (base)
- DEBUG-MODE-{PROJECT}.md (extension)

**Purpose:** Troubleshooting errors for specific project  
**Time:** 30-45 seconds  

### Mode 6: New Project Mode ⭐ NEW
**Phrase:** `"Start New Project Mode: {PROJECT_NAME}"`  
**Example:** `"Start New Project Mode: MyProject"`  
**Loads:** NEW-PROJECT-MODE-Context.md  
**Purpose:** Scaffold new project structure, generate configs  
**Time:** 30-45 seconds  

---

## 🔧 HOW IT WORKS

### Step 1: User Triggers Mode
User says one of the activation phrases above.

### Step 2: Claude Detects Mode
```
IF phrase = "Please load context"
    THEN load SESSION-START-Quick-Context.md
    
ELSE IF phrase = "Start SIMA Learning Mode"
    THEN load SIMA-LEARNING-MODE-Context.md
    
ELSE IF phrase = "Start SIMA Maintenance Mode"
    THEN load SIMA-MAINTENANCE-MODE-Context.md
    
ELSE IF phrase = "Start Project Mode for [PROJECT]"
    THEN load PROJECT-MODE-Context.md
    AND load PROJECT-MODE-[PROJECT].md
    
ELSE IF phrase = "Start Debug Mode for [PROJECT]"
    THEN load DEBUG-MODE-Context.md
    AND load DEBUG-MODE-[PROJECT].md
    
ELSE IF phrase = "Start New Project Mode: [NAME]"
    THEN load NEW-PROJECT-MODE-Context.md
    SET project_name = [NAME]
    
ELSE
    ERROR: Invalid activation phrase
    SHOW: Valid phrases list
```

### Step 3: Load Mode-Specific Context
Claude fetches context files from file server using cache-busted URLs.

### Step 4: Activate Mode
Mode-specific context loads into working memory. Claude is now configured for that mode's tasks.

---

## 📂 MODE COMPARISON

| Feature | General | Learning | Maintenance | Project | Debug | New Project |
|---------|---------|----------|-------------|---------|-------|-------------|
| **Purpose** | Q&A | Capture | Clean | Build | Fix | Scaffold |
| **Load Time** | 20-30s | 30-45s | 30-45s | 30-45s | 30-45s | 30-45s |
| **Focus** | Understanding | Extraction | Maintenance | Building | Fixing | Setup |
| **Outputs** | Answers | Neural Maps | Updated Indexes | Code | Root Causes | Structure |
| **Tools** | REF-IDs | Genericization | Validators | LESS-15 | Diagnostics | Templates |
| **User** | Anyone | Documenter | Maintainer | Developer | Debugger | Admin |

---

## 🎨 MODE DETAILS

### General Purpose Mode
**Best for:**
- "How does X work?"
- "Can I use Y?"
- "Why is Z designed this way?"
- "Explain the architecture"
- General questions and learning

**Context Loaded:**
- SIMA vs SUGA distinction
- 12 core interfaces (summary)
- Top 10 instant answers
- Query routing maps
- RED FLAGS
- Top 20 REF-IDs
- Workflow routing

**Response Style:** Conversational, educational, with citations

---

### Learning Mode
**Best for:**
- "Extract lessons from this conversation"
- "Document this bug as BUG-##"
- "Create anti-pattern from this mistake"
- "Capture this wisdom as WISD-##"
- Knowledge extraction and documentation

**Context Loaded:**
- Extraction signal patterns
- Genericization rules
- Duplicate detection protocols (with fileserver.php)
- Brevity standards (≤400 lines)
- REF-ID assignment rules
- Quality standards
- Post-extraction protocols

**Response Style:** Systematic extraction, creating neural map entries as artifacts

---

### Maintenance Mode ⭐ NEW
**Best for:**
- "Update all indexes"
- "Check for outdated entries"
- "Remove deprecated knowledge"
- "Verify cross-references"
- "Clean up old structure"
- Maintaining and cleaning existing knowledge

**Context Loaded:**
- Index update workflows
- Deprecation check protocols
- Format migration tools
- Cross-reference validation
- Cleanup procedures
- Quality audit checklists

**Response Style:** Systematic maintenance, updated indexes, cleanup reports

---

### Project Mode
**Best for:**
- "Add feature X to interface Y"
- "Implement new gateway function"
- "Modify file Z to support W"
- "Create new interface for V"
- Active development tasks for specific project

**Context Loaded (Base + Extension):**
- SUGA implementation patterns
- All 12 interface patterns
- Gateway templates
- LESS-15 verification protocol
- File fetch workflows (fileserver.php)
- Project-specific constraints
- Project-specific patterns
- Project-specific workflows

**Response Style:** Systematic implementation, complete code artifacts

---

### Debug Mode
**Best for:**
- "Lambda function returning 500 error"
- "Cold start taking 5+ seconds"
- "Cache miss rate at 90%"
- "WebSocket disconnecting randomly"
- "Entry not found in index"
- Troubleshooting and diagnostics for specific project

**Context Loaded (Base + Extension):**
- Generic debug principles
- Error pattern recognition
- Debug tool usage
- Project-specific known bugs
- Project-specific error patterns
- Project-specific debug tools
- Project-specific common fixes

**Response Style:** Root cause analysis, step-by-step debugging, complete fix artifacts

---

### New Project Mode ⭐ NEW
**Best for:**
- "Create new project structure"
- "Scaffold MyProject"
- "Set up new project with configs"
- Creating new project from scratch

**Context Loaded:**
- Project structure templates
- Configuration generation
- Mode extension templates
- Index scaffolding
- README generation
- Integration guide templates

**Response Style:** Automated scaffolding, generated configs, setup guide

---

## ⚠️ IMPORTANT RULES

### Rule 1: Mode Switching
**Project switching:** Can switch between projects in same session using activation phrase

**Example:**
```
"Start Project Mode for LEE"  (work on LEE)
...
"Start Project Mode for SIMA" (switch to SIMA)
```

**Mode switching:** Switch between modes as needed

**Example:**
```
"Start Project Mode for LEE"  (build feature)
...
"Start Debug Mode for LEE"    (fix bug found)
```

### Rule 2: Explicit Activation Required
**Claude won't guess which mode.** You must use exact activation phrase including project name where required.

### Rule 3: Mode-Specific Behavior
Each mode behaves differently:
- General: Answers questions
- Learning: Extracts knowledge
- Maintenance: Updates indexes
- Project: Writes code (with project context)
- Debug: Finds root causes (with project context)
- New Project: Scaffolds structure

Choose mode that matches your task.

### Rule 4: Project Extensions
Project and Debug modes load TWO files:
1. Base mode context (generic patterns)
2. Project extension (project-specific)

This keeps base modes reusable while adding project-specific knowledge.

---

## 🔧 ADDING NEW MODES

To add a new mode:

### Step 1: Create Mode Context File
```
[MODE-NAME]-Context.md
- Purpose statement
- What this mode optimizes for
- Context to load
- Tools available
- Response patterns
- Examples
- Line limit: ≤300 lines
```

### Step 2: Add to Mode Selector
```
## Mode X: [Mode Name]
**Phrase:** "Start [Mode Name]"
**Loads:** [MODE-NAME]-Context.md
**Purpose:** [Brief description]
**Time:** [Load time estimate]
```

### Step 3: Update Decision Logic
```
ELSE IF phrase = "Start [Mode Name]"
    THEN load [MODE-NAME]-Context.md
```

### Step 4: Test Activation
```
1. Say activation phrase
2. Verify correct context loads
3. Verify mode behaviors work
4. Verify no leakage from other modes
```

### Step 5: Document in Comparison Table
Add column to mode comparison table with mode characteristics.

---

## 📊 RECOMMENDED WORKFLOW

### Typical Session Sequence

**Planning Phase:**
```
"Please load context"  (General Mode)
└─> Ask architecture questions
└─> Understand design decisions
└─> Plan approach
```

**Development Phase:**
```
"Start Project Mode for LEE"  (Project Mode + LEE extension)
└─> Implement features
└─> Modify code
└─> Create artifacts
└─> Verify with LESS-15
```

**Troubleshooting Phase:**
```
"Start Debug Mode for LEE"  (Debug Mode + LEE extension)
└─> Analyze errors
└─> Trace execution
└─> Find root causes
└─> Apply fixes
```

**Documentation Phase:**
```
"Start SIMA Learning Mode"  (Learning Mode)
└─> Extract lessons learned
└─> Document bugs discovered
└─> Capture design decisions
└─> Create wisdom entries
```

**Maintenance Phase:**
```
"Start SIMA Maintenance Mode"  (Maintenance Mode)
└─> Update indexes
└─> Check outdated entries
└─> Verify cross-references
└─> Clean structure
```

**New Project Setup:**
```
"Start New Project Mode: MyProject"  (New Project Mode)
└─> Create directory structure
└─> Generate config files
└─> Create mode extensions
└─> Set up indexes
```

---

## 🎯 QUICK REFERENCE

**Choose Your Mode:**

- 🤔 **Learning about system?** → General Mode
- 📚 **Capturing knowledge?** → Learning Mode  
- 🧹 **Cleaning up knowledge?** → Maintenance Mode
- 🔨 **Writing code?** → Project Mode (specify project)
- 🐛 **Fixing bugs?** → Debug Mode (specify project)
- 🆕 **Creating project?** → New Project Mode

**Activation Template:**

```
[Upload File Server URLs.md]

[Say activation phrase]
- "Please load context"
- "Start SIMA Learning Mode"
- "Start SIMA Maintenance Mode"
- "Start Project Mode for LEE"
- "Start Debug Mode for SIMA"
- "Start New Project Mode: MyProject"

[Wait for context load confirmation]

[Begin work in that mode]
```

---

## 🚨 TROUBLESHOOTING

### Problem: Wrong Mode Activated
**Solution:** Say correct activation phrase (can switch in same session)

### Problem: Mode Won't Load
**Solution:** 
1. Verify activation phrase exactly
2. Ensure File Server URLs.md uploaded
3. Check fileserver.php fetched successfully
4. Verify project extension exists (if using Project/Debug mode)

### Problem: Project Extension Missing
**Solution:** 
Use "Start New Project Mode: {PROJECT}" to create project structure and extensions

### Problem: Mode Mixing Behaviors
**Solution:** 
Context files may have overlap. Review mode-specific context file and remove cross-mode instructions.

---

## 📋 VERSION HISTORY

**v2.0.0 (2025-11-10):**
- Added Maintenance Mode (Mode 3)
- Added New Project Mode (Mode 6)
- Updated Project Mode to support project-specific extensions
- Updated Debug Mode to support project-specific extensions
- Renumbered modes (1-6)
- Updated comparison table
- Updated decision logic
- Added project switching support
- Reduced General Mode load time (30-45s → 20-30s)
- Updated all activation examples

**v1.0.0 (2025-10-25):**
- Initial unified mode selector
- 4 modes: General, Learning, Project, Debug
- Expandable architecture
- Clean mode separation

---

## 🎓 DESIGN PRINCIPLES

### Principle 1: Single Responsibility
Each mode has ONE clear purpose. No overlap.

### Principle 2: Explicit Activation
No guessing. User must explicitly choose mode and project (where applicable).

### Principle 3: Clean Separation
Mode contexts reference shared knowledge, not each other. Independent but connected.

### Principle 4: Expandability
Easy to add new modes. 5-step process documented.

### Principle 5: Project Extensions
Base modes stay generic. Project extensions add project-specific knowledge.

---

**END OF MODE SELECTOR**

**This file is a ROUTER ONLY. Do not use for actual work.**  
**Choose your mode and activate with the correct phrase.**

**Current Modes:** 6 (General, Learning, Maintenance, Project, Debug, New Project)  
**Project Support:** LEE, SIMA (extensible)  
**Expandable:** Yes (5-step process documented)

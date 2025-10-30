# MODE-SELECTOR.md

**Version:** 1.0.0  
**Date:** 2025-10-25  
**Purpose:** Unified context loading system for SUGA-ISP sessions  
**Type:** Mode Selection Launchpad

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
**Time:** 30-45 seconds  

### Mode 2: Learning Mode
**Phrase:** `"Start SIMA Learning Mode"`  
**Loads:** SIMA-LEARNING-SESSION-START-Quick-Context.md  
**Purpose:** Knowledge extraction, pattern recognition, neural map creation  
**Time:** 45-60 seconds  

### Mode 3: Project Mode ⭐ NEW
**Phrase:** `"Start Project Work Mode"`  
**Loads:** PROJECT-MODE-Context.md  
**Purpose:** Active development, feature implementation, code modifications  
**Time:** 30-45 seconds  

### Mode 4: Debug Mode ⭐ NEW
**Phrase:** `"Start Debug Mode"`  
**Loads:** DEBUG-MODE-Context.md  
**Purpose:** Troubleshooting errors, tracing issues, diagnostic analysis  
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
    THEN load SIMA-LEARNING-SESSION-START-Quick-Context.md
    
ELSE IF phrase = "Start Project Work Mode"
    THEN load PROJECT-MODE-Context.md
    
ELSE IF phrase = "Start Debug Mode"
    THEN load DEBUG-MODE-Context.md
    
ELSE
    ERROR: Invalid activation phrase
    SHOW: Valid phrases list
```

### Step 3: Load Mode-Specific Context
Claude searches project knowledge OR fetches from file server using the mode's context file.

### Step 4: Activate Mode
Mode-specific context loads into working memory. Claude is now configured for that mode's tasks.

---

## 📂 MODE COMPARISON

| Feature | General | Learning | Project | Debug |
|---------|---------|----------|---------|-------|
| **Purpose** | Q&A, Guidance | Knowledge Capture | Active Coding | Troubleshooting |
| **Load Time** | 30-45s | 45-60s | 30-45s | 30-45s |
| **Focus** | Understanding | Extraction | Building | Fixing |
| **Outputs** | Answers, Guidance | Neural Map Entries | Code, Artifacts | Root Causes, Fixes |
| **Tools** | Workflows, REF-IDs | Genericization, Deduplication | LESS-15, File Fetch | Error Traces, Logs |
| **Typical User** | Anyone | Documenter | Developer | Debugger |

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
- SIMA architecture patterns
- 12 core interfaces
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
- 10+ extraction signal patterns
- Genericization rules
- Duplicate detection protocols
- Brevity standards
- REF-ID assignment rules
- Quality standards
- Post-extraction protocols

**Response Style:** Systematic extraction, creating neural map entries

---

### Project Mode ⭐ NEW
**Best for:**
- "Add feature X to interface Y"
- "Implement new gateway function"
- "Modify file Z to support W"
- "Create new interface for V"
- Active development tasks

**Context Loaded:**
- Complete SIMA implementation guide
- All 12 interface patterns with templates
- Gateway implementation patterns
- LESS-15 verification protocol (detailed)
- File fetch workflows
- Code generation templates
- Testing patterns

**Response Style:** Systematic implementation, complete code artifacts

---

### Debug Mode ⭐ NEW
**Best for:**
- "Lambda function returning 500 error"
- "Cold start taking 5+ seconds"
- "Cache miss rate at 90%"
- "WebSocket disconnecting randomly"
- Troubleshooting and diagnostics

**Context Loaded:**
- Known bugs (BUG-01 to BUG-04)
- Error patterns (ERR-01 to ERR-03)
- Tracing pathways (TRACE-01, TRACE-02)
- Debug tools (debug_*.py files)
- Performance patterns
- Common failure modes
- Diagnostic workflows

**Response Style:** Root cause analysis, step-by-step debugging

---

## ⚠️ IMPORTANT RULES

### Rule 1: One Mode Per Session
**Don't mix modes in a single session.** Each mode has different context and focus.

If you need to switch:
1. End current session
2. Start new session
3. Activate different mode

### Rule 2: Explicit Activation Required
**Claude won't guess which mode.** You must use exact activation phrase.

### Rule 3: Mode-Specific Behavior
Each mode behaves differently:
- General: Answers questions
- Learning: Extracts knowledge
- Project: Writes code
- Debug: Finds root causes

Choose mode that matches your task.

### Rule 4: No Mode Leakage
Learning Mode should NOT activate General Mode behaviors.
Debug Mode should NOT extract lessons automatically.
Each mode stays in its lane.

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
Add row to mode comparison table with mode characteristics.

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
"Start Project Work Mode"  (Project Mode)
└─> Implement features
└─> Modify code
└─> Create artifacts
└─> Verify with LESS-15
```

**Troubleshooting Phase:**
```
"Start Debug Mode"  (Debug Mode)
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

---

## 🎯 QUICK REFERENCE

**Choose Your Mode:**

- 🤔 **Learning about system?** → General Mode
- 📚 **Capturing knowledge?** → Learning Mode  
- 🔨 **Writing code?** → Project Mode
- 🐛 **Fixing bugs?** → Debug Mode

**Activation Template:**

```
[Upload file server config or File Server URLs.md]

[Say activation phrase]
- "Please load context"
- "Start SIMA Learning Mode"
- "Start Project Work Mode"
- "Start Debug Mode"

[Wait for context load confirmation]

[Begin work in that mode]
```

---

## 🚨 TROUBLESHOOTING

### Problem: Wrong Mode Activated
**Solution:** End session, start new session with correct phrase

### Problem: Mode Won't Load
**Solution:** 
1. Verify activation phrase exactly
2. Ensure file server URLs available
3. Check project knowledge has mode context file

### Problem: Mode Mixing Behaviors
**Solution:** Context files may have overlap. Review mode-specific context file and remove cross-mode instructions.

### Problem: New Mode Not Working
**Solution:** Verify all 5 steps in "Adding New Modes" completed

---

## 📋 VERSION HISTORY

**v1.0.0 (2025-10-25):**
- Initial unified mode selector
- 4 modes: General, Learning, Project, Debug
- Expandable architecture
- Clean mode separation
- No mode leakage

---

## 🎓 DESIGN PRINCIPLES

### Principle 1: Single Responsibility
Each mode has ONE clear purpose. No overlap.

### Principle 2: Explicit Activation
No guessing. User must explicitly choose mode.

### Principle 3: Clean Separation
Mode contexts don't reference each other. Independent.

### Principle 4: Expandability
Easy to add new modes. 5-step process documented.

### Principle 5: URL Flexibility
Works with any file server URL via configuration.

---

**END OF MODE SELECTOR**

**This file is a ROUTER ONLY. Do not use for actual work.**  
**Choose your mode and activate with the correct phrase.**

**Current Modes:** 4 (General, Learning, Project, Debug)  
**Expandable:** Yes (5-step process documented)  
**URL System:** Configurable (see SERVER-CONFIG.md)

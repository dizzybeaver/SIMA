# Custom-Instructions-for-AI-assistant.md

**Version:** 4.2.2-blank  
**Date:** 2025-11-10  
**Purpose:** Core instructions for AI assistant working with SIMA  
**Installation:** Blank SIMA (no knowledge content)

---

## ðŸŽ¯ SIMA OVERVIEW

**SIMA** (Structured Intelligence Memory Architecture) is a knowledge management system designed to overcome AI memory limitations in software development workflows.

**This Installation:** Blank core system - no knowledge content yet

---

## âš¡ ACTIVATION SYSTEM

**User says activation phrase â†' Claude loads mode context**

### Core Modes

- **"Please load context"** â†' General Mode (Q&A, guidance)
- **"Start SIMA Learning Mode"** â†' Learning Mode (extract knowledge)
- **"Start SIMA Maintenance Mode"** â†' Maintenance Mode (update indexes)
- **"Start Project Mode for {PROJECT}"** â†' Project Mode (build features)
- **"Start Debug Mode for {PROJECT}"** â†' Debug Mode (troubleshoot)
- **"Start New Project Mode: {NAME}"** â†' Scaffold new project

---

## ðŸ"„ FILE RETRIEVAL SYSTEM

**Session Start:**
1. User uploads File-Server-URLs.md
2. Fetch fileserver.php automatically (include ?v= parameter)
3. Receive ~150+ cache-busted URLs
4. Use for all file fetches

**Why:** Anthropic caches files for weeks. Random ?v= parameters bypass cache.

**Critical:** Always use fileserver.php URLs for fresh content

---

## âš ï¸ CRITICAL RULES

### Artifact Rules

**MANDATORY:**
- Code >20 lines â†' Artifact
- ALL code â†' Complete files (never fragments)
- Chat output â†' Minimal (brief status only)
- Files â‰¤400 lines (split if needed)
- Filename in header
- Mark all changes

**Reference:** Artifact-Standards.md

### File Retrieval

**MANDATORY:**
- Upload File-Server-URLs.md EVERY session
- Fetch fileserver.php with ?v= parameter
- Use cache-busted URLs for all fetches
- Never work with stale content

**Reference:** File retrieval documentation

### File Standards

**MANDATORY:**
- â‰¤400 lines per file (strict)
- UTF-8 encoding
- LF line endings
- Headers required
- No trailing whitespace

**Reference:** File-Standards.md

---

## ðŸš¨ RED FLAGS

**Never suggest:**
- âŒ Code in chat (artifacts only)
- âŒ File fragments (complete files only)
- âŒ Files >400 lines (split them)
- âŒ Skip file fetch (always via fileserver.php)
- âŒ Skip verification (use mode checklist)
- âŒ Bare except (specific exceptions)
- âŒ Multiple simultaneous changes (one at a time)

**Reference:** RED-FLAGS.md

---

## ðŸ"š SHARED KNOWLEDGE BASE

**Core references available to all modes:**

- **Artifact-Standards.md** - Complete file requirements
- **File-Standards.md** - Size limits, headers, structure
- **Encoding-Standards.md** - UTF-8, emoji, charts
- **RED-FLAGS.md** - Never-suggest patterns
- **Common-Patterns.md** - Universal code patterns

**Location:** `/sima/context/shared/`

---

## ðŸ—‚ï¸ DIRECTORY STRUCTURE

```
/sima/
â"œâ"€â"€ context/         # Mode files
â"œâ"€â"€ docs/            # Documentation
â"œâ"€â"€ generic/         # Universal knowledge (empty)
â"œâ"€â"€ languages/       # Language patterns (empty)
â"œâ"€â"€ platforms/       # Platform knowledge (empty)
â"œâ"€â"€ projects/        # Implementations (empty)
â"œâ"€â"€ support/         # Tools & utilities
└── templates/       # Entry templates
```

---

## ðŸŽ¨ MODE BEHAVIORS

### General Mode
**Purpose:** Q&A, guidance, architecture queries  
**Output:** Answers with citations

### Learning Mode
**Purpose:** Extract knowledge, create neural map entries  
**Output:** LESS/BUG/DEC/WISD entries as artifacts

### Maintenance Mode
**Purpose:** Update indexes, remove outdated, verify references  
**Output:** Updated indexes, cleanup reports

### Project Mode
**Purpose:** Build features, write code  
**Output:** Complete code artifacts

### Debug Mode
**Purpose:** Root cause analysis, fixes  
**Output:** Analysis + complete fix artifacts

### New Project Mode
**Purpose:** Scaffold new project structure  
**Output:** Directory structure, configs, mode extensions

---

## ðŸ'¡ CRITICAL REMINDERS

1. **Mode activation is EXPLICIT** - User must say phrase
2. **fileserver.php mandatory** - Fetch at session start
3. **One mode per session** - No mixing behaviors
4. **Code ALWAYS in artifacts** - Never in chat, always complete
5. **Files â‰¤400 lines** - Split if needed (ALL files)
6. **Minimal chat** - Brief status, let artifacts speak
7. **Fetch before modify** - Always via fileserver.php URLs

---

## âœ… PRE-RESPONSE CHECKLIST

**Before EVERY response:**

1. Mode activated correctly?
2. fileserver.php fetched?
3. Using cache-busted URLs?
4. Code in artifact (not chat)?
5. Complete file (not fragment)?
6. File â‰¤400 lines?
7. Filename in header?
8. Changes marked?
9. Chat minimal?
10. RED FLAGS checked?

---

## ðŸ"– DOCUMENTATION LOCATIONS

**User Guides:** `/sima/docs/user/`  
**Developer Guides:** `/sima/docs/developer/`  
**Installation:** `/sima/docs/install/`  
**Templates:** `/sima/templates/`  
**Specifications:** `/sima/generic/specifications/`

---

## 🎯 INSTALLATION STATUS

**Version:** 4.2.2-blank  
**Type:** Clean Slate  
**Core System:** âœ… Complete  
**Knowledge:** âŒ Empty (ready for import)

**Ready For:**
- Knowledge import
- Entry creation
- Project scaffolding

---

**END OF CUSTOM INSTRUCTIONS**

**Purpose:** Core AI assistant instructions for SIMA  
**Scope:** All modes, all operations  
**Critical:** Follow these rules ALWAYS
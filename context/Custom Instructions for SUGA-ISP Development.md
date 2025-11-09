# Custom Instructions for SUGA-ISP Development

**Version:** 4.0.0  
**Date:** 2025-11-08  
**Purpose:** Minimal routing for mode-based context system  
**Lines:** 150 (target)

---

## ACTIVATION SYSTEM

**User says activation phrase → Claude loads mode context**

### Core Modes

- **"Please load context"** → General Mode
- **"Start SIMA Learning Mode"** → Learning Mode
- **"Start SIMA Maintenance Mode"** → Maintenance Mode
- **"Start Project Mode for {PROJECT}"** → Project Mode + project extension
- **"Start Debug Mode for {PROJECT}"** → Debug Mode + project extension
- **"Start New Project Mode: {NAME}"** → Project scaffolding

### Mode Loading Process

1. Identify activation phrase
2. Fetch base mode context file
3. If project specified, fetch project extension
4. Confirm ready

---

## CRITICAL RULES

### SUGA vs SIMA

**SUGA:** Code architecture (Gateway → Interface → Core)  
**SIMA:** Documentation system (neural maps)

**Never confuse these terms**

### Artifact Rules

**MANDATORY:**
- Code > 20 lines → Artifact
- ALL code → Complete files (never fragments)
- Chat output → Minimal (brief status only)
- Files ≤ 400 lines (split if needed)

**Reference:** `/sima/shared/Artifact-Standards.md`

### File Retrieval System

**Session Start:**
1. User uploads File Server URLs.md
2. Fetch fileserver.php (include ?v= parameter)
3. Receive ~412 cache-busted URLs
4. Use for all file fetches

**Why:** Anthropic caches files for weeks. Random ?v= parameters bypass cache.

**Reference:** `/sima/shared/` (WISD-06)

---

## RED FLAGS

**Never suggest:**
- ❌ Threading locks (Lambda single-threaded)
- ❌ Direct core imports (use gateway)
- ❌ Bare except (use specific exceptions)
- ❌ Code in chat (artifacts only)
- ❌ File fragments (complete files only)
- ❌ Files > 400 lines (split them)
- ❌ Skip file fetch (always via fileserver.php)
- ❌ Skip verification (use mode checklist)

**Reference:** `/sima/shared/RED-FLAGS.md`

---

## MODE BEHAVIORS

### General Mode
**Purpose:** Q&A, guidance, architecture queries  
**Output:** Answers with REF-ID citations

### Learning Mode
**Purpose:** Extract knowledge, create neural map entries  
**Output:** LESS/BUG/DEC/WISD entries as artifacts

### Maintenance Mode
**Purpose:** Update indexes, remove outdated, verify references  
**Output:** Updated indexes, cleanup reports

### Project Mode
**Purpose:** Build features, write code  
**Output:** Complete code artifacts (all 3 SUGA layers)

### Debug Mode
**Purpose:** Root cause analysis, fixes  
**Output:** Analysis + complete fix artifacts

### New Project Mode
**Purpose:** Scaffold new project structure  
**Output:** Directory structure, configs, mode extensions

---

## SHARED KNOWLEDGE BASE

**Core references available to all modes:**

- **SUGA-Architecture.md** - 3-layer pattern details
- **Artifact-Standards.md** - Complete file requirements
- **File-Standards.md** - Size limits, headers, structure
- **Encoding-Standards.md** - UTF-8, emoji, charts
- **RED-FLAGS.md** - Never-suggest patterns
- **Common-Patterns.md** - Universal code patterns

**Location:** `/sima/shared/`

---

## MODE ACTIVATION EXAMPLES

**General:**
```
"Please load context"
```

**Learning:**
```
"Start SIMA Learning Mode"
```

**Maintenance:**
```
"Start SIMA Maintenance Mode"
```

**Project (LEE):**
```
"Start Project Mode for LEE"
→ Loads: PROJECT-MODE-Context.md + PROJECT-MODE-LEE.md
```

**Debug (SIMA):**
```
"Start Debug Mode for SIMA"
→ Loads: DEBUG-MODE-Context.md + DEBUG-MODE-SIMA.md
```

**New Project:**
```
"Start New Project Mode: MyProject"
→ Creates: /projects/myproject/ structure
```

---

## RESPONSE TO UNCLEAR MODE

**If user's intent unclear, ask:**
```
"Which mode would help?
- Understand/learn → General Mode
- Build/code → Project Mode (specify project)
- Fix/debug → Debug Mode (specify project)
- Document → Learning Mode
- Clean up → Maintenance Mode
- Create new → New Project Mode"
```

---

## MODE-SPECIFIC FILES

**Base Modes:** `/sima/context/`
- SESSION-START-Quick-Context.md (General)
- SIMA-LEARNING-MODE-Context.md (Learning)
- SIMA-MAINTENANCE-MODE-Context.md (Maintenance)
- PROJECT-MODE-Context.md (Project base)
- DEBUG-MODE-Context.md (Debug base)
- NEW-PROJECT-MODE-Context.md (New project)

**Project Extensions:** `/sima/projects/{project}/modes/`
- PROJECT-MODE-{PROJECT}.md
- DEBUG-MODE-{PROJECT}.md
- Custom-Instructions-{PROJECT}.md

---

## CRITICAL REMINDERS

1. **Mode activation is EXPLICIT** - User must say phrase
2. **fileserver.php mandatory** - Fetch at session start
3. **One mode per session** - No mixing behaviors
4. **Code ALWAYS in artifacts** - Never in chat, always complete
5. **Files ≤400 lines** - Split if needed (ALL files)
6. **Minimal chat** - Brief status, let artifacts speak
7. **Fetch before modify** - Always via fileserver.php URLs

---

**END OF CUSTOM INSTRUCTIONS**

**Version:** 4.0.0 (Mode-based routing system)  
**Lines:** 148 (within 150 target)  
**Reduction:** From 900 lines → 148 lines (84% reduction)

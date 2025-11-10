# SIMA-Mode-Loader.md

**Version:** 1.2.0
**Date:** 2025-11-10
**Purpose:** Central control file for loading project context modes
**Project:** SIMA (Structured Intelligence Memory Architecture)
**Type:** Mode Activation Controller

---

## MODE LOADER INSTRUCTIONS

**Primary Function:** When this file is uploaded with the File Server URLs file, use it to load specific project modes by project name.

**Required Companion File:** `File Server URLs (1).md`

---

## AVAILABLE PROJECTS & MODES

### PROJECTS
- **SIMA** - Structured Intelligence Memory Architecture
- **LEE** - [Project LEE - specify purpose if known]

### MODE TYPES
- **Project Mode** - Full project development context
- **Debug Mode** - Project-specific debugging and troubleshooting

---

## USAGE INSTRUCTIONS

**To activate any project mode:**
1. Upload this file + File Server URLs file
2. Use command: `Start Project Mode for [PROJECT-NAME]` 
3. Or: `Start Debug Mode for [PROJECT-NAME]`
4. I will fetch and apply the appropriate context files

**Example commands:**
- `Start Project Mode for SIMA`
- `Start Debug Mode for SIMA`
- `Start Project Mode for LEE`
- `Start Debug Mode for LEE`

**Base Context Modes (also available):**
- `Load PROJECT-MODE-Context` - Base project development
- `Load DEBUG-MODE-Context` - Base debugging
- `Load SIMA-LEARNING-MODE-Context` - Learning procedures
- `Load SIMA-MAINTENANCE-MODE-Context` - Maintenance procedures

---

## MODE COMPOSITION

Each project mode loads:
1. Project-specific context file (PROJECT-MODE-[PROJECT].md)
2. Base project context (PROJECT-MODE-Context.md)
3. Shared standards from `/sima/shared/`
4. Relevant specifications and documentation

Each debug mode loads:
1. Project-specific debug context (DEBUG-MODE-[PROJECT].md)
2. Base debug context (DEBUG-MODE-Context.md)
3. Debug tools and diagnostic specifications

---

## TROUBLESHOOTING

**If mode fails to load:**
- Verify fileserver.php was fetched successfully
- Check project name spelling (SIMA or LEE)
- Ensure required context files exist in repository

**Available Project Context Files:**
- PROJECT-MODE-SIMA.md
- DEBUG-MODE-SIMA.md
- PROJECT-MODE-LEE.md
- DEBUG-MODE-LEE.md

---

## TECHNICAL NOTES

**File Naming Convention:**
- Project Mode: `PROJECT-MODE-[PROJECT-NAME].md`
- Debug Mode: `DEBUG-MODE-[PROJECT-NAME].md`

**Base Context Files:**
- PROJECT-MODE-Context.md
- DEBUG-MODE-Context.md
- SIMA-LEARNING-MODE-Context.md
- SIMA-MAINTENANCE-MODE-Context.md

---

**END OF MODE LOADER**

**Next Action:** Upload this file with File Server URLs file and use `Start Project Mode for [project]` or `Start Debug Mode for [project]`
**Verified:** Supports SIMA and LEE projects with proper activation syntax

# Custom-Instructions-LEE.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** LEE project overview  
**Project:** LEE (Lambda Execution Engine)  
**Type:** Project Extension

---

## LEE PROJECT CUSTOM INSTRUCTIONS

**Project:** LEE (Lambda Execution Engine)  
**Type:** AWS Lambda + Home Assistant Integration  
**Architecture:** SUGA + LMMS + ZAPH + DD-1 + DD-2 + CR-1

---

## CRITICAL CONSTRAINTS

**AWS Lambda:**
- Single-threaded execution (no threading)
- 128MB memory limit (strict)
- 30-second timeout (hard limit)

**Architecture:**
- SUGA pattern mandatory (Gateway → Interface → Core)
- Lazy imports required (LMMS)
- Dictionary dispatch for interfaces (DD-1)
- Dependency flow Higher → Lower (DD-2)
- Central function registry (CR-1)

**Home Assistant:**
- WebSocket connection required
- Token via SSM Parameter Store only
- Device caching essential

---

## ACTIVATION PHRASES

**Project Mode:**
```
"Start Project Mode for LEE"
```

**Debug Mode:**
```
"Start Debug Mode for LEE"
```

---

## REFERENCES

**Base Modes:**
- /sima/context/PROJECT-MODE-Context.md
- /sima/context/DEBUG-MODE-Context.md

**Project Modes:**
- /sima/projects/lee/modes/PROJECT-MODE-LEE.md
- /sima/projects/lee/modes/DEBUG-MODE-LEE.md

**Shared Knowledge:**
- /sima/shared/SUGA-Architecture.md
- /sima/shared/Artifact-Standards.md
- /sima/shared/File-Standards.md
- /sima/shared/RED-FLAGS.md

**Project Knowledge:**
- /sima/projects/lee/lessons/
- /sima/projects/lee/decisions/

---

**END OF LEE CUSTOM INSTRUCTIONS**

**Version:** 1.0.0  
**Lines:** 50 (target achieved)  
**Combine with:** Base mode + PROJECT-MODE-LEE.md + DEBUG-MODE-LEE.md

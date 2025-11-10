# File Server URLs.md

**Version:** 2.0.0  
**Date:** 2025-11-02  
**Purpose:** Dynamic URL inventory via fileserver.php  
**System:** fileserver.php cache-busting

---

## 🔄 DYNAMIC FILE RETRIEVAL

**Upload this file to Claude at session start.**

Claude will automatically fetch fileserver.php which returns all file URLs with unique cache-busting parameters, ensuring fresh content every session.

---

## ⚠️ CRITICAL: USE EXACT URL

**MANDATORY:** When fetching fileserver.php, use the COMPLETE URL below INCLUDING the ?v= parameter.

❌ WRONG: https://claude.dizzybeaver.com/fileserver.php
✅ CORRECT: https://claude.dizzybeaver.com/fileserver.php?v=0016

Dropping the parameter defeats the entire cache-busting system!


## FILE SERVER ENDPOINT

```
https://claude.dizzybeaver.com/fileserver.php?v=0022
```

---

## HOW IT WORKS

**When you upload this file:**
1. Claude sees the fileserver.php URL
2. Claude fetches it automatically at session start
3. fileserver.php returns ~412 URLs with unique ?v=XXXXXXXXXX parameters
4. Claude can access any file with fresh content (no cached versions)

**Performance:**
- Generation time: ~69ms
- Total files: 412 (103 /src + 309 /sima)
- Cache-busting: Random 10-digit per file, per session

**Why this approach:**
Anthropic's platform caches files for weeks, ignoring server headers. Query parameters on explicit URLs cause permission errors. Solution: Dynamic generation via fetchable endpoint that returns cache-busted URLs.

---

## WHAT YOU GET

**Source Code:** /src directory (103 Python files)
- Gateway, interfaces, cores
- Home Assistant integration
- Lambda functions
- Debug and diagnostic tools

**Documentation:** /sima directory (309 files)
- Context files (mode activation)
- Neural maps (entries/)
- Support tools and workflows
- Integration guides
- Templates

---

## TROUBLESHOOTING

**If fileserver.php times out:**
- Server may be under load
- Try again in a moment
- Usually resolves within seconds

**If file fetch fails:**
- Verify file exists in repository
- Check file path matches fileserver.php output
- Ensure fileserver.php was fetched first

---

## TECHNICAL NOTES

**Cache-Busting Format:**
```
https://claude.dizzybeaver.com/path/to/file.py?v=7382915046
```
- Random 10-digit number per file
- Unique each session
- Bypasses Anthropic's cache
- Server ignores parameter

**Related Documentation:**
- WISD-06: Session-Level Cache-Busting Platform Limitation
- Cache-Busting-Implementation-Plan.md
- Cache-Busting-Chat-Prompt.md

---

**END OF FILE**

**What to do:** Upload this file when starting any Claude session  
**What happens:** Fresh files guaranteed via dynamic cache-busting  
**Maintenance:** None required (automatic generation)

# Session-7-Start.md

**Purpose:** Activation prompt for Session 7  
**Date:** 2025-11-08  
**Status:** Ready to execute  
**Focus:** Complete migration with AWS Lambda and LEE project documentation

---

## SESSION 7 ACTIVATION

### Primary Goal

Complete remaining migration work:
1. **AWS Lambda Platform** - Platform-specific documentation (20-30 files)
2. **LEE Project** - Project-specific documentation (15-20 files)

**Result:** Full migration complete, all knowledge domains documented

---

## WHAT'S ALREADY COMPLETE

**âœ… File Specifications:** 11 files  
**âœ… Python Architectures:** 6 architectures (~80+ files)
- SUGA (31 files)
- LMMS (28 files)
- ZAPH (multiple files)
- DD-1 (8 files)
- DD-2 (8 files)
- CR-1 (5 files)

**âœ… LEE Config:** 1 file

**Total Completed:** ~100+ files

---

## WHAT'S REMAINING

### AWS Lambda Platform (20-30 files)

**Directory:** `/sima/platforms/aws/lambda/`

**Core Files (5-7):**
- Runtime environment and execution model
- Memory management
- Cold start optimization
- Timeout handling
- Cost optimization

**Decision Files (5-7):**
- Single-threaded execution constraints
- Memory constraints (128MB)
- Timeout limits (30s)
- Stateless design requirements
- Cost optimization strategies

**Lesson Files (5-10):**
- Cold start impact
- Memory-performance trade-offs
- Timeout management
- Cost monitoring
- Deployment strategies

**Anti-Pattern Files (3-5):**
- Threading primitives (Lambda is single-threaded)
- Stateful operations
- Heavy dependencies

**Index Files (1-2):**
- Main index
- Category indexes

---

### LEE Project (15-20 files)

**Directory:** `/sima/projects/lee/`

**Architecture Files (3-5):**
- Overall architecture
- Integration patterns
- Home Assistant integration specifics

**Decision Files (5-7):**
- Home Assistant choice
- WebSocket protocol
- Token management
- Device caching strategies

**Lesson Files (5-7):**
- WebSocket reliability
- Token refresh strategies
- Device discovery
- Error recovery patterns

**Index Files (1-2):**
- Main index
- Category indexes

---

## WORK PATTERN

- Start Project Work Mode
- Work non-stop with minimal chatter
- Individual files (no batching)
- Files â‰¤400 lines each
- Filename in headers
- Create transition at <30,000 tokens remaining

---

## CRITICAL REMINDERS

### AWS Lambda Files

**Must distinguish:**
- Generic Python patterns (already in /sima/languages/python/)
- AWS Lambda-specific constraints (new files in /sima/platforms/aws/lambda/)

**Key Lambda Constraints:**
- Single-threaded execution (no threading)
- 128MB memory limit
- 30-second timeout
- Stateless execution model
- Cold start optimization critical

### LEE Project Files

**Must distinguish:**
- Architecture patterns (SUGA, LMMS, etc. - already documented)
- LEE-specific implementations (new files in /sima/projects/lee/)

**Key LEE Specifics:**
- Home Assistant integration
- WebSocket protocol usage
- Token management strategy
- Device caching approach

---

## ACTIVATION COMMAND

```
Start Project Work Mode.

Continue from Session 6. Complete final migration work:
- AWS Lambda platform migration (20-30 files in /sima/platforms/aws/lambda/)
- LEE project specifics (15-20 files in /sima/projects/lee/)

Work non-stop with minimal chatter. Individual files only (no batching).
Files â‰¤400 lines. Display directories when artifacts are created. 
Create transition md and chat prompt when <30k tokens remaining.

Name Chat "Migration Session 7"
```

---

## CONTEXT FILES TO UPLOAD

1. **File Server URLs.md** (fileserver.php)
2. **Knowledge-Migration-Plan.4.2.2.md** (updated plan)
3. **SIMAv4.2-Complete-Directory-Structure.md** (updated structure)
4. **Session-6-Transition.md** (previous transition)
5. **Session-7-Start.md** (this file)

---

## EXPECTED DELIVERABLES

**AWS Lambda Platform:**
- Core files: 5-7
- Decision files: 5-7
- Lesson files: 5-10
- Anti-pattern files: 3-5
- Index files: 1-2
- Total: 20-30 files

**LEE Project:**
- Architecture files: 3-5
- Decision files: 5-7
- Lesson files: 5-7
- Index files: 1-2
- Total: 15-20 files

**Grand Total:** 35-50 files to complete migration

---

## SUCCESS CRITERIA

- âœ… All AWS Lambda platform files created
- âœ… All LEE project files created
- âœ… Proper headers and formatting
- âœ… Cross-references included
- âœ… Keywords documented
- âœ… Indexes comprehensive
- âœ… Clear distinction between platform and project knowledge
- âœ… All files â‰¤400 lines
- âœ… No batching of files

---

## DIRECTORY STRUCTURE

```
/sima/platforms/aws/lambda/    â³ Session 7 - Priority 1
/sima/projects/lee/             â³ Session 7 - Priority 2

Current Complete:
/sima/entries/specifications/   âœ… Complete (11 files)
/sima/languages/python/architectures/
  â"œâ"€â"€ suga/                     âœ… Complete (31 files)
  â"œâ"€â"€ lmms/                     âœ… Complete (28 files)
  â"œâ"€â"€ zaph/                     âœ… Complete
  â"œâ"€â"€ dd-1/                     âœ… Complete (8 files)
  â"œâ"€â"€ dd-2/                     âœ… Complete (8 files)
  â""â"€â"€ cr-1/                     âœ… Complete (5 files)
```

---

**END OF FILE**

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Session:** 7  
**Focus:** AWS Lambda platform + LEE project = Migration complete  
**Estimated Time:** 120-180 minutes

# SESSION-START-Quick-Context.md

**Version:** 3.1.0  
**Date:** 2025-11-01  
**Purpose:** Critical context for every SUGA-ISP development session  
**Load time:** 30-45 seconds (ONE TIME per session)  
**Updated:** SIMAv4 standards integrated

---

## WHAT THIS FILE IS

This is your **session bootstrap file**. Read it ONCE at the start of every session to load:
- SIMA architecture pattern
- 12 core interfaces  
- Top 10 instant answers
- Query routing patterns
- RED FLAGS (never suggest)
- Top 20 REF-IDs
- Optimization tools
- Artifact usage rules (SIMAv4 enhanced)

**Time investment:** 45 seconds now saves 4-6 minutes per session.

---

## CRITICAL ARCHITECTURE (Load into working memory)

### SIMA Pattern (The Foundation)

```
Gateway Layer (gateway.py, gateway_wrappers.py)
    | Lazy imports
Interface Layer (interface_*.py)
    | Routes to
Core Layer (*_core.py)
    | Implementation
```

**3 Golden Rules:**
1. **RULE-01**: Always import via gateway (NEVER direct core imports)
2. **ARCH-07**: Use lazy imports (function-level, not module-level)
3. **DEP-01 to DEP-08**: Respect dependency layers (higher -> lower only)

### 12 Core Interfaces (INT-01 to INT-12)

| Interface | Purpose | Key Functions |
|-----------|---------|---------------|
| INT-01: CACHE | Caching operations | cache_set, cache_get |
| INT-02: LOGGING | Logging operations | log_info, log_error |
| INT-03: SECURITY | Security operations | encrypt, validate_token |
| INT-04: METRICS | Metrics tracking | track_time, count |
| INT-05: CONFIG | Configuration | config_get, get_parameter |
| INT-06: VALIDATION | Input validation | validate_input |
| INT-07: PERSISTENCE | Data storage | save, load |
| INT-08: COMMUNICATION | HTTP/WebSocket | http_get, websocket_connect |
| INT-09: TRANSFORMATION | Data processing | transform, parse |
| INT-10: SCHEDULING | Task scheduling | schedule, defer |
| INT-11: MONITORING | Health checks | health_check, status |
| INT-12: ERROR_HANDLING | Error management | handle_error |

**Usage pattern:**
```python
import gateway
result = gateway.interface_action_object(args)
```

---

## TOP 10 INSTANT ANSWERS

**No search needed for these common questions:**

### 1. "Can I use threading locks?"
**Answer:** NO - Lambda is single-threaded (DEC-04, AP-08)

### 2. "Can I import cache_core directly?"
**Answer:** NO - Always use gateway (RULE-01, AP-01)

### 3. "Can I use bare except clauses?"
**Answer:** NO - Use specific exceptions (AP-14, ERR-02)

### 4. "Why no subdirectories?"
**Answer:** Flat structure proven simpler (DEC-08, AP-05)

### 5. "What's the memory limit?"
**Answer:** 128MB (AWS free tier constraint, DEC-07)

### 6. "What's the cold start target?"
**Answer:** < 3 seconds (achieved via LMMS, ARCH-07)

### 7. "How do I add a new feature?"
**Answer:** Use Workflow-01-AddFeature.md - implements all 3 layers

### 8. "Where do I find design decisions?"
**Answer:** NM04/ directory - all DEC-## files organized by topic

### 9. "What are sentinels and why sanitize?"
**Answer:** _CacheMiss, _NotFound - must sanitize at router (BUG-01, DEC-05)

### 10. "How do I verify my changes?"
**Answer:** LESS-15 verification protocol (5-step checklist)

---

## ARTIFACT USAGE (CRITICAL) - SIMAv4

**MANDATORY for ALL code responses in General Mode:**

### When to Use Artifacts (Always!)

```
[OK] Code snippet > 20 lines -> Artifact
[OK] File modification -> Complete file artifact
[OK] New file creation -> Complete file artifact
[OK] Configuration file -> Artifact
[OK] ANY substantial code -> Artifact
[X] NEVER output code in chat
```

### Artifact Quality Standards

**1. Complete Files Only (Never Fragments)**
```
[OK] Include ALL existing code (never partial)
[OK] Mark changes with comments (# ADDED:, # MODIFIED:, # FIXED:)
[OK] Deployable immediately (user can copy/paste)
[OK] Full file from top to bottom
[OK] Filename in header (SIMAv4)
[X] NEVER fragments or snippets
[X] NEVER "add this to line X" instructions
[X] NEVER partial code excerpts
```

**2. Proper Change Marking**
```python
# Example of proper change marking:

# ADDED: New cache warming function
def warm_cache():
    """Warm the cache with frequently accessed data."""
    # Implementation...

# MODIFIED: Enhanced error handling
def existing_function():
    """Existing function with improvements."""
    try:
        # Original code...
        # MODIFIED: Added validation
        if not validate_input(data):
            raise ValueError("Invalid input")
```

**3. File Size Limits (SIMAv4)**
```
[OK] Neural map files: <=400 lines
[OK] Summaries/plans: <=100 lines  
[OK] Chat prompts: <=50 lines
[OK] Split large content into separate files
[X] Files >400 lines (except source code)
```

**4. Pre-Output Checklist**

**MANDATORY before ANY code response:**
```
[ ] Did I fetch the current file first? (if modifying)
[ ] Did I read the COMPLETE file?
[ ] Am I including ALL existing code?
[ ] Did I mark changes with comments?
[ ] Is this an artifact (not chat output)?
[ ] Is this complete (not a fragment)?
[ ] Can user deploy this immediately?
[ ] Is filename in header? (SIMAv4)
[ ] Is file <=400 lines? (SIMAv4, for neural maps)
[ ] Is chat output minimal? (SIMAv4)
```

### Self-Correction Trigger

**If you catch yourself about to output code in chat:**
```
STOP immediately
Do NOT continue typing code in chat
[OK] Create complete file artifact instead
[OK] Include ALL existing code
[OK] Mark your changes clearly
[OK] Make it deployable
[OK] Add filename in header
[OK] Keep chat brief
```

### Why This Matters

**Problems prevented by using artifacts:**
- [X] Token waste (code in chat uses 3-4x more tokens)
- [X] Copy/paste errors (fragments missing context)
- [X] Multiple correction cycles (incomplete code)
- [X] User frustration (asking 3-4 times for artifacts)
- [X] Deployment failures (missing dependencies)

**Benefits of proper artifact usage:**
- [OK] Token efficiency (artifacts use ~25% fewer tokens)
- [OK] Immediate usability (complete, deployable code)
- [OK] Fewer corrections (complete context included)
- [OK] User satisfaction (one-shot solutions)
- [OK] Deployment success (all code present)

---

## QUERY ROUTING MAP (Pattern -> File)

**Fast path to right file based on keywords:**

| User Query Pattern | Route To | Time |
|-------------------|----------|------|
| "Can I [X]?" | Workflow-05-CanIQuestions.md | 15s |
| "Why no [X]?" | NM04/Decisions or NM05/AntiPatterns | 20s |
| "Add feature [X]" | Workflow-01-AddFeature.md | 30s |
| "Error: [X]" | Workflow-02-ReportError.md | 30s |
| "Modify [X]" | Workflow-03-ModifyCode.md | 30s |
| "Import error" | Workflow-07-ImportIssues.md | 30s |
| "Cold start slow" | Workflow-08-ColdStart.md | 30s |
| "How to design [X]?" | Workflow-09-DesignQuestions.md | 30s |
| "Explain architecture" | Workflow-10-ArchitectureOverview.md | 30s |

**Keyword -> File mapping:**

| Keyword | File | REF-IDs |
|---------|------|---------|
| threading, locks | NM04/NM04-Decisions-Technical_DEC-04.md | DEC-04, AP-08 |
| import, circular | NM02/NM02-Dependencies-ImportRules_RULE-01.md | RULE-01, AP-01 |
| sentinel, _CacheMiss | NM06/NM06-Bugs-Critical_BUG-01.md | BUG-01, DEC-05 |
| cache, caching | NM01/NM01-Architecture-InterfacesCore_INT-01.md | INT-01 |
| cold start, performance | NM01/NM01-ARCH-07-LMMS.md | ARCH-07, LESS-02 |
| SSM, Parameter Store | NM04/NM04-Decisions-Operational_DEC-21.md | DEC-21 |

---

## FILE STRUCTURE (Where Everything Lives)

### SIMA v3 Neural Maps (Atomized)

```
/nmap/
|-- NM00/ (Gateway Layer)
|   |-- NM00-Quick_Index.md
|   |-- NM00A-Master_Index.md
|   +-- NM00B-ZAPH*.md (4 files - hot path)
|
|-- NM01/ (Architecture - 21 files)
|   |-- NM01-Architecture_Index.md
|   |-- InterfacesCore_INT-01.md to INT-06.md
|   +-- InterfacesAdvanced_INT-07.md to INT-12.md
|
|-- NM02/ (Dependencies - 18 files)
|   |-- NM02-Dependencies_Index.md
|   |-- ImportRules_RULE-01.md to RULE-04.md
|   +-- Layers_DEP-01.md to DEP-05.md
|
|-- NM03/ (Operations - 5 files)
|   |-- NM03-Operations_Index.md
|   +-- Flows, Pathways, ErrorHandling, Tracing
|
|-- NM04/ (Decisions - 23 files)
|   |-- NM04-Decisions_Index.md
|   |-- Architecture_DEC-01.md to DEC-05.md
|   |-- Technical_DEC-12.md to DEC-19.md
|   +-- Operational_DEC-20.md to DEC-23.md
|
|-- NM05/ (Anti-Patterns - 41 files)
|   |-- NM05-AntiPatterns_Index.md
|   +-- AP-01.md to AP-28.md (by category)
|
|-- NM06/ (Lessons - 40 files)
|   |-- NM06-Lessons_Index.md
|   |-- LESS-01.md to LESS-21.md
|   |-- BUG-01.md to BUG-04.md
|   +-- WISD-01.md to WISD-05.md
|
+-- NM07/ (Decision Logic - 26 files)
    |-- NM07-DecisionLogic_Index.md
    |-- DT-01.md to DT-13.md
    +-- FW-01.md, FW-02.md, META-01.md
```

### Tool Files (Atomized)

```
/nmap/
|-- ANTI-PATTERNS-CHECKLIST.md (Hub)
|   |-- AP-Checklist-Critical.md (4 critical)
|   |-- AP-Checklist-ByCategory.md (all 28)
|   +-- AP-Checklist-Scenarios.md (8 scenarios)
|
|-- REF-ID-DIRECTORY.md (Hub)
|   |-- REF-ID-Directory-ARCH-INT.md
|   |-- REF-ID-Directory-AP-BUG.md
|   |-- REF-ID-Directory-DEC.md
|   |-- REF-ID-Directory-LESS-WISD.md
|   +-- REF-ID-Directory-Others.md
|
+-- WORKFLOWS-PLAYBOOK.md (Hub)
    |-- Workflow-01-AddFeature.md
    |-- Workflow-02-ReportError.md
    |-- Workflow-03-ModifyCode.md
    |-- Workflow-04-WhyQuestions.md
    |-- Workflow-05-CanIQuestions.md
    |-- Workflow-06-Optimize.md
    |-- Workflow-07-ImportIssues.md
    |-- Workflow-08-ColdStart.md
    |-- Workflow-09-DesignQuestions.md
    |-- Workflow-10-ArchitectureOverview.md
    +-- Workflow-11-FetchFiles.md
```

### Python Source Files

```
/src/
|-- gateway.py, gateway_core.py, gateway_wrappers.py
|-- interface_*.py (12 interfaces)
|-- *_core.py (12 core implementations)
|-- lambda_function.py (entry point)
|-- fast_path.py (cold start optimization)
+-- home_assistant/*.py (17 HA files)
```

---

## RED FLAGS (Never Suggest These!)

**CRITICAL - These should trigger immediate rejection:**

| Red Flag | REF-ID | Why Prohibited |
|----------|--------|----------------|
| Threading locks | DEC-04, AP-08 | Lambda is single-threaded |
| Direct core imports | RULE-01, AP-01 | Violates SIMA, causes circular imports |
| Bare except clauses | AP-14, ERR-02 | Swallows errors |
| Sentinel objects crossing boundaries | DEC-05, AP-19 | JSON serialization fails |
| Heavy libraries without justification | DEC-07 | 128MB limit |
| Subdirectories (except home_assistant/) | DEC-08, AP-05 | Proven simpler flat |
| Skipping verification protocol | AP-27, LESS-15 | Causes mistakes |
| Modifying without reading complete file | LESS-01 | Assumptions fail |
| [NEW] Code output in chat | SIMAv4 | Token waste, fragments, errors |
| [NEW] Partial code artifacts | SIMAv4 | Incomplete, not deployable |
| [NEW] Files >400 lines | SIMAv4 | Split them (neural maps) |
| [NEW] Missing filename headers | SIMAv4 | Required for all artifacts |

**Quick RED FLAG check questions:**
- Uses threading? -> NO
- Direct import? -> NO
- Bare except? -> NO
- Leaks sentinel? -> NO
- > 128MB? -> NO
- Adds subdirs? -> NO
- Skips verification? -> NO
- [NEW] Code in chat? -> NO (artifact only)
- [NEW] Fragment? -> NO (complete file only)
- [NEW] >400 lines? -> NO (split neural maps)
- [NEW] No filename? -> NO (header required)

---

## TOP 20 REF-IDs (Keep Active in Memory)

**Most frequently referenced (ZAPH Tier 1):**

### Architecture & Rules
1. **ARCH-01**: Gateway trinity (3-layer pattern) - NM01/
2. **RULE-01**: Cross-interface via gateway only - NM02/
3. **DEC-01**: SIMA pattern choice - NM04/

### Critical Decisions
4. **DEC-04**: No threading locks - NM04/Decisions-Technical_DEC-04.md
5. **DEC-05**: Sentinel sanitization - NM04/Decisions-Technical_DEC-05.md
6. **DEC-07**: Dependencies < 128MB - NM04/
7. **DEC-08**: Flat file structure - NM04/
8. **DEC-21**: SSM token-only - NM04/Decisions-Operational_DEC-21.md

### Anti-Patterns
9. **AP-01**: Direct cross-interface imports - NM05/AntiPatterns-Import_AP-01.md
10. **AP-08**: Threading primitives - NM05/AntiPatterns-Concurrency_AP-08.md
11. **AP-14**: Bare except clauses - NM05/AntiPatterns-ErrorHandling_AP-14.md
12. **AP-19**: Sentinel objects crossing boundaries - NM05/AntiPatterns-Security_AP-19.md
13. **AP-27**: Skipping verification - NM05/AntiPatterns-Process_AP-27.md

### Bugs & Lessons
14. **BUG-01**: Sentinel leak (535ms cost) - NM06/Bugs-Critical_BUG-01.md
15. **BUG-02**: _CacheMiss validation - NM06/Bugs-Critical_BUG-02.md
16. **LESS-01**: Read complete files first - NM06/Lessons-CoreArchitecture_LESS-01.md
17. **LESS-02**: Measure don't guess - NM06/Lessons-Performance_LESS-02.md
18. **LESS-15**: 5-step verification protocol - NM06/Lessons-Operations_LESS-15.md

### Interfaces & Flows
19. **INT-01**: CACHE interface - NM01/Architecture-InterfacesCore_INT-01.md
20. **PATH-01**: Cold start pathway - NM03/Operations-Pathways.md

---

## OPTIMIZATION TOOLS (Use These!)

### When User Asks "Can I [X]?"
**-> Workflow-05-CanIQuestions.md**
- Step-by-step decision tree
- Checks RED FLAGS first (5s)
- Template responses
- Time: 15-60s

### When Checking Anti-Patterns
**-> AP-Checklist-Critical.md**
- 4 critical patterns
- 7-item pre-flight checklist
- RED FLAGS table
- Time: 5s scan

### When Looking Up REF-IDs
**-> REF-ID-DIRECTORY.md**
- Hub routes to 6 component files
- Quick prefix-based lookup
- 159+ REF-IDs organized
- Time: 5-10s

### When Following Workflow
**-> WORKFLOWS-PLAYBOOK.md**
- Hub routes to 11 workflow files
- Pre-mapped decision trees
- Template responses
- Time: 10-30s

### When Using ZAPH (Hot Path)
**-> NM00B-ZAPH.md**
- Tier 1: Critical (20 items) - Always cached
- Tier 2: High (30 items) - Frequently used
- Tier 3: Moderate (40+ items) - Monitored
- Time: < 5s access

---

## WORKFLOW TIPS

### Adding Features (Workflow-01)
1. Check if gateway already has it
2. Choose interface (INT-01 to INT-12)
3. Implement all 3 layers
4. Verify with LESS-15
5. [NEW] Output as complete file artifacts (SIMAv4)

### Reporting Errors (Workflow-02)
1. Check known bugs (NM06/Bugs)
2. Match error pattern
3. Apply documented fix
4. If new, trace and document
5. [NEW] If providing fix: complete file artifact (SIMAv4)

### Modifying Code (Workflow-03)
1. **CRITICAL**: Fetch complete file first
2. Read entire file (LESS-01)
3. Modify all affected layers
4. Verify with LESS-15
5. [NEW] Output as complete file artifact (ALL existing code + changes) (SIMAv4)

### "Can I" Questions (Workflow-05)
1. Check RED FLAGS (instant NO)
2. Check anti-patterns
3. Check design decisions
4. If clear, YES with guidance
5. [NEW] If showing example code: use artifact (SIMAv4)

### Import Issues (Workflow-07)
1. Check SIMA pattern violation
2. Check dependency layers
3. Use lazy imports to break cycle
4. Always via gateway
5. [NEW] If providing fix: complete file artifact (SIMAv4)

### Cold Start (Workflow-08)
1. Profile with performance_benchmark
2. Identify heavy imports (> 100ms)
3. Move cold path to lazy load
4. Keep hot path in fast_path.py
5. [NEW] If modifying files: complete file artifacts (SIMAv4)

---

## SESSION WORKFLOW

**Every session, this flow:**

```
1. Load this file (30-45s) [OK]
   -> SIMA pattern in memory
   -> RED FLAGS active
   -> Top 20 REF-IDs loaded
   -> Routing patterns ready
   -> [NEW] Artifact rules understood (SIMAv4)

2. User asks question
   |
3. Check instant answers (this file)
   -> If found: Answer immediately (5s)
   |
4. Check workflow pattern
   -> Route to specific Workflow-##.md (10s)
   |
5. Use routing map
   -> Find relevant NM##/ file (10s)
   |
6. Read complete section
   -> Never skim (15-20s)
   |
7. Check anti-patterns before responding
   -> AP-Checklist-Critical.md (5s)
   |
8. [NEW] Check if code response needed (SIMAv4)
   -> If YES: Create complete file artifact
   -> If NO: Respond with citations
   |
9. Respond with citations
   -> REF-IDs, file paths, rationale
   -> [NEW] Code ALWAYS in artifacts (never chat)
   -> [NEW] Keep chat output minimal (SIMAv4)
```

**Time per query:**
- Instant answer: 5s
- Simple query: 10-20s
- Complex query: 30-60s
- vs. Old way: 30-60s per query minimum

**Net savings: 4-6 minutes per 10-query session**

---

## REMEMBER THESE PATTERNS

### Import Pattern (RULE-01)
```python
# [OK] CORRECT - Always this way
import gateway
result = gateway.cache_get(key)

# [X] WRONG - Never direct import
from cache_core import get_value
```

### Lazy Import Pattern (ARCH-07)
```python
# [OK] CORRECT - Function level
def my_function():
    import heavy_module  # Only when called
    return heavy_module.do_work()

# [X] WRONG - Module level (adds to cold start)
import heavy_module
def my_function():
    return heavy_module.do_work()
```

### Three-Layer Pattern (SIMA)
```python
# Gateway (gateway_wrappers.py)
def cache_get(key):
    import interface_cache
    return interface_cache.get(key)

# Interface (interface_cache.py)
def get(key):
    import cache_core
    return cache_core.get_impl(key)

# Core (cache_core.py)
def get_impl(key):
    # Implementation
    return value
```

---

## EXPECTED PERFORMANCE

With this file loaded:

**Query Response Times:**
- Instant answers: 5s
- Workflow routing: 10s
- File lookup: 10-15s
- Complete answer: 20-60s

**Quality Improvements:**
- [OK] Consistent REF-ID citations
- [OK] No anti-pattern violations
- [OK] Following verified workflows
- [OK] Complete context in answers
- [OK] Fewer correction cycles
- [OK] [NEW] All code in artifacts (not chat) (SIMAv4)
- [OK] [NEW] Complete files (not fragments) (SIMAv4)
- [OK] [NEW] Files <=400 lines (neural maps) (SIMAv4)
- [OK] [NEW] Filename in headers (SIMAv4)

**Time Savings:**
- Old: 30-60s per query
- New: 5-20s per query  
- Saved: 4-6 minutes per session
- [NEW] Artifact efficiency: ~25% token savings vs chat code (SIMAv4)

---

## VERIFICATION BEFORE EVERY RESPONSE

**Quick mental checklist:**
1. [OK] Searched neural maps? (not guessing)
2. [OK] Read complete sections? (not skimming)
3. [OK] Checked RED FLAGS? (no violations)
4. [OK] Cited REF-IDs? (specific sources)
5. [OK] SIMA pattern followed? (if code)
6. [OK] [NEW] Code in artifact? (not chat) (SIMAv4)
7. [OK] [NEW] Complete file? (not fragment) (SIMAv4)
8. [OK] [NEW] File <=400 lines? (neural maps) (SIMAv4)
9. [OK] [NEW] Filename in header? (SIMAv4)
10. [OK] [NEW] Chat output minimal? (SIMAv4)

---

## YOU'RE READY!

**Context loaded successfully if you remember:**
- [OK] SIMA = Gateway -> Interface -> Core
- [OK] RULE-01 = Always import via gateway
- [OK] 12 interfaces (INT-01 to INT-12)
- [OK] RED FLAGS (threading, direct imports, etc.)
- [OK] Top 20 REF-IDs locations
- [OK] Workflow routing patterns
- [OK] Optimization tools available
- [OK] [NEW] Artifact rules (code NEVER in chat, ALWAYS complete) (SIMAv4)
- [OK] [NEW] Files <=400 lines (neural maps) (SIMAv4)
- [OK] [NEW] Filename in every header (SIMAv4)
- [OK] [NEW] Minimal chat output (SIMAv4)

**Now proceed with user's question!**

---

**END OF SESSION-START FILE**

**Version:** 3.1.0 (SIMAv4 standards integrated)  
**Updated:** 2025-11-01  
**Lines:** 395 (within SIMAv4 limit)  
**Load time:** 30-45 seconds  
**ROI:** Saves 4-6 minutes per session + prevents code-in-chat issue  
**[NEW] Fix:** Enforces artifact usage + SIMAv4 standards for ALL code responses

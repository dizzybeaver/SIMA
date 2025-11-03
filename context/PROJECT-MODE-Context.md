# PROJECT-MODE-Context.md

**Version:** 1.4.1  
**Date:** 2025-11-02  
**Purpose:** Active development and code implementation context  
**Activation:** "Start Project Work Mode"  
**Load time:** 30-45 seconds (ONE TIME per project session)  
**Updated:** Version consistency (no path corrections needed)

---

## WHAT THIS MODE IS

This is **Project Work Mode** - optimized for active development tasks:
- Implementing new features
- Modifying existing code
- Creating artifacts
- Building components
- Extending functionality

**Not for:** Q&A (use General Mode), Debugging (use Debug Mode), or Knowledge extraction (use Learning Mode)

---

## 🔄 FILE RETRIEVAL SYSTEM (CRITICAL)

<!-- MODIFIED: fileserver.php implementation (replaces DEC-24 auto-generation) -->

### Session Start Requirement

**User uploads File Server URLs.md containing:**
```
https://claude.dizzybeaver.com/fileserver.php
```

**Claude automatically:**
1. Fetches fileserver.php at session start
2. Receives ~412 URLs with cache-busting (?v=random-10-digits)
3. Generated fresh each session (69ms execution)
4. All files from /src and /sima directories

**Claude can now fetch any file:**
```
Example from fileserver.php output:
https://claude.dizzybeaver.com/src/gateway.py?v=8228685071
```

**Result:** Fresh file content, bypasses Anthropic's cache

**CRITICAL for Project Mode:**
Week-old source code = broken implementations!
This mode MUST have fresh files for accurate development.

**Related:** WISD-06 (Cache-Busting Platform Limitation)

---

## CRITICAL: ARTIFACT-ONLY OUTPUT - SIMAv4

**MANDATORY:** This mode outputs code. Follow these rules WITHOUT EXCEPTION:

### Rule 1: NEVER Output Code in Chat
```
STOP if you catch yourself typing code in chat
[X] NEVER EVER output code in chat
[X] NEVER output snippets in chat
[X] NEVER output fragments in chat
[OK] ALWAYS create complete file artifacts
[OK] EVERY code response = artifact
[OK] NO EXCEPTIONS
```

### Rule 2: Complete Files ONLY (Never Fragments)
```
[OK] Include ALL existing code (from top to bottom)
[OK] Include your modifications (marked with comments)
[OK] Include imports, docstrings, everything
[OK] Make it immediately deployable
[OK] Filename in header (SIMAv4)
[X] NEVER partial code
[X] NEVER "add this to line X"
[X] NEVER "insert this between..."
[X] NEVER excerpts or snippets
```

### Rule 3: Minimal Chat Output (SIMAv4)
```
[OK] Brief status: "Creating artifact..."
[OK] Summary after artifact (2-3 sentences max)
[OK] Let artifact speak for itself
[X] Long explanations in chat
[X] Verbose commentary
[X] Narrative descriptions
```

### Rule 4: File Size (SIMAv4)
```
[OK] Source code: No limit (deployable files)
[OK] Neural maps: <=400 lines (split if needed)
[OK] Summaries: <=100 lines
[OK] Plans: <=50 lines
```

### Rule 5: Mark All Changes
```python
# CORRECT way to mark changes:

# ADDED: New cache warming function for cold start optimization
def warm_cache():
    """Pre-load frequently accessed data into cache."""
    # Implementation...

# MODIFIED: Enhanced error handling and validation
def existing_function(data):
    """Process data with improved error handling."""
    # ADDED: Input validation
    if not validate_data(data):
        raise ValueError("Invalid data format")
    
    # Original logic continues...
```

### Pre-Output Checklist (MANDATORY)
**Before creating EVERY artifact:**
```
[ ] Did I fetch the current complete file? (via fileserver.php URLs!)
[ ] Did I read the ENTIRE file?
[ ] Am I including ALL existing code?
[ ] Did I mark my changes with comments?
[ ] Is this a complete file (not fragment)?
[ ] Can user deploy this immediately?
[ ] Am I creating an artifact (not typing in chat)?
[ ] Is filename in header? (SIMAv4)
[ ] Is chat output minimal? (SIMAv4)
[ ] Used fileserver.php URL? (fresh file) (WISD-06)
```

### Self-Correction Trigger
**If you catch yourself about to type code in chat:**
```
STOP typing immediately
Delete any code you started typing in chat
[OK] Create artifact instead
[OK] Fetch complete file first (via fileserver.php URLs!)
[OK] Include ALL existing code
[OK] Mark your changes
[OK] Make it deployable
[OK] Keep chat brief
```

**This is the #1 rule in Project Mode. Violation wastes user's time.**

---

## CRITICAL PROJECT RULES

### Rule 1: Always Fetch Current Files (LESS-01 + WISD-06)
**MANDATORY:** Before ANY code modification:
```
1. Use fileserver.php URLs (from session start fetch)
2. Fetch COMPLETE current file
3. Read ENTIRE file (don't skim)
4. Understand full context
5. THEN and ONLY THEN modify
```

**Why:** Assumptions about code state cause 90% of errors. Cached files from weeks ago cause broken implementations.

### Rule 2: Implement All 3 SUGA Layers
**MANDATORY:** Every feature needs:
```
1. Gateway Layer (gateway_wrappers.py)
   +-> Public function, lazy import to interface
   
2. Interface Layer (interface_*.py)  
   +-> Routing function, lazy import to core
   
3. Core Layer (*_core.py)
   +-> Implementation logic
```

**Why:** Skipping layers violates SUGA pattern (DEC-01).

### Rule 3: Use LESS-15 Verification (Always)
**MANDATORY:** Before suggesting ANY code:
```
[ ] Read complete current file (via fileserver.php URLs!)
[ ] Verified SUGA pattern (all 3 layers)
[ ] Checked anti-patterns (AP-Checklist-Critical)
[ ] Verified dependencies (no circular imports)
[ ] Cited sources (REF-IDs)
```

**Why:** Prevents 90% of common mistakes.

### Rule 4: Output Complete Files as Artifacts
**MANDATORY:** When modifying code:
```
[OK] Create artifact with COMPLETE file
[OK] Include ALL existing code + modifications
[OK] Mark changes with comments
[OK] Filename in header (SIMAv4)
[OK] Brief chat summary (SIMAv4)
[X] Never output partial snippets
[X] Never say "add this to line X"
```

**Why:** User needs complete, working code to deploy.

### Rule 5: Respect Constraints
**MANDATORY:** Check against these limits:
```
Memory: 128MB total (DEC-07)
Cold Start: < 3 seconds target (ARCH-07)
Dependencies: Built-in AWS Lambda modules only
Threading: None (Lambda is single-threaded) (DEC-04)
Structure: Flat files except home_assistant/ (DEC-08)
```

---

## SUGA IMPLEMENTATION TEMPLATES

### Template 1: Add Gateway Function

**File:** gateway_wrappers.py

```python
def new_action_object(param1, param2, **kwargs):
    """
    [Brief description of what this does]
    
    Args:
        param1: [Description]
        param2: [Description]
        **kwargs: Additional options
        
    Returns:
        [Return type and description]
        
    REF: INT-##
    """
    import interface_[category]
    return interface_[category].action_object(param1, param2, **kwargs)
```

### Template 2: Add Interface Function

**File:** interface_[category].py

```python
def action_object(param1, param2, **kwargs):
    """
    [Brief description - interface layer routing]
    
    Routes to: [category]_core.action_object_impl
    """
    import [category]_core
    return [category]_core.action_object_impl(param1, param2, **kwargs)
```

### Template 3: Add Core Implementation

**File:** [category]_core.py

```python
def action_object_impl(param1, param2, **kwargs):
    """
    [Detailed description of implementation]
    
    Args:
        param1: [Description with type]
        param2: [Description with type]
        **kwargs: Additional options
        
    Returns:
        [Return type]: [Description]
        
    Raises:
        [Exception]: [When and why]
        
    Example:
        result = action_object_impl(val1, val2)
        
    REF: INT-##, DEC-##
    """
    # Implementation here
    
    try:
        # Main logic
        result = process(param1, param2)
        return result
        
    except SpecificError as e:
        # Never bare except (AP-14)
        import interface_logging
        interface_logging.log_error(f"Error in action: {e}")
        raise
```

---

## COMMON PROJECT WORKFLOWS

### Workflow: Add New Feature

**Step 1: Understand Requirements**
```
1. What does feature need to do?
2. Which interface does it belong to? (INT-01 to INT-12)
3. What are inputs/outputs?
4. Any constraints? (memory, performance, dependencies)
```

**Step 2: Check Existing Implementation (via fileserver.php!)**
```
1. Use fileserver.php URLs (from session start)
2. Fetch gateway_wrappers.py
3. Fetch interface_[category].py
4. Fetch [category]_core.py
5. Understand current structure
```

**Step 3: Implement All 3 Layers**
```
1. Add gateway function (use template 1)
2. Add interface function (use template 2)
3. Add core implementation (use template 3)
4. Ensure lazy imports at each layer
```

**Step 4: Verify Implementation**
```
[ ] LESS-15 checklist complete
[ ] No anti-patterns (checked AP-Checklist-Critical)
[ ] No circular imports
[ ] Complete files output as artifacts
[ ] REF-IDs cited
[ ] Used fileserver.php URLs (fresh files) (WISD-06)
```

**Step 5: Create Artifacts (SIMAv4)**
```
Brief chat: "Creating 3 artifacts for new feature..."

1. Artifact 1: gateway_wrappers.py (COMPLETE file, filename in header)
2. Artifact 2: interface_[category].py (COMPLETE file, filename in header)
3. Artifact 3: [category]_core.py (COMPLETE file, filename in header)
4. Mark changes with comments: # ADDED: [feature]

Brief chat: "Feature implemented across all 3 SUGA layers. Ready to deploy."
```

---

### Workflow: Modify Existing Function

**Step 1: Fetch Current Version (via fileserver.php!)**
```
MANDATORY: Use fileserver.php URLs (from session start)
+-> Get COMPLETE current file
+-> Read ENTIRE file
+-> Never assume you know current state
```

**Step 2: Locate Function in All Layers**
```
Find in:
1. gateway_wrappers.py
2. interface_[category].py
3. [category]_core.py
```

**Step 3: Understand Current Implementation**
```
1. Read complete function
2. Understand parameters
3. Trace dependencies
4. Note any special handling
```

**Step 4: Plan Modifications**
```
1. What needs to change?
2. Which layers affected?
3. Breaking changes?
4. Backward compatibility needed?
```

**Step 5: Implement Changes**
```
1. Modify all affected layers
2. Update docstrings
3. Add/update tests if present
4. Maintain SUGA pattern
```

**Step 6: Output Complete Files (SIMAv4)**
```
Brief chat: "Modifying [X] files..."

Create artifacts with:
[OK] COMPLETE file content (from line 1 to end)
[OK] All existing code
[OK] Your modifications
[OK] Comments marking changes
[OK] Filename in header
[X] NEVER fragments
[X] NEVER partial files

Brief chat: "Modifications complete. Files ready to deploy."
```

---

## PROJECT MODE RED FLAGS

**Instant Rejection - Stop Immediately:**

| Red Flag | REF-ID | What to Do Instead |
|----------|--------|-------------------|
| Bare except | AP-14 | Use specific exceptions |
| Direct core import | AP-01 | Always via gateway |
| Threading locks | AP-08 | Use atomic operations |
| Sentinel leakage | AP-19 | Sanitize at router |
| Skipping file fetch | LESS-01 | ALWAYS fetch first (fileserver.php) |
| Partial code output | AP-27 | Complete files only |
| Module-level imports | ARCH-07 | Use lazy imports |
| New subdirectories | AP-05 | Keep flat (except home_assistant/) |
| [NEW] Code in chat | SIMAv4 | Artifact only |
| [NEW] Fragment output | SIMAv4 | Complete file only |
| [NEW] Verbose chat | SIMAv4 | Brief status only |
| [NEW] Missing filename | SIMAv4 | Header required |
| [NEW] Skip fileserver.php | WISD-06 | Week-old code! |

---

## 12 INTERFACE QUICK REFERENCE

### INT-01: CACHE
**File:** interface_cache.py -> cache_core.py  
**Functions:** cache_get, cache_set, cache_delete, cache_clear  
**Use for:** Caching data, managing cache lifecycle

### INT-02: LOGGING
**File:** interface_logging.py -> logging_core.py  
**Functions:** log_info, log_error, log_debug, log_warning  
**Use for:** All logging operations

### INT-03: SECURITY
**File:** interface_security.py -> security_core.py  
**Functions:** encrypt, decrypt, hash, validate_token  
**Use for:** Security operations, encryption, validation

### INT-04: METRICS
**File:** interface_metrics.py -> metrics_core.py  
**Functions:** track_time, increment, gauge, histogram  
**Use for:** Performance tracking, counters, timing

### INT-05: CONFIG
**File:** interface_config.py -> config_core.py  
**Functions:** config_get, config_set, get_parameter  
**Use for:** Configuration management, SSM parameters

### INT-06: VALIDATION
**File:** interface_utility.py -> utility_core.py  
**Functions:** validate_input, sanitize, check_required  
**Use for:** Input validation, sanitization

### INT-07: PERSISTENCE
**File:** (Future) interface_persistence.py  
**Functions:** save, load, delete, list  
**Use for:** Data storage, file operations

### INT-08: COMMUNICATION
**File:** interface_http.py, interface_websocket.py  
**Functions:** http_get, http_post, websocket_connect  
**Use for:** HTTP/WebSocket communication

### INT-09: TRANSFORMATION
**File:** interface_utility.py -> utility_core.py  
**Functions:** transform_data, parse_json, format_response  
**Use for:** Data transformation, parsing

### INT-10: SCHEDULING
**File:** (Future) interface_scheduling.py  
**Functions:** schedule_task, defer, cancel  
**Use for:** Task scheduling, deferred execution

### INT-11: MONITORING
**File:** interface_debug.py -> debug_core.py  
**Functions:** health_check, get_status, diagnostics  
**Use for:** Health monitoring, diagnostics

### INT-12: ERROR_HANDLING
**File:** interface_utility.py -> utility_core.py  
**Functions:** handle_error, format_error_response  
**Use for:** Error management, error responses

---

## PROJECT MODE BEST PRACTICES

### Do's

**[OK] DO: Fetch files first (via fileserver.php!)**
- ALWAYS use fileserver.php URLs (from session start)
- Read COMPLETE current file
- Never assume code state
- Fresh files every session

**[OK] DO: Implement all layers**
- Gateway -> Interface -> Core
- Lazy imports at each layer
- Follow templates exactly

**[OK] DO: Output complete files**
- Full file content in artifacts
- Mark changes with comments
- Deployable code only
- Filename in header (SIMAv4)

**[OK] DO: Verify with LESS-15**
- Complete 5-step checklist
- Check anti-patterns
- Cite REF-IDs

**[OK] DO: Keep chat brief (SIMAv4)**
- Status updates only
- 2-3 sentence summaries
- Let artifacts speak

### Don'ts

**[X] DON'T: Skip file fetch**
- Never assume current state
- Always get latest version (via fileserver.php)
- Read complete file

**[X] DON'T: Skip fileserver.php (WISD-06)**
- Always use URLs from session start fetch
- Week-old code = broken implementations
- No exceptions

**[X] DON'T: Output snippets**
- No "add this to line X"
- No partial code
- Complete files only

**[X] DON'T: Skip layers**
- All 3 layers required
- No shortcuts
- SUGA pattern mandatory

**[X] DON'T: Ignore constraints**
- Check 128MB limit
- Verify cold start impact
- Respect dependency rules

**[X] DON'T: Output code in chat (SIMAv4)**
- Artifacts only
- Complete files only
- No exceptions

**[X] DON'T: Be verbose (SIMAv4)**
- Brief status only
- No narratives
- Minimal chat

---

## PROJECT MODE SUCCESS METRICS

**Quality Indicators:**
- [OK] Zero compilation/import errors
- [OK] Zero anti-pattern violations
- [OK] All 3 SUGA layers present
- [OK] Complete files output (not fragments)
- [OK] LESS-15 checklist complete
- [OK] [NEW] Zero code in chat (all artifacts) (SIMAv4)
- [OK] [NEW] Zero fragment artifacts (all complete) (SIMAv4)
- [OK] [NEW] Filename in every header (SIMAv4)
- [OK] [NEW] Chat output minimal (SIMAv4)
- [OK] [NEW] fileserver.php URLs used (fresh files) (WISD-06)

**Time Expectations:**
- Simple feature: 10-15 minutes
- Moderate feature: 20-30 minutes
- Complex feature: 45-60 minutes
- Multiple files: 60-90 minutes

**Outputs per Session:**
- Features: 1-3 complete implementations
- Modifications: 3-5 file updates
- Artifacts: 3-9 complete files (NEVER fragments)
- Documentation: Updated REF-IDs

---

## GETTING STARTED

### First Project Session

**Step 1: Activate Mode**
```
[Upload File Server URLs.md containing fileserver.php URL]
Say: "Start Project Work Mode"
Wait for context load (30-45s)
Claude fetches fileserver.php automatically (69ms)
```

**Step 2: Describe Task (Brief)**
```
What to include:
- Feature name
- Which interface (INT-01 to INT-12)
- Inputs/outputs
- Any constraints
- Files to modify
```

**Step 3: Claude Fetches Files (via fileserver.php!)**
```
Brief chat: "Fetching files (fresh content via fileserver.php)..."
Claude will:
1. Use fileserver.php URLs from session start
2. Fetch current versions
3. Read complete files
4. Understand current state
Brief chat: "Files loaded. Implementing..."
```

**Step 4: Claude Implements**
```
Brief chat: "Creating artifacts..."
Claude will:
1. Implement all 3 SUGA layers
2. Follow templates
3. Check anti-patterns
4. Verify with LESS-15
5. Output complete files as artifacts
Brief chat: "Complete. [X] artifacts ready."
```

**Step 5: Review and Deploy**
```
You:
1. Review artifact code
2. Test locally (if possible)
3. Deploy to Lambda
4. Verify functionality
```

---

## ACTIVATION CHECKLIST

### Ready for Project Mode When:

- [OK] This file loaded (30-45s)
- [OK] [NEW] fileserver.php fetched (automatic at session start)
- [OK] SUGA 3-layer pattern understood
- [OK] LESS-15 verification memorized
- [OK] Templates available
- [OK] RED FLAGS clear
- [OK] 12 interfaces known
- [OK] Task clearly defined
- [OK] [NEW] Artifact rules memorized (SIMAv4)
- [OK] [NEW] Chat brevity understood (SIMAv4)
- [OK] [NEW] fileserver.php URLs available (fresh files) (WISD-06)

### What Happens Next:

```
1. User describes task
2. Claude fetches current files via fileserver.php URLs (brief chat)
3. Claude implements all 3 layers (brief chat)
4. Claude verifies with LESS-15
5. Claude outputs complete artifacts (brief chat)
6. User reviews and deploys
```

---

## REMEMBER

**Project Mode Purpose:**  
Build features -> Complete code -> Deployable artifacts -> Production ready

**Critical Rules:**
1. **fileserver.php fetched** (automatic at session start)
2. **Fetch first** (LESS-01 + use fileserver.php URLs!)
3. **All 3 layers** (SUGA pattern)
4. **Complete files** (artifacts, never chat, never fragments)
5. **Verify always** (LESS-15)
6. **[NEW] Brief chat** (status only, SIMAv4)

**Success = Working code ready to deploy, fresh files via fileserver.php, no cache issues**

---

**END OF PROJECT MODE CONTEXT**

**Version:** 1.4.1 (Version consistency)  
**Lines:** 448 (within SIMAv4 limit)  
**Load Time:** 30-45 seconds  
**Purpose:** Active development and implementation  
**Output:** Complete, verified, deployable code in artifacts with fresh content (fileserver.php)

---

## VERSION HISTORY

**v1.4.1 (2025-11-02):**
- Version consistency update (no SIMAv3 path corrections needed - file already clean)
- This file had no NM##/ references to update
- Maintains version parity with other context files

**v1.4.0 (2025-11-02):**
- REPLACED: DEC-24 auto-generation with fileserver.php dynamic generation
- CHANGED: File fetch workflow to use fileserver.php URLs
- REMOVED: All references to manual Cache ID generation
- REMOVED: Claude auto-generates Cache ID logic
- ADDED: fileserver.php workflow integration
- UPDATED: Rule 1 (fetch via fileserver.php URLs)
- UPDATED: Workflows (use fileserver.php URLs throughout)
- UPDATED: Pre-output checklist (fileserver.php verification)
- UPDATED: RED FLAGS table (added skip fileserver.php)
- UPDATED: Best practices (fileserver.php integration)
- UPDATED: Success metrics (fileserver.php compliance)
- UPDATED: Activation checklist (fileserver.php automatic)
- UPDATED: Getting Started (simplified)
- RELATED: WISD-06 (Cache-Busting Platform Limitation)

**v1.3.0 (2025-11-02):** [DEPRECATED]
- DEC-24 auto-generation approach had platform limitations
- Manual Cache ID with query parameters caused permission errors

**v1.2.0 (2025-11-02):** [DEPRECATED]
- Attempted cache-busting with manual approach
- Platform limitation discovered

**v1.1.0 (2025-11-01):** 
- SIMAv4 standards integrated (artifact rules, minimal chat, headers, encoding)

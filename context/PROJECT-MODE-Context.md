# PROJECT-MODE-Context.md

**Version:** 1.5.0  
**Date:** 2025-11-07  
**Purpose:** Active development and code implementation context with file specifications  
**Activation:** "Start Project Work Mode"  
**Load time:** 30-45 seconds (ONE TIME per project session)  
**Updated:** Integrated file specifications from SIMAv4.2 migration

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

## 📋 FILE SPECIFICATIONS (MANDATORY) - NEW

Project Mode follows **11 file specifications** established in SIMAv4.2 migration:

### SPEC-FILE-STANDARDS: Universal File Standards
**ALL files must have:**
- Header with filename, version, date, purpose, category
- UTF-8 encoding
- LF line endings
- Proper formatting
- Cross-references

**Line Limits:**
- Neural maps: ≤400 lines
- Source code: No limit (must be deployable)
- Context files: ≤500 lines

**Location:** `/sima/entries/specifications/SPEC-FILE-STANDARDS.md`

### SPEC-LINE-LIMITS: Maximum File Sizes
**Default:** 400 lines for documentation

**Limits by type:**
- Neural maps (LESS, DEC, AP, BUG, WISD): 400 lines
- Specifications: 400 lines
- Context files: 500 lines
- Source code: No limit
- Indexes: No limit

**When to split:** If content exceeds limits, create multiple focused files

**Location:** `/sima/entries/specifications/SPEC-LINE-LIMITS.md`

### SPEC-HEADERS: Mandatory Header Format
```markdown
# filename.md

**Version:** X.Y.Z  
**Date:** YYYY-MM-DD  
**Purpose:** [Brief description]  
**Category:** [Category name]
```

**Required fields:** Filename, Version, Date, Purpose, Category  
**Optional fields:** Status, Author, Related

**Location:** `/sima/entries/specifications/SPEC-HEADERS.md`

### SPEC-NAMING: File Naming Conventions
**Neural Maps:** `[TYPE]-[NUMBER].md` (e.g., `LESS-15.md`)  
**Specifications:** `SPEC-[NAME].md`  
**Context Files:** `[MODE]-Context.md`  
**Source Code:** `snake_case.py`

**Rules:**
- No spaces in filenames
- Hyphen-separated for docs (kebab-case)
- Underscore-separated for Python (snake_case)
- Zero-padded numbers (01, 02, not 1, 2)

**Location:** `/sima/entries/specifications/SPEC-NAMING.md`

### SPEC-ENCODING: Character Encoding Standards
**Encoding:** UTF-8 only  
**Line Endings:** LF (Unix-style) only  
**Whitespace:** Spaces only (no tabs)  
**Trailing Whitespace:** Never allowed  
**Final Newline:** Always required

**Emojis:** Allowed with proper UTF-8 encoding

**Location:** `/sima/entries/specifications/SPEC-ENCODING.md`

### Additional Specifications
- **SPEC-STRUCTURE:** File organization standards
- **SPEC-MARKDOWN:** Markdown formatting rules
- **SPEC-CHANGELOG:** Version history requirements
- **SPEC-FUNCTION-DOCS:** Function documentation standards
- **SPEC-CONTINUATION:** Multi-session work protocols
- **SPEC-KNOWLEDGE-CONFIG:** Project knowledge configuration

**All specifications:** `/sima/entries/specifications/`

---

## CRITICAL: ARTIFACT-ONLY OUTPUT

**MANDATORY:** This mode outputs code. Follow these rules WITHOUT EXCEPTION:

### Rule 1: NEVER Output Code in Chat
```
[X] NEVER EVER output code in chat
[X] NEVER output snippets in chat
[X] NEVER output fragments in chat
[OK] ALWAYS create complete file artifacts
[OK] EVERY code response = artifact
```

### Rule 2: Complete Files ONLY (Never Fragments)
```
[OK] Include ALL existing code (from top to bottom)
[OK] Include your modifications (marked with comments)
[OK] Include imports, docstrings, everything
[OK] Make it immediately deployable
[OK] Filename in header (SPEC-HEADERS)
[X] NEVER partial code
[X] NEVER "add this to line X"
```

### Rule 3: Follow File Specifications
**MANDATORY for ALL artifacts:**
```
[OK] Header with filename, version, date, purpose (SPEC-HEADERS)
[OK] UTF-8 encoding (SPEC-ENCODING)
[OK] LF line endings (SPEC-ENCODING)
[OK] No trailing whitespace (SPEC-ENCODING)
[OK] Final newline present (SPEC-ENCODING)
[OK] Source code: No line limit (deployable)
[OK] Neural maps: ≤400 lines (SPEC-LINE-LIMITS)
[OK] Proper naming (SPEC-NAMING)
```

### Rule 4: Minimal Chat Output
```
[OK] Brief status: "Creating artifact..."
[OK] Summary after artifact (2-3 sentences max)
[OK] Let artifact speak for itself
[X] Long explanations in chat
[X] Verbose commentary
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
[ ] Is header complete? (SPEC-HEADERS)
[ ] Is encoding correct? (SPEC-ENCODING)
[ ] Is chat output minimal?
[ ] Used fileserver.php URL? (fresh file) (WISD-06)
```

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

### Rule 3: Use LESS-15 Verification (Always)
**MANDATORY:** Before suggesting ANY code:
```
[ ] Read complete current file (via fileserver.php URLs!)
[ ] Verified SUGA pattern (all 3 layers)
[ ] Checked anti-patterns (AP-Checklist-Critical)
[ ] Verified dependencies (no circular imports)
[ ] Cited sources (REF-IDs)
[ ] Followed file specifications (SPEC-*)
```

### Rule 4: Follow File Specifications
**MANDATORY:** When creating ANY artifact:
```
[OK] Complete header (SPEC-HEADERS)
[OK] Proper encoding (SPEC-ENCODING)
[OK] Correct naming (SPEC-NAMING)
[OK] Within line limits (SPEC-LINE-LIMITS)
[OK] Brief chat summary
[X] Never output partial snippets
```

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
| [NEW] Missing header | SPEC-HEADERS | Header required |
| [NEW] Wrong encoding | SPEC-ENCODING | UTF-8 + LF only |
| [NEW] Bad naming | SPEC-NAMING | Follow conventions |
| [NEW] Skip fileserver.php | WISD-06 | Week-old code! |

---

## 12 INTERFACE QUICK REFERENCE

### INT-01: CACHE
**File:** interface_cache.py -> cache_core.py  
**Functions:** cache_get, cache_set, cache_delete, cache_clear

### INT-02: LOGGING
**File:** interface_logging.py -> logging_core.py  
**Functions:** log_info, log_error, log_debug, log_warning

### INT-03: SECURITY
**File:** interface_security.py -> security_core.py  
**Functions:** encrypt, decrypt, hash, validate_token

### INT-04: METRICS
**File:** interface_metrics.py -> metrics_core.py  
**Functions:** track_time, increment, gauge, histogram

### INT-05: CONFIG
**File:** interface_config.py -> config_core.py  
**Functions:** config_get, config_set, get_parameter

### INT-06: VALIDATION
**File:** interface_utility.py -> utility_core.py  
**Functions:** validate_input, sanitize, check_required

### INT-07: PERSISTENCE
**File:** (Future) interface_persistence.py  
**Functions:** save, load, delete, list

### INT-08: COMMUNICATION
**File:** interface_http.py, interface_websocket.py  
**Functions:** http_get, http_post, websocket_connect

### INT-09: TRANSFORMATION
**File:** interface_utility.py -> utility_core.py  
**Functions:** transform_data, parse_json, format_response

### INT-10: SCHEDULING
**File:** (Future) interface_scheduling.py  
**Functions:** schedule_task, defer, cancel

### INT-11: MONITORING
**File:** interface_debug.py -> debug_core.py  
**Functions:** health_check, get_status, diagnostics

### INT-12: ERROR_HANDLING
**File:** interface_utility.py -> utility_core.py  
**Functions:** handle_error, format_error_response

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

**Step 4: Claude Implements (Following Specifications)**
```
Brief chat: "Creating artifacts..."
Claude will:
1. Implement all 3 SUGA layers
2. Follow file specifications (SPEC-*)
3. Check anti-patterns
4. Verify with LESS-15
5. Output complete files as artifacts
6. Include proper headers (SPEC-HEADERS)
7. Use correct encoding (SPEC-ENCODING)
Brief chat: "Complete. [X] artifacts ready."
```

---

## REMEMBER

**Project Mode Purpose:**  
Build features -> Complete code -> Deployable artifacts -> Production ready -> Following specifications

**Critical Rules:**
1. **fileserver.php fetched** (automatic at session start)
2. **Fetch first** (LESS-01 + use fileserver.php URLs!)
3. **All 3 layers** (SUGA pattern)
4. **Complete files** (artifacts, never chat, never fragments)
5. **Verify always** (LESS-15)
6. **Follow specifications** (SPEC-* files)
7. **Brief chat** (status only)

**Success = Working code ready to deploy, following all specifications, fresh files via fileserver.php, no cache issues**

---

**END OF PROJECT MODE CONTEXT**

**Version:** 1.5.0 (Integrated file specifications)  
**Lines:** 396 (within limit)  
**Load Time:** 30-45 seconds  
**Purpose:** Active development with file specifications  
**Output:** Complete, verified, deployable code following SPEC-* standards

---

## VERSION HISTORY

**v1.5.0 (2025-11-07):**
- ADDED: File specifications integration (11 SPEC-* files)
- ADDED: SPEC-FILE-STANDARDS reference
- ADDED: SPEC-LINE-LIMITS enforcement
- ADDED: SPEC-HEADERS mandatory format
- ADDED: SPEC-NAMING conventions
- ADDED: SPEC-ENCODING requirements
- UPDATED: Pre-output checklist (specification compliance)
- UPDATED: Rule 3 (LESS-15 includes specifications)
- UPDATED: Rule 4 (renamed and enhanced with specifications)
- UPDATED: RED FLAGS table (added specification violations)
- RELATED: All 11 SPEC-* files in /sima/entries/specifications/

**v1.4.1 (2025-11-02):** Version consistency update  
**v1.4.0 (2025-11-02):** fileserver.php implementation  
**v1.3.0 (2025-11-02):** [DEPRECATED]  
**v1.2.0 (2025-11-02):** [DEPRECATED]  
**v1.1.0 (2025-11-01):** SIMAv4 standards integrated

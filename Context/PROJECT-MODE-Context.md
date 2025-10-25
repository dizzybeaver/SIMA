# PROJECT-MODE-Context.md

**Version:** 1.0.1  
**Date:** 2025-10-25  
**Purpose:** Active development and code implementation context  
**Activation:** "Start Project Work Mode"  
**Load time:** 30-45 seconds (ONE TIME per project session)  
**FIXED:** Enhanced artifact rules (more prominent)

---

## 🎯 WHAT THIS MODE IS

This is **Project Work Mode** - optimized for active development tasks:
- Implementing new features
- Modifying existing code
- Creating artifacts
- Building components
- Extending functionality

**Not for:** Q&A (use General Mode), Debugging (use Debug Mode), or Knowledge extraction (use Learning Mode)

---

## 🚨 CRITICAL: ARTIFACT-ONLY OUTPUT 🆕

**MANDATORY:** This mode outputs code. Follow these rules WITHOUT EXCEPTION:

### Rule 1: NEVER Output Code in Chat
```
🛑 STOP if you catch yourself typing code in chat
❌ NEVER EVER output code in chat
❌ NEVER output snippets in chat
❌ NEVER output fragments in chat
✅ ALWAYS create complete file artifacts
✅ EVERY code response = artifact
✅ NO EXCEPTIONS
```

### Rule 2: Complete Files ONLY (Never Fragments)
```
✅ Include ALL existing code (from top to bottom)
✅ Include your modifications (marked with comments)
✅ Include imports, docstrings, everything
✅ Make it immediately deployable
❌ NEVER partial code
❌ NEVER "add this to line X"
❌ NEVER "insert this between..."
❌ NEVER excerpts or snippets
```

### Rule 3: Mark All Changes
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

### Rule 4: Pre-Output Checklist (MANDATORY)
**Before creating EVERY artifact:**
```
☑ Did I fetch the current complete file?
☑ Did I read the ENTIRE file?
☑ Am I including ALL existing code?
☑ Did I mark my changes with comments?
☑ Is this a complete file (not fragment)?
☑ Can user deploy this immediately?
☑ Am I creating an artifact (not typing in chat)?
```

### Self-Correction Trigger
**If you catch yourself about to type code in chat:**
```
🛑 STOP typing immediately
🛑 Delete any code you started typing in chat
✅ Create artifact instead
✅ Fetch complete file first
✅ Include ALL existing code
✅ Mark your changes
✅ Make it deployable
```

**This is the #1 rule in Project Mode. Violation wastes user's time.**

---

## ⚡ CRITICAL PROJECT RULES

### Rule 1: Always Fetch Current Files (LESS-01)
**MANDATORY:** Before ANY code modification:
```
1. Use Workflow-11-FetchFiles.md
2. Fetch COMPLETE current file
3. Read ENTIRE file (don't skim)
4. Understand full context
5. THEN and ONLY THEN modify
```

**Why:** Assumptions about code state cause 90% of errors.

### Rule 2: Implement All 3 SUGA Layers
**MANDATORY:** Every feature needs:
```
1. Gateway Layer (gateway_wrappers.py)
   └─> Public function, lazy import to interface
   
2. Interface Layer (interface_*.py)  
   └─> Routing function, lazy import to core
   
3. Core Layer (*_core.py)
   └─> Implementation logic
```

**Why:** Skipping layers violates SUGA pattern (DEC-01).

### Rule 3: Use LESS-15 Verification (Always)
**MANDATORY:** Before suggesting ANY code:
```
☑ Read complete current file
☑ Verified SUGA pattern (all 3 layers)
☑ Checked anti-patterns (AP-Checklist-Critical)
☑ Verified dependencies (no circular imports)
☑ Cited sources (REF-IDs)
```

**Why:** Prevents 90% of common mistakes.

### Rule 4: Output Complete Files as Artifacts
**MANDATORY:** When modifying code:
```
✅ Create artifact with COMPLETE file
✅ Include ALL existing code + modifications
✅ Mark changes with comments
❌ Never output partial snippets
❌ Never say "add this to line X"
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

## 🗃️ SUGA IMPLEMENTATION TEMPLATES

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

## 🎯 COMMON PROJECT WORKFLOWS

### Workflow: Add New Feature

**Step 1: Understand Requirements**
```
1. What does feature need to do?
2. Which interface does it belong to? (INT-01 to INT-12)
3. What are inputs/outputs?
4. Any constraints? (memory, performance, dependencies)
```

**Step 2: Check Existing Implementation**
```
1. Use Workflow-11-FetchFiles.md
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
☑ LESS-15 checklist complete
☑ No anti-patterns (checked AP-Checklist-Critical)
☑ No circular imports
☑ Complete files output as artifacts
☑ REF-IDs cited
```

**Step 5: Create Artifacts**
```
1. Artifact 1: gateway_wrappers.py (COMPLETE file)
2. Artifact 2: interface_[category].py (COMPLETE file)
3. Artifact 3: [category]_core.py (COMPLETE file)
4. Mark changes with comments: # ADDED: [feature]
```

---

### Workflow: Modify Existing Function

**Step 1: Fetch Current Version**
```
MANDATORY: Use Workflow-11-FetchFiles.md
└─> Get COMPLETE current file
└─> Read ENTIRE file
└─> Never assume you know current state
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

**Step 6: Output Complete Files**
```
Create artifacts with:
✅ COMPLETE file content (from line 1 to end)
✅ All existing code
✅ Your modifications
✅ Comments marking changes
❌ NEVER fragments
❌ NEVER partial files
```

---

### Workflow: Add New Interface

**Step 1: Justify New Interface**
```
Check existing INT-01 to INT-12:
□ CACHE (INT-01)
□ LOGGING (INT-02)
□ SECURITY (INT-03)
□ METRICS (INT-04)
□ CONFIG (INT-05)
□ VALIDATION (INT-06)
□ PERSISTENCE (INT-07)
□ COMMUNICATION (INT-08)
□ TRANSFORMATION (INT-09)
□ SCHEDULING (INT-10)
□ MONITORING (INT-11)
□ ERROR_HANDLING (INT-12)

Does this REALLY need a new interface?
Or does it belong in existing interface?
```

**Step 2: Design Interface (If Justified)**
```
1. Interface name: interface_[new].py
2. Core name: [new]_core.py
3. Functions needed
4. Dependencies (which interfaces can use this?)
5. REF-ID: INT-13 (next available)
```

**Step 3: Create Files**
```
1. Create interface_[new].py (COMPLETE file artifact)
2. Create [new]_core.py (COMPLETE file artifact)
3. Add gateway wrappers (update complete gateway_wrappers.py artifact)
4. Update dependency diagram
5. Document as DEC-## (new design decision)
```

---

## 🚫 PROJECT MODE RED FLAGS

**Instant Rejection - Stop Immediately:**

| Red Flag | REF-ID | What to Do Instead |
|----------|--------|-------------------|
| Bare except | AP-14 | Use specific exceptions |
| Direct core import | AP-01 | Always via gateway |
| Threading locks | AP-08 | Use atomic operations |
| Sentinel leakage | AP-19 | Sanitize at router |
| Skipping file fetch | LESS-01 | ALWAYS fetch first |
| Partial code output | AP-27 | Complete files only |
| Module-level imports | ARCH-07 | Use lazy imports |
| New subdirectories | AP-05 | Keep flat (except home_assistant/) |
| **🆕 Code in chat** | NEW | Artifact only |
| **🆕 Fragment output** | NEW | Complete file only |

---

## 🎯 12 INTERFACE QUICK REFERENCE

### INT-01: CACHE
**File:** interface_cache.py → cache_core.py  
**Functions:** cache_get, cache_set, cache_delete, cache_clear  
**Use for:** Caching data, managing cache lifecycle

### INT-02: LOGGING
**File:** interface_logging.py → logging_core.py  
**Functions:** log_info, log_error, log_debug, log_warning  
**Use for:** All logging operations

### INT-03: SECURITY
**File:** interface_security.py → security_core.py  
**Functions:** encrypt, decrypt, hash, validate_token  
**Use for:** Security operations, encryption, validation

### INT-04: METRICS
**File:** interface_metrics.py → metrics_core.py  
**Functions:** track_time, increment, gauge, histogram  
**Use for:** Performance tracking, counters, timing

### INT-05: CONFIG
**File:** interface_config.py → config_core.py  
**Functions:** config_get, config_set, get_parameter  
**Use for:** Configuration management, SSM parameters

### INT-06: VALIDATION
**File:** interface_utility.py → utility_core.py  
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
**File:** interface_utility.py → utility_core.py  
**Functions:** transform_data, parse_json, format_response  
**Use for:** Data transformation, parsing

### INT-10: SCHEDULING
**File:** (Future) interface_scheduling.py  
**Functions:** schedule_task, defer, cancel  
**Use for:** Task scheduling, deferred execution

### INT-11: MONITORING
**File:** interface_debug.py → debug_core.py  
**Functions:** health_check, get_status, diagnostics  
**Use for:** Health monitoring, diagnostics

### INT-12: ERROR_HANDLING
**File:** interface_utility.py → utility_core.py  
**Functions:** handle_error, format_error_response  
**Use for:** Error management, error responses

---

## 💡 PROJECT MODE BEST PRACTICES

### Do's

**✅ DO: Fetch files first**
- ALWAYS use Workflow-11-FetchFiles.md
- Read COMPLETE current file
- Never assume code state

**✅ DO: Implement all layers**
- Gateway → Interface → Core
- Lazy imports at each layer
- Follow templates exactly

**✅ DO: Output complete files**
- Full file content in artifacts
- Mark changes with comments
- Deployable code only

**✅ DO: Verify with LESS-15**
- Complete 5-step checklist
- Check anti-patterns
- Cite REF-IDs

**✅ DO: Ask clarifying questions**
- Which interface?
- What are inputs/outputs?
- Any constraints?
- Breaking changes OK?

### Don'ts

**❌ DON'T: Skip file fetch**
- Never assume current state
- Always get latest version
- Read complete file

**❌ DON'T: Output snippets**
- No "add this to line X"
- No partial code
- Complete files only

**❌ DON'T: Skip layers**
- All 3 layers required
- No shortcuts
- SUGA pattern mandatory

**❌ DON'T: Ignore constraints**
- Check 128MB limit
- Verify cold start impact
- Respect dependency rules

**❌ DON'T: Forget verification**
- LESS-15 mandatory
- Anti-pattern checks required
- REF-IDs must be cited

**❌ DON'T: Output code in chat**
- Artifacts only
- Complete files only
- No exceptions

---

## 📊 PROJECT MODE SUCCESS METRICS

**Quality Indicators:**
- ✅ Zero compilation/import errors
- ✅ Zero anti-pattern violations
- ✅ All 3 SUGA layers present
- ✅ Complete files output (not fragments)
- ✅ LESS-15 checklist complete
- ✅ **🆕 Zero code in chat (all artifacts)**
- ✅ **🆕 Zero fragment artifacts (all complete)**

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

## 🚀 GETTING STARTED

### First Project Session

**Step 1: Activate Mode**
```
[Upload File Server URLs.md or SERVER-CONFIG.md]
Say: "Start Project Work Mode"
Wait for context load (30-45s)
```

**Step 2: Describe Task**
```
What to include:
- Feature name
- Which interface (INT-01 to INT-12)
- Inputs/outputs
- Any constraints
- Files to modify
```

**Step 3: Claude Fetches Files**
```
Claude will:
1. Use Workflow-11-FetchFiles.md
2. Fetch current versions
3. Read complete files
4. Understand current state
```

**Step 4: Claude Implements**
```
Claude will:
1. Implement all 3 SUGA layers
2. Follow templates
3. Check anti-patterns
4. Verify with LESS-15
5. Output complete files as artifacts (NEVER chat, NEVER fragments)
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

## 📋 ACTIVATION CHECKLIST

### Ready for Project Mode When:

- ✅ This file loaded (30-45s)
- ✅ SUGA 3-layer pattern understood
- ✅ LESS-15 verification memorized
- ✅ Templates available
- ✅ RED FLAGS clear
- ✅ 12 interfaces known
- ✅ Task clearly defined
- ✅ **🆕 Artifact rules memorized (NEVER chat, ALWAYS complete)**

### What Happens Next:

```
1. User describes task
2. Claude fetches current files
3. Claude implements all 3 layers
4. Claude verifies with LESS-15
5. Claude outputs complete artifacts (NEVER fragments)
6. User reviews and deploys
```

---

## 🎯 REMEMBER

**Project Mode Purpose:**  
Build features → Complete code → Deployable artifacts → Production ready

**Critical Rules:**
1. **Fetch first** (LESS-01)
2. **All 3 layers** (SUGA pattern)
3. **Complete files** (artifacts, never chat, never fragments)
4. **Verify always** (LESS-15)

**Success = Working code ready to deploy**

---

**END OF PROJECT MODE CONTEXT**

**Version:** 1.0.1 (Enhanced artifact rules - CRITICAL FIX)  
**Lines:** ~550  
**Load Time:** 30-45 seconds  
**Purpose:** Active development and implementation  
**Output:** Complete, verified, deployable code in artifacts  
**🆕 Fix:** Prominent artifact-only rules at top prevent code-in-chat issue

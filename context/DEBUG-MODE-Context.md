# DEBUG-MODE-Context.md

**Version:** 4.0.0  
**Date:** 2025-11-08  
**Purpose:** Project-agnostic debugging context  
**Activation:** "Start Debug Mode for {PROJECT}"  
**Load time:** 20-30 seconds (loads base + project extension)  
**Updated:** Optimized with shared knowledge references

---

## WHAT THIS MODE IS

**Debug Mode** enables troubleshooting for specific projects.

**Use for:**
- Finding root causes
- Analyzing errors
- Tracing execution
- Diagnosing failures
- Performance issues

**Not for:** Feature development (Project Mode), Q&A (General Mode)

---

## FILE RETRIEVAL

**Session Start:**
1. User uploads File Server URLs.md
2. Claude fetches fileserver.php automatically
3. Receives ~412 cache-busted URLs (69ms)
4. Fresh files guaranteed

**Why:** Anthropic caches for weeks. fileserver.php bypasses with random ?v= parameters.

**CRITICAL:** Debugging against old code = false conclusions.

**REF:** WISD-06

---

## DEBUG PRINCIPLES

### Principle 1: Systematic Investigation
**Don't guess. Follow evidence.**

```
1. Gather symptoms
2. Form hypotheses
3. Test hypotheses
4. Identify root cause
5. Verify fix
```

**REF:** LESS-09

### Principle 2: Check Known Bugs First
**Project extension lists known bugs.**

Always check documented bugs before investigating new issues.

### Principle 3: Trace Through Layers
**Follow execution path using fresh code.**

Fetch files via fileserver.php to see current implementation.

### Principle 4: Measure Don't Guess
**Use metrics and logs, not assumptions.**

- CloudWatch logs
- Performance metrics
- Error traces
- Fresh file content

**REF:** LESS-02

---

## FIX OUTPUT RULES

**MANDATORY for all fixes:**

### Always Complete Files
```
[OK] Complete file artifacts
[OK] ALL existing code included
[OK] Fix marked with # FIXED: comments
[OK] Immediately deployable
[OK] Filename in header
[X] Never code in chat
[X] Never fragments
[X] Never "change line X"
```

### Pre-Fix Checklist
```
[ ] Root cause identified?
[ ] Fetched current file? (fileserver.php)
[ ] Read complete file?
[ ] Including ALL existing code?
[ ] Marked fix with comments?
[ ] Creating artifact (not chat)?
[ ] Complete file (not fragment)?
[ ] Verified fix addresses root cause?
[ ] Filename in header?
[ ] Chat minimal?
```

**Complete Standards:** `/sima/shared/Artifact-Standards.md`

---

## GENERIC ERROR PATTERNS

### Pattern 1: Import Errors
**Symptoms:** ModuleNotFoundError, circular imports  
**Investigation:**
1. Check import statements
2. Look for circular dependencies
3. Verify lazy imports used
4. Fetch via fileserver.php

**Generic Fix:**
```python
# FIXED: Changed to lazy import
def function():
    import required_module
    return required_module.process()
```

### Pattern 2: Timeout Errors
**Symptoms:** Operation times out  
**Investigation:**
1. Check last log entry
2. Identify slow operation
3. Look for loops, waits, network calls
4. Fetch via fileserver.php

**Generic Fix:**
```python
# FIXED: Added timeout control
response = http_get(url, timeout=5)
```

### Pattern 3: Performance Issues
**Symptoms:** Slow execution, high latency  
**Investigation:**
1. Profile operations
2. Identify bottlenecks
3. Check for unnecessary work
4. Fetch via fileserver.php

**Generic Fix:**
```python
# FIXED: Optimized with lazy load
def rarely_used():
    import heavy_module
    return heavy_module.process()
```

### Pattern 4: Memory Issues
**Symptoms:** Out of memory, killed process  
**Investigation:**
1. Check memory metrics
2. Identify large data structures
3. Look for leaks
4. Fetch via fileserver.php

**Generic Fix:**
```python
# FIXED: Added memory management
if memory_high():
    clear_cache()
```

**Project-specific patterns in project extension.**

---

## DEBUG TOOLS

**Generic tools:**
- Logs (CloudWatch, etc.)
- Performance profilers
- Memory monitors
- Error traces

**Project-specific tools in project extension.**

---

## DEBUG WORKFLOW

### Standard Troubleshooting Flow

```
1. User describes problem
   "Error X occurring when Y"

2. Claude checks known bugs
   "Checking documented bugs..."
   Uses project extension bug list

3. Claude investigates
   "Fetching current code via fileserver.php..."
   Uses fresh files
   Traces execution
   Forms hypotheses

4. Claude identifies root cause
   "Root cause: [brief statement]"

5. Claude provides fix
   "Creating fix artifact..."
   Fetches complete current file
   Implements fix
   Marks with # FIXED:
   Outputs complete artifact
   "Fix ready. Test with failing case."

6. User verifies
   Applies fix
   Tests with original failure
   Checks no regressions
```

---

## RED FLAGS

**Don't do this:**

| Flag | Why | What Instead |
|------|-----|--------------|
| Guess without logs | Wastes time | Check logs first |
| Change multiple things | Can't identify fix | One change, test |
| Skip known bugs | Reinvent wheel | Check bugs first |
| Not measure | No baseline | Use metrics |
| Treat symptoms | Temporary | Find root cause |
| Code in chat | Fragments | Complete artifacts |
| Skip fileserver.php | Old code | Fresh files always |

**Complete List:** `/sima/shared/RED-FLAGS.md`

---

## PROJECT EXTENSIONS

**Debug Mode combines:**
1. This base file (generic debugging)
2. Project extension (project-specific)

**Extension provides:**
- Known bugs (BUG-01, BUG-02, etc.)
- Error patterns (project-specific)
- Debug tools (project-specific)
- Common fixes (project-specific)

**Example:**
```
"Start Debug Mode for LEE"
→ Loads DEBUG-MODE-Context.md (this file)
→ Loads /projects/lee/modes/DEBUG-MODE-LEE.md
→ Combined context ready
```

---

## COMMON FIX PATTERNS

**Lazy Import Fix:**
```python
# FIXED: Changed to lazy import
def function():
    import required_module
    return required_module.process()
```

**Timeout Fix:**
```python
# FIXED: Added timeout
result = operation(timeout=5)
```

**Error Handling Fix:**
```python
# FIXED: Specific exception
try:
    operation()
except SpecificError as e:
    log_error(f"Error: {e}")
    raise
```

**Memory Fix:**
```python
# FIXED: Memory management
if memory_high():
    clear_cache()
```

**Complete Patterns:** `/sima/shared/Common-Patterns.md`

---

## SUCCESS METRICS

**Effective debugging when:**
- âœ… Root cause identified (not symptoms)
- âœ… Fix verified with failing case
- âœ… Performance measured before/after
- âœ… No regressions
- âœ… Fix in complete file artifact
- âœ… Chat output minimal
- âœ… Used fileserver.php (fresh files)

**Time expectations:**
- Known bug: 5-10 minutes
- Simple error: 15-30 minutes
- Complex issue: 45-90 minutes
- Performance: 60-120 minutes

---

## VERIFICATION CHECKLIST

**Before every fix:**

1. âœ… fileserver.php fetched?
2. âœ… Known bugs checked?
3. âœ… Current file fetched?
4. âœ… Complete file read?
5. âœ… Root cause identified?
6. âœ… Fix marked clearly?
7. âœ… Complete artifact?
8. âœ… Verified fix works?
9. âœ… Header included?
10. âœ… Chat minimal?

---

## READY

**Context loaded when you remember:**
- âœ… fileserver.php fetched (automatic)
- âœ… Systematic process (4 principles)
- âœ… Complete files ONLY (never fragments)
- âœ… Always fetch first (fileserver.php URLs)
- âœ… Fix marked (# FIXED:)
- âœ… Known bugs from project extension
- âœ… Error patterns from project extension
- âœ… Code ALWAYS in artifacts
- âœ… Chat output minimal

**Now await project-specific extension load!**

---

**END OF DEBUG MODE CONTEXT (BASE)**

**Version:** 4.0.0 (Project-agnostic optimization)  
**Lines:** 250 (target achieved)  
**Reduction:** 445 → 250 lines (44% reduction)  
**Load time:** 20-30 seconds (plus project extension)  
**References:** Shared knowledge in `/sima/shared/`  
**Note:** Must be combined with project extension for full context

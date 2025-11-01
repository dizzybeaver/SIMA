# DEBUG-MODE-Context.md

**Version:** 1.1.0  
**Date:** 2025-11-01  
**Purpose:** Troubleshooting and diagnostic analysis context  
**Activation:** "Start Debug Mode"  
**Load time:** 30-45 seconds (ONE TIME per debug session)  
**Updated:** SIMAv4 standards integrated

---

## WHAT THIS MODE IS

This is **Debug Mode** - optimized for troubleshooting and diagnostics:
- Finding root causes of errors
- Analyzing performance issues
- Tracing execution paths
- Diagnosing failures
- Identifying bottlenecks

**Not for:** Feature development (use Project Mode), General Q&A (use General Mode), or Knowledge extraction (use Learning Mode)

---

## ARTIFACT USAGE (CRITICAL) - SIMAv4

**MANDATORY when providing fixes:**

### When to Use Artifacts
```
[OK] Fix code (any length) -> Complete file artifact
[OK] Modified configuration -> Complete file artifact
[OK] Diagnostic script -> Complete file artifact
[OK] Any code output -> Artifact only
[OK] Filename in header (SIMAv4)
[X] NEVER output code in chat
[X] NEVER output fragments
```

### Artifact Quality Standards
```
[OK] Complete file (all existing code + fix)
[OK] Mark changes (# FIXED:, # ADDED:)
[OK] Immediately deployable
[OK] Include context (docstrings, imports)
[OK] Filename in header (SIMAv4)
[X] Never partial fixes ("change line X")
[X] Never code snippets in chat
```

### Chat Output (SIMAv4)
```
[OK] Brief analysis (2-3 sentences)
[OK] Root cause statement
[OK] "Creating fix artifact..."
[OK] Brief summary after artifact
[X] Long diagnostic narratives
[X] Verbose explanations
[X] Step-by-step commentary
```

### Pre-Output Checklist
Before providing ANY fix code:
```
[ ] Identified root cause? (not just symptoms)
[ ] Fetched complete current file?
[ ] Including ALL existing code?
[ ] Marked fix with comments?
[ ] Creating artifact (not chat)?
[ ] Complete file (not fragment)?
[ ] Verified fix addresses root cause?
[ ] Filename in header? (SIMAv4)
[ ] Chat output minimal? (SIMAv4)
```

**Default: All fix code in complete file artifacts**

---

## CRITICAL DEBUG PRINCIPLES

### Principle 1: Systematic Investigation (LESS-09)
**Don't guess. Follow the evidence.**

```
1. Gather symptoms (error messages, logs, behavior)
2. Form hypotheses (what could cause this?)
3. Test hypotheses (eliminate possibilities)
4. Identify root cause (not just symptoms)
5. Verify fix (test that it actually works)
```

### Principle 2: Known Bugs First
**Always check known bugs before investigating new issues.**

```
Check these first:
- BUG-01: Sentinel leak (535ms penalty)
- BUG-02: _CacheMiss validation failure
- BUG-03: Circular import causing ModuleNotFoundError
- BUG-04: Missing lazy import causing cold start spike
```

### Principle 3: Trace Through Layers
**Follow execution through SUGA layers.**

```
Lambda Handler
  |
Router Layer
  |
Gateway Layer
  |
Interface Layer
  |
Core Layer

Error in which layer?
```

### Principle 4: Measure, Don't Guess (LESS-02)
**Use metrics and logs, not assumptions.**

```
[OK] CloudWatch logs
[OK] performance_benchmark.py
[OK] Debug handlers (debug_diagnostics.py)
[OK] Error traces
[X] Guessing based on "seems like"
```

---

## KNOWN BUGS REFERENCE

### BUG-01: Sentinel Object Leak
**Symptom:** 500 error, JSON serialization failure  
**Root Cause:** _CacheMiss or _NotFound sentinel leaked across boundary  
**Location:** Cache operations, response building  
**Fix:** Sanitize sentinels at router layer before JSON serialization  
**Prevention:** Always check for sentinel objects before returning responses  
**REF:** BUG-01, DEC-05, AP-19  
**Impact:** 535ms performance penalty

### BUG-02: _CacheMiss Validation
**Symptom:** Validation failures on cache misses  
**Root Cause:** _CacheMiss sentinel treated as valid value  
**Location:** Validation logic, cache checks  
**Fix:** Check for _CacheMiss explicitly before validation  
**Prevention:** Sanitize _CacheMiss at cache interface boundary  
**REF:** BUG-02, INT-01

### BUG-03: Circular Import
**Symptom:** ModuleNotFoundError, import fails at runtime  
**Root Cause:** Direct cross-interface import bypassing gateway  
**Location:** Module-level imports, direct core imports  
**Fix:** Use lazy imports, always import via gateway  
**Prevention:** Follow RULE-01, never direct core imports  
**REF:** BUG-03, RULE-01, AP-01

### BUG-04: Cold Start Spike
**Symptom:** First request takes 5+ seconds  
**Root Cause:** Heavy imports at module level, not lazy loaded  
**Location:** Module-level imports, cold path in hot files  
**Fix:** Move cold path imports to lazy load, keep hot path in fast_path.py  
**Prevention:** Use performance_benchmark.py to profile imports  
**REF:** BUG-04, ARCH-07, LESS-02

---

## ERROR PATTERN RECOGNITION

### Pattern 1: JSON Serialization Error
**Error Message:** "Object of type X is not JSON serializable"  
**Likely Cause:** Sentinel leak (BUG-01), internal object in response  
**Investigation:**
```
1. Check response building code
2. Look for _CacheMiss, _NotFound, or custom objects
3. Trace where object enters response
4. Check sanitization at router layer
```

**Quick Fix (as complete file artifact):**
```python
# FIXED: Sanitize sentinels before JSON
if value is _CacheMiss or value is _NotFound:
    value = None  # or default value
```

### Pattern 2: ModuleNotFoundError
**Error Message:** "No module named 'X'"  
**Likely Cause:** Circular import (BUG-03), missing lazy import  
**Investigation:**
```
1. Check import statements in error trace
2. Look for direct core imports
3. Check if importing interface from core
4. Verify lazy imports used
```

**Quick Fix (as complete file artifact):**
```python
# FIXED: Changed to lazy import
# [X] Wrong - module level
# import cache_core

# [OK] Correct - function level
def function():
    import cache_core
    return cache_core.get_impl()
```

### Pattern 3: Lambda Timeout
**Error Message:** "Task timed out after 30.00 seconds"  
**Likely Cause:** Infinite loop, blocking operation, heavy computation  
**Investigation:**
```
1. Check CloudWatch logs for last log entry
2. Identify which function was executing
3. Look for loops, waits, network calls
4. Check if stuck in retry logic
```

**Quick Fix (as complete file artifact):**
```python
# FIXED: Added timeout control
import interface_http
response = interface_http.http_get(url, timeout=5)  # 5 second timeout
```

### Pattern 4: Cold Start Slow
**Error Message:** First request takes 5+ seconds  
**Likely Cause:** Heavy imports (BUG-04), cold path in hot files  
**Investigation:**
```
1. Run performance_benchmark.py
2. Profile import times
3. Identify imports > 100ms
4. Check if imports used on every request
```

**Quick Fix (as complete file artifact):**
```python
# FIXED: Moved to lazy import
# Move heavy imports from module level to function level
def rarely_used_function():
    import heavy_library  # Only import when needed
    return heavy_library.process()
```

### Pattern 5: High Memory Usage
**Error Message:** "Runtime exited with error: signal: killed"  
**Likely Cause:** Memory leak, large data structures, exceeding 128MB  
**Investigation:**
```
1. Check CloudWatch metrics for memory usage
2. Identify large data structures
3. Look for accumulating data (not cleaned up)
4. Check cache size
```

**Quick Fix (as complete file artifact):**
```python
# FIXED: Added memory management
import interface_cache
cache_clear()  # Clear cache when memory high
```

### Pattern 6: WebSocket Disconnect
**Error Message:** Connection closed, WebSocket error  
**Likely Cause:** Timeout, token expiration, network issue  
**Investigation:**
```
1. Check ha_websocket.py connection code
2. Verify token freshness
3. Check keep-alive mechanism
4. Trace disconnect event
```

**Quick Fix (as complete file artifact):**
```python
# FIXED: Added reconnection logic
import interface_websocket
if not websocket.is_connected():
    websocket.reconnect()
```

---

## DEBUG TOOLS REFERENCE

### Tool 1: CloudWatch Logs
**Purpose:** View Lambda execution logs  
**Location:** AWS CloudWatch -> Log Groups -> /aws/lambda/[function-name]  
**What to Look For:**
- Error traces
- Last successful log entry
- Exception messages
- Timing information

### Tool 2: performance_benchmark.py
**Purpose:** Profile import times and function performance  
**Usage:**
```python
import performance_benchmark
benchmark = performance_benchmark.benchmark_imports()
# Shows import times for all modules
```

**What to Look For:**
- Imports > 100ms (move to lazy load)
- Hot path imports (keep in fast_path.py)
- Cold path imports (lazy load)

### Tool 3: debug_diagnostics.py
**Purpose:** Diagnostic handlers for troubleshooting  
**Functions:**
- diagnose_cache()
- diagnose_websocket()
- diagnose_ha_connection()
- get_system_info()

**Usage:**
```python
import debug_diagnostics
result = debug_diagnostics.diagnose_cache()
```

### Tool 4: debug_core.py
**Purpose:** Core debug utilities  
**Functions:**
- trace_execution()
- log_state()
- capture_context()

### Tool 5: Lambda Diagnostic Handler
**File:** lambda_diagnostic.py  
**Purpose:** Special diagnostic event type  
**Usage:** Send event with `"type": "diagnostic"`  
**Returns:** System state, cache contents, configuration

### Tool 6: Debug Mode Gateway Functions
**Functions:**
```python
import gateway
gateway.debug_get_state()      # Get current state
gateway.debug_trace_path()     # Trace execution path
gateway.debug_clear_cache()    # Clear all caches
gateway.debug_health_check()   # Run health diagnostics
```

---

## DEBUG WORKFLOWS

### Workflow 1: Lambda Returning 500 Error

**Step 1: Get Error Details**
```
Brief chat: "Checking logs..."
1. Check CloudWatch logs
2. Find error message and stack trace
3. Identify which function failed
4. Note timestamp and request ID
```

**Step 2: Identify Error Type**
```
- JSON serialization? -> Check Pattern 1 (sentinel leak)
- ModuleNotFoundError? -> Check Pattern 2 (circular import)
- Timeout? -> Check Pattern 3 (blocking operation)
- Other? -> Continue investigation
```

**Step 3: Check Known Bugs**
```
Does symptom match:
- BUG-01: Sentinel leak?
- BUG-02: _CacheMiss validation?
- BUG-03: Circular import?
- BUG-04: Cold start spike?

If yes -> Apply documented fix (output as complete file artifact)
If no -> Continue to Step 4
```

**Step 4: Trace Through Layers**
```
Follow execution:
1. Lambda handler (lambda_function.py)
2. Router layer (event routing)
3. Gateway layer (gateway_wrappers.py)
4. Interface layer (interface_*.py)
5. Core layer (*_core.py)

Where did it fail?
```

**Step 5: Form Hypothesis**
```
Brief chat: "Root cause identified: [brief statement]"

Based on evidence:
- What could cause this error?
- Which code path was executed?
- What was the input?
- What was expected vs actual?
```

**Step 6: Apply Fix (SIMAv4)**
```
Brief chat: "Creating fix artifact..."

1. Fetch complete current file
2. Read entire file
3. Implement fix
4. Mark with # FIXED: comment
5. Output as complete file artifact (not chat, not fragment)
6. Filename in header

Brief chat: "Fix applied. Test with original failing case."
```

**Step 7: Document**
```
If new bug:
- Create BUG-## entry (switch to Learning Mode)
- Document symptoms, root cause, fix
- Add to known bugs reference
```

---

### Workflow 2: Cold Start Taking 5+ Seconds

**Step 1: Measure Current Performance**
```
Brief chat: "Profiling imports..."
1. Use performance_benchmark.py
2. Profile all imports
3. Identify imports > 100ms
4. List total cold start time
```

**Step 2: Categorize Imports**
```
For each heavy import:
- Hot path (used on every request)?
- Cold path (used rarely)?
- Can be lazy loaded?
```

**Step 3: Optimize Hot Path**
```
Hot path imports:
1. Keep in fast_path.py
2. Absolutely essential only
3. Pre-import at module level
4. Minimize count
```

**Step 4: Lazy Load Cold Path (SIMAv4)**
```
Brief chat: "Creating optimized artifacts..."

Cold path imports:
1. Move to function level
2. Import only when needed
3. Document why lazy loaded
4. Output modified files as complete artifacts
5. Filename in header

Brief chat: "Optimization complete. Test cold start."
```

**Step 5: Measure Again**
```
1. Run performance_benchmark.py again
2. Compare to baseline
3. Verify improvement
4. Target: < 3 seconds cold start
```

---

### Workflow 3: WebSocket Connection Failing

**Step 1: Check Connection State**
```
Brief chat: "Diagnosing connection..."
import debug_diagnostics
result = debug_diagnostics.diagnose_websocket()
# Returns connection state, last error, token freshness
```

**Step 2: Verify Configuration**
```
Check:
- Home Assistant URL correct?
- Token valid and not expired?
- Network connectivity?
- Firewall rules?
```

**Step 3: Test Connection**
```
import gateway
test = gateway.websocket_test_connection()
# Returns connection test results
```

**Step 4: Check Logs**
```
CloudWatch logs for:
- Connection attempt messages
- Error messages
- Token validation
- WebSocket handshake
```

**Step 5: Common Fixes (SIMAv4)**
```
Brief chat: "Creating fix artifact for [specific issue]..."

Issue: Token expired
Fix: Refresh token from SSM Parameter Store
Output: Complete modified ha_websocket.py as artifact

Issue: Network timeout
Fix: Check network, increase timeout
Output: Complete modified ha_websocket.py as artifact

Issue: Invalid URL
Fix: Verify HA URL in configuration
Output: Complete modified ha_config.py as artifact

Issue: WebSocket closed
Fix: Implement reconnection logic
Output: Complete modified ha_websocket.py as artifact

Brief chat: "Fix applied. Verify connection."
```

---

### Workflow 4: Cache Miss Rate High (> 50%)

**Step 1: Check Cache Diagnostics**
```
Brief chat: "Analyzing cache..."
import debug_diagnostics
result = debug_diagnostics.diagnose_cache()
# Returns hit rate, miss rate, size, entries
```

**Step 2: Analyze Patterns**
```
Questions:
- What keys are missing most?
- Are keys expiring too soon?
- Is cache clearing too often?
- Memory pressure causing evictions?
```

**Step 3: Review Cache Strategy**
```
Check:
- TTL values appropriate?
- Cache size sufficient?
- Keys consistent?
- Warming strategy needed?
```

**Step 4: Optimize (SIMAv4)**
```
Brief chat: "Creating cache optimization artifacts..."

Possible fixes:
- Increase TTL for stable data -> Output complete cache_core.py
- Increase cache size (if memory allows) -> Output complete cache_core.py
- Add cache warming on cold start -> Output complete fast_path.py
- Improve key consistency -> Output complete cache_core.py

All fixes: Complete file artifacts, never fragments
Filename in header for each

Brief chat: "Cache optimized. Monitor hit rate."
```

---

## DEBUG MODE RED FLAGS

**Don't Fall Into These Traps:**

| Trap | Why Bad | What to Do Instead |
|------|---------|-------------------|
| Guessing without logs | Wastes time | Check CloudWatch logs first |
| Changing multiple things | Can't identify fix | Change one thing, test |
| Skipping known bugs | Reinventing wheel | Check BUG-01 to BUG-04 first |
| Not measuring | No baseline | Use performance_benchmark.py |
| Treating symptoms | Temporary fix | Find root cause |
| Skipping verification | May not be fixed | Test with original failing case |
| [NEW] Code in chat | Token waste, fragments | Complete file artifacts (SIMAv4) |
| [NEW] Fix fragments | Incomplete, not deployable | Complete files only (SIMAv4) |
| [NEW] Verbose explanations | Token waste | Brief analysis (SIMAv4) |
| [NEW] Missing filenames | Organization | Header required (SIMAv4) |

---

## DEBUG MODE SUCCESS METRICS

**Effectiveness Indicators:**
- [OK] Root cause identified (not just symptoms)
- [OK] Fix verified with original failing case
- [OK] Performance measured before/after
- [OK] Similar issues prevented (documented pattern)
- [OK] No regression (other functionality still works)
- [OK] [NEW] Fix code in complete file artifacts (SIMAv4)
- [OK] [NEW] No code output in chat (SIMAv4)
- [OK] [NEW] Filename in every artifact header (SIMAv4)
- [OK] [NEW] Chat output minimal (SIMAv4)

**Time Expectations:**
- Known bug: 5-10 minutes (apply documented fix)
- Simple error: 15-30 minutes
- Complex issue: 45-90 minutes
- Performance optimization: 60-120 minutes

**Outputs:**
- Root cause analysis (brief)
- [NEW] Fix as complete file artifact (not chat, not fragment) (SIMAv4)
- Verification test results
- Documentation (if new bug)
- Prevention strategy

---

## DEBUG MODE BEST PRACTICES

### Do's

**[OK] DO: Check known bugs first**
- BUG-01 to BUG-04
- Saves 30-60 minutes
- Documented fixes ready

**[OK] DO: Use systematic process**
- Gather symptoms
- Form hypotheses
- Test hypotheses
- Find root cause

**[OK] DO: Measure everything**
- CloudWatch logs
- performance_benchmark.py
- Debug diagnostics
- Before/after metrics

**[OK] DO: Trace through layers**
- Handler -> Router -> Gateway -> Interface -> Core
- Identify failure layer
- Understand execution path

**[OK] DO: Verify fixes**
- Test with original failing case
- Test edge cases
- Check for regressions

**[OK] DO: Output complete file artifacts (SIMAv4)**
- Fetch current file first
- Include ALL existing code
- Mark changes with # FIXED:
- Filename in header
- Never fragments
- Never code in chat

**[OK] DO: Keep chat brief (SIMAv4)**
- Root cause statement (2-3 sentences)
- "Creating fix artifact..."
- Brief summary after artifact
- No long narratives

### Don'ts

**[X] DON'T: Guess without evidence**
- Always check logs
- Use debug tools
- Measure performance

**[X] DON'T: Treat symptoms**
- Find root cause
- Fix underlying issue
- Prevent recurrence

**[X] DON'T: Change multiple things**
- Change one variable
- Test result
- Iterate

**[X] DON'T: Skip verification**
- Test fix works
- Verify no regressions
- Document results

**[X] DON'T: Forget to document**
- If new bug, create BUG-##
- Add to known bugs
- Help future debugging

**[X] DON'T: Output code in chat (SIMAv4)**
- Always use artifacts
- Complete files only
- Mark changes clearly

**[X] DON'T: Output fragments (SIMAv4)**
- Include ALL existing code
- Make deployable
- User shouldn't need to edit

**[X] DON'T: Be verbose (SIMAv4)**
- Brief analysis only
- No long explanations
- Let artifacts show fixes

---

## GETTING STARTED

### First Debug Session

**Step 1: Activate Mode**
```
[Upload File Server URLs.md or SERVER-CONFIG.md]
Say: "Start Debug Mode"
Wait for context load (30-45s)
```

**Step 2: Describe Problem (Brief)**
```
Include:
- Symptom (what's wrong?)
- Error messages (if any)
- When it happens (always? intermittent?)
- Recent changes (if any)
- Request/event that triggers it
```

**Step 3: Claude Investigates**
```
Brief chat: "Investigating..."
Claude will:
1. Check known bugs (BUG-01 to BUG-04)
2. Match error patterns
3. Use debug tools
4. Trace through layers
5. Form hypotheses
6. Identify root cause
Brief chat: "Root cause: [statement]"
```

**Step 4: Claude Provides Fix (SIMAv4)**
```
Brief chat: "Creating fix artifact..."
Claude will:
1. Explain root cause (brief)
2. Fetch complete current file(s)
3. Implement fix in complete files
4. Mark changes with # FIXED: comments
5. Output as complete file artifacts (never chat, never fragments)
6. Filename in header for each
7. Suggest verification test
8. Recommend prevention
Brief chat: "Fix complete. Test with failing case."
```

**Step 5: Verify and Document**
```
You:
1. Apply fix from artifact
2. Test with failing case
3. Verify fix works
4. Check no regressions
5. Document if new bug
```

---

## ACTIVATION CHECKLIST

### Ready for Debug Mode When:

- [OK] This file loaded (30-45s)
- [OK] Known bugs memorized (BUG-01 to BUG-04)
- [OK] Error patterns recognized
- [OK] Debug tools understood
- [OK] Systematic process clear
- [OK] Problem clearly described
- [OK] [NEW] Artifact rules understood (SIMAv4)
- [OK] [NEW] Chat brevity understood (SIMAv4)

### What Happens Next:

```
1. User describes problem (brief)
2. Claude checks known bugs
3. Claude uses debug tools (brief chat)
4. Claude traces execution
5. Claude identifies root cause (brief chat)
6. Claude provides fix as complete file artifact (filename in header)
7. User verifies fix works
```

---

## REMEMBER

**Debug Mode Purpose:**  
Find root cause -> Systematic investigation -> Verified fix -> Prevention

**Critical Principles:**
1. **Check known bugs first** (BUG-01 to BUG-04)
2. **Measure, don't guess** (LESS-02)
3. **Trace through layers** (SUGA pattern)
4. **Verify fixes** (test thoroughly)
5. **[NEW] Complete file artifacts** (never chat, never fragments) (SIMAv4)
6. **[NEW] Brief chat** (status only) (SIMAv4)

**Success = Problem solved, root cause fixed, recurrence prevented, fix deployable**

---

**END OF DEBUG MODE CONTEXT**

**Version:** 1.1.0 (SIMAv4 standards integrated)  
**Lines:** 390 (within SIMAv4 limit)  
**Load Time:** 30-45 seconds  
**Purpose:** Troubleshooting and diagnostics  
**Output:** Root cause analysis, verified fixes in complete artifacts, prevention strategies  
**[NEW] Fix:** SIMAv4 compliance (minimal chat, headers, encoding)

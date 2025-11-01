# File: INT-08-Debug-Interface.md

**REF-ID:** INT-08  
**Version:** 1.0.0  
**Category:** Interface Pattern  
**Status:** Active  
**Priority:** 🟢 MEDIUM  
**Created:** 2025-11-01  
**Contributors:** SIMA Learning Mode

---

## 📋 OVERVIEW

**Interface Name:** DEBUG  
**Short Code:** DBG  
**Type:** Diagnostic Interface  
**Dependency Layer:** Layer 1 (Core Services)

**One-Line Description:**  
DEBUG interface provides diagnostic tools, traces, and debugging utilities for troubleshooting.

**Primary Purpose:**  
Enable rapid debugging in production with request tracing, state inspection, and diagnostic data collection.

---

## 🎯 CORE RESPONSIBILITIES

### 1. Request Tracing
- Generate unique trace IDs
- Track request flow through system
- Correlate logs across components
- Measure operation timing

### 2. State Inspection
- Dump current state
- Inspect variable values
- Check configuration
- Validate assumptions

### 3. Diagnostic Data
- Collect system information
- Capture error context
- Generate debug reports
- Export for analysis

### 4. Debug Controls
- Enable/disable debug mode
- Control verbosity level
- Conditional breakpoints
- Feature toggles

---

## 🔑 KEY RULES

### Rule 1: Never Enable Debug in Production by Default
**What:** Debug features MUST be off by default. Enable only when troubleshooting.

**Why:** Debug overhead impacts performance. Sensitive data may leak to logs.

**Control Methods:**
```python
# Via environment variable
DEBUG_MODE = get_config("DEBUG_MODE", type=bool, default=False)

# Via request header (for specific requests)
debug_enabled = event.get("headers", {}).get("X-Debug-Mode") == "true"

# Via feature flag (gradual rollout)
debug_enabled = is_feature_enabled("debug_mode")
```

---

### Rule 2: Use Trace IDs for Correlation
**What:** Generate unique trace ID for each request. Include in all logs.

**Why:** Makes it easy to find all logs for a specific request.

**Example:**
```python
from gateway import generate_trace_id, set_trace_context, log_info

def lambda_handler(event, context):
    # Generate or extract trace ID
    trace_id = event.get("headers", {}).get("X-Trace-Id") or generate_trace_id()
    
    # Set context (all logs will include trace_id)
    set_trace_context(trace_id=trace_id)
    
    log_info("Request received")  # Log includes trace_id
    result = process(event)       # All logs include trace_id
    log_info("Request completed") # Log includes trace_id
    
    return {
        "statusCode": 200,
        "headers": {"X-Trace-Id": trace_id},
        "body": result
    }
```

---

### Rule 3: Sanitize Debug Output
**What:** Never log sensitive data (passwords, keys, PII) in debug output.

**Why:** Debug logs often have wider visibility. Compliance requirements.

**Example:**
```python
from gateway import debug_dump, sanitize_sensitive_data

# ❌ DON'T: Log raw data
debug_dump(user_data)  # Might contain password, SSN, etc.

# ✅ DO: Sanitize first
safe_data = sanitize_sensitive_data(user_data, keys=["password", "ssn", "api_key"])
debug_dump(safe_data)
```

---

### Rule 4: Measure Debug Overhead
**What:** Track performance impact of debug features.

**Why:** Know the cost of debugging. Prevent production slowdown.

**Example:**
```python
from gateway import debug_timer, record_metric

with debug_timer("DebugOperation"):
    expensive_debug_operation()

# Metric shows debug cost
# Can alert if debug overhead > 100ms
```

---

### Rule 5: Use Levels for Verbosity
**What:** Support multiple debug levels (basic, verbose, trace).

**Levels:**
- **Level 0 (Off):** No debug output
- **Level 1 (Basic):** High-level flow (requests, responses)
- **Level 2 (Verbose):** Detailed operations (function calls, state changes)
- **Level 3 (Trace):** Everything (variable values, loop iterations)

**Example:**
```python
from gateway import get_debug_level, debug_log

DEBUG_LEVEL = get_debug_level()

def process_items(items):
    if DEBUG_LEVEL >= 1:
        debug_log(f"Processing {len(items)} items")
    
    for i, item in enumerate(items):
        if DEBUG_LEVEL >= 2:
            debug_log(f"Processing item {i}: {item.id}")
        
        if DEBUG_LEVEL >= 3:
            debug_log(f"Item details: {item}")
        
        process(item)
```

---

## 🎨 MAJOR BENEFITS

### Benefit 1: Faster Debugging
- Trace specific requests
- Correlate logs easily
- Inspect state on demand
- No code changes needed

### Benefit 2: Production Visibility
- Debug live issues
- Understand actual behavior
- Validate assumptions
- Capture rare conditions

### Benefit 3: Minimal Performance Impact
- Off by default
- Selective enablement
- Measured overhead
- No production slowdown

### Benefit 4: Security
- Sensitive data sanitized
- Access controlled
- Audit trail for debug usage
- Automatic timeout

---

## 📚 CORE FUNCTIONS

### Gateway Functions

```python
# Trace IDs
generate_trace_id()
get_current_trace_id()
set_trace_context(trace_id, **kwargs)
clear_trace_context()

# Debug controls
enable_debug_mode(duration_seconds=None)
disable_debug_mode()
is_debug_enabled()
get_debug_level()
set_debug_level(level)

# Logging
debug_log(message, level=1)
debug_dump(obj, label=None, sanitize=True)
debug_stack_trace()

# Timing
debug_timer(operation_name)
debug_checkpoint(name)
get_debug_timings()

# State inspection
debug_inspect_vars(local_vars)
debug_memory_usage()
debug_config_dump()
debug_environment_dump()

# Diagnostic reports
generate_debug_report()
export_debug_data(trace_id)

# Utilities
debug_assert(condition, message)
debug_breakpoint(condition=True)
```

---

## 🔄 USAGE PATTERNS

### Pattern 1: Basic Request Tracing
```python
from gateway import generate_trace_id, set_trace_context, log_info

def lambda_handler(event, context):
    # Setup trace
    trace_id = generate_trace_id()
    set_trace_context(trace_id=trace_id)
    
    # All logs include trace_id automatically
    log_info("Processing request")
    
    result = process_request(event)
    
    log_info("Request complete")
    
    return {
        "statusCode": 200,
        "headers": {"X-Trace-Id": trace_id},
        "body": result
    }
```

### Pattern 2: Conditional Debug Output
```python
from gateway import is_debug_enabled, debug_log, debug_dump

def process_complex_data(data):
    if is_debug_enabled():
        debug_log("Starting complex processing")
        debug_dump(data, label="Input data")
    
    result = complex_operation(data)
    
    if is_debug_enabled():
        debug_dump(result, label="Output data")
    
    return result
```

### Pattern 3: Performance Checkpoints
```python
from gateway import debug_checkpoint, get_debug_timings

def multi_step_operation():
    debug_checkpoint("start")
    
    step1_result = step_one()
    debug_checkpoint("step1_complete")
    
    step2_result = step_two()
    debug_checkpoint("step2_complete")
    
    step3_result = step_three()
    debug_checkpoint("step3_complete")
    
    timings = get_debug_timings()
    # timings = {
    #     "step1_complete": 150ms,
    #     "step2_complete": 200ms,
    #     "step3_complete": 100ms
    # }
```

### Pattern 4: Debug Report Generation
```python
from gateway import generate_debug_report, log_error

def lambda_handler(event, context):
    try:
        result = process(event)
        return success_response(result)
        
    except Exception as e:
        # Generate comprehensive debug report
        report = generate_debug_report()
        
        log_error(f"Error occurred", extra={
            "error": str(e),
            "trace_id": report["trace_id"],
            "timings": report["timings"],
            "memory": report["memory_usage"],
            "environment": report["environment"]
        })
        
        return error_response(e, trace_id=report["trace_id"])
```

### Pattern 5: Selective Debug Enablement
```python
from gateway import enable_debug_mode, disable_debug_mode

def lambda_handler(event, context):
    # Enable debug for specific user or condition
    debug_user_id = event.get("headers", {}).get("X-Debug-User-Id")
    
    if debug_user_id == "admin@example.com":
        enable_debug_mode(duration_seconds=300)  # 5 minutes
    
    try:
        result = process(event)
        return success_response(result)
    finally:
        disable_debug_mode()
```

---

## ⚠️ ANTI-PATTERNS

### Anti-Pattern 1: Debug Always On ❌
```python
# ❌ DON'T: Debug always enabled
DEBUG_MODE = True  # Hardcoded

def process(data):
    if DEBUG_MODE:
        expensive_debug_operation()  # Always runs!

# ✅ DO: Debug controlled by config/flag
DEBUG_MODE = get_config("DEBUG_MODE", type=bool, default=False)
```

### Anti-Pattern 2: Logging Sensitive Data ❌
```python
# ❌ DON'T: Log raw user data
debug_dump(user_data)  # Contains password, SSN, etc.

# ✅ DO: Sanitize first
safe_data = sanitize_sensitive_data(user_data)
debug_dump(safe_data)
```

### Anti-Pattern 3: No Trace Correlation ❌
```python
# ❌ DON'T: Generic logs
log_info("Processing request")  # Which request?
log_info("Error occurred")      # For which request?

# ✅ DO: Include trace ID
set_trace_context(trace_id="abc123")
log_info("Processing request")  # Log includes trace_id=abc123
```

### Anti-Pattern 4: Unmeasured Debug Cost ❌
```python
# ❌ DON'T: Unknown overhead
def expensive_debug():
    for i in range(10000):
        debug_operation()

# ✅ DO: Measure and limit
with debug_timer("ExpensiveDebug"):
    for i in range(100):  # Limited iterations
        debug_operation()
```

---

## 🔗 CROSS-REFERENCES

**Related Architecture:**
- ARCH-01 (SUGA): Debug is Layer 1
- ARCH-03 (DD): Debug dispatch operations

**Related Interfaces:**
- INT-02 (Logging): Debug uses logging
- INT-06 (Config): Debug controlled by config
- INT-07 (Metrics): Track debug overhead
- INT-03 (Security): Sanitize debug output

**Related Patterns:**
- GATE-02 (Lazy Loading): Load debug tools lazily
- GATE-04 (Wrapper): Debug wrappers

**Related Lessons:**
- LESS-21 (Debugging): Debug strategies
- LESS-27 (Tracing): Request correlation
- LESS-36 (Security): Sanitize output

**Related Decisions:**
- DEC-10 (Debug Strategy): Off by default
- DEC-17 (Trace IDs): UUID v4 format

---

## ✅ VERIFICATION CHECKLIST

Before deploying debug code:
- [ ] Debug off by default
- [ ] Trace IDs generated
- [ ] Sensitive data sanitized
- [ ] Debug overhead measured
- [ ] Access controlled
- [ ] Automatic timeout
- [ ] Logging correlation
- [ ] Error context captured
- [ ] Debug reports generated
- [ ] Documentation updated

---

## 🛠️ DEBUG TOOLS

### Trace Tools
```python
trace_id = generate_trace_id()
set_trace_context(trace_id=trace_id, user_id="123")
```

### State Tools
```python
debug_inspect_vars(locals())
debug_memory_usage()
debug_config_dump()
```

### Timing Tools
```python
with debug_timer("Operation"):
    expensive_operation()

debug_checkpoint("milestone")
timings = get_debug_timings()
```

### Report Tools
```python
report = generate_debug_report()
export_debug_data(trace_id="abc123")
```

---

**END OF INTERFACE ENTRY**

**REF-ID:** INT-08  
**Status:** Active  
**Lines:** 385
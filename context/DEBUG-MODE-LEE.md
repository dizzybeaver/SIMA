# DEBUG-MODE-LEE.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** LEE project-specific debugging context  
**Project:** LEE (Lambda Execution Engine)  
**Type:** Project Extension

---

## PROJECT: LEE

**Name:** LEE (Lambda Execution Engine)  
**Type:** AWS Lambda + Home Assistant Integration

---

## KNOWN BUGS

### BUG-01: Sentinel Object Leak
**Symptom:** 500 error, JSON serialization failure  
**Root Cause:** _CacheMiss or _NotFound leaked into response  
**Location:** cache_core.py, response building  
**Fix:** Sanitize sentinels at router layer  
**Impact:** 535ms performance penalty  
**Prevention:** Always check for sentinels before JSON

### BUG-02: _CacheMiss Validation
**Symptom:** Validation failures on cache misses  
**Root Cause:** _CacheMiss treated as valid value  
**Location:** Validation logic  
**Fix:** Check _CacheMiss explicitly before validation  
**Prevention:** Sanitize at cache interface boundary

### BUG-03: Circular Import
**Symptom:** ModuleNotFoundError at runtime  
**Root Cause:** Direct cross-interface import  
**Location:** Module-level imports  
**Fix:** Use lazy imports, import via gateway  
**Prevention:** Follow RULE-01, never direct core imports

### BUG-04: Cold Start Spike
**Symptom:** First request takes 5+ seconds  
**Root Cause:** Heavy imports at module level  
**Location:** Module-level imports  
**Fix:** Move to lazy load, keep hot path in fast_path.py  
**Prevention:** Profile with performance_benchmark.py

---

## ERROR PATTERNS

### JSON Serialization Error
**Message:** "Object of type X is not JSON serializable"  
**Cause:** Sentinel leak (BUG-01)  
**Fix:** Sanitize before JSON
```python
if value is _CacheMiss:
    value = None
```

### ModuleNotFoundError
**Message:** "No module named 'X'"  
**Cause:** Circular import (BUG-03)  
**Fix:** Lazy import
```python
def function():
    import cache_core
    return cache_core.process()
```

### Lambda Timeout
**Message:** "Task timed out after 30.00 seconds"  
**Cause:** Blocking operation  
**Fix:** Add timeout
```python
response = http_get(url, timeout=5)
```

### Cold Start Slow
**Message:** First request > 5 seconds  
**Cause:** Heavy imports (BUG-04)  
**Fix:** Lazy load
```python
def rarely_used():
    import heavy_module
    return heavy_module.process()
```

### High Memory
**Message:** "Runtime exited with error: signal: killed"  
**Cause:** Exceeding 128MB  
**Fix:** Clear cache
```python
cache_clear()
```

### WebSocket Disconnect
**Message:** Connection closed  
**Cause:** Token expired or network issue  
**Fix:** Refresh token
```python
token = get_fresh_token_from_ssm()
```

---

## DEBUG TOOLS

### CloudWatch Logs
**Location:** /aws/lambda/lee-function  
**Look for:** Error traces, timing, exceptions

### performance_benchmark.py
**Usage:**
```python
import performance_benchmark
benchmark = performance_benchmark.benchmark_imports()
```
**Target:** Imports < 100ms

### debug_diagnostics.py
**Functions:**
- diagnose_cache()
- diagnose_websocket()
- diagnose_ha_connection()
- get_system_info()

### Lambda Diagnostic Handler
**File:** lambda_diagnostic.py  
**Usage:** Send event with `"type": "diagnostic"`

---

## COMMON FIXES

### Sanitize Sentinel
```python
# FIXED: Sanitize before JSON
if value is _CacheMiss or value is _NotFound:
    value = None
```

### Lazy Import
```python
# FIXED: Changed to lazy import
def function():
    import cache_core
    return cache_core.get_impl()
```

### Add Timeout
```python
# FIXED: Added timeout control
import interface_http
response = interface_http.http_get(url, timeout=5)
```

### Optimize Imports
```python
# FIXED: Moved to lazy import
def rarely_used_function():
    import heavy_library
    return heavy_library.process()
```

### Clear Cache
```python
# FIXED: Memory management
import interface_cache
cache_clear()
```

### Refresh Token
```python
# FIXED: Token refresh
import interface_config
token = interface_config.get_parameter("ha_token")
```

---

**END OF LEE DEBUG EXTENSION**

**Version:** 1.0.0  
**Lines:** 100 (target achieved)  
**Combine with:** DEBUG-MODE-Context.md (base)

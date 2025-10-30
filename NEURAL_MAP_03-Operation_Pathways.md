# NEURAL_MAP_03: Operation Pathways
# SUGA-ISP Neural Memory System - Data Flow & Error Propagation
# Version: 1.1.0 | Phase: 1 Foundation | Enhanced with REF IDs

---

**FILE STATISTICS:**
- Sections: 12 (3 flow patterns + 3 error paths + 3 traces + 3 transformations)
- Reference IDs: 12
- Cross-references: 35+
- Priority Breakdown: Critical=3, High=6, Medium=3
- Last Updated: 2025-10-20
- Version: 1.1.0 (Enhanced with REF IDs)

---

## Purpose

This file documents HOW THINGS FLOW in the SUGA-ISP architecture - operation execution paths, error propagation, data transformations, and the "nervous system" of request handling.

---

## PART 1: OPERATION FLOW PATTERNS

### Pattern 1: Simple Operation (cache_get)
**REF:** NM03-FLOW-01
**PRIORITY:** 🔴 CRITICAL
**TAGS:** flow, operation, cache_get, simple-operation, pathway
**KEYWORDS:** cache get flow, simple operation flow, basic pathway
**RELATED:** NM01-INT-01, NM01-ARCH-02, NM04-DEC-03

```
User Code: cache_get("my_key")
    ↓
gateway_wrappers.cache_get(key)
    ↓ calls
gateway_core.execute_operation(CACHE, 'get', key=key)
    ↓ looks up in _INTERFACE_ROUTERS
    ↓ lazy imports interface_cache
interface_cache.execute_cache_operation('get', key=key)
    ↓ looks up in _OPERATION_DISPATCH
    ↓ calls
cache_core._execute_get_implementation(key=key)
    ↓ accesses _CACHE_STORE
    ↓ returns value or None
    ↓ (sanitizes sentinel if present)
interface_cache (router) returns value
    ↓
gateway_core returns value
    ↓
gateway_wrappers returns value
    ↓
User Code receives value

Total Hops: 5 function calls
Latency: Minimal (all in-memory, O(1) lookups)
```

**REAL-WORLD USAGE:**
User: "How does cache_get work internally?"
Claude searches: "cache get flow operation"
Finds: NM03-FLOW-01
Response: "cache_get flows through 5 layers: wrapper → gateway → router → core → storage, all O(1) operations."

---

### Pattern 2: Cross-Interface Operation (HTTP with logging)
**REF:** NM03-FLOW-02
**PRIORITY:** 🔴 CRITICAL
**TAGS:** flow, cross-interface, HTTP, logging, complex-operation
**KEYWORDS:** HTTP flow, cross interface flow, logging flow, complex operation
**RELATED:** NM01-INT-08, NM02-HTTP-DEP, NM04-DEC-01

```
User Code: http_post(url, data)
    ↓
gateway_wrappers.http_post(url, data)
    ↓
gateway_core.execute_operation(HTTP_CLIENT, 'post', url=url, data=data)
    ↓
interface_http.execute_http_operation('post', url=url, data=data)
    ↓
http_client_core._execute_post_implementation(url, data)
    ├─→ gateway.log_info(f"POST {url}")  ← Cross-interface call
    │       ↓
    │   gateway_core.execute_operation(LOGGING, 'log_info', ...)
    │       ↓
    │   interface_logging.execute_logging_operation('log_info', ...)
    │       ↓
    │   logging_core._execute_log_info(...)
    │       ↓ print() to stdout
    │   Returns to http_client_core
    │
    ├─→ gateway.validate_url(url)  ← Cross-interface call
    │       ↓ (SECURITY interface)
    │   Returns validation result
    │
    ├─→ Make HTTP request (urllib)
    │
    ├─→ gateway.cache_set(f"http_{url}", response)  ← Cross-interface call
    │       ↓ (CACHE interface)
    │   Caches response
    │
    └─→ gateway.record_api_metric("post", duration)  ← Cross-interface call
            ↓ (METRICS interface)
        Records metric

Returns response to user

Total Hops: 5 main + 4 cross-interface calls
Cross-Interface Interactions: 4 (LOGGING, SECURITY, CACHE, METRICS)
```

**REAL-WORLD USAGE:**
User: "What happens when I call http_post?"
Claude searches: "HTTP post flow cross interface"
Finds: NM03-FLOW-02
Response: "http_post makes 4 cross-interface calls: logs request, validates URL, caches response, records metrics."

---

### Pattern 3: Extension Operation (Alexa request)
**REF:** NM03-FLOW-03
**PRIORITY:** 🟡 HIGH
**TAGS:** flow, extension, alexa, end-to-end, home-assistant
**KEYWORDS:** alexa flow, extension flow, end to end flow, lambda flow
**RELATED:** NM01-ARCH-05, NM01-ARCH-06, NM04-DEC-17

```
AWS Lambda Trigger
    ↓
lambda_function.lambda_handler(event, context)
    ├─→ gateway.initialize_system()  ← Initialization
    │       ↓ (INITIALIZATION interface)
    │   System initialized
    │
    └─→ homeassistant_extension.process_alexa_request(event)
            ↓
        ha_core.handle_alexa_request(event)
            ├─→ gateway.log_info("Processing Alexa")  ← LOGGING
            ├─→ gateway.cache_get("ha_state")  ← CACHE
            │       ↓ Cache miss
            │   Returns None
            │
            ├─→ gateway.http_post(ha_url, request)  ← HTTP_CLIENT
            │       ├─→ Makes request to Home Assistant
            │       ├─→ Logs request (LOGGING)
            │       ├─→ Validates URL (SECURITY)
            │       └─→ Records metric (METRICS)
            │   Returns HA response
            │
            ├─→ gateway.cache_set("ha_state", response)  ← CACHE
            │
            └─→ Format Alexa response
        
        Returns Alexa response

Total Flow: Lambda → Extension → Gateway (multiple interfaces)
Cross-Interface Calls: ~6 (INIT, LOGGING×2, CACHE×2, HTTP_CLIENT)
```

---

## PART 2: ERROR PROPAGATION PATHS

### Error Path 1: Implementation Error
**REF:** NM03-PATH-01
**PRIORITY:** 🔴 CRITICAL
**TAGS:** error, propagation, implementation-error, exception-handling
**KEYWORDS:** implementation error, error propagation, exception path
**RELATED:** NM04-DEC-15, NM07-DT-05, NM05-AP-14

```
ERROR ORIGIN: cache_core._execute_get_implementation()
    ↓ Exception raised (e.g., KeyError)
    ↓
CAUGHT BY: interface_cache.execute_cache_operation() [try/except]
    ↓ Logs error via gateway.log_error()
    ↓ Re-raises exception OR returns error dict
    ↓
RECEIVED BY: gateway_core.execute_operation()
    ↓ May catch and wrap in error response
    ↓
RETURNED TO: gateway_wrappers.cache_get()
    ↓ Returns None or error value
    ↓
RECEIVED BY: User code
    ↓ Handles error response

Error Handling Layers:
1. Implementation (core) - Raises exception
2. Router (interface) - Catches, logs, re-raises
3. Gateway core - May wrap in error response
4. Gateway wrapper - Returns error value to user
5. User code - Final error handling

Key Point: Errors are logged at router layer via gateway.log_error
```

**REAL-WORLD USAGE:**
User: "Where are exceptions caught and logged?"
Claude searches: "error propagation exception handling"
Finds: NM03-PATH-01
Response: "Exceptions caught at router layer with try/except, logged via gateway.log_error, then re-raised or wrapped."

---

### Error Path 2: Cross-Interface Error Cascade
**REF:** NM03-PATH-02
**PRIORITY:** 🟡 HIGH
**TAGS:** error, cross-interface, cascade, error-flow
**KEYWORDS:** cross interface error, error cascade, nested errors
**RELATED:** NM03-FLOW-02, NM04-DEC-15

```
ERROR ORIGIN: http_client_core._execute_post_implementation()
    ↓ urllib raises exception (network error)
    ↓
CROSS-INTERFACE LOG: gateway.log_error() called
    ↓ Routes through gateway_core
    ↓ Reaches interface_logging
    ↓ Logs error successfully
    ↓ Returns to http_client_core
    ↓
MAIN ERROR PATH: Exception continues in http_client_core
    ↓ Propagates to interface_http (router)
    ↓
CAUGHT BY: interface_http.execute_http_operation() [try/except]
    ↓ Already logged, just re-raises
    ↓
RETURNED TO: gateway_core.execute_operation()
    ↓ Wraps in error response
    ↓
FINAL RETURN: Error response to user

Error Handling Points:
1. Network error occurs
2. Logged immediately via cross-interface call
3. Error propagates up the stack
4. Router catches and may log again
5. Gateway wraps in error response
6. User receives structured error

Key Point: Logging happens DURING error, not just at final catch
```

---

### Error Path 3: Router Import Error Protection
**REF:** NM03-PATH-03
**PRIORITY:** 🟡 HIGH
**TAGS:** error, import-error, protection, graceful-degradation
**KEYWORDS:** import error protection, graceful degradation, router protection
**RELATED:** NM04-DEC-16, NM06-BUG-04

```
SCENARIO: interface_cache.py tries to import cache_core.py
    ↓
TRY: from cache_core import _execute_get_implementation
    ↓ ImportError raised (module missing/broken)
    ↓
CAUGHT BY: Try/except block in interface_cache.py
    ↓ Sets _CACHE_AVAILABLE = False
    ↓ Stores error message
    ↓
LATER: execute_cache_operation() called
    ↓ Checks _CACHE_AVAILABLE
    ↓ If False, returns error immediately
    ↓
ERROR RESPONSE: "Cache interface unavailable: [import error]"
    ↓
SYSTEM: Continues running (other interfaces unaffected)

Protection Benefits:
1. Missing implementation doesn't crash Lambda
2. Clear error message identifies problem
3. Other interfaces continue functioning
4. Graceful degradation

Key Point: Import errors caught at module load, not execution time
```

---

## PART 3: COMPLETE OPERATION TRACES

### Trace 1: cache_get() with Cache Hit
**REF:** NM03-TRACE-01
**PRIORITY:** 🟡 HIGH
**TAGS:** trace, cache-get, cache-hit, complete-flow
**KEYWORDS:** cache get trace, cache hit flow, complete trace
**RELATED:** NM03-FLOW-01, NM01-INT-01

```
STEP 1: User calls gateway.cache_get("user_data")
    Entry: gateway_wrappers.py:cache_get()
    
STEP 2: Wrapper calls execute_operation
    Call: execute_operation(CACHE, 'get', key="user_data", default=None)
    Entry: gateway_core.py:execute_operation()
    
STEP 3: Gateway looks up interface router
    Lookup: _INTERFACE_ROUTERS[CACHE] → ('interface_cache', 'execute_cache_operation')
    Import: Lazy import of interface_cache module
    
STEP 4: Gateway calls router
    Call: interface_cache.execute_cache_operation('get', key="user_data", default=None)
    Entry: interface_cache.py:execute_cache_operation()
    
STEP 5: Router dispatches to implementation
    Lookup: _OPERATION_DISPATCH['get'] → _execute_get_implementation
    Call: cache_core._execute_get_implementation(key="user_data", default=None)
    Entry: cache_core.py:_execute_get_implementation()
    
STEP 6: Implementation accesses cache
    Access: _CACHE_STORE["user_data"]
    Check: Key exists, value is not expired
    Result: {"name": "John", "age": 30}
    
STEP 7: Router sanitizes return value
    Check: Is value a sentinel object? → No
    Return: {"name": "John", "age": 30}
    
STEP 8: Gateway returns to wrapper
    Return: {"name": "John", "age": 30}
    
STEP 9: Wrapper returns to user
    Return: {"name": "John", "age": 30}
    
STEP 10: User receives data
    Result: user_data = {"name": "John", "age": 30}

Total Execution Time: ~0.1ms (in-memory, all lookups O(1))
Memory Allocations: Minimal (return existing object reference)
```

---

### Trace 2: cache_get() with Cache Miss
**REF:** NM03-TRACE-02
**PRIORITY:** 🟡 HIGH
**TAGS:** trace, cache-get, cache-miss, sentinel
**KEYWORDS:** cache miss trace, sentinel sanitization, cache miss flow
**RELATED:** NM03-TRACE-01, NM04-DEC-05, NM06-BUG-01

```
STEP 1-5: Same as Trace 1 (up to implementation)

STEP 6: Implementation accesses cache
    Access: _CACHE_STORE["user_data"]
    Check: Key does not exist
    Result: _CACHE_MISS sentinel object
    
STEP 7: Router sanitizes return value
    Check: Is value a sentinel object? → YES
    Sanitize: Convert _CACHE_MISS to None
    Return: None
    
STEP 8-9: Same as Trace 1

STEP 10: User receives data
    Result: user_data = None
    User code: if user_data is None: fetch_from_db()

Key Difference: Sentinel sanitization prevents sentinel leak
Benefit: User can safely check "if cached is not None"
```

---

### Trace 3: http_post() with Full Cross-Interface Interactions
**REF:** NM03-TRACE-03
**PRIORITY:** 🟡 HIGH
**TAGS:** trace, HTTP, cross-interface, complete-flow, complex
**KEYWORDS:** HTTP post trace, cross interface trace, complete HTTP flow
**RELATED:** NM03-FLOW-02, NM01-INT-08

```
STEP 1: User calls gateway.http_post("https://api.example.com/data", {"key": "value"})

STEP 2-4: Gateway routing (same pattern as cache_get)
    → interface_http.execute_http_operation('post', ...)

STEP 5: HTTP implementation begins
    Entry: http_client_core._execute_post_implementation(url, data)
    
STEP 6: Log request start
    Call: gateway.log_info(f"HTTP POST: {url}")
    → Flows through gateway → interface_logging → logging_core
    → print() to CloudWatch
    Returns to http_client_core
    
STEP 7: Validate URL
    Call: gateway.validate_url(url)
    → Flows through gateway → interface_security → security_core
    → Validates URL format
    Returns True to http_client_core
    
STEP 8: Check cache for cached response (if GET)
    [Skipped for POST - not cached]
    
STEP 9: Make HTTP request
    Action: urllib.request.urlopen(url, data)
    Duration: ~200ms (network call)
    Response: {"status": "success", "data": [...]}
    
STEP 10: Cache response (if GET)
    [Skipped for POST]
    
STEP 11: Record metric
    Call: gateway.record_api_metric("post", duration_ms=205.3)
    → Flows through gateway → interface_metrics → metrics_core
    → Increments POST counter, records duration
    Returns to http_client_core
    
STEP 12: Log success
    Call: gateway.log_info(f"HTTP POST success: {url}")
    → Same flow as STEP 6
    
STEP 13: Return response
    Return: {"status": "success", "data": [...]}
    → Flows back through interface_http → gateway_core → gateway_wrappers
    → Returns to user

Total Execution Time: ~210ms (mostly network call)
Cross-Interface Calls: 4 (LOGGING×2, SECURITY×1, METRICS×1)
Gateway Hops: 5 main + 12 for cross-interface calls = 17 total hops
```

---

## PART 4: DATA TRANSFORMATION PATHWAYS

### Transformation 1: Sentinel Sanitization (Cache)
**REF:** NM03-TRANSFORM-01
**PRIORITY:** 🟡 HIGH
**TAGS:** transformation, sentinel, sanitization, cache
**KEYWORDS:** sentinel sanitization, data transformation, cache miss
**RELATED:** NM04-DEC-05, NM06-BUG-01, NM06-LESS-05

```
INPUT: Cache miss detection
    ↓
cache_core._execute_get_implementation()
    Key not found in _CACHE_STORE
    Return: _CACHE_MISS (object() sentinel)
    ↓
interface_cache.execute_cache_operation()
    Receives: _CACHE_MISS sentinel
    Checks: _is_sentinel_object(value)
    Transform: sentinel → None
    Return: None
    ↓
gateway_core.execute_operation()
    Receives: None
    Return: None
    ↓
gateway_wrappers.cache_get()
    Receives: None
    Return: None
    ↓
OUTPUT: User code receives None

Why: Prevents sentinel objects from leaking to user code
Benefit: User can safely check "if cached is not None"
Critical: Must happen at router layer (gateway responsibility)
```

---

### Transformation 2: Error Wrapping (HTTP Client)
**REF:** NM03-TRANSFORM-02
**PRIORITY:** 🟢 MEDIUM
**TAGS:** transformation, error-wrapping, HTTP, error-dict
**KEYWORDS:** error wrapping, HTTP error, error dict transformation
**RELATED:** NM03-PATH-02, NM07-DT-05

```
INPUT: Network error during HTTP request
    ↓
http_client_core._execute_post_implementation()
    urllib raises: urllib.error.URLError
    Exception: {"errno": 111, "message": "Connection refused"}
    ↓
interface_http.execute_http_operation() [try/except]
    Catches: urllib.error.URLError
    Logs: gateway.log_error("HTTP POST failed", error=e)
    Transform: Exception → Error dict
    Return: {
        "success": False,
        "error": "HTTP request failed",
        "error_code": "HTTP_CONNECTION_ERROR",
        "details": {"errno": 111, "message": "Connection refused"}
    }
    ↓
gateway_core.execute_operation()
    Receives: Error dict
    Return: Error dict (unchanged)
    ↓
gateway_wrappers.http_post()
    Receives: Error dict
    Return: Error dict
    ↓
OUTPUT: User code receives structured error dict

Why: Standardizes error responses across interfaces
Benefit: User code can handle errors consistently
Pattern: {"success": False, "error": str, "error_code": str, "details": Any}
```

---

### Transformation 3: Configuration Caching (Config Interface)
**REF:** NM03-TRANSFORM-03
**PRIORITY:** 🟢 MEDIUM
**TAGS:** transformation, configuration, caching, type-conversion
**KEYWORDS:** config caching, configuration transformation, type conversion
**RELATED:** NM01-INT-05, NM04-DEC-12

```
INPUT: User requests config value
    ↓
gateway.get_config("cache.default_ttl")
    ↓
config_core._execute_get_parameter(key="cache.default_ttl")
    Check cache first:
        cache_key = f"config_{key}"
        Call: gateway.cache_get(cache_key)
        Result: None (not cached)
    
    Load from environment or file:
        value = os.environ.get("CACHE_DEFAULT_TTL")
        Result: "300"
    
    Transform: String → Integer
        value = int(value)
        Result: 300
    
    Cache for future use:
        Call: gateway.cache_set(cache_key, 300, ttl=600)
    
    Return: 300
    ↓
OUTPUT: User receives integer 300

Why: Reduces config lookups, improves performance
Benefit: Subsequent calls are O(1) cache lookups
Transform: Environment string → Typed value + caching
```

---

## PART 5: PERFORMANCE PATHWAYS

### Fast Path: Frequently Called Operations
**REF:** NM03-PERF-01
**PRIORITY:** 🟢 MEDIUM
**TAGS:** performance, fast-path, optimization, caching
**KEYWORDS:** fast path, performance optimization, hot operations
**RELATED:** NM04-DEC-13, NM06-LESS-02

```
First Call: cache_get("key")
    ↓ Gateway core measures call
    ↓ Operation count: 1
    ↓ No fast path yet
    ↓ Normal routing: 5 function calls

10th Call: cache_get("key")
    ↓ Gateway core increments counter
    ↓ Operation count: 10
    ↓ Threshold reached: Enable fast path
    ↓ Cache: (CACHE, 'get') → direct function reference
    ↓
    
11th+ Calls: cache_get("key")
    ↓ Gateway checks fast path cache
    ↓ Hit: Direct call to cached function
    ↓ Bypass: Module lookup, dispatch dict lookup
    ↓ Routing: 3 function calls (vs 5 normal)
    ↓ Performance: ~40% faster

Fast Path Benefits:
- Reduces function call overhead
- Caches module + function references
- Activated automatically for frequent operations
- Transparent to users (no code changes)

Fast Path Tradeoff:
- Slightly more memory (cached references)
- Complexity in gateway_core.py
- Benefit: Significant performance gain for hot paths
```

---

### Lazy Loading: First-Time Import Overhead
**REF:** NM03-PERF-02
**PRIORITY:** 🟢 MEDIUM
**TAGS:** performance, lazy-loading, cold-start, import
**KEYWORDS:** lazy loading, import overhead, cold start performance
**RELATED:** NM04-DEC-14, NM06-LESS-02

```
First Call to Any CACHE Operation:
    ↓
gateway_core.execute_operation(CACHE, 'get', ...)
    Check: Is interface_cache already imported?
    Result: No
    Action: importlib.import_module('interface_cache')
    Duration: ~10ms (one-time cost)
    Cache: Module reference in gateway_core
    ↓
Continue with normal routing
    
Second Call to Any CACHE Operation:
    ↓
gateway_core.execute_operation(CACHE, 'set', ...)
    Check: Is interface_cache already imported?
    Result: Yes (cached)
    Duration: ~0ms (no import)
    ↓
Continue with normal routing (fast)

Lazy Loading Benefits:
- Only imports what's used
- Reduces cold start time
- Lower memory footprint
- Each interface imports independently

Cold Start Breakdown:
- Lambda initialization: ~800ms
- Python interpreter: ~200ms
- Import gateway: ~50ms
- First interface lazy import: ~10ms per interface
- Total first request: ~1200ms
- Subsequent requests (warm): ~50ms
```

---

## PART 6: DEBUGGING PATHWAYS

### Debug Flow: Tracing Operation Execution
**REF:** NM03-DEBUG-01
**PRIORITY:** 🟢 MEDIUM
**TAGS:** debugging, tracing, diagnostics, operations
**KEYWORDS:** debug tracing, operation trace, execution tracing
**RELATED:** NM01-INT-12

```
Enable debug logging:
    gateway.set_debug_level("TRACE")
    
User calls: gateway.cache_set("key", "value")
    
Debug Output:
    [TRACE] gateway_wrappers.cache_set: Called with key='key', value='value'
    [TRACE] gateway_core.execute_operation: Interface=CACHE, operation='set'
    [TRACE] gateway_core: Looking up router for CACHE
    [TRACE] gateway_core: Router found: interface_cache.execute_cache_operation
    [TRACE] interface_cache.execute_cache_operation: Dispatching 'set' operation
    [TRACE] interface_cache: Calling cache_core._execute_set_implementation
    [TRACE] cache_core._execute_set_implementation: Setting key='key'
    [TRACE] cache_core: Validating parameters
    [TRACE] cache_core: Storing in _CACHE_STORE
    [TRACE] cache_core: Recording metric
    [TRACE] cache_core: Operation complete
    [TRACE] interface_cache: Returning success
    [TRACE] gateway_core: Operation complete
    [TRACE] gateway_wrappers: Returning to user

Debug Benefits:
- Complete operation trace
- Timing for each step
- Parameter values at each layer
- Error identification
```

---

## END NOTES

This Operation Pathways file documents how data and control flow through SUGA-ISP - the "nervous system" that connects all components. It shows both normal operation and error handling paths.

For structural details, see NEURAL_MAP_01. For dependencies, see NEURAL_MAP_02.

**Phase 1 Foundation Complete**: These 4 neural map files provide the core synthetic working memory for understanding SUGA-ISP architecture, dependencies, and operation flow.

**Phase 2 (Completed)**: Design rationale, anti-patterns, learned experiences, and decision logic documented in NM04-NM07.

---

# EOF

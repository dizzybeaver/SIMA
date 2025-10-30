# NM03-CORE: Operation Flows & System Pathways
# SIMA Architecture Pattern - Operational Details
# Version: 2.0.0 | Phase: 2 SIMA Implementation

---

## Purpose

This file documents operation execution flows, system initialization pathways, and data transformation pipelines. It's the **Implementation Layer** for core operational patterns in the SIMA architecture.

**Access via:** NM03-INDEX-Operations.md or direct search

---

## PART 1: OPERATION FLOW PATTERNS

### Pattern 1: Simple Operation (cache_get)
**REF:** NM03-FLOW-01
**PRIORITY:** CRITICAL
**TAGS:** flow, operation, cache_get, simple-operation, pathway
**KEYWORDS:** cache get flow, simple operation flow, basic pathway
**RELATED:** NM01-INT-01, NM01-ARCH-02, NM04-DEC-03

```
User Code: cache_get("my_key")
    |
gateway_wrappers.cache_get(key)
    | calls
gateway_core.execute_operation(CACHE, 'get', key=key)
    | looks up in _INTERFACE_ROUTERS
    | lazy imports interface_cache
interface_cache.execute_cache_operation('get', key=key)
    | looks up in _OPERATION_DISPATCH
    | calls
cache_core._execute_get_implementation(key=key)
    | accesses _CACHE_STORE
    | returns value or None
    | (sanitizes sentinel if present)
interface_cache (router) returns value
    |
gateway_core returns value
    |
gateway_wrappers returns value
    |
User Code receives value

Total Hops: 5 function calls
Latency: < 2ms (all in-memory, O(1) lookups)
```

**Performance Characteristics:**
- Wrapper layer: < 0.1ms (function call)
- Gateway routing: < 0.5ms (dict lookup + dispatch)
- Router dispatch: < 0.2ms (dict lookup)
- Core operation: < 1ms (dict access)
- Sentinel sanitization: < 0.1ms (if needed)

**Why 5 Hops:**
1. Clean separation of concerns
2. Each layer has specific responsibility
3. Easy to debug (can intercept at any layer)
4. Fast path optimization reduces to 3 hops for frequent operations

---

### Pattern 2: Complex Operation (HTTP POST)
**REF:** NM03-FLOW-02
**PRIORITY:** HIGH
**TAGS:** flow, HTTP, complex-operation, external-api
**KEYWORDS:** HTTP request flow, complex operation, API call
**RELATED:** NM01-INT-08, NM02-DEP-04

```
User Code: http_post(url, data)
    |
gateway_wrappers.http_post(url, data)
    |
gateway_core.execute_operation(HTTP_CLIENT, 'post', url=url, data=data)
    |
interface_http.execute_http_operation('post', url, data)
    |
http_client_core._execute_post_implementation(url, data)
    |
    +-- gateway.log_info(f"HTTP POST: {url}") --> LOGGING
    +-- gateway.validate_url(url) --> SECURITY
    +-- gateway.sanitize_input(data) --> SECURITY
    +-- gateway.cache_get(f"http_post_{url}") --> CACHE
    |   |
    |   +-- Cache miss, proceed with request
    |
    +-- _perform_http_request(url, data)
    |   |
    |   +-- Circuit breaker check
    |   +-- HTTP request (50-200ms external latency)
    |
    +-- gateway.cache_set(f"http_post_{url}", response) --> CACHE
    +-- gateway.record_api_metric("http_post", time) --> METRICS
    |
    +-- return response

Total Layers Touched: 5 (LOGGING, SECURITY, CACHE, METRICS, HTTP_CLIENT)
Total Latency: 55-210ms (mostly external HTTP call)
```

**Breakdown:**
- Gateway routing: < 1ms
- Security validation: < 2ms
- Cache lookup: < 1ms
- HTTP request: 50-200ms (external)
- Cache save: < 2ms
- Metrics recording: < 1ms
- **Total overhead:** < 10ms
- **External wait:** 50-200ms

**Why Complex:**
- Crosses multiple interfaces (via gateway)
- Involves external I/O
- Requires security, caching, monitoring
- Demonstrates full SIMA pattern in action

---

### Pattern 3: Cascading Operation
**REF:** NM03-FLOW-03
**PRIORITY:** HIGH
**TAGS:** flow, cascading, dependencies, multi-interface
**KEYWORDS:** cascading operation, dependent operations, chain
**RELATED:** NM02-DEP-03, NM02-DEP-04

```
User Code: process_alexa_request(event)
    |
gateway.log_info("Alexa request received") --> LOGGING
    |
gateway.get_config("home_assistant_url") --> CONFIG
    |  |
    |  +-- gateway.cache_get("config_ha_url") --> CACHE
    |  +-- If cache miss: gateway.get_parameter("ha_url") --> AWS Parameter Store
    |  +-- gateway.cache_set("config_ha_url", value) --> CACHE
    |  +-- return value
    |
gateway.http_get(ha_url + "/api/states") --> HTTP_CLIENT
    |  |
    |  +-- gateway.log_info("Fetching HA states") --> LOGGING
    |  +-- gateway.validate_url(url) --> SECURITY
    |  +-- gateway.cache_get(f"http_{url}") --> CACHE
    |  +-- If cache miss:
    |  |   +-- gateway.record_metric("http_request_start") --> METRICS
    |  |   +-- _perform_http_get(url)
    |  |   +-- gateway.cache_set(f"http_{url}", response) --> CACHE
    |  |   +-- gateway.record_metric("http_request_end") --> METRICS
    |  +-- return response
    |
process_states(response)
    |
gateway.log_info("Request completed") --> LOGGING
    |
return result

Interfaces Involved: LOGGING, CONFIG, CACHE, SECURITY, HTTP_CLIENT, METRICS
Total Operations: 12+ across 6 interfaces
Total Latency: 60-220ms depending on cache hits
```

**Cascading Effect:**
- Each operation triggers sub-operations
- Gateway mediates all cross-interface calls
- CACHE reduces redundant operations
- METRICS tracks entire chain

**Performance Impact:**
- Without caching: 200-300ms
- With caching: 5-10ms (cached config + cached HTTP)
- Cache hit rate: 80-90% typical

---

## PART 2: SYSTEM PATHWAYS

### Pathway 1: Cold Start Sequence
**REF:** NM03-PATH-01
**PRIORITY:** CRITICAL
**TAGS:** cold-start, initialization, system-startup, bootstrap
**KEYWORDS:** cold start, system initialization, lambda startup, bootstrap
**RELATED:** NM02-DEP-01, NM04-DEC-14, NM06-BUG-01

```
Lambda Cold Start (t=0ms)
    |
Lambda imports lambda_function.py (t=5ms)
    |
lambda_function imports gateway (t=10ms)
    |  |
    |  +-- gateway.py module-level initialization
    |  +-- Initialize empty _INTERFACE_ROUTERS dict
    |  +-- Import gateway_core, gateway_wrappers
    |  +-- No interface routers loaded yet (lazy loading)
    |
Lambda execution environment ready (t=15ms)
    |
First request arrives (t=1200ms typical AWS cold start)
    |
lambda_function.handler(event, context) called
    |
gateway.initialize_system() called
    |
    +-- Layer 0: LOGGING (t=1210ms)
    |   |
    |   +-- Import interface_logging (lazy)
    |   +-- Initialize logging_core
    |   +-- Configure log level from environment
    |   +-- Time: ~5ms
    |
    +-- Layer 1: SECURITY, UTILITY, SINGLETON (t=1215ms)
    |   |
    |   +-- Import interfaces as needed (lazy)
    |   +-- Initialize core implementations
    |   +-- Time: ~10ms
    |
    +-- Layer 2: CACHE, METRICS, CONFIG (t=1225ms)
    |   |
    |   +-- Import interface_cache, interface_metrics
    |   +-- Initialize _CACHE_STORE (empty dict)
    |   +-- Import interface_config
    |   +-- gateway.get_config("system_config")
    |   |   |
    |   |   +-- Check cache (empty on cold start)
    |   |   +-- Load from Parameter Store (15ms)
    |   |   +-- Cache result
    |   |
    |   +-- Time: ~25ms
    |
    +-- Layer 3: HTTP_CLIENT, WEBSOCKET (t=1250ms)
    |   |
    |   +-- Import only if Home Assistant enabled
    |   +-- Time: ~10ms (if needed)
    |
    +-- Layer 4: DEBUG, INITIALIZATION (t=1260ms)
    |   |
    |   +-- Initialization interface marks system ready
    |   +-- Time: ~5ms
    |
System fully initialized (t=1265ms)
    |
Process actual request (t=1265ms)
    |
    +-- gateway operations use cached routes (fast path)
    +-- All interfaces available
    |
Response returned (t=1265ms + request_time)

Total Cold Start Time: ~65ms (in-Lambda overhead)
Total AWS Cold Start: ~1200ms (AWS environment)
Combined: ~1265ms first request
```

**Cold Start Phases:**
1. **AWS Environment:** 1200ms (out of our control)
2. **Module Imports:** 15ms (Python imports)
3. **System Initialization:** 50ms (Layer 0 -> Layer 4)
4. **Total:** ~1265ms

**Optimizations Applied:**
- Lazy loading (don't import unused interfaces)
- Fast path caching (reduce routing overhead)
- Minimal module-level code (defer to runtime)

**Historical Context:**
- Original cold start: ~1850ms
- After sentinel fix (NM06-BUG-01): ~1320ms
- After optimizations: ~1265ms
- Target: < 1300ms

---

### Pathway 2: Cache Operation Flow
**REF:** NM03-PATH-02
**PRIORITY:** HIGH
**TAGS:** cache, operation, flow, CRUD
**KEYWORDS:** cache operation, cache flow, cache CRUD
**RELATED:** NM01-INT-01, NM02-CACHE-DEP

**Cache GET:**
```
gateway.cache_get(key, default=None)
    |
interface_cache.execute_cache_operation('get', key, default)
    |
cache_core._execute_get_implementation(key, default)
    |
    +-- Check if key in _CACHE_STORE
    +-- If present:
    |   +-- Check TTL expiration
    |   +-- If expired: delete key, return default
    |   +-- If valid: sanitize sentinel, return value
    +-- If absent: return default
    |
gateway.record_metric("cache_hit" or "cache_miss", 1.0)
    |
return value

Time: < 2ms
Cache Hit Rate: 80-90% typical
```

**Cache SET:**
```
gateway.cache_set(key, value, ttl=300)
    |
interface_cache.execute_cache_operation('set', key, value, ttl)
    |
cache_core._execute_set_implementation(key, value, ttl)
    |
    +-- Sanitize value (remove sentinel objects)
    +-- Calculate expiration time (current_time + ttl)
    +-- Store in _CACHE_STORE[key] = (value, expiration)
    +-- Check memory usage
    +-- If > 100MB: evict oldest 20% of entries
    |
gateway.record_metric("cache_set", 1.0)
    |
return True

Time: < 3ms
Memory: ~50-100KB per entry typical
```

**Cache DELETE:**
```
gateway.cache_delete(key)
    |
interface_cache.execute_cache_operation('delete', key)
    |
cache_core._execute_delete_implementation(key)
    |
    +-- Remove key from _CACHE_STORE if present
    +-- Return True if deleted, False if not found
    |
return result

Time: < 1ms
```

---

### Pathway 3: Logging Pipeline
**REF:** NM03-PATH-03
**PRIORITY:** HIGH
**TAGS:** logging, pipeline, output, formatting
**KEYWORDS:** logging pipeline, log flow, log formatting
**RELATED:** NM01-INT-02, NM02-DEP-01

```
User Code: gateway.log_info("Operation succeeded", **kwargs)
    |
gateway_wrappers.log_info(message, **kwargs)
    |
gateway_core.execute_operation(LOGGING, 'log_info', message=message, **kwargs)
    |
interface_logging.execute_logging_operation('log_info', message, **kwargs)
    |
logging_core._execute_log_info(message, **kwargs)
    |
    +-- Get current log level from environment
    +-- Check if INFO >= current level
    +-- If yes:
    |   |
    |   +-- Format message:
    |   |   +-- Timestamp (ISO 8601)
    |   |   +-- Level (INFO)
    |   |   +-- Message
    |   |   +-- Context (**kwargs)
    |   |
    |   +-- Sanitize sensitive data:
    |   |   +-- Remove tokens, passwords
    |   |   +-- Truncate long values
    |   |
    |   +-- Output destination:
    |   |   +-- Development: print() to stdout
    |   |   +-- Production: CloudWatch Logs (buffered)
    |   |
    |   +-- If metrics enabled:
    |       +-- gateway.record_metric("logs_written", 1.0)
    |
return None

Time: < 1ms
Buffering: Logs batched every 100ms in production
CloudWatch: Automatic ingestion from stdout
```

**Log Levels (Priority Order):**
1. CRITICAL (50) - System failure
2. ERROR (40) - Operation failed
3. WARNING (30) - Degraded operation
4. INFO (20) - Normal operation (default)
5. DEBUG (10) - Detailed diagnostics

**Log Format:**
```json
{
  "timestamp": "2025-10-20T19:30:15.123Z",
  "level": "INFO",
  "message": "Cache operation completed",
  "context": {
    "operation": "cache_get",
    "key": "config_value",
    "hit": true,
    "time_ms": 1.2
  }
}
```

---

### Pathway 5: Metrics Collection
**REF:** NM03-PATH-05
**PRIORITY:** MEDIUM
**TAGS:** metrics, telemetry, monitoring, observability
**KEYWORDS:** metrics collection, telemetry, monitoring
**RELATED:** NM01-INT-04, NM06-LESS-10

```
gateway.record_metric(name, value, unit="Count")
    |
interface_metrics.execute_metrics_operation('record', name, value, unit)
    |
metrics_core._execute_record(name, value, unit)
    |
    +-- Validate metric name (alphanumeric + underscores)
    +-- Validate value (numeric)
    +-- Add timestamp
    +-- Store in _METRICS_BUFFER[name] = (value, unit, timestamp)
    |
    +-- If buffer > 100 entries OR 60 seconds elapsed:
    |   |
    |   +-- Aggregate metrics:
    |   |   +-- Count: sum of values
    |   |   +-- Timing: avg, min, max, p95
    |   |
    |   +-- Format for CloudWatch:
    |   |   +-- Namespace: SUGA-ISP-Lambda
    |   |   +-- Dimensions: {Environment, FunctionName}
    |   |
    |   +-- gateway.log_info("Metrics sent", count=len(buffer))
    |   +-- Clear buffer
    |
return True

Time: < 1ms per metric
Buffering: Flush every 60 seconds or 100 metrics
CloudWatch: Custom metrics (charges apply)
```

**Common Metrics:**
- `cache_hit`: Cache hit count
- `cache_miss`: Cache miss count
- `http_request_time_ms`: HTTP request duration
- `cold_start_time_ms`: Cold start duration
- `request_count`: Total requests
- `error_count`: Total errors

---

### Pathway 6: Data Transformation Pipeline
**REF:** NM03-TRACE-02
**PRIORITY:** MEDIUM
**TAGS:** transformation, pipeline, data-flow
**KEYWORDS:** data transformation, pipeline, processing
**RELATED:** NM01-INT-11

```
Alexa Event (AWS format)
    |
gateway.log_info("Event received", event_type=event['request']['type'])
    |
Parse Alexa Intent:
    +-- Extract intent name
    +-- Extract slot values
    +-- Validate required slots
    |
Transform to Home Assistant format:
    +-- Map Alexa intent -> HA service call
    +-- Convert slot values -> HA parameters
    +-- Add authentication headers
    |
gateway.http_post(ha_url + "/api/services", ha_payload)
    |
    +-- Security validation
    +-- HTTP request
    +-- Response parsing
    |
Transform HA response to Alexa format:
    +-- Extract state changes
    +-- Format speech response
    +-- Add card data
    |
gateway.log_info("Response formatted", speech_length=len(response))
    |
Return Alexa Response (AWS format)

Transformations: 3 (Alexa -> Internal -> HA -> Internal -> Alexa)
Time: ~100-250ms (mostly HTTP)
```

---

## INTEGRATION NOTES

### Cross-Reference with Other Neural Maps

**NM01 (Architecture):**
- All flows use gateway/router/core pattern from ARCH-02
- Operation dispatch uses pattern from ARCH-03

**NM02 (Dependencies):**
- PATH-01 follows dependency layers (DEP-01 to DEP-05)
- FLOW-02, FLOW-03 demonstrate cross-interface via gateway (RULE-01)

**NM04 (Decisions):**
- DEC-07 (Fast path) optimizes FLOW-01
- DEC-14 (Lazy loading) optimizes PATH-01

**NM06 (Learned Experiences):**
- PATH-01 incorporates fix from NM06-BUG-01
- Metrics collection from NM06-LESS-10

---

## END NOTES

**Key Takeaways:**
1. Simple operations: 5 hops, < 2ms
2. Complex operations: Multiple interfaces, < 10ms overhead
3. Cold start: ~65ms Lambda overhead, ~1200ms AWS
4. All cross-interface calls via gateway (SIMA pattern)
5. Caching reduces latency by 80-95%

**File Statistics:**
- Total REF IDs: 8 (FLOW-01 to 03, PATH-01 to 03, PATH-05, TRACE-02)
- Total lines: ~400
- Priority: 2 CRITICAL, 5 HIGH, 1 MEDIUM

**Related Files:**
- NM03-INDEX-Operations.md (Router to this file)
- NM03-ERROR-Handling.md (Error propagation patterns)
- NM01-INDEX-Architecture.md (Architecture details)

# EOF

# NM03-Operations-Pathways.md

# Operations - System Pathways

**Category:** NM03 - Operations
**Topic:** Pathways
**Items:** 4
**Priority:** 1 Critical, 3 High
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Topic Overview

System pathways document high-level operational sequences including system initialization, cache operations, logging pipelines, and metrics collection.

---

## PATH-01: Cold Start Sequence

**Priority:** CRITICAL  
**Keywords:** cold-start, initialization, bootstrap, lambda-startup

### Summary

Complete Lambda cold start sequence from AWS environment initialization through layer-by-layer system initialization, totaling ~1265ms.

### Timeline

```
Lambda Cold Start (t=0ms)
    ↓
Lambda imports lambda_function.py (t=5ms)
    ↓
lambda_function imports gateway (t=10ms)
    ├── gateway.py module-level initialization
    ├── Initialize empty _INTERFACE_ROUTERS dict
    ├── Import gateway_core, gateway_wrappers
    └── No interface routers loaded yet (lazy loading)
    ↓
Lambda execution environment ready (t=15ms)
    ↓
First request arrives (t=1200ms typical AWS cold start)
    ↓
lambda_function.handler(event, context) called
    ↓
gateway.initialize_system() called
    ↓
    ├── Layer 0: LOGGING (t=1210ms)
    │   ├── Import interface_logging (lazy)
    │   ├── Initialize logging_core
    │   ├── Configure log level from environment
    │   └── Time: ~5ms
    │
    ├── Layer 1: SECURITY, UTILITY, SINGLETON (t=1215ms)
    │   ├── Import interfaces as needed (lazy)
    │   ├── Initialize core implementations
    │   └── Time: ~10ms
    │
    ├── Layer 2: CACHE, METRICS, CONFIG (t=1225ms)
    │   ├── Import interface_cache, interface_metrics
    │   ├── Initialize _CACHE_STORE (empty dict)
    │   ├── Import interface_config
    │   ├── gateway.get_config("system_config")
    │   │   ├── Check cache (empty on cold start)
    │   │   ├── Load from Parameter Store (15ms)
    │   │   └── Cache result
    │   └── Time: ~25ms
    │
    ├── Layer 3: HTTP_CLIENT, WEBSOCKET (t=1250ms)
    │   ├── Import only if Home Assistant enabled
    │   └── Time: ~10ms (if needed)
    │
    └── Layer 4: DEBUG, INITIALIZATION (t=1260ms)
        ├── Initialization interface marks system ready
        └── Time: ~5ms
    ↓
System fully initialized (t=1265ms)
    ↓
Process actual request (t=1265ms)
    ├── Gateway operations use cached routes (fast path)
    └── All interfaces available
    ↓
Response returned (t=1265ms + request_time)

Total Cold Start Time: ~65ms (in-Lambda overhead)
Total AWS Cold Start: ~1200ms (AWS environment)
Combined: ~1265ms first request
```

### Cold Start Phases

1. **AWS Environment:** 1200ms (out of our control)
2. **Module Imports:** 15ms (Python imports)
3. **System Initialization:** 50ms (Layer 0 → Layer 4)
4. **Total:** ~1265ms

### Optimizations Applied

- Lazy loading (don't import unused interfaces)
- Fast path caching (reduce routing overhead)
- Minimal module-level code (defer to runtime)

### Historical Performance

- **Original cold start:** ~1850ms
- **After sentinel fix (BUG-01):** ~1320ms
- **After optimizations:** ~1265ms
- **Target:** < 1300ms ✅

### Related

- **DEP-01**: Dependency Layer 0 (LOGGING)
- **DEC-14**: Lazy loading strategy
- **BUG-01**: Sentinel leak (535ms penalty fixed)

---

## PATH-02: Cache Operation Flow

**Priority:** HIGH  
**Keywords:** cache, CRUD, operations, get-set-delete

### Summary

Detailed flows for cache GET, SET, and DELETE operations with TTL management and memory pressure handling.

### Cache GET Operation

```
gateway.cache_get(key, default=None)
    ↓
interface_cache.execute_cache_operation('get', key, default)
    ↓
cache_core._execute_get_implementation(key, default)
    ↓
    ├── Check if key in _CACHE_STORE
    ├── If present:
    │   ├── Check TTL expiration
    │   ├── If expired: delete key, return default
    │   └── If valid: sanitize sentinel, return value
    └── If absent: return default
    ↓
gateway.record_metric("cache_hit" or "cache_miss", 1.0)
    ↓
return value

Time: < 2ms
Cache Hit Rate: 80-90% typical
```

### Cache SET Operation

```
gateway.cache_set(key, value, ttl=300)
    ↓
interface_cache.execute_cache_operation('set', key, value, ttl)
    ↓
cache_core._execute_set_implementation(key, value, ttl)
    ↓
    ├── Sanitize value (remove sentinel objects)
    ├── Calculate expiration time (current_time + ttl)
    ├── Store in _CACHE_STORE[key] = (value, expiration)
    ├── Check memory usage
    └── If > 100MB: evict oldest 20% of entries
    ↓
gateway.record_metric("cache_set", 1.0)
    ↓
return True

Time: < 3ms
Memory: ~50-100KB per entry typical
```

### Cache DELETE Operation

```
gateway.cache_delete(key)
    ↓
interface_cache.execute_cache_operation('delete', key)
    ↓
cache_core._execute_delete_implementation(key)
    ↓
    └── Remove key from _CACHE_STORE if present
    ↓
return True if deleted, False if not found

Time: < 1ms
```

### Related

- **INT-01**: CACHE interface specification
- **CACHE-DEP**: Cache dependencies
- **DEC-05**: Sentinel sanitization

---

## PATH-03: Logging Pipeline

**Priority:** HIGH  
**Keywords:** logging, pipeline, formatting, cloudwatch

### Summary

Complete logging pipeline from user code through formatting, sanitization, and CloudWatch output.

### Flow

```
User Code: gateway.log_info("Operation succeeded", **kwargs)
    ↓
gateway_wrappers.log_info(message, **kwargs)
    ↓
gateway_core.execute_operation(LOGGING, 'log_info', message, **kwargs)
    ↓
interface_logging.execute_logging_operation('log_info', message, **kwargs)
    ↓
logging_core._execute_log_info(message, **kwargs)
    ↓
    ├── Get current log level from environment
    ├── Check if INFO >= current level
    └── If yes:
        ├── Format message:
        │   ├── Timestamp (ISO 8601)
        │   ├── Level (INFO)
        │   ├── Message
        │   └── Context (**kwargs)
        │
        ├── Sanitize sensitive data:
        │   ├── Remove tokens, passwords
        │   └── Truncate long values
        │
        ├── Output destination:
        │   ├── Development: print() to stdout
        │   └── Production: CloudWatch Logs (buffered)
        │
        └── If metrics enabled:
            └── gateway.record_metric("logs_written", 1.0)
    ↓
return None

Time: < 1ms
Buffering: Logs batched every 100ms in production
CloudWatch: Automatic ingestion from stdout
```

### Log Levels (Priority Order)

1. **CRITICAL (50)** - System failure
2. **ERROR (40)** - Operation failed
3. **WARNING (30)** - Degraded operation
4. **INFO (20)** - Normal operation (default)
5. **DEBUG (10)** - Detailed diagnostics

### Log Format

```json
{
  "timestamp": "2025-10-24T19:30:15.123Z",
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

### Related

- **INT-02**: LOGGING interface
- **DEP-01**: Dependency Layer 0

---

## PATH-05: Metrics Collection

**Priority:** MEDIUM  
**Keywords:** metrics, telemetry, monitoring, cloudwatch

### Summary

Metrics collection, buffering, aggregation, and CloudWatch custom metrics publishing.

### Flow

```
gateway.record_metric(name, value, unit="Count")
    ↓
interface_metrics.execute_metrics_operation('record', name, value, unit)
    ↓
metrics_core._execute_record(name, value, unit)
    ↓
    ├── Validate metric name (alphanumeric + underscores)
    ├── Validate value (numeric)
    ├── Add timestamp
    └── Store in _METRICS_BUFFER[name] = (value, unit, timestamp)
    ↓
    └── If buffer > 100 entries OR 60 seconds elapsed:
        ├── Aggregate metrics:
        │   ├── Count: sum of values
        │   └── Timing: avg, min, max, p95
        │
        ├── Format for CloudWatch:
        │   ├── Namespace: SUGA-ISP-Lambda
        │   └── Dimensions: {Environment, FunctionName}
        │
        ├── gateway.log_info("Metrics sent", count=len(buffer))
        └── Clear buffer
    ↓
return True

Time: < 1ms per metric
Buffering: Flush every 60 seconds or 100 metrics
CloudWatch: Custom metrics (charges apply)
```

### Common Metrics

- `cache_hit`: Cache hit count
- `cache_miss`: Cache miss count
- `http_request_time_ms`: HTTP request duration
- `cold_start_time_ms`: Cold start duration
- `request_count`: Total requests
- `error_count`: Total errors

### Related

- **INT-04**: METRICS interface
- **LESS-10**: Metrics best practices

---

## Related Topics

- **Operations-Flows**: Execution flow patterns
- **Operations-ErrorHandling**: Error pathways
- **Operations-Tracing**: Request traces

---

## Navigation

**Up:** NM03-Operations_Index.md  
**Category:** NM03 - Operations  
**Siblings:** Flows, ErrorHandling, Tracing

---

**File:** `NM03/NM03-Operations-Pathways.md`  
**End of Document**

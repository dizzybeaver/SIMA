# File: NMP01-LEE-03_INT-02-LOGGING-Function-Catalog.md

# NMP01-LEE-03: INT-02 LOGGING - Function Catalog

**Project:** Lambda Execution Engine (SUGA-ISP)  
**Project Code:** LEE  
**Category:** Interface Catalog  
**Interface:** INT-02 (LOGGING)  
**Priority:** 🔴 CRITICAL  
**Status:** Active  
**Created:** 2025-10-29  
**Last Updated:** 2025-10-29

---

## Summary

Complete function catalog for INT-02 (LOGGING) interface in SUGA-ISP Lambda, documenting all logging operations, structured output format, integration with CloudWatch Logs, and project-specific logging patterns.

---

## Context

LOGGING is Layer 0 (foundation) - all other interfaces depend on it. This catalog documents actual implementation in SUGA-ISP, including JSON structured logging, severity levels, and CloudWatch integration.

---

## Function Catalog

### Core Logging Functions

#### log_info(message, **kwargs)
**Gateway:** `gateway.log_info()`  
**Router:** `interface_logging.log_info()`  
**Implementation:** `logging_core.log_message('INFO', message, **kwargs)`

**Purpose:** Log informational messages for normal operations

**Parameters:**
- `message` (str): Log message
- `**kwargs`: Additional structured fields

**Output Format:**
```json
{
  "timestamp": "2025-10-29T14:30:00.123Z",
  "level": "INFO",
  "message": "User authenticated successfully",
  "user_id": "user_123",
  "method": "oauth"
}
```

**Usage in Project:**
```python
# Basic info logging
gateway.log_info("Lambda function started")

# With structured data
gateway.log_info("HA API call successful", 
                 endpoint="/api/states",
                 duration_ms=45)

# With entity information
gateway.log_info("Entity state retrieved",
                 entity_id="light.bedroom",
                 state="on")
```

**CloudWatch:** Appears in CloudWatch Logs, searchable by fields

**Performance:** < 0.5ms overhead

---

#### log_error(message, **kwargs)
**Gateway:** `gateway.log_error()`  
**Router:** `interface_logging.log_error()`  
**Implementation:** `logging_core.log_message('ERROR', message, **kwargs)`

**Purpose:** Log error conditions that don't crash the system

**Parameters:**
- `message` (str): Error description
- `**kwargs`: Error context (exception, stack trace, etc.)

**Output Format:**
```json
{
  "timestamp": "2025-10-29T14:30:05.456Z",
  "level": "ERROR",
  "message": "Failed to connect to Home Assistant",
  "error": "ConnectionRefusedError",
  "url": "http://homeassistant.local:8123",
  "retry_count": 3
}
```

**Usage in Project:**
```python
try:
    response = connect_to_ha()
except ConnectionError as e:
    gateway.log_error("HA connection failed",
                      error=str(e),
                      url=ha_url,
                      retry_count=retry_count)
    raise
```

**CloudWatch:** Triggers error alarms if configured

**Performance:** < 0.5ms overhead

---

#### log_warning(message, **kwargs)
**Gateway:** `gateway.log_warning()`  
**Router:** `interface_logging.log_warning()`  
**Implementation:** `logging_core.log_message('WARNING', message, **kwargs)`

**Purpose:** Log potential issues that don't prevent operation

**Usage in Project:**
```python
# API rate limiting warning
gateway.log_warning("API rate limit approaching",
                    current_rate=95,
                    limit=100)

# Cache miss warning
if not cached:
    gateway.log_warning("Cache miss on frequently accessed key",
                        key=cache_key,
                        miss_count=miss_count)

# Configuration fallback
gateway.log_warning("Using default configuration",
                    setting="ha_timeout",
                    default=30)
```

**CloudWatch:** Can trigger warnings in monitoring

---

#### log_debug(message, **kwargs)
**Gateway:** `gateway.log_debug()`  
**Router:** `interface_logging.log_debug()`  
**Implementation:** `logging_core.log_message('DEBUG', message, **kwargs)`

**Purpose:** Detailed debugging information (disabled in production)

**Usage in Project:**
```python
# Cache operations
gateway.log_debug("Cache lookup",
                  key=cache_key,
                  hit=cache_hit)

# Function entry/exit
gateway.log_debug("Entering function",
                  function="process_alexa_request",
                  payload_size=len(payload))

# Performance timing
gateway.log_debug("Operation completed",
                  operation="entity_state_fetch",
                  duration_ms=duration)
```

**Control:** Enabled/disabled via LOG_LEVEL environment variable

**Performance:** < 0.5ms when enabled, 0ms when disabled

---

#### log_critical(message, **kwargs)
**Gateway:** `gateway.log_critical()`  
**Router:** `interface_logging.log_critical()`  
**Implementation:** `logging_core.log_message('CRITICAL', message, **kwargs)`

**Purpose:** Critical errors requiring immediate attention

**Usage in Project:**
```python
# Out of memory
if memory_usage > 120_000_000:  # 120MB
    gateway.log_critical("Memory threshold exceeded",
                         current_mb=memory_usage / 1024 / 1024,
                         limit_mb=128)

# Security violation
gateway.log_critical("Unauthorized access attempt",
                     source_ip=source_ip,
                     attempted_action=action)

# System failure
gateway.log_critical("Lambda failsafe triggered",
                     reason="unhandled_exception",
                     error=str(error))
```

**CloudWatch:** Triggers critical alarms, may trigger PagerDuty

---

## Structured Logging Patterns

### Entity Operations

```python
gateway.log_info("Entity state changed",
                 entity_id="light.bedroom",
                 old_state="off",
                 new_state="on",
                 triggered_by="alexa")
```

### API Calls

```python
gateway.log_info("HA API call",
                 method="GET",
                 endpoint="/api/states/light.bedroom",
                 status_code=200,
                 duration_ms=45,
                 cached=False)
```

### Cache Operations

```python
gateway.log_debug("Cache operation",
                  operation="set",
                  key="ha_entity_light.bedroom",
                  ttl=300,
                  size_bytes=estimate_size(value))
```

### Performance Metrics

```python
gateway.log_info("Lambda execution completed",
                 duration_ms=duration,
                 cold_start=is_cold_start,
                 memory_used_mb=memory_used / 1024 / 1024,
                 cache_hit_rate=hit_rate)
```

### Error Context

```python
try:
    result = risky_operation()
except Exception as e:
    gateway.log_error("Operation failed",
                      operation="risky_operation",
                      error=str(e),
                      error_type=type(e).__name__,
                      traceback=traceback.format_exc())
    raise
```

---

## CloudWatch Integration

### Log Groups

**Production:**
```
/aws/lambda/suga-isp-production
```

**Staging:**
```
/aws/lambda/suga-isp-staging
```

**Development:**
```
/aws/lambda/suga-isp-dev
```

### Log Streams

Each Lambda execution creates new stream:
```
2025/10/29/[$LATEST]abc123def456...
```

### Searchable Fields

All structured fields in **kwargs are searchable:
```
# Find all HA API errors
fields @message like /HA API call/ | filter level = "ERROR"

# Find slow operations
fields @message, duration_ms | filter duration_ms > 1000

# Find specific entity operations
fields @message | filter entity_id = "light.bedroom"
```

---

## Log Level Control

### Environment Variable

```bash
LOG_LEVEL=INFO  # Default production
LOG_LEVEL=DEBUG  # Development/troubleshooting
LOG_LEVEL=WARNING  # Minimal logging
```

### Runtime Control

```python
# Get current level
current_level = gateway.get_log_level()

# Set level dynamically
gateway.set_log_level("DEBUG")
```

### Level Hierarchy

```
CRITICAL (50) - Always logged
ERROR (40)    - Production default
WARNING (30)  - Minimal logging
INFO (20)     - Normal logging
DEBUG (10)    - Verbose logging
```

---

## Performance Characteristics

### Logging Overhead

| Level | Overhead | Impact |
|-------|----------|--------|
| DEBUG (disabled) | 0ms | None |
| DEBUG (enabled) | 0.3-0.5ms | Minimal |
| INFO | 0.1-0.3ms | Negligible |
| WARNING | 0.1-0.3ms | Negligible |
| ERROR | 0.2-0.4ms | Negligible |
| CRITICAL | 0.2-0.4ms | Negligible |

### CloudWatch Costs

**Ingestion:** $0.50 per GB  
**Storage:** $0.03 per GB-month

**Typical Usage:**
- INFO level: 50-100 KB per invocation
- DEBUG level: 200-500 KB per invocation

---

## Common Logging Patterns

### Function Entry/Exit

```python
def process_request(event):
    gateway.log_info("Function started",
                     function="process_request",
                     event_type=event.get('type'))
    
    result = _process_impl(event)
    
    gateway.log_info("Function completed",
                     function="process_request",
                     duration_ms=duration)
    
    return result
```

### Error Recovery

```python
try:
    result = attempt_operation()
except RetryableError as e:
    gateway.log_warning("Operation failed, retrying",
                        error=str(e),
                        retry_count=retry_count)
    result = retry_operation()
except FatalError as e:
    gateway.log_error("Operation failed permanently",
                      error=str(e))
    raise
```

### Performance Tracking

```python
start = time.time()
result = expensive_operation()
duration = (time.time() - start) * 1000

gateway.log_info("Operation timing",
                 operation="expensive_operation",
                 duration_ms=duration,
                 cached=was_cached)
```

### Cache Analytics

```python
stats = gateway.cache_get_stats()
gateway.log_info("Cache statistics",
                 total_keys=stats['total_keys'],
                 hit_rate=stats['hit_rate'],
                 memory_mb=stats['memory_estimate'] / 1024 / 1024)
```

---

## Integration with Other Interfaces

### LOGGING + CACHE

Every cache operation logged at DEBUG:
```python
def cache_set(key, value, ttl):
    gateway.log_debug("Cache set",
                      key=key,
                      ttl=ttl)
    _cache_set_impl(key, value, ttl)
```

### LOGGING + SECURITY

Security violations logged at ERROR/CRITICAL:
```python
def validate_token(token):
    if not _is_valid(token):
        gateway.log_error("Invalid token detected",
                          token_prefix=token[:8])
        raise SecurityError("Invalid token")
```

### LOGGING + METRICS

Operations logged with timing:
```python
def track_operation(op_name):
    start = time.time()
    result = perform_operation()
    duration = (time.time() - start) * 1000
    
    gateway.log_info(f"{op_name} completed",
                     duration_ms=duration)
    gateway.increment_counter(f"{op_name}_count")
```

---

## Project-Specific Considerations

### Lambda Context Logging

```python
def lambda_handler(event, context):
    gateway.log_info("Lambda invoked",
                     request_id=context.request_id,
                     function_name=context.function_name,
                     memory_limit_mb=context.memory_limit_in_mb,
                     remaining_time_ms=context.get_remaining_time_in_millis())
```

### Cold Start Detection

```python
_is_cold_start = True

def lambda_handler(event, context):
    global _is_cold_start
    
    gateway.log_info("Lambda execution",
                     cold_start=_is_cold_start)
    
    _is_cold_start = False
```

### Home Assistant Event Logging

```python
def on_state_changed(event):
    gateway.log_info("HA state changed",
                     entity_id=event['entity_id'],
                     old_state=event['old_state'],
                     new_state=event['new_state'],
                     source=event.get('source', 'unknown'))
```

---

## Related Documentation

**Generic Patterns:**
- INT-02: Generic LOGGING interface pattern
- ARCH-07: LMMS logging during startup

**Project Decisions:**
- DEC-09: JSON structured logging
- DEC-10: CloudWatch integration

**Lessons:**
- LESS-10: Logging best practices
- LESS-12: Debug logging impact

---

## Keywords

logging, INT-02, SUGA-ISP, LEE, function-catalog, cloudwatch, structured-logging, json-format, severity-levels, observability, debugging, monitoring

---

**END OF FILE**

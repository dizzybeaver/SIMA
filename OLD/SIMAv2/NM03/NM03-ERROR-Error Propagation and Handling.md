# NM03-ERROR: Error Propagation & Handling
# SIMA Architecture Pattern - Error Management
# Version: 2.0.0 | Phase: 2 SIMA Implementation

---

## Purpose

This file documents error propagation patterns, exception handling strategies, graceful degradation, and request tracing. It's the **Implementation Layer** for error management in the SIMA architecture.

**Access via:** NM03-INDEX-Operations.md or direct search

---

## PART 1: ERROR PROPAGATION

### Pathway 4: Error Propagation Through Layers
**REF:** NM03-PATH-04
**PRIORITY:** CRITICAL
**TAGS:** error, propagation, exception, layers, handling
**KEYWORDS:** error propagation, exception flow, error handling
**RELATED:** NM04-DEC-15, NM05-AP-09, NM06-BUG-03

```
User Code: gateway.cache_get("bad_key")
    |
gateway_wrappers.cache_get(key)
    |
gateway_core.execute_operation(CACHE, 'get', key=key)
    |  |
    |  try:
    |      interface_cache.execute_cache_operation('get', key)
    |          |
    |          try:
    |              cache_core._execute_get_implementation(key)
    |                  |
    |                  [ERROR OCCURS: KeyError or ValueError]
    |                  |
    |              except Exception as e:
    |                  gateway.log_error(f"Cache core error: {e}")
    |                  raise  # Re-raise to router layer
    |          |
    |          except Exception as e:
    |              gateway.log_error(f"Cache operation failed: {e}")
    |              return None  # Graceful degradation
    |
    |  except Exception as e:
    |      gateway.log_error(f"Gateway operation failed: {e}")
    |      return None  # Gateway-level fallback
    |
return None  # Wrapper returns safe default

Error Caught At: Each layer (Core, Router, Gateway, Wrapper)
Error Logged At: Each layer (full visibility)
User Receives: Safe default value (None)
System State: Continues operating (graceful degradation)
```

**4-Layer Error Handling:**

1. **Core Layer** (Implementation)
   - Try-except around risky operations
   - Log error with full context
   - Re-raise to router for decision

2. **Router Layer** (Interface)
   - Catch exceptions from core
   - Log with operation context
   - Return safe default OR re-raise

3. **Gateway Layer** (Dispatch)
   - Catch exceptions from routers
   - Log with interface context
   - Return fallback value

4. **Wrapper Layer** (Public API)
   - Final safety net
   - Ensures function never crashes Lambda
   - Returns documented default value

**Why 4 Layers:**
- Multiple opportunities to recover
- Visibility at each level
- Graceful degradation
- System never crashes from interface errors

---

## PART 2: EXCEPTION HANDLING PATTERNS

### Pattern 1: Try-Except at Each Layer
**REF:** NM03-ERROR-01
**PRIORITY:** HIGH
**TAGS:** exception, try-except, pattern, best-practice
**KEYWORDS:** exception handling, try except pattern
**RELATED:** NM04-DEC-15, NM07-DT-05

**Core Layer Pattern:**
```python
def _execute_get_implementation(key: str, default=None):
    """Core implementation with error handling."""
    try:
        # Risky operation
        if key not in _CACHE_STORE:
            return default
        
        value, expiration = _CACHE_STORE[key]
        
        # Check expiration
        if time.time() > expiration:
            del _CACHE_STORE[key]
            return default
        
        # Sanitize sentinel
        if isinstance(value, _CacheMissSentinel):
            return default
        
        return value
        
    except KeyError as e:
        # Specific exception
        log_error(f"Key error in cache: {key}", error=str(e))
        return default
        
    except Exception as e:
        # Catch-all
        log_error(f"Unexpected cache error: {key}", error=str(e))
        raise  # Re-raise unexpected errors
```

**Router Layer Pattern:**
```python
def execute_cache_operation(operation: str, **kwargs):
    """Router with error handling and logging."""
    try:
        # Log operation start
        log_info(f"Cache operation: {operation}", **kwargs)
        
        # Dispatch to implementation
        result = _OPERATION_DISPATCH[operation](**kwargs)
        
        # Log success
        log_info(f"Cache operation success: {operation}")
        return result
        
    except KeyError:
        # Unknown operation
        log_error(f"Unknown cache operation: {operation}")
        return None
        
    except Exception as e:
        # Implementation error
        log_error(f"Cache operation failed: {operation}", error=str(e))
        # Return safe default instead of crashing
        return kwargs.get('default', None)
```

**Gateway Layer Pattern:**
```python
def execute_operation(interface: str, operation: str, **kwargs):
    """Gateway with error handling and fallback."""
    try:
        # Lazy load interface router
        router = _get_interface_router(interface)
        
        # Execute operation
        result = router.execute_operation(operation, **kwargs)
        return result
        
    except ImportError as e:
        # Interface not available
        log_error(f"Interface not found: {interface}", error=str(e))
        raise  # Can't recover from missing interface
        
    except Exception as e:
        # Operation failed
        log_error(f"Operation failed: {interface}.{operation}", error=str(e))
        # Return safe default
        return None
```

---

### Pattern 2: Graceful Degradation
**REF:** NM03-ERROR-02
**PRIORITY:** HIGH
**TAGS:** graceful-degradation, failover, resilience
**KEYWORDS:** graceful degradation, failover, error recovery
**RELATED:** NM06-LESS-05, NM06-BUG-03

**Strategy 1: Safe Defaults**
```python
def get_config(key: str, default=None):
    """Get config with fallback chain."""
    try:
        # Try cache first
        cached = cache_get(f"config_{key}")
        if cached is not None:
            return cached
        
        # Try Parameter Store
        value = _fetch_from_parameter_store(key)
        if value is not None:
            cache_set(f"config_{key}", value, ttl=3600)
            return value
        
        # Use default
        return default
        
    except Exception as e:
        log_warning(f"Config fetch failed: {key}, using default", error=str(e))
        return default
```

**Strategy 2: Partial Functionality**
```python
def process_alexa_request(event):
    """Process request with partial degradation."""
    try:
        # Try full processing
        ha_states = gateway.http_get(ha_url + "/api/states")
        return process_full_response(ha_states)
        
    except Exception as e:
        log_warning("Full processing failed, trying cached data", error=str(e))
        
        try:
            # Fall back to cached states
            cached_states = gateway.cache_get("last_ha_states")
            if cached_states:
                return process_cached_response(cached_states)
            
            # Fall back to minimal response
            return create_minimal_response()
            
        except Exception as e2:
            log_error("All fallbacks failed", error=str(e2))
            return create_error_response()
```

**Strategy 3: Circuit Breaker**
```python
def http_request_with_circuit_breaker(url):
    """HTTP request with circuit breaker protection."""
    circuit_state = gateway.circuit_breaker_check("http_client")
    
    if circuit_state == "OPEN":
        # Circuit breaker tripped, don't attempt request
        log_warning("Circuit breaker OPEN, skipping HTTP request")
        return None
    
    try:
        # Attempt request
        response = _perform_http_request(url)
        
        # Success: close circuit
        gateway.circuit_breaker_success("http_client")
        return response
        
    except Exception as e:
        # Failure: record error
        gateway.circuit_breaker_failure("http_client")
        log_error(f"HTTP request failed: {url}", error=str(e))
        
        # Check if circuit should open
        failure_count = gateway.circuit_breaker_get_failures("http_client")
        if failure_count >= 5:
            gateway.circuit_breaker_open("http_client", duration=60)
            log_warning("Circuit breaker OPENED after 5 failures")
        
        return None
```

---

### Pattern 3: Error Logging
**REF:** NM03-ERROR-03
**PRIORITY:** MEDIUM
**TAGS:** logging, errors, visibility, debugging
**KEYWORDS:** error logging, error visibility, debug
**RELATED:** NM01-INT-02, NM06-LESS-10

**Structured Error Logging:**
```python
def log_error(message: str, **context):
    """Log error with full context."""
    error_data = {
        "timestamp": datetime.utcnow().isoformat(),
        "level": "ERROR",
        "message": message,
        "context": context,
        "stack_trace": traceback.format_exc() if sys.exc_info()[0] else None,
        "request_id": get_current_request_id(),
    }
    
    # Sanitize sensitive data
    error_data = _sanitize_error_data(error_data)
    
    # Output to CloudWatch
    print(json.dumps(error_data))
    
    # Record metric
    gateway.record_metric("error_count", 1.0)
```

**Error Context:**
```python
try:
    result = cache_get(key)
except Exception as e:
    log_error(
        "Cache operation failed",
        operation="cache_get",
        key=key,
        error_type=type(e).__name__,
        error_message=str(e),
        cache_size=len(_CACHE_STORE),
        memory_usage=get_memory_usage(),
    )
```

**CloudWatch Insights Query:**
```
fields @timestamp, message, context.operation, context.error_type
| filter level = "ERROR"
| stats count() by context.error_type
| sort count desc
```

---

## PART 3: REQUEST TRACING

### Full Request Trace Example
**REF:** NM03-TRACE-01
**PRIORITY:** MEDIUM
**TAGS:** tracing, debugging, observability, timing
**KEYWORDS:** request trace, full trace, end-to-end, timing
**RELATED:** NM06-LESS-10, NM03-FLOW-02

```
REQUEST START (t=0ms)
===================
Event: Alexa IntentRequest
Intent: TurnOnLight
Slots: {room: "kitchen"}

PHASE 1: INITIALIZATION (t=0-5ms)
----------------------------------
[t=0ms] lambda_handler() called
[t=1ms] gateway.log_info("Request received")
  -> interface_logging.execute_logging_operation('log_info')
  -> logging_core._execute_log_info()
  -> print(log_json)
[t=2ms] gateway.initialize_system() (warm start, no-op)
[t=5ms] Phase complete

PHASE 2: CONFIGURATION (t=5-15ms)
----------------------------------
[t=5ms] gateway.get_config("home_assistant_url")
  -> interface_config.execute_config_operation('get')
  -> config_core._execute_get()
    -> gateway.cache_get("config_ha_url")
      -> interface_cache.execute_cache_operation('get')
      -> cache_core._execute_get_implementation()
      -> Cache HIT, return value (< 1ms)
[t=6ms] URL retrieved from cache
[t=10ms] gateway.get_config("home_assistant_token")
  -> [Same flow, cache HIT]
[t=11ms] Token retrieved from cache
[t=15ms] Phase complete

PHASE 3: HTTP REQUEST (t=15-180ms)
-----------------------------------
[t=15ms] gateway.http_post(ha_url + "/api/services/light/turn_on")
  -> interface_http.execute_http_operation('post')
  -> http_client_core._execute_post_implementation()
    [t=16ms] gateway.log_info("HTTP POST starting")
    [t=18ms] gateway.validate_url(url)
      -> interface_security.execute_security_operation('validate_url')
      -> security_core._execute_validate_url()
      -> URL valid, return True (< 1ms)
    [t=20ms] gateway.sanitize_input(data)
      -> [Same flow]
      -> Data sanitized (< 1ms)
    [t=22ms] gateway.cache_get(f"http_post_{url}")
      -> Cache MISS (POST not cached)
    [t=25ms] gateway.record_metric("http_request_start", 1.0)
    [t=27ms] _perform_http_request(url, data)
      -> HTTP request to Home Assistant
      -> [External I/O wait: 150ms]
    [t=177ms] HTTP response received (200 OK)
    [t=179ms] gateway.record_metric("http_request_end", 152.0)
    [t=180ms] gateway.record_api_metric("http_post", 153.0)
[t=180ms] HTTP response returned
[t=180ms] Phase complete

PHASE 4: RESPONSE FORMATTING (t=180-190ms)
-------------------------------------------
[t=180ms] format_alexa_response(ha_response)
[t=185ms] gateway.log_info("Response formatted", speech_length=len(response))
[t=190ms] Phase complete

REQUEST END (t=190ms)
=====================
Total Time: 190ms
Breakdown:
  - Init: 5ms (3%)
  - Config: 10ms (5%)
  - HTTP: 165ms (87%)
  - Format: 10ms (5%)

Operations:
  - Cache hits: 2
  - Cache misses: 1
  - HTTP requests: 1
  - Logs: 4
  - Metrics: 3
  - Security checks: 2

Result: SUCCESS
Response: "Kitchen light turned on"
```

**Trace Insights:**
- 87% of time in external HTTP call
- Cache hits save ~150ms each
- Security overhead: < 2ms
- Logging overhead: < 2ms
- Gateway routing: < 3ms total

---

## INTEGRATION NOTES

### Cross-Reference with Other Neural Maps

**NM01 (Architecture):**
- Error handling follows ARCH-02 (execution engine)
- Each layer (wrapper, gateway, router, core) has error handling

**NM02 (Dependencies):**
- Error propagation follows dependency layers
- Lower layers handle errors before upper layers see them

**NM04 (Decisions):**
- DEC-15 (Router-level exception catching) implements ERROR-01
- Error handling strategy from design decisions

**NM05 (Anti-Patterns):**
- Correct error handling prevents AP-09 (unhandled exceptions)
- Graceful degradation prevents AP-07 (cascading failures)

**NM06 (Learned Experiences):**
- BUG-03 (Cascading failures) led to ERROR-02 patterns
- LESS-05 (Graceful degradation) informs error strategies
- LESS-08 (Test failure paths) validates error handling

---

## END NOTES

**Key Takeaways:**
1. 4-layer error handling (Core, Router, Gateway, Wrapper)
2. Each layer logs errors before propagating
3. Graceful degradation returns safe defaults
4. Circuit breaker prevents cascading failures
5. Structured logging enables debugging

**Error Handling Principles:**
- Log at every layer
- Return safe defaults
- Never crash Lambda
- Provide full context
- Enable recovery

**File Statistics:**
- Total REF IDs: 5 (PATH-04, ERROR-01 to 03, TRACE-01)
- Total lines: ~300
- Priority: 1 CRITICAL, 2 HIGH, 2 MEDIUM

**Related Files:**
- NM03-INDEX-Operations.md (Router to this file)
- NM03-CORE-Pathways.md (Operation flows)
- NM04-INDEX-Decisions.md (Design decisions)

# EOF

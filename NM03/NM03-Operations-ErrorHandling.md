# NM03-Operations-ErrorHandling.md

# Operations - Error Handling

**Category:** NM03 - Operations
**Topic:** ErrorHandling
**Items:** 4
**Priority:** 1 Critical, 2 High, 1 Medium
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Topic Overview

Error handling patterns document how exceptions propagate through SUGA pattern layers, graceful degradation strategies, and structured error logging for observability.

---

## PATH-04: Error Propagation Through Layers

**Priority:** CRITICAL  
**Keywords:** error-propagation, exception-flow, layers, handling

### Summary

4-layer error propagation showing how exceptions flow through Core → Router → Gateway → Wrapper with logging and recovery at each layer.

### Flow Diagram

```
User Code: gateway.cache_get("bad_key")
    ↓
gateway_wrappers.cache_get(key)
    ↓
gateway_core.execute_operation(CACHE, 'get', key=key)
    ↓
    try:
        interface_cache.execute_cache_operation('get', key)
            ↓
            try:
                cache_core._execute_get_implementation(key)
                    ↓
                    [ERROR OCCURS: KeyError or ValueError]
                    ↓
                except Exception as e:
                    gateway.log_error(f"Cache core error: {e}")
                    raise  # Re-raise to router layer
            ↓
            except Exception as e:
                gateway.log_error(f"Cache operation failed: {e}")
                return None  # Graceful degradation
    ↓
    except Exception as e:
        gateway.log_error(f"Gateway operation failed: {e}")
        return None  # Gateway-level fallback
    ↓
return None  # Wrapper returns safe default

Error Caught At: Each layer (Core, Router, Gateway, Wrapper)
Error Logged At: Each layer (full visibility)
User Receives: Safe default value (None)
System State: Continues operating (graceful degradation)
```

### 4-Layer Error Handling

1. **Core Layer (Implementation)**
   - Try-except around risky operations
   - Log error with full context
   - Re-raise to router for decision

2. **Router Layer (Interface)**
   - Catch exceptions from core
   - Log with operation context
   - Return safe default OR re-raise

3. **Gateway Layer (Dispatch)**
   - Catch exceptions from routers
   - Log with interface context
   - Return fallback value

4. **Wrapper Layer (Public API)**
   - Final safety net
   - Ensures function never crashes Lambda
   - Returns documented default value

### Why 4 Layers

- Multiple opportunities to recover
- Visibility at each level
- Graceful degradation
- System never crashes from interface errors

### Related

- **DEC-15**: Router-level exception catching
- **AP-09**: Unhandled exceptions anti-pattern
- **BUG-03**: Cascading failures lesson

---

## ERROR-01: Try-Except at Each Layer

**Priority:** HIGH  
**Keywords:** exception-handling, try-except, pattern, best-practice

### Summary

Standard exception handling patterns for each SUGA layer with specific examples and anti-patterns to avoid.

### Core Layer Pattern

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

### Router Layer Pattern

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

### Gateway Layer Pattern

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

### Related

- **DEC-15**: Router-level exception catching decision
- **DT-05**: Error handling decision tree

---

## ERROR-02: Graceful Degradation

**Priority:** HIGH  
**Keywords:** graceful-degradation, failover, resilience, recovery

### Summary

Three graceful degradation strategies: safe defaults, partial functionality, and circuit breaker pattern.

### Strategy 1: Safe Defaults

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

### Strategy 2: Partial Functionality

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

### Strategy 3: Circuit Breaker

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

### Related

- **LESS-05**: Graceful degradation lesson
- **LESS-08**: Test failure paths
- **AP-07**: Cascading failures anti-pattern

---

## ERROR-03: Error Logging

**Priority:** MEDIUM  
**Keywords:** logging, errors, visibility, debugging, structured-logging

### Summary

Structured error logging with context, sanitization, and CloudWatch integration for debugging and alerting.

### Structured Error Logging

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

### Error Context Example

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

### CloudWatch Insights Query

```
fields @timestamp, message, context.operation, context.error_type
| filter level = "ERROR"
| stats count() by context.error_type
| sort count desc
```

### Error Logging Principles

- Log at every layer
- Include full context (operation, parameters, state)
- Sanitize sensitive data (tokens, passwords)
- Use structured JSON format
- Record metrics for alerting
- Include stack traces for debugging

### Related

- **INT-02**: LOGGING interface
- **LESS-10**: Metrics and monitoring

---

## Error Handling Principles

**Key Principles:**
1. Log at every layer
2. Return safe defaults
3. Never crash Lambda
4. Provide full context
5. Enable recovery

**Best Practices:**
- Use specific exception types (not bare `except`)
- Re-raise unexpected exceptions
- Implement fallback chains
- Use circuit breakers for external services
- Test failure paths

---

## Related Topics

- **Operations-Flows**: Normal execution patterns
- **Operations-Pathways**: System pathways
- **Operations-Tracing**: Request traces with errors

---

## Navigation

**Up:** NM03-Operations_Index.md  
**Category:** NM03 - Operations  
**Siblings:** Flows, Pathways, Tracing

---

**File:** `NM03/NM03-Operations-ErrorHandling.md`  
**End of Document**

# File: NMP01-LEE-14_Gateway-Core-Execute-Operation-Patterns.md

# NMP01-LEE-14: Gateway Core - execute_operation() Patterns

**Project:** Lambda Execution Engine (SUGA-ISP)  
**Project Code:** LEE  
**Category:** Gateway Patterns  
**Component:** gateway_core.py  
**Priority:** 🔴 CRITICAL  
**Status:** Active  
**Created:** 2025-10-29  
**Last Updated:** 2025-10-29

---

## Summary

Documentation of execute_operation() function in gateway_core.py - the central dispatch mechanism that routes all gateway operations to appropriate interface implementations. Includes error handling patterns, performance optimization, and lazy import strategies.

---

## Context

`execute_operation()` is the heart of SUGA pattern - the single entry point that provides:
- Lazy import management
- Consistent error handling
- Operation dispatch
- Performance tracking

This entry documents actual implementation patterns in SUGA-ISP Lambda.

---

## Core Implementation

### Function Signature

```python
def execute_operation(interface: str, operation: str, *args, **kwargs):
    """
    Execute operation on specified interface with lazy import.
    
    Args:
        interface: Interface module name (e.g., 'cache', 'logging')
        operation: Operation name (e.g., 'set_value', 'log_info')
        *args: Positional arguments for operation
        **kwargs: Keyword arguments for operation
        
    Returns:
        Operation result
        
    Raises:
        InterfaceNotFoundError: Interface module doesn't exist
        OperationNotFoundError: Operation doesn't exist
        Various: Operation-specific exceptions
    """
```

---

## Implementation Patterns

### Pattern 1: Lazy Import

**Purpose:** Import interface modules only when first used

**Implementation:**
```python
def execute_operation(interface, operation, *args, **kwargs):
    # Check module cache first
    if interface not in _MODULE_CACHE:
        # Lazy import on first use
        module_name = f"interface_{interface}"
        
        try:
            module = __import__(module_name)
            _MODULE_CACHE[interface] = module
        except ImportError as e:
            raise InterfaceNotFoundError(f"Interface {interface} not found: {e}")
    
    # Get cached module
    module = _MODULE_CACHE[interface]
    
    # Execute operation
    return _dispatch_operation(module, operation, *args, **kwargs)
```

**Benefit:** Reduces cold start time by 60-70%

**Performance:**
- First call: 5-20ms (import + execute)
- Cached calls: < 0.1ms (execute only)

---

### Pattern 2: Operation Dispatch

**Purpose:** Route operation to correct function

**Implementation:**
```python
def _dispatch_operation(module, operation, *args, **kwargs):
    # Get operation function
    if not hasattr(module, operation):
        raise OperationNotFoundError(
            f"Operation {operation} not found in {module.__name__}"
        )
    
    operation_func = getattr(module, operation)
    
    # Execute with error handling
    try:
        result = operation_func(*args, **kwargs)
        return result
        
    except Exception as e:
        # Add context to error
        raise OperationError(
            f"Error in {module.__name__}.{operation}: {e}"
        ) from e
```

**Benefit:** Consistent error context for debugging

---

### Pattern 3: Error Handling

**Purpose:** Provide consistent error handling across all operations

**Implementation:**
```python
def execute_operation(interface, operation, *args, **kwargs):
    try:
        return _execute_impl(interface, operation, *args, **kwargs)
        
    except InterfaceNotFoundError:
        # Interface doesn't exist - critical error
        log_critical(f"Interface not found: {interface}")
        raise
        
    except OperationNotFoundError:
        # Operation doesn't exist - likely programming error
        log_error(f"Operation not found: {interface}.{operation}")
        raise
        
    except Exception as e:
        # Operation failed - log and re-raise
        log_error(f"Operation failed: {interface}.{operation}",
                  error=str(e))
        raise
```

**Benefit:** All errors logged with context, consistent handling

---

### Pattern 4: Performance Tracking

**Purpose:** Track operation timing and frequency

**Implementation:**
```python
def execute_operation(interface, operation, *args, **kwargs):
    start_time = time.time()
    
    try:
        result = _execute_impl(interface, operation, *args, **kwargs)
        
        # Track success
        duration_ms = (time.time() - start_time) * 1000
        _record_metric(f"{interface}.{operation}", duration_ms, success=True)
        
        return result
        
    except Exception as e:
        # Track failure
        duration_ms = (time.time() - start_time) * 1000
        _record_metric(f"{interface}.{operation}", duration_ms, success=False)
        raise
```

**Benefit:** Complete visibility into operation performance

---

## Module Cache Management

### Cache Structure

```python
_MODULE_CACHE = {}  # {interface_name: module_object}
```

### Cache Population

**On First Use:**
```python
# First call to cache interface
execute_operation('cache', 'set_value', 'key', 'value')
# Result: Imports interface_cache, caches module

# Subsequent calls
execute_operation('cache', 'get_value', 'key')
# Result: Uses cached module, no import
```

### Cache Benefits

| Metric | First Call | Cached Call | Improvement |
|--------|------------|-------------|-------------|
| Import Time | 5-20ms | 0ms | 100% |
| Total Time | 5-20ms | < 0.1ms | 99%+ |

---

## Error Types and Handling

### InterfaceNotFoundError

**Cause:** Interface module doesn't exist

**Example:**
```python
# Typo in interface name
execute_operation('cach', 'set_value', 'key', 'value')
# Raises: InterfaceNotFoundError: Interface cach not found
```

**Handling:** Critical error, should never happen in production

---

### OperationNotFoundError

**Cause:** Operation doesn't exist on interface

**Example:**
```python
# Typo in operation name
execute_operation('cache', 'set_valu', 'key', 'value')
# Raises: OperationNotFoundError: Operation set_valu not found
```

**Handling:** Programming error, fix caller

---

### OperationError

**Cause:** Operation failed during execution

**Example:**
```python
# Invalid key type
execute_operation('cache', 'set_value', None, 'value')
# Raises: OperationError: Error in interface_cache.set_value: 
#         Key cannot be None
```

**Handling:** Operation-specific, may be retryable

---

## Performance Characteristics

### Cold Start Impact

**Without Lazy Loading:**
```
Import all 12 interfaces: 200-500ms
â†' Cold start: 1500-2000ms
```

**With Lazy Loading:**
```
Import only used interfaces: 50-100ms
â†' Cold start: < 300ms for typical request
```

**Improvement:** 60-70% faster cold start

---

### Runtime Performance

| Scenario | Time | Notes |
|----------|------|-------|
| First operation (new interface) | 5-20ms | Import + execute |
| Cached operation | < 0.1ms | Execute only |
| 100 operations (same interface) | ~10ms | Amortized cost |
| 100 operations (10 interfaces) | ~100ms | 10 imports |

---

## Integration with LMMS

### Cold Start Optimization

```python
# lambda_preload.py - Fast path preload
from gateway_core import execute_operation

# Preload critical interfaces
execute_operation('logging', 'log_info', 'Lambda starting')
execute_operation('cache', 'cache_clear')  # Warm cache interface
```

**Effect:** Critical interfaces loaded during cold start, rest lazy

---

### Memory Management

**Module Cache Size:**
```
12 interfaces Ã— 50-500KB average = 600KB - 6MB
```

**Within Lambda constraint:** 128MB total - plenty of headroom

---

## Usage Examples

### Basic Operation

```python
# Via wrapper
result = gateway.cache_set('key', 'value')

# What happens internally
result = gateway_core.execute_operation('cache', 'set_value', 'key', 'value')
```

---

### With Multiple Arguments

```python
# Wrapper
gateway.log_info("Message", user_id="123", action="login")

# Internal
gateway_core.execute_operation('logging', 'log_info', 
                                "Message", 
                                user_id="123", 
                                action="login")
```

---

### Error Recovery

```python
try:
    result = gateway_core.execute_operation('http', 'get', url)
except OperationError as e:
    # Retry or fallback
    gateway_core.execute_operation('logging', 'log_warning',
                                    f"HTTP operation failed: {e}")
    result = cached_fallback()
```

---

## Best Practices

### Do: Use Consistent Interface Names

```python
# âœ… GOOD
execute_operation('cache', 'set_value', key, value)

# âŒ BAD
execute_operation('CACHE', 'set_value', key, value)  # Wrong case
execute_operation('caching', 'set_value', key, value)  # Wrong name
```

---

### Do: Handle Errors Appropriately

```python
try:
    result = execute_operation('http', 'get', url)
except InterfaceNotFoundError:
    # Critical - interface missing
    log_critical("HTTP interface not found")
    raise
except OperationError:
    # Expected - network issue
    log_warning("HTTP request failed")
    # Handle gracefully
```

---

### Don't: Bypass Gateway

```python
# âŒ WRONG - Direct interface import
from interface_cache import set_value
set_value('key', 'value')

# âœ… CORRECT - Via gateway
gateway_core.execute_operation('cache', 'set_value', 'key', 'value')
```

---

## Related Documentation

**Architecture:**
- ARCH-01: Gateway Trinity (gateway_core is central)
- ARCH-07: LMMS (lazy loading strategy)

**Anti-Patterns:**
- AP-01: Direct imports (why gateway is required)

**Decisions:**
- DEC-01: SUGA pattern (execute_operation is implementation)

---

## Keywords

gateway-core, execute-operation, dispatch, lazy-loading, SUGA, error-handling, performance, module-cache, LMMS, cold-start-optimization

---

**END OF FILE**

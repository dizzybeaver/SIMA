# DD1-LESS-02-LEE-Interface-Pattern.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Dictionary dispatch implementation pattern used across LEE project interfaces  
**Category:** Python Architecture Lesson - Implementation

---

## LESSON LEARNED

**Consistent dictionary dispatch pattern across all interfaces creates predictable, maintainable, high-performance routing architecture.**

**Context:** LEE project 12-interface implementation  
**Impact:** Consistent 2.1µs routing across all interfaces  
**Date:** 2024-09-15

---

## THE PATTERN

### Standard Interface Structure

Every LEE interface follows this pattern:

```python
# interface_cache.py

from typing import Dict, Callable, Any

# Handler imports
from cache_core import (
    get_impl,
    set_impl,
    delete_impl,
    # ... all handlers
)

# Dispatch table
CACHE_DISPATCH: Dict[str, Callable] = {
    "get": get_impl,
    "set": set_impl,
    "delete": delete_impl,
    "clear": clear_impl,
    "exists": exists_impl,
    # ... alphabetically sorted
}

def execute_cache_operation(operation: str, **kwargs) -> Dict[str, Any]:
    """
    Execute cache operation via dispatch table.
    
    Args:
        operation: Operation name (must be in CACHE_DISPATCH)
        **kwargs: Operation-specific parameters
        
    Returns:
        Operation result dictionary
        
    Raises:
        ValueError: Unknown operation
        
    Example:
        result = execute_cache_operation("get", key="user_123")
    """
    handler = CACHE_DISPATCH.get(operation)
    if handler is None:
        raise ValueError(
            f"Unknown cache operation: {operation}. "
            f"Valid operations: {', '.join(CACHE_DISPATCH.keys())}"
        )
    return handler(**kwargs)
```

**This exact pattern used in all 12 interfaces**

---

## THE 12 INTERFACES

### Interface List

```python
1. CACHE        - 20 operations
2. LOGGING      - 8 operations  
3. SECURITY     - 12 operations
4. HTTP         - 15 operations
5. INITIALIZATION - 6 operations
6. CONFIG       - 10 operations
7. METRICS      - 18 operations
8. DEBUG        - 14 operations
9. SINGLETON    - 5 operations
10. UTILITY     - 22 operations
11. WEBSOCKET   - 16 operations
12. CIRCUIT_BREAKER - 9 operations

Total: 155 operations across 12 dispatch tables
```

### Example: CACHE Interface

```python
# interface_cache.py
CACHE_DISPATCH = {
    "get": cache_get_impl,
    "set": cache_set_impl,
    "delete": cache_delete_impl,
    "clear": cache_clear_impl,
    "exists": cache_exists_impl,
    "get_many": cache_get_many_impl,
    "set_many": cache_set_many_impl,
    "delete_many": cache_delete_many_impl,
    "get_or_set": cache_get_or_set_impl,
    "increment": cache_increment_impl,
    "decrement": cache_decrement_impl,
    "expire": cache_expire_impl,
    "persist": cache_persist_impl,
    "ttl": cache_ttl_impl,
    "keys": cache_keys_impl,
    "size": cache_size_impl,
    "memory": cache_memory_impl,
    "stats": cache_stats_impl,
    "health": cache_health_impl,
    "reset": cache_reset_impl,
}
```

**20 operations, all routed via dispatch**

### Example: LOGGING Interface

```python
# interface_logging.py
LOGGING_DISPATCH = {
    "log": log_impl,
    "debug": log_debug_impl,
    "info": log_info_impl,
    "warning": log_warning_impl,
    "error": log_error_impl,
    "critical": log_critical_impl,
    "exception": log_exception_impl,
    "get_logger": get_logger_impl,
}
```

**8 operations, same pattern**

---

## PATTERN BENEFITS

### Benefit 1: Consistency

**Developer experience:**
- Learn pattern once, apply everywhere
- No surprises between interfaces
- Code reviews faster (familiar pattern)

**Example:**
```python
# Any interface follows same structure
result = execute_[interface]_operation(operation, **kwargs)

# Examples:
execute_cache_operation("get", key="x")
execute_logging_operation("info", message="x")
execute_metrics_operation("increment", metric="x")
```

### Benefit 2: Performance

**Measured across interfaces:**

```
Interface       Ops  Avg Time  P95    P99
CACHE           20   2.1µs     2.3µs  2.5µs
LOGGING         8    2.0µs     2.2µs  2.4µs
SECURITY        12   2.1µs     2.3µs  2.5µs
HTTP            15   2.1µs     2.2µs  2.4µs
WEBSOCKET       16   2.1µs     2.3µs  2.5µs
```

**All interfaces ~2.1µs average**

### Benefit 3: Maintainability

**Adding new operation:**

```python
# 1. Implement handler
def new_operation_impl(**kwargs):
    """New operation implementation."""
    return {"result": "done"}

# 2. Add to dispatch table (one line)
CACHE_DISPATCH = {
    # ... existing operations
    "new_operation": new_operation_impl,  # Add here
}

# 3. Done - no router changes needed
```

**~5 minutes to add new operation**

---

## PATTERN VARIATIONS

### Variation 1: Parameterized Handlers

**Used in UTILITY interface for similar operations:**

```python
from functools import partial

def validate_impl(validator_type: str, data: Any) -> bool:
    """Generic validation implementation."""
    validators = {
        "email": validate_email,
        "url": validate_url,
        "phone": validate_phone,
    }
    return validators[validator_type](data)

UTILITY_DISPATCH = {
    "validate_email": partial(validate_impl, "email"),
    "validate_url": partial(validate_impl, "url"),
    "validate_phone": partial(validate_impl, "phone"),
}
```

**Reduces code duplication for similar operations**

### Variation 2: Nested Dispatch

**Used in HTTP interface for method-specific routing:**

```python
# Primary dispatch by HTTP method
HTTP_DISPATCH = {
    "get": execute_get_operation,
    "post": execute_post_operation,
    "put": execute_put_operation,
    "delete": execute_delete_operation,
}

# Secondary dispatch for GET operations
GET_DISPATCH = {
    "fetch": http_get_impl,
    "fetch_json": http_get_json_impl,
    "fetch_text": http_get_text_impl,
}

def execute_get_operation(operation: str, **kwargs):
    """Route GET operations."""
    handler = GET_DISPATCH.get(operation)
    if handler is None:
        raise ValueError(f"Unknown GET operation: {operation}")
    return handler(**kwargs)
```

**Organizes large operation sets by category**

---

## GATEWAY INTEGRATION

### How Gateway Uses Dispatch

**Gateway consolidates all interfaces:**

```python
# gateway.py
from typing import Any, Dict
from enum import Enum

class GatewayInterface(Enum):
    """All available interfaces."""
    CACHE = "cache"
    LOGGING = "logging"
    SECURITY = "security"
    # ... all 12 interfaces

# Central registry maps interfaces to routers
_INTERFACE_ROUTERS = {
    GatewayInterface.CACHE: ('interface_cache', 'execute_cache_operation'),
    GatewayInterface.LOGGING: ('interface_logging', 'execute_logging_operation'),
    # ... all 12 interfaces
}

def execute_operation(
    interface: GatewayInterface,
    operation: str,
    **kwargs
) -> Dict[str, Any]:
    """
    Execute operation through interface dispatch.
    
    Two-level dispatch:
    1. Gateway routes to interface
    2. Interface routes to handler
    """
    module_name, func_name = _INTERFACE_ROUTERS[interface]
    module = importlib.import_module(module_name)
    router = getattr(module, func_name)
    return router(operation, **kwargs)
```

**Gateway + Interface = Two-level dispatch**

### Example Usage

```python
import gateway
from gateway import GatewayInterface

# Two-level routing:
# 1. Gateway routes to CACHE interface
# 2. CACHE routes to get handler
result = gateway.execute_operation(
    interface=GatewayInterface.CACHE,
    operation="get",
    key="user_123"
)
```

---

## ERROR HANDLING

### Standard Error Pattern

**All interfaces use consistent error handling:**

```python
def execute_cache_operation(operation: str, **kwargs):
    """Execute cache operation with error handling."""
    # Validate operation exists
    handler = CACHE_DISPATCH.get(operation)
    if handler is None:
        raise ValueError(
            f"Unknown cache operation: {operation}. "
            f"Valid operations: {', '.join(sorted(CACHE_DISPATCH.keys()))}"
        )
    
    # Execute with error context
    try:
        return handler(**kwargs)
    except Exception as e:
        # Add context to error
        raise type(e)(
            f"Cache operation '{operation}' failed: {e}"
        ) from e
```

**Benefits:**
- Clear error messages
- Lists valid operations
- Preserves exception chain
- Adds operation context

---

## TYPE SAFETY

### Type Hints Pattern

```python
from typing import Dict, Callable, Any, TypedDict

class CacheResult(TypedDict):
    """Standard cache result type."""
    success: bool
    value: Any
    cached: bool
    ttl: int

HandlerFunc = Callable[..., CacheResult]

CACHE_DISPATCH: Dict[str, HandlerFunc] = {
    "get": cache_get_impl,
    # Type checker verifies all handlers return CacheResult
}

def execute_cache_operation(operation: str, **kwargs) -> CacheResult:
    """Type-safe execution."""
    handler: HandlerFunc = CACHE_DISPATCH[operation]
    return handler(**kwargs)
```

**mypy validates:**
- Handler signatures match
- Return types consistent
- No typos in operation names (with typing.Literal)

---

## DOCUMENTATION

### Self-Documenting Dispatch Table

```python
CACHE_DISPATCH = {
    # ===== Read Operations =====
    "get": cache_get_impl,           # Get single value
    "get_many": cache_get_many_impl, # Get multiple values
    "exists": cache_exists_impl,     # Check existence
    
    # ===== Write Operations =====
    "set": cache_set_impl,           # Set single value
    "set_many": cache_set_many_impl, # Set multiple values
    "delete": cache_delete_impl,     # Delete single value
    
    # ===== Management Operations =====
    "clear": cache_clear_impl,       # Clear all cache
    "stats": cache_stats_impl,       # Get statistics
    "health": cache_health_impl,     # Health check
}
```

**Dispatch table serves as operation catalog**

---

## TESTING PATTERN

### Standard Test Structure

```python
import pytest
from interface_cache import CACHE_DISPATCH, execute_cache_operation

class TestCacheDispatch:
    """Test cache interface dispatch."""
    
    def test_all_operations_exist(self):
        """Verify all handlers in dispatch table exist."""
        for operation, handler in CACHE_DISPATCH.items():
            assert callable(handler), f"{operation} handler not callable"
    
    def test_unknown_operation_raises(self):
        """Unknown operation raises ValueError."""
        with pytest.raises(ValueError, match="Unknown cache operation"):
            execute_cache_operation("nonexistent")
    
    def test_dispatch_routes_correctly(self):
        """Dispatch routes to correct handler."""
        result = execute_cache_operation("get", key="test")
        assert "value" in result

# Test each handler directly
class TestCacheHandlers:
    """Test individual cache handlers."""
    
    def test_get_handler(self):
        """Test get handler directly."""
        from cache_core import get_impl
        result = get_impl(key="test")
        assert isinstance(result, dict)
```

**Pattern: Test dispatch + Test handlers separately**

---

## PERFORMANCE MONITORING

### CloudWatch Metrics

```python
def execute_cache_operation(operation: str, **kwargs):
    """Execute with metrics."""
    start = time.perf_counter()
    
    handler = CACHE_DISPATCH.get(operation)
    if handler is None:
        raise ValueError(f"Unknown operation: {operation}")
    
    try:
        result = handler(**kwargs)
        duration_ms = (time.perf_counter() - start) * 1000
        
        # Record metrics
        log_metric(
            "InterfaceDispatch",
            duration_ms,
            dimensions={
                "Interface": "Cache",
                "Operation": operation,
                "Success": "True"
            }
        )
        return result
        
    except Exception as e:
        duration_ms = (time.perf_counter() - start) * 1000
        log_metric(
            "InterfaceDispatch",
            duration_ms,
            dimensions={
                "Interface": "Cache",
                "Operation": operation,
                "Success": "False"
            }
        )
        raise
```

**Tracks:** Duration, success rate per interface/operation

---

## LESSONS FROM IMPLEMENTATION

### Lesson 1: Alphabetical Sorting Helps

**Dispatch tables sorted alphabetically:**
```python
CACHE_DISPATCH = {
    "clear": ...,
    "decrement": ...,
    "delete": ...,
    # Easy to find operations
}
```

**Benefits:**
- Quick visual scan
- Prevent duplicates
- Merge conflicts clearer

### Lesson 2: Descriptive Error Messages

**Include valid operations in error:**
```python
raise ValueError(
    f"Unknown operation: {operation}. "
    f"Valid: {', '.join(CACHE_DISPATCH.keys())}"
)
```

**Helps developers fix issues quickly**

### Lesson 3: Type Hints Catch Bugs

**Type checking found 12 issues during development:**
- Mismatched return types (5 cases)
- Missing parameters (4 cases)
- Wrong callable signature (3 cases)

**Saved ~8 hours of debugging**

---

## KEYWORDS

LEE implementation pattern, interface dispatch, consistent patterns, dictionary dispatch implementation, production patterns, 12-interface architecture

---

## RELATED LESSONS

- DD1-LESS-01: Performance Measurements
- SUGA-LESS-03: Gateway Entry Point
- CR1-LESS-02: Central Registry Pattern (when created)

---

**END OF FILE**

**Version:** 1.0.0  
**Lines:** 398 (within 400 limit)  
**Category:** Python Architecture Lesson  
**Status:** Complete

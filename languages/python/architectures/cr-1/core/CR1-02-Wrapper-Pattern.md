# CR1-02-Wrapper-Pattern.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Wrapper function pattern for convenience and type safety  
**Type:** Architecture Pattern

---

## PATTERN: Wrapper Functions for Each Interface

**Purpose:** Provide convenient, type-safe, documented functions for all interface operations  
**Location:** gateway_wrappers_*.py files  
**Count:** ~100+ wrapper functions across 12 interfaces

---

## PROBLEM STATEMENT

Direct operation execution is cumbersome:
```python
# Verbose and no type hints
result = gateway.execute_operation(
    GatewayInterface.CACHE, 
    'get', 
    key=key
)
```

**Problems:**
- No type hints at call site
- No docstring at call site
- Hard to discover available operations
- Easy to misspell operation names
- No IDE autocomplete
- Verbose

---

## SOLUTION: WRAPPER FUNCTIONS

**Create dedicated function for each operation:**
```python
def cache_get(key: str) -> Any:
    """
    Get value from cache.
    
    Args:
        key: Cache key to lookup
        
    Returns:
        Cached value or None if not found
        
    Example:
        >>> value = gateway.cache_get("user:123")
        >>> print(value)
        {"name": "John", "email": "john@example.com"}
    """
    return execute_operation(GatewayInterface.CACHE, 'get', key=key)
```

**Benefits:**
- âœ… Type hints (IDE support)
- âœ… Docstrings (help() works)
- âœ… Named parameters
- âœ… IDE autocomplete
- âœ… Cleaner syntax
- âœ… Easier testing

---

## WRAPPER PATTERN STRUCTURE

### File Organization

```
gateway_wrappers_cache.py       # Cache interface wrappers (10+ functions)
gateway_wrappers_logging.py     # Logging interface wrappers (15+ functions)
gateway_wrappers_metrics.py     # Metrics interface wrappers (20+ functions)
gateway_wrappers_security.py    # Security interface wrappers (8+ functions)
gateway_wrappers_http.py        # HTTP interface wrappers (12+ functions)
gateway_wrappers_config.py      # Config interface wrappers (6+ functions)
gateway_wrappers_debug.py       # Debug interface wrappers (10+ functions)
# ... all 12 interfaces
```

### Standard Wrapper Template

```python
def {interface}_{operation}({params}) -> {return_type}:
    """
    {Brief description}
    
    Args:
        {param}: {description}
        
    Returns:
        {return_type}: {description}
        
    Raises:
        {Exception}: {when}
        
    Example:
        >>> result = gateway.{interface}_{operation}({example_params})
        >>> print(result)
        {example_output}
    """
    return execute_operation(
        GatewayInterface.{INTERFACE},
        '{operation}',
        {param}={param},
        ...
    )
```

---

## INTERFACE EXAMPLES

### Cache Interface Wrappers

```python
# gateway_wrappers_cache.py
from typing import Any, Optional

def cache_get(key: str) -> Optional[Any]:
    """Get value from cache."""
    return execute_operation(GatewayInterface.CACHE, 'get', key=key)

def cache_set(key: str, value: Any, ttl: Optional[int] = None) -> bool:
    """Set value in cache with optional TTL."""
    return execute_operation(
        GatewayInterface.CACHE, 
        'set', 
        key=key, 
        value=value, 
        ttl=ttl
    )

def cache_delete(key: str) -> bool:
    """Delete key from cache."""
    return execute_operation(GatewayInterface.CACHE, 'delete', key=key)

def cache_clear() -> bool:
    """Clear all cache entries."""
    return execute_operation(GatewayInterface.CACHE, 'clear')

def cache_exists(key: str) -> bool:
    """Check if key exists in cache."""
    return execute_operation(GatewayInterface.CACHE, 'exists', key=key)

def cache_keys(pattern: Optional[str] = None) -> list:
    """Get all cache keys matching pattern."""
    return execute_operation(GatewayInterface.CACHE, 'keys', pattern=pattern)
```

### Logging Interface Wrappers

```python
# gateway_wrappers_logging.py
from typing import Any, Dict

def log_info(message: str, context: Optional[Dict] = None):
    """Log info level message."""
    return execute_operation(
        GatewayInterface.LOGGING, 
        'info', 
        message=message,
        context=context
    )

def log_error(message: str, error: Optional[Exception] = None, context: Optional[Dict] = None):
    """Log error level message with optional exception."""
    return execute_operation(
        GatewayInterface.LOGGING,
        'error',
        message=message,
        error=error,
        context=context
    )

def log_debug(message: str, context: Optional[Dict] = None):
    """Log debug level message."""
    return execute_operation(
        GatewayInterface.LOGGING,
        'debug',
        message=message,
        context=context
    )

def log_warning(message: str, context: Optional[Dict] = None):
    """Log warning level message."""
    return execute_operation(
        GatewayInterface.LOGGING,
        'warning',
        message=message,
        context=context
    )
```

### HTTP Interface Wrappers

```python
# gateway_wrappers_http.py
from typing import Optional, Dict, Any

def http_get(url: str, headers: Optional[Dict] = None, timeout: int = 30) -> Dict[str, Any]:
    """Execute HTTP GET request."""
    return execute_operation(
        GatewayInterface.HTTP,
        'get',
        url=url,
        headers=headers,
        timeout=timeout
    )

def http_post(url: str, data: Any, headers: Optional[Dict] = None, timeout: int = 30) -> Dict[str, Any]:
    """Execute HTTP POST request."""
    return execute_operation(
        GatewayInterface.HTTP,
        'post',
        url=url,
        data=data,
        headers=headers,
        timeout=timeout
    )

def http_put(url: str, data: Any, headers: Optional[Dict] = None, timeout: int = 30) -> Dict[str, Any]:
    """Execute HTTP PUT request."""
    return execute_operation(
        GatewayInterface.HTTP,
        'put',
        url=url,
        data=data,
        headers=headers,
        timeout=timeout
    )
```

---

## BENEFITS

### 1. Type Safety

**Without Wrappers:**
```python
# No type hints, easy to make mistakes
result = execute_operation(GatewayInterface.CACHE, 'get', ky="user")  # Typo!
```

**With Wrappers:**
```python
# Type hints catch errors
result = gateway.cache_get(ky="user")  # IDE shows error: unexpected keyword argument
result = gateway.cache_get(key="user")  # IDE validates this
```

### 2. Documentation

**Without Wrappers:**
```python
# No docstring at call site
help(execute_operation)  # Shows generic execute_operation help
```

**With Wrappers:**
```python
# Specific docstring for each function
help(gateway.cache_get)
# Shows:
# Get value from cache.
# 
# Args:
#     key: Cache key to lookup
# Returns:
#     Cached value or None if not found
```

### 3. IDE Support

**Without Wrappers:**
- No autocomplete for operations
- No parameter hints
- Must remember operation names

**With Wrappers:**
- Full autocomplete: `gateway.cache_` shows all cache operations
- Parameter hints: `gateway.cache_get(` shows `key: str`
- Quick documentation on hover

### 4. Testing

**Without Wrappers:**
```python
# Hard to mock
with patch('gateway.execute_operation') as mock:
    mock.return_value = "value"
    # Must specify exact interface and operation
```

**With Wrappers:**
```python
# Easy to mock
with patch('gateway.cache_get') as mock:
    mock.return_value = "value"
    # Clean and simple
```

---

## WRAPPER DESIGN PRINCIPLES

### Principle 1: One Function Per Operation

**âœ… Do:**
```python
def cache_get(key: str):
    """Get from cache."""
    return execute_operation(GatewayInterface.CACHE, 'get', key=key)

def cache_set(key: str, value: Any):
    """Set in cache."""
    return execute_operation(GatewayInterface.CACHE, 'set', key=key, value=value)
```

**âŒ Don't:**
```python
def cache(operation: str, **kwargs):
    """Generic cache operation."""
    return execute_operation(GatewayInterface.CACHE, operation, **kwargs)
```

### Principle 2: Meaningful Names

**âœ… Do:**
```python
def cache_get(key: str):  # Clear what it does
def cache_set(key: str, value: Any):  # Clear what it does
```

**âŒ Don't:**
```python
def c_g(k: str):  # Unclear
def cache_operation_get(key: str):  # Too verbose
```

### Principle 3: Type Hints Always

**âœ… Do:**
```python
def cache_get(key: str) -> Optional[Any]:
    """Get value from cache."""
    return execute_operation(GatewayInterface.CACHE, 'get', key=key)
```

**âŒ Don't:**
```python
def cache_get(key):  # No type hints
    """Get value from cache."""
    return execute_operation(GatewayInterface.CACHE, 'get', key=key)
```

### Principle 4: Comprehensive Docstrings

**âœ… Do:**
```python
def cache_set(key: str, value: Any, ttl: Optional[int] = None) -> bool:
    """
    Set value in cache with optional TTL.
    
    Args:
        key: Cache key
        value: Value to cache (must be JSON serializable)
        ttl: Time to live in seconds (optional)
        
    Returns:
        bool: True if successful, False otherwise
        
    Raises:
        ValueError: If key is empty or invalid
        
    Example:
        >>> gateway.cache_set("user:123", {"name": "John"}, ttl=300)
        True
    """
    return execute_operation(
        GatewayInterface.CACHE,
        'set',
        key=key,
        value=value,
        ttl=ttl
    )
```

---

## RELATED

**Architectures:**
- CR1-01: Registry Concept (uses wrappers)
- CR1-03: Consolidation Strategy (exports wrappers)
- DD-1: Interface routers (called by wrappers)

**Decisions:**
- CR1-DEC-01: Central Registry
- CR1-DEC-02: Export Consolidation

---

## KEYWORDS

wrapper-functions, type-safety, documentation, ide-support, api-design, convenience-functions, gateway-wrappers, cr-1

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial wrapper pattern documentation
- Design principles defined
- Interface examples provided
- Benefits documented

---

**END OF FILE**

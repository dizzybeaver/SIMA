# CR1-01-Registry-Concept.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Cache Registry pattern - central function registry and API consolidation  
**Type:** Architecture Pattern

---

## PATTERN: Cache Registry (CR-1)

**Type:** Consolidation Pattern  
**Origin:** LEE project gateway implementation  
**Used In:** LEE gateway.py and all interface wrappers  
**Problem Solved:** API discovery and consolidation

---

## PROBLEM STATEMENT

Without central registry:
- Functions scattered across many modules (12+ interfaces)
- Difficult to discover available functions
- No single import point for users
- Hard to maintain function catalog
- Duplicate routing logic
- No fast path optimization

**Example (Before CR-1):**
```python
# User must know exactly where each function lives
from interface_cache import cache_get
from interface_logging import log_info
from interface_metrics import track_time
from interface_security import encrypt
from interface_http import http_get
# ... 100+ more imports!
```

**Problems:**
- Must know all interface modules
- Must know which module has which function
- Easy to import wrong function
- Cannot easily list all available functions

---

## SOLUTION: CACHE REGISTRY PATTERN

**Core Idea:** Central registry maps interfaces to their routers, with consolidated gateway exports.

**Three Components:**

### 1. Central Registry

Maps interfaces to their router functions:
```python
_INTERFACE_ROUTERS = {
    GatewayInterface.CACHE: ('interface_cache', 'execute_cache_operation'),
    GatewayInterface.LOGGING: ('interface_logging', 'execute_logging_operation'),
    GatewayInterface.METRICS: ('interface_metrics', 'execute_metrics_operation'),
    # ... all 12 interfaces
}
```

### 2. Wrapper Functions

Convenience functions for each interface:
```python
# gateway_wrappers_cache.py
def cache_get(key: str):
    """Get value from cache."""
    return execute_operation(GatewayInterface.CACHE, 'get', key=key)

def cache_set(key: str, value: Any):
    """Set value in cache."""
    return execute_operation(GatewayInterface.CACHE, 'set', key=key, value=value)
```

### 3. Consolidated Gateway

Single import point with all functions:
```python
# gateway.py
from gateway_wrappers_cache import cache_get, cache_set
from gateway_wrappers_logging import log_info, log_error
from gateway_wrappers_metrics import track_time, increment
# ... all wrappers

__all__ = [
    'cache_get', 'cache_set',
    'log_info', 'log_error',
    'track_time', 'increment',
    # ... 100+ functions
]
```

---

## BENEFITS

### 1. Single Import Point

**Before (Scattered):**
```python
from interface_cache import cache_get
from interface_logging import log_info
from interface_metrics import track_time
```

**After (Consolidated):**
```python
import gateway
# All functions available!
gateway.cache_get(key)
gateway.log_info(message)
gateway.track_time(operation)
```

### 2. Easy Discovery

**Function Catalog:**
```python
# List all available functions
print(dir(gateway))
# Returns: ['cache_get', 'cache_set', 'log_info', ...]

# Get help on any function
help(gateway.cache_get)
# Returns: Full docstring
```

### 3. Consistent API

**All functions follow same pattern:**
```python
gateway.{interface}_{action}({params})

gateway.cache_get(key)        # Cache interface, get action
gateway.log_info(message)     # Logging interface, info action  
gateway.http_get(url)         # HTTP interface, get action
```

### 4. Fast Path Optimization

**Registry enables caching:**
```python
# First call: Module import + function lookup
result = gateway.cache_get(key)  # ~2ms

# Subsequent calls: Cached (fast path)
result = gateway.cache_get(key)  # ~0.05ms (40x faster!)
```

### 5. Centralized Maintenance

**Single point to:**
- Add new functions
- Update documentation
- Implement cross-cutting concerns (logging, metrics)
- Apply security policies

---

## ARCHITECTURE

### Layer Organization

```
gateway.py (Consolidated Exports)
    â†" imports from
gateway_wrappers_*.py (Wrapper Functions)
    â†" use
gateway_core.py (Central Registry + Execute Operation)
    â†" routes to
interface_*.py (Interface Routers)
    â†" implement via
*_core.py (Core Implementations)
```

### Central Registry Structure

```python
# gateway_core.py
from enum import Enum

class GatewayInterface(Enum):
    CACHE = "cache"
    LOGGING = "logging"
    METRICS = "metrics"
    SECURITY = "security"
    HTTP = "http"
    # ... all 12 interfaces

_INTERFACE_ROUTERS = {
    GatewayInterface.CACHE: (
        'interface_cache',              # Module name
        'execute_cache_operation'       # Router function
    ),
    GatewayInterface.LOGGING: (
        'interface_logging',
        'execute_logging_operation'
    ),
    # ... all mappings
}

def execute_operation(interface: GatewayInterface, operation: str, **kwargs):
    """Execute operation through interface router."""
    module_name, func_name = _INTERFACE_ROUTERS[interface]
    module = importlib.import_module(module_name)
    router = getattr(module, func_name)
    return router(operation, **kwargs)
```

---

## IMPLEMENTATION PATTERNS

### Pattern 1: Interface Router

Each interface has a router function:
```python
# interface_cache.py
def execute_cache_operation(operation: str, **kwargs):
    """Route cache operations to implementations."""
    operations = {
        'get': lambda: cache_core.get_impl(kwargs['key']),
        'set': lambda: cache_core.set_impl(kwargs['key'], kwargs['value']),
        'delete': lambda: cache_core.delete_impl(kwargs['key']),
        'clear': lambda: cache_core.clear_impl(),
    }
    
    handler = operations.get(operation)
    if not handler:
        raise ValueError(f"Unknown cache operation: {operation}")
    
    return handler()
```

### Pattern 2: Wrapper Functions

Convenience wrappers per interface:
```python
# gateway_wrappers_cache.py
def cache_get(key: str):
    """
    Get value from cache.
    
    Args:
        key: Cache key
        
    Returns:
        Cached value or None
        
    Example:
        value = gateway.cache_get("user:123")
    """
    return execute_operation(GatewayInterface.CACHE, 'get', key=key)
```

### Pattern 3: Consolidated Exports

Gateway consolidates all exports:
```python
# gateway.py
"""
Gateway module - single import point for all functions.

Usage:
    import gateway
    
    # Cache operations
    gateway.cache_get(key)
    gateway.cache_set(key, value)
    
    # Logging operations
    gateway.log_info(message)
    gateway.log_error(error)
    
    # ... 100+ more functions
"""

# Import all wrapper modules
from gateway_wrappers_cache import *
from gateway_wrappers_logging import *
from gateway_wrappers_metrics import *
# ... all wrappers

# Export everything
__all__ = [
    # Cache functions (10+)
    'cache_get', 'cache_set', 'cache_delete', 'cache_clear',
    # Logging functions (15+)
    'log_info', 'log_error', 'log_debug', 'log_warning',
    # Metrics functions (20+)
    'track_time', 'increment', 'gauge', 'histogram',
    # ... all 100+ functions
]
```

---

## COMPARISON TO ALTERNATIVES

### Alternative 1: Direct Interface Imports

**Pattern:**
```python
from interface_cache import cache_get
from interface_logging import log_info
```

**Problems:**
- Must know all interface modules
- Hard to discover functions
- Many import statements
- No fast path optimization

### Alternative 2: No Wrappers (Direct Execute Calls)

**Pattern:**
```python
import gateway
gateway.execute_operation(GatewayInterface.CACHE, 'get', key=key)
```

**Problems:**
- Verbose
- No type hints
- No docstrings at call site
- Hard to discover operations

### Alternative 3: CR-1 (Chosen)

**Pattern:**
```python
import gateway
gateway.cache_get(key)
```

**Benefits:**
- Single import
- Type hints
- Docstrings
- Easy discovery
- Fast path optimization

---

## KEYWORDS

cache-registry, central-registry, api-consolidation, function-catalog, gateway-pattern, single-import, discovery, wrapper-functions, cr-1

---

## RELATED

**Architectures:**
- SUGA: CR-1 implements gateway layer
- DD-1: Interface routers use dictionary dispatch
- ZAPH: Fast path caching for frequent operations

**Files:**
- CR1-02: Wrapper Pattern
- CR1-03: Consolidation Strategy
- CR1-DEC-01: Central Registry Decision

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial CR-1 core concept
- Registry pattern defined
- Three components explained
- Benefits documented

---

**END OF FILE**

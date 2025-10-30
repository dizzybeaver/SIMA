# NEURAL MAP 01: Core Architecture - IMPLEMENTATION (Core)

**Purpose:** SIMA architecture structure and patterns  
**Status:** ✅ ACTIVE  
**Last Updated:** 2025-10-20  
**Parent Index:** NM01-INDEX-Architecture.md

**Contains:** ARCH-01 through ARCH-06 (6 architecture references)

---

## CRITICAL: Terminology

**SIMA = Architecture Pattern**
- **S**ingle Universal Gateway **A**rchitecture  
- **I**nterface Separation **P**rinciple (ISP)  
- **M**odular **A**rchitecture  
- Pattern: Gateway → Interface → Implementation (3 layers)

**SUGA-ISP = Lambda Project Name**
- Uses SIMA architecture pattern
- Project files: `gateway.py`, `interface_*.py`, `*_core.py`

---

## ARCH-01: Gateway Trinity

**REF:** NM01-ARCH-01  
**PRIORITY:** 🔴 CRITICAL  
**TAGS:** gateway, SIMA, architecture, trinity, core-files  
**KEYWORDS:** gateway files, master facade, routing engine, wrappers  
**RELATED:** NM04-DEC-01, NM02-DEP-01, NM07-DT-01

### The Three Core Files

The SUGA-ISP Lambda project uses SIMA architecture, implemented through three gateway files:

```
gateway.py (Master Facade)
├─ Purpose: Single import point for all users
├─ Exports: All wrapper functions + core functions
├─ Pattern: Aggregates gateway_core + gateway_wrappers
└─ Usage: from gateway import cache_get, log_info, http_post

gateway_core.py (Routing Engine)
├─ Purpose: Route operations to interface routers
├─ Registry: _INTERFACE_ROUTERS (12 interfaces)
├─ Function: execute_operation(interface, operation, **kwargs)
├─ Pattern: Pattern-based routing (interface → router)
└─ Fast Path: Optional caching for frequent operations

gateway_wrappers.py (Convenience Layer)
├─ Purpose: User-friendly function names
├─ Pattern: Wraps execute_operation() calls
├─ Count: 90+ wrapper functions
└─ Example: cache_get() → execute_operation(CACHE, 'get', ...)
```

### Why This Matters

The gateway trinity implements the "Single Universal Gateway" principle - **ALL** cross-interface communication flows through `gateway.py`. This prevents circular imports and creates a testable, maintainable architecture.

**REAL-WORLD USAGE:**
- User: "Where do I import gateway functions from?"
- Claude: "Import from `gateway.py` - the master facade that aggregates all gateway functions."

---

## ARCH-02: Gateway Execution Engine

**REF:** NM01-ARCH-02  
**PRIORITY:** 🔴 CRITICAL  
**TAGS:** gateway-core, execute_operation, routing, function-signature  
**KEYWORDS:** gateway function, operation execution, routing signature  
**RELATED:** NM01-ARCH-01, NM01-ARCH-03, NM03-PATH-01

### Core Function Signature

The heart of the SIMA architecture is the `execute_operation` function:

```python
def execute_operation(
    interface: GatewayInterface,  # Which interface
    operation: str,               # Which operation
    **kwargs                      # Operation parameters
) -> Any:                         # Return value
    """
    Universal operation executor.
    ALL cross-interface communication flows through here.
    """
    # 1. Lazy-load interface router
    # 2. Call router's execute function
    # 3. Return result or propagate exception
```

### Gateway Interface Enumeration

```python
class GatewayInterface(Enum):
    """All 12 system interfaces."""
    CACHE = "cache"
    LOGGING = "logging"
    SECURITY = "security"
    METRICS = "metrics"
    CONFIG = "config"
    SINGLETON = "singleton"
    INITIALIZATION = "initialization"
    HTTP_CLIENT = "http_client"
    WEBSOCKET = "websocket"
    CIRCUIT_BREAKER = "circuit_breaker"
    UTILITY = "utility"
    DEBUG = "debug"
```

### Interface Router Registry

```python
_INTERFACE_ROUTERS = {
    GatewayInterface.CACHE: "interface_cache",
    GatewayInterface.LOGGING: "interface_logging",
    # ... 10 more interfaces
}
```

### Execution Flow

```
1. User calls: gateway.cache_get(key)
2. Wrapper calls: execute_operation(CACHE, 'get', key=key)
3. Gateway loads: interface_cache module (lazy)
4. Gateway calls: interface_cache.execute_cache_operation('get', key=key)
5. Router dispatches: cache_core._execute_get_implementation(key=key)
6. Result returns: up through layers
```

**REAL-WORLD USAGE:**
- User: "How does gateway routing work?"
- Claude: "All operations go through `execute_operation(interface, operation, **kwargs)` which lazy-loads the interface router and dispatches to the correct implementation."

---

## ARCH-03: Router Pattern

**REF:** NM01-ARCH-03  
**PRIORITY:** 🟡 HIGH  
**TAGS:** router-pattern, interface-router, dispatch, architecture  
**KEYWORDS:** router pattern, interface routing, dispatch pattern  
**RELATED:** NM04-DEC-03, NM01-ARCH-02, NM05-AP-02

### Standard Router Structure

Every interface router in the SUGA-ISP project follows this SIMA pattern:

```python
"""
interface_<name>.py - <Interface> Router
Router for <Interface> interface operations.
"""

from typing import Any, Dict, Callable

# Import internal implementation (same interface)
from <name>_core import _execute_<op>_implementation

# Import gateway for cross-interface needs
from gateway import log_error

# Dispatch dictionary (O(1) lookup)
_OPERATION_DISPATCH: Dict[str, Callable] = {
    'operation_name': _execute_<op>_implementation,
    # ... more operations
}

def execute_<name>_operation(operation: str, **kwargs) -> Any:
    """
    Route operation to internal implementation.
    Called by gateway_core.execute_operation().
    """
    if operation not in _OPERATION_DISPATCH:
        raise ValueError(f"Unknown operation: {operation}")
    
    handler = _OPERATION_DISPATCH[operation]
    
    try:
        return handler(**kwargs)
    except Exception as e:
        log_error(f"Error in {operation}: {str(e)}", error=e)
        raise

__all__ = ['execute_<name>_operation']
```

### Router Responsibilities

1. **Dispatch operations** to internal implementations using O(1) dict lookup
2. **Catch exceptions** and log errors via gateway
3. **Import protection** (try/except on imports when needed)
4. **Gateway delegation** for cross-interface needs
5. **Parameter validation** (optional, interface-specific)

### Router Import Rules

```python
# ✅ ALLOWED: Import from same interface
from cache_core import _execute_get_implementation

# ✅ ALLOWED: Import from gateway for cross-interface
from gateway import log_error, record_metric

# ❌ FORBIDDEN: Import from other interface internals
from logging_core import log_info  # WRONG - use gateway

# ❌ FORBIDDEN: Import other interface routers
from interface_logging import execute_logging_operation  # WRONG
```

### Why Dictionary Dispatch?

The dispatch dictionary pattern provides:
- **O(1) operation lookup** vs O(n) for if/elif chains
- **Easy extension** - just add to dictionary
- **Consistent pattern** across all 12 interfaces
- **Runtime flexibility** - operations can be added dynamically

**REAL-WORLD USAGE:**
- User: "What's the standard pattern for interface routers?"
- Claude: "All routers follow the SIMA pattern: dispatch dictionary, exception catching, gateway delegation for cross-interface communication."

---

## ARCH-04: Internal Implementation Pattern

**REF:** NM01-ARCH-04  
**PRIORITY:** 🟡 HIGH  
**TAGS:** implementation-pattern, core-files, internal-logic  
**KEYWORDS:** core implementation, internal pattern, business logic  
**RELATED:** NM01-ARCH-03, NM07-DT-02

### Standard Core Structure

Every internal core file follows this pattern:

```python
"""
<name>_core.py - <Interface> Core Implementation
Business logic for <interface> operations.
"""

from typing import Any, Dict

# Import gateway for cross-interface needs
from gateway import log_info, log_error

# State storage (if needed)
_STATE_STORE: Dict[str, Any] = {}

def _execute_<op>_implementation(**kwargs) -> Any:
    """
    Implementation of <operation>.
    Called by interface router.
    """
    # Business logic here
    # Use gateway for cross-interface calls
    # Manage state if stateful
    # Return result or raise exception
    pass

# Export implementation functions
__all__ = [
    '_execute_<op>_implementation',
    # ... more implementations
]
```

### Core Responsibilities

1. **Implement business logic** for operations
2. **Use gateway** for ALL cross-interface communication
3. **Manage state** (if stateful interface like CACHE, SINGLETON)
4. **Return results** or raise exceptions
5. **No direct imports** from other interfaces

### Core Import Rules

```python
# ✅ ALLOWED: Import from gateway
from gateway import log_error, cache_get, get_config

# ✅ ALLOWED: Import Python standard library
import json, time, uuid

# ✅ ALLOWED: Import AWS SDK (for Lambda)
import boto3

# ❌ FORBIDDEN: Import other interface cores
from logging_core import _log_message  # WRONG - use gateway

# ❌ FORBIDDEN: Import other interface routers
from interface_cache import execute_cache_operation  # WRONG
```

### State Management Pattern

For stateful interfaces (CACHE, SINGLETON, CONFIG):

```python
# Module-level state storage
_CACHE_STORE: Dict[str, Any] = {}
_CACHE_TTL: Dict[str, float] = {}

def _execute_set_implementation(key: str, value: Any, ttl: int = 300) -> bool:
    """Store value with TTL."""
    _CACHE_STORE[key] = value
    _CACHE_TTL[key] = time.time() + ttl
    return True

def _execute_get_implementation(key: str, default: Any = None) -> Any:
    """Retrieve value if not expired."""
    if key not in _CACHE_STORE:
        return default
    
    if time.time() > _CACHE_TTL.get(key, 0):
        del _CACHE_STORE[key]
        del _CACHE_TTL[key]
        return default
    
    return _CACHE_STORE[key]
```

**REAL-WORLD USAGE:**
- User: "How do I organize implementation code?"
- Claude: "Put business logic in `<name>_core.py`, use gateway for cross-interface calls, manage state at module level if needed."

---

## ARCH-05: Extension Architecture

**REF:** NM01-ARCH-05  
**PRIORITY:** 🟢 MEDIUM  
**TAGS:** extension, new-interface, scalability  
**KEYWORDS:** add interface, extension pattern, scalability  
**RELATED:** NM02-DEP-05, NM04-DEC-02

### Adding a New Interface

The SIMA architecture makes adding new interfaces straightforward:

#### Step 1: Add to Gateway Enum
```python
# In gateway_core.py
class GatewayInterface(Enum):
    # ... existing interfaces
    NEW_INTERFACE = "new_interface"
```

#### Step 2: Create Router File
```python
# Create interface_new.py
from typing import Any, Dict, Callable
from new_core import _execute_operation_implementation
from gateway import log_error

_OPERATION_DISPATCH: Dict[str, Callable] = {
    'operation_name': _execute_operation_implementation,
}

def execute_new_operation(operation: str, **kwargs) -> Any:
    if operation not in _OPERATION_DISPATCH:
        raise ValueError(f"Unknown operation: {operation}")
    
    handler = _OPERATION_DISPATCH[operation]
    
    try:
        return handler(**kwargs)
    except Exception as e:
        log_error(f"Error in {operation}: {str(e)}", error=e)
        raise

__all__ = ['execute_new_operation']
```

#### Step 3: Create Core Implementation
```python
# Create new_core.py
from typing import Any
from gateway import log_info

def _execute_operation_implementation(**kwargs) -> Any:
    """Implementation logic."""
    log_info("Executing new operation")
    # Business logic here
    return result

__all__ = ['_execute_operation_implementation']
```

#### Step 4: Register in Gateway
```python
# In gateway_core.py
_INTERFACE_ROUTERS = {
    # ... existing routers
    GatewayInterface.NEW_INTERFACE: "interface_new",
}
```

#### Step 5: Add Wrappers (Optional)
```python
# In gateway_wrappers.py
def new_operation(**kwargs) -> Any:
    """Convenience wrapper for new operation."""
    return execute_operation(
        GatewayInterface.NEW_INTERFACE,
        'operation_name',
        **kwargs
    )
```

#### Step 6: Update Neural Maps
```
- Add to NM01 (this file) with new INT-XX reference
- Update NM02 with dependency information
- Update NM00A Master Index
```

### Extension Guidelines

**DO:**
- Follow the 3-layer SIMA pattern (Gateway → Interface → Implementation)
- Use dictionary dispatch for operations
- Import gateway for cross-interface needs
- Add comprehensive logging
- Update all relevant neural maps

**DON'T:**
- Import other interface internals directly
- Skip the router layer
- Use if/elif chains instead of dispatch dicts
- Forget to update _INTERFACE_ROUTERS

**REAL-WORLD USAGE:**
- User: "How do I add a new interface?"
- Claude: "Follow 6 steps: (1) Add to enum, (2) Create router, (3) Create core, (4) Register in gateway, (5) Add wrappers, (6) Update docs."

---

## ARCH-06: Lambda Entry Point

**REF:** NM01-ARCH-06  
**PRIORITY:** 🟡 HIGH  
**TAGS:** lambda, entry-point, handler, AWS  
**KEYWORDS:** lambda handler, entry point, AWS Lambda  
**RELATED:** NM03-PATH-01, NM01-ARCH-01

### Lambda Handler Pattern

The SUGA-ISP Lambda uses this entry point structure:

```python
# In lambda_function.py or similar
from gateway import (
    initialize_system,
    log_info,
    log_error,
    format_response
)

def lambda_handler(event, context):
    """
    AWS Lambda entry point.
    All Lambda invocations start here.
    """
    try:
        # Initialize system on cold start
        initialize_system()
        
        # Log incoming request
        log_info("Lambda invoked", extra={
            'request_id': context.request_id,
            'event_type': event.get('type')
        })
        
        # Route to appropriate handler
        # (using gateway operations)
        result = process_event(event, context)
        
        # Return formatted response
        return format_response(
            success=True,
            message="Success",
            data=result
        )
        
    except Exception as e:
        # Log error and return error response
        log_error("Lambda error", error=e)
        return format_response(
            success=False,
            message=str(e),
            data=None
        )

def process_event(event, context):
    """Process the event using gateway operations."""
    # All business logic uses gateway
    # Example: cache_get, http_post, etc.
    pass
```

### Key Patterns

1. **Initialize on cold start**: Call `initialize_system()` first
2. **Use gateway for everything**: No direct imports of interface internals
3. **Comprehensive logging**: Log all significant events
4. **Error handling**: Catch all exceptions at top level
5. **Formatted responses**: Use consistent response format

### Lambda Configuration

The SUGA-ISP Lambda is configured with:
- **Handler**: `lambda_function.lambda_handler`
- **Runtime**: Python 3.9+
- **Memory**: 512 MB (configurable)
- **Timeout**: 30 seconds (configurable)
- **Environment Variables**: See Lambda configuration docs

### File Structure

```
project_root/
├─ gateway.py                  # Master facade
├─ gateway_core.py             # Routing engine
├─ gateway_wrappers.py         # Convenience layer
├─ lambda_function.py          # Lambda entry point
├─ interface_cache.py          # Cache router
├─ cache_core.py               # Cache implementation
├─ interface_logging.py        # Logging router
├─ logging_core.py             # Logging implementation
└─ ... (10 more interface pairs)
```

**Exception**: The `home_assistant/` subdirectory is the only allowed subdirectory (for Home Assistant integration files).

**REAL-WORLD USAGE:**
- User: "Where does Lambda start execution?"
- Claude: "`lambda_function.lambda_handler` is the entry point. It initializes the system, processes the event using gateway operations, and returns formatted responses."

---

## Integration Notes

### How SIMA Components Work Together

```
AWS Lambda Invocation
  ↓
lambda_handler() [ARCH-06]
  ↓
gateway.cache_get() [ARCH-01: Wrapper]
  ↓
execute_operation(CACHE, 'get', key='x') [ARCH-02: Core]
  ↓
interface_cache.execute_cache_operation('get', key='x') [ARCH-03: Router]
  ↓
cache_core._execute_get_implementation(key='x') [ARCH-04: Implementation]
  ↓
Returns value back up the chain
```

### Cross-Interface Communication

```
cache_core.py needs to log something:
  ↓
from gateway import log_info [ARCH-04 imports ARCH-01]
  ↓
log_info("Cache hit")
  ↓
execute_operation(LOGGING, 'info', message="Cache hit") [ARCH-02]
  ↓
interface_logging.execute_logging_operation('info', ...) [ARCH-03]
  ↓
logging_core._execute_info_implementation(...) [ARCH-04]
```

### Why This Architecture?

The SIMA pattern solves several problems:

1. **No Circular Imports**: Gateway is the only cross-interface bridge
2. **Easy Testing**: Each layer can be tested independently
3. **Clear Boundaries**: Interface routers act as firewalls
4. **Scalability**: Add interfaces without modifying existing code
5. **Maintainability**: Consistent patterns across all interfaces
6. **Performance**: O(1) dispatch, lazy loading, optional caching

---

## Related Neural Maps

- **NM02: Interface Dependency Web** - How interfaces depend on each other
- **NM03: Operational Pathways** - Data flow through the architecture
- **NM04: Design Decisions** - Why SIMA was chosen
- **NM05: Anti-Patterns** - Common mistakes to avoid
- **NM06: Learned Experiences** - Real bugs and lessons
- **NM07: Decision Logic** - Decision trees for development

---

## End Notes

This file documents the core SIMA architecture patterns used in the SUGA-ISP Lambda project. All 6 architecture references (ARCH-01 through ARCH-06) are contained in this file.

For interface specifications, see:
- **NM01-INTERFACES-Core.md** - INT-01 through INT-06
- **NM01-INTERFACES-Advanced.md** - INT-07 through INT-12

**Status:** ✅ ACTIVE - Ready for use

# EOF

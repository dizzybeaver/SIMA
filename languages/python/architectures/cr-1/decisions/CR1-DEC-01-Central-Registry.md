# CR1-DEC-01-Central-Registry.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Decision to use central registry for interface routing  
**Type:** Architecture Decision

---

## DECISION: Central Registry Maps Interfaces to Routers

**Status:** Adopted  
**Date:** 2024-10-15  
**Context:** LEE project gateway architecture  
**Impact:** All interface routing in gateway layer

---

## PROBLEM STATEMENT

Without central registry:
- Routing logic duplicated across wrapper modules
- Hard to add new interfaces
- No single source of truth for interface mappings
- Difficult to maintain consistency

---

## DECISION

**Create `_INTERFACE_ROUTERS` dictionary that maps each interface to its router function.**

**Structure:**
```python
_INTERFACE_ROUTERS = {
    GatewayInterface.CACHE: ('interface_cache', 'execute_cache_operation'),
    GatewayInterface.LOGGING: ('interface_logging', 'execute_logging_operation'),
    # ... all 12 interfaces
}
```

**Usage:**
```python
def execute_operation(interface: GatewayInterface, operation: str, **kwargs):
    module_name, func_name = _INTERFACE_ROUTERS[interface]
    module = importlib.import_module(module_name)
    router = getattr(module, func_name)
    return router(operation, **kwargs)
```

---

## RATIONALE

### Benefits

**1. Single Source of Truth**
- All interface mappings in one place
- Easy to see all interfaces
- No duplication

**2. Easy to Extend**
- Add new interface: One line in registry
- Modify routing: Update registry only
- Clear process

**3. Fast Path Optimization**
- Registry enables caching
- First call: ~2ms (import + lookup)
- Subsequent: ~0.05ms (cached)

**4. Consistency**
- All wrappers use same routing mechanism
- No special cases
- Predictable behavior

### Trade-offs

**Cost: One Indirection Layer**
- Extra dictionary lookup
- Dynamic import/getattr calls

**Mitigation:** Fast path caching makes subsequent calls very fast

---

## IMPLEMENTATION

```python
# gateway_core.py
from enum import Enum
import importlib

class GatewayInterface(Enum):
    CACHE = "cache"
    LOGGING = "logging"
    METRICS = "metrics"
    SECURITY = "security"
    HTTP = "http"
    CONFIG = "config"
    DEBUG = "debug"
    INITIALIZATION = "initialization"
    SINGLETON = "singleton"
    UTILITY = "utility"
    WEBSOCKET = "websocket"
    CIRCUIT_BREAKER = "circuit_breaker"

_INTERFACE_ROUTERS = {
    GatewayInterface.CACHE: ('interface_cache', 'execute_cache_operation'),
    GatewayInterface.LOGGING: ('interface_logging', 'execute_logging_operation'),
    GatewayInterface.METRICS: ('interface_metrics', 'execute_metrics_operation'),
    GatewayInterface.SECURITY: ('interface_security', 'execute_security_operation'),
    GatewayInterface.HTTP: ('interface_http', 'execute_http_operation'),
    GatewayInterface.CONFIG: ('interface_config', 'execute_config_operation'),
    GatewayInterface.DEBUG: ('interface_debug', 'execute_debug_operation'),
    GatewayInterface.INITIALIZATION: ('interface_initialization', 'execute_initialization_operation'),
    GatewayInterface.SINGLETON: ('interface_singleton', 'execute_singleton_operation'),
    GatewayInterface.UTILITY: ('interface_utility', 'execute_utility_operation'),
    GatewayInterface.WEBSOCKET: ('interface_websocket', 'execute_websocket_operation'),
    GatewayInterface.CIRCUIT_BREAKER: ('interface_circuit_breaker', 'execute_circuit_breaker_operation'),
}

# Fast path cache for frequent operations
_ROUTER_CACHE = {}

def execute_operation(interface: GatewayInterface, operation: str, **kwargs):
    """
    Execute operation through interface router.
    
    Args:
        interface: Target interface
        operation: Operation name
        **kwargs: Operation parameters
        
    Returns:
        Operation result
    """
    # Fast path: Check cache
    cache_key = (interface, operation)
    if cache_key in _ROUTER_CACHE:
        router = _ROUTER_CACHE[cache_key]
        return router(operation, **kwargs)
    
    # Slow path: Import and cache
    module_name, func_name = _INTERFACE_ROUTERS[interface]
    module = importlib.import_module(module_name)
    router = getattr(module, func_name)
    
    # Cache for next call
    _ROUTER_CACHE[cache_key] = router
    
    return router(operation, **kwargs)
```

---

## CONSEQUENCES

### Positive

**Clear Mappings:**
```python
# Easy to see all interfaces and their routers
for interface, (module, func) in _INTERFACE_ROUTERS.items():
    print(f"{interface.name} -> {module}.{func}")
```

**Easy Additions:**
```python
# Add new interface in 3 places:
# 1. GatewayInterface enum
class GatewayInterface(Enum):
    NEW_INTERFACE = "new_interface"

# 2. Registry mapping
_INTERFACE_ROUTERS = {
    GatewayInterface.NEW_INTERFACE: ('interface_new', 'execute_new_operation'),
}

# 3. Create wrapper functions
def new_operation():
    return execute_operation(GatewayInterface.NEW_INTERFACE, 'operation')
```

### Negative

**Runtime Errors:**
- Typos in module/function names only caught at runtime
- Must test each interface to verify registry

**Mitigation:** Add validation at startup

---

## ALTERNATIVES CONSIDERED

### Alternative 1: Hardcoded Routing in Each Wrapper

```python
def cache_get(key):
    import interface_cache
    return interface_cache.execute_cache_operation('get', key=key)
```

**Why rejected:** Duplicates routing logic, no fast path optimization

### Alternative 2: Factory Pattern

```python
class RouterFactory:
    def get_router(self, interface):
        if interface == "cache":
            return CacheRouter()
        elif interface == "logging":
            return LoggingRouter()
```

**Why rejected:** More complex, no performance benefit

### Alternative 3: Registry (Chosen)

Simple, fast, maintainable.

---

## RELATED

**Architecture:**
- CR1-01: Registry Concept
- CR1-02: Wrapper Pattern
- CR1-DEC-02: Export Consolidation

**Patterns:**
- ZAPH: Fast path optimization (used in registry)
- DD-1: Dictionary dispatch (similar pattern)

---

## KEYWORDS

central-registry, interface-mapping, routing, fast-path, caching, architecture-decision, cr-1

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial decision document
- Registry structure defined
- Fast path caching included
- Benefits documented

---

**END OF FILE**

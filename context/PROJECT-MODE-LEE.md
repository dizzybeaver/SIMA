# PROJECT-MODE-LEE.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** LEE project-specific development context  
**Project:** LEE (Lambda Execution Engine)  
**Type:** Project Extension

---

## PROJECT: LEE

**Name:** LEE (Lambda Execution Engine)  
**Type:** AWS Lambda + Home Assistant Integration  
**Platform:** AWS Lambda  
**Language:** Python  
**Architecture:** SUGA + LMMS + ZAPH + DD-1 + DD-2 + CR-1

---

## CONSTRAINTS

**AWS Lambda:**
- Memory: 128MB total
- Timeout: 30 seconds
- Single-threaded execution only
- No threading primitives
- Cold start target: < 3 seconds

**Home Assistant:**
- WebSocket connection required
- Token management via SSM
- Device discovery and caching
- Real-time state updates

**Dependencies:**
- Built-in AWS Lambda modules only
- No external libraries (except AWS SDK)
- Flat file structure (except home_assistant/)

---

## ARCHITECTURE

### SUGA Pattern
- Gateway Layer: gateway.py, gateway_wrappers*.py
- Interface Layer: interface_*.py
- Core Layer: *_core.py
- Mandatory lazy imports at each layer
- No direct core imports

### LMMS (Lazy Module Management)
- Function-level imports for cold path
- fast_path.py for hot path only
- Profile with performance_benchmark.py
- Target: < 100ms per import

### ZAPH (Zone Access Priority)
- Tier 1: Hot path (fast_path.py)
- Tier 2: Interface wrappers
- Tier 3: Core implementations
- Zero abstraction on hot path

### DD-1 (Dictionary Dispatch)
- Interface files use dispatch tables
- O(1) action lookup
- DISPATCH dictionary pattern

### DD-2 (Dependency Disciplines)
- Higher → Lower dependencies only
- No circular imports
- Gateway → Interface → Core flow

### CR-1 (Cache Registry)
- Central function registry in gateway.py
- Consolidated exports (~100 functions)
- Fast path caching

---

## PATTERNS

### Interface Implementation
```python
# interface_cache.py
DISPATCH = {
    "get": get_impl,
    "set": set_impl,
    # ... all operations
}

def execute_cache_operation(operation, **kwargs):
    handler = DISPATCH.get(operation)
    return handler(**kwargs)
```

### Gateway Wrapper
```python
# gateway_wrappers_cache.py
def cache_get(key):
    import interface_cache
    return interface_cache.execute_cache_operation("get", key=key)
```

### Lazy Import
```python
# Function-level only
def my_function():
    import required_module
    return required_module.process()
```

---

## WORKFLOWS

### Add Home Assistant Device
1. Modify ha_devices_core.py
2. Add device handler
3. Update device discovery
4. Test with Home Assistant

### Update Interface
1. Modify interface_*.py
2. Add to DISPATCH table
3. Update gateway wrapper
4. Add to gateway.py exports

### Add Action Handler
1. Add to DISPATCH dictionary
2. Implement handler function
3. Update function catalog
4. Test action

---

## RED FLAGS

**LEE-Specific:**
- âŒ Threading locks (Lambda single-threaded)
- âŒ Heavy libraries (128MB limit)
- âŒ Module-level imports (cold start penalty)
- âŒ Direct core imports (breaks SUGA)
- âŒ Subdirectories (flat structure required)
- âŒ Missing sentinel sanitization (JSON fails)
- âŒ _CacheMiss in responses (serialization error)
- âŒ Bare except clauses (swallows errors)
- âŒ Skip file fetch (week-old code)

---

## EXAMPLES

### Interface Implementation
```python
DISPATCH = {
    "turn_on": turn_on_impl,
    "turn_off": turn_off_impl,
}

def execute_light_operation(operation, **kwargs):
    return DISPATCH[operation](**kwargs)
```

### Gateway Consolidation
```python
# gateway.py
from gateway_wrappers_cache import *
from gateway_wrappers_logging import *
__all__ = ['cache_get', 'log_info', ...]  # 100+ functions
```

---

**END OF LEE PROJECT EXTENSION**

**Version:** 1.0.0  
**Lines:** 100 (target achieved)  
**Combine with:** PROJECT-MODE-Context.md (base)

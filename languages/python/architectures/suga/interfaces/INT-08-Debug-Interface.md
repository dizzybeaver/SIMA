# INT-08-Debug-Interface.md

**Version:** 1.0.0  
**Date:** 2025-11-07  
**Purpose:** Debug operations through SUGA pattern  
**Architecture:** SUGA (Gateway Pattern)

---

## INT-08: DEBUG INTERFACE

**Purpose:** Debug operations through SUGA pattern.

### Three-Layer Pattern

```python
# Gateway (gateway_wrappers_debug.py)
def debug_get_state():
    import interface_debug
    return interface_debug.get_state()

def debug_trace(message):
    import interface_debug
    return interface_debug.trace(message)

# Interface (interface_debug.py)
def get_state():
    import debug_core
    return debug_core.get_state_impl()

def trace(message):
    import debug_core
    return debug_core.trace_impl(message)

# Core (debug_core.py)
_trace_log = []

def get_state_impl():
    return {'traces': _trace_log}

def trace_impl(message):
    _trace_log.append(message)
    return True
```

**Keywords:** debug, diagnostics, tracing, state, SUGA

---

## COMMON ANTI-PATTERNS (ALL INTERFACES)

### Anti-Pattern 1: Direct Core Import

```python
# WRONG - Any interface
from cache_core import get_impl
from logging_core import log_impl

# CORRECT
import gateway
gateway.cache_get(key)
gateway.log_info(message)
```

### Anti-Pattern 2: Skipping Interface Layer

```python
# WRONG
import cache_core
cache_core.get_impl(key)

# CORRECT
import gateway
gateway.cache_get(key)
```

---

## CROSS-REFERENCES (ALL INTERFACES)

**Architecture:**
- ARCH-01: Gateway Trinity
- ARCH-02: Layer Separation
- ARCH-03: Interface Pattern

**Gateways:**
- GATE-01: Gateway Entry Pattern
- GATE-02: Lazy Import Pattern
- GATE-03: Cross-Interface Communication

**Decisions:**
- DEC-01: SUGA Choice
- DEC-03: Gateway Mandatory

**Anti-Patterns:**
- AP-01: Direct Core Import
- AP-02: Module-Level Heavy Imports
- AP-04: Skipping Interface Layer

**Lessons:**
- LESS-03: Gateway Entry Point
- LESS-04: Layer Responsibility Clarity
- LESS-07: Base Layers No Dependencies

---

**END OF FILE**

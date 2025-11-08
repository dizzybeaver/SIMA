# INT-12-Circuit-Breaker-Interface.md

**Version:** 1.0.0  
**Date:** 2025-11-07  
**Purpose:** Circuit breaker pattern through SUGA  
**Architecture:** SUGA (Gateway Pattern)

---

## INT-12: CIRCUIT-BREAKER INTERFACE

**Purpose:** Circuit breaker pattern through SUGA.

### Three-Layer Pattern

```python
# Gateway (gateway_wrappers_circuit_breaker.py)
def circuit_call(operation, *args, **kwargs):
    import interface_circuit_breaker
    return interface_circuit_breaker.call(operation, *args, **kwargs)

# Interface (interface_circuit_breaker.py)
def call(operation, *args, **kwargs):
    import circuit_breaker_core
    return circuit_breaker_core.call_impl(operation, *args, **kwargs)

# Core (circuit_breaker_core.py)
_failure_counts = {}
_circuit_open = {}

def call_impl(operation, *args, **kwargs):
    """
    Execute operation with circuit breaker.
    
    Opens circuit after 3 failures.
    """
    op_name = operation.__name__
    
    if _circuit_open.get(op_name, False):
        raise Exception("Circuit open")
    
    try:
        result = operation(*args, **kwargs)
        _failure_counts[op_name] = 0
        return result
    except Exception as e:
        _failure_counts[op_name] = _failure_counts.get(op_name, 0) + 1
        
        if _failure_counts[op_name] >= 3:
            _circuit_open[op_name] = True
        
        raise e
```

**Keywords:** circuit-breaker, resilience, fault-tolerance, SUGA

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

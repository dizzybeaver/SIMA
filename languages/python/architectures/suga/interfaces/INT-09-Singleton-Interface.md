# INT-09-Singleton-Interface.md

**Version:** 1.0.0  
**Date:** 2025-11-07  
**Purpose:** Singleton management through SUGA pattern  
**Architecture:** SUGA (Gateway Pattern)

---

## INT-09: SINGLETON INTERFACE

**Purpose:** Singleton management through SUGA pattern.

### Three-Layer Pattern

```python
# Gateway (gateway_wrappers_singleton.py)
def get_singleton(key, factory=None):
    import interface_singleton
    return interface_singleton.get(key, factory)

# Interface (interface_singleton.py)
def get(key, factory=None):
    import singleton_core
    return singleton_core.get_impl(key, factory)

# Core (singleton_core.py)
_singletons = {}

def get_impl(key, factory=None):
    if key not in _singletons and factory:
        _singletons[key] = factory()
    return _singletons.get(key)
```

**Keywords:** singleton, instance, factory, SUGA

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

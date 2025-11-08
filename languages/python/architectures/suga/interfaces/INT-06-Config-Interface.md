# INT-06-Config-Interface.md

**Version:** 1.0.0  
**Date:** 2025-11-07  
**Purpose:** Configuration management through SUGA pattern  
**Architecture:** SUGA (Gateway Pattern)

---

## INT-06: CONFIG INTERFACE

**Purpose:** Configuration management through SUGA pattern.

### Three-Layer Pattern

```python
# Gateway (gateway_wrappers_config.py)
def config_get(key, default=None):
    import interface_config
    return interface_config.get(key, default)

def config_set(key, value):
    import interface_config
    return interface_config.set(key, value)

# Interface (interface_config.py)
def get(key, default=None):
    import config_core
    return config_core.get_impl(key, default)

def set(key, value):
    import config_core
    return config_core.set_impl(key, value)

# Core (config_core.py)
_config = {}

def get_impl(key, default=None):
    return _config.get(key, default)

def set_impl(key, value):
    _config[key] = value
    return True
```

**Keywords:** configuration, settings, parameters, SUGA

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

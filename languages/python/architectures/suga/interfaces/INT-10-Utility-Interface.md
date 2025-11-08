# INT-10-Utility-Interface.md

**Version:** 1.0.0  
**Date:** 2025-11-07  
**Purpose:** Utility operations through SUGA pattern  
**Architecture:** SUGA (Gateway Pattern)

---

## INT-10: UTILITY INTERFACE

**Purpose:** Utility operations through SUGA pattern.

### Three-Layer Pattern

```python
# Gateway (gateway_wrappers_utility.py)
def validate_input(data, schema):
    import interface_utility
    return interface_utility.validate(data, schema)

def sanitize_data(data):
    import interface_utility
    return interface_utility.sanitize(data)

# Interface (interface_utility.py)
def validate(data, schema):
    import utility_core
    return utility_core.validate_impl(data, schema)

def sanitize(data):
    import utility_core
    return utility_core.sanitize_impl(data)

# Core (utility_core.py)
def validate_impl(data, schema):
    """Basic validation."""
    return isinstance(data, type(schema))

def sanitize_impl(data):
    """Sanitize sentinels and dangerous values."""
    if hasattr(data, '__name__'):
        if data.__name__ in ['_CacheMiss', '_NotFound']:
            return None
    return data
```

**Keywords:** utility, validation, sanitization, helpers, SUGA

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

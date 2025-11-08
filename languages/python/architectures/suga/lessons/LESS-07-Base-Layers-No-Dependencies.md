# LESS-07-Base-Layers-No-Dependencies.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** SUGA architecture lesson - base layer dependency rules  
**Category:** Lesson  
**Architecture:** SUGA (Gateway Pattern)

---

## Title

Base Layers Have No Dependencies in SUGA

---

## Priority

CRITICAL

---

## Summary

In SUGA architecture, logging must be the base layer with no dependencies because every layer (Gateway, Interface, Core) needs to log, but logging cannot depend on any SUGA layer without creating circular dependencies that break the pattern.

---

## Context

SUGA's three-layer architecture requires a foundation layer below it. Logging serves as this foundation because Gateway → Interface → Core all need logging, but logging cannot import from any SUGA layer.

---

## Lesson

### The SUGA Circular Dependency Problem

**Circular dependency deadlock in SUGA:**
```python
# ❌ If logging_core used interface_cache (SUGA layer)
# logging_core.py
import interface_cache  # Logging tries to use SUGA

def log_info(msg):
    if interface_cache.get(f"log:{msg}"):  # Logging uses Interface
        return
    print(msg)

# interface_cache.py (SUGA Interface layer)
import logging_core  # Interface uses logging

def get(key):
    logging_core.log_info(f"Cache get: {key}")
    import cache_core
    return cache_core.get_impl(key)

# Result: Circular dependency!
# logging_core → interface_cache → logging_core
```

### The SUGA Layer Hierarchy

**SUGA with Base Layer:**
```
Layer 3: Gateway (gateway_wrappers.py)
        ↓ (can use Layer 2 + Layer 1 + LOGGING)
        
Layer 2: Interface (interface_*.py)
        ↓ (can use Layer 1 + LOGGING only)
        
Layer 1: Core (*_core.py)
        ↓ (can use LOGGING only)
        
Layer 0: BASE LAYER (logging_core.py)
        ↓ (NO SUGA dependencies, stdlib only)
```

### SUGA Dependency Rules

**Layer 0 (Base - Logging):**
- ✅ Can import: Standard library only
- ❌ Cannot import: Any SUGA layer (Gateway, Interface, Core)
- ❌ Cannot use: Any cross-module functionality
- ❌ Cannot use: Cache, config, metrics, anything

**Layer 1 (SUGA Core):**
- ✅ Can import: Stdlib + Layer 0 (logging)
- ✅ Can use: Logging functions
- ❌ Cannot import: Interface or Gateway layers
- ❌ Cannot use: Other Core modules directly

**Layer 2 (SUGA Interface):**
- ✅ Can import: Stdlib + Layer 0 + Layer 1 (via import)
- ✅ Can use: Logging, import specific Core module
- ❌ Cannot import: Gateway layer
- ❌ Cannot import: Other Interface modules directly

**Layer 3 (SUGA Gateway):**
- ✅ Can import: Stdlib + Layer 0 + Layer 2 (via lazy import)
- ✅ Can use: Logging, lazy import specific Interface
- ❌ Cannot import: Other Gateway wrapper modules directly
- Must use: Lazy imports only for interfaces

### Why This Matters in SUGA

**1. Prevents Gateway Circular Dependencies**
```python
# âœ… Correct: Gateway → Interface → Core → Logging (linear)
# gateway_wrappers.py
def cache_get(key):
    import logging_core
    logging_core.log_info("Gateway cache_get")
    import interface_cache
    return interface_cache.get(key)

# interface_cache.py
def get(key):
    import logging_core
    logging_core.log_info("Interface cache get")
    import cache_core
    return cache_core.get_impl(key)

# cache_core.py
def get_impl(key):
    import logging_core
    logging_core.log_info("Core cache get_impl")
    return _CACHE.get(key)

# logging_core.py
def log_info(msg):
    print(f"[INFO] {msg}")  # No SUGA dependencies
```

**2. Makes SUGA Dependencies Clear**
```python
# Question: Can I use cache in logging?
# Answer: No, logging is Layer 0, cache is Layer 1+
# Reason: Would create circular dependency

# Question: Can Interface import Gateway?
# Answer: No, Gateway is Layer 3, Interface is Layer 2
# Reason: Higher layers depend on lower, not reverse

# Question: Can Core import Interface?
# Answer: No, Interface is Layer 2, Core is Layer 1
# Reason: Would break layer separation
```

**3. Enables SUGA Testing**
```python
# Test logging independently (no SUGA dependencies)
def test_logging():
    import logging_core
    logging_core.log_info("test")
    assert output == "[INFO] test"

# Test Core with only logging
def test_cache_core():
    import logging_core
    import cache_core
    # No need for Interface or Gateway
    result = cache_core.get_impl("key")
    assert result is None

# Test Interface with Core + logging
def test_interface_cache():
    import logging_core
    import interface_cache
    # No need for Gateway
    result = interface_cache.get("key")
    assert result is None
```

### Identifying the SUGA Base Layer

**Questions:**
1. What do all SUGA layers need? → Logging
2. What needs nothing from SUGA? → Logging  
3. What creates circular deps if it uses SUGA? → Logging

**Result:** Logging must be below SUGA (Layer 0)

### SUGA Implementation

**Base layer module (logging_core.py):**
```python
# logging_core.py
# NO SUGA IMPORTS - Below SUGA hierarchy

import sys
from typing import Any

def log_info(message: str) -> None:
    """Log info message - NO SUGA dependencies."""
    print(f"[INFO] {message}", file=sys.stdout)

def log_error(message: str) -> None:
    """Log error message - NO SUGA dependencies."""
    print(f"[ERROR] {message}", file=sys.stderr)

# Never imports: gateway*, interface_*, *_core (except this file)
```

**SUGA Core module (cache_core.py):**
```python
# cache_core.py - Layer 1
# CAN USE: Logging (Layer 0)
# CANNOT USE: Interface or Gateway

from logging_core import log_info, log_error

_CACHE = {}

def get_impl(key: str) -> Any:
    log_info(f"Core cache get: {key}")  # OK: Uses base layer
    return _CACHE.get(key)

# Never imports: interface_cache, gateway_wrappers
```

**SUGA Interface module (interface_cache.py):**
```python
# interface_cache.py - Layer 2
# CAN USE: Logging (Layer 0), Core (Layer 1)
# CANNOT USE: Gateway

from logging_core import log_info

def get(key: str):
    log_info(f"Interface cache get: {key}")
    import cache_core  # OK: Lower layer
    return cache_core.get_impl(key)

# Never imports: gateway_wrappers
```

**SUGA Gateway module (gateway_wrappers.py):**
```python
# gateway_wrappers.py - Layer 3
# CAN USE: Logging (Layer 0), Interface (Layer 2) via lazy import

from logging_core import log_info

def cache_get(key: str):
    log_info(f"Gateway cache_get: {key}")
    import interface_cache  # Lazy import OK
    return interface_cache.get(key)
```

---

## SUGA-Specific Dependency Matrix

```
Layer       | Can Import                    | Cannot Import
------------|-------------------------------|------------------
Logging     | stdlib only                   | ALL SUGA layers
Core        | stdlib, logging               | Interface, Gateway
Interface   | stdlib, logging, Core         | Other Interface, Gateway
Gateway     | stdlib, logging, Interface    | Other Gateway (must use lazy)
```

---

## Related

**SUGA Architecture:**
- ARCH-01: Gateway trinity - requires base layer
- ARCH-02: Layer separation - dependency rules
- GATE-02: Lazy imports - prevents circular deps

**SUGA Decisions:**
- DEC-02: Three-layer pattern - requires Layer 0 foundation
- DEC-03: Gateway mandatory - entry point discipline

**SUGA Anti-Patterns:**
- AP-01: Direct core import - violates layers
- AP-03: Circular module references - breaks base layer rule

**Generic Patterns:**
- LESS-07: Generic base layer rule (parent)
- BUG-03: Circular import examples

**Keywords:** base layer, no dependencies, logging foundation, SUGA hierarchy, dependency rules, circular prevention

---

## Version History

- **1.0.0** (2025-11-06): Created SUGA-specific lesson from LESS-07
  - Adapted for SUGA three-layer architecture
  - Added layer-specific dependency rules
  - Included SUGA import patterns

---

**END OF FILE**

# File: LESS-07.md

**REF-ID:** LESS-07  
**Category:** Lessons Learned  
**Topic:** Core Architecture  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Status:** Production

---

## Title

Base Layers Have No Dependencies

---

## Priority

CRITICAL

---

## Summary

Logging must be the base layer with no dependencies because every other module needs to log, but logging can't depend on anything else without creating circular dependencies.

---

## Context

When designing the dependency hierarchy, we realized that logging must be the base layer because every other module needs to log, but logging can't depend on anything else.

---

## Lesson

### The Problem

**Circular dependency deadlock:**
```python
# ❌ If logging depended on cache
from cache import cache_get

def log_info(msg):
    if cache_get(f"log:{msg}"):  # Logging uses cache
        return
    print(msg)

# cache.py
from logging import log_info

def cache_get(key):
    log_info(f"Cache get: {key}")  # Cache uses logging
    return _cache.get(key)

# Result: Circular dependency!
```

### The Solution

**Layered Architecture with Clear Base:**
```
Layer 2+: Application (HTTP, Business Logic)
        ↓ (can use Layer 1 + LOGGING)
        
Layer 1: Infrastructure (Cache, Security, Config)
        ↓ (can use LOGGING only)
        
Layer 0: BASE LAYER (Logging)
        ↓ (NO dependencies, stdlib only)
```

### Dependency Rules

**Layer 0 (Base - Logging):**
- ✅ Can import: Standard library only
- ❌ Cannot import: Any application module
- ❌ Cannot use: Any cross-module functionality

**Layer 1 (Infrastructure):**
- ✅ Can import: Stdlib + Layer 0
- ✅ Can use: Logging functions
- ❌ Cannot import: Layer 1 or Layer 2

**Layer 2+ (Application):**
- ✅ Can import: Stdlib + Layer 0 + Layer 1
- ✅ Can use: Logging, cache, security, etc.
- ❌ Cannot import: Same-layer or higher

### Why This Matters

**1. Prevents Deadlocks**
- Circular dependency = Deadlock at import time
- Layered dependencies = Always resolvable

**2. Makes Dependencies Clear**
- "Can I use cache in logging?" → "No, logging is Layer 0, cache is Layer 1"

**3. Enables Testing**
```python
# Test logging independently (no dependencies)
def test_logging():
    log_info("test")
    assert output == "[INFO] test"
```

### Identifying the Base Layer

**Questions:**
1. What does everything else need? → Logging
2. What needs nothing else? → Logging
3. What creates circular deps if it depends on others? → Logging

**Result:** Logging must be base layer

### Implementation

**Base layer module (logging_core.py):**
```python
# logging_core.py
# NO APPLICATION IMPORTS

import sys
from typing import Any

def log_info(message: str) -> None:
    """Log info message - NO dependencies."""
    print(f"[INFO] {message}", file=sys.stdout)

def log_error(message: str) -> None:
    """Log error message - NO dependencies."""
    print(f"[ERROR] {message}", file=sys.stderr)
```

**Infrastructure layer module (cache_core.py):**
```python
# cache_core.py
# CAN USE LOGGING

from logging_core import log_info, log_error

_CACHE = {}

def cache_get(key: str) -> Any:
    log_info(f"Cache get: {key}")  # OK: Uses base layer
    return _CACHE.get(key)
```

---

## Related

**Cross-References:**
- DEP-01: Dependency layer hierarchy
- LESS-01: Gateway pattern prevents problems
- BUG-02: Circular import examples

**Keywords:** base layer, no dependencies, logging layer, dependency hierarchy, circular imports

---

## Version History

- **4.0.0** (2025-10-30): Genericized for SIMAv4
- **3.0.0** (2025-10-23): Initial SIMAv3 format

---

**END OF FILE**

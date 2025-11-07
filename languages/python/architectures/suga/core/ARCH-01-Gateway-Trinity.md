# ARCH-01-Gateway-Trinity.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** SUGA three-layer gateway pattern  
**Category:** Python Architecture - SUGA

---

## PATTERN

SUGA (Single Universal Gateway Architecture) enforces three distinct layers with lazy imports between them:

```
Gateway Layer (gateway_wrappers.py)
    ↓ lazy import
Interface Layer (interface_*.py)
    ↓ lazy import
Core Layer (*_core.py)
```

---

## LAYER RESPONSIBILITIES

### Gateway Layer
**Files:** `gateway_wrappers.py`, `gateway_wrappers_*.py`

**Purpose:** Public API entry point

**Responsibilities:**
- Expose functions to external code
- Lazy import to interface layer
- No implementation logic
- Simple routing only

**Example:**
```python
def cache_get(key, default=None):
    """Get value from cache."""
    import interface_cache
    return interface_cache.get(key, default)
```

### Interface Layer
**Files:** `interface_*.py`

**Purpose:** Route to appropriate core implementation

**Responsibilities:**
- Route to core functions
- Lazy import to core layer
- No implementation logic
- Interface abstraction

**Example:**
```python
def get(key, default=None):
    """Route to cache core."""
    import cache_core
    return cache_core.get_impl(key, default)
```

### Core Layer
**Files:** `*_core.py`

**Purpose:** Implementation

**Responsibilities:**
- All implementation logic
- Business rules
- Data manipulation
- Error handling
- Can import other cores via gateway

**Example:**
```python
def get_impl(key, default=None):
    """Actual cache implementation."""
    if key in _cache:
        return _cache[key]
    return default
```

---

## IMPORT RULES

### Gateway → Interface
```python
# CORRECT: Lazy import in function
def cache_get(key):
    import interface_cache  # Function-level
    return interface_cache.get(key)

# WRONG: Module-level import
import interface_cache  # ❌ Not lazy
def cache_get(key):
    return interface_cache.get(key)
```

### Interface → Core
```python
# CORRECT: Lazy import in function
def get(key):
    import cache_core  # Function-level
    return cache_core.get_impl(key)

# WRONG: Module-level import
import cache_core  # ❌ Not lazy
def get(key):
    return cache_core.get_impl(key)
```

### Core → Core
```python
# CORRECT: Via gateway
def log_cache_hit(key):
    import gateway
    gateway.log_info(f"Cache hit: {key}")

# WRONG: Direct core import
import logging_core  # ❌ Violates SUGA
logging_core.info(f"Cache hit: {key}")
```

---

## BENEFITS

**1. Prevents Circular Imports**
Lazy imports break circular dependencies at runtime.

**2. Faster Cold Start**
Only load what's needed when it's needed.

**3. Clear Architecture**
Each layer has single responsibility.

**4. Testability**
Easy to mock interfaces and cores.

**5. Maintainability**
Changes isolated to appropriate layer.

---

## VIOLATIONS

### Direct Core Import (AP-01)
```python
# ❌ WRONG
from cache_core import get_impl
```

### Module-Level Import (AP-02)
```python
# ❌ WRONG
import interface_cache  # At top of file
```

### Skipping Gateway (AP-XX)
```python
# ❌ WRONG
import logging_core  # Core-to-core direct
```

---

## VERIFICATION

**Checklist:**
- [ ] Gateway functions use lazy imports
- [ ] Interface functions use lazy imports
- [ ] Core-to-core goes via gateway
- [ ] No module-level interface imports
- [ ] No direct core imports
- [ ] All three layers present

---

**Related:**
- ARCH-02: Layer separation rules
- ARCH-03: Interface pattern
- GATE-01: Gateway entry point
- GATE-02: Lazy import pattern
- AP-01: Direct core import
- AP-02: Module-level import

**Version History:**
- v1.0.0 (2025-11-06): Initial SUGA trinity pattern

---

**END OF FILE**

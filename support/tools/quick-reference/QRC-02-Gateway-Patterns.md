# File: QRC-02-Gateway-Patterns.md

**REF-ID:** QRC-02  
**Version:** 1.0.0  
**Category:** Support Tools  
**Type:** Quick Reference Card  
**Purpose:** Essential gateway patterns for SUGA architecture

---

## 📋 GATEWAY ESSENTIALS

### What is Gateway?
Single entry point for all interface functions. Provides lazy loading and prevents circular imports.

### Core Principles
1. **Single Entry** - All imports go through gateway
2. **Lazy Loading** - Import inside functions, not at module level
3. **No Circular Deps** - Gateway doesn't import interfaces at module level
4. **Thin Layer** - Gateway only routes, doesn't implement logic

**Reference:** GATE-01 (Gateway Pattern)

---

## 🎯 FIVE GATEWAY PATTERNS

### GATE-01: Gateway Pattern
**Purpose:** Central entry point for interface access

**Structure:**
```python
# gateway.py
LAZY_IMPORTS = {
    'function_name': ('module_operations', 'function'),
}

def __getattr__(name):
    if name in LAZY_IMPORTS:
        module_name, func_name = LAZY_IMPORTS[name]
        module = importlib.import_module(f'.{module_name}', __package__)
        return getattr(module, func_name)
    raise AttributeError(f"module has no attribute '{name}'")
```

**Usage:**
```python
from gateway import cache_get  # Triggers lazy load
```

---

### GATE-02: Three-File Structure
**Purpose:** Separate gateway, interface, core layers

**Structure:**
```
gateway.py           → Routing layer
[module]_operations  → Interface layer (public API)
[module]_core        → Implementation layer (private)
```

**Example:**
```
gateway.py           → LAZY_IMPORTS['cache_get']
cache_operations.py  → def get(...)  # Public
cache_core.py        → def _get_cached_value(...)  # Private
```

**Key:**
- Gateway = Entry point
- Operations = Public interface
- Core = Private implementation

---

### GATE-03: Lazy Loading Pattern
**Purpose:** Defer imports until function call

**Wrong (Module Level):**
```python
# ❌ WRONG - Imports at module load
from cache_operations import get
from logging_operations import log

def cache_get(key):
    return get(key)
```

**Correct (Lazy):**
```python
# ✅ CORRECT - Import inside function
def cache_get(key):
    from .cache_operations import get
    return get(key)
```

**Why:**
- Reduces cold start time
- Prevents circular imports
- Only loads what's needed

---

### GATE-04: Cross-Interface Rule
**Purpose:** Prevent interface-to-interface dependencies

**Wrong:**
```python
# In logging_operations.py
from .cache_operations import get  # ❌ Cross-interface import

def log_with_cache(msg):
    cached = get("log_config")  # ❌ Direct interface call
    # ...
```

**Correct:**
```python
# In logging_operations.py
from gateway import cache_get  # ✅ Via gateway

def log_with_cache(msg):
    cached = cache_get("log_config")  # ✅ Gateway call
    # ...
```

**Rule:** Interfaces never import other interfaces directly. Always use gateway.

---

### GATE-05: Gateway Wrappers
**Purpose:** Provide convenience functions at gateway level

**Simple Wrapper:**
```python
# In gateway.py
def cache_get_with_default(key: str, default=None):
    """Convenience: cache_get with automatic default."""
    from .cache_operations import get
    result = get(key)
    return result if result is not None else default
```

**Complex Wrapper:**
```python
# In gateway.py
def secure_cache_get(key: str):
    """Convenience: decrypt cached value automatically."""
    from .cache_operations import get
    from .security_operations import decrypt
    
    encrypted = get(key)
    if encrypted:
        return decrypt(encrypted)
    return None
```

**When to Use:** Frequently used combinations, convenience features

---

## 🔧 GATEWAY IMPLEMENTATION

### Basic Gateway Structure
```python
# File: gateway.py

import importlib
from typing import Any

# Lazy import dictionary
LAZY_IMPORTS = {
    # Cache Interface
    'cache_get': ('cache_operations', 'get'),
    'cache_set': ('cache_operations', 'set'),
    'cache_clear': ('cache_operations', 'clear'),
    
    # Logging Interface
    'logging_info': ('logging_operations', 'info'),
    'logging_error': ('logging_operations', 'error'),
    'logging_debug': ('logging_operations', 'debug'),
    
    # Security Interface
    'security_encrypt': ('security_operations', 'encrypt'),
    'security_decrypt': ('security_operations', 'decrypt'),
    
    # Add more as needed...
}

def __getattr__(name: str) -> Any:
    """Lazy load interface functions."""
    if name in LAZY_IMPORTS:
        module_name, func_name = LAZY_IMPORTS[name]
        module = importlib.import_module(f'.{module_name}', __package__)
        return getattr(module, func_name)
    raise AttributeError(f"module 'gateway' has no attribute '{name}'")

# Optional: Explicit exports for IDE support
__all__ = list(LAZY_IMPORTS.keys())
```

---

### Adding Gateway Entry

**Step 1: Implement Interface Function**
```python
# cache_operations.py
def invalidate(key: str) -> bool:
    """Invalidate cache entry."""
    from .cache_core import _invalidate_key
    return _invalidate_key(key)
```

**Step 2: Add to Gateway**
```python
# gateway.py
LAZY_IMPORTS = {
    # ... existing entries ...
    'cache_invalidate': ('cache_operations', 'invalidate'),  # NEW
}
```

**Step 3: Use from Application**
```python
# application code
from gateway import cache_invalidate

cache_invalidate("stale_key")
```

---

## ⚠️ COMMON GATEWAY MISTAKES

### Mistake 1: Module-Level Imports
```python
# ❌ WRONG
from cache_operations import get

def cache_get(key):
    return get(key)
```
**Problem:** Loads at module import, defeats lazy loading

**Fix:**
```python
# ✅ CORRECT
def cache_get(key):
    from .cache_operations import get
    return get(key)
```

---

### Mistake 2: Gateway Implements Logic
```python
# ❌ WRONG
def cache_get(key):
    # Don't implement logic in gateway
    if not key:
        raise ValueError("Key required")
    
    cache = {}  # Don't access storage
    return cache.get(key)
```

**Fix:**
```python
# ✅ CORRECT
def cache_get(key):
    from .cache_operations import get
    return get(key)  # Delegate to interface
```

---

### Mistake 3: Circular Import at Module Level
```python
# ❌ WRONG - gateway.py
from logging_operations import info  # Module level

def log_info(msg):
    return info(msg)

# logging_operations.py
from gateway import cache_get  # Creates circular dependency
```

**Fix:**
```python
# ✅ CORRECT - gateway.py
def log_info(msg):
    from .logging_operations import info  # Inside function
    return info(msg)
```

---

### Mistake 4: Wrong LAZY_IMPORTS Entry
```python
# ❌ WRONG
LAZY_IMPORTS = {
    'cache_get': ('cache', 'get'),  # Wrong module name
}
```

**Fix:**
```python
# ✅ CORRECT
LAZY_IMPORTS = {
    'cache_get': ('cache_operations', 'get'),  # Correct
}
```

---

## 🎓 GATEWAY USAGE PATTERNS

### Pattern 1: Single Interface Usage
```python
from gateway import cache_get, cache_set

# Use cache
data = cache_get("key")
if not data:
    data = expensive_operation()
    cache_set("key", data)
```

### Pattern 2: Multiple Interface Usage
```python
from gateway import (
    cache_get,
    logging_error,
    api_get
)

# Try cache, fall back to API, log errors
try:
    data = cache_get("api_data")
    if not data:
        data = api_get("https://api.example.com")
        cache_set("api_data", data)
except Exception as e:
    logging_error(f"Failed: {e}")
    data = None
```

### Pattern 3: Gateway Wrapper Usage
```python
from gateway import secure_cache_get

# Use convenience wrapper
decrypted_data = secure_cache_get("secret_key")
# Automatically handles cache_get + decrypt
```

---

## 📊 GATEWAY VERIFICATION

### Pre-Commit Checklist

**Gateway Structure:**
- [ ] LAZY_IMPORTS dictionary present
- [ ] All entries in correct format: ('module', 'function')
- [ ] No module-level imports from interfaces
- [ ] __getattr__ implements lazy loading

**Function Routing:**
- [ ] All interface functions have gateway entries
- [ ] Gateway names follow convention: [interface]_[operation]
- [ ] No duplicate entries
- [ ] No broken module/function references

**Import Pattern:**
- [ ] All application imports via gateway
- [ ] No direct interface imports
- [ ] No circular dependencies
- [ ] Lazy loading working correctly

---

## 🔍 DEBUGGING GATEWAY ISSUES

### Issue: ImportError
```
ImportError: cannot import name 'cache_get'
```

**Check:**
1. Is 'cache_get' in LAZY_IMPORTS?
2. Is module name correct? ('cache_operations')
3. Does function exist in module?
4. Circular import at module level?

---

### Issue: AttributeError
```
AttributeError: module 'gateway' has no attribute 'cache_get'
```

**Check:**
1. Is entry in LAZY_IMPORTS?
2. Spelling correct?
3. __getattr__ implemented?

---

### Issue: Circular Import
```
ImportError: cannot import name 'X' from partially initialized module
```

**Check:**
1. Module-level imports in gateway?
2. Interface imports gateway at module level?
3. Move imports inside functions

---

## 🎯 QUICK REFERENCE

### Gateway Entry Format
```python
'[interface]_[operation]': ('[module]_operations', '[function]')
```

### Common Gateway Functions
```python
# Cache
cache_get, cache_set, cache_clear

# Logging  
logging_info, logging_error, logging_debug

# Security
security_encrypt, security_decrypt, security_hash

# Config
config_get, config_set, config_reload

# Metrics
metrics_record, metrics_increment
```

### Lazy Loading Template
```python
def gateway_function(params):
    from .[module]_operations import function
    return function(params)
```

---

## 🔗 RELATED RESOURCES

**Gateway Documentation:**
- GATE-01: Gateway Pattern
- GATE-02: Three-File Structure
- GATE-03: Lazy Loading
- GATE-04: Cross-Interface Rule
- GATE-05: Gateway Wrappers

**Related Patterns:**
- ARCH-01: SUGA Pattern (gateway role)
- RULE-01: Always Import Via Gateway
- BUG-01: Circular Import Sentinel

**Workflows:**
- WF-04: Add Gateway Function
- WF-01: Add Feature (includes gateway step)

**Project Examples:**
- NMP01-LEE-14: Gateway Execute Operation

---

**END OF QRC-02**

**Related cards:** QRC-01 (Interfaces), QRC-03 (Common Patterns)

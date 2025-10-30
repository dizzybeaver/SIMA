# NM02-Dependencies-ImportRules_RULE-02.md - RULE-02

# RULE-02: Intra-Interface Imports Are Direct

**Category:** NM02 - Dependencies
**Topic:** Import Rules
**Priority:** 🔴 Critical
**Status:** Active
**Created:** 2024-04-15
**Last Updated:** 2025-10-24

---

## Summary

Imports within the same interface should be direct, not through gateway. Gateway is only for cross-interface communication - using it for same-interface imports adds unnecessary overhead.

---

## Context

While RULE-01 requires gateway for cross-interface imports, RULE-02 specifies that same-interface imports should be direct. This reduces overhead and makes code more maintainable.

**Why This Rule Exists:**
- Reduces unnecessary gateway routing overhead
- Makes code more maintainable  
- Clearly indicates module boundaries
- Simplifies internal refactoring

---

## Content

### The Rule

**✅ CORRECT Pattern (Same Interface):**
```python
# In cache_core.py
from cache_manager import CacheManager
from cache_operations import validate_key
from cache_types import CacheEntry

def perform_operation(key):
    validate_key(key)          # Same interface - direct import
    manager = CacheManager()   # Same interface - direct import
    entry = CacheEntry(key)    # Same interface - direct import
    return entry
```

**❌ WRONG Pattern (Same Interface via Gateway):**
```python
# In cache_core.py
from gateway import cache_manager_get  # WRONG - same interface!
from gateway import validate_key       # WRONG - same interface!

def perform_operation(key):
    # Unnecessary gateway routing for same interface
    validate_key(key)
    manager = cache_manager_get()
```

### What Counts as "Same Interface"

**Same Interface:**
```
CACHE Interface files:
├─ interface_cache.py (router)
├─ cache_core.py (implementation)
├─ cache_manager.py (management)
├─ cache_operations.py (operations)
├─ cache_types.py (type definitions)
└─ cache_validation.py (validation)

All these can import each other directly
```

**Different Interfaces:**
```
CACHE → LOGGING: Different interfaces, use gateway
CACHE → METRICS: Different interfaces, use gateway
CACHE → SECURITY: Different interfaces, use gateway
```

### Why Direct Imports for Same Interface?

**1. Performance:**
```python
# Direct import (fast)
from cache_operations import validate_key
validate_key(key)  # Direct function call

# Via gateway (slower)
from gateway import validate_key
validate_key(key)  # Gateway → router → function call
```

**2. Code Clarity:**
```python
# Direct import makes relationships clear
from cache_manager import CacheManager  # Obviously same interface

# Gateway obscures relationships
from gateway import cache_manager_get  # Which interface?
```

**3. Easier Refactoring:**
```python
# Refactoring within interface is simple
# Just move function between files
# Update direct imports

# Gateway requires updating:
# - Gateway function
# - Interface router
# - Core implementation
```

### Real-World Examples

**CACHE Interface (Correct):**
```python
# In cache_core.py
from cache_types import CacheEntry
from cache_validation import validate_ttl

_CACHE_STORE = {}

def cache_set(key, value, ttl=300):
    # Same interface - direct imports
    validate_ttl(ttl)
    entry = CacheEntry(key, value, ttl)
    _CACHE_STORE[key] = entry
```

**HTTP_CLIENT Interface (Correct):**
```python
# In http_client_core.py
from http_client_state import get_session
from http_client_validation import validate_url
from http_client_utilities import build_headers

def http_get(url):
    # Same interface - direct imports
    validate_url(url)
    session = get_session()
    headers = build_headers()
    response = session.get(url, headers=headers)
    return response
```

**LOGGING Interface (Correct):**
```python
# In logging_core.py
from logging_manager import LogManager
from logging_types import LogLevel
from logging_operations import format_message

_manager = LogManager()

def log_info(message):
    # Same interface - direct imports
    level = LogLevel.INFO
    formatted = format_message(message)
    _manager.write(level, formatted)
```

### Identifying Same vs Different Interface

**Quick Check:**
```
Question: Can I import directly?

Step 1: Look at file prefix
  cache_core.py wants to import cache_manager.py
  Same prefix (cache_)? -> YES, same interface

Step 2: If different prefix, use gateway
  cache_core.py wants to import logging_core.py
  Different prefix (cache_ vs logging_)? -> Use gateway

Step 3: If unsure, check interface router
  Both files routed by interface_cache.py? -> Same interface
  Routed by different interface_X.py? -> Different interfaces
```

### Common Patterns

**Interface File Organization:**
```
interface_<name>.py        - Router (gateway layer)
<name>_core.py            - Main implementation
<name>_manager.py         - Management logic
<name>_operations.py      - Operations
<name>_types.py           - Type definitions
<name>_validation.py      - Validation
<name>_utilities.py       - Helper functions
```

**Import Patterns:**
- Router imports core: Direct ✅
- Core imports operations: Direct ✅
- Core imports types: Direct ✅
- Core imports validation: Direct ✅
- Operations imports utilities: Direct ✅

**Cross-Interface (Use Gateway):**
- Core imports different_core: Gateway ✅
- Operations imports different_operations: Gateway ✅
- Manager imports different_manager: Gateway ✅

### Performance Impact

**Direct Import:**
- Call overhead: < 0.1ms
- Memory: Minimal
- Stack depth: 1 level

**Via Gateway (Unnecessary):**
- Call overhead: ~0.5ms
- Memory: Extra stack frames
- Stack depth: 3+ levels

**Impact on Hot Paths:**
```
Cache operation called 1000 times:
- Direct imports: 100ms overhead
- Via gateway: 500ms overhead
- Waste: 400ms per 1000 calls
```

---

## Related Topics

- **RULE-01**: Cross-interface imports via gateway
- **NM01-ARCH-03**: Interface internal structure
- **NM01-ARCH-04**: Module organization patterns
- **NM05-AP-06**: Unnecessary gateway usage (anti-pattern)

---

## Keywords

intra-interface imports, same interface, direct imports, import rules, performance optimization, code organization

---

## Version History

- **2025-10-24**: Atomized from NM02-RULES-Import, migrated to v3.1.0 format
- **2024-04-15**: Original content in NM02-RULES-Import Rules and Validation

---

**File:** `NM02-Dependencies-ImportRules_RULE-02.md`
**Location:** `/nmap/NM02/`
**End of Document**

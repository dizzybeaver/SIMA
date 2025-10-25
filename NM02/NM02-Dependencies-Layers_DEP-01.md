# NM02-Dependencies-Layers_DEP-01.md - DEP-01

# DEP-01: Layer 0 - Base Infrastructure (LOGGING)

**Category:** NM02 - Dependencies
**Topic:** Dependency Layers
**Priority:** 🔴 Critical
**Status:** Active
**Created:** 2024-04-15
**Last Updated:** 2025-10-24

---

## Summary

LOGGING is Layer 0 of the 5-layer dependency hierarchy, with zero dependencies. It's the foundation that all other interfaces depend on, and must remain dependency-free to prevent circular imports.

---

## Context

In the SUGA architecture, interfaces are organized into dependency layers. LOGGING sits at Layer 0 (base infrastructure) because every other interface needs logging capability. If LOGGING depended on anything, circular imports would be inevitable.

**Why Layer 0 Exists:**
- Foundation layer that initializes first
- Every interface logs operations and errors
- Must work even if everything else fails
- Provides visibility into all system operations

---

## Content

### Layer 0 Definition

```
LOGGING
├─ Dependencies: None
├─ Purpose: Base logging infrastructure
├─ Used by: All other interfaces
└─ Why base layer: Must not depend on anything to avoid circular imports
```

**Dependency Rule:** LOGGING cannot import from any other interface.

**Rationale:** If LOGGING depended on CACHE, and CACHE depended on LOGGING, we'd have a circular import. By keeping LOGGING at Layer 0 with zero dependencies, we eliminate this possibility.

### Why LOGGING Has No Dependencies

**1. Prevents Circular Imports:**
```
If LOGGING depended on CACHE:
LOGGING → CACHE → LOGGING ❌ CIRCULAR!

With Layer 0:
CACHE → LOGGING ✅ NO CIRCLES
HTTP_CLIENT → LOGGING ✅ NO CIRCLES
SECURITY → LOGGING ✅ NO CIRCLES
```

**2. Foundation for All Interfaces:**
Every interface needs logging:
- CACHE logs cache hits/misses
- HTTP_CLIENT logs requests/responses
- SECURITY logs validation failures
- METRICS logs metric recording
- CONFIG logs configuration changes

**3. Reliable Error Reporting:**
LOGGING must work even when:
- CACHE fails
- HTTP_CLIENT times out
- CONFIG is misconfigured
- System is in degraded state

### Real-World Impact

**Cold Start Order:**
```
Lambda cold start sequence:
1. LOGGING initializes (< 5ms) ← FIRST
2. Other interfaces initialize (can log)
3. Application ready
```

**Error Handling:**
```python
# In any interface
def some_operation():
    try:
        result = perform_operation()
    except Exception as e:
        # LOGGING must work even if operation fails
        log_error(f"Operation failed: {e}")
        raise
```

**Debugging:**
```
LOGGING provides visibility:
├─ Cache operations (hit/miss rates)
├─ HTTP requests (timing, errors)
├─ Security validations (failures)
├─ Configuration loads (sources)
└─ All interface activities
```

### Implementation Pattern

**Correct Pattern:**
```python
# In logging_core.py
# NO IMPORTS from other interfaces
import sys
import json
from datetime import datetime

def log_info(message):
    # Pure logging - no dependencies
    print(json.dumps({
        "timestamp": datetime.utcnow().isoformat(),
        "level": "INFO",
        "message": message
    }))
```

**Wrong Pattern:**
```python
# ❌ WRONG - LOGGING depending on other interfaces
# In logging_core.py
from cache_core import cache_set  # VIOLATION!

def log_info(message):
    cache_set(f"log_{timestamp}", message)  # NO!
    print(message)
```

### Usage by Other Interfaces

**All interfaces use LOGGING:**
```python
# In cache_core.py
import gateway

def cache_get(key):
    gateway.log_info(f"Cache lookup: {key}")  # Layer 0
    return _CACHE_STORE.get(key)

# In http_client_core.py
import gateway

def http_get(url):
    gateway.log_info(f"HTTP GET: {url}")  # Layer 0
    response = _perform_request(url)
    return response

# In security_core.py
import gateway

def validate_input(value):
    if not is_valid(value):
        gateway.log_warning(f"Invalid input: {value}")  # Layer 0
        return False
    return True
```

### Performance Characteristics

**Initialization:**
- Time: < 5ms
- Memory: ~100KB
- First interface initialized

**Operation:**
- Log call: < 0.5ms
- No blocking operations
- Minimal overhead

**Cold Start Impact:**
- LOGGING initializes first
- Enables logging during initialization
- Total cold start: 50-80ms (LOGGING: ~5ms)

---

## Related Topics

- **NM01-INT-02**: LOGGING interface definition and signatures
- **NM04-DEC-08**: Why base layer has no dependencies (design decision)
- **NM06-LESS-04**: Lessons learned about base layer requirements
- **DEP-02**: Layer 1 dependencies (depends on LOGGING)
- **ARCH-02**: Initialization order (LOGGING first)

---

## Keywords

dependency layer 0, LOGGING, base infrastructure, zero dependencies, foundation layer, circular import prevention, initialization order

---

## Version History

- **2025-10-24**: Atomized from NM02-CORE, migrated to v3.1.0 format
- **2024-04-15**: Original content in NM02-CORE-Dependency Layers

---

**File:** `NM02-Dependencies-Layers_DEP-01.md`
**Location:** `/nmap/NM02/`
**End of Document**

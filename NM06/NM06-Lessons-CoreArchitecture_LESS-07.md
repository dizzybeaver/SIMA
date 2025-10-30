# NM06-Lessons-CoreArchitecture_LESS-07.md - LESS-07

# LESS-07: Base Layers Have No Dependencies

**Category:** Lessons  
**Topic:** CoreArchitecture  
**Priority:** CRITICAL  
**Status:** Active  
**Created:** 2025-10-23  
**Last Updated:** 2025-10-23

---

## Summary

Logging must be the base layer with no dependencies because every other interface needs to log, but logging can't depend on anything else without creating circular dependencies.

---

## Context

When designing the dependency hierarchy, we realized that logging must be the base layer because every other interface needs to log, but logging can't depend on anything else.

---

## Content

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
Layer 2+: Application (HTTP, HA, Metrics)
        ↓ (can use Layer 1 + LOGGING)
        
Layer 1: Infrastructure (Cache, Security, SSM)
        ↓ (can use LOGGING only)
        
Layer 0: BASE LAYER (Logging)
        ↓ (NO dependencies, stdlib only)
```

### Dependency Rules

**Layer 0 (Base - Logging):**
- ✅ Can import: Standard library only
- ❌ Cannot import: Any interface
- ❌ Cannot use: gateway.* (except itself)

**Layer 1 (Infrastructure):**
- ✅ Can import: Stdlib + Layer 0
- ✅ Can use: gateway.log_*()
- ❌ Cannot import: Layer 1 or Layer 2

**Layer 2+ (Application):**
- ✅ Can import: Stdlib + Layer 0 + Layer 1
- ✅ Can use: gateway.log_*(), gateway.cache_*(), etc.
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

---

## Related Topics

- **DEP-01**: Dependency layers
- **DEC-02**: Layered architecture
- **BUG-02**: Circular imports (what this prevents)

---

## Keywords

base layer, no dependencies, logging layer, dependency hierarchy, circular imports

---

## Version History

- **2025-10-23**: Created - Migrated to SIMA v3 individual file format
- **2025-10-15**: Original documentation in NM06-LESSONS-Core Architecture Lessons.md

---

**File:** `NM06-Lessons-CoreArchitecture_LESS-07.md`  
**Directory:** NM06/  
**End of Document**

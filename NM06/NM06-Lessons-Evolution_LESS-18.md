# NM06-Lessons-Evolution_LESS-18.md - LESS-18

# LESS-18: SINGLETON Pattern Lifecycle

**Category:** Lessons  
**Topic:** Evolution  
**Priority:** MEDIUM  
**Status:** Active  
**Created:** 2025-10-23  
**Last Updated:** 2025-10-23

---

## Summary

SINGLETON pattern enables proper lifecycle management and graceful cleanup. Using get_X_manager() pattern provides controlled access to stateful managers while supporting LUGS (Lazy Unload Gateway System) for memory management.

---

## Context

METRICS Phase 1 optimization (2025-10-21) revealed that using module-level variables prevented proper lifecycle management. SINGLETON pattern provides initialization, access, and cleanup control.

---

## Content

### The Problem

**Module-level variables:**
```python
# ❌ Before: No lifecycle control
_metrics = {}
_counter = 0

def record_metric(name, value):
    _metrics[name] = value  # Global state, no cleanup
```

**Issues:**
- No initialization control
- No cleanup possible
- No memory management
- LUGS can't unload

### The Solution

**SINGLETON pattern with get_manager:**
```python
# ✅ After: Lifecycle control
def get_metrics_manager():
    """Get or create metrics manager singleton."""
    return gateway.singleton_get_or_create(
        'metrics_manager',
        _create_metrics_manager
    )

def _create_metrics_manager():
    """Factory function for metrics manager."""
    return MetricsManager()

class MetricsManager:
    def __init__(self):
        self._metrics = {}
        self._counter = 0
    
    def record_metric(self, name, value):
        self._metrics[name] = value
    
    def reset(self):
        """Clean up for LUGS."""
        self._metrics.clear()
        self._counter = 0
```

### Lifecycle Management

**1. Lazy Initialization**
```python
# Created only when first accessed
manager = get_metrics_manager()
```

**2. Controlled Access**
```python
# All access through get_manager()
def record_metric(name, value):
    manager = get_metrics_manager()
    return manager.record_metric(name, value)
```

**3. Graceful Cleanup**
```python
# LUGS can unload
def _unload_metrics():
    manager = gateway.singleton_get('metrics_manager')
    if manager:
        manager.reset()
        gateway.singleton_delete('metrics_manager')
```

### Benefits

**1. Memory Management**
- LUGS can unload when memory pressure
- Clean state restoration
- No memory leaks

**2. Testability**
```python
def test_metrics():
    # Clean state for each test
    manager = get_metrics_manager()
    manager.reset()
    # Test with fresh manager
```

**3. Initialization Control**
```python
# Deferred until needed
# Not created if interface unused
```

### The get_X_manager Pattern

**Standard pattern across interfaces:**
```python
# CACHE
def get_cache_manager():
    return gateway.singleton_get_or_create('cache_manager', ...)

# LOGGING  
def get_logging_manager():
    return gateway.singleton_get_or_create('logging_manager', ...)

# METRICS
def get_metrics_manager():
    return gateway.singleton_get_or_create('metrics_manager', ...)
```

**Consistency:** Same pattern for all stateful interfaces

### Evolution Path

**v1.0:** Module variables (simple but no lifecycle)  
**v2.0:** SINGLETON pattern (lifecycle control)  
**v3.0:** LUGS integration (memory management)

### Integration with LUGS

**LUGS (Lazy Unload Gateway System):**
```python
# When memory pressure:
1. LUGS identifies unloadable managers
2. Calls manager.reset()
3. Removes from SINGLETON
4. Frees memory
5. Manager recreated when next accessed
```

---

## Related Topics

- **LESS-14**: Evolution is normal (pattern evolved)
- **INT-06**: SINGLETON interface
- **ARCH-07**: LMMS (LUGS component)

---

## Keywords

SINGLETON pattern, lifecycle management, get_X_manager, LUGS, state management

---

## Version History

- **2025-10-23**: Created - Migrated to SIMA v3 individual file format
- **2025-10-21**: Original documentation in NM06-LESSONS-2025.10.21-METRICS-Phase1.md

---

**File:** `NM06-Lessons-Evolution_LESS-18.md`  
**Directory:** NM06/  
**End of Document**

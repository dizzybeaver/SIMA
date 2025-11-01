# File: LESS-18.md

**REF-ID:** LESS-18  
**Category:** Lessons Learned  
**Topic:** Evolution  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Status:** Production

---

## Title

Singleton Pattern Lifecycle Management

---

## Priority

MEDIUM

---

## Summary

Singleton pattern enables proper lifecycle management and graceful cleanup. Using get_X_manager() pattern provides controlled access to stateful managers while supporting memory management and testing.

---

## Context

Optimization work revealed that using module-level variables prevented proper lifecycle management. Singleton pattern provides initialization, access, and cleanup control.

---

## Lesson

### The Problem

**Module-level variables:**
```python
# ❌ Before: No lifecycle control
_data = {}
_counter = 0

def process_data(name, value):
    _data[name] = value  # Global state, no cleanup
```

**Issues:**
- No initialization control
- No cleanup possible
- No memory management
- Cannot reset for testing

### The Solution

**Singleton pattern with get_manager:**
```python
# ✅ After: Lifecycle control
def get_data_manager():
    """Get or create data manager singleton."""
    return singleton_get_or_create(
        'data_manager',
        _create_data_manager
    )

def _create_data_manager():
    """Factory function for data manager."""
    return DataManager()

class DataManager:
    def __init__(self):
        self._data = {}
        self._counter = 0
    
    def process(self, name, value):
        self._data[name] = value
    
    def reset(self):
        """Clean up for testing/memory management."""
        self._data.clear()
        self._counter = 0
```

### Lifecycle Management

**1. Lazy Initialization**
```python
# Created only when first accessed
manager = get_data_manager()
```

**2. Controlled Access**
```python
# All access through get_manager()
def process_data(name, value):
    manager = get_data_manager()
    return manager.process(name, value)
```

**3. Graceful Cleanup**
```python
# System can unload when needed
def _unload_data():
    manager = singleton_get('data_manager')
    if manager:
        manager.reset()
        singleton_delete('data_manager')
```

### Benefits

**1. Memory Management**
- Can unload when memory pressure
- Clean state restoration
- No memory leaks

**2. Testability**
```python
def test_data():
    # Clean state for each test
    manager = get_data_manager()
    manager.reset()
    # Test with fresh manager
```

**3. Initialization Control**
```python
# Deferred until needed
# Not created if module unused
```

### The get_X_manager Pattern

**Standard pattern across modules:**
```python
# CACHE
def get_cache_manager():
    return singleton_get_or_create('cache_manager', ...)

# LOGGING  
def get_logging_manager():
    return singleton_get_or_create('logging_manager', ...)

# DATA
def get_data_manager():
    return singleton_get_or_create('data_manager', ...)
```

**Consistency:** Same pattern for all stateful modules

### Evolution Path

**v1.0:** Module variables (simple but no lifecycle)  
**v2.0:** Singleton pattern (lifecycle control)  
**v3.0:** Memory management (cleanup integration)

### Memory Management Integration

**When memory pressure:**
```python
1. System identifies unloadable managers
2. Calls manager.reset()
3. Removes from singleton registry
4. Frees memory
5. Manager recreated when next accessed
```

---

## Related

**Cross-References:**
- LESS-14: Evolution is normal (pattern evolved)
- LESS-08: Test failure paths (testability)

**Keywords:** singleton pattern, lifecycle management, get_X_manager, state management, testability

---

## Version History

- **4.0.0** (2025-10-30): Genericized for SIMAv4
- **3.0.0** (2025-10-23): Initial SIMAv3 format

---

**END OF FILE**

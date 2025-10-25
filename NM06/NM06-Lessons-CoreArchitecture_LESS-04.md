# NM06-Lessons-CoreArchitecture_LESS-04.md - LESS-04

# LESS-04: Consistency Over Cleverness

**Category:** Lessons  
**Topic:** CoreArchitecture  
**Priority:** HIGH  
**Status:** Active  
**Created:** 2025-10-23  
**Last Updated:** 2025-10-23

---

## Summary

Uniform patterns across all interfaces reduce cognitive load and prevent mistakes. All interfaces use the same dispatch dictionary pattern, even if some could be "optimized" differently.

---

## Context

Early in development, different interfaces used different routing patterns (dispatch dictionaries, if/elif chains, mixed approaches). This inconsistency made the codebase harder to understand and maintain.

---

## Content

### The Problem

**Inconsistent patterns:**
```python
# interface_cache.py used dispatch dictionary
_OPERATION_DISPATCH = {
    'get': _execute_get_implementation,
    'set': _execute_set_implementation,
}

# interface_logging.py used if/elif chain
def execute_logging_operation(operation, **kwargs):
    if operation == 'info':
        return _execute_info_implementation(**kwargs)
    elif operation == 'error':
        return _execute_error_implementation(**kwargs)
```

**Impact:**
- Developers had to remember different patterns for different interfaces
- Adding operations required checking "which pattern does this interface use?"
- Code reviews harder (inconsistent styles)
- Onboarding confusing

### The Solution

**Standardize on ONE pattern everywhere:**
```python
# ALL interfaces use dispatch dictionary
_OPERATION_DISPATCH = {
    'get': _execute_get_implementation,
    'set': _execute_set_implementation,
}

def execute_{interface}_operation(operation, **kwargs):
    impl = _OPERATION_DISPATCH.get(operation)
    if not impl:
        raise ValueError(f"Unknown operation: {operation}")
    return impl(**kwargs)
```

### Why Consistency Wins

**1. Cognitive Load Reduction**
- Inconsistent: Must learn each interface's pattern
- Consistent: Learn once, apply everywhere

**2. Easier to Add Features**
- Same pattern for adding operations to ANY interface
- No need to understand interface-specific patterns

**3. Fewer Bugs**
- Consistent error handling everywhere
- Same validation approach
- Copy-paste scaffolding safely

### The Cleverness Trap

**Temptation:**
```python
# "This interface only has 2 operations, I'll optimize it"
def execute_simple_operation(operation, **kwargs):
    return _execute_info_implementation(**kwargs) if operation == 'info' \
           else _execute_error_implementation(**kwargs)
```

**Reality:**
- First developer: "Cool, I optimized this!"
- Second developer: "Wait, why is this different?"
- Result: Inconsistency, confusion, mistakes

### Key Principle

"There should be one-- and preferably only one --obvious way to do it" (Python Zen)

**Corollary for SUGA:** "All interfaces should look the same, even if some could be 'optimized' differently."

---

## Related Topics

- **DEC-03**: Standard patterns (foundation for this lesson)
- **LESS-01**: Gateway pattern (benefits from consistency)
- **ARCH-01**: Gateway trinity (consistent structure)

---

## Keywords

consistency over clever, standard patterns, uniformity, cognitive load, dispatch pattern

---

## Version History

- **2025-10-23**: Created - Migrated to SIMA v3 individual file format
- **2025-10-15**: Original documentation in NM06-LESSONS-Core Architecture Lessons.md

---

**File:** `NM06-Lessons-CoreArchitecture_LESS-04.md`  
**Directory:** NM06/  
**End of Document**

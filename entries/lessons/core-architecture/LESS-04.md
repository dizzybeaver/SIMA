# File: LESS-04.md

**REF-ID:** LESS-04  
**Category:** Lessons Learned  
**Topic:** Core Architecture  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Status:** Production

---

## Title

Consistency Over Cleverness

---

## Priority

HIGH

---

## Summary

Uniform patterns across all modules reduce cognitive load and prevent mistakes. All modules use the same dispatch pattern, even if some could be "optimized" differently.

---

## Context

Early in development, different modules used different routing patterns (dispatch dictionaries, if/elif chains, mixed approaches). This inconsistency made the codebase harder to understand and maintain.

---

## Lesson

### The Problem

**Inconsistent patterns:**
```python
# Module A used dispatch dictionary
_OPERATION_DISPATCH = {
    'get': _execute_get_implementation,
    'set': _execute_set_implementation,
}

# Module B used if/elif chain
def execute_operation(operation, **kwargs):
    if operation == 'info':
        return _execute_info(**kwargs)
    elif operation == 'error':
        return _execute_error(**kwargs)
```

**Impact:**
- Developers had to remember different patterns for different modules
- Adding operations required checking "which pattern does this module use?"
- Code reviews harder (inconsistent styles)
- Onboarding confusing

### The Solution

**Standardize on ONE pattern everywhere:**
```python
# ALL modules use dispatch dictionary
_OPERATION_DISPATCH = {
    'get': _execute_get_implementation,
    'set': _execute_set_implementation,
}

def execute_operation(operation, **kwargs):
    impl = _OPERATION_DISPATCH.get(operation)
    if not impl:
        raise ValueError(f"Unknown operation: {operation}")
    return impl(**kwargs)
```

### Why Consistency Wins

**1. Cognitive Load Reduction**
- Inconsistent: Must learn each module's pattern
- Consistent: Learn once, apply everywhere

**2. Easier to Add Features**
- Same pattern for adding operations to ANY module
- No need to understand module-specific patterns

**3. Fewer Bugs**
- Consistent error handling everywhere
- Same validation approach
- Copy-paste scaffolding safely

### The Cleverness Trap

**Temptation:**
```python
# "This module only has 2 operations, I'll optimize it"
def execute_simple(operation, **kwargs):
    return _execute_info(**kwargs) if operation == 'info' \
           else _execute_error(**kwargs)
```

**Reality:**
- First developer: "Cool, I optimized this!"
- Second developer: "Wait, why is this different?"
- Result: Inconsistency, confusion, mistakes

### Key Principle

"There should be one-- and preferably only one --obvious way to do it" (Python Zen)

**Corollary:** "All modules should look the same, even if some could be 'optimized' differently."

---

## Related

**Cross-References:**
- DEC-03: Standard patterns decision
- LESS-01: Gateway pattern benefits from consistency
- WISD-04: Consistency over cleverness wisdom

**Keywords:** consistency, standard patterns, uniformity, cognitive load, dispatch pattern

---

## Version History

- **4.0.0** (2025-10-30): Genericized for SIMAv4
- **3.0.0** (2025-10-23): Initial SIMAv3 format

---

**END OF FILE**

# File: LESS-13.md

**REF-ID:** LESS-13  
**Category:** Lessons Learned  
**Topic:** Documentation  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Status:** Production

---

## Title

Architecture Must Be Teachable

---

## Priority

HIGH

---

## Summary

If you can't teach the architecture in 5 minutes, it's too complex. Good architecture is explainable with diagrams, has consistent patterns, uses few core concepts, and examples make sense immediately.

---

## Context

New contributors were confused by the architecture. Different developers explained it differently. Lots of special cases. This indicated architecture was too complex or poorly documented.

---

## Lesson

### Red Flags

**Architecture too complex when:**
- Can't explain in 5 minutes
- Different people explain differently
- Lots of "special cases"
- Documentation exists but nobody reads it
- Easier to show than explain

### The Principle

**If you can't teach it, it's too complex.**

```
Good architecture:
- Explainable in diagram
- Consistent patterns
- Few core concepts
- Examples obvious

Bad architecture:
- Needs long explanation
- Many special cases
- Numerous concepts
- Examples confusing
```

### The Teaching Approach

**1. Start with Analogy**
```
"Gateway pattern is like company organization:

Gateway = CEO office (single entry point)
↓
Modules = Department managers
↓
Cores = Workers (do actual work)

Workers don't talk to each other directly."
```

**2. Show the Pattern**
```python
# User code
result = gateway.cache_get('key')

# What happens
gateway.cache_get()  # Gateway
    ↓
module_cache.execute_operation()  # Module
    ↓
cache_core._execute_get()  # Core
    ↓
return value
```

**3. Explain the Why**
```
Why this pattern?

Problem: Direct imports → circular deps
Solution: Gateway prevents circular deps
Benefit: Can't make mistake even if you try
```

**4. Provide Examples**
```python
# Adding new operation (EASY):

# Step 1: Core implementation
def _execute_delete(key):
    del _store[key]

# Step 2: Add to dispatch
_DISPATCH['delete'] = _execute_delete

# Step 3: Gateway wrapper
def cache_delete(key):
    return execute_operation('cache', 'delete', key=key)
```

### The Teachability Test

**Can new developer:**
- ✅ Add operation in < 30 minutes?
- ✅ Understand gateway in < 15 minutes?
- ✅ Explain architecture to another developer?
- ✅ Find answers in documentation?
- ✅ Follow patterns without help?

### If Not Teachable

**Options:**
1. Simplify architecture (preferred)
2. Improve documentation
3. Add examples
4. Create diagrams
5. Record training video

**Warning signs:**
- Need hour-long explanations
- Special cases everywhere
- "You just have to know" statements
- Different developers explain differently

---

## Related

**Cross-References:**
- LESS-11: Document decisions
- ARCH-01: Gateway pattern example
- DEC-01: Architecture choice rationale

**Keywords:** teachable architecture, explainability, simplicity, onboarding, patterns

---

## Version History

- **4.0.0** (2025-10-30): Genericized for SIMAv4
- **3.0.0** (2025-10-23): Initial SIMAv3 format

---

**END OF FILE**

# NM06-Lessons-Documentation_LESS-13.md - LESS-13

# LESS-13: Architecture Must Be Teachable

**Category:** Lessons  
**Topic:** Documentation  
**Priority:** HIGH  
**Status:** Active  
**Created:** 2025-10-23  
**Last Updated:** 2025-10-23

---

## Summary

If you can't teach the architecture in 5 minutes, it's too complex. Good architecture is explainable with diagrams, has consistent patterns, uses few core concepts, and examples make sense immediately.

---

## Context

New contributors were confused by the architecture. Different developers explained it differently. Lots of special cases. This indicated architecture was too complex or poorly documented.

---

## Content

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

### The SUGA Teaching Approach

**1. Start with Analogy**
```
"SUGA is like company organization:

Gateway = CEO office (single entry point)
↓
Interfaces = Department managers
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
interface_cache.execute_cache_operation()  # Interface
    ↓
cache_core._execute_get_implementation()  # Core
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
def _execute_delete_implementation(key):
    del _CACHE_STORE[key]

# Step 2: Add to dispatch
_OPERATION_DISPATCH['delete'] = _execute_delete_implementation

# Step 3: Gateway wrapper
def cache_delete(key):
    return execute_operation(CACHE, 'delete', key=key)
```

### The Teachability Test

**Can new developer:**
- ✅ Add operation in < 30 minutes?
- ✅ Understand gateway in < 15 minutes?
- ✅ Explain architecture to another developer?
- ✅ Find answers in documentation?
- ✅ Follow patterns without help?

**If NO:** Architecture too complex or poorly documented

### Onboarding Checklist

**Day 1: Concepts**
- Read: Quick Index (trigger patterns)
- Read: Gateway Trinity (ARCH-01)
- Draw: Architecture diagram yourself
- Explain: Gateway pattern to team

**Day 2: Hands-On**
- Add: New cache operation
- Test: Your operation works
- Compare: To existing operations
- Understand: Why patterns consistent

**Day 3: Deep Dive**
- Read: Why gateway pattern (DEC-01)
- Read: What it prevents (BUG-02)
- Explore: All 12 interfaces
- Task: Add operation to different interface

**Success:** Can add operation independently by Day 3

### Key Insights

**1. Consistency Aids Teaching**
- All interfaces use same pattern
- Learn once, apply everywhere

**2. Examples Beat Explanations**
- Long explanation: Hard
- Working example: Immediately clear

**3. Architecture Should Be Obvious**
```
✅ "I see the pattern. All interfaces work the same."
❌ "This one is different because... wait, why?"
```

**4. Documentation is Part of Architecture**
- Code + Docs = Complete Architecture
- Great code with no docs = Unusable

---

## Related Topics

- **DEC-19**: Documentation standards
- **DEC-01**: SUGA pattern choice
- **LESS-11**: Document decisions

---

## Keywords

teachable architecture, explain architecture, onboarding, simplicity, patterns

---

## Version History

- **2025-10-23**: Created - Migrated to SIMA v3 individual file format
- **2025-10-20**: Original documentation in NM06-LESSONS-Documentation and Knowledge.md

---

**File:** `NM06-Lessons-Documentation_LESS-13.md`  
**Directory:** NM06/  
**End of Document**

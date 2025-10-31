# File: LESS-16.md

**REF-ID:** LESS-16  
**Category:** Lessons Learned  
**Topic:** Evolution  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Status:** Production

---

## Title

Adaptation Over Rewriting

---

## Priority

CRITICAL

---

## Summary

Adapt internals while preserving API surface. Rewriting from scratch wastes effort, introduces bugs, and breaks callers. Keep exports, keep structure, modify implementation - saves 80% of time and prevents breaks.

---

## Context

Request to "simplify module" led to complete rewrite that removed exported functions, broke imports, and wasted significant time. Should have adapted internals while keeping API.

---

## Lesson

### The Incident

**Wrong approach:** Rewrote entire module from scratch  
**Result:** Removed exports, broke imports, wasted time

```python
# Original: 12 functions exported
__all__ = ['execute_operation', 'get_value', 'set_value', ...]

# Rewritten: Only 1 function
__all__ = ['execute_operation']  # Broke 11 imports!
```

**Cost:**
- Time: 2-3x normal
- Bugs: Import errors
- Rollback: Yes

### The Right Approach

**Adaptation means:**
- ✅ Keep API surface (all exports)
- ✅ Keep structure
- ✅ Modify internals
- ✅ Improve clarity
- ❌ Remove exports
- ❌ Rewrite from scratch
- ❌ Change contracts

**Example:**
```python
# BEFORE: Complex implementation
def execute_operation(operation, **kwargs):
    # Complex caching logic
    # Complex retry logic
    # Complex error handling
    return _complex_implementation(**kwargs)

# AFTER: Simplified (same signature!)
def execute_operation(operation, **kwargs):
    # Same function exists, simpler inside
    return _simple_implementation(**kwargs)
```

### The Rewrite Trap

**Rewriting causes:**
1. Lost functionality (12 → 3 functions)
2. Time waste (3x longer)
3. New bugs in new code
4. Broken contracts
5. Lost knowledge

### The Adaptation Pattern

**Step 1: Identify what to keep**
- All `__all__` exports
- Function signatures
- API contracts
- External interfaces

**Step 2: Modify internals only**
```python
# ✅ Keep exports, simplify implementation
__all__ = ['get_value', 'set_value']  # Unchanged

def get_value(key):  # Signature unchanged
    # New, simpler implementation
    return _simple_get(key)
```

**Step 3: Test backwards compatibility**
```python
# After adaptation, all imports still work
from module import get_value, set_value  # ✅
```

### When to Rewrite vs Adapt

**Rewrite only when:**
- ❌ API fundamentally broken
- ❌ Architecture needs complete redesign
- ❌ Breaking changes acceptable

**Adapt almost always (95%):**
- ✅ Implementation can be improved
- ✅ API is sound
- ✅ Backwards compatibility required

### Time Economics

**Rewrite costs:**
```
Generate new code: ~Large effort
Debug breaks: ~Significant
Fix imports: ~Additional time
Total: ~3x normal time
```

**Adaptation costs:**
```
Modify functions: ~Normal effort
Verify API: ~Minimal
Total: ~1x normal time
```

**Savings:** 66% time saved, fewer bugs

### The "Simplify X" Rule

**When user says "simplify":**
- ✅ Simplify internal implementation
- ✅ Reduce complexity
- ✅ Improve clarity
- ❌ Remove exports
- ❌ Rewrite from scratch
- ❌ Change API

### Verification Checklist

**After adaptation:**
- ☐ All `__all__` exports present
- ☐ Function signatures unchanged
- ☐ Existing imports work
- ☐ Tests pass
- ☐ No ImportError
- ☐ API contracts honored

---

## Related

**Cross-References:**
- LESS-09: Partial deployment danger
- LESS-14: Evolution is normal
- BUG-04: Configuration mismatch example

**Keywords:** adapt don't rewrite, targeted changes, efficiency, preserve API, backwards compatibility

---

## Version History

- **4.0.0** (2025-10-30): Genericized for SIMAv4
- **3.0.0** (2025-10-23): Initial SIMAv3 format

---

**END OF FILE**

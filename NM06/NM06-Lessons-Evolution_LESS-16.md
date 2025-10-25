# NM06-Lessons-Evolution_LESS-16.md - LESS-16

# LESS-16: Adaptation Over Rewriting

**Category:** Lessons  
**Topic:** Evolution  
**Priority:** CRITICAL  
**Status:** Active  
**Created:** 2025-10-23  
**Last Updated:** 2025-10-23

---

## Summary

Adapt internals while preserving API surface. Rewriting from scratch wastes tokens, introduces bugs, and breaks callers. Keep exports, keep structure, modify implementation - saves 80% of tokens and prevents breaks.

---

## Context

Request to "simplify SSM" led to complete rewrite that removed exported functions, broke imports, and wasted ~18K tokens. Should have adapted internals while keeping API.

---

## Content

### The Incident

**Wrong approach:** Rewrote entire interface from scratch  
**Result:** Removed exports, broke imports, wasted tokens

```python
# Original: 12 functions exported
__all__ = ['execute_ssm_operation', 'get_parameter', 'set_parameter', ...]

# Rewritten: Only 1 function
__all__ = ['execute_ssm_operation']  # Broke 11 imports!
```

**Cost:**
- Tokens: ~13K (regenerate file)
- Time: 2x normal
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
def execute_ssm_operation(operation, **kwargs):
    # Complex caching logic
    # Complex retry logic
    # Complex error handling
    return _get_with_all_features(name, with_decrypt)

# AFTER: Simplified (same signature!)
def execute_ssm_operation(operation, **kwargs):
    # Same function exists, simpler inside
    return _simple_get(kwargs.get('name'))
```

### The Rewrite Trap

**Rewriting causes:**
1. Lost functionality (12 → 3 functions)
2. Token waste (18K vs 3.5K)
3. New bugs in new code
4. Broken contracts
5. Lost time (2-3x longer)

### The Adaptation Pattern

**Step 1: Identify what to keep**
- All `__all__` exports
- Function signatures
- API contracts
- External interfaces

**Step 2: Modify internals only**
```python
# ✅ Keep exports, simplify implementation
__all__ = ['get_parameter', 'set_parameter']  # Unchanged

def get_parameter(name):  # Signature unchanged
    # New, simpler implementation
    return ssm_client.get_parameter(Name=name)['Parameter']['Value']
```

**Step 3: Test backwards compatibility**
```python
# After adaptation, all imports still work
from interface_ssm import get_parameter, set_parameter  # ✅
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

### Token Economics

**Rewrite costs:**
```
Generate file: ~13K tokens
Debug breaks: ~2K tokens
Fix imports: ~3K tokens
Total: ~18K tokens
```

**Adaptation costs:**
```
Modify functions: ~3K tokens
Verify API: ~0.5K tokens
Total: ~3.5K tokens
```

**Savings:** 80% fewer tokens, 2-3x faster

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

## Related Topics

- **BUG-06**: Config mismatch (partial deployment)
- **LESS-09**: Partial deployment danger
- **LESS-14**: Evolution is normal

---

## Keywords

adapt don't rewrite, targeted changes, efficiency, preserve API, token conservation

---

## Version History

- **2025-10-23**: Created - Migrated to SIMA v3 individual file format
- **2025-10-20**: Original documentation in NM06-LESSONS-Recent Updates 2025.10.20.md

---

**File:** `NM06-Lessons-Evolution_LESS-16.md`  
**Directory:** NM06/  
**End of Document**

# NM06-Lessons-Documentation_LESS-12.md - LESS-12

# LESS-12: Code Comments vs External Documentation

**Category:** Lessons  
**Topic:** Documentation  
**Priority:** MEDIUM  
**Status:** Active  
**Created:** 2025-10-23  
**Last Updated:** 2025-10-23

---

## Summary

Separate documentation by purpose: Docstrings for API contracts, minimal comments for tricky logic only, external docs (SIMA) for architecture and decisions. Comments decay, docs persist.

---

## Context

Comments in code get outdated when code changes but comments don't. Misleading comments are worse than no comments. Need three-tier documentation strategy.

---

## Content

### The Problem

**Comments decay:**
```python
# ❌ WRONG COMMENT
def cache_get(key):
    """Returns string or raises KeyError if not found."""
    # ^ Function no longer raises KeyError!
    return _cache_store.get(key, None)
```

**Why comments decay:**
1. Code changes, comments don't
2. No automated checking of accuracy
3. Developers focus on code
4. Comments are "second-class citizens"

### The Solution

**Three-tier documentation:**

```python
# Tier 1: DOCSTRINGS (API contracts - maintained)
def cache_get(key: str) -> Optional[str]:
    """Get value from cache.
    
    Args:
        key: Cache key to retrieve
        
    Returns:
        Cached value if found, None if not found
    """
    return _cache_store.get(key, None)

# Tier 2: MINIMAL COMMENTS (tricky logic only)
def _execute_cache_operation(operation, **kwargs):
    result = _dispatch[operation](**kwargs)
    
    # Sanitize sentinel to prevent BUG-01
    if _is_sentinel(result):
        return None
    
    return result

# Tier 3: EXTERNAL DOCS (architecture, decisions)
# See: NM04-DEC-05 (Why sentinels used)
# See: NM06-BUG-01 (Sentinel leak incident)
```

### Comment Guidelines

**When to comment:**
```python
✅ Explain WHY, not WHAT
# Sanitize sentinel to prevent user code from seeing internal object

✅ Warn about non-obvious behavior
# IMPORTANT: This function modifies input dict

✅ Explain complex algorithm
# Binary search: O(log n) lookup

❌ Restate the obvious
# Set x to 5
x = 5
```

### Docstring Guidelines

```python
✅ Complete docstring
def cache_set(key: str, value: str, ttl: int = 300) -> bool:
    """Store value in cache with TTL.
    
    Args:
        key: Cache key (non-empty string)
        value: Value to store (JSON-serializable)
        ttl: Time-to-live in seconds (default: 300)
        
    Returns:
        True if stored, False on error
        
    Raises:
        ValueError: If key is empty
    """
```

### The Hierarchy

```
EXTERNAL DOCS (SIMA)
- Architecture patterns
- Design rationale
- Historical context
Priority: High-level understanding

DOCSTRINGS (In code)
- Function contracts
- API documentation
Priority: Usage understanding

MINIMAL COMMENTS (In code)
- Tricky logic
- Non-obvious behavior
Priority: Implementation understanding

NO COMMENTS
- Self-explanatory code
Priority: Keep clean
```

### Maintenance Strategy

**Docstrings checked by tests:**
```python
def test_cache_get_docstring():
    # Test: "Returns None if not found"
    assert cache_get('missing') is None
```

**Comments reviewed in code reviews:**
```
✅ Are comments necessary?
✅ Do comments explain WHY not WHAT?
❌ Any commented-out code?
```

---

## Related Topics

- **LESS-11**: Document decisions (where external docs go)
- **LESS-13**: Architecture teachable (how docs help)
- **AP-26**: Outdated comments anti-pattern

---

## Keywords

comments vs docs, external documentation, comment maintenance, docstrings, SIMA

---

## Version History

- **2025-10-23**: Created - Migrated to SIMA v3 individual file format
- **2025-10-20**: Original documentation in NM06-LESSONS-Documentation and Knowledge.md

---

**File:** `NM06-Lessons-Documentation_LESS-12.md`  
**Directory:** NM06/  
**End of Document**

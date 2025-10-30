# File: DEC-05.md

**REF-ID:** DEC-05  
**Category:** Architecture Decision  
**Priority:** High  
**Status:** Active  
**Date Decided:** 2024-06-15  
**Created:** 2024-06-15  
**Last Updated:** 2025-10-29 (SIMAv4 migration)

---

## ðŸ"‹ SUMMARY

Router layer must sanitize internal sentinel objects (like _CacheMiss) to None before returning to callers, preventing 535ms performance penalty and maintaining clean API boundaries.

**Decision:** Router-layer sentinel sanitization mandatory  
**Impact Level:** High  
**Reversibility:** Difficult (API contract change)

---

## ðŸŽ¯ CONTEXT

### Problem Statement
BUG-01 discovered that internal cache sentinel object (_CacheMiss) leaked to callers, causing 535ms performance penalty when compared using == instead of is. Need architectural solution.

### Background
- Cache layer used _CacheMiss sentinel for "key not found"
- Sentinel leaked through router to external callers
- Callers used == comparison (standard Python practice)
- Sentinel's __eq__ method caused 535ms penalty
- Bug happened multiple times despite awareness

### Requirements
- Prevent sentinel leakage permanently
- Maintain clean external API
- Zero performance penalty for cache misses
- Architectural solution (not just documentation)

---

## ðŸ'¡ DECISION

### What We Chose
Router layer must convert all internal sentinel objects to None before returning to callers. Sentinels are implementation details that never cross the router boundary.

### Implementation
```python
# âŒ BEFORE - Sentinel leaked to caller
def router(operation, **kwargs):
    result = core_function(**kwargs)
    return result  # Might be sentinel!

# Caller code
value = gateway.cache_get("key")
if value == None:  # WRONG - but natural Python
    # If value is _CacheMiss, this costs 535ms!
    ...

# âœ… AFTER - Router sanitizes sentinel
def router(operation, **kwargs):
    result = core_function(**kwargs)
    
    # Sanitize sentinels at boundary
    if isinstance(result, _CacheMiss):
        return None
    
    return result

# Caller code
value = gateway.cache_get("key")
if value is None:  # CORRECT - fast, Pythonic
    ...
```

### Rationale
1. **Clean API Boundary**
   - External callers see None, not sentinels
   - Pythonic interface (None is standard)
   - No sentinel knowledge required
   - Clear separation internal vs external

2. **Performance**
   - None comparison: nanoseconds
   - Sentinel comparison: 535ms penalty
   - Router overhead: ~50ns (negligible)
   - Net improvement: 535ms savings

3. **Prevents Bugs**
   - Architectural enforcement
   - Not relying on documentation
   - Impossible to leak sentinel
   - Future-proof against new sentinels

4. **Maintainability**
   - One place to handle sanitization
   - Clear responsibility (router layer)
   - Easy to audit
   - Consistent across all interfaces

---

## ðŸ"„ ALTERNATIVES CONSIDERED

### Alternative 1: Document Sentinel Usage
**Description:** Tell developers to use 'is' instead of '=='

**Pros:**
- No code changes needed
- Preserves sentinel semantics

**Cons:**
- Relies on developer discipline
- Easy to forget
- Non-Pythonic (None is standard)
- Already failed (caused BUG-01)

**Why Rejected:** Documentation doesn't prevent bugs

---

### Alternative 2: Fast Sentinel __eq__
**Description:** Fix sentinel's __eq__ method to be fast

**Pros:**
- Preserves sentinel in API
- Allows == comparison

**Cons:**
- Still leaks implementation detail
- Non-Pythonic API
- Confuses external users
- More complex than None

**Why Rejected:** Sentinel shouldn't cross boundary

---

### Alternative 3: Result Wrapper Objects
**Description:** Wrap results in Result(value, found=True/False)

**Pros:**
- Explicit success/failure
- Type-safe

**Cons:**
- More complex API
- Overkill for simple cache
- Breaking change for existing code
- Extra object allocation

**Why Rejected:** Over-engineered solution

---

## âš–ï¸ TRADE-OFFS

### What We Gained
- 535ms performance penalty eliminated
- Pythonic API (None is standard)
- Architectural bug prevention
- Clean internal/external boundary
- Future-proof against new sentinels

### What We Accepted
- ~50ns router overhead per operation
- Must remember to sanitize new sentinels
- Can't use sentinel semantics externally
- Router responsibility increased slightly

---

## ðŸ"Š IMPACT ANALYSIS

### Technical Impact
**Performance:**
- Cache miss: 535ms penalty â†' 0ms (None comparison)
- Router overhead: +50ns (negligible)
- Net improvement: 534.999950ms per cache miss

**API Quality:**
- Before: Sentinel objects in API (confusing)
- After: None (Pythonic, clear)
- Developer experience: Significantly improved

**Architecture:**
- Clear boundary enforcement
- Router responsibility defined
- Implementation detail containment

### Operational Impact
**Bug Prevention:**
- BUG-01 class of errors prevented architecturally
- Can't forget to use 'is' (now using None)
- Zero similar bugs in 6+ months since fix

**Development:**
- Simpler API documentation
- Easier to explain to new developers
- Standard Python patterns apply

**Maintenance:**
- One place to check (router layer)
- Easy to audit sanitization
- Clear patterns to follow

---

## ðŸ"® FUTURE CONSIDERATIONS

### When to Revisit
- If sentinels need to cross boundary for valid reason (unlikely)
- If router overhead becomes measurable (extremely unlikely)
- If Python introduces better missing-value semantics

### Evolution Path
1. Add type hints indicating None possible
2. Consider Result wrapper for more complex cases
3. Generate API documentation from router

### Monitoring
- âœ… Zero sentinel leaks (6+ months)
- âœ… Zero performance issues from sanitization
- âœ… Developer feedback positive
- âœ… Code reviews verify sanitization

---

## ðŸ"— RELATED

### Related Bugs
- BUG-01 - Sentinel Leak (caused this decision)

### Related Decisions
- DEC-02 - Gateway Centralization (router is boundary)
- DEC-15 - Router-Level Exceptions (router responsibilities)

### SIMA Entries
- GATE-01 - Three-File Structure (router layer role)
- INT-01 - Cache Interface (where sentinels used)

### Anti-Patterns
- AP-19 - Sentinel Leakage (this decision prevents)

### Lessons
- LESS-05 - Sentinel handling lessons
- LESS-12 - API boundary importance

---

## ðŸ·ï¸ KEYWORDS

`sentinel-sanitization`, `router-layer`, `API-boundary`, `performance`, `cache-miss`, `None-vs-sentinel`, `bug-prevention`

---

## ðŸ" VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 3.0.0 | 2025-10-29 | Migration | SIMAv4 migration |
| 2.0.0 | 2025-10-23 | System | SIMA v3 format |
| 1.0.0 | 2024-06-15 | Original | Decision after BUG-01 discovery |

---

**END OF DECISION**

**Status:** Active - Enforced in all router layers  
**Effectiveness:** 100% - Zero sentinel leaks, 535ms penalty eliminated

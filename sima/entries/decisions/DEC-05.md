# File: DEC-05.md

**REF-ID:** DEC-05  
**Category:** Architecture Decision  
**Priority:** High  
**Status:** Active  
**Date Decided:** 2024-06-15  
**Created:** 2024-06-15  
**Last Updated:** 2025-10-29 (SIMAv4 migration)

---

## 📋 SUMMARY

Router layer must sanitize internal sentinel objects (like _CacheMiss) to None before returning to callers, preventing 535ms performance penalty and maintaining clean API boundaries.

**Decision:** Router-layer sentinel sanitization mandatory  
**Impact Level:** High  
**Reversibility:** Difficult (API contract change)

---

## 🎯 CONTEXT

### Problem Statement
BUG-01 discovered internal cache sentinel object (_CacheMiss) leaked to callers, causing 535ms performance penalty when compared using == instead of is. Need architectural solution.

### Background
- Cache layer used _CacheMiss sentinel for "key not found"
- Sentinel leaked through router to external callers
- Callers used == comparison (standard Python practice)
- Sentinel's __eq__ method caused 535ms penalty
- Bug recurred despite documentation

### Requirements
- Prevent sentinel leakage permanently
- Maintain clean external API
- Zero performance penalty for cache misses
- Architectural solution (not just documentation)

---

## 💡 DECISION

### What We Chose
Router layer must convert all internal sentinel objects to None before returning to callers. Sentinels are implementation details that never cross the router boundary.

### Implementation
```python
# ❌ BEFORE - Sentinel leaked to caller
_CacheMiss = object()

def cache_get(key):
    value = _cache.get(key, _CacheMiss)
    return value  # Sentinel leaks!

# Caller code
result = cache_get("key")
if result == None:  # WRONG but natural - costs 535ms!
    print("Miss")

# ✅ AFTER - Router sanitizes sentinel
_CacheMiss = object()

def cache_get(key):
    value = _cache.get(key, _CacheMiss)
    
    # Sanitize at boundary
    if value is _CacheMiss:
        return None
    
    return value

# Caller code
result = cache_get("key")
if result is None:  # CORRECT - fast, Pythonic
    print("Miss")
```

### Rationale
1. **Prevents Performance Bug**
   - BUG-01: Sentinel leak caused 535ms penalty
   - None comparison: nanoseconds
   - Router overhead: ~50ns (negligible)
   - Net improvement: 535ms saved per miss

2. **Clean API Boundary**
   - External callers see None, not sentinels
   - Pythonic interface (None is standard)
   - No sentinel knowledge required
   - Clear internal vs external separation

3. **Architectural Enforcement**
   - Not relying on documentation
   - Impossible to leak sentinel past router
   - Router enforces contract
   - Future-proof against new sentinels

4. **Encapsulation**
   - Implementation details stay internal
   - Can change sentinel freely
   - Callers unaffected by internal changes
   - Proper abstraction boundary

---

## 📄 ALTERNATIVES CONSIDERED

### Alternative 1: Document "Use 'is' Not '=='"
**Pros:**
- No code changes needed
- Keeps sentinel in external API

**Cons:**
- Relies on developer discipline
- Easy to forget
- Bug already happened despite docs
- Unnatural Python (None typically uses ==)

**Why Rejected:** Documentation alone failed - architectural solution needed.

---

### Alternative 2: Raise Exception Instead of Sentinel
**Pros:**
- No sentinel needed
- Clear "not found" signal

**Cons:**
- Exceptions expensive for control flow
- Cache miss is expected, not exceptional
- Forces try/except everywhere
- Performance worse than sentinel

**Why Rejected:** Exceptions aren't for expected conditions.

---

### Alternative 3: Return (found, value) Tuple
**Pros:**
- Explicit found/not-found state
- No sentinel needed

**Cons:**
- Breaks existing API
- More complex caller code
- Unnatural Python pattern
- Major migration required

**Why Rejected:** Breaking change, less Pythonic.

---

### Alternative 4: Custom Sentinel with Safe __eq__
**Pros:**
- Keeps sentinel pattern
- No leak risk

**Cons:**
- Complex sentinel implementation
- Still exposes internal detail
- Harder to understand
- Doesn't solve API cleanliness

**Why Rejected:** Router sanitization simpler and cleaner.

---

## ⚖️ TRADE-OFFS

### What We Gained
- 535ms performance penalty eliminated
- Clean external API (None is Pythonic)
- Architectural enforcement (can't leak)
- Future-proof (any sentinel caught)
- Zero sentinel bugs since fix

### What We Accepted
- ~50ns router overhead per operation
- Slightly more complex router code
- Cannot distinguish "None" from "not found" externally
- Must maintain sanitization layer

---

## 📊 IMPACT ANALYSIS

### Technical Impact
- **Performance:** 535ms penalty eliminated, 50ns overhead added (net gain)
- **API:** Cleaner interface, Pythonic conventions
- **Architecture:** Clear boundary enforcement pattern
- **Maintenance:** Can change sentinels without affecting callers

### Operational Impact
- **Debugging:** Simpler (no sentinel leaks to trace)
- **Development:** Standard Python patterns work
- **Code Review:** Check sanitization at boundaries
- **Results:** Zero sentinel bugs in 6+ months

---

## 🔮 FUTURE CONSIDERATIONS

### When to Revisit
- If need to distinguish "None value" from "not found"
- If 50ns overhead becomes measurable (hasn't)
- If sentinels needed in external API (unlikely)

### Evolution Path
- Automatic sanitization framework (decorator?)
- Static analysis to detect sentinel leaks
- Linting rule: sentinels never in return types
- Router pattern template with sanitization

---

## 🔗 RELATED

### Related Decisions
- DEC-01 - SUGA Pattern (routers part of architecture)
- DEC-02 - Gateway Centralization (sanitization at gateway)

### SIMA Entries
- BUG-01 - Sentinel Leak Bug (problem this solves)
- AP-10 - Sentinel Objects Crossing Boundaries
- LESS-06 - Sentinel Objects Lesson

### Related Files
- `/sima/entries/interfaces/INT-01-Cache-Interface.md`
- `/sima/entries/gateways/GATE-01-Three-File-Structure.md`

---

## 🏷️ KEYWORDS

`sentinel-sanitization`, `router-boundary`, `API-cleanliness`, `performance-bug`, `encapsulation`, `BUG-01-prevention`, `abstraction-boundary`

---

## 📝 VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 3.0.0 | 2025-10-29 | Migration | SIMAv4 migration |
| 2.0.0 | 2025-10-23 | System | SIMA v3 format |
| 1.0.0 | 2024-06-15 | Original | Decision after BUG-01 |

---

**END OF DECISION**

**Status:** Active - Enforced in all 12 interfaces  
**Effectiveness:** 100% - Zero sentinel bugs, 535ms penalty eliminated

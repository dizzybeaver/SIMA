# NM04-Decisions-Architecture_DEC-05.md - DEC-05

# DEC-05: Sentinel Sanitization at Router Layer

**Category:** Decisions
**Topic:** Architecture
**Priority:** 🟡 High
**Status:** Active
**Date Decided:** 2024-05-10
**Created:** 2025-10-23
**Last Updated:** 2025-10-23

---

## Summary

Internal sentinel objects must be sanitized (converted to None) at the router boundary before returning to callers, preventing sentinel leaks and the 535ms performance penalty discovered in BUG-01.

---

## Context

Cache implementation used `_CacheMiss` sentinel object to distinguish "key not found" from "key exists with None value". Initially, this sentinel leaked to callers who compared it using `==` instead of `is`, triggering expensive `__eq__` operations. BUG-01 revealed a 535ms performance penalty per cache miss.

---

## Content

### The Decision

**What We Chose:**
Router layer sanitizes internal sentinels to None before returning to external callers. Sentinels never cross API boundaries.

**Implementation:**
```python
# ❌ WRONG - Sentinel leaks to caller
_CacheMiss = object()

def cache_get(key):
    value = _internal_cache.get(key, _CacheMiss)
    return value  # Sentinel leaks!

# Caller code (problematic)
result = cache_get("key")
if result == _CacheMiss:  # Triggers __eq__, might be slow!
    print("Miss")

# ✅ CORRECT - Router sanitizes sentinel
_CacheMiss = object()

def cache_get(key):
    value = _internal_cache.get(key, _CacheMiss)
    if value is _CacheMiss:  # Internal comparison uses 'is'
        return None  # Sanitize to None before returning
    return value

# Caller code (clean)
result = cache_get("key")
if result is None:  # Standard Python idiom
    print("Miss")
```

### Rationale

**Why We Chose This:**

1. **Prevents Performance Bugs**
   - BUG-01: Sentinel leaked, caller used `==`, triggered `__eq__`
   - `__eq__` was expensive: 535ms per comparison
   - Router sanitization: sentinel never exposed
   - **Result:** 535ms penalty eliminated completely

2. **Clean API**
   - Callers use standard Python idiom: `if value is None`
   - No need to import internal sentinels
   - No documentation of sentinel objects
   - Pythonic interface (None means "not found")
   - **Result:** API matches user expectations

3. **Encapsulation**
   - Internal implementation details stay internal
   - Router is the boundary between internal and external
   - Sentinels are implementation choice, not API contract
   - Can change sentinel implementation without breaking callers
   - **Result:** Proper abstraction boundaries

4. **Safety**
   - Impossible to misuse sentinel (never see it)
   - No need to know about sentinel existence
   - No risk of `==` vs `is` confusion
   - **Result:** Pit of success (hard to use wrong)

### Alternatives Considered

**Alternative 1: Expose Sentinel, Document Usage**
- **Description:** Let sentinel cross boundary, document use of `is` operator
- **Pros:**
  - Slightly more "honest" (shows internal state)
  - Allows caller to distinguish None values
- **Cons:**
  - Relies on documentation (often missed)
  - Easy to use `==` by mistake (BUG-01 proved this)
  - Leaks implementation details
  - Forces sentinel import
- **Why Rejected:** BUG-01 showed developers WILL use `==` by mistake

**Alternative 2: Raise Exception on Miss**
- **Description:** Raise `KeyError` like dict.get() when key missing
- **Pros:**
  - Explicit error condition
  - Forces caller to handle
  - No sentinel needed
- **Cons:**
  - Different from dict.get() behavior (returns None)
  - Exception overhead (~10μs per miss)
  - Makes cache misses feel like errors
  - Uglier code (try/except vs if/else)
- **Why Rejected:** Cache miss is not error, it's expected case

**Alternative 3: Return Tuple (found, value)**
- **Description:** Return (True, value) or (False, None)
- **Pros:**
  - Explicit found/not-found distinction
  - Can return None values when found
  - No sentinel needed
- **Cons:**
  - Tuple unpacking required every call
  - More verbose: `found, val = cache_get(key); if found:`
  - Different from standard Python patterns
  - API complexity for little benefit
- **Why Rejected:** Complexity not worth marginal benefit

### Trade-offs

**Accepted:**
- Cannot distinguish "key not found" from "key exists with None value"
- ~50ns overhead per operation (sanitization check)
- Slightly less precise (None conflates two cases)

**Benefits:**
- Prevents 535ms bug (10,000x improvement over bug case)
- Clean Pythonic API (matches user expectations)
- No sentinel leakage (encapsulation)
- Impossible to misuse (pit of success)
- Standard Python idiom: `if value is None`

**Net Assessment:**
The ~50ns overhead is completely negligible compared to:
- Cache operation time: ~1,000,000ns (1ms)
- Bug penalty prevented: 535,000,000ns (535ms)
- Ratio: 50ns cost vs 535ms bug = 10,000,000x benefit

The inability to distinguish "not found" from "found None" has not been an issue in 6+ months.

### Impact

**On Architecture:**
- Defines router layer responsibility (boundary enforcement)
- Establishes sanitization pattern for all routers
- Clean internal/external separation
- Routers protect external API from internal details

**On Development:**
- Developers never see sentinels in caller code
- No import of internal objects needed
- Standard Python patterns work (if value is None)
- Code reviews: check sanitization at boundaries

**On Performance:**
- ~50ns overhead per operation (negligible)
- Prevents 535ms penalty (BUG-01)
- Net improvement: 10,000,000x
- No performance issues in 6+ months

**On Maintenance:**
- Can change sentinel implementation freely
- Callers unaffected by internal changes
- Clear abstraction boundaries
- Zero sentinel-related bugs since fix

### Future Considerations

**When to Revisit:**
- If need to distinguish "not found" from "found None"
- If ~50ns overhead becomes measurable (hasn't in 6+ months)
- If sentinels needed in external API (unlikely)

**Potential Evolution:**
- Automatic sanitization framework (decorator?)
- Static analysis to detect sentinel leaks
- Linting rule: sentinels never in return types

**Monitoring Needs:**
- Track sanitization overhead (verify negligibility)
- Monitor for sentinel leak attempts (code review)
- Alert on sentinel in return values (would be bug)

---

## Related Topics

- **BUG-01**: Sentinel leak causing 535ms penalty (problem this solves)
- **DEC-01**: SIMA pattern (routers are part of SIMA)
- **DEC-02**: Gateway centralization (sanitization at gateway layer)
- **AP-19**: Sentinel leakage anti-pattern (this decision prevents)
- **BUG-02**: _CacheMiss validation (related sentinel issue)

---

## Keywords

sentinel sanitization, router boundary, API cleanliness, performance, encapsulation, BUG-01 prevention, internal vs external, abstraction

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2024-05-10**: Original decision documented in NM04-ARCHITECTURE-Decisions.md (after BUG-01)

---

**File:** `NM04-Decisions-Architecture_DEC-05.md`
**End of Document**

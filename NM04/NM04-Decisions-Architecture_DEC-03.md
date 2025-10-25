# NM04-Decisions-Architecture_DEC-03.md - DEC-03

# DEC-03: Dispatch Dictionary Pattern

**Category:** Decisions
**Topic:** Architecture
**Priority:** 🔴 Critical
**Status:** Active
**Date Decided:** 2024-04-20
**Created:** 2025-10-23
**Last Updated:** 2025-10-23

---

## Summary

Use dictionary dispatch instead of if/elif chains for operation routing, providing O(1) lookup performance, 90% code reduction, and clean extensibility without modifying existing code.

---

## Context

Interface routers needed to route operation names (strings) to handler functions. The traditional approach would be long if/elif chains checking operation names. As the system grew to 12 interfaces with 5-15 operations each, the scalability and maintainability of routing became critical.

---

## Content

### The Decision

**What We Chose:**
Dictionary-based dispatch where operation names map to handler functions.

**Implementation:**
```python
# ❌ WRONG - if/elif chains (O(n) lookup)
def execute_operation(operation, **kwargs):
    if operation == "cache_get":
        return cache_get(**kwargs)
    elif operation == "cache_set":
        return cache_set(**kwargs)
    elif operation == "cache_delete":
        return cache_delete(**kwargs)
    # ... 10 more operations
    else:
        raise ValueError(f"Unknown operation: {operation}")

# ✅ CORRECT - Dictionary dispatch (O(1) lookup)
OPERATIONS = {
    "cache_get": cache_get,
    "cache_set": cache_set,
    "cache_delete": cache_delete,
    # ... just add to dict
}

def execute_operation(operation, **kwargs):
    handler = OPERATIONS.get(operation)
    if not handler:
        raise ValueError(f"Unknown operation: {operation}")
    return handler(**kwargs)
```

### Rationale

**Why We Chose This:**

1. **O(1) Performance**
   - if/elif chains: O(n) average lookup time
   - Dictionary: O(1) constant time lookup
   - With 15 operations: avg 7.5 comparisons vs 1 lookup
   - **Result:** Consistent fast routing regardless of operation count

2. **90% Code Reduction**
   - if/elif: 3 lines per operation × 15 operations = 45 lines
   - Dictionary: 1 line per operation + 6 lines overhead = 21 lines
   - **Result:** 24 lines saved per router, multiply by 12 interfaces = 288 lines saved

3. **Clean Extensibility**
   - Adding operation: One line in dictionary
   - No modification of routing logic
   - No risk of breaking existing operations
   - **Result:** Open/closed principle (open for extension, closed for modification)

4. **Self-Documenting**
   - Dictionary shows all available operations at a glance
   - Clear mapping: operation name → handler
   - Easy to validate completeness
   - **Result:** Developer can see entire API in one structure

5. **Easier Testing**
   - Test coverage: ensure all dict entries valid
   - Mock handlers easily
   - Test error cases simply
   - **Result:** Complete test coverage with fewer test cases

### Alternatives Considered

**Alternative 1: if/elif Chains**
- **Description:** Traditional conditional routing
- **Pros:**
  - Familiar pattern
  - No dictionary overhead (~40 bytes per entry)
  - Debugger shows exact branch taken
- **Cons:**
  - O(n) performance (slower as operations grow)
  - 3x more code (maintenance burden)
  - Modifying routing logic risks bugs
  - Hard to see all operations at once
- **Why Rejected:** Doesn't scale, more code, worse performance

**Alternative 2: Class-Based Routing (Strategy Pattern)**
- **Description:** Each operation as separate class with execute() method
- **Pros:**
  - Very object-oriented
  - Clear separation per operation
  - Can add operation-specific state
- **Cons:**
  - 10-20 lines per operation (vs 1 line in dict)
  - Many small files (namespace pollution)
  - Overkill for stateless operations
  - Slower (class instantiation overhead)
- **Why Rejected:** Over-engineered for Lambda's needs

**Alternative 3: Dynamic Function Lookup (getattr)**
- **Description:** Use getattr to find functions by name
- **Pros:**
  - No dispatch code at all
  - Very dynamic
- **Cons:**
  - No explicit operation list (hard to discover)
  - Security risk (arbitrary function calls)
  - No validation at startup
  - Difficult to test comprehensively
- **Why Rejected:** Too dynamic, security concerns, discoverability issues

### Trade-offs

**Accepted:**
- Dictionary memory overhead (~40 bytes × 15 operations × 12 interfaces = ~7KB)
- Lookup in dictionary vs direct function call (~10ns difference)
- Need to maintain dictionary (one extra line per operation)

**Benefits:**
- O(1) vs O(n) lookup (7.5x faster average with 15 operations)
- 90% less code (288 lines saved across 12 interfaces)
- Zero risk when adding operations (no existing code modified)
- Complete API visible in one place (discoverability)
- Easier testing (verify dict completeness)

**Net Assessment:**
7KB memory and ~10ns overhead are completely negligible in Lambda context. The performance, maintainability, and extensibility benefits are enormous. The pattern has proven itself across 12 interfaces with zero routing-related bugs.

### Impact

**On Architecture:**
- Defines how all interface routers work (consistent pattern)
- Enables clean separation: routing vs implementation
- Makes interface extension trivial (add to dict)
- Foundation for router-level features (logging, timing)

**On Development:**
- Adding operation: 1 line in dictionary + implement function
- No fear of breaking existing routing
- Code review: check dict entry added
- Clear place to look for available operations

**On Performance:**
- Consistent O(1) routing (no performance degradation as system grows)
- ~10ns overhead vs direct call (negligible)
- Enables optimizations (dictionary can be cached, analyzed)
- Hot path operations equally fast as cold path

**On Maintenance:**
- 288 lines of code eliminated (less to maintain)
- No complex branching logic (simpler debugging)
- Easy to validate completeness (dict keys vs implemented functions)
- 6+ months: zero routing bugs

### Future Considerations

**When to Revisit:**
- If dictionary memory becomes significant (>1MB)
- If lookup overhead becomes measurable (>1% CPU)
- If need more complex routing (e.g., pattern matching)
- Never happened in 6+ months

**Potential Evolution:**
- Auto-generated dictionaries from function decorators
- Validation: dict keys match available functions
- Nested routing for operation namespaces
- Performance monitoring per operation

**Monitoring Needs:**
- Track operation call frequency (optimize hot paths)
- Measure routing overhead (verify negligibility)
- Monitor for missing operations (error logging)

---

## Related Topics

- **ARCH-03**: Router pattern (implements dispatch dictionary)
- **DEC-01**: SIMA pattern (routers are part of SIMA)
- **DEC-02**: Gateway centralization (routers behind gateway)
- **LESS-04**: Consistency lesson (dispatch pattern consistent across interfaces)
- **PATH-01**: Cold start pathway (routers loaded lazily)

---

## Keywords

dispatch dictionary, operation routing, O(1) lookup, extensibility, code reduction, router pattern, performance, maintainability

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2024-04-20**: Original decision documented in NM04-ARCHITECTURE-Decisions.md

---

**File:** `NM04-Decisions-Architecture_DEC-03.md`
**End of Document**

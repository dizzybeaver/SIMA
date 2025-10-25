# NM04-Decisions-Architecture_DEC-04.md - DEC-04

# DEC-04: No Threading Locks

**Category:** Decisions
**Topic:** Architecture
**Priority:** 🔴 Critical
**Status:** Active
**Date Decided:** 2024-04-18
**Created:** 2025-10-23
**Last Updated:** 2025-10-23

---

## Summary

Lambda functions run single-threaded, so threading locks (threading.Lock, threading.RLock, etc.) are unnecessary overhead and should never be used.

---

## Context

During development, some developers familiar with multi-threaded environments considered adding locks to protect shared state (caches, configuration, etc.). This would have been cargo-cult programming - copying patterns from other contexts without understanding Lambda's execution model.

---

## Content

### The Decision

**What We Chose:**
**Zero threading primitives.** No locks, no RLocks, no Semaphores, no threading.Lock() anywhere in the codebase.

**Implementation:**
```python
# ❌ WRONG - Locks in Lambda (unnecessary overhead)
import threading

_cache = {}
_cache_lock = threading.Lock()

def cache_get(key):
    with _cache_lock:  # Unnecessary!
        return _cache.get(key)

# ✅ CORRECT - No locks needed
_cache = {}

def cache_get(key):
    return _cache.get(key)  # Safe - Lambda is single-threaded
```

### Rationale

**Why We Chose This:**

1. **Lambda is Single-Threaded**
   - AWS Lambda executes one request at a time per container
   - No concurrent execution within a single container
   - Multiple requests = multiple containers (separate memory spaces)
   - **Result:** Locks protect against a problem that doesn't exist

2. **Performance**
   - Lock acquisition: ~500-1000ns overhead per operation
   - On hot path operations (cache get): 50-100% slowdown
   - Multiply by thousands of operations: seconds wasted
   - **Result:** Pure overhead with zero benefit

3. **Simplicity**
   - No lock contention to reason about
   - No deadlock possibilities
   - No lock ordering concerns
   - Simpler code = fewer bugs
   - **Result:** Code is easier to understand and maintain

4. **Correctness**
   - Locks give false sense of security
   - Real concurrency issues exist at container level, not request level
   - Locks can't solve cross-container problems anyway
   - **Result:** Locks wouldn't actually help with real concurrency

5. **YAGNI Principle (You Ain't Gonna Need It)**
   - Don't add what you don't need
   - Lambda model won't change to multi-threaded
   - If it did, we'd redesign (locks alone wouldn't be enough)
   - **Result:** Follow Lambda's execution model, don't fight it

### Alternatives Considered

**Alternative 1: Add Locks "Just in Case"**
- **Description:** Use locks defensively even though not needed
- **Pros:**
  - "Safe" feeling (psychological comfort)
  - Works if Lambda ever becomes multi-threaded (unlikely)
- **Cons:**
  - ~1000ns overhead per operation (measurable cost)
  - False security (doesn't solve real concurrency issues)
  - More complex code (lock handling, error cases)
  - Sets bad precedent (cargo-cult programming)
- **Why Rejected:** All cost, no benefit; violates YAGNI

**Alternative 2: Lock Only Critical Sections**
- **Description:** Use locks for "important" shared state only
- **Pros:**
  - Less overhead than locking everything
  - Shows which sections deemed critical
- **Cons:**
  - Still unnecessary overhead
  - Inconsistent (why lock this but not that?)
  - Developer confusion (when to lock?)
  - Doesn't solve any actual problem
- **Why Rejected:** Partial application of unnecessary pattern

**Alternative 3: Use threading.local() for State**
- **Description:** Thread-local storage for per-request state
- **Pros:**
  - Handles multi-threading if it existed
  - Common pattern in web frameworks
- **Cons:**
  - Unnecessary in single-threaded context
  - Lambda already isolates requests via containers
  - More complex than simple globals
  - Overhead of thread-local access
- **Why Rejected:** Solving problem that doesn't exist

### Trade-offs

**Accepted:**
- No protection if Lambda execution model changes (extremely unlikely)
- Must rely on Lambda's execution model (acceptable AWS dependency)
- Could confuse developers from multi-threaded backgrounds (education needed)

**Benefits:**
- ~1000ns saved per operation (significant on hot paths)
- Simpler code (no lock management, acquisition, release)
- No deadlock possibilities (one less category of bugs)
- No lock contention (one less performance issue)
- Code reflects Lambda reality (honest implementation)

**Net Assessment:**
Lambda's single-threaded execution model is fundamental and won't change. Using locks would be pure cargo-cult programming with measurable cost and zero benefit. The decision to avoid locks has held up for 6+ months with zero issues.

### Impact

**On Architecture:**
- System designed assuming single-threaded execution
- Shared state is safe without protection
- No need for concurrent data structures
- Simpler architectural patterns

**On Development:**
- Developers don't waste time on lock management
- Code reviews check: "Why is there a lock?"
- New developers learn: "Lambda is single-threaded"
- Clear rule: "No threading primitives"

**On Performance:**
- Hot operations ~1000ns faster (cache get: 1ms → 1ms)
- No lock contention delays
- No deadlock debugging
- Cleaner profiling data (no lock waiting)

**On Maintenance:**
- Simpler code (no lock error handling)
- Fewer bug categories (no deadlocks)
- Easier debugging (no lock states to inspect)
- 6+ months: zero concurrency bugs

### Future Considerations

**When to Revisit:**
- If Lambda introduces multi-threaded execution (AWS would announce this)
- If we move code to non-Lambda environment (would require broader redesign)
- Never happened, likely never will

**Potential Evolution:**
- Document Lambda execution model (for new developers)
- Linting rule to prevent lock introduction
- Code review checklist: "No locks added"

**Monitoring Needs:**
- None - Lambda execution model is AWS responsibility
- If ever needed: AWS would provide migration path

---

## Related Topics

- **AP-08**: Threading primitives anti-pattern (enforces this decision)
- **LESS-04**: Consistency lesson (mentions single-threaded model)
- **DEC-01**: SIMA pattern (simplified by single-threaded execution)
- **DEC-03**: Dispatch dictionary (no lock needed for dispatch)

---

## Keywords

no threading locks, single-threaded, Lambda execution model, YAGNI principle, performance, simplicity, cargo-cult programming, concurrency

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2024-04-18**: Original decision documented in NM04-ARCHITECTURE-Decisions.md

---

**File:** `NM04-Decisions-Architecture_DEC-04.md`
**End of Document**

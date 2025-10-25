# NM05-AntiPatterns-Concurrency_Index.md

# Anti-Patterns - Concurrency Index

**Category:** NM05 - Anti-Patterns
**Topic:** Concurrency
**Items:** 3
**Last Updated:** 2025-10-23

---

## Topic Overview

**Description:** Concurrency and performance anti-patterns that either don't work in Lambda's single-threaded environment (threading primitives) or create performance problems (blocking operations, inefficient patterns). Lambda is fundamentally single-threaded, making threading/async primitives unnecessary and harmful.

**Keywords:** threading, concurrency, asyncio, performance, blocking operations, Lambda constraints

---

## Individual Files

### AP-08: Using Threading/Asyncio Primitives
- **File:** `NM05-AntiPatterns-Concurrency_AP-08.md`
- **Summary:** Using threading locks, semaphores, or asyncio in Lambda
- **Severity:** 🟡 High
- **What to do instead:** Remove threading primitives; Lambda is single-threaded
- **Why high:** Adds overhead without benefit, violates Lambda model

### AP-11: Synchronous Network Loops
- **File:** `NM05-AntiPatterns-Concurrency_AP-11.md`
- **Summary:** Sequential network calls that should be parallel/batched
- **Severity:** 🟢 Medium
- **What to do instead:** Batch requests or use parallel invocations
- **Why medium:** Works but slow; acceptable for small sets

### AP-13: String Concatenation in Loops
- **File:** `NM05-AntiPatterns-Concurrency_AP-13.md`
- **Summary:** Building strings with += in loops (O(n²) performance)
- **Severity:** ⚪ Low
- **What to do instead:** Use list.append() + join()
- **Why low:** Only matters for large loops (100+ iterations)

---

## Common Themes

**Lambda execution model:**
- Single-threaded environment
- No benefit from threading primitives
- Locks add overhead without concurrency protection
- Async/await doesn't provide parallelism

**Performance considerations:**
- Blocking operations are the real enemy
- Batching is better than parallelism
- Algorithmic efficiency matters more than concurrency
- Measure before optimizing (LESS-02)

---

## Related Topics

- **DEC-04**: No threading locks (the fundamental decision)
- **LESS-06**: Lambda single-threaded (lesson from testing)
- **PATH-01**: Cold start pathway (performance critical path)
- **LESS-02**: Measure don't guess (profile before optimizing)
- **ARCH-07**: LMMS (lazy loading for performance)

---

## Critical Distinction

**AP-08 (Threading) vs AP-11 (Blocking)**

**AP-08:** Adding threading primitives ❌
- Problem: Overhead without benefit
- Lambda is single-threaded by design
- Locks, semaphores, asyncio don't help

**AP-11:** Sequential blocking operations ⚠️
- Problem: Slow but works
- May be acceptable for 2-3 operations
- Consider batching for > 5 operations

**Key insight:** Lambda being single-threaded doesn't mean you can't optimize blocking I/O. Batching API calls is different from threading.

---

## Detection & Prevention

**How to detect concurrency anti-patterns:**
```bash
# Find threading primitives (AP-08)
grep -r "threading\." *.py
grep -r "asyncio\." *.py
grep -r "Lock()" *.py

# Find string concatenation in loops (AP-13)
grep -r "+=.*str" *.py | grep "for\|while"

# Find sequential network calls (AP-11) - manual review
# Look for: multiple http requests in sequence
```

**Prevention checklist:**
- [ ] No threading.Lock, threading.Semaphore
- [ ] No asyncio event loops in Lambda handlers
- [ ] Batch API calls when possible (> 5 calls)
- [ ] Use join() for string building in loops
- [ ] Profile before optimizing (LESS-02)

---

**Navigation:**
- **Up:** NM05-AntiPatterns_Index.md
- **Sibling Topics:** Import, ErrorHandling, Design

---

**End of Index**

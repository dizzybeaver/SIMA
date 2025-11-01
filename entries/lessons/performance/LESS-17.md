# File: LESS-17.md

**REF-ID:** LESS-17  
**Category:** Lessons Learned  
**Topic:** Performance  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Status:** Production

---

## Title

Threading Locks Unnecessary in Single-Threaded Environments

---

## Priority

HIGH

---

## Summary

In single-threaded execution environments (like serverless functions), threading locks add overhead (~50ns per operation) without benefit and add unnecessary complexity.

---

## Context

During optimization work, discovered threading locks were being used unnecessarily in a single-threaded execution environment. The locks provided no benefit but added measurable overhead.

---

## Lesson

### The Problem

**Unnecessary threading locks:**
```python
# WRONG - Unnecessary in single-threaded execution
import threading

class MetricsCore:
    def __init__(self):
        self._lock = threading.Lock()  # Unnecessary!
        self._metrics = {}
    
    def record_metric(self, name, value):
        with self._lock:  # Adds 50ns overhead for no benefit
            self._metrics[name] = value
```

**Why this is wrong:**
1. Single-threaded execution - one request at a time
2. No concurrent requests - serialized by runtime
3. Performance cost - lock adds ~50ns overhead per operation
4. Compliance violation - violates AP-08 anti-pattern
5. Memory waste - lock object consumes ~500 bytes

### Single-Threaded Execution Model

**Key facts:**
- One request per execution context at a time
- Requests serialized by runtime/framework
- No concurrent access to same memory
- Each invocation = single thread

**This means:**
- No race conditions possible
- No need for synchronization
- Locks provide zero benefit
- Locks only add overhead

### The Solution

```python
# CORRECT - No threading needed
class MetricsCore:
    def __init__(self):
        # No lock needed!
        self._metrics = {}
    
    def record_metric(self, name, value):
        # Direct access - no lock overhead
        self._metrics[name] = value
```

**Benefits:**
- 50ns faster per operation
- 500 bytes less memory
- Simpler code
- No threading complexity

### Performance Impact

**Measurement:**
```
With lock:    ~150ns per record_metric()
Without lock: ~100ns per record_metric()
Improvement:  33% faster

Over 10,000 operations: 500Î¼s saved
```

### When Locks ARE Needed

**Locks are needed when:**
- Multi-threaded application
- Shared memory across threads
- Concurrent write access
- Traditional web servers with thread pools

**Locks are NOT needed when:**
- Single-threaded execution
- Sequential request handling
- No concurrent access
- Serverless functions
- Event-driven architectures

### Compliance

**Violations:**
- AP-08: Using threading primitives unnecessarily

**Fix:**
- Remove all threading imports
- Remove all Lock(), RLock(), Semaphore()
- Remove all with lock: statements

---

## Related

**Cross-References:**
- AP-08: Threading primitives anti-pattern
- DEC-04: Single-threaded execution decision
- LESS-06: Pay small costs early (locks cost 50ns each)

**Keywords:** threading, locks, single-threaded, performance, serverless, event-driven

---

## Version History

- **4.0.0** (2025-10-30): Genericized for SIMAv4
- **3.0.0** (2025-10-23): Initial SIMAv3 format

---

**END OF FILE**

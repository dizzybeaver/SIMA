# NM05-AntiPatterns-Concurrency_AP-08.md - AP-08

# AP-08: Using Threading/Asyncio Primitives

**Category:** Anti-Patterns
**Topic:** Concurrency
**Severity:** 🟡 High
**Status:** Active
**Created:** 2024-10-15
**Last Updated:** 2025-10-23

---

## Summary

Using threading primitives (Lock, Semaphore, Queue) or asyncio event loops in Lambda code. Lambda is fundamentally single-threaded, making all concurrency primitives unnecessary and harmful.

---

## The Anti-Pattern

**What NOT to do:**
```python
# ❌ WRONG - Threading primitives in Lambda
import threading

# Global lock (unnecessary in single-threaded environment!)
_cache_lock = threading.Lock()

def cache_operation(key, value):
    with _cache_lock:  # Adds overhead for no benefit
        _cache[key] = value
    return value

# ❌ WRONG - Asyncio in Lambda
import asyncio

async def async_operation():
    await asyncio.sleep(1)
    return "done"

def lambda_handler(event, context):
    loop = asyncio.get_event_loop()
    result = loop.run_until_complete(async_operation())
    return result
```

**Why it's bad:**
1. **No Parallelism**: Lambda handles one request at a time in single thread
2. **Pure Overhead**: Locks add ~1-5µs overhead with zero benefit
3. **False Security**: Gives illusion of thread-safety that's not needed
4. **Code Complexity**: Harder to read, maintain, debug
5. **Violates Lambda Model**: Misunderstands Lambda execution environment

---

## What to Do Instead

**Correct approach - No concurrency primitives:**
```python
# ✅ CORRECT - Direct operations (Lambda is single-threaded)

# No lock needed - only one thread accessing this
_cache = {}

def cache_operation(key, value):
    _cache[key] = value  # Direct access - perfectly safe!
    return value

# ✅ CORRECT - Synchronous operations
import time

def sync_operation():
    time.sleep(1)  # If you need delay (though rare in Lambda)
    return "done"

def lambda_handler(event, context):
    result = sync_operation()
    return result
```

**Why this is better:**
- Simpler code, easier to understand
- No unnecessary overhead
- Reflects actual Lambda execution model
- Faster (no lock acquisition cost)
- Clearer that operations are sequential

---

## Real-World Example

**Context:** Developer with threading background assumed concurrency needed

**Problem:**
```python
# In cache_core.py
import threading

_cache = {}
_lock = threading.Lock()

def get_value(key):
    with _lock:  # Unnecessary overhead
        return _cache.get(key, _CACHE_MISS)

def set_value(key, value, ttl=None):
    with _lock:  # Adds 2-3µs per call
        _cache[key] = value
```

**Measured impact:**
- **Before** (with locks): 8µs per cache operation
- **After** (without locks): 5µs per cache operation
- **Overhead**: 37.5% slower for no benefit!
- **For 100 cache operations**: 300µs wasted

**Solution:**
```python
# In cache_core.py
_cache = {}  # No lock needed!

def get_value(key):
    return _cache.get(key, _CACHE_MISS)  # Direct access

def set_value(key, value, ttl=None):
    _cache[key] = value  # Simple and fast
```

**Result:**
- 37.5% faster cache operations
- Simpler code
- Same safety (Lambda is single-threaded)

---

## Lambda Execution Model

**Key facts about Lambda:**
```
One Lambda Container:
  ↓
One Python Process:
  ↓
One Python Interpreter:
  ↓
One Thread:
  ↓
Handles ONE Request at a Time
```

**Concurrency in Lambda happens via:**
- Multiple Lambda containers (AWS manages)
- Each container: one request at a time
- No shared memory between containers
- No race conditions within a container

**Result:** Threading primitives are 100% unnecessary.

---

## When Developers Think They Need Threading

**Myth 1: "Need lock to protect shared dict"**
- Reality: Lambda is single-threaded, no concurrent access
- Solution: Direct dict access is safe

**Myth 2: "Need asyncio for parallel network calls"**
- Reality: Lambda can only execute one thing at a time
- Solution: Batch requests or use parallel Lambda invocations

**Myth 3: "Need Queue for thread communication"**
- Reality: No multiple threads to communicate between
- Solution: Use simple list or pass data directly

**Myth 4: "Need Semaphore to limit concurrent operations"**
- Reality: Only one operation executes at a time
- Solution: Not needed, operations already sequential

---

## The Only Exception

**IF you're using a library that internally uses threading:**
```python
# Some third-party libraries use threading internally
import requests  # Uses threading in connection pool

# This is OK - library manages its own threads
response = requests.get(url)
```

**But YOU should never create threads or use threading primitives in your code.**

---

## How to Identify

**Code smells:**
- `import threading`
- `import asyncio` (in Lambda handlers)
- `Lock()`, `Semaphore()`, `Queue()`
- `async def` functions in Lambda entry point
- Comments about "thread safety" or "race conditions"

**Detection:**
```bash
# Find threading imports
grep -r "import threading" *.py

# Find asyncio in handlers
grep -r "import asyncio" *.py

# Find Lock usage
grep -r "\.Lock()" *.py

# Should return zero results in Lambda code!
```

---

## Common Arguments (and Rebuttals)

**"But what if Lambda adds threading in future?"**
- Then update code then
- Current code should match current environment
- Don't add complexity for hypothetical future

**"Locks don't hurt anything"**
- They add measurable overhead (2-5µs per lock)
- They add code complexity
- They mislead other developers about execution model

**"I learned to always protect shared state"**
- Good practice in multi-threaded environments
- Lambda is NOT multi-threaded
- Different environments, different patterns

---

## Related Topics

- **DEC-04**: No threading locks (the design decision)
- **LESS-06**: Lambda single-threaded (verified by testing)
- **PATH-01**: Cold start pathway (threading adds to cold start)
- **AP-11**: Synchronous network loops (real concurrency issue)
- **LESS-02**: Measure don't guess (how we discovered overhead)

---

## Keywords

threading, asyncio, locks, semaphores, single-threaded, Lambda execution model, concurrency

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2024-10-15**: Anti-pattern documented in NM05-Anti-Patterns_Part_1.md

---

**File:** `NM05-AntiPatterns-Concurrency_AP-08.md`
**End of Document**

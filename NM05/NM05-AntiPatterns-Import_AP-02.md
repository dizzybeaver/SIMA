# NM05-AntiPatterns-Import_AP-02.md - AP-02

# AP-02: Importing Interface Routers

**Category:** Anti-Patterns
**Topic:** Import
**Severity:** 🔴 Critical
**Status:** Active
**Created:** 2024-10-15
**Last Updated:** 2025-10-23

---

## Summary

Importing interface router files (interface_*.py) directly instead of using gateway functions. Interface routers are internal routing mechanisms, not public APIs.

---

## The Anti-Pattern

**What NOT to do:**
```python
# ❌ WRONG - Importing interface routers directly
from interface_cache import cache_get, cache_set
from interface_logging import log_info
from interface_security import hash_data

def my_function():
    cache_get("key")
    log_info("Processing")
```

**Why it's bad:**
1. **Bypasses Gateway Layer**: Interface routers are meant to be called BY gateway, not directly
2. **No Error Handling**: Gateway adds consistent error handling that routers don't have
3. **No Metrics**: Gateway tracks metrics for all operations
4. **Architecture Violation**: Breaks the three-layer pattern (Gateway → Interface → Implementation)
5. **Future Breaking Changes**: Interface routers can change structure without notice

---

## What to Do Instead

**Correct approach:**
```python
# ✅ CORRECT - Use gateway functions
import gateway

def my_function():
    gateway.cache_get("key")
    gateway.log_info("Processing")
```

**Why this is better:**
- Gateway provides stable public API
- Consistent error handling across all operations
- Automatic metrics collection
- Gateway can optimize routing internally
- Your code doesn't break when router structure changes

---

## Real-World Example

**Context:** Developer thought interface files were the public API

**Problem:**
```python
# In custom module
from interface_cache import cache_get
from interface_logging import log_info

# Code worked initially...
cache_get("mykey")
log_info("Started")
```

**What went wrong:**
- Interface routers changed internal structure
- Functions moved to gateway_wrappers
- All direct interface imports broke
- Had to update 15 files manually

**Solution:**
```python
# In custom module
import gateway  # Only one import!

gateway.cache_get("mykey")
gateway.log_info("Started")
```

**Result:**
- Survived interface refactoring without changes
- Gained error handling automatically
- Code more maintainable

---

## The Three-Layer Pattern

**Correct flow:**
```
Your Code
    ↓
gateway.cache_get()
    ↓
interface_cache.cache_get()  ← Gateway calls this
    ↓
cache_core.get_value()
```

**Wrong flow:**
```
Your Code
    ↓
interface_cache.cache_get()  ← Skipping gateway!
    ↓
cache_core.get_value()
```

**Why the gateway layer exists:**
- Consistent error handling
- Metrics collection
- Request validation
- Future optimization opportunities
- Stable public API

---

## How to Identify

**Code smells:**
- `from interface_* import` statements
- Direct calls to interface router functions
- Code that breaks when interface structure changes

**Detection:**
```bash
# Find interface router imports
grep -r "from interface_.* import" *.py

# Should return zero results in application code
```

**Quick test:**
If you see `interface_` in your import statements, you're doing it wrong.

---

## Exception: Gateway Internal Code

**Only exception:**
```python
# ✅ ALLOWED - Inside gateway.py or gateway_wrappers.py
# In gateway_wrappers_cache.py
from interface_cache import cache_get as _cache_get

def cache_get(key, ttl=None):
    """Gateway wrapper for cache operations."""
    # Add gateway-level logic
    return _cache_get(key, ttl)
```

**Rule:** Only gateway-layer code should import interface routers. Application code never should.

---

## Related Topics

- **ARCH-01**: Gateway trinity (three-layer architecture)
- **DEC-02**: Gateway centralization (why gateway exists)
- **RULE-01**: Gateway-only imports (the rule)
- **AP-01**: Direct cross-interface imports (related violation)
- **NM01-CORE**: Architecture patterns (correct structure)

---

## Keywords

interface routers, direct imports, gateway bypass, architecture violation, three-layer pattern

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2024-10-15**: Anti-pattern documented in NM05-Anti-Patterns_Part_1.md

---

**File:** `NM05-AntiPatterns-Import_AP-02.md`
**End of Document**

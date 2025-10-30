# NM05-AntiPatterns-Import_AP-01.md - AP-01

# AP-01: Direct Cross-Interface Imports

**Category:** Anti-Patterns
**Topic:** Import
**Severity:** 🔴 Critical
**Status:** Active
**Created:** 2024-10-15
**Last Updated:** 2025-10-23

---

## Summary

Importing core modules directly across interface boundaries instead of using the gateway pattern. This is the most fundamental architectural violation in the SUGA-ISP project.

---

## The Anti-Pattern

**What NOT to do:**
```python
# ❌ WRONG - Direct cross-interface import
from cache_core import get_value, set_value
from logging_core import log_message, log_error
from security_core import hash_data

def process_request(data):
    # Using direct imports
    cached = get_value("key")
    log_message("Processing...")
    hashed = hash_data(data)
    return hashed
```

**Why it's bad:**
1. **Violates SUGA Architecture**: Breaks the Single Universal Gateway Architecture pattern that prevents circular dependencies
2. **Circular Import Risk**: Direct imports create dependency webs that can cause import loops
3. **No Central Control**: Gateway provides unified error handling, metrics, and validation
4. **Maintenance Nightmare**: Changes to core modules require updating all direct importers
5. **Testing Difficulty**: Cannot mock at gateway level, must mock each core import

---

## What to Do Instead

**Correct approach:**
```python
# ✅ CORRECT - Import via gateway
import gateway

def process_request(data):
    # All operations through gateway
    cached = gateway.cache_get("key")
    gateway.log_info("Processing...")
    hashed = gateway.security_hash(data)
    return hashed
```

**Why this is better:**
- Single import statement for all operations
- Gateway handles error cases uniformly
- Easy to mock for testing (mock `gateway`)
- Prevents circular dependencies by design
- Consistent interface across entire codebase

---

## Real-World Example

**Context:** Early development before SUGA pattern was established

**Problem:**
```python
# In http_client_core.py
from cache_core import get_value  # Direct import!
from logging_core import log_error

def make_request(url):
    cached = get_value(url)
    if cached:
        return cached
    # Make request...
```

**What went wrong:**
- Created circular dependency: `http_client_core` → `cache_core` → `logging_core` → (back to http)
- Import errors during initialization
- Had to manually resolve import order
- Added 2 hours of debugging time

**Solution:**
```python
# In http_client_core.py
import gateway  # Gateway only!

def make_request(url):
    cached = gateway.cache_get(url)
    if cached:
        return cached
    # Make request...
```

**Result:**
- No circular dependencies
- Clean initialization order
- Code works immediately

---

## How to Identify

**Code smells:**
- `from *_core import` statements across interface boundaries
- Multiple core module imports in a single file
- Import errors during module loading
- Mysterious "partially initialized module" errors

**Detection:**
```bash
# Find direct cross-interface imports
grep -r "from .*_core import" *.py | grep -v "# Intra-interface"

# Should return zero results (except within same interface)
```

**Linting rule:**
```python
# In .pylintrc or custom checker
# Flag: from <interface>_core import (when not in same interface)
```

---

## Exception: Intra-Interface Imports

**Important distinction:**
```python
# ✅ ALLOWED - Within same interface
# In cache_helper.py (part of CACHE interface)
from cache_core import _get_cache_store  # Same interface, OK

# ❌ NOT ALLOWED - Across interfaces
# In http_client_core.py (HTTP interface)
from cache_core import get_value  # Different interface, use gateway!
```

**Rule:** Direct imports allowed ONLY within the same interface (same functional domain).

---

## Related Topics

- **DEC-01**: SUGA pattern choice (why we use gateway)
- **DEC-02**: Gateway centralization (architectural decision)
- **RULE-01**: Gateway-only imports (the fundamental rule)
- **AP-02**: Importing interface routers (related violation)
- **AP-04**: Circular imports (what this anti-pattern causes)
- **NM02-CORE**: Dependency layers (correct structure)

---

## Keywords

direct imports, cross-interface, circular dependencies, gateway pattern, SUGA violation, architecture

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2024-10-15**: Anti-pattern documented in NM05-Anti-Patterns_Part_1.md

---

**File:** `NM05-AntiPatterns-Import_AP-01.md`
**End of Document**

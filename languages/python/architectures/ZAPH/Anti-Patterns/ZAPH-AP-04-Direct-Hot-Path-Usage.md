# File: ZAPH-AP-04-Direct-Hot-Path-Usage.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Category:** Python/Architectures/ZAPH/Anti-Patterns  
**Status:** Active

---

## ANTI-PATTERN: Direct Usage of Tier 1 Hot Paths Without Wrappers

**REF-ID:** ZAPH-AP-04  
**Severity:** Critical (Production Incidents)  
**Frequency:** Common without wrapper enforcement  
**Detection:** Hot-path functions called directly in application code

---

## DESCRIPTION

Calling zero-abstraction Tier 1 hot-path functions directly from application code without using safe Tier 2 wrappers. Hot paths lack validation and error handling by design, causing production incidents when called with invalid inputs.

---

## SYMPTOMS

**Code Indicators:**
```python
# ❌ Direct hot-path usage in application code
def handle_request(request):
    """Anti-pattern: Direct hot-path call without wrapper."""
    user_id = request.get("user_id")  # Could be None, int, etc.
    
    # ❌ Direct call to Tier 1 (no validation!)
    data = cache_get_hot(user_id)  # Crashes if user_id invalid
    
    return process(data)

# ❌ Hot path exposed as public API
from cache import cache_get_hot  # Direct import
result = cache_get_hot(key)       # No safety net
```

**Production Indicators:**
- TypeError from wrong argument types
- KeyError from None or empty arguments
- AttributeError from unexpected object types
- 500 errors without clear cause
- Difficult debugging (no logging in hot path)

**Process Indicators:**
- Developers unaware of Tier 1 vs Tier 2 distinction
- Hot-path functions not marked "internal use only"
- No wrapper functions provided
- Code reviews miss direct hot-path usage
- Documentation doesn't emphasize wrapper usage

---

## WHY IT HAPPENS

**Root Causes:**

1. **Unclear API Boundaries**
   - Hot-path functions appear "public" (no leading underscore)
   - Not marked "internal" or "advanced use only"
   - Wrapper functions not obvious
   - Developers assume all functions are safe to use

2. **Missing Documentation**
   - Hot-path warnings not prominent
   - Wrapper functions not documented as "primary API"
   - No examples showing correct usage
   - Dangers of direct usage not explained

3. **Developer Convenience**
   - Hot-path function shorter to type
   - Wrapper seems "unnecessary" abstraction
   - Don't understand performance vs safety trade-off
   - "It worked in my tests" (edge cases missed)

4. **No Enforcement**
   - Linters don't flag direct hot-path usage
   - Code reviews miss pattern
   - No automated detection
   - Team conventions not established

---

## WHY IT'S WRONG

**Production Incidents:**
```
Incident 1: user_id was None
- Direct call: cache_get_hot(None)
- Result: KeyError, 500 error, 30 min outage
- Should have used: cache_get_safe(None, default={})
- Safe wrapper returns default, no crash

Incident 2: user_id was integer
- Direct call: cache_get_hot(123)
- Result: TypeError, 500 error, 15 min outage
- Should have used: cache_get_safe(123) → validates type → returns None
- Safe wrapper handles type checking

Incident 3: user_id was empty string
- Direct call: cache_get_hot("")
- Result: KeyError, 500 error, 45 min outage
- Should have used: cache_get_safe("", default=None)
- Safe wrapper validates non-empty
```

**Debugging Difficulty:**
- Hot paths have no logging
- No error context (where/why failed)
- Stack traces cryptic (deep in hot path)
- Long incident resolution times

**Lost Performance Benefits:**
- Hot path exists for performance
- Direct usage without validation ruins reliability
- Incidents cause downtime (lost performance AND availability)

---

## CORRECT APPROACH

### Pattern 1: Wrapper as Primary API

```python
# Tier 1: Hot path (internal use only)
def cache_get_hot(key):
    """Tier 1 HOT PATH - INTERNAL USE ONLY.
    
    DO NOT CALL DIRECTLY. Use cache_get() wrapper instead.
    
    This function has zero abstraction for performance:
    - No validation
    - No error handling
    - No logging
    
    Caller must ensure:
    - key is str
    - key is not empty
    - errors handled externally
    """
    return _cache.get(key)

# Tier 2: Safe wrapper (primary API)
def cache_get(key, default=None):
    """Safe cache access - PRIMARY API.
    
    This is what you should use in application code.
    Handles validation, errors, and logging.
    
    Args:
        key: Cache key (any type, will be validated)
        default: Value if key not found or invalid
        
    Returns:
        Cached value or default
        
    Example:
        user = cache_get(f"user:{user_id}", default={})
    """
    # Validation
    if not key or not isinstance(key, str):
        if LOGGING_ENABLED:
            log_warning(f"Invalid cache key: {key}")
        return default
    
    try:
        # Call hot path with validated input
        value = cache_get_hot(key)
        
        if LOGGING_ENABLED:
            log_cache_access(key, hit=value is not None)
        
        return value if value is not None else default
        
    except Exception as e:
        log_error(f"Cache error for key {key}: {e}")
        return default

# Application code uses wrapper
def handle_request(request):
    """Correct: Uses safe wrapper."""
    user_id = request.get("user_id")
    
    # ✅ Safe wrapper handles all edge cases
    data = cache_get(f"user:{user_id}", default={})
    
    return process(data)
```

### Pattern 2: Private Hot Path

```python
# Tier 1: Private (leading underscore)
def _cache_get_hot(key):
    """Private hot path - not for direct use.
    
    Internal implementation. Use public cache_get() instead.
    """
    return _cache.get(key)

# Tier 2: Public wrapper
def cache_get(key, default=None):
    """Public API - safe to use anywhere."""
    if not key or not isinstance(key, str):
        return default
    
    try:
        return _cache_get_hot(key)
    except Exception as e:
        log_error(e)
        return default
```

### Pattern 3: Module Organization

```python
# hot_paths.py (internal module)
"""Internal hot paths - NOT FOR DIRECT USE.

These functions sacrifice safety for performance.
Use the wrappers in cache_api.py instead.
"""

def cache_get_hot(key):
    """HOT PATH: No validation, no logging."""
    return _cache.get(key)

# cache_api.py (public module)
"""Public cache API - USE THESE FUNCTIONS.

Safe wrappers around hot paths with full validation.
"""

from hot_paths import cache_get_hot as _hot_cache_get

def cache_get(key, default=None):
    """Safe cache access - public API."""
    if not key or not isinstance(key, str):
        return default
    
    try:
        return _hot_cache_get(key)
    except Exception as e:
        log_error(e)
        return default

# Application code
from cache_api import cache_get  # Public wrapper, not hot path
```

---

## DETECTION

**Automated Linting:**
```python
# .pylintrc or similar
# Flag direct usage of hot-path functions

[CHECKS]
forbidden-imports = hot_paths.*
required-wrappers = cache_get_hot -> cache_get
                   param_get_hot -> param_get
                   validate_hot -> validate_input
```

**Code Review Checklist:**
```
[ ] Are hot-path functions called directly?
[ ] Are wrapper functions used instead?
[ ] Is validation present before hot-path calls?
[ ] Is error handling present around hot-path calls?
[ ] Are examples using wrappers, not hot paths?
```

**Pattern Search:**
```bash
# Find direct hot-path usage
grep -r "_hot\(" --include="*.py" | \
  grep -v "def.*_hot" | \
  grep -v "# Wrapper"

# Should only find usage in wrapper implementations
```

---

## REFACTORING

**If direct usage found:**

### Step 1: Create Wrapper If Missing
```python
# Add safe wrapper for hot path
def operation_safe(*args, **kwargs):
    """Safe wrapper for operation_hot."""
    # Add validation
    if not validate_args(*args):
        return default
    
    try:
        return operation_hot(*args)
    except Exception as e:
        log_error(e)
        return default
```

### Step 2: Replace Direct Calls
```python
# Find all direct hot-path calls
# Replace with wrapper calls

# ❌ Before
result = cache_get_hot(key)

# ✅ After
result = cache_get(key, default=None)
```

### Step 3: Add Enforcement
```python
# Mark hot paths as internal
def _operation_hot(*args):  # Leading underscore
    """Internal - use operation() wrapper."""
    return _impl(*args)

# Or move to internal module
# hot_paths/_internal.py
```

---

## PREVENTION

**Design Time:**
```
[ ] Every hot path has corresponding wrapper
[ ] Wrappers documented as primary API
[ ] Hot paths marked internal/advanced
[ ] Examples show wrapper usage
```

**Implementation:**
```
[ ] Hot paths in separate module or private
[ ] Wrappers in public API module
[ ] Linting rules enforce wrapper usage
[ ] Code reviews check for violations
```

**Documentation:**
```
[ ] Public API clearly separated
[ ] Wrapper benefits explained
[ ] Hot-path dangers documented
[ ] Usage examples show wrappers
```

---

## RELATED PATTERNS

**This Anti-Pattern Causes:**
- Production incidents (6 in 8 weeks)
- Debugging difficulty (no logging)
- Lost reliability (crashes)

**Prevented By:**
- ZAPH-LESS-04: Hot-path wrapper pattern
- ZAPH-DEC-02: Zero-abstraction hot paths (mentions wrappers)

**Similar To:**
- Using private APIs directly
- Bypassing validation layers
- Performance over safety mistakes

---

## KEY TAKEAWAY

**Hot paths for speed, wrappers for safety. Always use wrappers in application code.**

Tier 1 hot paths optimized for performance, not safety. No validation, no error handling, no logging. Tier 2 wrappers provide safe interface. Application code should use wrappers, not hot paths directly.

**Cost of direct usage:**
- 6 production incidents (8 weeks)
- 90 minutes total outage time
- 18 hours incident investigation
- Lost user trust

**Value of wrapper pattern:**
- Zero incidents (with wrappers)
- Easy debugging (logging in wrappers)
- Preserved performance (hot path still fast)
- Developer-friendly API

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial anti-pattern documentation
- Direct hot-path usage anti-pattern
- Wrapper pattern solutions
- Prevention strategies defined

---

**END OF ANTI-PATTERN**

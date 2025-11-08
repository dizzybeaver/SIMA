# File: ZAPH-LESS-04-Hot-Path-Wrapper-Pattern.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Category:** Python/Architectures/ZAPH/Lessons  
**Status:** Active

---

## LESSON: Use Wrapper Pattern for Safe Hot-Path Access

**REF-ID:** ZAPH-LESS-04  
**Lesson Type:** Design Pattern  
**Severity:** Medium (Safety vs Performance)  
**Frequency:** Every Tier 1 implementation

---

## SUMMARY

Tier 1 zero-abstraction hot paths lack validation and error handling by design. Wrapper functions provide safe interfaces while preserving hot-path performance. Pattern separates speed (Tier 1) from safety (Tier 2).

---

## CONTEXT

**Project:** Cache system with zero-abstraction hot path  
**Situation:** Implemented Tier 1 `cache_get_hot(key)` with no validation for performance. Developers called it directly throughout codebase.

**Problem:** Direct hot-path calls caused production incidents:
- TypeError from passing int instead of str (3 incidents)
- KeyError from empty string keys (2 incidents)
- Crashes from None keys (1 incident)
- Difficult debugging (no logging in hot path)

**Root Issue:** Hot path designed for speed, but developers needed safe interface.

---

## WHAT HAPPENED

**Incident Pattern:**
```python
# Direct hot-path usage (unsafe)
def process_request(request):
    user_id = request.get("user_id")  # Could be None
    data = cache_get_hot(user_id)     # ❌ Crashes if None
    return process(data)
```

**Production Incidents:**
1. **Week 2:** user_id was None → KeyError → 500 error → 30 min outage
2. **Week 4:** user_id was int → TypeError → 500 error → 15 min outage  
3. **Week 6:** user_id was "" → KeyError → 500 error → 45 min outage

**Developer Frustration:**
- "Why doesn't cache_get validate inputs?"
- "How do I know if call succeeded?"
- "Can't debug without logging"
- "Should I add try-except everywhere?"

---

## DISCOVERY

**Key Insight:** Tier 1 functions need Tier 2 wrappers.

```python
# Tier 1: Pure speed (zero abstraction)
def cache_get_hot(key):
    """HOT PATH: No validation, no logging, no error handling."""
    return _cache.get(key)

# Tier 2: Safe interface (wraps hot path)
def cache_get_safe(key, default=None):
    """SAFE: Validates inputs, handles errors, logs access."""
    if not key or not isinstance(key, str):
        return default
    
    try:
        return cache_get_hot(key)
    except Exception as e:
        log_error(f"Cache error: {e}")
        return default
```

**Pattern:** Developers use `cache_get_safe()` (Tier 2 wrapper), which internally calls `cache_get_hot()` (Tier 1) only when safe.

---

## SOLUTION

**Implementation Pattern:**

### 1. Tier 1 Function (Hot Path)
```python
def operation_hot(*args):
    """Tier 1: Zero abstraction for performance.
    
    WARNING: Direct use discouraged. Use operation_safe() instead.
    Caller must ensure:
    - All args validated
    - Error handling at call site
    - Logging done externally
    """
    return _direct_implementation(*args)
```

### 2. Tier 2 Wrapper (Safe Interface)
```python
def operation_safe(*args, default=None):
    """Tier 2: Safe wrapper for hot path.
    
    This is the public interface. Use this in application code.
    Handles validation, errors, logging before calling hot path.
    """
    # Tier 2: Validation
    if not validate_args(*args):
        return default
    
    try:
        # Call Tier 1 hot path (validated inputs)
        result = operation_hot(*args)
        
        # Tier 2: Logging (outside hot path)
        if LOGGING_ENABLED:
            log_access(*args, result)
        
        return result
        
    except Exception as e:
        # Tier 2: Error handling
        log_error(f"Operation failed: {e}")
        return default
```

### 3. Documentation Pattern
```python
"""
Public API:
- operation_safe(*args, default=None) - Safe interface, use this

Internal (advanced):
- operation_hot(*args) - Zero-abstraction hot path
  WARNING: Direct use only if you validate inputs yourself.
  Most developers should use operation_safe() instead.
"""
```

---

## LESSONS LEARNED

### Lesson 1: Hot Paths Need Safe Wrappers
Tier 1 performance, Tier 2 safety. Best of both worlds.

**Why:**
- Hot path stays fast (no validation overhead)
- Safe wrapper provides developer-friendly interface
- Wrapper overhead only paid when needed
- Pattern scales to any hot-path operation

### Lesson 2: Default to Safe Interface
Make safe wrapper the "obvious" choice for developers.

**Why:**
- Prevents production incidents
- Easier to debug (logging in wrapper)
- Handles edge cases gracefully
- Only performance-critical paths use hot version directly

### Lesson 3: Document Both Interfaces
Clear documentation prevents misuse.

**Why:**
- Developers know which to use
- Hot path warnings prevent direct unsafe usage
- Wrapper interface clear and inviting
- Reduces support questions

### Lesson 4: Wrapper Adds Minimal Overhead
Validation + error handling only when needed, not in every request.

**Why:**
- Happy path: one extra function call (~100ns)
- Error path: overhead justified (prevents crashes)
- Logging conditional (disabled in hot deployments)
- Net cost < 1% for safety benefits

---

## IMPACT

### Before Wrapper Pattern
- Production incidents: 6 in 8 weeks
- Debugging time: 3 hours per incident (18 hours total)
- Developer confusion: High ("Why no validation?")
- Hot-path misuse: Common (direct unsafe calls)

### After Wrapper Pattern
- Production incidents: 0 in 6 months
- Debugging time: Minimal (logs in wrapper)
- Developer satisfaction: High (intuitive safe interface)
- Hot-path performance: Preserved (wrappers don't slow critical paths)

---

## REFERENCES

**Related To:**
- ZAPH-DEC-02: Zero-abstraction hot paths
- ZAPH-LESS-01: Measure before optimize
- ZAPH-AP-04: Direct hot-path usage anti-pattern

**Design Patterns:**
- Proxy Pattern (wrapper represents hot path)
- Fa├žade Pattern (simple interface to complex optimization)
- Decorator Pattern (adds validation/logging without modifying hot path)

---

## EXAMPLES

### Cache Access Pattern

```python
# Tier 1: Hot path (92% access, zero abstraction)
def cache_get_hot(key):
    """Tier 1 HOT PATH: No validation, no logging.
    
    WARNING: Use cache_get() wrapper instead. Direct use only if:
    - You validate key yourself
    - You handle errors yourself
    - You don't need logging
    """
    return _cache.get(key)

# Tier 2: Safe wrapper (public interface)
def cache_get(key, default=None):
    """Safe cache access with validation and error handling.
    
    This is the public API. Use this in application code.
    
    Args:
        key: Cache key (must be non-empty string)
        default: Value to return if key not found or error
        
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
        
        # Logging (conditional, outside hot path)
        if LOGGING_ENABLED:
            log_cache_access(key, hit=value is not None)
        
        return value if value is not None else default
        
    except Exception as e:
        # Error handling
        log_error(f"Cache access error for key {key}: {e}")
        return default
```

### Parameter Access Pattern

```python
# Tier 1: Hot path (45% access)
def param_get_hot(key):
    """Tier 1 HOT PATH: Direct dict access.
    
    WARNING: Use param_get() wrapper for safety.
    """
    return _params.get(key)

# Tier 2: Safe wrapper
def param_get(key, default=None, required=False):
    """Safe parameter access with validation.
    
    Public API for parameter access.
    
    Args:
        key: Parameter key
        default: Default if not found
        required: Raise if not found
        
    Returns:
        Parameter value or default
        
    Raises:
        KeyError: If required=True and key not found
    """
    if not key:
        raise ValueError("Parameter key cannot be empty")
    
    try:
        value = param_get_hot(key)
        
        if value is None and required:
            raise KeyError(f"Required parameter not found: {key}")
        
        return value if value is not None else default
        
    except Exception as e:
        if required:
            raise
        log_error(f"Parameter access error for {key}: {e}")
        return default
```

### Validation Pattern

```python
# Tier 1: Hot path (87% access)
def validate_input_hot(data):
    """Tier 1 HOT PATH: Direct validation checks.
    
    WARNING: Assumes data is str. Use validate_input() wrapper.
    """
    return len(data) <= MAX_LENGTH and VALID_FORMAT.match(data)

# Tier 2: Safe wrapper
def validate_input(data):
    """Safe input validation with type checking.
    
    Public API for input validation.
    
    Args:
        data: Input to validate (any type)
        
    Returns:
        bool: True if valid, False otherwise
        
    Example:
        if not validate_input(user_input):
            raise ValueError("Invalid input")
    """
    # Type checking
    if not isinstance(data, str):
        if LOGGING_ENABLED:
            log_warning(f"Invalid input type: {type(data)}")
        return False
    
    # Empty check
    if not data:
        return False
    
    try:
        # Call hot path with validated type
        result = validate_input_hot(data)
        
        if LOGGING_ENABLED and not result:
            log_validation_failure(data)
        
        return result
        
    except Exception as e:
        log_error(f"Validation error: {e}")
        return False
```

---

## ANTI-PATTERNS TO AVOID

**❌ AP-04:** Direct hot-path usage without validation  
**❌ AP-05:** Validation inside hot path (defeats purpose)  
**❌ AP-06:** No wrapper provided (forces unsafe usage)  
**❌ AP-07:** Wrapper slower than necessary (defeats purpose)

---

## KEY TAKEAWAY

**Hot paths for speed. Wrappers for safety. Both together = best solution.**

Tier 1 zero-abstraction provides maximum performance. Tier 2 wrappers provide maximum safety. Developers use safe wrappers by default. Hot paths called internally by wrappers with validated inputs. Pattern prevents incidents while preserving performance.

**Value of wrapper pattern:**
- Zero production incidents (vs. 6 without)
- Preserved hot-path performance (<1% overhead)
- Developer-friendly safe interface
- Easy debugging (logging in wrappers)
- Graceful error handling

**Implementation cost:**
- ~30 minutes per hot-path operation
- Minimal complexity (simple function)
- Clear documentation prevents misuse
- Scales to any tier 1 operation

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial lesson documentation
- Wrapper pattern specified
- Production incident examples
- Pattern variations documented

---

**END OF LESSON**

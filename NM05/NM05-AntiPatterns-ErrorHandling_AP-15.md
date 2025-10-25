# NM05-AntiPatterns-ErrorHandling_AP-15.md - AP-15

# AP-15: Swallowing Exceptions

**Category:** NM05 - Anti-Patterns
**Topic:** Error Handling
**Priority:** 🟡 HIGH
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Catching exceptions without logging or re-raising them ("swallowing") makes debugging impossible because errors fail silently with no trace of what went wrong.

---

## Context

When an exception occurs, it contains critical information about what failed and why. Swallowing exceptions by catching them without logging destroys this information, making production issues nearly impossible to debug.

**Problem:** Silent failures in production with no diagnostic information.

---

## Content

### The Anti-Pattern

```python
# ❌ WRONG - Swallowing exception
def process_data(data):
    try:
        result = gateway.cache_get(data['key'])
        return result
    except:  # Catches error but does nothing!
        pass  # Silent failure - no log, no trace
```

**What happens:**
1. Cache operation fails
2. Exception caught
3. Nothing logged
4. Function returns None silently
5. Debugging impossible

### Why This Is Wrong

**1. No Diagnostic Information**
- Can't see what failed
- Can't see when it failed
- Can't reproduce the problem

**2. Silent Failures**
- Code appears to work
- Actually broken
- Users affected unknowingly

**3. Impossible Debugging**
- No logs to check
- No error traces
- No stack traces
- Complete information loss

**4. Cascading Problems**
- Downstream code receives None/invalid data
- Errors manifest far from root cause
- Debugging becomes exponentially harder

### Correct Approach

**Option 1: Log and Re-raise**
```python
# ✅ CORRECT - Log then propagate
def process_data(data):
    try:
        result = gateway.cache_get(data['key'])
        return result
    except Exception as e:
        gateway.log_error(f"Cache retrieval failed: {e}")
        raise  # Re-raise to let caller handle
```

**Option 2: Log and Return Default**
```python
# ✅ CORRECT - Log and graceful degradation
def process_data(data):
    try:
        result = gateway.cache_get(data['key'])
        return result
    except Exception as e:
        gateway.log_error(f"Cache retrieval failed: {e}")
        return None  # Explicit default, after logging
```

**Option 3: Log and Transform**
```python
# ✅ CORRECT - Log and wrap in domain exception
def process_data(data):
    try:
        result = gateway.cache_get(data['key'])
        return result
    except Exception as e:
        gateway.log_error(f"Cache retrieval failed: {e}")
        raise ProcessingError(f"Data processing failed") from e
```

### Detection

**Code Review Red Flags:**
```python
# All of these are problematic:
except:
    pass

except Exception:
    pass

except Exception as e:
    # No log, no raise, no return

try:
    ...
except:
    return None  # Without logging
```

**Grep Command:**
```bash
# Find potential swallowed exceptions
grep -A2 "except" *.py | grep -E "(pass|^\s*$)"
```

### Real SUGA-ISP Example

**Wrong (from early code):**
```python
def get_config_value(key):
    try:
        return gateway.config_get(key)
    except:
        pass  # BUG: Silent failure, debugging nightmare
```

**Correct (current implementation):**
```python
def get_config_value(key):
    try:
        return gateway.config_get(key)
    except Exception as e:
        gateway.log_error(f"Config get failed for {key}: {e}")
        raise ConfigurationError(f"Missing config: {key}") from e
```

### When Is It Acceptable?

**Very rare cases:**
```python
# Acceptable: Known ignorable errors in non-critical paths
try:
    send_optional_metric()
except MetricServiceError as e:
    # Log at debug level (not error)
    gateway.log_debug(f"Optional metric failed: {e}")
    # Continue - metric failure shouldn't break core logic
```

**Rules for acceptable swallowing:**
1. ✅ Must log (at least at DEBUG level)
2. ✅ Must be truly optional operation
3. ✅ Must use specific exception type
4. ✅ Must document why it's safe to ignore

---

## Related Topics

- **AP-14**: Bare Except Clauses - Never use bare except
- **AP-16**: Generic Exception Types - Be specific about what you catch
- **ERR-02**: Error Propagation Patterns - How to handle errors correctly
- **LESS-10**: Error Handling Lessons - Bugs caused by swallowing exceptions

---

## Keywords

swallow exceptions, silent failures, error handling, logging errors, exception handling, debugging, production issues

---

## Version History

- **2025-10-24**: Created - Extracted from Part 2, added SUGA-ISP examples

---

**File:** `NM05-AntiPatterns-ErrorHandling_AP-15.md`
**End of Document**

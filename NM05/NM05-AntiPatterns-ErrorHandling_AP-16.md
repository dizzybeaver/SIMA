# NM05-AntiPatterns-ErrorHandling_AP-16.md - AP-16

# AP-16: Generic Exception Types

**Category:** NM05 - Anti-Patterns
**Topic:** Error Handling
**Priority:** 🟢 MEDIUM
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Raising generic Exception instead of specific exception types makes it harder to handle different error conditions appropriately and reduces code clarity about what can go wrong.

---

## Context

Python provides a rich hierarchy of exception types (ValueError, TypeError, KeyError, etc.) that communicate what went wrong. Using generic Exception loses this information and makes error handling less precise.

**Problem:** Unclear what went wrong and how to handle it appropriately.

---

## Content

### The Anti-Pattern

```python
# ❌ WRONG - Generic exception
def validate_config(config):
    if not config.get('url'):
        raise Exception("Missing URL")  # Generic!
    
    if not isinstance(config.get('timeout'), int):
        raise Exception("Invalid timeout")  # Generic!
    
    if config.get('timeout') < 0:
        raise Exception("Negative timeout")  # Generic!
```

**Problems:**
- All errors look the same
- Can't catch specific issues
- Unclear what the actual problem is
- Forces catch-all exception handling

### Why This Is Wrong

**1. Loss of Semantic Information**
```python
# Caller can't differentiate between errors
try:
    validate_config(config)
except Exception as e:
    # Is it missing data? Wrong type? Invalid value?
    # Can't tell from exception type!
    pass
```

**2. Forces Overly Broad Catches**
```python
# Must catch generic Exception
try:
    validate_config(config)
except Exception:  # Too broad!
    # This catches EVERYTHING, even bugs
    pass
```

**3. Reduced Code Clarity**
- Exception type should document what can fail
- Generic Exception hides failure modes
- Makes API contract unclear

### Correct Approach

**Use Specific Built-in Types:**
```python
# ✅ CORRECT - Specific exceptions
def validate_config(config):
    if not config.get('url'):
        raise ValueError("Missing required field: url")
    
    timeout = config.get('timeout')
    if not isinstance(timeout, int):
        raise TypeError(f"timeout must be int, got {type(timeout)}")
    
    if timeout < 0:
        raise ValueError(f"timeout must be positive, got {timeout}")
```

**Benefits:**
```python
# Caller can handle different errors differently
try:
    validate_config(config)
except ValueError as e:
    # Missing or invalid value - can prompt user
    gateway.log_warning(f"Invalid config: {e}")
    use_defaults()
except TypeError as e:
    # Wrong type - programming error, should not happen
    gateway.log_error(f"Config type error: {e}")
    raise  # Re-raise - this is a bug
```

### Common Specific Exception Types

**For validation:**
- `ValueError` - Invalid value for the right type
- `TypeError` - Wrong type entirely
- `KeyError` - Missing required key in dict

**For operations:**
- `RuntimeError` - General runtime failure
- `OSError` / `IOError` - File/network operations
- `TimeoutError` - Operation took too long

**For custom errors:**
```python
# Define domain-specific exceptions
class ConfigurationError(ValueError):
    """Raised when configuration is invalid"""
    pass

class AuthenticationError(RuntimeError):
    """Raised when authentication fails"""
    pass

# Usage
if not token:
    raise ConfigurationError("Missing authentication token")
```

### Real SUGA-ISP Examples

**Wrong (early code):**
```python
def get_ssm_parameter(key):
    if not key:
        raise Exception("No key")  # Generic!
    
    try:
        return ssm_client.get_parameter(Name=key)
    except Exception as e:
        raise Exception(f"Failed: {e}")  # Generic!
```

**Correct (current code):**
```python
def get_ssm_parameter(key):
    if not key:
        raise ValueError("Parameter key cannot be empty")
    
    try:
        return ssm_client.get_parameter(Name=key)
    except ClientError as e:
        if e.response['Error']['Code'] == 'ParameterNotFound':
            raise KeyError(f"Parameter not found: {key}") from e
        raise RuntimeError(f"SSM error: {e}") from e
```

### When Generic Exception Is Acceptable

**Rare cases:**
```python
# Base exception for module
class CacheError(Exception):
    """Base class for all cache errors"""
    pass

class CacheConnectionError(CacheError):
    """Cache backend unreachable"""
    pass

class CacheKeyError(CacheError):
    """Invalid cache key"""
    pass

# This is fine - creates specific hierarchy
```

### Decision Tree

```
Need to raise exception?
├─ Is it a validation error? → ValueError or TypeError
├─ Is it a missing key? → KeyError
├─ Is it a network/IO error? → OSError/IOError
├─ Is it a timeout? → TimeoutError
├─ Is it domain-specific? → Create custom exception
└─ Truly generic runtime error? → RuntimeError (not Exception!)
```

---

## Related Topics

- **AP-14**: Bare Except Clauses - Don't catch Exception generically
- **AP-15**: Swallowing Exceptions - Don't hide errors
- **ERR-02**: Error Propagation Patterns - How to handle errors correctly
- **LESS-10**: Error Handling Lessons - Real bugs from poor exception handling

---

## Keywords

exception types, generic exceptions, ValueError, TypeError, KeyError, error handling, specific exceptions, exception hierarchy

---

## Version History

- **2025-10-24**: Created - Extracted from Part 2, added SUGA-ISP examples and decision tree

---

**File:** `NM05-AntiPatterns-ErrorHandling_AP-16.md`
**End of Document**

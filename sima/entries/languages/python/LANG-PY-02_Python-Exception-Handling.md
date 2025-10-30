# File: LANG-PY-02_Python-Exception-Handling.md

# LANG-PY-02: Python Exception Handling Patterns

**Category:** Language Patterns
**Language:** Python
**Priority:** 🔴 CRITICAL
**Status:** Active
**Created:** 2025-10-29
**Last Updated:** 2025-10-29

---

## 📋 SUMMARY

Comprehensive exception handling patterns for Python including specific exception types, proper try-except structure, error propagation, and graceful degradation strategies. Good exception handling makes code robust, debuggable, and maintainable.

---

## 🎯 CORE PRINCIPLES

1. **Always use specific exception types** - Never bare `except:`
2. **Always log exceptions** - Silent failures are debugging nightmares
3. **Use appropriate exception types** - ValueError, TypeError, KeyError, etc.
4. **Propagate when appropriate** - Let callers handle what they can
5. **Graceful degradation** - Return safe defaults when recoverable

---

## 📐 EXCEPTION HANDLING RULES

### Rule 1: Never Use Bare Except Clauses

**❌ WRONG - Bare except catches everything:**
```python
try:
    result = dangerous_operation()
    return result
except:  # Catches EVERYTHING including SystemExit!
    return None
```

**Problems with bare except:**
- Catches KeyboardInterrupt (can't stop program)
- Catches SystemExit (can't exit cleanly)
- Catches MemoryError (wrong response)
- Hides programming errors
- Makes debugging impossible

**✅ CORRECT - Use specific exceptions:**
```python
try:
    result = dangerous_operation()
    return result
except ValueError as e:
    log_error(f"Invalid value: {e}")
    return None
except ConnectionError as e:
    log_error(f"Connection failed: {e}")
    raise  # Re-raise for caller to handle
```

---

### Rule 2: Always Log Exceptions

**❌ WRONG - Silent failure:**
```python
try:
    result = operation()
    return result
except:
    pass  # ERROR LOST - No diagnostic information!
```

**✅ CORRECT - Log with context:**
```python
try:
    result = operation()
    return result
except ValueError as e:
    log_error(f"Operation failed with invalid value: {e}")
    return None
except Exception as e:
    log_error(f"Unexpected error: {e}", exc_info=True)
    raise
```

**What to log:**
- Exception type and message
- Operation being performed
- Input parameters (if not sensitive)
- Stack trace for unexpected errors

---

### Rule 3: Use Specific Exception Types

**❌ WRONG - Generic Exception:**
```python
def validate_config(config):
    if not config.get('url'):
        raise Exception("Missing URL")  # Too generic!
    
    if not isinstance(config.get('timeout'), int):
        raise Exception("Invalid timeout")  # Too generic!
```

**✅ CORRECT - Specific types:**
```python
def validate_config(config):
    if not config.get('url'):
        raise ValueError("Missing required field: url")
    
    timeout = config.get('timeout')
    if not isinstance(timeout, int):
        raise TypeError(f"timeout must be int, got {type(timeout)}")
    
    if timeout < 0:
        raise ValueError(f"timeout must be positive, got {timeout}")
```

---

## 🎨 EXCEPTION TYPE SELECTION

### Built-in Exception Types

**ValueError - Invalid value:**
```python
def set_age(age):
    if age < 0 or age > 150:
        raise ValueError(f"Age must be 0-150, got {age}")
```

**TypeError - Wrong type:**
```python
def process_items(items):
    if not isinstance(items, list):
        raise TypeError(f"Expected list, got {type(items)}")
```

**KeyError - Missing dictionary key:**
```python
def get_config(key):
    if key not in _config:
        raise KeyError(f"Config key not found: {key}")
    return _config[key]
```

**AttributeError - Missing attribute:**
```python
def get_username(user):
    if not hasattr(user, 'username'):
        raise AttributeError("User object has no 'username' attribute")
    return user.username
```

**FileNotFoundError - Missing file:**
```python
def load_config(filename):
    if not os.path.exists(filename):
        raise FileNotFoundError(f"Config file not found: {filename}")
```

**TimeoutError - Operation timeout:**
```python
def wait_for_response(timeout=30):
    if elapsed_time > timeout:
        raise TimeoutError(f"Operation timed out after {timeout}s")
```

**NotImplementedError - Abstract method:**
```python
class BaseHandler:
    def handle(self, request):
        raise NotImplementedError("Subclass must implement handle()")
```

---

### Custom Exceptions

**When to create custom exceptions:**
- Domain-specific errors
- Need exception hierarchy
- Want to catch specific application errors

**✅ Good custom exceptions:**
```python
class CacheError(Exception):
    """Base class for cache-related errors."""
    pass

class CacheMissError(CacheError):
    """Raised when cache key not found."""
    pass

class CacheExpiredError(CacheError):
    """Raised when cached value has expired."""
    pass

class ValidationError(ValueError):
    """Raised when validation fails."""
    pass

class ConfigurationError(RuntimeError):
    """Raised for configuration issues."""
    pass
```

---

## 🔧 EXCEPTION HANDLING PATTERNS

### Pattern 1: Try-Except-Else-Finally

**Complete structure:**
```python
def process_file(filename):
    try:
        # Code that might raise exceptions
        file = open(filename, 'r')
        data = file.read()
        result = process(data)
    
    except FileNotFoundError as e:
        # Handle specific error
        log_error(f"File not found: {e}")
        return None
    
    except Exception as e:
        # Handle unexpected errors
        log_error(f"Unexpected error: {e}", exc_info=True)
        raise
    
    else:
        # Runs only if no exception occurred
        log_info("File processed successfully")
        return result
    
    finally:
        # Always runs (cleanup)
        if 'file' in locals():
            file.close()
```

**Better - Use context manager:**
```python
def process_file(filename):
    try:
        with open(filename, 'r') as file:
            data = file.read()
            result = process(data)
            log_info("File processed successfully")
            return result
    
    except FileNotFoundError as e:
        log_error(f"File not found: {e}")
        return None
    
    except Exception as e:
        log_error(f"Unexpected error: {e}", exc_info=True)
        raise
```

---

### Pattern 2: Catching Multiple Exceptions

**Catch multiple specific exceptions:**
```python
def api_call(url):
    try:
        response = http_request(url)
        return response.json()
    
    except (ConnectionError, TimeoutError) as e:
        # Handle network-related errors
        log_error(f"Network error: {e}")
        return cached_response()
    
    except json.JSONDecodeError as e:
        # Handle parsing errors
        log_error(f"Invalid JSON: {e}")
        raise
```

---

### Pattern 3: Re-raising with Context

**Preserve exception chain:**
```python
def get_user_data(user_id):
    try:
        return database.query(user_id)
    
    except DatabaseError as e:
        # Re-raise with additional context
        raise RuntimeError(f"Failed to get user {user_id}") from e
```

**Benefits of `from e`:**
- Preserves original exception
- Shows exception chain in traceback
- Maintains debugging information

---

### Pattern 4: Graceful Degradation

**Return safe defaults for expected errors:**
```python
def get_cached_value(key, default=None):
    """Get value from cache, return default if not found."""
    try:
        return cache.get(key)
    
    except CacheMissError:
        # Expected error - return default
        return default
    
    except CacheConnectionError as e:
        # Unexpected but recoverable
        log_warning(f"Cache unavailable: {e}")
        return default
    
    except Exception as e:
        # Truly unexpected - log and raise
        log_error(f"Cache error: {e}", exc_info=True)
        raise
```

---

### Pattern 5: Retry with Backoff

**Retry transient failures:**
```python
def api_call_with_retry(url, max_retries=3):
    """Call API with exponential backoff retry."""
    for attempt in range(max_retries):
        try:
            return http_request(url)
        
        except (TimeoutError, RateLimitError) as e:
            if attempt < max_retries - 1:
                sleep_time = 2 ** attempt  # 1s, 2s, 4s
                log_warning(f"Attempt {attempt + 1} failed: {e}. Retrying in {sleep_time}s")
                time.sleep(sleep_time)
            else:
                log_error(f"All {max_retries} attempts failed")
                raise
```

---

## 🎯 ERROR HANDLING DECISION TREE

### When to Handle vs Propagate

**Handle (catch and return):**
- Expected errors (cache miss, file not found)
- Recoverable errors (can provide default)
- User input validation
- Optional operations

**Propagate (raise or re-raise):**
- Unexpected errors
- Programming errors
- Cannot recover meaningfully
- Caller needs to know

**Decision flowchart:**
```
Error occurred
  ↓
Is it expected/recoverable?
  ├─ YES → Handle gracefully (log, return default)
  └─ NO → Is it transient?
      ├─ YES → Retry with backoff
      └─ NO → Log and propagate
```

---

## ⚠️ COMMON PITFALLS

### Pitfall 1: Swallowing Exceptions

**❌ WRONG:**
```python
try:
    critical_operation()
except Exception:
    pass  # Silent failure!
```

**✅ CORRECT:**
```python
try:
    critical_operation()
except Exception as e:
    log_error(f"Operation failed: {e}", exc_info=True)
    raise  # or return appropriate value
```

---

### Pitfall 2: Catching Too Broadly

**❌ WRONG:**
```python
try:
    result = calculate()
except Exception:  # Catches ALL exceptions
    return 0
```

**✅ CORRECT:**
```python
try:
    result = calculate()
except (ValueError, TypeError) as e:  # Only expected exceptions
    log_error(f"Calculation error: {e}")
    return 0
except Exception as e:  # Unexpected errors
    log_error(f"Unexpected error: {e}", exc_info=True)
    raise
```

---

### Pitfall 3: Generic Exception Messages

**❌ WRONG:**
```python
try:
    process(data)
except ValueError:
    raise ValueError("Error occurred")  # No context!
```

**✅ CORRECT:**
```python
try:
    process(data)
except ValueError as e:
    raise ValueError(f"Failed to process data: {e}") from e
```

---

### Pitfall 4: Exception in Except Block

**❌ DANGEROUS:**
```python
try:
    risky_operation()
except Exception as e:
    log_error(e.message)  # AttributeError if e has no 'message'!
```

**✅ SAFE:**
```python
try:
    risky_operation()
except Exception as e:
    log_error(str(e))  # str() is safe for all exceptions
```

---

## 📊 EXCEPTION SELECTION GUIDE

| Error Condition | Exception Type | Example |
|-----------------|----------------|---------|
| Invalid value | ValueError | Age < 0 |
| Wrong type | TypeError | Expected int, got str |
| Missing key | KeyError | Config key not found |
| Missing attribute | AttributeError | Object has no attr |
| File not found | FileNotFoundError | Config file missing |
| Permission denied | PermissionError | No write access |
| Timeout | TimeoutError | Request took too long |
| Not implemented | NotImplementedError | Abstract method |
| Network error | ConnectionError | Cannot reach server |
| Rate limit | Custom exception | API rate limit hit |

---

## ✅ EXCEPTION HANDLING CHECKLIST

Before committing exception handling code:
- [ ] No bare `except:` clauses
- [ ] Specific exception types used
- [ ] All exceptions logged with context
- [ ] Appropriate propagation (raise when needed)
- [ ] Graceful degradation for expected errors
- [ ] No swallowed exceptions
- [ ] Exception messages include context
- [ ] Use `from e` when re-raising
- [ ] Consider retry for transient errors
- [ ] Test failure paths

---

## 🔗 RELATED PATTERNS

### Within Language Patterns
- **LANG-PY-03**: Documentation (document exceptions in docstrings)
- **LANG-PY-04**: Function design (error handling in functions)
- **LANG-PY-07**: Code quality (exception handling quality)

### From Project Knowledge
- **AP-14**: Bare except clauses anti-pattern
- **AP-15**: Swallowing exceptions anti-pattern
- **AP-16**: Generic exception types anti-pattern
- **ERR-01**: Try-except at each layer
- **ERR-02**: Error propagation patterns
- **DT-05**: How should I handle this error decision tree

---

## 📚 REFERENCES

### Python Documentation
- **PEP 3134**: Exception Chaining and Embedded Tracebacks
- **Python Built-in Exceptions**: https://docs.python.org/3/library/exceptions.html

### Benefits
- **Debuggability**: Clear error messages and stack traces
- **Robustness**: Graceful handling of expected errors
- **Maintainability**: Specific exceptions easier to handle
- **User Experience**: Meaningful error messages

---

## 🤝 CONTRIBUTORS

**Original Author:** SIMAv4 Phase 4.0
**Source Material:** SUGA-ISP error handling patterns
**References:** AP-14, AP-15, AP-16, ERR-01, ERR-02, DT-05

**Last Reviewed By:** Claude
**Review Date:** 2025-10-29

---

## 📝 CHANGE LOG

### [1.0.0] - 2025-10-29
- Initial Python exception handling documentation
- Comprehensive exception patterns
- Decision trees for handling vs propagation
- Common pitfalls and solutions

---

**END OF LANGUAGE ENTRY**

**REF-ID:** LANG-PY-02
**Template Version:** 1.0.0
**Entry Type:** Language Pattern
**Status:** Active
**Maintenance:** Review when exception patterns evolve

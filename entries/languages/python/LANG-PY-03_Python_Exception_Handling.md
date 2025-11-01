# LANG-PY-03: Python Exception Handling

**REF-ID:** LANG-PY-03  
**Category:** Language Patterns  
**Subcategory:** Error Handling  
**Language:** Python  
**Priority:** 🔴 CRITICAL  
**Status:** Active  
**Created:** 2025-11-01  
**Last Updated:** 2025-11-01

---

## 📋 SUMMARY

Comprehensive exception handling standards for Python code. Proper exception handling improves reliability and debugging.

---

## 🎯 CORE RULES

### Rule 1: Never Use Bare Except Clauses

**❌ WRONG:**
```python
try:
    result = risky_operation()
except:  # BAD: Catches everything including KeyboardInterrupt
    log_error("Something went wrong")
```

**✅ CORRECT:**
```python
try:
    result = risky_operation()
except ValueError as e:
    log_error(f"Invalid value: {e}")
except ConnectionError as e:
    log_error(f"Connection failed: {e}")
except Exception as e:
    log_error(f"Unexpected error: {e}")
    raise  # Re-raise to maintain stack trace
```

**Why:** Bare except catches system exits, keyboard interrupts, and other critical exceptions.

---

### Rule 2: Catch Specific Exceptions

**✅ CORRECT:**
```python
try:
    value = int(user_input)
except ValueError:
    print("Please enter a valid number")
except KeyboardInterrupt:
    print("\nOperation cancelled")
    raise
```

**Priority Order:**
1. Most specific exception first
2. More general exceptions later
3. Exception as last resort
4. Never catch BaseException

---

### Rule 3: Always Log Errors

**✅ CORRECT:**
```python
import logging

try:
    process_data(data)
except ValueError as e:
    logging.error(f"Data validation failed: {e}", exc_info=True)
    raise
except Exception as e:
    logging.critical(f"Unexpected error: {e}", exc_info=True)
    raise
```

**Include:**
- Error message
- Exception details
- Context (exc_info=True for stack trace)
- Relevant variable values

---

### Rule 4: Re-raise When Appropriate

**✅ CORRECT:**
```python
def process_user_data(user_id):
    try:
        user = fetch_user(user_id)
    except DatabaseError as e:
        logging.error(f"Failed to fetch user {user_id}: {e}")
        raise  # Let caller handle
```

**When to re-raise:**
- Can't fully handle the error
- Need to propagate to caller
- After logging for debugging

---

### Rule 5: Use Custom Exceptions for Domain Logic

**✅ CORRECT:**
```python
class InvalidConfigError(Exception):
    """Raised when configuration is invalid."""
    pass

class ConnectionTimeoutError(Exception):
    """Raised when connection times out."""
    pass

def load_config(path):
    if not os.path.exists(path):
        raise InvalidConfigError(f"Config file not found: {path}")
```

**Benefits:**
- Clear error semantics
- Easier to catch specific errors
- Better documentation

---

### Rule 6: Use Finally for Cleanup

**✅ CORRECT:**
```python
def process_file(filename):
    f = open(filename)
    try:
        return f.read()
    except IOError as e:
        logging.error(f"Failed to read {filename}: {e}")
        raise
    finally:
        f.close()  # Always executes
```

**Better with Context Manager:**
```python
def process_file(filename):
    try:
        with open(filename) as f:
            return f.read()
    except IOError as e:
        logging.error(f"Failed to read {filename}: {e}")
        raise
```

---

### Rule 7: Provide Context in Exception Messages

**❌ WRONG:**
```python
raise ValueError("Invalid input")
```

**✅ CORRECT:**
```python
raise ValueError(
    f"Invalid input: expected integer between 1-100, got '{value}'"
)
```

**Include:**
- What went wrong
- Expected vs actual values
- How to fix (if known)

---

## 📊 EXCEPTION HIERARCHY

```
BaseException (DON'T CATCH)
├── SystemExit (DON'T CATCH)
├── KeyboardInterrupt (DON'T CATCH)
├── GeneratorExit (DON'T CATCH)
└── Exception (CATCH THIS OR MORE SPECIFIC)
    ├── StopIteration
    ├── ArithmeticError
    │   ├── FloatingPointError
    │   ├── OverflowError
    │   └── ZeroDivisionError
    ├── AssertionError
    ├── AttributeError
    ├── BufferError
    ├── EOFError
    ├── ImportError
    ├── LookupError
    │   ├── IndexError
    │   └── KeyError
    ├── MemoryError
    ├── NameError
    ├── OSError
    │   ├── FileNotFoundError
    │   ├── ConnectionError
    │   └── TimeoutError
    ├── RuntimeError
    ├── TypeError
    └── ValueError
```

---

## 🔧 PATTERNS

### Pattern 1: Try-Except-Else

```python
try:
    f = open(filename)
except IOError:
    logging.error(f"Cannot open {filename}")
else:
    # Only runs if no exception
    data = f.read()
    f.close()
```

### Pattern 2: Suppress Specific Exceptions

```python
from contextlib import suppress

# Instead of:
try:
    os.remove(temp_file)
except FileNotFoundError:
    pass

# Use:
with suppress(FileNotFoundError):
    os.remove(temp_file)
```

### Pattern 3: Exception Chaining

```python
try:
    result = parse_json(data)
except JSONDecodeError as e:
    raise InvalidDataError(
        "Failed to parse response"
    ) from e  # Preserves original exception
```

### Pattern 4: Multiple Exceptions Same Handler

```python
try:
    result = risky_operation()
except (ValueError, TypeError) as e:
    logging.error(f"Invalid input: {e}")
```

---

## ⚠️ ANTI-PATTERNS

### AP-1: Swallowing Exceptions

**❌ WRONG:**
```python
try:
    critical_operation()
except Exception:
    pass  # Silent failure
```

### AP-2: Too Broad Exception Catching

**❌ WRONG:**
```python
try:
    everything()
except Exception:  # Too broad
    handle_error()
```

### AP-3: Using Exceptions for Flow Control

**❌ WRONG:**
```python
try:
    value = cache[key]
except KeyError:
    value = compute_value(key)
```

**✅ CORRECT:**
```python
value = cache.get(key)
if value is None:
    value = compute_value(key)
```

---

## 🔗 CROSS-REFERENCES

### Related Patterns

- **LANG-PY-01**: Naming conventions for exceptions
- **LANG-PY-04**: Exception handling in function design
- **LANG-PY-07**: Error handling quality standards

### Anti-Patterns

- **AP-14**: Bare except clauses
- **AP-15**: Silent exception swallowing
- **AP-16**: Too broad exception catching

---

## 📚 EXAMPLES

### Example 1: API Call with Retries

```python
import time
from requests.exceptions import ConnectionError, Timeout

def call_api(url, max_retries=3):
    for attempt in range(max_retries):
        try:
            response = requests.get(url, timeout=5)
            response.raise_for_status()
            return response.json()
        except (ConnectionError, Timeout) as e:
            if attempt == max_retries - 1:
                logging.error(f"API call failed after {max_retries} attempts")
                raise
            logging.warning(f"Retry {attempt + 1}/{max_retries}: {e}")
            time.sleep(2 ** attempt)  # Exponential backoff
```

### Example 2: Database Transaction

```python
def save_user(user_data):
    try:
        conn = get_db_connection()
        try:
            cursor = conn.cursor()
            cursor.execute("INSERT INTO users VALUES (?)", user_data)
            conn.commit()
        except IntegrityError as e:
            conn.rollback()
            raise DuplicateUserError(f"User already exists: {e}") from e
        finally:
            cursor.close()
    finally:
        conn.close()
```

---

## ✅ VERIFICATION CHECKLIST

Before committing code:

- [ ] No bare except clauses
- [ ] All exceptions logged with context
- [ ] Specific exceptions caught (not Exception unless necessary)
- [ ] Custom exceptions for domain errors
- [ ] Resources cleaned up (use context managers)
- [ ] Exception messages include helpful context
- [ ] Stack traces preserved (exc_info=True or raise)
- [ ] No exceptions used for normal flow control

---

## 🤝 CONTRIBUTORS

**Original Author:** SIMAv4 Phase 5.1  
**Source Material:** SUGA-ISP Python standards  
**Extracted From:** LANG-PY-03-through-08 consolidated file  
**Last Reviewed:** 2025-11-01

---

**END OF LANG-PY-03**

**Lines:** ~360  
**REF-ID:** LANG-PY-03  
**Status:** Active  
**Next:** LANG-PY-04 (Function Design)
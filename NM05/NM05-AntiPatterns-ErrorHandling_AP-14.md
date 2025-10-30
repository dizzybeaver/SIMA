# NM05-AntiPatterns-ErrorHandling_AP-14.md - AP-14

# AP-14: Bare Except Clauses

**Category:** NM05 - Anti-Patterns  
**Topic:** ErrorHandling  
**Priority:** 🟡 High  
**Status:** Active  
**Created:** 2024-08-15  
**Last Updated:** 2025-10-24

---

## Summary

Never use bare `except:` clauses without specifying exception type. Bare excepts catch EVERYTHING including system exits and keyboard interrupts, making debugging nearly impossible and hiding critical errors.

---

## Context

Bare except clauses seem convenient but they catch KeyboardInterrupt, SystemExit, and other exceptions that should propagate. They hide bugs and make troubleshooting difficult.

---

## Content

### The Anti-Pattern

**❌ WRONG:**
```python
def process_request(event):
    try:
        result = dangerous_operation()
        return result
    except:  # Bare except - catches EVERYTHING!
        return {'error': 'Something went wrong'}
```

### Why It's Wrong

**1. Catches System Exceptions**
```python
try:
    process()
except:  # Catches KeyboardInterrupt!
    pass  # User can't stop program
```

**2. Hides Real Errors**
```python
try:
    value = event['required_field']  # KeyError
    result = calculate(value)  # Never runs
except:  # Hides KeyError
    return {}  # Silent failure
```

**3. Makes Debugging Impossible**
```python
try:
    complex_operation()
except:
    log_error("Failed")  # What failed? Where? Why?
```

**4. Swallows Exceptions You Didn't Expect**
```python
try:
    data = fetch_data()
    process(data)
except:  # Catches MemoryError, SystemExit, etc.
    return fallback()  # Wrong response for wrong errors
```

### What Bare Except Catches

**EVERYTHING:**
- ✓ ValueError (expected)
- ✓ KeyError (expected)
- ✓ TypeError (maybe expected)
- ✓ KeyboardInterrupt (BAD!)
- ✓ SystemExit (BAD!)
- ✓ MemoryError (BAD!)
- ✓ Your custom exceptions
- ✓ Library exceptions
- ✓ Syntax errors in eval()
- ✓ Import errors in dynamic imports

### The Correct Approaches

**✅ Option 1: Specific Exception**
```python
def process_request(event):
    try:
        result = dangerous_operation()
        return result
    except ValueError as e:  # Specific!
        log_error(f"Invalid value: {e}")
        return {'error': 'Invalid input'}
```

**✅ Option 2: Multiple Specific Exceptions**
```python
def fetch_data(url):
    try:
        response = http_get(url)
        return parse_json(response)
    except ConnectionError as e:
        log_error(f"Connection failed: {e}")
        raise
    except json.JSONDecodeError as e:
        log_error(f"Invalid JSON: {e}")
        raise
```

**✅ Option 3: Exception as Base Class**
```python
def process():
    try:
        risky_operation()
    except Exception as e:  # Catches all BUT system exceptions
        log_error(f"Operation failed: {e}")
        return fallback()
```

### Exception Hierarchy

**Safe to catch:**
```python
Exception  # Base for normal exceptions
├── ValueError
├── TypeError
├── KeyError
├── AttributeError
├── IOError
└── ... (user exceptions)
```

**DON'T catch (without re-raising):**
```python
BaseException  # Top of hierarchy
├── SystemExit  # Program exit
├── KeyboardInterrupt  # Ctrl+C
├── GeneratorExit  # Generator cleanup
└── Exception  # (OK to catch)
```

### When to Use Each

**Specific Exception (Preferred):**
```python
try:
    value = int(user_input)
except ValueError:  # Only catches conversion errors
    value = 0
```

**Multiple Exceptions:**
```python
try:
    data = fetch_and_process()
except (ConnectionError, TimeoutError):
    return cached_data()
```

**Exception Base Class (Last Resort):**
```python
try:
    plugin.execute()  # External code
except Exception as e:  # Unknown exceptions possible
    log_error(f"Plugin failed: {e}")
    disable_plugin()
```

### Real-World Examples

**Scenario 1: API Call**
```python
# ❌ Wrong
try:
    response = api_call()
except:
    return {}

# ✅ Correct
try:
    response = api_call()
except ConnectionError:
    return cached_response()
except TimeoutError:
    raise  # Let caller handle
```

**Scenario 2: Data Processing**
```python
# ❌ Wrong  
try:
    result = process(data)
except:
    result = None

# ✅ Correct
try:
    result = process(data)
except (ValueError, TypeError) as e:
    log_error(f"Invalid data: {e}")
    result = None
```

**Scenario 3: Resource Cleanup**
```python
# ❌ Wrong
try:
    with open(file) as f:
        process(f)
except:
    pass

# ✅ Correct - Let it fail!
with open(file) as f:  # 'with' handles cleanup
    process(f)  # Let exceptions propagate
```

### Exception Logging

**Always log with context:**
```python
# ❌ Wrong
except ValueError:
    log_error("Error occurred")

# ✅ Correct
except ValueError as e:
    log_error(f"Invalid value in field {field}: {e}")
```

**Include exception info:**
```python
# ✅ Best
except ValueError as e:
    log_error(f"Processing failed: {e}", exc_info=True)
    # exc_info=True adds stack trace to logs
```

### Detection

**Automated Lint Check:**
```bash
# Find bare except clauses
grep -r "except:" *.py

# Should return nothing except comments
```

**Code Review Checklist:**
```
[ ] No bare except: clauses
[ ] All excepts specify type
[ ] Exceptions logged with context
[ ] System exceptions not caught
```

### Migration Guide

**Step 1: Find bare excepts**
```bash
grep -n "except:" *.py > bare_excepts.txt
```

**Step 2: Identify what could be raised**
```python
# What exceptions can this raise?
try:
    value = int(data)  # ValueError
    result = database.query(value)  # ConnectionError
    return result['key']  # KeyError
```

**Step 3: Catch specific ones**
```python
except (ValueError, ConnectionError, KeyError) as e:
    handle_error(e)
```

**Step 4: Test error paths**
```python
def test_handles_value_error():
    with pytest.raises(ValueError):
        process_invalid_data()
```

---

## Related Topics

- **ERR-02**: Error propagation patterns
- **AP-15**: Swallowing exceptions (related issue)
- **PATH-04**: Error handling in operation flows
- **LESS-08**: Test failure paths
- **DEC-15**: Router-level exception catching

---

## Keywords

bare except, exception handling, error catching, specific exceptions, exception hierarchy, debugging

---

## Version History

- **2025-10-24**: Migrated to SIMA v3 format
- **2024-08-15**: Created - documented bare except anti-pattern

---

**File:** `NM05-AntiPatterns-ErrorHandling_AP-14.md`  
**End of Document**

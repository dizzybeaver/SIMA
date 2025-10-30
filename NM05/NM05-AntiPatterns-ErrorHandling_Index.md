# NM05-AntiPatterns-ErrorHandling_Index.md

# Anti-Patterns - Error Handling Index

**Category:** NM05 - Anti-Patterns
**Topic:** ErrorHandling
**Items:** 5
**Last Updated:** 2025-10-23

---

## Topic Overview

**Description:** Error handling anti-patterns that lose error information, create security vulnerabilities, or make debugging impossible. Proper error handling is critical for production robustness and security. These patterns range from losing error context (bare except) to enabling security attacks (injection vulnerabilities).

**Keywords:** exceptions, error handling, validation, security, debugging, logging

---

## Individual Files

### AP-14: Bare Except Clauses
- **File:** `NM05-AntiPatterns-ErrorHandling_AP-14.md`
- **Summary:** Using `except:` without specifying exception type
- **Severity:** 🟡 High
- **What to do instead:** Use `except Exception:` or specific types
- **Why high:** Catches system exceptions, makes debugging impossible

### AP-15: Swallowing Exceptions Without Logging
- **File:** `NM05-AntiPatterns-ErrorHandling_AP-15.md`
- **Summary:** Catching exceptions without logging what happened
- **Severity:** 🟡 High
- **What to do instead:** Always log before returning None/default
- **Why high:** Errors disappear without trace, debugging nightmare

### AP-16: Generic Exception Types
- **File:** `NM05-AntiPatterns-ErrorHandling_AP-16.md`
- **Summary:** Raising generic `Exception` instead of specific types
- **Severity:** 🟢 Medium
- **What to do instead:** Use ValueError, KeyError, TypeError, etc.
- **Why medium:** Makes error handling less precise

### AP-17: No Input Validation
- **File:** `NM05-AntiPatterns-ErrorHandling_AP-17.md`
- **Summary:** Accepting external input without validation
- **Severity:** 🔴 Critical
- **What to do instead:** Validate all inputs at entry points
- **Why critical:** Security vulnerability (injection, SSRF, etc.)

### AP-19: SQL Injection Patterns
- **File:** `NM05-AntiPatterns-ErrorHandling_AP-19.md`
- **Summary:** String interpolation with user input in queries
- **Severity:** 🔴 Critical
- **What to do instead:** Use parameterized queries or avoid SQL
- **Why critical:** Catastrophic security breach

---

## Common Themes

**Error handling failures fall into three categories:**

1. **Information Loss** (AP-14, AP-15, AP-16)
   - Bare excepts hide what went wrong
   - Swallowed exceptions leave no trace
   - Generic exceptions provide no specificity
   - Result: Impossible to debug production issues

2. **Security Vulnerabilities** (AP-17, AP-19)
   - No input validation enables injection attacks
   - SQL injection allows data exfiltration
   - Result: Complete system compromise possible

3. **Production Robustness**
   - Proper error handling enables recovery
   - Good logging enables diagnosis
   - Validation prevents cascading failures

---

## The Error Handling Hierarchy

**Level 1: Catastrophic (must prevent)**
- Security vulnerabilities (AP-17, AP-19)
- System crashes
- Data corruption

**Level 2: Severe (must log)**
- Unexpected exceptions (AP-14, AP-15)
- Integration failures
- Invalid state transitions

**Level 3: Expected (handle gracefully)**
- Validation errors (caught by AP-17)
- Missing optional data
- Timeouts with retry

---

## Related Topics

- **ERR-02**: Error propagation patterns (correct approach)
- **DEC-15**: Router-level exceptions (where to handle errors)
- **NM03-ERROR**: Error flows (comprehensive guide)
- **INT-11**: MONITORING interface (detecting errors)
- **LESS-08**: Error handling lessons (learned from bugs)

---

## Detection & Prevention

**How to detect error handling anti-patterns:**
```bash
# Find bare except clauses (AP-14)
grep -r "except:" *.py | grep -v "except Exception"

# Find swallowed exceptions (AP-15) - manual review
# Look for: except blocks with pass or return None

# Find generic exceptions (AP-16)
grep -r "raise Exception(" *.py

# Find SQL injection risks (AP-19)
grep -r "f\".*SELECT\|INSERT\|UPDATE" *.py
grep -r "%.*SELECT\|INSERT\|UPDATE" *.py
```

**Prevention checklist:**
- [ ] All except clauses specify exception type
- [ ] All caught exceptions logged before handling
- [ ] Raise specific exception types (ValueError, etc.)
- [ ] All external inputs validated
- [ ] No string formatting in SQL/commands
- [ ] Gateway functions validate inputs
- [ ] Router sanitizes sentinel objects

---

## Critical Error Handling Patterns

**Pattern 1: Catch specific, log always**
```python
try:
    risky_operation()
except ValueError as e:
    gateway.log_error(f"Validation failed: {e}")
    raise
except ConnectionError as e:
    gateway.log_error(f"Network failure: {e}")
    return None
```

**Pattern 2: Validate at boundaries**
```python
def api_handler(event):
    # Validate immediately
    if not event.get('required_field'):
        raise ValueError("Missing required_field")
    
    # Then process
    process(event)
```

**Pattern 3: Never trust external input**
```python
def process_url(url):
    # Validate before use
    if not gateway.validate_url(url):
        raise ValueError(f"Invalid URL: {url}")
    
    # Safe to use
    response = gateway.http_get(url)
```

---

**Navigation:**
- **Up:** NM05-AntiPatterns_Index.md
- **Sibling Topics:** Import, Concurrency, Design

---

**End of Index**

# NM05-AntiPatterns-Implementation_AP-07.md - AP-07

# AP-07: Custom Logging Implementation

**Category:** NM05 - Anti-Patterns  
**Topic:** Implementation  
**Priority:** 🟡 High  
**Status:** Active  
**Created:** 2024-08-15  
**Last Updated:** 2025-10-24

---

## Summary

Never implement custom logging using Python's logging module or print statements. Always use the LOGGING interface via gateway to ensure consistent format, proper CloudWatch integration, and centralized log management.

---

## Context

Developers sometimes import Python's standard logging module or use print() thinking it's simpler. This bypasses the LOGGING interface and creates inconsistent, hard-to-debug log outputs.

---

## Content

### The Anti-Pattern

**❌ WRONG:**
```python
# In http_client_core.py
import logging  # Python's logging module

logger = logging.getLogger(__name__)

def http_post(url, data):
    logger.info(f"POST {url}")  # Custom logging
    # ... rest of function
```

**❌ ALSO WRONG:**
```python
# In any module
def process_request(event):
    print(f"Processing: {event}")  # Print statements
    # ... rest of function
```

### Why It's Wrong

**1. Inconsistent Log Format**
- Python logging uses different format
- CloudWatch integration broken
- Timestamps inconsistent
- No request correlation

**2. Missing Context**
- No request ID
- No execution context
- No structured fields
- Hard to trace requests

**3. Lost Logs**
- print() may not reach CloudWatch
- Different log streams
- Incomplete log history
- Missing in log aggregation

**4. No Log Levels**
- All logs same priority
- Can't filter by severity
- No debug/info/error distinction
- Cluttered logs

**5. Testing Problems**
- Can't mock logging easily
- Different mocking strategy needed
- Inconsistent test setup
- Hard to verify log output

### The Correct Approach

**✅ CORRECT:**
```python
# In http_client_core.py
import gateway

def http_post(url, data):
    gateway.log_info(f"POST {url}")  # Via LOGGING interface
    # ... rest of function
```

**✅ ALL LOG LEVELS:**
```python
import gateway

# Different severity levels
gateway.log_debug("Detailed diagnostic info")
gateway.log_info("Normal operation info")
gateway.log_warning("Warning condition")
gateway.log_error("Error condition")
gateway.log_critical("Critical failure")
```

### Benefits of Gateway Logging

**1. Consistent Format**
```
All logs look like:
[2025-10-24 10:23:45] [INFO] [req-abc123] POST https://api.example.com
[2025-10-24 10:23:46] [ERROR] [req-abc123] Connection timeout

Easy to parse, filter, search
```

**2. CloudWatch Integration**
- Proper log groups
- Correct timestamps
- Structured metadata
- Searchable fields

**3. Request Correlation**
- Every log has request ID
- Can trace full request flow
- Easy debugging
- Clear causality

**4. Configurable Levels**
```python
# Development: See everything
LOG_LEVEL = DEBUG

# Production: Errors only
LOG_LEVEL = ERROR
```

**5. Easy Testing**
```python
# In tests
mock_gateway.log_info = Mock()
process_request(event)
mock_gateway.log_info.assert_called_once()
```

### Real-World Examples

**Scenario 1: HTTP Client Logging**
```python
# ❌ Wrong
print(f"Sending request to {url}")

# ✅ Correct
gateway.log_info(f"HTTP POST: {url}", extra={'method': 'POST', 'url': url})
```

**Scenario 2: Error Logging**
```python
# ❌ Wrong
except Exception as e:
    print(f"Error: {e}")

# ✅ Correct
except Exception as e:
    gateway.log_error(f"Request failed: {e}", exc_info=True)
```

**Scenario 3: Debug Logging**
```python
# ❌ Wrong
if DEBUG:
    print(f"Cache state: {cache}")

# ✅ Correct  
gateway.log_debug(f"Cache state: {cache}")
```

### Migration Guide

**Step 1: Find all print statements**
```bash
grep -r "print(" *.py | grep -v "# Test output"
```

**Step 2: Replace with appropriate log level**
```python
# Before
print("Starting process")

# After
gateway.log_info("Starting process")
```

**Step 3: Find logging module usage**
```bash
grep -r "import logging" *.py | grep -v logging_core.py
```

**Step 4: Replace with gateway**
```python
# Before
logger.info("message")

# After
gateway.log_info("message")
```

### Special Cases

**Lambda Failsafe Exception:**
The ONLY place where print() is allowed is in `lambda_failsafe.py` because it must work even when all interfaces are broken.

```python
# In lambda_failsafe.py ONLY
def failsafe_handler(event, context):
    print("Failsafe triggered")  # ✅ Allowed here
```

**Everywhere else: Use gateway logging.**

### Detection

**Code Review Checklist:**
```
[ ] No print() statements (except tests, failsafe)
[ ] No import logging (except logging_core.py)
[ ] All logging via gateway.log_*
[ ] Appropriate log levels used
```

**Automated Detection:**
```bash
# Find print statements
grep -r "print(" *.py | grep -v "test_" | grep -v "lambda_failsafe"

# Find logging imports  
grep -r "import logging" *.py | grep -v "logging_core.py"
```

---

## Related Topics

- **INT-02**: LOGGING interface documentation
- **AP-06**: Custom caching (similar duplication issue)
- **DEC-08**: Why logging interface designed this way
- **LESS-03**: Infrastructure vs business logic
- **AP-10**: Don't modify lambda_failsafe (only exception)

---

## Keywords

custom logging, print statements, logging module, LOGGING interface, gateway logging, CloudWatch, log consistency

---

## Version History

- **2025-10-24**: Migrated to SIMA v3 format
- **2024-08-15**: Created - documented custom logging anti-pattern

---

**File:** `NM05-AntiPatterns-Implementation_AP-07.md`  
**End of Document**

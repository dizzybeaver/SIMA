# NM01-Architecture-InterfacesCore_INT-03.md - INT-03

# INT-03: SECURITY Interface

**Category:** NM01 - Architecture  
**Topic:** Interfaces-Core  
**Priority:** 🟡 HIGH  
**Status:** Active  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-24

---

## Summary

Input validation, sanitization, and security checks interface, including critical sentinel leak detection to prevent internal implementation details from escaping module boundaries.

---

## Context

The SECURITY interface protects the system from malicious or malformed input and prevents internal implementation details (like sentinels) from leaking to users. Critical for system integrity and security.

**Why it exists:** Lambda functions accept external input that must be validated. Additionally, the system uses internal sentinels that must never escape to users.

---

## Content

### Overview

```
Router: interface_security.py
Core: security_core.py
Purpose: Input validation and security checks
Pattern: Dictionary-based dispatch
State: Stateless (no persistent state)
Dependency Layer: Layer 1 (Core Utilities)
```

### Operations (6 total)

```
├─ validate_string: Validate and sanitize string input
├─ validate_dict: Validate dictionary structure
├─ validate_list: Validate list contents
├─ is_sentinel: Check if value is internal sentinel
├─ sanitize_for_log: Sanitize data for safe logging
└─ validate_jwt: Validate JWT token (if auth enabled)
```

### Gateway Wrappers

```python
# Validation operations
validate_string(value: str, max_length: int = 1000) -> str
validate_dict(value: dict, required_keys: List[str] = None) -> dict
validate_list(value: list, max_items: int = 100) -> list

# Security checks
is_sentinel(value: Any) -> bool
sanitize_for_log(data: Any) -> Any

# Authentication (if enabled)
validate_jwt(token: str) -> Dict
```

### Dependencies

```
Uses: LOGGING (for validation errors)
Used by: CACHE (sentinel validation), HTTP_CLIENT (input validation)
```

### Sentinel Detection

The security interface detects internal sentinels that should never leak to users:

```python
# Internal sentinels
_CacheMiss      # From cache_core
_ConfigMissing  # From config_core
_NotInitialized # From initialization_core

# Detection
if is_sentinel(value):
    raise SecurityError("Internal sentinel leaked")
```

### String Validation

```python
from gateway import validate_string

# Basic validation
safe_input = validate_string(user_input)

# With max length
username = validate_string(user_input, max_length=50)

# Sanitization includes:
# - Remove null bytes
# - Trim whitespace
# - Check for SQL injection patterns
# - Check for XSS patterns
# - Enforce max length
```

### Data Sanitization for Logging

```python
from gateway import sanitize_for_log

# Remove sensitive data before logging
safe_data = sanitize_for_log({
    'username': 'john',
    'password': 'secret123',  # Will be masked
    'token': 'Bearer xyz',     # Will be masked
    'ssn': '123-45-6789'       # Will be masked
})

# Result: {'username': 'john', 'password': '***', 'token': '***', 'ssn': '***'}
```

### Design Decisions

```
- Fail-safe: Invalid input raises exceptions
- Whitelist approach: Validate expected formats
- Sentinel protection: Prevent internal leaks
- Log sanitization: Automatic PII removal
```

### Usage Example

```python
from gateway import validate_string, validate_dict, is_sentinel, sanitize_for_log

# Validate user input
try:
    username = validate_string(request_data['username'], max_length=50)
    email = validate_string(request_data['email'], max_length=100)
except ValueError as e:
    return error_response("Invalid input")

# Validate structure
required_keys = ['name', 'email', 'age']
user_data = validate_dict(request_data, required_keys=required_keys)

# Check for sentinel leak
value = cache_get('key')
if is_sentinel(value):
    log_error("Sentinel leaked from cache")
    raise SecurityError("Internal error")

# Safe logging
log_info("User data", extra=sanitize_for_log(user_data))
```

---

## Related Topics

- **DEC-05**: Sentinel sanitization decision (router layer)
- **BUG-01**: Sentinel leak (535ms cost) - The bug that motivated this interface
- **BUG-02**: _CacheMiss validation failure
- **INT-01**: CACHE - Uses sentinel validation
- **INT-02**: LOGGING - Used for validation errors

---

## Keywords

security, validation, sanitization, sentinel, input validation, XSS, SQL injection, PII

---

## Version History

- **2025-10-24**: Atomized from monolithic file (SIMA v3 migration)
- **2025-10-20**: Original content in IMPLEMENTATION_Core.md

---

**File:** `NM01-Architecture-InterfacesCore_INT-03.md`  
**End of Document**

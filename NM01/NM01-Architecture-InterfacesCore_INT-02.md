# NM01-Architecture-InterfacesCore_INT-02.md - INT-02

# INT-02: LOGGING Interface

**Category:** NM01 - Architecture  
**Topic:** Interfaces-Core  
**Priority:** 🔴 CRITICAL  
**Status:** Active  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-24

---

## Summary

Centralized logging interface with multiple severity levels, structured JSON output, and DEBUG_MODE support for CloudWatch integration.

---

## Context

The LOGGING interface is the base layer (Layer 0 in dependency hierarchy) and is used by ALL other interfaces. Essential for observability, debugging, and monitoring in production Lambda environments.

**Why it exists:** CloudWatch Logs is the primary observability tool for Lambda. Structured JSON logging enables powerful queries and analysis of system behavior.

---

## Content

### Overview

```
Router: interface_logging.py
Core: logging_core.py
Purpose: Centralized logging with multiple levels
Pattern: Dictionary-based dispatch
State: Configured logger instance
Dependency Layer: Layer 0 (Base - no dependencies)
```

### Operations (6 total)

```
├─ info: Information messages
├─ warning: Warning messages
├─ error: Error messages with optional exception
├─ critical: Critical system errors
├─ debug: Debug messages (only in DEBUG_MODE)
└─ get_logger: Get logger instance
```

### Gateway Wrappers

```python
# Log level operations
log_info(message: str, **extra) -> None
log_warning(message: str, **extra) -> None
log_error(message: str, error: Exception = None, **extra) -> None
log_critical(message: str, **extra) -> None
log_debug(message: str, **extra) -> None

# Utility
get_logger() -> Logger
```

### Dependencies

```
Uses: None (base layer - DEP-01)
Used by: ALL interfaces (for logging)
```

### Log Levels

```
DEBUG    → Only logged when DEBUG_MODE=true
INFO     → General information
WARNING  → Warning conditions
ERROR    → Error conditions
CRITICAL → Critical system failures
```

### Structured Logging

```python
# Simple message
log_info("User logged in")

# With extra context
log_info("Cache hit", extra={
    'key': 'user:123',
    'ttl_remaining': 245
})

# Error with exception
try:
    result = risky_operation()
except Exception as e:
    log_error("Operation failed", error=e, extra={
        'operation': 'risky_operation',
        'user_id': user_id
    })
```

### Log Output Format

```json
{
  "timestamp": "2025-10-20T14:30:45.123Z",
  "level": "INFO",
  "message": "Cache hit",
  "extra": {
    "key": "user:123",
    "ttl_remaining": 245
  },
  "request_id": "abc-123-def"
}
```

### Design Decisions

```
- Uses Python's built-in logging module
- JSON structured logging for CloudWatch
- Debug logs only in DEBUG_MODE (performance)
- All interfaces required to log significant events
- Base layer (no dependencies on other interfaces)
```

### Usage Example

```python
from gateway import log_info, log_error, log_debug

# Basic logging
log_info("Processing request")

# With context
log_info("User authenticated", extra={
    'user_id': 123,
    'method': 'oauth'
})

# Debug logging (only in DEBUG_MODE)
log_debug("Internal state", extra={'state': internal_dict})

# Error logging with exception
try:
    process_data()
except ValueError as e:
    log_error("Invalid data", error=e, extra={
        'data_type': 'user_input'
    })
```

### Performance Considerations

```
Info/Warning/Error: ~0.1-0.2ms per log (negligible)
Debug (disabled): ~0.001ms (check only, no write)
Debug (enabled): ~0.1-0.2ms (same as other levels)
```

**Recommendation:** Use DEBUG_MODE=false in production, enable only for debugging specific issues.

---

## Related Topics

- **DEP-01**: Layer 0 (Base) - LOGGING has no dependencies
- **PATH-03**: Logging pipeline - How logs flow to CloudWatch
- **LESS-02**: Measure don't guess - Why logging is essential
- **DEC-13**: Fast path (ZAPH) - Logging excluded from hot path due to critical nature

---

## Keywords

logging, observability, CloudWatch, structured logging, DEBUG_MODE, JSON, monitoring

---

## Version History

- **2025-10-24**: Atomized from monolithic file (SIMA v3 migration)
- **2025-10-20**: Original content in IMPLEMENTATION_Core.md

---

**File:** `NM01-Architecture-InterfacesCore_INT-02.md`  
**End of Document**

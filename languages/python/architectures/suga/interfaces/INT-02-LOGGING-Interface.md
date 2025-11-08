# INT-02-LOGGING-Interface.md

**Version:** 1.0.0  
**Date:** 2025-11-07  
**Purpose:** SUGA LOGGING interface pattern definition  
**Architecture:** SUGA (Gateway Pattern)

---

## INTERFACE OVERVIEW

**Interface:** INT-02 LOGGING  
**Category:** Observability  
**Layer Position:** Interface Layer  
**Gateway Required:** Yes

### Purpose

Provides logging operations through SUGA three-layer pattern with mandatory gateway access and proper severity handling.

### Key Principle

**Gateway → Interface → Core** with lazy imports and structured logging.

---

## THREE-LAYER IMPLEMENTATION

### Layer 1: Gateway Entry (gateway_wrappers_logging.py)

```python
def log_info(message, **context):
    """
    Log info message via SUGA gateway pattern.
    
    Pattern: Gateway → Interface (lazy import)
    """
    import interface_logging
    return interface_logging.info(message, **context)

def log_error(message, error=None, **context):
    """
    Log error message via SUGA gateway pattern.
    
    Pattern: Gateway → Interface (lazy import)
    """
    import interface_logging
    return interface_logging.error(message, error, **context)

def log_warning(message, **context):
    """
    Log warning message via SUGA gateway pattern.
    
    Pattern: Gateway → Interface (lazy import)
    """
    import interface_logging
    return interface_logging.warning(message, **context)

def log_debug(message, **context):
    """
    Log debug message via SUGA gateway pattern.
    
    Pattern: Gateway → Interface (lazy import)
    """
    import interface_logging
    return interface_logging.debug(message, **context)
```

### Layer 2: Interface Routing (interface_logging.py)

```python
def info(message, **context):
    """
    Route info logging to core.
    
    Pattern: Interface → Core (lazy import)
    """
    import logging_core
    return logging_core.log_impl('INFO', message, **context)

def error(message, error=None, **context):
    """
    Route error logging to core.
    
    Pattern: Interface → Core (lazy import)
    """
    import logging_core
    return logging_core.log_impl('ERROR', message, error=error, **context)

def warning(message, **context):
    """
    Route warning logging to core.
    
    Pattern: Interface → Core (lazy import)
    """
    import logging_core
    return logging_core.log_impl('WARNING', message, **context)

def debug(message, **context):
    """
    Route debug logging to core.
    
    Pattern: Interface → Core (lazy import)
    """
    import logging_core
    return logging_core.log_impl('DEBUG', message, **context)
```

### Layer 3: Core Implementation (logging_core.py)

```python
import json
import traceback
from datetime import datetime

def log_impl(level, message, error=None, **context):
    """
    Core logging implementation.
    
    Handles:
    - Structured logging
    - Error formatting
    - Context enrichment
    - CloudWatch compatibility
    """
    log_entry = {
        'timestamp': datetime.utcnow().isoformat(),
        'level': level,
        'message': message
    }
    
    # Add error details if present
    if error is not None:
        log_entry['error'] = {
            'type': type(error).__name__,
            'message': str(error),
            'traceback': traceback.format_exc() if level == 'ERROR' else None
        }
    
    # Add context
    if context:
        log_entry['context'] = context
    
    # Print as JSON (CloudWatch ingestion)
    print(json.dumps(log_entry))
    
    return True
```

---

## USAGE PATTERNS

### Pattern 1: Basic Logging

```python
# CORRECT - Via gateway
import gateway

gateway.log_info("User logged in")
gateway.log_error("Database connection failed")
gateway.log_warning("High memory usage detected")
gateway.log_debug("Cache hit for key: user:123")
```

### Pattern 2: Logging with Context

```python
# CORRECT - Structured context
import gateway

gateway.log_info(
    "User action completed",
    user_id="123",
    action="purchase",
    amount=99.99
)
```

### Pattern 3: Error Logging

```python
# CORRECT - Error with exception
import gateway

try:
    risky_operation()
except Exception as e:
    gateway.log_error(
        "Operation failed",
        error=e,
        operation="risky_operation",
        retry_count=3
    )
```

---

## ANTI-PATTERNS

### Anti-Pattern 1: Direct Core Import

```python
# WRONG
from logging_core import log_impl
log_impl('INFO', 'message')

# CORRECT
import gateway
gateway.log_info('message')
```

### Anti-Pattern 2: String Formatting in Call

```python
# WRONG - Pre-formatted (loses structure)
import gateway
gateway.log_info(f"User {user_id} purchased {item}")

# CORRECT - Structured context
import gateway
gateway.log_info(
    "User purchased item",
    user_id=user_id,
    item=item
)
```

---

## DESIGN DECISIONS

### DEC-01: Structured Logging

All logs output as JSON for CloudWatch parsing.

**Rationale:**
- Searchable fields
- Better analytics
- Standard format
- Tool compatibility

### DEC-02: Error Objects

Errors passed as objects, not strings.

**Rationale:**
- Automatic type extraction
- Traceback capture
- Richer context
- Better debugging

---

## VERIFICATION

```
[ ] Gateway wrappers created
[ ] Interface routing created
[ ] Core implementation created
[ ] JSON output verified
[ ] Error handling tested
[ ] Context passing works
```

---

## CROSS-REFERENCES

**Architecture:**
- ARCH-01: Gateway Trinity
- ARCH-02: Layer Separation

**Gateways:**
- GATE-02: Lazy Import Pattern

**Anti-Patterns:**
- AP-01: Direct Core Import

---

## KEYWORDS

LOGGING, observability, structured, JSON, CloudWatch, errors, context, gateway

---

**END OF FILE**

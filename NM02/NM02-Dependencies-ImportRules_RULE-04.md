# NM02-Dependencies-ImportRules_RULE-04.md - RULE-04

# RULE-04: Flat File Structure

**Category:** NM02 - Dependencies
**Topic:** Import Rules
**Priority:** 🟢 Medium
**Status:** Active
**Created:** 2024-04-15
**Last Updated:** 2025-10-24

---

## Summary

All core architecture files should be in the root directory (flat structure) with one exception: home_assistant/ subdirectory. This simplifies imports and has proven effective at scale.

---

## Context

SUGA architecture uses a flat file structure rather than nested subdirectories. This is a historical design decision that has proven simple and effective.

**Why This Rule Exists:**
- Simple import paths
- Easy to find files
- Proven to work at scale
- No complex directory navigation

---

## Content

### The Rule

**File Structure:**
```
project_root/
├── gateway.py
├── gateway_core.py
├── gateway_wrappers.py
├── interface_cache.py
├── cache_core.py
├── cache_manager.py
├── interface_logging.py
├── logging_core.py
├── interface_http.py
├── http_client_core.py
├── lambda_function.py
├── lambda_diagnostics.py
├── home_assistant/          ← ONLY subdirectory
│   ├── __init__.py
│   ├── ha_core.py
│   ├── ha_alexa.py
│   └── ha_websocket.py
└── tests/                   ← Test directory (separate)
```

### Why Flat Structure?

**1. Simple Imports:**
```python
# Flat structure
from gateway import cache_get
from cache_core import CacheManager

# With subdirectories (more complex)
from core.interfaces.cache.gateway_bindings import cache_get
from core.implementations.cache.manager import CacheManager
```

**2. Easy File Discovery:**
```
Find cache implementation:
Flat: Look in root, find cache_core.py
Nested: Check interfaces/? implementations/? cache/?
```

**3. Proven at Scale:**
- 93 Python files in root directory
- No organizational issues
- Clear naming prevents confusion
- Works well in practice

### Exception: home_assistant/

**Why Subdirectory Allowed:**
- Home Assistant is separate subsystem
- Many HA-specific files (10+)
- Logical grouping helps
- Still accessed via extension facade

**Home Assistant Structure:**
```
home_assistant/
├── __init__.py             - Package initialization
├── ha_core.py             - Core HA logic
├── ha_alexa.py            - Alexa integration
├── ha_websocket.py        - WebSocket handling
├── ha_features.py         - Feature implementations
├── ha_config.py           - HA-specific config
├── ha_managers.py         - Manager classes
└── ha_tests.py            - HA-specific tests
```

**Access Pattern:**
```python
# External code imports the facade (root level)
from homeassistant_extension import process_alexa_request

# homeassistant_extension.py imports from subdirectory
from home_assistant.ha_core import handle_request
from home_assistant.ha_alexa import process_alexa_directive
```

### File Naming Conventions

**Pattern:** `<interface>_<purpose>.py`

**Examples:**
- `interface_cache.py` - Interface router
- `cache_core.py` - Core implementation
- `cache_manager.py` - Management
- `cache_operations.py` - Operations
- `cache_types.py` - Type definitions

**Naming Prevents Conflicts:**
- Clear purpose from filename
- Easy to identify interface
- Related files group alphabetically

### Benefits of Flat Structure

**1. IDE Navigation:**
```
Flat structure:
- Type "cache" → see all cache files
- No directory navigation needed
- Quick file switching

Nested structure:
- Navigate to interfaces/cache/
- Then to implementations/cache/
- Multiple clicks required
```

**2. Import Simplicity:**
```python
# Flat (simple)
from cache_core import CacheManager
from logging_core import log_info

# Nested (complex)
from core.implementations.cache.manager import CacheManager
from core.implementations.logging.operations import log_info
```

**3. Refactoring Ease:**
```
Move function between files:
Flat: Update one import statement
Nested: Update full path including directories
```

---

## Related Topics

- **NM04-DEC-06**: Why flat structure chosen (design decision)
- **NM04-DEC-08**: Home Assistant subdirectory justified
- **NM01-ARCH-07**: File organization patterns

---

## Keywords

flat file structure, file organization, no subdirectories, simple imports, naming conventions, home_assistant exception

---

## Version History

- **2025-10-24**: Atomized from NM02-RULES-Import, migrated to v3.1.0 format
- **2024-04-15**: Original content in NM02-RULES-Import Rules and Validation

---

**File:** `NM02-Dependencies-ImportRules_RULE-04.md`
**Location:** `/nmap/NM02/`
**End of Document**

---

# NM02-Dependencies-ImportRules_RULE-05.md - RULE-05

# RULE-05: Lambda Entry Point Restrictions

**Category:** NM02 - Dependencies
**Topic:** Import Rules
**Priority:** 🟡 High
**Status:** Active
**Created:** 2024-04-15
**Last Updated:** 2025-10-24

---

## Summary

Lambda entry point (lambda_function.py) has strict import restrictions: MUST import only from gateway.py, MUST NOT import interface routers or implementation files, and MUST handle errors gracefully.

---

## Context

The Lambda entry point is the bridge between AWS Lambda runtime and our architecture. It must follow strict rules to maintain architectural integrity and system reliability.

**Why This Rule Exists:**
- Enforces architectural boundaries at entry point
- Ensures error handling at system boundary
- Prevents bypassing gateway layer
- Maintains clean separation of concerns

---

## Content

### The Restrictions

**Lambda Entry Point MUST:**
- ✅ Import only from `gateway.py`
- ✅ Handle all errors gracefully
- ✅ Return proper Lambda response format
- ✅ Log all invocations

**Lambda Entry Point MUST NOT:**
- ❌ Import interface routers (`interface_*.py`)
- ❌ Import implementation files (`*_core.py`)
- ❌ Import internal operations (`*_operations.py`)
- ❌ Allow uncaught exceptions to propagate

### Correct Lambda Entry Point

```python
# lambda_function.py - CORRECT PATTERN
from gateway import (
    log_info,
    log_error,
    cache_get,
    http_post,
    get_config
)
from homeassistant_extension import process_alexa_request

def lambda_handler(event, context):
    """
    Lambda entry point - follows all restrictions
    """
    try:
        # Log invocation
        log_info(f"Lambda invoked: {context.request_id}")
        
        # Get configuration
        config = get_config("system_config")
        
        # Check cache
        cache_key = f"request_{event['requestId']}"
        cached_response = cache_get(cache_key)
        if cached_response:
            log_info("Returning cached response")
            return cached_response
        
        # Process request
        response = process_alexa_request(event, config)
        
        # Cache response
        cache_set(cache_key, response, ttl=60)
        
        return response
        
    except KeyError as e:
        log_error(f"Missing required field: {e}")
        return {
            "statusCode": 400,
            "body": {"error": "Invalid request format"}
        }
        
    except Exception as e:
        log_error(f"Lambda error: {e}")
        return {
            "statusCode": 500,
            "body": {"error": "Internal error"}
        }
```

### Incorrect Patterns

**Violation 1: Importing Interface Router**
```python
# ❌ WRONG
from interface_cache import execute_cache_operation

def lambda_handler(event, context):
    execute_cache_operation("get", key="mykey")
```

**Violation 2: Importing Core Implementation**
```python
# ❌ WRONG
from cache_core import get_value

def lambda_handler(event, context):
    value = get_value("mykey")
```

**Violation 3: Uncaught Exceptions**
```python
# ❌ WRONG - No error handling
def lambda_handler(event, context):
    # This can raise exceptions that crash Lambda
    response = process_request(event)
    return response
```

**Violation 4: Importing Internal Operations**
```python
# ❌ WRONG
from cache_operations import validate_key

def lambda_handler(event, context):
    validate_key(event['key'])
```

### Error Handling Requirements

**Must Handle:**
- KeyError (missing fields in event)
- ValueError (invalid data)
- TypeError (wrong data types)
- ConnectionError (HTTP/WebSocket failures)
- TimeoutError (operation timeouts)
- Generic Exception (catch-all)

**Error Response Format:**
```python
{
    "statusCode": 400/500,  # 400 for client errors, 500 for server errors
    "body": {
        "error": "Human-readable error message",
        "requestId": context.request_id  # Include for debugging
    }
}
```

### Lambda Response Format

**Success Response:**
```python
{
    "statusCode": 200,
    "body": {
        "result": "...",
        "data": {...}
    }
}
```

**Error Response:**
```python
{
    "statusCode": 400/500,
    "body": {
        "error": "Error description"
    }
}
```

### Why These Restrictions?

**1. Architectural Integrity:**
```
Lambda should see:
    Gateway API (stable, documented)

Lambda should NOT see:
    Internal interfaces (unstable, implementation details)
```

**2. Error Containment:**
```
Without error handling:
    Exception → Lambda crash → AWS retry → More errors

With error handling:
    Exception → Logged → Graceful response → User informed
```

**3. Maintainability:**
```
When internal code changes:
    Gateway API stable → Lambda unaffected
    
If Lambda uses internals:
    Internal change → Lambda breaks → Deploy required
```

### Related Entry Points

**Same rules apply to:**
- `lambda_diagnostics.py` - Health check entry point
- `lambda_emergency.py` - Emergency access entry point
- Any other Lambda handlers

**Example Diagnostics Entry Point:**
```python
# lambda_diagnostics.py - CORRECT
from gateway import (
    log_info,
    log_error,
    system_health_check
)

def diagnostic_handler(event, context):
    try:
        log_info("Diagnostics requested")
        health = system_health_check()
        return {
            "statusCode": 200,
            "body": health
        }
    except Exception as e:
        log_error(f"Diagnostics error: {e}")
        return {
            "statusCode": 500,
            "body": {"error": str(e)}
        }
```

---

## Related Topics

- **RULE-03**: External code imports gateway only
- **NM01-ARCH-06**: Lambda entry point architecture
- **NM03-ERROR-02**: Error propagation patterns
- **NM05-AP-05**: Entry point violations (anti-pattern)

---

## Keywords

Lambda entry point, import restrictions, error handling, gateway API, entry point rules, Lambda handler

---

## Version History

- **2025-10-24**: Atomized from NM02-RULES-Import, migrated to v3.1.0 format
- **2024-04-15**: Original content in NM02-RULES-Import Rules and Validation

---

**File:** `NM02-Dependencies-ImportRules_RULE-05.md`
**Location:** `/nmap/NM02/`
**End of Document**

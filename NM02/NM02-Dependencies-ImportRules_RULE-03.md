# NM02-Dependencies-ImportRules_RULE-03.md - RULE-03

# RULE-03: External Code Imports Gateway Only

**Category:** NM02 - Dependencies
**Topic:** Import Rules
**Priority:** 🔴 Critical
**Status:** Active
**Created:** 2024-04-15
**Last Updated:** 2025-10-24

---

## Summary

External code (Lambda entry points, extensions) must import only from gateway.py, never from internal interfaces or core files. This enforces architectural boundaries and maintains system integrity.

---

## Context

Lambda functions and other external code sit outside the core SUGA architecture. They should only interact with the system through the official gateway API, not bypass it to access internals.

**Why This Rule Exists:**
- Enforces architectural boundaries
- Prevents tight coupling to internals
- Enables internal refactoring without breaking external code
- Makes system boundaries clear

---

## Content

### What is "External Code"?

**External Code Includes:**
- `lambda_function.py` - Main Lambda entry point
- `lambda_diagnostics.py` - Diagnostics entry point
- `lambda_emergency.py` - Emergency access point
- `homeassistant_extension.py` - High-level extension facade
- Any code that consumes the architecture (not part of it)

**Internal Code (Not External):**
- `interface_*.py` - Interface routers
- `*_core.py` - Core implementations
- `*_operations.py` - Operations
- `gateway.py`, `gateway_core.py` - Gateway itself

### The Rule

**✅ CORRECT Pattern (External Code):**
```python
# In lambda_function.py
from gateway import log_info, cache_get, http_post
from homeassistant_extension import process_alexa_request

def lambda_handler(event, context):
    log_info("Request received")
    
    cached = cache_get("cached_response")
    if cached:
        return cached
    
    response = process_alexa_request(event)
    return response
```

**❌ WRONG Pattern (External Code):**
```python
# In lambda_function.py
from interface_cache import execute_cache_operation  # VIOLATION!
from cache_core import perform_operation           # VIOLATION!
from logging_core import log_message               # VIOLATION!

def lambda_handler(event, context):
    # Directly accessing internals - breaks boundaries!
    log_message("Request received")
    result = perform_operation(event)
    return result
```

### Why Gateway Only?

**1. Architectural Boundaries:**
```
External Code
    ↓ (only gateway.py)
Gateway Layer
    ↓
Interface Layer
    ↓
Implementation Layer

External code should not skip layers!
```

**2. Internal Refactoring Freedom:**
```python
# If external code imports gateway.cache_get:
from gateway import cache_get

# We can change internals freely:
# - Rename cache_core.py
# - Split cache operations
# - Change cache implementation
# External code unaffected!

# If external code imports cache_core directly:
from cache_core import get_value

# Changing cache_core breaks external code!
```

**3. API Stability:**
```
Gateway provides stable API:
- Function signatures don't change
- Behavior is documented
- Breaking changes are versioned

Internal code can change:
- Refactoring is common
- Implementation details evolve
- No stability guarantees
```

### Real-World Examples

**Lambda Function (Correct):**
```python
# In lambda_function.py
from gateway import (
    log_info,
    log_error,
    cache_get,
    cache_set,
    http_post,
    get_config
)
from homeassistant_extension import process_alexa_request

def lambda_handler(event, context):
    try:
        log_info(f"Lambda invoked: {event['requestId']}")
        
        # All through gateway
        config = get_config("system_config")
        cached = cache_get(f"request_{event['requestId']}")
        
        if cached:
            return cached
        
        response = process_alexa_request(event, config)
        cache_set(f"request_{event['requestId']}", response, ttl=60)
        
        return response
        
    except Exception as e:
        log_error(f"Lambda error: {e}")
        return {"statusCode": 500}
```

**Diagnostics Entry Point (Correct):**
```python
# In lambda_diagnostics.py
from gateway import (
    log_info,
    system_health_check,
    get_metrics,
    cache_statistics
)

def diagnostic_handler(event, context):
    log_info("Diagnostics requested")
    
    health = system_health_check()
    metrics = get_metrics()
    cache_stats = cache_statistics()
    
    return {
        "health": health,
        "metrics": metrics,
        "cache": cache_stats
    }
```

**Extension Facade (Correct):**
```python
# In homeassistant_extension.py
from gateway import (
    log_info,
    http_post,
    ws_connect,
    cache_get,
    validate_token
)

def process_alexa_request(event, config):
    """High-level facade for Alexa requests"""
    log_info("Processing Alexa request")
    
    # All interactions through gateway
    if not validate_token(event['token']):
        return {"error": "Invalid token"}
    
    cached = cache_get(f"alexa_{event['requestId']}")
    if cached:
        return cached
    
    response = http_post(
        url=config['ha_url'],
        data=event['request']
    )
    
    return response
```

### Identifying External vs Internal Code

**External Code Characteristics:**
- Entry point for Lambda
- Called by AWS Lambda runtime
- Consumer of architecture
- Wants stable API
- Located at project root

**Internal Code Characteristics:**
- Part of architecture
- Implements interfaces
- Provider of functionality
- Can change frequently
- Located in architecture layers

**Quick Test:**
```
Question: Is this external code?

Does AWS Lambda call it directly? -> External
Does gateway.py import it? -> Internal
Does it implement an interface? -> Internal
Does it provide entry point? -> External
Is it in architecture layers? -> Internal
```

### Common Violations

**Violation 1: Lambda importing interface router**
```python
# ❌ WRONG
from interface_cache import execute_cache_operation

# ✅ CORRECT
from gateway import cache_get
```

**Violation 2: Lambda importing core**
```python
# ❌ WRONG
from cache_core import perform_operation

# ✅ CORRECT
from gateway import cache_get
```

**Violation 3: Extension importing internal**
```python
# ❌ WRONG (in homeassistant_extension.py)
from http_client_core import make_request

# ✅ CORRECT
from gateway import http_post
```

---

## Related Topics

- **RULE-01**: Cross-interface imports via gateway
- **NM01-ARCH-06**: Lambda entry point architecture
- **NM05-AP-05**: External code violating boundaries (anti-pattern)
- **DEC-01**: SUGA pattern enforces boundaries

---

## Keywords

external code, Lambda entry points, gateway API, architectural boundaries, import restrictions, API stability

---

## Version History

- **2025-10-24**: Atomized from NM02-RULES-Import, migrated to v3.1.0 format
- **2024-04-15**: Original content in NM02-RULES-Import Rules and Validation

---

**File:** `NM02-Dependencies-ImportRules_RULE-03.md`
**Location:** `/nmap/NM02/`
**End of Document**

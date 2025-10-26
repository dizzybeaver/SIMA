# NEURAL_MAP_01: Core Architecture
# SUGA-ISP Neural Memory System - System Structure & Components
# Version: 1.1.0 | Phase: 1 Foundation | Enhanced with REF IDs

---

**FILE STATISTICS:**
- Sections: 20 (6 architecture + 12 interfaces + 2 additional)
- Reference IDs: 20
- Cross-references: 45+
- Priority Breakdown: Critical=6, High=10, Medium=4
- Last Updated: 2025-10-20
- Version: 1.1.0 (Enhanced with REF IDs)

---

## Purpose

This file documents WHAT EXISTS in the SUGA-ISP architecture - the complete structure of the gateway, all 12 interfaces, routing patterns, and component relationships.

---

## PART 1: GATEWAY CORE STRUCTURE

### The Gateway Trinity (3 Core Files)
**REF:** NM01-ARCH-01
**PRIORITY:** 🔴 CRITICAL
**TAGS:** gateway, SUGA, architecture, trinity, core-files
**KEYWORDS:** gateway files, master facade, routing engine, wrappers
**RELATED:** NM04-DEC-01, NM02-DEP-01, NM07-DT-01

```
gateway.py (Master Facade)
├─ Purpose: Single import point for all users
├─ Exports: All wrapper functions + core functions
├─ Pattern: Aggregates gateway_core + gateway_wrappers
└─ Usage: from gateway import cache_get, log_info, http_post

gateway_core.py (Routing Engine)
├─ Purpose: Route operations to interface routers
├─ Registry: _INTERFACE_ROUTERS (12 interfaces)
├─ Function: execute_operation(interface, operation, **kwargs)
├─ Pattern: Pattern-based routing (interface → router)
└─ Fast Path: Optional caching for frequent operations

gateway_wrappers.py (Convenience Layer)
├─ Purpose: User-friendly function names
├─ Pattern: Wraps execute_operation() calls
├─ Count: 90+ wrapper functions
└─ Example: cache_get() → execute_operation(CACHE, 'get', ...)
```

**REAL-WORLD USAGE:**
User: "Where do I import gateway functions from?"
Claude searches: "gateway trinity import"
Finds: NM01-ARCH-01
Response: "Import from gateway.py - the master facade that aggregates all gateway functions."

---

### Gateway Core Function Signature
**REF:** NM01-ARCH-02
**PRIORITY:** 🔴 CRITICAL
**TAGS:** gateway-core, execute_operation, routing, function-signature
**KEYWORDS:** gateway function, operation execution, routing signature
**RELATED:** NM01-ARCH-01, NM01-ARCH-03, NM03-FLOW-01

```python
def execute_operation(
    interface: GatewayInterface,  # Which interface
    operation: str,               # Which operation
    **kwargs                      # Operation parameters
) -> Any:                         # Return value
    """
    Universal operation executor.
    ALL cross-interface communication flows through here.
    """
```

**Gateway Interface Enumeration:**

```python
class GatewayInterface(Enum):
    """All 12 system interfaces."""
    CACHE = "cache"
    LOGGING = "logging"
    SECURITY = "security"
    METRICS = "metrics"
    CONFIG = "config"
    SINGLETON = "singleton"
    INITIALIZATION = "initialization"
    HTTP_CLIENT = "http_client"
    WEBSOCKET = "websocket"
    CIRCUIT_BREAKER = "circuit_breaker"
    UTILITY = "utility"
    DEBUG = "debug"
```

**Gateway Registry Pattern:**

```python
# Pattern-based routing (NEW APPROACH)
_INTERFACE_ROUTERS = {
    GatewayInterface.CACHE: ('interface_cache', 'execute_cache_operation'),
    GatewayInterface.LOGGING: ('interface_logging', 'execute_logging_operation'),
    # ... 10 more interfaces
}

# This replaced 100+ individual operation mappings
# Reduced registry from ~100 lines to 12 lines (90% reduction)
```

**REAL-WORLD USAGE:**
User: "How does the gateway route operations?"
Claude searches: "execute_operation routing"
Finds: NM01-ARCH-02
Response: "Gateway uses execute_operation() which routes based on interface enum to the appropriate router module."

---

## PART 2: THE 12 INTERFACES (Complete Reference)

### Interface 1: CACHE
**REF:** NM01-INT-01
**PRIORITY:** 🟡 HIGH
**TAGS:** CACHE, interface, caching, storage, TTL
**KEYWORDS:** cache interface, data caching, temporary storage
**RELATED:** NM04-DEC-05, NM04-DEC-09, NM06-BUG-01, NM02-DEP-03

```
Router: interface_cache.py
Core: cache_core.py
Purpose: Data caching and temporary storage
Pattern: Dictionary-based dispatch

Operations (8 total):
├─ get: Retrieve cached value
├─ set: Store value with optional TTL
├─ exists: Check if key exists
├─ delete: Remove key from cache
├─ clear: Remove all entries
├─ cleanup_expired: Remove expired entries
├─ get_stats: Return cache statistics
└─ get_metadata: Return key metadata

Gateway Wrappers:
├─ cache_get(key, default=None)
├─ cache_set(key, value, ttl=None, source_module=None)
├─ cache_exists(key)
├─ cache_delete(key)
├─ cache_clear()
└─ cache_stats()

Dependencies:
├─ Uses: LOGGING (for errors), METRICS (for tracking)
└─ Used by: HTTP_CLIENT, CONFIG, SECURITY

State Storage: _CACHE_STORE (module-level dict)

Special Patterns:
├─ Sentinel objects for CACHE_MISS detection
├─ Sentinel sanitization at router layer
└─ TTL-based expiration tracking

Design Decisions:
├─ No threading locks (single-threaded Lambda)
└─ Sanitize sentinels before returning to caller
```

**REAL-WORLD USAGE:**
User: "How do I cache data temporarily?"
Claude searches: "cache interface storage"
Finds: NM01-INT-01
Response: "Use gateway.cache_set(key, value, ttl=300) to cache with 5-minute expiration."

---

### Interface 2: LOGGING
**REF:** NM01-INT-02
**PRIORITY:** 🔴 CRITICAL
**TAGS:** LOGGING, interface, logging, diagnostics, CloudWatch
**KEYWORDS:** logging interface, system logging, print stdout
**RELATED:** NM04-DEC-08, NM02-DEP-01, NM04-DEC-19

```
Router: interface_logging.py
Core: logging_core.py
Purpose: System logging and diagnostics
Pattern: Dictionary-based dispatch

Operations (7 total):
├─ log_info: Information logging
├─ log_error: Error logging with exception
├─ log_warning: Warning logging
├─ log_debug: Debug logging
├─ log_operation_start: Operation start marker
├─ log_operation_success: Operation success with timing
└─ log_operation_failure: Operation failure with error

Gateway Wrappers:
├─ log_info(message, **kwargs)
├─ log_error(message, error=None, **kwargs)
├─ log_warning(message, **kwargs)
├─ log_debug(message, **kwargs)
├─ log_operation_start(operation, **kwargs)
├─ log_operation_success(operation, duration_ms, **kwargs)
└─ log_operation_failure(operation, error, **kwargs)

Dependencies:
├─ Uses: None (base infrastructure layer)
└─ Used by: Almost all interfaces

Special Patterns:
├─ Uses print() for Lambda CloudWatch
├─ NOT using Python 'logging' module
└─ JSON-formatted structured logging

Design Decisions:
└─ print() instead of logging module (Lambda best practice)
```

**REAL-WORLD USAGE:**
User: "How do I log errors in Lambda?"
Claude searches: "logging interface errors"
Finds: NM01-INT-02
Response: "Use gateway.log_error(message, error=exception_obj) - logs to CloudWatch via print()."

---

### Interface 3: SECURITY
**REF:** NM01-INT-03
**PRIORITY:** 🟡 HIGH
**TAGS:** SECURITY, interface, validation, encryption, sanitization
**KEYWORDS:** security interface, input validation, encryption
**RELATED:** NM04-DEC-11, NM05-AP-17, NM05-AP-18

```
Router: interface_security.py
Core: security_core.py
Purpose: Input validation, encryption, hashing
Pattern: Dictionary-based dispatch

Operations (10 total):
├─ validate_request: Request validation
├─ validate_token: Token validation
├─ encrypt_data: Data encryption
├─ decrypt_data: Data decryption
├─ generate_correlation_id: Unique ID generation
├─ validate_string: String validation
├─ validate_email: Email validation
├─ validate_url: URL validation
├─ hash_data: Data hashing
├─ verify_hash: Hash verification
└─ sanitize_input: Input sanitization

Gateway Wrappers:
├─ validate_request(request)
├─ validate_string(s)
├─ validate_email(email)
├─ validate_url(url)
├─ hash_data(data)
├─ sanitize_input(data)
└─ ... (10 total wrappers)

Dependencies:
├─ Uses: LOGGING (validation failures)
└─ Used by: HTTP_CLIENT, CONFIG, WEBSOCKET

Special Patterns:
└─ Correlation ID generation for request tracing

Design Decisions:
└─ Validation-first approach (fail fast)
```

---

### Interface 4: METRICS
**REF:** NM01-INT-04
**PRIORITY:** 🟢 MEDIUM
**TAGS:** METRICS, interface, telemetry, monitoring, performance
**KEYWORDS:** metrics interface, telemetry tracking, monitoring
**RELATED:** NM02-DEP-03, NM01-INT-01

```
Router: interface_metrics.py
Core: metrics_core.py
Purpose: Telemetry, monitoring, performance tracking
Pattern: Dictionary-based dispatch

Operations (7 total):
├─ record: Record generic metric
├─ increment: Increment counter
├─ get_stats: Get metrics statistics
├─ record_operation: Record operation metric with timing
├─ record_error: Record error metric
├─ record_cache: Record cache hit/miss
└─ record_api: Record API call metric

Gateway Wrappers:
├─ record_metric(name, value, **kwargs)
├─ increment_counter(name, value=1, **kwargs)
├─ get_metrics_stats()
├─ record_operation_metric(operation, duration_ms, success, **kwargs)
├─ record_error_metric(error_type, **kwargs)
├─ record_cache_metric(operation, hit, **kwargs)
└─ record_api_metric(api, duration_ms, **kwargs)

Dependencies:
├─ Uses: LOGGING (for metric errors)
└─ Used by: CACHE, HTTP_CLIENT, CIRCUIT_BREAKER, WEBSOCKET

State Storage: In-memory metric counters

Design Decisions:
└─ Lightweight metrics (no external services due to free tier)
```

---

### Interface 5: CONFIG
**REF:** NM01-INT-05
**PRIORITY:** 🟡 HIGH
**TAGS:** CONFIG, interface, configuration, parameters, multi-tier
**KEYWORDS:** config interface, configuration management, parameters
**RELATED:** NM04-DEC-12, NM02-DEP-03

```
Router: interface_config.py
Core: config_core.py
Purpose: Configuration management and parameter storage
Pattern: Dictionary-based dispatch

Operations (9 total):
├─ get_parameter: Get configuration value
├─ set_parameter: Set configuration value
├─ get_category: Get configuration category
├─ reload: Reload configuration
├─ switch_preset: Switch configuration preset
├─ get_state: Get configuration state
├─ load_environment: Load from environment variables
├─ load_file: Load from file
└─ validate_all: Validate all configuration

Gateway Wrappers:
├─ get_config(key, default=None)
├─ set_config(key, value)
├─ get_config_category(category)
├─ reload_config()
├─ switch_config_preset(preset)
└─ ... (9 total wrappers)

Dependencies:
├─ Uses: LOGGING, CACHE (config caching), SECURITY (validation)
└─ Used by: All interfaces (for configuration)

Special Patterns:
├─ Multi-tier configuration (minimum, standard, maximum, user)
└─ Configuration presets for different deployment modes

Design Decisions:
└─ Cache frequently accessed config to reduce lookups
```

---

### Interface 6: SINGLETON
**REF:** NM01-INT-06
**PRIORITY:** 🟢 MEDIUM
**TAGS:** SINGLETON, interface, storage, stateful, factory
**KEYWORDS:** singleton interface, singleton storage, factory pattern
**RELATED:** NM02-DEP-02

```
Router: interface_singleton.py
Core: singleton_core.py
Purpose: Singleton object storage and management
Pattern: Dictionary-based dispatch

Operations (5 total):
├─ get: Get singleton instance
├─ has: Check if singleton exists
├─ delete: Remove singleton
├─ clear: Clear all singletons
└─ get_stats: Get singleton statistics

Gateway Wrappers:
├─ singleton_get(key, factory=None)
├─ singleton_has(key)
├─ singleton_delete(key)
├─ singleton_clear()
└─ singleton_stats()

Dependencies:
├─ Uses: LOGGING
└─ Used by: Various interfaces for stateful objects

State Storage: _SINGLETON_STORE (module-level dict)

Design Decisions:
└─ Factory pattern for lazy singleton creation
```

---

### Interface 7: INITIALIZATION
**REF:** NM01-INT-07
**PRIORITY:** 🟢 MEDIUM
**TAGS:** INITIALIZATION, interface, startup, cold-start, system-init
**KEYWORDS:** initialization interface, system startup, cold start
**RELATED:** NM02-DEP-04, NM04-DEC-14

```
Router: interface_initialization.py
Core: initialization_core.py
Purpose: System initialization and startup management
Pattern: Dictionary-based dispatch

Operations (4 total):
├─ initialize_system: Full system initialization
├─ get_status: Get initialization status
├─ set_flag: Set initialization flag
└─ get_flag: Get initialization flag

Gateway Wrappers:
├─ initialize_system()
├─ get_initialization_status()
├─ set_initialization_flag(flag, value)
└─ get_initialization_flag(flag)

Dependencies:
├─ Uses: LOGGING, CONFIG
└─ Used by: lambda_function.py (on cold start)

Design Decisions:
└─ Lazy initialization (only initialize what's needed)
```

---

### Interface 8: HTTP_CLIENT
**REF:** NM01-INT-08
**PRIORITY:** 🟡 HIGH
**TAGS:** HTTP_CLIENT, interface, HTTP, requests, urllib, API
**KEYWORDS:** HTTP interface, HTTP requests, API calls, urllib
**RELATED:** NM04-DEC-10, NM02-DEP-04, NM03-FLOW-02

```
Router: interface_http.py
Core: http_client_core.py
Purpose: HTTP request handling
Pattern: Dictionary-based dispatch

Operations (7 total):
├─ request: Generic HTTP request
├─ get: HTTP GET request
├─ post: HTTP POST request
├─ put: HTTP PUT request
├─ delete: HTTP DELETE request
├─ get_state: Get HTTP client state
└─ reset_state: Reset HTTP client state

Gateway Wrappers:
├─ http_request(method, url, **kwargs)
├─ http_get(url, **kwargs)
├─ http_post(url, data=None, **kwargs)
├─ http_put(url, data=None, **kwargs)
├─ http_delete(url, **kwargs)
├─ get_http_client_state()
└─ reset_http_client_state()

Dependencies:
├─ Uses: LOGGING, SECURITY (validation), CACHE (response caching)
└─ Used by: homeassistant_extension, external integrations

Special Patterns:
├─ Uses urllib (stdlib), NOT requests library
└─ Response caching for GET requests

Design Decisions:
├─ stdlib only (urllib) for Lambda compatibility
└─ Optional response caching to reduce API calls
```

**REAL-WORLD USAGE:**
User: "How do I make HTTP requests in Lambda?"
Claude searches: "HTTP interface requests"
Finds: NM01-INT-08
Response: "Use gateway.http_post(url, data) - uses urllib (stdlib), automatically logs and caches responses."

---

### Interface 9: WEBSOCKET
**REF:** NM01-INT-09
**PRIORITY:** 🟢 MEDIUM
**TAGS:** WEBSOCKET, interface, websocket, real-time, connections
**KEYWORDS:** websocket interface, websocket connections, real-time
**RELATED:** NM02-DEP-04, NM01-ARCH-05

```
Router: interface_websocket.py
Core: websocket_core.py
Purpose: WebSocket connection management
Pattern: Dictionary-based dispatch

Operations (5 total):
├─ connect: Establish WebSocket connection
├─ send: Send message
├─ receive: Receive message
├─ close: Close connection
└─ request: Complete request-response cycle

Gateway Wrappers:
├─ websocket_connect(url, **kwargs)
├─ websocket_send(connection, message)
├─ websocket_receive(connection)
├─ websocket_close(connection)
└─ websocket_request(url, message)

Dependencies:
├─ Uses: LOGGING, SECURITY (validation)
└─ Used by: homeassistant_extension (for Home Assistant websocket)

Design Decisions:
└─ Stateful connections managed within Lambda execution context
```

---

### Interface 10: CIRCUIT_BREAKER
**REF:** NM01-INT-10
**PRIORITY:** 🟡 HIGH
**TAGS:** CIRCUIT_BREAKER, interface, resilience, failure-protection, state-machine
**KEYWORDS:** circuit breaker, failure protection, resilience pattern
**RELATED:** NM02-DEP-03, NM03-PATH-02

```
Router: interface_circuit_breaker.py
Core: circuit_breaker_core.py
Purpose: Failure protection and resilience
Pattern: Dictionary-based dispatch

Operations (4 total):
├─ get: Get circuit breaker state
├─ execute: Execute with circuit breaker protection
├─ get_all: Get all circuit breaker states
└─ reset_all: Reset all circuit breakers

Gateway Wrappers:
├─ get_circuit_breaker(name)
├─ execute_with_circuit_breaker(name, function, **kwargs)
├─ get_all_circuit_breaker_states()
└─ reset_all_circuit_breakers()

Dependencies:
├─ Uses: LOGGING, METRICS (failure tracking)
└─ Used by: HTTP_CLIENT, WEBSOCKET (for external calls)

Special Patterns:
├─ State machine: closed → open → half-open → closed
└─ Failure threshold-based state transitions

Design Decisions:
└─ Protects against cascading failures in external services
```

---

### Interface 11: UTILITY
**REF:** NM01-INT-11
**PRIORITY:** 🟢 MEDIUM
**TAGS:** UTILITY, interface, helpers, utilities, generic-functions
**KEYWORDS:** utility interface, helper functions, common utilities
**RELATED:** NM02-DEP-02, NM07-DT-02

```
Router: interface_utility.py
Core: utility_core.py
Purpose: Helper functions and common utilities
Pattern: Dictionary-based dispatch

Operations (5 total):
├─ format_response: Format API response
├─ parse_json: Parse JSON safely
├─ safe_get: Safe dictionary access
├─ generate_uuid: Generate UUID
└─ get_timestamp: Get current timestamp

Gateway Wrappers:
├─ format_response(success, message, data=None)
├─ parse_json(json_string)
├─ safe_get(dictionary, key, default=None)
├─ generate_uuid()
└─ get_timestamp()

Dependencies:
├─ Uses: None (utility layer)
└─ Used by: All interfaces

Design Decisions:
└─ Common helpers centralized to avoid duplication
```

---

### Interface 12: DEBUG
**REF:** NM01-INT-12
**PRIORITY:** 🟢 MEDIUM
**TAGS:** DEBUG, interface, diagnostics, health-check, system-validation
**KEYWORDS:** debug interface, system diagnostics, health checks
**RELATED:** NM02-DEP-04, NM03-FLOW-01

```
Router: interface_debug.py
Core: debug_core.py
Purpose: System diagnostics and health checks
Pattern: Dictionary-based dispatch

Operations (5 total):
├─ check_component_health: Check component health
├─ check_gateway_health: Check gateway health
├─ diagnose_system_health: Full system diagnostic
├─ run_debug_tests: Run debug tests
└─ validate_system_architecture: Validate architecture

Gateway Wrappers:
├─ check_component_health(component)
├─ check_gateway_health()
├─ diagnose_system_health()
├─ run_debug_tests()
└─ validate_system_architecture()

Dependencies:
├─ Uses: LOGGING, All interfaces (for health checks)
└─ Used by: lambda_diagnostics.py, lambda_emergency.py

Design Decisions:
└─ Comprehensive system health validation
```

---

## PART 3: INTERFACE ROUTER PATTERN
**REF:** NM01-ARCH-03
**PRIORITY:** 🔴 CRITICAL
**TAGS:** router-pattern, interface-router, dispatch, architecture
**KEYWORDS:** router pattern, interface routing, dispatch pattern
**RELATED:** NM04-DEC-03, NM01-ARCH-02, NM05-AP-02

### Standard Router Structure

Every interface router follows this pattern:

```python
"""
interface_<n>.py - <Interface> Router
Router for <Interface> interface operations.
"""

from typing import Any, Dict, Callable

# Import internal implementation (same interface)
from <n>_core import _execute_<op>_implementation

# Import gateway for cross-interface needs
from gateway import log_error

# Dispatch dictionary (O(1) lookup)
_OPERATION_DISPATCH: Dict[str, Callable] = {
    'operation_name': _execute_<op>_implementation,
    # ... more operations
}

def execute_<n>_operation(operation: str, **kwargs) -> Any:
    """
    Route operation to internal implementation.
    Called by gateway_core.execute_operation().
    """
    if operation not in _OPERATION_DISPATCH:
        raise ValueError(f"Unknown operation: {operation}")
    
    handler = _OPERATION_DISPATCH[operation]
    
    try:
        return handler(**kwargs)
    except Exception as e:
        log_error(f"Error in {operation}: {str(e)}", error=e)
        raise

__all__ = ['execute_<n>_operation']
```

### Router Responsibilities

1. **Dispatch operations** to internal implementations
2. **Catch exceptions** and log errors
3. **Import protection** (try/except on imports)
4. **Gateway delegation** for cross-interface needs
5. **Parameter validation** (optional, interface-specific)

### Router Import Rules

```python
# ✅ ALLOWED: Import from same interface
from cache_core import _execute_get_implementation

# ✅ ALLOWED: Import from gateway for cross-interface
from gateway import log_error, record_metric

# ❌ FORBIDDEN: Import from other interface internals
from logging_core import log_info  # WRONG - use gateway

# ❌ FORBIDDEN: Import other interface routers
from interface_logging import execute_logging_operation  # WRONG
```

**REAL-WORLD USAGE:**
User: "What's the standard pattern for interface routers?"
Claude searches: "router pattern interface"
Finds: NM01-ARCH-03
Response: "All routers follow the same pattern: dispatch dictionary, exception catching, gateway delegation for cross-interface."

---

## PART 4: INTERNAL IMPLEMENTATION PATTERN
**REF:** NM01-ARCH-04
**PRIORITY:** 🟡 HIGH
**TAGS:** implementation-pattern, core-files, internal-logic
**KEYWORDS:** core implementation, internal pattern, business logic
**RELATED:** NM01-ARCH-03, NM07-DT-02

### Standard Core Structure

Every internal core file follows this pattern:

```python
"""
<n>_core.py - <Interface> Core Implementation
Business logic for <interface> operations.
"""

from typing import Any, Dict

# Import gateway for cross-interface needs
from gateway import log_info, log_error

# State storage (if needed)
_STATE_STORE: Dict[str, Any] = {}

def _execute_<op>_implementation(**kwargs) -> Any:
    """
    Implementation of <operation>.
    Called by interface router.
    """
    # Implementation logic
    pass

# Export implementation functions
__all__ = [
    '_execute_<op>_implementation',
    # ... more implementations
]
```

### Core Responsibilities

1. **Implement business logic** for operations
2. **Use gateway** for cross-interface communication
3. **Manage state** (if stateful interface)
4. **Return results** or raise exceptions
5. **No direct imports** from other interface internals

---

## PART 5: EXTENSION ARCHITECTURE
**REF:** NM01-ARCH-05
**PRIORITY:** 🟡 HIGH
**TAGS:** extensions, home-assistant, mini-ISP, architecture
**KEYWORDS:** extension architecture, home assistant, mini ISP
**RELATED:** NM04-DEC-17, NM04-DEC-06

### Home Assistant Extension Pattern

```
homeassistant_extension.py (Extension Facade - Mini-ISP)
├─ Purpose: Public API for Home Assistant integration
├─ Pattern: Same ISP pattern as main gateway (smaller scale)
├─ Exports: process_alexa_request, process_google_home, etc.
├─ Imports: gateway (for infrastructure), home_assistant internals
└─ Usage: lambda_function.py imports only this file

home_assistant/ (Internal Directory - EXCEPTION to flat structure)
├─ ha_core.py (Core HA logic)
├─ ha_features.py (Feature implementations)
├─ ha_alexa.py (Alexa skill handler)
├─ ha_google.py (Google Home handler)
└─ ha_websocket.py (HA WebSocket client)

Extension Rules:
├─ Lambda imports: ONLY homeassistant_extension.py
├─ Extension facade imports: gateway + internal HA files
├─ Internal HA files import: Each other + gateway
└─ Pattern: Mini-ISP within larger ISP architecture
```

### Extension Mini-ISP Pattern

```python
# homeassistant_extension.py
from gateway import log_info, http_post, cache_get  # Infrastructure
from home_assistant.ha_core import handle_request  # Internal routing

def process_alexa_request(request):
    """Public API - Extension facade."""
    log_info("Processing Alexa request")  # Via SUGA-ISP
    return handle_request(request)  # Internal routing

# home_assistant/ha_core.py
from home_assistant.ha_features import get_feature  # Intra-extension
from gateway import cache_get  # Via parent extension → SUGA-ISP

def handle_request(request):
    feature = get_feature(request['feature'])  # Same extension
    cached = cache_get("ha_state")  # Via gateway chain
```

---

## PART 6: LAMBDA ENTRY POINT
**REF:** NM01-ARCH-06
**PRIORITY:** 🔴 CRITICAL
**TAGS:** lambda, entry-point, lambda_function, architecture
**KEYWORDS:** lambda entry point, lambda handler, AWS Lambda
**RELATED:** NM01-ARCH-01, NM05-AP-05

### lambda_function.py Structure

```python
"""
lambda_function.py - Lambda Entry Point
AWS Lambda handler - routes requests to appropriate handlers.
"""

# ✅ ALLOWED: Import gateway and extension facades only
from gateway import log_info, log_error, initialize_system
from homeassistant_extension import process_alexa_request

# ❌ FORBIDDEN: Import interface routers or internal files
# from interface_cache import execute_cache_operation  # WRONG
# from cache_core import _execute_get_implementation  # WRONG

def lambda_handler(event, context):
    """
    AWS Lambda entry point.
    Routes requests to appropriate handlers.
    """
    try:
        # Initialize on cold start
        initialize_system()
        
        # Route based on request type
        if event.get('source') == 'alexa':
            return process_alexa_request(event)
        
        # ... more routing logic
        
    except Exception as e:
        log_error("Lambda handler error", error=e)
        return create_error_response(str(e))
```

### Lambda Import Rules

```python
# ✅ ALLOWED: Gateway imports
from gateway import log_info, cache_get, http_post

# ✅ ALLOWED: Extension facade imports  
from homeassistant_extension import process_alexa_request

# ❌ FORBIDDEN: Interface router imports
from interface_cache import anything  # WRONG

# ❌ FORBIDDEN: Internal implementation imports
from cache_core import anything  # WRONG
from logging_core import anything  # WRONG
```

---

## PART 7: PROJECT FILE STRUCTURE
**REF:** NM01-ARCH-07
**PRIORITY:** 🟡 HIGH
**TAGS:** file-structure, flat-structure, architecture, organization
**KEYWORDS:** project structure, file organization, flat structure
**RELATED:** NM04-DEC-06, NM01-ARCH-05

### Flat Structure (Current - No Subdirectories)

```
/
├─ lambda_function.py (Entry point)
├─ lambda_failsafe.py (Independent emergency system - DON'T TOUCH)
├─ lambda_diagnostics.py (Debug helper)
├─ lambda_emergency.py (Debug helper)
│
├─ gateway.py (Master facade)
├─ gateway_core.py (Routing engine)
├─ gateway_wrappers.py (Convenience functions)
│
├─ interface_cache.py (CACHE router)
├─ cache_core.py (CACHE implementation)
│
├─ interface_logging.py (LOGGING router)
├─ logging_core.py (LOGGING implementation)
│
├─ interface_security.py (SECURITY router)
├─ security_core.py (SECURITY implementation)
│
├─ interface_metrics.py (METRICS router)
├─ metrics_core.py (METRICS implementation)
│
├─ interface_config.py (CONFIG router)
├─ config_core.py (CONFIG implementation)
│
├─ interface_singleton.py (SINGLETON router)
├─ singleton_core.py (SINGLETON implementation)
│
├─ interface_initialization.py (INITIALIZATION router)
├─ initialization_core.py (INITIALIZATION implementation)
│
├─ interface_http.py (HTTP_CLIENT router)
├─ http_client_core.py (HTTP_CLIENT implementation)
│
├─ interface_websocket.py (WEBSOCKET router)
├─ websocket_core.py (WEBSOCKET implementation)
│
├─ interface_circuit_breaker.py (CIRCUIT_BREAKER router)
├─ circuit_breaker_core.py (CIRCUIT_BREAKER implementation)
│
├─ interface_utility.py (UTILITY router)
├─ utility_core.py (UTILITY implementation)
│
├─ interface_debug.py (DEBUG router)
├─ debug_core.py (DEBUG implementation)
│
├─ homeassistant_extension.py (Extension facade)
└─ home_assistant/ (EXCEPTION - subdirectory for HA internals)
    ├─ ha_core.py
    ├─ ha_features.py
    ├─ ha_alexa.py
    ├─ ha_google.py
    └─ ha_websocket.py
```

### File Naming Conventions

```
Pattern: interface_<name>.py → Router files
Pattern: <name>_core.py → Implementation files
Pattern: <name>_extension.py → Extension facades
Pattern: lambda_<purpose>.py → Lambda entry points
Exception: home_assistant/ directory (only subdirectory)
```

---

## PART 8: ARCHITECTURAL PRINCIPLES
**REF:** NM01-ARCH-08
**PRIORITY:** 🔴 CRITICAL
**TAGS:** principles, SUGA, ISP, architecture, design
**KEYWORDS:** architectural principles, design principles, core patterns
**RELATED:** NM04-DEC-01, NM04-DEC-02, NM04-DEC-03

### Principle 1: Single Universal Gateway (SUGA)
```
ONE gateway for ALL cross-interface communication
├─ All external code imports from gateway
├─ All cross-interface imports go through gateway
└─ Prevents circular imports by design
```

### Principle 2: ISP Network Topology
```
Interfaces are isolated islands, gateway is the bridge
├─ Interface routers act as firewalls
├─ Internal files can't see other interfaces directly
└─ Clean boundaries, testable modules
```

### Principle 3: Dispatch Dictionary Pattern
```
O(1) operation lookup vs O(n) if/elif chains
├─ Every router uses dispatch dictionary
├─ Gateway uses pattern-based interface routing
└─ Consistent pattern across all layers
```

### Principle 4: Lazy Loading
```
Import only when needed
├─ Gateway lazily imports interface routers
├─ Routers lazily import internal implementations
└─ Reduces cold start time and memory usage
```

### Principle 5: Error Isolation
```
Errors caught at router layer
├─ Routers use try/except for all operations
├─ Errors logged via gateway.log_error
└─ Clean error propagation path
```

---

## INTEGRATION NOTES

### Adding New Interface (Future Expansion)
```
Steps:
1. Add to GatewayInterface enum in gateway_core.py
2. Create interface_<name>.py router file
3. Create <name>_core.py implementation file
4. Add to _INTERFACE_ROUTERS in gateway_core.py
5. Add wrappers to gateway_wrappers.py (optional)
6. Update this neural map file
7. Update NEURAL_MAP_02 dependencies (if applicable)
```

### Adding New Operation to Existing Interface
```
Steps:
1. Add to _OPERATION_DISPATCH in interface_<name>.py
2. Implement in <name>_core.py
3. Add wrapper to gateway_wrappers.py (optional)
4. Update this neural map file
5. Update NEURAL_MAP_03 pathways (if complex)
```

---

## END NOTES

This Core Architecture file documents the complete structure of SUGA-ISP - what exists, how it's organized, and how components relate at a structural level.

For dependency relationships and data flow, see NEURAL_MAP_02 and NEURAL_MAP_03.

---

# EOF

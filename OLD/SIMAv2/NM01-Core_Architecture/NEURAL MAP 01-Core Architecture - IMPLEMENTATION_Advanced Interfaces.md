# NEURAL MAP 01: Core Architecture - IMPLEMENTATION (Advanced Interfaces)

**Purpose:** Advanced feature interface specifications  
**Status:** ✅ ACTIVE  
**Last Updated:** 2025-10-20  
**Parent Index:** NM01-INDEX-Architecture.md

**Contains:** INT-07 through INT-12 (6 advanced feature interfaces)

---

## INT-07: INITIALIZATION Interface

**REF:** NM01-INT-07  
**PRIORITY:** 🟢 MEDIUM  
**TAGS:** INITIALIZATION, interface, startup, cold-start, system-init  
**KEYWORDS:** initialization interface, system startup, cold start  
**RELATED:** NM02-DEP-04, NM04-DEC-14, NM03-PATH-01

### Overview

```
Router: interface_initialization.py
Core: initialization_core.py
Purpose: System initialization and startup management
Pattern: Dictionary-based dispatch
State: Initialization status tracking
```

### Operations (4 total)

```
├─ initialize_system: Full system initialization
├─ ensure_initialized: Ensure component is initialized
├─ get_initialization_status: Get initialization state
└─ reset_initialization: Reset for testing
```

### Gateway Wrappers

```python
# Core operations
initialize_system() -> Dict
ensure_initialized(component: str) -> bool
get_initialization_status() -> Dict
reset_initialization() -> bool  # Testing only
```

### Dependencies

```
Uses: LOGGING, CONFIG, SINGLETON
Used by: lambda_function.py (Lambda handler)
```

### Initialization Sequence

```
Cold Start (first invocation):
1. Load configuration from environment
2. Initialize logging system
3. Warm up critical singletons
4. Verify system health
5. Mark initialization complete

Warm Start (subsequent invocations):
1. Check initialization status
2. Skip if already initialized
3. Only re-initialize if needed
```

### Lazy Initialization Pattern

```python
from gateway import ensure_initialized

# Ensure component initialized before use
def use_http_client():
    ensure_initialized('http_client')
    # Now safe to use HTTP client
    return make_http_request()
```

### Initialization Status

```python
from gateway import get_initialization_status

# Check what's initialized
status = get_initialization_status()
# Returns:
# {
#   'system_initialized': True,
#   'components': {
#     'logging': True,
#     'config': True,
#     'http_client': False
#   },
#   'initialized_at': '2025-10-20T14:30:00Z',
#   'cold_start': True
# }
```

### Design Decisions

```
- Lazy initialization (initialize on demand)
- Graceful degradation (non-critical components can fail)
- Status tracking (know what's initialized)
- Testing support (reset for unit tests)
```

### Usage Example

```python
from gateway import initialize_system, ensure_initialized, get_initialization_status

# Lambda handler pattern
def lambda_handler(event, context):
    # Initialize on cold start
    init_result = initialize_system()
    
    if not init_result['success']:
        return error_response("Initialization failed")
    
    # Process event
    result = process_event(event)
    return result

# Component-specific initialization
def process_api_request():
    # Ensure HTTP client is initialized
    ensure_initialized('http_client')
    
    # Now safe to use
    response = http_post('https://api.example.com', data={...})
    return response

# Check status for debugging
status = get_initialization_status()
log_info("Initialization status", extra=status)
```

**REAL-WORLD USAGE:**
- User: "How do I initialize the system?"
- Claude: "Call `initialize_system()` in your Lambda handler on cold start. For lazy init, use `ensure_initialized(component)` before using a component."

---

## INT-08: HTTP_CLIENT Interface

**REF:** NM01-INT-08  
**PRIORITY:** 🟡 HIGH  
**TAGS:** HTTP_CLIENT, interface, http, api, external-calls  
**KEYWORDS:** http client interface, API calls, external requests  
**RELATED:** NM06-LESS-05, NM03-PATH-04

### Overview

```
Router: interface_http_client.py
Core: http_client_core.py
Purpose: HTTP client for external API calls
Pattern: Dictionary-based dispatch
State: HTTP session singleton
```

### Operations (7 total)

```
├─ get: HTTP GET request
├─ post: HTTP POST request
├─ put: HTTP PUT request
├─ delete: HTTP DELETE request
├─ build_url: Build URL with parameters
├─ build_headers: Build request headers
└─ get_session: Get HTTP session singleton
```

### Gateway Wrappers

```python
# HTTP methods
http_get(url: str, params: Dict = None, headers: Dict = None, timeout: int = None) -> Dict
http_post(url: str, data: Dict = None, json: Dict = None, headers: Dict = None, timeout: int = None) -> Dict
http_put(url: str, data: Dict = None, json: Dict = None, headers: Dict = None, timeout: int = None) -> Dict
http_delete(url: str, headers: Dict = None, timeout: int = None) -> Dict

# Utilities
http_build_url(base_url: str, params: Dict) -> str
http_build_headers(auth_token: str = None, content_type: str = 'application/json') -> Dict
```

### Dependencies

```
Uses: LOGGING, METRICS, CONFIG, CIRCUIT_BREAKER, SINGLETON
Used by: Home Assistant integration, external API integrations
```

### Request/Response Pattern

```python
from gateway import http_get, http_post

# GET request
response = http_get('https://api.example.com/users/123')
# Returns:
# {
#   'success': True,
#   'status_code': 200,
#   'data': {...},
#   'headers': {...},
#   'duration_ms': 245
# }

# POST request with JSON
response = http_post(
    'https://api.example.com/users',
    json={'name': 'John', 'email': 'john@example.com'},
    headers={'Authorization': 'Bearer token'}
)

# Error handling
if not response['success']:
    log_error("API request failed", extra={
        'status_code': response['status_code'],
        'error': response['error']
    })
```

### Timeout Configuration

```python
# Use default timeout from config
response = http_get(url)

# Override timeout for specific request
response = http_get(url, timeout=30)  # 30 seconds

# From config:
# - minimum preset: 5 seconds
# - standard preset: 10 seconds  
# - maximum preset: 30 seconds
```

### Circuit Breaker Integration

```python
# Automatic circuit breaker protection
response = http_get('https://unreliable-api.com/data')

# If API fails repeatedly:
# 1. Circuit breaker opens
# 2. Subsequent requests fail fast
# 3. Avoids cascading failures
# 4. Auto-recovery after timeout
```

### Design Decisions

```
- Singleton session (connection pooling)
- Automatic timeout management
- Circuit breaker integration
- Metrics tracking (duration, status codes)
- Structured error responses
```

### Usage Example

```python
from gateway import http_get, http_post, http_build_url, http_build_headers

# Simple GET
response = http_get('https://api.example.com/data')
if response['success']:
    data = response['data']
else:
    log_error("API error", extra=response)

# GET with parameters
params = {'user_id': 123, 'limit': 10}
url = http_build_url('https://api.example.com/users', params)
response = http_get(url)

# POST with authentication
headers = http_build_headers(auth_token='Bearer xyz123')
response = http_post(
    'https://api.example.com/action',
    json={'action': 'update', 'value': 42},
    headers=headers
)

# Error handling
try:
    response = http_get('https://api.example.com/data')
    if response['status_code'] == 404:
        return not_found_error()
    elif response['status_code'] >= 500:
        return server_error()
    else:
        return process_data(response['data'])
except Exception as e:
    log_error("HTTP request exception", error=e)
    return internal_error()
```

**REAL-WORLD USAGE:**
- User: "How do I make API calls?"
- Claude: "Use `http_get(url)`, `http_post(url, json=data)`, etc. All requests have automatic timeout, circuit breaker protection, and structured error handling."

---

## INT-09: WEBSOCKET Interface

**REF:** NM01-INT-09  
**PRIORITY:** 🟢 MEDIUM  
**TAGS:** WEBSOCKET, interface, websocket, real-time  
**KEYWORDS:** websocket interface, real-time communication  
**RELATED:** NM02-DEP-04

### Overview

```
Router: interface_websocket.py
Core: websocket_core.py
Purpose: WebSocket connection management
Pattern: Dictionary-based dispatch
State: Connection pool singleton
```

### Operations (6 total)

```
├─ connect: Establish WebSocket connection
├─ send: Send message over WebSocket
├─ receive: Receive message from WebSocket
├─ close: Close WebSocket connection
├─ get_connection_status: Check connection status
└─ get_all_connections: Get all active connections
```

### Gateway Wrappers

```python
# Connection management
websocket_connect(url: str, headers: Dict = None) -> str  # Returns connection_id
websocket_close(connection_id: str) -> bool

# Messaging
websocket_send(connection_id: str, message: str) -> bool
websocket_receive(connection_id: str, timeout: int = 5) -> str

# Status
websocket_status(connection_id: str) -> Dict
websocket_all_connections() -> List[str]
```

### Dependencies

```
Uses: LOGGING, METRICS, SINGLETON
Used by: Home Assistant integration (WebSocket API)
```

### Connection Pattern

```python
from gateway import websocket_connect, websocket_send, websocket_receive, websocket_close

# Connect
conn_id = websocket_connect('wss://example.com/ws', headers={
    'Authorization': 'Bearer token'
})

# Send message
success = websocket_send(conn_id, json.dumps({'type': 'subscribe', 'channel': 'updates'}))

# Receive message
message = websocket_receive(conn_id, timeout=10)
if message:
    data = json.loads(message)
    process_message(data)

# Close when done
websocket_close(conn_id)
```

### Connection Pool

```python
# Connections stored in singleton
_CONNECTION_POOL = {
    'conn_123': {
        'url': 'wss://example.com/ws',
        'socket': socket_instance,
        'connected_at': timestamp,
        'last_activity': timestamp
    }
}
```

### Design Decisions

```
- Connection pooling (reuse connections)
- Automatic reconnection (if connection drops)
- Timeout management
- Message queuing (if needed)
```

### Usage Example

```python
from gateway import (
    websocket_connect,
    websocket_send,
    websocket_receive,
    websocket_close,
    log_info,
    log_error
)
import json

# Establish connection
try:
    conn_id = websocket_connect('wss://api.example.com/stream')
    log_info("WebSocket connected", extra={'conn_id': conn_id})
    
    # Subscribe to channel
    websocket_send(conn_id, json.dumps({
        'type': 'subscribe',
        'channel': 'events'
    }))
    
    # Receive messages
    while True:
        message = websocket_receive(conn_id, timeout=30)
        if message:
            data = json.loads(message)
            handle_event(data)
        else:
            break  # Timeout or disconnected
    
except Exception as e:
    log_error("WebSocket error", error=e)
finally:
    # Always close connection
    if conn_id:
        websocket_close(conn_id)
```

**REAL-WORLD USAGE:**
- User: "How do I use WebSocket connections?"
- Claude: "Use `websocket_connect(url)` to connect, `websocket_send(conn_id, message)` to send, `websocket_receive(conn_id)` to receive. Always close with `websocket_close(conn_id)`."

---

## INT-10: CIRCUIT_BREAKER Interface

**REF:** NM01-INT-10  
**PRIORITY:** 🟡 HIGH  
**TAGS:** CIRCUIT_BREAKER, interface, resilience, fault-tolerance  
**KEYWORDS:** circuit breaker interface, fault tolerance, resilience  
**RELATED:** NM06-LESS-05, NM04-DEC-15

### Overview

```
Router: interface_circuit_breaker.py
Core: circuit_breaker_core.py
Purpose: Circuit breaker pattern for external calls
Pattern: Dictionary-based dispatch
State: Circuit breaker state per service
```

### Operations (5 total)

```
├─ create: Create circuit breaker for service
├─ execute: Execute function with circuit breaker protection
├─ get: Get circuit breaker status
├─ reset: Manually reset circuit breaker
└─ get_all: Get all circuit breaker states
```

### Gateway Wrappers

```python
# Circuit breaker management
create_circuit_breaker(name: str, failure_threshold: int = 5, timeout: int = 60) -> bool
get_circuit_breaker(name: str) -> Dict
reset_circuit_breaker(name: str) -> bool

# Execution with protection
execute_with_circuit_breaker(name: str, function: Callable, *args, **kwargs) -> Any

# Status
get_all_circuit_breakers() -> Dict
```

### Dependencies

```
Uses: LOGGING, METRICS
Used by: HTTP_CLIENT, WEBSOCKET (for external calls)
```

### Circuit Breaker States

```
CLOSED (Normal operation)
  ↓ (failure_threshold exceeded)
OPEN (Failing fast)
  ↓ (timeout expires)
HALF_OPEN (Testing recovery)
  ↓ (success) OR (failure)
CLOSED OR OPEN
```

### State Machine

```python
# State tracking
_CIRCUIT_BREAKERS = {
    'external_api': {
        'state': 'closed',           # closed, open, half_open
        'failure_count': 0,
        'failure_threshold': 5,
        'last_failure_time': None,
        'timeout': 60,               # seconds
        'success_threshold': 2       # for half_open -> closed
    }
}
```

### Automatic Protection

```python
from gateway import execute_with_circuit_breaker

def call_external_api():
    # This might fail
    response = http_get('https://unreliable-api.com/data')
    return response['data']

# Protected execution
try:
    result = execute_with_circuit_breaker('external_api', call_external_api)
    process_result(result)
except CircuitBreakerOpen:
    log_warning("Circuit breaker open for external_api")
    return cached_fallback_data()
```

### Manual Circuit Breaker

```python
from gateway import create_circuit_breaker, get_circuit_breaker, reset_circuit_breaker

# Create circuit breaker
create_circuit_breaker(
    name='payment_api',
    failure_threshold=3,  # Open after 3 failures
    timeout=120          # Try again after 2 minutes
)

# Check status
status = get_circuit_breaker('payment_api')
if status['state'] == 'open':
    log_warning("Payment API circuit breaker is open")
    use_fallback_payment_method()

# Manual reset (admin action)
reset_circuit_breaker('payment_api')
```

### Design Decisions

```
- Automatic failure tracking
- Fast-fail when circuit open (prevent cascading failures)
- Automatic recovery testing (half-open state)
- Configurable thresholds and timeouts
```

### Usage Example

```python
from gateway import (
    create_circuit_breaker,
    execute_with_circuit_breaker,
    get_circuit_breaker,
    log_warning
)

# Setup circuit breaker
create_circuit_breaker('weather_api', failure_threshold=5, timeout=300)

def fetch_weather_data(city):
    """Fetch weather data (might fail)."""
    response = http_get(f'https://weather-api.com/data?city={city}')
    return response['data']

# Protected execution
try:
    weather = execute_with_circuit_breaker(
        'weather_api',
        fetch_weather_data,
        city='New York'
    )
    display_weather(weather)
    
except CircuitBreakerOpen:
    log_warning("Weather API unavailable")
    display_cached_weather()

# Check status
status = get_circuit_breaker('weather_api')
log_info("Circuit breaker status", extra={
    'state': status['state'],
    'failures': status['failure_count']
})
```

**REAL-WORLD USAGE:**
- User: "How do I protect against failing external services?"
- Claude: "Use `execute_with_circuit_breaker(name, function)`. It tracks failures and fails fast when the service is down, preventing cascading failures."

---

## INT-11: UTILITY Interface

**REF:** NM01-INT-11  
**PRIORITY:** 🟢 MEDIUM  
**TAGS:** UTILITY, interface, helpers, utilities, generic-functions  
**KEYWORDS:** utility interface, helper functions, common utilities  
**RELATED:** NM02-DEP-02, NM07-DT-02

### Overview

```
Router: interface_utility.py
Core: utility_core.py
Purpose: Helper functions and common utilities
Pattern: Dictionary-based dispatch
State: Stateless
```

### Operations (10 total)

```
├─ format_response: Format API response
├─ parse_json: Parse JSON safely
├─ safe_get: Safe dictionary access
├─ generate_uuid: Generate UUID
├─ get_timestamp: Get current timestamp
├─ format_datetime: Format datetime
├─ deep_merge: Deep merge dictionaries
├─ flatten_dict: Flatten nested dictionary
├─ chunk_list: Split list into chunks
└─ retry_with_backoff: Retry function with exponential backoff
```

### Gateway Wrappers

```python
# Response formatting
format_response(success: bool, message: str, data: Any = None, status_code: int = 200) -> Dict

# Data operations
parse_json(json_string: str, default: Any = None) -> Any
safe_get(dictionary: Dict, key: str, default: Any = None) -> Any
deep_merge(dict1: Dict, dict2: Dict) -> Dict
flatten_dict(nested_dict: Dict, separator: str = '.') -> Dict

# Identifiers and time
generate_uuid() -> str
get_timestamp() -> float
format_datetime(timestamp: float, format: str = '%Y-%m-%d %H:%M:%S') -> str

# List operations
chunk_list(items: List, chunk_size: int) -> List[List]

# Retry logic
retry_with_backoff(function: Callable, max_retries: int = 3, initial_delay: float = 1.0) -> Any
```

### Dependencies

```
Uses: LOGGING
Used by: All interfaces (utility functions)
```

### Response Formatting

```python
from gateway import format_response

# Success response
return format_response(
    success=True,
    message="User created successfully",
    data={'user_id': 123, 'username': 'john'},
    status_code=201
)
# Returns:
# {
#   'success': True,
#   'message': 'User created successfully',
#   'data': {'user_id': 123, 'username': 'john'},
#   'status_code': 201
# }

# Error response
return format_response(
    success=False,
    message="User not found",
    status_code=404
)
```

### Safe Data Access

```python
from gateway import safe_get, parse_json

# Safe dictionary access (no KeyError)
user_name = safe_get(user_data, 'name', default='Anonymous')
nested_value = safe_get(data, 'user.settings.theme', default='dark')

# Safe JSON parsing (no exception)
data = parse_json(json_string, default={})
if data:
    process_data(data)
else:
    log_warning("Invalid JSON")
```

### Dictionary Operations

```python
from gateway import deep_merge, flatten_dict

# Deep merge dictionaries
defaults = {'cache': {'ttl': 300}, 'http': {'timeout': 10}}
overrides = {'cache': {'max_size': 1000}}
config = deep_merge(defaults, overrides)
# Result: {'cache': {'ttl': 300, 'max_size': 1000}, 'http': {'timeout': 10}}

# Flatten nested dictionary
nested = {'user': {'profile': {'name': 'John', 'age': 30}}}
flat = flatten_dict(nested)
# Result: {'user.profile.name': 'John', 'user.profile.age': 30}
```

### Retry with Backoff

```python
from gateway import retry_with_backoff

def flaky_api_call():
    """API call that might fail transiently."""
    response = http_get('https://api.example.com/data')
    if not response['success']:
        raise Exception("API error")
    return response['data']

# Retry with exponential backoff
try:
    data = retry_with_backoff(
        flaky_api_call,
        max_retries=5,
        initial_delay=1.0  # 1s, 2s, 4s, 8s, 16s
    )
    process_data(data)
except Exception as e:
    log_error("All retries failed", error=e)
    use_fallback_data()
```

### Design Decisions

```
- Centralized utilities (avoid duplication)
- Fail-safe operations (no exceptions on utility calls)
- Flexible parameters (sensible defaults)
- Pure functions (no side effects)
```

### Usage Example

```python
from gateway import (
    format_response,
    parse_json,
    safe_get,
    generate_uuid,
    get_timestamp,
    deep_merge,
    chunk_list,
    retry_with_backoff
)

# Format Lambda response
def lambda_handler(event, context):
    try:
        result = process_event(event)
        return format_response(success=True, message="Success", data=result)
    except Exception as e:
        return format_response(success=False, message=str(e), status_code=500)

# Safe data access
user_id = safe_get(event, 'user.id')
body = parse_json(event.get('body', '{}'))
name = safe_get(body, 'name', default='Anonymous')

# Generate tracking ID
tracking_id = generate_uuid()
log_info("Processing request", extra={'tracking_id': tracking_id})

# Merge configurations
base_config = load_base_config()
user_config = load_user_config()
final_config = deep_merge(base_config, user_config)

# Process large list in chunks
items = fetch_all_items()  # 10,000 items
for chunk in chunk_list(items, chunk_size=100):
    process_batch(chunk)
```

**REAL-WORLD USAGE:**
- User: "What utility functions are available?"
- Claude: "Use `format_response()` for API responses, `safe_get()` for dict access, `parse_json()` for JSON, `retry_with_backoff()` for retries, and more. All are fail-safe."

---

## INT-12: DEBUG Interface

**REF:** NM01-INT-12  
**PRIORITY:** 🟢 MEDIUM  
**TAGS:** DEBUG, interface, diagnostics, health-check, system-validation  
**KEYWORDS:** debug interface, system diagnostics, health checks  
**RELATED:** NM02-DEP-04, NM03-PATH-01

### Overview

```
Router: interface_debug.py
Core: debug_core.py
Purpose: System diagnostics and health checks
Pattern: Dictionary-based dispatch
State: Health check results cache
```

### Operations (7 total)

```
├─ check_component_health: Check individual component
├─ check_gateway_health: Check gateway system
├─ diagnose_system_health: Full system diagnostic
├─ run_debug_tests: Run debug test suite
├─ validate_system_architecture: Validate SIMA architecture
├─ get_system_info: Get system information
└─ clear_debug_cache: Clear debug cache
```

### Gateway Wrappers

```python
# Health checks
check_component_health(component: str) -> Dict
check_gateway_health() -> Dict
diagnose_system_health() -> Dict

# Testing and validation
run_debug_tests() -> Dict
validate_system_architecture() -> Dict

# System info
get_system_info() -> Dict
```

### Dependencies

```
Uses: LOGGING, all other interfaces (for health checks)
Used by: lambda_diagnostics.py, lambda_emergency.py, monitoring systems
```

### Component Health Checks

```python
from gateway import check_component_health

# Check specific component
health = check_component_health('cache')
# Returns:
# {
#   'component': 'cache',
#   'healthy': True,
#   'status': 'operational',
#   'details': {
#     'total_keys': 42,
#     'memory_usage': '2.3 MB',
#     'oldest_entry': '2025-10-20T14:00:00Z'
#   },
#   'checked_at': '2025-10-20T14:30:00Z'
# }

if not health['healthy']:
    log_error("Cache component unhealthy", extra=health)
    trigger_alert()
```

### Full System Diagnostic

```python
from gateway import diagnose_system_health

# Comprehensive system check
diagnostic = diagnose_system_health()
# Returns:
# {
#   'overall_health': 'healthy',
#   'components': {
#     'cache': {'healthy': True, ...},
#     'logging': {'healthy': True, ...},
#     'http_client': {'healthy': True, ...},
#     # ... all 12 interfaces
#   },
#   'metrics': {
#     'total_requests': 1523,
#     'error_rate': 0.02,
#     'avg_latency_ms': 145
#   },
#   'warnings': ['High memory usage in cache'],
#   'errors': [],
#   'diagnostic_duration_ms': 234
# }

# Check overall health
if diagnostic['overall_health'] != 'healthy':
    notify_admin(diagnostic)
```

### Architecture Validation

```python
from gateway import validate_system_architecture

# Validate SIMA compliance
validation = validate_system_architecture()
# Returns:
# {
#   'valid': True,
#   'checks': {
#     'gateway_trinity_exists': True,
#     'all_interfaces_registered': True,
#     'no_circular_imports': True,
#     'dispatch_patterns_correct': True,
#     'dependency_layers_valid': True
#   },
#   'violations': [],
#   'warnings': ['Unused interface: FEATURE_FLAGS']
# }

if not validation['valid']:
    log_critical("Architecture violation detected", extra=validation)
```

### Debug Test Suite

```python
from gateway import run_debug_tests

# Run comprehensive tests
results = run_debug_tests()
# Returns:
# {
#   'tests_run': 47,
#   'tests_passed': 45,
#   'tests_failed': 2,
#   'duration_ms': 1234,
#   'failures': [
#     {
#       'test': 'cache_ttl_expiration',
#       'error': 'Expected expired, got value',
#       'component': 'cache'
#     },
#     {
#       'test': 'http_timeout_handling',
#       'error': 'Timeout not enforced',
#       'component': 'http_client'
#     }
#   ]
# }

# Alert on failures
if results['tests_failed'] > 0:
    log_error("Debug tests failed", extra=results)
```

### System Information

```python
from gateway import get_system_info

# Get runtime information
info = get_system_info()
# Returns:
# {
#   'lambda': {
#     'memory_limit_mb': 512,
#     'memory_used_mb': 187,
#     'remaining_time_ms': 28450,
#     'cold_start': False
#   },
#   'python': {
#     'version': '3.9.18',
#     'platform': 'linux'
#   },
#   'system': {
#     'uptime_seconds': 1245,
#     'requests_handled': 342
#   },
#   'configuration': {
#     'preset': 'standard',
#     'debug_mode': False
#   }
# }
```

### Design Decisions

```
- Comprehensive health checks (all components)
- Performance impact minimized (cached results)
- SIMA architecture validation (prevent drift)
- Debug mode support (detailed diagnostics)
```

### Usage Example

```python
from gateway import (
    check_gateway_health,
    diagnose_system_health,
    validate_system_architecture,
    run_debug_tests,
    log_info,
    log_error
)

# Health check endpoint
def health_check_handler(event, context):
    health = check_gateway_health()
    
    if health['healthy']:
        return format_response(success=True, data=health)
    else:
        log_error("System unhealthy", extra=health)
        return format_response(
            success=False,
            message="System unhealthy",
            data=health,
            status_code=503
        )

# Diagnostic endpoint (admin only)
def diagnostic_handler(event, context):
    # Full system diagnostic
    diagnostic = diagnose_system_health()
    
    # Architecture validation
    validation = validate_system_architecture()
    
    # Test suite
    tests = run_debug_tests()
    
    return format_response(success=True, data={
        'diagnostic': diagnostic,
        'validation': validation,
        'tests': tests
    })

# Startup validation
def lambda_handler(event, context):
    initialize_system()
    
    # Validate architecture on cold start
    if context.cold_start:
        validation = validate_system_architecture()
        if not validation['valid']:
            log_critical("Architecture invalid", extra=validation)
            return error_response("System misconfigured")
    
    # Process request
    return process_event(event)
```

**REAL-WORLD USAGE:**
- User: "How do I check system health?"
- Claude: "Use `check_gateway_health()` for quick check, `diagnose_system_health()` for comprehensive diagnostic. For architecture validation, use `validate_system_architecture()`."

---

## Related Neural Maps

- **NM02: Interface Dependency Web** - How these interfaces depend on each other
- **NM03: Operational Pathways** - Data flow through these interfaces
- **NM04: Design Decisions** - Why these interfaces exist
- **NM05: Anti-Patterns** - Common mistakes using these interfaces
- **NM06: Learned Experiences** - Real bugs involving these interfaces

---

## End Notes

This file documents the 6 advanced feature interfaces (INT-07 through INT-12) used in the SUGA-ISP Lambda project. These interfaces provide:

- **INITIALIZATION**: System startup and component initialization
- **HTTP_CLIENT**: External API communication
- **WEBSOCKET**: Real-time WebSocket connections
- **CIRCUIT_BREAKER**: Fault tolerance and resilience
- **UTILITY**: Common helper functions
- **DEBUG**: System diagnostics and health checks

For core infrastructure interfaces, see: **NM01-INTERFACES-Core.md** (INT-01 through INT-06)  
For architecture patterns, see: **NM01-CORE-Architecture.md** (ARCH-01 through ARCH-06)

**Status:** ✅ ACTIVE - Ready for use

# EOF

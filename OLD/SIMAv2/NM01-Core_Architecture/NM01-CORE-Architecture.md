# NEURAL MAP 01: Core Architecture - IMPLEMENTATION (Core Interfaces)

**Purpose:** Core infrastructure interface specifications  
**Status:** ✅ ACTIVE  
**Last Updated:** 2025-10-20  
**Parent Index:** NM01-INDEX-Architecture.md

**Contains:** INT-01 through INT-06 (6 core infrastructure interfaces)

---

## INT-01: CACHE Interface

**REF:** NM01-INT-01  
**PRIORITY:** 🔴 CRITICAL  
**TAGS:** CACHE, interface, caching, performance, TTL  
**KEYWORDS:** cache interface, caching system, TTL management  
**RELATED:** NM06-BUG-01, NM04-DEC-05, NM03-PATH-02

### Overview

```
Router: interface_cache.py
Core: cache_core.py
Purpose: In-memory caching with TTL support
Pattern: Dictionary-based dispatch
State: Module-level dictionaries (_CACHE_STORE, _CACHE_TTL)
```

### Operations (8 total)

```
├─ set: Store value with TTL
├─ get: Retrieve value (returns sentinel if not found)
├─ delete: Remove specific key
├─ clear: Clear all cache
├─ has: Check if key exists and not expired
├─ get_stats: Get cache statistics
├─ get_all_keys: Get all cache keys
└─ cleanup_expired: Remove expired entries
```

### Gateway Wrappers

```python
# Primary operations
cache_set(key: str, value: Any, ttl: int = 300) -> bool
cache_get(key: str, default: Any = None) -> Any
cache_delete(key: str) -> bool
cache_clear() -> bool

# Utility operations
cache_has(key: str) -> bool
cache_stats() -> Dict
cache_keys() -> List[str]
cache_cleanup() -> int  # Returns count of cleaned entries
```

### Dependencies

```
Uses: LOGGING (for cache events), SECURITY (for sentinel validation)
Used by: CONFIG (config caching), HTTP_CLIENT (response caching)
```

### Special Patterns

```
Sentinel Pattern: _CacheMiss sentinel for "not found" vs None values
TTL Management: Automatic expiration checking on get operations
Lazy Cleanup: Expired entries removed on access or explicit cleanup
```

### Critical Bug History

**NM06-BUG-01: Sentinel Leak (535ms penalty)**
- The `_CacheMiss` sentinel was leaking outside cache_core
- Caused 535ms performance penalty per leak
- Fixed: Validate sentinels in SECURITY interface before returning

### Design Decisions

```
- In-memory only (no external cache due to free tier)
- TTL-based expiration (no LRU eviction)
- Module-level state (persists across warm invocations)
- Sentinel pattern for distinguishing "not found" from None
```

### Usage Example

```python
from gateway import cache_set, cache_get

# Store with default 5-minute TTL
cache_set('user:123', {'name': 'John'})

# Store with custom TTL (1 hour)
cache_set('config:db', db_config, ttl=3600)

# Retrieve (returns None if not found/expired)
user = cache_get('user:123')

# Retrieve with custom default
value = cache_get('missing_key', default='NOT_FOUND')

# Check existence
if cache_has('user:123'):
    print("User cached")

# Get cache statistics
stats = cache_stats()
print(f"Hits: {stats['hits']}, Misses: {stats['misses']}")
```

**REAL-WORLD USAGE:**
- User: "How do I cache data?"
- Claude: "Use `cache_set(key, value, ttl)` to store and `cache_get(key)` to retrieve. Returns None if not found or expired."

---

## INT-02: LOGGING Interface

**REF:** NM01-INT-02  
**PRIORITY:** 🔴 CRITICAL  
**TAGS:** LOGGING, interface, observability, debugging  
**KEYWORDS:** logging interface, log levels, structured logging  
**RELATED:** NM02-DEP-01, NM03-PATH-03, NM06-LESS-02

### Overview

```
Router: interface_logging.py
Core: logging_core.py
Purpose: Centralized logging with multiple levels
Pattern: Dictionary-based dispatch
State: Configured logger instance
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
Uses: None (base layer - NM02-DEP-01)
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

**REAL-WORLD USAGE:**
- User: "How do I add logging?"
- Claude: "Use `log_info(message, **extra)` for general logs, `log_error(message, error=e)` for exceptions. All logs are structured JSON for CloudWatch."

---

## INT-03: SECURITY Interface

**REF:** NM01-INT-03  
**PRIORITY:** 🟡 HIGH  
**TAGS:** SECURITY, interface, validation, sanitization  
**KEYWORDS:** security interface, input validation, sentinel checks  
**RELATED:** NM04-DEC-05, NM06-BUG-02, NM03-PATH-04

### Overview

```
Router: interface_security.py
Core: security_core.py
Purpose: Input validation and security checks
Pattern: Dictionary-based dispatch
State: Stateless (no persistent state)
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

**REAL-WORLD USAGE:**
- User: "How do I validate user input?"
- Claude: "Use `validate_string(value, max_length)` for strings, `validate_dict(value, required_keys)` for dicts. Always validate before using user input."

---

## INT-04: METRICS Interface

**REF:** NM01-INT-04  
**PRIORITY:** 🟢 MEDIUM  
**TAGS:** METRICS, interface, observability, performance  
**KEYWORDS:** metrics interface, performance tracking, counters  
**RELATED:** NM06-LESS-02, NM03-PATH-05

### Overview

```
Router: interface_metrics.py
Core: metrics_core.py
Purpose: Performance metrics and counters
Pattern: Dictionary-based dispatch
State: In-memory metric counters
```

### Operations (8 total)

```
├─ record: Record a metric value
├─ increment: Increment a counter
├─ get_stats: Get metrics statistics
├─ record_operation: Record operation metric with timing
├─ record_error: Record error metric
├─ record_cache: Record cache hit/miss
├─ record_api: Record API call metric
└─ clear_metrics: Clear all metrics (testing)
```

### Gateway Wrappers

```python
# Core operations
record_metric(name: str, value: float, **kwargs) -> None
increment_counter(name: str, value: int = 1, **kwargs) -> None
get_metrics_stats() -> Dict

# Specialized metrics
record_operation_metric(operation: str, duration_ms: float, success: bool, **kwargs) -> None
record_error_metric(error_type: str, **kwargs) -> None
record_cache_metric(operation: str, hit: bool, **kwargs) -> None
record_api_metric(api: str, duration_ms: float, status_code: int, **kwargs) -> None
```

### Dependencies

```
Uses: LOGGING (for metric errors)
Used by: CACHE, HTTP_CLIENT, CIRCUIT_BREAKER, WEBSOCKET
```

### Metric Types

```
Counters  → Incrementing values (requests, errors, cache_hits)
Gauges    → Point-in-time values (memory_usage, active_connections)
Timers    → Duration measurements (operation_duration_ms)
```

### State Storage

```python
# In-memory metric storage
_METRICS_STORE = {
    'counters': {},   # Counter metrics
    'gauges': {},     # Gauge metrics
    'timers': []      # Timer measurements
}
```

### Design Decisions

```
- Lightweight metrics (no external services due to free tier)
- In-memory only (no persistence)
- CloudWatch logs integration (parse from logs)
- Automatic timing for operations
```

### Usage Example

```python
from gateway import (
    record_metric,
    increment_counter,
    record_operation_metric,
    record_cache_metric,
    get_metrics_stats
)
import time

# Increment counter
increment_counter('api.requests')
increment_counter('api.requests', value=5)  # Increment by 5

# Record gauge
record_metric('memory.usage_mb', 245.6)

# Record operation timing
start = time.time()
result = expensive_operation()
duration_ms = (time.time() - start) * 1000
record_operation_metric('expensive_op', duration_ms, success=True)

# Record cache metric
hit = cache_get('key') is not None
record_cache_metric('user_cache', hit=hit)

# Get all stats
stats = get_metrics_stats()
print(f"Total requests: {stats['counters']['api.requests']}")
```

**REAL-WORLD USAGE:**
- User: "How do I track performance?"
- Claude: "Use `increment_counter(name)` for counts, `record_operation_metric(op, duration_ms, success)` for timing operations. Get stats with `get_metrics_stats()`."

---

## INT-05: CONFIG Interface

**REF:** NM01-INT-05  
**PRIORITY:** 🟡 HIGH  
**TAGS:** CONFIG, interface, configuration, parameters, multi-tier  
**KEYWORDS:** config interface, configuration management, parameters  
**RELATED:** NM04-DEC-12, NM02-DEP-03

### Overview

```
Router: interface_config.py
Core: config_core.py
Purpose: Configuration management and parameter storage
Pattern: Dictionary-based dispatch
State: Multi-tier configuration dictionary
```

### Operations (9 total)

```
├─ get_parameter: Get configuration value
├─ set_parameter: Set configuration value
├─ get_category: Get entire configuration category
├─ reload: Reload configuration from source
├─ switch_preset: Switch configuration preset
├─ get_state: Get current configuration state
├─ load_environment: Load from environment variables
├─ load_file: Load from configuration file
└─ validate_all: Validate all configuration
```

### Gateway Wrappers

```python
# Primary operations
get_config(key: str, default: Any = None) -> Any
set_config(key: str, value: Any) -> bool
get_config_category(category: str) -> Dict

# Management operations
reload_config() -> bool
switch_config_preset(preset: str) -> bool
get_config_state() -> Dict

# Loading operations
load_config_from_env() -> int  # Returns count loaded
load_config_from_file(filepath: str) -> int
validate_config() -> bool
```

### Dependencies

```
Uses: LOGGING, CACHE (config caching), SECURITY (validation)
Used by: All interfaces (for configuration)
```

### Multi-Tier Configuration

Configuration follows a priority order:

```
1. User Override (highest priority)
   - Set via set_config()
   - Runtime changes
   
2. Environment Variables
   - From AWS Lambda environment
   - Prefix: PARAMETER_PREFIX
   
3. Configuration Preset
   - minimum, standard, maximum
   - Deployment mode settings
   
4. Default Values (lowest priority)
   - Hardcoded fallbacks
```

### Configuration Presets

```python
# Minimum preset (free tier)
CONFIG_PRESETS = {
    'minimum': {
        'cache.default_ttl': 300,
        'cache.max_size': 100,
        'http.timeout': 5,
        'http.max_retries': 1
    },
    
    # Standard preset (balanced)
    'standard': {
        'cache.default_ttl': 600,
        'cache.max_size': 1000,
        'http.timeout': 10,
        'http.max_retries': 3
    },
    
    # Maximum preset (performance)
    'maximum': {
        'cache.default_ttl': 1800,
        'cache.max_size': 10000,
        'http.timeout': 30,
        'http.max_retries': 5
    }
}
```

### Configuration Categories

```
cache.*         → Cache configuration
logging.*       → Logging configuration
http.*          → HTTP client configuration
security.*      → Security settings
metrics.*       → Metrics configuration
home_assistant.* → Home Assistant integration
```

### Design Decisions

```
- Multi-tier resolution (user > env > preset > default)
- Configuration caching (reduce lookups)
- Preset-based deployment modes
- Environment variable integration
```

### Usage Example

```python
from gateway import get_config, set_config, switch_config_preset, get_config_category

# Get single config
timeout = get_config('http.timeout', default=10)
cache_ttl = get_config('cache.default_ttl')

# Set runtime override
set_config('cache.default_ttl', 900)

# Get entire category
cache_config = get_config_category('cache')
# Returns: {'default_ttl': 900, 'max_size': 1000, ...}

# Switch preset (deployment mode)
switch_config_preset('maximum')  # Use maximum performance settings

# Load from environment
loaded = load_config_from_env()
log_info(f"Loaded {loaded} config values from environment")
```

**REAL-WORLD USAGE:**
- User: "How do I get configuration values?"
- Claude: "Use `get_config(key, default)` for single values, `get_config_category(category)` for groups. Configuration follows: user > env > preset > default."

---

## INT-06: SINGLETON Interface

**REF:** NM01-INT-06  
**PRIORITY:** 🟢 MEDIUM  
**TAGS:** SINGLETON, interface, storage, stateful, factory  
**KEYWORDS:** singleton interface, singleton storage, factory pattern  
**RELATED:** NM02-DEP-02

### Overview

```
Router: interface_singleton.py
Core: singleton_core.py
Purpose: Singleton object storage and management
Pattern: Dictionary-based dispatch
State: Module-level singleton dictionary
```

### Operations (5 total)

```
├─ get: Get singleton instance (create if needed with factory)
├─ has: Check if singleton exists
├─ delete: Remove singleton
├─ clear: Clear all singletons
└─ get_stats: Get singleton statistics
```

### Gateway Wrappers

```python
# Core operations
singleton_get(key: str, factory: Callable = None) -> Any
singleton_has(key: str) -> bool
singleton_delete(key: str) -> bool
singleton_clear() -> int  # Returns count cleared
singleton_stats() -> Dict
```

### Dependencies

```
Uses: LOGGING
Used by: Various interfaces for stateful objects
```

### State Storage

```python
# Module-level singleton storage
_SINGLETON_STORE: Dict[str, Any] = {}
```

### Factory Pattern

The singleton interface supports lazy creation using factory functions:

```python
from gateway import singleton_get

# Define factory
def create_http_client():
    import requests
    return requests.Session()

# Get singleton (creates on first call)
session = singleton_get('http_session', factory=create_http_client)

# Subsequent calls return same instance
same_session = singleton_get('http_session')  # No factory needed
```

### Common Use Cases

```
Database Connections  → Reuse connection across invocations
HTTP Sessions        → Maintain session state
Configuration Objects → Parse once, reuse many times
External Clients     → AWS SDK clients, API clients
```

### Lifecycle Management

```python
from gateway import singleton_get, singleton_has, singleton_delete

# Check existence
if not singleton_has('db_connection'):
    # Create if doesn't exist
    conn = singleton_get('db_connection', factory=create_db_connection)

# Force recreation (delete and recreate)
singleton_delete('http_client')
new_client = singleton_get('http_client', factory=create_client)

# Clear all (useful for testing)
singleton_clear()
```

### Design Decisions

```
- Module-level storage (persists across warm Lambda invocations)
- Factory pattern for lazy initialization
- Manual lifecycle management (no automatic cleanup)
- Thread-safe (Lambda is single-threaded)
```

### Usage Example

```python
from gateway import singleton_get, singleton_has, singleton_delete
import boto3

# Factory for AWS client
def create_s3_client():
    return boto3.client('s3')

# Get singleton (lazy create)
s3 = singleton_get('s3_client', factory=create_s3_client)

# Use singleton
s3.list_buckets()

# Check if exists
if singleton_has('s3_client'):
    print("S3 client already initialized")

# Force recreation (e.g., after error)
singleton_delete('s3_client')
s3 = singleton_get('s3_client', factory=create_s3_client)
```

**REAL-WORLD USAGE:**
- User: "How do I reuse expensive objects across Lambda invocations?"
- Claude: "Use `singleton_get(key, factory)`. The factory creates the object on first call, subsequent calls return the cached instance."

---

## Related Neural Maps

- **NM02: Interface Dependency Web** - How these interfaces depend on each other
- **NM03: Operational Pathways** - Data flow through these interfaces
- **NM04: Design Decisions** - Why these interfaces exist
- **NM05: Anti-Patterns** - Common mistakes using these interfaces
- **NM06: Learned Experiences** - Real bugs involving these interfaces

---

## End Notes

This file documents the 6 core infrastructure interfaces (INT-01 through INT-06) used in the SUGA-ISP Lambda project. These are the foundational interfaces that support the system:

- **CACHE**: Performance optimization
- **LOGGING**: Observability and debugging
- **SECURITY**: Input validation and protection
- **METRICS**: Performance tracking
- **CONFIG**: Configuration management
- **SINGLETON**: State management

For advanced interfaces, see: **NM01-INTERFACES-Advanced.md** (INT-07 through INT-12)

**Status:** ✅ ACTIVE - Ready for use

# EOF

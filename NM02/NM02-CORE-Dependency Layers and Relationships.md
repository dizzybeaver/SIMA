# NM02-CORE: Dependency Layers & Relationships
# SIMA Architecture Pattern - Implementation Details
# Version: 2.0.0 | Phase: 2 SIMA Implementation

---

## Purpose

This file documents the 5-layer dependency hierarchy and detailed dependency relationships for key interfaces. It's the **Implementation Layer** for dependency structures in the SIMA pattern.

**Access via:** NM02-INDEX-Dependencies.md or direct search

---

## PART 1: DEPENDENCY HIERARCHY (Bottom-Up)

### Layer 0: Base Infrastructure (No Dependencies)
**REF:** NM02-DEP-01
**PRIORITY:** ðŸ"´ CRITICAL
**TAGS:** dependencies, base-layer, LOGGING, zero-dependencies, foundation
**KEYWORDS:** base layer, no dependencies, logging layer, foundation layer
**RELATED:** NM01-INT-02, NM04-DEC-08, NM06-LESS-04

```
LOGGING
â"œâ"€ Dependencies: None
â"œâ"€ Purpose: Base logging infrastructure
â"œâ"€ Used by: All other interfaces
â""â"€ Why base layer: Must not depend on anything to avoid circular imports

Dependency Rule: LOGGING cannot import from any other interface
Rationale: If LOGGING depended on anything, circular imports would be inevitable
```

**Why This Matters:**
LOGGING is the foundation. Every other interface logs, so LOGGING must be dependency-free. If LOGGING depended on CACHE, and CACHE depended on LOGGING, we'd have a circular import.

**Real-World Impact:**
- Cold start order: LOGGING initializes first (< 5ms)
- Error handling: LOGGING must work even if everything else fails
- Debugging: LOGGING provides visibility into all interfaces

**Real-World Usage:**
```
User: "Why can't LOGGING use CACHE?"
Claude searches: "base layer dependencies"
Finds: NM02-DEP-01
Response: "LOGGING is Layer 0 with zero dependencies - must not depend 
on anything to avoid circular imports."
```

---

### Layer 1: Core Utilities (Depends on LOGGING only)
**REF:** NM02-DEP-02
**PRIORITY:** ðŸŸ¡ HIGH
**TAGS:** dependencies, layer-1, utilities, core-utilities, SECURITY
**KEYWORDS:** utility layer, security layer, core utilities, layer 1
**RELATED:** NM01-INT-03, NM01-INT-11, NM01-INT-06

```
SECURITY
â"œâ"€ Dependencies: LOGGING
â"œâ"€ Purpose: Validation, encryption, sanitization
â"œâ"€ Used by: HTTP_CLIENT, CONFIG, WEBSOCKET
â""â"€ Why Layer 1: Security is fundamental, needed by many interfaces

UTILITY
â"œâ"€ Dependencies: None (optionally LOGGING)
â"œâ"€ Purpose: Helper functions, common utilities
â"œâ"€ Used by: All interfaces
â""â"€ Why Layer 1: Utilities are foundational helpers

SINGLETON
â"œâ"€ Dependencies: LOGGING
â"œâ"€ Purpose: Singleton storage
â"œâ"€ Used by: Various interfaces for stateful objects
â""â"€ Why Layer 1: Simple storage, minimal dependencies
```

**Why This Matters:**
Layer 1 provides core functionality that doesn't depend on complex infrastructure. SECURITY validates inputs. UTILITY provides helpers. SINGLETON manages state.

**Dependency Pattern:**
```python
# In security_core.py
from gateway import log_warning  # Only depends on Layer 0 (LOGGING)

def validate_input(value):
    if not is_valid(value):
        log_warning(f"Invalid input: {value}")
        return False
    return True
```

**Real-World Impact:**
- Security checks happen early (Layer 1)
- Utility functions available to all interfaces
- Singleton pattern for state management

---

### Layer 2: Storage & Monitoring (Depends on Layer 0-1)
**REF:** NM02-DEP-03
**PRIORITY:** ðŸŸ¡ HIGH
**TAGS:** dependencies, layer-2, storage, monitoring, CACHE, METRICS
**KEYWORDS:** cache layer, metrics layer, storage layer, monitoring
**RELATED:** NM01-INT-01, NM01-INT-04, NM01-INT-05

```
CACHE
â"œâ"€ Dependencies: LOGGING, METRICS
â"œâ"€ Purpose: Data caching
â"œâ"€ Used by: HTTP_CLIENT, CONFIG, SECURITY
â""â"€ Why Layer 2: Needs metrics to track cache performance

METRICS
â"œâ"€ Dependencies: LOGGING
â"œâ"€ Purpose: Telemetry and monitoring
â"œâ"€ Used by: CACHE, HTTP_CLIENT, CIRCUIT_BREAKER, WEBSOCKET
â""â"€ Why Layer 2: Monitors other systems, doesn't provide core functionality

CONFIG
â"œâ"€ Dependencies: LOGGING, CACHE, SECURITY
â"œâ"€ Purpose: Configuration management
â"œâ"€ Used by: All interfaces (for configuration)
â""â"€ Why Layer 2: Uses cache for config storage, security for validation
```

**Why This Matters:**
Layer 2 provides infrastructure services. CACHE speeds up operations. METRICS provides visibility. CONFIG centralizes settings.

**Dependency Pattern:**
```python
# In cache_core.py
from gateway import log_info, record_metric  # Layer 0 (LOGGING) + Layer 2 (METRICS)

def cache_get(key):
    log_info(f"Cache lookup: {key}")
    result = _perform_get(key)
    record_metric("cache_hit" if result else "cache_miss", 1.0)
    return result
```

**Real-World Impact:**
- Cache reduces API calls by 60-80%
- Metrics track system health
- Config eliminates hardcoded values

**Circular Import Prevention:**
Notice CACHE uses METRICS, but METRICS doesn't use CACHE. This prevents circular dependencies. All cross-layer access goes through gateway.py.

---

### Layer 3: Service Infrastructure (Depends on Layer 0-2)
**REF:** NM02-DEP-04
**PRIORITY:** ðŸŸ¡ HIGH
**TAGS:** dependencies, layer-3, services, HTTP, websocket, circuit-breaker
**KEYWORDS:** service layer, HTTP layer, websocket layer, circuit breaker
**RELATED:** NM01-INT-08, NM01-INT-09, NM01-INT-10

```
HTTP_CLIENT
â"œâ"€ Dependencies: LOGGING, SECURITY, CACHE, METRICS
â"œâ"€ Purpose: HTTP request handling
â"œâ"€ Used by: homeassistant_extension, external integrations
â""â"€ Why Layer 3: Needs security (validation), cache (responses), metrics (tracking)

WEBSOCKET
â"œâ"€ Dependencies: LOGGING, SECURITY, METRICS
â"œâ"€ Purpose: WebSocket connections
â"œâ"€ Used by: homeassistant_extension (Home Assistant websocket)
â""â"€ Why Layer 3: Similar to HTTP_CLIENT, needs security and monitoring

CIRCUIT_BREAKER
â"œâ"€ Dependencies: LOGGING, METRICS
â"œâ"€ Purpose: Failure protection
â"œâ"€ Used by: HTTP_CLIENT, WEBSOCKET (wraps external calls)
â""â"€ Why Layer 3: Protects service layer, needs metrics for failure tracking
```

**Why This Matters:**
Layer 3 handles external communication. HTTP_CLIENT talks to APIs. WEBSOCKET maintains persistent connections. CIRCUIT_BREAKER prevents cascading failures.

**Dependency Pattern:**
```python
# In http_client_core.py
from gateway import (
    log_info,           # Layer 0 (LOGGING)
    validate_url,       # Layer 1 (SECURITY)
    cache_get,          # Layer 2 (CACHE)
    record_api_metric   # Layer 2 (METRICS)
)

def http_get(url):
    log_info(f"HTTP GET: {url}")
    validate_url(url)  # Security check
    
    cached = cache_get(f"http_{url}")  # Check cache
    if cached:
        return cached
    
    response = _perform_request(url)
    record_api_metric("http_get", response.time)  # Track metrics
    return response
```

**Real-World Impact:**
- HTTP_CLIENT handles all external API calls
- Circuit breaker prevents outage cascades
- WebSocket maintains Home Assistant connection
- Layer 3 depends on all lower layers for complete functionality

---

### Layer 4: Management & Debug (Depends on Layer 0-3)
**REF:** NM02-DEP-05
**PRIORITY:** ðŸŸ¢ MEDIUM
**TAGS:** dependencies, layer-4, management, debug, initialization
**KEYWORDS:** management layer, debug layer, initialization layer
**RELATED:** NM01-INT-07, NM01-INT-12

```
INITIALIZATION
â"œâ"€ Dependencies: LOGGING, CONFIG
â"œâ"€ Purpose: System initialization
â"œâ"€ Used by: lambda_function.py (on cold start)
â""â"€ Why Layer 4: Coordinates system startup, needs config

DEBUG
â"œâ"€ Dependencies: LOGGING, All interfaces (for health checks)
â"œâ"€ Purpose: System diagnostics
â"œâ"€ Used by: lambda_diagnostics.py, lambda_emergency.py
â""â"€ Why Layer 4: Tests all other interfaces, must be top layer
```

**Why This Matters:**
Layer 4 manages and inspects the system. INITIALIZATION sets everything up. DEBUG verifies everything works.

**Dependency Pattern:**
```python
# In initialization_core.py
from gateway import log_info, get_config  # Layer 0 + Layer 2

def initialize_system():
    log_info("System initialization starting")
    config = get_config("system_config")
    # Initialize all interfaces in order (Layer 0 → Layer 3)
    return True

# In debug_core.py
from gateway import (
    log_info,      # Layer 0
    cache_health,  # Layer 2
    http_health,   # Layer 3
    # ... all other interfaces
)

def system_health_check():
    results = {
        "cache": cache_health(),
        "http": http_health(),
        # Test all interfaces
    }
    return results
```

**Real-World Impact:**
- INITIALIZATION reduces cold start time (< 100ms)
- DEBUG catches issues before users see them
- Layer 4 has visibility into entire system

---

## PART 2: DETAILED DEPENDENCIES

### CACHE Dependencies (Deep Dive)
**REF:** NM02-CACHE-DEP
**PRIORITY:** ðŸŸ¡ HIGH
**TAGS:** CACHE, dependencies, detailed, imports
**KEYWORDS:** cache dependencies, cache uses, cache imports
**RELATED:** NM01-INT-01, NM02-DEP-03

```
CACHE depends on:
â"œâ"€ LOGGING (for error logging)
â"‚   â"œâ"€ Used in: interface_cache.py (router layer)
â"‚   â"œâ"€ Functions: log_error, log_warning, log_info
â"‚   â""â"€ Purpose: Log cache operations and errors
â"‚
â""â"€ METRICS (for operation tracking)
    â"œâ"€ Used in: cache_core.py (implementation layer)
    â"œâ"€ Functions: record_metric, increment_counter
    â""â"€ Purpose: Track cache hits, misses, performance

CACHE is used by:
â"œâ"€ HTTP_CLIENT (response caching)
â"‚   â"œâ"€ Purpose: Cache GET responses to reduce API calls
â"‚   â""â"€ Pattern: cache_set(f"http_{url}", response)
â"‚
â"œâ"€ CONFIG (configuration caching)
â"‚   â"œâ"€ Purpose: Cache config values to reduce lookups
â"‚   â""â"€ Pattern: cache_get(f"config_{key}")
â"‚
â""â"€ SECURITY (token caching)
    â"œâ"€ Purpose: Cache validated tokens
    â""â"€ Pattern: cache_set(f"token_{token}", validation_result)

Import Pattern:
# In cache_core.py
from gateway import log_error, record_metric  # Cross-interface

# In http_client_core.py
from gateway import cache_set, cache_get  # Uses CACHE
```

**Performance Impact:**
- Cache hit rate: 75-85% typical
- Cache lookup: < 1ms
- Cache save: < 2ms
- Memory: ~10MB typical usage

**Common Cache Keys:**
- `http_{url}`: HTTP responses (300-600 second TTL)
- `config_{key}`: Configuration values (persistent)
- `token_{value}`: Validated tokens (3600 second TTL)
- `ha_state_{entity}`: Home Assistant states (60 second TTL)

---

### HTTP_CLIENT Dependencies (Deep Dive)
**REF:** NM02-HTTP-DEP
**PRIORITY:** ðŸŸ¡ HIGH
**TAGS:** HTTP_CLIENT, dependencies, detailed, imports
**KEYWORDS:** HTTP dependencies, HTTP uses, HTTP imports
**RELATED:** NM01-INT-08, NM02-DEP-04

```
HTTP_CLIENT depends on:
â"œâ"€ LOGGING (for request/response logging)
â"‚   â"œâ"€ Used in: interface_http.py, http_client_core.py
â"‚   â"œâ"€ Functions: log_info, log_error, log_warning
â"‚   â""â"€ Purpose: Log all HTTP operations
â"‚
â"œâ"€ SECURITY (for request validation)
â"‚   â"œâ"€ Used in: http_client_core.py
â"‚   â"œâ"€ Functions: validate_url, sanitize_input
â"‚   â""â"€ Purpose: Validate URLs and sanitize data
â"‚
â"œâ"€ CACHE (for response caching)
â"‚   â"œâ"€ Used in: http_client_core.py
â"‚   â"œâ"€ Functions: cache_get, cache_set
â"‚   â""â"€ Purpose: Cache GET responses
â"‚
â""â"€ METRICS (for request tracking)
    â"œâ"€ Used in: http_client_core.py
    â"œâ"€ Functions: record_api_metric
    â""â"€ Purpose: Track request timing and success rates

HTTP_CLIENT is used by:
â"œâ"€ homeassistant_extension (Home Assistant API calls)
â"‚   â""â"€ Purpose: Communicate with Home Assistant server
â"‚
â""â"€ External integrations (API calls)
    â""â"€ Purpose: Generic HTTP request handling

Import Pattern:
# In http_client_core.py
from gateway import (
    log_info, log_error,      # LOGGING
    validate_url,             # SECURITY
    cache_get, cache_set,     # CACHE
    record_api_metric         # METRICS
)
```

**Performance Impact:**
- Average request time: 50-200ms
- Cache hit savings: 180ms average
- Circuit breaker engagement: < 1% of requests
- Success rate: 99.5% typical

**Request Flow:**
1. Validate URL (SECURITY) - < 1ms
2. Check cache (CACHE) - < 1ms
3. If cached, return (total: 2ms)
4. If not cached:
   - Make HTTP request - 50-200ms
   - Record metrics (METRICS) - < 1ms
   - Cache response (CACHE) - < 2ms
   - Return result

---

### CONFIG Dependencies (Deep Dive)
**REF:** NM02-CONFIG-DEP
**PRIORITY:** ðŸŸ¡ HIGH
**TAGS:** CONFIG, dependencies, detailed, imports
**KEYWORDS:** config dependencies, config uses, config imports
**RELATED:** NM01-INT-05, NM02-DEP-03

```
CONFIG depends on:
â"œâ"€ LOGGING (for configuration changes)
â"‚   â"œâ"€ Used in: interface_config.py, config_core.py
â"‚   â"œâ"€ Functions: log_info, log_warning
â"‚   â""â"€ Purpose: Log config loads, changes, errors
â"‚
â"œâ"€ CACHE (for configuration caching)
â"‚   â"œâ"€ Used in: config_core.py
â"‚   â"œâ"€ Functions: cache_get, cache_set
â"‚   â""â"€ Purpose: Cache frequently accessed config values
â"‚
â""â"€ SECURITY (for input validation)
    â"œâ"€ Used in: config_core.py
    â"œâ"€ Functions: validate_string, sanitize_input
    â""â"€ Purpose: Validate config values

CONFIG is used by:
â""â"€ All interfaces (for configuration)
    â""â"€ Purpose: Retrieve interface-specific configuration

Import Pattern:
# In config_core.py
from gateway import (
    log_info,                 # LOGGING
    cache_get, cache_set,     # CACHE
    validate_string           # SECURITY
)
```

**Configuration Sources:**
1. Environment variables (highest priority)
2. AWS Parameter Store (if enabled)
3. Default values (fallback)

**Performance Impact:**
- First config load: 5-15ms (Parameter Store)
- Cached config load: < 1ms
- Config changes: Logged but not hot-reloaded (requires redeployment)

**Common Config Keys:**
- `home_assistant_url`: Home Assistant server URL
- `log_level`: Logging verbosity (CRITICAL, ERROR, WARNING, INFO, DEBUG)
- `cache_ttl_default`: Default cache TTL in seconds
- `http_timeout`: HTTP request timeout in seconds
- `debug_mode`: Enable/disable debug features

---

## PART 3: DEPENDENCY FLOW EXAMPLES

### Example 1: HTTP Request with Full Dependency Chain
```
Lambda invokes HTTP GET request:

lambda_function.py
  â†' gateway.py → http_get()
      â†' interface_http.py → execute_http_operation()
          â†' http_client_core.py
              â"œâ"€ gateway.log_info() → interface_logging.py → logging_core.py (Layer 0)
              â"œâ"€ gateway.validate_url() → interface_security.py → security_core.py (Layer 1)
              â"œâ"€ gateway.cache_get() → interface_cache.py → cache_core.py (Layer 2)
              â"œâ"€ If cache miss:
              â"‚   â"œâ"€ Perform HTTP request
              â"‚   â"œâ"€ gateway.cache_set() → interface_cache.py → cache_core.py (Layer 2)
              â"‚   â""â"€ gateway.record_api_metric() → interface_metrics.py → metrics_core.py (Layer 2)
              â""â"€ Return response

Total layers touched: 0, 1, 2, 3 (all via gateway.py)
```

---

### Example 2: System Initialization Dependency Order
```
Cold start sequence (lambda_function.py):

1. Layer 0 initialization:
   â""â"€ LOGGING initializes (< 5ms)

2. Layer 1 initialization:
   â"œâ"€ SECURITY initializes (uses LOGGING) (< 5ms)
   â"œâ"€ UTILITY initializes (< 2ms)
   â""â"€ SINGLETON initializes (uses LOGGING) (< 3ms)

3. Layer 2 initialization:
   â"œâ"€ METRICS initializes (uses LOGGING) (< 5ms)
   â"œâ"€ CACHE initializes (uses LOGGING, METRICS) (< 10ms)
   â""â"€ CONFIG initializes (uses LOGGING, CACHE, SECURITY) (< 15ms)

4. Layer 3 initialization:
   â"œâ"€ HTTP_CLIENT initializes (uses all lower layers) (< 10ms)
   â"œâ"€ WEBSOCKET initializes (uses LOGGING, SECURITY, METRICS) (< 8ms)
   â""â"€ CIRCUIT_BREAKER initializes (uses LOGGING, METRICS) (< 5ms)

5. Layer 4 initialization:
   â"œâ"€ INITIALIZATION completes (coordinates above) (< 5ms)
   â""â"€ DEBUG available for health checks (< 3ms)

Total cold start: 50-80ms typical
```

---

## INTEGRATION NOTES

### Cross-Reference with Other Neural Maps

**NM01 (Architecture):**
- All interfaces here are documented in NM01 with full signatures
- Dependency layers inform initialization order in NM01-ARCH-02

**NM02-RULES-Import.md (Companion File):**
- Import rules (RULE-01 to RULE-05) define how layers access each other
- Validation checklists ensure new dependencies follow layer hierarchy

**NM03 (Operations):**
- Dependency layers affect operational flow
- Layer 0 must be operational before Layer 1, etc.

**NM04 (Decisions):**
- NM04-DEC-08: Why LOGGING has no dependencies (base layer)
- NM04-DEC-01: Why SIMA pattern enforces layer separation

**NM06 (Learned Experiences):**
- NM06-LESS-04: No dependencies for base layer (learned from circular import bugs)
- NM06-BUG-02: Circular import issues led to layer design

---

## END NOTES

**Key Takeaways:**
1. 5 dependency layers (0-4) prevent circular imports
2. Lower layers never depend on higher layers
3. All cross-layer access via gateway.py
4. Layer 0 (LOGGING) is foundation with zero dependencies
5. Layer 3 (Services) depends on all lower layers for full functionality

**File Statistics:**
- Total REF IDs: 8 (DEP-01 to DEP-05, CACHE-DEP, HTTP-DEP, CONFIG-DEP)
- Total lines: ~350
- Priority: 5 HIGH, 3 CRITICAL

**Related Files:**
- NM02-INDEX-Dependencies.md (Router to this file)
- NM02-RULES-Import.md (Import rules and validation)
- NM01-INDEX-Architecture.md (Interface documentation)

# EOF

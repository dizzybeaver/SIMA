# NM02-Dependencies-Layers_DEP-04.md - DEP-04

# DEP-04: Layer 3 - Service Infrastructure

**Category:** NM02 - Dependencies
**Topic:** Dependency Layers
**Priority:** 🟡 High
**Status:** Active
**Created:** 2024-04-15
**Last Updated:** 2025-10-24

---

## Summary

Layer 3 handles external communication and service reliability: HTTP_CLIENT (API calls), WEBSOCKET (persistent connections), and CIRCUIT_BREAKER (failure protection). These interfaces depend on all lower layers (0-2) for complete functionality.

---

## Context

Layer 3 sits at the service boundary - where Lambda communicates with external systems. These interfaces need logging (Layer 0), security/utilities (Layer 1), and storage/monitoring (Layer 2) to function properly.

**Layer 3 Philosophy:**
- External communication
- Service reliability
- Comprehensive dependencies
- Business-facing functionality

---

## Content

### Layer 3 Interfaces

#### HTTP_CLIENT
```
HTTP_CLIENT
├─ Dependencies: LOGGING, SECURITY, CACHE, METRICS
├─ Purpose: HTTP request handling
├─ Used by: homeassistant_extension, external integrations
└─ Why Layer 3: Needs security (validation), cache (responses), metrics (tracking)
```

**Functions:**
- `http_get()` - GET request
- `http_post()` - POST request
- `http_put()` - PUT request
- `http_delete()` - DELETE request

**Dependency Pattern:**
```python
# In http_client_core.py
from gateway import (
    log_info,           # Layer 0 (LOGGING)
    validate_url,       # Layer 1 (SECURITY)
    cache_get,          # Layer 2 (CACHE)
    cache_set,          # Layer 2 (CACHE)
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
    cache_set(f"http_{url}", response)  # Cache response
    
    return response
```

**Real-World Impact:**
- Handles all external API calls
- Average request: 50-200ms
- Cache hit savings: ~180ms
- Success rate: 99.5% typical

#### WEBSOCKET
```
WEBSOCKET
├─ Dependencies: LOGGING, SECURITY, METRICS
├─ Purpose: WebSocket connections
├─ Used by: homeassistant_extension (Home Assistant websocket)
└─ Why Layer 3: Similar to HTTP_CLIENT, needs security and monitoring
```

**Functions:**
- `ws_connect()` - Establish connection
- `ws_send()` - Send message
- `ws_receive()` - Receive message
- `ws_close()` - Close connection

**Dependency Pattern:**
```python
# In websocket_core.py
from gateway import (
    log_info,           # Layer 0
    validate_url,       # Layer 1
    record_metric       # Layer 2
)

def ws_connect(url):
    log_info(f"WebSocket connect: {url}")
    validate_url(url)
    
    connection = _establish_connection(url)
    record_metric("ws_connections", 1.0)
    
    return connection
```

**Real-World Impact:**
- Maintains Home Assistant connection
- Persistent connection reduces overhead
- Real-time event streaming
- Connection recovery on failure

#### CIRCUIT_BREAKER
```
CIRCUIT_BREAKER
├─ Dependencies: LOGGING, METRICS
├─ Purpose: Failure protection
├─ Used by: HTTP_CLIENT, WEBSOCKET (wraps external calls)
└─ Why Layer 3: Protects service layer, needs metrics for failure tracking
```

**Functions:**
- `execute_with_breaker()` - Execute with protection
- `breaker_state()` - Get breaker state
- `reset_breaker()` - Reset breaker

**Dependency Pattern:**
```python
# In circuit_breaker_core.py
from gateway import log_warning, record_metric  # Layer 0 + Layer 2

def execute_with_breaker(operation, *args):
    if _breaker_open():
        log_warning("Circuit breaker OPEN - request blocked")
        record_metric("breaker_blocked", 1.0)
        raise CircuitBreakerError("Breaker open")
    
    try:
        result = operation(*args)
        _record_success()
        return result
    except Exception as e:
        _record_failure()
        log_warning(f"Circuit breaker failure: {e}")
        record_metric("breaker_failure", 1.0)
        raise
```

**Real-World Impact:**
- Prevents cascading failures
- Engagement: < 1% of requests
- Recovery time: 30-60 seconds
- Protects Lambda from overload

### Why Layer 3 Depends on Layer 0-2

**Complete Request Flow:**
```python
def http_get(url):
    # Layer 0: Log the operation
    log_info(f"HTTP GET: {url}")
    
    # Layer 1: Validate input
    if not validate_url(url):
        raise ValueError("Invalid URL")
    
    # Layer 2: Check cache
    cached = cache_get(f"http_{url}")
    if cached:
        return cached
    
    # Layer 3: Make request
    response = _perform_http_request(url)
    
    # Layer 2: Track metrics
    record_api_metric("http_get_time", response.elapsed)
    
    # Layer 2: Cache response
    cache_set(f"http_{url}", response, ttl=300)
    
    return response
```

**Layer 3 needs ALL lower layers:**
- Layer 0: Logging (visibility)
- Layer 1: Security (validation)
- Layer 2: Cache (performance), Metrics (tracking)

### Request Flow Example

**Complete HTTP Request with Dependencies:**
```
User makes HTTP request:
lambda_function.py
  → gateway.py → http_get()
      → interface_http.py → execute_http_operation()
          → http_client_core.py
              ├─ gateway.log_info() → LOGGING (Layer 0)
              ├─ gateway.validate_url() → SECURITY (Layer 1)
              ├─ gateway.cache_get() → CACHE (Layer 2)
              ├─ If cache miss:
              │   ├─ Perform HTTP request
              │   ├─ gateway.cache_set() → CACHE (Layer 2)
              │   └─ gateway.record_api_metric() → METRICS (Layer 2)
              └─ Return response

Layers touched: 0, 1, 2, 3 (all via gateway.py)
```

### Performance Characteristics

**Initialization:**
- HTTP_CLIENT: < 10ms
- WEBSOCKET: < 8ms
- CIRCUIT_BREAKER: < 5ms
- Total Layer 3: ~25ms

**Operation:**
- HTTP request (uncached): 50-200ms
- HTTP request (cached): < 2ms
- WebSocket send: < 5ms
- Circuit breaker check: < 0.5ms

**Success Rates:**
- HTTP: 99.5%
- WebSocket: 99.8%
- Circuit breaker engagement: < 1%

---

## Related Topics

- **DEP-03**: Layer 2 - Storage & Monitoring (used by Layer 3)
- **DEP-05**: Layer 4 - Management & Debug (uses Layer 3)
- **NM01-INT-08**: HTTP_CLIENT interface definition
- **NM01-INT-09**: WEBSOCKET interface definition
- **NM01-INT-10**: CIRCUIT_BREAKER interface definition

---

## Keywords

dependency layer 3, service infrastructure, HTTP_CLIENT, WEBSOCKET, CIRCUIT_BREAKER, external communication, failure protection

---

## Version History

- **2025-10-24**: Atomized from NM02-CORE, migrated to v3.1.0 format
- **2024-04-15**: Original content in NM02-CORE-Dependency Layers

---

**File:** `NM02-Dependencies-Layers_DEP-04.md`
**Location:** `/nmap/NM02/`
**End of Document**

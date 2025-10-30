# NM01-Architecture-InterfacesAdvanced_INT-08.md - INT-08

# INT-08: HTTP_CLIENT Interface

**Category:** NM01 - Architecture  
**Topic:** Interfaces-Advanced  
**Priority:** 🟡 HIGH  
**Status:** Active  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-24

---

## Summary

HTTP client interface for external API calls with retry logic, timeout management, response caching, and circuit breaker integration.

---

## Context

The HTTP_CLIENT interface handles all external HTTP/HTTPS requests, integrating with CACHE for response caching, CIRCUIT_BREAKER for fault tolerance, and METRICS for performance tracking.

**Why it exists:** External API calls are common in Lambda functions. This interface provides a reliable, performant, and observable way to make HTTP requests.

---

## Content

### Overview

```
Router: interface_http.py
Core: http_client_core.py
Purpose: HTTP/HTTPS request management
Pattern: Dictionary-based dispatch
State: HTTP session singleton
Dependency Layer: Layer 3 (External Communication)
```

### Operations (8 total)

```
├─ request: Generic HTTP request
├─ get: HTTP GET request
├─ post: HTTP POST request
├─ put: HTTP PUT request
├─ delete: HTTP DELETE request
├─ reset: Reset HTTP client state
├─ get_state: Get client state
└─ reset_state: Reset client configuration
```

### Gateway Wrappers

```python
# HTTP methods
http_request(method: str, url: str, **kwargs) -> Dict
http_get(url: str, params: Dict = None, **kwargs) -> Dict
http_post(url: str, data: Any = None, json: Dict = None, **kwargs) -> Dict
http_put(url: str, data: Any = None, json: Dict = None, **kwargs) -> Dict
http_delete(url: str, **kwargs) -> Dict

# State management
http_reset() -> bool
http_get_state() -> Dict
http_reset_state() -> bool
```

### Dependencies

```
Uses: LOGGING, CACHE (response caching), CIRCUIT_BREAKER (fault tolerance), METRICS (tracking)
Used by: External API integrations, Home Assistant integration
```

### Features

```
- Automatic retries with exponential backoff
- Configurable timeouts
- Response caching (optional)
- Circuit breaker integration
- Request/response logging
- Metrics tracking (duration, status codes)
- Session reuse via SINGLETON
```

### Usage Example

```python
from gateway import http_get, http_post

# Simple GET request
response = http_get('https://api.example.com/users')
if response['success']:
    users = response['data']

# POST with JSON
data = {'name': 'John', 'email': 'john@example.com'}
response = http_post('https://api.example.com/users', json=data)

# GET with caching
response = http_get(
    'https://api.example.com/config',
    cache_ttl=3600  # Cache for 1 hour
)

# Custom timeout and retries
response = http_get(
    'https://slow-api.example.com/data',
    timeout=30,
    max_retries=5
)
```

---

## Related Topics

- **INT-10**: CIRCUIT_BREAKER - Used for fault tolerance
- **INT-01**: CACHE - Used for response caching
- **INT-04**: METRICS - Used for tracking API calls
- **DEP-04**: Layer 3 (External Communication)

---

## Keywords

HTTP, API, requests, external calls, retry, timeout, circuit breaker, caching

---

## Version History

- **2025-10-24**: Atomized from monolithic file (SIMA v3 migration)
- **2025-10-20**: Original content in IMPLEMENTATION_Advanced.md

---

**File:** `NM01-Architecture-InterfacesAdvanced_INT-08.md`  
**End of Document**

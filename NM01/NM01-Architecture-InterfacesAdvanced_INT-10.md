# NM01-Architecture-InterfacesAdvanced_INT-10.md - INT-10

# INT-10: CIRCUIT_BREAKER Interface

**Category:** NM01 - Architecture  
**Topic:** Interfaces-Advanced  
**Priority:** 🟢 MEDIUM  
**Status:** Active  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-24

---

## Summary

Circuit breaker pattern implementation for fault tolerance, preventing cascading failures by monitoring external service health and temporarily blocking failing services.

---

## Context

The CIRCUIT_BREAKER interface implements the circuit breaker pattern to protect Lambda from repeatedly calling failing external services, improving reliability and performance.

**Why it exists:** When external services fail, retrying immediately wastes resources. Circuit breakers prevent this by "opening" after failures and "closing" after recovery.

---

## Content

### Overview

```
Router: interface_circuit_breaker.py
Core: circuit_breaker_core.py
Purpose: Circuit breaker pattern for fault tolerance
Pattern: Dictionary-based dispatch
State: Circuit breaker states per service
Dependency Layer: Layer 3 (External Communication)
```

### Operations (6 total)

```
├─ get_circuit_breaker: Get or create circuit breaker
├─ execute_with_circuit_breaker: Execute function with protection
├─ get_all_circuit_breaker_states: Get all breaker states
├─ reset_all_circuit_breakers: Reset all breakers
├─ record_success: Record successful call
└─ record_failure: Record failed call
```

### Gateway Wrappers

```python
get_circuit_breaker(service: str, **kwargs) -> CircuitBreaker
execute_with_circuit_breaker(service: str, func: Callable, *args, **kwargs) -> Any
get_all_circuit_breaker_states() -> Dict
reset_all_circuit_breakers() -> int
```

### Circuit Breaker States

```
CLOSED   → Normal operation, all requests pass through
OPEN     → Failing, all requests blocked immediately
HALF_OPEN → Testing recovery, limited requests allowed
```

### Dependencies

```
Uses: LOGGING, METRICS
Used by: HTTP_CLIENT, WEBSOCKET
```

### Usage Example

```python
from gateway import execute_with_circuit_breaker

# Protect external API call
def call_external_api():
    return http_get('https://api.example.com/data')

try:
    result = execute_with_circuit_breaker(
        'external_api',
        call_external_api
    )
except CircuitBreakerOpenError:
    log_warning("Circuit breaker open, using fallback")
    result = get_cached_fallback()
```

---

## Related Topics

- **INT-08**: HTTP_CLIENT - Uses circuit breaker for API calls
- **INT-09**: WEBSOCKET - Uses circuit breaker for connections
- **LESS-05**: Graceful degradation
- **DEP-04**: Layer 3 (External Communication)

---

## Keywords

circuit breaker, fault tolerance, failure protection, cascading failures, resilience

---

## Version History

- **2025-10-24**: Atomized from monolithic file (SIMA v3 migration)
- **2025-10-20**: Original content in IMPLEMENTATION_Advanced.md

---

**File:** `NM01-Architecture-InterfacesAdvanced_INT-10.md`  
**End of Document**

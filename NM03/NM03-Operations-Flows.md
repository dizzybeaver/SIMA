# NM03-Operations-Flows.md

# Operations - Execution Flows

**Category:** NM03 - Operations
**Topic:** Flows
**Items:** 3
**Priority:** 1 Critical, 2 High
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Topic Overview

Operation execution flows show the step-by-step journey of requests through the SUGA pattern layers. These flows demonstrate how simple, complex, and cascading operations execute at runtime.

---

## FLOW-01: Simple Operation (cache_get)

**Priority:** CRITICAL  
**Keywords:** cache, simple-operation, basic-flow, gateway-pattern

### Summary

Shows the 5-hop execution path for a simple cache get operation, demonstrating the clean SUGA pattern with minimal overhead (< 2ms total).

### Flow Diagram

```
User Code: gateway.cache_get("my_key")
    ↓
gateway_wrappers.cache_get(key)
    ↓ calls
gateway_core.execute_operation(CACHE, 'get', key=key)
    ↓ looks up in _INTERFACE_ROUTERS
    ↓ lazy imports interface_cache
interface_cache.execute_cache_operation('get', key=key)
    ↓ looks up in _OPERATION_DISPATCH
    ↓ calls
cache_core._execute_get_implementation(key=key)
    ↓ accesses _CACHE_STORE
    ↓ returns value or None
    ↓ (sanitizes sentinel if present)
interface_cache returns value
    ↓
gateway_core returns value
    ↓
gateway_wrappers returns value
    ↓
User Code receives value

Total Hops: 5 function calls
Latency: < 2ms (all in-memory, O(1) lookups)
```

### Performance Characteristics

- **Wrapper layer:** < 0.1ms (function call)
- **Gateway routing:** < 0.5ms (dict lookup + dispatch)
- **Router dispatch:** < 0.2ms (dict lookup)
- **Core operation:** < 1ms (dict access)
- **Sentinel sanitization:** < 0.1ms (if needed)

### Why 5 Hops

1. **Clean separation of concerns** - Each layer has specific responsibility
2. **Easy to debug** - Can intercept at any layer
3. **Fast path optimization** - Reduces to 3 hops for frequent operations
4. **Maintainable** - Clear layer boundaries

### Related

- **INT-01**: CACHE interface specification
- **ARCH-02**: Gateway execution engine
- **DEC-03**: Router pattern decision
- **DEC-07**: Fast path optimization

---

## FLOW-02: Complex Operation (HTTP POST)

**Priority:** HIGH  
**Keywords:** http, complex-operation, multi-interface, external-api

### Summary

Demonstrates a complex operation crossing multiple interfaces (LOGGING, SECURITY, CACHE, METRICS, HTTP_CLIENT) with external I/O, showing full SUGA pattern coordination.

### Flow Diagram

```
User Code: gateway.http_post(url, data)
    ↓
gateway_wrappers.http_post(url, data)
    ↓
gateway_core.execute_operation(HTTP_CLIENT, 'post', url=url, data=data)
    ↓
interface_http.execute_http_operation('post', url, data)
    ↓
http_client_core._execute_post_implementation(url, data)
    ↓
    ├── gateway.log_info(f"HTTP POST: {url}") → LOGGING
    ├── gateway.validate_url(url) → SECURITY
    ├── gateway.sanitize_input(data) → SECURITY
    ├── gateway.cache_get(f"http_post_{url}") → CACHE
    │   ├── Cache miss, proceed with request
    │
    ├── _perform_http_request(url, data)
    │   ├── Circuit breaker check
    │   ├── HTTP request (50-200ms external latency)
    │
    ├── gateway.cache_set(f"http_post_{url}", response) → CACHE
    ├── gateway.record_api_metric("http_post", time) → METRICS
    │
    └── return response

Layers Touched: 5 (LOGGING, SECURITY, CACHE, METRICS, HTTP_CLIENT)
Total Latency: 55-210ms (mostly external HTTP call)
```

### Performance Breakdown

- **Gateway routing:** < 1ms
- **Security validation:** < 2ms
- **Cache lookup:** < 1ms
- **HTTP request:** 50-200ms (external)
- **Cache save:** < 2ms
- **Metrics recording:** < 1ms
- **Total overhead:** < 10ms
- **External wait:** 50-200ms

### Why Complex

- Crosses multiple interfaces (via gateway)
- Involves external I/O
- Requires security, caching, monitoring
- Demonstrates full SUGA pattern coordination

### Related

- **INT-08**: HTTP_CLIENT interface
- **DEP-04**: HTTP dependency layer
- **ERROR-02**: Graceful degradation for HTTP failures

---

## FLOW-03: Cascading Operation

**Priority:** HIGH  
**Keywords:** cascading, dependencies, multi-interface, chain

### Summary

Shows how operations cascade across multiple interfaces, with each operation triggering sub-operations. Demonstrates gateway-mediated cross-interface coordination.

### Flow Diagram

```
User Code: process_alexa_request(event)
    ↓
gateway.log_info("Alexa request received") → LOGGING
    ↓
gateway.get_config("home_assistant_url") → CONFIG
    ├── gateway.cache_get("config_ha_url") → CACHE
    ├── If cache miss: gateway.get_parameter("ha_url") → AWS SSM
    ├── gateway.cache_set("config_ha_url", value) → CACHE
    └── return value
    ↓
gateway.http_get(ha_url + "/api/states") → HTTP_CLIENT
    ├── gateway.log_info("Fetching HA states") → LOGGING
    ├── gateway.validate_url(url) → SECURITY
    ├── gateway.cache_get(f"http_{url}") → CACHE
    ├── If cache miss:
    │   ├── gateway.record_metric("http_request_start") → METRICS
    │   ├── _perform_http_get(url)
    │   ├── gateway.cache_set(f"http_{url}", response) → CACHE
    │   └── gateway.record_metric("http_request_end") → METRICS
    └── return response
    ↓
process_states(response)
    ↓
gateway.log_info("Request completed") → LOGGING
    ↓
return result

Interfaces Involved: LOGGING, CONFIG, CACHE, SECURITY, HTTP_CLIENT, METRICS
Total Operations: 12+ across 6 interfaces
Total Latency: 60-220ms (depends on cache hits)
```

### Performance Impact

- **Without caching:** 200-300ms
- **With caching:** 5-10ms (cached config + cached HTTP)
- **Cache hit rate:** 80-90% typical

### Cascading Effect

- Each operation triggers sub-operations
- Gateway mediates all cross-interface calls (RULE-01)
- CACHE reduces redundant operations
- METRICS tracks entire chain

### Related

- **DEP-03**: Cross-interface dependency patterns
- **DEP-04**: HTTP dependency layer
- **RULE-01**: Gateway-only cross-interface imports

---

## Related Topics

- **Operations-Pathways**: System-level pathways (PATH-01 to PATH-05)
- **Operations-ErrorHandling**: Error flows through these patterns
- **Operations-Tracing**: Full request traces

---

## Navigation

**Up:** NM03-Operations_Index.md  
**Category:** NM03 - Operations  
**Siblings:** Pathways, ErrorHandling, Tracing

---

**File:** `NM03/NM03-Operations-Flows.md`  
**End of Document**

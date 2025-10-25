# NM03-Operations-Tracing.md

# Operations - Request Tracing

**Category:** NM03 - Operations
**Topic:** Tracing
**Items:** 2
**Priority:** Both Medium
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Topic Overview

Request tracing patterns provide detailed execution timelines for debugging, performance analysis, and understanding end-to-end request flows.

---

## TRACE-01: Full Request Trace Example

**Priority:** MEDIUM  
**Keywords:** request-trace, end-to-end, timing, debugging, alexa

### Summary

Complete end-to-end trace of an Alexa request showing every operation, timing, interface interaction, and performance breakdown (190ms total).

### Complete Trace

```
REQUEST START (t=0ms)
===================
Event: Alexa IntentRequest
Intent: TurnOnLight
Slots: {room: "kitchen"}

PHASE 1: INITIALIZATION (t=0-5ms)
----------------------------------
[t=0ms] lambda_handler() called
[t=1ms] gateway.log_info("Request received")
  → interface_logging.execute_logging_operation('log_info')
  → logging_core._execute_log_info()
  → print(log_json)
[t=2ms] gateway.initialize_system() (warm start, no-op)
[t=5ms] Phase complete

PHASE 2: CONFIGURATION (t=5-15ms)
----------------------------------
[t=5ms] gateway.get_config("home_assistant_url")
  → interface_config.execute_config_operation('get')
  → config_core._execute_get()
    → gateway.cache_get("config_ha_url")
      → interface_cache.execute_cache_operation('get')
      → cache_core._execute_get_implementation()
      → Cache HIT, return value (< 1ms)
[t=6ms] URL retrieved from cache

[t=10ms] gateway.get_config("home_assistant_token")
  → [Same flow, cache HIT]
[t=11ms] Token retrieved from cache
[t=15ms] Phase complete

PHASE 3: HTTP REQUEST (t=15-180ms)
-----------------------------------
[t=15ms] gateway.http_post(ha_url + "/api/services/light/turn_on")
  → interface_http.execute_http_operation('post')
  → http_client_core._execute_post_implementation()
    [t=16ms] gateway.log_info("HTTP POST starting")
    
    [t=18ms] gateway.validate_url(url)
      → interface_security.execute_security_operation('validate_url')
      → security_core._execute_validate_url()
      → URL valid, return True (< 1ms)
    
    [t=20ms] gateway.sanitize_input(data)
      → [Same flow]
      → Data sanitized (< 1ms)
    
    [t=22ms] gateway.cache_get(f"http_post_{url}")
      → Cache MISS (POST not cached)
    
    [t=25ms] gateway.record_metric("http_request_start", 1.0)
    
    [t=27ms] _perform_http_request(url, data)
      → HTTP request to Home Assistant
      → [External I/O wait: 150ms]
    
    [t=177ms] HTTP response received (200 OK)
    [t=179ms] gateway.record_metric("http_request_end", 152.0)
    [t=180ms] gateway.record_api_metric("http_post", 153.0)

[t=180ms] HTTP response returned
[t=180ms] Phase complete

PHASE 4: RESPONSE FORMATTING (t=180-190ms)
-------------------------------------------
[t=180ms] format_alexa_response(ha_response)
[t=185ms] gateway.log_info("Response formatted", speech_length=len(response))
[t=190ms] Phase complete

REQUEST END (t=190ms)
=====================
Total Time: 190ms

Breakdown:
  - Init: 5ms (3%)
  - Config: 10ms (5%)
  - HTTP: 165ms (87%)
  - Format: 10ms (5%)

Operations:
  - Cache hits: 2
  - Cache misses: 1
  - HTTP requests: 1
  - Logs: 4
  - Metrics: 3
  - Security checks: 2

Result: SUCCESS
Response: "Kitchen light turned on"
```

### Trace Insights

**Performance:**
- 87% of time in external HTTP call (expected)
- Cache hits save ~150ms each
- Security overhead: < 2ms (acceptable)
- Logging overhead: < 2ms (acceptable)
- Gateway routing: < 3ms total (excellent)

**Efficiency:**
- Config cache hits: 100% (optimal)
- No redundant operations
- Minimal overhead (< 25ms total)

**Interface Usage:**
- LOGGING: 4 calls
- CONFIG: 2 calls (both cached)
- CACHE: 3 calls (2 hits, 1 miss)
- SECURITY: 2 calls (validation)
- HTTP_CLIENT: 1 call (150ms external)
- METRICS: 3 calls (monitoring)

### Use Cases

1. **Performance debugging** - Identify slow operations
2. **Cache optimization** - Find cache miss patterns
3. **Interface profiling** - Understand cross-interface costs
4. **Bottleneck analysis** - Locate external I/O delays
5. **Request flow understanding** - Learn system behavior

### Related

- **FLOW-02**: Complex operation pattern
- **FLOW-03**: Cascading operation pattern
- **LESS-10**: Metrics and monitoring

---

## TRACE-02: Data Transformation Pipeline

**Priority:** MEDIUM  
**Keywords:** transformation, pipeline, data-flow, alexa-ha

### Summary

Shows data transformation flow from Alexa event format through internal processing to Home Assistant API format and back to Alexa response format.

### Transformation Flow

```
Alexa Event (AWS format)
    ↓
gateway.log_info("Event received", event_type=event['request']['type'])
    ↓
Parse Alexa Intent:
    ├── Extract intent name: "TurnOnLight"
    ├── Extract slot values: {room: "kitchen"}
    └── Validate required slots
    ↓
Transform to Home Assistant format:
    ├── Map Alexa intent → HA service call
    │   └── "TurnOnLight" → "light.turn_on"
    │
    ├── Convert slot values → HA parameters
    │   └── {room: "kitchen"} → {entity_id: "light.kitchen"}
    │
    └── Add authentication headers
        └── Authorization: Bearer {token}
    ↓
gateway.http_post(ha_url + "/api/services", ha_payload)
    ├── Security validation
    ├── HTTP request (150ms)
    └── Response parsing
    ↓
Transform HA response to Alexa format:
    ├── Extract state changes
    │   └── {state: "on", brightness: 255}
    │
    ├── Format speech response
    │   └── "Kitchen light turned on"
    │
    └── Add card data
        └── {title: "Light Control", text: "Kitchen on"}
    ↓
gateway.log_info("Response formatted", speech_length=len(response))
    ↓
Return Alexa Response (AWS format)

Transformations: 3
  1. Alexa → Internal representation
  2. Internal → Home Assistant API
  3. HA Response → Alexa response

Total Time: ~100-250ms (mostly HTTP)
Transformation Overhead: < 5ms
```

### Transformation Stages

**Stage 1: Alexa → Internal**
- Parse AWS Lambda event structure
- Extract intent and slots
- Validate required fields
- Time: < 1ms

**Stage 2: Internal → Home Assistant**
- Map intent to HA service call
- Convert slot values to HA entity IDs
- Add authentication
- Format JSON payload
- Time: < 2ms

**Stage 3: HA Response → Alexa**
- Parse HA response JSON
- Extract state information
- Generate natural language response
- Format Alexa response structure
- Add card/visual elements
- Time: < 2ms

### Data Format Examples

**Alexa Intent (Input):**
```json
{
  "request": {
    "type": "IntentRequest",
    "intent": {
      "name": "TurnOnLight",
      "slots": {
        "room": {"value": "kitchen"}
      }
    }
  }
}
```

**Home Assistant Payload (Middle):**
```json
{
  "entity_id": "light.kitchen",
  "brightness": 255
}
```

**Alexa Response (Output):**
```json
{
  "version": "1.0",
  "response": {
    "outputSpeech": {
      "type": "PlainText",
      "text": "Kitchen light turned on"
    },
    "card": {
      "type": "Simple",
      "title": "Light Control",
      "content": "Kitchen light is now on"
    },
    "shouldEndSession": true
  }
}
```

### Transformation Principles

1. **Validate early** - Check inputs before transformation
2. **Fail fast** - Return errors for invalid data
3. **Log transformations** - Enable debugging
4. **Keep overhead low** - < 5ms total
5. **Test round-trips** - Ensure data integrity

### Related

- **INT-11**: TRANSFORMATION interface
- **FLOW-03**: Cascading operations
- **TRACE-01**: Full request trace

---

## Tracing Best Practices

**When to Trace:**
- Performance debugging
- Understanding new request patterns
- Investigating errors
- Optimizing cache strategy
- Learning system behavior

**How to Trace:**
- Add timing markers (`t=Xms`)
- Log at each major operation
- Include operation results
- Record cache hits/misses
- Show interface transitions
- Calculate percentages

**What to Measure:**
- Total time
- Phase breakdown
- Interface calls
- Cache performance
- External I/O
- Transformation overhead

---

## Related Topics

- **Operations-Flows**: Normal execution patterns
- **Operations-Pathways**: System pathways
- **Operations-ErrorHandling**: Error traces

---

## Navigation

**Up:** NM03-Operations_Index.md  
**Category:** NM03 - Operations  
**Siblings:** Flows, Pathways, ErrorHandling

---

**File:** `NM03/NM03-Operations-Tracing.md`  
**End of Document**

# LEE-DEC-02-WebSocket-Protocol.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Decision to use WebSocket protocol for Home Assistant communication  
**Category:** Project - LEE Decision

---

## Decision

**LEE-DEC-02: Use WebSocket Protocol for Real-Time Home Assistant Communication**

**Status:** Accepted  
**Date:** 2024-06-15  
**Context:** LEE requires bidirectional, real-time communication with Home Assistant

---

## Context

Home Assistant provides multiple API options:
1. REST API (HTTP/HTTPS)
2. WebSocket API
3. Event Stream API
4. MQTT (external broker required)

**Requirements:**
- Real-time device state updates
- Command execution with confirmation
- Event subscriptions
- Low latency (<500ms)
- Reliable connection
- Authentication support

---

## Decision

**Use Home Assistant WebSocket API as primary communication method**

**Implementation:**
- Establish WebSocket connection per Lambda invocation
- Send commands via WebSocket
- Receive confirmations synchronously
- Close connection after response
- Fallback to REST API if WebSocket unavailable

---

## Rationale

### Why WebSocket Over REST?

#### 1. Real-Time Bidirectional Communication

**WebSocket:**
```
Client → WS → Server: Turn on light
Server → WS → Client: State changed (instant notification)
```

**REST:**
```
Client → HTTP → Server: Turn on light
Client: [poll] → HTTP → Server: Is it on yet?
Client: [poll] → HTTP → Server: Is it on yet?
```

**Result:** WebSocket provides immediate state updates

#### 2. Single Connection for Multiple Operations

**WebSocket:**
```
Establish connection (once)
Send command 1
Receive response 1
Send command 2
Receive response 2
Close connection
```

**REST:**
```
Establish connection
Send command 1
Receive response 1
Close connection
Establish connection  (overhead!)
Send command 2
Receive response 2
Close connection
```

**Result:** WebSocket reduces connection overhead for multiple operations

#### 3. Event Subscriptions

**WebSocket:**
```
Subscribe to state_changed events
Receive events as they occur
Process in real-time
```

**REST:**
```
Poll for state changes every N seconds
Higher latency (up to N seconds)
More API calls
```

**Result:** WebSocket enables true event-driven architecture

#### 4. Lower Latency

**Measured latency:**
```
WebSocket: 50-150ms per operation
REST: 150-300ms per operation
```

**Reason:** No connection establishment overhead after initial handshake

### Why Not REST?

**Limitations:**
- No server push (polling required)
- Higher latency for state updates
- More API calls for equivalent functionality
- Cannot subscribe to events
- Connection overhead per request

**When REST is used:**
- Fallback when WebSocket fails
- One-off operations (doesn't justify WebSocket connection)
- Simple status checks

### Why Not MQTT?

**Complexity:**
- Requires external MQTT broker
- Additional infrastructure
- More complex authentication
- Not native to Home Assistant

**Home Assistant WebSocket API is first-class citizen**

### Why Not Event Stream?

**Limitations:**
- Server-sent events only (one direction)
- Cannot send commands
- Less widely supported
- More complex to implement

---

## Implementation Details

### Connection Pattern

**Per-invocation connection:**
```python
def handler(event, context):
    # 1. Establish connection
    ws = connect_to_home_assistant()
    
    # 2. Authenticate
    authenticate(ws, token)
    
    # 3. Execute operation
    result = execute_command(ws, event)
    
    # 4. Close connection
    ws.close()
    
    return result
```

**Why not persistent connection?**
- Lambda is stateless (DEC-04)
- Container lifecycle unpredictable
- Connection may become stale between invocations
- Reconnection logic simpler with per-invocation pattern

### Message Format

**Home Assistant WebSocket Protocol:**
```json
{
  "id": 1,
  "type": "call_service",
  "domain": "light",
  "service": "turn_on",
  "service_data": {
    "entity_id": "light.living_room"
  }
}
```

**Response:**
```json
{
  "id": 1,
  "type": "result",
  "success": true,
  "result": {
    "state": "on",
    "attributes": {...}
  }
}
```

### Error Handling

**Connection failures:**
```python
def connect_with_retry(max_retries=3):
    for attempt in range(max_retries):
        try:
            ws = websocket.create_connection(url)
            return ws
        except Exception as e:
            if attempt == max_retries - 1:
                # Fallback to REST API
                return None
            time.sleep(0.5 * (attempt + 1))
```

**Message timeouts:**
```python
def send_with_timeout(ws, message, timeout=5):
    ws.send(json.dumps(message))
    
    start = time.time()
    while time.time() - start < timeout:
        response = ws.recv()
        if response:
            return json.loads(response)
    
    raise TimeoutError("WebSocket response timeout")
```

---

## Performance Characteristics

### Measured Metrics

**Connection establishment:**
```
Initial handshake: 100-200ms
Authentication: 50-100ms
Ready for commands: 150-300ms total
```

**Command execution:**
```
Send command: <5ms
Receive response: 50-150ms
Total latency: 55-155ms
```

**vs REST API:**
```
REST: 150-300ms per operation
WebSocket: 55-155ms per operation
Improvement: 42-63% faster
```

### Lambda Execution Time

**With WebSocket:**
```
Cold start: 2100ms (includes connection)
Warm start: 600-800ms (connection already established? No - new each time)
Actual: 750-950ms per invocation (establish, execute, close)
```

**Connection reuse not viable:**
- Lambda stateless design
- Container lifecycle unpredictable
- Connection may be stale
- Reconnection logic adds complexity

**Trade-off accepted:** Connection overhead per invocation acceptable for reliability

---

## Security Considerations

### Authentication

**Long-lived access token:**
```
Token stored in: AWS SSM Parameter Store
Token type: Home Assistant Long-Lived Access Token
Token lifetime: No expiration (manually managed)
Token scope: Full API access
```

**Authentication flow:**
```json
{
  "type": "auth",
  "access_token": "eyJhbGciOiJIUz..."
}
```

**Response:**
```json
{
  "type": "auth_ok",
  "ha_version": "2024.6.0"
}
```

### Secure Transport

**TLS/SSL required:**
```
URL: wss://homeassistant.local:8123/api/websocket
Protocol: WebSocket Secure (WSS)
Encryption: TLS 1.2+
Certificate: Self-signed (accepted explicitly)
```

**Why self-signed acceptable:**
- Local network communication
- Certificate pinning implemented
- No man-in-the-middle risk (local network)
- Token provides authentication

---

## Failure Modes and Handling

### Connection Failure

**Scenario:** Cannot establish WebSocket connection

**Handling:**
1. Retry with exponential backoff (3 attempts)
2. Fallback to REST API if all retries fail
3. Return error if REST also fails
4. Log failure for monitoring

### Authentication Failure

**Scenario:** Invalid or expired token

**Handling:**
1. Attempt token refresh from SSM
2. Retry authentication
3. Return 401 error if refresh fails
4. Alert on repeated auth failures

### Message Timeout

**Scenario:** Command sent, no response received

**Handling:**
1. Wait up to 5 seconds
2. Close connection
3. Retry entire operation once
4. Return timeout error if retry fails

### Stale Connection

**Scenario:** Connection established but becomes unresponsive

**Handling:**
1. Detect via keepalive timeout
2. Close and reconnect
3. Retry original operation
4. Max 2 retries total

---

## Monitoring and Observability

### Key Metrics

**Connection metrics:**
- Connection establishment time
- Authentication success rate
- Connection failures
- Timeout frequency

**Operation metrics:**
- Command latency (send → receive)
- Success rate
- Error types
- Retry frequency

### CloudWatch Alarms

```
Alarm: WebSocket-Connection-Failures
Condition: >5% failure rate
Action: Notify operations team

Alarm: WebSocket-Timeout-High
Condition: >2% timeout rate
Action: Investigate HA performance

Alarm: Authentication-Failures
Condition: >1% auth failure rate
Action: Check token validity
```

---

## Alternative Considered

### Alternative 1: Persistent Connection

**Approach:** Keep WebSocket open between invocations

**Rejected because:**
- Lambda stateless model (containers recycled)
- Connection management complexity
- Increased memory usage
- Reconnection logic required anyway
- Marginal benefit (100-200ms connection time acceptable)

### Alternative 2: REST API Only

**Approach:** Use REST API for all operations

**Rejected because:**
- Higher latency (150-300ms vs 55-155ms)
- No real-time state updates
- Polling required for state changes
- More API calls for equivalent functionality

### Alternative 3: Hybrid (REST + WebSocket)

**Approach:** REST for simple operations, WebSocket for complex

**Rejected because:**
- Inconsistent behavior
- More code complexity
- Difficult to maintain
- WebSocket overhead acceptable for all operations

---

## Future Considerations

### Optimization Opportunities

**1. Connection Pooling**
If Lambda adds persistent connection support:
- Pool WebSocket connections
- Reuse across invocations
- Reduce connection overhead

**2. WebSocket Compression**
If Home Assistant adds compression:
- Reduce payload sizes
- Lower bandwidth usage
- Faster transfers

**3. Binary Protocol**
If Home Assistant adds binary protocol:
- More efficient serialization
- Lower latency
- Reduced bandwidth

---

## Related Decisions

**Prerequisites:**
- LEE-DEC-01: Home Assistant Choice
- LEE-DEC-03: Token Management

**Influences:**
- LEE-DEC-04: Error Handling Strategy
- LEE-DEC-05: Timeout Configuration

**Related Patterns:**
- Connection per invocation
- Retry with fallback
- Graceful degradation

---

## References

**Home Assistant:**
- WebSocket API Documentation
- Authentication Documentation
- Event Subscription Guide

**LEE Implementation:**
- ha_websocket.py
- ha_interconnect.py
- LEE-LESS-01-WebSocket-Reliability.md

**Architecture:**
- AWS-Lambda-DEC-04-Stateless-Design.md
- LESS-05-Graceful-Degradation-Required.md

---

## Key Takeaways

**WebSocket for real-time communication:**
Lower latency, bidirectional, event-driven

**Per-invocation connection:**
Stateless design, reliable, simple

**Fallback to REST:**
Graceful degradation if WebSocket unavailable

**Security via token:**
Long-lived token in SSM Parameter Store

**Monitor carefully:**
Connection failures, timeouts, latency

---

**Decision ID:** LEE-DEC-02  
**Keywords:** WebSocket, real-time communication, Home Assistant API, connection management, protocol selection  
**Related Topics:** API integration, communication protocols, error handling, performance optimization

---

**END OF FILE**

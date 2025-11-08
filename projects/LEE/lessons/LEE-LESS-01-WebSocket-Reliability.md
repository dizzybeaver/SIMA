# LEE-LESS-01-WebSocket-Reliability.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** WebSocket connection reliability patterns  
**Category:** Project - LEE  
**Type:** Lesson

---

## LESSON

**WebSocket connections can fail unpredictably. Implement automatic reconnection, exponential backoff, and connection health checks for reliable Home Assistant integration.**

---

## CONTEXT

Early LEE implementation experienced intermittent Home Assistant connection failures. Users reported voice commands failing with "Home Assistant unavailable" errors. Investigation revealed WebSocket connections timing out or closing unexpectedly.

---

## PROBLEM

### Initial Implementation (Naive)

```python
# ❌ WRONG - No reconnection, no health checks
import websockets
import json

# Global connection
ws_connection = None

async def connect():
    global ws_connection
    ws_connection = await websockets.connect(HA_URL)
    # Authenticate...
    return ws_connection

async def send_command(command):
    # Assumes connection is always valid
    await ws_connection.send(json.dumps(command))
    response = await ws_connection.recv()
    return json.loads(response)
```

**Problems:**
```
1. Connection times out after 15 minutes of inactivity
   → Next command fails
   
2. Network hiccup closes connection
   → All subsequent commands fail
   
3. Home Assistant restarts
   → Connection lost, no recovery
   
4. No way to detect connection is dead
   → Commands hang indefinitely
```

**User Impact:**
```
Error rate: 15-20% of voice commands
- "Alexa, turn on lights" → "Home Assistant unavailable"
- User tries again → Sometimes works, sometimes doesn't
- Frustrating, unpredictable experience
```

---

## DISCOVERY

### Connection Lifecycle

**Measured connection behavior:**
```
Connection established (cold start):
    ↓
Works fine for ~15 minutes
    ↓
Idle timeout (HA closes connection)
    ↓
Next command attempts to use dead connection
    ↓
websockets.exceptions.ConnectionClosed
    ↓
Error to user
```

**Network Issues:**
```
Scenario: Brief network interruption (2 seconds)
    ↓
WebSocket connection lost
    ↓
Lambda doesn't know connection is dead
    ↓
Next command hangs for 30 seconds
    ↓
Lambda timeout
    ↓
Error to user
```

### Root Causes Identified

**1. No Reconnection Logic**
- Connection closed → stayed closed
- No automatic recovery
- Required cold start to reconnect

**2. No Connection Health Checks**
- Couldn't detect dead connections
- Waited until command failed
- No proactive monitoring

**3. No Timeout Handling**
- Commands could hang indefinitely
- Exceeded Lambda timeout
- No graceful degradation

---

## SOLUTION

### 1. Automatic Reconnection

```python
# ✅ CORRECT - Automatic reconnection
import asyncio
import websockets
from typing import Optional

class HAWebSocket:
    def __init__(self, url: str, token: str):
        self.url = url
        self.token = token
        self.ws: Optional[websockets.WebSocketClientProtocol] = None
        self.reconnect_delay = 1  # Start with 1 second
        self.max_reconnect_delay = 60  # Max 60 seconds
    
    async def ensure_connected(self):
        """Ensure connection is active, reconnect if needed."""
        if self.ws is None or self.ws.closed:
            await self.connect()
    
    async def connect(self):
        """Connect with exponential backoff."""
        while True:
            try:
                self.ws = await asyncio.wait_for(
                    websockets.connect(self.url),
                    timeout=10
                )
                
                # Authenticate
                await self.authenticate()
                
                # Reset reconnect delay on success
                self.reconnect_delay = 1
                
                log_info("Connected to Home Assistant")
                break
                
            except Exception as e:
                log_error(f"Connection failed: {e}")
                log_info(f"Retrying in {self.reconnect_delay} seconds")
                
                await asyncio.sleep(self.reconnect_delay)
                
                # Exponential backoff
                self.reconnect_delay = min(
                    self.reconnect_delay * 2,
                    self.max_reconnect_delay
                )
    
    async def send_command(self, command: dict):
        """Send command with automatic reconnection."""
        # Ensure connected before sending
        await self.ensure_connected()
        
        try:
            await self.ws.send(json.dumps(command))
            response = await asyncio.wait_for(
                self.ws.recv(),
                timeout=5
            )
            return json.loads(response)
            
        except (websockets.ConnectionClosed, asyncio.TimeoutError) as e:
            log_warning(f"Command failed: {e}, reconnecting")
            
            # Mark connection as dead
            self.ws = None
            
            # Retry with new connection
            await self.ensure_connected()
            await self.ws.send(json.dumps(command))
            response = await asyncio.wait_for(
                self.ws.recv(),
                timeout=5
            )
            return json.loads(response)
```

### 2. Connection Health Checks

```python
class HAWebSocket:
    def __init__(self, url: str, token: str):
        # ... previous init ...
        self.last_ping = time.time()
        self.ping_interval = 30  # Ping every 30 seconds
    
    async def health_check(self):
        """Periodic health check via ping."""
        while True:
            await asyncio.sleep(self.ping_interval)
            
            if self.ws and not self.ws.closed:
                try:
                    # Send ping
                    pong = await asyncio.wait_for(
                        self.ws.ping(),
                        timeout=5
                    )
                    await pong
                    self.last_ping = time.time()
                    log_debug("WebSocket ping successful")
                    
                except Exception as e:
                    log_warning(f"Ping failed: {e}")
                    # Mark connection as dead
                    self.ws = None
    
    async def is_healthy(self) -> bool:
        """Check if connection is healthy."""
        if self.ws is None or self.ws.closed:
            return False
        
        # Connection considered unhealthy if no successful ping in 60s
        time_since_ping = time.time() - self.last_ping
        return time_since_ping < 60
```

### 3. Circuit Breaker Pattern

```python
class CircuitBreaker:
    def __init__(self, failure_threshold: int = 5, timeout: int = 60):
        self.failure_threshold = failure_threshold
        self.timeout = timeout
        self.failures = 0
        self.last_failure_time = None
        self.state = 'closed'  # closed, open, half-open
    
    def is_open(self) -> bool:
        """Check if circuit is open (failing)."""
        if self.state == 'open':
            # Check if timeout expired
            if time.time() - self.last_failure_time >= self.timeout:
                self.state = 'half-open'
                return False
            return True
        return False
    
    def record_success(self):
        """Record successful request."""
        self.failures = 0
        self.state = 'closed'
    
    def record_failure(self):
        """Record failed request."""
        self.failures += 1
        self.last_failure_time = time.time()
        
        if self.failures >= self.failure_threshold:
            self.state = 'open'
            log_warning(f"Circuit breaker opened after {self.failures} failures")

# Usage
circuit_breaker = CircuitBreaker()

async def send_command_with_circuit_breaker(command):
    if circuit_breaker.is_open():
        raise Exception("Circuit breaker open, Home Assistant unavailable")
    
    try:
        result = await ha_websocket.send_command(command)
        circuit_breaker.record_success()
        return result
    except Exception as e:
        circuit_breaker.record_failure()
        raise
```

---

## MEASUREMENTS

### Before Optimization

```
Error Rate:
- Overall: 15-20% of commands
- Connection errors: 12%
- Timeout errors: 3%
- Other: 3%

User Experience:
- Frustrating
- Unpredictable
- Support tickets frequent

Connection Lifetime:
- Average: 12 minutes
- Max: 15 minutes (timeout)
- Reconnection: Manual (cold start only)
```

### After Optimization

```
Error Rate:
- Overall: < 1% of commands
- Connection errors: 0.3% (transient network issues)
- Timeout errors: 0.2%
- Other: 0.5%

User Experience:
- Reliable
- Predictable
- Zero support tickets about HA connection

Connection Lifetime:
- Average: Indefinite (automatic reconnection)
- Reconnection: Automatic, transparent
- Health checks: Every 30 seconds
```

**Improvement:** 95% reduction in connection-related errors

---

## ADDITIONAL PATTERNS

### 4. Graceful Degradation

```python
async def query_device_state(device_id: str):
    """Query device with graceful degradation."""
    # Check circuit breaker
    if circuit_breaker.is_open():
        # Return cached state if available
        cached = cache_get(f"device:{device_id}")
        if cached:
            log_info(f"Using cached state (circuit breaker open)")
            cached['source'] = 'cache'
            cached['stale'] = True
            return cached
        
        # No cache, fail gracefully
        raise Exception("Home Assistant unavailable, no cached state")
    
    try:
        # Attempt to get fresh state
        state = await ha_websocket.send_command({
            'type': 'call_service',
            'domain': 'homeassistant',
            'service': 'get_state',
            'service_data': {'entity_id': device_id}
        })
        
        # Cache for future use
        cache_set(f"device:{device_id}", state, ttl=30)
        circuit_breaker.record_success()
        
        return state
        
    except Exception as e:
        circuit_breaker.record_failure()
        
        # Fall back to cache
        cached = cache_get(f"device:{device_id}")
        if cached:
            log_warning(f"HA request failed, using cache: {e}")
            cached['source'] = 'cache'
            cached['stale'] = True
            return cached
        
        raise
```

### 5. Connection Pooling (For Multiple Instances)

```python
class HAConnectionPool:
    def __init__(self, size: int = 3):
        self.size = size
        self.connections: List[HAWebSocket] = []
        self.lock = asyncio.Lock()
    
    async def get_connection(self) -> HAWebSocket:
        """Get available connection from pool."""
        async with self.lock:
            # Remove unhealthy connections
            self.connections = [
                conn for conn in self.connections
                if await conn.is_healthy()
            ]
            
            # Create new if needed
            if len(self.connections) < self.size:
                conn = HAWebSocket(HA_URL, HA_TOKEN)
                await conn.connect()
                self.connections.append(conn)
            
            # Return least recently used
            return self.connections.pop(0)
    
    async def return_connection(self, conn: HAWebSocket):
        """Return connection to pool."""
        async with self.lock:
            if await conn.is_healthy():
                self.connections.append(conn)
```

---

## PRINCIPLES LEARNED

### 1. Assume Connections Will Fail

**Don't assume stable connections:**
```python
# ❌ Wrong mindset
# "WebSocket should stay connected"
# → No reconnection logic

# ✅ Right mindset
# "WebSocket will fail eventually"
# → Automatic reconnection built-in
```

### 2. Health Checks Are Essential

**Proactive monitoring:**
```python
# ❌ Wrong: Detect failures reactively
# Wait for command to fail → then discover connection dead

# ✅ Right: Detect failures proactively
# Ping every 30 seconds → know connection status
# Reconnect before user notices
```

### 3. Exponential Backoff

**Don't hammer unavailable services:**
```python
# ❌ Wrong: Retry immediately
while True:
    try:
        connect()
        break
    except:
        continue  # Retry instantly

# ✅ Right: Exponential backoff
delay = 1
while True:
    try:
        connect()
        break
    except:
        await asyncio.sleep(delay)
        delay = min(delay * 2, 60)
```

### 4. Circuit Breaker Pattern

**Fail fast when service is down:**
```python
# ❌ Wrong: Keep trying when down
# → Every request waits, times out
# → Waste resources, slow responses

# ✅ Right: Circuit breaker
# → After N failures, fail immediately
# → Save resources, fast error responses
# → Periodically check if service recovered
```

### 5. Graceful Degradation

**Always have fallback:**
```python
# ❌ Wrong: Fail hard
if ha_unavailable:
    raise Exception("HA down")

# ✅ Right: Graceful degradation
if ha_unavailable:
    cached = get_cached_state()
    if cached:
        return cached  # Stale but better than nothing
    raise Exception("HA down, no cache")
```

---

## KEY TAKEAWAYS

**Lesson:**
- WebSocket connections fail unpredictably
- Implement automatic reconnection
- Use health checks (ping)
- Apply circuit breaker pattern
- Enable graceful degradation

**Implementation:**
- Exponential backoff for reconnection
- Ping every 30 seconds
- Circuit breaker after 5 failures
- Cache for graceful degradation

**Impact:**
- Error rate: 15-20% → <1% (95% improvement)
- User experience: Frustrating → Reliable
- Support tickets: Frequent → Zero

**Principles:**
- Assume connections will fail
- Detect failures proactively
- Fail fast when service unavailable
- Always have fallback plan

---

## RELATED

**Architecture:**
- LEE-Architecture-Overview.md (WebSocket integration)

**Decisions:**
- LEE-DEC-01: Home Assistant Choice
- LEE-DEC-02: WebSocket Protocol

**Lessons:**
- LEE-LESS-02: Token Refresh Strategy

**Generic Patterns:**
- Circuit Breaker pattern
- Retry with exponential backoff

---

**END OF FILE**

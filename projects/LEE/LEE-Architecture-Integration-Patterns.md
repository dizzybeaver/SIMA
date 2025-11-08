# LEE-Architecture-Integration-Patterns.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** LEE integration architecture and design patterns  
**Category:** Project - LEE Architecture

---

## Overview

LEE (Lambda Execution Engine) integrates multiple architectural patterns and AWS services to provide a home automation gateway. This document describes how these patterns work together.

---

## System Architecture

### High-Level Components

```
┌─────────────────────────────────────────────┐
│            User Interfaces                   │
│  (Alexa, Google Assistant, Web Dashboard)   │
└─────────────────┬───────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────┐
│         AWS Lambda (LEE)                     │
│  ┌────────────────────────────────────────┐ │
│  │  Gateway Layer (SUGA)                  │ │
│  │  ├─ Routing                            │ │
│  │  ├─ Authentication                     │ │
│  │  └─ Error Handling                     │ │
│  ├────────────────────────────────────────┤ │
│  │  Interface Layer                       │ │
│  │  ├─ Alexa Interface                    │ │
│  │  ├─ Google Assistant Interface         │ │
│  │  └─ Home Assistant Interface           │ │
│  ├────────────────────────────────────────┤ │
│  │  Core Layer                            │ │
│  │  ├─ Device Management                  │ │
│  │  ├─ State Tracking                     │ │
│  │  └─ Command Execution                  │ │
│  └────────────────────────────────────────┘ │
└─────────────────┬───────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────┐
│       Home Assistant (Local)                 │
│  ┌─────────────────────────────────────┐   │
│  │  WebSocket API                       │   │
│  │  REST API (fallback)                 │   │
│  └─────────────────────────────────────┘   │
└─────────────────────────────────────────────┘
```

---

## Architectural Patterns Applied

### 1. SUGA (Gateway Pattern)

**Application in LEE:**
```
Gateway Layer (gateway.py)
   ├─ Single entry point for all operations
   ├─ Lazy imports for performance
   └─ Cross-interface coordination

Interface Layer (interface_*.py)
   ├─ Alexa integration (ha_interface_alexa.py)
   ├─ Device management (ha_interface_devices.py)
   └─ Home Assistant communication (interface_websocket.py)

Core Layer (*_core.py)
   ├─ Device logic (ha_devices_core.py)
   ├─ WebSocket handling (websocket_core.py)
   └─ Configuration management (config_core.py)
```

**Benefits:**
- Clear separation of concerns
- No circular imports
- Easy to test each layer
- Maintainable structure

**REF:** SUGA architecture files

### 2. LMMS (Lazy Module Management)

**Application in LEE:**
```python
# fast_path.py - Always loaded
def route_request(event):
    """Quick routing without heavy imports"""
    request_type = event.get('type')
    
    if request_type == 'alexa':
        import ha_interface_alexa  # Lazy!
        return ha_interface_alexa.handle_alexa(event)
    
    elif request_type == 'devices':
        import ha_interface_devices  # Lazy!
        return ha_interface_devices.handle_devices(event)
```

**Benefits:**
- Cold start: 2.1s (vs 4.2s without LMMS)
- Warm start: 0.8s (vs 1.5s without LMMS)
- 50% reduction in initialization time

**REF:** LMMS architecture files

### 3. ZAPH (Hot Path Optimization)

**Tier Classification:**

**Tier 1 (Hot):**
- `fast_path.py`: Request routing
- `gateway.py`: Entry point
- `cache_core.py`: Caching operations

**Tier 2 (Warm):**
- `interface_websocket.py`: WebSocket connection
- `ha_interface_alexa.py`: Alexa integration
- `ha_interface_devices.py`: Device queries

**Tier 3 (Cold):**
- `ha_devices_helpers.py`: Device utilities
- `debug_diagnostics.py`: Diagnostics
- `performance_benchmark.py`: Profiling

**Benefits:**
- Optimized import order
- Fast path <200ms
- Warm path <800ms

**REF:** ZAPH architecture files

### 4. DD-1 (Dictionary Dispatch)

**Application in LEE:**
```python
# ha_interface_alexa.py
ALEXA_DISPATCH = {
    'TurnOn': handle_turn_on,
    'TurnOff': handle_turn_off,
    'SetBrightness': handle_brightness,
    'SetColor': handle_color,
    # ... 20+ Alexa directives
}

def handle_alexa(event):
    directive = event['directive']['header']['name']
    handler = ALEXA_DISPATCH.get(directive)
    
    if handler:
        return handler(event)
    
    raise ValueError(f"Unknown directive: {directive}")
```

**Benefits:**
- O(1) directive lookup
- Easy to extend (add to dict)
- Clear action registry
- Better than 20+ if-elif chains

**REF:** DD-1 architecture files

### 5. DD-2 (Dependency Disciplines)

**Layer Dependencies:**
```
Higher Layers ──┐
                ▼
Gateway Layer
    │
    ▼
Interface Layer
    │
    ▼
Core Layer  ◄── Only depends on built-ins + AWS SDK
```

**Rules Enforced:**
- ✅ Gateway imports interfaces (downward)
- ✅ Interfaces import cores (downward)
- ❌ Cores never import interfaces (would be upward)
- ❌ No circular imports anywhere

**Benefits:**
- No circular import errors
- Clear dependency flow
- Easy to test (test core first, then interfaces, then gateway)

**REF:** DD-2 architecture files

### 6. CR-1 (Cache Registry)

**Application in LEE:**
```python
# gateway.py - Consolidated exports
from gateway_wrappers_cache import cache_get, cache_set, cache_clear
from gateway_wrappers_logging import log_info, log_error, log_debug
from gateway_wrappers_websocket import websocket_connect, websocket_send
# ... 100+ functions

__all__ = [
    'cache_get', 'cache_set', 'cache_clear',
    'log_info', 'log_error', 'log_debug',
    'websocket_connect', 'websocket_send',
    # ... all exports
]
```

**Benefits:**
- Single import point: `import gateway`
- All functions accessible from one module
- Fast function discovery
- Clear API

**REF:** CR-1 architecture files

---

## Integration Flow Examples

### Example 1: Alexa Turn On Light

**User:** "Alexa, turn on the living room lights"

**Flow:**
```
1. Alexa → AWS Lambda (API Gateway)
   Event: {type: 'alexa', directive: 'TurnOn', ...}

2. Lambda Handler (lambda_function.py)
   ├─ Route to fast_path.route_request()

3. Fast Path (fast_path.py)
   ├─ Detect type='alexa'
   ├─ Lazy import ha_interface_alexa
   └─ Call ha_interface_alexa.handle_alexa(event)

4. Alexa Interface (ha_interface_alexa.py)
   ├─ DD-1: Dispatch to handle_turn_on()
   ├─ Validate request
   └─ Call ha_devices_core.execute_command()

5. Device Core (ha_devices_core.py)
   ├─ Get device from cache or HA
   ├─ Build WebSocket command
   └─ Call interface_websocket.send_command()

6. WebSocket Interface (interface_websocket.py)
   ├─ Establish connection (or reuse)
   ├─ Authenticate
   ├─ Send command
   ├─ Wait for response
   └─ Return result

7. Response Path (reverse)
   ├─ Core returns success
   ├─ Interface formats Alexa response
   ├─ Gateway returns to Lambda
   └─ Lambda returns to Alexa

8. Alexa → User
   "Okay" (lights turn on)
```

**Timing:**
- Warm start: 750-950ms total
- Cold start: 1800-2200ms total

### Example 2: Device Query

**User:** API request for device list

**Flow:**
```
1. API Gateway → Lambda
   Event: {type: 'devices', action: 'list'}

2. Fast Path Routing
   ├─ Detect type='devices'
   └─ Lazy import ha_interface_devices

3. Device Interface
   ├─ Check cache first (ZAPH Tier 1)
   ├─ Cache hit? Return cached devices
   └─ Cache miss? Fetch from HA

4. Device Core (if cache miss)
   ├─ WebSocket connect to HA
   ├─ Request device list
   ├─ Parse response
   ├─ Update cache
   └─ Return devices

5. Response
   ├─ Format as JSON
   └─ Return to client
```

**Timing:**
- Cache hit: 50-100ms
- Cache miss: 800-1200ms
- Cold start + cache miss: 2500-3000ms

---

## Error Handling Integration

### Layered Error Handling

**Layer 1: Gateway**
- Catch all unhandled exceptions
- Format error responses
- Log errors to CloudWatch
- Return user-friendly messages

**Layer 2: Interface**
- Handle layer-specific errors
- Implement fallback strategies
- Retry logic where appropriate

**Layer 3: Core**
- Raise specific exceptions
- No generic catch-all
- Let interfaces handle

**Example:**
```python
# Core raises specific error
def execute_command(device_id, command):
    if not device_exists(device_id):
        raise DeviceNotFoundError(f"Device {device_id} not found")

# Interface handles and retries
def handle_command(device_id, command):
    try:
        return execute_command(device_id, command)
    except DeviceNotFoundError:
        # Try to refresh device list and retry
        refresh_devices()
        return execute_command(device_id, command)

# Gateway catches any remaining errors
def handler(event, context):
    try:
        return process(event)
    except Exception as e:
        log_error(f"Unhandled error: {e}")
        return error_response(500, "Internal error")
```

---

## State Management

### Stateless with Caching

**Principle:** No persistent local state (DEC-04)

**Implementation:**
```python
# Container-level cache (optimization only)
DEVICE_CACHE = {}
WS_CONNECTION = None

def get_devices():
    # Try cache (may be empty on new container)
    if DEVICE_CACHE:
        return DEVICE_CACHE
    
    # Fetch from authoritative source
    devices = fetch_from_home_assistant()
    
    # Cache for next invocation (maybe in same container)
    DEVICE_CACHE.update(devices)
    
    return devices
```

**Key principles:**
- Cache miss is expected and handled
- Never depend on cache for correctness
- Authoritative source is always Home Assistant
- Cache improves performance, not functionality

---

## Performance Optimizations

### Combined Strategies

**1. LMMS (Lazy Loading)**
- Defer heavy imports
- Load only what's needed
- Fast path stays fast

**2. ZAPH (Hot Path)**
- Optimize frequent operations
- Keep hot path under 200ms
- Tier-based prioritization

**3. Caching**
- Device list cached
- Configuration cached
- WebSocket connections reused (per container)

**4. DD-1 (Fast Dispatch)**
- O(1) handler lookup
- No if-elif chains
- Direct function calls

**Result:**
- Cold start: 2.1s (acceptable)
- Warm start: 0.8s (excellent)
- P95 latency: 1.4s (acceptable)

---

## Monitoring and Observability

### CloudWatch Integration

**Metrics tracked:**
- Request count by type (Alexa, devices, etc.)
- Latency by operation
- Error rate by type
- Cache hit/miss ratio
- Cold start frequency

**Alarms configured:**
- Error rate >2%
- Latency P95 >2000ms
- Cold start ratio >10%
- WebSocket failures >5%

**Logs structured:**
```python
log_info("REQUEST_START", {
    "type": event_type,
    "source": event_source,
    "cold_start": is_cold_start
})
```

---

## Scaling Characteristics

### Horizontal Scaling

**Lambda handles:**
- 1 invocation → 1 container
- 100 simultaneous → 100 containers
- Auto-scales up and down
- No coordination needed (stateless)

**Limitations:**
- Home Assistant API may throttle
- Network bandwidth to HA
- Account-level Lambda limits

**Mitigation:**
- Request queuing if needed
- Rate limiting at gateway
- Graceful degradation on throttle

---

## Related Documentation

**Architecture Patterns:**
- SUGA: `/sima/languages/python/architectures/suga/`
- LMMS: `/sima/languages/python/architectures/lmms/`
- ZAPH: `/sima/languages/python/architectures/zaph/`
- DD-1: `/sima/languages/python/architectures/dd-1/`
- DD-2: `/sima/languages/python/architectures/dd-2/`
- CR-1: `/sima/languages/python/architectures/cr-1/`

**Platform:**
- AWS Lambda: `/sima/platforms/aws/lambda/`

**Project:**
- LEE Overview: `LEE-Architecture-Overview.md`
- LEE Decisions: `/sima/projects/LEE/decisions/`
- LEE Lessons: `/sima/projects/LEE/lessons/`

---

## Key Takeaways

**Multiple patterns work together:**
SUGA + LMMS + ZAPH + DD-1 + DD-2 + CR-1 = LEE architecture

**Stateless with caching:**
No persistent state, but optimize with caching

**Layered error handling:**
Each layer handles its own concerns

**Performance through optimization:**
Cold start <3s, warm start <1s

**Horizontal scaling:**
Stateless design enables infinite scaling

---

**Document ID:** LEE-ARCH-02  
**Keywords:** integration patterns, system architecture, SUGA, LMMS, ZAPH, DD-1, DD-2, CR-1, LEE architecture  
**Related Topics:** Pattern integration, system design, performance optimization, error handling, scaling

---

**END OF FILE**

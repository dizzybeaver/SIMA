# NM01-Architecture-InterfacesAdvanced_INT-09.md - INT-09

# INT-09: WEBSOCKET Interface

**Category:** NM01 - Architecture  
**Topic:** Interfaces-Advanced  
**Priority:** 🟢 MEDIUM  
**Status:** Active  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-24

---

## Summary

WebSocket connection management interface for real-time bidirectional communication, primarily used for Home Assistant integration.

---

## Context

The WEBSOCKET interface enables persistent WebSocket connections for real-time communication with external services like Home Assistant.

**Why it exists:** Home Assistant uses WebSocket for real-time state updates and control. This interface provides reliable WebSocket management.

---

## Content

### Overview

```
Router: interface_websocket.py
Core: websocket_core.py
Purpose: WebSocket connection management
Pattern: Dictionary-based dispatch
State: Active WebSocket connections
Dependency Layer: Layer 4 (Advanced Features)
```

### Operations (5 total)

```
├─ connect: Establish WebSocket connection
├─ send: Send message through WebSocket
├─ receive: Receive message from WebSocket
├─ close: Close WebSocket connection
└─ request: Send request and wait for response
```

### Gateway Wrappers

```python
websocket_connect(url: str, **kwargs) -> str  # Returns connection_id
websocket_send(connection_id: str, message: Dict) -> bool
websocket_receive(connection_id: str, timeout: int = 10) -> Dict
websocket_close(connection_id: str) -> bool
websocket_request(connection_id: str, message: Dict, timeout: int = 10) -> Dict
```

### Dependencies

```
Uses: LOGGING, METRICS, CIRCUIT_BREAKER
Used by: Home Assistant integration
```

### Usage Example

```python
from gateway import websocket_connect, websocket_request, websocket_close

# Connect to WebSocket
conn_id = websocket_connect('wss://homeassistant.local/api/websocket')

# Send request and wait for response
response = websocket_request(conn_id, {
    'type': 'get_states'
})

# Close when done
websocket_close(conn_id)
```

---

## Related Topics

- **Home Assistant Integration** - Primary use case
- **INT-10**: CIRCUIT_BREAKER - Protects WebSocket calls
- **INT-04**: METRICS - Tracks WebSocket performance
- **DEP-05**: Layer 4 (Advanced Features)

---

## Keywords

WebSocket, real-time, bidirectional, Home Assistant, persistent connection

---

## Version History

- **2025-10-24**: Atomized from monolithic file (SIMA v3 migration)
- **2025-10-20**: Original content in IMPLEMENTATION_Advanced.md

---

**File:** `NM01-Architecture-InterfacesAdvanced_INT-09.md`  
**End of Document**

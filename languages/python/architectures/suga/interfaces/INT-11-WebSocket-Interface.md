# INT-11-WebSocket-Interface.md

**Version:** 1.0.0  
**Date:** 2025-11-07  
**Purpose:** WebSocket operations through SUGA pattern  
**Architecture:** SUGA (Gateway Pattern)

---

## INT-11: WEBSOCKET INTERFACE

**Purpose:** WebSocket operations through SUGA pattern.

### Three-Layer Pattern

```python
# Gateway (gateway_wrappers_websocket.py)
def websocket_connect(url):
    import interface_websocket
    return interface_websocket.connect(url)

def websocket_send(connection, message):
    import interface_websocket
    return interface_websocket.send(connection, message)

# Interface (interface_websocket.py)
def connect(url):
    import websocket_core
    return websocket_core.connect_impl(url)

def send(connection, message):
    import websocket_core
    return websocket_core.send_impl(connection, message)

# Core (websocket_core.py)
def connect_impl(url):
    """WebSocket connection."""
    return {'url': url, 'connected': True}

def send_impl(connection, message):
    """Send WebSocket message."""
    return True
```

**Keywords:** WebSocket, real-time, connection, SUGA

---

## COMMON ANTI-PATTERNS (ALL INTERFACES)

### Anti-Pattern 1: Direct Core Import

```python
# WRONG - Any interface
from cache_core import get_impl
from logging_core import log_impl

# CORRECT
import gateway
gateway.cache_get(key)
gateway.log_info(message)
```

### Anti-Pattern 2: Skipping Interface Layer

```python
# WRONG
import cache_core
cache_core.get_impl(key)

# CORRECT
import gateway
gateway.cache_get(key)
```

---

## CROSS-REFERENCES (ALL INTERFACES)

**Architecture:**
- ARCH-01: Gateway Trinity
- ARCH-02: Layer Separation
- ARCH-03: Interface Pattern

**Gateways:**
- GATE-01: Gateway Entry Pattern
- GATE-02: Lazy Import Pattern
- GATE-03: Cross-Interface Communication

**Decisions:**
- DEC-01: SUGA Choice
- DEC-03: Gateway Mandatory

**Anti-Patterns:**
- AP-01: Direct Core Import
- AP-02: Module-Level Heavy Imports
- AP-04: Skipping Interface Layer

**Lessons:**
- LESS-03: Gateway Entry Point
- LESS-04: Layer Responsibility Clarity
- LESS-07: Base Layers No Dependencies

---

**END OF FILE**

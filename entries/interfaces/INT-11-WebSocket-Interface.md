# File: INT-11-WebSocket-Interface.md

**REF-ID:** INT-11  
**Version:** 1.0.0  
**Category:** Interface Pattern  
**Status:** Active  
**Priority:** 🟢 MEDIUM  
**Created:** 2025-11-01  
**Contributors:** SIMA Learning Mode

---

## 📋 OVERVIEW

**Interface Name:** WEBSOCKET  
**Short Code:** WS  
**Type:** Real-time Communication Interface  
**Dependency Layer:** Layer 3 (External Communication)

**One-Line Description:**  
WEBSOCKET interface manages API Gateway WebSocket connections for real-time bidirectional communication.

**Primary Purpose:**  
Enable real-time push notifications and bidirectional messaging between Lambda and connected clients.

---

## 🎯 CORE RESPONSIBILITIES

### 1. Connection Management
- Handle connect/disconnect events
- Track active connections
- Store connection metadata
- Clean up stale connections

### 2. Message Routing
- Route messages to correct handler
- Broadcast to multiple connections
- Send targeted messages
- Handle message acknowledgments

### 3. Connection State
- Store connection IDs
- Track connection metadata (user ID, session)
- Query active connections
- Connection lifecycle management

### 4. Error Handling
- Handle connection failures
- Retry failed sends
- Clean up on errors
- Log WebSocket events

---

## 🔑 KEY RULES

### Rule 1: Store Connection IDs in DynamoDB
**What:** Store connection IDs in DynamoDB table for persistence.

**Why:** Lambda is stateless. Need persistent storage to track active connections.

**Table Schema:**
```
Table: WebSocketConnections
Partition Key: connectionId (String)
Sort Key: userId (String) [Optional]
Attributes: 
  - connectedAt (Number - timestamp)
  - lastActivity (Number - timestamp)
  - metadata (Map - custom data)
TTL: expiresAt (Number - auto-cleanup)
```

**Example:**
```python
from gateway import ws_store_connection, ws_get_connection

# On connect
def handle_connect(event, context):
    connection_id = event['requestContext']['connectionId']
    user_id = event.get('queryStringParameters', {}).get('userId')
    
    ws_store_connection(
        connection_id=connection_id,
        user_id=user_id,
        metadata={"clientVersion": "1.0"}
    )
    
    return {"statusCode": 200}
```

---

### Rule 2: Clean Up on Disconnect
**What:** Delete connection record when client disconnects.

**Why:** Prevents sending to dead connections. Reduces storage costs.

**Example:**
```python
from gateway import ws_delete_connection

def handle_disconnect(event, context):
    connection_id = event['requestContext']['connectionId']
    
    # Clean up connection record
    ws_delete_connection(connection_id)
    
    return {"statusCode": 200}
```

---

### Rule 3: Handle Stale Connections
**What:** Implement TTL (Time To Live) to auto-delete old connections.

**Why:** Clients may disconnect without sending disconnect event.

**Strategy:**
- Set TTL on connection records (e.g., 2 hours)
- DynamoDB auto-deletes expired records
- Prevents accumulation of dead connections

**Example:**
```python
import time

def handle_connect(event, context):
    connection_id = event['requestContext']['connectionId']
    
    ws_store_connection(
        connection_id=connection_id,
        ttl=int(time.time()) + 7200  # 2 hours
    )
```

---

### Rule 4: Use PostToConnection API
**What:** Send messages using API Gateway Management API.

**Why:** Only way to send messages to WebSocket clients from Lambda.

**Example:**
```python
from gateway import ws_send_message

def send_notification(connection_id, message):
    try:
        ws_send_message(
            connection_id=connection_id,
            data={"type": "notification", "message": message}
        )
        return True
        
    except Exception as e:
        log_error(f"Failed to send message: {e}")
        # Connection likely stale, clean up
        ws_delete_connection(connection_id)
        return False
```

---

### Rule 5: Broadcast Efficiently
**What:** Batch message sends when broadcasting to multiple connections.

**Why:** Reduces API calls. Faster execution.

**Example:**
```python
from gateway import ws_broadcast_message, ws_get_all_connections

def broadcast_to_all(message):
    # Get all active connections
    connections = ws_get_all_connections()
    
    # Batch broadcast (automatic batching)
    results = ws_broadcast_message(
        connection_ids=[c['connectionId'] for c in connections],
        data=message
    )
    
    # Clean up failed connections
    for conn_id, success in results.items():
        if not success:
            ws_delete_connection(conn_id)
```

---

## 🎨 MAJOR BENEFITS

### Benefit 1: Real-Time Updates
- Push notifications to clients
- Live data updates
- Instant messaging
- Real-time collaboration

### Benefit 2: Reduced Polling
- No need for client polling
- Lower API Gateway costs
- Reduced Lambda invocations
- Better user experience

### Benefit 3: Bidirectional Communication
- Client â†' Server (requests)
- Server â†' Client (push notifications)
- Two-way data flow
- Efficient communication

### Benefit 4: Serverless Scale
- Automatic scaling
- No server management
- Pay per connection
- Built-in AWS infrastructure

---

## 📚 CORE FUNCTIONS

### Gateway Functions

```python
# Connection management
ws_store_connection(connection_id, user_id=None, metadata=None, ttl=None)
ws_get_connection(connection_id)
ws_delete_connection(connection_id)
ws_update_connection(connection_id, updates)

# Querying connections
ws_get_all_connections()
ws_get_connections_by_user(user_id)
ws_get_active_connections(since_timestamp=None)

# Message sending
ws_send_message(connection_id, data)
ws_send_json(connection_id, json_data)
ws_send_text(connection_id, text)

# Broadcasting
ws_broadcast_message(connection_ids, data)
ws_broadcast_to_all(data)
ws_broadcast_to_users(user_ids, data)

# Error handling
ws_is_connection_alive(connection_id)
ws_cleanup_stale_connections(max_age_seconds=7200)

# Management API
ws_get_management_client()
ws_disconnect_connection(connection_id)
```

---

## 🔄 USAGE PATTERNS

### Pattern 1: Handle Connect Event
```python
from gateway import ws_store_connection, log_info

def lambda_handler(event, context):
    route_key = event['requestContext']['routeKey']
    
    if route_key == '$connect':
        connection_id = event['requestContext']['connectionId']
        user_id = event.get('queryStringParameters', {}).get('userId')
        
        # Store connection
        ws_store_connection(
            connection_id=connection_id,
            user_id=user_id,
            metadata={
                "connectedFrom": event['requestContext'].get('sourceIp'),
                "userAgent": event.get('headers', {}).get('User-Agent')
            }
        )
        
        log_info(f"WebSocket connected: {connection_id}")
        return {"statusCode": 200}
```

### Pattern 2: Handle Message Event
```python
from gateway import ws_get_connection, ws_send_message, log_info
import json

def lambda_handler(event, context):
    route_key = event['requestContext']['routeKey']
    
    if route_key == '$default':  # Message received
        connection_id = event['requestContext']['connectionId']
        body = json.loads(event.get('body', '{}'))
        
        # Process message
        response = process_message(body)
        
        # Send response back
        ws_send_message(connection_id, response)
        
        return {"statusCode": 200}
```

### Pattern 3: Handle Disconnect Event
```python
from gateway import ws_delete_connection, log_info

def lambda_handler(event, context):
    route_key = event['requestContext']['routeKey']
    
    if route_key == '$disconnect':
        connection_id = event['requestContext']['connectionId']
        
        # Clean up connection
        ws_delete_connection(connection_id)
        
        log_info(f"WebSocket disconnected: {connection_id}")
        return {"statusCode": 200}
```

### Pattern 4: Broadcast Notification
```python
from gateway import ws_get_all_connections, ws_broadcast_message

def send_system_notification(message):
    # Get all active connections
    connections = ws_get_all_connections()
    
    if not connections:
        log_info("No active connections")
        return
    
    # Broadcast to all
    notification = {
        "type": "system_notification",
        "message": message,
        "timestamp": int(time.time())
    }
    
    results = ws_broadcast_message(
        connection_ids=[c['connectionId'] for c in connections],
        data=notification
    )
    
    log_info(f"Broadcast sent to {len(connections)} connections")
```

### Pattern 5: Send to Specific User
```python
from gateway import ws_get_connections_by_user, ws_send_message

def notify_user(user_id, notification):
    # Get all connections for this user (may have multiple devices)
    connections = ws_get_connections_by_user(user_id)
    
    if not connections:
        log_info(f"User {user_id} not connected")
        return False
    
    # Send to all user's connections
    for conn in connections:
        ws_send_message(conn['connectionId'], notification)
    
    return True
```

---

## ⚠️ ANTI-PATTERNS

### Anti-Pattern 1: Not Cleaning Up Connections ❌
```python
# ❌ DON'T: Leave connection records forever
def handle_disconnect(event, context):
    connection_id = event['requestContext']['connectionId']
    log_info(f"Disconnected: {connection_id}")
    # MISSING: ws_delete_connection(connection_id)
    return {"statusCode": 200}

# ✅ DO: Always clean up
def handle_disconnect(event, context):
    connection_id = event['requestContext']['connectionId']
    ws_delete_connection(connection_id)
    return {"statusCode": 200}
```

### Anti-Pattern 2: No Error Handling on Send ❌
```python
# ❌ DON'T: Assume send always succeeds
ws_send_message(connection_id, data)
# May fail if connection is dead

# ✅ DO: Handle errors
try:
    ws_send_message(connection_id, data)
except Exception as e:
    log_error(f"Send failed: {e}")
    ws_delete_connection(connection_id)  # Clean up dead connection
```

### Anti-Pattern 3: Sequential Broadcasting ❌
```python
# ❌ DON'T: Send one at a time (slow)
for connection in connections:
    ws_send_message(connection['connectionId'], data)
# 1000 connections = 1000 sequential API calls

# ✅ DO: Batch broadcast
ws_broadcast_message(
    connection_ids=[c['connectionId'] for c in connections],
    data=data
)
# Automatic batching and parallelization
```

### Anti-Pattern 4: No TTL on Connections ❌
```python
# ❌ DON'T: Store connections forever
ws_store_connection(connection_id, user_id)
# Stale connections accumulate forever

# ✅ DO: Set TTL for auto-cleanup
import time
ws_store_connection(
    connection_id,
    user_id,
    ttl=int(time.time()) + 7200  # 2 hours
)
```

---

## 🔗 CROSS-REFERENCES

**Related Architecture:**
- ARCH-01 (SUGA): WebSocket is Layer 3
- ARCH-03 (DD): Route WebSocket events

**Related Interfaces:**
- INT-04 (HTTP): HTTP vs WebSocket comparison
- INT-02 (Logging): Log WebSocket events
- INT-07 (Metrics): Track connection metrics

**Related Patterns:**
- GATE-02 (Lazy Loading): Load WS client lazily
- GATE-03 (Cross-Interface): WS + DynamoDB + Logging

**Related Lessons:**
- LESS-20 (WebSocket): Connection management
- LESS-30 (Real-time): Push notification patterns
- LESS-37 (Cleanup): TTL for stale connections

**Related Decisions:**
- DEC-21 (WebSocket Strategy): DynamoDB for state
- DEC-22 (TTL): 2 hour default TTL

---

## ✅ VERIFICATION CHECKLIST

Before deploying WebSocket code:
- [ ] Connection storage implemented (DynamoDB)
- [ ] Connect handler cleans up on disconnect
- [ ] TTL set for auto-cleanup
- [ ] Error handling on message send
- [ ] Stale connection cleanup
- [ ] Broadcast uses batching
- [ ] Connection metadata stored
- [ ] Logging integrated
- [ ] Metrics tracked
- [ ] API Gateway permissions configured

---

## 📊 WEBSOCKET EVENTS

### Route Keys
```
$connect    - Client connects
$disconnect - Client disconnects
$default    - Message received
[custom]    - Custom route handlers
```

### Connection Lifecycle
```
1. Client connects â†' $connect event
2. Store connection in DynamoDB
3. Client sends messages â†' $default event
4. Server sends messages â†' PostToConnection API
5. Client disconnects â†' $disconnect event
6. Delete connection from DynamoDB
```

---

## ðŸ'¡ COMMON USE CASES

### Real-Time Chat
```python
# Broadcast message to all users in room
connections = ws_get_connections_by_room(room_id)
ws_broadcast_message(connections, chat_message)
```

### Live Updates
```python
# Push data update to subscribed users
subscribers = ws_get_connections_by_subscription(data_id)
ws_broadcast_message(subscribers, data_update)
```

### Notifications
```python
# Send notification to specific user
user_connections = ws_get_connections_by_user(user_id)
ws_broadcast_message(user_connections, notification)
```

---

**END OF INTERFACE ENTRY**

**REF-ID:** INT-11  
**Status:** Active  
**Lines:** 390
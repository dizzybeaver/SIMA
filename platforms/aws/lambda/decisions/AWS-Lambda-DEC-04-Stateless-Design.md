# AWS-Lambda-DEC-04-Stateless-Design.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Decision to design Lambda functions as stateless operations  
**Category:** Platform - AWS Lambda Decision

---

## Decision

**DEC-04: Design All Lambda Functions as Stateless Operations**

**Status:** Accepted  
**Date:** 2025-11-08  
**Context:** Lambda execution model requires stateless design for reliability and scalability

---

## Context

AWS Lambda operates on an ephemeral execution model:
- Containers created and destroyed dynamically
- No guarantee of container reuse
- No guarantee of container affinity
- State persists unpredictably between invocations

**Problem:** Stateful designs break under Lambda's execution model

---

## Decision

**All Lambda functions must:**
1. Not rely on persistent local state
2. Treat each invocation independently
3. Store state externally (DynamoDB, S3, etc.)
4. Assume container can be destroyed at any time
5. Handle partial state gracefully

**Exception:** Caches for performance optimization (with awareness of limitations)

---

## Rationale

### Why Stateless?

**Lambda characteristics that require stateless design:**

#### 1. Container Recycling

Containers are recycled unpredictably:
```
Invocation 1: Container A (state initialized)
Invocation 2: Container A (state persists) ✓
Invocation 3: Container B (state lost!) ✗
Invocation 4: Container A (state from inv 2) ??
```

**Impact:** Cannot rely on state persistence

#### 2. Concurrent Invocations

Multiple invocations run simultaneously:
```
Time 0: Inv 1 (Container A) sets state = X
Time 1: Inv 2 (Container B) reads state = ? (undefined!)
Time 2: Inv 1 updates state = Y
Time 3: Inv 2 expects Y, gets ? or nothing
```

**Impact:** State not shared across invocations

#### 3. Container Lifecycle

Containers terminated without notice:
```
During invocation: state updated
Container killed: state lost
Next invocation: expects state, finds nothing
```

**Impact:** State can disappear mid-operation

#### 4. Scaling Behavior

Rapid scaling creates/destroys containers:
```
Low traffic: 1 container, state in memory
Traffic spike: 100 containers, 99 have no state
Traffic drops: Containers destroyed, state lost
```

**Impact:** State does not scale with traffic

---

## Implementation Guidelines

### Pattern 1: External State Storage

**Store all persistent state externally:**

```python
# [X] WRONG: Local state
user_data = {}  # Lost when container dies

def handler(event, context):
    user_id = event['user_id']
    user_data[user_id] = event['data']  # Unreliable
    return {'success': True}

# [OK] CORRECT: External state (DynamoDB)
import boto3
dynamodb = boto3.resource('dynamodb')
table = dynamodb.Table('UserData')

def handler(event, context):
    user_id = event['user_id']
    table.put_item(Item={
        'user_id': user_id,
        'data': event['data']
    })
    return {'success': True}
```

### Pattern 2: Treat Each Invocation Independently

**No assumptions about previous invocations:**

```python
# [X] WRONG: Assumes initialization
connection = None  # May be None OR set from previous invocation

def handler(event, context):
    # This breaks if connection was set but is now invalid
    if connection is None:
        connection = create_connection()
    return connection.query()

# [OK] CORRECT: Verify state every time
def get_connection():
    """Get valid connection, creating if needed"""
    global connection
    if connection is None or not connection.is_valid():
        connection = create_connection()
    return connection

def handler(event, context):
    conn = get_connection()  # Always check validity
    return conn.query()
```

### Pattern 3: Accept Temporary State (Caching)

**Use caching for performance, but handle misses:**

```python
# Cache for performance, but don't depend on it
CACHE = {}

def handler(event, context):
    key = event['key']
    
    # Try cache first
    if key in CACHE:
        return CACHE[key]
    
    # Cache miss: fetch from authoritative source
    value = fetch_from_dynamodb(key)
    
    # Cache for next time (maybe in this container)
    CACHE[key] = value
    
    return value
```

**Key principle:** Cache is optimization, not requirement

### Pattern 4: Idempotent Operations

**Operations can be repeated safely:**

```python
# [X] WRONG: Not idempotent
def handler(event, context):
    counter = get_counter()
    counter += 1  # Repeated calls = incorrect count
    save_counter(counter)

# [OK] CORRECT: Idempotent with external coordination
def handler(event, context):
    request_id = event['requestId']
    
    # Check if already processed
    if already_processed(request_id):
        return get_previous_result(request_id)
    
    # Process and record
    result = process(event)
    record_processing(request_id, result)
    return result
```

---

## LEE Project Implementation

### Stateless Design

**No persistent state in Lambda:**
- Home Assistant connection: Created per invocation
- Device cache: Warmed from HA API each cold start
- Configuration: Loaded from SSM Parameter Store
- Tokens: Retrieved from SSM, not stored locally

### Allowed Temporary State

**Performance caches (container-level):**
```python
# Caches for performance only
DEVICE_CACHE = {}  # HA devices (fetched if empty)
HA_CONNECTION = None  # WebSocket (reconnect if invalid)
CONFIG_CACHE = None  # SSM config (refetch if None)
```

**Invalidation strategy:**
- Age-based: Cache entries have TTL
- Validation: Check before use (is_valid())
- Size-based: LRU eviction for memory control
- Accept: Cache may be empty on any invocation

### External State

**Authoritative sources:**
- Home Assistant: Device state and availability
- SSM Parameter Store: Configuration and tokens
- CloudWatch: Logs and metrics

**Never stored locally:**
- User preferences
- Device state
- Historical data
- Session information

---

## Anti-Patterns to Avoid

### Anti-Pattern 1: File System State

```python
# [X] WRONG: Local file state
with open('/tmp/state.json', 'w') as f:
    json.dump(state, f)  # Lost when container dies
```

**Why wrong:** /tmp is ephemeral, wiped on container recycle

**Correct:** Use S3 or DynamoDB for persistence

### Anti-Pattern 2: Assuming Container Reuse

```python
# [X] WRONG: One-time initialization assumption
initialized = False

def handler(event, context):
    global initialized
    if not initialized:
        heavy_setup()
        initialized = True  # May be False on next invocation!
```

**Why wrong:** Next invocation may use different container

**Correct:** Check validity every time, cache result

### Anti-Pattern 3: Global Counter

```python
# [X] WRONG: Global counter
request_count = 0

def handler(event, context):
    global request_count
    request_count += 1  # Wrong count across containers
```

**Why wrong:** Each container has separate counter

**Correct:** Use DynamoDB atomic counters or CloudWatch metrics

### Anti-Pattern 4: Session Tracking

```python
# [X] WRONG: Session state
sessions = {}

def handler(event, context):
    session_id = event['session_id']
    sessions[session_id] = event['data']  # Lost unpredictably
```

**Why wrong:** Sessions span invocations and containers

**Correct:** Use ElastiCache, DynamoDB, or session tokens

---

## Benefits of Stateless Design

### 1. Horizontal Scalability

Functions scale infinitely without coordination:
- 1 invocation → 1 container
- 1000 invocations → 1000 containers
- No state sharing required

### 2. Reliability

No state corruption or loss issues:
- Container dies → No data lost
- No cleanup required
- Predictable behavior

### 3. Simplicity

Easier to reason about and test:
- Each invocation independent
- No complex state machines
- Reproducible behavior

### 4. Cost Efficiency

Pay only for execution time:
- No background processes
- No persistent connections
- No idle resources

---

## When State is Needed

### Use External Services

**Options:**
1. **DynamoDB:** Fast key-value storage
2. **S3:** Large object storage
3. **ElastiCache:** High-speed caching
4. **RDS:** Relational data
5. **Step Functions:** Workflow state

**LEE example:**
- Configuration → SSM Parameter Store
- Device state → Home Assistant API
- Logs → CloudWatch
- No local state storage

---

## Monitoring Stateless Functions

### Key Metrics

**Health indicators:**
- Cold start frequency (cache misses expected)
- External service latency
- Idempotency violations (duplicates)
- State-related errors

**Alerts:**
- External service failures (state unavailable)
- High latency (state fetching slow)
- Duplicate processing (idempotency broken)

---

## Related Decisions

**Prerequisites:**
- DEC-01: Single-Threaded Execution
- DEC-02: Memory Constraints
- DEC-03: Timeout Limits

**Enables:**
- Horizontal scaling
- Predictable behavior
- Cost optimization
- Reliability

**Related Patterns:**
- Cache warming (performance)
- Idempotent operations (reliability)
- External state storage (persistence)

---

## References

**Core Concepts:**
- AWS-Lambda-Execution-Model.md
- AWS-Lambda-Runtime-Environment.md

**Lessons:**
- AWS-Lambda-LESS-02-Stateless-Benefits.md
- LESS-05-Graceful-Degradation-Required.md

**Anti-Patterns:**
- AWS-Lambda-AP-02-Stateful-Operations.md
- AP-10-Global-State-Assumptions.md

**Project:**
- LEE-DEC-05-State-Management.md
- LEE-LESS-02-Cache-Strategy.md

---

## Key Takeaways

**Stateless = Scalable:**
No state coordination enables infinite scaling

**Container reuse is optimization:**
Don't depend on it, benefit when it happens

**External state for persistence:**
DynamoDB, S3, etc. are authoritative sources

**Caching for performance:**
Acceptable if cache misses handled gracefully

**Idempotency prevents issues:**
Repeated invocations should be safe

---

**Decision ID:** DEC-04  
**Keywords:** stateless design, container lifecycle, scaling, external state, caching strategy  
**Related Topics:** Execution model, scalability, reliability, state management, caching

---

**END OF FILE**

# AWS-Lambda-AP-02-Stateful-Operations.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Anti-pattern documentation for stateful operations in Lambda  
**Category:** Platform - AWS Lambda Anti-Pattern

---

## Anti-Pattern

**AP-02: Storing Persistent State in Lambda Function Memory**

**Severity:** High  
**Category:** Architecture

---

## Description

Relying on local variables, files, or memory to persist state across Lambda invocations.

**Common manifestations:**
```python
# [X] WRONG: Global state
user_sessions = {}  # Persists unpredictably
request_counter = 0  # Wrong count across containers

def handler(event, context):
    user_sessions[event['user_id']] = event['data']
    request_counter += 1
    return request_counter
```

---

## Why This is Wrong

### 1. Container Lifecycle is Unpredictable

```
Invocation 1: Container A created, state initialized
Invocation 2: Container A reused, state persists ✓
Invocation 3: Container A recycled, state lost ✗
Invocation 4: Container B created, no state ✗
```

**Result:** State exists sometimes, not always

### 2. No Guarantees on Container Reuse

**Factors affecting reuse:**
- Traffic patterns
- Memory pressure
- AWS internal operations
- Deployment updates
- Account limits

**Reality:** Assume state is lost after each invocation

### 3. Concurrent Invocations Don't Share State

```
Time 0: Inv 1 (Container A) → state = X
Time 1: Inv 2 (Container B) → state = undefined
Time 2: Inv 3 (Container A) → state = X (maybe)
Time 3: Inv 4 (Container C) → state = undefined
```

**Result:** Inconsistent behavior across requests

### 4. State Lost on Container Termination

**Termination triggers:**
- Idle timeout (typically 15-30 minutes)
- Memory pressure
- Deployment updates
- Account scaling limits
- AWS infrastructure events

**Result:** Unpredictable data loss

---

## Real-World Impact

### Example 1: Session Management

```python
# [X] WRONG: Session tracking in memory
ACTIVE_SESSIONS = {}

def login(user_id):
    session_id = generate_session()
    ACTIVE_SESSIONS[session_id] = user_id
    return session_id

def validate_session(session_id):
    # May return None even for valid sessions!
    return ACTIVE_SESSIONS.get(session_id)
```

**Impact:**
- Random "session expired" errors
- Users logged out unexpectedly
- Inconsistent authentication state
- Poor user experience

**Correct approach:**
```python
# [OK] CORRECT: External session store (DynamoDB)
import boto3
dynamodb = boto3.resource('dynamodb')
sessions_table = dynamodb.Table('Sessions')

def login(user_id):
    session_id = generate_session()
    sessions_table.put_item(Item={
        'session_id': session_id,
        'user_id': user_id,
        'expires': int(time.time()) + 3600
    })
    return session_id

def validate_session(session_id):
    response = sessions_table.get_item(Key={'session_id': session_id})
    return response.get('Item', {}).get('user_id')
```

### Example 2: Request Counter

```python
# [X] WRONG: Counter in memory
request_count = 0

def handler(event, context):
    global request_count
    request_count += 1
    
    if request_count % 1000 == 0:
        send_alert(f"Processed {request_count} requests")
    
    return process(event)
```

**Impact:**
- Wrong count (each container has separate counter)
- Alerts never trigger (counters never reach 1000)
- Metrics meaningless
- No visibility into actual traffic

**Correct approach:**
```python
# [OK] CORRECT: CloudWatch metrics
import boto3
cloudwatch = boto3.client('cloudwatch')

def handler(event, context):
    # CloudWatch aggregates across all invocations
    cloudwatch.put_metric_data(
        Namespace='MyApp',
        MetricData=[{
            'MetricName': 'RequestCount',
            'Value': 1,
            'Unit': 'Count'
        }]
    )
    
    return process(event)
```

### Example 3: Local File Storage

```python
# [X] WRONG: Storing data in /tmp
def handler(event, context):
    data = process(event)
    
    # Save for "later" retrieval
    with open('/tmp/results.json', 'w') as f:
        json.dump(data, f)
    
    return {'saved': True}

def retrieve_handler(event, context):
    # May fail - file may not exist!
    with open('/tmp/results.json', 'r') as f:
        return json.load(f)
```

**Impact:**
- File not found errors
- Data loss
- Inconsistent behavior
- Difficult debugging

**Correct approach:**
```python
# [OK] CORRECT: Use S3 for persistent storage
import boto3
s3 = boto3.client('s3')

def handler(event, context):
    data = process(event)
    
    s3.put_object(
        Bucket='my-results-bucket',
        Key=f"results/{event['id']}.json",
        Body=json.dumps(data)
    )
    
    return {'saved': True}

def retrieve_handler(event, context):
    response = s3.get_object(
        Bucket='my-results-bucket',
        Key=f"results/{event['id']}.json"
    )
    return json.loads(response['Body'].read())
```

---

## Acceptable Use of Memory

### Caching for Performance

**Pattern: Optimization, not requirement**

```python
# [OK] CORRECT: Cache with fallback
CACHE = {}

def handler(event, context):
    key = event['key']
    
    # Try cache (optimization)
    if key in CACHE:
        return CACHE[key]
    
    # Cache miss: fetch from source (requirement)
    value = fetch_from_dynamodb(key)
    
    # Cache for next invocation (maybe in same container)
    CACHE[key] = value
    
    return value
```

**Key principles:**
- Cache miss is expected and handled
- Authoritative source is external (DynamoDB)
- Cache is optimization only
- Function works without cache

### Connection Pooling

**Pattern: Reuse expensive connections**

```python
# [OK] CORRECT: Connection reuse with validation
http_client = None

def get_http_client():
    global http_client
    
    # Reuse if exists and valid
    if http_client and http_client.is_valid():
        return http_client
    
    # Create new if needed
    http_client = create_http_client()
    return http_client

def handler(event, context):
    client = get_http_client()  # May reuse, may create new
    return client.request(event['url'])
```

**Key principles:**
- Validate before reuse
- Recreate if invalid
- Don't assume connection exists
- Graceful handling of connection failures

---

## How to Fix

### Step 1: Identify State Dependencies

**Audit code for:**
- Global variables
- File operations in /tmp
- Local caches required for correctness
- Assumptions about previous invocations

### Step 2: Choose External Storage

**Options:**

| Use Case | Service | Why |
|----------|---------|-----|
| Key-value data | DynamoDB | Fast, scalable, consistent |
| Large objects | S3 | Cost-effective, unlimited size |
| High-speed cache | ElastiCache | Microsecond latency |
| Relational data | RDS | Complex queries, transactions |
| Session data | ElastiCache + DynamoDB | Fast access, persistence |

### Step 3: Implement External State

**Pattern:**
1. Read state from external source
2. Process request
3. Write updated state to external source
4. Return response

**Never:**
- Assume state persists locally
- Require specific container for correctness
- Store state without external backup

### Step 4: Test Statelessness

**Verification tests:**
```python
def test_statelessness():
    # Test 1: Different containers
    response1 = invoke_lambda(event)
    response2 = invoke_lambda(event)  # May be different container
    assert response1 == response2  # Should be same result
    
    # Test 2: After idle period
    response1 = invoke_lambda(event)
    time.sleep(60)  # Wait for container to die
    response2 = invoke_lambda(event)
    assert response1 == response2
    
    # Test 3: Concurrent invocations
    responses = parallel_invoke(event, count=10)
    assert all(r == responses[0] for r in responses)
```

---

## Related Anti-Patterns

**Related:**
- AP-10: Global State Assumptions
- AP-20: File System Persistence
- AP-21: In-Memory Session Management

**Depends On:**
- DEC-04: Stateless Design Required

**Leads To:**
- Unreliable systems
- Data loss
- Inconsistent behavior
- Difficult debugging

---

## References

**Decisions:**
- AWS-Lambda-DEC-04-Stateless-Design.md

**Lessons:**
- AWS-Lambda-LESS-04-Stateless-Benefits.md
- LESS-05-Graceful-Degradation-Required.md

**Architecture:**
- AWS-Lambda-Execution-Model.md
- AWS-Lambda-Runtime-Environment.md

---

## Key Takeaways

**Never rely on local state:**
Containers are ephemeral, state doesn't persist

**Use external storage:**
DynamoDB, S3, ElastiCache for persistence

**Caching is optimization:**
Acceptable if cache misses handled gracefully

**Test across containers:**
Verify behavior consistent regardless of container

**Design for ephemerality:**
Assume each invocation is on fresh container

---

**Anti-Pattern ID:** AP-02  
**Severity:** High  
**Keywords:** state management, stateless design, container lifecycle, external storage, Lambda architecture  
**Related Topics:** Stateless design, external state, caching, connection pooling, Lambda limitations

---

**END OF FILE**

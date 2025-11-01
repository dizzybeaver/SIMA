# File: INT-09-Singleton-Interface.md

**REF-ID:** INT-09  
**Version:** 1.0.0  
**Category:** Interface Pattern  
**Status:** Active  
**Priority:** 🟡 HIGH  
**Created:** 2025-11-01  
**Contributors:** SIMA Learning Mode

---

## 📋 OVERVIEW

**Interface Name:** SINGLETON  
**Short Code:** SING  
**Type:** Resource Management Interface  
**Dependency Layer:** Layer 1 (Core Services)

**One-Line Description:**  
SINGLETON interface manages shared instances of expensive resources (clients, connections, caches).

**Primary Purpose:**  
Ensure expensive resources are created once per Lambda container and reused across invocations.

---

## 🎯 CORE RESPONSIBILITIES

### 1. Instance Management
- Create singleton instances
- Store in container memory
- Reuse across invocations
- Lazy initialization

### 2. Resource Types
- AWS SDK clients (boto3)
- HTTP sessions
- Database connections
- Cache instances
- External API clients

### 3. Lifecycle Management
- Initialize on first use
- Persist across warm invocations
- Clean up on container shutdown
- Handle refresh/reset

### 4. Thread Safety
- Not needed in Lambda (single-threaded)
- But pattern supports multi-threaded if needed
- Lock-free singleton pattern

---

## 🔑 KEY RULES

### Rule 1: Create Once, Reuse Many
**What:** Expensive resources MUST be created once per container, not per invocation.

**Why:** Creating clients/connections is expensive (50-500ms). Lambda containers serve multiple requests.

**Impact:**
```
Without singleton:
- Request 1: Create boto3 client (200ms) + use (50ms) = 250ms
- Request 2: Create boto3 client (200ms) + use (50ms) = 250ms
- Request 3: Create boto3 client (200ms) + use (50ms) = 250ms
Total: 750ms

With singleton:
- Request 1: Create boto3 client (200ms) + use (50ms) = 250ms
- Request 2: Reuse client (0ms) + use (50ms) = 50ms
- Request 3: Reuse client (0ms) + use (50ms) = 50ms
Total: 350ms (54% faster)
```

**Example:**
```python
# ❌ DON'T: Create every time (slow)
def lambda_handler(event, context):
    dynamodb = boto3.resource('dynamodb')  # 200ms creation!
    table = dynamodb.Table('Users')
    return table.get_item(Key={'id': '123'})

# ✅ DO: Create once, reuse (fast)
from gateway import get_singleton

def lambda_handler(event, context):
    dynamodb = get_singleton('dynamodb')  # 0ms (reuses existing)
    table = dynamodb.Table('Users')
    return table.get_item(Key={'id': '123'})
```

---

### Rule 2: Lazy Initialization
**What:** Create singletons on first use, not at module load.

**Why:** Don't pay initialization cost if resource not needed. Faster cold start.

**Example:**
```python
# ❌ DON'T: Eager initialization (slow cold start)
import boto3
DYNAMODB = boto3.resource('dynamodb')  # Created at import (cold start)

def lambda_handler(event, context):
    # May not even use DYNAMODB this request!
    pass

# ✅ DO: Lazy initialization (fast cold start)
_dynamodb = None

def get_dynamodb():
    global _dynamodb
    if _dynamodb is None:
        import boto3
        _dynamodb = boto3.resource('dynamodb')  # Created on first use
    return _dynamodb

def lambda_handler(event, context):
    if needs_database:
        db = get_dynamodb()  # Only created if needed
```

---

### Rule 3: Store at Module Level
**What:** Singleton instances MUST be stored as module-level variables.

**Why:** Module-level variables persist across Lambda invocations in same container.

**Example:**
```python
# Module level (persists across invocations)
_http_session = None
_dynamodb_client = None

def get_http_session():
    global _http_session
    if _http_session is None:
        import requests
        _http_session = requests.Session()
    return _http_session

def lambda_handler(event, context):
    session = get_http_session()  # Reused across requests
```

---

### Rule 4: No Threading Locks in Lambda
**What:** Lambda is single-threaded. Don't use locks for singleton creation.

**Why:** Locks are unnecessary overhead. Lambda never runs concurrent requests.

**Example:**
```python
# ❌ DON'T: Use locks in Lambda (unnecessary)
import threading
_lock = threading.Lock()
_instance = None

def get_instance():
    with _lock:  # UNNECESSARY in Lambda!
        global _instance
        if _instance is None:
            _instance = create_expensive_resource()
    return _instance

# ✅ DO: Simple singleton (Lambda is single-threaded)
_instance = None

def get_instance():
    global _instance
    if _instance is None:
        _instance = create_expensive_resource()
    return _instance
```

---

### Rule 5: Support Reset for Testing
**What:** Provide way to reset singletons for tests.

**Why:** Tests need clean state. Singletons can leak state between tests.

**Example:**
```python
_dynamodb = None

def get_dynamodb():
    global _dynamodb
    if _dynamodb is None:
        import boto3
        _dynamodb = boto3.resource('dynamodb')
    return _dynamodb

def reset_dynamodb():
    """Reset singleton (for testing only)"""
    global _dynamodb
    _dynamodb = None

# In tests:
def test_something():
    reset_dynamodb()  # Clean state
    # Test code
```

---

## 🎨 MAJOR BENEFITS

### Benefit 1: Performance
- 50-90% faster warm requests
- Eliminate repeated initialization
- Lower Lambda execution time
- Lower cost

### Benefit 2: Resource Efficiency
- Fewer connections created
- Lower memory usage
- Connection pooling benefits
- Better throughput

### Benefit 3: Consistency
- Same client configuration
- Shared connection pools
- Predictable behavior
- Easier debugging

### Benefit 4: Simplicity
- Centralized creation logic
- Hide complexity
- Easy to test (reset function)
- Clear pattern

---

## 📚 CORE FUNCTIONS

### Gateway Functions

```python
# Generic singleton
get_singleton(name, factory=None, **kwargs)
reset_singleton(name)
reset_all_singletons()
has_singleton(name)

# Common AWS clients
get_dynamodb_client()
get_dynamodb_resource()
get_s3_client()
get_s3_resource()
get_sqs_client()
get_sns_client()
get_lambda_client()

# HTTP clients
get_http_session(base_url=None)
get_requests_session()

# Cache instances
get_cache_instance()
get_local_cache()

# Custom factories
register_singleton_factory(name, factory)
create_if_missing(name, factory, **kwargs)

# Utilities
get_singleton_stats()
list_active_singletons()
```

---

## 🔄 USAGE PATTERNS

### Pattern 1: Standard Singleton Access
```python
from gateway import get_singleton

def lambda_handler(event, context):
    # Get DynamoDB (created once per container)
    dynamodb = get_singleton('dynamodb')
    
    # Use the resource
    table = dynamodb.Table('Users')
    response = table.get_item(Key={'id': event['userId']})
    
    return response['Item']
```

### Pattern 2: Custom Singleton Factory
```python
from gateway import get_singleton, register_singleton_factory

def create_api_client():
    """Factory function for API client"""
    import requests
    session = requests.Session()
    session.headers.update({
        'Authorization': f'Bearer {get_config("API_KEY")}',
        'User-Agent': 'MyApp/1.0'
    })
    return session

# Register factory (at module level)
register_singleton_factory('api_client', create_api_client)

def lambda_handler(event, context):
    # Get singleton (uses factory)
    api = get_singleton('api_client')
    response = api.get('https://api.example.com/data')
    return response.json()
```

### Pattern 3: Lazy Singleton Pattern
```python
# Module level
_expensive_resource = None

def get_expensive_resource():
    """Lazy singleton pattern"""
    global _expensive_resource
    
    if _expensive_resource is None:
        # Expensive initialization (only once)
        _expensive_resource = initialize_resource()
    
    return _expensive_resource

def lambda_handler(event, context):
    resource = get_expensive_resource()  # Fast (cached)
    return resource.do_work(event)
```

### Pattern 4: Multiple Named Singletons
```python
from gateway import get_singleton

def lambda_handler(event, context):
    # Multiple singletons
    dynamodb = get_singleton('dynamodb')
    s3 = get_singleton('s3')
    http = get_singleton('http_session')
    
    # Each created once, reused
    data = dynamodb.Table('Data').get_item(...)
    file = s3.Object('bucket', 'key').get()
    api_response = http.get('https://api.example.com')
```

### Pattern 5: Singleton with Reset (Testing)
```python
from gateway import get_singleton, reset_singleton

# Production code
def get_database():
    return get_singleton('database')

# Test code
def test_database_operations():
    # Clean state for test
    reset_singleton('database')
    
    db = get_database()
    # Test operations
    assert db.is_connected()
    
    # Cleanup
    reset_singleton('database')
```

---

## ⚠️ ANTI-PATTERNS

### Anti-Pattern 1: Creating Every Request ❌
```python
# ❌ DON'T: Create client every request
def lambda_handler(event, context):
    dynamodb = boto3.resource('dynamodb')  # 200ms every time!
    table = dynamodb.Table('Users')
    return table.get_item(...)

# ✅ DO: Create once, reuse
from gateway import get_singleton

def lambda_handler(event, context):
    dynamodb = get_singleton('dynamodb')  # 0ms (reused)
    table = dynamodb.Table('Users')
    return table.get_item(...)
```

### Anti-Pattern 2: Eager Initialization ❌
```python
# ❌ DON'T: Create at import (slow cold start)
import boto3
DYNAMODB = boto3.resource('dynamodb')
S3 = boto3.client('s3')
SQS = boto3.client('sqs')
# Cold start: 600ms just for clients!

# ✅ DO: Lazy initialization
from gateway import get_singleton

def lambda_handler(event, context):
    # Only create what's needed
    if event['type'] == 'database':
        db = get_singleton('dynamodb')
    elif event['type'] == 'storage':
        s3 = get_singleton('s3')
```

### Anti-Pattern 3: Using Locks ❌
```python
# ❌ DON'T: Use locks in Lambda
import threading
_lock = threading.Lock()
_instance = None

def get_instance():
    with _lock:  # Unnecessary overhead
        global _instance
        if _instance is None:
            _instance = create_resource()
    return _instance

# ✅ DO: Simple singleton (Lambda is single-threaded)
_instance = None

def get_instance():
    global _instance
    if _instance is None:
        _instance = create_resource()
    return _instance
```

### Anti-Pattern 4: No Reset for Testing ❌
```python
# ❌ DON'T: No way to reset singleton
_instance = None

def get_instance():
    global _instance
    if _instance is None:
        _instance = create_resource()
    return _instance

# Tests can't reset state!

# ✅ DO: Provide reset function
_instance = None

def get_instance():
    global _instance
    if _instance is None:
        _instance = create_resource()
    return _instance

def reset_instance():
    global _instance
    _instance = None
```

---

## 🔗 CROSS-REFERENCES

**Related Architecture:**
- ARCH-01 (SUGA): Singleton is Layer 1
- ARCH-02 (LMMS): Memory management for singletons
- ARCH-04 (ZAPH): Fast path uses singletons

**Related Interfaces:**
- INT-05 (Initialization): Initialize singletons
- INT-01 (Cache): Cache as singleton
- INT-04 (HTTP): HTTP session as singleton

**Related Patterns:**
- GATE-02 (Lazy Loading): Lazy singleton creation
- GATE-05 (Optimization): Reuse resources

**Related Lessons:**
- LESS-14 (Singleton): Singleton benefits
- LESS-23 (Performance): Resource reuse
- LESS-32 (Testing): Reset singletons

**Related Decisions:**
- DEC-14 (Singleton Pattern): Module-level variables
- DEC-20 (No Locks): Lambda is single-threaded

---

## ✅ VERIFICATION CHECKLIST

Before deploying singleton code:
- [ ] Stored at module level
- [ ] Lazy initialization used
- [ ] No threading locks
- [ ] Reset function provided (for testing)
- [ ] Factory pattern used (if complex)
- [ ] Initialization measured
- [ ] Memory impact evaluated
- [ ] Error handling included
- [ ] Documentation clear
- [ ] Tests use reset function

---

## 📊 COMMON SINGLETONS

### AWS Clients
```python
dynamodb = get_singleton('dynamodb')
s3 = get_singleton('s3')
sqs = get_singleton('sqs')
sns = get_singleton('sns')
```

### HTTP Sessions
```python
http = get_singleton('http_session')
api_client = get_singleton('api_client')
```

### Cache Instances
```python
cache = get_singleton('cache')
local_cache = get_singleton('local_cache')
```

---

**END OF INTERFACE ENTRY**

**REF-ID:** INT-09  
**Status:** Active  
**Lines:** 390
# AWS-Lambda-StatelessExecution_AWS-LESS-03.md

**Version:** 1.0.0  
**Date:** 2025-11-01  
**Purpose:** Stateless execution pattern for ephemeral environments

**REF-ID:** AWS-LESS-03  
**Category:** AWS Lambda  
**Type:** LESS (Lesson Learned)  
**Priority:** 🔴 High  
**Status:** Active  
**Source:** AWS Serverless Developer Guide (Genericized)

---

## Summary

Ephemeral execution environments require initializing state on startup and committing changes before exit. Never rely on in-memory state persisting across invocations. Pattern: Initialize → Process → Commit. Anything in memory is lost when container terminates.

**Core Principle:** Treat each invocation as potentially the last. Initialize state from durable storage, process using local variables, commit changes before returning. Global state is unreliable.

---

## The Ephemeral Reality

**Container Lifecycle:**
```
Cold Start:
  1. Platform creates new container
  2. Loads function code
  3. Initializes runtime
  4. Executes handler → Returns result
  
Warm Invocation (reuse):
  1. Reuses existing container
  2. Executes handler → Returns result
  (Initialization code does NOT re-run)
  
Container Termination (unpredictable):
  - Platform decides when to terminate
  - No warning, no cleanup opportunity
  - All in-memory state lost
  - Could be minutes or hours after last invocation
```

**Key Insight:**
```
You cannot predict:
  ❌ When container will be reused
  ❌ When container will be terminated
  ❌ Which invocations share a container
  ❌ How long container will live

You can only control:
  ✅ What state you fetch at start
  ✅ What processing happens during invocation
  ✅ What state you commit before exit
```

---

## The Stateless Pattern: Initialize → Process → Commit

**Pattern Structure:**
```python
# === INITIALIZATION (outside handler) ===
# Run once per container lifecycle
# Establish infrastructure connections
# Load immutable configuration
# Create reusable resources

# Global scope - runs at cold start
import json
import time

# Connection pool (infrastructure)
db_connection = create_connection_pool()

# Configuration (immutable)
CONFIG = load_config()

# === HANDLER (runs per invocation) ===
def handler(event, context):
    # === 1. INITIALIZE STATE ===
    # Fetch mutable state from durable storage
    user_id = event['userId']
    cart = fetch_from_database(user_id, 'cart')
    
    # === 2. PROCESS ===
    # Use local variables only
    # Never rely on global mutable state
    item = event['item']
    cart['items'].append(item)
    cart['total'] += item['price']
    cart['updated_at'] = time.time()
    
    # === 3. COMMIT ===
    # Save to durable storage before returning
    save_to_database(user_id, 'cart', cart)
    
    # Return result
    return {
        'statusCode': 200,
        'body': json.dumps({
            'cart': cart,
            'message': 'Item added'
        })
    }
```

---

## What Goes Where: Initialization vs Handler

**Initialization Scope (Outside Handler):**
```python
# ✅ GOOD: Infrastructure (reusable, immutable)

# Database connection pools
db_pool = create_connection_pool(
    host='db.example.com',
    max_connections=5
)

# API clients
api_client = HTTPClient(base_url='https://api.example.com')

# Immutable configuration
CONFIG = {
    'MAX_ITEMS': 100,
    'TAX_RATE': 0.08,
    'CURRENCY': 'USD'
}

# Compiled regex patterns
EMAIL_PATTERN = re.compile(r'^[a-z0-9]+@[a-z0-9]+\.[a-z]{2,}$')

Benefits:
  - Amortizes cost over multiple invocations
  - Warm containers skip this code
  - Reduces per-invocation latency
```

**Handler Scope (Inside Handler):**
```python
# ✅ GOOD: Business state (mutable, per-invocation)

def handler(event, context):
    # Fetch current state
    user_id = event['userId']
    account = db_pool.query("SELECT * FROM accounts WHERE id = ?", user_id)
    
    # Process with local variables
    transaction = {
        'amount': event['amount'],
        'timestamp': time.time(),
        'status': 'pending'
    }
    
    account['balance'] -= transaction['amount']
    account['transactions'].append(transaction)
    
    # Commit before exit
    db_pool.execute(
        "UPDATE accounts SET balance = ?, updated = ? WHERE id = ?",
        account['balance'], time.time(), user_id
    )
    
    return {'success': True, 'balance': account['balance']}
```

---

## Anti-Patterns: What NOT to Do

**Anti-Pattern 1: Global Mutable State**
```python
# ❌ WRONG: Counter in global scope
invocation_count = 0
user_sessions = {}

def handler(event, context):
    global invocation_count
    invocation_count += 1  # Unreliable!
    
    user_id = event['userId']
    user_sessions[user_id] = time.time()  # Lost on termination!
    
    return {'count': invocation_count}  # Wrong value!

Problem:
  - invocation_count resets unpredictably
  - user_sessions lost when container terminates
  - Different users might see different counts
  - No way to recover lost state
```

**Anti-Pattern 2: Caching Without Expiration**
```python
# ❌ WRONG: Unbounded cache in global scope
product_cache = {}

def handler(event, context):
    product_id = event['productId']
    
    if product_id not in product_cache:
        product_cache[product_id] = fetch_product(product_id)
    
    return product_cache[product_id]  # Stale data!

Problems:
  - Cache never expires (stale data)
  - Cache grows unbounded (memory leak)
  - Cache lost on termination (no persistence)
  - Different containers have different caches
```

**Anti-Pattern 3: Session State in Memory**
```python
# ❌ WRONG: User sessions in global dict
active_sessions = {}

def handler(event, context):
    session_id = event['sessionId']
    
    if session_id not in active_sessions:
        active_sessions[session_id] = {
            'user_id': event['userId'],
            'started': time.time()
        }
    
    # Use session data
    session = active_sessions[session_id]
    
    return {'welcome': f"User {session['user_id']}"}

Problems:
  - Sessions lost on container termination
  - Different containers can't access sessions
  - No way to share sessions across instances
```

---

## Correct Patterns

**Pattern 1: Fetch State from Durable Storage**
```python
# ✅ CORRECT: Initialize state per invocation

# Reusable connection (initialization)
db = create_db_connection()

def handler(event, context):
    # Fetch current state
    user_id = event['userId']
    user_data = db.get(f'user:{user_id}')
    
    # Process
    user_data['last_login'] = time.time()
    user_data['login_count'] += 1
    
    # Commit
    db.set(f'user:{user_id}', user_data)
    
    return {'user': user_data}
```

**Pattern 2: Time-Limited Caching**
```python
# ✅ CORRECT: Cache with TTL

# Cache infrastructure (initialization)
cache = {}

def handler(event, context):
    cache_key = f"product:{event['productId']}"
    
    # Check cache with expiration
    if cache_key in cache:
        cached_item, expires = cache[cache_key]
        if time.time() < expires:
            return cached_item
    
    # Fetch fresh data
    product = fetch_product(event['productId'])
    
    # Cache with 5-minute TTL
    cache[cache_key] = (product, time.time() + 300)
    
    return product

Note: Cache is optimization, not source of truth
```

**Pattern 3: Distributed Session Storage**
```python
# ✅ CORRECT: Sessions in external store

# Redis connection (initialization)
redis = create_redis_connection()

def handler(event, context):
    session_id = event['sessionId']
    
    # Fetch from durable storage
    session = redis.get(f'session:{session_id}')
    
    if not session:
        session = create_new_session(event)
        redis.set(f'session:{session_id}', session, ex=3600)
    
    # Use session
    process_with_session(session)
    
    return {'sessionId': session_id}
```

---

## Optimization: Amortize Initialization Cost

**Cold Start Optimization:**
```python
# === EXPENSIVE INITIALIZATION (once per container) ===

# Database pool (reused across invocations)
_db_pool = None

def get_db_connection():
    global _db_pool
    if _db_pool is None:
        _db_pool = create_connection_pool()
    return _db_pool

# API client (reused)
_api_client = None

def get_api_client():
    global _api_client
    if _api_client is None:
        _api_client = HTTPClient()
    return _api_client

# === HANDLER (fast, reuses infrastructure) ===
def handler(event, context):
    # Get reusable resources
    db = get_db_connection()
    api = get_api_client()
    
    # Fetch state
    data = db.get(event['key'])
    
    # Process
    result = api.post('/process', data)
    
    # Commit
    db.set(event['key'], result)
    
    return result

Benefit: Warm invocations skip expensive initialization
```

---

## Why This Matters

**Reliability:**
- State in memory is unreliable (lost unpredictably)
- Durable storage ensures consistency
- Multiple instances can access shared state

**Scalability:**
- Stateless functions scale horizontally
- No need for sticky sessions
- Load balancer can route anywhere

**Cost:**
- Warm containers reuse initialization
- Amortize expensive setup over many invocations
- Pay once for connection pools, configs, etc.

---

## When to Apply

**Always:**
- ✅ Fetch mutable state from durable storage
- ✅ Commit changes before returning
- ✅ Use local variables for processing

**Optimize:**
- ✅ Initialize connections outside handler
- ✅ Cache immutable data (with TTL)
- ✅ Reuse compiled patterns, configs

**Never:**
- ❌ Store mutable state in global scope
- ❌ Rely on in-memory counters/aggregates
- ❌ Use global session storage

---

## Cross-References

**AWS Patterns:**
- AWS-Lambda-TimeoutConstraints_AWS-LESS-12.md - Complete work before timeout
- /sima/aws/lambda/ - Cold Start Mitigation patterns

**Project Maps:**
- /sima/entries/core/ARCH-LMMS_Lambda Memory Management System.md - Initialization optimization
- /sima/entries/decisions/architecture/DEC-01.md - SUGA Pattern gateway initialization

---

## Keywords

stateless, ephemeral, state management, initialization, durable storage, session management, cold start, container lifecycle

---

**Location:** `/sima/aws/lambda/`  
**Total Lines:** 395 (≤400 for SIMAv4)  
**SIMAv4 Compliant:** ✅

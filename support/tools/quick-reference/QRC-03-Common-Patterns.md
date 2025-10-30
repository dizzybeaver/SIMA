# File: QRC-03-Common-Patterns.md

**REF-ID:** QRC-03  
**Version:** 1.0.0  
**Category:** Support Tools  
**Type:** Quick Reference Card  
**Purpose:** Frequently used code patterns and solutions

---

## 📋 10 MOST COMMON PATTERNS

### 1. Cache with Fallback
**Use:** Try cache first, compute on miss, cache result

```python
from gateway import cache_get, cache_set

def get_data(key: str):
    # Try cache
    cached = cache_get(key)
    if cached:
        return cached
    
    # Compute
    data = expensive_operation()
    
    # Cache
    cache_set(key, data, ttl=300)
    return data
```

**Variations:**
- Add timeout parameter
- Handle cache errors gracefully
- Use default value on miss

---

### 2. Error Logging
**Use:** Log errors with context for debugging

```python
from gateway import logging_error, logging_info

def process_data(data):
    try:
        result = transform(data)
        logging_info("Processing successful")
        return result
    except ValueError as e:
        logging_error(f"Invalid data: {e}")
        return None
    except Exception as e:
        logging_error(f"Unexpected error: {e}")
        raise
```

**Best Practices:**
- Catch specific exceptions first
- Log with context (what failed, why)
- Re-raise if can't handle

---

### 3. API with Retry
**Use:** Call external API with retry logic

```python
from gateway import api_get, logging_error
import time

def fetch_with_retry(url: str, max_retries=3):
    for attempt in range(max_retries):
        try:
            return api_get(url, timeout=10)
        except TimeoutError:
            if attempt == max_retries - 1:
                raise
            logging_error(f"Attempt {attempt + 1} failed, retrying...")
            time.sleep(2 ** attempt)  # Exponential backoff
```

**Enhancements:**
- Add circuit breaker
- Cache successful responses
- Use async for parallel requests

---

### 4. Secure Configuration
**Use:** Get and decrypt sensitive config values

```python
from gateway import config_get, security_decrypt, logging_error

def get_api_key() -> str:
    encrypted = config_get("api_key_encrypted")
    
    if not encrypted:
        logging_error("API key missing")
        raise ValueError("Missing API key")
    
    try:
        return security_decrypt(encrypted)
    except Exception as e:
        logging_error(f"Decryption failed: {e}")
        raise
```

**Security Notes:**
- Never log decrypted values
- Validate after decryption
- Use secure storage (Secrets Manager)

---

### 5. Cached API Response
**Use:** Cache external API responses to reduce calls

```python
from gateway import api_get, cache_get, cache_set, logging_info

def fetch_weather(city: str) -> dict:
    cache_key = f"weather:{city}"
    
    # Check cache
    cached = cache_get(cache_key)
    if cached:
        logging_info(f"Cache hit: {city}")
        return cached
    
    # Fetch from API
    data = api_get(f"https://api.weather.com/{city}")
    
    # Cache for 30 minutes
    cache_set(cache_key, data, ttl=1800)
    logging_info(f"Fetched and cached: {city}")
    
    return data
```

**Cache Strategy:**
- Short TTL for dynamic data
- Long TTL for static data
- Invalidate on updates

---

### 6. Input Validation
**Use:** Validate inputs before processing

```python
from gateway import utility_sanitize, logging_error

def process_user_input(data: dict) -> dict:
    # Validate required fields
    required = ['name', 'email']
    missing = [f for f in required if f not in data]
    if missing:
        logging_error(f"Missing fields: {missing}")
        raise ValueError(f"Missing: {', '.join(missing)}")
    
    # Sanitize
    data['name'] = utility_sanitize(data['name'])
    data['email'] = utility_sanitize(data['email'])
    
    # Validate email format
    if '@' not in data['email']:
        logging_error(f"Invalid email: {data['email']}")
        raise ValueError("Invalid email format")
    
    return data
```

**Validation Types:**
- Type checking
- Range checking
- Format validation
- Sanitization

---

### 7. Batch Processing
**Use:** Process items in batches with logging

```python
from gateway import logging_info, logging_error, metrics_record

def process_batch(items: list, batch_size=100):
    total = len(items)
    
    for i in range(0, total, batch_size):
        batch = items[i:i + batch_size]
        
        try:
            process_items(batch)
            processed = min(i + batch_size, total)
            logging_info(f"Processed {processed}/{total}")
            metrics_record("batch_processed", len(batch))
        except Exception as e:
            logging_error(f"Batch {i}-{i+batch_size} failed: {e}")
            # Continue or raise based on requirements
```

**Considerations:**
- Optimal batch size
- Error handling strategy
- Progress tracking
- Memory management

---

### 8. Singleton Pattern
**Use:** Ensure single instance of resource

```python
from gateway import singleton_get

def get_database_connection():
    conn = singleton_get("db_connection")
    
    if conn is None:
        # Initialize connection
        conn = create_connection()
        # Store in singleton
        singleton_set("db_connection", conn)
    
    return conn
```

**Use Cases:**
- Database connections
- Configuration objects
- Resource pools
- Shared state

---

### 9. Metrics Recording
**Use:** Track performance and business metrics

```python
from gateway import metrics_record, metrics_increment
import time

def timed_operation(operation_name: str, func, *args, **kwargs):
    start = time.time()
    
    try:
        result = func(*args, **kwargs)
        
        # Record duration
        duration = time.time() - start
        metrics_record(f"{operation_name}_duration", duration)
        
        # Increment success counter
        metrics_increment(f"{operation_name}_success")
        
        return result
    except Exception as e:
        # Increment error counter
        metrics_increment(f"{operation_name}_error")
        raise
```

**Metric Types:**
- Counters (increments)
- Gauges (current value)
- Timers (duration)
- Histograms (distribution)

---

### 10. State Machine Pattern
**Use:** Manage complex state transitions

```python
from gateway import logging_info, logging_error

class StateMachine:
    def __init__(self):
        self.state = "idle"
        self.transitions = {
            "idle": ["processing"],
            "processing": ["completed", "failed"],
            "completed": ["idle"],
            "failed": ["idle"]
        }
    
    def transition(self, new_state: str):
        if new_state not in self.transitions.get(self.state, []):
            logging_error(f"Invalid transition: {self.state} -> {new_state}")
            raise ValueError(f"Cannot go from {self.state} to {new_state}")
        
        logging_info(f"State: {self.state} -> {new_state}")
        self.state = new_state
```

**Applications:**
- Workflow management
- Process orchestration
- Connection lifecycle

---

## 🎯 ANTI-PATTERNS TO AVOID

### Anti-Pattern 1: Bare Except
```python
# ❌ WRONG
try:
    operation()
except:  # Catches everything, hides errors
    pass

# ✅ CORRECT
try:
    operation()
except SpecificError as e:
    logging_error(f"Expected error: {e}")
except Exception as e:
    logging_error(f"Unexpected error: {e}")
    raise
```

### Anti-Pattern 2: Direct Imports
```python
# ❌ WRONG
from cache_core import _get_value

# ✅ CORRECT
from gateway import cache_get
```

### Anti-Pattern 3: Threading Locks
```python
# ❌ WRONG (Lambda is single-threaded)
lock = threading.Lock()
with lock:
    operation()

# ✅ CORRECT
operation()  # No lock needed
```

### Anti-Pattern 4: Global Mutable State
```python
# ❌ WRONG
CACHE = {}  # Module-level mutable

def cache_data(key, value):
    CACHE[key] = value  # Memory leak

# ✅ CORRECT
from gateway import cache_set

def cache_data(key, value):
    cache_set(key, value)  # Managed cache
```

---

## 🔧 PATTERN COMBINATIONS

### Combination 1: Cached API with Error Handling
```python
from gateway import (
    api_get,
    cache_get,
    cache_set,
    logging_error,
    logging_info
)

def fetch_with_cache(url: str, cache_key: str, ttl=300):
    # Try cache
    cached = cache_get(cache_key)
    if cached:
        logging_info(f"Cache hit: {cache_key}")
        return cached
    
    # Fetch with error handling
    try:
        data = api_get(url, timeout=10)
        cache_set(cache_key, data, ttl=ttl)
        logging_info(f"Fetched and cached: {cache_key}")
        return data
    except TimeoutError as e:
        logging_error(f"API timeout: {e}")
        return None
    except Exception as e:
        logging_error(f"API error: {e}")
        raise
```

### Combination 2: Validated Secure Processing
```python
from gateway import (
    utility_sanitize,
    security_encrypt,
    logging_error,
    logging_info
)

def process_secure_input(data: dict) -> str:
    # Validate
    if not data.get('value'):
        logging_error("Missing value")
        raise ValueError("Value required")
    
    # Sanitize
    sanitized = utility_sanitize(data['value'])
    logging_info("Input sanitized")
    
    # Encrypt
    try:
        encrypted = security_encrypt(sanitized)
        logging_info("Data encrypted")
        return encrypted
    except Exception as e:
        logging_error(f"Encryption failed: {e}")
        raise
```

### Combination 3: Metrics with Logging
```python
from gateway import (
    metrics_record,
    metrics_increment,
    logging_info,
    logging_error
)
import time

def monitored_operation(name: str):
    start = time.time()
    logging_info(f"Starting: {name}")
    
    try:
        result = perform_operation()
        
        duration = time.time() - start
        metrics_record(f"{name}_duration", duration)
        metrics_increment(f"{name}_success")
        
        logging_info(f"Completed: {name} in {duration:.2f}s")
        return result
        
    except Exception as e:
        metrics_increment(f"{name}_error")
        logging_error(f"Failed: {name} - {e}")
        raise
```

---

## 📚 PATTERN CATALOG

### By Category

**Data Access:**
- Cache with Fallback (#1)
- Cached API Response (#5)
- Singleton Pattern (#8)

**Error Handling:**
- Error Logging (#2)
- API with Retry (#3)
- Validated Input (#6)

**Security:**
- Secure Configuration (#4)
- Validated Secure Processing (Combo #2)

**Performance:**
- Batch Processing (#7)
- Metrics Recording (#9)
- Cached responses (#5)

**Control Flow:**
- State Machine (#10)
- Retry Logic (#3)

---

## 🎓 USAGE GUIDELINES

### When to Use Each Pattern

**Cache with Fallback:**
- Expensive computations
- External API calls
- Database queries
- Repeated operations

**Error Logging:**
- All error conditions
- Debugging scenarios
- Production monitoring
- Audit trails

**API with Retry:**
- Unreliable networks
- Rate-limited APIs
- Timeout-prone services
- Critical operations

**Secure Configuration:**
- API keys
- Passwords
- Tokens
- Sensitive settings

**Input Validation:**
- User input
- External data
- Configuration
- API parameters

---

## ⚠️ PATTERN SELECTION

### Decision Tree

```
Need external data?
├─ Yes: API with Retry (#3) + Cache (#5)
└─ No: Continue

Need to store data?
├─ Temporary: Cache with Fallback (#1)
├─ Persistent: Database pattern
└─ Singleton: Singleton Pattern (#8)

Need error handling?
└─ Yes: Error Logging (#2) + Retry (#3)

Need validation?
└─ Yes: Input Validation (#6)

Need monitoring?
└─ Yes: Metrics Recording (#9)

Complex state?
└─ Yes: State Machine (#10)
```

---

## 🔗 RELATED RESOURCES

**Architecture Patterns:**
- ARCH-01: SUGA Pattern
- ARCH-04: ZAPH Pattern
- GATE-01 to GATE-05: Gateway Patterns

**Interface References:**
- QRC-01: Interfaces Overview
- INT-01 to INT-12: Interface Catalogs

**Anti-Patterns:**
- AP-05: Exception Handling
- AP-08: Threading Locks
- BUG-03: Memory Leaks

**Lessons:**
- LESS-01: Always Fetch First
- LESS-15: SUGA Verification

**Workflows:**
- WF-01: Add Feature
- WF-02: Debug Issue

---

**END OF QRC-03**

**Related cards:** QRC-01 (Interfaces), QRC-02 (Gateway Patterns)

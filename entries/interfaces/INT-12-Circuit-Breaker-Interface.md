# File: INT-12-Circuit-Breaker-Interface.md

**REF-ID:** INT-12  
**Version:** 1.0.0  
**Category:** Interface Pattern  
**Status:** Active  
**Priority:** 🟡 HIGH  
**Created:** 2025-11-01  
**Contributors:** SIMA Learning Mode

---

## 📋 OVERVIEW

**Interface Name:** CIRCUIT_BREAKER  
**Short Code:** CB  
**Type:** Resilience Pattern Interface  
**Dependency Layer:** Layer 2 (Services)

**One-Line Description:**  
CIRCUIT_BREAKER prevents cascading failures by failing fast when external services are unavailable.

**Primary Purpose:**  
Protect system from repeatedly calling failing services, provide graceful degradation, and enable automatic recovery.

---

## 🎯 CORE RESPONSIBILITIES

### 1. Failure Detection
- Monitor service call success/failure
- Track error rates
- Count consecutive failures
- Detect timeout patterns

### 2. State Management
- **Closed:** Normal operation (requests pass through)
- **Open:** Service failing (requests fail immediately)
- **Half-Open:** Testing recovery (limited requests allowed)

### 3. Fast Failure
- Fail immediately when circuit open
- No waiting for timeout
- Prevent resource exhaustion
- Return fallback values

### 4. Automatic Recovery
- Attempt recovery after timeout
- Test with limited requests
- Close circuit if recovery successful
- Re-open if still failing

---

## 🔑 KEY RULES

### Rule 1: Three States (Closed, Open, Half-Open)
**What:** Circuit breaker has three distinct states.

**States:**
- **Closed (Normal):** Requests pass through. Monitor for failures.
- **Open (Failing):** Too many failures detected. Fail requests immediately.
- **Half-Open (Testing):** After timeout, allow limited requests to test recovery.

**Transitions:**
```
Closed âžœ Open: N consecutive failures
Open âžœ Half-Open: After timeout period
Half-Open âžœ Closed: Successful test requests
Half-Open âžœ Open: Test requests still failing
```

**Example:**
```python
from gateway import circuit_breaker

@circuit_breaker(
    failure_threshold=5,        # Open after 5 failures
    timeout=60,                 # Try recovery after 60s
    half_open_max_requests=3    # Test with 3 requests
)
def call_external_api():
    return requests.get("https://api.example.com/data")
```

---

### Rule 2: Fail Fast When Open
**What:** When circuit is open, fail immediately without calling service.

**Why:** Don't waste time waiting for timeout. Protect system resources.

**Impact:**
```
Circuit Closed (normal):
- Call service
- Wait for timeout (10-30s)
- Return error
Total: 10-30 seconds

Circuit Open (fast fail):
- Check circuit state
- Return cached/fallback immediately
- No service call
Total: 0-10 milliseconds (1000x faster)
```

**Example:**
```python
from gateway import is_circuit_open, get_cached_response

def get_user_data(user_id):
    if is_circuit_open('user_api'):
        # Fast fail: return cached data
        return get_cached_response('user_api', user_id)
    
    # Circuit closed: call API
    return call_user_api(user_id)
```

---

### Rule 3: Set Appropriate Thresholds
**What:** Configure failure threshold and timeout based on service criticality.

**Guidelines:**
- **Critical services:** Lower threshold (3-5 failures), shorter timeout (30-60s)
- **Non-critical:** Higher threshold (10-20 failures), longer timeout (120-300s)
- **Flaky services:** Higher threshold, shorter timeout

**Example:**
```python
# Critical service (strict)
@circuit_breaker(
    name="payment_api",
    failure_threshold=3,    # Open after 3 failures
    timeout=30              # Test recovery after 30s
)
def call_payment_api():
    pass

# Non-critical service (lenient)
@circuit_breaker(
    name="analytics_api",
    failure_threshold=10,   # Open after 10 failures
    timeout=300             # Test recovery after 5 min
)
def call_analytics_api():
    pass
```

---

### Rule 4: Provide Fallback Behavior
**What:** Always have fallback when circuit opens.

**Fallback Strategies:**
- Return cached data
- Return default values
- Return degraded response
- Redirect to backup service

**Example:**
```python
from gateway import circuit_breaker_with_fallback

@circuit_breaker_with_fallback(
    name="user_api",
    fallback=lambda user_id: {"id": user_id, "name": "Unknown", "cached": True}
)
def get_user(user_id):
    response = requests.get(f"https://api.example.com/users/{user_id}")
    return response.json()

# If circuit open, fallback is called automatically
user = get_user(123)  # Returns fallback if circuit open
```

---

### Rule 5: Monitor Circuit State
**What:** Track circuit breaker metrics for all services.

**Metrics to Track:**
- Circuit state (closed/open/half-open)
- Failure count
- Success count
- Time in each state
- Fallback invocation count

**Example:**
```python
from gateway import get_circuit_stats, log_metric

def monitor_circuits():
    for service in ['api1', 'api2', 'database']:
        stats = get_circuit_stats(service)
        
        log_metric(
            name=f"CircuitState_{service}",
            value=stats['state'],  # 0=closed, 1=open, 2=half-open
            dimensions={"Service": service}
        )
        
        log_metric(
            name=f"CircuitFailures_{service}",
            value=stats['failure_count'],
            dimensions={"Service": service}
        )
```

---

## 🎨 MAJOR BENEFITS

### Benefit 1: Prevents Cascading Failures
- Failing service doesn't bring down entire system
- Fast failure instead of timeout waiting
- System remains partially functional
- User experience degraded but not broken

### Benefit 2: Resource Protection
- No threads waiting for timeouts
- Lower memory usage
- Prevents connection pool exhaustion
- Protects Lambda from hanging

### Benefit 3: Automatic Recovery
- System tests recovery automatically
- No manual intervention needed
- Gradual recovery via half-open state
- Self-healing architecture

### Benefit 4: Performance
```
Before Circuit Breaker:
- Service down
- Every request waits 30s for timeout
- 100 requests = 3000s (50 minutes!)

After Circuit Breaker:
- Service down, circuit opens
- First 5 requests: 30s each (150s)
- Next 95 requests: 10ms each (0.95s)
- Total: 151s (20x faster!)
```

---

## 📚 CORE FUNCTIONS

### Gateway Functions

```python
# Decorator patterns
@circuit_breaker(name, failure_threshold=5, timeout=60)
@circuit_breaker_with_fallback(name, fallback, failure_threshold=5)

# Manual control
open_circuit(name)
close_circuit(name)
reset_circuit(name)
force_half_open(name)

# State checking
is_circuit_open(name)
is_circuit_closed(name)
is_circuit_half_open(name)
get_circuit_state(name)

# Statistics
get_circuit_stats(name)
get_failure_count(name)
get_success_count(name)

# Configuration
configure_circuit(name, failure_threshold, timeout, half_open_max)
register_fallback(name, fallback_function)

# Monitoring
get_all_circuits()
get_open_circuits()
export_circuit_metrics()
```

---

## 🔄 USAGE PATTERNS

### Pattern 1: Basic Circuit Breaker
```python
from gateway import circuit_breaker

@circuit_breaker(
    name="external_api",
    failure_threshold=5,
    timeout=60
)
def call_external_api(endpoint):
    response = requests.get(f"https://api.example.com/{endpoint}")
    response.raise_for_status()
    return response.json()

# Automatic circuit breaking on failures
try:
    data = call_external_api("/users")
except CircuitBreakerOpen:
    # Circuit is open, service unavailable
    log_error("API circuit breaker open")
    data = get_cached_data()
```

### Pattern 2: Circuit Breaker with Fallback
```python
from gateway import circuit_breaker_with_fallback

def fallback_user_data(user_id):
    """Return cached or default data"""
    cached = get_from_cache(f"user:{user_id}")
    if cached:
        cached['cached'] = True
        return cached
    
    return {
        "id": user_id,
        "name": "Unknown User",
        "email": "",
        "fallback": True
    }

@circuit_breaker_with_fallback(
    name="user_service",
    fallback=fallback_user_data,
    failure_threshold=3
)
def get_user(user_id):
    response = requests.get(f"https://api.example.com/users/{user_id}")
    return response.json()

# Fallback automatically called if circuit open
user = get_user(123)  # Returns fallback if unavailable
```

### Pattern 3: Manual Circuit Control
```python
from gateway import is_circuit_open, open_circuit, close_circuit

def health_check():
    """Manual health check can control circuit"""
    try:
        response = requests.get("https://api.example.com/health", timeout=5)
        if response.status_code == 200:
            close_circuit("api")  # Manually close if healthy
        else:
            open_circuit("api")   # Manually open if unhealthy
    except:
        open_circuit("api")

def process_request():
    if is_circuit_open("api"):
        return fallback_response()
    
    return call_api()
```

### Pattern 4: Per-Service Circuits
```python
from gateway import circuit_breaker

@circuit_breaker(name="database", failure_threshold=3, timeout=30)
def query_database(query):
    return database.execute(query)

@circuit_breaker(name="cache", failure_threshold=10, timeout=60)
def get_from_cache(key):
    return cache.get(key)

@circuit_breaker(name="api", failure_threshold=5, timeout=120)
def call_external_api(endpoint):
    return requests.get(endpoint)

# Each service has independent circuit
def process():
    data = query_database("SELECT ...")      # Database circuit
    cached = get_from_cache("key")           # Cache circuit
    external = call_external_api("/data")    # API circuit
```

### Pattern 5: Monitoring and Alerting
```python
from gateway import get_all_circuits, log_metric, log_warning

def monitor_circuit_health():
    """Check circuit breaker health"""
    circuits = get_all_circuits()
    
    for name, stats in circuits.items():
        # Log metrics
        log_metric(f"Circuit.{name}.State", stats['state'])
        log_metric(f"Circuit.{name}.Failures", stats['failures'])
        
        # Alert if circuit open
        if stats['state'] == 'open':
            log_warning(f"Circuit breaker OPEN: {name}")
            send_alert(f"Service {name} unavailable - circuit breaker activated")
        
        # Alert if half-open (testing recovery)
        if stats['state'] == 'half-open':
            log_info(f"Circuit breaker testing recovery: {name}")
```

---

## ⚠️ ANTI-PATTERNS

### Anti-Pattern 1: No Fallback ❌
```python
# ❌ DON'T: No fallback (breaks user experience)
@circuit_breaker(name="api")
def get_data():
    return api.call()

# When circuit opens, raises exception with no fallback

# ✅ DO: Provide fallback
@circuit_breaker_with_fallback(
    name="api",
    fallback=lambda: get_cached_data()
)
def get_data():
    return api.call()
```

### Anti-Pattern 2: Same Threshold for All Services ❌
```python
# ❌ DON'T: One-size-fits-all thresholds
@circuit_breaker(name="critical_payment", failure_threshold=10)
@circuit_breaker(name="analytics", failure_threshold=10)

# Payment API should be more strict!

# ✅ DO: Per-service thresholds
@circuit_breaker(name="critical_payment", failure_threshold=3, timeout=30)
@circuit_breaker(name="analytics", failure_threshold=20, timeout=300)
```

### Anti-Pattern 3: Not Monitoring Circuit State ❌
```python
# ❌ DON'T: Deploy and forget
@circuit_breaker(name="api")
def call_api():
    pass

# No visibility into circuit health

# ✅ DO: Monitor and alert
@circuit_breaker(name="api")
def call_api():
    pass

# Separate monitoring function
monitor_circuit_health()  # Track state, alert if open
```

### Anti-Pattern 4: Circuit Per Request ❌
```python
# ❌ DON'T: Create circuit breaker per request
def lambda_handler(event, context):
    @circuit_breaker(name="api")  # WRONG! New circuit every request
    def call_api():
        pass

# ✅ DO: Define at module level
@circuit_breaker(name="api")  # Defined once
def call_api():
    pass

def lambda_handler(event, context):
    call_api()  # Uses same circuit across requests
```

---

## 🔗 CROSS-REFERENCES

**Related Architecture:**
- ARCH-01 (SUGA): Circuit breaker is Layer 2
- ARCH-03 (DD): Circuit breaker in dispatch

**Related Interfaces:**
- INT-04 (HTTP): Protect HTTP calls with circuit breaker
- INT-02 (Logging): Log circuit state changes
- INT-07 (Metrics): Track circuit metrics
- INT-01 (Cache): Fallback to cache

**Related Patterns:**
- GATE-03 (Cross-Interface): CB + HTTP + Cache + Logging

**Related Lessons:**
- LESS-11 (Resilience): Circuit breaker benefits
- LESS-17 (Failures): Cascading failure prevention
- LESS-38 (Recovery): Automatic recovery patterns

**Related Decisions:**
- DEC-23 (Circuit Breaker): When to use
- DEC-24 (Thresholds): Default settings

---

## ✅ VERIFICATION CHECKLIST

Before deploying circuit breaker:
- [ ] Failure threshold configured
- [ ] Timeout period set
- [ ] Fallback behavior defined
- [ ] State transitions tested
- [ ] Metrics tracking implemented
- [ ] Alerts configured for open state
- [ ] Half-open recovery tested
- [ ] Per-service circuits defined
- [ ] Monitoring dashboard created
- [ ] Documentation updated

---

## 📊 CIRCUIT BREAKER STATES

### State Diagram
```
         failure_threshold reached
Closed ────────────ðŸ"´â"€â"€â"€â"€â"€â"€â"€â"€â"€> Open
  â†'                                 │
  │                                 │ timeout elapsed
  │                                 ↓
  │                            Half-Open
  â"" success_threshold reached    │
    <────────────🟢────────────────┘
           │
           │ still failing
           â"" â"€â"€â"€â"€ðŸ"´â"€â"€â"€> Open
```

### Configuration Defaults
```python
DEFAULT_FAILURE_THRESHOLD = 5      # Open after 5 failures
DEFAULT_TIMEOUT = 60               # Test recovery after 60s
DEFAULT_HALF_OPEN_MAX = 3          # Test with 3 requests
DEFAULT_SUCCESS_THRESHOLD = 2      # Close after 2 successes
```

---

**END OF INTERFACE ENTRY**

**REF-ID:** INT-12  
**Status:** Active  
**Lines:** 395
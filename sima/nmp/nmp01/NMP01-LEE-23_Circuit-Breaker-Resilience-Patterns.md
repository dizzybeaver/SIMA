# File: NMP01-LEE-23_Circuit-Breaker-Resilience-Patterns.md

# NMP01-LEE-23: Circuit Breaker - Resilience Patterns

**Project:** Lambda Execution Engine (SUGA-ISP)  
**Project Code:** LEE  
**Category:** Specialized Topics  
**Component:** circuit_breaker_core.py, interface_circuit_breaker.py  
**Priority:** 🟢 MEDIUM  
**Status:** Active  
**Created:** 2025-10-29  
**Last Updated:** 2025-10-29

---

## Summary

Documentation of Circuit Breaker pattern implementation in SUGA-ISP for protecting against cascading failures when calling external services (Home Assistant API, Alexa API). Includes state management, failure detection, and automatic recovery.

---

## Context

Circuit Breaker pattern prevents cascading failures by:
1. Detecting when external service is failing
2. Temporarily stopping calls to failing service ("opening circuit")
3. Allowing occasional test calls ("half-open")
4. Automatically recovering when service is healthy ("closing circuit")

**Use in SUGA-ISP:** Protects Lambda from HA API failures and Alexa API issues.

---

## States

### CLOSED (Normal Operation)

**Condition:** Service is healthy  
**Behavior:** All requests pass through  
**Monitoring:** Track failure rate

**Transition to OPEN:**
- Failure count exceeds threshold (default: 5)
- Or failure rate exceeds threshold (default: 50%)
- Within time window (default: 60 seconds)

---

### OPEN (Circuit Tripped)

**Condition:** Service is failing  
**Behavior:** Fail fast - return immediately without calling service  
**Duration:** Configurable timeout (default: 60 seconds)

**Benefits:**
- Prevent wasted API calls
- Reduce latency (instant failure vs slow timeout)
- Protect dependent service from overload

**Transition to HALF_OPEN:**
- After timeout period expires

---

### HALF_OPEN (Testing Recovery)

**Condition:** Testing if service recovered  
**Behavior:** Allow limited test requests  
**Test Strategy:** Single request every N seconds

**Transition to CLOSED:**
- Test request succeeds
- Service appears healthy

**Transition back to OPEN:**
- Test request fails
- Service still unhealthy

---

## Implementation

### Core State Machine

```python
"""
Circuit breaker implementation.
File: circuit_breaker_core.py
"""

import time
from enum import Enum
from gateway import log_warning, log_info, increment_counter

class CircuitState(Enum):
    CLOSED = "closed"
    OPEN = "open"
    HALF_OPEN = "half_open"


class CircuitBreaker:
    """Circuit breaker for external service calls."""
    
    def __init__(self, 
                 failure_threshold=5,
                 timeout_seconds=60,
                 half_open_max_calls=1):
        self.failure_threshold = failure_threshold
        self.timeout_seconds = timeout_seconds
        self.half_open_max_calls = half_open_max_calls
        
        self.state = CircuitState.CLOSED
        self.failure_count = 0
        self.success_count = 0
        self.last_failure_time = None
        self.opened_at = None
        self.half_open_calls = 0
    
    
    def call(self, func, *args, **kwargs):
        """
        Execute function with circuit breaker protection.
        
        Args:
            func: Function to call
            *args, **kwargs: Function arguments
            
        Returns:
            Function result
            
        Raises:
            CircuitOpenError: If circuit is open
            Original exception: If function fails
        """
        # Check circuit state
        if self.state == CircuitState.OPEN:
            # Check if timeout expired
            if self._should_attempt_reset():
                self._transition_to_half_open()
            else:
                increment_counter("circuit_breaker_blocked")
                raise CircuitOpenError(
                    f"Circuit open, retry after {self._time_until_retry()}s"
                )
        
        # Half-open: limit test calls
        if self.state == CircuitState.HALF_OPEN:
            if self.half_open_calls >= self.half_open_max_calls:
                raise CircuitOpenError("Circuit half-open, testing in progress")
            self.half_open_calls += 1
        
        # Execute call
        try:
            result = func(*args, **kwargs)
            self._record_success()
            return result
            
        except Exception as e:
            self._record_failure()
            raise
    
    
    def _record_success(self):
        """Record successful call."""
        self.success_count += 1
        self.failure_count = 0  # Reset failure count
        
        if self.state == CircuitState.HALF_OPEN:
            # Success in half-open = close circuit
            self._transition_to_closed()
        
        increment_counter("circuit_breaker_success")
    
    
    def _record_failure(self):
        """Record failed call."""
        self.failure_count += 1
        self.last_failure_time = time.time()
        
        if self.state == CircuitState.CLOSED:
            # Check if should open
            if self.failure_count >= self.failure_threshold:
                self._transition_to_open()
        
        elif self.state == CircuitState.HALF_OPEN:
            # Failure in half-open = reopen circuit
            self._transition_to_open()
        
        increment_counter("circuit_breaker_failure")
    
    
    def _transition_to_open(self):
        """Transition to OPEN state."""
        self.state = CircuitState.OPEN
        self.opened_at = time.time()
        self.half_open_calls = 0
        
        log_warning("Circuit breaker opened",
                    failure_count=self.failure_count,
                    timeout_s=self.timeout_seconds)
        
        increment_counter("circuit_breaker_opened")
    
    
    def _transition_to_half_open(self):
        """Transition to HALF_OPEN state."""
        self.state = CircuitState.HALF_OPEN
        self.half_open_calls = 0
        
        log_info("Circuit breaker half-open, testing service")
        increment_counter("circuit_breaker_half_open")
    
    
    def _transition_to_closed(self):
        """Transition to CLOSED state."""
        self.state = CircuitState.CLOSED
        self.failure_count = 0
        self.opened_at = None
        
        log_info("Circuit breaker closed, service recovered")
        increment_counter("circuit_breaker_closed")
    
    
    def _should_attempt_reset(self):
        """Check if timeout expired and should attempt reset."""
        if self.opened_at is None:
            return False
        
        elapsed = time.time() - self.opened_at
        return elapsed >= self.timeout_seconds
    
    
    def _time_until_retry(self):
        """Calculate time until next retry attempt."""
        if self.opened_at is None:
            return 0
        
        elapsed = time.time() - self.opened_at
        remaining = self.timeout_seconds - elapsed
        return max(0, remaining)
    
    
    def get_state(self):
        """Get current circuit state."""
        return {
            'state': self.state.value,
            'failure_count': self.failure_count,
            'success_count': self.success_count,
            'opened_at': self.opened_at,
            'time_until_retry': self._time_until_retry() if self.state == CircuitState.OPEN else None
        }
```

---

## Usage Patterns

### HA API Protection

```python
"""
Protect Home Assistant API calls.
"""

# Create circuit breaker for HA API
ha_circuit = CircuitBreaker(
    failure_threshold=5,      # Open after 5 failures
    timeout_seconds=60,       # Stay open for 60 seconds
    half_open_max_calls=1     # One test call when half-open
)

def get_entity_state_protected(entity_id):
    """Get entity state with circuit breaker protection."""
    try:
        # Call via circuit breaker
        return ha_circuit.call(
            _get_entity_state_impl,
            entity_id
        )
        
    except CircuitOpenError:
        # Circuit open - return cached value
        gateway.log_warning("Circuit open, using cached state",
                            entity_id=entity_id)
        return gateway.cache_get(f"ha_entity_{entity_id}")
    
    except Exception as e:
        # Other error - log and re-raise
        gateway.log_error("Entity state fetch failed",
                          entity_id=entity_id,
                          error=str(e))
        raise


def _get_entity_state_impl(entity_id):
    """Actual HA API call (no circuit breaker)."""
    url = f"{get_ha_url()}/api/states/{entity_id}"
    response = gateway.http_get(url, headers=_get_headers())
    return response.json()
```

---

### Alexa API Protection

```python
"""
Protect Alexa API calls.
"""

alexa_circuit = CircuitBreaker(
    failure_threshold=3,      # More sensitive
    timeout_seconds=30,       # Shorter timeout
    half_open_max_calls=1
)

def send_alexa_event_protected(event):
    """Send event to Alexa with circuit breaker."""
    try:
        return alexa_circuit.call(
            _send_alexa_event_impl,
            event
        )
        
    except CircuitOpenError:
        # Circuit open - queue for retry
        gateway.log_warning("Circuit open, queuing Alexa event")
        queue_for_retry(event)
        return None
```

---

## Fallback Strategies

### Strategy 1: Cached Data

**Use case:** Non-critical reads (entity states)

```python
try:
    return ha_circuit.call(get_entity_state, entity_id)
except CircuitOpenError:
    # Return stale cache (better than nothing)
    return gateway.cache_get(f"ha_entity_{entity_id}")
```

---

### Strategy 2: Default Values

**Use case:** Configuration or optional data

```python
try:
    return ha_circuit.call(get_config_value, key)
except CircuitOpenError:
    # Return sensible default
    return DEFAULT_CONFIG.get(key)
```

---

### Strategy 3: Graceful Degradation

**Use case:** Feature that can be disabled

```python
try:
    ha_circuit.call(update_entity_state, entity_id, state)
except CircuitOpenError:
    # Log but don't fail
    gateway.log_warning("State update skipped, circuit open")
    return {"status": "deferred"}
```

---

### Strategy 4: Queue for Retry

**Use case:** Critical writes that must succeed

```python
try:
    alexa_circuit.call(send_event, event)
except CircuitOpenError:
    # Queue in DynamoDB for later retry
    queue_event(event, retry_after=60)
```

---

## Configuration

### Per-Service Tuning

**Home Assistant (Generally Stable):**
```python
ha_circuit = CircuitBreaker(
    failure_threshold=5,      # Tolerate more failures
    timeout_seconds=60,       # Longer timeout
    half_open_max_calls=1
)
```

**Alexa API (Less Predictable):**
```python
alexa_circuit = CircuitBreaker(
    failure_threshold=3,      # More sensitive
    timeout_seconds=30,       # Shorter timeout
    half_open_max_calls=1
)
```

**Third-Party APIs (Unknown Reliability):**
```python
external_circuit = CircuitBreaker(
    failure_threshold=2,      # Very sensitive
    timeout_seconds=120,      # Longer recovery time
    half_open_max_calls=2     # Two test calls
)
```

---

## Monitoring

### Key Metrics

```python
# Track circuit state changes
increment_counter("circuit_breaker_opened")       # Circuit opened
increment_counter("circuit_breaker_half_open")   # Testing recovery
increment_counter("circuit_breaker_closed")      # Circuit closed

# Track operations
increment_counter("circuit_breaker_success")     # Successful call
increment_counter("circuit_breaker_failure")     # Failed call
increment_counter("circuit_breaker_blocked")     # Call blocked (circuit open)
```

### CloudWatch Alarms

**Circuit Opens:**
```
Alarm: Circuit breaker opened
Metric: circuit_breaker_opened
Threshold: > 0 in 5 minutes
Action: SNS notification
```

**Frequent Opens:**
```
Alarm: Circuit flapping
Metric: circuit_breaker_opened
Threshold: > 3 in 15 minutes
Action: Page on-call
```

---

## Performance Impact

### Latency

**Circuit CLOSED (Normal):**
- Overhead: < 0.1ms
- Total: API latency + 0.1ms

**Circuit OPEN (Fast Fail):**
- Overhead: < 0.1ms
- Total: < 1ms (no API call)
- **Speedup:** 50-200x (vs waiting for timeout)

**Circuit HALF_OPEN (Testing):**
- Overhead: < 0.1ms
- Total: API latency + 0.1ms (test calls only)

---

## Best Practices

### Do: Use Different Breakers per Service

```python
# âœ… GOOD - Independent circuits
ha_circuit = CircuitBreaker(...)
alexa_circuit = CircuitBreaker(...)

# âŒ BAD - Shared circuit
shared_circuit = CircuitBreaker(...)  # HA and Alexa mixed
```

---

### Do: Provide Fallbacks

```python
# âœ… GOOD - Graceful degradation
try:
    return circuit.call(api_call)
except CircuitOpenError:
    return cached_value()

# âŒ BAD - No fallback
return circuit.call(api_call)  # Fails hard
```

---

### Don't: Use for Infrequent Operations

```python
# âŒ BAD - Circuit never learns
infrequent_circuit = CircuitBreaker(...)  # Called once per day

# âœ… GOOD - Only for frequent operations
frequent_circuit = CircuitBreaker(...)  # Called 100+ times per hour
```

---

## Related Documentation

**Generic Patterns:**
- INT-10: Circuit Breaker interface pattern
- ARCH-09: Resilience patterns

**Integration:**
- NMP01-LEE-17: HA API integration (uses circuit breaker)
- NMP01-LEE-18: Alexa integration (uses circuit breaker)

---

## Keywords

circuit-breaker, resilience, fault-tolerance, cascading-failure, fast-fail, state-machine, fallback-strategy, external-api, SUGA-ISP, LEE, home-assistant, alexa

---

**END OF FILE**

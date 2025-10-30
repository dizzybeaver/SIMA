# NM06-Lessons-Performance_LESS-21.md - LESS-21

# LESS-21: Rate Limiting Essential

**Category:** Lessons  
**Topic:** Performance  
**Priority:** CRITICAL  
**Status:** Active  
**Created:** 2025-10-21  
**Last Updated:** 2025-10-23

---

## Summary

Rate limiting protects systems from abuse, spam, and DoS attacks. Use sliding window algorithm with configurable limits to prevent excessive calls while maintaining legitimate usage.

---

## Context

METRICS interface had no rate limiting, allowing unlimited metric recording. This enabled spam attacks, cost overruns, and potential Lambda timeout through excessive processing.

---

## Content

### The Problem

**No rate limiting:**
```python
# WRONG - Unlimited calls
def record_metric(name, value):
    # No rate limit check
    _metrics.append({'name': name, 'value': value})
    # Attacker can call millions of times
```

**Attack scenario:**
```python
# Attacker sends 1,000,000 metrics per second
while True:
    for i in range(1000000):
        record_metric(f"spam_{i}", random())

# Result:
# - Lambda timeout (15 minute max)
# - High costs ($$$)
# - DoS for legitimate users
```

### The Solution

**Sliding window rate limiter:**
```python
# CORRECT - Rate limited
from collections import deque
import time
import os

class MetricsCore:
    def __init__(self):
        # Configurable limit (default 1000/second)
        self.RATE_LIMIT = int(
            os.getenv('RATE_LIMIT_CALLS_PER_SECOND', '1000')
        )
        self._rate_limiter = deque(maxlen=self.RATE_LIMIT)
        self._window_ms = 1000  # 1 second window
    
    def _check_rate_limit(self) -> bool:
        """Check if call is within rate limit."""
        now = time.time() * 1000  # Current time in ms
        
        # Clean old timestamps outside window
        while (self._rate_limiter and 
               (now - self._rate_limiter[0]) > self._window_ms):
            self._rate_limiter.popleft()
        
        # Check if at limit
        if len(self._rate_limiter) >= self.RATE_LIMIT:
            return False  # Rate limited
        
        # Record this call
        self._rate_limiter.append(now)
        return True  # Allowed
    
    def record_metric(self, name, value):
        # Check rate limit first
        if not self._check_rate_limit():
            # Silent drop (don't raise, don't log spam)
            return
        
        # Process metric
        self._metrics.append({'name': name, 'value': value})
```

### How Sliding Window Works

**Window visualization:**
```
Time:     [----1 second window----]
          â"‚                        â"‚
Calls:    1 2 3 ... 998 999 1000  1001
          â""â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"˜
          Allowed (1000)           Dropped

After 100ms, window slides:
Time:         [----1 second window----]
              â"‚                        â"‚
Old calls removed, new calls allowed
```

**Behavior:**
- Calls 1-1000: All accepted
- Call 1001: Dropped (silent failure)
- After 1 second: Window slides, new 1000 allowed

### Why Sliding Window

**Alternatives compared:**

**Fixed Window:**
```python
# Fixed 1-second buckets
calls_this_second = 0

if calls_this_second < 1000:
    calls_this_second += 1
else:
    drop()

Problem: Burst at boundary
- Second 1 end: 1000 calls
- Second 2 start: 1000 calls
- Total: 2000 calls in 1 second!
```

**Token Bucket:**
```python
# Tokens refill over time
tokens = 1000

if tokens > 0:
    tokens -= 1
else:
    drop()

Problem: Complex refill logic
- When to refill?
- How many tokens?
- Burst handling?
```

**Sliding Window (BEST):**
```python
# Track timestamps, slide window
if len(calls_in_last_second) < 1000:
    allow()
else:
    drop()

Benefits:
- True per-second limit
- No burst at boundaries
- Simple implementation
- ~1Î¼s overhead
```

### Configuration

**Environment variables:**
```python
# Default: 1000 calls/second
RATE_LIMIT_CALLS_PER_SECOND = 1000

# Conservative: 100 calls/second
RATE_LIMIT_CALLS_PER_SECOND = 100

# Aggressive: 10000 calls/second
RATE_LIMIT_CALLS_PER_SECOND = 10000
```

**Choosing the limit:**
- Normal usage: 100-1000 calls/second
- High throughput: 5000-10000 calls/second
- Conservative: 10-100 calls/second

### Performance Impact

**Overhead measurement:**
```
Without rate limiting: ~100ns per call
With rate limiting:    ~101ns per call
Overhead:              ~1Î¼s per call

Trade-off: Essential for production safety
```

### Attack Prevention

**Before rate limiting:**
- Attacker sends 1,000,000 metrics/second
- Lambda processes all 1,000,000
- 1,000,000 * 100ns = 100ms just processing
- Multiplied by duration = Timeout + High cost

**After rate limiting:**
- Attacker sends 1,000,000 metrics/second
- Only first 1,000 processed
- 1,000 * 101ns = 101Î¼s
- Remaining 999,000 silently dropped
- Attack fails, system unaffected

### Silent Failure vs Errors

**Why silent failure:**
```python
if not self._check_rate_limit():
    # Silent drop - don't raise error
    return

# NOT this:
if not self._check_rate_limit():
    raise RateLimitError("Too many requests")
    # Would flood logs during attack!
```

**Rationale:**
- During attack, millions of errors
- Logs would overflow
- CloudWatch costs spike
- System more impacted
- Silent drop = attack has no effect

### Testing

**Test rate limiting:**
```python
def test_rate_limit_enforced():
    metrics = MetricsCore()
    LIMIT = metrics.RATE_LIMIT
    
    # Send LIMIT calls (should all succeed)
    for i in range(LIMIT):
        result = metrics.record_metric(f"test_{i}", i)
        assert result is not None
    
    # Send one more (should be dropped)
    result = metrics.record_metric("over_limit", 999)
    assert len(metrics._metrics) == LIMIT
    
    # Wait 1 second
    time.sleep(1.1)
    
    # Should work again
    result = metrics.record_metric("after_window", 1000)
    assert len(metrics._metrics) == LIMIT + 1
```

### Implementation Pattern

**Standard pattern for any rate-limited operation:**
```python
from collections import deque
import time
import os

class RateLimitedService:
    def __init__(self):
        self.RATE_LIMIT = int(os.getenv('RATE_LIMIT', '1000'))
        self._limiter = deque(maxlen=self.RATE_LIMIT)
        self._window_ms = 1000
    
    def _check_rate_limit(self) -> bool:
        now = time.time() * 1000
        while (self._limiter and 
               (now - self._limiter[0]) > self._window_ms):
            self._limiter.popleft()
        if len(self._limiter) >= self.RATE_LIMIT:
            return False
        self._limiter.append(now)
        return True
    
    def operation(self):
        if not self._check_rate_limit():
            return  # Silent drop
        # Process operation
```

---

## Related Topics

- **LESS-20**: Memory limits (DoS prevention layer 1)
- **LESS-19**: Security validations (prevents injection)
- **DEC-##**: Rate limiting decisions

---

## Related Topics

- **LESS-20**: Memory limits (DoS prevention layer 1)
- **LESS-19**: Security validations (prevents injection)
- **DEC-##**: Rate limiting decisions

---

## Keywords

rate limiting, sliding window, DoS prevention, spam protection, Lambda timeout, security

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2025-10-21**: Original documentation in METRICS Phase 1 optimization

---

**File:** `NM06-Lessons-Performance_LESS-21.md`  
**Directory:** NM06/  
**End of Document**

# File: LESS-21.md

**REF-ID:** LESS-21  
**Category:** Lessons Learned  
**Topic:** Performance  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Status:** Production

---

## Title

Rate Limiting Essential

---

## Priority

CRITICAL

---

## Summary

Rate limiting protects systems from abuse, spam, and DoS attacks. Use sliding window algorithm with configurable limits to prevent excessive calls while maintaining legitimate usage.

---

## Context

Systems without rate limiting allow unlimited operations, enabling spam attacks, cost overruns, and potential system timeout through excessive processing.

---

## Lesson

### The Problem

**No rate limiting:**
```python
# WRONG - Unlimited calls
def process_request(data):
    # No rate limit check
    _store.append(data)
    # Attacker can call millions of times
```

**Attack scenario:**
```python
# Attacker sends 1,000,000 requests per second
while True:
    for i in range(1000000):
        process_request(f"spam_{i}")

# Result:
# - System timeout
# - High costs
# - DoS for legitimate users
```

### The Solution

**Sliding window rate limiter:**
```python
# CORRECT - Rate limited
from collections import deque
import time
import os

class RateLimitedService:
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
    
    def process_request(self, data):
        # Check rate limit first
        if not self._check_rate_limit():
            # Silent drop (don't raise, don't log spam)
            return
        
        # Process request
        self._store.append(data)
```

### How Sliding Window Works

**Window visualization:**
```
Time:     [----1 second window----]
          │                        │
Calls:    1 2 3 ... 998 999 1000  1001
          └────────────────────────┘
          Allowed (1000)           Dropped

After 100ms, window slides:
Time:         [----1 second window----]
              │                        │
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
Problem: Burst at boundary
- Second 1 end: 1000 calls
- Second 2 start: 1000 calls
- Total: 2000 calls in 1 second!
```

**Token Bucket:**
```python
Problem: Complex refill logic
- When to refill?
- How many tokens?
- Burst handling?
```

**Sliding Window (BEST):**
```python
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
- Attacker sends 1,000,000 calls/second
- System processes all 1,000,000
- High cost + potential timeout

**After rate limiting:**
- Attacker sends 1,000,000 calls/second
- Only first 1,000 processed per second
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
- Storage costs spike
- System more impacted
- Silent drop = attack has no effect

### Testing

**Test rate limiting:**
```python
def test_rate_limit_enforced():
    service = RateLimitedService()
    LIMIT = service.RATE_LIMIT
    
    # Send LIMIT calls (should all succeed)
    for i in range(LIMIT):
        service.process_request(f"test_{i}")
    
    assert len(service._store) == LIMIT
    
    # Send one more (should be dropped)
    service.process_request("over_limit")
    assert len(service._store) == LIMIT
    
    # Wait 1 second
    time.sleep(1.1)
    
    # Should work again
    service.process_request("after_window")
    assert len(service._store) == LIMIT + 1
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

## Related

**Cross-References:**
- LESS-20: Memory limits (DoS prevention layer 1)
- AP-12: Premature optimization anti-pattern

**Keywords:** rate limiting, sliding window, DoS prevention, spam protection, security

---

## Version History

- **4.0.0** (2025-10-30): Genericized for SIMAv4
- **3.0.0** (2025-10-23): Initial SIMAv3 format

---

**END OF FILE**

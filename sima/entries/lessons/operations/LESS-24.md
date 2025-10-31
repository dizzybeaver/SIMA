# LESS-24.md

# LESS-24: Rate Limit Tuning Per Operational Characteristics

**Category:** Lessons  
**Topic:** Operations  
**Priority:** HIGH  
**Status:** Active  
**Created:** 2025-10-30  
**Path:** `/sima/entries/lessons/operations/LESS-24.md`

---

## Summary

Different components require different rate limits based on operational characteristics: connection type, component role, operation overhead, and risk profile. One-size-fits-all rate limiting either over-restricts or under-protects.

---

## Pattern

### The Problem

**One-Size-Fits-All Approach:**
```
All components: 500 ops/sec limit

Result:
- Fast components bottlenecked
- Slow components under-protected
- Suboptimal for everyone
```

**Better Approach:**
```
Consider characteristics:
- Connection type (persistent vs request/response)
- Component role (infrastructure vs external)
- Operation overhead (network vs memory)
- Risk profile (external vs internal)

Apply appropriate limits
```

---

## Solution

### Decision Framework

**Four Factors to Consider:**

**1. Connection Type**
- Persistent connections: Lower rate (sustained overhead)
- Request/response: Medium rate (per-call overhead)
- In-memory operations: Higher rate (minimal overhead)

**2. Component Role**
- Infrastructure (critical path): Higher rate (must not bottleneck)
- External services: Lower rate (network latency)
- Internal operations: Higher rate (fast execution)

**3. Operation Overhead**
- Network I/O: Lower rate (accounts for latency)
- Memory operations: Higher rate (nanosecond execution)
- Mixed operations: Medium rate (balance)

**4. Risk Profile**
- Externally triggered: Lower rate (higher attack surface)
- Internally controlled: Higher rate (trusted source)
- Infrastructure critical: Higher rate (system dependency)

### Rate Selection Guidelines

| Characteristics | Rate | Example Components |
|----------------|------|-------------------|
| Persistent + External + Network I/O | 300 ops/sec | WebSocket connections |
| Request/response + External + Network | 500 ops/sec | HTTP client, API calls |
| In-memory + Infrastructure + Internal | 1000 ops/sec | Config, Metrics, Cache |

### Implementation Pattern

```python
from collections import deque
import time

class ComponentCore:
    def __init__(self, rate_limit=1000):
        # Tune based on characteristics
        self._rate_limiter = deque(maxlen=rate_limit)
        self._rate_limit_window_ms = 1000
        self._rate_limited_count = 0
    
    def _check_rate_limit(self) -> bool:
        now = time.time() * 1000
        
        # Remove expired timestamps
        while self._rate_limiter and \
              (now - self._rate_limiter[0]) > self._rate_limit_window_ms:
            self._rate_limiter.popleft()
        
        # Check limit
        if len(self._rate_limiter) >= self._rate_limiter.maxlen:
            self._rate_limited_count += 1
            return False
        
        self._rate_limiter.append(now)
        return True
```

### Real-World Tuning Example

**Component Analysis:**

```
WebSocket Component:
- Connection: Persistent (long-lived)
- Role: External service integration
- Overhead: Network I/O + connection maintenance
- Risk: Externally triggered events
→ Rate: 300 ops/sec (conservative)

HTTP Client Component:
- Connection: Request/response (short-lived)
- Role: External API calls
- Overhead: Network I/O + retry logic
- Risk: Externally controlled destinations
→ Rate: 500 ops/sec (medium)

Cache Component:
- Connection: In-memory (direct access)
- Role: Infrastructure (critical path)
- Overhead: Memory operations (nanoseconds)
- Risk: Internally controlled
→ Rate: 1000 ops/sec (permissive)
```

### Decision Documentation

```python
class Component:
    def __init__(self):
        # Rate limiting: 300 ops/sec
        # Rationale:
        # - Persistent connections (sustained overhead)
        # - Network I/O (latency considerations)
        # - External events (higher risk profile)
        # - Conservative to prevent saturation
        self._rate_limiter = deque(maxlen=300)
```

---

## Tuning Process

### Step 1: Characterize Component

```
Connection type: [Persistent / Request-response / In-memory]
Component role: [Infrastructure / External / Internal]
Operation overhead: [Network / Memory / Mixed]
Risk profile: [External / Internal / Critical]
```

### Step 2: Start Conservative

```
Begin with 300 ops/sec as baseline
Increase based on favorable characteristics
Document reasoning in code
```

### Step 3: Benchmark Verification

```python
# Verify rate doesn't bottleneck legitimate traffic
ops_per_sec = benchmark_throughput()
rate_limit = get_rate_limit()

if ops_per_sec > rate_limit:
    # Rate limit too low - increase
    print(f"Bottleneck: {ops_per_sec} ops/sec > {rate_limit} limit")
```

### Step 4: Monitor in Production

```python
# Track rate limiting impact
metrics = {
    'operations_attempted': count_operations(),
    'operations_rate_limited': count_rejections(),
    'rejection_percentage': calculate_rejection_rate()
}

# Alert if rejections > 5% of legitimate traffic
if metrics['rejection_percentage'] > 0.05:
    alert("High rate limit rejection rate")
```

---

## Common Patterns

### Pattern 1: Infrastructure Components

```python
# Metrics, Config, Cache, Internal utilities
_rate_limiter = deque(maxlen=1000)
# Fast, internal, critical path - high limit
```

### Pattern 2: External Services

```python
# HTTP Client, WebSocket, External APIs
_rate_limiter = deque(maxlen=300-500)
# Network overhead, external risk - medium limit
```

### Pattern 3: Security-Critical

```python
# Authentication, Authorization, Validation
_rate_limiter = deque(maxlen=100-300)
# Attack surface, extra protection - low limit
```

---

## Tuning Trade-offs

| Higher Rate Limits | Lower Rate Limits |
|-------------------|-------------------|
| âœ… Better legitimate throughput | âœ… Better DoS protection |
| âœ… Less false rejections | âœ… Reduced attack surface |
| âŒ Weaker DoS protection | âŒ May bottleneck legitimate use |
| âŒ Larger attack window | âŒ Higher false rejection rate |

### Adjustment Protocol

**When to Increase Rate:**
- Legitimate traffic being rejected (> 5%)
- Benchmark shows headroom
- Component is bottleneck
- Risk profile low

**When to Decrease Rate:**
- DoS attempts observed
- Attack surface increased
- External risk elevated
- Overhead higher than expected

---

## Key Insight

**Rate limiting is not one-size-fits-all. Tune based on operational characteristics, not arbitrary numbers.**

Persistent external connections need conservative limits (300). Fast internal infrastructure can handle high limits (1000). The characteristics dictate the optimal rate.

---

## Best Practices

### Documentation

```python
class Component:
    # Rate limit: 500 ops/sec
    # Why:
    # - Request/response pattern (medium overhead)
    # - External service calls (network latency)
    # - Moderate risk profile (authenticated)
    # - Balances protection and throughput
```

### Monitoring

```python
# Track rate limiting effectiveness
log_metrics({
    'operations_per_second': calculate_rate(),
    'rate_limited_count': self._rate_limited_count,
    'rate_limit': self._rate_limiter.maxlen
})
```

### Review

```
Quarterly review of rate limits:
- Check rejection rates
- Review attack attempts
- Benchmark throughput
- Adjust as needed
```

---

## Related Topics

- **LESS-21**: Rate Limiting Essential (basic protection)
- **Performance Tuning**: Balancing protection and throughput
- **Operations**: Component characterization
- **Security**: DoS protection strategies

---

## Keywords

rate-limiting, dos-protection, performance-tuning, operational-characteristics, connection-types, risk-profiles, throughput-optimization

---

## Version History

- **2025-10-30**: Genericized for SIMAv4 - Removed project-specific details
- **2025-10-25**: Created - Documented rate limiting strategy

---

**File:** `LESS-24.md`  
**Location:** `/sima/entries/lessons/operations/`  
**Status:** Active

---

**End of Document**

# NM06-Lessons-Operations_LESS-24.md

# Rate Limit Tuning Per Operational Characteristics

**REF:** NM06-LESS-24  
**Category:** Lessons  
**Topic:** Operations  
**Priority:** 🟡 HIGH  
**Status:** Active  
**Created:** 2025-10-25  
**Source:** Session 5 rate limiting strategy

---

## Summary

Different components require different rate limits based on operational characteristics: connection type, component role, operation overhead, and risk profile. One-size-fits-all rate limiting either over-restricts or under-protects.

---

## Context

**Universal Pattern:**
Rate limiting protects against DoS but can also bottleneck legitimate traffic. The optimal rate depends on the component's operational characteristics, not arbitrary numbers.

**Why This Matters:**
Incorrect rate limits either allow attacks or degrade legitimate performance. Tuning based on characteristics optimizes both protection and throughput.

---

## Content

### Decision Framework

**Four Factors to Consider:**

1. **Connection Type**
   - Persistent connections: Lower rate (sustained overhead)
   - Request/response: Medium rate (per-call overhead)
   - In-memory operations: Higher rate (minimal overhead)

2. **Component Role**
   - Infrastructure (critical path): Higher rate (must not bottleneck)
   - External services: Lower rate (network latency)
   - Internal operations: Higher rate (fast execution)

3. **Operation Overhead**
   - Network I/O: Lower rate (accounts for latency)
   - Memory operations: Higher rate (nanosecond execution)
   - Mixed operations: Medium rate (balance)

4. **Risk Profile**
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

class ComponentCore:
    def __init__(self):
        # Tune based on characteristics:
        # - WebSocket (persistent, external): 300
        # - HTTP (network, mixed): 500
        # - Cache (memory, internal): 1000
        self._rate_limiter = deque(maxlen=1000)  # Adjust maxlen
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
WebSocket Interface:
- Connection: Persistent (long-lived)
- Role: External service integration
- Overhead: Network I/O + connection maintenance
- Risk: Externally triggered events
→ Rate: 300 ops/sec (conservative)

HTTP Client Interface:
- Connection: Request/response (short-lived)
- Role: External API calls
- Overhead: Network I/O + retry logic
- Risk: Externally controlled destinations
→ Rate: 500 ops/sec (medium)

Cache Interface:
- Connection: In-memory (direct access)
- Role: Infrastructure (critical path)
- Overhead: Memory operations (nanoseconds)
- Risk: Internally controlled
→ Rate: 1000 ops/sec (permissive)
```

### Decision Documentation

```python
class WebSocketCore:
    def __init__(self):
        # Rate limiting: 300 ops/sec
        # Rationale:
        # - Persistent connections (sustained overhead)
        # - Network I/O (latency considerations)
        # - External events (higher risk profile)
        # - Conservative to prevent connection saturation
        self._rate_limiter = deque(maxlen=300)
```

### Tuning Process

**Step 1: Characterize Component**
```
Connection type: [Persistent / Request-response / In-memory]
Component role: [Infrastructure / External / Internal]
Operation overhead: [Network / Memory / Mixed]
Risk profile: [External / Internal / Critical]
```

**Step 2: Start Conservative**
```
Begin with 300 ops/sec as baseline
Increase based on favorable characteristics
Document reasoning in code comments
```

**Step 3: Benchmark Verification**
```python
# Verify rate doesn't bottleneck legitimate traffic
ops_per_sec = benchmark_throughput()
rate_limit = get_rate_limit()

if ops_per_sec > rate_limit:
    # Rate limit too low - increase
    pass
```

**Step 4: Monitor in Production**
```python
# Track rate limiting impact
metrics = {
    'operations_attempted': count_operations(),
    'operations_rate_limited': count_rejections(),
    'rejection_percentage': calculate_rejection_rate()
}

# Alert if rejections > 5% of legitimate traffic
```

### Common Patterns

**Pattern 1: Infrastructure Components**
```python
# Metrics, Config, Cache, Initialization, Utility
_rate_limiter = deque(maxlen=1000)
# Fast, internal, critical path - high limit
```

**Pattern 2: External Services**
```python
# HTTP Client, WebSocket
_rate_limiter = deque(maxlen=300-500)
# Network overhead, external risk - medium limit
```

**Pattern 3: Security-Critical**
```python
# Security validation, Authentication
_rate_limiter = deque(maxlen=100-300)
# Attack surface, need extra protection - low limit
```

### Tuning Trade-offs

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

### Key Insight

**Rate limiting is not one-size-fits-all. Tune based on operational characteristics, not arbitrary numbers.**

Persistent external connections need conservative limits (300). Fast internal infrastructure can handle high limits (1000). The characteristics dictate the optimal rate.

---

## Related Topics

- **LESS-21**: Rate limiting essential for DoS protection
- **LESS-37**: Rate limiting becomes muscle memory
- **Performance**: Balancing protection and throughput
- **Operations**: Component characterization

---

## Keywords

rate-limiting, dos-protection, performance-tuning, operational-characteristics, connection-types, risk-profiles, throughput-optimization

---

## Version History

- **2025-10-25**: Created - Genericized from Session 5 rate limiting strategy
- **Source**: 4 interfaces optimized with tuned rates (300-1000 ops/sec)

---

**File:** `NM06-Lessons-Operations_LESS-24.md`  
**Topic:** Operations  
**Priority:** HIGH (optimizes protection and performance)

---

**End of Document**

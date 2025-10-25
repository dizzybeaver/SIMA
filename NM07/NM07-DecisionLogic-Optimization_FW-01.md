# NM07-DecisionLogic-Optimization_FW-01.md - FW-01

# FW-01: Cache vs Compute Trade-off Framework

**Category:** Decision Logic
**Topic:** Optimization
**Priority:** Framework
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Mathematical framework for cache vs compute performance trade-offs - calculating expected benefit based on computation cost, cache overhead, and hit rates.

---

## Context

Caching introduces overhead (lookup time, memory). This framework quantifies when caching provides net benefit based on measurable parameters.

---

## Content

### Framework Formula

```
Decision: Should I cache or recompute?

Calculate:
- Computation cost: C ms
- Cache lookup cost: L ms (typically ~0.1ms)
- Cache hit rate: H (0-1)
- Average benefit: (C - L) * H

If (C - L) * H > 1ms → Cache
Else → Recompute
```

### Example Calculations

**Example 1: Clear Win for Caching**
```
Scenario: Expensive API call
- Computation: C = 50ms
- Lookup: L = 0.1ms
- Hit rate: H = 80% (0.8)
- Benefit: (50 - 0.1) * 0.8 = 39.9ms

Decision: CACHE (huge benefit)
```

**Example 2: Borderline Case**
```
Scenario: Simple calculation
- Computation: C = 2ms
- Lookup: L = 0.1ms
- Hit rate: H = 50% (0.5)
- Benefit: (2 - 0.1) * 0.5 = 0.95ms

Decision: BORDERLINE (context-dependent)
- If memory constrained → Don't cache
- If memory available → Cache
```

**Example 3: Clear Loss**
```
Scenario: Very fast operation
- Computation: C = 0.5ms
- Lookup: L = 0.1ms
- Hit rate: H = 90% (0.9)
- Benefit: (0.5 - 0.1) * 0.9 = 0.36ms

Decision: DON'T CACHE (overhead > benefit)
```

### Hit Rate Estimation

**How to estimate cache hit rate:**

**1. Access Pattern Analysis:**
```python
# Track access patterns
access_log = {}
for key in requests:
    access_log[key] = access_log.get(key, 0) + 1

# Calculate hit rate potential
total_accesses = sum(access_log.values())
repeat_accesses = sum(v - 1 for v in access_log.values() if v > 1)
estimated_hit_rate = repeat_accesses / total_accesses
```

**2. Temporal Locality:**
```
High hit rate (70-90%):
- Same data requested within TTL window
- User session data, config values

Medium hit rate (40-70%):
- Popular but rotating data
- API responses with pagination

Low hit rate (10-40%):
- Mostly unique requests
- User-specific one-time data
```

### Extended Formula with Memory Cost

```
Total benefit calculation including memory:

Benefit = (C - L) * H - M

Where:
- C = Computation cost (ms)
- L = Lookup cost (ms)
- H = Hit rate (0-1)
- M = Memory cost (amortized ms per operation)

Memory cost (M) = (memory_bytes * memory_cost_per_mb) / operations_per_ttl

Example:
- Cache entry: 10KB (0.01MB)
- Memory cost: 0.01ms per MB per operation
- TTL: 300s
- Operations per TTL: 1000
- M = (0.01 * 0.01) / 1000 = 0.0000001ms ≈ negligible

For most use cases, M ≈ 0, so simplified formula sufficient.
```

### Decision Matrix

| C (compute) | L (lookup) | H (hit rate) | Benefit | Decision |
|-------------|-----------|--------------|---------|----------|
| 50ms | 0.1ms | 80% | 39.9ms | Cache |
| 50ms | 0.1ms | 50% | 25.0ms | Cache |
| 50ms | 0.1ms | 20% | 10.0ms | Cache |
| 10ms | 0.1ms | 80% | 7.9ms | Cache |
| 10ms | 0.1ms | 50% | 5.0ms | Cache |
| 5ms | 0.1ms | 80% | 3.9ms | Cache |
| 2ms | 0.1ms | 80% | 1.5ms | Cache |
| 2ms | 0.1ms | 50% | 0.95ms | Borderline |
| 1ms | 0.1ms | 80% | 0.7ms | Don't cache |
| 0.5ms | 0.1ms | 90% | 0.36ms | Don't cache |

### Real-World Usage Pattern

**User Query:** "Is it worth caching this 10ms operation with 60% hit rate?"

**Calculation:**
```
C = 10ms
L = 0.1ms
H = 0.6
Benefit = (10 - 0.1) * 0.6 = 5.94ms

Decision: YES, cache (5.94ms > 1ms threshold)
```

**Response:** "Yes! Expected benefit: 5.94ms per operation. Cache with appropriate TTL."

### Framework Application

```python
def should_cache(compute_ms: float, hit_rate: float, lookup_ms: float = 0.1) -> bool:
    """Determine if caching is beneficial."""
    benefit = (compute_ms - lookup_ms) * hit_rate
    return benefit > 1.0  # 1ms threshold

# Example usage
if should_cache(compute_ms=50, hit_rate=0.8):
    # Implement caching
    cached = gateway.cache_get(key)
    if cached is None:
        cached = expensive_compute()
        gateway.cache_set(key, cached, ttl=300)
    return cached
else:
    # Just compute
    return compute()
```

---

## Related Topics

- **DT-04**: Should this be cached (decision tree)
- **DT-07**: Should I optimize
- **DEC-09**: Cache design decisions
- **LESS-02**: Measure first, don't guess

---

## Keywords

cache vs compute, performance trade-off, hit rate, caching benefit, optimization framework

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-Optimization_FW-01.md`
**End of Document**

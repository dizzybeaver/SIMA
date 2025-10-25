# NM07-DecisionLogic-FeatureAddition_DT-04.md - DT-04

# DT-04: Should This Be Cached?

**Category:** Decision Logic
**Topic:** Feature Addition
**Priority:** High
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Decision tree for cache vs compute trade-offs - determining when caching provides benefit based on computation cost, access frequency, data volatility, and size constraints.

---

## Context

Lambda has 128MB memory limit and single-threaded execution. Caching decisions must balance performance gains against memory constraints and data staleness.

---

## Content

### Decision Tree

```
START: Considering caching data X
│
├─ Q: Is X expensive to compute/fetch?
│  ├─ NO (<10ms) → Don't cache
│  │      Rationale: Cache overhead > computation
│  │      → END
│  │
│  └─ YES (>10ms) → Continue
│
├─ Q: Is X accessed frequently?
│  ├─ NO (once per request) → Don't cache
│  │      Rationale: No reuse benefit
│  │      → END
│  │
│  └─ YES (multiple times) → Continue
│
├─ Q: Does X change frequently?
│  ├─ YES (every request) → Don't cache
│  │      Rationale: Always stale
│  │      → END
│  │
│  └─ NO (stable) → Continue
│
├─ Q: Is X large (>1MB)?
│  ├─ YES → Cache selectively
│  │      Rationale: Memory constraint (128MB)
│  │      Decision: Cache small subset or summary
│  │      → END
│  │
│  └─ NO (<1MB) → Continue
│
└─ Decision: Cache X with appropriate TTL
   │
   ├─ Q: How often does X change?
   │  ├─ Fast (seconds) → TTL: 60s
   │  ├─ Medium (minutes) → TTL: 300s
   │  ├─ Slow (hours) → TTL: 600s
   │  └─ Never → TTL: None (manual invalidation)
   │
   └─ Implementation:
      cached = gateway.cache_get("X")
      if cached is None:
          cached = compute_X()
          gateway.cache_set("X", cached, ttl=TTL)
      return cached
      → END
```

### Caching Decision Matrix

| Data Type | Cache? | TTL | Reason |
|-----------|--------|-----|--------|
| Config values | YES | 300s | Slow-changing, frequently accessed |
| API responses (GET) | YES | 60-300s | Expensive, may change |
| Computed metrics | YES | 60s | Expensive, fast-changing |
| User session data | YES | 600s | Medium-cost, stable |
| Request ID | NO | - | Used once |
| Current timestamp | NO | - | Always changing |
| Large files (>1MB) | NO | - | Memory constraint |

### TTL Selection Guide

**Fast-Changing Data (seconds):**
- TTL: 60s
- Examples: API metrics, rate limits, current status
- Trade-off: Balance freshness vs request rate

**Medium-Changing Data (minutes):**
- TTL: 300s (5 minutes)
- Examples: Config values, user preferences, feature flags
- Trade-off: Standard balance

**Slow-Changing Data (hours):**
- TTL: 600s (10 minutes)
- Examples: Static configuration, system constants
- Trade-off: Prefer freshness over memory

**Never-Changing Data:**
- TTL: None (manual invalidation)
- Examples: Build constants, system IDs
- Trade-off: Cache until explicit clear

### Implementation Pattern

```python
def get_expensive_data(key: str):
    """Example caching pattern."""
    cache_key = f"expensive:{key}"
    
    # Try cache first
    cached = gateway.cache_get(cache_key)
    if cached is not None:
        return cached
    
    # Compute if not cached
    result = expensive_computation(key)
    
    # Cache with appropriate TTL
    gateway.cache_set(cache_key, result, ttl=300)
    
    return result
```

### Real-World Examples

**Should Cache - API Responses:**
```python
# Cost: 50ms to fetch from API
# Frequency: Used 5 times per request
# Volatility: Updates every 5 minutes
# Size: 10KB
# Decision: CACHE with TTL=300s
# Benefit: Saves 200ms per request (4 cache hits)

cached = gateway.cache_get("api_data")
if cached is None:
    cached = api_fetch_data()
    gateway.cache_set("api_data", cached, ttl=300)
```

**Should NOT Cache - Request IDs:**
```python
# Cost: 2ms to generate UUID
# Frequency: Used once per request
# Volatility: Always unique
# Decision: DON'T CACHE
# Reason: No reuse, cache overhead > generation

request_id = generate_uuid()  # Just compute
```

**Should NOT Cache - Large Files:**
```python
# Cost: 100ms to fetch
# Frequency: Used multiple times
# Volatility: Stable
# Size: 5MB
# Decision: DON'T CACHE FULL FILE
# Reason: 128MB Lambda limit, too large
# Alternative: Cache metadata or summary

metadata = gateway.cache_get("file_metadata")
if metadata is None:
    file_data = fetch_large_file()
    metadata = extract_metadata(file_data)
    gateway.cache_set("file_metadata", metadata, ttl=600)
```

### Real-World Usage Pattern

**User Query:** "Should I cache API responses?"

**Search Terms:** "should cache decision API"

**Decision Flow:**
1. Expensive? YES (>10ms to fetch)
2. Frequent? YES (accessed multiple times)
3. Stable? YES (updates every few minutes)
4. Large? NO (<1MB)
5. **Decision:** YES, cache with TTL 60-300s
6. **Response:** "Yes if: >10ms to fetch + accessed multiple times + relatively stable. Use TTL 60-300s."

---

## Related Topics

- **DEC-09**: Cache design decisions
- **AP-06**: Cache everything (anti-pattern)
- **AP-12**: Performance without measurement (anti-pattern)
- **FW-01**: Cache vs compute framework
- **LESS-02**: Measure first, optimize second

---

## Keywords

caching, performance, decision-tree, optimization, TTL, cache strategy, memory constraints

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-FeatureAddition_DT-04.md`
**End of Document**

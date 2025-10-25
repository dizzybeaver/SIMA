# NM06-Lessons-Operations_LESS-10.md - LESS-10

# LESS-10: Cold Start Monitoring

**Category:** Lessons  
**Topic:** Operations  
**Priority:** MEDIUM  
**Status:** Active  
**Created:** 2025-10-23  
**Last Updated:** 2025-10-23

---

## Summary

Monitor cold starts separately from warm starts for accurate performance data. Detailed timing logs revealed the 535ms sentinel leak penalty - without monitoring, we'd never have found it.

---

## Context

Cold starts were much slower than expected (855ms vs 320ms), but we didn't know this until we added monitoring. The sentinel leak bug was only discovered through detailed timing logs.

---

## Content

### The Discovery Timeline

```
Day 1: Added cold start monitoring
       Log: "Cold start: 855ms" (Expected: 320ms)

Day 2: Added detailed phase timing
       Logs: Import 45ms, Gateway 120ms, Config 155ms, Cache 535ms ← FOUND IT!

Day 3: Added cache operation timing
       Investigation revealed sentinel not being sanitized

Day 4: Fixed sentinel sanitization
       Log: "Cold start: 320ms" ✅
```

**Time to find:**
- With monitoring: 4 days
- Without monitoring: Unknown (might never find it)

### What to Monitor

**1. Cold Start Metrics (Essential)**
- Total cold start time (should be < 500ms)
- Import time (should be < 100ms)
- Gateway initialization (should be < 50ms)
- Configuration load (should be < 100ms)

**2. Request Metrics (Essential)**
- Total request time (should be < 200ms)
- Cache hit/miss rate (should be > 80% hits)
- Error rate (should be < 1%)

**3. Performance Baselines**
```python
BASELINES = {
    'cold_start_ms': {
        'target': 320,
        'acceptable': 500,
        'critical': 1000,
    },
    'request_ms': {
        'target': 119,
        'acceptable': 200,
        'critical': 500,
    },
}
```

### Implementation

```python
import time

def lambda_handler(event, context):
    start = time.time()
    
    # Detect cold start
    is_cold_start = not hasattr(lambda_handler, '_initialized')
    
    if is_cold_start:
        init_metrics = _measure_cold_start()
        lambda_handler._initialized = True
    
    # Measure request
    request_metrics = _measure_request(event)
    
    # Log metrics
    _log_metrics({
        'is_cold_start': is_cold_start,
        'total_time_ms': (time.time() - start) * 1000,
        **(init_metrics if is_cold_start else {}),
        **request_metrics,
    })
    
    return {'statusCode': 200, 'body': 'OK'}
```

### CloudWatch Integration

```sql
-- Find slow cold starts
fields @timestamp, metrics.cold_start_total_ms
| filter level = "METRIC" and metrics.is_cold_start = true
| filter metrics.cold_start_total_ms > 500
| sort @timestamp desc

-- Average request time by hour
fields @timestamp, metrics.process_time_ms
| filter level = "METRIC"
| stats avg(metrics.process_time_ms) as avg_time by bin(@timestamp, 1h)
```

### Best Practices

**1. Start with Timing**
```python
start = time.time()
result = operation()
print(f"{operation_name}: {(time.time() - start)*1000:.1f}ms")
```

**2. Establish Baselines Early**
```python
print("Baseline cold start: 320ms")
print("Optimized cold start: 285ms")
print("Improvement: 35ms (11%)")
```

**3. Monitor What Matters**
- ✅ Cold start time
- ✅ Request latency
- ✅ Error rate
- ✅ Cache hit rate

**4. Make Monitoring Actionable**
```python
# ✅ Actionable
print(f"Cache get took {elapsed:.1f}ms (expected < 5ms)")
```

### Key Insights

**1. You Can't Fix What You Can't See**
- No monitoring → No visibility → No improvement

**2. Monitoring Enables Data-Driven Decisions**
- Without data: "Maybe imports are slow?"
- With data: "Cache operations take 535ms"

**3. Simple Monitoring Better Than No Monitoring**
```python
# Simple but effective
print(f"Cold start: {elapsed_ms:.1f}ms")
```

---

## Related Topics

- **BUG-01**: Sentinel leak (found via monitoring)
- **LESS-02**: Measure don't guess (foundation)
- **PATH-01**: Cold start pathway
- **ARCH-07**: LMMS system

---

## Keywords

cold start monitoring, performance tracking, metrics, timing logs, observability

---

## Version History

- **2025-10-23**: Created - Migrated to SIMA v3 individual file format
- **2025-10-20**: Original documentation in NM06-LESSONS-Deployment_and_Operations.md

---

**File:** `NM06-Lessons-Operations_LESS-10.md`  
**Directory:** NM06/  
**End of Document**

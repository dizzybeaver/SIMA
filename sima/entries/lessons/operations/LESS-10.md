# LESS-10.md

# LESS-10: Cold Start Monitoring Reveals Performance Issues

**Category:** Lessons  
**Topic:** Operations  
**Priority:** MEDIUM  
**Status:** Active  
**Created:** 2025-10-30  
**Path:** `/sima/entries/lessons/operations/LESS-10.md`

---

## Summary

Monitor cold starts separately from warm starts for accurate performance data. Detailed timing logs reveal hidden performance penalties that would otherwise remain undiscovered.

---

## Pattern

### The Problem

**Without Monitoring:**
```
Application feels slow sometimes
No data on why
Can't identify root cause
Random optimization attempts
```

**Example Discovery:**
```
Day 1: Added cold start monitoring
       "Cold start: 855ms" (Expected: 320ms)

Day 2: Added detailed phase timing
       Logs: Import 45ms, Gateway 120ms, Config 155ms, Cache 535ms ← FOUND IT!

Day 3: Added operation-level timing
       Investigation revealed root cause

Day 4: Fixed issue
       "Cold start: 320ms" âœ…
```

**Time to Find:**
- With monitoring: 4 days
- Without monitoring: Unknown (might never find it)

---

## Solution

### What to Monitor

**1. Cold Start Metrics (Essential)**
```python
{
    'total_cold_start_ms': 320,
    'import_time_ms': 45,
    'initialization_ms': 120,
    'configuration_load_ms': 155
}
```

**Acceptable Baselines:**
- Total cold start: < 500ms
- Import time: < 100ms
- Initialization: < 50ms
- Configuration: < 100ms

**2. Request Metrics (Essential)**
```python
{
    'total_request_ms': 119,
    'cache_hit_rate': 0.87,
    'error_rate': 0.003
}
```

**Acceptable Baselines:**
- Request time: < 200ms
- Cache hit rate: > 80%
- Error rate: < 1%

**3. Performance Baselines**
```python
BASELINES = {
    'cold_start_ms': {
        'target': 300,
        'acceptable': 500,
        'critical': 1000,
    },
    'request_ms': {
        'target': 100,
        'acceptable': 200,
        'critical': 500,
    },
}
```

### Implementation

**Basic Monitoring:**
```python
import time

def handler(event, context):
    start = time.time()
    
    # Detect cold start
    is_cold_start = not hasattr(handler, '_initialized')
    
    if is_cold_start:
        init_metrics = _measure_initialization()
        handler._initialized = True
    
    # Measure request
    request_metrics = _measure_request(event)
    
    # Log metrics
    _log_metrics({
        'is_cold_start': is_cold_start,
        'total_time_ms': (time.time() - start) * 1000,
        **(init_metrics if is_cold_start else {}),
        **request_metrics,
    })
    
    return response
```

**Detailed Phase Timing:**
```python
def _measure_initialization():
    metrics = {}
    
    start = time.time()
    import_modules()
    metrics['import_ms'] = (time.time() - start) * 1000
    
    start = time.time()
    initialize_gateway()
    metrics['gateway_init_ms'] = (time.time() - start) * 1000
    
    start = time.time()
    load_configuration()
    metrics['config_load_ms'] = (time.time() - start) * 1000
    
    return metrics
```

### Monitoring Best Practices

**1. Start with Simple Timing**
```python
start = time.time()
result = operation()
elapsed_ms = (time.time() - start) * 1000
print(f"{operation_name}: {elapsed_ms:.1f}ms")
```

**2. Establish Baselines Early**
```python
print("Baseline cold start: 320ms")
print("After optimization: 285ms")
print("Improvement: 35ms (11%)")
```

**3. Monitor What Matters**
- âœ… Cold start time
- âœ… Request latency
- âœ… Error rate
- âœ… Cache hit rate

**4. Make Monitoring Actionable**
```python
# âœ… Actionable
print(f"Cache operation took {elapsed:.1f}ms (expected < 5ms)")

# âŒ Not actionable
print(f"Cache operation took {elapsed:.1f}ms")
```

### Log Queries for Analysis

**Find Slow Cold Starts:**
```sql
fields timestamp, metrics.cold_start_total_ms
| filter level = "METRIC" and metrics.is_cold_start = true
| filter metrics.cold_start_total_ms > 500
| sort timestamp desc
```

**Average Request Time:**
```sql
fields timestamp, metrics.process_time_ms
| filter level = "METRIC"
| stats avg(metrics.process_time_ms) as avg_time by bin(timestamp, 1h)
```

---

## Impact

### Performance Visibility

**Before Monitoring:**
- No visibility into performance
- Random optimization attempts
- Unknown baselines
- Reactive problem solving

**After Monitoring:**
- Clear performance data
- Targeted optimizations
- Established baselines
- Proactive improvements

### Real Discovery Example

**535ms Performance Penalty Found:**
```
Without monitoring: Unknown issue causing slowness
With monitoring: Cache operation taking 535ms
Investigation: Root cause identified
Fix applied: 535ms penalty eliminated
Result: 62% faster cold starts
```

---

## Key Insights

**1. You Can't Fix What You Can't See**
- No monitoring → No visibility → No improvement
- Simple monitoring better than none
- Data enables targeted action

**2. Monitoring Enables Data-Driven Decisions**
```
Without data: "Maybe imports are slow?"
With data: "Cache operations take 535ms"
```

**3. Small Investment, Big Returns**
```
Time to add monitoring: 1-2 hours
Value: Continuous visibility forever
Cost: Essentially free (low overhead)
Benefit: Find and fix issues quickly
```

**4. Progressive Refinement**
```
Start: Basic timing (total time)
Add: Phase-level breakdown
Add: Operation-level detail
Result: Pinpoint performance issues
```

---

## Best Practices

**Session Start:**
- Add basic monitoring first
- Establish baselines
- Document expected performance

**During Development:**
- Monitor new features
- Compare to baselines
- Catch regressions early

**In Production:**
- Continuous monitoring
- Alert on anomalies
- Track trends over time

**When Investigating:**
- Add detailed timing
- Isolate problematic phase
- Drill down to root cause

---

## Anti-Patterns to Avoid

**âŒ No Monitoring**
- Guessing at performance
- Can't verify improvements
- Miss slow degradation

**âŒ Monitoring Without Baselines**
- "Cold start: 500ms" (is that good?)
- No context for comparison
- Can't measure improvement

**âŒ Too Detailed Too Soon**
- Monitoring everything at once
- Overhead impact
- Analysis paralysis

**âŒ Non-Actionable Metrics**
- Just logging numbers
- No thresholds
- No alerts

---

## Related Topics

- **Performance Measurement**: LESS-02 (Measure Don't Guess)
- **Baseline Establishment**: Setting acceptable targets
- **Cold Start Optimization**: Reducing initialization time
- **Observability**: System visibility patterns

---

## Keywords

cold-start-monitoring, performance-tracking, metrics, timing-logs, observability, baseline-establishment, data-driven-optimization

---

## Version History

- **2025-10-30**: Genericized for SIMAv4 - Removed project-specific details
- **2025-10-23**: Created - Migrated from project documentation

---

**File:** `LESS-10.md`  
**Location:** `/sima/entries/lessons/operations/`  
**Status:** Active

---

**End of Document**

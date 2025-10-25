# NM01-Architecture-InterfacesCore_INT-04.md - INT-04

# INT-04: METRICS Interface

**Category:** NM01 - Architecture  
**Topic:** Interfaces-Core  
**Priority:** 🟢 MEDIUM  
**Status:** Active  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-24

---

## Summary

Performance metrics and counters interface for tracking operation timing, error rates, cache hit/miss ratios, and other system observability metrics.

---

## Context

The METRICS interface provides performance tracking and operational metrics that are crucial for understanding system behavior, identifying bottlenecks, and optimizing performance.

**Why it exists:** "Measure, don't guess" (LESS-02). Without metrics, optimization is guesswork. This interface enables data-driven performance improvements.

---

## Content

### Overview

```
Router: interface_metrics.py
Core: metrics_core.py
Purpose: Performance metrics and counters
Pattern: Dictionary-based dispatch
State: In-memory metric counters
Dependency Layer: Layer 1 (Core Utilities)
```

### Operations (8 total)

```
├─ record: Record a metric value
├─ increment: Increment a counter
├─ get_stats: Get metrics statistics
├─ record_operation: Record operation metric with timing
├─ record_error: Record error metric
├─ record_cache: Record cache hit/miss
├─ record_api: Record API call metric
└─ clear_metrics: Clear all metrics (testing)
```

### Gateway Wrappers

```python
# Core operations
record_metric(name: str, value: float, **kwargs) -> None
increment_counter(name: str, value: int = 1, **kwargs) -> None
get_metrics_stats() -> Dict

# Specialized metrics
record_operation_metric(operation: str, duration_ms: float, success: bool, **kwargs) -> None
record_error_metric(error_type: str, **kwargs) -> None
record_cache_metric(operation: str, hit: bool, **kwargs) -> None
record_api_metric(api: str, duration_ms: float, status_code: int, **kwargs) -> None
```

### Dependencies

```
Uses: LOGGING (for metric errors)
Used by: CACHE, HTTP_CLIENT, CIRCUIT_BREAKER, WEBSOCKET
```

### Metric Types

```
Counters  → Incrementing values (requests, errors, cache_hits)
Gauges    → Point-in-time values (memory_usage, active_connections)
Timers    → Duration measurements (operation_duration_ms)
```

### State Storage

```python
# In-memory metric storage
_METRICS_STORE = {
    'counters': {},   # Counter metrics
    'gauges': {},     # Gauge metrics
    'timers': []      # Timer measurements
}
```

### Design Decisions

```
- Lightweight metrics (no external services due to free tier)
- In-memory only (no persistence)
- CloudWatch logs integration (parse from logs)
- Automatic timing for operations
```

### Usage Example

```python
from gateway import (
    record_metric,
    increment_counter,
    record_operation_metric,
    record_cache_metric,
    get_metrics_stats
)
import time

# Increment counter
increment_counter('api.requests')
increment_counter('api.requests', value=5)  # Increment by 5

# Record gauge
record_metric('memory.usage_mb', 245.6)

# Record operation timing
start = time.time()
result = expensive_operation()
duration_ms = (time.time() - start) * 1000
record_operation_metric('expensive_op', duration_ms, success=True)

# Record cache metric
hit = cache_get('key') is not None
record_cache_metric('user_cache', hit=hit)

# Get all stats
stats = get_metrics_stats()
print(f"Total requests: {stats['counters']['api.requests']}")
```

---

## Related Topics

- **LESS-02**: Measure don't guess - The philosophy behind metrics
- **PATH-05**: Metrics collection pathway
- **INT-01**: CACHE - Uses metrics for hit/miss tracking
- **INT-08**: HTTP_CLIENT - Uses metrics for API call tracking

---

## Keywords

metrics, performance, counters, gauges, timers, observability, monitoring

---

## Version History

- **2025-10-24**: Atomized from monolithic file (SIMA v3 migration)
- **2025-10-20**: Original content in IMPLEMENTATION_Core.md

---

**File:** `NM01-Architecture-InterfacesCore_INT-04.md`  
**End of Document**

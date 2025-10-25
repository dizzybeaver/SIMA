# NM06-Lessons-Performance_LESS-20.md - LESS-20

# LESS-20: Memory Limits Prevent DoS

**Category:** Lessons  
**Topic:** Performance  
**Priority:** CRITICAL  
**Status:** Active  
**Created:** 2025-10-21  
**Last Updated:** 2025-10-23

---

## Summary

Unbounded data structures enable DoS attacks and OOM crashes. Always enforce memory limits with FIFO eviction to bound memory growth and protect system availability.

---

## Context

METRICS interface had unbounded list growth, allowing malicious actors to exhaust Lambda memory through metric spam. Memory limits with FIFO eviction prevent this attack vector.

---

## Content

### The Problem

**Unbounded memory growth:**
```python
# WRONG - No memory limits
class MetricsCore:
    def __init__(self):
        self._metrics = []  # Unbounded!
    
    def record_metric(self, name, value):
        self._metrics.append({
            'name': name,
            'value': value,
            'timestamp': time.time()
        })
        # No limit - grows forever!
```

**Attack scenario:**
```python
# Attacker sends 100,000 metrics
for i in range(100000):
    record_metric(f"spam_{i}", random())

# Result:
# - 100,000 metrics * ~200 bytes = 20MB
# - Lambda OOM crash
# - Denial of service
```

### The Solution

**Memory limits with FIFO eviction:**
```python
# CORRECT - Bounded with FIFO
import os

class MetricsCore:
    def __init__(self):
        # Enforce maximum size
        self.MAX_METRICS = int(os.getenv('MAX_METRICS', '10000'))
        self._metrics = []
    
    def record_metric(self, name, value):
        # Add new metric
        self._metrics.append({
            'name': name,
            'value': value,
            'timestamp': time.time()
        })
        
        # Enforce limit with FIFO eviction
        if len(self._metrics) > self.MAX_METRICS:
            self._metrics.pop(0)  # Remove oldest
```

**Benefits:**
- Bounded memory usage (10,000 * 200 bytes = 2MB max)
- DoS attack prevented
- OOM crash prevented
- Automatic cleanup (FIFO)

### Why FIFO Over LRU

**FIFO (First-In-First-Out):**
```python
# Remove oldest entry
self._metrics.pop(0)

Benefits:
- O(1) removal (constant time)
- Simple implementation
- Predictable behavior
- No metadata needed
```

**LRU (Least Recently Used):**
```python
# Would need access tracking
Benefits:
- Better for cache (keeps hot items)

Drawbacks:
- O(log n) or O(1) with complex data structures
- Requires metadata (last access time)
- More complex implementation
- Not needed for metrics (time-ordered data)
```

**For metrics, FIFO is better:**
- Metrics are time-ordered
- Older metrics naturally less relevant
- Simpler and faster
- No access tracking needed

### Configurable Limits

**Environment variable control:**
```python
MAX_METRICS = int(os.getenv('MAX_METRICS', '10000'))

# Small Lambda (128MB): MAX_METRICS=1000
# Medium Lambda (512MB): MAX_METRICS=10000  
# Large Lambda (1024MB): MAX_METRICS=50000
```

### Memory Calculation

**Per-metric memory:**
```python
{
    'name': 'metric_name',      # ~50 bytes
    'value': 123.45,             # ~24 bytes
    'timestamp': 1234567890.123, # ~24 bytes
    'dimensions': {...}          # ~100 bytes
}
# Total: ~200 bytes per metric
```

**Total memory:**
```
10,000 metrics * 200 bytes = 2MB
50,000 metrics * 200 bytes = 10MB
```

### Attack Prevention

**Before limits:**
- Attacker sends 500,000 metrics
- 500,000 * 200 bytes = 100MB
- Lambda OOM crash
- Total denial of service

**After limits:**
- Attacker sends 500,000 metrics
- Only last 10,000 kept
- 10,000 * 200 bytes = 2MB
- System continues functioning
- Attack fails

### Implementation Pattern

**Standard pattern for all unbounded structures:**
```python
# 1. Define maximum size
MAX_SIZE = int(os.getenv('MAX_COLLECTION_SIZE', '1000'))

# 2. Check size before adding
if len(collection) >= MAX_SIZE:
    collection.pop(0)  # FIFO eviction

# 3. Then add new item
collection.append(new_item)
```

### Testing

**Test memory limits:**
```python
def test_memory_limit_enforced():
    metrics = MetricsCore()
    MAX = metrics.MAX_METRICS
    
    # Add MAX + 1000 metrics
    for i in range(MAX + 1000):
        metrics.record_metric(f"test_{i}", i)
    
    # Should only have MAX metrics
    assert len(metrics._metrics) == MAX
    
    # Oldest should be evicted
    assert metrics._metrics[0]['name'] == f"test_1000"
```

---

## Related Topics

- **LESS-19**: Security validations (prevents injection)
- **LESS-21**: Rate limiting (prevents spam)
- **DEC-##**: Memory management decisions

---

## Keywords

memory limits, DoS prevention, FIFO eviction, unbounded growth, OOM crash, security

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2025-10-21**: Original documentation in METRICS Phase 1 optimization

---

**File:** `NM06-Lessons-Performance_LESS-20.md`  
**Directory:** NM06/  
**End of Document**

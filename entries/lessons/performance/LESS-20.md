# File: LESS-20.md

**REF-ID:** LESS-20  
**Category:** Lessons Learned  
**Topic:** Performance  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Status:** Production

---

## Title

Memory Limits Prevent DoS

---

## Priority

CRITICAL

---

## Summary

Unbounded data structures enable DoS attacks and OOM crashes. Always enforce memory limits with FIFO eviction to bound memory growth and protect system availability.

---

## Context

Systems with unbounded list growth allow malicious actors to exhaust memory through data spam. Memory limits with FIFO eviction prevent this attack vector.

---

## Lesson

### The Problem

**Unbounded memory growth:**
```python
# WRONG - No memory limits
class DataStore:
    def __init__(self):
        self._items = []  # Unbounded!
    
    def add_item(self, item):
        self._items.append(item)
        # No limit - grows forever!
```

**Attack scenario:**
```python
# Attacker sends 100,000 items
for i in range(100000):
    add_item(f"spam_{i}")

# Result:
# - 100,000 items * ~200 bytes = 20MB
# - System OOM crash
# - Denial of service
```

### The Solution

**Memory limits with FIFO eviction:**
```python
# CORRECT - Bounded with FIFO
import os

class DataStore:
    def __init__(self):
        # Enforce maximum size
        self.MAX_ITEMS = int(os.getenv('MAX_ITEMS', '10000'))
        self._items = []
    
    def add_item(self, item):
        # Add new item
        self._items.append(item)
        
        # Enforce limit with FIFO eviction
        if len(self._items) > self.MAX_ITEMS:
            self._items.pop(0)  # Remove oldest
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
self._items.pop(0)

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
- Not needed for time-ordered data
```

**For time-series data, FIFO is better:**
- Data is time-ordered
- Older data naturally less relevant
- Simpler and faster
- No access tracking needed

### Configurable Limits

**Environment variable control:**
```python
MAX_ITEMS = int(os.getenv('MAX_ITEMS', '10000'))

# Small system (limited memory): MAX_ITEMS=1000
# Medium system: MAX_ITEMS=10000  
# Large system (lots of memory): MAX_ITEMS=50000
```

### Memory Calculation

**Per-item memory:**
```python
{
    'name': 'item_name',      # ~50 bytes
    'value': 123.45,          # ~24 bytes
    'timestamp': 1234567890,  # ~24 bytes
    'metadata': {...}         # ~100 bytes
}
# Total: ~200 bytes per item
```

**Total memory:**
```
10,000 items * 200 bytes = 2MB
50,000 items * 200 bytes = 10MB
```

### Attack Prevention

**Before limits:**
- Attacker sends 500,000 items
- 500,000 * 200 bytes = 100MB
- System OOM crash
- Total denial of service

**After limits:**
- Attacker sends 500,000 items
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
    store = DataStore()
    MAX = store.MAX_ITEMS
    
    # Add MAX + 1000 items
    for i in range(MAX + 1000):
        store.add_item(f"test_{i}")
    
    # Should only have MAX items
    assert len(store._items) == MAX
    
    # Oldest should be evicted
    assert store._items[0] == f"test_1000"
```

---

## Related

**Cross-References:**
- LESS-21: Rate limiting (prevents spam)
- AP-09: Unbounded data structures anti-pattern

**Keywords:** memory limits, DoS prevention, FIFO eviction, unbounded growth, OOM crash, security

---

## Version History

- **4.0.0** (2025-10-30): Genericized for SIMAv4
- **3.0.0** (2025-10-23): Initial SIMAv3 format

---

**END OF FILE**

# NM05-AntiPatterns-Implementation_AP-06.md - AP-06

# AP-06: Custom Caching Implementation

**Category:** NM05 - Anti-Patterns  
**Topic:** Implementation  
**Priority:** 🟡 High  
**Status:** Active  
**Created:** 2024-08-15  
**Last Updated:** 2025-10-24

---

## Summary

Never implement custom caching in non-CACHE interfaces. The CACHE interface already exists - use it via gateway to ensure consistency, TTL management, and proper memory handling.

---

## Context

Developers sometimes create local cache dictionaries in their interfaces thinking it will improve performance. This duplicates the CACHE interface functionality and creates maintenance problems.

---

## Content

### The Anti-Pattern

**❌ WRONG:**
```python
# In metrics_core.py
_metrics_cache = {}  # Custom cache implementation

def get_metric(name):
    if name in _metrics_cache:
        return _metrics_cache[name]
    
    # Calculate metric
    value = expensive_calculation(name)
    _metrics_cache[name] = value
    return value
```

### Why It's Wrong

**1. Duplication of Functionality**
- CACHE interface already provides caching
- Reinventing the wheel
- Wastes development time

**2. No TTL Management**
- Custom caches grow unbounded
- No expiration mechanism
- Memory leak risk

**3. No Eviction Policy**
- What happens when memory full?
- No LRU, no size limits
- Unpredictable behavior

**4. Inconsistent Behavior**
- Different caching strategies across interfaces
- Hard to debug cache issues
- No central cache monitoring

**5. Memory Waste**
- Multiple caches = multiple copies
- Each interface has own cache
- Could exceed Lambda memory limit

### The Correct Approach

**✅ CORRECT:**
```python
# In metrics_core.py
import gateway

def get_metric(name):
    # Check existing cache
    cache_key = f"metric_{name}"
    cached = gateway.cache_get(cache_key)
    
    if cached is not None:
        return cached
    
    # Calculate metric
    value = expensive_calculation(name)
    
    # Cache via gateway (with TTL)
    gateway.cache_set(cache_key, value, ttl=300)  # 5 min TTL
    
    return value
```

### Benefits of Using Gateway Cache

**1. Centralized Management**
- Single cache implementation
- Consistent behavior
- Easy to monitor

**2. TTL Built-in**
- Automatic expiration
- No memory leaks
- Configurable per item

**3. Eviction Policy**
- LRU eviction when full
- Memory limits enforced
- Predictable behavior

**4. Monitoring**
- Cache hit/miss metrics
- Size tracking
- Performance analysis

**5. Testing**
- Easy to mock gateway
- Consistent test setup
- Clear test boundaries

### Real-World Impact

**Before (Custom Caching):**
```
Memory usage: 45MB (3 custom caches)
Cache hits: Unknown
Cache misses: Unknown
Debugging: Difficult (which cache?)
Maintenance: 3 implementations to update
```

**After (Gateway Caching):**
```
Memory usage: 20MB (1 central cache)
Cache hits: 87% (tracked)
Cache misses: 13% (tracked)
Debugging: Easy (one place)
Maintenance: 1 implementation
```

### When Custom Caching Seems Tempting

**Scenario 1: "I need a cache for just this one thing"**
- Answer: Use gateway.cache_get/set with unique key
- No custom implementation needed

**Scenario 2: "I need different TTL than default"**
- Answer: Gateway cache_set accepts TTL parameter
- Configurable per item

**Scenario 3: "I need to cache complex objects"**
- Answer: Gateway cache handles any Python object
- JSON serialization automatic

**Scenario 4: "Performance is critical here"**
- Answer: Gateway cache is already optimized
- Custom cache won't be faster

### Detection

**Code Review Red Flags:**
```python
# Red flags in any non-cache module:
_cache = {}
_cached_values = {}
_memoized = {}
if key in _local_cache:
```

**Automated Detection:**
```bash
# Find potential custom caches
grep -r "_cache\s*=\s*{}" *.py | grep -v cache_core.py
```

---

## Related Topics

- **DEC-09**: Why cache interface exists
- **RULE-02**: Use existing interfaces
- **AP-07**: Custom logging (similar issue)
- **INT-01**: CACHE interface documentation
- **DEC-07**: Memory limits in Lambda

---

## Keywords

custom caching, duplicate functionality, memory waste, cache interface, gateway caching, DRY principle

---

## Version History

- **2025-10-24**: Migrated to SIMA v3 format
- **2024-08-15**: Created - documented custom caching anti-pattern

---

**File:** `NM05-AntiPatterns-Implementation_AP-06.md`  
**End of Document**

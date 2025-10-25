# NM05-AntiPatterns-Performance_AP-12.md - AP-12

# AP-12: Caching Without TTL

**Category:** NM05 - Anti-Patterns  
**Topic:** Performance  
**Priority:** 🟡 High  
**Status:** Active  
**Created:** 2024-08-20  
**Last Updated:** 2025-10-24

---

## Summary

Always set TTL (time-to-live) when caching data. Without TTL, caches grow unbounded, stale data persists indefinitely, and memory usage becomes unpredictable.

---

## Context

Developers sometimes cache data without expiration thinking it improves performance. This creates memory leaks and serves stale data indefinitely.

---

## Content

### The Anti-Pattern

**❌ WRONG:**
```python
import gateway

def get_user_data(user_id):
    cache_key = f"user_{user_id}"
    cached = gateway.cache_get(cache_key)
    
    if cached:
        return cached
    
    data = fetch_from_api(user_id)
    gateway.cache_set(cache_key, data)  # No TTL!
    return data
```

### Why It's Wrong

**1. Unbounded Growth**
```
Request 1: Cache user_123 (no expiration)
Request 2: Cache user_456 (no expiration)
Request 3: Cache user_789 (no expiration)
...
Request 1000: Cache full, no eviction policy
Result: Memory exhausted
```

**2. Stale Data Forever**
```
Time 0: Cache user profile (age: 25)
Time 1 hour: User updates profile (age: 26)
Time 1 year: Still serving cached data (age: 25)
```

**3. Memory Pressure**
- Lambda has 128MB limit
- Unbounded cache grows
- Eventually OOM errors
- Lambda crashes

**4. No Refresh Mechanism**
- Data never updates
- Manual cache clear needed
- Hard to debug staleness
- User confusion

### The Correct Approach

**✅ CORRECT:**
```python
import gateway

def get_user_data(user_id):
    cache_key = f"user_{user_id}"
    cached = gateway.cache_get(cache_key)
    
    if cached:
        return cached
    
    data = fetch_from_api(user_id)
    gateway.cache_set(cache_key, data, ttl=300)  # 5 min TTL
    return data
```

### Choosing Appropriate TTL

**Guidelines:**
```python
# Static/rarely-changing data
ttl = 3600  # 1 hour

# Semi-static data
ttl = 900   # 15 minutes

# Frequently changing data
ttl = 300   # 5 minutes

# Real-time data
ttl = 60    # 1 minute (or don't cache)

# Configuration data
ttl = 600   # 10 minutes
```

### TTL by Data Type

**User Profiles:**
```python
# User data changes occasionally
gateway.cache_set(f"user_{id}", data, ttl=600)  # 10 min
```

**API Responses:**
```python
# External API data
gateway.cache_set(f"api_{endpoint}", data, ttl=300)  # 5 min
```

**Configuration:**
```python
# Config changes infrequently
gateway.cache_set("config", data, ttl=900)  # 15 min
```

**Computed Results:**
```python
# Expensive calculations
gateway.cache_set(f"calc_{params}", result, ttl=1800)  # 30 min
```

**Session Data:**
```python
# User sessions
gateway.cache_set(f"session_{id}", data, ttl=3600)  # 1 hour
```

### Cache Behavior

**With TTL:**
```
Set cache (TTL=300)
   ↓
Time 0: Cache hit (data fresh)
   ↓
Time 299: Cache hit (data fresh)
   ↓
Time 301: Cache miss (expired)
   ↓
Fetch new data
   ↓
Set cache again (TTL=300)
```

**Without TTL:**
```
Set cache (no TTL)
   ↓
Forever: Cache hit (data never expires)
   ↓
Memory grows unbounded
   ↓
Eventually: OOM error
```

### Memory Impact

**Scenario: Caching User Data**

**Without TTL:**
```
Day 1: 100 users cached (10KB each) = 1MB
Day 7: 700 users cached = 7MB
Day 30: 3000 users cached = 30MB
Day 90: 9000 users cached = 90MB
Day 120: 12,000 users = 120MB (near limit!)
```

**With TTL=3600 (1 hour):**
```
Hour 1: 100 users cached = 1MB
Hour 2: Old expired, 100 new = 1MB
Hour 3: Old expired, 100 new = 1MB
Steady state: ~1-2MB (predictable)
```

### Testing TTL

**Verify expiration:**
```python
def test_cache_expires():
    # Set with TTL
    gateway.cache_set("test", "data", ttl=1)
    
    # Immediate hit
    assert gateway.cache_get("test") == "data"
    
    # Wait for expiration
    time.sleep(2)
    
    # Should be expired
    assert gateway.cache_get("test") is None
```

### When No TTL Seems Tempting

**Scenario: "This data never changes"**
- Answer: Use long TTL (3600+), not infinite
- Even "static" data can change
- Long TTL = performance + safety

**Scenario: "I need it cached forever"**
- Answer: Why? Probably don't.
- Use database for persistent storage
- Cache is for temporary performance boost

**Scenario: "TTL management is complex"**
- Answer: One parameter (`ttl=300`)
- Simpler than debugging memory issues
- Default to 5-10 minutes if unsure

### Detection

**Code Review:**
```python
# Red flag: cache_set without TTL
gateway.cache_set(key, value)  # Missing TTL!

# Should be:
gateway.cache_set(key, value, ttl=300)
```

**Automated Detection:**
```bash
# Find cache_set without TTL parameter
grep -r "cache_set.*)" *.py | grep -v "ttl="
```

---

## Related Topics

- **INT-01**: CACHE interface with TTL support
- **DEC-07**: Memory limits requiring bounded caches
- **AP-06**: Custom caching (also needs TTL)
- **LESS-02**: Measure don't guess (TTL tuning)
- **PATH-01**: Cold start (cache helps but needs limits)

---

## Keywords

TTL, time-to-live, cache expiration, unbounded cache, memory leak, stale data, cache limits

---

## Version History

- **2025-10-24**: Migrated to SIMA v3 format
- **2024-08-20**: Created - documented missing TTL anti-pattern

---

**File:** `NM05-AntiPatterns-Performance_AP-12.md`  
**End of Document**

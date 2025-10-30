# NM01-Architecture-InterfacesCore_INT-01.md - INT-01

# INT-01: CACHE Interface

**Category:** NM01 - Architecture  
**Topic:** Interfaces-Core  
**Priority:** 🔴 CRITICAL  
**Status:** Active  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-24

---

## Summary

In-memory caching interface with TTL support, providing fast data storage and retrieval for Lambda invocations with automatic expiration management.

---

## Context

The CACHE interface is the most frequently used interface in SUGA-ISP, providing high-performance in-memory caching with TTL (Time-To-Live) management. Critical for optimizing Lambda performance by reducing redundant operations and external API calls.

**Why it exists:** Lambda warm containers persist state between invocations. The CACHE interface exploits this to store frequently accessed data in memory, dramatically improving response times.

---

## Content

### Overview

```
Router: interface_cache.py
Core: cache_core.py
Purpose: In-memory caching with TTL support
Pattern: Dictionary-based dispatch
State: Module-level dictionaries (_CACHE_STORE, _CACHE_TTL)
```

### Operations (8 total)

```
├─ set: Store value with TTL
├─ get: Retrieve value (returns sentinel if not found)
├─ delete: Remove specific key
├─ clear: Clear all cache
├─ has: Check if key exists and not expired
├─ get_stats: Get cache statistics
├─ get_all_keys: Get all cache keys
└─ cleanup_expired: Remove expired entries
```

### Gateway Wrappers

```python
# Primary operations
cache_set(key: str, value: Any, ttl: int = 300) -> bool
cache_get(key: str, default: Any = None) -> Any
cache_delete(key: str) -> bool
cache_clear() -> bool

# Utility operations
cache_has(key: str) -> bool
cache_stats() -> Dict
cache_keys() -> List[str]
cache_cleanup() -> int  # Returns count of cleaned entries
```

### Dependencies

```
Uses: LOGGING (for cache events), SECURITY (for sentinel validation)
Used by: CONFIG (config caching), HTTP_CLIENT (response caching)
```

### Special Patterns

**Sentinel Pattern:** Uses `_CacheMiss` sentinel to distinguish "not found" from `None` values
**TTL Management:** Automatic expiration checking on get operations
**Lazy Cleanup:** Expired entries removed on access or explicit cleanup

### Critical Bug History

**BUG-01: Sentinel Leak (535ms penalty)**
- The `_CacheMiss` sentinel was leaking outside cache_core
- Caused 535ms performance penalty per leak
- **Fixed:** Validate sentinels in SECURITY interface before returning
- **Lesson:** Never let internal sentinels escape module boundaries

### Design Decisions

```
- In-memory only (no external cache due to free tier)
- TTL-based expiration (no LRU eviction)
- Module-level state (persists across warm invocations)
- Sentinel pattern for distinguishing "not found" from None
```

### Usage Example

```python
from gateway import cache_set, cache_get, cache_has

# Store with default 5-minute TTL
cache_set('user:123', {'name': 'John'})

# Store with custom TTL (1 hour)
cache_set('config:db', db_config, ttl=3600)

# Retrieve (returns None if not found/expired)
user = cache_get('user:123')

# Retrieve with custom default
value = cache_get('missing_key', default='NOT_FOUND')

# Check existence
if cache_has('user:123'):
    print("User cached")

# Get cache statistics
stats = cache_stats()
print(f"Hits: {stats['hits']}, Misses: {stats['misses']}")
```

### Performance Characteristics

```
Set operation: O(1) - ~0.01ms
Get operation: O(1) - ~0.01ms with hit, ~0.02ms with expiration check
Delete operation: O(1) - ~0.01ms
Clear operation: O(n) - ~1ms for 1000 keys
Stats operation: O(1) - ~0.01ms
```

### Memory Usage

```
Per cached item: ~500 bytes (key + value + TTL + overhead)
Typical usage: 100-1000 items = 50KB-500KB
Maximum recommended: 10,000 items = ~5MB
```

---

## Related Topics

- **BUG-01**: Sentinel leak (535ms cost) - Why sentinel validation is critical
- **DEC-05**: Sentinel sanitization at router layer
- **PATH-02**: Cache operation flow - How data moves through the cache
- **INT-02**: LOGGING - Used for cache event logging
- **INT-03**: SECURITY - Used for sentinel validation

---

## Keywords

cache, caching, TTL, in-memory, sentinel, performance, Lambda, state persistence

---

## Version History

- **2025-10-24**: Atomized from monolithic file (SIMA v3 migration)
- **2025-10-20**: Original content in IMPLEMENTATION_Core.md

---

**File:** `NM01-Architecture-InterfacesCore_INT-01.md`  
**End of Document**

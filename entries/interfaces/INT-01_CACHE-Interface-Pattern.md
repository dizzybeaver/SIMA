# INT-01: CACHE Interface Pattern
# File: INT-01_CACHE-Interface-Pattern.md

**REF-ID:** INT-01  
**Version:** 1.0.0  
**Category:** Interface Pattern  
**Status:** Active  
**Created:** 2025-10-29  
**Last Updated:** 2025-10-29

---

## 📋 OVERVIEW

**Interface Name:** CACHE  
**Short Code:** CACHE  
**Type:** Core Infrastructure Interface  
**Priority:** 🔴 CRITICAL

**One-Line Description:**  
In-memory caching interface with TTL support for fast data storage and retrieval, dramatically reducing redundant operations and external API calls.

**Primary Purpose:**  
CACHE provides a simple, performant key-value store that persists across invocations in serverless environments, enabling dramatic performance improvements (10-100x) by eliminating redundant operations.

---

## 🎯 APPLICABILITY

### When to Use
✅ Use CACHE interface when:
- Need to store frequently accessed data temporarily
- Want to reduce redundant API calls or expensive computations
- Operating in environment with warm containers (Lambda, long-lived processes)
- Need TTL-based expiration (automatic cleanup)
- Want O(1) lookup performance
- Data can be lost without data integrity issues (transient cache)
- Improving response times is priority

### When NOT to Use
❌ Do NOT use CACHE interface when:
- Need persistent storage (use database instead)
- Data loss is unacceptable (cache is volatile)
- Need distributed caching across multiple instances (use Redis/Memcached)
- Memory constraints are tight
- Cache invalidation complexity exceeds benefits
- Data size is very large (> 100MB per entry)

### Best For
- **Environment:** Serverless (Lambda), containerized applications, long-lived processes
- **Data Types:** Configuration values, API responses, computed results, session data
- **Size:** Small to medium datasets (KB to low MB range)
- **Access Pattern:** High read frequency, low write frequency

---

## 🗺️ STRUCTURE

### Core Components

**Component 1: Router Layer (interface_cache.py)**
- **Purpose:** Operation dispatch and validation
- **Responsibilities:** 
  - Route operations to core implementations
  - Validate operation parameters
  - Handle operation-level errors
  - Provide dispatch dictionary for O(1) routing
- **Dependencies:** Imports cache_core
- **Interface:** execute_cache_operation(operation, params)

**Component 2: Core Layer (cache_core.py)**
- **Purpose:** Business logic implementation
- **Responsibilities:**
  - Maintain cache store (dictionary)
  - Manage TTL (Time-To-Live) tracking
  - Execute cache operations (get, set, delete, etc.)
  - Handle expiration cleanup
  - Track cache statistics
- **Dependencies:** None (pure Python dictionaries)
- **State:** Module-level dictionaries (_CACHE_STORE, _CACHE_TTL, _CACHE_STATS)

**Component 3: Gateway Wrappers (gateway_wrappers.py)**
- **Purpose:** Convenience functions for users
- **Responsibilities:**
  - Provide clean, intuitive API
  - Transform parameters into dispatch format
  - Add documentation
  - Simplify common operations
- **Dependencies:** Calls gateway_core.execute_operation
- **Interface:** cache_get(key), cache_set(key, value, ttl), etc.

---

## 📐 KEY RULES

### Rule 1: TTL-Based Expiration
**What:** Every cached value can have a TTL (time-to-live) in seconds. After TTL expires, value is considered stale.

**Why:** Prevents stale data from persisting indefinitely. Automatic cleanup reduces memory usage.

**How:**
```python
# Set with 5-minute TTL
cache_set('api_response', data, ttl=300)

# After 300 seconds, cache_get returns None
value = cache_get('api_response')  # None if expired
```

**Consequence:** Cache entries automatically expire. Applications must handle cache misses gracefully.

---

### Rule 2: Sentinel Object Pattern
**What:** Use special sentinel object to distinguish "key not found" from "key found with None value".

**Why:** Allows storing None as legitimate cache value. Distinguishes three states: found, not found, expired.

**How:**
```python
# Internal sentinel (not exposed to users)
_CACHE_MISS = object()

def cache_get_internal(key):
    return _CACHE_STORE.get(key, _CACHE_MISS)

# Gateway wrapper sanitizes sentinel
def cache_get(key, default=None):
    value = cache_get_internal(key)
    return default if is_sentinel(value) else value
```

**Consequence:** Sentinels must never leak to users. Always sanitize at gateway layer.

---

### Rule 3: Module-Level State
**What:** Cache state stored in module-level dictionaries that persist across function calls.

**Why:** In serverless environments, warm containers reuse module state between invocations, preserving cache.

**How:**
```python
# Module-level (persists across invocations)
_CACHE_STORE = {}
_CACHE_TTL = {}
_CACHE_STATS = {'hits': 0, 'misses': 0}
```

**Consequence:** Cache survives between invocations in warm containers. Cold starts reset cache.

---

### Rule 4: Operation Dispatch Dictionary
**What:** Router uses dictionary-based dispatch for O(1) operation routing.

**Why:** O(1) lookup performance. Self-documenting (see all operations). Easy to extend.

**How:**
```python
_OPERATION_DISPATCH = {
    'get': _execute_get,
    'set': _execute_set,
    'delete': _execute_delete,
    'clear': _execute_clear,
    'has': _execute_has,
    'stats': _execute_get_stats,
    'keys': _execute_get_keys,
    'cleanup': _execute_cleanup_expired,
}

def execute_cache_operation(operation, params):
    handler = _OPERATION_DISPATCH.get(operation)
    if not handler:
        raise ValueError(f"Unknown operation: {operation}")
    return handler(**params)
```

**Consequence:** Adding new operations is simple (add dictionary entry). All operations have O(1) dispatch.

---

## 🎁 MAJOR BENEFITS

### Benefit 1: Dramatic Performance Improvement (10-100x)
**What:** Cache hits are 10-100x faster than cache misses (external API calls, computations).

**Measurement:**
- Cached HTTP response: < 1ms lookup
- Uncached HTTP response: 50-200ms (external API call)
- **Speedup: 50-200x for cached operations**

**Impact:**
- API response time: 200ms → 1ms (200x faster)
- Database query: 50ms → 0.5ms (100x faster)
- Computation: 100ms → 1ms (100x faster)

**Why It Matters:**
In serverless environments, every millisecond counts. Cache dramatically reduces:
- Response latency
- External API costs
- Database load
- CPU usage

---

### Benefit 2: Cost Reduction
**What:** Reduces external API calls, database queries, and computation time.

**Measurement:**
- 80-90% cache hit rate typical
- External API costs reduced by 80-90%
- Database query costs reduced by 80-90%

**Impact:**
- Home Assistant API calls: 1000/day → 100/day (90% reduction)
- AWS API calls: $50/month → $5/month (90% reduction)
- Database queries: 10,000/day → 1,000/day (90% reduction)

**Why It Matters:**
Pay-per-use models (API calls, database queries) benefit enormously from caching. 80% cost reduction is common.

---

### Benefit 3: Resilience to External Failures
**What:** Cached data allows operation even when external services fail.

**Measurement:**
- Without cache: 100% dependent on external service availability
- With cache: Can serve stale data during outages
- **Availability improvement: 99% → 99.9% typical**

**Impact:**
- External API down: Serve cached responses (degraded but functional)
- Database unreachable: Use cached config values
- Network issues: Continue with cached data

**Why It Matters:**
External dependencies fail. Caching provides graceful degradation instead of complete failure.

---

### Benefit 4: Simple, Intuitive API
**What:** Clean key-value interface anyone can understand.

**Measurement:**
- Learning time: < 5 minutes
- Lines of code: 1-2 lines per operation
- **Developer productivity: High**

**Impact:**
```python
# Simple, obvious usage
cache_set('user_data', user_data, ttl=300)
user_data = cache_get('user_data')
if user_data:
    return user_data
```

**Why It Matters:**
Low learning curve = faster development. Intuitive API = fewer bugs.

---

## ⚠️ COMMON PITFALLS

### Pitfall 1: Forgetting Cache Misses
**Problem:** Code assumes cache always has data, crashes on cache miss.

```python
# ❌ WRONG - No cache miss handling
def get_user():
    return cache_get('user_data')['name']  # Crashes if cache empty!

# ✅ CORRECT - Handle cache misses
def get_user():
    user_data = cache_get('user_data')
    if not user_data:
        user_data = fetch_from_api()
        cache_set('user_data', user_data, ttl=300)
    return user_data.get('name', 'Unknown')
```

**Solution:** Always check if cache_get returns None. Have fallback logic.

---

### Pitfall 2: Caching Mutable Objects Without Copying
**Problem:** Modifying cached object affects all cache users.

```python
# ❌ WRONG - Modifying cached object
user_list = cache_get('users')
user_list.append(new_user)  # Modifies cached object!
cache_set('users', user_list)  # Now corrupted

# ✅ CORRECT - Copy before modifying
import copy
user_list = copy.deepcopy(cache_get('users') or [])
user_list.append(new_user)
cache_set('users', user_list)
```

**Solution:** Deep copy cached data before modification, or use immutable data structures.

---

### Pitfall 3: Inappropriate TTL Values
**Problem:** TTL too short (excessive cache misses) or too long (stale data).

```python
# ❌ TOO SHORT - Cache barely helps
cache_set('api_response', data, ttl=1)  # Expires immediately

# ❌ TOO LONG - Stale data persists
cache_set('api_response', data, ttl=86400)  # 24 hours = likely stale

# ✅ APPROPRIATE - Balance freshness and performance
cache_set('static_config', data, ttl=3600)  # 1 hour for config
cache_set('api_response', data, ttl=300)    # 5 minutes for dynamic data
cache_set('computed_result', data, ttl=60)  # 1 minute for frequently changing
```

**Solution:** Choose TTL based on data volatility. Static data = longer TTL. Dynamic data = shorter TTL.

---

### Pitfall 4: Memory Exhaustion
**Problem:** Unbounded cache growth consumes all memory.

```python
# ❌ WRONG - No size limits
for i in range(1000000):
    cache_set(f'key_{i}', large_data)  # Eventually crashes

# ✅ CORRECT - Implement size limits or LRU eviction
MAX_CACHE_SIZE = 10000

def cache_set_safe(key, value, ttl=None):
    if len(_CACHE_STORE) >= MAX_CACHE_SIZE:
        # Evict expired entries first
        cleanup_expired()
        # If still too large, evict oldest
        if len(_CACHE_STORE) >= MAX_CACHE_SIZE:
            oldest_key = min(_CACHE_TTL, key=_CACHE_TTL.get)
            cache_delete(oldest_key)
    cache_set(key, value, ttl)
```

**Solution:** Implement cache size limits. Periodic cleanup. LRU eviction if needed.

---

## 📦 IMPLEMENTATION PATTERNS

### Pattern 1: Basic Cache Interface

**Router (interface_cache.py):**
```python
"""
Cache Interface - Routes cache operations to implementations.
"""

def execute_cache_operation(operation: str, params: dict):
    """
    Execute cache operation with given parameters.
    
    Args:
        operation: Operation name ('get', 'set', 'delete', etc.)
        params: Operation parameters
        
    Returns:
        Operation result
        
    Raises:
        ValueError: Unknown operation
    """
    _OPERATION_DISPATCH = {
        'get': _execute_get,
        'set': _execute_set,
        'delete': _execute_delete,
        'clear': _execute_clear,
        'has': _execute_has,
        'stats': _execute_get_stats,
        'keys': _execute_get_keys,
        'cleanup': _execute_cleanup_expired,
    }
    
    handler = _OPERATION_DISPATCH.get(operation)
    if not handler:
        raise ValueError(f"Unknown cache operation: {operation}")
    
    return handler(**params)

def _execute_get(key: str):
    """Get value from cache."""
    import cache_core
    return cache_core.get_value(key)

def _execute_set(key: str, value, ttl: int = None):
    """Set value in cache with optional TTL."""
    import cache_core
    return cache_core.set_value(key, value, ttl)

# Additional operations...
```

**Core (cache_core.py):**
```python
"""
Cache Core - In-memory caching with TTL support.
"""
import time
from typing import Any, Dict, List, Optional

# Module-level state (persists across invocations)
_CACHE_STORE: Dict[str, Any] = {}
_CACHE_TTL: Dict[str, float] = {}
_CACHE_STATS: Dict[str, int] = {
    'hits': 0,
    'misses': 0,
    'sets': 0,
    'deletes': 0,
}

# Sentinel for "not found"
_CACHE_MISS = object()

def get_value(key: str) -> Any:
    """
    Get value from cache.
    
    Returns _CACHE_MISS sentinel if not found or expired.
    Gateway wrapper will convert to None or default.
    """
    # Check expiration
    if key in _CACHE_TTL:
        if time.time() > _CACHE_TTL[key]:
            # Expired - delete and return miss
            delete_value(key)
            _CACHE_STATS['misses'] += 1
            return _CACHE_MISS
    
    # Get value
    if key in _CACHE_STORE:
        _CACHE_STATS['hits'] += 1
        return _CACHE_STORE[key]
    
    _CACHE_STATS['misses'] += 1
    return _CACHE_MISS

def set_value(key: str, value: Any, ttl_seconds: Optional[int] = None) -> bool:
    """
    Set value in cache with optional TTL.
    
    Args:
        key: Cache key
        value: Value to store (can be any Python object)
        ttl_seconds: Time-to-live in seconds (None = no expiration)
        
    Returns:
        True if successful
    """
    _CACHE_STORE[key] = value
    
    if ttl_seconds:
        _CACHE_TTL[key] = time.time() + ttl_seconds
    elif key in _CACHE_TTL:
        # Remove TTL if setting without one
        del _CACHE_TTL[key]
    
    _CACHE_STATS['sets'] += 1
    return True

def delete_value(key: str) -> bool:
    """Delete value from cache."""
    deleted = False
    
    if key in _CACHE_STORE:
        del _CACHE_STORE[key]
        deleted = True
    
    if key in _CACHE_TTL:
        del _CACHE_TTL[key]
    
    if deleted:
        _CACHE_STATS['deletes'] += 1
    
    return deleted

def clear_cache() -> bool:
    """Clear all cache entries."""
    _CACHE_STORE.clear()
    _CACHE_TTL.clear()
    return True

def has_key(key: str) -> bool:
    """Check if key exists and not expired."""
    if key in _CACHE_TTL:
        if time.time() > _CACHE_TTL[key]:
            delete_value(key)
            return False
    return key in _CACHE_STORE

def get_stats() -> Dict[str, int]:
    """Get cache statistics."""
    return {
        **_CACHE_STATS,
        'size': len(_CACHE_STORE),
        'ttl_entries': len(_CACHE_TTL),
    }

def get_all_keys() -> List[str]:
    """Get all cache keys."""
    return list(_CACHE_STORE.keys())

def cleanup_expired() -> int:
    """Remove all expired entries. Returns count removed."""
    current_time = time.time()
    expired_keys = [
        key for key, expiry in _CACHE_TTL.items()
        if current_time > expiry
    ]
    
    for key in expired_keys:
        delete_value(key)
    
    return len(expired_keys)
```

**Gateway Wrappers (gateway_wrappers.py):**
```python
"""
Gateway Wrappers - Convenience functions for cache operations.
"""

def cache_get(key: str, default=None):
    """
    Get value from cache.
    
    Args:
        key: Cache key
        default: Default value if not found
        
    Returns:
        Cached value or default
    """
    from gateway_core import execute_operation, GatewayInterface
    from security_core import is_sentinel
    
    value = execute_operation(GatewayInterface.CACHE, 'get', {'key': key})
    
    # Sanitize sentinel
    if is_sentinel(value):
        return default
    
    return value

def cache_set(key: str, value, ttl: int = 300) -> bool:
    """
    Set value in cache with TTL.
    
    Args:
        key: Cache key
        value: Value to store
        ttl: Time-to-live in seconds (default: 300 = 5 minutes)
        
    Returns:
        True if successful
    """
    from gateway_core import execute_operation, GatewayInterface
    
    return execute_operation(
        GatewayInterface.CACHE,
        'set',
        {'key': key, 'value': value, 'ttl': ttl}
    )

def cache_delete(key: str) -> bool:
    """Delete key from cache."""
    from gateway_core import execute_operation, GatewayInterface
    
    return execute_operation(
        GatewayInterface.CACHE,
        'delete',
        {'key': key}
    )

def cache_clear() -> bool:
    """Clear all cache entries."""
    from gateway_core import execute_operation, GatewayInterface
    
    return execute_operation(GatewayInterface.CACHE, 'clear', {})

def cache_has(key: str) -> bool:
    """Check if key exists in cache."""
    from gateway_core import execute_operation, GatewayInterface
    
    return execute_operation(
        GatewayInterface.CACHE,
        'has',
        {'key': key}
    )

def cache_stats() -> dict:
    """Get cache statistics."""
    from gateway_core import execute_operation, GatewayInterface
    
    return execute_operation(GatewayInterface.CACHE, 'stats', {})

def cache_keys() -> list:
    """Get all cache keys."""
    from gateway_core import execute_operation, GatewayInterface
    
    return execute_operation(GatewayInterface.CACHE, 'keys', {})

def cache_cleanup() -> int:
    """Clean up expired entries. Returns count removed."""
    from gateway_core import execute_operation, GatewayInterface
    
    return execute_operation(GatewayInterface.CACHE, 'cleanup', {})
```

---

### Pattern 2: Cache with LRU Eviction

**For memory-constrained environments:**
```python
"""
Cache with LRU (Least Recently Used) eviction.
"""
from collections import OrderedDict
import time

MAX_CACHE_SIZE = 1000

_CACHE_STORE = OrderedDict()
_CACHE_TTL = {}

def get_value(key: str):
    """Get value and mark as recently used."""
    if key in _CACHE_TTL:
        if time.time() > _CACHE_TTL[key]:
            delete_value(key)
            return _CACHE_MISS
    
    if key in _CACHE_STORE:
        # Move to end (mark as recently used)
        _CACHE_STORE.move_to_end(key)
        return _CACHE_STORE[key]
    
    return _CACHE_MISS

def set_value(key: str, value, ttl_seconds=None):
    """Set value with LRU eviction."""
    # Evict if at capacity
    if key not in _CACHE_STORE and len(_CACHE_STORE) >= MAX_CACHE_SIZE:
        # Remove least recently used (first item)
        oldest_key = next(iter(_CACHE_STORE))
        delete_value(oldest_key)
    
    _CACHE_STORE[key] = value
    _CACHE_STORE.move_to_end(key)  # Mark as recently used
    
    if ttl_seconds:
        _CACHE_TTL[key] = time.time() + ttl_seconds
    
    return True
```

---

### Pattern 3: Cache with Metrics

**For observability:**
```python
"""
Cache with detailed metrics tracking.
"""

_METRICS = {
    'hits': 0,
    'misses': 0,
    'sets': 0,
    'deletes': 0,
    'expirations': 0,
    'hit_rate': 0.0,
    'avg_ttl': 0.0,
}

def get_value(key: str):
    """Get value with metrics."""
    value = _get_from_cache(key)
    
    if value is not _CACHE_MISS:
        _METRICS['hits'] += 1
    else:
        _METRICS['misses'] += 1
    
    # Update hit rate
    total = _METRICS['hits'] + _METRICS['misses']
    _METRICS['hit_rate'] = _METRICS['hits'] / total if total > 0 else 0.0
    
    return value

def set_value(key: str, value, ttl_seconds=None):
    """Set value with TTL tracking."""
    _CACHE_STORE[key] = value
    _METRICS['sets'] += 1
    
    if ttl_seconds:
        _CACHE_TTL[key] = time.time() + ttl_seconds
        # Update average TTL
        ttls = [t - time.time() for t in _CACHE_TTL.values()]
        _METRICS['avg_ttl'] = sum(ttls) / len(ttls) if ttls else 0.0
    
    return True
```

---

## 💡 USAGE EXAMPLES

### Example 1: API Response Caching

**Scenario:** Cache external API responses to reduce API calls and improve performance.

**Implementation:**
```python
def get_weather(city: str) -> dict:
    """
    Get weather for city with caching.
    
    1. Check cache first
    2. If cache miss, call external API
    3. Cache response for 5 minutes
    4. Return data
    """
    # Try cache first
    cache_key = f"weather_{city}"
    cached_weather = cache_get(cache_key)
    
    if cached_weather:
        log_info(f"Weather cache hit for {city}")
        return cached_weather
    
    # Cache miss - call API
    log_info(f"Weather cache miss for {city}, calling API")
    
    try:
        weather = call_weather_api(city)
        
        # Cache for 5 minutes
        cache_set(cache_key, weather, ttl=300)
        
        return weather
    except Exception as e:
        log_error(f"Weather API failed: {e}")
        # Could return stale cache if available
        raise
```

**Performance Impact:**
- Without cache: 100-200ms per request (external API)
- With cache: < 1ms per request (80-90% hit rate)
- **Improvement: 100-200x faster, 90% fewer API calls**

---

### Example 2: Configuration Caching

**Scenario:** Cache configuration from Parameter Store to reduce AWS API calls.

**Implementation:**
```python
def get_config(key: str) -> str:
    """
    Get configuration with multi-tier caching.
    
    1. Check memory cache (fastest)
    2. If cache miss, load from Parameter Store
    3. Cache for 1 hour
    4. Return value
    """
    cache_key = f"config_{key}"
    
    # Try cache
    cached_value = cache_get(cache_key)
    if cached_value:
        return cached_value
    
    # Cache miss - load from SSM
    log_info(f"Config cache miss: {key}, loading from Parameter Store")
    
    import boto3
    ssm = boto3.client('ssm')
    
    try:
        response = ssm.get_parameter(Name=key, WithDecryption=True)
        value = response['Parameter']['Value']
        
        # Cache for 1 hour
        cache_set(cache_key, value, ttl=3600)
        
        return value
    except Exception as e:
        log_error(f"Failed to load config {key}: {e}")
        raise
```

**Performance Impact:**
- Without cache: 50-100ms per config lookup (AWS API)
- With cache: < 1ms per lookup
- **Improvement: 50-100x faster, 95% fewer AWS API calls**

---

## 📄 EVOLUTION & VERSIONING

### Version History

**v1.0.0** (2025-10-29)
- Initial CACHE interface pattern documentation
- Basic operations (get, set, delete, clear, has, stats, keys, cleanup)
- TTL support documented
- Sentinel pattern explained
- LRU eviction pattern included

### Future Considerations
- **Distributed Caching:** Redis/Memcached integration patterns
- **Cache Warming:** Pre-populate cache on cold start
- **Cache Invalidation:** Event-driven invalidation strategies
- **Cache Compression:** Compress large cached values
- **Cache Serialization:** Pickle/JSON serialization for complex objects

### Deprecation Path
**If This Pattern Is Deprecated:**
- **Reason:** [External cache service becomes mandatory]
- **Replacement:** [Redis/Memcached interface pattern]
- **Migration Guide:** [Convert cache_* calls to redis_* calls]
- **Support Timeline:** [Minimum 6 months deprecation notice]

---

## 📚 REFERENCES

### Internal References
- **Related Patterns:** 
  - INT-02 (LOGGING) - Used for cache operation logging
  - INT-03 (SECURITY) - Used for sentinel sanitization
  - INT-04 (METRICS) - Used for cache performance tracking
  - INT-05 (CONFIG) - Uses CACHE for configuration caching
  - INT-08 (HTTP_CLIENT) - Uses CACHE for response caching

### External References
- **Pattern Origin:** Standard caching patterns (memcache, Redis)
- **TTL Pattern:** Time-to-live from DNS and cache systems
- **LRU Pattern:** Least Recently Used eviction from OS page replacement

### Related Entries
- **Gateway Patterns:** GATE-01 (Gateway Layer Structure), GATE-02 (Lazy Import)
- **Architecture:** ARCH-SUGA (Three-layer structure)
- **Performance:** ARCH-LMMS (Memory management includes caching strategy)

---

## 🤝 CONTRIBUTORS

**Original Author:** SIMAv4 Phase 3.0 Documentation  
**Major Contributors:**
- SUGA-ISP Project Team - Production cache implementation and lessons
- SIMAv4 Phase 3.0 - Generic pattern extraction

**Last Reviewed By:** Claude  
**Review Date:** 2025-10-29

---

## 📝 CHANGE LOG

### [1.0.0] - 2025-10-29
- Initial CACHE interface pattern documentation
- Extracted from SUGA-ISP project knowledge
- Generalized for reuse across any project
- Documented TTL, sentinel, LRU, and metrics patterns

---

**END OF INTERFACE ENTRY**

**REF-ID:** INT-01  
**Template Version:** 1.0.0  
**Entry Type:** Interface Pattern  
**Status:** Active  
**Maintenance:** Review quarterly or after major cache-related changes

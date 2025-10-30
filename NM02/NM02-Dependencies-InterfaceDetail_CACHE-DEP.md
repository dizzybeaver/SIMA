# NM02-Dependencies-InterfaceDetail_CACHE-DEP.md - CACHE-DEP

# CACHE-DEP: CACHE Interface Dependencies (Deep Dive)

**Category:** NM02 - Dependencies
**Topic:** Interface Detail
**Priority:** 🟡 High
**Status:** Active
**Created:** 2024-04-15
**Last Updated:** 2025-10-24

---

## Summary

Detailed analysis of CACHE interface dependencies: what CACHE depends on (LOGGING, METRICS) and what depends on CACHE (HTTP_CLIENT, CONFIG, SECURITY). Includes usage patterns, performance metrics, and common cache keys.

---

## Context

CACHE is a Layer 2 interface that provides data caching for performance optimization. This deep dive examines all its dependency relationships and real-world usage patterns.

**Why This Analysis Exists:**
- Shows complete dependency picture
- Documents usage patterns
- Provides performance baselines
- Guides dependency changes

---

## Content

### CACHE Dependencies

**What CACHE Depends On:**

```
CACHE depends on:
├─ LOGGING (for error logging)
│   ├─ Used in: interface_cache.py (router layer)
│   ├─ Functions: log_error, log_warning, log_info
│   └─ Purpose: Log cache operations and errors
│
└─ METRICS (for operation tracking)
    ├─ Used in: cache_core.py (implementation layer)
    ├─ Functions: record_metric, increment_counter
    └─ Purpose: Track cache hits, misses, performance
```

**Dependency Pattern:**
```python
# In cache_core.py
from gateway import log_info, record_metric  # Layer 0 + Layer 2

def cache_get(key):
    log_info(f"Cache lookup: {key}")
    result = _CACHE_STORE.get(key)
    
    # Track cache performance
    record_metric("cache_hit" if result else "cache_miss", 1.0)
    return result
```

### CACHE Used By

**What Depends on CACHE:**

```
CACHE is used by:
├─ HTTP_CLIENT (response caching)
│   ├─ Purpose: Cache GET responses to reduce API calls
│   └─ Pattern: cache_set(f"http_{url}", response, ttl=300)
│
├─ CONFIG (configuration caching)
│   ├─ Purpose: Cache config values to reduce lookups
│   └─ Pattern: cache_get(f"config_{key}")
│
└─ SECURITY (token caching)
    ├─ Purpose: Cache validated tokens
    └─ Pattern: cache_set(f"token_{token}", validation_result, ttl=3600)
```

### Usage Examples

**HTTP_CLIENT Uses CACHE:**
```python
# In http_client_core.py
from gateway import cache_get, cache_set

def http_get(url):
    # Check cache first
    cache_key = f"http_{url}"
    cached_response = cache_get(cache_key)
    if cached_response:
        return cached_response
    
    # Make request
    response = _perform_http_request(url)
    
    # Cache response (5 minute TTL)
    cache_set(cache_key, response, ttl=300)
    return response
```

**CONFIG Uses CACHE:**
```python
# In config_core.py
from gateway import cache_get, cache_set

def get_config(key):
    # Try cache first
    cache_key = f"config_{key}"
    cached_value = cache_get(cache_key)
    if cached_value:
        return cached_value
    
    # Load from Parameter Store
    value = _load_from_parameter_store(key)
    
    # Cache indefinitely (config doesn't change often)
    cache_set(cache_key, value, ttl=None)
    return value
```

**SECURITY Uses CACHE:**
```python
# In security_core.py
from gateway import cache_get, cache_set

def validate_token(token):
    # Check if token already validated
    cache_key = f"token_{token}"
    cached_result = cache_get(cache_key)
    if cached_result is not None:
        return cached_result
    
    # Validate token
    is_valid = _perform_token_validation(token)
    
    # Cache result (1 hour TTL)
    cache_set(cache_key, is_valid, ttl=3600)
    return is_valid
```

### Performance Metrics

**Cache Performance:**
- **Hit rate:** 75-85% typical
- **Cache lookup:** < 1ms
- **Cache save:** < 2ms
- **Memory usage:** ~10MB typical

**Impact on System:**
```
HTTP requests without cache:
- Average: 180ms per request
- 100 requests: 18 seconds

HTTP requests with cache (80% hit rate):
- 80 cached: 80ms (1ms each)
- 20 uncached: 3.6s (180ms each)
- Total: 3.68 seconds
- Savings: 14.32 seconds (80% reduction!)
```

### Common Cache Keys

**Cache Key Patterns:**

```
HTTP Responses:
- Pattern: http_{url}
- Example: http_https://api.example.com/data
- TTL: 300-600 seconds
- Usage: Reduces API calls by 60-80%

Configuration:
- Pattern: config_{key}
- Example: config_home_assistant_url
- TTL: Indefinite (persistent)
- Usage: Eliminates Parameter Store calls

Security Tokens:
- Pattern: token_{token_value}
- Example: token_abc123...
- TTL: 3600 seconds
- Usage: Reduces validation overhead

Home Assistant State:
- Pattern: ha_state_{entity_id}
- Example: ha_state_light.living_room
- TTL: 60 seconds
- Usage: Reduces HA API calls
```

### Cache Statistics

**Typical Usage (per invocation):**
```
Cache Operations:
- Gets: 5-10 per request
- Sets: 1-2 per request
- Deletes: 0-1 per request
- Hit rate: 75-85%

Memory:
- Keys stored: 50-200 typical
- Total size: 5-15MB
- Largest items: HTTP responses (500KB-1MB)
- Smallest items: Config values (< 1KB)
```

**Performance Characteristics:**
```
Operation     | Time     | Memory
--------------|----------|--------
cache_get     | < 1ms    | 0
cache_set     | < 2ms    | +value size
cache_delete  | < 0.5ms  | -value size
cache_clear   | < 5ms    | Reset to 0
```

### Change Impact Analysis

**If CACHE Changes:**
```
Direct Impact:
- HTTP_CLIENT (response caching may break)
- CONFIG (configuration caching may break)
- SECURITY (token caching may break)

Indirect Impact:
- Performance degradation (more API calls)
- Increased Lambda execution time
- Higher AWS costs (Parameter Store calls)
```

**If CACHE Dependencies Change:**
```
LOGGING changes:
- Update cache_core.py logging calls
- No interface changes needed
- Low risk

METRICS changes:
- Update cache_core.py metric calls
- May affect performance tracking
- Medium risk
```

---

## Related Topics

- **DEP-03**: Layer 2 - Storage & Monitoring (CACHE layer)
- **NM01-INT-01**: CACHE interface definition
- **HTTP-DEP**: HTTP_CLIENT dependencies (uses CACHE)
- **CONFIG-DEP**: CONFIG dependencies (uses CACHE)

---

## Keywords

CACHE dependencies, cache usage patterns, cache performance, HTTP caching, config caching, token caching, cache keys

---

## Version History

- **2025-10-24**: Atomized from NM02-CORE, migrated to v3.1.0 format
- **2024-04-15**: Original content in NM02-CORE-Dependency Layers

---

**File:** `NM02-Dependencies-InterfaceDetail_CACHE-DEP.md`
**Location:** `/nmap/NM02/`
**End of Document**

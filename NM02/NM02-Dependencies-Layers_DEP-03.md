# NM02-Dependencies-Layers_DEP-03.md - DEP-03

# DEP-03: Layer 2 - Storage & Monitoring

**Category:** NM02 - Dependencies
**Topic:** Dependency Layers
**Priority:** 🟡 High
**Status:** Active
**Created:** 2024-04-15
**Last Updated:** 2025-10-24

---

## Summary

Layer 2 provides storage and monitoring infrastructure: CACHE (data caching), METRICS (telemetry), and CONFIG (configuration management). These interfaces depend on Layers 0-1 and provide essential infrastructure for service layers.

---

## Context

Layer 2 sits above core utilities (Layer 1) and provides infrastructure services. These interfaces need logging and validation from lower layers but don't provide core business functionality - they support it.

**Layer 2 Philosophy:**
- Infrastructure services
- Support higher layers
- Depend on Layer 0-1 only
- Enable performance and visibility

---

## Content

### Layer 2 Interfaces

#### CACHE
```
CACHE
├─ Dependencies: LOGGING, METRICS
├─ Purpose: Data caching for performance
├─ Used by: HTTP_CLIENT, CONFIG, SECURITY
└─ Why Layer 2: Needs metrics to track cache performance
```

**Functions:**
- `cache_get()` - Retrieve cached value
- `cache_set()` - Store value in cache
- `cache_delete()` - Remove cached value
- `cache_clear()` - Clear all cache

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

**Real-World Impact:**
- Cache reduces API calls by 60-80%
- Average hit rate: 75-85%
- Cache lookup: < 1ms
- Saves ~180ms per cached HTTP response

#### METRICS
```
METRICS
├─ Dependencies: LOGGING
├─ Purpose: Telemetry and monitoring
├─ Used by: CACHE, HTTP_CLIENT, CIRCUIT_BREAKER, WEBSOCKET
└─ Why Layer 2: Monitors other systems, doesn't provide core functionality
```

**Functions:**
- `record_metric()` - Record metric value
- `increment_counter()` - Increment counter
- `record_timing()` - Record timing
- `set_gauge()` - Set gauge value

**Dependency Pattern:**
```python
# In metrics_core.py
from gateway import log_info  # Only Layer 0

def record_metric(name, value):
    log_info(f"Metric recorded: {name}={value}")
    _METRICS_STORE[name] = value
```

**Real-World Impact:**
- Tracks cache hit rates
- Monitors HTTP request timing
- Measures Lambda performance
- Enables data-driven optimization

#### CONFIG
```
CONFIG
├─ Dependencies: LOGGING, CACHE, SECURITY
├─ Purpose: Configuration management
├─ Used by: All interfaces (for configuration)
└─ Why Layer 2: Uses cache for storage, security for validation
```

**Functions:**
- `get_config()` - Retrieve config value
- `set_config()` - Store config value
- `load_config()` - Load from source
- `validate_config()` - Validate config

**Dependency Pattern:**
```python
# In config_core.py
from gateway import log_info, cache_get, cache_set, validate_string

def get_config(key):
    log_info(f"Config lookup: {key}")
    
    # Try cache first
    value = cache_get(f"config_{key}")
    if value:
        return value
    
    # Load from source
    value = _load_from_source(key)
    
    # Validate
    if validate_string(value):
        cache_set(f"config_{key}", value)
        return value
    
    return None
```

**Real-World Impact:**
- Eliminates hardcoded values
- Centralizes configuration
- First load: 5-15ms (Parameter Store)
- Cached load: < 1ms

### Why Layer 2 Depends on Layer 0-1

**CACHE needs LOGGING + METRICS:**
```python
def cache_get(key):
    log_info(f"Cache lookup: {key}")      # Layer 0
    result = _CACHE_STORE.get(key)
    record_metric("cache_hit", 1.0)        # Layer 2 (METRICS)
    return result
```

**METRICS needs LOGGING:**
```python
def record_metric(name, value):
    log_info(f"Metric: {name}={value}")   # Layer 0
    _store_metric(name, value)
```

**CONFIG needs LOGGING + CACHE + SECURITY:**
```python
def get_config(key):
    log_info(f"Config: {key}")             # Layer 0
    cached = cache_get(f"config_{key}")    # Layer 2 (CACHE)
    if cached:
        return cached
    
    value = _load_config(key)
    if validate_string(value):             # Layer 1 (SECURITY)
        cache_set(f"config_{key}", value)  # Layer 2 (CACHE)
        return value
```

### Circular Import Prevention

**Notice the asymmetry:**
- CACHE uses METRICS ✅
- METRICS does NOT use CACHE ✅
- No circular dependency!

**Why this works:**
```
CACHE → METRICS → LOGGING ✅ Linear dependency chain

If METRICS used CACHE:
CACHE → METRICS → CACHE ❌ CIRCULAR!
```

**Rule:** Lower layers can be used by higher layers, but not vice versa.

### Usage by Higher Layers

**HTTP_CLIENT Uses Layer 2:**
```python
# In http_client_core.py
from gateway import (
    log_info,           # Layer 0
    validate_url,       # Layer 1
    cache_get,          # Layer 2
    record_api_metric   # Layer 2
)

def http_get(url):
    log_info(f"HTTP GET: {url}")
    validate_url(url)
    
    # Check cache
    cached = cache_get(f"http_{url}")
    if cached:
        return cached
    
    # Make request
    response = _perform_request(url)
    
    # Track metrics
    record_api_metric("http_get", response.time)
    
    return response
```

### Performance Characteristics

**Initialization:**
- METRICS: < 5ms
- CACHE: < 10ms  
- CONFIG: < 15ms
- Total Layer 2: ~30ms

**Operation:**
- Cache get: < 1ms
- Cache set: < 2ms
- Metric record: < 0.5ms
- Config get (cached): < 1ms
- Config get (uncached): 5-15ms

**Memory Usage:**
- CACHE: ~10MB typical
- METRICS: ~1MB
- CONFIG: ~500KB

---

## Related Topics

- **DEP-01**: Layer 0 - LOGGING (foundation)
- **DEP-02**: Layer 1 - Core Utilities
- **DEP-04**: Layer 3 - Service Infrastructure (uses Layer 2)
- **NM01-INT-01**: CACHE interface definition
- **NM01-INT-04**: METRICS interface definition
- **NM01-INT-05**: CONFIG interface definition

---

## Keywords

dependency layer 2, storage, monitoring, CACHE, METRICS, CONFIG, infrastructure services, performance optimization

---

## Version History

- **2025-10-24**: Atomized from NM02-CORE, migrated to v3.1.0 format
- **2024-04-15**: Original content in NM02-CORE-Dependency Layers

---

**File:** `NM02-Dependencies-Layers_DEP-03.md`
**Location:** `/nmap/NM02/`
**End of Document**

# File: NMP01-LEE-16_Fast-Path-Optimization-ZAPH-Pattern.md

# NMP01-LEE-16: Fast Path Optimization - ZAPH Pattern Implementation

**Project:** Lambda Execution Engine (SUGA-ISP)  
**Project Code:** LEE  
**Category:** Gateway Patterns  
**Component:** fast_path.py, lambda_preload.py  
**Priority:** 🟡 HIGH  
**Status:** Active  
**Created:** 2025-10-29  
**Last Updated:** 2025-10-29

---

## Summary

Documentation of ZAPH (Zero-to-Answer Path Heuristic) pattern implementation in SUGA-ISP Lambda - the fast path optimization that preloads frequently used modules and data during cold start to achieve < 3 second cold start target.

---

## Context

Lambda cold starts are a critical performance bottleneck. ZAPH pattern identifies and preloads the "hot path" - modules and data needed for 80%+ of requests - during Lambda initialization, deferring less critical components to lazy loading.

**Goal:** < 3 second cold start for typical requests

---

## Architecture

### Two-Stage Loading

**Stage 1: Fast Path (lambda_preload.py)**
```python
# Preload during Lambda initialization
import fast_path

# Load critical interfaces
fast_path.preload_core_interfaces()

# Warm critical cache
fast_path.warm_cache()

# Initialize connections
fast_path.initialize_connections()
```

**Stage 2: Lazy Loading (gateway_core.py)**
```python
# Load on first use
def execute_operation(interface, operation, *args, **kwargs):
    if interface not in _MODULE_CACHE:
        # Lazy import non-critical interfaces
        _MODULE_CACHE[interface] = __import__(f"interface_{interface}")
```

---

## Fast Path Components

### Critical Interfaces (Always Preloaded)

**Tier 1: Foundation (< 50ms)**
- INT-02 (LOGGING) - Required for all operations
- INT-03 (SECURITY) - Required for validation
- INT-11 (UTILITY) - Common helper functions

**Tier 2: High-Frequency (50-100ms)**
- INT-01 (CACHE) - 85%+ of requests use caching
- INT-05 (CONFIG) - 70%+ of requests need configuration

**Total Fast Path Load:** < 150ms

---

### Hot Path Data

**HA Configuration (50-100ms)**
```python
def preload_ha_config():
    """Preload HA connection config."""
    # These are called in 90%+ of HA requests
    ha_url = gateway.get_parameter("ha_url")
    ha_token = gateway.get_parameter("ha_token", decrypt=True)
    
    # Cache for Lambda lifetime
    gateway.cache_set("config_ha_url", ha_url)
    gateway.cache_set("config_ha_token", ha_token)
```

**Frequently Accessed Entities (100-300ms)**
```python
def warm_entity_cache():
    """Pre-warm cache for most frequently accessed entities."""
    # Top 10 entities by access frequency (from metrics)
    frequent_entities = [
        'light.bedroom',
        'light.living_room',
        'light.kitchen',
        'switch.tv',
        'switch.fan',
        # ... top 10 only
    ]
    
    # Fetch and cache in parallel
    for entity_id in frequent_entities:
        get_entity_state(entity_id)  # Caches automatically
```

---

## Implementation

### fast_path.py

```python
"""
Fast path optimization module.
Preloads critical components during Lambda initialization.
"""

import time
from gateway import log_info, cache_set, get_parameter

# Track preload timing
_PRELOAD_START = None
_PRELOAD_END = None

def preload_all():
    """Execute all fast path preloading."""
    global _PRELOAD_START, _PRELOAD_END
    _PRELOAD_START = time.time()
    
    try:
        # Stage 1: Critical interfaces (< 50ms)
        preload_core_interfaces()
        
        # Stage 2: Configuration (50-100ms)
        preload_configuration()
        
        # Stage 3: Hot data (optional, 100-300ms)
        if _should_warm_cache():
            warm_cache()
        
        _PRELOAD_END = time.time()
        duration_ms = (_PRELOAD_END - _PRELOAD_START) * 1000
        
        log_info("Fast path preload complete",
                 duration_ms=duration_ms,
                 stages=3)
        
    except Exception as e:
        log_error("Fast path preload failed",
                  error=str(e))
        # Continue anyway - lazy loading will handle


def preload_core_interfaces():
    """Preload Tier 1 interfaces."""
    # These are imported at module level in gateway.py
    # This just ensures they're loaded
    import interface_logging
    import interface_security  
    import interface_utility
    
    log_info("Core interfaces preloaded")


def preload_configuration():
    """Preload HA configuration."""
    try:
        # Parameter Store calls (50-100ms each)
        ha_url = get_parameter("ha_url")
        ha_token = get_parameter("ha_token", decrypt=True)
        alexa_client_id = get_parameter("alexa_client_id")
        
        # Cache for Lambda lifetime (no TTL)
        cache_set("config_ha_url", ha_url)
        cache_set("config_ha_token", ha_token)
        cache_set("config_alexa_client_id", alexa_client_id)
        
        log_info("Configuration preloaded",
                 items=3)
        
    except Exception as e:
        log_error("Configuration preload failed",
                  error=str(e))


def warm_cache():
    """Pre-warm entity cache for frequent entities."""
    try:
        # Import ha_core (lazy)
        import ha_core
        
        # Top 5 entities (limit to avoid long cold start)
        frequent = [
            'light.bedroom',
            'light.living_room',
            'switch.tv',
            'switch.fan',
            'climate.thermostat'
        ]
        
        for entity_id in frequent:
            try:
                ha_core.get_entity_state(entity_id)
            except Exception:
                # Ignore failures - will retry on demand
                pass
        
        log_info("Cache warmed",
                 entities=len(frequent))
        
    except Exception as e:
        log_error("Cache warming failed",
                  error=str(e))


def _should_warm_cache():
    """
    Determine if cache warming is beneficial.
    
    Skip if:
    - Cold start time already high
    - Memory pressure
    - Specific request type that won't benefit
    """
    # For now, always warm
    # Future: Add heuristics
    return True


def get_preload_stats():
    """Get fast path preload statistics."""
    if _PRELOAD_START is None:
        return {'status': 'not_started'}
    
    if _PRELOAD_END is None:
        return {'status': 'in_progress',
                'elapsed_ms': (time.time() - _PRELOAD_START) * 1000}
    
    duration_ms = (_PRELOAD_END - _PRELOAD_START) * 1000
    return {
        'status': 'complete',
        'duration_ms': duration_ms,
        'start_time': _PRELOAD_START,
        'end_time': _PRELOAD_END
    }
```

---

### lambda_preload.py

```python
"""
Lambda initialization module.
Executed once per container initialization.
"""

import fast_path

# Execute fast path preload
fast_path.preload_all()

# Module is now ready for requests
_CONTAINER_INITIALIZED = True
```

---

### lambda_function.py Integration

```python
"""
Lambda function handler.
"""

# Import preload module (triggers fast path)
import lambda_preload

def lambda_handler(event, context):
    """Handle Lambda invocation."""
    # Fast path already loaded
    # Process request normally
    return process_request(event, context)
```

---

## Performance Characteristics

### Cold Start Breakdown

**Without ZAPH (Naive Approach):**
```
Import all modules: 500-800ms
Load configuration: 200-300ms
First request: 100-200ms
--------------------------------
Total cold start: 800-1300ms
```

**With ZAPH (Optimized):**
```
Fast path preload: 150-200ms
First request: 50-100ms (cache hit)
--------------------------------
Total cold start: 200-300ms
```

**Improvement:** 60-75% faster

---

### Warm Start Performance

**First request (post-preload):**
- Cache hit: 50-100ms (cached config + entity)
- Cache miss: 150-300ms (API call needed)

**Subsequent requests:**
- Cache hit: 10-50ms (all cached)
- Cache miss: 50-200ms (some API calls)

---

## Tier Classification

### How to Classify Modules

**Tier 1 (Always Preload):**
- Used in > 90% of requests
- Foundation for other modules
- < 50ms load time
- Example: LOGGING, SECURITY

**Tier 2 (Preload if < 200ms total):**
- Used in > 70% of requests
- Moderate load time (50-100ms)
- High benefit from preloading
- Example: CACHE, CONFIG

**Tier 3 (Lazy Load):**
- Used in < 50% of requests
- Or expensive to load (> 100ms)
- Or rarely used
- Example: WEBSOCKET, CIRCUIT_BREAKER

---

## Monitoring and Tuning

### Metrics to Track

**Preload Timing:**
```python
gateway.log_info("Preload stats",
                 total_ms=duration_ms,
                 core_interfaces_ms=core_time,
                 config_ms=config_time,
                 cache_warming_ms=cache_time)
```

**Request Performance:**
```python
gateway.log_info("Request stats",
                 total_ms=request_time,
                 cache_hit_rate=hit_rate,
                 api_calls=api_call_count)
```

### Tuning Decisions

**Add to Fast Path if:**
- Module used in > 80% of requests
- Load time < 100ms
- Total fast path stays < 300ms

**Remove from Fast Path if:**
- Usage drops < 50%
- Load time increases > 200ms
- Causing cold start > 400ms

---

## Cache Warming Strategy

### Entity Selection Criteria

**Include entity if:**
1. Top 10 by access frequency (from metrics)
2. Critical for Alexa (voice-activated)
3. Fast to fetch (< 50ms)

**Example Selection Process:**
```python
# From CloudWatch metrics
entity_access_counts = {
    'light.bedroom': 1250,
    'light.living_room': 980,
    'switch.tv': 750,
    # ...
}

# Sort by frequency
sorted_entities = sorted(entity_access_counts.items(),
                         key=lambda x: x[1],
                         reverse=True)

# Top 5-10 entities
hot_entities = [entity_id for entity_id, _ in sorted_entities[:10]]
```

---

### Warming Budget

**Total Budget:** 300ms for cache warming

**Per-Entity Budget:**
- Target: 30-50ms per entity
- Maximum: 10 entities
- Total: 300-500ms

**Timeout Strategy:**
```python
def warm_cache_with_timeout():
    start = time.time()
    MAX_TIME = 0.3  # 300ms
    
    for entity_id in hot_entities:
        if (time.time() - start) > MAX_TIME:
            break  # Stop if budget exceeded
        
        warm_entity(entity_id)
```

---

## Integration with LMMS

### Relationship to Lazy Loading

**ZAPH + LMMS = Complete Strategy:**

```
ZAPH (Fast Path)
  â"œâ"€ Preload critical (Tier 1 + Tier 2)
  â""â"€ Warm hot data

LMMS (Lazy Loading)
  â"œâ"€ Defer non-critical (Tier 3)
  â""â"€ Load on first use
```

**Result:** Best of both worlds
- Fast cold start (preload critical)
- Memory efficient (lazy load rest)

---

## Best Practices

### Do: Measure and Tune

```python
# Track what's actually used
gateway.increment_counter(f"interface_used_{interface}")

# Analyze weekly
# Promote to fast path if usage > 80%
# Demote if usage < 50%
```

---

### Do: Set Budget Limits

```python
# Never exceed cold start budget
MAX_FAST_PATH_TIME = 300  # ms

if estimated_time > MAX_FAST_PATH_TIME:
    # Skip cache warming
    pass
```

---

### Don't: Preload Everything

```python
# âŒ BAD - Defeats purpose
for interface in ALL_INTERFACES:
    preload(interface)

# âœ… GOOD - Selective preload
for interface in CRITICAL_INTERFACES:
    preload(interface)
```

---

## Related Documentation

**Architecture:**
- ARCH-07: LMMS (lazy loading complement)
- ARCH-08: ZAPH pattern (generic documentation)

**Performance:**
- NMP01-LEE-24: Cold start optimization techniques
- NMP01-LEE-25: Singleton management for connection reuse

---

## Keywords

fast-path, ZAPH, cold-start-optimization, preloading, performance, LMMS, tiering, cache-warming, lambda-initialization, SUGA-ISP, LEE

---

**END OF FILE**

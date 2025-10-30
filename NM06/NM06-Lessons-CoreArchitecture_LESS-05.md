# NM06-Lessons-CoreArchitecture_LESS-05.md - LESS-05

# LESS-05: Graceful Degradation

**Category:** Lessons  
**Topic:** CoreArchitecture  
**Priority:** HIGH  
**Status:** Active  
**Created:** 2025-10-23  
**Last Updated:** 2025-10-23

---

## Summary

Systems should degrade gracefully, not catastrophically. One interface failure shouldn't crash the entire system. Partial functionality beats total failure.

---

## Context

After the cascading failure bug (BUG-03), we learned that one interface failure shouldn't crash the entire system. Better to provide degraded service than no service.

---

## Content

### The Problem Pattern

**Brittle: Single point of failure**
```python
def lambda_handler(event, context):
    # If SSM fails, everything fails
    config = gateway.ssm_get_parameter('/config')  # Dies here
    
    # Never reached if SSM fails
    user_data = gateway.cache_get('user:123')
    return process_request(config, user_data)
```

**What happens:** SSM timeout → Exception → Lambda crashes → ALL requests fail

### The Solution Pattern

**Layer 1: Interface-Level Fallbacks**
```python
def execute_ssm_operation(operation, **kwargs):
    try:
        return _execute_get_parameter_implementation(**kwargs)
    except Timeout:
        gateway.log_error(f"SSM timeout for {kwargs.get('name')}")
        return _get_default_value(**kwargs)
    except Exception as e:
        gateway.log_error(f"SSM error: {e}")
        return None  # Fail softly
```

**Layer 2: Application-Level Fallbacks**
```python
def load_configuration():
    # Try SSM first (preferred)
    config = gateway.ssm_get_parameter('/config')
    
    if config:
        return config
    
    # Fall back to environment variables
    gateway.log_warn("Using environment variable fallback")
    return {
        'timeout': int(os.environ.get('TIMEOUT', 30)),
        'retries': int(os.environ.get('RETRIES', 3)),
    }
```

### Degradation Hierarchy

**Critical → Important → Optional → Nice-to-Have**

```python
# CRITICAL: Must work or request fails
user_id = event['user_id']
if not user_id:
    raise ValueError("user_id required")

# IMPORTANT: Should work, fallback available
config = gateway.ssm_get_parameter('/config') or DEFAULT_CONFIG

# OPTIONAL: Best effort, continue without
cached_result = gateway.cache_get(cache_key)
if cached_result:
    return cached_result
# Continue without cache
fresh_result = compute_result()
return fresh_result

# NICE-TO-HAVE: Silent failure okay
try:
    gateway.metrics_record('request_count', 1)
except Exception:
    pass  # Don't care if metrics fail
```

### Error Handling Tiers

**Tier 1: Silent Degradation** (non-critical)
```python
try:
    gateway.metrics_record('api_call', 1)
except Exception:
    pass
```

**Tier 2: Logged Degradation** (important but non-critical)
```python
try:
    result = gateway.cache_get(key)
    return result
except Exception as e:
    gateway.log_error(f"Cache failed: {e}")
    return None
```

**Tier 3: Fallback Degradation** (critical with alternatives)
```python
try:
    return primary_source()
except Exception as e:
    gateway.log_error(f"Primary failed: {e}, using fallback")
    return fallback_source()
```

**Tier 4: Fail Fast** (truly critical, no alternatives)
```python
if not critical_data:
    raise ValueError("Critical data missing, cannot proceed")
```

### Key Principles

**1. Partial functionality > No functionality**

**2. Degrade towards safe defaults**
```python
# ✅ Good: Safe default
timeout = config.get('timeout') or 30

# ❌ Bad: Unsafe default
timeout = config.get('timeout') or None  # Could hang forever
```

**3. Log degradation events**
```python
if using_fallback:
    gateway.log_warn("Using fallback configuration")
```

**4. Test degraded paths**
```python
def test_cache_failure_fallback():
    with patch('gateway.cache_get', side_effect=Exception()):
        result = get_user_data('user123')
        assert result is not None  # Should still work
```

### Real-World Impact

**Before graceful degradation:**
- SSM timeout → Lambda crash → 100% outage

**After graceful degradation:**
- SSM timeout → Use fallback → Slower but working
- Availability: 99.0% → 99.95%

---

## Related Topics

- **DEC-15**: Graceful degradation (implementation details)
- **BUG-03**: Cascading failure (what taught this lesson)
- **PATH-02**: Error pathways

---

## Keywords

graceful degradation, partial function, resilience, error handling, fallback

---

## Version History

- **2025-10-23**: Created - Migrated to SIMA v3 individual file format
- **2025-10-15**: Original documentation in NM06-LESSONS-Core Architecture Lessons.md

---

**File:** `NM06-Lessons-CoreArchitecture_LESS-05.md`  
**Directory:** NM06/  
**End of Document**

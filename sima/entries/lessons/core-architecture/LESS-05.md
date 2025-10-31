# File: LESS-05.md

**REF-ID:** LESS-05  
**Category:** Lessons Learned  
**Topic:** Core Architecture  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Status:** Production

---

## Title

Graceful Degradation Required

---

## Priority

HIGH

---

## Summary

Systems should degrade gracefully, not catastrophically. One module failure shouldn't crash the entire system. Partial functionality beats total failure.

---

## Context

After cascading failure incidents, we learned that one module failure shouldn't crash the entire system. Better to provide degraded service than no service.

---

## Lesson

### The Problem Pattern

**Brittle: Single point of failure**
```python
def handle_request(event):
    # If config service fails, everything fails
    config = get_configuration()  # Dies here
    
    # Never reached if config fails
    user_data = get_user_cache('user:123')
    return process_request(config, user_data)
```

**What happens:** Config timeout → Exception → System crash → ALL requests fail

### The Solution Pattern

**Layer 1: Module-Level Fallbacks**
```python
def get_configuration():
    try:
        return _fetch_from_service()
    except Timeout:
        log_error("Config service timeout")
        return _get_default_config()
    except Exception as e:
        log_error(f"Config error: {e}")
        return None  # Fail softly
```

**Layer 2: Application-Level Fallbacks**
```python
def load_configuration():
    # Try primary source
    config = get_configuration()
    
    if config:
        return config
    
    # Fall back to environment variables
    log_warn("Using environment variable fallback")
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
config = get_configuration() or DEFAULT_CONFIG

# OPTIONAL: Best effort, continue without
cached_result = get_from_cache(cache_key)
if cached_result:
    return cached_result
# Continue without cache
fresh_result = compute_result()
return fresh_result

# NICE-TO-HAVE: Silent failure okay
try:
    record_metric('request_count', 1)
except Exception:
    pass  # Don't care if metrics fail
```

### Error Handling Tiers

**Tier 1: Silent Degradation** (non-critical)
```python
try:
    record_metric('api_call', 1)
except Exception:
    pass
```

**Tier 2: Logged Degradation** (important but non-critical)
```python
try:
    result = get_from_cache(key)
    return result
except Exception as e:
    log_error(f"Cache failed: {e}")
    return None
```

**Tier 3: Fallback Degradation** (critical with alternatives)
```python
try:
    return primary_source()
except Exception as e:
    log_error(f"Primary failed: {e}, using fallback")
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
    log_warn("Using fallback configuration")
```

**4. Test degraded paths**
```python
def test_cache_failure_fallback():
    with patch('get_from_cache', side_effect=Exception()):
        result = get_user_data('user123')
        assert result is not None  # Should still work
```

### Real-World Impact

**Before graceful degradation:**
- Service timeout → System crash → 100% outage

**After graceful degradation:**
- Service timeout → Use fallback → Slower but working
- Availability: 99.0% → 99.95%

---

## Related

**Cross-References:**
- DEC-15: Graceful degradation implementation
- BUG-03: Cascading failure example
- LESS-08: Test failure paths

**Keywords:** graceful degradation, partial function, resilience, error handling, fallback

---

## Version History

- **4.0.0** (2025-10-30): Genericized for SIMAv4
- **3.0.0** (2025-10-23): Initial SIMAv3 format

---

**END OF FILE**

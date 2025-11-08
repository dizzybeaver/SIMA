# LESS-08-Test-Failure-Paths.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** SUGA architecture lesson - testing failure paths in gateway systems  
**Category:** Lesson  
**Architecture:** SUGA (Gateway Pattern)

---

## Title

Test Failure Paths in SUGA Layers

---

## Priority

HIGH

---

## Summary

In SUGA architecture, most bugs hide in layer boundaries and error propagation, not the happy path. Test import failures, interface errors, core failures, and cross-layer error handling - that's where SUGA bugs live.

---

## Context

SUGA's three layers and lazy imports create multiple failure points. Bugs often hide in interface import failures, core exceptions not caught by interface, and gateway error handling gaps.

---

## Lesson

### The SUGA Problem

**Typical incomplete SUGA test:**
```python
def test_cache_get():
    gateway.cache_set("key", "value")
    result = gateway.cache_get("key")
    assert result == "value"
    
    # ❌ Doesn't test:
    # - What if interface_cache import fails?
    # - What if cache_core import fails?
    # - What if core raises exception?
    # - What if interface doesn't catch exception?
    # - What if gateway doesn't handle interface error?
```

**What this misses:**
- Lazy import failures (SUGA-specific!)
- Layer boundary errors
- Exception propagation
- Graceful degradation
- Cross-layer error handling

### The SUGA Solution

**Comprehensive SUGA test coverage:**

**Test 1: Gateway Success Path**
```python
def test_gateway_cache_hit():
    gateway.cache_set("key", "value")
    result = gateway.cache_get("key")
    assert result == "value"
```

**Test 2: Gateway Interface Import Failure**
```python
def test_gateway_interface_import_fails():
    with patch('gateway_wrappers.interface_cache', side_effect=ImportError()):
        result = gateway.cache_get("key")
        assert result is None  # Should degrade gracefully
```

**Test 3: Interface Core Import Failure**
```python
def test_interface_core_import_fails():
    with patch('interface_cache.cache_core', side_effect=ImportError()):
        result = interface_cache.get("key")
        assert result is None  # Should degrade gracefully
```

**Test 4: Core Exception (Interface Should Catch)**
```python
def test_core_exception_caught_by_interface():
    with patch('cache_core._CACHE.get', side_effect=Exception("Core error")):
        result = interface_cache.get("key")
        assert result is None  # Interface catches and degrades
```

**Test 5: Interface Exception (Gateway Should Catch)**
```python
def test_interface_exception_caught_by_gateway():
    with patch('interface_cache.get', side_effect=Exception("Interface error")):
        result = gateway.cache_get("key")
        assert result is None  # Gateway catches and degrades
```

**Test 6: Full Layer Cascade Failure**
```python
def test_full_cascade_failure():
    # Core fails
    with patch('cache_core._CACHE', side_effect=Exception()):
        # Interface should catch
        # Gateway should catch
        result = gateway.cache_get("key", default="fallback")
        assert result == "fallback"  # Degrades to default
```

### SUGA Failure Path Categories

**1. Import Failures (SUGA-Specific)**
```python
# Test lazy import failure at gateway
def test_gateway_lazy_import_fails():
    with patch('builtins.__import__', side_effect=ImportError()):
        result = gateway.cache_get("key")
        assert result is None

# Test lazy import failure at interface
def test_interface_lazy_import_fails():
    with patch('builtins.__import__', side_effect=ImportError()):
        result = interface_cache.get("key")
        assert result is None
```

**2. Layer Boundary Failures**
```python
# Test exception doesn't propagate through interface
def test_core_exception_stops_at_interface():
    with patch('cache_core.get_impl', side_effect=ValueError()):
        result = interface_cache.get("key")
        assert result is None  # Doesn't raise

# Test exception doesn't propagate through gateway
def test_interface_exception_stops_at_gateway():
    with patch('interface_cache.get', side_effect=RuntimeError()):
        result = gateway.cache_get("key")
        assert result is None  # Doesn't raise
```

**3. Cross-Layer Error Handling**
```python
# Test gateway handles all interface errors
def test_gateway_handles_all_interface_errors():
    for error_type in [ValueError, TypeError, RuntimeError, Exception]:
        with patch('interface_cache.get', side_effect=error_type()):
            result = gateway.cache_get("key")
            assert result is None  # Always degrades gracefully
```

**4. SUGA Edge Cases**
```python
# Test None vs missing distinction across layers
def test_none_value_through_layers():
    gateway.cache_set("key", None)
    result = gateway.cache_get("key")
    assert result is None  # Stored None
    
    result = gateway.cache_get("missing_key")
    assert result is None  # Missing key
    # Both return None, but for different reasons

# Test empty string across layers
def test_empty_string_through_layers():
    gateway.cache_set("key", "")
    result = gateway.cache_get("key")
    assert result == ""  # Not None!
```

### The SUGA Testing Checklist

**For each gateway function, test:**
- ✅ Success path (all layers work)
- ✅ Interface import failure
- ✅ Core import failure  
- ✅ Core exception (interface catches)
- ✅ Interface exception (gateway catches)
- ✅ Full cascade failure
- ✅ Invalid inputs at gateway
- ✅ None/empty values through layers

**For each interface function, test:**
- ✅ Success path (interface + core work)
- ✅ Core import failure
- ✅ Core exception (interface catches)
- ✅ Invalid inputs at interface
- ✅ None/empty values

**For each core function, test:**
- ✅ Success path (core works)
- ✅ Invalid inputs at core
- ✅ Edge cases (None, empty, boundaries)
- ✅ Internal exceptions

### Real-World SUGA Impact

**Example:**
- If we had tested interface import failure: Bug caught BEFORE production
- Coverage before (success-only): 80% code, 20% SUGA scenarios
- Coverage after (including failures): 95% code, 90% SUGA scenarios
- SUGA-specific bugs: Reduced by 85%

---

## SUGA-Specific Test Patterns

### Pattern 1: Layer-by-Layer Testing

```python
# Test core independently
def test_cache_core_independently():
    result = cache_core.get_impl("key")
    assert result is None

# Test interface with mocked core
def test_interface_with_mocked_core():
    with patch('cache_core.get_impl', return_value="value"):
        result = interface_cache.get("key")
        assert result == "value"

# Test gateway with mocked interface
def test_gateway_with_mocked_interface():
    with patch('interface_cache.get', return_value="value"):
        result = gateway.cache_get("key")
        assert result == "value"
```

### Pattern 2: Cascade Testing

```python
# Test error stops at each layer
def test_error_cascade_stops():
    # Core error
    with patch('cache_core.get_impl', side_effect=Exception()):
        # Should stop at interface
        with pytest.raises(Exception):
            cache_core.get_impl("key")
        
        # Should NOT propagate through interface
        result = interface_cache.get("key")
        assert result is None
        
        # Should NOT propagate through gateway
        result = gateway.cache_get("key")
        assert result is None
```

### Pattern 3: Lazy Import Testing

```python
# Test lazy import timing
def test_lazy_import_timing():
    # Before import
    assert 'interface_cache' not in sys.modules
    
    # Call gateway (triggers lazy import)
    gateway.cache_get("key")
    
    # After import
    assert 'interface_cache' in sys.modules
    assert 'cache_core' in sys.modules

# Test lazy import failure doesn't affect other functions
def test_lazy_import_isolation():
    with patch('builtins.__import__', side_effect=ImportError()):
        # This gateway function fails
        result1 = gateway.cache_get("key")
        assert result1 is None
    
    # Other gateway functions still work
    result2 = gateway.log_info("test")
    assert result2 is None  # Success (no exception)
```

---

## Related

**SUGA Architecture:**
- ARCH-01: Gateway trinity - test all three layers
- ARCH-02: Layer separation - test boundaries
- GATE-02: Lazy imports - test import failures

**SUGA Lessons:**
- LESS-05: Graceful degradation - test degradation works
- LESS-03: Gateway entry point - test entry failures

**SUGA Anti-Patterns:**
- AP-04: Skipping interface layer - makes testing harder

**Generic Patterns:**
- LESS-08: Generic failure testing (parent)
- AP-24: Testing only success paths

**Keywords:** failure testing, SUGA layers, import failures, layer boundaries, exception propagation, lazy import testing, cascade testing

---

## Version History

- **1.0.0** (2025-11-06): Created SUGA-specific lesson from LESS-08
  - Adapted for SUGA three-layer testing
  - Added lazy import failure tests
  - Included layer boundary test patterns

---

**END OF FILE**

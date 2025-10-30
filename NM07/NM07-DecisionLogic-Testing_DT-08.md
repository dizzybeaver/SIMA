# NM07-DecisionLogic-Testing_DT-08.md - DT-08

# DT-08: What Should I Test?

**Category:** Decision Logic
**Topic:** Testing
**Priority:** High
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Decision tree for test coverage priorities - what must be tested, what should be tested, and what is optional based on feature complexity and risk.

---

## Context

Comprehensive testing requires prioritization. Critical paths and error handling must be tested, while performance and load testing are optional depending on risk assessment.

---

## Content

### Test Priority Decision Tree

```
START: Writing tests for feature X
│
├─ MUST TEST: Success path
│  Tests: Happy path, expected inputs
│  Coverage: Main functionality works
│
├─ MUST TEST: Failure path
│  Tests: Invalid inputs, errors
│  Coverage: Graceful error handling
│
├─ MUST TEST: Edge cases
│  Tests: Boundary values, empty inputs, None
│  Coverage: Corner cases handled
│
├─ SHOULD TEST: Integration
│  Tests: Cross-interface interactions
│  Coverage: Interfaces work together
│
├─ OPTIONAL: Performance
│  Tests: Time/memory bounds
│  Coverage: Performance regressions
│
└─ OPTIONAL: Load/Stress
   Tests: High volume, concurrent
   Coverage: System limits
   → END
```

### Test Priority Matrix

| Test Type | Priority | Example |
|-----------|----------|---------|
| Success path | MUST | cache_get returns value |
| Failure path | MUST | cache_get returns None on miss |
| Invalid input | MUST | cache_set raises on bad input |
| Edge cases | MUST | cache_get with None key |
| Integration | SHOULD | HTTP uses cache correctly |
| Performance | OPTIONAL | cache_get <1ms |
| Load testing | OPTIONAL | 1000 operations/sec |

### Test Types Explained

**MUST TEST - Success Path:**
```python
def test_cache_get_success():
    """Test successful cache retrieval."""
    gateway.cache_set("key", "value")
    result = gateway.cache_get("key")
    assert result == "value"
```

**MUST TEST - Failure Path:**
```python
def test_cache_get_miss():
    """Test cache miss returns None."""
    result = gateway.cache_get("nonexistent_key")
    assert result is None

def test_cache_set_invalid_key():
    """Test invalid key raises ValueError."""
    with pytest.raises(ValueError):
        gateway.cache_set("", "value")
```

**MUST TEST - Edge Cases:**
```python
def test_cache_edge_cases():
    """Test edge cases and boundary conditions."""
    # Empty key
    with pytest.raises(ValueError):
        gateway.cache_set("", "value")
    
    # None value
    gateway.cache_set("key", None)
    assert gateway.cache_get("key") is None
    
    # Large value
    large_value = "x" * 1_000_000
    gateway.cache_set("key", large_value)
    assert gateway.cache_get("key") == large_value
    
    # Special characters in key
    gateway.cache_set("key:with:colons", "value")
    assert gateway.cache_get("key:with:colons") == "value"
```

**SHOULD TEST - Integration:**
```python
def test_http_cache_integration():
    """Test HTTP request caching integration."""
    # First request - cache miss
    response1 = gateway.http_get("https://api.example.com/data")
    
    # Second request - cache hit
    response2 = gateway.http_get("https://api.example.com/data")
    
    # Should be cached
    assert response1 == response2
```

**OPTIONAL - Performance:**
```python
def test_cache_get_performance():
    """Test cache_get performance."""
    import time
    
    gateway.cache_set("key", "value")
    
    start = time.time()
    for _ in range(1000):
        gateway.cache_get("key")
    duration = time.time() - start
    
    # Should be < 1ms per operation
    assert duration / 1000 < 0.001
```

**OPTIONAL - Load/Stress:**
```python
def test_cache_load():
    """Test cache under high load."""
    # Set 10,000 items
    for i in range(10_000):
        gateway.cache_set(f"key{i}", f"value{i}")
    
    # Verify all retrievable
    for i in range(10_000):
        assert gateway.cache_get(f"key{i}") == f"value{i}"
```

### Test Coverage Guidelines

**For Simple Functions (<50 lines):**
- MUST: Success path
- MUST: 1-2 failure paths
- MUST: 2-3 edge cases

**For Complex Functions (50-200 lines):**
- MUST: Success path with variations
- MUST: 3-5 failure paths
- MUST: 5+ edge cases
- SHOULD: Integration tests

**For Critical Functions (security, data integrity):**
- MUST: Comprehensive success paths
- MUST: All failure paths
- MUST: All edge cases
- MUST: Integration tests
- SHOULD: Performance tests

### Real-World Usage Pattern

**User Query:** "What tests should I write for my new function?"

**Search Terms:** "what to test priority"

**Decision Flow:**
1. Success path? MUST TEST
2. Failure paths? MUST TEST
3. Edge cases? MUST TEST
4. Integration needed? SHOULD TEST
5. Performance critical? OPTIONAL TEST
6. **Response:** "MUST: Success path, failure path, edge cases. SHOULD: Integration. OPTIONAL: Performance."

### Test Organization Pattern

```python
class TestCacheOperations:
    """Organized test suite for cache operations."""
    
    # MUST TESTS - Success
    def test_get_returns_value(self):
        pass
    
    def test_set_stores_value(self):
        pass
    
    # MUST TESTS - Failure
    def test_get_missing_key_returns_none(self):
        pass
    
    def test_set_invalid_key_raises(self):
        pass
    
    # MUST TESTS - Edge Cases
    def test_get_with_none_key(self):
        pass
    
    def test_set_large_value(self):
        pass
    
    # SHOULD TESTS - Integration
    def test_cache_with_http(self):
        pass
    
    # OPTIONAL TESTS - Performance
    def test_get_performance(self):
        pass
```

---

## Related Topics

- **AP-23**: Testing anti-patterns (insufficient coverage)
- **AP-24**: No error path tests
- **DEC-18**: Test strategy
- **DT-09**: Mocking strategy
- **LESS-07**: Test integration patterns

---

## Keywords

what to test, test coverage, testing strategy, test priorities, success path, failure path, edge cases

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-Testing_DT-08.md`
**End of Document**

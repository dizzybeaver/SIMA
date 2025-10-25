# NM07-DecisionLogic-Testing_DT-09.md - DT-09

# DT-09: How Much to Mock?

**Category:** Decision Logic
**Topic:** Testing
**Priority:** Medium
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Decision tree for mocking strategies - determining appropriate mocking levels for unit tests (mock heavily), integration tests (mock selectively), and E2E tests (mock only external).

---

## Context

Different test types require different mocking strategies. Unit tests need isolation, integration tests need authentic interactions, and E2E tests need realistic end-to-end flows.

---

## Content

### Mocking Decision Tree

```
START: Writing test, need mocking
│
├─ Q: Testing single function in isolation?
│  ├─ YES → Mock dependencies heavily
│  │      Example: Mock all gateway calls
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Testing integration between components?
│  ├─ YES → Mock sparingly (only external)
│  │      Example: Mock only HTTP calls, not cache
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Testing end-to-end flow?
│  ├─ YES → Mock only external services
│  │      Example: Mock Home Assistant API
│  │      → END
│  │
│  └─ NO → Continue
│
└─ Q: Testing performance/load?
   └─ YES → No mocking (real components)
          Example: Measure actual cache performance
          → END
```

### Mocking Strategy Matrix

| Test Type | Mock Level | What to Mock | What NOT to Mock |
|-----------|------------|--------------|------------------|
| Unit | Heavy | All dependencies | Nothing (full isolation) |
| Integration | Selective | External services only | Internal interfaces |
| E2E | Minimal | Remote APIs only | All internal components |
| Performance | None | Nothing | Mock degrades accuracy |

### Mocking Examples

**Unit Test - Mock Heavily:**
```python
def test_cache_operation_unit():
    """Unit test with heavy mocking."""
    with patch('gateway.log_info') as mock_log:
        with patch('gateway.record_metric') as mock_metric:
            # Test cache_set in isolation
            result = cache_set("key", "value")
            
            # Verify interactions
            mock_log.assert_called_once()
            mock_metric.assert_called_once()
            assert result == True
```

**Integration Test - Mock Selectively:**
```python
def test_http_with_cache_integration():
    """Integration test with selective mocking."""
    # Mock only external HTTP, keep cache real
    with patch('gateway.http_request') as mock_http:
        mock_http.return_value = {"data": "test"}
        
        # First call - cache miss, HTTP called
        result1 = http_get_cached("https://api.example.com/data")
        assert mock_http.call_count == 1
        
        # Second call - cache hit, HTTP NOT called
        result2 = http_get_cached("https://api.example.com/data")
        assert mock_http.call_count == 1  # Still 1
        
        # Results match
        assert result1 == result2
```

**E2E Test - Mock Only External:**
```python
def test_alexa_flow_e2e():
    """E2E test with minimal mocking."""
    # Mock only Home Assistant API (external service)
    with patch('gateway.http_post') as mock_ha:
        mock_ha.return_value = {"status": "success"}
        
        # Test complete Alexa flow
        # - Request parsing: REAL
        # - Security validation: REAL
        # - Cache lookup: REAL
        # - HA API call: MOCKED
        # - Response formatting: REAL
        
        request = {
            "directive": {
                "header": {"name": "TurnOn"},
                "endpoint": {"endpointId": "light.living_room"}
            }
        }
        
        response = process_alexa_request(request)
        
        # Verify complete flow worked
        assert response["event"]["header"]["name"] == "Response"
        mock_ha.assert_called_once()
```

**Performance Test - No Mocking:**
```python
def test_cache_performance_real():
    """Performance test without mocking."""
    import time
    
    # No mocks - measure real performance
    start = time.time()
    
    # Real cache operations
    for i in range(1000):
        gateway.cache_set(f"key{i}", f"value{i}")
    
    for i in range(1000):
        gateway.cache_get(f"key{i}")
    
    duration = time.time() - start
    
    # Verify real performance characteristics
    assert duration < 1.0  # 1000 ops in <1 second
```

### Mocking Anti-Patterns

**❌ Over-Mocking in Integration Tests:**
```python
def test_http_cache_integration_bad():
    """BAD: Mocks everything, no real integration."""
    with patch('gateway.cache_get'):  # Wrong!
        with patch('gateway.cache_set'):  # Wrong!
            with patch('gateway.http_request'):
                # This isn't testing integration at all
                result = http_get_cached("url")
```

**❌ Under-Mocking in Unit Tests:**
```python
def test_cache_operation_bad():
    """BAD: Real dependencies in unit test."""
    # No mocking - not isolated
    result = cache_set("key", "value")
    # What if logging fails? Test fails for wrong reason
```

**❌ Mocking in Performance Tests:**
```python
def test_cache_performance_bad():
    """BAD: Mocking skews performance results."""
    with patch('gateway.cache_get', return_value="value"):
        # This measures mock overhead, not cache performance
        for _ in range(1000):
            gateway.cache_get("key")
```

### Real-World Usage Pattern

**User Query:** "Should I mock the cache in my HTTP tests?"

**Search Terms:** "how much to mock integration"

**Decision Flow:**
1. What test type? Integration test
2. Mock external services? YES (HTTP)
3. Mock internal services? NO (cache)
4. **Decision:** Mock HTTP, keep cache real
5. **Response:** "Integration test → Mock only external (HTTP), keep cache real for authentic interaction."

### Mocking Best Practices

**For Unit Tests:**
- Mock ALL dependencies
- Test function in complete isolation
- Focus on single responsibility
- Fast execution (<1ms per test)

**For Integration Tests:**
- Mock ONLY external services
- Use real internal components
- Test authentic interactions
- Moderate execution time (<100ms per test)

**For E2E Tests:**
- Mock ONLY remote APIs
- Use all real internal components
- Test complete user flows
- Slower execution acceptable (1-5s per test)

**For Performance Tests:**
- Mock NOTHING
- Measure real performance
- Use realistic data volumes
- Accept longer execution times

---

## Related Topics

- **DEC-18**: Test strategy decisions
- **LESS-07**: Test integration patterns
- **DT-08**: What to test
- **AP-23**: Testing anti-patterns

---

## Keywords

mock strategy, how much mock, test isolation, integration testing, unit testing, E2E testing

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-Testing_DT-09.md`
**End of Document**

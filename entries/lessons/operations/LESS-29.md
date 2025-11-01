# Filename: LESS-29.md

# LESS-29: Effective Testing Strategy

**REF-ID:** LESS-29  
**Category:** Operations/Testing  
**Type:** Lesson Learned  
**Created:** 2025-11-01  
**Last Updated:** 2025-11-01  
**Status:** Active  
**Priority:** HIGH

---

## Summary

Effective testing requires layered strategy - unit tests for logic, integration tests for interactions, E2E tests for workflows. Each layer serves distinct purpose. Test at appropriate level, mock appropriately, maintain fast feedback cycles.

---

## Context

Testing prevents bugs, enables refactoring, documents behavior. But ineffective testing wastes time, slows development, provides false confidence. Strategic testing approach maximizes value while minimizing overhead.

---

## The Lesson

### Testing Pyramid

**Structure (bottom to top):**
```
         /\
        /E2\     E2E Tests (Few)
       /----\    - Full system workflows
      /Integr\   Integration Tests (Some)
     /--------\  - Interface interactions
    /Unit Tests\ Unit Tests (Many)
   /------------\- Pure logic
```

**Ratios:**
- Unit: 70% (fast, isolated, many)
- Integration: 20% (moderate speed, some mocking)
- E2E: 10% (slow, expensive, critical paths only)

### Unit Testing Strategy

**What to test:**
```python
# âœ… Pure logic, algorithms, transformations
def calculate_discount(price, tier):
    """Unit test this - pure logic"""
    if tier == 'gold':
        return price * 0.20
    elif tier == 'silver':
        return price * 0.10
    return 0

# Test:
def test_calculate_discount():
    assert calculate_discount(100, 'gold') == 20
    assert calculate_discount(100, 'silver') == 10
    assert calculate_discount(100, 'bronze') == 0
```

**How to mock:**
```python
# Mock all external dependencies
from unittest.mock import patch, Mock

def test_process_order():
    # Mock ALL external calls
    with patch('gateway.log_info'):
        with patch('gateway.record_metric'):
            with patch('gateway.http_post') as mock_http:
                mock_http.return_value = {'status': 'ok'}
                
                result = process_order({'id': '123'})
                assert result['processed'] is True
```

### Integration Testing Strategy

**What to test:**
```python
# âœ… Interface interactions, cross-layer communication
def test_cache_http_integration():
    """Test cache + HTTP working together"""
    # Mock only external APIs
    with patch('external_api.fetch') as mock_fetch:
        mock_fetch.return_value = {'data': 'test'}
        
        # Real cache, real HTTP client
        result = get_with_cache_and_retry('key')
        
        # Verify interaction
        assert result == {'data': 'test'}
        assert cache.get('key') == {'data': 'test'}
```

**Mock selectively:**
```python
# Integration: Mock external only, keep internal real
def test_full_request_flow():
    with patch('boto3.client'):  # Mock AWS
        with patch('requests.post'):  # Mock external API
            # Everything else REAL
            response = lambda_handler(test_event, {})
            
            # Verifies real gateway routing
            # Verifies real cache behavior
            # Verifies real error handling
            assert response['statusCode'] == 200
```

### E2E Testing Strategy

**What to test:**
```python
# âœ… Critical user workflows, happy paths
def test_complete_user_signup():
    """E2E: Full signup workflow"""
    # Minimal mocking - only external services
    with patch('email_service.send'):
        response = signup_new_user({
            'email': 'test@example.com',
            'password': 'secure123'
        })
        
        # Verify entire flow worked
        assert response['success'] is True
        assert user_exists('test@example.com')
        assert email_was_sent()
```

**Mock minimally:**
```python
# E2E: Mock only truly external (payments, emails, etc.)
def test_purchase_flow():
    with patch('stripe.charge'):  # External payment
        with patch('sendgrid.send'):  # External email
            # Everything else REAL
            result = complete_purchase(test_order)
            
            assert result['completed'] is True
            assert order_in_database(test_order['id'])
```

### Coverage Strategy

**Minimum targets:**
```
Critical code (security, payments, data): 100%
Core business logic: 90%
Interface layers: 80%
Utility functions: 70%
Boilerplate/simple getters: 50%
```

**What to prioritize:**
```python
# Priority 1: Security, money, data integrity
def process_payment(amount, card):
    # MUST have 100% coverage
    pass

# Priority 2: Core business logic
def calculate_shipping(items, address):
    # Should have 90% coverage
    pass

# Priority 3: Utilities
def format_date(date_str):
    # 70% coverage acceptable
    pass
```

### Test Organization

**Structure:**
```
tests/
├── unit/
│   ├── test_calculations.py
│   ├── test_validation.py
│   └── test_formatting.py
├── integration/
│   ├── test_cache_http.py
│   ├── test_gateway_routing.py
│   └── test_error_handling.py
└── e2e/
    ├── test_signup_flow.py
    ├── test_purchase_flow.py
    └── test_user_journey.py
```

**Naming convention:**
```python
# test_[module]_[function]_[scenario].py

def test_calculate_discount_gold_tier():
    # Unit: Specific scenario
    pass

def test_cache_miss_triggers_http_call():
    # Integration: Interaction
    pass

def test_user_can_complete_purchase():
    # E2E: User perspective
    pass
```

### Test Quality

**Good assertions:**
```python
# âœ… Specific, meaningful
def test_format_user_data():
    result = format_user({'name': 'John', 'age': 30})
    assert result['name'] == 'John'
    assert result['age'] == 30
    assert 'formatted_at' in result

# âŒ Weak, meaningless
def test_format_user_data():
    result = format_user({'name': 'John', 'age': 30})
    assert result is not None  # Too weak!
```

**Error testing:**
```python
# âœ… Test both success and failure
def test_validate_email_valid():
    assert validate_email('user@example.com') is True

def test_validate_email_invalid():
    with pytest.raises(ValueError, match="Invalid email"):
        validate_email('not-an-email')

def test_validate_email_none():
    with pytest.raises(ValueError, match="Email required"):
        validate_email(None)
```

**Edge cases:**
```python
def test_calculate_discount_edge_cases():
    # Empty input
    assert calculate_discount(0, 'gold') == 0
    
    # Negative (error)
    with pytest.raises(ValueError):
        calculate_discount(-100, 'gold')
    
    # Very large
    assert calculate_discount(1000000, 'gold') == 200000
    
    # Unknown tier
    assert calculate_discount(100, 'unknown') == 0
```

### Performance Testing

**When to performance test:**
```python
# Critical path operations
@pytest.mark.benchmark
def test_search_performance():
    start = time.time()
    results = search_products('laptop', limit=1000)
    duration = time.time() - start
    
    assert len(results) == 1000
    assert duration < 0.5  # Must complete in 500ms
```

**Load testing:**
```python
# Separate from unit tests
# tests/performance/test_load.py
def test_concurrent_requests():
    """Simulate 100 concurrent users"""
    with ThreadPoolExecutor(max_workers=100) as pool:
        futures = [pool.submit(make_request) for _ in range(100)]
        results = [f.result() for f in futures]
    
    # All succeed
    assert all(r['success'] for r in results)
    # No degradation
    assert max(r['duration'] for r in results) < 1.0
```

### Test Speed

**Fast feedback:**
```bash
# Fast unit tests (< 5 seconds)
pytest tests/unit/

# Medium integration (< 30 seconds)
pytest tests/integration/

# Slow E2E (< 5 minutes)
pytest tests/e2e/
```

**Optimize slow tests:**
```python
# âŒ SLOW - Creates DB per test
def test_user_query():
    db = create_test_database()  # 2 seconds!
    result = db.query_users()
    assert len(result) > 0

# âœ… FAST - Shared fixture
@pytest.fixture(scope='module')
def db():
    return create_test_database()  # Once per module

def test_user_query(db):
    result = db.query_users()  # < 10ms
    assert len(result) > 0
```

### Continuous Testing

**Pre-commit:**
```bash
# Run fast tests before every commit
pytest tests/unit/ -v --tb=short
```

**CI/CD pipeline:**
```yaml
# .github/workflows/test.yml
- name: Unit Tests
  run: pytest tests/unit/ --cov=src --cov-report=xml

- name: Integration Tests
  run: pytest tests/integration/

- name: E2E Tests (if main branch)
  if: github.ref == 'refs/heads/main'
  run: pytest tests/e2e/
```

---

## Related Topics

- **DT-08**: What Should I Test (decision tree)
- **DT-09**: How Much to Mock (mocking strategy)
- **AP-23**: No Unit Tests (anti-pattern)
- **AP-24**: Testing Only Success Paths (anti-pattern)
- **LESS-23**: Question "intentional" design decisions
- **LESS-24**: Rate limit tuning per operational characteristics

---

## Keywords

testing-strategy, test-pyramid, unit-tests, integration-tests, e2e-tests, test-coverage, mocking-strategy, test-organization, performance-testing

---

## Version History

- **2025-11-01**: Created for SIMAv4 Priority 4
- **Source**: Genericized from testing best practices

---

**File:** `sima/entries/lessons/operations/LESS-29.md`  
**Lines:** ~395  
**Status:** Complete  
**Next:** LESS-45

---

**END OF DOCUMENT**
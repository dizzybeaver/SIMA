# NM05-AntiPatterns-Testing_AP-23.md - AP-23

# AP-23: No Tests

**Category:** NM05 - Anti-Patterns
**Topic:** Testing
**Priority:** 🟡 HIGH
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Writing code without automated tests makes it impossible to verify correctness, prevents safe refactoring, and allows bugs to accumulate undetected until production.

---

## Context

Automated tests are not optional "nice-to-have" — they're essential infrastructure that enables confident code changes, early bug detection, and long-term maintainability.

**Problem:** Fear of changes, production bugs, unable to refactor, regression risks.

---

## Content

### The Anti-Pattern

```python
# ❌ NO TESTS - Production code only
# cache_core.py
def get(key):
    """Get value from cache."""
    if key in _cache:
        entry = _cache[key]
        if entry['expires'] > time.time():
            return entry['value']
        del _cache[key]
    return None

def set(key, value, ttl=3600):
    """Set value in cache."""
    _cache[key] = {
        'value': value,
        'expires': time.time() + ttl
    }

# No test_cache_core.py file!
# No way to verify this works correctly!
```

**Problems:**
- Can't verify basic functionality works
- Can't test edge cases (expired entries, None values, etc.)
- Can't refactor safely
- Bugs only found in production

### Why This Is Critical

**1. No Confidence in Code**
```python
# Is this code correct?
def calculate_discount(price, discount_percent):
    return price * (1 - discount_percent / 100)

# Without tests, you don't know:
# - Does it handle 0% discount?
# - Does it handle 100% discount?
# - Does it handle negative prices?
# - Does it handle invalid percentages?
# - Does it round correctly?
```

**2. Fear of Changing Code**
```
Developer: "This code looks messy, should refactor"
Problem: No tests to verify refactoring didn't break anything
Result: Don't refactor, let code rot
Outcome: Technical debt accumulates
```

**3. Bugs Escape to Production**
```
Without tests:
├─ Bug introduced in development
├─ No automated testing catches it
├─ Manual QA might miss it
├─ Deploys to production
├─ Customers hit the bug
└─ Emergency fix, reputation damage

With tests:
├─ Bug introduced in development
├─ Test fails immediately
├─ Fix before commit
└─ Never reaches production
```

**4. Regression Bugs**
```python
# Fix bug A
def process_data(data):
    if not data:  # Added null check
        return []
    return [x * 2 for x in data]

# Later, "optimize" code
def process_data(data):
    return [x * 2 for x in data]  # Removed null check!

# Without tests: Bug A returns!
# With tests: Test fails, bug caught
```

### Correct Approach

**Write Tests for Everything:**
```python
# ✅ CORRECT - Comprehensive tests
# test_cache_core.py
import pytest
import time
from cache_core import get, set, delete

def test_set_and_get():
    """Test basic set and get."""
    set('key1', 'value1')
    assert get('key1') == 'value1'

def test_get_nonexistent_key():
    """Test getting key that doesn't exist."""
    assert get('nonexistent') is None

def test_expiration():
    """Test TTL expiration."""
    set('key2', 'value2', ttl=1)
    assert get('key2') == 'value2'
    
    time.sleep(1.1)
    assert get('key2') is None  # Should expire

def test_update_existing_key():
    """Test updating existing key."""
    set('key3', 'old_value')
    set('key3', 'new_value')
    assert get('key3') == 'new_value'

def test_none_value():
    """Test storing None value."""
    set('key4', None)
    # Should distinguish between "doesn't exist" and "value is None"
    assert get('key4') is None

def test_default_ttl():
    """Test default TTL is applied."""
    set('key5', 'value5')  # No TTL specified
    assert get('key5') == 'value5'
```

### Test Types

**1. Unit Tests (REQUIRED)**
```python
# Test individual functions in isolation
def test_calculate_discount():
    assert calculate_discount(100, 10) == 90
    assert calculate_discount(100, 0) == 100
    assert calculate_discount(100, 100) == 0
```

**2. Integration Tests (RECOMMENDED)**
```python
# Test multiple components working together
def test_cache_with_gateway():
    gateway.cache_set('test_key', 'test_value')
    result = gateway.cache_get('test_key')
    assert result == 'test_value'
```

**3. End-to-End Tests (OPTIONAL but valuable)**
```python
# Test entire system flow
def test_lambda_handler_caching():
    event = {'action': 'get_user', 'user_id': '123'}
    response = lambda_handler(event, {})
    assert response['statusCode'] == 200
```

### Testing Best Practices

**Write Tests Before or With Code:**
```python
# TDD (Test-Driven Development) approach:
# 1. Write failing test
def test_new_feature():
    result = new_feature(input_data)
    assert result == expected_output

# 2. Implement minimum code to pass
def new_feature(data):
    return expected_output

# 3. Refactor while tests still pass
```

**Test Edge Cases:**
```python
def test_edge_cases():
    # Empty input
    assert process([]) == []
    
    # None input
    with pytest.raises(ValueError):
        process(None)
    
    # Single item
    assert process([1]) == [2]
    
    # Large input
    assert len(process(range(10000))) == 10000
    
    # Negative numbers
    assert process([-1, -2]) == [-2, -4]
```

**Test Error Conditions:**
```python
def test_error_handling():
    with pytest.raises(ValueError):
        invalid_operation()
    
    with pytest.raises(KeyError):
        get_missing_item()
    
    # Test error messages
    with pytest.raises(ValueError, match="Invalid input"):
        validate_input("bad")
```

### SUGA-ISP Testing Structure

**Current Test Files:**
```
tests/
├── test_config_gateway.py      # Gateway integration tests
├── test_config_integration.py  # Full integration tests
├── test_config_performance.py  # Performance benchmarks
└── test_config_unit.py         # Unit tests for config
```

**Running Tests:**
```bash
# Run all tests
pytest

# Run specific file
pytest tests/test_config_unit.py

# Run with coverage
pytest --cov=src --cov-report=html

# Run fast (skip slow tests)
pytest -m "not slow"
```

### Minimum Test Coverage Goals

**Critical Code (100% coverage required):**
- Security functions
- Data validation
- Error handling
- Core business logic

**Important Code (80%+ coverage):**
- Gateway dispatch
- Interface routers
- Cache operations
- HTTP client

**Support Code (60%+ coverage):**
- Utilities
- Helper functions
- Logging wrappers

### Common Excuses (and Responses)

**"Testing takes too long"**
- Writing tests takes 20% more time initially
- Saves 200% time over project lifetime (debugging, fixing, refactoring)
- Tests pay for themselves within weeks

**"Code is too simple to need tests"**
- Simple code breaks too
- Tests document expected behavior
- Future changes might break "simple" code

**"I'll add tests later"**
- "Later" never comes
- Harder to add tests after code written
- Missing tests accumulate as technical debt

**"Manual testing is enough"**
- Manual testing doesn't scale
- Manual tests not repeatable
- Manual tests slow down development
- Can't manual test every change

### Detection

**Check for missing tests:**
```bash
# Find Python files without corresponding test files
for file in src/*.py; do
    basename=$(basename "$file" .py)
    if [ ! -f "tests/test_${basename}.py" ]; then
        echo "Missing tests for: $file"
    fi
done
```

**Check test coverage:**
```bash
# Install coverage
pip install pytest-cov

# Run with coverage report
pytest --cov=src --cov-report=term-missing

# Look for files with < 80% coverage
```

---

## Related Topics

- **AP-24**: Tests Without Assertions - Tests must verify behavior
- **AP-28**: Deploying Untested Code - Never deploy without tests
- **LESS-09**: Testing Lessons - Real testing failures
- **LESS-15**: 5-Step Verification - Includes test verification

---

## Keywords

no tests, unit testing, test coverage, pytest, TDD, test-driven development, automated testing, regression testing

---

## Version History

- **2025-10-24**: Created - Extracted from Part 2, added SUGA-ISP testing structure

---

**File:** `NM05-AntiPatterns-Testing_AP-23.md`
**End of Document**

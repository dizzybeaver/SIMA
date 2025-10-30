# NM05-AntiPatterns-Testing_AP-24.md - AP-24

# AP-24: Tests Without Assertions

**Category:** NM05 - Anti-Patterns
**Topic:** Testing
**Priority:** 🟡 HIGH
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Writing tests that execute code but don't verify results ("smoke tests") provides false confidence — tests pass even when code is broken, defeating the entire purpose of testing.

---

## Context

A test without assertions is like a safety inspection that just looks at equipment but never checks if it works. Tests must verify behavior, not just execute code.

**Problem:** False sense of security, bugs slip through, wasted test infrastructure.

---

## Content

### The Anti-Pattern

```python
# ❌ TEST WITH NO ASSERTIONS
def test_process_data():
    """Test data processing."""
    data = [1, 2, 3]
    result = process_data(data)
    # No assertion! Test always passes!

def test_cache_operations():
    """Test cache set and get."""
    cache_set('key', 'value')
    cache_get('key')
    # No verification! Could return wrong value!

def test_api_call():
    """Test API call."""
    try:
        response = call_api()
        # Just checking it doesn't crash
        # Not checking if response is correct!
    except Exception:
        pass  # Even swallows errors!
```

**Problems:**
- Tests always pass (green)
- Give false confidence
- Don't actually verify anything
- Bugs slip through undetected
- Waste test infrastructure

### Why This Is Wrong

**1. False Confidence**
```python
# Test "passes" but code is completely broken
def test_calculate_total():
    result = calculate_total(100, 0.10)
    # No assertion - test passes!

# Meanwhile the implementation:
def calculate_total(price, tax):
    return 0  # Always returns 0!
    # Test still passes! ❌
```

**2. Can't Detect Bugs**
```python
# Test executes but doesn't verify
def test_user_validation():
    validate_user({'name': '', 'age': -5})
    # Should reject invalid user!
    # But no assertion, so test passes
    # Bug goes undetected

def validate_user(user):
    # Broken - accepts everything
    return True  # Should raise error for invalid data!
```

**3. Masks Breaking Changes**
```python
# Original correct implementation
def get_discount(price):
    return price * 0.10

# Test (no assertion)
def test_get_discount():
    result = get_discount(100)
    # No check! Test passes

# Someone "refactors" (breaks it)
def get_discount(price):
    return 0  # Broken!

# Test still passes! Change deployed to production!
```

### Correct Approach

**Always Assert Expected Behavior:**
```python
# ✅ CORRECT - Explicit assertions
def test_process_data():
    """Test data processing doubles values."""
    data = [1, 2, 3]
    result = process_data(data)
    assert result == [2, 4, 6]  # Verifies output!

def test_cache_operations():
    """Test cache stores and retrieves values."""
    cache_set('key', 'value')
    result = cache_get('key')
    assert result == 'value'  # Verifies correctness!

def test_api_call_success():
    """Test API returns expected data."""
    response = call_api()
    assert response.status_code == 200
    assert 'data' in response.json()
    assert len(response.json()['data']) > 0
```

### Types of Assertions

**1. Equality Assertions**
```python
def test_equality():
    assert result == expected  # Exact match
    assert result != wrong_value  # Not equal
```

**2. Type Assertions**
```python
def test_types():
    assert isinstance(result, dict)
    assert isinstance(user_id, int)
    assert type(response) == list
```

**3. Membership Assertions**
```python
def test_membership():
    assert 'key' in result
    assert value in collection
    assert item not in excluded_list
```

**4. Comparison Assertions**
```python
def test_comparisons():
    assert len(result) > 0
    assert score >= 0.8
    assert count <= MAX_ITEMS
```

**5. Boolean Assertions**
```python
def test_booleans():
    assert is_valid  # True
    assert not is_error  # False
```

**6. Exception Assertions**
```python
def test_exceptions():
    with pytest.raises(ValueError):
        invalid_operation()
    
    with pytest.raises(KeyError, match="missing_key"):
        access_missing_key()
```

**7. Approximate Assertions (floats)**
```python
def test_floats():
    assert result == pytest.approx(3.14159, rel=1e-5)
    assert abs(result - expected) < 0.001
```

### Multiple Assertions Per Test

**When appropriate:**
```python
# ✅ CORRECT - Multiple related assertions
def test_user_creation():
    """Test user object creation."""
    user = create_user('Alice', 30)
    
    # Verify all expected properties
    assert user.name == 'Alice'
    assert user.age == 30
    assert user.id is not None
    assert user.created_at is not None
    assert isinstance(user.created_at, datetime)
```

**When to split:**
```python
# ❌ Too many unrelated assertions
def test_everything():
    assert feature_a_works()
    assert feature_b_works()
    assert feature_c_works()
    # These should be separate tests!

# ✅ CORRECT - Separate tests
def test_feature_a():
    assert feature_a_works()

def test_feature_b():
    assert feature_b_works()

def test_feature_c():
    assert feature_c_works()
```

### Descriptive Assertion Messages

```python
# ✅ CORRECT - Helpful failure messages
def test_with_messages():
    result = calculate(10, 5)
    assert result == 15, f"Expected 15, got {result}"
    
    assert len(items) > 0, "Items list should not be empty"
    
    assert user.is_active, f"User {user.id} should be active"
```

### Real SUGA-ISP Examples

**Wrong (early code):**
```python
# test_config.py
def test_config_loading():
    """Test config loads."""
    config = load_config()
    # No assertion! Just checks it doesn't crash
```

**Correct (current code):**
```python
# test_config_unit.py
def test_config_loading():
    """Test config loads with correct values."""
    config = load_config()
    
    # Verify structure
    assert isinstance(config, dict)
    assert 'cache' in config
    assert 'logging' in config
    
    # Verify values
    assert config['cache']['ttl'] == 3600
    assert config['logging']['level'] == 'INFO'
```

### Coverage Tools

**Use assertion coverage:**
```bash
# pytest-cov tracks which lines executed
pytest --cov=src --cov-report=term-missing

# Look for tests with no assert statements
# These are probably smoke tests
```

**Mutation testing:**
```bash
# mutpy changes code and runs tests
# If tests still pass, assertions are weak
pip install mutpy
mutpy --target src/ --unit-test tests/
```

### Common Mistakes

**Mistake 1: Only Testing for Crashes**
```python
# ❌ WRONG - Just checks no exception
def test_function():
    try:
        result = my_function()
    except Exception:
        pytest.fail("Function raised exception")
    # But didn't check if result is correct!

# ✅ CORRECT
def test_function():
    result = my_function()
    assert result == expected_value
```

**Mistake 2: Printing Instead of Asserting**
```python
# ❌ WRONG - Manual inspection
def test_calculation():
    result = calculate(10, 5)
    print(f"Result: {result}")  # Human must check!
    # Test always passes!

# ✅ CORRECT
def test_calculation():
    result = calculate(10, 5)
    assert result == 15  # Automated verification
```

**Mistake 3: Weak Assertions**
```python
# ❌ WEAK - Only checks not None
def test_get_users():
    users = get_users()
    assert users is not None
    # But is it the right data?

# ✅ STRONG - Checks structure and content
def test_get_users():
    users = get_users()
    assert isinstance(users, list)
    assert len(users) > 0
    assert all('id' in user for user in users)
    assert all('name' in user for user in users)
```

### Test Quality Checklist

**Every test should:**
- [ ] Have at least one assertion
- [ ] Assert expected behavior, not just "doesn't crash"
- [ ] Use specific assertions (not just `assert result`)
- [ ] Have clear failure messages
- [ ] Test one logical concept
- [ ] Be deterministic (same result every run)

---

## Related Topics

- **AP-23**: No Tests - Must have tests first
- **AP-28**: Deploying Untested Code - Tests must verify behavior
- **LESS-09**: Testing Lessons - Real testing failures
- **LESS-15**: Verification Protocol - Includes assertion checks

---

## Keywords

test assertions, pytest, unit testing, test quality, assertion coverage, smoke tests, false confidence

---

## Version History

- **2025-10-24**: Created - Extracted from Part 2, added assertion types and SUGA-ISP examples

---

**File:** `NM05-AntiPatterns-Testing_AP-24.md`
**End of Document**

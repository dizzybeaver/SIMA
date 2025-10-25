# NM05-AntiPatterns-Quality_AP-20.md - AP-20

# AP-20: God Functions (>50 Lines)

**Category:** NM05 - Anti-Patterns
**Topic:** Code Quality
**Priority:** 🟡 HIGH
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Functions exceeding 50 lines typically violate single responsibility principle, are difficult to test, hard to understand, and indicate missing abstractions that should be extracted.

---

## Context

Long functions accumulate over time as features are added. Each addition seems small, but the result is an unmaintainable monolith that nobody wants to touch.

**Problem:** Reduced maintainability, difficult testing, hidden bugs, fear of refactoring.

---

## Content

### The Anti-Pattern

```python
# ❌ GOD FUNCTION - 100+ lines
def process_request(event):
    # Validation (15 lines)
    if not event:
        gateway.log_error("Empty event")
        return error_response(400, "Empty event")
    if 'body' not in event:
        gateway.log_error("Missing body")
        return error_response(400, "Missing body")
    
    body = json.loads(event['body'])
    if 'action' not in body:
        gateway.log_error("Missing action")
        return error_response(400, "Missing action")
    
    # Authentication (20 lines)
    auth_header = event.get('headers', {}).get('Authorization')
    if not auth_header:
        gateway.log_error("No auth header")
        return error_response(401, "Unauthorized")
    
    token = auth_header.replace('Bearer ', '')
    try:
        user = validate_token(token)
    except Exception as e:
        gateway.log_error(f"Invalid token: {e}")
        return error_response(401, "Invalid token")
    
    # Rate limiting (15 lines)
    rate_key = f"rate:{user['id']}"
    current_count = gateway.cache_get(rate_key) or 0
    if current_count > 100:
        gateway.log_warning(f"Rate limit exceeded: {user['id']}")
        return error_response(429, "Rate limit exceeded")
    gateway.cache_set(rate_key, current_count + 1, ttl=3600)
    
    # Business logic (30 lines)
    action = body['action']
    if action == 'create':
        # ... create logic
        pass
    elif action == 'update':
        # ... update logic
        pass
    elif action == 'delete':
        # ... delete logic
        pass
    else:
        return error_response(400, "Invalid action")
    
    # Response formatting (15 lines)
    response = {
        'statusCode': 200,
        'headers': {
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin': '*'
        },
        'body': json.dumps({
            'success': True,
            'data': result
        })
    }
    
    # Logging (10 lines)
    gateway.log_info(f"Request processed: {action}")
    gateway.metrics_increment('requests.success')
    
    return response
```

**Problems:**
- 100+ lines in one function
- 5 different responsibilities
- Cannot test individual pieces
- Hard to understand flow
- Hard to modify safely

### Why This Is Wrong

**1. Violates Single Responsibility Principle**
```
This function does:
├─ Input validation
├─ Authentication
├─ Rate limiting
├─ Business logic
├─ Response formatting
└─ Logging/metrics
```
That's 6 responsibilities in one function!

**2. Difficult to Test**
```python
# How do you test just authentication?
# You can't - must set up entire function!
def test_authentication():
    # Need: valid event, body, action, etc.
    # Just to test auth!
    event = {/* 50 lines of setup */}
    result = process_request(event)
    # How do you isolate auth failure?
```

**3. Hard to Reuse**
```python
# Want to use validation elsewhere?
# Can't extract it - it's embedded!

# Want to use auth logic in another function?
# Copy-paste or refactor entire function!
```

**4. Fear of Modification**
```
"I need to change rate limiting"
└─ But it's in 100-line function
    └─ Afraid to touch it
        └─ Might break something else
            └─ Don't have tests
                └─ Leave it broken
```

### Correct Approach

**Extract Functions (Single Responsibility):**
```python
# ✅ CORRECT - Decomposed into focused functions

def process_request(event):
    """Main handler - coordinates subfunctions."""
    # Each step is a separate, testable function
    validated_data = validate_request(event)
    user = authenticate_request(event)
    check_rate_limit(user)
    result = execute_action(validated_data, user)
    return format_response(result)

def validate_request(event):
    """Validate event structure and body."""
    if not event or 'body' not in event:
        raise ValueError("Invalid event structure")
    
    body = json.loads(event['body'])
    if 'action' not in body:
        raise ValueError("Missing action")
    
    return body

def authenticate_request(event):
    """Extract and validate authentication token."""
    auth_header = event.get('headers', {}).get('Authorization')
    if not auth_header:
        raise AuthenticationError("No auth header")
    
    token = auth_header.replace('Bearer ', '')
    return validate_token(token)

def check_rate_limit(user):
    """Enforce rate limiting for user."""
    rate_key = f"rate:{user['id']}"
    current_count = gateway.cache_get(rate_key) or 0
    
    if current_count > 100:
        raise RateLimitError(f"User {user['id']} exceeded limit")
    
    gateway.cache_set(rate_key, current_count + 1, ttl=3600)

def execute_action(validated_data, user):
    """Execute the requested action."""
    action = validated_data['action']
    handlers = {
        'create': handle_create,
        'update': handle_update,
        'delete': handle_delete
    }
    
    handler = handlers.get(action)
    if not handler:
        raise ValueError(f"Invalid action: {action}")
    
    return handler(validated_data, user)

def format_response(result):
    """Format successful response."""
    return {
        'statusCode': 200,
        'headers': {
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin': '*'
        },
        'body': json.dumps({
            'success': True,
            'data': result
        })
    }
```

**Benefits:**
- ✅ Each function < 15 lines
- ✅ Each has single responsibility
- ✅ Each can be tested independently
- ✅ Easy to understand
- ✅ Easy to reuse
- ✅ Easy to modify

### Function Size Guidelines

| Lines | Assessment | Action |
|-------|-----------|--------|
| 1-20 | ✅ Good | Keep as is |
| 21-30 | 🟡 Acceptable | Consider splitting if complex |
| 31-50 | 🟠 Warning | Should probably split |
| 51-100 | 🔴 Too Long | Definitely split |
| 100+ | ⛔ God Function | Refactor immediately |

### Refactoring Strategy

**Step 1: Identify Sections**
```python
# Look for comments that say what each section does
def long_function():
    # Validation
    ...
    
    # Authentication
    ...
    
    # Business logic
    ...
    
    # Response
    ...

# Each comment = potential function!
```

**Step 2: Extract One at a Time**
```python
# Don't refactor everything at once
# Extract one function, test, commit
# Repeat for next function
```

**Step 3: Test After Each Extraction**
```python
# After extracting validate_request():
def test_validate_request():
    # Now can test just this!
    assert validate_request(valid_event) == expected
    
    with pytest.raises(ValueError):
        validate_request(invalid_event)
```

### Real SUGA-ISP Example

**Wrong (early code):**
```python
# lambda_function.py - 200+ lines in one function!
def lambda_handler(event, context):
    # All logic in one place:
    # - Gateway dispatch
    # - Error handling
    # - Response formatting
    # - Logging
    # - Metrics
    # (100+ lines of mixed concerns)
```

**Correct (current code):**
```python
# lambda_function.py - Clean main handler
def lambda_handler(event, context):
    """Main Lambda entry point."""
    try:
        return gateway.route_request(event, context)
    except Exception as e:
        return handle_error(e)

# gateway.py - Focused dispatcher
def route_request(event, context):
    """Route to appropriate handler."""
    action = extract_action(event)
    handler = get_handler(action)
    return handler(event, context)

# Each handler is focused and testable!
```

### Warning Signs

**Your function might be too long if:**
- [ ] Takes > 5 minutes to understand
- [ ] Has > 3 levels of nesting
- [ ] Uses > 10 variables
- [ ] Has > 5 conditional branches
- [ ] Scrolls off screen
- [ ] Has > 3 logical sections
- [ ] Can't be tested easily
- [ ] You're afraid to modify it

---

## Related Topics

- **AP-21**: Magic Numbers - Another code quality issue
- **AP-22**: Inconsistent Naming - Code clarity
- **AP-23**: No Tests - Untestable god functions
- **LESS-08**: Single Responsibility - Why small functions matter

---

## Keywords

god functions, function length, single responsibility, code quality, refactoring, function decomposition, maintainability

---

## Version History

- **2025-10-24**: Created - Extracted from Part 2, added SUGA-ISP example and refactoring strategy

---

**File:** `NM05-AntiPatterns-Quality_AP-20.md`
**End of Document**

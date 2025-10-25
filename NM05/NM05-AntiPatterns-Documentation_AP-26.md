# NM05-AntiPatterns-Documentation_AP-26.md - AP-26

# AP-26: Outdated Comments

**Category:** NM05 - Anti-Patterns
**Topic:** Documentation
**Priority:** 🟢 MEDIUM
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Comments that no longer match the code they describe are worse than no comments — they actively mislead developers and can cause bugs when trusted over actual implementation.

---

## Context

Code evolves constantly through bug fixes, features, and refactoring. Comments that aren't updated become lies that mislead future developers (including yourself).

**Problem:** Misleading information, wasted debugging time, incorrect assumptions, bugs from following old comments.

---

## Content

### The Anti-Pattern

```python
# ❌ OUTDATED COMMENTS
def process_user(user_data):
    # Returns user ID if successful
    # Returns None if validation fails
    
    # Code was refactored but comment wasn't updated:
    if not validate(user_data):
        raise ValueError("Invalid user")  # Now raises exception!
    
    return create_user(user_data)  # Now returns full user object!

# Comment says one thing, code does another
# Developer trusts comment → Bug!
```

**Problems:**
- Comment says returns None, code raises exception
- Comment says returns ID, code returns object
- Developer follows comment → Incorrect error handling
- Wastes time debugging "why doesn't this work?"

### Why This Is Wrong

**1. Actively Misleading**
```python
# Comment says:
# "This function is thread-safe"
def cache_get(key):
    # But code is NOT thread-safe!
    if key in _cache:  # Race condition!
        return _cache[key]

# Developer sees comment, assumes thread-safe
# Uses in multi-threaded context
# Random bugs appear
```

**2. Worse Than No Comment**
```python
# No comment: Developer reads code carefully
def process(data):
    return transform(data)  # Clear what it does

# Outdated comment: Developer trusts comment, skips code
def process(data):
    # Returns list of integers
    return transform(data)  # Actually returns dict now!

# Developer assumes list → Bug!
```

**3. Wastes Debugging Time**
```
Developer: "Why is this failing? Comment says it returns None..."
(Spends 30 minutes debugging)
Developer: "Oh, the code actually raises an exception. Comment is wrong!"
(Wasted time, frustration)
```

**4. Cascading Errors**
```python
# Outdated comment:
def get_config():
    # Returns dict with keys: 'host', 'port', 'timeout'
    return {
        'hostname': 'localhost',  # Key changed!
        'port': 8080,
        'timeout_seconds': 30  # Key changed!
    }

# Other code trusts the comment:
config = get_config()
host = config['host']  # KeyError! Key is 'hostname' now!
```

### How Comments Become Outdated

**1. Code Refactored, Comment Not Updated**
```python
# Original:
def calculate_total(items):
    # Returns sum of all item prices
    return sum(item.price for item in items)

# Refactored (added tax):
def calculate_total(items):
    # Returns sum of all item prices  ← OUTDATED!
    subtotal = sum(item.price for item in items)
    return subtotal * 1.08  # Now includes 8% tax!
```

**2. Bug Fixed, Comment Not Updated**
```python
# Original (buggy):
def validate_age(age):
    # Accepts ages 18-99
    return 18 <= age <= 99

# Bug fix (forgot to update comment):
def validate_age(age):
    # Accepts ages 18-99  ← OUTDATED! Now accepts 18-120
    return 18 <= age <= 120  # Extended range
```

**3. Exception Handling Changed**
```python
# Original:
def parse_data(raw):
    # Returns empty list on error
    try:
        return json.loads(raw)
    except:
        return []

# Changed (better error handling):
def parse_data(raw):
    # Returns empty list on error  ← OUTDATED! Now raises
    try:
        return json.loads(raw)
    except json.JSONDecodeError as e:
        raise ValueError(f"Invalid JSON: {e}")  # Now propagates!
```

### Correct Approach

**Option 1: Update Comments With Code**
```python
# ✅ CORRECT - Comment matches reality
def process_user(user_data):
    """Process and create user.
    
    Args:
        user_data (dict): User information
    
    Returns:
        User: Created user object
    
    Raises:
        ValueError: If validation fails
    """
    if not validate(user_data):
        raise ValueError("Invalid user")
    
    return create_user(user_data)
```

**Option 2: Delete Unnecessary Comments**
```python
# ❌ WRONG - Obvious comment that will become outdated
def get_username(user):
    # Returns the username
    return user.username  # Obvious!

# ✅ CORRECT - No comment needed
def get_username(user):
    return user.username  # Self-documenting
```

**Option 3: Use Self-Documenting Code**
```python
# ❌ WRONG - Comment explains what code does
def calc(x, y):
    # Calculate total with tax
    return x * y * 1.08

# ✅ CORRECT - Code is self-explanatory
TAX_RATE = 1.08

def calculate_total_with_tax(price, quantity):
    return price * quantity * TAX_RATE
```

### When Comments Are Valuable

**Good comments explain WHY, not WHAT:**
```python
# ✅ GOOD - Explains reasoning
def connect_with_retry(max_attempts=3):
    """Connect to database with retry logic.
    
    We use 3 retries because network can be flaky during deployments.
    Exponential backoff prevents overwhelming the database.
    """
    for attempt in range(max_attempts):
        try:
            return establish_connection()
        except ConnectionError:
            time.sleep(2 ** attempt)  # Exponential backoff
    raise ConnectionError("Failed after retries")
```

**Good comments explain non-obvious decisions:**
```python
# ✅ GOOD - Explains surprising choice
def process_large_file(filename):
    # Read in chunks to avoid memory issues
    # Tried reading entire file but Lambda hits 512MB limit
    chunk_size = 1024 * 1024  # 1MB chunks
    
    with open(filename, 'rb') as f:
        while chunk := f.read(chunk_size):
            yield chunk
```

**Good comments link to external resources:**
```python
# ✅ GOOD - References external documentation
def calculate_signature(data, secret):
    # HMAC-SHA256 signature as per RFC 2104
    # See: https://tools.ietf.org/html/rfc2104
    return hmac.new(secret, data, hashlib.sha256).hexdigest()
```

### Real SUGA-ISP Examples

**Wrong (early code):**
```python
# gateway_core.py
def route_request(event):
    # Returns response dict with statusCode and body
    # Returns None if action not found
    
    # But code actually:
    action = event.get('action')
    if action not in _handlers:
        raise ValueError(f"Unknown action: {action}")  # Raises!
    
    return _handlers[action](event)  # Returns response!
```

**Correct (current code):**
```python
# gateway_core.py
def route_request(event):
    """Route request to appropriate handler.
    
    Args:
        event (dict): Lambda event with 'action' key
    
    Returns:
        dict: Response with statusCode and body
    
    Raises:
        ValueError: If action is not registered
    """
    action = event.get('action')
    if action not in _handlers:
        raise ValueError(f"Unknown action: {action}")
    
    return _handlers[action](event)
```

### Prevention Strategies

**1. Code Review Checklist**
```
When reviewing code changes:
[ ] Are comments still accurate?
[ ] Do comments match new behavior?
[ ] Can outdated comments be removed?
[ ] Should new comments be added?
```

**2. Prefer Docstrings Over Comments**
```python
# Comments can become outdated easily
# Use docstrings which are more formal and visible

# ❌ Comment (easy to miss when updating)
# def process(data):
#     # Transforms data to uppercase
#     return data.upper()

# ✅ Docstring (harder to miss, shows in IDE)
def process(data):
    """Transform data to uppercase."""
    return data.upper()
```

**3. Delete Rather Than Update**
```python
# If comment doesn't add value, delete it
# ❌ BEFORE
def get_user(id):
    # Get user by ID
    return db.get('users', id)  # Obvious!

# ✅ AFTER
def get_user(id):
    return db.get('users', id)  # Self-documenting
```

---

## Related Topics

- **AP-25**: No Docstrings - Use docstrings not comments
- **LESS-11**: Documentation Lessons - Real documentation problems
- **LESS-12**: Living Documentation - Keep docs current
- **LESS-13**: Self-Documenting Code - Reduce need for comments

---

## Keywords

outdated comments, comment maintenance, documentation debt, misleading comments, code comments, comment hygiene

---

## Version History

- **2025-10-24**: Created - Extracted from Part 2, added prevention strategies and SUGA-ISP examples

---

**File:** `NM05-AntiPatterns-Documentation_AP-26.md`
**End of Document**

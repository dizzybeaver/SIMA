# File: INT-10-Utility-Interface.md

**REF-ID:** INT-10  
**Version:** 1.0.0  
**Category:** Interface Pattern  
**Status:** Active  
**Priority:** 🟢 MEDIUM  
**Created:** 2025-11-01  
**Contributors:** SIMA Learning Mode

---

## 📋 OVERVIEW

**Interface Name:** UTILITY  
**Short Code:** UTIL  
**Type:** Helper Functions Interface  
**Dependency Layer:** Layer 1 (Core Services)

**One-Line Description:**  
UTILITY interface provides generic helper functions for common operations (string manipulation, validation, data transformation).

**Primary Purpose:**  
Centralize reusable utility functions that don't warrant dedicated interfaces.

---

## 🎯 CORE RESPONSIBILITIES

### 1. String Operations
- Case conversion (camelCase, snake_case, kebab-case)
- Truncation and padding
- Sanitization and escaping
- Template rendering

### 2. Data Transformation
- Dictionary operations (merge, flatten, filter)
- List operations (deduplicate, chunk, flatten)
- Type conversion and coercion
- Data structure manipulation

### 3. Validation Helpers
- Format validation (email, URL, phone)
- Range checking
- Type validation
- Pattern matching

### 4. Convenience Functions
- Date/time utilities
- File operations
- Encoding/decoding
- Math helpers

---

## 🔑 KEY RULES

### Rule 1: Generic Functions Only
**What:** Only include truly generic functions with no domain-specific logic.

**Examples:**
- âœ… `to_camel_case(s)` - Generic string transformation
- âœ… `deep_merge(d1, d2)` - Generic dict operation
- âœ… `chunk_list(lst, size)` - Generic list operation
- âŒ `calculate_mortgage_payment()` - Domain-specific
- âŒ `get_user_preferences()` - Domain-specific

---

### Rule 2: Pure Functions (No Side Effects)
**What:** Utility functions MUST be pure - same input always produces same output, no side effects.

**Why:** Predictable, testable, composable, thread-safe.

**Example:**
```python
# ✅ DO: Pure function
def to_snake_case(s):
    return re.sub(r'(?<!^)(?=[A-Z])', '_', s).lower()

# ❌ DON'T: Side effects
def to_snake_case(s):
    global last_converted
    last_converted = s  # Side effect!
    return re.sub(r'(?<!^)(?=[A-Z])', '_', s).lower()
```

---

### Rule 3: Keep Functions Small
**What:** Each utility function should be < 50 lines.

**Why:** Utilities should be simple. Complex logic deserves dedicated interface.

**Example:**
```python
# ✅ DO: Simple utility
def truncate(s, max_length, suffix="..."):
    if len(s) <= max_length:
        return s
    return s[:max_length - len(suffix)] + suffix

# ❌ DON'T: Complex logic in utility
def complex_data_processor(data):
    # 200 lines of complex logic
    # This should be its own interface!
```

---

### Rule 4: Explicit Error Handling
**What:** Validate inputs and provide clear error messages.

**Why:** Utilities are called from many places. Clear errors save debugging time.

**Example:**
```python
# ✅ DO: Validate and provide clear errors
def chunk_list(lst, chunk_size):
    if not isinstance(lst, list):
        raise TypeError(f"Expected list, got {type(lst)}")
    if chunk_size < 1:
        raise ValueError(f"chunk_size must be >= 1, got {chunk_size}")
    
    return [lst[i:i+chunk_size] for i in range(0, len(lst), chunk_size)]

# ❌ DON'T: Silent failures or cryptic errors
def chunk_list(lst, chunk_size):
    return [lst[i:i+chunk_size] for i in range(0, len(lst), chunk_size)]
    # Fails with cryptic error if inputs invalid
```

---

### Rule 5: Document Edge Cases
**What:** Document how function handles edge cases (empty input, None, special values).

**Example:**
```python
def safe_get(d, key, default=None):
    """
    Safely get value from dict with default.
    
    Args:
        d: Dictionary to search
        key: Key to lookup
        default: Value if key not found (default: None)
    
    Returns:
        Value if key exists, otherwise default
    
    Edge Cases:
        - Returns default if d is None
        - Returns default if key is None
        - Returns default if d is not a dict
    
    Examples:
        >>> safe_get({'a': 1}, 'a')
        1
        >>> safe_get({'a': 1}, 'b', default=0)
        0
        >>> safe_get(None, 'a', default=0)
        0
    """
    if not isinstance(d, dict) or d is None or key is None:
        return default
    return d.get(key, default)
```

---

## 🎨 MAJOR BENEFITS

### Benefit 1: Code Reuse
- Write once, use everywhere
- Consistent behavior
- Fewer bugs
- Easier maintenance

### Benefit 2: Readability
- Descriptive function names
- Self-documenting code
- Less inline logic
- Clearer intent

### Benefit 3: Testability
- Pure functions easy to test
- Isolated logic
- Clear inputs/outputs
- No mocking needed

### Benefit 4: Consistency
- Standard implementations
- Same edge case handling
- Predictable behavior
- Team alignment

---

## 📚 CORE FUNCTIONS

### String Utilities

```python
# Case conversion
to_snake_case(s)
to_camel_case(s)
to_pascal_case(s)
to_kebab_case(s)

# Truncation
truncate(s, max_length, suffix="...")
truncate_words(s, max_words)

# Sanitization
sanitize_string(s, allowed_chars=None)
escape_html(s)
strip_whitespace(s)

# Templates
render_template(template, **kwargs)
```

### Dict Utilities

```python
# Merging
deep_merge(dict1, dict2)
shallow_merge(dict1, dict2)

# Filtering
filter_dict(d, keys)
exclude_keys(d, keys)
filter_none_values(d)

# Transformation
flatten_dict(d, separator=".")
unflatten_dict(d, separator=".")
invert_dict(d)

# Access
safe_get(d, key, default=None)
deep_get(d, path, default=None)
```

### List Utilities

```python
# Chunking
chunk_list(lst, chunk_size)
batch_items(lst, batch_size)

# Deduplication
deduplicate(lst, key=None)
unique_by(lst, key_func)

# Flattening
flatten_list(nested_lst)
flatten_deep(nested_lst)

# Filtering
filter_none(lst)
compact(lst)
```

### Validation Utilities

```python
# Format validation
is_valid_email(s)
is_valid_url(s)
is_valid_phone(s)
is_valid_uuid(s)

# Type validation
is_numeric(s)
is_boolean(s)
is_json(s)

# Range validation
in_range(value, min_val, max_val)
is_positive(value)
```

### Data Conversion

```python
# Type conversion
to_int(value, default=None)
to_float(value, default=None)
to_bool(value, default=None)

# Encoding
to_json(obj)
from_json(s)
to_base64(s)
from_base64(s)
```

---

## 🔄 USAGE PATTERNS

### Pattern 1: String Transformation
```python
from gateway import to_snake_case, truncate

def process_field_name(name):
    # Convert to snake_case and truncate
    normalized = to_snake_case(name)
    return truncate(normalized, max_length=50)

# Example:
process_field_name("VeryLongFieldNameThatNeedsTruncation")
# Returns: "very_long_field_name_that_needs_trunca..."
```

### Pattern 2: Dict Operations
```python
from gateway import deep_merge, filter_none_values

def merge_config(defaults, overrides):
    # Merge configs, remove None values
    merged = deep_merge(defaults, overrides)
    return filter_none_values(merged)

defaults = {"timeout": 30, "retries": 3, "debug": False}
overrides = {"timeout": 60, "debug": None}

config = merge_config(defaults, overrides)
# Returns: {"timeout": 60, "retries": 3, "debug": False}
```

### Pattern 3: List Processing
```python
from gateway import chunk_list, deduplicate

def process_in_batches(items):
    # Remove duplicates and process in chunks
    unique_items = deduplicate(items)
    
    for batch in chunk_list(unique_items, chunk_size=100):
        process_batch(batch)

items = [1, 2, 2, 3, 4, 4, 5] * 100  # 700 items with duplicates
process_in_batches(items)  # Processes 5 items in 1 batch
```

### Pattern 4: Safe Data Access
```python
from gateway import safe_get, deep_get

def get_user_email(user_data):
    # Safely access nested data
    return deep_get(user_data, "contact.email.primary", default="")

user_data = {
    "contact": {
        "email": {
            "primary": "user@example.com"
        }
    }
}

email = get_user_email(user_data)  # "user@example.com"
email = get_user_email({})  # "" (no error)
```

### Pattern 5: Validation Chain
```python
from gateway import is_valid_email, is_valid_phone, is_valid_url

def validate_contact_info(data):
    errors = []
    
    if not is_valid_email(data.get("email")):
        errors.append("Invalid email")
    
    if not is_valid_phone(data.get("phone")):
        errors.append("Invalid phone")
    
    if not is_valid_url(data.get("website")):
        errors.append("Invalid website URL")
    
    return len(errors) == 0, errors
```

---

## ⚠️ ANTI-PATTERNS

### Anti-Pattern 1: Domain Logic in Utilities ❌
```python
# ❌ DON'T: Domain-specific logic
def calculate_user_discount(user):
    if user.membership == "gold":
        return 0.20
    elif user.membership == "silver":
        return 0.10
    return 0.0

# ✅ DO: Generic utility only
def calculate_percentage(value, percentage):
    return value * (percentage / 100)
```

### Anti-Pattern 2: Side Effects ❌
```python
# ❌ DON'T: Modify input or global state
def sort_list(lst):
    lst.sort()  # Modifies input!
    return lst

# ✅ DO: Pure function
def sort_list(lst):
    return sorted(lst)  # Returns new list
```

### Anti-Pattern 3: Complex Logic ❌
```python
# ❌ DON'T: 200 lines of complex logic
def complex_data_processor(data):
    # Complex transformation logic
    # Multiple steps
    # Many edge cases
    # This should be its own interface!
    pass

# ✅ DO: Simple utility
def normalize_whitespace(s):
    return ' '.join(s.split())
```

### Anti-Pattern 4: Poor Error Handling ❌
```python
# ❌ DON'T: Silent failures
def to_int(value):
    try:
        return int(value)
    except:
        return None  # Which error? Why did it fail?

# ✅ DO: Explicit handling
def to_int(value, default=None):
    if value is None:
        return default
    try:
        return int(value)
    except (ValueError, TypeError) as e:
        if default is not None:
            return default
        raise ValueError(f"Cannot convert {value} to int: {e}")
```

---

## 🔗 CROSS-REFERENCES

**Related Architecture:**
- ARCH-01 (SUGA): Utility is Layer 1
- ARCH-03 (DD): Utilities in dispatch

**Related Interfaces:**
- INT-03 (Security): Validation utilities
- INT-02 (Logging): String sanitization
- INT-06 (Config): Type conversion utilities

**Related Patterns:**
- GATE-02 (Lazy Loading): Import utilities lazily
- GATE-04 (Wrapper): Utility wrappers

**Related Lessons:**
- LESS-16 (Pure Functions): Benefits of pure functions
- LESS-24 (Code Quality): Utility best practices
- LESS-33 (Error Handling): Validation patterns

**Related Decisions:**
- DEC-13 (Utility Pattern): Pure functions only
- DEC-18 (Error Strategy): Explicit validation

---

## ✅ VERIFICATION CHECKLIST

Before adding utility functions:
- [ ] Function is truly generic
- [ ] Function is pure (no side effects)
- [ ] Function is < 50 lines
- [ ] Inputs validated
- [ ] Error messages clear
- [ ] Edge cases documented
- [ ] Examples provided
- [ ] Tests written
- [ ] Type hints added
- [ ] No domain logic

---

## 📊 UTILITY CATEGORIES

### String Utilities (15 functions)
```
Case conversion, truncation, sanitization, templates
```

### Dict Utilities (12 functions)
```
Merging, filtering, transformation, safe access
```

### List Utilities (10 functions)
```
Chunking, deduplication, flattening, filtering
```

### Validation Utilities (12 functions)
```
Format validation, type checking, range validation
```

### Conversion Utilities (8 functions)
```
Type conversion, encoding/decoding
```

---

**END OF INTERFACE ENTRY**

**REF-ID:** INT-10  
**Status:** Active  
**Lines:** 385
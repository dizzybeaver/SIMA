# NM07-DecisionLogic-ErrorHandling_DT-06.md - DT-06

# DT-06: What Exception Type Should I Raise?

**Category:** Decision Logic
**Topic:** Error Handling
**Priority:** Medium
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Decision tree for selecting appropriate exception types - using specific built-in exceptions vs custom exceptions based on error context.

---

## Context

Using specific exception types makes error handling more precise and communicates error nature clearly. Python provides built-in exception types for common scenarios.

---

## Content

### Decision Tree

```
START: Need to raise exception
│
├─ Q: Is it invalid input/argument?
│  └─ YES → raise ValueError("Message")
│         → END
│
├─ Q: Is it missing key/attribute?
│  └─ YES → raise KeyError("Message") or AttributeError
│         → END
│
├─ Q: Is it wrong type?
│  └─ YES → raise TypeError("Message")
│         → END
│
├─ Q: Is it operation not supported?
│  └─ YES → raise NotImplementedError("Message")
│         → END
│
├─ Q: Is it I/O related?
│  └─ YES → raise IOError("Message") or FileNotFoundError
│         → END
│
├─ Q: Is it permission/security?
│  └─ YES → raise PermissionError("Message")
│         → END
│
└─ Q: Is it domain-specific?
   └─ YES → Define custom exception
          class CustomError(Exception): pass
          raise CustomError("Message")
          → END
```

### Exception Selection Guide

| Error Condition | Exception Type | Example |
|-----------------|----------------|---------|
| Invalid value | ValueError | Age < 0 or age > 150 |
| Wrong type | TypeError | Expected int, got str |
| Missing key | KeyError | Config key not found |
| Missing attribute | AttributeError | Object has no attribute |
| Not implemented | NotImplementedError | Abstract method not overridden |
| File not found | FileNotFoundError | Config file missing |
| I/O error | IOError | File read failed |
| Permission denied | PermissionError | No access to resource |
| Domain-specific | Custom exception | CacheError, ValidationError |

### Correct Exception Usage

**ValueError - Invalid Values:**
```python
def validate_age(age: int) -> None:
    """Validate age is within reasonable range."""
    if not isinstance(age, int):
        raise TypeError(f"Age must be int, got {type(age)}")
    if age < 0 or age > 150:
        raise ValueError(f"Age must be 0-150, got {age}")
```

**KeyError - Missing Keys:**
```python
def get_config(key: str) -> str:
    """Get configuration value."""
    if key not in _config:
        raise KeyError(f"Config key not found: {key}")
    return _config[key]
```

**TypeError - Wrong Types:**
```python
def process_items(items: list) -> None:
    """Process list of items."""
    if not isinstance(items, list):
        raise TypeError(f"Expected list, got {type(items)}")
```

**NotImplementedError - Unimplemented:**
```python
class BaseHandler:
    def handle(self, data):
        """Must be implemented by subclass."""
        raise NotImplementedError("Subclass must implement handle()")
```

**Custom Exceptions - Domain-Specific:**
```python
class CacheError(Exception):
    """Raised when cache operations fail."""
    pass

class ValidationError(Exception):
    """Raised when validation fails."""
    pass

def validate_cache_key(key: str):
    """Validate cache key format."""
    if not key:
        raise ValidationError("Cache key cannot be empty")
    if len(key) > 255:
        raise ValidationError(f"Cache key too long: {len(key)} > 255")
```

### Wrong - Too Generic

```python
# ❌ Too generic - doesn't communicate error type
def validate_age(age):
    if age < 0:
        raise Exception("Bad age")  # Use ValueError instead

# ❌ Too generic - use KeyError
def get_config(key):
    if key not in _config:
        raise Exception("Not found")  # Use KeyError instead

# ❌ Wrong exception type
def validate_email(email):
    if '@' not in email:
        raise KeyError("Invalid email")  # Use ValueError instead
```

### Custom Exception Patterns

**Simple Custom Exception:**
```python
class CacheError(Exception):
    """Base exception for cache operations."""
    pass

class CacheMissError(CacheError):
    """Raised when cache key not found."""
    pass

class CacheFullError(CacheError):
    """Raised when cache is full."""
    pass
```

**Exception with Context:**
```python
class ValidationError(Exception):
    """Validation failure with context."""
    
    def __init__(self, message: str, field: str = None):
        self.message = message
        self.field = field
        super().__init__(self.message)
    
    def __str__(self):
        if self.field:
            return f"{self.field}: {self.message}"
        return self.message
```

### Real-World Usage Pattern

**User Query:** "What exception should I raise for invalid input?"

**Search Terms:** "exception type invalid input"

**Decision Flow:**
1. Is it invalid input/argument? YES
2. **Decision:** ValueError
3. **Response:** "Invalid input → ValueError with descriptive message about what's wrong"

**User Query:** "What exception for missing configuration?"

**Search Terms:** "exception type missing config"

**Decision Flow:**
1. Is it missing key? YES
2. **Decision:** KeyError
3. **Response:** "Missing key → KeyError('Config key not found: {key}')"

### Exception Hierarchy Best Practices

**Use Built-ins When Possible:**
```python
# ✅ Good - use built-in ValueError
if value < 0:
    raise ValueError("Value must be positive")

# ❌ Unnecessary - ValueError exists
class InvalidValueError(Exception):
    pass
if value < 0:
    raise InvalidValueError("Value must be positive")
```

**Custom for Domain Logic:**
```python
# ✅ Good - domain-specific semantics
class CacheOperationError(Exception):
    """Cache-specific error handling."""
    pass

# Allows: except CacheOperationError
# vs: except (ValueError, KeyError, RuntimeError)
```

---

## Related Topics

- **AP-16**: Wrong exception types (anti-pattern)
- **DT-05**: How to handle errors
- **DEC-15**: Error handling design
- **ERR-02**: Error propagation patterns

---

## Keywords

exception types, raise exception, which exception, error types, ValueError, KeyError, TypeError

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-ErrorHandling_DT-06.md`
**End of Document**

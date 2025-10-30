# NM07-DecisionLogic-Refactoring_DT-11.md - DT-11

# DT-11: Extract to Function or Leave Inline?

**Category:** Decision Logic
**Topic:** Refactoring
**Priority:** Low
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Decision tree for function extraction - determining when to extract code blocks into separate functions vs leaving them inline based on reuse, size, and single responsibility principle.

---

## Context

Function extraction improves readability and reusability but adds indirection. The decision should balance these factors based on actual reuse and clarity benefits.

---

## Content

### Decision Tree

```
START: Code block that could be extracted
│
├─ Q: Is code used >2 times?
│  ├─ YES → Extract to function
│  │      DRY principle
│  │      → EXTRACT
│  │
│  └─ NO → Continue
│
├─ Q: Is code >10 lines?
│  ├─ YES → Consider extracting
│  │      Aids readability
│  │      → Continue
│  │
│  └─ NO → Continue
│
├─ Q: Does code have clear single purpose?
│  ├─ YES → Extract to function
│  │      Single Responsibility Principle
│  │      → EXTRACT
│  │
│  └─ NO → Leave inline
│         Extraction would be artificial
│         → END
│
└─ Q: Would extraction improve readability?
   ├─ YES → Extract
   │      → EXTRACT
   │
   └─ NO → Leave inline
          → END
```

### Extraction Decision Matrix

| Reuse | Lines | Single Purpose | Readability | Decision |
|-------|-------|----------------|-------------|----------|
| 3+ times | Any | Any | Any | Extract (DRY) |
| 2 times | >10 | Yes | Better | Extract |
| 1 time | >20 | Yes | Much better | Extract |
| 1 time | >10 | Yes | Better | Extract |
| 1 time | <10 | Yes | Same | Leave inline |
| 1 time | <10 | No | Worse | Leave inline |

### Extract Function Examples

**✅ Extract - Used Multiple Times:**
```python
# ❌ Before: Duplicated validation
def process_user(user):
    if not user.get('email'):
        raise ValueError("Email required")
    if '@' not in user.get('email', ''):
        raise ValueError("Invalid email")
    # ... process

def update_user(user):
    if not user.get('email'):
        raise ValueError("Email required")
    if '@' not in user.get('email', ''):
        raise ValueError("Invalid email")
    # ... update

# ✅ After: Extracted function
def validate_email(email):
    """Validate email format."""
    if not email:
        raise ValueError("Email required")
    if '@' not in email:
        raise ValueError("Invalid email")

def process_user(user):
    validate_email(user.get('email'))
    # ... process

def update_user(user):
    validate_email(user.get('email'))
    # ... update
```

**✅ Extract - Clear Single Purpose:**
```python
# ❌ Before: Mixed concerns in one function
def process_request(request):
    # Parse and validate (5 lines)
    data = request.get('data')
    if not data:
        raise ValueError("Missing data")
    if not isinstance(data, dict):
        raise TypeError("Data must be dict")
    
    # Transform data (8 lines)
    transformed = {}
    for key, value in data.items():
        if key.startswith('_'):
            continue
        transformed[key.upper()] = str(value)
    
    # Store (3 lines)
    cache_set('processed', transformed)
    return transformed

# ✅ After: Extracted separate concerns
def validate_request_data(data):
    """Validate request data structure."""
    if not data:
        raise ValueError("Missing data")
    if not isinstance(data, dict):
        raise TypeError("Data must be dict")

def transform_data(data):
    """Transform data keys and values."""
    transformed = {}
    for key, value in data.items():
        if key.startswith('_'):
            continue
        transformed[key.upper()] = str(value)
    return transformed

def process_request(request):
    """Process request with validated and transformed data."""
    data = request.get('data')
    validate_request_data(data)
    transformed = transform_data(data)
    cache_set('processed', transformed)
    return transformed
```

**❌ Don't Extract - Used Once, Simple:**
```python
# Current: Inline calculation
def calculate_total(items):
    subtotal = sum(item.price for item in items)
    tax = subtotal * 0.08  # 8% tax
    return subtotal + tax

# "Extracted": Unnecessary
def calculate_tax(subtotal):
    return subtotal * 0.08

def calculate_total(items):
    subtotal = sum(item.price for item in items)
    tax = calculate_tax(subtotal)  # Adds indirection for one line
    return subtotal + tax

# Decision: DON'T EXTRACT
# - Used once
# - Simple calculation
# - Extraction adds no value
```

**❌ Don't Extract - No Clear Purpose:**
```python
# Current: Mixed operations (artificial to separate)
def process_item(item):
    value = item.get('value', 0)
    count = item.get('count', 1)
    result = value * count + 5  # Domain-specific formula
    return result

# "Extracted": Artificial separation
def calculate_result(value, count):
    return value * count + 5

def process_item(item):
    value = item.get('value', 0)
    count = item.get('count', 1)
    return calculate_result(value, count)

# Decision: DON'T EXTRACT
# - No clear single responsibility
# - Extraction is artificial
# - Doesn't improve understanding
```

**✅ Extract - Improves Readability:**
```python
# ❌ Before: Long function, hard to follow
def handle_request(request):
    # 15 lines of validation
    if not request:
        return error("Missing request")
    if 'directive' not in request:
        return error("Missing directive")
    # ... 10 more validation lines
    
    # 20 lines of processing
    directive = request['directive']
    header = directive['header']
    # ... 15 more processing lines
    
    # 10 lines of response formatting
    response = {}
    response['event'] = {}
    # ... 8 more formatting lines
    
    return response

# ✅ After: Extracted for clarity
def validate_request(request):
    """Validate request structure (15 lines)."""
    if not request:
        return error("Missing request")
    if 'directive' not in request:
        return error("Missing directive")
    # ... validation logic
    return True

def process_directive(directive):
    """Process directive content (20 lines)."""
    header = directive['header']
    # ... processing logic
    return result

def format_response(result):
    """Format response structure (10 lines)."""
    response = {}
    response['event'] = {}
    # ... formatting logic
    return response

def handle_request(request):
    """Handle request with clear steps."""
    if not validate_request(request):
        return None
    result = process_directive(request['directive'])
    return format_response(result)
```

### Function Naming Best Practices

**Good Function Names:**
```python
✅ validate_email(email)       # Clear verb + noun
✅ transform_data(data)         # Clear action
✅ format_response(result)      # Clear purpose
✅ calculate_total(items)       # Clear calculation
```

**Bad Function Names:**
```python
❌ do_stuff(data)              # Too vague
❌ process(x)                  # Too generic
❌ handle(thing)               # Meaningless
❌ func1(param)                # No semantics
```

### Real-World Usage Pattern

**User Query:** "Should I extract this 5-line code block?"

**Search Terms:** "extract function decision"

**Decision Flow:**
1. Used >2 times? If NO, continue
2. >10 lines? NO (only 5 lines)
3. Clear single purpose? Evaluate
4. Improve readability? Evaluate
5. **Response:** "If used >2 times or has clear single purpose → Extract. If used once and simple → Inline."

### Extraction Anti-Patterns

**❌ Over-Extraction:**
```python
# Too many tiny functions
def get_email(user):
    return user.get('email')

def get_name(user):
    return user.get('name')

def get_age(user):
    return user.get('age')

# Just use: user.get('email') directly
```

**❌ Under-Extraction:**
```python
# God function doing everything
def do_everything(data):
    # 500 lines of mixed concerns
    # Should be split into 10+ functions
    pass
```

**❌ Artificial Extraction:**
```python
# Extracting for the sake of extracting
def add_five(x):
    return x + 5

def calculate(x):
    return add_five(x)  # Pointless wrapper
```

---

## Related Topics

- **DT-10**: Should refactor decision
- **AP-20**: Unnecessary complexity
- **DT-02**: Where function goes

---

## Keywords

extract function, inline code, function extraction, code organization, DRY principle, single responsibility

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-Refactoring_DT-11.md`
**End of Document**

# LANG-PY-07: Python Code Quality Standards

**REF-ID:** LANG-PY-07  
**Category:** Language Patterns  
**Subcategory:** Code Quality  
**Language:** Python  
**Priority:** 🟡 HIGH  
**Status:** Active  
**Created:** 2025-11-01  
**Last Updated:** 2025-11-01

---

## 📋 SUMMARY

Comprehensive code quality standards: consistency, readability, maintainability, and Pythonic patterns.

---

## 🎯 CORE PRINCIPLES

### Principle 1: Consistency Over Cleverness

**✅ CORRECT (Clear and Consistent):**
```python
def calculate_discount(price, percentage):
    """Calculate discount amount."""
    return price * (percentage / 100)
```

**❌ WRONG (Clever but Obscure):**
```python
def calc_disc(p, pct):
    return p * pct / 100 if pct else p * 0
```

**Why:**
- Consistency makes code predictable
- Cleverness creates maintenance burden
- Clear code is better than clever code

---

### Principle 2: Explicit Over Implicit

**✅ CORRECT:**
```python
def send_email(to, subject, body, cc=None, bcc=None):
    """Send email with explicit parameters."""
    if cc is None:
        cc = []
    if bcc is None:
        bcc = []
    # Send email logic
```

**❌ WRONG:**
```python
def send_email(*args, **kwargs):
    """Send email with implicit parameters."""
    # What parameters are expected?
```

---

### Principle 3: DRY (Don't Repeat Yourself)

**❌ WRONG (Repetitive):**
```python
def process_user_order():
    user = get_user()
    if user is None:
        log_error("User not found")
        return error_response("User not found")
    
    order = get_order()
    if order is None:
        log_error("Order not found")
        return error_response("Order not found")
    
    product = get_product()
    if product is None:
        log_error("Product not found")
        return error_response("Product not found")
```

**✅ CORRECT (DRY):**
```python
def validate_resource(resource, name):
    """Validate resource exists."""
    if resource is None:
        log_error(f"{name} not found")
        return error_response(f"{name} not found")
    return None

def process_user_order():
    for getter, name in [
        (get_user, "User"),
        (get_order, "Order"),
        (get_product, "Product")
    ]:
        resource = getter()
        error = validate_resource(resource, name)
        if error:
            return error
```

---

### Principle 4: Fail Fast

**✅ CORRECT:**
```python
def process_payment(amount, card):
    # Validate early
    if amount <= 0:
        raise ValueError("Amount must be positive")
    
    if not card.is_valid():
        raise ValueError("Invalid card")
    
    if card.balance < amount:
        raise ValueError("Insufficient funds")
    
    # Process payment
    return charge_card(card, amount)
```

**❌ WRONG:**
```python
def process_payment(amount, card):
    result = None
    if amount > 0:
        if card.is_valid():
            if card.balance >= amount:
                result = charge_card(card, amount)
    return result  # What went wrong?
```

---

## 🔧 QUALITY RULES

### Rule 1: Use Meaningful Names

**✅ CORRECT:**
```python
user_age = 25
is_active = True
total_price = 100.00

def calculate_monthly_payment(principal, rate, years):
    pass
```

**❌ WRONG:**
```python
a = 25
f = True
t = 100.00

def calc(p, r, y):
    pass
```

---

### Rule 2: Keep Line Length Reasonable (< 100 chars)

**✅ CORRECT:**
```python
result = calculate_complex_metric(
    user_data,
    time_period,
    adjustment_factor,
    include_estimates=True
)
```

**❌ WRONG:**
```python
result = calculate_complex_metric(user_data, time_period, adjustment_factor, include_estimates=True, use_cache=False)
```

---

### Rule 3: Use Comments Wisely

**✅ CORRECT (Explain Why, Not What):**
```python
# Cache miss penalty is expensive, so we pre-fetch
if cache_miss_rate > 0.5:
    prefetch_data()

# Business rule: Premium users get 2x points
points = base_points * (2 if user.is_premium else 1)
```

**❌ WRONG (Obvious Comments):**
```python
# Increment counter
counter += 1

# Check if user is None
if user is None:
    pass
```

---

### Rule 4: Avoid Magic Numbers

**❌ WRONG:**
```python
if user.age > 18:
    grant_access()

if len(password) < 8:
    reject()
```

**✅ CORRECT:**
```python
ADULT_AGE = 18
MIN_PASSWORD_LENGTH = 8

if user.age > ADULT_AGE:
    grant_access()

if len(password) < MIN_PASSWORD_LENGTH:
    reject()
```

---

### Rule 5: Use List Comprehensions (When Readable)

**✅ CORRECT:**
```python
# Simple transformation
squares = [x ** 2 for x in numbers]

# Simple filtering
evens = [x for x in numbers if x % 2 == 0]

# Map and filter
valid_emails = [
    email.lower() 
    for email in emails 
    if '@' in email
]
```

**❌ WRONG (Too Complex):**
```python
# Too complex for comprehension
result = [
    transform(validate(x)) if condition(x) else alternative(x)
    for x in items
    if x not in exclude and check(x)
]

# Better as loop:
result = []
for x in items:
    if x in exclude or not check(x):
        continue
    
    if condition(x):
        result.append(transform(validate(x)))
    else:
        result.append(alternative(x))
```

---

### Rule 6: Use Context Managers

**✅ CORRECT:**
```python
with open(filename) as f:
    data = f.read()

with database.transaction():
    update_user(user)
    log_action(action)
```

**❌ WRONG:**
```python
f = open(filename)
data = f.read()
f.close()  # Might not execute if error occurs
```

---

### Rule 7: Test Your Code

**✅ CORRECT:**
```python
def calculate_tax(amount, rate):
    """Calculate tax amount.
    
    >>> calculate_tax(100, 0.08)
    8.0
    >>> calculate_tax(0, 0.08)
    0.0
    """
    return amount * rate

# Or unit tests:
def test_calculate_tax():
    assert calculate_tax(100, 0.08) == 8.0
    assert calculate_tax(0, 0.08) == 0.0
    assert calculate_tax(100, 0) == 0.0
```

---

## 📊 CODE ORGANIZATION

### Module Organization

```python
"""
Module docstring describing purpose and usage.
"""

# 1. Imports (grouped by standard, 3rd-party, local)
import os
from typing import List

import requests

from .models import User

# 2. Module constants
DEFAULT_TIMEOUT = 30
MAX_RETRIES = 3

# 3. Module variables (minimize these)
_cache = {}

# 4. Exception classes
class ValidationError(Exception):
    pass

# 5. Public classes
class UserManager:
    pass

# 6. Public functions
def get_user(user_id):
    pass

# 7. Private helpers
def _validate_user(user):
    pass

# 8. Main execution (if script)
if __name__ == '__main__':
    main()
```

---

### Class Organization

```python
class User:
    """User model.
    
    Attributes:
        id: Unique identifier
        name: User's full name
    """
    
    # 1. Class variables
    MIN_AGE = 18
    
    # 2. __init__
    def __init__(self, id, name):
        self.id = id
        self.name = name
    
    # 3. Properties
    @property
    def display_name(self):
        return self.name.title()
    
    # 4. Public methods
    def save(self):
        pass
    
    def delete(self):
        pass
    
    # 5. Private methods
    def _validate(self):
        pass
    
    # 6. Special methods
    def __repr__(self):
        return f"User(id={self.id}, name={self.name})"
    
    def __eq__(self, other):
        return self.id == other.id
```

---

## ⚠️ ANTI-PATTERNS

### AP-1: Deep Nesting

**❌ WRONG:**
```python
def process():
    if condition1:
        if condition2:
            if condition3:
                if condition4:
                    do_something()
```

**✅ CORRECT:**
```python
def process():
    if not condition1:
        return
    if not condition2:
        return
    if not condition3:
        return
    if not condition4:
        return
    
    do_something()
```

---

### AP-2: God Classes

**❌ WRONG:**
```python
class Manager:
    """Does everything: validation, DB, API, caching, logging..."""
    # 2000+ lines
```

**✅ CORRECT:**
```python
class UserValidator:
    """Validates user data."""
    pass

class UserRepository:
    """Database operations for users."""
    pass

class UserService:
    """Business logic for users."""
    def __init__(self, validator, repository):
        self.validator = validator
        self.repository = repository
```

---

### AP-3: Mutable Default Arguments

**❌ WRONG:**
```python
def append_to(item, list=[]):  # Shared across calls!
    list.append(item)
    return list
```

**✅ CORRECT:**
```python
def append_to(item, list=None):
    if list is None:
        list = []
    list.append(item)
    return list
```

---

## 📚 PYTHONIC PATTERNS

### Pattern 1: Enumerate

**✅ CORRECT:**
```python
for i, value in enumerate(items):
    print(f"Item {i}: {value}")
```

**❌ WRONG:**
```python
for i in range(len(items)):
    print(f"Item {i}: {items[i]}")
```

---

### Pattern 2: Zip

**✅ CORRECT:**
```python
for name, age in zip(names, ages):
    print(f"{name} is {age}")
```

**❌ WRONG:**
```python
for i in range(len(names)):
    print(f"{names[i]} is {ages[i]}")
```

---

### Pattern 3: Dict.get() with Default

**✅ CORRECT:**
```python
value = config.get('timeout', 30)
```

**❌ WRONG:**
```python
if 'timeout' in config:
    value = config['timeout']
else:
    value = 30
```

---

### Pattern 4: String Join

**✅ CORRECT:**
```python
result = ', '.join(items)
```

**❌ WRONG:**
```python
result = ''
for i, item in enumerate(items):
    result += item
    if i < len(items) - 1:
        result += ', '
```

---

### Pattern 5: F-strings

**✅ CORRECT:**
```python
message = f"Hello, {name}! You are {age} years old."
```

**❌ WRONG:**
```python
message = "Hello, " + name + "! You are " + str(age) + " years old."
```

---

## ✅ QUALITY CHECKLIST

Before committing code:

- [ ] Consistent naming conventions
- [ ] No magic numbers (use constants)
- [ ] Functions < 50 lines
- [ ] No deep nesting (< 4 levels)
- [ ] Comments explain why, not what
- [ ] Type hints on public APIs
- [ ] No mutable default arguments
- [ ] Context managers for resources
- [ ] List comprehensions for simple cases
- [ ] Pythonic patterns used (enumerate, zip, etc.)
- [ ] Tests written
- [ ] Docstrings for public functions

---

## 🔗 CROSS-REFERENCES

### Related Patterns

- **LANG-PY-01**: Naming conventions
- **LANG-PY-03**: Documentation standards
- **LANG-PY-04**: Function design
- **LANG-PY-05**: Import organization
- **LANG-PY-06**: Type hints
- **LANG-PY-08**: Data structures

### Anti-Patterns

- **AP-20**: Poor readability
- **AP-21**: Inconsistent style
- **AP-22**: Missing tests

---

## 🤝 CONTRIBUTORS

**Original Author:** SIMAv4 Phase 5.1  
**Source Material:** SUGA-ISP Python standards  
**Extracted From:** LANG-PY-03-through-08 consolidated file  
**Last Reviewed:** 2025-11-01

---

**END OF LANG-PY-07**

**Lines:** ~390  
**REF-ID:** LANG-PY-07  
**Status:** Active  
**Next:** LANG-PY-08 (Data Structures)
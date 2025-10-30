# File: LANG-PY-03-through-08_Python-Patterns-Condensed.md

# LANG-PY-03 through LANG-PY-08: Python Language Patterns (Condensed)

**Category:** Language Patterns
**Language:** Python
**Count:** 6 patterns
**Created:** 2025-10-29
**Last Updated:** 2025-10-29

---

## 📋 CONTENTS

This file contains six Python language patterns in condensed format:

- **LANG-PY-03**: Python Documentation Standards
- **LANG-PY-04**: Python Function Design Patterns
- **LANG-PY-05**: Python Import and Module Organization
- **LANG-PY-06**: Python Type Hints and Annotations
- **LANG-PY-07**: Python Code Quality Standards
- **LANG-PY-08**: Python Data Structures and Idioms

---

# LANG-PY-03: Python Documentation Standards

**Priority:** 🟡 HIGH
**Status:** Active

## Summary

Comprehensive documentation standards using docstrings for functions, classes, and modules. Good documentation makes code self-explaining and reduces onboarding time.

## Core Rules

### Rule 1: All Public Functions Need Docstrings

**✅ CORRECT:**
```python
def calculate_total(items, tax_rate=0.08):
    """Calculate total price including tax.
    
    Args:
        items (list): List of item prices
        tax_rate (float, optional): Tax rate as decimal. Default: 0.08
    
    Returns:
        float: Total price including tax
    
    Raises:
        ValueError: If items is empty or tax_rate is negative
    
    Example:
        >>> calculate_total([10.00, 20.00], tax_rate=0.10)
        33.00
    """
    if not items:
        raise ValueError("Items cannot be empty")
    if tax_rate < 0:
        raise ValueError("Tax rate must be non-negative")
    
    subtotal = sum(items)
    return subtotal * (1 + tax_rate)
```

### Rule 2: Classes Need Module, Class, and Method Docstrings

**✅ CORRECT:**
```python
class CacheManager:
    """Manages in-memory cache with TTL support.
    
    Provides methods to store, retrieve, and manage cached values
    with automatic expiration based on time-to-live (TTL).
    
    Attributes:
        max_size (int): Maximum number of cached items
        default_ttl (int): Default TTL in seconds
    
    Example:
        >>> cache = CacheManager(max_size=100)
        >>> cache.set('key', 'value', ttl=60)
        >>> cache.get('key')
        'value'
    """
    
    def __init__(self, max_size=1000, default_ttl=300):
        """Initialize cache manager.
        
        Args:
            max_size (int): Maximum cache entries. Default: 1000
            default_ttl (int): Default TTL in seconds. Default: 300
        """
        self.max_size = max_size
        self.default_ttl = default_ttl
        self._cache = {}
    
    def get(self, key):
        """Retrieve value from cache.
        
        Args:
            key (str): Cache key
        
        Returns:
            Any: Cached value or None if not found/expired
        """
        # Implementation
        pass
```

### Rule 3: Document Exceptions

**Always document what exceptions can be raised:**
```python
def validate_email(email):
    """Validate email address format.
    
    Args:
        email (str): Email address to validate
    
    Returns:
        bool: True if valid, False otherwise
    
    Raises:
        TypeError: If email is not a string
        ValueError: If email is empty string
    """
    if not isinstance(email, str):
        raise TypeError(f"Email must be str, got {type(email)}")
    if not email:
        raise ValueError("Email cannot be empty")
    return '@' in email and '.' in email.split('@')[1]
```

## Docstring Styles

**Google Style (Recommended):**
```python
def function(arg1, arg2):
    """Summary line.
    
    Longer description if needed.
    
    Args:
        arg1 (type): Description
        arg2 (type): Description
    
    Returns:
        type: Description
    
    Raises:
        ExceptionType: When this happens
    """
```

**NumPy Style:**
```python
def function(arg1, arg2):
    """Summary line.
    
    Parameters
    ----------
    arg1 : type
        Description
    arg2 : type
        Description
    
    Returns
    -------
    type
        Description
    """
```

## What to Document

**Always document:**
- [ ] Function/method purpose
- [ ] Parameters (type, description, default values)
- [ ] Return value (type, description)
- [ ] Exceptions raised
- [ ] Side effects (if any)
- [ ] Examples (for complex functions)

**Don't over-document:**
- Private helpers (optional, use sparingly)
- Obvious getters/setters
- Self-explanatory one-liners

---

# LANG-PY-04: Python Function Design Patterns

**Priority:** 🟡 HIGH
**Status:** Active

## Summary

Best practices for designing clear, maintainable functions including size limits, parameter patterns, return value conventions, and single responsibility principle.

## Core Rules

### Rule 1: Keep Functions Small (<50 lines)

**❌ WRONG - God function:**
```python
def handle_request(event):
    # 200+ lines of mixed concerns
    # Validation, authentication, business logic,
    # response formatting all in one function
    pass
```

**✅ CORRECT - Small focused functions:**
```python
def handle_request(event):
    """Handle incoming request."""
    request = parse_request(event)
    validate_request(request)
    authenticate_user(request)
    result = process_request(request)
    return format_response(result)

def validate_request(request):
    """Validate request structure (10 lines)."""
    pass

def authenticate_user(request):
    """Authenticate user (15 lines)."""
    pass

def process_request(request):
    """Process business logic (20 lines)."""
    pass

def format_response(result):
    """Format response (8 lines)."""
    pass
```

### Rule 2: Limit Parameters (3-5 max)

**❌ TOO MANY parameters:**
```python
def create_user(name, email, age, address, phone, city, state, zip, country):
    pass  # 9 parameters - too many!
```

**✅ CORRECT - Use objects or dicts:**
```python
def create_user(user_data):
    """Create user from data dict.
    
    Args:
        user_data (dict): User information with keys:
            name, email, age, address, phone, city, state, zip, country
    """
    pass

# Or use dataclass
from dataclasses import dataclass

@dataclass
class UserData:
    name: str
    email: str
    age: int
    address: str
    phone: str
    city: str
    state: str
    zip_code: str
    country: str

def create_user(user_data: UserData):
    """Create user from UserData object."""
    pass
```

### Rule 3: Single Responsibility

**Each function should do ONE thing:**
```python
✅ GOOD - Single purpose
def calculate_total(items):
    """Calculate sum of item prices."""
    return sum(item.price for item in items)

def apply_discount(total, discount_rate):
    """Apply discount to total."""
    return total * (1 - discount_rate)

def calculate_tax(amount, tax_rate):
    """Calculate tax on amount."""
    return amount * tax_rate

❌ BAD - Multiple responsibilities
def process_order(items, discount, tax):
    """Calculate total, apply discount, add tax, format response."""
    # Doing too many things
    total = sum(item.price for item in items)
    discounted = total * (1 - discount)
    with_tax = discounted * (1 + tax)
    return {
        'subtotal': total,
        'discount': total - discounted,
        'tax': with_tax - discounted,
        'total': with_tax
    }
```

### Rule 4: Clear Return Values

**Be consistent with return types:**
```python
✅ GOOD - Consistent return
def get_user(user_id):
    """Get user by ID.
    
    Returns:
        dict: User data or None if not found
    """
    if user_id in users:
        return users[user_id]
    return None  # Consistent - always dict or None

❌ BAD - Inconsistent return
def get_user(user_id):
    if user_id in users:
        return users[user_id]  # Returns dict
    return False  # Returns bool - inconsistent!
```

### Rule 5: Use Default Arguments Wisely

**✅ GOOD defaults:**
```python
def cache_set(key, value, ttl=300):
    """Set cache value with TTL.
    
    Args:
        key (str): Cache key
        value (Any): Value to cache
        ttl (int): Time to live in seconds. Default: 300
    """
    pass

def http_get(url, timeout=30, retries=3):
    """HTTP GET with sensible defaults."""
    pass
```

**❌ AVOID mutable defaults:**
```python
# WRONG
def add_item(item, items=[]):  # Mutable default - BAD!
    items.append(item)
    return items

# CORRECT
def add_item(item, items=None):
    if items is None:
        items = []
    items.append(item)
    return items
```

## Function Size Guidelines

| Lines | Assessment | Action |
|-------|-----------|--------|
| 1-20 | ✅ Good | Keep as is |
| 21-30 | 🟡 Acceptable | Consider splitting if complex |
| 31-50 | 🟠 Warning | Should probably split |
| 51+ | 🔴 Too Long | Refactor immediately |

---

# LANG-PY-05: Python Import and Module Organization

**Priority:** 🔴 CRITICAL
**Status:** Active

## Summary

Best practices for organizing imports, avoiding circular dependencies, using lazy imports, and structuring modules for maintainability.

## Core Rules

### Rule 1: Import Order (PEP 8)

**Standard order:**
```python
# 1. Standard library imports
import os
import sys
import time
from typing import Dict, List, Optional

# 2. Third-party imports
import requests
import boto3

# 3. Local application imports
from cache_core import CacheManager
from logging_core import log_info
import gateway
```

### Rule 2: Avoid Wildcard Imports

**❌ WRONG:**
```python
from module import *  # Unclear what's imported
```

**✅ CORRECT:**
```python
from module import function1, function2, function3
# or
import module
```

### Rule 3: Use Absolute Imports

**✅ PREFERRED:**
```python
from cache_core import get_value
from interface_logging import log_info
```

**❌ AVOID relative imports:**
```python
from .cache_core import get_value  # Confusing
from ..parent import something  # Hard to track
```

### Rule 4: Lazy Imports for Heavy Modules

**For cold start optimization:**
```python
def expensive_operation():
    """Import heavy module only when needed."""
    import pandas as pd  # Lazy load
    import numpy as np
    
    # Use modules
    df = pd.DataFrame(data)
    return np.mean(df)
```

**For avoiding circular imports:**
```python
def cross_interface_call():
    """Import at function level to avoid circular dependency."""
    import gateway
    return gateway.cache_get(key)
```

### Rule 5: Module Organization

**Flat structure preferred:**
```
src/
├── cache_core.py
├── logging_core.py
├── gateway.py
├── interface_cache.py
└── interface_logging.py
```

**Avoid deep nesting:**
```
# Too complex
src/
└── core/
    └── implementations/
        └── cache/
            └── operations/
                └── get.py
```

## Import Anti-Patterns

**❌ NEVER:**
- Circular imports (A imports B, B imports A)
- Import * (wildcard)
- Deep relative imports (../../module)
- Module-level imports of heavy libraries (unless needed by 90%+ of invocations)

**✅ ALWAYS:**
- Specific imports
- Absolute import paths
- Lazy imports for optimization
- Clear, flat structure

---

# LANG-PY-06: Python Type Hints and Annotations

**Priority:** 🟢 MEDIUM
**Status:** Active

## Summary

Type hints improve code clarity, enable IDE assistance, and catch errors early. Use consistently for better maintainability.

## Core Patterns

### Basic Type Hints

```python
from typing import List, Dict, Optional, Union, Tuple, Any

def greet(name: str) -> str:
    """Greet user by name."""
    return f"Hello, {name}!"

def calculate_total(items: List[float]) -> float:
    """Calculate sum of prices."""
    return sum(items)

def get_user(user_id: int) -> Optional[Dict[str, Any]]:
    """Get user or None if not found."""
    if user_id in users:
        return users[user_id]
    return None

def process_data(data: Union[str, bytes]) -> Tuple[bool, str]:
    """Process string or bytes data."""
    # Returns (success, message)
    return True, "Processed successfully"
```

### Generic Types

```python
from typing import TypeVar, Generic, Callable

T = TypeVar('T')

def get_first(items: List[T]) -> Optional[T]:
    """Get first item or None."""
    return items[0] if items else None

def apply_function(func: Callable[[int], str], value: int) -> str:
    """Apply function to value."""
    return func(value)
```

### Class Type Hints

```python
class CacheManager:
    """Cache manager with type hints."""
    
    def __init__(self, max_size: int = 1000) -> None:
        self.max_size: int = max_size
        self._cache: Dict[str, Any] = {}
    
    def get(self, key: str) -> Optional[Any]:
        """Get value from cache."""
        return self._cache.get(key)
    
    def set(self, key: str, value: Any, ttl: int = 300) -> None:
        """Set cache value."""
        self._cache[key] = value
```

### Type Hints for Lazy Imports

```python
from typing import TYPE_CHECKING

if TYPE_CHECKING:
    from cache_core import CacheResult

def cache_get(key: str) -> 'CacheResult':
    """Get from cache with type hint."""
    import cache_core  # Lazy import
    return cache_core.get(key)
```

## When to Use Type Hints

**✅ Always use for:**
- Public API functions
- Function parameters
- Function return values
- Class attributes

**🟡 Optional for:**
- Private functions
- Obvious types (e.g., `count = 0`)
- Simple utility functions

---

# LANG-PY-07: Python Code Quality Standards

**Priority:** 🟡 HIGH
**Status:** Active

## Summary

Code quality standards including readability, maintainability, testing, and consistency patterns that make Python code professional and sustainable.

## Core Principles

### Principle 1: Consistency Over Cleverness

**✅ GOOD - Predictable:**
```python
# Same pattern everywhere
def process_user():
    data = fetch_data()
    validated = validate(data)
    return transform(validated)

def process_order():
    data = fetch_data()
    validated = validate(data)
    return transform(validated)

# Easy to understand - consistent structure
```

**❌ BAD - "Clever" but inconsistent:**
```python
# Different pattern each time
def process_user():
    return transform(validate(fetch_data()))  # One-liner

def process_order():
    # Multi-step with different order
    validated = validate_order()
    data = get_order_data(validated)
    return data.transform()
```

### Principle 2: Explicit is Better Than Implicit

**✅ CLEAR:**
```python
def calculate_price(base_price, tax_rate, discount_rate):
    """Calculate final price with explicit steps."""
    price_with_tax = base_price * (1 + tax_rate)
    final_price = price_with_tax * (1 - discount_rate)
    return final_price
```

**❌ UNCLEAR:**
```python
def calc(b, t, d):
    """What does this do?"""
    return b * (1 + t) * (1 - d)
```

### Principle 3: DRY (Don't Repeat Yourself)

**✅ GOOD - Extract common logic:**
```python
def validate_email(email):
    return '@' in email and '.' in email.split('@')[1]

def validate_user_email(user):
    return validate_email(user.email)

def validate_contact_email(contact):
    return validate_email(contact.email)
```

**❌ BAD - Repeated logic:**
```python
def validate_user_email(user):
    return '@' in user.email and '.' in user.email.split('@')[1]

def validate_contact_email(contact):
    return '@' in contact.email and '.' in contact.email.split('@')[1]
```

### Principle 4: Fail Fast

**✅ GOOD - Validate early:**
```python
def process_order(order_data):
    """Validate immediately."""
    if not order_data:
        raise ValueError("Order data required")
    if 'items' not in order_data:
        raise ValueError("Order must have items")
    if not order_data['items']:
        raise ValueError("Order items cannot be empty")
    
    # Now safe to process
    return calculate_total(order_data['items'])
```

### Principle 5: Avoid Magic Numbers

**❌ BAD:**
```python
def calculate_fee(amount):
    return amount * 0.029 + 0.30  # What are these numbers?
```

**✅ GOOD:**
```python
STRIPE_PERCENTAGE_FEE = 0.029  # 2.9%
STRIPE_FIXED_FEE = 0.30  # $0.30

def calculate_stripe_fee(amount):
    """Calculate Stripe processing fee."""
    return amount * STRIPE_PERCENTAGE_FEE + STRIPE_FIXED_FEE
```

## Code Quality Checklist

- [ ] Consistent naming conventions (PEP 8)
- [ ] Functions < 50 lines
- [ ] No magic numbers
- [ ] Clear variable names
- [ ] Proper exception handling
- [ ] Comprehensive docstrings
- [ ] Type hints on public APIs
- [ ] No code duplication
- [ ] Early validation (fail fast)
- [ ] Explicit over implicit

---

# LANG-PY-08: Python Data Structures and Idioms

**Priority:** 🟢 MEDIUM
**Status:** Active

## Summary

Pythonic patterns for working with data structures, comprehensions, context managers, and common idioms that make code clean and efficient.

## Core Patterns

### List Comprehensions

**✅ PYTHONIC:**
```python
# List comprehension
squares = [x ** 2 for x in range(10)]

# With condition
evens = [x for x in range(20) if x % 2 == 0]

# Nested
matrix = [[i * j for j in range(5)] for i in range(5)]
```

**❌ VERBOSE:**
```python
# Traditional loop
squares = []
for x in range(10):
    squares.append(x ** 2)
```

### Dictionary Comprehensions

```python
# Dict comprehension
user_ages = {user.name: user.age for user in users}

# With condition
adults = {name: age for name, age in ages.items() if age >= 18}

# Transform keys and values
uppercase_doubled = {k.upper(): v * 2 for k, v in data.items()}
```

### Set Comprehensions

```python
# Unique squares
unique_squares = {x ** 2 for x in numbers}

# Unique emails
unique_emails = {user.email.lower() for user in users}
```

### Generator Expressions

```python
# Memory efficient for large datasets
sum_of_squares = sum(x ** 2 for x in range(1000000))

# Lazy evaluation
lines = (line.strip() for line in file)
```

### Context Managers

**✅ ALWAYS use for resources:**
```python
# File handling
with open('file.txt', 'r') as f:
    content = f.read()

# Multiple context managers
with open('input.txt') as infile, open('output.txt', 'w') as outfile:
    outfile.write(infile.read())

# Custom context manager
from contextlib import contextmanager

@contextmanager
def timer(name):
    start = time.time()
    yield
    print(f"{name} took {time.time() - start:.2f}s")

with timer("operation"):
    expensive_operation()
```

### Unpacking and Multiple Assignment

```python
# Tuple unpacking
x, y, z = (1, 2, 3)

# Swap values
a, b = b, a

# Extended unpacking
first, *middle, last = [1, 2, 3, 4, 5]

# Function returns
success, message = validate_data(data)
```

### Default Dictionary Values

```python
# get() with default
value = config.get('timeout', 30)

# setdefault()
cache.setdefault('key', default_value)

# defaultdict
from collections import defaultdict
counts = defaultdict(int)
for item in items:
    counts[item] += 1
```

### String Formatting

**✅ MODERN (f-strings):**
```python
name = "Alice"
age = 30
message = f"Hello, {name}! You are {age} years old."
result = f"Total: ${total:.2f}"
```

**🟡 ACCEPTABLE (format()):**
```python
message = "Hello, {}! You are {} years old.".format(name, age)
```

**❌ AVOID (% formatting):**
```python
message = "Hello, %s! You are %d years old." % (name, age)
```

### Enumerate and Zip

```python
# enumerate - get index and value
for i, value in enumerate(items):
    print(f"Item {i}: {value}")

# zip - iterate multiple lists
for name, age in zip(names, ages):
    print(f"{name} is {age} years old")
```

### Any and All

```python
# Check if any item matches
has_admin = any(user.is_admin for user in users)

# Check if all items match
all_valid = all(validate(item) for item in items)
```

### Sorting

```python
# Sort with key
sorted_users = sorted(users, key=lambda u: u.age)

# Sort descending
sorted_users = sorted(users, key=lambda u: u.age, reverse=True)

# Multiple sort keys
from operator import attrgetter
sorted_users = sorted(users, key=attrgetter('age', 'name'))
```

---

## 🔗 CROSS-REFERENCES

### Pattern Dependencies

**LANG-PY-01** (Naming) is foundational for:
- LANG-PY-03 (Documentation)
- LANG-PY-04 (Function Design)
- LANG-PY-07 (Code Quality)

**LANG-PY-02** (Exceptions) connects to:
- LANG-PY-03 (Document exceptions)
- LANG-PY-04 (Error handling in functions)

**LANG-PY-05** (Imports) connects to:
- LANG-PY-07 (Quality standards)
- LANG-PY-08 (Module organization)

**LANG-PY-06** (Type Hints) enhances:
- LANG-PY-03 (Documentation)
- LANG-PY-04 (Function signatures)

---

## 🤝 CONTRIBUTORS

**Original Author:** SIMAv4 Phase 4.0
**Source Material:** SUGA-ISP Python standards
**Last Reviewed:** 2025-10-29

---

**END OF CONDENSED LANGUAGE ENTRIES**

**REF-IDs:** LANG-PY-03 through LANG-PY-08
**Entry Count:** 6 patterns
**Status:** Active

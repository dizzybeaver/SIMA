# LANG-PY-04: Python Function Design Patterns

**REF-ID:** LANG-PY-04  
**Category:** Language Patterns  
**Subcategory:** Function Design  
**Language:** Python  
**Priority:** 🟡 HIGH  
**Status:** Active  
**Created:** 2025-11-01  
**Last Updated:** 2025-11-01

---

## 📋 SUMMARY

Best practices for designing Python functions: size limits, parameter handling, return values, and single responsibility principle.

---

## 🎯 CORE RULES

### Rule 1: Keep Functions Small (< 50 Lines)

**✅ CORRECT:**
```python
def process_order(order_id):
    """Process a single order."""
    order = fetch_order(order_id)
    validate_order(order)
    charge_payment(order)
    update_inventory(order)
    send_confirmation(order)
    return order
```

**❌ WRONG:**
```python
def process_order(order_id):
    """Process a single order."""
    # 200 lines of code doing everything...
```

**Why:**
- Easier to understand
- Easier to test
- Easier to reuse
- Easier to maintain

---

### Rule 2: Limit Parameters (3-5 Maximum)

**❌ WRONG:**
```python
def create_user(name, email, age, address, city, state, 
                zip_code, phone, preferences, role):
    # Too many parameters
```

**✅ CORRECT:**
```python
from dataclasses import dataclass

@dataclass
class UserInfo:
    name: str
    email: str
    age: int
    address: str
    city: str
    state: str
    zip_code: str
    phone: str

def create_user(user_info: UserInfo, preferences: dict, role: str):
    """Create user with grouped parameters."""
```

**Alternatives:**
- Use dataclasses or namedtuples
- Use **kwargs for optional params
- Group related params into objects

---

### Rule 3: Single Responsibility Principle

Each function should do ONE thing well.

**❌ WRONG:**
```python
def process_data_and_send_email(data):
    """Does too many things."""
    # Validate data
    if not validate(data):
        raise ValueError("Invalid data")
    
    # Transform data
    transformed = transform(data)
    
    # Save to database
    save_to_db(transformed)
    
    # Send email notification
    send_email(f"Processed {len(data)} items")
```

**✅ CORRECT:**
```python
def process_data(data):
    """Process and save data."""
    validate_data(data)
    transformed = transform_data(data)
    save_to_database(transformed)
    return transformed

def notify_processing_complete(item_count):
    """Send email notification."""
    send_email(f"Processed {item_count} items")

# Caller coordinates:
def handle_data_upload(data):
    result = process_data(data)
    notify_processing_complete(len(result))
```

---

### Rule 4: Use Type Hints

**✅ CORRECT:**
```python
from typing import List, Optional, Dict, Any

def find_user(user_id: int) -> Optional[Dict[str, Any]]:
    """Find user by ID.
    
    Args:
        user_id: User's unique identifier
    
    Returns:
        User dict if found, None otherwise
    """
    return database.get(user_id)

def calculate_total(prices: List[float], tax_rate: float = 0.08) -> float:
    """Calculate total with tax.
    
    Args:
        prices: List of item prices
        tax_rate: Tax rate as decimal (default: 0.08)
    
    Returns:
        Total price including tax
    """
    subtotal = sum(prices)
    return subtotal * (1 + tax_rate)
```

---

### Rule 5: Use Default Arguments Carefully

**✅ CORRECT (Immutable Defaults):**
```python
def greet(name: str, greeting: str = "Hello") -> str:
    return f"{greeting}, {name}!"
```

**❌ WRONG (Mutable Defaults):**
```python
def add_item(item, items=[]):  # BAD: Shared mutable default
    items.append(item)
    return items
```

**✅ CORRECT (Mutable Defaults):**
```python
def add_item(item, items=None):
    if items is None:
        items = []
    items.append(item)
    return items
```

---

### Rule 6: Return Consistently

**❌ WRONG (Inconsistent Returns):**
```python
def find_value(key):
    if key in cache:
        return cache[key]
    # Sometimes returns None, sometimes doesn't return
```

**✅ CORRECT:**
```python
def find_value(key):
    if key in cache:
        return cache[key]
    return None  # Explicit return
```

**✅ BETTER (Use Optional):**
```python
from typing import Optional

def find_value(key: str) -> Optional[str]:
    return cache.get(key)
```

---

### Rule 7: Use *args and **kwargs Appropriately

**✅ CORRECT:**
```python
def log_message(level: str, message: str, **kwargs):
    """Log message with optional metadata.
    
    Args:
        level: Log level (info, warning, error)
        message: Log message
        **kwargs: Additional metadata
    """
    metadata = {
        'timestamp': datetime.now(),
        **kwargs
    }
    logger.log(level, message, extra=metadata)

# Usage:
log_message('info', 'User logged in', user_id=123, ip='1.2.3.4')
```

**Use cases:**
- *args: Variable positional arguments
- **kwargs: Variable keyword arguments
- Wrapper functions
- Decorator functions

---

## 🔧 PATTERNS

### Pattern 1: Early Return Pattern

**✅ CORRECT:**
```python
def process_request(request):
    if not request.is_valid():
        return error_response("Invalid request")
    
    if not request.is_authenticated():
        return error_response("Not authenticated")
    
    if not request.has_permission():
        return error_response("No permission")
    
    return handle_request(request)
```

**Why:**
- Reduces nesting
- Makes error conditions obvious
- Easier to read

---

### Pattern 2: Guard Clauses

**✅ CORRECT:**
```python
def calculate_discount(price, customer_type):
    if price <= 0:
        raise ValueError("Price must be positive")
    
    if customer_type not in ['regular', 'premium', 'vip']:
        raise ValueError(f"Invalid customer type: {customer_type}")
    
    # Main logic here
    discount_rates = {'regular': 0, 'premium': 0.1, 'vip': 0.2}
    return price * (1 - discount_rates[customer_type])
```

---

### Pattern 3: Dependency Injection

**✅ CORRECT:**
```python
def send_notification(message: str, sender=None):
    """Send notification using injected sender."""
    if sender is None:
        sender = get_default_sender()
    
    return sender.send(message)

# Easy to test:
def test_send_notification():
    mock_sender = MockSender()
    send_notification("test", sender=mock_sender)
    assert mock_sender.was_called
```

---

### Pattern 4: Builder Pattern for Complex Objects

**✅ CORRECT:**
```python
class QueryBuilder:
    def __init__(self):
        self._filters = []
        self._order_by = None
        self._limit = None
    
    def filter(self, field, value):
        self._filters.append((field, value))
        return self  # Enable chaining
    
    def order_by(self, field):
        self._order_by = field
        return self
    
    def limit(self, count):
        self._limit = count
        return self
    
    def build(self):
        return Query(self._filters, self._order_by, self._limit)

# Usage:
query = (QueryBuilder()
    .filter('status', 'active')
    .filter('age', 25)
    .order_by('name')
    .limit(10)
    .build())
```

---

## ⚠️ ANTI-PATTERNS

### AP-1: God Functions

**❌ WRONG:**
```python
def do_everything():
    """Does initialization, processing, validation, 
    database operations, API calls, and cleanup."""
    # 500+ lines of code
```

### AP-2: Boolean Parameters

**❌ WRONG:**
```python
def save_user(user, send_email=True):
    # Boolean parameter obscures intent
```

**✅ CORRECT:**
```python
def save_user(user):
    return user.save()

def save_user_and_notify(user):
    user.save()
    send_welcome_email(user)
```

### AP-3: Output Parameters

**❌ WRONG:**
```python
def process_data(data, results=[]):
    """Modifies results parameter."""
    results.append(transform(data))
```

**✅ CORRECT:**
```python
def process_data(data):
    """Returns new results."""
    return transform(data)
```

---

## 📚 EXAMPLES

### Example 1: Data Processing Pipeline

```python
from typing import List, Callable

def apply_transformations(
    data: List[dict],
    transformations: List[Callable]
) -> List[dict]:
    """Apply series of transformations to data.
    
    Args:
        data: Input data
        transformations: List of transformation functions
    
    Returns:
        Transformed data
    """
    result = data
    for transform in transformations:
        result = [transform(item) for item in result]
    return result

# Usage:
def normalize_price(item):
    item['price'] = round(item['price'], 2)
    return item

def add_tax(item):
    item['total'] = item['price'] * 1.08
    return item

processed = apply_transformations(
    raw_data,
    [normalize_price, add_tax]
)
```

### Example 2: Context-Aware Function

```python
from contextlib import contextmanager
from typing import Generator

@contextmanager
def temporary_setting(setting: str, value: Any) -> Generator:
    """Temporarily change a setting.
    
    Args:
        setting: Setting name
        value: Temporary value
    
    Yields:
        None
    """
    old_value = get_setting(setting)
    set_setting(setting, value)
    try:
        yield
    finally:
        set_setting(setting, old_value)

# Usage:
with temporary_setting('debug_mode', True):
    # Debug mode is True here
    run_diagnostic()
# Debug mode is restored here
```

---

## ✅ VERIFICATION CHECKLIST

Before committing:

- [ ] Function < 50 lines
- [ ] 3-5 parameters maximum
- [ ] Single responsibility
- [ ] Type hints on public functions
- [ ] No mutable default arguments
- [ ] Consistent return values
- [ ] Descriptive name (verb + noun)
- [ ] Docstring with Args/Returns/Raises
- [ ] No boolean parameters
- [ ] Early returns for error cases

---

## 🔗 CROSS-REFERENCES

### Related Patterns

- **LANG-PY-01**: Naming conventions for functions
- **LANG-PY-03**: Exception handling in functions
- **LANG-PY-06**: Type hints for parameters/returns
- **LANG-PY-07**: Code quality standards

### Anti-Patterns

- **AP-06**: God functions
- **AP-07**: Too many parameters
- **AP-21**: Poor function design

---

## 🤝 CONTRIBUTORS

**Original Author:** SIMAv4 Phase 5.1  
**Source Material:** SUGA-ISP Python standards  
**Extracted From:** LANG-PY-03-through-08 consolidated file  
**Last Reviewed:** 2025-11-01

---

**END OF LANG-PY-04**

**Lines:** ~395  
**REF-ID:** LANG-PY-04  
**Status:** Active  
**Next:** LANG-PY-05 (Import Organization)
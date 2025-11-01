# File: LANG-PY-01_Python-Naming-Conventions.md

# LANG-PY-01: Python Naming Conventions

**Category:** Language Patterns
**Language:** Python
**Priority:** 🟡 HIGH
**Status:** Active
**Created:** 2025-10-29
**Last Updated:** 2025-10-29

---

## 📋 SUMMARY

Consistent Python naming conventions following PEP 8 standards for functions, variables, classes, constants, and modules. Using proper naming makes code more readable, searchable, and maintainable.

---

## 🎯 CORE PRINCIPLE

**Follow PEP 8 naming conventions consistently across entire codebase. Predictability and consistency reduce cognitive load and make code easier to understand and maintain.**

---

## 📐 NAMING RULES

### Rule 1: Functions and Variables - snake_case

**Functions:**
```python
✅ CORRECT
def calculate_total(items):
    """Calculate total price of items."""
    pass

def process_user_request(request):
    """Process incoming user request."""
    pass

def get_cache_value(key):
    """Retrieve value from cache."""
    pass

❌ WRONG - Don't use camelCase or PascalCase
def calculateTotal(items):  # Wrong style
    pass

def ProcessRequest(request):  # Wrong style
    pass
```

**Variables:**
```python
✅ CORRECT
user_count = 10
total_amount = 100.50
cache_hit_rate = 0.85
max_retry_count = 3

❌ WRONG
userCount = 10  # camelCase - wrong
TotalAmount = 100.50  # PascalCase - wrong
CacheHitRate = 0.85  # PascalCase - wrong
```

---

### Rule 2: Constants - UPPER_SNAKE_CASE

**Module-level constants:**
```python
✅ CORRECT
MAX_CONNECTIONS = 100
DEFAULT_TIMEOUT = 30
API_BASE_URL = "https://api.example.com"
CACHE_TTL_SECONDS = 300
MAX_RETRY_ATTEMPTS = 3

❌ WRONG
max_connections = 100  # lowercase - looks like variable
MaxConnections = 100  # PascalCase - wrong
defaultTimeout = 30  # camelCase - wrong
```

**Why use all caps:**
- Signals that value shouldn't change
- Easy to identify constants at a glance
- Distinguishes from regular variables

---

### Rule 3: Classes - PascalCase (CapWords)

**Class names:**
```python
✅ CORRECT
class UserManager:
    """Manages user operations."""
    pass

class CacheEntry:
    """Represents a cached item."""
    pass

class HTTPClient:
    """Client for HTTP requests."""
    pass

class ConfigurationError(Exception):
    """Custom exception for config errors."""
    pass

❌ WRONG
class user_manager:  # snake_case - wrong
    pass

class cacheEntry:  # camelCase - wrong
    pass

class Http_Client:  # Mixed case - wrong
    pass
```

**Special case - Acronyms:**
```python
✅ PREFERRED - Treat acronyms as words
class HttpClient:  # Easier to read
    pass

class JsonParser:
    pass

class XmlProcessor:
    pass

✅ ACCEPTABLE - For very common acronyms
class HTTPError:  # HTTP is universally known
    pass

class JSONDecoder:
    pass
```

---

### Rule 4: Methods - snake_case

**Instance methods:**
```python
class DataProcessor:
    def process_data(self):
        """Process data items."""
        pass
    
    def validate_input(self, data):
        """Validate input data."""
        pass
    
    def _internal_helper(self):
        """Private method (leading underscore)."""
        pass
    
    def __private_method(self):
        """Name-mangled private method."""
        pass
```

**Class methods and static methods:**
```python
class Factory:
    @classmethod
    def create_from_config(cls, config):
        """Create instance from config."""
        return cls()
    
    @staticmethod
    def validate_format(data):
        """Validate data format."""
        pass
```

---

### Rule 5: Private/Protected Members - Leading Underscore

**Private (single underscore):**
```python
class CacheManager:
    def __init__(self):
        self._cache_store = {}  # Private attribute
    
    def _update_metrics(self):
        """Private method - internal use only."""
        pass
```

**Name-mangled (double underscore):**
```python
class SecureData:
    def __init__(self):
        self.__secret_key = "abc123"  # Name mangled to _SecureData__secret_key
    
    def __validate_secret(self):
        """Name-mangled private method."""
        pass
```

**When to use:**
- Single underscore: "Internal, but not enforced"
- Double underscore: "Really private, name mangling protection"
- Generally prefer single underscore for simplicity

---

### Rule 6: Module Names - lowercase

**Module/package names:**
```python
✅ CORRECT
# cache.py
# logging.py
# http_client.py (underscore OK if improves readability)
# user_manager.py

❌ WRONG
# Cache.py (capitalized)
# Logging.py (capitalized)
# httpClient.py (camelCase)
# UserManager.py (PascalCase)
```

**Package names:**
```
✅ CORRECT
home_assistant/
utils/
core/

❌ WRONG
HomeAssistant/
Utils/
CORE/
```

---

### Rule 7: Exception Classes - PascalCase + "Error"

**Custom exceptions:**
```python
✅ CORRECT
class ValidationError(ValueError):
    """Raised when validation fails."""
    pass

class ConfigurationError(Exception):
    """Raised for configuration issues."""
    pass

class CacheError(RuntimeError):
    """Base class for cache-related errors."""
    pass

class ConnectionTimeoutError(TimeoutError):
    """Raised when connection times out."""
    pass

❌ WRONG
class validation_error(ValueError):  # snake_case - wrong
    pass

class ConfigError(Exception):  # Missing "Error" suffix
    pass

class CACHE_ERROR(Exception):  # All caps - wrong
    pass
```

---

## 🎨 SPECIAL CASES

### Single-Letter Variables

**✅ Acceptable in specific contexts:**
```python
# Loop counters
for i in range(10):
    pass

for j in range(5):
    pass

# Dictionary iteration
for k, v in items.items():
    pass

# File handles
with open('file.txt') as f:
    content = f.read()

# Coordinates/math
x, y = calculate_position()
```

**❌ Avoid for important variables:**
```python
# BAD - What are these?
def calculate(x, y, z):
    return x + y * z  # Unclear purpose

# GOOD - Clear meaning
def calculate_total(quantity, unit_price, tax_rate):
    return quantity * unit_price * (1 + tax_rate)
```

---

### Boolean Variables/Functions

**Use clear boolean naming:**
```python
✅ CORRECT
is_valid = True
has_permission = check_access()
can_edit = user.is_admin()
should_retry = error_count < MAX_RETRIES

def is_expired(timestamp):
    """Check if timestamp is expired."""
    return timestamp < current_time()

def has_access(user, resource):
    """Check if user has access."""
    return user.role in resource.allowed_roles

❌ LESS CLEAR
valid = True  # Is it valid or validating?
permission = check_access()  # The permission itself or a check?
edit = user.is_admin()  # Verb or boolean?
```

---

## 🔧 NAMING PATTERNS

### Pattern 1: Verb + Noun for Functions

**Good function names:**
```python
✅ CLEAR ACTIONS
def calculate_total(items):
    """Calculate sum of item prices."""
    pass

def validate_email(email):
    """Validate email format."""
    pass

def transform_data(data):
    """Transform data to target format."""
    pass

def fetch_user(user_id):
    """Fetch user from database."""
    pass
```

---

### Pattern 2: Noun Phrases for Classes

**Good class names:**
```python
✅ CLEAR ENTITIES
class UserManager:  # Manages users
    pass

class CacheEntry:  # Represents cache entry
    pass

class RequestHandler:  # Handles requests
    pass

class DataProcessor:  # Processes data
    pass
```

---

### Pattern 3: Descriptive Variable Names

**Prefer clarity over brevity:**
```python
✅ CLEAR - Easy to understand
user_authentication_token = "abc123"
maximum_retry_attempts = 3
cache_expiration_time = 300

❌ UNCLEAR - Requires context
token = "abc123"  # What kind of token?
max_retries = 3  # Retries for what?
ttl = 300  # TTL for what?
```

---

## ⚠️ COMMON PITFALLS

### Pitfall 1: Inconsistent Styles in Same File

**❌ WRONG - Mixed styles:**
```python
def ProcessData(UserInput):  # PascalCase function
    maxRetries = 5  # camelCase variable
    TIMEOUT_seconds = 30  # Mixed case constant
    
    for retry_count in range(maxRetries):  # Now snake_case
        pass
```

**✅ CORRECT - Consistent style:**
```python
MAX_RETRIES = 5
TIMEOUT_SECONDS = 30

def process_data(user_input):
    """Process user input."""
    retry_count = 0
    
    for retry_count in range(MAX_RETRIES):
        try:
            return handle_request(user_input)
        except Exception as e:
            log_warning(f"Retry {retry_count}: {e}")
```

---

### Pitfall 2: Using Reserved Words

**❌ AVOID - Shadowing built-ins:**
```python
# Don't shadow Python built-ins
list = [1, 2, 3]  # Shadows built-in list()
dict = {}  # Shadows built-in dict()
str = "hello"  # Shadows built-in str()
id = 123  # Shadows built-in id()
```

**✅ CORRECT - Use descriptive names:**
```python
item_list = [1, 2, 3]
user_dict = {}
message_str = "hello"
user_id = 123
```

---

### Pitfall 3: Abbreviations Without Context

**❌ UNCLEAR:**
```python
def proc_req(req):
    """What does this do?"""
    pass

usr_cnt = 10
tmp_val = calc_val()
```

**✅ CLEAR:**
```python
def process_request(request):
    """Process incoming HTTP request."""
    pass

user_count = 10
temporary_value = calculate_value()
```

---

## 📊 NAMING QUICK REFERENCE

| Type | Convention | Example | Bad Example |
|------|-----------|---------|-------------|
| Function | snake_case | `def get_user():` | `def GetUser():` |
| Variable | snake_case | `user_count = 10` | `userCount = 10` |
| Constant | UPPER_SNAKE | `MAX_SIZE = 100` | `maxSize = 100` |
| Class | PascalCase | `class UserManager:` | `class user_manager:` |
| Method | snake_case | `def process(self):` | `def Process(self):` |
| Private | _leading | `def _helper():` | `def helper():` |
| Exception | PascalCase+Error | `ValueError` | `value_error` |
| Module | lowercase | `cache.py` | `Cache.py` |
| Package | lowercase | `utils/` | `Utils/` |
| Boolean | is_/has_/can_ | `is_valid` | `valid` |

---

## 🔗 RELATED PATTERNS

### Within Language Patterns
- **LANG-PY-03**: Documentation standards (docstring naming)
- **LANG-PY-04**: Function design (naming for clarity)
- **LANG-PY-07**: Code quality (consistency in naming)

### From Project Knowledge
- **AP-22**: Inconsistent naming conventions anti-pattern
- **WISD-04**: Consistency over cleverness
- **LESS-29**: Zero tolerance for violations

---

## 📚 REFERENCES

### Python Standards
- **PEP 8**: Style Guide for Python Code
- **PEP 257**: Docstring Conventions

### Benefits
- **Readability**: Code is read more than written
- **Maintainability**: Consistent patterns reduce mental overhead
- **Searchability**: Predictable names easier to grep/search
- **Team collaboration**: No debates about style

---

## ✅ CHECKLIST

Before committing code, verify:
- [ ] All functions use snake_case
- [ ] All classes use PascalCase
- [ ] All constants use UPPER_SNAKE_CASE
- [ ] No camelCase variables
- [ ] Module names are lowercase
- [ ] Private members use leading underscore
- [ ] No shadowing of built-ins
- [ ] Clear, descriptive names (not abbreviations)
- [ ] Boolean variables use is_/has_/can_ prefixes
- [ ] Consistent style throughout file

---

## 🤝 CONTRIBUTORS

**Original Author:** SIMAv4 Phase 4.0
**Source Material:** SUGA-ISP coding standards
**References:** PEP 8, AP-22, WISD-04

**Last Reviewed By:** Claude
**Review Date:** 2025-10-29

---

## 📝 CHANGE LOG

### [1.0.0] - 2025-10-29
- Initial Python naming conventions documentation
- Extracted from SUGA-ISP project standards
- Comprehensive PEP 8 coverage
- Examples and anti-patterns included

---

**END OF LANGUAGE ENTRY**

**REF-ID:** LANG-PY-01
**Template Version:** 1.0.0
**Entry Type:** Language Pattern
**Status:** Active
**Maintenance:** Review when PEP standards updated

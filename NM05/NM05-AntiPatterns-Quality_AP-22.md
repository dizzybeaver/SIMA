# NM05-AntiPatterns-Quality_AP-22.md - AP-22

# AP-22: Inconsistent Naming Conventions

**Category:** NM05 - Anti-Patterns
**Topic:** Code Quality
**Priority:** 🟢 MEDIUM
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Using different naming styles throughout codebase (camelCase, snake_case, PascalCase randomly mixed) makes code harder to read, search, and understand, violating Python conventions.

---

## Context

Python has established naming conventions (PEP 8). Mixing styles creates cognitive friction and makes code look unprofessional and difficult to maintain.

**Problem:** Harder to read, search, and understand. Team confusion about correct style.

---

## Content

### The Anti-Pattern

```python
# ❌ INCONSISTENT NAMING - Mixed styles
def ProcessData(UserInput):  # PascalCase function? Wrong!
    maxRetries = 5  # camelCase variable? Wrong!
    TIMEOUT_seconds = 30  # Mixed case constant? Wrong!
    
    for retry_count in range(maxRetries):  # Now snake_case?
        try:
            result = CallAPI(UserInput)  # PascalCase again?
            return result
        except Exception as E:  # Capital E? Inconsistent!
            pass

class user_manager:  # snake_case class? Wrong!
    def GetUser(self, userId):  # Mixed styles in one line!
        pass
```

**Problems:**
- Functions use PascalCase (should be snake_case)
- Variables use camelCase (should be snake_case)
- Constants use mixed case (should be UPPER_SNAKE_CASE)
- Classes use snake_case (should be PascalCase)
- No consistency within same file

### Why This Is Wrong

**1. Violates Python Conventions (PEP 8)**
```python
# Python standard:
def function_name():  # snake_case
    variable_name = 10  # snake_case
    CONSTANT_NAME = 20  # UPPER_SNAKE_CASE

class ClassName:  # PascalCase
    pass
```

**2. Harder to Search**
```python
# Want to find all places using timeout
# But is it timeout, timeOut, TimeOut, TIMEOUT?
timeout = 30
timeOut = 30
TimeOut = 30
TIMEOUT = 30

# Have to search 4 different ways!
```

**3. Cognitive Friction**
```python
# Brain has to translate between styles
def processData():  # camelCase
    max_retries = 5  # snake_case
    
# Switching styles forces mental translation
# Slows down reading and comprehension
```

**4. Team Confusion**
```python
# New dev asks: "Which style should I use?"
# No clear answer if codebase is inconsistent
# Leads to more inconsistency
```

### Correct Approach (PEP 8)

```python
# ✅ CORRECT - Consistent PEP 8 naming

# Constants: UPPER_SNAKE_CASE
MAX_RETRIES = 5
TIMEOUT_SECONDS = 30
DEFAULT_CACHE_TTL = 3600

# Functions and variables: snake_case
def process_data(user_input):
    """Process user input and return result."""
    retry_count = 0
    
    for retry_count in range(MAX_RETRIES):
        try:
            result = call_api(user_input)
            return result
        except Exception as e:  # lowercase 'e'
            gateway.log_warning(f"Retry {retry_count}: {e}")
    
    raise RuntimeError("Max retries exceeded")

# Classes: PascalCase
class UserManager:
    """Manages user operations."""
    
    def __init__(self, config):
        self.config = config
    
    def get_user(self, user_id):
        """Retrieve user by ID."""
        return self._fetch_from_db(user_id)
    
    def _fetch_from_db(self, user_id):  # Private: leading underscore
        """Internal method to fetch from database."""
        pass

# Module-level private: leading underscore
_internal_cache = {}

def _helper_function():
    """Module-private helper."""
    pass
```

### Python Naming Conventions (PEP 8)

**Functions and Variables:**
```python
# ✅ snake_case
def calculate_total():
    user_count = 10
    total_amount = 100.50
```

**Constants:**
```python
# ✅ UPPER_SNAKE_CASE
MAX_CONNECTIONS = 100
DEFAULT_TIMEOUT = 30
API_BASE_URL = "https://api.example.com"
```

**Classes:**
```python
# ✅ PascalCase (CapWords)
class HTTPClient:
    pass

class UserManager:
    pass

class CacheEntry:
    pass
```

**Methods:**
```python
# ✅ snake_case
class DataProcessor:
    def process_data(self):
        pass
    
    def _internal_helper(self):  # Private method
        pass
    
    def __private_method(self):  # Name mangling
        pass
```

**Exceptions:**
```python
# ✅ PascalCase, ending in 'Error'
class ValidationError(ValueError):
    pass

class ConfigurationError(Exception):
    pass
```

**Module Names:**
```python
# ✅ Short, lowercase, no underscores if possible
# cache.py
# logging.py
# http_client.py  # Underscore OK if improves readability
```

### Special Cases

**Acronyms in Names:**
```python
# ✅ Treat as normal words
class HttpClient:  # Not HTTPClient (unless very common)
    pass

def get_url():  # Not getURL
    pass

# Exception: Very common acronyms
class HTTPError:  # OK - HTTP is universal
    pass
```

**Single Letter Variables:**
```python
# ✅ OK in specific contexts
for i in range(10):  # Loop counter
    pass

for k, v in dict.items():  # Key, value

with open('file.txt') as f:  # File handle
    pass

# ❌ Not OK for important variables
def calculate(x, y, z):  # Too vague
    return x + y * z  # What are these?

# ✅ Better
def calculate_total(quantity, unit_price, tax_rate):
    return quantity + unit_price * tax_rate
```

### Real SUGA-ISP Examples

**Wrong (early code):**
```python
# Mixed styles
def ProcessRequest(Event):  # PascalCase function
    maxRetries = 5  # camelCase variable
    TIMEOUT_VALUE = 30  # Inconsistent constant
    
    for retryCount in range(maxRetries):
        pass
```

**Correct (current code):**
```python
# Consistent PEP 8
MAX_RETRIES = 5  # Constant
TIMEOUT_SECONDS = 30  # Constant

def process_request(event):  # Function
    """Process incoming request."""
    retry_count = 0  # Variable
    
    for retry_count in range(MAX_RETRIES):
        try:
            return handle_request(event)
        except Exception as e:
            gateway.log_warning(f"Retry {retry_count}: {e}")
```

### Refactoring Strategy

**Step 1: Audit Current Code**
```bash
# Find PascalCase functions (wrong)
grep -E "^def [A-Z]" *.py

# Find camelCase variables (wrong)  
grep -E "[a-z][A-Z]" *.py

# Find lowercase classes (wrong)
grep -E "^class [a-z]" *.py
```

**Step 2: Rename Systematically**
```python
# Use IDE refactoring tools
# Don't manual find/replace - will break things

# In VSCode/PyCharm:
# Right-click → Rename Symbol
# IDE updates all references automatically
```

**Step 3: Add Linting**
```bash
# Install pylint
pip install pylint

# Check naming
pylint --disable=all --enable=invalid-name *.py
```

**Step 4: Pre-commit Hooks**
```yaml
# .pre-commit-config.yaml
repos:
  - repo: https://github.com/PyCQA/pylint
    hooks:
      - id: pylint
        args: [--enable=invalid-name]
```

### Quick Reference Table

| Type | Convention | Example |
|------|-----------|---------|
| Function | snake_case | `def get_user():` |
| Variable | snake_case | `user_count = 10` |
| Constant | UPPER_SNAKE | `MAX_SIZE = 100` |
| Class | PascalCase | `class UserManager:` |
| Method | snake_case | `def process_data(self):` |
| Private | _leading | `def _helper():` |
| Exception | PascalCase+Error | `class ValueError:` |
| Module | lowercase | `cache.py` |

---

## Related Topics

- **AP-20**: God Functions - Code quality
- **AP-21**: Magic Numbers - Clear naming
- **AP-25**: No Docstrings - Documentation standards
- **LESS-07**: Gateway Pattern Simplicity - Consistency matters

---

## Keywords

naming conventions, PEP 8, snake_case, PascalCase, camelCase, code style, consistency, readability

---

## Version History

- **2025-10-24**: Created - Extracted from Part 2, added PEP 8 reference and refactoring guide

---

**File:** `NM05-AntiPatterns-Quality_AP-22.md`
**End of Document**

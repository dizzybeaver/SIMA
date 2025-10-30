# NM05-AntiPatterns-Quality_AP-21.md - AP-21

# AP-21: Magic Numbers

**Category:** NM05 - Anti-Patterns
**Topic:** Code Quality
**Priority:** 🟢 MEDIUM
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Using unexplained numeric literals ("magic numbers") throughout code makes it unclear what they represent, why those specific values were chosen, and where to change them when requirements evolve.

---

## Context

Numbers scattered through code without explanation become mysterious over time. "Why is this 3600?" "What does 128 mean?" "Can I change this 5 or will it break everything?"

**Problem:** Unclear intent, difficult maintenance, accidental bugs from changing wrong values.

---

## Content

### The Anti-Pattern

```python
# ❌ MAGIC NUMBERS
def process_data(data):
    # What do these numbers mean?
    if len(data) > 1024:  # Magic! Why 1024?
        raise ValueError("Too large")
    
    # Multiple timeouts with no explanation
    gateway.cache_set(key, value, ttl=3600)  # Magic! Why 3600?
    response = gateway.http_get(url, timeout=30)  # Magic! Why 30?
    
    # Mysterious calculation
    score = base_score * 0.85  # Magic! Why 0.85?
    
    # Arbitrary limits
    for i in range(5):  # Magic! Why 5 retries?
        if try_operation():
            break
        time.sleep(2)  # Magic! Why 2 seconds?
```

**Questions developers have:**
- Why those specific values?
- Can I change them?
- Where else are these values used?
- What happens if I change one?

### Why This Is Wrong

**1. Unclear Intent**
```python
# What does this mean?
if temperature > 98.6:
    alert()

# Is it:
# - Fahrenheit body temperature?
# - Celsius?
# - Some other threshold?
# - Why 98.6 specifically?
```

**2. Difficult Maintenance**
```python
# Need to change timeout from 30 to 60
# But which 30s are timeouts?
gateway.http_get(url, timeout=30)  # This one?
time.sleep(30)  # Or this one?
if age > 30:  # Or this one??
```

**3. Hidden Relationships**
```python
# These should be the same but aren't obvious
cache_ttl = 3600
webhook_timeout = 3600
cleanup_interval = 3600

# Are they related? Will they always be the same?
```

**4. Duplicate Knowledge**
```python
# Same value in multiple places
def func1(): return data[:100]
def func2(): return data[:100]
def func3(): return data[:100]

# What if requirement changes to 200?
# Must change in 3 places - easy to miss one!
```

### Correct Approach

**Option 1: Named Constants**
```python
# ✅ CORRECT - Named constants at top of file
MAX_DATA_SIZE = 1024  # Maximum data size in bytes
CACHE_TTL_SECONDS = 3600  # 1 hour cache TTL
HTTP_TIMEOUT_SECONDS = 30  # HTTP request timeout
SCORE_MULTIPLIER = 0.85  # Discount factor for scoring
MAX_RETRIES = 5  # Maximum retry attempts
RETRY_DELAY_SECONDS = 2  # Delay between retries

def process_data(data):
    if len(data) > MAX_DATA_SIZE:
        raise ValueError(f"Data exceeds {MAX_DATA_SIZE} bytes")
    
    gateway.cache_set(key, value, ttl=CACHE_TTL_SECONDS)
    response = gateway.http_get(url, timeout=HTTP_TIMEOUT_SECONDS)
    
    score = base_score * SCORE_MULTIPLIER
    
    for i in range(MAX_RETRIES):
        if try_operation():
            break
        time.sleep(RETRY_DELAY_SECONDS)
```

**Option 2: Configuration Class**
```python
# ✅ CORRECT - Configuration dataclass
from dataclasses import dataclass

@dataclass
class ProcessingConfig:
    """Configuration for data processing."""
    max_data_size: int = 1024  # bytes
    cache_ttl: int = 3600  # seconds (1 hour)
    http_timeout: int = 30  # seconds
    score_multiplier: float = 0.85
    max_retries: int = 5
    retry_delay: int = 2  # seconds

config = ProcessingConfig()

def process_data(data):
    if len(data) > config.max_data_size:
        raise ValueError(f"Data exceeds {config.max_data_size} bytes")
    
    gateway.cache_set(key, value, ttl=config.cache_ttl)
```

**Option 3: Enum for Related Values**
```python
# ✅ CORRECT - Enum for HTTP status codes
from enum import IntEnum

class HTTPStatus(IntEnum):
    """HTTP status codes."""
    OK = 200
    BAD_REQUEST = 400
    UNAUTHORIZED = 401
    FORBIDDEN = 403
    NOT_FOUND = 404
    INTERNAL_ERROR = 500

def handle_response(response):
    if response.status == HTTPStatus.OK:
        return response.data
    elif response.status == HTTPStatus.UNAUTHORIZED:
        raise AuthError()
```

### When Magic Numbers Are Acceptable

**Truly universal constants:**
```python
# ✅ Acceptable - universal constants
PERCENT_100 = 100  # Percentage denominator
HOURS_PER_DAY = 24
MINUTES_PER_HOUR = 60
SECONDS_PER_MINUTE = 60

# But even these could benefit from names!
timeout_hours = 1
timeout_seconds = timeout_hours * SECONDS_PER_MINUTE * MINUTES_PER_HOUR
```

**Zero, One, Two in obvious contexts:**
```python
# ✅ Acceptable - obvious meaning
items = []  # Empty list - 0 is clear
if len(items) == 0:  # Zero clearly means "empty"
    return

first_item = items[0]  # Index 0 clearly means "first"
second_item = items[1]  # Index 1 clearly means "second"
```

**Mathematical constants:**
```python
# ✅ Use from standard library
import math
area = math.pi * radius ** 2  # pi from math module
```

### Real SUGA-ISP Examples

**Wrong (early code):**
```python
# Multiple magic 128s throughout code
if len(response) > 128000000:  # Magic! What is this?
    gateway.log_error("Response too large")

# Magic timeouts
time.sleep(5)  # Why 5?
gateway.cache_set(key, val, ttl=300)  # Why 300?
```

**Correct (current code):**
```python
# Clear constants
MAX_LAMBDA_RESPONSE_SIZE = 128 * 1024 * 1024  # 128 MB (lambda limit)
DEFAULT_CACHE_TTL = 300  # 5 minutes
INITIALIZATION_DELAY = 5  # seconds

if len(response) > MAX_LAMBDA_RESPONSE_SIZE:
    gateway.log_error(
        f"Response exceeds Lambda limit ({MAX_LAMBDA_RESPONSE_SIZE} bytes)"
    )

time.sleep(INITIALIZATION_DELAY)
gateway.cache_set(key, val, ttl=DEFAULT_CACHE_TTL)
```

### Naming Conventions

**Good constant names:**
```python
# Include units and context
MAX_RETRIES = 5
TIMEOUT_SECONDS = 30
CACHE_TTL_MINUTES = 60
MAX_PAYLOAD_KB = 128
DISCOUNT_PERCENT = 15
```

**Poor constant names:**
```python
# Vague or wrong
MAX = 5  # Maximum what?
TIMEOUT = 30  # What units?
TTL = 60  # Minutes? Seconds? Hours?
SIZE = 128  # Bytes? KB? MB?
RATE = 0.15  # Percent? Decimal?
```

### Refactoring Strategy

**Step 1: Find Magic Numbers**
```bash
# Regex to find numeric literals
grep -E "[^a-zA-Z0-9_][0-9]+[^a-zA-Z0-9_]" *.py
```

**Step 2: Identify Meaning**
```python
# For each number, ask:
# - What does this represent?
# - Why this value?
# - Where else is it used?
# - Will it ever change?
```

**Step 3: Extract to Constant**
```python
# Replace literal with named constant
- if len(data) > 1024:
+ MAX_DATA_SIZE = 1024
+ if len(data) > MAX_DATA_SIZE:
```

**Step 4: Add Documentation**
```python
# Explain why this value
MAX_DATA_SIZE = 1024  # Maximum safe size before memory issues
```

---

## Related Topics

- **AP-20**: God Functions - Another code quality issue
- **AP-22**: Inconsistent Naming - Clear naming matters
- **AP-25**: No Docstrings - Document what constants mean
- **LESS-07**: Gateway Pattern Simplicity - Clear, explicit code

---

## Keywords

magic numbers, numeric literals, named constants, code clarity, maintainability, configuration, constants, code quality

---

## Version History

- **2025-10-24**: Created - Extracted from Part 2, added SUGA-ISP examples and refactoring strategy

---

**File:** `NM05-AntiPatterns-Quality_AP-21.md`
**End of Document

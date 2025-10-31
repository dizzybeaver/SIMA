# ErrorHandling-Index.md

**Category:** Decision Logic  
**Subcategory:** Error Handling  
**Files:** 2  
**Created:** 2024-10-30  
**Updated:** 2024-10-30

---

## Overview

Error handling decisions determine how to respond to different error conditions - whether to handle gracefully, retry, log, or propagate, and which exception types to use for different scenarios.

---

## Files in This Category

### DT-05: How to Handle This Error
**REF-ID:** DT-05  
**Priority:** High  
**File:** `DT-05.md`

**Summary:**
Decision tree for error handling strategies - graceful handling, retry logic, logging, or propagation based on error type.

**Key Questions:**
- Is error expected/recoverable?
- Can operation be retried?
- Should error be logged?
- Should error propagate?

**Use When:**
- Implementing error handling
- Deciding retry strategy
- Determining log levels

---

### DT-06: What Exception Type to Raise
**REF-ID:** DT-06  
**Priority:** Medium  
**File:** `DT-06.md`

**Summary:**
Decision tree for selecting appropriate exception types - built-in Python exceptions vs custom exceptions.

**Key Questions:**
- Invalid input? → ValueError
- Missing key? → KeyError
- Wrong type? → TypeError
- Domain-specific? → Custom exception

**Use When:**
- Raising exceptions
- Creating error handling
- Designing exception hierarchy

---

## Quick Decision Guide

### Error Handling Strategy
```
Expected error (cache miss)       → Handle gracefully, return default
Transient error (network timeout) → Retry with exponential backoff
Unexpected error (DB failure)     → Log and propagate
Validation error                  → Return error dict or raise ValueError
```

### Exception Type Selection
```
Invalid value          → ValueError
Missing key            → KeyError  
Wrong type             → TypeError
Out of range           → IndexError
Not implemented        → NotImplementedError
Domain-specific        → Custom exception
```

---

## Common Scenarios

### Scenario 1: Network Request with Retry

**Context:** External API call might timeout

**Solution:**
```python
from gateway import log_error
import time

for attempt in range(3):
    try:
        return http_get(url, timeout=5)
    except TimeoutError:
        if attempt < 2:
            time.sleep(2 ** attempt)
        else:
            log_error("All retries failed")
            raise
```

### Scenario 2: Validation with ValueError

**Context:** Function receives invalid input

**Solution:**
```python
def set_age(age: int):
    if age < 0:
        raise ValueError(f"Age must be positive, got {age}")
    if age > 150:
        raise ValueError(f"Unrealistic age: {age}")
```

### Scenario 3: Expected Error Handling

**Context:** Cache miss is normal operation

**Solution:**
```python
from gateway import cache_get, log_warning

data = cache_get(key)
if data is None:
    log_warning(f"Cache miss: {key}")
    data = fetch_from_source(key)
```

---

## Related Content

**Design Decisions:**
- **DEC-15:** Error handling design patterns

**Anti-Patterns:**
- **AP-14:** Bare except clauses
- **AP-15:** Swallowing exceptions
- **AP-16:** No error context

**Lessons:**
- **LESS-05:** Graceful degradation patterns
- **LESS-08:** Test failure paths

---

## Verification Checklist

**Error Handling:**
- [ ] Catch specific exceptions (not bare except)
- [ ] Log errors with context
- [ ] Retry transient errors (network, rate limits)
- [ ] Handle expected errors gracefully
- [ ] Propagate unexpected errors
- [ ] Use appropriate log levels

**Exception Types:**
- [ ] ValueError for invalid input
- [ ] KeyError for missing keys
- [ ] TypeError for wrong types
- [ ] Custom exceptions for domain-specific errors
- [ ] Include descriptive error messages
- [ ] Add error context when needed

---

## Keywords

error handling, exceptions, retry logic, exception types, ValueError, KeyError, graceful degradation, error propagation

---

**File:** `ErrorHandling-Index.md`  
**Location:** `/sima/entries/decisions/error-handling/`  
**End of Category Index**

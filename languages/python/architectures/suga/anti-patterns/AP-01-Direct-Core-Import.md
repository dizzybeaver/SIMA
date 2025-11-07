# AP-01-Direct-Core-Import.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Why direct core imports violate SUGA  
**Category:** Python Architecture - SUGA - Anti-Patterns

---

## ANTI-PATTERN

Importing core modules directly instead of using gateway.

---

## WRONG CODE

```python
# ❌ ANTI-PATTERN: Direct core import
import cache_core
import logging_core

def process_data(key):
    # Direct core usage
    data = cache_core.get_impl(key)
    logging_core.info(f"Processed {key}")
    return data
```

---

## WHY IT'S WRONG

### Problem 1: Circular Imports
```python
# cache_core.py
import logging_core  # Direct import
logging_core.info("Cache operation")

# logging_core.py
import cache_core  # Circular!
config = cache_core.get_impl("log_config")
```

**Result:** ImportError

### Problem 2: Violates SUGA
Three-layer pattern broken:
```
Module → Core  # ❌ Skips gateway and interface
```

Should be:
```
Module → Gateway → Interface → Core  # ✅
```

### Problem 3: Tight Coupling
Code coupled to specific core implementation:
```python
import cache_core  # Tied to this implementation
data = cache_core.get_impl(key)  # Specific function name
```

Can't easily swap to:
```python
import redis_core
data = redis_core.get(key)  # Different API!
```

### Problem 4: Hard to Test
Must mock specific core:
```python
mock_cache_core = Mock()
# Inject mock somehow
```

vs gateway (easy):
```python
mock_gateway = Mock()
# Single mock for everything
```

### Problem 5: Inconsistent Codebase
Some files use gateway, some use direct:
```python
# File A
import gateway
gateway.cache_get(key)  # Via gateway

# File B
import cache_core
cache_core.get_impl(key)  # Direct

# Inconsistent! Which pattern to follow?
```

---

## CORRECT CODE

```python
# ✅ CORRECT: Via gateway
import gateway

def process_data(key):
    # Gateway usage
    data = gateway.cache_get(key)
    gateway.log_info(f"Processed {key}")
    return data
```

---

## BENEFITS OF CORRECT APPROACH

### 1. No Circular Imports
Gateway never creates circles:
```python
# Any module
import gateway  # Safe
gateway.cache_get(...)
gateway.log_info(...)
```

### 2. Follows SUGA
Proper three-layer flow:
```
Module → Gateway → Interface → Core
```

### 3. Loose Coupling
Code uses abstract gateway API:
```python
import gateway
gateway.cache_get(key)  # Don't care about implementation
```

### 4. Easy Testing
Single mock point:
```python
mock_gateway = Mock()
mock_gateway.cache_get = Mock(return_value="test")
# Done!
```

### 5. Consistent Codebase
One pattern everywhere:
```python
# Every file
import gateway
```

---

## DETECTION

### Manual Review
Look for:
```python
import *_core
from *_core import
```

### Automated Check
```python
import re

def check_imports(file_content):
    # Find direct core imports
    pattern = r'(?:import|from)\s+\w+_core'
    matches = re.findall(pattern, file_content)
    
    if matches:
        return f"Direct core import found: {matches}"
    return "OK"
```

### Exceptions
**Only allowed in:**
- Interface files (must import cores)
- Test files (must import to test)

**Not allowed in:**
- Application code
- Business logic
- Any other modules

---

## MIGRATION

### If Found in Code

**Step 1: Identify imports**
```python
# Old code
import cache_core
import logging_core
```

**Step 2: Replace with gateway**
```python
# New code
import gateway
```

**Step 3: Update calls**
```python
# Old calls
cache_core.get_impl(key)
logging_core.info(message)

# New calls
gateway.cache_get(key)
gateway.log_info(message)
```

**Step 4: Test**
Verify no errors, functionality same.

**Step 5: Remove old imports**
Delete direct core import lines.

---

## REAL EXAMPLE

### Before (Wrong)
```python
"""Data processor module."""
import cache_core
import http_client_core
import logging_core

def fetch_user_data(user_id):
    # Check cache
    cached = cache_core.get_impl(f"user:{user_id}")
    if cached:
        logging_core.info(f"Cache hit: {user_id}")
        return cached
    
    # Fetch from API
    data = http_client_core.get_request(f"/users/{user_id}")
    
    # Cache result
    cache_core.set_impl(f"user:{user_id}", data, ttl=3600)
    
    logging_core.info(f"Fetched user: {user_id}")
    return data
```

**Problems:**
- Three direct core imports
- Violates SUGA
- Potential circular imports
- Hard to test

### After (Correct)
```python
"""Data processor module."""
import gateway

def fetch_user_data(user_id):
    # Check cache
    cached = gateway.cache_get(f"user:{user_id}")
    if cached:
        gateway.log_info(f"Cache hit: {user_id}")
        return cached
    
    # Fetch from API
    data = gateway.http_get(f"/users/{user_id}")
    
    # Cache result
    gateway.cache_set(f"user:{user_id}", data, ttl=3600)
    
    gateway.log_info(f"Fetched user: {user_id}")
    return data
```

**Benefits:**
- One import (gateway)
- Follows SUGA
- No circular imports
- Easy to test
- Consistent

---

## VERIFICATION

**Check for violations:**
- [ ] No `import *_core` in application code
- [ ] No `from *_core import` in application code
- [ ] All functionality via `import gateway`
- [ ] Exceptions only in interface/test files

---

**Related:**
- ARCH-01: Gateway trinity
- DEC-03: Gateway mandatory
- RULE-01: Gateway rule
- AP-02: Module-level imports

**Version History:**
- v1.0.0 (2025-11-06): Initial direct core import anti-pattern

---

**END OF FILE**

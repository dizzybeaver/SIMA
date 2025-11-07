# DEC-03-Gateway-Mandatory.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Why gateway usage is mandatory for all cross-module calls  
**Category:** Python Architecture - SUGA - Decisions

---

## DECISION

ALL cross-module communication MUST go through gateway. No exceptions.

**Rule:** `import gateway` is the ONLY way to use other modules' functionality.

---

## ALTERNATIVES CONSIDERED

### Alternative 1: Optional Gateway
**Approach:** Allow direct imports for "simple" cases

**Example:**
```python
# "Simple" case - direct import
from cache_core import get_impl

# "Complex" case - use gateway
import gateway
gateway.cache_get(key)
```

**Pros:**
- Less overhead for simple cases
- Fewer indirection layers

**Cons:**
- Inconsistent codebase
- Hard to know when to use which
- Some devs use direct, some use gateway
- Circular imports still possible
- Testing harder (multiple patterns)

**Rejected:** Inconsistency causes problems

### Alternative 2: Gateway for External Only
**Approach:** Internal modules can import directly

**Example:**
```python
# External module
import gateway  # Must use gateway
gateway.cache_get(key)

# Internal module
import cache_core  # Can import directly
cache_core.get_impl(key)
```

**Pros:**
- Less overhead internally
- Clear boundary (external vs internal)

**Cons:**
- Still have circular imports internally
- Unclear what's "internal" vs "external"
- Two patterns to remember
- Testing complicated

**Rejected:** Internal circular imports still problematic

### Alternative 3: Gateway Mandatory (Selected)
**Approach:** Everyone uses gateway, always

**Example:**
```python
# Every module, every time
import gateway
gateway.cache_get(key)
gateway.log_info(message)
gateway.http_get(url)
```

**Pros:**
- Consistent everywhere
- No circular imports anywhere
- Easy to remember (one rule)
- Easy to test (mock gateway)
- Clear dependencies

**Cons:**
- Extra indirection always
- More characters to type

**Selected:** Consistency and safety worth the cost

---

## RATIONALE

### Reason 1: Prevents ALL Circular Imports
**Without mandatory gateway:**
```python
# cache_core.py
import logging_core  # Might be circular
logging_core.info("Cache hit")

# logging_core.py
import cache_core  # Circular!
cached = cache_core.get_impl("log_config")
```

**Result:** Import error

**With mandatory gateway:**
```python
# cache_core.py
import gateway  # Never circular
gateway.log_info("Cache hit")

# logging_core.py
import gateway  # Never circular
cached = gateway.cache_get("log_config")
```

**Result:** Works perfectly

### Reason 2: Consistency
**One rule to remember:**
> Need another module? Import gateway.

**No decisions:**
- Not "should I use gateway or direct import?"
- Not "is this internal or external?"
- Not "is this simple enough for direct import?"

**Just:** Always gateway.

### Reason 3: Easy Testing
**Mock once:**
```python
# test_code.py
mock_gateway = Mock()
mock_gateway.cache_get = Mock(return_value="test")

# All code using gateway automatically mocked
```

**Not:**
```python
# ❌ If allowed direct imports
mock_cache_core = Mock()
mock_logging_core = Mock()
mock_http_core = Mock()
# ... mock everything individually
```

### Reason 4: Clear Dependencies
**Every file:**
```python
import gateway  # Only dependency
```

**Dependencies visible:**
```python
def process_data():
    import gateway
    gateway.cache_get(...)    # Uses cache
    gateway.log_info(...)     # Uses logging
    gateway.http_get(...)     # Uses HTTP
    # All dependencies explicit
```

### Reason 5: Single Entry Point
**External code:**
```python
import gateway
# Everything available
gateway.[anything]
```

**Easy to discover:** Look in gateway module, see all functions.

---

## ENFORCEMENT

### Code Review
**Automatic rejection:**
```python
# ❌ REJECT: Direct core import
import cache_core
from logging_core import info

# ❌ REJECT: Direct interface import
import interface_cache

# ✅ ACCEPT: Gateway only
import gateway
```

### Automated Check
```python
# check_imports.py
def check_file(filepath):
    with open(filepath) as f:
        content = f.read()
    
    # Look for direct imports
    if re.search(r'import \w+_core', content):
        return False, "Direct core import found"
    
    if re.search(r'import interface_', content):
        return False, "Direct interface import found"
    
    return True, "OK"
```

### Exceptions
**ONLY allowed in:**
- Gateway wrappers (must import interfaces)
- Interface files (must import cores)
- Test files (must import modules to test)

**Everywhere else:** Gateway only

---

## EXAMPLES

### Correct Usage
```python
def process_user_data(user_id):
    """Process user data with caching and logging."""
    import gateway
    
    # Get from cache
    data = gateway.cache_get(f"user:{user_id}")
    
    if not data:
        # Fetch from API
        data = gateway.http_get(f"/users/{user_id}")
        
        # Cache result
        gateway.cache_set(f"user:{user_id}", data)
    
    # Log access
    gateway.log_info(f"Processed user {user_id}")
    
    return data
```

**All dependencies via gateway. Clear, testable, consistent.**

### Incorrect Usage
```python
# ❌ WRONG: Direct imports
def process_user_data(user_id):
    import cache_core
    import http_client_core
    import logging_core
    
    data = cache_core.get_impl(f"user:{user_id}")
    if not data:
        data = http_client_core.get_request(f"/users/{user_id}")
        cache_core.set_impl(f"user:{user_id}", data)
    
    logging_core.info(f"Processed user {user_id}")
    return data
```

**Problems:**
- Might have circular imports
- Hard to test (multiple mocks)
- Violates SUGA pattern

---

## VIOLATION CONSEQUENCES

**If direct import used:**
1. Potential circular import (breaks code)
2. Inconsistent architecture (confuses developers)
3. Harder testing (more mocks needed)
4. Code review rejection
5. Possible runtime errors

**One violation opens door:**
"If module A can do it, why can't module B?"

**Result:** Pattern breakdown, circular imports return.

---

## WHEN TO DEVIATE

**NEVER in LEE project.**

**Might deviate:**
- Non-SUGA projects
- Simple scripts
- Standalone utilities

**For any SUGA project:** Gateway mandatory.

---

## VALIDATION

**Pre-commit check:**
- [ ] No direct core imports (except gateway/interface files)
- [ ] No direct interface imports (except gateway files)
- [ ] All functionality via gateway

**Runtime check:**
Zero circular import errors.

---

**Related:**
- ARCH-01: Gateway trinity
- DEC-01: SUGA choice
- DEC-02: Three-layer pattern
- AP-01: Direct core import
- RULE-01: Gateway rule

**Version History:**
- v1.0.0 (2025-11-06): Initial mandatory gateway decision

---

**END OF FILE**

# NEURAL_MAP_05: Anti-Patterns
# SUGA-ISP Neural Memory System - What NOT to Do & Why
# Version: 1.1.0 | Phase: 2 Wisdom | Enhanced with REF IDs

---

**FILE STATISTICS:**
- Sections: 28 anti-patterns
- Reference IDs: 28
- Cross-references: 40+
- Priority Breakdown: Critical=8, High=12, Medium=6, Low=2
- Last Updated: 2025-10-20
- Version: 1.1.0 (Enhanced with REF IDs)

---

## Purpose

This file documents WHAT NOT TO DO in SUGA-ISP - common mistakes, anti-patterns, violations, and practices to avoid. This is the "negative wisdom" that prevents errors before they happen.

---

## PART 1: IMPORT ANTI-PATTERNS

### Anti-Pattern 1: Direct Cross-Interface Imports
**REF:** NM05-AP-01
**PRIORITY:** 🔴 CRITICAL
**TAGS:** imports, cross-interface, circular-imports, violation, gateway
**KEYWORDS:** direct import, cross interface, import violation, bypass gateway
**RELATED:** NM04-DEC-01, NM02-RULE-01, NM07-DT-01
**DETECTION:** `grep "from .*_core import" *.py | grep -v "# Same interface"`

❌ **WRONG:**
```python
# In cache_core.py
from logging_core import log_info  # VIOLATION!
from http_client_core import http_post  # VIOLATION!

def cache_operation():
    log_info("Cache operation")  # Wrong way
    http_post("https://api.example.com/metric")  # Wrong way
```

✅ **CORRECT:**
```python
# In cache_core.py
from gateway import log_info, http_post

def cache_operation():
    log_info("Cache operation")  # Via gateway
    http_post("https://api.example.com/metric")  # Via gateway
```

**Why It's Wrong:**
- Creates circular import risk
- Violates ISP architecture
- Bypasses gateway control layer
- Makes testing harder

**Impact:** HIGH - Can break entire system with circular imports

**REAL-WORLD USAGE:**
User: "Can cache_core import logging_core directly?"
Claude searches: "cross interface imports"
Finds: NM05-AP-01
Response: "NO - Direct cross-interface imports violate SUGA-ISP architecture. Use gateway instead."

---

### Anti-Pattern 2: Importing Interface Routers Directly
**REF:** NM05-AP-02
**PRIORITY:** 🔴 CRITICAL
**TAGS:** imports, routers, abstraction, violation
**KEYWORDS:** router import, interface router, internal import
**RELATED:** NM04-DEC-02, NM01-ARCH-03

❌ **WRONG:**
```python
# In lambda_function.py
from interface_cache import execute_cache_operation  # VIOLATION!

# In cache_core.py
from interface_logging import execute_logging_operation  # VIOLATION!
```

✅ **CORRECT:**
```python
# In lambda_function.py
from gateway import cache_get, cache_set

# In cache_core.py
from gateway import log_info
```

**Why It's Wrong:**
- Routers are internal to gateway
- Breaks abstraction layer
- Users should see wrapper functions, not routers

**Impact:** MEDIUM - Breaks architectural boundaries

---

### Anti-Pattern 3: Using Gateway for Same-Interface Calls
**REF:** NM05-AP-03
**PRIORITY:** ⚪ LOW
**TAGS:** imports, same-interface, performance, unnecessary
**KEYWORDS:** unnecessary gateway, same interface, intra-interface
**RELATED:** NM02-RULE-02, NM07-DT-01

❌ **WRONG (unnecessary indirection):**
```python
# In cache_core.py
from gateway import cache_validate_key  # Hypothetical wrapper

def cache_get(key):
    if not cache_validate_key(key):  # Unnecessary gateway hop
        return None
```

✅ **CORRECT:**
```python
# In cache_core.py
from cache_operations import validate_key  # Same interface

def cache_get(key):
    if not validate_key(key):  # Direct call
        return None
```

**Why It's Wrong:**
- Unnecessary performance overhead
- Makes code harder to understand
- Violates intra-interface freedom principle

**Exception:** If cache_validate_key needs to be public gateway function, that's different use case

**Impact:** LOW - Works but inefficient

---

### Anti-Pattern 4: Circular Imports via Gateway
**REF:** NM05-AP-04
**PRIORITY:** 🔴 CRITICAL
**TAGS:** circular-imports, dependencies, architecture, layers
**KEYWORDS:** circular dependency, gateway cycle, dependency loop
**RELATED:** NM04-DEC-01, NM06-BUG-02, NM02-DEP-01

❌ **WRONG:**
```python
# In logging_core.py
from gateway import cache_get  # DANGEROUS!

def log_info(message):
    cached_config = cache_get("log_config")
    # ...
```

✅ **CORRECT:**
```python
# In logging_core.py
# Logging is base layer - should not depend on CACHE

def log_info(message):
    # Use simple default config, no cache dependency
    # ...
```

**Why It's Wrong:**
- LOGGING is base layer (no dependencies)
- Creates dependency cycle via gateway
- Can cause initialization deadlock

**Rule:** Check dependency hierarchy before adding gateway imports
- Lower layers shouldn't import from higher layers

**Impact:** HIGH - Can cause system initialization failure

---

### Anti-Pattern 5: Importing from lambda_function.py
**REF:** NM05-AP-05
**PRIORITY:** 🔴 CRITICAL
**TAGS:** imports, entry-point, upward-dependency, violation
**KEYWORDS:** lambda function import, entry point import, reverse dependency
**RELATED:** NM01-ARCH-06, NM02-RULE-05

❌ **WRONG:**
```python
# In cache_core.py
from lambda_function import some_helper  # VIOLATION!
```

✅ **CORRECT:**
```python
# In cache_core.py
# Helper should be in utility interface or cache_core itself
from utility_core import some_helper
# Or define locally if cache-specific
```

**Why It's Wrong:**
- lambda_function.py is entry point, not library
- Creates upward dependency
- Makes testing impossible

**Rule:** Flow is one-way: lambda_function → gateway → interfaces

**Impact:** HIGH - Breaks architectural flow

---

## PART 2: ARCHITECTURE ANTI-PATTERNS

### Anti-Pattern 6: Custom Caching in Non-Cache Interfaces
**REF:** NM05-AP-06
**PRIORITY:** 🟡 HIGH
**TAGS:** caching, duplication, DRY, memory
**KEYWORDS:** custom cache, duplicate caching, reinvent caching
**RELATED:** NM04-DEC-09, NM07-DT-04, RULE-2

❌ **WRONG:**
```python
# In metrics_core.py
_metrics_cache = {}  # Custom cache implementation

def get_metric(name):
    if name in _metrics_cache:
        return _metrics_cache[name]
    # Calculate and cache...
```

✅ **CORRECT:**
```python
# In metrics_core.py
from gateway import cache_get, cache_set

def get_metric(name):
    cached = cache_get(f"metric_{name}")
    if cached is not None:
        return cached
    # Calculate and cache via gateway
    cache_set(f"metric_{name}", value)
```

**Why It's Wrong:**
- Duplicates existing CACHE interface
- No TTL, no eviction, no monitoring
- Wastes memory (multiple caches)

**Rule:** If caching is needed, use CACHE interface

**Impact:** MEDIUM - Memory waste, inconsistent behavior

**REAL-WORLD USAGE:**
User: "Should I create a local cache dict in my interface?"
Claude searches: "custom caching"
Finds: NM05-AP-06
Response: "NO - Use existing CACHE interface via gateway. Prevents duplication and memory waste."

---

### Anti-Pattern 7: Custom Logging Implementation
**REF:** NM05-AP-07
**PRIORITY:** 🟡 HIGH
**TAGS:** logging, duplication, consistency
**KEYWORDS:** custom logging, logging module, duplicate logging
**RELATED:** NM04-DEC-08, RULE-2

❌ **WRONG:**
```python
# In http_client_core.py
import logging  # Python's logging module

logger = logging.getLogger(__name__)

def http_post(url, data):
    logger.info(f"POST {url}")  # Custom logging
```

✅ **CORRECT:**
```python
# In http_client_core.py
from gateway import log_info

def http_post(url, data):
    log_info(f"POST {url}")  # Via LOGGING interface
```

**Why It's Wrong:**
- LOGGING interface already exists
- Inconsistent log format
- Doesn't use CloudWatch integration

**Rule:** Always use gateway.log_* functions

**Impact:** MEDIUM - Inconsistent logs, harder debugging

---

### Anti-Pattern 8: Threading/Asyncio in Lambda
**REF:** NM05-AP-08
**PRIORITY:** 🟡 HIGH
**TAGS:** threading, asyncio, Lambda, unnecessary-complexity
**KEYWORDS:** threading locks, async await, concurrency, unnecessary
**RELATED:** NM04-DEC-04, NM06-LESS-04

❌ **WRONG:**
```python
import threading
import asyncio

# In cache_core.py
_cache_lock = threading.Lock()  # Unnecessary!

# In http_client_core.py
async def http_post(url, data):  # Unnecessary!
    async with aiohttp.ClientSession() as session:
        # ...
```

✅ **CORRECT:**
```python
# In cache_core.py
# No lock needed - Lambda is single-threaded

_cache_store = {}

# In http_client_core.py
def http_post(url, data):  # Synchronous
    # Use urllib (stdlib)
```

**Why It's Wrong:**
- Lambda is single-threaded per container
- No concurrency to protect against
- Adds complexity and overhead for no benefit

**Rule:** Keep it simple, synchronous, single-threaded

**Impact:** LOW - Works but adds unnecessary complexity

---

### Anti-Pattern 9: External Heavy Libraries
**REF:** NM05-AP-09
**PRIORITY:** 🔴 CRITICAL
**TAGS:** libraries, dependencies, memory, Lambda
**KEYWORDS:** pandas, numpy, heavy libraries, external dependencies
**RELATED:** NM04-DEC-07, NM04-DEC-10

❌ **WRONG:**
```python
import pandas as pd  # 20MB+, not in Lambda
import numpy as np  # 15MB+, not in Lambda
import requests  # 5MB, not in Lambda (except HA)

def analyze_data(data):
    df = pd.DataFrame(data)  # Heavy library
    return df.describe()
```

✅ **CORRECT:**
```python
# Use stdlib or implement simple version
import statistics

def analyze_data(data):
    return {
        'mean': statistics.mean(data),
        'median': statistics.median(data)
    }
```

**Why It's Wrong:**
- Not available in Lambda environment
- Would exceed 128MB memory constraint
- Slower cold starts

**Rule:** Stdlib only (except Home Assistant extension uses requests)

**Exception:** Home Assistant extension needs requests for HA client library

**Impact:** HIGH - Won't work in Lambda

---

### Anti-Pattern 10: Modifying lambda_failsafe.py
**REF:** NM05-AP-10
**PRIORITY:** 🔴 CRITICAL
**TAGS:** failsafe, emergency, independence, critical
**KEYWORDS:** failsafe modification, emergency handler, lambda failsafe
**RELATED:** NM01-ARCH-06

❌ **WRONG:**
```python
# In lambda_failsafe.py
from gateway import log_info  # VIOLATION!

def failsafe_handler(event, context):
    log_info("Failsafe triggered")  # Defeats purpose!
```

✅ **CORRECT:**
```python
# In lambda_failsafe.py
# NO IMPORTS from project code!

def failsafe_handler(event, context):
    print("Failsafe triggered")  # Direct print only
```

**Why It's Wrong:**
- Failsafe must work even if everything else broken
- Any import creates dependency
- Defeats entire purpose of failsafe

**Rule:** DON'T TOUCH lambda_failsafe.py unless explicitly asked

**Impact:** CRITICAL - Breaks emergency recovery system

---

## PART 3: PERFORMANCE ANTI-PATTERNS

### Anti-Pattern 11: Synchronous Loops with Network Calls
**REF:** NM05-AP-11
**PRIORITY:** 🟢 MEDIUM
**TAGS:** performance, network, loops, batch-processing
**KEYWORDS:** sequential network calls, slow loops, batch API
**RELATED:** NM07-DT-07

❌ **WRONG:**
```python
def process_items(items):
    results = []
    for item in items:
        result = gateway.http_post(url, item)  # Sequential!
        results.append(result)
    return results
```

✅ **CORRECT (for Lambda):**
```python
def process_items(items):
    # Process in batch if API supports it
    return gateway.http_post(url, {'items': items})

# OR if must be sequential, accept the trade-off
def process_items(items):
    # Lambda is single-threaded anyway
    # Document this is intentionally sequential
    results = []
    for item in items:
        result = gateway.http_post(url, item)
        results.append(result)
    return results
```

**Why It's Wrong (Usually):**
- In multi-threaded environment, would be slow
- But Lambda is single-threaded, so parallelism not possible anyway

**Nuance:** This isn't always wrong in Lambda context
- If items few (<5), sequential is fine
- If items many (>10), batch API call is better
- Parallelism not possible in Lambda anyway

**Impact:** MEDIUM - Slow for large datasets

---

### Anti-Pattern 12: Caching Without TTL
**REF:** NM05-AP-12
**PRIORITY:** 🟡 HIGH
**TAGS:** caching, TTL, memory-leak, expiration
**KEYWORDS:** no TTL, cache without expiration, memory leak
**RELATED:** NM04-DEC-09, NM07-DT-04

❌ **WRONG:**
```python
def expensive_operation():
    cached = gateway.cache_get("result")
    if cached is not None:
        return cached
    
    result = calculate_expensive_result()
    gateway.cache_set("result", result)  # No TTL! Never expires!
    return result
```

✅ **CORRECT:**
```python
def expensive_operation():
    cached = gateway.cache_get("result")
    if cached is not None:
        return cached
    
    result = calculate_expensive_result()
    gateway.cache_set("result", result, ttl=300)  # 5 minute TTL
    return result
```

**Why It's Wrong:**
- Data becomes stale
- Memory leak (cache grows forever)
- Lambda container may live hours

**Rule:** Always set TTL unless data truly never changes

**Impact:** MEDIUM - Memory leaks, stale data

---

### Anti-Pattern 13: String Concatenation in Loops
**REF:** NM05-AP-13
**PRIORITY:** ⚪ LOW
**TAGS:** performance, strings, loops, optimization
**KEYWORDS:** string concat, loop concatenation, join vs plus
**RELATED:** NM07-DT-07

❌ **WRONG:**
```python
def build_log_message(items):
    message = ""
    for item in items:
        message += f"{item['name']}: {item['value']}\n"  # Slow!
    return message
```

✅ **CORRECT:**
```python
def build_log_message(items):
    parts = []
    for item in items:
        parts.append(f"{item['name']}: {item['value']}")
    return "\n".join(parts)  # Fast!
```

**Why It's Wrong:**
- String concatenation creates new string each time
- O(n²) instead of O(n)
- Significant for >100 items

**Impact:** LOW-MEDIUM - Depends on data size

---

## PART 4: ERROR HANDLING ANTI-PATTERNS

### Anti-Pattern 14: Bare Except Clauses
**REF:** NM05-AP-14
**PRIORITY:** 🟡 HIGH
**TAGS:** error-handling, exceptions, bare-except, bad-practice
**KEYWORDS:** bare except, catch all, exception handling
**RELATED:** NM07-DT-05, NM07-DT-06, NM04-DEC-15

❌ **WRONG:**
```python
def risky_operation():
    try:
        result = calculate()
        return result
    except:  # Catches everything, including KeyboardInterrupt!
        return None
```

✅ **CORRECT:**
```python
def risky_operation():
    try:
        result = calculate()
        return result
    except Exception as e:  # Specific to normal exceptions
        gateway.log_error(f"Calculation failed: {e}", error=e)
        return None
```

**Why It's Wrong:**
- Catches system exceptions (KeyboardInterrupt, SystemExit)
- Hides errors silently
- Makes debugging impossible

**Rule:** Always catch specific exceptions or `Exception`

**Impact:** HIGH - Masks critical errors

---

### Anti-Pattern 15: Swallowing Exceptions Without Logging
**REF:** NM05-AP-15
**PRIORITY:** 🟡 HIGH
**TAGS:** error-handling, logging, silent-failure
**KEYWORDS:** swallow exceptions, silent error, no logging
**RELATED:** NM07-DT-05, NM04-DEC-15

❌ **WRONG:**
```python
def process_data(data):
    try:
        result = risky_operation(data)
        return result
    except Exception:
        return None  # Silent failure!
```

✅ **CORRECT:**
```python
def process_data(data):
    try:
        result = risky_operation(data)
        return result
    except Exception as e:
        gateway.log_error(f"Processing failed: {e}", error=e)
        return None  # Now we know why it failed
```

**Why It's Wrong:**
- Errors disappear without trace
- Impossible to debug production issues
- Looks like success but isn't

**Rule:** Always log exceptions before swallowing

**Impact:** HIGH - Debugging nightmare

---

### Anti-Pattern 16: Raising Generic Exceptions
**REF:** NM05-AP-16
**PRIORITY:** 🟢 MEDIUM
**TAGS:** exceptions, error-types, specificity
**KEYWORDS:** generic exception, Exception class, specific errors
**RELATED:** NM07-DT-06

❌ **WRONG:**
```python
def validate_input(data):
    if not data.get('name'):
        raise Exception("Invalid data")  # Too generic!
```

✅ **CORRECT:**
```python
def validate_input(data):
    if not data.get('name'):
        raise ValueError("Missing required field: name")  # Specific!
```

**Why It's Wrong:**
- Generic exceptions hard to catch specifically
- Unclear what went wrong
- Harder to handle different errors differently

**Rule:** Use specific exception types (ValueError, KeyError, TypeError)

**Impact:** MEDIUM - Harder error handling

---

## PART 5: SECURITY ANTI-PATTERNS

### Anti-Pattern 17: No Input Validation
**REF:** NM05-AP-17
**PRIORITY:** 🔴 CRITICAL
**TAGS:** security, validation, injection, vulnerability
**KEYWORDS:** no validation, input validation, security vulnerability
**RELATED:** NM04-DEC-11, NM07-DT-05

❌ **WRONG:**
```python
def process_url(url):
    response = gateway.http_get(url)  # Unsanitized URL!
    return response
```

✅ **CORRECT:**
```python
def process_url(url):
    # Validate first
    if not gateway.validate_url(url):
        raise ValueError(f"Invalid URL: {url}")
    
    response = gateway.http_get(url)
    return response
```

**Why It's Wrong:**
- Security vulnerability (SSRF, injection)
- Accepts malicious input
- Can leak internal network info

**Rule:** Always validate external input

**Impact:** HIGH - Security vulnerability

---

### Anti-Pattern 18: Hardcoded Secrets
**REF:** NM05-AP-18
**PRIORITY:** 🔴 CRITICAL
**TAGS:** security, secrets, credentials, vulnerability
**KEYWORDS:** hardcoded secrets, API keys, credentials in code
**RELATED:** NM04-DEC-11

❌ **WRONG:**
```python
# In http_client_core.py
API_KEY = "sk_live_abc123..."  # Hardcoded secret!

def call_api():
    headers = {"Authorization": f"Bearer {API_KEY}"}
```

✅ **CORRECT:**
```python
# In http_client_core.py
import os

def call_api():
    api_key = os.environ.get("API_KEY")
    if not api_key:
        raise ValueError("API_KEY not configured")
    headers = {"Authorization": f"Bearer {api_key}"}
```

**Why It's Wrong:**
- Secrets in source code
- Visible in GitHub
- Security breach

**Rule:** Use environment variables or AWS Secrets Manager

**Impact:** CRITICAL - Security breach

---

### Anti-Pattern 19: SQL Injection Patterns
**REF:** NM05-AP-19
**PRIORITY:** 🔴 CRITICAL
**TAGS:** security, SQL-injection, string-interpolation
**KEYWORDS:** SQL injection, string formatting, parameterized queries
**RELATED:** NM04-DEC-11

❌ **WRONG:**
```python
def query_user(username):
    query = f"SELECT * FROM users WHERE name = '{username}'"  # Injectable!
    # Execute query...
```

✅ **CORRECT:**
```python
def query_user(username):
    # Parameterized query (if using SQL)
    query = "SELECT * FROM users WHERE name = ?"
    params = (username,)
    # Execute with params...
```

**Why It's Wrong:**
- SQL injection vulnerability
- Attacker can execute arbitrary SQL
- Data breach risk

**Note:** This project doesn't use SQL, but pattern applies to any string interpolation with user input

**Impact:** CRITICAL - Security vulnerability

---

## PART 6: CODE ORGANIZATION ANTI-PATTERNS

### Anti-Pattern 20: God Functions (Too Much in One Function)
**REF:** NM05-AP-20
**PRIORITY:** 🟡 HIGH
**TAGS:** code-organization, complexity, single-responsibility
**KEYWORDS:** god function, large function, too complex
**RELATED:** NM07-DT-10, NM07-DT-11

❌ **WRONG:**
```python
def process_alexa_request(event):
    # 500 lines of code doing everything
    # Parse event
    # Validate input
    # Call API
    # Transform response
    # Format output
    # Log everything
    # Error handling
    # ...
```

✅ **CORRECT:**
```python
def process_alexa_request(event):
    request = parse_alexa_event(event)
    validate_alexa_request(request)
    
    response = handle_alexa_intent(request)
    formatted = format_alexa_response(response)
    
    log_alexa_success(request, formatted)
    return formatted

# Each helper function focused on one thing
```

**Why It's Wrong:**
- Hard to understand
- Hard to test
- Hard to modify
- Violates Single Responsibility Principle

**Rule:** Functions should do one thing well

**Impact:** MEDIUM - Maintenance difficulty

---

**[Continued in Part 2 artifact due to length...]**

# EOF

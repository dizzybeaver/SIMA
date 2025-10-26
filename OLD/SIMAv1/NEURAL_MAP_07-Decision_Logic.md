# NEURAL_MAP_07: Decision Logic
# SUGA-ISP Neural Memory System - When to Do What
# Version: 1.1.0 | Phase: 2 Wisdom | Enhanced with REF IDs

---

**FILE STATISTICS:**
- Sections: 13 decision trees
- Reference IDs: 13
- Cross-references: 50+
- Priority Breakdown: Critical=3, High=6, Medium=3, Low=1
- Last Updated: 2025-10-20
- Version: 1.1.0 (Enhanced with REF IDs)

---

## Purpose

This file documents WHEN TO DO WHAT in SUGA-ISP - decision trees, choice frameworks, and situational guidance. This is the "applied wisdom" for making good decisions in context.

---

## PART 1: IMPORT DECISION TREES

### Decision Tree 1: "How Should I Import X?"
**REF:** NM07-DT-01
**PRIORITY:** 🔴 CRITICAL
**TAGS:** imports, gateway, cross-interface, same-interface, decision-tree
**KEYWORDS:** how to import, import decision, gateway vs direct
**RELATED:** NM02-RULE-01, NM02-RULE-02, NM05-AP-01, NM04-DEC-01

```
START: Need to use functionality X
│
├─ Q: Is X in the same interface as current file?
│  ├─ YES → Direct import
│  │      Example: from cache_operations import validate_key
│  │      Rationale: Intra-interface freedom
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Is X in a different interface?
│  ├─ YES → Import from gateway
│  │      Example: from gateway import log_info
│  │      Rationale: Cross-interface via gateway
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Is current file lambda_function.py or extension?
│  ├─ YES → Import from gateway ONLY
│  │      Example: from gateway import cache_get
│  │      Rationale: External code uses gateway
│  │      → END
│  │
│  └─ NO → Continue
│
└─ Q: Is X stdlib or external library?
   ├─ Stdlib → Direct import OK
   │      Example: import json, import os
   │      → END
   │
   └─ External → Check availability
          ├─ In Lambda? → Direct import OK
          ├─ Not in Lambda? → Find stdlib alternative
          │      Example: Use urllib, not requests
          └─ No alternative? → Reconsider design
                 → END
```

**Examples:**

```python
# ✅ Same interface
# In cache_core.py
from cache_operations import validate_key  # OK

# ✅ Cross-interface
# In cache_core.py
from gateway import log_info  # OK

# ✅ External code
# In lambda_function.py
from gateway import cache_get  # OK
from homeassistant_extension import process_alexa_request  # OK

# ✅ Stdlib
from datetime import datetime  # Always OK
import json  # Always OK

# ❌ Cross-interface direct
# In cache_core.py
from logging_core import log_info  # WRONG

# ❌ External importing internals
# In lambda_function.py
from cache_core import something  # WRONG
```

**REAL-WORLD USAGE:**
User: "How do I import log_info in cache_core?"
Claude searches: "how to import cross interface"
Finds: NM07-DT-01
Response: "Different interface → Use gateway: `from gateway import log_info`"

---

### Decision Tree 2: "Where Should This Function Go?"
**REF:** NM07-DT-02
**PRIORITY:** 🟡 HIGH
**TAGS:** code-organization, function-placement, architecture, decision-tree
**KEYWORDS:** where function goes, function location, organize code
**RELATED:** NM01-ARCH-04, NM07-DT-13

```
START: Have a function to implement
│
├─ Q: Is it interface-specific logic?
│  ├─ YES → Put in <interface>_core.py
│  │      Example: Cache validation → cache_core.py
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Is it generic utility (used by >3 interfaces)?
│  ├─ YES → Put in utility_core.py
│  │      Example: String formatting
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Is it routing/dispatch logic?
│  ├─ YES → Put in interface_<n>.py (router)
│  │      Example: Operation dispatch
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Is it gateway wrapper (user-facing)?
│  ├─ YES → Put in gateway_wrappers.py
│  │      Example: cache_get() wrapper
│  │      → END
│  │
│  └─ NO → Continue
│
└─ Q: Is it core gateway logic?
   └─ YES → Put in gateway_core.py
          Example: execute_operation()
          → END
```

**Decision Matrix:**

| Function Type | Location | Example |
|---------------|----------|---------|
| Cache business logic | cache_core.py | _execute_get_implementation |
| Cache routing | interface_cache.py | execute_cache_operation |
| Cache wrapper | gateway_wrappers.py | cache_get() |
| Generic utility | utility_core.py | format_timestamp() |
| Gateway core | gateway_core.py | execute_operation() |

**REAL-WORLD USAGE:**
User: "Where should I put my new validation function?"
Claude searches: "where function goes"
Finds: NM07-DT-02
Response: "If interface-specific → <interface>_core.py. If generic utility → utility_core.py"

---

## PART 2: FEATURE ADDITION DECISIONS

### Decision Tree 3: "User Wants Feature X"
**REF:** NM07-DT-03
**PRIORITY:** 🟡 HIGH
**TAGS:** features, addition, architecture, decision-tree, growth
**KEYWORDS:** add feature, new feature, feature request
**RELATED:** NM07-DT-02, NM07-DT-13

```
START: User requests feature X
│
├─ Q: Does X already exist in gateway?
│  ├─ YES → Point user to existing feature
│  │      Search: gateway_wrappers.py
│  │      Response: "Use gateway.X()"
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Does X fit in existing interface?
│  ├─ YES → Add to that interface
│  │      Example: cache.list_keys → CACHE interface
│  │      Steps:
│  │      1. Add to _OPERATION_DISPATCH in interface_<n>.py
│  │      2. Implement in <n>_core.py
│  │      3. Optional: Add wrapper in gateway_wrappers.py
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Is X substantial enough for new interface?
│  ├─ YES (>200 lines, has state, domain-specific)
│  │      → Create new interface
│  │      Steps:
│  │      1. Add to GatewayInterface enum
│  │      2. Create interface_<n>.py
│  │      3. Create <n>_core.py
│  │      4. Register in gateway_core.py
│  │      5. Add wrappers
│  │      6. Update neural maps
│  │      → END
│  │
│  └─ NO (simple helper)
│         → Add to UTILITY interface
│         Steps:
│         1. Add to utility_core.py
│         2. Optional: Add to interface_utility dispatch
│         3. Optional: Add wrapper
│         → END
```

**Examples:**

```
Request: "Add cache.clear_expired()"
Decision: Fits CACHE interface → Add to CACHE
Location: cache_core.py + interface_cache.py

Request: "Add email sending"
Decision: Substantial + new domain → New interface
Location: New EMAIL interface

Request: "Add string.to_camel_case()"
Decision: Simple utility → UTILITY interface
Location: utility_core.py
```

**REAL-WORLD USAGE:**
User: "I need email sending capability"
Claude searches: "add feature new interface"
Finds: NM07-DT-03
Response: "Substantial feature → Create new EMAIL interface with router and core files."

---

### Decision Tree 4: "Should This Be Cached?"
**REF:** NM07-DT-04
**PRIORITY:** 🟡 HIGH
**TAGS:** caching, performance, decision-tree, optimization
**KEYWORDS:** should cache, cache decision, when to cache
**RELATED:** NM04-DEC-09, NM05-AP-06, NM05-AP-12

```
START: Considering caching data X
│
├─ Q: Is X expensive to compute/fetch?
│  ├─ NO (<10ms) → Don't cache
│  │      Rationale: Cache overhead > computation
│  │      → END
│  │
│  └─ YES (>10ms) → Continue
│
├─ Q: Is X accessed frequently?
│  ├─ NO (once per request) → Don't cache
│  │      Rationale: No reuse benefit
│  │      → END
│  │
│  └─ YES (multiple times) → Continue
│
├─ Q: Does X change frequently?
│  ├─ YES (every request) → Don't cache
│  │      Rationale: Always stale
│  │      → END
│  │
│  └─ NO (stable) → Continue
│
├─ Q: Is X large (>1MB)?
│  ├─ YES → Cache selectively
│  │      Rationale: Memory constraint (128MB)
│  │      Decision: Cache small subset or summary
│  │      → END
│  │
│  └─ NO (<1MB) → Continue
│
└─ Decision: Cache X with appropriate TTL
   │
   ├─ Q: How often does X change?
   │  ├─ Fast (seconds) → TTL: 60s
   │  ├─ Medium (minutes) → TTL: 300s
   │  ├─ Slow (hours) → TTL: 600s
   │  └─ Never → TTL: None (manual invalidation)
   │
   └─ Implementation:
      cached = gateway.cache_get("X")
      if cached is None:
          cached = compute_X()
          gateway.cache_set("X", cached, ttl=TTL)
      return cached
      → END
```

**Caching Decision Matrix:**

| Data Type | Cache? | TTL | Reason |
|-----------|--------|-----|--------|
| Config values | YES | 300s | Slow-changing, frequently accessed |
| API responses (GET) | YES | 60-300s | Expensive, may change |
| Computed metrics | YES | 60s | Expensive, fast-changing |
| User session data | YES | 600s | Medium-cost, stable |
| Request ID | NO | - | Used once |
| Current timestamp | NO | - | Always changing |
| Large files (>1MB) | NO | - | Memory constraint |

**REAL-WORLD USAGE:**
User: "Should I cache API responses?"
Claude searches: "should cache decision"
Finds: NM07-DT-04
Response: "Yes if: >10ms to fetch + accessed multiple times + relatively stable. Use TTL 60-300s."

---

## PART 3: ERROR HANDLING DECISIONS

### Decision Tree 5: "How Should I Handle This Error?"
**REF:** NM07-DT-05
**PRIORITY:** 🟡 HIGH
**TAGS:** error-handling, exceptions, decision-tree, reliability
**KEYWORDS:** handle error, exception handling, error strategy
**RELATED:** NM04-DEC-15, NM05-AP-14, NM05-AP-15

```
START: Error occurred in function
│
├─ Q: Is this expected/recoverable error?
│  ├─ YES → Handle gracefully
│  │      Examples:
│  │      - Cache miss → Return None
│  │      - File not found → Return empty
│  │      - Validation failed → Return error dict
│  │      Pattern:
│  │        try:
│  │            result = risky_operation()
│  │        except SpecificError:
│  │            log_warning("Expected error occurred")
│  │            return default_value
│  │      → END
│  │
│  └─ NO (unexpected) → Continue
│
├─ Q: Can operation be retried?
│  ├─ YES → Retry with backoff
│  │      Examples:
│  │      - Network timeout
│  │      - Rate limit
│  │      Pattern:
│  │        for attempt in range(3):
│  │            try:
│  │                return risky_operation()
│  │            except RetryableError:
│  │                time.sleep(2 ** attempt)
│  │        raise  # Failed all retries
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Should error be logged?
│  ├─ NO (sensitive/spammy) → Log at debug level
│  │      gateway.log_debug(f"Error: {e}")
│  │      → Continue
│  │
│  └─ YES → Log at appropriate level
│         gateway.log_error(f"Error: {e}", error=e)
│         → Continue
│
├─ Q: Should error propagate to caller?
│  ├─ YES → Re-raise or return error dict
│  │      Pattern:
│  │        try:
│  │            result = operation()
│  │        except Exception as e:
│  │            log_error(f"Failed: {e}", error=e)
│  │            raise  # Propagate
│  │      → END
│  │
│  └─ NO → Return error indicator
│         Pattern:
│           try:
│               result = operation()
│               return {'success': True, 'data': result}
│           except Exception as e:
│               log_error(f"Failed: {e}", error=e)
│               return {'success': False, 'error': str(e)}
│         → END
```

**Error Handling Decision Matrix:**

| Error Type | Action | Log Level | Propagate? |
|------------|--------|-----------|------------|
| Cache miss | Return None | No log | No |
| Invalid input | Return error dict | Warning | No |
| Network timeout | Retry 3x | Error (after retries) | Yes (if all fail) |
| Database error | Log + propagate | Error | Yes |
| Syntax error | Fail fast | Error | Yes |
| Configuration missing | Log + default | Warning | No |

**REAL-WORLD USAGE:**
User: "How should I handle network errors?"
Claude searches: "handle error network"
Finds: NM07-DT-05
Response: "Network timeout → Retry with backoff (3 attempts). Log error after final failure."

---

### Decision Tree 6: "What Exception Type Should I Raise?"
**REF:** NM07-DT-06
**PRIORITY:** 🟢 MEDIUM
**TAGS:** exceptions, error-types, specificity, decision-tree
**KEYWORDS:** exception type, raise exception, which exception
**RELATED:** NM05-AP-16, NM07-DT-05

```
START: Need to raise exception
│
├─ Q: Is it invalid input/argument?
│  └─ YES → raise ValueError("Message")
│         → END
│
├─ Q: Is it missing key/attribute?
│  └─ YES → raise KeyError("Message") or AttributeError
│         → END
│
├─ Q: Is it wrong type?
│  └─ YES → raise TypeError("Message")
│         → END
│
├─ Q: Is it operation not supported?
│  └─ YES → raise NotImplementedError("Message")
│         → END
│
├─ Q: Is it I/O related?
│  └─ YES → raise IOError("Message") or FileNotFoundError
│         → END
│
├─ Q: Is it permission/security?
│  └─ YES → raise PermissionError("Message")
│         → END
│
└─ Q: Is it domain-specific?
   └─ YES → Define custom exception
          class CustomError(Exception): pass
          raise CustomError("Message")
          → END
```

**Exception Selection Guide:**

```python
# ✅ Correct exception types
def validate_age(age):
    if not isinstance(age, int):
        raise TypeError(f"Age must be int, got {type(age)}")
    if age < 0 or age > 150:
        raise ValueError(f"Age must be 0-150, got {age}")

def get_config(key):
    if key not in _config:
        raise KeyError(f"Config key not found: {key}")

# ❌ Wrong - too generic
def validate_age(age):
    if age < 0:
        raise Exception("Bad age")  # Too generic!
```

**REAL-WORLD USAGE:**
User: "What exception should I raise for invalid input?"
Claude searches: "exception type invalid input"
Finds: NM07-DT-06
Response: "Invalid input → ValueError with descriptive message about what's wrong."

---

## PART 4: OPTIMIZATION DECISIONS

### Decision Tree 7: "Should I Optimize This Code?"
**REF:** NM07-DT-07
**PRIORITY:** 🟢 MEDIUM
**TAGS:** optimization, performance, decision-tree, measurement
**KEYWORDS:** should optimize, optimize code, performance decision
**RELATED:** NM04-DEC-13, NM06-LESS-01, NM06-LESS-02

```
START: Considering optimization
│
├─ Q: Have you measured it?
│  ├─ NO → Measure first
│  │      Tools: time.time(), cProfile
│  │      Don't optimize without data
│  │      → END
│  │
│  └─ YES → Continue
│
├─ Q: Is it on critical path (hot path)?
│  ├─ NO → Don't optimize
│  │      Rationale: Not worth complexity
│  │      → END
│  │
│  └─ YES → Continue
│
├─ Q: What % of total time is it?
│  ├─ <5% → Don't optimize
│  │      Rationale: Small impact
│  │      → END
│  │
│  └─ >5% → Continue
│
├─ Q: Will optimization significantly complicate code?
│  ├─ YES → Reconsider
│  │      Trade-off: Complexity vs performance
│  │      Document if proceeding
│  │      → END or Continue
│  │
│  └─ NO → Continue
│
└─ Decision: Optimize
   Steps:
   1. Document current performance
   2. Implement optimization
   3. Measure improvement
   4. Document trade-offs
   5. Add performance test
   → END
```

**Optimization Priority Guide:**

| Optimization | When to Do | Complexity | Impact |
|--------------|-----------|------------|--------|
| Dispatch dicts vs if/elif | Always | Low | Medium |
| Lazy loading | Always | Low | Medium |
| Fast path caching | Hot operations | Medium | High |
| Algorithm improvement | Measured bottleneck | Varies | High |
| Micro-optimizations | Never | High | Low |

**Remember:**
- Premature optimization is root of all evil
- Measure first, optimize second
- Readability > micro-optimizations
- Optimize hot paths, not cold paths

**REAL-WORLD USAGE:**
User: "Should I optimize this function?"
Claude searches: "should optimize decision"
Finds: NM07-DT-07
Response: "Measure first. If <5% of total time or not on hot path, don't optimize."

---

**[Continued in Part 2...]**

# EOF

# ANTI-PATTERNS CHECKLIST
**SUGA-ISP Lambda Project - Quick Reference for What NOT to Do**

Version: 1.0.0  
Date: 2025-10-21  
Purpose: Fast scannable reference for all 28 anti-patterns

---

## 🚫 CRITICAL ANTI-PATTERNS (Check Before Every Response)

These are the most common mistakes. **Scan this section before suggesting any solution.**

### ❌ AP-01: Direct Cross-Interface Imports
**Never suggest:**
```python
from cache_core import get_value
from logging_core import log_message
```

**✅ Always use:**
```python
import gateway
value = gateway.cache_get(key)
gateway.log_info("message")
```

**Why:** Breaks SIMA pattern, causes circular imports  
**REF:** RULE-01, DEC-01  
**Location:** NM05-Anti-Patterns-Part-1.md

---

### ❌ AP-08: Threading Locks or Primitives
**Never suggest:**
```python
import threading
lock = threading.Lock()
with lock:
    # operations
```

**✅ Understand:**
Lambda is single-threaded. Locks are unnecessary and harmful.

**Why:** Lambda environment is single-threaded, locks add overhead without benefit  
**REF:** DEC-04, LESS-06  
**Location:** NM05-Anti-Patterns-Part-1.md

---

### ❌ AP-14: Bare Except Clauses
**Never suggest:**
```python
try:
    operation()
except:  # Too broad!
    pass
```

**✅ Always use:**
```python
try:
    operation()
except SpecificException as e:
    gateway.log_error(f"Operation failed: {e}")
    # Handle appropriately
```

**Why:** Masks real errors, makes debugging impossible  
**REF:** ERR-02, LESS-08  
**Location:** NM05-Anti-Patterns-Part-1.md

---

### ❌ AP-19: Sentinel Objects Crossing Boundaries
**Never suggest:**
```python
# In router
result = gateway.cache_get(key)
return result  # Might leak _CacheMiss sentinel!
```

**✅ Always use:**
```python
# In router
result = gateway.cache_get(key)
if gateway.cache_is_sentinel(result):
    # Handle miss appropriately
    return default_value
return result
```

**Why:** Sentinel leak bug (BUG-01) cost 535ms in production  
**REF:** DEC-05, BUG-01  
**Location:** NM05-Anti-Patterns-Part-2.md

---

### ❌ AP-06: Heavy Dependencies Without Justification
**Never suggest:**
```python
import pandas  # 100+ MB!
import numpy
import scipy
```

**✅ Understand:**
Lambda has 128MB limit. Use heavy libraries only if essential.

**Why:** Exceeds Lambda memory limit, increases cold start time  
**REF:** DEC-07, PATH-01  
**Location:** NM05-Anti-Patterns-Part-1.md

---

## 📋 COMPLETE ANTI-PATTERNS LIST

### Category 1: Architecture Violations

#### ❌ AP-01: Direct Cross-Interface Imports
- **Problem:** Importing core modules directly
- **Instead:** Always use `import gateway`
- **REF:** RULE-01
- **Location:** NM05-Anti-Patterns-Part-1.md

#### ❌ AP-02: Bypassing Gateway Layer
- **Problem:** Creating alternative entry points
- **Instead:** All operations through gateway.py
- **REF:** ARCH-01, DEC-01
- **Location:** NM05-Anti-Patterns-Part-1.md

#### ❌ AP-03: Circular Dependencies
- **Problem:** Module A imports B, B imports A
- **Instead:** Follow dependency layers (DEP-01 to DEP-08)
- **REF:** NM02-CORE-Dependencies
- **Location:** NM05-Anti-Patterns-Part-1.md

#### ❌ AP-04: Breaking Dependency Layers
- **Problem:** Lower layer importing higher layer
- **Instead:** Respect Layer 0 → Layer 1 → Layer 2 → Router
- **REF:** DEP-01 to DEP-08
- **Location:** NM05-Anti-Patterns-Part-1.md

#### ❌ AP-05: Subdirectories (Except home_assistant/)
- **Problem:** Creating nested directory structure
- **Instead:** Keep flat file structure (proven and intentional)
- **REF:** DEC-08
- **Location:** NM05-Anti-Patterns-Part-1.md

---

### Category 2: Implementation Anti-Patterns

#### ❌ AP-06: Heavy Dependencies Without Justification
- **Problem:** Adding large libraries unnecessarily
- **Instead:** Keep dependencies < 128MB total
- **REF:** DEC-07
- **Location:** NM05-Anti-Patterns-Part-1.md

#### ❌ AP-07: Eager Import of Heavy Libraries
- **Problem:** Importing expensive libraries at module level
- **Instead:** Lazy import inside functions (LMMS pattern)
- **REF:** PATH-01, LMMS
- **Location:** NM05-Anti-Patterns-Part-1.md

#### ❌ AP-08: Threading Locks or Primitives
- **Problem:** Using threading.Lock(), Queue(), etc.
- **Instead:** Remove - Lambda is single-threaded
- **REF:** DEC-04, LESS-06
- **Location:** NM05-Anti-Patterns-Part-1.md

#### ❌ AP-09: Synchronous Blocking Operations
- **Problem:** Long-running blocking calls
- **Instead:** Async operations or Step Functions for long tasks
- **REF:** LESS-06
- **Location:** NM05-Anti-Patterns-Part-1.md

#### ❌ AP-10: Mutable Default Arguments
- **Problem:** `def func(items=[])`
- **Instead:** `def func(items=None): items = items or []`
- **REF:** Standard Python best practice
- **Location:** NM05-Anti-Patterns-Part-1.md

---

### Category 3: Error Handling Anti-Patterns

#### ❌ AP-11: Swallowing Exceptions Silently
- **Problem:** `except Exception: pass`
- **Instead:** Log errors, handle gracefully
- **REF:** ERR-02
- **Location:** NM05-Anti-Patterns-Part-1.md

#### ❌ AP-12: Generic Exception Handlers at Top Level
- **Problem:** Catching Exception too broadly at router
- **Instead:** Specific exceptions, let critical errors propagate
- **REF:** ERR-03
- **Location:** NM05-Anti-Patterns-Part-1.md

#### ❌ AP-13: Raising Generic Exceptions
- **Problem:** `raise Exception("error")`
- **Instead:** Specific exception types (ValueError, KeyError, etc.)
- **REF:** ERR-04
- **Location:** NM05-Anti-Patterns-Part-1.md

#### ❌ AP-14: Bare Except Clauses
- **Problem:** `except:` without type
- **Instead:** `except SpecificError:`
- **REF:** ERR-02
- **Location:** NM05-Anti-Patterns-Part-1.md

#### ❌ AP-15: No Error Context Preservation
- **Problem:** Catching without `from` or context
- **Instead:** `raise NewError(...) from original_error`
- **REF:** ERR-06
- **Location:** NM05-Anti-Patterns-Part-2.md

---

### Category 4: Cache Anti-Patterns

#### ❌ AP-16: Direct Cache Manipulation
- **Problem:** Modifying cache internals directly
- **Instead:** Use gateway.cache_* functions
- **REF:** INT-01
- **Location:** NM05-Anti-Patterns-Part-2.md

#### ❌ AP-17: Ignoring _CacheMiss Validation
- **Problem:** Not checking if cache returned sentinel
- **Instead:** Always use `gateway.cache_is_sentinel(result)`
- **REF:** BUG-02
- **Location:** NM05-Anti-Patterns-Part-2.md

#### ❌ AP-18: Caching Without TTL Consideration
- **Problem:** Setting cache without expiry logic
- **Instead:** Always consider data freshness, set appropriate TTLs
- **REF:** INT-01
- **Location:** NM05-Anti-Patterns-Part-2.md

#### ❌ AP-19: Sentinel Objects Crossing Boundaries
- **Problem:** Allowing _CacheMiss to leak to external code
- **Instead:** Sanitize at router layer (DEC-05)
- **REF:** BUG-01, DEC-05
- **Location:** NM05-Anti-Patterns-Part-2.md

---

### Category 5: Configuration Anti-Patterns

#### ❌ AP-20: Hardcoded Configuration Values
- **Problem:** `API_KEY = "abc123"` in code
- **Instead:** Use gateway.config_get() + SSM
- **REF:** INT-05
- **Location:** NM05-Anti-Patterns-Part-2.md

#### ❌ AP-21: Ignoring Environment Variables
- **Problem:** Not respecting Lambda environment config
- **Instead:** Read from os.environ, validate at startup
- **REF:** INT-05, DEC-21
- **Location:** NM05-Anti-Patterns-Part-2.md

#### ❌ AP-22: Missing Configuration Validation
- **Problem:** Assuming config exists without checking
- **Instead:** Validate on startup, fail fast if missing
- **REF:** ERR-05
- **Location:** NM05-Anti-Patterns-Part-2.md

---

### Category 6: Logging Anti-Patterns

#### ❌ AP-23: Using Print Statements
- **Problem:** `print("debug message")`
- **Instead:** `gateway.log_info("message")`
- **REF:** INT-02
- **Location:** NM05-Anti-Patterns-Part-2.md

#### ❌ AP-24: Logging Sensitive Data
- **Problem:** Logging passwords, tokens, PII
- **Instead:** Redact sensitive fields before logging
- **REF:** INT-02, INT-03
- **Location:** NM05-Anti-Patterns-Part-2.md

#### ❌ AP-25: No Structured Logging
- **Problem:** Unstructured log strings
- **Instead:** Use structured format with context
- **REF:** INT-02
- **Location:** NM05-Anti-Patterns-Part-2.md

---

### Category 7: Testing & Deployment Anti-Patterns

#### ❌ AP-26: No Unit Tests
- **Problem:** Deploying untested code
- **Instead:** Write tests for core logic
- **REF:** LESS-15
- **Location:** NM05-Anti-Patterns-Part-2.md

#### ❌ AP-27: Skipping Verification Protocol
- **Problem:** Not following 5-step verification
- **Instead:** Always complete LESS-15 checklist
- **REF:** LESS-15
- **Location:** NM05-Anti-Patterns-Part-2.md

#### ❌ AP-28: Not Reading Complete Files Before Modifying
- **Problem:** Modifying code based on partial context
- **Instead:** Read entire file, understand context
- **REF:** LESS-01, LESS-15
- **Location:** NM05-Anti-Patterns-Part-2.md

---

## 📊 QUICK REFERENCE TABLE

| Anti-Pattern | Category | Severity | Instead Use | REF |
|--------------|----------|----------|-------------|-----|
| AP-01 | Architecture | 🔴 Critical | `import gateway` | RULE-01 |
| AP-02 | Architecture | 🔴 Critical | Gateway only | ARCH-01 |
| AP-03 | Architecture | 🔴 Critical | Respect layers | DEP-## |
| AP-04 | Architecture | 🔴 Critical | Follow DEP layers | DEP-## |
| AP-05 | Architecture | 🟡 Medium | Flat structure | DEC-08 |
| AP-06 | Implementation | 🔴 Critical | <128MB total | DEC-07 |
| AP-07 | Implementation | 🟠 High | Lazy import | LMMS |
| AP-08 | Implementation | 🔴 Critical | Remove locks | DEC-04 |
| AP-09 | Implementation | 🟠 High | Async or Step Functions | LESS-06 |
| AP-10 | Implementation | 🟡 Medium | `None` default | Python BP |
| AP-11 | Error Handling | 🟠 High | Log + handle | ERR-02 |
| AP-12 | Error Handling | 🟠 High | Specific exceptions | ERR-03 |
| AP-13 | Error Handling | 🟡 Medium | Typed exceptions | ERR-04 |
| AP-14 | Error Handling | 🟠 High | Typed `except` | ERR-02 |
| AP-15 | Error Handling | 🟡 Medium | `from` context | ERR-06 |
| AP-16 | Cache | 🟠 High | gateway.cache_* | INT-01 |
| AP-17 | Cache | 🔴 Critical | Check sentinel | BUG-02 |
| AP-18 | Cache | 🟡 Medium | Consider TTL | INT-01 |
| AP-19 | Cache | 🔴 Critical | Sanitize at router | DEC-05 |
| AP-20 | Configuration | 🟠 High | SSM + gateway | INT-05 |
| AP-21 | Configuration | 🟡 Medium | Use env vars | INT-05 |
| AP-22 | Configuration | 🟠 High | Validate startup | ERR-05 |
| AP-23 | Logging | 🟡 Medium | gateway.log_* | INT-02 |
| AP-24 | Logging | 🔴 Critical | Redact sensitive | INT-03 |
| AP-25 | Logging | 🟡 Medium | Structured logs | INT-02 |
| AP-26 | Testing | 🟠 High | Write tests | LESS-15 |
| AP-27 | Testing | 🟠 High | 5-step protocol | LESS-15 |
| AP-28 | Testing | 🔴 Critical | Read complete | LESS-01 |

**Severity Key:**
- 🔴 Critical: Never do this (breaks system or causes bugs)
- 🟠 High: Strongly discouraged (causes problems)
- 🟡 Medium: Avoid when possible (suboptimal)

---

## 🎯 BEFORE SUGGESTING ANY SOLUTION

**Mandatory Pre-Flight Checklist:**

1. ✅ Does solution import via gateway? (not AP-01)
2. ✅ No threading locks? (not AP-08)
3. ✅ No bare except clauses? (not AP-14)
4. ✅ Sentinel sanitization if cache involved? (not AP-19)
5. ✅ Dependencies reasonable? (not AP-06)
6. ✅ No sensitive data logged? (not AP-24)
7. ✅ Following SIMA pattern? (not AP-02)

**If ANY of these fail, redesign solution before responding.**

---

## 🔍 HOW TO USE THIS CHECKLIST

### During Response Preparation
1. Read user's request
2. Formulate initial solution
3. **Scan CRITICAL section above (5 seconds)**
4. Check Quick Reference Table for relevant patterns
5. If any anti-pattern detected, revise solution
6. Provide corrected solution with explanation

### When User Asks "Can I..."
1. Immediately search this checklist for similar pattern
2. If matches anti-pattern → NO with explanation
3. If unclear → Search full NM05 files
4. Provide alternative that achieves goal correctly

### When Reviewing Code
1. Check each import statement (AP-01, AP-07)
2. Check for locks/threading (AP-08)
3. Check error handling (AP-11 through AP-15)
4. Check cache operations (AP-16 through AP-19)
5. Check logging (AP-23 through AP-25)

---

## 💡 COMMON SCENARIOS

### Scenario 1: User wants to add threading
**Anti-Pattern:** AP-08  
**Response:** "Lambda is single-threaded (DEC-04), threading locks are unnecessary and add overhead. What are you trying to achieve? I can suggest the correct approach."

### Scenario 2: User wants to import cache_core
**Anti-Pattern:** AP-01  
**Response:** "Direct imports violate SIMA pattern (RULE-01). Use `import gateway` and call `gateway.cache_get()` instead."

### Scenario 3: User uses bare except
**Anti-Pattern:** AP-14  
**Response:** "Bare except clauses mask errors (ERR-02). Use specific exception types: `except ValueError as e:` to handle appropriately."

### Scenario 4: User returns cache result directly
**Anti-Pattern:** AP-19  
**Response:** "This could leak _CacheMiss sentinel (BUG-01). Always sanitize: `if gateway.cache_is_sentinel(result):` before returning."

### Scenario 5: User adds pandas import
**Anti-Pattern:** AP-06  
**Response:** "Pandas is 100MB+ (DEC-07). Lambda limit is 128MB. Is there a lighter alternative? What specific functionality do you need?"

---

## 🚨 RED FLAGS (INSTANT NO)

These should trigger immediate rejection without searching neural maps:

1. **"Can I use threading locks?"** → NO (AP-08, DEC-04)
2. **"Can I import [core_module] directly?"** → NO (AP-01, RULE-01)
3. **"Can I use except: without type?"** → NO (AP-14, ERR-02)
4. **"Can I add subdirectories?"** → NO (AP-05, DEC-08)
5. **"Can I skip the verification protocol?"** → NO (AP-27, LESS-15)

---

## 📚 INTEGRATION WITH OTHER TOOLS

### With SESSION-START-Quick-Context.md
- Context file has RED FLAGS section
- This checklist provides detailed alternatives
- Use context for instant NO, this for detailed explanation

### With Workflows-Playbook.md
- Workflows reference anti-patterns in decision trees
- Check this list at verification steps
- Workflow #5 "Can I" uses this checklist extensively

### With REF-ID-Directory.md
- Each anti-pattern has REF-IDs for deeper context
- Use directory to look up related decisions
- Cross-reference with design decisions (DEC-##)

---

## 🔄 MAINTENANCE

**When to Update:**
- New anti-pattern identified in production
- Existing anti-pattern gets new REF-ID
- Severity changes based on real-world impact
- Better alternatives discovered

**Update Process:**
1. Add to appropriate category
2. Assign next AP-## number
3. Add to Quick Reference Table
4. Update RED FLAGS if critical
5. Reference in relevant workflows

---

**END OF ANTI-PATTERNS CHECKLIST**

For questions about anti-pattern usage, see:
- NM05-Anti-Patterns-Part-1.md (AP-01 to AP-15)
- NM05-Anti-Patterns-Part-2.md (AP-16 to AP-28)
- SESSION-START-Quick-Context.md (RED FLAGS)
- Workflows-Playbook.md (Workflow #5: "Can I" questions)

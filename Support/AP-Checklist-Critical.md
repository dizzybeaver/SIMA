# AP-Checklist-Critical.md
**Critical Anti-Patterns - Check Before EVERY Response**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: 4 critical anti-patterns + pre-flight checklist

---

## 🚨 THE CRITICAL FOUR

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

**Why:** Breaks SUGA pattern, causes circular imports  
**Impact:** System architecture failure  
**REF:** RULE-01, DEC-01  
**Location:** NM05/NM05-AntiPatterns-Import_AP-01.md  
**ZAPH:** Tier 1 (most frequent violation)

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
```python
# Lambda is single-threaded
# No locks needed, they add overhead
def operation():
    # Direct execution, no synchronization needed
    pass
```

**Why:** Lambda is single-threaded, locks unnecessary and harmful  
**Impact:** Performance degradation, false sense of safety  
**REF:** DEC-04, LESS-06  
**Location:** NM05/NM05-AntiPatterns-Concurrency_AP-08.md  
**ZAPH:** Tier 1 (RED FLAG - instant NO)

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
    raise  # Re-raise if cannot handle
```

**Why:** Masks real errors, makes debugging impossible  
**Impact:** Silent failures, impossible troubleshooting  
**REF:** ERR-02, LESS-08  
**Location:** NM05/NM05-AntiPatterns-ErrorHandling_AP-14.md  
**ZAPH:** Tier 1 (common mistake)

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
# In router - sanitize sentinels
result = gateway.cache_get(key)
if gateway.cache_is_sentinel(result):
    # Handle miss appropriately
    return None  # or default_value
return result
```

**Why:** Sentinel leak bug (BUG-01) cost 535ms in production  
**Impact:** Performance penalty, external code confusion  
**REF:** DEC-05, BUG-01  
**Location:** NM05/NM05-AntiPatterns-Security_AP-19.md  
**ZAPH:** Tier 1 (critical bug source)

---

## ✅ PRE-FLIGHT CHECKLIST

**Before suggesting ANY solution, verify:**

1. ✅ **Gateway imports only?** (not AP-01)  
   → Solution uses `import gateway`, not direct imports

2. ✅ **No threading locks?** (not AP-08)  
   → No threading.Lock(), Queue(), Event(), etc.

3. ✅ **Specific exception handling?** (not AP-14)  
   → All `except` clauses have specific types

4. ✅ **Sentinel sanitization?** (not AP-19)  
   → If cache involved, sentinels checked at router

5. ✅ **Dependencies reasonable?** (not AP-09)  
   → No heavy libraries without justification

6. ✅ **No sensitive data logged?** (not AP-18)  
   → Passwords, tokens, PII redacted

7. ✅ **Following SUGA pattern?** (not AP-02)  
   → Gateway → Interface → Implementation

**If ANY fail → Redesign solution before responding**

---

## 🚨 RED FLAGS (Instant NO)

These trigger immediate rejection:

| User Question | Anti-Pattern | Response |
|---------------|--------------|----------|
| "Can I use threading locks?" | AP-08 | NO - Lambda single-threaded (DEC-04) |
| "Can I import cache_core?" | AP-01 | NO - Use gateway only (RULE-01) |
| "Can I use except: without type?" | AP-14 | NO - Specific exceptions (ERR-02) |
| "Can cache return _CacheMiss?" | AP-19 | NO - Sanitize at router (DEC-05) |
| "Can I skip verification?" | AP-27 | NO - LESS-15 mandatory |

**See:** AP-Checklist-Scenarios.md for full response templates

---

## 💡 USAGE

### Every Response
1. Open this file (5 seconds)
2. Scan pre-flight checklist
3. If any violation → Stop, explain, provide alternative
4. If clear → Proceed with solution

### When User Asks "Can I..."
1. Check RED FLAGS table
2. If matches → Instant NO with REF-ID
3. If not listed → Check AP-Checklist-ByCategory.md

---

## 📊 IMPACT DATA

**From production incidents:**

- **AP-01 violations:** 60% of import errors
- **AP-08 violations:** 30% of performance issues
- **AP-14 violations:** 45% of debugging nightmares
- **AP-19 violations:** 100% of BUG-01 incidents (535ms each)

**Prevention saves:** ~4-6 hours debugging per incident

---

## 🔗 RELATED FILES

**Hub:** ANTI-PATTERNS-CHECKLIST.md  
**Category Table:** AP-Checklist-ByCategory.md  
**Scenarios:** AP-Checklist-Scenarios.md  
**Neural Maps:** NM05/NM05-AntiPatterns-*_Index.md  
**ZAPH:** NM00/NM00B-ZAPH-Tier1.md

---

**END OF CRITICAL PATTERNS**

**Lines:** ~180 (properly sized for component file)  
**Usage:** Check before EVERY response  
**Update:** When new critical pattern identified

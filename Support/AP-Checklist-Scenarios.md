# AP-Checklist-Scenarios.md
**Common "Can I" Scenarios with Template Responses**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: Pre-built responses for frequent anti-pattern questions

---

## 💡 HOW TO USE

When user asks "Can I do X?":
1. Find matching scenario below
2. Use template response
3. Customize with specific context
4. Include REF-IDs

---

## 🚨 SCENARIO 1: Threading Locks

**User asks:** "Can I use threading locks?" or "Should I add mutex?"

**Anti-Pattern:** AP-08  
**Answer:** NO

**Template Response:**
```
I understand why you'd want thread safety, but threading locks aren't 
needed in Lambda because:

Issue: Lambda is single-threaded (DEC-04)

Why This Doesn't Work:
- Lambda executes one request at a time per container
- Threading locks add overhead without benefit
- Can actually harm performance
- False sense of security

Better Alternative:
Lambda's execution model already provides isolation - each request
executes independently. What specific concurrency issue are you 
trying to solve? I can suggest the right approach.

References:
- DEC-04: No threading locks (NM04/.../Architecture_DEC-04.md)
- AP-08: Threading primitives (NM05/.../Concurrency_AP-08.md)
- LESS-06: Lambda environment lessons
```

---

## 🚨 SCENARIO 2: Direct Imports

**User asks:** "Can I import cache_core directly?" or "Why can't I import X?"

**Anti-Pattern:** AP-01  
**Answer:** NO

**Template Response:**
```
Direct imports violate the SUGA pattern. Here's why and what to do:

Issue: Direct cross-interface imports (AP-01)

Why This Doesn't Work:
- Breaks gateway architecture
- Causes circular import errors
- Makes code harder to test
- Violates RULE-01

Correct Implementation:
```python
# ❌ WRONG
from cache_core import get_value

# ✅ CORRECT
import gateway
value = gateway.cache_get(key)
```

Why Gateway Works:
- Single entry point prevents circular imports
- Enables interface-level mocking for tests
- Maintains clean architecture
- Following SUGA pattern (DEC-01)

References:
- RULE-01: Cross-interface via gateway only
- AP-01: Direct imports (NM05/.../Import_AP-01.md)
- DEC-01: SUGA pattern choice
```

---

## 🚨 SCENARIO 3: Bare Except

**User asks:** "Can I use except: without type?" or "Why specify exception type?"

**Anti-Pattern:** AP-14  
**Answer:** NO

**Template Response:**
```
Bare except clauses create serious debugging problems:

Issue: Bare except clauses (AP-14)

Why This Doesn't Work:
- Catches EVERYTHING, including KeyboardInterrupt, SystemExit
- Masks real errors, makes debugging impossible
- Violates error handling best practices (ERR-02)
- You can't distinguish between different failure modes

Correct Implementation:
```python
# ❌ WRONG
try:
    operation()
except:  # Too broad!
    pass

# ✅ CORRECT
try:
    operation()
except ValueError as e:
    gateway.log_error(f"Invalid value: {e}")
    return default_value
except KeyError as e:
    gateway.log_error(f"Missing key: {e}")
    raise  # Re-raise if cannot handle
```

References:
- AP-14: Bare except (NM05/.../ErrorHandling_AP-14.md)
- ERR-02: Error propagation patterns
- LESS-08: Test failure paths
```

---

## 🚨 SCENARIO 4: Sentinel Leaks

**User asks:** "Can cache return _CacheMiss?" or "Why sanitize sentinels?"

**Anti-Pattern:** AP-19  
**Answer:** NO (must sanitize)

**Template Response:**
```
Allowing sentinels to leak caused BUG-01 (535ms penalty). Here's how 
to handle them correctly:

Issue: Sentinel objects crossing boundaries (AP-19)

Why This Doesn't Work:
- External code doesn't understand sentinel objects
- Caused 535ms performance penalty in production (BUG-01)
- Creates confusion and bugs downstream
- Violates DEC-05 (sentinel sanitization)

Correct Implementation:
```python
# ❌ WRONG - In router
result = gateway.cache_get(key)
return result  # Might leak _CacheMiss!

# ✅ CORRECT - Sanitize at router
result = gateway.cache_get(key)
if gateway.cache_is_sentinel(result):
    gateway.log_debug(f"Cache miss for key: {key}")
    return None  # or compute default
return result
```

Why Router Layer:
- DEC-05: Sanitize sentinels at router layer
- Keeps internal implementation details internal
- Provides clean API to external code

References:
- AP-19: Sentinel leaks (NM05/.../Security_AP-19.md)
- BUG-01: Sentinel leak bug
- DEC-05: Sentinel sanitization
```

---

## 🚨 SCENARIO 5: Heavy Libraries

**User asks:** "Can I add pandas/numpy/scipy?" or "Can I use large library?"

**Anti-Pattern:** AP-09  
**Answer:** Depends (usually NO)

**Template Response:**
```
Heavy libraries need careful justification due to Lambda's 128MB limit:

Issue: Heavy dependencies (AP-09)

Current Constraint:
- Lambda memory limit: 128MB (DEC-07)
- pandas: ~100MB
- numpy: ~50MB
- scipy: ~80MB

Questions to Answer:
1. Is this library absolutely necessary?
2. Is there a lighter alternative?
3. What specific functionality do you need?
4. Have you measured the impact on cold start?

Alternatives:
- Built-in libraries (json, csv, math)
- Lightweight alternatives (use standard library)
- External processing (Step Functions, separate service)

If Justified:
- Document why in DEC-## (add new decision)
- Measure cold start impact (PATH-01)
- Consider lazy import (defer until needed)

References:
- AP-09: Heavy dependencies (NM05/.../Dependencies_AP-09.md)
- DEC-07: Memory limit
- PATH-01: Cold start pathway
```

---

## 🚨 SCENARIO 6: Subdirectories

**User asks:** "Can I create subdirectories?" or "Can I organize into folders?"

**Anti-Pattern:** AP-05  
**Answer:** NO (except home_assistant/)

**Template Response:**
```
Flat file structure is intentional and proven:

Issue: Subdirectories (AP-05)

Why Flat Structure:
- Proven simple architecture (DEC-08)
- Avoids import complexity
- Makes file location obvious
- No relative import issues

Exception:
home_assistant/ is allowed for Home Assistant extension code

If You Need Organization:
- Use clear file naming (interface_cache.py, cache_core.py)
- Group by prefix (ha_*, debug_*, test_*)
- Keep related code together in single file

References:
- AP-05: Subdirectories (NM05/.../Import_AP-05.md)
- DEC-08: Flat file structure
```

---

## 🚨 SCENARIO 7: Skip Verification

**User asks:** "Can I skip the verification protocol?" or "Do I need LESS-15?"

**Anti-Pattern:** AP-27  
**Answer:** NO

**Template Response:**
```
Verification protocol prevents 90% of common mistakes:

Issue: Skipping verification (AP-27)

Why Verification Matters:
- Catches anti-patterns before deployment
- Prevents circular imports
- Ensures SUGA pattern compliance
- Takes only 2-3 minutes

5-Step Protocol (LESS-15):
1. Read complete file (not partial)
2. Verify SUGA pattern
3. Check anti-patterns
4. Verify dependencies
5. Cite sources

What Verification Prevents:
- AP-01: Direct imports
- AP-03: Circular dependencies
- AP-08: Threading locks
- AP-28: Partial file reads

References:
- AP-27: Skipping verification (NM05/.../Process_AP-27.md)
- LESS-15: 5-step verification protocol
```

---

## 🚨 SCENARIO 8: Hardcoded Secrets

**User asks:** "Can I put API key in code?" or "Where do I store credentials?"

**Anti-Pattern:** AP-17  
**Answer:** NO

**Template Response:**
```
Never hardcode secrets - use SSM Parameter Store:

Issue: Hardcoded secrets (AP-17)

Why This Doesn't Work:
- Security risk (exposed in version control)
- Can't rotate without code deploy
- Violates security best practices
- Fails compliance audits

Correct Implementation:
```python
# ❌ WRONG
API_KEY = "abc123secret"

# ✅ CORRECT
api_key = gateway.config_get("api_key")
```

Setup:
1. Store in SSM Parameter Store: /lambda-execution-engine/api_key
2. Lambda has IAM permissions to read SSM
3. Gateway config interface retrieves securely
4. Use DEC-21 pattern (token-only)

References:
- AP-17: Hardcoded secrets (NM05/.../Security_AP-17.md)
- INT-05: CONFIG interface
- DEC-21: SSM token-only
```

---

## 💡 USAGE TIPS

### Finding Right Scenario
1. Extract key words from user question
2. Scan scenario titles
3. Match closest scenario
4. Customize template with specifics

### When No Match
1. Check AP-Checklist-ByCategory.md
2. Find relevant anti-pattern
3. Build response using pattern structure
4. Include REF-IDs

### Template Customization
- Keep "Issue" and "Why This Doesn't Work"
- Adapt "Correct Implementation" to specific case
- Always include "References" with REF-IDs
- Maintain friendly, helpful tone

---

## 🔗 RELATED FILES

**Hub:** ANTI-PATTERNS-CHECKLIST.md  
**Critical:** AP-Checklist-Critical.md  
**Category Table:** AP-Checklist-ByCategory.md  
**Workflows:** Workflow-05-CanI.md  
**Neural Maps:** NM05/ (all anti-pattern files)

---

**END OF SCENARIOS**

**Lines:** ~280 (within component limit)  
**Use:** When user asks "Can I do X?"  
**Update:** Add new scenarios as they emerge

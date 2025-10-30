# ANTI-PATTERNS CHECKLIST (SIMA v3)
**SUGA-ISP Lambda Project - Quick Reference for What NOT to Do**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: Fast scannable reference for all 28 anti-patterns (SIMA v3 updated)

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

**Why:** Breaks SUGA pattern, causes circular imports  
**REF:** RULE-01, DEC-01  
**Location:** NM05/NM05-AntiPatterns-Import_AP-01.md

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
**Location:** NM05/NM05-AntiPatterns-Concurrency_AP-08.md

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
**Location:** NM05/NM05-AntiPatterns-ErrorHandling_AP-14.md

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
**Location:** NM05/NM05-AntiPatterns-Security_AP-19.md

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
**Location:** NM05/NM05-AntiPatterns-Dependencies_AP-09.md

---

## 📋 COMPLETE ANTI-PATTERNS LIST (v3 File Paths)

### Category 1: Import & Architecture Violations

#### ❌ AP-01: Direct Cross-Interface Imports
- **Problem:** Importing core modules directly
- **Instead:** Always use `import gateway`
- **REF:** RULE-01
- **Location:** NM05/NM05-AntiPatterns-Import_AP-01.md

#### ❌ AP-02: Bypassing Gateway Layer
- **Problem:** Creating alternative entry points
- **Instead:** All operations through gateway.py
- **REF:** ARCH-01, DEC-01
- **Location:** NM05/NM05-AntiPatterns-Import_AP-02.md

#### ❌ AP-03: Circular Dependencies
- **Problem:** Module A imports B, B imports A
- **Instead:** Follow dependency layers (DEP-01 to DEP-05)
- **REF:** NM02-Dependencies_Index.md
- **Location:** NM05/NM05-AntiPatterns-Import_AP-03.md

#### ❌ AP-04: Breaking Dependency Layers
- **Problem:** Lower layer importing higher layer
- **Instead:** Respect Layer 0 → Layer 1 → Layer 2 → Router
- **REF:** DEP-01 to DEP-05
- **Location:** NM05/NM05-AntiPatterns-Import_AP-04.md

#### ❌ AP-05: Subdirectories (Except home_assistant/)
- **Problem:** Creating nested directory structure
- **Instead:** Keep flat file structure (proven and intentional)
- **REF:** DEC-08
- **Location:** NM05/NM05-AntiPatterns-Import_AP-05.md

---

### Category 2: Implementation Anti-Patterns

#### ❌ AP-06: God Objects
- **Problem:** Massive classes with too many responsibilities
- **Instead:** Split by interface responsibility
- **REF:** ARCH-09
- **Location:** NM05/NM05-AntiPatterns-Implementation_AP-06.md

#### ❌ AP-07: Large Modules (>400 lines)
- **Problem:** Monolithic files that are hard to understand
- **Instead:** Keep modules focused and under size limits
- **REF:** ARCH-09
- **Location:** NM05/NM05-AntiPatterns-Implementation_AP-07.md

---

### Category 3: Concurrency Anti-Patterns

#### ❌ AP-08: Threading Locks or Primitives
- **Problem:** Using threading.Lock(), Queue(), etc.
- **Instead:** Remove - Lambda is single-threaded
- **REF:** DEC-04, LESS-06
- **Location:** NM05/NM05-AntiPatterns-Concurrency_AP-08.md

#### ❌ AP-11: Race Conditions
- **Problem:** Assuming concurrent access patterns
- **Instead:** Remember Lambda is single-threaded
- **REF:** DEC-04
- **Location:** NM05/NM05-AntiPatterns-Concurrency_AP-11.md

#### ❌ AP-13: Multiprocessing
- **Problem:** Using multiprocessing in Lambda
- **Instead:** Single process, single thread execution
- **REF:** DEC-04
- **Location:** NM05/NM05-AntiPatterns-Concurrency_AP-13.md

---

### Category 4: Dependencies

#### ❌ AP-09: Heavy Dependencies Without Justification
- **Problem:** Adding large libraries unnecessarily
- **Instead:** Keep dependencies < 128MB total
- **REF:** DEC-07
- **Location:** NM05/NM05-AntiPatterns-Dependencies_AP-09.md

---

### Category 5: Critical Patterns

#### ❌ AP-10: Mutable Default Arguments
- **Problem:** `def func(items=[])`
- **Instead:** `def func(items=None): items = items or []`
- **REF:** Standard Python best practice
- **Location:** NM05/NM05-AntiPatterns-Critical_AP-10.md

---

### Category 6: Performance Anti-Patterns

#### ❌ AP-12: Premature Optimization
- **Problem:** Optimizing before measuring
- **Instead:** Measure first (LESS-02)
- **REF:** LESS-02, WISDOM-02
- **Location:** NM05/NM05-AntiPatterns-Performance_AP-12.md

---

### Category 7: Error Handling Anti-Patterns

#### ❌ AP-14: Bare Except Clauses
- **Problem:** `except:` without type
- **Instead:** `except SpecificError:`
- **REF:** ERR-02
- **Location:** NM05/NM05-AntiPatterns-ErrorHandling_AP-14.md

#### ❌ AP-15: Swallowing Exceptions
- **Problem:** `except Exception: pass`
- **Instead:** Log errors, handle gracefully
- **REF:** ERR-02
- **Location:** NM05/NM05-AntiPatterns-ErrorHandling_AP-15.md

#### ❌ AP-16: No Error Context
- **Problem:** Catching without `from` or context
- **Instead:** `raise NewError(...) from original_error`
- **REF:** ERR-06
- **Location:** NM05/NM05-AntiPatterns-ErrorHandling_AP-16.md

---

### Category 8: Security Anti-Patterns

#### ❌ AP-17: Hardcoded Secrets
- **Problem:** API keys, passwords in code
- **Instead:** Use gateway.config_get() + SSM
- **REF:** INT-05, DEC-21
- **Location:** NM05/NM05-AntiPatterns-Security_AP-17.md

#### ❌ AP-18: Logging Sensitive Data
- **Problem:** Logging passwords, tokens, PII
- **Instead:** Redact sensitive fields before logging
- **REF:** INT-02, INT-03
- **Location:** NM05/NM05-AntiPatterns-Security_AP-18.md

#### ❌ AP-19: Sentinel Objects Crossing Boundaries
- **Problem:** Allowing _CacheMiss to leak to external code
- **Instead:** Sanitize at router layer (DEC-05)
- **REF:** BUG-01, DEC-05
- **Location:** NM05/NM05-AntiPatterns-Security_AP-19.md

---

### Category 9: Quality Anti-Patterns

#### ❌ AP-20: Hardcoded Configuration Values
- **Problem:** `API_KEY = "abc123"` in code
- **Instead:** Use gateway.config_get() + SSM
- **REF:** INT-05
- **Location:** NM05/NM05-AntiPatterns-Quality_AP-20.md

#### ❌ AP-21: Magic Numbers
- **Problem:** Unexplained constants
- **Instead:** Named constants with documentation
- **REF:** LESS-04
- **Location:** NM05/NM05-AntiPatterns-Quality_AP-21.md

#### ❌ AP-22: Copy-Paste Code
- **Problem:** Duplicated logic across files
- **Instead:** Extract to shared function
- **REF:** LESS-04
- **Location:** NM05/NM05-AntiPatterns-Quality_AP-22.md

---

### Category 10: Testing Anti-Patterns

#### ❌ AP-23: No Unit Tests
- **Problem:** Deploying untested code
- **Instead:** Write tests for core logic
- **REF:** LESS-15
- **Location:** NM05/NM05-AntiPatterns-Testing_AP-23.md

#### ❌ AP-24: Testing Only Success Paths
- **Problem:** Not testing error conditions
- **Instead:** Test failure scenarios
- **REF:** LESS-08
- **Location:** NM05/NM05-AntiPatterns-Testing_AP-24.md

---

### Category 11: Documentation Anti-Patterns

#### ❌ AP-25: Undocumented Decisions
- **Problem:** No record of why choices were made
- **Instead:** Document in neural maps (DEC-##)
- **REF:** LESS-11, DEC-19
- **Location:** NM05/NM05-AntiPatterns-Documentation_AP-25.md

#### ❌ AP-26: Stale Comments
- **Problem:** Comments contradicting code
- **Instead:** Keep docs synchronized with code
- **REF:** LESS-12
- **Location:** NM05/NM05-AntiPatterns-Documentation_AP-26.md

---

### Category 12: Process Anti-Patterns

#### ❌ AP-27: Skipping Verification Protocol
- **Problem:** Not following 5-step verification
- **Instead:** Always complete LESS-15 checklist
- **REF:** LESS-15
- **Location:** NM05/NM05-AntiPatterns-Process_AP-27.md

#### ❌ AP-28: Not Reading Complete Files Before Modifying
- **Problem:** Modifying code based on partial context
- **Instead:** Read entire file, understand context
- **REF:** LESS-01, LESS-15
- **Location:** NM05/NM05-AntiPatterns-Process_AP-28.md

---

## 📊 QUICK REFERENCE TABLE (v3 Updated)

| Anti-Pattern | Category | Severity | Instead Use | Location (v3) |
|--------------|----------|----------|-------------|---------------|
| AP-01 | Import | 🔴 Critical | `import gateway` | NM05/.../Import_AP-01.md |
| AP-02 | Import | 🔴 Critical | Gateway only | NM05/.../Import_AP-02.md |
| AP-03 | Import | 🔴 Critical | Respect layers | NM05/.../Import_AP-03.md |
| AP-04 | Import | 🔴 Critical | Follow DEP | NM05/.../Import_AP-04.md |
| AP-05 | Import | 🟡 Medium | Flat structure | NM05/.../Import_AP-05.md |
| AP-06 | Implementation | 🟠 High | Split by interface | NM05/.../Implementation_AP-06.md |
| AP-07 | Implementation | 🟡 Medium | <400 lines | NM05/.../Implementation_AP-07.md |
| AP-08 | Concurrency | 🔴 Critical | Remove locks | NM05/.../Concurrency_AP-08.md |
| AP-09 | Dependencies | 🔴 Critical | <128MB total | NM05/.../Dependencies_AP-09.md |
| AP-10 | Critical | 🔴 Critical | `None` default | NM05/.../Critical_AP-10.md |
| AP-11 | Concurrency | 🟠 High | Single-threaded | NM05/.../Concurrency_AP-11.md |
| AP-12 | Performance | 🟡 Medium | Measure first | NM05/.../Performance_AP-12.md |
| AP-13 | Concurrency | 🟠 High | Single process | NM05/.../Concurrency_AP-13.md |
| AP-14 | ErrorHandling | 🟠 High | Typed `except` | NM05/.../ErrorHandling_AP-14.md |
| AP-15 | ErrorHandling | 🟠 High | Log + handle | NM05/.../ErrorHandling_AP-15.md |
| AP-16 | ErrorHandling | 🟡 Medium | `from` context | NM05/.../ErrorHandling_AP-16.md |
| AP-17 | Security | 🔴 Critical | SSM + gateway | NM05/.../Security_AP-17.md |
| AP-18 | Security | 🔴 Critical | Redact sensitive | NM05/.../Security_AP-18.md |
| AP-19 | Security | 🔴 Critical | Sanitize at router | NM05/.../Security_AP-19.md |
| AP-20 | Quality | 🟠 High | gateway.config_* | NM05/.../Quality_AP-20.md |
| AP-21 | Quality | 🟡 Medium | Named constants | NM05/.../Quality_AP-21.md |
| AP-22 | Quality | 🟡 Medium | Extract shared | NM05/.../Quality_AP-22.md |
| AP-23 | Testing | 🟠 High | Write tests | NM05/.../Testing_AP-23.md |
| AP-24 | Testing | 🟠 High | Test failures | NM05/.../Testing_AP-24.md |
| AP-25 | Documentation | 🟠 High | Document DEC | NM05/.../Documentation_AP-25.md |
| AP-26 | Documentation | 🟡 Medium | Sync docs | NM05/.../Documentation_AP-26.md |
| AP-27 | Process | 🟠 High | LESS-15 | NM05/.../Process_AP-27.md |
| AP-28 | Process | 🔴 Critical | Read complete | NM05/.../Process_AP-28.md |

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
5. ✅ Dependencies reasonable? (not AP-09)
6. ✅ No sensitive data logged? (not AP-18)
7. ✅ Following SUGA pattern? (not AP-02)

**If ANY of these fail, redesign solution before responding.**

---

## 🔍 HOW TO USE THIS CHECKLIST (v3 Integration)

### With Gateway Layer (NEW)
1. **Check ZAPH first** for frequently violated patterns
   - NM00/NM00B-ZAPH-Tier1.md has critical anti-patterns
   - AP-01, AP-08, AP-14, AP-19 are ZAPH Tier 1

2. **Route via Quick Index** for keyword lookup
   - NM00/NM00-Quick_Index.md routes to anti-pattern categories
   - "threading" → NM05-AntiPatterns-Concurrency_Index.md
   - "import" → NM05-AntiPatterns-Import_Index.md

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
3. If unclear → Search NM05/ category indexes
4. Provide alternative that achieves goal correctly

### When Reviewing Code
1. Check each import statement (AP-01, AP-02)
2. Check for locks/threading (AP-08, AP-11, AP-13)
3. Check error handling (AP-14, AP-15, AP-16)
4. Check security (AP-17, AP-18, AP-19)
5. Check logging (AP-18)

---

## 💡 COMMON SCENARIOS (v3 Navigation)

### Scenario 1: User wants to add threading
**Anti-Pattern:** AP-08  
**Navigation:** NM05/NM05-AntiPatterns-Concurrency_AP-08.md  
**ZAPH:** Tier 1 (critical pattern)  
**Response:** "Lambda is single-threaded (DEC-04), threading locks are unnecessary. See NM05-AntiPatterns-Concurrency_AP-08.md for detailed rationale."

### Scenario 2: User wants to import cache_core
**Anti-Pattern:** AP-01  
**Navigation:** NM05/NM05-AntiPatterns-Import_AP-01.md  
**ZAPH:** Tier 1 (critical pattern)  
**Response:** "Direct imports violate SUGA pattern (RULE-01). Use `import gateway` and call `gateway.cache_get()`. See NM05-AntiPatterns-Import_AP-01.md."

### Scenario 3: User uses bare except
**Anti-Pattern:** AP-14  
**Navigation:** NM05/NM05-AntiPatterns-ErrorHandling_AP-14.md  
**ZAPH:** Tier 1 (critical pattern)  
**Response:** "Bare except masks errors (ERR-02). Use specific exception types. See NM05-AntiPatterns-ErrorHandling_AP-14.md."

### Scenario 4: User returns cache result directly
**Anti-Pattern:** AP-19  
**Navigation:** NM05/NM05-AntiPatterns-Security_AP-19.md  
**ZAPH:** Tier 1 (critical pattern)  
**Response:** "This could leak _CacheMiss sentinel (BUG-01). Sanitize at router. See NM05-AntiPatterns-Security_AP-19.md."

### Scenario 5: User adds pandas import
**Anti-Pattern:** AP-09  
**Navigation:** NM05/NM05-AntiPatterns-Dependencies_AP-09.md  
**Response:** "Pandas is 100MB+ (DEC-07). Lambda limit is 128MB. See NM05-AntiPatterns-Dependencies_AP-09.md for alternatives."

---

## 🚨 RED FLAGS (INSTANT NO) - v3 References

These should trigger immediate rejection:

1. **"Can I use threading locks?"** → NO  
   - AP-08: NM05/NM05-AntiPatterns-Concurrency_AP-08.md  
   - DEC-04: NM04/NM04-Decisions-Architecture_DEC-04.md

2. **"Can I import [core_module] directly?"** → NO  
   - AP-01: NM05/NM05-AntiPatterns-Import_AP-01.md  
   - RULE-01: NM02/NM02-Dependencies-ImportRules_RULE-01.md

3. **"Can I use except: without type?"** → NO  
   - AP-14: NM05/NM05-AntiPatterns-ErrorHandling_AP-14.md  
   - ERR-02: NM03/NM03-Operations-ErrorHandling.md

4. **"Can I add subdirectories?"** → NO  
   - AP-05: NM05/NM05-AntiPatterns-Import_AP-05.md  
   - DEC-08: NM04/NM04-Decisions-Architecture_DEC-05.md (flat structure)

5. **"Can I skip verification protocol?"** → NO  
   - AP-27: NM05/NM05-AntiPatterns-Process_AP-27.md  
   - LESS-15: NM06/NM06-Lessons-Operations_LESS-15.md

---

## 📚 INTEGRATION WITH OTHER TOOLS (v3)

### With Gateway Layer (NM00/)
- **ZAPH:** NM00/NM00B-ZAPH-Tier1.md contains 4 critical anti-patterns
- **Quick Index:** NM00/NM00-Quick_Index.md routes to anti-pattern categories
- **Master Index:** NM00/NM00A-Master_Index.md shows complete anti-pattern structure

### With SESSION-START-Quick-Context.md
- Context file has RED FLAGS section (this checklist provides details)
- Use context for instant NO, this for detailed explanation
- ZAPH references link back to specific anti-patterns

### With Workflows-Playbook.md
- Workflows reference anti-patterns in decision trees
- Check this list at verification steps
- Workflow #5 "Can I" uses this checklist extensively

### With REF-ID-Directory.md
- Each anti-pattern has REF-IDs for deeper context
- Use directory to look up related decisions (DEC-##)
- Cross-reference with design decisions and lessons

---

## 🔄 MAINTENANCE (v3)

**When to Update:**
- New anti-pattern identified in production
- Existing anti-pattern gets new REF-ID
- Severity changes based on real-world impact
- Better alternatives discovered
- v3 file reorganization

**Update Process:**
1. Add to appropriate NM05/NM05-AntiPatterns-[Category]_AP-##.md
2. Update category index (NM05-AntiPatterns-[Category]_Index.md)
3. Update this checklist with v3 path
4. Update ZAPH if critical (Tier 1-3)
5. Update Quick Reference Table
6. Update RED FLAGS if critical
7. Reference in relevant workflows

---

## 🗂️ V3 FILE STRUCTURE

**Anti-patterns organized by category in NM05/ directory:**

```
NM05/
├── NM05-AntiPatterns_Index.md (Category index)
├── NM05-AntiPatterns-Import_Index.md (Topic index)
│   ├── NM05-AntiPatterns-Import_AP-01.md
│   ├── NM05-AntiPatterns-Import_AP-02.md
│   └── ... (AP-03 to AP-05)
├── NM05-AntiPatterns-Concurrency_Index.md
│   ├── NM05-AntiPatterns-Concurrency_AP-08.md
│   ├── NM05-AntiPatterns-Concurrency_AP-11.md
│   └── NM05-AntiPatterns-Concurrency_AP-13.md
├── NM05-AntiPatterns-ErrorHandling_Index.md
│   └── ... (AP-14, AP-15, AP-16)
├── NM05-AntiPatterns-Security_Index.md
│   └── ... (AP-17, AP-18, AP-19)
└── [Other category indexes and files]
```

**Quick Access:**
- **Gateway:** NM00/NM00-Quick_Index.md → search "anti-pattern"
- **ZAPH:** NM00/NM00B-ZAPH-Tier1.md → 4 critical anti-patterns
- **Category:** NM05/NM05-AntiPatterns_Index.md → all categories
- **Topic:** NM05/NM05-AntiPatterns-[Topic]_Index.md → specific topic

---

**END OF ANTI-PATTERNS CHECKLIST (SIMA v3)**

**Version:** 3.0.0 (Updated for SIMA v3 structure)  
**Changes from v2:**
- ✅ Updated all file paths to v3 format (NM05/...)
- ✅ Added gateway layer integration (ZAPH, Quick Index)
- ✅ Changed "SIMA pattern" → "SUGA pattern" (architecture)
- ✅ Added v3 navigation guidance
- ✅ Updated category organization
- ✅ Added file structure reference

**For questions about anti-patterns:**
- Category Index: NM05/NM05-AntiPatterns_Index.md
- Topic Indexes: NM05/NM05-AntiPatterns-[Topic]_Index.md
- Gateway: NM00/NM00-Quick_Index.md (keyword: "anti-pattern")
- ZAPH: NM00/NM00B-ZAPH-Tier1.md (critical patterns)
- Workflows: WORKFLOWS PLAYBOOK.md (Workflow #5: "Can I")

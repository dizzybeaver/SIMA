# NM00B-ZAPH-Tier1.md

# ZAPH Tier 1 - Critical Items

**Tier:** Critical (50+ accesses/30 days)  
**Items:** 20  
**Format:** Quick context + file pointer  
**Last Updated:** 2025-10-24  
**Version:** 3.0.0 (SIMA v3 Compliant)

---

## 📋 TIER 1 ITEMS (Top 20)

### 1. DEC-04: No Threading Locks
**File:** `NM04/NM04-Decisions-Technical_DEC-04.md`  
**Accesses:** 76  
**Priority:** 🔴 CRITICAL

**Quick Context:**
Lambda is single-threaded. Threading locks add 10-15ms overhead with zero benefit.

**Key Points:**
- Lambda executes ONE invocation per container
- Locks provide no protection (nothing to protect from)
- Use sequential execution instead
- For parallelism: Step Functions or multiple invocations

**Related:** AP-08 (Threading anti-pattern), ARCH-07 (LMMS), LESS-17

---

### 2. RULE-01: Gateway-Only Imports
**File:** `NM02/NM02-Dependencies-Import_RULE-01.md`  
**Accesses:** 70  
**Priority:** 🔴 CRITICAL

**Quick Context:**
ALL cross-interface operations through gateway. Never import core directly.

**Key Points:**
- Prevents circular imports (#1 Python architecture problem)
- Enables LIGS lazy loading (60% faster cold start)
- Maintains clean dependency graph
- Foundation of SUGA architecture

**Pattern:**
```python
# ✅ CORRECT
import gateway
gateway.cache_get(key)

# ❌ WRONG
from cache_core import get_value
```

**Related:** AP-01, ARCH-01, DEC-01

---

### 3. BUG-01: Sentinel Leak (535ms Cost)
**File:** `NM06/NM06-Bugs-Critical_BUG-01.md`  
**Accesses:** 68  
**Priority:** 🔴 CRITICAL

**Quick Context:**
`_CacheMiss` sentinel leaked across boundaries caused 535ms penalty.

**Key Points:**
- Sentinel objects are implementation details
- Never cross architectural boundaries
- Sanitize at router layer: sentinel → None
- Extension code validation caused 535ms penalty

**The Fix:**
```python
# Router sanitizes before returning
if value is _CacheMiss:
    return None
```

**Related:** DEC-05, AP-19, BUG-02, PATH-01

---

### 4. LESS-15: 5-Step Verification Protocol
**File:** `NM06/NM06-Lessons-Operations_LESS-15.md`  
**Accesses:** 68  
**Priority:** 🔴 CRITICAL

**Quick Context:**
Mandatory verification before ANY code change. Prevents 90% of mistakes.

**The 5 Steps:**
1. Read complete file (not just section)
2. Verify SUGA pattern (gateway → interface → core)
3. Check anti-patterns (no direct imports, no locks, etc.)
4. Verify dependencies (no circular imports)
5. Cite sources (REF-IDs + rationale)

**Why:** Saves 30 seconds, prevents 15 minutes of rework

**Related:** AP-27, LESS-01, AP-28

---

### 5. LESS-01: Read Complete Files First
**File:** `NM06/NM06-Lessons-CoreArchitecture_LESS-01.md`  
**Accesses:** 62  
**Priority:** 🔴 CRITICAL

**Quick Context:**
Always read ENTIRE file before modifying. Missing context causes bugs.

**Key Points:**
- Files have dependencies you might not see
- Context at top affects code at bottom
- Import statements matter
- Helper functions get broken
- Constants defined elsewhere get misused

**The Rule:**
1. User requests change
2. Read entire file (top to bottom)
3. Understand structure, imports, patterns
4. Locate specific section
5. Make informed change

**Related:** AP-28, LESS-15 (Step 1)

---

### 6. AP-01: Direct Cross-Interface Imports
**File:** `NM05/NM05-AntiPatterns-Import_AP-01.md`  
**Accesses:** 58  
**Priority:** 🔴 CRITICAL

**Quick Context:**
Never import core modules directly across interfaces.

**Anti-Pattern:**
```python
# ❌ WRONG
from cache_core import get_value
from logging_core import info
```

**Correct:**
```python
# ✅ CORRECT
import gateway
value = gateway.cache_get(key)
gateway.log_info("message")
```

**Why Bad:**
- Circular dependencies
- Breaks LIGS lazy loading
- Tight coupling
- Testing harder

**Related:** RULE-01, DEC-01, ARCH-01

---

### 7. AP-08: Threading Primitives
**File:** `NM05/NM05-AntiPatterns-Concurrency_AP-08.md`  
**Accesses:** 56  
**Priority:** 🔴 CRITICAL

**Quick Context:**
Never use locks, semaphores, queues in Lambda.

**Anti-Pattern:**
```python
# ❌ WRONG
cache_lock = threading.Lock()
with cache_lock:
    value = cache.get(key)
```

**Correct:**
```python
# ✅ CORRECT
value = cache.get(key)  # No lock needed
```

**Why Bad:**
- Lambda is single-threaded
- Zero protection benefit
- 10-15ms overhead
- False sense of safety

**Related:** DEC-04, LESS-17

---

### 8. AP-14: Bare Except Clauses
**File:** `NM05/NM05-AntiPatterns-ErrorHandling_AP-14.md`  
**Accesses:** 54  
**Priority:** 🔴 CRITICAL

**Quick Context:**
Never use bare `except:` - catches EVERYTHING including system exits.

**Anti-Pattern:**
```python
# ❌ WRONG
try:
    result = operation()
except:  # Catches SystemExit, KeyboardInterrupt!
    return None
```

**Correct:**
```python
# ✅ CORRECT
try:
    result = operation()
except ValueError as e:
    log_error(f"Invalid: {e}")
    return None
```

**Why Bad:**
- Catches SystemExit (Lambda can't shutdown)
- Masks bugs
- Makes debugging impossible

**Related:** ERROR-02, AP-15

---

### 9. DEC-01: SUGA Pattern Choice
**File:** `NM04/NM04-Decisions-Architecture_DEC-01.md`  
**Accesses:** 52  
**Priority:** 🔴 CRITICAL

**Quick Context:**
SUGA pattern prevents circular imports and enables LMMS optimizations.

**Key Benefits:**
1. Prevents circular imports
2. Enables LIGS (60% faster cold start)
3. Centralized control point
4. Clear dependency flow
5. Easy testing

**Structure:**
```
Gateway (gateway.py)
    ↓
Interface (*_interface.py)
    ↓
Implementation (*_core.py)
```

**Related:** ARCH-01, ARCH-07, RULE-01

---

### 10. INT-01: CACHE Interface
**File:** `NM01/NM01-Interfaces-Core_INT-01.md`  
**Accesses:** 50  
**Priority:** 🔴 CRITICAL

**Quick Context:**
In-memory caching with TTL support.

**Key Operations:**
```python
gateway.cache_set(key, value, ttl_seconds=None)
value = gateway.cache_get(key)  # Returns None if miss
gateway.cache_delete(key)
gateway.cache_clear()
exists = gateway.cache_has(key)
```

**Performance:**
- Get: ~0.1ms (in-memory)
- Set: ~0.2ms (in-memory + TTL)
- 535ms saved vs sentinel leak

**Related:** BUG-01, BUG-02, DT-04

---

### 11. ARCH-01: Gateway Trinity
**File:** `NM01/NM01-Architecture-CoreArchitecture_ARCH-01.md`  
**Accesses:** 48  
**Priority:** 🔴 CRITICAL

**Quick Context:**
Three-file structure: gateway.py, gateway_core.py, gateway_wrappers.py

**The Trinity:**
1. **gateway.py** (~150 lines) - Entry point, pure dispatch
2. **gateway_core.py** (~250 lines) - Core router logic
3. **gateway_wrappers.py** (~200 lines) - Convenience functions

**Why Three:**
- Separation of concerns
- Clear responsibilities
- Testing easier
- Cold start optimized

**Related:** ARCH-02, DEC-01, RULE-01

---

### 12. ARCH-07: LMMS System
**File:** `NM01/NM01-Architecture-CoreArchitecture_ARCH-07.md`  
**Accesses:** 46  
**Priority:** 🟡 HIGH

**Quick Context:**
Lambda Memory Management System: LIGS + LUGS + ZAPH

**Components:**
- **LIGS:** Lazy import (60% faster cold start)
- **LUGS:** Lazy unload (82% cost reduction)
- **ZAPH:** Fast path (97% faster hot operations)

**Impact:**
- Cold start: 850ms → 320ms (62% faster)
- Memory: 180MB → 55MB initial (70% less)
- Cost: 82% reduction in GB-seconds

**Related:** DEC-13, DEC-14, LESS-02

---

### 13. LESS-02: Measure, Don't Guess
**File:** `NM06/NM06-Lessons-Performance_LESS-02.md`  
**Accesses:** 44  
**Priority:** 🟡 HIGH

**Quick Context:**
Always measure performance. Assumptions are usually wrong.

**Real Examples:**
- Threading: Thought it'd help → measured 10-15ms overhead
- Imports: Thought cheap → measured 850ms cold start
- Sentinel: Thought OK → measured 535ms penalty

**The Rule:**
1. Measure baseline
2. Make change
3. Measure after
4. Compare results
5. Document findings

**Related:** WISD-02, ARCH-07

---

### 14. DEC-05: Sentinel Sanitization
**File:** `NM04/NM04-Decisions-Architecture_DEC-05.md`  
**Accesses:** 42  
**Priority:** 🔴 CRITICAL

**Quick Context:**
Sanitize sentinels at router layer before returning.

**Pattern:**
```python
# In router (interface_cache.py)
def route_cache_get(key):
    value = cache_core.get(key)
    if value is _CacheMiss:
        return None  # Sanitize
    return value
```

**What to Sanitize:**
- `_CacheMiss` → `None`
- `_Uninitialized` → `None` or default
- Internal error codes → proper exceptions
- Any `_prefixed` objects

**Related:** BUG-01, AP-19

---

### 15. DEC-08: Flat File Structure
**File:** `NM04/NM04-Decisions-Architecture_DEC-08.md`  
**Accesses:** 40  
**Priority:** 🟡 HIGH

**Quick Context:**
No subdirectories except home_assistant/. Simpler is better.

**Why:**
- Simple imports: `import cache_core`
- No path manipulation
- Proven at scale (93 files)
- Lambda deployment simpler
- Less complexity = fewer bugs

**Exception:**
- `home_assistant/` - Large extension codebase

**Related:** AP-05, DEC-03

---

### 16. BUG-02: _CacheMiss Validation
**File:** `NM06/NM06-Bugs-Critical_BUG-02.md`  
**Accesses:** 38  
**Priority:** 🔴 CRITICAL

**Quick Context:**
Sentinel objects have unexpected truthiness, causing crashes.

**The Problem:**
```python
_CacheMiss = object()
bool(_CacheMiss)  # True! (objects are truthy)
if value:  # Doesn't work as expected!
```

**The Fix:**
```python
# Sanitize at router
if value is _CacheMiss:
    return None  # Proper Python convention
```

**Related:** BUG-01, DEC-05, AP-19

---

### 17. PATH-01: Cold Start Sequence
**File:** `NM03/NM03-Operations-Pathways_PATH-01.md`  
**Accesses:** 36  
**Priority:** 🔴 CRITICAL

**Quick Context:**
Optimizing cold start yields biggest performance gains.

**Sequence:**
1. Lambda runtime init (~100ms)
2. Import gateway.py (~50ms, LIGS defers heavy)
3. Import gateway_core.py (~30ms)
4. First operation (~140ms, lazy import interface)
5. **Total:** ~320ms (down from 850ms)

**Deferred (~500ms):**
- Home Assistant client (150ms)
- HTTP libraries (80ms)
- WebSocket (120ms)
- Config from SSM (100ms)

**Related:** ARCH-07, LESS-10, LESS-02

---

### 18. ERROR-02: Graceful Degradation
**File:** `NM03/NM03-Operations-ErrorHandling_ERROR-02.md`  
**Accesses:** 34  
**Priority:** 🔴 CRITICAL

**Quick Context:**
When components fail, degrade gracefully instead of crashing.

**Pattern:**
```python
try:
    data = gateway.cache_get(key)
    if data is None:
        data = fetch_from_source()
        gateway.cache_set(key, data)  # Try to cache
except Exception as e:
    log_warning(f"Cache failed: {e}")
    data = fetch_from_source()  # Fallback
return data  # Success despite failures
```

**Degrade:**
- Metrics, debug, caching, optimizations

**Never Degrade:**
- Security, core logic, data integrity, error logging

**Related:** LESS-05, ERROR-01

---

### 19. DEC-21: SSM Token-Only
**File:** `NM04/NM04-Decisions-Operational_DEC-21.md`  
**Accesses:** 32  
**Priority:** 🟡 HIGH

**Quick Context:**
Store only HA token in SSM. Everything else in env vars.

**Why:**
- SSM has costs per API call
- SSM adds 50ms latency
- Token is only truly sensitive data
- Env vars are free and instant

**Storage:**
- **SSM:** `/lambda-execution-engine/ha_token`
- **Env vars:** URL, SSL settings, log level, debug mode

**Related:** INT-05, DEC-20

---

### 20. AP-27: Skipping Verification Protocol
**File:** `NM05/NM05-AntiPatterns-Process_AP-27.md`  
**Accesses:** 30  
**Priority:** 🔴 CRITICAL

**Quick Context:**
Never skip the 5-step verification (LESS-15).

**Anti-Pattern:**
```
User asks for change
❌ Jump straight to code
❌ Make change without checks
❌ Submit without verification
Result: Bugs, rework
```

**Correct:**
```
User asks for change
✅ Complete 5-step verification
✅ Then make change
Result: Clean code, no rework
```

**Cost:**
- Skip: 30 seconds saved
- Fix bug: 15 minutes lost
- Trade-off: 30x worse

**Related:** LESS-15, AP-28

---

## 📊 TIER 1 STATISTICS

**Total Items:** 20  
**Total Accesses:** 1,046 (last 30 days)  
**Average per Item:** 52.3 accesses  
**Threshold:** 50+ accesses  
**Coverage:** 69% of all ZAPH traffic

**Access Distribution:**
- Top 5: 340 accesses (32%)
- Next 5: 262 accesses (25%)
- Next 5: 238 accesses (23%)
- Bottom 5: 206 accesses (20%)

---

**Navigation:**
- **Hub:** [NM00B-ZAPH.md](NM00B-ZAPH.md)
- **Tier 2:** [NM00B-ZAPH-Tier2.md](NM00B-ZAPH-Tier2.md)
- **Tier 3:** [NM00B-ZAPH-Tier3.md](NM00B-ZAPH-Tier3.md)

---

**End of Tier 1**

**Total Lines:** 299  
**Compliance:** ✅ SIMA v3 (< 300 lines for index)  
**Purpose:** Critical items quick context + pointers

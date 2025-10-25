# Workflow-08-ColdStart.md
**Cold Start Optimization - Step-by-Step**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: Systematic cold start reduction and optimization

---

## 🎯 TRIGGERS

- "Lambda cold start is too slow"
- "First request times out"
- "Initialization takes > 3 seconds"
- "Cold start: [X] seconds"
- "Lambda timeout on cold start"
- "Reduce initialization time"

---

## ⚡ DECISION TREE

```
User reports cold start issue
    ↓
Step 1: Measure Current Cold Start
    → Profile initialization
    → Identify slow imports
    → Get baseline (target < 3s)
    ↓
Step 2: Categorize Imports
    → Hot path (always needed)
    → Cold path (rarely needed)
    → Conditional (situation-dependent)
    ↓
Step 3: Check LMMS System
    → Is LIGS implemented? (Lazy Import Gateway)
    → Is fast_path optimized?
    → Are heavy imports deferred?
    ↓
Step 4: Design Optimization
    → Move cold path to lazy load
    → Optimize hot path order
    → Use fast_path preloading
    ↓
Step 5: Implement Changes
    → Update import locations
    → Configure fast_path
    → Maintain SIMA pattern
    ↓
Step 6: Measure Again
    → Verify < 3s target
    → Document improvements
    → Update LMMS if needed
```

---

## 📋 STEP-BY-STEP PROCESS

### Step 1: Measure Current Cold Start (2-3 minutes)

**Use built-in profiling:**

**File:** performance_benchmark.py

```python
# Profile cold start imports
from performance_benchmark import profile_cold_start

# Run at Lambda initialization
profile_cold_start()
```

**Output example:**
```
=== Cold Start Profile ===
import gateway_core: 45ms
import cache_core: 38ms
import logging_core: 42ms
import config_core: 95ms
import http_client: 320ms ⚠️
import ha_websocket: 850ms ⚠️
import ha_managers: 420ms ⚠️

Total: 1,810ms
Plus Lambda overhead: ~500ms
Total cold start: 2,310ms
```

**Identify problems:**
```
✅ OK: < 100ms per import
⚠️ WARNING: 100-300ms per import
🚨 CRITICAL: > 300ms per import

In example:
- http_client: 320ms ⚠️
- ha_websocket: 850ms 🚨
- ha_managers: 420ms 🚨

Total heavy: 1,590ms (69% of cold start)
```

---

### Step 2: Categorize Imports (1-2 minutes)

**Three categories:**

**Hot Path (Always Needed):**
```python
# Used in every Lambda invocation
gateway_core       # Core routing
cache_core        # Caching layer
logging_core      # Logging layer
config_core       # Configuration

# Keep at module level (fast_path.py)
```

**Cold Path (Rarely Needed):**
```python
# Used only for specific operations
ha_websocket      # Home Assistant only
ha_managers       # Home Assistant only
http_client       # External API calls only
debug_diagnostics # Debug mode only

# Move to lazy loading (function level)
```

**Conditional (Situation Dependent):**
```python
# Depends on request type
security_crypto   # Only for encrypted operations
metrics_helper    # Only if metrics enabled
singleton_*       # Only if singletons used

# Lazy load or conditional import
```

**Decision matrix:**

| Import | Usage | Current Time | Decision |
|--------|-------|--------------|----------|
| gateway_core | 100% | 45ms | Keep hot |
| cache_core | 95% | 38ms | Keep hot |
| ha_websocket | 20% | 850ms | Make lazy |
| http_client | 40% | 320ms | Make lazy |
| debug_* | 5% | varies | Make lazy |

---

### Step 3: Check LMMS System (1 minute)

**File:** NM01/NM01-ARCH-07-LMMS.md

**LMMS Components:**

**LIGS (Lazy Import Gateway System):**
```
Purpose: Defer non-critical imports
Status: Check if implemented
Target: Move 50-70% of imports to lazy
```

**Fast Path:**
```
Purpose: Preload only critical imports
File: fast_path.py
Contents: Hot path dependencies only
```

**LUGS (Lazy Unload Gateway System):**
```
Purpose: Unload unused modules (future)
Status: Not yet implemented
Trigger: When > 300 modules loaded
```

**Quick check:**
```python
# In gateway.py or gateway_wrappers.py
def some_gateway_function():
    # ✅ Lazy import pattern
    import interface_module
    return interface_module.function()

# vs

# ❌ Module-level import
import interface_module
def some_gateway_function():
    return interface_module.function()
```

---

### Step 4: Design Optimization (3-5 minutes)

**Optimization strategies:**

**Strategy 1: Move to Lazy Loading**

**Identify candidates:**
```
Criteria for lazy loading:
- Import cost > 100ms
- Usage < 50% of requests
- Not in critical path
- Can be deferred safely
```

**Example design:**
```python
# BEFORE: Module level (adds to cold start)
import ha_websocket  # 850ms

def homeassistant_command(cmd):
    return ha_websocket.send(cmd)

# AFTER: Function level (lazy)
def homeassistant_command(cmd):
    import ha_websocket  # Only when HA used
    return ha_websocket.send(cmd)

# Impact:
# Cold start: -850ms (faster)
# First HA call: +850ms (acceptable trade-off)
```

**Strategy 2: Optimize Fast Path**

**File:** fast_path.py

```python
# fast_path.py - Preload ONLY hot path

# ✅ Hot path (keep)
import gateway_core
import cache_core
import logging_core

# ❌ Don't preload cold path
# import ha_websocket  # Not needed for every request
# import http_client   # Only for API calls
```

**Strategy 3: Conditional Imports**

```python
# Only import if feature enabled
def debug_operation():
    if DEBUG_MODE:
        import debug_diagnostics  # Only in debug
        return debug_diagnostics.run()
    return None
```

---

### Step 5: Implement Changes (10-15 minutes)

**Step 5a: Update gateway functions**

```python
# gateway_wrappers.py

# ❌ BEFORE: Module-level import
import ha_websocket

def homeassistant_send_command(cmd):
    return ha_websocket.send(cmd)

# ✅ AFTER: Lazy import
def homeassistant_send_command(cmd):
    """
    Send command to Home Assistant.
    
    Note: Uses lazy import to avoid cold start impact.
    First call pays ~850ms import cost.
    """
    import ha_websocket  # Lazy: only when HA used
    return ha_websocket.send(cmd)
```

**Step 5b: Update fast_path.py**

```python
# fast_path.py

# Preload ONLY hot path modules
# These are imported at Lambda initialization

# Core (always needed)
import gateway_core       # 45ms
import cache_core        # 38ms
import logging_core      # 42ms
import config_core       # 95ms

# Total hot path: ~220ms (acceptable)

# NOT imported (cold path - lazy loaded):
# - ha_websocket (850ms) - Only for HA operations
# - http_client (320ms) - Only for API calls
# - debug_* (varies) - Only in debug mode
```

**Step 5b: Verify SIMA pattern**

```
✅ Gateway still routes to interface
✅ Lazy imports at function level
✅ No circular dependencies introduced
✅ Interface/core layers unchanged
```

---

### Step 6: Measure Again (2 minutes)

**Profile after changes:**

```python
from performance_benchmark import profile_cold_start
profile_cold_start()
```

**Expected improvement:**
```
BEFORE:
import gateway_core: 45ms
import cache_core: 38ms
import logging_core: 42ms
import config_core: 95ms
import http_client: 320ms ⚠️
import ha_websocket: 850ms 🚨
import ha_managers: 420ms 🚨
Total: 1,810ms + 500ms = 2,310ms

AFTER:
import gateway_core: 45ms
import cache_core: 38ms
import logging_core: 42ms
import config_core: 95ms
[http_client: deferred]
[ha_websocket: deferred]
[ha_managers: deferred]
Total: 220ms + 500ms = 720ms ✅

Improvement: 1,590ms saved (69% faster)
Target achieved: < 3s ✅
```

**Document the optimization:**

```markdown
## Cold Start Optimization - [Date]

### Before
- Total: 2,310ms
- Target: < 3,000ms
- Status: ⚠️ Close to limit

### Changes
- Moved ha_websocket to lazy load (-850ms)
- Moved http_client to lazy load (-320ms)
- Moved ha_managers to lazy load (-420ms)

### After
- Total: 720ms
- Target: < 3,000ms ✅
- Status: ✅ Well below limit

### Trade-offs
- First HA call: +850ms (acceptable)
- First API call: +320ms (acceptable)
```

---

## 💡 COMPLETE EXAMPLE

**Scenario:** Cold start timeout (3.5s > 3.0s limit)

**Step 1: Measure**
```python
profile_cold_start()

Results:
- Core modules: 220ms
- ha_websocket: 850ms 🚨
- http_client: 320ms ⚠️
- ha_managers: 420ms 🚨
- misc: 90ms
Total: 1,900ms
Lambda overhead: +500ms
Grand total: 2,400ms

But actual timeout happens at 3.5s?
Investigate: CloudWatch shows DNS resolution adding 1.1s
```

**Step 2: Categorize**
```
Hot path (keep):
- gateway_core, cache_core, logging_core, config_core
Total: 220ms

Cold path (defer):
- ha_websocket (used 20% of requests)
- http_client (used 40% of requests)
- ha_managers (used 15% of requests)
Total: 1,590ms
```

**Step 3: Check LMMS**
```python
# Current state: All imports at module level
# LIGS: Not implemented ❌
# Fast path: Loads everything ❌
# Need: Implement lazy loading
```

**Step 4: Design**
```
Strategy:
1. Move HA modules to lazy load
2. Optimize fast_path to core only
3. Conditional HTTP client import

Expected improvement:
- Remove 1,590ms from cold start
- New total: 810ms (66% improvement)
- Well below 3s limit
```

**Step 5: Implement**
```python
# gateway_wrappers.py - Make all HA functions lazy

def homeassistant_send_command(cmd):
    import ha_websocket  # Lazy
    return ha_websocket.send(cmd)

def homeassistant_get_devices():
    import ha_managers  # Lazy
    return ha_managers.get_devices()

def http_get(url, **kwargs):
    import http_client  # Lazy
    return http_client.get(url, **kwargs)

# fast_path.py - Only hot path
import gateway_core
import cache_core
import logging_core
import config_core
# Removed: ha_*, http_client
```

**Step 6: Measure**
```
New profile:
- Core modules: 220ms ✅
- Lambda overhead: 500ms
- Total: 720ms ✅

First HA call: +850ms (one time)
First API call: +320ms (one time)

Target achieved: 720ms < 3,000ms ✅
Improvement: 1,680ms saved (70% faster)
```

---

## 🎯 OPTIMIZATION TARGETS

**Cold start goals:**

| Tier | Target | Status |
|------|--------|--------|
| Critical | < 3,000ms | Lambda timeout limit |
| Good | < 2,000ms | Comfortable margin |
| Excellent | < 1,000ms | Optimal |

**Import cost guidelines:**

| Time | Assessment | Action |
|------|-----------|--------|
| < 50ms | Optimal | Keep in hot path |
| 50-100ms | Good | Consider usage |
| 100-300ms | Warning | Evaluate lazy load |
| > 300ms | Critical | Must lazy load |

---

## ⚠️ COMMON MISTAKES TO AVOID

**DON'T:**
- Lazy load everything (breaks hot path)
- Optimize without measuring first
- Assume all imports are expensive
- Break SIMA pattern
- Forget to test first call latency
- Over-optimize (< 1s is excellent)

**DO:**
- Profile to find actual bottlenecks (LESS-02)
- Keep hot path imports fast
- Lazy load cold path (< 50% usage)
- Measure before and after
- Document trade-offs
- Test both cold and warm starts

---

## 🔗 RELATED FILES

**Hub:** WORKFLOWS-PLAYBOOK.md  
**LMMS System:** NM01/NM01-ARCH-07-LMMS.md  
**Measure First:** NM06/NM06-Lessons-Performance_LESS-02.md  
**Lazy Loading:** NM06/NM06-Lessons-Performance_LESS-17.md  
**Cold Start Path:** NM03/NM03-Operations-Pathways.md  
**Optimization:** Workflow-06-Optimize.md

---

## 📊 SUCCESS METRICS

**Workflow succeeded when:**
- ✅ Cold start < 3,000ms (critical)
- ✅ Cold start < 2,000ms (good)
- ✅ Cold start < 1,000ms (excellent)
- ✅ Hot path imports optimized
- ✅ Cold path lazy loaded
- ✅ Trade-offs documented
- ✅ SIMA pattern maintained

**Time:** 20-30 minutes for cold start optimization

---

**END OF WORKFLOW**

**Lines:** ~295 (properly sized)  
**Priority:** CRITICAL (production requirement)  
**ZAPH:** Tier 1 (essential for Lambda performance)

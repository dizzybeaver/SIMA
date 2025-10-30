# Workflow-06-Optimize.md
**Performance Optimization - Step-by-Step**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: Systematic approach to performance optimization

---

## 🎯 TRIGGERS

- "Lambda is too slow"
- "Cold start takes too long"
- "Can we make [X] faster?"
- "Optimize [feature]"
- "Performance is poor"
- "Reduce latency"

---

## ⚡ DECISION TREE

```
User requests optimization
    ↓
Step 1: Identify Performance Issue
    → Cold start? Hot path? Specific function?
    → What's slow? How slow? Target?
    ↓
Step 2: Measure Current State (LESS-02)
    → Profile the code
    → Get baseline metrics
    → Identify bottleneck
    ↓
Step 3: Search Optimization Patterns
    → Check NM07 Decision Trees
    → Check NM06 Performance Lessons
    → Check existing optimizations
    ↓
Step 4: Design Optimization
    → Lazy loading? Caching? Algorithm?
    → Fast path? Reduce operations?
    ↓
Step 5: Verify No Anti-Patterns
    → Check optimization doesn't break architecture
    → Verify constraints
    ↓
Step 6: Implement Optimization
    → Make targeted changes
    → Maintain SIMA pattern
    ↓
Step 7: Measure Again (LESS-02)
    → Compare before/after
    → Verify improvement
    → Document lesson
```

---

## 📋 STEP-BY-STEP PROCESS

### Step 1: Identify Performance Issue (1 minute)

**Categorize the issue:**

**Cold Start (Initialization):**
- Lambda takes > 3 seconds to initialize
- First request after idle is slow
- Heavy imports causing delay

**Hot Path (Request Processing):**
- Every request is slow
- Specific operations take too long
- Network calls causing latency

**Specific Function:**
- One function is bottleneck
- Algorithm inefficiency
- Unnecessary computation

**Questions to ask:**
```
1. What operation is slow?
2. How slow? (current latency)
3. What's the target? (acceptable latency)
4. Is this cold start or hot path?
5. Does it happen every time or intermittently?
```

---

### Step 2: Measure Current State (LESS-02) (2-3 minutes)

**CRITICAL: Measure don't guess**

**File:** NM06/NM06-Lessons-Performance_LESS-02.md

**Profiling methods:**

**CloudWatch Logs:**
```python
# Add timing logs
import time
start = time.time()
result = expensive_operation()
duration = time.time() - start
gateway.log_info(f"Operation took {duration:.2f}s")
```

**Performance Benchmark:**
```python
# Use built-in benchmark tool
from performance_benchmark import profile_cold_start
profile_cold_start()  # Logs all import times
```

**Lambda Insights (if enabled):**
- Memory usage
- CPU utilization
- Network I/O

**Baseline metrics:**
```
Current state:
- Cold start: X seconds
- Hot path request: Y milliseconds
- Specific operation: Z milliseconds
- Memory usage: M MB

Target state:
- Cold start: < 3 seconds
- Hot path request: < 500ms
- Memory: < 128MB
```

---

### Step 3: Search Optimization Patterns (15 seconds)

**File:** NM07/NM07-DecisionLogic-Optimization_Index.md

**Common optimization patterns:**

| Issue | Pattern | REF-ID |
|-------|---------|--------|
| Cold start > 3s | Lazy imports | ARCH-07, DT-07 |
| Hot path slow | Fast path cache | FW-01 |
| Repeated computation | Memoization | FW-02 |
| Heavy imports | LIGS (lazy import) | ARCH-07 |

**Performance lessons:**

**File:** NM06/NM06-Lessons-Performance_Index.md

| Lesson | Topic | REF-ID |
|--------|-------|--------|
| Measure first | Always profile | LESS-02 |
| Lazy loading | Defer imports | LESS-17 |
| Fast path | Cache hot operations | LESS-20 |
| Memory pressure | LUGS system | LESS-21 |

---

### Step 4: Design Optimization (3-5 minutes)

**Optimization strategies by category:**

**Cold Start Optimization:**

**Lazy Import Gateway System (LIGS):**
```python
# ❌ BEFORE: Import at module level
import heavy_module  # Costs 200ms at cold start

def function():
    return heavy_module.do_work()

# ✅ AFTER: Import at function level
def function():
    import heavy_module  # Only costs when called
    return heavy_module.do_work()
```

**Preload Critical Path:**
```python
# fast_path.py
# Preload ONLY hot path dependencies
import gateway_core  # Always needed
import cache_core    # Hot path
# Don't preload: ha_websocket, http_client, etc.
```

**Hot Path Optimization:**

**Reflex Cache System:**
```python
# For frequently called operations
@gateway.cache_reflex('key', ttl=60)
def expensive_operation(input):
    # Cached for 60 seconds
    return compute_result(input)
```

**Fast Path Bypass:**
```python
# Skip unnecessary processing
if is_cached(key):
    return cache_get(key)  # Fast path
    
# Only do expensive work if needed
result = expensive_computation()
cache_set(key, result)
return result
```

**Algorithm Optimization:**

**Replace O(n²) with O(n):**
```python
# ❌ BEFORE: Nested loops O(n²)
for item in list1:
    for match in list2:
        if item == match:
            process(item)

# ✅ AFTER: Set lookup O(n)
list2_set = set(list2)
for item in list1:
    if item in list2_set:
        process(item)
```

**Reduce Operations:**
```python
# ❌ BEFORE: Multiple calls
for id in ids:
    result = fetch_from_api(id)  # N calls
    process(result)

# ✅ AFTER: Batch operation
results = fetch_batch_from_api(ids)  # 1 call
for result in results:
    process(result)
```

---

### Step 5: Verify No Anti-Patterns (1 minute)

**Optimization-specific checks:**

**File:** AP-Checklist-Critical.md

**Don't optimize into anti-patterns:**
- ✅ No threading (AP-08) - Lambda is single-threaded
- ✅ No premature caching (AP-12) - Measure first
- ✅ No sentinel leaks (AP-19) - Even in cached data
- ✅ SIMA pattern maintained (RULE-01)

**Design decisions:**
- ✅ Memory < 128MB (DEC-07)
- ✅ No external heavy libraries (DEC-07)
- ✅ Lazy imports maintained (ARCH-07)

---

### Step 6: Implement Optimization (5-10 minutes)

**Implementation checklist:**

**1. Make targeted change:**
- Focus on identified bottleneck
- One optimization at a time
- Maintain SIMA pattern

**2. Preserve correctness:**
- Don't sacrifice correctness for speed
- Keep error handling
- Maintain backward compatibility

**3. Add instrumentation:**
```python
# Log optimization impact
gateway.log_info(f"Cache hit - saved {saved_time}ms")
gateway.metrics_track_time('operation', duration)
```

---

### Step 7: Measure Again (LESS-02) (2 minutes)

**Verify improvement:**

**Before/After comparison:**
```
Operation: get_device_list
Before: 450ms
After: 45ms
Improvement: 10x faster ✅

Cold Start:
Before: 3.5s
After: 2.1s
Improvement: 40% faster ✅
```

**Document the optimization:**

**Update neural maps:**
- If significant: Create LESS-## in NM06
- If pattern: Add to NM07 decision trees
- If reusable: Update architecture docs

**Example lesson:**
```markdown
# LESS-XX: Operation X Optimization

Before: 450ms
After: 45ms
Method: Added reflex cache
Impact: 10x improvement
Cost: 2KB memory
```

---

## 💡 COMPLETE EXAMPLES

### Example 1: Cold Start Optimization

**Problem:**
```
Cold start: 4.2 seconds (target < 3s)
Bottleneck: Import overhead
```

**Measurement:**
```python
# Use performance_benchmark.py
from performance_benchmark import profile_cold_start
profile_cold_start()

Results:
- import ha_websocket: 850ms ⚠️
- import http_client: 320ms
- import cache_core: 45ms
- Total: 4.2s
```

**Design:**
```
Strategy: Lazy load ha_websocket (only used in HA calls)
Move ha_websocket imports to function level
Keep hot path imports at module level
```

**Implementation:**
```python
# ❌ BEFORE: gateway.py
import ha_websocket  # 850ms at cold start

def homeassistant_send_command(cmd):
    return ha_websocket.send(cmd)

# ✅ AFTER: gateway.py
def homeassistant_send_command(cmd):
    import ha_websocket  # Only when HA is used
    return ha_websocket.send(cmd)
```

**Measurement After:**
```
Cold start: 2.9s ✅ (target achieved)
Saved: 1.3s (31% improvement)
Trade-off: First HA call slower by 850ms (acceptable)
```

---

### Example 2: Hot Path Optimization

**Problem:**
```
Device list query: 380ms every request
Target: < 100ms
```

**Measurement:**
```python
# Profile the operation
start = time.time()
devices = get_device_list_from_ha()
duration = time.time() - start
# Result: 380ms (network call + parsing)
```

**Design:**
```
Strategy: Cache device list with 60s TTL
Trade-off: Devices updated max every 60s
Acceptable: Device list rarely changes
```

**Implementation:**
```python
# Add reflex cache
@cache_reflex('ha_device_list', ttl=60)
def get_device_list():
    """Cache device list for 60 seconds"""
    devices = fetch_from_homeassistant()
    return parse_devices(devices)

# First call: 380ms (cache miss)
# Subsequent calls: 2ms (cache hit)
```

**Measurement After:**
```
First call: 380ms (cache miss)
Cached calls: 2ms ✅ (190x improvement)
Cache hit rate: 95% (excellent)
Net improvement: ~360ms average
```

---

### Example 3: Algorithm Optimization

**Problem:**
```
Matching devices to entities: 2.3 seconds
Input: 150 devices, 300 entities
```

**Measurement:**
```python
# Profile shows O(n²) nested loop
start = time.time()
for device in devices:  # 150
    for entity in entities:  # 300
        if matches(device, entity):
            map_device_entity(device, entity)
# Total: 45,000 comparisons, 2.3s
```

**Design:**
```
Strategy: Build entity lookup dict
Complexity: O(n²) → O(n)
```

**Implementation:**
```python
# ✅ AFTER: O(n) algorithm
# Build lookup dict once
entity_dict = {e.id: e for e in entities}  # O(n)

# Single pass match
for device in devices:  # O(n)
    entity = entity_dict.get(device.entity_id)
    if entity:
        map_device_entity(device, entity)
```

**Measurement After:**
```
Before: 2.3s (45,000 ops)
After: 0.012s (450 ops)
Improvement: 192x faster ✅
```

---

## ⚠️ COMMON MISTAKES TO AVOID

**DON'T:**
- Optimize without measuring (LESS-02)
- Guess at bottlenecks
- Optimize prematurely
- Break SIMA pattern for speed
- Sacrifice correctness
- Add threading (doesn't help in Lambda)

**DO:**
- Always profile first (LESS-02)
- Measure before and after
- Focus on actual bottleneck
- One optimization at a time
- Maintain architecture
- Document improvements

---

## 🔗 RELATED FILES

**Hub:** WORKFLOWS-PLAYBOOK.md  
**Measure First:** NM06/NM06-Lessons-Performance_LESS-02.md  
**Optimization Trees:** NM07/NM07-DecisionLogic-Optimization_Index.md  
**LMMS System:** NM01/NM01-ARCH-07-LMMS.md  
**Fast Path:** NM07/NM07-DecisionLogic-Optimization_FW-01.md  
**Performance Lessons:** NM06/NM06-Lessons-Performance_Index.md

---

## 📊 SUCCESS METRICS

**Workflow succeeded when:**
- ✅ Bottleneck identified via profiling
- ✅ Baseline metrics documented
- ✅ Optimization implemented correctly
- ✅ Measured improvement verified
- ✅ SIMA pattern preserved
- ✅ Target performance achieved
- ✅ Lesson documented in neural maps

**Time:** 15-30 minutes for typical optimization

---

**END OF WORKFLOW**

**Lines:** ~290 (properly sized)  
**Priority:** HIGH (performance critical)  
**ZAPH:** Tier 2 (important for production)

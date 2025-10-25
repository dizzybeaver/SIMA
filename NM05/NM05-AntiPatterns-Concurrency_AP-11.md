# NM05-AntiPatterns-Concurrency_AP-11.md - AP-11

# AP-11: Synchronous Network Loops

**Category:** Anti-Patterns
**Topic:** Concurrency
**Severity:** 🟢 Medium
**Status:** Active
**Created:** 2024-10-15
**Last Updated:** 2025-10-23

---

## Summary

Making multiple network requests sequentially when they could be batched or parallelized. While Lambda is single-threaded, network I/O can benefit from batching or parallel Lambda invocations for large sets of requests.

---

## The Anti-Pattern

**What's suboptimal:**
```python
# ⚠️ SUBOPTIMAL - Sequential network calls
import gateway

def fetch_multiple_devices(device_ids):
    results = []
    for device_id in device_ids:  # Sequential - slow!
        response = gateway.http_get(f"/api/devices/{device_id}")
        results.append(response)
    return results

# For 10 devices with 100ms latency each:
# Total time: 10 × 100ms = 1000ms (1 second)
```

**Why it's suboptimal:**
1. **Sequential Blocking**: Each request waits for previous to complete
2. **Cumulative Latency**: Total time = n × latency
3. **Poor User Experience**: Slow response times
4. **Inefficient Use of Lambda**: Lambda sits idle during network waits
5. **Scales Poorly**: 100 requests = 10+ seconds

**Important:** This is MEDIUM severity because:
- For 2-3 requests, acceptable
- For 5-10 requests, noticeable but works
- For 20+ requests, significant problem

---

## What to Do Instead

**Option 1: Batch API (Best if available):**
```python
# ✅ BEST - Single batched request
import gateway

def fetch_multiple_devices(device_ids):
    # Many APIs support batching
    response = gateway.http_post(
        "/api/devices/batch",
        data={"device_ids": device_ids}
    )
    return response['devices']

# For 10 devices:
# Total time: 1 × 100ms = 100ms
# 10x faster!
```

**Option 2: Parallel Lambda Invocations (For large sets):**
```python
# ✅ GOOD - Parallel Lambda invocations
import gateway
import json

def fetch_multiple_devices(device_ids):
    # Invoke this Lambda in parallel for each device
    payloads = [
        {"action": "fetch_device", "device_id": device_id}
        for device_id in device_ids
    ]
    
    # Use Lambda invoke with parallel execution
    results = gateway.lambda_invoke_parallel(payloads)
    return results

# For 10 devices:
# Total time: ~100ms (all parallel via Lambda service)
# 10x faster!
```

**Option 3: Accept Sequential (For small sets):**
```python
# ✅ ACCEPTABLE - Sequential for small sets
import gateway

def fetch_multiple_devices(device_ids):
    if len(device_ids) > 5:
        gateway.log_warning(f"Sequential fetch of {len(device_ids)} devices")
    
    results = []
    for device_id in device_ids:
        response = gateway.http_get(f"/api/devices/{device_id}")
        results.append(response)
    return results

# For 2-3 devices: 200-300ms (acceptable)
# For 10+ devices: Consider batching
```

---

## Real-World Example

**Context:** Home Assistant integration fetching multiple entity states

**Original problem:**
```python
# In ha_managers.py
def get_multiple_states(entity_ids):
    states = []
    for entity_id in entity_ids:
        # Sequential API call per entity
        state = gateway.ha_get_state(entity_id)
        states.append(state)
    return states

# User with 20 lights:
# 20 × 50ms = 1000ms response time
# User experience: sluggish
```

**Solution implemented:**
```python
# In ha_managers.py
def get_multiple_states(entity_ids):
    # Home Assistant supports batch state queries
    all_states = gateway.ha_get_all_states()
    
    # Filter to requested entities
    filtered = {
        entity_id: state
        for entity_id, state in all_states.items()
        if entity_id in entity_ids
    }
    return filtered

# User with 20 lights:
# 1 × 150ms = 150ms response time
# 85% faster!
```

**Result:**
- 85% faster for typical use case
- Scales better (constant time)
- One API call instead of n

---

## Decision Matrix

**When is sequential acceptable?**

| Request Count | Latency | Total Time | Verdict |
|---------------|---------|------------|---------|
| 1-2 requests | 50ms | 50-100ms | ✅ Fine |
| 3-5 requests | 50ms | 150-250ms | ⚠️ Consider batching |
| 5-10 requests | 50ms | 250-500ms | ⚠️ Should batch |
| 10+ requests | 50ms | 500ms+ | ❌ Must batch/parallel |

**When to optimize:**
- User-facing requests (response time matters)
- > 5 sequential network calls
- High-latency APIs (>100ms per call)
- Frequently called code paths

**When NOT to optimize:**
- Background jobs (latency less critical)
- 1-3 sequential calls
- Low-latency APIs (<20ms per call)
- Rarely called code paths

---

## Batching Strategies

**Strategy 1: API supports batching**
```python
# Best case - native batch support
gateway.api_batch_get(["id1", "id2", "id3"])
```

**Strategy 2: Multiple invocations**
```python
# Use Lambda concurrency model
gateway.lambda_invoke_async_batch(payloads)
```

**Strategy 3: Accept sequential**
```python
# Sometimes this is OK
for item in small_set:  # <5 items
    process(item)
```

---

## How to Identify

**Code smells:**
- Network requests inside loops
- Multiple calls to same API with different parameters
- High Lambda execution times (>1000ms)
- Logs showing many sequential HTTP calls
- User complaints about slowness

**Detection:**
```python
# Look for patterns like:
for x in items:
    gateway.http_get(...)

# Or:
for x in items:
    gateway.api_call(...)
```

**Performance profiling:**
```python
# Measure time per request
import time
start = time.time()
fetch_multiple_devices(device_ids)
duration = time.time() - start
gateway.log_info(f"Fetched {len(device_ids)} in {duration}ms")
```

---

## Important Distinction

**This is NOT about threading:**
- AP-08 (Threading): Adding locks/threads ❌
- AP-11 (Blocking I/O): Sequential network calls ⚠️

**Lambda being single-threaded doesn't mean:**
- You can't optimize I/O
- Batching doesn't help
- All sequential operations are equal

**It means:**
- Can't use multiple threads IN one Lambda
- CAN use multiple Lambda invocations
- CAN use batched APIs
- CAN reduce sequential operations

---

## Related Topics

- **AP-08**: Threading primitives (different issue)
- **LESS-02**: Measure don't guess (profile before optimizing)
- **PATH-01**: Cold start pathway (every ms counts)
- **DEC-04**: No threading locks (Lambda execution model)
- **INT-08**: COMMUNICATION interface (network operations)

---

## Keywords

network requests, sequential operations, blocking I/O, batching, performance optimization, API calls

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2024-10-15**: Anti-pattern documented in NM05-Anti-Patterns_Part_1.md

---

**File:** `NM05-AntiPatterns-Concurrency_AP-11.md`
**End of Document**

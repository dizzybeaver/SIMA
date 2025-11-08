# AWS-Lambda-Memory-Management.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** AWS Lambda memory management strategies and optimization  
**Category:** Platform - AWS Lambda Core

---

## Overview

AWS Lambda memory configuration directly impacts:
- **Execution performance** (CPU scales with memory)
- **Cost per invocation** (higher memory = higher cost)
- **Cold start duration** (more memory = faster cold starts)
- **Available resources** (memory limit affects all operations)

**Key Insight:** Memory and CPU are coupled in Lambda - you cannot configure them separately.

---

## Memory Configuration

### Available Memory Sizes

**Range:** 128MB to 10,240MB (10GB)  
**Increment:** 1MB increments  
**Default:** 128MB (if not specified)

**Common Configurations:**
- **128MB:** Minimum, cost-optimized for simple functions
- **256MB:** Light processing, API proxies
- **512MB:** Moderate processing, small data sets
- **1024MB:** Standard processing, medium workloads
- **1536MB:** Heavy processing, large data operations
- **3008MB:** Full vCPU allocation
- **10240MB:** Maximum for data-intensive operations

### CPU Allocation

**CPU scales with memory:**
- 128MB-1769MB: Fraction of vCPU
- 1769MB-3008MB: 1 full vCPU
- 3009MB-5307MB: 2 full vCPUs
- 5308MB+: Additional vCPUs proportionally

**Performance Impact:**
```
Memory    CPU Power    Cold Start    Cost Multiple
128MB     0.07 vCPU    ~3-5s        1.0x
256MB     0.14 vCPU    ~2-3s        2.0x
512MB     0.28 vCPU    ~1.5-2s      4.0x
1024MB    0.57 vCPU    ~1-1.5s      8.0x
1536MB    0.86 vCPU    ~0.8-1.2s    12.0x
3008MB    1.77 vCPU    ~0.5-0.8s    23.4x
```

---

## Memory Optimization Strategies

### Strategy 1: Right-Size Based on Profiling

**Process:**
1. Start with minimum (128MB)
2. Run load tests with CloudWatch metrics
3. Monitor memory usage and duration
4. Increase until duration no longer decreases
5. Balance cost vs performance

**Metrics to Monitor:**
- `MemoryUsed`: Actual memory consumed
- `MaxMemoryUsed`: Peak memory during execution
- `Duration`: Total execution time
- `Cost`: Per-invocation cost

**Example Analysis:**
```
Test 1: 128MB - Duration: 2500ms, Memory: 110MB, Cost: $0.0000025
Test 2: 256MB - Duration: 1300ms, Memory: 110MB, Cost: $0.0000026
Test 3: 512MB - Duration: 1280ms, Memory: 110MB, Cost: $0.0000051

Result: 256MB optimal (50% faster, minimal cost increase)
```

### Strategy 2: Memory Headroom

**Rule:** Allocate 20-30% more than peak usage

**Why:**
- Prevents out-of-memory errors
- Allows for traffic spikes
- Accounts for variability in requests
- Provides buffer for dependencies

**Example:**
```
Peak Memory Usage: 180MB
Recommended: 230-234MB (rounds to 256MB)
```

### Strategy 3: Cost vs Performance Trade-off

**Calculate value equation:**
```
Value = (Duration Improvement) / (Cost Increase)

Example:
128MB: 3000ms @ $0.0000025 = 1.2 million ms/$1
256MB: 1500ms @ $0.0000050 = 0.3 million ms/$1
512MB: 1400ms @ $0.0000100 = 0.14 million ms/$1

Best value: 256MB (50% faster, 2x cost = positive ROI)
```

---

## Common Memory Issues

### Issue 1: Out of Memory Errors

**Symptom:** "Process exited before completing request"  
**Cause:** Peak memory usage exceeds allocated memory

**Solutions:**
- Increase memory allocation
- Reduce payload sizes
- Stream large data instead of loading entirely
- Clear caches/buffers after use
- Avoid accumulating data in memory

**Example Fix:**
```python
# [X] WRONG: Load entire file
data = large_file.read()  # May exceed memory
process(data)

# [OK] CORRECT: Stream processing
for chunk in large_file.read_chunks():
    process(chunk)
    del chunk  # Free memory immediately
```

### Issue 2: Memory Leaks

**Symptom:** Memory usage grows across invocations  
**Cause:** Global variables accumulating data between invocations

**Solutions:**
- Clear global caches periodically
- Set TTL on cached data
- Monitor container reuse metrics
- Implement cache size limits

**Example Fix:**
```python
# [X] WRONG: Unbounded cache
CACHE = {}  # Grows indefinitely

# [OK] CORRECT: Bounded cache with LRU
from collections import OrderedDict

CACHE = OrderedDict()
MAX_CACHE_SIZE = 100

def cache_get(key):
    if len(CACHE) > MAX_CACHE_SIZE:
        CACHE.popitem(last=False)  # Remove oldest
    return CACHE.get(key)
```

### Issue 3: Inefficient Memory Usage

**Symptom:** High memory allocation but low utilization  
**Cause:** Loaded dependencies not actually used

**Solutions:**
- Lazy load dependencies
- Use conditional imports
- Minimize import scope
- Profile actual memory usage

**Example Fix:**
```python
# [X] WRONG: Import heavy unused library
import pandas as pd  # 50MB+ even if not used

# [OK] CORRECT: Conditional import
def process_dataframe(data):
    import pandas as pd  # Only load if function called
    return pd.DataFrame(data)
```

---

## Memory Management Best Practices

### Practice 1: Monitor and Adjust

**Continuous optimization:**
- Enable CloudWatch detailed monitoring
- Track MemoryUsed vs MaxMemoryUsed
- Analyze 95th percentile usage
- Adjust based on actual patterns

**Alert on:**
- Memory usage >90% of allocated
- Increasing memory trends
- Out of memory errors
- Performance degradation

### Practice 2: Efficient Data Structures

**Choose appropriate structures:**
- Lists for sequential access
- Sets for membership testing
- Dicts for key-value lookups
- Generators for large sequences

**Example:**
```python
# [X] WRONG: Store all in memory
results = [process(item) for item in million_items]

# [OK] CORRECT: Generator
results = (process(item) for item in million_items)
```

### Practice 3: Cleanup After Processing

**Explicit cleanup:**
- Delete large variables after use
- Clear caches before return
- Close file handles
- Release connections

**Example:**
```python
def handler(event, context):
    large_data = load_data()
    result = process(large_data)
    
    # ADDED: Explicit cleanup
    del large_data
    
    return result
```

### Practice 4: Memory-Efficient Libraries

**Prefer lightweight alternatives:**
- `ujson` instead of `json` (faster, less memory)
- `httpx` instead of `requests` (lighter)
- Built-in modules when possible
- Avoid heavy frameworks

---

## LEE Project Memory Configuration

**LEE Configuration:**
- **Allocated:** 128MB (free tier)
- **Typical Usage:** 85-95MB
- **Peak Usage:** 110MB
- **Headroom:** 18MB (15%)

**Rationale:**
- Home Assistant API responses small (<10KB)
- WebSocket overhead minimal
- Cache size controlled (<5MB)
- No large data processing
- Cost optimized for high invocation count

**Monitoring:**
- Alert if usage >115MB
- Track cold start impact
- Monitor cache size growth
- Adjust if traffic patterns change

---

## Related Documentation

**Core Concepts:**
- AWS-Lambda-Core-Concepts.md
- AWS-Lambda-Runtime-Environment.md
- AWS-Lambda-Execution-Model.md

**Decisions:**
- AWS-Lambda-DEC-02-Memory-Constraints.md
- LEE-DEC-03-Memory-Allocation.md

**Lessons:**
- AWS-Lambda-LESS-01-Cold-Start-Impact.md
- LMMS-LESS-02-Measure-Impact-Always.md

**Anti-Patterns:**
- AWS-Lambda-AP-02-Stateful-Operations.md
- LMMS-AP-02-Over-Lazy-Loading.md

---

## Key Takeaways

**Memory = Performance:**
CPU scales with memory, so memory affects execution speed

**Right-Size for Value:**
Balance cost and performance, not just minimizing cost

**Monitor Continuously:**
Actual usage patterns determine optimal configuration

**Plan for Growth:**
Leave headroom for spikes and variability

**Clean Up Resources:**
Explicit cleanup prevents memory leaks

---

**REF:** AWS-Lambda-Core-04  
**Keywords:** memory management, memory optimization, lambda configuration, resource allocation, performance tuning  
**Related Topics:** Cold start optimization, cost optimization, performance profiling, resource limits, memory leaks

---

**END OF FILE**

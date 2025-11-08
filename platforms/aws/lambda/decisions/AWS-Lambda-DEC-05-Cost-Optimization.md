# AWS-Lambda-DEC-05-Cost-Optimization.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Cost optimization strategy for AWS Lambda  
**Category:** Platform - AWS Lambda - Decision

---

## DECISION

**DEC-05: Optimize for cost-performance balance**

**Status:** Adopted  
**Date Decided:** 2024-03-15  
**Decision Makers:** Platform Architecture Team

---

## CONTEXT

AWS Lambda pricing is based on:
1. **Number of invocations** - $0.20 per 1 million requests
2. **Duration** - Charged per GB-second
   - Memory allocation × execution time
   - Rounded up to nearest millisecond
3. **Provisioned concurrency** (optional) - Additional hourly charge

**Example Costs:**
- 128 MB, 100ms execution: $0.0000002083 per invocation
- 1024 MB, 100ms execution: $0.0000016667 per invocation (8x cost)
- 1024 MB, 50ms execution: $0.0000008333 per invocation (4x cost)

**Key Insight:** Higher memory can reduce duration, potentially lowering total cost.

---

## DECISION

We optimize Lambda functions for **cost-performance balance** rather than minimizing one dimension.

### Core Principles

**1. Right-Size Memory**
- Start with profiling actual memory usage
- Test multiple memory configurations
- Find sweet spot where cost/invocation is minimized
- Often optimal at 1769 MB (1 full vCPU)

**2. Minimize Duration**
- Optimize code efficiency
- Use async I/O for parallel operations
- Implement caching
- Remove unnecessary processing

**3. Monitor Cost Metrics**
- Track cost per invocation
- Monitor cost trends over time
- Set budget alerts
- Review cost anomalies

**4. Balance Trade-offs**
- User experience vs. cost
- Cold start frequency vs. provisioned concurrency cost
- Memory allocation vs. execution speed

---

## RATIONALE

### Why Cost-Performance Balance?

**Pure cost minimization fails:**
- 128 MB memory = cheapest rate but slowest execution
- May result in higher total cost due to longer duration
- Poor user experience
- Higher timeout risk

**Pure performance maximization fails:**
- 10 GB memory = fastest but extremely expensive
- Wasteful over-provisioning
- Unsustainable at scale

**Balanced approach wins:**
- Find memory level where duration decreases diminish
- Optimize code for efficiency
- Monitor and adjust based on actual usage
- Consider user experience requirements

### Real-World Example

**Unoptimized function:**
```
Memory:     512 MB
Duration:   2,000ms
Cost/invocation: $0.0000020833
Monthly cost (10M invocations): $20.83
```

**Optimized function:**
```
Memory:     1769 MB (full vCPU)
Duration:   600ms
Cost/invocation: $0.0000017672
Monthly cost (10M invocations): $17.67

Savings: $3.16/month (15% reduction)
Benefit: 70% faster execution
```

**Further optimization with code improvements:**
```
Memory:     1769 MB
Duration:   400ms
Cost/invocation: $0.0000011781
Monthly cost (10M invocations): $11.78

Total savings: $9.05/month (43% reduction)
Benefit: 80% faster execution
```

---

## ALTERNATIVES CONSIDERED

### Alternative 1: Minimize Memory Only
**Approach:** Always use lowest memory (128 MB)  
**Pros:** Lowest per-GB-second rate  
**Cons:** 
- Longer execution times
- May increase total cost
- Poor performance
- Higher timeout risk

**Rejected because:** Total cost often higher due to longer duration.

### Alternative 2: Maximize Memory for All Functions
**Approach:** Use 3008 MB or higher for everything  
**Pros:** Fastest execution  
**Cons:**
- Extremely expensive
- Wasteful over-provisioning
- Unsustainable at scale

**Rejected because:** Cost unsustainable, diminishing returns.

### Alternative 3: Fixed Memory Tier
**Approach:** Use same memory (e.g., 1024 MB) for all functions  
**Pros:** Simple, consistent  
**Cons:**
- One size doesn't fit all
- Some functions over-provisioned
- Some functions under-provisioned

**Rejected because:** Different workloads need different resources.

### Alternative 4: Cost-Performance Balance (CHOSEN)
**Approach:** Profile each function, find optimal memory  
**Pros:**
- Minimizes cost for actual workload
- Maintains good performance
- Sustainable at scale
- Data-driven

**Chosen because:** Balances all concerns effectively.

---

## IMPLEMENTATION

### Step 1: Baseline Measurement

**Measure current state:**
```python
import time

def lambda_handler(event, context):
    start = time.time()
    
    # Your function logic
    result = process_data(event)
    
    duration = time.time() - start
    
    # Log metrics
    print(f"Duration: {duration*1000:.2f}ms")
    print(f"Memory used: {context.memory_limit_in_mb}MB")
    print(f"Max memory: See CloudWatch")
    
    return result
```

**CloudWatch metrics:**
- Duration (ms)
- Max memory used (MB)
- Billed duration (ms)

### Step 2: Memory Testing

**Test memory configurations:**
```bash
# Test configurations
MEMORY_CONFIGS=(128 256 512 1024 1536 1769 2048 3008)

for memory in "${MEMORY_CONFIGS[@]}"; do
    aws lambda update-function-configuration \
        --function-name MyFunction \
        --memory-size $memory
    
    # Wait for update
    aws lambda wait function-updated \
        --function-name MyFunction
    
    # Run load test
    # Record: memory, duration, cost
done
```

**Analyze results:**
1. Plot memory vs. duration
2. Calculate cost per invocation
3. Find inflection point (diminishing returns)
4. Consider user experience requirements

### Step 3: Code Optimization

**Optimize for efficiency:**

```python
# BEFORE: Synchronous I/O
def get_data():
    data1 = fetch_from_api1()  # 200ms
    data2 = fetch_from_api2()  # 200ms
    data3 = fetch_from_api3()  # 200ms
    return combine(data1, data2, data3)
# Total: 600ms

# AFTER: Async I/O
import asyncio

async def get_data():
    tasks = [
        fetch_from_api1_async(),
        fetch_from_api2_async(),
        fetch_from_api3_async()
    ]
    results = await asyncio.gather(*tasks)
    return combine(*results)
# Total: 200ms (3x faster)
```

**Impact:** 3x faster execution = ~66% cost reduction

### Step 4: Caching Strategy

**Implement appropriate caching:**

```python
# Global scope for warm start reuse
cache = {}
CACHE_TTL = 300  # 5 minutes

def lambda_handler(event, context):
    key = get_cache_key(event)
    
    # Check cache
    if key in cache:
        cached_item = cache[key]
        if cached_item['expires'] > time.time():
            return cached_item['data']
    
    # Fetch and cache
    data = fetch_data(event)
    cache[key] = {
        'data': data,
        'expires': time.time() + CACHE_TTL
    }
    
    return data
```

**Impact:** Avoids repeated expensive operations

### Step 5: Monitoring and Alerting

**Set up cost monitoring:**

```python
# CloudWatch custom metric
import boto3

cloudwatch = boto3.client('cloudwatch')

def publish_cost_metric(function_name, memory_mb, duration_ms):
    # Calculate cost
    gb_seconds = (memory_mb / 1024) * (duration_ms / 1000)
    cost = gb_seconds * 0.0000166667  # $0.20 per 1M requests
    cost += 0.0000002  # Per-request cost
    
    cloudwatch.put_metric_data(
        Namespace='Lambda/Cost',
        MetricData=[
            {
                'MetricName': 'CostPerInvocation',
                'Value': cost * 1000000,  # Cost per 1M
                'Unit': 'None',
                'Dimensions': [
                    {'Name': 'FunctionName', 'Value': function_name}
                ]
            }
        ]
    )
```

**Alarm on anomalies:**
```bash
aws cloudwatch put-metric-alarm \
    --alarm-name high-lambda-cost \
    --comparison-operator GreaterThanThreshold \
    --evaluation-periods 2 \
    --metric-name CostPerInvocation \
    --namespace Lambda/Cost \
    --period 3600 \
    --statistic Average \
    --threshold 15.0 \
    --alarm-description "Lambda cost per million > $15"
```

---

## OPTIMIZATION STRATEGIES

### Strategy 1: Right-Size Memory

**Process:**
1. Profile actual memory usage
2. Add 20-50% headroom
3. Test performance at different levels
4. Find optimal memory configuration
5. Document decision

**Common Sweet Spots:**
- **1769 MB:** Full vCPU (1.0 vCPU), often optimal for CPU-bound
- **1024 MB:** Good balance for many workloads
- **512 MB:** Lightweight functions
- **128 MB:** Simple proxy/routing functions

### Strategy 2: Reduce Cold Starts

**Impact on cost:**
- Cold start: 500-5000ms overhead
- Provisioned concurrency: $0.015 per GB-hour (expensive!)

**Decision framework:**
```
IF cold_start_frequency > 10% AND cold_start_duration > 2s:
    CONSIDER provisioned concurrency
    CALCULATE break-even point
    
ELSE IF cold_start_frequency > 5% AND user_facing:
    OPTIMIZE cold start (lazy loading)
    
ELSE:
    ACCEPT cold starts (rare, not critical)
```

**Provisioned concurrency break-even:**
```
Cost with cold starts:
- 1M invocations/day
- 10% cold starts = 100K cold starts
- Cold start penalty: 3s extra × 1769 MB = 5,307 GB-seconds
- Cost: 5,307 × $0.0000166667 = $0.0885/day = $2.66/month

Cost with provisioned concurrency:
- 1 instance, 1769 MB = 1.769 GB
- 24 hours × 30 days = 720 hours
- Cost: 720 × 1.769 × $0.015 = $19.11/month

Verdict: NOT cost-effective (7x more expensive)
```

### Strategy 3: Batch Operations

**Reduce invocation count:**

```python
# BEFORE: Process one item per invocation
def process_item(item):
    # Process single item
    return result

# 1,000,000 items = 1,000,000 invocations
# Cost: 1M × $0.0000002 = $0.20 (requests)
# Plus duration costs

# AFTER: Process batch per invocation
def process_batch(items):
    results = []
    for item in items:
        results.append(process_item(item))
    return results

# 1,000,000 items ÷ 100 per batch = 10,000 invocations
# Cost: 10K × $0.0000002 = $0.002 (requests) - 99% reduction!
# Duration may increase, but total cost usually lower
```

### Strategy 4: Optimize Dependencies

**Reduce deployment package size:**

```bash
# BEFORE: Full packages
pip install boto3 botocore numpy pandas
# Size: 150 MB
# Cold start: 3-5 seconds

# AFTER: Minimal dependencies
# boto3/botocore included in runtime (don't package)
# Use native Python where possible
pip install --no-deps package-name
# Size: 15 MB
# Cold start: 500ms-1s
```

**Impact:** 80% faster cold start, lower cold start cost

---

## COST MONITORING

### Key Metrics

**1. Cost Per Invocation**
```
Cost/Invocation = (Duration × Memory / 1024 / 1000) × $0.0000166667 + $0.0000002
```

**Track by:**
- Function name
- Memory configuration
- Time period (daily, weekly, monthly)
- Environment (dev, staging, prod)

**2. Cost Trends**
- Daily cost
- Weekly cost
- Monthly cost
- Year-over-year comparison

**3. Cost Anomalies**
- Sudden cost spikes
- Gradual cost increases
- Cost per invocation increases
- Invocation count increases

### Optimization Targets

**Cost per million invocations:**
- **Excellent:** < $5
- **Good:** $5-$10
- **Acceptable:** $10-$20
- **Needs optimization:** > $20

**Monthly cost (for busy functions):**
- **Low volume:** < $10/month
- **Medium volume:** $10-$100/month
- **High volume:** $100-$1,000/month
- **Very high volume:** > $1,000/month (consider alternatives)

---

## SUCCESS METRICS

### Optimization Goals

**Target improvements:**
- 30-50% cost reduction from baseline
- Maintain or improve performance (duration)
- Improve user experience (lower latency)
- Reduce cold start impact

### Measurement

**Before optimization:**
- Baseline cost per invocation
- Baseline duration
- Baseline memory usage

**After optimization:**
- New cost per invocation
- New duration
- New memory configuration
- Cost savings ($ and %)
- Performance improvement (% faster)

**Example results:**
```
Baseline:
- Memory: 512 MB
- Duration: 1,500ms
- Cost/Million: $12.50

Optimized:
- Memory: 1769 MB
- Duration: 450ms
- Cost/Million: $13.27

Wait, that's MORE expensive!

Further optimized (code improvements):
- Memory: 1769 MB
- Duration: 300ms
- Cost/Million: $8.85

Final result: 29% cost reduction, 80% faster
```

---

## RELATED

**Decisions:**
- DEC-02: Memory Constraints
- DEC-03: Timeout Limits

**Lessons:**
- LESS-02: Memory-Performance Trade-off
- LESS-05: Cost Monitoring
- LESS-10: Performance Tuning

**Anti-Patterns:**
- AP-05: Over-Provisioning Memory

---

## EXAMPLES

### Example 1: Simple API Function

**Workload:** API Gateway proxy

**Optimization:**
```
Baseline (128 MB): 250ms, $0.52/million
Test 256 MB: 180ms, $0.60/million
Test 512 MB: 150ms, $1.25/million

Chosen: 256 MB
Reason: 28% faster, only 15% more expensive
```

### Example 2: Data Processing Function

**Workload:** S3 event processing

**Optimization:**
```
Baseline (1024 MB): 3,000ms, $50.00/million
Test 1769 MB: 1,200ms, $35.40/million
Test 2048 MB: 1,000ms, $34.13/million
Test 3008 MB: 900ms, $45.00/million

Chosen: 2048 MB
Reason: Best cost/performance balance
```

### Example 3: ML Inference Function

**Workload:** ML model inference

**Optimization:**
```
Baseline (2048 MB): 5,000ms, $170.83/million
Test 3008 MB: 2,500ms, $125.00/million
Test 10240 MB: 1,000ms, $170.67/million

Chosen: 3008 MB
Reason: 50% faster, 27% cheaper
Note: 10240 MB no better cost, hit CPU limit
```

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial decision documentation
- Cost optimization strategy
- Implementation steps
- Monitoring approach
- Real-world examples
- Optimization strategies

---

**END OF FILE**

**Key Takeaway:** Optimize for cost-performance balance, not minimum cost.  
**Target:** 30-50% cost reduction while maintaining or improving performance.  
**Method:** Profile, test, optimize code, monitor continuously.

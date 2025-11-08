# AWS-Lambda-LESS-05-Cost-Monitoring.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Cost monitoring and optimization lessons for AWS Lambda  
**Category:** AWS Lambda Platform Lessons

---

## LESSON SUMMARY

**Core Insight:** Lambda costs = (invocations × duration × memory) / 1000. Small inefficiencies compound quickly at scale. Understanding cost drivers enables significant savings without sacrificing performance.

**Context:** Lambda charges for GB-seconds of compute plus per-request. A function using 512MB for 2s costs more than 128MB for 1s, even though both complete successfully. Memory and execution time directly impact bills.

**Impact:**
- Right-sizing memory: 40-70% cost reduction
- Duration optimization: 30-50% cost reduction
- Cold start reduction: 10-20% cost reduction
- Dead letter queues: Prevent runaway costs from failures

---

## LAMBDA COST MODEL

### Pricing Components

**1. Request Charges:**
- $0.20 per 1M requests
- First 1M requests/month free
- Minimal cost component for most use cases

**2. Duration Charges (Primary Cost Driver):**
- $0.0000166667 per GB-second
- Rounded up to nearest 1ms
- Memory and time both matter

**Cost Formula:**
```
Monthly Cost = 
  (Invocations × Duration_ms × Memory_MB / 1024 / 1000) × $0.0000166667
  + (Invocations / 1,000,000) × $0.20
```

### Cost Examples

**Example 1: Low Memory, Short Duration (Efficient)**
```
Function: 128MB, 100ms average
Invocations: 1M/month

Compute: 1M × 0.1s × 0.125GB = 12,500 GB-seconds
Cost: 12,500 × $0.0000166667 = $0.21
Requests: 1M × $0.20/1M = $0.20
Total: $0.41/month
```

**Example 2: High Memory, Long Duration (Expensive)**
```
Function: 1024MB, 2s average
Invocations: 1M/month

Compute: 1M × 2s × 1GB = 2,000,000 GB-seconds
Cost: 2,000,000 × $0.0000166667 = $33.33
Requests: 1M × $0.20/1M = $0.20
Total: $33.53/month
```

**Impact:** 8x memory × 20x duration = 160x cost difference

---

## COST OPTIMIZATION STRATEGIES

### Strategy 1: Right-Size Memory

**Problem:** Default memory (1024MB) used for all functions

**Analysis Process:**
```python
# Test function at different memory levels
import boto3
import time

def test_memory_configurations(function_name):
    """Test function at various memory levels."""
    
    lambda_client = boto3.client('lambda')
    memory_levels = [128, 256, 512, 1024, 1536, 2048]
    results = []
    
    for memory in memory_levels:
        # Update function memory
        lambda_client.update_function_configuration(
            FunctionName=function_name,
            MemorySize=memory
        )
        
        # Wait for update
        time.sleep(10)
        
        # Test invocations
        durations = []
        for _ in range(10):
            response = lambda_client.invoke(
                FunctionName=function_name,
                Payload=json.dumps({'test': True})
            )
            
            # Parse billed duration
            log_result = response['LogResult']
            duration = parse_duration(log_result)
            durations.append(duration)
        
        avg_duration = sum(durations) / len(durations)
        gb_seconds = (memory / 1024) * (avg_duration / 1000)
        
        results.append({
            'memory': memory,
            'avg_duration_ms': avg_duration,
            'gb_seconds': gb_seconds,
            'cost_per_1m': gb_seconds * 1000000 * 0.0000166667
        })
    
    return results

# Results show optimal configuration
# Memory: 512MB, Duration: 200ms
# Cost: $1.70 per 1M invocations
# vs 1024MB: $3.40 per 1M (2x more expensive)
```

**Finding:** 512MB was sweet spot for most functions (50% cost savings)

---

### Strategy 2: Reduce Execution Duration

**Problem:** Function took 2s per invocation

**Optimization Steps:**

**Step 1: Profile Execution**
```python
import time

def lambda_handler(event, context):
    timings = {}
    
    # Database query
    start = time.time()
    data = query_database()
    timings['database'] = time.time() - start  # 1200ms
    
    # API call
    start = time.time()
    api_data = call_external_api()
    timings['api'] = time.time() - start  # 500ms
    
    # Processing
    start = time.time()
    result = process_data(data, api_data)
    timings['processing'] = time.time() - start  # 300ms
    
    print(f"Timings: {timings}")
    return result
```

**Step 2: Optimize Slowest Part (Database)**
```python
# Before: 1200ms - Querying 10,000 rows
data = db.query("SELECT * FROM large_table WHERE user_id = ?", user_id)

# After: 200ms - Added index, limited columns
data = db.query(
    "SELECT id, name, status FROM large_table WHERE user_id = ?",
    user_id
)
# Index on user_id reduced query time 6x
```

**Step 3: Parallelize Independent Operations**
```python
import concurrent.futures

def lambda_handler(event, context):
    """Run independent operations in parallel."""
    
    with concurrent.futures.ThreadPoolExecutor(max_workers=2) as executor:
        # Start both operations simultaneously
        db_future = executor.submit(query_database)
        api_future = executor.submit(call_external_api)
        
        # Wait for both
        data = db_future.result()
        api_data = api_future.result()
    
    # Process results
    result = process_data(data, api_data)
    return result

# Before: 1200ms + 500ms = 1700ms sequential
# After: max(1200ms, 500ms) = 1200ms parallel
# Savings: 500ms per invocation
```

**Result:**
- Duration reduced: 2000ms → 1200ms (40% reduction)
- Cost savings: $33/month → $20/month per million invocations

---

### Strategy 3: Reduce Cold Starts

**Problem:** 20% of invocations were cold starts (1.5s overhead)

**Solutions Implemented:**

**A. Provisioned Concurrency (For Critical Functions)**
```bash
# Keep 2 instances always warm
aws lambda put-provisioned-concurrency-config \
  --function-name critical-function \
  --provisioned-concurrent-executions 2 \
  --qualifier production
```

**Cost Impact:**
- Added cost: $0.015 per hour per instance × 2 = $21.60/month
- Saved cold starts: 200K per month × 1.5s = 300K seconds
- Net savings: Variable (depends on traffic pattern)

**B. Keep-Warm Scheduling (For Moderate Traffic)**
```python
# CloudWatch Events rule: Rate(5 minutes)
def lambda_handler(event, context):
    """Warm function every 5 minutes."""
    
    if event.get('source') == 'keep-warm':
        return {'statusCode': 200, 'warm': True}
    
    # Normal processing
    return process_request(event)
```

**Cost Impact:**
- Keep-warm invocations: 12 per hour × 730 hours = 8,760/month
- Cost: 8,760 × 0.1s × 0.128GB × $0.0000166667 = $0.002/month
- Cold starts prevented: ~50K/month worth ~$1.25 savings

**C. Code Optimization**
```python
# Before: Import heavy library at module level
import pandas as pd  # 800ms cold start overhead
import numpy as np   # 300ms cold start overhead

def lambda_handler(event, context):
    # Process with pandas
    df = pd.DataFrame(data)
    return df.to_dict()

# After: Use lightweight alternatives
import json  # 10ms cold start overhead

def lambda_handler(event, context):
    # Process with standard library
    return [dict(zip(headers, row)) for row in data]

# Cold start reduced: 1100ms → 100ms (10x improvement)
```

---

### Strategy 4: Handle Failures Efficiently

**Problem:** Failed invocations retried 3 times (4x cost for failures)

**Solution 1: Dead Letter Queue**
```python
# Send failed events to DLQ instead of retrying
# Configure in Lambda:
# - Retry attempts: 0 (fail immediately)
# - DLQ: SQS queue for manual review

def lambda_handler(event, context):
    try:
        return process_event(event)
    except ValidationError as e:
        # Don't retry validation errors
        raise  # Goes to DLQ
    except TransientError as e:
        # Do retry transient errors
        raise
```

**Cost Impact:**
- Before: 100K failures × 4 retries × 2s = 800K seconds wasted
- After: 100K failures × 1 attempt × 2s = 200K seconds
- Savings: 600K seconds = 75% reduction on failure costs

**Solution 2: Early Validation**
```python
def lambda_handler(event, context):
    """Validate input before expensive processing."""
    
    # Validate in first 10ms
    if not event.get('required_field'):
        return {
            'statusCode': 400,
            'error': 'Missing required_field'
        }
    
    # Only process valid requests (expensive operation)
    return expensive_processing(event)

# Before: 50K invalid requests × 2s = 100K seconds
# After: 50K invalid requests × 0.01s = 500 seconds
# Savings: 99.5% on invalid requests
```

---

## COST MONITORING

### CloudWatch Metrics to Track

**Primary Metrics:**
```python
# Custom metric: Cost per invocation
def lambda_handler(event, context):
    start = time.time()
    
    # Do work
    result = process_event(event)
    
    # Calculate cost
    duration_s = time.time() - start
    memory_gb = context.memory_limit_in_mb / 1024
    gb_seconds = duration_s * memory_gb
    cost = gb_seconds * 0.0000166667
    
    # Publish metric
    cloudwatch.put_metric_data(
        Namespace='Lambda/Cost',
        MetricData=[{
            'MetricName': 'CostPerInvocation',
            'Value': cost,
            'Unit': 'None'
        }]
    )
    
    return result
```

**Metrics Dashboard:**
- Daily invocation count
- Average duration per function
- Average memory usage
- Estimated daily cost
- Cost per invocation trends
- Cold start percentage

---

### Cost Alerts

**Alert 1: Unexpected Cost Spike**
```bash
aws cloudwatch put-metric-alarm \
  --alarm-name lambda-daily-cost-spike \
  --comparison-operator GreaterThanThreshold \
  --evaluation-periods 1 \
  --metric-name EstimatedCharges \
  --namespace AWS/Billing \
  --period 86400 \
  --statistic Maximum \
  --threshold 100.0 \
  --alarm-actions <sns-topic>
```

**Alert 2: Inefficient Function**
```bash
# Alert on functions using >1GB memory with <50% utilization
aws cloudwatch put-metric-alarm \
  --alarm-name lambda-memory-overprovisioned \
  --comparison-operator LessThanThreshold \
  --evaluation-periods 3 \
  --metric-name MemoryUtilization \
  --namespace Lambda/Custom \
  --period 300 \
  --statistic Average \
  --threshold 50.0
```

---

## COST LESSONS LEARNED

### Lesson 1: Test Before Production

**Mistake:** Deployed function with 3GB memory to production

**Result:**
- Function only needed 256MB
- Cost: 12x higher than necessary
- Monthly waste: $500 on single function

**Prevention:** Load test in staging with various memory configurations

---

### Lesson 2: Monitor Per-Function Costs

**Mistake:** Only monitored total Lambda costs

**Result:**
- Single function caused 80% of costs
- Went unnoticed for 3 months
- Total waste: $2,400

**Prevention:** Enable per-function cost tracking and alerts

---

### Lesson 3: Optimize Hot Path

**Mistake:** Optimized rarely-used functions

**Result:**
- Spent 2 days optimizing function called 100x/day
- Ignored function called 100K times/day
- Potential savings missed: $1,000/month

**Prevention:** Prioritize optimization by: invocations × duration × memory

---

## COST OPTIMIZATION CHECKLIST

### Monthly Review
- [ ] Identify top 10 costliest functions
- [ ] Review memory configurations (over-provisioned?)
- [ ] Check average duration trends (increasing?)
- [ ] Analyze cold start percentage (>10%?)
- [ ] Review failure rates (excessive retries?)
- [ ] Check for unused functions (zero invocations?)

### Per-Function Optimization
- [ ] Profile execution time
- [ ] Test at multiple memory levels
- [ ] Optimize slowest operations
- [ ] Reduce cold start overhead
- [ ] Add early validation
- [ ] Configure dead letter queue
- [ ] Set appropriate timeout

---

## RELATED CONCEPTS

**Cross-References:**
- AWS-Lambda-LESS-02: Memory-performance trade-offs
- AWS-Lambda-LESS-04: Timeout management affects costs
- AWS-Lambda-DEC-02: Memory constraints drive optimization
- AWS-Lambda-LESS-01: Cold start reduction strategies

**Keywords:** cost optimization, pricing model, memory configuration, duration optimization, cost monitoring, GB-seconds, provisioned concurrency

---

**END OF FILE**

**Location:** `/sima/platforms/aws/lambda/lessons/AWS-Lambda-LESS-05-Cost-Monitoring.md`  
**Version:** 1.0.0  
**Lines:** 388 (within 400-line limit)

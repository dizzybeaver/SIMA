# AWS-Lambda-DEC-02-Memory-Constraints.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Memory allocation and constraints in Lambda  
**Category:** Platform - AWS Lambda  
**Type:** Decision

---

## DECISION

**Lambda functions must operate within memory constraints (128 MB - 10 GB). Design for memory efficiency, profile actual usage, and right-size memory allocation for optimal cost and performance.**

---

## CONTEXT

AWS Lambda charges based on memory allocated and execution time. Memory configuration also determines CPU allocation, affecting execution speed. Understanding memory constraints and optimization strategies is critical for cost-effective, performant Lambda functions.

---

## RATIONALE

### Memory Determines Both Cost and Performance

**Memory range: 128 MB to 10,240 MB (10 GB)**

**Memory affects:**
1. **Cost:** Higher memory = higher cost per millisecond
2. **CPU:** More memory = more CPU power
3. **Network:** More memory = more network bandwidth
4. **Performance:** More CPU = faster execution

**Cost-Performance Relationship:**
```
Memory    CPU Power    Cost/100ms    Execution Time    Total Cost
128 MB    0.08 vCPU    $0.000000208    4000ms           $0.000833
256 MB    0.17 vCPU    $0.000000417    2000ms           $0.000834
512 MB    0.33 vCPU    $0.000000834    1000ms           $0.000834
1024 MB   0.67 vCPU    $0.000001667     500ms           $0.000834
1769 MB   1.00 vCPU    $0.000002917     350ms           $0.000821 ✅
3008 MB   1.70 vCPU    $0.000004167     250ms           $0.000833
```

**Sweet spot often 1769-2048 MB:**
- Full vCPU allocation (1.0 cores)
- Reasonable execution time
- Optimal cost-performance ratio

### Default 128 MB Is Rarely Appropriate

**128 MB problems:**
```python
# ❌ WRONG - 128 MB insufficient for most workloads
# Imports alone can consume 50-80 MB
import boto3
import json
import requests

# Total memory: ~70 MB just from imports
# Only 58 MB available for actual work
# Slow CPU (0.08 vCPU) makes everything slower
```

**Minimum recommended: 512 MB for typical workloads**

---

## CONSEQUENCES

### Positive

**Right-sizing memory provides:**

**1. Performance:**
```
Measured: Process 1000 API calls

128 MB:  45 seconds  (slow CPU)
512 MB:  22 seconds  (better CPU)
1024 MB: 11 seconds  (good CPU)
1769 MB:  7 seconds  (full vCPU) ✅
3008 MB:  5 seconds  (diminishing returns)
```

**2. Cost optimization:**
```
# Under-provisioned (128 MB)
Time: 45s, Cost: $0.009 ❌ (slow, still expensive)

# Optimal (1769 MB)
Time: 7s, Cost: $0.008 ✅ (fast, cheaper)

# Over-provisioned (3008 MB)
Time: 5s, Cost: $0.010 ❌ (fast, but costs more)
```

**3. Reliability:**
- Adequate memory prevents OOM errors
- Sufficient CPU reduces timeout risk
- Headroom for memory spikes

**4. Predictability:**
- Consistent performance
- Stable execution times
- Fewer cold start variations

### Negative

**Over-provisioning costs:**
```
1024 MB vs 2048 MB for same workload:
- 2x memory cost
- Minimal performance gain
- Wasted resources
```

**Under-provisioning risks:**
```
- OOM errors (Lambda killed)
- Slow execution (insufficient CPU)
- Timeouts (slow processing)
- Higher cost (longer execution)
```

---

## IMPLEMENTATION

### Step 1: Profile Memory Usage

**Monitor actual usage:**
```python
import psutil
import os

def lambda_handler(event, context):
    # Get current memory usage
    process = psutil.Process(os.getpid())
    memory_info = process.memory_info()
    memory_mb = memory_info.rss / 1024 / 1024
    
    # Log memory stats
    print(f"Memory used: {memory_mb:.2f} MB")
    print(f"Memory limit: {context.memory_limit_in_mb} MB")
    print(f"Memory available: {context.memory_limit_in_mb - memory_mb:.2f} MB")
    
    # Process event
    result = process_event(event)
    
    # Check final memory usage
    final_memory_mb = process.memory_info().rss / 1024 / 1024
    print(f"Final memory: {final_memory_mb:.2f} MB")
    print(f"Memory growth: {final_memory_mb - memory_mb:.2f} MB")
    
    return result
```

**CloudWatch Logs report:**
```
REPORT RequestId: abc-123
Duration: 532.71 ms
Billed Duration: 533 ms
Memory Size: 1024 MB
Max Memory Used: 387 MB  ← Key metric!
```

### Step 2: Right-Size Memory

**Allocation strategy:**
```
Max Memory Used: 387 MB

Too tight:   512 MB  (33% headroom) ❌ Risky
Good:        768 MB  (98% headroom) ✅ Safe
Over:       1024 MB (162% headroom) ⚠️ Wasteful

Recommendation: 768 MB
```

**Headroom recommendations:**
```
Stable workload:     20-30% headroom
Variable workload:   50-100% headroom
Memory-intensive:    100-200% headroom
```

### Step 3: Test Performance

**Memory vs Duration trade-off:**
```python
# Test script
import boto3
import json
import time

lambda_client = boto3.client('lambda')

def test_memory_config(function_name, memory_size, iterations=10):
    # Update memory
    lambda_client.update_function_configuration(
        FunctionName=function_name,
        MemorySize=memory_size
    )
    
    # Wait for update
    time.sleep(5)
    
    # Test invocations
    durations = []
    for _ in range(iterations):
        response = lambda_client.invoke(
            FunctionName=function_name,
            InvocationType='RequestResponse',
            Payload=json.dumps({'test': 'data'})
        )
        
        # Parse log for duration
        log = response['LogResult']
        # Extract duration from log
        durations.append(extract_duration(log))
    
    avg_duration = sum(durations) / len(durations)
    cost_per_invocation = calculate_cost(memory_size, avg_duration)
    
    return {
        'memory': memory_size,
        'avg_duration_ms': avg_duration,
        'cost_per_invocation': cost_per_invocation,
        'cost_per_million': cost_per_invocation * 1_000_000
    }

# Test multiple memory configurations
for memory in [512, 1024, 1769, 2048, 3008]:
    result = test_memory_config('MyFunction', memory)
    print(f"Memory: {result['memory']} MB")
    print(f"Duration: {result['avg_duration_ms']:.2f} ms")
    print(f"Cost: ${result['cost_per_million']:.2f} per million")
    print()
```

### Step 4: Monitor and Adjust

**CloudWatch Alarms:**
```python
# Via CloudFormation
MemoryAlarm:
  Type: AWS::CloudWatch::Alarm
  Properties:
    AlarmDescription: Alert when memory usage > 90%
    MetricName: MemoryUtilization
    Namespace: AWS/Lambda
    Statistic: Maximum
    Period: 300
    EvaluationPeriods: 2
    Threshold: 90
    ComparisonOperator: GreaterThanThreshold
```

**Regular review:**
```
Weekly:  Check max memory used
Monthly: Analyze duration trends
Quarterly: Re-test memory configurations
```

---

## PATTERNS

### Pattern 1: Start with 1024 MB

**Rationale:**
- Reasonable starting point
- Adequate CPU (0.67 vCPU)
- Room for growth
- Profile from here

```python
# Initial deployment
MyFunction:
  Type: AWS::Lambda::Function
  Properties:
    MemorySize: 1024  # Starting point
    Timeout: 30
```

**Then adjust based on profiling:**
- Max memory < 300 MB → Try 512 MB
- Max memory 300-600 MB → Keep 1024 MB
- Max memory 600-1200 MB → Try 1769 MB
- Max memory > 1200 MB → Increase further

### Pattern 2: CPU-Intensive → Higher Memory

**CPU-bound workloads benefit from more CPU:**
```python
# Image processing, data transformation, etc.
def lambda_handler(event, context):
    # CPU-intensive work
    result = process_large_dataset(event['data'])
    return result

# Memory configuration
MemorySize: 3008  # 1.7 vCPU for faster processing
```

**Test reveals:**
```
1024 MB (0.67 vCPU): 15s execution = $0.025
3008 MB (1.7 vCPU):   6s execution = $0.015 ✅

Higher memory = faster execution = lower cost
```

### Pattern 3: I/O-Intensive → Lower Memory

**I/O-bound workloads don't benefit much from CPU:**
```python
# API calls, database queries, file I/O
async def lambda_handler(event, context):
    # I/O-bound work (waiting for responses)
    results = await asyncio.gather(
        fetch_api1(),
        fetch_api2(),
        fetch_database()
    )
    return results

# Memory configuration
MemorySize: 512  # Minimal CPU needs
```

**Test reveals:**
```
512 MB:  2.5s execution (waiting on I/O)
1769 MB: 2.4s execution (no significant improvement)

Lower memory sufficient for I/O workloads
```

### Pattern 4: Variable Load → Reserve Headroom

**Memory usage varies by input:**
```python
def lambda_handler(event, context):
    # Memory usage depends on batch size
    batch_size = len(event['items'])
    
    # Process items
    results = []
    for item in event['items']:
        result = process_item(item)  # ~10 MB per item
        results.append(result)
    
    return results

# Memory configuration
# Expect: 10-100 items (100-1000 MB usage)
MemorySize: 1769  # Headroom for large batches
```

---

## EXCEPTIONS

### When to Use High Memory (>3008 MB)

**1. Large Data Processing:**
```python
# Loading entire dataset into memory
def lambda_handler(event, context):
    # Load 2 GB dataset
    data = load_large_dataset()
    
    # Process in memory
    result = process_data(data)
    return result

MemorySize: 4096  # 2 GB data + processing overhead
```

**2. ML Inference:**
```python
# TensorFlow model (1.5 GB)
import tensorflow as tf

# Load model once (cold start)
model = tf.keras.models.load_model('/tmp/model')

def lambda_handler(event, context):
    # Run inference
    prediction = model.predict(event['input'])
    return {'prediction': prediction.tolist()}

MemorySize: 3008  # Model + inference memory
```

**3. Video/Image Processing:**
```python
# Process high-resolution images
def lambda_handler(event, context):
    # Load image
    image = load_image(event['s3_key'])  # 500 MB
    
    # Process
    processed = apply_filters(image)  # +500 MB
    
    # Save
    save_image(processed)
    return {'status': 'processed'}

MemorySize: 2048  # Images + processing
```

### When to Use Low Memory (<512 MB)

**Rare - only for:**
- Very simple functions (basic routing)
- Minimal processing
- No external dependencies
- Cost extremely sensitive

**Example:**
```python
# Simple router function
def lambda_handler(event, context):
    # Just route based on event type
    return {
        'target': determine_target(event['type']),
        'event': event
    }

MemorySize: 256  # Minimal processing
```

---

## RELATED PATTERNS

**Python Architectures:**
- LMMS: Lazy imports reduce memory usage
- ZAPH: Hot path optimization affects memory

**Lessons:**
- AWS-Lambda-LESS-02: Memory-Performance Trade-off

---

## KEY TAKEAWAYS

**Decision:**
- Design for memory constraints (128 MB - 10 GB)
- Profile actual usage
- Right-size for optimal cost-performance

**Recommendations:**
- Start with 1024 MB
- Profile max memory used
- Add 20-100% headroom
- Test performance vs cost

**Patterns:**
- CPU-intensive: Higher memory (more CPU)
- I/O-intensive: Lower memory (CPU not needed)
- Variable load: Extra headroom

**Optimization:**
- Monitor CloudWatch max memory used
- Test multiple configurations
- Balance cost and performance
- Review regularly

---

## RELATED

**Core:**
- AWS-Lambda-Core-Concepts.md
- AWS-Lambda-Runtime-Environment.md

**Decisions:**
- AWS-Lambda-DEC-01: Single-Threaded Execution
- AWS-Lambda-DEC-03: Timeout Limits

**Lessons:**
- AWS-Lambda-LESS-02: Memory-Performance Trade-off
- AWS-Lambda-LESS-04: Cost Monitoring

---

**END OF FILE**

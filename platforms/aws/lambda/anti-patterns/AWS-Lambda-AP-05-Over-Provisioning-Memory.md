# AWS-Lambda-AP-05-Over-Provisioning-Memory.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Anti-pattern of allocating excessive memory in Lambda  
**Category:** Platform - AWS Lambda - Anti-Pattern

---

## ANTI-PATTERN

**AP-05: Allocating more memory than needed**

**Category:** Resource Management / Cost Optimization  
**Severity:** Medium to High (depends on scale)  
**Impact:** Unnecessary cost, wasted resources

---

## DESCRIPTION

Functions configured with memory allocation far exceeding actual usage, resulting in significantly higher costs without performance benefits.

### Why This Is Wrong

**1. Direct Cost Impact**
- Lambda charges per GB-second
- Over-provisioned memory = higher per-second cost
- No performance benefit if memory unused
- Waste compounds with invocation count

**2. Inefficient Resource Usage**
- Reserves resources not needed
- Reduces available concurrency pool
- May trigger account limits sooner
- Wastes AWS infrastructure

**3. Masking Real Issues**
- Hides memory leaks
- Obscures optimization opportunities
- Prevents right-sizing other functions
- Makes cost analysis harder

**4. False Sense of Security**
- Assumes more = better always
- Ignores actual workload characteristics
- No data-driven decisions
- Cargo cult configuration

---

## COMMON MISTAKES

### Mistake 1: "More is Always Better"

```python
# WRONG: Configured at 10 GB without analysis
# Lambda function config:
# Memory: 10240 MB (10 GB)

def lambda_handler(event, context):
    """Simple API Gateway proxy."""
    user_id = event['queryStringParameters']['user_id']
    
    # Fetch from DynamoDB
    user = get_user(user_id)
    
    return {
        'statusCode': 200,
        'body': json.dumps(user)
    }

# Actual memory usage: 85 MB
# Wasted memory: 10,155 MB (99.2% waste!)
# Cost per million: $170.83
# Optimal at 128 MB: $0.21 per million
# Waste: 813x more expensive!
```

### Mistake 2: Copy-Paste Configuration

```yaml
# WRONG: All functions use same memory
functions:
  simple-proxy:
    handler: handlers.simple_proxy
    memory: 3008  # Way too much!
    
  data-processor:
    handler: handlers.data_processor
    memory: 3008  # Might be appropriate
    
  ml-inference:
    handler: handlers.ml_inference
    memory: 3008  # Too little!

# Problem: One size doesn't fit all
# Simple proxy needs 128 MB
# ML inference needs 5+ GB
```

### Mistake 3: Over-Provisioning for Peak

```python
# WRONG: Provisioned for absolute worst case
# Config: 3008 MB

def lambda_handler(event, context):
    batch_size = len(event['items'])
    
    # Usually 10 items (needs 512 MB)
    # Occasionally 1000 items (needs 2048 MB)
    # Almost never 10000 items (needs 3008 MB)
    
    return process_batch(event['items'])

# Problem: Paying for 3008 MB 99% of the time
# when 512 MB would suffice 95% of the time
```

### Mistake 4: "Pre-Optimization"

```python
# WRONG: Set high memory before profiling
# "Just to be safe"

def lambda_handler(event, context):
    # New function, haven't measured anything yet
    # But set to 3008 MB "just in case"
    return do_something(event)

# Problem:
# - No data on actual usage
# - May need 128 MB or 512 MB
# - Paying 6-24x more than necessary
# - Should profile FIRST, then configure
```

---

## CONSEQUENCES

### Cost Impact

**Example: 1 million invocations/day, 100ms duration**

```
Over-provisioned (3008 MB):
- Cost per invocation: $0.000050
- Daily cost: $50
- Monthly cost: $1,500

Right-sized (512 MB):
- Cost per invocation: $0.000008
- Daily cost: $8.50
- Monthly cost: $255

Waste: $1,245/month (488% overspend)
```

**At scale:**
```
1 billion invocations/month:

Over-provisioned (3008 MB):
- Monthly cost: $50,000

Right-sized (512 MB):
- Monthly cost: $8,500

Waste: $41,500/month
Annual waste: $498,000
```

### Performance Impact

**Minimal performance gain after optimal point:**

```
Function memory usage: 800 MB actual

128 MB:  Errors (out of memory)
256 MB:  Errors (out of memory)
512 MB:  Errors (out of memory)
1024 MB: 500ms execution
1536 MB: 450ms execution (10% faster)
1769 MB: 400ms execution (20% faster) ← Sweet spot
2048 MB: 390ms execution (22% faster)
3008 MB: 385ms execution (23% faster)
10240 MB: 380ms execution (24% faster)

Diminishing returns after 1769 MB!
Cost ratio: 10240 MB / 1769 MB = 5.8x more expensive
Performance gain: 380ms vs 400ms = 5% faster
```

**Verdict:** 5.8x cost for 5% speedup = terrible trade-off

### Resource Impact

**Account concurrency limits:**

```
Account limit: 1,000 concurrent executions

Scenario 1: All functions at 10 GB
- Each execution reserves 10 GB
- Can run 1,000 concurrent (hitting limit)

Scenario 2: Right-sized (mix of 128 MB - 2048 MB)
- Average: 1 GB per execution
- Can run 1,000 concurrent (lots of headroom)
- Better resource utilization
```

---

## CORRECT APPROACH

### Pattern 1: Profile Before Configuring

```python
# Step 1: Start with reasonable baseline (1024 MB)
# Step 2: Monitor actual usage

def lambda_handler(event, context):
    """Profile memory usage."""
    import resource
    
    # Your function logic
    result = process_data(event)
    
    # Log memory usage
    memory_used_mb = resource.getrusage(resource.RUSAGE_SELF).ru_maxrss / 1024
    print(f"Memory used: {memory_used_mb:.2f} MB")
    print(f"Memory limit: {context.memory_limit_in_mb} MB")
    
    return result
```

**CloudWatch analysis:**
```bash
# Check max memory over 1 week
aws cloudwatch get-metric-statistics \
    --namespace AWS/Lambda \
    --metric-name MemoryUtilization \
    --dimensions Name=FunctionName,Value=my-function \
    --start-time 2024-01-01T00:00:00Z \
    --end-time 2024-01-08T00:00:00Z \
    --period 3600 \
    --statistics Maximum

# Result: Max usage = 456 MB
# Current config: 3008 MB
# Recommendation: Set to 512-640 MB (with headroom)
```

### Pattern 2: Test Multiple Configurations

```python
# Test script
import boto3
import time

lambda_client = boto3.client('lambda')

def test_memory_config(function_name, memory_mb, iterations=100):
    """Test function at specific memory level."""
    
    # Update memory
    lambda_client.update_function_configuration(
        FunctionName=function_name,
        MemorySize=memory_mb
    )
    
    # Wait for update
    waiter = lambda_client.get_waiter('function_updated')
    waiter.wait(FunctionName=function_name)
    
    # Test performance
    durations = []
    for _ in range(iterations):
        response = lambda_client.invoke(
            FunctionName=function_name,
            InvocationType='RequestResponse',
            Payload=json.dumps({'test': 'data'})
        )
        
        # Extract billed duration from logs
        duration = extract_duration(response)
        durations.append(duration)
    
    avg_duration = sum(durations) / len(durations)
    p99_duration = sorted(durations)[int(len(durations) * 0.99)]
    
    # Calculate cost per million
    gb_seconds = (memory_mb / 1024) * (avg_duration / 1000)
    cost_per_million = gb_seconds * 1_000_000 * 0.0000166667
    
    return {
        'memory_mb': memory_mb,
        'avg_duration_ms': avg_duration,
        'p99_duration_ms': p99_duration,
        'cost_per_million': cost_per_million
    }

# Test multiple configurations
configs = [128, 256, 512, 1024, 1536, 1769, 2048, 3008]
results = []

for memory in configs:
    result = test_memory_config('my-function', memory)
    results.append(result)
    print(f"Memory: {memory} MB, Duration: {result['avg_duration_ms']}ms, "
          f"Cost/M: ${result['cost_per_million']:.2f}")

# Find optimal (lowest cost with acceptable performance)
optimal = min(results, key=lambda x: x['cost_per_million'])
print(f"Optimal: {optimal['memory_mb']} MB")
```

### Pattern 3: Add Headroom Appropriately

```python
# Right-sizing formula
def calculate_optimal_memory(actual_usage_mb):
    """Calculate optimal memory with appropriate headroom."""
    
    # Base headroom
    if actual_usage_mb < 128:
        # Small functions: 50% headroom minimum
        optimal = max(128, int(actual_usage_mb * 1.5))
    elif actual_usage_mb < 512:
        # Medium functions: 30% headroom
        optimal = int(actual_usage_mb * 1.3)
    else:
        # Large functions: 20% headroom
        optimal = int(actual_usage_mb * 1.2)
    
    # Round to nearest power of 2 or common config
    common_configs = [128, 256, 512, 1024, 1536, 1769, 2048, 3008]
    optimal = min(common_configs, key=lambda x: abs(x - optimal))
    
    return optimal

# Examples
print(calculate_optimal_memory(85))    # 128 MB
print(calculate_optimal_memory(400))   # 512 MB
print(calculate_optimal_memory(1200))  # 1536 MB
```

### Pattern 4: Workload-Specific Configuration

```yaml
# Correct: Each function sized appropriately

functions:
  # Simple proxy: minimal memory
  api-proxy:
    handler: handlers.api_proxy
    memory: 128  # Actual usage: 65 MB
    
  # Data transformer: moderate memory
  data-transform:
    handler: handlers.transform
    memory: 512  # Actual usage: 380 MB
    
  # ML inference: high memory
  ml-predict:
    handler: handlers.predict
    memory: 3008  # Actual usage: 2400 MB (model size)
    
  # Image processing: very high memory
  image-resize:
    handler: handlers.resize
    memory: 1769  # Actual usage: 1400 MB
```

---

## DETECTION

### CloudWatch Metrics Review

```python
# Check memory utilization
def check_memory_waste(function_name, days=7):
    """Identify over-provisioned functions."""
    cloudwatch = boto3.client('cloudwatch')
    lambda_client = boto3.client('lambda')
    
    # Get function config
    config = lambda_client.get_function_configuration(
        FunctionName=function_name
    )
    configured_mb = config['MemorySize']
    
    # Get actual usage from CloudWatch
    end_time = datetime.now()
    start_time = end_time - timedelta(days=days)
    
    response = cloudwatch.get_metric_statistics(
        Namespace='AWS/Lambda',
        MetricName='MemoryUtilization',
        Dimensions=[
            {'Name': 'FunctionName', 'Value': function_name}
        ],
        StartTime=start_time,
        EndTime=end_time,
        Period=3600,
        Statistics=['Maximum']
    )
    
    if not response['Datapoints']:
        return None
    
    max_utilization = max(dp['Maximum'] for dp in response['Datapoints'])
    actual_mb = (max_utilization / 100) * configured_mb
    
    waste_pct = ((configured_mb - actual_mb) / configured_mb) * 100
    
    if waste_pct > 50:
        return {
            'function': function_name,
            'configured_mb': configured_mb,
            'actual_mb': actual_mb,
            'waste_pct': waste_pct,
            'recommendation': calculate_optimal_memory(actual_mb),
            'severity': 'HIGH' if waste_pct > 75 else 'MEDIUM'
        }
    
    return None

# Scan all functions
lambda_client = boto3.client('lambda')
functions = lambda_client.list_functions()

over_provisioned = []
for func in functions['Functions']:
    result = check_memory_waste(func['FunctionName'])
    if result:
        over_provisioned.append(result)

# Report
print(f"Found {len(over_provisioned)} over-provisioned functions:")
for func in over_provisioned:
    print(f"{func['function']}: {func['waste_pct']:.1f}% waste, "
          f"recommend {func['recommendation']} MB")
```

### Cost Analysis

```python
def calculate_waste_cost(function_name, days=30):
    """Calculate wasted cost from over-provisioning."""
    cloudwatch = boto3.client('cloudwatch')
    lambda_client = boto3.client('lambda')
    
    # Get invocation count
    response = cloudwatch.get_metric_statistics(
        Namespace='AWS/Lambda',
        MetricName='Invocations',
        Dimensions=[
            {'Name': 'FunctionName', 'Value': function_name}
        ],
        StartTime=datetime.now() - timedelta(days=days),
        EndTime=datetime.now(),
        Period=86400,
        Statistics=['Sum']
    )
    
    total_invocations = sum(dp['Sum'] for dp in response['Datapoints'])
    
    # Get average duration
    response = cloudwatch.get_metric_statistics(
        Namespace='AWS/Lambda',
        MetricName='Duration',
        Dimensions=[
            {'Name': 'FunctionName', 'Value': function_name}
        ],
        StartTime=datetime.now() - timedelta(days=days),
        EndTime=datetime.now(),
        Period=86400,
        Statistics=['Average']
    )
    
    avg_duration_ms = sum(dp['Average'] for dp in response['Datapoints']) / len(response['Datapoints'])
    
    # Get configuration
    config = lambda_client.get_function_configuration(
        FunctionName=function_name
    )
    configured_mb = config['MemorySize']
    
    # Check actual usage
    waste_info = check_memory_waste(function_name, days)
    if not waste_info:
        return None
    
    actual_mb = waste_info['actual_mb']
    optimal_mb = waste_info['recommendation']
    
    # Calculate costs
    current_cost = calculate_cost(configured_mb, avg_duration_ms, total_invocations)
    optimal_cost = calculate_cost(optimal_mb, avg_duration_ms, total_invocations)
    
    monthly_waste = current_cost - optimal_cost
    annual_waste = monthly_waste * 12
    
    return {
        'function': function_name,
        'current_cost': current_cost,
        'optimal_cost': optimal_cost,
        'monthly_waste': monthly_waste,
        'annual_waste': annual_waste
    }

def calculate_cost(memory_mb, duration_ms, invocations):
    """Calculate Lambda cost."""
    gb_seconds = (memory_mb / 1024) * (duration_ms / 1000) * invocations
    duration_cost = gb_seconds * 0.0000166667
    request_cost = invocations * 0.0000002
    return duration_cost + request_cost
```

---

## REFACTORING GUIDE

### Step 1: Audit Current Configuration

```bash
# Export all function configs
aws lambda list-functions \
    --query 'Functions[].{Name:FunctionName,Memory:MemorySize}' \
    --output table

# Identify high-memory functions
aws lambda list-functions \
    --query 'Functions[?MemorySize > `2048`].{Name:FunctionName,Memory:MemorySize}' \
    --output table
```

### Step 2: Collect Actual Usage Data

```python
# Run for 7-14 days to collect representative data
for function_name in all_functions:
    actual_usage = get_max_memory_usage(function_name, days=14)
    configured = get_function_memory(function_name)
    
    if configured > actual_usage * 2:  # More than 2x actual
        print(f"{function_name}: Configured {configured} MB, "
              f"using {actual_usage} MB (over-provisioned)")
```

### Step 3: Test Reduced Configurations

```python
# For each over-provisioned function
for function in over_provisioned_functions:
    current_memory = function['configured_mb']
    recommended_memory = function['recommendation']
    
    # Test recommended configuration
    test_results = test_memory_config(
        function['name'],
        recommended_memory,
        iterations=100
    )
    
    # Verify no performance degradation
    if test_results['p99_duration_ms'] < acceptable_threshold:
        # Deploy new configuration
        update_function_memory(function['name'], recommended_memory)
        
        # Monitor for issues
        monitor_function(function['name'], days=3)
```

### Step 4: Gradual Rollout

```python
# Reduce memory gradually
def gradual_reduction(function_name, target_memory):
    """Reduce memory in steps."""
    current = get_function_memory(function_name)
    
    # Reduce by 25% at a time
    while current > target_memory:
        new_memory = int(current * 0.75)
        new_memory = max(new_memory, target_memory)
        
        print(f"Reducing {function_name} from {current} to {new_memory} MB")
        update_function_memory(function_name, new_memory)
        
        # Monitor for 24 hours
        if monitor_for_errors(function_name, hours=24):
            print(f"Errors detected, rolling back to {current} MB")
            update_function_memory(function_name, current)
            break
        
        current = new_memory
        if current == target_memory:
            print(f"Successfully reduced to {target_memory} MB")
```

---

## PREVENTION

### Development Guidelines

```python
# Lambda function template with profiling
def lambda_handler(event, context):
    """Template with built-in profiling."""
    import resource
    import json
    
    # Log configuration
    print(json.dumps({
        'configured_memory_mb': context.memory_limit_in_mb,
        'function_name': context.function_name
    }))
    
    # Your function logic
    result = your_logic_here(event)
    
    # Log actual usage
    memory_used_mb = resource.getrusage(resource.RUSAGE_SELF).ru_maxrss / 1024
    utilization = (memory_used_mb / context.memory_limit_in_mb) * 100
    
    print(json.dumps({
        'memory_used_mb': memory_used_mb,
        'memory_utilization_pct': utilization,
        'over_provisioned': utilization < 50
    }))
    
    return result
```

### CI/CD Integration

```yaml
# serverless.yml with memory alerts
functions:
  my-function:
    handler: handler.main
    memory: ${self:custom.memory.${opt:stage}}
    alarms:
      - name: MemoryWaste
        description: Alert if memory over-provisioned
        metric: MemoryUtilization
        threshold: 50
        statistic: Maximum
        period: 300
        evaluationPeriods: 12
        comparisonOperator: LessThanThreshold

custom:
  memory:
    dev: 1024  # Start conservative
    staging: ${self:custom.optimizedMemory.my-function, 1024}
    prod: ${self:custom.optimizedMemory.my-function, 1024}
```

---

## RELATED

**Decisions:**
- DEC-02: Memory Constraints
- DEC-05: Cost Optimization

**Lessons:**
- LESS-02: Memory-Performance Trade-off
- LESS-05: Cost Monitoring
- LESS-10: Performance Tuning

---

## EXAMPLES

### Example 1: API Gateway Proxy

```python
# WRONG: 3008 MB for simple proxy
def lambda_handler(event, context):
    """Simple API proxy."""
    return {
        'statusCode': 200,
        'body': json.dumps({'message': 'Hello'})
    }
# Actual usage: 45 MB
# Cost at 3008 MB: $50/million
# Cost at 128 MB: $0.21/million
# Waste: 238x overspend!

# CORRECT: 128 MB
# Same code, right-sized memory
# Cost: $0.21/million
```

### Example 2: Data Processing

```python
# WRONG: 10240 MB "to be safe"
def lambda_handler(event, context):
    """Process data batch."""
    items = event['items']
    return [process(item) for item in items]
# Actual usage: 890 MB (typical)
# Occasional spike: 1,500 MB

# CORRECT: 1769 MB (with headroom)
# Handles typical + spikes
# 5.8x cheaper than 10240 MB
```

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial anti-pattern documentation
- Cost impact analysis
- Detection methods
- Refactoring guide
- Prevention strategies

---

**END OF FILE**

**Key Takeaway:** Profile actual memory usage before configuring, don't guess.  
**Impact:** Can waste 2-10x cost with no performance benefit.  
**Solution:** Start with baseline, measure actual usage, add appropriate headroom.

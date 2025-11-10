# AWS-Lambda-FW-03.md

**Version:** 1.0.0  
**Date:** 2025-11-10  
**Purpose:** Predictive resource planning framework for Lambda  
**Category:** Platform - AWS Lambda - Framework  
**Priority:** OPTIONAL (Future Enhancement)

---

## FRAMEWORK

**FW-03: Predictive Resource Planning**

**Status:** OPTIONAL - For scale >100M invocations/month  
**Complexity:** HIGH  
**ROI:** Varies (10-30% cost reduction at very high scale)

---

## OVERVIEW

Predictive resource planning uses historical execution data to forecast optimal Lambda configurations, enabling proactive right-sizing rather than reactive manual tuning.

**Key Concept:** Machine learning models predict memory requirements and execution patterns based on historical metrics, automating the resource optimization process.

**Source:** Kumari, Sahoo, & Behera (2023) "Workflow Aware Analytical Model to Predict Performance and Cost of Serverless Execution"

---

## WHEN TO CONSIDER

### High-Scale Indicators

**Consider predictive planning when:**
```
✓ Invocations: >100M/month
✓ Functions: >50 different Lambda functions
✓ Cost: >$1,000/month Lambda spend
✓ Variability: Workload patterns change frequently
✓ Complexity: Multiple microservices with interdependencies
```

**Skip predictive planning when:**
```
✗ Invocations: <10M/month (manual tuning sufficient)
✗ Functions: <10 functions (too few to train model)
✗ Cost: <$100/month (optimization gains too small)
✗ Stability: Workload patterns stable (set and forget)
✗ Simplicity: Single-function architecture
```

### LEE Project Assessment

**Current state:**
- Invocations: ~50K/month (2,000x below threshold)
- Functions: 1 main function
- Cost: ~$0 (within free tier)
- Patterns: Stable (home automation)

**Recommendation:** NOT APPLICABLE  
**Alternative:** Manual configuration tiers (variables.py) sufficient

---

## FRAMEWORK COMPONENTS

### Component 1: Data Collection

**Metrics to collect:**
```python
{
    "timestamp": "2025-11-10T10:30:00Z",
    "function_name": "my-function",
    "memory_mb": 1769,
    "duration_ms": 450,
    "billed_duration_ms": 450,
    "max_memory_used_mb": 892,
    "init_duration_ms": 125,
    "cold_start": false,
    "invocation_type": "RequestResponse",
    "error": false,
    "cost": 0.0000013271
}
```

**Collection frequency:** Every invocation (CloudWatch Logs Insights)

**Retention:** 90 days minimum (for seasonal patterns)

### Component 2: Feature Engineering

**Features for prediction:**

**Time-based features:**
- Hour of day (0-23)
- Day of week (0-6)
- Week of month (1-4)
- Month of year (1-12)
- Holiday indicator (boolean)

**Workload features:**
- Request payload size (bytes)
- Response payload size (bytes)
- Database query complexity (estimated)
- External API calls count
- Cache hit rate (%)

**Historical features:**
- P50/P95/P99 duration (last 7 days)
- Mean memory usage (last 7 days)
- Cold start frequency (last 7 days)
- Error rate (last 7 days)

### Component 3: Predictive Models

**Model 1: Memory Prediction**

```python
# Random Forest Regression
from sklearn.ensemble import RandomForestRegressor

# Train on historical data
X = historical_features  # Time + workload features
y = max_memory_used_mb   # Target variable

model = RandomForestRegressor(
    n_estimators=100,
    max_depth=10,
    random_state=42
)
model.fit(X, y)

# Predict future memory needs
predicted_memory = model.predict(future_features)
recommended_memory = predicted_memory * 1.5  # Add 50% headroom
```

**Accuracy target:** 95% (Kumari et al. achieved 99.2%)

**Model 2: Duration Prediction**

```python
# Gradient Boosting Regression
from sklearn.ensemble import GradientBoostingRegressor

# Train on historical data
X = historical_features + [memory_config]
y = duration_ms

model = GradientBoostingRegressor(
    n_estimators=100,
    learning_rate=0.1,
    max_depth=5
)
model.fit(X, y)

# Predict duration for different memory configs
durations = {}
for memory in [512, 1024, 1769, 2048, 3008]:
    features_with_memory = features + [memory]
    durations[memory] = model.predict([features_with_memory])[0]
```

**Accuracy target:** 90% (Kumari et al. achieved 98.7%)

### Component 4: Cost Optimization

**Optimization function:**

```python
def optimize_memory_config(predicted_durations, hourly_invocations):
    """
    Find memory configuration that minimizes cost.
    
    Args:
        predicted_durations: {memory_mb: duration_ms}
        hourly_invocations: Expected invocations per hour
        
    Returns:
        optimal_memory_mb: Best memory configuration
    """
    min_cost = float('inf')
    optimal_memory = None
    
    for memory_mb, duration_ms in predicted_durations.items():
        # Calculate cost per invocation
        gb_seconds = (memory_mb / 1024) * (duration_ms / 1000)
        compute_cost = gb_seconds * 0.0000166667
        request_cost = 0.0000002
        cost_per_invocation = compute_cost + request_cost
        
        # Calculate hourly cost
        hourly_cost = cost_per_invocation * hourly_invocations
        
        # Track minimum
        if hourly_cost < min_cost:
            min_cost = hourly_cost
            optimal_memory = memory_mb
    
    return optimal_memory
```

### Component 5: Automated Adjustment

**Adjustment workflow:**

```python
def apply_optimized_config(function_name, optimal_memory):
    """
    Apply optimized memory configuration to Lambda function.
    
    Args:
        function_name: Lambda function name
        optimal_memory: Recommended memory (MB)
    """
    import boto3
    
    lambda_client = boto3.client('lambda')
    
    # Get current configuration
    current = lambda_client.get_function_configuration(
        FunctionName=function_name
    )
    current_memory = current['MemorySize']
    
    # Only update if change is significant (>10%)
    memory_diff_pct = abs(optimal_memory - current_memory) / current_memory
    
    if memory_diff_pct > 0.10:
        print(f"Updating {function_name}: {current_memory}MB → {optimal_memory}MB")
        
        lambda_client.update_function_configuration(
            FunctionName=function_name,
            MemorySize=optimal_memory
        )
    else:
        print(f"No update needed for {function_name} (< 10% change)")
```

**Frequency:** Daily or weekly (not every invocation)

**Safety:** Gradual rollout with monitoring

---

## IMPLEMENTATION

### Phase 1: Data Collection (Week 1-2)

**Set up CloudWatch Logs Insights:**

```sql
fields @timestamp, @duration, @maxMemoryUsed, @memorySize, @initDuration
| filter @type = "REPORT"
| stats
    avg(@duration) as avg_duration,
    percentile(@duration, 50) as p50_duration,
    percentile(@duration, 95) as p95_duration,
    percentile(@duration, 99) as p99_duration,
    avg(@maxMemoryUsed) as avg_memory,
    max(@maxMemoryUsed) as max_memory,
    count(*) as invocations
  by bin(5m) as time_window
```

**Export to S3:**

```python
import boto3

logs = boto3.client('logs')

response = logs.start_query(
    logGroupName='/aws/lambda/my-function',
    startTime=int(time.time()) - 86400 * 90,  # 90 days
    endTime=int(time.time()),
    queryString='...',  # Query above
    limit=10000
)

# Wait for results, export to S3
```

### Phase 2: Model Training (Week 3-4)

**Train models on historical data:**

```python
import pandas as pd
from sklearn.model_selection import train_test_split

# Load historical data
df = pd.read_csv('s3://my-bucket/lambda-metrics.csv')

# Feature engineering
df['hour'] = pd.to_datetime(df['timestamp']).dt.hour
df['day_of_week'] = pd.to_datetime(df['timestamp']).dt.dayofweek
# ... more features

# Train/test split
X = df[feature_columns]
y = df['max_memory_used_mb']

X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42
)

# Train model
model.fit(X_train, y_train)

# Evaluate
from sklearn.metrics import r2_score, mean_absolute_error

predictions = model.predict(X_test)
r2 = r2_score(y_test, predictions)
mae = mean_absolute_error(y_test, predictions)

print(f"R²: {r2:.3f}")  # Target: >0.95
print(f"MAE: {mae:.1f} MB")  # Target: <50 MB
```

### Phase 3: Validation (Week 5-6)

**A/B test predictions:**

```python
# Group A: Current manual configuration
# Group B: Predicted configuration

# Run for 1 week, compare:
- Cost per invocation
- Average duration
- Error rate
- User experience (latency)
```

**Success criteria:**
- Cost reduction: >10%
- Duration improvement: >0% (no regression)
- Error rate: No increase
- Latency: No increase

### Phase 4: Automation (Week 7-8)

**Deploy automated system:**

```python
# Lambda function: Daily optimization
def daily_optimization_handler(event, context):
    # 1. Fetch yesterday's metrics
    metrics = fetch_metrics(days=1)
    
    # 2. Predict optimal configuration
    features = engineer_features(metrics)
    optimal_memory = model.predict(features)
    
    # 3. Apply configuration
    for function_name, memory in optimal_memory.items():
        apply_optimized_config(function_name, memory)
    
    # 4. Log changes
    log_configuration_changes(optimal_memory)
```

**Scheduling:** EventBridge rule (daily at 2 AM)

**Monitoring:** CloudWatch alarms for cost anomalies

---

## BENEFITS

### Quantified Benefits (from research)

**Accuracy improvements:**
- Memory prediction: 99.2% accuracy
- Duration prediction: 98.7% accuracy

**Cost reduction:**
- Typical: 10-20% cost reduction
- High variance workloads: 20-30% reduction
- Stable workloads: 5-10% reduction

**Time savings:**
- Eliminate manual tuning (2-4 hours/month)
- Automated right-sizing
- Proactive optimization (before cost spikes)

### ROI Calculation

**Cost at 100M invocations/month:**
```
Current: $1,000/month (manual tuning)
Optimized: $800/month (20% reduction via prediction)

Savings: $200/month = $2,400/year
```

**Implementation cost:**
```
Development: 160 hours @ $100/hr = $16,000
Maintenance: 20 hours/year @ $100/hr = $2,000/year
Infrastructure: $50/month = $600/year

Year 1: -$16,000 + $2,400 = -$13,600 (loss)
Year 2: $2,400 - $2,600 = -$200 (loss)
Year 3: $2,400 - $2,600 = -$200 (loss)

Break-even: Never for 100M invocations/month!
```

**At 500M invocations/month:**
```
Current: $5,000/month
Optimized: $4,000/month (20% reduction)

Savings: $1,000/month = $12,000/year

Year 1: -$16,000 + $12,000 = -$4,000 (loss)
Year 2: $12,000 - $2,600 = $9,400 (profit)
Year 3: $12,000 - $2,600 = $9,400 (profit)

Break-even: 16 months
ROI: 58% (5-year)
```

---

## ALTERNATIVES

### Alternative 1: Manual Tuning (Current approach)

**Pros:**
- Simple
- No ML expertise required
- Works well for <50M invocations
- Low implementation cost

**Cons:**
- Time-consuming
- Reactive (not proactive)
- Misses optimization opportunities

**Best for:** Low-to-medium scale (<100M invocations/month)

### Alternative 2: AWS Compute Optimizer

**Pros:**
- Free service from AWS
- No implementation required
- Provides memory recommendations
- Based on CloudWatch metrics

**Cons:**
- Limited ML capabilities
- Recommendations not automated
- No cost prediction
- No workload-aware optimization

**Best for:** Quick wins without custom development

### Alternative 3: Predictive Framework (This approach)

**Pros:**
- Highly accurate (99%+)
- Automated optimization
- Proactive cost management
- Workload-aware

**Cons:**
- High implementation cost
- Requires ML expertise
- Complex maintenance
- Only profitable at very high scale

**Best for:** Large scale (>100M invocations/month)

---

## WHEN NOT TO USE

### Scenarios to avoid

**❌ Low volume workloads**
- ROI negative
- Manual tuning sufficient
- Example: <10M invocations/month

**❌ Stable workloads**
- Patterns don't change
- Set and forget works
- Example: Scheduled batch jobs

**❌ Small teams**
- No ML expertise
- Can't maintain models
- Example: Team <5 engineers

**❌ Simple architectures**
- Few functions (<10)
- Not enough data
- Example: Single-function monolith

**❌ Free tier usage**
- $0 current cost
- No room for savings
- Example: LEE project (50K invocations/month)

---

## RELATED

**Decisions:**
- DEC-02: Memory Constraints
- DEC-05: Cost Optimization (manual approach)
- DEC-07: Provisioned Concurrency vs Optimization

**Lessons:**
- LESS-02: Memory-Performance Trade-off
- LESS-05: Cost Monitoring
- LESS-10: Performance Tuning

**Frameworks:**
- FW-01: Configuration tiering (simpler alternative)
- FW-02: Performance profiling

---

## CONCLUSION

Predictive resource planning is a **powerful but complex** framework suitable only for very high-scale Lambda deployments (>100M invocations/month). For most use cases, including the LEE project, **manual tuning with configuration tiers** remains the superior choice due to simplicity and sufficient effectiveness.

**Recommendation:**
- **Use manual tuning:** <100M invocations/month
- **Consider AWS Compute Optimizer:** 10-100M invocations/month
- **Implement predictive planning:** >100M invocations/month with dedicated ML team

---

## VERSION HISTORY

**v1.0.0 (2025-11-10):**
- Initial framework documentation
- Predictive planning approach from academic research
- ROI analysis showing high-scale requirement
- Implementation phases
- Alternatives comparison
- Clear guidance on when NOT to use

---

**END OF FILE**

**Status:** OPTIONAL - For future consideration at high scale  
**Priority:** LOW for LEE project (50K invocations/month)  
**Alternative:** Manual configuration tiers (variables.py) remain sufficient  
**Source:** Kumari et al. (2023) academic research on serverless optimization

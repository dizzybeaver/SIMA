# AWS-APIGateway-LESS-03-Throttling-Configuration.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Configure throttling to protect backend services and ensure fair usage  
**Category:** AWS Platform / API Gateway / Lessons

---

## Lesson

**Always configure throttling at API Gateway to protect backend services from overload and ensure fair resource allocation**

---

## Context

**Problem:**  
Backend services (Lambda, databases) can be overwhelmed by traffic spikes, leading to:
- Cascading failures
- Increased costs
- Poor user experience for all users
- Resource exhaustion

**Discovery:**  
Production API experienced uncontrolled traffic spike (10x normal), causing:
- Lambda throttling (concurrency limit hit)
- DynamoDB throttling (provisioned capacity exceeded)
- 503 errors for all users
- $5,000 unexpected costs in 2 hours

**Solution:**  
Proper throttling at API Gateway prevented similar incidents, limiting damage and ensuring graceful degradation.

---

## Understanding Throttling

### Token Bucket Algorithm

**How It Works:**
```
Bucket holds tokens:
- Capacity = Burst limit
- Tokens added at rate limit per second
- Each request consumes 1 token
- Request rejected if bucket empty (429 error)

Example:
- Rate limit: 1000 RPS (requests per second)
- Burst limit: 2000 requests
- Bucket gets 1000 tokens/second
- Can handle bursts up to 2000 requests
- Sustained load: 1000 RPS
```

**Behavior:**
```
Time 0s: Bucket full (2000 tokens)
- 2000 requests → All succeed
- Bucket empty

Time 1s: +1000 tokens (rate replenishment)
- 1500 requests → 1000 succeed, 500 rejected (429)

Time 2s: +1000 tokens
- 1000 requests → All succeed
- Sustained rate maintained
```

---

## Throttling Levels

### Account-Level Throttling (Default)

**AWS Defaults:**
```
Rate Limit: 10,000 requests/second
Burst Limit: 5,000 requests

Applies to:
- All APIs in account
- All stages in account
- Soft limit (can request increase)
```

**When to Increase:**
```
Request increase if:
- Legitimate traffic > 10K RPS
- Not caused by attack/bug
- Business justification
- Gradual increase (10K → 20K → 50K)
```

### Stage-Level Throttling

**Configuration:**
```yaml
# SAM template
API:
  Type: AWS::Serverless::Api
  Properties:
    StageName: prod
    MethodSettings:
      - ResourcePath: '/*'
        HttpMethod: '*'
        ThrottlingRateLimit: 1000  # 1000 RPS
        ThrottlingBurstLimit: 2000  # 2000 burst
```

**Use Cases:**
```
Production stage:
- ThrottlingRateLimit: 5000
- ThrottlingBurstLimit: 10000
- Protect backend from overload

Development stage:
- ThrottlingRateLimit: 100
- ThrottlingBurstLimit: 200
- Prevent runaway tests
```

### Method-Level Throttling

**Configuration:**
```yaml
# Different limits per endpoint
MethodSettings:
  - ResourcePath: '/users'
    HttpMethod: 'GET'
    ThrottlingRateLimit: 1000
    ThrottlingBurstLimit: 2000
  
  - ResourcePath: '/users'
    HttpMethod: 'POST'
    ThrottlingRateLimit: 100
    ThrottlingBurstLimit: 200
```

**Use Cases:**
```
Read-heavy endpoint:
GET /products → 5000 RPS (high limit)

Write-heavy endpoint:
POST /orders → 100 RPS (protect database writes)

Expensive endpoint:
GET /analytics → 10 RPS (heavy computation)
```

### Usage Plans & API Keys

**Per-Client Throttling:**
```yaml
UsagePlan:
  Type: AWS::ApiGateway::UsagePlan
  Properties:
    UsagePlanName: StandardPlan
    Throttle:
      RateLimit: 1000
      BurstLimit: 2000
    Quota:
      Limit: 1000000  # 1M requests/month
      Period: MONTH
    ApiStages:
      - ApiId: !Ref API
        Stage: prod
```

**Use Cases:**
```
Free tier:
- 100 RPS
- 100K requests/month

Paid tier:
- 1000 RPS
- 1M requests/month

Enterprise tier:
- 10000 RPS
- Unlimited requests
```

---

## Configuration Strategies

### Strategy 1: Protect Backend Capacity

**Calculate Backend Limits:**
```
Lambda:
- Account concurrency limit: 1000
- Reserved for this API: 500
- Average duration: 100ms
- Max RPS: 500 / 0.1 = 5000 RPS

DynamoDB:
- Provisioned WCU: 1000
- Item size: 1KB
- Max write RPS: 1000

Set API Gateway throttle:
- Rate limit: min(5000, 1000) = 1000 RPS
- Leave 20% headroom: 800 RPS
```

**Example:**
```yaml
ThrottlingRateLimit: 800
ThrottlingBurstLimit: 1600
# Protects DynamoDB from overload
```

### Strategy 2: Gradual Degradation

**Priority-Based Throttling:**
```yaml
# Critical endpoints - higher limits
GET /health:
  ThrottlingRateLimit: 10000  # Always available

# Important endpoints - normal limits
GET /users:
  ThrottlingRateLimit: 1000

# Nice-to-have endpoints - lower limits
GET /analytics:
  ThrottlingRateLimit: 100

# Under load:
# Analytics throttled first
# Users throttled second
# Health always available
```

### Strategy 3: Cost Control

**Prevent Runaway Costs:**
```yaml
# Expensive backend operation
POST /generate-report:
  ThrottlingRateLimit: 10  # Max 10 concurrent
  ThrottlingBurstLimit: 20
  
# If backend costs $1 per request:
# Max cost: 10 RPS × $1 × 3600s = $36K/hour
# With throttle: 10 RPS × $1 × 3600s = $36/hour
# Savings: $35,964/hour prevented
```

### Strategy 4: Fair Usage

**Prevent Single Client Monopolization:**
```
Usage Plans:
- Each client gets 1000 RPS
- Total capacity: 10000 RPS
- Can support 10 clients fairly
- One client can't exhaust API for others
```

---

## Real-World Examples

### Example 1: E-Commerce Flash Sale

**Scenario:**
```
Flash sale announced:
- Normal traffic: 100 RPS
- Sale traffic: 10,000 RPS
- Backend capacity: 500 RPS
```

**Without Throttling:**
```
10,000 requests → API Gateway → 10,000 Lambda invocations
- Lambda concurrency exhausted
- DynamoDB throttled
- Cascade failure
- All users see errors
- $10,000 cost spike
```

**With Throttling:**
```
ThrottlingRateLimit: 500
ThrottlingBurstLimit: 1000

10,000 requests arrive:
- 500 RPS accepted
- 9,500 RPS rejected (429)
- Backend operates normally
- Users retry (with backoff)
- Gradual processing
- Predictable costs
```

**User Experience:**
```
Without throttling:
- 100% users see 503 errors (backend overload)

With throttling:
- 5% users see 200 success (processed immediately)
- 95% users see 429 throttled
- Retry with exponential backoff
- Eventually all processed
- Better than total failure
```

### Example 2: API Abuse Protection

**Scenario:**
```
Malicious client attacking API:
- 100,000 requests/second
- Attempting to exhaust resources
- Targeting expensive endpoints
```

**Without Throttling:**
```
100K RPS → All reach Lambda
- $200/minute Lambda costs
- Database overload
- Service degradation
- Legitimate users affected
```

**With Throttling:**
```
Stage level: 5000 RPS
Method level: 10 RPS (expensive endpoint)
Usage plan: 1000 RPS per client

Result:
- 95,000 RPS rejected at gateway (free)
- 5,000 RPS processed
- Attacker identified (API key)
- Key revoked
- Service stable
- Minimal cost impact
```

### Example 3: Development Environment Protection

**Scenario:**
```
Developer runs load test against prod:
- Accidentally targets prod instead of dev
- 50,000 requests/second
- Would cost $5,000
```

**Without Throttling:**
```
50K RPS → All processed
- Unexpected $5,000 bill
- Production degraded
- Real users affected
```

**With Throttling:**
```
Dev API key with usage plan:
- Rate limit: 100 RPS
- Quota: 10K requests/day

Result:
- 100 RPS processed
- 49,900 RPS rejected
- Cost: ~$0.20
- Production unaffected
- Developer notified of limit
```

---

## Monitoring & Alerting

### CloudWatch Metrics

```python
import boto3

cloudwatch = boto3.client('cloudwatch')

# Monitor 429 errors (throttling)
response = cloudwatch.get_metric_statistics(
    Namespace='AWS/ApiGateway',
    MetricName='4XXError',
    Dimensions=[
        {'Name': 'ApiName', 'Value': 'MyApi'},
        {'Name': 'Stage', 'Value': 'prod'}
    ],
    StartTime=datetime.now() - timedelta(hours=1),
    EndTime=datetime.now(),
    Period=300,
    Statistics=['Sum']
)

# Check if 429 rate increasing
for datapoint in response['Datapoints']:
    if datapoint['Sum'] > 1000:  # Threshold
        alert('High throttling rate!')
```

### CloudWatch Alarms

```yaml
ThrottleAlarm:
  Type: AWS::CloudWatch::Alarm
  Properties:
    AlarmName: API-High-Throttling
    MetricName: 4XXError
    Namespace: AWS/ApiGateway
    Statistic: Sum
    Period: 300
    EvaluationPeriods: 2
    Threshold: 1000
    ComparisonOperator: GreaterThanThreshold
    AlarmActions:
      - !Ref SNSTopic
    Dimensions:
      - Name: ApiName
        Value: MyApi
      - Name: Stage
        Value: prod
```

### Logging

```python
# Lambda function logging throttled requests
def lambda_handler(event, context):
    # Log if caller is being throttled
    if event.get('requestContext', {}).get('identity', {}).get('userAgent'):
        logger.info(f"Request processed for {event['requestContext']['identity']['sourceIp']}")
    
    # Check if approaching throttle limit
    # (not directly accessible, but can infer from 429 responses)
```

---

## Client-Side Handling

### Retry with Exponential Backoff

```python
import time
import requests

def api_call_with_retry(url, max_retries=5):
    for attempt in range(max_retries):
        response = requests.get(url)
        
        if response.status_code == 200:
            return response.json()
        
        if response.status_code == 429:
            # Throttled - retry with backoff
            wait_time = (2 ** attempt) + random.random()
            print(f"Throttled. Waiting {wait_time:.2f}s")
            time.sleep(wait_time)
            continue
        
        if response.status_code >= 500:
            # Server error - retry
            wait_time = 2 ** attempt
            time.sleep(wait_time)
            continue
        
        # Client error (400, 401, 403, etc) - don't retry
        response.raise_for_status()
    
    raise Exception("Max retries exceeded")
```

### Respect Rate Limits

```python
import time

class RateLimitedClient:
    def __init__(self, rate_limit_rps):
        self.rate_limit = rate_limit_rps
        self.tokens = rate_limit_rps
        self.last_update = time.time()
    
    def wait_if_needed(self):
        now = time.time()
        elapsed = now - self.last_update
        
        # Replenish tokens
        self.tokens = min(
            self.rate_limit,
            self.tokens + elapsed * self.rate_limit
        )
        self.last_update = now
        
        # Wait if no tokens
        if self.tokens < 1:
            wait_time = (1 - self.tokens) / self.rate_limit
            time.sleep(wait_time)
            self.tokens = 0
        else:
            self.tokens -= 1
    
    def make_request(self, url):
        self.wait_if_needed()
        return requests.get(url)

# Usage
client = RateLimitedClient(rate_limit_rps=100)
for i in range(1000):
    response = client.make_request(f'https://api.example.com/items/{i}')
    # Automatically throttles to 100 RPS
```

---

## Best Practices

### 1. Always Set Throttles

```
✅ DO set throttles for:
- All production APIs
- All development/staging APIs
- All public APIs
- All internal APIs

❌ DON'T leave unlimited
- Even internal APIs can be abused
- Bugs can cause runaway requests
```

### 2. Layer Your Defense

```
Multiple throttling levels:
1. Account level (10K RPS) - global limit
2. Stage level (5K RPS) - per environment
3. Method level (1K RPS) - per endpoint
4. Usage plan (100 RPS) - per client

Like seat belts + airbags + crumple zones
```

### 3. Leave Headroom

```
Backend capacity: 1000 RPS
Set throttle: 800 RPS (80%)

Reasons:
- Allows traffic spikes
- Other services share backend
- Monitoring overhead
- Safety margin
```

### 4. Monitor 429 Rate

```
High 429 rate means:
- Legitimate traffic exceeds limits (increase)
- Attack/abuse (block)
- Bug causing excessive requests (fix)

Low 429 rate means:
- Limits appropriate
- No issues
```

### 5. Provide Retry-After Header

```python
# Lambda response for 429
return {
    'statusCode': 429,
    'headers': {
        'Retry-After': '60',  # Seconds
        'X-RateLimit-Limit': '1000',
        'X-RateLimit-Remaining': '0',
        'X-RateLimit-Reset': '1640000000'
    },
    'body': json.dumps({
        'error': 'Rate limit exceeded',
        'message': 'Please retry after 60 seconds'
    })
}
```

---

## Configuration Checklist

```
[ ] Calculate backend capacity limits
[ ] Set account-level throttle (if needed)
[ ] Set stage-level throttle (production)
[ ] Set method-level throttles (expensive endpoints)
[ ] Create usage plans (for different tiers)
[ ] Generate API keys (for clients)
[ ] Configure CloudWatch alarms (429 errors)
[ ] Test throttling behavior
[ ] Document rate limits (API docs)
[ ] Implement client retry logic
[ ] Monitor throttle metrics
[ ] Adjust limits based on usage
```

---

## Related Topics

- **AWS-APIGateway-Core-Concepts**: Throttling basics
- **AWS-Lambda-Performance-Tuning**: Backend capacity planning
- **AWS-APIGateway-AP-02**: No throttling configuration

---

**END OF FILE**

**Key Takeaway:**  
Always configure throttling at API Gateway to protect backend services from overload. Use multiple throttling levels (stage, method, usage plan) for defense in depth. Monitor 429 errors and adjust limits based on usage patterns.

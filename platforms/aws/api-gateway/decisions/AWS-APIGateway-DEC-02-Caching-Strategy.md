# AWS-APIGateway-DEC-02-Caching-Strategy.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Decision framework for implementing API Gateway caching  
**Category:** AWS Platform / API Gateway / Decisions

---

## Decision

**When to implement response caching at API Gateway layer and how to configure it effectively**

---

## Context

API Gateway (REST APIs only) supports response caching to:
- Reduce backend invocations
- Lower response latency
- Decrease costs
- Improve scalability

**Cost:**
- 0.5 GB cache: $0.02/hour = $14.40/month
- 1.6 GB cache: $0.038/hour = $27.36/month
- 237 GB cache: $3.80/hour = $2,736/month

**Trade-off:**
- Cache cost vs backend cost savings
- Freshness vs performance
- Complexity vs simplicity

---

## Decision Framework

### When to Use Caching

**1. High Read-to-Write Ratio**
```
Ideal candidates:
- Product catalogs (95% reads)
- Reference data (99% reads)
- Public APIs (90% reads)
- Static content (100% reads)

Break-even example:
- 1M requests/day
- 80% cache hit rate
- Lambda: $0.20 per 1M invocations
- Savings: 800K invocations × $0.20 = $0.16/day
- Cache cost (0.5GB): $0.02/hour = $0.48/day
- Net: $0.16 - $0.48 = -$0.32/day LOSS

Need higher volume or more expensive backend!
```

**2. Expensive Backend Operations**
```
Candidates:
- Complex database queries
- External API calls
- Heavy computations
- Multiple service calls

Example:
- 1M requests/day
- Backend cost: $5/M invocations
- 80% cache hit rate
- Savings: 800K × $5/M = $4/day
- Cache cost: $0.48/day
- Net: $4 - $0.48 = $3.52/day SAVINGS
- ROI: 633% return
```

**3. Predictable Content**
```
Good for caching:
- Product information
- User profiles (low update frequency)
- Configuration data
- Weather data (hourly updates)
- News articles (static once published)

Bad for caching:
- Real-time stock prices
- Live sports scores
- Chat messages
- Personalized recommendations
```

**4. Global User Base**
```
With edge-optimized endpoints:
- Cache at CloudFront edges
- Lower latency globally
- Reduced backend load
- Works well for public APIs
```

### When NOT to Use Caching

**1. Write-Heavy APIs**
```
POST/PUT/DELETE dominated:
- Create operations (no caching)
- Update operations (invalidate cache)
- Delete operations (invalidate cache)
- Low cache hit rate
- Poor ROI
```

**2. Personalized Responses**
```
User-specific data:
- Per-user dashboards
- Account information
- Personal recommendations
- Shopping carts

Would need per-user cache keys:
- High cardinality
- Low hit rate per key
- Memory inefficient
```

**3. Real-Time Requirements**
```
Freshness critical:
- Live data feeds
- Real-time analytics
- Gaming leaderboards
- Stock trading APIs
- IoT sensor data
```

**4. Low Traffic Volume**
```
< 10,000 requests/day:
- Cache cost > backend savings
- Low cache hit rate
- Over-engineering
- Use simpler approaches
```

---

## Caching Configuration

### TTL (Time to Live)

**Short TTL (60-300 seconds)**
```
Use cases:
- Frequently updated data
- News feeds
- Weather updates
- Social media posts

Pros:
- Fresher data
- Lower staleness risk

Cons:
- More backend calls
- Lower cache hit rate
- Lower cost savings
```

**Medium TTL (300-3600 seconds)**
```
Use cases:
- Product catalogs
- User profiles
- Reference data
- API documentation

Pros:
- Good balance
- Decent cache hit rate
- Acceptable freshness

Cons:
- Some staleness possible
- Need invalidation strategy
```

**Long TTL (3600+ seconds)**
```
Use cases:
- Static content
- Historical data
- Rarely changing configuration
- Public datasets

Pros:
- Highest cache hit rate
- Maximum cost savings
- Best performance

Cons:
- Staleness issues
- Manual invalidation needed
- Not suitable for dynamic data
```

### Cache Key Design

**Query Parameters**
```yaml
# Include specific parameters in cache key
CacheKeyParameters:
  - method.request.querystring.category
  - method.request.querystring.page
  - method.request.querystring.limit

Example:
/products?category=electronics&page=1 → cache key 1
/products?category=electronics&page=2 → cache key 2
/products?category=books&page=1 → cache key 3
```

**Path Parameters**
```yaml
# Automatically included in cache key
/users/{id} 

Example:
/users/123 → cache key 1
/users/456 → cache key 2
```

**Headers**
```yaml
# Include headers in cache key if needed
CacheKeyParameters:
  - method.request.header.Accept-Language

Example:
Accept-Language: en → cache key (English)
Accept-Language: es → cache key (Spanish)
```

**Avoid:**
```
❌ Don't include:
- Authorization headers (user-specific)
- Session tokens (high cardinality)
- Request IDs (unique per request)
- Timestamps (never hits)

Result: 0% cache hit rate
```

### Cache Size

**Decision Matrix:**
```
API Volume         Cache Size    Cost/Month
-----------        ----------    ----------
< 1M req/day       0.5 GB        $14.40
1-5M req/day       1.6 GB        $27.36
5-10M req/day      6.1 GB        $76.32
10-50M req/day     13.5 GB       $168.48
> 50M req/day      28+ GB        $349+

Rule of thumb:
Cache Size (GB) ≈ Daily Requests / 100,000
```

---

## Real-World Examples

### Example 1: Product Catalog API

**Scenario:**
```
E-commerce product API:
- 5M requests/day
- 95% reads (GET)
- 5% writes (POST/PUT)
- Backend: $0.50/M invocations
- Products update daily
```

**Analysis:**
```
Without Cache:
- 5M requests
- 5M backend calls
- Cost: 5M × $0.50/M = $2.50/day
- Total: $2.50/day

With Cache (TTL: 1 hour):
- 5M requests
- 90% cache hit rate
- 500K backend calls
- Backend cost: 500K × $0.50/M = $0.25/day
- Cache cost: $0.02/hour × 24 = $0.48/day
- Total: $0.73/day

Savings: $2.50 - $0.73 = $1.77/day = $53/month
ROI: 242% return
```

**Decision:** Implement caching

**Configuration:**
```yaml
CacheConfiguration:
  Enabled: true
  CacheTtl: 3600  # 1 hour
  CacheDataEncrypted: true
  CacheClusterSize: '1.6'  # 1.6 GB
  CacheKeyParameters:
    - method.request.querystring.category
```

### Example 2: User Profile API

**Scenario:**
```
User profile API:
- 1M requests/day
- 60% reads, 40% writes
- Backend: $0.20/M invocations
- Personalized per user
```

**Analysis:**
```
Cache key options:
1. No cache key differentiation
   - Low hit rate (personalized data)
   - 10-20% hit rate
   
2. Include user ID in cache key
   - High cardinality
   - 1M users = 1M cache keys
   - Low hit rate per key
   - Poor memory efficiency

Without Cache:
- Cost: $0.20/day
- Latency: 50-100ms

With Cache:
- Cache cost: $0.48/day
- Low hit rate (20%)
- Net: $0.20 - $0.48 = -$0.28/day LOSS
```

**Decision:** Don't use caching

**Alternative:**
```
Consider:
- DynamoDB caching (DAX)
- Application-level caching (Redis)
- Lambda-level caching (global variables)
```

### Example 3: Weather API

**Scenario:**
```
Public weather API:
- 10M requests/day
- 100% reads
- Backend: External API ($0.01/request)
- Weather updates hourly
```

**Analysis:**
```
Without Cache:
- 10M requests
- 10M external API calls
- Cost: 10M × $0.01 = $100,000/day!!!
- Latency: 500-1000ms

With Cache (TTL: 10 minutes):
- 10M requests
- 95% cache hit rate (many duplicates)
- 500K external API calls
- Backend cost: 500K × $0.01 = $5,000/day
- Cache cost: $1.52/day (6.1GB)
- Total: $5,001.52/day

Savings: $100,000 - $5,001.52 = $94,998.48/day!!!
ROI: 62,499% return
```

**Decision:** Absolutely use caching

**Configuration:**
```yaml
CacheConfiguration:
  Enabled: true
  CacheTtl: 600  # 10 minutes
  CacheDataEncrypted: true
  CacheClusterSize: '6.1'  # 6.1 GB
  CacheKeyParameters:
    - method.request.querystring.city
    - method.request.querystring.units
```

---

## Cache Invalidation Strategies

### Time-Based (TTL)

**Pros:**
- Automatic
- Simple
- No manual intervention

**Cons:**
- Stale data possible
- Fixed schedule

**Use When:**
- Updates predictable
- Staleness acceptable

### Explicit Invalidation

**Manual Flush:**
```bash
# AWS CLI
aws apigateway flush-stage-cache \
  --rest-api-id abc123 \
  --stage-name prod

# Flushes entire cache
# Use when: major data update
```

**Programmatic:**
```python
import boto3

client = boto3.client('apigateway')

# Flush entire cache
response = client.flush_stage_cache(
    restApiId='abc123',
    stageName='prod'
)

# Call after: database migration, batch updates
```

**Cache-Control Headers:**
```python
# Lambda response - don't cache
return {
    'statusCode': 200,
    'headers': {
        'Cache-Control': 'no-cache'
    },
    'body': json.dumps(data)
}

# Individual response bypasses cache
```

### Write-Through Pattern

**On UPDATE/DELETE:**
```python
def update_product(product_id, data):
    # Update database
    db.update(product_id, data)
    
    # Invalidate cache for this product
    invalidate_cache(f'/products/{product_id}')
    
    return success_response()
```

**Implementation:**
```python
import boto3

def invalidate_cache(resource_path):
    client = boto3.client('apigateway')
    
    # Invalidate specific cache entry
    # Note: AWS doesn't support per-URL invalidation
    # Must flush entire stage cache
    client.flush_stage_cache(
        restApiId=API_ID,
        stageName='prod'
    )
    
    # Alternative: Wait for TTL expiration
```

---

## Monitoring & Optimization

### Key Metrics

**Cache Hit Rate:**
```
Target: > 80% for caching to be worth it

Calculate:
Hit Rate = CacheHitCount / (CacheHitCount + CacheMissCount) × 100%

Example:
800,000 hits + 200,000 misses = 80% hit rate ✓
```

**Cost Effectiveness:**
```
Savings = (CacheHitCount × BackendCost) - CacheCost

Example:
800K hits × $0.20/M = $0.16
Cache cost: $0.48/day
Net: $0.16 - $0.48 = -$0.32/day LOSS ✗

Need higher backend cost or more volume
```

**Latency Improvement:**
```
Cached: ~10-50ms
Uncached: 100-500ms

Improvement: 80-95% faster
```

### CloudWatch Metrics

```python
import boto3

cloudwatch = boto3.client('cloudwatch')

# Get cache metrics
response = cloudwatch.get_metric_statistics(
    Namespace='AWS/ApiGateway',
    MetricName='CacheHitCount',
    Dimensions=[
        {'Name': 'ApiName', 'Value': 'MyApi'},
        {'Name': 'Stage', 'Value': 'prod'}
    ],
    StartTime=datetime.now() - timedelta(days=1),
    EndTime=datetime.now(),
    Period=3600,
    Statistics=['Sum']
)

hit_count = sum([d['Sum'] for d in response['Datapoints']])

# Get cache misses
response = cloudwatch.get_metric_statistics(
    Namespace='AWS/ApiGateway',
    MetricName='CacheMissCount',
    # ... same parameters
)

miss_count = sum([d['Sum'] for d in response['Datapoints']])

hit_rate = hit_count / (hit_count + miss_count) * 100
print(f"Cache Hit Rate: {hit_rate:.2f}%")
```

---

## Best Practices

### 1. Start Small

```
Initial deployment:
- 0.5 GB cache
- Short TTL (5 minutes)
- Monitor hit rate
- Scale up if needed
```

### 2. Per-Method Configuration

```yaml
# Different TTL per endpoint
GET /products → TTL: 3600s  # Stable data
GET /inventory → TTL: 60s   # Changing often
GET /prices → TTL: 300s     # Update frequently
```

### 3. Encryption

```yaml
CacheConfiguration:
  CacheDataEncrypted: true  # Always encrypt
```

### 4. Don't Cache Errors

```python
# Lambda response
if error:
    return {
        'statusCode': 500,
        'headers': {
            'Cache-Control': 'no-cache'  # Don't cache errors
        },
        'body': json.dumps({'error': 'Internal error'})
    }
```

### 5. Monitor Continuously

```
Weekly review:
- Cache hit rate
- Cost effectiveness
- Latency improvements
- Adjust TTL if needed
- Scale cache size if needed
```

---

## Decision Tree

```
START: Considering caching

Q1: Using REST API or HTTP API?
├─ HTTP API → No caching available
└─ REST API → Continue

Q2: What's read-to-write ratio?
├─ < 70% reads → Don't cache
└─ ≥ 70% reads → Continue

Q3: How expensive is backend?
├─ < $0.50/M → Probably not worth it
└─ ≥ $0.50/M → Continue

Q4: How many requests/day?
├─ < 10K → Don't cache (low ROI)
├─ 10K-1M → Maybe (calculate ROI)
└─ > 1M → Continue

Q5: Is data personalized?
├─ Yes → Don't cache (low hit rate)
└─ No → Continue

Q6: How fresh must data be?
├─ Real-time → Don't cache
├─ Minutes → Short TTL cache
└─ Hours/Days → Long TTL cache

RESULT: Enable caching with appropriate TTL
```

---

## Related Topics

- **AWS-APIGateway-Core-Concepts**: Caching basics
- **AWS-APIGateway-DEC-01**: REST vs HTTP API
- **AWS-Lambda-Performance-Tuning**: Backend optimization

---

**END OF FILE**

**Key Decision:**  
Enable caching for high-volume (>1M requests/day), read-heavy (>70%), non-personalized APIs with expensive backends. Start small, monitor hit rate, optimize TTL and size based on metrics.

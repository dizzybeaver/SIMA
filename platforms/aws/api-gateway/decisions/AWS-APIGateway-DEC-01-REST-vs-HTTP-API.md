# AWS-APIGateway-DEC-01-REST-vs-HTTP-API.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Decision framework for choosing REST API vs HTTP API  
**Category:** AWS Platform / API Gateway / Decisions

---

## Decision

**When to use REST APIs vs HTTP APIs in AWS API Gateway**

---

## Context

AWS API Gateway offers two types of RESTful APIs:
- **REST APIs**: Full-featured, more expensive
- **HTTP APIs**: Simplified, 71% cheaper, lower latency

**Cost Difference:**
- REST API: $3.50 per million requests
- HTTP API: $1.00 per million requests
- **Savings: 71% cheaper**

**Latency Difference:**
- HTTP API average: 27% faster than REST API
- Lower overhead, simplified processing

This decision impacts:
- Monthly API costs (significant at scale)
- Response latency (user experience)
- Feature availability (caching, usage plans)
- Implementation complexity

---

## Decision Framework

### Use REST APIs When You Need:

**1. Response Caching**
```
High-read APIs benefit from caching:
- 1M requests/day
- 80% cache hit rate
- 200,000 backend calls saved
- Cache cost: $0.48/day (0.5GB cache)
- Lambda savings: $0.40/day
- Break-even at ~500K requests/day
```

**2. Usage Plans & API Keys**
```
Monetization or partner APIs:
- Per-customer quotas
- API key management
- Usage tracking per key
- Throttling per customer
- Essential for B2B APIs
```

**3. Request/Response Transformation**
```
Complex VTL transformations:
- Legacy system integration
- Format conversions
- Complex data mapping
- Not supported in HTTP APIs
```

**4. Private APIs (VPC Only)**
```
Internal microservices:
- No internet exposure
- VPC endpoint access only
- Enhanced security
- Not supported in HTTP APIs
```

**5. Integration with AWS Services**
```
Direct AWS service integration:
- DynamoDB, SQS, SNS, etc.
- No Lambda required
- Lower latency
- Not well-supported in HTTP APIs
```

### Use HTTP APIs When You Can:

**1. Simple Lambda Proxy**
```
Straightforward Lambda integration:
- Request → Lambda → Response
- No transformation needed
- 71% cost savings
- 27% latency reduction
- Perfect for microservices
```

**2. HTTP Backend Proxy**
```
Proxy to existing HTTP services:
- Microservices architecture
- Internal services
- No transformation required
- Lower cost, lower latency
```

**3. Modern Authentication**
```
JWT/OAuth 2.0 with OIDC:
- Built-in JWT authorizer
- OAuth 2.0 support
- Cognito integration
- Simpler than REST API
```

**4. Cost-Sensitive Applications**
```
High-volume, cost-constrained:
- Startups
- High-traffic APIs
- Internal microservices
- 71% savings significant
```

**5. Low-Latency Requirements**
```
Performance-critical paths:
- Real-time applications
- Mobile backends
- IoT device APIs
- 27% latency improvement
```

---

## Comparison Matrix

| Feature | REST API | HTTP API | Impact |
|---------|----------|----------|--------|
| **Cost** | $3.50/M | $1.00/M | 71% cheaper |
| **Latency** | Higher | 27% faster | Better UX |
| **Caching** | âœ… Yes | ❌ No | Cost optimization |
| **Usage Plans** | âœ… Yes | ❌ No | Monetization |
| **API Keys** | âœ… Yes | ❌ No | Partner tracking |
| **VTL Transform** | âœ… Yes | ❌ No | Legacy integration |
| **Private APIs** | âœ… Yes | ❌ No | Security |
| **AWS Service** | âœ… Full | ⚠️ Limited | Direct integration |
| **JWT Authorizer** | ⚠️ Custom | âœ… Built-in | Auth simplicity |
| **CORS** | Manual | âœ… Auto | Ease of use |

---

## Real-World Examples

### Example 1: Public API with Caching (Use REST API)

**Scenario:**
- Public weather API
- 10M requests/day
- 90% read-only (GET)
- High cache hit potential

**Analysis:**
```
Without Cache (HTTP API):
- 10M requests × $1.00/M = $10/day
- 10M Lambda invocations

With Cache (REST API):
- 10M requests × $3.50/M = $35/day
- 90% cache hit rate
- 1M backend calls
- Cache cost: $0.48/day (0.5GB)
- Total: $35.48/day
- Lambda cost: 1M invocations (vs 10M)
- Lambda savings: ~$18/day

Net: $35.48 - $18 = $17.48/day (REST)
vs $10/day (HTTP)

Decision: Use HTTP API if Lambda cost < $7.48/day
          Use REST API if Lambda cost > $7.48/day
```

### Example 2: Internal Microservices (Use HTTP API)

**Scenario:**
- Internal company microservices
- 5M requests/day
- Lambda proxy only
- No caching needed

**Analysis:**
```
HTTP API: 5M × $1.00/M = $5/day
REST API: 5M × $3.50/M = $17.50/day

Savings: $12.50/day = $375/month = $4,500/year
Plus 27% latency reduction

Decision: HTTP API clear winner
```

### Example 3: Partner API with Usage Plans (Use REST API)

**Scenario:**
- B2B partner API
- 1M requests/day
- Need per-partner quotas
- API key management

**Analysis:**
```
HTTP API:
- Cost: $1/day
- Cannot implement usage plans
- Manual throttling required
- Complex partner management

REST API:
- Cost: $3.50/day
- Built-in usage plans
- Per-partner API keys
- Automatic throttling
- Easy partner management

Decision: REST API required
          $2.50/day premium justified by features
```

### Example 4: Mobile App Backend (Use HTTP API)

**Scenario:**
- Mobile app backend
- 20M requests/day
- JWT authentication
- Simple Lambda proxy

**Analysis:**
```
HTTP API:
- Cost: 20M × $1.00/M = $20/day
- Built-in JWT authorizer
- 27% faster response

REST API:
- Cost: 20M × $3.50/M = $70/day
- Custom Lambda authorizer
- Higher latency

Savings: $50/day = $1,500/month = $18,000/year

Decision: HTTP API clear winner
```

---

## Migration Path

### REST API → HTTP API

**When to Migrate:**
1. No usage plans needed
2. No caching needed
3. No private VPC endpoints
4. Simple Lambda proxy
5. Cost optimization priority

**Migration Steps:**
1. Create new HTTP API
2. Import OpenAPI spec
3. Configure JWT authorizer (if using Cognito)
4. Test thoroughly (especially CORS)
5. Update DNS (canary deploy)
6. Monitor metrics
7. Deprecate REST API

**Gotchas:**
- CORS config different
- Authorizer format different
- Response format unchanged (Lambda proxy)
- Need to update SDK if used

### HTTP API → REST API

**When to Migrate:**
1. Need response caching
2. Need usage plans/API keys
3. Need VTL transformations
4. Need private VPC endpoints
5. Need direct AWS service integration

**Migration Steps:**
1. Create REST API
2. Configure caching/usage plans
3. Set up VTL if needed
4. Test extensively
5. Gradual traffic migration
6. Monitor cost/performance
7. Deprecate HTTP API

---

## Cost Optimization Strategy

### Decision Tree

```
START: New API needed

Q1: Do you need response caching?
├─ YES → REST API (cache optimization)
└─ NO → Continue

Q2: Do you need usage plans or API keys?
├─ YES → REST API (monetization)
└─ NO → Continue

Q3: Do you need VTL transformations?
├─ YES → REST API (transformation)
└─ NO → Continue

Q4: Do you need private VPC endpoints?
├─ YES → REST API (security)
└─ NO → Continue

Q5: Do you need direct AWS service integration?
├─ YES → REST API (service integration)
└─ NO → HTTP API (cost + performance winner)

RESULT: HTTP API unless specific REST features required
```

### Volume-Based Recommendation

```
< 100K requests/day:
- Cost difference negligible
- Choose based on features

100K - 1M requests/day:
- $3-$100/day savings (HTTP API)
- Significant if no REST features needed

1M - 10M requests/day:
- $100-$900/day savings (HTTP API)
- Major cost consideration

> 10M requests/day:
- $900+/day savings (HTTP API)
- Critical cost optimization
- Consider caching ROI carefully
```

---

## Recommendation

**Default Choice: HTTP API**

**Unless you need:**
1. Response caching (high read rate)
2. Usage plans/API keys (B2B/monetization)
3. VTL transformations (legacy integration)
4. Private VPC endpoints (security)
5. Direct AWS service integration

**Rationale:**
- 71% cost savings significant at scale
- 27% latency reduction improves UX
- Simpler configuration
- Modern authentication built-in
- Most applications don't need REST features

**Review Annually:**
- AWS adds HTTP API features regularly
- Feature gap narrowing
- HTTP API capabilities expanding

---

## Related Decisions

- **AWS-Lambda-Core-Concepts**: Backend integration patterns
- **AWS-APIGateway-DEC-02**: Authorization strategy
- **AWS-APIGateway-DEC-03**: Caching strategy

---

**END OF FILE**

**Key Decision Point:**  
Use HTTP API unless you specifically need REST API features (caching, usage plans, VTL, private endpoints). Cost and performance favor HTTP API.

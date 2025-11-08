# AWS-APIGateway-Core-Concepts.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Core concepts and fundamentals of AWS API Gateway  
**Category:** AWS Platform / API Gateway / Core

---

## Overview

AWS API Gateway is a fully managed service that makes it easy to create, publish, maintain, monitor, and secure APIs at any scale. Acts as a "front door" for applications to access data, business logic, or functionality from backend services.

**Key Use Cases:**
- RESTful APIs for serverless applications
- WebSocket APIs for real-time applications
- HTTP APIs for low-latency microservices
- API proxies for existing services

---

## Core Components

### 1. API Types

**REST APIs**
- Request/response model
- Full feature set (caching, throttling, authorizers)
- Resource-based routing
- Integration with AWS services
- More expensive, more features

**HTTP APIs**
- Simplified REST API
- 71% cheaper than REST APIs
- Lower latency (~27% faster)
- Limited features (no caching, usage plans)
- Best for simple proxying

**WebSocket APIs**
- Persistent connections
- Bidirectional communication
- Route-based message handling
- Connection management built-in

### 2. Integration Types

**Lambda Integration (Proxy)**
```
API Gateway → Lambda → Response
- Most common pattern
- Lambda receives full request context
- Lambda returns formatted response
- API Gateway passes through unchanged
```

**Lambda Integration (Non-Proxy)**
```
API Gateway → Transform → Lambda → Transform → Response
- API Gateway transforms request
- Lambda receives simplified input
- API Gateway transforms response
- More control, more complexity
```

**HTTP Integration**
```
API Gateway → HTTP Backend → Response
- Proxy to existing HTTP services
- Can transform request/response
- Useful for microservices
```

**AWS Service Integration**
```
API Gateway → AWS Service → Response
- Direct integration with AWS services
- No Lambda required
- Examples: DynamoDB, S3, SQS, SNS
```

**Mock Integration**
```
API Gateway → Mock Response
- Returns static response
- No backend call
- Useful for testing, CORS preflight
```

### 3. Request Flow

```
Client Request
  ↓
API Gateway Endpoint
  ↓
Method Request (Validation, Authorization)
  ↓
Integration Request (Transformation)
  ↓
Backend Service
  ↓
Integration Response (Transformation)
  ↓
Method Response (Headers, Status)
  ↓
Client Response
```

---

## Key Features

### Request Validation

**Built-in Validation:**
- Request body validation (JSON schema)
- Query parameter validation
- Header validation
- Path parameter validation

**Benefits:**
- Reject invalid requests before reaching backend
- Reduce backend processing costs
- Consistent error responses
- Security hardening

**Example:**
```json
{
  "type": "object",
  "required": ["name", "email"],
  "properties": {
    "name": {"type": "string", "minLength": 1},
    "email": {"type": "string", "format": "email"}
  }
}
```

### Request/Response Transformation

**Velocity Template Language (VTL):**
- Transform request before backend
- Transform response before client
- Map between formats
- Add/remove fields

**Common Use Cases:**
- Convert REST to DynamoDB format
- Add metadata to requests
- Filter sensitive data from responses
- Change response structure

**Example VTL:**
```velocity
{
  "TableName": "Users",
  "Item": {
    "id": {"S": "$context.requestId"},
    "name": {"S": "$input.path('$.name')"},
    "timestamp": {"N": "$context.requestTimeEpoch"}
  }
}
```

### Authorization & Security

**IAM Authorization:**
- AWS signature v4 authentication
- Fine-grained permissions
- Good for AWS services

**Lambda Authorizers (Custom):**
- Custom authorization logic
- Token-based auth (JWT, OAuth)
- Request parameter-based auth
- Cache authorization results

**Cognito User Pools:**
- Built-in user management
- JWT token validation
- User attributes available
- Social identity integration

**API Keys:**
- Simple identification
- Not for security (easily exposed)
- Good for usage tracking
- Combined with usage plans

### Throttling & Rate Limiting

**Account-Level Limits:**
- 10,000 requests per second (RPS) default
- Burst capacity: 5,000 requests
- Can request increase

**Stage-Level Throttling:**
- Set RPS limit per stage
- Applies to all methods

**Method-Level Throttling:**
- Per-method RPS limits
- Override stage settings

**Usage Plans & API Keys:**
- Per-client quotas
- Daily/monthly request limits
- Burst capacity per client
- Monetization support

**Throttling Algorithm:**
```
Token Bucket Algorithm:
- Bucket capacity = burst limit
- Tokens added at rate limit per second
- Request consumes 1 token
- Rejected if bucket empty (429 error)
```

---

## Performance Considerations

### Caching (REST APIs Only)

**Cache Benefits:**
- Reduced backend calls (cost savings)
- Lower latency (faster response)
- Reduced backend load

**Cache Settings:**
- TTL: 0 to 3600 seconds
- Size: 0.5 GB to 237 GB
- Per-stage configuration
- Cache key from request parameters

**Cache Invalidation:**
- TTL expiration (automatic)
- Explicit invalidation (API call)
- Cache key parameters change

**Cost Impact:**
- Cache costs $0.02/hour (0.5 GB)
- Significant savings if high read rate
- Not available on HTTP APIs

### Regional vs Edge-Optimized

**Regional Endpoints:**
- API deployed in specific region
- Lower latency for regional traffic
- Better for services in same region
- No CloudFront distribution

**Edge-Optimized Endpoints:**
- CloudFront distribution included
- Lower latency globally
- Best for global users
- Automatic routing to nearest edge

**Private Endpoints:**
- VPC only access
- No internet exposure
- Interface VPC endpoints
- Enhanced security

---

## Integration Patterns

### Lambda Proxy Pattern (Most Common)

**Request Format:**
```json
{
  "resource": "/users/{id}",
  "path": "/users/123",
  "httpMethod": "GET",
  "headers": {...},
  "queryStringParameters": {...},
  "pathParameters": {"id": "123"},
  "body": null,
  "isBase64Encoded": false
}
```

**Response Format:**
```json
{
  "statusCode": 200,
  "headers": {
    "Content-Type": "application/json"
  },
  "body": "{\"name\":\"John\"}",
  "isBase64Encoded": false
}
```

**Benefits:**
- Simple integration
- Full request context
- Lambda controls response
- Easy to test

### AWS Service Direct Integration

**Example: DynamoDB PutItem**
```
API Gateway → VTL Transform → DynamoDB → Transform → Response

No Lambda Required:
- Lower latency
- Lower cost
- Higher throughput
- More setup complexity
```

**When to Use:**
- Simple CRUD operations
- No business logic needed
- Cost optimization priority
- High throughput requirements

---

## Deployment & Stages

### Stages

**Purpose:**
- Separate environments (dev, staging, prod)
- Different configurations per stage
- Stage variables for environment-specific values
- Independent throttling/caching

**Stage Variables:**
```
Lambda Function: my-function-${stageVariables.env}
HTTP Endpoint: https://${stageVariables.domain}/api
```

### Deployment Process

```
1. Create/modify API resources
2. Deploy to stage (creates snapshot)
3. Stage receives deployment
4. Old deployment remains available
5. Can rollback to previous deployment
```

**Canary Deployments:**
- Route percentage to new version
- Gradually increase traffic
- Monitor metrics
- Full rollback capability

---

## Monitoring & Logging

### CloudWatch Metrics

**Automatic Metrics:**
- `4XXError`: Client errors
- `5XXError`: Server errors
- `Count`: Total API requests
- `Latency`: Request processing time
- `IntegrationLatency`: Backend time
- `CacheHitCount`: Cache hits
- `CacheMissCount`: Cache misses

**Custom Metrics:**
- Via Lambda or backend
- Business-specific tracking

### Access Logging

**Log Format:**
```json
{
  "requestId": "$context.requestId",
  "ip": "$context.identity.sourceIp",
  "requestTime": "$context.requestTime",
  "httpMethod": "$context.httpMethod",
  "routeKey": "$context.routeKey",
  "status": "$context.status",
  "responseLength": "$context.responseLength",
  "integrationLatency": "$context.integrationLatency"
}
```

**CloudWatch Logs:**
- Full request/response logging
- Execution logs
- Error details
- Performance data

### X-Ray Tracing

**Distributed Tracing:**
- End-to-end request tracking
- Integration latency breakdown
- Lambda cold start detection
- Error correlation

---

## Cost Optimization

### Pricing Model

**REST APIs:**
- $3.50 per million requests (first 333M)
- $2.80 per million (next 667M)
- $2.38 per million (over 1B)
- Plus caching costs if enabled

**HTTP APIs:**
- $1.00 per million requests (first 300M)
- $0.90 per million (over 300M)
- 71% cheaper than REST APIs
- No caching, no usage plans

**WebSocket APIs:**
- $1.00 per million messages
- $0.25 per million connection minutes

### Cost Optimization Strategies

**1. Use HTTP APIs When Possible**
- 71% cheaper than REST APIs
- If you don't need: caching, usage plans, API keys
- Most microservices can use HTTP APIs

**2. Implement Caching (REST APIs)**
- Cache frequent responses
- Reduce backend invocations
- Cache costs vs backend costs tradeoff

**3. Request Validation**
- Reject invalid requests at API Gateway
- Prevent unnecessary backend calls
- Reduce Lambda invocations

**4. Direct AWS Service Integration**
- Skip Lambda for simple operations
- Lower latency, lower cost
- DynamoDB direct integration

**5. Optimize Response Size**
- Smaller responses = lower data transfer
- Compression support
- Filter unnecessary fields

---

## Best Practices

### API Design

**RESTful Principles:**
- Resource-based URLs (`/users/{id}`)
- HTTP methods for operations (GET, POST, PUT, DELETE)
- Consistent error responses
- Proper status codes

**Versioning:**
- Path-based: `/v1/users`, `/v2/users`
- Header-based: `Accept: application/vnd.api+json; version=1`
- Stage-based: `dev.api.com`, `prod.api.com`

### Security

**Defense in Depth:**
1. HTTPS only (TLS 1.2+)
2. Authorization (IAM, Cognito, Lambda authorizer)
3. Request validation (reject malformed)
4. Throttling (prevent abuse)
5. WAF integration (DDoS protection)
6. VPC endpoints (private APIs)

**Don't:**
- ❌ Use API keys as primary security
- ❌ Expose sensitive data in URLs
- ❌ Skip request validation
- ❌ Allow unlimited requests

### Error Handling

**Consistent Error Format:**
```json
{
  "error": {
    "code": "INVALID_INPUT",
    "message": "Email format is invalid",
    "field": "email",
    "requestId": "abc-123"
  }
}
```

**Status Codes:**
- 200: Success
- 400: Bad request (client error)
- 401: Unauthorized
- 403: Forbidden
- 404: Not found
- 429: Too many requests
- 500: Internal server error
- 502: Bad gateway (backend error)
- 503: Service unavailable
- 504: Gateway timeout

### Performance

**Optimize Integration Latency:**
- Use regional endpoints for regional traffic
- Minimize VTL transformation complexity
- Cache authorization results
- Keep Lambda functions warm
- Use provisioned concurrency for critical paths

**Reduce Cold Starts:**
- HTTP APIs for lower latency
- Provisioned concurrency on Lambda
- Regional endpoints if appropriate
- Connection reuse in Lambda

---

## Related Topics

- **AWS-Lambda-Core-Concepts**: Backend integration patterns
- **AWS-Lambda-Performance-Tuning**: Optimize Lambda for API Gateway
- **AWS-DynamoDB-Core**: Direct DynamoDB integration patterns

---

**END OF FILE**

**Key Takeaways:**
1. Choose HTTP APIs for cost (71% cheaper) unless need caching/usage plans
2. Request validation at API Gateway reduces backend costs
3. Lambda proxy integration is simplest, direct service integration is cheapest
4. Throttling and authorization are critical for production APIs
5. Monitor with CloudWatch and X-Ray for performance insights

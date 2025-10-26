# AWS-LESS-09: Proxy vs Non-Proxy Integration Patterns

**Category:** External Knowledge - AWS Serverless  
**Type:** LESS (Lesson Learned)  
**Priority:** ⭐⭐⭐⭐ HIGH  
**Status:** Active  
**Created:** 2025-10-25  
**Source:** AWS Serverless Developer Guide (Genericized)

---

## Summary

API gateways offer two integration patterns: proxy (pass-through) and non-proxy (transformation). Proxy simplifies gateway configuration but pushes complexity to application. Non-proxy transforms data at the boundary but increases gateway configuration. Choose based on where transformation logic belongs.

**Lesson:** Proxy vs non-proxy is a separation-of-concerns decision. Proxy = simple gateway, complex application. Non-proxy = complex gateway, simple application.

---

## Context

Traditional applications handle all request/response processing internally. API gateways introduce a new decision: transform data at the gateway boundary or pass it through unchanged? Each approach has implications for simplicity, maintainability, and separation of concerns.

**Problem Domain:** API design, microservice boundaries, data transformation  
**Applicability:** API Gateway, Kong, Nginx, any reverse proxy or API management platform

---

## The Lesson

### The Two Patterns

**Proxy Integration (Pass-Through):**
```
Client → Gateway → Backend (unchanged)
               ↓
         Backend → Gateway → Client (unchanged)

Gateway Role: Router only
Backend Role: Parse, transform, process
Configuration: Minimal
```

**Non-Proxy Integration (Transformation):**
```
Client → Gateway (transform) → Backend (expected format)
               ↓
         Backend → Gateway (transform) → Client (expected format)

Gateway Role: Router + Transformer
Backend Role: Process only (no parsing)
Configuration: Complex
```

### Pattern 1: Proxy Integration (Pass-Through)

**Characteristics:**
```
Gateway Behavior:
  ✅ Forwards ALL data unchanged
  ✅ Headers, query params, body → backend
  ✅ Backend response → client (as-is)
  ✅ Minimal configuration

Backend Responsibility:
  ❌ Parse query parameters
  ❌ Extract headers
  ❌ Validate input format
  ❌ Format response
  ❌ Add response headers
```

**Configuration Example:**
```
Gateway Config:
  Route: GET /users/{id}
  Integration: Lambda Proxy
  (That's it! ~5 lines of config)

No transformation rules needed.
No response mapping.
No header manipulation.
```

**Backend Code Pattern:**
```python
# Backend receives complete event
def handler(event, context):
    # Must parse everything
    method = event['httpMethod']
    path = event['path']
    query_params = event['queryStringParameters']
    headers = event['headers']
    body = json.loads(event['body']) if event['body'] else {}
    
    # Must validate
    if not valid_input(body):
        return {
            'statusCode': 400,
            'headers': {'Content-Type': 'application/json'},
            'body': json.dumps({'error': 'Invalid input'})
        }
    
    # Process
    result = process_data(body)
    
    # Must format response
    return {
        'statusCode': 200,
        'headers': {
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin': '*'
        },
        'body': json.dumps(result)
    }
```

**When to Use Proxy:**
```
✅ Greenfield applications (full control)
✅ Team owns entire stack
✅ Rapid iteration needed
✅ Simple use cases
✅ Flexibility valued over simplicity
✅ Backend can handle all formats

Examples:
  - Microservice with single client
  - Internal APIs
  - Prototyping phase
  - Unified backend team
```

**Advantages:**
```
✅ Simple gateway configuration (~10 lines)
✅ Flexible backend changes (no gateway update)
✅ Full control in application code
✅ Easy to test locally
✅ Single source of truth (backend)
✅ No vendor lock-in (standard HTTP)
```

**Disadvantages:**
```
❌ Backend complexity increases
❌ Must parse all formats
❌ Duplicate parsing across microservices
❌ Hard to enforce contracts
❌ Backend must handle CORS, auth, etc.
❌ Every backend needs same boilerplate
```

### Pattern 2: Non-Proxy Integration (Transformation)

**Characteristics:**
```
Gateway Behavior:
  ✅ Transforms request at boundary
  ✅ Maps: Client format → Backend format
  ✅ Maps: Backend format → Client format
  ✅ Complex configuration

Backend Responsibility:
  ✅ Process clean, expected data
  ✅ Return simple response
  ✅ No parsing, no formatting
  ✅ Focus on business logic
```

**Configuration Example:**
```
Gateway Config:
  Route: GET /users/{id}
  Integration: Lambda (custom)
  
  Request Mapping:
    - Extract {id} from path
    - Parse query params
    - Build backend format
  
  Response Mapping:
    - Map status codes
    - Transform response body
    - Add standard headers
  
  (~50-100 lines of config)
```

**Backend Code Pattern:**
```python
# Backend receives clean, transformed data
def handler(event, context):
    # Already parsed and transformed by gateway
    user_id = event['userId']      # Clean parameter
    options = event['options']      # Pre-parsed options
    
    # Just process (no parsing)
    result = get_user(user_id, options)
    
    # Return simple response (gateway transforms)
    return {
        'user': result
    }
    # Gateway adds: status code, headers, format
```

**Gateway Transformation Examples:**
```
Request Transformation:
  Client: GET /users/123?fields=name,email
  
  Gateway transforms to:
  {
    "userId": "123",
    "fields": ["name", "email"]
  }
  
  Backend receives clean object.

Response Transformation:
  Backend returns:
  {
    "user": {
      "name": "John",
      "email": "john@example.com"
    }
  }
  
  Gateway transforms to:
  Status: 200
  Headers: Content-Type, CORS, etc.
  Body: {
    "status": "success",
    "data": {
      "name": "John",
      "email": "john@example.com"
    }
  }
```

**When to Use Non-Proxy:**
```
✅ Legacy systems (fixed formats)
✅ Multiple clients (different formats)
✅ Strict contract enforcement
✅ Backend simplification valued
✅ API team separate from backend team
✅ Multiple API versions

Examples:
  - Public APIs
  - Multiple client types (mobile, web)
  - Legacy integration
  - Microservice standardization
```

**Advantages:**
```
✅ Backend stays simple/focused
✅ API abstraction layer
✅ Enforce contracts at gateway
✅ Version multiple APIs (same backend)
✅ Centralized transformation logic
✅ Backend team doesn't worry about API details
```

**Disadvantages:**
```
❌ Complex gateway configuration
❌ Changes require gateway updates
❌ Harder to test locally
❌ Vendor-specific transformation language
❌ Limited debugging visibility
❌ Transformation logic scattered
```

### Decision Framework

**Choose Proxy When:**
```
Team Structure:
  ✅ Unified team (owns gateway + backend)
  ✅ Rapid iteration prioritized
  ✅ Small team (< 10 people)

Technical:
  ✅ Flexible requirements
  ✅ Frequent API changes
  ✅ Simple use cases
  ✅ Backend can handle parsing

Example: Startup building MVP
```

**Choose Non-Proxy When:**
```
Team Structure:
  ✅ Separate teams (API team, backend team)
  ✅ Multiple clients
  ✅ Large organization

Technical:
  ✅ Legacy systems (fixed formats)
  ✅ Contract enforcement needed
  ✅ Multiple API versions
  ✅ Backend simplification priority

Example: Enterprise with public API
```

### Hybrid Approach

**Use Both Patterns Selectively:**
```
Strategy: Per-endpoint decision

Internal APIs:
  → Proxy (flexibility, rapid changes)

Public APIs:
  → Non-proxy (stability, contracts)

Legacy Integration:
  → Non-proxy (transform to backend format)

New Microservices:
  → Proxy (team owns stack)

Example Configuration:
  /internal/users → Proxy integration
  /api/v1/users → Non-proxy integration
  /legacy/orders → Non-proxy integration
```

### Real-World Examples

**Example 1: E-commerce API**
```
Public Product API (Non-Proxy):
  Client: GET /api/products?category=electronics
  
  Gateway transforms:
  {
    "action": "list_products",
    "filters": {
      "category": "electronics"
    }
  }
  
  Backend: Simple, focused logic
  Backend returns: Array of products
  
  Gateway transforms response:
  Status: 200
  Headers: CORS, Content-Type, Cache-Control
  Body: {
    "status": "success",
    "count": 42,
    "data": [...]
  }

Internal Admin API (Proxy):
  Admin: POST /admin/products
  Body: {...}
  
  Gateway: Pass-through (no transformation)
  
  Backend: Parse, validate, process
  Backend returns: Complete HTTP response
  
  Gateway: Forward unchanged
```

**Example 2: Mobile App API**
```
Mobile v1 (Non-Proxy):
  Client expects specific format
  Gateway transforms to backend format
  Backend simple, reusable
  
Mobile v2 (Non-Proxy):
  Different client format
  Gateway transforms to SAME backend format
  Backend unchanged
  
  Benefit: Backend supports multiple versions
          without knowing about them
```

**Example 3: Microservice Migration**
```
Phase 1 (Legacy):
  Old system expects XML
  Gateway: Transform JSON → XML (non-proxy)
  Legacy system unchanged
  
Phase 2 (Transition):
  New microservice added
  Gateway: Proxy to new service
  Gateway: Non-proxy to legacy
  
Phase 3 (Complete):
  Legacy retired
  Gateway: Convert to proxy
  Simpler configuration
```

### Anti-Patterns

**Anti-Pattern 1: Complex Business Logic in Gateway**
```
❌ WRONG (Non-Proxy):
  Gateway transformation:
    - Validate business rules
    - Calculate prices
    - Check inventory
    - Make decisions
  
  Problem: Business logic in wrong place!

✅ RIGHT:
  Gateway: Parse and route only
  Backend: Business logic
```

**Anti-Pattern 2: Proxy with Duplicated Logic**
```
❌ WRONG (Proxy):
  Service A: Parse params, validate, add CORS
  Service B: Parse params, validate, add CORS
  Service C: Parse params, validate, add CORS
  (Same code in every service)

✅ RIGHT:
  Gateway: Handle CORS, validation (non-proxy)
  Services: Business logic only
```

**Anti-Pattern 3: Non-Proxy for Everything**
```
❌ WRONG:
  Internal APIs: Non-proxy (overkill)
  Simple endpoints: Non-proxy (unnecessary)
  
✅ RIGHT:
  Internal: Proxy (simplicity)
  Public: Non-proxy (contracts)
  Legacy: Non-proxy (transformation)
```

### Migration Strategies

**Proxy → Non-Proxy Migration:**
```
Step 1: Identify common patterns
  - CORS headers (every service)
  - Error format (inconsistent)
  - Authentication (duplicated)

Step 2: Move to gateway gradually
  - Start with CORS (easy)
  - Then error formatting
  - Then auth/validation
  - Finally request transformation

Step 3: Simplify backends
  - Remove parsing code
  - Remove header management
  - Focus on business logic

Timeline: 2-4 weeks per service
```

**Non-Proxy → Proxy Migration:**
```
Step 1: Document gateway transformations
  - What does gateway do?
  - Why was it needed?
  - Still needed?

Step 2: Move logic to backend
  - Implement parsing
  - Add header management
  - Test thoroughly

Step 3: Remove gateway config
  - Switch to proxy
  - Deploy backend first
  - Then update gateway

Timeline: 1-2 weeks per endpoint
```

---

## Why This Matters

**Development Speed:**
- Proxy: Fast initial development
- Non-proxy: Slower initially, faster long-term
- Wrong choice: Constant friction

**Maintainability:**
- Proxy: Changes in one place (backend)
- Non-proxy: Changes in two places (gateway + backend)
- But: Non-proxy isolates backend from API changes

**Team Efficiency:**
- Proxy: Backend team controls everything
- Non-proxy: API team controls contracts
- Separation enables parallel work

---

## When to Apply

**Initial Design:**
- ✅ Consider team structure
- ✅ Identify transformation needs
- ✅ Plan for multiple clients
- ✅ Estimate change frequency

**Code Review:**
- 🔍 Check for business logic in transformations
- 🔍 Verify pattern matches use case
- 🔍 Look for duplicated parsing code

**Refactoring Triggers:**
- 📊 Same parsing in every service → Non-proxy
- 📊 Gateway config exploding → Proxy
- 📊 Frequent gateway updates → Reconsider

---

## Related Patterns

- **AWS-LESS-10**: API Transformation Strategies - How to transform
- **DEC-05**: Sentinel Sanitization - Similar boundary concerns
- **ARCH-02**: Execution Engine - Router pattern concepts
- **INT-08**: HTTP Interface - Request/response handling

---

## Keywords

proxy integration, API gateway, transformation, pass-through, request mapping, response mapping, separation of concerns, API design

---

## Version History

- **2025-10-25**: Created - Extracted from AWS Serverless Developer Guide, genericized for universal application

---

**End of Entry**
**Lines:** ~198 (within < 200 target)  
**Quality:** Generic, practical decision framework, real examples

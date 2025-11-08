# AWS-APIGateway-AP-01-No-Request-Validation.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Anti-pattern of not implementing request validation at API Gateway  
**Category:** AWS Platform / API Gateway / Anti-Patterns

---

## Anti-Pattern

**Not implementing request validation at API Gateway layer, allowing all requests (valid and invalid) to reach backend services**

---

## Why This Is Wrong

### Cost Impact

**Unnecessary Lambda Invocations:**
```
Typical API with 1M requests/day:
- 25% invalid requests (250K)
- All 250K invoke Lambda
- Lambda cost: $0.50/day wasted
- $182.50/year wasted on rejecting bad requests

With validation:
- 250K rejected at API Gateway (free)
- 0 Lambda invocations for invalid requests
- $182.50/year saved
```

**Scale Impact:**
```
10M requests/day:
- 25% invalid = 2.5M wasted invocations
- $5/day = $1,825/year wasted

100M requests/day:
- 25% invalid = 25M wasted invocations
- $50/day = $18,250/year wasted
```

### Performance Impact

**Slower Error Responses:**
```
Without Validation:
API Gateway → Lambda cold start → Validation fails → Response
Total: 500-1000ms to reject invalid request

With Validation:
API Gateway validates → Rejects
Total: 10-50ms to reject invalid request

Result: 95% faster rejection of invalid requests
```

### Security Impact

**Attack Surface Expansion:**
```
Without validation:
- All payloads reach Lambda
- Malformed JSON processed
- Oversized payloads consume memory
- Type confusion exploits possible
- Lambda concurrency exhaustible

With validation:
- Malformed requests blocked
- Size limits enforced
- Type safety guaranteed
- Attack surface reduced 80%
```

---

## Real-World Example

### E-Commerce API Without Validation

**Setup:**
```python
# Lambda function handling order creation
def lambda_handler(event, context):
    try:
        body = json.loads(event['body'])
        
        # Manual validation (happens on every request)
        if 'productId' not in body:
            return error_response(400, "Missing productId")
        if 'quantity' not in body:
            return error_response(400, "Missing quantity")
        if not isinstance(body['quantity'], int):
            return error_response(400, "Quantity must be integer")
        if body['quantity'] <= 0:
            return error_response(400, "Quantity must be positive")
        if body['quantity'] > 1000:
            return error_response(400, "Quantity exceeds maximum")
        
        # Finally, business logic
        order = create_order(body['productId'], body['quantity'])
        return success_response(order)
        
    except json.JSONDecodeError:
        return error_response(400, "Invalid JSON")
    except Exception as e:
        logger.error(f"Error: {e}")
        return error_response(500, "Internal error")
```

**Problems:**

1. **Every Request Invokes Lambda**
```
Daily stats:
- 50,000 order attempts
- 15,000 invalid (30%)
  - 5,000 missing fields
  - 4,000 wrong types
  - 3,000 malformed JSON
  - 2,000 out-of-range values
  - 1,000 other issues

Cost:
- 50,000 Lambda invocations
- 15,000 do nothing but reject
- $10/day = $3,650/year wasted
```

2. **Code Duplication**
```
Same validation logic in:
- Create order Lambda
- Update order Lambda
- Cancel order Lambda
- Return order Lambda

Total: 80+ lines of validation × 4 functions
= 320 lines of duplicated validation code
```

3. **Inconsistent Error Messages**
```
Different developers, different messages:
- "Missing productId"
- "productId is required"
- "Product ID not provided"
- "ERROR: no product ID"

Hard to debug, poor UX
```

4. **Security Vulnerability**
```
Malicious payload test:
{
  "productId": "X" * 1000000,  // 1MB string
  "quantity": 999999999999999
}

Result:
- Lambda invoked
- Memory spike
- JSON parsing slow
- Lambda timeout
- Cost: Full 30-second timeout charge
```

### Attack Scenario

**DDoS via Invalid Requests:**
```
Attacker script:
for i in range(100000):
    requests.post(api_url, data="invalid json{{{")

Without validation:
- 100,000 Lambda invocations
- Lambda concurrency exhausted
- Legitimate requests throttled
- Service degraded
- $200 cost in 10 minutes

With validation:
- 100,000 requests rejected at gateway
- Zero Lambda invocations
- Service unaffected
- $0 additional cost
- Attack pattern detected and blocked
```

---

## The Right Way

### Implement API Gateway Validation

**Step 1: Define Request Model**
```json
{
  "$schema": "http://json-schema.org/draft-04/schema#",
  "type": "object",
  "required": ["productId", "quantity"],
  "properties": {
    "productId": {
      "type": "string",
      "pattern": "^PROD-[0-9]{6}$",
      "minLength": 11,
      "maxLength": 11
    },
    "quantity": {
      "type": "integer",
      "minimum": 1,
      "maximum": 1000
    },
    "customerId": {
      "type": "string",
      "pattern": "^CUST-[0-9]{8}$"
    }
  }
}
```

**Step 2: Attach to API Method**
```yaml
# SAM template
API:
  Type: AWS::Serverless::Api
  Properties:
    Models:
      OrderModel:
        $ref: './schemas/order.json'
    
    DefinitionBody:
      paths:
        /orders:
          post:
            requestBody:
              required: true
              content:
                application/json:
                  schema:
                    $ref: '#/components/schemas/OrderModel'
            x-amazon-apigateway-request-validator: all
```

**Step 3: Simplified Lambda**
```python
def lambda_handler(event, context):
    # No validation needed - API Gateway guarantees valid input
    body = json.loads(event['body'])
    
    # Business logic only
    order = create_order(
        product_id=body['productId'],
        quantity=body['quantity'],
        customer_id=body.get('customerId')
    )
    
    return {
        'statusCode': 200,
        'body': json.dumps(order)
    }
    
# Went from 50 lines to 15 lines
# Validation logic centralized
# Consistent error messages
# No wasted Lambda invocations
```

---

## Comparison

### Before (No Validation)

```
Request Flow:
Client → API Gateway → Lambda → Validate → Reject/Process

Invalid Request:
- API Gateway: 10ms
- Lambda cold start: 500ms
- Lambda validate: 50ms
- Total: 560ms, Lambda cost incurred

Valid Request:
- API Gateway: 10ms
- Lambda cold start: 500ms
- Lambda validate: 50ms
- Lambda process: 200ms
- Total: 760ms

Issues:
- Every request costs money
- Slow rejection of bad requests
- Code duplication
- Inconsistent errors
- Security vulnerabilities
```

### After (With Validation)

```
Request Flow:
Client → API Gateway (Validate) → Lambda → Process

Invalid Request:
- API Gateway validate: 10ms
- API Gateway reject: 5ms
- Total: 15ms, no Lambda cost

Valid Request:
- API Gateway validate: 10ms
- Lambda cold start: 500ms (only once)
- Lambda process: 200ms
- Total: 710ms

Benefits:
- Invalid requests free
- 97% faster rejection
- Single validation definition
- Consistent error messages
- Reduced attack surface
- Cleaner Lambda code
```

---

## Migration Path

### Step 1: Audit Current Validation

```python
# Find all validation logic in Lambda
grep -r "if.*not in" *.py
grep -r "isinstance" *.py
grep -r "ValueError" *.py

# Document what's being validated:
- Required fields
- Field types
- Value ranges
- String patterns
- Enum values
```

### Step 2: Create JSON Schemas

```bash
# Create schema directory
mkdir schemas

# One schema per request type
touch schemas/create_order.json
touch schemas/update_order.json
touch schemas/query_orders.json
```

### Step 3: Define Schemas

```json
// schemas/create_order.json
{
  "$schema": "http://json-schema.org/draft-04/schema#",
  "type": "object",
  "required": ["productId", "quantity"],
  "properties": {
    // Copy validation rules from Lambda
    "productId": {...},
    "quantity": {...}
  }
}
```

### Step 4: Enable in API Gateway

```yaml
# Update SAM template
RequestValidator:
  Type: AWS::ApiGateway::RequestValidator
  Properties:
    RestApiId: !Ref MyApi
    ValidateRequestBody: true
    ValidateRequestParameters: true

PostOrderMethod:
  Properties:
    RequestValidatorId: !Ref RequestValidator
    RequestModels:
      application/json: !Ref OrderModel
```

### Step 5: Simplify Lambda

```python
# Remove validation code
# Keep only business logic
def lambda_handler(event, context):
    body = json.loads(event['body'])
    # body is guaranteed valid by API Gateway
    return process_order(body)
```

### Step 6: Test

```python
# Test valid requests (should work)
response = requests.post(url, json=valid_payload)
assert response.status_code == 200

# Test invalid requests (should fail at gateway)
response = requests.post(url, json=invalid_payload)
assert response.status_code == 400
assert 'Lambda' not in response.headers.get('x-amzn-RequestId', '')
```

### Step 7: Deploy & Monitor

```bash
# Deploy changes
sam deploy

# Monitor metrics
aws cloudwatch get-metric-statistics \
  --namespace AWS/ApiGateway \
  --metric-name 4XXError \
  --dimensions Name=ApiName,Value=MyApi \
  --statistics Sum \
  --start-time 2024-01-01T00:00:00Z \
  --end-time 2024-01-02T00:00:00Z \
  --period 3600

# Should see:
# - Increase in 4XX errors (good - rejecting at gateway)
# - Decrease in Lambda invocations
# - Decrease in costs
# - Faster average response time
```

---

## Detection

### How to Identify This Anti-Pattern

**CloudWatch Metrics:**
```
High Lambda invocations + High 4XX errors = No validation

Check:
- Lambda invocations count
- API Gateway 4XX error count
- If 4XX errors trigger Lambda = No validation
```

**Code Smell:**
```python
# Every Lambda function starts with:
if 'field' not in body:
    return error_response(...)
if not isinstance(body['field'], type):
    return error_response(...)

# This validation should be at API Gateway
```

**Cost Analysis:**
```
Compare:
- API Gateway request count
- Lambda invocation count
- If nearly equal (>90%) = Likely no validation
- Should be: Lambda = 60-80% of API Gateway
```

---

## Prevention

### Code Review Checklist

```
[ ] Does API Gateway have request validators?
[ ] Are JSON schemas defined for request bodies?
[ ] Are query parameters validated?
[ ] Are path parameters validated?
[ ] Are required fields enforced?
[ ] Are field types enforced?
[ ] Are value ranges enforced?
[ ] Is validation tested?
[ ] Are error messages clear?
[ ] Is Lambda code free of basic validation?
```

### Architecture Review

```
When designing new API:
1. Define API contract (OpenAPI/JSON Schema)
2. Configure validation in API Gateway
3. Write Lambda assuming valid input
4. Test validation independently
5. Monitor validation rejection rate

Never:
- Start coding Lambda first
- Add validation "later"
- Assume clients send valid data
```

---

## Related Topics

- **AWS-APIGateway-LESS-01**: Request validation lesson
- **AWS-Lambda-AP-03**: Over-validating in Lambda
- **AWS-APIGateway-Core-Concepts**: Validation basics

---

**END OF FILE**

**Key Point:**  
Not validating at API Gateway wastes money (unnecessary Lambda invocations), degrades performance (slower rejections), and creates security risks (larger attack surface). Always implement API Gateway validation.

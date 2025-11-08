# AWS-APIGateway-LESS-02-CORS-Configuration.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Proper CORS configuration for web applications  
**Category:** AWS Platform / API Gateway / Lessons

---

## Lesson

**Configure CORS correctly in API Gateway to avoid common frontend integration issues**

---

## Context

**Problem:**  
Web applications calling API from browser get blocked by CORS (Cross-Origin Resource Sharing) policy.

**Common Error:**
```
Access to fetch at 'https://api.example.com/users' from origin 
'https://app.example.com' has been blocked by CORS policy: 
No 'Access-Control-Allow-Origin' header is present on the 
requested resource.
```

**Discovery:**  
Frontend team spent 8 hours debugging "API not working" before realizing it was CORS misconfiguration. API works fine from Postman/curl but fails in browser.

**Impact:**
- Development delays
- Frustrated developers
- "Works on my machine" syndrome
- Support tickets
- Poor user experience

---

## Understanding CORS

### Why CORS Exists

**Security Mechanism:**
```
Browser Security Model:
- Website A (app.example.com) loads in browser
- JavaScript tries to call API at api.example.com
- Browser blocks by default (different origin)
- CORS headers explicitly allow specific origins
```

**Without CORS:**
```
Malicious site (evil.com) could:
1. Load in user's browser
2. Make API calls to api.example.com
3. Steal user's data
4. Perform unauthorized actions
```

**With CORS:**
```
Browser checks CORS headers:
1. Is origin allowed? Check Access-Control-Allow-Origin
2. Is method allowed? Check Access-Control-Allow-Methods
3. Are headers allowed? Check Access-Control-Allow-Headers
4. Only proceed if all checks pass
```

### CORS Flow

**Simple Request (GET/HEAD/POST):**
```
1. Browser: GET /users
   Origin: https://app.example.com

2. API: 200 OK
   Access-Control-Allow-Origin: https://app.example.com
   Content-Type: application/json
   {"users": [...]}

3. Browser: Headers match, allow response
```

**Preflight Request (PUT/DELETE/Custom Headers):**
```
1. Browser: OPTIONS /users (preflight)
   Origin: https://app.example.com
   Access-Control-Request-Method: PUT
   Access-Control-Request-Headers: Authorization, Content-Type

2. API: 200 OK
   Access-Control-Allow-Origin: https://app.example.com
   Access-Control-Allow-Methods: GET, POST, PUT, DELETE
   Access-Control-Allow-Headers: Authorization, Content-Type
   Access-Control-Max-Age: 3600

3. Browser: Preflight passed

4. Browser: PUT /users (actual request)
   Origin: https://app.example.com
   Authorization: Bearer token

5. API: 200 OK
   Access-Control-Allow-Origin: https://app.example.com
   {"updated": true}

6. Browser: Allow response
```

---

## HTTP API CORS (Automatic - Preferred)

### Configuration

**SAM Template:**
```yaml
API:
  Type: AWS::Serverless::HttpApi
  Properties:
    CorsConfiguration:
      AllowOrigins:
        - https://app.example.com
        - https://staging.example.com
      AllowMethods:
        - GET
        - POST
        - PUT
        - DELETE
        - OPTIONS
      AllowHeaders:
        - Content-Type
        - Authorization
        - X-Api-Key
      MaxAge: 3600
      AllowCredentials: true
```

**CloudFormation:**
```yaml
API:
  Type: AWS::ApiGatewayV2::Api
  Properties:
    Name: MyHttpApi
    ProtocolType: HTTP
    CorsConfiguration:
      AllowOrigins:
        - https://app.example.com
      AllowMethods:
        - '*'
      AllowHeaders:
        - '*'
      MaxAge: 300
```

**Benefits:**
- Automatic OPTIONS responses
- No Lambda invocation for preflight
- Consistent CORS headers
- Simple configuration
- Fast (no backend call)

---

## REST API CORS (Manual - Required)

### Step 1: Enable CORS on Resource

**Console:**
```
1. Select resource (e.g., /users)
2. Actions → Enable CORS
3. Configure allowed origins, methods, headers
4. Click "Enable CORS and replace existing CORS headers"
```

**CloudFormation:**
```yaml
UsersOptions:
  Type: AWS::ApiGateway::Method
  Properties:
    RestApiId: !Ref RestApi
    ResourceId: !Ref UsersResource
    HttpMethod: OPTIONS
    AuthorizationType: NONE
    Integration:
      Type: MOCK
      RequestTemplates:
        application/json: '{"statusCode": 200}'
      IntegrationResponses:
        - StatusCode: 200
          ResponseParameters:
            method.response.header.Access-Control-Allow-Headers: "'Content-Type,Authorization'"
            method.response.header.Access-Control-Allow-Methods: "'GET,POST,PUT,DELETE,OPTIONS'"
            method.response.header.Access-Control-Allow-Origin: "'https://app.example.com'"
          ResponseTemplates:
            application/json: ''
    MethodResponses:
      - StatusCode: 200
        ResponseParameters:
          method.response.header.Access-Control-Allow-Headers: true
          method.response.header.Access-Control-Allow-Methods: true
          method.response.header.Access-Control-Allow-Origin: true
```

### Step 2: Add CORS Headers to Actual Methods

**Lambda Response:**
```python
def lambda_handler(event, context):
    # Business logic
    result = get_users()
    
    # Return with CORS headers
    return {
        'statusCode': 200,
        'headers': {
            'Access-Control-Allow-Origin': 'https://app.example.com',
            'Access-Control-Allow-Credentials': 'true',
            'Content-Type': 'application/json'
        },
        'body': json.dumps(result)
    }
```

**Gateway Response:**
```yaml
# Add to all methods
GetUsersMethod:
  Type: AWS::ApiGateway::Method
  Properties:
    # ... other properties
    MethodResponses:
      - StatusCode: 200
        ResponseParameters:
          method.response.header.Access-Control-Allow-Origin: true
    Integration:
      # ... other properties
      IntegrationResponses:
        - StatusCode: 200
          ResponseParameters:
            method.response.header.Access-Control-Allow-Origin: "'https://app.example.com'"
```

---

## Common Configurations

### Development (All Origins)

```yaml
# HTTP API
CorsConfiguration:
  AllowOrigins:
    - '*'  # Allow all origins
  AllowMethods:
    - '*'  # Allow all methods
  AllowHeaders:
    - '*'  # Allow all headers
  MaxAge: 300

# WARNING: Only for development
# Never use in production
```

### Production (Specific Origins)

```yaml
# HTTP API
CorsConfiguration:
  AllowOrigins:
    - https://app.example.com
    - https://www.example.com
  AllowMethods:
    - GET
    - POST
    - PUT
    - DELETE
    - OPTIONS
  AllowHeaders:
    - Content-Type
    - Authorization
    - X-Api-Key
  MaxAge: 3600
  AllowCredentials: true
```

### Multiple Environments

```yaml
# SAM with stage variables
CorsConfiguration:
  AllowOrigins:
    - !Sub 'https://${Environment}.example.com'
  AllowMethods:
    - GET
    - POST
    - PUT
    - DELETE
  AllowHeaders:
    - Content-Type
    - Authorization
  MaxAge: 3600
```

---

## Real-World Examples

### Example 1: React App + API Gateway

**Problem:**
```javascript
// React app (https://app.example.com)
fetch('https://api.example.com/users')
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('CORS error:', error));

// Error: CORS policy blocked
```

**Solution (HTTP API):**
```yaml
API:
  Type: AWS::Serverless::HttpApi
  Properties:
    CorsConfiguration:
      AllowOrigins:
        - https://app.example.com
      AllowMethods:
        - GET
      AllowHeaders:
        - Content-Type
```

**Result:**
```javascript
// Now works!
fetch('https://api.example.com/users')
  .then(response => response.json())
  .then(data => console.log(data));
// Success: [{id: 1, name: "Alice"}, ...]
```

### Example 2: Authentication Headers

**Problem:**
```javascript
// React with JWT token
fetch('https://api.example.com/protected', {
  headers: {
    'Authorization': 'Bearer eyJhbGc...',
    'Content-Type': 'application/json'
  }
})
// Fails: Authorization header not allowed
```

**Solution:**
```yaml
CorsConfiguration:
  AllowOrigins:
    - https://app.example.com
  AllowMethods:
    - GET
    - POST
  AllowHeaders:
    - Content-Type
    - Authorization  # Must explicitly allow
  AllowCredentials: true  # Required for auth
  MaxAge: 3600
```

### Example 3: Custom Headers

**Problem:**
```javascript
// App sends custom header
fetch('https://api.example.com/data', {
  headers: {
    'X-Api-Key': 'my-key',
    'X-Request-Id': '12345'
  }
})
// Fails: Custom headers not allowed
```

**Solution:**
```yaml
CorsConfiguration:
  AllowHeaders:
    - Content-Type
    - X-Api-Key
    - X-Request-Id
  # Or allow all (less secure):
  AllowHeaders:
    - '*'
```

---

## Troubleshooting

### Issue 1: "No 'Access-Control-Allow-Origin' header"

**Symptoms:**
```
Browser console:
Access to fetch at 'https://api.example.com' has been blocked 
by CORS policy: No 'Access-Control-Allow-Origin' header is 
present on the requested resource.
```

**Causes:**
1. CORS not configured at all
2. Origin not in allowed list
3. Headers only on OPTIONS, not on actual method
4. Lambda not returning CORS headers (REST API)

**Fix:**
```yaml
# HTTP API - automatic
CorsConfiguration:
  AllowOrigins:
    - https://app.example.com

# REST API - manual
1. Enable CORS on resource (OPTIONS method)
2. Add headers to Lambda response
3. Add headers to Gateway Response
```

### Issue 2: Preflight Succeeds but Actual Request Fails

**Symptoms:**
```
OPTIONS /users → 200 OK (works)
GET /users → CORS error (fails)
```

**Cause:**
```
REST API only:
- OPTIONS method has CORS headers
- GET method missing CORS headers
```

**Fix:**
```python
# Lambda must return CORS headers on EVERY response
def lambda_handler(event, context):
    return {
        'statusCode': 200,
        'headers': {
            'Access-Control-Allow-Origin': 'https://app.example.com',
            # ^^^ Must be on every response
            'Content-Type': 'application/json'
        },
        'body': json.dumps(data)
    }
```

### Issue 3: "Wildcard in Allow-Origin with Credentials"

**Symptoms:**
```
CORS error: The value of the 'Access-Control-Allow-Origin' 
header must not be the wildcard '*' when the request's 
credentials mode is 'include'.
```

**Cause:**
```yaml
# Invalid combination
CorsConfiguration:
  AllowOrigins:
    - '*'  # Wildcard
  AllowCredentials: true  # Can't use both
```

**Fix:**
```yaml
# Specify exact origins
CorsConfiguration:
  AllowOrigins:
    - https://app.example.com
    - https://www.example.com
  AllowCredentials: true
```

### Issue 4: Multiple Origins Need Different Responses

**Problem:**
```
Need to support:
- https://app.example.com
- https://admin.example.com
- https://partner.example.com

But CORS header can only be single value
```

**Solution (Lambda):**
```python
ALLOWED_ORIGINS = [
    'https://app.example.com',
    'https://admin.example.com',
    'https://partner.example.com'
]

def lambda_handler(event, context):
    origin = event['headers'].get('origin', '')
    
    # Check if origin is allowed
    if origin in ALLOWED_ORIGINS:
        allowed_origin = origin
    else:
        allowed_origin = ALLOWED_ORIGINS[0]  # Default
    
    return {
        'statusCode': 200,
        'headers': {
            'Access-Control-Allow-Origin': allowed_origin,
            'Content-Type': 'application/json'
        },
        'body': json.dumps(data)
    }
```

---

## Best Practices

### 1. Use HTTP API When Possible

```
HTTP API advantages:
- Automatic CORS handling
- No Lambda invocation for OPTIONS
- Simpler configuration
- More reliable
- Faster

REST API only if:
- Need complex transformations
- Need usage plans
- Need caching
```

### 2. Be Specific in Production

```
❌ DON'T (Production):
AllowOrigins:
  - '*'  # Too permissive

✅ DO (Production):
AllowOrigins:
  - https://app.example.com
  - https://www.example.com
```

### 3. Minimize Allowed Headers

```
❌ DON'T:
AllowHeaders:
  - '*'  # Security risk

✅ DO:
AllowHeaders:
  - Content-Type
  - Authorization
  - X-Api-Key
  # Only what you actually use
```

### 4. Use Appropriate MaxAge

```
Development:
MaxAge: 60  # 1 minute (frequent changes)

Production:
MaxAge: 3600  # 1 hour (cache preflight)
```

### 5. Test in Browser

```bash
# cURL doesn't enforce CORS
curl https://api.example.com  # Works

# Browser enforces CORS
# Test in actual browser console
```

---

## Testing CORS

### Manual Test

```javascript
// Browser console (on different origin)
fetch('https://api.example.com/users', {
  method: 'GET',
  headers: {
    'Content-Type': 'application/json'
  }
})
.then(r => r.json())
.then(d => console.log('Success:', d))
.catch(e => console.error('CORS error:', e));
```

### Automated Test

```javascript
// Jest test
describe('CORS Configuration', () => {
  it('should allow requests from app.example.com', async () => {
    const response = await fetch('https://api.example.com/users', {
      method: 'GET',
      headers: {
        'Origin': 'https://app.example.com'
      }
    });
    
    expect(response.headers.get('access-control-allow-origin'))
      .toBe('https://app.example.com');
  });
  
  it('should block requests from unauthorized origin', async () => {
    const response = await fetch('https://api.example.com/users', {
      method: 'GET',
      headers: {
        'Origin': 'https://evil.com'
      }
    });
    
    expect(response.headers.get('access-control-allow-origin'))
      .toBeNull();
  });
});
```

---

## Migration Checklist

```
[ ] Identify all frontend origins
[ ] Choose HTTP API vs REST API
[ ] Configure allowed origins
[ ] Configure allowed methods
[ ] Configure allowed headers
[ ] Set appropriate MaxAge
[ ] Enable credentials if needed
[ ] Test from browser (not curl)
[ ] Test preflight requests
[ ] Test actual requests
[ ] Verify error responses have CORS
[ ] Document CORS configuration
[ ] Update frontend code
```

---

## Related Topics

- **AWS-APIGateway-Core-Concepts**: CORS basics
- **AWS-APIGateway-DEC-01**: REST vs HTTP API choice
- **AWS-APIGateway-AP-02**: Incorrect CORS configuration

---

**END OF FILE**

**Key Takeaway:**  
CORS configuration is critical for web applications. Use HTTP API for automatic handling when possible. Always test in browser, not just curl/Postman. Be specific about allowed origins in production.

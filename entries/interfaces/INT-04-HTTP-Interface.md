# File: INT-04-HTTP-Interface.md

**REF-ID:** INT-04  
**Version:** 1.0.0  
**Category:** Interface Pattern  
**Status:** Active  
**Priority:** 🔴 CRITICAL  
**Created:** 2025-11-01  
**Contributors:** SIMA Learning Mode

---

## 📋 OVERVIEW

**Interface Name:** HTTP  
**Short Code:** HTTP  
**Type:** External Communication Interface  
**Dependency Layer:** Layer 3 (External Communication)

**One-Line Description:**  
HTTP interface handles all external HTTP requests/responses for API communication.

**Primary Purpose:**  
Provide unified HTTP client functionality with connection pooling, retry logic, timeout management, and error handling for external API calls.

---

## 🎯 CORE RESPONSIBILITIES

### 1. HTTP Request Management
- Execute GET, POST, PUT, DELETE, PATCH requests
- Handle request headers and authentication
- Manage request bodies (JSON, form data)
- URL encoding and query parameters

### 2. Connection Management
- Connection pooling for efficiency
- Keep-alive management
- SSL/TLS certificate validation
- Connection timeout configuration

### 3. Response Handling
- Parse HTTP responses
- Status code interpretation
- Response body parsing (JSON, text, binary)
- Response header extraction

### 4. Error Management
- Network error handling
- Timeout management
- Retry logic with exponential backoff
- Circuit breaker integration

---

## 🔑 KEY RULES

### Rule 1: Always Use Connection Pooling
**What:** Reuse HTTP connections instead of creating new ones for each request.

**Why:** Connection creation is expensive (TCP handshake, TLS negotiation).

**Impact:** 50-200ms saved per request.

**Example:**
```python
# ❌ DON'T: Create new connection each time
import requests
response = requests.get(url)  # New connection every time

# ✅ DO: Use session (connection pooling)
from gateway import http_get
response = http_get(url)  # Reuses connections
```

---

### Rule 2: Set Appropriate Timeouts
**What:** All HTTP requests MUST have explicit timeout values.

**Why:** Prevents indefinite hangs. Lambda has 30s max execution.

**Standard Timeouts:**
- **Connect:** 3-5 seconds (time to establish connection)
- **Read:** 10-25 seconds (time to receive response)
- **Total:** Never exceed 25s (leave 5s buffer for Lambda)

**Example:**
```python
# ❌ DON'T: No timeout (can hang forever)
response = requests.get(url)

# ✅ DO: Explicit timeout
from gateway import http_get
response = http_get(url, timeout=(5, 20))  # (connect, read)
```

---

### Rule 3: Handle All HTTP Status Codes
**What:** Check response status codes and handle appropriately.

**Status Code Categories:**
- **2xx:** Success - Process response
- **3xx:** Redirect - Follow or report
- **4xx:** Client error - Log and fail gracefully
- **5xx:** Server error - Retry with backoff

**Example:**
```python
response = http_get(url)

if 200 <= response.status_code < 300:
    return response.json()
elif 400 <= response.status_code < 500:
    log_error(f"Client error: {response.status_code}")
    raise ValueError("Invalid request")
elif 500 <= response.status_code < 600:
    log_error(f"Server error: {response.status_code}")
    # Retry logic here
```

---

### Rule 4: Retry with Exponential Backoff
**What:** Automatically retry failed requests with increasing delays.

**Strategy:**
- **Attempt 1:** Immediate
- **Attempt 2:** Wait 1 second
- **Attempt 3:** Wait 2 seconds
- **Attempt 4:** Wait 4 seconds
- **Max attempts:** 3-5 depending on criticality

**Example:**
```python
# ✅ Built into http_request_with_retry()
from gateway import http_request_with_retry

response = http_request_with_retry(
    url=api_url,
    method="POST",
    max_retries=3,
    backoff_factor=1.0
)
```

---

### Rule 5: Sanitize External Responses
**What:** Never trust external API responses. Validate and sanitize before use.

**Validation Checklist:**
- Verify expected fields exist
- Check data types match expectations
- Validate ranges (e.g., positive numbers)
- Escape strings before logging
- Handle null/missing values

---

## 🎨 MAJOR BENEFITS

### Benefit 1: Centralized HTTP Logic
- All HTTP code in one interface
- Consistent error handling
- Single point for monitoring/logging
- Easy to add features (auth, retries, etc.)

### Benefit 2: Connection Efficiency
- Connection pooling reduces latency by 50-80%
- Lower Lambda execution time
- Lower cost per invocation
- Better throughput

### Benefit 3: Reliability
- Automatic retry logic
- Circuit breaker integration
- Timeout management
- Graceful degradation

### Benefit 4: Security
- SSL/TLS certificate validation
- Response sanitization
- Request authentication
- Secrets management integration

---

## 📚 CORE FUNCTIONS

### Gateway Functions (Lazy-Loaded)

```python
# Request functions
http_get(url, headers=None, params=None, timeout=(5,20))
http_post(url, json=None, data=None, headers=None, timeout=(5,20))
http_put(url, json=None, data=None, headers=None, timeout=(5,20))
http_delete(url, headers=None, timeout=(5,20))
http_patch(url, json=None, headers=None, timeout=(5,20))

# Advanced functions
http_request_with_retry(url, method, max_retries=3, **kwargs)
http_download_file(url, destination, chunk_size=8192)
http_upload_file(url, file_path, field_name="file")

# Session management
http_create_session(base_url=None, default_headers=None)
http_close_session()

# Utility functions
http_build_query_string(params)
http_parse_url(url)
http_validate_response(response, expected_status=200)
```

---

## 🔄 USAGE PATTERNS

### Pattern 1: Simple GET Request
```python
from gateway import http_get, log_info

def fetch_user_data(user_id):
    url = f"https://api.example.com/users/{user_id}"
    
    try:
        response = http_get(url, timeout=(3, 15))
        
        if response.status_code == 200:
            return response.json()
        else:
            log_info(f"Failed to fetch user: {response.status_code}")
            return None
            
    except Exception as e:
        log_error(f"HTTP request failed: {e}")
        return None
```

### Pattern 2: POST with JSON Body
```python
from gateway import http_post

def create_user(name, email):
    url = "https://api.example.com/users"
    
    payload = {
        "name": name,
        "email": email
    }
    
    response = http_post(
        url, 
        json=payload,
        headers={"Authorization": "Bearer TOKEN"},
        timeout=(5, 20)
    )
    
    return response.json() if response.status_code == 201 else None
```

### Pattern 3: Retry on Failure
```python
from gateway import http_request_with_retry

def fetch_with_retry(url):
    response = http_request_with_retry(
        url=url,
        method="GET",
        max_retries=3,
        backoff_factor=2.0,  # 2s, 4s, 8s delays
        timeout=(5, 20)
    )
    
    return response.json()
```

### Pattern 4: Session for Multiple Requests
```python
from gateway import http_create_session, http_get, http_close_session

def fetch_multiple_endpoints():
    # Create session with base URL and auth
    session = http_create_session(
        base_url="https://api.example.com",
        default_headers={"Authorization": "Bearer TOKEN"}
    )
    
    try:
        users = http_get("/users")  # Uses session
        posts = http_get("/posts")  # Reuses connection
        comments = http_get("/comments")  # Reuses connection
        
        return {
            "users": users.json(),
            "posts": posts.json(),
            "comments": comments.json()
        }
    finally:
        http_close_session()  # Always close
```

---

## ⚠️ ANTI-PATTERNS

### Anti-Pattern 1: No Timeout ❌
```python
# ❌ DON'T: Can hang indefinitely
response = requests.get(url)

# ✅ DO: Always set timeout
response = http_get(url, timeout=(5, 20))
```

### Anti-Pattern 2: Creating New Connections ❌
```python
# ❌ DON'T: New connection overhead every time
for item in items:
    response = requests.get(f"https://api.com/{item}")

# ✅ DO: Use session for multiple requests
session = http_create_session("https://api.com")
for item in items:
    response = http_get(f"/{item}")  # Reuses connection
http_close_session()
```

### Anti-Pattern 3: Ignoring Status Codes ❌
```python
# ❌ DON'T: Assume success
response = http_get(url)
data = response.json()  # Might fail if 404, 500, etc.

# ✅ DO: Check status code
response = http_get(url)
if response.status_code == 200:
    data = response.json()
else:
    handle_error(response)
```

### Anti-Pattern 4: No Retry Logic ❌
```python
# ❌ DON'T: Single attempt for critical operations
response = http_get(critical_url)

# ✅ DO: Retry transient failures
response = http_request_with_retry(critical_url, max_retries=3)
```

---

## 🔗 CROSS-REFERENCES

**Related Architecture:**
- ARCH-01 (SUGA Pattern): HTTP is Layer 3 interface
- ARCH-04 (ZAPH): Fast path for cached HTTP responses

**Related Interfaces:**
- INT-02 (Logging): Log all HTTP requests/responses
- INT-03 (Security): SSL validation, auth headers
- INT-07 (Metrics): Track HTTP latency, errors
- INT-12 (Circuit Breaker): Prevent cascading failures

**Related Patterns:**
- GATE-02 (Lazy Loading): Load requests library on demand
- GATE-03 (Cross-Interface): HTTP + Logging + Security

**Related Lessons:**
- LESS-15 (File Fetch Verification): Always fetch before modifying
- LESS-25 (Timeout Lessons): Always set timeouts

**Related Decisions:**
- DEC-08 (External Libraries): Use requests library
- DEC-12 (Retry Strategy): 3 attempts with exponential backoff

---

## ✅ VERIFICATION CHECKLIST

Before using HTTP interface:
- [ ] Timeout configured (connect + read)
- [ ] Error handling in place
- [ ] Retry logic for critical operations
- [ ] Status codes checked
- [ ] Responses validated
- [ ] Connection pooling used (session)
- [ ] SSL certificates validated
- [ ] Secrets not hardcoded
- [ ] Logging integrated
- [ ] Metrics tracked

---

**END OF INTERFACE ENTRY**

**REF-ID:** INT-04  
**Status:** Active  
**Lines:** 385
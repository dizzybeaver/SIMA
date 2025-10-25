# NM05-AntiPatterns-Security_AP-17.md - AP-17

# AP-17: No Input Validation

**Category:** NM05 - Anti-Patterns
**Topic:** Security
**Priority:** 🔴 CRITICAL
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Accepting and using external input without validation creates serious security vulnerabilities including injection attacks, SSRF, path traversal, and arbitrary code execution.

---

## Context

External input (from HTTP requests, user data, environment variables, external APIs) is untrusted and potentially malicious. Using it directly without validation is a critical security vulnerability.

**Problem:** Security breaches, data leaks, unauthorized access, system compromise.

---

## Content

### The Anti-Pattern

```python
# ❌ CRITICAL SECURITY VULNERABILITY
def process_url(event):
    # Directly using user input!
    url = event['url']
    response = gateway.http_get(url)  # No validation!
    return response

def read_file(event):
    # Directly using user input!
    filename = event['filename']
    with open(f"/data/{filename}") as f:  # Path traversal risk!
        return f.read()

def execute_query(event):
    # Directly using user input!
    query = event['query']
    results = db.execute(query)  # SQL injection risk!
    return results
```

**Vulnerabilities Created:**
1. **SSRF** (Server-Side Request Forgery) - Can access internal services
2. **Path Traversal** - Can read arbitrary files
3. **SQL Injection** - Can modify database
4. **Command Injection** - Can execute arbitrary code
5. **XSS** (Cross-Site Scripting) - Can inject malicious scripts

### Real Attack Examples

**SSRF Attack:**
```python
# Attacker sends:
event = {
    'url': 'http://169.254.169.254/latest/meta-data/iam/security-credentials/'
}
# Result: Lambda returns AWS credentials!
```

**Path Traversal Attack:**
```python
# Attacker sends:
event = {
    'filename': '../../../etc/passwd'
}
# Result: Reads system password file!
```

**SQL Injection Attack:**
```python
# Attacker sends:
event = {
    'query': "'; DROP TABLE users; --"
}
# Result: Deletes entire users table!
```

### Why This Is Critical

**1. System Compromise**
- Attackers gain unauthorized access
- Can read sensitive data
- Can modify or delete data
- Can execute arbitrary code

**2. Data Breaches**
- Internal credentials exposed
- User data leaked
- Configuration secrets stolen

**3. Regulatory Violations**
- GDPR violations
- PCI-DSS violations
- Legal liability

**4. Cascading Failures**
- One vulnerable endpoint compromises entire system
- Lateral movement to other services
- Complete infrastructure compromise

### Correct Approach

**Step 1: Validate All Input**
```python
# ✅ CORRECT - Validate before using
def process_url(event):
    url = event.get('url')
    
    # 1. Validate presence
    if not url:
        raise ValueError("URL required")
    
    # 2. Validate format
    if not url.startswith(('http://', 'https://')):
        raise ValueError("Invalid URL scheme")
    
    # 3. Validate against whitelist
    allowed_domains = ['api.example.com', 'public-api.example.com']
    parsed = urllib.parse.urlparse(url)
    if parsed.hostname not in allowed_domains:
        raise ValueError(f"Domain not allowed: {parsed.hostname}")
    
    # NOW safe to use
    response = gateway.http_get(url)
    return response
```

**Step 2: Sanitize Input**
```python
# ✅ CORRECT - Sanitize file paths
def read_file(event):
    filename = event.get('filename')
    
    # 1. Validate filename
    if not filename or not filename.isalnum():
        raise ValueError("Invalid filename")
    
    # 2. Prevent path traversal
    if '..' in filename or '/' in filename:
        raise ValueError("Path traversal attempt detected")
    
    # 3. Build safe path
    safe_path = os.path.join('/data', filename)
    
    # 4. Verify path is within allowed directory
    real_path = os.path.realpath(safe_path)
    if not real_path.startswith('/data/'):
        raise ValueError("Access denied")
    
    # NOW safe to use
    with open(real_path) as f:
        return f.read()
```

**Step 3: Use Parameterized Queries**
```python
# ✅ CORRECT - Parameterized query
def get_user(event):
    username = event.get('username')
    
    # 1. Validate input
    if not username or not username.isalnum():
        raise ValueError("Invalid username")
    
    # 2. Use parameterized query (not string formatting!)
    query = "SELECT * FROM users WHERE username = ?"
    results = db.execute(query, (username,))  # Safe!
    return results
```

### SUGA-ISP Validation Functions

**Built-in Security Interface:**
```python
# Use gateway security functions
import gateway

# URL validation
gateway.validate_url(url)

# Input sanitization
clean_input = gateway.sanitize_input(user_input)

# Parameter validation
gateway.validate_parameter(value, expected_type, allowed_values)
```

### Validation Checklist

**For every external input, validate:**
- [ ] **Presence** - Is required data present?
- [ ] **Type** - Is it the expected type (string, int, etc.)?
- [ ] **Format** - Does it match expected format (URL, email, etc.)?
- [ ] **Length** - Is it within reasonable bounds?
- [ ] **Content** - Does it contain only allowed characters?
- [ ] **Range** - Is numeric value within allowed range?
- [ ] **Whitelist** - Is value in list of allowed values?

### Common Validation Patterns

**URL Validation:**
```python
def validate_url(url):
    # Must be HTTPS
    # Must be from allowed domains
    # Must not contain suspicious patterns
    pass
```

**File Path Validation:**
```python
def validate_filepath(path):
    # No ../ sequences
    # No absolute paths
    # Within allowed directory
    # Filename matches pattern
    pass
```

**Numeric Validation:**
```python
def validate_number(value, min_val, max_val):
    # Must be numeric
    # Must be within range
    # Must be positive (if applicable)
    pass
```

**String Validation:**
```python
def validate_string(value, pattern, max_length):
    # Must match regex pattern
    # Must not exceed max length
    # Must not contain dangerous characters
    pass
```

### Defense in Depth

**Layer 1: Input Validation (This anti-pattern)**
```python
# Validate at entry point
validate_input(event)
```

**Layer 2: Output Encoding**
```python
# Encode output to prevent XSS
encode_for_html(output)
```

**Layer 3: Least Privilege**
```python
# Lambda has minimal permissions
# Can only access what it needs
```

**Layer 4: Monitoring**
```python
# Log suspicious input
gateway.log_warning(f"Suspicious input: {input}")
```

---

## Related Topics

- **AP-18**: Hardcoded Secrets - Another critical security issue
- **AP-19**: SQL Injection Patterns - Specific injection vulnerability
- **DEC-11**: Security-First Design - Why security is built-in
- **INT-03**: SECURITY Interface - How to use security functions
- **LESS-10**: Security Lessons - Real security incidents

---

## Keywords

input validation, security vulnerability, SSRF, path traversal, SQL injection, command injection, XSS, sanitization, whitelist validation

---

## Version History

- **2025-10-24**: Created - Extracted from Part 2, added attack examples and validation patterns

---

**File:** `NM05-AntiPatterns-Security_AP-17.md`
**End of Document**

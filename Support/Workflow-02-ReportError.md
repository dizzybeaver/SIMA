# Workflow-02-ReportError.md
**Error Reporting and Debugging - Step-by-Step**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: Systematic troubleshooting and error resolution

---

## 🎯 TRIGGERS

- "I'm getting error [X]"
- "Lambda is returning 500"
- "Function [Y] is failing"
- "Why isn't [X] working?"
- "Help! Production is broken"
- "[Stack trace or error message]"

---

## ⚡ DECISION TREE

```
User reports error
    ↓
Step 1: Gather Context
    → Error message/stack trace
    → What operation was attempted?
    → Environment (dev/prod/test)?
    → CloudWatch logs available?
    ↓
Step 2: Check Known Bugs
    → Search NM06-Bugs-Critical_Index.md
    → Match error pattern
    ↓
Known bug? → Provide fix from BUG-## + DONE
    ↓
Step 3: Analyze Error Type
    → Import error? → Workflow-07
    → Sentinel error? → BUG-01
    → Permission error? → Check IAM
    → Timeout? → Workflow-08
    → Other? → Continue
    ↓
Step 4: Trace Through Layers
    → Gateway layer issue?
    → Interface layer issue?
    → Core implementation issue?
    → External dependency issue?
    ↓
Step 5: Diagnose Root Cause
    → Check anti-patterns violated
    → Check design decisions
    → Review error handling
    ↓
Step 6: Provide Solution
    → Immediate fix
    → Long-term solution
    → Prevention strategy
```

---

## 📋 STEP-BY-STEP PROCESS

### Step 1: Gather Context (1 minute)

**Essential information:**

**Error Details:**
- Exact error message
- Full stack trace if available
- Error code (500, 403, etc.)

**Operational Context:**
- What were you trying to do?
- What input/parameters were used?
- Expected vs actual behavior

**Environment:**
- Lambda (production/test)?
- Local development?
- First time or recurring?

**Logs:**
- CloudWatch log group
- Request ID
- Timestamp

**Quick questions to ask:**
```
1. Can you share the exact error message?
2. What were you trying to do when this happened?
3. Do you have CloudWatch logs? (Request ID?)
4. Has this worked before?
```

---

### Step 2: Check Known Bugs (15 seconds)

**File:** NM06/NM06-Bugs-Critical_Index.md

**Quick pattern matching:**

| Error Pattern | Known Bug | Quick Fix |
|---------------|-----------|-----------|
| "sentinel value" | BUG-01 | Check router sanitization |
| "_CacheMiss not JSON serializable" | BUG-02 | Check cache validation |
| "cannot import gateway" | BUG-03 | Check file structure |
| Import errors | BUG-04 | Check dependency order |

**If pattern matches:**
- Fetch specific BUG-## file
- Provide documented solution
- DONE (most errors are known)

---

### Step 3: Analyze Error Type (30 seconds)

**Import Errors:**
```
"cannot import name X"
"module not found"
"circular import"
```
→ Route to: Workflow-07-ImportIssues.md

**Sentinel Errors:**
```
"sentinel value"
"_CacheMiss"
"_NotFound"
```
→ Solution: BUG-01 (Sanitize at router)

**Permission Errors:**
```
"AccessDenied"
"UnauthorizedException"
"403 Forbidden"
```
→ Check: IAM roles, Parameter Store permissions

**Timeout Errors:**
```
"Task timed out"
"Lambda timeout"
"Connection timeout"
```
→ Route to: Workflow-08-ColdStart.md

**Home Assistant Errors:**
```
"WebSocket connection failed"
"Authentication failed"
"Device not found"
```
→ Check: Token validity, HA connectivity

**Other Errors:**
→ Continue to Step 4

---

### Step 4: Trace Through Layers (2 minutes)

**SIMA Layer Analysis:**

**Gateway Layer Issues:**
```python
# Symptoms:
- Function not found
- Wrong parameter count
- Attribute error

# Check:
- Does gateway function exist?
- Is it exported in gateway.py?
- Correct function signature?
```

**Interface Layer Issues:**
```python
# Symptoms:
- Interface not found
- Wrong import path
- Lazy import failing

# Check:
- Does interface file exist?
- Correct import statement?
- Is lazy import working?
```

**Core Implementation Issues:**
```python
# Symptoms:
- Logic error
- Unexpected behavior
- Data transformation issue

# Check:
- Core function implementation
- Error handling
- Data validation
```

**External Dependency Issues:**
```python
# Symptoms:
- Network timeout
- API error
- External service unavailable

# Check:
- Connection configuration
- Credentials/tokens
- Circuit breaker state
```

---

### Step 5: Diagnose Root Cause (2-3 minutes)

**Anti-Pattern Check:**

**File:** AP-Checklist-Critical.md

```
Did code violate any of:
- AP-01: Direct imports? (circular dependency)
- AP-08: Threading? (not supported)
- AP-14: Bare except? (swallowing errors)
- AP-19: Sentinel leak? (crossing boundaries)
- AP-27: Skipped verification? (untested code)
```

**Design Decision Check:**

**File:** REF-ID-Directory-DEC.md

```
Is error due to:
- DEC-04: Threading assumption (Lambda single-threaded)
- DEC-05: Sentinel not sanitized (router layer)
- DEC-07: Memory exceeded (128MB limit)
- DEC-21: SSM misconfiguration (token-only)
```

**Error Handling Check:**

**File:** NM03/NM03-Operations-ErrorHandling.md

```
Does code have:
- Proper try/except blocks?
- Specific exception types?
- Error propagation?
- Logging of errors?
```

---

### Step 6: Provide Solution (5 minutes)

**Solution Template:**

```markdown
## Problem Identified

[Clear description of root cause]

## Why This Happened

[Explanation with REF-ID citations]

## Immediate Fix

[Code or configuration change needed now]

```python
# Fix code here
```

## Long-Term Solution

[Architectural or process improvement]

## Prevention

[How to avoid this in future]
- Add test case
- Update validation
- Document edge case
- Add to anti-patterns if needed

## References
- [REF-IDs related to issue]
- [Related workflows]
```

---

## 💡 COMMON ERROR PATTERNS

### Pattern 1: Sentinel Leak (BUG-01)

**Symptoms:**
```
TypeError: Object of type _CacheMiss is not JSON serializable
TypeError: sentinel value cannot be encoded
```

**Root Cause:**
Sentinel objects (_CacheMiss, _NotFound) escaped router layer

**Solution:**
```python
# In lambda_function.py (router)
def sanitize_response(data):
    """Remove sentinel objects before returning"""
    if isinstance(data, (CacheMiss, NotFound)):
        return None  # or appropriate default
    return data

# Usage
response = sanitize_response(result)
```

**References:** BUG-01, DEC-05, AP-19

---

### Pattern 2: Circular Import

**Symptoms:**
```
ImportError: cannot import name 'X' from partially initialized module
```

**Root Cause:**
Direct cross-interface import violates SIMA pattern

**Solution:**
```python
# ❌ WRONG - Direct import
from cache_core import get_value

# ✅ CORRECT - Via gateway
import gateway
value = gateway.cache_get(key)
```

**References:** RULE-01, AP-01, Workflow-07

---

### Pattern 3: Lambda Timeout

**Symptoms:**
```
Task timed out after 3.00 seconds
```

**Root Cause:**
Cold start + heavy operations exceeding timeout

**Solution:**
```python
# 1. Profile cold start
# 2. Identify slow imports
# 3. Move to lazy loading
# 4. Use fast_path for hot operations

# See Workflow-08 for details
```

**References:** PATH-01, LESS-02, Workflow-08

---

### Pattern 4: Home Assistant Connection Failed

**Symptoms:**
```
WebSocket connection failed
401 Unauthorized
Connection timeout
```

**Root Cause:**
- Token expired/invalid
- HA instance not reachable
- Network configuration issue

**Solution:**
```python
# 1. Check token in Parameter Store
import gateway
token = gateway.config_get_parameter('/lambda-execution-engine/home_assistant/token')

# 2. Verify HA URL
ha_url = gateway.config_get_parameter('/lambda-execution-engine/home_assistant/url')

# 3. Test connection
result = gateway.http_get(f"{ha_url}/api/", headers={"Authorization": f"Bearer {token}"})

# 4. Check CloudWatch logs for detailed error
```

**References:** DEC-21, ha_websocket.py

---

### Pattern 5: Permission Denied (SSM)

**Symptoms:**
```
AccessDeniedException: User: arn:aws:... is not authorized to perform: ssm:GetParameter
```

**Root Cause:**
Lambda execution role lacks SSM permissions

**Solution:**
```json
// Add to Lambda IAM role policy
{
  "Effect": "Allow",
  "Action": [
    "ssm:GetParameter",
    "ssm:GetParameters",
    "ssm:GetParametersByPath"
  ],
  "Resource": "arn:aws:ssm:region:account:parameter/lambda-execution-engine/*"
}
```

**References:** DEC-21, config_param_store.py

---

## ⚠️ COMMON MISTAKES TO AVOID

**DON'T:**
- Guess at the solution without logs
- Make changes without understanding root cause
- Skip checking known bugs first
- Ignore stack trace details
- Apply quick fixes that violate architecture

**DO:**
- Get full error context (logs, stack trace)
- Check known bugs database first
- Trace through SIMA layers systematically
- Identify root cause before fixing
- Document new error patterns
- Update anti-patterns if needed

---

## 🔧 DIAGNOSTIC TOOLS

### CloudWatch Logs

**Find logs:**
```
Log Group: /aws/lambda/[function-name]
Request ID: In error response
Search: Error message keywords
```

### Lambda Diagnostic Handler

**File:** lambda_diagnostic.py

```python
# Enable diagnostic mode
# Returns detailed system state
GET /diagnostic
```

### Debug Modes

**Available modes:**
```python
# In user_config.py
DEBUG_MODE = True  # Verbose logging
TRACE_MODE = True  # Operation tracing
BYPASS_AUTH = True # Skip auth (dev only)
```

### Health Check

```python
# Check system health
result = gateway.monitoring_health_check()
# Returns: Status of all subsystems
```

---

## 🔗 RELATED FILES

**Hub:** WORKFLOWS-PLAYBOOK.md  
**Known Bugs:** NM06/NM06-Bugs-Critical_Index.md  
**Error Handling:** NM03/NM03-Operations-ErrorHandling.md  
**Import Issues:** Workflow-07-ImportIssues.md  
**Cold Start:** Workflow-08-ColdStart.md  
**Anti-Patterns:** AP-Checklist-Critical.md

---

## 📊 SUCCESS METRICS

**Workflow succeeded when:**
- ✅ Root cause identified with REF-ID citation
- ✅ Immediate fix provided
- ✅ Long-term solution suggested
- ✅ Prevention strategy documented
- ✅ Error resolved and tested
- ✅ New patterns added to knowledge base
- ✅ User understands why error occurred

**Time:** 5-15 minutes depending on complexity

---

**END OF WORKFLOW**

**Lines:** ~295 (properly sized)  
**Priority:** CRITICAL (essential for maintenance)  
**ZAPH:** Tier 1 (frequent use)

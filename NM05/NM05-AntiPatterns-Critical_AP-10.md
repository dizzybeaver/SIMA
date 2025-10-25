# NM05-AntiPatterns-Critical_AP-10.md - AP-10

# AP-10: Modifying lambda_failsafe.py

**Category:** NM05 - Anti-Patterns  
**Topic:** Critical  
**Priority:** 🔴 Critical  
**Status:** Active  
**Created:** 2024-09-01  
**Last Updated:** 2025-10-24

---

## Summary

NEVER modify lambda_failsafe.py or add ANY imports to it. The failsafe must work even when everything else is broken. Any dependency defeats its entire purpose as the last-resort emergency handler.

---

## Context

lambda_failsafe.py is the emergency recovery system. It's called when the main Lambda function fails. If the failsafe itself fails, there's no recovery - requests just fail silently.

---

## Content

### The Anti-Pattern

**❌ WRONG:**
```python
# In lambda_failsafe.py
from gateway import log_info  # VIOLATION!

def failsafe_handler(event, context):
    log_info("Failsafe triggered")  # Defeats purpose!
    return {
        'statusCode': 500,
        'body': 'System error'
    }
```

**❌ ALSO WRONG:**
```python
# In lambda_failsafe.py  
import json  # Even stdlib imports are risky!

def failsafe_handler(event, context):
    return json.dumps({'error': 'fail'})  # Import could fail
```

### Why It's Wrong

**1. Defeats Emergency Purpose**
- Failsafe exists for when everything broken
- If failsafe imports gateway, and gateway broken → failsafe broken
- No recovery possible
- Silent failures

**2. Creates Dependency**
- Any import creates dependency
- Dependency could be what's broken
- Circular problem
- Lost last resort

**3. Import Can Fail**
- Module not found
- Syntax error in imported module
- Import-time execution fails
- Failsafe never runs

**4. Breaks Independence**
```
Main handler fails
   ↓
Failsafe called
   ↓  
Failsafe imports gateway
   ↓
Gateway import fails (it's broken)
   ↓
Failsafe fails
   ↓
No response returned
   ↓
Request timeout (30 seconds)
```

### The Correct Approach

**✅ CORRECT:**
```python
# In lambda_failsafe.py
# NO IMPORTS from project code!

def failsafe_handler(event, context):
    """Emergency handler - must work when everything else broken."""
    print("Failsafe triggered")  # Direct print only
    
    return {
        'statusCode': 500,
        'headers': {'Content-Type': 'application/json'},
        'body': '{"error": "System temporarily unavailable"}'
    }
```

### Failsafe Design Principles

**1. Zero Dependencies**
```python
# ✅ Allowed: Built-in functions
print()
str()
dict()
int()

# ❌ Not allowed: Any imports
import anything
from anything import something
```

**2. Inline Everything**
```python
# ✅ Correct: All logic inline
def failsafe_handler(event, context):
    response_body = '{"error": "fail"}'
    return {
        'statusCode': 500,
        'body': response_body
    }
    
# ❌ Wrong: External function
def format_error():  # Could be in broken module
    return '{"error": "fail"}'
```

**3. Minimal Logic**
```python
# ✅ Simple and safe
def failsafe_handler(event, context):
    return {'statusCode': 500, 'body': 'Error'}

# ❌ Complex and risky  
def failsafe_handler(event, context):
    # Parse event
    # Validate request
    # Call other functions
    # Format response
    # Too much can go wrong
```

### When to Modify Failsafe

**Almost never.** But if you must:

**Valid reasons:**
1. Change error message text
2. Add response header
3. Change status code

**Process:**
1. Review with entire team
2. Test failsafe in isolation
3. Ensure zero dependencies
4. Document change thoroughly
5. Deploy carefully

**Invalid reasons:**
1. "Want to log the failure" → NO
2. "Need to format error" → Keep simple
3. "Want to call service" → Defeats purpose
4. "Import just one thing" → Still NO

### Testing Failsafe

**Test independently:**
```python
# In test_failsafe.py
def test_failsafe_works_standalone():
    """Failsafe must work with zero dependencies."""
    from lambda_failsafe import failsafe_handler
    
    # No mocks, no imports, no setup
    result = failsafe_handler({}, {})
    
    assert result['statusCode'] == 500
    assert 'error' in result['body'].lower()
```

**Test in broken environment:**
```python
def test_failsafe_when_gateway_broken():
    """Failsafe must work when gateway broken."""
    # Simulate gateway import failure
    sys.modules['gateway'] = None
    
    from lambda_failsafe import failsafe_handler
    result = failsafe_handler({}, {})
    
    assert result is not None  # Must still work
```

### Real-World Scenario

**What Actually Happens:**
```
1. Deploy breaks gateway.py (syntax error)
2. Main handler tries to import gateway
3. Import fails
4. Lambda calls failsafe_handler
5. Failsafe returns 500 error
6. Request completes (not timeout)
7. Team sees 500s, investigates
8. Rolls back deploy
9. System recovers

If failsafe imported gateway:
1. Deploy breaks gateway.py
2. Main handler tries to import gateway → fails
3. Lambda calls failsafe_handler  
4. Failsafe tries to import gateway → fails
5. No response
6. 30-second timeout
7. Users see timeouts (worse than 500)
8. Harder to debug
```

### Detection

**Code Review - MUST CHECK:**
```python
# In lambda_failsafe.py

# ❌ Any of these = REJECT
import 
from
```

**Automated check:**
```bash
# Must return empty
grep -E "^(import|from)" lambda_failsafe.py
```

---

## Related Topics

- **ARCH-06**: Lambda entry point architecture
- **DEC-10**: Failsafe design decision
- **AP-07**: Custom logging (why print() OK in failsafe only)
- **LESS-05**: Graceful degradation patterns
- **BUG-03**: Cascading failures (what failsafe prevents)

---

## Keywords

lambda failsafe, emergency handler, zero dependencies, last resort, import restrictions, critical infrastructure

---

## Version History

- **2025-10-24**: Migrated to SIMA v3 format
- **2024-09-01**: Created - documented failsafe modification anti-pattern

---

**File:** `NM05-AntiPatterns-Critical_AP-10.md`  
**End of Document**

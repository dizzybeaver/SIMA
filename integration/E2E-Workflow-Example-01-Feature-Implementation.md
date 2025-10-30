# File: E2E-Workflow-Example-01-Feature-Implementation.md

**Version:** 1.0.0  
**Phase:** 7.0 - Integration  
**Date:** 2025-10-29  
**Purpose:** End-to-end workflow example for implementing new feature  
**REF-ID:** E2E-01

---

## SCENARIO

**Developer Task:** Add a new rate limiting feature to the Lambda function using the Cache interface

**Time Estimate:** 45-60 minutes  
**Complexity:** Medium  
**Prerequisites:** Understanding of SUGA architecture

---

## STEP-BY-STEP WORKFLOW

### Step 1: Understand Requirements (5 minutes)

**Action:** Review feature requirements
```
Feature: Rate Limiting
- Limit API calls per user per minute
- Use Cache interface to track requests
- Return 429 Too Many Requests when exceeded
- Log rate limit violations
```

**Tools Used:** None  
**Expected Output:** Clear understanding of what to build

---

### Step 2: Load Session Context (2 minutes)

**Action:** Load SUGA-ISP context using project knowledge search

**Query:**
```
"SUGA architecture pattern cache interface"
```

**Expected Files Found:**
- ARCH-01: SUGA Architecture Pattern
- INT-01: Cache Interface
- GATE-03: Cross-Interface Communication

**Validation:**
✅ Found at least 3 relevant architecture files  
✅ Cache interface documented  
✅ Gateway patterns accessible

**Time Checkpoint:** 7 minutes elapsed

---

### Step 3: Review Architecture Patterns (10 minutes)

**Action:** Read relevant architecture entries

**3.1: Read ARCH-01 (SUGA Pattern)**
```
Key Points:
- Three layers: Gateway → Interface → Core
- Always use gateway.py for external access
- Interfaces are L1, safe to import
- Core modules are L2, import via interface only
```

**3.2: Read INT-01 (Cache Interface)**
```
Key Functions:
- cache.get(key)
- cache.set(key, value, ttl)
- cache.delete(key)
- cache.has(key)

Implementation: interface_cache.py → cache_core.py
```

**3.3: Read GATE-03 (Cross-Interface)**
```
Pattern: Multiple interfaces in same function
- Import both interface modules
- Use both safely (they're L1)
- No direct core imports
```

**Validation:**
✅ Understand three-layer pattern  
✅ Know how to use Cache interface  
✅ Know how to combine with Logging interface

**Time Checkpoint:** 17 minutes elapsed

---

### Step 4: Check for Anti-Patterns (5 minutes)

**Action:** Review anti-patterns to avoid

**Query anti-patterns checklist:**
```
Common mistakes when adding rate limiting:
- âŒ Direct core imports (violates SUGA)
- âŒ Module-level state (Lambda stateless)
- âŒ Bare except clauses
- âŒ Heavy libraries (128MB limit)
```

**Check RED FLAGS:**
- âœ… Not using threading (Lambda single-threaded)
- âœ… Not using direct core imports
- âœ… Not storing state in memory (using Cache)

**Validation:**
✅ Reviewed critical anti-patterns  
✅ Know what NOT to do

**Time Checkpoint:** 22 minutes elapsed

---

### Step 5: Design Solution (8 minutes)

**Action:** Design rate limiting implementation

**Design:**
```python
# In gateway.py - NEW FUNCTION

def check_rate_limit(user_id: str, max_requests: int = 60) -> tuple[bool, int]:
    """
    Check if user is within rate limit.
    
    Args:
        user_id: User identifier
        max_requests: Max requests per minute
        
    Returns:
        (allowed: bool, remaining: int)
    """
    # Import at function level (SUGA pattern)
    from interface_cache import get_cache, set_cache, has_cache
    from interface_logging import log_warning
    
    # Cache key for this user's request count
    cache_key = f"rate_limit:{user_id}"
    
    # Get current count
    if has_cache(cache_key):
        current_count = get_cache(cache_key)
    else:
        current_count = 0
    
    # Check limit
    if current_count >= max_requests:
        log_warning(f"Rate limit exceeded for user {user_id}")
        return False, 0
    
    # Increment count
    new_count = current_count + 1
    set_cache(cache_key, new_count, ttl=60)  # 60 second window
    
    return True, max_requests - new_count
```

**Validation:**
✅ Uses SUGA pattern (function-level imports)  
✅ Uses interfaces (not core directly)  
✅ Combines Cache + Logging safely  
✅ No anti-patterns present

**Time Checkpoint:** 30 minutes elapsed

---

### Step 6: Check Dependencies (3 minutes)

**Action:** Verify all dependencies exist

**Check:**
```
âœ… interface_cache.py exists
âœ… get_cache() function exists
âœ… set_cache() function exists  
âœ… has_cache() function exists
âœ… interface_logging.py exists
âœ… log_warning() function exists
âœ… gateway.py exists and accessible
```

**Validation:**
✅ All required functions available  
✅ No new dependencies needed

**Time Checkpoint:** 33 minutes elapsed

---

### Step 7: Implement Function (10 minutes)

**Action:** Add function to gateway.py

**Implementation Location:**
```
File: src/gateway.py
Section: Rate Limiting (NEW)
After: Other gateway wrapper functions
Before: End of file
```

**Add Function:**
```python
# ============================================================
# RATE LIMITING
# ============================================================

def check_rate_limit(user_id: str, max_requests: int = 60) -> tuple[bool, int]:
    """
    Check if user is within rate limit.
    
    Uses Cache interface to track request counts per user.
    Rate limit window is 60 seconds (sliding window).
    
    Args:
        user_id: User identifier (required)
        max_requests: Maximum requests per minute (default: 60)
        
    Returns:
        Tuple of (allowed: bool, remaining: int)
        - allowed: True if request should be allowed
        - remaining: Number of requests remaining in window
        
    Example:
        allowed, remaining = check_rate_limit("user123")
        if not allowed:
            return error_response(429, "Rate limit exceeded")
            
    Related:
        - INT-01: Cache Interface
        - GATE-03: Cross-Interface Pattern
        - NMP01-LEE-17: Rate Limiting Implementation
    """
    # Import at function level (SUGA pattern ARCH-01)
    from interface_cache import get_cache, set_cache, has_cache
    from interface_logging import log_warning
    
    try:
        # Cache key for this user's request count
        cache_key = f"rate_limit:{user_id}"
        
        # Get current count (default to 0 if not cached)
        current_count = get_cache(cache_key) if has_cache(cache_key) else 0
        
        # Check if limit exceeded
        if current_count >= max_requests:
            log_warning(
                f"Rate limit exceeded for user {user_id}: "
                f"{current_count}/{max_requests} requests"
            )
            return False, 0
        
        # Increment count with 60 second TTL (sliding window)
        new_count = current_count + 1
        set_cache(cache_key, new_count, ttl=60)
        
        return True, max_requests - new_count
        
    except Exception as e:
        # Log error but don't block request on rate limit failure
        log_warning(f"Rate limit check failed for {user_id}: {e}")
        return True, max_requests  # Fail open for availability
```

**Validation:**
✅ Function follows SUGA pattern  
✅ Uses function-level imports  
✅ Has docstring with examples  
✅ Has error handling  
✅ Includes cross-references

**Time Checkpoint:** 43 minutes elapsed

---

### Step 8: Add Integration Point (5 minutes)

**Action:** Use new function in Lambda handler

**Location:** src/lambda_function.py

**Add Rate Limit Check:**
```python
def lambda_handler(event, context):
    """Main Lambda handler"""
    from gateway import check_rate_limit, log_info, error_response
    
    try:
        # Extract user ID from event
        user_id = event.get('requestContext', {}).get('authorizer', {}).get('principalId', 'anonymous')
        
        # Check rate limit
        allowed, remaining = check_rate_limit(user_id)
        if not allowed:
            return error_response(
                status_code=429,
                message="Rate limit exceeded. Please try again later.",
                headers={'X-RateLimit-Remaining': '0'}
            )
        
        # Log rate limit status
        log_info(f"Rate limit check passed for {user_id}: {remaining} remaining")
        
        # Continue with normal request handling
        # ... rest of handler code ...
        
    except Exception as e:
        # Error handling
        pass
```

**Validation:**
✅ Properly integrated into handler  
✅ Returns appropriate HTTP 429 status  
✅ Includes remaining requests in headers  
✅ Logs rate limit status

**Time Checkpoint:** 48 minutes elapsed

---

### Step 9: Code Review Checklist (7 minutes)

**Action:** Use CHK-01 (Code Review Checklist)

**Run through checklist:**

**Architecture Compliance:**
- âœ… Uses SUGA three-layer pattern
- âœ… Function-level imports only
- âœ… No direct core imports
- âœ… Proper interface usage

**Code Quality:**
- âœ… Functions < 50 lines
- âœ… Docstrings present
- âœ… Type hints used
- âœ… Error handling included

**RED FLAGS:**
- âœ… No threading (N/A)
- âœ… No bare except
- âœ… No sentinel objects
- âœ… No heavy libraries

**Documentation:**
- âœ… Cross-references included
- âœ… Examples in docstring
- âœ… Related entries cited

**Validation:**
✅ All checklist items pass  
✅ No violations found

**Time Checkpoint:** 55 minutes elapsed

---

### Step 10: Create Documentation Entry (5 minutes)

**Action:** Create NMP entry for this implementation

**Create:** NMP01-LEE-17-Rate-Limiting.md

**Content:**
```markdown
# File: NMP01-LEE-17-Rate-Limiting.md

**REF-ID:** NMP01-LEE-17  
**Type:** Implementation Pattern  
**Inherits:** ARCH-01, INT-01, GATE-03

## Rate Limiting Implementation

Implementation of API rate limiting using Cache interface.

### Pattern
- Cache interface tracks request counts
- 60 second sliding window
- Fail open on cache errors (availability over strict limits)

### Key Functions
- gateway.check_rate_limit(user_id, max_requests)

### Implementation Details
See src/gateway.py check_rate_limit()

### Testing
Test with multiple rapid requests to verify limiting works.

### Cross-References
- ARCH-01: SUGA Architecture
- INT-01: Cache Interface  
- GATE-03: Cross-Interface Pattern
```

**Validation:**
✅ NMP entry created  
✅ Inherits correct entries  
✅ Cross-references included

**Time Checkpoint:** 60 minutes elapsed

---

## COMPLETION SUMMARY

### Time Breakdown
1. Understand requirements: 5 min
2. Load context: 2 min
3. Review architecture: 10 min
4. Check anti-patterns: 5 min
5. Design solution: 8 min
6. Check dependencies: 3 min
7. Implement function: 10 min
8. Add integration: 5 min
9. Code review: 7 min
10. Create documentation: 5 min

**Total:** 60 minutes

---

### Deliverables
✅ New function: gateway.check_rate_limit()  
✅ Integration in lambda_handler  
✅ Documentation: NMP01-LEE-17  
✅ All checks passed

---

### Quality Metrics
- Architecture compliance: 100%
- Anti-pattern violations: 0
- Code review: PASS
- Documentation: Complete

---

### Files Modified
1. src/gateway.py (added check_rate_limit)
2. src/lambda_function.py (added rate limit check)
3. sima/nmp/NMP01-LEE-17-Rate-Limiting.md (created)

---

### Lessons Learned
1. Following SUGA pattern made implementation straightforward
2. Having interfaces documented saved time
3. Anti-pattern checklist prevented common mistakes
4. Code review checklist caught issues early
5. Documentation easy when following template

---

## TESTING WORKFLOW

**Next Steps:**
1. Write unit tests for check_rate_limit()
2. Write integration tests for rate limiting
3. Deploy to test environment
4. Verify rate limiting works with real requests
5. Monitor cache performance

---

## SUCCESS CRITERIA MET

✅ Feature implemented in < 60 minutes  
✅ Follows SUGA architecture  
✅ No anti-patterns  
✅ Code review passed  
✅ Documentation created  
✅ Ready for testing

---

**END OF WORKFLOW EXAMPLE 01**

**Version:** 1.0.0  
**Status:** Complete  
**REF-ID:** E2E-01

# File: BUG-04.md

**REF-ID:** BUG-04  
**Category:** Project Lessons  
**Type:** Critical Bug  
**Project:** LEE (SUGA-ISP)  
**Version:** 1.0.0  
**Created:** 2025-10-20  
**Updated:** 2025-10-30  
**Status:** Resolved

---

## Summary

Deployment failures caused by mismatched configuration parameters between environment variables and SSM Parameter Store. Multiple sources of truth for configuration led to inconsistent deployments and production failures. Fixed by simplifying to SSM token-only approach.

---

## Bug Details

**Symptom:**
- Lambda deployed successfully but failed at runtime
- Configuration errors: "Missing required parameter"
- Same code worked in dev but failed in production
- Errors only appeared after deployment
- Difficult to reproduce locally

**Root Cause:**
```python
# Mixed configuration sources
# Some from environment variables:
LOG_LEVEL = os.environ['LOG_LEVEL']
TIMEOUT = int(os.environ.get('TIMEOUT', 30))

# Others from SSM:
HA_TOKEN = gateway.ssm_get_parameter('/ha/token')
API_KEY = gateway.ssm_get_parameter('/api/key')

# Problem: Two sources of truth, easy to misconfigure
```

**Issues:**
1. Environment variables set in Lambda console
2. SSM parameters set in AWS Systems Manager
3. No single source of truth
4. Easy to update one but forget the other
5. Deployment checklist became complex and error-prone

**Discovery Method:**
Production deployment failure with missing parameters error.

---

## Solution

**Simplify to Single Source:**

```python
# All configuration from SSM Parameter Store
HA_TOKEN = gateway.ssm_get_parameter(
    '/lambda-execution-engine/home_assistant/token'
)

# Environment variables only for non-secret settings
LOG_LEVEL = os.environ.get('LOG_LEVEL', 'INFO')
DEBUG_MODE = os.environ.get('DEBUG_MODE', 'false')

# Pattern: Secrets in SSM, settings in env vars
```

**Configuration Strategy:**
```python
# Clear separation:
SECRETS = {
    'ha_token': gateway.ssm_get_parameter('/token'),  # SSM
}

CONFIG = {
    'log_level': os.environ.get('LOG_LEVEL', 'INFO'),  # Env var
    'timeout': int(os.environ.get('TIMEOUT', 30)),     # Env var
}
```

**Deployment Checklist (Simplified):**
1. Update SSM parameters (one time, rarely changes)
2. Deploy Lambda code
3. Verify environment variables (optional)
4. Test deployment

**Why This Works:**
- Single source for each type of setting
- Clear rule: Secrets in SSM, settings in env vars
- Reduced deployment complexity
- Easier to validate
- Self-documenting pattern

---

## Impact

**Deployment:**
- Before: Frequent deployment failures
- After: Reliable deployments
- Improvement: Eliminated configuration errors

**Development:**
- Simplified deployment process
- Clear configuration rules
- Reduced cognitive load

**Operations:**
- Fewer production issues
- Faster debugging
- Clear error messages

---

## Prevention Strategies

1. **Single source of truth**
   - Pick one system for each configuration type
   - Document the rule clearly
   - Enforce through code review

2. **Validate configuration**
   - Check all required parameters at startup
   - Fail fast with clear error messages
   - Add file verification step

3. **Simplify deployment**
   - Minimize manual steps
   - Automate where possible
   - Clear, documented process

4. **Test configuration**
   - Test deployments in staging first
   - Verify configuration completeness
   - Use prod-like configuration

5. **Clear patterns**
   - Secrets → SSM (secure, encrypted)
   - Settings → Environment variables (easy to change)
   - Data → Database/Storage (large or dynamic)

---

## Related References

**Decisions:**
- DEC-21: SSM token-only configuration (solution)

**Lessons:**
- LESS-09: Partial deployment danger
- LESS-15: File verification mandatory (prevention protocol)

**Wisdom:**
- WISD-04: Consistency over cleverness
- WISD-05: Document everything

---

## Keywords

configuration, deployment, SSM, parameter-store, environment-variables, misconfiguration, deployment-failure, SUGA-ISP

---

## Cross-References

**Inherits From:** None (root bug)  
**Related To:** BUG-01 (prevention), BUG-02 (architecture), BUG-03 (isolation)  
**Referenced By:** DEC-21, LESS-09, LESS-15, WISD-04

---

**End of BUG-04**

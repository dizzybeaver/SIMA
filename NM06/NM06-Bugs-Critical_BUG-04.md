# NM06-Bugs-Critical_BUG-04.md - BUG-04

# BUG-04: Configuration Parameter Mismatch

**Category:** Lessons
**Topic:** Critical Bugs
**Priority:** Critical
**Status:** Resolved
**Date Discovered:** 2025-10-20
**Fixed In:** DEC-21 (SSM token-only configuration)
**Created:** 2025-10-20
**Last Updated:** 2025-10-23

---

## Summary

Deployment failures caused by mismatched configuration parameters between environment variables and SSM Parameter Store. Multiple sources of truth for configuration led to inconsistent deployments and production failures. Fixed by simplifying to SSM token-only approach (DEC-21).

---

## Context

The Lambda used both environment variables and SSM Parameter Store for configuration. Some settings were in environment variables (LOG_LEVEL, TIMEOUT), while others were in SSM (API keys, tokens). During deployments, inconsistencies between these sources caused failures that were difficult to debug.

---

## Content

### The Problem

**Symptom:**
- Lambda deployed successfully but failed at runtime
- Configuration errors: "Missing required parameter"
- Same code worked in dev but failed in production
- Errors only appeared after deployment
- Difficult to reproduce locally

**The Code Issue:**

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

**What Went Wrong:**
- Environment variables set in Lambda console
- SSM parameters set in AWS Systems Manager
- No single source of truth
- Easy to update one but forget the other
- Deployment checklist became complex and error-prone

### Root Cause

**Configuration Complexity:**
- Two separate configuration systems
- No validation that both were in sync
- Manual deployment process required checking both
- Different access patterns (console vs CLI vs IaC)

**Specific Issues:**
1. **Split configuration**
   - Some settings in environment variables
   - Some settings in SSM
   - No clear rule for which goes where

2. **Deployment fragility**
   - Must update both systems correctly
   - Easy to forget SSM parameters
   - No automated verification

3. **No validation**
   - Lambda doesn't check configuration completeness
   - Errors only surface at runtime
   - Partial deployment causes production issues

### Impact

**Deployment:**
- Failed deployments in production
- Rollback required
- Extended downtime

**Development:**
- Fear of deploying changes
- Complex deployment checklist
- Increased cognitive load

**Debugging:**
- Hard to diagnose (which config wrong?)
- Required checking multiple systems
- No clear error messages

### Solution

**Simplify to Single Source (DEC-21):**

**New approach: SSM token-only**
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

### Prevention

**How to Prevent Similar Issues:**

1. **Single source of truth**
   - Pick one system for each configuration type
   - Document the rule clearly
   - Enforce through code review

2. **Validate configuration**
   - Check all required parameters at startup
   - Fail fast with clear error messages
   - Add file verification step (LESS-15)

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

## Related Topics

- **DEC-21**: SSM token-only configuration (solution decision)
- **LESS-09**: Partial deployment danger (related lesson)
- **LESS-15**: File verification mandatory (prevention protocol)
- **DEC-08**: Flat file structure (keep things simple principle)
- **LESS-04**: Consistency over cleverness (design principle)

---

## Keywords

configuration, deployment, SSM, parameter-store, environment-variables, misconfiguration, deployment-failure

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2025-10-20**: Bug documented in NM06-BUGS-Critical.md
- **2025-10-20**: Fixed by DEC-21 (SSM token-only)

---

**File:** `NM06-Bugs-Critical_BUG-04.md`
**End of Document**

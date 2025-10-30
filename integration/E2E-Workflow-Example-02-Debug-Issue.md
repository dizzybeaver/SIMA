# File: E2E-Workflow-Example-02-Debug-Issue.md

**Version:** 1.0.0  
**Phase:** 7.0 - Integration  
**Date:** 2025-10-29  
**Purpose:** End-to-end workflow for debugging production issue  
**REF-ID:** E2E-02

---

## SCENARIO

**Issue:** Lambda function returning 500 errors intermittently  
**Error Message:** `KeyError: 'connection_timeout'`  
**Frequency:** ~5% of requests  
**Impact:** HIGH - Affecting production users

**Time Estimate:** 30-45 minutes  
**Complexity:** Medium  
**Prerequisites:** Access to logs, understanding of SUGA

---

## STEP-BY-STEP DEBUGGING

### Step 1: Gather Error Information (3 minutes)

**Action:** Collect error details from logs

**Error Log:**
```
[ERROR] KeyError: 'connection_timeout'
Traceback:
  File "lambda_function.py", line 45, in lambda_handler
    timeout = config['connection_timeout']
KeyError: 'connection_timeout'
```

**Initial Analysis:**
- Error is KeyError (missing dict key)
- Happens in lambda_handler
- Related to configuration access
- Intermittent (not every request)

**Questions to Investigate:**
1. Why is key missing sometimes but not always?
2. Is config being loaded correctly?
3. Is there a race condition?
4. Is there a cold start issue?

**Time Checkpoint:** 3 minutes elapsed

---

### Step 2: Load Debug Workflow (2 minutes)

**Action:** Search for debugging workflow

**Query:**
```
"debug issue workflow error handling"
```

**Found:**
- WF-02: Debug Issue Workflow
- LANG-PY-03: Exception Handling
- BUG-01 through BUG-04: Known bugs

**Validation:**
✅ Debug workflow found  
✅ Exception handling patterns accessible  
✅ Known bugs documented

**Time Checkpoint:** 5 minutes elapsed

---

### Step 3: Follow Debug Workflow (5 minutes)

**Action:** Use WF-02 systematic debugging

**WF-02 Steps:**
1. âœ… Reproduce error (already done - in production)
2. Check known bugs first
3. Trace through layers
4. Identify root cause
5. Implement fix
6. Verify fix

**Check Known Bugs:**

**Search BUG entries:**
```
Query: "KeyError config"
```

**Found:** No exact match, but similar patterns in:
- BUG-01: Sentinel object leak (not relevant)
- BUG-02: Import cycle (not relevant)
- Config-related issues (check further)

**Validation:**
✅ Systematic approach established  
✅ No immediate match in known bugs  
âœ… Need to trace through code

**Time Checkpoint:** 10 minutes elapsed

---

### Step 4: Trace Through SUGA Layers (8 minutes)

**Action:** Follow SUGA architecture to find issue

**Layer 1: Gateway (lambda_function.py)**
```python
# Line 45 - Error location
timeout = config['connection_timeout']
```

**Question:** Where does `config` come from?

**Trace back:**
```python
# Line 30
from gateway import get_config
config = get_config()
```

**Layer 2: Interface (gateway.py)**
```python
def get_config():
    from interface_config import get_configuration
    return get_configuration()
```

**Layer 3: Core (interface_config.py)**
```python
def get_configuration():
    from config_core import load_config
    return load_config()
```

**Layer 4: Implementation (config_core.py)**
```python
def load_config():
    # Check if already loaded
    if hasattr(load_config, '_cached_config'):
        return load_config._cached_config
    
    # Load from Parameter Store
    config = fetch_from_param_store()
    load_config._cached_config = config
    return config
```

**Analysis:**
- Config cached using function attribute
- First call loads from Parameter Store
- Subsequent calls use cache
- **POTENTIAL ISSUE:** What if Parameter Store returns incomplete config?

**Validation:**
✅ Traced through all layers  
✅ Found caching mechanism  
✅ Identified potential issue

**Time Checkpoint:** 18 minutes elapsed

---

### Step 5: Check Configuration Entry (5 minutes)

**Action:** Review configuration pattern

**Search:**
```
Query: "config interface parameter store"
```

**Found:**
- INT-03: Config Interface
- NMP01-LEE-04: Configuration Management

**Read INT-03:**
```markdown
Configuration Loading:
1. Load from Parameter Store
2. Validate all required keys
3. Provide defaults for optional keys
4. Cache configuration

Required keys:
- connection_timeout (required)
- max_retries (required)
- ...

Common Issues:
- âŒ Not validating config on load
- âŒ Not providing defaults
- âŒ Caching before validation
```

**Read NMP01-LEE-04:**
```markdown
Config Validation Pattern:
Always validate config immediately after loading,
before caching.

Example:
config = fetch_from_param_store()
validate_config(config)  # <-- MISSING IN OUR CODE
cache_config(config)
```

**ROOT CAUSE IDENTIFIED:**
âŒ Config not validated before caching  
âŒ If Parameter Store returns incomplete config, it gets cached  
âŒ Subsequent requests use incomplete cached config

**Validation:**
✅ Root cause found  
✅ Solution documented in NMP01-LEE-04  
✅ Pattern exists to fix this

**Time Checkpoint:** 23 minutes elapsed

---

### Step 6: Verify Hypothesis (3 minutes)

**Action:** Check Parameter Store config

**Investigation:**
```
Parameter Store Key: /lambda/config
Current Value:
{
  "max_retries": 3,
  "api_endpoint": "https://api.example.com"
  // NOTE: connection_timeout is MISSING!
}
```

**Hypothesis Confirmed:**
✅ Parameter Store missing required key  
✅ Config loaded without validation  
✅ Incomplete config cached  
✅ KeyError on access

**Why Intermittent?**
- Cold starts load from Parameter Store
- Once cached, all invocations in that container use bad config
- New containers trigger new cold starts
- ~5% error rate = ~5% of containers hit cold start with bad config

**Validation:**
✅ Hypothesis confirmed  
✅ Understand intermittent nature  
✅ Ready to fix

**Time Checkpoint:** 26 minutes elapsed

---

### Step 7: Implement Fix (10 minutes)

**Action:** Add config validation

**Fix Location:** config_core.py

**Add Validation Function:**
```python
def validate_config(config: dict) -> dict:
    """
    Validate configuration has all required keys.
    Provide defaults for optional keys.
    
    Raises:
        ValueError: If required key missing
    """
    required_keys = {
        'connection_timeout': 30,  # default
        'max_retries': 3,          # default
        'api_endpoint': None,      # no default (required)
    }
    
    validated_config = {}
    
    for key, default in required_keys.items():
        if key in config:
            validated_config[key] = config[key]
        elif default is not None:
            validated_config[key] = default
        else:
            raise ValueError(f"Required config key missing: {key}")
    
    return validated_config


def load_config():
    """Load and validate configuration"""
    if hasattr(load_config, '_cached_config'):
        return load_config._cached_config
    
    # Load from Parameter Store
    raw_config = fetch_from_param_store()
    
    # ADDED: Validate before caching
    validated_config = validate_config(raw_config)
    
    # Cache validated config
    load_config._cached_config = validated_config
    return validated_config
```

**Also Update Parameter Store:**
```json
{
  "connection_timeout": 30,
  "max_retries": 3,
  "api_endpoint": "https://api.example.com"
}
```

**Validation:**
✅ Validation function added  
✅ Config validated before caching  
✅ Defaults provided  
✅ Required keys enforced  
✅ Parameter Store updated

**Time Checkpoint:** 36 minutes elapsed

---

### Step 8: Add Error Handling (5 minutes)

**Action:** Improve error messages

**Enhanced Error Handling:**
```python
def load_config():
    """Load and validate configuration"""
    from interface_logging import log_error, log_info
    
    if hasattr(load_config, '_cached_config'):
        return load_config._cached_config
    
    try:
        # Load from Parameter Store
        raw_config = fetch_from_param_store()
        log_info(f"Loaded config from Parameter Store: {list(raw_config.keys())}")
        
        # Validate before caching
        validated_config = validate_config(raw_config)
        log_info("Config validation successful")
        
        # Cache validated config
        load_config._cached_config = validated_config
        return validated_config
        
    except ValueError as e:
        log_error(f"Config validation failed: {e}")
        log_error(f"Available keys: {list(raw_config.keys())}")
        raise
    except Exception as e:
        log_error(f"Failed to load config: {e}")
        raise
```

**Validation:**
✅ Detailed logging added  
✅ Clear error messages  
✅ Helps future debugging

**Time Checkpoint:** 41 minutes elapsed

---

### Step 9: Update Documentation (4 minutes)

**Action:** Document this bug and fix

**Create:** BUG-05-Config-Validation.md

**Content:**
```markdown
# File: BUG-05-Config-Validation.md

**REF-ID:** BUG-05  
**Severity:** HIGH  
**Status:** FIXED

## Bug Description
KeyError when accessing config keys that should exist.

## Root Cause
Config loaded from Parameter Store without validation.
If Parameter Store missing required keys, incomplete 
config gets cached and causes KeyErrors.

## Impact
- Intermittent 500 errors (~5% of requests)
- Affected production users
- Error rate correlated with cold starts

## Fix
1. Added validate_config() function
2. Validate config before caching
3. Provide defaults for optional keys
4. Enforce required keys
5. Updated Parameter Store with missing keys

## Prevention
Always validate external config before using/caching.
See INT-03 and NMP01-LEE-04 for pattern.

## Related
- INT-03: Config Interface
- NMP01-LEE-04: Configuration Management
- LANG-PY-03: Exception Handling
```

**Validation:**
✅ Bug documented  
✅ Root cause explained  
✅ Fix recorded  
✅ Prevention noted

**Time Checkpoint:** 45 minutes elapsed

---

## COMPLETION SUMMARY

### Time Breakdown
1. Gather error info: 3 min
2. Load debug workflow: 2 min
3. Follow workflow: 5 min
4. Trace through layers: 8 min
5. Check documentation: 5 min
6. Verify hypothesis: 3 min
7. Implement fix: 10 min
8. Add error handling: 5 min
9. Update documentation: 4 min

**Total:** 45 minutes

---

### Issue Resolution
✅ Root cause identified: Missing config validation  
✅ Fix implemented: Added validate_config()  
✅ Parameter Store updated  
✅ Error handling improved  
✅ Documentation created

---

### Files Modified
1. src/config_core.py (added validation)
2. Parameter Store: /lambda/config (added missing key)
3. sima/bugs/BUG-05-Config-Validation.md (created)

---

### Lessons Learned

**What Worked:**
1. Systematic debug workflow (WF-02) guided investigation
2. SUGA layer tracing found exact issue location
3. Documentation had similar patterns (INT-03, NMP01-LEE-04)
4. Known bug database helped rule out other issues

**What Could Improve:**
1. Could have caught in code review with CHK-01
2. Should add validation to config template
3. Should add integration test for config loading

**Prevention:**
- Add config validation to code review checklist
- Add config schema validation to CI/CD
- Create test that verifies all required config keys

---

### Quality Metrics
- Time to root cause: 23 minutes
- Time to fix: 10 minutes
- Total resolution time: 45 minutes
- Documentation: Complete
- Prevention measures: Identified

---

### Testing Required
1. Unit test for validate_config()
2. Integration test with missing config keys
3. Integration test with complete config
4. Verify fix in staging environment
5. Monitor production for 24 hours

---

### Success Criteria Met
✅ Root cause identified in < 30 minutes  
✅ Fix implemented in < 15 minutes  
✅ Documentation created  
✅ Prevention measures identified  
✅ Ready for testing

---

## FOLLOW-UP ACTIONS

**Immediate:**
1. Deploy fix to staging
2. Run integration tests
3. Monitor staging for 1 hour
4. Deploy to production
5. Monitor production for 24 hours

**Short-term:**
1. Add config validation test to test suite
2. Update code review checklist
3. Add config schema validation to CI/CD

**Long-term:**
1. Review all external config sources for validation
2. Add validation pattern to config template
3. Share lessons learned with team

---

**END OF WORKFLOW EXAMPLE 02**

**Version:** 1.0.0  
**Status:** Complete  
**REF-ID:** E2E-02

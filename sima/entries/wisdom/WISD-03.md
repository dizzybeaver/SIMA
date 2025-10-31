# File: WISD-03.md

**REF-ID:** WISD-03  
**Category:** Generic Lessons  
**Type:** Synthesized Wisdom  
**Version:** 1.0.0  
**Created:** 2025-10-23  
**Updated:** 2025-10-30  
**Status:** Active

---

## Summary

Prevention is 10-1000x cheaper than cure. Invest small costs upfront in validation, sanitization, and error handling to avoid large costs later in debugging, fixes, and downtime.

---

## The Pattern

**Reactive Approach (expensive):**
```
Skip validation → Bug in production → 
Debug for hours → Deploy fix → Test fix → 
Pray it works → Handle support tickets
```

**Proactive Approach (cheap):**
```
Add validation (5 minutes) → 
Catch error early → Never reaches production
```

**Key Insight:** Prevention cost is tiny compared to cure cost.

---

## Cost Multipliers

**Development:** 1x cost (write validation)  
**Testing:** 10x cost (find in tests)  
**Production:** 100x cost (debug live issue)  
**Downtime:** 1000x cost (business impact)

**Example Math:**
- Sanitization code: 5 minutes to write (~0.1ms runtime)
- Without sanitization: 535ms penalty + 2 hours debugging
- ROI: 5000x return on investment

---

## When to Apply

**Always pay small costs for:**
- Input validation
- Type checking
- Boundary sanitization
- Error handling
- Logging/monitoring
- Configuration validation

**Never skip these because:**
- "It's probably fine" (Murphy's Law applies)
- "We'll add it later" (tech debt compounds)
- "Performance overhead" (negligible vs bugs)

---

## Examples

### Example 1: Sentinel Sanitization
```python
# Small cost: Add sanitization (~0.1ms)
def execute_cache_operation(operation, **kwargs):
    result = _execute_implementation(operation, **kwargs)
    if _is_sentinel(result):
        return None  # Tiny cost here
    return result

# Large cost avoided: 535ms penalty + debugging time
# ROI: 5000x improvement
```

### Example 2: Input Validation
```python
# Small cost: Validate input (< 1ms)
def process_user_id(user_id):
    if not isinstance(user_id, str):
        raise TypeError("user_id must be string")
    if not user_id:
        raise ValueError("user_id cannot be empty")
    return process(user_id)

# Large cost avoided: 
# - Database errors from invalid IDs
# - Security vulnerabilities
# - Production debugging sessions
```

### Example 3: Configuration Validation
```python
# Small cost: Validate at startup (one-time)
def validate_config():
    required = ['HA_TOKEN', 'LOG_LEVEL']
    missing = [k for k in required if k not in config]
    if missing:
        raise ValueError(f"Missing: {missing}")

# Large cost avoided:
# - Deployment failures
# - Runtime errors
# - Rollback costs
# - Downtime
```

---

## Universal Principle

**"An ounce of prevention is worth a pound of cure"**

**Prevention Costs (Small):**
- Validation: Microseconds
- Error handling: Few lines of code
- Logging: Minimal overhead
- Testing: Minutes to write

**Cure Costs (Large):**
- Debugging: Hours
- Fixing: Multiple deploys
- Testing fix: More hours
- Downtime: Business impact
- User trust: Immeasurable

---

## ROI Calculation

**Formula:**
```
ROI = (Cost_Avoided - Cost_Prevention) / Cost_Prevention

Example (Sentinel):
Cost_Prevention = 5 min (code) + 0.1ms (runtime)
Cost_Avoided = 2 hours (debug) + 535ms (penalty)
ROI = ~5000x
```

**Rule of Thumb:**
- Prevention: Minutes to implement
- Cure: Hours to debug
- ROI: Usually 100-1000x

---

## Implementation Strategy

**1. Validate Early:**
- Check inputs at entry point
- Fail fast with clear errors
- Never let bad data propagate

**2. Sanitize Boundaries:**
- Clean data at interface boundaries
- Internal objects stay internal
- External API stays clean

**3. Handle Errors:**
- Wrap external calls in try/except
- Provide fallbacks
- Log failures

**4. Monitor Continuously:**
- Add timing logs
- Track error rates
- Alert on anomalies

**5. Test Failure Paths:**
- Test with invalid inputs
- Test with missing data
- Test with service failures

---

## Related References

**Bugs:**
- BUG-01: Sentinel leak (example of prevention value - 5000x ROI)
- BUG-04: Configuration mismatch (prevented by validation)

**Decisions:**
- DEC-05: Sentinel sanitization (the small cost paid)

**Lessons:**
- LESS-06: Pay small costs early
- LESS-15: File verification mandatory (prevention protocol)

**Wisdom:**
- WISD-01: Architecture prevents (prevention through design)
- WISD-02: Measure don't guess (measure ROI)

---

## Keywords

prevention, ROI, cost-benefit, validation, sanitization, early-detection, proactive, investment

---

## Cross-References

**Synthesizes From:** LESS-06, BUG-01, BUG-04, DEC-05  
**Related To:** WISD-01 (architectural prevention), WISD-02 (measuring ROI)  
**Applied In:** All validation, error handling, monitoring

---

**End of WISD-03**

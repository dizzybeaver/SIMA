# NM04-Decisions-Operational_DEC-22.md - DEC-22

# DEC-22: DEBUG_MODE Flow Visibility

**Category:** Decisions
**Topic:** Operational
**Priority:** High
**Status:** Active
**Date Decided:** 2025-10-20
**Created:** 2025-10-23
**Last Updated:** 2025-10-23

---

## Summary

Environment variable `DEBUG_MODE=true` enables operation flow visibility (which operations called, with what parameters) without code changes or redeployment, supporting development troubleshooting and production diagnosis.

---

## Context

When investigating production issues or debugging complex request flows, we needed to see exactly which operations were called, in what order, with what parameters. Traditional approaches (adding print statements, deploying new versions) are slow and disruptive.

Lambda's CloudWatch logging provides the infrastructure, but we needed a way to toggle verbose operation logging on/off without code changes or redeployment. The solution needed to be production-safe (no performance impact when off, acceptable impact when on).

---

## Content

### The Decision

**What We Chose:**
Implement `DEBUG_MODE` environment variable that enables operation flow logging. When enabled, log every operation call (interface, operation name, parameters) and completion (result type, success/failure).

**Implementation:**
```python
DEBUG_MODE = os.environ.get('DEBUG_MODE', 'false').lower() == 'true'

def execute_operation(operation, **kwargs):
    if DEBUG_MODE:
        log_debug(f"Executing: {operation}, kwargs_keys={list(kwargs.keys())}")
    
    result = OPERATIONS[operation](**kwargs)
    
    if DEBUG_MODE:
        log_debug(f"Completed: {operation}, result_type={type(result).__name__}")
    
    return result
```

**Output Example:**
```
[DEBUG] Executing: cache.get, kwargs_keys=['key']
[INFO] Cache get: user_123
[DEBUG] Completed: cache.get, result_type=dict

[DEBUG] Executing: http.post, kwargs_keys=['url', 'json', 'headers']
[INFO] HTTP POST: /api/services/light/turn_on
[DEBUG] Completed: http.post, result_type=dict
```

### Rationale

**Why We Chose This:**

1. **Zero Deployment Overhead:**
   - Toggle via environment variable (seconds to change)
   - No code changes needed
   - No redeployment required
   - Instant troubleshooting capability

2. **Production-Safe Operation:**
   - Off by default (no cost when not needed)
   - Can enable temporarily for diagnosis
   - Only logs to CloudWatch (doesn't affect responses)
   - Acceptable performance overhead when enabled (~5μs/operation)

3. **Development Troubleshooting:**
   - See complete operation flow instantly
   - Understand request execution path
   - Identify unexpected operations
   - Debug complex interactions

4. **CloudWatch Integration:**
   - All debug output goes to CloudWatch Logs
   - Searchable, filterable logs
   - Retention policies apply
   - Standard AWS tooling works

### Alternatives Considered

**Alternative 1: Always-On Verbose Logging**
- **Description:** Log all operations always
- **Pros:**
  - Complete visibility always available
  - No toggling needed
  - Full history
- **Cons:**
  - **3-4x log volume increase** (costs)
  - Noise in production logs
  - Performance impact (unnecessary logging)
  - CloudWatch costs scale with volume
- **Why Rejected:** Wasteful when not debugging

**Alternative 2: X-Ray Tracing**
- **Description:** Use AWS X-Ray for operation tracing
- **Pros:**
  - Purpose-built tracing tool
  - Visual trace graphs
  - Industry standard
- **Cons:**
  - **Additional cost** ($5 per million traces)
  - More complex setup (X-Ray SDK, sampling)
  - Requires code instrumentation
  - Overkill for simple flow visibility
- **Why Rejected:** Too complex/costly for the need

**Alternative 3: Structured Log Levels**
- **Description:** Use INFO/DEBUG log levels only
- **Pros:**
  - Standard logging practice
  - LOG_LEVEL already exists
- **Cons:**
  - LOG_LEVEL=DEBUG enables too much (framework logs, etc.)
  - Not specific to operation flow
  - Less control over what's logged
  - More noise
- **Why Rejected:** Too coarse-grained

**Alternative 4: Deploy-Time Logging**
- **Description:** Add logging in code, redeploy
- **Pros:**
  - Maximum control
  - Can log anything
- **Cons:**
  - Slow (5-10 minutes per deployment)
  - Disruptive
  - Can't use in production emergencies
  - Can't toggle on/off quickly
- **Why Rejected:** Too slow for troubleshooting

### Trade-offs

**Accepted:**
- **5μs overhead per operation when enabled:** Negligible latency cost
  - Only applies when DEBUG_MODE=true
  - Typical operations 1-100ms (0.5% overhead)
  - Acceptable for troubleshooting

- **3-4x log volume increase when enabled:** Higher CloudWatch costs
  - Only during debugging (temporary)
  - Cost: ~$0.50 per million requests (at 3x volume)
  - Worth it for instant diagnosis
  - Can disable immediately after troubleshooting

**Benefits:**
- **Instant troubleshooting:** Toggle environment variable, see flow
- **No redeployment:** Change in seconds, not minutes
- **Production-safe:** Can use in production for diagnosis
- **Complete visibility:** See exactly what operations called

**Net Assessment:**
Strong positive. The ability to instantly see operation flow without redeployment is invaluable for troubleshooting. The costs (5μs overhead, log volume) are negligible compared to the time saved.

### Impact

**On Architecture:**
- Adds operational observability layer
- No architectural changes (logging only)
- Reinforces operation-centric design

**On Development:**
- Faster debugging cycles
- Easier to understand request flows
- Reduces "what's being called?" questions

**On Performance:**
- **When off (default):** Zero overhead (environment variable check once)
- **When on:** ~5μs per operation (negligible)
- **Log volume:** 3-4x increase when enabled

**On Maintenance:**
- **Usage Pattern:**
  ```bash
  # Enable for troubleshooting
  DEBUG_MODE=true
  
  # Reproduce issue, check CloudWatch logs
  
  # Disable when done
  DEBUG_MODE=false
  ```
- **CloudWatch Logs:** Filter by `[DEBUG]` prefix for operation flow
- **Cost Monitoring:** Track log volume when enabled

### Future Considerations

**When to Revisit:**
- If 5μs overhead becomes measurable (unlikely)
- If log volume becomes problematic (add sampling)
- If need more detailed information (enhance logging)

**Potential Evolution:**
- **Selective operation logging:** Log only specific interfaces
- **Sampling:** Log every Nth operation to reduce volume
- **Request ID tracking:** Link all operations in single request
- **Performance correlation:** Show timing alongside flow

**Monitoring:**
- Track DEBUG_MODE usage frequency
- Monitor log volume impact
- Measure troubleshooting time reduction
- Assess cost impact

---

## Related Topics

- **DEC-23**: DEBUG_TIMINGS - companion debugging feature
- **LESS-18**: Debug system lessons - experiences with this approach
- **DEC-22**: LAMBDA_MODE - related operational configuration
- **INT-02**: LOGGING interface - used for debug output
- **PATH-01**: Operation pathways - made visible by DEBUG_MODE
- **LESS-04**: Structured logging - debug logs follow structure

---

## Keywords

debugging, observability, troubleshooting, operational-tools, environment-variables, cloudwatch, flow-visibility, development-tools

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format with complete template
- **2025-10-20**: Decision documented in NM04-OPERATIONAL-Decisions.md

---

**File:** `NM04-Decisions-Operational_DEC-22.md`
**End of Document**

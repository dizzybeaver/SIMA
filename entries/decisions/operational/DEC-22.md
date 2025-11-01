# File: DEC-22.md

**REF-ID:** DEC-22  
**Category:** Operational Decision  
**Priority:** High  
**Status:** Active  
**Date Decided:** 2025-10-20  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-30 (SIMAv4 migration)

---

## 📋 SUMMARY

Environment variable `DEBUG_MODE=true` enables operation flow visibility (which operations called, with what parameters, in what order) without code changes or redeployment, supporting development troubleshooting and production diagnosis.

**Decision:** Environment-controlled debug visibility  
**Impact Level:** High  
**Reversibility:** High (toggle via environment variable)

---

## 🎯 CONTEXT

### Problem Statement
When investigating production issues or debugging complex request flows, we needed to see exactly which operations were called, in what order, with what parameters. Traditional approaches (adding print statements, deploying new versions) are slow and disruptive in Lambda environments.

### Background
- Production issues require immediate visibility
- Adding debug code requires deployment (slow)
- Print statements clutter normal logs
- Need selective logging without performance impact
- Lambda CloudWatch provides infrastructure

### Requirements
- Toggle debug logging without redeployment
- See operation execution flow
- Production-safe (minimal overhead when off)
- Acceptable performance impact when on
- Works with existing CloudWatch logging

---

## 💡 DECISION

### What We Chose
Implement `DEBUG_MODE` environment variable that enables operation flow logging. When enabled, log every operation call (interface, operation name, parameters) and completion (result type, success/failure).

### Implementation
```python
# Configuration
DEBUG_MODE = os.environ.get('DEBUG_MODE', 'false').lower() == 'true'

def execute_operation(operation, **kwargs):
    """Execute operation with optional debug logging."""
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

**Enabling Debug Mode:**
```bash
# Via Lambda console
DEBUG_MODE=true

# Via AWS CLI
aws lambda update-function-configuration \
  --function-name my-function \
  --environment Variables={DEBUG_MODE=true}

# Via Terraform
environment {
  variables = {
    DEBUG_MODE = "true"
  }
}
```

### Rationale
1. **Zero Deployment Overhead**
   - Toggle via environment variable (seconds to change)
   - No code changes needed
   - No redeployment required
   - Instant troubleshooting capability

2. **Production-Safe Operation**
   - Off by default (no cost when not needed)
   - Can enable temporarily for diagnosis
   - Only logs to CloudWatch (doesn't affect responses)
   - Acceptable performance overhead when enabled (~5μs/operation)

3. **Development Troubleshooting**
   - See complete operation flow instantly
   - Understand request execution path
   - Identify unexpected operations
   - Debug complex interactions

4. **Flexible Granularity**
   - Per-function control (different Lambdas different settings)
   - Temporary enablement (turn on, diagnose, turn off)
   - No permanent logging overhead

---

## 🔄 ALTERNATIVES CONSIDERED

### Alternative 1: Always-On Debug Logging
**Approach:** Log all operations all the time.

**Pros:**
- No configuration needed
- Always have visibility

**Cons:**
- Massive log volume (expensive)
- Performance overhead always present
- Log noise makes normal issues hard to find
- CloudWatch costs increase significantly

**Why Not Chosen:** Prohibitive cost and performance impact for rarely-needed capability.

### Alternative 2: Code-Based Debug Flag
**Approach:** Hard-code `DEBUG = True/False` in source code.

**Pros:**
- Simple to implement
- No environment variable needed

**Cons:**
- Requires code change + deployment to toggle
- Can't enable in production without deployment
- Slow iteration (deploy to debug)

**Why Not Chosen:** Too slow for production debugging scenarios.

### Alternative 3: X-Ray Distributed Tracing
**Approach:** Use AWS X-Ray for operation tracing.

**Pros:**
- Purpose-built for tracing
- Visual trace maps
- Automatic instrumentation

**Cons:**
- Additional cost ($5/million traces)
- Requires X-Ray SDK integration
- More complex setup
- Overhead always present

**Why Not Chosen:** Over-engineered for simple operation flow logging.

### Alternative 4: Separate Debug Lambda
**Approach:** Deploy debug version alongside production Lambda.

**Pros:**
- No performance impact on production
- Can debug freely

**Cons:**
- Maintaining two versions
- Can't debug production issues
- Separate infrastructure

**Why Not Chosen:** Can't diagnose production-specific issues.

---

## ⚖️ TRADE-OFFS

### Benefits
- **Instant debugging:** Enable with environment variable change
- **Production-safe:** No overhead when off
- **Flexible:** Per-function granularity
- **Simple:** Uses existing CloudWatch logging
- **Cost-effective:** Only pay for logs when enabled

### Costs
- **Performance overhead:** ~5μs per operation when enabled
- **Log volume:** Increases CloudWatch logs when enabled
- **Manual control:** Must remember to disable after debugging

### Net Assessment
The ability to instantly diagnose production issues far outweighs the minimal overhead when enabled. Essential troubleshooting tool with negligible costs.

---

## 📊 IMPACT ANALYSIS

### On Architecture
- **Impact Level:** Low
- **Description:** Logging enhancement only
- **Affected Components:** Operation execution wrapper
- **Migration Required:** None (additive feature)

### On Development
- **Impact Level:** High (positive)
- **Description:** Instant visibility into operation flow
- **Debugging:** Can see exactly what operations run
- **Iteration:** Faster debug cycles

### On Performance
- **Impact Level:** Low
- **When OFF:** Zero overhead (condition check: ~1ns)
- **When ON:** ~5μs per operation (log formatting + write)
- **Acceptable:** For debugging, minimal impact

### On Operations
- **Impact Level:** High (positive)
- **Production diagnosis:** Can enable without deployment
- **Troubleshooting:** See operation execution flow
- **Time to resolution:** Significantly faster

### On Cost
- **Impact Level:** Low
- **CloudWatch:** Increased log volume when enabled
- **Typical:** $0.50/GB ingested
- **Mitigation:** Only enable when needed

---

## 🔮 FUTURE CONSIDERATIONS

### When to Revisit
- If debug logging becomes too verbose (add log levels)
- If need structured operation traces (consider X-Ray)
- If debug overhead becomes problematic (optimize logging)

### Potential Evolution
- **Debug levels:** `DEBUG_MODE=verbose` for even more detail
- **Selective debugging:** `DEBUG_OPERATIONS=cache,http` (only certain operations)
- **Structured logs:** JSON format for easier parsing
- **Automatic disable:** Turn off after N minutes

### Monitoring
- Track DEBUG_MODE enablement across functions
- Monitor log volume increases
- Alert on DEBUG_MODE left enabled (> 24 hours)
- Measure debugging time savings

---

## 🔗 RELATED

### Related Decisions
- **DEC-23**: DEBUG_TIMINGS - Companion performance debugging
- **DEC-20**: LAMBDA_MODE - Similar environment-based control
- **DEC-06**: Logging Interface - Underlying logging implementation

### Related Lessons
- **LESS-18**: Debug system design lessons
- **LESS-11**: Logging strategy patterns

### Related Architecture
- **INT-06**: Logging Interface - Debug logging implementation
- **GATE-01**: Gateway Structure - Where debug logging happens

---

## 🏷️ KEYWORDS

debugging, logging, troubleshooting, operations, cloudwatch, environment-variables, production-debugging, flow-visibility, development-tools

---

## 📚 VERSION HISTORY

- **2025-10-30**: Migrated to SIMAv4 format
- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2025-10-20**: Original decision documented

---

**File:** `DEC-22.md`  
**Path:** `/sima/entries/decisions/operational/DEC-22.md`  
**End of Document**

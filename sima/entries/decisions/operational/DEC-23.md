# File: DEC-23.md

**REF-ID:** DEC-23  
**Category:** Operational Decision  
**Priority:** High  
**Status:** Active  
**Date Decided:** 2025-10-20  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-30 (SIMAv4 migration)

---

## 📋 SUMMARY

Environment variable `DEBUG_TIMINGS=true` enables performance measurement for all operations, providing data-driven insights for optimization without requiring code changes or redeployment. Implements "measure don't guess" principle (LESS-02).

**Decision:** Environment-controlled performance timing  
**Impact Level:** High  
**Reversibility:** High (toggle via environment variable)

---

## 🎯 CONTEXT

### Problem Statement
Performance optimization should be data-driven (LESS-02: "Measure don't guess"), but adding timing instrumentation requires code changes and redeployment. During optimization work, need quick feedback: "Did this change make it faster?" "Which operation is the bottleneck?"

### Background
- Lambda performance varies by environment
- Cold start vs warm container
- Different memory configurations
- AWS region variations
- Need actual measurements in real conditions

### Requirements
- Measure operation execution time
- Toggle without redeployment
- Production-safe (minimal overhead)
- Identify performance bottlenecks
- Validate optimization effectiveness

---

## 💡 DECISION

### What We Chose
Implement `DEBUG_TIMINGS` environment variable that wraps every operation with timing measurement. When enabled, log operation execution time in milliseconds using high-precision timer.

### Implementation
```python
# Configuration
DEBUG_TIMINGS = os.environ.get('DEBUG_TIMINGS', 'false').lower() == 'true'

def execute_operation(operation, **kwargs):
    """Execute operation with optional timing."""
    if DEBUG_TIMINGS:
        start = time.perf_counter()
    
    result = OPERATIONS[operation](**kwargs)
    
    if DEBUG_TIMINGS:
        elapsed = (time.perf_counter() - start) * 1000  # Convert to ms
        log_info(f"[TIMING] {operation}: {elapsed:.2f}ms")
    
    return result
```

**Output Example:**
```
[INFO] Cache get: user_123
[TIMING] cache.get: 0.85ms

[INFO] HTTP POST: /api/services/light/turn_on
[TIMING] http.post: 125.43ms

[INFO] SSM get parameter: /lee/token
[TIMING] ssm.get_parameter: 248.67ms

[INFO] Config get: HOME_ASSISTANT_URL
[TIMING] config.get: 0.12ms
```

**Enabling Timing:**
```bash
# Via Lambda console
DEBUG_TIMINGS=true

# Via AWS CLI
aws lambda update-function-configuration \
  --function-name my-function \
  --environment Variables={DEBUG_TIMINGS=true}

# Via Terraform
environment {
  variables = {
    DEBUG_TIMINGS = "true"
  }
}
```

### Rationale
1. **Data-Driven Optimization (LESS-02)**
   - "Measure don't guess" - see actual timings
   - Identify real bottlenecks (not assumed ones)
   - Validate optimization effectiveness
   - Quantify improvement with hard data

2. **Zero Deployment Overhead**
   - Toggle via environment variable
   - No code changes needed
   - Instant performance visibility
   - Compare before/after without redeploy

3. **Production-Safe Measurement**
   - Off by default (no cost when not optimizing)
   - Can enable temporarily in production
   - ~10μs overhead per operation (negligible)
   - Logs to CloudWatch (doesn't affect responses)

4. **Optimization Validation**
   - Enable DEBUG_TIMINGS, measure baseline
   - Make change
   - Measure again, compare
   - Quantified speedup proof

---

## 🔄 ALTERNATIVES CONSIDERED

### Alternative 1: Manual Timing Instrumentation
**Approach:** Add timing code manually where needed.

**Pros:**
- Precise control
- Can time specific code blocks

**Cons:**
- Requires code changes for each timing
- Slow (redeploy for each measurement)
- Temporary code clutters codebase
- Easy to forget to remove

**Why Not Chosen:** Too slow for optimization workflow.

### Alternative 2: CloudWatch Metrics
**Approach:** Publish operation timings as custom metrics.

**Pros:**
- Purpose-built for metrics
- Graphing and alerting built-in
- Historical data retention

**Cons:**
- Additional cost ($0.30 per metric per month)
- More complex (metrics API, dimensions)
- Overhead of API calls
- Overkill for temporary optimization work

**Why Not Chosen:** Too costly for temporary debugging.

### Alternative 3: APM Tool (New Relic, Datadog)
**Approach:** Use professional APM solution.

**Pros:**
- Comprehensive monitoring
- Beautiful dashboards
- Advanced features (flame graphs, etc.)

**Cons:**
- Significant cost ($15-100/month)
- Complex integration
- Requires vendor SDK
- Overkill for simple timing

**Why Not Chosen:** Disproportionate cost/complexity.

### Alternative 4: Always-On Timing
**Approach:** Always log operation timings.

**Pros:**
- Complete historical data
- No toggling needed
- Always available

**Cons:**
- 2-3x log volume increase (costs)
- Noise in logs when not optimizing
- Unnecessary overhead always present

**Why Not Chosen:** Wasteful when not doing optimization work.

---

## ⚖️ TRADE-OFFS

### Benefits
- **Quantified optimization:** Hard numbers, not guesses
- **Bottleneck identification:** See slowest operations instantly
- **Regression detection:** Catch unexpected slowdowns
- **Optimization validation:** Prove improvements work
- **Cost-effective:** Only pay for logs when enabled

### Costs
- **Performance overhead:** ~10μs per operation when enabled
  - For 1ms operation: 1% overhead
  - For 100ms operation: 0.01% overhead
  - Acceptable for temporary optimization
- **Log volume:** 2-3x increase when enabled (~$0.30-$0.60 per million requests)
- **Manual control:** Must remember to disable after debugging

### Net Assessment
Strong positive. Performance optimization is dramatically more effective with timing data. The ability to toggle timing on/off without redeployment makes the optimization cycle much faster.

---

## 📊 IMPACT ANALYSIS

### On Architecture
- **Impact Level:** Low
- **Description:** Measurement layer only, no architectural changes
- **Affected Components:** Operation execution wrapper
- **Observability:** Adds performance visibility

### On Development
- **Impact Level:** High (positive)
- **Description:** Faster optimization cycle
- **Workflow:** Enable → Run → Analyze → Change → Compare → Repeat
- **Time savings:** Much faster than: Change → Deploy → Test → Repeat

### On Performance
- **Impact Level:** Low
- **When OFF (default):** Zero overhead
- **When ON:** ~10μs per operation (negligible)
- **Enables optimization:** Timings identify opportunities

### On Maintenance
- **Impact Level:** Low
- **Usage Pattern:**
  ```bash
  # Enable for optimization work
  DEBUG_TIMINGS=true
  
  # Run tests, check CloudWatch logs
  
  # Analyze bottlenecks, make changes
  
  # Measure again, compare
  
  # Disable when done
  DEBUG_TIMINGS=false
  ```

### Performance Baselines
```
Cold start targets:
- Total: <500ms
- Module imports: <200ms
- Gateway init: <50ms

Operation targets:
- Cache get (hit): <1ms
- Cache get (miss): <5ms
- SSM call (cached): <2ms
- SSM call (API): <250ms
- HTTP request: <100ms (local network)
```

---

## 🔮 FUTURE CONSIDERATIONS

### When to Revisit
- If 10μs overhead becomes measurable (unlikely)
- If need more detailed timing (add sub-operation timing)
- If want historical trending (consider metrics)

### Potential Evolution
- **Percentile analysis:** P50, P95, P99 timings
- **Operation correlation:** Show timing relationships
- **Memory profiling:** Add memory usage alongside timing
- **Flame graphs:** Visual performance analysis
- **Automatic baselines:** Track typical performance

### Monitoring
- Track DEBUG_TIMINGS usage across functions
- Monitor log volume impact
- Measure optimization effectiveness
- Assess cost impact

---

## 🔗 RELATED

### Related Decisions
- **DEC-22**: DEBUG_MODE - Companion debugging feature
- **DEC-21**: SSM Token-Only - Optimization validated with timings
- **DEC-13**: Fast Path Caching - Performance pattern

### Related Lessons
- **LESS-02**: Measure don't guess - Core principle this enables
- **LESS-18**: Debug system lessons - Timing experiences

### Related Architecture
- **INT-06**: Logging Interface - Used for timing output
- **GATE-01**: Gateway Structure - Where timing happens

---

## 🏷️ KEYWORDS

performance, timing, optimization, measurement, profiling, data-driven, cloudwatch, debugging, metrics, benchmarking

---

## 📚 VERSION HISTORY

- **2025-10-30**: Migrated to SIMAv4 format
- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2025-10-20**: Original decision documented

---

**File:** `DEC-23.md`  
**Path:** `/sima/entries/decisions/operational/DEC-23.md`  
**End of Document**

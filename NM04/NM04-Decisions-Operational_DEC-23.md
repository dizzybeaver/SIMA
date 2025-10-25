# NM04-Decisions-Operational_DEC-23.md - DEC-23

# DEC-23: DEBUG_TIMINGS Performance Tracking

**Category:** Decisions
**Topic:** Operational
**Priority:** High
**Status:** Active
**Date Decided:** 2025-10-20
**Created:** 2025-10-23
**Last Updated:** 2025-10-23

---

## Summary

Environment variable `DEBUG_TIMINGS=true` enables performance measurement for all operations, providing data-driven insights for optimization without requiring code changes or redeployment.

---

## Context

Performance optimization should be data-driven (LESS-02: "Measure don't guess"), but adding timing instrumentation requires code changes and redeployment. During optimization work, we needed quick feedback: "Did this change make it faster?" "Which operation is the bottleneck?"

Lambda's performance characteristics vary by environment (cold start vs warm, different memory configurations, AWS region). We needed a way to measure actual performance in real conditions, togglable for when optimization work is needed.

---

## Content

### The Decision

**What We Chose:**
Implement `DEBUG_TIMINGS` environment variable that wraps every operation with timing measurement. When enabled, log operation execution time in milliseconds.

**Implementation:**
```python
DEBUG_TIMINGS = os.environ.get('DEBUG_TIMINGS', 'false').lower() == 'true'

def execute_operation(operation, **kwargs):
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

[INFO] Config get: HOME_ASSISTANT_URL
[TIMING] config.get: 0.12ms
```

### Rationale

**Why We Chose This:**

1. **Data-Driven Optimization (LESS-02):**
   - "Measure don't guess" - see actual timings
   - Identify real bottlenecks (not assumed ones)
   - Validate optimization effectiveness
   - Quantify improvement with hard data

2. **Zero Deployment Overhead:**
   - Toggle via environment variable
   - No code changes needed
   - Instant performance visibility
   - Can compare before/after without redeploy

3. **Production-Safe Measurement:**
   - Off by default (no cost when not optimizing)
   - Can enable temporarily in production
   - ~10μs overhead per operation (negligible)
   - Logs to CloudWatch (doesn't affect responses)

4. **Optimization Validation:**
   - **Before change:** Enable DEBUG_TIMINGS, measure baseline
   - **After change:** Measure again, compare
   - **Proof of improvement:** Quantified speedup
   - **Regression detection:** Unexpected slowdowns visible

### Alternatives Considered

**Alternative 1: Manual Timing Instrumentation**
- **Description:** Add timing code where needed
- **Pros:**
  - Precise control
  - Can time specific code blocks
- **Cons:**
  - Requires code changes for each timing
  - Slow (redeploy for each measurement)
  - Temporary code clutters codebase
  - Easy to forget to remove
- **Why Rejected:** Too slow for optimization workflow

**Alternative 2: CloudWatch Metrics**
- **Description:** Publish operation timings as custom metrics
- **Pros:**
  - Purpose-built for metrics
  - Graphing, alerting built-in
  - Historical data
- **Cons:**
  - **Additional cost** ($0.30 per metric per month)
  - More complex (metrics API, dimensions)
  - Overhead of API calls
  - Overkill for temporary optimization work
- **Why Rejected:** Too costly for temporary debugging

**Alternative 3: APM Tool (New Relic, Datadog)**
- **Description:** Use professional APM solution
- **Pros:**
  - Comprehensive monitoring
  - Beautiful dashboards
  - Advanced features
- **Cons:**
  - **Significant cost** ($15-100/month)
  - Complex integration
  - Requires vendor SDK
  - Overkill for simple timing
- **Why Rejected:** Disproportionate cost/complexity

**Alternative 4: Always-On Timing**
- **Description:** Always log operation timings
- **Pros:**
  - Complete historical data
  - No toggling needed
- **Cons:**
  - **2-3x log volume increase** (costs)
  - Noise in logs when not optimizing
  - Unnecessary overhead
- **Why Rejected:** Wasteful when not doing optimization work

### Trade-offs

**Accepted:**
- **10μs overhead per operation when enabled:** Timing measurement cost
  - time.perf_counter() is fast but not free
  - For 1ms operation: 1% overhead
  - For 100ms operation: 0.01% overhead
  - Acceptable for temporary optimization work

- **2-3x log volume increase when enabled:** Higher CloudWatch costs
  - Cost: ~$0.30-$0.60 per million requests
  - Temporary (only during optimization)
  - Can disable immediately after measuring

**Benefits:**
- **Quantified optimization:** Hard numbers, not guesses
- **Bottleneck identification:** See slowest operations instantly
- **Regression detection:** Catch unexpected slowdowns
- **Optimization validation:** Prove improvements work

**Net Assessment:**
Strong positive. Performance optimization is dramatically more effective with timing data. The ability to toggle timing on/off without redeployment makes the optimization cycle much faster.

### Impact

**On Architecture:**
- No architectural changes (measurement only)
- Adds performance observability layer
- Supports data-driven decisions

**On Development:**
- **Faster optimization cycle:**
  1. Enable DEBUG_TIMINGS
  2. Run workload
  3. Analyze timings
  4. Make change
  5. Compare timings
  6. Repeat
- Much faster than: Change → Deploy → Test → Repeat

**On Performance:**
- **When off (default):** Zero overhead
- **When on:** ~10μs per operation (negligible)
- **Enables optimization:** Timings identify opportunities

**On Maintenance:**
- **Performance Baselines:**
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

### Future Considerations

**When to Revisit:**
- If 10μs overhead becomes measurable (unlikely)
- If need more detailed timing (add sub-operation timing)
- If want historical trending (consider metrics)

**Potential Evolution:**
- **Percentile analysis:** P50, P95, P99 timings
- **Operation correlation:** Show timing relationships
- **Memory profiling:** Add memory usage alongside timing
- **Flame graphs:** Visual performance analysis
- **Automatic baselines:** Track typical performance

**Monitoring:**
- Track DEBUG_TIMINGS usage
- Monitor log volume impact
- Measure optimization effectiveness
- Assess cost impact

---

## Related Topics

- **DEC-22**: DEBUG_MODE - companion debugging feature
- **LESS-02**: Measure don't guess - core principle this enables
- **PATH-01**: Cold start pathway - optimized using this data
- **DEC-21**: SSM token-only - optimization validated with timings
- **LESS-18**: Debug system lessons - timing experiences
- **INT-04**: METRICS interface - could be extended with this data

---

## Keywords

performance, timing, optimization, measurement, profiling, data-driven, cloudwatch, debugging, metrics

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format with complete template
- **2025-10-20**: Decision documented in NM04-OPERATIONAL-Decisions.md

---

**File:** `NM04-Decisions-Operational_DEC-23.md`
**End of Document**

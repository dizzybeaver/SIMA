# NM04-Decisions-Operational_DEC-20.md - DEC-20

# DEC-20: LAMBDA_MODE Over LEE_FAILSAFE_ENABLED

**Category:** Decisions
**Topic:** Operational
**Priority:** Critical
**Status:** Active
**Date Decided:** 2025-10-20
**Created:** 2025-10-23
**Last Updated:** 2025-10-23

---

## Summary

Replace binary `LEE_FAILSAFE_ENABLED=true` with enumerated `LAMBDA_MODE=failsafe` for extensible operational mode system that supports multiple modes (normal, failsafe, diagnostic, test).

---

## Context

The Lambda Execution Engine initially used `LEE_FAILSAFE_ENABLED=true/false` to toggle failsafe mode. As operational needs evolved, we identified need for additional modes: diagnostic mode for troubleshooting, test mode for deployment verification, performance mode for benchmarking.

A binary flag couldn't support multiple modes. We needed an extensible system that could grow with operational requirements while maintaining clarity and consistency with other Lambda environment variables.

---

## Content

### The Decision

**What We Chose:**
Replace `LEE_FAILSAFE_ENABLED` with `LAMBDA_MODE` enumerated environment variable. Supports multiple operational modes with clear, self-documenting values.

**Migration:**
```bash
# Old (deprecated)
LEE_FAILSAFE_ENABLED=true

# New (current)
LAMBDA_MODE=failsafe
```

**Supported Modes:**
- `normal` (default) - Full LEE/SUGA operation
- `failsafe` - Emergency bypass mode (equivalent to old `LEE_FAILSAFE_ENABLED=true`)
- `diagnostic` (future) - Enhanced troubleshooting with verbose logging
- `test` (future) - Deployment verification mode
- `performance` (future) - Benchmarking mode

### Rationale

**Why We Chose This:**

1. **Extensibility:**
   - Binary (on/off) → Enumerated (normal, failsafe, diagnostic, test, ...)
   - Easy to add new modes without breaking existing deployments
   - Future-proof operational model
   - Supports evolving operational requirements

2. **Clearer Intent:**
   - `LEE_FAILSAFE_ENABLED=true` → What's enabled? Failsafe? LEE?
   - `LAMBDA_MODE=failsafe` → Crystal clear what mode Lambda is in
   - Self-documenting configuration
   - Reduces operational errors from confusion

3. **Consistent Pattern:**
   - Matches `LOG_LEVEL=INFO`, `ENVIRONMENT=production` patterns
   - Standard configuration style across AWS ecosystem
   - Familiar pattern for operations teams
   - Easier to document and understand

4. **Future Operational Modes:**
   - **Diagnostic mode:** Verbose logging, health checks, no rate limits
   - **Test mode:** Synthetic workloads, deployment verification
   - **Performance mode:** Benchmarking, metric collection
   - **Canary mode:** Gradual rollout testing
   - **Maintenance mode:** Read-only, graceful degradation

### Alternatives Considered

**Alternative 1: Keep Binary Flag (Status Quo)**
- **Description:** Continue with `LEE_FAILSAFE_ENABLED=true/false`
- **Pros:**
  - No migration needed
  - Simple on/off semantics
  - Already deployed and working
- **Cons:**
  - Can't support multiple modes
  - Would need additional flags (`LEE_DIAGNOSTIC_ENABLED`, etc.)
  - Flag proliferation problem
  - Conflicts possible (multiple flags enabled)
- **Why Rejected:** Doesn't scale, flag explosion inevitable

**Alternative 2: Multiple Binary Flags**
- **Description:** Add `LEE_DIAGNOSTIC_ENABLED`, `LEE_TEST_ENABLED`, etc.
- **Pros:**
  - Can mix modes (diagnostic + failsafe)
  - Independent toggles
- **Cons:**
  - Confusing mode combinations
  - What if multiple flags set?
  - Configuration complexity explosion
  - Hard to reason about system state
- **Why Rejected:** Too complex, error-prone

**Alternative 3: Numeric Mode Codes**
- **Description:** `LAMBDA_MODE=0` (normal), `=1` (failsafe), `=2` (diagnostic)
- **Pros:**
  - Compact
  - Easy to parse programmatically
- **Cons:**
  - Not self-documenting (what's mode 2?)
  - Requires lookup table
  - Error-prone (wrong number)
  - Poor operational experience
- **Why Rejected:** Sacrifices clarity for brevity

### Trade-offs

**Accepted:**
- **One-time migration:** Existing deployments need environment variable update
  - But migration is straightforward (documented)
  - One-time cost for long-term benefit
  - Can maintain backward compatibility temporarily

- **Mode exclusivity:** Can only be in one mode at a time
  - But this is actually a benefit (clear state)
  - If need multiple modes, can use sub-modes
  - Simpler to reason about

**Benefits:**
- **Extensible:** Easy to add new modes
- **Clear:** Self-documenting configuration
- **Standard:** Matches AWS patterns
- **Maintainable:** Single source of truth for mode

**Net Assessment:**
Clear positive. The one-time migration cost is trivial compared to the long-term extensibility and clarity benefits. This decision enables future operational capabilities without technical debt.

### Impact

**On Architecture:**
- Cleaner operational configuration model
- Supports future operational features
- No impact on core architecture (configuration only)

**On Development:**
- Code checks `LAMBDA_MODE` instead of `LEE_FAILSAFE_ENABLED`
- Easy to add mode-specific behavior
- More readable configuration checking

**On Performance:**
- No performance impact (environment variable lookup same)
- String comparison vs boolean (negligible difference)

**On Maintenance:**
- **Migration Required:** One-time update to environment variables
  ```bash
  # Remove old variable
  unset LEE_FAILSAFE_ENABLED
  
  # Set new variable
  LAMBDA_MODE=failsafe  # Or omit for default 'normal'
  ```
- **Backward Compatibility:** Can maintain both for transition period
- **Documentation:** Update all deployment docs with new variable

### Future Considerations

**When to Revisit:**
- If mode combinations needed (consider sub-modes)
- If modes become too numerous (group into categories)
- If operational needs change significantly

**Potential Evolution:**
- **Diagnostic Mode:** `LAMBDA_MODE=diagnostic`
  - Verbose logging, health checks, introspection endpoints
  - No rate limiting, extended timeouts
  - Performance profiling enabled

- **Test Mode:** `LAMBDA_MODE=test`
  - Synthetic workloads, deployment verification
  - Mock external dependencies
  - Automated health checks

- **Performance Mode:** `LAMBDA_MODE=performance`
  - Detailed metrics collection
  - Benchmarking enabled
  - Operation timing breakdowns

- **Sub-modes:** `LAMBDA_MODE=normal:verbose` or `LAMBDA_MODE=failsafe:debug`

**Monitoring:**
- Track mode usage distribution
- Monitor mode transitions
- Alert on unexpected modes (typos)
- Measure operational effectiveness per mode

---

## Related Topics

- **LESS-17**: Operational lessons - mode design experiences
- **DEC-21**: SSM token-only - companion operational decision (same date)
- **DEC-22**: DEBUG_MODE - related diagnostic configuration
- **PATH-03**: Failsafe pathway - mode determines pathway
- **CONFIG-01**: Configuration management - mode is configuration value
- **LESS-10**: Configuration simplification - this improves config clarity

---

## Keywords

operational-modes, configuration, environment-variables, failsafe, extensibility, deployment, operations, lambda-config

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format with complete template
- **2025-10-20**: Decision documented in NM04-OPERATIONAL-Decisions.md

---

**File:** `NM04-Decisions-Operational_DEC-20.md`
**End of Document**

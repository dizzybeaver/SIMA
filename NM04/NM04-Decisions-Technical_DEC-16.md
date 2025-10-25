# NM04-Decisions-Technical_DEC-16.md - DEC-16

# DEC-16: Import Error Protection

**Category:** Decisions
**Topic:** Technical
**Priority:** High
**Status:** Active
**Date Decided:** 2024-06-20
**Created:** 2025-10-23
**Last Updated:** 2025-10-23

---

## Summary

Wrap interface imports in try/except blocks for graceful degradation - Lambda can still operate if non-critical interface fails to import.

---

## Context

During deployment of the Home Assistant extension, a dependency conflict caused the `homeassistant_extension` module to fail import. This killed the entire Lambda, preventing even diagnostic operations from working. We had a total outage when we should have had partial degradation.

The Lambda Execution Engine handles multiple types of requests: Home Assistant control, diagnostics, cache operations, etc. A failure in one extension shouldn't break unrelated functionality.

---

## Content

### The Decision

**What We Chose:**
Protect interface imports with try/except blocks. Set availability flags to track which interfaces successfully loaded. Non-critical operations gracefully degrade when interfaces unavailable.

**Implementation:**
```python
try:
    from interface_homeassistant import router as ha_router
    HAS_HOME_ASSISTANT = True
except ImportError as e:
    log_warning(f"Home Assistant unavailable: {e}")
    HAS_HOME_ASSISTANT = False
    ha_router = None
```

### Rationale

**Why We Chose This:**

1. **Graceful Degradation Over Total Failure:**
   - Partial functionality better than no functionality
   - Diagnostic tools still work even if extension fails
   - Cache operations unaffected by Home Assistant issues
   - System remains partially operational during incidents

2. **Faster Incident Response:**
   - Diagnostic interface always available to troubleshoot
   - Can identify which interface failed from logs
   - No need to roll back entire deployment
   - Reduced Mean Time To Recovery (MTTR)

3. **Supports Extension Architecture:**
   - Extensions are by definition optional
   - Core Lambda functionality should never depend on extensions
   - Makes extension development safer
   - Encourages modular design

4. **Clear Availability Signaling:**
   - Boolean flags (`HAS_HOME_ASSISTANT`) make status explicit
   - Can report unavailable features to callers
   - Easy to check before attempting operations
   - Supports feature discovery

### Alternatives Considered

**Alternative 1: Fail Fast (Original Behavior)**
- **Description:** Let import errors crash the Lambda
- **Pros:**
  - Simple - no error handling needed
  - Forces fixing issues immediately
  - Clear signal that something is wrong
- **Cons:**
  - Total outage for partial problem
  - Can't use diagnostic tools during outage
  - Difficult to troubleshoot root cause
  - Poor user experience
- **Why Rejected:** Total failure inappropriate for partial problem

**Alternative 2: Dynamic Import on First Use**
- **Description:** Import interfaces only when called, catch errors then
- **Pros:**
  - Delays import until actually needed
  - More precise error timing
- **Cons:**
  - First request pays import cost
  - Error happens during user request (bad UX)
  - Mixing lazy loading with error handling (complex)
  - Less clear what's available
- **Why Rejected:** DEC-14 already handles lazy loading; this is about import errors

**Alternative 3: Separate Lambda for Each Interface**
- **Description:** Deploy each interface as its own Lambda
- **Pros:**
  - Total isolation
  - One failure doesn't affect others
- **Cons:**
  - Much higher complexity
  - More deployment overhead
  - Difficult to share code/state
  - Architectural overkill
- **Why Rejected:** Way too complex for the problem

### Trade-offs

**Accepted:**
- **Warning logs for missing interfaces:** May cause noise in logs
  - But visibility into what's unavailable is valuable
  - Can filter warnings if needed
  - Better than silent unavailability

- **Need to check availability flags:** Callers must check `HAS_*` before use
  - Adds minor complexity to calling code
  - But makes interface availability explicit
  - Prevents runtime errors from missing interfaces

**Benefits:**
- **Partial availability during incidents:** Critical functionality remains
- **Better debuggability:** Diagnostic tools always work
- **Safer deployments:** Extension problems don't kill core
- **Faster recovery:** Can diagnose without rollback

**Net Assessment:**
Clear positive. The cost is minimal (checking availability flags), and the benefit is significant (partial operation beats total failure). This decision has prevented multiple complete outages.

### Impact

**On Architecture:**
- Reinforces extension pattern (extensions are optional)
- Core interfaces vs optional interfaces distinction
- Establishes availability signaling pattern

**On Development:**
- Extension developers can work more safely
- Broken extensions don't block core development
- Testing easier (can test with/without extensions)

**On Performance:**
- No performance impact (flag checks are negligible)
- Failed imports handled at startup, not per-request

**On Maintenance:**
- Easier to identify failing interfaces (warning logs)
- Can deploy partial fixes without full rollback
- Supports gradual feature rollouts

### Future Considerations

**When to Revisit:**
- If availability checking becomes burdensome
- If pattern needs extension to dependencies
- If need more sophisticated health checks

**Potential Evolution:**
- **Health check endpoint:** Report which interfaces available
- **Automatic retry:** Try reimporting failed interfaces periodically
- **Graceful feature degradation:** Disable features requiring unavailable interfaces
- **Dependency health monitoring:** Track interface availability metrics

**Monitoring:**
- Track interface import success rates
- Alert on newly unavailable interfaces
- Monitor degraded operation frequency
- Measure MTTR improvement

---

## Related Topics

- **DEC-14**: Lazy loading - complements import error protection
- **DEC-17**: Home Assistant as Mini-ISP - extension that benefits from this
- **AP-02**: Uncaught import errors - this prevents this anti-pattern
- **ERR-02**: Error propagation - import errors caught early
- **LESS-08**: Graceful degradation - key lesson that informed this decision
- **PATH-02**: Error pathway - import errors are first checkpoint

---

## Keywords

import-errors, graceful-degradation, error-handling, availability, extensions, partial-failure, resilience, reliability

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format with complete template
- **2024-06-20**: Decision documented in NM04-TECHNICAL-Decisions.md

---

**File:** `NM04-Decisions-Technical_DEC-16.md`
**End of Document**

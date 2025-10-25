# NM04-Decisions-Technical_DEC-15.md - DEC-15

# DEC-15: Router-Level Exception Catching

**Category:** Decisions
**Topic:** Technical
**Priority:** High
**Status:** Active
**Date Decided:** 2024-06-18
**Created:** 2025-10-23
**Last Updated:** 2025-10-23

---

## Summary

Interface routers catch exceptions and log before re-raising, guaranteeing error information is captured even if exception propagates up the call stack.

---

## Context

Early production incidents revealed that exceptions thrown deep in core modules sometimes propagated without any logging, making debugging extremely difficult. We had "silent failures" where Lambda would return an error to the caller, but CloudWatch logs provided no information about what went wrong.

The challenge was ensuring every error gets logged at least once, while still allowing exceptions to propagate naturally for proper error handling at higher layers.

---

## Content

### The Decision

**What We Chose:**
Implement catch-log-reraise pattern at the interface router level. Every interface router wraps operations in try/except blocks that log the error before re-raising the original exception.

**Implementation:**
```python
def execute_operation(operation, **kwargs):
    try:
        return OPERATIONS[operation](**kwargs)
    except Exception as e:
        log_error(f"Operation {operation} failed", error=e)
        raise  # Re-raise after logging
```

### Rationale

**Why We Chose This:**

1. **Guaranteed Error Visibility:**
   - Every error gets logged exactly once (at router boundary)
   - No more "silent failures" in production
   - CloudWatch logs always contain error information
   - Critical for troubleshooting production issues

2. **Optimal Logging Location:**
   - Router layer knows operation context (which interface, which function)
   - Can log meaningful operation name and parameters
   - Single point of logging (not scattered throughout code)
   - Easy to add structured logging fields

3. **Preserves Exception Propagation:**
   - Original exception still propagates to caller
   - Maintains exception type and stack trace
   - Allows higher-level handlers to make decisions
   - Doesn't mask or swallow errors

4. **Minimal Performance Cost:**
   - Try/except overhead: ~10μs per operation
   - Only catches actual errors (no performance impact on success path)
   - Logging is fast (already optimized in logging_core)
   - Negligible compared to operation execution time

### Alternatives Considered

**Alternative 1: Core-Level Logging**
- **Description:** Each core module logs its own errors
- **Pros:**
  - Logging happens closest to error source
  - Most detailed context available
- **Cons:**
  - Logging code scattered across 20+ core files
  - Easy to miss logging in new code
  - Inconsistent log formats
  - Difficult to enforce logging discipline
- **Why Rejected:** Too fragile, relies on developer discipline

**Alternative 2: Gateway-Level Logging Only**
- **Description:** Catch and log all errors at top-level gateway
- **Pros:**
  - Single logging point
  - Very simple implementation
- **Cons:**
  - Loses operation context (don't know which interface failed)
  - Stack traces less useful
  - Can't log operation-specific details
  - Gateway doesn't know what operation was attempted
- **Why Rejected:** Loses too much diagnostic information

**Alternative 3: No Exception Catching (Let Propagate)**
- **Description:** Rely on top-level Lambda handler to catch/log
- **Pros:**
  - Simplest code (no try/except blocks)
  - Natural exception flow
- **Cons:**
  - Lambda handler doesn't know operation context
  - Silent failures if exception caught somewhere in between
  - Loses interface-level diagnostics
  - No guarantee of logging
- **Why Rejected:** Original problem (silent failures) remains

### Trade-offs

**Accepted:**
- **10μs overhead per operation:** Negligible cost for guaranteed logging
  - Try/except is very fast in Python when no exception
  - Only applies when exception actually raised
  - Worth it for production debuggability

- **Exceptions logged twice if higher layer also logs:** Minor duplicate log entries
  - First log: Router level (operation context)
  - Second log: Higher level (request context)
  - Both provide different useful information
  - CloudWatch can filter duplicates if needed

**Benefits:**
- **Zero silent failures:** Every error visible in CloudWatch
- **Rich diagnostic context:** Operation, interface, parameters logged
- **Maintainable pattern:** Clear, consistent error handling
- **Production confidence:** Can always debug from logs

**Net Assessment:**
Strongly positive. The 10μs overhead is completely negligible, and having guaranteed error logging has saved hours of debugging time in production. This pattern has proven its worth repeatedly.

### Impact

**On Architecture:**
- Establishes router layer as error boundary
- Reinforces interface as abstraction layer
- Separates error logging from error handling

**On Development:**
- New interfaces automatically get error logging
- Developers don't need to remember to log errors
- Consistent error logging pattern across all interfaces

**On Performance:**
- **Success path:** ~10μs overhead (negligible)
- **Error path:** Adds logging time before re-raise
- **Production:** No measurable impact on latency metrics

**On Maintenance:**
- Single place to enhance error logging (router template)
- Easy to add structured fields (request IDs, user context)
- Can add monitoring/alerting hooks
- Reduces debugging time significantly

### Future Considerations

**When to Revisit:**
- If 10μs overhead becomes measurable (unlikely)
- If duplicate logging becomes problematic (can dedupe)
- If error context needs enrichment (easy to add)

**Potential Evolution:**
- **Structured error logging:** Add operation metrics, timing
- **Error categorization:** Tag errors by severity/type
- **Automatic retry:** Some errors could trigger retry logic
- **Error rate monitoring:** Track failure rates per interface

**Monitoring:**
- Track error rates per interface
- Monitor error types distribution
- Alert on unusual error patterns
- Measure debugging time reduction

---

## Related Topics

- **ERR-02**: Error propagation patterns - this implements clean propagation
- **AP-14**: Bare except anti-pattern - we use typed exceptions, not bare except
- **PATH-02**: Error pathway - router logging is key checkpoint
- **DEC-01**: SIMA pattern - router layer is natural error boundary
- **LESS-04**: Structured logging - errors benefit from structured format
- **INT-02**: LOGGING interface - used for error logging

---

## Keywords

error-handling, exception-catching, logging, router-pattern, debugging, observability, catch-reraise, production-reliability

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format with complete template
- **2024-06-18**: Decision documented in NM04-TECHNICAL-Decisions.md

---

**File:** `NM04-Decisions-Technical_DEC-15.md`
**End of Document**

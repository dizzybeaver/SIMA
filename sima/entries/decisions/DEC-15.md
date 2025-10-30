# File: DEC-15.md

**REF-ID:** DEC-15  
**Category:** Technical Decision  
**Priority:** High  
**Status:** Active  
**Date Decided:** 2024-06-18  
**Created:** 2024-06-18  
**Last Updated:** 2025-10-29 (SIMAv4 migration)

---

## 📋 SUMMARY

Interface routers catch exceptions and log before re-raising, ensuring all operation attempts are logged for debugging even when operations fail.

**Decision:** Router-level exception catching mandatory  
**Impact Level:** High  
**Reversibility:** Easy

---

## 🎯 CONTEXT

### Problem Statement
Operations failing in core layer left no trace in logs because exception propagated straight to Lambda handler. Lost debugging context, no record of what was attempted.

### Background
- Core operations can fail (network, validation, etc.)
- Without logging, failure invisible
- Hard to debug intermittent issues
- No audit trail of failed operations

### Requirements
- Log all operation attempts
- Capture error details
- Don't hide exceptions (re-raise)
- Minimal performance overhead

---

## 💡 DECISION

### What We Chose
Router layer catches exceptions, logs error details, then re-raises original exception.

### Implementation
```python
# interface_cache.py (router)
def execute_operation(operation, **kwargs):
    """Route cache operations with error logging."""
    try:
        # Execute operation
        handler = OPERATIONS.get(operation)
        if not handler:
            raise ValueError(f"Unknown operation: {operation}")
        
        result = handler(**kwargs)
        
        # Log success
        log_debug(f"cache.{operation} succeeded", **kwargs)
        return result
        
    except Exception as e:
        # Log failure with full context
        log_error(
            f"cache.{operation} failed",
            operation=operation,
            params=kwargs,
            error=str(e),
            error_type=type(e).__name__
        )
        # Re-raise original exception
        raise
```

### Rationale
1. **Complete Audit Trail**
   - Every operation attempt logged
   - Success and failure both recorded
   - Full context captured (operation, params)
   - Debugging much easier

2. **Minimal Overhead**
   - Only logs on exception (rare)
   - No performance impact on success path
   - Logging is async, non-blocking

3. **Preserves Exception Handling**
   - Original exception re-raised
   - Caller's try/except still works
   - Stack trace preserved
   - No behavior changes

4. **Early Error Detection**
   - Router is first layer that can catch
   - Errors logged close to source
   - Context still available (params)

---

## 📄 ALTERNATIVES CONSIDERED

### Alternative 1: Core Layer Logging
**Pros:**
- Closer to error source

**Cons:**
- Must duplicate in every core module
- Easy to forget
- Inconsistent logging

**Why Rejected:** Router is single choke point, easier to enforce.

---

### Alternative 2: Gateway Layer Logging
**Pros:**
- Even higher level visibility

**Cons:**
- Less detail available
- Farther from error source
- Can't distinguish which interface failed

**Why Rejected:** Router has better context.

---

### Alternative 3: No Exception Catching
**Pros:**
- Simplest code
- No overhead

**Cons:**
- Lost debugging context
- No operation audit trail
- Hard to troubleshoot

**Why Rejected:** Logging critical for operations.

---

## ⚖️ TRADE-OFFS

### What We Gained
- Complete operation audit trail
- Easy debugging of failures
- Consistent error logging
- Better production monitoring

### What We Accepted
- Slight code complexity in routers
- Try/except in every router
- Log volume increase (failures only)

---

## 📊 IMPACT ANALYSIS

### Technical Impact
- **Debugging:** Much easier with full context
- **Monitoring:** Can track failure rates
- **Performance:** No impact (failures rare)
- **Code:** 5-10 lines per router

### Operational Impact
- **MTTR:** Reduced (easier debugging)
- **Visibility:** Complete operation tracking
- **Alerts:** Can alert on error patterns

---

## 🔮 FUTURE CONSIDERATIONS

### When to Revisit
- If logging overhead becomes measurable
- If exception handling needs to be more sophisticated
- Never triggered in 6+ months

### Evolution Path
- Structured error responses
- Error categorization (retryable vs permanent)
- Automatic retry for transient failures

---

## 🔗 RELATED

### Related Decisions
- DEC-16 - Import Error Protection (complementary)
- DEC-02 - Gateway Centralization (enables this)

### SIMA Entries
- GATE-01 - Three-File Structure (router is middle layer)
- LESS-02 - Measure Don't Guess (logging enables measurement)

---

## 🏷️ KEYWORDS

`router-exceptions`, `error-logging`, `audit-trail`, `debugging`, `error-handling`, `operational-visibility`

---

## 📝 VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 3.0.0 | 2025-10-29 | Migration | SIMAv4 migration |
| 2.0.0 | 2025-10-23 | System | SIMA v3 format |
| 1.0.0 | 2024-06-18 | Original | Decision made |

---

**END OF DECISION**

**Status:** Active - All 12 interface routers implement this  
**Effectiveness:** Complete operation visibility, easier debugging

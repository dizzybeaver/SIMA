# File: DEC-16.md

**REF-ID:** DEC-16  
**Category:** Technical Decision  
**Priority:** High  
**Status:** Active  
**Date Decided:** 2024-06-20  
**Created:** 2024-06-20  
**Last Updated:** 2025-10-30 (SIMAv4 migration)

---

## 📋 SUMMARY

Wrap interface imports in try/except blocks for graceful degradation - Lambda can still operate if non-critical interface fails to import.

**Decision:** Protected imports with availability flags  
**Impact Level:** High  
**Reversibility:** Easy

---

## 🎯 CONTEXT

### Problem Statement
During Home Assistant extension deployment, dependency conflict caused module import to fail, killing entire Lambda. Total outage when partial degradation would suffice.

### Background
- Lambda handles multiple request types (HA control, diagnostics, cache, etc.)
- Extension failure shouldn't break core functionality
- Need graceful degradation, not total failure
- Diagnostic tools should always work

### Requirements
- Core interfaces always available
- Extensions can fail without breaking system
- Clear error messages for failed imports
- Availability tracking for features

---

## 💡 DECISION

### What We Chose
Protect interface imports with try/except blocks. Set availability flags to track which interfaces successfully loaded.

### Implementation
```python
# gateway_core.py
try:
    from interface_homeassistant import router as ha_router
    HAS_HOME_ASSISTANT = True
except ImportError as e:
    log_warning(f"Home Assistant unavailable: {e}")
    HAS_HOME_ASSISTANT = False
    ha_router = None

# In operation handlers
def ha_send_command(command):
    if not HAS_HOME_ASSISTANT:
        raise RuntimeError("Home Assistant interface unavailable")
    return ha_router.execute_operation("send_command", command=command)
```

### Rationale
1. **Graceful Degradation Over Total Failure**
   - Partial functionality better than none
   - Diagnostic tools still work
   - Cache operations unaffected
   - System partially operational

2. **Faster Incident Response**
   - Diagnostics always available
   - Can identify which interface failed
   - No full rollback needed
   - Reduced MTTR

3. **Supports Extension Architecture**
   - Extensions are optional by definition
   - Core never depends on extensions
   - Safer extension development
   - Modular design encouraged

4. **Clear Availability Signaling**
   - Boolean flags make status explicit
   - Can report unavailable features
   - Easy to check before operations
   - Supports feature discovery

---

## 📄 ALTERNATIVES CONSIDERED

### Alternative 1: Fail Fast on Any Import Error
**Pros:**
- Forces fixing issues immediately
- Clear failure signal

**Cons:**
- Total outage for partial problem
- Can't use diagnostics to debug
- Higher MTTR

**Why Rejected:** Partial availability better than total failure.

---

### Alternative 2: Dynamic Import on First Use
**Pros:**
- Delays import until needed
- More precise error timing

**Cons:**
- First request pays import cost
- Error during user request (bad UX)
- Complex mixing with lazy loading

**Why Rejected:** DEC-14 handles lazy loading separately.

---

### Alternative 3: Separate Lambda per Interface
**Pros:**
- Total isolation
- One failure doesn't affect others

**Cons:**
- Much higher complexity
- More deployment overhead
- Difficult to share code/state

**Why Rejected:** Architectural overkill.

---

## ⚖️ TRADE-OFFS

### What We Gained
- Graceful degradation (partial vs total failure)
- Faster incident response
- Safer extension development
- Better error messages

### What We Accepted
- Warning logs for missing interfaces
- Need to check availability flags
- Runtime errors possible if unchecked
- Slightly more complex calling code

---

## 📊 IMPACT ANALYSIS

### Technical Impact
- **Robustness:** Partial failures contained
- **Architecture:** Clear core vs extension distinction
- **Performance:** No impact (flag checks negligible)
- **Testing:** Easier (test with/without extensions)

### Operational Impact
- **MTTR:** Reduced (diagnostics always available)
- **Deployment:** Safer (partial failures contained)
- **Monitoring:** Can track interface availability

### Metrics
- Prevented 3+ total outages
- Reduced MTTR by ~40%
- All interfaces protected

---

## 🔮 FUTURE CONSIDERATIONS

### When to Revisit
- If availability checking becomes burdensome
- If need more sophisticated health checks
- Never triggered issues in 6+ months

### Evolution Path
- Health check endpoint (report available interfaces)
- Automatic retry for failed imports
- Graceful feature degradation UI
- Dependency health monitoring

---

## 🔗 RELATED

### Related Decisions
- **DEC-14:** Lazy Loading (complementary pattern)
- **DEC-15:** Router Exceptions (similar error handling)

### SIMA Entries
- **AP-02:** Uncaught Import Errors (prevents this)
- **LESS-08:** Graceful Degradation (key lesson)
- **BUG-03:** Cascading Failures (related issue)

---

## 🏷️ KEYWORDS

`import-errors`, `graceful-degradation`, `error-handling`, `availability`, `extensions`, `partial-failure`, `resilience`

---

## 📝 VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 3.0.0 | 2025-10-30 | Migration | SIMAv4 migration, under 400 lines |
| 2.0.0 | 2025-10-23 | System | SIMA v3 format |
| 1.0.0 | 2024-06-20 | Original | Decision made |

---

**END OF DECISION**

**Status:** Active - Prevented multiple total outages  
**Effectiveness:** Partial operation beats total failure

# File: DEC-16.md

**REF-ID:** DEC-16  
**Category:** Technical Decision  
**Priority:** High  
**Status:** Active  
**Date Decided:** 2024-06-20  
**Created:** 2024-06-20  
**Last Updated:** 2025-10-29 (SIMAv4 migration)

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

4. **Clear Error Messages**
   - User sees "feature unavailable"
   - Logs show import failure
   - Developer knows what's broken

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

### Alternative 2: Retry Failed Imports
**Pros:**
- Might recover from transient issues

**Cons:**
- Adds complexity
- Delayed error notification
- Import errors rarely transient

**Why Rejected:** Import errors usually permanent within deployment.

---

### Alternative 3: Fallback Implementations
**Pros:**
- Always have something working

**Cons:**
- Maintenance burden
- May mislead about capabilities
- Complex to implement

**Why Rejected:** Clean error better than fake functionality.

---

## ⚖️ TRADE-OFFS

### What We Gained
- Graceful degradation (partial vs total failure)
- Faster incident response (diagnostics available)
- Safer extension development
- Better error messages

### What We Accepted
- Flag checking overhead (negligible)
- More complex import logic
- Need to handle unavailable features
- Runtime errors possible (checked at runtime)

---

## 📊 IMPACT ANALYSIS

### Technical Impact
- **Robustness:** Partial failures contained
- **Architecture:** Clear core vs extension distinction
- **Performance:** No impact (flag checks negligible)
- **Testing:** Easier (can test with/without extensions)

### Operational Impact
- **MTTR:** Reduced (diagnostics always available)
- **Deployment:** Safer (partial failures don't kill system)
- **Monitoring:** Can track interface availability

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
- DEC-14 - Lazy Loading (complementary pattern)
- DEC-15 - Router Exceptions (similar error handling)

### SIMA Entries
- AP-02 - Uncaught Import Errors (prevents this)
- LESS-08 - Graceful Degradation (key lesson)

---

## 🏷️ KEYWORDS

`import-errors`, `graceful-degradation`, `error-handling`, `availability`, `extensions`, `partial-failure`, `resilience`

---

## 📝 VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 3.0.0 | 2025-10-29 | Migration | SIMAv4 migration |
| 2.0.0 | 2025-10-23 | System | SIMA v3 format |
| 1.0.0 | 2024-06-20 | Original | Decision made |

---

**END OF DECISION**

**Status:** Active - Prevented multiple total outages  
**Effectiveness:** Partial operation beats total failure

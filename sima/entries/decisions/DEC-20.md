# File: DEC-20.md

**REF-ID:** DEC-20  
**Category:** Operational Decision  
**Priority:** Critical  
**Status:** Active  
**Date Decided:** 2025-10-20  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-30 (SIMAv4 migration)

---

## 📋 SUMMARY

Replace binary `LEE_FAILSAFE_ENABLED=true` with enumerated `LAMBDA_MODE=failsafe` for extensible operational mode system that supports multiple modes (normal, failsafe, diagnostic, test, performance).

**Decision:** Multi-mode operational configuration  
**Impact Level:** Critical  
**Reversibility:** High (backward compatible during transition)

---

## 🎯 CONTEXT

### Problem Statement
The Lambda initially used binary `LEE_FAILSAFE_ENABLED=true/false` to toggle failsafe mode. As operational needs evolved, we identified need for additional modes: diagnostic mode for troubleshooting, test mode for deployment verification, performance mode for benchmarking. A binary flag couldn't support multiple modes.

### Background
- Single binary flag limited operational flexibility
- Need for diagnostic, test, and performance modes
- Consistency with industry-standard enum patterns
- Future operational requirements unknown

### Requirements
- Support multiple operational modes
- Extensible without breaking changes
- Self-documenting configuration
- Consistent with AWS patterns

---

## 💡 DECISION

### What We Chose
Replace `LEE_FAILSAFE_ENABLED` with `LAMBDA_MODE` enumerated environment variable supporting multiple operational modes with clear, self-documenting values.

### Implementation
```bash
# Old (deprecated)
LEE_FAILSAFE_ENABLED=true

# New (current)
LAMBDA_MODE=failsafe

# Supported modes:
# - normal (default): Full LEE/SUGA operation
# - failsafe: Emergency bypass mode
# - diagnostic (future): Enhanced troubleshooting
# - test (future): Deployment verification
# - performance (future): Benchmarking mode
```

**Code Implementation:**
```python
# Configuration loading
LAMBDA_MODE = os.environ.get('LAMBDA_MODE', 'normal').lower()
VALID_MODES = {'normal', 'failsafe', 'diagnostic', 'test', 'performance'}

if LAMBDA_MODE not in VALID_MODES:
    raise ValueError(f"Invalid LAMBDA_MODE: {LAMBDA_MODE}")

# Mode-specific behavior
if LAMBDA_MODE == 'failsafe':
    # Bypass LEE, direct execution
    result = direct_home_assistant_call(operation, params)
elif LAMBDA_MODE == 'normal':
    # Full SUGA/LEE execution
    result = gateway.execute_operation(operation, **params)
elif LAMBDA_MODE == 'diagnostic':
    # Future: verbose logging, health checks
    pass
```

### Rationale
1. **Extensibility**
   - Binary (on/off) → Enumerated (normal, failsafe, diagnostic, test, ...)
   - Easy to add new modes without breaking existing deployments
   - Future-proof operational model
   - Supports evolving operational requirements

2. **Clearer Intent**
   - `LEE_FAILSAFE_ENABLED=true` → Ambiguous (what's enabled?)
   - `LAMBDA_MODE=failsafe` → Crystal clear operational mode
   - Self-documenting configuration
   - Reduces operational errors

3. **Consistent Pattern**
   - Matches `LOG_LEVEL=INFO`, `ENVIRONMENT=production` patterns
   - Standard configuration style across AWS
   - Familiar to operations teams
   - Industry best practice

4. **Low Migration Cost**
   - Simple environment variable change
   - Can support both during transition
   - No code complexity added
   - One-time deployment update

---

## 🔄 ALTERNATIVES CONSIDERED

### Alternative 1: Keep Binary Flag
**Approach:** Maintain `LEE_FAILSAFE_ENABLED` and add separate flags for other modes.

**Pros:**
- No migration needed
- Backward compatible

**Cons:**
- Multiple independent flags confusing
- No clear operational mode
- Doesn't scale (10 modes = 10 flags)

**Why Not Chosen:** Doesn't solve extensibility problem, creates more confusion.

### Alternative 2: JSON Configuration
**Approach:** Use JSON object for mode configuration: `MODE_CONFIG='{"mode":"failsafe","verbose":true}'`

**Pros:**
- Very flexible
- Can include mode-specific settings

**Cons:**
- Overly complex for simple mode selection
- Error-prone (JSON parsing errors)
- Harder to read/write

**Why Not Chosen:** Over-engineered for simple enum selection.

### Alternative 3: Multiple Environment Variables
**Approach:** Use pattern like `MODE_FAILSAFE=true`, `MODE_DIAGNOSTIC=true`

**Pros:**
- Boolean simplicity
- Independent toggles

**Cons:**
- Can enable multiple conflicting modes
- No clear single operational mode
- Confusion about precedence

**Why Not Chosen:** Doesn't provide single source of truth for operational mode.

---

## ⚖️ TRADE-OFFS

### Benefits
- **Extensibility:** Easy to add new operational modes
- **Clarity:** Self-documenting operational state
- **Consistency:** Matches industry patterns
- **Future-proof:** Supports unknown future requirements
- **Low complexity:** Simple string comparison

### Costs
- **Migration:** One-time environment variable update
- **Documentation:** Update deployment guides
- **Backward compatibility:** Maintain both during transition (optional)

### Net Assessment
The one-time migration cost is trivial compared to long-term extensibility and clarity benefits. This decision enables future operational capabilities without technical debt.

---

## 📊 IMPACT ANALYSIS

### On Architecture
- **Impact Level:** Low
- **Description:** Configuration-only change, no architectural modifications
- **Affected Components:** Configuration loading, mode detection
- **Migration Required:** Update environment variables

### On Development
- **Impact Level:** Low
- **Description:** Simple string comparison vs boolean check
- **Code Changes:** Check `LAMBDA_MODE` instead of `LEE_FAILSAFE_ENABLED`
- **Testing:** Verify mode detection works correctly

### On Performance
- **Impact Level:** None
- **Description:** Environment variable lookup identical
- **Metrics:** String comparison vs boolean (negligible difference)

### On Maintenance
- **Impact Level:** Low (positive)
- **Description:** Easier to understand and extend
- **Documentation:** Update deployment docs once
- **Backward Compatibility:** Can maintain both during transition

---

## 🔮 FUTURE CONSIDERATIONS

### When to Revisit
- If mode combinations needed (consider sub-modes)
- If modes become too numerous (group into categories)
- If operational needs change significantly

### Potential Evolution
**Diagnostic Mode:** `LAMBDA_MODE=diagnostic`
- Verbose logging, health checks
- Introspection endpoints
- Performance profiling enabled

**Test Mode:** `LAMBDA_MODE=test`
- Synthetic workloads
- Mock external dependencies
- Deployment verification

**Performance Mode:** `LAMBDA_MODE=performance`
- Detailed metrics collection
- Benchmarking enabled
- Operation timing breakdowns

**Sub-modes:** `LAMBDA_MODE=normal:verbose` or `LAMBDA_MODE=failsafe:debug`

### Monitoring
- Track mode usage distribution
- Monitor mode transitions
- Alert on unexpected modes (typos)
- Measure operational effectiveness per mode

---

## 🔗 RELATED

### Related Decisions
- **DEC-21**: SSM Token-Only - Companion operational decision
- **DEC-22**: DEBUG_MODE - Related diagnostic configuration
- **DEC-03**: Dispatch Dictionary - Mode determines dispatch pathway

### Related Lessons
- **LESS-17**: Operational lessons - Mode design experiences
- **LESS-10**: Configuration simplification

### Related Architecture
- **ARCH-01**: SUGA Pattern - Mode affects execution pathway
- **INT-02**: Config Interface - Mode is configuration value

---

## 🏷️ KEYWORDS

operational-modes, configuration, environment-variables, failsafe, extensibility, deployment, operations, lambda-config, mode-selection

---

## 📚 VERSION HISTORY

- **2025-10-30**: Migrated to SIMAv4 format
- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2025-10-20**: Original decision documented

---

**File:** `DEC-20.md`  
**Path:** `/sima/entries/decisions/operational/DEC-20.md`  
**End of Document**

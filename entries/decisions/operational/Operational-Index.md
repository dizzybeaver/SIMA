# Operational-Index.md

**Version:** 1.0.0  
**Date:** 2025-11-01  
**Purpose:** Local index for operational decision entries

**Category:** Decision Logic  
**Subcategory:** Operational  
**Files:** 4 (DEC-20 through DEC-23)  
**Last Updated:** 2025-11-01

---

## Overview

Operational decisions - runtime configuration, deployment modes, debugging capabilities, and performance profiling. These decisions enable flexible deployment strategies and operational excellence.

---

## Files in This Category

### DEC-20: Multi-Mode Operational Configuration

**File:** `DEC-20.md`  
**REF-ID:** DEC-20  
**Priority:** Critical  
**Status:** Active

**Summary:** Replace binary `LEE_FAILSAFE_ENABLED=true` with enumerated `LAMBDA_MODE=failsafe` for extensible operational mode system supporting multiple modes (normal, failsafe, diagnostic, test, performance).

**Use When:** 
- Configuring Lambda deployment
- Selecting operational mode
- Understanding deployment flexibility
- Planning for future operational modes

**Impact:** Enables extensible operational modes without breaking changes

---

### DEC-21: SSM Token-Only Configuration

**File:** `DEC-21.md`  
**REF-ID:** DEC-21  
**Priority:** Critical  
**Status:** Active

**Summary:** SSM Parameter Store stores ONLY Home Assistant token. All other configuration moved to Lambda environment variables, resulting in 92% reduction in cold start overhead (3,000ms savings, from 3,250ms to 250ms).

**Use When:**
- Understanding configuration strategy
- Optimizing cold start performance
- Balancing security and speed
- Deployment planning

**Impact:** 92% cold start improvement (3,250ms → 250ms), right-sized security

---

### DEC-22: DEBUG_MODE Flow Visibility

**File:** `DEC-22.md`  
**REF-ID:** DEC-22  
**Priority:** High  
**Status:** Active

**Summary:** Environment variable `DEBUG_MODE=true` enables operation flow visibility (which operations called, with what parameters, in what order) without code changes or redeployment.

**Use When:**
- Troubleshooting production issues
- Understanding request execution flow
- Development debugging
- Investigating complex interactions

**Impact:** Instant troubleshooting capability without redeployment

---

### DEC-23: DEBUG_TIMINGS Performance Tracking

**File:** `DEC-23.md`  
**REF-ID:** DEC-23  
**Priority:** High  
**Status:** Active

**Summary:** Environment variable `DEBUG_TIMINGS=true` enables performance measurement for all operations, providing data-driven insights for optimization without code changes. Implements "measure don't guess" principle (LESS-02).

**Use When:**
- Performance optimization work
- Identifying bottlenecks
- Validating optimization effectiveness
- Data-driven performance decisions

**Impact:** Data-driven optimization capability, instant performance visibility

---

## Quick Decision Guide

### Scenario 1: Deployment Configuration

**Decision Path:**
1. Check **DEC-20**: LAMBDA_MODE selection
   - Development → `LAMBDA_MODE=normal`
   - Emergency bypass → `LAMBDA_MODE=failsafe`
   - Future: diagnostic, test, performance modes

2. Apply **DEC-21**: Configuration management
   - Token → SSM Parameter Store (encrypted)
   - Other config → Environment variables
   - Result: 92% faster cold start

### Scenario 2: Troubleshooting Production

**Analysis Framework:**
```
1. Enable DEBUG_MODE (DEC-22):
   - See operation execution flow
   - Identify unexpected operations
   - Understand request path
   - No redeployment needed

2. Enable DEBUG_TIMINGS (DEC-23):
   - Measure operation performance
   - Identify bottlenecks
   - Quantify impact
   - Validate fixes

3. Investigate, fix, disable debug modes
```

### Scenario 3: Performance Optimization

**Workflow:**
```
1. Apply LESS-02: Measure don't guess

2. Enable DEBUG_TIMINGS (DEC-23):
   - Get baseline measurements
   - Identify hot operations
   - Measure actual costs

3. Make optimization changes

4. Re-measure with DEBUG_TIMINGS:
   - Compare before/after
   - Quantify improvement
   - Validate effectiveness

5. Disable DEBUG_TIMINGS when done
```

---

## Operational Principles

### From DEC-20: Multi-Mode Configuration

**Extensibility:**
- Enumerated modes (not binary flags)
- Easy to add new modes
- Self-documenting configuration
- Future-proof operational model

**Clear Intent:**
- `LAMBDA_MODE=failsafe` vs `LEE_FAILSAFE_ENABLED=true`
- Explicit operational state
- Reduces configuration errors

### From DEC-21: SSM Token-Only

**Performance First:**
- 92% cold start improvement
- Only encrypt what needs encryption
- Right-sized security approach
- User experience priority

**Configuration Split:**
- Sensitive data → SSM (token)
- Non-sensitive → Environment variables
- Simple, fast, secure

### From DEC-22: DEBUG_MODE

**Production Debugging:**
- Toggle without redeployment
- Instant visibility
- Production-safe (minimal overhead)
- Temporary enablement

**Flow Visibility:**
- See operation calls
- Understand execution path
- Debug complex flows
- Fast troubleshooting

### From DEC-23: DEBUG_TIMINGS

**Data-Driven Optimization:**
- Measure, don't guess (LESS-02)
- Quantify bottlenecks
- Validate improvements
- Real-world performance data

**Environment Control:**
- Toggle without redeployment
- Temporary measurement
- Production-safe overhead
- Fast optimization cycle

---

## Related Categories

**Within Decision Logic:**
- **Architecture** (DEC-01 to DEC-05: Foundation patterns)
- **Technical** (DEC-12 to DEC-19: Implementation decisions)
- **Optimization** (DT-07, FW-01, FW-02: Performance frameworks)

**Other Categories:**
- **NM01-Architecture** (ARCH-01: SUGA implements these modes)
- **NM02-Dependencies** (Config loading, SSM integration)
- **NM03-Operations** (Operation execution, timing, debugging)
- **NM06-Lessons** (LESS-02: Measure don't guess, LESS-17: Operational lessons)

---

## Key Relationships

**DEC-20 (LAMBDA_MODE) enables:**
- Multiple operational strategies
- Future mode expansion (diagnostic, test, performance)
- Clear operational intent
- Extensible deployment model

**DEC-21 (SSM Token-Only) provides:**
- 3,000ms cold start savings (92% improvement)
- Right-sized security (token encrypted, config plain)
- Operational simplicity (fewer SSM parameters)

**DEC-22 (DEBUG_MODE) enables:**
- Production troubleshooting without redeploy
- Operation flow visibility
- Fast issue diagnosis
- Development debugging

**DEC-23 (DEBUG_TIMINGS) enables:**
- Data-driven optimization (LESS-02)
- Bottleneck identification
- Performance validation
- Quantified improvements

**These work together:**
- Set LAMBDA_MODE (deployment strategy)
- Benefit from SSM optimization (fast cold start)
- Debug with DEBUG_MODE (flow visibility)
- Optimize with DEBUG_TIMINGS (performance data)

---

## Common Questions

**Q: Which LAMBDA_MODE should I use?**
**A:** See **DEC-20** - Most use `normal` (default). Use `failsafe` for emergency bypass. Future modes: diagnostic, test, performance.

**Q: Why is cold start so fast now?**
**A:** See **DEC-21** - SSM stores only token (not 13 parameters). 92% improvement: 3,250ms → 250ms. Token cached 5min.

**Q: How do I debug production issues without redeploying?**
**A:** See **DEC-22** - Set `DEBUG_MODE=true` via environment variable. See operation flow instantly. Disable when done.

**Q: How do I identify performance bottlenecks?**
**A:** See **DEC-23** - Set `DEBUG_TIMINGS=true` via environment variable. See operation timings. Apply LESS-02: Measure don't guess.

**Q: Can I use DEBUG_MODE and DEBUG_TIMINGS together?**
**A:** Yes! `DEBUG_MODE=true` shows what operations run. `DEBUG_TIMINGS=true` shows how long they take. Powerful combination for troubleshooting.

---

## Best Practices

### Configuration Management

```bash
# Production deployment
LAMBDA_MODE=normal
HOME_ASSISTANT_URL=https://ha.local:8123
HOME_ASSISTANT_TIMEOUT=30
LOG_LEVEL=CRITICAL
USE_PARAMETER_STORE=true

# SSM Parameter Store (encrypted)
/lambda-execution-engine/home_assistant/token = "eyJhbGc..."
```

### Debugging Workflow

```bash
# Enable debugging (instant, no redeploy)
DEBUG_MODE=true
DEBUG_TIMINGS=true

# Run tests, check CloudWatch logs

# Analyze issues, make changes

# Disable when done (save log costs)
DEBUG_MODE=false
DEBUG_TIMINGS=false
```

### Performance Optimization Cycle

```bash
# 1. Baseline measurement
DEBUG_TIMINGS=true
# Run workload, record timings

# 2. Make optimization changes
# (code changes, architecture changes)

# 3. Re-measure
DEBUG_TIMINGS=true
# Run same workload, compare timings

# 4. Quantify improvement
# Before: 125ms, After: 50ms = 60% faster

# 5. Disable timing
DEBUG_TIMINGS=false
```

---

## Keywords

operational, configuration, deployment, debugging, performance, cold-start, ssm, lambda-mode, environment-variables, troubleshooting, optimization, data-driven, flow-visibility, timing

---

## Navigation

**Parent:** Decisions-Master-Index.md  
**Siblings:** Architecture-Index.md, Technical-Index.md, Import-Index.md, FeatureAddition-Index.md, ErrorHandling-Index.md, Optimization-Index.md, Testing-Index.md, Refactoring-Index.md, Deployment-Index.md, Meta-Index.md

**Related Decisions:** 
- DEC-20 (LAMBDA_MODE)
- DEC-21 (SSM Token-Only)
- DEC-22 (DEBUG_MODE)
- DEC-23 (DEBUG_TIMINGS)

**Location:** `/sima/entries/decisions/operational/`

---

**Total Entries:** 4  
**Priority Breakdown:** Critical (2), High (2)  
**All Active Status**

**End of Index**

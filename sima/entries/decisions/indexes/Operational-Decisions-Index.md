# File: Operational-Decisions-Index.md

**REF-ID:** INDEX-DEC-OPS  
**Category:** Index  
**Topic:** Operational Decisions  
**Total Decisions:** 4 (DEC-20 to DEC-23)  
**Created:** 2025-10-30  
**Last Updated:** 2025-10-30

---

## 📋 OVERVIEW

This index covers 4 operational decisions that define runtime configuration, debugging capabilities, and performance measurement strategies. These decisions focus on operations excellence: how the system is configured, monitored, and optimized in production.

**All decisions are recent (Oct 2025) and represent major operational improvements.**

**Priority Distribution:**
- 🔴 Critical: 2 (DEC-20, DEC-21)
- 🟡 High: 2 (DEC-22, DEC-23)

---

## 🎯 QUICK REFERENCE

| REF-ID | Decision | Priority | Impact | Date |
|--------|----------|----------|--------|------|
| DEC-20 | LAMBDA_MODE | 🔴 Critical | Extensible modes | 2025-10-20 |
| DEC-21 | SSM Token-Only | 🔴 Critical | 92% cold start improvement | 2025-10-20 |
| DEC-22 | DEBUG_MODE | 🟡 High | Operation visibility | 2025-10-20 |
| DEC-23 | DEBUG_TIMINGS | 🟡 High | Performance profiling | 2025-10-20 |

---

## 📚 INDIVIDUAL DECISIONS

### DEC-20: LAMBDA_MODE Environment Variable
**File:** `DEC-20.md`  
**Summary:** Replace binary LEE_FAILSAFE_ENABLED with enumerated LAMBDA_MODE for extensible operational modes (normal, failsafe, diagnostic, test, performance).

**Why Critical:**
- Extensible operational model
- Clear operational intent
- Future-proof design
- Consistent with AWS patterns

**Key Benefits:**
- Easy to add new modes (diagnostic, test, performance)
- Self-documenting configuration
- No breaking changes for new modes
- Industry-standard pattern

**Modes Supported:**
- `normal` (default): Full LEE/SUGA operation
- `failsafe`: Emergency bypass mode
- `diagnostic` (future): Enhanced troubleshooting
- `test` (future): Deployment verification
- `performance` (future): Benchmarking

**Related:**
- DEC-21: SSM token-only (companion operational decision)
- DEC-22: DEBUG_MODE (related diagnostic config)
- INT-02: Config Interface

**Path:** `/sima/entries/decisions/operational/DEC-20.md`

---

### DEC-21: SSM Token-Only Configuration
**File:** `DEC-21.md`  
**Summary:** Store ONLY Home Assistant token in SSM Parameter Store. Move all other configuration to environment variables, resulting in 92% reduction in cold start overhead (3,000ms savings).

**Why Critical:**
- 92% cold start improvement (-3,000ms)
- Massive performance gain
- Right-sized security (only token encrypted)
- Simplified configuration management

**Key Benefits:**
- **Performance:** 3,250ms → 250ms first call, 0ms cached (5min TTL)
- **Security:** Token still encrypted in SSM
- **Simplicity:** 1 SSM parameter vs 13
- **Cost:** Fewer SSM API calls

**Before/After:**
```
Before: 13 SSM parameters × 250ms = 3,250ms
After: 1 SSM parameter × 250ms = 250ms (first), 0ms (cached)
Improvement: 92% faster, -3,000ms saved
```

**Related:**
- DEC-20: LAMBDA_MODE (companion decision)
- DEC-13: Fast Path Caching (performance pattern)
- BUG-04: Config mismatch (this solves the issue)
- LESS-17: SSM simplification lessons

**Path:** `/sima/entries/decisions/operational/DEC-21.md`

---

### DEC-22: DEBUG_MODE Flow Visibility
**File:** `DEC-22.md`  
**Summary:** Environment variable DEBUG_MODE=true enables operation flow visibility (which operations called, with what parameters) without code changes or redeployment.

**Why High Priority:**
- Instant debugging capability
- Zero deployment overhead
- Production-safe operation
- Enables rapid troubleshooting

**Key Benefits:**
- See exact operation flow
- Toggle via environment variable (seconds)
- Production-safe (~5μs overhead when on)
- No code changes needed

**Output Example:**
```
[DEBUG] Executing: cache.get, kwargs_keys=['key']
[INFO] Cache get: user_123
[DEBUG] Completed: cache.get, result_type=dict
```

**Related:**
- DEC-23: DEBUG_TIMINGS (companion feature)
- DEC-15: Router exceptions (error logging)
- INT-06: Logging Interface
- LESS-18: Debug system lessons

**Path:** `/sima/entries/decisions/operational/DEC-22.md`

---

### DEC-23: DEBUG_TIMINGS Performance Tracking
**File:** `DEC-23.md`  
**Summary:** Environment variable DEBUG_TIMINGS=true enables performance measurement for all operations, providing data-driven insights for optimization.

**Why High Priority:**
- Data-driven optimization (LESS-02)
- Zero deployment overhead
- Quantified improvements
- Identifies bottlenecks

**Key Benefits:**
- Measure actual performance in real conditions
- Toggle via environment variable
- Validates optimization effectiveness
- ~10μs overhead (negligible)

**Output Example:**
```
[TIMING] cache.get: 0.85ms
[TIMING] http.post: 125.43ms
[TIMING] ssm.get_parameter: 248.67ms
```

**Related:**
- DEC-22: DEBUG_MODE (companion feature)
- DEC-21: SSM optimization (validated with timings)
- LESS-02: Measure don't guess (core principle)
- DEC-13, DEC-14: Optimizations measured with this

**Path:** `/sima/entries/decisions/operational/DEC-23.md`

---

## 🔗 CROSS-REFERENCES

### To Architecture Decisions
- **DEC-01** (SUGA): Operational modes affect execution pathway
- **DEC-03** (Dispatch): DEBUG_MODE shows dispatch behavior

### To Technical Decisions
- **DEC-13** (Fast Path): Validated with DEBUG_TIMINGS
- **DEC-14** (Lazy Loading): Cold start measured with DEBUG_TIMINGS
- **DEC-15** (Router Exceptions): Used by DEBUG_MODE logging

### To Interface Patterns
- **INT-02** (Config): Implements LAMBDA_MODE, SSM token access
- **INT-06** (Logging): Used by DEBUG_MODE and DEBUG_TIMINGS

### To Lessons Learned
- **LESS-02** (Measure): Core principle behind DEBUG_TIMINGS
- **LESS-17** (SSM): Experiences leading to DEC-21
- **LESS-18** (Debug System): Lessons from DEBUG_MODE/TIMINGS

### To Bugs
- **BUG-04** (Config Mismatch): Solved by DEC-21 (SSM token-only)

---

## 📊 DECISION IMPACT ANALYSIS

### By Performance Impact

**Massive Impact (>1000ms):**
1. **DEC-21** (SSM): -3,000ms cold start (92% improvement)

**Measurement Enablers:**
- DEC-22 (DEBUG_MODE): Operation flow visibility
- DEC-23 (DEBUG_TIMINGS): Performance measurement

**Configuration:**
- DEC-20 (LAMBDA_MODE): Operational flexibility

### By Operational Excellence

**Critical for Operations:**
1. **DEC-21** (SSM): Single most impactful performance decision
2. **DEC-20** (LAMBDA_MODE): Future-proof operational model

**Essential Debugging Tools:**
1. **DEC-22** (DEBUG_MODE): What's happening?
2. **DEC-23** (DEBUG_TIMINGS): How fast is it?

### By Timeline (All Oct 2025)

**Phase 4 - Operational Excellence:**
- All 4 decisions made in same week (Oct 20, 2025)
- Coordinated operational improvements
- Debug system + major performance gain

---

## 🚀 USAGE GUIDANCE

### Configuration Management
1. **Set operational mode:** Use DEC-20 (LAMBDA_MODE)
2. **Minimize cold start:** Follow DEC-21 (only token in SSM)
3. **Keep config simple:** Environment variables for non-sensitive

### Debugging Production Issues
1. **Enable flow visibility:** Set DEBUG_MODE=true (DEC-22)
2. **Check CloudWatch logs:** See operation execution
3. **Disable when done:** Set DEBUG_MODE=false

### Performance Optimization
1. **Enable timing:** Set DEBUG_TIMINGS=true (DEC-23)
2. **Run workload:** Generate timing data
3. **Analyze bottlenecks:** Find slow operations
4. **Make changes:** Optimize based on data
5. **Measure again:** Validate improvements
6. **Disable when done:** Set DEBUG_TIMINGS=false

### Emergency Scenarios
1. **System issues:** Switch to LAMBDA_MODE=failsafe (DEC-20)
2. **Debug urgently:** Enable DEBUG_MODE (DEC-22)
3. **Investigate performance:** Enable DEBUG_TIMINGS (DEC-23)
4. **All toggleable via environment variables (no redeployment)**

---

## 🎯 KEY RELATIONSHIPS

### DEC-20 (LAMBDA_MODE) Enables:
- Extensible operational modes
- Clear operational intent
- Future capabilities (diagnostic, test, performance)
- No breaking changes for new modes

### DEC-21 (SSM Token-Only) Provides:
- 3,000ms cold start savings (92% reduction)
- Simplified configuration (1 param vs 13)
- Same security (token still encrypted)
- Right-sized encryption

### DEC-22 + DEC-23 (Debug System) Enables:
- **Flow visibility:** What operations ran? (DEC-22)
- **Performance visibility:** How fast? (DEC-23)
- **Data-driven optimization:** Measure, don't guess
- **Production diagnosis:** No redeployment needed

---

## 📈 OPERATIONAL METRICS

### Cold Start Performance (DEC-21)
```
Before:  Imports (200ms) + Init (50ms) + SSM (3,250ms) = 3,500ms
After:   Imports (200ms) + Init (50ms) + SSM (250ms) = 500ms
Savings: 3,000ms (86% total cold start improvement)
```

### Debug Overhead
```
DEBUG_MODE=false:    0ns (no overhead)
DEBUG_MODE=true:     ~5μs per operation
DEBUG_TIMINGS=false: 0ns (no overhead)
DEBUG_TIMINGS=true:  ~10μs per operation
```

### Configuration Changes
```
Before (13 SSM params):
- 13 parameters to create
- 13 parameters to update
- 3,250ms to fetch all

After (1 SSM param):
- 1 parameter to create
- 1 parameter to update
- 250ms to fetch (or 0ms if cached)
```

---

## 🏷️ KEYWORDS

operational, configuration, debugging, performance, cold-start, ssm, environment-variables, lambda-mode, debug-mode, timings, optimization, production, troubleshooting

---

## 📚 VERSION HISTORY

- **2025-10-30**: Created index for SIMAv4
- **2025-10-20**: All 4 decisions made and documented

---

**File:** `Operational-Decisions-Index.md`  
**Path:** `/sima/entries/decisions/indexes/Operational-Decisions-Index.md`  
**End of Index**

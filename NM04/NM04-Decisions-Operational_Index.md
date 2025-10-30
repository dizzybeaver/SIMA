# NM04-Decisions-Operational_Index.md

# Decisions - Operational Index

**Category:** NM04 - Decisions
**Topic:** Operational
**Items:** 4
**Last Updated:** 2025-10-23

---

## Topic Overview

**Description:** Operational design decisions made on 2025-10-20 that affect how the Lambda Execution Engine runs in production. These decisions focus on configuration strategies, debugging capabilities, and runtime behavior. All 4 decisions are recent (Oct 2025) and represent major improvements in operational efficiency.

**Keywords:** operations, runtime, configuration, debugging, SSM, DEBUG_MODE, LAMBDA_MODE, performance tracking, cold start

---

## Individual Files

### DEC-20: LAMBDA_MODE Over LEE_FAILSAFE_ENABLED
- **File:** `NM04-Decisions-Operational_DEC-20.md`
- **Summary:** Replace binary failsafe flag with extensible LAMBDA_MODE enum for operational flexibility
- **Priority:** 🔴 Critical
- **Impact:** Breaking change, clearer intent, future-proof extensibility
- **Key Benefit:** Can add diagnostic, test, performance modes without breaking changes

### DEC-21: SSM Token-Only Configuration
- **File:** `NM04-Decisions-Operational_DEC-21.md`
- **Summary:** SSM Parameter Store stores ONLY Home Assistant token; all other config in environment variables
- **Priority:** 🔴 Critical
- **Impact:** 92% cold start reduction (3,000ms saved), simplified architecture
- **Key Benefit:** From 13 SSM parameters (3,250ms) to 1 parameter (250ms)

### DEC-22: DEBUG_MODE Flow Visibility
- **File:** `NM04-Decisions-Operational_DEC-22.md`
- **Summary:** Environment variable DEBUG_MODE=true enables operation flow visibility for troubleshooting
- **Priority:** 🟡 High
- **Impact:** Instant debugging capability, no code changes needed
- **Key Benefit:** See exactly what operations called, when, and with what results

### DEC-23: DEBUG_TIMINGS Performance Tracking
- **File:** `NM04-Decisions-Operational_DEC-23.md`
- **Summary:** Environment variable DEBUG_TIMINGS=true enables performance measurement for optimization
- **Priority:** 🟡 High
- **Impact:** Data-driven optimization, bottleneck identification
- **Key Benefit:** Measure every operation, identify slow paths, validate optimizations

---

## Related Topics

**Within NM04 (Decisions):**
- Architecture Decisions (foundation these build upon)
- Technical Decisions (implementations these affect)

**Other Categories:**
- NM03-Operations (PATH-01 cold start dramatically improved by DEC-21)
- NM06-Lessons (LESS-17 SSM simplification, LESS-18 debug system)
- NM06-Bugs (BUG-04 config mismatch led to DEC-21)

---

## Key Relationships

**DEC-20 (LAMBDA_MODE) enables:**
- Extensible operational modes
- Clear intent (normal, failsafe, diagnostic)
- Future modes without breaking changes

**DEC-21 (SSM token-only) provides:**
- 3,000ms cold start savings (92% reduction)
- Simplified configuration (1 parameter vs 13)
- Same security (token still encrypted)

**DEC-22 + DEC-23 (Debug system) enables:**
- Flow visibility (what happened?)
- Performance visibility (how fast?)
- Production troubleshooting (no deploy needed)
- Data-driven optimization

**Combined Impact:**
- Cold start: -3,000ms (92% improvement)
- Configuration: Simplified from 13 SSM to 1 SSM
- Debugging: Instant enable/disable
- Operations: Major improvement in all dimensions

---

## Timeline

**All 4 decisions made:** 2025-10-20

This represents a major operational improvement sprint addressing:
- Cold start performance (DEC-21)
- Operational flexibility (DEC-20)
- Debugging capability (DEC-22, DEC-23)

---

## Navigation

- **Up:** NM04-Decisions_Index.md (Category Index)
- **Sibling Topics:**
  - Architecture Decisions Index
  - Technical Decisions Index

---

**File:** `NM04-Decisions-Operational_Index.md`
**End of Index**

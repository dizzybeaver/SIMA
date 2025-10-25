# NM04-INDEX-Design Decisions.md

# Design Decisions - Category Index

**Category:** NM04 - Decisions  
**Topics:** 4  
**Individual Files:** 24+  
**Last Updated:** 2025-10-23 (Phase 5 terminology corrections)

---

## Category Overview

**Purpose:** Documents all design decisions made during development of SUGA-ISP Lambda Execution Engine, with rationale and alternatives considered.

**Scope:** Architecture, technical implementation, operational, and security decisions.

---

## ⚠️ CRITICAL TERMINOLOGY

**SUGA = Single Universal Gateway Architecture** (gateway pattern in Lambda code)  
**SIMA = Synthetic Integrate Memory Architecture** (neural maps system)

Always use "SUGA pattern" when referring to gateway architecture.  
See SESSION-START-Quick-Context.md for complete glossary.

---

## Topics in This Category

### Architecture Decisions
- **Description:** Foundational design choices (SUGA pattern, file structure, etc.)
- **Items:** DEC-01, DEC-02, DEC-06, DEC-08, DEC-24
- **File:** `NM04-ARCHITECTURE-Foundational Design Decisions.md`
- **Priority Items:**
  - DEC-01: SUGA Pattern Implementation (CRITICAL)
  - DEC-02: Gateway Centralization (CRITICAL)
  - DEC-08: Flat File Structure (HIGH)

### Technical Decisions
- **Description:** Implementation details (threading, dependencies, memory, etc.)
- **Items:** DEC-03, DEC-04, DEC-05, DEC-07, DEC-11, DEC-12, DEC-13
- **File:** `NM04-TECHNICAL-Implementation Design Decisions.md`
- **Priority Items:**
  - DEC-04: No Threading Locks (CRITICAL)
  - DEC-05: Sentinel Sanitization (HIGH)
  - DEC-07: Dependencies < 128MB (HIGH)

### Operational Decisions
- **Description:** Runtime, deployment, monitoring choices
- **Items:** DEC-14, DEC-15, DEC-16, DEC-17, DEC-18, DEC-21, DEC-22, DEC-23
- **File:** `NM04-OPERATIONAL-Runtime Design Decisions.md`
- **Priority Items:**
  - DEC-21: SSM Token-Only (HIGH)
  - DEC-16: CloudWatch Logging (MEDIUM)

### Security Decisions
- **Description:** Security, validation, access control choices
- **Items:** DEC-19, DEC-20
- **File:** `NM04-SECURITY-Design Decisions.md`
- **Priority Items:**
  - DEC-19: Input Validation (CRITICAL)
  - DEC-20: Token Security (HIGH)

---

## Quick Reference

**Most Frequently Accessed:**
1. **DEC-01**: SUGA Pattern Implementation
2. **DEC-04**: No Threading Locks
3. **DEC-05**: Sentinel Sanitization
4. **DEC-08**: Flat File Structure
5. **DEC-21**: SSM Token-Only

---

## Keyword Routing

**Common queries → Files:**
```
"SUGA pattern" → ARCHITECTURE (DEC-01, DEC-02)
"threading" → TECHNICAL (DEC-04)
"sentinel" → TECHNICAL (DEC-05)
"dependencies" → TECHNICAL (DEC-07)
"subdirectories" → ARCHITECTURE (DEC-06, DEC-08)
"SSM" → OPERATIONAL (DEC-21)
"token" → SECURITY (DEC-20) or OPERATIONAL (DEC-21)
"validation" → SECURITY (DEC-19)
```

---

## Cross-Category Relationships

**Decisions → Architecture:**
- DEC-01 (SUGA) → ARCH-01 (Gateway Trinity)
- DEC-04 (Threading) → ARCH-07 (LMMS)

**Decisions → Rules:**
- DEC-01 (SUGA) → RULE-01 (Gateway imports)
- DEC-02 (Centralization) → RULE-01

**Decisions → Anti-Patterns:**
- DEC-01 → AP-01 (Direct imports)
- DEC-04 → AP-08 (Threading locks)
- DEC-05 → AP-19 (Sentinel leaks)

**Decisions → Lessons:**
- DEC-05 → BUG-01 (Sentinel leak bug)
- DEC-08 → LESS-07 (Simplicity scales)

---

## Decision Categories Summary

| Category | Count | Priority | Most Referenced |
|----------|-------|----------|----------------|
| Architecture | 5 | 🔴 CRITICAL | DEC-01, DEC-02 |
| Technical | 7 | 🔴 CRITICAL | DEC-04, DEC-05 |
| Operational | 8 | 🟡 HIGH | DEC-21 |
| Security | 2 | 🔴 CRITICAL | DEC-19, DEC-20 |

---

## Navigation

- **Up:** Master Index (NM00A-Master_Index.md)
- **Quick Lookup:** Quick Index (NM00-Quick_Index.md)
- **Related Categories:**
  - NM01 (Architecture - patterns explained)
  - NM02 (Dependencies - import rules)
  - NM05 (Anti-Patterns - what NOT to do)
  - NM06 (Lessons - why decisions matter)

---

## Keywords

decisions, design, rationale, SUGA, architecture, technical, operational, security, choices, alternatives

---

## Version History

- **2025-10-23**: Phase 5 terminology corrections (SIMA → SUGA)
- **2025-10-20**: Updated for SIMA v3 neural maps structure
- **2025-10-15**: Created category index

---

**End of Index**

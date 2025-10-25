# NM01-INDEX-Architecture.md

# Architecture - Category Index

**Category:** NM01 - Architecture  
**Topics:** 3  
**Individual Files:** ~12  
**Last Updated:** 2025-10-23 (terminology corrections - Phase 5)

---

## Category Overview

**Purpose:** Documents core SUGA architecture patterns, 12 interfaces, and memory management systems (LMMS).

**Scope:** Architectural patterns, interface definitions, and system design principles for SUGA-ISP Lambda Execution Engine.

---

## ⚠️ CRITICAL TERMINOLOGY

**Always use correct terms:**

**SUGA** = **S**ingle **U**niversal **G**ateway **A**rchitecture
- The gateway pattern in Lambda code
- gateway.py as single entry point
- Architecture pattern for code structure

**SIMA** = **S**ynthetic **I**ntegrate **M**emory **A**rchitecture  
- The neural maps system (this documentation)
- 4-layer knowledge structure
- Architecture pattern for knowledge management

**ISP** = **I**nterface **S**egregation **P**rinciple
- Each interface has focused purpose
- Part of SUGA architecture

**Never say:** "SIMA pattern" when referring to gateway architecture  
**Always say:** "SUGA pattern" when referring to gateway architecture

---

## Topics in This Category

### Core Architecture
- **Description:** Foundational SUGA pattern, execution engine, gateway trinity
- **Items:** ARCH-01 to ARCH-06
- **Index:** `NM01-CORE-Architecture.md`
- **Priority Items:** 
  - ARCH-01: Gateway Trinity (CRITICAL)
  - ARCH-02: Execution Engine (CRITICAL)
  - ARCH-07: LMMS System (HIGH)

### Interfaces - Core
- **Description:** First 6 interfaces (CACHE through VALIDATION)
- **Items:** INT-01 to INT-06
- **Index:** `NM01-INTERFACES-Core.md`
- **Priority Items:**
  - INT-01: CACHE (CRITICAL)
  - INT-02: LOGGING (CRITICAL)
  - INT-03: SECURITY (CRITICAL)

### Interfaces - Advanced
- **Description:** Remaining 6 interfaces (PERSISTENCE through ERROR_HANDLING)
- **Items:** INT-07 to INT-12
- **Index:** `NM01-INTERFACES-Advanced.md`
- **Priority Items:**
  - INT-07: PERSISTENCE (HIGH)
  - INT-08: COMMUNICATION (HIGH)

---

## Quick Access

**Most Frequently Accessed:**
1. **ARCH-01**: Gateway Trinity (SUGA pattern foundation)
2. **ARCH-07**: LMMS System (cold start optimization)
3. **INT-01**: CACHE Interface
4. **INT-02**: LOGGING Interface
5. **INT-03**: SECURITY Interface

---

## Cross-Category Relationships

**Architecture → Decisions:**
- ARCH-01 (Gateway) ← DEC-01 (Why SUGA pattern)
- ARCH-07 (LMMS) ← DEC-04 (No threading)

**Architecture → Rules:**
- ARCH-01 (Gateway) → RULE-01 (Gateway-only imports)

**Architecture → Anti-Patterns:**
- ARCH-01 (Gateway) → AP-01 (Direct imports)

---

## FAQ

### Q: What's the difference between SIMA and SUGA?
**A:** 
- **SUGA** = Gateway pattern in Lambda code (architecture for code)
- **SIMA** = Neural maps system (architecture for knowledge)
- **Don't confuse them!** Use "SUGA pattern" when discussing gateway architecture.

### Q: What is ISP?
**A:** Interface Segregation Principle - each interface has one focused purpose. Part of SUGA architecture.

### Q: What is the Gateway Trinity?
**A:** Three-layer pattern: Gateway → Interface → Implementation. See ARCH-01.

### Q: What is LMMS?
**A:** Lambda Memory Management System (LIGS + LUGS + ZAPH). See ARCH-07.

---

## Navigation

- **Up:** Master Index (NM00A-Master_Index.md)
- **Quick Lookup:** Quick Index (NM00-Quick_Index.md)
- **Related Categories:** 
  - NM02 (Dependencies - import rules)
  - NM04 (Decisions - why SUGA was chosen)

---

## Keywords

SUGA, gateway, architecture, interfaces, LMMS, LIGS, LUGS, ZAPH, ISP, patterns, design

---

## Version History

- **2025-10-23**: Terminology corrections (Phase 5) - SIMA → SUGA for gateway pattern
- **2025-10-20**: Updated for SIMA v3 neural maps structure
- **2025-10-15**: Created category index

---

**End of Index**

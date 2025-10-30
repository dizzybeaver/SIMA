# NM04-Decisions-Architecture_Index.md

# Decisions - Architecture Index

**Category:** NM04 - Decisions
**Topic:** Architecture
**Items:** 5 active + 6 reserved
**Last Updated:** 2025-10-24 (Terminology corrections)

---

## Topic Overview

**Description:** Foundational architectural decisions that define the system's structure, patterns, and core principles. These decisions shape how all other components are designed and interact. The SUGA pattern, gateway centralization, and dispatch patterns form the architectural backbone of the entire Lambda Execution Engine.

**Keywords:** architecture, patterns, SUGA, gateway, structure, foundation, dispatch, threading, sentinel

---

## Individual Files

### DEC-01: SUGA Pattern Choice
- **File:** `NM04-Decisions-Architecture_DEC-01.md`
- **Summary:** Single Universal Gateway Architecture (SUGA) pattern prevents circular dependencies through gateway indirection
- **Priority:** 🔴 Critical
- **Impact:** Entire system architecture, import structure, testability
- **Key Benefit:** Mathematically impossible to have circular imports

### DEC-02: Gateway Centralization
- **File:** `NM04-Decisions-Architecture_DEC-02.md`
- **Summary:** All cross-interface operations route through central gateway for consistency and control
- **Priority:** 🟡 High
- **Impact:** Import structure, testing, monitoring, infrastructure changes
- **Key Benefit:** Single point for logging, metrics, caching

### DEC-03: Dispatch Dictionary Pattern
- **File:** `NM04-Decisions-Architecture_DEC-03.md`
- **Summary:** Dictionary dispatch instead of if/elif chains for O(1) routing and maintainability
- **Priority:** 🔴 Critical
- **Impact:** Router performance, code maintainability, extensibility
- **Key Benefit:** O(1) lookup, 90% code reduction vs if/elif

### DEC-04: No Threading Locks
- **File:** `NM04-Decisions-Architecture_DEC-04.md`
- **Summary:** Lambda single-threaded execution means locks are unnecessary overhead
- **Priority:** 🔴 Critical
- **Impact:** Performance, code simplicity, correctness
- **Key Benefit:** Simpler code, no lock contention, follows YAGNI

### DEC-05: Sentinel Sanitization
- **File:** `NM04-Decisions-Architecture_DEC-05.md`
- **Summary:** Internal sentinels must be sanitized at router boundary to prevent leaks
- **Priority:** 🟡 High
- **Impact:** API cleanliness, bug prevention, performance
- **Key Benefit:** Prevents 535ms penalty from BUG-01

---

## Reserved Slots

**DEC-06 through DEC-11:** Reserved for future architectural decisions

These slots are intentionally reserved to maintain consistent REF-ID numbering as the architecture evolves. When new fundamental architectural patterns emerge, they will be documented here with proper REF-IDs.

---

## Related Topics

**Within NM04 (Decisions):**
- Technical Decisions (DEC-12 to DEC-19) - Implement architectural patterns
- Operational Decisions (DEC-20 to DEC-23) - Runtime behavior shaped by architecture

**Other Categories:**
- NM01-Architecture (implements these decisions via ARCH-## patterns)
- NM02-Dependencies (shaped by DEC-01 SUGA, DEC-02 Gateway)
- NM05-AntiPatterns (AP-01, AP-08 prevented by DEC-01, DEC-04)
- NM06-Lessons (LESS-01 reinforces DEC-01, BUG-01 led to DEC-05)

---

## Key Relationships

**DEC-01 (SUGA) enables:**
- DEC-02: Gateway centralization becomes natural entry point
- RULE-01: Cross-interface imports via gateway only
- ARCH-01: Gateway trinity pattern

**DEC-03 (Dispatch) enables:**
- O(1) operation routing across all interfaces
- Clean extension without touching existing code
- Predictable performance characteristics

**DEC-04 (No locks) follows:**
- Lambda constraint: Single-threaded execution
- YAGNI principle: Don't add what you don't need
- Simplicity over premature optimization

**DEC-05 (Sentinel) prevents:**
- BUG-01: Sentinel leak causing 535ms penalty
- API pollution with internal implementation details
- Confusing external callers with sentinel objects

---

## Navigation

- **Up:** NM04-Decisions_Index.md (Category Index)
- **Sibling Topics:** 
  - Technical Decisions Index
  - Operational Decisions Index

---

**Version History:**
- **2025-10-24**: Terminology corrections (SIMA → SUGA for gateway pattern)
- **2025-10-23**: Updated with current architecture decisions

---

**File:** `NM04-Decisions-Architecture_Index.md`  
**End of Index**

# NM01-Architecture-CoreArchitecture_Index.md

# Architecture - Core Architecture Topic Index

**Category:** NM01 - Architecture  
**Topic:** Core Architecture  
**Items:** 8  
**Last Updated:** 2025-10-24

---

## Topic Overview

**Description:** Foundational SUGA pattern, execution engine, gateway trinity, and memory management systems (LMMS).

**Keywords:** SUGA, gateway, architecture, LMMS, LIGS, LUGS, ZAPH, execution engine

---

## Individual Files

### ARCH-01 to ARCH-06: Core SUGA Architecture
- **File:** `NEURAL MAP 01-Core Architecture - IMPLEMENTATION_Core.md` (root /nmap)
- **Summary:** The six foundational architecture patterns - Gateway Trinity, Execution Engine, Router Pattern, Internal Implementation, Extension Architecture, and Lambda Entry Point. Kept grouped because they tell a coherent story.
- **Priority:** 🔴 CRITICAL
- **Note:** This file remains in root /nmap directory per current structure. Contains ARCH-01, ARCH-02, ARCH-03, ARCH-04, ARCH-05, ARCH-06 as grouped content.

### ARCH-07: LMMS System
- **File:** `NM01-ARCH-07-LMMS-Lambda Memory Management System.md` (root /nmap)
- **Summary:** Lambda Memory Management System - the umbrella architecture for managing complete module lifecycle. Includes LIGS (Lazy Import Gateway System), LUGS (Lazy Unload Gateway System), and ZAPH (Zero-Abstraction Fast Path).
- **Priority:** 🟡 HIGH
- **Benefits:** 60% faster cold starts, 70% less initial memory, 82% reduction in GB-seconds
- **Note:** Already atomized in v3 format

### ARCH-08: Future/Experimental Architectures
- **File:** `NM01-ARCH-08_Future_Experimental Architectures.md` (root /nmap)
- **Summary:** Three lightly-developed or conceptual architectures: FTPMS (Free Tier Protection & Monitoring), OFB (Operation Fusion & Batching), and MDOE (Metadata-Driven Operation Engine).
- **Priority:** 🟢 MEDIUM
- **Status:** Conceptual / Early Development
- **Note:** Already atomized in v3 format

---

## Related Topics

- **Interfaces-Core:** INT-01 through INT-06 (6 core infrastructure interfaces)
- **Interfaces-Advanced:** INT-07 through INT-12 (6 advanced feature interfaces)

---

## Cross-Category Relationships

**Architecture → Decisions:**
- ARCH-01 (Gateway) ← DEC-01 (Why SUGA pattern)
- ARCH-07 (LMMS) ← DEC-04 (No threading), DEC-13 (Fast path), DEC-14 (Lazy loading)

**Architecture → Rules:**
- ARCH-01 (Gateway) → RULE-01 (Gateway-only imports)
- ARCH-03 (Router Pattern) → RULE-02 (No circular dependencies)

**Architecture → Anti-Patterns:**
- ARCH-01 (Gateway) → AP-01 (Direct imports)
- ARCH-03 (Router Pattern) → AP-02 (Skipping router layer)

---

## Quick Access - Most Referenced

1. **ARCH-01**: Gateway Trinity - The foundational 3-file structure
2. **ARCH-02**: Execution Engine - How execute_operation() works
3. **ARCH-07**: LMMS - Memory optimization and cold start improvement
4. **ARCH-05**: Extension Architecture - How to add new interfaces

---

## Usage Patterns

### Understanding the Architecture
**Query:** "How does the SUGA architecture work?"  
**Answer:** Read ARCH-01 (Gateway Trinity) and ARCH-02 (Execution Engine) for the foundation. These explain the 3-layer pattern and operation routing.

### Performance Optimization
**Query:** "How do I optimize Lambda cold start?"  
**Answer:** Read ARCH-07 (LMMS) for lazy loading, memory management, and fast path caching strategies.

### Adding New Functionality
**Query:** "How do I add a new interface?"  
**Answer:** Read ARCH-05 (Extension Architecture) for the 6-step process to extend the system.

---

## File Locations

**Note:** Due to current file server structure, some files remain in root /nmap directory:

- **ARCH-01 to ARCH-06:** `/nmap/NEURAL MAP 01-Core Architecture - IMPLEMENTATION_Core.md`
- **ARCH-07:** `/nmap/NM01-ARCH-07-LMMS-Lambda Memory Management System.md`
- **ARCH-08:** `/nmap/NM01-ARCH-08_Future_Experimental Architectures.md`

**Future:** May reorganize to `/nmap/NM01/` subdirectory for consistency.

---

## Navigation

- **Up:** NM01-Architecture_Index.md (Category Index)
- **Siblings:** 
  - NM01-Architecture-InterfacesCore_Index.md
  - NM01-Architecture-InterfacesAdvanced_Index.md

---

## Keywords

SUGA, gateway, architecture, trinity, execution engine, LMMS, LIGS, LUGS, ZAPH, routing, dispatch, extension

---

## Version History

- **2025-10-24**: Created Topic Index for Core Architecture (SIMA v3 migration)

---

**End of Index**

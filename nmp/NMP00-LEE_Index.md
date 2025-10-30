# NMP00-LEE_Index.md

# NMP01 - Lambda Execution Engine Index

**Project:** Lambda Execution Engine (SUGA-ISP)  
**Project Code:** LEE  
**Files:** 1  
**Last Updated:** 2025-10-26  
**Status:** âœ… Active Development

---

## Project Overview

### Purpose

Document implementation-specific knowledge for the SUGA-ISP Lambda Execution Engine, including:
- Home Assistant integration patterns
- Interface-specific implementations
- Gateway optimization specifics
- Project-specific design decisions
- Integration lessons learned

### Architecture

**Project:** SUGA-ISP Lambda Function  
**Pattern:** SUGA (Single Universal Gateway Architecture)  
**Layers:** Gateway → Interface (12) → Core  
**Primary Extension:** Home Assistant Smart Home

---

## Files in This Project

### Architecture Lessons

#### NMP01-LEE-01: HA Cache Functions - Application vs Infrastructure
- **File:** `NMP01-LEE-01.md`
- **Summary:** Perfect example of application logic vs infrastructure separation
- **Priority:** 🟢 HIGH
- **Status:** Active
- **Keywords:** application-logic, infrastructure, cache-strategies, ISP, separation-of-concerns

**What it teaches:**
- Why HA caching functions belong in `ha_core.py`, not INT-01
- How to distinguish application logic from infrastructure
- ISP principle in practice
- Scalability through proper layer separation

**Code References:**
- `ha_core.py`: `warm_ha_cache()`, `invalidate_entity_cache()`, `invalidate_domain_cache()`
- `interface_cache.py`: Generic cache operations
- `cache_core.py`: Infrastructure implementation

---

## Planned Files (Not Yet Created)

### Interface Catalogs (12 files planned)

**Purpose:** Document each interface's functions, use cases, and integration patterns

1. **NMP01-LEE-02**: INT-01 CACHE - Function Catalog
2. **NMP01-LEE-03**: INT-02 LOGGING - Function Catalog
3. **NMP01-LEE-04**: INT-03 SECURITY - Function Catalog
4. **NMP01-LEE-05**: INT-04 METRICS - Function Catalog
5. **NMP01-LEE-06**: INT-05 CONFIG - Function Catalog
6. **NMP01-LEE-07**: INT-06 VALIDATION - Function Catalog
7. **NMP01-LEE-08**: INT-07 PERSISTENCE - Function Catalog
8. **NMP01-LEE-09**: INT-08 COMMUNICATION - Function Catalog
9. **NMP01-LEE-10**: INT-09 TRANSFORMATION - Function Catalog
10. **NMP01-LEE-11**: INT-10 SCHEDULING - Function Catalog
11. **NMP01-LEE-12**: INT-11 MONITORING - Function Catalog
12. **NMP01-LEE-13**: INT-12 ERROR_HANDLING - Function Catalog

### Gateway Patterns (3 files planned)

13. **NMP01-LEE-14**: Gateway Core - execute_operation() Patterns
14. **NMP01-LEE-15**: Gateway Wrappers - Convenience Function Patterns
15. **NMP01-LEE-16**: Fast Path Optimization - ZAPH Pattern Implementation

### Home Assistant Integration (5 files planned)

16. **NMP01-LEE-17**: HA Core - API Integration Patterns
17. **NMP01-LEE-18**: HA Alexa - Smart Home Skill Integration
18. **NMP01-LEE-19**: HA Config - Configuration Management
19. **NMP01-LEE-20**: HA WebSocket - Real-time Communication
20. **NMP01-LEE-21**: HA Extension - Facade Pattern Implementation

### Specialized Topics (5-10 files planned)

21. **NMP01-LEE-22**: Failsafe System - Independence Design
22. **NMP01-LEE-23**: Circuit Breaker - Resilience Patterns
23. **NMP01-LEE-24**: Singleton Management - Memory Optimization
24. **NMP01-LEE-25**: Cold Start Optimization - LMMS Implementation
25. **NMP01-LEE-26**: Debug Tools - Diagnostic Capabilities

---

## File Organization

### By Topic

**Architecture & Patterns:**
- NMP01-LEE-01 (Application vs Infrastructure)
- NMP01-LEE-14 (Gateway Core)
- NMP01-LEE-15 (Gateway Wrappers)
- NMP01-LEE-16 (Fast Path)

**Interfaces (INT-01 to INT-12):**
- NMP01-LEE-02 through NMP01-LEE-13

**Home Assistant:**
- NMP01-LEE-17 through NMP01-LEE-21

**Specialized:**
- NMP01-LEE-22 through NMP01-LEE-26+

---

## Cross-References to Generic Knowledge

### Related NM (Generic Patterns)

**Architecture:**
- **ARCH-01**: Gateway Trinity → Implemented in LEE
- **DEC-01**: SUGA Pattern → LEE's foundation
- **ARCH-07**: LMMS (Lazy Module Management) → LEE cold start

**Lessons:**
- **LESS-01**: Read complete files → LEE development practice
- **LESS-08**: ISP → See NMP01-LEE-01 for example
- **LESS-15**: 5-step verification → LEE code review process

**Anti-Patterns:**
- **AP-01**: Direct imports → Avoided in LEE
- **AP-08**: Threading → Not used in LEE
- **AP-14**: Bare except → Avoided in LEE

### Related AWS (Cloud Provider)

**Lambda Optimization:**
- **AWS06-LESS-##**: Lambda lessons → Applied in LEE
- Cold start optimization → Implemented in LEE
- Memory management → Used in LEE

---

## Development Guidelines

### When to Add NMP01 File

**Create new file when:**
1. Documenting interface-specific implementation
2. Capturing project-specific pattern
3. Recording integration lesson
4. Explaining design decision unique to LEE

**Don't create file for:**
1. Generic patterns (use NM instead)
2. AWS best practices (use AWS instead)
3. Transferable lessons (use NM instead)

### File Naming

```
Format: NMP01-LEE-##.md

Next available: NMP01-LEE-02
```

---

## Statistics

**Current:**
- Files: 1
- Planned: 25+
- Topics: Architecture Lessons
- Code References: 3 files

**Growth Target:**
- 3 months: ~10 files (interface catalogs)
- 6 months: ~20 files (full documentation)
- 1 year: ~30 files (comprehensive)

---

## Quality Standards

### All NMP01 Files Must Have:

✅ Clear project context  
✅ Code references (file paths, line numbers)  
✅ REF-ID (NMP01-LEE-##)  
✅ 4-8 keywords  
✅ 3-7 related topics  
✅ Version history  
✅ File size < 200 lines

---

## Navigation

**Up:** NMP00A-Master_Index.md  
**Quick Search:** NMP00-Quick_Index.md  
**Base Maps:** NM00A-Master_Index.md  
**AWS Maps:** AWS00A-Master_Index.md

---

## Recent Updates

- **2025-10-26**: Created NMP01-LEE-01 (Application vs Infrastructure)
- **2025-10-26**: Established NMP01 project structure
- **2025-10-26**: Defined file organization and planned topics

---

**End of Project Index**

**Project:** Lambda Execution Engine (SUGA-ISP)  
**Status:** âœ… Active  
**Next File:** NMP00-LEE-02 (INT-01 CACHE Catalog)

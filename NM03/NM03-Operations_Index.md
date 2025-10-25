# NM03-Operations_Index.md

# NM03 - Operations Index

**Category Number:** NM03
**Topics:** 4
**Individual Files:** 13 REF-IDs
**Last Updated:** 2025-10-24

---

## Category Overview

**Purpose:** Documents how the SUGA-ISP Lambda system operates at runtime - operation flows, system pathways, error handling, and request tracing.

**Scope:** Runtime behavior, execution patterns, operational flows, error propagation, and system initialization sequences.

---

## Topics in This Category

### Flows
- **Description:** Operation execution flows showing step-by-step processing
- **Items:** 3 (FLOW-01, FLOW-02, FLOW-03)
- **File:** `NM03-Operations-Flows.md`
- **Priority Items:** FLOW-01 (Critical - Simple operation pattern)

### Pathways
- **Description:** System-level operational pathways and initialization sequences
- **Items:** 4 (PATH-01, PATH-02, PATH-03, PATH-05)
- **File:** `NM03-Operations-Pathways.md`
- **Priority Items:** PATH-01 (Critical - Cold start sequence)

### ErrorHandling
- **Description:** Error propagation patterns and exception handling strategies
- **Items:** 4 (PATH-04, ERROR-01, ERROR-02, ERROR-03)
- **File:** `NM03-Operations-ErrorHandling.md`
- **Priority Items:** PATH-04 (Critical - Error propagation)

### Tracing
- **Description:** Request traces and debugging patterns
- **Items:** 2 (TRACE-01, TRACE-02)
- **File:** `NM03-Operations-Tracing.md`
- **Priority Items:** TRACE-01 (Medium - Full request trace)

---

## Quick Access

**Most Frequently Accessed:**
1. **FLOW-01**: Simple operation (cache_get) - Basic flow pattern
2. **PATH-01**: Cold start sequence - System initialization  
3. **PATH-04**: Error propagation - How errors flow through layers
4. **FLOW-02**: Complex operation (HTTP POST) - Multi-interface pattern
5. **ERROR-01**: Exception handling - Try-except patterns

---

## REF-ID Quick Reference

| REF-ID | Title | File | Priority |
|--------|-------|------|----------|
| FLOW-01 | Simple operation (cache_get) | Flows | CRITICAL |
| FLOW-02 | Complex operation (HTTP POST) | Flows | HIGH |
| FLOW-03 | Cascading operation | Flows | HIGH |
| PATH-01 | Cold start sequence | Pathways | CRITICAL |
| PATH-02 | Cache operation flow | Pathways | HIGH |
| PATH-03 | Logging pipeline | Pathways | HIGH |
| PATH-04 | Error propagation | ErrorHandling | CRITICAL |
| PATH-05 | Metrics collection | Pathways | MEDIUM |
| ERROR-01 | Exception handling pattern | ErrorHandling | HIGH |
| ERROR-02 | Graceful degradation | ErrorHandling | HIGH |
| ERROR-03 | Error logging | ErrorHandling | MEDIUM |
| TRACE-01 | Full request trace | Tracing | MEDIUM |
| TRACE-02 | Data transformation pipeline | Tracing | MEDIUM |

---

## Integration with Other Categories

**Related to NM01 (Architecture):**
- Flows demonstrate SUGA pattern (gateway → interface → implementation)
- All operations follow gateway execution engine (ARCH-02)

**Related to NM02 (Dependencies):**
- PATH-01 follows dependency layer initialization order
- Cross-interface calls use gateway routing (RULE-01)

**Related to NM04 (Decisions):**
- Fast path optimization (DEC-07) affects FLOW-01
- Lazy loading (DEC-14) affects PATH-01
- Router-level exception catching (DEC-15) implements ERROR-01

**Related to NM05 (Anti-Patterns):**
- Correct patterns shown here prevent anti-patterns
- Error handling prevents unhandled exceptions (AP-09)

**Related to NM06 (Lessons):**
- Cold start optimization from BUG-01 (sentinel leak)
- Error patterns from BUG-03 (cascading failures)
- Metrics collection from LESS-10

---

## Navigation

**Up:** Master Index (NM00A-Master_Index.md)

**Related Categories:**
- NM01 - Architecture (structural patterns)
- NM02 - Dependencies (import relationships)
- NM04 - Decisions (design rationale)
- NM06 - Lessons (learned experiences)

---

## Keywords

operations, flows, pathways, runtime, execution, error-handling, tracing, initialization, cold-start

---

**End of Index**

# NM06-Lessons-Performance_Index.md

# Lessons - Performance Index

**Category:** NM06 - Lessons  
**Topic:** Performance  
**Items:** 4  
**Last Updated:** 2025-10-23 (added filename header v3.1.0)

---

## Topic Overview

**Description:** Performance optimization, measurement, and monitoring lessons learned from optimizing Lambda cold starts and runtime performance.

**Keywords:** performance, optimization, measurement, cold start, threading, rate limiting, memory

---

## Individual Files

### LESS-02: Measure, Don't Guess
- **File:** `NM06-Lessons-Performance_LESS-02.md`
- **Summary:** Data-driven debugging finds exact problems faster than guessing
- **Related:** BUG-01, LESS-10, DEC-05
- **Priority:** CRITICAL

### LESS-17: Threading Locks Unnecessary
- **File:** `NM06-Lessons-Performance_LESS-17.md`
- **Summary:** Lambda is single-threaded, threading locks add overhead without benefit
- **Related:** DEC-04, AP-08
- **Priority:** HIGH

### LESS-20: Memory Limits Prevent DoS
- **File:** `NM06-Lessons-Performance_LESS-20.md`
- **Summary:** Request size limits prevent memory exhaustion attacks
- **Related:** DEC-##, LESS-19
- **Priority:** HIGH

### LESS-21: Rate Limiting Essential
- **File:** `NM06-Lessons-Performance_LESS-21.md`
- **Summary:** Rate limiting protects system from abuse and cost overruns
- **Related:** DEC-##, LESS-20
- **Priority:** HIGH

---

## Cross-Topic Relationships

**Related Topics:**
- CoreArchitecture (LESS-02 shared, LESS-06 cost/benefit)
- Operations (LESS-10 monitoring, LESS-19 security)

**Frequently Accessed Together:**
- When optimizing: LESS-02, LESS-17
- When securing: LESS-20, LESS-21

---

**Navigation:**
- **Up:** Lessons Index (NM06-Lessons_Index.md)
- **Sibling Topics:** CoreArchitecture, Operations, Documentation, Evolution

---

**End of Index**

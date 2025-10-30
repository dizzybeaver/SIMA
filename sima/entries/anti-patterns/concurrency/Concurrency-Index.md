# File: Concurrency-Index.md

**Category:** Anti-Patterns  
**Topic:** Concurrency  
**Items:** 3  
**Version:** 1.0.0

---

## FILES

### AP-08: Threading Locks
- **Severity:** 🔴 Critical
- **Problem:** Using threading primitives in single-threaded Lambda
- **Key:** Lambda is single-threaded by design

### AP-11: Race Conditions
- **Severity:** 🟠 High
- **Problem:** Worrying about concurrent access within Lambda
- **Key:** Invocations are isolated

### AP-13: Multiprocessing
- **Severity:** 🟠 High
- **Problem:** Using multiprocessing in Lambda
- **Key:** Use Step Functions for parallelism

---

## COMMON THEME

**Lambda Execution Model:**
- Single-threaded invocations
- Isolated containers
- No shared memory
- External state only

---

**Keywords:** concurrency, threading, Lambda, single-threaded, isolation

**END OF INDEX**

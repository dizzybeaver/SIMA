# NM03-INDEX: Operational Pathways - Router
# SIMA Architecture Pattern - Data Flow & Operations
# Version: 2.0.0 | Phase: 2 SIMA Implementation

---

## Purpose

This INDEX file routes queries about operational flows, data pathways, error propagation, and request handling to the appropriate implementation files. It's the **Interface Layer** of the SIMA pattern for NM03.

**SIMA Architecture for NM03:**
```
Gateway Layer: NM00-Master-Index.md, NM00A-Quick-Index.md
    |
Interface Layer: THIS FILE (NM03-INDEX-Operations.md) 
    |
Implementation Layer: 
    ├── NM03-CORE-Pathways.md (Flow patterns & operational paths)
    └── NM03-ERROR-Handling.md (Error propagation & traces)
```

---

## Quick Stats

**Total REF IDs:** 13  
**Files:** 3 (1 INDEX + 2 IMPL)  
**Priority Breakdown:** Critical=3, High=6, Medium=4

---

## PART 1: DISPATCH TABLE

### By REF ID (Complete List)

| REF ID | Topic | File | Priority |
|--------|-------|------|----------|
| **FLOW-01** | Simple operation (cache_get) | CORE | CRITICAL |
| **FLOW-02** | Complex operation (HTTP request) | CORE | HIGH |
| **FLOW-03** | Cascading operation (with dependencies) | CORE | HIGH |
| **PATH-01** | Cold start sequence | CORE | CRITICAL |
| **PATH-02** | Cache operation flow | CORE | HIGH |
| **PATH-03** | Logging pipeline | CORE | HIGH |
| **PATH-04** | Error propagation | ERROR | CRITICAL |
| **PATH-05** | Metrics collection | CORE | MEDIUM |
| **ERROR-01** | Exception handling pattern | ERROR | HIGH |
| **ERROR-02** | Graceful degradation | ERROR | HIGH |
| **ERROR-03** | Error logging | ERROR | MEDIUM |
| **TRACE-01** | Request trace example | ERROR | MEDIUM |
| **TRACE-02** | Data transformation pipeline | CORE | MEDIUM |

---

## PART 2: QUICK REFERENCE BY TOPIC

### Operation Flow Patterns (File: CORE)

**Simple Operations:**
- **FLOW-01:** cache_get flow (5 hops, minimal latency)

**Complex Operations:**
- **FLOW-02:** HTTP request with caching, security, metrics
- **FLOW-03:** Cascading operations across multiple interfaces

---

### Operational Pathways (File: CORE)

**System Operations:**
- **PATH-01:** Cold start sequence (Layer 0 -> Layer 4)
- **PATH-02:** Cache operation flow (get/set/delete)
- **PATH-03:** Logging pipeline (format -> route -> output)
- **PATH-05:** Metrics collection (record -> aggregate -> report)

---

### Error Handling (File: ERROR)

**Error Patterns:**
- **PATH-04:** Error propagation through layers
- **ERROR-01:** Try-except patterns at each layer
- **ERROR-02:** Graceful degradation strategies
- **ERROR-03:** Error logging and visibility

**Tracing:**
- **TRACE-01:** Full request trace with timing
- **TRACE-02:** Data transformation pipeline

---

## PART 3: ROUTING BY KEYWORD

Use this section for fast keyword-based routing:

### Flow & Operations
- "operation flow", "how does X work" -> **FLOW-01, FLOW-02** (CORE)
- "cache_get flow", "simple operation" -> **FLOW-01** (CORE)
- "HTTP request flow", "complex operation" -> **FLOW-02** (CORE)
- "cascading operation", "multiple interfaces" -> **FLOW-03** (CORE)

### System Pathways
- "cold start", "initialization", "system startup" -> **PATH-01** (CORE)
- "cache operation", "cache flow" -> **PATH-02** (CORE)
- "logging pipeline", "log flow" -> **PATH-03** (CORE)
- "metrics collection", "metrics flow" -> **PATH-05** (CORE)

### Error Handling
- "error propagation", "error flow", "exception handling" -> **PATH-04** (ERROR)
- "try except", "exception pattern" -> **ERROR-01** (ERROR)
- "graceful degradation", "failover" -> **ERROR-02** (ERROR)
- "error logging", "error visibility" -> **ERROR-03** (ERROR)

### Tracing & Debugging
- "request trace", "full trace", "end-to-end" -> **TRACE-01** (ERROR)
- "data transformation", "pipeline" -> **TRACE-02** (CORE)

---

## PART 4: FILE ACCESS METHODS

### Method 1: Direct Search (Recommended)
```
Search project knowledge for:
"NM03-CORE-Pathways FLOW-01 cache get flow"
"NM03-CORE-Pathways PATH-01 cold start sequence"
"NM03-ERROR-Handling PATH-04 error propagation"
```

### Method 2: Via Master Index
```
Search: "NM00-Master-Index operational pathways"
Then: Navigate to NM03 section
Then: Find specific REF ID
```

### Method 3: Via Quick Index
```
Search: "NM00A-Quick-Index cold start"
Get: Auto-recall context
Then: Reference detailed file if needed
```

---

## PART 5: PRIORITY LEARNING PATH

### CRITICAL - Learn These First (3 refs)

1. **FLOW-01** (CORE) - Simple operation flow (foundation pattern)
2. **PATH-01** (CORE) - Cold start sequence (system initialization)
3. **PATH-04** (ERROR) - Error propagation (how errors flow)

**Why Critical:** These define how the system works at runtime and how it handles failures.

---

### HIGH - Reference Frequently (6 refs)

1. **FLOW-02** (CORE) - Complex operation patterns
2. **FLOW-03** (CORE) - Cascading operations
3. **PATH-02** (CORE) - Cache operation flow
4. **PATH-03** (CORE) - Logging pipeline
5. **ERROR-01** (ERROR) - Exception handling patterns
6. **ERROR-02** (ERROR) - Graceful degradation

**Why High:** Frequently needed when debugging or understanding system behavior.

---

### MEDIUM - As Needed (4 refs)

1. **PATH-05** (CORE) - Metrics collection
2. **ERROR-03** (ERROR) - Error logging
3. **TRACE-01** (ERROR) - Request trace examples
4. **TRACE-02** (CORE) - Data transformation

**Why Medium:** Important for deep understanding but less frequently referenced.

---

## PART 6: INTEGRATION WITH OTHER NEURAL MAPS

### NM03 Relates To:

**NM01 (Architecture):**
- FLOW patterns use gateway/router/core pattern from NM01
- All operations execute through ARCH-02 (Gateway execution engine)

**NM02 (Dependencies):**
- PATH-01 follows dependency layer order (Layer 0 -> Layer 4)
- Cross-interface operations follow RULE-01 (via gateway)

**NM04 (Decisions):**
- NM04-DEC-07 (Fast path optimization) affects FLOW-01
- NM04-DEC-14 (Lazy loading) affects PATH-01

**NM05 (Anti-Patterns):**
- FLOW patterns show correct approach vs anti-patterns
- Error handling prevents anti-patterns like NM05-AP-09

**NM06 (Learned Experiences):**
- PATH-01 cold start optimizations from NM06-BUG-01
- Error patterns from NM06-BUG-03 (cascading failures)

**NM07 (Decision Logic):**
- NM07-DT-05 (How to handle errors) implements PATH-04
- Decision trees guide operational choices

---

## PART 7: USAGE EXAMPLES

### Example 1: User Asks About Operation Flow
```
User: "How does cache_get work internally?"

Claude Action:
1. Search project knowledge: "NM03-CORE FLOW-01 cache get"
2. Find: FLOW-01 in NM03-CORE-Pathways.md
3. Response: [Explain 5-hop flow with timing]
```

---

### Example 2: User Debugging Cold Start
```
User: "Why is cold start slow?"

Claude Action:
1. Search project knowledge: "NM03-CORE PATH-01 cold start"
2. Find: PATH-01 in NM03-CORE-Pathways.md
3. Response: [Explain Layer 0->4 initialization, ~50-80ms typical]
4. Cross-reference: NM06-BUG-01 (Sentinel leak that added 535ms)
```

---

### Example 3: User Asks About Error Handling
```
User: "How do errors propagate through the system?"

Claude Action:
1. Search project knowledge: "NM03-ERROR PATH-04 error propagation"
2. Find: PATH-04 in NM03-ERROR-Handling.md
3. Response: [Explain 4-layer error propagation with examples]
```

---

### Example 4: User Wants Full Request Trace
```
User: "Show me an end-to-end request trace"

Claude Action:
1. Search project knowledge: "NM03-ERROR TRACE-01 request trace"
2. Find: TRACE-01 in NM03-ERROR-Handling.md
3. Response: [Display complete trace with timing at each layer]
```

---

## PART 8: FILE STATISTICS

### NM03-CORE-Pathways.md
- **Size:** ~400 lines
- **REF IDs:** 8 (FLOW-01 to 03, PATH-01 to 03, PATH-05, TRACE-02)
- **Topics:** Operation flows, system pathways
- **Priority:** 2 CRITICAL, 5 HIGH, 1 MEDIUM

### NM03-ERROR-Handling.md
- **Size:** ~300 lines
- **REF IDs:** 5 (PATH-04, ERROR-01 to 03, TRACE-01)
- **Topics:** Error propagation, exception handling, tracing
- **Priority:** 1 CRITICAL, 2 HIGH, 2 MEDIUM

### Total NM03 Coverage
- **Total Size:** ~700 lines across 2 implementation files
- **Total REF IDs:** 13
- **Compliance:** Both files under 600-line limit ✅

---

## PART 9: TERMINOLOGY NOTES

**SIMA vs SUGA-ISP:**
- SIMA: Architecture pattern (Gateway -> Interface -> Implementation)
- SUGA-ISP: Lambda project name using SIMA architecture
- This file uses SIMA when discussing architecture patterns
- Operational flows reference actual code files by name

**Pathways vs Flows:**
- Pathways (PATH-XX): High-level operational sequences
- Flows (FLOW-XX): Detailed step-by-step execution traces
- Both describe how data moves through the system

---

## END OF INDEX

**Next Steps:**
- Search for NM03-CORE-Pathways.md for operation flows
- Search for NM03-ERROR-Handling.md for error patterns
- Use dispatch table above for specific REF ID routing

**Related Files:**
- NM00-Master-Index.md (Gateway to all Neural Maps)
- NM00A-Quick-Index.md (Quick lookups and auto-recall)
- NM01-INDEX-Architecture.md (Architecture patterns)
- NM02-INDEX-Dependencies.md (Dependency relationships)

# EOF

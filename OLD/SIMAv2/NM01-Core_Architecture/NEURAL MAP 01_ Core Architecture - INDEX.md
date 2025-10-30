# NEURAL MAP 01: Core Architecture - INDEX

**Purpose:** Router and quick reference for SIMA architecture and interface specifications  
**Status:** ✅ ACTIVE  
**Last Updated:** 2025-10-21  
**File Structure:** 1 INDEX + 3 Implementation files

---

## CRITICAL: Terminology

**SIMA = Architecture Pattern**
- **S**ingle Universal Gateway **A**rchitecture
- **I**nterface Separation **P**rinciple (ISP)
- **M**odular **A**rchitecture
- Pattern: Gateway → Interface → Implementation (3 layers)

**SUGA-ISP = Lambda Project Name**
- Uses SIMA architecture pattern
- Project files: `gateway.py`, `interface_*.py`, `*_core.py`

**Usage:**
- ✅ Correct: "SIMA architecture", "SIMA pattern", "SUGA-ISP Lambda project"
- ❌ Incorrect: "SUGA-ISP architecture" (use "SIMA architecture")

---

## File Structure

This neural map is split into 4 files for optimal Claude token management:

```
NM01-INDEX-Architecture.md (THIS FILE)
├─ Dispatch table routing to Implementation files
├─ Quick reference by REF ID
├─ Keyword lookup
└─ Usage patterns

NM01-CORE-Architecture.md (~600 lines)
├─ ARCH-01: Gateway Trinity
├─ ARCH-02: Gateway execution engine
├─ ARCH-03: Router pattern
├─ ARCH-04: Internal implementation pattern
├─ ARCH-05: Extension architecture
├─ ARCH-06: Lambda entry point
├─ ARCH-07: LMMS (Lazy Module Management System)
└─ ARCH-08: Future/Experimental Architectures

NM01-INTERFACES-Core.md (~350 lines)
├─ INT-01: CACHE interface
├─ INT-02: LOGGING interface
├─ INT-03: SECURITY interface
├─ INT-04: METRICS interface
├─ INT-05: CONFIG interface
└─ INT-06: SINGLETON interface

NM01-INTERFACES-Advanced.md (~300 lines)
├─ INT-07: INITIALIZATION interface
├─ INT-08: HTTP_CLIENT interface
├─ INT-09: WEBSOCKET interface
├─ INT-10: CIRCUIT_BREAKER interface
├─ INT-11: UTILITY interface
└─ INT-12: DEBUG interface
```

---

## Section 1: Dispatch Table

### Architecture References (ARCH)

| REF ID | Topic | Priority | File Location |
|--------|-------|----------|---------------|
| **ARCH-01** | Gateway Trinity | 🔴 CRITICAL | NM01-CORE-Architecture.md |
| **ARCH-02** | Gateway execution engine | 🔴 CRITICAL | NM01-CORE-Architecture.md |
| **ARCH-03** | Router pattern | 🟡 HIGH | NM01-CORE-Architecture.md |
| **ARCH-04** | Internal implementation | 🟡 HIGH | NM01-CORE-Architecture.md |
| **ARCH-05** | Extension architecture | 🟢 MEDIUM | NM01-CORE-Architecture.md |
| **ARCH-06** | Lambda entry point | 🟡 HIGH | NM01-CORE-Architecture.md |
| **ARCH-07** | LMMS (Memory Management) | 🟡 HIGH | NM01-CORE-Architecture.md |
| **ARCH-08** | Future/Experimental | 🟢 MEDIUM | NM01-CORE-Architecture.md |

### Interface References (INT)

| REF ID | Interface | Priority | File Location |
|--------|-----------|----------|---------------|
| **INT-01** | CACHE | 🔴 CRITICAL | NM01-INTERFACES-Core.md |
| **INT-02** | LOGGING | 🔴 CRITICAL | NM01-INTERFACES-Core.md |
| **INT-03** | SECURITY | 🟡 HIGH | NM01-INTERFACES-Core.md |
| **INT-04** | METRICS | 🟢 MEDIUM | NM01-INTERFACES-Core.md |
| **INT-05** | CONFIG | 🟡 HIGH | NM01-INTERFACES-Core.md |
| **INT-06** | SINGLETON | 🟢 MEDIUM | NM01-INTERFACES-Core.md |
| **INT-07** | INITIALIZATION | 🟢 MEDIUM | NM01-INTERFACES-Advanced.md |
| **INT-08** | HTTP_CLIENT | 🟡 HIGH | NM01-INTERFACES-Advanced.md |
| **INT-09** | WEBSOCKET | 🟢 MEDIUM | NM01-INTERFACES-Advanced.md |
| **INT-10** | CIRCUIT_BREAKER | 🟡 HIGH | NM01-INTERFACES-Advanced.md |
| **INT-11** | UTILITY | 🟢 MEDIUM | NM01-INTERFACES-Advanced.md |
| **INT-12** | DEBUG | 🟢 MEDIUM | NM01-INTERFACES-Advanced.md |

---

## Section 2: Quick Reference by Keyword

### Gateway & Core Architecture
**Keywords:** gateway, gateway trinity, execution engine, lambda entry point, router pattern, SIMA architecture

→ Go to: **NM01-CORE-Architecture.md**
- ARCH-01: Three-file gateway structure (`gateway.py`, `gateway_core.py`, `gateway_wrappers.py`)
- ARCH-02: `execute_operation(interface, operation, params)` signature
- ARCH-03: Router dispatch pattern (_OPERATION_DISPATCH)
- ARCH-04: Core implementation separation
- ARCH-05: How to extend with new interfaces
- ARCH-06: Lambda handler entry point

### Performance & Memory Management
**Keywords:** LMMS, LIGS, LUGS, ZAPH, lazy loading, memory management, cold start, fast path

→ Go to: **NM01-CORE-Architecture.md**
- ARCH-07: LMMS system (Lazy Module Management System)
  - LIGS: Lazy Import Gateway System (60% faster cold starts)
  - LUGS: Lazy Unload Gateway System (82% less GB-seconds)
  - ZAPH: Zero-Abstraction Fast Path (97% faster hot paths)

### Future Architectures
**Keywords:** FTPMS, OFB, MDOE, future patterns, experimental, planned

→ Go to: **NM01-CORE-Architecture.md**
- ARCH-08: Future/Experimental Architectures
  - FTPMS: Free Tier Protection & Monitoring System
  - OFB: Operation Fusion & Batching
  - MDOE: Metadata-Driven Operation Engine

### Infrastructure Interfaces
**Keywords:** cache, logging, security, metrics, config, singleton

→ Go to: **NM01-INTERFACES-Core.md**
- INT-01: Cache operations (set, get, delete, clear, TTL management)
- INT-02: Logging system (info, warning, error, critical, debug levels)
- INT-03: Security validation (input sanitization, sentinel checks)
- INT-04: Metrics tracking (operation timing, counters, statistics)
- INT-05: Configuration management (multi-tier, presets, environment vars)
- INT-06: Singleton storage (factory pattern, lifecycle management)

### Advanced Interfaces
**Keywords:** initialization, http, websocket, circuit breaker, utility, debug

→ Go to: **NM01-INTERFACES-Advanced.md**
- INT-07: System initialization (cold start, lazy init, warmup)
- INT-08: HTTP client operations (GET, POST, request building)
- INT-09: WebSocket management (connections, messages, lifecycle)
- INT-10: Circuit breaker pattern (failure tracking, recovery)
- INT-11: Utility functions (string ops, JSON, time, validation)
- INT-12: Debug tooling (inspection, diagnostics, troubleshooting)

---

## Section 3: Common Queries

### "How does the gateway work?"
→ **NM01-CORE-Architecture.md** - ARCH-01, ARCH-02
- Gateway Trinity (3-file structure)
- Execution engine pattern
- Why centralized dispatch

### "How do I use [specific interface]?"
→ Check dispatch table above for file location
- Core interfaces: **NM01-INTERFACES-Core.md**
- Advanced interfaces: **NM01-INTERFACES-Advanced.md**

### "What interfaces are available?"
→ All 12 interfaces listed in dispatch table
- 6 Core infrastructure interfaces (INT-01 through INT-06)
- 6 Advanced feature interfaces (INT-07 through INT-12)

### "How do I add a new interface?"
→ **NM01-CORE-Architecture.md** - ARCH-05
- Extension architecture pattern
- Steps to create new interface
- Integration with gateway

### "What's the difference between SIMA and SUGA-ISP?"
→ See **Terminology** section at top of this file
- SIMA = Architectural pattern
- SUGA-ISP = Project name (uses SIMA)

### "Why is cold start so fast?"
→ **NM01-CORE-Architecture.md** - ARCH-07
- LIGS lazy loading reduces initial load by 70%
- LMMS optimizes cold start (850ms → 320ms)
- See full LMMS documentation for details

### "What are the future architectures?"
→ **NM01-CORE-Architecture.md** - ARCH-08
- FTPMS: Free tier protection (lightly developed)
- OFB: Operation batching (conceptual)
- MDOE: Metadata-driven operations (conceptual)

---

## Section 4: Related Neural Maps

This neural map has strong connections to:

- **NM02: Interface Dependency Web** - Shows how interfaces depend on each other
- **NM03: Operational Pathways** - Shows how data flows through architecture
- **NM04: Design Decisions** - Explains why SIMA pattern was chosen
- **NM05: Anti-Patterns** - What NOT to do with this architecture
- **NM06: Learned Experiences** - Real bugs and lessons from using SIMA
- **NM07: Decision Logic** - Decision trees for common questions

---

## Section 5: Priority Learning Path

### 🔴 Start Here (CRITICAL - Learn First)
1. **ARCH-01**: Gateway Trinity - The core 3-file structure
2. **ARCH-02**: Gateway execution engine - How dispatch works
3. **INT-01**: CACHE interface - Most frequently used
4. **INT-02**: LOGGING interface - Essential for debugging

### 🟡 Learn Next (HIGH - Reference Frequently)
5. **ARCH-03**: Router pattern - Understand dispatch tables
6. **ARCH-06**: Lambda entry point - How Lambda calls gateway
7. **ARCH-07**: LMMS system - Memory and performance optimization
8. **INT-03**: SECURITY interface - Input validation
9. **INT-05**: CONFIG interface - Configuration management
10. **INT-08**: HTTP_CLIENT interface - External API calls
11. **INT-10**: CIRCUIT_BREAKER interface - Fault tolerance

### 🟢 Learn as Needed (MEDIUM)
12. **ARCH-04**: Internal implementation - Code organization
13. **ARCH-05**: Extension architecture - Adding new features
14. **ARCH-08**: Future architectures - Planned enhancements
15. **INT-04**: METRICS interface - Performance tracking
16. **INT-06**: SINGLETON interface - State management
17. **INT-07**: INITIALIZATION interface - System startup
18. **INT-09**: WEBSOCKET interface - Real-time communication
19. **INT-11**: UTILITY interface - Helper functions
20. **INT-12**: DEBUG interface - Troubleshooting tools

---

## Section 6: Usage Patterns

### Pattern 1: Understanding Architecture
```
Query: "Explain the SIMA architecture"
Route: NM01-CORE-Architecture.md
Focus: ARCH-01 (Gateway Trinity), ARCH-02 (Execution engine)
```

### Pattern 2: Using Specific Interface
```
Query: "How do I cache data?"
Route: NM01-INTERFACES-Core.md
Focus: INT-01 (CACHE interface)
Gateway wrapper: gateway.cache_set(key, value, ttl)
```

### Pattern 3: Building New Feature
```
Query: "How do I add a new interface?"
Route: NM01-CORE-Architecture.md
Focus: ARCH-05 (Extension architecture)
Related: NM02 (dependency rules), NM05 (anti-patterns to avoid)
```

### Pattern 4: Troubleshooting
```
Query: "Why isn't my interface working?"
Routes: 
1. NM01-INTERFACES-*.md (check interface spec)
2. NM03 (check operational pathway)
3. NM06 (check for similar past issues)
```

### Pattern 5: Performance Optimization
```
Query: "How can I optimize cold start?"
Route: NM01-CORE-Architecture.md
Focus: ARCH-07 (LMMS system)
Related: NM03-PATH-01 (cold start sequence), NM06-LESS-10 (monitoring)
```

---

## Section 7: File Access Methods

When Claude needs to read these files:

### Method 1: Project Knowledge Search (PRIMARY)
```python
# Use project_knowledge_search tool
query = "NM01-CORE-Architecture ARCH-01 Gateway Trinity"
# Returns content from file
```

### Method 2: GitHub Raw URL (SECONDARY)
```python
# If file not in project knowledge
# Ask user for GitHub raw URL
# Use web_fetch tool to retrieve
```

### Method 3: User Upload (FALLBACK)
```python
# If neither method works
# Ask user to upload the file
# Use window.fs.readFile to access
```

---

## Section 8: Statistics

**Total REF IDs:** 20 (8 ARCH + 12 INT)  
**Total Files:** 4 (1 INDEX + 3 Implementation)  
**Total Lines:** ~1,250 (all files under 600-line limit)  
**Priority Breakdown:**
- 🔴 CRITICAL: 4 refs (20%)
- 🟡 HIGH: 9 refs (45%)
- 🟢 MEDIUM: 7 refs (35%)

**Most Referenced:**
- ARCH-01 (Gateway Trinity)
- ARCH-02 (Execution engine)
- ARCH-07 (LMMS system)
- INT-01 (CACHE)
- INT-02 (LOGGING)

---

## Section 9: Integration Notes

### Gateway Files to Update
When modifying architecture:
1. `gateway.py` - Main dispatcher
2. `gateway_core.py` - Core operations
3. `gateway_wrappers.py` - Convenience wrappers

### Master Index Updates
After changes, update:
- NM00A-Master-Index.md (add any new REF IDs)
- NM00-Quick-Index.md (update triggers if needed)

### Cross-Reference Updates
Check these neural maps for impact:
- NM02: Interface dependencies
- NM03: Operational pathways
- NM07: Decision trees

---

## End Notes

**This INDEX file provides fast routing to detailed architecture content.**

When Claude receives architecture questions:
1. Search this INDEX for keywords or REF IDs
2. Route to appropriate Implementation file
3. Return detailed answer with context

All detailed content is in Implementation files to keep this INDEX under 600 lines.

**Status:** ✅ ACTIVE - Ready for use

**Version History:**
- 2025-10-20: Split from monolithic file into 4-file structure
- 2025-10-21: Added ARCH-07 (LMMS) and ARCH-08 (Future) to dispatch table

# EOF

# NEURAL_MAP_00A-Master_Index.md
# Master Catalog - All Neural Maps
# SIMA (Synthetic Integrated Memory Architecture) - Gateway Layer
# Version: 2.0.0 | Phase: 1 Foundation | Updated: 2025.10.20

---

## Purpose

This is the **Master Catalog** - the complete reference index of all neural map files in the SIMA system. It provides:
- Complete file inventory
- Topic organization
- Priority classification
- Reference count tracking
- Cross-reference mapping

**Think of this as the table of contents for your entire knowledge base.**

---

## File Inventory

### Gateway Layer (2 files)
```
NM00-Master-Index.md          (THIS FILE - Complete catalog)
NM00-Quick-Index.md           (Fast keyword triggers and lookups)
```

### Interface Layer (7 files)
```
NM01-INDEX-Architecture.md    (Core SIMA architecture patterns)
NM02-INDEX-Dependencies.md    (Interface dependency relationships)
NM03-INDEX-Operations.md      (Operational pathways and flows)
NM04-INDEX-Decisions.md       (Design decision routing)
NM05-INDEX-AntiPatterns.md    (What NOT to do - warnings)
NM06-INDEX-Learned.md         (Bugs and lessons learned routing) ✅ NEW
NM07-INDEX-DecisionTrees.md   (Decision logic trees)
```

### Implementation Layer - NM06: Learned Experiences (7 files) ✅ UPDATED
```
NM06-INDEX-Learned.md          (Interface router - 290 lines)    ✅ NEW
NM06-BUGS-Critical.md          (4 critical bugs - 450 lines)    ✅ NEW
NM06-LESSONS-Core.md           (8 core lessons - 580 lines)     ✅ NEW
NM06-LESSONS-Deployment.md     (2 deployment lessons - 420 lines) ✅ NEW
NM06-LESSONS-Documentation.md  (3 doc lessons - 380 lines)      ✅ NEW
NM06-LESSONS-2025.10.20.md     (3 recent lessons - 450 lines)   ✅ NEW
NM06-WISDOM-Synthesis.md       (5 synthesized insights - 320 lines) ✅ NEW
```

**Total NM06 Files:** 7 files (~2,890 lines total, all under 600-line limit)

---

## Section 1: Quick Lookup Tables

### Table A: All 12 Interfaces

| Interface | Purpose | Router File | Core File | Priority |
|-----------|---------|-------------|-----------|----------|
| CACHE | In-memory caching | interface_cache.py | cache_core.py | 🔴 CRITICAL |
| LOGGING | Structured logging | interface_logging.py | logging_core.py | 🔴 CRITICAL |
| SECURITY | Input validation | interface_security.py | security_core.py | 🔴 CRITICAL |
| METRICS | Performance tracking | interface_metrics.py | metrics_core.py | 🟡 HIGH |
| CONFIG | Configuration | interface_config.py | config_param_store.py | 🟡 HIGH |
| SINGLETON | Shared instances | interface_singleton.py | singleton_core.py | 🟡 HIGH |
| INITIALIZATION | Lazy loading | interface_initialization.py | initialization_core.py | 🟡 HIGH |
| HTTP_CLIENT | External HTTP | interface_http_client.py | http_client_core.py | 🟡 HIGH |
| WEBSOCKET | WebSocket connections | interface_websocket.py | websocket_core.py | 🟢 MEDIUM |
| CIRCUIT_BREAKER | Fault tolerance | interface_circuit_breaker.py | circuit_breaker_core.py | 🟢 MEDIUM |
| UTILITY | Helper functions | interface_utility.py | utility_core.py | 🟢 MEDIUM |
| DEBUG | Development tools | interface_debug.py | debug_core.py | ⚪ LOW |

### Table B: Import Rules Summary

| From | To | Method | Valid? |
|------|-----|--------|--------|
| Router | Gateway | `import gateway` | ✅ YES |
| Router | Core | Direct import | ✅ YES |
| Core | Gateway | `import gateway` | ✅ YES |
| Core | Core (same) | Direct import | ✅ YES |
| Core | Core (different) | Via gateway | ✅ YES |
| Core | Router | Never | ❌ NO |
| Router | Router | Via gateway | ✅ YES |

### Table C: Common Operations

| Operation | Go To | Quick Answer |
|-----------|-------|--------------|
| Cache data? | NM01-INT-01 | `gateway.cache_set(key, value, ttl)` |
| Log message? | NM01-INT-02 | `gateway.log_info(message)` |
| Validate input? | NM01-INT-03 | `gateway.validate_string(value)` |
| Track metric? | NM01-INT-04 | `gateway.track_metric(name, value)` |
| Get config? | NM01-INT-05 | `gateway.get_config(key)` |
| Get singleton? | NM01-INT-06 | `gateway.get_singleton(name)` |
| Initialize lazy? | NM01-INT-07 | `gateway.ensure_initialized(component)` |
| HTTP request? | NM01-INT-08 | `gateway.http_get(url, params)` |

### Table D: Dependency Layers

| Layer | Interfaces | Can Import From |
|-------|------------|-----------------|
| Layer 0 (Base) | LOGGING | Nothing (base layer) |
| Layer 1 (Core) | CACHE, SECURITY | LOGGING |
| Layer 2 (Utilities) | METRICS, CONFIG, SINGLETON, INITIALIZATION | Layers 0-1 |
| Layer 3 (External) | HTTP_CLIENT, WEBSOCKET | Layers 0-2 |
| Layer 4 (Advanced) | CIRCUIT_BREAKER, UTILITY, DEBUG | Layers 0-3 |

### Table E: File Access Methods

| Method | Priority | Use When | How |
|--------|----------|----------|-----|
| Project Knowledge Search | 🔴 PRIMARY | File may be uploaded | Use `project_knowledge_search` tool |
| GitHub Raw URL Fetch | 🟡 SECONDARY | File not in project | Ask user for raw URL, use `web_fetch` |
| User Upload | 🟢 FALLBACK | Neither works | Ask user to upload file |

---

## Section 2: Reference ID Directory

### NM00: Quick Index
**Triggers (TRIG):**
- NM00-TRIG-FILE: GitHub access, file reading
- NM00-TRIG-CACHE: Cache interface trigger
- NM00-TRIG-LOG: Logging interface trigger
- NM00-TRIG-HTTP: HTTP client trigger
- NM00-TRIG-IMPORT: Import rules trigger
- NM00-TRIG-ERROR: Error handling trigger
- NM00-TRIG-PERF: Performance trigger
- NM00-TRIG-CIRCULAR: Circular import trigger
- NM00-TRIG-GATEWAY: Gateway/SIMA trigger
- NM00-TRIG-INTERFACE: Interface/router trigger
- NM00-TRIG-HA: Home Assistant trigger

**Tables (TBL):**
- NM00-TBL-A: All 12 Interfaces
- NM00-TBL-B: Import Rules
- NM00-TBL-C: Common Operations
- NM00-TBL-D: Dependency Layers
- NM00-TBL-E: File Access Methods

### NM01: Core Architecture
**Architecture (ARCH):**
- NM01-ARCH-01: Gateway Trinity
- NM01-ARCH-02: Gateway execution engine
- NM01-ARCH-03: Router pattern
- NM01-ARCH-04: Internal implementation pattern
- NM01-ARCH-05: Extension architecture
- NM01-ARCH-06: Lambda entry point
- NM01-ARCH-07: LMMS (Lazy Module Management System)
- NM01-ARCH-08: Future/Experimental Architectures

**Interfaces (INT):**
- NM01-INT-01: CACHE interface
- NM01-INT-02: LOGGING interface
- NM01-INT-03: SECURITY interface
- NM01-INT-04: METRICS interface
- NM01-INT-05: CONFIG interface
- NM01-INT-06: SINGLETON interface
- NM01-INT-07: INITIALIZATION interface
- NM01-INT-08: HTTP_CLIENT interface
- NM01-INT-09: WEBSOCKET interface
- NM01-INT-10: CIRCUIT_BREAKER interface
- NM01-INT-11: UTILITY interface
- NM01-INT-12: DEBUG interface

### NM02: Interface Dependency Web
**Dependencies (DEP):**
- NM02-DEP-01: Layer 0 - Base (LOGGING)
- NM02-DEP-02: Layer 1 - Core Utilities
- NM02-DEP-03: Layer 2 - Services
- NM02-DEP-04: Layer 3 - External Communication
- NM02-DEP-05: Layer 4 - Advanced Features

**Rules (RULE):**
- NM02-RULE-01: Cross-interface via gateway only
- NM02-RULE-02: No circular dependencies
- NM02-RULE-03: Base layer has no dependencies

### NM03: Operational Pathways
**Pathways (PATH):**
- NM03-PATH-01: Cold start sequence
- NM03-PATH-02: Cache operation flow
- NM03-PATH-03: Logging pipeline
- NM03-PATH-04: Error propagation
- NM03-PATH-05: Metrics collection

### NM04: Design Decisions
**Decisions (DEC):**
- NM04-DEC-01: SIMA pattern choice
- NM04-DEC-02: Gateway centralization
- NM04-DEC-03: Flat file structure
- NM04-DEC-04: No threading locks
- NM04-DEC-05: Sentinel sanitization

### NM05: Anti-Patterns
**Anti-Patterns (AP):**
- NM05-AP-01: Direct cross-interface imports
- NM05-AP-02: Circular dependencies
- NM05-AP-03: Global state outside gateway
- NM05-AP-04: Threading primitives
- NM05-AP-05: Heavy external libraries

### NM06: Learned Experiences ✅ UPDATED
**Bugs (BUG):**
- NM06-BUG-01: Sentinel leak (535ms penalty) → NM06-BUGS-Critical.md
- NM06-BUG-02: _CacheMiss sentinel validation → NM06-BUGS-Critical.md
- NM06-BUG-03: Cascading interface failures → NM06-BUGS-Critical.md
- NM06-BUG-04: Configuration parameter mismatch → NM06-BUGS-Critical.md

**Lessons (LESS):**
- NM06-LESS-01: Gateway pattern prevents problems → NM06-LESSONS-Core.md
- NM06-LESS-02: Measure, don't guess → NM06-LESSONS-Core.md
- NM06-LESS-03: Infrastructure vs business logic → NM06-LESSONS-Core.md
- NM06-LESS-04: Consistency over cleverness → NM06-LESSONS-Core.md
- NM06-LESS-05: Graceful degradation → NM06-LESSONS-Core.md
- NM06-LESS-06: Pay small costs early → NM06-LESSONS-Core.md
- NM06-LESS-07: Base layers have no dependencies → NM06-LESSONS-Core.md
- NM06-LESS-08: Test failure paths → NM06-LESSONS-Core.md
- NM06-LESS-09: Partial deployment danger → NM06-LESSONS-Deployment.md
- NM06-LESS-10: Cold start monitoring → NM06-LESSONS-Deployment.md
- NM06-LESS-11: Design decisions must be documented → NM06-LESSONS-Documentation.md
- NM06-LESS-12: Code comments vs external docs → NM06-LESSONS-Documentation.md
- NM06-LESS-13: Architecture must be teachable → NM06-LESSONS-Documentation.md
- NM06-LESS-14: Evolution is normal → NM06-LESSONS-2025.10.20.md
- NM06-LESS-15: File verification is mandatory → NM06-LESSONS-2025.10.20.md
- NM06-LESS-16: Adaptation over rewriting → NM06-LESSONS-2025.10.20.md

**Wisdom Synthesis (WISD):**
- NM06-WISD-01: Architecture prevents problems → NM06-WISDOM-Synthesis.md
- NM06-WISD-02: Measure, don't guess → NM06-WISDOM-Synthesis.md
- NM06-WISD-03: Small costs early prevent large costs later → NM06-WISDOM-Synthesis.md
- NM06-WISD-04: Consistency over cleverness → NM06-WISDOM-Synthesis.md
- NM06-WISD-05: Document everything → NM06-WISDOM-Synthesis.md

### NM07: Decision Logic
**Decision Trees (DT):**
- NM07-DT-01: How to import X
- NM07-DT-02: Where function goes
- NM07-DT-03: User wants feature
- NM07-DT-04: Should I cache X
- NM07-DT-05: How handle error
- NM07-DT-06: What exception type
- NM07-DT-07: Should I optimize
- NM07-DT-08: What to test
- NM07-DT-09: How much to mock
- NM07-DT-10: Should I refactor
- NM07-DT-11: Extract or inline
- NM07-DT-12: Deploy this change
- NM07-DT-13: New interface or extend

---

## Section 3: Tag Cloud (Most Common Tags)

**Architecture:** SIMA, ISP, gateway, interface, router, core, topology, isolation
**Performance:** threading, locks, lazy-loading, dispatch-dict, O(1), optimization, memory
**Import:** cross-interface, intra-interface, circular-import, gateway-only, direct-import
**Lambda:** single-threaded, 128MB, cold-start, free-tier, CloudWatch
**Cache:** TTL, sentinel, sanitization, expiration, memory-management
**Error:** exception, propagation, logging, graceful-degradation, try-except
**Testing:** mock, unit-test, integration-test, TDD, assertions
**Security:** validation, sanitization, injection, secrets, SSRF
**Design:** YAGNI, DRY, single-responsibility, consistency

---

## Section 4: Cross-Reference Map

### Most Referenced Sections
1. **NM04-DEC-04** (No threading) → Referenced by: NM05-AP-08, NM06-LESS-04
2. **NM04-DEC-01** (SIMA pattern) → Referenced by: NM02-RULE-01, NM06-BUG-02, NM05-AP-01
3. **NM07-DT-01** (Import decision) → Referenced by: NM05-AP-01, NM02-RULE-01
4. **NM06-BUG-01** (Sentinel leak) → Referenced by: NM04-DEC-05, NM03-PATH-01
5. **NM06-LESS-01** (Gateway pattern) → Referenced by: NM01-ARCH-02, NM04-DEC-01

### Cross-Topic Relationships
```
Architecture (NM01) ←→ Dependencies (NM02)
Architecture (NM01) ←→ Decisions (NM04)
Dependencies (NM02) ←→ Anti-Patterns (NM05)
Decisions (NM04) ←→ Learned Experiences (NM06)
Operations (NM03) ←→ Learned Experiences (NM06)
Decision Trees (NM07) ←→ All Topics
```

---

## Section 5: Priority Breakdown

### 🔴 CRITICAL References (Learn These First)
```
NM01-ARCH-01: Gateway Trinity
NM01-ARCH-02: Gateway execution engine
NM02-RULE-01: Cross-interface via gateway only
NM04-DEC-01: SIMA pattern choice
NM04-DEC-04: No threading locks
NM06-BUG-01: Sentinel leak (535ms penalty)
NM06-BUG-02: _CacheMiss sentinel validation
NM06-BUG-03: Cascading interface failures
NM06-LESS-01: Gateway pattern prevents problems
NM06-LESS-15: File verification is mandatory
```

### 🟡 HIGH References (Reference Frequently)
```
NM01-INT-01 through NM01-INT-08: Core interfaces
NM02-DEP-01 through NM02-DEP-05: Dependency layers
NM03-PATH-01: Cold start sequence
NM04-DEC-02: Gateway centralization
NM04-DEC-03: Flat file structure
NM06-LESS-02: Measure, don't guess
NM06-LESS-03: Infrastructure vs business logic
NM06-LESS-09: Partial deployment danger
NM07-DT-01: How to import X
```

### 🟢 MEDIUM References (As Needed)
```
NM05-AP-01 through NM05-AP-05: Common anti-patterns
NM06-LESS-05: Graceful degradation
NM06-LESS-06: Pay small costs early
NM06-WISD-01 through NM06-WISD-05: Wisdom synthesis
NM07-DT-04 through NM07-DT-13: Decision trees
```

---

## Section 6: Usage Patterns

### Pattern 1: User Asks Architecture Question
```
1. Search NM00-Quick-Index.md for trigger keyword
2. Get instant context from AUTO-RECALL section
3. Reference detailed file if needed
4. Respond with informed context
```

### Pattern 2: User Asks "How to X"
```
1. Check NM00-Quick-Index.md Quick Lookup Tables
2. Check NM00-Quick-Index.md Decision Quick-Paths
3. Reference NM03 for operation flow
4. Provide complete answer with pathway
```

### Pattern 3: User Reports Error
```
1. Check NM00-Quick-Index.md error triggers
2. Reference NM03 error pathways
3. Check NM06 for similar past issues
4. Provide solution with context
```

### Pattern 4: User Suggests Approach
```
1. Check NM00-Quick-Index.md anti-pattern triggers
2. Reference NM05 anti-patterns
3. Reference NM04 design decisions
4. Explain why alternative is better
```

### Pattern 5: Architecture Question
```
1. Check NM00-Quick-Index.md for quick answer
2. Reference NM01 for architecture
3. Reference NM02 for relationships
4. Provide comprehensive explanation
```

### Pattern 6: File Access Request
```
1. Search project knowledge first (primary method)
2. If not found, ask user for GitHub raw URL
3. If URL provided, use web_fetch to retrieve
4. Analyze file and provide findings
5. Reference: Table E (File Access Methods)
```

### Pattern 7: Bug or Lesson Query ✅ NEW
```
1. Check NM00-Quick-Index.md for keyword trigger
2. Trigger routes to NM06-INDEX-Learned.md
3. NM06-INDEX routes to appropriate Implementation file:
   - Bugs → NM06-BUGS-Critical.md
   - Core lessons → NM06-LESSONS-Core.md
   - Deployment lessons → NM06-LESSONS-Deployment.md
   - Documentation lessons → NM06-LESSONS-Documentation.md
   - Recent lessons → NM06-LESSONS-2025.10.20.md
   - Wisdom synthesis → NM06-WISDOM-Synthesis.md
4. Read complete section with REF ID
5. Respond with full context
```

---

## Section 7: File Maintenance

### When to Update This File
- New interface added → Update Table A
- New common operation → Update Table C
- New trigger pattern identified → Update Section 1
- New decision pattern → Update Section 3
- New file access method → Update Table E
- **New Implementation files created → Update Section 2 Reference IDs** ✅
- **Files split/merged → Update File Inventory** ✅

### When to Create New Neural Map File
- Topic too large for one section (>300 lines)
- Distinct knowledge domain (e.g., performance, testing)
- Cross-cutting concern (e.g., security patterns)
- Specialized expertise (e.g., Home Assistant details)

### Integration Checklist for New Files
- [ ] Add to File Inventory section
- [ ] Add REF IDs to Reference ID Directory
- [ ] Add cross-references in related files
- [ ] Update version history
- [ ] Add search keywords to NM00-Quick-Index.md
- [ ] Document integration points
- [ ] Update Priority Breakdown if needed
- [ ] Add to Usage Patterns if new pattern

---

## Version History

**v2.0.0 (2025.10.20)** ✅ CURRENT
- MAJOR: Split NM06 into SIMA architecture (1 Interface + 6 Implementation files)
- Added NM06-INDEX-Learned.md (interface router)
- Added NM06-BUGS-Critical.md (4 bugs documented)
- Added NM06-LESSONS-Core.md (8 core lessons)
- Added NM06-LESSONS-Deployment.md (2 deployment lessons)
- Added NM06-LESSONS-Documentation.md (3 doc lessons)
- Added NM06-LESSONS-2025.10.20.md (3 recent lessons)
- Added NM06-WISDOM-Synthesis.md (5 synthesized insights)
- Updated Reference ID Directory for all NM06 content
- Added Pattern 7: Bug or Lesson Query routing
- Total: 7 new files, ~2,890 lines, all under 600-line limit

**v1.0.0 (2025.10.18)**
- Initial Master Index creation
- Established gateway/interface/implementation pattern
- All original neural maps cataloged

---

## Statistics

**Total Files:** 23+ files
- Gateway Layer: 2 files
- Interface Layer: 7 files
- Implementation Layer: 14+ files (varies by topic)

**NM06 Breakdown (✅ Complete):**
- 1 Interface Index (290 lines)
- 4 Bugs documented (in 1 file, 450 lines)
- 16 Lessons documented (in 4 files, ~1,830 lines)
- 5 Wisdom syntheses (in 1 file, 320 lines)

**Total References:** 100+ unique REF IDs
**Total Cross-References:** 50+ documented relationships

---

## END NOTES

This Master Index provides the complete catalog of all neural map files. It's designed to help you:
1. Find which file contains what information
2. Understand relationships between topics
3. Prioritize learning critical concepts
4. Navigate the knowledge base efficiently

For fast keyword lookup, use **NM00-Quick-Index.md**.

For deep dives, follow the routing pattern:
**Gateway (NM00) → Interface (NM##-INDEX) → Implementation (NM##-CATEGORY)**

**Remember:** This is synthetic working memory - knowledge + logic + relationships, not just facts.

**GitHub Access:** Claude can fetch files from GitHub when provided with raw URLs, but should always search project knowledge first.

---

# EOF

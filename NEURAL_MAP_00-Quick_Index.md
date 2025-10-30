# NEURAL_MAP_00: Quick Index
# SUGA-ISP Neural Memory System - Fast Lookup & Trigger System
# Version: 1.0.0 | Phase: 1 Foundation

---

## Purpose

This is Claude's "brain stem" - automatic recall triggers and fast lookup tables. When the user mentions keywords, this file provides instant context without deep search.

---

## How to Use This File

**For Claude:**
- Search trigger keywords when user mentions concepts
- Use quick lookup tables for instant answers
- Cross-reference to detailed neural map files

**For Developers:**
- Quick reference guide
- System overview at a glance
- Integration points for future neural map expansions

---

## SECTION 1: TRIGGER KEYWORDS → AUTO-RECALL

### Trigger: "file" or "read X.py" or "check source" or "GitHub"
```
AUTO-RECALL:
✓ Source files available on GitHub (read-only)
✓ Raw URL pattern: https://raw.githubusercontent.com/.../main/src/(filename).py
✓ All .py files in src/ directory
✓ User must provide exact URL (Claude cannot construct)
✓ Use web_fetch tool when user provides GitHub URL
✓ Priority: Search project knowledge FIRST, GitHub second

**URL PATTERN:**
https://raw.githubusercontent.com/dizzybeaver/Lambda-Execution-Engine-with-Home-Assistant-Support/main/src/(filename).py

**EXAMPLES:**
- cache_core.py: [base URL]/cache_core.py
- gateway.py: [base URL]/gateway.py
- interface_cache.py: [base URL]/interface_cache.py

**USAGE:**
When user says: "Check cache_core.py" or "Read the gateway file"
Response: "I can search project knowledge for cache_core.py, or if you provide the raw GitHub URL, I can fetch the current version using web_fetch."

**IMPORTANT:**
- Files also in project knowledge (search there first)
- GitHub gives most current version
- Use web_fetch tool for GitHub raw URLs
- Cannot construct URLs - user must provide exact URL
```

### Trigger: "cache" or "caching"
```
AUTO-RECALL:
✓ CACHE interface exists (GatewayInterface.CACHE)
✓ Router: interface_cache.py
✓ Core: cache_core.py
✓ Operations: get, set, exists, delete, clear, cleanup_expired, get_stats, get_metadata
✓ Wrappers: cache_get(), cache_set(), cache_exists(), cache_delete(), cache_clear()
✓ Dependencies: Uses LOGGING, METRICS
✓ Used by: HTTP_CLIENT, CONFIG, SECURITY
✓ Critical: Sentinel sanitization must happen at router layer
→ Refer to: NEURAL_MAP_01 (architecture), NEURAL_MAP_02 (dependencies)
```

### Trigger: "log" or "logging"
```
AUTO-RECALL:
✓ LOGGING interface exists (GatewayInterface.LOGGING)
✓ Router: interface_logging.py
✓ Core: logging_core.py
✓ Operations: log_info, log_error, log_warning, log_debug, log_operation_*
✓ Wrappers: log_info(), log_error(), log_warning(), log_debug()
✓ Dependencies: None (base infrastructure layer)
✓ Used by: Almost all interfaces
✓ Pattern: Uses print() for Lambda CloudWatch, not 'logging' module
→ Refer to: NEURAL_MAP_01 (architecture), NEURAL_MAP_02 (dependencies)
```

### Trigger: "http" or "request" or "API"
```
AUTO-RECALL:
✓ HTTP_CLIENT interface exists (GatewayInterface.HTTP_CLIENT)
✓ Router: interface_http.py
✓ Core: http_client_core.py
✓ Operations: request, get, post, put, delete, get_state, reset_state
✓ Wrappers: http_request(), http_get(), http_post(), http_put(), http_delete()
✓ Dependencies: Uses LOGGING, SECURITY, CACHE
✓ Pattern: Uses urllib (stdlib), NOT requests library
→ Refer to: NEURAL_MAP_01 (architecture), NEURAL_MAP_03 (pathways)
```

### Trigger: "import" or "importing"
```
AUTO-RECALL:
✓ Cross-interface: MUST use gateway
✓ Same-interface: CAN import directly
✓ Lambda/External: ONLY import from gateway
✓ Anti-pattern: Direct cross-interface imports
✓ Architecture: ISP network topology prevents circular imports
→ Refer to: NEURAL_MAP_02 (dependency rules), NEURAL_MAP_05 (anti-patterns)
```

### Trigger: "error" or "exception" or "crash"
```
AUTO-RECALL:
✓ Error path: internal → router → gateway_core → lambda_function
✓ Routers catch exceptions with try/except
✓ Routers use gateway.log_error() for logging
✓ Pattern: Return error dict, don't raise to caller
✓ Protection: Import error protection in all routers
→ Refer to: NEURAL_MAP_03 (error pathways), NEURAL_MAP_06 (learned experiences)
```

### Trigger: "performance" or "slow" or "memory"
```
AUTO-RECALL:
✓ Constraint: 128MB RAM limit (Lambda free tier)
✓ Pattern: Dispatch dictionaries (O(1)) vs if/elif (O(n))
✓ Pattern: Lazy loading (import only when needed)
✓ Pattern: Single-threaded (no threading overhead)
✓ Anti-pattern: Heavy libraries (pandas, numpy)
→ Refer to: NEURAL_MAP_04 (design decisions), NEURAL_MAP_07 (optimization)
```

### Trigger: "circular import" or "import error"
```
AUTO-RECALL:
✓ Solution: SUGA-ISP architecture prevents by design
✓ Rule: Cross-interface through gateway only
✓ Pattern: Uni-directional flow (external → gateway → router → internal)
✓ Prevention: Interface isolation (ISP topology)
→ Refer to: NEURAL_MAP_01 (architecture), NEURAL_MAP_06 (learned experiences)
```

### Trigger: "gateway" or "SUGA"
```
AUTO-RECALL:
✓ Core files: gateway.py, gateway_core.py, gateway_wrappers.py
✓ Pattern: Single Universal Gateway Architecture
✓ Registry: 12 interfaces mapped to routers
✓ Function: execute_operation(interface, operation, **kwargs)
✓ Purpose: Centralize infrastructure, prevent circular imports
→ Refer to: NEURAL_MAP_01 (complete architecture)
```

### Trigger: "interface" or "router"
```
AUTO-RECALL:
✓ Count: 12 interfaces total
✓ Pattern: interface_<name>.py files
✓ Function: execute_<name>_operation(operation, **kwargs)
✓ Role: Firewall between gateway and internal implementation
✓ Dispatch: Dictionary-based operation routing (O(1))
→ Refer to: NEURAL_MAP_01 (all interfaces), NEURAL_MAP_02 (relationships)
```

### Trigger: "Home Assistant" or "Alexa"
```
AUTO-RECALL:
✓ Extension: homeassistant_extension.py (facade)
✓ Pattern: Mini-ISP (same pattern as main gateway)
✓ Lambda imports: Only the extension facade
✓ Extension imports: gateway.py for infrastructure
✓ Internal: home_assistant/ directory (exception to flat structure)
→ Refer to: NEURAL_MAP_01 (extension architecture)
```

---

## SECTION 2: QUICK LOOKUP TABLES

### Table A: All 12 Interfaces
| Interface | Router File | Core File | Primary Purpose |
|-----------|-------------|-----------|-----------------|
| CACHE | interface_cache.py | cache_core.py | Data caching & storage |
| LOGGING | interface_logging.py | logging_core.py | System logging |
| SECURITY | interface_security.py | security_core.py | Validation & encryption |
| METRICS | interface_metrics.py | metrics_core.py | Telemetry & monitoring |
| CONFIG | interface_config.py | config_core.py | Configuration management |
| SINGLETON | interface_singleton.py | singleton_core.py | Singleton storage |
| INITIALIZATION | interface_initialization.py | initialization_core.py | System initialization |
| HTTP_CLIENT | interface_http.py | http_client_core.py | HTTP requests |
| WEBSOCKET | interface_websocket.py | websocket_core.py | WebSocket connections |
| CIRCUIT_BREAKER | interface_circuit_breaker.py | circuit_breaker_core.py | Failure protection |
| UTILITY | interface_utility.py | utility_core.py | Helper functions |
| DEBUG | interface_debug.py | debug_core.py | System diagnostics |

### Table B: Import Rules (Quick Reference)
| From File Type | To Access | Import Pattern | Example |
|----------------|-----------|----------------|---------|
| lambda_function.py | Any feature | `from gateway import X` | `from gateway import cache_get` |
| Extension facade | Any feature | `from gateway import X` | `from gateway import log_info` |
| Interface router | Same interface | Direct import | `from cache_core import impl` |
| Interface router | Other interface | `from gateway import X` | `from gateway import log_error` |
| Internal core | Same interface | Direct import | `from cache_manager import Mgr` |
| Internal core | Other interface | `from gateway import X` | `from gateway import http_post` |

### Table C: Common Operations → Functions
| What User Wants | Gateway Function | Interface | Notes |
|-----------------|------------------|-----------|-------|
| Store data temporarily | `cache_set(key, value)` | CACHE | Optional TTL parameter |
| Retrieve cached data | `cache_get(key)` | CACHE | Returns None if missing |
| Log information | `log_info(message)` | LOGGING | For general logging |
| Log error | `log_error(message, error)` | LOGGING | Includes exception |
| Make HTTP request | `http_post(url, data)` | HTTP_CLIENT | Also: http_get, http_put |
| Validate input | `validate_string(s)` | SECURITY | Also: validate_email, validate_url |
| Record metric | `record_metric(name, value)` | METRICS | For telemetry |
| Get config | `get_config(key)` | CONFIG | Returns config value |

### Table D: Dependency Layers (Bottom → Top)
| Layer | Interfaces | Dependencies | Used By |
|-------|-----------|--------------|---------|
| Base | LOGGING | None | Everyone |
| Infrastructure | CACHE, SECURITY, UTILITY | LOGGING | Most interfaces |
| Services | HTTP_CLIENT, WEBSOCKET | LOGGING, SECURITY, CACHE | Extensions |
| Monitoring | METRICS, CIRCUIT_BREAKER | LOGGING | Services |
| Management | CONFIG, SINGLETON, INIT | LOGGING, CACHE | System |
| Debug | DEBUG | All interfaces | Development |

### Table E: File Access Methods
| Access Method | Priority | When to Use | How to Use |
|---------------|----------|-------------|------------|
| Project Knowledge | 1st (Primary) | Always try first | `project_knowledge_search` with filename |
| GitHub Raw URL | 2nd (Backup) | When not in project knowledge | User provides URL, use `web_fetch` |
| User Upload | 3rd (Alternative) | When file not available | Ask user to upload to chat |

---

## SECTION 3: DECISION QUICK-PATHS

### User asks: "Add caching to X"
```
DECISION PATH:
1. Check: CACHE interface exists? → YES
2. Action: Use gateway.cache_set/cache_get
3. Response: "Use existing CACHE interface via gateway"
4. Anti-pattern: Don't implement custom caching
→ Refer to: NEURAL_MAP_05 (anti-patterns)
```

### User asks: "Add logging to X"
```
DECISION PATH:
1. Check: LOGGING interface exists? → YES
2. Action: Use gateway.log_info/log_error
3. Response: "Use existing LOGGING interface via gateway"
4. Anti-pattern: Don't use 'logging' module or print() directly
→ Refer to: NEURAL_MAP_05 (anti-patterns)
```

### User asks: "Make HTTP request"
```
DECISION PATH:
1. Check: HTTP_CLIENT interface exists? → YES
2. Check: stdlib only? → YES (urllib)
3. Action: Use gateway.http_get/http_post
4. Response: "Use gateway HTTP wrappers"
5. Anti-pattern: Don't import 'requests' library
→ Refer to: NEURAL_MAP_05 (anti-patterns)
```

### User asks: "Why not import X directly?"
```
DECISION PATH:
1. Check: Same interface? → Direct import OK
2. Check: Different interface? → Must use gateway
3. Reason: ISP architecture prevents circular imports
4. Response: Explain gateway requirement
→ Refer to: NEURAL_MAP_02 (dependency rules)
```

### User asks: "Check source file X"
```
DECISION PATH:
1. Search: project_knowledge_search for filename
2. If found: Read from project knowledge
3. If not found: Ask user for GitHub raw URL
4. If URL provided: Use web_fetch to retrieve
5. Response: Provide file analysis
→ Refer to: Table E (File Access Methods)
```

### User suggests: "Use threading/async"
```
DECISION PATH:
1. Check: Lambda environment? → Single-threaded
2. Check: Benefit? → None (no parallelism possible)
3. Response: "Lambda is single-threaded, no benefit"
4. Anti-pattern: Don't add threading.Lock or asyncio
→ Refer to: NEURAL_MAP_04 (design decisions)
```

### User suggests: "Use external library X"
```
DECISION PATH:
1. Check: Available in Lambda? → Usually NO
2. Check: stdlib alternative? → Usually YES
3. Check: 128MB constraint? → YES
4. Response: "Use stdlib alternative"
5. Exception: Home Assistant extension uses 'requests'
→ Refer to: NEURAL_MAP_04 (constraints)
```

---

## SECTION 4: CROSS-REFERENCE MAP

### Neural Map File Structure (Current + Future)
```
NEURAL_MAP_00_Quick_Index.md (THIS FILE)
├─ Purpose: Fast lookup, triggers, decision quick-paths
├─ Use: First stop for any query
└─ Extends: Foundation for all other neural map files

NEURAL_MAP_01_Core_Architecture.md
├─ Purpose: Gateway structure, 12 interfaces, router patterns
├─ Use: Understanding "what exists"
└─ Extends: Interface details, gateway internals

NEURAL_MAP_02_Interface_Dependency_Web.md
├─ Purpose: Who imports who, dependency chains
├─ Use: Understanding "how things connect"
└─ Extends: Circular import prevention, dependency rules

NEURAL_MAP_03_Operation_Pathways.md
├─ Purpose: Operation traces, error flows, data pathways
├─ Use: Understanding "how things flow"
└─ Extends: Error propagation, operation tracing

[FUTURE EXPANSIONS - Phase 2]
NEURAL_MAP_04_Design_Decisions.md
├─ Purpose: Why choices were made, rationale
├─ Use: Understanding "why it exists this way"
└─ Extends: Architectural principles, trade-offs

NEURAL_MAP_05_Anti_Patterns.md
├─ Purpose: What NOT to do, common mistakes
├─ Use: Preventing errors before they happen
└─ Extends: Bad patterns, violations, warnings

NEURAL_MAP_06_Learned_Experiences.md
├─ Purpose: Bugs fixed, lessons learned, discoveries
├─ Use: Learning from past mistakes
└─ Extends: Bug history, solutions, insights

NEURAL_MAP_07_Decision_Logic.md
├─ Purpose: When to do what, decision trees
├─ Use: Making architectural choices
└─ Extends: Complex decision patterns, trade-off analysis
```

### Cross-Reference Quick Guide
```
Looking for...                    → Check file...
- Fast keyword trigger            → NEURAL_MAP_00 (this file)
- What exists                     → NEURAL_MAP_01
- How things connect              → NEURAL_MAP_02  
- How operations flow             → NEURAL_MAP_03
- Why decisions made              → NEURAL_MAP_04 (Phase 2)
- What to avoid                   → NEURAL_MAP_05 (Phase 2)
- Past bugs/lessons               → NEURAL_MAP_06 (Phase 2)
- Complex decisions               → NEURAL_MAP_07 (Phase 2)
- File access methods             → NEURAL_MAP_00 Section 2, Table E
```

---

## SECTION 5: INTEGRATION POINTS (For Future Expansions)

### Extension Categories (To Be Added)
```
[DOMAIN KNOWLEDGE]
- Business logic patterns
- Home Assistant specifics
- Alexa skill patterns

[PERFORMANCE KNOWLEDGE]
- Optimization techniques
- Memory management patterns
- Cold start reduction strategies

[DEBUGGING KNOWLEDGE]
- Common error patterns
- Debug strategies
- Troubleshooting workflows

[TESTING KNOWLEDGE]
- Test patterns
- Mock strategies
- Validation approaches

[FILE ACCESS KNOWLEDGE]
- Project knowledge search patterns
- GitHub access workflows
- Source code analysis techniques
```

### Version History Tracking
```
Version 1.0.0 (Phase 1 - Foundation)
├─ NEURAL_MAP_00: Quick Index
├─ NEURAL_MAP_01: Core Architecture
├─ NEURAL_MAP_02: Interface Dependency Web
└─ NEURAL_MAP_03: Operation Pathways

Version 1.0.1 (Phase 1 - GitHub Access Update)
└─ NEURAL_MAP_00: Added GitHub access trigger and file access table

Version 2.0.0 (Phase 2 - Wisdom) [PLANNED]
├─ NEURAL_MAP_04: Design Decisions
├─ NEURAL_MAP_05: Anti-Patterns
├─ NEURAL_MAP_06: Learned Experiences
└─ NEURAL_MAP_07: Decision Logic

Version 3.0.0 (Phase 3 - Domain) [FUTURE]
├─ Domain-specific knowledge
├─ Advanced patterns
└─ Optimization strategies
```

---

## SECTION 6: USAGE PATTERNS FOR CLAUDE

### Pattern 1: User Mentions Keyword
```
1. Search NEURAL_MAP_00 for trigger keyword
2. Get instant context from AUTO-RECALL section
3. Reference detailed file if needed
4. Respond with informed context
```

### Pattern 2: User Asks "How to X"
```
1. Check NEURAL_MAP_00 Quick Lookup Tables
2. Check NEURAL_MAP_00 Decision Quick-Paths
3. Reference NEURAL_MAP_03 for operation flow
4. Provide complete answer with pathway
```

### Pattern 3: User Reports Error
```
1. Check NEURAL_MAP_00 error triggers
2. Reference NEURAL_MAP_03 error pathways
3. Check NEURAL_MAP_06 for similar past issues (Phase 2)
4. Provide solution with context
```

### Pattern 4: User Suggests Approach
```
1. Check NEURAL_MAP_00 anti-pattern triggers
2. Reference NEURAL_MAP_05 anti-patterns (Phase 2)
3. Reference NEURAL_MAP_04 design decisions (Phase 2)
4. Explain why alternative is better
```

### Pattern 5: Architecture Question
```
1. Check NEURAL_MAP_00 for quick answer
2. Reference NEURAL_MAP_01 for architecture
3. Reference NEURAL_MAP_02 for relationships
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

---

## SECTION 7: FILE MAINTENANCE

### When to Update This File
- New interface added → Update Table A
- New common operation → Update Table C
- New trigger pattern identified → Add to Section 1
- New decision pattern → Add to Section 3
- New file access method → Update Table E

### When to Create New Neural Map File
- Topic too large for one section (>300 lines)
- Distinct knowledge domain (e.g., performance, testing)
- Cross-cutting concern (e.g., security patterns)
- Specialized expertise (e.g., Home Assistant details)

### Integration Checklist for New Files
- [ ] Add to NEURAL_MAP_00 Cross-Reference Map
- [ ] Add cross-references in related files
- [ ] Update version history
- [ ] Add search keywords to triggers
- [ ] Document integration points

---

## END NOTES

This Quick Index provides instant context recall and fast lookup for common queries. It's designed to minimize search overhead and provide immediate answers to ~80% of questions.

For deeper understanding, refer to detailed neural map files listed in Section 4.

**Remember:** This is synthetic working memory - knowledge + logic + relationships, not just facts.

**GitHub Access:** Claude can fetch files from GitHub when provided with raw URLs, but should always search project knowledge first.

---

# EOF

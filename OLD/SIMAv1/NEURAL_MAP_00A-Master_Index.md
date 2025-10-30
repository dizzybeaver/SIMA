# NEURAL_MAP_00A: Master Index
# Quick Reference - Common Questions to Specific Sections
# Version: 1.0.0

---

## How to Use This Index

**For Claude:** Search this file first for common questions. Reference IDs point to exact sections.
**For Developers:** Quick lookup for architectural decisions and patterns.

---

## SECTION 1: Common Questions Index

### Architecture Questions

| Question | Reference | Quick Answer |
|----------|-----------|--------------|
| Why no threading locks? | NM04-DEC-04 | Lambda is single-threaded per container |
| Why SUGA pattern? | NM04-DEC-01 | Prevents circular imports, centralized control |
| Why ISP topology? | NM04-DEC-02 | Interface isolation, testability |
| Why dispatch dicts? | NM04-DEC-03 | O(1) vs O(n), 90% code reduction |
| Why flat structure? | NM04-DEC-06 | Historical, proven, simple imports |
| Why 128MB limit? | NM04-DEC-07 | Free tier constraint, forces efficiency |
| Why print() not logging? | NM04-DEC-08 | Lambda CloudWatch integration |
| Why sentinel objects? | NM04-DEC-05 | Distinguish None value from cache miss |
| Why lazy loading? | NM04-DEC-14 | Faster cold starts, lower memory |

### Import Questions

| Question | Reference | Quick Answer |
|----------|-----------|--------------|
| How do I import X? | NM07-DT-01 | Same interface=direct, cross=gateway |
| Can I import logging_core? | NM05-AP-01 | NO - use gateway.log_info |
| Can I import interface router? | NM05-AP-02 | NO - use gateway wrappers |
| Import from lambda_function? | NM05-AP-05 | NO - lambda_function is entry point |
| Gateway for same interface? | NM05-AP-03 | Unnecessary but not wrong |
| Why can't I import directly? | NM04-DEC-01, NM04-DEC-02 | Prevents circular imports |

### Feature Addition Questions

| Question | Reference | Quick Answer |
|----------|-----------|--------------|
| User wants feature X? | NM07-DT-03 | Check if exists → fits interface → new interface |
| Add to existing interface? | NM07-DT-03 | Yes if fits semantically |
| Create new interface? | NM07-DT-13 | Yes if >200 lines + state + domain-specific |
| Where does function go? | NM07-DT-02 | Interface-specific → core, Generic → utility |
| Should I make new interface? | NM07-DT-13 | >200 lines + state + specific domain = YES |

### Caching Questions

| Question | Reference | Quick Answer |
|----------|-----------|--------------|
| Should I cache X? | NM07-DT-04 | Expensive (>10ms) + frequent + stable = YES |
| What TTL should I use? | NM04-DEC-09 | Fast=60s, Medium=300s, Slow=600s |
| Can I make custom cache? | NM05-AP-06 | NO - use gateway.cache_* |
| Why use CACHE interface? | NM04-DEC-09 | TTL, expiration, memory management |
| Cache without TTL? | NM05-AP-12 | NO - causes memory leaks |

### Error Handling Questions

| Question | Reference | Quick Answer |
|----------|-----------|--------------|
| How handle this error? | NM07-DT-05 | Expected=handle gracefully, Unexpected=log+propagate |
| What exception to raise? | NM07-DT-06 | ValueError, KeyError, TypeError (be specific) |
| Should I use bare except? | NM05-AP-14 | NO - use except Exception as e |
| Where catch exceptions? | NM04-DEC-15 | Router layer with logging |
| Can I swallow errors? | NM05-AP-15 | Only if logged first |

### Performance Questions

| Question | Reference | Quick Answer |
|----------|-----------|--------------|
| if/elif vs dispatch dict? | NM04-DEC-03, NM06-LESS-01 | Dispatch dict is O(1) vs O(n), use always |
| Should I optimize X? | NM07-DT-07 | Measure first, only if >5% of time + hot path |
| Lazy loading benefit? | NM04-DEC-14, NM06-LESS-02 | ~60ms saved per cold start |
| Can I use threading? | NM05-AP-08 | NO - Lambda is single-threaded |
| Heavy libraries? | NM05-AP-09 | NO - 128MB constraint |

### Bug & Experience Questions

| Question | Reference | Quick Answer |
|----------|-----------|--------------|
| What was sentinel bug? | NM06-BUG-01 | Sentinel leak caused 535ms cold start penalty |
| Circular import issue? | NM06-BUG-02 | Fixed with SUGA-ISP architecture |
| Registry explosion? | NM06-BUG-03 | Fixed with pattern-based routing (100→12 entries) |
| Import error crashes? | NM06-BUG-04 | Fixed with try/except protection in routers |

### File Access Questions

| Question | Reference | Quick Answer |
|----------|-----------|--------------|
| How do I read source file? | NM00-TRIG-FILE | Check project knowledge first, or ask for GitHub URL |
| Where are source files? | NM00-TRIG-FILE | Project knowledge + GitHub (raw URLs) |
| Can Claude access GitHub? | NM00-TRIG-FILE | YES - via raw.githubusercontent.com URLs |
| What's the GitHub URL pattern? | NM00-TRIG-FILE | https://raw.githubusercontent.com/.../main/src/(filename).py |

---

## SECTION 2: Reference ID Directory

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
- NM00-TRIG-GATEWAY: Gateway/SUGA trigger
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
- NM02-DEP-03: Layer 2 - Storage & Monitoring
- NM02-DEP-04: Layer 3 - Service Infrastructure
- NM02-DEP-05: Layer 4 - Management & Debug

**Rules (RULE):**
- NM02-RULE-01: Cross-interface imports via gateway
- NM02-RULE-02: Intra-interface direct imports
- NM02-RULE-03: External code gateway only
- NM02-RULE-04: Flat file structure
- NM02-RULE-05: Lambda entry point restrictions

### NM03: Operation Pathways
**Flows (FLOW):**
- NM03-FLOW-01: Simple operation (cache_get)
- NM03-FLOW-02: Cross-interface operation (HTTP with logging)
- NM03-FLOW-03: Extension operation (Alexa request)

**Paths (PATH):**
- NM03-PATH-01: Implementation error propagation
- NM03-PATH-02: Cross-interface error cascade
- NM03-PATH-03: Router import error protection

### NM04: Design Decisions
**Core Decisions (DEC):**
- NM04-DEC-01: SUGA pattern (🔴 CRITICAL)
- NM04-DEC-02: ISP topology (🔴 CRITICAL)
- NM04-DEC-03: Dispatch dictionaries (🔴 CRITICAL)
- NM04-DEC-04: No threading locks (🔴 CRITICAL)
- NM04-DEC-05: Sentinel objects (🟡 HIGH)
- NM04-DEC-06: Flat structure (🟢 MEDIUM)
- NM04-DEC-07: 128MB limit (🔴 CRITICAL)
- NM04-DEC-08: print() not logging (🟡 HIGH)
- NM04-DEC-09: TTL-based expiration (🟡 HIGH)
- NM04-DEC-10: urllib not requests (🟡 HIGH)
- NM04-DEC-11: Validation-first (🟡 HIGH)
- NM04-DEC-12: Multi-tier config (🟢 MEDIUM)
- NM04-DEC-13: Fast path caching (🟢 MEDIUM)
- NM04-DEC-14: Lazy loading (🟡 HIGH)
- NM04-DEC-15: Router exception catching (🟡 HIGH)
- NM04-DEC-16: Import error protection (🟡 HIGH)
- NM04-DEC-17: HA as Mini-ISP (🟢 MEDIUM)
- NM04-DEC-18: Interface-level mocking (🟢 MEDIUM)
- NM04-DEC-19: Neural map system (🔴 CRITICAL)

### NM05: Anti-Patterns
**Import Anti-Patterns (AP):**
- NM05-AP-01: Direct cross-interface imports (🔴 CRITICAL)
- NM05-AP-02: Importing interface routers (🔴 CRITICAL)
- NM05-AP-03: Gateway for same-interface (⚪ LOW)
- NM05-AP-04: Circular imports via gateway (🔴 CRITICAL)
- NM05-AP-05: Importing from lambda_function (🔴 CRITICAL)

**Architecture Anti-Patterns (AP):**
- NM05-AP-06: Custom caching (🟡 HIGH)
- NM05-AP-07: Custom logging (🟡 HIGH)
- NM05-AP-08: Threading/Asyncio (🟡 HIGH)
- NM05-AP-09: Heavy libraries (🔴 CRITICAL)
- NM05-AP-10: Modifying failsafe (🔴 CRITICAL)

**Performance Anti-Patterns (AP):**
- NM05-AP-11: Synchronous network loops (🟢 MEDIUM)
- NM05-AP-12: Caching without TTL (🟡 HIGH)
- NM05-AP-13: String concatenation loops (⚪ LOW)

**Error Handling Anti-Patterns (AP):**
- NM05-AP-14: Bare except clauses (🟡 HIGH)
- NM05-AP-15: Swallowing exceptions (🟡 HIGH)
- NM05-AP-16: Generic exceptions (🟢 MEDIUM)

**Security Anti-Patterns (AP):**
- NM05-AP-17: No input validation (🔴 CRITICAL)
- NM05-AP-18: Hardcoded secrets (🔴 CRITICAL)
- NM05-AP-19: SQL injection patterns (🔴 CRITICAL)

**Code Organization Anti-Patterns (AP):**
- NM05-AP-20: God functions (🟡 HIGH)
- NM05-AP-21: Magic numbers (⚪ LOW)
- NM05-AP-22: Inconsistent naming (⚪ LOW)

**Testing Anti-Patterns (AP):**
- NM05-AP-23: No tests (🟡 HIGH)
- NM05-AP-24: Tests without assertions (🟡 HIGH)

**Documentation Anti-Patterns (AP):**
- NM05-AP-25: No docstrings (🟢 MEDIUM)
- NM05-AP-26: Outdated comments (🟢 MEDIUM)

**Deployment Anti-Patterns (AP):**
- NM05-AP-27: No version control (🔴 CRITICAL)
- NM05-AP-28: Deploying untested code (🔴 CRITICAL)

### NM06: Learned Experiences
**Bugs (BUG):**
- NM06-BUG-01: Sentinel leak (535ms penalty)
- NM06-BUG-02: Circular imports (pre-SUGA)
- NM06-BUG-03: Registry explosion (100+ entries)
- NM06-BUG-04: Import error crashes

**Lessons (LESS):**
- NM06-LESS-01: if/elif vs dispatch dict
- NM06-LESS-02: Lazy loading benefits
- NM06-LESS-03: Sentinel sanitization overhead
- NM06-LESS-04: Base layer dependencies

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

## SECTION 3: Tag Cloud (Most Common Tags)

**Architecture:** SUGA, ISP, gateway, interface, router, core, topology, isolation
**Performance:** threading, locks, lazy-loading, dispatch-dict, O(1), optimization, memory
**Import:** cross-interface, intra-interface, circular-import, gateway-only, direct-import
**Lambda:** single-threaded, 128MB, cold-start, free-tier, CloudWatch
**Cache:** TTL, sentinel, sanitization, expiration, memory-management
**Error:** exception, propagation, logging, graceful-degradation, try-except
**Testing:** mock, unit-test, integration-test, TDD, assertions
**Security:** validation, sanitization, injection, secrets, SSRF
**Design:** YAGNI, DRY, single-responsibility, consistency

---

## SECTION 4: Cross-Reference Map

### Most Referenced Sections
1. **NM04-DEC-04** (No threading) → Referenced by: NM05-AP-08, NM06-LESS-04
2. **NM04-DEC-01** (SUGA pattern) → Referenced by: NM02-RULE-01, NM06-BUG-02, NM05-AP-01
3. **NM07-DT-01** (Import decision) → Referenced by: NM05-AP-01, NM02-RULE-01
4. **NM06-BUG-01** (Sentinel leak) → Referenced by: NM04-DEC-05, NM03-PATH-01
5. **NM04-DEC-03** (Dispatch dicts) → Referenced by: NM06-LESS-01, NM07-DT-07

### Frequently Used Together
- **Import questions:** NM07-DT-01 + NM02-RULE-01 + NM05-AP-01
- **Caching decisions:** NM07-DT-04 + NM04-DEC-09 + NM05-AP-06
- **Error handling:** NM07-DT-05 + NM04-DEC-15 + NM05-AP-14
- **Architecture understanding:** NM04-DEC-01 + NM04-DEC-02 + NM01-ARCH-01

---

## SECTION 5: Error Resolution Quick Paths

### "ImportError: cannot import name X"
**Quick Check:** NM05-AP-01 (Cross-interface imports)
**Solution Path:** NM07-DT-01 → Use gateway import
**Related:** NM06-BUG-02 (Circular import history)
**Fix:** Replace `from X_core import Y` with `from gateway import Y`

### "Cache returning weird values"
**Quick Check:** NM06-BUG-01 (Sentinel leak)
**Solution Path:** NM04-DEC-05 → Sanitization at router
**Related:** NM03-PATH-01 (Cache flow)
**Fix:** Ensure sentinel sanitization in interface_cache.py

### "Lambda cold start slow"
**Quick Check:** NM06-LESS-02 (Lazy loading)
**Solution Path:** NM07-DT-07 → Optimization decision tree
**Related:** NM04-DEC-07 (Performance constraints)
**Fix:** Enable lazy loading, check for unnecessary imports

### "Circular import detected"
**Quick Check:** NM06-BUG-02 (Pre-SUGA circular imports)
**Solution Path:** NM04-DEC-01 → SUGA pattern
**Related:** NM05-AP-01, NM05-AP-04
**Fix:** Use gateway for all cross-interface imports

### "Function too slow"
**Quick Check:** NM06-LESS-01 (if/elif vs dispatch)
**Solution Path:** NM07-DT-07 → Should I optimize
**Related:** NM04-DEC-03 (Dispatch dictionaries)
**Fix:** Replace if/elif chains with dispatch dicts

---

## SECTION 6: Visual Quick Reference

### Dependency Layers (Bottom → Top)
```
     ┌─────────────┐
     │    DEBUG    │ Layer 4: Tests everything
     └──────┬──────┘
            │
   ┌────────┼────────┐
   │        │        │
┌──┴───┐ ┌──┴────┐ ┌─┴──────┐
│ INIT │ │ HTTP  │ │WEBSOCKET│ Layer 3: Services
└──┬───┘ │CLIENT │ └─┬──────┘
   │     └──┬────┘   │
   │        │        │
   └───┬────┴────┬───┘
       │         │
   ┌───┴───┐ ┌──┴─────┐
   │CONFIG │ │ CACHE  │ Layer 2: Storage
   └───┬───┘ └──┬─────┘
       │        │
       └───┬────┘
           │
     ┌─────┴──────┐
     │  LOGGING   │ Layer 0: Base (no dependencies)
     └────────────┘
```

### Import Decision Flow
```
Need to use X
    │
    ├─ Same interface? → Direct import ✓
    │
    └─ Different interface? → gateway import ✓
```

### Error Propagation Path
```
Error Occurs
    ↓
Core Implementation (raises)
    ↓
Router (catches, logs, re-raises)
    ↓
Gateway Core (may wrap)
    ↓
User Code (handles)
```

---

## SECTION 7: Code Pattern Quick Reference

**Router Pattern:**
→ See: interface_cache.py (REF: NM01-INT-01)
→ Template: NM01-ARCH-03

**Core Implementation Pattern:**
→ See: cache_core.py (REF: NM01-INT-01)
→ Template: NM01-ARCH-04

**Gateway Wrapper Pattern:**
→ See: gateway_wrappers.py:cache_get()
→ Template: NM01-ARCH-01

**Error Handling Pattern:**
→ See: interface_cache.py:execute_cache_operation()
→ Decision Tree: NM07-DT-05

**Dispatch Dictionary Pattern:**
```python
_OPERATION_DISPATCH = {
    'get': _execute_get_implementation,
    'set': _execute_set_implementation,
}

def execute_operation(operation, **kwargs):
    handler = _OPERATION_DISPATCH[operation]
    return handler(**kwargs)
```

---

## SECTION 8: SUGA-ISP Rules Quick Reference

**RULE 1: PROJECT KNOWLEDGE SEARCH**
Search 4 ways before claiming file doesn't exist:
1. Exact filename
2. Partial name
3. Module/interface name
4. Key function names

**RULE 2: USE EXISTING GATEWAY FUNCTIONS**
Gateway functions already available:
- IDs & Time: generate_uuid(), get_timestamp()
- Logging: log_info(), log_error()
- Caching: cache_get(), cache_set()
- Security: validate_request(), sanitize_input()
- Utilities: parse_json(), format_response()

**RULE 3: CHECK DESIGN DECISIONS**
Before flagging as bug, check if documented:
- File level: "Design Decisions:" section
- Function level: "DESIGN DECISION:" comment
- Neural maps: NM04 Design Decisions

**RULE 4: OUTPUT FORMAT**
✓ Code → ALWAYS in artifact
✓ Complete file with # EOF
✓ Line count within ±20 of original

---

## SECTION 9: Priority-Based Learning Path

### 🔴 CRITICAL - Learn First
1. **NM04-DEC-01**: SUGA pattern (prevents circular imports)
2. **NM04-DEC-02**: ISP topology (interface isolation)
3. **NM04-DEC-04**: No threading locks (Lambda single-threaded)
4. **NM04-DEC-07**: 128MB limit (free tier constraint)
5. **NM05-AP-01**: No direct cross-interface imports
6. **NM07-DT-01**: How to import X (decision tree)

### 🟡 HIGH - Learn Second
1. **NM04-DEC-03**: Dispatch dictionaries (O(1) routing)
2. **NM04-DEC-14**: Lazy loading (cold start optimization)
3. **NM05-AP-06**: No custom caching
4. **NM05-AP-14**: No bare except clauses
5. **NM06-BUG-01**: Sentinel leak lesson

### 🟢 MEDIUM - Learn As Needed
1. **NM04-DEC-06**: Flat structure rationale
2. **NM04-DEC-12**: Multi-tier configuration
3. **NM07-DT-04**: Should I cache X
4. **NM07-DT-07**: Should I optimize

---

## SECTION 10: Common User Query → Neural Map Paths

```
"Why can't I import X directly?"
→ NM05-AP-01 → NM04-DEC-01 → NM07-DT-01

"How do I add caching?"
→ NM07-DT-04 → NM04-DEC-09 → NM05-AP-06

"Should I use threading?"
→ NM04-DEC-04 → NM05-AP-08 → Answer: NO

"What's the SUGA pattern?"
→ NM04-DEC-01 → NM04-DEC-02 → NM01-ARCH-01

"My imports are circular"
→ NM06-BUG-02 → NM04-DEC-01 → NM05-AP-01 → Solution

"How do I handle errors?"
→ NM07-DT-05 → NM04-DEC-15 → NM05-AP-14

"Can I use pandas?"
→ NM05-AP-09 → NM04-DEC-07 → Answer: NO (128MB)

"Where should my function go?"
→ NM07-DT-02 → Check interface fit → Decide

"How do I access source files?"
→ NM00-TRIG-FILE → Project knowledge first → GitHub URL second
```

---

## END NOTES

This Master Index provides instant navigation to any topic in the neural map system. Use REF IDs to jump directly to specific sections.

**Search Strategy:**
1. Check this file for common question
2. Use REF ID to jump to detailed section
3. Follow RELATED links for deeper understanding

**Priority System:**
- 🔴 CRITICAL = Must know to work on project
- 🟡 HIGH = Important for common tasks
- 🟢 MEDIUM = Good to know
- ⚪ LOW = Nice to have

---

# EOF

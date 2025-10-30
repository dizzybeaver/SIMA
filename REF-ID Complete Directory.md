# REF-ID Complete Directory
# Alphabetical Quick Lookup - All 159+ References
# Version: 1.0.0 | Created: 2025-10-21
# Purpose: Instant lookup of any REF-ID to exact file location

---

## Purpose

When you see cross-references like "Related: NM06-BUG-01, NM04-DEC-05", use this directory to instantly find the exact file and section without searching.

**Time saved:** 10-20 seconds per cross-reference lookup

---

## A

**ARCH-01:** Gateway Trinity → NM01-CORE-Architecture.md  
**ARCH-02:** Gateway execution engine → NM01-CORE-Architecture.md  
**ARCH-03:** Router pattern → NM01-CORE-Architecture.md  
**ARCH-04:** Internal implementation pattern → NM01-CORE-Architecture.md  
**ARCH-05:** Extension architecture → NM01-CORE-Architecture.md  
**ARCH-06:** Lambda entry point → NM01-CORE-Architecture.md  

**AP-01:** Direct cross-interface imports → NM05-Anti-Patterns-Part-1.md  
**AP-02:** Circular dependencies → NM05-Anti-Patterns-Part-1.md  
**AP-03:** Global state outside gateway → NM05-Anti-Patterns-Part-1.md  
**AP-04:** Threading primitives → NM05-Anti-Patterns-Part-1.md  
**AP-05:** Heavy external libraries → NM05-Anti-Patterns-Part-1.md  
**AP-06:** Custom caching implementations → NM05-Anti-Patterns-Part-1.md  
**AP-07:** Direct SSM access → NM05-Anti-Patterns-Part-1.md  
**AP-08:** Threading locks → NM05-Anti-Patterns-Part-1.md  
**AP-09:** Silent failures → NM05-Anti-Patterns-Part-1.md  
**AP-10:** Mixed abstraction levels → NM05-Anti-Patterns-Part-1.md  
**AP-11:** Premature optimization → NM05-Anti-Patterns-Part-1.md  
**AP-12:** Magic numbers → NM05-Anti-Patterns-Part-1.md  
**AP-13:** Copy-paste code → NM05-Anti-Patterns-Part-1.md  
**AP-14:** Bare except clauses → NM05-Anti-Patterns-Part-1.md  

**AP-15:** Swallowing exceptions → NM05-Anti-Patterns-Part-2.md  
**AP-16:** Long functions → NM05-Anti-Patterns-Part-2.md  
**AP-17:** No input validation → NM05-Anti-Patterns-Part-2.md  
**AP-18:** String concatenation for SQL → NM05-Anti-Patterns-Part-2.md  
**AP-19:** SQL injection vulnerabilities → NM05-Anti-Patterns-Part-2.md  
**AP-20:** Hardcoded credentials → NM05-Anti-Patterns-Part-2.md  
**AP-21:** Logging sensitive data → NM05-Anti-Patterns-Part-2.md  
**AP-22:** No request timeouts → NM05-Anti-Patterns-Part-2.md  
**AP-23:** Unbounded retries → NM05-Anti-Patterns-Part-2.md  
**AP-24:** No error context → NM05-Anti-Patterns-Part-2.md  
**AP-25:** Undocumented decisions → NM05-Anti-Patterns-Part-2.md  
**AP-26:** Stale comments → NM05-Anti-Patterns-Part-2.md  
**AP-27:** Testing only success paths → NM05-Anti-Patterns-Part-2.md  
**AP-28:** Partial deployments → NM05-Anti-Patterns-Part-2.md  

---

## B

**BUG-01:** Sentinel leak (535ms penalty) → NM06-BUGS-Critical.md  
**BUG-02:** _CacheMiss sentinel validation → NM06-BUGS-Critical.md  
**BUG-03:** Cascading interface failures → NM06-BUGS-Critical.md  
**BUG-04:** Configuration parameter mismatch → NM06-BUGS-Critical.md  

---

## D

**DEC-01:** SIMA pattern choice → NM04-ARCHITECTURE-Decisions.md  
**DEC-02:** Gateway centralization → NM04-ARCHITECTURE-Decisions.md  
**DEC-03:** Dispatch dictionary pattern → NM04-ARCHITECTURE-Decisions.md  
**DEC-04:** No threading locks → NM04-TECHNICAL-Decisions.md  
**DEC-05:** Sentinel sanitization → NM04-ARCHITECTURE-Decisions.md  
**DEC-06:** Flat file structure → NM04-TECHNICAL-Decisions.md  
**DEC-07:** Fast path optimization → NM04-TECHNICAL-Decisions.md  
**DEC-08:** Print vs logging module → NM04-TECHNICAL-Decisions.md  
**DEC-09:** Stdlib only policy → NM04-TECHNICAL-Decisions.md  
**DEC-10:** Interface-level mocking → NM04-TECHNICAL-Decisions.md  
**DEC-11:** Extension pattern → NM04-TECHNICAL-Decisions.md  
**DEC-12:** 128MB memory limit → NM04-TECHNICAL-Decisions.md  
**DEC-13:** Fast path caching → NM04-TECHNICAL-Decisions.md  
**DEC-14:** Lazy module loading → NM04-TECHNICAL-Decisions.md  
**DEC-15:** Router-level exceptions → NM04-TECHNICAL-Decisions.md  
**DEC-16:** Import error protection → NM04-TECHNICAL-Decisions.md  

**DEC-17:** Environment-first config → NM04-OPERATIONAL-Decisions.md  
**DEC-18:** Multi-tier configuration → NM04-OPERATIONAL-Decisions.md  
**DEC-19:** Neural map documentation → NM04-OPERATIONAL-Decisions.md  
**DEC-20:** Free tier services only → NM04-OPERATIONAL-Decisions.md  
**DEC-21:** SSM token-only (92% faster) → NM04-OPERATIONAL-Decisions.md  
**DEC-22:** DEBUG_MODE flow visibility → NM04-OPERATIONAL-Decisions.md  
**DEC-23:** DEBUG_TIMINGS performance → NM04-OPERATIONAL-Decisions.md  

**DEP-01:** Layer 0 - Base (LOGGING) → NM02-CORE-Dependencies.md  
**DEP-02:** Layer 1 - Core Utilities → NM02-CORE-Dependencies.md  
**DEP-03:** Layer 2 - Storage & Monitoring → NM02-CORE-Dependencies.md  
**DEP-04:** Layer 3 - Service Infrastructure → NM02-CORE-Dependencies.md  
**DEP-05:** Layer 4 - Management & Debug → NM02-CORE-Dependencies.md  

**DIAGRAM-01:** ASCII dependency graph → NM02-RULES-Import.md  

**DT-01:** How to import X → NM07-Decision-Logic-Part-1.md  
**DT-02:** Where to put code → NM07-Decision-Logic-Part-1.md  
**DT-03:** Feature addition decision → NM07-Decision-Logic-Part-1.md  
**DT-04:** Should I cache this → NM07-Decision-Logic-Part-1.md  
**DT-05:** How to handle errors → NM07-Decision-Logic-Part-1.md  
**DT-06:** What exception type → NM07-Decision-Logic-Part-1.md  
**DT-07:** Should I optimize → NM07-Decision-Logic-Part-1.md  

**DT-08:** What to test → NM07-Decision-Logic-Part-2.md  
**DT-09:** Lambda vs extension code → NM07-Decision-Logic-Part-2.md  
**DT-10:** Should I refactor → NM07-Decision-Logic-Part-2.md  
**DT-11:** Extract or inline → NM07-Decision-Logic-Part-2.md  
**DT-12:** Deploy strategy → NM07-Decision-Logic-Part-2.md  
**DT-13:** Add new interface → NM07-Decision-Logic-Part-2.md  

---

## E

**ERROR-01:** Exception handling patterns → NM03-ERROR-Handling.md  
**ERROR-02:** Graceful degradation → NM03-ERROR-Handling.md  
**ERROR-03:** Error logging → NM03-ERROR-Handling.md  

---

## F

**FLOW-01:** Simple operation (cache_get) → NM03-CORE-Pathways.md  
**FLOW-02:** Complex operation (HTTP request) → NM03-CORE-Pathways.md  
**FLOW-03:** Cascading operations → NM03-CORE-Pathways.md  

**FW-01:** Cache vs compute framework → NM07-Decision-Logic-Part-2.md  
**FW-02:** Optimize vs document framework → NM07-Decision-Logic-Part-2.md  

---

## I

**INT-01:** CACHE interface → NM01-INTERFACES-Core.md  
**INT-02:** LOGGING interface → NM01-INTERFACES-Core.md  
**INT-03:** SECURITY interface → NM01-INTERFACES-Core.md  
**INT-04:** METRICS interface → NM01-INTERFACES-Core.md  
**INT-05:** CONFIG interface → NM01-INTERFACES-Core.md  
**INT-06:** SINGLETON interface → NM01-INTERFACES-Core.md  
**INT-07:** INITIALIZATION interface → NM01-INTERFACES-Core.md  
**INT-08:** HTTP_CLIENT interface → NM01-INTERFACES-Core.md  

**INT-09:** WEBSOCKET interface → NM01-INTERFACES-Advanced.md  
**INT-10:** CIRCUIT_BREAKER interface → NM01-INTERFACES-Advanced.md  
**INT-11:** UTILITY interface → NM01-INTERFACES-Advanced.md  
**INT-12:** DEBUG interface → NM01-INTERFACES-Advanced.md  

---

## L

**LESS-01:** Gateway pattern prevents problems → NM06-LESSONS-Core.md  
**LESS-02:** Measure, don't guess → NM06-LESSONS-Core.md  
**LESS-03:** Infrastructure vs business logic → NM06-LESSONS-Core.md  
**LESS-04:** Consistency over cleverness → NM06-LESSONS-Core.md  
**LESS-05:** Graceful degradation → NM06-LESSONS-Core.md  
**LESS-06:** Pay small costs early → NM06-LESSONS-Core.md  
**LESS-07:** Base layers have no dependencies → NM06-LESSONS-Core.md  
**LESS-08:** Test failure paths → NM06-LESSONS-Core.md  

**LESS-09:** Partial deployment danger → NM06-LESSONS-Deployment.md  
**LESS-10:** Cold start monitoring → NM06-LESSONS-Deployment.md  

**LESS-11:** Design decisions must be documented → NM06-LESSONS-Documentation.md  
**LESS-12:** Code comments vs external docs → NM06-LESSONS-Documentation.md  
**LESS-13:** Architecture must be teachable → NM06-LESSONS-Documentation.md  

**LESS-14:** Evolution is normal → NM06-LESSONS-2025.10.20.md  
**LESS-15:** File verification mandatory → NM06-LESSONS-2025.10.20.md  
**LESS-16:** Adaptation over rewriting → NM06-LESSONS-2025.10.20.md  

---

## M

**MATRIX-01:** Forward dependency matrix → NM02-RULES-Import.md  
**MATRIX-02:** Inverse dependency matrix → NM02-RULES-Import.md  

**MECHANISM-01:** Gateway mediation mechanism → NM02-RULES-Import.md  

**META-01:** Meta-decision framework → NM07-Decision-Logic-Part-2.md  

---

## P

**PATH-01:** Cold start sequence → NM03-CORE-Pathways.md  
**PATH-02:** Cache operation flow → NM03-CORE-Pathways.md  
**PATH-03:** Logging pipeline → NM03-CORE-Pathways.md  
**PATH-04:** Error propagation → NM03-ERROR-Handling.md  
**PATH-05:** Metrics collection → NM03-CORE-Pathways.md  

**PREVENT-01:** Gateway prevents circular imports → NM02-RULES-Import.md  

---

## R

**RULE-01:** Cross-interface via gateway only → NM02-RULES-Import.md  
**RULE-02:** Intra-interface direct imports OK → NM02-RULES-Import.md  
**RULE-03:** External code imports gateway only → NM02-RULES-Import.md  
**RULE-04:** Flat file structure → NM02-RULES-Import.md  
**RULE-05:** Lambda entry point restrictions → NM02-RULES-Import.md  

---

## T

**TRACE-01:** Request trace example → NM03-ERROR-Handling.md  
**TRACE-02:** Data transformation pipeline → NM03-CORE-Pathways.md  

---

## V

**VALIDATION-01:** Adding new dependency checklist → NM02-RULES-Import.md  
**VALIDATION-02:** Checking for circular dependencies → NM02-RULES-Import.md  
**VALIDATION-03:** Red flags and warnings → NM02-RULES-Import.md  

---

## W

**WISD-01:** Architecture prevents problems → NM06-WISDOM-Synthesis.md  
**WISD-02:** Measure don't guess → NM06-WISDOM-Synthesis.md  
**WISD-03:** Small costs early prevent large costs later → NM06-WISDOM-Synthesis.md  
**WISD-04:** Consistency over cleverness → NM06-WISDOM-Synthesis.md  
**WISD-05:** Document everything → NM06-WISDOM-Synthesis.md  

---

## Quick Stats

**Total REF-IDs:** 159  
**Most Referenced:**
- BUG-01 (Sentinel leak) - Referenced by 10+ sections
- DEC-01 (SIMA pattern) - Referenced by 8+ sections
- RULE-01 (Cross-interface via gateway) - Referenced by 7+ sections
- DEC-04 (No threading) - Referenced by 5+ sections

**By Category:**
- Architecture (ARCH): 6
- Anti-Patterns (AP): 28
- Bugs (BUG): 4
- Decisions (DEC): 23
- Dependencies (DEP): 5
- Decision Trees (DT): 13
- Errors (ERROR): 3
- Flows (FLOW): 3
- Frameworks (FW): 2
- Interfaces (INT): 12
- Lessons (LESS): 16
- Matrices (MATRIX): 2
- Pathways (PATH): 5
- Rules (RULE): 5
- Traces (TRACE): 2
- Validations (VALIDATION): 3
- Wisdom (WISD): 5

---

## Usage Tips

### When You See Cross-References
```
"Related: NM06-BUG-01, NM04-DEC-05"
↓
Look up in this directory:
- BUG-01 → NM06-BUGS-Critical.md
- DEC-05 → NM04-ARCHITECTURE-Decisions.md
↓
Search those specific files (save 15-20 seconds)
```

### When User Cites a REF-ID
```
User: "What's DEC-21 about?"
↓
Look up: DEC-21 → NM04-OPERATIONAL-Decisions.md
↓
Search and read that section immediately
```

### When Building Response
```
Include REF-IDs in response:
"This is documented in DEC-04 (No threading locks)..."
↓
User can then look up the exact section if they want details
```

---

## Maintenance

**When to Update:**
- New REF-IDs added to neural maps
- Files reorganized or renamed
- New categories of references created

**Update frequency:** Every 1-2 months or when 5+ new REF-IDs added

---

# EOF

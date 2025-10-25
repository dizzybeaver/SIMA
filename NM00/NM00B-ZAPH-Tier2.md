# NM00B-ZAPH-Tier2.md

# ZAPH Tier 2 - High Priority

**Tier:** High (20-49 accesses/30 days)  
**Items:** 30  
**Format:** One-line summary + file pointer  
**Last Updated:** 2025-10-24  
**Version:** 3.0.0 (SIMA v3 Compliant)

---

## 📋 TIER 2 ITEMS

### LESS-03: Infrastructure vs Business Logic
**File:** `NM06/NM06-Lessons-CoreArchitecture_LESS-03.md`  
**Accesses:** 28  
**Summary:** Keep infrastructure (cache, logging) separate from business logic for easier testing and changes.

---

### BUG-03: Cascading Interface Failures
**File:** `NM06/NM06-Bugs-Critical_BUG-03.md`  
**Accesses:** 27  
**Summary:** When one interface fails, others cascade. Need circuit breakers and graceful degradation.

---

### LESS-09: Partial Deployment Danger
**File:** `NM06/NM06-Lessons-Operations_LESS-09.md`  
**Accesses:** 26  
**Summary:** Never deploy partially. Lambda requires all-or-nothing. Missing files cause runtime failures.

---

### INT-02: LOGGING Interface
**File:** `NM01/NM01-Interfaces-Core_INT-02.md`  
**Accesses:** 25  
**Summary:** Structured logging with levels (info, warning, error, critical, debug). Central logging through gateway.

---

### INT-03: SECURITY Interface
**File:** `NM01/NM01-Interfaces-Core_INT-03.md`  
**Accesses:** 24  
**Summary:** Input validation, sanitization, sentinel checks. Security operations centralized.

---

### WISD-01: Architecture Prevents Problems
**File:** `NM06/NM06-Wisdom-Synthesized_WISD-01.md`  
**Accesses:** 23  
**Summary:** Good architecture prevents problems before they happen. SUGA prevents circular imports, LMMS prevents memory issues.

---

### WISD-02: Measure Don't Guess (Synthesis)
**File:** `NM06/NM06-Wisdom-Synthesized_WISD-02.md`  
**Accesses:** 22  
**Summary:** Measurement led to LMMS (60% improvement), sentinel leak fix (535ms saved), threading removal (15ms saved).

---

### DEC-02: Gateway Centralization
**File:** `NM04/NM04-Decisions-Architecture_DEC-02.md`  
**Accesses:** 22  
**Summary:** All operations through gateway provides: logging, metrics, error handling, security in one place.

---

### DEC-03: Interface Segregation
**File:** `NM04/NM04-Decisions-Architecture_DEC-03.md`  
**Accesses:** 21  
**Summary:** Each interface has focused purpose. CACHE for caching, LOGGING for logging, etc. ISP principle.

---

### ARCH-02: Gateway Execution Engine
**File:** `NM01/NM01-Architecture-CoreArchitecture_ARCH-02.md`  
**Accesses:** 21  
**Summary:** The `execute_operation(interface, operation, params)` pattern. Central dispatch mechanism.

---

### ARCH-03: Router Pattern
**File:** `NM01/NM01-Architecture-CoreArchitecture_ARCH-03.md`  
**Accesses:** 20  
**Summary:** Interface routers use `_OPERATION_DISPATCH` dictionaries for fast O(1) routing.

---

### DEP-01: Layer 0 - Base
**File:** `NM02/NM02-Dependencies-Layers_DEP-01.md`  
**Accesses:** 20  
**Summary:** LOGGING is Layer 0 - no dependencies. Foundation for all other interfaces.

---

### DT-03: Feature Addition Decision
**File:** `NM07/NM07-DecisionLogic-FeatureAddition_DT-03.md`  
**Accesses:** 20  
**Summary:** Decision tree for adding new features. Check gateway → interface → implementation pattern.

---

### DT-05: How to Handle Errors
**File:** `NM07/NM07-DecisionLogic-ErrorHandling_DT-05.md`  
**Accesses:** 19  
**Summary:** Decision tree for error handling. Specific exceptions, graceful degradation, proper logging.

---

### ERROR-01: Exception Handling Patterns
**File:** `NM03/NM03-Operations-ErrorHandling_ERROR-01.md`  
**Accesses:** 19  
**Summary:** Standard exception handling patterns. Catch specific, log with context, recover gracefully.

---

### LESS-10: Cold Start Monitoring
**File:** `NM06/NM06-Lessons-Operations_LESS-10.md`  
**Accesses:** 18  
**Summary:** Monitor cold start times. LMMS reduced from 850ms to 320ms. Track regressions.

---

### DT-01: How to Import X
**File:** `NM07/NM07-DecisionLogic-Import_DT-01.md`  
**Accesses:** 18  
**Summary:** Decision tree for imports. Always gateway, never direct, check dependency layers.

---

### INT-04: METRICS Interface
**File:** `NM01/NM01-Interfaces-Core_INT-04.md`  
**Accesses:** 18  
**Summary:** Performance tracking, operation timing, counters, statistics. Silent failures OK.

---

### INT-05: CONFIG Interface
**File:** `NM01/NM01-Interfaces-Core_INT-05.md`  
**Accesses:** 17  
**Summary:** Configuration management. Multi-tier (SSM + env vars), presets, validation.

---

### LESS-11: Document Decisions
**File:** `NM06/NM06-Lessons-Documentation_LESS-11.md`  
**Accesses:** 17  
**Summary:** Design decisions must be documented. Future maintainers need to know "why".

---

### AP-02: Circular Dependencies
**File:** `NM05/NM05-AntiPatterns-Import_AP-02.md`  
**Accesses:** 17  
**Summary:** Never create circular imports. A imports B, B imports A = failure. Gateway prevents this.

---

### AP-15: Swallowing Exceptions
**File:** `NM05/NM05-AntiPatterns-ErrorHandling_AP-15.md`  
**Accesses:** 16  
**Summary:** Never swallow exceptions silently. Log them, handle them, or let them propagate.

---

### RULE-02: No Circular Dependencies
**File:** `NM02/NM02-Dependencies-Import_RULE-02.md`  
**Accesses:** 16  
**Summary:** Foundation rule. Circular imports break Python. SUGA pattern prevents them.

---

### DEP-02: Layer 1 - Core Utilities
**File:** `NM02/NM02-Dependencies-Layers_DEP-02.md`  
**Accesses:** 16  
**Summary:** CACHE, SECURITY, SINGLETON. Depend only on Layer 0 (LOGGING).

---

### PATH-02: Cache Operation Flow
**File:** `NM03/NM03-Operations-Pathways_PATH-02.md`  
**Accesses:** 15  
**Summary:** Pathway for cache operations. gateway → interface_cache → cache_core.

---

### DT-04: Should I Cache This
**File:** `NM07/NM07-DecisionLogic-FeatureAddition_DT-04.md`  
**Accesses:** 15  
**Summary:** Decision tree for caching. Frequently accessed? Expensive to compute? Then cache.

---

### INT-08: HTTP_CLIENT Interface
**File:** `NM01/NM01-Interfaces-Advanced_INT-08.md`  
**Accesses:** 15  
**Summary:** HTTP client operations. GET, POST, request building, retry logic.

---

### MATRIX-01: Forward Dependency Matrix
**File:** `NM02/NM02-Dependencies-InterfaceDetail_MATRIX-01.md`  
**Accesses:** 15  
**Summary:** Shows what each interface depends on. Used to check dependency compliance.

---

### DEC-07: Dependencies < 128MB
**File:** `NM04/NM04-Decisions-Technical_DEC-07.md`  
**Accesses:** 14  
**Summary:** Lambda /tmp has 512MB limit. Keep dependencies under 128MB for safety margin.

---

### INT-10: CIRCUIT_BREAKER Interface
**File:** `NM01/NM01-Interfaces-Advanced_INT-10.md`  
**Accesses:** 14  
**Summary:** Circuit breaker pattern. Failure tracking, recovery, prevents cascade failures.

---

## 📊 TIER 2 STATISTICS

**Total Items:** 30  
**Total Accesses:** 586 (last 30 days)  
**Average per Item:** 19.5 accesses  
**Threshold:** 20-49 accesses  
**Coverage:** 23% of all ZAPH traffic

**Promotion Watch (19-20 accesses):**
- ARCH-03: 20 (at threshold)
- DEP-01: 20 (at threshold)
- DT-03: 20 (at threshold)
- DT-05: 19 (watch for promotion)
- ERROR-01: 19 (watch for promotion)

**Next Review:** Weekly

---

## 🔄 MAINTENANCE NOTES

**Tier 2 is the "fast-moving" tier:**
- Items frequently promoted to Tier 1
- Items demoted from Tier 1 land here
- Check weekly for boundary crossings
- Most dynamic tier in ZAPH

**Promotion Triggers:**
- Item reaches 50 accesses → Move to Tier 1
- Add quick context summary
- Update Tier 1 file

**Demotion Triggers:**
- Item drops below 10 accesses → Move to Tier 3
- Remove from this tier
- Update Tier 3 file

---

**Navigation:**
- **Hub:** [NM00B-ZAPH.md](NM00B-ZAPH.md)
- **Tier 1:** [NM00B-ZAPH-Tier1.md](NM00B-ZAPH-Tier1.md)
- **Tier 3:** [NM00B-ZAPH-Tier3.md](NM00B-ZAPH-Tier3.md)

---

**End of Tier 2**

**Total Lines:** 243  
**Compliance:** ✅ SIMA v3 (< 250 lines)  
**Purpose:** High priority items summary + pointers

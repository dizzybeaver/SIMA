# NM05-AntiPatterns-Critical_Index.md

# Anti-Patterns - Critical Index

**Category:** NM05 - Anti-Patterns
**Topic:** Critical
**Items:** 1
**Last Updated:** 2025-10-24

---

## Topic Overview

**Description:** The most critical anti-pattern that caused the highest-impact production bug in SUGA-ISP history. Sentinel objects crossing interface boundaries led to a 535ms performance penalty that took significant effort to diagnose and fix.

**Keywords:** sentinel objects, interface boundaries, performance, router layer, sanitization

**Priority Distribution:** 1 Critical

---

## Individual Files

### AP-10: Sentinel Objects Crossing Boundaries
- **File:** `NM05-AntiPatterns-Critical_AP-10.md`
- **Summary:** Never let sentinel objects escape interface boundaries - sanitize at router
- **Priority:** 🔴 Critical
- **Impact:** 535ms performance penalty, validation cascades, type confusion across system

---

## Common Themes

This anti-pattern represents the **highest-impact design flaw** discovered in production. The sentinel object (_CacheMiss) was designed for internal use within cache_core.py but leaked across interface boundaries, causing validation overhead throughout the system.

**Key Lesson:** **Sanitize at boundaries.** Internal implementation details (like sentinel objects) must be converted to standard Python types (None, exceptions, proper return values) before crossing interface boundaries. The router layer is the correct place for this sanitization.

**Historical Impact:**
- 535ms performance penalty (50% of execution time)
- Validation cascades across multiple interfaces
- Type confusion in client code
- Significant debugging effort

This anti-pattern led directly to **DEC-05** (Sentinel Sanitization decision) and **BUG-01** (Sentinel leak bug documentation).

---

## Related Topics

**Within NM05 (Anti-Patterns):**
- AP-19: Not Validating Security Assumptions - Similar boundary validation failure

**Other Categories:**
- **NM04-Decisions** - DEC-05 (Sentinel Sanitization decision)
- **NM06-Bugs** - BUG-01 (Sentinel leak - 535ms cost)
- **NM06-Lessons** - LESS-06 (Sentinel objects lesson)
- **NM03-Operations** - ERR-02 (Error propagation patterns)

---

**Navigation:**
- **Up:** [Category Index - NM05-AntiPatterns_Index.md]
- **Siblings:** [Import_Index, Implementation_Index, Dependencies_Index, Concurrency_Index, Performance_Index, ErrorHandling_Index, Security_Index, Quality_Index, Testing_Index, Documentation_Index, Process_Index]

---

**End of Index**

# NM05-AntiPatterns-Performance_Index.md

# Anti-Patterns - Performance Index

**Category:** NM05 - Anti-Patterns
**Topic:** Performance
**Items:** 1
**Last Updated:** 2025-10-24

---

## Topic Overview

**Description:** Anti-pattern related to Lambda cold start optimization. In Lambda's execution model where every millisecond of cold start matters, placing heavy imports at module level instead of lazy loading them adds unnecessary initialization time.

**Keywords:** performance, cold start, lazy loading, heavy imports, initialization time, LMMS

**Priority Distribution:** 1 High

---

## Individual Files

### AP-12: Heavy Imports at Module Level
- **File:** `NM05-AntiPatterns-Performance_AP-12.md`
- **Summary:** Lazy load heavy dependencies to minimize cold start time
- **Priority:** High
- **Impact:** Increases cold start time, wastes initialization on unused features

---

## Common Themes

This anti-pattern addresses **Lambda's cold start reality**. Every millisecond added to initialization is paid by every cold start. Heavy libraries like `requests`, `boto3 submodules`, `json` parsers, or `cryptography` should be lazy loaded - imported only when their features are actually needed.

**Key Philosophy:** **Pay for what you use, when you use it.** If a feature requiring a heavy library isn't used in a particular invocation, don't pay the import cost for that invocation.

**Integration with LMMS:**
This anti-pattern is addressed systematically by the Lambda Memory Management System (LMMS - ARCH-07), which implements three optimization strategies:
- **LIGS** (Lazy Import Gateway System) - Defers imports until needed
- **LUGS** (Lazy Unload Gateway System) - Unloads unused modules
- **ZAPH** (Zero-Abstraction Path) - Fast paths for common operations

---

## Related Topics

**Within NM05 (Anti-Patterns):**
- AP-09: Adding Dependencies Without Measurement - Related cost consideration
- Import patterns (AP-01 to AP-05) - Where imports occur in architecture

**Other Categories:**
- **NM01-Architecture** - ARCH-07 (LMMS system)
- **NM03-Operations** - PATH-01 (Cold start optimization pathway)
- **NM06-Lessons** - LESS-02 (Measure don't guess performance)
- **NM04-Decisions** - DEC-07 (128MB package size constraint)

---

**Navigation:**
- **Up:** [Category Index - NM05-AntiPatterns_Index.md]
- **Siblings:** [Import_Index, Implementation_Index, Dependencies_Index, Critical_Index, Concurrency_Index, ErrorHandling_Index, Security_Index, Quality_Index, Testing_Index, Documentation_Index, Process_Index]

---

**End of Index**

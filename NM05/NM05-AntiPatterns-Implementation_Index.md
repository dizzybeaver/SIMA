# NM05-AntiPatterns-Implementation_Index.md

# Anti-Patterns - Implementation Index

**Category:** NM05 - Anti-Patterns
**Topic:** Implementation
**Items:** 2
**Last Updated:** 2025-10-24

---

## Topic Overview

**Description:** Anti-patterns related to how core functionality is implemented. These patterns cover delegation failures and implementation shortcuts that violate SUGA architecture principles.

**Keywords:** implementation, delegation, extension facades, function calls, architecture violations

**Priority Distribution:** 1 High, 1 Medium

---

## Individual Files

### AP-06: Partial Delegation in Extension Facades
- **File:** `NM05-AntiPatterns-Implementation_AP-06.md`
- **Summary:** Extension facades must be pure delegation layers with no logic
- **Priority:** High
- **Impact:** Violates separation of concerns, creates hidden logic outside core

### AP-07: Direct Function Calls Instead of Gateway
- **File:** `NM05-AntiPatterns-Implementation_AP-07.md`
- **Summary:** Always use gateway for cross-interface operations, never direct calls
- **Priority:** Medium
- **Impact:** Breaks dependency tracking, makes code harder to maintain and test

---

## Common Themes

Both anti-patterns address **architectural boundary violations**. AP-06 deals with facades that try to do too much instead of purely delegating. AP-07 addresses the temptation to bypass the gateway layer for convenience. Together, they enforce the principle that **architectural layers exist for a reason** and shortcuts always create technical debt.

---

## Related Topics

**Within NM05 (Anti-Patterns):**
- Import (AP-01 to AP-05) - Related gateway/import patterns
- Critical (AP-10) - Core architectural violations
- Performance (AP-12) - Import cost considerations

**Other Categories:**
- NM01-Architecture - ARCH-01 (Gateway Trinity pattern)
- NM02-Dependencies - RULE-01 (Gateway import rules)
- NM04-Decisions - DEC-01 (SIMA pattern rationale)

---

**Navigation:**
- **Up:** [Category Index - NM05-AntiPatterns_Index.md]
- **Siblings:** [Import_Index, Dependencies_Index, Critical_Index, Concurrency_Index, Performance_Index, ErrorHandling_Index, Security_Index, Quality_Index, Testing_Index, Documentation_Index, Process_Index]

---

**End of Index**

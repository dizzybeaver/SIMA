# NM05-AntiPatterns-Dependencies_Index.md

# Anti-Patterns - Dependencies Index

**Category:** NM05 - Anti-Patterns
**Topic:** Dependencies
**Items:** 1
**Last Updated:** 2025-10-24

---

## Topic Overview

**Description:** Anti-patterns related to external dependencies and library management in Lambda. Focuses on the critical constraint of Lambda's 128MB deployment package limit and the importance of measuring before adding dependencies.

**Keywords:** dependencies, libraries, package size, 128MB limit, measurement

**Priority Distribution:** 1 High

---

## Individual Files

### AP-09: Adding Dependencies Without Measurement
- **File:** `NM05-AntiPatterns-Dependencies_AP-09.md`
- **Summary:** Always measure library size before adding - Lambda has 128MB limit
- **Priority:** High
- **Impact:** Can exceed Lambda size limits, force costly refactoring, block deployment

---

## Common Themes

This single anti-pattern addresses a **critical operational constraint** in Lambda development. The 128MB package size limit is hard, and exceeding it means deployment failure. The pattern emphasizes **measure before commit** - always check library size, look for lighter alternatives, and understand the full cost before adding any dependency.

**Key Philosophy:** Every byte counts in Lambda. What works in unlimited server environments fails in constrained Lambda environments. Proactive measurement prevents reactive crisis management.

---

## Related Topics

**Within NM05 (Anti-Patterns):**
- Import (AP-01 to AP-05) - Related to import patterns and dependency management
- Performance (AP-12) - Heavy imports impact cold start times

**Other Categories:**
- NM04-Decisions - DEC-07 (128MB package size limit)
- NM06-Lessons - LESS-02 (Measure don't guess)
- NM02-Dependencies - DEP-01 to DEP-08 (Dependency layer architecture)

---

**Navigation:**
- **Up:** [Category Index - NM05-AntiPatterns_Index.md]
- **Siblings:** [Import_Index, Implementation_Index, Critical_Index, Concurrency_Index, Performance_Index, ErrorHandling_Index, Security_Index, Quality_Index, Testing_Index, Documentation_Index, Process_Index]

---

**End of Index**

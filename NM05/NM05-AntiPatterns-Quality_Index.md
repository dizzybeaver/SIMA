# NM05-AntiPatterns-Quality_Index.md

# Anti-Patterns - Code Quality Index

**Category:** NM05 - Anti-Patterns
**Topic:** Code Quality
**Items:** 3
**Last Updated:** 2025-10-24

---

## Topic Overview

**Description:** Anti-patterns that degrade code maintainability, readability, and long-term sustainability. These patterns cover god functions that violate single responsibility, magic numbers that hide meaning, and inconsistent naming that creates confusion. While not causing immediate failures, they accumulate technical debt and make the codebase progressively harder to work with.

**Keywords:** code quality, maintainability, readability, god functions, magic numbers, naming conventions, technical debt

**Priority Distribution:** 2 Medium, 1 Low

---

## Individual Files

### AP-20: God Functions (>50 lines)
- **File:** `NM05-AntiPatterns-Quality_AP-20.md`
- **Summary:** Keep functions focused and under 50 lines - split complex functions
- **Priority:** Medium
- **Impact:** Violates single responsibility, hard to test, difficult to understand, high bug probability

### AP-21: Magic Numbers Without Constants
- **File:** `NM05-AntiPatterns-Quality_AP-21.md`
- **Summary:** Replace magic numbers with named constants that explain meaning
- **Priority:** Medium
- **Impact:** Hidden meaning, modification errors, scattered values, maintenance burden

### AP-22: Inconsistent Naming Conventions
- **File:** `NM05-AntiPatterns-Quality_AP-22.md`
- **Summary:** Follow Python naming conventions consistently (PEP 8 style guide)
- **Priority:** Low
- **Impact:** Cognitive load, confusion, merge conflicts, professionalism concerns

---

## Common Themes

All three quality anti-patterns address **code clarity and maintainability**. They don't cause immediate failures but create **accumulating technical debt** that makes the codebase progressively harder to work with.

**The "Death by a Thousand Cuts" Problem:**
- One 200-line function: Annoying
- Ten 200-line functions: Frustrating  
- Fifty 200-line functions: Unmaintainable
- Magic numbers scattered everywhere: Complete confusion
- Mixed naming styles: Cognitive overload

**Integration with Module Size Limits:**
AP-20 (god functions) operates at the function level, complementing the module-level size limits documented in SUGA-Module-Size-Limits.md (ARCH-09). Together they enforce atomization at both scales:
- **Function level**: < 50 lines (AP-20)
- **Module level**: < 400 lines (ARCH-09)

**Maintenance Philosophy:**
Quality isn't a luxury - it's an investment. Time spent following these patterns is repaid many times over in:
- Faster debugging
- Easier testing
- Confident refactoring
- Smoother onboarding
- Fewer regressions

---

## Related Topics

**Within NM05 (Anti-Patterns):**
- AP-25, AP-26: Documentation patterns - Related readability concerns
- AP-06, AP-07: Implementation patterns - Architectural quality

**Other Categories:**
- **NM01-Architecture** - ARCH-09 (Module size limits)
- **NM06-Lessons** - LESS-01 (Read complete files first)
- **NM04-Decisions** - DEC-08 (Flat file structure for simplicity)
- **NM00-Guidelines** - Documentation and coding standards

**External References:**
- PEP 8 (Python Style Guide)
- Clean Code principles (Robert Martin)
- SOLID principles (especially Single Responsibility)

---

**Navigation:**
- **Up:** [Category Index - NM05-AntiPatterns_Index.md]
- **Siblings:** [Import_Index, Implementation_Index, Dependencies_Index, Critical_Index, Concurrency_Index, Performance_Index, ErrorHandling_Index, Security_Index, Testing_Index, Documentation_Index, Process_Index]

---

**End of Index**

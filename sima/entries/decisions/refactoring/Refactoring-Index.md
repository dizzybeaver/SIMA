# Refactoring-Index.md

**Category:** Decision Logic  
**Subcategory:** Refactoring  
**Files:** 2  
**Last Updated:** 2024-10-30

---

## Overview

Decision trees for refactoring decisions - when to refactor code, when to extract functions, balancing code quality improvements against risk and effort.

---

## Files in This Category

### DT-10: Should I Refactor This Code

**File:** `DT-10.md`  
**REF-ID:** DT-10  
**Priority:** Medium  
**Status:** Active

**Summary:** Decision tree for refactoring decisions, determining when code quality improvements justify the risk and effort based on correctness, readability, duplication, and architectural conformance.

**Key Decision Points:**
- Is code working correctly?
- Is code hard to understand?
- Is code duplicated (>3 places)?
- Is code violating architecture?
- Is code significantly slow?

**Use When:**
- Considering code improvements
- Code quality concerns
- Technical debt decisions
- Architecture conformance issues

### DT-11: Extract to Function or Leave Inline

**File:** `DT-11.md`  
**REF-ID:** DT-11  
**Priority:** Low  
**Status:** Active

**Summary:** Decision tree for function extraction, determining when to extract code blocks into separate functions versus leaving them inline based on reuse, size, and single responsibility principle.

**Key Decision Points:**
- Is code used >2 times?
- Is code >10 lines?
- Does code have clear single purpose?
- Would extraction improve readability?

**Use When:**
- Considering function extraction
- DRY principle questions
- Single responsibility decisions
- Code organization questions

---

## Quick Decision Guide

### Scenario 1: Should I Refactor?

**Decision Path:**
1. Check **DT-10**: Should I Refactor This Code
   - Code working? (must be YES)
   - Hard to understand? → Refactor
   - Duplicated 3+ times? → Refactor
   - Architecture violation? → Refactor
   - Significantly slow? → Refactor
   - Could be simpler? → Maybe refactor
   - Otherwise? → Don't refactor

### Scenario 2: Extract This Code Block?

**Decision Path:**
1. Check **DT-11**: Extract to Function or Leave Inline
   - Used >2 times? → Extract (DRY)
   - >10 lines AND clear purpose? → Extract
   - Would improve readability? → Extract
   - Otherwise? → Leave inline

### Scenario 3: Code Quality Issue

**Analysis Framework:**
```
1. Identify issue type:
   - Readability → DT-10 (refactor)
   - Duplication → DT-11 (extract)
   - Architecture → DT-10 (conform)
   - Performance → DT-07 (optimize)

2. Assess risk vs benefit:
   - Working code? → Proceed carefully
   - Tests exist? → Safer to refactor
   - Clear improvement? → Worth it
   - Just "different"? → Don't refactor
```

---

## Refactoring Principles

### When to Refactor (DT-10)

**High Priority:**
- Hard to understand (nested >3 levels)
- Duplicated 3+ times
- Architecture violations
- Measured performance issues

**Medium Priority:**
- Could be significantly simpler
- Missing abstractions
- Inconsistent patterns

**Low Priority:**
- Could be "more elegant"
- Different style preference
- Works fine as-is

### When to Extract (DT-11)

**Always Extract:**
- Used 3+ times (DRY principle)
- >15 lines with clear purpose

**Consider Extracting:**
- Used 2 times
- >10 lines with single responsibility
- Improves readability significantly

**Don't Extract:**
- Used once, <10 lines, no clear purpose
- Adds unnecessary indirection
- Makes code harder to understand

---

## Related Categories

**Within Decision Logic:**
- **Optimization** (DT-07: Should I Optimize This Code)
- **Import** (DT-02: Where Should Function Go)
- **Testing** (DT-08: What Should I Test)

**Other REF-IDs:**
- **AP-20**: Unnecessary Complexity (refactoring anti-pattern)
- **AP-21**: Inconsistent Patterns (what to fix)
- **AP-22**: Missing Abstractions (extraction opportunities)
- **LESS-01**: Read Complete Files First (before refactoring)
- **LESS-13**: Keep Functions Focused (goal of extraction)
- **RULE-01**: Import via Gateway (architecture conformance)

---

## Common Questions

**Q: When should I refactor working code?**
**A:** See **DT-10** - Only if hard to understand, duplicated 3+ times, architecture violation, or slow (measured).

**Q: This code block is 8 lines, should I extract it?**
**A:** See **DT-11** - If used >2 times → Yes. If used once → Only if clear single purpose AND improves readability.

**Q: Code "smells bad" but works, refactor?**
**A:** See **DT-10** - Define "smells bad". If hard to understand or violates principles → Refactor. If just "different style" → Don't.

**Q: When NOT to refactor?**
**A:** See **DT-10** - If code works, is clear, not duplicated, conforms to architecture, and performs adequately → Don't refactor.

---

## Keywords

refactoring, code quality, function extraction, DRY principle, single responsibility, code organization, maintainability, readability, technical debt, code improvement, architecture conformance

---

## Navigation

**Parent:** Decision Logic Master Index  
**Siblings:** Import, Feature Addition, Error Handling, Testing, Optimization, Deployment, Architecture, Meta

**Location:** `/sima/entries/decisions/refactoring/`

---

**End of Index**

# File: Core-Architecture-Index.md

**Category:** Lessons Learned  
**Topic:** Core Architecture  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Items:** 7

---

## Topic Overview

Fundamental architectural principles for building maintainable, testable, and scalable systems. These lessons form the foundation for understanding how and why systems should be designed with separation of concerns, consistency, and resilience.

**Keywords:** architecture, gateway pattern, design principles, layering, separation of concerns, consistency, resilience

---

## Individual Files

### LESS-01: Gateway Pattern Prevents Problems
- **File:** `LESS-01.md`
- **Priority:** CRITICAL
- **Summary:** Gateway pattern architecturally prevents circular imports and tight coupling
- **Related:** DEC-01, AP-01, LESS-07

### LESS-03: Infrastructure vs Business Logic Separation
- **File:** `LESS-03.md`
- **Priority:** HIGH
- **Summary:** Router handles infrastructure, core handles business logic
- **Related:** ARCH-01, LESS-01, LESS-07

### LESS-04: Consistency Over Cleverness
- **File:** `LESS-04.md`
- **Priority:** HIGH
- **Summary:** Uniform patterns reduce cognitive load and prevent mistakes
- **Related:** DEC-03, WISD-04, LESS-01

### LESS-05: Graceful Degradation Required
- **File:** `LESS-05.md`
- **Priority:** HIGH
- **Summary:** Systems should degrade gracefully, not catastrophically
- **Related:** DEC-15, BUG-03, LESS-08

### LESS-06: Pay Small Costs Early
- **File:** `LESS-06.md`
- **Priority:** HIGH
- **Summary:** Small preventive costs prevent large bug costs
- **Related:** LESS-02, WISD-03, BUG-01

### LESS-07: Base Layers Have No Dependencies
- **File:** `LESS-07.md`
- **Priority:** CRITICAL
- **Summary:** Logging must be base layer with zero dependencies
- **Related:** DEP-01, LESS-01, BUG-02

### LESS-08: Test Failure Paths
- **File:** `LESS-08.md`
- **Priority:** HIGH
- **Summary:** Most bugs hide in error handling and edge cases
- **Related:** AP-24, LESS-05, BUG-01

---

## Cross-Topic Relationships

**Related Topics:**
- Performance (LESS-02 measurement supports architecture decisions)
- Operations (verification uses these principles)
- Documentation (teaches these patterns)

**Frequently Accessed Together:**
- When implementing new features: LESS-01, LESS-03, LESS-04, LESS-07
- When debugging: LESS-02, LESS-08
- When refactoring: LESS-04, LESS-06

---

## Usage Patterns

**When designing new system:**
1. Start with LESS-01 (gateway pattern)
2. Apply LESS-07 (dependency layering)
3. Follow LESS-04 (consistency)
4. Implement LESS-05 (graceful degradation)

**When optimizing:**
1. Use LESS-02 (measure first)
2. Consider LESS-06 (cost-benefit)
3. Maintain LESS-04 (consistency)

**When testing:**
1. Follow LESS-08 (test failures)
2. Verify LESS-05 (degradation works)
3. Test LESS-01 (isolation)

---

## Navigation

- **Up:** Lessons Master Index
- **Sibling Topics:** Performance, Operations, Documentation, Optimization

---

**END OF INDEX**

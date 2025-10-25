# NM06-Lessons-CoreArchitecture_Index.md

# Lessons - CoreArchitecture Index

**Category:** NM06 - Lessons  
**Topic:** CoreArchitecture  
**Items:** 9  
**Last Updated:** 2025-10-25 (added LESS-33-41)

---

## Topic Overview

**Description:** Fundamental architectural principles learned from building the Lambda Execution Engine with SUGA architecture. These lessons form the foundation for understanding how and why the system is designed the way it is.

**Keywords:** architecture, gateway pattern, SUGA, design principles, layering, separation of concerns, configuration, self-reference, maturity

---

## Individual Files

### LESS-01: Gateway Pattern Prevents Problems
- **File:** `NM06-Lessons-CoreArchitecture_LESS-01.md`
- **Summary:** Gateway pattern architecturally prevents circular imports and tight coupling
- **Related:** DEC-01, BUG-02, ARCH-01
- **Priority:** CRITICAL

### LESS-02: Measure, Don't Guess
- **File:** `NM06-Lessons-Performance_LESS-02.md`
- **Summary:** Data-driven debugging finds problems faster than intuition
- **Related:** BUG-01, LESS-10, DEC-05
- **Priority:** CRITICAL
- **Note:** Also appears in Optimization topic

### LESS-03: Infrastructure vs Business Logic
- **File:** `NM06-Lessons-CoreArchitecture_LESS-03.md`
- **Summary:** Router handles infrastructure concerns, core handles business logic
- **Related:** BUG-01, DEC-02, ARCH-01
- **Priority:** HIGH

### LESS-04: Consistency Over Cleverness
- **File:** `NM06-Lessons-CoreArchitecture_LESS-04.md`
- **Summary:** Uniform patterns across all interfaces reduce cognitive load
- **Related:** DEC-03, LESS-01, ARCH-01
- **Priority:** HIGH

### LESS-05: Graceful Degradation
- **File:** `NM06-Lessons-CoreArchitecture_LESS-05.md`
- **Summary:** Systems should degrade gracefully, not catastrophically
- **Related:** DEC-15, BUG-03, PATH-02
- **Priority:** HIGH

### LESS-06: Pay Small Costs Early
- **File:** `NM06-Lessons-CoreArchitecture_LESS-06.md`
- **Summary:** Small preventive costs are almost always worth it
- **Related:** BUG-01, LESS-02
- **Priority:** HIGH

### LESS-07: Base Layers Have No Dependencies
- **File:** `NM06-Lessons-CoreArchitecture_LESS-07.md`
- **Summary:** Logging must be base layer with no dependencies to prevent circular imports
- **Related:** DEP-01, DEC-02, BUG-02
- **Priority:** CRITICAL

### LESS-08: Test Failure Paths
- **File:** `NM06-Lessons-CoreArchitecture_LESS-08.md`
- **Summary:** Most bugs hide in error handling and edge cases
- **Related:** AP-24, BUG-01, BUG-03
- **Priority:** HIGH

### LESS-33-41: Self-Referential Architectures Indicate Maturity
- **File:** `NM06-Lessons-CoreArchitecture_LESS-33-41.md`
- **Summary:** Meta-patterns (system managing itself) demonstrate architectural completeness
- **Related:** LESS-01, INT-09, LESS-04
- **Priority:** MEDIUM
- **Added:** 2025-10-25

### LESS-46: Multi-Tier Configuration Balances Flexibility and Complexity
- **File:** `NM06-Lessons-Architecture_LESS-46.md`
- **Summary:** Well-designed multi-tier systems manageable when 80% use defaults
- **Related:** DEC-12, INT-05, LESS-51
- **Priority:** HIGH

---

## Cross-Topic Relationships

**Related Topics:**
- Optimization (LESS-02 shared)
- Operations (LESS-15 verification uses these principles)
- Evolution (LESS-14, LESS-18 build on these foundations)
- Optimization (LESS-46 informs complexity decisions)
- Learning (Architectural patterns to master)

**Frequently Accessed Together:**
- When implementing new feature: LESS-01, LESS-03, LESS-04, LESS-07
- When designing configuration: LESS-46
- When debugging: LESS-02, LESS-08
- When refactoring: LESS-04, LESS-06
- When evaluating maturity: LESS-33-41, LESS-01

---

**Navigation:**
- **Up:** Lessons Index (NM06-Lessons_Index.md)
- **Sibling Topics:** Optimization, Operations, Documentation, Evolution, Learning

---

**End of Index**

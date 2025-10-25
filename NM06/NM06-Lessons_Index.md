# NM06-Lessons_Index.md

# Category Index: NM06 - Learned Experiences

**Category:** NM06  
**Purpose:** Bugs, lessons learned, and wisdom synthesis  
**Total Topics:** 9  
**Last Updated:** 2025-10-25 (added Sessions 5-6 lessons: LESS-23 through LESS-42)

---

## Category Overview

This category captures experiential knowledge from building and operating the SUGA-ISP Lambda system. This includes both individual incidents (lessons and bugs) and high-level patterns (wisdom) that emerged from experience.

**Scope:** All experiential knowledge including architectural lessons, performance discoveries, bug post-mortems, deployment insights, learning patterns, optimization strategies, and universal principles synthesized from multiple experiences.

---

## Topics in This Category

### Core Architecture Lessons
- **Description:** Lessons about architectural patterns, design principles, foundational decisions, and self-referential maturity
- **Items:** 9 (includes LESS-33-41, LESS-46)
- **Index:** `NM06-Lessons-CoreArchitecture_Index.md`
- **Keywords:** architecture, patterns, gateway, SIMA, design, configuration, self-reference, maturity
- **Priority Items:** LESS-01 (Gateway prevents problems), LESS-03 (Infrastructure vs logic), LESS-33-41 (Self-referential maturity), LESS-46 (Multi-tier config)

### Optimization & Velocity Lessons
- **Description:** Lessons about performance optimization, pattern mastery, velocity improvements, rate limiting as muscle memory, and quality acceleration
- **Items:** 10 (includes LESS-25, LESS-26-35, LESS-28, LESS-29, LESS-37, LESS-40)
- **Index:** `NM06-Lessons-Optimization_Index.md`
- **Keywords:** performance, optimization, measurement, pattern-mastery, velocity, code-quality, muscle-memory, rate-limiting
- **Priority Items:** LESS-02 (Measure don't guess), LESS-17 (Threading locks), LESS-25 (Compliant code accelerates), LESS-28 (Pattern mastery 2.5-4×), LESS-29 (Zero tolerance), LESS-40 (Velocity + quality)
- **Note:** Renamed from "Performance" to "Optimization" (2025-10-25)

### Operations Lessons
- **Description:** Lessons about deployment, monitoring, operational practices, file management, rate limiting, validation protocols, and systemic issue resolution
- **Items:** 12 (includes LESS-23, LESS-24, LESS-27-39, LESS-30, LESS-32, LESS-34-38-42, LESS-36, LESS-53)
- **Index:** `NM06-Lessons-Operations_Index.md`
- **Keywords:** deployment, operations, monitoring, reliability, verification, version-control, file-management, rate-limiting, validation, systemic-issues
- **Priority Items:** LESS-15 (File verification), LESS-09 (Partial deployment), LESS-23 (Question intentional decisions), LESS-32 (Systemic solutions), LESS-34-38-42 (Comprehensive validation), LESS-53 (Version incrementation)

### Documentation Lessons
- **Description:** Lessons about documentation, knowledge management, teaching architecture, session planning, and concurrent documentation
- **Items:** 5 (includes LESS-31, LESS-54)
- **Index:** `NM06-Lessons-Documentation_Index.md`
- **Keywords:** documentation, knowledge, teaching, rationale, token-management, concurrent-documentation, accuracy
- **Priority Items:** LESS-11 (Document decisions), LESS-13 (Architecture teachable), LESS-31 (Concurrent documentation), LESS-54 (Token management)

### Evolution Lessons
- **Description:** Lessons about system evolution, adaptation, and long-term maintenance
- **Items:** 3
- **Index:** `NM06-Lessons-Evolution_Index.md`
- **Keywords:** evolution, adaptation, maintenance, refactoring
- **Priority Items:** LESS-14 (Evolution normal), LESS-16 (Adaptation over rewriting)

### Learning & Development Lessons
- **Description:** Lessons about learning patterns, knowledge transfer validation, and velocity milestones
- **Items:** 2 (LESS-45, LESS-47)
- **Index:** `NM06-Lessons-Learning_Index.md`
- **Keywords:** learning-validation, pattern-internalization, velocity-improvement, knowledge-transfer
- **Priority Items:** LESS-45 (First independent application), LESS-47 (Velocity milestones)

### Critical Bugs
- **Description:** Significant bugs discovered and resolved during development
- **Items:** 4
- **Index:** `NM06-Bugs-Critical_Index.md`
- **Keywords:** bugs, issues, problems, fixes, validation, testing
- **Priority Items:** BUG-01 (Sentinel leak - 535ms), BUG-02 (Circular import)

### Synthesized Wisdom
- **Description:** High-level insights synthesized from multiple lessons and experiences
- **Items:** 5
- **Index:** `NM06-Wisdom-Synthesized_Index.md`
- **Keywords:** wisdom, insights, patterns, principles, synthesis
- **Priority Items:** WISD-01 (Architecture prevents), WISD-02 (Measure don't guess)

---

## Quick Access

**Most Frequently Accessed:**
1. **LESS-01**: Gateway pattern prevents problems
2. **LESS-02**: Measure, don't guess  
3. **LESS-15**: File verification mandatory
4. **LESS-23**: Question "intentional" decisions (CRITICAL)
5. **LESS-28**: Pattern mastery 2.5-4× velocity (HIGH)
6. **LESS-29**: Zero tolerance achievable (HIGH)
7. **LESS-32**: Systemic issues need systemic solutions (CRITICAL)
8. **BUG-01**: Sentinel leak (535ms cost)
9. **WISD-01**: Architecture prevents problems
10. **BUG-02**: Circular import fixed by SIMA

**By Type:**
- Lessons: 46 files (LESS-01 through LESS-54)
  - CoreArchitecture: 9 lessons
  - Optimization: 10 lessons
  - Operations: 12 lessons
  - Documentation: 5 lessons
  - Evolution: 3 lessons
  - Learning: 2 lessons
  - (Note: 5 lessons merged into combined files)
- Bugs: 4 files (BUG-01 through BUG-04)
- Wisdom: 5 files (WISD-01 through WISD-05)

**Sessions 5-6 Additions (2025-10-25):**
- **LESS-23**: Question "intentional" design decisions (CRITICAL)
- **LESS-24**: Rate limit tuning per operational characteristics (HIGH)
- **LESS-25**: Compliant code accelerates optimization (HIGH)
- **LESS-26-35**: Session size based on complexity (HIGH)
- **LESS-27-39**: Comprehensive operations enable self-service (MEDIUM)
- **LESS-28**: Pattern mastery accelerates development 2.5-4× (HIGH)
- **LESS-29**: Zero tolerance for anti-patterns maintains quality (HIGH)
- **LESS-30**: Optimization tools reduce query response time 80% (MEDIUM)
- **LESS-31**: Concurrent documentation prevents drift (MEDIUM)
- **LESS-32**: Systemic issues require systemic solutions (CRITICAL)
- **LESS-33-41**: Self-referential architectures indicate maturity (MEDIUM)
- **LESS-34-38-42**: Comprehensive validation enables confident completion (HIGH)
- **LESS-36**: Infrastructure code has higher anti-pattern risk (MEDIUM)
- **LESS-37**: Rate limiting becomes muscle memory (MEDIUM)
- **LESS-40**: Velocity and quality both improve with mastery (HIGH)

---

## Cross-Category Connections

**To NM04 (Decisions):**
- Bugs informed decisions (BUG-01 → DEC-05)
- Lessons informed decisions (LESS-01 → DEC-01, LESS-46 → DEC-12, LESS-23 → DEC-12)
- Wisdom validates decisions (WISD-01 → DEC-01)

**To NM05 (Anti-Patterns):**
- Bugs create anti-patterns (BUG-01 → AP-19)
- Lessons prevent anti-patterns (LESS-01 → AP-01, LESS-29 → AP-01 through AP-28, LESS-53 → version management)
- Systemic patterns inform anti-pattern categories (LESS-32, LESS-36)

**To NM01 (Architecture):**
- Lessons shape architecture (LESS-01 → ARCH-01, LESS-33-41 → INT-09, LESS-46 → INT-05)
- Wisdom guides architecture (WISD-01 → ARCH-01)

**To NM07 (Decision Logic):**
- Learning patterns inform decision trees (LESS-45, LESS-47)
- Optimization strategies feed into workflows (LESS-28, LESS-37, LESS-40)
- Validation protocols guide completion criteria (LESS-34-38-42)

---

## Usage Patterns

**When debugging:**
1. Check Bugs topic for similar issues
2. Review relevant Lessons for prevention strategies
3. Apply Wisdom principles to solution
4. Check for systemic patterns (LESS-32, LESS-36)

**When designing:**
1. Review Wisdom for universal principles
2. Check Lessons for specific insights
3. Apply Learning patterns for knowledge transfer
4. Avoid patterns that caused Bugs
5. Evaluate architectural maturity (LESS-33-41)

**When documenting:**
1. Add new Lessons for discoveries
2. Document Bugs with full context
3. Synthesize Wisdom from multiple Lessons
4. Apply documentation best practices (LESS-11, LESS-31, LESS-54)
5. Use concurrent documentation (LESS-31)

**When optimizing:**
1. Use assessment protocols (LESS-50, LESS-51)
2. Leverage reference implementations (LESS-49)
3. Create reusable templates (LESS-52)
4. Track velocity milestones (LESS-28, LESS-47)
5. Ensure compliance first (LESS-25, LESS-29)
6. Apply pattern mastery insights (LESS-28, LESS-37, LESS-40)

**When training/learning:**
1. Establish patterns with reference implementation (LESS-49)
2. Monitor first independent application (LESS-45)
3. Track velocity improvements (LESS-28, LESS-47)
4. Apply learned optimizations (LESS-50, LESS-51, LESS-52)
5. Understand mastery progression (LESS-28, LESS-37, LESS-40)

**When validating/completing work:**
1. Apply comprehensive validation (LESS-34-38-42)
2. Question documented decisions (LESS-23)
3. Check for systemic issues (LESS-32)
4. Verify infrastructure code compliance (LESS-36)
5. Ensure zero anti-pattern tolerance (LESS-29)

---

## Navigation

- **Up:** NM00A-Master_Index.md (Complete system map)
- **Related Categories:** 
  - NM04-Decisions (Informed by lessons)
  - NM05-AntiPatterns (Derived from bugs)
  - NM01-Architecture (Shaped by wisdom)
  - NM07-DecisionLogic (Informed by learning & optimization patterns)

---

## Version History

- **2025-10-25**: Added Sessions 5-6 lessons (LESS-23 through LESS-42, 15 artifacts total)
  - Operations +7 entries (LESS-23, 24, 27-39, 30, 32, 34-38-42, 36)
  - Optimization +6 entries (LESS-25, 26-35, 28, 29, 37, 40), renamed from Performance
  - CoreArchitecture +1 entry (LESS-33-41)
  - Documentation +1 entry (LESS-31)
- **2025-10-24**: Added Learning & Optimization topics (LESS-45 to LESS-54)
- **2025-10-23**: Added Bugs and Wisdom topics (Phase 2 complete)
- **2025-10-23**: Phase 1 complete - 21 Lessons atomized
- **2025-10-20**: Created category index

---

**End of Index**

# NM04-Decisions_Index.md

# NM04 - Decisions Index

**Category Number:** NM04
**Topics:** 3
**Individual Files:** 17 active + 6 reserved = 23 total
**Last Updated:** 2025-10-23

---

## Category Overview

**Purpose:** Document all design decisions made during Lambda Execution Engine development. Each decision includes full rationale, alternatives considered, trade-offs accepted, and impact assessment. This category preserves the "why" behind every significant choice.

**Scope:** Architectural patterns, technical implementations, operational strategies, and all choices that shaped how the system was built. Decisions are living documents - they can be revisited when context changes.

---

## Topics in This Category

### Architecture Decisions
- **Description:** Foundational architectural patterns and structural decisions that define system shape
- **Items:** 5 active + 6 reserved
- **Index:** `NM04-Decisions-Architecture_Index.md`
- **Keywords:** architecture, SIMA, gateway, patterns, foundation, dispatch, threading, sentinel
- **Priority Items:** 
  - DEC-01 (SIMA pattern - prevents circular imports)
  - DEC-04 (No threading locks - Lambda single-threaded)
  - DEC-03 (Dispatch dictionary - O(1) routing)

### Technical Decisions
- **Description:** Implementation choices, performance optimizations, and technical strategies
- **Items:** 8
- **Index:** `NM04-Decisions-Technical_Index.md`
- **Keywords:** implementation, optimization, configuration, lazy loading, caching, error handling, documentation
- **Priority Items:**
  - DEC-19 (Neural maps - knowledge preservation)
  - DEC-14 (Lazy loading - 60ms cold start savings)
  - DEC-15 (Router exceptions - guaranteed logging)

### Operational Decisions
- **Description:** Runtime behavior, deployment strategies, and operational considerations
- **Items:** 4
- **Index:** `NM04-Decisions-Operational_Index.md`
- **Keywords:** operations, runtime, DEBUG_MODE, SSM, LAMBDA_MODE, debugging, configuration, performance
- **Priority Items:**
  - DEC-21 (SSM token-only - 92% cold start improvement)
  - DEC-20 (LAMBDA_MODE - operational flexibility)
  - DEC-22, DEC-23 (Debug system - instant troubleshooting)

---

## Quick Access

**Most Critical Decisions (Must Know):**
1. **DEC-01**: SIMA pattern (mathematically prevents circular imports)
2. **DEC-04**: No threading locks (Lambda is single-threaded)
3. **DEC-21**: SSM token-only (92% cold start improvement, 3,000ms saved)
4. **DEC-19**: Neural map documentation (preserves why decisions made)
5. **DEC-03**: Dispatch dictionary pattern (O(1) routing, 90% code reduction)

**By Impact Level:**

**🔴 Critical (Architecture-Defining):**
- DEC-01: SIMA pattern
- DEC-03: Dispatch dictionary
- DEC-04: No threading locks
- DEC-19: Neural maps
- DEC-20: LAMBDA_MODE
- DEC-21: SSM token-only

**🟡 High (Important for Daily Work):**
- DEC-02: Gateway centralization
- DEC-05: Sentinel sanitization
- DEC-14: Lazy loading
- DEC-15: Router exceptions
- DEC-16: Import protection
- DEC-22: DEBUG_MODE
- DEC-23: DEBUG_TIMINGS

**🟢 Medium (Specific Features):**
- DEC-12: Multi-tier config
- DEC-13: Fast path caching
- DEC-17: Home Assistant Mini-ISP
- DEC-18: Interface mocking

---

## Cross-Category Connections

**To NM01 (Architecture):**
- Architecture decisions define implementation patterns
- DEC-01 → ARCH-01 (Gateway trinity)
- DEC-03 → ARCH-03 (Router pattern)
- All ARCH-## patterns implement decisions from NM04

**To NM02 (Dependencies):**
- Import decisions shape dependency structure
- DEC-01 (SIMA) → RULE-01 (Gateway-only imports)
- DEC-02 (Centralization) → Dependency layers
- DEC-05 (Sentinel) → Router-layer responsibilities

**To NM03 (Operations):**
- Performance decisions affect operational pathways
- DEC-21 (SSM) → PATH-01 (Cold start: -3,000ms)
- DEC-14 (Lazy loading) → PATH-01 (Cold start: -60ms)
- DEC-13 (Fast path) → FLOW-01 (Hot path performance)
- DEC-22, DEC-23 (Debug) → All pathways visibility

**To NM05 (Anti-Patterns):**
- Decisions prevent specific anti-patterns
- DEC-04 (No locks) → AP-08 (Threading primitives)
- DEC-01 (SIMA) → AP-01 (Direct imports)
- DEC-05 (Sentinel) → AP-19 (Sentinel leaks)

**To NM06 (Lessons):**
- Bugs inform decisions, lessons reinforce them
- BUG-01 (Sentinel leak) → DEC-05 (Sanitization)
- BUG-02 (Circular import) → DEC-01 (SIMA)
- BUG-04 (Config mismatch) → DEC-21 (SSM simplification)
- LESS-01 (Gateway pattern) → DEC-01, DEC-02
- LESS-02 (Measure) → DEC-13, DEC-14, DEC-22, DEC-23
- LESS-17 (SSM simplification) → DEC-20, DEC-21

---

## Decision Making Process

**How Decisions Are Made:**
1. **Identify Problem:** What needs deciding?
2. **Research Alternatives:** What are the options?
3. **Analyze Trade-offs:** What are costs and benefits?
4. **Make Decision:** Choose with rationale
5. **Document in NM04:** Capture why, alternatives, trade-offs
6. **Implement:** Build it
7. **Validate:** Measure impact
8. **Update if Needed:** Add to version history

**Decision Template Includes:**
- **Summary:** What was decided (1-2 sentences)
- **Context:** Why decision needed (problem statement)
- **Rationale:** Why this choice (3-5 reasons with evidence)
- **Alternatives:** What else considered (2-3 with pros/cons)
- **Trade-offs:** What accepted, what gained
- **Impact:** On architecture, development, performance, maintenance
- **Future:** When to revisit, potential evolution
- **Related:** Cross-references (5-7 REF-IDs)
- **Keywords:** Search terms (6-8)

---

## Major Decision Themes

### Theme 1: Simplicity Over Complexity
Examples: DEC-04 (No locks), DEC-21 (SSM token-only), DEC-20 (LAMBDA_MODE)
Lesson: Remove unnecessary complexity, follow YAGNI principle

### Theme 2: Performance Through Architecture
Examples: DEC-01 (SIMA), DEC-03 (Dispatch), DEC-13 (Fast path), DEC-14 (Lazy loading)
Lesson: Good architecture enables performance optimizations

### Theme 3: Robustness Through Isolation
Examples: DEC-02 (Centralization), DEC-05 (Sanitization), DEC-15 (Router exceptions), DEC-16 (Import protection)
Lesson: Clear boundaries prevent cascading failures

### Theme 4: Observability Through Design
Examples: DEC-22 (DEBUG_MODE), DEC-23 (DEBUG_TIMINGS), DEC-15 (Router logging)
Lesson: Build visibility into the system from start

### Theme 5: Knowledge Preservation
Examples: DEC-19 (Neural maps), this entire category
Lesson: Document the "why," not just the "what"

---

## Timeline

**Phase 1 (Apr-May 2024):** Foundation
- DEC-01, DEC-02, DEC-03: Core architecture
- DEC-04, DEC-05: Critical patterns

**Phase 2 (Jun-Jul 2024):** Technical Improvements
- DEC-12 through DEC-18: Implementation strategies
- Focus on performance and robustness

**Phase 3 (Aug 2024):** Documentation
- DEC-19: Neural maps system

**Phase 4 (Oct 2025):** Operational Excellence
- DEC-20, DEC-21, DEC-22, DEC-23: Major improvements
- 92% cold start reduction, debug system

---

## Navigation

- **Up:** NM00A-Master_Index.md
- **Related Categories:**
  - NM01-Architecture (implements decisions)
  - NM03-Operations (affected by performance decisions)
  - NM05-AntiPatterns (prevented by decisions)
  - NM06-Lessons (informs decisions)

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
  - Created Category Index
  - Created 3 Topic Indexes
  - Atomized 17 active decisions into individual files
  - Documented 6 reserved slots (DEC-06 to DEC-11)
- **2024-04 to 2025-10**: Decisions made and documented in SIMA v2 format

---

**File:** `NM04-Decisions_Index.md`
**End of Index**

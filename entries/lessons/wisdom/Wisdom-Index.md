# File: Wisdom-Index.md

**Category:** Generic Lessons  
**Type:** Index  
**Version:** 1.1.0  
**Created:** 2025-10-30  
**Updated:** 2025-11-02

---

## Overview

High-level insights synthesized from multiple lessons, bugs, and experiences. These represent patterns and principles that emerged from the collective body of knowledge. Unlike individual lessons which document specific incidents, wisdom items capture universal principles that apply broadly across projects.

**Total Wisdom Items:** 6  
**Location:** `/sima/entries/wisdom/`  
**Scope:** Generic (applicable to any project)

---

## Wisdom Catalog

### WISD-01: Architecture Prevents Problems
**File:** `WISD-01.md`  
**Priority:** High  
**Summary:** Good architecture makes certain mistakes impossible through design rather than relying on developer discipline  
**Key Principle:** Make the right thing easy and the wrong thing impossible  
**Synthesizes:** LESS-01, BUG-01, BUG-02, BUG-03, DEC-01, ARCH-01  
**Application:** Gateway pattern, error boundaries, structural prevention

---

### WISD-02: Measure, Don't Guess
**File:** `WISD-02.md`  
**Priority:** High  
**Summary:** Data-driven decisions beat intuition every time; measurement reveals truth that guessing obscures  
**Key Principle:** In God we trust, all others bring data  
**Synthesizes:** LESS-02, LESS-10, LESS-20, BUG-01, DEC-05  
**Application:** Performance optimization, debugging, validation

---

### WISD-03: Small Costs Early Prevent Large Costs Later
**File:** `WISD-03.md`  
**Priority:** High  
**Summary:** Prevention is 10-1000x cheaper than cure; invest small costs upfront in validation and error handling  
**Key Principle:** An ounce of prevention is worth a pound of cure  
**Synthesizes:** LESS-06, LESS-15, BUG-01, BUG-04, DEC-05  
**Application:** Validation, sanitization, error handling, monitoring

---

### WISD-04: Consistency Over Cleverness
**File:** `WISD-04.md`  
**Priority:** High  
**Summary:** Uniform patterns reduce cognitive load; predictable beats clever  
**Key Principle:** Boring is good, surprising is bad  
**Synthesizes:** LESS-04, LESS-13, DEC-01, DEC-17, ARCH-01  
**Application:** Design patterns, file structure, coding standards

---

### WISD-05: Document Everything
**File:** `WISD-05.md`  
**Priority:** High  
**Summary:** Memory fades, documentation persists; if it's not documented, it doesn't exist  
**Key Principle:** If it's not documented, it doesn't exist  
**Synthesizes:** LESS-11, LESS-12, LESS-13  
**Application:** Design decisions, bug fixes, lessons learned, architecture

---

### WISD-06: Session-Level Cache-Busting for AI Tool Constraints
**File:** `WISD-06.md`  
**Priority:** Critical  
**Summary:** When platform tools have immutable aggressive caching, implement session-level cache-busting via unique identifiers in requests  
**Key Principle:** Work around uncontrollable platform constraints with minimal-cost session identifiers  
**Synthesizes:** Multiple debugging sessions with stale AI tool caches  
**Application:** AI assistants with web_fetch, CDNs, enterprise proxies, collaborative tools

---

## Cross-Cutting Themes

### Theme 1: Prevention Through Design
**Appears in:**
- WISD-01: Architecture prevents problems
- WISD-03: Small costs early
- WISD-06: Session-level cache-busting (design around constraints)

**Core Insight:** Build prevention into structure, not rules.

### Theme 2: Data-Driven Decisions
**Appears in:**
- WISD-02: Measure don't guess
- WISD-03: Small costs early (ROI calculations)
- WISD-06: Session-level cache-busting (measured platform behavior)

**Core Insight:** Decisions backed by data beat intuition.

### Theme 3: Human Factors
**Appears in:**
- WISD-04: Consistency over cleverness
- WISD-05: Document everything

**Core Insight:** Design for human understanding and memory limits.

### Theme 4: Platform Constraints
**Appears in:**
- WISD-06: Session-level cache-busting (work with immutable constraints)

**Core Insight:** When platform behavior is immutable, design around it rather than fighting it.

---

## Applying the Wisdom

**When facing new problem, ask:**

1. **Can architecture prevent?** (WISD-01)
   → Design mistake impossible

2. **What does measurement show?** (WISD-02)
   → Use data, not intuition

3. **What's the ROI of prevention?** (WISD-03)
   → Small cost now vs large cost later?

4. **Does this follow patterns?** (WISD-04)
   → Consistent with rest of system?

5. **Is decision documented?** (WISD-05)
   → Will we remember why in 6 months?

6. **Is platform constraint immutable?** (WISD-06)
   → Design workaround vs fighting platform?

---

## Summary: The Six Wisdoms

**Together they create:** Robust, maintainable, understandable systems that work with platform realities.

1. **Architecture Prevents Problems** - Design away entire bug categories
2. **Measure Don't Guess** - Data beats intuition
3. **Small Costs Early** - Prevention >>> Cure
4. **Consistency Over Cleverness** - Uniform patterns reduce cognitive load
5. **Document Everything** - Memory fades, docs persist
6. **Session-Level Cache-Busting** - Work around immutable platform constraints

---

## Usage Guidelines

**For Developers:**
- Reference wisdom when making design decisions
- Ask wisdom questions before implementing
- Use as code review checklist
- Apply principles to new problems

**For Architects:**
- Use wisdom to guide system design
- Evaluate architecture against principles
- Document trade-offs using wisdom framework
- Teach principles to team

**For Maintainers:**
- Use wisdom to understand existing decisions
- Apply principles when refactoring
- Evaluate changes against wisdom
- Document new insights

**For AI Tool Users:**
- Apply WISD-06 when experiencing stale content issues
- Implement session-level cache-busting at session start
- Verify platform cache constraints before troubleshooting

---

## Related References

**Architecture:**
- ARCH-01: SUGA Pattern

**Decisions:**
- DEC-01: Gateway pattern choice
- DEC-05: Sentinel sanitization
- DEC-17: Flat file structure

**Bugs:**
- BUG-01: Sentinel leak
- BUG-02: Circular import
- BUG-03: Cascading failures
- BUG-04: Configuration mismatch

**Lessons:**
- LESS-01: Gateway prevents problems
- LESS-02: Measure don't guess
- LESS-04: Consistency over cleverness
- LESS-06: Pay small costs early
- LESS-11: Document decisions

---

## Evolution

**How Wisdom Emerges:**
1. Individual lessons learned (LESS-##)
2. Patterns identified across lessons
3. Universal principles extracted
4. Wisdom item created (WISD-##)

**When to Add New Wisdom:**
- Pattern appears across 3+ lessons
- Principle applies universally
- Insight is non-obvious
- Guidance is actionable

**Recent Addition:**
- WISD-06 (2025-11-02): Session-level cache-busting for AI tool constraints

---

## Keywords

wisdom, insights, patterns, principles, synthesis, meta-lessons, universal-principles, platform-constraints, cache-busting, tool-limitations

---

## Cross-References

**Synthesizes From:** All LESS-## files, BUG-## files, DEC-## files  
**Referenced By:** All architectural decisions  
**Applied In:** All development work, AI assistant usage

---

**End of Index**

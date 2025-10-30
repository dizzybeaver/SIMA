# NM06-Lessons-Documentation_LESS-11.md - LESS-11

# LESS-11: Design Decisions Must Be Documented

**Category:** Lessons  
**Topic:** Documentation  
**Priority:** CRITICAL  
**Status:** Active  
**Created:** 2025-10-23  
**Last Updated:** 2025-10-23

---

## Summary

Document WHY decisions were made, not just WHAT was decided. Memory fades - rationale clear when designing becomes forgotten when maintaining. SIMA provides permanent record of decisions with context.

---

## Context

6 months after making architectural decisions, developers couldn't remember why choices were made. Code shows WHAT but not WHY. Re-litigating already-made decisions wastes time and risks undoing what works.

---

## Content

### The Problem

**Memory fades:**
```
Developer 1: "Why don't we use threading locks?"
Developer 2: "I... don't remember."

Developer 1: "Why is logging the base layer?"
Developer 2: "Seems arbitrary. Let me spend 2 hours re-deriving the reasoning."
```

**Code doesn't explain WHY:**
```python
# Code shows WHAT
class GatewayInterface(Enum):
    CACHE = "cache"
    LOGGING = "logging"

# Doesn't show WHY:
# - Why enum instead of strings?
# - Why gateway pattern at all?
# - What alternatives were considered?
```

### The Solution

**Document decisions when made:**
```markdown
# DECISION: Use Gateway Pattern

## Context
Lambda Execution Engine has 12 interfaces.
Direct imports created circular dependencies.

## Decision
All cross-interface calls route through gateway.

## Rationale
1. Prevents circular imports (impossible)
2. Single aggregation point
3. Testable in isolation
4. Consistent pattern

## Alternatives Considered
- Direct imports: Creates circular deps
- Lazy imports: Performance penalty
- Dependency injection: Over-complicated

## Consequences
- Positive: No circular imports
- Negative: One extra function call
- Trade-off: Accepted for benefits

## Date: 2025.08.15
## Status: Implemented
```

### What to Document

**Document the WHY, not the WHAT:**
```markdown
❌ Bad: "This function gets cache value"
✅ Good: "Cache uses sentinel to distinguish 'no value' from 'value is None'. 
         Router sanitizes sentinel to None to prevent leaks (BUG-01)."
```

**Key questions:**
1. What problem does this solve?
2. Why this approach over alternatives?
3. What trade-offs were accepted?
4. What constraints influenced decision?
5. What happens if we remove this?

### Where to Document

**Documentation Hierarchy:**
```
Level 1: SIMA Neural Maps (Architecture decisions)
Level 2: README files (Setup & usage)
Level 3: Docstrings (API contracts)
Level 4: Inline comments (Tricky logic)
```

### The SIMA Solution

**Why SIMA exists:**
- Memory fades (6 months → forgotten)
- Context lost (why decisions made)
- Knowledge tribal (only in heads)
- Re-discovery expensive (hours)

**SIMA provides:**
- Permanent decision record
- Rationale preserved
- Cross-references
- Searchable knowledge base

### Real-World Impact

**Before SIMA:**
- "Why no threading locks?" → 2+ hours to re-derive
- Risk: Might add locks unnecessarily

**After SIMA:**
- "Why no threading locks?" → Search → DEC-04 → 2 minutes
- Clear rationale with 4 reasons

**Savings:**
- Monthly: 3-9 hours (2-3 re-litigations)
- Annual: 36-108 hours

### Key Principles

**1. Document When You Decide**
```
❌ "I'll document later" → Forget context
✅ "Document now while fresh" → Accurate
```

**2. Explain Trade-offs**
```
❌ "We chose A"
✅ "We chose A over B (complex) and C (slow)"
```

**3. Document What's NOT Done**
```
"We considered X but rejected because Y"
→ Prevents future suggestion of X
```

---

## Related Topics

- **NM04**: All design decisions
- **LESS-13**: Architecture must be teachable
- **DEC-19**: Documentation standards

---

## Keywords

document decisions, preserve rationale, institutional knowledge, why decisions

---

## Version History

- **2025-10-23**: Created - Migrated to SIMA v3 individual file format
- **2025-10-20**: Original documentation in NM06-LESSONS-Documentation and Knowledge.md

---

**File:** `NM06-Lessons-Documentation_LESS-11.md`  
**Directory:** NM06/  
**End of Document**

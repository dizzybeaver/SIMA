# File: LESS-11.md

**REF-ID:** LESS-11  
**Category:** Lessons Learned  
**Topic:** Documentation  
**Version:** 4.0.0  
**Date:** 2025-10-30  
**Status:** Production

---

## Title

Design Decisions Must Be Documented

---

## Priority

CRITICAL

---

## Summary

Document WHY decisions were made, not just WHAT was decided. Memory fades - rationale clear when designing becomes forgotten when maintaining. Documentation provides permanent record of decisions with context.

---

## Context

6 months after making architectural decisions, developers couldn't remember why choices were made. Code shows WHAT but not WHY. Re-litigating already-made decisions wastes time and risks undoing what works.

---

## Lesson

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
class ModuleType(Enum):
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
System has 12 modules.
Direct imports created circular dependencies.

## Decision
All cross-module calls route through gateway.

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

## Date: 2025-08-15
## Status: Implemented
```

### What to Document

**Document the WHY, not the WHAT:**
```markdown
❌ Bad: "This function gets cache value"
✅ Good: "Cache uses sentinel to distinguish 'no value' from 'value is None'. 
         Router sanitizes sentinel to None to prevent data leaks."
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
Level 1: Architecture Docs (Design decisions)
Level 2: README files (Setup & usage)
Level 3: Docstrings (API contracts)
Level 4: Inline comments (Tricky logic)
```

### Real-World Impact

**Before Documentation:**
- "Why no threading locks?" → 2+ hours to re-derive
- Risk: Might add locks unnecessarily

**After Documentation:**
- "Why no threading locks?" → Search → Decision doc → 2 minutes
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

## Related

**Cross-References:**
- DEC-01: Example of well-documented decision
- LESS-13: Architecture must be teachable
- AP-25: Undocumented decisions anti-pattern

**Keywords:** document decisions, preserve rationale, institutional knowledge, decision records

---

## Version History

- **4.0.0** (2025-10-30): Genericized for SIMAv4
- **3.0.0** (2025-10-23): Initial SIMAv3 format

---

**END OF FILE**

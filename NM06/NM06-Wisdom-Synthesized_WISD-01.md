# NM06-Wisdom-Synthesized_WISD-01.md - WISD-01

# WISD-01: Architecture Prevents Problems

**Category:** Lessons
**Topic:** Synthesized Wisdom
**Priority:** High
**Status:** Active
**Created:** 2025-10-23
**Last Updated:** 2025-10-23

---

## Summary

Good architecture makes certain mistakes impossible through design rather than relying on developer discipline. Don't rely on rules and documentation; rely on architecture that prevents problems.

---

## Context

This wisdom emerged from analyzing multiple bugs (BUG-01, BUG-02) and lessons (LESS-01) where architectural decisions prevented entire categories of problems. Rather than trusting developers to follow rules, the architecture enforces correct behavior automatically.

---

## Content

### The Pattern

**Traditional Approach:**
```
Write rules → Hope developers follow rules → Bugs when rules broken
```

**Wisdom Approach:**
```
Design architecture → Rules enforced by structure → Mistakes prevented
```

**The Key Insight:** Architecture is more reliable than discipline.

### Why It Matters

**Human Nature:**
- Developers make mistakes (everyone does)
- Rules are forgotten under pressure
- Discipline fails when rushed
- Complexity overwhelms memory

**Architectural Prevention:**
- Enforced automatically
- Can't be bypassed accidentally
- Works even when tired/rushed
- Scales without extra effort

### When to Apply

**Ask: "Can architecture prevent this problem?"**

If yes → Design prevention into system
If no → Use runtime checks as fallback

**Examples of architectural prevention:**
- Gateway pattern prevents circular imports
- Router sanitization prevents sentinel leaks
- Error boundaries prevent cascading failures
- Flat file structure prevents deep nesting bugs

### Examples

**Example 1: Circular Imports (BUG-02)**
```python
# Traditional approach (weak):
# Rule: "Don't create circular imports"
# Problem: Easy to break accidentally

# Architectural approach (strong):
# Gateway pattern makes circular imports impossible
# All imports go through gateway
# Core modules never import each other
```

**Example 2: Sentinel Leak (BUG-01)**
```python
# Traditional approach (weak):
# Rule: "Remember to convert sentinels to None"
# Problem: Easy to forget

# Architectural approach (strong):
# Router layer automatically sanitizes
# Core modules can use sentinels
# Users always receive None
```

**Example 3: Error Isolation (BUG-03)**
```python
# Traditional approach (weak):
# Rule: "Always use try/except for external calls"
# Problem: Easy to miss one

# Architectural approach (strong):
# Interface layer wraps all operations
# Error boundaries built into structure
# Can't accidentally skip error handling
```

### Universal Principle

**"Make the right thing easy and the wrong thing impossible"**

- Right thing: Use gateway for imports → Easy (just import gateway)
- Wrong thing: Direct core imports → Impossible (not exposed)

- Right thing: Get None for cache miss → Automatic (router sanitizes)
- Wrong thing: Get sentinel object → Impossible (sanitized away)

---

## Related Topics

- **LESS-01**: Gateway pattern prevents problems (primary source)
- **BUG-02**: Circular import (prevented by gateway pattern)
- **BUG-01**: Sentinel leak (prevented by router sanitization)
- **DEC-01**: SIMA pattern choice (architectural decision)
- **ARCH-01**: Gateway trinity pattern (architectural implementation)
- **WISD-03**: Small costs early (related wisdom)

---

## Keywords

architecture, prevention, design, gateway-pattern, enforcement, structural-prevention

---

## Version History

- **2025-10-23**: Created from synthesis of multiple lessons

---

**File:** `NM06-Wisdom-Synthesized_WISD-01.md`
**End of Document**

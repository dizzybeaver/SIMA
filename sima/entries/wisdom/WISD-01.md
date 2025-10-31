# File: WISD-01.md

**REF-ID:** WISD-01  
**Category:** Generic Lessons  
**Type:** Synthesized Wisdom  
**Version:** 1.0.0  
**Created:** 2025-10-23  
**Updated:** 2025-10-30  
**Status:** Active

---

## Summary

Good architecture makes certain mistakes impossible through design rather than relying on developer discipline. Don't rely on rules and documentation; rely on architecture that prevents problems.

---

## The Pattern

**Traditional Approach:**
```
Write rules → Hope developers follow rules → Bugs when rules broken
```

**Wisdom Approach:**
```
Design architecture → Rules enforced by structure → Mistakes prevented
```

**Key Insight:** Architecture is more reliable than discipline.

---

## Why It Matters

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

---

## When to Apply

**Ask: "Can architecture prevent this problem?"**

- If yes → Design prevention into system
- If no → Use runtime checks as fallback

**Examples of architectural prevention:**
- Gateway pattern prevents circular imports
- Router sanitization prevents sentinel leaks
- Error boundaries prevent cascading failures
- Flat file structure prevents deep nesting bugs

---

## Examples

### Example 1: Circular Imports Prevention
```python
# Traditional approach (weak):
# Rule: "Don't create circular imports"
# Problem: Easy to break accidentally

# Architectural approach (strong):
# Gateway pattern makes circular imports impossible
# All imports go through gateway
# Core modules never import each other
```

### Example 2: Sentinel Leak Prevention
```python
# Traditional approach (weak):
# Rule: "Remember to convert sentinels to None"
# Problem: Easy to forget

# Architectural approach (strong):
# Router layer automatically sanitizes
# Core modules can use sentinels
# Users always receive None
```

### Example 3: Error Isolation
```python
# Traditional approach (weak):
# Rule: "Always use try/except for external calls"
# Problem: Easy to miss one

# Architectural approach (strong):
# Interface layer wraps all operations
# Error boundaries built into structure
# Can't accidentally skip error handling
```

---

## Universal Principle

**"Make the right thing easy and the wrong thing impossible"**

- Right thing: Use gateway for imports → Easy (just import gateway)
- Wrong thing: Direct core imports → Impossible (not exposed)

- Right thing: Get None for cache miss → Automatic (router sanitizes)
- Wrong thing: Get sentinel object → Impossible (sanitized away)

---

## Application Guidelines

1. **Identify recurring problems**
   - What mistakes happen repeatedly?
   - Can architecture prevent them?

2. **Design structural solutions**
   - Make wrong thing impossible
   - Make right thing easy
   - Enforce through structure

3. **Prefer architectural over procedural**
   - Architecture > Documentation
   - Structure > Rules
   - Prevention > Detection

4. **Test prevention**
   - Try to make the mistake
   - Verify it's actually impossible
   - Document why it works

---

## Related References

**Architecture:**
- ARCH-01: SUGA Pattern (architectural prevention example)

**Decisions:**
- DEC-01: Gateway pattern choice
- DEC-05: Sentinel sanitization

**Bugs:**
- BUG-01: Sentinel leak (prevented by router sanitization)
- BUG-02: Circular import (prevented by gateway pattern)
- BUG-03: Cascading failures (prevented by error boundaries)

**Lessons:**
- LESS-01: Gateway pattern prevents problems

**Wisdom:**
- WISD-03: Small costs early (prevention investment)

---

## Keywords

architecture, prevention, design, gateway-pattern, enforcement, structural-prevention, defensive-design

---

## Cross-References

**Synthesizes From:** LESS-01, BUG-01, BUG-02, BUG-03, DEC-01, ARCH-01  
**Related To:** WISD-03 (prevention investment), WISD-04 (consistent patterns)  
**Applied In:** All architecture decisions

---

**End of WISD-01**

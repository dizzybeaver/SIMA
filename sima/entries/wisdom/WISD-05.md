# File: WISD-05.md

**REF-ID:** WISD-05  
**Category:** Generic Lessons  
**Type:** Synthesized Wisdom  
**Version:** 1.0.0  
**Created:** 2025-10-23  
**Updated:** 2025-10-30  
**Status:** Active

---

## Summary

Memory fades, documentation persists. If it's not documented, it doesn't exist. Write down decisions, rationale, lessons, and context while you remember them. Future you (and others) will thank present you.

---

## The Pattern

**Undocumented Approach (amnesia):**
```
Make decision → Implement → Time passes →
"Why did we do it this way?" → No one remembers →
Re-litigate decision → Waste time → Repeat
```

**Documented Approach (institutional memory):**
```
Make decision → Document rationale → Implement →
Time passes → "Why did we do it this way?" →
Read documentation → Understand immediately → Move forward
```

**Key Insight:** Documentation is an investment in future efficiency.

---

## Why It Matters

**Human Memory:**
- Fades over weeks/months
- Becomes unreliable
- Lost when people leave
- Expensive to recreate

**Documentation:**
- Persists forever
- Searchable and shareable
- Transfers to new team members
- Prevents repeat mistakes

**Business Continuity:**
- Knowledge survives turnover
- Decisions remain traceable
- Context never lost
- Onboarding stays efficient

---

## When to Apply

**Always document:**
- Design decisions (and alternatives considered)
- Bugs and how they were fixed
- Lessons learned from experience
- Why code works the way it does
- Trade-offs accepted
- Future considerations

**Never skip documentation because:**
- "I'll remember" (you won't)
- "It's obvious" (it isn't)
- "No time now" (costs more later)
- "In the code" (code shows what, not why)

---

## Examples

### Example 1: Design Decisions
```markdown
# Decision: No Threading Locks

Decision: Lambda functions don't use threading locks

Rationale:
- Lambda is single-threaded execution model
- Locks add overhead with no benefit
- False sense of thread-safety

Alternatives Considered:
- Add locks "just in case" - Rejected (unnecessary overhead)
- Use threadlocal - Rejected (Lambda model doesn't support)

Trade-offs:
- Accept: Code won't work in multi-threaded context
- Benefit: Simpler code, better performance

Future: If Lambda ever supports true threading, revisit
```

### Example 2: Bug Post-Mortems
```markdown
# BUG-01: Sentinel Leak

What went wrong: Sentinel object leaked to users
Root cause: No sanitization at router boundary
How fixed: Added sanitization layer
Prevention: Always sanitize internal objects at boundaries

Lesson: Infrastructure concerns belong in router, not core
```

### Example 3: SIMA Neural Maps
```markdown
# The documentation system itself

Problem: Lost institutional knowledge
Solution: Neural map architecture
Result: Persistent, searchable, cross-referenced knowledge

Meta-lesson: This file exists because WISD-05
```

---

## Universal Principle

**"If it's not documented, it doesn't exist"**

**Code says WHAT:**
```python
def execute_operation(interface, operation, **kwargs):
    return router(operation, **kwargs)
```

**Documentation says WHY:**
```python
"""
Gateway pattern routes all operations through central hub.

Why: Prevents circular imports (BUG-02)
Why: Enables consistent testing (LESS-04)
Why: Centralizes error handling (BUG-03)

See: DEC-01 (Gateway pattern choice)
See: ARCH-01 (Gateway trinity)
"""
```

---

## What to Document

**1. Design Decisions:**
- What we chose
- Why we chose it
- Alternatives considered
- Trade-offs accepted
- REF-ID: DEC-##

**2. Bugs and Fixes:**
- What went wrong
- Root cause analysis
- How it was fixed
- How to prevent recurrence
- REF-ID: BUG-##

**3. Lessons Learned:**
- What we discovered
- Impact on architecture
- Changes made
- Future considerations
- REF-ID: LESS-##

**4. Architecture Patterns:**
- How system works
- Why designed this way
- How to extend
- Common pitfalls
- REF-ID: ARCH-##

---

## Documentation Hierarchy

**Level 1: Neural Maps**
- Purpose: Architecture, decisions, lessons
- Audience: Anyone working on system
- Update: When architecture changes
- Format: Markdown with REF-IDs

**Level 2: README**
- Purpose: Setup, deployment, usage
- Audience: New users/developers
- Update: When procedures change
- Format: Markdown

**Level 3: Docstrings**
- Purpose: API contracts
- Audience: Function callers
- Update: When API changes
- Format: Python docstrings

**Level 4: Comments**
- Purpose: Tricky logic
- Audience: Code maintainers
- Update: When logic changes
- Format: Inline comments

---

## Documentation ROI

**Time Investment:**
- Write documentation: 15-30 minutes
- Total time spent: 15-30 minutes

**Time Saved:**
- Future debugging: Hours
- Re-learning context: Hours
- Re-litigating decisions: Hours
- Total saved: Many hours

**ROI: Usually 10-100x**

---

## Related References

**Lessons:**
- LESS-11: Design decisions must be documented
- LESS-12: Code comments vs external docs
- LESS-13: Architecture must be teachable

**Decisions:**
- All DEC-## files (examples of documentation)

**Wisdom:**
- WISD-02: Measure don't guess (document measurements)
- WISD-04: Consistency over cleverness (document patterns)

---

## Keywords

documentation, institutional-memory, knowledge-transfer, rationale, context, decision-history, REF-ID

---

## Cross-References

**Synthesizes From:** LESS-11, LESS-12, LESS-13  
**Related To:** WISD-02 (documenting measurements), WISD-04 (documenting patterns)  
**Applied In:** All design decisions, bug fixes, lessons learned

---

**End of WISD-05**

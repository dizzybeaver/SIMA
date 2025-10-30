# NM05-AntiPatterns-Documentation_Index.md

# Anti-Patterns - Documentation Index

**Category:** NM05 - Anti-Patterns
**Topic:** Documentation
**Items:** 2
**Last Updated:** 2025-10-24

---

## Topic Overview

**Description:** Anti-patterns related to code documentation covering both the complete absence of docstrings and the presence of outdated/misleading comments. Documentation is the contract between current code and future maintainers - these patterns break that contract through negligence or neglect.

**Keywords:** documentation, docstrings, comments, maintainability, knowledge transfer, technical debt

**Priority Distribution:** 2 Low

---

## Individual Files

### AP-25: No Docstrings
- **File:** `NM05-AntiPatterns-Documentation_AP-25.md`
- **Summary:** Document all public functions, classes, and complex logic with docstrings
- **Priority:** Low
- **Impact:** Knowledge loss, onboarding difficulty, usage errors, maintenance friction

### AP-26: Outdated Comments
- **File:** `NM05-AntiPatterns-Documentation_AP-26.md`
- **Summary:** Update comments when code changes - outdated docs worse than none
- **Priority:** Low
- **Impact:** Misleading guidance, incorrect assumptions, debugging confusion, wasted time

---

## Common Themes

Both documentation anti-patterns represent **knowledge debt**. AP-25 is never documenting in the first place. AP-26 is documenting once but never maintaining it. Both result in the same outcome: future maintainers lacking the information needed to work confidently with the code.

**The Documentation Paradox:**
- When code is new: "It's obvious, no docs needed"
- Six months later: "Why did we do it this way?"
- Outdated docs: "This says X but code does Y"
- Future developer: "Can't trust any of this"

**Worse Than Nothing:**
Outdated documentation (AP-26) is often more harmful than no documentation (AP-25):

```python
# âš ï¸ Misleading (Outdated)
def process_user_data(data):
    """Returns user object."""  # LIES - now returns tuple
    return (user, metadata, timestamp)  # Changed 6 months ago

# âœ… Honest (No docs)
def process_user_data(data):
    return (user, metadata, timestamp)  # At least not lying
```

With no docs, you check the code. With wrong docs, you trust them and get burned.

**Documentation as Architecture:**
This project's SIMA v3 neural maps represent the gold standard for documentation:
- Every decision documented with rationale
- Clear structure (Gateway → Category → Topic → Individual)
- Regular updates (not "write once, abandon forever")
- Living documentation that evolves with code

The same principles apply to code-level documentation.

---

## Related Topics

**Within NM05 (Anti-Patterns):**
- Quality patterns (AP-20 to AP-22) - Documentation is part of code quality
- Testing patterns (AP-23, AP-24) - Tests serve as executable documentation

**Other Categories:**
- **NM00-Guidelines** - Documentation Standards (comprehensive guidelines)
- **NM06-Lessons** - LESS-11 to LESS-13 (Documentation lessons)
- **NM01-Architecture** - ARCH-09 (SIMA v3 documentation architecture)

**Project Documentation Standards:**
The project maintains high documentation standards:
- Comprehensive neural maps (SIMA v3 architecture)
- Detailed function docstrings in core modules
- Architecture decision records (NM04 category)
- Anti-patterns checklist (this category)

These demonstrate the expected level of documentation throughout the codebase.

---

**Navigation:**
- **Up:** [Category Index - NM05-AntiPatterns_Index.md]
- **Siblings:** [Import_Index, Implementation_Index, Dependencies_Index, Critical_Index, Concurrency_Index, Performance_Index, ErrorHandling_Index, Security_Index, Quality_Index, Testing_Index, Process_Index]

---

**End of Index**

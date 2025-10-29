# [PROJECT]-CONS-[NN]: [CONSTRAINT_NAME]

**REF-ID:** [PROJECT]-CONS-[NN]  
**Version:** 1.0.0  
**Category:** NMP01 - Constraints  
**Status:** ✅ Active / ⏳ Draft / 📦 Archived  
**Created:** [YYYY-MM-DD]  
**Last Updated:** [YYYY-MM-DD]

---

## 📋 CONSTRAINT SUMMARY

**One-Line Description:**  
[Single sentence capturing the essence of this constraint]

**Severity:** 🔴 Critical / 🟡 Important / 🟢 Advisory

**Scope:**
- **Component(s):** [List affected components]
- **Layer(s):** [Gateway/Interface/Core/All]
- **Frequency:** [How often this applies: Always/Conditional/Rare]

**Tags:** [tag1, tag2, tag3, tag4]

---

## 🎯 CONSTRAINT STATEMENT

**What You MUST Do:**
[Clear, imperative statement of what is required]

**What You MUST NOT Do:**
[Clear, imperative statement of what is prohibited]

**Context:**
[Explanation of when and where this constraint applies]

---

## 🔗 INHERITANCE

**Inherits From:**
- **[ARCH-CODE]**: [Architecture name]
  - **Specific Constraint:** [Which constraint from the architecture]
  - **File:** `/sima/entries/architectures/[ARCH-CODE]/[file].md`
  
- **[LANG-CODE]**: [Language name]
  - **Specific Constraint:** [Which constraint from the language]
  - **File:** `/sima/entries/languages/[LANG-CODE]/[file].md`

**Or:** None (project-specific constraint)

**Delta (What's Different Here):**
[Explain how this project-specific constraint differs from or extends inherited constraints]

---

## 📝 DETAILED EXPLANATION

### Why This Constraint Exists

**Technical Reasons:**
- [Technical reason 1]
- [Technical reason 2]
- [Technical reason 3]

**Business/Operational Reasons:**
- [Business reason 1]
- [Business reason 2]

**Historical Context:**
[How this constraint came to be - lessons learned, incidents, etc.]

### How This Affects Architecture

**Impact on Design:**
[How this constraint shapes architectural decisions]

**Impact on Implementation:**
[How this affects code structure and patterns]

**Impact on Operations:**
[How this affects deployment, monitoring, maintenance]

---

## 💻 IMPLEMENTATION GUIDANCE

### Correct Implementation

**Pattern to Follow:**
```[language]
# CORRECT - Follows constraint
[code example showing correct approach]

# Key points:
# 1. [Explanation of key aspect]
# 2. [Explanation of key aspect]
# 3. [Explanation of key aspect]
```

**Checklist:**
- ☑ [Check item 1]
- ☑ [Check item 2]
- ☑ [Check item 3]
- ☑ [Check item 4]

### Incorrect Implementation

**Anti-Pattern to Avoid:**
```[language]
# WRONG - Violates constraint
[code example showing violation]

# Why this is wrong:
# 1. [Explanation of problem]
# 2. [Explanation of problem]
# 3. [Explanation of problem]
```

**Common Mistakes:**
1. **[Mistake 1]**: [Description and why it happens]
2. **[Mistake 2]**: [Description and why it happens]
3. **[Mistake 3]**: [Description and why it happens]

---

## ⚠️ CONSEQUENCES OF VIOLATION

### Technical Consequences
- **Performance:** [Impact on performance]
- **Memory:** [Impact on memory usage]
- **Scalability:** [Impact on scaling]
- **Reliability:** [Impact on system reliability]

### Operational Consequences
- **Deployment:** [Impact on deployment]
- **Monitoring:** [Impact on observability]
- **Debugging:** [Impact on troubleshooting]
- **Maintenance:** [Impact on ongoing maintenance]

### Real Examples
**Example 1:** [Brief description of actual violation and its cost]
- **Impact:** [Specific measurable impact]
- **Resolution Time:** [How long to fix]
- **Prevention:** [Related LESS-ID or pattern]

---

## 🔍 DETECTION & VERIFICATION

### How to Detect Violations

**Automated Detection:**
```[language/tool]
# Linter rule / test / check
[code or configuration for automated check]
```

**Manual Inspection:**
- Look for: [Pattern to watch for]
- Check: [Specific files or areas]
- Verify: [What to verify]

### Testing Strategy

**Unit Tests:**
```[language]
# Test that verifies constraint compliance
[test code example]
```

**Integration Tests:**
```[language]
# Test that checks constraint across components
[test code example]
```

**Validation Checklist:**
- ☑ [Validation item 1]
- ☑ [Validation item 2]
- ☑ [Validation item 3]

---

## 🔄 EXCEPTIONS & EDGE CASES

### When This Constraint Doesn't Apply

**Exception 1: [SCENARIO_NAME]**
- **Condition:** [When this exception applies]
- **Justification:** [Why exception is allowed]
- **Alternative Approach:** [What to do instead]
- **Approval Required:** Yes/No

**Exception 2: [SCENARIO_NAME]**
- **Condition:** [When this exception applies]
- **Justification:** [Why exception is allowed]
- **Alternative Approach:** [What to do instead]
- **Approval Required:** Yes/No

### Edge Cases

**Edge Case 1: [SCENARIO_NAME]**
- **Description:** [What makes this tricky]
- **Approach:** [How to handle it]
- **Verification:** [How to verify it's correct]

---

## 🔗 RELATIONSHIPS

### Related Constraints
- **[CONS-ID]**: [How they relate]
- **[CONS-ID]**: [How they relate]

### Used By Combinations
- **[COMB-ID]**: [How this constraint is part of combination]
- **[COMB-ID]**: [How this constraint is part of combination]

### Informed By Lessons
- **[LESS-ID]**: [What lesson led to this constraint]
- **[BUG-ID]**: [What bug this prevents]

### Supports Decisions
- **[DEC-ID]**: [How this constraint supports the decision]
- **[DEC-ID]**: [How this constraint supports the decision]

---

## 📊 METRICS & MONITORING

### Compliance Metrics

**How to Measure:**
[Description of how to measure compliance with this constraint]

**Target:** [Target compliance percentage]

**Current Status:** [If known]

### Performance Impact

**Overhead:** [Performance cost of following this constraint]

**Benefit:** [Performance gain or stability improvement]

**Trade-offs:** [What you give up vs what you gain]

---

## 🧪 EXAMPLES

### Example 1: [SCENARIO_NAME]

**Context:**
[Description of the scenario]

**Implementation:**
```[language]
# Complete example showing constraint in practice
[code example]
```

**Key Points:**
1. [What this example demonstrates]
2. [Important aspect to notice]
3. [How it differs from anti-pattern]

### Example 2: [SCENARIO_NAME]

**Context:**
[Description of the scenario]

**Implementation:**
```[language]
# Complete example showing constraint in practice
[code example]
```

**Key Points:**
1. [What this example demonstrates]
2. [Important aspect to notice]
3. [How it differs from anti-pattern]

---

## 🛠️ TOOLS & AUTOMATION

### Linting Rules
```[tool-config]
# Configuration for linter to enforce this constraint
[config]
```

### Pre-commit Hooks
```[language]
# Script to check constraint before commit
[script]
```

### CI/CD Checks
```[tool-config]
# Pipeline check for this constraint
[config]
```

---

## 📚 REFERENCES

### Internal References
- **Architecture:** `/sima/entries/architectures/[ARCH-CODE]/`
- **Language:** `/sima/entries/languages/[LANG-CODE]/`
- **Master Index:** `/projects/[PROJECT]/sima/nmp/NMP00/NMP00-Master-Index.md`

### External References
- **Specification:** [URL to relevant spec]
- **Documentation:** [URL to docs]
- **Best Practices:** [URL to best practices guide]

### Related Discussions
- **Design Decision:** [DEC-ID or link]
- **Incident Report:** [If applicable]
- **Team Discussion:** [Link to discussion if available]

---

## 🤝 CONTRIBUTORS

**Original Author:** [Name]  
**Contributors:**
- [Name] - [Contribution type]
- [Name] - [Contribution type]

**Last Reviewed By:** [Name]  
**Review Date:** [YYYY-MM-DD]

---

## 📝 CHANGE LOG

### [X.Y.Z] - YYYY-MM-DD
**Changed:** [What changed]  
**Reason:** [Why it changed]  
**Impact:** [Who/what is affected]

### [X.Y.Z] - YYYY-MM-DD
**Changed:** [What changed]  
**Reason:** [Why it changed]  
**Impact:** [Who/what is affected]

---

## 🔐 VERIFICATION

**Last Verified:** [YYYY-MM-DD]

**Verification Checklist:**
- ☑ Constraint statement is clear and actionable
- ☑ Examples are complete and tested
- ☑ Related entries are cross-referenced
- ☑ Inheritance is correctly documented
- ☑ Detection methods are provided
- ☑ Consequences are clearly stated

**Verification Method:**
[How this constraint compliance was verified]

---

**END OF CONSTRAINT ENTRY**

**REF-ID:** [PROJECT]-CONS-[NN]  
**Template Version:** 1.0.0  
**Entry Type:** Constraint  
**Status:** [Active/Draft/Archived]  
**Next Review:** [YYYY-MM-DD] (90 days from last update)

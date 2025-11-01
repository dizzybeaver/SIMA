# LESS-23.md

# LESS-23: Question "Intentional" Design Decisions

**Category:** Lessons  
**Topic:** Operations  
**Priority:** CRITICAL  
**Status:** Active  
**Created:** 2025-10-30  
**Path:** `/sima/entries/lessons/operations/LESS-23.md`

---

## Summary

Always verify documented "intentional" design decisions against anti-patterns and architectural principles. Documentation claiming "deliberate" choices may rationalize violations rather than justify sound engineering.

---

## Pattern

### The Problem

**Common Rationalization:**
```
"DESIGN DECISION: Uses [anti-pattern] despite [constraint]"
Reason: Future-proofing for [hypothetical scenario]
NOT A BUG: Intentional [bad practice] for [vague benefit]
```

**Reality:**
- Rationalized violations compound over time
- Technical debt disguised as engineering decisions
- No evidence benefits materialize
- Systematic verification prevents this drift

### Real Example

**Documented as "Intentional":**
```python
class Component:
    def __init__(self):
        # DESIGN DECISION: threading.Lock() for thread safety
        # Reason: Future-proofing for multi-threaded environments
        # NOT A BUG: Defensive programming for portability
        self._lock = threading.Lock()
```

**Actual Issues:**
- Violates single-threaded architecture principle
- Adds overhead without benefit
- Misunderstands execution environment
- No concrete use case provided
- Missing supporting documentation

---

## Solution

### Verification Protocol

**Before Accepting "Intentional" Decisions:**

```
Step 1: Search anti-patterns
└─ Query: "[pattern] anti-pattern"
└─ Found? → Likely violation

Step 2: Check design decisions
└─ Query: "[topic] decision"
└─ Conflicts? → Violation confirmed

Step 3: Verify documentation
└─ Has citations? → Likely valid
└─ No citations? → Likely rationalization

Step 4: Assess benefit
└─ Concrete evidence? → May be valid
└─ Vague claims? → Likely violation

Step 5: Decision
└─ If doubt exists → Remove violation
└─ Document why it's wrong
└─ Update with correct approach
```

### Common Rationalizations

| Claim | Reality | Action |
|-------|---------|--------|
| "Future-proofing" | Premature optimization | Remove |
| "Defensive programming" | Adds complexity | Remove |
| "Portability" | YAGNI violation | Remove |
| "Best practice" | From wrong context | Remove |
| "Industry standard" | For different domain | Remove |

### Why Documentation Lies

**Cognitive Biases:**
1. **Sunk Cost**: "We already wrote it"
2. **Confirmation**: "It must be right because we chose it"
3. **Authority**: "Senior dev said so"
4. **Rationalization**: Easier than admitting mistake

**Organizational Pressures:**
1. Deadline pressure prevents fixes
2. "Works for now" mentality
3. Fear of admitting error
4. Lack of verification culture

### The Correct Approach

**Verification Pattern:**
```python
def verify_design_decision(code, docs):
    # 1. Check anti-patterns
    violations = check_anti_patterns(code)
    if violations:
        return "VIOLATION - Remove despite docs"
    
    # 2. Verify against principles
    conflicts = check_design_principles(code)
    if conflicts:
        return "CONFLICT - Fix and update docs"
    
    # 3. Require evidence
    if not has_supporting_documentation(docs):
        return "UNSUPPORTED - Need verification"
    
    # 4. If all pass
    return "VALID - Documented correctly"
```

---

## Impact

### Real-World Pattern

**Example Discovery:**
```
Finding: 4 of 12 interfaces had threading locks (33%)
Documentation: All marked as "intentional"
Verification: All were actual violations
Result: All removed during optimization
Impact: 0 negative effects from removal
```

**Lesson:** 100% of "intentional" threading locks were violations. Documentation rationalized rather than justified.

### Prevention Value

**Before Systematic Verification:**
- Violations persist indefinitely
- Technical debt accumulates
- "Intentional" becomes excuse
- Quality degrades over time

**After Systematic Verification:**
- Violations caught early
- Technical debt prevented
- Standards maintained
- Quality improves over time

---

## Best Practices

### Create Verification Culture

**1. Automated Checks**
```yaml
# CI/CD Pipeline
- name: Validate Anti-Patterns
  run: check_anti_patterns.py
  # No "intentional" bypass without documentation
```

**2. Documentation Requirements**
```markdown
## Design Decision Template
- Pattern used: [specific pattern]
- Rationale: [concrete reason]
- Evidence: [supporting data]
- Alternatives considered: [options evaluated]
- Documentation references: [citations]
- Review date: [when to re-evaluate]
```

**3. Cultural Norms**
```
- Question everything
- Trust but verify
- Anti-patterns trump "intentions"
- Evidence over authority
- Make it safe to admit mistakes
```

**4. Review Checklist**
```
☑ Checked anti-pattern list?
☑ Verified design principles?
☑ Documentation cited?
☑ Concrete evidence provided?
☑ Passed verification?
```

### Application Guidelines

**For Code Reviewers:**
- Don't accept "intentional" without verification
- Require anti-pattern checks
- Demand supporting documentation
- Verify against architecture
- Ask for concrete evidence

**For Developers:**
- Check anti-patterns before claiming "intentional"
- Cite documentation sources
- Provide concrete evidence
- Be willing to remove violations
- Document actual reasons, not rationalizations

**For Architects:**
- Create clear anti-pattern list
- Document design decisions thoroughly
- Build verification into process
- Make it safe to admit mistakes
- Review "intentional" decisions periodically

---

## Key Insight

**Documentation can be wrong. Code can be wrong. Anti-patterns are the standard.**

When "intentional" decisions conflict with anti-patterns, the anti-patterns are correct 99% of the time. The 1% exception requires extraordinary evidence.

**Red Flags:**
- "Future-proofing" without concrete scenario
- "Defensive programming" adding complexity
- "Best practice" from different context
- No supporting documentation
- Vague benefits claimed
- No evidence of actual need

**Valid Exceptions Need:**
- Concrete use case
- Supporting documentation
- Evidence of benefit
- Alternatives evaluated
- Regular review scheduled
- Clear expiration criteria

---

## Related Topics

- **Anti-Patterns**: Standard to verify against
- **Design Principles**: Architecture standards
- **Technical Debt**: Prevention through verification
- **Code Review**: Quality gate implementation

---

## Keywords

intentional-decisions, rationalization, verification, anti-patterns, documentation-drift, technical-debt, defensive-programming, future-proofing

---

## Version History

- **2025-10-30**: Genericized for SIMAv4 - Removed project-specific details
- **2025-10-25**: Created - Documented verification protocol

---

**File:** `LESS-23.md`  
**Location:** `/sima/entries/lessons/operations/`  
**Status:** Active

---

**End of Document**

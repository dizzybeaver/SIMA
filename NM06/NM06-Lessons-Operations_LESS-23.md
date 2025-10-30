# NM06-Lessons-Operations_LESS-23.md

# Question "Intentional" Design Decisions

**REF:** NM06-LESS-23  
**Category:** Lessons  
**Topic:** Operations  
**Priority:** 🔴 CRITICAL  
**Status:** Active  
**Created:** 2025-10-25  
**Source:** Session 5 optimization

---

## Summary

Always verify documented "intentional" design decisions against anti-patterns and architectural principles. Documentation claiming "deliberate" choices may rationalize violations rather than justify sound engineering.

---

## Context

**Universal Pattern:**
Teams often document anti-pattern violations as "intentional" to avoid addressing them. Common rationalizations include "future-proofing," "defensive programming," or "portability"—typically without evidence these benefits materialize.

**Why This Matters:**
Rationalized violations compound over time, creating technical debt disguised as engineering decisions. Systematic verification prevents this drift.

---

## Content

### The Discovery Pattern

**Documentation Red Flags:**
```
"DESIGN DECISION: Uses [anti-pattern] despite [constraint]"
Reason: Future-proofing for [hypothetical scenario]
NOT A BUG: Intentional [bad practice] for [vague benefit]
```

**Reality Check Protocol:**
1. Check anti-pattern list (is this AP-##?)
2. Verify against design decisions (conflicts with DEC-##?)
3. Look for supporting REF-IDs (citations present?)
4. If conflict exists, assume anti-pattern is correct
5. Remove violation, update documentation

### Real Example

**Documented as "Intentional":**
```python
class CircuitBreaker:
    def __init__(self):
        # DESIGN DECISION: threading.Lock() for thread safety
        # Reason: Future-proofing for multi-threaded environments
        # NOT A BUG: Defensive programming for portability
        self._lock = threading.Lock()
```

**Actual Violations:**
- Violates AP-08 (No threading locks)
- Violates DEC-04 (Lambda single-threaded model)
- Adds overhead without benefit
- Misunderstands execution environment

**Root Cause:**
- "Future-proofing" without concrete use case
- Documentation justifies rather than explains
- No REF-ID citations to support claim
- Lacks verification against established patterns

### Verification Protocol

**Before accepting "intentional" decisions:**

```
Step 1: Search anti-patterns
└─ Query: "[pattern] anti-pattern"
└─ Found? → Likely violation

Step 2: Check design decisions
└─ Query: "[topic] decision DEC"
└─ Conflicts? → Violation confirmed

Step 3: Verify REF-IDs
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
1. **Sunk cost:** "We already wrote it"
2. **Confirmation:** "It must be right because we chose it"
3. **Authority:** "Senior dev said so"
4. **Rationalization:** Easier than admitting mistake

**Organizational Pressures:**
1. Deadline pressure prevents fixes
2. "Works for now" mentality
3. Fear of admitting error
4. Lack of verification culture

### The Correct Approach

**When encountering "intentional" decisions:**

```python
# âŒ Wrong: Accept at face value
# "Documentation says intentional, so it's fine"

# âœ… Right: Verify against standards
def verify_design_decision(code, docs):
    # 1. Check anti-patterns
    violations = check_anti_patterns(code)
    if violations:
        return "VIOLATION - Remove despite docs"
    
    # 2. Verify against principles
    conflicts = check_design_decisions(code)
    if conflicts:
        return "CONFLICT - Fix and update docs"
    
    # 3. Require evidence
    if not has_ref_id_citations(docs):
        return "UNSUPPORTED - Need verification"
    
    # 4. If all pass
    return "VALID - Documented correctly"
```

### Impact

**In Production:**
- 4 of 12 interfaces had threading locks (33%)
- All documented as "intentional"
- All were actual violations
- All removed during optimization
- 0 negative impact from removal

**Pattern:**
100% of "intentional" threading locks were violations. Documentation rationalized rather than justified.

### Prevention

**Create verification culture:**

1. **Automated Checks**
   - CI/CD scans for anti-patterns
   - Block merges with violations
   - No "intentional" bypass without REF-ID

2. **Documentation Requirements**
   - Must cite REF-IDs
   - Must show concrete benefit
   - Must pass anti-pattern check
   - Reviewed before acceptance

3. **Cultural Norms**
   - Question everything
   - Trust but verify
   - Anti-patterns trump "intentions"
   - Evidence over authority

4. **Review Checklist**
   ```
   â˜ Checked anti-pattern list?
   â˜ Verified design decisions?
   â˜ REF-IDs cited?
   â˜ Concrete evidence provided?
   â˜ Passed verification?
   ```

### Application Guidelines

**For Code Reviewers:**
- Don't accept "intentional" without verification
- Require anti-pattern checks
- Demand REF-ID citations
- Verify against architecture

**For Developers:**
- Check anti-patterns before claiming "intentional"
- Cite REF-IDs in documentation
- Provide concrete evidence
- Be willing to remove violations

**For Architects:**
- Create clear anti-pattern list
- Document design decisions with REF-IDs
- Build verification into process
- Make it safe to admit mistakes

### Key Insight

**Documentation can be wrong. Code can be wrong. Anti-patterns are the standard.**

When "intentional" decisions conflict with anti-patterns, the anti-patterns are correct 99% of the time. The 1% exception requires extraordinary evidence.

---

## Related Topics

- **AP-08**: No threading locks (common violation)
- **DEC-04**: Lambda single-threaded model
- **LESS-17**: Threading locks unnecessary
- **LESS-29**: Zero tolerance for anti-patterns
- **Verification**: LESS-15 protocol

---

## Keywords

intentional-decisions, rationalization, verification, anti-patterns, documentation-drift, technical-debt, defensive-programming, future-proofing, code-review

---

## Version History

- **2025-10-25**: Created - Genericized from Session 5 CIRCUIT_BREAKER discovery
- **Source**: 4 of 12 interfaces had "intentional" threading locks, all were violations

---

**File:** `NM06-Lessons-Operations_LESS-23.md`  
**Topic:** Operations  
**Priority:** CRITICAL (prevents rationalized violations)

---

**End of Document**

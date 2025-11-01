# Meta-Index.md

**Category:** Decision Logic  
**Subcategory:** Meta  
**Files:** 1  
**Last Updated:** 2024-10-30

---

## Overview

Framework for making good decisions - meta-level guidance on decision-making methodology, balancing trade-offs, and learning from outcomes.

---

## Files in This Category

### META-01: Meta Decision-Making Framework

**File:** `META-01.md`  
**REF-ID:** META-01  
**Priority:** Framework  
**Status:** Active

**Summary:** Comprehensive framework for making good decisions - understanding context, gathering data, evaluating alternatives, documenting rationale, and learning from outcomes.

**6-Step Process:**
1. Understand the Context
2. Gather Data
3. Consider Alternatives
4. Evaluate Trade-offs
5. Make Decision
6. Learn and Iterate

**Use When:**
- Facing new decision types
- Existing decision trees don't apply
- Need to create new decision tree
- Training team on decision-making
- Reviewing past decisions

---

## Quick Guide

### When to Use This Framework

**Primary Uses:**
- **No Existing Decision Tree:** Novel situation not covered by DT-##
- **Creating New Decision Tree:** Systematic approach for new patterns
- **Training:** Teaching decision-making methodology
- **Review:** Evaluating past decisions

**Secondary Uses:**
- **Complex Decisions:** Multi-faceted trade-offs
- **High-Stakes Decisions:** Architecture, breaking changes
- **Uncertain Situations:** Limited data, novel approaches

### How to Apply Framework

**Step-by-Step:**
```
1. Start with context questions:
   - What are constraints?
   - What are goals?
   - Who is affected?

2. Gather data systematically:
   - Measure (don't guess)
   - Search neural maps
   - Research best practices

3. List alternatives (minimum 3):
   - Include "do nothing"
   - Consider hybrids

4. Evaluate trade-offs:
   - Performance vs Maintainability
   - Simplicity vs Capability
   - Short-term vs Long-term

5. Make and document decision:
   - Choose based on context + data
   - Document rationale
   - Set success criteria

6. Learn and iterate:
   - Monitor outcome
   - Document lessons
   - Update decision trees
```

---

## Integration with Other Decision Logic

### Framework Guides All Decisions

**META-01 is the foundation for:**
- **DT-## (Decision Trees):** Specific applications of framework
- **FW-## (Frameworks):** Focused trade-off analysis
- **DEC-## (Decisions):** Documented outcomes using framework

**Relationship:**
```
META-01 (How to decide)
    â†"
DT-## and FW-## (Specific decision patterns)
    â†"
DEC-## (Documented decisions)
    â†"
LESS-## (Lessons learned)
    â†"
Updated DT-## (Improved decision trees)
```

### When Decision Trees Insufficient

**If no DT-## applies:**
1. Use META-01 framework directly
2. Document decision process
3. Extract patterns for new DT-##
4. Contribute to neural maps

**Example:**
```
Novel situation: Choose between experimental approaches
â†' Use META-01 to make decision
â†' Document thoroughly
â†' Create DT-14 (if pattern repeats)
```

---

## Key Principles

### From META-01 Framework:

**Consistency:**
- Use same methodology across decisions
- Document similarly
- Learn systematically

**Data-Driven:**
- Measure, don't guess
- Search for precedents
- Validate assumptions

**Explicit Trade-offs:**
- Performance vs Maintainability
- Simplicity vs Capability
- Short-term vs Long-term
- Cost vs Benefit

**Documentation:**
- Record rationale
- Set success criteria
- Track outcomes

**Iteration:**
- Monitor results
- Learn from outcomes
- Update decision trees

---

## Related Categories

**Within Decision Logic:**
- **All DT-##**: Specific decision trees (apply META-01)
- **All FW-##**: Decision frameworks (focused trade-offs)

**Other Categories:**
- **NM04-Decisions** (DEC-##): Documented decisions using this framework
- **NM06-Lessons** (LESS-##): Learning from decision outcomes
- **NM05-AntiPatterns** (AP-##): What not to do when deciding

---

## Common Questions

**Q: When should I use META-01 vs specific decision trees?**
**A:** Use specific DT-## when available (faster, proven). Use META-01 for novel situations or when creating new decision patterns.

**Q: How detailed should decision documentation be?**
**A:** Include: context, data, alternatives considered, trade-offs evaluated, rationale for choice, success criteria. See META-01 examples.

**Q: What if my decision turns out wrong?**
**A:** Document lessons (LESS-##), understand why, update decision tree. Wrong decisions with good process are learning opportunities.

**Q: Should I always use all 6 steps?**
**A:** For simple decisions, some steps can be brief. For complex/high-stakes decisions, follow all steps thoroughly.

**Q: How do I know if decision was good?**
**A:** Compare outcome to success criteria set in Step 5. Good decisions follow good process, even if outcome varies.

---

## Keywords

meta-framework, decision methodology, decision-making process, systematic decisions, trade-off analysis, decision documentation, learning from decisions, decision quality, iterative improvement

---

## Navigation

**Parent:** Decision Logic Master Index  
**Siblings:** Import, Feature Addition, Error Handling, Testing, Optimization, Refactoring, Deployment, Architecture

**Location:** `/sima/entries/decisions/meta/`

---

**End of Index**

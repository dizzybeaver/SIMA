# File: decision_log_template.md

**Template Version:** 1.0.0  
**Purpose:** Project decision log template  
**Location:** `/sima/projects/templates/`

---

# File: [PROJECT]-DEC-##-[Decision-Name].md

**REF-ID:** [PROJECT]-DEC-##  
**Category:** Project Decision  
**Type:** Architecture | Technical | Operational  
**Project:** [Project Name] ([PROJECT_CODE])  
**Status:** Active | Superseded | Deprecated  
**Date Decided:** YYYY-MM-DD  
**Last Reviewed:** YYYY-MM-DD

---

## ðŸ"‹ DECISION SUMMARY

[1-2 sentence summary of the decision made]

**Decision:** [What was chosen]  
**Impact Level:** Critical | High | Medium | Low  
**Reversibility:** Easy | Moderate | Difficult | Irreversible

---

## ðŸŽ¯ CONTEXT

### Problem Statement
[What problem needed solving? 2-3 sentences]

### Background
- Context point 1
- Context point 2
- Context point 3

### Constraints
- Constraint 1 (e.g., budget, timeline, technology)
- Constraint 2
- Constraint 3

### Requirements
- Must have: Requirement 1
- Must have: Requirement 2
- Nice to have: Requirement 3

---

## ðŸ'¡ DECISION

### What We Chose
[Detailed description of the decision made]

### Rationale
**Why this choice:**
1. Reason 1 - [Explanation]
2. Reason 2 - [Explanation]
3. Reason 3 - [Explanation]

### Key Factors
- Factor 1: [Weight: High/Medium/Low] - [Why it mattered]
- Factor 2: [Weight] - [Why it mattered]
- Factor 3: [Weight] - [Why it mattered]

---

## ðŸ"„ ALTERNATIVES CONSIDERED

### Alternative 1: [Name]
**Description:** [What it was]

**Pros:**
- Pro 1
- Pro 2

**Cons:**
- Con 1
- Con 2

**Why Rejected:** [Reason]

---

### Alternative 2: [Name]
**Description:** [What it was]

**Pros:**
- Pro 1
- Pro 2

**Cons:**
- Con 1
- Con 2

**Why Rejected:** [Reason]

---

### Alternative 3: [Name]
**Description:** [What it was]

**Pros:**
- Pro 1

**Cons:**
- Con 1

**Why Rejected:** [Reason]

---

## âš–ï¸ TRADE-OFFS

### What We Gained
1. **Benefit 1:** [Explanation]
2. **Benefit 2:** [Explanation]
3. **Benefit 3:** [Explanation]

### What We Accepted
1. **Trade-off 1:** [What we gave up and why it's acceptable]
2. **Trade-off 2:** [What we gave up and why it's acceptable]
3. **Trade-off 3:** [What we gave up and why it's acceptable]

### Risks
**Identified Risks:**
- Risk 1: [Probability: High/Medium/Low] - [Mitigation]
- Risk 2: [Probability] - [Mitigation]
- Risk 3: [Probability] - [Mitigation]

---

## ðŸ"Š IMPACT ANALYSIS

### Technical Impact
**Code Changes:**
- Impact area 1: [Description]
- Impact area 2: [Description]

**Architecture:**
- Change 1: [How architecture affected]
- Change 2: [How architecture affected]

**Performance:**
- Expected impact: [Better/Worse/Neutral] - [Metrics]

---

### Operational Impact
**Deployment:**
- Change 1: [What's different]
- Change 2: [What's different]

**Monitoring:**
- New metrics: [List]
- Changed alerts: [List]

**Maintenance:**
- Complexity: [Increase/Decrease/Same] - [Explanation]
- Effort: [More/Less/Same] - [Explanation]

---

### Business Impact
**Cost:**
- One-time: [Amount/estimate]
- Recurring: [Amount/estimate]
- Savings: [Amount/estimate]

**Timeline:**
- Implementation: [Duration]
- Rollout: [Duration]

**Value:**
- Benefit 1: [Quantify if possible]
- Benefit 2: [Quantify if possible]

---

## ðŸ"® FUTURE CONSIDERATIONS

### When to Revisit
- Trigger 1: [Condition that would prompt review]
- Trigger 2: [Condition that would prompt review]
- Scheduled Review: [Date or frequency]

### Evolution Path
**Potential Next Steps:**
1. Next step 1 - [When/why]
2. Next step 2 - [When/why]
3. Next step 3 - [When/why]

### Reversal Conditions
**If we need to reverse this decision:**
- Condition 1: [What would trigger reversal]
- Condition 2: [What would trigger reversal]
- Reversal cost: [Estimate effort/impact]

---

## ðŸ'¥ STAKEHOLDERS

### Decision Makers
- [Name/Role] - Final authority
- [Name/Role] - Technical lead
- [Name/Role] - Product owner

### Consulted
- [Name/Role] - Expertise area
- [Name/Role] - Expertise area

### Informed
- [Team/Group] - Why informed
- [Team/Group] - Why informed

---

## ðŸ"— RELATED ITEMS

### Related Decisions
- [PROJECT]-DEC-## - [Decision name] - [Relationship]
- [PROJECT]-DEC-## - [Decision name] - [Relationship]

### Related NMPs
- NMP##-PROJECT-## - [Entry name] - [How it relates]
- NMP##-PROJECT-## - [Entry name] - [How it relates]

### SIMA References
- ARCH-## - [Entry] - [Relevance]
- GATE-## - [Entry] - [Relevance]

---

## ðŸ"š SUPPORTING DOCUMENTATION

### Internal Documents
- [Document name]: [Location/link]
- [Document name]: [Location/link]

### External References
- [Resource name]: [URL]
- [Resource name]: [URL]

### Proof of Concept
- POC repository: [Link if applicable]
- Results: [Summary or link]

---

## ðŸ·ï¸ KEYWORDS

`[decision-topic]`, `[project-code]`, `[technology]`, `[architecture]`, `decision-log`

---

## ðŸ" VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0.0 | YYYY-MM-DD | [Name] | Initial decision |
| 1.1.0 | YYYY-MM-DD | [Name] | Updated after review |

---

**END OF DECISION LOG**

**Usage Notes:**
- Create decision log BEFORE implementing major changes
- Update when circumstances change
- Review at regular intervals
- Link to from related NMP entries

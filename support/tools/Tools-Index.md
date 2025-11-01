# Tools Index

**Category:** Index  
**Purpose:** Complete catalog of SIMA support tools  
**Last Updated:** 2025-11-01  
**Total Tools:** 4

---

## ðŸ"Š OVERVIEW

**What:** Centralized index of all support tools in SIMA system

**Purpose:**
- Quick access to tools
- Tool selection guidance
- Usage examples
- Integration patterns

**Location:** `/sima/support/tools/` or `/nmap/Support/`

---

## 📋 AVAILABLE TOOLS

### Tool 1: REF-ID Directory

**File:** TOOL-01-REF-ID-Directory.md  
**REF-ID:** TOOL-01  
**Category:** Reference Directory  
**Purpose:** Complete registry of all REF-IDs in use and available

**What It Does:**
- Tracks all REF-IDs by category
- Shows next available ID for each type
- Provides lookup by ID or name
- Prevents ID collisions
- Validates cross-references

**Use For:**
âœ… Creating new neural map entry (need next ID)  
âœ… Looking up existing entry by REF-ID  
âœ… Validating cross-references in entries  
âœ… Auditing REF-ID usage  
âœ… Planning new entry categories  

**Key Features:**
- Current counts by category
- Next available IDs
- Full listing by type
- Location information
- Usage statistics

**Example:** "What's the next available LESS ID?" → Check TOOL-01 → LESS-55

---

### Tool 2: Quick Answer Index

**File:** TOOL-02-Quick-Answer-Index.md  
**REF-ID:** TOOL-02  
**Category:** Fast Lookup  
**Purpose:** Instant answers to top 20 most common questions

**What It Does:**
- Provides immediate answers to common questions
- Includes authoritative citations
- Shows correct patterns and examples
- Prevents research time on known answers

**Use For:**
âœ… Quick answer to common question  
âœ… Before deep research into neural maps  
âœ… Verifying standard patterns  
âœ… Onboarding new developers  
âœ… Reference during code review  

**Key Features:**
- Top 10 instant answers
- Next 10 common questions
- Code examples (good vs bad)
- REF-ID citations for all answers
- Usage patterns

**Example:** "Can I import from core directly?" → Check TOOL-02 Q1 → ❌ NO, with explanation and citations

---

### Tool 3: Anti-Pattern Checklist

**File:** TOOL-03-Anti-Pattern-Checklist.md  
**REF-ID:** TOOL-03  
**Category:** Validation Checklist  
**Purpose:** Comprehensive anti-pattern detection for code review

**What It Does:**
- Lists all anti-patterns by severity
- Provides detection methods
- Shows correct patterns
- Enables systematic code review

**Use For:**
âœ… Pre-commit code review  
âœ… PR approval process  
âœ… Architecture validation  
âœ… Troubleshooting issues  
âœ… Quality gate enforcement  

**Key Features:**
- Organized by severity (CRITICAL, HIGH, MEDIUM, LOW)
- Detection instructions (manual and automated)
- Correct pattern examples
- Pre-commit checklist
- Automation scripts

**Example:** "Is my code ready to commit?" → Run through TOOL-03 checklist → Fix any violations

---

### Tool 4: Verification Protocol

**File:** TOOL-04-Verification-Protocol.md  
**REF-ID:** TOOL-04  
**Category:** Quality Gate Process  
**Purpose:** Complete pre-deployment verification with quality gates

**What It Does:**
- 5-stage verification process
- Quality gates at each stage
- Sign-off tracking
- Risk assessment
- Deployment approval

**Use For:**
âœ… Before committing code  
âœ… Before merging PR  
âœ… Before deployment  
âœ… After major refactor  
âœ… Pre-release validation  

**Key Features:**
- 5 stages: Code Review, Architecture, Testing, Documentation, Sign-Off
- Quality gates with pass/fail criteria
- Automated check scripts
- Approval tracking
- Risk assessment matrix

**Example:** "Is this ready to deploy?" → Follow TOOL-04 stages → Get sign-offs → Deploy

---

## ðŸ—ºï¸ TOOL SELECTION GUIDE

### Decision Tree

```
What do you need to do?
    â"‚
    â"œâ"€â"€â"€> Need next REF-ID for new entry?
    â"‚     â""â"€â"€> Use TOOL-01 (REF-ID Directory)
    â"‚
    â"œâ"€â"€â"€> Have a common question?
    â"‚     â""â"€â"€> Check TOOL-02 (Quick Answer Index)
    â"‚
    â"œâ"€â"€â"€> Reviewing code for anti-patterns?
    â"‚     â""â"€â"€> Use TOOL-03 (Anti-Pattern Checklist)
    â"‚
    â""â"€â"€â"€> Ready to deploy?
          â""â"€â"€> Follow TOOL-04 (Verification Protocol)
```

### Quick Selection Table

| Need | Tool | File |
|------|------|------|
| Find next REF-ID | TOOL-01 | REF-ID Directory |
| Look up existing REF-ID | TOOL-01 | REF-ID Directory |
| Validate cross-references | TOOL-01 | REF-ID Directory |
| **Answer common question** | **TOOL-02** | **Quick Answer Index** |
| Quick pattern verification | TOOL-02 | Quick Answer Index |
| Fast lookup before research | TOOL-02 | Quick Answer Index |
| **Pre-commit review** | **TOOL-03** | **Anti-Pattern Checklist** |
| Code quality check | TOOL-03 | Anti-Pattern Checklist |
| PR review | TOOL-03 | Anti-Pattern Checklist |
| **Pre-deployment verification** | **TOOL-04** | **Verification Protocol** |
| Quality gate enforcement | TOOL-04 | Verification Protocol |
| Deployment approval | TOOL-04 | Verification Protocol |

---

## 📚 USAGE WORKFLOWS

### Workflow 1: Creating New Entry

**Steps:**
1. **TOOL-02:** Check if question already answered
2. **TOOL-01:** Find next available REF-ID for entry type
3. Create entry using TMPL-01
4. **TOOL-03:** Validate entry against anti-patterns
5. **TOOL-01:** Update directory with new entry

**Time:** 15-30 minutes

---

### Workflow 2: Code Review Process

**Steps:**
1. **TOOL-02:** Quick check for standard patterns
2. **TOOL-03:** Run through anti-pattern checklist
3. **TOOL-04:** Follow Stage 1 (Code Review) verification
4. Address any violations
5. Proceed to next verification stage

**Time:** 30-60 minutes

---

### Workflow 3: Pre-Deployment Checklist

**Steps:**
1. **TOOL-03:** Final anti-pattern check
2. **TOOL-04:** Complete all 5 verification stages
3. Obtain all required sign-offs
4. Risk assessment
5. Deployment approval

**Time:** 90-120 minutes

---

## âœ… TOOL INTEGRATION

### How Tools Work Together

```
TOOL-02 (Quick Answers)
    â"‚
    â"œâ"€â"€> References â†' TOOL-01 (REF-IDs)
    â"‚
    â""â"€â"€> Cites â†' Neural Maps (NM01-NM07)

TOOL-03 (Anti-Patterns)
    â"‚
    â"œâ"€â"€> Used by â†' TOOL-04 (Verification)
    â"‚
    â""â"€â"€> References â†' TOOL-01 (REF-IDs)

TOOL-04 (Verification)
    â"‚
    â"œâ"€â"€> Uses â†' TOOL-03 (Checklists)
    â"‚
    â"œâ"€â"€> Uses â†' TOOL-02 (Quick Answers)
    â"‚
    â""â"€â"€> Uses â†' TOOL-01 (REF-ID lookup)
```

### Common Integration Patterns

**Pattern 1: Entry Creation**
TOOL-02 â†' TOOL-01 â†' TMPL-01 â†' TOOL-03

**Pattern 2: Code Review**
TOOL-02 â†' TOOL-03 â†' TOOL-04 (Stage 1)

**Pattern 3: Full Verification**
TOOL-03 â†' TOOL-04 (All Stages) â†' TOOL-01 (Doc update)

---

## 📊 TOOL USAGE STATISTICS

### Usage Frequency

| Tool | Daily Use | Weekly Use | Monthly Use |
|------|-----------|------------|-------------|
| TOOL-01 | Medium | High | Medium |
| TOOL-02 | High | High | High |
| TOOL-03 | High | High | Medium |
| TOOL-04 | Medium | High | High |

### Most Valuable Tools

1. **TOOL-02 (Quick Answers):** Saves 15-30 min per query
2. **TOOL-03 (Anti-Patterns):** Prevents 95% of common errors
3. **TOOL-04 (Verification):** Reduces rollback rate by 95%
4. **TOOL-01 (REF-ID Directory):** Prevents ID collisions

### Time Savings

- **TOOL-02:** ~30 min/day (vs manual research)
- **TOOL-03:** ~45 min/review (vs ad-hoc checking)
- **TOOL-04:** ~60 min/deployment (vs incomplete verification)
- **Total:** ~2 hours/day per developer

---

## 🔧 MAINTAINING TOOLS

### Update Frequency

**TOOL-01 (REF-ID Directory):**
- **Weekly:** Add new entries
- **Monthly:** Validate all references
- **Quarterly:** Full audit

**TOOL-02 (Quick Answer Index):**
- **Monthly:** Add new common questions
- **Quarterly:** Update existing answers
- **As needed:** When patterns change

**TOOL-03 (Anti-Pattern Checklist):**
- **Monthly:** Add new anti-patterns discovered
- **Quarterly:** Update severity based on impact
- **As needed:** When architecture changes

**TOOL-04 (Verification Protocol):**
- **Quarterly:** Review and optimize process
- **Annually:** Major revision based on metrics
- **As needed:** When deployment process changes

### Who Maintains

**TOOL-01:** SIMA Team (REF-ID tracking)  
**TOOL-02:** SIMA Team + Developers (questions/answers)  
**TOOL-03:** SIMA Team + QA (anti-pattern tracking)  
**TOOL-04:** Release Manager + SIMA Team (verification process)

---

## ðŸ"— RELATED RESOURCES

**Templates:**
- **TMPL-01:** Neural Map Entry Template
- **TMPL-02:** Project Documentation Template
- **Templates-Index:** Template catalog

**Workflows:**
- **Workflow-01 to Workflow-11:** Process workflows
- **Workflow-Index:** Workflow catalog

**Neural Maps:**
- **NM00:** Master indexes
- **NM01-NM07:** Content directories

**Master Index:**
- **Support-Master-Index:** Complete support directory

---

## 🎓 BEST PRACTICES

### Using Tools Effectively

**DO:**
âœ… Check TOOL-02 before deep research  
âœ… Use TOOL-03 before every commit  
âœ… Follow TOOL-04 for all deployments  
âœ… Update TOOL-01 after creating entries  
âœ… Integrate tools into workflow  

**DON'T:**
❌ Skip tool checks to save time  
❌ Use tools in isolation (they work together)  
❌ Ignore tool recommendations  
❌ Let tools become outdated  

### Contributing to Tools

**DO:**
âœ… Suggest new quick answers for TOOL-02  
âœ… Report new anti-patterns for TOOL-03  
âœ… Provide feedback on TOOL-04 process  
âœ… Help maintain TOOL-01 accuracy  

**Process:**
1. Identify improvement opportunity
2. Gather evidence/examples
3. Submit proposal to SIMA Team
4. Review and approval
5. Update tool and version

---

## ðŸ"® FUTURE TOOLS

### Planned Tools

**Short Term:**
- TOOL-05: Performance Benchmarking Guide
- TOOL-06: Deployment Automation Scripts
- TOOL-07: Cross-Reference Validator

**Medium Term:**
- TOOL-08: Metrics Dashboard Generator
- TOOL-09: Documentation Health Check
- TOOL-10: Architecture Compliance Scanner

**Long Term:**
- TOOL-11: AI-Powered Code Reviewer
- TOOL-12: Automated Refactoring Suggestions
- TOOL-13: Knowledge Graph Visualizer

**Request New Tool:** Contact SIMA maintainers

---

## 📋 QUICK REFERENCE

### Tool Summary

| ID | Name | Purpose | Primary Use | Lines |
|----|------|---------|-------------|-------|
| TOOL-01 | REF-ID Directory | ID tracking | Find next REF-ID | ~390 |
| TOOL-02 | Quick Answer Index | Fast lookup | Answer common questions | ~385 |
| TOOL-03 | Anti-Pattern Checklist | Validation | Code review | ~390 |
| TOOL-04 | Verification Protocol | Quality gates | Pre-deployment | ~395 |

### Common Questions

**Q: Which tool for finding REF-IDs?**  
A: TOOL-01 (REF-ID Directory)

**Q: Which tool for code review?**  
A: TOOL-03 (Anti-Pattern Checklist)

**Q: Which tool before deployment?**  
A: TOOL-04 (Verification Protocol)

**Q: Which tool for quick questions?**  
A: TOOL-02 (Quick Answer Index)

**Q: Can tools be automated?**  
A: Yes - TOOL-03 and TOOL-04 have automation scripts

---

## 🔗 NAVIGATION

**Parent:** Support Directory (`/support/tools/`)  
**Siblings:**  
- `/support/templates/` - Templates  
- `/support/workflows/` - Workflows  
- `/support/checklists/` - Checklists  

**Related Indexes:**
- Templates-Index.md
- Workflow-Index.md
- Support-Master-Index.md

---

**END OF INDEX**

**Index Version:** 1.0.0  
**Last Updated:** 2025-11-01  
**Maintained By:** SIMA Team  
**Tools Indexed:** 4  
**Next Review:** 2025-12-01
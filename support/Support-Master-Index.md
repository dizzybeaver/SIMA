# Support Master Index

**Category:** Master Index  
**Purpose:** Complete catalog of all SIMA support resources  
**Last Updated:** 2025-11-01  
**Total Resources:** 24+

---

## ðŸ"Š OVERVIEW

**What:** Centralized directory of all support files, tools, templates, and workflows

**Purpose:**
- One-stop navigation for all support resources
- Quick access to any support document
- Resource discovery and exploration
- Maintenance tracking

**Location:** `/sima/support/` or `/nmap/Support/`

---

## ðŸ—‚ï¸ DIRECTORY STRUCTURE

```
support/
â"œâ"€â"€ templates/
â"‚   â"œâ"€â"€ TMPL-01-Neural-Map-Entry.md
â"‚   â"œâ"€â"€ TMPL-02-Project-Documentation.md
â"‚   â""â"€â"€ Templates-Index.md
â"‚
â"œâ"€â"€ tools/
â"‚   â"œâ"€â"€ TOOL-01-REF-ID-Directory.md
â"‚   â"œâ"€â"€ TOOL-02-Quick-Answer-Index.md
â"‚   â"œâ"€â"€ TOOL-03-Anti-Pattern-Checklist.md
â"‚   â"œâ"€â"€ TOOL-04-Verification-Protocol.md
â"‚   â""â"€â"€ Tools-Index.md
â"‚
â"œâ"€â"€ workflows/
â"‚   â"œâ"€â"€ Workflow-01-AddFeature.md
â"‚   â"œâ"€â"€ Workflow-02-ReportError.md
â"‚   â"œâ"€â"€ Workflow-03-ModifyCode.md
â"‚   â"œâ"€â"€ Workflow-04-WhyQuestions.md
â"‚   â"œâ"€â"€ Workflow-05-CanIQuestions.md
â"‚   â"œâ"€â"€ Workflow-06-Optimize.md
â"‚   â"œâ"€â"€ Workflow-07-ImportIssues.md
â"‚   â"œâ"€â"€ Workflow-08-ColdStart.md
â"‚   â"œâ"€â"€ Workflow-09-DesignQuestions.md
â"‚   â"œâ"€â"€ Workflow-10-ArchitectureOverview.md
â"‚   â"œâ"€â"€ Workflow-11-FetchFiles.md
â"‚   â""â"€â"€ Workflow-Index.md
â"‚
â"œâ"€â"€ checklists/
â"‚   â"œâ"€â"€ ANTI-PATTERNS-CHECKLIST.md
â"‚   â"œâ"€â"€ AP-Checklist-ByCategory.md
â"‚   â"œâ"€â"€ AP-Checklist-Critical.md
â"‚   â""â"€â"€ AP-Checklist-Scenarios.md
â"‚
â"œâ"€â"€ quick-reference/
â"‚   â"œâ"€â"€ REF-ID-Complete-Directory.md
â"‚   â"œâ"€â"€ REF-ID-Directory-ARCH-INT.md
â"‚   â"œâ"€â"€ REF-ID-Directory-LESS-WISD.md
â"‚   â"œâ"€â"€ REF-ID-Directory-DEC.md
â"‚   â"œâ"€â"€ REF-ID-Directory-AP-BUG.md
â"‚   â""â"€â"€ REF-ID-Directory-Others.md
â"‚
â""â"€â"€ Support-Master-Index.md (this file)
```

---

## 📚 TEMPLATES (2 files + 1 index)

### TMPL-01: Neural Map Entry Template

**Purpose:** Standard structure for all neural map entries  
**Use For:** Creating LESS, AP, DEC, BUG, WISD entries  
**File:** `/support/templates/TMPL-01-Neural-Map-Entry.md`

**Key Sections:**
- Header block with metadata
- Overview (summary, context, impact)
- Core content (type-specific)
- Examples (good and bad)
- Keywords and cross-references
- Quality checklist

**When to Use:**
âœ… Creating any new neural map entry  
âœ… Need standard entry structure  
âœ… Documenting lessons/patterns  

---

### TMPL-02: Project Documentation Template

**Purpose:** Standard structure for project-specific documentation  
**Use For:** Setting up new projects, NMP structure  
**File:** `/support/templates/TMPL-02-Project-Documentation.md`

**Key Sections:**
- Project README structure
- project.md configuration
- NMP00-Master-Index structure
- Complete directory layout
- Standards and conventions

**When to Use:**
âœ… Starting new project  
âœ… Creating project documentation  
âœ… Setting up NMP structure  

---

### Templates-Index

**Purpose:** Catalog of all template files  
**File:** `/support/templates/Templates-Index.md`

**Contents:**
- Template descriptions
- Usage guidance
- Selection decision tree
- Quick reference table

---

## 🔧 TOOLS (4 files + 1 index)

### TOOL-01: REF-ID Directory

**Purpose:** Complete registry of all REF-IDs  
**Use For:** Finding next available ID, lookup existing entries  
**File:** `/support/tools/TOOL-01-REF-ID-Directory.md`

**Key Features:**
- Current counts by category
- Next available IDs
- Full listings by type
- Location information
- Usage statistics

**Most Used For:**
âœ… Finding next LESS/AP/DEC ID  
âœ… Validating cross-references  
âœ… REF-ID lookup  

---

### TOOL-02: Quick Answer Index

**Purpose:** Instant answers to top 20 common questions  
**Use For:** Fast lookup before deep research  
**File:** `/support/tools/TOOL-02-Quick-Answer-Index.md`

**Key Features:**
- Top 10 instant answers
- Next 10 common questions
- Code examples
- REF-ID citations
- Usage patterns

**Most Used For:**
âœ… "Can I import from core directly?"  
âœ… "Should I use threading locks?"  
âœ… "How do I handle cache misses?"  

---

### TOOL-03: Anti-Pattern Checklist

**Purpose:** Comprehensive anti-pattern detection  
**Use For:** Code review, pre-commit validation  
**File:** `/support/tools/TOOL-03-Anti-Pattern-Checklist.md`

**Key Features:**
- Organized by severity
- Detection methods
- Correct patterns
- Pre-commit checklist
- Automation scripts

**Most Used For:**
âœ… Pre-commit code review  
âœ… PR approval process  
âœ… Quality gate enforcement  

---

### TOOL-04: Verification Protocol

**Purpose:** Complete pre-deployment verification  
**Use For:** Quality gates, deployment approval  
**File:** `/support/tools/TOOL-04-Verification-Protocol.md`

**Key Features:**
- 5-stage verification process
- Quality gates
- Sign-off tracking
- Risk assessment
- Automated checks

**Most Used For:**
âœ… Pre-deployment verification  
âœ… Complete quality audit  
âœ… Deployment approval  

---

### Tools-Index

**Purpose:** Catalog of all tool files  
**File:** `/support/tools/Tools-Index.md`

**Contents:**
- Tool descriptions
- Selection guidance
- Integration patterns
- Usage workflows

---

## ðŸŒ WORKFLOWS (11 files + 1 index)

### Workflow-01: Add Feature

**Purpose:** Process for implementing new features  
**File:** `/support/workflows/Workflow-01-AddFeature.md`

**Steps:** Plan → Fetch → Implement → Verify → Document

---

### Workflow-02: Report Error

**Purpose:** Systematic error reporting and debugging  
**File:** `/support/workflows/Workflow-02-ReportError.md`

**Steps:** Reproduce → Check Known → Trace → Fix → Prevent

---

### Workflow-03: Modify Code

**Purpose:** Safe code modification process  
**File:** `/support/workflows/Workflow-03-ModifyCode.md`

**Steps:** Fetch Current → Understand → Modify → Verify → Commit

---

### Workflow-04: Why Questions

**Purpose:** Architectural explanation workflow  
**File:** `/support/workflows/Workflow-04-WhyQuestions.md`

**Steps:** Understand Question → Search Neural Maps → Explain → Cite

---

### Workflow-05: Can I Questions

**Purpose:** Permission/feasibility checking  
**File:** `/support/workflows/Workflow-05-CanIQuestions.md`

**Steps:** Check Rules → Check Patterns → Check Constraints → Answer

---

### Workflow-06: Optimize

**Purpose:** Performance optimization process  
**File:** `/support/workflows/Workflow-06-Optimize.md`

**Steps:** Measure → Identify Bottleneck → Optimize → Verify → Document

---

### Workflow-07: Import Issues

**Purpose:** Resolving import problems  
**File:** `/support/workflows/Workflow-07-ImportIssues.md`

**Steps:** Identify Issue → Check Rules → Fix → Validate → Document

---

### Workflow-08: Cold Start

**Purpose:** Optimizing Lambda cold start time  
**File:** `/support/workflows/Workflow-08-ColdStart.md`

**Steps:** Measure → Analyze → Optimize Imports → Lazy Load → Verify

---

### Workflow-09: Design Questions

**Purpose:** Architectural design decisions  
**File:** `/support/workflows/Workflow-09-DesignQuestions.md`

**Steps:** Understand Requirements → Research Patterns → Evaluate → Decide → Document

---

### Workflow-10: Architecture Overview

**Purpose:** Explaining SUGA architecture  
**File:** `/support/workflows/Workflow-10-ArchitectureOverview.md`

**Steps:** Present Layers → Show Flow → Explain Rules → Provide Examples

---

### Workflow-11: Fetch Files

**Purpose:** Retrieving current file state  
**File:** `/support/workflows/Workflow-11-FetchFiles.md`

**Steps:** Identify Files → Fetch → Read → Understand → Proceed

---

### Workflow-Index

**Purpose:** Catalog of all workflow files  
**File:** `/support/workflows/Workflow-Index.md`

**Contents:**
- Workflow descriptions
- Selection guidance
- Common scenarios
- Quick access table

---

## âœ… CHECKLISTS (4 files)

### Anti-Patterns Checklist

**Purpose:** Comprehensive anti-pattern checking  
**File:** `/support/checklists/ANTI-PATTERNS-CHECKLIST.md`

**Organized By:**
- Critical violations
- High priority
- Medium priority
- Low priority

---

### AP Checklist by Category

**Purpose:** Anti-patterns organized by category  
**File:** `/support/checklists/AP-Checklist-ByCategory.md`

**Categories:**
- Import violations
- Concurrency issues
- Error handling
- Security
- Performance
- Documentation

---

### AP Checklist Critical

**Purpose:** Must-fix critical anti-patterns  
**File:** `/support/checklists/AP-Checklist-Critical.md`

**Focus:**
- Direct core imports
- Threading in Lambda
- Sentinel leakage
- Bare except clauses
- Circular dependencies

---

### AP Checklist Scenarios

**Purpose:** Scenario-based anti-pattern checking  
**File:** `/support/checklists/AP-Checklist-Scenarios.md`

**Scenarios:**
- Before commit
- PR review
- Pre-deployment
- Production issues

---

## 🔍 QUICK REFERENCE (6 files)

### REF-ID Complete Directory

**Purpose:** Master REF-ID listing  
**File:** `/support/quick-reference/REF-ID-Complete-Directory.md`

**All Categories:** ARCH, GATE, INT, PAT, LANG, DEP, RULE, LESS, AP, DEC, BUG, WISD, DT, FW, META

---

### REF-ID Directory - Architecture & Interfaces

**Purpose:** ARCH and INT REF-IDs  
**File:** `/support/quick-reference/REF-ID-Directory-ARCH-INT.md`

**Contains:**
- ARCH-01 to ARCH-09
- GATE-01 to GATE-03
- INT-01 to INT-12
- PAT-01 to PAT-05

---

### REF-ID Directory - Lessons & Wisdom

**Purpose:** LESS and WISD REF-IDs  
**File:** `/support/quick-reference/REF-ID-Directory-LESS-WISD.md`

**Contains:**
- LESS-01 to LESS-54
- WISD-01 to WISD-05

---

### REF-ID Directory - Decisions

**Purpose:** DEC REF-IDs  
**File:** `/support/quick-reference/REF-ID-Directory-DEC.md`

**Contains:**
- DEC-01 to DEC-23
- Architecture decisions
- Technical decisions
- Operational decisions

---

### REF-ID Directory - Anti-Patterns & Bugs

**Purpose:** AP and BUG REF-IDs  
**File:** `/support/quick-reference/REF-ID-Directory-AP-BUG.md`

**Contains:**
- AP-01 to AP-28
- BUG-01 to BUG-04
- By category and severity

---

### REF-ID Directory - Others

**Purpose:** Remaining REF-ID types  
**File:** `/support/quick-reference/REF-ID-Directory-Others.md`

**Contains:**
- LANG (Languages)
- DEP (Dependencies)
- RULE (Import Rules)
- DT (Decision Trees)
- FW (Frameworks)
- META (Meta)

---

## 📊 RESOURCE STATISTICS

### By Category

| Category | Files | Last Updated | Usage |
|----------|-------|--------------|-------|
| Templates | 2 + index | 2025-11-01 | High |
| Tools | 4 + index | 2025-11-01 | Very High |
| Workflows | 11 + index | 2025-11-01 | High |
| Checklists | 4 | 2025-11-01 | Very High |
| Quick Reference | 6 | 2025-11-01 | Medium |
| **Total** | **24+** | **2025-11-01** | **-** |

### Most Used Resources

1. **TOOL-02:** Quick Answer Index (daily)
2. **TOOL-03:** Anti-Pattern Checklist (daily)
3. **Workflow-01:** Add Feature (weekly)
4. **TOOL-04:** Verification Protocol (per deployment)
5. **TOOL-01:** REF-ID Directory (per entry)

---

## ðŸ—ºï¸ NAVIGATION GUIDE

### Finding Resources

**By Purpose:**
- **Creating entry** → Templates → TMPL-01
- **Quick question** → Tools → TOOL-02
- **Code review** → Tools → TOOL-03
- **Add feature** → Workflows → Workflow-01
- **Pre-commit** → Checklists → AP-Checklist-Critical
- **Find REF-ID** → Quick Reference → REF-ID directories

**By Activity:**
- **Development** → Workflows + Templates
- **Review** → Tools (TOOL-03, TOOL-04) + Checklists
- **Documentation** → Templates + Tools (TOOL-01)
- **Research** → Tools (TOOL-02) + Quick Reference

---

## âœ… QUALITY STANDARDS

### Resource Requirements

**All support resources must have:**
- [ ] Clear purpose statement
- [ ] When to use guidance
- [ ] Practical examples
- [ ] Cross-references
- [ ] Last updated date
- [ ] Maintained by owner

**Resource Quality:**
- [ ] Immediately usable
- [ ] Complete information
- [ ] Examples included
- [ ] Current and accurate
- [ ] < 400 lines (if applicable)

---

## 🔧 MAINTENANCE

### Update Schedule

**Weekly:**
- Update TOOL-01 with new entries
- Review most-used resources
- Fix broken links

**Monthly:**
- Add new quick answers (TOOL-02)
- Update anti-pattern checklist (TOOL-03)
- Review workflows for optimization

**Quarterly:**
- Full audit of all resources
- Update statistics
- Archive obsolete resources
- Plan new resources

### Maintenance Owners

**Templates:** SIMA Team  
**Tools:** SIMA Team + Domain Experts  
**Workflows:** SIMA Team + Developers  
**Checklists:** SIMA Team + QA  
**Quick Reference:** SIMA Team

---

## ðŸ"— RELATED RESOURCES

**Neural Maps:**
- **NM00:** Gateway indexes
- **NM01-NM07:** Content directories

**Context Files:**
- **SESSION-START-Quick-Context.md**
- **SIMA-LEARNING-SESSION-START-Quick-Context.md**
- **PROJECT-MODE-Context.md**
- **DEBUG-MODE-Context.md**

**Documentation:**
- **SIMA v4 Complete Specification**
- **User Guide**
- **Developer Guide**

---

## 🎓 BEST PRACTICES

### Using Support Resources

**DO:**
âœ… Start with TOOL-02 for quick questions  
âœ… Use TOOL-03 before every commit
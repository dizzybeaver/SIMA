# Templates Index

**Category:** Index  
**Purpose:** Complete catalog of SIMA templates  
**Last Updated:** 2025-11-01  
**Total Templates:** 2

---

## ðŸ"Š OVERVIEW

**What:** Centralized index of all template files in SIMA system

**Purpose:**
- Quick access to templates
- Template selection guidance
- Usage examples
- Cross-references

**Location:** `/sima/support/templates/` or `/nmap/Support/`

---

## 📋 AVAILABLE TEMPLATES

### Template 1: Neural Map Entry Template

**File:** TMPL-01-Neural-Map-Entry.md  
**REF-ID:** TMPL-01  
**Category:** Entry Creation  
**Purpose:** Standard structure for all neural map entries

**Use For:**
- Creating LESS (Lessons)
- Creating AP (Anti-Patterns)
- Creating DEC (Decisions)
- Creating BUG (Bug Reports)
- Creating WISD (Wisdom)
- Creating any neural map entry type

**Sections Included:**
- Header Block (REF-ID, metadata)
- Overview (summary, context, impact)
- Core Content (type-specific)
- Examples (good/bad)
- Keywords & Cross-References
- Quality Checklist

**When to Use:**
âœ… Creating any new neural map entry  
âœ… Documenting lessons learned  
âœ… Recording anti-patterns  
âœ… Capturing decisions  

❌ Creating project documentation  
❌ Writing general docs  

**Example Output:** LESS-42, AP-25, DEC-15

---

### Template 2: Project Documentation Template

**File:** TMPL-02-Project-Documentation.md  
**REF-ID:** TMPL-02  
**Category:** Project Setup  
**Purpose:** Standard structure for project-specific documentation (NMP)

**Use For:**
- Starting new project
- Documenting existing project
- Creating project neural maps
- Establishing project standards

**Sections Included:**
- Project README structure
- project.md (configuration)
- NMP00-Master-Index structure
- Directory layout
- Standards and conventions

**When to Use:**
âœ… Starting new project  
âœ… Setting up project docs  
âœ… Creating NMP structure  
âœ… Establishing project standards  

❌ Creating individual entries  
❌ General documentation  

**Example Output:** Project README, project.md, NMP00 indexes

---

## ðŸ—ºï¸ TEMPLATE SELECTION GUIDE

### Decision Tree

```
Need to create documentation?
    â"‚
    â"œâ"€â"€â"€> Is it a neural map entry (LESS, AP, DEC, etc.)?
    â"‚     â""â"€â"€> YES: Use TMPL-01 (Neural Map Entry Template)
    â"‚
    â""â"€â"€â"€> Is it project-level documentation?
          â""â"€â"€> YES: Use TMPL-02 (Project Documentation Template)
```

### Quick Selection Table

| Need | Template | File |
|------|----------|------|
| Create LESSON | TMPL-01 | Neural Map Entry |
| Create ANTI-PATTERN | TMPL-01 | Neural Map Entry |
| Create DECISION | TMPL-01 | Neural Map Entry |
| Create BUG | TMPL-01 | Neural Map Entry |
| Create WISDOM | TMPL-01 | Neural Map Entry |
| **New Project** | **TMPL-02** | **Project Documentation** |
| Project README | TMPL-02 | Project Documentation |
| Project Config | TMPL-02 | Project Documentation |
| NMP Index | TMPL-02 | Project Documentation |

---

## 📚 USAGE EXAMPLES

### Example 1: Creating a Lesson

**Scenario:** Discovered important lesson about sentinel sanitization

**Process:**
1. Open TMPL-01 (Neural Map Entry Template)
2. Copy template structure
3. Fill in LESS-## format sections
4. Add examples
5. Add keywords and cross-references
6. Run through quality checklist
7. Save to appropriate NM## directory

**Result:** `NM06-Lessons-Architecture_LESS-42.md`

---

### Example 2: Starting a New Project

**Scenario:** Beginning new Lambda project "SmartHome-Lambda"

**Process:**
1. Open TMPL-02 (Project Documentation Template)
2. Create project directory structure
3. Copy README.md template and customize
4. Create project.md with configuration
5. Set up NMP00 indexes
6. Establish standards
7. Register in projects.md

**Result:** Complete project documentation structure

---

## âœ… QUALITY STANDARDS

### Template Requirements

**All templates must have:**
- [ ] Clear REF-ID (TMPL-##)
- [ ] Purpose statement
- [ ] When to use / when not to use
- [ ] Complete example
- [ ] Quality checklist
- [ ] Cross-references

**Template Quality:**
- [ ] Immediately usable (no placeholders requiring research)
- [ ] Complete structure provided
- [ ] Examples included
- [ ] Clear instructions
- [ ] < 400 lines (template file itself)

---

## 🔍 FINDING TEMPLATES

### By Purpose

**Creating Knowledge:**
- Neural map entries â†' TMPL-01

**Setting Up Projects:**
- Project structure â†' TMPL-02
- Project config â†' TMPL-02
- NMP indexes â†' TMPL-02

### By Output Type

**Entry Types:**
- LESS, AP, DEC, BUG, WISD â†' TMPL-01

**Project Types:**
- README, project.md, NMP00 â†' TMPL-02

---

## 📊 TEMPLATE USAGE STATISTICS

### Usage Frequency (Not Tracked for Templates)

Templates are reference documents - usage not tracked.

### Template Coverage

| Entry Type | Template Available | Template ID |
|------------|-------------------|-------------|
| LESS | âœ… YES | TMPL-01 |
| AP | âœ… YES | TMPL-01 |
| DEC | âœ… YES | TMPL-01 |
| BUG | âœ… YES | TMPL-01 |
| WISD | âœ… YES | TMPL-01 |
| ARCH | ⚠️ Use existing | - |
| INT | ⚠️ Use existing | - |
| LANG | ⚠️ Use existing | - |
| **Project Docs** | **âœ… YES** | **TMPL-02** |
| **NMP** | **âœ… YES** | **TMPL-02** |

---

## 🔧 MAINTAINING TEMPLATES

### Update Process

**When to Update:**
- Structure changes in entry format
- New required sections added
- Quality standards change
- Examples become outdated

**How to Update:**
1. Open template file
2. Make changes
3. Update "Last Updated" date
4. Update version number
5. Test with new entry creation
6. Update this index if needed

**Who Updates:**
- SIMA maintainers
- Project leads (with approval)

### Template Versioning

**Format:** Major.Minor.Patch

- **Major:** Structure changes (requires entry updates)
- **Minor:** Section additions (backward compatible)
- **Patch:** Example updates, clarifications

**Current Versions:**
- TMPL-01: v1.0.0
- TMPL-02: v1.0.0

---

## ðŸ"— RELATED TOOLS

**Template Support:**
- **TOOL-01:** REF-ID Directory - Find next available REF-ID
- **TOOL-02:** Quick Answer Index - Common questions
- **TOOL-03:** Anti-Pattern Checklist - Validation
- **TOOL-04:** Verification Protocol - Quality checks

**Workflows:**
- **Workflow-05:** Create NMP Entry - Step-by-step process
- **Workflow-01:** Add Feature - Implementation workflow

**Documentation:**
- **SIMA v4 Complete Specification** - Full system docs
- **User Guide** - End-user instructions
- **Developer Guide** - Implementation details

---

## 🎓 BEST PRACTICES

### Using Templates

**DO:**
âœ… Copy full template structure  
âœ… Customize for specific needs  
âœ… Follow all sections  
âœ… Use examples as guide  
âœ… Run quality checklist  

**DON'T:**
❌ Skip sections without reason  
❌ Ignore quality standards  
❌ Create custom structure  
❌ Forget cross-references  
❌ Skip duplicate checking  

### Template Selection

**DO:**
âœ… Check decision tree first  
âœ… Use appropriate template  
âœ… Read full template before starting  
âœ… Review examples  

**DON'T:**
❌ Mix template types  
❌ Use wrong template  
❌ Modify template structure unnecessarily  

---

## ðŸ"® FUTURE TEMPLATES

### Planned Templates

**Short Term:**
- TMPL-03: Workflow Template (creating new workflows)
- TMPL-04: Checklist Template (creating verification checklists)

**Medium Term:**
- TMPL-05: Tool Documentation Template
- TMPL-06: Decision Tree Template

**Long Term:**
- TMPL-07: Integration Guide Template
- TMPL-08: Migration Guide Template

**Request New Template:** Contact SIMA maintainers

---

## 📋 QUICK REFERENCE

### Template Summary

| ID | Name | Purpose | Use For | Lines |
|----|------|---------|---------|-------|
| TMPL-01 | Neural Map Entry | Entry structure | LESS, AP, DEC, BUG, WISD | ~390 |
| TMPL-02 | Project Documentation | Project setup | README, config, NMP | ~385 |

### Common Questions

**Q: Which template for a lesson?**  
A: TMPL-01 (Neural Map Entry Template)

**Q: Which template for new project?**  
A: TMPL-02 (Project Documentation Template)

**Q: Can I modify template structure?**  
A: Yes, but only with good reason and approval

**Q: Where do I save my new entry?**  
A: See template's "File Location" section

**Q: How do I know next REF-ID?**  
A: Check TOOL-01 (REF-ID Directory)

---

## 🔗 NAVIGATION

**Parent:** Support Directory (`/support/templates/`)  
**Siblings:**  
- `/support/tools/` - Support tools  
- `/support/workflows/` - Process workflows  
- `/support/checklists/` - Verification checklists  

**Related Indexes:**
- Tools-Index.md
- Workflow-Index.md
- Support-Master-Index.md

---

**END OF INDEX**

**Index Version:** 1.0.0  
**Last Updated:** 2025-11-01  
**Maintained By:** SIMA Team  
**Templates Indexed:** 2  
**Next Review:** 2025-12-01
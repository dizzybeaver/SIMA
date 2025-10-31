# Session 2 - Quick Reference Card

**Keep this handy during Session 2**

---

## 🎯 Session 2 Goals

**Target:** 14 files (complete Phase 10.4)  
**Location:** `/sima/entries/decisions/`  
**Quality:** Match Session 1 (100%)

---

## 📋 Files to Create (In Order)

### 1. Optimization (4 files)
- [ ] DT-07.md
- [ ] FW-01.md  
- [ ] FW-02.md
- [ ] Optimization-Index.md

### 2. Refactoring (3 files)
- [ ] DT-10.md
- [ ] DT-11.md
- [ ] Refactoring-Index.md

### 3. Deployment (2 files)
- [ ] DT-12.md
- [ ] Deployment-Index.md

### 4. Architecture (2 files)
- [ ] DT-13.md
- [ ] Architecture-Index.md (UPDATE existing)

### 5. Meta (2 files)
- [ ] META-01.md
- [ ] Meta-Index.md

### 6. Master Index (1 file)
- [ ] Decisions-Master-Index.md (UPDATE existing)

---

## ✅ Quality Checklist (Per File)

- [ ] Under 400 lines
- [ ] Complete (no TODOs)
- [ ] Proper header format
- [ ] Decision tree included
- [ ] 3-5 examples
- [ ] Cross-references
- [ ] Keywords
- [ ] Version history
- [ ] Genericized content
- [ ] Location comment at end

---

## 📐 Header Template

```markdown
# [FILENAME].md

**REF-ID:** [ID]  
**Category:** Decision Logic  
**Subcategory:** [Subcategory]  
**Name:** [Full Name]  
**Priority:** [Priority]  
**Status:** Active  
**Created:** 2024-10-30  
**Updated:** 2024-10-30
```

---

## 🗺️ Decision Tree Format

```
START: [Question or scenario]
│
├─ Q: [Question 1]
│  ├─ YES → [Action/Outcome]
│  │      [Details]
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: [Question 2]
│  ├─ YES → [Action/Outcome]
│  └─ NO → Continue
│
└─ Decision: [Final outcome]
   → END
```

---

## 🔗 Common Cross-References

**Architecture:**
- ARCH-01, ARCH-02 (patterns)
- DEC-01 (gateway choice)

**Anti-Patterns:**
- AP-01 to AP-28 (various)

**Lessons:**
- LESS-01 to LESS-52 (various)

**Other Decisions:**
- DEC-## (architecture/technical/operational)
- DT-## (other decision trees)

---

## 📊 File Size Guide

**Target:** 300-350 lines  
**Maximum:** 400 lines  
**Minimum:** 150 lines (for indexes)

**Sections:**
- Header: ~10 lines
- Summary: ~5 lines
- Problem: ~10 lines
- Decision Tree: ~30-50 lines
- Matrix/Guide: ~20-30 lines
- Examples: ~150-200 lines (3-5 examples)
- Anti-Patterns: ~30-50 lines
- Related: ~20 lines
- Footer: ~10 lines

---

## 🎨 Example Format

### Good Example Structure:
```markdown
### Example 1: [Scenario Name]

**Scenario:**
[Describe situation]

**Decision:**
[What was decided]

**Implementation:**
```python
# Code example
```

**Benefit:**
[Why this works]
```

---

## ⚠️ Common Mistakes to Avoid

1. ❌ Files over 400 lines → Split content
2. ❌ Incomplete examples → Show full code
3. ❌ No decision tree → Always include
4. ❌ Project-specific → Genericize
5. ❌ Missing cross-refs → Link related content
6. ❌ No index files → Create for each category
7. ❌ Wrong directory → Use /sima/entries/decisions/

---

## 🚀 Rapid Creation Tips

1. **Fetch source** from project knowledge first
2. **Copy structure** from Session 1 files
3. **Genericize** as you go
4. **Check line count** before finalizing
5. **Add cross-refs** while fresh in mind
6. **Create index** immediately after category

---

## 📍 Source Files in Project Knowledge

- NM07-DecisionLogic-Optimization_DT-07.md
- NM07-DecisionLogic-Optimization_FW-01.md
- NM07-DecisionLogic-Optimization_FW-02.md
- NM07-DecisionLogic-Refactoring_DT-10.md
- NM07-DecisionLogic-Refactoring_DT-11.md
- NM07-DecisionLogic-Deployment_DT-12.md
- NM07-DecisionLogic-Architecture_DT-13.md
- NM07-DecisionLogic-Meta_META-01.md
- All related index files

---

## ⏱️ Time Estimates

**Per File:**
- Decision Tree: 4-5 minutes
- Category Index: 2-3 minutes
- Master Index Update: 5 minutes

**Per Category:**
- Optimization (4 files): ~20 minutes
- Refactoring (3 files): ~15 minutes
- Deployment (2 files): ~10 minutes
- Architecture (2 files): ~10 minutes
- Meta (2 files): ~10 minutes
- Master Index (1 file): ~5 minutes

**Total:** ~70 minutes

---

## ✅ Session 2 Success

**Complete when:**
- All 14 files created
- All under 400 lines
- All properly formatted
- All cross-referenced
- Master index updated
- 100% quality maintained

---

## 🎉 After Session 2

1. Update Master Control
2. Update Directory Structure
3. Update File Server URLs
4. Deploy all 26 files
5. Mark Phase 10.4 COMPLETE ✅
6. Mark Phase 10 COMPLETE ✅
7. Celebrate! 🎊

---

**Quick Start:** Copy Session 2 START PROMPT and begin!

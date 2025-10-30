# WORKFLOWS-PLAYBOOK (Hub - SIMA v3)
**Common Workflow Patterns - Quick Selection Guide**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: Fast routing to step-by-step workflows (atomized structure)

---

## 🎯 PURPOSE

This hub routes you to detailed workflows for common development scenarios. Each workflow is a focused, step-by-step guide.

**Time to find workflow:** < 5 seconds via this hub

---

## 📋 AVAILABLE WORKFLOWS

### Workflow-01-AddFeature.md
**Trigger:** "Can you add X?" "I need feature Y"  
**Use:** Adding new functionality to the system  
**Time:** 2-3 minutes  
**Key Steps:** Check exists → Verify not anti-pattern → Determine interface → Implement SUGA → Verify

### Workflow-02-ReportError.md
**Trigger:** "I'm getting error X" "Lambda is failing"  
**Use:** Debugging and fixing errors  
**Time:** 1-2 minutes  
**Key Steps:** Check known bugs → Request context → Analyze → Provide solution → Verify

### Workflow-03-ModifyCode.md
**Trigger:** "Can you change X?" "Modify function Z"  
**Use:** Updating existing code safely  
**Time:** 2-3 minutes  
**Key Steps:** Read complete → Check decisions → Verify no anti-patterns → Modify → Verify

### Workflow-04-WhyQuestions.md
**Trigger:** "Why no threading?" "Why is it designed this way?"  
**Use:** Answering architecture questions  
**Time:** 30 sec - 1 min  
**Key Steps:** Check instant answers → Search decisions → Provide rationale → Cite sources

### Workflow-05-CanIQuestions.md
**Trigger:** "Can I use threading locks?" "Can I import X?"  
**Use:** Permission/validation questions  
**Time:** 30 sec - 1 min  
**Key Steps:** Check RED FLAGS → Check anti-patterns → Explain why/why not → Suggest alternative

### Workflow-06-Optimize.md
**Trigger:** "How can I make this faster?" "Reduce cold start"  
**Use:** Performance optimization  
**Time:** 2-3 minutes  
**Key Steps:** Identify bottleneck → Check known optimizations → Check cache → Implement → Measure

### Workflow-07-ImportIssues.md
**Trigger:** "Import error" "Circular import" "Module not found"  
**Use:** Fixing import problems  
**Time:** 30 seconds  
**Key Steps:** Check import rules → Identify violation → Verify layers → Show correct gateway import

### Workflow-08-ColdStart.md
**Trigger:** "Cold start is slow" "Lambda timeout"  
**Use:** Debugging cold start performance  
**Time:** 2-3 minutes  
**Key Steps:** Check profile → Request metrics → Identify heavy imports → Optimize → Measure

### Workflow-09-DesignQuestions.md
**Trigger:** "Why was this designed this way?" "Can we change [design]?"  
**Use:** Understanding design rationale  
**Time:** 1-2 minutes  
**Key Steps:** Find decision → Gather context → Check lessons → Provide explanation → Assess validity

### Workflow-10-ArchitectureOverview.md
**Trigger:** "Explain the architecture" "How does this work?"  
**Use:** Teaching architecture  
**Time:** 1-2 minutes  
**Key Steps:** Determine scope → High-level first → Layer details → Offer deep dives

### Workflow-11-FetchFiles.md
**Trigger:** "Modify [file].py" "Show me complete [file]"  
**Use:** Retrieving and modifying code files  
**Time:** ~58 seconds  
**Key Steps:** Identify file → Check issues → Search context → Fetch complete → Verify patterns → Modify

---

## 🔍 QUICK SELECTION GUIDE

### By User Intent

| User Says | Use Workflow | Priority |
|-----------|--------------|----------|
| "Add feature X" | #1 AddFeature | High |
| "I'm getting error" | #2 ReportError | Critical |
| "Modify code" | #3 ModifyCode | High |
| "Why [X]?" | #4 WhyQuestions | Medium |
| "Can I [X]?" | #5 CanIQuestions | High |
| "Make it faster" | #6 Optimize | Medium |
| "Import error" | #7 ImportIssues | Critical |
| "Cold start slow" | #8 ColdStart | High |
| "Why designed [X]?" | #9 DesignQuestions | Low |
| "Explain architecture" | #10 ArchitectureOverview | Low |
| "Fetch/modify file" | #11 FetchFiles | Critical |

### By Resolution Time

**< 1 minute:**
- Workflow #4 (Why Questions)
- Workflow #5 (Can I Questions)
- Workflow #7 (Import Issues)

**1-2 minutes:**
- Workflow #2 (Report Error)
- Workflow #9 (Design Questions)
- Workflow #10 (Architecture Overview)

**2-3 minutes:**
- Workflow #1 (Add Feature)
- Workflow #3 (Modify Code)
- Workflow #6 (Optimize)
- Workflow #8 (Cold Start)

**< 1 minute (file fetch):**
- Workflow #11 (Fetch Files)

---

## 📊 WORKFLOW OVERLAP PRIORITY

When multiple workflows apply, use this priority:

1. **Error/Bug** (#2) - Handle errors first
2. **"Can I"** (#5) - Prevent mistakes before they happen
3. **Modify** (#3) - Safety checks before changes
4. **Add Feature** (#1) - New work after safety checks
5. **Optimize** (#6) - Enhancement after functionality works
6. **Overview** (#10) - Education when unclear

**Example:** User says "I want to add threading locks to optimize"
- First: #5 (Can I) → NO (RED FLAG)
- Then: #4 (Why) → Explain DEC-04
- Then: #6 (Optimize) → Suggest correct optimization

---

## 🎯 DECISION TREE

```
User request received
    ↓
Does it match known workflow trigger? (see table above)
    ↓
YES → Open specific workflow file
    ↓
NO → Determine intent:
    ├─ Question → #4 or #9
    ├─ Permission → #5
    ├─ Problem → #2 or #7 or #8
    ├─ Change → #1 or #3 or #6
    └─ Learning → #10
    ↓
Follow workflow steps
```

---

## 🔗 INTEGRATION WITH V3

### With Gateway Layer
- **ZAPH:** Top workflows pre-indexed  
  📍 NM00/NM00B-ZAPH-Tier2.md

- **Quick Index:** Routes "workflow" keyword  
  📍 NM00/NM00-Quick_Index.md

### With Other Tools
- **Anti-Patterns:** Workflow #5 uses AP checklist  
  📍 ANTI-PATTERNS-CHECKLIST.md

- **REF-ID Directory:** Workflows cite REF-IDs  
  📍 REF-ID-DIRECTORY.md

- **SESSION-START:** Workflow patterns pre-loaded  
  📍 SESSION-START-Quick-Context.md

---

## 💡 USAGE PATTERNS

### Pattern 1: Direct Match
```
User: "I'm getting an error"
↓
Matches: Workflow #2 (Report Error)
↓
Open: Workflow-02-ReportError.md
↓
Follow steps
```

### Pattern 2: Intent-Based
```
User: "How do I improve performance?"
↓
Intent: Optimization
↓
Choose: Workflow #6 (Optimize)
↓
Follow steps
```

### Pattern 3: Multi-Step
```
User: "Add threading locks for speed"
↓
Step 1: Workflow #5 → NO (threading)
Step 2: Workflow #4 → Why (DEC-04)
Step 3: Workflow #6 → Correct optimization
```

---

## 📍 FILE LOCATIONS

**Tool Files (in /nmap root or /tools):**
- WORKFLOWS-PLAYBOOK.md (this hub)
- Workflow-01-AddFeature.md
- Workflow-02-ReportError.md
- Workflow-03-ModifyCode.md
- Workflow-04-WhyQuestions.md
- Workflow-05-CanIQuestions.md
- Workflow-06-Optimize.md
- Workflow-07-ImportIssues.md
- Workflow-08-ColdStart.md
- Workflow-09-DesignQuestions.md
- Workflow-10-ArchitectureOverview.md
- Workflow-11-FetchFiles.md

**Neural Map Files:** Referenced within workflows (NM00-NM07/)

---

## 🔄 MAINTENANCE

**When to update:**
- New workflow pattern identified
- Existing workflow improved based on usage
- New tools/features added to system
- v3 structure changes

**Update process:**
1. Create/update workflow file
2. Add to this hub's Available Workflows section
3. Update selection guide tables
4. Update ZAPH if frequently used
5. Reference in SESSION-START if critical

---

## ⚡ QUICK ACTIONS

**I need to:**

→ Add new feature → Workflow-01-AddFeature.md  
→ Fix an error → Workflow-02-ReportError.md  
→ Change existing code → Workflow-03-ModifyCode.md  
→ Answer "why" question → Workflow-04-WhyQuestions.md  
→ Answer "can I" question → Workflow-05-CanIQuestions.md  
→ Optimize performance → Workflow-06-Optimize.md  
→ Fix import error → Workflow-07-ImportIssues.md  
→ Debug cold start → Workflow-08-ColdStart.md  
→ Explain design → Workflow-09-DesignQuestions.md  
→ Teach architecture → Workflow-10-ArchitectureOverview.md  
→ Fetch/modify file → Workflow-11-FetchFiles.md

---

**END OF HUB**

**Next:** Open specific workflow file based on user request  
**Lines:** ~240 (properly sized hub)  
**Usage:** Start here for any development task

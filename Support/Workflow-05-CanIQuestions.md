# Workflow-05-CanIQuestions.md
**"Can I Do X?" Questions - Step-by-Step**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: Handle permission/validation questions systematically

---

## 🎯 TRIGGERS

- "Can I add threading locks?"
- "Can I import X directly?"
- "Can I use library Y?"
- "Can I skip verification?"
- "Is it okay to...?"

---

## ⚡ DECISION TREE

```
User asks "Can I [X]?"
    ↓
Step 1: Check RED FLAGS
    → Open: AP-Checklist-Critical.md
    → Scan: RED FLAGS section (5 seconds)
    ↓
Is RED FLAG? → Instant NO with REF-ID + explanation
    ↓
Step 2: Check Anti-Patterns
    → Open: AP-Checklist-ByCategory.md
    → Find: Relevant category
    ↓
Is anti-pattern? → NO with explanation + alternative
    ↓
Step 3: Check Design Decisions
    → Open: REF-ID-Directory-DEC.md
    → Search: Related DEC-##
    ↓
Violates decision? → NO with rationale + alternative
    ↓
Step 4: Check Constraints
    → 128MB Lambda limit?
    → Cold start budget?
    → Single-threaded environment?
    ↓
Violates constraint? → NO with explanation + alternative
    ↓
Step 5: If all clear
    → YES with guidance
    → Show SUGA implementation
    → Include verification steps
```

---

## 📋 STEP-BY-STEP PROCESS

### Step 1: Check RED FLAGS (5 seconds)

**File:** AP-Checklist-Critical.md

**Quick check table:**
| Question | Anti-Pattern | Answer |
|----------|--------------|--------|
| Threading locks? | AP-08 | NO |
| Direct imports? | AP-01 | NO |
| Bare except? | AP-14 | NO |
| Skip verification? | AP-27 | NO |
| Leak sentinels? | AP-19 | NO |

**If match found:** Use template from AP-Checklist-Scenarios.md → DONE

---

### Step 2: Check Anti-Patterns (10 seconds)

**File:** AP-Checklist-ByCategory.md

**Process:**
1. Identify relevant category (Import, Concurrency, Security, etc.)
2. Check if question matches any AP in that category
3. If match → Use scenario template

**Example:**
```
User: "Can I add subdirectories?"
Category: Import (AP-05)
Result: NO - Use flat structure (DEC-08)
Template: See AP-Checklist-Scenarios.md #6
```

---

### Step 3: Check Design Decisions (10 seconds)

**File:** REF-ID-Directory-DEC.md

**Process:**
1. Search for keywords in DEC list
2. If relevant DEC found → Read decision
3. If question violates → NO with rationale

**Example:**
```
User: "Can I use multiprocessing?"
Search: "threading", "concurrency"
Find: DEC-04 (No threading locks)
Related: Lambda is single-threaded
Result: NO - Single process, single thread
```

---

### Step 4: Check Constraints (5 seconds)

**Common constraints:**

**Memory:**
- Lambda limit: 128MB (DEC-07)
- Heavy libraries need justification
- Check: Is library < 50MB or essential?

**Cold Start:**
- Target: < 3 seconds
- Heavy imports increase cold start
- Check: Can it be lazy loaded?

**Environment:**
- Single-threaded (DEC-04)
- No concurrent access patterns
- Check: Does solution assume concurrency?

---

### Step 5: Provide Answer

**If NO (most common):**

**Template:**
```
I understand why you'd want to [X], but this isn't recommended because:

Issue: [AP-## or DEC-## or constraint]

Why This Doesn't Work:
[Clear explanation with citations]

Better Alternative:
[Suggested approach that achieves goal correctly]

Implementation:
[Code showing the right way]

References:
- [REF-IDs for further reading]
```

**If YES (less common):**

**Template:**
```
Yes, you can [X]! Here's how to do it correctly:

Requirements:
[What's needed to implement safely]

SUGA Implementation:
```python
# Gateway function
gateway.your_function(args)

# Interface definition
def your_function(args):
    """Interface docstring"""
    return implementation.your_function_impl(args)

# Implementation
def your_function_impl(args):
    """Implementation logic"""
    pass
```

Verification (LESS-15):
[ ] Gateway function exists
[ ] Interface follows pattern
[ ] Implementation correct
[ ] No anti-patterns
[ ] Dependencies verified

References:
[REF-IDs for related patterns]
```

---

## 💡 COMMON SCENARIOS

### Scenario 1: Threading Question
**Quick path:** AP-Checklist-Scenarios.md #1  
**Time:** 15 seconds  
**Answer:** NO (always)

### Scenario 2: Import Question
**Quick path:** AP-Checklist-Scenarios.md #2  
**Time:** 15 seconds  
**Answer:** NO (direct imports), YES (via gateway)

### Scenario 3: Library Question
**Quick path:** Check size + justification  
**Time:** 30 seconds  
**Answer:** Depends (usually NO if > 50MB)

### Scenario 4: Architecture Question
**Quick path:** Check relevant DEC-##  
**Time:** 20 seconds  
**Answer:** Usually NO (decisions are intentional)

---

## ⚠️ COMMON MISTAKES TO AVOID

**DON'T:**
- Say "yes" without verification
- Skip anti-pattern check
- Forget to provide alternative
- Omit REF-ID citations
- Give vague explanations

**DO:**
- Check RED FLAGS first (saves time)
- Use scenario templates when available
- Provide clear rationale with REF-IDs
- Suggest working alternative
- Include implementation example

---

## 🔗 RELATED FILES

**Hub:** WORKFLOWS-PLAYBOOK.md  
**Critical Patterns:** AP-Checklist-Critical.md  
**Scenarios:** AP-Checklist-Scenarios.md  
**Category Table:** AP-Checklist-ByCategory.md  
**Decisions:** REF-ID-Directory-DEC.md

---

## 📊 SUCCESS METRICS

**Workflow succeeded when:**
- ✅ Clear YES or NO answer provided
- ✅ Rationale includes REF-ID citations
- ✅ Alternative suggested (if NO)
- ✅ Implementation shown (if YES)
- ✅ Verification steps included
- ✅ User understands why/why not

**Time:** 15-60 seconds depending on complexity

---

**END OF WORKFLOW**

**Lines:** ~200 (properly sized)  
**Most Used:** Yes (check this workflow frequently)  
**ZAPH:** Tier 2 (high priority)

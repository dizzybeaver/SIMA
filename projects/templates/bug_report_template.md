# File: bug_report_template.md

**Template Version:** 1.0.0  
**Purpose:** Project bug report template  
**Location:** `/sima/projects/templates/`

---

# File: [PROJECT]-BUG-##-[Bug-Name].md

**REF-ID:** [PROJECT]-BUG-##  
**Category:** Project Bug Report  
**Project:** [Project Name] ([PROJECT_CODE])  
**Severity:** Critical | High | Medium | Low  
**Status:** Open | InProgress | Fixed | Verified | Closed  
**Reported:** YYYY-MM-DD  
**Last Updated:** YYYY-MM-DD

---

## ðŸ"‹ BUG SUMMARY

[1-2 sentence description of the bug]

**Impact:** [Brief statement of impact]  
**Affected:** [What's affected - features, users, systems]  
**Frequency:** Always | Often | Sometimes | Rare

---

## ðŸ" BUG DETAILS

### Description
[Detailed description of the bug behavior]

### Expected Behavior
[What should happen]

### Actual Behavior
[What actually happens]

### Steps to Reproduce
1. Step 1
2. Step 2
3. Step 3
4. Observe: [What happens]

**Reproducibility:** 100% | 75% | 50% | 25% | Random

---

## ðŸŽ¯ ENVIRONMENT

### System Information
- **Environment:** Development | Staging | Production
- **Version:** [Software version]
- **Platform:** [OS, runtime, etc.]
- **Configuration:** [Relevant config details]

### Dependencies
- Dependency 1: [version]
- Dependency 2: [version]
- Dependency 3: [version]

---

## 🔍 ROOT CAUSE

### Analysis
[Detailed analysis of why the bug occurs]

**Root Cause:**
[The underlying reason - be specific]

### Contributing Factors
1. Factor 1: [Explanation]
2. Factor 2: [Explanation]
3. Factor 3: [Explanation]

### Code Location
```
File: path/to/file.py
Function: function_name()
Lines: X-Y
```

**Problematic Code:**
```python
# Current buggy implementation
def problematic_function():
    # Bug is here
    pass
```

---

## ðŸ›  FIX

### Solution
[Description of the fix]

**Fixed Code:**
```python
# Corrected implementation
def fixed_function():
    # Fix applied
    pass
```

### Changes Made
1. Change 1: [Description]
2. Change 2: [Description]
3. Change 3: [Description]

### Why This Works
[Explanation of why the fix resolves the issue]

---

## âœ… VERIFICATION

### Test Cases
1. **Test 1:** [Description]
   - Input: [Test input]
   - Expected: [Expected result]
   - Result: [Actual result]
   - Status: âœ… Pass / âŒ Fail

2. **Test 2:** [Description]
   - Input: [Test input]
   - Expected: [Expected result]
   - Result: [Actual result]
   - Status: âœ… Pass / âŒ Fail

### Regression Testing
- âœ… Existing functionality unaffected
- âœ… Related features still work
- âœ… Performance not degraded

---

## ðŸ"Š IMPACT ANALYSIS

### Before Fix
**Issues:**
- Issue 1: [Description and impact]
- Issue 2: [Description and impact]

**Metrics:**
- Error rate: [X%]
- Affected users: [Y]
- Support tickets: [Z]

### After Fix
**Improvements:**
- Improvement 1: [Measurement]
- Improvement 2: [Measurement]

**Metrics:**
- Error rate: [X%] â†' [New %]
- Affected users: [Y] â†' [New #]
- Support tickets: [Z] â†' [New #]

---

## ðŸ"® PREVENTION

### How This Happened
**Process Gaps:**
- Gap 1: [What missed this bug]
- Gap 2: [What missed this bug]

### Prevention Measures
1. **Measure 1:** [What to add/change]
   - When: [In what phase]
   - How: [Implementation]

2. **Measure 2:** [What to add/change]
   - When: [In what phase]
   - How: [Implementation]

### Detection Improvements
**Add Tests:**
- Test type 1: [To catch this class of bugs]
- Test type 2: [To catch this class of bugs]

**Add Monitoring:**
- Metric 1: [To detect similar issues]
- Metric 2: [To detect similar issues]

---

## ⚠️ WORKAROUNDS

### Temporary Workaround (if applicable)
[Description of workaround before fix deployed]

**Steps:**
1. Step 1
2. Step 2
3. Step 3

**Limitations:**
- Limitation 1
- Limitation 2

---

## ðŸ"— RELATED ITEMS

### Related Bugs
- [PROJECT]-BUG-## - [Related bug] - [Relationship]

### Related Lessons
- [PROJECT]-LESS-## - [Lesson] - [What we learned]

### Affected NMPs
- NMP##-PROJECT-## - [Entry] - [How affected]

### Related Code
- File: [path] - [Relationship]
- Function: [name] - [Relationship]

---

## ðŸ'¥ PEOPLE

### Reported By
- Name: [Reporter]
- Date: YYYY-MM-DD
- Contact: [Email/Slack]

### Assigned To
- Name: [Developer]
- Assigned: YYYY-MM-DD

### Reviewed By
- Name: [Reviewer]
- Reviewed: YYYY-MM-DD

---

## ðŸ"… TIMELINE

| Date | Event | Details |
|------|-------|---------|
| YYYY-MM-DD | Reported | [Reporter] found issue in [environment] |
| YYYY-MM-DD | Triaged | Severity set to [level], assigned to [person] |
| YYYY-MM-DD | Root Cause | Identified cause: [brief] |
| YYYY-MM-DD | Fixed | Fix implemented in [branch/commit] |
| YYYY-MM-DD | Tested | Fix verified in [environment] |
| YYYY-MM-DD | Deployed | Released to [environment] |
| YYYY-MM-DD | Verified | Confirmed resolved in production |
| YYYY-MM-DD | Closed | Issue closed |

---

## ðŸ"š REFERENCES

### Internal Documentation
- Incident Report: [Link if applicable]
- Post-Mortem: [Link if applicable]

### External Resources
- Issue Tracker: [Link to ticket]
- Commit: [Link to fix commit]
- PR: [Link to pull request]

---

## ðŸ·ï¸ KEYWORDS

`bug`, `[project-code]`, `[component]`, `[error-type]`, `[severity]`

---

## ðŸ" VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0.0 | YYYY-MM-DD | [Name] | Bug reported |
| 1.1.0 | YYYY-MM-DD | [Name] | Root cause identified |
| 1.2.0 | YYYY-MM-DD | [Name] | Fix implemented |
| 2.0.0 | YYYY-MM-DD | [Name] | Fix verified and deployed |

---

**END OF BUG REPORT**

**Usage Notes:**
- Create bug report as soon as issue identified
- Update status as bug progresses through workflow
- Include reproduction steps for verification
- Document prevention measures to avoid recurrence
- Link to related lessons learned after resolution

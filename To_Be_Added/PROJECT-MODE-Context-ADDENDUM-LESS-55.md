# PROJECT-MODE-Context-ADDENDUM-LESS-55.md

**Purpose:** Updates to PROJECT-MODE-Context.md based on LESS-55  
**Date:** 2025-10-26  
**Reason:** Add architectural constraint checking to prevent wholesale rewrites  
**Integration:** Add these sections to PROJECT-MODE-Context.md

---

## INTEGRATION INSTRUCTIONS

Add the following content to PROJECT-MODE-Context.md in the specified locations:

---

## âš ï¸ SECTION 1: Add After "Rule 4: Pre-Output Checklist"

### Rule 5: Respect Architectural Constraints (LESS-55) ðŸ†•

**MANDATORY:** Before ANY code modification, verify architectural constraints:

```
Pre-Modification Constraint Check:

â˜' Fetched complete current file?
â˜' Read ENTIRE file (not just target section)?
â˜' Checked current file size (line count)?
â˜' Verified file structure (monolithic vs atomized)?
â˜' Identified MINIMAL change needed?
â˜' Checked size limits:
    - Core modules: < 400 lines
    - Interface modules: < 200 lines
    - Gateway modules: < 300 lines
    - Functions: < 50 lines
â˜' Will modification violate size limits?
â˜' Making incremental change (not wholesale rewrite)?

If ANY constraint violated â†' STOP and reassess
```

**Why This Matters:**

**Incremental Change (Correct):**
```
Task: Add 1 function to existing 300-line file
Approach: Fetch file â†' Add function â†' Output complete file
Result: 330-line file, clear what changed
Tokens: ~5,500
Time: 10 minutes
```

**Wholesale Rewrite (Incorrect):**
```
Task: Add 1 function to existing 300-line file
Approach: Recreate entire file from memory
Result: 350-line file, unclear what changed
Tokens: ~30,000
Time: 30 minutes + rework needed
Violation: Wasted 446% tokens, requires redo
```

**Compounding Error Pattern:**
```
Error 1: Wholesale rewrite â†' wastes tokens
    â†"
Error 2: Try to fix with fragment â†' violates mode rules
    â†"
Error 3: Try to fix again â†' more violations
    â†"
Result: 3x token waste, user frustration

Correct response to Error 1:
1. Acknowledge error
2. STOP trying to fix in same session
3. Create session continuation document
4. Next session handles properly
```

**File Structure Verification:**

Before modifying, check:
```
1. Is file already at size limit? (e.g., 390/400 lines)
2. Has file been atomized/split previously?
3. Does atomized structure still exist?
4. Which specific sub-module needs the change?

If file was split:
âœ… Update ONLY the relevant sub-module
âŒ Don't recreate monolithic version
âŒ Don't merge split files back together
```

**Violation Recovery:**

If you realize you've made a wholesale rewrite error:
```
1. STOP immediately
2. Don't compound with fragment attempts
3. Create session continuation doc
4. Let next session handle correctly

DO NOT try to fix in same session - this wastes more tokens
```

---

## ðŸ"Š SECTION 2: Update "Don'ts" List

Add these to the **Don'ts** section:

```
### Don'ts

**âŒ DON'T: Make wholesale rewrites**
- Add single function â†' don't recreate entire file
- Check size limits before modifying
- Verify file structure (split vs monolithic)
- Make MINIMAL incremental changes

**âŒ DON'T: Ignore size constraints**
- Core modules: 400 lines max
- Interface modules: 200 lines max
- Gateway modules: 300 lines max
- Functions: 50 lines max
- Check before AND after changes

**âŒ DON'T: Compound errors**
- If you make Error 1, don't make Error 2
- Stop and create continuation doc
- Don't try to fix mistakes with more mistakes
- Next session handles properly
```

---

## ðŸ"‹ SECTION 3: Update "Activation Checklist"

Update the checklist to include:

```
### Ready for Project Mode When:

- âœ… This file loaded (30-45s)
- âœ… SUGA 3-layer pattern understood
- âœ… LESS-15 verification memorized
- âœ… **ðŸ†• LESS-55 constraint checking understood**
- âœ… Templates available
- âœ… RED FLAGS clear
- âœ… 12 interfaces known
- âœ… Task clearly defined
- âœ… Artifact rules memorized (NEVER chat, ALWAYS complete)
- âœ… **ðŸ†• Size limits known (400/200/300 lines by module type)**
```

---

## ðŸ'¡ SECTION 4: Update "Best Practices - Do's"

Add to the **Do's** section:

```
**âœ… DO: Verify constraints before modifying** ðŸ†•
- Check current file size
- Verify structure (split vs monolithic)
- Identify minimal change needed
- Ensure change won't violate limits
- Make incremental additions, not rewrites

**âœ… DO: Stop on constraint violations** ðŸ†•
- If file at limit, discuss splitting first
- If file was split, update correct sub-module
- If making it worse, stop and reassess
- Don't proceed with constraint violations
```

---

## ðŸš€ SECTION 5: Update "Getting Started - Step 3"

Modify **Step 3: Claude Fetches Files** to:

```
**Step 3: Claude Fetches Files and Verifies Constraints** ðŸ†•
```

```
Claude will:
1. Use Workflow-11-FetchFiles.md
2. Fetch current versions
3. Read complete files
4. Understand current state
5. **ðŸ†• Verify architectural constraints:**
   - Check current file size
   - Identify file structure (monolithic vs split)
   - Verify size limits not violated
   - Determine minimal change approach
6. **ðŸ†• Plan incremental change (not wholesale rewrite)**
```

---

## ðŸŽ¯ SECTION 6: Update "Remember" Section

Add to the **REMEMBER** section at the end:

```
**Critical Rules:**
1. **Fetch first** (LESS-01)
2. **All 3 layers** (SUGA pattern)
3. **Complete files** (artifacts, never chat, never fragments)
4. **Verify always** (LESS-15)
5. **ðŸ†• Respect constraints** (LESS-55) - Check size limits, make incremental changes

**Success = Working code ready to deploy, respecting ALL architectural constraints**
```

---

## ðŸ"Š SECTION 7: Update "Success Metrics"

Add to the **Quality Indicators:**

```
**Quality Indicators:**
- âœ… Zero compilation/import errors
- âœ… Zero anti-pattern violations
- âœ… All 3 SUGA layers present
- âœ… Complete files output (not fragments)
- âœ… LESS-15 checklist complete
- âœ… Zero code in chat (all artifacts)
- âœ… Zero fragment artifacts (all complete)
- âœ… **ðŸ†• Zero size limit violations**
- âœ… **ðŸ†• Incremental changes (not wholesale rewrites)**
- âœ… **ðŸ†• Constraint verification performed**
```

---

## 📝 SECTION 8: Update Version History

Add to version history at the top of PROJECT-MODE-Context.md:

```
**Version:** 1.0.2  
**Date:** 2025-10-26  
**Purpose:** Active development and code implementation context  
**Activation:** "Start Project Work Mode"  
**Load time:** 30-45 seconds (ONE TIME per project session)  
**LATEST FIX:** Added LESS-55 constraint checking (prevents wholesale rewrites)

---

## Version History

- **v1.0.2** (2025-10-26): Added LESS-55 constraint checking to prevent wholesale rewrites
- **v1.0.1** (2025-10-25): Enhanced artifact rules (more prominent)
- **v1.0.0** (2025-10-25): Initial Project Mode context
```

---

## 🔗 SECTION 9: Update Related Lessons References

Add to any "Related Lessons" or "Key Lessons" section:

```
**Critical Lessons:**
- **LESS-01**: Always read complete files before modifying
- **LESS-15**: 5-step verification protocol (includes size checks)
- **LESS-16**: Adaptation over rewriting (incremental philosophy)
- **ðŸ†• LESS-55**: Respect architectural constraints during modifications
- **ARCH-09**: File size limits and atomization principle
```

---

## âœ… IMPLEMENTATION CHECKLIST

To integrate these updates into PROJECT-MODE-Context.md:

```
[ ] Update version to 1.0.2
[ ] Add version history entry
[ ] Add Rule 5 (after Rule 4)
[ ] Update Don'ts section
[ ] Update Activation Checklist
[ ] Update Best Practices Do's
[ ] Update Getting Started Step 3
[ ] Update Remember section
[ ] Update Success Metrics
[ ] Add to Related Lessons
[ ] Verify total file size still < 650 lines
[ ] Update date in header
[ ] Save changes
```

---

## ðŸ"Š EXPECTED IMPACT

**Before LESS-55 Integration:**
- Risk of wholesale rewrites (30K tokens wasted)
- Size limit violations undetected
- Incremental vs wholesale decision unclear
- Compounding error patterns possible

**After LESS-55 Integration:**
- Explicit constraint checking before modifications
- Size limits verified pre-change
- Clear incremental change guidance
- Error recovery protocol defined

**Token Savings:**
- Prevents 446% token waste on rewrites
- Saves 24,500 tokens per avoided wholesale rewrite
- Reduces rework cycles (saves additional 10-20K tokens)

---

## 📚 RELATED DOCUMENTS

**This addendum integrates:**
- **LESS-55**: New lesson (created in this session)
- **ARCH-09**: Existing size limit documentation
- **LESS-15**: Existing verification protocol
- **Workflow-03**: ModifyCode workflow (already uses incremental approach)

**Other modes to consider updating:**
- **DEBUG-MODE-Context.md**: Already has artifact rules, may benefit from constraint checking for fixes
- **SIMA-LEARNING-MODE**: Already has brevity standards, constraint checking implicit

---

## 💡 USAGE NOTES

**When to apply these updates:**
1. After user approves the changes
2. In the next session (current session already too long)
3. Create new version of PROJECT-MODE-Context.md
4. Test with a small modification task

**Testing the updates:**
```
Test Case: Add small function to existing file
Expected: Claude checks size, verifies structure, makes incremental change
Result: Should see explicit constraint verification in Claude's process
```

---

**END OF ADDENDUM**

**Created:** 2025-10-26  
**Purpose:** Integrate LESS-55 into PROJECT-MODE-Context.md  
**Estimated Integration Time:** 10 minutes  
**Estimated Lines Added:** ~80 lines across 9 sections  
**Net Result:** PROJECT-MODE-Context.md v1.0.2 (~630 lines total)

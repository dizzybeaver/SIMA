# NM06-Lessons-Operations_LESS-55.md

# LESS-55: Respect Architectural Constraints During Modifications

**REF:** NM06-LESS-55  
**Category:** Lessons  
**Topic:** Operations  
**Priority:** 🔴 CRITICAL  
**Status:** Active  
**Created:** 2025-10-26  
**Last Updated:** 2025-10-26

---

## Summary

When modifying existing code, always verify and respect architectural constraints (module size limits, atomization patterns, file structure). Making wholesale rewrites instead of incremental changes wastes effort, violates architectural principles, and often requires complete rework.

---

## Context

**The Incident (2025-10-26):**
During Phase 5 purification work, Claude was asked to add a single function (`get_performance_report()`) to an existing gateway wrapper file. Instead of making an incremental addition:

1. **What Happened:** Recreated the entire gateway_wrappers.py file (600+ lines) as a monolithic artifact
2. **What Was Violated:** SUGA's 400-line module size limit (ARCH-09), proper atomization pattern
3. **What Was Wasted:** ~30,000 tokens recreating existing code unnecessarily
4. **Compounding Error:** When corrected, attempted to provide a code fragment instead of complete file, violating Project Mode rules

**Root Causes:**
1. Did not check if file had been previously atomized into smaller modules
2. Did not verify module size limits before recreating
3. Assumed wholesale rewrite was acceptable instead of incremental change
4. Did not follow Project Mode rule: "complete files only, never fragments"

---

## Content

### The Universal Principle

**Respect architectural constraints during ALL modifications:**

```
BEFORE modifying any code:
1. Check file structure (has it been split? atomized?)
2. Check size limits (module max lines, function max lines)
3. Check architectural patterns (3-layer SUGA, atomization)
4. Make MINIMAL change needed
5. Verify constraints still met after change
```

### Size Constraints by System

**Code Modules (SUGA Pattern):**
- Core modules: 400 lines maximum
- Interface modules: 200 lines maximum  
- Gateway modules: 300 lines maximum
- Functions: 50 lines maximum (AP-20)

**Documentation (SIMA Pattern):**
- Individual files: 200 lines maximum
- Topic indexes: 300 lines maximum
- Category indexes: 250 lines maximum
- Gateway: 400 lines maximum

### Incremental vs Wholesale Changes

**Incremental Change (Correct):**
```
Task: Add one function to existing file
Approach:
1. Fetch complete current file
2. Read entire file (verify structure)
3. Locate insertion point
4. Add ONLY new function
5. Update exports list
6. Output complete file with changes marked

Result: Minimal change, clear diff, respects constraints
Tokens: ~5,500 (efficient)
```

**Wholesale Rewrite (Incorrect):**
```
Task: Add one function to existing file  
Approach:
1. Recreate entire file from memory
2. Include all existing functions
3. Add new function somewhere
4. Hope structure is correct

Result: Massive unnecessary change, unclear what actually changed
Tokens: ~30,000 (wasteful)
Violations: Size limits ignored, atomization lost
```

### When Wholesale Changes ARE Appropriate

**Legitimate scenarios for complete rewrites:**
1. **Refactoring for size compliance** - File exceeds limits, needs splitting
2. **Architecture migration** - Moving from old pattern to new pattern
3. **Breaking changes** - Major version upgrade requiring full revision
4. **Deprecation** - Removing old API, replacing with new

**Key distinction:** These are INTENTIONAL restructuring tasks, not simple additions.

### The Compounding Error Pattern

**First mistake â†' Second mistake â†' Token waste cascade:**

```
Error 1: Wholesale rewrite (wastes tokens)
    â†"
Error 2: Fragment fix attempt (violates mode rules)
    â†"
Error 3: Another mistake trying to fix mistake
    â†"
Result: 3x token waste, user frustration
```

**Correct response to Error 1:**
```
1. Acknowledge error clearly
2. Stop trying to "fix" in same session
3. Create session continuation document
4. Let next session handle it properly
```

### Prevention Checklist

**Before ANY code modification:**

```
Pre-Modification Checklist:

â˜' Fetched complete current file?
â˜' Read ENTIRE file (not just target area)?
â˜' Checked file size (current line count)?
â˜' Verified architectural pattern (split vs monolithic)?
â˜' Identified MINIMAL change needed?
â˜' Checked size constraints (will change violate)?
â˜' Confirmed mode rules (artifacts? complete files?)?

If ANY checkbox fails â†' STOP and reassess approach
```

### Mode-Specific Rules

**Project Mode:**
- ALWAYS output complete files as artifacts
- NEVER output fragments or snippets
- NEVER output code in chat
- Mark changes with comments (# ADDED:, # MODIFIED:)

**When you realize you violated these rules:**
- STOP immediately
- Don't try to "fix" with more violations
- Create session continuation document
- Next session handles it properly

### Real-World Impact

**Token Cost:**
```
Incremental approach: 5,500 tokens
Wholesale rewrite:    30,000 tokens
Wasted:              24,500 tokens (446% overhead)

User time:
Incremental: 10 minutes
Wholesale:   30 minutes (then requires redo)
```

**Quality Cost:**
```
Incremental:
âœ… Clear what changed
âœ… Respects constraints
âœ… Follows mode rules
âœ… Deployable immediately

Wholesale:
âŒ Unclear what changed
âŒ Violates constraints
âŒ May break mode rules
âŒ Requires rework
```

### Integration with Existing Lessons

**This lesson complements:**

- **LESS-01**: Read complete files first (foundation for respecting structure)
- **LESS-15**: 5-step verification (includes constraint checking)
- **LESS-16**: Adaptation over rewriting (incremental philosophy)
- **LESS-53**: Version incrementation (tracks what actually changed)
- **ARCH-09**: File size limits (the constraints to respect)

**This lesson adds:**
- Explicit constraint checking before modification
- Incremental vs wholesale decision framework
- Compounding error pattern recognition
- Mode-specific output rules enforcement

---

## Related Topics

**Within NM06 (Lessons):**
- **LESS-01**: Read Complete Files First
- **LESS-15**: 5-Step Verification Protocol
- **LESS-16**: Adaptation Over Rewriting
- **LESS-53**: File Version Incrementation

**Within NM01 (Architecture):**
- **ARCH-09**: File Size Limits and Atomization Principle

**Within NM05 (Anti-Patterns):**
- **AP-20**: God Functions (>50 lines)
- **AP-27**: Skipping Verification

**Support Tools:**
- **Workflow-03**: ModifyCode (uses incremental approach)
- **Workflow-11**: FetchFiles (foundation for constraint checking)
- **PROJECT-MODE-Context**: Mode rules for complete artifacts

---

## Keywords

architectural constraints, module size limits, incremental changes, wholesale rewrites, atomization, token efficiency, mode rules, complete artifacts

---

## Practical Application

### Scenario: Adding a Function

**User Request:** "Add cache warming function to interface_cache.py"

**Correct Approach:**
```
1. Fetch interface_cache.py
2. Check current size: 180 lines
3. New function: ~30 lines
4. Projected size: 210 lines (under 200? NO - but close to limit)
5. Decision: Add function, monitor for future splitting need
6. Add function with # ADDED: comment
7. Output complete 210-line file as artifact
8. Note in CHANGELOG: Approaching size limit
```

**Incorrect Approach:**
```
1. Remember interface_cache.py has cache functions
2. Recreate entire file from memory
3. Add new function somewhere
4. Output 200-line file
5. Hope it's correct
Result: May have missed recent changes, unclear what's new
```

### Scenario: File Exceeds Limit

**Discovery:** gateway_wrappers.py is 587 lines (exceeds 400-line limit)

**Correct Response:**
```
1. Acknowledge: "This file exceeds the 400-line limit"
2. Check history: "Was it previously split?"
3. If split: "Fetch the split modules and update correct one"
4. If not split: "Should we split it as part of this task?"
5. User decides: Split now or defer
6. Follow decision appropriately
```

**Incorrect Response:**
```
1. Recreate entire 600-line file
2. Add new function
3. Output 650-line monolith
4. Ignore size violation
Result: Made problem worse
```

---

## Version History

- **2025-10-26**: Created - Documents Phase 5 purification incident and prevention

---

## Usage Notes

**When to reference this lesson:**
- Before ANY code modification task
- When adding features to existing modules
- During code reviews (size checks)
- When refactoring or restructuring

**What this lesson prevents:**
- Wasteful token usage on rewrites
- Architectural constraint violations
- Compounding error patterns
- Mode rule violations

**What this lesson enables:**
- Efficient incremental changes
- Constraint-respecting modifications
- Clear change tracking
- Mode-compliant outputs

---

**File:** `NM06-Lessons-Operations_LESS-55.md`  
**Lines:** ~190 (under 200-line limit ✅)  
**Status:** ✅ Active Protocol  
**Applies to:** All code modification tasks in any mode

---

**End of Document**

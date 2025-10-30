# WORKFLOWS PLAYBOOK
**SUGA-ISP Lambda Project - Common Workflow Patterns**

Version: 1.0.0  
Date: 2025-10-21  
Purpose: Step-by-step guides for frequent development scenarios

---

## 📋 TABLE OF CONTENTS

1. [Workflow 1: User Wants to Add New Feature](#workflow-1-user-wants-to-add-new-feature)
2. [Workflow 2: User Reports Error or Bug](#workflow-2-user-reports-error-or-bug)
3. [Workflow 3: User Wants to Modify Existing Code](#workflow-3-user-wants-to-modify-existing-code)
4. [Workflow 4: User Asks "Why" Questions](#workflow-4-user-asks-why-questions)
5. [Workflow 5: User Asks "Can I" Questions](#workflow-5-user-asks-can-i-questions)
6. [Workflow 6: User Wants Performance Optimization](#workflow-6-user-wants-performance-optimization)
7. [Workflow 7: User Encounters Import Issues](#workflow-7-user-encounters-import-issues)
8. [Workflow 8: User Wants to Debug Cold Start](#workflow-8-user-wants-to-debug-cold-start)
9. [Workflow 9: User Questions Design Decision](#workflow-9-user-questions-design-decision)
10. [Workflow 10: User Needs Architecture Overview](#workflow-10-user-needs-architecture-overview)
11. [Workflow 11: Fetching Complete Files from File Server](#workflow-11-fetching-complete-files-from-File-Server)
---

## WORKFLOW 1: User Wants to Add New Feature

### Trigger Phrases
- "Can you add X functionality?"
- "I need feature Y"
- "How do I implement Z?"

### Decision Tree
```
User wants new feature
    ↓
Step 1: Check if already exists
    → Search SESSION-START instant answers
    → Search NM01-INTERFACES-Core.md (INT-01 to INT-12)
    ↓
Already exists? → Point to existing gateway function
    ↓
Step 2: Check if anti-pattern
    → Search Anti-Patterns-Checklist.md
    → Search NM05 files
    ↓
Is anti-pattern? → Explain why + suggest alternative
    ↓
Step 3: Determine interface category
    → Which of 12 interfaces? (CACHE, LOGGING, SECURITY, etc.)
    → Search NM01-INDEX-Architecture.md
    ↓
Step 4: Check dependencies
    → Search NM02-CORE-Dependencies.md
    → Verify no circular imports
    ↓
Step 5: Follow decision tree
    → Search NM07-Decision-Logic-Part-1.md (DT-03: Feature Addition)
    ↓
Step 6: Implement using SIMA
    → Gateway function
    → Interface definition
    → Implementation in core
    ↓
Step 7: Verify with 5-step protocol
    → LESS-15 (NM06-LESSONS-2025.10.20.md)
```

### Template Response
```
I'll help add [feature]. Let me check the architecture first:

1. Gateway Check: [Does gateway.X already exist?]
2. Anti-Pattern Check: [Is this prohibited? See AP-##]
3. Interface Category: [Which interface? INT-##]
4. Dependencies: [What does it need? DEP-##]
5. Implementation Path: [Following DT-03]

Here's the SIMA implementation:
[Code with gateway → interface → implementation]

Verification (LESS-15):
- [ ] Gateway function added
- [ ] Interface updated
- [ ] Implementation follows pattern
- [ ] No anti-patterns introduced
- [ ] Dependencies verified
```

### Related REF-IDs
- DT-03: Feature addition decision tree
- LESS-15: 5-step verification protocol
- ARCH-01: Gateway trinity pattern
- RULE-01: Cross-interface via gateway only

---

## WORKFLOW 2: User Reports Error or Bug

### Trigger Phrases
- "I'm getting error X"
- "This isn't working"
- "Lambda is failing with Y"

### Decision Tree
```
User reports error
    ↓
Step 1: Check known bugs
    → Search NM06-BUGS-Critical.md
    → Check BUG-01 (sentinel leak), BUG-02 (CacheMiss), etc.
    ↓
Known bug? → Provide fix with REF-ID
    ↓
Step 2: Request complete context
    → Full error message
    → CloudWatch logs if available
    → What triggered it?
    ↓
Step 3: Check error propagation patterns
    → Search NM03-ERROR-Handling.md
    → Check ERR-01 to ERR-08 patterns
    ↓
Step 4: Search neural maps for keywords
    → Extract key terms from error
    → Search relevant NM files
    ↓
Step 5: Analyze root cause
    → Import issue? → NM02
    → Cache issue? → NM01-INT-01
    → Config issue? → NM01-INT-05
    ↓
Step 6: Provide solution
    → Explain root cause
    → Provide fix with rationale
    → Cite REF-IDs
    → Suggest verification steps
```

### Template Response
```
Let me help debug this error. First, let me check known issues:

1. Known Bugs Check: [BUG-## similar?]
2. Error Pattern: [ERR-## matches this pattern]
3. Root Cause: [Analysis based on neural maps]

The issue is: [Clear explanation with REF-ID]

Solution:
[Code fix or configuration change]

Why this works: [Rationale with citations]

Verification:
- [ ] Error no longer occurs
- [ ] Check CloudWatch logs
- [ ] Test cold start if relevant
```

### Related REF-IDs
- BUG-01: Sentinel leak (535ms)
- BUG-02: _CacheMiss validation
- BUG-03: Cascading failures
- ERR-01 to ERR-08: Error propagation patterns
- LESS-08: Debugger trap usage

---

## WORKFLOW 3: User Wants to Modify Existing Code

### Trigger Phrases
- "Can you change X to do Y?"
- "Modify function Z"
- "Update the code to..."

### Decision Tree
```
User wants modification
    ↓
Step 1: Find current implementation
    → Search project knowledge for filename
    → Read COMPLETE current code
    ↓
Step 2: Check if change violates decisions
    → Search NM04-Design-Decisions files
    → Is this against DEC-##?
    ↓
Violates decision? → Explain why + suggest alternative
    ↓
Step 3: Check if creates anti-pattern
    → Search Anti-Patterns-Checklist.md
    → Would this introduce AP-##?
    ↓
Creates anti-pattern? → Explain + redesign
    ↓
Step 4: Verify dependencies won't break
    → Search NM02-CORE-Dependencies.md
    → Check dependency layers (DEP-01 to DEP-08)
    ↓
Step 5: Implement with SIMA pattern
    → Maintain gateway → interface → implementation
    → Update all three layers if needed
    ↓
Step 6: Verify with 5-step protocol
    → LESS-15 checklist
```

### Template Response
```
I'll modify [component] as requested. Let me verify this change is safe:

Current Implementation:
[Show relevant current code]

Change Analysis:
1. Design Decision Check: [No violations of DEC-##]
2. Anti-Pattern Check: [No AP-## introduced]
3. Dependency Check: [DEP-## remain intact]

Modified Implementation:
[Code changes maintaining SIMA pattern]

Why This Works:
[Rationale with REF-ID citations]

Verification (LESS-15):
- [ ] SIMA pattern maintained
- [ ] No design decision violations
- [ ] No anti-patterns introduced
- [ ] Dependencies verified
- [ ] Functionality preserved/enhanced
```

### Related REF-IDs
- LESS-15: Verification protocol
- ARCH-01: Gateway trinity
- RULE-01: Import rules
- DEP-01 to DEP-08: Dependency layers

---

## WORKFLOW 4: User Asks "Why" Questions

### Trigger Phrases
- "Why no threading locks?"
- "Why is it designed this way?"
- "Why can't I do X?"

### Decision Tree
```
User asks "why"
    ↓
Step 1: Check instant answers
    → Search SESSION-START-Quick-Context.md
    → Is this in top 10 instant answers?
    ↓
Instant answer exists? → Respond immediately with REF-ID
    ↓
Step 2: Search design decisions
    → Search NM04-ARCHITECTURE-Decisions.md
    → Search NM04-TECHNICAL-Decisions.md
    → Search NM04-OPERATIONAL-Decisions.md
    ↓
Step 3: Provide explanation
    → State the decision (DEC-##)
    → Explain rationale
    → Show alternatives considered
    → Cite lessons learned if relevant (LESS-##)
```

### Template Response
```
Great question! Let me explain why [X]:

Decision: [DEC-## title]
Found in: [NM04 file reference]

Rationale:
[Clear explanation of why this design choice was made]

Alternatives Considered:
[What else was evaluated and why rejected]

Lessons Learned:
[LESS-## that informed this decision, if applicable]

Related Decisions:
[Other DEC-## that connect to this]
```

### Common "Why" Questions & Answers
```
Q: Why no threading locks?
A: DEC-04 - Lambda is single-threaded, locks unnecessary and harmful

Q: Why gateway pattern?
A: DEC-01 - SIMA prevents circular imports, provides single entry point

Q: Why sentinel sanitization?
A: DEC-05 - Prevents sentinel leak bug (BUG-01), 535ms cost

Q: Why flat file structure?
A: DEC-08 - Proven simple, avoids import complexity

Q: Why no heavy libraries?
A: DEC-07 - 128MB Lambda limit, cold start performance

Q: Why SSM token-only?
A: DEC-21 - Simplified config, token metadata sufficient

Q: Why router sanitization?
A: DEC-05 - Layer boundary protection, prevents leaks
```

### Related REF-IDs
- DEC-01 to DEC-25: All design decisions
- LESS-01 to LESS-16: Lessons learned
- BUG-01 to BUG-04: Bugs that informed decisions

---

## WORKFLOW 5: User Asks "Can I" Questions

### Trigger Phrases
- "Can I add threading locks?"
- "Can I import X directly?"
- "Can I use library Y?"

### Decision Tree
```
User asks "can I"
    ↓
Step 1: Check RED FLAGS
    → Search SESSION-START-Quick-Context.md
    → Is this in RED FLAGS list?
    ↓
Is RED FLAG? → Immediate NO with explanation
    ↓
Step 2: Check anti-patterns
    → Search Anti-Patterns-Checklist.md
    → Search NM05 files
    ↓
Is anti-pattern? → NO with explanation + alternative
    ↓
Step 3: Check design decisions
    → Search NM04 files
    → Does this violate DEC-##?
    ↓
Violates decision? → NO with rationale + alternative
    ↓
Step 4: Check constraints
    → 128MB Lambda limit
    → Cold start budget
    → Single-threaded environment
    ↓
Violates constraint? → NO with explanation + alternative
    ↓
Step 5: If all clear
    → YES with guidance
    → Show how to implement following SIMA
```

### Template Response (NO)
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
[REF-IDs for further reading]
```

### Template Response (YES)
```
Yes, you can [X]! Here's how to do it correctly:

Requirements:
[What's needed to implement this safely]

SIMA Implementation:
[Code following gateway → interface → implementation]

Verification:
[Checklist to ensure it's done right]

References:
[REF-IDs for related patterns]
```

### Related REF-IDs
- AP-01 to AP-28: All anti-patterns
- DEC-01 to DEC-25: Design decisions
- RULE-01 to RULE-08: Hard rules

---

## WORKFLOW 6: User Wants Performance Optimization

### Trigger Phrases
- "How can I make this faster?"
- "Reduce cold start time"
- "Optimize performance"

### Decision Tree
```
User wants optimization
    ↓
Step 1: Identify bottleneck
    → Check if cold start related
    → Check if runtime related
    → Get CloudWatch metrics if available
    ↓
Step 2: Check known optimizations
    → Search NM03-CORE-Pathways.md (cold start)
    → Search NM06-LESSONS-Performance
    → Search WISDOM-01 (operational insights)
    ↓
Step 3: Check cache opportunities
    → Search NM01-INTERFACES-Core.md (INT-01: CACHE)
    → Is this already cached?
    → Can this be cached?
    ↓
Step 4: Check lazy loading
    → Search LMMS documentation
    → Can imports be deferred?
    → Can initialization be delayed?
    ↓
Step 5: Provide optimization
    → Code changes
    → Configuration changes
    → Architecture changes if needed
```

### Template Response
```
I'll help optimize [component]. Let me analyze the performance:

Current Performance:
[Metrics if available, or estimated impact]

Bottleneck Analysis:
[What's causing the slowdown]

Optimization Strategy:
1. [First optimization - highest impact]
2. [Second optimization - medium impact]
3. [Third optimization - nice to have]

Implementation:
[Code changes with explanations]

Expected Improvement:
[Estimated performance gain]

References:
- [PATH-## for cold start optimizations]
- [LESS-## for performance lessons]
- [WISDOM-01 for operational insights]
```

### Common Performance Optimizations
```
Cold Start:
- PATH-01: Lazy imports (defer heavy libraries)
- PATH-02: Gateway trinity (initialization order)
- DEC-07: Avoid heavy dependencies

Runtime:
- INT-01: Cache frequently accessed data
- ERR-05: Short-circuit validation
- DEC-10: Early return patterns

Memory:
- DEC-07: 128MB limit awareness
- LMMS: Lazy Memory Management System
- Remove unused imports
```

### Related REF-IDs
- PATH-01: Cold start pathway
- PATH-02: Warm start pathway
- INT-01: CACHE interface
- DEC-07: Dependency management
- WISDOM-01: Operational wisdom

---

## WORKFLOW 7: User Encounters Import Issues

### Trigger Phrases
- "Import error"
- "Circular import"
- "Module not found"

### Decision Tree
```
User has import issue
    ↓
Step 1: Check import rules
    → Search SESSION-START-Quick-Context.md
    → RULE-01: Always import gateway, never core
    ↓
Step 2: Identify violation
    → Is user importing core file directly?
    → Is there circular dependency?
    → Search NM02-RULES-Import.md
    ↓
Step 3: Check dependency layers
    → Search NM02-CORE-Dependencies.md
    → DEP-01 to DEP-08 layers
    → Are they importing upward?
    ↓
Step 4: Provide fix
    → Show correct gateway import
    → Explain why it failed
    → Verify no other violations
```

### Template Response
```
I see the import issue. Let me fix this:

Problem:
[What's wrong with current import]

Rule Violation:
[RULE-## that was violated]

Why It Failed:
[Technical explanation with DEP-## reference]

Correct Implementation:
```python
# ❌ WRONG
from cache_core import get_value

# ✅ CORRECT
import gateway
value = gateway.cache_get(key)
```

Explanation:
[Why gateway import works + RULE-01 citation]

Verification:
- [ ] No direct core imports
- [ ] Gateway provides function
- [ ] No circular dependencies
```

### Related REF-IDs
- RULE-01: Cross-interface via gateway only
- RULE-02: Gateway is root import
- DEP-01 to DEP-08: Dependency layers
- AP-01: Direct cross-interface imports
- DEC-01: SIMA pattern choice

---

## WORKFLOW 8: User Wants to Debug Cold Start

### Trigger Phrases
- "Cold start is slow"
- "Lambda takes long to start"
- "First request timeout"

### Decision Tree
```
User debugging cold start
    ↓
Step 1: Check current cold start profile
    → Search NM03-CORE-Pathways.md (PATH-01)
    → What's the normal cold start time?
    ↓
Step 2: Request debug data
    → CloudWatch logs
    → Duration metrics
    → Memory usage
    ↓
Step 3: Identify heavy imports
    → Search NM02-CORE-Dependencies.md
    → Which libraries are heavy?
    → Can they be lazy loaded?
    ↓
Step 4: Check LMMS utilization
    → Search LEE Architecture docs
    → Is lazy loading configured?
    → Are imports deferred?
    ↓
Step 5: Check cache initialization
    → Search NM01-INTERFACES-Core.md (INT-01)
    → Is cache warming too aggressive?
    ↓
Step 6: Provide optimization
    → Defer heavy imports
    → Reduce initialization
    → Follow PATH-01 guidance
```

### Template Response
```
Let me help debug the cold start. Current analysis:

Cold Start Profile (PATH-01):
- Expected: [Normal cold start time for this Lambda]
- Actual: [User's cold start time]
- Delta: [Difference]

Likely Causes:
1. [Heavy import] → [Solution]
2. [Eager initialization] → [Solution]
3. [Cache warming] → [Solution]

Optimization Plan:
[Step-by-step changes to reduce cold start]

Implementation:
```python
# Move heavy imports inside functions
def heavy_operation():
    import heavy_library  # Lazy import
    ...
```

Expected Improvement:
[Estimated cold start reduction]

References:
- PATH-01: Cold start pathway
- LMMS: Lazy Memory Management
- DEC-07: Dependency management
```

### Related REF-IDs
- PATH-01: Cold start pathway
- PATH-02: Warm start pathway
- DEC-07: Dependencies < 128MB
- LESS-06: Lambda environment lessons
- WISDOM-01: Operational insights

---

## WORKFLOW 9: User Questions Design Decision

### Trigger Phrases
- "Why was this designed this way?"
- "Can we change [design]?"
- "I don't understand the reasoning"

### Decision Tree
```
User questions design
    ↓
Step 1: Find the decision
    → Search NM04-ARCHITECTURE-Decisions.md
    → Search NM04-TECHNICAL-Decisions.md
    → Search NM04-OPERATIONAL-Decisions.md
    → Identify DEC-##
    ↓
Step 2: Gather context
    → What problem does it solve?
    → What alternatives were considered?
    → What lessons informed it?
    ↓
Step 3: Check related lessons
    → Search NM06-LESSONS files
    → Are there LESS-## that explain why?
    ↓
Step 4: Check if bugs led to decision
    → Search NM06-BUGS-Critical.md
    → Did BUG-## drive this?
    ↓
Step 5: Provide complete explanation
    → Decision rationale
    → Historical context
    → Alternatives considered
    → Current validity
```

### Template Response
```
Excellent question about [design element]. Let me explain the history:

Design Decision: [DEC-## title]
Category: [Architecture/Technical/Operational]
Date: [When decided]

Current Implementation:
[What we have now]

Rationale:
[Why this design was chosen]

Alternatives Considered:
[What else was evaluated and why rejected]

Lessons That Informed This:
[LESS-## or BUG-## that led to this decision]

Is It Still Valid?
[Current assessment - usually yes, occasionally "could be revisited"]

If You Want to Change It:
[What would need to be reconsidered, what risks exist]

References:
- [DEC-## for decision]
- [LESS-## for lessons]
- [BUG-## if bugs influenced it]
```

### Related REF-IDs
- DEC-01 to DEC-25: All design decisions
- LESS-01 to LESS-16: Lessons learned
- BUG-01 to BUG-04: Critical bugs
- WISDOM-01: Operational wisdom

---

## WORKFLOW 10: User Needs Architecture Overview

### Trigger Phrases
- "Explain the architecture"
- "How does this work?"
- "Give me an overview"

### Decision Tree
```
User needs overview
    ↓
Step 1: Determine scope
    → Entire system? → Start with ARCH-01
    → Specific component? → Search relevant NM
    → Decision rationale? → Search NM04
    ↓
Step 2: Provide high-level first
    → SIMA pattern (Gateway → Interface → Implementation)
    → 12 core interfaces
    → Key design principles
    ↓
Step 3: Layer in details
    → Show code examples
    → Explain workflows
    → Cite REF-IDs for deep dives
    ↓
Step 4: Offer deep dives
    → "Want to know more about X? See [NM##]"
    → "Read [DEC-##] for detailed rationale"
```

### Template Response (Full Architecture)
```
I'll explain the SUGA-ISP Lambda architecture:

## High-Level Overview (ARCH-01)

The system uses SIMA architecture:
- **Gateway** (gateway.py) → Single entry point for all operations
- **Interfaces** → 12 standardized interfaces (CACHE, LOGGING, SECURITY, etc.)
- **Implementation** → Core files that implement interfaces

```
External Code
    ↓
gateway.py (routing layer)
    ↓
Interface Layer (standardized APIs)
    ↓
Core Implementation (actual logic)
```
```
# Workflow #11: Fetching Complete Files from File Server

**Date:** 2025-10-21  
**Purpose:** Complete file retrieval for code modifications  
**Priority:** 🔴 CRITICAL (affects all code modification workflows)

---

## When to Use This Workflow

**Trigger phrases:**
- "Modify [filename].py"
- "Update [filename].py to add..."
- "Fix [filename].py"
- "Add function to [filename].py"
- "Output complete [filename].py"
- "Show me the full [filename].py"

**Key indicator:** User wants code changes or complete file output

---

## The Workflow

### Step 1: Identify the File Needed
```
User asks to modify code
  ↓
Extract filename from request
  ↓
If filename provided:
  → Use provided filename
  
If concept provided ("cache system"):
  → project_knowledge_search to identify file
  → Extract filename from results
```

**Example:**
```
User: "Modify the cache system to add batch operations"
  ↓
Search: "cache implementation file"
  ↓
Result: "cache_core.py implements cache operations"
  ↓
Filename: cache_core.py
```

---

### Step 2: Check for Known Issues
```
Is filename == "gateway_core.py"?
  YES → Use Fallback Method (manual paste)
  NO  → Continue to Step 3
```

**Fallback message:**
```
"I'll need the complete gateway_core.py file to make these changes. 
This file has a temporary cache issue. Could you paste the complete 
file from GitHub?"
```

---

### Step 3: Search for Context (Optional but Recommended)
```
project_knowledge_search("[filename] architecture patterns")
```

**Purpose:**
- Understand file's role in system
- Identify related patterns
- Find design decisions
- Check anti-patterns

**Time:** 5 seconds  
**Tokens:** ~2K

**Example:**
```
project_knowledge_search("cache_core.py patterns sentinel")
  ↓
Results: DEC-05 (sentinel sanitization), RULE-01 (imports)
  ↓
Context: Apply sentinel sanitization, use gateway for imports
```

---

### Step 4: Fetch Complete File from Server
```
web_fetch("https://claude.dizzybeaver.com/src/[filename].py")
```

**Expected result:**
- ✅ Complete file (header to EOF)
- ✅ All imports, classes, functions
- ✅ All exports
- ✅ No truncation

**Time:** 10 seconds  
**Tokens:** ~3K

**Verify completeness:**
```
Check for:
- [ ] Apache license header
- [ ] Import statements
- [ ] Function/class definitions
- [ ] __all__ exports
- [ ] # EOF marker at end
```

---

### Step 5: Check Patterns and Anti-Patterns
```
project_knowledge_search("[concept] anti-patterns")
```

**Purpose:**
- Verify no anti-pattern violations
- Apply correct patterns
- Check design decisions

**Common checks:**
- RULE-01: Cross-interface imports via gateway?
- DEC-04: No threading locks?
- AP-14: No bare except clauses?
- LESS-15: File verification protocol?

**Time:** 5 seconds  
**Tokens:** ~2K

---

### Step 6: Modify Complete File
```
Modify the complete file:
1. Make requested changes
2. Follow SIMA pattern
3. Apply design decisions
4. Verify no anti-patterns
5. Check imports follow RULE-01
6. Ensure complete file structure
```

**Verification checklist (LESS-15):**
- [ ] Read complete file first
- [ ] Verified SIMA pattern compliance
- [ ] Checked anti-patterns (AP-01, AP-08, AP-14, etc.)
- [ ] Verified dependencies (no circular imports)
- [ ] Complete file returned (header to EOF)

---

### Step 7: Return Complete Modified File
```
Create artifact with:
1. Complete modified file
2. Verification notes
3. REF-ID citations
4. What changed summary
```

**Artifact format:**
```python
"""
[filename].py - [Description]
Version: [version]
Description: [what this file does]

MODIFICATIONS (YYYY-MM-DD):
- Added: batch_get() function
- Modified: Imports to include batch operation support
- Applied: DEC-05 (sentinel sanitization)
- Verified: LESS-15 protocol completed

[Complete Apache License Header]
"""

[Complete file contents from header to EOF]

# EOF
```

---

## Error Handling

### Error: web_fetch Returns UNKNOWN_ERROR
```
File: gateway_core.py specifically
Cause: Cloudflare cache issue (temporary)
Solution: Use fallback method (manual paste)

Response:
"gateway_core.py has a temporary cache issue. Could you paste 
the complete file from GitHub so I can modify it correctly?"
```

---

### Error: web_fetch Returns PERMISSIONS_ERROR
```
Cause: URL not in uploaded file list
Solution: Check directory listing for correct filename

Steps:
1. web_fetch("https://claude.dizzybeaver.com/src/")
2. Find correct filename in listing
3. Retry with correct filename
```

**Response:**
```
"I couldn't find that file. Let me check the directory listing...
[fetch directory]
I found these similar files: [list options]
Which one did you mean?"
```

---

### Error: File Appears Incomplete
```
Symptoms:
- No # EOF marker
- Missing imports
- Missing functions
- Line count seems low

Cause: Display truncation (not file server issue)
Solution: File server returns complete files - verify visually

Action:
"The file fetched successfully (XXX lines). If it appears truncated 
in display, the complete file is available for modification."
```

---

### Error: Don't Know Filename
```
Cause: User gave concept, not filename
Solution: Search project knowledge first

Steps:
1. project_knowledge_search("[concept] implementation")
2. Extract filename from results
3. Proceed with fetch
```

**Example:**
```
User: "Modify the logging system"
  ↓
Search: "logging implementation file"
  ↓
Result: "logging_core.py, logging_manager.py"
  ↓
Clarify: "I found logging_core.py and logging_manager.py. 
         Which would you like to modify?"
```

---

## Performance Metrics

### Workflow Timing
- **Step 1** (Identify): 2 seconds
- **Step 2** (Check issues): 1 second
- **Step 3** (Context): 5 seconds
- **Step 4** (Fetch): 10 seconds
- **Step 5** (Patterns): 5 seconds
- **Step 6** (Modify): 30 seconds
- **Step 7** (Return): 5 seconds
- **Total:** ~58 seconds

### Token Usage
- Context search: ~2K
- File fetch: ~3K
- Pattern check: ~2K
- Modification: ~2K
- **Total:** ~9K tokens

### Comparison to Old Method
- **Old method:** 3-5 minutes, 50-80K tokens, fragmented code
- **New method:** 58 seconds, 9K tokens, complete code
- **Improvement:** 83% time saved, 86% tokens saved

---

## Template Responses

### Success Response
```
I've modified [filename].py to [description of changes].

Changes made:
- [Change 1]
- [Change 2]
- [Change 3]

Patterns applied:
- RULE-01: Cross-interface imports via gateway
- DEC-05: Sentinel sanitization applied
- LESS-15: File verification completed

The complete modified file is in the artifact below, ready to deploy.

[Artifact with complete file]
```

---

### Fallback Response (gateway_core.py)
```
I'll need the complete gateway_core.py file to make these changes correctly.

This file has a temporary Cloudflare cache issue. Could you paste the 
complete file from GitHub? Once I have it, I'll:

1. Add [requested feature]
2. Follow SIMA pattern
3. Verify with LESS-15 protocol
4. Return complete modified file
```

---

### Clarification Response
```
I found these files that might match your request:
- cache_core.py (19K) - Main cache implementation
- interface_cache.py (8.9K) - Cache interface router
- cache_manager.py - Cache singleton manager

Which file would you like to modify?
```

---

## Related Workflows

**Before this workflow:**
- Workflow #4: Understanding Architecture (context gathering)
- Workflow #5: "Can I" Questions (verify patterns)

**After this workflow:**
- LESS-15: File Verification Protocol (completeness check)
- Workflow #2: Deploying Changes (if user asks)

---

## Related Documentation

**File Access:**
- File-Server-Quick-Reference.md
- File-Access-Methods.md
- Project-Knowledge-Search-Usage.md

**Verification:**
- LESS-15: File Verification Protocol
- Anti-Patterns-Checklist.md

**Architecture:**
- SESSION-START-Quick-Context.md (file server section)
- LESS-ZZ: File Server Integration

---

## Best Practices

### Always Do
✅ Search for context before fetching (understand patterns)  
✅ Fetch complete file from server (don't use snippets)  
✅ Check patterns and anti-patterns (verify compliance)  
✅ Modify complete file (full context)  
✅ Return complete file (header to EOF)  
✅ Apply LESS-15 verification (completeness check)  
✅ Cite REF-IDs (DEC-##, RULE-##, etc.)

### Never Do
❌ Use project_knowledge_search to get code files  
❌ Return fragmented code  
❌ Skip pattern verification  
❌ Assume snippets are complete  
❌ Output code without full context  
❌ Skip EOF marker verification

---

## Success Criteria

**✅ Workflow succeeded when:**
- Complete file fetched from server
- All patterns verified (SIMA, anti-patterns)
- Complete modified file returned
- Verification checklist completed (LESS-15)
- REF-IDs cited for decisions
- User can deploy immediately

**❌ Workflow needs improvement when:**
- File fragments returned
- Missing sections in output
- Pattern violations present
- No verification performed
- Manual paste required (except gateway_core.py)

---

## Examples

### Example 1: Add Function to Cache
```
User: "Add a batch_get function to cache_core.py"

Step 1: Identify file → cache_core.py ✓

Step 2: Check issues → Not gateway_core.py ✓

Step 3: Search context
  → "cache_core.py patterns batch"
  → Found: LUGSIntegratedCache class, DEC-05 sanitization

Step 4: Fetch file
  → web_fetch("https://claude.dizzybeaver.com/src/cache_core.py")
  → Got: Complete 600-line file ✓

Step 5: Check patterns
  → "cache anti-patterns sentinel"
  → Found: AP-19 (sentinel leaks), DEC-05 (sanitization)

Step 6: Modify
  → Add batch_get() function
  → Apply sentinel sanitization
  → Follow SIMA pattern
  → Verify imports via gateway

Step 7: Return complete file
  → Artifact with complete modified cache_core.py
  → Ready to deploy ✓

Result: 58 seconds, 9K tokens, complete working code
```

---

### Example 2: Fix Error in Logging
```
User: "Fix the error in logging_manager.py"

Step 1: Identify file → logging_manager.py ✓

Step 2: Check issues → Not gateway_core.py ✓

Step 3: Search context
  → "logging_manager.py errors"
  → Found: Common error patterns, DEBUG_MODE paths

Step 4: Fetch file
  → web_fetch("https://claude.dizzybeaver.com/src/logging_manager.py")
  → Got: Complete file ✓

Step 5: Check patterns
  → "logging anti-patterns"
  → Found: AP-14 (bare except), ERR-02 (propagation)

Step 6: Modify
  → Fix error
  → Verify no bare except clauses
  → Check error propagation pattern

Step 7: Return complete file
  → Artifact with fixed logging_manager.py
  → Ready to deploy ✓

Result: 55 seconds, 8K tokens, working fix
```

---

### Example 3: Modify gateway_core.py (Fallback)
```
User: "Add a new operation to gateway_core.py"

Step 1: Identify file → gateway_core.py ✓

Step 2: Check issues → IS gateway_core.py ⚠️
  → Use fallback method

Response:
"I'll need the complete gateway_core.py file to make these changes.
This file has a temporary cache issue. Could you paste the complete 
file from GitHub?"

User: [pastes complete file]

Step 3-7: Continue with manual file
  → Modify complete file
  → Return complete modified file ✓

Result: Manual paste required (expected), complete working code
```

---

**END OF WORKFLOW #11**

**Status:** ✅ Production Ready  
**Priority:** CRITICAL  
**Impact:** Eliminates fragmented code problem  
**Savings:** 70K tokens + 4 minutes per code modification

```

## The 12 Core Interfaces (INT-01 to INT-12)

[List with brief description of each]

## Key Design Principles

1. **Single Entry Point** (DEC-01)
   - All operations go through gateway
   - Prevents circular imports
   - Centralized routing

2. **No Threading Locks** (DEC-04)
   - Lambda is single-threaded
   - Locks unnecessary and harmful

3. **Flat File Structure** (DEC-08)
   - No subdirectories (except home_assistant/)
   - Proven simple architecture

4. **Lazy Loading** (LMMS)
   - Defer heavy imports
   - Optimize cold start

## Common Operations

[Show 2-3 code examples of typical operations]

## Want to Learn More?

- Architecture details: NM01-CORE-Architecture.md
- Design decisions: NM04 files
- Operation flows: NM03-CORE-Pathways.md
- Best practices: NM06-LESSONS files
```

### Related REF-IDs
- ARCH-01: Gateway trinity
- ARCH-02: Gateway execution engine
- INT-01 to INT-12: All interfaces
- DEC-01: SIMA pattern choice
- RULE-01: Import rules

---

## 📊 WORKFLOW SELECTION QUICK REFERENCE

| User Intent | Use Workflow | Search First | Time to Resolution |
|-------------|-------------|--------------|-------------------|
| Add feature | #1 | NM01, NM05, NM07 | 2-3 min |
| Report error | #2 | NM06, NM03 | 1-2 min |
| Modify code | #3 | Find file, NM04, NM05 | 2-3 min |
| Ask "why" | #4 | SESSION-START, NM04 | 30 sec - 1 min |
| Ask "can I" | #5 | SESSION-START, NM05 | 30 sec - 1 min |
| Optimize | #6 | NM03, NM06, WISDOM | 2-3 min |
| Import issue | #7 | NM02 | 30 sec |
| Cold start | #8 | NM03, PATH-01 | 2-3 min |
| Question design | #9 | NM04, NM06 | 1-2 min |
| Architecture overview | #10 | NM01, SESSION-START | 1-2 min |

---

## 🎯 BEST PRACTICES FOR USING WORKFLOWS

1. **Identify Pattern Early**
   - Recognize which workflow applies in first 5 seconds
   - Start with most specific workflow

2. **Follow Decision Trees**
   - Don't skip steps
   - Each step has a purpose
   - Early exits save time

3. **Always Cite REF-IDs**
   - Every answer should have sources
   - Use REF-ID-Directory.md for cross-references
   - Build user's understanding of neural maps

4. **Use Template Responses**
   - Consistent format improves clarity
   - Templates ensure nothing is missed
   - Adapt templates as needed

5. **Verify Before Suggesting**
   - Check Anti-Patterns-Checklist BEFORE responding
   - Verify with 5-step protocol (LESS-15)
   - Prevent mistakes proactively

---

## 🔄 WHEN WORKFLOWS OVERLAP

Sometimes multiple workflows apply. Priority order:

1. **Error/Bug** (#2) - Always handle errors first
2. **"Can I"** (#5) - Prevent mistakes before they happen
3. **Modify** (#3) - Check safety before modifying
4. **Add Feature** (#1) - New work after safety checks
5. **Optimize** (#6) - Enhancement after functionality works
6. **Overview** (#10) - Education when unclear

Example: User says "I want to add threading locks to optimize performance"
- First: Workflow #5 (Can I) → NO (RED FLAG)
- Then: Workflow #4 (Why) → Explain DEC-04
- Then: Workflow #6 (Optimize) → Suggest correct optimization

---

**END OF WORKFLOWS PLAYBOOK**

For questions about workflow usage, see Custom Instructions or SESSION-START-Quick-Context.md

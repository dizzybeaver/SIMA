# LESS-54.md

**Version:** 1.0.0  
**Date:** 2025-11-01  
**Purpose:** Document the 400-line limit constraint for neural map files

**REF-ID:** LESS-54  
**Category:** Documentation / Operations  
**Type:** LESS (Lesson Learned)  
**Priority:** 🔴 Critical  
**Status:** Active  
**Impact:** System Functionality

---

## Summary

All neural map files MUST be ≤400 lines due to `project_knowledge_search` truncation behavior. Files exceeding 400 lines lose middle content entirely (lines 201-N-200 disappear), making them partially inaccessible to Claude and breaking the knowledge system.

**Core Principle:** The 400-line limit is not a style guideline—it's a hard technical constraint based on how project_knowledge_search retrieves content.

---

## The Technical Constraint

**project_knowledge_search Behavior:**
```
For any file retrieved:
  Returns: First 200 lines + Last 200 lines
  Maximum viewable: 400 lines total
  
If file > 400 lines:
  Lines 1-200: ✅ Accessible
  Lines 201-(N-200): ❌ TRUNCATED (completely lost)
  Lines (N-199)-N: ✅ Accessible
  
Result: Middle content invisible to Claude
```

**Real Example:**
```
700-line file:
  Lines 1-200: Visible
  Lines 201-500: LOST (300 lines = 43% of content)
  Lines 501-700: Visible
  
Impact:
  - Cross-references in middle: Broken
  - Examples in middle: Missing
  - Key patterns: Inaccessible
  - Anti-patterns: Lost
  - Related patterns: Invisible
```

---

## Why This Matters

**Functional Impact:**
- Claude cannot access truncated content
- Queries return incomplete information
- Cross-references become broken links
- Knowledge system partially fails
- User gets wrong/incomplete answers

**Real Scenario:**
```
User asks: "What's the AWS Lambda cost model pattern?"

If AWS-LESS-11 is buried in middle of 700-line combined file:
  → project_knowledge_search retrieves file
  → AWS-LESS-11 content in lines 201-500 (truncated)
  → Claude sees: "File exists but content missing"
  → Response: "I don't have information about that"
  
Even though the content EXISTS, it's INACCESSIBLE.
```

**System Degradation:**
- 1 file >400 lines = 1 topic partially inaccessible
- 10 files >400 lines = 10 topics partially broken
- 50 files >400 lines = Knowledge system 25% broken

---

## The Rule

**Mandatory Constraint:**
```
ALL neural map files MUST be ≤400 lines

No exceptions. No "just this once." No "it's only 450."

If content exceeds 400 lines:
  → Split into multiple files
  → Each file = ONE focused topic
  → Link files with cross-references
```

**File Size Guidelines:**
```
✅ Safe: ≤350 lines (comfortable margin)
⚠️ Caution: 351-390 lines (watch carefully)
❌ Danger: 391-400 lines (absolute limit)
❌ Forbidden: >400 lines (will be truncated)
```

---

## Common Mistakes

**Mistake 1: Combining Multiple Topics**
```
❌ WRONG:
  File: "AWS-Patterns-Combined.md" (700 lines)
  Contains:
    - AWS-LESS-10 (Transformation) - 153 lines
    - AWS-LESS-11 (Cost Model) - 267 lines  
    - AWS-LESS-12 (Timeouts) - 288 lines
    
  Result: Lines 201-500 lost = AWS-LESS-11 invisible

✅ RIGHT:
  File 1: "AWS-APIGateway-Transformation_AWS-LESS-10.md" (153 lines)
  File 2: "AWS-Lambda-CostModel_AWS-LESS-11.md" (267 lines)
  File 3: "AWS-Lambda-TimeoutConstraints_AWS-LESS-12.md" (288 lines)
  
  Result: All 3 files fully accessible
```

**Mistake 2: "We Can Condense It"**
```
❌ WRONG Thinking:
  "These 3 separate 300-line files could be 1 file"
  "Let's combine them to reduce file count"
  "More efficient to have everything in one place"
  
❌ Result:
  900-line file → 500 lines truncated
  55% of content lost
  System broken

✅ RIGHT Thinking:
  "Keep files focused and ≤400 lines"
  "Each file covers ONE topic completely"
  "Smaller files = fully accessible content"
```

**Mistake 3: Ignoring Line Count**
```
❌ WRONG:
  Create file without checking length
  "It feels about right"
  Ignore warnings at 350+ lines
  
✅ RIGHT:
  Always check line count before finalizing
  If approaching 380 lines, consider splitting
  If >400 lines, MUST split (no exceptions)
```

---

## Correct Patterns

**Pattern 1: Single-Topic Files**
```
✅ Each file = ONE complete topic
✅ Keep file ≤350 lines (comfortable margin)
✅ Use cross-references to link related topics
✅ Filename indicates specific topic

Example:
  LESS-15.md (Operations) - 287 lines
  LESS-16.md (Performance) - 312 lines
  LESS-17.md (Cold Start) - 298 lines
  
Each file fully accessible, topics linked via cross-refs
```

**Pattern 2: Hierarchical Organization**
```
✅ Use directories to group related files
✅ Index files to navigate between files
✅ Each individual file ≤400 lines

Example:
  /aws/lambda/
    Lambda-Index.md (150 lines)
    AWS-Lambda-StatelessExecution_AWS-LESS-03.md (395 lines)
    AWS-Lambda-CostModel_AWS-LESS-11.md (267 lines)
    AWS-Lambda-TimeoutConstraints_AWS-LESS-12.md (288 lines)
```

**Pattern 3: Splitting Large Topics**
```
If topic naturally >400 lines, split into sub-topics:

Instead of:
  ❌ "Complete-HTTP-Guide.md" (800 lines)
  
Create:
  ✅ "HTTP-Client-Core.md" (380 lines)
  ✅ "HTTP-Client-Transformation.md (320 lines)
  ✅ "HTTP-Client-Validation.md" (290 lines)
  
Link them: Each file cross-references the others
```

---

## Verification Process

**Before Finalizing Any Neural Map File:**

1. **Check Line Count**
   ```
   wc -l filename.md
   
   If result > 400: MUST SPLIT
   If result > 380: STRONGLY CONSIDER SPLITTING
   If result < 350: ✅ SAFE
   ```

2. **Verify Content Accessibility**
   ```
   If file will exceed 400 lines:
     → Identify natural split points
     → Create separate files per topic
     → Add cross-references
     → Verify each file ≤400 lines
   ```

3. **Test Retrieval**
   ```
   After deployment:
     → Use project_knowledge_search
     → Verify all content returned
     → Check middle sections visible
     → Confirm no truncation
   ```

---

## When to Split Files

**Split Immediately If:**
- File >400 lines (mandatory)
- File 380-399 lines AND growing (proactive)
- File combines multiple distinct topics (logical)
- File has identifiable sections >400 lines total (structural)

**Split Process:**
1. Identify natural topic boundaries
2. Create separate files (one per topic)
3. Update headers and metadata
4. Add cross-references between files
5. Verify each file ≤400 lines
6. Update indexes to include new files
7. Test accessibility via project_knowledge_search

---

## Anti-Patterns

**Anti-Pattern 1: "Comprehensive" Files**
```
❌ Creating "complete guide" files that cover everything
❌ "HTTP-Complete-Reference.md" (1200 lines)
❌ "AWS-All-Patterns.md" (850 lines)
❌ "SUGA-Everything.md" (920 lines)

Why Wrong: 60-75% of content truncated and lost

✅ Instead: Multiple focused files, each <400 lines
```

**Anti-Pattern 2: Efficiency Over Accessibility**
```
❌ Thinking: "One big file is more efficient"
❌ Believing: "Less files = better organized"
❌ Assuming: "Claude can handle large files"

Why Wrong: Claude literally CANNOT ACCESS truncated content

✅ Instead: Optimize for accessibility, not file count
```

**Anti-Pattern 3: Ignoring Technical Constraints**
```
❌ "It's just a guideline, 450 lines is fine"
❌ "The 400-line limit is flexible"
❌ "We can make an exception this time"

Why Wrong: Technical constraint, not a guideline
             Lines 201-250 will be LOST

✅ Instead: Treat 400 lines as absolute maximum
```

---

## Real-World Impact: AWS Combined File Case Study

**What Happened:**
```
During AWS SIMAv4 conversion:
  - Created combined artifact with 3 AWS patterns
  - Total size: ~708 lines
  - Content: AWS-LESS-10 (153) + AWS-LESS-11 (267) + AWS-LESS-12 (288)
```

**Projected Impact if Deployed:**
```
project_knowledge_search would return:
  Lines 1-200: AWS-LESS-10 (Transformation) - ✅ Visible
  Lines 201-508: LOST CONTENT - ❌ Invisible
    - End of AWS-LESS-10
    - ALL OF AWS-LESS-11 (Cost Model) ← COMPLETELY LOST
    - Beginning of AWS-LESS-12
  Lines 509-708: AWS-LESS-12 (Timeouts) end - ✅ Visible

Result:
  - 43% of content lost (308/708 lines)
  - AWS-LESS-11 (Cost Model) completely inaccessible
  - User queries about cost optimization would fail
  - System appears to "not have" information that exists
```

**Lesson Learned:**
```
Split into 3 files:
  ✅ AWS-LESS-10: 153 lines - 100% accessible
  ✅ AWS-LESS-11: 267 lines - 100% accessible
  ✅ AWS-LESS-12: 288 lines - 100% accessible

All content preserved and fully searchable.
```

---

## Why Claude Needs to Remember This

**Critical for Learning Mode:**
When creating neural map entries:
- Always check line count BEFORE finalizing
- Split any content approaching 400 lines
- Never combine multiple topics into one file
- Verify each file is independently accessible

**Critical for Project Mode:**
When implementing features with documentation:
- Keep documentation files focused and ≤400 lines
- Split large docs into multiple focused files
- Don't create "comprehensive" files >400 lines

**Critical for Debug Mode:**
When troubleshooting knowledge access issues:
- Check if files >400 lines
- Verify middle content not truncated
- Recommend splitting oversized files

---

## Related Patterns

**Cross-References:**
- LESS-11: Documentation patterns - File organization
- LESS-12: Version control - Managing split files
- WISD-01: System constraints - Understanding limits
- SIMAv4-Developer-Guide.md - File size standards

---

## Keywords

400-line-limit, project-knowledge-search, truncation, file-size, accessibility, neural-maps, documentation-constraints, system-limits, content-loss, SIMAv4

---

**Location:** `/sima/entries/lessons/documentation/`  
**Total Lines:** 380 (≤400 for SIMAv4)  
**SIMAv4 Compliant:** ✅

**End of Entry**

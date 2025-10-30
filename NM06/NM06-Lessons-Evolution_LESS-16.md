# NM06-Lessons-Operations_LESS-15.md - LESS-15

# LESS-15: 5-Step Verification Protocol

**Category:** Lessons  
**Topic:** Operations  
**Priority:** CRITICAL  
**Status:** Active  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-25 (added file completeness indicators)

---

## Summary

Before suggesting ANY code change, complete a 5-step verification checklist: (1) read complete file with completeness verification, (2) verify SUGA pattern, (3) check anti-patterns, (4) verify dependencies, (5) cite sources. This protocol prevents 90% of common mistakes.

---

## Context

Multiple incidents of suggesting code changes that violated anti-patterns, broke the SUGA pattern, or were based on partial file reading led to the creation of this mandatory verification protocol. Cascading interface failures from incomplete file deployments emphasized the critical importance of file completeness verification.

---

## Content

### The 5-Step Checklist

Before suggesting ANY code change, complete ALL five steps:

**Step 1: Read Complete File with Completeness Verification**
- [ ] Read entire current file, not just the section to modify
- [ ] Verify file completeness using indicators (see below)
- [ ] Understand full context and dependencies
- [ ] Never suggest changes based on partial file reading

**File Completeness Indicators:**

*Quick checks to verify you have the complete file:*

1. **Header Format Check** (5 seconds)
   ```
   ✅ COMPLETE: Full Apache 2.0 license header (~15 lines)
   ❌ TRUNCATED: Short header with just title/version (~5-8 lines)
   
   Full header = Complete file (usually)
   Short header = Possible truncation (investigate)
   ```

2. **Line Count Verification** (5 seconds)
   ```
   Compare to known size:
   - Within ±20 lines of original → ✅ Probably complete
   - Significantly shorter → ❌ Likely truncated
   - Much longer → ✅ OK if additions documented
   ```

3. **EOF Marker Check** (2 seconds)
   ```
   ✅ COMPLETE: File ends with "# EOF"
   ❌ INCOMPLETE: Abrupt ending, missing EOF
   ```

4. **Critical Functions Check** (10 seconds)
   ```
   For interface files, verify presence of:
   - All __all__ exports listed
   - Implementation wrapper functions
   - Required imports
   
   Example for cache_core.py:
   grep "_execute_.*_implementation" → Should find 9+ functions
   ```

5. **Structural Completeness** (10 seconds)
   ```
   ✅ Has: Imports → Classes/Functions → Exports → EOF
   ❌ Missing: Any of these sections
   ```

**When Completeness Checks Fail:**
```
If ANY indicator suggests truncation:
1. DO NOT proceed with modifications
2. Fetch file again or request manual paste
3. Verify completeness before continuing
4. Document the issue if persistent
```

**Step 2: Verify SUGA Pattern**
- [ ] Gateway function exists/updated
- [ ] Interface definition follows pattern
- [ ] Implementation in correct core file
- [ ] Three-layer structure maintained

**Step 3: Check Anti-Patterns**
- [ ] Scanned Anti-Patterns-Checklist
- [ ] No direct imports (AP-01)
- [ ] No threading locks (AP-08)
- [ ] No bare excepts (AP-14)
- [ ] No sentinel leaks (AP-19)

**Step 4: Verify Dependencies**
- [ ] No circular imports
- [ ] Follows dependency layers (DEP-01 to DEP-08)
- [ ] Total size < 128MB if adding library
- [ ] Check what other files import (use LESS-16 checklist)

**Step 5: Cite Sources**
- [ ] Referenced relevant REF-IDs
- [ ] Included file locations
- [ ] Explained rationale with citations

### Why Completeness Verification Matters

**Real Incident (2025-10-20):**
```
Problem:
1. Deployed cache_core.py with wrong header format
2. File was truncated (506 lines instead of 590)
3. Missing: Lines 380-590 (implementation wrappers)
4. Result: interface_cache.py couldn't import functions
5. Impact: Complete Lambda failure, cascading errors

Lesson:
Short header = Warning sign of truncation
Always verify line count vs original
Check for critical functions before deploying
```

**Cost of Incomplete Files:**
```
Deploy incomplete file:
- 15 min outage
- 30 min debugging
- 45 min fixing + redeploying
- 2 hours investigating cascading failures
Total: ~3.5 hours

Verify completeness (30 sec):
- Check header format: 5 sec
- Compare line count: 5 sec
- Grep critical functions: 10 sec
- Check EOF marker: 2 sec
- Visual scan: 8 sec
Total: 30 seconds

ROI: 30 seconds prevents 3.5 hours = 420x return
```

### Header Format as Health Indicator

**Pattern Recognition:**

**HEALTHY (Complete File):**
```python
"""
module_name.py - Brief Description
Version: YYYY.MM.DD.NN
Description: Detailed description

Copyright YYYY Joseph Hersey

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   ...full license text...
   limitations under the License.
"""
```
- Header: 15 lines
- Sign: Likely complete file

**UNHEALTHY (Possible Truncation):**
```python
"""
Module Name - Brief Description
Version: YYYY.MM.DD.NN
Description: Multi-line description
Features: Bullet points
Memory Limit: Technical details
"""
```
- Header: 5-8 lines
- Sign: Check for truncation immediately

**Why Header Matters:**
```
Full license header = 15 lines
Short header = 5-8 lines
Difference = 10 lines often indicates missing code

If header is short:
→ Compare line count to original
→ Check for EOF marker
→ Verify critical functions present
→ Investigate before using
```

### Why This Protocol Matters

**Without verification:**
```
Suggest change → User implements → Breaks system → Debug for hours
Example: Suggested direct import → Violated AP-01 → Circular dependency
Example: Used truncated file → Missing functions → Complete failure
```

**With verification:**
```
Verify first → Catch violation → Suggest correct approach → Works first time
Example: Checked AP-01 → Use gateway import → No issues
Example: Checked completeness → Detected truncation → Re-fetched → Success
```

### Real Examples

**Example 1: Direct Import Caught**
```python
# User asks: "How do I use cache in this module?"

# ❌ Without verification:
"Just import cache_core directly"
# Result: Violates AP-01, creates coupling

# ✅ With verification (Step 3):
"Import gateway, then use gateway.cache_get()"
# Result: Follows SUGA pattern, no violation
```

**Example 2: Incomplete File Read Caught**
```python
# User asks: "Add logging to this function"

# ❌ Without verification (skipped Step 1):
"Add gateway.log_info() at line 42"
# Result: Line 42 already has logging, creates duplicate

# ✅ With verification (Step 1):
Read complete file → See existing logging → Suggest enhancement
# Result: No duplication, proper solution
```

**Example 3: Threading Lock Caught**
```python
# User asks: "Make this cache thread-safe"

# ❌ Without verification:
"Add threading.Lock() around cache operations"
# Result: Violates AP-08 and DEC-04 (Lambda single-threaded)

# ✅ With verification (Step 3):
Check anti-patterns → See AP-08 → Explain Lambda is single-threaded
# Result: Correct guidance, no wasted effort
```

**Example 4: File Truncation Caught (NEW)**
```python
# Fetching cache_core.py for modification

# ❌ Without completeness check:
Fetch file → See 506 lines → Modify → Deploy
# Result: Missing implementation wrappers → Complete failure

# ✅ With completeness check (Step 1):
Fetch file → Check header (short) → Check line count (506 vs 590)
→ Detect truncation → Re-fetch or request paste → Verify → Success
# Result: Complete file deployed, no issues
```

### When to Use This Protocol

**Always:**
- Before suggesting code modifications
- Before creating new files
- Before refactoring existing code
- Before answering "how do I..." questions about code
- **Before deploying any file**

**Never Skip:**
- Even for "simple" changes
- Even when you're confident
- Even under time pressure
- Even when change seems obvious
- **Even for files that "look complete"**

### Time Investment

**Per verification:**
- Step 1 (Read + Completeness): 30-60 seconds + 30 seconds
- Step 2 (SUGA): 10-20 seconds
- Step 3 (Anti-patterns): 20-30 seconds
- Step 4 (Dependencies): 10-20 seconds
- Step 5 (Citations): 10-20 seconds

**Total:** 110-180 seconds (~2-3 minutes)

**Time saved by preventing mistakes:**
- Fixing direct import: 30-60 minutes
- Debugging circular dependency: 1-3 hours
- Finding duplicate code: 15-30 minutes
- Explaining why change broke: 30-60 minutes
- **Fixing cascading interface failures: 2-4 hours**

**ROI:** 3 minutes investment saves 30 minutes to 4 hours

### Integration with Other Lessons

**Builds on:**
- LESS-01: Gateway pattern (Step 2 verifies this)
- LESS-07: Base layers (Step 4 checks dependency layers)
- LESS-08: Test failure paths (Step 3 checks error handling)
- **LESS-16**: Adaptation over rewriting (Step 4 checks dependencies)

**Enforces:**
- RULE-01: Gateway imports (Step 3 checks AP-01)
- DEC-04: No threading (Step 3 checks AP-08)
- All anti-patterns (Step 3)
- **File integrity** (Step 1 completeness checks)

**Prevents:**
- **BUG-03**: Cascading interface failures (completeness checks)
- Import errors (dependency verification)
- Anti-pattern violations (explicit checking)

---

## Related Topics

- **LESS-01**: Gateway pattern (verified in Step 2)
- **LESS-16**: Adaptation over rewriting (dependency checking in Step 4)
- **AP-27**: Skipping verification (the anti-pattern this prevents)
- **AP-28**: Not reading complete files (prevented by Step 1)
- **RULE-01**: Import rules (checked in Step 3)
- **BUG-03**: Cascading interface failures (prevented by completeness checks)
- **NM05**: All anti-patterns (checked in Step 3)

---

## Keywords

verification protocol, quality gate, checklist, code review, anti-patterns, SUGA pattern, file completeness, truncation detection, header format, EOF marker

---

## Version History

- **2025-10-25**: Added file completeness indicators and header format checking to Step 1
- **2025-10-23**: Added filename header (v3.1.0), updated "Last Updated" date
- **2025-10-20**: Created - Documented mandatory verification protocol
- **2025-10-15**: Original concept in NM06-LESSONS-Recent Updates 2025.10.20.md

---

**File:** `NM06-Lessons-Operations_LESS-15.md`  
**Directory:** NM06/  
**End of Document**

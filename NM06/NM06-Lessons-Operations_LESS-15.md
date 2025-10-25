# NM06-Lessons-Operations_LESS-15.md - LESS-15

# LESS-15: 5-Step Verification Protocol

**Category:** Lessons  
**Topic:** Operations  
**Priority:** CRITICAL  
**Status:** Active  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-23 (added filename header v3.1.0)

---

## Summary

Before suggesting ANY code change, complete a 5-step verification checklist: (1) read complete file, (2) verify SUGA pattern, (3) check anti-patterns, (4) verify dependencies, (5) cite sources. This protocol prevents 90% of common mistakes.

---

## Context

Multiple incidents of suggesting code changes that violated anti-patterns, broke the SUGA pattern, or were based on partial file reading led to the creation of this mandatory verification protocol.

---

## Content

### The 5-Step Checklist

Before suggesting ANY code change, complete ALL five steps:

**Step 1: Read Complete File**
- ☐ Read entire current file, not just the section to modify
- ☐ Understand full context and dependencies
- ☐ Never suggest changes based on partial file reading

**Step 2: Verify SUGA Pattern**
- ☐ Gateway function exists/updated
- ☐ Interface definition follows pattern
- ☐ Implementation in correct core file
- ☐ Three-layer structure maintained

**Step 3: Check Anti-Patterns**
- ☐ Scanned Anti-Patterns-Checklist
- ☐ No direct imports (AP-01)
- ☐ No threading locks (AP-08)
- ☐ No bare excepts (AP-14)
- ☐ No sentinel leaks (AP-19)

**Step 4: Verify Dependencies**
- ☐ No circular imports
- ☐ Follows dependency layers (DEP-01 to DEP-08)
- ☐ Total size < 128MB if adding library

**Step 5: Cite Sources**
- ☐ Referenced relevant REF-IDs
- ☐ Included file locations
- ☐ Explained rationale with citations

### Why This Protocol Matters

**Without verification:**
```
Suggest change → User implements → Breaks system → Debug for hours
Example: Suggested direct import → Violated AP-01 → Circular dependency
```

**With verification:**
```
Verify first → Catch violation → Suggest correct approach → Works first time
Example: Checked AP-01 → Use gateway import → No issues
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

### When to Use This Protocol

**Always:**
- Before suggesting code modifications
- Before creating new files
- Before refactoring existing code
- Before answering "how do I..." questions about code

**Never Skip:**
- Even for "simple" changes
- Even when you're confident
- Even under time pressure
- Even when change seems obvious

### Time Investment

**Per verification:**
- Step 1 (Read): 30-60 seconds
- Step 2 (SUGA): 10-20 seconds
- Step 3 (Anti-patterns): 20-30 seconds
- Step 4 (Dependencies): 10-20 seconds
- Step 5 (Citations): 10-20 seconds

**Total:** 80-150 seconds (~2 minutes)

**Time saved by preventing mistakes:**
- Fixing direct import: 30-60 minutes
- Debugging circular dependency: 1-3 hours
- Finding duplicate code: 15-30 minutes
- Explaining why change broke: 30-60 minutes

**ROI:** 2 minutes investment saves 30 minutes to 3 hours

### Integration with Other Lessons

**Builds on:**
- LESS-01: Gateway pattern (Step 2 verifies this)
- LESS-07: Base layers (Step 4 checks dependency layers)
- LESS-08: Test failure paths (Step 3 checks error handling)

**Enforces:**
- RULE-01: Gateway imports (Step 3 checks AP-01)
- DEC-04: No threading (Step 3 checks AP-08)
- All anti-patterns (Step 3)

---

## Related Topics

- **LESS-01**: Gateway pattern (verified in Step 2)
- **AP-27**: Skipping verification (the anti-pattern this prevents)
- **RULE-01**: Import rules (checked in Step 3)
- **NM05**: All anti-patterns (checked in Step 3)

---

## Keywords

verification protocol, quality gate, checklist, code review, anti-patterns, SUGA pattern

---

## Version History

- **2025-10-23**: Added filename header (v3.1.0), updated "Last Updated" date
- **2025-10-20**: Created - Documented mandatory verification protocol
- **2025-10-15**: Original concept in NM06-LESSONS-Recent Updates 2025.10.20.md

---

**File:** `NM06-Lessons-Operations_LESS-15.md`  
**Directory:** NM06/  
**End of Document**

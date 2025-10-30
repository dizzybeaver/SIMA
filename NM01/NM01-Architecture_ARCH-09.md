# NM01-Architecture_ARCH-09.md - ARCH-09

# ARCH-09: File Size Limits and Atomization Principle

**Category:** NM01 - Architecture
**Topic:** Core Principles
**Priority:** 🔴 CRITICAL
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

File size limits in both SIMA (neural maps) and SUGA (code architecture) aren't arbitrary - they serve critical architectural purposes: preventing truncation, enabling fast retrieval, ensuring atomic updates, and making systems comprehensible. Atomization is a shared principle across both architectures.

---

## Context

During Phase 5 terminology corrections (2025-10-24), Claude inadvertently created monolithic combination files (450+ lines) instead of working with individual atomic files. This violated SIMA v3's core principle and demonstrated why file size limits exist.

**What Happened:**
- Asked to correct terminology in neural maps
- Created "NM04-ARCHITECTURE-Foundational Design Decisions.md" (450+ lines) combining DEC-01 through DEC-05
- Created "NM06-BUGS-Critical Bugs Fixed.md" (400+ lines) combining BUG-01 through BUG-04
- Missed the entire point: SIMA v3 was specifically designed to eliminate these combination files

**The Mistake:**
This violated SIMA v3's fundamental principle: **one concept, one file, under 200 lines**.

---

## Content

### The Atomization Principle

**Core Philosophy:**
Break knowledge/code into smallest meaningful units that can be independently:
- Retrieved
- Updated
- Referenced
- Understood
- Maintained

**Applies to BOTH Architectures:**

```
SIMA v3 (Neural Maps):
âœ… Individual Files: LESS-01.md, DEC-04.md, BUG-02.md
âŒ Combination Files: LESSONS-Core Architecture.md (1000+ lines)

SUGA (Code):
âœ… Individual Modules: cache_core.py, logging_core.py
âŒ God Modules: everything.py (5000+ lines)
```

### Why File Size Limits Matter

#### 1. Prevents Truncation (SIMA v3 Primary Driver)

**Problem:**
- Claude has token limits per response
- Large files get truncated when fetched
- Truncation loses critical information
- User has to wait multiple minutes for large files

**Solution:**
- Files < 200 lines fit comfortably in token budget
- Complete file retrieved in 10-15 seconds
- No information loss
- Fast, predictable access

**Real Impact:**
```
Monolithic file (1000 lines):
├─ Fetch time: 60+ seconds
├─ Often truncated: 30-40% of content lost
├─ User experience: Frustrating
└─ Information retrieval: Unreliable

Atomic file (150 lines):
├─ Fetch time: 10-15 seconds
├─ Never truncated: 100% complete
├─ User experience: Smooth
└─ Information retrieval: Reliable
```

#### 2. Enables Fast Retrieval

**Single Responsibility:**
- Each file addresses one concept
- Direct access without searching
- Minimal reading required

**Example:**
```
User: "Why no threading locks?"

Monolithic approach:
1. Fetch NM04-ALL-Decisions.md (1500 lines, 90 seconds)
2. Search through entire file
3. Read surrounding context
4. Total time: 2-3 minutes

Atomic approach:
1. Route to DEC-04.md (120 lines, 12 seconds)
2. Read complete decision
3. Total time: 15 seconds

10x faster!
```

#### 3. Ensures Atomic Updates

**Modification Safety:**
- Change one concept = modify one file
- No risk of affecting unrelated content
- Version history per concept
- Easy rollback if needed

**Example:**
```
Update DEC-04 (threading locks decision):

Monolithic:
├─ Open 1500-line file
├─ Find DEC-04 section (search)
├─ Edit carefully (don't break other sections)
├─ Risk breaking DEC-01, DEC-02, DEC-03, DEC-05
└─ Difficult review (what changed?)

Atomic:
├─ Open 120-line DEC-04.md file
├─ Edit entire file (it's just DEC-04)
├─ Zero risk to other decisions
└─ Clear review (all changes visible)
```

#### 4. Makes Systems Comprehensible

**Cognitive Load:**
- Human working memory: ~7 items
- 200-line file: Fits in one mental model
- 1000-line file: Impossible to hold in mind

**Learning Curve:**
```
New developer understanding DEC-04:

Monolithic:
├─ Read 1500 lines to find DEC-04
├─ Understand context from other decisions
├─ Determine boundaries (where does DEC-04 end?)
└─ Time: 30-45 minutes

Atomic:
├─ Read 120-line DEC-04.md
├─ Complete standalone context
├─ Clear boundaries (file start to end)
└─ Time: 5-10 minutes
```

### File Size Guidelines

#### SIMA v3 (Neural Maps)

| File Type | Max Lines | Typical Lines | Purpose |
|-----------|-----------|---------------|---------|
| Individual | 200 | 100-150 | Single concept (LESS-01, DEC-04) |
| Topic Index | 300 | 200-250 | Organize 3-15 individuals |
| Category Index | 250 | 150-200 | Organize topics |
| Gateway | 400 | 250-350 | Route to categories |

**If file exceeds limit:**
- Individual > 200: Split into multiple concepts or create supplementals
- Index > limit: Create supplemental indexes

#### SUGA (Code Architecture)

| Module Type | Max Lines | Typical Lines | Purpose |
|-------------|-----------|---------------|---------|
| Core Module | 400 | 200-300 | Single interface implementation |
| Interface | 200 | 100-150 | Router + dispatch |
| Gateway | 300 | 150-250 | Central dispatcher |
| Utility | 300 | 150-200 | Helper functions |

**If module exceeds limit:**
- Core > 400: Split into focused submodules
- Interface > 200: Simplify dispatch or extract helpers

### Cross-Architecture Parallels

**SIMA v3 and SUGA share the same philosophy:**

| Principle | SIMA v3 | SUGA |
|-----------|---------|------|
| **Atomization** | One concept per file | One responsibility per module |
| **Size Limits** | 200 lines max | 400 lines max |
| **Single Entry** | Gateway layer routes | gateway.py dispatches |
| **No Monoliths** | Split large files | Refactor god objects |
| **Clear Boundaries** | Related Topics links | Import rules |
| **Fast Access** | Direct file retrieval | O(1) dispatch |

**Both solve the same problems:**
- Complexity management
- Fast navigation
- Safe modifications
- Team collaboration
- Knowledge transfer

### The Incident: What We Learned

**Scenario:** Terminology corrections (SIMA → SUGA in files)

**Wrong Approach (What Claude Did):**
1. Created new monolithic files combining multiple concepts
2. Result: 450-line combination files
3. Problem: Violated SIMA v3's core principle
4. Impact: Wasted time, had to redo work

**Correct Approach (What Should Have Been Done):**
1. Fetch individual files from File Server
2. Make targeted terminology corrections
3. Save corrected individual files
4. Result: 120-line atomic files
5. Time: 10-15 seconds per file

**Lesson:**
When working with SIMA v3, **always work with individual atomic files**, never create or modify combination files. The architecture exists to prevent the problems that monolithic files create.

---

## Related Topics

- **ARCH-01**: Gateway Trinity (SUGA's atomization in code)
- **DEC-08**: Flat File Structure (SUGA prefers flat, not nested)
- **LESS-01**: Gateway Pattern Prevents Problems (architectural prevention)
- **LESS-15**: 5-Step Verification Protocol (includes file size check)
- **SUGA-ARCH-XX**: Module Size Limits (parallel principle in code) [TO BE CREATED]

---

## Keywords

atomization, file size limits, monolithic files, SIMA principle, SUGA principle, truncation prevention, fast retrieval

---

## Version History

- **2025-10-24**: Created - Documents fundamental atomization principle shared by SIMA and SUGA

---

**File:** `NM01-Architecture_ARCH-09.md`  
**Directory:** NM01/  
**End of Document**

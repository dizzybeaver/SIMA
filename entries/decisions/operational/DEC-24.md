# DEC-24.md

**Version:** 1.0.0  
**Date:** 2025-11-02  
**Category:** Operational  
**Status:** Approved for Implementation  
**Related:** WISD-06

---

## Decision

**Change cache-busting protocol from user-provided timestamp to Claude-generated random 10-digit number.**

---

## Context

Cache-busting (WISD-06) requires a session-level identifier appended to all file fetches to bypass Claude's aggressive file caching. Currently requires user to:
1. Run terminal command (`date +%s`)
2. Copy timestamp
3. Paste as "Cache ID: [timestamp]"

This adds friction to session start and requires technical knowledge.

---

## Problem

**Current workflow:**
```
User: Start Project Work Mode
      Cache ID: 1730486400  ← requires terminal command
```

**Issues:**
- Extra step (run command)
- Technical requirement (Unix timestamp)
- Windows users need PowerShell command
- Friction at every session start
- Not truly necessary (any unique number works)

---

## Decision

**Replace user-provided timestamp with Claude-generated random number.**

**New workflow:**
```
User: Start Project Work Mode

Claude: ✅ Project Work Mode loaded.
        ✅ Cache ID: 8472936158 registered (auto-generated)
        All file fetches will use cache-busting.
```

Claude automatically:
1. Generates random 10-digit number
2. Stores for session
3. Applies to all fetches
4. No user action required

---

## Rationale

**Benefits:**
- ✅ Zero user friction (automatic)
- ✅ No technical knowledge required
- ✅ No terminal commands needed
- ✅ Works identically (random number = unique per session)
- ✅ Same cache-busting effectiveness
- ✅ Better user experience

**Why 10 digits:**
- Large enough to avoid collisions (10 billion possibilities)
- Easy to read/verify if needed
- Consistent format

**Why random vs sequential:**
- No state to track
- No coordination needed
- Already unique per session

---

## Implementation

**Files to modify:**
1. Custom Instructions for SUGA-ISP Development.md
2. SESSION-START-Quick-Context.md
3. SIMA-LEARNING-SESSION-START-Quick-Context.md
4. PROJECT-MODE-Context.md
5. DEBUG-MODE-Context.md

**Changes required:**
1. Remove "user provides Cache ID" section
2. Add "Claude generates Cache ID" section
3. Update activation patterns
4. Update session workflow examples
5. Remove timestamp generation instructions

**Backward compatibility:**
- User can still provide Cache ID if desired
- If user provides Cache ID: use theirs
- If user doesn't: auto-generate

---

## Verification

**Test cases:**
1. Start mode without Cache ID → auto-generates
2. Start mode with Cache ID → uses provided
3. Multiple file fetches → same ID applied
4. New session → new ID generated

**Success criteria:**
- ✅ No user prompt for Cache ID
- ✅ Cache-busting still works
- ✅ All fetches use generated ID
- ✅ Session workflow smoother

---

## Impact

**User experience:** Significantly improved (removes friction)  
**Implementation:** Low complexity (5 file updates)  
**Risk:** None (backward compatible)  
**Testing:** Minimal (session-level verification)

---

## Alternatives Considered

**1. Keep timestamp requirement**
- Rejected: Unnecessary friction

**2. Use sequential counter**
- Rejected: Requires state tracking

**3. Use shorter number (6 digits)**
- Rejected: Higher collision risk

**4. Use UUID/GUID**
- Rejected: Too long, harder to read

---

## Decision Owner

User request, Claude implementation

---

## Keywords

cache-busting, session-management, user-experience, workflow-improvement, automation

---

## Related

- WISD-06: Session-Level Cache-Busting Protocol
- All mode context files (implementation targets)

---

**REF-ID:** DEC-24

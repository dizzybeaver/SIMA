# SIMA-LEARNING-MODE-Context.md

**Version:** 3.0.0  
**Date:** 2025-11-08  
**Purpose:** Knowledge extraction and documentation  
**Activation:** "Start SIMA Learning Mode"  
**Load time:** 30-45 seconds (ONE TIME per learning session)  
**Updated:** Optimized with shared knowledge references

---

## WHAT THIS MODE IS

**Learning Mode** extracts knowledge from conversations and experiences.

**Purpose:** Transform raw material into structured neural map entries.

**Outputs:** LESS, DEC, AP, BUG, WISD entries as artifacts.

---

## FILE RETRIEVAL

**Session Start:**
1. User uploads File Server URLs.md
2. Claude fetches fileserver.php automatically
3. Receives ~412 cache-busted URLs (69ms)
4. Fresh files guaranteed

**Why:** fileserver.php ensures fresh content when checking for duplicates.

**REF:** WISD-06

---

## THREE CRITICAL PRINCIPLES

### 1. Genericize by Default
**Strip project-specifics. Extract universal principles.**

[X] "In SUGA-ISP Lambda, threading locks caused deadlock."  
[OK] "Threading primitives fail in single-threaded runtimes."

### 2. Check Duplicates First
**Always search before creating.**

Use fileserver.php URLs to fetch fresh entries.  
If similar exists → Update, don't duplicate.

### 3. Extreme Brevity
**Minimize tokens. Every word must earn its place.**

- Summaries: 2-3 sentences MAX
- Examples: 2-3 lines MAX
- Files: ≤400 lines
- No filler words

**Complete Standards:** `/sima/shared/File-Standards.md`

---

## EXTRACTION SIGNALS

**Look for these patterns:**

1. **"Aha!" Moments** - "Oh, I see now...", "That explains why..."
2. **Pain Points Resolved** - "After 3 hours, we found...", "The breakthrough came when..."
3. **Design Decisions** - "We chose X over Y because...", "The tradeoff was..."
4. **Patterns Discovered** - "This keeps happening...", "Every time we X, Y occurs..."
5. **Mistakes Made** - "We shouldn't have...", "That was a mistake because..."
6. **Wisdom Synthesized** - "The key insight is...", "What really matters is..."
7. **Process Improvements** - "Next time we'll...", "A better approach would be..."

---

## UNIVERSAL EXTRACTION WORKFLOW

**For ALL knowledge types:**

```
STEP 1: Identify Signal
- What triggered this knowledge?
- Type: LESS, AP, DEC, BUG, WISD?

STEP 2: Check Duplicates (via fileserver.php)
- Search: "[keyword] [type]"
- Use fileserver.php URLs for fresh content
- If similar exists → Update, don't create

STEP 3: Genericize
- Strip project-specifics
- Extract universal principle
- Remove tool/framework names (unless core)

STEP 4: Extract Core Content (BRIEF)
- Root cause/principle (1 sentence)
- Context (1 sentence IF needed)
- Impact (quantified if possible)
- Application (1 sentence)
- Example (2-3 lines MAX)

STEP 5: Assign REF-ID
- Use next sequential number
- Never reuse IDs

STEP 6: Create Artifact
- Follow brevity standards
- Filename in header
- File ≤400 lines
- Output as markdown artifact
- Brief chat: "Creating [TYPE-##] artifact..."
```

---

## ENTRY TYPES

### LESSONS (LESS-##)
**What worked, what didn't, why.**

File: `/sima/entries/lessons/[category]/LESS-##.md`

Template:
- Context (1 sentence)
- Problem
- Discovery
- Principle
- Application

### ANTI-PATTERNS (AP-##)
**What NOT to do.**

File: `/sima/entries/anti-patterns/[category]/AP-##.md`

Template:
- Pattern (what NOT to do)
- Why (root cause)
- Impact
- Alternative (right way)
- Example (2-3 lines)

### DECISIONS (DEC-##)
**Choices made and rationale.**

File: `/sima/entries/decisions/[category]/DEC-##.md`

Template:
- Decision
- Context
- Alternatives
- Rationale (1-2 sentences)
- Outcome

### BUGS (BUG-##)
**Issues found and fixed.**

File: `/sima/entries/lessons/bugs/BUG-##.md`

Template:
- Symptom
- Root Cause
- Impact
- Fix
- Prevention

### WISDOM (WISD-##)
**Profound insights.**

File: `/sima/entries/lessons/wisdom/WISD-##.md`

Template:
- Insight (1-2 sentences)
- Origin (1 sentence)
- Implications (1 sentence)
- Applications (1 sentence)

---

## ARTIFACT RULES

**MANDATORY for all neural maps:**

### Output Format
```
[OK] Neural maps -> Artifacts (markdown)
[OK] Multiple entries -> Multiple artifacts
[OK] Index updates -> Artifacts
[OK] Filename in header
[OK] File ≤400 lines
[X] Never output in chat
[X] Never condense files
```

### Chat Output
```
[OK] Brief status: "Extracting knowledge..."
[OK] "Creating [X] artifacts..."
[OK] Brief summary (2-3 sentences)
[X] Long narratives
[X] Verbose commentary
```

**Complete Standards:** `/sima/shared/Artifact-Standards.md`

---

## QUALITY CRITERIA

**All entries must be:**

1. **Actionable** - Can be applied immediately
2. **Generic** - No unnecessary project-specifics
3. **Unique** - Not duplicate (checked via fileserver.php)
4. **Brief** - ≤400 lines, minimal words
5. **Complete** - Key info present (but brief!)
6. **Verifiable** - Testable/measurable

---

## POST-EXTRACTION PROTOCOL

**After creating each entry:**

1. âœ… Searched before creating (fileserver.php)
2. âœ… Project-specifics removed
3. âœ… Total lines ≤400
4. âœ… Summary ≤3 sentences
5. âœ… Examples ≤3 lines
6. âœ… Filename in header
7. âœ… Output as artifact
8. âœ… Brief chat output
9. âœ… Keywords present (4-8)
10. âœ… Cross-references (REF-IDs only)

---

## EXAMPLE EXTRACTION

**Raw Material:**
```
"After 3 hours debugging, we found that
_CacheMiss caused JSON serialization failures."
```

**Process:**

1. **Check duplicates** (fileserver.php)  
   Search: "sentinel serialization"  
   → None found

2. **Genericize**  
   Remove: "3 hours", "_CacheMiss", project names  
   Extract: Sentinel leakage across boundaries

3. **Create artifact (BRIEF)**

```markdown
# LESS-##.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Sanitize sentinels at boundaries

LESS-##: Sanitize implementation sentinels

Context: Internal sentinels fail serialization  
Impact: 535ms penalty, JSON failures  
Prevention: Sanitize at boundary before serialization  
Example: `if value is SENTINEL: return None`

Keywords: sentinel, serialization, boundary, sanitization  
Related: BUG-01, DEC-05, AP-19
```

4. **Output as artifact**  
   Brief chat: "LESS-## created. Covers sentinel sanitization."

---

## BEST PRACTICES

### Do's
- âœ… Check duplicates FIRST (fileserver.php)
- âœ… Genericize aggressively
- âœ… Be ruthlessly brief
- âœ… Extract immediately
- âœ… Cross-reference heavily
- âœ… Output as artifacts
- âœ… Keep chat minimal

### Don'ts
- âŒ Create duplicates
- âŒ Keep project-specifics
- âŒ Write long summaries
- âŒ Extract everything
- âŒ Be vague
- âŒ Output in chat
- âŒ Condense files
- âŒ Exceed 400 lines
- âŒ Skip fileserver.php

---

## SUCCESS METRICS

**Target:**
- Uniqueness: 100% (no duplicates)
- Genericization: <5% project-specific content
- Brevity: All files ≤400 lines
- Capture Rate: 3-5 entries per session
- Artifacts: 100% (every entry)
- Chat: ≤5 sentences per entry
- File Separation: 1 topic per file

---

## WORKFLOW

### Learning Session Flow

```
1. User provides source material
   "Here's a conversation to extract from..."

2. Claude identifies signals
   "Extracting knowledge..."
   Recognizes 7 signal types

3. Claude checks duplicates
   Searches via fileserver.php
   Gets fresh entry list

4. Claude genericizes
   Strips project-specifics
   Extracts universal principles

5. Claude creates entries
   "Creating [X] artifacts..."
   Follows brevity standards
   Outputs as markdown artifacts

6. Claude updates indexes
   Separate index artifacts
   Cross-references updated

7. Brief summary
   "[X] artifacts created."
   "Topics: [list]"
```

---

## READY

**Context loaded when you remember:**
- âœ… fileserver.php fetched (automatic)
- âœ… 3 principles (Genericize, Duplicates, Brevity)
- âœ… 7 extraction signals
- âœ… 5 entry types (LESS, AP, DEC, BUG, WISD)
- âœ… Universal workflow (6 steps)
- âœ… Quality criteria (6 standards)
- âœ… Artifacts ONLY (never chat)
- âœ… ≤400 lines per file
- âœ… Chat minimal (2-3 sentences)

**Now ready to extract knowledge!**

---

**END OF LEARNING MODE CONTEXT**

**Version:** 3.0.0 (Optimized with shared references)  
**Lines:** 300 (target achieved)  
**Reduction:** 870 → 300 lines (66% reduction)  
**Load time:** 30-45 seconds  
**References:** Shared knowledge in `/sima/shared/`  
**Purpose:** Extract generic, unique, brief knowledge as artifacts

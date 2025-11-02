# Custom Instructions for SUGA-ISP Development (Mode-Based)

**Version:** 3.2.0  
**Date:** 2025-11-02  
**Purpose:** Streamlined instructions using unified mode selection system  
**Updated:** Cache-busting protocol integrated (WISD-06)

---

## MANDATORY FIRST STEP - EVERY SESSION

**CRITICAL:** This project uses a **mode-based context system**. You must:

1. **Determine which mode is needed** (or ask user if unclear)
2. **Wait for user's activation phrase** (exact phrase required)
3. **Load the mode-specific context file**
4. **Operate within that mode only**

**DO NOT load context until user provides activation phrase.**

---

## FOUR MODES AVAILABLE

### Mode 1: General Purpose Mode
**When:** Questions, learning, guidance, architecture queries  
**Activation:** User says: `"Please load context"`  
**Loads:** SESSION-START-Quick-Context.md (30-45s)  
**Behavior:** Answer questions, provide guidance, cite REF-IDs

### Mode 2: Learning Mode  
**When:** Extracting knowledge, documenting lessons, creating neural map entries  
**Activation:** User says: `"Start SIMA Learning Mode"`  
**Loads:** SIMA-LEARNING-SESSION-START-Quick-Context.md (45-60s)  
**Behavior:** Extract patterns, create LESS/BUG/DEC/WISD entries, update indexes

### Mode 3: Project Mode
**When:** Active development, implementing features, writing code  
**Activation:** User says: `"Start Project Work Mode"`  
**Loads:** PROJECT-MODE-Context.md (30-45s)  
**Behavior:** Implement features, output complete code artifacts, verify with LESS-15

### Mode 4: Debug Mode
**When:** Troubleshooting errors, finding root causes, diagnostics  
**Activation:** User says: `"Start Debug Mode"`  
**Loads:** DEBUG-MODE-Context.md (30-45s)  
**Behavior:** Trace issues, identify root causes, provide fixes

---

## CRITICAL ARCHITECTURAL RULES (All Modes)

These apply regardless of mode:

### Terminology (IMPORTANT)
**SUGA** = The Lambda gateway architecture pattern (3 layers)
- Gateway Layer -> Interface Layer -> Core Layer
- Use when discussing: Lambda code, gateway.py, import patterns

**SIMA** = The neural maps documentation system  
- Gateway -> Category -> Topic -> Individual
- Use when discussing: NM01-, NM04-, documentation structure

**Never confuse these terms.** The SUGA-ISP project uses SUGA architecture, documented by SIMA neural maps.

### The 4 Golden Rules (Memorize)
1. **Search neural maps FIRST** - They are authoritative source
2. **Read COMPLETE sections** - Never skim, follow cross-references
3. **Always cite REF-IDs** - Make answers verifiable
4. **Verify before suggesting** - Use appropriate mode checklist

### RED FLAGS (Never Suggest)
- [X] Threading locks (Lambda is single-threaded)
- [X] Direct core imports (always via gateway)
- [X] Bare except clauses (use specific exceptions)
- [X] Sentinel objects crossing boundaries (sanitize at router)
- [X] Heavy libraries without justification (128MB limit)
- [X] New subdirectories (flat structure except home_assistant/)
- [X] Skipping verification (mode-specific checklists)

---

## ARTIFACT RULES (All Modes) - SIMAv4 Enhanced

**CRITICAL:** These rules prevent code output in chat and ensure complete files.

### Rule 1: Always Use Artifacts for Code
**MANDATORY for ANY code response:**
```
[OK] Code snippet > 20 lines -> Create artifact
[OK] ANY file modification -> Complete file artifact
[OK] New file creation -> Complete file artifact
[OK] Configuration files -> Artifact
[OK] Neural map files -> Artifact
[X] NEVER output code in chat
[X] NEVER output snippets in chat
```

### Rule 2: Complete Files Only (Never Fragments)
**MANDATORY for ALL artifacts:**
```
[OK] Include ALL existing code + modifications
[OK] Mark changes with comments (# ADDED:, # MODIFIED:)
[OK] Full, deployable file
[OK] User can copy/paste immediately
[OK] Filename in header (SIMAv4)
[X] NEVER partial snippets
[X] NEVER "add this to line X"
[X] NEVER fragments or excerpts
```

### Rule 3: Minimize Chat Output (SIMAv4 - NEW)
**MANDATORY during changes:**
```
[OK] Brief status updates only ("Creating artifact...")
[OK] Minimal explanatory text
[OK] Let artifacts speak for themselves
[OK] Summary after artifact (2-3 sentences max)
[X] Long explanations in chat
[X] Verbose commentary during work
[X] Narrative descriptions of changes
```

### Rule 4: File Size Limits (SIMAv4 - NEW)
**MANDATORY for neural maps:**
```
[OK] Neural map files: <=400 lines
[OK] Summaries/plans: <=100 lines
[OK] Chat prompts: <=50 lines
[OK] Split large content into separate files
[X] Files >400 lines (except source code)
[X] Condensed multi-topic files
```

### Rule 5: Encoding Standards (SIMAv4 - NEW)
**MANDATORY for all artifacts:**
```
[OK] Verify emoji encoding (UTF-8)
[OK] Test charts/graphs render correctly
[OK] Check special characters display
[OK] Validate markdown formatting
[X] Broken emoji/unicode
[X] Malformed charts
[X] Encoding errors
```

### Rule 6: File Headers (SIMAv4 - NEW)
**MANDATORY for all artifacts:**
```
[OK] Filename at top of every file
[OK] Version number included
[OK] Date included
[OK] Purpose/description included
Example:
# filename.md
**Version:** 1.0.0
**Date:** 2025-11-01
**Purpose:** [Brief description]
```

### Pre-Output Verification Checklist (Updated)
**MANDATORY before EVERY artifact:**
```
[ ] Did I fetch the current file first? (if modifying)
[ ] Did I read the complete file?
[ ] Am I including ALL existing code?
[ ] Did I mark changes with comments?
[ ] Is this an artifact (not chat)?
[ ] Is this complete (not fragment)?
[ ] Is filename in header? (SIMAv4)
[ ] Is file <=400 lines? (SIMAv4, neural maps)
[ ] Are emojis/charts encoded correctly? (SIMAv4)
[ ] Is chat output minimal? (SIMAv4)
```

### Self-Correction Trigger
**If you catch yourself about to output code in chat:**
```
STOP immediately
Do NOT output in chat
[OK] Create complete artifact instead
[OK] Include all existing code
[OK] Mark your changes
[OK] Add filename in header
[OK] Keep chat brief
```

**These rules apply to ALL modes.**

---

## MODE-SPECIFIC BEHAVIORS

### Once Mode Loaded:

**General Mode:**
- Answer questions with REF-ID citations
- Use workflow routing for common patterns
- Check instant answers first (top 10)
- Provide architectural guidance
- When showing code: ALWAYS use artifacts (never chat)
- Keep responses focused and brief

**Learning Mode (SIMAv4 Enhanced):**
- Check for duplicates before creating entries
- Genericize all content (strip project-specifics)
- Keep entries brief (<=400 lines per file)
- Create separate neural maps (never condense)
- Filename in header of every file
- Output as artifacts with minimal chat
- Verify emoji/chart encoding
- Brief summaries only

**Project Mode:**
- ALWAYS fetch current files first (LESS-01)
- Implement all 3 SUGA layers (Gateway -> Interface -> Core)
- Output COMPLETE files as artifacts (never snippets)
- Verify with LESS-15 before suggesting code
- TRIPLE-CHECK: Complete file artifact, not fragment
- Filename in header
- Minimal chat during implementation
- Brief status updates only

**Debug Mode:**
- Check known bugs first (BUG-01 to BUG-04)
- Use systematic investigation (not guessing)
- Trace through SUGA layers
- Provide root cause + fix + prevention
- When providing fix code: Complete file artifact
- Filename in header
- Brief analysis, let artifacts show fixes
- Minimal explanatory text

---

## 🔄 CACHE-BUSTING PROTOCOL (Critical - All Modes)

### The Problem

Claude's `web_fetch` tool aggressively caches files, ignoring all server-side cache control headers. This can serve week-old (or older) code files, making collaborative development impossible.

### The Solution

Session-level timestamp cache-busting using Unix timestamps appended as query parameters.

### Activation Pattern

User provides at session start:
```
Cache ID: [unix_timestamp]
```

**How User Generates:**
- Unix/Mac: `date +%s`
- Windows PowerShell: `[int][double]::Parse((Get-Date -UFormat %s))`
- Online: https://www.unixtimestamp.com/
- Alternative: Random 6-digit number (e.g., 847293)

### Implementation Rules (MANDATORY)

#### Rule 1: Check for Cache ID at Session Start
After mode context loads:
1. Look for "Cache ID: [number]" in user's message
2. If present: Store cache ID for entire session
3. If absent: Prompt user for cache ID before ANY fetches

Response Pattern:
```
✅ [Mode Name] loaded.
✅ Cache ID: 1730486400 registered.
   All file fetches will use cache-busting.
```

#### Rule 2: Transform ALL URLs Before Fetching
URL Transformation (Automatic):
```
User provides:
https://claude.dizzybeaver.com/src/gateway.py

Claude fetches:
https://claude.dizzybeaver.com/src/gateway.py?v=1730486400
```

Implementation:
- Append `?v=[cache_id]` to every `web_fetch` URL
- Do this automatically (don't ask permission)
- Apply to ALL file types (Python, markdown, config, etc.)
- Server ignores parameter, Claude bypasses cache

#### Rule 3: No Exceptions
Apply cache-busting to:
- ✅ Source code files (.py)
- ✅ Documentation files (.md)
- ✅ Context files (SESSION-START, PROJECT-MODE, etc.)
- ✅ Neural maps (LESS, BUG, DEC, etc.)
- ✅ Configuration files (.yml, .json, etc.)
- ✅ ALL files from File Server URLs.md

Never skip cache-busting for:
- ❌ "Just a quick check"
- ❌ "File unlikely to have changed"
- ❌ "Already fetched this session"

**Every fetch = cache-busting applied**

#### Rule 4: File Server URLs.md Stays Clean
- User's `File Server URLs.md` contains clean URLs (no cache-busting)
- Claude adds cache-busting ONLY when actually fetching
- This keeps the URL inventory maintainable

### Session Workflow

**Standard Session Start:**
```
User: Start Project Work Mode
      Cache ID: 1730486400
      [pastes File Server URLs.md]

Claude: ✅ Project Work Mode loaded.
        ✅ Cache ID: 1730486400 registered.
        Ready for active development with cache-busting enabled.
```

**If Cache ID Missing:**
```
Claude: ✅ [Mode Name] loaded.
        ⚠️ Cache ID required for file fetching.
        
        Please provide a cache ID to ensure fresh files:
        Cache ID: [run: date +%s]
```

**During Session:**
```
Claude: Fetching gateway.py with cache-busting...
        [internally fetches: .../gateway.py?v=1730486400]
        ✅ Retrieved current version (2025.11.02)
```

### Benefits
- ✅ One timestamp per session (~5 seconds work)
- ✅ File Server URLs.md unchanged
- ✅ No infrastructure changes needed
- ✅ Always gets fresh file content
- ✅ Reliable development workflow

### Critical Reminders
1. Cache ID is mandatory - Don't proceed with fetches without it
2. Apply to every URL - No exceptions, no shortcuts
3. Automatic transformation - User provides clean URLs, Claude adds cache-busting
4. Store for session - Remember cache ID for entire conversation
5. Confirm receipt - Let user know cache-busting is active

**This solves a critical platform limitation that makes development impossible without it.**

**Related:** WISD-06

---

## MODE SWITCHING

**Important:** One mode per session.

**To switch modes:**
1. End current session
2. Start new session  
3. Provide new activation phrase
4. New mode loads

**Never mix modes in same session** - causes context confusion.

---

## QUICK START GUIDE

### For New Session:

```
1. User uploads File Server URLs.md (optional but helpful)

2. User provides activation phrase AND Cache ID:
   - "Please load context"          (General Mode)
   - "Start SIMA Learning Mode"     (Learning Mode)
   - "Start Project Work Mode"      (Project Mode)
   - "Start Debug Mode"             (Debug Mode)
   - Cache ID: [timestamp or 6-digit number]

3. You load mode-specific context (30-60s)

4. Confirm mode loaded AND cache ID registered (BRIEF):
   "✅ [Mode Name] loaded.
    ✅ Cache ID: [number] registered.
    Ready to [primary function]."

5. Operate within that mode's guidelines with cache-busting active
```

### If User Doesn't Know Which Mode:

```
Ask clarifying questions (BRIEF):
- "Understand, build, debug, or document?"

Then suggest appropriate mode:
- Understand -> General Mode
- Build -> Project Mode
- Debug -> Debug Mode
- Document -> Learning Mode
```

### If User Forgets Cache ID:

```
Prompt immediately (BRIEF):
"⚠️ Cache ID required for file fetching.

Please provide: Cache ID: [run: date +%s]

Why: Claude's cache can serve week-old files without this."
```

---

## CRITICAL REMINDERS

### Always Remember:
1. **Mode activation is EXPLICIT** - user must say activation phrase
2. **Cache ID is MANDATORY** - prompt if missing before any fetches
3. **One mode per session** - no mixing, no switching
4. **Mode context is complete** - don't add extra rules from memory
5. **SUGA vs SIMA distinction** - use correct terminology
6. **RED FLAGS apply always** - regardless of mode
7. **Code ALWAYS in artifacts** - never in chat, always complete
8. **[NEW] Minimal chat output** - brief status, let artifacts speak (SIMAv4)
9. **[NEW] Files <=400 lines** - split if needed (SIMAv4)
10. **[NEW] Filename in headers** - every artifact (SIMAv4)
11. **[NEW] Verify encoding** - emojis, charts work (SIMAv4)
12. **[NEW] Cache-busting mandatory** - apply to ALL fetches (WISD-06)

### Never Do:
- [X] Load mode without activation phrase
- [X] Fetch files without Cache ID
- [X] Mix behaviors from different modes
- [X] Skip mode-specific verification steps
- [X] Confuse SUGA (code architecture) with SIMA (documentation)
- [X] Suggest RED FLAG patterns
- [X] Output code in chat (always artifacts)
- [X] Output code fragments (always complete files)
- [X] [NEW] Verbose chat during work (brief only)
- [X] [NEW] Files >400 lines (split them)
- [X] [NEW] Missing filename headers
- [X] [NEW] Broken emoji/chart encoding
- [X] [NEW] Skip cache-busting (week-old code!)

---

## SUCCESS CRITERIA (SIMAv4 Updated)

**You're operating correctly when:**
- [OK] Waited for explicit activation phrase
- [OK] Checked for Cache ID and prompted if missing
- [OK] Loaded correct mode context
- [OK] Following mode-specific guidelines
- [OK] Not mixing behaviors from other modes
- [OK] Using correct terminology (SUGA/SIMA)
- [OK] Avoiding all RED FLAGS
- [OK] Citing REF-IDs appropriately
- [OK] All code in artifacts (not chat)
- [OK] All artifacts complete (not fragments)
- [OK] [NEW] Chat output minimal (brief status only)
- [OK] [NEW] Files <=400 lines (neural maps)
- [OK] [NEW] Filename in every header
- [OK] [NEW] Emojis/charts render correctly
- [OK] [NEW] Separate files (not condensed)
- [OK] [NEW] Cache-busting applied to all fetches

---

## VERSION HISTORY

**v3.2.0 (2025-11-02):** [NEW] Cache-Busting Protocol
- ADDED: Cache-busting protocol section (mandatory for all fetches)
- ADDED: Cache ID verification at session start
- ADDED: URL transformation rules for web_fetch
- FIXED: Platform caching issue preventing fresh file retrieval
- UPDATED: Quick Start Guide with Cache ID requirements
- UPDATED: Success criteria with cache-busting check
- RELATED: WISD-06 (Session-Level Cache-Busting)

**v3.1.0 (2025-11-01):** [NEW] SIMAv4
- Added: Minimal chat output rule (Rule 3)
- Added: File size limits (Rule 4, <=400 lines)
- Added: Encoding standards (Rule 5)
- Added: File header requirements (Rule 6)
- Added: Separate file requirement (no condensing)
- Updated: Pre-output checklist (4 new items)
- Updated: Success criteria (5 new items)
- Updated: Mode behaviors for brevity
- Enhanced: All modes for SIMAv4 compliance
- Fixed: Emoji encoding (UTF-8 verified)

**v3.0.1 (2025-10-25):**
- CRITICAL FIX: Added comprehensive artifact rules
- Fixed: Code output in chat issue
- Fixed: Fragment output issue
- Added: Pre-output verification checklist
- Added: Self-correction trigger

**v3.0.0 (2025-10-25):**
- Complete rewrite for unified mode selection
- 4 modes: General, Learning, Project, Debug
- Streamlined from 200+ lines to focused essentials
- Mode-specific context in separate files

---

**END OF CUSTOM INSTRUCTIONS**

**System:** Mode-based (v3.2.0)  
**Modes:** 4 (General, Learning, Project, Debug)  
**Standard:** SIMAv4 (minimal chat, <=400 lines, headers, encoding) + Cache-busting (WISD-06)  
**Activation:** Explicit phrases + Cache ID required  
**Artifacts:** MANDATORY for all code/neural maps  
**Cache-Busting:** MANDATORY for all file fetches

**Remember:** Check Cache ID first, load mode, apply cache-busting to ALL fetches, operate briefly, verify encoding!

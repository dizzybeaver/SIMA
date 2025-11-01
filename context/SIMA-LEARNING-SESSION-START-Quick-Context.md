# SIMA-LEARNING-SESSION-START-Quick-Context.md

**Version:** 2.1.0  
**Date:** 2025-11-01  
**Purpose:** Knowledge extraction and learning mode  
**Activation:** "Start SIMA Learning Mode"  
**Load time:** 45-60 seconds (ONE TIME per learning session)  
**Updated:** SIMAv4 standards integrated

---

## WHAT THIS FILE IS

This is your **knowledge extraction bootstrap file**. Read it ONCE when entering SIMA Learning Mode to activate:
- Pattern recognition for 10+ knowledge types
- Extraction workflows with **genericization** and **duplicate checks**
- Quality standards emphasizing **brevity**
- REF-ID assignment rules
- SIMA v3 routing logic
- Post-extraction protocols
- [NEW] **SIMAv4 compliance** (minimal chat, <=400 lines, headers, encoding)

**Purpose:** Transform raw material into structured, **generic, unique, concise** neural map entries.

**Time investment:** 60 seconds now enables systematic knowledge capture all session.

---

## ARTIFACT USAGE (Learning Mode) - SIMAv4

**When creating neural map entries:**

### Output Format
```
[OK] Neural map files -> Artifacts (markdown)
[OK] Multiple entries -> Multiple artifacts (separate files)
[OK] Index updates -> Artifacts
[OK] Cross-references -> Include in artifacts
[OK] Filename in header (SIMAv4)
[OK] File <=400 lines (SIMAv4)
[X] Don't output neural maps in chat
[X] Don't condense multiple topics in one file
```

### Quality Standards (SIMAv4)
```
[OK] Complete entry (all sections)
[OK] Proper REF-ID format
[OK] 4-8 keywords
[OK] 3-7 related topics
[OK] Brief (<=400 lines per file)
[OK] Generic (no project-specifics)
[OK] Unique (not duplicate)
[OK] Filename in header
[OK] Separate files (never condense)
```

### Chat Output (SIMAv4)
```
[OK] Brief status: "Extracting knowledge..."
[OK] "Creating [X] neural map artifacts..."
[OK] Brief summary (2-3 sentences)
[X] Long extraction narratives
[X] Verbose commentary
[X] Detailed explanations in chat
```

**Default: Output neural map entries as complete markdown artifacts with minimal chat**

---

## THREE CRITICAL ENHANCEMENTS

### Enhancement 1: Genericize by Default

**Rule:** Strip project-specific details unless directly relevant to current project.

**Process:**
1. Extract the **universal principle** from the specific instance
2. Remove references to specific tools/frameworks/languages (unless core to lesson)
3. Focus on **transferable patterns** and **timeless insights**
4. Keep only context needed to understand the principle

**Examples:**

[X] **Too Specific:**
> "In SUGA-ISP Lambda, we found that threading locks caused deadlock because AWS Lambda runs single-threaded."

[OK] **Properly Generic:**
> "Threading primitives fail in single-threaded execution environments. Verify runtime model before choosing concurrency approach."

[X] **Too Specific:**
> "We use gateway.py to prevent circular imports in our Python Lambda project."

[OK] **Properly Generic:**
> "Gateway pattern (single entry point) prevents circular imports in modular systems. Apply to any language/platform with tight coupling risk."

### Enhancement 2: Duplicate Detection

**Rule:** Always verify uniqueness before creating new entries.

**Check Process:**
```
BEFORE creating any new entry:

1. Search existing entries for same concept
   - Same REF-ID prefix (LESS, AP, DEC, etc.)
   - Related keywords
   - Similar patterns

2. If similar entry exists:
   -> DON'T create duplicate
   -> Update existing entry instead
   -> Cross-reference related items

3. If truly new:
   -> Proceed with creation
   -> Note why it's distinct from existing
```

**Duplicate Detection Queries:**
- "Is there already a lesson about [topic]?"
- "Do we have an anti-pattern covering [pattern]?"
- "Is this decision already documented?"
- "Does BUG-## cover this root cause?"

**If Duplicate Found:**
- **Update** existing entry with new insights
- **Enhance** examples with new cases
- **Strengthen** cross-references
- **Don't create** new duplicate entry

### Enhancement 3: Extreme Brevity

**Rule:** Minimize tokens. Every word must earn its place.

**Standards:**
- Summaries: 2-3 sentences MAX
- Examples: 1-2 lines code + 1 line explanation
- Descriptions: Direct, no filler words
- Cross-refs: REF-IDs only, no explanatory text
- Files: <=400 lines (SIMAv4)

**Before:**
> "After conducting extensive debugging sessions and analyzing the performance characteristics of our system, we eventually discovered through careful investigation that the root cause of the slowdown was related to how we were handling cache misses, which were occurring more frequently than expected due to..."

**After:**
> "Cache miss handling caused 535ms penalty. Root: _CacheMiss objects leaked across boundaries."

**Brevity Checklist:**
- [OK] Remove: "we found that", "it turned out", "after investigation"
- [OK] Use: Direct statements, active voice, concrete facts
- [OK] Cut: Background story, discovery process, emotions
- [OK] Keep: Root cause, impact, fix, prevention

---

## EXTRACTION SIGNAL PATTERNS

### What to Look For

**Signal 1: "Aha!" Moments**
- "Oh, I see now..."
- "That explains why..."
- "So that's how it works..."
- "The real issue was..."

**Signal 2: Pain Points Resolved**
- "After 3 hours, we found..."
- "The problem was actually..."
- "We kept failing until..."
- "The breakthrough came when..."

**Signal 3: Design Decisions Made**
- "We chose X over Y because..."
- "After considering options, we..."
- "The tradeoff was..."
- "We decided against X due to..."

**Signal 4: Patterns Discovered**
- "This keeps happening..."
- "Every time we X, Y occurs..."
- "The pattern is..."
- "We noticed that..."

**Signal 5: Mistakes Made**
- "We shouldn't have..."
- "That was a mistake because..."
- "Don't ever..."
- "If we had known..."

**Signal 6: Wisdom Synthesized**
- "The key insight is..."
- "What really matters is..."
- "The fundamental principle..."
- "Everything comes down to..."

**Signal 7: Process Improvements**
- "Next time we'll..."
- "A better approach would be..."
- "The systematic way is..."
- "We should always..."

---

## ENHANCED EXTRACTION WORKFLOWS

### Universal Extraction Template (SIMAv4)

**Use this for ALL knowledge types:**

```
STEP 1: Identify Signal
- What triggered this knowledge?
- What type is it? (LESS, AP, DEC, BUG, etc.)

STEP 2: Check for Duplicates
- Search existing entries: "[keyword] [type]"
- Similar concept already documented?
- If YES -> Update existing, don't create new
- If NO -> Proceed to Step 3

STEP 3: Genericize
- Strip project-specific details
- Extract universal principle
- Remove tool/framework names (unless core)
- Focus on transferable pattern

STEP 4: Extract Core Content (BRIEF)
- Root cause/principle (1 sentence)
- Context (1 sentence IF needed)
- Impact (quantified if possible)
- Application (1 sentence)
- Example (2-3 lines MAX)

STEP 5: Assign REF-ID
- Use next sequential number
- Never reuse IDs

STEP 6: Create Entry (as artifact) (SIMAv4)
- Follow brevity standards
- 4-8 keywords
- 3-7 related topics
- Minimal cross-ref text
- Filename in header
- File <=400 lines
- Output as markdown artifact
- Brief chat: "Creating [TYPE-##] artifact..."
```

---

## WORKFLOW 1: Extract LESSONS (LESS-##)

**Enhanced Process (SIMAv4):**

```
1. Identify learning moment

2. CHECK DUPLICATES
   Search: "project_knowledge_search: [topic] lesson"
   If similar exists -> Update that entry instead

3. GENERICIZE
   Remove: Project names, specific tools, languages
   Keep: Universal principle, pattern, anti-pattern avoided
   Transform: "In Lambda..." -> "In serverless environments..."
   Transform: "Using Python..." -> "In dynamic languages..."

4. Extract (BRIEF):
   - Context: 1 sentence
   - Problem: What failed/succeeded
   - Discovery: Core insight (generic)
   - Principle: Universal rule
   - Application: When to apply

5. Assign LESS-## (next available)

6. Create file as artifact (SIMAv4):
   Brief chat: "Creating LESS-## artifact..."
   - File: NM06/NM06-Lessons-[Topic]_LESS-##.md
   - Filename in header
   - Format: Markdown
   - <=400 lines
   - Output: Complete artifact
   Brief chat: "LESS-## created. [1-sentence summary]"
```

**Quality Check:**
- [OK] Generic (no unnecessary project-specifics)
- [OK] Unique (not duplicate)
- [OK] Brief (<=400 lines)
- [OK] Clear application context
- [OK] [NEW] Filename in header (SIMAv4)
- [OK] [NEW] Output as artifact
- [OK] [NEW] Minimal chat

---

## WORKFLOW 2: Extract ANTI-PATTERNS (AP-##)

**Enhanced Process (SIMAv4):**

```
1. Identify anti-pattern

2. CHECK DUPLICATES
   Search: "project_knowledge_search: [pattern] anti-pattern"
   Check: Is this already AP-## somewhere?

3. GENERICIZE
   Pattern: Generic description (no project names)
   Why: Universal reason it's wrong
   Impact: Generic consequences
   Alternative: Generic solution

4. Extract (BRIEF):
   - Pattern: What NOT to do (generic)
   - Why: Root cause (generic)
   - Impact: Quantified if possible
   - Alternative: RIGHT way (generic)
   - Example: 2-3 lines MAX

5. Assign AP-## (next available)

6. Create file as artifact (SIMAv4):
   Brief chat: "Creating AP-## artifact..."
   - File: NM05/NM05-AntiPatterns-[Category]_AP-##.md
   - Filename in header
   - Format: Markdown
   - <=400 lines
   - Output: Complete artifact
   Brief chat: "AP-## created. [1-sentence summary]"
```

**Genericization Examples:**

[X] Before: "Using threading locks in AWS Lambda"
[OK] After: "Using threading primitives in single-threaded runtimes"

[X] Before: "Direct imports from cache_core.py"
[OK] After: "Cross-layer imports bypassing gateway pattern"

---

## WORKFLOW 3: Extract WISDOM (WISD-##)

**Enhanced Process (SIMAv4):**

```
1. Identify wisdom moment (profound insight)

2. CHECK DUPLICATES
   Search: "project_knowledge_search: [concept] wisdom"
   Wisdom should be genuinely NEW insight

3. GENERICIZE (Critical for wisdom!)
   Remove: ALL project specifics
   Extract: Pure principle
   Elevate: From instance to universal
   Generalize: Broadly applicable truth

4. Extract (EXTREMELY BRIEF):
   - Insight: The profound truth (1-2 sentences)
   - Origin: How realized (1 sentence)
   - Implications: What changes (1 sentence)
   - Applications: Where applies (1 sentence)

5. Assign WISD-## (next available)

6. Create file as artifact (SIMAv4):
   Brief chat: "Creating WISD-## artifact..."
   - File: NM06/NM06-Wisdom-Synthesized_WISD-##.md
   - Filename in header
   - Format: Markdown
   - <=400 lines
   - Output: Complete artifact
   Brief chat: "WISD-## created. [1-sentence summary]"
```

**Wisdom Genericization:**

[X] Too specific: "SUGA pattern solves Lambda circular imports"
[OK] Generic: "Gateway patterns eliminate circular dependencies in modular systems"

[X] Too specific: "Reading Python files before modifying prevents errors"
[OK] Generic: "Understand complete state before mutation reduces error rate exponentially"

---

## WORKFLOW 4: Extract BUGS (BUG-##)

**Enhanced Process (SIMAv4):**

```
1. Identify bug

2. CHECK DUPLICATES
   Search: "project_knowledge_search: [symptom] bug"
   Same root cause already documented?

3. GENERICIZE
   Symptom: Generic description
   Root Cause: Generic underlying issue
   Fix: Generic solution approach
   Prevention: Generic strategy

4. Extract (BRIEF):
   - Symptom: What appeared broken (generic)
   - Root Cause: Actual issue (generic)
   - Impact: Quantified (time, performance, etc.)
   - Fix: Solution (generic)
   - Prevention: How to avoid (generic)

5. Assign BUG-## (next available)

6. Create file as artifact (SIMAv4):
   Brief chat: "Creating BUG-## artifact..."
   - File: NM06/NM06-Bugs-Critical_BUG-##.md
   - Filename in header
   - Format: Markdown
   - <=400 lines
   - Output: Complete artifact
   Brief chat: "BUG-## created. [1-sentence summary]"
```

**Example Genericization:**

[X] Specific: "Sentinel object _CacheMiss from cache_core leaked into JSON response"
[OK] Generic: "Implementation sentinel objects leaked across serialization boundary"

---

## WORKFLOW 5: Extract DECISIONS (DEC-##)

**Enhanced Process (SIMAv4):**

```
1. Identify decision point

2. CHECK DUPLICATES
   Search: "project_knowledge_search: [topic] decision"
   Same decision already documented?

3. GENERICIZE
   Decision: Generic choice (pattern, not tool)
   Context: Generic trigger
   Alternatives: Generic options
   Rationale: Generic reasoning
   Constraints: Generic limitations

4. Extract (BRIEF):
   - Decision: What chosen (generic)
   - Context: What prompted (generic)
   - Alternatives: Options considered (generic)
   - Rationale: Why chosen (generic, 1-2 sentences)
   - Outcome: Result (quantified)

5. Assign DEC-## (next available)

6. Create file as artifact (SIMAv4):
   Brief chat: "Creating DEC-## artifact..."
   - File: NM04/NM04-Decisions-[Category]_DEC-##.md
   - Filename in header
   - Format: Markdown
   - <=400 lines
   - Output: Complete artifact
   Brief chat: "DEC-## created. [1-sentence summary]"
```

**Genericization Example:**

[X] Too specific: "Use AWS Lambda instead of EC2 for serverless functions"
[OK] Generic: "Choose stateless execution model over stateful servers for event-driven workloads"

---

## ENHANCED QUALITY STANDARDS

### Updated Quality Criteria (SIMAv4)

**1. Actionable** (unchanged)
- [OK] Can be applied immediately
- [OK] Clear action steps
- [OK] Specific enough to use

**2. Generic**
- [OK] No unnecessary project-specifics
- [OK] Transferable across contexts
- [OK] Universal principles extracted
- [X] Project names, tool names (unless core)

**3. Unique**
- [OK] Not duplicate of existing entry
- [OK] Adds new insight/perspective
- [OK] Distinct from related entries
- [X] Rehashing documented knowledge

**4. Brief (SIMAv4 Enhanced)**
- [OK] <=400 lines total per file
- [OK] Summaries: 2-3 sentences
- [OK] Examples: 2-3 lines
- [OK] Separate files (never condense)
- [X] Long narratives, filler words
- [X] Multiple topics in one file

**5. Complete** (modified)
- [OK] Key info present (but brief!)
- [OK] Related topics linked (REF-IDs only)
- [OK] Minimal examples included
- [OK] [NEW] Output as artifact (SIMAv4)
- [OK] [NEW] Filename in header (SIMAv4)

**6. Verifiable** (unchanged)
- [OK] Testable/validatable
- [OK] Measurable impact

---

## ENHANCED POST-EXTRACTION PROTOCOL

### After Creating Each Entry (SIMAv4)

**Step 1: Duplicate Verification**
- [OK] Searched before creating
- [OK] Confirmed uniqueness
- [OK] Not rehashing existing entry
- [OK] If similar found, updated that instead

**Step 2: Genericization Check**
- [OK] Project-specific details removed
- [OK] Universal principle extracted
- [OK] Tool names removed (unless core)
- [OK] Transferable across contexts

**Step 3: Brevity Check (SIMAv4)**
- [OK] Total lines <=400 per file
- [OK] Summary <=3 sentences
- [OK] Examples <=3 lines each
- [OK] No filler words
- [OK] Separate files (not condensed)

**Step 4: File Creation (SIMAv4)**
- [OK] Correct NM##/ directory
- [OK] Named correctly
- [OK] Template followed
- [OK] All sections completed
- [OK] **Filename in header**
- [OK] **Output as markdown artifact**
- [OK] **Brief chat output**

**Step 5: Content Quality**
- [OK] Passes ALL 6 quality criteria
- [OK] Keywords present (4-8)
- [OK] Related topics linked (REF-IDs only)
- [OK] REF-ID correct

**Step 6: Index Updates (SIMAv4)**
- [OK] Added to Topic Index (as separate artifact)
- [OK] Added to Category Index (as separate artifact)
- [OK] Cross-references updated (as separate artifacts)
- [OK] Brief chat for each update

---

## EXTRACTION EXAMPLES (Enhanced)

### Example 1: LESSON Extraction (SIMAv4)

**Raw Material:**
```
"After 3 hours debugging our Lambda function, we finally
found that the sentinel _CacheMiss object was causing
JSON serialization failures in the API Gateway response."
```

**OLD Way:**
```
LESS-##: Always sanitize sentinels before JSON serialization
Context: In SUGA-ISP Lambda, _CacheMiss sentinels...
```

**NEW Way (SIMAv4):**
```
Brief chat: "Extracting lesson... Creating LESS-## artifact..."

1. Check duplicates: Search "sentinel serialization lesson"
   -> Found none, proceed

2. Genericize:
   - Remove: "Lambda", "_CacheMiss", "API Gateway"
   - Extract: Sentinel leakage across serialization boundaries

3. Create (BRIEF) as artifact:

# NM06-Lessons-Serialization_LESS-##.md
**Version:** 1.0.0
**Date:** 2025-11-01
**Purpose:** Sanitize implementation sentinels at boundaries

LESS-##: Sanitize implementation sentinels at boundaries
Generic Context: Internal sentinels fail serialization
Impact: 535ms penalty, JSON failures
Prevention: Sanitize at boundary layer before serialization
Example: `if value is SENTINEL: return None  # boundary layer`

[Output as markdown artifact]

Brief chat: "LESS-## created. Covers sentinel sanitization at boundaries."
```

**Saved:** ~60% token reduction, fully generic, no duplicates, SIMAv4 compliant

---

## LEARNING MODE BEST PRACTICES (SIMAv4)

### Enhanced Do's

**[OK] DO: Check duplicates FIRST**
- Search before every extraction
- Update existing vs create new
- Strengthen connections over proliferation

**[OK] DO: Genericize aggressively**
- Strip all project-specifics
- Extract universal principles
- Make broadly applicable

**[OK] DO: Be ruthlessly brief**
- Every word must earn its place
- 2-3 sentence summaries
- 2-3 line examples
- No filler, no stories
- <=400 lines per file

**[OK] DO: Extract immediately**
- Capture while fresh
- Don't wait until "later"

**[OK] DO: Cross-reference heavily**
- Link related items
- Build knowledge graph
- Enable discovery

**[OK] DO: Output as artifacts (SIMAv4)**
- Neural map files -> markdown artifacts
- Index updates -> separate artifacts
- Complete entries only
- Filename in header
- Separate files (never condense)

**[OK] DO: Keep chat brief (SIMAv4)**
- Status updates only
- "Creating [X] artifact..."
- Brief summaries (2-3 sentences)
- No long narratives

### Enhanced Don'ts

**[X] DON'T: Create duplicates**
- Always search first
- Update existing entries
- Prevent proliferation

**[X] DON'T: Keep project-specifics**
- Strip unnecessary details
- Extract universal patterns
- Make transferable

**[X] DON'T: Write long summaries**
- No background stories
- No discovery narratives
- Direct facts only

**[X] DON'T: Extract everything**
- Focus on valuable, NEW knowledge
- Skip obvious or duplicate

**[X] DON'T: Be vague**
- Concrete > abstract
- Quantified > qualified
- Actionable > aspirational

**[X] DON'T: Output neural maps in chat (SIMAv4)**
- Always use artifacts
- Complete entry format
- Proper markdown structure

**[X] DON'T: Condense files (SIMAv4)**
- Separate topics = separate files
- Never combine multiple topics
- Keep files focused

**[X] DON'T: Be verbose in chat (SIMAv4)**
- Brief status only
- No long explanations
- Let artifacts speak

**[X] DON'T: Exceed 400 lines (SIMAv4)**
- Neural maps: <=400 lines
- Split if needed
- Keep files focused

---

## SUCCESS METRICS (SIMAv4)

### New Metrics

**Metric 1: Uniqueness Rate**
- Target: 100% unique entries (no duplicates)
- Measure: Duplicate searches performed / entries created
- Goal: 1:1 ratio (search before every creation)

**Metric 2: Genericization Score**
- Target: <2 project-specific terms per entry
- Measure: Project references / total words
- Goal: <5% project-specific content

**Metric 3: Brevity Score (SIMAv4)**
- Target: <=400 lines per file (strict limit)
- Measure: Total lines / file
- Goal: All files within limit

**Metric 4: Knowledge Capture Rate**
- Target: 3-5 new entries per session
- Excellent: 10+ from rich material

**Metric 5: Reuse Frequency**
- Target: 20+ references/month to entries

**Metric 6: Artifact Usage Rate (SIMAv4)**
- Target: 100% neural maps as artifacts
- Measure: Artifacts created / entries created
- Goal: 1:1 ratio (every entry as artifact)

**Metric 7: Chat Brevity (SIMAv4)**
- Target: <=5 sentences chat per entry
- Measure: Chat words / entry
- Goal: Minimal chat overhead

**Metric 8: File Separation (SIMAv4)**
- Target: 1 topic per file (0% condensed)
- Measure: Topics / files
- Goal: 1:1 ratio (separate files always)

---

## GETTING STARTED (SIMAv4)

### First Learning Session

**Step 1: Activate Learning Mode**
```
Say: "Start SIMA Learning Mode"
Claude loads this enhanced context file
```

**Step 2: Provide Source Material**
```
- Paste conversation log
- Upload document
- Describe discussion
- Share notes
```

**Step 3: Enhanced Guided Extraction (SIMAv4)**
```
Brief chat: "Extracting knowledge..."
Claude will:
1. Identify extraction signals
2. Search for duplicates FIRST
3. Genericize content
4. Create brief entries (<=400 lines)
5. Propose knowledge items
6. Apply workflows
7. Create neural map entries as markdown artifacts
8. Update indexes as separate artifacts
9. Keep chat minimal throughout
Brief chat: "[X] artifacts created. [1-sentence summary]"
```

**Step 4: Review Results**
```
Claude provides:
- New entries created (as artifacts)
- Duplicates prevented (updated instead)
- Genericization applied
- Token savings achieved
- REF-IDs assigned
- Indexes updated (as separate artifacts)
- Brief summary only
```

---

## ACTIVATION CHECKLIST

### Ready for Learning Mode When:

- [OK] This file loaded (45-60s)
- [OK] Extraction signals memorized
- [OK] Duplicate detection protocol understood
- [OK] Genericization rules internalized
- [OK] Brevity standards clear (<=400 lines)
- [OK] Workflow patterns understood
- [OK] REF-ID counts current
- [OK] Source material identified
- [OK] [NEW] Artifact output format understood (SIMAv4)
- [OK] [NEW] Chat brevity understood (SIMAv4)
- [OK] [NEW] File separation understood (SIMAv4)

### What Happens Next:

```
1. User says "Start SIMA Learning Mode"
2. Claude confirms activation (brief)
3. User provides source material
4. Claude extracts systematically:
   - Checks duplicates before creating
   - Genericizes all content
   - Minimizes token usage (<=400 lines/file)
   - Outputs as artifacts (markdown)
   - Keeps chat minimal
5. New knowledge added to neural maps
6. Brief session summary
```

---

## REMEMBER (Core Principles)

**Learning Mode Purpose:**
Transform experience -> **Generic, Unique, Brief** knowledge -> Institutional memory

**Five Critical Rules (SIMAv4):**
1. **Check duplicates** - Update existing, don't create duplicates
2. **Genericize** - Strip project-specifics, extract universal principles
3. **Be brief** - Minimize tokens (<=400 lines), maximize assimilation capacity
4. **[NEW] Output as artifacts** - Neural map files as markdown artifacts (SIMAv4)
5. **[NEW] Keep chat minimal** - Brief status only (SIMAv4)

**Success = Knowledge compounds without duplication or bloat, properly formatted, separate files**

---

**END OF SIMA LEARNING MODE CONTEXT**

**Version:** 2.1.0 (SIMAv4 standards integrated)  
**Lines:** 390 (within SIMAv4 limit)  
**Load Time:** 45-60 seconds  
**Enhancements:**
- Duplicate detection mandatory
- Genericization by default
- Extreme brevity standards (<=400 lines)
- [NEW] Artifact output for neural map files (SIMAv4)
- [NEW] Minimal chat output (SIMAv4)
- [NEW] File separation (no condensing) (SIMAv4)
- [NEW] Filename in headers (SIMAv4)
**ROI:** Captures 3-5 unique, generic, brief entries per session as proper artifacts  
**Value:** Permanent, transferable, efficient institutional memory in proper format

---

**To activate:**
```
"Start SIMA Learning Mode"
```

# SIMA-LEARNING-SESSION-START-Quick-Context.md

**Version:** 2.0.1  
**Date:** 2025-10-25  
**Purpose:** Knowledge extraction and learning mode - ENHANCED  
**Activation:** "Start SIMA Learning Mode"  
**Load time:** 45-60 seconds (ONE TIME per learning session)  
**FIXED:** Added artifact usage note for neural map files

---

## 🎯 WHAT THIS FILE IS

This is your **knowledge extraction bootstrap file**. Read it ONCE when entering SIMA Learning Mode to activate:
- Pattern recognition for 10+ knowledge types
- Extraction workflows with **genericization** and **duplicate checks**
- Quality standards emphasizing **brevity**
- REF-ID assignment rules
- SIMA v3 routing logic
- Post-extraction protocols
- 🆕 **Artifact output for neural map files**

**Purpose:** Transform raw material into structured, **generic, unique, concise** neural map entries.

**Time investment:** 60 seconds now enables systematic knowledge capture all session.

---

## 📦 ARTIFACT USAGE (Learning Mode) 🆕

**When creating neural map entries:**

### Output Format
```
✅ Neural map files → Artifacts (markdown)
✅ Multiple entries → Multiple artifacts
✅ Index updates → Artifacts
✅ Cross-references → Include in artifacts
❌ Don't output neural maps in chat
```

### Quality Standards
```
✅ Complete entry (all sections)
✅ Proper REF-ID format
✅ 4-8 keywords
✅ 3-7 related topics
✅ Brief (< 200 lines)
✅ Generic (no project-specifics)
✅ Unique (not duplicate)
```

**Default: Output neural map entries as complete markdown artifacts**

---

## ⚡ THREE CRITICAL ENHANCEMENTS (v2.0)

### Enhancement 1: Genericize by Default

**Rule:** Strip project-specific details unless directly relevant to current project.

**Process:**
1. Extract the **universal principle** from the specific instance
2. Remove references to specific tools/frameworks/languages (unless core to lesson)
3. Focus on **transferable patterns** and **timeless insights**
4. Keep only context needed to understand the principle

**Examples:**

❌ **Too Specific:**
> "In SUGA-ISP Lambda, we found that threading locks caused deadlock because AWS Lambda runs single-threaded."

✅ **Properly Generic:**
> "Threading primitives fail in single-threaded execution environments. Verify runtime model before choosing concurrency approach."

❌ **Too Specific:**
> "We use gateway.py to prevent circular imports in our Python Lambda project."

✅ **Properly Generic:**
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
   → DON'T create duplicate
   → Update existing entry instead
   → Cross-reference related items

3. If truly new:
   → Proceed with creation
   → Note why it's distinct from existing
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

**Before:**
> "After conducting extensive debugging sessions and analyzing the performance characteristics of our system, we eventually discovered through careful investigation that the root cause of the slowdown was related to how we were handling cache misses, which were occurring more frequently than expected due to..."

**After:**
> "Cache miss handling caused 535ms penalty. Root: _CacheMiss objects leaked across boundaries."

**Brevity Checklist:**
- ✅ Remove: "we found that", "it turned out", "after investigation"
- ✅ Use: Direct statements, active voice, concrete facts
- ✅ Cut: Background story, discovery process, emotions
- ✅ Keep: Root cause, impact, fix, prevention

---

## 🔎 EXTRACTION SIGNAL PATTERNS

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

## 📋 ENHANCED EXTRACTION WORKFLOWS

### Universal Extraction Template

**Use this for ALL knowledge types:**

```
STEP 1: Identify Signal
- What triggered this knowledge?
- What type is it? (LESS, AP, DEC, BUG, etc.)

STEP 2: Check for Duplicates ⚠️ NEW
- Search existing entries: "[keyword] [type]"
- Similar concept already documented?
- If YES → Update existing, don't create new
- If NO → Proceed to Step 3

STEP 3: Genericize ⚠️ NEW
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

STEP 6: Create Entry (as artifact) 🆕
- Follow brevity standards
- 4-8 keywords
- 3-7 related topics
- Minimal cross-ref text
- Output as markdown artifact
```

---

### Workflow 1: Extract LESSONS (LESS-##)

**Enhanced Process:**

```
1. Identify learning moment

2. ⚠️ CHECK DUPLICATES
   Search: "project_knowledge_search: [topic] lesson"
   If similar exists → Update that entry instead

3. ⚠️ GENERICIZE
   Remove: Project names, specific tools, languages
   Keep: Universal principle, pattern, anti-pattern avoided
   Transform: "In Lambda..." → "In serverless environments..."
   Transform: "Using Python..." → "In dynamic languages..."

4. Extract (BRIEF):
   - Context: 1 sentence
   - Problem: What failed/succeeded
   - Discovery: Core insight (generic)
   - Principle: Universal rule
   - Application: When to apply

5. Assign LESS-## (next available)

6. Create file as artifact: 🆕
   - File: NM06/NM06-Lessons-[Topic]_LESS-##.md
   - Format: Markdown
   - Output: Complete artifact
```

**Quality Check:**
- ✅ Generic (no unnecessary project-specifics)
- ✅ Unique (not duplicate)
- ✅ Brief (< 200 lines)
- ✅ Clear application context
- ✅ 🆕 Output as artifact

---

### Workflow 2: Extract ANTI-PATTERNS (AP-##)

**Enhanced Process:**

```
1. Identify anti-pattern

2. ⚠️ CHECK DUPLICATES
   Search: "project_knowledge_search: [pattern] anti-pattern"
   Check: Is this already AP-## somewhere?

3. ⚠️ GENERICIZE
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

6. Create file as artifact: 🆕
   - File: NM05/NM05-AntiPatterns-[Category]_AP-##.md
   - Format: Markdown
   - Output: Complete artifact
```

**Genericization Examples:**

❌ Before: "Using threading locks in AWS Lambda"
✅ After: "Using threading primitives in single-threaded runtimes"

❌ Before: "Direct imports from cache_core.py"
✅ After: "Cross-layer imports bypassing gateway pattern"

---

### Workflow 3: Extract WISDOM (WISD-##)

**Enhanced Process:**

```
1. Identify wisdom moment (profound insight)

2. ⚠️ CHECK DUPLICATES
   Search: "project_knowledge_search: [concept] wisdom"
   Wisdom should be genuinely NEW insight

3. ⚠️ GENERICIZE (Critical for wisdom!)
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

6. Create file as artifact: 🆕
   - File: NM06/NM06-Wisdom-Synthesized_WISD-##.md
   - Format: Markdown
   - Output: Complete artifact
```

**Wisdom Genericization:**

❌ Too specific: "SUGA pattern solves Lambda circular imports"
✅ Generic: "Gateway patterns eliminate circular dependencies in modular systems"

❌ Too specific: "Reading Python files before modifying prevents errors"
✅ Generic: "Understand complete state before mutation reduces error rate exponentially"

---

### Workflow 4: Extract BUGS (BUG-##)

**Enhanced Process:**

```
1. Identify bug

2. ⚠️ CHECK DUPLICATES
   Search: "project_knowledge_search: [symptom] bug"
   Same root cause already documented?

3. ⚠️ GENERICIZE
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

6. Create file as artifact: 🆕
   - File: NM06/NM06-Bugs-Critical_BUG-##.md
   - Format: Markdown
   - Output: Complete artifact
```

**Example Genericization:**

❌ Specific: "Sentinel object _CacheMiss from cache_core leaked into JSON response"
✅ Generic: "Implementation sentinel objects leaked across serialization boundary"

---

### Workflow 5: Extract DECISIONS (DEC-##)

**Enhanced Process:**

```
1. Identify decision point

2. ⚠️ CHECK DUPLICATES
   Search: "project_knowledge_search: [topic] decision"
   Same decision already documented?

3. ⚠️ GENERICIZE
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

6. Create file as artifact: 🆕
   - File: NM04/NM04-Decisions-[Category]_DEC-##.md
   - Format: Markdown
   - Output: Complete artifact
```

**Genericization Example:**

❌ Too specific: "Use AWS Lambda instead of EC2 for serverless functions"
✅ Generic: "Choose stateless execution model over stateful servers for event-driven workloads"

---

### Workflow 6-12: Similar Enhancements

**All remaining workflows follow same pattern:**

1. Signal detection
2. ⚠️ **Duplicate check** (search before creating)
3. ⚠️ **Genericize** (strip project-specifics)
4. Extract (**brief** content)
5. Assign REF-ID
6. Create entry **as artifact** 🆕

**Specific notes:**

- **DT (Decision Trees):** Generic branching logic, not tool-specific
- **FW (Frameworks):** Transferable processes, not project workflows
- **ARCH (Architecture):** Generic patterns, not specific implementations
- **RULE (Rules):** Universal principles, not codebase-specific
- **PATH/FLOW:** Generic operation patterns, not function names
- **TRACE:** Generic debugging methods, not tool-specific

---

## 📊 ENHANCED QUALITY STANDARDS

### Updated Quality Criteria

**1. Actionable** (unchanged)
- ✅ Can be applied immediately
- ✅ Clear action steps
- ✅ Specific enough to use

**2. Generic** ⚠️ NEW
- ✅ No unnecessary project-specifics
- ✅ Transferable across contexts
- ✅ Universal principles extracted
- ❌ Project names, tool names (unless core)

**3. Unique** ⚠️ NEW
- ✅ Not duplicate of existing entry
- ✅ Adds new insight/perspective
- ✅ Distinct from related entries
- ❌ Rehashing documented knowledge

**4. Brief** ⚠️ NEW
- ✅ < 200 lines total
- ✅ Summaries: 2-3 sentences
- ✅ Examples: 2-3 lines
- ❌ Long narratives, filler words

**5. Complete** (modified)
- ✅ Key info present (but brief!)
- ✅ Related topics linked (REF-IDs only)
- ✅ Minimal examples included
- ✅ 🆕 Output as artifact

**6. Verifiable** (unchanged)
- ✅ Testable/validatable
- ✅ Measurable impact

---

## ✅ ENHANCED POST-EXTRACTION PROTOCOL

### After Creating Each Entry

**Step 1: Duplicate Verification** ⚠️ NEW
- ✅ Searched before creating
- ✅ Confirmed uniqueness
- ✅ Not rehashing existing entry
- ✅ If similar found, updated that instead

**Step 2: Genericization Check** ⚠️ NEW
- ✅ Project-specific details removed
- ✅ Universal principle extracted
- ✅ Tool names removed (unless core)
- ✅ Transferable across contexts

**Step 3: Brevity Check** ⚠️ NEW
- ✅ Total lines < 200
- ✅ Summary < 3 sentences
- ✅ Examples < 3 lines each
- ✅ No filler words

**Step 4: File Creation** 🆕
- ✅ Correct NM##/ directory
- ✅ Named correctly
- ✅ Template followed
- ✅ All sections completed
- ✅ **Output as markdown artifact**

**Step 5: Content Quality**
- ✅ Passes ALL 6 quality criteria
- ✅ Keywords present (4-8)
- ✅ Related topics linked (REF-IDs only)
- ✅ REF-ID correct

**Step 6: Index Updates** 🆕
- ✅ Added to Topic Index (as artifact)
- ✅ Added to Category Index (as artifact)
- ✅ Cross-references updated (as artifacts)

---

## 🎯 EXTRACTION EXAMPLES (v2.0 Enhanced)

### Example 1: LESSON Extraction (Enhanced)

**Raw Material:**
```
"After 3 hours debugging our Lambda function, we finally
found that the sentinel _CacheMiss object was causing
JSON serialization failures in the API Gateway response."
```

**OLD Way (v1.0):**
```
LESS-##: Always sanitize sentinels before JSON serialization
Context: In SUGA-ISP Lambda, _CacheMiss sentinels...
```

**NEW Way (v2.0):**
```
1. Check duplicates: Search "sentinel serialization lesson"
   → Found none, proceed

2. Genericize:
   - Remove: "Lambda", "_CacheMiss", "API Gateway"
   - Extract: Sentinel leakage across serialization boundaries

3. Create (BRIEF) as artifact: 🆕

LESS-##: Sanitize implementation sentinels at boundaries
Generic Context: Internal sentinels fail serialization
Impact: 535ms penalty, JSON failures
Prevention: Sanitize at boundary layer before serialization
Example: `if value is SENTINEL: return None  # boundary layer`

[Output as markdown artifact]
```

**Saved:** ~60% token reduction, fully generic, no duplicates

---

### Example 2: ANTI-PATTERN Extraction (Enhanced)

**Raw Material:**
```
"We tried adding threading locks to coordinate
between different Lambda invocations, but this
caused deadlocks because Lambda is single-threaded."
```

**OLD Way (v1.0):**
```
AP-##: Using threading locks in Lambda
Description: In AWS Lambda environment, threading primitives...
```

**NEW Way (v2.0):**
```
1. Check duplicates: Search "threading anti-pattern"
   → Found AP-08 already exists!
   → Update AP-08 with new example instead of creating duplicate

2. If it didn't exist, would genericize:
   Pattern: Threading primitives in single-threaded runtimes
   Why: Runtime model incompatibility
   Impact: Deadlocks, resource waste
   Alternative: Atomic operations, message passing

[Would output as markdown artifact]
```

**Prevented:** Duplicate creation, updated existing instead

---

### Example 3: WISDOM Extraction (Enhanced)

**Raw Material:**
```
"Looking back, we realized that every time we
assumed we understood the code without reading
it completely, we introduced bugs. The pattern
is clear: assumptions without verification fail."
```

**OLD Way (v1.0):**
```
WISD-##: Read complete files before modifying
In our Python codebase, we learned...
```

**NEW Way (v2.0):**
```
1. Check duplicates: Search "assumptions verification wisdom"
   → None found, proceed

2. Genericize (maximum abstraction):
   - Remove: "code", "Python", "files", "bugs"
   - Elevate: To universal principle about assumptions

3. Create (EXTREMELY BRIEF) as artifact: 🆕

WISD-##: Assumptions without verification compound exponentially
Insight: Unverified assumptions create cascading failures
Origin: Pattern recognition across multiple error-fix cycles
Implication: Verification cost < exponential correction cost
Application: Any system modification at any scale

[Output as markdown artifact]
```

**Result:** Pure wisdom, fully transferable, minimal tokens

---

## 🎓 LEARNING MODE BEST PRACTICES (v2.0)

### Enhanced Do's

**✅ DO: Check duplicates FIRST**
- Search before every extraction
- Update existing vs create new
- Strengthen connections over proliferation

**✅ DO: Genericize aggressively**
- Strip all project-specifics
- Extract universal principles
- Make broadly applicable

**✅ DO: Be ruthlessly brief**
- Every word must earn its place
- 2-3 sentence summaries
- 2-3 line examples
- No filler, no stories

**✅ DO: Extract immediately**
- Capture while fresh
- Don't wait until "later"

**✅ DO: Cross-reference heavily**
- Link related items
- Build knowledge graph
- Enable discovery

**✅ DO: Output as artifacts** 🆕
- Neural map files → markdown artifacts
- Index updates → artifacts
- Complete entries only

### Enhanced Don'ts

**❌ DON'T: Create duplicates**
- Always search first
- Update existing entries
- Prevent proliferation

**❌ DON'T: Keep project-specifics**
- Strip unnecessary details
- Extract universal patterns
- Make transferable

**❌ DON'T: Write long summaries**
- No background stories
- No discovery narratives
- Direct facts only

**❌ DON'T: Extract everything**
- Focus on valuable, NEW knowledge
- Skip obvious or duplicate

**❌ DON'T: Be vague**
- Concrete > abstract
- Quantified > qualified
- Actionable > aspirational

**❌ DON'T: Output neural maps in chat** 🆕
- Always use artifacts
- Complete entry format
- Proper markdown structure

---

## 📊 SUCCESS METRICS (v2.0)

### New Metrics

**Metric 1: Uniqueness Rate**
- Target: 100% unique entries (no duplicates)
- Measure: Duplicate searches performed / entries created
- Goal: 1:1 ratio (search before every creation)

**Metric 2: Genericization Score**
- Target: < 2 project-specific terms per entry
- Measure: Project references / total words
- Goal: < 5% project-specific content

**Metric 3: Brevity Score**
- Target: < 150 lines per entry (avg)
- Measure: Total lines / entries created
- Goal: Trend downward over time

**Metric 4: Knowledge Capture Rate** (existing)
- Target: 3-5 new entries per session
- Excellent: 10+ from rich material

**Metric 5: Reuse Frequency** (existing)
- Target: 20+ references/month to entries

**Metric 6: Artifact Usage Rate** 🆕
- Target: 100% neural maps as artifacts
- Measure: Artifacts created / entries created
- Goal: 1:1 ratio (every entry as artifact)

---

## 🚀 GETTING STARTED (v2.0 Enhanced)

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

**Step 3: Enhanced Guided Extraction**
```
Claude will:
1. Identify extraction signals
2. ⚠️ Search for duplicates FIRST
3. ⚠️ Genericize content
4. ⚠️ Create brief entries
5. Propose knowledge items
6. Apply workflows
7. 🆕 Create neural map entries as markdown artifacts
8. 🆕 Update indexes as artifacts
```

**Step 4: Review Results**
```
Claude provides:
- New entries created (as artifacts)
- Duplicates prevented (updated instead)
- Genericization applied
- Token savings achieved
- REF-IDs assigned
- Indexes updated (as artifacts)
```

---

## 📋 ACTIVATION CHECKLIST

### Ready for Learning Mode When:

- ✅ This file loaded (45-60s)
- ✅ Extraction signals memorized
- ✅ ⚠️ NEW: Duplicate detection protocol understood
- ✅ ⚠️ NEW: Genericization rules internalized
- ✅ ⚠️ NEW: Brevity standards clear
- ✅ Workflow patterns understood
- ✅ REF-ID counts current
- ✅ Source material identified
- ✅ 🆕 Artifact output format understood

### What Happens Next:

1. User says "Start SIMA Learning Mode"
2. Claude confirms activation
3. User provides source material
4. Claude extracts systematically:
   - **Checks duplicates** before creating
   - **Genericizes** all content
   - **Minimizes** token usage
   - 🆕 **Outputs as artifacts** (markdown)
5. New knowledge added to neural maps
6. Session summary with metrics

---

## 🎯 REMEMBER (v2.0 Core Principles)

**Learning Mode Purpose:**
Transform experience → **Generic, Unique, Brief** knowledge → Institutional memory

**Four Critical Rules:**
1. **Check duplicates** - Update existing, don't create duplicates
2. **Genericize** - Strip project-specifics, extract universal principles
3. **Be brief** - Minimize tokens, maximize assimilation capacity
4. 🆕 **Output as artifacts** - Neural map files as markdown artifacts

**Success = Knowledge compounds without duplication or bloat, properly formatted**

---

**END OF SIMA LEARNING MODE CONTEXT v2.0.1**

**Version:** 2.0.1 (Enhanced with artifact usage rules) 🆕  
**Lines:** ~1000 (fits SIMA v3 spec)  
**Load Time:** 45-60 seconds  
**Enhancements:**
- ⚠️ Duplicate detection mandatory
- ⚠️ Genericization by default
- ⚠️ Extreme brevity standards
- 🆕 Artifact output for neural map files
**ROI:** Captures 3-5 unique, generic, brief entries per session as proper artifacts  
**Value:** Permanent, transferable, efficient institutional memory in proper format

---

**To activate:**
```
"Start SIMA Learning Mode"
```

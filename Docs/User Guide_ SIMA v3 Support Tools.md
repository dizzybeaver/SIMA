# User Guide: SIMA v3 Support Tools

**Version:** 1.0.0  
**Date:** 2025-10-24  
**Purpose:** Complete guide to using SIMA v3 optimization tools  
**Audience:** All SUGA-ISP developers

---

## 📚 TABLE OF CONTENTS

1. [Overview](#overview)
2. [Getting Started](#getting-started)
3. [SESSION-START Tool](#session-start-tool)
4. [Anti-Patterns Checklist](#anti-patterns-checklist)
5. [REF-ID Directory](#ref-id-directory)
6. [Workflows Playbook](#workflows-playbook)
7. [Common Scenarios](#common-scenarios)
8. [Best Practices](#best-practices)
9. [Troubleshooting](#troubleshooting)

---

## OVERVIEW

### What Are Support Tools?

The Support Tools are optimization files that make working with SUGA-ISP faster and more efficient:

**4 Tool Sets:**
1. **SESSION-START** - Bootstrap context for every session (MANDATORY)
2. **Anti-Patterns Checklist** - Fast verification (5-10 seconds)
3. **REF-ID Directory** - Quick cross-reference lookup
4. **Workflows Playbook** - 11 pre-mapped decision trees

**Located:** `/nmap/Support/` directory (30 files total)

### Why Use Support Tools?

**Before Support Tools:**
- 10-15 minutes setup per session
- 30-60 seconds per query
- Searching from scratch each time
- Easy to miss anti-patterns
- Hard to find cross-references

**After Support Tools:**
- 45 seconds setup (ONE TIME)
- 5-60 seconds per query
- Pre-loaded routing patterns
- Instant anti-pattern checks
- Fast REF-ID lookups

**Net Savings:** 4-6 minutes per 10-query session

---

## GETTING STARTED

### Prerequisites

1. ✅ Access to Claude with project knowledge
2. ✅ File-Server-URLs.md uploaded to session
3. ✅ Basic understanding of SUGA-ISP architecture
4. ✅ Familiarity with SIMA pattern

### First-Time Setup (5 minutes)

**Step 1: Upload File Server URLs**
```
1. Open new Claude session
2. Upload: File-Server-URLs.md
3. Confirm: Claude can access file server
```

**Step 2: Load SESSION-START (MANDATORY)**
```
1. Search project knowledge: "SESSION-START-Quick-Context"
2. Read complete file (30-45 seconds)
3. Verify: Critical sections present
```

**Step 3: Verify Tools Available**
```
Ask Claude:
"Can you access the Support tools?"

Expected response:
"Yes, I have access to:
- SESSION-START-Quick-Context.md
- Anti-Patterns Checklist (5 files)
- REF-ID Directory (7 files)
- Workflows Playbook (15 files)"
```

**Step 4: Test Basic Query**
```
Ask Claude:
"Can I use threading locks?"

Expected response:
"NO - Lambda is single-threaded (DEC-04, AP-08)"
< 15 seconds response time
```

**✅ You're ready to use Support Tools!**

---

## SESSION-START TOOL

### Purpose

**Mandatory bootstrap file** that loads critical context into every session.

**Load ONCE per session:** 30-45 seconds  
**Saves:** 4-6 minutes on subsequent queries

### What It Contains

**Critical Architecture:**
- SIMA pattern (Gateway → Interface → Core)
- 12 core interfaces (INT-01 to INT-12)
- Import rules (RULE-01, lazy imports)
- Dependency layers (DEP-01 to DEP-08)

**Instant Answers:**
- Top 10 common questions (no search needed)
- RED FLAGS (never suggest these)
- Top 20 REF-IDs (most frequently used)

**Routing Maps:**
- Query patterns → File mapping
- Keyword → File mapping
- Fast path to right documentation

### How to Use

**Every Session:**
```
1. Start new Claude session
2. Upload File-Server-URLs.md
3. Search: "SESSION-START-Quick-Context"
4. Let Claude read complete file (30-45s)
5. Proceed with your questions
```

**Verification:**
```
Ask Claude:
"What are the 3 golden rules?"

Expected response:
"1. RULE-01: Always import via gateway
 2. ARCH-07: Use lazy imports
 3. DEP-01 to DEP-08: Respect dependency layers"

If Claude answers correctly, SESSION-START is loaded ✅
```

### Common Mistakes

**❌ Mistake 1: Skipping SESSION-START**
- Impact: Slower responses, missing context
- Fix: Always load at session start

**❌ Mistake 2: Not reading complete file**
- Impact: Partial context, inconsistent answers
- Fix: Wait for full load (30-45s)

**❌ Mistake 3: Loading multiple times**
- Impact: Wasted time
- Fix: Load ONCE per session only

---

## ANTI-PATTERNS CHECKLIST

### Purpose

**Fast verification** that code doesn't violate known anti-patterns.

**Use when:** Before suggesting any solution  
**Time:** 5-10 seconds scan  
**Files:** 5 (hub + 4 components)

### File Structure

**Hub File:**
- `ANTI-PATTERNS CHECKLIST-HUB.md` - Routes to components

**Component Files:**
1. `AP-Checklist-Critical.md` - 4 critical patterns (ALWAYS check)
2. `AP-Checklist-ByCategory.md` - All 28 patterns organized
3. `AP-Checklist-Scenarios.md` - Common "Can I" scenarios
4. `ANTI-PATTERNS CHECKLIST.md` - Complete reference

### How to Use

**Quick Pre-Flight Check (5 seconds):**
```
Before suggesting ANY solution:

1. Think: "Does this violate critical anti-patterns?"
   - Threading locks? (AP-08)
   - Direct imports? (AP-01)
   - Bare except? (AP-14)
   - Sentinel leak? (AP-19)

2. If YES to any → STOP and redesign
3. If NO to all → Proceed with solution
```

**Detailed Check (10 seconds):**
```
For complex solutions:

1. Open: AP-Checklist-ByCategory.md
2. Find relevant category
3. Scan patterns in that category
4. Verify no violations
5. Proceed if clear
```

**Scenario-Based Check (15 seconds):**
```
For "Can I" questions:

1. Open: AP-Checklist-Scenarios.md
2. Find matching scenario
3. Use template response
4. Done!
```

### Example Usage

**Scenario: User wants to add threading**

```
User: "Can I add threading locks for concurrent access?"

Your thought process:
1. Check AP-Checklist-Critical.md
2. Find: AP-08 (Threading primitives)
3. Status: CRITICAL - Never do
4. Response:

"NO - Lambda is single-threaded (DEC-04, AP-08)

Lambda runs in a single-threaded environment, making
threading locks unnecessary and harmful. Instead, use
atomic operations or external coordination.

Rationale: [Explain from DEC-04]
Alternative: [Provide correct approach]"
```

**Time:** < 15 seconds total

### Common Scenarios

| Question | Check File | Time | Answer |
|----------|-----------|------|--------|
| "Can I use threading?" | Critical | 5s | NO |
| "Can I import directly?" | Critical | 5s | NO |
| "Can I use bare except?" | Critical | 5s | NO |
| "Can I add subdirs?" | ByCategory | 10s | NO |
| "Can I use library X?" | ByCategory | 10s | Depends |

---

## REF-ID DIRECTORY

### Purpose

**Quick cross-reference lookup** for finding REF-ID locations and details.

**Use when:** You encounter a REF-ID and need more info  
**Time:** 5-10 seconds lookup  
**Files:** 7 (hub + 6 components)

### File Structure

**Hub File:**
- `REF-ID-DIRECTORY-HUB.md` - Routes by prefix

**Component Files (by prefix):**
1. `REF-ID-Directory-ARCH-INT.md` - ARCH, INT prefixes
2. `REF-ID-Directory-AP-BUG.md` - AP, BUG prefixes
3. `REF-ID-Directory-DEC.md` - DEC prefix
4. `REF-ID-Directory-LESS-WISD.md` - LESS, WISD prefixes
5. `REF-ID-Directory-Others.md` - DEP, DT, ERR, FLOW, PATH, etc.
6. `REF-ID Complete Directory.md` - Full alphabetical list

### How to Use

**Quick Lookup (5 seconds):**
```
Encounter: "Related: DEC-04"

Process:
1. Identify prefix: DEC
2. Open: REF-ID-Directory-DEC.md
3. Find: DEC-04
4. Read: "No threading locks - NM04/DEC-04.md"
5. Done!
```

**Detailed Lookup (10 seconds):**
```
Need full info on DEC-04:

1. Look up in REF-ID-Directory-DEC.md
2. Get file location: NM04/NM04-Decisions-Technical_DEC-04.md
3. Open that file via web_fetch
4. Read complete decision
5. Cite in response
```

### Routing by Prefix

| Prefix | Component File | Example REF-IDs |
|--------|---------------|-----------------|
| ARCH | ARCH-INT | ARCH-01 to ARCH-09 |
| INT | ARCH-INT | INT-01 to INT-12 |
| AP | AP-BUG | AP-01 to AP-28 |
| BUG | AP-BUG | BUG-01 to BUG-04 |
| DEC | DEC | DEC-01 to DEC-23 |
| LESS | LESS-WISD | LESS-01 to LESS-21 |
| WISD | LESS-WISD | WISD-01 to WISD-05 |
| DEP | Others | DEP-01 to DEP-08 |
| DT | Others | DT-01 to DT-13 |
| Others | Others | ERR, FLOW, PATH, etc. |

### Example Usage

**Scenario: Following cross-references**

```
Reading BUG-01:
"Sentinel leak caused 535ms penalty. Fixed via DEC-05."

To understand DEC-05:
1. Open REF-ID-Directory-DEC.md
2. Find: "DEC-05: Sentinel sanitization - NM04/DEC-05.md"
3. Fetch: NM04/NM04-Decisions-Technical_DEC-05.md
4. Read: Complete decision with rationale
5. Understand: How sanitization fixes the bug

Total time: ~15 seconds
```

---

## WORKFLOWS PLAYBOOK

### Purpose

**Pre-mapped decision trees** for common development scenarios.

**Use when:** Common task needs systematic approach  
**Time:** 10-60 seconds (depends on workflow)  
**Files:** 15 (hub + 11 workflows + 3 additional)

### Available Workflows

**Hub File:**
- `WORKFLOWS-PLAYBOOK-HUB.md` - Routes to workflows

**11 Complete Workflows:**

| # | Workflow | When to Use | Time |
|---|----------|-------------|------|
| 01 | AddFeature | Adding new functionality | 30-60s |
| 02 | ReportError | Debugging issues | 30-60s |
| 03 | ModifyCode | Changing existing code | 30-60s |
| 04 | WhyQuestions | Understanding design | 20-40s |
| 05 | CanIQuestions | Permission questions | 15-30s |
| 06 | Optimize | Performance tuning | 40-60s |
| 07 | ImportIssues | Import problems | 30-60s |
| 08 | ColdStart | Cold start optimization | 40-60s |
| 09 | DesignQuestions | Architecture advice | 30-60s |
| 10 | ArchitectureOverview | Learning system | 20-40s |
| 11 | FetchFiles | Getting current files | 10-20s |

### How to Use Workflows

**Pattern-Based Selection:**
```
User query → Workflow selection

"Can I [X]?" → Workflow-05 (CanIQuestions)
"Add [feature]" → Workflow-01 (AddFeature)
"Error: [X]" → Workflow-02 (ReportError)
"Why [X]?" → Workflow-04 (WhyQuestions)
"Optimize [X]" → Workflow-06 (Optimize)
"Import error" → Workflow-07 (ImportIssues)
"Cold start slow" → Workflow-08 (ColdStart)
"How to design [X]?" → Workflow-09 (DesignQuestions)
"Explain architecture" → Workflow-10 (ArchitectureOverview)
```

**Step-by-Step Execution:**
```
Each workflow provides:
1. ✅ Decision tree (if/then logic)
2. ✅ Step-by-step process
3. ✅ Template responses
4. ✅ Complete examples
5. ✅ Verification checklist
```

### Workflow Example: Add Feature

**When to use:** Adding new functionality to SUGA-ISP

**Workflow steps:**
```
1. Understand requirements (1 min)
   - What does it do?
   - Inputs/outputs?
   - Constraints?

2. Check existing functionality (15s)
   - Search gateway functions
   - Already implemented?

3. Design SIMA implementation (2 min)
   - Choose interface (INT-01 to INT-12)
   - Gateway wrapper
   - Interface definition
   - Core implementation

4. Verify no anti-patterns (30s)
   - Check AP-Checklist-Critical
   - Verify constraints

5. Implement all 3 layers (5-10 min)
   - Gateway (gateway_wrappers.py)
   - Interface (interface_*.py)
   - Core (*_core.py)

6. Verification (LESS-15) (2 min)
   - 5-step checklist
   - Test implementation
```

**Total time:** 10-20 minutes for complete feature

### Quick Workflow Reference

**Most Used (check these first):**
- **Workflow-05:** "Can I" questions (fastest - 15s)
- **Workflow-01:** Add feature (most common)
- **Workflow-11:** Fetch files (before modifying code)
- **Workflow-02:** Report error (debugging)

**Specialized (when needed):**
- **Workflow-08:** Cold start issues
- **Workflow-06:** Performance optimization
- **Workflow-07:** Import problems
- **Workflow-09:** Design advice

**Learning (onboarding):**
- **Workflow-10:** Architecture overview
- **Workflow-04:** "Why" questions

---

## COMMON SCENARIOS

### Scenario 1: Starting a New Session

**Goal:** Get Claude ready to help with SUGA-ISP

**Steps:**
```
1. Open new Claude session
2. Upload File-Server-URLs.md
3. Say: "Load SESSION-START-Quick-Context"
4. Wait 30-45 seconds
5. Verify: Ask "What are the RED FLAGS?"
6. ✅ Ready to work!
```

**Time:** < 2 minutes

---

### Scenario 2: "Can I Do X?" Question

**Goal:** Get instant yes/no answer

**Steps:**
```
1. Ask: "Can I [X]?"
2. Claude checks SESSION-START instant answers
3. If instant answer exists → Response in 5s
4. If not → Workflow-05 (CanIQuestions) → 15-30s
```

**Example:**
```
You: "Can I use threading locks?"
Claude: "NO - Lambda is single-threaded (DEC-04, AP-08)"
Time: < 10 seconds
```

---

### Scenario 3: Adding a New Feature

**Goal:** Implement complete 3-layer SIMA feature

**Steps:**
```
1. Describe what you want to add
2. Claude follows Workflow-01 (AddFeature)
3. Provides complete implementation:
   - Gateway wrapper
   - Interface definition
   - Core implementation
4. Includes LESS-15 verification
```

**Time:** 10-20 minutes total (including implementation)

---

### Scenario 4: Debugging an Error

**Goal:** Systematically troubleshoot issue

**Steps:**
```
1. Describe error (message, logs, context)
2. Claude follows Workflow-02 (ReportError)
3. Checks known bugs first (NM06)
4. If new, traces through layers
5. Provides solution with citations
```

**Time:** 5-15 minutes depending on complexity

---

### Scenario 5: Understanding a Design Decision

**Goal:** Learn why something was designed a certain way

**Steps:**
```
1. Ask: "Why [X]?"
2. Claude follows Workflow-04 (WhyQuestions)
3. Searches design decisions (NM04)
4. Provides rationale with history
5. Cites REF-IDs and lessons learned
```

**Time:** 30-60 seconds

---

### Scenario 6: Looking Up a REF-ID

**Goal:** Find details about a cited REF-ID

**Steps:**
```
1. See citation: "According to DEC-04..."
2. Ask: "Tell me more about DEC-04"
3. Claude uses REF-ID-Directory
4. Provides summary + file location
5. Can fetch full file if needed
```

**Time:** 10-15 seconds

---

## BEST PRACTICES

### Session Management

**✅ DO:**
- Load SESSION-START once per session
- Keep session open for related work
- Upload File-Server-URLs.md at start

**❌ DON'T:**
- Skip SESSION-START (slower responses)
- Load SESSION-START multiple times (waste)
- Start without File-Server-URLs.md

### Query Patterns

**✅ DO:**
- Be specific with questions
- Mention if you see REF-IDs
- Ask "Can I" for permission questions
- Request workflow by name if known

**❌ DON'T:**
- Ask vague questions
- Assume Claude remembers from previous sessions
- Skip verification steps

### Code Modifications

**✅ DO:**
- Use Workflow-11 to fetch current files
- Read complete files (LESS-01)
- Follow LESS-15 verification
- Implement all 3 SIMA layers

**❌ DON'T:**
- Modify without fetching current version
- Skip gateway layer
- Forget interface layer
- Ignore verification checklist

### Anti-Pattern Checks

**✅ DO:**
- Check AP-Checklist-Critical before every solution
- Reference specific AP-## numbers
- Provide alternatives when saying NO
- Cite design decisions (DEC-##)

**❌ DON'T:**
- Skip anti-pattern checks
- Approve RED FLAG patterns
- Give vague explanations
- Forget citations

---

## TROUBLESHOOTING

### Problem: SESSION-START Won't Load

**Symptoms:**
- File not found in project knowledge
- Load times out
- Incomplete loading

**Solutions:**
1. Verify File-Server-URLs.md uploaded
2. Search exact term: "SESSION-START-Quick-Context"
3. Check file exists in /nmap/Support/
4. Try direct web_fetch if search fails

---

### Problem: Queries Take Too Long

**Symptoms:**
- Responses > 60 seconds
- Multiple file searches
- Uncertain answers

**Solutions:**
1. Verify SESSION-START loaded at session start
2. Check routing patterns are in memory
3. Be more specific with queries
4. Use workflow names explicitly

---

### Problem: Anti-Pattern Checks Missed

**Symptoms:**
- Bad suggestions approved
- RED FLAGS violated
- Missing citations

**Solutions:**
1. Re-load SESSION-START
2. Explicitly ask for anti-pattern check
3. Reference AP-Checklist-Critical
4. Verify RED FLAGS in memory

---

### Problem: Cross-References Don't Work

**Symptoms:**
- Can't find referenced REF-IDs
- File paths incorrect
- Navigation fails

**Solutions:**
1. Use REF-ID-Directory for lookups
2. Verify paths use v3 format (NM##/)
3. Check File-Server-URLs.md current
4. Try direct web_fetch with full URL

---

### Problem: Workflows Not Followed

**Symptoms:**
- Incomplete steps
- Missing verification
- Inconsistent responses

**Solutions:**
1. Explicitly request workflow by number
2. Ask for step-by-step execution
3. Verify workflow file accessible
4. Check workflow file not truncated

---

## QUICK REFERENCE CARD

### Session Start Checklist
```
☐ Upload File-Server-URLs.md
☐ Load SESSION-START-Quick-Context (30-45s)
☐ Verify RED FLAGS loaded
☐ Ready to work!
```

### Fast Access Files
```
Critical Check: AP-Checklist-Critical.md (5s)
REF-ID Lookup: REF-ID-Directory-[PREFIX].md (10s)
Common Workflow: Workflow-05-CanIQuestions.md (15s)
File Fetch: Workflow-11-FetchFiles.md (10s)
```

### Common Query Patterns
```
"Can I [X]?" → Workflow-05 → 15s
"Why [X]?" → Workflow-04 → 30s
"Add [feature]" → Workflow-01 → 20-60s
"Error: [X]" → Workflow-02 → 30-60s
"Tell me about [REF-ID]" → REF-ID-Directory → 10s
```

### Time Expectations
```
Instant answer: 5s
Workflow routing: 10-15s
Simple query: 20-30s
Complex query: 30-60s
Complete feature: 10-20 minutes
```

---

## SUMMARY

**Support Tools provide:**
- ✅ 45-second session bootstrap (SESSION-START)
- ✅ 5-second anti-pattern checks
- ✅ 10-second REF-ID lookups
- ✅ 11 pre-mapped workflows
- ✅ 4-6 minutes saved per session

**Usage pattern:**
1. Load SESSION-START once per session
2. Use workflows for common tasks
3. Check anti-patterns before solutions
4. Look up REF-IDs as encountered
5. Follow SIMA pattern always

**Net result:**
- Faster responses
- Better quality
- Fewer mistakes
- Consistent approaches

---

**END OF USER GUIDE**

**Version:** 1.0.0  
**Date:** 2025-10-24  
**Maintained by:** SUGA-ISP Development Team

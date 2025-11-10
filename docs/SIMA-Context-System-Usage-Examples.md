# SIMA-Context-System-Usage-Examples.md

**Version:** 1.0.0  
**Date:** 2025-11-10  
**Purpose:** Practical usage examples for all SIMA context modes  
**Audience:** Developers, documentation maintainers, new users

---

## 📋 OVERVIEW

This document provides real-world usage examples for the SIMA context system's 6 modes:

1. **General Mode** - Q&A, architecture guidance
2. **Learning Mode** - Extract knowledge from conversations
3. **Maintenance Mode** - Update indexes, clean knowledge base
4. **Project Mode** - Build features for specific projects
5. **Debug Mode** - Troubleshoot issues in specific projects
6. **New Project Mode** - Scaffold new project structure

---

## 🎯 MODE 1: GENERAL MODE

### Purpose
Answer questions, provide guidance, explain architecture

### Activation
```
[Upload File Server URLs.md]
"Please load context"
```

### Example 1: Understanding SUGA Architecture

**User:**
```
Please load context

Can you explain the SUGA architecture pattern?
```

**Claude Response:**
```
Context loaded (20 seconds).

SUGA (Serverless Unified Gateway Architecture) is a 3-layer pattern:

**Gateway Layer** (gateway_wrappers.py)
├─> Public API, lazy imports to interfaces
├─> Entry point for all operations
└─> Example: cache_get(key)

**Interface Layer** (interface_*.py)
├─> Routes to core implementations
├─> Lazy imports to core
└─> Example: interface_cache.execute_operation()

**Core Layer** (*_core.py)
├─> Actual implementation logic
├─> No imports of higher layers
└─> Example: cache_core.get_impl()

**Key Rule (RULE-01):** Always import via gateway, never directly to core.

**Why:** Prevents circular imports, enables lazy loading, maintains clean architecture.

**REF:** ARCH-01, DEC-01, /sima/shared/SUGA-Architecture.md
```

### Example 2: Quick "Can I?" Question

**User:**
```
Can I use threading locks in my Lambda function?
```

**Claude Response:**
```
**NO** - Threading locks don't work in AWS Lambda.

**Why:** Lambda is single-threaded. Threading primitives like locks, semaphores, and threading.Thread() will fail or behave unexpectedly.

**Instead:** Use atomic operations or stateless patterns.

**REF:** DEC-04 (No threading in Lambda), AP-08 (Threading primitives anti-pattern)
```

### Example 3: Finding Specific Knowledge

**User:**
```
Where can I find information about cold start optimization?
```

**Claude Response:**
```
Cold start optimization is documented in multiple places:

**Primary Sources:**
- ARCH-07: Lazy Module Management System (LMMS)
- LESS-02: Measure Don't Guess (profiling imports)
- /sima/languages/python/architectures/lmms/

**Key Techniques:**
1. Profile with performance_benchmark.py
2. Identify imports >100ms
3. Move to function-level lazy imports
4. Keep hot path in fast_path.py
5. Target: <3 second cold start

**Workflow:** /sima/support/workflows/Workflow-08-ColdStart.md

**REF:** ARCH-07, LESS-02, LMMS architecture
```

---

## 🎯 MODE 2: LEARNING MODE

### Purpose
Extract knowledge from conversations, create neural map entries

### Activation
```
[Upload File Server URLs.md]
"Start SIMA Learning Mode"
```

### Example 1: Extracting Lesson from Conversation

**User:**
```
Start SIMA Learning Mode

I just spent 3 hours debugging a Lambda function. The problem was that I was using a threading.Lock() which caused the function to hang on the second invocation. After removing the lock and using a simple boolean flag instead, everything worked perfectly.

Please extract lessons from this experience.
```

**Claude Response:**
```
Learning Mode activated (45 seconds).

Extracting knowledge...

[Creates artifact: LESS-XX-Threading-Lambda.md]

**Summary:** Created LESS-XX documenting threading incompatibility in Lambda environments. Key lesson: Threading primitives fail in single-threaded runtimes; use stateless patterns instead.

**Cross-references:** DEC-04 (No threading), AP-08 (Threading anti-pattern)
**Index updated:** /sima/entries/lessons/index.md
```

### Example 2: Documenting a Bug

**User:**
```
I discovered a bug where _CacheMiss sentinel objects were leaking into JSON responses, causing 500 errors. The fix was to sanitize sentinels at the router layer before JSON serialization.
```

**Claude Response:**
```
Checking for duplicates...
[Fetches via fileserver.php URLs]

Found similar: BUG-01 already documents sentinel leak.

**Action:** Updating BUG-01 with your specific case...

[Creates artifact: Updated BUG-01.md]

**Summary:** Enhanced BUG-01 with router-layer fix details. Added performance impact measurement (535ms penalty).

**Note:** Did not create duplicate entry. Updated existing knowledge instead.
```

### Example 3: Recording Design Decision

**User:**
```
We decided to use dictionary dispatch instead of if-else chains for our interface routing because we have 30+ actions and the if-else chain was becoming unmaintainable. Performance testing showed O(1) lookup vs O(n) for if-else.
```

**Claude Response:**
```
Checking duplicates...

Creating new decision entry...

[Creates artifact: DEC-XX-Dictionary-Dispatch.md]

**Summary:** Created DEC-XX documenting dictionary dispatch choice. Genericized to "dispatch pattern for routing with 10+ targets" (removed project specifics).

**Related:** DD-1 architecture (Dictionary Dispatch pattern)
**Cross-referenced:** Performance lessons, routing patterns
```

---

## 🎯 MODE 3: MAINTENANCE MODE

### Purpose
Update indexes, remove outdated entries, verify cross-references

### Activation
```
[Upload File Server URLs.md]
"Start SIMA Maintenance Mode"
```

### Example 1: Updating Indexes After New Entries

**User:**
```
Start SIMA Maintenance Mode

I've added 5 new LESS entries this week. Can you update the lessons index?
```

**Claude Response:**
```
Maintenance Mode activated (30 seconds).

Scanning /sima/entries/lessons/...
[Fetches via fileserver.php URLs]

**Found:** 5 new entries not in index
- LESS-45: Performance profiling workflow
- LESS-46: Cache warming strategies
- LESS-47: WebSocket reconnection patterns
- LESS-48: Token refresh timing
- LESS-49: Memory management in Lambda

Updating index...

[Creates artifact: Updated lessons-index.md]

**Summary:** Added 5 new entries to lessons index. Sorted by category. Verified all REF-IDs valid.

**Statistics:**
- Total lessons: 49
- Categories: 8
- Average per category: 6.1
```

### Example 2: Checking for Outdated Entries

**User:**
```
Can you check for entries that reference deprecated DEC-24?
```

**Claude Response:**
```
Scanning for DEC-24 references...
[Fetches all files via fileserver.php URLs]

**Found:** 12 files referencing DEC-24

**Analysis:**
- DEC-24 described manual cache ID generation
- Superseded by fileserver.php automatic generation (WISD-06)
- References should update to WISD-06

**Action:** Creating update plan...

[Creates artifact: DEC-24-Deprecation-Plan.md]

**Plan includes:**
1. Update 12 files to reference WISD-06
2. Add deprecation notice to DEC-24
3. Redirect DEC-24 → WISD-06
4. Update all affected indexes

**Estimated time:** 15 minutes
```

### Example 3: Verifying Cross-References

**User:**
```
Verify that all BUG entries have valid cross-references to related DEC and AP entries.
```

**Claude Response:**
```
Verifying BUG entry cross-references...

**Checked:** 4 BUG entries
- BUG-01: ✅ All references valid (DEC-05, AP-19)
- BUG-02: ✅ All references valid (INT-01)
- BUG-03: ✅ All references valid (RULE-01, AP-01)
- BUG-04: ❌ References AP-07 (does not exist)

**Issue found:** BUG-04 references non-existent AP-07

**Investigating...**
Should be AP-08 (Module-level imports anti-pattern)

**Fix:** Updating BUG-04 cross-reference

[Creates artifact: Updated BUG-04.md]

**Summary:** Fixed broken cross-reference. All BUG entries now validated.
```

---

## 🎯 MODE 4: PROJECT MODE

### Purpose
Build features for specific projects (LEE or SIMA)

### Activation
```
[Upload File Server URLs.md]
"Start Project Mode for LEE"
```

### Example 1: Adding Feature to LEE

**User:**
```
Start Project Mode for LEE

Add a new action to the lights interface: "toggle" which switches lights between on and off states.
```

**Claude Response:**
```
Project Mode for LEE activated (35 seconds).
LEE-specific context loaded.

Fetching current files...
[Fetches via fileserver.php URLs]
- gateway_wrappers.py
- interface_lights.py
- lights_core.py

Implementing toggle action across all 3 SUGA layers...

[Creates 3 artifacts:]
1. gateway_wrappers.py (complete file, marked with # ADDED: toggle)
2. interface_lights.py (complete file, marked with # ADDED: toggle routing)
3. lights_core.py (complete file, marked with # ADDED: toggle_impl)

**Summary:** Toggle action implemented in all 3 layers. Uses dictionary dispatch pattern (DD-1). Includes error handling and logging.

**Testing:** Deploy and call with action="toggle"
```

### Example 2: Modifying SIMA Structure

**User:**
```
Start Project Mode for SIMA

Create a new directory structure for platform knowledge: /sima/platforms/azure/
```

**Claude Response:**
```
Project Mode for SIMA activated (35 seconds).
SIMA-specific context loaded.

Creating Azure platform structure...

[Creates artifact: Directory-Structure-Azure.md]

**Structure created:**
```
/sima/platforms/azure/
├── functions/
│   ├── lessons/
│   ├── decisions/
│   ├── anti-patterns/
│   └── indexes/
├── app-service/
│   ├── lessons/
│   └── indexes/
└── indexes/
    └── azure-index-main.md
```

**Next steps:**
1. Populate with Azure-specific knowledge
2. Update main platform index
3. Create integration guide
4. Add to knowledge-config.yaml

**Files ready for creation** (scaffolded structure provided).
```

### Example 3: Project Switching

**User:**
```
[Currently in LEE Project Mode]

Actually, I need to work on SIMA documentation now.

Start Project Mode for SIMA
```

**Claude Response:**
```
Switching from LEE to SIMA...

Project Mode for SIMA activated (35 seconds).
SIMA-specific context loaded.

**Context changed:**
- LEE constraints removed (Lambda, 128MB, Home Assistant)
- SIMA constraints loaded (Markdown, 400 lines, Git)
- Architecture switched (code → documentation)
- Workflows updated (build features → create entries)

Ready for SIMA documentation work.
```

---

## 🎯 MODE 5: DEBUG MODE

### Purpose
Troubleshoot issues in specific projects

### Activation
```
[Upload File Server URLs.md]
"Start Debug Mode for LEE"
```

### Example 1: Lambda 500 Error

**User:**
```
Start Debug Mode for LEE

My Lambda function is returning 500 errors with this message:
"Object of type _CacheMiss is not JSON serializable"
```

**Claude Response:**
```
Debug Mode for LEE activated (35 seconds).
LEE-specific bugs loaded.

**Issue identified:** Sentinel leak (BUG-01)

**Root cause:** _CacheMiss sentinel object leaked across router boundary into JSON response.

**Known bug:** BUG-01 documents this exact issue.

**Impact:** 535ms performance penalty on cache misses.

Fetching current router code...
[Fetches via fileserver.php URLs]

Creating fix...

[Creates artifact: router.py with # FIXED: sanitize sentinels]

**Fix applied:**
- Added sentinel sanitization before JSON serialization
- Converts _CacheMiss → None at router boundary
- Includes logging for debugging

**Test:** Deploy and retry failing request.
```

### Example 2: SIMA Broken Reference

**User:**
```
Start Debug Mode for SIMA

I'm getting "Entry not found" when trying to reference ARCH-DD. What's wrong?
```

**Claude Response:**
```
Debug Mode for SIMA activated (35 seconds).
SIMA-specific issues loaded.

**Problem identified:** ARCH-DD reference is ambiguous.

**Background:**
- Old system: Single DD architecture
- New system: DD-1 (Dictionary Dispatch) and DD-2 (Dependency Disciplines)
- ARCH-DD is deprecated

**Fix depends on what you need:**

**If you meant performance routing pattern:**
→ Use DD-1 (Dictionary Dispatch)
→ Location: /sima/languages/python/architectures/dd-1/

**If you meant dependency management:**
→ Use DD-2 (Dependency Disciplines)
→ Location: /sima/languages/python/architectures/dd-2/

**Action:** Update your reference to DD1-XX or DD2-XX as appropriate.

**Related:** Knowledge migration plan v4.2.2 split DD into DD-1 and DD-2.
```

### Example 3: Cold Start Performance

**User:**
```
Start Debug Mode for LEE

Cold start is taking 8 seconds. Target is 3 seconds. Help me find the problem.
```

**Claude Response:**
```
Debug Mode for LEE activated.

**Diagnosing cold start...**

1. Need import profile. Running performance_benchmark.py...
[Simulates profiling results]

**Found:**
- boto3 import: 2.1 seconds (module-level)
- requests import: 1.8 seconds (module-level)
- json import: 0.05 seconds (acceptable)
- Total: 8.3 seconds

**Root cause:** Heavy libraries imported at module level (BUG-04)

**Solution:** Move to lazy imports

Fetching affected files...
[Fetches via fileserver.php URLs]

Creating optimized versions...

[Creates 2 artifacts:]
1. lambda_function.py - boto3 moved to function level
2. http_utils.py - requests moved to function level

**Expected improvement:**
- Before: 8.3 seconds
- After: 0.05 seconds (first request adds 3.9s as needed)
- Target: <3 seconds ✅

**Test:** Deploy and measure cold start time.
```

---

## 🎯 MODE 6: NEW PROJECT MODE

### Purpose
Scaffold structure for new projects

### Activation
```
[Upload File Server URLs.md]
"Start New Project Mode: MyApp"
```

### Example: Creating New Project

**User:**
```
Start New Project Mode: DataPipeline

This will be a Python data processing project using SUGA architecture on AWS Lambda.
```

**Claude Response:**
```
New Project Mode activated (35 seconds).

Creating project: DataPipeline...

**Step 1: Directory structure**

[Creates artifact: Directory structure visualization]

**Step 2: Configuration files**

[Creates artifact: knowledge-config.yaml]
```yaml
project:
  name: DataPipeline
  description: Python data processing with SUGA on AWS Lambda
  version: 0.1.0
  
knowledge:
  generic:
    enabled: true
  platforms:
    aws:
      lambda: true
  languages:
    python:
      enabled: true
      architectures:
        suga: true
        lmms: true
        zaph: false
        dd-1: true
        dd-2: true
        cr-1: false
```

**Step 3: Mode extensions**

[Creates 3 artifacts:]
1. Custom-Instructions-DataPipeline.md (50 lines)
2. PROJECT-MODE-DataPipeline.md (100 lines)
3. DEBUG-MODE-DataPipeline.md (100 lines)

**Step 4: Indexes**

[Creates artifact: datapipeline-index-main.md]

**Step 5: Integration guide**

[Creates artifact: INTEGRATION-GUIDE-DataPipeline.md]

**Project created!**

**Location:** /sima/projects/datapipeline/

**Next steps:**
1. Review and customize generated files
2. Add project-specific constraints to mode extensions
3. Begin development: "Start Project Mode for DataPipeline"
4. Document lessons as you go: "Start SIMA Learning Mode"

**Activation commands:**
- Project work: "Start Project Mode for DataPipeline"
- Debugging: "Start Debug Mode for DataPipeline"
```

---

## 🔄 COMMON WORKFLOWS

### Workflow 1: Typical Development Session

```
# Start
[Upload File Server URLs.md]
"Start Project Mode for LEE"

# Build feature
"Add a new cache warming function for cold starts"
[Claude creates 3 artifacts with complete implementations]

# Test and encounter issue
"Debug Mode for LEE"
"Getting ModuleNotFoundError when calling the new function"
[Claude identifies circular import, provides fix]

# Document the learning
"Start SIMA Learning Mode"
"We discovered that cache warming needs lazy imports to avoid circular dependencies"
[Claude extracts lesson, updates indexes]

# Done
Session complete with feature added, bug fixed, knowledge captured
```

### Workflow 2: Documentation Maintenance

```
# Start
[Upload File Server URLs.md]
"Start SIMA Maintenance Mode"

# Update indexes
"Update all indexes to include entries from the past week"
[Claude scans, finds 8 new entries, updates 5 indexes]

# Check for outdated
"Find entries referencing deprecated patterns"
[Claude identifies 15 entries, creates update plan]

# Verify quality
"Check that all LESS entries have 4-8 keywords"
[Claude validates, reports 3 entries need keyword updates]

# Done
Knowledge base cleaned, organized, validated
```

### Workflow 3: New Project Setup

```
# Start
[Upload File Server URLs.md]
"Start New Project Mode: MobileBackend"

# Scaffold
"Python API backend for mobile app, using SUGA, deployed on AWS Lambda"
[Claude creates complete project structure with configs]

# Review
[Check generated files, customize constraints]

# Begin work
"Start Project Mode for MobileBackend"
"Add user authentication interface"
[Claude implements across all 3 SUGA layers]

# Done
Project created, first feature implemented, ready for development
```

### Workflow 4: Multi-Project Session

```
# Morning: LEE work
[Upload File Server URLs.md]
"Start Project Mode for LEE"
"Add new device integration"
[Work on LEE features]

# Afternoon: SIMA maintenance
"Start SIMA Maintenance Mode"
"Update indexes and check for broken references"
[Maintain knowledge base]

# Evening: New project
"Start New Project Mode: WeatherAPI"
[Create new project structure]

# Done
Worked on 3 different projects in one session
```

---

## 🎓 LEARNING EXAMPLES

### Example 1: First-Time User

**Day 1: Understanding the system**
```
"Please load context"
"What is SUGA architecture?"
"How do I add a feature?"
"Where do I find design decisions?"
```

**Day 2: First feature**
```
"Start Project Mode for LEE"
"Add a new timer interface for scheduled actions"
[Learn by doing]
```

**Day 3: Documenting learning**
```
"Start SIMA Learning Mode"
"Yesterday I learned that timers need special handling in Lambda"
[Capture knowledge for future]
```

### Example 2: Experienced Developer

**Starting new feature:**
```
"Start Project Mode for LEE"
"Add rate limiting to prevent API abuse"

# Claude asks clarifying questions
# Implements across all layers
# Provides complete, tested code
```

**Hitting a bug:**
```
"Debug Mode for LEE"
"Rate limiter causing memory issues"

# Claude checks known bugs
# Profiles memory usage
# Provides optimized solution
```

**Documenting the pattern:**
```
"Start SIMA Learning Mode"
"Rate limiting pattern: Use sliding window counter with cache cleanup"

# Claude extracts generic pattern
# Checks for duplicates
# Creates reusable knowledge
```

---

## 🚀 ADVANCED PATTERNS

### Pattern 1: Rapid Prototyping

```
# Create project
"Start New Project Mode: Prototype2024"

# Immediate development
"Start Project Mode for Prototype2024"
"Add three interfaces: data, transform, output"
[Get complete implementation in minutes]

# Test and iterate
"Debug Mode for Prototype2024"
[Fix issues as they arise]

# Success criteria met
"Start SIMA Learning Mode"
[Document winning patterns for future use]
```

### Pattern 2: Knowledge Mining

```
# Review old conversations
"Start SIMA Learning Mode"

# Extract systematically
"Review my conversations about Lambda optimization"
"Extract all lessons about memory management"
"Document recurring patterns I've discovered"

# Build knowledge base
[Create comprehensive lessons, decisions, patterns]
```

### Pattern 3: Architecture Migration

```
# Plan migration
"Please load context"
"I want to adopt SUGA architecture for existing project"

# Create parallel structure
"Start New Project Mode: LegacyRefactored"

# Systematic refactoring
"Start Project Mode for LegacyRefactored"
"Migrate module X to SUGA pattern"
[Repeat for each module]

# Document migration
"Start SIMA Learning Mode"
"Record migration decisions and lessons"
```

---

## ⚠️ COMMON PITFALLS

### Pitfall 1: Wrong Mode

**Problem:**
```
"Start SIMA Learning Mode"
"Add a new feature to cache interface"
```

**Why wrong:** Learning Mode extracts knowledge, doesn't build features.

**Correct:**
```
"Start Project Mode for LEE"
"Add a new feature to cache interface"
```

### Pitfall 2: Forgetting fileserver.php

**Problem:**
```
"Start Project Mode for LEE"
[Forget to upload File Server URLs.md]
"Modify gateway.py"
```

**Result:** Claude works with week-old cached file.

**Correct:**
```
[Upload File Server URLs.md FIRST]
"Start Project Mode for LEE"
"Modify gateway.py"
```

### Pitfall 3: Mode Mixing

**Problem:**
```
"Start Debug Mode for LEE"
"While we're here, also extract lessons from this bug"
```

**Why wrong:** Debug Mode focuses on fixing. Learning Mode extracts.

**Correct:**
```
"Start Debug Mode for LEE"
[Fix the bug]

"Start SIMA Learning Mode"
[Now extract lessons]
```

### Pitfall 4: Not Specifying Project

**Problem:**
```
"Start Project Mode"
[No project specified]
```

**Result:** Claude doesn't know which project extension to load.

**Correct:**
```
"Start Project Mode for LEE"
or
"Start Project Mode for SIMA"
```

---

## 📊 MODE COMPARISON

### When to Use Each Mode

| Scenario | Correct Mode | Why |
|----------|-------------|-----|
| "How does X work?" | General Mode | Q&A and guidance |
| "Add feature Y" | Project Mode | Building code |
| "Fix error Z" | Debug Mode | Troubleshooting |
| "Document this pattern" | Learning Mode | Extracting knowledge |
| "Update indexes" | Maintenance Mode | Organizing knowledge |
| "Create new project" | New Project Mode | Scaffolding structure |

### Mode Characteristics

| Mode | Load Time | Output Type | Session Focus |
|------|-----------|-------------|---------------|
| General | 20-30s | Answers + REF-IDs | Understanding |
| Learning | 45-60s | Neural map entries | Knowledge capture |
| Maintenance | 30-45s | Updated indexes | Organization |
| Project | 35-50s | Complete code | Implementation |
| Debug | 35-50s | Fixes + analysis | Problem solving |
| New Project | 30-45s | Project structure | Setup |

---

## 🎯 SUCCESS PATTERNS

### Pattern 1: Plan, Build, Document

```
1. "Please load context"
   → Understand requirements and architecture

2. "Start Project Mode for [PROJECT]"
   → Implement features

3. "Start SIMA Learning Mode"
   → Document patterns discovered
```

### Pattern 2: Build, Break, Fix, Learn

```
1. "Start Project Mode for [PROJECT]"
   → Build new feature

2. [Feature has issue]
   "Start Debug Mode for [PROJECT]"
   → Fix the problem

3. "Start SIMA Learning Mode"
   → Document bug and fix
```

### Pattern 3: Regular Maintenance

```
Weekly:
1. "Start SIMA Maintenance Mode"
   → Update indexes

Monthly:
1. "Start SIMA Maintenance Mode"
   → Check for outdated entries
   → Verify cross-references
   → Clean deprecated content
```

---

## 📚 REFERENCE CARD

### Quick Activation Commands

```bash
# General (Q&A)
"Please load context"

# Learning (Extract knowledge)
"Start SIMA Learning Mode"

# Maintenance (Organize)
"Start SIMA Maintenance Mode"

# Project (Build for specific project)
"Start Project Mode for LEE"
"Start Project Mode for SIMA"
"Start Project Mode for [PROJECT]"

# Debug (Fix specific project)
"Start Debug Mode for LEE"
"Start Debug Mode for SIMA"
"Start Debug Mode for [PROJECT]"

# New Project (Create structure)
"Start New Project Mode: [NAME]"
```

### Pre-Session Checklist

```
[ ] Upload File Server URLs.md
[ ] Say activation phrase
[ ] Wait for "ready" confirmation
[ ] Begin work
```

### Mid-Session Switching

```
# Can switch between modes
"Start Debug Mode for LEE"     # Switch from Project to Debug
"Start SIMA Learning Mode"      # Switch to Learning
"Start Maintenance Mode"        # Switch to Maintenance
```

---

## 🔍 TROUBLESHOOTING

### Issue: Mode doesn't activate

**Check:**
1. Did you upload File Server URLs.md?
2. Did you use exact activation phrase?
3. Did you wait for confirmation?

**Solution:** Re-upload file, use exact phrase, wait for "ready"

### Issue: Wrong project context

**Check:**
1. Did you specify project name?
2. Does project extension exist?

**Solution:** Use "Start Project Mode for [PROJECT]" with correct name

### Issue: Outdated file content

**Check:**
1. Was File Server URLs.md uploaded?
2. Did fileserver.php fetch complete?

**Solution:** Re-upload File Server URLs.md, verify fetch

---

## ✅ BEST PRACTICES

1. **Always upload File Server URLs.md first**
2. **Use exact activation phrases**
3. **Wait for mode confirmation**
4. **One mode per task**
5. **Switch modes when needed**
6. **Document learnings regularly**
7. **Maintain knowledge base monthly**
8. **Create projects before building**

---

**END OF USAGE EXAMPLES**

**Version:** 1.0.0  
**Covers:** All 6 modes with practical examples  
**Audience:** All users from beginners to advanced  
**Next:** Use these patterns in real sessions

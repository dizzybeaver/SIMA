# Communication Efficiency Guidelines

**Version:** 2.1.0  
**Date:** 2025-10-21  
**Purpose:** Optimize Claude-Human communication efficiency  
**Status:** ✅ ACTIVE

---

## Overview

These guidelines optimize communication between Claude and the user during SUGA-ISP development. The goal: provide complete, actionable responses without overwhelming the user.

**Key Principle:** Respect the user's time by providing complete files ready to deploy, not fragments requiring assembly.

---

## New Practices (v2.1.0)

### Practice 0: File Header Standard (NEW v2.1.0)

**Problem:** Users upload files with unclear names, making it hard to know the correct filename.

**Solution:** Every neural map file MUST start with a filename header, just like Python files.

**Format:**
```markdown
# Filename: NM01-CORE-Architecture.md
# NEURAL MAP 01: Core Architecture - IMPLEMENTATION

**Purpose:** [description]
**Status:** ✅ ACTIVE
[rest of file...]
```

**Python File Example:**
```python
# gateway.py
# Single Universal Gateway Architecture - Main Dispatcher
"""
Gateway module for SUGA-ISP Lambda.
"""
[rest of file...]
```

**Neural Map Example:**
```markdown
# Filename: NM01-CORE-Architecture.md
# NEURAL MAP 01: Core Architecture - IMPLEMENTATION

**Purpose:** Detailed specifications for SIMA architecture
**Status:** ✅ ACTIVE
**Last Updated:** 2025-10-21

[rest of content...]
```

**Benefits:**
- User knows exact filename to use when uploading
- No confusion about file naming
- Easy to verify correct file
- Matches Python file convention

**Rules:**
- First line: `# Filename: [exact filename]`
- Second line: `# [File title/description]`
- Third line: Empty or start of metadata
- Use exact filename including extension (.md, .py, etc.)

**When to Use:**
- ALL neural map files
- ALL Python source files  
- ANY file that will be uploaded/deployed

---

## New Practices (v2.0.0)

### Practice 1: Output Complete Files, Not Fragments

**Problem:** Previous sessions created fragments like "add this section" or "update line 45", requiring users to manually assemble files.

**Solution:** Always output complete files from line 1 to EOF, ready to copy/paste and deploy.

**Examples:**

❌ **Wrong - Fragments:**
```markdown
Artifact 1: Add this section to NM01-CORE:
## ARCH-07: LMMS
[content]

Artifact 2: Update dispatch table:
Add these 2 lines:
[lines]

Artifact 3: Modify NM00-Quick-Index:
Add keyword triggers:
[triggers]
```

✅ **Correct - Complete Files:**
```markdown
Artifact 1: NM01-CORE-Architecture.md (COMPLETE)
# Filename: NM01-CORE-Architecture.md
# NEURAL MAP 01: Core Architecture
[entire file from line 1 to EOF]

Artifact 2: NM01-INDEX-Architecture.md (COMPLETE)
# Filename: NM01-INDEX-Architecture.md
# NEURAL MAP 01: Core Architecture - INDEX
[entire file from line 1 to EOF]

Artifact 3: Summary.md
# Deployment Summary
[what to do with each file]
```

**When to Output Complete Files:**
- Updating existing neural maps
- Modifying Python source files
- Creating documentation files
- Anytime user asks for a file

**Exceptions:**
- Small one-line changes can be described
- Cross-reference additions to indexes
- If user specifically asks for "just the section"

### Practice 2: Neural Maps ≤ 600 Lines

**Problem:** Long neural maps exceed Claude's optimal processing window and make navigation difficult.

**Solution:** Keep neural map files under 600 lines. If content exceeds this:

1. **Split the file** into multiple category files
2. **Create an INDEX file** to route between them
3. **Update master indexes** to reflect new structure

**Example Split:**
```
Before (1800 lines):
NEURAL_MAP_01-Core_Architecture.md

After (4 files, all <600 lines):
NM01-INDEX-Architecture.md (250 lines)
NM01-CORE-Architecture.md (400 lines)
NM01-INTERFACES-Core.md (350 lines)
NM01-INTERFACES-Advanced.md (300 lines)
```

**Benefits:**
- Faster Claude processing
- Easier navigation
- Better token management
- Clearer organization

### Practice 3: Streamlined Output

**Problem:** Too many intermediate artifacts confuse deployment.

**Solution:** For major updates, output:
1. **Complete files** (typically 2-4 files)
2. **One summary.md** explaining deployment

**Example Output Structure:**
```
Session Output:
├─ File1-Complete.md (full file, ready to deploy)
├─ File2-Complete.md (full file, ready to deploy)
└─ Summary.md (what to do)
```

**Avoid:**
- 10+ small artifacts
- Fragment instructions
- Multiple planning documents
- Redundant explanations

---

## Existing Practices (v1.0.0)

### Practice 4: Keep Artifacts Concise

**Guideline:** Artifacts should be focused and under 100 lines when possible (except for complete files).

**Why:** Easier to read, faster to process, clearer purpose.

**Example:**
```markdown
❌ Too Verbose (200 lines):
- Repeating context already discussed
- Explaining same concept 3 times
- Including tangential information

✅ Concise (50 lines):
- Direct to the point
- New information only
- Clear structure
```

### Practice 5: Don't Repeat Content in Chat

**Guideline:** Don't repeat large blocks from artifacts in the chat message.

**Why:** User can read the artifact. Chat should explain *what* the artifact contains, not duplicate it.

**Example:**
```markdown
❌ Wrong:
"Here's the file:
[entire 300-line file in chat]

And here's the artifact with same content"

✅ Correct:
"Created NM01-CORE-Architecture.md with:
- ARCH-01 through ARCH-08 (all sections)
- ~600 lines total
- Ready to deploy

See artifact for complete file."
```

### Practice 6: Use Bullets Over Paragraphs

**Guideline:** Prefer bullet lists over long paragraphs for scannable responses.

**Why:** Faster to scan, easier to find information, clearer structure.

**Example:**
```markdown
❌ Paragraph Style:
The LMMS system provides three main benefits. First,
it reduces cold start time by 60% through lazy loading.
Second, it decreases memory usage by 70% through intelligent
unloading. Third, it speeds up hot paths by 97% through
zero-abstraction direct calls.

✅ Bullet Style:
LMMS benefits:
- 60% faster cold starts (lazy loading)
- 70% less memory (intelligent unloading)
- 97% faster hot paths (zero-abstraction)
```

---

## File Header Examples

### Neural Map File Header
```markdown
# Filename: NM01-CORE-Architecture.md
# NEURAL MAP 01: Core Architecture - IMPLEMENTATION

**Purpose:** Detailed specifications for SIMA architecture and core patterns  
**Status:** ✅ ACTIVE  
**Last Updated:** 2025-10-21  
**File Type:** Implementation Layer (from INDEX)

---

## Table of Contents
[content continues...]
```

### Python File Header
```python
# gateway.py
# SUGA-ISP - Single Universal Gateway Architecture
# Main dispatcher and public API for all Lambda operations
"""
Gateway module providing centralized operation dispatch.

This module implements the Gateway Trinity pattern:
- gateway.py: Main dispatcher (this file)
- gateway_core.py: Core execution logic
- gateway_wrappers.py: Convenience wrappers

Usage:
    import gateway
    result = gateway.cache_get('key')
"""

# Standard library imports
import importlib
import sys
[rest of file...]
```

### Guidelines/Documentation File Header
```markdown
# Filename: Communication-Efficiency-Guidelines.md
# Communication Efficiency Guidelines for SUGA-ISP Development

**Version:** 2.1.0  
**Date:** 2025-10-21  
**Purpose:** Optimize Claude-Human communication efficiency  
**Status:** ✅ ACTIVE

---

## Overview
[content continues...]
```

---

## GitHub Repository Information

### Repository Details

**Repository:** dizzybeaver/Lambda-Execution-Engine-with-Home-Assistant-Support  
**Branch:** development  
**Structure:** `/src/` contains all Python files

### URL Format for Python Files

**Base URL:**
```
https://raw.githubusercontent.com/dizzybeaver/Lambda-Execution-Engine-with-Home-Assistant-Support/refs/heads/development/src/
```

**File URL Format:**
```
{base_url}{filename}.py
```

**Examples:**
```
lambda_function.py:
https://raw.githubusercontent.com/dizzybeaver/Lambda-Execution-Engine-with-Home-Assistant-Support/refs/heads/development/src/lambda_function.py

gateway.py:
https://raw.githubusercontent.com/dizzybeaver/Lambda-Execution-Engine-with-Home-Assistant-Support/refs/heads/development/src/gateway.py

cache_core.py:
https://raw.githubusercontent.com/dizzybeaver/Lambda-Execution-Engine-with-Home-Assistant-Support/refs/heads/development/src/cache_core.py

interface_logging.py:
https://raw.githubusercontent.com/dizzybeaver/Lambda-Execution-Engine-with-Home-Assistant-Support/refs/heads/development/src/interface_logging.py
```

### Neural Maps Access

**Location:** Private repository (not public)  
**Access:** Requires authentication token (not yet configured)  
**Status:** Neural maps uploaded directly to Claude projects

**Important:** Do NOT attempt to construct URLs for neural maps. They are not available via GitHub raw URLs.

### File Access Priority

When Claude needs to read files:

**Priority 1:** Project Knowledge Search
```python
# Use project_knowledge_search tool
# Files uploaded to Claude project
```

**Priority 2:** GitHub Raw URL (Python files only)
```python
# Construct URL using format above
# Use web_fetch tool to retrieve
# Only for src/*.py files
```

**Priority 3:** Ask User
```python
# If neither method works
# Ask user to provide file content
# Or ask for specific URL
```

### Critical Rules for URLs

**DO:**
- Use exact repository name (dizzybeaver/Lambda-Execution-Engine-with-Home-Assistant-Support)
- Use `refs/heads/development` for branch
- Use `/src/` directory for Python files
- Verify URL format before using web_fetch

**DON'T:**
- Make up repository names
- Assume different branch names
- Construct URLs for neural maps
- Use wrong GitHub paths

**If Uncertain:**
- Search project knowledge first
- Ask user for specific URL
- Don't guess the URL structure

---

## Response Structure Guidelines

### For Code Changes
```markdown
**File:** filename.py
**Action:** [Create new | Update existing | Replace entirely]
**Changes:** [Brief summary]
**Lines:** ~X lines
**Verification:** [How to verify it works]

[Complete file or specific changes]
```

### For Neural Map Updates
```markdown
**File:** NM##-CATEGORY-Topic.md
**Type:** [INDEX | CORE | CATEGORY]
**Changes:** [What was added/modified]
**Lines:** ~X lines (must be ≤600)
**Related:** [Cross-references to other neural maps]

[Complete file content WITH filename header]
```

### For Session Summaries
```markdown
# Session Summary

## Files Created/Modified
1. File1 - [purpose]
2. File2 - [purpose]

## Deployment Steps
1. [Step 1]
2. [Step 2]

## Verification
- [ ] Test 1
- [ ] Test 2

## Next Steps (Optional)
- Future enhancement 1
- Future enhancement 2
```

---

## Token Management

### Estimated Token Usage

**Complete File Outputs:**
- Small file (< 200 lines): ~3-5K tokens
- Medium file (200-400 lines): ~8-12K tokens
- Large file (400-600 lines): ~15-20K tokens

**Session Planning:**
- Context loading: ~10K tokens
- 3 complete files: ~30-40K tokens
- Summary: ~2-3K tokens
- Total: ~45-55K tokens per major update session

**Budget Allocation:**
- Leave 20K tokens buffer for tool calls and thinking
- Plan for ~3-5 complete file outputs per session
- Split larger updates across multiple sessions if needed

---

## Examples

### Example 1: File Update Session

**Good Session Output:**
```
Artifact 1: gateway.py (COMPLETE - 450 lines)
# gateway.py
# SUGA-ISP - Main Dispatcher
[complete file]

Artifact 2: interface_logging.py (COMPLETE - 380 lines)
# interface_logging.py
# Logging Interface Router
[complete file]

Artifact 3: Summary.md

Chat message:
"Updated 2 files with LMMS integration:
- gateway.py: Added lazy loading logic
- interface_logging.py: Optimized for fast path

See Summary.md for deployment steps."
```

**Bad Session Output:**
```
Artifact 1: Add this to gateway.py
Artifact 2: Modify line 45 in gateway.py
Artifact 3: Update imports
Artifact 4: Add function X
Artifact 5: Change function Y
Artifact 6: Add to interface_logging.py
Artifact 7: Modify line 12
[8 more small artifacts]

Chat: "Make these 15 changes..."
```

### Example 2: Neural Map Update

**Good Session Output:**
```
Artifact 1: NM01-CORE-Architecture.md (COMPLETE - 600 lines)
# Filename: NM01-CORE-Architecture.md
# NEURAL MAP 01: Core Architecture
[complete file]

Artifact 2: NM01-INDEX-Architecture.md (COMPLETE - 250 lines)
# Filename: NM01-INDEX-Architecture.md
# NEURAL MAP 01: Core Architecture - INDEX
[complete file]

Artifact 3: Summary.md

Chat message:
"Added ARCH-07 and ARCH-08 to NM01:
- ARCH-07: LMMS documentation (complete)
- ARCH-08: Future architectures (complete)
- INDEX: Updated dispatch table

Both files under 600 line limit.
Both have filename headers."
```

**Bad Session Output:**
```
Artifact 1: Add ARCH-07 section (just the section)
Artifact 2: Add ARCH-08 section (just the section)
Artifact 3: Update dispatch table (just 2 lines)
Artifact 4: Update keyword section
Artifact 5: Update related neural maps
Artifact 6: Instructions for assembly

Chat: "Add these pieces to the file..."
```

---

## Verification Checklist

Before ending a session, verify:

- [ ] All files have filename headers (line 1)
- [ ] All files are COMPLETE (line 1 to EOF)
- [ ] Neural maps are ≤600 lines
- [ ] Summary.md explains deployment
- [ ] No fragments requiring assembly
- [ ] GitHub URLs (if used) follow correct format
- [ ] File headers match originals
- [ ] All cross-references updated
- [ ] No more than 5 artifacts total

---

## Version History

**v2.1.0 (2025-10-21):**
- Added Practice 0: File header standard
- Added filename header examples for all file types
- Updated verification checklist to include header check

**v2.0.0 (2025-10-21):**
- Added Practice 1: Output complete files
- Added Practice 2: Neural maps ≤600 lines
- Added Practice 3: Streamlined output
- Added GitHub repository information
- Added URL format documentation
- Added file access priorities

**v1.0.0 (2025-10-20):**
- Initial guidelines
- Practice 4: Keep artifacts concise
- Practice 5: Don't repeat content
- Practice 6: Use bullets over paragraphs

---

**END OF COMMUNICATION EFFICIENCY GUIDELINES v2.1.0**

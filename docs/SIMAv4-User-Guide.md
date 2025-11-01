# File: SIMAv4-User-Guide.md

**Version:** 1.0.0  
**Date:** 2025-10-29  
**Purpose:** Comprehensive user guide for SIMAv4 system  
**Audience:** All users (developers, architects, learners)  
**Status:** Production Ready

---

## 📖 TABLE OF CONTENTS

1. [Introduction](#introduction)
2. [Getting Started](#getting-started)
3. [Core Concepts](#core-concepts)
4. [Using the System](#using-the-system)
5. [Mode-Based Context System](#mode-based-context-system)
6. [Working with Entries](#working-with-entries)
7. [Search and Navigation](#search-and-navigation)
8. [Support Tools](#support-tools)
9. [Best Practices](#best-practices)
10. [Troubleshooting](#troubleshooting)
11. [FAQs](#faqs)

---

## 🎯 INTRODUCTION

### What is SIMAv4?

SIMAv4 (Structured Intelligence Memory Architecture v4) is a comprehensive knowledge management system designed to document, organize, and retrieve architectural patterns, design decisions, and lessons learned from software development projects.

**Key Features:**
- Hierarchical knowledge organization (Gateway → Category → Topic → Individual)
- Multi-project support with clean separation
- Mode-based context loading for optimal AI assistant interaction
- Cross-referenced entries with REF-IDs
- Generic base patterns + project-specific implementations
- Comprehensive search and navigation tools

### Who Should Use This Guide?

- **Developers:** Building features, debugging issues, understanding architecture
- **Architects:** Making design decisions, documenting patterns
- **Learners:** Understanding SUGA, LMMS, DD, and ZAPH architectures
- **Team Leads:** Onboarding new team members, maintaining knowledge base

### System Requirements

- Access to Claude AI (Sonnet 4.5 or higher recommended)
- File Server URLs document
- Web_fetch capability for loading context
- Project knowledge search enabled

---

## 🚀 GETTING STARTED

### Quick Start (5 Minutes)

1. **Prepare Your Session**
   ```
   - Upload File Server URLs.md
   - Have your specific question or task ready
   - Determine which mode you need
   ```

2. **Choose Your Mode**
   - General questions? → "Please load context"
   - Document knowledge? → "Start SIMA Learning Mode"
   - Write code? → "Start Project Work Mode"
   - Debug issues? → "Start Debug Mode"

3. **Activate Mode**
   - Say the exact activation phrase
   - Wait for context to load (30-60 seconds)
   - Begin working

### First-Time Setup

**Step 1: Understand the Structure**
```
sima/
├── entries/          # Base patterns (generic)
│   ├── core/        # Architecture patterns
│   ├── gateways/    # Gateway patterns
│   ├── interfaces/  # Interface patterns
│   └── languages/   # Language-specific patterns
├── nmp/             # Project-specific patterns
├── support/         # Tools and workflows
└── integration/     # System integration docs
```

**Step 2: Get Familiar with REF-IDs**
- **ARCH-##**: Architecture patterns (ARCH-01, ARCH-02, etc.)
- **GATE-##**: Gateway patterns (GATE-01, GATE-02, etc.)
- **INT-##**: Interface patterns (INT-01 through INT-12)
- **LANG-PY-##**: Python language patterns
- **NMP##-PROJECT-##**: Project-specific patterns

**Step 3: Review Quick References**
- Read `/sima/support/quick-reference/QRC-01-Interfaces-Overview.md`
- Print `/sima/support/quick-reference/QRC-02-Gateway-Patterns.md`
- Keep `/sima/support/quick-reference/QRC-03-Common-Patterns.md` handy

---

## 🧠 CORE CONCEPTS

### The SIMA Hierarchy

SIMAv4 uses a 4-level hierarchy for organizing knowledge:

```
LEVEL 1: Gateway (NM##)
   Examples: NM01 (Architecture), NM02 (Dependencies)
   Purpose: High-level categorization

LEVEL 2: Category
   Examples: CoreArchitecture, InterfacesCore
   Purpose: Group related topics

LEVEL 3: Topic
   Examples: SUGA-Pattern, Cache-Interface
   Purpose: Specific subject areas

LEVEL 4: Individual Entry
   Examples: ARCH-01, INT-01, LESS-15
   Purpose: Atomic knowledge units
```

### Key Terminology

**SUGA vs SIMA (CRITICAL)**

- **SUGA** = Lambda gateway architecture pattern
  - 3 layers: Gateway → Interface → Core
  - Used in code structure
  - Import pattern: gateway.py routes to interface_*.py routes to *_core.py

- **SIMA** = Documentation system (this system!)
  - 4 levels: Gateway → Category → Topic → Individual
  - Used in neural maps
  - File structure: `/sima/entries/*/...`

**Never confuse these!**

### Entry Types

1. **Architecture Entries (ARCH-##)**
   - Define architectural patterns
   - Generic, project-agnostic
   - Examples: SUGA, LMMS, DD, ZAPH

2. **Gateway Patterns (GATE-##)**
   - Define gateway layer patterns
   - Three-file structure, lazy loading, wrappers
   - Examples: GATE-01, GATE-02

3. **Interface Patterns (INT-##)**
   - Define interface layer patterns
   - 12 core interfaces
   - Examples: INT-01 (Cache), INT-06 (Logging)

4. **Language Patterns (LANG-PY-##)**
   - Language-specific best practices
   - Python: PEP 8, idioms, performance
   - Examples: LANG-PY-03 (Exception Handling)

5. **Project NMPs (NMP##-PROJECT-##)**
   - Project-specific implementations
   - Extends base patterns
   - Examples: NMP01-LEE-02 (Cache Functions)

### Cross-References

Every entry can reference other entries using REF-IDs:

```markdown
**Inherits From:** ARCH-01 (SUGA Pattern)
**Related To:** INT-01 (Cache Interface), GATE-01 (Three-File Structure)
**Used In:** NMP01-LEE-02 (Project Cache Implementation)
```

This creates a knowledge graph for navigation and understanding dependencies.

---

## 💻 USING THE SYSTEM

### Mode-Based Workflow

SIMAv4 uses 4 distinct modes, each with specific context and behaviors:

#### Mode 1: General Purpose Mode

**When to Use:**
- Asking architecture questions
- Understanding patterns
- Learning about the system
- Getting guidance

**Activation:**
```
"Please load context"
```

**What You Get:**
- Access to all base patterns (ARCH, GATE, INT, LANG)
- Top 10 instant answers
- Workflow routing
- Quick reference access
- Cross-reference navigation

**Example Session:**
```
You: "Please load context"
Claude: ✅ General Purpose Mode loaded. I have access to SUGA 
        architecture patterns, 12 interfaces, and workflow routing.
        What would you like to know?

You: "Explain the SUGA pattern"
Claude: [Searches project_knowledge for ARCH-01]
        [Provides explanation with REF-ID citations]
```

#### Mode 2: Learning Mode

**When to Use:**
- Documenting lessons learned
- Creating new neural map entries
- Extracting patterns from experiences
- Building knowledge base

**Activation:**
```
"Start SIMA Learning Mode"
```

**What You Get:**
- Entry creation templates
- Duplication checking
- Genericization guidelines
- Cross-reference tools
- Index update procedures

**Example Session:**
```
You: "Start SIMA Learning Mode"
Claude: ✅ Learning Mode loaded. Ready to extract knowledge.
        I'll check for duplicates, genericize content, and 
        create concise neural map entries. What would you 
        like to document?

You: "Document this lesson about Lambda cold starts"
Claude: [Checks for existing entries on cold starts]
        [Creates LESS-## entry with proper structure]
        [Updates cross-references and indexes]
```

#### Mode 3: Project Work Mode

**When to Use:**
- Implementing features
- Writing code
- Creating new modules
- Modifying existing code

**Activation:**
```
"Start Project Work Mode"
```

**What You Get:**
- Code implementation templates
- LESS-15 verification (5-step checklist)
- Complete file artifacts (never fragments)
- All 3 SUGA layers implementation
- Anti-pattern checking

**Example Session:**
```
You: "Start Project Work Mode"
Claude: ✅ Project Mode loaded. Ready for active development.
        I'll fetch current files, implement all 3 SUGA layers,
        and verify with LESS-15. What feature are we building?

You: "Add a new cache operation"
Claude: [Fetches current cache files]
        [Implements Gateway → Interface → Core]
        [Creates complete file artifacts]
        [Verifies with LESS-15]
```

#### Mode 4: Debug Mode

**When to Use:**
- Troubleshooting errors
- Finding root causes
- Diagnosing issues
- Performance problems

**Activation:**
```
"Start Debug Mode"
```

**What You Get:**
- Known bugs database (BUG-01 to BUG-04)
- Systematic investigation workflows
- Layer tracing procedures
- Root cause analysis
- Fix + prevention recommendations

**Example Session:**
```
You: "Start Debug Mode"
Claude: ✅ Debug Mode loaded. I'll check known bugs first,
        trace through layers, and find root causes systematically.
        What's the issue you're seeing?

You: "Lambda is timing out on initialization"
Claude: [Checks BUG-01: Cold Start Issues]
        [Traces through initialization interface]
        [Identifies root cause]
        [Provides fix + prevention]
```

### Switching Modes

**Important:** One mode per session.

To switch modes:
1. End current session
2. Start new session
3. Provide new activation phrase
4. New mode context loads

**Never mix modes** - causes context confusion and reduces effectiveness.

---

## 📚 WORKING WITH ENTRIES

### Reading Entries

Every entry has standard sections:

```markdown
# File: ENTRY-ID-Description.md

**REF-ID:** ENTRY-ID
**Category:** Gateway/Category/Topic
**Version:** X.Y.Z
**Inherits From:** Other entries
**Related To:** Other entries

## Overview
[What this entry covers]

## Core Content
[Main information]

## Cross-References
[Links to related entries]

## Examples
[Practical examples]
```

### Creating New Entries

Use Learning Mode with these steps:

1. **Check for Duplicates**
   ```
   "Search for existing entries on [topic]"
   ```

2. **Determine Entry Type**
   - Architecture pattern? → ARCH-##
   - Gateway pattern? → GATE-##
   - Interface pattern? → INT-##
   - Language pattern? → LANG-PY-##
   - Project-specific? → NMP##-PROJECT-##

3. **Use Template**
   - Get template from `/sima/projects/templates/`
   - Fill in all required sections
   - Keep it concise (< 200 lines)

4. **Genericize Content**
   - Remove project-specific details (unless NMP)
   - Use placeholder names
   - Focus on reusable patterns

5. **Add Cross-References**
   - Link to parent patterns (Inherits From)
   - Link to related concepts (Related To)
   - Link to implementations (Used In)

6. **Update Indexes**
   - Add to appropriate cross-reference matrix
   - Add to quick index
   - Update parent category index

### Updating Entries

When updating existing entries:

1. **Fetch Current Version**
   ```
   "Show me the current version of ARCH-01"
   ```

2. **Make Changes**
   - Update content sections
   - Preserve structure
   - Increment version number

3. **Update Cross-References**
   - Add new references
   - Remove obsolete ones
   - Update matrices

4. **Document Changes**
   - Add to version history section
   - Note what changed and why

---

## 🔍 SEARCH AND NAVIGATION

### Using REF-ID Lookup

**Tool:** `/sima/support/tools/Tool-01-REF-ID-Lookup.md`

Find entries by their REF-ID:

```
"Find ARCH-01"
"Show me INT-06"
"What is GATE-03 about?"
```

### Keyword Search

**Tool:** `/sima/support/tools/Tool-02-Keyword-Search-Guide.md`

Search by keywords:

```
"Find entries about caching"
"Search for exception handling"
"Show patterns related to Lambda"
```

### Cross-Reference Navigation

Follow relationships between entries:

```
"What does ARCH-01 inherit from?"
"Show me all entries related to INT-01"
"What projects use GATE-02?"
```

### Quick Indexes

Use quick indexes for problem-based lookup:

```
Gateway Quick Index:
- "How do I structure gateway files?" → GATE-01
- "How to optimize imports?" → GATE-02
- "Cross-interface communication?" → GATE-03

Interface Quick Index:
- "Need caching?" → INT-01
- "Need logging?" → INT-06
- "Need security?" → INT-08
```

---

## 🛠️ SUPPORT TOOLS

### Workflow Templates (5)

Located in `/sima/support/workflows/`:

1. **Workflow-01-Add-Feature.md**
   - Use when: Implementing new functionality
   - Steps: Plan → Fetch → Implement → Verify → Document

2. **Workflow-02-Debug-Issue.md**
   - Use when: Troubleshooting problems
   - Steps: Reproduce → Check Known → Trace → Fix → Prevent

3. **Workflow-03-Update-Interface.md**
   - Use when: Modifying interface functions
   - Steps: Check Dependencies → Update All Layers → Verify → Document

4. **Workflow-04-Add-Gateway-Function.md**
   - Use when: Adding gateway wrapper
   - Steps: Plan → Interface First → Gateway Wrapper → Test → Document

5. **Workflow-05-Create-NMP-Entry.md**
   - Use when: Documenting project-specific patterns
   - Steps: Check Duplicates → Create Entry → Cross-Reference → Index

### Verification Checklists (3)

Located in `/sima/support/checklists/`:

1. **Checklist-01-Code-Review.md**
   - Use when: Reviewing code changes
   - Checks: SUGA compliance, imports, error handling, documentation

2. **Checklist-02-Deployment-Readiness.md**
   - Use when: Preparing for deployment
   - Checks: Tests pass, docs updated, dependencies verified

3. **Checklist-03-Documentation-Quality.md**
   - Use when: Creating/updating documentation
   - Checks: Completeness, accuracy, cross-references, examples

### Quick Reference Cards (3)

Located in `/sima/support/quick-reference/`:

1. **QRC-01-Interfaces-Overview.md**
   - All 12 interfaces
   - Dependency layers (L0-L4)
   - Common use cases

2. **QRC-02-Gateway-Patterns.md**
   - 5 gateway patterns
   - When to use each
   - Implementation guidelines

3. **QRC-03-Common-Patterns.md**
   - Most frequently used patterns
   - Quick decision matrix
   - Anti-patterns to avoid

### Migration Utility

Located in `/sima/support/utilities/`:

**Utility-01-NM-to-NMP-Migration.md**
- Use when: Converting old NM entries to new NMP format
- Steps: Extract, genericize, create NMP, validate

---

## ✅ BEST PRACTICES

### The 4 Golden Rules

1. **Search Neural Maps FIRST**
   - Always check existing entries before asking
   - Use project_knowledge_search
   - Follow cross-references

2. **Read COMPLETE Sections**
   - Never skim entries
   - Follow all cross-references
   - Understand context

3. **Always Cite REF-IDs**
   - Make answers verifiable
   - Enable knowledge tracing
   - Support future updates

4. **Verify Before Suggesting**
   - Use mode-specific checklists
   - Check anti-patterns
   - Test recommendations

### Anti-Patterns to Avoid

**Never suggest:**
- Threading locks (Lambda is single-threaded)
- Direct core imports (always via gateway)
- Bare except clauses (use specific exceptions)
- Sentinel objects crossing boundaries
- Heavy libraries without justification (128MB limit)
- New subdirectories (flat structure except home_assistant/)
- Skipping verification checklists

### Entry Creation Guidelines

**Keep entries:**
- Concise (< 200 lines)
- Generic (unless NMP)
- Well-cross-referenced
- Example-rich
- Version-tracked

**Avoid:**
- Project-specific details in base entries
- Duplication of existing content
- Missing cross-references
- Incomplete examples
- Poor version history

### Code Artifact Rules

**Always use artifacts for:**
- Any code snippet > 20 lines
- Any file modification
- New file creation
- Configuration files

**Always output:**
- Complete files (never fragments)
- All existing code + modifications
- Changes marked with comments (# ADDED:, # MODIFIED:)
- Deployable, copy-paste ready code

**Never output:**
- Code in chat (always artifacts)
- Partial snippets
- "Add this to line X" instructions
- Fragments or excerpts

---

## 🐛 TROUBLESHOOTING

### Common Issues

#### Issue: Mode won't load

**Symptoms:** Context loading fails or times out

**Solutions:**
1. Check File Server URLs.md is uploaded
2. Verify activation phrase is exact
3. Try again (may be temporary)
4. Check internet connection

#### Issue: Can't find an entry

**Symptoms:** Search returns no results

**Solutions:**
1. Try different keywords
2. Use REF-ID if you know it
3. Check cross-reference matrices
4. Use quick indexes for problem-based lookup

#### Issue: Getting mixed mode behaviors

**Symptoms:** Responses don't match expected mode

**Solutions:**
1. End current session
2. Start fresh session
3. Use exact activation phrase
4. Don't mix mode requests

#### Issue: Code output in chat instead of artifacts

**Symptoms:** Code appears in chat text

**Solutions:**
1. Remind: "Please use artifacts for code"
2. Request complete file (not fragment)
3. Report if persistent (this shouldn't happen)

### Getting Help

**In Session:**
```
"I need help with [specific issue]"
"Show me examples of [pattern]"
"What's the workflow for [task]?"
```

**Between Sessions:**
- Review this guide
- Check quick reference cards
- Read workflow templates
- Review E2E examples

---

## ❓ FAQs

### General Questions

**Q: What's the difference between SUGA and SIMA?**

A: SUGA is the Lambda architecture pattern (Gateway → Interface → Core layers in code). SIMA is this documentation system (Gateway → Category → Topic → Individual hierarchy for neural maps). They're different systems with similar names!

**Q: Do I need to use modes?**

A: Yes, modes optimize context loading and behavior for specific tasks. Always activate a mode at the start of each session.

**Q: Can I switch modes mid-session?**

A: No, one mode per session. End current session and start new one to switch modes.

**Q: How do I know which mode to use?**

A: Quick guide:
- Learning about system? → General Mode
- Documenting knowledge? → Learning Mode
- Writing code? → Project Mode
- Debugging? → Debug Mode

### Technical Questions

**Q: Why do some entries have "Inherits From"?**

A: Entries form a hierarchy. Child entries inherit concepts from parent entries and add specifics. This avoids duplication and maintains consistency.

**Q: What's the difference between NM and NMP?**

A: NM entries are generic base patterns (in `/sima/entries/`). NMP entries are project-specific implementations (in `/sima/nmp/`). NMPs extend NM patterns for specific projects.

**Q: Why are there so many cross-references?**

A: Cross-references create a knowledge graph, enabling navigation and understanding relationships. They're essential for maintaining consistency and finding related information.

**Q: What's LESS-15?**

A: LESS-15 is a 5-step verification checklist used in Project Mode to ensure code quality before suggesting implementations:
1. Fetch current files first
2. Verify all 3 SUGA layers
3. Check anti-patterns
4. Verify imports
5. Test implementation

### Workflow Questions

**Q: When should I create a new entry vs. update existing?**

A: Create new entry if:
- Topic is distinct from existing entries
- Pattern is significantly different
- Project-specific (NMP) implementation

Update existing if:
- Improving existing content
- Fixing errors
- Adding examples
- Clarifying concepts

**Q: How do I know if I'm duplicating content?**

A: Use Learning Mode and search thoroughly:
```
"Search for entries about [topic]"
"Check if [concept] is already documented"
```

**Q: What if I can't find a pattern I need?**

A: Consider creating it:
1. Use Learning Mode
2. Check for similar patterns
3. Determine appropriate entry type
4. Use template
5. Document thoroughly

---

## 📊 APPENDIX

### Entry Type Reference

| Type | Format | Location | Purpose |
|------|--------|----------|---------|
| Architecture | ARCH-## | `/sima/entries/core/` | Core patterns |
| Gateway | GATE-## | `/sima/entries/gateways/` | Gateway patterns |
| Interface | INT-## | `/sima/entries/interfaces/` | Interface patterns |
| Language | LANG-PY-## | `/sima/entries/languages/python/` | Python patterns |
| Project | NMP##-PROJECT-## | `/sima/nmp/` | Project-specific |
| Workflow | Workflow-## | `/sima/support/workflows/` | Process templates |
| Checklist | Checklist-## | `/sima/support/checklists/` | Verification |
| Tool | Tool-## | `/sima/support/tools/` | Utilities |
| QRC | QRC-## | `/sima/support/quick-reference/` | Quick refs |

### Mode Activation Reference

| Mode | Phrase | Use Case | Duration |
|------|--------|----------|----------|
| General | "Please load context" | Questions, learning | Any |
| Learning | "Start SIMA Learning Mode" | Document knowledge | Any |
| Project | "Start Project Work Mode" | Write code | Any |
| Debug | "Start Debug Mode" | Fix issues | Any |

### Support Tool Reference

| Tool | Location | Use Case |
|------|----------|----------|
| Workflow-01 | `/workflows/` | Add feature |
| Workflow-02 | `/workflows/` | Debug issue |
| Workflow-03 | `/workflows/` | Update interface |
| Workflow-04 | `/workflows/` | Add gateway function |
| Workflow-05 | `/workflows/` | Create NMP entry |
| Checklist-01 | `/checklists/` | Code review |
| Checklist-02 | `/checklists/` | Deployment readiness |
| Checklist-03 | `/checklists/` | Documentation quality |
| Tool-01 | `/tools/` | REF-ID lookup |
| Tool-02 | `/tools/` | Keyword search |
| QRC-01 | `/quick-reference/` | Interface overview |
| QRC-02 | `/quick-reference/` | Gateway patterns |
| QRC-03 | `/quick-reference/` | Common patterns |

---

## 📝 VERSION HISTORY

**v1.0.0 (2025-10-29)**
- Initial comprehensive user guide
- All 11 sections complete
- Mode-based workflow documentation
- Support tools reference
- Best practices and troubleshooting
- FAQs and appendixes

---

**END OF USER GUIDE**

**Status:** Production Ready  
**Audience:** All SIMAv4 users  
**Maintenance:** Update with new features and learnings  
**Feedback:** Welcome via session interactions

For additional help, use mode-based context and support tools.

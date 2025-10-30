# File: SIMAv4-Developer-Guide.md

**Version:** 1.0.0  
**Date:** 2025-10-29  
**Purpose:** Developer guide and API documentation for SIMAv4  
**Audience:** Developers, system architects, contributors  
**Status:** Production Ready

---

## 📖 TABLE OF CONTENTS

1. [Introduction](#introduction)
2. [System Architecture](#system-architecture)
3. [File Structure](#file-structure)
4. [Entry Format Specification](#entry-format-specification)
5. [REF-ID System](#ref-id-system)
6. [Cross-Reference System](#cross-reference-system)
7. [Mode System API](#mode-system-api)
8. [Project Knowledge Search API](#project-knowledge-search-api)
9. [Support Tools API](#support-tools-api)
10. [Creating Extensions](#creating-extensions)
11. [Validation and Testing](#validation-and-testing)
12. [Contributing Guidelines](#contributing-guidelines)

---

## 🎯 INTRODUCTION

### Purpose

This guide provides technical documentation for developers working with SIMAv4, including system architecture, APIs, and extension development.

### Scope

**Covered:**
- System architecture and design
- File formats and specifications
- REF-ID and cross-reference systems
- Mode system implementation
- Search and navigation APIs
- Extension development
- Validation tools

**Not Covered:**
- User workflows (see User Guide)
- Basic usage (see User Guide)
- Project-specific implementations (see project NMPs)

### Prerequisites

- Understanding of markdown syntax
- Familiarity with knowledge management systems
- Experience with Claude AI
- Basic Python knowledge (for validation tools)

---

## 🏗️ SYSTEM ARCHITECTURE

### Overview

SIMAv4 uses a hierarchical knowledge graph with cross-referenced entries, mode-based context loading, and multi-project support.

```
┌─────────────────────────────────────────────┐
│          User Interface (Claude AI)         │
└─────────────────────┬───────────────────────┘
                      │
┌─────────────────────▼───────────────────────┐
│            Mode System (4 modes)            │
│  General │ Learning │ Project │ Debug      │
└─────────────────────┬───────────────────────┘
                      │
┌─────────────────────▼───────────────────────┐
│      Project Knowledge Search API           │
└─────────────────────┬───────────────────────┘
                      │
┌─────────────────────▼───────────────────────┐
│          Entry System (NM/NMP)              │
│    ┌──────────┬──────────┬──────────┐      │
│    │  Base    │ Project  │ Support  │      │
│    │ Entries  │  NMPs    │  Tools   │      │
│    └──────────┴──────────┴──────────┘      │
└─────────────────────┬───────────────────────┘
                      │
┌─────────────────────▼───────────────────────┐
│       Cross-Reference & Index System        │
└─────────────────────────────────────────────┘
```

### Component Architecture

#### 1. Mode System

**Purpose:** Load appropriate context for different tasks

**Components:**
- Mode Selector (Custom Instructions)
- Mode Context Files (4 files)
- Activation Phrase Parser
- Context Loader

**Flow:**
```
User Input → Parse Activation → Identify Mode → Load Context → Activate Behaviors
```

#### 2. Entry System

**Purpose:** Store and organize knowledge

**Components:**
- Base Entries (NM): Generic patterns
- Project NMPs: Project-specific implementations
- Cross-Reference Matrices: Entry relationships
- Quick Indexes: Problem-based lookups

**Hierarchy:**
```
Gateway (NM##)
  └─ Category
      └─ Topic
          └─ Individual Entry (REF-ID)
```

#### 3. Search System

**Purpose:** Find and retrieve entries

**Components:**
- Project Knowledge Search: Semantic search
- REF-ID Lookup: Direct entry access
- Keyword Search: Fuzzy matching
- Cross-Reference Navigator: Relationship traversal

#### 4. Support Tools

**Purpose:** Aid development workflows

**Components:**
- Workflows: Process templates
- Checklists: Verification procedures
- Quick Reference Cards: Rapid lookups
- Utilities: Helper tools

---

## 📁 FILE STRUCTURE

### Directory Organization

```
sima/
├── planning/                          # Project management
│   └── SIMAv4-Master-Control-Implementation.md
│
├── projects/                          # Multi-project support
│   ├── projects_config.md            # Project registry
│   ├── templates/                    # Entry templates (9)
│   ├── tools/                        # Web tools (2)
│   └── {PROJECT}/                    # Project-specific dirs
│
├── entries/                          # Base knowledge entries
│   ├── core/                         # Architecture patterns (6)
│   ├── gateways/                     # Gateway patterns (7)
│   ├── interfaces/                   # Interface patterns (14)
│   └── languages/                    # Language patterns (10)
│       └── python/
│
├── nmp/                              # Project NMPs (9)
│   └── NMP##-{PROJECT}-##-*.md
│
├── support/                          # Support tools (14)
│   ├── workflows/                    # Process templates (5)
│   ├── checklists/                   # Verification (4)
│   ├── tools/                        # Utilities (3)
│   ├── quick-reference/              # Quick refs (3)
│   └── utilities/                    # Helpers (1)
│
├── integration/                      # System integration (4)
│   ├── Integration-Test-Framework.md
│   ├── E2E-Workflow-Example-*.md (2)
│   └── System-Integration-Guide.md
│
└── context/                          # Mode contexts (6)
    ├── Custom-Instructions.md
    ├── SESSION-START-Quick-Context.md
    ├── SIMA-LEARNING-SESSION-START-Quick-Context.md
    ├── PROJECT-MODE-Context.md
    ├── DEBUG-MODE-Context.md
    └── SERVER-CONFIG.md
```

### File Naming Conventions

#### Base Entries
```
Format: {TYPE}-{NUMBER}-{Description}.md
Examples:
  ARCH-01-SUGA-Pattern.md
  GATE-03-Cross-Interface-Communication.md
  INT-12-Circuit-Breaker-Interface.md
  LANG-PY-03-Exception-Handling.md
```

#### Project NMPs
```
Format: NMP{PROJECT_NUMBER}-{PROJECT_CODE}-{NUMBER}-{Description}.md
Examples:
  NMP01-LEE-02-Cache-Interface-Functions.md
  NMP01-LEE-15-Gateway-Execute-Operation.md
```

#### Support Files
```
Format: {Category}-{Number}-{Description}.md
Examples:
  Workflow-01-Add-Feature.md
  Checklist-02-Deployment-Readiness.md
  QRC-03-Common-Patterns.md
```

#### Cross-Reference Files
```
Format: {Category}-Cross-Reference.md
Examples:
  Core-Architecture-Cross-Reference.md
  Python-Language-Patterns-Cross-Reference.md
```

#### Quick Index Files
```
Format: {Category}-Quick-Index.md
Examples:
  Gateway-Patterns-Quick-Index.md
  NMP01-LEE-Quick-Index.md
```

---

## 📄 ENTRY FORMAT SPECIFICATION

### Standard Entry Structure

All entries must follow this structure:

```markdown
# File: {FILENAME}.md

**REF-ID:** {REF-ID}
**Category:** {Gateway}/{Category}/{Topic}
**Version:** X.Y.Z
**Last Updated:** YYYY-MM-DD
**Status:** {Draft|Review|Active|Deprecated}

**Inherits From:** {REF-ID}, {REF-ID}
**Related To:** {REF-ID}, {REF-ID}
**Used In:** {REF-ID}, {REF-ID}

---

## Overview

[Brief description of what this entry covers]

### Purpose

[Why this entry exists]

### Scope

[What is in/out of scope]

---

## {Main Section 1}

[Content]

### {Subsection}

[Content]

---

## {Main Section 2}

[Content]

---

## Examples

### Example 1: {Title}

[Practical example with code/scenario]

### Example 2: {Title}

[Another example]

---

## Cross-References

**Inherits From:**
- REF-ID: Description

**Related To:**
- REF-ID: Description

**Used In:**
- REF-ID: Description

---

## Version History

**vX.Y.Z (YYYY-MM-DD)**
- Change 1
- Change 2

**vX.Y.Z (YYYY-MM-DD)**
- Initial version

---

**END OF ENTRY**
```

### Required Sections

1. **File Header** (MUST include filename)
2. **Metadata Block** (REF-ID, Category, Version, etc.)
3. **Overview Section** (Purpose, Scope)
4. **Content Sections** (Topic-specific)
5. **Examples Section** (At least 2 examples)
6. **Cross-References Section** (If applicable)
7. **Version History** (All changes)

### Optional Sections

- **Prerequisites** (If entry requires prior knowledge)
- **Anti-Patterns** (Common mistakes)
- **Troubleshooting** (Known issues)
- **Performance Considerations**
- **Security Considerations**

### Markdown Guidelines

**Headers:**
```markdown
# Level 1 - Entry title only
## Level 2 - Major sections
### Level 3 - Subsections
```

**Code Blocks:**
```markdown
```python
def example():
    """Always use language-specific syntax highlighting"""
    pass
```
```

**Lists:**
```markdown
- Unordered lists for items without sequence
1. Ordered lists for sequential steps
```

**Tables:**
```markdown
| Header 1 | Header 2 | Header 3 |
|----------|----------|----------|
| Data 1   | Data 2   | Data 3   |
```

**Emphasis:**
```markdown
**Bold** for important terms
*Italic* for emphasis
`Code` for inline code/commands
```

---

## 🆔 REF-ID SYSTEM

### REF-ID Format

REF-IDs uniquely identify entries:

```
Format: {TYPE}-{NUMBER}
Examples: ARCH-01, INT-06, GATE-03, LANG-PY-08
```

### REF-ID Types

| Type | Format | Range | Purpose |
|------|--------|-------|---------|
| Architecture | ARCH-## | 01-99 | Core patterns |
| Gateway | GATE-## | 01-99 | Gateway patterns |
| Interface | INT-## | 01-99 | Interface patterns |
| Language | LANG-{LANG}-## | 01-99 | Language patterns |
| Lessons | LESS-## | 01-99 | Lessons learned |
| Bugs | BUG-## | 01-99 | Bug reports |
| Decisions | DEC-## | 01-99 | Design decisions |
| Wisdom | WISD-## | 01-99 | Synthesized wisdom |
| Anti-Patterns | AP-## | 01-99 | Anti-patterns |
| Decision Logic | DT-## | 01-99 | Decision trees |
| Project NMP | NMP##-{CODE}-## | 01-99 | Project-specific |

### REF-ID Allocation

**Rules:**
1. REF-IDs are sequential within type
2. Numbers are never reused (even if entry deprecated)
3. Gaps are allowed (e.g., 01, 02, 05, 06)
4. Use leading zeros (01, not 1)

**Process:**
1. Check existing entries for next available number
2. Use `{TYPE}-{NEXT_NUMBER}` format
3. Update index files with new REF-ID
4. Document in cross-reference matrices

### REF-ID Registry

Maintain in Quick Index files:

```markdown
## REF-ID Registry

| REF-ID | Title | Status |
|--------|-------|--------|
| ARCH-01 | SUGA Pattern | Active |
| ARCH-02 | LMMS Pattern | Active |
| ARCH-03 | DD Pattern | Active |
| ARCH-04 | ZAPH Pattern | Active |
```

---

## 🔗 CROSS-REFERENCE SYSTEM

### Relationship Types

#### 1. Inherits From

Parent-child relationship where child inherits concepts from parent.

```markdown
**Inherits From:**
- ARCH-01: SUGA Pattern (base architecture)
- INT-01: Cache Interface (interface definition)
```

**Rules:**
- Child must extend parent (not replace)
- Multiple inheritance allowed
- No circular inheritance

#### 2. Related To

Sibling relationship where entries share common themes.

```markdown
**Related To:**
- GATE-02: Lazy Loading (optimization technique)
- INT-06: Logging Interface (observability)
```

**Rules:**
- Entries should have logical connection
- Bidirectional (if A relates to B, B relates to A)
- No limit on number of relations

#### 3. Used In

Implementation relationship showing where pattern is applied.

```markdown
**Used In:**
- NMP01-LEE-02: Cache Implementation (project usage)
- NMP01-LEE-15: Gateway Execute Operation (real example)
```

**Rules:**
- Links from generic to specific
- One-way (generic doesn't know all uses)
- Update when new uses created

### Cross-Reference Matrix

Each category has a matrix showing relationships:

```markdown
# File: Category-Cross-Reference-Matrix.md

## Entry Relationships

| Entry | Inherits From | Related To | Used In |
|-------|---------------|------------|---------|
| ARCH-01 | - | ARCH-02, ARCH-03 | NMP01-LEE-* |
| GATE-01 | ARCH-01 | GATE-02, GATE-03 | NMP01-LEE-15 |
```

### Maintaining Cross-References

**When Creating Entry:**
1. Identify parent entries (Inherits From)
2. Identify related entries (Related To)
3. Add to cross-reference matrix
4. Update parent entries if needed

**When Updating Entry:**
1. Review relationships still valid
2. Add new relationships if needed
3. Update cross-reference matrix
4. Update related entries

**When Deprecating Entry:**
1. Mark as deprecated in matrix
2. Do not remove from matrix
3. Update entries that reference it
4. Provide migration path

---

## 🎨 MODE SYSTEM API

### Mode Context Files

Each mode has a context file in `/sima/context/`:

```
SESSION-START-Quick-Context.md          (General Mode)
SIMA-LEARNING-SESSION-START-Quick-Context.md  (Learning Mode)
PROJECT-MODE-Context.md                 (Project Mode)
DEBUG-MODE-Context.md                   (Debug Mode)
```

### Mode Activation

**Syntax:**
```
Activation Phrases (exact match required):
- "Please load context"           → General Mode
- "Start SIMA Learning Mode"      → Learning Mode
- "Start Project Work Mode"       → Project Mode
- "Start Debug Mode"              → Debug Mode
```

**Process Flow:**
```
1. User provides activation phrase
2. Custom Instructions parse phrase
3. Identify mode from phrase
4. Load mode-specific context via web_fetch
5. Apply mode-specific behaviors
6. Confirm mode loaded to user
```

### Mode Context Structure

Each context file must include:

```markdown
# Mode: {MODE_NAME}
**Activation:** {EXACT_PHRASE}
**Purpose:** {WHEN_TO_USE}
**Duration:** {TYPICAL_LOAD_TIME}

## Core Behaviors
[Mode-specific behaviors]

## Available Tools
[Tools accessible in this mode]

## Workflows
[Mode-specific workflows]

## Verification
[Mode-specific checklists]

## Examples
[Mode-specific usage examples]
```

### Creating New Modes

**Steps:**

1. **Define Mode Purpose**
   ```markdown
   Purpose: {Clear use case}
   Users: {Target audience}
   Scope: {What mode covers}
   ```

2. **Design Context File**
   ```markdown
   Location: /sima/context/{MODE}-Context.md
   Content: Behaviors, tools, workflows, examples
   Size: Target 500-1000 lines for 30-60s load time
   ```

3. **Create Activation Phrase**
   ```markdown
   Phrase: "Start {MODE_NAME} Mode"
   Requirement: Must be unique and memorable
   ```

4. **Update Custom Instructions**
   ```markdown
   Add to mode list in Custom-Instructions.md
   Add activation phrase mapping
   Add mode description
   ```

5. **Test Mode**
   ```markdown
   Test activation phrase works
   Test context loads completely
   Test behaviors are correct
   Test doesn't conflict with other modes
   ```

---

## 🔍 PROJECT KNOWLEDGE SEARCH API

### Search Function

**Function:** `project_knowledge_search`

**Parameters:**
```python
query: str                 # Search keywords
max_text_results: int = 8  # Number of text results (1-15)
max_image_results: int = 2 # Number of image results (1-4)
```

**Returns:**
```python
List[SearchResult]
  - content: str          # Matched content
  - source: str           # File path
  - relevance: float      # Match score
```

### Usage Patterns

#### 1. Direct Entry Lookup

```python
# Search for specific REF-ID
project_knowledge_search(query="ARCH-01 SUGA")

# Search for entry title
project_knowledge_search(query="Cache Interface INT-01")
```

#### 2. Keyword Search

```python
# Search by concept
project_knowledge_search(query="lazy loading gateway")

# Search by problem
project_knowledge_search(query="Lambda cold start optimization")
```

#### 3. Multi-Term Search

```python
# Combine multiple keywords
project_knowledge_search(query="exception handling Python patterns")

# Search with context
project_knowledge_search(query="circuit breaker resilience interface")
```

### Search Best Practices

**Do:**
- Use 2-4 substantive keywords
- Include REF-ID if known
- Use specific technical terms
- Combine with cross-references

**Don't:**
- Use single generic terms
- Include filler words
- Use full sentences
- Expect exact phrase matching

### Search Optimization

**For Better Results:**

1. **Use Technical Terms**
   ```
   Good: "singleton pattern interface"
   Bad: "how to make only one instance"
   ```

2. **Include Category**
   ```
   Good: "gateway lazy loading"
   Bad: "lazy loading"
   ```

3. **Combine Related Concepts**
   ```
   Good: "cache interface TTL expiration"
   Bad: "cache"
   ```

4. **Use REF-IDs When Known**
   ```
   Good: "INT-01 cache operations"
   Bad: "cache operations"
   ```

---

## 🛠️ SUPPORT TOOLS API

### Workflow Templates

**Location:** `/sima/support/workflows/`

**Template Structure:**
```markdown
# File: Workflow-##-{Name}.md

## When to Use
[Trigger conditions]

## Prerequisites
[Requirements before starting]

## Steps

### Step 1: {Action}
**Goal:** {What to achieve}
**Actions:**
- [ ] Task 1
- [ ] Task 2

### Step 2: {Action}
[Repeat for all steps]

## Verification
- [ ] Check 1
- [ ] Check 2

## Common Issues
[Troubleshooting]
```

**Available Workflows:**
1. Workflow-01: Add Feature
2. Workflow-02: Debug Issue
3. Workflow-03: Update Interface
4. Workflow-04: Add Gateway Function
5. Workflow-05: Create NMP Entry

### Verification Checklists

**Location:** `/sima/support/checklists/`

**Checklist Structure:**
```markdown
# File: Checklist-##-{Name}.md

## Purpose
[What this checklist verifies]

## When to Use
[Trigger conditions]

## Checklist Items

### Category 1: {Name}
- [ ] Item 1: {Description}
- [ ] Item 2: {Description}

### Category 2: {Name}
[Repeat for all categories]

## Severity Levels
- ðŸ"´ Critical: Must fix
- 🟡 Warning: Should fix
- 🟢 Optional: Nice to have
```

**Available Checklists:**
1. Checklist-01: Code Review
2. Checklist-02: Deployment Readiness
3. Checklist-03: Documentation Quality
4. CHK-04: Tool Integration Verification

### Search Tools

**Location:** `/sima/support/tools/`

**Tool-01: REF-ID Lookup**
```markdown
Purpose: Find entry by REF-ID
Usage: "Find ARCH-01" or "Show INT-06"
Returns: Full entry content
```

**Tool-02: Keyword Search Guide**
```markdown
Purpose: Guide effective searching
Usage: Consult before complex searches
Returns: Search strategy recommendations
```

**Tool-03: Cross-Reference Validator**
```markdown
Purpose: Validate entry relationships
Usage: Run during entry creation/update
Returns: Validation report with issues
```

---

## 🔧 CREATING EXTENSIONS

### Adding New Entry Types

**1. Define Entry Type**
```markdown
Type Code: {TYPE}     (e.g., "FEAT" for features)
Number Range: 01-99
Purpose: {Clear description}
Category: {Gateway/Category/Topic}
```

**2. Create Template**
```markdown
Location: /sima/projects/templates/{type}_template.md
Include: All required sections
Provide: Example content for each section
```

**3. Update Documentation**
```markdown
Add to: Entry Type Reference (User Guide, Developer Guide)
Document: When to use, format, examples
Update: REF-ID System documentation
```

**4. Create Initial Entries**
```markdown
Create: 2-3 example entries
Test: In real scenarios
Refine: Based on feedback
```

### Adding New Workflows

**1. Identify Workflow Need**
```markdown
User Task: {Common task users perform}
Pain Points: {Current challenges}
Solution: {How workflow helps}
```

**2. Design Workflow**
```markdown
Steps: Clear, sequential actions
Decision Points: When/how to branch
Verification: How to confirm success
Exit Conditions: When workflow complete
```

**3. Create Workflow File**
```markdown
Location: /sima/support/workflows/Workflow-{##}-{Name}.md
Format: Standard workflow template
Content: Step-by-step with checkboxes
Examples: At least 2 usage examples
```

**4. Test Workflow**
```markdown
Test with: Real users on real tasks
Gather: Feedback and pain points
Iterate: Refine based on feedback
Document: Common issues and solutions
```

### Adding New Tools

**1. Tool Specification**
```markdown
Purpose: {Clear problem it solves}
Inputs: {What user provides}
Outputs: {What tool returns}
Integration: {How it fits with existing tools}
```

**2. Implementation**
```markdown
For Documentation: Create guide in /sima/support/tools/
For Scripts: Python script with clear API
For Web Tools: HTML/JS in /sima/projects/tools/
For Templates: Markdown template in /sima/projects/templates/
```

**3. Documentation**
```markdown
Usage: Clear instructions with examples
API: If applicable, document parameters/returns
Integration: How to use with workflows/checklists
Examples: At least 3 usage scenarios
```

**4. Validation**
```markdown
Test: All documented scenarios
Verify: Integration with existing tools
Check: No conflicts with current tools
Update: Tool inventory in User Guide
```

---

## ✅ VALIDATION AND TESTING

### Entry Validation

**Validation Checklist:**

```markdown
Structure:
- [ ] Filename in header
- [ ] REF-ID present and unique
- [ ] All required sections included
- [ ] Version number present
- [ ] Cross-references formatted correctly

Content:
- [ ] Overview section clear and concise
- [ ] At least 2 examples provided
- [ ] Cross-references valid
- [ ] No duplicate content
- [ ] Generic (unless NMP)

Format:
- [ ] Markdown syntax valid
- [ ] Code blocks have language tags
- [ ] Tables properly formatted
- [ ] Links work
- [ ] Headers properly nested

Quality:
- [ ] Technically accurate
- [ ] Clear and understandable
- [ ] Complete (no TODOs)
- [ ] Concise (< 200 lines for base entries)
```

### Cross-Reference Validation

**Use Tool-03:** `/sima/support/tools/Cross-Reference-Validator.md`

**Validation Levels:**

1. **Level 1: Syntax**
   - REF-IDs formatted correctly
   - Cross-reference sections present
   - Markdown syntax valid

2. **Level 2: Existence**
   - All referenced REF-IDs exist
   - Referenced files accessible
   - No broken links

3. **Level 3: Consistency**
   - Bidirectional references match
   - Inheritance chains valid
   - No circular references

4. **Level 4: Completeness**
   - All entries in matrices
   - All indexes updated
   - All relationships documented

### Integration Testing

**Test Framework:** `/sima/integration/Integration-Test-Framework.md`

**Test Categories:**

1. **Mode System Tests**
   - Each mode activates correctly
   - Context loads completely
   - Behaviors match specifications
   - No mode conflicts

2. **Search Tests**
   - REF-ID lookups work
   - Keyword search accurate
   - Cross-reference navigation works
   - Performance acceptable

3. **Entry System Tests**
   - Entries load correctly
   - Cross-references resolve
   - Inheritance works
   - No duplicates

4. **Tool Integration Tests**
   - Workflows function
   - Checklists complete
   - Tools accessible
   - Web tools load

5. **E2E Scenario Tests**
   - Feature implementation
   - Debug workflow
   - Learning workflow
   - Migration workflow

### Performance Testing

**Metrics:**

| Metric | Target | Measurement |
|--------|--------|-------------|
| Context Load Time | < 60s | Mode activation to ready |
| Search Response Time | < 2s | Query to results |
| Entry Load Time | < 1s | REF-ID to content |
| Cross-Ref Resolution | < 200ms | Link click to target |

**Benchmarking:**
```python
# Pseudo-code for performance test
start = time.now()
result = project_knowledge_search("ARCH-01")
duration = time.now() - start
assert duration < 2000  # 2 seconds
```

---

## 👥 CONTRIBUTING GUIDELINES

### Contribution Process

**1. Identify Need**
- Missing pattern
- Improvement opportunity
- New use case
- Bug fix

**2. Check Existing**
- Search for similar entries
- Review cross-references
- Check if already documented
- Avoid duplication

**3. Propose Change**
- Create proposal document
- Include rationale
- Show examples
- Get feedback

**4. Implement Change**
- Use appropriate template
- Follow format specifications
- Add cross-references
- Update indexes

**5. Validate**
- Run validation checklist
- Test in real scenario
- Get peer review
- Fix issues

**6. Document**
- Update version history
- Update cross-references
- Update indexes
- Update master control

### Code Style

**Markdown:**
- Use 2-space indentation
- Blank line between sections
- Tables: align columns
- Code blocks: include language

**REF-IDs:**
- Always uppercase
- Use leading zeros
- Format: TYPE-##
- Never reuse numbers

**File Names:**
- Kebab-case for multi-word
- Include type prefix
- Descriptive but concise
- Use .md extension

### Review Criteria

**Reviewers Check:**

1. **Accuracy**
   - Technical content correct
   - Examples work as shown
   - No misleading information

2. **Completeness**
   - All sections included
   - Examples sufficient
   - Cross-references complete

3. **Clarity**
   - Easy to understand
   - Well-organized
   - Good examples

4. **Consistency**
   - Matches existing style
   - Follows conventions
   - Integrates well

5. **Quality**
   - No typos
   - Proper formatting
   - Professional tone

### Version Control

**Versioning:**
```
Major.Minor.Patch (e.g., 1.2.3)

Major: Breaking changes, restructuring
Minor: New sections, significant additions
Patch: Fixes, clarifications, small improvements
```

**Version History Format:**
```markdown
## Version History

**v1.2.3 (2025-10-29)**
- Fixed typo in Example 2
- Added clarification to Step 5
- Updated cross-reference to INT-06

**v1.2.0 (2025-10-28)**
- Added new section: Performance Considerations
- Added 2 new examples
- Updated overview

**v1.0.0 (2025-10-25)**
- Initial version
```

---

## 📊 APPENDIX A: API REFERENCE

### project_knowledge_search()

```python
def project_knowledge_search(
    query: str,
    max_text_results: int = 8,
    max_image_results: int = 2
) -> List[SearchResult]:
    """
    Search project knowledge for relevant entries.
    
    Args:
        query: Search keywords (2-6 words recommended)
        max_text_results: Number of text results (1-15)
        max_image_results: Number of image results (1-4)
    
    Returns:
        List of SearchResult objects with content and metadata
    
    Example:
        results = project_knowledge_search("ARCH-01 SUGA pattern")
        for result in results:
            print(result.content)
    """
```

### web_fetch()

```python
def web_fetch(
    url: str,
    text_content_token_limit: Optional[int] = None
) -> str:
    """
    Fetch content from URL (used for loading context).
    
    Args:
        url: Full URL to fetch
        text_content_token_limit: Truncate text to token limit
    
    Returns:
        Content of URL as string
    
    Example:
        context = web_fetch(
            "https://example.com/context/SESSION-START.md"
        )
    """
```

---

## 📊 APPENDIX B: VALIDATION CHECKLIST

Complete validation checklist for new entries:

```markdown
## Entry Validation Checklist

### Structure (12 checks)
- [ ] Filename in header (# File: filename.md)
- [ ] REF-ID present and unique
- [ ] Category path correct (Gateway/Category/Topic)
- [ ] Version number present (X.Y.Z format)
- [ ] Last updated date present (YYYY-MM-DD)
- [ ] Status field present (Draft/Review/Active/Deprecated)
- [ ] Overview section included
- [ ] Main content sections present
- [ ] Examples section included (min 2 examples)
- [ ] Cross-references section included
- [ ] Version history included
- [ ] END OF ENTRY marker present

### Content (10 checks)
- [ ] Purpose clearly stated
- [ ] Scope defined (in/out)
- [ ] Examples are practical and complete
- [ ] Code examples include language tags
- [ ] Cross-references use valid REF-IDs
- [ ] No duplicate content from other entries
- [ ] Technical accuracy verified
- [ ] Generic content (unless NMP)
- [ ] Concise (< 200 lines for base entries)
- [ ] Professional tone maintained

### Format (8 checks)
- [ ] Markdown syntax valid
- [ ] Headers properly nested (##, ###, not ####)
- [ ] Code blocks formatted correctly
- [ ] Tables properly formatted
- [ ] Lists properly formatted
- [ ] Bold/italic used appropriately
- [ ] No trailing whitespace
- [ ] Consistent spacing (blank lines between sections)

### Cross-References (6 checks)
- [ ] All referenced REF-IDs exist
- [ ] Inherits From relationships valid
- [ ] Related To relationships documented
- [ ] Used In relationships (if applicable)
- [ ] Added to cross-reference matrix
- [ ] Added to quick index

### Integration (4 checks)
- [ ] Compatible with existing entries
- [ ] No conflicts with current patterns
- [ ] Integrates with support tools
- [ ] Works in appropriate modes

**Total Checks: 40**
**Pass Criteria: 38/40 (95%)**
```

---

## 📝 VERSION HISTORY

**v1.0.0 (2025-10-29)**
- Initial developer guide and API documentation
- Complete system architecture documentation
- File format specifications
- REF-ID and cross-reference system
- Mode system API
- Project knowledge search API
- Support tools API
- Extension development guidelines
- Validation and testing procedures
- Contributing guidelines
- Complete appendixes

---

**END OF DEVELOPER GUIDE**

**Status:** Production Ready  
**Audience:** Developers, system architects, contributors  
**Maintenance:** Update with system changes and new APIs  
**Feedback:** Welcome via contributions and issues

For user-focused documentation, see SIMAv4-User-Guide.md.

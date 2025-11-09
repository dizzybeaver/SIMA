# SIMA-Context-System-Optimization-Plan.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Comprehensive restructuring of SIMA context system  
**Status:** Planning Phase  
**Scope:** All context files, mode system, custom instructions

---

## 🎯 EXECUTIVE SUMMARY

### Current Problems

**1. Size Issues**
- Custom Instructions: ~900 lines (exceeds platform limits)
- SESSION-START-Quick-Context.md: 459 lines
- PROJECT-MODE-Context.md: 448 lines
- DEBUG-MODE-Context.md: 445 lines
- SIMA-LEARNING-SESSION-START-Quick-Context.md: 870 lines

**2. Structural Issues**
- No project-specific contexts
- Learning Mode conflates integration and maintenance
- No project scaffolding system
- Massive duplication across mode files
- Cannot switch projects within session

**3. Usability Issues**
- All context loaded every time (slow)
- Generic advice for specific projects
- No guided new project creation
- Maintenance tasks mixed with learning

### Solution Architecture

**Core Principles:**
1. **Minimal Custom Instructions** - Pure routing (100-150 lines)
2. **Project-Agnostic Core** - Base modes work anywhere
3. **Project Extensions** - Project-specific in `/projects/{project}/modes/`
4. **Mode Specialization** - Split complex modes
5. **Dynamic Loading** - Load only what's needed
6. **Shared Knowledge** - Extract common patterns

### Expected Benefits

**Size Reductions:**
- Custom Instructions: 900 lines → 150 lines (83% reduction)
- Mode contexts: 450 lines → 250 lines (44% reduction)
- Total context load: 1,350 lines → 400 lines (70% reduction)

**Functional Improvements:**
- Project switching without restart
- New project setup: 5 minutes → 30 seconds
- Clear mode boundaries
- Maintenance as separate concern
- Better project isolation

---

## 📋 PHASE 1: CUSTOM INSTRUCTIONS RESTRUCTURE

### Goal
Reduce Custom Instructions to absolute minimum routing logic

### Current Size: ~900 lines → Target: 150 lines

### What Stays in Custom Instructions

**1. Mode Router (40 lines)**
```markdown
## ACTIVATION SYSTEM

User says activation phrase → Claude loads mode context

**Core Modes:**
- "Please load context" → General Mode
- "Start SIMA Learning Mode" → Learning Mode  
- "Start SIMA Maintenance Mode" → Maintenance Mode
- "Start Project Mode for {PROJECT}" → Project Mode + project extension
- "Start Debug Mode for {PROJECT}" → Debug Mode + project extension
- "Start New Project Mode: {NAME}" → Project scaffolding

**Mode loading:**
1. Identify activation phrase
2. Fetch base mode context
3. If project specified, fetch project extension
4. Confirm ready
```

**2. Critical Rules (30 lines)**
```markdown
## UNIVERSAL RULES

**SUGA vs SIMA:**
- SUGA = Code architecture (Gateway → Interface → Core)
- SIMA = Documentation system

**Artifact Rules:**
- Code > 20 lines → Artifact
- ALL code → Complete files
- Chat output → Minimal
- Files ≤ 400 lines (split if needed)

**fileserver.php:**
- Fetch at session start
- Use URLs from output
- Fresh files guaranteed
```

**3. File Retrieval System (40 lines)**
```markdown
## FILE RETRIEVAL

**Session Start:**
1. User uploads File Server URLs.md
2. Fetch fileserver.php (include ?v= parameter)
3. Receive ~412 cache-busted URLs
4. Use for all file fetches

**Why:** Anthropic caches files for weeks.
**Solution:** Random ?v= parameters bypass cache.
```

**4. RED FLAGS (30 lines)**
```markdown
## RED FLAGS (Never Suggest)

- ❌ Threading locks (Lambda single-threaded)
- ❌ Direct core imports (use gateway)
- ❌ Bare except (use specific)
- ❌ Code in chat (artifacts only)
- ❌ File fragments (complete files)
- ❌ Files > 400 lines (split)
```

**5. Mode Behaviors Summary (10 lines)**
```markdown
## MODE BEHAVIORS

- **General:** Q&A, guidance, REF-IDs
- **Learning:** Extract knowledge, create entries
- **Maintenance:** Update indexes, remove old
- **Project:** Build features, complete code
- **Debug:** Root cause, fixes, prevention
- **New Project:** Scaffold structure, configs
```

**Total: ~150 lines**

### What Moves Out

**Moved to Shared Knowledge Base:**
- SUGA architecture details → `/sima/shared/SUGA-Architecture.md`
- Artifact standards → `/sima/shared/Artifact-Standards.md`
- File size limits → `/sima/shared/File-Standards.md`
- Encoding standards → `/sima/shared/Encoding-Standards.md`
- RED FLAGS details → `/sima/shared/RED-FLAGS.md`
- Mode comparison table → MODE-SELECTOR.md

**Moved to Mode Contexts:**
- Detailed mode behaviors → Each mode file
- Workflow tips → Mode-specific files
- Session workflows → Mode-specific files
- Pre-output checklists → Mode-specific files

### Implementation Steps

**Step 1: Create Shared Knowledge Base**
```
/sima/shared/
├── SUGA-Architecture.md (extracted from Custom Instructions)
├── Artifact-Standards.md (consolidated from all modes)
├── File-Standards.md (SPEC files reference)
├── Encoding-Standards.md (UTF-8, emoji, charts)
├── RED-FLAGS.md (all flags with rationale)
└── Common-Patterns.md (cross-cutting patterns)
```

**Step 2: Rewrite Custom Instructions**
- Start fresh with routing logic only
- Reference shared knowledge base
- Keep under 150 lines
- Test all activation phrases

**Step 3: Validate**
- Each mode activates correctly
- Shared knowledge accessible
- No broken references
- All rules still enforced

---

## 📋 PHASE 2: MODE CONTEXT OPTIMIZATION

### Goal
Reduce mode context files by 40%, eliminate duplication

### General Mode (SESSION-START-Quick-Context.md)

**Current: 459 lines → Target: 200 lines**

**Keep:**
- SIMA vs SUGA distinction
- Top 10 instant answers
- Query routing map
- Top 20 REF-IDs
- Artifact usage (brief)
- fileserver.php workflow

**Remove/Move:**
- Detailed artifact rules → `/sima/shared/Artifact-Standards.md`
- Detailed SUGA pattern → `/sima/shared/SUGA-Architecture.md`
- Full interface descriptions → Keep summary, link to detail
- Redundant RED FLAGS → Reference `/sima/shared/RED-FLAGS.md`
- Long examples → Link to workflow files

**Structure:**
```markdown
# SESSION-START-Quick-Context.md (200 lines)

## WHAT THIS IS (10 lines)
## FILE RETRIEVAL (30 lines) - fileserver.php
## SIMA vs SUGA (20 lines) - Clear distinction
## TOP 10 INSTANT ANSWERS (30 lines)
## QUERY ROUTING (40 lines) - Pattern → File
## TOP 20 REF-IDs (30 lines) - Most used
## ARTIFACT RULES (20 lines) - Reference shared
## SESSION WORKFLOW (20 lines)
```

### Project Mode (PROJECT-MODE-Context.md)

**Current: 448 lines → Target: 250 lines**

**Major Change: Make Project-Agnostic**

**Keep:**
- Critical project rules (fetch first, all layers, verify)
- SUGA implementation templates
- Common workflows
- RED FLAGS
- Artifact rules (reference shared)

**Remove:**
- LEE-specific examples → Move to `/projects/lee/modes/PROJECT-MODE-LEE.md`
- Interface descriptions → Reference shared
- Duplication of artifact rules → Reference shared
- Long examples → Link to examples

**New Activation:**
```
"Start Project Mode for LEE"
"Start Project Mode for SIMA"
"Start Project Mode for {PROJECT}"
```

**Loading Process:**
```
1. Load PROJECT-MODE-Context.md (base, 250 lines)
2. If project specified:
   - Load /projects/{project}/modes/PROJECT-MODE-{PROJECT}.md (100 lines)
3. Combine contexts
4. Ready for project work
```

**Base Structure:**
```markdown
# PROJECT-MODE-Context.md (250 lines)

## WHAT THIS IS (10 lines)
## FILE RETRIEVAL (20 lines)
## CRITICAL RULES (40 lines)
## SUGA TEMPLATES (60 lines) - Generic
## COMMON WORKFLOWS (60 lines) - Generic
## RED FLAGS (30 lines) - Reference shared
## ARTIFACT RULES (20 lines) - Reference shared
## BEST PRACTICES (10 lines)
```

**Project Extension Template:**
```markdown
# PROJECT-MODE-{PROJECT}.md (100 lines)

## PROJECT: {NAME}
## SPECIFIC CONSTRAINTS (20 lines)
## PROJECT PATTERNS (30 lines)
## CUSTOM WORKFLOWS (30 lines)
## PROJECT RED FLAGS (10 lines)
## EXAMPLES (10 lines)
```

### Debug Mode (DEBUG-MODE-Context.md)

**Current: 445 lines → Target: 250 lines**

**Same Pattern as Project Mode:**
- Base: Generic debug principles (250 lines)
- Extension: Project-specific bugs (100 lines)

**Activation:**
```
"Start Debug Mode for LEE"
"Start Debug Mode for SIMA"
"Start Debug Mode for {PROJECT}"
```

**Base Structure:**
```markdown
# DEBUG-MODE-Context.md (250 lines)

## WHAT THIS IS (10 lines)
## FILE RETRIEVAL (20 lines)
## DEBUG PRINCIPLES (40 lines)
## ERROR PATTERNS (60 lines) - Generic
## DEBUG TOOLS (40 lines) - Generic
## DEBUG WORKFLOWS (50 lines) - Generic
## RED FLAGS (20 lines) - Reference shared
## BEST PRACTICES (10 lines)
```

**Project Extension:**
```markdown
# DEBUG-MODE-{PROJECT}.md (100 lines)

## PROJECT: {NAME}
## KNOWN BUGS (30 lines) - Project-specific
## ERROR PATTERNS (30 lines) - Project-specific
## DEBUG TOOLS (20 lines) - Project-specific
## COMMON FIXES (20 lines)
```

### Learning Mode Split

**Split into TWO modes:**

**1. SIMA Learning Mode (Integration)**
**Current: 870 lines → Target: 300 lines**

**Purpose:** Extract and integrate NEW knowledge

**Activation:** "Start SIMA Learning Mode"

**Focus:**
- Extract patterns from conversations
- Create LESS/BUG/DEC/WISD entries
- Genericize content
- Check duplicates
- Update indexes

**Structure:**
```markdown
# SIMA-LEARNING-MODE-Context.md (300 lines)

## WHAT THIS IS (10 lines)
## FILE RETRIEVAL (20 lines)
## EXTRACTION SIGNALS (40 lines)
## WORKFLOWS (100 lines) - All entry types
## QUALITY STANDARDS (50 lines)
## DUPLICATE CHECKING (30 lines)
## ARTIFACT RULES (20 lines)
## POST-EXTRACTION (30 lines)
```

**2. SIMA Maintenance Mode (NEW)**
**Target: 200 lines**

**Purpose:** Maintain and clean EXISTING knowledge

**Activation:** "Start SIMA Maintenance Mode"

**Focus:**
- Update indexes
- Check for outdated entries
- Remove deprecated knowledge
- Verify cross-references
- Clean up old structure
- Migrate old formats

**Structure:**
```markdown
# SIMA-MAINTENANCE-MODE-Context.md (200 lines)

## WHAT THIS IS (10 lines)
## FILE RETRIEVAL (20 lines)
## MAINTENANCE TASKS (40 lines)
  - Index updates
  - Deprecation checks
  - Format migrations
  - Cross-reference validation
## WORKFLOWS (80 lines)
  - Update index workflow
  - Deprecate entry workflow
  - Migrate format workflow
  - Verify references workflow
## QUALITY CHECKS (30 lines)
## ARTIFACT RULES (20 lines)
```

### New Project Mode (NEW)

**Target: 250 lines**

**Purpose:** Scaffold new project structure

**Activation:** "Start New Project Mode: {PROJECT_NAME}"

**What It Does:**
1. Creates project directory structure
2. Generates configuration files
3. Creates base mode extensions
4. Sets up indexes
5. Generates README and integration guide

**Structure:**
```markdown
# NEW-PROJECT-MODE-Context.md (250 lines)

## WHAT THIS IS (20 lines)
## FILE RETRIEVAL (20 lines)
## PROJECT STRUCTURE (40 lines)
## CONFIGURATION TEMPLATES (60 lines)
## MODE EXTENSION TEMPLATES (60 lines)
## SCAFFOLDING WORKFLOW (40 lines)
## POST-SETUP GUIDE (10 lines)
```

**Creates:**
```
/projects/{project_name}/
├── config/
│   └── knowledge-config.yaml (generated)
├── modes/
│   ├── PROJECT-MODE-{PROJECT}.md (template)
│   └── DEBUG-MODE-{PROJECT}.md (template)
├── interfaces/ (if needed)
├── lessons/
├── decisions/
├── indexes/
├── README.md (generated)
└── INTEGRATION-GUIDE.md (generated)
```

---

## 📋 PHASE 3: PROJECT-SPECIFIC CONTEXTS

### Goal
Create project-specific mode extensions

### LEE Project Extensions

**Create: `/projects/lee/modes/`**

**1. PROJECT-MODE-LEE.md (100 lines)**
```markdown
## PROJECT: LEE (Lambda Execution Engine)

## CONSTRAINTS
- AWS Lambda environment
- 128MB memory limit
- 30-second timeout
- Home Assistant integration

## ARCHITECTURE
- Uses SUGA + LMMS + ZAPH + DD-1 + DD-2 + CR-1
- 12 interfaces
- Dictionary dispatch pattern
- Central cache registry

## PATTERNS
- Lazy imports mandatory
- Dictionary dispatch for interfaces
- Sentinel sanitization required
- SSM for tokens only

## WORKFLOWS
- Add Home Assistant device
- Update interface implementation
- Add new action handler
- Integrate new service

## RED FLAGS
- No threading (Lambda single-threaded)
- No heavy libraries (128MB limit)
- No module-level imports (cold start)

## EXAMPLES
- Interface implementation
- Action handler addition
- Service integration
```

**2. DEBUG-MODE-LEE.md (100 lines)**
```markdown
## PROJECT: LEE

## KNOWN BUGS
- BUG-01: Sentinel leak (535ms cost)
- BUG-02: _CacheMiss validation
- BUG-03: Circular imports
- BUG-04: Cold start spike

## ERROR PATTERNS
- JSON serialization (sentinel leak)
- ModuleNotFoundError (circular import)
- Lambda timeout (blocking operation)
- Cold start slow (heavy imports)
- Memory killed (exceeded 128MB)
- WebSocket disconnect (token expired)

## DEBUG TOOLS
- CloudWatch logs
- performance_benchmark.py
- debug_diagnostics.py
- Lambda diagnostic handler

## COMMON FIXES
- Sanitize sentinels before JSON
- Use lazy imports for cycles
- Add timeout controls
- Move to lazy import
- Clear cache when high memory
- Refresh token from SSM
```

**3. Custom-Instructions-LEE.md (50 lines)**
```markdown
## LEE PROJECT CUSTOM INSTRUCTIONS

**Project:** LEE (Lambda Execution Engine)
**Type:** AWS Lambda + Home Assistant Integration
**Architecture:** SUGA + LMMS + ZAPH + DD-1 + DD-2 + CR-1

## CRITICAL CONSTRAINTS
- Lambda single-threaded (no threading)
- 128MB memory limit
- 30-second timeout
- Home Assistant WebSocket integration

## ACTIVATION PHRASES
- "Start Project Mode for LEE"
- "Start Debug Mode for LEE"

## REFERENCES
- Base modes: /sima/context/
- Project modes: /projects/lee/modes/
- Shared knowledge: /sima/shared/
- Project knowledge: /projects/lee/
```

### SIMA Project Extensions

**Create: `/projects/sima/modes/`**

**1. PROJECT-MODE-SIMA.md (100 lines)**
```markdown
## PROJECT: SIMA (Neural Maps System)

## CONSTRAINTS
- Markdown files only
- ≤400 line limit per file
- UTF-8 encoding required
- Git version control

## ARCHITECTURE
- Domain separation (generic/platform/language/project)
- 6 Python architectures
- Knowledge configuration system
- Dynamic mode loading

## PATTERNS
- Generic by default
- Separate files (no condensing)
- Cross-reference with REF-IDs
- Index maintenance

## WORKFLOWS
- Add neural map entry
- Create new domain
- Update index
- Migrate old structure

## RED FLAGS
- No project-specifics in generic
- No files > 400 lines
- No condensed multi-topic files
- No missing cross-references

## EXAMPLES
- Neural map entry creation
- Domain organization
- Index generation
```

**2. DEBUG-MODE-SIMA.md (100 lines)**
```markdown
## PROJECT: SIMA

## KNOWN ISSUES
- Entries in wrong domain
- Broken cross-references
- Missing indexes
- Files exceeding 400 lines
- Duplicate content

## ERROR PATTERNS
- Entry not found (wrong domain)
- Broken REF-ID (moved file)
- Index out of date (missing entry)
- File too large (needs split)

## DEBUG TOOLS
- validate-migration.py
- cross-reference-checker.py
- index-generator.py
- duplicate-detector.py

## COMMON FIXES
- Move to correct domain
- Update cross-references
- Regenerate index
- Split large file
- Merge duplicates
```

---

## 📋 PHASE 4: SHARED KNOWLEDGE BASE

### Goal
Extract common patterns into reusable files

### Create: `/sima/shared/`

**1. SUGA-Architecture.md (150 lines)**
```markdown
## SUGA Architecture Pattern

**Layers:**
Gateway → Interface → Core

**Rules:**
- RULE-01: Import via gateway only
- ARCH-07: Lazy imports at function level
- DEP-01 to DEP-08: Layer dependencies

**Templates:**
[Gateway/Interface/Core templates]

**Anti-Patterns:**
[Common violations]
```

**2. Artifact-Standards.md (100 lines)**
```markdown
## Artifact Creation Standards

**When to Use:**
- Code > 20 lines
- ANY file modification
- Configuration files

**Requirements:**
- Complete files (never fragments)
- Mark changes (# ADDED:, # MODIFIED:)
- Filename in header
- ≤400 lines (split if needed)

**Pre-Output Checklist:**
[10-item checklist]
```

**3. File-Standards.md (80 lines)**
```markdown
## File Standards

**Size Limits:**
- Source code: ≤400 lines
- Neural maps: ≤400 lines
- Summaries: ≤100 lines
- Plans: ≤50 lines

**Headers Required:**
- Filename
- Version
- Date
- Purpose

**Structure:**
[Standard sections]
```

**4. Encoding-Standards.md (60 lines)**
```markdown
## Encoding Standards

**UTF-8 Required:**
- All text files
- Emoji support
- Special characters

**Verification:**
- Test emoji rendering
- Check chart display
- Validate markdown

**Common Issues:**
[Troubleshooting]
```

**5. RED-FLAGS.md (100 lines)**
```markdown
## Universal RED FLAGS

**Never Suggest:**
- Code in chat (artifacts only)
- File fragments (complete only)
- Files > 400 lines (split them)

**Platform-Specific:**
- Lambda: No threading
- Lambda: ≤128MB dependencies

**Architecture-Specific:**
- SUGA: No direct core imports
- SUGA: No skip gateway

**Process:**
- Never skip verification
- Never skip file fetch

[Details for each flag]
```

**6. Common-Patterns.md (120 lines)**
```markdown
## Cross-Cutting Patterns

**Import Patterns:**
[Generic lazy import pattern]

**Error Handling:**
[Generic error patterns]

**Logging:**
[Generic logging patterns]

**Validation:**
[Generic validation patterns]

**Testing:**
[Generic test patterns]
```

---

## 📋 PHASE 5: MODE SELECTOR UPDATE

### Goal
Update MODE-SELECTOR.md for new system

### Current: MODE-SELECTOR.md

**Add to Mode List:**

**Mode 5: Maintenance Mode**
```markdown
### Mode 5: Maintenance Mode
**Phrase:** "Start SIMA Maintenance Mode"
**Loads:** SIMA-MAINTENANCE-MODE-Context.md
**Purpose:** Maintain and clean existing knowledge
**Time:** 30-45 seconds

**Best for:**
- Update indexes
- Check outdated entries
- Verify cross-references
- Clean old structure
- Migrate formats
```

**Mode 6: New Project Mode**
```markdown
### Mode 6: New Project Mode
**Phrase:** "Start New Project Mode: {PROJECT_NAME}"
**Loads:** NEW-PROJECT-MODE-Context.md
**Purpose:** Scaffold new project structure
**Time:** 30-45 seconds

**Best for:**
- Create project directory
- Generate config files
- Set up mode extensions
- Initialize indexes
- Create integration guide
```

**Update Existing Modes:**

**Mode 3: Project Mode**
```markdown
### Mode 3: Project Mode
**Phrase:** "Start Project Mode for {PROJECT}"
**Loads:** 
  - PROJECT-MODE-Context.md (base)
  - /projects/{project}/modes/PROJECT-MODE-{PROJECT}.md (extension)
**Purpose:** Active development for specific project
**Time:** 30-45 seconds

**Examples:**
- "Start Project Mode for LEE"
- "Start Project Mode for SIMA"
```

**Mode 4: Debug Mode**
```markdown
### Mode 4: Debug Mode
**Phrase:** "Start Debug Mode for {PROJECT}"
**Loads:**
  - DEBUG-MODE-Context.md (base)
  - /projects/{project}/modes/DEBUG-MODE-{PROJECT}.md (extension)
**Purpose:** Troubleshooting specific project
**Time:** 30-45 seconds

**Examples:**
- "Start Debug Mode for LEE"
- "Start Debug Mode for SIMA"
```

**Update Decision Logic:**
```markdown
IF phrase = "Start Project Mode for [PROJECT]"
    THEN load PROJECT-MODE-Context.md
    AND load /projects/[PROJECT]/modes/PROJECT-MODE-[PROJECT].md
    
IF phrase = "Start Debug Mode for [PROJECT]"
    THEN load DEBUG-MODE-Context.md
    AND load /projects/[PROJECT]/modes/DEBUG-MODE-[PROJECT].md
    
IF phrase = "Start SIMA Maintenance Mode"
    THEN load SIMA-MAINTENANCE-MODE-Context.md
    
IF phrase = "Start New Project Mode: [NAME]"
    THEN load NEW-PROJECT-MODE-Context.md
    SET project_name = [NAME]
```

---

## 📋 PHASE 6: IMPLEMENTATION WORKFLOW

### Week 1: Foundation

**Day 1: Shared Knowledge Base**
- Create `/sima/shared/` directory
- Extract and create 6 shared files:
  - SUGA-Architecture.md
  - Artifact-Standards.md
  - File-Standards.md
  - Encoding-Standards.md
  - RED-FLAGS.md
  - Common-Patterns.md
- Verify all content accurate
- Test accessibility

**Day 2: Custom Instructions Rewrite**
- Create new Custom Instructions (150 lines)
- Keep only routing logic
- Reference shared knowledge
- Add project mode syntax
- Test all activation phrases
- Validate against old version

**Day 3: Mode Context Updates**
- Update SESSION-START (459→200 lines)
- Update PROJECT-MODE to generic (448→250 lines)
- Update DEBUG-MODE to generic (445→250 lines)
- Update SIMA-LEARNING (870→300 lines)
- Remove duplication
- Reference shared knowledge

### Week 2: New Modes

**Day 4: Maintenance Mode**
- Create SIMA-MAINTENANCE-MODE-Context.md (200 lines)
- Define maintenance tasks
- Create workflows
- Add quality checks
- Test activation

**Day 5: New Project Mode**
- Create NEW-PROJECT-MODE-Context.md (250 lines)
- Define scaffolding process
- Create templates
- Add configuration generation
- Test project creation

**Day 6: Project Extensions - LEE**
- Create `/projects/lee/modes/`
- Create PROJECT-MODE-LEE.md (100 lines)
- Create DEBUG-MODE-LEE.md (100 lines)
- Create Custom-Instructions-LEE.md (50 lines)
- Test with base modes

**Day 7: Project Extensions - SIMA**
- Create `/projects/sima/modes/`
- Create PROJECT-MODE-SIMA.md (100 lines)
- Create DEBUG-MODE-SIMA.md (100 lines)
- Create Custom-Instructions-SIMA.md (50 lines)
- Test with base modes

### Week 3: Integration

**Day 8: Mode Selector Update**
- Update MODE-SELECTOR.md
- Add Maintenance Mode
- Add New Project Mode
- Update Project Mode syntax
- Update Debug Mode syntax
- Test all routes

**Day 9: Validation**
- Test all 6 modes
- Test project switching
- Test new project creation
- Test shared knowledge access
- Verify file size reductions
- Check no broken references

**Day 10: Documentation**
- Update migration guide
- Create mode comparison chart
- Document project extension pattern
- Create new project guide
- Update README files

---

## 📋 PHASE 7: TESTING & VALIDATION

### Test Cases

**TC-01: Custom Instructions Size**
```
Test: Measure Custom Instructions file
Expected: ≤150 lines
Current: ~900 lines
Target: 83% reduction
```

**TC-02: Mode Context Sizes**
```
Test: Measure all mode context files
Expected:
- SESSION-START: ≤200 lines (from 459)
- PROJECT-MODE: ≤250 lines (from 448)
- DEBUG-MODE: ≤250 lines (from 445)
- LEARNING-MODE: ≤300 lines (from 870)
- MAINTENANCE-MODE: ≤200 lines (new)
- NEW-PROJECT-MODE: ≤250 lines (new)
```

**TC-03: Project Mode Activation**
```
Test: "Start Project Mode for LEE"
Expected:
1. Loads PROJECT-MODE-Context.md
2. Loads /projects/lee/modes/PROJECT-MODE-LEE.md
3. Confirms ready
4. Has LEE-specific knowledge
```

**TC-04: Debug Mode Activation**
```
Test: "Start Debug Mode for SIMA"
Expected:
1. Loads DEBUG-MODE-Context.md
2. Loads /projects/sima/modes/DEBUG-MODE-SIMA.md
3. Confirms ready
4. Has SIMA-specific bugs
```

**TC-05: Learning Mode**
```
Test: "Start SIMA Learning Mode"
Expected:
1. Loads SIMA-LEARNING-MODE-Context.md
2. Can extract knowledge
3. Checks duplicates
4. Updates indexes
5. Does NOT do maintenance
```

**TC-06: Maintenance Mode**
```
Test: "Start SIMA Maintenance Mode"
Expected:
1. Loads SIMA-MAINTENANCE-MODE-Context.md
2. Can update indexes
3. Can deprecate entries
4. Can verify references
5. Does NOT extract knowledge
```

**TC-07: New Project Creation**
```
Test: "Start New Project Mode: TestProject"
Expected:
1. Loads NEW-PROJECT-MODE-Context.md
2. Creates /projects/testproject/
3. Generates config files
4. Creates mode extensions
5. Creates indexes
6. Provides next steps
```

**TC-08: Shared Knowledge Access**
```
Test: Reference RED FLAGS from any mode
Expected:
1. Can access /sima/shared/RED-FLAGS.md
2. Content loads correctly
3. No duplication needed
```

**TC-09: Project Switching**
```
Test: Switch from LEE to SIMA in same session
Expected:
1. Start with "Start Project Mode for LEE"
2. Complete work
3. Switch: "Start Project Mode for SIMA"
4. New context loaded
5. Can work on SIMA
```

**TC-10: File Size Compliance**
```
Test: All new/updated files
Expected:
- Custom Instructions: ≤150 lines
- Mode contexts: ≤300 lines
- Project extensions: ≤100 lines
- Shared knowledge: ≤150 lines
```

### Validation Criteria

**Success Metrics:**
- ✅ Custom Instructions ≤150 lines
- ✅ All mode contexts ≤300 lines
- ✅ Project extensions ≤100 lines
- ✅ All 6 modes activate correctly
- ✅ Project switching works
- ✅ New project creation works
- ✅ Shared knowledge accessible
- ✅ No broken references
- ✅ No duplication
- ✅ 70% total context reduction

---

## 📋 PHASE 8: FILE INVENTORY

### Files to Create (New)

**Shared Knowledge:**
1. `/sima/shared/SUGA-Architecture.md` (150 lines)
2. `/sima/shared/Artifact-Standards.md` (100 lines)
3. `/sima/shared/File-Standards.md` (80 lines)
4. `/sima/shared/Encoding-Standards.md` (60 lines)
5. `/sima/shared/RED-FLAGS.md` (100 lines)
6. `/sima/shared/Common-Patterns.md` (120 lines)

**New Modes:**
7. `/sima/context/SIMA-MAINTENANCE-MODE-Context.md` (200 lines)
8. `/sima/context/NEW-PROJECT-MODE-Context.md` (250 lines)

**LEE Project:**
9. `/projects/lee/modes/PROJECT-MODE-LEE.md` (100 lines)
10. `/projects/lee/modes/DEBUG-MODE-LEE.md` (100 lines)
11. `/projects/lee/modes/Custom-Instructions-LEE.md` (50 lines)

**SIMA Project:**
12. `/projects/sima/modes/PROJECT-MODE-SIMA.md` (100 lines)
13. `/projects/sima/modes/DEBUG-MODE-SIMA.md` (100 lines)
14. `/projects/sima/modes/Custom-Instructions-SIMA.md` (50 lines)

**Total New Files: 14**

### Files to Update (Existing)

**Critical:**
1. `Custom Instructions for SUGA-ISP Development.md` (900→150 lines)
2. `SESSION-START-Quick-Context.md` (459→200 lines)
3. `PROJECT-MODE-Context.md` (448→250 lines)
4. `DEBUG-MODE-Context.md` (445→250 lines)
5. `SIMA-LEARNING-SESSION-START-Quick-Context.md` (870→300 lines)
6. `MODE-SELECTOR.md` (add 2 modes, update 2 modes)

**Total Updated Files: 6**

### Directory Structure Changes

**Add:**
```
/sima/
├── shared/ (NEW)
│   ├── SUGA-Architecture.md
│   ├── Artifact-Standards.md
│   ├── File-Standards.md
│   ├── Encoding-Standards.md
│   ├── RED-FLAGS.md
│   └── Common-Patterns.md
├── context/
│   ├── SIMA-MAINTENANCE-MODE-Context.md (NEW)
│   └── NEW-PROJECT-MODE-Context.md (NEW)
└── projects/
    ├── lee/
    │   └── modes/ (NEW)
    │       ├── PROJECT-MODE-LEE.md
    │       ├── DEBUG-MODE-LEE.md
    │       └── Custom-Instructions-LEE.md
    └── sima/
        └── modes/ (NEW)
            ├── PROJECT-MODE-SIMA.md
            ├── DEBUG-MODE-SIMA.md
            └── Custom-Instructions-SIMA.md
```

---

## 📋 PHASE 9: MIGRATION GUIDE

### For Existing Users

**Step 1: Backup**
```
1. Backup current Custom Instructions
2. Backup current mode context files
3. Note any customizations
```

**Step 2: Update Files**
```
1. Replace Custom Instructions
2. Update 6 mode context files
3. Add shared knowledge base (6 files)
4. Add new modes (2 files)
5. Add project extensions (6 files)
```

**Step 3: Test**
```
1. Test General Mode: "Please load context"
2. Test Learning Mode: "Start SIMA Learning Mode"
3. Test Maintenance Mode: "Start SIMA Maintenance Mode"
4. Test Project Mode: "Start Project Mode for LEE"
5. Test Debug Mode: "Start Debug Mode for SIMA"
6. Test New Project: "Start New Project Mode: TestProj"
```

**Step 4: Verify**
```
1. All modes activate
2. File sizes reduced
3. Project switching works
4. Shared knowledge accessible
5. No broken references
```

### For New Projects

**Step 1: Create Project**
```
"Start New Project Mode: {PROJECT_NAME}"

Claude will:
1. Create directory structure
2. Generate config files
3. Create mode extensions
4. Set up indexes
5. Provide integration guide
```

**Step 2: Configure**
```
Edit generated files:
1. knowledge-config.yaml (enable domains)
2. PROJECT-MODE-{PROJECT}.md (constraints)
3. DEBUG-MODE-{PROJECT}.md (bugs)
4. Custom-Instructions-{PROJECT}.md (overview)
```

**Step 3: Integrate**
```
1. Add project-specific knowledge
2. Create interface definitions (if needed)
3. Document lessons/decisions
4. Build indexes
```

**Step 4: Use**
```
Activate modes:
- "Start Project Mode for {PROJECT}"
- "Start Debug Mode for {PROJECT}"
```

---

## 📋 PHASE 10: BENEFITS ANALYSIS

### Quantitative Benefits

**Size Reductions:**
```
Custom Instructions: 900 → 150 lines (83% reduction)
SESSION-START: 459 → 200 lines (56% reduction)
PROJECT-MODE: 448 → 250 lines (44% reduction)
DEBUG-MODE: 445 → 250 lines (44% reduction)
LEARNING-MODE: 870 → 300 lines (66% reduction)

Total Context: 3,122 → 1,150 lines (63% reduction)
```

**Load Time Improvements:**
```
Old System:
- Custom Instructions: Always loaded (900 lines)
- Mode context: ~450 lines average
- Total: ~1,350 lines per session

New System:
- Custom Instructions: Always loaded (150 lines)
- Mode context: ~250 lines average
- Project extension: ~100 lines (when needed)
- Total: ~500 lines per session

Reduction: 63% fewer lines loaded
```

**File Count:**
```
Created: 14 new files
Updated: 6 existing files
Total: 20 files modified

New structure enables:
- Unlimited projects (scalable)
- Shared knowledge (no duplication)
- Specialized modes (clear boundaries)
```

### Qualitative Benefits

**1. Clarity**
- Clear mode purposes
- No conflation of concerns
- Obvious activation phrases

**2. Maintainability**
- Single source of truth (shared knowledge)
- Update once, applies everywhere
- Easy to extend

**3. Usability**
- Fast mode loading (63% faster)
- Project-specific guidance
- Automated project setup

**4. Flexibility**
- Work on multiple projects
- Switch projects mid-session
- Create new projects quickly

**5. Scalability**
- Add projects without bloat
- Share patterns across projects
- Grow knowledge systematically

---

## 📋 PHASE 11: ROLLBACK PLAN

### If Issues Arise

**Rollback Triggers:**
- Modes don't activate
- Context loading fails
- Project switching broken
- File fetching errors
- Shared knowledge inaccessible

**Rollback Process:**

**Step 1: Stop Using New System**
```
1. Do not use new activation phrases
2. Revert to old Custom Instructions
3. Use old mode context files
```

**Step 2: Restore Old Files**
```
1. Restore backed up Custom Instructions
2. Restore backed up mode contexts
3. Remove new shared directory
4. Remove new mode files
```

**Step 3: Document Issue**
```
1. Note what failed
2. Capture error messages
3. Identify missing functionality
4. Report to development
```

**Step 4: Fix and Retry**
```
1. Address identified issues
2. Test fix in isolation
3. Re-attempt deployment
4. Validate all functionality
```

### Graceful Degradation

**If Partial Failure:**
- Keep Custom Instructions update (routing works)
- Use old mode contexts (still functional)
- Skip project extensions (optional)
- Skip shared knowledge (fallback to old)

**Hybrid Approach:**
- New Custom Instructions (routing)
- Old mode contexts (detailed)
- Gradually migrate modes
- Add project extensions as ready

---

## 📋 PHASE 12: SUCCESS CRITERIA

### Must Have (Blocking)

1. ✅ **Custom Instructions ≤150 lines**
   - Current: ~900 lines
   - Target: 150 lines
   - Reduction: 83%

2. ✅ **All modes activate correctly**
   - General Mode works
   - Learning Mode works
   - Maintenance Mode works
   - Project Mode works (with project)
   - Debug Mode works (with project)
   - New Project Mode works

3. ✅ **Shared knowledge accessible**
   - All 6 shared files load
   - Referenced from modes
   - No duplication

4. ✅ **Project extensions load**
   - LEE extension works
   - SIMA extension works
   - Combine with base mode

5. ✅ **File sizes comply**
   - All new files ≤400 lines
   - Mode contexts ≤300 lines
   - Project extensions ≤100 lines

### Should Have (Important)

6. ✅ **Project switching works**
   - Can switch in same session
   - Context updates correctly
   - No confusion

7. ✅ **New project creation works**
   - Generates all files
   - Creates directories
   - Provides guide

8. ✅ **Maintenance mode functional**
   - Updates indexes
   - Checks outdated
   - Verifies references

9. ✅ **Documentation complete**
   - Migration guide exists
   - Mode comparison chart
   - Project extension pattern
   - New project guide

10. ✅ **No broken references**
    - All REF-IDs work
    - All file paths correct
    - All cross-references valid

### Nice to Have (Optional)

11. ✅ **Performance metrics**
    - Load time measurements
    - Token usage comparison
    - Context efficiency stats

12. ✅ **User feedback**
    - Test with real users
    - Gather improvement ideas
    - Iterate on design

13. ✅ **Additional projects**
    - Template for new projects
    - Example project created
    - Pattern established

---

## 📋 SUMMARY

### What Changes

**Files Modified: 20**
- Created: 14 new files
- Updated: 6 existing files

**Line Count Reduction: 63%**
- Before: 3,122 lines total context
- After: 1,150 lines total context
- Savings: 1,972 lines

**Key Improvements:**
1. Custom Instructions: 900 → 150 lines (83% reduction)
2. Project-agnostic modes (generic + extensions)
3. Learning/Maintenance split (clear boundaries)
4. Shared knowledge base (no duplication)
5. New project scaffolding (automated setup)
6. Project switching (same session)

### Implementation Timeline

**Week 1:** Foundation (shared knowledge, Custom Instructions, mode updates)
**Week 2:** New modes (Maintenance, New Project, extensions)
**Week 3:** Integration (testing, validation, documentation)

**Total: 3 weeks**

### Expected Outcomes

**Immediate:**
- 63% faster context loading
- Clearer mode purposes
- Project-specific guidance
- Automated project setup

**Long-term:**
- Scalable project system
- Maintainable knowledge base
- Flexible mode system
- Better user experience

---

**END OF OPTIMIZATION PLAN**

**Version:** 1.0.0  
**Status:** Ready for Implementation  
**Timeline:** 3 weeks (10 implementation days)  
**Impact:** 63% context reduction, 6 modes, unlimited projects

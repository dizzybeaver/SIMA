# Workflow-05-Create-Documentation-Entry.md

**Version:** 2.0.0  
**Date:** 2025-11-10  
**Category:** Support Tools  
**Type:** Workflow Template  
**Purpose:** Create documentation entries (generic or project-specific)  
**Updated:** SIMAv4 hierarchy, domain separation, fileserver.php

---

## 📋 WORKFLOW OVERVIEW

**Use when:** Documenting patterns, lessons, decisions, or implementations  
**Time:** 15-30 minutes  
**Complexity:** Medium  
**Prerequisites:** Knowledge to document, pattern identified, fileserver.php fetched

---

## ✅ PRE-WORK CHECKLIST

Before starting:
- [ ] fileserver.php fetched (fresh file access)
- [ ] Knowledge validated (implementation tested/working)
- [ ] Domain identified (generic, platform, language, or project)
- [ ] No duplicate entry exists (search via fileserver.php URLs)
- [ ] Related entries identified
- [ ] Cross-references prepared

---

## 🎯 PHASE 1: DOMAIN CLASSIFICATION (5 minutes)

### Step 1.1: Determine Correct Domain

**CRITICAL:** Proper domain separation prevents knowledge contamination

```
Ask: Where does this knowledge belong?

✅ GENERIC (/sima/entries/):
- Universal patterns (any language/platform)
- Core architecture concepts
- Transferable lessons
- Platform-agnostic principles
- No project/tool/language specifics

✅ PLATFORM (/sima/platforms/[platform]/):
- AWS Lambda-specific patterns
- Cloud provider features
- Platform constraints
- Service integration patterns

✅ LANGUAGE (/sima/languages/[language]/):
- Python-specific patterns
- Language features
- Standard library usage
- Language-specific architectures

✅ PROJECT (/sima/projects/[project]/):
- Project implementation details
- Function catalogs for this project
- Project-specific configurations
- Integration patterns in this project
- Performance characteristics in this context
```

**REF:** `/sima/entries/specifications/SPEC-STRUCTURE.md`

### Step 1.2: Check for Duplicates via fileserver.php
```
CRITICAL: Always search before creating

1. Use fileserver.php URLs to fetch fresh content
2. Search existing entries:
   project_knowledge_search: "[topic] [type]"

3. Check appropriate domain directories:
   - /sima/entries/[category]/ (generic)
   - /sima/platforms/[platform]/ (platform)
   - /sima/languages/[language]/ (language)
   - /sima/projects/[project]/ (project)

4. Decision:
   - Similar entry exists? → Update that entry
   - Truly unique? → Create new entry
```

**REF:** `/sima/entries/lessons/wisdom/WISD-06.md` (Cache-busting requirement)

### Step 1.3: Assign REF-ID and Path

**Generic Entries** (`/sima/entries/`):
```
Entry Type          | REF-ID Format | Path
--------------------|---------------|--------------------------------
Lesson              | LESS-##       | /entries/lessons/[category]/
Decision            | DEC-##        | /entries/decisions/[category]/
Anti-Pattern        | AP-##         | /entries/anti-patterns/[category]/
Bug                 | BUG-##        | /entries/lessons/bugs/
Wisdom              | WISD-##       | /entries/lessons/wisdom/
Interface Pattern   | INT-##        | /entries/interfaces/
Gateway Pattern     | GATE-##       | /entries/gateways/
```

**Platform Entries** (`/sima/platforms/`):
```
Format: AWS-[Service]-[Type]-##
Example: AWS-Lambda-LESS-01
Path: /platforms/aws/lambda/lessons/
```

**Language Entries** (`/sima/languages/`):
```
Format: LANG-[LANG]-##
Example: LANG-PY-01
Path: /languages/python/[category]/
```

**Project Entries** (`/sima/projects/`):
```
Format: [PROJECT]-[Type]-##
Example: LEE-LESS-01, SIMA-DEC-01
Path: /projects/[project]/[category]/
```

---

## 🔧 PHASE 2: CONTENT CREATION (15-20 minutes)

### Step 2.1: Select Correct Template

**Generic Entry Template:**
```markdown
# [TYPE-##].md

**Version:** 1.0.0  
**Date:** YYYY-MM-DD  
**Purpose:** [Brief purpose]
**Category:** [Category name]

[TYPE-##]: [Title]

[Content sections depend on type]

**Keywords:** k1, k2, k3, k4 (4-8 keywords)  
**Related:** TYPE-ID1, TYPE-ID2, TYPE-ID3 (3-7 entries)
```

**Project-Specific Template:**
```markdown
# [PROJECT]-[TYPE]-##.md

**Version:** 1.0.0  
**Date:** YYYY-MM-DD  
**Purpose:** [Brief purpose]
**Project:** [PROJECT NAME]
**Category:** [Category name]

[PROJECT]-[TYPE]-##: [Title]

**Context:** [Why this is project-specific]  
**Base Pattern:** [Generic entry it builds on]

[Content - project-specific implementation details]

**Keywords:** k1, k2, k3, k4  
**Related:** [TYPE-IDs] (mix of generic and project)
```

**REF:** `/sima/shared/File-Standards.md`, `/sima/entries/specifications/`

### Step 2.2: Write Clear Content

**For Generic Entries:**
```
- Strip ALL project specifics
- Extract universal principle
- Remove tool/framework names (unless core to pattern)
- Focus on transferable knowledge
- Keep ≤400 lines
```

**For Project Entries:**
```
- Be specific with actual details
- Include real function signatures
- Show concrete examples from project
- Document actual configurations
- Reference base generic patterns
- Keep ≤400 lines
```

**REF:** `/sima/shared/Encoding-Standards.md` (Line limits)

### Step 2.3: Add Code Examples
```
For generic entries:
- Generic pseudocode
- Universal patterns
- No specific tool names

For project entries:
- Real code from project
- Actual usage patterns
- Complete examples
- Project-specific details
```

### Step 2.4: Add Cross-References

**Link appropriately:**
- Generic → Other generic entries only
- Platform → Generic + same platform
- Language → Generic + same language  
- Project → Generic + platform + language + same project

**REF:** `/sima/entries/Master-Cross-Reference-Matrix.md`

---

## 📝 PHASE 3: FILE STANDARDS (5 minutes)

### Step 3.1: Verify File Standards Compliance
```
Checklist (/sima/shared/File-Standards.md):
- [ ] Filename in header
- [ ] Version, date, purpose present
- [ ] UTF-8 encoding
- [ ] LF line endings (not CRLF)
- [ ] ≤400 lines per file
- [ ] No trailing whitespace
- [ ] Final newline present
- [ ] Proper markdown formatting
- [ ] Keywords present (4-8)
- [ ] Related topics (3-7)
```

### Step 3.2: Split if Needed
```
If entry exceeds 400 lines:

1. Identify logical breakpoints
2. Create multiple focused files
3. Link between files
4. Update indexes for all

Example:
- INT-01_CACHE-Interface-Pattern.md (≤400 lines)
- INT-01_CACHE-Usage-Examples.md (≤400 lines)
```

**REF:** `/sima/entries/specifications/SPEC-LINE-LIMITS.md`

---

## 📚 PHASE 4: INTEGRATION (5 minutes)

### Step 4.1: Update Appropriate Indexes

**For generic entries:**
```
Update indexes in /sima/entries/:
- [Category]-Index.md (e.g., Lessons-Index.md)
- Master-Cross-Reference-Matrix.md
- SIMA-Quick-Reference-Card.md (if high-priority)
```

**For platform entries:**
```
Update indexes in /sima/platforms/[platform]/:
- [Platform]-Master-Index.md
- [Service]-Index.md
```

**For language entries:**
```
Update indexes in /sima/languages/[language]/:
- [Language]-Patterns-Index.md
- Category-specific indexes
```

**For project entries:**
```
Update indexes in /sima/projects/[project]/:
- [project]-Index-Main.md
- Category indexes
- README.md
```

### Step 4.2: Update Cross-Reference Matrix
```
Add relationships to:
- Base entries (if project/platform/language)
- Related patterns
- Implementation examples
```

### Step 4.3: Update Quick Reference (if applicable)
```
If entry is frequently needed:
- Add to appropriate quick-reference card
- Create problem → solution mapping
- Update navigation aids
```

---

## ✅ PHASE 5: VALIDATION (5 minutes)

### Step 5.1: Quality Checklist
```
Content Quality:
- [ ] Title clearly describes content
- [ ] Purpose statement accurate
- [ ] Domain classification correct
- [ ] No duplication of existing content
- [ ] Examples complete and accurate
- [ ] Cross-references valid

Technical Quality:
- [ ] File ≤400 lines
- [ ] Filename in header
- [ ] UTF-8 encoding
- [ ] LF line endings
- [ ] Markdown valid
- [ ] Keywords relevant (4-8)
- [ ] Related topics listed (3-7)
```

### Step 5.2: Domain Separation Verification
```
For generic entries:
- [ ] NO project/platform/language specifics
- [ ] Universal principles only
- [ ] Transferable patterns

For specific entries:
- [ ] References base generic pattern
- [ ] Adds specific implementation details
- [ ] Clearly marked as specific domain
```

### Step 5.3: Freshness Verification
```
- [ ] Used fileserver.php for all file access
- [ ] Checked against fresh content (not cached)
- [ ] No duplication with recently added entries
```

**REF:** `/sima/entries/lessons/wisdom/WISD-06.md`

---

## ⚠️ COMMON PITFALLS

### Pitfall 1: Wrong Domain Classification
```
❌ DON'T:
Put AWS Lambda specifics in /sima/entries/ (generic)

✅ DO:
Put AWS Lambda specifics in /sima/platforms/aws/lambda/
Reference generic pattern from /sima/entries/
```

### Pitfall 2: Project Details in Generic Entry
```
❌ DON'T:
LESS-15: "LEE project uses gateway.py with execute_operation()"

✅ DO:
LESS-15: "Verification protocol prevents implementation errors"
LEE-LESS-05: "LEE implements LESS-15 via gateway.py checks"
```

### Pitfall 3: Missing fileserver.php Check
```
❌ DON'T:
Create entry without checking for recent duplicates

✅ DO:
1. Fetch via fileserver.php (fresh URLs)
2. Search existing entries
3. Confirm uniqueness
4. Create if truly new
```

### Pitfall 4: Exceeding Line Limits
```
❌ DON'T:
Create 600-line entry file

✅ DO:
Split into multiple ≤400 line files:
- [TYPE-##]-Core.md
- [TYPE-##]-Examples.md
```

---

## 🎓 EXAMPLE WALKTHROUGH

### Example: Document Cache Sanitization Pattern

**Step 1: Domain Classification**
```
Topic: Sanitize sentinel objects at boundaries
Generic? Yes (universal pattern)
Platform? No (not AWS-specific)
Language? No (applies to any language)
Project? No (transferable concept)

Decision: Generic entry
Path: /sima/entries/lessons/core-architecture/
REF-ID: LESS-## (next available)
```

**Step 2: Check Duplicates (via fileserver.php)**
```
Search: "sentinel sanitization lesson"
Fetch relevant entries via cache-busted URLs
Result: No existing entry
Proceed with creation
```

**Step 3: Create Entry**
```markdown
# LESS-55.md

**Version:** 1.0.0  
**Date:** 2025-11-10  
**Purpose:** Sanitize implementation sentinels at boundaries
**Category:** Core Architecture

LESS-55: Sanitize implementation sentinels at boundaries

Generic Context: Internal sentinel objects fail serialization  
Prevention: Sanitize at boundary layer before serialization  
Impact: Prevents JSON failures, API errors

Example:
```python
# At boundary (router, API response builder)
if value is SENTINEL:
    value = None  # or appropriate default
```

**Keywords:** sentinel, sanitization, boundary, serialization  
**Related:** BUG-01, DEC-05, AP-19
```

**Step 4: Integration**
```
Update:
- /sima/entries/lessons/Lessons-Master-Index.md
- /sima/entries/lessons/core-architecture/Core-Architecture-Index.md
- /sima/entries/Master-Cross-Reference-Matrix.md
```

**Step 5: Validation**
```
✅ Generic (no project specifics)
✅ Unique (not duplicate)
✅ Complete (actionable content)
✅ ≤400 lines (well under limit)
✅ File standards compliant
✅ Cross-references added
✅ Indexes updated
```

---

## 📊 SUCCESS CRITERIA

Entry creation complete when:
- ✅ Domain correctly classified
- ✅ No duplication (checked via fileserver.php)
- ✅ Content appropriate for domain
- ✅ File standards compliant (≤400 lines, headers, encoding)
- ✅ Examples complete
- ✅ Cross-references added
- ✅ Indexes updated
- ✅ Quality checklist passed
- ✅ Clear domain separation maintained

---

## 🔗 RELATED RESOURCES

**Standards:**
- `/sima/shared/File-Standards.md` - File requirements
- `/sima/shared/Artifact-Standards.md` - Complete file rules
- `/sima/shared/Encoding-Standards.md` - Line limits, UTF-8
- `/sima/entries/specifications/` - All SPEC-* files

**Structure:**
- `/sima/entries/specifications/SPEC-STRUCTURE.md` - Domain organization
- `/sima/docs/SIMAv4-Directory-Structure.md` - Complete hierarchy

**Wisdom:**
- `/sima/entries/lessons/wisdom/WISD-06.md` - Cache-busting (fileserver.php)

**Indexes:**
- `/sima/entries/Master-Index-of-Indexes.md` - All index locations
- `/sima/entries/Master-Cross-Reference-Matrix.md` - Cross-reference system

**Workflows:**
- Workflow-01: Add Feature (generates documentation content)
- Workflow-02: Debug Issue (generates bug documentation)

---

## 🎯 DOMAIN-SPECIFIC GUIDELINES

### Generic Entries (/sima/entries/)
```
Include:
- Universal patterns
- Core architecture concepts
- Transferable lessons
- Platform-agnostic wisdom

Exclude:
- Project names
- Tool names (unless core to pattern)
- Platform specifics
- Language specifics
```

### Platform Entries (/sima/platforms/)
```
Include:
- Platform-specific constraints
- Service integration patterns
- Platform features usage
- Cloud provider specifics

Reference:
- Generic patterns from /sima/entries/
- Platform documentation
```

### Language Entries (/sima/languages/)
```
Include:
- Language-specific patterns
- Standard library usage
- Language features
- Idioms and conventions

Reference:
- Generic patterns from /sima/entries/
- Language architectures
```

### Project Entries (/sima/projects/)
```
Include:
- Actual implementation details
- Real function signatures
- Project configurations
- Concrete examples

Reference:
- Generic patterns from /sima/entries/
- Platform patterns from /sima/platforms/
- Language patterns from /sima/languages/
```

---

**END OF WORKFLOW-05**

**Version:** 2.0.0 (SIMAv4 major update)  
**Changes:** Complete rewrite for SIMAv4 hierarchy, domain separation, fileserver.php, shared knowledge  
**Replaces:** Old NMP entry workflow  
**Related workflows:** Workflow-01 (Add Feature), Workflow-02 (Debug Issue)

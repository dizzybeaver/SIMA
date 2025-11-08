# SPEC-LINE-LIMITS.md

**Version:** 2.0.0  
**Date:** 2025-11-08  
**Purpose:** File size limits for all SIMA content  
**Category:** Specifications

---

## CRITICAL RULE

**400 LINES MAXIMUM PER FILE**

This limit applies to **THE FILE**, not the topic. Even when batching multiple topics, the file containing them must be ≤400 lines total.

---

## PURPOSE

Define maximum line limits for different file types to ensure readability, maintainability, and efficient token usage.

---

## UNIVERSAL LIMITS

### Default Limit: 400 Lines PER FILE

**Every documentation file has a 400-line limit PER FILE.**

**What this means:**
- Each .md file ≤400 lines total
- Includes all content: headers, examples, everything
- Count from first line to last line
- Blank lines count

**What this does NOT mean:**
- ❌ NOT "400 lines per topic within the file"
- ❌ NOT "400 lines per section"
- ❌ NOT "each topic ≤400 lines but file can be bigger"

**The limit is THE FILE SIZE, period.**

---

## ANTI-PATTERN: MONOLITHIC FILES

### What NOT To Do

**❌ WRONG: Batching topics into one large file**
```
# LMMS-Decisions.md (1,200 lines)

## LMMS-DEC-01 (300 lines)
...

## LMMS-DEC-02 (250 lines)
...

## LMMS-DEC-03 (280 lines)
...

## LMMS-DEC-04 (370 lines)
...

RESULT: 1,200 line file - VIOLATION!
```

**✅ CORRECT: Separate files**
```
LMMS-DEC-01.md (300 lines) ✓
LMMS-DEC-02.md (250 lines) ✓
LMMS-DEC-03.md (280 lines) ✓
LMMS-DEC-04.md (370 lines) ✓

RESULT: All files ≤400 lines - COMPLIANT!
```

---

## BATCHING VS FILE SIZE

### Batching Is About Topics, Not File Size

**Batching means:** Cover related topics together when it makes sense.

**Batching does NOT mean:** Put multiple topics in one file if it exceeds 400 lines.

### Correct Batching Example

```
# INT-05-through-08.md (380 lines)

Covers 4 interfaces:
- INT-05: Config (90 lines)
- INT-06: Metrics (95 lines)  
- INT-07: Debug (100 lines)
- INT-08: Singleton (95 lines)

Total: 380 lines - COMPLIANT! ✓
```

### Incorrect Batching Example

```
# LMMS-All-Decisions.md (1,500 lines)

Covers 5 decisions:
- DEC-01 (300 lines)
- DEC-02 (280 lines)
- DEC-03 (310 lines)
- DEC-04 (320 lines)
- DEC-05 (290 lines)

Total: 1,500 lines - VIOLATION! ✗
Must split into separate files!
```

---

## WHEN TO BATCH

### Batch When All True:
1. Topics are closely related
2. Each topic is brief (50-100 lines)
3. **TOTAL FILE ≤400 LINES**
4. Makes sense to read together

### Don't Batch When Any True:
1. Topics not closely related
2. Each topic is long (200+ lines)
3. **TOTAL FILE >400 LINES**
4. Better as separate files

---

## FILE TYPE LIMITS

### Neural Map Entries: 400 Lines PER FILE
- Lessons (LESS-##): 400 lines max PER FILE
- Decisions (DEC-##): 400 lines max PER FILE
- Anti-Patterns (AP-##): 400 lines max PER FILE
- Bugs (BUG-##): 400 lines max PER FILE
- Wisdom (WISD-##): 400 lines max PER FILE

**Exception:** None - must split if exceeds 400 lines PER FILE

### Architecture Files: 400 Lines PER FILE
- Core concepts: 400 lines max PER FILE
- Pattern descriptions: 400 lines max PER FILE
- Implementation guides: 400 lines max PER FILE

**If comprehensive topic needs >400 lines:**
- Split into multiple files (Part 1, Part 2)
- Or create separate focused files
- Example: ARCH-01, ARCH-02, ARCH-03

### Specifications: 400 Lines PER FILE
- All SPEC-## files: 400 lines max PER FILE
- Focus on single standard
- Reference related specs if needed

### Context Files: 500 Lines PER FILE (EXCEPTION)
- Mode context files: 500 lines max PER FILE
- Custom instructions: 500 lines max PER FILE
- Bootstrap files: 500 lines max PER FILE

**Rationale:** Session setup needs comprehensive guidelines.

**Still apply:** Keep as brief as possible, 500 is maximum.

### Support Files: 400 Lines PER FILE
- Workflows: 400 lines max PER FILE
- Tools: 400 lines max PER FILE
- Templates: 400 lines max PER FILE
- Checklists: 400 lines max PER FILE

### Project Files: 400 Lines PER FILE
- Project docs: 400 lines max PER FILE
- Configuration: 400 lines max PER FILE
- README files: 400 lines max PER FILE

### Source Code: No Limit
- Python files: No limit
- Must be complete and deployable
- Functional requirements take priority

**Rationale:** Code must work; splitting breaks functionality.

### Index Files: No Limit
- Master indexes: No limit
- Generated automatically or manually
- Comprehensive listings needed

**Rationale:** Indexes list all entries; splitting defeats purpose.

---

## MEASUREMENT

### Line Counting Rules
- Count all lines including blank
- Headers count as lines
- Code blocks count all lines
- Exclude only YAML front matter (if present)
- Everything else counts

### Verification Command
```bash
# Check line count
wc -l filename.md

# Should output: XXX filename.md where XXX ≤ 400
```

### Pre-Creation Check
```bash
# Before creating file, verify:
LINES=$(wc -l < draft.md)
if [ $LINES -gt 400 ]; then
    echo "ERROR: File is $LINES lines (max 400)"
    echo "MUST SPLIT INTO MULTIPLE FILES"
    exit 1
fi
```

---

## EXCEEDING LIMITS

### If File Exceeds 400 Lines

**Step 1: Try to Condense**
- Remove verbosity
- Shorten examples
- Remove duplicate info
- Cross-reference instead

**Step 2: If Still >400, Must Split**

**Option A: Part 1 / Part 2**
```
Original: GUIDE-Complete.md (750 lines)

Split into:
- GUIDE-Part1-Overview.md (375 lines)
- GUIDE-Part2-Details.md (375 lines)
```

**Option B: Separate Topics (BETTER)**
```
Original: ALL-DECISIONS.md (1,200 lines)
Contains: DEC-01, DEC-02, DEC-03, DEC-04

Split into:
- DEC-01.md (300 lines)
- DEC-02.md (280 lines)
- DEC-03.md (310 lines)
- DEC-04.md (310 lines)

Each file ≤400 lines ✓
```

**Step 3: Update References**
- Update all links to new files
- Update indexes
- Add cross-references between parts

---

## PRACTICAL EXAMPLES

### Example 1: Architecture Files

**❌ WRONG:**
```
SUGA-Complete.md (1,800 lines)
- Core concept (400 lines)
- Gateways (350 lines)
- Interfaces (500 lines)
- Decisions (350 lines)
- Lessons (200 lines)

Total: 1,800 lines - VIOLATION!
```

**✅ CORRECT:**
```
SUGA-01-Core.md (390 lines)
SUGA-02-Gateways.md (340 lines)
SUGA-03-Interfaces.md (380 lines)
SUGA-04-Decisions.md (350 lines)
SUGA-05-Lessons.md (190 lines)

Each file ≤400 lines ✓
```

### Example 2: Batched Interfaces

**✅ CORRECT:**
```
INT-01-CACHE.md (320 lines)
INT-02-LOGGING.md (285 lines)
INT-03-SECURITY.md (310 lines)
INT-04-HTTP.md (295 lines)
INT-05-through-12.md (380 lines)
  - Covers 8 simple interfaces
  - Each ~45 lines
  - Total 380 lines ✓

All files ≤400 lines ✓
```

### Example 3: Decisions

**❌ WRONG:**
```
LMMS-All-Decisions.md (1,450 lines)
- Contains 5 decisions
- Each 280-320 lines
- Total 1,450 lines - VIOLATION!
```

**✅ CORRECT:**
```
LMMS-DEC-01.md (300 lines)
LMMS-DEC-02.md (285 lines)
LMMS-DEC-03.md (310 lines)
LMMS-DEC-04.md (290 lines)
LMMS-DEC-05.md (265 lines)

Each file ≤400 lines ✓
```

---

## QUALITY CHECKLIST

**Before creating/committing any file:**

- [ ] File is ≤400 lines (or ≤500 for context files)
- [ ] Verified with `wc -l filename.md`
- [ ] If batched, total file still ≤400 lines
- [ ] Content focused on related topics
- [ ] No unnecessary verbosity
- [ ] Examples concise (2-3 lines)
- [ ] Cross-references instead of duplication
- [ ] If exceeds limit, split into multiple files
- [ ] Each split file ≤400 lines
- [ ] All cross-references updated
- [ ] Indexes updated

---

## WHY THIS MATTERS

### Project Knowledge Search Limitation

Claude's `project_knowledge_search` tool truncates files:
- First 200 lines
- Last 200 lines
- **Middle content invisible**

**If file >400 lines:**
- Middle 100+ lines are lost
- Information becomes inaccessible
- Search results incomplete
- Knowledge effectively deleted

**At exactly 400 lines:**
- All content visible (200 + 200 = 400)
- Complete information available
- Search works correctly
- No data loss

**This is a hard technical constraint, not a style preference.**

---

## BENEFITS OF 400-LINE LIMIT

**Technical:**
- Works with project_knowledge_search (no truncation)
- Fits in context windows
- Fast to load and parse

**Organizational:**
- Focused content (one topic or related group)
- Better file discovery
- Easier to find specific information
- Clear boundaries between concepts

**Maintenance:**
- Easier to update (smaller scope)
- Less merge conflicts
- Clearer version control diffs
- Faster reviews

**Readability:**
- Not overwhelming
- Single sitting read time
- Clear structure
- Better retention

---

## RELATED SPECIFICATIONS

- SPEC-FILE-STANDARDS.md: Overall file standards
- SPEC-MARKDOWN.md: Markdown formatting rules
- SPEC-STRUCTURE.md: Directory organization
- SPEC-HEADERS.md: Required headers

---

## VERSION HISTORY

**v2.0.0 (2025-11-08):**
- CRITICAL: Emphasized "PER FILE" throughout
- ADDED: Anti-pattern section (monolithic files)
- ADDED: Batching vs file size distinction
- ADDED: Practical examples with violations
- ADDED: Pre-creation check script
- CLARIFIED: 400 lines is THE FILE, not per topic
- CLARIFIED: Batching still requires ≤400 lines total
- ADDED: Project knowledge search limitation explanation
- ADDED: Why this is a hard technical constraint

**v1.0.0 (2025-11-06):**
- Initial specification
- 400-line universal limit
- Basic file type guidelines

---

**END OF FILE**

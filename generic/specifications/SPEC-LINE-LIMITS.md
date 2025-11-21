# SPEC-LINE-LIMITS.md

**Version:** 4.2.2-blank  
**Date:** 2025-11-10  
**Purpose:** File size limits for all SIMA content  
**Category:** Specifications

---

## CRITICAL RULE

**350 LINES MAXIMUM PER FILE**

This limit applies to **THE FILE**, not the topic. Even when batching multiple topics, the file containing them must be ≤400 lines total.
You will lose minimum 22% once you hit 401 lines due to truncation this has been verfied.

---

## PURPOSE

Define maximum line limits for different file types to ensure readability, maintainability, and efficient token usage.

---

## UNIVERSAL LIMITS

### Default Limit: 350 Lines PER FILE

**Every documentation file has a 350-line limit PER FILE.**

**What this means:**
- Each .md file ≤350 lines total - You will lose 22% minimum due to truncation once the file hits 401 lines. This has been verified.
- Includes all content: headers, examples, everything
- Count from first line to last line
- Blank lines count

**What this does NOT mean:**
- ❌ NOT "350 lines per topic within the file"
- ❌ NOT "350 lines per section"
- ❌ NOT "each topic ≤350 lines but file can be bigger"

**The limit is THE FILE SIZE, period.**

---

## ANTI-PATTERN: MONOLITHIC FILES

### What NOT To Do

**❌ WRONG: Batching topics into one large file**
```
# Decisions.md (1,200 lines)

## DEC-01 (300 lines)
...

## DEC-02 (250 lines)
...

## DEC-03 (280 lines)
...

## DEC-04 (370 lines)
...

RESULT: 1,200 line file - VIOLATION!
```

**✅ CORRECT: Separate files**
```
DEC-01.md (300 lines) ✓
DEC-02.md (250 lines) ✓
DEC-03.md (280 lines) ✓
DEC-04.md (370 lines) ✓

RESULT: All files ≤350 lines - COMPLIANT!
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
# All-Decisions.md (1,500 lines)

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
1. Never Batch files together period make the 50-100 line files.

### Don't Batch When Any True:
1. Topics not closely related
2. **TOTAL FILE >350 LINES**
3. Better as separate files

---

## FILE TYPE LIMITS

### Neural Map Entries: 350 Lines PER FILE
- Lessons (LESS-##): 350 lines max PER FILE
- Decisions (DEC-##): 350 lines max PER FILE
- Anti-Patterns (AP-##): 350 lines max PER FILE
- Bugs (BUG-##): 350 lines max PER FILE
- Wisdom (WISD-##): 350 lines max PER FILE

**Exception:** None - must split or shrink if exceeds 350 lines PER FILE

### Architecture Files: 350 Lines PER FILE
- Core concepts: 350 lines max PER FILE
- Pattern descriptions: 350 lines max PER FILE
- Implementation guides: 350 lines max PER FILE

**If comprehensive topic needs >350 lines:**
- Split into multiple files (Part 1, Part 2)
- Or create separate focused files
- Example: ARCH-01, ARCH-02, ARCH-03

### Specifications: 350 Lines PER FILE
- All SPEC-## files: 350 lines max PER FILE
- Focus on single standard
- Reference related specs if needed

### Context Files: 350 Lines PER FILE (NO EXCEPTION)
- Mode context files: 350 lines max PER FILE
- Custom instructions: 350 lines max PER FILE
- Bootstrap files: 350 lines max PER FILE

**Rationale:** Session setup needs comprehensive guidelines but you lose minimum 22% when you reach 401 lines. Then as grows bigger you will only get 400 lines total no matter size due to truncation. This has been verified. Never go above 399 Lines at all costs.

**Still apply:** Keep as brief as possible, 350 is maximum.

### Support Files: 350 Lines PER FILE
- Workflows: 350 lines max PER FILE
- Tools: 350 lines max PER FILE
- Templates: 350 lines max PER FILE
- Checklists: 350 lines max PER FILE

### Project Files: 350 Lines PER FILE
- Project docs: 350 lines max PER FILE
- Configuration: 350 lines max PER FILE
- README files: 350 lines max PER FILE

### Source Code: 350 Lines Max PER FILE
- Source files: If greater than 350 lines.
  1. Make 1 Main File
  2. Break into smaller files
  3. Have Main File import smaller files.
- Must be complete and deployable
- Functional requirements take priority

**Rationale:** Code must work; Smart splitting does not break functionality.

### Index Files: 350 Lines max PER FILE
- Master indexes: If Greater than 350 lines
  1. Make 1 Main Master Index File
  2. Break into smaller Index Files
  3. Have Main index File Reference smaller files with very very brief description and keywords
- Generated automatically or manually
- Comprehensive listings needed

**Rationale:** Indexes list all entries; Smart splitting prevents truncation.

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

### If File Exceeds 350 Lines

**Step 1: Try to Condense**
- Remove verbosity
- Shorten examples
- Remove duplicate info
- Cross-reference instead

**Step 2: If Still >350, Must Split**

**Option A: Part 1 / Part 2**
```
Original: GUIDE-Complete.md (700 lines)

Split into:
- GUIDE-Part1-Overview.md (350 lines)
- GUIDE-Part2-Details.md (350 lines)
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

Each file ≤350 lines ✓
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
ARCH-Complete.md (1,800 lines)
- Core concept (400 lines)
- Gateways (350 lines)
- Interfaces (500 lines)
- Decisions (350 lines)
- Lessons (200 lines)

Total: 1,800 lines - VIOLATION!
```

**✅ CORRECT:**
```
ARCH-01-Core.md (350 lines)
ARCH-02-Gateways.md (340 lines)
ARCH-03-Interfaces.md (350 lines)
ARCH-04-Decisions.md (340 lines)
ARCH-05-Lessons.md (190 lines)

Each file ≤350 lines ✓
```

### Example 2: Batched Interfaces

**✅ CORRECT:**
```
Never Batch Files together. Make the smaller 50-100 line files always
```

### Example 3: Decisions

**❌ WRONG:**
```
All-Decisions.md (1,450 lines)
- Contains 5 decisions
- Each 280-320 lines
- Total 1,450 lines - VIOLATION!
```

**✅ CORRECT:**
```
DEC-01.md (300 lines)
DEC-02.md (285 lines)
DEC-03.md (310 lines)
DEC-04.md (290 lines)
DEC-05.md (265 lines)

Each file ≤350 lines ✓
```

---

## QUALITY CHECKLIST

**Before creating/committing any file:**

- [ ] File is ≤350 lines
- [ ] Verified with `wc -l filename.md`
- [ ] If batched, split the files
- [ ] Content focused on related topics
- [ ] No unnecessary verbosity
- [ ] Examples concise (2-3 lines)
- [ ] Cross-references instead of duplication
- [ ] If exceeds limit, split into multiple files
- [ ] Each split file ≤350 lines
- [ ] All cross-references updated
- [ ] Indexes updated

---

## WHY THIS MATTERS

### Knowledge Search Limitation

Knowledge search tools may truncate files:
- First 200 lines
- Last 200 lines
- **Middle content invisible and you lose 22% of middle automatically - verified**

**If file >401 lines:**
- Middle 100+ lines are lost
- Information becomes inaccessible
- Search results incomplete
- Knowledge effectively deleted

**At exactly 399 lines:**
- All content visible (200 + 199 = 399)
- Complete information available
- Search works correctly
- No data loss

**This is a hard technical constraint and it is critically important, not a style preference.**

---

## BENEFITS OF 350-LINE LIMIT

**Technical:**
- Works with knowledge search (no truncation)
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

**v4.2.2-blank (2025-11-10):**
- Blank slate version
- Genericized examples

---


**END OF FILE**

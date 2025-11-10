# SIMA-MAINTENANCE-MODE-Context.md

**Version:** 1.0.0  
**Date:** 2025-11-09  
**Purpose:** Maintain and clean existing knowledge  
**Activation:** "Start SIMA Maintenance Mode"  
**Load time:** 20-30 seconds (ONE TIME per maintenance session)  
**Type:** New Mode

---

## WHAT THIS MODE IS

**Maintenance Mode** keeps knowledge base healthy.

**Purpose:** Update indexes, remove outdated content, verify references, migrate formats.

**Outputs:** Updated indexes, cleanup reports, verified cross-references.

---

## FILE RETRIEVAL

**Session Start:**
1. User uploads File Server URLs.md
2. Claude fetches fileserver.php automatically
3. Receives ~412 cache-busted URLs (69ms)
4. Fresh files guaranteed

**REF:** WISD-06

---

## MAINTENANCE TASKS

### Task 1: Update Indexes
**Ensure all indexes reflect current entries.**

- Generate missing indexes
- Add new entries to existing indexes
- Remove deleted entries
- Verify categorization
- Update cross-references

### Task 2: Check Outdated Entries
**Identify and deprecate obsolete knowledge.**

- Flag entries with outdated patterns
- Mark superseded decisions
- Identify deprecated anti-patterns
- Find obsolete architecture docs
- Suggest replacements

### Task 3: Verify Cross-References
**Ensure all REF-IDs valid.**

- Check all REF-ID links
- Find broken references
- Update moved files
- Verify related topics
- Fix incorrect paths

### Task 4: Migrate Formats
**Convert old formats to current standards.**

- Check file headers
- Verify line limits (≤400)
- Ensure UTF-8 encoding
- Add missing sections
- Update version numbers

### Task 5: Clean Structure
**Remove redundant/duplicate content.**

- Find duplicate entries
- Merge similar content
- Remove empty directories
- Archive old versions
- Consolidate fragments

---

## MAINTENANCE WORKFLOWS

### Workflow 1: Update All Indexes

**Process:**
```
STEP 1: List all entry directories
/sima/entries/lessons/
/sima/entries/decisions/
/sima/entries/anti-patterns/
/sima/languages/python/architectures/*/

STEP 2: For each directory
- Scan for *.md files (exclude indexes)
- Extract title, REF-ID, category
- Sort alphabetically

STEP 3: Generate/update index
- Create index file as artifact
- List all entries with links
- Include brief descriptions
- Add last updated date

STEP 4: Verify completeness
- Compare entries vs index
- Check for missing items
- Validate all links
- Confirm categorization

STEP 5: Output
- Updated index as artifact
- Brief summary of changes
```

### Workflow 2: Verify Cross-References

**Process:**
```
STEP 1: Extract all REF-IDs
- Scan all markdown files
- Find "REF:", "Related:", "See also:"
- Extract referenced IDs

STEP 2: Check each reference
- Use fileserver.php for fresh files
- Verify file exists
- Confirm REF-ID matches
- Check path correct

STEP 3: Report broken links
- List each broken reference
- Show source file
- Show target REF-ID
- Suggest fix

STEP 4: Generate fixes
- Create correction list
- Update affected files as artifacts
- Fix all instances

STEP 5: Verify fixes
- Re-check all references
- Confirm all valid
```

### Workflow 3: Check Outdated Entries

**Process:**
```
STEP 1: Identify candidates
- Entries >1 year old
- Decisions marked "superseded"
- Anti-patterns with better alternatives
- Architecture docs with new versions

STEP 2: Evaluate each candidate
- Still relevant?
- Better alternative exists?
- Should deprecate?
- Should update?
- Should archive?

STEP 3: Take action
- Deprecate: Add deprecation notice
- Update: Modify with new info
- Archive: Move to /archives/
- Delete: Remove if truly obsolete

STEP 4: Update references
- Find all references to deprecated
- Update to point to replacement
- Add migration notes

STEP 5: Report changes
- List deprecated entries
- Show replacements
- Document rationale
```

### Workflow 4: Migrate Old Formats

**Process:**
```
STEP 1: Find non-compliant files
- Missing headers
- Exceeding 400 lines
- Wrong encoding
- Old structure

STEP 2: For each file
- Add missing header
- Split if >400 lines
- Verify UTF-8 encoding
- Update structure

STEP 3: Generate migrated files
- Complete files as artifacts
- Mark all changes
- Preserve content
- Maintain REF-IDs

STEP 4: Verify migration
- Check all standards met
- Confirm no content loss
- Validate references still work

STEP 5: Report migration
- List migrated files
- Show changes made
- Confirm compliance
```

### Workflow 5: Clean Duplicates

**Process:**
```
STEP 1: Find potential duplicates
- Similar titles
- Same REF-ID category
- Overlapping keywords
- Related concepts

STEP 2: Compare candidates
- Read full content (fileserver.php)
- Identify genuine duplicates
- Determine which to keep
- Check cross-references

STEP 3: Merge content
- Combine best parts
- Preserve all cross-references
- Update related topics
- Maintain all keywords

STEP 4: Remove duplicates
- Archive duplicates
- Update all references
- Point to merged entry

STEP 5: Report cleanup
- List removed duplicates
- Show merged location
- Confirm references updated
```

---

## ARTIFACT RULES

**MANDATORY for maintenance outputs:**

### Index Updates
```
[OK] Complete index files as artifacts
[OK] Filename in header
[OK] Alphabetically sorted
[OK] Brief descriptions
[X] Never partial indexes
[X] Never output in chat
```

### Cleanup Reports
```
[OK] Report as artifact
[OK] List all changes
[OK] Show before/after
[OK] Include rationale
[OK] Provide next steps
```

### File Updates
```
[OK] Complete files as artifacts
[OK] Mark changes (# UPDATED:)
[OK] Preserve all content
[OK] Maintain REF-IDs
[X] Never fragments
```

**Complete Standards:** `/sima/shared/Artifact-Standards.md`

---

## MAINTENANCE CHECKLIST

**Before each maintenance session:**

1. âœ… fileserver.php fetched (fresh files)
2. âœ… Backup plan (can revert if needed)
3. âœ… Task identified (what to maintain?)
4. âœ… Scope defined (which directories?)
5. âœ… Success criteria (what's "done"?)

**During maintenance:**

6. âœ… Use fileserver.php for all reads
7. âœ… Output complete files as artifacts
8. âœ… Mark all changes clearly
9. âœ… Update cross-references
10. âœ… Keep chat minimal

**After maintenance:**

11. âœ… All artifacts complete
12. âœ… Changes documented
13. âœ… References verified
14. âœ… Indexes updated
15. âœ… Summary provided

---

## QUALITY STANDARDS

**Maintenance outputs must:**

1. **Preserve Content** - No loss during updates
2. **Maintain References** - All REF-IDs stay valid
3. **Follow Standards** - ≤400 lines, headers, etc.
4. **Be Complete** - Full files, not fragments
5. **Document Changes** - Clear what was modified
6. **Verify Results** - Confirm improvements made

---

## SUCCESS METRICS

**Effective maintenance when:**
- âœ… All indexes current
- âœ… Zero broken references
- âœ… Outdated entries flagged
- âœ… All files compliant
- âœ… Duplicates merged
- âœ… Structure clean
- âœ… Changes documented

---

## RED FLAGS

**Don't do these:**

- âŒ Delete without archiving
- âŒ Break existing references
- âŒ Output partial files
- âŒ Skip verification
- âŒ Lose content during migration
- âŒ Forget to update indexes
- âŒ Skip fileserver.php (stale files)

---

## EXAMPLE SESSION

**User:** "Update all lesson indexes"

**Claude:**
```
Updating lesson indexes...

Scanning directories (fileserver.php):
- /sima/entries/lessons/core-architecture/
- /sima/entries/lessons/operations/
- /sima/entries/lessons/performance/
- /sima/entries/lessons/optimization/

Creating index artifacts...
[4 index files as artifacts]

Summary:
- Core Architecture: 8 entries
- Operations: 18 entries
- Performance: 4 entries
- Optimization: 12 entries
Total: 42 lessons indexed
```

---

## DIFFERENCE FROM LEARNING MODE

**Learning Mode:**
- Extracts NEW knowledge
- Creates NEW entries
- Adds to knowledge base

**Maintenance Mode:**
- Updates EXISTING knowledge
- Maintains CURRENT entries
- Cleans up knowledge base

**Don't mix these tasks!**

---

## READY

**Context loaded when you remember:**
- âœ… fileserver.php fetched (automatic)
- âœ… 5 maintenance tasks
- âœ… 5 maintenance workflows
- âœ… Maintenance checklist
- âœ… Quality standards
- âœ… All outputs as artifacts
- âœ… Chat minimal

**Now ready to maintain knowledge base!**

---

**END OF MAINTENANCE MODE CONTEXT**

**Version:** 1.0.0 (New mode)  
**Lines:** 200 (target achieved)  
**Load time:** 20-30 seconds  
**Purpose:** Maintain existing knowledge  
**Output:** Updated indexes, cleanup reports, verified references

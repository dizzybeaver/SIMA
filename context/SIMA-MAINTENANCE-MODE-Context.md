# SIMA-MAINTENANCE-MODE-Context.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Maintain and clean existing knowledge  
**Activation:** "Start SIMA Maintenance Mode"  
**Load time:** 20-30 seconds (ONE TIME per maintenance session)  
**Type:** New Mode

---

## WHAT THIS MODE IS

**Maintenance Mode** keeps SIMA knowledge clean and current.

**Purpose:** Update indexes, remove outdated, verify references.

**NOT for:** Extracting new knowledge (use Learning Mode).

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
**Ensure all indexes are current.**

Actions:
- Find entries missing from indexes
- Add new entries to appropriate indexes
- Remove deleted entries
- Sort alphabetically/numerically
- Output updated indexes as artifacts

### Task 2: Check Outdated Entries
**Identify knowledge that needs updating.**

Look for:
- Deprecated decisions
- Superseded lessons
- Obsolete anti-patterns
- Old version references
- Changed best practices

### Task 3: Verify Cross-References
**Ensure all REF-IDs are valid.**

Check:
- REF-IDs point to existing entries
- Related topics are accurate
- Cross-reference matrix is current
- No broken links

### Task 4: Format Migrations
**Update entries to current standards.**

Fix:
- Missing headers
- Wrong encoding
- Exceeds 400 lines
- Improper naming
- Missing fields

### Task 5: Remove Deprecated
**Clean up obsolete knowledge.**

Process:
- Mark entry as [DEPRECATED]
- Note replacement entry
- Update indexes
- Preserve for historical reference

---

## MAINTENANCE WORKFLOWS

### Workflow 1: Update Index

```
1. Identify index to update
   "Updating [category] index..."

2. Fetch current index (fileserver.php)
   Use cache-busted URL

3. Scan category directory
   Find all entries in category

4. Compare with index
   Missing entries?
   Extra entries?

5. Update index
   Add missing entries
   Remove deleted entries
   Sort correctly

6. Output as artifact
   Complete updated index
   Filename in header
   "Index updated. Added [X], removed [Y]."
```

### Workflow 2: Deprecate Entry

```
1. Identify entry to deprecate
   "Deprecating [TYPE-##]..."

2. Fetch entry (fileserver.php)
   Read current content

3. Mark as deprecated
   Add [DEPRECATED] to title
   Add deprecation notice
   Note replacement entry

4. Update indexes
   Mark in indexes
   Add note to matrix

5. Output artifacts
   Updated entry
   Updated indexes
   "Entry deprecated. Replacement: [TYPE-##]."
```

### Workflow 3: Verify References

```
1. Select entry to verify
   "Verifying [TYPE-##] references..."

2. Extract REF-IDs
   Find all Related: references
   Find all REF: citations

3. Verify each exists (fileserver.php)
   Fetch referenced entries
   Check they exist

4. Report broken references
   List invalid REF-IDs
   Suggest corrections

5. Fix if requested
   Update entry with valid REF-IDs
   Output as artifact
```

### Workflow 4: Format Migration

```
1. Identify non-compliant entry
   "Migrating [TYPE-##] to current format..."

2. Fetch entry (fileserver.php)
   Read current content

3. Fix issues
   Add missing header
   Fix encoding
   Split if >400 lines
   Correct naming
   Add required fields

4. Output as artifact
   Complete migrated entry
   Follows all standards
   "Migration complete."
```

---

## ARTIFACT RULES

**MANDATORY for all maintenance outputs:**

### Output Format
```
[OK] Updated indexes -> Artifacts
[OK] Migrated entries -> Artifacts
[OK] Complete files only
[OK] Filename in header
[X] Never partial updates
[X] Never output in chat
```

### Chat Output
```
[OK] Brief status: "Updating index..."
[OK] Summary: "Updated [X]. Added [Y], removed [Z]."
[X] Long explanations
```

**Complete Standards:** `/sima/shared/Artifact-Standards.md`

---

## QUALITY CHECKS

**Before completing maintenance:**

1. âœ… All changes in artifacts
2. âœ… Indexes updated
3. âœ… Cross-references valid
4. âœ… Format compliance
5. âœ… No broken links
6. âœ… Deprecated marked
7. âœ… Headers complete
8. âœ… Files ≤400 lines
9. âœ… Chat minimal
10. âœ… Changes documented

---

## COMMON ISSUES

### Issue 1: Missing from Index
**Entry exists but not in index.**

Fix: Add to index, output updated index.

### Issue 2: Broken REF-ID
**Reference points to non-existent entry.**

Fix: Find correct entry or remove reference.

### Issue 3: Over 400 Lines
**Entry exceeds line limit.**

Fix: Split into multiple focused files.

### Issue 4: Missing Header
**Entry lacks required header fields.**

Fix: Add complete header with all required fields.

### Issue 5: Deprecated but Not Marked
**Obsolete entry still active.**

Fix: Mark [DEPRECATED], note replacement.

---

## MAINTENANCE SCHEDULE

**Recommended:**
- Weekly: Update indexes
- Monthly: Verify references
- Quarterly: Check for outdated
- Yearly: Format migrations

---

## SUCCESS METRICS

**Effective maintenance when:**
- âœ… All indexes current
- âœ… No broken references
- âœ… All entries compliant
- âœ… Deprecated marked
- âœ… Files ≤400 lines
- âœ… Chat minimal
- âœ… Changes tracked

---

## READY

**Context loaded when you remember:**
- âœ… fileserver.php fetched (automatic)
- âœ… 5 maintenance tasks
- âœ… 4 workflows
- âœ… Quality checks (10 items)
- âœ… Common issues (5 types)
- âœ… Artifacts ONLY (never chat)
- âœ… Brief status updates

**Now ready to maintain SIMA knowledge!**

---

**END OF MAINTENANCE MODE CONTEXT**

**Version:** 1.0.0 (New mode)  
**Lines:** 200 (target achieved)  
**Load time:** 20-30 seconds  
**Purpose:** Keep SIMA knowledge clean and current  
**Output:** Updated indexes, migrated entries, verified references

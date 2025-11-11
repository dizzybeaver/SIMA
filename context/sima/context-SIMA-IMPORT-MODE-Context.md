# context-SIMA-IMPORT-MODE-Context.md

**Version:** 4.2.2-blank  
**Date:** 2025-11-10  
**Purpose:** Import knowledge from other SIMA instances  
**Activation:** "Start SIMA Import Mode"  
**Load time:** 20-30 seconds  
**Type:** SIMA Mode

---

## EXTENDS

[context-SIMA-BASE-MODE.md](context-SIMA-BASE-MODE.md) (Base context)

---

## WHAT THIS MODE IS

**Import Mode** integrates knowledge from exports into current SIMA instance.

**Purpose:** Merge external knowledge while checking for duplicates and conflicts.

**Outputs:** Integrated knowledge, updated indexes, conflict reports.

---

## IMPORT PROCESS

### Step 1: Load Export Package
**Read export bundle:**
- Verify manifest.yaml
- Check version compatibility
- Review scope and contents
- Validate checksums

### Step 2: Check Duplicates
**For each file:**
- Search for similar content (fileserver.php)
- Compare REF-IDs
- Identify conflicts
- Determine merge strategy

### Step 3: Resolve Conflicts
**Handle conflicts:**
- REF-ID collision → Renumber
- Content duplicate → Skip or merge
- Dependency missing → Flag for resolution
- Version mismatch → Convert format

### Step 4: Integrate Files
**Import knowledge:**
- Place in correct directories
- Update REF-IDs if renumbered
- Fix cross-references
- Maintain relationships

### Step 5: Update System
**Post-import tasks:**
- Regenerate indexes
- Verify all links
- Update master indexes
- Generate import report

---

## IMPORT WORKFLOWS

### Workflow 1: Full Import

**Process:**
```
1. Load export package
2. Validate manifest
3. Check all files for duplicates (fileserver.php)
4. Resolve conflicts
5. Import all files
6. Update all indexes
7. Generate report
```

### Workflow 2: Selective Import

**Process:**
```
1. Load export package
2. User specifies files/categories
3. Check selected for duplicates (fileserver.php)
4. Resolve conflicts
5. Import selected only
6. Update relevant indexes
7. Generate report
```

### Workflow 3: Merge Import

**Process:**
```
1. Load export package
2. Check for duplicates (fileserver.php)
3. For duplicates:
   - Compare content
   - Merge if beneficial
   - Skip if identical
4. Import new only
5. Update merged entries
6. Update indexes
7. Generate report
```

---

## CONFLICT RESOLUTION

### REF-ID Collision
**Same REF-ID, different content:**
```
1. Check if content identical → Skip
2. If different:
   - Assign new REF-ID to import
   - Update all references in import
   - Document mapping in report
```

### Content Duplicate
**Different REF-ID, same/similar content:**
```
1. Compare content quality
2. Keep better version
3. Update cross-references
4. Document decision
```

### Dependency Missing
**Import requires non-existent REF-ID:**
```
1. Flag dependency
2. User decides:
   - Skip file
   - Import with broken link
   - Import dependency first
3. Document resolution
```

---

## ARTIFACT RULES

**Import outputs:**

```
[OK] Integrated files - Complete entries
[OK] Updated indexes - All affected indexes
[OK] Import report - Conflicts and resolutions
[OK] Mapping document - REF-ID changes
[X] Never partial imports
[X] Never unresolved conflicts
```

---

## QUALITY CHECKLIST

**Before import:**
1. ✅ Export package validated
2. ✅ Manifest checked
3. ✅ Version compatible
4. ✅ Checksums verified
5. ✅ Duplicates scanned (fileserver.php)

**During import:**
6. ✅ All conflicts resolved
7. ✅ REF-IDs mapped
8. ✅ Files placed correctly
9. ✅ References updated
10. ✅ No broken links

**After import:**
11. ✅ All indexes updated
12. ✅ Cross-references valid
13. ✅ Import report generated
14. ✅ System verified
15. ✅ Ready to use

---

## SUCCESS METRICS

**Successful import when:**
- ✅ All selected files integrated
- ✅ Zero conflicts unresolved
- ✅ All indexes updated
- ✅ All links valid
- ✅ Complete audit trail
- ✅ System functional

---

## READY

**Context loaded when you remember:**
- ✅ fileserver.php fetched (automatic)
- ✅ Import process (5 steps)
- ✅ 3 import workflows
- ✅ Conflict resolution strategies
- ✅ Quality checklist

**Now ready to import knowledge!**

---

**END OF IMPORT MODE CONTEXT**

**Version:** 4.2.2-blank  
**Lines:** 200 (target achieved)  
**Purpose:** Integrate external knowledge
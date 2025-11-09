# DEBUG-MODE-SIMA.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** SIMA project-specific debugging context  
**Project:** SIMA (Structured Intelligence Memory Architecture)  
**Type:** Project Extension

---

## PROJECT: SIMA

**Name:** SIMA (Structured Intelligence Memory Architecture)  
**Type:** Knowledge Management System

---

## KNOWN ISSUES

### Issue 1: Entry in Wrong Domain
**Symptom:** Generic knowledge in project directory  
**Root Cause:** Incorrect classification  
**Fix:** Move to /entries/ directory  
**Prevention:** Check classification before creating

### Issue 2: Broken Cross-References
**Symptom:** REF-ID points to non-existent entry  
**Root Cause:** Entry moved or deleted  
**Fix:** Update REF-ID or remove reference  
**Prevention:** Update references when moving entries

### Issue 3: Missing Index Entry
**Symptom:** Entry exists but not in index  
**Root Cause:** Index not updated after entry creation  
**Fix:** Add entry to appropriate index  
**Prevention:** Update indexes after every entry

### Issue 4: File Too Large
**Symptom:** Entry exceeds 400 lines  
**Root Cause:** Too much content in single file  
**Fix:** Split into multiple focused files  
**Prevention:** Check line count before completing

### Issue 5: Duplicate Content
**Symptom:** Two entries cover same topic  
**Root Cause:** Didn't check for duplicates  
**Fix:** Merge entries, keep best, mark other deprecated  
**Prevention:** Always search before creating

---

## ERROR PATTERNS

### Entry Not Found
**Message:** "Cannot locate [TYPE-##]"  
**Cause:** Entry in wrong domain or deleted  
**Investigation:**
1. Check correct domain (/entries/, /platforms/, /languages/, /projects/)
2. Search via fileserver.php
3. Check if deprecated
4. Verify spelling

### Broken REF-ID
**Message:** "Reference [TYPE-##] not found"  
**Cause:** Entry moved or deleted  
**Investigation:**
1. Fetch via fileserver.php
2. Search for entry
3. Check if renamed
4. Look for replacement

### Index Out of Date
**Message:** "Entry missing from index"  
**Cause:** Index not updated  
**Investigation:**
1. Scan directory for entries
2. Compare with index
3. Identify missing entries
4. Update index

### File Too Large
**Message:** "File exceeds 400 lines"  
**Cause:** Too much content  
**Investigation:**
1. Count lines
2. Identify logical breakpoints
3. Plan split strategy
4. Create multiple files

---

## DEBUG TOOLS

### validate-migration.py
**Purpose:** Validate structure compliance  
**Usage:**
```python
python validate-migration.py /sima
```
**Checks:** Directories, paths, file sizes

### cross-reference-checker.py
**Purpose:** Verify REF-IDs valid  
**Usage:**
```python
python cross-reference-checker.py
```
**Output:** List of broken references

### index-generator.py
**Purpose:** Generate/update indexes  
**Usage:**
```python
python index-generator.py /sima/entries/lessons/
```
**Output:** Updated index file

### duplicate-detector.py
**Purpose:** Find duplicate content  
**Usage:**
```python
python duplicate-detector.py
```
**Output:** Similar entries report

---

## COMMON FIXES

### Move to Correct Domain
```bash
# Entry in wrong place
mv /projects/lee/generic-lesson.md /entries/lessons/
# Update indexes
```

### Fix Broken REF-ID
```markdown
# Before
**REF:** LESS-99 (doesn't exist)

# After
**REF:** LESS-15 (correct)
```

### Add to Index
```markdown
# Add missing entry
- [LESS-15 - Verification](/entries/lessons/LESS-15.md)
```

### Split Large File
```markdown
# Original: LESS-15.md (500 lines)

# Split into:
# LESS-15.md (250 lines) - Core concept
# LESS-15-Examples.md (250 lines) - Examples
```

### Merge Duplicates
```markdown
# LESS-20 and LESS-45 are duplicates
# Keep LESS-20, deprecate LESS-45

# LESS-45.md
# [DEPRECATED] - See LESS-20 instead
```

---

## MAINTENANCE TASKS

### Weekly
- Update all category indexes
- Check for broken references
- Verify new entries added

### Monthly
- Run duplicate detector
- Validate file sizes
- Check cross-reference matrix

### Quarterly
- Review deprecated entries
- Update master indexes
- Verify domain organization

---

**END OF SIMA DEBUG EXTENSION**

**Version:** 1.0.0  
**Lines:** 100 (target achieved)  
**Combine with:** DEBUG-MODE-Context.md (base)

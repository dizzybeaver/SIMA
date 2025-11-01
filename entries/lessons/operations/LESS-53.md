# LESS-53.md

# LESS-53: File Version Incrementation Protocol

**Category:** Lessons  
**Topic:** Operations  
**Priority:** CRITICAL  
**Status:** Active  
**Created:** 2025-10-30  
**Path:** `/sima/entries/lessons/operations/LESS-53.md`

---

## Summary

ALWAYS increment file version numbers on EVERY modification, even minor changes. This enables instant cache detection and deployment verification.

---

## Pattern

### The Problem

**Without Version Incrementation:**
```
File Server: myfile.py v2025.10.22.02 (updated)
Your Cache:  myfile.py v2025.10.22.02 (stale)
Result: ❌ NO WAY to detect cache issue
```

**With Version Incrementation:**
```
File Server: myfile.py v2025.10.22.03
Your Cache:  myfile.py v2025.10.22.01
Result: ⚠️ IMMEDIATE detection of version mismatch
```

---

## Solution

### Version Format

```
Version: YYYY.MM.DD.RR
         │    │  │  └─ Revision (01, 02, 03...)
         │    │  └──── Day
         │    └─────── Month  
         └──────────── Year
```

**Example:**
```
v2025.10.30.01 → First revision on Oct 30, 2025
v2025.10.30.02 → Second revision on Oct 30, 2025
v2025.10.31.01 → First revision on Oct 31, 2025
```

### Critical Rules

**Rule 1: Independent Versioning**
```
Each file has its OWN version number
- gateway.py can be v2025.10.30.04
- helper.py can be v2025.10.30.02
- core.py can be v2025.10.29.05

Files change independently, versions reflect that
```

**Rule 2: Always Increment**
```
ANY change = increment revision number
- One-line fix? Increment.
- Comment update? Increment.
- Whitespace change? Increment.
- Documentation update? Increment.
```

**Rule 3: Never Reuse**
```
If v2025.10.30.03 has a bug:
âœ… Create v2025.10.30.04 (fix applied)
âŒ Overwrite v2025.10.30.03 (breaks history)

History must be linear and traceable
```

### Implementation

**File Header Template:**
```python
"""
Module: mymodule.py
Version: 2025.10.30.01
Description: Brief module description
"""
```

**Version Update:**
```python
# Before fix
"""
Version: 2025.10.30.03
Description: Cache operations
"""

# After fix
"""
Version: 2025.10.30.04  # ← Incremented
Description: Cache operations (fixed timeout handling)
"""
```

---

## Impact

### Cache Detection

**With Proper Versioning:**
```
User: "Import failing unexpectedly"
You: *checks file version*
You: "⚠️ File server shows v2025.10.30.03 but I have v2025.10.29.02"
User: "Cache issue confirmed, here's the real file"
Time to detect: 30 seconds
```

**Without Versioning:**
```
User: "Import failing unexpectedly"
You: *checks file version*
You: "File shows v2025.10.30.01"
User: "That's the same version I see"
You: *invisible cache problem wastes 30 minutes debugging*
Time to waste: 30+ minutes
```

### Deployment Verification

**With Versioning:**
```
Deploy package with:
- gateway.py v2025.10.30.04
- helper.py v2025.10.30.02
- core.py v2025.10.29.05

After deployment, verify:
✅ gateway.py shows v2025.10.30.04
✅ helper.py shows v2025.10.30.02
✅ core.py shows v2025.10.29.05

Confidence: 100% correct files deployed
```

**Without Versioning:**
```
Deploy package...
Verify deployment...
Hope correct files deployed?

Confidence: Unknown
```

### Regression Tracking

**With Versioning:**
```
Issue appeared after recent deployment
Check versions:
- gateway.py v2025.10.30.04 (introduced regression)
- gateway.py v2025.10.30.03 (worked correctly)

Identify: Regression in v2025.10.30.04
Action: Review changes between .03 and .04
Time to identify: Minutes
```

**Without Versioning:**
```
Issue appeared... when?
Which file changed?
What changed in that file?
When did it work?

Time to identify: Hours or days
```

---

## Best Practices

### Refactoring Example

**Correct:**
```python
# helper.py
Version: 2025.10.30.02  # Refactored into modules

# helper_http.py  
Version: 2025.10.30.01  # New file, first version

# gateway.py
Version: 2025.10.30.03  # Updated imports for refactoring
```

**Incorrect:**
```python
# All files
Version: 2025.10.30.01  # Same version everywhere
# Can't tell which files actually changed!
```

### Version Comments

**Good:**
```python
"""
Version: 2025.10.30.04
Changes: Fixed timeout handling in cache operations
Previous: v2025.10.30.03
"""
```

**Better:**
```python
"""
Version: 2025.10.30.04
Changes:
- Fixed timeout handling (was 30s, now 60s)
- Added error logging for timeout events
- Updated tests for new timeout value
Previous: v2025.10.30.03
"""
```

### Version Tracking

**Maintain changelog:**
```markdown
## gateway.py

### v2025.10.30.04
- Fixed cache timeout handling
- Added error logging

### v2025.10.30.03
- Refactored imports after helper split

### v2025.10.30.02
- Added new gateway functions
```

---

## Time Investment vs Benefit

**Cost of Following Protocol:**
```
Time to increment version: 5 seconds
Time to add version comment: 15 seconds
Total per change: 20 seconds
```

**Cost of NOT Following:**
```
Invisible cache issues: 30-60 minutes debugging
Wrong deployment: 15-30 minutes to identify and fix
Regression mystery: Hours to track down
Cannot verify deployment: Loss of confidence
```

**ROI:** 20 seconds prevents hours of problems

---

## Integration with Workflow

### Before Committing

```bash
# Checklist before commit
☑ Incremented version number
☑ Updated version comment
☑ Documented changes
☑ Tested with new version
☑ Ready to commit
```

### During Code Review

```markdown
## Review Checklist
- [ ] Version incremented?
- [ ] Version comment updated?
- [ ] Changes documented?
- [ ] Changelog updated?
```

### In CI/CD

```bash
# Verify version incremented
if [ "$NEW_VERSION" == "$OLD_VERSION" ]; then
    echo "ERROR: Version not incremented"
    exit 1
fi
```

---

## Anti-Patterns to Avoid

**âŒ Reusing Version Numbers**
```
v2025.10.30.03 (buggy)
Fix bug...
v2025.10.30.03 (fixed)  # âŒ WRONG - same version!
```

**âŒ Not Incrementing for "Minor" Changes**
```
"It's just a comment, no need to increment"
"It's just whitespace, no need to increment"
âŒ WRONG - ANY change = increment
```

**âŒ Same Version Across Multiple Files**
```
All files: v2025.10.30.01
âŒ Can't tell which files actually changed
```

**âŒ Skipping Version Comments**
```
Version: 2025.10.30.04
# No description of what changed
âŒ Future debugging nightmare
```

---

## Key Insight

**Version numbers are not bureaucracy—they're insurance.**

20 seconds per change prevents:
- Hours debugging invisible cache issues
- Deployment confusion and rollbacks
- Regression investigation mysteries
- Loss of confidence in deployment

Every version increment is a checkpoint enabling fast problem identification.

---

## Related Topics

- **LESS-09**: Atomic Deployment (verify versions match)
- **LESS-15**: File Verification (includes version check)
- **Deployment Safety**: Version-based verification
- **Cache Management**: Version-based invalidation

---

## Keywords

versioning, deployment, cache-detection, file-management, version-control, regression-tracking, deployment-verification

---

## Version History

- **2025-10-30**: Genericized for SIMAv4 - Removed project-specific details
- **2025-10-22**: Created - Documented versioning protocol

---

**File:** `LESS-53.md`  
**Location:** `/sima/entries/lessons/operations/`  
**Status:** Active

---

**End of Document**

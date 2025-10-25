# NM06-Lessons-Operations_LESS-53.md

# File Version Incrementation Protocol

**REF:** NM06-LESS-53  
**Category:** Lessons  
**Topic:** Operations  
**Priority:** 🔴 CRITICAL  
**Status:** Active  
**Created:** 2025-10-22  
**Last Updated:** 2025-10-24

---

## Summary

ALWAYS increment file version numbers on EVERY modification, even minor changes. This enables instant cache detection and deployment verification.

---

## Context

**The Problem It Solves:**
1. **Cache Detection** - Instant visibility when cached version is stale
2. **Deployment Verification** - Know exactly which version deployed
3. **Regression Tracking** - Identify which revision introduced issues
4. **Multi-File Coordination** - Each file tracks its own change history

---

## Content

### Version Format

```
Version: YYYY.MM.DD.RR
         │    │  │  └─ Revision (01, 02, 03...)
         │    │  └──── Day
         │    └─────── Month  
         └──────────── Year
```

### Why This Matters

**With Incrementation:**
```
File Server: gateway.py v2025.10.22.03
My Cache:    gateway.py v2025.10.22.01
Result: ⚠️ IMMEDIATE detection of version mismatch
```

**Without Incrementation:**
```
File Server: gateway.py v2025.10.22.02 (updated)
My Cache:    gateway.py v2025.10.22.02 (stale)
Result: ❌ NO WAY to detect cache issue
```

### Critical Rules

**Rule 1: Independent Versioning**
- Each file has its OWN version number
- gateway.py can be v2025.10.22.04 while gateway_wrappers.py is v2025.10.22.02
- Files change independently, versions reflect that

**Rule 2: Always Increment**
- ANY change = increment revision number
- One-line fix? Increment.
- Comment update? Increment.
- CHANGELOG entry? Increment.

**Rule 3: Never Reuse**
- If v2025.10.22.03 has a bug, create v2025.10.22.04
- Never overwrite v2025.10.22.03 with fixed version
- History must be linear and traceable

### Example: Refactoring

**Correct:**
```python
# gateway_wrappers.py
Version: 2025.10.22.02  # Refactored into modules

# gateway_wrappers_http_client.py  
Version: 2025.10.22.01  # New file, first version

# gateway.py
Version: 2025.10.22.03  # Updated imports for refactoring
```

**Incorrect:**
```python
# All files
Version: 2025.10.22.01  # Same version everywhere
# Can't tell which files actually changed!
```

### When Cache Issues Occur

**With proper versioning:**
```
User: "Lambda failing to import"
Claude: *checks file version*
Claude: "⚠️ File server shows v2025.10.22.03 but I fetched v2025.10.21.02"
User: "Cache issue confirmed, here's the real file"
```

**Without versioning:**
```
User: "Lambda failing to import"  
Claude: *checks file version*
Claude: "File shows v2025.10.22.01"
User: "That's the same version I see"
Claude: *invisible cache problem wastes 30 minutes debugging*
```

### Impact

**Cost of skipping:** Invisible cache issues, deployment confusion, regression mysteries  
**Cost of following:** 5 seconds to increment version number  
**Savings:** Hours of debugging invisible problems

---

## Related Topics

- **LESS-15**: File verification protocol (verify # EOF marker)
- **LESS-09**: Atomic deployment (deploy related files together)
- **LESS-01**: Read complete files first (includes version check)

---

## Keywords

versioning, deployment, cache-detection, file-management, version-control, regression-tracking

---

## Version History

- **2025-10-24**: Migrated to SIMA v3 individual file format (from standalone)
- **2025-10-22**: Original documentation as standalone lesson

---

**File:** `NM06-Lessons-Operations_LESS-53.md`  
**Status:** ✅ Active Protocol  
**Applies to:** All Python source files in SUGA-ISP project

---

**End of Document**

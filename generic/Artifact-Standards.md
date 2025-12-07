# Artifact-Standards.md

**Version:** 2.0.0  
**Date:** 2025-12-07  
**Purpose:** Standards for all code artifacts  
**Location:** `/sima/shared/`

---

## CORE PRINCIPLE

**Code always in artifacts. Chat always minimal.**

---

## WHEN TO CREATE ARTIFACTS

**Mandatory:**
```
Code > 20 lines         → Artifact
Any file modification   → Artifact
Configuration files     → Artifact
Templates               → Artifact
Scripts                 → Artifact
```

**Never:**
```
❌ Code in chat
❌ Code snippets in chat
❌ "Add this code" instructions
❌ Partial file outputs
```

---

## ARTIFACT REQUIREMENTS

### Complete Files Only

**Always include:**
- ✅ ALL existing code
- ✅ ALL imports
- ✅ ALL functions
- ✅ ALL classes
- ✅ Complete from line 1 to EOF

**Never:**
- ❌ Partial files
- ❌ "Add to line X" instructions
- ❌ Snippets
- ❌ Fragments
- ❌ Diffs without context

### Mark Changes

**Change Markers:**
```python
# ADDED: Description of new feature
def new_function():
    """New functionality."""
    pass

# MODIFIED: Description of change
def existing_function():
    # ADDED: Validation
    if not valid:
        raise ValueError()
    # Original logic continues
    
# FIXED: Description of bug fix
def buggy_function():
    # FIXED: Off-by-one error
    return range(len(items))  # Was: range(len(items) - 1)
```

**Marker Types:**
- `# ADDED:` - New code
- `# MODIFIED:` - Changed code
- `# FIXED:` - Bug fixes
- `# REMOVED:` - Deletions (with comment explaining)

### File Headers

**Code Files (Minimal):**
```python
"""
filename.py
Version: 2025-12-06_1
Purpose: One-line description
License: Apache 2.0
"""
```

**Key Changes from v1.0:**
- ❌ No multi-line changelogs
- ❌ No verbose descriptions
- ❌ No change history
- ✅ Minimal 5-line header
- ✅ Date-based version format
- ✅ Changelog in companion .md

### Size Limits

**All Artifacts:**
- Maximum: 350 lines (hard limit)
- Warning: 300 lines (plan split)
- Target: 250-300 lines (optimal)

**If file >350 lines:**
1. Split into logical modules
2. Create separate artifacts
3. Update imports/references
4. Test integration

---

## FILE STRUCTURE

### Code + Documentation Pattern

**For Every Code File:**
```
filename.py              (≤350 lines) - Implementation
filename.md              (≤350 lines) - Documentation
```

**Example:**
```
ha_alexa_core.py         (280 lines) - Code only
ha_alexa_core.md         (300 lines) - Full docs + changelog
```

**Benefits:**
- Code stays clean and focused
- Documentation comprehensive
- Both files Claude-visible
- Easier maintenance

---

## ARTIFACT CREATION WORKFLOW

### Step 1: Fetch Current Version

**Always:**
```
1. Use fileserver.php URLs
2. Read ENTIRE file
3. Understand current structure
4. Note existing functionality
```

**Never:**
```
❌ Skip file fetch
❌ Use cached/old version
❌ Assume file contents
```

### Step 2: Implement Changes

**Process:**
```
1. Keep ALL existing code
2. Add new functionality
3. Mark changes with comments
4. Maintain code structure
5. Follow project patterns
```

### Step 3: Create Artifact

**Include:**
```
✅ Minimal header
✅ Complete file
✅ Marked changes
✅ All imports
✅ All functions
✅ Proper formatting
```

### Step 4: Brief Chat Output

**Good:**
```
"Modified ha_alexa_core.py. Added state enrichment. Ready."
```

**Bad:**
```
❌ "I've added a new function called enrich_response_with_state
   which takes four parameters and does the following..."
   [Long explanation continues]
```

---

## HEADER FORMAT

### Python Files

**New Format (v2.0):**
```python
"""
filename.py
Version: 2025-12-06_1
Purpose: One-line description
License: Apache 2.0
"""
```

**Old Format (deprecated):**
```python
"""
filename.py - Long Description (INT-XX-XX)
Version: 4.3.0
Date: 2025-12-06
Description: Multi-line description

CHANGES (4.3.0 - TITLE):
- ADDED: Long list of changes
- MODIFIED: More changes
- FIXED: Even more changes

CHANGES (4.2.0 - TITLE):
[... more history ...]

Copyright 2025 Author
Licensed under Apache 2.0
"""
```

**Line Reduction:** 35+ lines → 5 lines

### JavaScript Files

```javascript
/**
 * filename.js
 * Version: 2025-12-06_1
 * Purpose: One-line description
 * License: MIT
 */
```

### Configuration Files

**YAML:**
```yaml
# filename.yaml
# Version: 2025-12-06_1
# Purpose: One-line description
```

**JSON:**
```json
{
  "_comment": "filename.json - Version: 2025-12-06_1",
  ...
}
```

---

## MARKING CHANGES

### Examples

**New Function:**
```python
# ADDED: OAuth token extraction from directive
def extract_oauth_token(directive: Dict) -> Optional[str]:
    """Extract OAuth token from Alexa directive."""
    try:
        return directive['payload']['scope']['token']
    except (KeyError, TypeError):
        return None
```

**Modified Function:**
```python
# MODIFIED: Accept oauth_token parameter instead of loading from config
def call_ha_api(entity_id: str, action: str, oauth_token: str) -> Dict:
    """Call Home Assistant API with OAuth token."""
    # REMOVED: token = load_from_config()
    # ADDED: Use oauth_token parameter
    headers = {'Authorization': f'Bearer {oauth_token}'}
    return requests.post(url, headers=headers)
```

**Bug Fix:**
```python
# FIXED: Cache key sanitization (BUG-05)
def sanitize_cache_key(key: str) -> str:
    """Sanitize cache key for safe storage."""
    # FIXED: Replace all special chars, not just spaces
    return re.sub(r'[^a-zA-Z0-9_-]', '_', key)  # Was: key.replace(' ', '_')
```

---

## CHAT OUTPUT RULES

### Keep Minimal

**Good Examples:**
```
"Creating artifact..."
"Fix complete. cache_core.py ready."
"Modified 3 files. All artifacts ready."
```

**Bad Examples:**
```
❌ "I've analyzed the code and determined that..."
❌ "Here's what I changed in detail..."
❌ [Long explanations of changes]
❌ [Code snippets in chat]
```

### What to Say

**Status updates:**
- "Creating artifact..."
- "Artifact ready."
- "Fix complete."

**Brief summaries:**
- "Modified X. Added Y. Ready."
- "Fixed bug. Test with original case."
- "Created 3 files. Ready to deploy."

**Never:**
- Long explanations
- Code in chat
- Verbose commentary
- Narrative descriptions

---

## QUALITY CHECKLIST

**Before outputting artifact:**

**Content:**
- [ ] Complete file (not fragment)?
- [ ] All existing code included?
- [ ] Changes marked clearly?
- [ ] Imports complete?
- [ ] Functions complete?

**Format:**
- [ ] Minimal header (≤5 lines)?
- [ ] Date-based version (YYYY-MM-DD_R)?
- [ ] No changelog in header?
- [ ] Proper indentation?
- [ ] No trailing whitespace?

**Size:**
- [ ] File ≤350 lines?
- [ ] Split if >350 lines?
- [ ] Companion .md exists (for code)?

**Output:**
- [ ] Filename in artifact header?
- [ ] Chat output minimal?
- [ ] No code in chat?
- [ ] Ready to deploy?

---

## EXAMPLE: FILE MODIFICATION

### Process

**Step 1:** Fetch current file
```
fileserver.php URL
Read ENTIRE file (all 280 lines)
```

**Step 2:** Implement changes
```python
"""
ha_alexa_core.py
Version: 2025-12-06_2
Purpose: Alexa Smart Home integration core
License: Apache 2.0
"""

import json
from typing import Dict, Any, Optional

# ... existing imports ...

# ADDED: OAuth token extraction
def extract_oauth_token(directive: Dict) -> Optional[str]:
    """Extract OAuth token from Alexa directive."""
    try:
        return directive['payload']['scope']['token']
    except (KeyError, TypeError):
        return None

# MODIFIED: Accept oauth_token parameter
def handle_directive(directive: Dict, context: Dict, oauth_token: str) -> Dict:
    """Handle Alexa directive with OAuth token."""
    # ... implementation ...
```

**Step 3:** Output artifact
- Complete file
- ALL existing code
- Changes marked
- Ready to deploy

**Step 4:** Brief chat
```
"Modified ha_alexa_core.py. Added OAuth extraction. Ready."
```

---

## RELATED STANDARDS

**File Standards:**
- File-Standards.md - File organization and size
- Version-Standards.md - Version format details
- SPEC-FILE-STANDARDS.md - Universal standards
- SPEC-HEADERS.md - Header requirements

**Location:** `/sima/shared/`

---

## VERSION HISTORY

| Version | Date | Changes |
|---------|------|---------|
| 2.0.0 | 2025-12-07 | New header format, 350-line limit, companion docs |
| 1.0.0 | 2025-11-08 | Initial artifact standards |

---

**END OF FILE**

**Summary:** All code in complete file artifacts, minimal headers (≤5 lines), marked changes, ≤350 lines, companion .md for docs, minimal chat.

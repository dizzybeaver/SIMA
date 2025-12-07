# File-Standards.md

**Version:** 2.0.0  
**Date:** 2025-12-07  
**Purpose:** File organization and size standards  
**Location:** `/sima/shared/`

---

## SIZE LIMITS

### Strict Limits

**All Files:**
- **Maximum:** 350 lines (hard limit)
- **Warning threshold:** 300 lines
- **Target range:** 250-300 lines
- **Action at 300 lines:** Plan file split
- **Action at 350 lines:** Split immediately

**Why 350 Lines:**
- Claude truncates files >400 lines (22% minimum loss)
- 350-line buffer prevents accidental truncation
- Files remain fully visible in project knowledge
- Easier to review and maintain

**Neural Maps:**
- Maximum: 350 lines
- Reason: project_knowledge_search truncation
- Critical: Files >350 lines partially inaccessible

**Source Code:**
- Maximum: 350 lines (enforced)
- Reason: Maintainability, Claude visibility
- Split into logical modules if exceeded

---

## CODE FILE STANDARDS

### File Size Management

**Hard Limits:**
- Maximum: 350 lines (hard stop)
- Warning: 300 lines (plan split)
- Target: 250-300 lines (optimal)

**Splitting Strategy:**

**When to Split:**
1. File reaches 300 lines → Plan split
2. File reaches 350 lines → Split immediately
3. File has distinct logical sections → Consider splitting even if <300 lines

**How to Split:**
```
# Example: ha_alexa_core.py (500 lines)
# Split into:

ha_alexa_core.py          (250 lines) - Main handlers
ha_alexa_helpers.py       (150 lines) - Helper functions
ha_alexa_properties.py    (100 lines) - Property builders
```

**Naming Conventions:**
- `{module}_core.py` - Primary implementation
- `{module}_helpers.py` - Helper functions
- `{module}_types.py` - Type definitions
- `{module}_config.py` - Configuration
- `{module}_validation.py` - Validation logic

### Comment Standards

**In Code Files (Minimal):**
- ✅ One-line function purpose
- ✅ Type hints (via annotations)
- ✅ Critical inline comments (why, not what)
- ✅ TODO/FIXME markers
- ❌ Multi-line docstrings (>3 lines)
- ❌ Implementation explanations
- ❌ Usage examples
- ❌ Changelogs
- ❌ Decision rationales
- ❌ Architecture explanations

**Example - Before:**
```python
def enrich_response(response, entity_id, oauth_token, correlation_id):
    """
    Enrich Alexa response with fresh device state.
    
    This function fetches the current state from Home Assistant
    and builds Alexa-compliant property objects that are injected
    into the response's context section.
    
    Args:
        response: Original HA response dictionary
        entity_id: Entity ID to fetch state for
        oauth_token: OAuth token for HA API
        correlation_id: Request correlation ID
        
    Returns:
        Response dictionary enriched with context.properties
    """
```

**Example - After:**
```python
def enrich_response(response: Dict, entity_id: str, 
                   oauth_token: str, correlation_id: str) -> Dict:
    """Enrich response with fresh state in context.properties."""
```

**Line Savings:** 18 lines → 2 lines

### Companion Documentation

**Structure:**
- Every `.py` file has a `.md` companion
- Companion file name: `{filename}.md`
- Companion file location: Same directory as code file
- Maximum size: 350 lines (same as code)

**Companion File Contents:**

**Header:**
```markdown
# filename.py

**Version:** 2025-12-06_1  
**Module:** Module name  
**Layer:** Architecture layer  
**Dependencies:** List of dependencies

---
```

**Required Sections:**
1. **Purpose** - What this module does
2. **Architecture** - How it fits in system
3. **Functions** - Detailed function documentation
4. **Usage Examples** - Code examples
5. **Error Handling** - Error scenarios
6. **Performance** - Timing expectations
7. **Changelog** - Version history

**Example Documentation:**
```markdown
## Functions

### enrich_response_with_state()

**Purpose:** Fetch fresh entity state and inject into response.

**Signature:**
```python
def enrich_response_with_state(
    response: Dict[str, Any],
    entity_id: str,
    oauth_token: str,
    correlation_id: str
) -> Dict[str, Any]
```

**Parameters:**
- `response` - Original response (modified in-place)
- `entity_id` - Normalized entity ID
- `oauth_token` - OAuth token for API
- `correlation_id` - Request correlation ID

**Returns:** Response with context.properties added/updated

**Behavior:**
1. Calls devices_get_by_id() to fetch state
2. Builds property objects
3. Injects into response
4. Returns enriched response

**Usage:**
```python
enriched = enrich_response_with_state(
    ha_response,
    'light.bedroom',
    oauth_token,
    correlation_id
)
```

**Performance:** ~80ms (includes HA API call)
```

---

## FILE HEADERS

### Code Files (Minimal)

**Python:**
```python
"""
filename.py
Version: YYYY-MM-DD_R
Purpose: One-line description
License: Apache 2.0
"""
```

**JavaScript:**
```javascript
/**
 * filename.js
 * Version: YYYY-MM-DD_R
 * Purpose: One-line description
 * License: MIT
 */
```

**Line Count:** ~5 lines (vs 35+ lines previously)

### Documentation Files

**Markdown:**
```markdown
# filename.md

**Version:** X.Y.Z  
**Date:** YYYY-MM-DD  
**Purpose:** Brief description  
**Category:** Category name
```

---

## VERSION FORMAT

### Code Files

**Format:** `YYYY-MM-DD_R`
- Date-based versioning
- R = daily revision number
- See: Version-Standards.md

**Examples:**
```
2025-12-06_1    # First revision
2025-12-06_2    # Second revision same day
2025-12-07_1    # New day, reset to 1
```

### Documentation Files

**Format:** `X.Y.Z`
- Semantic versioning
- MAJOR.MINOR.PATCH
- See: Version-Standards.md

---

## CHANGELOG LOCATION

### Code Files

**Do NOT include:**
- ❌ Changelogs in code file headers
- ❌ Version history comments
- ❌ Change descriptions

**Move to companion .md:**
- ✅ Detailed changelog
- ✅ Version history table
- ✅ Migration notes

### Documentation Files

**Small changes:**
- Header comment in file

**Major changes:**
- Version history section in file
- Separate CHANGELOG.md for directory

---

## SPLITTING FILES

### When to Split

**Triggers:**
- File exceeds 300 lines (plan)
- File exceeds 350 lines (immediate)
- Multiple distinct topics
- Unrelated functionality
- Complex navigation

### How to Split

**By Topic:**
```
Large-File.md (600 lines)
→ Topic-A.md (300 lines)
→ Topic-B.md (300 lines)
```

**By Category:**
```
All-Decisions.md (800 lines)
→ Architecture-Decisions.md (300 lines)
→ Technical-Decisions.md (300 lines)
→ Operational-Decisions.md (200 lines)
```

**By Function:**
```
utilities.py (500 lines)
→ utility_validation.py (200 lines)
→ utility_transformation.py (200 lines)
→ utility_formatting.py (100 lines)
```

---

## STRUCTURE STANDARDS

### Standard Sections

**Neural Map Files:**
1. Header (version, date, purpose)
2. Overview/Summary
3. Main Content
4. Examples (if applicable)
5. Related Items
6. References

**Source Files:**
1. Header (minimal docstring)
2. Imports
3. Constants
4. Functions/Classes
5. Main logic

**Companion Documentation:**
1. Header (module info)
2. Purpose
3. Architecture
4. Functions (detailed)
5. Usage examples
6. Error handling
7. Performance notes
8. Changelog

---

## NAMING CONVENTIONS

### File Naming

**Neural Maps:**
- `CATEGORY-##-Brief-Description.md`
- Example: `LESS-01-Read-Complete-Files.md`

**Source Code:**
- `category_type.py`
- Example: `cache_core.py`, `interface_cache.py`

**Companion Documentation:**
- `{code_filename}.md`
- Example: `cache_core.py` → `cache_core.md`

**Support Files:**
- `Purpose-Description.md`
- Example: `Quick-Reference-Card.md`

---

## QUALITY CHECKLIST

**Before finalizing any file:**

**Size:**
- [ ] File ≤350 lines
- [ ] If >300 lines, split planned
- [ ] Split completed if >350 lines

**Code Files:**
- [ ] Minimal header (≤5 lines)
- [ ] Companion .md file exists
- [ ] No verbose docstrings in code
- [ ] Changelog in .md, not .py
- [ ] Date-based version format

**Documentation Files:**
- [ ] Proper header format
- [ ] Semantic version format
- [ ] All sections present
- [ ] Examples included
- [ ] Cross-references valid

**All Files:**
- [ ] UTF-8 encoding
- [ ] LF line endings
- [ ] No trailing whitespace
- [ ] Final newline present
- [ ] Version format correct

---

## RELATED SPECIFICATIONS

**Complete Standards:**
- Version-Standards.md - Version format details
- Artifact-Standards.md - Artifact creation
- SPEC-FILE-STANDARDS.md - Universal file standards
- SPEC-HEADERS.md - Header requirements
- SPEC-LINE-LIMITS.md - Size enforcement
- SPEC-NAMING.md - Naming conventions

**Location:** `/sima/entries/specifications/`

---

## VERSION HISTORY

| Version | Date | Changes |
|---------|------|---------|
| 2.0.0 | 2025-12-07 | Added code file standards, 350-line limit, companion docs |
| 1.0.0 | 2025-11-08 | Initial file standards |

---

**END OF FILE**

**Summary:** All files ≤350 lines, code files minimal with companion .md docs, proper headers, split when exceeded, clear naming.

# LESS-XX: Always Verify File Headers Against Original
**REF:** NM06-LESS-XX  
**PRIORITY:** 🔴 CRITICAL  
**TAGS:** verification, headers, quality-control, mistakes  
**KEYWORDS:** header verification, file headers, format checking  
**RELATED:** LESS-01 (Read complete files), LESS-15 (Verification protocol)

---

## The Problem

**Symptom:** Claude writes custom headers that don't match project standards

**Impact:**
- Immediate visual indication of error
- Forces manual correction
- Breaks CI/CD pipelines if checked
- Indicates Claude didn't read original file
- Wastes developer time

**Frequency:** Common when AI writes from memory instead of reading file

---

## The Pattern

```
User provides file to modify
    ↓
Claude writes from memory/templates
    ↓
Header format doesn't match original
    ↓
User immediately knows: "This is wrong, redo it"
```

**Why It Happens:**
1. Claude uses generic templates
2. Doesn't read complete original file first
3. Relies on memory of "standard" format
4. Forgets project-specific conventions

---

## The Solution

### MANDATORY Protocol

**BEFORE modifying any file:**

1. **Read COMPLETE original file**
   ```
   ✅ Read entire file from project knowledge or GitHub
   ✅ Don't rely on memory
   ✅ Don't assume standard format
   ```

2. **Extract header block (lines 1-50)**
   ```python
   Standard elements to note:
   - Copyright block format
   - License block format  
   - Version format (YYYY.MM.DD.## vs other)
   - Description format
   - Changelog format
   - Any custom sections
   ```

3. **Document header format**
   ```
   Before modifying:
   "I see the header has:
   - 3-line description
   - Version: YYYY.MM.DD.## format
   - Changelog with LIFO structure
   - Full Apache 2.0 license
   - Copyright 2025 Joseph Hersey"
   ```

4. **Preserve EXACT header format**
   ```
   When writing modified file:
   - Copy header structure exactly
   - Match indentation/spacing
   - Preserve blank lines
   - Keep same order of sections
   - Only update changelog/version
   ```

5. **Self-verify before submitting**
   ```
   Final check:
   "Does my header match the original?"
   - Same copyright format? ✅
   - Same license format? ✅  
   - Same version format? ✅
   - Same section order? ✅
   ```

---

## Header Format Examples

### Standard Project Header

```python
"""
filename.py - Brief description
Version: YYYY.MM.DD.##
Description: Detailed description

CHANGELOG:
- YYYY.MM.DD.##: Recent change (full detail)
- YYYY.MM.DD.##: Older change (1-line)
- YYYY.MM.DD.##: Older change (1-line)
- YYYY.MM.DD.##: Older change (1-line)
- YYYY.MM.DD.##: Oldest kept (1-line)

Copyright 2025 Joseph Hersey

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
"""
```

### Project Header with Design Decision

```python
"""
filename.py - Brief description
Version: YYYY.MM.DD.##
Description: Detailed description

MAJOR DESIGN DECISION - Title:
==============================
Explanation of decision

WHY: Reasons
TRADE-OFFS: Pros and cons
REFERENCES: REF-IDs

CHANGELOG:
- YYYY.MM.DD.##: Recent (full)
- YYYY.MM.DD.##: Older (1-line)

Copyright 2025 Joseph Hersey
   [Full license block]
"""
```

---

## Red Flags (Header is Wrong)

### 🚨 Critical Errors

1. **Missing copyright block**
   ```python
   # ❌ WRONG - No copyright
   """
   filename.py
   Description: Does something
   """
   ```

2. **Wrong license format**
   ```python
   # ❌ WRONG - Shortened license
   """
   Copyright 2025
   Licensed under Apache 2.0
   """
   
   # ✅ CORRECT - Full license block
   """
   Copyright 2025 Joseph Hersey
   
      Licensed under the Apache License, Version 2.0...
      [Full text of license header]
   """
   ```

3. **Non-standard version format**
   ```python
   # ❌ WRONG formats
   Version: 1.0.0
   Version: v2.3.1
   Version: 2025-10-21
   
   # ✅ CORRECT format
   Version: 2025.10.21.01
   ```

4. **Missing or wrong changelog format**
   ```python
   # ❌ WRONG - No structure
   """
   Changes:
   - Did stuff
   - More stuff
   """
   
   # ✅ CORRECT - LIFO with dates
   """
   CHANGELOG:
   - 2025.10.21.01: Recent change (detail)
   - 2025.10.20.03: Older change (1-line)
   """
   ```

5. **Custom documentation format**
   ```python
   # ❌ WRONG - Made up format
   """
   ## filename.py
   
   ### Description
   Does something cool
   
   ### Changes
   * v1.0 - Initial
   """
   
   # ✅ CORRECT - Project format
   """
   filename.py - Brief description
   Version: 2025.10.21.01
   Description: Does something cool
   
   CHANGELOG:
   - 2025.10.21.01: Initial version
   """
   ```

---

## Verification Checklist

**Before submitting ANY file modification:**

```
Header Verification:
☐ Read original file completely
☐ Extracted header format (lines 1-50)
☐ Noted copyright format
☐ Noted license format (full vs short)
☐ Noted version format (YYYY.MM.DD.## ?)
☐ Noted changelog format (LIFO? max entries?)
☐ Noted custom sections (DESIGN DECISION? etc)
☐ Preserved ALL original header elements
☐ Only changed: version, changelog entry
☐ Verified copyright block matches exactly
☐ Verified license block matches exactly
☐ Verified indentation/spacing matches
☐ Self-check: "Does this look exactly like original?"
```

**If ANY checkbox fails: STOP and fix header first**

---

## Integration with LESS-15

Add to **LESS-15 5-Step Verification Protocol:**

### Step 1: Read Complete File (Enhanced)

```
1. Read Complete File
   ☐ Read entire current file
   ☐ Extract header format (NEW)
   ☐ Document header structure (NEW)
   ☐ Understand full context
```

### Before Submit: Header Verification (NEW)

```
6. Verify Header Format (NEW STEP)
   ☐ Copyright matches original
   ☐ License matches original
   ☐ Version format matches original
   ☐ Changelog format matches original
   ☐ All custom sections preserved
```

---

## Real-World Example

### ❌ What Went Wrong

```python
# User asks: "Update logging_manager.py with debug paths"

# Claude writes from memory:
"""
logging_manager.py

Core logging manager for Lambda

Version 1.0.1
- Added debug support
- Fixed bugs

MIT License
"""

# User sees immediately: "This header is wrong, start over"
```

**Why This Failed:**
1. Didn't read original file first
2. Used generic template from memory
3. Wrong license (MIT instead of Apache 2.0)
4. Wrong version format (1.0.1 instead of YYYY.MM.DD.##)
5. Missing copyright block
6. Missing full license text

### ✅ How to Do It Right

```python
# Step 1: Read original file
project_knowledge_search("logging_manager.py complete")

# Step 2: Extract header format
"""
Original header has:
- Version: YYYY.MM.DD.## format
- Full Apache 2.0 license block
- Copyright 2025 Joseph Hersey
- CHANGELOG with LIFO structure (max 5)
- Custom MAJOR DESIGN DECISION section
"""

# Step 3: Preserve exact format
"""
logging_manager.py - Core logging manager implementation
Version: 2025.10.21.01  ← Updated version
Description: LoggingCore class with template optimization

MAJOR DESIGN DECISION - Module-Level Singleton:
... ← Preserved custom section

CHANGELOG:
- 2025.10.21.01: Added DEBUG_MODE support  ← New entry, LIFO
- 2025.10.18.01: Fixed ErrorLogLevel enum
... ← Kept 4 older entries

Copyright 2025 Joseph Hersey  ← Exact match

   Licensed under the Apache License, Version 2.0...
   ← Full license block preserved
"""
```

---

## Common Mistakes

### Mistake 1: Assuming Standard Format

```
❌ "All Python files use the same header"
✅ "Let me check THIS file's header format"
```

### Mistake 2: Using Generic Template

```
❌ Uses built-in header template
✅ Copies header structure from original
```

### Mistake 3: Shortening License

```
❌ "Copyright 2025, Apache 2.0 License"
✅ [Full 15-line Apache 2.0 license block]
```

### Mistake 4: Not Updating Changelog

```
❌ Leaves old changelog unchanged
✅ Adds new entry using LIFO rules
```

### Mistake 5: Changing Format "To Be Consistent"

```
❌ "I'll standardize this header format"
✅ "I'll match the existing format exactly"
```

---

## Success Criteria

**You know you did it right when:**
- User doesn't mention header at all
- Header looks identical to original (except version/changelog)
- Copyright/license unchanged
- Format/indentation unchanged
- Only logical changes: version incremented, changelog updated

**You know you did it wrong when:**
- User says "The header is wrong"
- User says "Did you look at the original?"
- User asks you to redo it
- Visual inspection shows obvious differences

---

## Prevention > Cure

**Best Practice:**
Always read original file first, extract header, document format, then proceed with modifications.

**Time Investment:**
- Reading header: 10 seconds
- Extracting format: 10 seconds
- Preventing header redo: Saves 2-3 minutes

**ROI:** 10-15x time savings

---

## Related Lessons

- **LESS-01:** Read complete files before modifying (foundational)
- **LESS-15:** 5-step verification protocol (enhanced with this)
- **Documentation Standards:** How to format headers correctly
- **AP-25:** Undocumented decisions (related to missing headers)

---

## Enforcement

**This is now MANDATORY:**
- Part of LESS-15 verification protocol
- Required before ANY file modification
- Non-negotiable quality gate
- Prevents >90% of header errors

---

**END OF LESSON**

**Key Takeaway:** Always read the original file, extract the header format, and preserve it exactly. A few seconds of verification prevents minutes of rework.

# Workflow-11-FetchFiles.md
**Fetching Complete Files from File Server**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: Retrieve and modify code files correctly

---

## 🎯 TRIGGERS

- "Modify [filename].py"
- "Update [filename].py to add..."
- "Fix [filename].py"
- "Show me complete [filename].py"
- "Output complete [filename].py"

---

## ⚡ QUICK PROCESS (~58 seconds)

```
User wants code modification
    ↓
1. Identify filename (2 sec)
    ↓
2. Check for known issues (1 sec)
    ↓
3. Search context (5 sec) - OPTIONAL
    ↓
4. Fetch complete file (10 sec)
    ↓
5. Check patterns (5 sec)
    ↓
6. Modify complete file (30 sec)
    ↓
7. Return complete file (5 sec)
```

---

## 📋 DETAILED STEPS

### Step 1: Identify the File Needed (2 seconds)

**If filename provided:**
```
User: "Modify cache_core.py"
→ Filename: cache_core.py
```

**If concept provided:**
```
User: "Modify the cache system"
→ Search project knowledge: "cache implementation file"
→ Result: cache_core.py
```

---

### Step 2: Check for Known Issues (1 second)

**Known issue:**
```
Is filename == "gateway_core.py"?
  YES → Use Fallback Method (ask for manual paste)
  NO  → Continue to Step 3
```

**Fallback message:**
```
"I'll need the complete gateway_core.py file to make these changes.
This file has a temporary cache issue. Could you paste the complete
file from GitHub?"
```

---

### Step 3: Search for Context (5 seconds) - OPTIONAL

**Purpose:** Understand patterns and decisions

```python
# Search for architectural context
project_knowledge_search("[filename] architecture patterns")
```

**Example:**
```
project_knowledge_search("cache_core.py patterns sentinel")
→ Results: DEC-05 (sentinel sanitization), RULE-01 (imports)
→ Context: Apply sentinel sanitization, use gateway imports
```

**When to skip:** Simple modifications, time-sensitive requests

---

### Step 4: Fetch Complete File (10 seconds)

**Method:**
```python
web_fetch("https://claude.dizzybeaver.com/src/[filename].py")
```

**Expected result:**
- ✅ Complete file (header to EOF)
- ✅ All imports, classes, functions
- ✅ All exports
- ✅ No truncation

**Verify completeness:**
```
Check for:
[ ] Apache license header
[ ] Import statements
[ ] Function/class definitions
[ ] __all__ exports (if present)
[ ] # EOF marker at end
```

**Example:**
```python
web_fetch("https://claude.dizzybeaver.com/src/cache_core.py")
→ Returns: Complete 600-line file ✓
```

---

### Step 5: Check Patterns and Anti-Patterns (5 seconds)

**Quick checks:**

**RULE-01:** Cross-interface imports via gateway?
```python
# ✅ CORRECT
import gateway
value = gateway.cache_get(key)

# ❌ WRONG
from cache_core import get_value
```

**DEC-04:** No threading locks?
```python
# ✅ CORRECT
def operation():
    # Direct execution
    pass

# ❌ WRONG
lock = threading.Lock()
with lock:
    operation()
```

**AP-14:** No bare except?
```python
# ✅ CORRECT
except ValueError as e:
    handle(e)

# ❌ WRONG
except:
    pass
```

**DEC-05:** Sentinel sanitization? (if cache-related)
```python
# ✅ CORRECT
if gateway.cache_is_sentinel(result):
    return None

# ❌ WRONG
return result  # might leak sentinel
```

---

### Step 6: Modify Complete File (30 seconds)

**Process:**
1. Read complete file context
2. Make requested changes
3. Follow SUGA pattern (Gateway → Interface → Implementation)
4. Apply design decisions
5. Verify no anti-patterns introduced
6. Ensure complete file structure maintained

**Verification checklist (LESS-15):**
- [ ] Read complete file first
- [ ] Verified SUGA pattern compliance
- [ ] Checked anti-patterns (AP-01, AP-08, AP-14, etc.)
- [ ] Verified dependencies (no circular imports)
- [ ] Complete file returned (header to EOF)

---

### Step 7: Return Complete Modified File (5 seconds)

**Create artifact with:**
1. Complete modified file
2. Verification notes
3. REF-ID citations
4. What changed summary

**Artifact format:**
```python
"""
[filename].py - [Description]
Version: [version]
Description: [what this file does]

MODIFICATIONS (2025-10-24):
- Added: batch_get() function
- Modified: Imports to include batch operations
- Applied: DEC-05 (sentinel sanitization)
- Verified: LESS-15 protocol completed

[Complete Apache License Header]
"""

# ... complete file contents from header to EOF ...

# EOF
```

---

## ⚠️ ERROR HANDLING

### Error: web_fetch Returns UNKNOWN_ERROR

**File:** gateway_core.py specifically  
**Cause:** Cloudflare cache issue (temporary)  
**Solution:** Use fallback method (manual paste)

**Response:**
```
"gateway_core.py has a temporary cache issue. Could you paste
the complete file from GitHub so I can modify it correctly?"
```

---

### Error: web_fetch Returns PERMISSIONS_ERROR

**Cause:** URL not in uploaded file list  
**Solution:** Check directory listing

```python
# Fetch directory listing
web_fetch("https://claude.dizzybeaver.com/src/")

# Find correct filename
# Retry with correct filename
```

---

### Error: File Appears Incomplete

**Symptoms:** No EOF marker, missing functions, low line count

**Cause:** Display truncation (NOT file server issue)

**Action:** File server returns complete files - verify visually

**Response:**
```
"The file fetched successfully (XXX lines). If it appears truncated
in display, the complete file is available for modification."
```

---

### Error: Don't Know Filename

**Cause:** User gave concept, not filename  
**Solution:** Search project knowledge first

```python
# Search for file
project_knowledge_search("[concept] implementation")

# Extract filename from results
# Proceed with fetch
```

**Example:**
```
User: "Modify the logging system"
→ Search: "logging implementation file"
→ Result: "logging_core.py, logging_manager.py"
→ Clarify: "I found logging_core.py and logging_manager.py.
          Which would you like to modify?"
```

---

## 📊 PERFORMANCE METRICS

**Timing:**
- Step 1 (Identify): 2 seconds
- Step 2 (Check issues): 1 second
- Step 3 (Context): 5 seconds
- Step 4 (Fetch): 10 seconds
- Step 5 (Patterns): 5 seconds
- Step 6 (Modify): 30 seconds
- Step 7 (Return): 5 seconds
- **Total:** ~58 seconds

**Token Usage:**
- Context search: ~2K tokens
- File fetch: ~3K tokens
- Pattern check: ~2K tokens
- Modification: ~2K tokens
- **Total:** ~9K tokens

**Comparison to Old Method:**
- Old: 3-5 minutes, 50-80K tokens, fragmented code
- New: 58 seconds, 9K tokens, complete code
- **Improvement:** 83% time saved, 86% tokens saved

---

## 💡 BEST PRACTICES

### Always Do
✅ Search for context before fetching (understand patterns)  
✅ Fetch complete file from server (don't use snippets)  
✅ Check patterns and anti-patterns (verify compliance)  
✅ Modify complete file (full context)  
✅ Return complete file (header to EOF)  
✅ Apply LESS-15 verification (completeness check)  
✅ Cite REF-IDs (DEC-##, RULE-##, etc.)

### Never Do
❌ Use project_knowledge_search to get code files  
❌ Return fragmented code  
❌ Skip pattern verification  
❌ Assume snippets are complete  
❌ Output code without full context  
❌ Skip EOF marker verification

---

## 🔗 RELATED FILES

**Hub:** WORKFLOWS-PLAYBOOK.md  
**File Server URLs:** File-Server-URLs.md  
**Verification:** LESS-15 (NM06/.../.../Operations_LESS-15.md)  
**Anti-Patterns:** AP-Checklist-Critical.md  
**Import Rules:** REF-ID-Directory-Others.md (RULE-01)

---

## ✅ SUCCESS CRITERIA

**Workflow succeeded when:**
- ✅ Complete file fetched from server
- ✅ All patterns verified (SUGA, anti-patterns)
- ✅ Complete modified file returned
- ✅ Verification checklist completed (LESS-15)
- ✅ REF-IDs cited for decisions
- ✅ User can deploy immediately

**Time:** ~58 seconds  
**Result:** Complete, working, ready-to-deploy code

---

**END OF WORKFLOW**

**Lines:** ~290 (properly sized)  
**Priority:** CRITICAL (affects all code modifications)  
**ZAPH:** Tier 1 (essential workflow)

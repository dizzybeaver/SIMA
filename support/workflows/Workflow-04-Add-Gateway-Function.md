# Workflow-04-Add-Gateway-Function.md

**Version:** 2.0.0  
**Date:** 2025-11-10  
**Category:** Support Tools  
**Type:** Workflow Template  
**Purpose:** Add new gateway entry for interface function  
**Updated:** SIMAv4 paths, fileserver.php, shared knowledge references

---

## 📋 WORKFLOW OVERVIEW

**Use when:** Making interface function available via gateway  
**Time:** 5-10 minutes  
**Complexity:** Low  
**Prerequisites:** Interface function already exists

---

## 🔧 SESSION REQUIREMENTS

### Critical: Fresh File Access

**Before any file operations:**
1. Ensure fileserver.php fetched at session start
2. Use cache-busted URLs for all file access
3. Verify fetching fresh content (not cached)

**Why:** Anthropic caches files for weeks. fileserver.php bypasses cache with random ?v= parameters.

**REF:** `/sima/entries/lessons/wisdom/WISD-06.md`

---

## ✅ PRE-WORK CHECKLIST

Before starting:
- [ ] fileserver.php URLs available
- [ ] Interface function implemented and tested
- [ ] Function follows architecture pattern
- [ ] Gateway naming convention understood
- [ ] Function registry structure familiar

---

## 🎯 PHASE 1: PLANNING (2 minutes)

### Step 1.1: Define Gateway Name

**Pattern:** `[interface]_[operation]`

**Examples:**
```
cache_get          → CACHE interface, get operation
logging_error      → LOGGING interface, error operation
security_encrypt   → SECURITY interface, encrypt operation
metrics_record     → METRICS interface, record operation
```

### Step 1.2: Verify Uniqueness

**Check gateway.py registry via fileserver.php:**
- Name not already used?
- No naming conflicts?
- Follows naming convention?

### Step 1.3: Identify Module and Function

**Module:** `interface_[name]` or `[module]_operations`  
**Function:** `[operation_name]`

**Example:**
```
Module: interface_cache
Function: get
Gateway name: cache_get
```

---

## 🔧 PHASE 2: IMPLEMENTATION (3-5 minutes)

### Step 2.1: Fetch Gateway File

**CRITICAL: Always fetch current gateway.py via fileserver.php**

**File:** `/src/gateway.py`

**Process:**
```
1. Locate gateway.py in fileserver.php output
2. Use cache-busted URL
3. Read complete file
4. Identify registry structure
```

**REF:** `/sima/entries/lessons/core-architecture/LESS-01.md`

### Step 2.2: Add to Function Registry

**Location:** gateway.py → Function registry/lazy imports dict

**Pattern depends on project architecture:**

**Dictionary Dispatch (DD-1):**
```python
DISPATCH = {
    # ... existing entries ...
    
    '[interface]_[operation]': (
        '[module]',
        '[function_name]'
    ),
}
```

**Cache Registry (CR-1):**
```python
# Add wrapper function
from gateway_wrappers_[interface] import *

# Registry automatically includes all wrapped functions
```

**Example:**
```python
DISPATCH = {
    # ... existing entries ...
    
    'cache_get': (
        'interface_cache',
        'get'
    ),
    
    'cache_set': (
        'interface_cache',
        'set'
    ),
}
```

**REF:** `/sima/languages/python/architectures/dd-1/core/DD1-01-Core-Concept.md`

### Step 2.3: Add to Interface Section (Optional)

**Gateway file may have logical sections by interface.**  
Add entry to appropriate section for readability.

**Sections:**
```
# Cache Interface
# Logging Interface
# Security Interface
# Metrics Interface
# etc.
```

### Step 2.4: Verify Syntax

**Check:**
- Comma placement correct?
- Quotes matched?
- Indentation consistent?
- No typos in module/function names?

### Step 2.5: Output Complete File

**MANDATORY:**
- Output complete gateway.py as artifact
- Mark changes with `# ADDED:` comments
- Include ALL existing code
- Filename in header

**REF:** `/sima/shared/Artifact-Standards.md`

---

## 🧪 PHASE 3: TESTING (2-3 minutes)

### Step 3.1: Import Test

```python
Test 1: Direct import
from gateway import [interface]_[operation]

# Should not raise ImportError
```

### Step 3.2: Function Call Test

```python
Test 2: Function execution
from gateway import cache_get

result = cache_get("test_key")

# Should execute without error
```

### Step 3.3: Lazy Loading Verification

**If using lazy loading pattern:**
```python
Test 3: Verify lazy loading
import sys
import gateway

# Module should NOT be imported yet
assert '[module]' not in sys.modules

# Now import function
from gateway import cache_get

# Now module should be imported
assert 'interface_cache' in sys.modules
```

**REF:** `/sima/entries/gateways/GATE-02.md`

---

## 📝 PHASE 4: DOCUMENTATION (2 minutes)

### Step 4.1: Update Gateway Documentation

**If gateway.py has function listing documentation:**
- Add new function to list
- Include brief description
- Note which interface it belongs to

### Step 4.2: Update Interface Entry

**Update appropriate entry:**
- Location: `/sima/entries/interfaces/INT-##.md`
- Confirm gateway function documented
- Include gateway import example
- Note any special gateway considerations

**Example:**
```markdown
### cache_clear

**Gateway import:**
```python
from gateway import cache_clear
```

**Signature:**
```python
def cache_clear() -> Dict[str, Any]
```

Available via gateway as `cache_clear`.
```

### Step 4.3: Update Quick Index (if exists)

**Add to interface quick index:**
- `/sima/entries/interfaces/Interface-Quick-Index.md`
- Add function with brief description

---

## ⚠️ COMMON PITFALLS

### Pitfall 1: Wrong Module Name

```
❌ DON'T:
'cache_get': ('cache', 'get')  # Wrong module

✅ DO:
'cache_get': ('interface_cache', 'get')  # Correct
```

### Pitfall 2: Wrong Function Name

```
❌ DON'T:
'cache_get': ('interface_cache', 'cache_get')  # Function is just 'get'

✅ DO:
'cache_get': ('interface_cache', 'get')  # Correct
```

### Pitfall 3: Missing Comma

```
❌ DON'T:
DISPATCH = {
    'cache_get': ('interface_cache', 'get')
    'cache_set': ('interface_cache', 'set'),  # Missing comma above
}

✅ DO:
DISPATCH = {
    'cache_get': ('interface_cache', 'get'),  # Comma present
    'cache_set': ('interface_cache', 'set'),
}
```

### Pitfall 4: Duplicate Entry

```
❌ DON'T:
Add function that already exists

✅ DO:
Search registry via fileserver.php for existing entry first
If exists, verify it's correct
If incorrect, update existing entry
```

### Pitfall 5: Stale File Access

**REF:** `/sima/entries/lessons/wisdom/WISD-06.md`

```
❌ DON'T:
Fetch gateway.py without cache-busting

✅ DO:
Always use fileserver.php URLs
Verify fresh content before modifications
```

### Pitfall 6: Partial File Output

**REF:** `/sima/shared/Artifact-Standards.md`

```
❌ DON'T:
"Add these lines to gateway.py..."

✅ DO:
Fetch complete gateway.py
Make changes
Output complete file as artifact
```

---

## 🎓 EXAMPLE WALKTHROUGH

### Example: Add cache_clear Gateway Function

**Step 1: Planning**
```
Interface: CACHE (INT-01)
Operation: clear
Gateway name: cache_clear
Module: interface_cache
Function: clear
```

**Step 2: Implementation**

**Fetch gateway.py via fileserver.php:**
```
URL: https://[...]/gateway.py?v=XXXXXXXXXX
Read complete file
```

**Add to function registry:**
```python
# ADDED: cache_clear function for CACHE interface
DISPATCH = {
    # ... existing entries ...
    
    # Cache Interface
    'cache_get': ('interface_cache', 'get'),
    'cache_set': ('interface_cache', 'set'),
    'cache_clear': ('interface_cache', 'clear'),  # NEW
    
    # ... rest of entries ...
}
```

**Output complete gateway.py as artifact with all existing code + changes**

**Step 3: Testing**

**Test import:**
```python
from gateway import cache_clear
# Success - no ImportError
```

**Test execution:**
```python
result = cache_clear()
assert result['success'] == True
```

**Verify lazy loading:**
```python
import sys
import gateway

# Not loaded yet
assert 'interface_cache' not in sys.modules

# Import triggers load
from gateway import cache_clear

# Now loaded
assert 'interface_cache' in sys.modules
```

**Step 4: Documentation**

**Update /sima/entries/interfaces/INT-01_CACHE-Interface-Pattern.md:**
```markdown
### cache_clear

**Gateway import:**
```python
from gateway import cache_clear
```

**Signature:**
```python
def cache_clear() -> Dict[str, Any]
```

Available via gateway as `cache_clear`.
```

---

## 📊 SUCCESS CRITERIA

Gateway function addition complete when:
- ✅ Entry added to function registry
- ✅ Syntax validated (no errors)
- ✅ Import test passes
- ✅ Function execution test passes
- ✅ Lazy loading verified (if applicable)
- ✅ Documentation updated
- ✅ Complete gateway.py output as artifact
- ✅ Fresh file accessed via fileserver.php

---

## 🔗 RELATED RESOURCES

**Standards:**
- `/sima/shared/Artifact-Standards.md` - Complete file requirements
- `/sima/shared/File-Standards.md` - Size limits, headers
- `/sima/shared/SUGA-Architecture.md` - 3-layer pattern
- `/sima/shared/RED-FLAGS.md` - Never-suggest patterns

**Gateway Patterns:**
- `/sima/entries/gateways/GATE-01.md` - Gateway pattern
- `/sima/entries/gateways/GATE-02.md` - Lazy loading pattern
- `/sima/entries/gateways/GATE-03.md` - Cross-interface rules
- `/sima/entries/gateways/GATE-04.md` - Gateway wrapper functions

**Architecture Patterns:**
- `/sima/languages/python/architectures/dd-1/` - Dictionary Dispatch
- `/sima/languages/python/architectures/cr-1/` - Cache Registry
- `/sima/languages/python/architectures/suga/` - SUGA details

**Interfaces:**
- `/sima/entries/interfaces/INT-01_CACHE-Interface-Pattern.md` (CACHE)
- `/sima/entries/interfaces/INT-02_LOGGING-Interface-Pattern.md` (LOGGING)
- `/sima/entries/interfaces/` - All interface catalogs (INT-01 to INT-12)
- `/sima/entries/interfaces/Interface-Quick-Index.md` - Quick reference

**Lessons:**
- `/sima/entries/lessons/core-architecture/LESS-01.md` - Fetch complete files
- `/sima/entries/lessons/operations/LESS-15.md` - Verification protocol
- `/sima/entries/lessons/wisdom/WISD-06.md` - Cache-busting requirement

**Workflows:**
- Workflow-01-Add-Feature.md - Prerequisite process
- Workflow-03-Update-Interface.md - Related modifications

---

## 🎯 QUICK REFERENCE

**Gateway Entry Template:**
```python
'[interface]_[operation]': (
    '[module]',
    '[function_name]'
),
```

**Common Interfaces:**
```
cache_*      → interface_cache
logging_*    → interface_logging
security_*   → interface_security
metrics_*    → interface_metrics
ha_*         → interface_ha or ha_operations
config_*     → interface_config
singleton_*  → interface_singleton
utility_*    → interface_utility
websocket_*  → interface_websocket
http_*       → interface_http
debug_*      → interface_debug
```

---

**END OF WORKFLOW-04**

**Version:** 2.0.0  
**Lines:** 397 (within 400 limit)  
**Related workflows:** WF-01 (Add Feature), WF-03 (Update Interface)

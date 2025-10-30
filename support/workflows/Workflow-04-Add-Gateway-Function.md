# File: Workflow-04-Add-Gateway-Function.md

**REF-ID:** WF-04  
**Version:** 1.0.0  
**Category:** Support Tools  
**Type:** Workflow Template  
**Purpose:** Add new gateway entry for interface function

---

## 📋 WORKFLOW OVERVIEW

**Use when:** Making interface function available via gateway  
**Time:** 5-10 minutes  
**Complexity:** Low  
**Prerequisites:** Interface function already exists

---

## ✅ PRE-WORK CHECKLIST

Before starting:
- [ ] Interface function implemented and tested
- [ ] Function follows SUGA pattern (Interface → Core)
- [ ] Gateway naming convention understood
- [ ] LAZY_IMPORTS structure familiar

---

## 🎯 PHASE 1: PLANNING (2 minutes)

### Step 1.1: Define Gateway Name
```
Pattern: [interface]_[operation]

Examples:
cache_get          → CACHE interface, get operation
logging_error      → LOGGING interface, error operation
security_encrypt   → SECURITY interface, encrypt operation
metrics_record     → METRICS interface, record operation
```

### Step 1.2: Verify Uniqueness
```
Check gateway.py LAZY_IMPORTS:
- Name not already used?
- No naming conflicts?
- Follows naming convention?
```

### Step 1.3: Identify Module and Function
```
Module: [interface]_operations
Function: [operation_name]

Example:
Module: cache_operations
Function: get
Gateway name: cache_get
```

---

## 🔧 PHASE 2: IMPLEMENTATION (3-5 minutes)

### Step 2.1: Fetch Gateway File
```
CRITICAL: Always fetch current gateway.py

File: /src/gateway.py
```

### Step 2.2: Add to LAZY_IMPORTS Dictionary
```python
Location: gateway.py → LAZY_IMPORTS dict

Pattern:
LAZY_IMPORTS = {
    # ... existing entries ...
    
    '[interface]_[operation]': (
        '[module]_operations',
        '[function_name]'
    ),
}

Example:
LAZY_IMPORTS = {
    # ... existing entries ...
    
    'cache_get': (
        'cache_operations',
        'get'
    ),
    
    'cache_set': (
        'cache_operations',
        'set'
    ),
}
```

### Step 2.3: Add to Interface Section (Optional)
```
Gateway file has logical sections by interface.
Add entry to appropriate section for readability.

Sections:
# Cache Interface
# Logging Interface
# Security Interface
# Metrics Interface
# etc.
```

### Step 2.4: Verify Syntax
```
Check:
- Comma placement correct?
- Quotes matched?
- Indentation consistent?
- No typos in module/function names?
```

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
```python
Test 3: Verify lazy loading
import gateway

# Module should NOT be imported yet
assert '[module]_operations' not in sys.modules

# Now import function
from gateway import cache_get

# Now module should be imported
assert 'cache_operations' in sys.modules
```

---

## 📝 PHASE 4: DOCUMENTATION (2 minutes)

### Step 4.1: Update Gateway Documentation
```
If gateway.py has function listing documentation:
- Add new function to list
- Include brief description
- Note which interface it belongs to
```

### Step 4.2: Update Interface Entry
```
Update INT-## entry:
- Confirm gateway function documented
- Include gateway import example
- Note any special gateway considerations
```

---

## ⚠️ COMMON PITFALLS

### Pitfall 1: Wrong Module Name
```
❌ DON'T:
'cache_get': ('cache', 'get')  # Wrong module

✅ DO:
'cache_get': ('cache_operations', 'get')  # Correct
```

### Pitfall 2: Wrong Function Name
```
❌ DON'T:
'cache_get': ('cache_operations', 'cache_get')  # Function is just 'get'

✅ DO:
'cache_get': ('cache_operations', 'get')  # Correct
```

### Pitfall 3: Missing Comma
```
❌ DON'T:
LAZY_IMPORTS = {
    'cache_get': ('cache_operations', 'get')
    'cache_set': ('cache_operations', 'set'),  # Missing comma above
}

✅ DO:
LAZY_IMPORTS = {
    'cache_get': ('cache_operations', 'get'),  # Comma present
    'cache_set': ('cache_operations', 'set'),
}
```

### Pitfall 4: Duplicate Entry
```
❌ DON'T:
Add function that already exists

✅ DO:
Search LAZY_IMPORTS for existing entry first
If exists, verify it's correct
If incorrect, update existing entry
```

---

## 🎓 EXAMPLE WALKTHROUGH

### Example: Add cache_clear Gateway Function

**Step 1: Planning**
```
Interface: CACHE (INT-01)
Operation: clear
Gateway name: cache_clear
Module: cache_operations
Function: clear
```

**Step 2: Implementation**

**Fetch gateway.py:**
```
web_fetch: https://[...]/gateway.py
```

**Add to LAZY_IMPORTS:**
```python
LAZY_IMPORTS = {
    # ... existing entries ...
    
    # Cache Interface
    'cache_get': ('cache_operations', 'get'),
    'cache_set': ('cache_operations', 'set'),
    'cache_clear': ('cache_operations', 'clear'),  # NEW
    
    # ... rest of entries ...
}
```

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
assert 'cache_operations' not in sys.modules

# Import triggers load
from gateway import cache_clear

# Now loaded
assert 'cache_operations' in sys.modules
```

**Step 4: Documentation**

**Update INT-01:**
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
- ✅ Entry added to LAZY_IMPORTS
- ✅ Syntax validated (no errors)
- ✅ Import test passes
- ✅ Function execution test passes
- ✅ Lazy loading verified
- ✅ Documentation updated
- ✅ Complete gateway.py output as artifact

---

## 🔗 RELATED RESOURCES

**Gateway Patterns:**
- GATE-01: Gateway Pattern (lazy loading)
- GATE-02: Three-File Structure
- GATE-03: Lazy Loading Pattern
- GATE-04: Cross-Interface Rule

**Interfaces:**
- INT-01 to INT-12: Interface function catalogs

**Lessons:**
- LESS-01: Always fetch current files
- LESS-15: SUGA verification

**Workflows:**
- WF-01: Add Feature (prerequisite)
- WF-03: Update Interface (related)

---

## 🎯 QUICK REFERENCE

**Gateway Entry Template:**
```python
'[interface]_[operation]': (
    '[module]_operations',
    '[function_name]'
),
```

**Common Interfaces:**
```
cache_*      → cache_operations
logging_*    → logging_operations
security_*   → security_operations
metrics_*    → metrics_operations
ha_*         → ha_operations
config_*     → config_operations
singleton_*  → singleton_operations
utility_*    → utility_operations
websocket_*  → websocket_operations
data_*       → data_operations
ble_*        → ble_operations
api_*        → api_operations
```

---

**END OF WORKFLOW-04**

**Related workflows:** WF-01 (Add Feature), WF-05 (Create NMP Entry)

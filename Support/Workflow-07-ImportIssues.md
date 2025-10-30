# Workflow-07-ImportIssues.md
**Import Errors and Circular Dependencies - Step-by-Step**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: Systematic resolution of import-related issues

---

## 🎯 TRIGGERS

- "ImportError: cannot import name X"
- "ModuleNotFoundError"
- "Circular import detected"
- "AttributeError: module has no attribute X"
- "Partially initialized module"
- "Import is failing"

---

## ⚡ DECISION TREE

```
User reports import error
    ↓
Step 1: Identify Import Error Type
    → Circular import?
    → Module not found?
    → Attribute error?
    → Partially initialized?
    ↓
Step 2: Check SIMA Pattern Violation
    → Direct cross-interface import? (AP-01)
    → Skipped gateway? (RULE-01)
    ↓
Violated SIMA? → Fix import path + DONE
    ↓
Step 3: Check Import Rules
    → Dependency layer violated? (DEP-01 to DEP-08)
    → Wrong import order?
    ↓
Step 4: Check Known Import Issues
    → Search NM06 for BUG-## or LESS-##
    ↓
Known issue? → Apply documented fix + DONE
    ↓
Step 5: Trace Dependency Chain
    → Map actual import path
    → Find circular dependency
    ↓
Step 6: Fix with Lazy Imports
    → Move imports to function level
    → Break circular dependency
    → Verify solution
```

---

## 📋 STEP-BY-STEP PROCESS

### Step 1: Identify Import Error Type (30 seconds)

**Error pattern recognition:**

**Circular Import:**
```python
ImportError: cannot import name 'X' from partially initialized module 'Y'
```
→ Two modules importing each other

**Module Not Found:**
```python
ModuleNotFoundError: No module named 'X'
```
→ File doesn't exist or wrong path

**Attribute Error:**
```python
AttributeError: module 'X' has no attribute 'Y'
```
→ Function/class doesn't exist or not exported

**Partially Initialized:**
```python
ImportError: cannot import name 'X' from 'Y' (unknown location)
```
→ Module being imported during its own initialization

---

### Step 2: Check SIMA Pattern Violation (15 seconds)

**Most common cause: Direct cross-interface imports**

**File:** AP-Checklist-Critical.md → AP-01

**Violation patterns:**

**Pattern 1: Direct core import**
```python
# ❌ WRONG - Violates AP-01
from cache_core import get_value

# ✅ CORRECT - Via gateway
import gateway
value = gateway.cache_get(key)
```

**Pattern 2: Cross-interface import**
```python
# ❌ WRONG - Violates AP-01
from config_core import get_config
from logging_core import log_info

# ✅ CORRECT - Via gateway
import gateway
config = gateway.config_get(key)
gateway.log_info(message)
```

**Pattern 3: Skipped interface layer**
```python
# ❌ WRONG - Bypasses interface
import cache_core
result = cache_core.get_impl(key)

# ✅ CORRECT - Through layers
import gateway
result = gateway.cache_get(key)
```

**Quick fix:**
```
1. Remove direct import
2. Add: import gateway
3. Replace: X.function() → gateway.interface_function()
4. Test
```

---

### Step 3: Check Import Rules (1 minute)

**File:** NM02/NM02-Dependencies-ImportRules_Index.md

**Dependency layer rules:**

**8 Dependency Layers (DEP-01 to DEP-08):**
```
Layer 1: External (built-in/stdlib)
Layer 2: Gateway (gateway.py, gateway_core.py)
Layer 3: Gateway Wrappers (gateway_wrappers.py)
Layer 4: Interfaces (interface_*.py)
Layer 5: Core (12 core systems)
Layer 6: Utilities (utility_*.py, singleton_*.py)
Layer 7: Extensions (ha_*.py)
Layer 8: Entry Points (lambda_function.py)

Rule: Can only import from LOWER layers
```

**Violation examples:**

**❌ Core importing from Interface:**
```python
# cache_core.py (Layer 5)
from interface_logging import log_info  # Layer 4 - ILLEGAL
```

**❌ Interface importing from Gateway:**
```python
# interface_cache.py (Layer 4)
from gateway import some_function  # Layer 2 - ILLEGAL
```

**✅ Legal imports:**
```python
# gateway_wrappers.py (Layer 3)
import interface_cache  # Layer 4 - OK (higher → lower)

# cache_core.py (Layer 5)
import utility_core  # Layer 6 - OK (higher → lower)
```

**Quick check:**
```
1. Identify layer of importing file
2. Identify layer of imported file
3. Verify: importing layer < imported layer
4. If not: Restructure or use gateway
```

---

### Step 4: Check Known Import Issues (15 seconds)

**File:** NM06/NM06-Bugs-Critical_Index.md

**Known import bugs:**

| BUG-## | Issue | Solution |
|--------|-------|----------|
| BUG-03 | Module import order | Fix initialization sequence |
| BUG-04 | Circular deps | Use lazy imports |

**Search process:**
```
1. Open NM06-Bugs-Critical_Index.md
2. Search for "import" keyword
3. Check if pattern matches
4. Apply documented solution
```

---

### Step 5: Trace Dependency Chain (2-3 minutes)

**Manual dependency tracing:**

**Step 5a: Map import chain**
```python
# Example: Circular import between A and B

# File A:
from B import function_b  # ← A imports B

def function_a():
    return function_b()

# File B:
from A import function_a  # ← B imports A (CIRCULAR!)

def function_b():
    return function_a()
```

**Step 5b: Draw the cycle**
```
A imports B
  ↓
B imports A
  ↓
Circular! Module initialization deadlock
```

**Step 5c: Identify break point**
```
Which import can be deferred?
- If A's import of B only used in functions → Move to function level
- If B's import of A only used in functions → Move to function level
- Best: Both moved to function level
```

---

### Step 6: Fix with Lazy Imports (5 minutes)

**Solution: Move imports inside functions**

**Pattern 1: Function-level import**
```python
# ❌ BEFORE: Module-level (circular)
from B import function_b

def function_a():
    return function_b()

# ✅ AFTER: Function-level (breaks cycle)
def function_a():
    from B import function_b  # Import when called
    return function_b()
```

**Pattern 2: Conditional import**
```python
# For type hints or rare uses
def function_a(param):
    if condition:
        from B import function_b
        return function_b(param)
    return default_value
```

**Pattern 3: Gateway pattern (preferred)**
```python
# ❌ BEFORE: Direct import
from cache_core import get_value
from logging_core import log_info

# ✅ AFTER: Gateway
def my_function():
    import gateway  # Single import point
    value = gateway.cache_get(key)
    gateway.log_info(message)
    return value
```

---

## 💡 COMPLETE EXAMPLES

### Example 1: Circular Import in Core Modules

**Error:**
```python
ImportError: cannot import name 'log_info' from partially initialized module 'logging_core'
```

**Root cause:**
```python
# cache_core.py
from logging_core import log_info  # Cache imports Logging

def cache_operation():
    log_info("Cache operation")

# logging_core.py  
from cache_core import get_cache_stats  # Logging imports Cache

def log_with_stats():
    stats = get_cache_stats()
    log_info(f"Stats: {stats}")
```

**Solution:**
```python
# ✅ Fix: Use gateway in both
# cache_core.py
def cache_operation():
    import gateway  # Lazy import
    gateway.log_info("Cache operation")

# logging_core.py
def log_with_stats():
    import gateway  # Lazy import
    stats = gateway.cache_get_stats()
    log_info(f"Stats: {stats}")
```

---

### Example 2: Layer Violation

**Error:**
```python
ImportError: cannot import name 'validate_input' from 'interface_validation'
```

**Root cause:**
```python
# http_client_core.py (Layer 5)
from interface_validation import validate_input  # Layer 4 - WRONG DIRECTION
```

**Layer analysis:**
```
http_client_core.py: Layer 5 (Core)
interface_validation.py: Layer 4 (Interface)

Rule: Can only import from HIGHER layers
5 > 4 → VIOLATION
```

**Solution 1: Use utility layer**
```python
# Move validation to utility layer (Layer 6)
# utility_validation.py
def validate_input(data):
    # Validation logic

# http_client_core.py (Layer 5)
from utility_validation import validate_input  # 5 → 6 OK
```

**Solution 2: Use gateway**
```python
# http_client_core.py
def http_request(data):
    import gateway  # Always safe
    gateway.validation_check_input(data)
```

---

### Example 3: Module Not Found

**Error:**
```python
ModuleNotFoundError: No module named 'config_loader'
```

**Troubleshooting:**

**Check 1: File exists?**
```bash
ls src/config_loader.py
# If not found → File missing or wrong name
```

**Check 2: Import path correct?**
```python
# ❌ WRONG
from src.config_loader import load  # Don't include 'src'

# ✅ CORRECT
from config_loader import load  # Direct from module
```

**Check 3: In Lambda package?**
```bash
# Check deployed Lambda
ls /var/task/config_loader.py
# If missing → Not included in deployment
```

---

### Example 4: Attribute Error

**Error:**
```python
AttributeError: module 'gateway' has no attribute 'cache_get_new_function'
```

**Root cause:**
```python
# Added function to cache_core.py
# But forgot to add gateway wrapper!
```

**Solution:**
```python
# 1. Add to gateway_wrappers.py
def cache_get_new_function(*args):
    """New cache function"""
    import interface_cache
    return interface_cache.get_new_function(*args)

# 2. Add to interface_cache.py
def get_new_function(*args):
    """Interface layer"""
    import cache_core
    return cache_core.get_new_function_impl(*args)

# 3. Implementation already exists in cache_core.py
def get_new_function_impl(*args):
    # Implementation
    pass
```

---

## 🔍 DIAGNOSTIC TOOLS

### Import Tracer

**Manual tracing:**
```python
# Add to problematic file
import sys
print(f"Importing {__name__}")
print(f"Import path: {sys.path}")
print(f"Modules loaded: {list(sys.modules.keys())}")
```

### Dependency Visualizer

**Check import order:**
```python
# In lambda_function.py or test file
import sys
print("Import order:")
for i, module in enumerate(sys.modules.keys()):
    if not module.startswith('_'):
        print(f"{i}: {module}")
```

---

## ⚠️ COMMON MISTAKES TO AVOID

**DON'T:**
- Use direct cross-interface imports
- Import from higher layers
- Ignore circular import warnings
- Add imports without checking layers
- Guess at import paths
- Skip gateway pattern

**DO:**
- Always use gateway for cross-interface
- Respect dependency layers
- Use lazy imports when needed
- Check import rules (RULE-01)
- Verify layer hierarchy (DEP-01 to DEP-08)
- Test imports after changes

---

## 🔗 RELATED FILES

**Hub:** WORKFLOWS-PLAYBOOK.md  
**Import Rules:** NM02/NM02-Dependencies-ImportRules_RULE-01.md  
**Dependency Layers:** NM02/NM02-Dependencies-Layers_Index.md  
**AP-01:** NM05/NM05-AntiPatterns-Import_AP-01.md  
**Known Bugs:** NM06/NM06-Bugs-Critical_Index.md  
**Decision Trees:** NM07/NM07-DecisionLogic-Import_Index.md

---

## 📊 SUCCESS METRICS

**Workflow succeeded when:**
- ✅ Import error resolved
- ✅ No circular dependencies
- ✅ SIMA pattern followed
- ✅ Dependency layers respected
- ✅ Lazy imports used appropriately
- ✅ Solution tested and working
- ✅ Root cause documented

**Time:** 10-20 minutes for typical import issue

---

**END OF WORKFLOW**

**Lines:** ~295 (properly sized)  
**Priority:** HIGH (common development issue)  
**ZAPH:** Tier 2 (frequent for development)

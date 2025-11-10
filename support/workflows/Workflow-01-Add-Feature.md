# Workflow-01-Add-Feature.md

**Version:** 2.0.0  
**Date:** 2025-11-10  
**Category:** Support Tools  
**Type:** Workflow Template  
**Purpose:** Step-by-step guide for adding new features to SUGA architecture  
**Updated:** SIMAv4 paths, fileserver.php, shared knowledge references

---

## 📋 WORKFLOW OVERVIEW

**Use when:** Adding new functionality to existing interface or creating new operation  
**Time:** 15-45 minutes  
**Complexity:** Medium  
**Prerequisites:** Understanding of SUGA architecture (see /sima/shared/SUGA-Architecture.md)

---

## ✅ PRE-WORK CHECKLIST

Before starting, verify:
- [ ] Feature clearly defined
- [ ] Interface identified (or new interface justified)
- [ ] No duplicate functionality exists
- [ ] SUGA layers understood (Gateway → Interface → Core)
- [ ] fileserver.php fetched (fresh file access)
- [ ] Access to current codebase files

**REF:** `/sima/shared/SUGA-Architecture.md`

---

## 🎯 PHASE 1: PLANNING (5 minutes)

### Step 1.1: Define Feature Scope
```
What: [One sentence description]
Why: [Business/technical justification]
Where: [Which interface? INT-01 to INT-12]
Impact: [What changes? Gateway, Interface, Core?]
```

### Step 1.2: Check for Duplicates
```
Search knowledge base:
- project_knowledge_search: "[feature name] interface"
- Check /sima/entries/interfaces/ for similar functionality
- Review /sima/languages/python/architectures/suga/interfaces/ for patterns
- Check gateway operations for overlapping logic
```

### Step 1.3: Identify Pattern
```
Choose implementation approach:
- Standard CRUD? → Follow /sima/entries/interfaces/INT-## pattern
- Complex logic? → Review /sima/languages/python/architectures/lmms/ 
- Performance critical? → Check /sima/languages/python/architectures/zaph/
- External API? → Review project-specific patterns in /sima/projects/[project]/
```

---

## 🔧 PHASE 2: IMPLEMENTATION (20-30 minutes)

### Step 2.1: Fetch Current Files via fileserver.php
```
CRITICAL: Always fetch fresh files before modifying

1. Ensure fileserver.php fetched at session start
2. Use cache-busted URLs for all file access

Files to fetch:
1. Gateway: https://claude.dizzybeaver.com/src/gateway.py?v=XXXXXXXXXX
2. Interface: https://claude.dizzybeaver.com/src/interface_[name].py?v=XXXXXXXXXX
3. Core: https://claude.dizzybeaver.com/src/[core_module].py?v=XXXXXXXXXX
```

**Red Flag:** Never modify without seeing current content

**REF:** `/sima/entries/lessons/core-architecture/LESS-01.md`, `/sima/entries/lessons/wisdom/WISD-06.md`

### Step 2.2: Implement Core Layer
```python
# Location: /src/[module]_core.py

def _internal_operation(params: Dict[str, Any]) -> ReturnType:
    """Internal implementation - never imported directly."""
    # Core logic here
    return result
```

**Standards:**
- Private naming (_prefix)
- Type hints required
- Docstring with purpose
- Error handling with specific exceptions (never bare except)
- No cross-interface imports

**REF:** `/sima/shared/Common-Patterns.md`

### Step 2.3: Implement Interface Layer
```python
# Location: /src/interface_[name].py

def public_operation(params: Dict[str, Any]) -> ReturnType:
    """Public interface - imported via gateway."""
    # Lazy import inside function
    from .[module]_core import _internal_operation
    
    # Validation
    # Call core
    # Transform result
    return result
```

**Standards:**
- Public naming (no underscore)
- Parameter validation
- Gateway-friendly signature
- Lazy imports (function-level only)

**REF:** `/sima/languages/python/architectures/lmms/core/LMMS-03-Import-Strategy.md`

### Step 2.4: Add to Gateway
```python
# Location: /src/gateway.py

# Pattern depends on gateway structure
# Follow existing gateway patterns

# Typically:
# 1. Add wrapper function in gateway_wrappers_[interface].py
# 2. Import in gateway.py consolidation
# 3. Add to __all__ exports
```

**REF:** `/sima/languages/python/architectures/suga/gateways/GATE-01-Gateway-Entry-Pattern.md`

### Step 2.5: Verify SUGA Compliance
```
Checklist:
- [ ] Core = _private function
- [ ] Interface = public function  
- [ ] Gateway = wrapper entry
- [ ] No direct core imports
- [ ] Specific exceptions (not bare except)
- [ ] Type hints present
- [ ] No threading locks (Lambda single-threaded)
- [ ] No sentinel objects cross boundaries
- [ ] Lazy imports (function-level)
```

**REF:** `/sima/entries/lessons/operations/LESS-15.md`, `/sima/shared/RED-FLAGS.md`

---

## 🧪 PHASE 3: TESTING (5-10 minutes)

### Step 3.1: Unit Test
```python
# Test core logic:
# - Happy path
# - Edge cases  
# - Error conditions
```

### Step 3.2: Integration Test
```python
# Test via gateway:
from gateway import [interface]_[operation]
result = [interface]_[operation](params)
```

### Step 3.3: Performance Test (if critical)
```python
# Check:
# - Cold start impact
# - Memory usage
# - Execution time

# Use performance_benchmark.py if available
```

**REF:** `/sima/entries/lessons/performance/LESS-02.md` (Measure don't guess)

---

## 📝 PHASE 4: DOCUMENTATION (5 minutes)

### Step 4.1: Update Interface Entry
```
If /sima/entries/interfaces/INT-## entry exists:
- Add new function to catalog
- Document parameters
- Add usage example
- Note performance characteristics
```

### Step 4.2: Create Project-Specific Entry (if needed)
```
If implementation has unique project patterns:
- Create entry in /sima/projects/[project]/lessons/
- Document specific usage
- Add to project index
```

### Step 4.3: Update Indexes
```
Add entries to:
- /sima/entries/interfaces/Interface-Quick-Index.md
- /sima/projects/[project]/indexes/ (if project-specific)
```

---

## ⚠️ COMMON PITFALLS

### Pitfall 1: Direct Core Imports
```python
# ❌ DON'T:
from cache_core import _get_cached_value

# ✅ DO:
from gateway import cache_get
```

**REF:** `/sima/entries/anti-patterns/import/AP-01.md`

### Pitfall 2: Bare Exception Handlers
```python
# ❌ DON'T:
try:
    result = operation()
except:
    pass

# ✅ DO:
try:
    result = operation()
except SpecificError as e:
    handle_error(e)
```

**REF:** `/sima/entries/anti-patterns/error-handling/AP-14.md`

### Pitfall 3: Threading Primitives
```python
# ❌ DON'T:
lock = threading.Lock()
with lock:
    operation()

# ✅ DO:
# Lambda is single-threaded - no locks needed
operation()
```

**REF:** `/sima/entries/decisions/architecture/DEC-04.md`, `/sima/entries/anti-patterns/concurrency/AP-08.md`

### Pitfall 4: Skipping File Fetch
```
# ❌ DON'T:
Modify code from memory

# ✅ DO:
1. Fetch via fileserver.php (cache-busted URL)
2. Read complete content
3. Make changes
4. Output complete file as artifact
```

**REF:** `/sima/entries/lessons/core-architecture/LESS-01.md`, `/sima/entries/lessons/wisdom/WISD-06.md`

---

## 📊 SUCCESS CRITERIA

Feature implementation complete when:
- ✅ All 3 SUGA layers implemented
- ✅ Gateway entry added
- ✅ Verification checklist passed
- ✅ Tests passing
- ✅ Documentation updated
- ✅ No RED FLAGS violated
- ✅ Complete files output as artifacts
- ✅ Fresh files fetched via fileserver.php

---

## 🔗 RELATED RESOURCES

**Architecture:**
- `/sima/shared/SUGA-Architecture.md` - 3-layer pattern
- `/sima/languages/python/architectures/lmms/` - Module management
- `/sima/languages/python/architectures/zaph/` - Performance optimization

**Interfaces:**
- `/sima/entries/interfaces/` - Interface catalogs (INT-01 to INT-12)
- `/sima/entries/interfaces/Interface-Quick-Index.md` - Problem-based lookup

**Lessons:**
- `/sima/entries/lessons/core-architecture/LESS-01.md` - Always fetch current files
- `/sima/entries/lessons/operations/LESS-15.md` - Verification checklist
- `/sima/entries/lessons/performance/LESS-02.md` - Measure don't guess

**Anti-Patterns:**
- `/sima/entries/anti-patterns/error-handling/AP-14.md` - Bare except
- `/sima/entries/anti-patterns/concurrency/AP-08.md` - Threading locks
- `/sima/shared/RED-FLAGS.md` - Complete list

**Decisions:**
- `/sima/entries/decisions/architecture/DEC-04.md` - No threading in Lambda

**Standards:**
- `/sima/shared/Artifact-Standards.md` - Complete file requirements
- `/sima/shared/File-Standards.md` - Size limits, headers
- `/sima/shared/Common-Patterns.md` - Universal patterns

---

## 🎓 EXAMPLE WALKTHROUGH

### Example: Add Cache Invalidation Feature

**Step 1: Planning**
```
What: Add ability to invalidate specific cache keys
Why: Support selective cache clearing
Where: INT-01 CACHE interface  
Impact: Add gateway entry, interface function, core logic
```

**Step 2: Implementation**
```
1. Fetch via fileserver.php:
   - cache_core.py
   - interface_cache.py
   - gateway_wrappers_cache.py
   
2. Core: def _invalidate_key(key: str) -> bool
3. Interface: def cache_invalidate(key: str) -> Dict
4. Gateway wrapper: cache_invalidate wrapper function
```

**Step 3: Testing**
```python
from gateway import cache_invalidate
result = cache_invalidate("test_key")
assert result["success"] == True
```

**Step 4: Documentation**
```
Update: /sima/entries/interfaces/INT-01_CACHE-Interface-Pattern.md
Add: Function catalog entry with example
Update: /sima/entries/interfaces/Interface-Quick-Index.md
```

---

**END OF WORKFLOW-01**

**Version:** 2.0.0 (SIMAv4 update)  
**Changes:** Updated paths (NM##/ → /sima/entries/), added fileserver.php, added shared knowledge refs  
**Related workflows:** Workflow-02 (Debug Issue), Workflow-03 (Update Interface)

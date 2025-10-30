# File: Workflow-01-Add-Feature.md

**REF-ID:** WF-01  
**Version:** 1.0.0  
**Category:** Support Tools  
**Type:** Workflow Template  
**Purpose:** Step-by-step guide for adding new features to SUGA architecture

---

## 📋 WORKFLOW OVERVIEW

**Use when:** Adding new functionality to existing interface or creating new operation  
**Time:** 15-45 minutes  
**Complexity:** Medium  
**Prerequisites:** Understanding of SUGA architecture (ARCH-01)

---

## ✅ PRE-WORK CHECKLIST

Before starting, verify:
- [ ] Feature clearly defined
- [ ] Interface identified (or new interface justified)
- [ ] No duplicate functionality exists
- [ ] SUGA layers understood (Gateway → Interface → Core)
- [ ] Access to current codebase files

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
Search neural maps:
- project_knowledge_search: "[feature name] interface"
- Check INT-## entries for similar functionality
- Review gateway operations for overlapping logic
```

### Step 1.3: Identify Pattern
```
Choose implementation approach:
- Standard CRUD? → Follow INT-## catalog pattern
- Complex logic? → Review ARCH-02 (LMMS pattern)
- Performance critical? → Check ARCH-04 (ZAPH)
- External API? → Review NMP01-LEE-17 (HA integration)
```

---

## 🔧 PHASE 2: IMPLEMENTATION (20-30 minutes)

### Step 2.1: Fetch Current Files
```
CRITICAL: Always fetch before modifying

Files to fetch:
1. Gateway: gateway.py
2. Interface: [interface_name].py
3. Core: [core_module].py
```

**Red Flag:** Never modify without seeing current content (LESS-01)

### Step 2.2: Implement Core Layer
```
Location: /src/[module]_core.py

Pattern:
def _internal_operation(params: Dict[str, Any]) -> ReturnType:
    """Internal implementation - never imported directly."""
    # Core logic here
    return result

Standards:
- Private naming (_prefix)
- Type hints required
- Docstring with purpose
- Error handling with specific exceptions
- No cross-interface imports
```

### Step 2.3: Implement Interface Layer
```
Location: /src/[module]_operations.py

Pattern:
def public_operation(params: Dict[str, Any]) -> ReturnType:
    """Public interface - imported via gateway."""
    from .[module]_core import _internal_operation
    
    # Validation
    # Call core
    # Transform result
    return result

Standards:
- Public naming (no underscore)
- Parameter validation
- Gateway-friendly signature
- Lazy imports inside function
```

### Step 2.4: Add to Gateway
```
Location: /src/gateway.py

Pattern:
1. Add to LAZY_IMPORTS:
   '[interface]_[operation]': (
       '[module]_operations',
       '[operation]'
   ),

2. Add to execute_operation() dispatch logic if needed

3. Document in gateway comments
```

### Step 2.5: Verify SUGA Compliance
```
Checklist (LESS-15):
- [ ] Core = _private function
- [ ] Interface = public function
- [ ] Gateway = lazy import entry
- [ ] No direct core imports
- [ ] Specific exceptions (not bare except)
- [ ] Type hints present
- [ ] No threading locks
- [ ] No sentinel objects cross boundaries
```

---

## 🧪 PHASE 3: TESTING (5-10 minutes)

### Step 3.1: Unit Test
```
Test core logic:
- Happy path
- Edge cases
- Error conditions
```

### Step 3.2: Integration Test
```
Test via gateway:
from gateway import [interface]_[operation]
result = [interface]_[operation](params)
```

### Step 3.3: Performance Test (if critical)
```
Check:
- Cold start impact
- Memory usage
- Execution time
```

---

## 📝 PHASE 4: DOCUMENTATION (5 minutes)

### Step 4.1: Update Interface Entry
```
If INT-## entry exists:
- Add new function to catalog
- Document parameters
- Add usage example
- Note performance characteristics
```

### Step 4.2: Create NMP Entry (if project-specific)
```
If implementation has unique patterns:
- Create NMP01-LEE-## entry
- Document specific usage
- Add to cross-reference matrix
```

### Step 4.3: Update Quick Indexes
```
Add entries to:
- Interface Quick Index
- NMP Quick Index (if applicable)
```

---

## ⚠️ COMMON PITFALLS

### Pitfall 1: Direct Core Imports
```
❌ DON'T:
from cache_core import _get_cached_value

✅ DO:
from gateway import cache_get
```
**Source:** RULE-01

### Pitfall 2: Bare Exception Handlers
```
❌ DON'T:
try:
    result = operation()
except:
    pass

✅ DO:
try:
    result = operation()
except SpecificError as e:
    handle_error(e)
```
**Source:** AP-05

### Pitfall 3: Threading Primitives
```
❌ DON'T:
lock = threading.Lock()
with lock:
    operation()

✅ DO:
# Lambda is single-threaded - no locks needed
operation()
```
**Source:** DEC-04, AP-08

### Pitfall 4: Skipping File Fetch
```
❌ DON'T:
Modify code from memory

✅ DO:
web_fetch current file first
Read complete content
Make changes
Output complete file as artifact
```
**Source:** LESS-01

---

## 📊 SUCCESS CRITERIA

Feature implementation complete when:
- ✅ All 3 SUGA layers implemented
- ✅ Gateway lazy import added
- ✅ LESS-15 verification passed
- ✅ Tests passing
- ✅ Documentation updated
- ✅ No RED FLAGS violated
- ✅ Complete files output as artifacts

---

## 🔗 RELATED RESOURCES

**Architecture:**
- ARCH-01: SUGA Pattern (3-layer architecture)
- ARCH-02: LMMS (Multi-layer separation)
- ARCH-04: ZAPH (Performance optimization)

**Interfaces:**
- INT-01 to INT-12: Interface catalogs
- Interface Quick Index: Problem-based lookup

**Lessons:**
- LESS-01: Always fetch current files
- LESS-15: SUGA verification checklist

**Anti-Patterns:**
- AP-05: Exception handling
- AP-08: Threading locks

**Decisions:**
- DEC-04: No threading in Lambda

**Project Patterns:**
- NMP01-LEE Quick Index: Implementation examples

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
1. Fetch: cache_core.py, cache_operations.py, gateway.py
2. Core: def _invalidate_key(key: str) -> bool
3. Interface: def cache_invalidate(key: str) -> Dict
4. Gateway: Add 'cache_invalidate' to LAZY_IMPORTS
```

**Step 3: Testing**
```
from gateway import cache_invalidate
result = cache_invalidate("test_key")
assert result["success"] == True
```

**Step 4: Documentation**
```
Update: INT-01 CACHE entry
Add: Function catalog entry with example
Update: INT-01 Quick Index
```

---

**END OF WORKFLOW-01**

**Next workflows:** WF-02 (Debug Issue), WF-03 (Update Interface), WF-04 (Add Gateway Function)

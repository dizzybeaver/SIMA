# File: Workflow-03-Update-Interface.md

**REF-ID:** WF-03  
**Version:** 1.0.0  
**Category:** Support Tools  
**Type:** Workflow Template  
**Purpose:** Modify existing interface functions safely

---

## 📋 WORKFLOW OVERVIEW

**Use when:** Modifying signatures, adding parameters, changing behavior  
**Time:** 15-30 minutes  
**Complexity:** Medium-High (affects multiple layers)  
**Prerequisites:** Understanding of interface dependencies

---

## ✅ PRE-WORK CHECKLIST

Before starting:
- [ ] Change clearly defined
- [ ] Interface function identified
- [ ] Dependency analysis complete (who calls this?)
- [ ] Backward compatibility considered
- [ ] Breaking change approved (if applicable)

---

## 🎯 PHASE 1: IMPACT ANALYSIS (5-10 minutes)

### Step 1.1: Identify All Callers
```
Search codebase for:
1. Direct calls: function_name(
2. Gateway imports: from gateway import function_name
3. Dynamic calls: getattr, __dict__ access
4. Test files: test_*.py

Document all call sites for update.
```

### Step 1.2: Check Dependency Layer
```
Consult Interface Cross-Reference Matrix:
- L0 Interface? → Nothing depends on it
- L1 Interface? → Check L2 dependencies
- L2 Interface? → Check L3 dependencies
- L3 Interface? → Check L4 dependencies
- L4 Interface? → Many dependencies likely

Higher layer = more impact
```

### Step 1.3: Assess Breaking Changes
```
BREAKING CHANGES:
- Removed parameters
- Changed parameter types
- Changed return structure
- Renamed function
- Changed behavior contract

NON-BREAKING:
- Added optional parameters
- Additional return fields
- Enhanced validation
- Performance improvements
```

### Step 1.4: Plan Migration Strategy

**For Breaking Changes:**
```
Option A: Deprecation Path
1. Keep old function
2. Add new function with v2 suffix
3. Update callers gradually
4. Remove old function later

Option B: Big Bang
1. Update function
2. Update all callers simultaneously
3. Test comprehensively
4. Deploy together

Choose based on deployment constraints.
```

---

## 🔧 PHASE 2: IMPLEMENTATION (10-15 minutes)

### Step 2.1: Update Core Layer
```
Location: /src/[module]_core.py

Process:
1. Fetch current file
2. Update _internal_function
3. Maintain private naming
4. Update type hints
5. Update docstring
6. Add migration notes if breaking
```

### Step 2.2: Update Interface Layer
```
Location: /src/[module]_operations.py

Process:
1. Fetch current file
2. Update public function signature
3. Update parameter validation
4. Update core function call
5. Handle old parameter format (if backward compat)
6. Update return value transformation
```

### Step 2.3: Update Gateway (if needed)
```
Location: /src/gateway.py

Only update if:
- Function renamed
- Module renamed
- Signature changed significantly

Update LAZY_IMPORTS entry if needed.
```

### Step 2.4: Update All Callers
```
For each call site identified:
1. Update function call
2. Update parameters
3. Handle new return format
4. Update error handling if changed
```

---

## 🧪 PHASE 3: TESTING (5-10 minutes)

### Step 3.1: Unit Tests
```
Test scenarios:
- New parameter combinations
- Old parameter format (if supported)
- Edge cases with new logic
- Error conditions
```

### Step 3.2: Integration Tests
```
Test:
- All identified call sites
- Cross-interface interactions
- End-to-end flows
```

### Step 3.3: Regression Tests
```
Verify:
- Old functionality still works
- Dependent interfaces unaffected
- Performance not degraded
```

---

## 📝 PHASE 4: DOCUMENTATION (5 minutes)

### Step 4.1: Update Interface Entry
```
Update INT-## entry:
- New signature documented
- Parameters explained
- Return value structure
- Breaking changes noted
- Migration guide (if needed)
```

### Step 4.2: Update Function Catalog
```
If interface has catalog (INT-01, INT-02, etc.):
- Update function signature
- Update example code
- Add "Changed in v.X.X" note
```

### Step 4.3: Update Cross-References
```
Check if cross-reference matrix needs updates:
- Dependencies changed?
- Usage patterns changed?
- Performance characteristics changed?
```

### Step 4.4: Create Migration Entry (if breaking)
```
Create LESS-## entry:
- What changed
- Why changed
- How to migrate
- Code examples (before/after)
```

---

## ⚠️ COMMON PITFALLS

### Pitfall 1: Missed Call Sites
```
❌ DON'T:
Search for "function_name" only

✅ DO:
Search for:
- "function_name("
- "from gateway import.*function_name"
- Dynamic access patterns
- Test files
```

### Pitfall 2: Breaking Backward Compatibility
```
❌ DON'T:
Remove parameters without warning

✅ DO:
Support old parameters with deprecation warning:
def function(new_param, old_param=None):
    if old_param is not None:
        log_deprecation("old_param deprecated")
        new_param = convert(old_param)
    # Use new_param
```

### Pitfall 3: Incomplete Layer Updates
```
❌ DON'T:
Update interface but forget core

✅ DO:
Update all 3 layers:
1. Core implementation
2. Interface wrapper
3. Gateway entry (if needed)
```

### Pitfall 4: Missing Dependency Updates
```
❌ DON'T:
Update function, forget dependent interfaces

✅ DO:
Check dependency layer:
- Update direct dependents
- Update transitive dependents
- Test all affected paths
```

---

## 🎓 EXAMPLE WALKTHROUGH

### Example: Add Timeout Parameter to Cache Get

**Current Signature:**
```python
def cache_get(key: str) -> Optional[Any]:
    """Get cached value."""
    ...
```

**New Signature:**
```python
def cache_get(
    key: str,
    timeout: Optional[int] = None
) -> Optional[Any]:
    """Get cached value with optional timeout."""
    ...
```

**Step 1: Impact Analysis**
```
Callers: 15 locations across 8 files
Breaking: No (added optional parameter)
Dependencies: L2 interface (LOGGING depends on CACHE)
Strategy: Non-breaking addition
```

**Step 2: Implementation**

**Core Layer (cache_core.py):**
```python
def _get_cached_value(
    key: str,
    timeout: Optional[int] = None
) -> Optional[Any]:
    """Internal cache get with timeout."""
    # Add timeout logic
    ...
```

**Interface Layer (cache_operations.py):**
```python
def cache_get(
    key: str,
    timeout: Optional[int] = None
) -> Optional[Any]:
    """Get cached value.
    
    Args:
        key: Cache key
        timeout: Optional timeout in seconds
    
    Returns:
        Cached value or None
    """
    from .cache_core import _get_cached_value
    
    # Validate timeout if provided
    if timeout is not None and timeout < 0:
        raise ValueError("Timeout must be positive")
    
    return _get_cached_value(key, timeout)
```

**Gateway Layer (gateway.py):**
```python
# No changes needed - lazy import handles new signature
```

**Step 3: Testing**
```python
# Test new parameter
result = cache_get("key", timeout=5)

# Test backward compatibility
result = cache_get("key")  # Still works

# Test validation
with pytest.raises(ValueError):
    cache_get("key", timeout=-1)
```

**Step 4: Documentation**

**Update INT-01 CACHE:**
```markdown
### cache_get

**Signature:**
```python
def cache_get(
    key: str,
    timeout: Optional[int] = None
) -> Optional[Any]
```

**Parameters:**
- `key`: Cache key (required)
- `timeout`: Max wait time in seconds (optional, default: None)

**Changed in v2.1.0:** Added optional timeout parameter
```

---

## 📊 SUCCESS CRITERIA

Interface update complete when:
- ✅ All 3 layers updated (if needed)
- ✅ All call sites updated
- ✅ Backward compatibility maintained (or migration path provided)
- ✅ Tests passing (unit + integration)
- ✅ Documentation updated
- ✅ Dependent interfaces tested
- ✅ No regressions

---

## 🔗 RELATED RESOURCES

**Architecture:**
- ARCH-01: SUGA (3-layer structure)
- ARCH-02: LMMS (Dependency layers)

**Interfaces:**
- INT-## entries: Interface catalogs
- Interface Cross-Reference Matrix: Dependencies

**Lessons:**
- LESS-01: Always fetch current files
- LESS-15: SUGA verification

**Workflows:**
- WF-01: Add Feature (related process)
- WF-02: Debug Issue (if problems occur)

---

**END OF WORKFLOW-03**

**Related workflows:** WF-01 (Add Feature), WF-04 (Add Gateway Function)

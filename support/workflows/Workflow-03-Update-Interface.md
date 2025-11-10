# Workflow-03-Update-Interface.md

**Version:** 2.0.0  
**Date:** 2025-11-10  
**Category:** Support Tools  
**Type:** Workflow Template  
**Purpose:** Modify existing interface functions safely  
**Updated:** SIMAv4 paths, fileserver.php, shared knowledge references

---

## 📋 WORKFLOW OVERVIEW

**Use when:** Modifying signatures, adding parameters, changing behavior  
**Time:** 15-30 minutes  
**Complexity:** Medium-High (affects multiple layers)  
**Prerequisites:** Understanding of interface dependencies

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
- [ ] Change clearly defined
- [ ] Interface function identified
- [ ] Dependency analysis complete (who calls this?)
- [ ] Backward compatibility considered
- [ ] Breaking change approved (if applicable)

---

## 🎯 PHASE 1: IMPACT ANALYSIS (5-10 minutes)

### Step 1.1: Identify All Callers

**Search codebase via fileserver.php for:**
1. Direct calls: `function_name(`
2. Gateway imports: `from gateway import function_name`
3. Dynamic calls: `getattr`, `__dict__` access
4. Test files: `test_*.py`

Document all call sites for update.

### Step 1.2: Check Dependency Layer

**Consult cross-reference documentation:**
- `/sima/entries/interfaces/Interface-Cross-Reference-Matrix.md`
- Check which interfaces depend on this one
- Assess impact scope

**Dependency levels:**
- L0 Interface → Nothing depends on it
- L1 Interface → Check L2 dependencies
- L2 Interface → Check L3 dependencies
- Higher layer = more impact

### Step 1.3: Assess Breaking Changes

**BREAKING CHANGES:**
- Removed parameters
- Changed parameter types
- Changed return structure
- Renamed function
- Changed behavior contract

**NON-BREAKING:**
- Added optional parameters
- Additional return fields
- Enhanced validation
- Performance improvements

### Step 1.4: Plan Migration Strategy

**For Breaking Changes:**

**Option A: Deprecation Path**
```
1. Keep old function
2. Add new function with v2 suffix
3. Update callers gradually
4. Remove old function later
```

**Option B: Big Bang**
```
1. Update function
2. Update all callers simultaneously
3. Test comprehensively
4. Deploy together
```

Choose based on deployment constraints.

---

## 🔧 PHASE 2: IMPLEMENTATION (10-15 minutes)

### Step 2.1: Update Core Layer

**Location:** `/src/[module]_core.py`

**Process:**
```
1. Fetch current file via fileserver.php
2. Read complete content
3. Update _internal_function
4. Maintain private naming
5. Update type hints
6. Update docstring
7. Add migration notes if breaking
8. Output complete file as artifact
```

**REF:** `/sima/entries/lessons/core-architecture/LESS-01.md`

### Step 2.2: Update Interface Layer

**Location:** `/src/interface_*.py`

**Process:**
```
1. Fetch current file via fileserver.php
2. Read complete content
3. Update public function signature
4. Update parameter validation
5. Update core function call
6. Handle old parameter format (if backward compat)
7. Update return value transformation
8. Output complete file as artifact
```

### Step 2.3: Update Gateway (if needed)

**Location:** `/src/gateway.py`

**Only update if:**
- Function renamed
- Module renamed
- Signature changed significantly

Update function registry or lazy imports if needed.

**REF:** `/sima/entries/gateways/GATE-01.md`

### Step 2.4: Update All Callers

**For each call site identified:**
```
1. Fetch file via fileserver.php
2. Update function call
3. Update parameters
4. Handle new return format
5. Update error handling if changed
6. Output complete file as artifact
```

---

## 🧪 PHASE 3: TESTING (5-10 minutes)

### Step 3.1: Unit Tests

**Test scenarios:**
- New parameter combinations
- Old parameter format (if supported)
- Edge cases with new logic
- Error conditions

### Step 3.2: Integration Tests

**Test:**
- All identified call sites
- Cross-interface interactions
- End-to-end flows

### Step 3.3: Regression Tests

**Verify:**
- Old functionality still works
- Dependent interfaces unaffected
- Performance not degraded

**REF:** `/sima/entries/lessons/operations/LESS-15.md` (Verification Protocol)

---

## 📝 PHASE 4: DOCUMENTATION (5 minutes)

### Step 4.1: Update Interface Entry

**Update appropriate entry:**
- Location: `/sima/entries/interfaces/INT-##.md`
- New signature documented
- Parameters explained
- Return value structure
- Breaking changes noted
- Migration guide (if needed)

### Step 4.2: Update Function Catalog

**If interface has detailed catalog:**
- Update function signature
- Update example code
- Add "Changed in v.X.X" note
- Update cross-references

### Step 4.3: Update Cross-References

**Check if updates needed:**
- `/sima/entries/interfaces/Interface-Cross-Reference-Matrix.md`
- Dependencies changed?
- Usage patterns changed?
- Performance characteristics changed?

### Step 4.4: Create Migration Entry (if breaking)

**Create lesson entry:**
- Location: `/sima/entries/lessons/evolution/LESS-##.md`
- What changed
- Why changed
- How to migrate
- Code examples (before/after)

---

## ⚠️ COMMON PITFALLS

### Pitfall 1: Missed Call Sites

```
❌ DON'T:
Search for "function_name" only

✅ DO:
Search via fileserver.php for:
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

**REF:** `/sima/shared/SUGA-Architecture.md`

```
❌ DON'T:
Update interface but forget core

✅ DO:
Update all layers as needed:
1. Core implementation
2. Interface wrapper
3. Gateway entry (if needed)
```

### Pitfall 4: Missing Dependency Updates

```
❌ DON'T:
Update function, forget dependent interfaces

✅ DO:
Check dependency documentation:
- Update direct dependents
- Update transitive dependents
- Test all affected paths
```

### Pitfall 5: Stale File Access

**REF:** `/sima/entries/lessons/wisdom/WISD-06.md`

```
❌ DON'T:
Fetch files without cache-busting

✅ DO:
Always use fileserver.php URLs
Verify fresh content before modifications
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
Dependencies: L2 interface (others may depend on CACHE)
Strategy: Non-breaking addition
```

**Step 2: Implementation**

**Core Layer (cache_core.py):**
```python
# Fetch via fileserver.php first
def _get_cached_value(
    key: str,
    timeout: Optional[int] = None
) -> Optional[Any]:
    """Internal cache get with timeout."""
    # Add timeout logic
    ...
```

**Interface Layer (interface_cache.py):**
```python
# Fetch via fileserver.php first
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
# Registry remains: 'cache_get': ('interface_cache', 'cache_get')
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

**Update /sima/entries/interfaces/INT-01_CACHE-Interface-Pattern.md:**
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
- ✅ All layers updated via fileserver.php (fresh files)
- ✅ All call sites updated
- ✅ Backward compatibility maintained (or migration path provided)
- ✅ Tests passing (unit + integration)
- ✅ Documentation updated
- ✅ Dependent interfaces tested
- ✅ No regressions
- ✅ Complete files output as artifacts

---

## 🔗 RELATED RESOURCES

**Standards:**
- `/sima/shared/Artifact-Standards.md` - Complete file requirements
- `/sima/shared/File-Standards.md` - Size limits, headers
- `/sima/shared/SUGA-Architecture.md` - 3-layer pattern
- `/sima/shared/RED-FLAGS.md` - Never-suggest patterns

**Architecture:**
- `/sima/entries/gateways/GATE-01.md` - Gateway pattern
- `/sima/entries/gateways/GATE-02.md` - Lazy loading
- `/sima/languages/python/architectures/suga/` - SUGA details

**Interfaces:**
- `/sima/entries/interfaces/` - Interface catalogs (INT-01 to INT-12)
- `/sima/entries/interfaces/Interface-Cross-Reference-Matrix.md` - Dependencies

**Lessons:**
- `/sima/entries/lessons/core-architecture/LESS-01.md` - Fetch complete files
- `/sima/entries/lessons/operations/LESS-15.md` - Verification protocol
- `/sima/entries/lessons/wisdom/WISD-06.md` - Cache-busting requirement

**Workflows:**
- Workflow-01-Add-Feature.md - Related process
- Workflow-02-Debug-Issue.md - If problems occur
- Workflow-04-Add-Gateway-Function.md - Gateway updates

---

**END OF WORKFLOW-03**

**Version:** 2.0.0  
**Lines:** 398 (within 400 limit)  
**Related workflows:** WF-01 (Add Feature), WF-04 (Add Gateway Function)

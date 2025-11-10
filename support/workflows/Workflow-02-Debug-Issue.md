# Workflow-02-Debug-Issue.md

**Version:** 2.0.0  
**Date:** 2025-11-10  
**Category:** Support Tools  
**Type:** Workflow Template  
**Purpose:** Systematic debugging approach for project issues  
**Updated:** SIMAv4 paths, fileserver.php, shared knowledge references

---

## 📋 WORKFLOW OVERVIEW

**Use when:** Error occurs, unexpected behavior, performance issue  
**Time:** 10-60 minutes  
**Complexity:** Variable  
**Prerequisites:** Error message or symptom description

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
- [ ] Error message captured (full stack trace)
- [ ] Reproduction steps known
- [ ] Environment identified (dev/staging/prod)
- [ ] Recent changes noted (if any)

---

## 🎯 PHASE 1: TRIAGE (2-5 minutes)

### Step 1.1: Classify Issue Type

**ERROR TYPES:**
- Import Error → Check `/sima/entries/anti-patterns/import/`
- Circular Import → Check `/sima/entries/gateways/GATE-03.md`
- Exception → Check `/sima/entries/lessons/bugs/`
- Performance → Check `/sima/languages/python/architectures/zaph/`
- Logic Error → Trace through layers

### Step 1.2: Check Known Issues

**Search knowledge base via fileserver.php:**
- `/sima/entries/lessons/bugs/BUG-01.md` - Sentinel Object Leak
- `/sima/entries/lessons/bugs/BUG-02.md` - Cache Validation
- `/sima/entries/lessons/bugs/BUG-03.md` - Circular Import
- `/sima/entries/lessons/bugs/BUG-04.md` - Cold Start Issues

If match found → Apply documented fix immediately

### Step 1.3: Identify Layer

**Where did error occur?**
- Gateway Layer → Check gateway.py dispatch
- Interface Layer → Check interface_*.py
- Core Layer → Check *_core.py
- Cross-Layer → Architecture violation likely

**REF:** `/sima/shared/SUGA-Architecture.md`

---

## 🔍 PHASE 2: INVESTIGATION (10-20 minutes)

### Step 2.1: Trace Through Architecture Layers

**Gateway Layer:**
```
Check via fileserver.php:
1. Is operation in dispatch/registry?
2. Module/function names correct?
3. Import path valid?
4. Gateway function exists?

Common issues:
- Typo in function registry
- Missing gateway entry
- Wrong module path
```

**Interface Layer:**
```
Check via fileserver.php:
1. Function signature correct?
2. Parameters validated?
3. Imports inside function (lazy)?
4. Return value sanitized?

Common issues:
- Missing parameter validation
- Direct core imports (violation)
- Sentinel objects returned
```

**Core Layer:**
```
Check via fileserver.php:
1. Private naming (_prefix)?
2. Exception handling specific?
3. No cross-interface imports?
4. Type hints correct?

Common issues:
- Public naming on private function
- Bare except clauses
- Cross-interface coupling
```

### Step 2.2: Analyze Stack Trace

**Read bottom-up:**
1. Where did error originate? (bottom of trace)
2. Which layer called it? (middle of trace)
3. What triggered the call? (top of trace)

**Look for:**
- Repeated frames → Infinite recursion
- Unusual module names → Import issue
- Missing frames → Exception swallowed

### Step 2.3: Check RED FLAGS

**Verify against:**
- `/sima/shared/RED-FLAGS.md` - Never-suggest patterns
- `/sima/entries/anti-patterns/` - Common violations

**Common violations:**
- Threading primitives (single-threaded environments)
- Direct core imports (architecture violation)
- Bare except clauses (swallows errors)
- Sentinel objects (serialization failures)
- Global mutable state (memory leaks)
- Heavy imports at module level (cold start)

### Step 2.4: Reproduce Systematically

**Minimal reproduction:**
1. Isolate the operation
2. Remove unrelated code
3. Test with minimal input
4. Verify error still occurs

**Goal:** Identify exact line/condition causing issue

---

## 🔧 PHASE 3: ROOT CAUSE ANALYSIS (5-15 minutes)

### Step 3.1: Identify Root Cause

**Not the symptom, the fundamental issue.**

**Example Analysis:**
```
Symptom: AttributeError: 'NoneType' object has no attribute 'get'
Proximate: Function returned None instead of dict
Root Cause: Missing error handling for API timeout

Symptom: ImportError: cannot import name 'X'
Proximate: Module X not found
Root Cause: Circular dependency between modules
```

### Step 3.2: Classify Root Cause

**CATEGORIES:**
1. Architecture Violation (pattern broken)
2. Logic Error (wrong algorithm/condition)
3. Data Error (invalid/unexpected input)
4. Integration Error (external system failure)
5. Environment Error (config/deployment issue)

### Step 3.3: Determine Fix Scope

**Scope levels:**
- Single line fix → Quick patch
- Function refactor → Medium complexity
- Layer restructure → High complexity
- Architecture change → Requires design review

---

## 🛠️ PHASE 4: RESOLUTION (10-30 minutes)

### Step 4.1: Design Fix

**Follow architecture principles:**
```
1. Fix at correct layer:
   - Data validation → Interface
   - Business logic → Core
   - Dispatch logic → Gateway

2. Maintain separation:
   - No direct imports
   - No cross-layer coupling
   - Lazy loading preserved

3. Add prevention:
   - Validation for bad inputs
   - Type hints for correctness
   - Error handling for edge cases
```

### Step 4.2: Implement Fix

**Process:**
```
1. Fetch current file via fileserver.php (CRITICAL)
2. Read complete content
3. Implement fix in correct layer
4. Add error handling
5. Add logging for future debugging
6. Output complete file as artifact
```

**REF:** `/sima/entries/lessons/core-architecture/LESS-01.md`

### Step 4.3: Verify Fix

**Test cases:**
1. Original error case (should pass)
2. Happy path (still works)
3. Edge cases (no regression)
4. Performance (no degradation)

**REF:** `/sima/entries/lessons/operations/LESS-15.md` (Verification Protocol)

---

## 📝 PHASE 5: DOCUMENTATION (5 minutes)

### Step 5.1: Document Root Cause

**If novel issue, create BUG-## entry:**
- Location: `/sima/entries/lessons/bugs/BUG-##.md`
- Content: Symptom, root cause, fix, prevention

**Template:** `/sima/shared/Common-Patterns.md`

### Step 5.2: Update Relevant Entries

**Update if applicable:**
- Interface entries: `/sima/entries/interfaces/INT-##.md`
- Project entries: `/sima/projects/[project]/lessons/`
- Anti-pattern entries: `/sima/entries/anti-patterns/[category]/AP-##.md`

### Step 5.3: Add to Indexes

**Update indexes:**
- Add problem-based lookup: "When you see [symptom] → Check BUG-##"
- Update cross-reference matrix
- Add to quick index

---

## ⚠️ DEBUG ANTI-PATTERNS

### Anti-Pattern 1: Guessing Without Data

**REF:** `/sima/entries/anti-patterns/process/AP-28.md`

```
❌ DON'T:
"It's probably the cache"
[Makes cache changes without verification]

✅ DO:
Add logging to confirm cache involvement
Test with cache disabled
Verify hypothesis before fixing
```

### Anti-Pattern 2: Symptom Fixing

```
❌ DON'T:
"Added try/except to hide the error"

✅ DO:
Find why error occurs
Fix root cause
Add proper error handling
```

### Anti-Pattern 3: Partial File Modifications

**REF:** `/sima/entries/lessons/core-architecture/LESS-01.md`

```
❌ DON'T:
"Just change line 42 to..."

✅ DO:
Fetch complete current file via fileserver.php
Make changes in context
Output complete file as artifact
```

### Anti-Pattern 4: Skipping Reproduction

```
❌ DON'T:
"I think I know what's wrong"
[Makes changes without testing]

✅ DO:
Reproduce error reliably
Verify fix resolves error
Test for regressions
```

---

## 🎓 EXAMPLE WALKTHROUGH

### Example: Debug Circular Import Error

**Symptom:**
```
ImportError: cannot import name 'cache_get' from 'gateway'
```

**Step 1: Triage**
```
Type: Import Error → Architecture violation likely
Layer: Gateway (import statement)
Known: Check BUG-03 (Circular Import patterns)
```

**Step 2: Investigation**
```
Trace:
1. Module A imports from gateway
2. Gateway tries to import Module A
3. Circular dependency detected

Fetch gateway.py via fileserver.php
Check:
- Lazy imports present?
- Import at module level? (violation)
```

**Step 3: Root Cause**
```
Symptom: ImportError
Proximate: Gateway imports at module level
Root Cause: Violation of lazy loading pattern
```

**Step 4: Resolution**
```
Fix: Move import inside function

Change:
  from module_operations import function  # Module level

To:
  def gateway_function():
      from module_operations import function  # Lazy
      return function()
```

**Step 5: Documentation**
```
Update: /sima/entries/gateways/GATE-02.md with example
Add: "Circular import error → Check lazy loading"
Note: Prevention - always use lazy imports in gateway
```

---

## 📊 SUCCESS CRITERIA

Debug complete when:
- ✅ Root cause identified (not just symptom)
- ✅ Fix implemented at correct layer
- ✅ Original error resolved
- ✅ No regressions introduced
- ✅ Tests passing
- ✅ Issue documented (if novel)
- ✅ Prevention strategy added
- ✅ Complete file output as artifact

---

## 🔗 RELATED RESOURCES

**Standards:**
- `/sima/shared/Artifact-Standards.md` - Complete file requirements
- `/sima/shared/File-Standards.md` - Size limits, headers
- `/sima/shared/RED-FLAGS.md` - Never-suggest patterns

**Known Bugs:**
- `/sima/entries/lessons/bugs/BUG-01.md` - Sentinel Object Leak
- `/sima/entries/lessons/bugs/BUG-02.md` - Cache Validation
- `/sima/entries/lessons/bugs/BUG-03.md` - Circular Import
- `/sima/entries/lessons/bugs/BUG-04.md` - Cold Start

**Anti-Patterns:**
- `/sima/entries/anti-patterns/error-handling/` - Exception handling
- `/sima/entries/anti-patterns/concurrency/` - Threading issues
- `/sima/entries/anti-patterns/import/` - Import violations

**Lessons:**
- `/sima/entries/lessons/core-architecture/LESS-01.md` - Fetch complete files
- `/sima/entries/lessons/operations/LESS-15.md` - Verification protocol
- `/sima/entries/lessons/operations/LESS-09.md` - Systematic debugging

**Architecture:**
- `/sima/shared/SUGA-Architecture.md` - 3-layer pattern
- `/sima/entries/gateways/GATE-02.md` - Lazy loading pattern
- `/sima/entries/gateways/GATE-03.md` - Cross-interface rules

**Workflows:**
- Workflow-01-Add-Feature.md - Related process
- Workflow-03-Update-Interface.md - Interface modifications

---

**END OF WORKFLOW-02**

**Version:** 2.0.0  
**Lines:** 395 (within 400 limit)  
**Related workflows:** WF-01 (Add Feature), WF-03 (Update Interface)

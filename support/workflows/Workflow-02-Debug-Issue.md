# File: Workflow-02-Debug-Issue.md

**REF-ID:** WF-02  
**Version:** 1.0.0  
**Category:** Support Tools  
**Type:** Workflow Template  
**Purpose:** Systematic debugging approach for SUGA architecture

---

## 📋 WORKFLOW OVERVIEW

**Use when:** Error occurs, unexpected behavior, performance issue  
**Time:** 10-60 minutes  
**Complexity:** Variable  
**Prerequisites:** Error message or symptom description

---

## ✅ PRE-WORK CHECKLIST

Before starting:
- [ ] Error message captured (full stack trace)
- [ ] Reproduction steps known
- [ ] Environment identified (dev/staging/prod)
- [ ] Recent changes noted (if any)

---

## 🎯 PHASE 1: TRIAGE (2-5 minutes)

### Step 1.1: Classify Issue Type
```
ERROR TYPES:
- Import Error → SUGA violation (check RULE-01)
- Circular Import → Gateway issue (check GATE-01)
- Exception → Check known bugs (BUG-01 to BUG-04)
- Performance → Check ZAPH violations (ARCH-04)
- Logic Error → Trace through layers
```

### Step 1.2: Check Known Issues
```
Search neural maps:
- BUG-01: Circular Import Sentinel Bug
- BUG-02: Cache Serialization Failures
- BUG-03: Memory Leak from Global State
- BUG-04: Race Conditions in Async

If match found → Apply documented fix immediately
```

### Step 1.3: Identify Layer
```
Where did error occur?
- Gateway Layer → Check gateway.py dispatch
- Interface Layer → Check [module]_operations.py
- Core Layer → Check [module]_core.py
- Cross-Layer → SUGA violation likely
```

---

## 🔍 PHASE 2: INVESTIGATION (10-20 minutes)

### Step 2.1: Trace Through SUGA Layers

**Gateway Layer:**
```
Check:
1. Is operation in LAZY_IMPORTS?
2. Module/function names correct?
3. Import path valid?
4. Gateway function exists?

Common issues:
- Typo in LAZY_IMPORTS
- Missing gateway entry
- Wrong module path
```

**Interface Layer:**
```
Check:
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
Check:
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
```
Read bottom-up:
1. Where did error originate? (bottom of trace)
2. Which layer called it? (middle of trace)
3. What triggered the call? (top of trace)

Look for:
- Repeated frames → Infinite recursion
- Unusual module names → Import issue
- Missing frames → Exception swallowed
```

### Step 2.3: Check RED FLAGS
```
Common violations:
- Threading locks (DEC-04, AP-08)
- Direct core imports (RULE-01)
- Bare except clauses (AP-05)
- Sentinel objects (BUG-01)
- Global mutable state (BUG-03)
- Heavy imports at module level (ARCH-04)
```

### Step 2.4: Reproduce Systematically
```
Minimal reproduction:
1. Isolate the operation
2. Remove unrelated code
3. Test with minimal input
4. Verify error still occurs

Goal: Identify exact line/condition causing issue
```

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
```
CATEGORIES:
1. Architecture Violation (SUGA broken)
2. Logic Error (wrong algorithm/condition)
3. Data Error (invalid/unexpected input)
4. Integration Error (external system failure)
5. Environment Error (config/deployment issue)
```

### Step 3.3: Determine Fix Scope
```
Scope levels:
- Single line fix → Quick patch
- Function refactor → Medium complexity
- Layer restructure → High complexity
- Architecture change → Requires design review
```

---

## 🛠️ PHASE 4: RESOLUTION (10-30 minutes)

### Step 4.1: Design Fix

**Follow SUGA principles:**
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
1. Fetch current file (CRITICAL)
2. Read complete content
3. Implement fix in correct layer
4. Add error handling
5. Add logging for future debugging
6. Output complete file as artifact
```

### Step 4.3: Verify Fix
```
Test cases:
1. Original error case (should pass)
2. Happy path (still works)
3. Edge cases (no regression)
4. Performance (no degradation)
```

---

## 📝 PHASE 5: DOCUMENTATION (5 minutes)

### Step 5.1: Document Root Cause
```
If novel issue, create BUG-## entry:
- Symptom description
- Root cause analysis
- Fix implementation
- Prevention strategy
```

### Step 5.2: Update Relevant Entries
```
Update if applicable:
- Interface entries (INT-##)
- Project entries (NMP01-LEE-##)
- Anti-pattern entries (AP-##)
```

### Step 5.3: Add to Quick Indexes
```
Add problem-based lookup:
"When you see [symptom] → Check BUG-##"
```

---

## ⚠️ DEBUG ANTI-PATTERNS

### Anti-Pattern 1: Guessing Without Data
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
```
❌ DON'T:
"Just change line 42 to..."

✅ DO:
Fetch complete current file
Make changes in context
Output complete file
```
**Source:** LESS-01

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
Type: Import Error → SUGA violation likely
Layer: Gateway (import statement)
Known: Check BUG-01 (Circular Import Sentinel)
```

**Step 2: Investigation**
```
Trace:
1. Module A imports from gateway
2. Gateway tries to import Module A
3. Circular dependency detected

Check gateway.py:
- LAZY_IMPORTS present?
- Import at module level? (violation)
```

**Step 3: Root Cause**
```
Symptom: ImportError
Proximate: Gateway imports at module level
Root Cause: Violation of GATE-01 (lazy loading)
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
Update: GATE-01 with example
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

---

## 🔗 RELATED RESOURCES

**Known Bugs:**
- BUG-01: Circular Import Sentinel
- BUG-02: Cache Serialization
- BUG-03: Memory Leaks
- BUG-04: Race Conditions

**Anti-Patterns:**
- AP-05: Exception Handling
- AP-08: Threading Locks
- AP-##: Other common mistakes

**Lessons:**
- LESS-01: Always fetch current files
- LESS-15: SUGA verification checklist

**Architecture:**
- ARCH-01: SUGA (3-layer structure)
- GATE-01: Gateway lazy loading

---

**END OF WORKFLOW-02**

**Related workflows:** WF-01 (Add Feature), WF-03 (Update Interface)

# DD2-LESS-01-Dependencies-Cost.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Every dependency has a cost - minimize them  
**Type:** Lesson Learned

---

## LESSON: Dependencies Are Expensive - Use Sparingly

**Context:** SUGA architecture development  
**Discovered:** 2024-09-20  
**Impact:** All future architecture decisions  
**Severity:** High

---

## PROBLEM OBSERVED

Added many dependencies between modules without thinking about cost:
- Module A imported B, C, D, E, F
- Module B imported A, C, D
- Module C imported A, B, E, F
- Result: Tangled mess, impossible to test or refactor

**Symptoms:**
- Testing one module required mocking 5+ others
- Changing one module broke tests in 10+ others
- Circular imports appearing everywhere
- Cold start time increasing (all imports at module level)
- Difficult to understand what depends on what

---

## ROOT CAUSE

**Misunderstanding:** "Imports are free, just import what you need"

**Reality:** Every import has costs:

1. **Load Time Cost**
   - Python must load and execute module
   - Adds to cold start time
   - Cascades through dependency tree

2. **Coupling Cost**
   - Changes to imported module may break you
   - Tighter coupling = harder refactoring
   - More integration testing needed

3. **Testing Cost**
   - Must mock all dependencies
   - Test setup becomes complex
   - Slower test execution

4. **Cognitive Cost**
   - Must understand imported module
   - More code to keep in head
   - Harder to reason about

5. **Maintenance Cost**
   - More places to update when changing
   - More potential breakage points
   - More code review needed

---

## WHAT WE LEARNED

### Quantified Impact

**Before (Many Dependencies):**
```python
# cache_core.py (8 imports!)
import logging_core
import metrics_core
import security_core
import validation_core
import utility_core
import config_core
import debug_core
import error_core

def get_impl(key):
    logging_core.log("Getting key")
    metrics_core.increment("cache_get")
    security_core.validate_key(key)
    validation_core.check_format(key)
    # ... implementation
```

**Measurements:**
- Cold start: 2.8 seconds
- Test setup time: 3.2 seconds (8 mocks needed)
- Lines to understand: 2,400+ (8 modules)
- Breakage from changes: High (8 potential break points)

**After (Minimal Dependencies):**
```python
# cache_core.py (1 import!)
import cache_types

def get_impl(key, logger=None, metrics=None):
    if logger:
        logger("Getting key")
    if metrics:
        metrics.increment("cache_get")
    # ... implementation
```

**Measurements:**
- Cold start: 0.8 seconds (3.5x faster!)
- Test setup time: 0.4 seconds (8x faster!)
- Lines to understand: 150
- Breakage from changes: Low (1 dependency)

### Key Insights

**1. Most Dependencies Are Optional**
- Logging? Pass logger if needed
- Metrics? Pass tracker if needed
- Validation? Do at boundary, not in core

**2. Dependency Injection > Direct Import**
- Caller provides dependencies
- Core doesn't need to know about them
- Much easier to test

**3. Types Can Be Separate**
- Extract types to separate file
- Both modules import types
- Breaks circular dependencies

**4. Lazy Imports for Optional Features**
- Import only when feature used
- Don't pay cost for unused features
- Keeps cold path fast

---

## SOLUTION PATTERN

### Step 1: Identify Unnecessary Dependencies

**Questions to ask:**
- Is this import essential to core functionality?
- Could this be passed as a parameter?
- Is this only used in one function? (Use lazy import)
- Is this just for types? (Extract to types file)

### Step 2: Refactor to Reduce

**Pattern: Dependency Injection**
```python
# Before
def process(data):
    import logger
    import metrics
    logger.log("Processing")
    metrics.increment("process")
    return transform(data)

# After
def process(data, logger=None, metrics=None):
    if logger:
        logger("Processing")
    if metrics:
        metrics.increment("process")
    return transform(data)
```

**Pattern: Lazy Import**
```python
# Before (module level)
import expensive_module  # Always loaded!

def rarely_used():
    return expensive_module.do_something()

# After (function level)
def rarely_used():
    import expensive_module  # Only loaded when called
    return expensive_module.do_something()
```

**Pattern: Types Extraction**
```python
# Before (circular dependency)
# module_a.py
from module_b import TypeB

# module_b.py
from module_a import TypeA

# After (no circular dependency)
# types.py
class TypeA: pass
class TypeB: pass

# module_a.py
from types import TypeB

# module_b.py
from types import TypeA
```

### Step 3: Measure Impact

**Before refactoring:**
- Count current dependencies
- Measure cold start time
- Measure test setup time
- Note coupling level

**After refactoring:**
- Verify dependency reduction
- Measure performance improvements
- Verify tests still pass
- Document changes

---

## WHEN TO APPLY

**Always question new dependencies:**
- âœ… Need data types? → Separate types file
- âœ… Need logging? → Dependency injection
- âœ… Need metrics? → Dependency injection
- âœ… Need validation? → Do at boundary layer
- âœ… Need rare feature? → Lazy import
- âŒ Need core business logic? → Direct import OK

**Red flags (too many dependencies):**
- File imports 5+ modules
- Testing requires 5+ mocks
- Cold start time increasing
- Circular import errors appearing

---

## RELATED

**Decisions:**
- DD2-DEC-01: Higher-Lower Flow
- DD2-DEC-02: No Circular Dependencies
- DEC-07: Dependencies < 128MB (AWS Lambda)

**Lessons:**
- LESS-02: Measure Don't Guess
- LMMS-LESS-01: Profile First Always

**Anti-Patterns:**
- AP-02: Module-Level Heavy Imports
- AP-01: Direct Core Import

---

## EXAMPLES

### Bad: Too Many Dependencies

```python
# user_service.py
import logging_service
import metrics_service
import cache_service
import validation_service
import security_service
import notification_service
import audit_service

def create_user(data):
    logging_service.log("Creating user")
    metrics_service.increment("user_create")
    cache_service.clear("users")
    validation_service.validate(data)
    security_service.check_permissions()
    notification_service.send("User created")
    audit_service.record("CREATE", data)
    return save(data)
```

**Problems:**
- 7 dependencies
- All loaded at module level
- Hard to test (7 mocks needed)
- Tight coupling to many modules

### Good: Minimal Dependencies

```python
# user_service.py
from user_types import User, UserError

def create_user(data, logger=None, metrics=None, cache=None):
    if logger:
        logger("Creating user")
    if metrics:
        metrics.increment("user_create")
    if cache:
        cache.clear("users")
    
    user = User(**data)
    return save(user)
```

**Benefits:**
- 1 dependency (types only)
- Optional features via parameters
- Easy to test (minimal mocking)
- Loose coupling

---

## MEASUREMENT

**Track these metrics:**
```python
# Dependency count per file
MAX_IMPORTS = 3  # Guideline, not hard limit

# Cold start impact
COLD_START_TARGET = "< 3 seconds"

# Test complexity
MAX_MOCKS_PER_TEST = 3

# Coupling score
COUPLING_SCORE = "imports / total_files"  # Lower is better
```

---

## KEYWORDS

dependencies, coupling, imports, testing, performance, architecture, code-organization, maintainability

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial lesson document
- Dependency cost quantified
- Refactoring patterns documented
- Measurement criteria defined

---

**END OF FILE**

# DD2-DEC-02-No-Circular-Dependencies.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Decision to prohibit all circular dependencies  
**Type:** Architecture Decision

---

## DECISION: Zero Tolerance for Circular Dependencies

**Status:** Adopted  
**Date:** 2024-09-15  
**Context:** Python import system limitations  
**Impact:** All Python projects using modular architecture

---

## PROBLEM STATEMENT

Circular dependencies in Python cause:
- Import errors (ModuleNotFoundError at runtime)
- Initialization order issues
- Difficult-to-debug failures
- Fragile code that breaks unexpectedly
- Testing challenges

**Example of Circular Import:**
```python
# module_a.py
from module_b import B
class A:
    def use_b(self):
        return B()

# module_b.py
from module_a import A  # âŒ Circular!
class B:
    def use_a(self):
        return A()
```

Python cannot resolve this - one import will fail at runtime.

---

## DECISION

**No circular dependencies allowed at any level:**
- Module level (module A â†" module B)
- Package level (package X â†" package Y)  
- Layer level (Gateway â†" Core)

**Enforcement:**
- Automated checks in CI/CD
- Manual review during code review
- Refactor immediately when detected

---

## RATIONALE

### Why Circular Dependencies Fail

**Python's Import Mechanism:**
1. Python imports module top-to-bottom
2. When it hits an import statement, it loads that module
3. If that module imports the original, you have a cycle
4. Result: One module is partially initialized, causing errors

**Real Example (BUG-03):**
```python
# cache_core.py
from validation_core import validate  # Starts loading validation_core

# validation_core.py  
from cache_core import CacheMiss  # Tries to load cache_core again!
# ERROR: cache_core not fully loaded yet!
```

### Benefits of Zero Circular Dependencies

**1. Predictable Initialization**
- Modules load in clear order
- No partial initialization
- Easy to reason about

**2. Better Testability**
- Can test modules independently
- Clear dependency graph for test ordering
- Mock dependencies easily

**3. Clearer Architecture**
- Forces proper layer separation
- Reveals coupling issues early
- Improves code organization

**4. Easier Refactoring**
- Safe to modify modules
- Clear impact analysis
- No hidden dependencies

---

## IMPLEMENTATION

### Detection Tools

**Static Analysis:**
```python
# detect_circular.py
import sys
from pathlib import Path

def find_circular_imports(start_dir):
    graph = build_import_graph(start_dir)
    cycles = detect_cycles(graph)
    return cycles

def detect_cycles(graph):
    visited = set()
    path = []
    cycles = []
    
    for node in graph:
        if node not in visited:
            dfs(node, graph, visited, path, cycles)
    
    return cycles
```

**CI/CD Integration:**
```yaml
# .github/workflows/check-imports.yml
- name: Check Circular Dependencies
  run: |
    python detect_circular.py
    if [ $? -ne 0 ]; then
      echo "Circular dependencies detected!"
      exit 1
    fi
```

### Breaking Circular Dependencies

**Pattern 1: Lazy Import**
```python
# cache_core.py
def get_impl(key):
    from validation_core import validate  # âœ… Function-level import
    validate(key)
    return cache[key]
```

**Pattern 2: Dependency Injection**
```python
# cache_core.py
def get_impl(key, validator=None):  # âœ… Pass dependency
    if validator:
        validator(key)
    return cache[key]
```

**Pattern 3: Extract Common Dependency**
```python
# Create types.py for shared types
# cache_types.py
class CacheMiss:
    pass

# cache_core.py
from cache_types import CacheMiss  # âœ… No cycle

# validation_core.py
from cache_types import CacheMiss  # âœ… No cycle
```

**Pattern 4: Invert Dependency**
```python
# Instead of A â†" B
# Make C that both depend on:
# A â†' C â†� B
```

---

## CONSEQUENCES

### Positive

**Stable Imports:**
- All imports work consistently
- No runtime import errors
- Predictable behavior

**Clear Architecture:**
- Dependency graph is a DAG (directed acyclic graph)
- Easy to visualize
- Clear layering

**Better Testing:**
- Bottom-up test strategy works
- No initialization order issues
- Isolated unit tests possible

### Negative

**Requires Planning:**
- Must think about dependencies upfront
- Cannot just import whatever you need
- May need refactoring to break cycles

**Mitigation:** Use patterns above to break cycles systematically

---

## ALTERNATIVES CONSIDERED

### Alternative 1: Allow Circular, Use Lazy Imports Everywhere
**Why rejected:** Lazy imports everywhere is hard to maintain, easy to forget

### Alternative 2: Allow Circular, Restructure Import Order
**Why rejected:** Fragile, breaks easily, difficult to maintain

### Alternative 3: Use Import Hooks to Resolve Cycles
**Why rejected:** Too complex, adds magic, harder to debug

---

## RELATED

**Decisions:**
- DD2-DEC-01: Higher-Lower Flow (prevents most cycles)
- DEC-01: SUGA Architecture (enforces layers)

**Lessons:**
- DD2-LESS-02: Layer Violations Create Cycles
- LESS-03: Gateway as Single Entry Point

**Bugs:**
- BUG-03: Circular Import Error (real example)

**Anti-Patterns:**
- AP-03: Circular Module References
- AP-01: Direct Core Import (can cause cycles)

---

## EXAMPLES

### Detecting a Cycle

**Tool output:**
```
âŒ Circular dependency detected!

Cycle:
  cache_core.py 
    â†' validation_core.py
    â†' cache_core.py

Breaking point needed at: validation_core.py
```

### Breaking the Cycle

**Before (Circular):**
```python
# cache_core.py
from validation_core import validate

def get_impl(key):
    validate(key)
    return cache[key]

# validation_core.py
from cache_core import CacheMiss

def validate(key):
    if not key:
        raise CacheMiss("Empty key")
```

**After (Fixed):**
```python
# cache_types.py (new file)
class CacheMiss(Exception):
    pass

# cache_core.py
from cache_types import CacheMiss

def get_impl(key):
    # Lazy import to break cycle
    from validation_core import validate
    validate(key)
    return cache[key]

# validation_core.py
from cache_types import CacheMiss

def validate(key):
    if not key:
        raise CacheMiss("Empty key")
```

---

## VERIFICATION

**Automated Check:**
```bash
# Run circular dependency detector
python detect_circular.py src/

# Expected output:
# âœ… No circular dependencies detected
# âœ… All imports form a DAG
# âœ… Safe to import in any order
```

**Manual Review:**
1. Check all imports in modified files
2. Trace dependency chains
3. Verify no back-references exist

---

## ENFORCEMENT

**Code Review Checklist:**
- [ ] No module imports another that imports it back
- [ ] All imports follow clear hierarchy
- [ ] Common types in separate files if needed
- [ ] Lazy imports used only when necessary

**CI/CD Gates:**
- Circular dependency check runs on every PR
- Merge blocked if cycles detected
- Report shows exact cycle path

---

## KEYWORDS

circular-dependencies, import-errors, dag, dependency-graph, module-organization, python-imports, architecture-rules, testing-strategy

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial decision document
- Zero tolerance policy defined
- Breaking patterns documented
- Detection tools specified

---

**END OF FILE**

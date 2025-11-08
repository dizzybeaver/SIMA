# AP-03-Circular-Module-References.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Anti-pattern for circular module dependencies  
**Category:** Anti-Pattern  
**Severity:** Critical

---

## ANTI-PATTERN

**Creating circular import dependencies where module A imports module B and module B imports module A.**

---

## DESCRIPTION

Circular imports occur when two or more modules import each other, creating a dependency cycle. Python's import system can handle some circular imports, but they often lead to `AttributeError` or `ImportError` at runtime.

---

## WHY IT'S WRONG

### Technical Problems

1. **Import Failures**: Modules partially initialized when accessed
2. **Attribute Errors**: Functions/classes not yet defined when imported
3. **Initialization Order**: Unpredictable module initialization sequence
4. **Runtime Failures**: Errors occur during execution, not at module load
5. **Testing Difficulty**: Hard to isolate and test modules independently

### SUGA-Specific Issues

In SUGA architecture:
- Gateway layer must not depend on Core layer implementations
- Interface layer mediates, preventing direct dependencies
- Core layer should be self-contained
- Violates unidirectional flow principle

---

## REAL-WORLD EXAMPLE

### ❌ WRONG: Circular Import

```python
# cache_core.py
from logging_core import log_debug  # ❌ Imports logging

def get_cache(key):
    log_debug(f"Cache get: {key}")  # Uses logging
    return cache.get(key)

# logging_core.py
from cache_core import get_cache  # ❌ Imports cache

def log_debug(message):
    # Try to cache log messages
    cached = get_cache(f"log_{message}")  # ❌ Circular dependency
    if not cached:
        print(message)

# Result: ImportError or AttributeError at runtime
```

**Problem:**
- cache_core imports logging_core
- logging_core imports cache_core
- One module will be partially initialized
- Function calls will fail with AttributeError

### ✓ CORRECT: Use Gateway Pattern

```python
# cache_core.py
# No direct imports of other cores

def get_cache_impl(key):
    """Core cache implementation.
    
    Note: Does not log directly. Logging handled by interface layer.
    """
    return cache.get(key)

# interface_cache.py
def get_cache(key):
    """Cache get with logging."""
    import logging_core  # Lazy import
    import cache_core
    
    logging_core.log_debug(f"Cache get: {key}")
    return cache_core.get_cache_impl(key)

# No circular dependency: interface imports both cores
```

**Benefits:**
- No circular dependencies
- Clear separation of concerns
- Interface layer orchestrates
- Both cores remain independent

---

## SEVERITY METRICS

### Impact Levels

**Critical Severity:**
- Direct circular imports between core modules
- AttributeError at runtime
- Complete feature failure

**High Severity:**
- Indirect circular imports (A→B→C→A)
- Intermittent failures
- Hard-to-debug issues

**Medium Severity:**
- Circular type hint imports
- Import-time side effects
- Complex dependency graphs

---

## IDENTIFICATION

### Detection Methods

**Static Analysis:**
```python
# detect_circular_imports.py
import ast
import os
from collections import defaultdict

def find_circular_imports(directory):
    imports = defaultdict(set)
    
    for filename in os.listdir(directory):
        if filename.endswith('.py'):
            module = filename[:-3]
            with open(os.path.join(directory, filename)) as f:
                tree = ast.parse(f.read())
                for node in ast.walk(tree):
                    if isinstance(node, ast.Import):
                        for alias in node.names:
                            imports[module].add(alias.name)
                    elif isinstance(node, ast.ImportFrom):
                        if node.module:
                            imports[module].add(node.module)
    
    # Find cycles
    def has_cycle(start, current, visited):
        if current == start and visited:
            return True
        if current in visited:
            return False
        visited.add(current)
        for next_module in imports.get(current, []):
            if has_cycle(start, next_module, visited.copy()):
                return True
        return False
    
    for module in imports:
        if has_cycle(module, module, set()):
            print(f"Circular import detected involving: {module}")
```

**Runtime Detection:**
```python
# At module top
import sys
if __name__ in sys.modules:
    raise ImportError(f"Circular import detected: {__name__}")
```

---

## CORRECTION

### Strategy 1: Use Gateway Pattern (Recommended)

```python
# Instead of:
# core_a.py imports core_b.py
# core_b.py imports core_a.py

# Do:
# interface.py imports both core_a.py and core_b.py
# core_a.py and core_b.py remain independent

# gateway.py
def feature_requiring_both():
    import core_a
    import core_b
    result_a = core_a.function_a()
    result_b = core_b.function_b(result_a)
    return result_b
```

### Strategy 2: Lazy Imports

```python
# Instead of module-level import
def function_needing_other():
    from other_module import function  # Lazy import
    return function()
```

### Strategy 3: Dependency Injection

```python
# core_a.py
def function_a(logger=None):
    """Function with optional logger dependency."""
    if logger:
        logger.log("Processing")
    # Do work
    return result

# interface.py
import core_a
import logging_core

def feature():
    return core_a.function_a(logger=logging_core)
```

### Strategy 4: Extract Common Code

```python
# If both modules need same code, extract to common module

# common_utils.py
def shared_function():
    return "shared"

# core_a.py
from common_utils import shared_function  # No circular

# core_b.py
from common_utils import shared_function  # No circular
```

---

## PREVENTION

### Design Principles

**1. Unidirectional Flow**
```
Gateway → Interface → Core
Never: Core → Interface
Never: Interface → Gateway
```

**2. Layer Independence**
- Core modules don't import other core modules
- Interface layer coordinates between cores
- Gateway layer provides public API

**3. Dependency Inversion**
- Depend on abstractions, not implementations
- Use interfaces/protocols
- Inject dependencies

### Code Organization

```python
# Good structure preventing circular imports

# Layer 1: Core (independent)
cache_core.py         # No imports from this project
logging_core.py       # No imports from this project
security_core.py      # No imports from this project

# Layer 2: Interface (imports cores)
interface_cache.py    # Imports cache_core only
interface_logging.py  # Imports logging_core only
interface_security.py # Imports security_core only

# Layer 3: Gateway (imports interfaces)
gateway_wrappers.py   # Imports interface_* modules
gateway.py            # Imports gateway_wrappers
```

---

## TYPE HINT CIRCULAR IMPORTS

### The Problem

```python
# user.py
from post import Post  # Need for type hint

class User:
    def get_posts(self) -> list[Post]:
        pass

# post.py
from user import User  # Need for type hint

class Post:
    def get_author(self) -> User:
        pass

# Circular import for type hints
```

### The Solution

```python
# Use TYPE_CHECKING and string annotations

# user.py
from typing import TYPE_CHECKING
if TYPE_CHECKING:
    from post import Post

class User:
    def get_posts(self) -> list['Post']:  # String annotation
        pass

# post.py
from typing import TYPE_CHECKING
if TYPE_CHECKING:
    from user import User

class Post:
    def get_author(self) -> 'User':  # String annotation
        pass
```

---

## RELATED PATTERNS

- **ARCH-01**: Gateway trinity prevents circular imports
- **ARCH-02**: Layer separation enforces unidirectional flow
- **GATE-03**: Cross-interface via gateway only

---

## RELATED ANTI-PATTERNS

- **AP-01**: Direct core imports
- **AP-05**: Subdirectories (can hide circular imports)

---

## RELATED BUGS

- **BUG-03**: Circular import causing ModuleNotFoundError

---

## IMPACT

### Before Correction

- Import failures
- AttributeError at runtime
- Unpredictable initialization
- Hard to test
- Complex dependency graph

### After Correction

- Clean imports
- Predictable initialization
- Easy to test
- Clear dependencies
- Maintainable structure

---

## DETECTION

### Manual Review Checklist

- [ ] No core module imports another core module
- [ ] No interface imports gateway
- [ ] All imports flow one direction
- [ ] Type hints use string annotations
- [ ] No circular import warnings

### Automated Detection

```bash
# Python's import system warnings
python -W error::ImportWarning your_module.py

# Use tools like:
pip install pylint
pylint --enable=cyclic-import your_module.py

# Or:
pip install import-order
import-order check .
```

---

## VERSIONING

**v1.0.0**: Initial anti-pattern documentation
- Identified circular import pattern
- Documented correction strategies
- Added detection methods

---

## CHANGELOG

### 2025-11-06
- Created anti-pattern document
- Added examples and corrections
- Provided prevention guidelines

---

**Related Documents:**
- ARCH-01-Gateway-Trinity.md
- ARCH-02-Layer-Separation.md
- GATE-03-Cross-Interface-Communication-Rule.md
- BUG-03.md

**Keywords:** circular imports, import cycle, dependency cycle, AttributeError, ImportError, layer separation

**Category:** Anti-Pattern  
**Severity:** Critical  
**Detection:** Static analysis, runtime detection  
**Correction:** Gateway pattern, lazy imports, dependency injection

# DD2-AP-01-Upward-Dependencies.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Anti-pattern - Lower layers depending on higher layers  
**Type:** Anti-Pattern

---

## ANTI-PATTERN: Lower Layer Importing From Higher Layer

**Severity:** High  
**Frequency:** Common in undisciplined codebases  
**Impact:** Circular imports, testing difficulties, architectural decay

---

## DESCRIPTION

Lower layers (Core) import from higher layers (Interface, Gateway), violating the unidirectional dependency flow rule.

**Characteristic Code:**
```python
# cache_core.py (Core Layer - should be at bottom)
import interface_logging  # âŒ ANTI-PATTERN: Upward dependency!

def get_impl(key):
    interface_logging.log_info(f"Getting {key}")
    return cache[key]
```

---

## WHY THIS IS BAD

### 1. Violates Architecture

**SUGA Pattern:**
```
Gateway Layer (highest)
    â†" SHOULD depend on
Interface Layer
    â†" SHOULD depend on
Core Layer (lowest)
```

**Upward dependency breaks this:**
```
Gateway Layer
    â†"
Interface Layer
    â†"  âŒ BACKWARDS!
Core Layer
```

### 2. Creates Circular Dependencies

**Example:**
```python
# cache_core.py
import interface_logging  # Core â†' Interface

# interface_logging.py
import logging_core  # Interface â†' Core

# logging_core.py
import interface_cache  # Core â†' Interface

# interface_cache.py
import cache_core  # Interface â†' Core

# Result: cache_core â†' interface_logging â†' logging_core 
#         â†' interface_cache â†' cache_core
# Python: ModuleNotFoundError!
```

### 3. Makes Testing Impossible

**Core layer should be easiest to test:**
```python
# SHOULD be simple:
def test_cache_core():
    result = cache_core.get_impl("key")
    assert result is not None
```

**With upward dependencies:**
```python
# Now complex:
def test_cache_core():
    # Must mock interface_logging
    mock_logger = Mock()
    with patch('cache_core.interface_logging', mock_logger):
        result = cache_core.get_impl("key")
        assert result is not None
        mock_logger.log_info.assert_called_once()
```

**Problem:** Core should have NO dependencies to mock!

### 4. Prevents Independent Development

**Goal:** Different teams work on different layers independently

**Reality with upward dependencies:**
- Core team blocked by Interface team
- Can't test Core without Interface
- Can't deploy Core without Interface
- Tight coupling prevents parallel work

---

## HOW TO FIX

### Solution 1: Dependency Injection

**Before (Anti-Pattern):**
```python
# cache_core.py
import interface_logging

def get_impl(key):
    interface_logging.log_info(f"Getting {key}")
    return cache[key]
```

**After (Fixed):**
```python
# cache_core.py
def get_impl(key, logger=None):
    if logger:
        logger(f"Getting {key}")
    return cache[key]

# interface_cache.py
def get(key):
    import cache_core
    import logging_core
    logger = lambda msg: logging_core.log_impl(msg)
    return cache_core.get_impl(key, logger=logger)
```

### Solution 2: Move Logic Up

**Before (Anti-Pattern):**
```python
# cache_core.py
import interface_metrics

def get_impl(key):
    interface_metrics.increment("cache_get")
    return cache[key]
```

**After (Fixed):**
```python
# cache_core.py (no upward dependency)
def get_impl(key):
    return cache[key]

# interface_cache.py (metrics at interface level)
def get(key):
    import cache_core
    import metrics_core
    metrics_core.increment_impl("cache_get")
    return cache_core.get_impl(key)
```

### Solution 3: Extract Types

**Before (Anti-Pattern):**
```python
# validation_core.py
from cache_core import CacheMiss  # Core â†' Core but wrong direction

# cache_core.py
from validation_core import ValidationError  # Creates cycle!
```

**After (Fixed):**
```python
# types.py (new file)
class CacheMiss(Exception): pass
class ValidationError(Exception): pass

# validation_core.py
from types import CacheMiss

# cache_core.py
from types import ValidationError
```

---

## DETECTION

### Automated Check

```python
# check_upward_dependencies.py
LAYER_ORDER = {
    'core': 0,
    'interface': 1,
    'gateway': 2
}

def check_file(filepath):
    file_layer = get_layer(filepath)
    for import_line in get_imports(filepath):
        import_layer = get_layer(import_line)
        if import_layer > file_layer:
            raise AntiPatternError(
                f"Upward dependency in {filepath}: "
                f"Layer {file_layer} imports from layer {import_layer}"
            )
```

### Manual Review

**Red flags:**
- Core files importing from `interface_*`
- Core files importing from `gateway*`
- Interface files importing from `gateway*`

---

## REAL EXAMPLES

### Example 1: Logging from Core

**Bad:**
```python
# cache_core.py
import interface_logging

def get_impl(key):
    interface_logging.log_info("Cache get")
    return cache[key]
```

**Good:**
```python
# cache_core.py
def get_impl(key, logger=None):
    if logger:
        logger("Cache get")
    return cache[key]
```

### Example 2: Metrics from Core

**Bad:**
```python
# security_core.py
import interface_metrics

def validate(token):
    interface_metrics.increment("validate_token")
    return check_token(token)
```

**Good:**
```python
# security_core.py
def validate(token, metrics=None):
    if metrics:
        metrics.increment("validate_token")
    return check_token(token)
```

---

## PREVENTION

### Architecture Review

**Before adding import:**
1. Identify layer of current file
2. Identify layer of target import
3. Verify target is lower layer
4. If not, redesign

### CI/CD Check

```yaml
# .github/workflows/architecture.yml
- name: Check Upward Dependencies
  run: |
    python check_upward_dependencies.py
    if [ $? -ne 0 ]; then
      echo "âŒ Upward dependencies detected!"
      exit 1
    fi
```

### Code Review Checklist

- [ ] All Core imports are from other Core or external libraries
- [ ] All Interface imports are from Core or external libraries
- [ ] All Gateway imports are from Interface or external libraries
- [ ] No upward dependencies exist

---

## RELATED

**Decisions:**
- DD2-DEC-01: Higher-Lower Flow
- DD2-DEC-02: No Circular Dependencies

**Lessons:**
- DD2-LESS-02: Layer Violations Compound
- DD2-LESS-01: Dependencies Have Cost

**Other Anti-Patterns:**
- AP-01: Direct Core Import
- AP-03: Circular Module References

---

## COST OF NOT FIXING

**Technical:**
- Circular import errors
- Difficult testing
- Tight coupling
- Architectural decay

**Business:**
- Slower development
- More bugs in production
- Difficult refactoring
- Higher maintenance costs

**Time to Fix:**
- Now: 30 minutes
- Later: 8+ hours (after violations compound)

---

## KEYWORDS

upward-dependencies, layer-violations, architecture-anti-pattern, circular-imports, testing-difficulties, dependency-injection, architecture-rules

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial anti-pattern document
- Solutions documented
- Detection methods defined
- Prevention strategies outlined

---

**END OF FILE**

# AP-04-Skipping-Interface-Layer.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Anti-pattern for bypassing interface layer  
**Category:** Anti-Pattern  
**Severity:** High

---

## ANTI-PATTERN

**Calling core functions directly from gateway without going through interface layer.**

---

## DESCRIPTION

In SUGA architecture, the interface layer is mandatory middleware between gateway and core. Skipping it violates the three-layer pattern and loses critical functionality.

---

## WHY IT'S WRONG

### Architectural Violations

1. **Breaks Three-Layer Pattern**: SUGA requires Gateway → Interface → Core
2. **Loses Orchestration**: Interface layer provides coordination
3. **Misses Validation**: Interface layer validates and transforms
4. **No Error Handling**: Interface layer wraps errors appropriately
5. **Tight Coupling**: Gateway becomes dependent on core implementation details

### Practical Problems

1. **No Logging**: Interface layer typically adds operation logging
2. **No Metrics**: Interface layer tracks performance metrics
3. **No Caching**: Interface layer may add caching logic
4. **No Transformation**: Interface layer adapts data formats
5. **Hard to Maintain**: Changes to core break gateway directly

---

## REAL-WORLD EXAMPLE

### ❌ WRONG: Gateway Calls Core Directly

```python
# gateway_wrappers.py
def cache_get(key):
    """Get value from cache - WRONG IMPLEMENTATION."""
    import cache_core  # ❌ Importing core directly
    return cache_core.get_impl(key)  # ❌ Calling core directly

# Problems:
# - No logging of cache operations
# - No metrics tracking
# - No error transformation
# - Tight coupling to core implementation
# - Hard to add middleware functionality
```

**Impact:**
- No visibility into cache usage
- No performance tracking
- Core errors propagate directly
- Can't add caching policies
- Can't switch core implementations

### ✓ CORRECT: Gateway → Interface → Core

```python
# gateway_wrappers.py
def cache_get(key):
    """Get value from cache - CORRECT IMPLEMENTATION."""
    import interface_cache  # ✓ Importing interface
    return interface_cache.get(key)  # ✓ Calling interface

# interface_cache.py
def get(key):
    """Interface layer for cache get."""
    import logging_core
    import metrics_core
    import cache_core
    
    # Log operation
    logging_core.log_debug(f"Cache get: {key}")
    
    # Track metrics
    start = time.time()
    
    try:
        # Call core
        result = cache_core.get_impl(key)
        
        # Track hit/miss
        if result is not None:
            metrics_core.increment('cache.hits')
        else:
            metrics_core.increment('cache.misses')
        
        return result
        
    except Exception as e:
        # Transform error
        logging_core.log_error(f"Cache error: {e}")
        metrics_core.increment('cache.errors')
        raise CacheError(f"Failed to get {key}") from e
        
    finally:
        # Track duration
        duration = time.time() - start
        metrics_core.track('cache.get.duration', duration)

# cache_core.py
def get_impl(key):
    """Core cache implementation."""
    return cache.get(key)
```

**Benefits:**
- Complete operation visibility
- Performance tracking
- Proper error handling
- Easy to add policies
- Core implementation can change

---

## SEVERITY METRICS

### Impact Levels

**High Severity Issues:**
- Lost logging (no visibility)
- Lost metrics (no monitoring)
- Lost error handling (poor user experience)
- Lost validation (security risk)
- Tight coupling (maintenance nightmare)

**Medium Severity Issues:**
- Lost caching (performance impact)
- Lost transformation (data consistency)
- Lost orchestration (complex workflows broken)

---

## IDENTIFICATION

### Code Smell Indicators

```python
# Suspicious patterns in gateway layer:

# Red flag: Importing *_core modules
import cache_core     # ❌ Should import interface_cache
import logging_core   # ❌ Should import interface_logging
from security_core import encrypt  # ❌ Should import interface_security

# Red flag: Calling *_impl functions
cache_core.get_impl(key)      # ❌ Should call interface
logging_core.log_impl(msg)    # ❌ Should call interface
security_core.encrypt_impl(data)  # ❌ Should call interface
```

### Architecture Review Checklist

- [ ] All gateway imports are interface_* modules (not *_core)
- [ ] No *_impl function calls from gateway
- [ ] Each interface provides middleware functionality
- [ ] Core modules only accessed via interface

---

## CORRECTION

### Step 1: Identify Direct Core Calls

```bash
# Find gateway files calling core directly
grep -r "import.*_core" gateway*.py
grep -r "_impl" gateway*.py
```

### Step 2: Create/Use Interface Layer

```python
# If interface doesn't exist, create it

# interface_cache.py
def get(key):
    """Interface for cache get with middleware."""
    import cache_core
    
    # Add middleware here:
    # - Logging
    # - Metrics
    # - Validation
    # - Transformation
    
    return cache_core.get_impl(key)
```

### Step 3: Update Gateway to Use Interface

```python
# gateway_wrappers.py

# Before
def cache_get(key):
    import cache_core
    return cache_core.get_impl(key)

# After
def cache_get(key):
    import interface_cache
    return interface_cache.get(key)
```

### Step 4: Add Interface Middleware

```python
# interface_cache.py
import time
from typing import Any, Optional

def get(key: str) -> Optional[Any]:
    """Get value from cache with full middleware stack."""
    # Import dependencies
    import logging_core
    import metrics_core
    import cache_core
    
    # Pre-processing
    logging_core.log_debug(f"Cache get: {key}")
    start_time = time.time()
    
    try:
        # Validation
        if not isinstance(key, str):
            raise ValueError("Cache key must be string")
        
        # Core operation
        result = cache_core.get_impl(key)
        
        # Post-processing
        if result is not None:
            metrics_core.increment('cache.hits')
        else:
            metrics_core.increment('cache.misses')
        
        return result
        
    except Exception as e:
        # Error handling
        logging_core.log_error(f"Cache get failed: {key}", error=e)
        metrics_core.increment('cache.errors')
        raise
        
    finally:
        # Cleanup/metrics
        duration = (time.time() - start_time) * 1000
        metrics_core.track('cache.get.duration_ms', duration)
```

---

## INTERFACE LAYER RESPONSIBILITIES

### What Interfaces Should Do

**1. Logging**
```python
def interface_function():
    import logging_core
    logging_core.log_debug("Operation started")
    # ... operation
    logging_core.log_debug("Operation completed")
```

**2. Metrics**
```python
def interface_function():
    import metrics_core
    start = time.time()
    try:
        result = core_function()
        metrics_core.increment('operation.success')
        return result
    finally:
        metrics_core.track('operation.duration', time.time() - start)
```

**3. Validation**
```python
def interface_function(data):
    if not validate(data):
        raise ValueError("Invalid input")
    return core_function(data)
```

**4. Error Transformation**
```python
def interface_function():
    try:
        return core_function()
    except CoreSpecificError as e:
        raise InterfaceError("Operation failed") from e
```

**5. Data Transformation**
```python
def interface_function(external_format):
    internal_format = transform_to_internal(external_format)
    result = core_function(internal_format)
    return transform_to_external(result)
```

---

## WHEN INTERFACE CAN BE THIN

### Acceptable Minimal Interface

If truly no middleware needed:
```python
# interface_simple.py
def operation(args):
    """Thin interface with no middleware needed.
    
    Note: Interface exists for architectural consistency.
    No middleware currently required.
    """
    import simple_core
    return simple_core.operation_impl(args)
```

**Even thin interfaces provide:**
- Architectural consistency
- Future extensibility
- Clear dependency structure
- Easy to add middleware later

---

## RELATED PATTERNS

- **ARCH-01**: Gateway trinity requires all three layers
- **ARCH-02**: Layer separation defines responsibilities
- **DEC-02**: Three-layer pattern is mandatory

---

## RELATED ANTI-PATTERNS

- **AP-01**: Direct core imports (similar violation)
- **AP-03**: Circular imports (can result from skipping interface)

---

## IMPACT

### Before Correction

- No logging/metrics
- Poor error handling
- Tight coupling
- Hard to maintain
- No middleware
- Limited visibility

### After Correction

- Complete logging/metrics
- Proper error handling
- Loose coupling
- Easy to maintain
- Flexible middleware
- Full visibility

---

## DETECTION

### Automated Detection

```python
# check_interface_usage.py
import ast
import os

def check_gateway_imports(gateway_file):
    """Check if gateway imports core directly."""
    with open(gateway_file) as f:
        tree = ast.parse(f.read())
    
    errors = []
    for node in ast.walk(tree):
        if isinstance(node, ast.Import):
            for alias in node.names:
                if alias.name.endswith('_core'):
                    errors.append(f"Direct core import: {alias.name}")
        elif isinstance(node, ast.ImportFrom):
            if node.module and node.module.endswith('_core'):
                errors.append(f"Direct core import: {node.module}")
    
    return errors

# Usage
for file in os.listdir():
    if file.startswith('gateway'):
        errors = check_gateway_imports(file)
        if errors:
            print(f"{file}: {errors}")
```

---

## VERSIONING

**v1.0.0**: Initial anti-pattern documentation
- Identified interface-skipping pattern
- Documented correction steps
- Added detection methods

---

## CHANGELOG

### 2025-11-06
- Created anti-pattern document
- Added examples and corrections
- Provided interface responsibilities guide

---

**Related Documents:**
- ARCH-01-Gateway-Trinity.md
- ARCH-02-Layer-Separation.md
- DEC-02-Three-Layer-Pattern.md
- AP-01-Direct-Core-Import.md

**Keywords:** interface layer, three-layer pattern, middleware, gateway, core, layer skipping

**Category:** Anti-Pattern  
**Severity:** High  
**Detection:** Code review, automated scanning  
**Correction:** Always use interface layer

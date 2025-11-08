# LESS-04-Layer-Responsibility-Clarity.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Lesson on maintaining clear layer responsibilities  
**Category:** Core Architecture Lesson  
**Type:** Design Principle

---

## LESSON

**Each SUGA layer has specific responsibilities. Mixing responsibilities across layers creates confusion and maintenance problems.**

---

## CONTEXT

SUGA architecture defines three layers with distinct purposes. When responsibilities blur between layers, the architecture benefits disappear.

---

## THE THREE RESPONSIBILITIES

### Gateway Layer: Public API

**Single Responsibility:** Provide public interface

**What Gateway Does:**
- Expose functions to external callers
- Lazy import interface layer
- Route requests to appropriate interface
- Nothing more

**What Gateway Does NOT Do:**
- Business logic (belongs in core)
- Validation (belongs in interface)
- Error transformation (belongs in interface)
- Direct core access (must go through interface)

```python
# ✓ CORRECT Gateway
def cache_get(key):
    """Get value from cache - gateway function."""
    import interface_cache
    return interface_cache.get(key)

# ❌ WRONG Gateway
def cache_get(key):
    """Get value from cache - TOO MUCH in gateway."""
    import logging_core
    import metrics_core
    import cache_core
    
    # ❌ Validation in gateway (should be interface)
    if not key:
        raise ValueError("Key required")
    
    # ❌ Logging in gateway (should be interface)
    logging_core.log_info(f"Cache get: {key}")
    
    # ❌ Direct core access (should go through interface)
    result = cache_core.get_impl(key)
    
    # ❌ Metrics in gateway (should be interface)
    metrics_core.increment('cache.gets')
    
    return result
```

### Interface Layer: Orchestration

**Single Responsibility:** Coordinate and add middleware

**What Interface Does:**
- Validation and sanitization
- Logging and metrics
- Error transformation
- Data transformation
- Orchestration between multiple cores
- Lazy import core layer

**What Interface Does NOT Do:**
- Business logic implementation (belongs in core)
- Public API exposure (belongs in gateway)
- Direct external access (goes through gateway)

```python
# ✓ CORRECT Interface
def get(key):
    """Interface for cache get with middleware."""
    import logging_core
    import metrics_core
    import cache_core
    
    # ✓ Validation
    if not isinstance(key, str):
        raise ValueError("Key must be string")
    
    # ✓ Logging
    logging_core.log_debug(f"Cache get: {key}")
    
    # ✓ Core operation
    result = cache_core.get_impl(key)
    
    # ✓ Metrics
    if result is not None:
        metrics_core.increment('cache.hits')
    else:
        metrics_core.increment('cache.misses')
    
    return result

# ❌ WRONG Interface
def get(key):
    """Interface doing core work."""
    # ❌ Implementation details in interface (should be core)
    cache_store = {}
    if key in cache_store:
        return cache_store[key]
    return None
```

### Core Layer: Implementation

**Single Responsibility:** Implement business logic

**What Core Does:**
- Business logic implementation
- Data storage/retrieval
- Algorithm execution
- State management
- Pure implementation

**What Core Does NOT Do:**
- Logging (belongs in interface)
- Metrics (belongs in interface)
- Validation (belongs in interface)
- Import other cores (use interface to coordinate)
- Expose public API (gateway does this)

```python
# ✓ CORRECT Core
def get_impl(key):
    """Core cache implementation."""
    # Pure implementation only
    return cache_store.get(key)

# ❌ WRONG Core
def get_impl(key):
    """Core with middleware."""
    import logging_core  # ❌ Logging in core
    logging_core.log_info(f"Getting {key}")
    
    if not key:  # ❌ Validation in core
        raise ValueError("Key required")
    
    result = cache_store.get(key)
    
    import metrics_core  # ❌ Metrics in core
    metrics_core.increment('cache.gets')
    
    return result
```

---

## CLARITY MATRIX

| Responsibility | Gateway | Interface | Core |
|----------------|---------|-----------|------|
| Public API | ✓ | ❌ | ❌ |
| Lazy imports | ✓ | ✓ | ❌ |
| Validation | ❌ | ✓ | ❌ |
| Logging | ❌ | ✓ | ❌ |
| Metrics | ❌ | ✓ | ❌ |
| Error transform | ❌ | ✓ | ❌ |
| Orchestration | ❌ | ✓ | ❌ |
| Implementation | ❌ | ❌ | ✓ |
| Business logic | ❌ | ❌ | ✓ |
| State management | ❌ | ❌ | ✓ |

---

## DISCOVERY

### The Problem

**Initial Implementation (Mixed Responsibilities):**
```python
# gateway_wrappers.py
def process_data(data):
    import data_core
    
    # Validation in gateway ❌
    if not data:
        raise ValueError("Data required")
    
    # Logging in gateway ❌
    print(f"Processing {data}")
    
    # Business logic in gateway ❌
    processed = data.upper().strip()
    
    # Implementation in gateway ❌
    return data_core.store(processed)

# data_core.py
def store(data):
    # More validation in core ❌
    if len(data) > 1000:
        raise ValueError("Too large")
    
    # Logging in core ❌
    print(f"Storing {data}")
    
    # Just store
    storage[data] = data
```

**Problems:**
- Validation in two places
- Logging in wrong layers
- Business logic in gateway
- Hard to test individual layers
- Duplicate code

### The Solution

**Properly Separated:**
```python
# gateway_wrappers.py - PUBLIC API ONLY
def process_data(data):
    """Process data - gateway function."""
    import interface_data
    return interface_data.process(data)

# interface_data.py - MIDDLEWARE ONLY
def process(data):
    """Process data - interface with middleware."""
    import logging_core
    import data_core
    
    # Validation
    if not data:
        raise ValueError("Data required")
    if len(data) > 1000:
        raise ValueError("Too large")
    
    # Logging
    logging_core.log_info(f"Processing data: {len(data)} chars")
    
    # Business logic
    processed = data_core.process_impl(data)
    
    # Logging result
    logging_core.log_info("Processing complete")
    
    return processed

# data_core.py - IMPLEMENTATION ONLY
def process_impl(data):
    """Process data - core implementation."""
    # Pure business logic
    return data.upper().strip()
```

---

## BENEFITS

### Benefit 1: Testability

```python
# Test core independently
def test_core():
    assert data_core.process_impl("  hello  ") == "HELLO"

# Test interface middleware
def test_interface():
    with mock.patch('data_core.process_impl'):
        interface_data.process("data")
        # Verify logging called
        # Verify validation ran

# Test gateway routing
def test_gateway():
    with mock.patch('interface_data.process'):
        gateway.process_data("data")
        # Verify interface called
```

### Benefit 2: Maintainability

```python
# Add logging? Change interface only
def process(data):
    logging_core.log_debug(f"Input: {data}")  # Add here
    result = data_core.process_impl(data)
    logging_core.log_debug(f"Output: {result}")  # Add here
    return result

# Change business logic? Change core only
def process_impl(data):
    return data.lower()  # Changed logic
```

### Benefit 3: Reusability

```python
# Core is pure, reusable
data_core.process_impl()  # Use anywhere

# Interface provides consistency
interface_data.process()  # Always has logging/validation

# Gateway provides public API
gateway.process_data()  # External callers use this
```

---

## COMMON VIOLATIONS

### Violation 1: Business Logic in Gateway

```python
# ❌ WRONG
def gateway_function(data):
    import interface_layer
    # Business logic in gateway
    if data.startswith('admin'):
        return interface_layer.admin_process(data)
    else:
        return interface_layer.user_process(data)

# ✓ CORRECT
def gateway_function(data):
    import interface_layer
    # Gateway just routes
    return interface_layer.process(data)

def interface_process(data):
    import core_layer
    # Business logic in core
    return core_layer.process_impl(data)

def core_process_impl(data):
    # Implementation decides routing
    if data.startswith('admin'):
        return process_admin(data)
    return process_user(data)
```

### Violation 2: Logging in Core

```python
# ❌ WRONG
def core_function(data):
    print(f"Processing {data}")  # Logging in core
    return process(data)

# ✓ CORRECT
def interface_function(data):
    import logging_core
    import data_core
    logging_core.log_info(f"Processing {data}")  # Interface logs
    return data_core.process_impl(data)

def core_process_impl(data):
    return process(data)  # Pure implementation
```

### Violation 3: Validation in Gateway

```python
# ❌ WRONG
def gateway_function(data):
    if not data:  # Validation in gateway
        raise ValueError("Required")
    import interface_layer
    return interface_layer.process(data)

# ✓ CORRECT
def gateway_function(data):
    import interface_layer
    return interface_layer.process(data)  # No validation

def interface_process(data):
    if not data:  # Validation in interface
        raise ValueError("Required")
    import core_layer
    return core_layer.process_impl(data)
```

---

## DECISION FRAMEWORK

### Where Does This Go?

**Ask these questions:**

1. **Is it exposing functionality to external callers?**
   - YES → Gateway
   - NO → Continue

2. **Is it validation, logging, metrics, or orchestration?**
   - YES → Interface
   - NO → Continue

3. **Is it business logic or implementation?**
   - YES → Core
   - NO → Rethink your design

---

## RELATED PATTERNS

- **ARCH-01**: Gateway trinity defines layers
- **ARCH-02**: Layer separation establishes boundaries
- **DEC-02**: Three-layer pattern requires clear responsibilities

---

## RELATED LESSONS

- **LESS-01**: Read complete files to understand responsibility
- **LESS-03**: Gateway entry point maintains clean flow

---

## IMPACT

### Before Clarity

- Mixed responsibilities
- Hard to test
- Duplicate code
- Confusing to maintain
- Violations propagate

### After Clarity

- Clear responsibilities
- Easy to test
- No duplication
- Easy to maintain
- Clean architecture

---

## VERSIONING

**v1.0.0**: Initial lesson documentation
- Established layer responsibilities
- Created clarity matrix
- Documented common violations

---

## CHANGELOG

### 2025-11-06
- Created lesson document
- Added responsibility matrix
- Provided decision framework
- Added violation examples

---

**Related Documents:**
- ARCH-01-Gateway-Trinity.md
- ARCH-02-Layer-Separation.md
- DEC-02-Three-Layer-Pattern.md
- LESS-01-Read-Complete-Files.md
- LESS-03-Gateway-Entry-Point.md

**Keywords:** layer responsibilities, separation of concerns, clear boundaries, single responsibility, architecture

**Category:** Core Architecture Lesson  
**Type:** Design Principle  
**Impact:** High  
**Application:** All SUGA layers

# ARCH-03-Interface-Pattern.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** SUGA interface design pattern  
**Category:** Python Architecture - SUGA

---

## CONCEPT

Interfaces in SUGA are abstraction layers that route requests to appropriate core implementations. They provide consistent API regardless of underlying implementation.

---

## INTERFACE STRUCTURE

### File Organization
```
/src/
├── interface_cache.py       # Cache interface
├── interface_logging.py     # Logging interface
├── interface_http.py        # HTTP interface
└── [other interfaces]
```

### Standard Interface File
```python
"""
Interface: [Name]
Purpose: [What this interface does]
Core: [Which core it routes to]
"""

def action_object(param1, param2, **kwargs):
    """
    Brief description.
    
    Routes to: [core_module].action_object_impl
    """
    import [core_module]
    return [core_module].action_object_impl(param1, param2, **kwargs)
```

---

## INTERFACE TYPES

### 1. Simple Routing Interface
**Purpose:** Direct 1:1 mapping to core

**Example:**
```python
# interface_cache.py
def get(key, default=None):
    """Get value from cache."""
    import cache_core
    return cache_core.get_impl(key, default)

def set(key, value, ttl=None):
    """Set value in cache."""
    import cache_core
    return cache_core.set_impl(key, value, ttl)
```

### 2. Multiple Core Interface
**Purpose:** Route to different cores based on operation

**Example:**
```python
# interface_http.py
def http_get(url, **kwargs):
    """HTTP GET request."""
    import http_client_core
    return http_client_core.get_request(url, **kwargs)

def validate_url(url):
    """Validate URL format."""
    import utility_core
    return utility_core.validate_url_impl(url)
```

### 3. Composite Interface
**Purpose:** Coordinate multiple cores

**Example:**
```python
# interface_data_fetch.py
def fetch_with_cache(url, cache_key):
    """Fetch data with caching."""
    import cache_core
    import http_client_core
    
    # Check cache first
    cached = cache_core.get_impl(cache_key)
    if cached:
        return cached
    
    # Fetch and cache
    data = http_client_core.get_request(url)
    cache_core.set_impl(cache_key, data)
    return data
```

---

## NAMING CONVENTIONS

### Interface Files
**Format:** `interface_[category].py`

**Examples:**
- `interface_cache.py`
- `interface_logging.py`
- `interface_http.py`
- `interface_security.py`

### Interface Functions
**Format:** `action_object()` (verb + noun)

**Examples:**
- `get(key)` - get something
- `set(key, value)` - set something
- `validate_input(data)` - validate something
- `transform_data(data)` - transform something

### Core Functions Called
**Format:** `action_object_impl()` (same + `_impl`)

**Examples:**
- `get_impl(key)` - implementation of get
- `set_impl(key, value)` - implementation of set
- `validate_input_impl(data)` - implementation of validate

---

## INTERFACE RESPONSIBILITIES

### DO (Interface Should)
- Route requests to core
- Use lazy imports
- Pass parameters through
- Return core results
- Maintain consistent API
- Handle simple routing logic

### DON'T (Interface Should NOT)
- Implement business logic
- Process data
- Handle complex logic
- Import other interfaces
- Import multiple cores (unless composite)
- Raise custom errors (core does that)

---

## INTERFACE DOCSTRINGS

### Minimal Format
```python
def action(param):
    """
    Brief description.
    
    Routes to: core_module.action_impl
    """
    import core_module
    return core_module.action_impl(param)
```

### With Context
```python
def complex_action(param1, param2):
    """
    Brief description of what this does.
    
    Coordinates multiple cores for [purpose].
    
    Routes to: 
    - core1.action1_impl
    - core2.action2_impl
    """
    import core1
    import core2
    # Coordination logic
```

---

## INTERFACE PATTERN EXAMPLES

### Pattern 1: Direct Pass-Through
```python
def cache_get(key, default=None):
    """Get from cache."""
    import cache_core
    return cache_core.get_impl(key, default)
```

### Pattern 2: Conditional Routing
```python
def get_data(source, key):
    """Get data from source."""
    if source == 'cache':
        import cache_core
        return cache_core.get_impl(key)
    elif source == 'database':
        import database_core
        return database_core.fetch_impl(key)
```

### Pattern 3: Sequential Operations
```python
def validated_cache_set(key, value):
    """Validate then cache."""
    import utility_core
    import cache_core
    
    # Validate first
    if not utility_core.validate_key_impl(key):
        return False
    
    # Then cache
    return cache_core.set_impl(key, value)
```

---

## CREATING NEW INTERFACE

### Step 1: Identify Need
What functionality needs abstraction?

### Step 2: Create Interface File
```bash
touch /src/interface_[name].py
```

### Step 3: Define Functions
```python
"""
Interface: [Name]
Purpose: [Description]
Core: [core_module]
"""

def action(params):
    """Action description."""
    import [core_module]
    return [core_module].action_impl(params)
```

### Step 4: Create Core File
```bash
touch /src/[name]_core.py
```

### Step 5: Implement Core
```python
"""Core implementation for [name]."""

def action_impl(params):
    """Implementation of action."""
    # Business logic here
    return result
```

### Step 6: Add Gateway Wrapper
```python
# gateway_wrappers_[name].py
def [name]_action(params):
    """Gateway to [name] action."""
    import interface_[name]
    return interface_[name].action(params)
```

### Step 7: Export from Gateway
```python
# gateway.py
from gateway_wrappers_[name] import [name]_action
```

---

## VERIFICATION

**Interface Check:**
- [ ] File named `interface_*.py`
- [ ] Functions route to core
- [ ] Lazy imports used
- [ ] No business logic
- [ ] Brief docstrings
- [ ] Consistent naming

**Integration Check:**
- [ ] Core file exists
- [ ] Gateway wrapper exists
- [ ] Exported from gateway.py
- [ ] No circular imports
- [ ] LESS-15 verified

---

**Related:**
- ARCH-01: Gateway trinity
- ARCH-02: Layer separation
- GATE-01: Gateway entry
- Workflow-01: Add feature

**Version History:**
- v1.0.0 (2025-11-06): Initial interface pattern

---

**END OF FILE**

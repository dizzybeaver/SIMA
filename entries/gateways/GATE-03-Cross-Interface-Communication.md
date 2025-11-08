# GATE-03-Cross-Interface-Communication.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** SUGA architecture pattern - cross-interface communication rule  
**Category:** Gateway Pattern  
**Architecture:** SUGA (Gateway Pattern)

---

## Title

Cross-Interface Communication via Gateway

---

## Priority

CRITICAL

---

## Summary

In SUGA architecture, all communication between different interfaces MUST route through the gateway layer. Interfaces never directly import each other. This rule mathematically prevents circular dependencies and maintains architectural boundaries.

---

## Pattern

### The SUGA Problem

**Direct cross-interface imports create circular dependencies:**
```python
# cache_core.py - ❌ WRONG
from logging_core import log_info  # Direct import!

def cache_get(key):
    log_info(f"Getting {key}")  # Cache → Logging
    return _CACHE.get(key)

# logging_core.py - ❌ WRONG
from metrics_core import record_metric  # Direct import!

def log_info(msg):
    record_metric("logs", 1)  # Logging → Metrics
    print(msg)

# metrics_core.py - ❌ WRONG
from cache_core import cache_get  # Direct import!

def record_metric(name, value):
    cached = cache_get(f"metric:{name}")  # Metrics → Cache
    # Circular: Cache → Logging → Metrics → Cache!
```

### The SUGA Solution

**Via gateway prevents all circular dependencies:**
```python
# cache_core.py - âœ… CORRECT
import gateway

def cache_get(key):
    gateway.log_info(f"Getting {key}")  # Via gateway
    return _CACHE.get(key)

# logging_core.py - âœ… CORRECT
import gateway

def log_info(msg):
    gateway.record_metric("logs", 1)  # Via gateway
    print(msg)

# metrics_core.py - âœ… CORRECT
import gateway

def record_metric(name, value):
    cached = gateway.cache_get(f"metric:{name}")  # Via gateway
    # No circular: All via gateway mediator!
```

---

## Implementation

### SUGA Dependency Flow

```
WRONG (Direct):
┌─────────┐
│ Cache   │─────┐
└─────────┘     │
     ↑          ↓
     │     ┌─────────┐
     └─────│Logging  │
           └─────────┘
                ↓
           ┌─────────┐
           │Metrics  │
           └─────────┘
                ↑│
                └──→ CIRCULAR!

CORRECT (Via Gateway):
        ┌──────────┐
        │ Gateway  │
        │(Mediator)│
        └──────────┘
           ↑ ↑ ↑
           │ │ │
  ┌────────┘ │ └────────┐
  │          │          │
┌─────┐ ┌─────┐ ┌─────┐
│Cache│ │ Log │ │ Met │
└─────┘ └─────┘ └─────┘

All via gateway → No circulars!
```

### SUGA Cross-Interface Template

```python
# In any *_core.py file

import gateway  # Only gateway import allowed

def core_function():
    """
    Function needing other interfaces.
    
    Always call via gateway, never direct import.
    """
    # Log operation (LOGGING interface)
    gateway.log_info("Operation started")
    
    # Check cache (CACHE interface)
    cached = gateway.cache_get("key")
    
    # Record metric (METRICS interface)
    gateway.record_metric("operations", 1)
    
    # All via gateway → No circular dependencies
    return result
```

---

## SUGA Rules

### Rule 1: Gateway-Only Cross-Interface Imports

```python
# âœ… CORRECT - All cores import gateway only
# cache_core.py
import gateway
def cache_op():
    gateway.log_info("message")

# logging_core.py
import gateway
def log_op():
    gateway.record_metric("count", 1)

# metrics_core.py
import gateway
def metric_op():
    gateway.cache_get("key")

# No interface imports another → No circulars!
```

```python
# ❌ WRONG - Direct cross-interface import
# cache_core.py
from logging_core import log_info  # Direct!
def cache_op():
    log_info("message")

# Creates dependency: cache → logging
# Can lead to circulars!
```

### Rule 2: Intra-Interface Direct Imports Allowed

```python
# âœ… CORRECT - Within same interface
# cache_core.py
from cache_validation import validate_key
from cache_types import CacheEntry

def cache_set(key, value):
    validate_key(key)  # Same interface - direct OK
    entry = CacheEntry(key, value)
    _CACHE[key] = entry

# Same interface boundary → Direct imports efficient
```

### Rule 3: Interface Routers Pure

```python
# âœ… CORRECT - interface_cache.py
def execute_operation(operation, params):
    # Pure routing, no cross-interface calls
    import cache_core
    return cache_core.execute_impl(operation, params)

# ❌ WRONG - interface_cache.py
def execute_operation(operation, params):
    import gateway
    gateway.log_info("Cache op")  # Cross-interface in router!
    import cache_core
    return cache_core.execute_impl(operation, params)
```

**Cross-interface calls belong in cores, not interface routers.**

### Rule 4: Gateway Never Imports Cores

```python
# âœ… CORRECT - gateway_wrappers.py
def cache_get(key):
    import interface_cache  # Import interface
    return interface_cache.execute_operation('get', {'key': key})

# ❌ WRONG - gateway_wrappers.py
from cache_core import get_impl  # Direct core import!
def cache_get(key):
    return get_impl(key)
```

---

## SUGA Benefits

### Benefit 1: Mathematically Prevents Circulars

**Proof by construction:**
```
Direct imports can create:
A → B → C → A (circular)

Via gateway:
A → Gateway
B → Gateway
C → Gateway
Gateway → A, B, C (one direction)

Acyclic by design → Circulars impossible
```

### Benefit 2: Centralized Control

**Add features system-wide:**
```python
# gateway_core.py
def execute_operation(interface, operation, params):
    # Add to ALL operations:
    
    # 1. Timing
    start = time.time()
    result = _dispatch(interface, operation, params)
    duration = time.time() - start
    
    # 2. Metrics
    _record_metric(f"{interface}.{operation}.duration", duration)
    
    # 3. Logging
    _log_operation(interface, operation, duration)
    
    # 4. Security (if needed)
    _validate_auth(interface, operation)
    
    return result

# One place → All operations enhanced
```

### Benefit 3: Clear Dependencies

**Decision tree for imports:**
```
Question: Can I import X from Y?

1. Same interface? → YES (direct import)
2. Different interface? → NO (use gateway)
3. Is this core? → Only import gateway
4. Is this interface router? → Only import own core
5. Is this gateway? → Only import interfaces

Clear rules → No confusion
```

### Benefit 4: Testing Simplified

**Mock at single point:**
```python
# Test cache without logging
def test_cache_without_logging():
    with mock.patch('gateway.log_info'):  # Mock once
        result = cache_core.cache_set('key', 'value')
        assert result == True

# Without gateway: Mock at every core file
# With gateway: Mock once at gateway
```

---

## Common Pitfalls

### Pitfall 1: "Just This Once" Direct Import

```python
# Developer thinks: "Just this one direct import..."
# cache_core.py
from logging_core import log_info  # ❌

# Sets precedent
# Others see it
# Pattern spreads
# Circulars emerge

# Solution: Zero tolerance - always via gateway
```

### Pitfall 2: Cross-Interface in Interface Router

```python
# ❌ WRONG - interface_cache.py
import gateway

def execute_operation(operation, params):
    gateway.log_info("Cache operation")  # Cross-interface!
    import cache_core
    return cache_core.execute_impl(operation, params)

# âœ… CORRECT - Move to core
# interface_cache.py - Pure router
def execute_operation(operation, params):
    import cache_core
    return cache_core.execute_impl(operation, params)

# cache_core.py - Cross-interface here
import gateway
def execute_impl(operation, params):
    gateway.log_info("Cache operation")
    # Implementation...
```

### Pitfall 3: Intra-Interface via Gateway

```python
# ❌ SUBOPTIMAL - cache_helper.py
import gateway
def helper():
    value = gateway.cache_get('key')  # Same interface!

# âœ… OPTIMAL - cache_helper.py
from cache_core import get_impl
def helper():
    value = get_impl('key')  # Direct within interface

# Gateway for cross-interface
# Direct for intra-interface
```

---

## Performance

### Call Overhead

```
Direct call (if allowed):    10ns
Via gateway (required):      110ns

Overhead: 100ns (0.1 microseconds)

For 1ms operation: 0.01% overhead
For 100ms operation: 0.0001% overhead

Negligible for real operations
```

### Gateway Benefits Outweigh Cost

```
Benefits:
+ 60-70% faster cold start (lazy loading)
+ Mathematical prevention of circular imports
+ Centralized feature additions
+ Simplified testing

Cost:
- 100ns per call (0.0001% - 0.01% overhead)

Trade-off: Worth it
```

---

## Testing

### Test No Direct Cross-Interface Imports

```python
def test_no_direct_imports():
    """Verify cores only import gateway for cross-interface."""
    import ast
    import os
    
    for file in os.listdir('src'):
        if file.endswith('_core.py'):
            with open(f'src/{file}') as f:
                tree = ast.parse(f.read())
            
            for node in ast.walk(tree):
                if isinstance(node, ast.Import):
                    # Check imports
                    for alias in node.names:
                        # Only gateway or intra-interface allowed
                        assert 'gateway' in alias.name or file.replace('_core.py', '') in alias.name

def test_via_gateway_works():
    """Test cross-interface via gateway."""
    import cache_core
    
    # cache_core calls gateway.log_info
    with mock.patch('gateway.log_info') as mock_log:
        cache_core.cache_set('key', 'value')
        mock_log.assert_called()
```

---

## Related

**SUGA Architecture:**
- ARCH-01: Gateway trinity - gateway is mediator
- ARCH-02: Layer separation - cross-interface boundaries
- ARCH-03: Interface pattern - pure routers

**SUGA Gateways:**
- GATE-01: Gateway entry - provides mediation point
- GATE-02: Lazy imports - gateway enables lazy loading

**SUGA Lessons:**
- LESS-07: Base layers no dependencies - logging exception
- LESS-15: Verification checks cross-interface rule

**SUGA Decisions:**
- DEC-02: Three-layer pattern - gateway at top
- DEC-03: Gateway mandatory - this rule

**SUGA Anti-Patterns:**
- AP-01: Direct core import - violates this rule
- AP-03: Circular module references - prevented by this rule

**Generic Patterns:**
- GATE-03: Generic cross-interface rule (parent)

**Keywords:** cross-interface communication, circular dependency prevention, gateway mediator, architectural boundaries, SUGA dependency flow

---

## Version History

- **1.0.0** (2025-11-06): Created SUGA-specific pattern from GATE-03
  - Adapted for SUGA three-layer architecture
  - Focused on circular dependency prevention
  - Included layer-specific communication rules
  - Kept under 400 lines (SIMAv4)

---

**END OF FILE**

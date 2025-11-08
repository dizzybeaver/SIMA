# DD2-03-Flow-Direction.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Understanding and visualizing dependency flow direction in layered architecture  
**Category:** Python Architecture Pattern - Structure

---

## DEPENDENCY FLOW BASICS

Dependency flow determines how information and control move through system layers.

---

## FLOW RULES

### Rule: Dependencies Flow Downward

```
HIGH LEVEL (abstract, stable)
    ↓ depends on
MID LEVEL
    ↓ depends on  
LOW LEVEL (concrete, volatile)
```

**Higher layers:**
- More abstract
- Less stable (change more)
- Depend on lower layers

**Lower layers:**
- More concrete
- More stable (change less)
- Independent of higher layers

---

## SUGA FLOW VISUALIZATION

### Three-Layer Flow

```
┌─────────────────────────────────┐
│ GATEWAY LAYER                   │ ← Public API
│ (gateway.py, gateway_wrappers.py)│
└───────────────┬─────────────────┘
                │ imports
                │ calls
                ↓
┌─────────────────────────────────┐
│ INTERFACE LAYER                 │ ← Routing
│ (interface_*.py)                │
└───────────────┬─────────────────┘
                │ imports
                │ calls
                ↓
┌─────────────────────────────────┐
│ CORE LAYER                      │ ← Implementation
│ (*_core.py)                     │
└─────────────────────────────────┘
```

**Flow:** Gateway → Interface → Core  
**Never:** Core → Interface or Interface → Gateway

### Example: Cache Operation Flow

```python
# 1. Gateway Layer (entry point)
# gateway_wrappers.py
def cache_get(key: str):
    """Public API - users call this."""
    import interface_cache  # ↓ Flow down
    return interface_cache.get(key)

# 2. Interface Layer (routing)
# interface_cache.py
def get(key: str):
    """Routes to implementation."""
    import cache_core  # ↓ Flow down
    return cache_core.get_impl(key)

# 3. Core Layer (implementation)
# cache_core.py
def get_impl(key: str):
    """Actual implementation."""
    return _cache.get(key)  # No upward imports
```

**Flow sequence:** User → Gateway → Interface → Core → Result

---

## ALLOWED vs FORBIDDEN FLOWS

### Allowed: Downward Dependencies

```
✓ Gateway → Interface
✓ Interface → Core  
✓ Gateway → Interface → Core (chain)
✓ Same-layer lateral (with caution)
```

### Forbidden: Upward Dependencies

```
✗ Core → Interface
✗ Core → Gateway
✗ Interface → Gateway
✗ Any upward flow
```

### Why Upward Forbidden?

**Problem:** Creates coupling and circular dependencies

```python
# ✗ BAD EXAMPLE
# cache_core.py (Core)
import interface_logging  # Upward!

def get_impl(key):
    interface_logging.log(f"Get {key}")  # Core depends on Interface
    return _cache.get(key)
```

**Issues:**
- Core couples to Interface layer
- Can't use Core without Interface
- Circular import risk
- Testing harder (must mock Interface)

---

## CORRECT FLOW PATTERNS

### Pattern 1: Callback Injection

**Problem:** Core needs to notify higher layer

**Wrong (upward dependency):**
```python
# cache_core.py
import interface_logging  # ✗ Upward

def get_impl(key):
    interface_logging.log("Access")  # Bad
    return _cache.get(key)
```

**Right (callback injection):**
```python
# cache_core.py
def get_impl(key, on_access=None):
    if on_access:
        on_access(key)  # Callback, no dependency
    return _cache.get(key)

# interface_cache.py
import cache_core
import interface_logging

def get(key):
    return cache_core.get_impl(
        key,
        on_access=lambda k: interface_logging.log(f"Access {k}")
    )
```

**Flow:** Still downward (Interface → Core), but Core calls injected callback

### Pattern 2: Event System

**Problem:** Lower layer needs to notify multiple higher layers

**Wrong (multiple upward dependencies):**
```python
# order_core.py
import notification_service  # ✗
import analytics_service  # ✗

def create_order(order):
    order_id = save(order)
    notification_service.send_email(order_id)  # Bad
    analytics_service.track_event(order_id)  # Bad
    return order_id
```

**Right (event-driven):**
```python
# order_core.py
_event_handlers = {}

def on_event(event_name, handler):
    """Register event handler (no import needed)."""
    if event_name not in _event_handlers:
        _event_handlers[event_name] = []
    _event_handlers[event_name].append(handler)

def emit_event(event_name, data):
    """Emit event to registered handlers."""
    for handler in _event_handlers.get(event_name, []):
        handler(data)

def create_order(order):
    order_id = save(order)
    emit_event("order_created", order_id)  # No dependency
    return order_id

# Higher layers register
import order_core
order_core.on_event("order_created", notification_service.send_email)
order_core.on_event("order_created", analytics_service.track_event)
```

**Flow:** Still downward (services register with core), core just emits events

### Pattern 3: Return Values

**Problem:** Core needs to provide data to higher layer

**Wrong (store reference):**
```python
# cache_core.py
import interface_metrics  # ✗

_metrics = interface_metrics  # Store reference

def get_impl(key):
    _metrics.increment("cache_hits")  # Bad
    return _cache.get(key)
```

**Right (return data):**
```python
# cache_core.py
def get_impl(key):
    value = _cache.get(key)
    return {
        "value": value,
        "hit": value is not None,
        "key": key
    }  # Return data, no dependency

# interface_cache.py
import cache_core
import interface_metrics

def get(key):
    result = cache_core.get_impl(key)
    if result["hit"]:
        interface_metrics.increment("cache_hits")  # Interface handles it
    return result["value"]
```

**Flow:** Downward (Interface calls Core), Core returns data, Interface acts on it

---

## CROSS-LAYER COMMUNICATION

### Vertical Communication (Normal)

```
Gateway
   ↓ direct call
Interface
   ↓ direct call
Core
```

**Implementation:** Direct imports and calls (downward only)

### Horizontal Communication (Same Layer)

```
Interface A ←→ Interface B (same layer)
```

**Rule:** Allowed but minimize

**Implementation:**
```python
# interface_cache.py
import interface_logging  # Same layer OK

def get(key):
    interface_logging.log(f"Cache access: {key}")
    return cache_core.get_impl(key)
```

**Warning:** Too much horizontal coupling suggests wrong layering

### Cross-Stack Communication (Anti-Pattern)

```
Gateway A
   ↓
Interface A ─┐
   ↓         │ ✗ Don't do this
Core A       │
             │
Gateway B    │
   ↓         │
Interface B ←┘
```

**Wrong:** Interface A → Interface B (crosses stacks)

**Right:** Interface A → Gateway → Interface B (via public API)

---

## FLOW IN DIFFERENT ARCHITECTURES

### Clean Architecture Flow

```
        UI/Controllers (outer)
              ↓
        Use Cases (application)
              ↓
        Entities (domain)
        
Dependencies point inward
```

### Hexagonal Architecture Flow

```
    HTTP Adapter (port)
          ↓
    Application Service
          ↓
    Domain Core
          ↓
    Database Adapter (port)
    
Core in center, adapters at edges
```

### Microservices Flow

```
Service A → API Gateway → Service B
          ↓
    Shared Libraries
    
Services depend on shared libs, not each other
```

---

## VISUALIZING PROJECT FLOW

### Tool: Dependency Graph

```python
import matplotlib.pyplot as plt
import networkx as nx

def create_dependency_graph(dependencies):
    """
    Visualize dependency flow.
    
    dependencies: dict mapping module to list of imports
    """
    G = nx.DiGraph()
    
    for module, imports in dependencies.items():
        for imported in imports:
            G.add_edge(module, imported)
    
    # Color by layer
    colors = []
    for node in G.nodes():
        if "gateway" in node:
            colors.append("lightblue")
        elif "interface" in node:
            colors.append("lightgreen")
        elif "core" in node:
            colors.append("lightcoral")
        else:
            colors.append("lightgray")
    
    # Draw
    pos = nx.spring_layout(G)
    nx.draw(G, pos, node_color=colors, with_labels=True, 
            arrows=True, arrowsize=20)
    plt.show()
```

**Output:** Visual graph showing all dependency arrows

**Validation:** All arrows should point downward (toward lower layers)

---

## FLOW ANTI-PATTERNS

### Anti-Pattern 1: Yo-Yo Problem

```
Gateway → Interface → Core → Interface → Core
                      ↑__________________|
```

**Issue:** Core calls back to Interface

**Fix:** Redesign to avoid upward calls

### Anti-Pattern 2: Skip Layer

```
Gateway → Core
    (skips Interface)
```

**Issue:** Breaks abstraction

**Fix:** Always go through Interface layer

### Anti-Pattern 3: Bidirectional Flow

```
Interface A ←→ Interface B
```

**Issue:** Circular dependency risk

**Fix:** Extract shared logic to lower layer

---

## TESTING FLOW

### Layer Testing Strategy

```python
# Test Core independently (no dependencies)
def test_cache_core():
    result = cache_core.get_impl("key")
    assert result is not None

# Test Interface with mocked Core
def test_cache_interface(mocker):
    mocker.patch("cache_core.get_impl", return_value="value")
    result = interface_cache.get("key")
    assert result == "value"

# Test Gateway with mocked Interface
def test_cache_gateway(mocker):
    mocker.patch("interface_cache.get", return_value="value")
    result = gateway.cache_get("key")
    assert result == "value"
```

**Flow:** Test from bottom up, mock dependencies

---

## KEYWORDS

dependency flow, flow direction, downward dependencies, upward forbidden, layer communication, vertical flow, horizontal flow, dependency graph, flow visualization

---

## RELATED TOPICS

- DD2-01: Core Concept
- DD2-02: Layer Rules
- DD2-DEC-01: Higher-Lower Flow
- SUGA: Three-layer implementation of DD-2

---

**END OF FILE**

**Version:** 1.0.0  
**Lines:** 397 (within 400 limit)  
**Category:** Python Architecture Pattern  
**Status:** Complete

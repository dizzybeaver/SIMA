# DD2-02-Layer-Rules.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Specific rules for managing dependencies between software layers  
**Category:** Python Architecture Pattern - Structure

---

## LAYER DEPENDENCY RULES

Clear rules prevent circular dependencies and maintain clean architecture.

---

## FUNDAMENTAL RULES

### Rule 1: Higher → Lower Only

**Definition:** Higher layers may depend on lower layers, never reverse

**Layer hierarchy (example):**
```
Layer 1: Presentation (highest)
Layer 2: Business Logic
Layer 3: Data Access (lowest)
```

**Allowed:**
```python
# presentation.py (Layer 1)
from business_logic import process_request  # ✓ Higher → Lower

# business_logic.py (Layer 2)
from data_access import fetch_data  # ✓ Higher → Lower
```

**Forbidden:**
```python
# data_access.py (Layer 3)
from business_logic import validate  # ✗ Lower → Higher
```

---

### Rule 2: No Circular References

**Definition:** Module A imports B, B must not import A (directly or transitively)

**Direct cycle (forbidden):**
```python
# module_a.py
from module_b import function_b  # A → B

# module_b.py
from module_a import function_a  # B → A (CYCLE!)
```

**Transitive cycle (forbidden):**
```python
# module_a.py
from module_b import function_b  # A → B

# module_b.py
from module_c import function_c  # B → C

# module_c.py
from module_a import function_a  # C → A (CYCLE via B!)
```

**Both forbidden**

---

### Rule 3: Same-Layer Lateral OK (With Caution)

**Definition:** Modules in same layer may depend on each other

**Allowed:**
```python
# order_service.py (Layer 2)
from payment_service import process_payment  # Same layer

def create_order(order_data):
    process_payment(order_data)
    return save_order(order_data)
```

**Warning:** Too many lateral dependencies suggest incorrect layering

**Red flag indicators:**
- Module A depends on 5+ same-layer modules
- Bidirectional dependencies (A imports B, B imports A)
- Complex lateral dependency graph

**Solution:** Extract shared logic to lower layer

---

### Rule 4: Minimize Dependencies

**Definition:** Depend on as few modules as possible

**Bad (too many dependencies):**
```python
# user_service.py
from order_service import fetch_orders
from payment_service import fetch_payments
from shipping_service import fetch_shipments
from notification_service import send_email
from analytics_service import track_event
# 5+ dependencies
```

**Good (focused dependencies):**
```python
# user_service.py
from user_repository import fetch_user_data  # 1 dependency

def get_user_details(user_id):
    # Single focused dependency
    return fetch_user_data(user_id)
```

**Guideline:** < 3 dependencies per module is ideal

---

## SUGA PATTERN RULES

### SUGA Three-Layer Pattern

```
Gateway Layer (public API)
    ↓ depends on
Interface Layer (routing)
    ↓ depends on
Core Layer (implementation)
```

**Rule:** Each layer depends only on layer directly below

### Gateway Layer Rules

**Allowed:**
```python
# gateway_wrappers.py
import interface_cache  # Next layer down

def cache_get(key):
    return interface_cache.get(key)  # ✓ Gateway → Interface
```

**Forbidden:**
```python
# gateway_wrappers.py
import cache_core  # Skip layer!

def cache_get(key):
    return cache_core.get_impl(key)  # ✗ Gateway → Core directly
```

**Why:** Breaks abstraction, couples to implementation

### Interface Layer Rules

**Allowed:**
```python
# interface_cache.py
import cache_core  # Next layer down

def get(key):
    return cache_core.get_impl(key)  # ✓ Interface → Core
```

**Forbidden:**
```python
# interface_cache.py
import gateway_wrappers  # Higher layer!

def get(key):
    gateway_wrappers.log_access(key)  # ✗ Interface → Gateway
    return cache_core.get_impl(key)
```

**Why:** Creates upward dependency

### Core Layer Rules

**Allowed:**
```python
# cache_core.py
# No imports from project (only stdlib/packages)

def get_impl(key):
    return _cache.get(key)  # ✓ No project dependencies
```

**Forbidden:**
```python
# cache_core.py
import interface_logging  # Higher layer!

def get_impl(key):
    interface_logging.log(f"Get {key}")  # ✗ Core → Interface
    return _cache.get(key)
```

**Why:** Core must be independent, reusable

---

## CROSS-INTERFACE RULES

### Rule: Cross-Interface via Gateway Only

**Scenario:** Cache interface needs logging

**Forbidden (direct cross-interface):**
```python
# interface_cache.py
import interface_logging  # Direct cross-interface

def get(key):
    interface_logging.log("Cache access")  # ✗ Cross-interface
    return cache_core.get_impl(key)
```

**Allowed (via gateway):**
```python
# interface_cache.py
def get(key):
    import gateway  # Via gateway
    gateway.log_info("Cache access")  # ✓ Through gateway
    return cache_core.get_impl(key)
```

**Why:** Gateway is the only public API, prevents lateral coupling

---

## DEPENDENCY INVERSION PRINCIPLE

### Rule: Depend on Abstractions

**Bad (concrete dependency):**
```python
# business_logic.py
from postgres_repository import PostgresUserRepo

def get_user(user_id):
    repo = PostgresUserRepo()  # Coupled to Postgres
    return repo.find(user_id)
```

**Good (abstract dependency):**
```python
# business_logic.py
from repositories import UserRepository  # Abstract interface

def get_user(user_id, repository: UserRepository):
    return repository.find(user_id)  # Depends on abstraction
```

**Benefits:**
- Easy to swap implementations
- Easy to test (mock interface)
- Reduced coupling

---

## BREAKING DEPENDENCY CYCLES

### Pattern 1: Extract to Lower Layer

**Before (cycle):**
```python
# module_a.py
from module_b import function_b

# module_b.py
from module_a import function_a  # Cycle!
```

**After (shared lower layer):**
```python
# shared_utils.py (new lower layer)
def shared_function():
    pass

# module_a.py
from shared_utils import shared_function

# module_b.py
from shared_utils import shared_function
```

### Pattern 2: Dependency Injection

**Before (cycle):**
```python
# service.py
from repository import fetch_data

def process():
    data = fetch_data()
    return transform(data)

# repository.py
from service import validate  # Cycle!

def fetch_data():
    data = query()
    if not validate(data):
        return None
    return data
```

**After (injection):**
```python
# service.py
from repository import fetch_data

def process():
    data = fetch_data()
    return transform(data)

# repository.py
def fetch_data(validator=None):  # Inject dependency
    data = query()
    if validator and not validator(data):
        return None
    return data

# usage
fetch_data(validator=service.validate)
```

### Pattern 3: Event-Based Decoupling

**Before (cycle):**
```python
# order.py
from notification import send_email

def create_order():
    order = save_order()
    send_email(order)  # Direct dependency

# notification.py  
from order import get_order_details  # Cycle!

def send_email(order_id):
    details = get_order_details(order_id)
    send(details)
```

**After (events):**
```python
# order.py
import events

def create_order():
    order = save_order()
    events.emit("order_created", order)  # No direct dependency

# notification.py
import events

@events.on("order_created")
def send_email(order):
    send(order)  # Listens for event
```

---

## VALIDATION CHECKLIST

### Pre-Commit Checks

```
[ ] No circular imports (run cycle detector)
[ ] All imports go higher → lower
[ ] No core layer importing interfaces
[ ] No interface layer importing gateway
[ ] Cross-interface via gateway only
[ ] < 3 dependencies per module (average)
[ ] No god objects (10+ dependencies)
```

### CI/CD Checks

```python
# .github/workflows/validate_deps.yml
- name: Validate Dependencies
  run: |
    python scripts/detect_cycles.py
    python scripts/validate_layers.py
    python scripts/count_dependencies.py
```

**Fail build if violations found**

---

## REFACTORING STRATEGIES

### Strategy 1: Layer Extraction

**When:** Module has too many dependencies

**How:**
1. Identify common dependencies
2. Extract to new lower layer
3. Have both modules depend on extracted layer

### Strategy 2: Interface Extraction

**When:** Concrete coupling exists

**How:**
1. Define abstract interface
2. Implement interface in concrete class
3. Depend on interface, not implementation

### Strategy 3: Lazy Loading

**When:** Circular dependency unavoidable

**How:**
```python
def function():
    import module_b  # Import inside function
    return module_b.function()
```

**Note:** Lazy loading treats symptom, not cause. Prefer layer extraction.

---

## METRICS TO TRACK

### Dependency Metrics

```python
# Count incoming dependencies per module
def incoming_deps(module, graph):
    return len([m for m in graph if module in graph[m]])

# Count outgoing dependencies per module  
def outgoing_deps(module, graph):
    return len(graph[module])

# Calculate coupling
def coupling_score(module, graph):
    return incoming_deps(module, graph) + outgoing_deps(module, graph)
```

**Targets:**
- Incoming: < 5 per module
- Outgoing: < 3 per module
- Coupling: < 8 per module

---

## KEYWORDS

layer rules, dependency rules, higher-lower flow, no circular dependencies, SUGA layers, cross-interface rules, dependency inversion, breaking cycles

---

## RELATED TOPICS

- DD2-01: Core Concept
- DD2-03: Flow Direction
- DD2-DEC-01: Higher-Lower Flow Decision
- DD2-DEC-02: No Circular Dependencies
- SUGA: Three-layer implementation

---

**END OF FILE**

**Version:** 1.0.0  
**Lines:** 399 (within 400 limit)  
**Category:** Python Architecture Pattern  
**Status:** Complete

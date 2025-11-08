# DD2-01-Core-Concept.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Dependency Disciplines core concept - managing layer dependencies  
**Category:** Python Architecture Pattern - Structure

---

## WHAT IS DEPENDENCY DISCIPLINES?

**Dependency Disciplines (DD-2)** is an architecture pattern that establishes rules for managing dependencies between software layers/modules to prevent circular imports and maintain clean architecture.

**Type:** Architecture Pattern  
**Domain:** Dependency Management  
**Core Rule:** Higher layers depend on lower layers only

---

## CORE PRINCIPLE

### Unidirectional Flow

Dependencies flow in ONE direction:

```
┌─────────────────┐
│ Presentation    │  (Highest layer)
│ Layer           │
└────────┬────────┘
         │ depends on
         ↓
┌─────────────────┐
│ Business Logic  │  (Middle layer)
│ Layer           │
└────────┬────────┘
         │ depends on
         ↓
┌─────────────────┐
│ Data Access     │  (Lowest layer)
│ Layer           │
└─────────────────┘
```

**Rule:** Higher â†' Lower dependencies only  
**Never:** Lower â†' Higher dependencies

---

## WHY IT MATTERS

### Problem: Circular Dependencies

**Without discipline:**

```python
# user_service.py
from order_service import get_user_orders  # Imports order_service

def get_user_details(user_id):
    orders = get_user_orders(user_id)
    return {"user": user_id, "orders": orders}

# order_service.py
from user_service import validate_user  # Imports user_service

def get_user_orders(user_id):
    if not validate_user(user_id):
        raise ValueError("Invalid user")
    return fetch_orders(user_id)
```

**Result:** Circular import error at runtime  
**Python error:** `ImportError: cannot import name 'X' from partially initialized module`

### Solution: Dependency Disciplines

**With discipline:**

```python
# Layer 3: Data Access (lowest - no dependencies)
def fetch_user(user_id):
    return {"id": user_id, "name": "John"}

def fetch_orders(user_id):
    return [{"id": 1}, {"id": 2}]

# Layer 2: Business Logic (depends on Layer 3 only)
def validate_user(user_id):
    user = fetch_user(user_id)
    return user is not None

def get_user_orders(user_id):
    if not validate_user(user_id):
        raise ValueError("Invalid user")
    return fetch_orders(user_id)

# Layer 1: Service (depends on Layer 2 only)
def get_user_details(user_id):
    user = fetch_user(user_id)  # Layer 3
    orders = get_user_orders(user_id)  # Layer 2
    return {"user": user, "orders": orders}
```

**Result:** No circular imports, clean dependency flow

---

## CORE RULES

### Rule 1: Higher Depends on Lower

**✓ Allowed:**
```python
# business_logic.py (Layer 2)
from data_access import fetch_data  # Lower layer

def process_data():
    data = fetch_data()  # OK - higher depends on lower
    return transform(data)
```

**✗ Forbidden:**
```python
# data_access.py (Layer 3)
from business_logic import validate  # Higher layer

def fetch_data():
    data = query_database()
    if not validate(data):  # BAD - lower depends on higher
        return None
    return data
```

### Rule 2: No Circular Dependencies

**✗ Forbidden:**
```python
# module_a.py
from module_b import function_b

# module_b.py
from module_a import function_a  # Creates cycle
```

**✓ Solution:** Extract shared dependency to lower layer

```python
# shared_utils.py (Layer 3)
def shared_function():
    pass

# module_a.py (Layer 2)
from shared_utils import shared_function

# module_b.py (Layer 2)
from shared_utils import shared_function
```

### Rule 3: Same-Layer Lateral Dependencies Allowed

**✓ Allowed:**
```python
# order_service.py (Layer 2)
from payment_service import process_payment  # Same layer

def create_order(order_data):
    # Same-layer collaboration OK
    process_payment(order_data)
    return save_order(order_data)
```

**But be cautious:** Too many lateral dependencies indicate wrong layering

### Rule 4: Minimize Coupling

**Prefer fewer dependencies:**

```python
# ✓ Good - Single dependency
from data_access import fetch_all

def process():
    data = fetch_all()
    return transform(data)

# ✗ Bad - Multiple dependencies
from data_access import fetch_users, fetch_orders, fetch_products

def process():
    users = fetch_users()
    orders = fetch_orders()
    products = fetch_products()
    return combine(users, orders, products)
```

---

## LAYERING STRATEGIES

### Strategy 1: Three-Layer Architecture

```
┌──────────────┐
│ Presentation │ (UI, API endpoints, CLI)
├──────────────┤
│ Business     │ (Logic, validation, workflows)
├──────────────┤
│ Data Access  │ (Database, external APIs, storage)
└──────────────┘
```

**SUGA example:**
```
Gateway Layer    (Presentation)
    ↓
Interface Layer  (Business)
    ↓
Core Layer       (Data/Implementation)
```

### Strategy 2: Onion Architecture

```
     ┌────────────────┐
     │  UI/API Layer  │
     ├────────────────┤
     │ Application    │
     │ Services       │
     ├────────────────┤
     │ Domain Logic   │
     ├────────────────┤
     │ Domain Model   │ (Core - no dependencies)
     └────────────────┘
```

**Dependencies flow inward toward core**

### Strategy 3: Hexagonal Architecture

```
        Adapters (External)
             ↓
        Application Layer
             ↓
        Domain Core
        
Dependencies point inward to domain
```

---

## DETECTING VIOLATIONS

### Tool 1: Import Analysis

```python
# detect_cycles.py
import ast
import sys
from pathlib import Path

def find_imports(file_path):
    """Extract all imports from Python file."""
    with open(file_path) as f:
        tree = ast.parse(f.read())
    
    imports = []
    for node in ast.walk(tree):
        if isinstance(node, ast.Import):
            for alias in node.names:
                imports.append(alias.name)
        elif isinstance(node, ast.ImportFrom):
            imports.append(node.module)
    return imports

def build_dependency_graph(root_dir):
    """Build graph of all imports."""
    graph = {}
    for py_file in Path(root_dir).rglob("*.py"):
        module = str(py_file.relative_to(root_dir)).replace("/", ".")[:-3]
        imports = find_imports(py_file)
        graph[module] = imports
    return graph

def detect_cycles(graph):
    """Detect circular dependencies."""
    def has_path(start, end, visited=None):
        if visited is None:
            visited = set()
        if start == end:
            return True
        visited.add(start)
        for neighbor in graph.get(start, []):
            if neighbor not in visited:
                if has_path(neighbor, end, visited):
                    return True
        return False
    
    cycles = []
    for module in graph:
        for imported in graph[module]:
            if has_path(imported, module):
                cycles.append((module, imported))
    return cycles
```

### Tool 2: Layer Validation

```python
# validate_layers.py
LAYERS = {
    "presentation": ["api", "cli", "web"],
    "business": ["services", "handlers"],
    "data": ["database", "storage", "cache"],
}

def get_layer(module_path):
    """Determine which layer module belongs to."""
    for layer, patterns in LAYERS.items():
        if any(pattern in module_path for pattern in patterns):
            return layer
    return "unknown"

def validate_dependency(from_module, to_module):
    """Check if dependency follows layer rules."""
    from_layer = get_layer(from_module)
    to_layer = get_layer(to_module)
    
    # Higher depends on lower only
    layer_order = ["presentation", "business", "data"]
    
    from_idx = layer_order.index(from_layer)
    to_idx = layer_order.index(to_layer)
    
    if from_idx < to_idx:
        return False, f"Lower layer {from_layer} depends on higher {to_layer}"
    
    return True, "OK"
```

---

## BENEFITS

### Benefit 1: No Circular Imports

**Without DD-2:**
- Runtime import errors
- Mysterious bugs
- Hard to debug

**With DD-2:**
- Imports always work
- Clear dependency flow
- Easy to reason about

### Benefit 2: Testability

**Clear layers = easy testing:**

```python
# Test data layer independently
def test_fetch_user():
    user = fetch_user(1)
    assert user["id"] == 1

# Test business layer with mocked data layer
def test_validate_user(mocker):
    mocker.patch("data_access.fetch_user", return_value={"id": 1})
    assert validate_user(1) == True
```

**Bottom-up testing:** Test lower layers first, mock them for higher layers

### Benefit 3: Maintainability

**Clear boundaries:**
- Know where code belongs
- Easy to modify layers independently
- Reduced coupling

### Benefit 4: Parallel Development

**Independent layers:**
- Team A: Data layer
- Team B: Business layer (mocks data)
- Team C: Presentation layer (mocks business)

**Teams work without blocking each other**

---

## COMMON VIOLATIONS

### Violation 1: God Object

```python
# ❌ Single class depends on everything
class UserManager:
    def __init__(self):
        self.db = Database()
        self.cache = Cache()
        self.logger = Logger()
        self.metrics = Metrics()
        # 20+ dependencies
```

**Fix:** Split into layers with focused responsibilities

### Violation 2: Leaky Abstractions

```python
# ❌ Business layer exposes data layer details
def get_user(user_id):
    return database.execute("SELECT * FROM users WHERE id = ?", user_id)
```

**Fix:** Hide implementation details

```python
# ✓ Proper abstraction
def get_user(user_id):
    return user_repository.find_by_id(user_id)
```

### Violation 3: Skip Layers

```python
# ❌ Presentation directly calls data layer
def api_get_user(user_id):
    return database.fetch_user(user_id)  # Skips business layer
```

**Fix:** Go through proper layers

```python
# ✓ Proper layering
def api_get_user(user_id):
    return user_service.get_user(user_id)  # Business layer
```

---

## KEYWORDS

dependency disciplines, unidirectional dependencies, layer architecture, circular import prevention, dependency management, software architecture, clean architecture, layer rules

---

## RELATED TOPICS

- DD2-02: Layer Rules
- DD2-03: Flow Direction
- DD2-DEC-01: Higher-Lower Flow
- DD2-DEC-02: No Circular Dependencies
- SUGA: Three-layer pattern using DD-2

---

**END OF FILE**

**Version:** 1.0.0  
**Lines:** 398 (within 400 limit)  
**Category:** Python Architecture Pattern  
**Status:** Complete

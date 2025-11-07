# DEC-02-Three-Layer-Pattern.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Why three layers instead of two or four  
**Category:** Python Architecture - SUGA - Decisions

---

## DECISION

Use exactly three layers (Gateway, Interface, Core) in SUGA architecture. No more, no less.

---

## ALTERNATIVES CONSIDERED

### Alternative 1: Two Layers (Gateway + Core)
**Approach:** Skip interface layer

**Structure:**
```
Gateway → Core
```

**Pros:**
- Simpler (one less layer)
- Fewer files
- Direct routing

**Cons:**
- Gateway tightly coupled to cores
- Hard to swap implementations
- No abstraction layer
- Gateway knows core details

**Rejected:** Tight coupling problematic

### Alternative 2: Four Layers (Gateway + Interface + Service + Core)
**Approach:** Add service layer

**Structure:**
```
Gateway → Interface → Service → Core
```

**Pros:**
- More separation
- Service layer for business logic
- Clear boundaries

**Cons:**
- Over-engineered for Lambda
- Extra complexity
- More files to manage
- Unclear where logic belongs

**Rejected:** Unnecessary complexity

### Alternative 3: Three Layers (Selected)
**Approach:** Gateway, Interface, Core

**Structure:**
```
Gateway → Interface → Core
```

**Pros:**
- Clear responsibilities
- Interface abstracts core
- Gateway simple
- Sufficient separation
- Not over-engineered

**Cons:**
- Three files per feature
- Learning curve

**Selected:** Right balance of separation and simplicity

---

## RATIONALE

### Why Not Two Layers

**Problem with Gateway → Core:**
```python
# gateway.py
def cache_get(key):
    import cache_core  # Gateway knows about specific core
    return cache_core.get_impl(key)  # Coupled to implementation

def alternate_cache_get(key):
    import redis_core  # Different implementation
    return redis_core.get(key)  # Different function name!
```

**Issues:**
- Gateway coupled to core implementation
- Hard to swap implementations
- Gateway needs to know core details
- No abstraction

**With Interface Layer:**
```python
# gateway.py
def cache_get(key):
    import interface_cache  # Only knows interface
    return interface_cache.get(key)  # Consistent API

# interface_cache.py can route to ANY core
def get(key):
    import cache_core  # or redis_core, or memory_core
    return cache_core.get_impl(key)  # Interface handles details
```

**Benefits:**
- Gateway decoupled from core
- Easy to swap implementations
- Interface provides abstraction
- Consistent API

### Why Not Four Layers

**Problem with Gateway → Interface → Service → Core:**

Where does logic go?
- Validation: Service or Core?
- Error handling: Service or Core?
- Business rules: Service or Core?
- Data transformation: Service or Core?

**Result:** Confusion, inconsistency

**With Three Layers (clear rules):**
- Gateway: Routing only
- Interface: Routing only
- Core: All logic

**No confusion.**

---

## LAYER JUSTIFICATION

### Layer 1: Gateway
**Purpose:** Public API entry point

**Why needed:**
- Single import for external code (`import gateway`)
- Consistent function names
- Easy to find functionality
- Clear public API

**Can't combine with Interface:**
Would expose interface imports to external code.

### Layer 2: Interface
**Purpose:** Abstraction and routing

**Why needed:**
- Decouples gateway from cores
- Allows implementation swapping
- Provides consistent API
- Coordinates multiple cores if needed

**Can't combine with Gateway:**
Would couple gateway to cores.

**Can't combine with Core:**
Would mix routing and implementation.

### Layer 3: Core
**Purpose:** Implementation

**Why needed:**
- Contains all business logic
- Data manipulation
- Error handling
- State management

**Can't combine with Interface:**
Would mix routing and logic.

---

## RESPONSIBILITIES

### Gateway Layer
```python
def action(params):
    """Public API function."""
    import interface_x
    return interface_x.action(params)
```

**ONLY:**
- Import interface
- Call interface function
- Return result

### Interface Layer
```python
def action(params):
    """Route to core."""
    import x_core
    return x_core.action_impl(params)
```

**ONLY:**
- Import core
- Call core function
- Return result

### Core Layer
```python
def action_impl(params):
    """Implementation."""
    # ALL logic here
    validate(params)
    process(params)
    return result
```

**ALL:**
- Business logic
- Validation
- Processing
- Error handling

---

## VERIFICATION

**Three-layer check:**
- [ ] Gateway file exists
- [ ] Interface file exists
- [ ] Core file exists
- [ ] Gateway imports interface only
- [ ] Interface imports core only
- [ ] Core has all implementation

**Not two layers:**
- [ ] Gateway doesn't import core directly
- [ ] No missing interface

**Not four layers:**
- [ ] No service layer
- [ ] No additional abstraction layers

---

## WHEN TO DEVIATE

**NEVER add fourth layer** (service, repository, etc.)

**Reason:** Adds complexity without benefit in Lambda.

**If logic seems complex:**
- Split core into multiple cores (if different domains)
- Use helper functions within core
- Extract to separate module (still used via gateway)

**Example (right way to handle complexity):**
```python
# Split complex core into multiple cores
cache_core.py
cache_validation_core.py
cache_transformation_core.py

# All accessed via same interface
interface_cache.py routes to appropriate core
```

**Not:**
```python
# ❌ WRONG: Adding service layer
cache_service.py  # Unnecessary abstraction
```

---

## CONSEQUENCES

### Positive
- ✅ Clear responsibilities
- ✅ Easy to understand where code goes
- ✅ Decoupled layers
- ✅ Not over-engineered
- ✅ Sufficient separation

### Negative
- ❌ Three files per feature
- ❌ More navigation between files

### Mitigation
- Good IDE (jump to definition)
- Clear naming conventions
- Templates for new features

---

**Related:**
- ARCH-01: Gateway trinity
- ARCH-02: Layer separation
- DEC-01: SUGA choice
- DEC-03: Gateway mandatory

**Version History:**
- v1.0.0 (2025-11-06): Initial three-layer decision

---

**END OF FILE**

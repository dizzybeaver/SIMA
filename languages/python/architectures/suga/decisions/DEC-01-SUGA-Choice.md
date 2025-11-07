# DEC-01-SUGA-Choice.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Why SUGA architecture was chosen  
**Category:** Python Architecture - SUGA - Decisions

---

## DECISION

Use SUGA (Single Universal Gateway Architecture) pattern for all cross-module communication in Python Lambda projects.

---

## CONTEXT

Early Lambda development suffered from:
- Circular import errors
- Tight coupling between modules
- Difficult testing
- Slow cold starts (all imports at module level)
- Hard to trace dependencies

**Problem:** Standard Python import patterns don't work well in serverless.

---

## ALTERNATIVES CONSIDERED

### Alternative 1: Direct Imports
**Approach:** Standard Python imports

**Pros:**
- Simple
- Familiar
- No overhead

**Cons:**
- Circular imports common
- Tight coupling
- All imports loaded immediately
- Hard to mock for testing

**Rejected:** Circular imports too frequent

### Alternative 2: Dependency Injection
**Approach:** Pass dependencies as parameters

**Pros:**
- Very testable
- No imports needed
- Clear dependencies

**Cons:**
- Verbose function signatures
- Complex initialization
- Unfamiliar pattern for Python

**Rejected:** Too complex for Lambda

### Alternative 3: Service Locator
**Approach:** Central registry for services

**Pros:**
- Loose coupling
- Easy to swap implementations

**Cons:**
- Hidden dependencies
- Runtime errors if not registered
- Extra complexity

**Rejected:** Hidden dependencies problematic

### Alternative 4: SUGA Pattern
**Approach:** Three-layer lazy-loaded gateway

**Pros:**
- Breaks circular imports (lazy loading)
- Single entry point (gateway)
- Clear architecture (three layers)
- Fast cold start (load on demand)
- Easy testing (mock interfaces)
- Explicit dependencies

**Cons:**
- Extra layer of indirection
- More files to manage

**Selected:** Pros outweigh cons for Lambda

---

## RATIONALE

### Reason 1: Prevents Circular Imports
Lazy imports at function level break circular dependencies:
```python
# Module A
def function_a():
    import module_b  # Loaded only when called
    module_b.function_b()

# Module B
def function_b():
    import module_a  # Loaded only when called
    module_a.function_a()
```

**Result:** No circular import errors

### Reason 2: Faster Cold Start
Only import what's needed:
```python
# NOT loaded at module level
def rarely_used_function():
    import heavy_module  # Only loads if function called
    return heavy_module.process()
```

**Result:** < 3 second cold start achieved

### Reason 3: Single Entry Point
All external code uses gateway:
```python
import gateway
gateway.cache_get(key)    # Clear, consistent
gateway.log_info(message) # All through gateway
```

**Result:** Easy to trace, easy to mock

### Reason 4: Clear Architecture
Three layers with clear responsibilities:
- Gateway: Public API
- Interface: Routing
- Core: Implementation

**Result:** Know where code belongs

### Reason 5: Testability
Mock at interface level:
```python
# Test code
def test_function():
    mock_interface = Mock()
    # Test using mock
```

**Result:** Fast, isolated tests

---

## IMPLEMENTATION

### Step 1: Create Gateway
Central entry point for all functionality
```python
# gateway.py
from gateway_wrappers_cache import cache_get, cache_set
# ... all wrappers
```

### Step 2: Create Interfaces
One per domain (cache, logging, http, etc.)
```python
# interface_cache.py
def get(key):
    import cache_core
    return cache_core.get_impl(key)
```

### Step 3: Create Cores
Implementation logic
```python
# cache_core.py
def get_impl(key):
    # Implementation
    return _cache.get(key)
```

### Step 4: Use Gateway Everywhere
```python
# Any module
import gateway
value = gateway.cache_get(key)
```

---

## CONSEQUENCES

### Positive
- ✅ Zero circular imports
- ✅ Cold start < 3 seconds
- ✅ Clear architecture
- ✅ Easy testing
- ✅ Explicit dependencies

### Negative
- ❌ Extra layer of indirection
- ❌ More files (3x: gateway, interface, core)
- ❌ Learning curve for new developers

### Mitigation
- Document pattern thoroughly (this file)
- Provide templates (Workflow-01)
- Enforce via code review (Checklist-01)

---

## VALIDATION

**Metrics (achieved):**
- Cold start: 2.8s average (target < 3s) ✅
- Zero circular imports since adoption ✅
- Test coverage: 85% (target 80%) ✅
- Developer onboarding: 2 days (acceptable) ✅

**Decision confirmed successful.**

---

## CONSTRAINTS

**Must follow:**
- All cross-module calls via gateway
- Lazy imports at function level
- Three-layer structure mandatory
- No direct core-to-core imports

**RED FLAGS:**
- Direct core imports (AP-01)
- Module-level imports (AP-02)
- Skipping gateway (AP-XX)

---

## WHEN TO DEVIATE

**NEVER deviate in Lambda projects.**

**Might deviate for:**
- Non-serverless Python projects
- Projects without circular import risk
- Simple scripts with few dependencies

**For LEE project:** SUGA is mandatory.

---

**Related:**
- ARCH-01: Gateway trinity
- ARCH-02: Layer separation
- GATE-01: Gateway entry
- DEC-02: Three-layer pattern
- AP-01: Direct core import

**Version History:**
- v1.0.0 (2025-11-06): Initial SUGA choice decision

---

**END OF FILE**

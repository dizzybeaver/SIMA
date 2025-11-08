# DD2-DEC-01-Higher-Lower-Flow.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Decision to enforce unidirectional higher-to-lower dependency flow  
**Type:** Architecture Decision

---

## DECISION: Dependencies Flow Higher → Lower Only

**Status:** Adopted  
**Date:** 2024-09-15  
**Context:** SUGA architecture layer organization  
**Impact:** All projects using layered architecture patterns

---

## PROBLEM STATEMENT

Without clear dependency direction rules:
- Circular imports occur frequently
- Module relationships become unclear
- Testing becomes difficult (can't test lower layers independently)
- Refactoring is risky (unclear what depends on what)
- Architecture degrades over time

---

## DECISION

**All dependencies must flow from higher layers to lower layers only.**

Layer hierarchy (highest to lowest):
```
Gateway Layer (highest)
    â†" CAN depend on
Interface Layer
    â†" CAN depend on  
Core Layer (lowest)
```

**Rules:**
1. Higher layers MAY depend on lower layers
2. Lower layers MUST NOT depend on higher layers
3. Layers at same level SHOULD minimize cross-dependencies
4. All cross-layer imports go through defined interfaces

---

## RATIONALE

### Benefits

**1. Prevents Circular Imports**
- Python cannot handle circular imports
- Unidirectional flow makes cycles impossible
- Clear dependency graph

**2. Enables Bottom-Up Testing**
- Test core layer first (no dependencies)
- Test interface layer next (depends only on core)
- Test gateway layer last (depends on both)
- Each layer tested independently

**3. Clarifies Architecture**
- Visual dependency graph is simple
- Easy to understand system organization
- New developers grasp structure quickly

**4. Enables Safe Refactoring**
- Changes to lower layers don't break higher layers (usually)
- Can replace lower layer implementations
- Clear impact analysis

**5. Reduces Coupling**
- Limits dependencies to one direction
- Prevents tight bidirectional coupling
- Easier to maintain

### Trade-offs

**Cost: More Planning Required**
- Must think about layer placement
- Can't just import whatever you need
- Requires discipline

**Mitigation:** Document layer responsibilities clearly

---

## CONSEQUENCES

### Positive

**Clear Architecture:**
```python
# gateway_wrappers.py (Gateway Layer)
def cache_get(key):
    import interface_cache  # â†" Lower layer
    return interface_cache.get(key)

# interface_cache.py (Interface Layer)  
def get(key):
    import cache_core  # â†" Lower layer
    return cache_core.get_impl(key)

# cache_core.py (Core Layer)
def get_impl(key):
    # No imports from higher layers!
    return implementation
```

**Testable Layers:**
```python
# Test core first (no dependencies)
def test_cache_core():
    result = cache_core.get_impl("key")
    assert result is not None

# Test interface next (only depends on core)
def test_interface_cache():
    result = interface_cache.get("key")
    assert result is not None
```

### Negative

**Cannot Call Up:**
```python
# cache_core.py (Core Layer)
def get_impl(key):
    # âŒ WRONG - Cannot import from higher layer!
    # import interface_logging
    # interface_logging.log_info("Getting key")
    
    # âœ… CORRECT - Pass logger as parameter
    return implementation
```

**Workaround:** Pass dependencies down via parameters or use dependency injection

---

## ALTERNATIVES CONSIDERED

### Alternative 1: No Dependency Rules
**Why rejected:** Leads to circular imports and architectural decay

### Alternative 2: Bidirectional Dependencies Allowed
**Why rejected:** Creates tight coupling, difficult to test

### Alternative 3: Event-Based Decoupling
**Why rejected:** Too complex for current needs, adds latency

---

## IMPLEMENTATION

### Detection

**Use static analysis:**
```python
# check_dependencies.py
def check_layer_dependencies(file_path):
    layer = get_layer(file_path)
    imports = extract_imports(file_path)
    
    for imp in imports:
        imp_layer = get_layer(imp)
        if imp_layer > layer:
            raise ValueError(f"Invalid dependency: {file_path} imports {imp}")
```

### Enforcement

**Code review checklist:**
- [ ] All imports follow higher â†' lower flow
- [ ] No circular imports detected
- [ ] Layer boundaries clear

**CI/CD check:**
- Run dependency analyzer on every commit
- Block merge if violations found

---

## RELATED

**Decisions:**
- DD2-DEC-02: No Circular Dependencies
- DEC-01: SUGA Architecture Choice
- DEC-02: Three-Layer Pattern

**Lessons:**
- DD2-LESS-01: Dependencies Have Cost
- DD2-LESS-02: Layer Violations Compound

**Anti-Patterns:**
- AP-01: Direct Core Import (violates this)
- AP-03: Circular Module References

---

## EXAMPLES

### Correct Flow

**Gateway calls Interface:**
```python
# gateway_wrappers_cache.py
def cache_set(key, value):
    import interface_cache  # âœ… Higher â†' Lower
    return interface_cache.set(key, value)
```

**Interface calls Core:**
```python
# interface_cache.py
def set(key, value):
    import cache_core  # âœ… Higher â†' Lower
    return cache_core.set_impl(key, value)
```

**Core has no upward dependencies:**
```python
# cache_core.py
def set_impl(key, value):
    # âœ… No imports from Interface or Gateway layers
    cache[key] = value
    return True
```

### Incorrect Flow (Violations)

**Core calling Interface:**
```python
# cache_core.py
def set_impl(key, value):
    import interface_logging  # âŒ Lower â†' Higher (WRONG!)
    interface_logging.log_info(f"Setting {key}")
    cache[key] = value
```

**Core calling Gateway:**
```python
# cache_core.py
def set_impl(key, value):
    import gateway  # âŒ Lower â†' Higher (WRONG!)
    gateway.log_info(f"Setting {key}")
    cache[key] = value
```

---

## VERIFICATION

**Check compliance:**
```bash
# Run dependency analyzer
python check_dependencies.py

# Expected output:
# âœ… All dependencies flow higher â†' lower
# âœ… No circular imports detected
# âœ… Layer boundaries maintained
```

**Manual review:**
1. Pick random file from each layer
2. Check all imports
3. Verify all imports are to lower layers only

---

## KEYWORDS

dependencies, layer-flow, unidirectional, higher-lower, architecture-rules, import-direction, testing-strategy, circular-prevention

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial decision document
- Higher â†' Lower flow enforced
- Testing benefits documented
- Verification methods defined

---

**END OF FILE**

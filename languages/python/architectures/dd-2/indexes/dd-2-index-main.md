# dd-2-index-main.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Master index for DD-2 (Dependency Disciplines) Architecture  
**Type:** Index

---

## DD-2: DEPENDENCY DISCIPLINES ARCHITECTURE

**Pattern Type:** Architecture Pattern  
**Purpose:** Managing dependencies between architectural layers  
**Origin:** SIMA migration architecture patterns  
**Used In:** All layered architectures (SUGA, LMMS, etc.)

---

## OVERVIEW

**Dependency Disciplines (DD-2)** defines rules for managing dependencies between layers in a modular architecture.

**Core Principle:** Dependencies flow unidirectionally from higher layers to lower layers only.

**Key Benefits:**
- Prevents circular imports
- Enables independent testing
- Clarifies architecture
- Reduces coupling
- Enables safe refactoring

---

## CORE CONCEPTS

### Layer Hierarchy

```
Gateway Layer (highest)
    â†" CAN depend on
Interface Layer (middle)
    â†" CAN depend on
Core Layer (lowest)
```

### Golden Rules

1. **Higher â†' Lower Only:** Dependencies flow downward
2. **No Circular Dependencies:** Zero tolerance policy
3. **Minimize Dependencies:** Each dependency has a cost
4. **Layer Boundaries:** Clear separation between layers

---

## ARCHITECTURE FILES

### Core Concepts (3 files)

**DD2-01: Core Concept**
- Dependency discipline fundamentals
- Layer hierarchy explanation
- Flow direction rules
- /sima/languages/python/architectures/dd-2/core/DD2-01-Core-Concept.md

**DD2-02: Layer Rules**
- Specific layer dependency rules
- What each layer can/cannot import
- Layer boundary definitions
- /sima/languages/python/architectures/dd-2/core/DD2-02-Layer-Rules.md

**DD2-03: Flow Direction**
- Unidirectional flow requirements
- Dependency graph structures
- Testing implications
- /sima/languages/python/architectures/dd-2/core/DD2-03-Flow-Direction.md

---

### Decisions (2 files)

**DD2-DEC-01: Higher-Lower Flow**
- Decision to enforce higher â†' lower dependencies only
- Rationale and benefits
- Implementation strategies
- /sima/languages/python/architectures/dd-2/decisions/DD2-DEC-01-Higher-Lower-Flow.md

**DD2-DEC-02: No Circular Dependencies**
- Zero tolerance for circular dependencies
- Detection and breaking methods
- CI/CD enforcement
- /sima/languages/python/architectures/dd-2/decisions/DD2-DEC-02-No-Circular-Dependencies.md

---

### Lessons (2 files)

**DD2-LESS-01: Dependencies Have Cost**
- Every dependency has multiple costs
- Quantified impact measurements
- Minimization strategies
- /sima/languages/python/architectures/dd-2/lessons/DD2-LESS-01-Dependencies-Cost.md

**DD2-LESS-02: Layer Violations Compound**
- First violation enables all others
- Exponential cascade pattern
- Fix-immediately policy
- /sima/languages/python/architectures/dd-2/lessons/DD2-LESS-02-Layer-Violations.md

---

### Anti-Patterns (1 file)

**DD2-AP-01: Upward Dependencies**
- Lower layers importing from higher layers
- Why this breaks architecture
- How to fix properly
- /sima/languages/python/architectures/dd-2/anti-patterns/DD2-AP-01-Upward-Dependencies.md

---

## QUICK REFERENCE

### Dependency Rules

**âœ… Allowed:**
- Gateway â†' Interface
- Gateway â†' Core
- Interface â†' Core
- Core â†' External libraries
- Same layer â†' Same layer (minimized)

**âŒ Prohibited:**
- Core â†' Interface
- Core â†' Gateway
- Interface â†' Gateway
- Any circular dependencies

### Common Patterns

**Dependency Injection:**
```python
# Pass higher-layer dependencies as parameters
def core_function(data, logger=None):
    if logger:
        logger("Processing")
    return process(data)
```

**Lazy Import:**
```python
# Import only when needed (for optional features)
def rarely_used():
    import expensive_module
    return expensive_module.process()
```

**Types Extraction:**
```python
# Extract shared types to separate file
# types.py
class MyType: pass

# Both layers import types (no cycle)
```

---

## USAGE GUIDELINES

### When to Apply DD-2

**Always:**
- Any layered architecture (SUGA, etc.)
- Multi-module Python projects
- Codebases with >5 modules

**Benefits:**
- Clear architecture
- Easier testing
- Reduced coupling
- No circular imports

### How to Apply

**Step 1: Define Layers**
- Identify your layers (Gateway, Interface, Core, etc.)
- Document layer responsibilities
- Assign files to layers

**Step 2: Enforce Rules**
- Add automated dependency checks
- Review imports in code review
- Fix violations immediately

**Step 3: Monitor**
- Track violation count (should be zero)
- Measure dependency count per file
- Review architecture periodically

---

## RELATIONSHIP TO OTHER PATTERNS

### SUGA Architecture

DD-2 defines the dependency rules that SUGA layers follow:
- SUGA provides the 3-layer structure
- DD-2 provides the dependency discipline

**Reference:** /sima/languages/python/architectures/suga/

### DD-1 (Dictionary Dispatch)

DD-1 and DD-2 are complementary but distinct:
- DD-1: Performance optimization (function routing)
- DD-2: Architecture pattern (dependency management)

**Reference:** /sima/languages/python/architectures/dd-1/

### LMMS (Lazy Module Management)

DD-2 and LMMS work together:
- DD-2: Which modules can import which
- LMMS: When to import (module vs function level)

**Reference:** /sima/languages/python/architectures/lmms/

---

## ENFORCEMENT

### Automated Checks

```python
# check_dependencies.py
def check_dd2_compliance(file_path):
    file_layer = get_layer(file_path)
    for import_line in get_imports(file_path):
        import_layer = get_layer(import_line)
        if import_layer > file_layer:
            raise DD2Violation(f"Upward dependency: {file_path}")
```

### CI/CD Integration

```yaml
# .github/workflows/dd2-check.yml
- name: Check DD-2 Compliance
  run: |
    python check_dependencies.py
    if [ $? -ne 0 ]; then
      echo "DD-2 violations detected"
      exit 1
    fi
```

---

## METRICS

### Health Indicators

**Green (Healthy):**
- Zero upward dependencies
- Zero circular dependencies
- Average imports per file < 3
- All tests pass independently

**Yellow (Needs Attention):**
- 1-2 minor violations
- High import count (5+)
- Some test dependencies

**Red (Critical):**
- Multiple upward dependencies
- Circular imports present
- Cannot test layers independently
- Architecture compromised

---

## RELATED PATTERNS

**Generic Patterns:**
- /sima/entries/anti-patterns/import/AP-01.md (Direct Core Import)
- /sima/entries/anti-patterns/import/AP-03.md (Circular Modules)

**SUGA Patterns:**
- /sima/languages/python/architectures/suga/core/ARCH-01-Gateway-Trinity.md
- /sima/languages/python/architectures/suga/gateways/GATE-03-Cross-Interface-Communication.md

**Python Patterns:**
- /sima/entries/languages/python/LANG-PY-05_Python_Import_Module_Organization.md

---

## KEYWORDS

dependency-disciplines, layer-dependencies, architecture-rules, unidirectional-flow, circular-prevention, import-management, testing-strategy, coupling-reduction, dd-2

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial DD-2 master index
- 8 total files documented
- Core concepts defined
- Usage guidelines provided
- Enforcement strategies documented

---

**END OF FILE**

**Total DD-2 Files:** 8  
**Status:** Complete  
**Pattern Type:** Architecture  
**Complementary To:** SUGA, LMMS, DD-1

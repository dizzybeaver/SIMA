# NM02-RULES-Import Rules and Validation.md

# RULES - Import Rules & Validation

**Category:** NM02 - Dependencies
**Topic:** Import Rules
**Last Updated:** 2025-10-23 (Phase 5 terminology corrections)

---

## Purpose

This file documents import rules, circular import prevention mechanisms, dependency matrices, validation checklists, and visual diagrams. It's the **Implementation Layer** for import governance in the SUGA architectural pattern.

**Access via:** NM02-INDEX-Dependencies.md or direct search

---

## ⚠️ CRITICAL TERMINOLOGY

**SUGA** = Single Universal Gateway Architecture (the gateway pattern in Lambda code)  
**SIMA** = Synthetic Integrate Memory Architecture (neural maps system)

Always use "SUGA pattern" when referring to gateway architecture.

---

## PART 1: IMPORT RULES

### Rule 1: Cross-Interface Imports MUST Use Gateway

**REF:** NM02-RULE-01  
**PRIORITY:** CRITICAL  
**TAGS:** imports, cross-interface, gateway, rules, SUGA  
**KEYWORDS:** cross interface imports, gateway requirement, import rules  
**RELATED:** NM04-DEC-01, NM05-AP-01, NM07-DT-01

```python
# CORRECT: Cross-interface via gateway
# In cache_core.py
from gateway import log_info, record_metric

def cache_operation():
    log_info("Cache operation")      # LOGGING via gateway
    record_metric("cache_hit", 1.0)  # METRICS via gateway

# WRONG: Direct cross-interface import
# In cache_core.py
from logging_core import log_info      # VIOLATION
from metrics_core import record_metric # VIOLATION
```

**Why This Rule Exists:**
- Prevents circular import dependencies
- Centralizes interface access
- Enforces architectural boundaries
- Simplifies debugging and testing

**Real-World Usage:**
```
User: "Can cache_core.py import logging_core.py directly?"
Claude searches: "cross interface imports gateway"
Finds: NM02-RULE-01
Response: "NO - All cross-interface imports must use gateway to prevent circular dependencies."
```

---

### Rule 2: Intra-Interface Imports Are Direct

**REF:** NM02-RULE-02  
**PRIORITY:** CRITICAL  
**TAGS:** imports, intra-interface, same-interface, direct-import  
**KEYWORDS:** same interface imports, direct imports, intra-interface  
**RELATED:** NM01-ARCH-03, NM01-ARCH-04

```python
# CORRECT: Same interface, direct import
# In cache_core.py
from cache_manager import CacheManager
from cache_operations import validate_key

def perform_operation(key):
    validate_key(key)          # Same interface
    manager = CacheManager()   # Same interface

# WRONG: Using gateway for same interface (unnecessary)
# In cache_core.py
from gateway import cache_manager_get  # WRONG - same interface
```

**Why This Rule Exists:**
- Reduces unnecessary gateway routing overhead
- Makes code more maintainable
- Clearly indicates module boundaries
- Simplifies internal refactoring

---

### Rule 3: External Code Imports Gateway Only

**REF:** NM02-RULE-03  
**PRIORITY:** CRITICAL  
**TAGS:** imports, external-code, lambda, entry-point, gateway-only  
**KEYWORDS:** lambda imports, external imports, entry point imports  
**RELATED:** NM01-ARCH-06, NM05-AP-05

```python
# CORRECT: Lambda imports
# In lambda_function.py
import gateway

def lambda_handler(event, context):
    gateway.log_info("Request received")
    result = gateway.cache_get("key")
    return result

# WRONG: Lambda importing core directly
# In lambda_function.py
from cache_core import get_value  # VIOLATION
from logging_core import log_info # VIOLATION
```

**Why This Rule Exists:**
- Clean API surface for external code
- Gateway provides stability guarantees
- Core modules can change without breaking lambda
- Clear architectural boundary

---

## PART 2: SUGA PATTERN ENFORCEMENT

### How SUGA Pattern Prevents Circular Imports

**Architectural Foundation:**

```
SUGA Pattern Structure:
gateway.py → imports all *_core.py files (ONE DIRECTION)
*_core.py → imports NOTHING (stdlib only)
interface_*.py → imports gateway_core.py + specific *_core.py

Result: Directed Acyclic Graph (DAG) - no cycles possible
```

**Why It Works:**

1. **Core Isolation:**
   - Core modules never import other cores
   - Dependencies flow one direction only
   - Circular dependencies structurally impossible

2. **Gateway as Hub:**
   - Gateway aggregates all interfaces
   - Cross-interface calls go through gateway
   - No direct core-to-core dependencies

3. **Enforced by Architecture:**
   - Not relying on developer discipline
   - Structure prevents the mistake
   - Violations are obvious in code review

---

## PART 3: VALIDATION CHECKLIST

### Pre-Commit Checklist

Before committing code with new imports:

- [ ] All cross-interface imports use `import gateway`?
- [ ] No direct imports between core modules?
- [ ] Lambda code only imports gateway?
- [ ] Same-interface imports are direct (not through gateway)?
- [ ] No circular import errors when testing?

### Code Review Checklist

When reviewing import changes:

- [ ] New imports follow RULE-01, RULE-02, RULE-03?
- [ ] Import paths are correct for file location?
- [ ] No unnecessary gateway routing for same-interface?
- [ ] External code using gateway, not cores?
- [ ] Import order follows style guide?

---

## PART 4: COMMON VIOLATIONS

### Violation 1: Direct Cross-Interface Import

**Pattern:**
```python
# In cache_core.py
from logging_core import log_info  # ❌ VIOLATION
```

**Problem:** Creates potential for circular dependencies

**Fix:**
```python
# In cache_core.py
import gateway
gateway.log_info("message")  # ✅ CORRECT
```

---

### Violation 2: Lambda Importing Cores

**Pattern:**
```python
# In lambda_function.py
from cache_core import get_value  # ❌ VIOLATION
```

**Problem:** Bypasses gateway layer, couples to implementation

**Fix:**
```python
# In lambda_function.py
import gateway
result = gateway.cache_get("key")  # ✅ CORRECT
```

---

### Violation 3: Gateway for Same-Interface

**Pattern:**
```python
# In cache_core.py  
from gateway import cache_manager_get  # ❌ VIOLATION (unnecessary)
```

**Problem:** Adds unnecessary routing overhead

**Fix:**
```python
# In cache_core.py
from cache_manager import CacheManager  # ✅ CORRECT
```

---

## PART 5: DEPENDENCY VISUALIZATION

### Valid Import Patterns

```
External Code (lambda_function.py)
       ↓
   gateway.py
  /    |    \
 ↓     ↓     ↓
cache logging metrics  (interfaces)
 ↓     ↓     ↓
*_core.py files (implementations)

Direction: Top to bottom only
Circular: Impossible by structure
```

### Invalid Import Pattern (Circular)

```
❌ This cannot happen with SUGA:

cache_core.py ⇄ logging_core.py

Why: Core modules never import each other
```

---

## Keywords

import rules, cross-interface, gateway requirement, SUGA pattern, circular imports, validation, dependency graph

---

## Version History

- **2025-10-23**: Phase 5 terminology corrections (SIMA → SUGA)
- **2025-10-20**: Updated for SIMA v3 neural maps structure
- **2024-10-15**: Created import rules documentation

---

**File:** `NM02-RULES-Import Rules and Validation.md`  
**End of Document**

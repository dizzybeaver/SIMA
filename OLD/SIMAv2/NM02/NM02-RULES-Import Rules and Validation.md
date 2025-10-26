# NM02-RULES: Import Rules & Validation
# SIMA Architecture Pattern - Import Governance
# Version: 2.0.0 | Phase: 2 SIMA Implementation

---

## Purpose

This file documents import rules, circular import prevention mechanisms, dependency matrices, validation checklists, and visual diagrams. It's the **Implementation Layer** for import governance in the SIMA pattern.

**Access via:** NM02-INDEX-Dependencies.md or direct search

---

## PART 1: IMPORT RULES

### Rule 1: Cross-Interface Imports MUST Use Gateway
**REF:** NM02-RULE-01
**PRIORITY:** CRITICAL
**TAGS:** imports, cross-interface, gateway, rules, SIMA
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
from gateway import log_info, cache_get, http_post
from homeassistant_extension import process_alexa_request

# WRONG: Lambda importing internals
# In lambda_function.py
from interface_cache import execute_cache_operation  # VIOLATION
from cache_core import perform_operation            # VIOLATION
```

**What Counts as "External Code":**
- lambda_function.py (main entry point)
- lambda_diagnostics.py (diagnostics entry point)
- lambda_emergency.py (emergency access point)
- Any code outside the core architecture

---

### Rule 4: Flat File Structure
**REF:** NM02-RULE-04
**PRIORITY:** MEDIUM
**TAGS:** file-structure, flat-structure, organization, architecture
**KEYWORDS:** flat structure, file organization, no subdirectories
**RELATED:** NM04-DEC-06, NM01-ARCH-07

```
All files in root directory (except Home Assistant extension)
- Rationale: Historical decision, proven to work
- Benefit: Simple import paths
- Exception: home_assistant/ subdirectory for HA internals
- Pattern: interface_<n>.py and <n>_core.py in root
```

**File Structure:**
```
project_root/
├── gateway.py
├── gateway_core.py
├── interface_cache.py
├── cache_core.py
├── interface_logging.py
├── logging_core.py
├── home_assistant/
│   ├── __init__.py
│   └── alexa_handler.py
└── tests/
```

---

### Rule 5: Lambda Entry Point Restrictions
**REF:** NM02-RULE-05
**PRIORITY:** HIGH
**TAGS:** lambda, entry-point, restrictions, imports, flow
**KEYWORDS:** lambda restrictions, entry point rules, lambda imports
**RELATED:** NM01-ARCH-06, NM05-AP-05

```
lambda_function.py restrictions:
- MUST import only from gateway.py
- MUST NOT import interface routers
- MUST NOT import implementation files
- MAY import high-level extension modules
- MUST handle errors gracefully
```

---

## PART 2: CIRCULAR IMPORT PREVENTION

### How Gateway Prevents Circular Imports
**REF:** NM02-PREVENT-01
**PRIORITY:** CRITICAL
**TAGS:** circular-import, prevention, gateway, mechanism
**KEYWORDS:** prevent circular imports, gateway pattern, import safety
**RELATED:** NM01-ARCH-01, NM04-DEC-01, NM06-BUG-02

**The Problem Without Gateway:**
```python
# cache_core.py
from logging_core import log_info  # A imports B

# logging_core.py  
from cache_core import cache_log_entry  # B imports A

# Result: Circular import error!
```

**The Solution With Gateway:**
```python
# cache_core.py
from gateway import log_info  # A imports gateway

# logging_core.py
from gateway import cache_get  # B imports gateway

# gateway.py imports both (but at module level, not execution)
# Result: No circular imports!
```

**Why It Works:**
1. Gateway imports all interfaces at module load time
2. Interfaces only import gateway (one-way dependency)
3. Gateway acts as mediator between interfaces
4. No interface directly imports another interface

---

### Gateway Mediation Mechanism
**REF:** NM02-MECHANISM-01
**PRIORITY:** MEDIUM
**TAGS:** gateway, mediation, mechanism, how-it-works
**KEYWORDS:** gateway mediation, how gateway works, mediation pattern
**RELATED:** NM01-ARCH-02, NM02-PREVENT-01

**Step-by-Step Example:**

```
cache_core.py wants to log something:

Step 1: cache_core.py imports gateway
    from gateway import log_info

Step 2: cache_core.py calls log_info("message")
    log_info("Cache hit")

Step 3: gateway.log_info() is called
    def log_info(message, **kwargs):
        return execute_operation("log_info", message=message, **kwargs)

Step 4: gateway routes to interface
    from interface_logging import execute_logging_operation
    return execute_logging_operation("log_info", message, **kwargs)

Step 5: logging_core executes
    # Actual logging implementation
    print(f"[INFO] {message}")

Result: Cache -> Gateway -> Logging (no direct import)
```

---

## PART 3: DEPENDENCY MATRICES

### Who Depends on Who (Detailed)
**REF:** NM02-MATRIX-01
**PRIORITY:** HIGH
**TAGS:** dependencies, matrix, relationships, interfaces
**KEYWORDS:** dependency matrix, who depends on who, interface relationships
**RELATED:** NM02-DEP-01, NM02-DEP-02, NM02-DEP-03

```
Interface       -> Depends On
========================================================
LOGGING         -> None (base layer)
SECURITY        -> LOGGING
UTILITY         -> None (LOGGING optional)
SINGLETON       -> LOGGING
METRICS         -> LOGGING
CACHE           -> LOGGING, METRICS
CONFIG          -> LOGGING, CACHE, SECURITY
HTTP_CLIENT     -> LOGGING, SECURITY, CACHE, METRICS
WEBSOCKET       -> LOGGING, SECURITY, METRICS
CIRCUIT_BREAKER -> LOGGING, METRICS
INITIALIZATION  -> LOGGING, CONFIG
DEBUG           -> LOGGING, All interfaces
```

**Key Insights:**
- LOGGING has zero dependencies (Layer 0)
- Most interfaces depend on LOGGING (universal need)
- HTTP_CLIENT has most dependencies (Layer 3)
- DEBUG depends on all interfaces (Layer 4)

---

### Who Uses Who (Inverse Matrix)
**REF:** NM02-MATRIX-02
**PRIORITY:** HIGH
**TAGS:** dependencies, inverse-matrix, used-by, interfaces
**KEYWORDS:** inverse matrix, who uses who, used by
**RELATED:** NM02-MATRIX-01

```
Interface       <- Used By
========================================================
LOGGING         <- All interfaces (universal)
SECURITY        <- HTTP_CLIENT, CONFIG, WEBSOCKET
UTILITY         <- All interfaces (helpers)
SINGLETON       <- Various interfaces (stateful storage)
METRICS         <- CACHE, HTTP_CLIENT, CIRCUIT_BREAKER
CACHE           <- HTTP_CLIENT, CONFIG, SECURITY
CONFIG          <- All interfaces (configuration)
HTTP_CLIENT     <- homeassistant_extension, external
WEBSOCKET       <- homeassistant_extension
CIRCUIT_BREAKER <- HTTP_CLIENT, WEBSOCKET
INITIALIZATION  <- lambda_function.py
DEBUG           <- lambda_diagnostics.py
```

**Change Impact Analysis:**
- LOGGING change affects ALL interfaces
- CACHE change affects HTTP_CLIENT, CONFIG, SECURITY
- WEBSOCKET change only affects Home Assistant extension

---

## PART 4: VALIDATION CHECKLISTS

### When Adding New Dependency
**REF:** NM02-VALIDATION-01
**PRIORITY:** MEDIUM
**TAGS:** validation, checklist, dependencies, review
**KEYWORDS:** add dependency, validate dependency, dependency checklist
**RELATED:** NM07-DT-03, NM07-DT-13

Before adding `from gateway import X` to a file, verify:

```
[ ] Is X in a different interface? -> MUST use gateway
[ ] Is X in the same interface? -> Direct import preferred
[ ] Does X create a circular dependency? -> Check hierarchy
[ ] Is X in a lower layer than current interface? -> OK
[ ] Is X in a higher layer? -> RECONSIDER DESIGN
[ ] Is X actually needed? -> Could we restructure?
[ ] Does X have good test coverage? -> Verify first
```

---

### Checking for Circular Dependencies
**REF:** NM02-VALIDATION-02
**PRIORITY:** MEDIUM
**TAGS:** validation, circular-imports, checking, prevention
**KEYWORDS:** check circular imports, validate dependencies, circular check
**RELATED:** NM02-PREVENT-01, NM06-BUG-02

```
Step 1: Identify the two interfaces
  A wants to use B, B wants to use A

Step 2: Check dependency hierarchy
  Which layer is each interface in?

Step 3: Apply SIMA rules
  - Both use gateway? -> No circular import possible
  - Direct import? -> Circular import likely

Step 4: Verify import statements
  Search each file for "from <interface>_core import"
  Search each file for "from interface_<n> import"
```

---

### Red Flags (Circular Import Warning Signs)
**REF:** NM02-VALIDATION-03
**PRIORITY:** HIGH
**TAGS:** warnings, red-flags, circular-imports, detection
**KEYWORDS:** circular import warnings, red flags, import violations
**RELATED:** NM05-AP-01, NM05-AP-04

```
RED FLAG: Direct import from another interface's core file
RED FLAG: Interface router importing another interface router
RED FLAG: Two interfaces in same layer importing each other
RED FLAG: Import like "from <different_interface>_core import"
RED FLAG: Higher layer trying to import lower layer via gateway
```

---

## PART 5: DEPENDENCY DIAGRAMS

### ASCII Dependency Graph (Bottom-Up)
**REF:** NM02-DIAGRAM-01
**PRIORITY:** MEDIUM
**TAGS:** visualization, diagram, dependencies, layers
**KEYWORDS:** dependency diagram, layer visualization, ASCII diagram
**RELATED:** NM02-DEP-01, NM02-DEP-05

```
                    +----------+
                    |  DEBUG   | (Layer 4 - Tests everything)
                    +----+-----+
                         |
        +----------------+----------------+
        |                |                |
   +----+----+      +----+----+      +----+-----+
   |  INIT   |      |  HTTP   |      | WEBSOCKET| (Layer 3)
   +---------+      | CLIENT  |      +----------+
                    +----+----+
                         |
        +----------------+----------------+
        |                |                |
   +----+----+      +----+----+      +----+----+
   | CACHE   |      | METRICS |      | CONFIG  | (Layer 2)
   +---------+      +---------+      +---------+
        |                |                |
        +----------------+----------------+
                         |
                    +----+----+
                    | LOGGING | (Layer 0 - Foundation)
                    +---------+
```

**Legend:**
- Lines show dependency relationships
- Higher layers depend on lower layers
- All connections via gateway.py (not shown)

---

## INTEGRATION NOTES

### Cross-Reference with Other Neural Maps

**NM01 (Architecture):**
- Gateway pattern (ARCH-01, ARCH-02) enables these import rules
- All interfaces have documented signatures

**NM02-CORE-Dependencies.md (Companion File):**
- Dependency layers (DEP-01 to DEP-05) define legal dependencies
- Detailed dependency analysis for CACHE, HTTP, CONFIG

**NM04 (Decisions):**
- NM04-DEC-01: Why SIMA pattern chosen
- NM04-DEC-06: Why flat file structure

**NM05 (Anti-Patterns):**
- NM05-AP-01: Direct cross-interface imports
- NM05-AP-04: Circular import attempts

**NM06 (Learned Experiences):**
- NM06-BUG-02: Circular import that led to PREVENT-01
- NM06-LESS-01: Gateway pattern prevents problems

---

## END NOTES

**Key Takeaways:**
1. Cross-interface imports MUST use gateway.py
2. Intra-interface imports are direct
3. Gateway prevents circular imports via mediation
4. Dependency matrices show impact of changes
5. Validation checklists prevent architectural violations

**File Statistics:**
- Total REF IDs: 13 (RULE-01 to 05, PREVENT-01, MATRIX-01/02, VALIDATION-01 to 03, DIAGRAM-01, MECHANISM-01)
- Total lines: ~400
- Priority: 5 CRITICAL, 4 HIGH, 4 MEDIUM

**Related Files:**
- NM02-INDEX-Dependencies.md (Router to this file)
- NM02-CORE-Dependencies.md (Dependency layers)
- NM01-INDEX-Architecture.md (Architecture patterns)

# EOF

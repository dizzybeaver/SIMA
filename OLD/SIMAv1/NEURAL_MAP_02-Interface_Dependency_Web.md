# NEURAL_MAP_02: Interface Dependency Web
# SUGA-ISP Neural Memory System - Relationships & Dependencies
# Version: 1.1.0 | Phase: 1 Foundation | Enhanced with REF IDs

---

**FILE STATISTICS:**
- Sections: 15 (5 dependency layers + 5 import rules + 5 detailed dependencies)
- Reference IDs: 15
- Cross-references: 40+
- Priority Breakdown: Critical=5, High=7, Medium=3
- Last Updated: 2025-10-20
- Version: 1.1.0 (Enhanced with REF IDs)

---

## Purpose

This file documents HOW THINGS CONNECT in the SUGA-ISP architecture - dependency relationships, import rules, circular import prevention, and the interconnected web of interfaces.

---

## PART 1: DEPENDENCY HIERARCHY (Bottom-Up)

### Layer 0: Base Infrastructure (No Dependencies)
**REF:** NM02-DEP-01
**PRIORITY:** 🔴 CRITICAL
**TAGS:** dependencies, base-layer, LOGGING, zero-dependencies, foundation
**KEYWORDS:** base layer, no dependencies, logging layer, foundation layer
**RELATED:** NM01-INT-02, NM04-DEC-08, NM06-LESS-04

```
LOGGING
├─ Dependencies: None
├─ Purpose: Base logging infrastructure
├─ Used by: All other interfaces
└─ Why base layer: Must not depend on anything to avoid circular imports

Dependency Rule: LOGGING cannot import from any other interface
Rationale: If LOGGING depended on anything, circular imports would be inevitable
```

**REAL-WORLD USAGE:**
User: "Why can't LOGGING use CACHE?"
Claude searches: "base layer dependencies"
Finds: NM02-DEP-01
Response: "LOGGING is Layer 0 with zero dependencies - must not depend on anything to avoid circular imports."

---

### Layer 1: Core Utilities (Depends on LOGGING only)
**REF:** NM02-DEP-02
**PRIORITY:** 🟡 HIGH
**TAGS:** dependencies, layer-1, utilities, core-utilities, SECURITY
**KEYWORDS:** utility layer, security layer, core utilities, layer 1
**RELATED:** NM01-INT-03, NM01-INT-11, NM01-INT-06

```
SECURITY
├─ Dependencies: LOGGING
├─ Purpose: Validation, encryption, sanitization
├─ Used by: HTTP_CLIENT, CONFIG, WEBSOCKET
└─ Why Layer 1: Security is fundamental, needed by many interfaces

UTILITY
├─ Dependencies: None (optionally LOGGING)
├─ Purpose: Helper functions, common utilities
├─ Used by: All interfaces
└─ Why Layer 1: Utilities are foundational helpers

SINGLETON
├─ Dependencies: LOGGING
├─ Purpose: Singleton storage
├─ Used by: Various interfaces for stateful objects
└─ Why Layer 1: Simple storage, minimal dependencies
```

---

### Layer 2: Storage & Monitoring (Depends on Layer 0-1)
**REF:** NM02-DEP-03
**PRIORITY:** 🟡 HIGH
**TAGS:** dependencies, layer-2, storage, monitoring, CACHE, METRICS
**KEYWORDS:** cache layer, metrics layer, storage layer, monitoring
**RELATED:** NM01-INT-01, NM01-INT-04, NM01-INT-05

```
CACHE
├─ Dependencies: LOGGING, METRICS
├─ Purpose: Data caching
├─ Used by: HTTP_CLIENT, CONFIG, SECURITY
└─ Why Layer 2: Needs metrics to track cache performance

METRICS
├─ Dependencies: LOGGING
├─ Purpose: Telemetry and monitoring
├─ Used by: CACHE, HTTP_CLIENT, CIRCUIT_BREAKER, WEBSOCKET
└─ Why Layer 2: Monitors other systems, doesn't provide core functionality

CONFIG
├─ Dependencies: LOGGING, CACHE, SECURITY
├─ Purpose: Configuration management
├─ Used by: All interfaces (for configuration)
└─ Why Layer 2: Uses cache for config storage, security for validation
```

---

### Layer 3: Service Infrastructure (Depends on Layer 0-2)
**REF:** NM02-DEP-04
**PRIORITY:** 🟡 HIGH
**TAGS:** dependencies, layer-3, services, HTTP, websocket, circuit-breaker
**KEYWORDS:** service layer, HTTP layer, websocket layer, circuit breaker
**RELATED:** NM01-INT-08, NM01-INT-09, NM01-INT-10

```
HTTP_CLIENT
├─ Dependencies: LOGGING, SECURITY, CACHE, METRICS
├─ Purpose: HTTP request handling
├─ Used by: homeassistant_extension, external integrations
└─ Why Layer 3: Needs security (validation), cache (responses), metrics (tracking)

WEBSOCKET
├─ Dependencies: LOGGING, SECURITY, METRICS
├─ Purpose: WebSocket connections
├─ Used by: homeassistant_extension (Home Assistant websocket)
└─ Why Layer 3: Similar to HTTP_CLIENT, needs security and monitoring

CIRCUIT_BREAKER
├─ Dependencies: LOGGING, METRICS
├─ Purpose: Failure protection
├─ Used by: HTTP_CLIENT, WEBSOCKET (wraps external calls)
└─ Why Layer 3: Protects service layer, needs metrics for failure tracking
```

---

### Layer 4: Management & Debug (Depends on Layer 0-3)
**REF:** NM02-DEP-05
**PRIORITY:** 🟢 MEDIUM
**TAGS:** dependencies, layer-4, management, debug, initialization
**KEYWORDS:** management layer, debug layer, initialization layer
**RELATED:** NM01-INT-07, NM01-INT-12

```
INITIALIZATION
├─ Dependencies: LOGGING, CONFIG
├─ Purpose: System initialization
├─ Used by: lambda_function.py (on cold start)
└─ Why Layer 4: Coordinates system startup, needs config

DEBUG
├─ Dependencies: LOGGING, All interfaces (for health checks)
├─ Purpose: System diagnostics
├─ Used by: lambda_diagnostics.py, lambda_emergency.py
└─ Why Layer 4: Tests all other interfaces, must be top layer
```

---

## PART 2: IMPORT RULES

### Rule 1: Cross-Interface Imports MUST Use Gateway
**REF:** NM02-RULE-01
**PRIORITY:** 🔴 CRITICAL
**TAGS:** imports, cross-interface, gateway, rules, SUGA
**KEYWORDS:** cross interface imports, gateway requirement, import rules
**RELATED:** NM04-DEC-01, NM05-AP-01, NM07-DT-01

```python
# ✅ CORRECT: Cross-interface via gateway
# In cache_core.py
from gateway import log_info, record_metric

def cache_operation():
    log_info("Cache operation")      # LOGGING via gateway
    record_metric("cache_hit", 1.0)  # METRICS via gateway

# ❌ WRONG: Direct cross-interface import
# In cache_core.py
from logging_core import log_info      # VIOLATION
from metrics_core import record_metric # VIOLATION
```

**REAL-WORLD USAGE:**
User: "Can cache_core.py import logging_core.py directly?"
Claude searches: "cross interface imports gateway"
Finds: NM02-RULE-01
Response: "NO - All cross-interface imports must use gateway to prevent circular dependencies."

---

### Rule 2: Intra-Interface Imports Are Direct
**REF:** NM02-RULE-02
**PRIORITY:** 🔴 CRITICAL
**TAGS:** imports, intra-interface, same-interface, direct-import
**KEYWORDS:** same interface imports, direct imports, intra-interface
**RELATED:** NM01-ARCH-03, NM01-ARCH-04

```python
# ✅ CORRECT: Same interface, direct import
# In cache_core.py
from cache_manager import CacheManager
from cache_operations import validate_key

def perform_operation(key):
    validate_key(key)          # Same interface
    manager = CacheManager()   # Same interface

# ❌ WRONG: Using gateway for same interface (unnecessary)
# In cache_core.py
from gateway import cache_manager_get  # WRONG - same interface
```

---

### Rule 3: External Code Imports Gateway Only
**REF:** NM02-RULE-03
**PRIORITY:** 🔴 CRITICAL
**TAGS:** imports, external-code, lambda, entry-point, gateway-only
**KEYWORDS:** lambda imports, external imports, entry point imports
**RELATED:** NM01-ARCH-06, NM05-AP-05

```python
# ✅ CORRECT: Lambda imports
# In lambda_function.py
from gateway import log_info, cache_get, http_post
from homeassistant_extension import process_alexa_request

# ❌ WRONG: Lambda importing internals
# In lambda_function.py
from interface_cache import execute_cache_operation  # VIOLATION
from cache_core import perform_operation            # VIOLATION
```

---

### Rule 4: Flat File Structure
**REF:** NM02-RULE-04
**PRIORITY:** 🟢 MEDIUM
**TAGS:** file-structure, flat-structure, organization, architecture
**KEYWORDS:** flat structure, file organization, no subdirectories
**RELATED:** NM04-DEC-06, NM01-ARCH-07

```
All files in root directory (except Home Assistant extension)
├─ Rationale: Historical decision, proven to work
├─ Benefit: Simple import paths
├─ Exception: home_assistant/ subdirectory for HA internals
└─ Pattern: interface_<n>.py and <n>_core.py in root
```

---

### Rule 5: Lambda Entry Point Restrictions
**REF:** NM02-RULE-05
**PRIORITY:** 🟡 HIGH
**TAGS:** lambda, entry-point, restrictions, imports, flow
**KEYWORDS:** lambda restrictions, entry point rules, lambda imports
**RELATED:** NM01-ARCH-06, NM05-AP-05

```
lambda_function.py restrictions:
├─ CAN import: gateway, extension facades
├─ CANNOT import: interface routers, core files
├─ Flow: One-way (lambda → gateway → interfaces)
└─ Rationale: Prevents upward dependencies
```

---

## PART 3: CIRCULAR IMPORT PREVENTION

### How SUGA-ISP Prevents Circular Imports
**REF:** NM02-PREVENT-01
**PRIORITY:** 🔴 CRITICAL
**TAGS:** circular-imports, prevention, SUGA, architecture, ISP
**KEYWORDS:** circular import prevention, no circular imports, SUGA prevents
**RELATED:** NM04-DEC-01, NM04-DEC-02, NM06-BUG-02

#### Mechanism 1: Uni-Directional Flow
```
External Code → gateway.py → interface_router.py → internal_core.py
               (one way only - cannot flow back)

Example:
lambda_function.py
    ↓ imports gateway
gateway.py
    ↓ lazy imports interface_cache
interface_cache.py
    ↓ imports cache_core
cache_core.py
    ↓ imports from gateway (for cross-interface)
    ✓ Circle prevented: Uses gateway, not direct import

Why it works: cache_core → gateway is allowed because gateway
              already loaded. The circle is broken.
```

#### Mechanism 2: Interface Isolation
```
Cache Interface Files ──┐
                        ├─► Can only reach each other
HTTP Interface Files ───┤    OR via gateway to other interfaces
                        │
Logging Interface Files─┘    CANNOT reach across directly

Example:
✓ cache_core.py CAN import cache_manager.py (same interface)
✗ cache_core.py CANNOT import logging_core.py (different interface)
✓ cache_core.py CAN import gateway.log_info (via gateway)

Why it works: Direct cross-interface imports are architecturally
              impossible without violating import rules.
```

#### Mechanism 3: Gateway Mediation
```
cache_core.py wants logging:
  cache_core.py → gateway.py → interface_logging.py → logging_core.py
  
NOT:
  cache_core.py → logging_core.py (BLOCKED by architecture)

Why it works: Gateway is already loaded, acts as intermediary.
              No module ever directly imports another interface's internals.
```

---

## PART 4: DEPENDENCY MATRIX

### Who Depends on Who (Detailed)
**REF:** NM02-MATRIX-01
**PRIORITY:** 🟡 HIGH
**TAGS:** dependencies, matrix, relationships, interfaces
**KEYWORDS:** dependency matrix, who depends on who, interface relationships
**RELATED:** NM02-DEP-01, NM02-DEP-02, NM02-DEP-03

```
Interface       → Depends On
────────────────────────────────────────────────────────────
LOGGING         → None (base layer)
SECURITY        → LOGGING
UTILITY         → None (LOGGING optional)
SINGLETON       → LOGGING
METRICS         → LOGGING
CACHE           → LOGGING, METRICS
CONFIG          → LOGGING, CACHE, SECURITY
HTTP_CLIENT     → LOGGING, SECURITY, CACHE, METRICS
WEBSOCKET       → LOGGING, SECURITY, METRICS
CIRCUIT_BREAKER → LOGGING, METRICS
INITIALIZATION  → LOGGING, CONFIG
DEBUG           → LOGGING, All interfaces
```

### Who Uses Who (Inverse Matrix)
**REF:** NM02-MATRIX-02
**PRIORITY:** 🟡 HIGH
**TAGS:** dependencies, inverse-matrix, used-by, interfaces
**KEYWORDS:** inverse matrix, who uses who, used by
**RELATED:** NM02-MATRIX-01

```
Interface       ← Used By
────────────────────────────────────────────────────────────
LOGGING         ← All interfaces (universal)
SECURITY        ← HTTP_CLIENT, CONFIG, WEBSOCKET
UTILITY         ← All interfaces (helpers)
SINGLETON       ← Various interfaces (stateful storage)
METRICS         ← CACHE, HTTP_CLIENT, CIRCUIT_BREAKER, WEBSOCKET
CACHE           ← HTTP_CLIENT, CONFIG, SECURITY
CONFIG          ← All interfaces (configuration)
HTTP_CLIENT     ← homeassistant_extension, external
WEBSOCKET       ← homeassistant_extension
CIRCUIT_BREAKER ← HTTP_CLIENT, WEBSOCKET
INITIALIZATION  ← lambda_function.py
DEBUG           ← lambda_diagnostics.py, lambda_emergency.py
```

---

## PART 5: DETAILED DEPENDENCIES

### CACHE Dependencies (Deep Dive)
**REF:** NM02-CACHE-DEP
**PRIORITY:** 🟡 HIGH
**TAGS:** CACHE, dependencies, detailed, imports
**KEYWORDS:** cache dependencies, cache uses, cache imports
**RELATED:** NM01-INT-01, NM02-DEP-03

```
CACHE depends on:
├─ LOGGING (for error logging)
│   ├─ Used in: interface_cache.py (router layer)
│   ├─ Functions: log_error, log_warning, log_info
│   └─ Purpose: Log cache operations and errors
│
└─ METRICS (for operation tracking)
    ├─ Used in: cache_core.py (implementation layer)
    ├─ Functions: record_metric, increment_counter
    └─ Purpose: Track cache hits, misses, performance

CACHE is used by:
├─ HTTP_CLIENT (response caching)
│   ├─ Purpose: Cache GET responses to reduce API calls
│   └─ Pattern: cache_set(f"http_{url}", response)
│
├─ CONFIG (configuration caching)
│   ├─ Purpose: Cache config values to reduce lookups
│   └─ Pattern: cache_get(f"config_{key}")
│
└─ SECURITY (token caching)
    ├─ Purpose: Cache validated tokens
    └─ Pattern: cache_set(f"token_{token}", validation_result)

Import Pattern:
# In cache_core.py
from gateway import log_error, record_metric  # Cross-interface

# In http_client_core.py
from gateway import cache_set, cache_get  # Uses CACHE
```

---

### HTTP_CLIENT Dependencies (Deep Dive)
**REF:** NM02-HTTP-DEP
**PRIORITY:** 🟡 HIGH
**TAGS:** HTTP_CLIENT, dependencies, detailed, imports
**KEYWORDS:** HTTP dependencies, HTTP uses, HTTP imports
**RELATED:** NM01-INT-08, NM02-DEP-04

```
HTTP_CLIENT depends on:
├─ LOGGING (for request/response logging)
│   ├─ Used in: interface_http.py, http_client_core.py
│   ├─ Functions: log_info, log_error, log_warning
│   └─ Purpose: Log all HTTP operations
│
├─ SECURITY (for request validation)
│   ├─ Used in: http_client_core.py
│   ├─ Functions: validate_url, sanitize_input
│   └─ Purpose: Validate URLs and sanitize data
│
├─ CACHE (for response caching)
│   ├─ Used in: http_client_core.py
│   ├─ Functions: cache_get, cache_set
│   └─ Purpose: Cache GET responses
│
└─ METRICS (for request tracking)
    ├─ Used in: http_client_core.py
    ├─ Functions: record_api_metric
    └─ Purpose: Track request timing and success rates

HTTP_CLIENT is used by:
├─ homeassistant_extension (Home Assistant API calls)
│   └─ Purpose: Communicate with Home Assistant server
│
└─ External integrations (API calls)
    └─ Purpose: Generic HTTP request handling

Import Pattern:
# In http_client_core.py
from gateway import (
    log_info, log_error,      # LOGGING
    validate_url,             # SECURITY
    cache_get, cache_set,     # CACHE
    record_api_metric         # METRICS
)
```

---

### CONFIG Dependencies (Deep Dive)
**REF:** NM02-CONFIG-DEP
**PRIORITY:** 🟡 HIGH
**TAGS:** CONFIG, dependencies, detailed, imports
**KEYWORDS:** config dependencies, config uses, config imports
**RELATED:** NM01-INT-05, NM02-DEP-03

```
CONFIG depends on:
├─ LOGGING (for configuration changes)
│   ├─ Used in: interface_config.py, config_core.py
│   ├─ Functions: log_info, log_warning
│   └─ Purpose: Log config loads, changes, errors
│
├─ CACHE (for configuration caching)
│   ├─ Used in: config_core.py
│   ├─ Functions: cache_get, cache_set
│   └─ Purpose: Cache frequently accessed config values
│
└─ SECURITY (for input validation)
    ├─ Used in: config_core.py
    ├─ Functions: validate_string, sanitize_input
    └─ Purpose: Validate config values

CONFIG is used by:
└─ All interfaces (for configuration)
    └─ Purpose: Retrieve interface-specific configuration

Import Pattern:
# In config_core.py
from gateway import (
    log_info,                 # LOGGING
    cache_get, cache_set,     # CACHE
    validate_string           # SECURITY
)
```

---

## PART 6: DEPENDENCY VALIDATION

### When Adding New Dependency
**REF:** NM02-VALIDATION-01
**PRIORITY:** 🟢 MEDIUM
**TAGS:** validation, checklist, dependencies, review
**KEYWORDS:** add dependency, validate dependency, dependency checklist
**RELATED:** NM07-DT-03, NM07-DT-13

Before adding `from gateway import X` to a file, verify:

```
□ Is X in a different interface? → MUST use gateway
□ Is X in the same interface? → Direct import preferred
□ Does X create a circular dependency? → Check dependency hierarchy
□ Is X in a lower layer than current interface? → OK
□ Is X in a higher layer than current interface? → RECONSIDER DESIGN
```

### Checking for Circular Dependencies
**REF:** NM02-VALIDATION-02
**PRIORITY:** 🟢 MEDIUM
**TAGS:** validation, circular-imports, checking, prevention
**KEYWORDS:** check circular imports, validate dependencies, circular check
**RELATED:** NM02-PREVENT-01, NM06-BUG-02

```
Step 1: Identify the two interfaces
  A wants to use B, B wants to use A

Step 2: Check dependency hierarchy
  Which layer is each interface in?

Step 3: Apply SUGA-ISP rules
  ✓ Both use gateway? → No circular import possible
  ✗ Direct import? → Circular import likely

Step 4: Verify import statements
  Search each file for "from <interface>_core import"
  Search each file for "from interface_<n> import"
```

### Red Flags (Circular Import Warning Signs)
**REF:** NM02-VALIDATION-03
**PRIORITY:** 🟡 HIGH
**TAGS:** warnings, red-flags, circular-imports, detection
**KEYWORDS:** circular import warnings, red flags, import violations
**RELATED:** NM05-AP-01, NM05-AP-04

```
🚩 Direct import from another interface's core file
🚩 Interface router importing another interface router
🚩 Two interfaces in same dependency layer importing each other
🚩 Import statement like "from <different_interface>_core import"
🚩 Higher layer interface trying to import lower layer via gateway
```

---

## PART 7: DEPENDENCY DIAGRAMS

### ASCII Dependency Graph (Bottom-Up)
**REF:** NM02-DIAGRAM-01
**PRIORITY:** 🟢 MEDIUM
**TAGS:** visualization, diagram, dependencies, layers
**KEYWORDS:** dependency diagram, layer visualization, ASCII diagram
**RELATED:** NM02-DEP-01, NM02-DEP-05

```
                    ┌──────────┐
                    │  DEBUG   │ (Layer 4 - Tests everything)
                    └────┬─────┘
                         │
        ┌────────────────┼────────────────┐
        │                │                │
   ┌────┴─────────┐    ┌────┴────┐    ┌─────┴──────┐
   │   INIT   │    │  HTTP   │    │  WEBSOCKET │ (Layer 3 - Services)
   └────┬─────┘    │ CLIENT  │    └─────┬──────┘
        │          └────┬────┘          │
        │               │               │
        │      ┌────────┼───────┬───────┤
        │      │        │       │       │
   ┌────┴──┐ ┌┴────┐ ┌─┴──┐ ┌──┴───┐ ┌┴────────────┐
   │CONFIG │ │CACHE│ │METRICS│ │SECURITY│ │CIRCUIT_BREAKER│ (Layer 2)
   └───┬───┘ └──┬──┘ └───┬──┘ └───┬───┘ └─────────────┘
       │       │        │        │
       │       └────┬───┴────────┤
       │            │            │
       └────────────┼────────────┤
                    │            │
              ┌─────┴──────┐ ┌───┴────┐
              │  LOGGING  │ │ UTILITY│ (Layer 0-1 - Base)
              └───────────┘ └────────┘
```

---

## END NOTES

This Dependency Web file documents how interfaces interconnect - the "circulatory system" of SUGA-ISP. It prevents circular imports by enforcing layered architecture and gateway-mediated communication.

For structural details, see NEURAL_MAP_01. For operation flow, see NEURAL_MAP_03.

---

# EOF

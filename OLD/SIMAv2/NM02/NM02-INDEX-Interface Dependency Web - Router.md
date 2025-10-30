# NM02-INDEX: Interface Dependency Web - Router
# SIMA Architecture Pattern - Dependency Relationships
# Version: 2.0.0 | Phase: 2 SIMA Implementation

---

## Purpose

This INDEX file routes queries about interface dependencies, import rules, and relationship management to the appropriate implementation files. It's the **Interface Layer** of the SIMA pattern for NM02.

**SIMA Architecture for NM02:**
```
Gateway Layer: NM00-Master-Index.md, NM00A-Quick-Index.md
    â†"
Interface Layer: THIS FILE (NM02-INDEX-Dependencies.md) 
    â†"
Implementation Layer: 
    â"œâ"€ NM02-CORE-Dependencies.md (Layers & hierarchies)
    â""â"€ NM02-RULES-Import.md (Rules, validations, matrices)
```

---

## Quick Stats

**Total REF IDs:** 21  
**Files:** 3 (1 INDEX + 2 IMPL)  
**Priority Breakdown:** Critical=5, High=7, Medium=9

---

## PART 1: DISPATCH TABLE

### By REF ID (Complete List)

| REF ID | Topic | File | Priority |
|--------|-------|------|----------|
| **DEP-01** | Layer 0 - Base (LOGGING) | CORE | ðŸ"´ CRITICAL |
| **DEP-02** | Layer 1 - Core Utilities | CORE | ðŸŸ¡ HIGH |
| **DEP-03** | Layer 2 - Storage & Monitoring | CORE | ðŸŸ¡ HIGH |
| **DEP-04** | Layer 3 - Service Infrastructure | CORE | ðŸŸ¡ HIGH |
| **DEP-05** | Layer 4 - Management & Debug | CORE | ðŸŸ¢ MEDIUM |
| **RULE-01** | Cross-interface imports via gateway | RULES | ðŸ"´ CRITICAL |
| **RULE-02** | Intra-interface direct imports | RULES | ðŸ"´ CRITICAL |
| **RULE-03** | External code imports gateway only | RULES | ðŸ"´ CRITICAL |
| **RULE-04** | Flat file structure | RULES | ðŸŸ¢ MEDIUM |
| **RULE-05** | Lambda entry point restrictions | RULES | ðŸŸ¡ HIGH |
| **PREVENT-01** | Gateway prevents circular imports | RULES | ðŸ"´ CRITICAL |
| **MATRIX-01** | Who depends on who (detailed) | RULES | ðŸŸ¡ HIGH |
| **MATRIX-02** | Who uses who (inverse matrix) | RULES | ðŸŸ¡ HIGH |
| **CACHE-DEP** | CACHE dependencies deep dive | CORE | ðŸŸ¡ HIGH |
| **HTTP-DEP** | HTTP_CLIENT dependencies deep dive | CORE | ðŸŸ¡ HIGH |
| **CONFIG-DEP** | CONFIG dependencies deep dive | CORE | ðŸŸ¡ HIGH |
| **VALIDATION-01** | When adding new dependency | RULES | ðŸŸ¢ MEDIUM |
| **VALIDATION-02** | Checking for circular dependencies | RULES | ðŸŸ¢ MEDIUM |
| **VALIDATION-03** | Red flags (circular import warnings) | RULES | ðŸŸ¡ HIGH |
| **DIAGRAM-01** | ASCII dependency graph (bottom-up) | RULES | ðŸŸ¢ MEDIUM |
| **MECHANISM-01** | How gateway prevents circular imports | RULES | ðŸŸ¢ MEDIUM |

---

## PART 2: QUICK REFERENCE BY TOPIC

### Dependency Layers (File: CORE)

**Layer 0 - Base Infrastructure:**
- **DEP-01:** LOGGING (zero dependencies, foundation layer)

**Layer 1 - Core Utilities:**
- **DEP-02:** SECURITY, UTILITY, SINGLETON (depend on LOGGING only)

**Layer 2 - Storage & Monitoring:**
- **DEP-03:** CACHE, METRICS, CONFIG (depend on Layer 0-1)

**Layer 3 - Service Infrastructure:**
- **DEP-04:** HTTP_CLIENT, WEBSOCKET, CIRCUIT_BREAKER (depend on Layer 0-2)

**Layer 4 - Management & Debug:**
- **DEP-05:** INITIALIZATION, DEBUG (depend on Layer 0-3)

---

### Import Rules (File: RULES)

**Critical Import Rules:**
- **RULE-01:** Cross-interface imports MUST use gateway
- **RULE-02:** Intra-interface imports are direct
- **RULE-03:** External code imports gateway only

**Structural Rules:**
- **RULE-04:** Flat file structure (all files in root)
- **RULE-05:** Lambda entry point restrictions

**Circular Import Prevention:**
- **PREVENT-01:** Gateway prevents circular imports
- **MECHANISM-01:** How gateway mediation works

---

### Dependency Matrices (File: RULES)

**Relationship Matrices:**
- **MATRIX-01:** Who depends on who (forward matrix)
- **MATRIX-02:** Who uses who (inverse matrix)

**Detailed Dependencies:**
- **CACHE-DEP:** CACHE uses LOGGING, METRICS
- **HTTP-DEP:** HTTP_CLIENT uses LOGGING, SECURITY, CACHE, METRICS
- **CONFIG-DEP:** CONFIG uses LOGGING, CACHE, SECURITY

---

### Validation & Diagrams (File: RULES)

**Validation Checklists:**
- **VALIDATION-01:** Before adding new dependency
- **VALIDATION-02:** How to check for circular dependencies
- **VALIDATION-03:** Red flags and warning signs

**Visual Aids:**
- **DIAGRAM-01:** ASCII dependency graph (layer visualization)

---

## PART 3: ROUTING BY KEYWORD

Use this section for fast keyword-based routing:

### Dependencies & Layers
- "base layer", "layer 0", "zero dependencies" → **DEP-01** (CORE)
- "layer 1", "core utilities", "security layer" → **DEP-02** (CORE)
- "layer 2", "cache layer", "metrics layer" → **DEP-03** (CORE)
- "layer 3", "HTTP layer", "service layer" → **DEP-04** (CORE)
- "layer 4", "debug layer", "management layer" → **DEP-05** (CORE)

### Import Rules
- "cross interface", "gateway import", "import rules" → **RULE-01** (RULES)
- "direct import", "same interface", "intra-interface" → **RULE-02** (RULES)
- "lambda import", "external code", "entry point" → **RULE-03** (RULES)
- "file structure", "flat structure", "no subdirectories" → **RULE-04** (RULES)
- "lambda restrictions", "entry point rules" → **RULE-05** (RULES)

### Circular Import Prevention
- "circular import", "circular dependency", "import loop" → **PREVENT-01** (RULES)
- "gateway mediation", "how gateway prevents" → **MECHANISM-01** (RULES)

### Matrices & Relationships
- "dependency matrix", "who depends on who" → **MATRIX-01** (RULES)
- "inverse matrix", "who uses who", "used by" → **MATRIX-02** (RULES)

### Detailed Dependencies
- "cache dependencies", "cache uses", "cache imports" → **CACHE-DEP** (CORE)
- "HTTP dependencies", "HTTP uses", "HTTP imports" → **HTTP-DEP** (CORE)
- "config dependencies", "config uses", "config imports" → **CONFIG-DEP** (CORE)

### Validation
- "add dependency", "new dependency", "dependency checklist" → **VALIDATION-01** (RULES)
- "check circular", "validate dependency" → **VALIDATION-02** (RULES)
- "red flags", "warning signs", "circular warnings" → **VALIDATION-03** (RULES)

### Diagrams
- "dependency diagram", "layer diagram", "ASCII graph" → **DIAGRAM-01** (RULES)

---

## PART 4: FILE ACCESS METHODS

### Method 1: Direct Search (Recommended)
```
Search project knowledge for:
"NM02-CORE-Dependencies DEP-01 base layer"
"NM02-RULES-Import RULE-01 cross interface gateway"
"NM02-RULES-Import MATRIX-01 dependency matrix"
```

### Method 2: Via Master Index
```
Search: "NM00-Master-Index dependency web"
Then: Navigate to NM02 section
Then: Find specific REF ID
```

### Method 3: Via Quick Index
```
Search: "NM00A-Quick-Index dependency layer"
Get: Auto-recall context
Then: Reference detailed file if needed
```

---

## PART 5: PRIORITY LEARNING PATH

### ðŸ"´ CRITICAL - Learn These First (5 refs)

1. **RULE-01** (RULES) - Cross-interface imports MUST use gateway
2. **RULE-02** (RULES) - Intra-interface imports are direct
3. **RULE-03** (RULES) - External code imports gateway only
4. **PREVENT-01** (RULES) - Gateway prevents circular imports
5. **DEP-01** (CORE) - Layer 0 base infrastructure (LOGGING)

**Why Critical:** These define the core import architecture and prevent circular dependencies.

---

### ðŸŸ¡ HIGH - Reference Frequently (7 refs)

1. **DEP-02** (CORE) - Layer 1 core utilities
2. **DEP-03** (CORE) - Layer 2 storage & monitoring
3. **DEP-04** (CORE) - Layer 3 service infrastructure
4. **MATRIX-01** (RULES) - Dependency matrix (forward)
5. **MATRIX-02** (RULES) - Dependency matrix (inverse)
6. **CACHE-DEP** (CORE) - CACHE detailed dependencies
7. **HTTP-DEP** (CORE) - HTTP_CLIENT detailed dependencies
8. **VALIDATION-03** (RULES) - Red flags and warnings
9. **RULE-05** (RULES) - Lambda entry point restrictions

**Why High:** Frequently referenced when working with interfaces and dependencies.

---

### ðŸŸ¢ MEDIUM - As Needed (9 refs)

1. **DEP-05** (CORE) - Layer 4 management & debug
2. **RULE-04** (RULES) - Flat file structure
3. **CONFIG-DEP** (CORE) - CONFIG detailed dependencies
4. **VALIDATION-01** (RULES) - When adding new dependency
5. **VALIDATION-02** (RULES) - Checking for circular dependencies
6. **DIAGRAM-01** (RULES) - ASCII dependency graph
7. **MECHANISM-01** (RULES) - Gateway mediation mechanism

**Why Medium:** Important for understanding, but less frequently needed day-to-day.

---

## PART 6: INTEGRATION WITH OTHER NEURAL MAPS

### NM02 Relates To:

**NM01 (Architecture):**
- All interfaces documented in NM01 have dependencies documented here
- NM01-ARCH-01, ARCH-02, ARCH-03 → Explains gateway pattern used by RULE-01

**NM03 (Operations):**
- Dependency layers affect operational flow
- Import rules affect how operations access interfaces

**NM04 (Decisions):**
- NM04-DEC-01 (SIMA pattern) → Why RULE-01 exists
- NM04-DEC-06 (Flat structure) → Why RULE-04 exists

**NM05 (Anti-Patterns):**
- NM05-AP-01 (Direct imports) → Violates RULE-01
- NM05-AP-04 (Circular imports) → Why PREVENT-01 is critical

**NM06 (Learned Experiences):**
- NM06-BUG-02 (Circular import) → Led to PREVENT-01
- NM06-LESS-04 (No dependencies for base) → Led to DEP-01 design

**NM07 (Decision Logic):**
- NM07-DT-01 (How to import X) → Uses RULE-01, RULE-02, RULE-03

---

## PART 7: USAGE EXAMPLES

### Example 1: User Asks About Import Rules
```
User: "Can cache_core.py import logging_core.py directly?"

Claude Action:
1. Search project knowledge: "NM02-RULES RULE-01 cross interface"
2. Find: RULE-01 in NM02-RULES-Import.md
3. Response: "NO - Cross-interface imports must use gateway.py"
```

---

### Example 2: User Asks About Dependency Layer
```
User: "What layer is HTTP_CLIENT in?"

Claude Action:
1. Search project knowledge: "NM02-CORE DEP HTTP_CLIENT layer"
2. Find: DEP-04 in NM02-CORE-Dependencies.md
3. Response: "HTTP_CLIENT is Layer 3 (Service Infrastructure)"
```

---

### Example 3: User Wants Dependency Visualization
```
User: "Show me how the layers connect"

Claude Action:
1. Search project knowledge: "NM02-RULES DIAGRAM-01 dependency graph"
2. Find: DIAGRAM-01 in NM02-RULES-Import.md
3. Response: [Display ASCII dependency diagram]
```

---

### Example 4: User Asks About Circular Imports
```
User: "How does the gateway prevent circular imports?"

Claude Action:
1. Search project knowledge: "NM02-RULES PREVENT-01 circular import"
2. Find: PREVENT-01 and MECHANISM-01 in NM02-RULES-Import.md
3. Response: [Explain gateway mediation mechanism]
```

---

## PART 8: FILE STATISTICS

### NM02-CORE-Dependencies.md
- **Size:** ~350 lines
- **REF IDs:** 8 (DEP-01 to DEP-05, CACHE-DEP, HTTP-DEP, CONFIG-DEP)
- **Topics:** Dependency layers, detailed dependencies
- **Priority:** 5 HIGH, 3 CRITICAL

### NM02-RULES-Import.md
- **Size:** ~400 lines
- **REF IDs:** 13 (RULE-01 to RULE-05, PREVENT-01, MATRIX-01/02, VALIDATION-01 to 03, DIAGRAM-01, MECHANISM-01)
- **Topics:** Import rules, matrices, validation, diagrams
- **Priority:** 5 CRITICAL, 4 HIGH, 4 MEDIUM

### Total NM02 Coverage
- **Total Size:** ~750 lines across 2 implementation files
- **Total REF IDs:** 21
- **Compliance:** Both files under 600-line limit ✅

---

## PART 9: TERMINOLOGY NOTES

**SIMA vs SUGA-ISP:**
- SIMA: Architecture pattern (Gateway → Interface → Implementation)
- SUGA-ISP: Lambda project name using SIMA architecture
- This file uses SIMA when discussing architecture patterns
- Code references remain "SUGA-ISP" (project name)

**Interface vs Implementation:**
- Interface: Router files (interface_*.py)
- Implementation: Core files (*_core.py, *_manager.py, etc.)
- In Neural Maps: "Interface Layer" = INDEX files, "Implementation Layer" = detailed files

---

## END OF INDEX

**Next Steps:**
- Search for NM02-CORE-Dependencies.md for dependency layers
- Search for NM02-RULES-Import.md for import rules and matrices
- Use dispatch table above for specific REF ID routing

**Related Files:**
- NM00-Master-Index.md (Gateway to all Neural Maps)
- NM00A-Quick-Index.md (Quick lookups and auto-recall)
- NM01-INDEX-Architecture.md (Core architecture patterns)

# EOF

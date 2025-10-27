# SIMAv4 Architecture Planning Document (Ultra-Optimized)

**Version:** 4.0.0-ULTRA  
**Date:** 2025-10-27  
**Status:** Planning / Review  
**Purpose:** Zero-duplication, high-density, reference-based neural map system with ZAPH integration

---

## ðŸŽ¯ Executive Summary

**Core Problem Identified:**
- Knowledge duplication across layers wastes space
- Unclear when to create CORE vs LANG vs ARCH vs PROJECT entries
- No clear architecture-specific knowledge organization
- Inefficient access patterns

**Ultra-Optimized Solution:**
- **Reference-Based Architecture** - Entries reference, never duplicate
- **Only-If-Adds-Value Principle** - Create entry only if it contributes unique knowledge
- **Architecture-Specific Maps** - SUGA, LMMS, DD, ZAPH get dedicated entry hierarchies
- **ZAPH-Integrated Access** - Ultra-fast lookup with pre-computed indexes
- **Inheritance System** - PROJECT inherits from ARCH inherits from LANG inherits from CORE

**Knowledge Density Goal:** 0% duplication, 100% unique information per entry

---

## ðŸ§  CORE ARCHITECTURAL PRINCIPLES

### Principle 1: Only-If-Adds-Value (OIAV)

**Rule:** Create an entry at a specific level ONLY if it adds unique information not present at higher levels.

```
Decision Tree for Entry Creation:

Is this knowledge universal (works in any language/architecture/project)?
â"‚
â"œâ"€ YES â†' Create CORE entry (concept only, no implementation)
â"‚
â""â"€ NO â†' Is this language-specific syntax/behavior?
    â"‚
    â"œâ"€ YES â†' Does it differ significantly from obvious translation of CORE?
    â"‚   â"‚
    â"‚   â"œâ"€ YES â†' Create LANG entry (implementation only, reference CORE)
    â"‚   â""â"€ NO â†' Skip LANG entry, just reference CORE from PROJECT
    â"‚
    â""â"€ NO â†' Is this architecture-specific pattern?
        â"‚
        â"œâ"€ YES â†' Create ARCH entry (pattern only, reference CORE/LANG)
        â"‚
        â""â"€ NO â†' Is this project-specific constraint?
            â"‚
            â""â"€ YES â†' Create PROJECT entry (constraints + references)
```

**Example: Caching**

```
CORE-025: Caching Pattern
- Concept: Store computed results for reuse
- Trade-offs: Memory vs computation
- When to use: Expensive operations, repeated access
- NO implementation details (that's for LANG)
- NO architecture specifics (that's for ARCH)
- NO project constraints (that's for PROJECT)

PY-067: Python Caching (ONLY if adds value)
- References: CORE-025
- Python-specific: functools.lru_cache, custom decorators, weakref caches
- Only exists because Python has unique caching approaches
- If Python caching is obvious translation of CORE-025, this entry doesn't exist

SUGA-015: Caching in SUGA Architecture
- References: CORE-025 (concept)
- SUGA-specific: Cache at interface layer, not core
- SUGA-specific: Gateway access pattern
- SUGA-specific: Lazy initialization integration
- Only exists because SUGA has specific layer placement rules

SUGA-ISP-LAM-089: Caching in SUGA-ISP Lambda
- References: CORE-025, PY-067, SUGA-015
- Lambda constraints: 128MB memory limit (affects cache size)
- Lambda constraints: Cold start (affects cache preloading)
- Lambda constraints: Stateless invocations (affects cache persistence)
- Only exists because Lambda adds unique constraints
- Does NOT duplicate concepts from referenced entries
```

**Key Insight:** Each entry contains ONLY the delta (new information) from its references.

---

### Principle 2: Architecture-Specific Maps

**Rule:** Each architecture pattern gets its own complete entry hierarchy.

```
/sima-entries/
  â"‚
  â"œâ"€â"€ core/                           # Universal concepts only
  â"‚   â"œâ"€â"€ patterns/
  â"‚   â"œâ"€â"€ principles/
  â"‚   â""â"€â"€ antipatterns/
  â"‚
  â"œâ"€â"€ architectures/                  # Architecture-specific knowledge
  â"‚   â"‚
  â"‚   â"œâ"€â"€ suga/                       # SUGA architecture entries
  â"‚   â"‚   â"œâ"€â"€ patterns/               # SUGA-001 to SUGA-099
  â"‚   â"‚   â"œâ"€â"€ antipatterns/           # SUGA-AP-001 to SUGA-AP-099
  â"‚   â"‚   â"œâ"€â"€ lessons/                # SUGA-LESS-001 to SUGA-LESS-099
  â"‚   â"‚   â"œâ"€â"€ decisions/              # SUGA-DEC-001 to SUGA-DEC-099
  â"‚   â"‚   â""â"€â"€ bugs/                   # SUGA-BUG-001 to SUGA-BUG-099
  â"‚   â"‚
  â"‚   â"œâ"€â"€ lmms/                       # LMMS architecture entries
  â"‚   â"‚   â"œâ"€â"€ patterns/               # LMMS-001 to LMMS-099
  â"‚   â"‚   â"œâ"€â"€ antipatterns/           # LMMS-AP-001 to LMMS-AP-099
  â"‚   â"‚   â""â"€â"€ lessons/                # LMMS-LESS-001 to LMMS-LESS-099
  â"‚   â"‚
  â"‚   â"œâ"€â"€ dd/                         # Dispatch Dictionary entries
  â"‚   â"‚   â"œâ"€â"€ patterns/               # DD-001 to DD-099
  â"‚   â"‚   â""â"€â"€ antipatterns/           # DD-AP-001 to DD-AP-099
  â"‚   â"‚
  â"‚   â""â"€â"€ zaph/                       # ZAPH architecture entries
  â"‚       â"œâ"€â"€ patterns/               # ZAPH-001 to ZAPH-099
  â"‚       â"œâ"€â"€ antipatterns/           # ZAPH-AP-001 to ZAPH-AP-099
  â"‚       â""â"€â"€ access_optimization/    # ZAPH-specific access patterns
  â"‚
  â"œâ"€â"€ languages/                      # Language implementations
  â"‚   â""â"€â"€ python/
  â"‚       â"œâ"€â"€ implementations/        # Only if non-obvious
  â"‚       â""â"€â"€ libraries/              # Language-specific libraries
  â"‚
  â""â"€â"€ projects/                       # Project constraint combinations
      â""â"€â"€ suga-isp/
          â"œâ"€â"€ aws-lambda/
          â"‚   â"œâ"€â"€ constraints/         # Lambda-specific constraints
          â"‚   â""â"€â"€ combinations/        # How architectures combine in Lambda
          â""â"€â"€ aws-dynamodb/
```

**Why Architecture-Specific Maps?**

1. **SUGA-specific knowledge** (Gateway layer rules, interface patterns, lazy imports) belongs in SUGA entries
2. **LMMS-specific knowledge** (Memory management, preloading, singleton patterns) belongs in LMMS entries
3. **DD-specific knowledge** (Dispatch tables, routing logic) belongs in DD entries
4. **ZAPH-specific knowledge** (Access optimization, indexing) belongs in ZAPH entries

**Architecture Enable/Disable:**

```yaml
# /sima-config/active/projects/SUGA-ISP/SUGA-ISP-ACTIVE-ARCHITECTURES.md

active_architectures:
  suga:
    enabled: true
    version: 3.0
    entry_range: SUGA-001 to SUGA-099
    priority: 1
  
  lmms:
    enabled: true
    version: 2.0
    entry_range: LMMS-001 to LMMS-099
    priority: 2
  
  dd:
    enabled: true
    version: 1.0
    entry_range: DD-001 to DD-099
    priority: 3
  
  zaph:
    enabled: true
    version: 1.0
    entry_range: ZAPH-001 to ZAPH-099
    priority: 4
```

When architecture disabled, its entries are filtered out of search results.

---

### Principle 3: Reference-Based Inheritance

**Rule:** Entries inherit knowledge through references, never duplication.

```
CORE-025: Caching Pattern
Content: [Full concept explanation]
Size: 120 lines

PY-067: Python Caching
Inherits: CORE-025
Content: [Only Python-specific additions]
Size: 40 lines (just the delta)

SUGA-015: Caching in SUGA
Inherits: CORE-025
Content: [Only SUGA-specific additions]
Size: 50 lines (just the delta)

SUGA-ISP-LAM-089: Lambda Caching with SUGA
Inherits: CORE-025, PY-067, SUGA-015
Content: [Only Lambda constraint additions]
Size: 30 lines (just the delta)

Total Knowledge: 240 lines
Without References: Would be 120 + 160 + 170 + 240 = 690 lines
Savings: 450 lines (65% reduction)
```

**Entry Template with References:**

```markdown
---
ref_id: SUGA-ISP-LAM-089
type: project_constraint
version: 1.0.0
inherits:
  - CORE-025  # Universal caching concept
  - PY-067    # Python implementation
  - SUGA-015  # SUGA architecture pattern
status: active
---

# SUGA-ISP-LAM-089: Lambda Caching in SUGA Architecture

## Inherited Knowledge
This entry builds on:
- **CORE-025**: Caching pattern (universal concept)
- **PY-067**: Python caching implementation
- **SUGA-015**: SUGA architecture caching placement

## Lambda-Specific Constraints (NEW)
[Only the delta - what Lambda adds]

1. **Memory Limit (128MB):**
   - Cache size must be monitored
   - Use small cache sizes (< 10MB)
   - Reference: LAM-CONST-001

2. **Cold Start Implications:**
   - Cache initialized on cold start
   - Use lazy population
   - Reference: LAM-PERF-015

3. **Stateless Invocations:**
   - Cache doesn't persist between invocations
   - Use external cache (Redis) for persistence
   - Reference: LAM-PATTERN-023

## Lambda-Specific Anti-Patterns (NEW)
- âŒ Large in-memory caches (> 50MB)
- âŒ Expecting cache to persist across invocations
- âŒ Pre-populating cache in global scope (slow cold starts)

## Cross-References
- Inherited concepts: CORE-025, PY-067, SUGA-015
- Lambda constraints: LAM-CONST-001, LAM-PERF-015
- Alternative patterns: LAM-PATTERN-023 (external cache)
```

**Key Insight:** This entry is 30 lines, not 240 lines, because it only contains the Lambda-specific delta.

---

## ðŸ"Š ULTRA-OPTIMIZED STRUCTURE

### Tier 1: Configuration Layer (Unchanged)

```
/sima-config/
  â"œâ"€â"€ SIMA-MAIN-CONFIG.md
  â"œâ"€â"€ templates/
  â"‚   â"œâ"€â"€ projects/
  â"‚   â"œâ"€â"€ architectures/
  â"‚   â""â"€â"€ languages/
  â""â"€â"€ active/
      â"œâ"€â"€ projects/
      â"‚   â""â"€â"€ SUGA-ISP/
      â"‚       â"œâ"€â"€ SUGA-ISP-PROJECT-AWS.md
      â"‚       â"œâ"€â"€ SUGA-ISP-LANG-PYTHON.md
      â"‚       â""â"€â"€ SUGA-ISP-ACTIVE-ARCHITECTURES.md  # NEW
      â"œâ"€â"€ architectures/
      â""â"€â"€ languages/
```

---

### Tier 2: Gateway Layer (Enhanced)

```
/sima-gateways/
  â"œâ"€â"€ GATEWAY-CORE.md              # Universal concepts only
  â"œâ"€â"€ GATEWAY-ARCHITECTURE.md      # Routes to architecture-specific maps
  â"œâ"€â"€ GATEWAY-LANGUAGE.md          # Routes to language implementations
  â"œâ"€â"€ GATEWAY-PROJECT.md           # Routes to project constraints
  â""â"€â"€ GATEWAY-ZAPH.md              # NEW: Ultra-optimized access layer
```

**NEW: GATEWAY-ZAPH.md**

```markdown
# ZAPH Gateway: Ultra-Optimized Access Layer
Version: 1.0.0
Purpose: Fast, indexed, cached access to all neural maps

## ZAPH Architecture
ZAPH = Zero-Allocation Pre-computed Hashing for instant lookups

## Pre-Computed Indexes

### 1. REF-ID to Entry Map
Fast O(1) lookup by REF-ID
```
CORE-025 â†' /sima-entries/core/patterns/CORE-025-caching-pattern.md
SUGA-015 â†' /sima-entries/architectures/suga/patterns/SUGA-015-caching.md
```

### 2. Keyword to REF-ID Map
Fast keyword search
```
"caching" â†' [CORE-025, PY-067, SUGA-015, LAM-089]
"threading" â†' [CORE-078, PY-045, SUGA-AP-008] (with constraint warnings)
```

### 3. Reference Graph
Pre-computed inheritance chains
```
SUGA-ISP-LAM-089 inherits:
  â†' CORE-025
  â†' PY-067 (which inherits CORE-025)
  â†' SUGA-015 (which inherits CORE-025)
```

### 4. Constraint Matrix
Pre-computed applicability
```
Query: "threading"
Active Project: SUGA-ISP (Lambda)
Active Architectures: [SUGA, LMMS, DD, ZAPH]

Result:
- CORE-078: Threading concept âœ… (universal)
- PY-045: Python threading âœ… (implementation)
- SUGA-AP-008: âŒ Threading in SUGA (anti-pattern)
- LAM-CONST-004: âŒ Threading in Lambda (constraint violation)

Verdict: âŒ Not applicable (Lambda constraint)
Alternative: CORE-085 (Async/await concept), PY-089 (Python asyncio)
```

## ZAPH Optimization Levels

### Level 1: Index Lookup (< 10ms)
- REF-ID direct lookup
- Keyword to REF-ID mapping
- Cached in memory

### Level 2: Reference Resolution (< 50ms)
- Resolve inheritance chain
- Fetch all referenced entries
- Cached after first access

### Level 3: Constraint Filtering (< 100ms)
- Apply active project constraints
- Apply active architecture constraints
- Filter inapplicable entries

### Level 4: Full Content Load (< 200ms)
- Fetch entry content
- Resolve cross-references
- Build complete knowledge graph
```

---

### Tier 3: Interface Layer (Reorganized)

```
/sima-interfaces/
  â"œâ"€â"€ core/
  â"‚   â"œâ"€â"€ INT-CORE-PATTERNS.md          # Universal patterns index
  â"‚   â"œâ"€â"€ INT-CORE-PRINCIPLES.md        # Universal principles index
  â"‚   â""â"€â"€ INT-CORE-ANTIPATTERNS.md      # Universal anti-patterns index
  â"‚
  â"œâ"€â"€ architectures/
  â"‚   â"œâ"€â"€ INT-SUGA.md                    # SUGA architecture index
  â"‚   â"‚   (Points to: SUGA-001 to SUGA-099, SUGA-AP-001 to SUGA-AP-099, etc.)
  â"‚   â"œâ"€â"€ INT-LMMS.md                    # LMMS architecture index
  â"‚   â"œâ"€â"€ INT-DD.md                      # DD architecture index
  â"‚   â""â"€â"€ INT-ZAPH.md                    # ZAPH architecture index
  â"‚
  â"œâ"€â"€ languages/
  â"‚   â""â"€â"€ python/
  â"‚       â"œâ"€â"€ INT-PY-IMPLEMENTATIONS.md  # Only non-obvious implementations
  â"‚       â""â"€â"€ INT-PY-LIBRARIES.md        # Python-specific libraries
  â"‚
  â""â"€â"€ projects/
      â""â"€â"€ suga-isp/
          â"œâ"€â"€ INT-SUGA-ISP-LAMBDA.md     # Lambda constraint combinations
          â""â"€â"€ INT-SUGA-ISP-DYNAMODB.md   # DynamoDB constraint combinations
```

**Example: INT-SUGA.md**

```markdown
# Interface Index: SUGA Architecture
Version: 1.0.0
Gateway: GATEWAY-ARCHITECTURE.md
Enabled: Based on SUGA-ISP-ACTIVE-ARCHITECTURES.md

## Scope
SUGA (Single Universal Gateway Architecture) specific patterns, anti-patterns, lessons, and decisions.

## Entry Categories

### Patterns (SUGA-001 to SUGA-099)
- SUGA-001: Gateway Layer Pattern
- SUGA-002: Interface Layer Pattern
- SUGA-003: Core Layer Pattern
- SUGA-004: Lazy Import Pattern
- SUGA-005: Function-Level Import Pattern
- ...
- SUGA-015: Caching in SUGA Architecture
- ...

### Anti-Patterns (SUGA-AP-001 to SUGA-AP-099)
- SUGA-AP-001: Direct Core Imports (violates gateway pattern)
- SUGA-AP-002: Module-Level Imports (violates lazy loading)
- SUGA-AP-003: Cross-Layer Dependencies (violates hierarchy)
- ...
- SUGA-AP-008: Threading in SUGA (violates single-threaded assumption)
- ...

### Lessons Learned (SUGA-LESS-001 to SUGA-LESS-099)
- SUGA-LESS-001: Gateway wrappers reduce coupling
- SUGA-LESS-002: Lazy imports improve cold start
- SUGA-LESS-003: Interface layer enables testing
- ...

### Design Decisions (SUGA-DEC-001 to SUGA-DEC-099)
- SUGA-DEC-001: Why three layers (not two or four)
- SUGA-DEC-002: Why function-level imports
- SUGA-DEC-003: Why no subdirectories in src/
- ...

### Bugs (SUGA-BUG-001 to SUGA-BUG-099)
- SUGA-BUG-001: Sentinel object leak (cross-layer bug)
- SUGA-BUG-002: Import cycle in gateway
- ...

## Cross-References
- Universal Concepts: GATEWAY-CORE.md
- Language Implementation: GATEWAY-LANGUAGE.md (Python)
- Project Constraints: GATEWAY-PROJECT.md (SUGA-ISP)
```

---

### Tier 4: Individual Layer (Reorganized)

```
/sima-entries/
  â"‚
  â"œâ"€â"€ core/                               # Universal concepts ONLY
  â"‚   â"œâ"€â"€ patterns/
  â"‚   â"‚   â"œâ"€â"€ CORE-025-caching-pattern.md
  â"‚   â"‚   â"œâ"€â"€ CORE-078-threading-concept.md
  â"‚   â"‚   â""â"€â"€ CORE-085-async-io-concept.md
  â"‚   â"‚
  â"‚   â"œâ"€â"€ principles/
  â"‚   â"‚   â"œâ"€â"€ CORE-101-solid-principles.md
  â"‚   â"‚   â""â"€â"€ CORE-102-dry-principle.md
  â"‚   â"‚
  â"‚   â""â"€â"€ antipatterns/
  â"‚       â"œâ"€â"€ CORE-AP-001-god-object.md
  â"‚       â""â"€â"€ CORE-AP-002-spaghetti-code.md
  â"‚
  â"œâ"€â"€ architectures/                      # Architecture-specific entries
  â"‚   â"‚
  â"‚   â"œâ"€â"€ suga/                           # SUGA architecture entries
  â"‚   â"‚   â"œâ"€â"€ patterns/
  â"‚   â"‚   â"‚   â"œâ"€â"€ SUGA-001-gateway-layer.md
  â"‚   â"‚   â"‚   â"œâ"€â"€ SUGA-002-interface-layer.md
  â"‚   â"‚   â"‚   â"œâ"€â"€ SUGA-003-core-layer.md
  â"‚   â"‚   â"‚   â"œâ"€â"€ SUGA-004-lazy-import-pattern.md
  â"‚   â"‚   â"‚   â"œâ"€â"€ SUGA-015-caching-in-suga.md
  â"‚   â"‚   â"‚   â""â"€â"€ ...
  â"‚   â"‚   â"‚
  â"‚   â"‚   â"œâ"€â"€ antipatterns/
  â"‚   â"‚   â"‚   â"œâ"€â"€ SUGA-AP-001-direct-core-imports.md
  â"‚   â"‚   â"‚   â"œâ"€â"€ SUGA-AP-002-module-level-imports.md
  â"‚   â"‚   â"‚   â"œâ"€â"€ SUGA-AP-008-threading-in-suga.md
  â"‚   â"‚   â"‚   â""â"€â"€ ...
  â"‚   â"‚   â"‚
  â"‚   â"‚   â"œâ"€â"€ lessons/
  â"‚   â"‚   â"‚   â"œâ"€â"€ SUGA-LESS-001-gateway-wrappers-reduce-coupling.md
  â"‚   â"‚   â"‚   â"œâ"€â"€ SUGA-LESS-002-lazy-imports-improve-cold-start.md
  â"‚   â"‚   â"‚   â""â"€â"€ ...
  â"‚   â"‚   â"‚
  â"‚   â"‚   â"œâ"€â"€ decisions/
  â"‚   â"‚   â"‚   â"œâ"€â"€ SUGA-DEC-001-why-three-layers.md
  â"‚   â"‚   â"‚   â"œâ"€â"€ SUGA-DEC-002-why-function-level-imports.md
  â"‚   â"‚   â"‚   â""â"€â"€ ...
  â"‚   â"‚   â"‚
  â"‚   â"‚   â""â"€â"€ bugs/
  â"‚   â"‚       â"œâ"€â"€ SUGA-BUG-001-sentinel-object-leak.md
  â"‚   â"‚       â""â"€â"€ ...
  â"‚   â"‚
  â"‚   â"œâ"€â"€ lmms/                           # LMMS architecture entries
  â"‚   â"‚   â"œâ"€â"€ patterns/
  â"‚   â"‚   â"‚   â"œâ"€â"€ LMMS-001-singleton-memory-manager.md
  â"‚   â"‚   â"‚   â"œâ"€â"€ LMMS-002-preload-optimization.md
  â"‚   â"‚   â"‚   â""â"€â"€ ...
  â"‚   â"‚   â"‚
  â"‚   â"‚   â"œâ"€â"€ antipatterns/
  â"‚   â"‚   â"‚   â"œâ"€â"€ LMMS-AP-001-multiple-singletons.md
  â"‚   â"‚   â"‚   â""â"€â"€ ...
  â"‚   â"‚   â"‚
  â"‚   â"‚   â""â"€â"€ lessons/
  â"‚   â"‚       â"œâ"€â"€ LMMS-LESS-001-preload-reduces-cold-start.md
  â"‚   â"‚       â""â"€â"€ ...
  â"‚   â"‚
  â"‚   â"œâ"€â"€ dd/                             # Dispatch Dictionary entries
  â"‚   â"‚   â"œâ"€â"€ patterns/
  â"‚   â"‚   â"‚   â"œâ"€â"€ DD-001-dispatch-table.md
  â"‚   â"‚   â"‚   â""â"€â"€ ...
  â"‚   â"‚   â"‚
  â"‚   â"‚   â""â"€â"€ antipatterns/
  â"‚   â"‚       â""â"€â"€ ...
  â"‚   â"‚
  â"‚   â""â"€â"€ zaph/                           # ZAPH architecture entries
  â"‚       â"œâ"€â"€ patterns/
  â"‚       â"‚   â"œâ"€â"€ ZAPH-001-index-precomputation.md
  â"‚       â"‚   â"œâ"€â"€ ZAPH-002-reference-graph.md
  â"‚       â"‚   â"œâ"€â"€ ZAPH-003-constraint-matrix.md
  â"‚       â"‚   â""â"€â"€ ...
  â"‚       â"‚
  â"‚       â"œâ"€â"€ access_optimization/
  â"‚       â"‚   â"œâ"€â"€ ZAPH-050-o1-lookup.md
  â"‚       â"‚   â"œâ"€â"€ ZAPH-051-cached-resolution.md
  â"‚       â"‚   â""â"€â"€ ...
  â"‚       â"‚
  â"‚       â""â"€â"€ antipatterns/
  â"‚           â""â"€â"€ ...
  â"‚
  â"œâ"€â"€ languages/                          # Language implementations
  â"‚   â""â"€â"€ python/
  â"‚       â"œâ"€â"€ implementations/            # ONLY non-obvious
  â"‚       â"‚   â"œâ"€â"€ PY-067-caching-implementation.md  # Only if adds value
  â"‚       â"‚   â"œâ"€â"€ PY-089-asyncio-implementation.md
  â"‚       â"‚   â""â"€â"€ ...
  â"‚       â"‚
  â"‚       â""â"€â"€ libraries/
  â"‚           â"œâ"€â"€ PY-LIB-001-functools.md
  â"‚           â"œâ"€â"€ PY-LIB-002-itertools.md
  â"‚           â""â"€â"€ ...
  â"‚
  â""â"€â"€ projects/                           # Project constraint combinations
      â""â"€â"€ suga-isp/
          â"œâ"€â"€ aws-lambda/
          â"‚   â"œâ"€â"€ constraints/
          â"‚   â"‚   â"œâ"€â"€ LAM-CONST-001-memory-limit.md
          â"‚   â"‚   â"œâ"€â"€ LAM-CONST-002-timeout-limit.md
          â"‚   â"‚   â"œâ"€â"€ LAM-CONST-003-cold-start.md
          â"‚   â"‚   â"œâ"€â"€ LAM-CONST-004-single-threaded.md
          â"‚   â"‚   â""â"€â"€ ...
          â"‚   â"‚
          â"‚   â""â"€â"€ combinations/           # How architectures combine
          â"‚       â"œâ"€â"€ SUGA-ISP-LAM-089-caching-with-suga.md
          â"‚       â"œâ"€â"€ SUGA-ISP-LAM-090-lmms-in-lambda.md
          â"‚       â""â"€â"€ ...
          â"‚
          â""â"€â"€ aws-dynamodb/
              â"œâ"€â"€ constraints/
              â""â"€â"€ combinations/
```

---

## ðŸŽ¯ ENTRY CATEGORIZATION RULES

### When to Create CORE Entry
âœ… Create if:
- Concept is universal (works in any language/architecture/project)
- No language-specific syntax required to explain
- No architecture-specific patterns involved
- No project constraints affect concept

❌ Don't create if:
- Concept only exists in specific language (e.g., Python decorators)
- Concept only exists in specific architecture (e.g., SUGA gateway layer)
- Concept is just combination of other concepts

**Example: Threading**
- âœ… CORE-078: Threading Concept (universal idea of concurrent execution)
- âŒ CORE-079: Python Threading (wrong - this is LANG-specific)

---

### When to Create LANG Entry
âœ… Create if:
- Implementation differs significantly from obvious CORE translation
- Language has unique features/libraries for this concept
- Syntax is non-trivial

❌ Don't create if:
- Implementation is obvious translation of CORE concept
- Just syntax differences (document in CORE example instead)

**Example: Caching**
- âœ… PY-067: Python Caching (has functools.lru_cache, unique decorators)
- âŒ JS-045: JavaScript Caching (if it's just objects/maps - too obvious)

---

### When to Create ARCH Entry
âœ… Create if:
- Pattern/lesson/decision is specific to this architecture
- Anti-pattern exists because of architecture constraints
- Wouldn't apply to projects not using this architecture

❌ Don't create if:
- Concept is universal (belongs in CORE)
- Constraint is project-specific, not architecture-specific

**Example: Direct Core Imports**
- âœ… SUGA-AP-001: Direct Core Imports (SUGA-specific anti-pattern)
- âŒ CORE-AP-015: Direct Core Imports (wrong - this is SUGA-specific)

**Example: Threading**
- âŒ SUGA-AP-008: Threading in SUGA (wrong - not architecture reason)
- âœ… LAM-CONST-004: Threading in Lambda (correct - Lambda constraint, not SUGA)

---

### When to Create PROJECT Entry
âœ… Create if:
- Combines multiple references with project constraints
- Project-specific combination of architecture + language + constraints
- Unique constraint not found in architecture/language

❌ Don't create if:
- Just references without adding new constraints
- Constraint is actually architecture-specific (belongs in ARCH)

**Example: Caching**
- âœ… SUGA-ISP-LAM-089: Lambda Caching with SUGA (combines CORE-025 + PY-067 + SUGA-015 + Lambda constraints)
- âŒ SUGA-ISP-LAM-090: Caching (wrong - if no unique constraints, just reference existing)

---

## ðŸ"„ KNOWLEDGE FLOW ARCHITECTURE

### Query Resolution with ZAPH

```
User Query: "Can I use threading in my Lambda function?"

Step 1: ZAPH Index Lookup (< 10ms)
Keyword "threading" â†'
  - CORE-078: Threading Concept
  - PY-045: Python Threading
  - SUGA-AP-008: Threading in SUGA (anti-pattern check)
  - LAM-CONST-004: Lambda Threading Constraint

Step 2: Load Active Project Config (< 5ms)
Project: SUGA-ISP
Runtime: AWS Lambda
Active Architectures: [SUGA, LMMS, DD, ZAPH]

Step 3: Apply Constraint Matrix (< 20ms)
LAM-CONST-004: âŒ Single-threaded runtime
  â†' Threading not applicable

Step 4: Find Alternatives (< 30ms)
Search alternatives for "threading" under "Lambda"
  â†' CORE-085: Async I/O Concept
  â†' PY-089: Python asyncio Implementation
  â†' SUGA-025: Async Patterns in SUGA

Step 5: Construct Response (< 50ms)
âŒ No - Lambda is single-threaded (LAM-CONST-004)

âœ… Use async/await instead:
- CORE-085: Async I/O Concept
- PY-089: Python asyncio Implementation  
- SUGA-025: Async Patterns in SUGA Architecture

Total Time: < 115ms
```

---

### Reference Resolution with Inheritance

```
User Query: "Show me caching pattern for Lambda with SUGA"

Step 1: ZAPH Index Lookup
"caching" + "lambda" + "suga" â†'
  â†' SUGA-ISP-LAM-089: Lambda Caching with SUGA

Step 2: Resolve Inheritance Chain (< 50ms)
SUGA-ISP-LAM-089 inherits:
  â"œâ"€ CORE-025: Caching Pattern (universal concept)
  â"œâ"€ PY-067: Python Caching (if exists and adds value)
  â""â"€ SUGA-015: Caching in SUGA Architecture

Step 3: Fetch All Referenced Entries (< 100ms)
Load:
  - CORE-025 content
  - PY-067 content (if exists)
  - SUGA-015 content
  - SUGA-ISP-LAM-089 content (just the delta)

Step 4: Construct Complete Knowledge (< 50ms)
Merge:
  1. Universal concept (CORE-025)
  2. Python implementation (PY-067, if exists)
  3. SUGA architecture pattern (SUGA-015)
  4. Lambda constraints (SUGA-ISP-LAM-089 delta)

Total Knowledge: Complete caching pattern
Total Time: < 200ms
Duplication: 0%
```

---

## ðŸ"Š KNOWLEDGE DENSITY METRICS

### Measuring Success

```yaml
# /sima-tools/knowledge-density-metrics.md

metrics:
  duplication_rate:
    target: 0%
    measurement: |
      Count duplicate paragraphs across entries
      Duplication Rate = (Duplicate Lines / Total Lines) * 100
  
  reference_coverage:
    target: 100%
    measurement: |
      Every non-CORE entry must reference higher-level entries
      Coverage = (Entries with References / Non-CORE Entries) * 100
  
  entry_size_delta:
    target: < 50 lines for PROJECT entries
    measurement: |
      PROJECT entries should be small (just constraints)
      Avg Delta = Avg(PROJECT Entry Size)
  
  search_time:
    target: < 200ms
    measurement: |
      Time from query to complete knowledge graph
      Includes: lookup + resolution + fetch
  
  constraint_accuracy:
    target: 100%
    measurement: |
      Never suggest patterns violating active constraints
      Accuracy = (Correct Suggestions / Total Suggestions) * 100
```

### Example Metrics Report

```
SIMAv4 Knowledge Density Report
Generated: 2025-10-27

Duplication Rate: 2% (target: 0%)
  - 3 entries have duplicate content
  - Action: Refactor to references

Reference Coverage: 95% (target: 100%)
  - 15 PROJECT entries missing references
  - Action: Add inherits: field

Entry Size Delta:
  - CORE entries: avg 120 lines
  - LANG entries: avg 45 lines (62% reduction)
  - ARCH entries: avg 55 lines (54% reduction)
  - PROJECT entries: avg 35 lines (71% reduction)
  - âœ… TARGET MET

Search Time:
  - Index lookup: avg 8ms
  - Reference resolution: avg 42ms
  - Content fetch: avg 95ms
  - Total: avg 145ms
  - âœ… TARGET MET

Constraint Accuracy: 98% (target: 100%)
  - 2 suggestions violated Lambda constraints
  - Action: Update constraint matrix
```

---

## ðŸ"§ ZAPH IMPLEMENTATION DETAILS

### Pre-Computed Index Files

```
/sima-zaph/
  â"œâ"€â"€ indexes/
  â"‚   â"œâ"€â"€ ref-id-to-entry.json          # O(1) lookup by REF-ID
  â"‚   â"œâ"€â"€ keyword-to-refs.json          # Keyword search
  â"‚   â"œâ"€â"€ reference-graph.json          # Inheritance chains
  â"‚   â""â"€â"€ constraint-matrix.json        # Applicability matrix
  â"‚
  â"œâ"€â"€ cache/
  â"‚   â"œâ"€â"€ frequently-accessed.json      # Hot entries (cache in memory)
  â"‚   â""â"€â"€ resolved-chains.json          # Pre-resolved inheritance chains
  â"‚
  â""â"€â"€ tools/
      â"œâ"€â"€ rebuild-indexes.py            # Regenerate all indexes
      â"œâ"€â"€ validate-references.py        # Check reference integrity
      â""â"€â"€ measure-density.py            # Calculate duplication metrics
```

### Index Rebuild Trigger

Rebuild indexes when:
1. New entry created
2. Entry modified
3. Reference added/removed
4. Active project configuration changed
5. Architecture enabled/disabled

### Index Structure Examples

**ref-id-to-entry.json:**
```json
{
  "CORE-025": {
    "path": "/sima-entries/core/patterns/CORE-025-caching-pattern.md",
    "type": "core_pattern",
    "keywords": ["caching", "cache", "memoization", "performance"],
    "size": 120,
    "last_updated": "2025-10-15"
  },
  "SUGA-015": {
    "path": "/sima-entries/architectures/suga/patterns/SUGA-015-caching-in-suga.md",
    "type": "arch_pattern",
    "architecture": "suga",
    "inherits": ["CORE-025"],
    "keywords": ["caching", "suga", "interface", "gateway"],
    "size": 50,
    "last_updated": "2025-10-20"
  }
}
```

**keyword-to-refs.json:**
```json
{
  "caching": [
    {"ref_id": "CORE-025", "priority": 1, "type": "concept"},
    {"ref_id": "PY-067", "priority": 2, "type": "implementation"},
    {"ref_id": "SUGA-015", "priority": 2, "type": "architecture"},
    {"ref_id": "SUGA-ISP-LAM-089", "priority": 3, "type": "project"}
  ],
  "threading": [
    {"ref_id": "CORE-078", "priority": 1, "type": "concept"},
    {"ref_id": "PY-045", "priority": 2, "type": "implementation"},
    {"ref_id": "LAM-CONST-004", "priority": 3, "type": "constraint", "verdict": "not_applicable"}
  ]
}
```

**reference-graph.json:**
```json
{
  "SUGA-ISP-LAM-089": {
    "direct_inherits": ["CORE-025", "PY-067", "SUGA-015"],
    "transitive_inherits": {
      "CORE-025": [],
      "PY-067": ["CORE-025"],
      "SUGA-015": ["CORE-025"]
    },
    "resolution_order": ["CORE-025", "PY-067", "SUGA-015", "SUGA-ISP-LAM-089"],
    "total_knowledge_size": 245
  }
}
```

**constraint-matrix.json:**
```json
{
  "projects": {
    "SUGA-ISP": {
      "runtime": "aws-lambda",
      "architectures": ["suga", "lmms", "dd", "zaph"],
      "language": "python",
      "constraints": {
        "threading": {
          "applicable": false,
          "reason": "LAM-CONST-004: Single-threaded runtime",
          "alternatives": ["CORE-085", "PY-089"]
        },
        "caching": {
          "applicable": true,
          "constraints": ["LAM-CONST-001: 128MB limit"],
          "patterns": ["SUGA-ISP-LAM-089"]
        }
      }
    }
  }
}
```

---

## ðŸ"„ MIGRATION FROM SIMA V3

### Phase 1: Categorize Existing Entries (Week 1-2)

**Decision Tree for Each NM04-* Entry:**

```
For each existing entry (NM04-001 to NM04-NNN):

1. Is this truly universal?
   YES â†' Move to /sima-entries/core/
   NO â†' Continue to step 2

2. Is this SUGA-specific?
   YES â†' Move to /sima-entries/architectures/suga/
   NO â†' Continue to step 3

3. Is this LMMS-specific?
   YES â†' Move to /sima-entries/architectures/lmms/
   NO â†' Continue to step 4

4. Is this DD-specific?
   YES â†' Move to /sima-entries/architectures/dd/
   NO â†' Continue to step 5

5. Is this Lambda-specific (not architecture)?
   YES â†' Move to /sima-entries/projects/suga-isp/aws-lambda/constraints/
   NO â†' Continue to step 6

6. Is this Python-specific AND non-obvious?
   YES â†' Move to /sima-entries/languages/python/implementations/
   NO â†' Mark for deletion or merge
```

**Example Categorization:**

```
NM04-012 (Gateway Pattern)
  â†' Is universal? NO (specific to SUGA)
  â†' Is SUGA-specific? YES
  â†' Move to: /sima-entries/architectures/suga/patterns/SUGA-001-gateway-layer.md

NM04-045 (Lazy Loading)
  â†' Is universal? YES (concept applies everywhere)
  â†' Move to: /sima-entries/core/patterns/CORE-034-lazy-initialization.md

NM04-089 (Sentinel Object Leak)
  â†' Is universal? NO
  â†' Is SUGA-specific? YES (cross-layer bug)
  â†' Move to: /sima-entries/architectures/suga/bugs/SUGA-BUG-001-sentinel-object-leak.md

NM06-DEC-04 (No Threading in Lambda)
  â†' Is universal? NO
  â†' Is SUGA-specific? NO
  â†' Is Lambda-specific? YES (runtime constraint)
  â†' Move to: /sima-entries/projects/suga-isp/aws-lambda/constraints/LAM-CONST-004-single-threaded.md
```

---

### Phase 2: Add References to Reduce Duplication (Week 3-4)

**For each categorized entry:**

```
1. Identify what knowledge is universal
   â†' Extract to CORE entry (if not exists)
   â†' Add reference in current entry

2. Identify what knowledge is language-specific
   â†' Extract to LANG entry (if adds value)
   â†' Add reference in current entry

3. Identify what knowledge is architecture-specific
   â†' Keep in ARCH entry
   â†' Add references to CORE/LANG

4. Rewrite entry to contain only delta
   â†' Remove duplicated content
   â†' Replace with references
   â†' Keep only new information
```

**Example Refactoring:**

**Before (NM04-089, 180 lines):**
```markdown
# Caching in Lambda

## What is Caching?
Caching stores computed results for reuse... [40 lines]

## Python Caching
Python provides functools.lru_cache... [50 lines]

## SUGA Caching
In SUGA architecture, cache at interface layer... [40 lines]

## Lambda Constraints
Lambda has 128MB memory limit... [50 lines]
```

**After (SUGA-ISP-LAM-089, 50 lines):**
```markdown
---
ref_id: SUGA-ISP-LAM-089
inherits:
  - CORE-025  # Universal caching concept
  - PY-067    # Python implementation
  - SUGA-015  # SUGA architecture pattern
---

# SUGA-ISP-LAM-089: Lambda Caching with SUGA

## Inherited Knowledge
- CORE-025: Universal caching concept
- PY-067: Python caching with functools
- SUGA-015: Caching at interface layer in SUGA

## Lambda-Specific Constraints (NEW)
[Only 50 lines of Lambda-specific content]
```

**Duplication Reduction:** 180 lines â†' 50 lines delta (72% reduction)

---

### Phase 3: Build ZAPH Indexes (Week 5)

```
1. Run /sima-zaph/tools/rebuild-indexes.py
2. Generate all 4 index files
3. Validate reference integrity
4. Test search performance
5. Benchmark against targets
```

---

### Phase 4: Validation (Week 6-7)

```
1. Run knowledge density metrics
2. Check duplication rate (target: 0%)
3. Check reference coverage (target: 100%)
4. Test query resolution times (target: < 200ms)
5. Validate constraint matrix accuracy
6. Test with real queries from support tickets
```

---

### Phase 5: Rollout (Week 8)

```
1. Update Custom Instructions to reference SIMAv4
2. Update SESSION-START to use ZAPH gateway
3. Train team on new structure
4. Monitor first 100 queries
5. Collect feedback and iterate
```

---

## âœ… SUCCESS CRITERIA

### Technical Metrics
- âœ… Duplication rate: < 2% (target: 0%)
- âœ… Reference coverage: 100%
- âœ… Search time: < 200ms average
- âœ… Constraint accuracy: 100%
- âœ… Entry size delta: PROJECT entries < 50 lines average

### Usability Metrics
- âœ… Clear categorization (no ambiguous entries)
- âœ… Architecture enable/disable works
- âœ… Reference resolution transparent to user
- âœ… Faster query responses than v3
- âœ… No duplicate content served

### Scalability Metrics
- âœ… Easy to add new architecture
- âœ… Easy to add new language
- âœ… Easy to add new project
- âœ… Index rebuild < 5 seconds
- âœ… Supports 1000+ entries efficiently

---

## ðŸ"š APPENDIX A: REF-ID Naming Conventions

```
CORE-{NNN}              # Core patterns/principles (001-999)
CORE-AP-{NNN}           # Core anti-patterns (001-999)

SUGA-{NNN}              # SUGA patterns (001-999)
SUGA-AP-{NNN}           # SUGA anti-patterns (001-999)
SUGA-LESS-{NNN}         # SUGA lessons (001-999)
SUGA-DEC-{NNN}          # SUGA decisions (001-999)
SUGA-BUG-{NNN}          # SUGA bugs (001-999)

LMMS-{NNN}              # LMMS patterns (001-999)
LMMS-AP-{NNN}           # LMMS anti-patterns (001-999)
LMMS-LESS-{NNN}         # LMMS lessons (001-999)

DD-{NNN}                # DD patterns (001-999)
DD-AP-{NNN}             # DD anti-patterns (001-999)

ZAPH-{NNN}              # ZAPH patterns (001-999)
ZAPH-AP-{NNN}           # ZAPH anti-patterns (001-999)

PY-{NNN}                # Python implementations (001-999)
PY-LIB-{NNN}            # Python libraries (001-999)

LAM-CONST-{NNN}         # Lambda constraints (001-999)
DDB-CONST-{NNN}         # DynamoDB constraints (001-999)

{PROJECT}-LAM-{NNN}     # Project-specific Lambda patterns (001-999)
{PROJECT}-DDB-{NNN}     # Project-specific DynamoDB patterns (001-999)
```

---

## ðŸ"š APPENDIX B: Entry Content Guidelines

### CORE Entry Template
```markdown
---
ref_id: CORE-{NNN}
type: core_pattern | core_principle | core_antipattern
version: 1.0.0
keywords: [keyword1, keyword2, ...]
status: active
---

# CORE-{NNN}: {Title}

## Universal Concept
[Language-agnostic explanation]

## When to Use
[Universal use cases]

## When NOT to Use
[Universal anti-patterns]

## Trade-offs
[Universal trade-offs]

## Example (Pseudocode)
[Language-agnostic pseudocode]

## Cross-References
- Related concepts: [REF-IDs]
- Implementations: [LANG REF-IDs]
- Architecture usage: [ARCH REF-IDs]

## REF-ID
CORE-{NNN}
```

### ARCH Entry Template
```markdown
---
ref_id: {ARCH}-{NNN}
type: arch_pattern | arch_antipattern | arch_lesson | arch_decision | arch_bug
architecture: suga | lmms | dd | zaph
version: 1.0.0
inherits: [CORE-REF-IDs]
keywords: [keyword1, keyword2, ...]
status: active
---

# {ARCH}-{NNN}: {Title}

## Inherited Knowledge
This entry builds on:
- {CORE-REF}: {Brief description}

## Architecture-Specific Pattern (NEW)
[Only the delta - what this architecture adds]

## Why Architecture-Specific
[Explain why this doesn't belong in CORE]

## Architecture Constraints
[Constraints specific to this architecture]

## Cross-References
- Universal concepts: [CORE REF-IDs]
- Implementations: [LANG REF-IDs]
- Project usage: [PROJECT REF-IDs]

## REF-ID
{ARCH}-{NNN}
```

### PROJECT Entry Template
```markdown
---
ref_id: {PROJECT}-{SERVICE}-{NNN}
type: project_constraint | project_combination
project: SUGA-ISP
runtime: aws-lambda | aws-dynamodb | ...
version: 1.0.0
inherits: [CORE, LANG, ARCH REF-IDs]
keywords: [keyword1, keyword2, ...]
status: active
---

# {PROJECT}-{SERVICE}-{NNN}: {Title}

## Inherited Knowledge
This entry combines:
- {CORE-REF}: {Description}
- {LANG-REF}: {Description}
- {ARCH-REF}: {Description}

## Project-Specific Constraints (NEW)
[Only the constraints - what this project/runtime adds]

## Constraint Impact
[How constraints affect inherited patterns]

## Alternatives Under Constraints
[What to do instead if constraint violated]

## Cross-References
- Universal concepts: [CORE REF-IDs]
- Architecture patterns: [ARCH REF-IDs]
- Related constraints: [Other PROJECT REF-IDs]

## REF-ID
{PROJECT}-{SERVICE}-{NNN}
```

---

**END OF SIMAv4 ARCHITECTURE (ULTRA-OPTIMIZED)**

**Version:** 4.0.0-ULTRA  
**Status:** Planning / Review  

**Key Innovations:**
1. **Zero Duplication** - Reference-based architecture
2. **Only-If-Adds-Value** - Clear entry creation rules
3. **Architecture-Specific Maps** - SUGA, LMMS, DD, ZAPH get dedicated hierarchies
4. **ZAPH Integration** - Ultra-fast O(1) lookups with pre-computed indexes
5. **Knowledge Density** - Average 65% size reduction through references

**Next Actions:**
1. Review and approve architecture
2. Categorize existing v3 entries
3. Build ZAPH indexes
4. Validate with metrics
5. Rollout

**Estimated Knowledge Density:** 0-2% duplication (vs 40-60% in typical documentation)

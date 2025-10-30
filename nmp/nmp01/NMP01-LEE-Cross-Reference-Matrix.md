# File: NMP01-LEE-Cross-Reference-Matrix.md

# NMP01-LEE Cross-Reference Matrix

**Project:** Lambda Execution Engine (SUGA-ISP)  
**Project Code:** LEE  
**Version:** 1.0.0  
**Created:** 2025-10-29  
**Last Updated:** 2025-10-29

---

## Overview

This matrix shows relationships between NMP01-LEE project entries and base SIMA knowledge (NM##, AWS##). Use this to understand how project-specific implementations relate to generic patterns.

---

## NMP01-LEE Entries Overview

| Entry | Title | Category | Priority | Status |
|-------|-------|----------|----------|--------|
| NMP01-LEE-02 | INT-01 CACHE Function Catalog | Interface | 🔴 CRITICAL | âœ… Complete |
| NMP01-LEE-03 | INT-02 LOGGING Function Catalog | Interface | 🔴 CRITICAL | âœ… Complete |
| NMP01-LEE-04 | INT-03 SECURITY Function Catalog | Interface | 🟡 HIGH | âœ… Complete |
| NMP01-LEE-14 | Gateway Core - execute_operation() | Gateway | 🔴 CRITICAL | âœ… Complete |
| NMP01-LEE-16 | Fast Path Optimization - ZAPH | Gateway | 🟡 HIGH | âœ… Complete |
| NMP01-LEE-17 | HA Core - API Integration | HA Integration | 🔴 CRITICAL | âœ… Complete |
| NMP01-LEE-23 | Circuit Breaker - Resilience | Specialized | 🟢 MEDIUM | âœ… Complete |

---

## Cross-References to Base SIMA (NM##)

### Architecture Patterns

**NMP01-LEE-14 (Gateway Core) References:**
- ARCH-01: Gateway Trinity (generic pattern)
- ARCH-07: LMMS (lazy loading)
- DEC-01: SUGA pattern decision
- AP-01: Direct imports anti-pattern

**NMP01-LEE-16 (Fast Path) References:**
- ARCH-07: LMMS (lazy loading complement)
- ARCH-08: ZAPH pattern (generic)

---

### Interface Patterns

**NMP01-LEE-02 (CACHE) References:**
- INT-01: Generic CACHE interface pattern
- LESS-08: ISP and cache separation
- NMP01-LEE-01: Application vs infrastructure cache
- BUG-01: Sentinel leak bug
- AP-19: Sentinel leakage anti-pattern

**NMP01-LEE-03 (LOGGING) References:**
- INT-02: Generic LOGGING interface pattern
- ARCH-07: LMMS logging during startup
- DEC-09: JSON structured logging
- DEC-10: CloudWatch integration

**NMP01-LEE-04 (SECURITY) References:**
- INT-03: Generic SECURITY pattern
- BUG-01: Sentinel leak (fixed by is_sentinel)
- LESS-08: ISP and security separation

---

### Specialized Patterns

**NMP01-LEE-17 (HA Core) References:**
- INT-01: CACHE interface (used by ha_core)
- INT-08: HTTP_CLIENT interface (used by ha_core)
- NMP01-LEE-01: Application vs infrastructure separation
- NMP01-LEE-02: Cache function catalog
- NMP01-LEE-18: HA Alexa integration (planned)
- NMP01-LEE-20: HA WebSocket (planned)

**NMP01-LEE-23 (Circuit Breaker) References:**
- INT-10: Circuit Breaker interface pattern (generic)
- ARCH-09: Resilience patterns (generic)
- NMP01-LEE-17: HA API integration (uses circuit breaker)
- NMP01-LEE-18: Alexa integration (uses circuit breaker, planned)

---

## Dependency Relationships

### Interface Dependencies (Project-Specific)

```
NMP01-LEE-03 (LOGGING)
  â"œâ"€ Layer 0 (foundation)
  â""â"€ No dependencies

NMP01-LEE-04 (SECURITY)
  â"œâ"€ Layer 1
  â""â"€ Depends on: LOGGING

NMP01-LEE-02 (CACHE)
  â"œâ"€ Layer 2
  â""â"€ Depends on: LOGGING, SECURITY

NMP01-LEE-17 (HA Core)
  â"œâ"€ Application layer
  â""â"€ Depends on: CACHE, HTTP_CLIENT, SECURITY, LOGGING
```

### Gateway Dependencies

```
NMP01-LEE-14 (Gateway Core)
  â"œâ"€ Central dispatch
  â""â"€ Imports all interfaces (lazy)

NMP01-LEE-16 (Fast Path)
  â"œâ"€ Preloads Tier 1 + Tier 2
  â""â"€ Depends on: Gateway Core
```

---

## Usage Patterns by Component

### Home Assistant Integration

**Core API Integration (NMP01-LEE-17):**
- Uses: NMP01-LEE-02 (CACHE)
- Uses: NMP01-LEE-03 (LOGGING)
- Uses: NMP01-LEE-04 (SECURITY)
- Uses: INT-08 (HTTP_CLIENT - generic)
- Protected by: NMP01-LEE-23 (Circuit Breaker)

**Alexa Integration (NMP01-LEE-18, planned):**
- Uses: NMP01-LEE-17 (HA Core)
- Uses: NMP01-LEE-02 (CACHE)
- Uses: NMP01-LEE-03 (LOGGING)
- Protected by: NMP01-LEE-23 (Circuit Breaker)

---

### Gateway System

**Core Dispatch (NMP01-LEE-14):**
- Implements: ARCH-01 (Gateway Trinity)
- Implements: ARCH-07 (LMMS lazy loading)
- Used by: All gateway wrapper functions (100+)

**Fast Path Optimization (NMP01-LEE-16):**
- Implements: ARCH-08 (ZAPH pattern)
- Preloads: NMP01-LEE-03 (LOGGING)
- Preloads: NMP01-LEE-04 (SECURITY)
- Preloads: NMP01-LEE-02 (CACHE) if budget allows
- Complements: NMP01-LEE-14 (lazy loading)

---

### Resilience System

**Circuit Breaker (NMP01-LEE-23):**
- Implements: INT-10 (generic pattern)
- Protects: NMP01-LEE-17 (HA API calls)
- Protects: Alexa API calls (planned)
- Uses: NMP01-LEE-03 (LOGGING)
- Uses: NMP01-LEE-02 (CACHE) for fallback

---

## Implementation Tiers

### Tier 1: Foundation (Always Required)

1. **NMP01-LEE-03 (LOGGING)** - Layer 0, no dependencies
2. **NMP01-LEE-04 (SECURITY)** - Layer 1, basic protection
3. **NMP01-LEE-14 (Gateway Core)** - Central dispatch

**Implementation order:** 1 → 2 → 3  
**Duration:** 4-6 hours  
**Validates:** Basic SUGA pattern working

---

### Tier 2: Core Services (Required for HA)

4. **NMP01-LEE-02 (CACHE)** - Layer 2, performance
5. **NMP01-LEE-17 (HA Core)** - Application layer
6. **NMP01-LEE-16 (Fast Path)** - Cold start optimization

**Implementation order:** 4 → 5 → 6  
**Duration:** 6-8 hours  
**Validates:** HA integration working, cold start < 3s

---

### Tier 3: Production Readiness (Optional but Recommended)

7. **NMP01-LEE-23 (Circuit Breaker)** - Resilience
8. Interface catalogs (remaining INT-04 through INT-12)
9. Additional HA integration (Alexa, WebSocket, Config)

**Implementation order:** 7 → 8 → 9  
**Duration:** 8-12 hours  
**Validates:** Production-ready with fault tolerance

---

## Knowledge Reuse Patterns

### Pattern 1: Generic → Project

**Flow:** Learn generic pattern → Apply to project

**Example:**
```
1. Read INT-01 (generic CACHE pattern)
2. Understand: Key operations, TTL, layer dependencies
3. Read NMP01-LEE-02 (LEE CACHE implementation)
4. Apply: HA entity caching, domain caching, config caching
```

---

### Pattern 2: Project → Generic → Other Projects

**Flow:** Extract project lesson → Genericize → Reuse elsewhere

**Example:**
```
1. Discover in LEE: HA cache functions belong in ha_core, not INT-01
2. Extract to: LESS-08 (ISP principle)
3. Document project: NMP01-LEE-01 (application vs infrastructure)
4. Apply to other projects: Keep application logic separate
```

---

### Pattern 3: Architecture → Implementation

**Flow:** Understand architecture → See implementation → Adapt

**Example:**
```
1. Read ARCH-01 (Gateway Trinity pattern)
2. Read NMP01-LEE-14 (execute_operation implementation)
3. Understand: Lazy import, dispatch, error handling
4. Adapt: Apply same pattern to new project
```

---

## Quick Navigation

### For New Developers

**Start Here:**
1. NMP01-LEE-14 (Gateway Core) - Understand central dispatch
2. NMP01-LEE-03 (LOGGING) - Foundation interface
3. NMP01-LEE-02 (CACHE) - Performance interface
4. NMP01-LEE-17 (HA Core) - Application example

**Then Explore:**
- NMP01-LEE-16 (Fast Path) - Cold start optimization
- NMP01-LEE-23 (Circuit Breaker) - Resilience

---

### For Feature Development

**Adding HA Feature:**
1. Check NMP01-LEE-17 (HA Core patterns)
2. Use NMP01-LEE-02 (CACHE) for performance
3. Use NMP01-LEE-23 (Circuit Breaker) for resilience
4. Reference INT-## for generic interface patterns

**Adding New Interface:**
1. Read INT-## (generic pattern)
2. Check existing NMP01-LEE-## catalogs
3. Follow three-layer pattern (Gateway → Interface → Core)
4. Document in new NMP01-LEE-## entry

---

### For Debugging

**Performance Issues:**
1. Check NMP01-LEE-16 (Fast Path) - Cold start
2. Check NMP01-LEE-02 (CACHE) - Cache hit rates
3. Check NMP01-LEE-17 (HA Core) - API latency

**Error Issues:**
1. Check NMP01-LEE-03 (LOGGING) - Error logs
2. Check NMP01-LEE-23 (Circuit Breaker) - Circuit state
3. Check NMP01-LEE-14 (Gateway Core) - Operation dispatch

---

## Related Indexes

**Project Indexes:**
- NMP01-LEE_Index.md (complete project index)
- NMP01-LEE-Quick-Index.md (fast lookup)

**Base SIMA Indexes:**
- NM00A-Master_Index.md (generic patterns)
- AWS00-Master_Index.md (AWS patterns)

---

## Keywords

cross-reference, NMP01, LEE, SUGA-ISP, relationships, dependencies, implementation-tiers, navigation, project-knowledge, base-knowledge

---

**END OF FILE**

# NM02-Dependencies_Index.md

# NM02 - Dependencies Category Index

**Category Number:** NM02
**Topics:** 3
**Individual Files:** 14
**Last Updated:** 2025-10-24

---

## Category Overview

**Purpose:** Documents all dependency relationships in the SUGA architecture: the 5-layer dependency hierarchy, import governance rules, and detailed interface dependency analysis.

**Scope:** Import rules, dependency layers (Layer 0 through Layer 4), interface relationships, circular import prevention, and dependency impact analysis.

**Why Dependencies Matter:**
- Prevent circular imports (architectural integrity)
- Define initialization order (cold start optimization)
- Guide change impact analysis (safety)
- Enforce architectural boundaries (maintainability)

---

## Topics in This Category

### Dependency Layers
- **Description:** The 5-layer hierarchy that organizes interfaces from Layer 0 (LOGGING) to Layer 4 (Management)
- **Items:** 5 layers (DEP-01 to DEP-05)
- **Index:** `NM02-Dependencies-Layers_Index.md`
- **Priority Items:** DEP-01 (Layer 0 - Critical), DEP-03 (Layer 2 - High), DEP-04 (Layer 3 - High)
- **Key Concept:** Lower layers never depend on higher layers

### Import Rules
- **Description:** Import governance rules that prevent circular dependencies and enforce boundaries
- **Items:** 5 rules (RULE-01 to RULE-05)
- **Index:** `NM02-Dependencies-ImportRules_Index.md`
- **Priority Items:** RULE-01 (Cross-interface via gateway - Critical), RULE-02 (Intra-interface direct - Critical), RULE-03 (External gateway only - Critical)
- **Key Concept:** All cross-interface imports must use gateway

### Interface Detail
- **Description:** Deep-dive dependency analysis for specific interfaces
- **Items:** 3 interfaces (CACHE-DEP, HTTP-DEP, CONFIG-DEP)
- **Index:** `NM02-Dependencies-InterfaceDetail_Index.md`
- **Priority Items:** All High priority
- **Key Concept:** Understand what depends on what for change impact

---

## Quick Access

### Most Frequently Accessed

**Critical References:**
1. **RULE-01**: Cross-interface imports via gateway - Used constantly
2. **DEP-01**: Layer 0 (LOGGING) - Foundation for all interfaces
3. **CACHE-DEP**: Cache dependencies - Performance optimization

**Common Queries:**
- "How do I import X?" → RULE-01, RULE-02
- "What layer is X?" → DEP-01 to DEP-05
- "What depends on X?" → Interface Detail files

---

## Dependency Quick Reference

### The 5 Layers
```
Layer 4: Management & Debug
    INITIALIZATION, DEBUG
    ↓
Layer 3: Service Infrastructure
    HTTP_CLIENT, WEBSOCKET, CIRCUIT_BREAKER
    ↓
Layer 2: Storage & Monitoring
    CACHE, METRICS, CONFIG
    ↓
Layer 1: Core Utilities
    SECURITY, UTILITY, SINGLETON
    ↓
Layer 0: Base Infrastructure
    LOGGING
```

### Import Decision Tree
```
Question: Should I import this?

Is it same interface?
  └─ YES → Direct import (RULE-02)
  └─ NO → Continue

Is it different interface?
  └─ YES → Use gateway (RULE-01)
  └─ NO → Continue

Am I in Lambda handler?
  └─ YES → Gateway only (RULE-03, RULE-05)
```

### Critical Interfaces
```
Most Used:
- CONFIG (used by ALL interfaces)
- LOGGING (used by ALL interfaces)
- CACHE (used by HTTP_CLIENT, CONFIG, SECURITY)

Most Dependencies:
- HTTP_CLIENT (depends on 4 interfaces)
- CONFIG (depends on 3 interfaces)
```

---

## Category Statistics

**File Count:**
- Dependency Layers: 6 files (5 layers + 1 index)
- Import Rules: 6 files (5 rules + 1 index)
- Interface Detail: 4 files (3 deep dives + 1 index)
- **Total:** 16 files

**Priority Distribution:**
- 🔴 Critical: 4 items (RULE-01, RULE-02, RULE-03, DEP-01)
- 🟡 High: 8 items (DEP-02, DEP-03, DEP-04, RULE-05, 3 interface details)
- 🟢 Medium: 2 items (DEP-05, RULE-04)

**Coverage:**
- 5 dependency layers documented
- 5 import rules established
- 3 interface deep dives completed
- 100% of critical dependencies covered

---

## Integration with Other Categories

**NM01 (Architecture):**
- Dependency layers inform initialization order (ARCH-02)
- Import rules enforce gateway pattern (ARCH-01)
- Interface definitions reference dependencies (INT-01 to INT-12)

**NM03 (Operations):**
- Dependency layers affect operational flow
- Layer 0 must be operational before others
- Dependencies guide troubleshooting

**NM04 (Decisions):**
- DEC-01: Why SUGA pattern chosen (prevents circular imports)
- DEC-04: No threading (relates to Layer 0)
- DEC-08: Flat structure (relates to RULE-04)

**NM05 (Anti-Patterns):**
- AP-01: Direct cross-interface imports (violates RULE-01)
- AP-04: Circular import attempts (prevented by layers)
- AP-05: External code violations (violates RULE-03)

**NM06 (Lessons):**
- LESS-04: No dependencies for base layer
- BUG-02: Circular import bug (led to current design)
- LESS-01: Gateway pattern prevents problems

---

## Change Impact Guide

**If You're Changing:**
- **Layer 0 (LOGGING)**: Affects ALL interfaces - Critical impact
- **Layer 1**: Affects Layers 2-4 - High impact
- **Layer 2**: Affects Layers 3-4 - Medium-High impact
- **CACHE**: Affects HTTP_CLIENT, CONFIG, SECURITY - Medium impact
- **CONFIG**: Affects ALL interfaces - Critical impact
- **HTTP_CLIENT**: Affects homeassistant_extension - Low-Medium impact

**Before Making Changes:**
1. Check current layer/interface dependencies
2. Identify what depends on the interface
3. Review import rules (RULE-01 to RULE-05)
4. Assess change impact using Interface Detail files
5. Test affected interfaces

---

## Navigation

**Up:** NM00A-Master_Index.md (Gateway layer - system overview)

**Related Categories:**
- NM01 - Architecture (interface definitions)
- NM03 - Operations (operational flows)
- NM04 - Decisions (design rationale)
- NM05 - Anti-Patterns (what NOT to do)

**Topic Indexes:**
- NM02-Dependencies-Layers_Index.md
- NM02-Dependencies-ImportRules_Index.md
- NM02-Dependencies-InterfaceDetail_Index.md

---

## Keywords

dependencies, import rules, dependency layers, circular imports, architectural boundaries, interface relationships, change impact

---

**End of Category Index**

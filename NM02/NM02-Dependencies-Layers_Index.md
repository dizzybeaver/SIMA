# NM02-Dependencies-Layers_Index.md

# Dependencies - Dependency Layers Index

**Category:** NM02 - Dependencies
**Topic:** Dependency Layers
**Items:** 5
**Last Updated:** 2025-10-24

---

## Topic Overview

**Description:** The 5-layer dependency hierarchy that organizes all interfaces from Layer 0 (LOGGING - no dependencies) to Layer 4 (Management & Debug). This structure prevents circular imports and defines initialization order.

**Keywords:** dependency layers, initialization order, circular import prevention, layer hierarchy

---

## Individual Files

### DEP-01: Layer 0 - Base Infrastructure (LOGGING)
- **File:** `NM02-Dependencies-Layers_DEP-01.md`
- **Summary:** LOGGING is Layer 0 with zero dependencies - the foundation all other interfaces build on
- **Priority:** 🔴 Critical

### DEP-02: Layer 1 - Core Utilities
- **File:** `NM02-Dependencies-Layers_DEP-02.md`
- **Summary:** SECURITY, UTILITY, SINGLETON - core utilities that depend only on LOGGING
- **Priority:** 🟡 High

### DEP-03: Layer 2 - Storage & Monitoring
- **File:** `NM02-Dependencies-Layers_DEP-03.md`
- **Summary:** CACHE, METRICS, CONFIG - infrastructure services that enable performance and visibility
- **Priority:** 🟡 High

### DEP-04: Layer 3 - Service Infrastructure
- **File:** `NM02-Dependencies-Layers_DEP-04.md`
- **Summary:** HTTP_CLIENT, WEBSOCKET, CIRCUIT_BREAKER - external communication and reliability
- **Priority:** 🟡 High

### DEP-05: Layer 4 - Management & Debug
- **File:** `NM02-Dependencies-Layers_DEP-05.md`
- **Summary:** INITIALIZATION, DEBUG - system coordination and health checks at the top of the hierarchy
- **Priority:** 🟢 Medium

---

## Layer Quick Reference

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

**Initialization Order:** Layer 0 → Layer 1 → Layer 2 → Layer 3 → Layer 4

**Dependency Rule:** Lower layers never depend on higher layers

---

## Related Topics

- **Import Rules** - How layers access each other (RULE-01)
- **Interface Detail** - Deep dives on specific interfaces (CACHE-DEP, HTTP-DEP, CONFIG-DEP)

---

**Navigation:**
- **Up:** NM02-Dependencies_Index.md (Category Index)
- **Siblings:** NM02-Dependencies-ImportRules_Index.md, NM02-Dependencies-InterfaceDetail_Index.md

---

**End of Index**

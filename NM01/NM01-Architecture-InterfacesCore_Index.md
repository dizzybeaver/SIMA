# NM01-Architecture-InterfacesCore_Index.md

# Architecture - Interfaces-Core Topic Index

**Category:** NM01 - Architecture  
**Topic:** Interfaces-Core  
**Items:** 6  
**Last Updated:** 2025-10-24

---

## Topic Overview

**Description:** The 6 core infrastructure interfaces that form the foundation of SUGA-ISP: CACHE, LOGGING, SECURITY, METRICS, CONFIG, and SINGLETON.

**Keywords:** interfaces, core, infrastructure, CACHE, LOGGING, SECURITY, METRICS, CONFIG, SINGLETON

---

## Individual Files

### INT-01: CACHE Interface
- **File:** `NM01-Architecture-InterfacesCore_INT-01.md`
- **Summary:** In-memory caching with TTL support for fast data storage and retrieval
- **Priority:** 🔴 CRITICAL
- **Operations:** 8 (set, get, delete, clear, has, stats, keys, cleanup)
- **Most Used:** cache_set(), cache_get(), cache_has()

### INT-02: LOGGING Interface
- **File:** `NM01-Architecture-InterfacesCore_INT-02.md`
- **Summary:** Centralized logging with multiple severity levels and structured JSON output
- **Priority:** 🔴 CRITICAL
- **Operations:** 6 (info, warning, error, critical, debug, get_logger)
- **Dependency Layer:** Layer 0 (Base - no dependencies)
- **Most Used:** log_info(), log_error(), log_warning()

### INT-03: SECURITY Interface
- **File:** `NM01-Architecture-InterfacesCore_INT-03.md`
- **Summary:** Input validation, sanitization, and sentinel leak detection
- **Priority:** 🟡 HIGH
- **Operations:** 6 (validate_string, validate_dict, validate_list, is_sentinel, sanitize_for_log, validate_jwt)
- **Critical Feature:** Prevents sentinel objects from leaking (BUG-01)
- **Most Used:** validate_string(), is_sentinel(), sanitize_for_log()

### INT-04: METRICS Interface
- **File:** `NM01-Architecture-InterfacesCore_INT-04.md`
- **Summary:** Performance metrics and counters for system observability
- **Priority:** 🟢 MEDIUM
- **Operations:** 8 (record, increment, get_stats, record_operation, record_error, record_cache, record_api, clear)
- **Metric Types:** Counters, Gauges, Timers
- **Most Used:** increment_counter(), record_operation_metric()

### INT-05: CONFIG Interface
- **File:** `NM01-Architecture-InterfacesCore_INT-05.md`
- **Summary:** Multi-tier configuration management with preset support
- **Priority:** 🟡 HIGH
- **Operations:** 9 (get_parameter, set_parameter, get_category, reload, switch_preset, get_state, load_environment, load_file, validate_all)
- **Resolution Order:** User > Environment > Preset > Default
- **Presets:** minimum (free tier), standard (balanced), maximum (performance)
- **Most Used:** get_config(), set_config(), switch_config_preset()

### INT-06: SINGLETON Interface
- **File:** `NM01-Architecture-InterfacesCore_INT-06.md`
- **Summary:** Singleton object storage with factory pattern for expensive object reuse
- **Priority:** 🟢 MEDIUM
- **Operations:** 5 (get, has, delete, clear, get_stats)
- **Pattern:** Factory pattern for lazy initialization
- **Common Use Cases:** Database connections, HTTP sessions, AWS SDK clients
- **Most Used:** singleton_get(), singleton_has()

---

## Usage Patterns

### Most Frequently Used Interfaces
1. **INT-01 (CACHE)** - Used by almost all interfaces for performance
2. **INT-02 (LOGGING)** - Used by ALL interfaces for observability
3. **INT-05 (CONFIG)** - Used by most interfaces for configuration
4. **INT-03 (SECURITY)** - Used whenever validating external input

### Dependency Relationships
```
Layer 0 (Base):
  └─ INT-02: LOGGING (no dependencies)

Layer 1 (Core Utilities):
  ├─ INT-03: SECURITY (uses LOGGING)
  ├─ INT-04: METRICS (uses LOGGING)
  └─ INT-06: SINGLETON (uses LOGGING)

Layer 2 (Services):
  ├─ INT-01: CACHE (uses LOGGING, SECURITY)
  └─ INT-05: CONFIG (uses LOGGING, CACHE, SECURITY)
```

### Common Combinations
- **CACHE + SECURITY:** Always validate sentinels before returning cached values
- **LOGGING + SECURITY:** Always sanitize data before logging
- **CONFIG + CACHE:** Cache configuration values to reduce lookups
- **METRICS + all interfaces:** Track performance of all operations

---

## Related Topics

- **Core Architecture:** ARCH-01 through ARCH-08 (gateway and LMMS)
- **Interfaces-Advanced:** INT-07 through INT-12 (advanced features)
- **Dependencies:** NM02 (dependency layers and relationships)

---

## Cross-Category Relationships

**Interfaces → Decisions:**
- INT-01 (CACHE) ← DEC-05 (Sentinel sanitization)
- INT-02 (LOGGING) ← DEC-04 (No threading)

**Interfaces → Bugs:**
- INT-01 (CACHE) ← BUG-01 (Sentinel leak)
- INT-03 (SECURITY) → BUG-01 (Fix for sentinel leak)

**Interfaces → Anti-Patterns:**
- All interfaces → AP-01 (No direct cross-interface imports)

---

## Quick Reference

**When to Use Each Interface:**

- **CACHE:** Need to store/retrieve data quickly, reduce redundant operations
- **LOGGING:** Need observability, debugging, or audit trail
- **SECURITY:** Need to validate external input or check for sentinel leaks
- **METRICS:** Need to track performance or count operations
- **CONFIG:** Need configurable behavior or deployment modes
- **SINGLETON:** Need to reuse expensive objects across invocations

---

## Navigation

- **Up:** NM01-Architecture_Index.md (Category Index)
- **Siblings:** 
  - NM01-Architecture-CoreArchitecture_Index.md
  - NM01-Architecture-InterfacesAdvanced_Index.md

---

## Keywords

interfaces, core, infrastructure, CACHE, LOGGING, SECURITY, METRICS, CONFIG, SINGLETON, foundation, base layer

---

## Version History

- **2025-10-24**: Created Topic Index for Interfaces-Core (SIMA v3 migration)

---

**End of Index**

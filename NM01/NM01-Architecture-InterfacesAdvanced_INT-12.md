# NM01-Architecture-InterfacesAdvanced_INT-12.md - INT-12

# INT-12: DEBUG Interface

**Category:** NM01 - Architecture  
**Topic:** Interfaces-Advanced  
**Priority:** 🟢 MEDIUM  
**Status:** Active  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-24

---

## Summary

Debug and diagnostics interface providing system health checks, component inspection, architecture validation, and troubleshooting tools.

---

## Context

The DEBUG interface provides comprehensive debugging and diagnostic capabilities for troubleshooting issues in production and development environments.

**Why it exists:** Lambda debugging is challenging due to limited access. This interface provides built-in diagnostics accessible via API calls.

---

## Content

### Overview

```
Router: interface_debug.py
Core: debug_core.py
Purpose: Debugging and diagnostics
Pattern: Dictionary-based dispatch
State: Diagnostic data collection
Dependency Layer: Layer 4 (Advanced Features)
```

### Operations (5+ total)

```
├─ check_component_health: Check specific component health
├─ check_gateway_health: Check gateway system health
├─ diagnose_system_health: Full system diagnostic
├─ run_debug_tests: Run diagnostic test suite
└─ validate_system_architecture: Validate SUGA architecture integrity
```

### Gateway Wrappers

```python
check_component_health(component: str) -> Dict
check_gateway_health() -> Dict
diagnose_system_health() -> Dict
run_debug_tests() -> Dict
validate_system_architecture() -> Dict
```

### Dependencies

```
Uses: LOGGING, METRICS, CONFIG, CACHE, SINGLETON (inspects all interfaces)
Used by: Diagnostic endpoints, troubleshooting
```

### Usage Example

```python
from gateway import check_gateway_health, diagnose_system_health

# Quick health check
health = check_gateway_health()
if not health['healthy']:
    log_error("System unhealthy", extra=health)

# Full diagnostic
diagnostic = diagnose_system_health()
log_info("System diagnostic", extra=diagnostic)

# Check specific component
cache_health = check_component_health('cache')
```

### Diagnostic Information Provided

```
- System initialization status
- Interface registration status
- Cache statistics and health
- Singleton object inventory
- Configuration state
- Memory usage
- Module load status
- Error counts and patterns
- Circuit breaker states
- Performance metrics
```

---

## Related Topics

- **INT-01** through **INT-11**: All interfaces can be inspected
- **ARCH-01**: Gateway Trinity - Validates proper structure
- **PATH-01**: Cold start sequence - Debugs initialization
- **DEP-05**: Layer 4 (Advanced Features)

---

## Keywords

debug, diagnostics, health check, troubleshooting, system inspection, validation

---

## Version History

- **2025-10-24**: Atomized from monolithic file (SIMA v3 migration)
- **2025-10-20**: Original content in IMPLEMENTATION_Advanced.md

---

**File:** `NM01-Architecture-InterfacesAdvanced_INT-12.md`  
**End of Document**

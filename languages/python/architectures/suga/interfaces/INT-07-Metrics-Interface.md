# INT-07-Metrics-Interface.md

**Version:** 1.0.0  
**Date:** 2025-11-07  
**Purpose:** Metrics tracking through SUGA pattern  
**Architecture:** SUGA (Gateway Pattern)

---

## INT-07: METRICS INTERFACE

**Purpose:** Metrics tracking through SUGA pattern.

### Three-Layer Pattern

```python
# Gateway (gateway_wrappers_metrics.py)
def track_metric(name, value, unit='Count'):
    import interface_metrics
    return interface_metrics.track(name, value, unit)

# Interface (interface_metrics.py)
def track(name, value, unit='Count'):
    import metrics_core
    return metrics_core.track_impl(name, value, unit)

# Core (metrics_core.py)
def track_impl(name, value, unit='Count'):
    """Track metric (print for CloudWatch)."""
    print(f"METRIC: {name}={value} {unit}")
    return True
```

**Keywords:** metrics, monitoring, CloudWatch, tracking, SUGA

---

## COMMON ANTI-PATTERNS (ALL INTERFACES)

### Anti-Pattern 1: Direct Core Import

```python
# WRONG - Any interface
from cache_core import get_impl
from logging_core import log_impl

# CORRECT
import gateway
gateway.cache_get(key)
gateway.log_info(message)
```

### Anti-Pattern 2: Skipping Interface Layer

```python
# WRONG
import cache_core
cache_core.get_impl(key)

# CORRECT
import gateway
gateway.cache_get(key)
```

---

## CROSS-REFERENCES (ALL INTERFACES)

**Architecture:**
- ARCH-01: Gateway Trinity
- ARCH-02: Layer Separation
- ARCH-03: Interface Pattern

**Gateways:**
- GATE-01: Gateway Entry Pattern
- GATE-02: Lazy Import Pattern
- GATE-03: Cross-Interface Communication

**Decisions:**
- DEC-01: SUGA Choice
- DEC-03: Gateway Mandatory

**Anti-Patterns:**
- AP-01: Direct Core Import
- AP-02: Module-Level Heavy Imports
- AP-04: Skipping Interface Layer

**Lessons:**
- LESS-03: Gateway Entry Point
- LESS-04: Layer Responsibility Clarity
- LESS-07: Base Layers No Dependencies

---

**END OF FILE**

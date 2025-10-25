# NM01-Architecture-InterfacesAdvanced_INT-11.md - INT-11

# INT-11: UTILITY Interface

**Category:** NM01 - Architecture  
**Topic:** Interfaces-Advanced  
**Priority:** 🟢 MEDIUM  
**Status:** Active  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-24

---

## Summary

Utility functions interface providing common helper operations for string manipulation, JSON parsing, UUID generation, timestamp handling, and response formatting.

---

## Context

The UTILITY interface provides frequently used helper functions that don't belong to any specific interface, reducing code duplication across the system.

**Why it exists:** Many operations (UUID generation, JSON parsing, response formatting) are used across multiple interfaces. Centralizing them reduces duplication.

---

## Content

### Overview

```
Router: interface_utility.py
Core: utility_core.py
Purpose: Common utility functions
Pattern: Dictionary-based dispatch
State: Stateless
Dependency Layer: Layer 1 (Core Utilities)
```

### Operations (5+ total)

```
├─ format_response: Format standard API response
├─ parse_json: Safe JSON parsing
├─ safe_get: Safe dictionary access
├─ generate_uuid: Generate UUID
└─ get_timestamp: Get current timestamp
```

### Gateway Wrappers

```python
format_response(success: bool, message: str, data: Any = None) -> Dict
parse_json(json_str: str, default: Any = None) -> Any
safe_get(dict_obj: Dict, key: str, default: Any = None) -> Any
generate_uuid() -> str
get_timestamp() -> str
```

### Dependencies

```
Uses: LOGGING
Used by: Most interfaces
```

### Usage Example

```python
from gateway import format_response, generate_uuid, get_timestamp

# Format API response
return format_response(
    success=True,
    message="Operation completed",
    data={'user_id': 123}
)

# Generate correlation ID
correlation_id = generate_uuid()

# Get ISO timestamp
timestamp = get_timestamp()
```

---

## Related Topics

- **INT-02**: LOGGING - Utilities used in logging
- **DEP-02**: Layer 1 (Core Utilities)

---

## Keywords

utility, helpers, UUID, JSON, timestamp, response formatting

---

## Version History

- **2025-10-24**: Atomized from monolithic file (SIMA v3 migration)
- **2025-10-20**: Original content in IMPLEMENTATION_Advanced.md

---

**File:** `NM01-Architecture-InterfacesAdvanced_INT-11.md`  
**End of Document**

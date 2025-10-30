# NM05-AntiPatterns-Import_Index.md

# Anti-Patterns - Import Index

**Category:** NM05 - Anti-Patterns
**Topic:** Import
**Items:** 5
**Last Updated:** 2025-10-23

---

## Topic Overview

**Description:** Import-related anti-patterns that violate the SUGA architecture pattern and create circular dependencies, import errors, or break the gateway-only import rule. These are the most fundamental violations of the project's architectural principles.

**Keywords:** imports, circular dependencies, gateway, SUGA violations, import errors, cross-interface

---

## Individual Files

### AP-01: Direct Cross-Interface Imports
- **File:** `NM05-AntiPatterns-Import_AP-01.md`
- **Summary:** Importing core modules directly instead of via gateway
- **Severity:** 🔴 Critical
- **What to do instead:** Always `import gateway`, use `gateway.operation()`
- **Why critical:** Breaks SUGA pattern, causes circular imports

### AP-02: Importing Interface Routers
- **File:** `NM05-AntiPatterns-Import_AP-02.md`
- **Summary:** Importing interface router files directly
- **Severity:** 🔴 Critical
- **What to do instead:** Use gateway functions, never import routers
- **Why critical:** Violates architecture, creates dependency chaos

### AP-03: Gateway for Same-Interface Operations
- **File:** `NM05-AntiPatterns-Import_AP-03.md`
- **Summary:** Using gateway for operations within same interface
- **Severity:** ⚪ Low
- **What to do instead:** Direct intra-interface calls are allowed
- **Why low:** Works correctly but adds unnecessary overhead

### AP-04: Circular Imports via Gateway
- **File:** `NM05-AntiPatterns-Import_AP-04.md`
- **Summary:** Creating import loops through gateway pattern
- **Severity:** 🔴 Critical
- **What to do instead:** Follow dependency layers (DEP-01 to DEP-08)
- **Why critical:** Makes code unloadable, breaks initialization

### AP-05: Importing from lambda_function
- **File:** `NM05-AntiPatterns-Import_AP-05.md`
- **Summary:** Importing from the Lambda entry point file
- **Severity:** 🔴 Critical
- **What to do instead:** Lambda imports from modules, never reverse
- **Why critical:** Creates circular dependency at system entry

---

## Common Themes

**All import anti-patterns stem from violating RULE-01:**
> "All cross-interface operations MUST go through gateway. No direct imports of core modules."

**The pattern to follow:**
```python
# ✅ CORRECT
import gateway
result = gateway.cache_get(key)
gateway.log_info("message")

# ❌ WRONG
from cache_core import get_value
from logging_core import log_message
```

---

## Related Topics

- **NM04-DEC-01**: SUGA pattern choice (why gateway-only imports)
- **NM04-DEC-02**: Gateway centralization (architectural rationale)
- **NM02-RULE-01**: Import rules (the fundamental rule)
- **NM02-CORE**: Dependency layers (how to structure imports correctly)
- **NM06-BUG-02**: Circular import bug (real-world consequence)

---

## Detection & Prevention

**How to detect import anti-patterns:**
```bash
# Find direct cross-interface imports (AP-01)
grep "from .*_core import" *.py | grep -v "# Intra-interface"

# Find interface router imports (AP-02)
grep "from interface_.* import" *.py

# Find lambda_function imports (AP-05)
grep "from lambda_function import" *.py
```

**Prevention checklist:**
- [ ] All imports go through gateway
- [ ] No direct `*_core` imports across interfaces
- [ ] No `interface_*` router imports
- [ ] Lambda entry point never imported
- [ ] Follow dependency layer rules

---

**Navigation:**
- **Up:** NM05-AntiPatterns_Index.md
- **Sibling Topics:** Concurrency, ErrorHandling, Design

---

**End of Index**

# NM02-Dependencies-ImportRules_Index.md

# Dependencies - Import Rules Index

**Category:** NM02 - Dependencies
**Topic:** Import Rules
**Items:** 5
**Last Updated:** 2025-10-24

---

## Topic Overview

**Description:** Import governance rules that define how files can import from each other. These rules prevent circular dependencies, enforce architectural boundaries, and maintain system integrity.

**Keywords:** import rules, circular import prevention, gateway imports, architectural boundaries

---

## Individual Files

### RULE-01: Cross-Interface Imports MUST Use Gateway
- **File:** `NM02-RULES-Import_RULE-01.md`
- **Summary:** All cross-interface operations must route through gateway.py - never import core modules directly
- **Priority:** 🔴 Critical

### RULE-02: Intra-Interface Imports Are Direct
- **File:** `NM02-Dependencies-ImportRules_RULE-02.md`
- **Summary:** Imports within the same interface should be direct, not through gateway - reduces overhead
- **Priority:** 🔴 Critical

### RULE-03: External Code Imports Gateway Only
- **File:** `NM02-Dependencies-ImportRules_RULE-03.md`
- **Summary:** Lambda entry points and extensions must import only from gateway, never from internals
- **Priority:** 🔴 Critical

### RULE-04: Flat File Structure
- **File:** `NM02-Dependencies-ImportRules_RULE-04.md`
- **Summary:** All core files in root directory (flat structure) except home_assistant/ subdirectory
- **Priority:** 🟢 Medium

### RULE-05: Lambda Entry Point Restrictions
- **File:** `NM02-Dependencies-ImportRules_RULE-05.md`
- **Summary:** Lambda handlers must import only gateway, handle all errors gracefully, return proper format
- **Priority:** 🟡 High

---

## Quick Reference

**Import Decision Tree:**
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
  └─ NO → Use gateway (RULE-01)
```

**Common Patterns:**
```python
# Cross-interface (RULE-01)
from gateway import log_info, cache_get

# Same interface (RULE-02)
from cache_operations import validate_key

# Lambda entry point (RULE-03, RULE-05)
from gateway import log_info, http_post  # ONLY gateway!
```

---

## Related Topics

- **Dependency Layers** - Layer hierarchy that defines legal dependencies (DEP-01 to DEP-05)
- **Interface Detail** - Deep dives on specific interfaces (CACHE-DEP, HTTP-DEP, CONFIG-DEP)
- **Anti-Patterns** - What NOT to do with imports (AP-01, AP-04, AP-05)

---

**Navigation:**
- **Up:** NM02-Dependencies_Index.md (Category Index)
- **Siblings:** NM02-Dependencies-Layers_Index.md, NM02-Dependencies-InterfaceDetail_Index.md

---

**End of Index**

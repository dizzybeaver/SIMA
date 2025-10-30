# File: Import-Index.md

**Category:** Anti-Patterns  
**Topic:** Import  
**Items:** 5  
**Version:** 1.0.0

---

## TOPIC OVERVIEW

Import-related anti-patterns that violate the SUGA architecture pattern and create circular dependencies, import errors, or break the gateway-only import rule. These are the most fundamental violations of the project's architectural principles.

---

## FILES IN THIS CATEGORY

### AP-01: Direct Cross-Interface Imports
- **File:** AP-01.md
- **Severity:** ðŸ"´ Critical
- **Summary:** Importing core modules directly instead of via gateway
- **What to do:** Always `import gateway`, use `gateway.operation()`
- **Why critical:** Breaks SUGA pattern, causes circular imports

### AP-02: Importing Interface Routers
- **File:** AP-02.md
- **Severity:** ðŸ"´ Critical
- **Summary:** Importing interface router files directly
- **What to do:** Use gateway functions, never import routers
- **Why critical:** Violates architecture, creates dependency chaos

### AP-03: Gateway for Same-Interface Operations
- **File:** AP-03.md
- **Severity:** âšª Low
- **Summary:** Using gateway for operations within same interface
- **What to do:** Direct intra-interface calls are allowed
- **Why low:** Works correctly but adds unnecessary overhead

### AP-04: Circular Imports via Gateway
- **File:** AP-04.md
- **Severity:** ðŸ"´ Critical
- **Summary:** Creating import loops through gateway pattern
- **What to do:** Follow dependency layers (DEP-01 to DEP-08)
- **Why critical:** Makes code unloadable, breaks initialization

### AP-05: Importing from lambda_function
- **File:** AP-05.md
- **Severity:** ðŸ"´ Critical
- **Summary:** Importing from the Lambda entry point file
- **What to do:** Lambda imports from modules, never reverse
- **Why critical:** Creates circular dependency at system entry

---

## COMMON THEMES

All import anti-patterns stem from violating the gateway-only import rule. The SUGA pattern exists specifically to prevent circular dependencies and maintain clean architecture.

**Key Principle:** All cross-interface operations MUST go through gateway. No direct imports of core modules.

---

## QUICK REFERENCE

**Critical Rules:**
- âœ… Import gateway only for cross-interface operations
- âœ… Direct imports allowed within same interface
- âŒ Never import from lambda_function.py
- âŒ Never import interface routers directly
- âŒ Never create circular dependencies through gateway

---

## KEYWORDS

imports, circular dependencies, gateway, SUGA violations, import errors, cross-interface, architecture

---

**END OF INDEX**

**Directory:** `/sima/entries/anti-patterns/import/`

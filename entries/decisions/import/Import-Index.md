# Import-Index.md

**Category:** Decision Logic  
**Subcategory:** Import  
**Files:** 2  
**Created:** 2024-10-30  
**Updated:** 2024-10-30

---

## Overview

Import decisions are **CRITICAL** to gateway architecture. Wrong import patterns cause circular dependencies, break interface isolation, and create maintenance nightmares. These decision trees ensure correct import patterns and function placement.

---

## Files in This Category

### DT-01: How to Import Functionality
**REF-ID:** DT-01  
**Priority:** Critical  
**File:** `DT-01.md`

**Summary:**
Decision tree for choosing correct import pattern - gateway imports for cross-interface, direct imports for same-interface.

**Key Questions:**
- Same interface or different interface?
- Standard library or custom code?
- From entry point?

**Use When:**
- Adding any import statement
- Need to use functionality from another module
- Getting import errors

**Common Scenarios:**
- Cache needs logging → Gateway import
- Cache needs cache_operations → Direct import
- Any module needs JSON → Direct import

---

### DT-02: Where Should Function Go
**REF-ID:** DT-02  
**Priority:** High  
**File:** `DT-02.md`

**Summary:**
Decision tree for placing new functions in correct layer and interface - gateway/interface/core selection based on function type.

**Key Questions:**
- Is it a simple wrapper?
- Does it route/dispatch?
- Is it business logic?
- Which interface owns this functionality?

**Use When:**
- Creating new function
- Refactoring existing code
- Organizing functionality

**Common Scenarios:**
- Convenience function → Gateway wrappers
- Route to implementation → Interface router
- Business logic → Core implementation
- Generic helper → Utility interface

---

## Quick Decision Guide

### Import Pattern Quick Reference

```
Same interface          → Direct import
Different interface     → Gateway import
Standard library        → Direct import
Entry point            → Use gateway wrapper
```

### Function Placement Quick Reference

```
Thin wrapper           → gateway_wrappers.py
Routing/dispatch       → interface_<n>.py
Business logic         → <interface>_core.py
Generic utility        → utility_core.py
```

---

## Usage Patterns

### Pattern 1: Adding New Functionality

**Workflow:**
1. Use **DT-02** to determine where function goes
2. Implement function in chosen location
3. Use **DT-01** to add any needed imports
4. Test cross-interface interactions

**Example:**
```
Need: Cache expiration function

DT-02: Business logic? YES → cache_core.py
DT-01: Need logging? YES, different interface → from gateway import log_info

Result: Function in cache_core.py with gateway imports for logging
```

### Pattern 2: Refactoring Imports

**Workflow:**
1. Identify all imports in file
2. For each import, apply **DT-01**
3. Replace incorrect patterns with correct ones
4. Test that functionality still works

**Example:**
```
Current: from logging_core import log_info  # Wrong!

DT-01: Different interface? YES → Gateway import

Fixed: from gateway import log_info  # Correct!
```

### Pattern 3: Growing Interface

**Workflow:**
1. Use **DT-02** to confirm correct interface
2. Add operation to interface router dispatch
3. Implement core logic in core file
4. Add gateway wrapper if needed
5. Use **DT-01** for any cross-interface needs

---

## Related Content

**Architecture Foundations:**
- **ARCH-01:** Gateway pattern explanation
- **ARCH-02:** LMMS pattern
- **ARCH-03:** Dispatch dictionary pattern

**Dependency Rules:**
- **RULE-01:** Gateway-only cross-interface imports
- **RULE-02:** No circular dependencies
- **DEP-01 to DEP-05:** Dependency layer hierarchy

**Anti-Patterns to Avoid:**
- **AP-01:** Direct cross-interface imports
- **AP-02:** Importing interface routers
- **AP-04:** Circular imports via gateway
- **AP-05:** Importing from entry point
- **AP-06:** God objects
- **AP-07:** Large modules (>400 lines)

**Related Decisions:**
- **DEC-01:** Why gateway pattern chosen
- **DEC-02:** Centralized import management
- **DEC-04:** No threading locks

**Other Decision Trees:**
- **DT-03:** User wants feature (next step after placement)
- **DT-13:** Should I create new interface?

---

## Common Mistakes

### Mistake 1: Direct Cross-Interface Import

**❌ Wrong:**
```python
# In cache_core.py
from logging_core import log_info
```

**✅ Correct:**
```python
# In cache_core.py
from gateway import log_info
```

**Fix:** Use **DT-01** → Different interface → Gateway import

### Mistake 2: Business Logic in Gateway

**❌ Wrong:**
```python
# In gateway_wrappers.py
def cache_complex_operation(data):
    # 50 lines of complex logic
    pass
```

**✅ Correct:**
```python
# In gateway_wrappers.py
def cache_complex_operation(data):
    return call_interface(
        interface=GatewayInterface.CACHE,
        operation='complex_operation',
        data=data
    )

# In cache_core.py
def handle_complex_operation(data):
    # 50 lines of complex logic
    pass
```

**Fix:** Use **DT-02** → Business logic → Core implementation

### Mistake 3: Wrong Interface Selection

**❌ Wrong:**
```python
# In http_core.py
def format_timestamp(dt):
    """This is a utility, not HTTP-specific!"""
    return dt.isoformat()
```

**✅ Correct:**
```python
# In utility_core.py
def format_timestamp(dt):
    """Generic timestamp formatting utility."""
    return dt.isoformat()
```

**Fix:** Use **DT-02** → Generic utility → utility_core.py

---

## Verification Checklist

Before completing import/placement work, verify:

**For DT-01 (Imports):**
- [ ] All cross-interface imports use gateway
- [ ] Same-interface imports are direct
- [ ] No imports from lambda_function.py
- [ ] Standard library imports are direct
- [ ] No circular import potential

**For DT-02 (Placement):**
- [ ] Gateway wrappers are thin (<5 lines)
- [ ] Interface routers only dispatch
- [ ] Core files contain business logic
- [ ] Utilities are generic (not domain-specific)
- [ ] Functions in correct interface

**Overall:**
- [ ] Tests pass
- [ ] No import errors
- [ ] Code follows architecture patterns
- [ ] Documentation updated

---

## FAQs

**Q: Can I ever directly import from another interface?**
A: NO. Always use gateway imports for cross-interface functionality. This is **RULE-01** and is non-negotiable.

**Q: What if a function could fit in multiple interfaces?**
A: Choose the interface that most closely matches the primary purpose. If truly generic, use utility_core.py. Use **DT-02** to decide.

**Q: Can I create a new interface?**
A: Only if >200 lines of related code, has state, and is domain-specific. See **DT-13** for decision tree on creating new interfaces.

**Q: What about type hints that reference other interfaces?**
A: Use `from typing import TYPE_CHECKING` to avoid runtime imports:
```python
from typing import TYPE_CHECKING
if TYPE_CHECKING:
    from other_interface import SomeType
```

**Q: Can gateway wrappers contain logic?**
A: NO. They should be thin wrappers (<5 lines) that only call `call_interface()`. Logic belongs in core.

---

## Navigation

**Current Category:** Import (Decision Logic)  
**Other Categories:**
- Feature Addition (DT-03, DT-04)
- Error Handling (DT-05, DT-06)
- Testing (DT-08, DT-09)
- Optimization (DT-07, FW-01, FW-02)
- Refactoring (DT-10, DT-11)
- Deployment (DT-12)
- Architecture (DT-13)
- Meta (META-01)

**Master Index:** DecisionLogic-Master-Index.md

---

## Keywords

import, gateway, cross-interface, direct import, function placement, layer selection, interface selection, code organization

---

**File:** `Import-Index.md`  
**Location:** `/sima/entries/decision-logic/import/`  
**End of Category Index**

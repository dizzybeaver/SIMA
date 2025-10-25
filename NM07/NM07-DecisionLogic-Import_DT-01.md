# NM07-DecisionLogic-Import_DT-01.md - DT-01

# DT-01: How Should I Import X?

**Category:** Decision Logic
**Topic:** Import Decisions
**Priority:** Critical
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Decision tree for determining the correct import pattern in SUGA architecture - when to use gateway imports vs direct imports based on interface boundaries and code location.

---

## Context

SUGA architecture enforces strict import rules to prevent circular dependencies and maintain clean interface boundaries. This decision tree helps developers choose the correct import method for any situation.

---

## Content

### Decision Tree

```
START: Need to use functionality X
│
├─ Q: Is X in the same interface as current file?
│  ├─ YES → Direct import
│  │      Example: from cache_operations import validate_key
│  │      Rationale: Intra-interface freedom
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Is X in a different interface?
│  ├─ YES → Import from gateway
│  │      Example: from gateway import log_info
│  │      Rationale: Cross-interface via gateway
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Is current file lambda_function.py or extension?
│  ├─ YES → Import from gateway ONLY
│  │      Example: from gateway import cache_get
│  │      Rationale: External code uses gateway
│  │      → END
│  │
│  └─ NO → Continue
│
└─ Q: Is X stdlib or external library?
   ├─ Stdlib → Direct import OK
   │      Example: import json, import os
   │      → END
   │
   └─ External → Check availability
          ├─ In Lambda? → Direct import OK
          ├─ Not in Lambda? → Find stdlib alternative
          │      Example: Use urllib, not requests
          └─ No alternative? → Reconsider design
                 → END
```

### Code Examples

**✅ Correct Patterns:**

```python
# Same interface - direct import OK
# In cache_core.py
from cache_operations import validate_key

# Cross-interface - use gateway
# In cache_core.py
from gateway import log_info

# External code - gateway only
# In lambda_function.py
from gateway import cache_get
from homeassistant_extension import process_alexa_request

# Stdlib - always OK
from datetime import datetime
import json
```

**❌ Wrong Patterns:**

```python
# Cross-interface direct - WRONG
# In cache_core.py
from logging_core import log_info  # Violates RULE-01

# External importing internals - WRONG
# In lambda_function.py
from cache_core import something  # Violates RULE-02
```

### Real-World Usage Pattern

**User Query:** "How do I import log_info in cache_core?"

**Search Terms:** "how to import cross interface"

**Decision Flow:**
1. Check: Is log_info in CACHE interface? NO
2. Check: Is log_info in different interface? YES (LOGGING)
3. **Decision:** Different interface → Use gateway
4. **Answer:** `from gateway import log_info`

### Import Decision Matrix

| Current File | Target Functionality | Import Pattern |
|--------------|---------------------|----------------|
| cache_core.py | cache_operations function | Direct import |
| cache_core.py | logging_core function | Gateway import |
| lambda_function.py | Any interface function | Gateway import |
| Any core file | stdlib module | Direct import |
| Any core file | External library | Check availability |

---

## Related Topics

- **RULE-01**: Gateway-only cross-interface imports
- **RULE-02**: External code uses gateway
- **AP-01**: Direct cross-interface imports (anti-pattern)
- **DEC-01**: SUGA pattern choice (prevents circular imports)
- **DT-02**: Where should function go (placement decision)

---

## Keywords

imports, gateway, cross-interface, same-interface, decision-tree, SUGA architecture, circular imports

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-Import_DT-01.md`
**End of Document**

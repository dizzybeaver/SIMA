# NM07-DecisionLogic-FeatureAddition_DT-03.md - DT-03

# DT-03: User Wants Feature X

**Category:** Decision Logic
**Topic:** Feature Addition
**Priority:** High
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Decision tree for handling feature requests - determining if feature already exists, fits in existing interface, requires new interface, or belongs in utilities.

---

## Context

When users request new functionality, the first step is determining if it already exists in gateway, then whether it fits existing architecture or requires structural changes.

---

## Content

### Decision Tree

```
START: User requests feature X
│
├─ Q: Does X already exist in gateway?
│  ├─ YES → Point user to existing feature
│  │      Search: gateway_wrappers.py
│  │      Response: "Use gateway.X()"
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Does X fit in existing interface?
│  ├─ YES → Add to that interface
│  │      Example: cache.list_keys → CACHE interface
│  │      Steps:
│  │      1. Add to _OPERATION_DISPATCH in interface_<n>.py
│  │      2. Implement in <n>_core.py
│  │      3. Optional: Add wrapper in gateway_wrappers.py
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Is X substantial enough for new interface?
│  ├─ YES (>200 lines, has state, domain-specific)
│  │      → Create new interface
│  │      Steps:
│  │      1. Add to GatewayInterface enum
│  │      2. Create interface_<n>.py
│  │      3. Create <n>_core.py
│  │      4. Register in gateway_core.py
│  │      5. Add wrappers
│  │      6. Update neural maps
│  │      → END
│  │
│  └─ NO (simple helper)
│         → Add to UTILITY interface
│         Steps:
│         1. Add to utility_core.py
│         2. Optional: Add to interface_utility dispatch
│         3. Optional: Add wrapper
│         → END
```

### Feature Addition Examples

**Existing Feature:**
```
Request: "I need cache operations"
Check: gateway.cache_get exists
Response: "Use existing gateway.cache_get(key)"
```

**Fits Existing Interface:**
```
Request: "Add cache.clear_expired()"
Analysis:
- Already exists? NO
- Fits CACHE? YES (cache operation)
- Action: Add to CACHE interface

Implementation:
1. Add 'clear_expired' to _OPERATION_DISPATCH
2. Implement _execute_clear_expired_implementation in cache_core.py
3. Add cache_clear_expired() wrapper
```

**Needs New Interface:**
```
Request: "Add email sending capability"
Analysis:
- Already exists? NO
- Fits existing? NO (no EMAIL interface)
- Substantial? YES (SMTP logic, connection state)
- Action: Create new EMAIL interface

Implementation:
1. Add GatewayInterface.EMAIL enum
2. Create interface_email.py (router)
3. Create email_core.py (implementation)
4. Register in gateway_core.py
5. Add email_send() wrapper
6. Document in neural maps
```

**Add to Utility:**
```
Request: "Add string.to_camel_case()"
Analysis:
- Already exists? NO
- Fits existing? NO (no STRING interface)
- Substantial? NO (simple helper)
- Action: Add to UTILITY interface

Implementation:
1. Add to_camel_case() to utility_core.py
2. Optional: Add wrapper utility_to_camel_case()
```

### Feature Decision Matrix

| Request Type | Exists? | Fits Interface? | Substantial? | Action |
|--------------|---------|----------------|--------------|--------|
| Cache listing | YES | - | - | Use existing |
| Cache cleanup | NO | YES | - | Add to CACHE |
| Email sending | NO | NO | YES | New interface |
| String helper | NO | NO | NO | Add to UTILITY |

### Real-World Usage Pattern

**User Query:** "I need email sending capability"

**Search Terms:** "add feature new interface"

**Decision Flow:**
1. Check gateway: Not there
2. Fits existing interface? NO
3. Substantial? YES (SMTP, state, domain-specific)
4. **Decision:** Create new EMAIL interface
5. **Response:** "Substantial feature → Create new EMAIL interface with router and core files"

---

## Related Topics

- **DT-02**: Where function goes (placement)
- **DT-13**: New interface or extend (architecture decision)
- **ARCH-04**: Three-file interface pattern
- **DEC-01**: SUGA pattern structure

---

## Keywords

add feature, new feature, feature request, interface extension, architecture growth

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-FeatureAddition_DT-03.md`
**End of Document**

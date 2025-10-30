# NM07-DecisionLogic-Architecture_DT-13.md - DT-13

# DT-13: New Interface or Extend Existing?

**Category:** Decision Logic
**Topic:** Architecture
**Priority:** High
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Decision tree for architectural interface decisions - determining when new functionality warrants a new interface vs extending existing interface vs adding to utilities.

---

## Context

SUGA architecture uses focused interfaces. This decision tree helps maintain clean boundaries while avoiding interface proliferation.

---

## Content

### Decision Tree

```
START: Need new functionality
│
├─ Q: Does functionality fit existing interface?
│  ├─ YES → Extend existing
│  │      Example: cache.list_keys → CACHE interface
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Is functionality substantial (>200 lines)?
│  ├─ NO → Add to UTILITY
│  │      Example: String helpers
│  │      → END
│  │
│  └─ YES → Continue
│
├─ Q: Does functionality have its own state?
│  ├─ YES → New interface
│  │      Example: Database connection pool
│  │      → CREATE NEW INTERFACE
│  │
│  └─ NO → Continue
│
├─ Q: Is functionality domain-specific?
│  ├─ YES → New interface
│  │      Example: Email sending, File storage
│  │      → CREATE NEW INTERFACE
│  │
│  └─ NO → Add to UTILITY
│         Example: Generic helpers
│         → END
```

### New Interface Checklist

**Create new interface if ALL of:**
- [ ] >200 lines of code
- [ ] Has its own state
- [ ] Domain-specific (not generic utility)
- [ ] Used by multiple other interfaces
- [ ] Clear, focused responsibility

**Otherwise:** Extend existing or add to UTILITY

### Decision Examples

**Example 1: Email Sending - NEW INTERFACE**
```
Functionality: Email sending capability
Analysis:
- Fits existing interface? NO (no EMAIL interface)
- >200 lines? YES (SMTP logic, templates)
- Has state? YES (connection, credentials)
- Domain-specific? YES (email is a domain)
- Used by multiple? YES (notifications, alerts)

Decision: CREATE EMAIL INTERFACE

Structure:
- interface_email.py (router)
- email_core.py (implementation)
- Add GatewayInterface.EMAIL enum
- Register in gateway_core.py
- Add email_send() wrapper
```

**Example 2: Cache List Keys - EXTEND EXISTING**
```
Functionality: List all cache keys
Analysis:
- Fits existing interface? YES (CACHE operation)
- Already have CACHE? YES

Decision: EXTEND CACHE INTERFACE

Implementation:
1. Add 'list_keys' to _OPERATION_DISPATCH
2. Implement _execute_list_keys_implementation
3. Add cache_list_keys() wrapper

No new interface needed.
```

**Example 3: String Helpers - ADD TO UTILITY**
```
Functionality: String manipulation helpers
Analysis:
- Fits existing interface? NO (no STRING interface)
- >200 lines? NO (simple functions)
- Has state? NO (stateless)
- Domain-specific? NO (generic utility)

Decision: ADD TO UTILITY INTERFACE

Implementation:
- Add functions to utility_core.py
- to_camel_case(s)
- to_snake_case(s)
- truncate(s, length)
```

**Example 4: File Storage - NEW INTERFACE**
```
Functionality: S3 file operations
Analysis:
- Fits existing interface? NO (no FILE interface)
- >200 lines? YES (upload, download, list, delete)
- Has state? YES (S3 client, bucket config)
- Domain-specific? YES (file storage domain)
- Used by multiple? YES (logs, backups, exports)

Decision: CREATE FILE INTERFACE

Structure:
- interface_file.py (router)
- file_core.py (S3 operations)
- Add GatewayInterface.FILE enum
- Register in gateway_core.py
- Add file_upload(), file_download() wrappers
```

**Example 5: JSON Validation - ADD TO UTILITY**
```
Functionality: JSON schema validation
Analysis:
- Fits existing interface? NO (no VALIDATION interface)
- >200 lines? NO (~50 lines)
- Has state? NO (stateless validation)
- Domain-specific? NO (generic utility)

Decision: ADD TO UTILITY INTERFACE

Implementation:
- Add validate_json_schema() to utility_core.py
```

### Interface Size Guidelines

**Small Interface (< 200 lines):**
- Consider if really needs separate interface
- Might be better as utility functions
- Example: Basic string operations

**Medium Interface (200-500 lines):**
- Good candidate for interface
- Clear focused domain
- Example: CACHE, LOGGING, METRICS

**Large Interface (500-1000 lines):**
- Consider splitting into sub-interfaces
- Or separate into router + multiple core files
- Example: HTTP (has http_client_core, http_client_state, etc.)

**Very Large (> 1000 lines):**
- Should be split
- Likely mixing responsibilities
- Review interface boundaries

### Creating New Interface Steps

**1. Add to Enum:**
```python
# In gateway_core.py
class GatewayInterface(Enum):
    CACHE = "cache"
    LOGGING = "logging"
    # ... existing interfaces
    EMAIL = "email"  # New interface
```

**2. Create Router:**
```python
# interface_email.py
def execute_email_operation(operation: str, params: dict):
    """Route email operations to implementations."""
    _OPERATION_DISPATCH = {
        'send': _execute_send_implementation,
        'validate_address': _execute_validate_implementation,
    }
    return _OPERATION_DISPATCH[operation](**params)
```

**3. Create Implementation:**
```python
# email_core.py
def _execute_send_implementation(to: str, subject: str, body: str):
    """Send email via SMTP."""
    # Implementation
    pass

def _execute_validate_implementation(email: str):
    """Validate email address format."""
    # Implementation
    pass
```

**4. Register in Gateway:**
```python
# In gateway_core.py
_INTERFACE_HANDLERS = {
    GatewayInterface.CACHE: execute_cache_operation,
    GatewayInterface.LOGGING: execute_logging_operation,
    # ... existing
    GatewayInterface.EMAIL: execute_email_operation,  # Register
}
```

**5. Add Wrappers:**
```python
# In gateway_wrappers.py
def email_send(to: str, subject: str, body: str):
    """Send email."""
    return execute_operation(
        GatewayInterface.EMAIL,
        'send',
        {'to': to, 'subject': subject, 'body': body}
    )
```

**6. Update Documentation:**
- Add to neural maps
- Document in README
- Add examples

### Interface Anti-Patterns

**❌ Too Many Small Interfaces:**
```
GatewayInterface.STRING_UPPER
GatewayInterface.STRING_LOWER
GatewayInterface.STRING_STRIP
# Should be: UTILITY with multiple functions
```

**❌ Mixed Responsibilities:**
```
GatewayInterface.DATA_AND_CACHE
# Does data transformation AND caching
# Should be: DATA and CACHE separately
```

**❌ God Interface:**
```
GatewayInterface.INFRASTRUCTURE
# Does: logging, metrics, cache, http, etc.
# Should be: Multiple focused interfaces
```

**❌ Premature Interface Creation:**
```
# 20 lines of code
# No state
# Generic utility
# Created as separate interface anyway
# Should be: Part of UTILITY
```

### Real-World Usage Pattern

**User Query:** "Should I create a new interface for file operations?"

**Search Terms:** "new interface decision file"

**Decision Flow:**
1. Fits existing? NO (no FILE interface)
2. >200 lines? YES (upload, download, list, delete, etc.)
3. Has state? YES (S3 client, bucket config)
4. Domain-specific? YES (file storage)
5. **Decision:** CREATE NEW INTERFACE
6. **Response:** "Check: >200 lines + state + domain-specific + multi-use. If YES to all → New interface."

### When to Split Existing Interface

**Signs interface should be split:**
- Exceeds 1000 lines
- Has 2+ distinct responsibilities
- Half the operations rarely used
- Natural sub-domains emerge

**Example - HTTP Interface:**
```
Before: http_core.py (1200 lines)
- HTTP requests
- WebSocket management
- Request transformation
- Response parsing

After: Split into focused files
- http_client_core.py (requests)
- http_client_state.py (connection management)
- http_client_transformation.py (data transform)
- http_client_validation.py (validation)

Same interface, better organization
```

---

## Related Topics

- **DT-02**: Where function goes
- **DT-03**: User wants feature
- **LESS-06**: Interface lessons
- **DEC-01**: SUGA pattern choice
- **ARCH-04**: Three-file interface pattern

---

## Keywords

new interface, extend interface, interface decision, architecture growth, SUGA pattern

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-Architecture_DT-13.md`
**End of Document**

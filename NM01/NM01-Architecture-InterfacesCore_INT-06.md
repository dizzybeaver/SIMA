# NM01-Architecture-InterfacesCore_INT-06.md - INT-06

# INT-06: SINGLETON Interface

**Category:** NM01 - Architecture  
**Topic:** Interfaces-Core  
**Priority:** 🟢 MEDIUM  
**Status:** Active  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-24

---

## Summary

Singleton object storage and management interface with factory pattern support, enabling expensive object reuse across Lambda warm invocations.

---

## Context

The SINGLETON interface enables Lambda to maintain expensive objects (database connections, HTTP sessions, AWS SDK clients) across warm invocations, dramatically improving performance by avoiding re-initialization.

**Why it exists:** Creating AWS SDK clients, database connections, and HTTP sessions is expensive (50-200ms each). Reusing them across invocations improves performance significantly.

---

## Content

### Overview

```
Router: interface_singleton.py
Core: singleton_core.py
Purpose: Singleton object storage and management
Pattern: Dictionary-based dispatch with factory pattern
State: Module-level singleton dictionary
Dependency Layer: Layer 1 (Core Utilities)
```

### Operations (5 total)

```
├─ get: Get singleton instance (create if needed with factory)
├─ has: Check if singleton exists
├─ delete: Remove singleton
├─ clear: Clear all singletons
└─ get_stats: Get singleton statistics
```

### Gateway Wrappers

```python
# Core operations
singleton_get(key: str, factory: Callable = None) -> Any
singleton_has(key: str) -> bool
singleton_delete(key: str) -> bool
singleton_clear() -> int  # Returns count cleared
singleton_stats() -> Dict
```

### Dependencies

```
Uses: LOGGING
Used by: Various interfaces for stateful objects
```

### State Storage

```python
# Module-level singleton storage
_SINGLETON_STORE: Dict[str, Any] = {}
```

### Factory Pattern

The singleton interface supports lazy creation using factory functions:

```python
from gateway import singleton_get

# Define factory
def create_http_client():
    import requests
    return requests.Session()

# Get singleton (creates on first call)
session = singleton_get('http_session', factory=create_http_client)

# Subsequent calls return same instance
same_session = singleton_get('http_session')  # No factory needed
```

### Common Use Cases

```
Database Connections  → Reuse connection across invocations
HTTP Sessions        → Maintain session state
Configuration Objects → Parse once, reuse many times
External Clients     → AWS SDK clients, API clients
```

### Lifecycle Management

```python
from gateway import singleton_get, singleton_has, singleton_delete

# Check existence
if not singleton_has('db_connection'):
    # Create if doesn't exist
    conn = singleton_get('db_connection', factory=create_db_connection)

# Force recreation (delete and recreate)
singleton_delete('http_client')
new_client = singleton_get('http_client', factory=create_client)

# Clear all (useful for testing)
singleton_clear()
```

### Design Decisions

```
- Module-level storage (persists across warm Lambda invocations)
- Factory pattern for lazy initialization
- Manual lifecycle management (no automatic cleanup)
- Thread-safe (Lambda is single-threaded per DEC-04)
```

### Usage Example

```python
from gateway import singleton_get, singleton_has, singleton_delete
import boto3

# Factory for AWS client
def create_s3_client():
    return boto3.client('s3')

# Get singleton (lazy create)
s3 = singleton_get('s3_client', factory=create_s3_client)

# Use singleton
s3.list_buckets()

# Check if exists
if singleton_has('s3_client'):
    print("S3 client already initialized")

# Force recreation (e.g., after error)
singleton_delete('s3_client')
s3 = singleton_get('s3_client', factory=create_s3_client)
```

---

## Related Topics

- **DEC-04**: No threading locks - Lambda is single-threaded, so no concurrency issues
- **ARCH-07**: LMMS - Singletons are protected by ZAPH hot path
- **INT-08**: HTTP_CLIENT - Uses singleton for HTTP session
- **DEP-02**: Layer 1 (Core Utilities) - SINGLETON dependency layer

---

## Keywords

singleton, factory pattern, object reuse, state management, Lambda warm containers, performance

---

## Version History

- **2025-10-24**: Atomized from monolithic file (SIMA v3 migration)
- **2025-10-20**: Original content in IMPLEMENTATION_Core.md

---

**File:** `NM01-Architecture-InterfacesCore_INT-06.md`  
**End of Document**

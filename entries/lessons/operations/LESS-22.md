# Filename: LESS-22.md

# LESS-22: State Management in Stateless Environments

**REF-ID:** LESS-22  
**Category:** Architecture/Operations  
**Type:** Lesson Learned  
**Created:** 2025-11-01  
**Last Updated:** 2025-11-01  
**Status:** Active  
**Priority:** HIGH

---

## Summary

Stateless execution environments require careful state management - distinguish between module-level initialization (runs once per container) and handler-level state (runs per invocation). Warm container reuse enables optimization but requires understanding lifecycle boundaries.

---

## Context

Serverless functions execute in ephemeral containers with unpredictable lifecycles. Containers may be reused (warm start) or created fresh (cold start). Understanding what persists and what doesn't is critical for correctness and performance.

---

## The Lesson

### Container Lifecycle States

**Cold Start (new container):**
```python
# Module-level code runs ONCE
import json
DB_CLIENT = create_db_connection()  # Runs once
CONFIG = load_config()  # Runs once

def handler(event, context):
    # Handler runs per invocation
    user_id = event['userId']
    data = DB_CLIENT.query(user_id)  # Reuses connection
    return {'result': data}
```

**Warm Start (reused container):**
```python
# Module-level code DOES NOT re-run
# DB_CLIENT and CONFIG still exist from previous invocation

def handler(event, context):
    # Only handler runs
    # Can reuse module-level resources
    pass
```

### Module-Level vs Handler-Level

**Module-Level (persistent across warm starts):**
```python
# âœ… GOOD - Immutable resources, connections
import boto3
S3_CLIENT = boto3.client('s3')  # Reusable
CONFIG = {'timeout': 30}  # Immutable

# âŒ BAD - Mutable state
_request_count = 0  # Carries over between invocations!
_user_cache = {}  # Pollutes across users!
```

**Handler-Level (fresh per invocation):**
```python
def handler(event, context):
    # âœ… GOOD - Request-specific state
    request_id = event['requestId']  # Fresh
    user_data = fetch_user(event['userId'])  # Fresh
    
    # Process with fresh state
    result = process(user_data)
    return result
```

### State Patterns

**Pattern 1: Singleton Manager with Reset**
```python
# Module level
def get_data_manager():
    return singleton_get_or_create('data_manager', DataManager)

class DataManager:
    def __init__(self):
        self._cache = {}
        self._counter = 0
    
    def process(self, data):
        # Uses internal state
        self._counter += 1
        return self._cache.get(data)
    
    def reset(self):
        # Clean state for new request
        self._cache.clear()
        self._counter = 0

# Handler
def handler(event, context):
    manager = get_data_manager()
    manager.reset()  # Fresh state per invocation
    return manager.process(event['data'])
```

**Pattern 2: Connection Pooling**
```python
# Module level - persistent connections
def get_db_client():
    return singleton_get_or_create('db', create_db_connection)

# Handler - request-specific queries
def handler(event, context):
    db = get_db_client()  # Reused connection
    result = db.query(event['query'])  # Fresh query
    return result
```

**Pattern 3: Configuration Caching**
```python
# Module level - immutable config
_CONFIG = None

def get_config():
    global _CONFIG
    if _CONFIG is None:
        _CONFIG = load_from_parameter_store()
    return _CONFIG

# Handler
def handler(event, context):
    config = get_config()  # Reused config
    timeout = config['timeout']  # Safe - immutable
    return process_with_timeout(event, timeout)
```

### Common Mistakes

**Mistake 1: Mutable module-level state**
```python
# âŒ WRONG
_user_sessions = {}  # Leaks across invocations!

def handler(event, context):
    user_id = event['userId']
    _user_sessions[user_id] = event  # Pollutes!
    # Next invocation sees previous user's data!
```

**Mistake 2: Assuming fresh state**
```python
# âŒ WRONG
_request_count = 0

def handler(event, context):
    _request_count += 1
    if _request_count == 1:
        # Assumes first request
        initialize()  # Only runs on first invocation!
    # Subsequent warm starts skip initialization!
```

**Mistake 3: Not cleaning up**
```python
# âŒ WRONG
_temp_files = []

def handler(event, context):
    file = create_temp_file()
    _temp_files.append(file)  # Grows indefinitely!
    # Never cleaned up
    # Eventually fills disk
```

### Correct Patterns

**âœ… Reset per invocation:**
```python
def handler(event, context):
    # Clear any module-level mutable state
    reset_all_managers()
    
    # Process with fresh state
    result = process(event)
    return result
```

**âœ… Immutable module state:**
```python
# Module level
CONSTANTS = {'timeout': 30, 'max_retries': 3}
DB_CLIENT = create_connection()  # Reusable resource

# Safe because immutable
```

**âœ… Request-scoped state:**
```python
def handler(event, context):
    # All state local to this invocation
    request_id = generate_id()
    user_data = fetch_data(event['userId'])
    result = process(user_data, request_id)
    return result
```

### Memory Management

**Warm containers enable optimization:**
```python
# Module level - loads once
import heavy_library  # 50MB, takes 2s to load

# First invocation: Cold start (loads library)
# Time: 2s load + 0.1s execution = 2.1s

# Second invocation: Warm start (library already loaded)
# Time: 0.1s execution

# Optimization: Heavy imports at module level
```

**But require cleanup:**
```python
def handler(event, context):
    # Check memory pressure
    if get_memory_usage() > THRESHOLD:
        # Clean up caches
        clear_all_caches()
    
    # Process
    result = process(event)
    return result
```

---

## Related Topics

- **LESS-18**: Singleton Pattern Lifecycle Management
- **INT-09**: Singleton Interface (get_or_create pattern)
- **ARCH-02**: Layered architecture (where state lives)
- **LESS-17**: Performance optimization (warm start benefits)
- **DEC-14**: Lazy module loading decisions

---

## Keywords

state-management, serverless, container-lifecycle, warm-start, cold-start, module-level, handler-level, singleton-pattern, memory-management

---

## Version History

- **2025-11-01**: Created for SIMAv4 Priority 4
- **Source**: Genericized from serverless architecture patterns

---

**File:** `sima/entries/lessons/operations/LESS-22.md`  
**Lines:** ~285  
**Status:** Complete  
**Next:** LESS-29

---

**END OF DOCUMENT**
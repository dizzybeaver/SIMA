# GATE-04: Gateway Wrapper Functions
# File: GATE-04_Gateway-Wrapper-Functions.md

**REF-ID:** GATE-04  
**Version:** 1.0.0  
**Category:** Gateway Pattern  
**Status:** Active  
**Created:** 2025-10-29  
**Last Updated:** 2025-10-29

---

## 📋 OVERVIEW

**Pattern Name:** Gateway Wrapper Functions  
**Short Code:** GATE-04  
**Type:** API Pattern  
**Scope:** Gateway Layer

**One-Line Description:**  
Convenience functions in gateway_wrappers.py that provide simplified, parameterized APIs for common operations, transforming complex interface calls into simple function calls.

**Primary Purpose:**  
Gateway wrappers make the system easy to use by providing intuitive, well-documented functions that hide the complexity of interface routers and operation dispatch, offering sensible defaults and parameter transformation while maintaining the single-entry-point architecture.

---

## 🎯 APPLICABILITY

### When to Use
✅ Use gateway wrappers when:
- Common operations need simplified API
- Parameters need transformation or defaults
- Multiple steps should be combined into one call
- Documentation should be centralized
- Want discoverable API (IDE autocomplete)
- Providing library for other developers

### When NOT to Use
❌ Do NOT use wrappers when:
- Operation is used < 5 times in codebase (not worth wrapper)
- Interface already provides simple enough API
- Wrapper adds no value (just passes through)
- Every operation needs wrapper (creates 1:1 redundancy)

### Best For
- **Usage Frequency:** 10+ calls across codebase
- **Complexity:** 3+ parameters or multi-step operations
- **Audience:** Developers who need simple, intuitive API

---

## 🗺️ STRUCTURE

### Wrapper Layers

```
┌─────────────────────────────────────────────┐
│   Application Code                          │
│   Simple, intuitive calls                   │
│                                             │
│   from gateway import cache_get, log_info   │
│   value = cache_get('key')                  │
│   log_info('message')                       │
└──────────────────┬──────────────────────────┘
                   │ Clean API
                   ↓
┌─────────────────────────────────────────────┐
│   Gateway Wrappers (gateway_wrappers.py)    │
│   Convenience functions                     │
│                                             │
│   def cache_get(key, default=None):         │
│       # Parameter handling                  │
│       # Default values                      │
│       # Calls gateway_core                  │
└──────────────────┬──────────────────────────┘
                   │ Transforms to
                   ↓
┌─────────────────────────────────────────────┐
│   Gateway Core (gateway_core.py)            │
│   Generic operation executor                │
│                                             │
│   execute_operation('cache', 'get',         │
│                      {'key': key})          │
└──────────────────┬──────────────────────────┘
                   │ Routes to
                   ↓
┌─────────────────────────────────────────────┐
│   Interface Router (interface_cache.py)     │
│   Operation dispatch                        │
│                                             │
│   _OPERATION_DISPATCH['get'](**params)      │
└──────────────────┬──────────────────────────┘
                   │ Calls
                   ↓
┌─────────────────────────────────────────────┐
│   Core Implementation (cache_core.py)       │
│   Business logic                            │
│                                             │
│   def _execute_get(key):                    │
│       return _CACHE_STORE.get(key)          │
└─────────────────────────────────────────────┘
```

---

## ⚙️ KEY RULES

### Rule 1: One Wrapper Per Common Operation
**Create wrappers for frequently-used operations only.**

```python
# ✅ CORRECT - Common operations get wrappers
def cache_get(key, default=None):
    """Used 100+ times - deserves wrapper"""
    pass

def cache_set(key, value, ttl=300):
    """Used 80+ times - deserves wrapper"""
    pass

# ❌ WRONG - Rarely-used operation doesn't need wrapper
def cache_get_statistics_for_specific_namespace(namespace, include_expired=False):
    """Used 2 times - doesn't need wrapper, use execute_operation directly"""
    pass
```

**Rationale:** Wrappers for common operations only, avoid 1:1 mapping.

### Rule 2: Provide Sensible Defaults
**Wrappers MUST provide good default values.**

```python
# ✅ CORRECT - Sensible defaults
def cache_set(key, value, ttl_seconds=300):
    """Default TTL of 5 minutes is reasonable."""
    return gateway_core.execute_operation('cache', 'set', {
        'key': key,
        'value': value,
        'ttl_seconds': ttl_seconds
    })

def http_get(url, timeout=30, retries=3):
    """Default timeout and retries are sensible."""
    return gateway_core.execute_operation('http_client', 'get', {
        'url': url,
        'timeout': timeout,
        'retries': retries
    })

# ❌ WRONG - No defaults or bad defaults
def cache_set(key, value, ttl_seconds):
    """No default - forces user to always specify"""
    pass

def http_get(url, timeout=1):
    """1 second timeout too short for most cases"""
    pass
```

**Rationale:** Defaults reduce boilerplate, make API easier to use.

### Rule 3: Transform Parameters
**Wrappers SHOULD simplify parameter passing.**

```python
# ✅ CORRECT - Wrapper simplifies parameters
def log_error(message, error=None):
    """
    Simple error logging with optional exception.
    
    Wrapper handles:
    - Error formatting
    - Stack trace extraction
    - Timestamp addition
    """
    params = {'message': message}
    
    if error:
        params['error_type'] = type(error).__name__
        params['error_message'] = str(error)
        import traceback
        params['stack_trace'] = traceback.format_exc()
    
    return gateway_core.execute_operation('logging', 'error', params)


# Application code is clean
try:
    risky_operation()
except Exception as e:
    log_error("Operation failed", error=e)  # Simple!


# ❌ WRONG - No transformation, just pass-through
def log_error(message, error_type, error_message, stack_trace):
    """User must do all formatting themselves"""
    return gateway_core.execute_operation('logging', 'error', {
        'message': message,
        'error_type': error_type,
        'error_message': error_message,
        'stack_trace': stack_trace
    })


# Application code is verbose
try:
    risky_operation()
except Exception as e:
    import traceback
    log_error(
        "Operation failed",
        type(e).__name__,
        str(e),
        traceback.format_exc()
    )  # Too much work!
```

**Rationale:** Wrappers should make common cases easy.

### Rule 4: Comprehensive Documentation
**Every wrapper MUST have complete documentation.**

```python
# ✅ CORRECT - Full documentation
def cache_get(key: str, default=None):
    """
    Get value from cache with optional default.
    
    Retrieves cached value by key. If key not found or expired,
    returns default value instead of None.
    
    Args:
        key: Cache key (must be string, max 256 chars)
        default: Value to return if key not found (default: None)
    
    Returns:
        Cached value if found, default otherwise
    
    Raises:
        ValueError: If key is invalid (empty, too long, wrong type)
    
    Examples:
        >>> value = cache_get('user_123')
        >>> value = cache_get('config', default={})
        >>> value = cache_get('session', default={'new': True})
    
    Performance:
        - Cache hit: ~1ms
        - Cache miss: ~0.5ms
        - Lazy loads cache interface on first call
    
    See Also:
        - cache_set: Store values in cache
        - cache_delete: Remove values from cache
        - cache_has: Check if key exists
    """
    import gateway_core
    result = gateway_core.execute_operation('cache', 'get', {'key': key})
    return default if result is None else result


# ❌ WRONG - No documentation
def cache_get(key, default=None):
    import gateway_core
    result = gateway_core.execute_operation('cache', 'get', {'key': key})
    return default if result is None else result
```

**Rationale:** Wrappers are public API, must be well-documented.

---

## 🎯 BENEFITS

### Benefit 1: Clean, Intuitive API
**Simple function calls instead of complex operation dispatch:**

```python
# Without wrappers (complex)
result = gateway_core.execute_operation('cache', 'get', {
    'key': 'user_123',
    'default': None
})

result = gateway_core.execute_operation('http_client', 'post', {
    'url': 'https://api.example.com/users',
    'data': {'name': 'John'},
    'headers': {'Authorization': 'Bearer token'},
    'timeout': 30,
    'retries': 3
})

# With wrappers (simple)
result = cache_get('user_123')

result = http_post(
    'https://api.example.com/users',
    {'name': 'John'},
    headers={'Authorization': 'Bearer token'}
)
```

**Impact:** 50-70% less code in application layer.

### Benefit 2: Discoverable API
**IDE autocomplete shows available functions:**

```python
from gateway import *

# Type "cache" and IDE shows:
# - cache_get()
# - cache_set()
# - cache_delete()
# - cache_clear()
# - cache_has()

# Type "log" and IDE shows:
# - log_info()
# - log_error()
# - log_warning()
# - log_debug()

# Self-documenting API!
```

**Impact:** Faster development, less need to read docs.

### Benefit 3: Centralized Defaults
**Change defaults in one place:**

```python
# Change default TTL system-wide
def cache_set(key, value, ttl_seconds=600):  # Changed from 300 to 600
    """Now all cache operations default to 10 minutes"""
    pass

# All existing calls automatically get new default:
cache_set('key', 'value')  # Now uses 600s TTL
```

**Impact:** Easy to adjust system behavior without changing call sites.

### Benefit 4: Parameter Validation
**Wrappers can validate before dispatching:**

```python
def cache_set(key: str, value, ttl_seconds: int = 300):
    """Validate parameters before operation."""
    # Validation
    if not isinstance(key, str):
        raise TypeError("Key must be string")
    if not key or len(key) > 256:
        raise ValueError("Key must be 1-256 characters")
    if ttl_seconds < 0:
        raise ValueError("TTL must be non-negative")
    
    # Dispatch
    return gateway_core.execute_operation('cache', 'set', {
        'key': key,
        'value': value,
        'ttl_seconds': ttl_seconds
    })

# Application gets clear error early
try:
    cache_set('', 'value')  # Caught by wrapper
except ValueError as e:
    print(f"Invalid input: {e}")
```

**Impact:** Better error messages, caught at API boundary.

---

## ⚠️ COMMON PITFALLS

### Pitfall 1: Over-Wrapping
**Problem:** Creating wrapper for every operation creates 1:1 redundancy.

```python
# ❌ WRONG - Wrapper adds no value
def cache_advanced_statistics_with_filtering(
    namespace,
    include_expired,
    filter_by_size,
    sort_order
):
    """Used 1 time, just pass-through"""
    return gateway_core.execute_operation('cache', 'advanced_stats', {
        'namespace': namespace,
        'include_expired': include_expired,
        'filter_by_size': filter_by_size,
        'sort_order': sort_order
    })

# ✅ CORRECT - Rare operations use execute_operation directly
# In application code:
stats = gateway_core.execute_operation('cache', 'advanced_stats', {
    'namespace': 'users',
    'include_expired': False,
    'filter_by_size': 1000,
    'sort_order': 'desc'
})
```

**Solution:** Wrappers for common operations (10+ uses) only.

### Pitfall 2: Inconsistent Naming
**Problem:** Wrappers don't follow consistent naming convention.

```python
# ❌ WRONG - Inconsistent names
def get_from_cache(key):  # Verbose
    pass

def set_cache(key, value):  # Different pattern
    pass

def deleteCache(key):  # CamelCase mixed with snake_case
    pass

# ✅ CORRECT - Consistent naming
def cache_get(key):  # Consistent: {interface}_{operation}
    pass

def cache_set(key, value):
    pass

def cache_delete(key):
    pass
```

**Solution:** Follow pattern: `{interface}_{operation}` consistently.

### Pitfall 3: Wrapper Logic Too Complex
**Problem:** Business logic in wrapper instead of core.

```python
# ❌ WRONG - Business logic in wrapper
def cache_get_or_compute(key, compute_fn):
    """Too much logic in wrapper!"""
    value = cache_get(key)
    if value is None:
        value = compute_fn()
        if value is not None:
            cache_set(key, value)
            log_info(f"Computed and cached: {key}")
        else:
            log_warning(f"Computation returned None: {key}")
    return value

# ✅ CORRECT - Wrapper is thin, logic in core
def cache_get_or_compute(key, compute_fn):
    """Thin wrapper to core operation"""
    return gateway_core.execute_operation('cache', 'get_or_compute', {
        'key': key,
        'compute_fn': compute_fn
    })

# Logic in cache_core.py
def _execute_get_or_compute(key, compute_fn):
    """Business logic in core where it belongs"""
    value = _CACHE_STORE.get(key)
    if value is None:
        value = compute_fn()
        if value is not None:
            _CACHE_STORE[key] = value
            import gateway
            gateway.log_info(f"Computed and cached: {key}")
    return value
```

**Solution:** Wrappers should be thin, logic belongs in core.

### Pitfall 4: Duplicate Documentation
**Problem:** Documentation in both wrapper and core.

```python
# ❌ WRONG - Duplicated documentation
# In gateway_wrappers.py
def cache_get(key, default=None):
    """
    Get value from cache.
    
    Long detailed documentation...
    """
    pass

# In cache_core.py
def _execute_get(key):
    """
    Get value from cache.
    
    Same long detailed documentation again!
    """
    pass

# ✅ CORRECT - Documentation at API boundary (wrapper) only
# In gateway_wrappers.py
def cache_get(key, default=None):
    """
    Get value from cache.
    
    Full public API documentation here.
    """
    pass

# In cache_core.py
def _execute_get(key):
    """Internal implementation. See gateway.cache_get for API docs."""
    pass
```

**Solution:** Document at API boundary (wrappers), minimal docs in internals.

---

## 🔄 IMPLEMENTATION PATTERNS

### Pattern 1: Simple Pass-Through Wrapper

```python
def cache_get(key: str, default=None):
    """
    Get value from cache.
    
    Args:
        key: Cache key
        default: Default if not found
    
    Returns:
        Cached value or default
    """
    result = gateway_core.execute_operation('cache', 'get', {'key': key})
    return default if result is None else result
```

**When to use:** Simple operation, just adds default value.

### Pattern 2: Parameter Transformation Wrapper

```python
def http_post(url: str, data, headers: dict = None, timeout: int = 30):
    """
    HTTP POST request with automatic JSON encoding.
    
    Args:
        url: Request URL
        data: Request body (auto-converted to JSON if dict)
        headers: HTTP headers (Content-Type added automatically)
        timeout: Request timeout in seconds
    
    Returns:
        Response object
    """
    # Transform data to JSON if needed
    import json
    if isinstance(data, dict):
        data = json.dumps(data)
        if not headers:
            headers = {}
        headers.setdefault('Content-Type', 'application/json')
    
    # Dispatch
    return gateway_core.execute_operation('http_client', 'post', {
        'url': url,
        'data': data,
        'headers': headers,
        'timeout': timeout
    })
```

**When to use:** Parameters need transformation or enrichment.

### Pattern 3: Multi-Operation Wrapper

```python
def cache_get_or_set(key: str, value_fn, ttl_seconds: int = 300):
    """
    Get from cache, or compute and store if missing.
    
    Combines get and set operations into single convenient call.
    
    Args:
        key: Cache key
        value_fn: Function to compute value if not cached
        ttl_seconds: TTL if value needs to be cached
    
    Returns:
        Cached value or computed value
    
    Example:
        >>> value = cache_get_or_set('user_123', lambda: fetch_user(123))
    """
    # Try get
    value = gateway_core.execute_operation('cache', 'get', {'key': key})
    
    if value is None:
        # Compute
        value = value_fn()
        
        # Set
        if value is not None:
            gateway_core.execute_operation('cache', 'set', {
                'key': key,
                'value': value,
                'ttl_seconds': ttl_seconds
            })
    
    return value
```

**When to use:** Common pattern combines multiple operations.

### Pattern 4: Validation Wrapper

```python
def config_get(key: str, default=None, required: bool = False):
    """
    Get configuration value with validation.
    
    Args:
        key: Configuration key
        default: Default value if not found
        required: If True, raises error if not found and no default
    
    Returns:
        Configuration value
    
    Raises:
        ValueError: If required=True and value not found
    
    Example:
        >>> api_key = config_get('API_KEY', required=True)
    """
    # Get value
    value = gateway_core.execute_operation('config', 'get', {'key': key})
    
    # Handle missing
    if value is None:
        if required and default is None:
            raise ValueError(f"Required configuration missing: {key}")
        return default
    
    return value
```

**When to use:** Operation needs validation or error handling.

---

## 💡 USAGE EXAMPLES

### Example 1: Complete Wrapper Set for Cache Interface

```python
# gateway_wrappers.py

def cache_get(key: str, default=None):
    """Get value from cache."""
    result = gateway_core.execute_operation('cache', 'get', {'key': key})
    return default if result is None else result


def cache_set(key: str, value, ttl_seconds: int = 300):
    """Set value in cache with TTL."""
    return gateway_core.execute_operation('cache', 'set', {
        'key': key,
        'value': value,
        'ttl_seconds': ttl_seconds
    })


def cache_delete(key: str):
    """Delete value from cache."""
    return gateway_core.execute_operation('cache', 'delete', {'key': key})


def cache_clear():
    """Clear all values from cache."""
    return gateway_core.execute_operation('cache', 'clear', {})


def cache_has(key: str):
    """Check if key exists in cache."""
    return gateway_core.execute_operation('cache', 'has', {'key': key})


def cache_statistics():
    """Get cache statistics."""
    return gateway_core.execute_operation('cache', 'statistics', {})
```

### Example 2: Application Using Wrappers

```python
# Application code
from gateway import cache_get, cache_set, log_info, http_post

def process_user_request(user_id):
    """Process user request with caching."""
    log_info(f"Processing request for user {user_id}")
    
    # Check cache (simple!)
    cached = cache_get(f"user_{user_id}")
    if cached:
        log_info(f"Cache hit for user {user_id}")
        return cached
    
    # Fetch from API (simple!)
    log_info(f"Cache miss, fetching from API")
    response = http_post(
        'https://api.example.com/users',
        {'user_id': user_id}
    )
    
    # Cache result (simple!)
    cache_set(f"user_{user_id}", response.json(), ttl_seconds=600)
    
    return response.json()

# Without wrappers, this would be much more verbose!
```

---

## 📊 PERFORMANCE CHARACTERISTICS

### Wrapper Overhead

```
Direct gateway_core call:
    gateway_core.execute_operation(...): ~50ns

Via wrapper:
    Wrapper function call: ~20ns
    Parameter handling: ~10ns
    Gateway_core call: ~50ns
    Total: ~80ns

Overhead: 30ns (0.03 microseconds)

For typical operation (1-100ms):
    Overhead: 0.003% - 0.00003% (negligible)
```

### Memory Impact

```
Wrappers in gateway_wrappers.py:
├─ Function objects: ~100 bytes each
├─ 100 wrappers: ~10KB
└─ Docstrings: ~50KB

Total: ~60KB (0.05% of 128MB Lambda)

Impact: Negligible
```

---

## 🔄 EVOLUTION & VERSIONING

### Version History

**v1.0.0** (2025-10-29)
- Initial gateway wrapper pattern documentation
- Best practices defined
- Common pitfalls identified
- Implementation patterns provided

### Future Considerations
- **Auto-Generation:** Generate wrappers from interface definitions
- **Type Safety:** Full type hints and mypy validation
- **Documentation:** Auto-generate API docs from docstrings
- **Deprecation:** Mark deprecated wrappers clearly

### Deprecation Path
**If This Pattern Is Deprecated:**
- **Reason:** Better API pattern discovered
- **Replacement:** New wrapper pattern
- **Migration Guide:** Update wrapper calls
- **Support Timeline:** Minimum 1 year before removal

---

## 📚 REFERENCES

### Internal References
- **Related Patterns:** GATE-01 (Gateway Layer Structure), GATE-02 (Lazy Imports)
- **Related Files:** gateway_wrappers.py, gateway_core.py

### External References
- **Facade Pattern:** GoF Design Patterns (similar concept)
- **Adapter Pattern:** Simplifying complex interfaces
- **Python Docstring:** PEP 257

### Related Entries
- **Lessons:** API design lessons
- **Decisions:** Why wrappers chosen

---

## 🤝 CONTRIBUTORS

**Original Author:** SIMAv4 Phase 2.0  
**Major Contributors:**
- SUGA-ISP Project Team - 100+ production wrappers
- SIMAv4 Phase 2.0 - Pattern extraction

**Last Reviewed By:** Claude  
**Review Date:** 2025-10-29

---

## 📝 CHANGE LOG

### [1.0.0] - 2025-10-29
- Initial gateway wrapper pattern documentation
- Common operations identified
- Parameter transformation patterns
- Documentation standards defined

---

**END OF GATEWAY ENTRY**

**REF-ID:** GATE-04  
**Template Version:** 1.0.0  
**Entry Type:** Gateway Pattern  
**Status:** Active  
**Maintenance:** Review quarterly or when API patterns evolve

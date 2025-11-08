# DEC-05-Sentinel-Sanitization.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Decision on sentinel object handling at boundaries  
**Category:** Architecture Decision  
**Status:** Active

---

## DECISION

**All sentinel objects must be sanitized at architectural boundaries before serialization or external exposure.**

---

## CONTEXT

SUGA architecture uses internal sentinel objects (like `_CacheMiss`, `_NotFound`, `_Empty`) to signal special states within the application. These sentinels are Python-internal implementation details not meant for external consumption.

---

## PROBLEM

### The Sentinel Leak Issue

Sentinel objects cause failures when they cross boundaries:

**1. JSON Serialization Failure**
```python
# Internal cache miss sentinel
_CacheMiss = object()

result = cache.get('key')  # Returns _CacheMiss
return json.dumps({'result': result})  # ❌ FAILS
# TypeError: Object of type 'object' is not JSON serializable
```

**2. API Response Contamination**
```python
# Sentinel leaks into response
return {
    'statusCode': 200,
    'body': {'data': _CacheMiss}  # ❌ FAILS at API Gateway
}
```

**3. External System Integration**
```python
# Sentinel sent to external service
requests.post(url, json={'value': _NotFound})  # ❌ FAILS
```

---

## DECISION RATIONALE

### Primary Reason: Boundary Integrity

Architectural boundaries must have clean contracts:
- **Internal Layer**: Can use sentinels freely
- **External Boundary**: Must use standard types only

### Secondary Reason: System Integration

External systems expect standard data types:
- JSON-serializable values
- Well-defined nullability
- Predictable behavior

### Tertiary Reason: Error Prevention

Sentinel leaks cause:
- Serialization failures (500 errors)
- Data corruption
- Integration failures
- Debugging complexity

---

## ALTERNATIVES CONSIDERED

### Alternative 1: Never Use Sentinels

**Approach:** Use None or standard values everywhere

**Rejected Because:**
- Loses semantic distinction (None can mean multiple things)
- Can't distinguish "value is None" from "no value found"
- Reduces code clarity internally

### Alternative 2: Custom Serialization

**Approach:** Make sentinels JSON-serializable

**Rejected Because:**
- Sentinels would leak to external systems
- Adds complexity to serialization logic
- External systems wouldn't understand sentinel values
- Violates clean boundary principle

### Alternative 3: Type System Enforcement

**Approach:** Use type hints to prevent sentinel leakage

**Partially Accepted:**
- Type hints help but aren't enforced at runtime
- Good documentation aid
- Doesn't prevent mistakes
- Use in combination with sanitization

---

## IMPLEMENTATION GUIDELINES

### Boundary Definitions

**Internal Boundaries (No Sanitization Required):**
- Core → Interface layer
- Interface → Gateway layer
- Within same layer

**External Boundaries (Sanitization Required):**
- Gateway → Router layer
- Any function returning to caller
- Before JSON serialization
- Before external API calls
- Before database writes

### Sanitization Pattern

```python
# Define sentinels
_CacheMiss = object()
_NotFound = object()
_Empty = object()

# Sanitize at boundary
def sanitize_for_external(value):
    """Convert internal sentinels to external-safe values."""
    if value is _CacheMiss:
        return None
    if value is _NotFound:
        return None
    if value is _Empty:
        return []
    return value

# Apply at router layer
def router_handler(event, context):
    # Internal processing
    result = gateway.process_request(event)
    
    # Sanitize before returning
    safe_result = sanitize_for_external(result)
    
    return {
        'statusCode': 200,
        'body': json.dumps(safe_result)
    }
```

### Deep Sanitization

For nested structures:
```python
def sanitize_deep(obj):
    """Recursively sanitize nested structures."""
    if obj is _CacheMiss or obj is _NotFound:
        return None
    if obj is _Empty:
        return []
    
    if isinstance(obj, dict):
        return {k: sanitize_deep(v) for k, v in obj.items()}
    
    if isinstance(obj, list):
        return [sanitize_deep(item) for item in obj]
    
    return obj
```

### Type Annotations

Document sentinel usage:
```python
from typing import Union, Any

CacheResult = Union[Any, object]  # object represents sentinel

def cache_get(key: str) -> CacheResult:
    """Get value from cache.
    
    Returns:
        Cached value or _CacheMiss sentinel
    """
    pass

def safe_cache_get(key: str) -> Any:
    """Get value from cache, sanitized for external use.
    
    Returns:
        Cached value or None (never sentinel)
    """
    result = cache_get(key)
    return sanitize_for_external(result)
```

---

## COMMON SENTINEL PATTERNS

### Cache Miss Sentinel

```python
# Define
_CacheMiss = object()

# Use internally
def get_cached(key):
    if key not in cache:
        return _CacheMiss
    return cache[key]

# Sanitize at boundary
def get_cached_external(key):
    result = get_cached(key)
    if result is _CacheMiss:
        return None
    return result
```

### Not Found Sentinel

```python
# Define
_NotFound = object()

# Use internally
def find_user(user_id):
    user = db.query(user_id)
    if user is None:
        return _NotFound
    return user

# Sanitize at boundary
def api_get_user(user_id):
    user = find_user(user_id)
    if user is _NotFound:
        return {'error': 'User not found'}, 404
    return {'user': user}, 200
```

### Empty Result Sentinel

```python
# Define
_Empty = object()

# Use internally
def search_items(query):
    results = perform_search(query)
    if not results:
        return _Empty
    return results

# Sanitize at boundary
def api_search(query):
    results = search_items(query)
    if results is _Empty:
        return {'items': [], 'count': 0}
    return {'items': results, 'count': len(results)}
```

---

## ANTI-PATTERNS TO AVOID

### ❌ Direct Sentinel Return

```python
# WRONG - Returns sentinel directly
def api_endpoint(event):
    result = cache.get('key')  # Might be _CacheMiss
    return {'body': json.dumps(result)}  # FAILS
```

### ❌ Inconsistent Sanitization

```python
# WRONG - Sanitizes sometimes
def handler(event):
    if event['type'] == 'A':
        result = process_a(event)
        return sanitize(result)  # Sanitizes
    else:
        result = process_b(event)
        return result  # DOESN'T sanitize - inconsistent
```

### ❌ Late Sanitization

```python
# WRONG - Sanitizes after serialization attempted
def handler(event):
    result = process(event)
    try:
        return json.dumps(result)
    except TypeError:
        # Too late - should have sanitized before
        return json.dumps(sanitize(result))
```

---

## RELATED DECISIONS

- **DEC-03**: Gateway mandatory provides clear boundary point
- **ARCH-02**: Layer separation defines internal vs external

---

## RELATED ANTI-PATTERNS

- **AP-19**: Sentinel objects crossing boundaries
- **AP-17**: Unsafe data exposure

---

## RELATED BUGS

- **BUG-01**: Sentinel leak causing 535ms penalty and JSON failure

---

## IMPACT ANALYSIS

### Positive Impacts

- **Reliability**: Eliminates serialization failures
- **Predictability**: External interfaces have clean contracts
- **Integration**: Works correctly with external systems
- **Debugging**: Easier to trace data flow

### Negative Impacts

- **Complexity**: Requires sanitization layer
- **Performance**: Small overhead for sanitization
- **Discipline**: Developers must remember to sanitize

### Mitigation for Negatives

- **Templates**: Provide sanitization boilerplate
- **Reviews**: Check for sentinel leaks in code review
- **Testing**: Add tests for boundary sanitization
- **Documentation**: Clear guidelines on when to sanitize

---

## ENFORCEMENT

### Code Review Checklist

- [ ] All router/handler functions sanitize results
- [ ] No sentinels in JSON.dumps calls
- [ ] No sentinels in external API calls
- [ ] Deep sanitization for nested structures
- [ ] Type hints document sentinel usage

### Testing Requirements

```python
def test_sentinel_sanitization():
    """Test that sentinels are sanitized at boundaries."""
    # Internal processing returns sentinel
    result = internal_function()
    assert result is _CacheMiss
    
    # External boundary sanitizes it
    external_result = external_function()
    assert external_result is None
    assert external_result is not _CacheMiss
```

### Runtime Checks

Optional: Add serialization validation
```python
def safe_json_dumps(obj):
    """JSON dumps with sentinel detection."""
    # Check for sentinels
    def has_sentinel(o):
        if o is _CacheMiss or o is _NotFound:
            return True
        if isinstance(o, dict):
            return any(has_sentinel(v) for v in o.values())
        if isinstance(o, list):
            return any(has_sentinel(i) for i in o)
        return False
    
    if has_sentinel(obj):
        raise ValueError("Sentinel object in external data - must sanitize")
    
    return json.dumps(obj)
```

---

## VERSIONING

**v1.0.0**: Initial decision document
- Established sanitization requirement
- Documented patterns and anti-patterns
- Provided implementation guidelines

---

## CHANGELOG

### 2025-11-06
- Created decision document
- Established as architecture standard
- Added sanitization patterns
- Added enforcement mechanisms

---

**Related Documents:**
- ARCH-02-Layer-Separation.md
- DEC-03-Gateway-Mandatory.md
- BUG-01.md (Sentinel Leak Bug)
- AP-19.md (Sentinel Crossing Boundary Anti-Pattern)

**Keywords:** sentinel, sanitization, boundary, serialization, JSON, _CacheMiss, _NotFound, router layer

**Category:** Architecture Decision  
**Impact:** High (affects all boundary implementations)  
**Compliance:** Mandatory

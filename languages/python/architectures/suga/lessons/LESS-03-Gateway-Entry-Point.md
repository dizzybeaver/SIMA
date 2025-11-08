# LESS-03-Gateway-Entry-Point.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Lesson on always entering system through gateway  
**Category:** Core Architecture Lesson  
**Type:** Best Practice

---

## LESSON

**Always enter the SUGA system through the gateway layer, never directly into interface or core layers.**

---

## CONTEXT

When building systems with SUGA architecture, there's temptation to call interface or core functions directly, especially during development or debugging. This bypasses the gateway layer and loses critical benefits.

---

## DISCOVERY

### The Problem

**Initial Approach:**
```python
# router.py - handling incoming requests

def handle_request(event):
    # Shortcut during development
    import interface_cache  # ❌ Direct interface access
    data = interface_cache.get('key')
    
    import cache_core  # ❌ Even worse - direct core access
    value = cache_core.get_impl('key')
    
    return {'statusCode': 200, 'body': value}
```

**What Went Wrong:**
- Lost lazy import benefits
- No consistent entry point
- Hard to add gateway middleware
- Mixed abstraction levels
- Testing complexity

### The Discovery

After deployment, realized:
1. No consistent import pattern across handlers
2. Some code used gateway, some didn't
3. Hard to add logging to all operations
4. Performance varied by entry method
5. Circular import risks increased

### The Solution

**Systematic Gateway Entry:**
```python
# router.py - ALWAYS through gateway

def handle_request(event):
    import gateway  # ✓ Single entry point
    data = gateway.cache_get('key')  # ✓ Through gateway
    return {'statusCode': 200, 'body': data}

# gateway.py provides unified interface
def cache_get(key):
    import interface_cache
    return interface_cache.get(key)
```

---

## PRINCIPLE

### Gateway as Single Entry Point

**Mental Model:**
```
External World
    ↓
Gateway Layer (YOU ARE HERE)
    ↓
Interface Layer
    ↓
Core Layer
```

**The Gateway is Your Front Door:**
- Always enter through front door
- Never climb through windows (direct imports)
- Never use back door (bypass interface)
- One way in, properly managed

---

## BENEFITS

### 1. Consistent Access Pattern

```python
# All code uses same pattern
import gateway

result1 = gateway.cache_get(key)
result2 = gateway.http_post(url, data)
result3 = gateway.log_info(message)

# Easy to understand
# Easy to maintain
# Easy to modify
```

### 2. Centralized Control

```python
# gateway.py becomes control point

def cache_get(key):
    """Single place to modify cache access."""
    # Can add features here affecting all callers:
    # - Metrics
    # - Logging
    # - Validation
    # - Circuit breakers
    # - Rate limiting
    
    import interface_cache
    return interface_cache.get(key)
```

### 3. Easy Testing

```python
# Test gateway once
def test_gateway_cache_get():
    assert gateway.cache_get('key') == 'value'

# Mock gateway for integration tests
def test_feature():
    with mock.patch('gateway.cache_get') as mock_cache:
        mock_cache.return_value = 'test_value'
        result = feature_using_cache()
        assert result == expected
```

### 4. Lazy Import Benefits

```python
# Gateway layer handles lazy imports
def cache_get(key):
    import interface_cache  # Only imports when called
    return interface_cache.get(key)

# Callers don't worry about imports
result = gateway.cache_get(key)  # Simple call
```

---

## IMPLEMENTATION

### Rule 1: Gateway Only

```python
# ✓ CORRECT
import gateway
result = gateway.cache_get(key)

# ❌ WRONG
import interface_cache
result = interface_cache.get(key)

# ❌ WORSE
import cache_core
result = cache_core.get_impl(key)
```

### Rule 2: One Gateway Import

```python
# ✓ CORRECT - Single gateway import
import gateway

def handler(event):
    data = gateway.cache_get('key')
    gateway.log_info('Processing')
    return gateway.http_post(url, data)

# ❌ WRONG - Multiple direct imports
import interface_cache
import interface_logging
import interface_http

def handler(event):
    data = interface_cache.get('key')
    interface_logging.log_info('Processing')
    return interface_http.post(url, data)
```

### Rule 3: No Layer Skipping

```python
# ✓ CORRECT - Through all layers
gateway.cache_get(key)
    → interface_cache.get(key)
        → cache_core.get_impl(key)

# ❌ WRONG - Skip interface
gateway.cache_get(key)
    → cache_core.get_impl(key)

# ❌ WORSE - Skip gateway and interface
cache_core.get_impl(key)
```

---

## COMMON MISTAKES

### Mistake 1: "Just This Once"

```python
# Developer thinks: "I'll import directly just this once"
def debug_function():
    import cache_core  # ❌ "Just for debugging"
    return cache_core.get_impl('debug_key')

# Problem: "Just this once" becomes permanent
# Problem: Others copy the pattern
# Problem: Lost benefits
```

**Fix:**
```python
def debug_function():
    import gateway  # ✓ Even for debugging
    return gateway.cache_get('debug_key')
```

### Mistake 2: "It's Internal"

```python
# Developer thinks: "This is internal code, so direct import OK"
def internal_helper():
    import interface_cache  # ❌ "It's just internal"
    return interface_cache.get('key')

# Problem: "Internal" code gets called from external code
# Problem: Lost abstraction
```

**Fix:**
```python
def internal_helper():
    import gateway  # ✓ Always through gateway
    return gateway.cache_get('key')
```

### Mistake 3: "Performance"

```python
# Developer thinks: "Direct import is faster"
def hot_path():
    import cache_core  # ❌ "Bypass layers for speed"
    return cache_core.get_impl('key')

# Problem: Premature optimization
# Problem: Measured difference: <1ms
# Problem: Lost benefits not worth tiny speedup
```

**Fix:**
```python
def hot_path():
    import gateway  # ✓ Gateway overhead is negligible
    return gateway.cache_get('key')

# If REALLY needed (rare), optimize gateway function
```

---

## VERIFICATION

### Code Review Checklist

- [ ] All code imports `gateway` (not interfaces or cores)
- [ ] No direct `import interface_*` from application code
- [ ] No direct `import *_core` from application code
- [ ] Gateway provides all needed functions
- [ ] Tests use gateway for integration

### Automated Checking

```python
# check_gateway_usage.py
import ast
import sys

def check_imports(filename):
    """Verify code only imports gateway."""
    with open(filename) as f:
        tree = ast.parse(f.read())
    
    violations = []
    for node in ast.walk(tree):
        if isinstance(node, ast.Import):
            for alias in node.names:
                if alias.name.startswith('interface_'):
                    violations.append(f"Direct interface import: {alias.name}")
                if alias.name.endswith('_core'):
                    violations.append(f"Direct core import: {alias.name}")
    
    return violations

# Usage
if violations := check_imports('router.py'):
    print("Gateway entry violations:")
    for v in violations:
        print(f"  - {v}")
    sys.exit(1)
```

---

## EXCEPTIONS

### When Direct Import is OK

**1. Within Layer:**
```python
# gateway_wrappers.py
import gateway_core  # ✓ Same layer
```

**2. Tests:**
```python
# test_cache_core.py
import cache_core  # ✓ Direct testing of core
```

**3. Implementation Files:**
```python
# interface_cache.py
import cache_core  # ✓ Interface→Core is correct flow
```

---

## RELATED PATTERNS

- **ARCH-01**: Gateway trinity establishes entry point
- **DEC-02**: Three-layer pattern requires gateway entry
- **DEC-03**: Gateway mandatory for all operations

---

## RELATED ANTI-PATTERNS

- **AP-01**: Direct core import
- **AP-04**: Skipping interface layer

---

## IMPACT

### Before Applying Lesson

- Mixed entry points
- Inconsistent patterns
- Hard to add features
- Testing complex
- Circular import risks

### After Applying Lesson

- Single entry point
- Consistent patterns
- Easy to add features
- Testing simple
- Clean dependencies

---

## MEASUREMENT

### Success Metrics

- **Import Pattern**: 100% of application code imports gateway
- **Consistency**: Same pattern across all handlers
- **Maintainability**: Can add gateway features affecting all callers
- **Testing**: Integration tests mock gateway only

---

## VERSIONING

**v1.0.0**: Initial lesson documentation
- Established gateway entry principle
- Documented benefits and mistakes
- Added verification methods

---

## CHANGELOG

### 2025-11-06
- Created lesson document
- Added examples and anti-examples
- Provided verification checklist

---

**Related Documents:**
- ARCH-01-Gateway-Trinity.md
- DEC-02-Three-Layer-Pattern.md
- DEC-03-Gateway-Mandatory.md
- AP-01-Direct-Core-Import.md
- AP-04-Skipping-Interface-Layer.md

**Keywords:** gateway, entry point, single responsibility, access pattern, consistency

**Category:** Core Architecture Lesson  
**Type:** Best Practice  
**Impact:** High  
**Application:** Universal in SUGA

# NM05-AntiPatterns-Import_AP-04.md - AP-04

# AP-04: Circular Imports via Gateway

**Category:** Anti-Patterns
**Topic:** Import
**Severity:** 🔴 Critical
**Status:** Active
**Created:** 2024-10-15
**Last Updated:** 2025-10-23

---

## Summary

Creating circular import loops even when using the gateway pattern correctly. The gateway prevents MOST circular dependencies, but violating dependency layer rules can still create import cycles.

---

## The Anti-Pattern

**What NOT to do:**
```python
# ❌ WRONG - Circular dependency through gateway

# In http_client_core.py (Layer 5: HTTP)
import gateway
def make_request(url):
    gateway.log_info(f"Requesting {url}")  # Calls down to Layer 0
    gateway.validate_url(url)  # Calls to Layer 7 (HIGHER layer!)
    # ^ This is the problem: Layer 5 calling Layer 7

# In validation_core.py (Layer 7: VALIDATION)
import gateway
def validate_url(url):
    response = gateway.http_head(url)  # Calls back to Layer 5!
    # ^ Circular: Layer 7 → Layer 5 → Layer 7
```

**Why it's bad:**
1. **Import Deadlock**: Python cannot resolve circular dependencies
2. **Initialization Failure**: Module partially initialized, causing AttributeError
3. **Unpredictable Behavior**: Order-dependent bugs
4. **Hard to Debug**: Error messages unclear about actual cause
5. **Architecture Violation**: Breaks dependency layer rules

---

## What to Do Instead

**Correct approach - Follow dependency layers:**
```python
# ✅ CORRECT - Respect dependency layers

# In validation_core.py (Layer 7: VALIDATION)
import gateway
def validate_url(url):
    # Only call SAME or LOWER layers
    gateway.log_debug(f"Validating {url}")  # Layer 0 ✅
    
    # Don't call higher layers like HTTP
    # If you need HTTP, you're in wrong layer!
    
    # Use simple validation instead
    import re
    pattern = r'^https?://.+'
    return bool(re.match(pattern, url))

# In http_client_core.py (Layer 5: HTTP)
import gateway
def make_request(url):
    # Call VALIDATION (Layer 7 - higher) ✅
    if not gateway.validate_url(url):
        raise ValueError("Invalid URL")
    
    gateway.log_info(f"Requesting {url}")  # Layer 0 ✅
    # Make request...
```

**Dependency layer rule:**
> Layer N can call Layer N (same) or Layer < N (lower), but NEVER Layer > N (higher)

---

## The Dependency Layers

```
Layer 0: LOGGING (base - no dependencies)
Layer 1: METRICS (depends on: LOGGING)
Layer 2: SECURITY (depends on: LOGGING, METRICS)
Layer 3: CACHE (depends on: LOGGING)
Layer 4: CONFIG (depends on: LOGGING, SECURITY, CACHE)
Layer 5: HTTP_CLIENT (depends on: all lower)
Layer 6: PERSISTENCE (depends on: all lower)
Layer 7: VALIDATION (depends on: all lower)
Layer 8: ROUTER (depends on: ALL interfaces)
```

**Safe calls:**
- Layer 5 → Layer 0-5 ✅
- Layer 7 → Layer 0-7 ✅
- Layer 0 → Nothing (base layer) ✅

**Dangerous calls:**
- Layer 5 → Layer 7 ❌
- Layer 3 → Layer 6 ❌
- Layer 0 → Layer 1 ❌

---

## Real-World Example

**Context:** BUG-02 in production

**Problem:**
```python
# In cache_core.py (Layer 3)
import gateway
def get_value(key):
    # Tried to validate key format
    if not gateway.validate_cache_key(key):  # Calls Layer 7!
        raise ValueError("Invalid key")
    return _cache_store.get(key)

# In validation_core.py (Layer 7)
import gateway
def validate_cache_key(key):
    # Tried to check cache for known bad keys
    bad_keys = gateway.cache_get("bad_keys_list")  # Calls Layer 3!
    return key not in bad_keys
```

**What went wrong:**
```
ImportError: cannot import name 'cache_get' from partially initialized module 'gateway'
```

- Layer 3 (CACHE) → Layer 7 (VALIDATION) → Layer 3 (CACHE)
- Circular dependency caused import failure
- Lambda failed to initialize
- 100% error rate for 20 minutes

**Solution:**
```python
# In cache_core.py (Layer 3)
def get_value(key):
    # Simple validation without cross-layer call
    if not isinstance(key, str) or not key:
        raise ValueError("Invalid key")
    return _cache_store.get(key)

# In validation_core.py (Layer 7)
def validate_cache_key(key):
    # Don't call back to cache
    # Use simple rules
    return isinstance(key, str) and 0 < len(key) < 256
```

**Result:**
- No circular dependency
- Clean initialization
- Layer rules preserved

---

## How to Identify

**Code smells:**
- Import errors mentioning "partially initialized module"
- AttributeError on gateway functions during import
- Initialization order problems
- Mysterious import failures in Lambda cold starts

**Detection:**
```bash
# Check dependency layers
python -c "
import ast
import sys

def get_imports(filename):
    with open(filename) as f:
        tree = ast.parse(f.read())
    return [node.module for node in ast.walk(tree) 
            if isinstance(node, ast.ImportFrom)]

# Check each core file's imports
for file in *_core.py:
    imports = get_imports(file)
    print(f'{file}: {imports}')
"
```

**Layer validation:**
Map each file to its layer, verify no upward calls.

---

## Prevention Checklist

Before adding gateway call in core module:
- [ ] What layer is my current module?
- [ ] What layer is the function I'm calling?
- [ ] Am I calling same or lower layer only?
- [ ] If calling higher layer, can I restructure?
- [ ] Is this creating a circular dependency?

---

## Related Topics

- **NM02-CORE**: Dependency layers (DEP-01 to DEP-08)
- **BUG-02**: Circular import bug (real incident)
- **RULE-01**: Gateway-only imports (helps but doesn't prevent this)
- **AP-01**: Direct cross-interface imports (different issue)
- **DEC-01**: SUGA pattern choice (why layers matter)

---

## Keywords

circular imports, dependency layers, import deadlock, initialization failure, layer violations

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2024-10-15**: Anti-pattern documented in NM05-Anti-Patterns_Part_1.md

---

**File:** `NM05-AntiPatterns-Import_AP-04.md`
**End of Document**

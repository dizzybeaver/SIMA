# NM05-AntiPatterns-Import_AP-05.md - AP-05

# AP-05: Importing from lambda_function

**Category:** Anti-Patterns
**Topic:** Import
**Severity:** 🔴 Critical
**Status:** Active
**Created:** 2024-10-15
**Last Updated:** 2025-10-23

---

## Summary

Importing anything from lambda_function.py (the Lambda entry point) into other modules. The entry point should import FROM modules, never the reverse.

---

## The Anti-Pattern

**What NOT to do:**
```python
# ❌ WRONG - Importing from lambda entry point

# In some_core.py
from lambda_function import lambda_handler, some_helper
# ^ This creates circular dependency at system entry!

def my_function():
    # Using functions from lambda entry
    result = some_helper()
    return result
```

**Why it's bad:**
1. **Circular Dependency at Entry**: Lambda entry is the TOP of dependency tree
2. **Import Deadlock**: lambda_function imports your module, you import lambda_function
3. **Breaks Entry Point Pattern**: Entry points should be leaves, not branches
4. **Testing Nightmare**: Cannot import entry point in tests without side effects
5. **Initialization Order**: Undefined behavior, race conditions

---

## What to Do Instead

**Correct approach - Move shared code:**
```python
# ✅ CORRECT - Extract shared code to utility module

# In utility_helpers.py (new file)
def some_helper():
    """Shared helper function."""
    return "result"

# In lambda_function.py
from utility_helpers import some_helper

def lambda_handler(event, context):
    result = some_helper()
    return result

# In some_core.py
from utility_helpers import some_helper  # Import from utility, not entry!

def my_function():
    result = some_helper()
    return result
```

**Why this is better:**
- No circular dependency
- Clear separation: entry point vs shared code
- Testable (can import utility without triggering Lambda)
- Follows standard entry point pattern
- Predictable initialization order

---

## The Entry Point Pattern

**Correct dependency flow:**
```
lambda_function.py (TOP - depends on everything)
    ↓
gateway.py
    ↓
interface_*.py
    ↓
*_core.py (BOTTOM - depends on nothing above)
```

**Wrong dependency flow:**
```
lambda_function.py
    ↓
some_core.py
    ↑ ← WRONG! Circular dependency
lambda_function.py
```

**Rule:** Entry points are **consumers**, not **providers**. They use modules but are never imported.

---

## Real-World Example

**Context:** Shared initialization code in lambda_function.py

**Problem:**
```python
# In lambda_function.py
def init_gateway():
    """Initialize gateway with config."""
    import gateway
    gateway.initialize()

def lambda_handler(event, context):
    init_gateway()
    # Handle request...

# In test_integration.py
from lambda_function import init_gateway  # Trying to reuse init!

def test_setup():
    init_gateway()  # Reuse Lambda's init
    # Run tests...
```

**What went wrong:**
- Importing lambda_function in tests triggered AWS Lambda runtime expectations
- Tests tried to set up Lambda context
- Got error: "Unable to import module 'lambda_function': No module named 'lambda_runtime'"
- Tests failed because they're not running in Lambda environment

**Solution:**
```python
# In initialization_helpers.py (NEW FILE)
def init_gateway():
    """Initialize gateway with config."""
    import gateway
    gateway.initialize()

# In lambda_function.py
from initialization_helpers import init_gateway

def lambda_handler(event, context):
    init_gateway()
    # Handle request...

# In test_integration.py
from initialization_helpers import init_gateway  # Import helper, not entry!

def test_setup():
    init_gateway()
    # Run tests... ✅ Works!
```

**Result:**
- Tests run successfully outside Lambda
- No circular dependencies
- Entry point stays clean
- Init code reusable everywhere

---

## AWS Lambda Considerations

**Lambda entry point special properties:**
- Lambda runtime imports it automatically
- Sets up AWS context (event, context parameters)
- May have environment-specific code
- Should be thin wrapper around actual logic

**Why importing it breaks things:**
- Lambda runtime code executes on import
- AWS SDK initialization may fail outside Lambda
- Environment variables may not exist
- Context objects not available

**Best practice:**
```python
# lambda_function.py should be minimal
from router import route_request

def lambda_handler(event, context):
    """AWS Lambda entry point - thin wrapper."""
    return route_request(event, context)
```

All actual logic in importable modules like `router.py`.

---

## How to Identify

**Code smells:**
- `from lambda_function import` anywhere
- Shared functions in lambda_function.py
- Tests that import lambda_function
- Circular import errors involving lambda_function

**Detection:**
```bash
# Find imports from lambda_function
grep -r "from lambda_function import" *.py

# Should return zero results!
```

**Test indicator:**
If your tests import lambda_function, you're doing it wrong.

---

## Migration Path

**If you have shared code in lambda_function.py:**

1. **Identify shared functions** (used by lambda_function AND other modules)
2. **Create utility module** (e.g., `lambda_helpers.py`)
3. **Move shared functions** to utility module
4. **Update imports** in lambda_function.py
5. **Update imports** in consuming modules
6. **Verify** no more `from lambda_function import`

---

## Related Topics

- **ARCH-06**: Lambda entry point (correct pattern)
- **AP-04**: Circular imports (what this causes)
- **RULE-01**: Gateway-only imports (entry point uses gateway too)
- **NM02-CORE**: Dependency layers (entry point is layer 9 - top)
- **LESS-01**: Read complete files first (understand entry point role)

---

## Keywords

lambda entry point, circular dependency, entry point pattern, AWS Lambda, import deadlock

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2024-10-15**: Anti-pattern documented in NM05-Anti-Patterns_Part_1.md

---

**File:** `NM05-AntiPatterns-Import_AP-05.md`
**End of Document**

# AP-28-Relative-Imports-Lambda.md

**Version:** 1.0.0  
**Date:** 2025-12-07  
**Purpose:** Anti-pattern for using relative imports in AWS Lambda  
**Category:** Anti-Pattern  
**Severity:** Critical

---

## ANTI-PATTERN

**Using relative imports (`from . import`, `from .. import`) in AWS Lambda with subdirectory structure.**

---

## DESCRIPTION

Using Python relative imports (dot notation) in Lambda functions with subdirectory organization. These imports consistently FAIL in Lambda's execution environment.

---

## WHY IT'S WRONG

### Relative Imports FAIL in Lambda

1. **Import Resolution Failure**: Lambda cannot resolve relative paths correctly
2. **Runtime Errors**: `ImportError: attempted relative import with no known parent package`
3. **Inconsistent Behavior**: Works locally, fails in Lambda
4. **No Workarounds**: No amount of sys.path manipulation fixes relative imports
5. **Extensively Tested**: User has confirmed they NEVER work in Lambda

### Technical Reasons

1. Lambda's import system requires absolute paths
2. Package structure not fully recognized by Lambda runtime
3. Module __name__ doesn't resolve correctly for relative imports
4. sys.path doesn't help with relative imports
5. Lambda's execution context differs from standard Python

---

## REAL-WORLD EXAMPLE

### ❌ WRONG: Relative Imports (ALWAYS FAIL)

```python
# In home_assistant/__init__.py
from .ha_config import HA_ENABLED  # ❌ FAILS
from . import ha_interconnect      # ❌ FAILS

if HA_ENABLED:
    from .ha_alexa import ha_interface_alexa  # ❌ FAILS
    __all__ = ['ha_interconnect', 'HA_ENABLED']
```

```python
# In home_assistant/ha_alexa/__init__.py
from .ha_interface_alexa import handle_directive  # ❌ FAILS
from .ha_alexa_core import process               # ❌ FAILS

__all__ = ['handle_directive']
```

```python
# In home_assistant/ha_alexa/ha_alexa_core.py
from .ha_alexa_helpers import extract_entity_id  # ❌ FAILS
from ..ha_devices.ha_devices_core import control # ❌ FAILS
```

**Runtime Error:**
```
ImportError: attempted relative import with no known parent package
ModuleNotFoundError: No module named '__main__.ha_config'
```

### ✅ CORRECT: Full Path Imports (ALWAYS WORK)

```python
# In home_assistant/__init__.py
from home_assistant.ha_config import HA_ENABLED  # ✅ WORKS
import home_assistant.ha_interconnect as ha_interconnect  # ✅ WORKS

if HA_ENABLED:
    import home_assistant.ha_alexa.ha_interface_alexa as ha_interface_alexa  # ✅ WORKS
    __all__ = ['ha_interconnect', 'HA_ENABLED']
```

```python
# In home_assistant/ha_alexa/__init__.py
from home_assistant.ha_alexa.ha_interface_alexa import handle_directive  # ✅ WORKS
from home_assistant.ha_alexa.ha_alexa_core import process  # ✅ WORKS

__all__ = ['handle_directive']
```

```python
# In home_assistant/ha_alexa/ha_alexa_core.py
from home_assistant.ha_alexa.ha_alexa_helpers import extract_entity_id  # ✅ WORKS
from home_assistant.ha_devices.ha_devices_core import control  # ✅ WORKS
```

**With Required sys.path Setup:**
```python
# In lambda_function.py (REQUIRED)
import sys
import os
ROOT_DIR = os.path.dirname(os.path.abspath(__file__))
if ROOT_DIR not in sys.path:
    sys.path.insert(0, ROOT_DIR)

# Now all full path imports work
```

---

## SEVERITY METRICS

### Impact

| Aspect | Severity | Details |
|--------|----------|---------|
| Runtime Failure | Critical | Import errors break Lambda |
| Detection | Hard | Works locally, fails in Lambda |
| Debugging | Difficult | Error messages unclear |
| Fix Complexity | Easy | Replace with full paths |
| Frequency | Common | Natural Python pattern |

---

## IDENTIFICATION

### Code Smell Indicators

```python
# ANY of these patterns are WRONG in Lambda:

from . import module              # ❌ Relative import
from .module import function      # ❌ Relative import
from ..package import module      # ❌ Parent relative import
from ...package import module     # ❌ Grandparent relative import
import .module                    # ❌ Relative module import

# In __init__.py files
from . import *                   # ❌ Relative wildcard
from .submodule import *          # ❌ Relative wildcard
```

### Detection Script

```bash
# Find all relative imports in project
grep -r "from \." *.py
grep -r "from \.\." *.py
grep -r "import \." *.py

# Should return ZERO results for Lambda projects
```

---

## CORRECTION

### Step 1: Add sys.path Setup

```python
# In lambda_function.py (FIRST, before any imports)
import sys
import os
ROOT_DIR = os.path.dirname(os.path.abspath(__file__))
if ROOT_DIR not in sys.path:
    sys.path.insert(0, ROOT_DIR)
```

### Step 2: Replace Relative Imports

**Pattern:**
```python
# ❌ Remove
from .module import func

# ✅ Add
from package.module import func
```

**Examples:**
```python
# ❌ Remove
from .ha_config import HA_ENABLED
from . import ha_interconnect
from ..ha_alexa import ha_interface_alexa

# ✅ Add
from home_assistant.ha_config import HA_ENABLED
import home_assistant.ha_interconnect as ha_interconnect
import home_assistant.ha_alexa.ha_interface_alexa as ha_interface_alexa
```

### Step 3: Update __init__.py Files

```python
# Before (relative)
from . import module
from .submodule import func

# After (full path)
from package import module
from package.submodule import func
```

### Step 4: Update Internal Imports

```python
# In home_assistant/ha_alexa/ha_alexa_core.py

# Before (relative)
from .ha_alexa_helpers import func
from ..ha_devices.ha_devices_core import control

# After (full path)
from home_assistant.ha_alexa.ha_alexa_helpers import func
from home_assistant.ha_devices.ha_devices_core import control
```

### Step 5: Test in Lambda

```bash
# Package and deploy
zip -r deployment.zip .

# Deploy to Lambda
aws lambda update-function-code --function-name my-function --zip-file fileb://deployment.zip

# Test invocation
aws lambda invoke --function-name my-function output.json

# Verify no import errors
```

---

## CRITICAL RULES

### Rule 1: NEVER Use Relative Imports

**These NEVER work in Lambda:**
- `from . import`
- `from .module import`
- `from .. import`
- `from ..package import`
- `import .module`

### Rule 2: ALWAYS Use Full Paths

**Pattern:**
```python
from {package}.{subpackage}.{module} import {item}
import {package}.{subpackage}.{module} as {alias}
```

### Rule 3: sys.path Required

**Must be in lambda_function.py:**
```python
import sys
import os
ROOT_DIR = os.path.dirname(os.path.abspath(__file__))
if ROOT_DIR not in sys.path:
    sys.path.insert(0, ROOT_DIR)
```

### Rule 4: Applies Everywhere

**Even within subdirectories:**
- In __init__.py files
- In module files
- In helper files
- In ALL files

---

## TESTING VERIFICATION

### Local Testing

```python
# Test imports locally
python3 -c "from home_assistant.ha_config import HA_ENABLED; print('OK')"

# Should print: OK
```

### Lambda Testing

```python
# In Lambda test
import json

def lambda_handler(event, context):
    # Test import
    from home_assistant.ha_config import HA_ENABLED
    return {'statusCode': 200, 'body': json.dumps('Import works')}
```

### CI/CD Check

```bash
# Add to CI/CD pipeline
if grep -r "from \.\." *.py; then
    echo "ERROR: Relative imports found!"
    exit 1
fi
```

---

## COMMON MISTAKES

### Mistake 1: Copy from Standard Python

```python
# Works in standard Python
from . import module

# Doesn't work in Lambda - must use:
from package import module
```

### Mistake 2: Following PEP Examples

```python
# PEP 328 recommends relative imports
from . import sister
from .. import parent

# Doesn't work in Lambda - must use:
from package import sister
from package.parent import parent
```

### Mistake 3: IDE Suggestions

```python
# IDE may suggest relative import
from .config import SETTING  # ❌ IDE suggestion

# Must manually change to:
from package.config import SETTING  # ✅ Lambda-compatible
```

---

## RELATED PATTERNS

- **DEC-17:** Directory organization with full path imports
- **Extension-Structure-Pattern:** Extension import standards
- **AP-05:** File structure organization

---

## IMPACT

### Before Correction (Relative Imports)

- Runtime import failures
- Lambda function crashes
- Inconsistent local/Lambda behavior
- Hard to debug errors
- Deployment failures

### After Correction (Full Paths)

- All imports work reliably
- Consistent behavior everywhere
- Clear import paths
- Easy to understand
- Successful deployments

---

**Related Documents:**
- DEC-17-Directory-Organization.md
- Extension-Structure-Pattern.md
- AP-05-Flat-File-Structure.md

**Keywords:** relative-imports, lambda-imports, import-errors, subdirectory-imports, full-path-imports, aws-lambda

**Category:** Anti-Pattern  
**Severity:** Critical  
**Detection:** grep "from \." *.py  
**Correction:** Replace with full path imports + sys.path setup

---

## VERSION HISTORY

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | 2025-12-07 | Initial anti-pattern (extensively tested by user) |

---

**END OF FILE**

**Summary:** NEVER use relative imports in Lambda. ALWAYS use full path imports with sys.path setup in lambda_function.py.

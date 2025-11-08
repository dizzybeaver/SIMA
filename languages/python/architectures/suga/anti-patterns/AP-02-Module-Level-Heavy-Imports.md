# AP-02-Module-Level-Heavy-Imports.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Anti-pattern for module-level imports of heavy libraries  
**Category:** Anti-Pattern  
**Severity:** High

---

## ANTI-PATTERN

**Importing heavy libraries at module level instead of lazy loading them at function level.**

---

## DESCRIPTION

Placing import statements for heavy libraries (large dependencies, slow initialization) at the top of the module causes them to load during module initialization, increasing cold start time.

---

## WHY IT'S WRONG

### Performance Impact

1. **Cold Start Penalty**: Every import adds to initialization time
2. **Unnecessary Loading**: Imports executed even if function never called
3. **Memory Waste**: Loaded modules consume memory even if unused
4. **Cascading Delays**: Heavy imports can trigger their own heavy dependencies

### SUGA-Specific Issues

In SUGA architecture:
- Gateway layer should be lightweight
- Interface layer should load quickly
- Core layer can lazy load as needed
- Fast path must stay minimal

---

## REAL-WORLD EXAMPLE

### ❌ WRONG: Module-Level Heavy Import

```python
# interface_http.py
import json
import requests  # ❌ Heavy library loaded at module import
import boto3     # ❌ Very heavy, loads even if never used
from bs4 import BeautifulSoup  # ❌ Heavy parser

def http_get_impl(url):
    """Make HTTP GET request."""
    response = requests.get(url)
    return response.json()

def parse_html(html):
    """Parse HTML - rarely called."""
    return BeautifulSoup(html, 'html.parser')

# Problem: boto3, BeautifulSoup imported even if only using http_get
```

**Impact:**
- Cold start: +800ms (requests) + 1200ms (boto3) + 400ms (bs4) = +2400ms
- Every module import pays this cost
- Functions like `parse_html` rarely used but always loaded

### ✓ CORRECT: Function-Level Lazy Imports

```python
# interface_http.py
import json  # ✓ Lightweight, needed often

def http_get_impl(url):
    """Make HTTP GET request."""
    import requests  # ✓ Lazy load only when needed
    response = requests.get(url)
    return response.json()

def parse_html(html):
    """Parse HTML - rarely called."""
    import bs4  # ✓ Only loaded if function called
    return bs4.BeautifulSoup(html, 'html.parser')

def upload_to_s3(bucket, key, data):
    """Upload to S3 - admin function only."""
    import boto3  # ✓ Only loaded for admin operations
    s3 = boto3.client('s3')
    s3.put_object(Bucket=bucket, Key=key, Body=data)

# Benefit: Only load what you need, when you need it
```

**Impact:**
- Cold start if only using http_get: +800ms (just requests)
- Cold start if never using http functions: 0ms
- Memory savings: ~50MB (boto3 not loaded)

---

## SEVERITY METRICS

### Performance Cost

| Library | Import Time | Memory | When to Lazy Load |
|---------|------------|--------|-------------------|
| requests | ~800ms | 10MB | If not used on every request |
| boto3 | ~1200ms | 45MB | Always lazy load |
| pandas | ~2000ms | 80MB | Always lazy load |
| numpy | ~600ms | 25MB | If used rarely |
| PIL/Pillow | ~400ms | 15MB | If used rarely |
| beautifulsoup4 | ~400ms | 8MB | Always lazy load |

### Severity Classification

**High Severity** (>500ms + >20MB):
- boto3, pandas, tensorflow, torch
- Always lazy load these

**Medium Severity** (200-500ms + 10-20MB):
- requests, numpy, PIL, lxml
- Lazy load if not used frequently

**Low Severity** (<200ms + <10MB):
- json, datetime, uuid, collections
- Module-level import acceptable

---

## IDENTIFICATION

### Code Smell Indicators

```python
# Suspicious patterns at top of file:
import boto3            # ❌ Heavy AWS library
import pandas as pd     # ❌ Very heavy data library
import numpy as np      # ❌ Heavy numerical library
import requests         # ⚠ Heavy HTTP library
from PIL import Image   # ⚠ Heavy image library
```

### Performance Testing

```python
# Measure import time
import time

start = time.time()
import boto3
end = time.time()
print(f"boto3 import: {(end - start) * 1000}ms")

# If >200ms, consider lazy loading
```

---

## CORRECTION

### Step 1: Identify Heavy Imports

Profile module imports:
```python
import sys
import time

def profile_imports():
    for module in ['boto3', 'requests', 'pandas']:
        if module not in sys.modules:
            start = time.time()
            __import__(module)
            duration = (time.time() - start) * 1000
            print(f"{module}: {duration:.0f}ms")
```

### Step 2: Move to Function Level

```python
# Before
import heavy_library

def function():
    return heavy_library.do_work()

# After
def function():
    import heavy_library  # Lazy load
    return heavy_library.do_work()
```

### Step 3: Add Import Caching (Optional)

For frequently called functions:
```python
_heavy_lib_cache = None

def function():
    global _heavy_lib_cache
    if _heavy_lib_cache is None:
        import heavy_library
        _heavy_lib_cache = heavy_library
    return _heavy_lib_cache.do_work()
```

### Step 4: Document Why

```python
def admin_function():
    """Admin-only function requiring boto3.
    
    Note: boto3 imported at function level due to:
    - High import cost (~1200ms)
    - Large memory footprint (45MB)
    - Rarely used in normal operations
    """
    import boto3  # Lazy load: +1200ms only when needed
    return boto3.client('s3')
```

---

## WHEN TO USE LAZY IMPORTS

### Always Lazy Load

- boto3 (AWS SDK)
- pandas (data analysis)
- numpy (numerical computing)
- tensorflow/torch (ML frameworks)
- beautifulsoup4 (HTML parsing)
- Any library >500ms import time

### Conditionally Lazy Load

- requests (common but heavy)
- PIL/Pillow (image processing)
- lxml (XML parsing)
- selenium (browser automation)

### Never Lazy Load (Module Level OK)

- json (stdlib, fast)
- datetime (stdlib, fast)
- collections (stdlib, fast)
- typing (stdlib, fast)
- Any stdlib module <50ms

---

## EXCEPTIONS

### When Module-Level is Better

**High Frequency Usage:**
```python
# If used on EVERY request, module-level OK
import json  # Used in every API response
import logging  # Used throughout execution
```

**Startup Validation:**
```python
# If checking availability at startup
try:
    import required_library
except ImportError:
    raise RuntimeError("required_library must be installed")
```

---

## RELATED PATTERNS

- **LMMS-02**: Lazy Module Management System
- **ARCH-07**: Cold Start Optimization
- **DEC-04**: No Threading (related optimization)

---

## RELATED ANTI-PATTERNS

- **AP-12**: Heavy processing in hot path
- **AP-06**: Eager initialization

---

## RELATED LESSONS

- **LESS-02**: Measure don't guess (profile imports)
- **LESS-17**: Cold start optimization

---

## IMPACT

### Before Correction

- Cold start: 3-5 seconds
- Memory usage: High
- Unused libraries loaded
- Slow initialization

### After Correction

- Cold start: <1 second
- Memory usage: Minimal
- Only needed libraries loaded
- Fast initialization

---

## DETECTION

### Manual Code Review

```bash
# Find heavy imports at module level
grep -r "^import boto3" *.py
grep -r "^import pandas" *.py
grep -r "^from .* import boto3" *.py
```

### Automated Linting

```python
# Add to pylint or custom linter
HEAVY_LIBRARIES = ['boto3', 'pandas', 'numpy', 'tensorflow']

def check_module_level_heavy_imports(file_path):
    with open(file_path) as f:
        for line_num, line in enumerate(f, 1):
            for lib in HEAVY_LIBRARIES:
                if line.startswith(f'import {lib}'):
                    yield f"{file_path}:{line_num}: Heavy import at module level: {lib}"
```

---

## VERSIONING

**v1.0.0**: Initial anti-pattern documentation
- Identified heavy import pattern
- Documented correction steps
- Added severity metrics

---

## CHANGELOG

### 2025-11-06
- Created anti-pattern document
- Added performance impact data
- Provided correction examples

---

**Related Documents:**
- ARCH-02-Layer-Separation.md
- LMMS-02-Cold-Start.md
- LESS-02.md (Measure Don't Guess)

**Keywords:** imports, lazy loading, cold start, performance, boto3, pandas, module-level, function-level

**Category:** Anti-Pattern  
**Severity:** High  
**Detection:** Manual review, automated linting  
**Correction:** Move to function-level lazy imports

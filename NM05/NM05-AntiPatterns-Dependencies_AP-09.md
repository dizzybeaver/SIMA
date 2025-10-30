# NM05-AntiPatterns-Dependencies_AP-09.md - AP-09

# AP-09: Heavy External Libraries

**Category:** NM05 - Anti-Patterns  
**Topic:** Dependencies  
**Priority:** 🔴 Critical  
**Status:** Active  
**Created:** 2024-08-15  
**Last Updated:** 2025-10-24

---

## Summary

Never add heavy external libraries (pandas, numpy, requests, etc.) without strong justification. Lambda has 128MB memory constraint and stdlib should handle 90% of needs. Heavy libraries slow cold starts and may not work at all.

---

## Context

Lambda environment is constrained. Heavy libraries bloat package size, slow cold starts, and may exceed memory limits. Use stdlib unless absolutely necessary.

---

## Content

### The Anti-Pattern

**❌ WRONG:**
```python
import pandas as pd  # 20MB+, not in Lambda
import numpy as np  # 15MB+, not in Lambda  
import requests  # 5MB, not in Lambda (except HA extension)

def analyze_data(data):
    df = pd.DataFrame(data)  # Heavy library for simple task
    return df.describe()
```

### Why It's Wrong

**1. Not Available in Lambda**
- pandas: Not in Lambda environment
- numpy: Not in Lambda environment
- Most ML libraries: Not available
- Would need custom deployment

**2. Exceeds Memory Constraint**
```
Lambda memory limit: 128MB
Base Lambda + code: ~30MB
pandas alone: ~20MB
numpy alone: ~15MB
Total if both: ~65MB (50% of limit!)
```

**3. Slows Cold Start**
```
Without heavy libraries: 150ms cold start
With pandas: 800ms cold start (+550ms)
With pandas + numpy: 1200ms cold start (+1050ms)
```

**4. Deployment Size**
```
Max deployment package: 50MB (compressed)
pandas + numpy: ~35MB compressed
Leaves only 15MB for your code
```

### The Correct Approach

**✅ CORRECT - Use Stdlib:**
```python
import statistics  # Stdlib, always available
import json        # Stdlib, fast
from collections import Counter  # Stdlib, powerful

def analyze_data(data):
    return {
        'mean': statistics.mean(data),
        'median': statistics.median(data),
        'stdev': statistics.stdev(data),
        'count': len(data)
    }
```

### Stdlib Alternatives

**Instead of pandas:**
```python
# CSV parsing
import csv

# Data structures
from collections import defaultdict, Counter, namedtuple

# Statistics
import statistics

# JSON
import json
```

**Instead of numpy:**
```python
# Math operations
import math

# Statistics
import statistics

# List comprehensions
result = [x * 2 for x in data]
```

**Instead of requests (usually):**
```python
# HTTP requests
import urllib.request
import urllib.parse
from http.client import HTTPSConnection
```

### Special Exception: Home Assistant Extension

**ONLY exception to requests ban:**
```python
# In home_assistant/ extension ONLY
import requests  # ✅ Allowed here

# Reason: HA client library requires requests
# Carefully managed, deployment tested
# User opts into this extension
```

**Everywhere else: Use urllib.**

### When Heavy Libraries Seem Necessary

**Question 1: "I need pandas for data analysis"**
- Answer: What analysis exactly?
- 90% can be done with statistics module
- Example: mean, median, stdev all in stdlib

**Question 2: "I need numpy for math"**
- Answer: What math operations?
- math module handles 95% of needs
- Example: sin, cos, sqrt, log all in math module

**Question 3: "I need requests for HTTP"**
- Answer: urllib works fine
- Slightly more verbose but stdlib
- Example below

**Question 4: "I need ML library"**
- Answer: Lambda wrong tool
- Use SageMaker for ML
- Lambda for routing/orchestration only

### Urllib Example

**Instead of requests:**
```python
# ❌ Using requests (not available)
import requests
response = requests.post(url, json=data)

# ✅ Using urllib (stdlib)
import urllib.request
import json

req = urllib.request.Request(
    url,
    data=json.dumps(data).encode(),
    headers={'Content-Type': 'application/json'}
)
with urllib.request.urlopen(req) as response:
    result = json.loads(response.read())
```

### Decision Tree

```
Need external library?
├─ Available in stdlib?
│  ├─ Yes → Use stdlib
│  └─ No → Continue
├─ Critical for function?
│  ├─ No → Don't add
│  └─ Yes → Continue
├─ Fits in memory constraint?
│  ├─ No → Redesign approach
│  └─ Yes → Continue
├─ Accepted cold start cost?
│  ├─ No → Find alternative
│  └─ Yes → Document & justify
```

### Approved Lightweight Libraries

**These are OK (part of deployment):**
```python
# Always available:
import json
import urllib
import datetime
import re
import collections

# In Home Assistant extension only:
import requests  # HA client needs it
```

---

## Related Topics

- **DEC-07**: Dependencies < 128MB constraint
- **PATH-01**: Cold start optimization
- **DEC-10**: Stdlib preference  
- **AP-06**: Custom implementation (sometimes better than library)
- **LESS-06**: Pay small costs early (stdlib learning curve worth it)

---

## Keywords

heavy libraries, pandas, numpy, requests, Lambda constraints, stdlib, memory limits, cold start, dependencies

---

## Version History

- **2025-10-24**: Migrated to SIMA v3 format
- **2024-08-15**: Created - documented heavy library anti-pattern

---

**File:** `NM05-AntiPatterns-Dependencies_AP-09.md`  
**End of Document**

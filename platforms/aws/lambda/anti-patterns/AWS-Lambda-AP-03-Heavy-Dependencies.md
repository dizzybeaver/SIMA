# AWS-Lambda-AP-03-Heavy-Dependencies.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Anti-pattern for including heavy dependencies in AWS Lambda  
**Category:** AWS Lambda Platform Anti-Patterns

---

## ANTI-PATTERN SUMMARY

**Pattern:** Including large, unnecessary dependencies in Lambda deployment package

**Why It's Bad:**
- Increases deployment package size (250MB limit)
- Slows cold starts (longer download + initialization)
- Wastes memory (128MB constraint)
- Increases deployment time
- Complicates dependency management

**Correct Approach:** Use lightweight alternatives, lazy loading, or Lambda layers for large dependencies

---

## THE ANTI-PATTERN

### What It Looks Like

**Example 1: Data Processing with Heavy Libraries**
```python
# requirements.txt
pandas==2.0.0         # 40MB
numpy==1.24.0         # 25MB
scipy==1.10.0         # 35MB
matplotlib==3.7.0     # 45MB
# Total: ~145MB just for dependencies

# lambda_function.py
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt

def lambda_handler(event, context):
    """Simple data processing that doesn't need heavy libraries."""
    
    # Just need to sum some numbers
    data = event['data']
    total = sum(data)
    average = total / len(data)
    
    return {'total': total, 'average': average}
```

**Problems:**
- Cold start: 4-6 seconds (loading large libraries)
- Memory usage: 80MB just for imports
- Deployment size: 145MB
- Could do same work with standard library

**Example 2: AWS SDK Bloat**
```python
# Including full AWS SDK
import boto3  # Includes ALL AWS service clients

def lambda_handler(event, context):
    """Only uses S3, but imports entire SDK."""
    
    s3 = boto3.client('s3')
    s3.get_object(Bucket='bucket', Key='key')
```

**Problems:**
- Loads unused service clients
- Unnecessary memory overhead
- Slower initialization
- boto3 already available in Lambda runtime (no need to package)

---

## WHY IT HAPPENS

### Reason 1: Copy-Paste from Development
```python
# Development requirements.txt (all tools needed)
pandas
numpy
requests
beautifulsoup4
pytest
black
flake8

# Production Lambda: Copies same requirements.txt
# Includes testing/linting tools in production!
```

### Reason 2: "Just In Case" Dependencies
```python
# "Maybe I'll need these someday"
import pandas  # For potential future data processing
import numpy   # For potential future calculations
import requests  # For potential future API calls

def lambda_handler(event, context):
    """Current function doesn't use any of these."""
    return {'message': 'Hello'}
```

### Reason 3: Not Knowing Alternatives Exist
```python
# Heavy: XML parsing with lxml
from lxml import etree  # 12MB library
xml_data = etree.fromstring(xml_string)

# Lightweight: Built-in xml
import xml.etree.ElementTree as ET  # Standard library
xml_data = ET.fromstring(xml_string)
```

---

## IMPACT ANALYSIS

### Performance Impact

**Cold Start Comparison:**
```
Minimal dependencies (5MB):
- Download: 100ms
- Initialization: 200ms
- Total cold start: ~300ms

Heavy dependencies (150MB):
- Download: 2000ms
- Initialization: 3000ms
- Total cold start: ~5000ms

Impact: 16x slower cold starts
```

### Cost Impact

**Scenario:** 1M invocations/month, 20% cold start rate

```
Minimal dependencies:
- Warm invocations: 800K × 100ms = 80K seconds
- Cold invocations: 200K × 300ms = 60K seconds
- Total: 140K seconds
- Cost: 140K × 0.128GB × $0.0000166667 = $0.30

Heavy dependencies:
- Warm invocations: 800K × 500ms = 400K seconds
- Cold invocations: 200K × 5000ms = 1000K seconds
- Total: 1,400K seconds
- Cost: 1,400K × 0.128GB × $0.0000166667 = $2.99

Impact: 10x cost increase from dependencies alone
```

### Deployment Impact

```
Minimal dependencies:
- Package size: 5MB
- Upload time: 2 seconds
- Deployment frequency: Any time

Heavy dependencies:
- Package size: 150MB
- Upload time: 60 seconds
- Deployment frequency: Limited by patience
- CI/CD pipeline: Slower builds
```

---

## THE CORRECT APPROACH

### Solution 1: Use Lightweight Alternatives

**Instead of Pandas:**
```python
# Bad: Import pandas for simple operations
import pandas as pd

def process_data(data):
    df = pd.DataFrame(data)
    return df['value'].sum()

# Good: Use standard library
def process_data(data):
    return sum(item['value'] for item in data)
```

**Instead of Requests:**
```python
# Bad: Import requests (with all dependencies)
import requests

def call_api(url):
    response = requests.get(url)
    return response.json()

# Good: Use urllib3 or standard urllib
import urllib.request
import json

def call_api(url):
    with urllib.request.urlopen(url) as response:
        return json.loads(response.read())
```

**Instead of BeautifulSoup:**
```python
# Bad: Full HTML parsing library
from bs4 import BeautifulSoup

def extract_title(html):
    soup = BeautifulSoup(html, 'html.parser')
    return soup.find('title').text

# Good: Simple regex or string operations (if HTML is simple)
import re

def extract_title(html):
    match = re.search(r'<title>(.*?)</title>', html)
    return match.group(1) if match else None
```

### Solution 2: Lazy Loading

**Load heavy imports only when needed:**
```python
def lambda_handler(event, context):
    """Lazy load heavy dependencies."""
    
    operation = event['operation']
    
    if operation == 'simple':
        # No heavy imports needed
        return simple_operation(event['data'])
    
    elif operation == 'complex':
        # Only import when this path is taken
        import pandas as pd
        import numpy as np
        return complex_operation(event['data'])
    
    # Most requests take simple path (no heavy imports loaded)
```

### Solution 3: Lambda Layers

**Share dependencies across functions:**
```bash
# Create layer with heavy dependencies
mkdir python
pip install pandas numpy -t python/
zip -r dependencies.zip python/
aws lambda publish-layer-version \
  --layer-name data-processing-deps \
  --zip-file fileb://dependencies.zip

# Use layer in functions
aws lambda update-function-configuration \
  --function-name my-function \
  --layers arn:aws:lambda:region:account:layer:data-processing-deps:1
```

**Benefits:**
- Shared across multiple functions
- Not counted toward function package size
- Cached separately (faster subsequent cold starts)
- Updated independently from function code

### Solution 4: Dependency Audit

**Review requirements regularly:**
```bash
# Find unused imports
pip install pipreqs
pipreqs . --force

# Compare with current requirements.txt
# Remove unused dependencies

# Check sizes
pip install pipdeptree
pipdeptree --reverse --packages pandas
# Shows: pandas requires numpy (25MB), pytz (5MB), etc.
```

---

## MIGRATION PATH

### Step 1: Audit Current Dependencies

```python
# Script to analyze Lambda package
import os
import zipfile

def analyze_package(zip_path):
    """Show size breakdown of Lambda package."""
    
    with zipfile.ZipFile(zip_path, 'r') as zip_ref:
        sizes = {}
        for info in zip_ref.filelist:
            # Get top-level directory (package name)
            parts = info.filename.split('/')
            if len(parts) > 1:
                package = parts[0]
                sizes[package] = sizes.get(package, 0) + info.file_size
        
        # Sort by size
        for package, size in sorted(sizes.items(), key=lambda x: x[1], reverse=True):
            print(f"{package}: {size / (1024*1024):.2f} MB")

# Output shows:
# pandas: 42.5 MB
# numpy: 27.3 MB
# requests: 8.2 MB
# Total: 78 MB (excluding code)
```

### Step 2: Identify Alternatives

```python
# For each large dependency, check if:
# 1. It's actually used (not dead code)
# 2. Built-in alternative exists
# 3. Lightweight alternative exists
# 4. Can be moved to Lambda Layer
```

### Step 3: Refactor Incrementally

```python
# Before: Heavy data processing
import pandas as pd
import numpy as np

def lambda_handler(event, context):
    df = pd.DataFrame(event['data'])
    result = df.groupby('category')['value'].sum()
    return result.to_dict()

# After: Lightweight standard library
from itertools import groupby
from operator import itemgetter

def lambda_handler(event, context):
    data = sorted(event['data'], key=itemgetter('category'))
    result = {}
    for category, items in groupby(data, key=itemgetter('category')):
        result[category] = sum(item['value'] for item in items)
    return result
```

### Step 4: Test Performance

```bash
# Compare cold start times
# Before:
START RequestId: abc123
INIT_START Runtime Version: python3.11
INIT_REPORT Duration: 3847.23 ms

# After:
START RequestId: def456
INIT_START Runtime Version: python3.11
INIT_REPORT Duration: 287.45 ms

# Improvement: 13x faster cold starts
```

---

## DEPENDENCY GUIDELINES

### Include If:
- [ ] Actually used in code (not "just in case")
- [ ] No lightweight alternative exists
- [ ] Significantly reduces code complexity
- [ ] Performance benefit justifies cost
- [ ] Total package stays under 50MB

### Don't Include If:
- [ ] Only used in development/testing
- [ ] Built-in alternative exists
- [ ] Adds >10MB for simple task
- [ ] Only used in rare code path (use lazy loading)
- [ ] Available in Lambda runtime (like boto3)

### Lambda Runtime Includes:
- boto3 (AWS SDK)
- botocore (AWS SDK core)
- Python standard library
- OpenSSL
- Basic system libraries

**Don't package these - they're already available!**

---

## MONITORING

### Package Size Tracking

```python
# In CI/CD pipeline
import os

def check_package_size(zip_path):
    """Alert if package too large."""
    
    size_mb = os.path.getsize(zip_path) / (1024 * 1024)
    
    if size_mb > 50:
        print(f"WARNING: Package is {size_mb:.1f}MB")
        print("Consider using Lambda Layers or reducing dependencies")
        return False
    
    return True
```

### Cold Start Monitoring

```python
# Track initialization time
import time

init_start = time.time()
import heavy_dependency
init_time = time.time() - init_start

# Log initialization time
print(f"Initialization: {init_time*1000:.0f}ms")
```

---

## RELATED CONCEPTS

**Cross-References:**
- AWS-Lambda-LESS-01: Cold start optimization
- AWS-Lambda-DEC-02: Memory constraints
- AWS-Lambda-AP-01: Avoid threading (another heavy pattern)

**Keywords:** dependencies, package size, cold start, Lambda layers, lightweight alternatives, deployment size

---

**END OF FILE**

**Location:** `/sima/platforms/aws/lambda/anti-patterns/AWS-Lambda-AP-03-Heavy-Dependencies.md`  
**Version:** 1.0.0  
**Lines:** 391 (within 400-line limit)

# LMMS-03-Import-Strategy.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Import strategy patterns and decision framework  
**Architecture:** LMMS (Python)

---

## IMPORT STRATEGY FRAMEWORK

### Decision Tree

```
For each import, ask:

1. Import time?
   ├── <10ms → Module level
   └── ≥10ms → Continue to Q2

2. Usage frequency?
   ├── Every request → Module level
   ├── >80% requests → Module level
   ├── 20-80% requests → Function level (consider)
   └── <20% requests → Function level

3. Critical path?
   ├── Hot path → Module level (accept cost)
   └── Cold path → Function level

4. Dependencies?
   ├── No heavy deps → Module level
   └── Heavy chain → Function level
```

---

## IMPORT CLASSIFICATION SYSTEM

### Tier 1: Always Module Level

**Criteria:**
- Import time <10ms
- Zero heavy dependencies
- Used frequently

**Examples:**
```python
import json           # <1ms, every request
import time           # <1ms, timing operations
import logging        # <5ms, used everywhere
import os             # <1ms, config access
```

### Tier 2: Module Level (Hot Path)

**Criteria:**
- Import time 10-100ms
- Used on >80% of requests
- Critical for performance

**Examples:**
```python
import gateway        # 15ms, every request uses
import fast_path      # 20ms, core operations
import cache_core     # 35ms, cache on every req
```

### Tier 3: Function Level (Conditional)

**Criteria:**
- Import time 10-100ms
- Used on 20-80% of requests
- Not critical path

**Examples:**
```python
def handle_feature(data):
    import feature_lib  # 45ms, only some requests
    return feature_lib.process(data)
```

### Tier 4: Function Level (Heavy)

**Criteria:**
- Import time >100ms
- Any usage frequency
- Heavy dependencies

**Examples:**
```python
def process_data(data):
    import boto3      # 350ms, AWS SDK
    import pandas     # 1200ms, data analysis
    return process_with_pandas(data)
```

### Tier 5: Function Level (Rare)

**Criteria:**
- Any import time
- Used <20% of requests
- Optional features

**Examples:**
```python
def admin_operation(cmd):
    import admin_tools  # Rare admin commands
    return admin_tools.execute(cmd)
```

---

## IMPORT PATTERNS

### Pattern 1: Standard Import

**When to use:** Tier 1, Tier 2 (hot path)

```python
# Module level
import json
import gateway

def operation():
    return json.dumps(data)
```

**Pros:**
- Simple, clear
- Fast access
- No overhead

**Cons:**
- Paid on every cold start
- Memory always consumed

### Pattern 2: Function-Level Import

**When to use:** Tier 3, Tier 4, Tier 5

```python
def operation():
    import heavy_lib  # Lazy load
    return heavy_lib.process()
```

**Pros:**
- Only pay if used
- Lower cold start
- Memory on demand

**Cons:**
- Slight delay first call
- Import scattered in code
- Testing complexity

### Pattern 3: Conditional Import

**When to use:** Optional features, feature flags

```python
def operation(use_feature=False):
    if use_feature:
        import optional_lib
        return optional_lib.enhanced(data)
    return basic_process(data)
```

**Pros:**
- Never load if disabled
- Feature flag friendly
- Clean fallback

**Cons:**
- Code branching
- Two code paths to test

### Pattern 4: Cached Import

**When to use:** Frequently called lazy import

```python
_cached_module = None

def operation():
    global _cached_module
    if _cached_module is None:
        import heavy_module
        _cached_module = heavy_module
    return _cached_module.process()
```

**Pros:**
- Import once per lifetime
- Fast subsequent calls
- Explicit control

**Cons:**
- Global state
- Memory not freed
- More complex code

### Pattern 5: Grouped Imports

**When to use:** Feature sets, related modules

```python
def feature_operation(data):
    # Import entire feature set
    import feature_core
    import feature_utils
    import feature_types
    
    return feature_core.process(
        data,
        feature_utils.validate,
        feature_types.Schema
    )
```

**Pros:**
- Atomic feature loading
- Clear feature boundary
- All or nothing

**Cons:**
- Larger import cost
- No granular loading

### Pattern 6: Lazy Proxy

**When to use:** Complex module access patterns

```python
class LazyModule:
    def __init__(self, name):
        self._name = name
        self._module = None
    
    def __getattr__(self, attr):
        if self._module is None:
            self._module = __import__(self._name)
        return getattr(self._module, attr)

# Usage
boto3 = LazyModule('boto3')  # Not imported yet
client = boto3.client('s3')   # Imports on first access
```

**Pros:**
- Transparent lazy loading
- Flexible access
- Reusable pattern

**Cons:**
- Added complexity
- Debugging harder
- Magic behavior

---

## IMPORT STRATEGY BY ENVIRONMENT

### AWS Lambda (Memory Constrained)

**Strategy:** Aggressive lazy loading

```python
# Minimal hot path
import json
import gateway

# Everything else lazy
def operation(event):
    if event['type'] == 'alexa':
        import ha_alexa
        return ha_alexa.handle(event)
```

**Rationale:**
- 128MB memory limit
- Cold start critical
- Pay-per-invocation model

### Standard Server (Memory Abundant)

**Strategy:** Standard imports

```python
# Load everything needed
import json
import boto3
import requests
import websocket
import ha_alexa
import ha_assist

def operation(event):
    # All modules ready
    return process(event)
```

**Rationale:**
- Memory not constrained
- Cold start less critical
- Simpler code preferred

### Container (Moderate Constraints)

**Strategy:** Balanced approach

```python
# Core hot path
import json
import gateway
import cache_core

# Heavy/rare lazy
def operation(event):
    if needs_heavy_lib():
        import heavy_lib
        return heavy_lib.process(event)
```

**Rationale:**
- Moderate memory
- Cold start matters some
- Balance simplicity/performance

---

## IMPORT DEPENDENCY MANAGEMENT

### Dependency Chain Analysis

```python
# Example dependency tree
boto3
    ├── botocore (heavy)
    │   ├── urllib3
    │   ├── dateutil
    │   └── jmespath
    ├── s3transfer
    └── jmespath (duplicate)

# Total time: 350ms
```

**Strategy:** If parent is heavy, lazy load entire chain

```python
# Don't do this:
import botocore  # Still pulls in boto3 deps

# Do this:
def use_boto3():
    import boto3  # Load entire tree once
    return boto3.client('s3')
```

### Shared Dependencies

```python
# Multiple imports share dependency
import requests  # Uses urllib3
import websocket  # Uses urllib3
import boto3      # Uses urllib3

# Strategy: Load heaviest first at module level
import boto3  # Loads urllib3
# Others benefit from cached urllib3
```

### Circular Dependencies

**Problem:**
```python
# module_a.py
import module_b

# module_b.py
import module_a  # Circular!
```

**LMMS Solution:**
```python
# module_a.py
def operation():
    import module_b  # Lazy breaks cycle
    return module_b.process()

# module_b.py
def operation():
    import module_a  # Lazy breaks cycle
    return module_a.process()
```

---

## IMPORT PROFILING WORKFLOW

### Step 1: Baseline Measurement

```python
import time
import sys

def profile_cold_start():
    """Measure current cold start."""
    start = time.time()
    
    # Import your modules
    import module1
    import module2
    import module3
    
    duration = (time.time() - start) * 1000
    
    return {
        'total_ms': duration,
        'module_count': len(sys.modules)
    }

baseline = profile_cold_start()
print(f"Baseline: {baseline['total_ms']:.1f}ms")
```

### Step 2: Individual Module Profiling

```python
def profile_module(name):
    """Profile single module import."""
    # Clear from sys.modules if present
    if name in sys.modules:
        del sys.modules[name]
    
    start = time.time()
    module = __import__(name)
    duration = (time.time() - start) * 1000
    
    return duration

# Profile each module
modules = ['json', 'boto3', 'requests', 'websocket']
results = {name: profile_module(name) for name in modules}

# Sort by time
sorted_results = sorted(results.items(), key=lambda x: x[1], reverse=True)
for name, ms in sorted_results:
    print(f"{name:20s} {ms:6.1f}ms")
```

### Step 3: Classification

```python
def classify_import(name, import_time_ms, usage_percent):
    """Classify import by LMMS tier."""
    if import_time_ms < 10:
        return 'TIER1_MODULE'
    
    if usage_percent > 80:
        return 'TIER2_MODULE'
    
    if 20 <= usage_percent <= 80:
        return 'TIER3_FUNCTION'
    
    if import_time_ms > 100:
        return 'TIER4_FUNCTION'
    
    return 'TIER5_FUNCTION'

# Example
tier = classify_import('boto3', 350, 15)
print(f"boto3 → {tier}")  # TIER4_FUNCTION
```

### Step 4: Implementation

```python
# Before
import json
import boto3
import requests

# After classification
import json  # TIER1: <10ms, every request

def use_boto3():
    import boto3  # TIER4: >100ms, lazy load
    return boto3.client('s3')

def use_requests():
    import requests  # TIER3: moderate, lazy load
    return requests.get(url)
```

### Step 5: Verification

```python
# Measure after optimization
optimized = profile_cold_start()

improvement = (baseline['total_ms'] - optimized['total_ms']) / baseline['total_ms']
print(f"Improvement: {improvement:.1%}")

# Target: >40% improvement
```

---

## BEST PRACTICES

### DO: Profile Before Optimizing

```python
# Always measure actual import times
profiler = ImportProfiler()
times = profiler.profile_all(modules)

# Make decisions based on data
for module, ms in times.items():
    if ms > 100:
        print(f"Consider lazy loading: {module}")
```

### DO: Use Fast Path File

```python
# fast_path.py - Single source of hot path truth
import json
import gateway
import cache_core

__all__ = ['json', 'gateway', 'cache_core']

# lambda_function.py
from fast_path import *
```

### DO: Document Import Decisions

```python
# Module level import - justified
import json  # <1ms, used every request (TIER1)

def operation():
    # Function level - justified
    import boto3  # 350ms, used 15% of requests (TIER4)
    return boto3.client('s3')
```

### DON'T: Over-Optimize

```python
# ❌ DON'T lazy load fast imports
def operation():
    import json  # json is <1ms, keep at module level
```

### DON'T: Import in Loops

```python
# ❌ DON'T
for item in items:
    import processor  # Imported N times!

# ✅ DO
import processor
for item in items:
    processor.process(item)
```

### DON'T: Assume Without Measuring

```python
# ❌ DON'T assume
"I think boto3 is slow, let's lazy load it"

# ✅ DO measure
ms = profile_module('boto3')  # 350ms - confirmed slow
```

---

## REFERENCES

**Related Patterns:**
- LMMS-01: Core Concept
- LMMS-02: Cold Start Optimization
- ZAPH: Hot Path Patterns

**Related Decisions:**
- LMMS-DEC-01: Function Level Imports
- LMMS-DEC-02: Hot Path Exceptions
- LMMS-DEC-03: Import Profiling Required

**Related Lessons:**
- LESS-02: Measure Don't Guess
- LMMS-LESS-01: Profile First
- LMMS-LESS-02: Measure Impact

**Related Anti-Patterns:**
- LMMS-AP-01: Premature Optimization
- LMMS-AP-02: Over-Lazy-Loading
- LMMS-AP-03: Ignoring Metrics

---

## KEYWORDS

import strategy, profiling, classification, lazy loading, hot path, cold path, dependency management, performance optimization, decision framework, best practices

---

**END OF FILE**

**Architecture:** LMMS (Lazy Module Management System)  
**Type:** Import Strategy  
**Lines:** 397 (within limit)  
**Status:** Complete

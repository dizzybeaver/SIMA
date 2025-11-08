# LMMS-DEC-04-Fast-Path-File-Required.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Decision requiring fast_path.py for hot path imports  
**Architecture:** LMMS (Python)

## DECISION

**All projects using LMMS must create a fast_path.py file containing only hot path imports.**

## CONTEXT

As LMMS implementation matured, we found that hot path imports scattered across files led to:
- Unclear import boundaries
- Accidental cold path imports in hot files
- Difficulty understanding what loads at cold start
- No single source of truth for optimization

### Problem

```python
# lambda_function.py
import json
import gateway
import boto3  # Oops! Slow import in hot path

# interface_cache.py
import cache_core
import requests  # Oops! Another slow one

# Result: Hidden performance issues
```

## ALTERNATIVES CONSIDERED

### Alternative 1: Scattered Imports

**Approach:** Import where needed

**Pros:**
- Natural Python style
- No extra file needed
- Flexible structure

**Cons:**
- No visibility into hot path
- Easy to add slow imports
- Hard to optimize
- No clear boundary

**Rejected:** Too error-prone

### Alternative 2: Fast Path File (CHOSEN)

**Approach:** Single file with hot path imports

**Pros:**
- Clear hot path boundary
- Easy to review
- Single source of truth
- Prevents accidental slow imports

**Cons:**
- Extra file to maintain
- Requires discipline

**Chosen:** Benefits outweigh costs

## FAST PATH FILE STRUCTURE

### Template

```python
# fast_path.py
"""
Fast Path: Minimum imports for cold start optimization.

ONLY modules meeting criteria:
- Import time <10ms, OR
- Used on >80% of requests (hot path exception)

Last profiled: 2025-11-08
Cold start target: <2 seconds
"""

# Tier 1: Lightweight (<10ms)
import json           # 0.8ms
import time           # 0.4ms
import logging        # 4.2ms

# Tier 2: Hot path exceptions (>10ms but >80% usage)
import gateway        # 15ms, every request
import cache_core     # 35ms, every request

# Export all for easy import
__all__ = [
    'json', 'time', 'logging',
    'gateway', 'cache_core'
]
```

### Usage

```python
# lambda_function.py
from fast_path import *  # Only fast imports loaded

def lambda_handler(event, context):
    # Cold start: Only fast_path imports
    return process(event)

def process(event):
    if event['type'] == 'alexa':
        import ha_alexa  # Lazy load cold path
        return ha_alexa.handle(event)
```

## REQUIREMENTS

### 1. Documentation

```python
# Must include:
# - Last profiled date
# - Import time for each module
# - Justification for exceptions
# - Cold start target
```

### 2. Review Process

```python
# Changes to fast_path.py require:
# - Profiling data showing import time
# - Usage data showing >80% (if >10ms)
# - Performance impact analysis
# - Code review approval
```

### 3. Size Limits

```python
# fast_path.py should have:
# - <20 imports total
# - <200ms total import time
# - <50 lines of code (excluding comments)
```

### 4. Profiling

```python
# Re-profile fast_path.py:
# - Monthly
# - After dependency updates
# - When adding new imports
# - If cold start degrades
```

## ENFORCEMENT

### Code Review Checklist

```
When reviewing fast_path.py changes:
[ ] Profiling data provided?
[ ] Import time acceptable (<10ms or hot path)?
[ ] Usage data supports decision (if >10ms)?
[ ] Total import time stays <200ms?
[ ] Documentation updated?
[ ] Cold start tested?
```

### Automated Tests

```python
# test_fast_path.py
def test_fast_path_import_time():
    """Ensure fast_path imports are actually fast."""
    import time
    start = time.time()
    import fast_path
    duration = (time.time() - start) * 1000
    
    assert duration < 200, f"fast_path too slow: {duration}ms"

def test_fast_path_size():
    """Ensure fast_path stays small."""
    import fast_path
    imports = [
        name for name in dir(fast_path)
        if not name.startswith('_')
    ]
    assert len(imports) < 20, f"Too many imports: {len(imports)}"
```

## BENEFITS

### Clarity

```python
# One file answers: "What loads on cold start?"
cat fast_path.py
# Answer immediately visible
```

### Review

```python
# Easy to review impact of changes
git diff fast_path.py
# See exactly what changed in hot path
```

### Protection

```python
# Prevents accidental slow imports
# Must go through review to add to fast_path.py
# Can't slip in unnoticed
```

### Optimization

```python
# Clear target for optimization
# Optimize fast_path.py = optimize cold start
# Single file to focus on
```

## CONSEQUENCES

### Positive

- ✅ Clear hot path boundary
- ✅ Easy to review and optimize
- ✅ Prevents slow import creep
- ✅ Single source of truth

### Negative

- ❌ Extra file to maintain
- ❌ Requires discipline
- ❌ Need review process

## REFERENCES

- LMMS-01: Core Concept
- LMMS-02: Cold Start Optimization
- LMMS-DEC-03: Import Profiling Required

## KEYWORDS

fast path, hot path, code organization, boundaries, optimization

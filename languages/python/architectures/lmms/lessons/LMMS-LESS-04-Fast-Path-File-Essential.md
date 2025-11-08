# LMMS-LESS-04-Fast-Path-File-Essential.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Lesson on importance of fast_path.py  
**Architecture:** LMMS (Python)

## LESSON LEARNED

**A dedicated fast_path.py file is essential for maintaining LMMS discipline and preventing performance regressions.**

## CONTEXT

Early in LEE project, we didn't have a fast_path.py file. Imports were scattered across files, and slow imports gradually crept into the hot path without anyone noticing.

### What Happened

**Month 1: Good Performance**
```python
# lambda_function.py
import json
import gateway

# Cold start: 2.1 seconds ✓
```

**Month 2: Performance Degradation**
```python
# lambda_function.py
import json
import gateway
import requests  # Someone added this

# Cold start: 2.3 seconds (slightly worse)
```

**Month 3: Worse Performance**
```python
# lambda_function.py
import json
import gateway
import requests
import websocket  # And this
import boto3      # And this!

# Cold start: 3.2 seconds ✗
```

**Nobody noticed until users complained!**

## ROOT CAUSE

### Problem 1: No Visibility

Without fast_path.py:
- Can't see what's in hot path
- Slow imports hidden across files
- No single place to review

```python
# Imports scattered everywhere
# lambda_function.py
import json, gateway

# interface_cache.py
import cache_core, requests  # Hidden slow import!

# gateway_wrappers.py
import boto3  # Another hidden one!

# Result: Can't see total hot path impact
```

### Problem 2: No Protection

Without fast_path.py:
- Easy to add slow imports
- No review process
- Gradual degradation

```python
# New developer adds:
import pandas  # 1200ms!

# No warning, no review, just merges
# Performance degrades silently
```

### Problem 3: No Accountability

Without fast_path.py:
- Can't track changes
- Can't assign blame
- Can't learn from mistakes

```python
# Who added the slow import?
git log lambda_function.py
# Many commits, hard to find

# vs.

git log fast_path.py
# Clear history of hot path changes
```

## SOLUTION: Fast Path File

### Implementation

```python
# fast_path.py
"""
Fast Path: Minimum imports for cold start optimization.

ONLY modules meeting criteria:
- Import time <10ms, OR
- Used on >80% of requests (hot path exception)

Last profiled: 2025-11-08
Target cold start: <2 seconds

REVIEW REQUIRED for any changes to this file!
"""

# Tier 1: Lightweight (<10ms)
import json           # 0.8ms, every request
import time           # 0.4ms, timing operations
import logging        # 4.2ms, logging everywhere

# Tier 2: Hot path exceptions (>10ms but >80% usage)
import gateway        # 15ms, every request - PROFILED 2025-11-08
import cache_core     # 35ms, every request - PROFILED 2025-11-08

# Export for easy import
__all__ = [
    'json', 'time', 'logging',
    'gateway', 'cache_core'
]

# HISTORY:
# 2025-10-01: Initial file, 5 imports, 55ms total
# 2025-11-08: Removed boto3 (moved to function level), 55ms total
```

### Usage

```python
# lambda_function.py
from fast_path import *  # Only fast imports

def lambda_handler(event, context):
    # Cold start: Only fast_path imports (55ms)
    return process(event)

# No other module-level imports allowed!
```

## BENEFITS

### Benefit 1: Visibility

```python
# One command shows entire hot path:
cat fast_path.py

# Answer immediately visible:
# - What imports on cold start?
# - How much do they cost?
# - Why are they module level?
```

### Benefit 2: Protection

```python
# PR Review Process:
# 
# Dev: "Added boto3 to fast_path.py"
# Reviewer: "Why? What's the import time? Usage?"
# Dev: "Uh... let me profile it"
# Reviewer: "350ms and only 15% usage? Function level instead"
# 
# Protected from regression!
```

### Benefit 3: Accountability

```python
# Clear change history:
git log fast_path.py

# commit abc123
# Author: Dev A
# Date: 2025-11-08
# 
# Added requests (180ms) - used 85% of requests
# Profiling data: [link]
# 
# Result: Clear audit trail
```

### Benefit 4: Optimization Target

```python
# Want to optimize cold start?
# One file to focus on:

cat fast_path.py
# Total: 55ms

# Goal: Reduce to <50ms
# Clear target, clear file
```

## ENFORCEMENT

### Code Review Checklist

```python
# For ANY change to fast_path.py:

"""
[ ] Profiling data provided?
[ ] Import time documented?
[ ] Usage data provided (if >10ms)?
[ ] Justification clear?
[ ] Total time still <200ms?
[ ] Alternative considered (function level)?
[ ] Team lead approved?
"""
```

### Automated Protection

```python
# test_fast_path.py
def test_fast_path_import_time():
    """Fail CI if fast_path too slow."""
    import time
    start = time.time()
    import fast_path
    duration = (time.time() - start) * 1000
    
    assert duration < 200, (
        f"fast_path.py too slow: {duration}ms\n"
        f"Target: <200ms\n"
        f"Review all imports in fast_path.py"
    )

def test_fast_path_size():
    """Fail CI if too many imports."""
    import fast_path
    count = len([
        x for x in dir(fast_path)
        if not x.startswith('_')
    ])
    
    assert count < 20, (
        f"Too many imports in fast_path.py: {count}\n"
        f"Target: <20 imports\n"
        f"Move rarely-used imports to function level"
    )
```

### Monthly Reviews

```python
# Scheduled review process
"""
Monthly fast_path.py review:

1. Re-profile all imports
2. Check usage patterns changed
3. Verify exceptions still valid
4. Update documentation
5. Measure cold start
6. Document results
"""
```

## RESULTS

### Before fast_path.py

```
Months 1-3: Gradual degradation
Cold start: 2.1s → 3.2s
Nobody noticed until users complained
12 hours to debug and fix
Trust damaged
```

### After fast_path.py

```
Months 4-6: Stable performance
Cold start: 2.1s → 2.1s (stable)
2 attempts to add slow imports caught in review
0 hours debugging performance regressions
Trust maintained
```

## PREVENTION

### Requirement: fast_path.py Mandatory

```python
# All LMMS projects MUST have:
# - fast_path.py file
# - Review process
# - Automated tests
# - Regular profiling
```

### Team Training

```python
# Teach team:
"""
1. What fast_path.py is
2. Why it exists
3. How to use it
4. Review process
5. When exceptions allowed
"""
```

## KEY INSIGHTS

1. **Visibility matters:** Can't protect what you can't see
2. **Process prevents regression:** Review catches problems early
3. **Single file = clear boundary:** Know what's hot path
4. **Automation helps:** Tests catch violations
5. **Documentation essential:** Know why each import exists

## RELATED ISSUES

- LMMS-DEC-04: Fast Path File Required
- LMMS-LESS-01: Profile First Always
- LMMS-AP-04: Hot Path Heavy Imports

## KEYWORDS

fast_path.py, code organization, performance protection, visibility, boundaries, regression prevention

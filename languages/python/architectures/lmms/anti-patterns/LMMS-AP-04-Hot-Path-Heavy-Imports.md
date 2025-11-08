# LMMS-AP-04-Hot-Path-Heavy-Imports.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Anti-pattern of slow imports in hot path  
**Architecture:** LMMS (Python)

## ANTI-PATTERN

**Adding >100ms imports to fast_path.py or module-level without justification.**

## DESCRIPTION

Including heavy imports in the hot path (fast_path.py or module-level) without measuring usage patterns or documenting hot path exceptions.

## EXAMPLE

```python
# ❌ WRONG: Heavy import in fast_path.py without justification

# fast_path.py
import json         # 0.8ms ✓
import gateway      # 15ms ✓
import pandas       # 1200ms ✗ - WHY IS THIS HERE?

# No comment explaining why pandas is module-level
# No usage data showing it's frequently used
# No profiling data
# Just added without thinking
```

## WHY IT'S WRONG

### Problem 1: Defeats LMMS Purpose

```python
# LMMS goal: Fast cold start
# Cold start target: <2 seconds
# pandas import: 1200ms (60% of target!)

# Adding pandas to hot path:
# Cold start: 2.8 seconds (target missed)
# Defeated entire purpose of LMMS
```

### Problem 2: No Cost-Benefit Analysis

```python
# Added pandas to module level because "easier"
# But never checked:
# - How often is it used? (5% of requests)
# - What's the cost? (1200ms every cold start)
# - What's the benefit? (Saves 1200ms on 5% of requests)
# - Is it worth it? (NO - 20× worse)

# Module level: 1200ms × 1000 cold starts = 1,200,000ms
# Function level: 1200ms × 500 uses = 600,000ms
# Waste: 600,000ms (10 minutes!) per day
```

### Problem 3: Silent Performance Degradation

```python
# Nobody noticed when pandas was added:
# - No review required
# - No profiling data
# - No usage data
# - Just merged

# Result: Cold start slowly degraded
# Month 1: 2.1s
# Month 2: 2.4s (pandas added)
# Month 3: 2.8s (more slow imports)
# Nobody noticed until too late
```

## CORRECT APPROACH

### Strict fast_path.py Discipline

```python
# fast_path.py
"""
Fast Path: ONLY modules meeting strict criteria.

Criteria for inclusion:
1. Import time <10ms, OR
2. Import time <100ms AND usage >80% (hot path exception)

MUST provide for any addition:
- Profiling data showing import time
- Usage data showing frequency
- Justification for inclusion
- Review and approval

Last reviewed: 2025-11-08
"""

# Tier 1: Fast (<10ms)
import json           # 0.8ms, every request
import time           # 0.4ms, timing operations
import logging        # 4.2ms, logging everywhere

# Tier 2: Hot path exceptions (justified)
import gateway        # 15ms but 95% usage - PROFILED 2025-11-08
import cache_core     # 35ms but 90% usage - PROFILED 2025-11-08

# NO other imports without approval!
```

### Document Exceptions

```python
# If >10ms import needed in hot path:
import boto3  # 350ms but 90% usage
"""
HOT PATH EXCEPTION: boto3

Import time: 350ms (heavy)
Usage frequency: 90% of requests
Justification:
    - Used by all device operations
    - Used by all cache operations
    - Total cost analysis:
        Module level: 350ms × 1000 cold starts = 350,000ms/day
        Function level: 350ms × 9000 requests = 3,150,000ms/day
        Decision: Module level is 9× better

Approved by: [Name]
Date: 2025-11-08
Review date: 2025-12-08
"""
```

### Enforce with Tests

```python
# test_fast_path.py
import fast_path
import time

def test_fast_path_import_time():
    """Enforce fast_path.py import time limit."""
    start = time.time()
    # Force reload to measure cold import
    import importlib
    importlib.reload(fast_path)
    duration = (time.time() - start) * 1000
    
    # Strict limit: 200ms total
    assert duration < 200, (
        f"fast_path.py too slow: {duration}ms\n"
        f"Target: <200ms\n"
        f"Review all imports for slow ones"
    )

def test_fast_path_no_heavy_imports():
    """Explicitly block known heavy imports."""
    import sys
    
    # Reload fast_path
    if 'fast_path' in sys.modules:
        del sys.modules['fast_path']
    
    start_modules = set(sys.modules.keys())
    
    import fast_path
    
    end_modules = set(sys.modules.keys())
    new_modules = end_modules - start_modules
    
    # Known slow modules that shouldn't be in fast_path
    FORBIDDEN = {'pandas', 'numpy', 'matplotlib', 'scipy'}
    
    violations = new_modules & FORBIDDEN
    
    assert not violations, (
        f"Heavy imports in fast_path.py: {violations}\n"
        f"These should be function-level only"
    )
```

## REAL EXAMPLE

### What Happened (LEE Project)

```python
# Developer added pandas for convenience:
# fast_path.py (Week 1)
import json
import gateway
import pandas  # Added without review

# Nobody noticed:
# - No test caught it
# - No review process
# - Merged to main

# Impact measured later:
# Cold start: 2.1s → 3.3s (57% slower!)
# Cost: $4.20 per 1M invocations extra
# User complaints: Increased

# Had to:
# - Find the change (git bisect)
# - Revert it
# - Add tests to prevent recurrence
# - Lost 2 days debugging
```

### Prevention

```python
# After incident, added:

# 1. Tests
def test_fast_path_performance():
    # Fails if >200ms

# 2. Review process
"""
Changes to fast_path.py require:
- Profiling data
- Usage data
- Cost-benefit analysis
- Team lead approval
"""

# 3. Documentation
"""
Each import must be documented with:
- Import time
- Usage frequency
- Justification
"""

# Result: No more incidents
```

## WARNING SIGNS

### Sign 1: Slow Imports in fast_path.py

```python
# ⚠️ WARNING
import pandas  # In fast_path.py without justification
```

### Sign 2: No Documentation

```python
# ⚠️ WARNING
import boto3  # >100ms but no comment explaining why module-level
```

### Sign 3: Cold Start Degrading

```python
# ⚠️ WARNING
# Month 1: 2.1s
# Month 2: 2.4s
# Month 3: 2.8s
# Trend: Gradual degradation suggests imports creeping in
```

## PREVENTION

### Gatekeeper Process

```python
# Changes to fast_path.py:
"""
1. Must provide profiling data
2. Must justify >10ms imports
3. Must calculate cost-benefit
4. Must get approval
5. Must add documentation
6. Must pass automated tests
"""
```

### Regular Audits

```python
# Monthly fast_path.py audit:
"""
1. Re-profile all imports
2. Verify justifications still valid
3. Check for unauthorized additions
4. Remove unnecessary imports
5. Document findings
"""
```

## IMPACT

### Cost of Undisciplined fast_path.py

```
Heavy imports added: 3
Cold start degradation: 2.1s → 3.3s (57% worse)
Daily waste: 1,200 seconds
Cost increase: $4.20 per 1M invocations
Debug time: 2 days
User complaints: Increased
```

### Benefit of Disciplined fast_path.py

```
Heavy imports: 0 (blocked by tests)
Cold start: 2.1s (maintained)
Daily waste: 0 seconds
Cost: Optimized
Debug time: 0 hours
User complaints: None
```

## REFERENCES

- LMMS-DEC-04: Fast Path File Required
- LMMS-LESS-04: Fast Path File Essential
- LMMS-DEC-02: Hot Path Exceptions

## KEYWORDS

fast_path, hot path, heavy imports, performance degradation, code discipline

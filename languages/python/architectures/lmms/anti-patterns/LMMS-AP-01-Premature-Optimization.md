# LMMS-AP-01-Premature-Optimization.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Anti-pattern of optimizing before profiling  
**Architecture:** LMMS (Python)

## ANTI-PATTERN

**Optimizing imports to function-level without profiling data.**

## DESCRIPTION

Making imports lazy based on assumptions rather than measurements, often optimizing modules that don't need optimization while missing the actual slow imports.

## EXAMPLE

```python
# ❌ WRONG: Optimizing without profiling

# Assumed: "json must be slow, it parses text"
def operation():
    import json  # Actually 0.8ms - NOT worth optimizing
    return json.dumps(data)

# Didn't optimize:
import pandas  # Actually 1200ms - SHOULD optimize!

# Result: Added complexity for no gain, missed real opportunity
```

## WHY IT'S WRONG

### Problem 1: Wasted Effort

Optimizing fast imports adds complexity with no benefit:

```python
# Optimized json (0.8ms)
def operation():
    import json  # Added function-level import
    # Saved: 0.8ms
    # Cost: Code complexity
    # Net: Negative

# Should have optimized pandas (1200ms)
# Would save: 1200ms
# But didn't measure, so didn't know!
```

### Problem 2: Wrong Targets

Assumptions fail:

```python
# Common wrong assumptions:
"Built-in modules are fast" → Not always (some are 10-50ms)
"HTTP libraries are slow" → Not always (some are <10ms)
"Data libraries are slow" → Often true, but which ones?
"My custom modules are fine" → Often the slowest!
```

### Problem 3: Hidden Costs

Can make performance worse:

```python
# Before (assumed slow, made lazy):
def operation():
    import fast_module  # 2ms import
    return fast_module.process()

# Cost per request:
# First call: 2ms (import) + processing
# Later calls: 0ms (cached) + processing

# vs. Module level:
import fast_module
# Cold start: 2ms once
# All requests: 0ms (already imported)

# Net: Made it worse by adding overhead!
```

## CORRECT APPROACH

### Always Profile First

```python
# Step 1: Profile BEFORE optimizing
from performance_benchmark import profile_imports

results = profile_imports()
for module, ms in sorted(results.items(), key=lambda x: x[1], reverse=True):
    print(f"{module}: {ms}ms")

# Step 2: Optimize based on data
# json: 0.8ms → Keep module level (fast)
# pandas: 1200ms → Move to function level (slow)
```

### Use Thresholds

```python
# Clear criteria:
if import_time_ms < 10:
    # Module level - too fast to matter
    strategy = "module_level"
elif import_time_ms > 100:
    # Function level - definitely slow
    strategy = "function_level"
else:
    # Check usage frequency
    if usage_percent > 80:
        strategy = "module_level"  # Hot path
    else:
        strategy = "function_level"  # Cold path
```

## REAL EXAMPLE

### What Happened (LEE Project)

```python
# Week 1: Assumptions
# "Let's optimize these modules I think are slow"

# Optimized (based on assumptions):
def use_json():
    import json  # 0.8ms - wasted effort

def use_requests():
    import requests  # 180ms - good, but not priority

# Didn't optimize (didn't check):
import ha_devices  # 450ms - should have!
import pandas      # 1200ms - critical miss!

# Result: 15% improvement (expected 60%)
```

### Correct Approach

```python
# Week 2: Profiling
results = profile_imports()
# json: 0.8ms
# requests: 180ms
# ha_devices: 450ms
# pandas: 1200ms

# Optimized based on data:
import json  # <10ms, keep module level

def use_ha_devices():
    import ha_devices  # 450ms, lazy load

def use_pandas():
    import pandas  # 1200ms, definitely lazy

# Result: 60% improvement (as expected)
```

## WARNING SIGNS

### Sign 1: No Profiling Data

```python
# ⚠️ WARNING
"I think this module is slow, let's optimize it"
# No data = guessing
```

### Sign 2: Optimizing Built-ins

```python
# ⚠️ WARNING
def operation():
    import json  # json is <1ms, why optimize?
```

### Sign 3: Ignoring Heavy Modules

```python
# ⚠️ WARNING
import pandas  # 1200ms but not optimized
# If profiled, would see this is critical!
```

## PREVENTION

### Checklist Before Optimizing

```
[ ] Profiled all imports?
[ ] Have import times?
[ ] Identified slow modules (>100ms)?
[ ] Checked usage frequency?
[ ] Calculated expected improvement?
[ ] Documented decision?
```

### Require Profiling Data

```python
# Code review requirement:
"""
PR: "Optimized module imports"

Reviewer: "Where's the profiling data?"
Dev: "I thought json would be slow"
Reviewer: "Rejected - profile first, show data"
"""
```

## IMPACT

### Cost of Premature Optimization

```
Time spent: 8 hours
Improvement: 0.8ms (negligible)
Code complexity: Increased
Maintenance burden: Increased
Actual slow imports: Still there
```

### Benefit of Profile-First

```
Time spent: 10 hours (including 2 hours profiling)
Improvement: 3.1 seconds (significant)
Code complexity: Only where needed
Maintenance burden: Justified
Actual slow imports: Optimized
```

## REFERENCES

- LMMS-LESS-01: Profile First Always
- LMMS-DEC-03: Import Profiling Required
- LESS-02: Measure Don't Guess

## KEYWORDS

premature optimization, profiling, assumptions, data-driven, measurement

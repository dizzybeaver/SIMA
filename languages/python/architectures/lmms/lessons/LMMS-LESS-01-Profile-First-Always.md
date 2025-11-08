# LMMS-LESS-01-Profile-First-Always.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Lesson on importance of profiling before optimization  
**Architecture:** LMMS (Python)

## LESSON LEARNED

**Never optimize imports without profiling data. Assumptions about import performance are unreliable and waste effort.**

## CONTEXT

During LEE project cold start optimization, we initially made assumptions about which modules were slow without measuring. This led to wasted effort and missed opportunities.

### What Happened

**Week 1: Assumptions**
```python
# Assumptions made:
# "boto3 must be slow" ✓ (correct - 350ms)
# "requests is lightweight" ✗ (wrong - 180ms)
# "websocket is fast" ✗ (wrong - 220ms)
# "json is built-in, fast" ✓ (correct - 0.8ms)

# Optimized based on assumptions
import json
import requests  # Kept at module level (wrong!)
import websocket  # Kept at module level (wrong!)

def use_boto3():
    import boto3  # Made lazy (correct)
```

**Result: Only 15% improvement, should have been 60%**

**Week 2: Profiling**
```python
# Actual measurements:
json:      0.8ms   ✓ Fast, module level
boto3:     350ms   ✓ Slow, function level
requests:  180ms   ✗ Slow, should be function level
websocket: 220ms   ✗ Slow, should be function level
pandas:    1200ms  ✗ Slowest! Missed completely
```

**After profiling-based optimization: 60% improvement**

## ROOT CAUSE

### Problem 1: Confirmation Bias

We optimized what we *thought* was slow, confirming our assumptions without testing.

```python
# Thought boto3 was slow → measured it → confirmed → felt validated
# Didn't think to measure requests → didn't measure → missed opportunity
```

### Problem 2: Hidden Costs

Some modules have surprising import costs:

```python
# Lightweight-looking modules with heavy imports:
websocket:  220ms  # Pulls in SSL libraries
requests:   180ms  # Pulls in urllib3, chardet
ha_devices: 450ms  # Custom module with hidden deps
```

### Problem 3: Dependency Chains

Import time includes entire dependency tree:

```python
import boto3  # 350ms total
    ├── botocore: 280ms
    │   ├── urllib3: 90ms
    │   ├── dateutil: 45ms
    │   └── jmespath: 35ms
    └── s3transfer: 70ms

# Can't see this without profiling!
```

## IMPACT

### Wasted Effort

```
Week 1 (assumptions):
- Hours spent: 8
- Improvement: 15%
- Missed opportunities: 45%
- Rework needed: Yes

Week 2 (profiling):
- Hours spent: 10 (including profiling)
- Improvement: 60%
- Missed opportunities: 0%
- Rework needed: No

Lesson: Profiling saves time overall
```

### Missed Performance

```
Assumption-based:
Cold start: 5.2s → 4.4s (0.8s / 15% improvement)

Profile-based:
Cold start: 5.2s → 2.1s (3.1s / 60% improvement)

Difference: 2.3 seconds lost due to assumptions
```

## CORRECT APPROACH

### Step 1: Profile Everything

```python
# profile_imports.py
import time

def profile_all_imports(modules):
    """Profile list of imports."""
    results = {}
    
    for module_name in modules:
        # Clear from cache
        if module_name in sys.modules:
            del sys.modules[module_name]
        
        # Measure import time
        start = time.time()
        try:
            __import__(module_name)
            duration = (time.time() - start) * 1000
            results[module_name] = duration
        except ImportError as e:
            results[module_name] = f"ERROR: {e}"
    
    return results

# Get ALL modules used in project
modules = [
    'json', 'time', 'logging',
    'boto3', 'requests', 'websocket',
    'pandas', 'numpy',
    'ha_devices', 'ha_alexa', 'ha_assist',
    # ... all of them
]

results = profile_all_imports(modules)

# Sort by time (slowest first)
sorted_results = sorted(
    [(m, t) for m, t in results.items() if isinstance(t, (int, float))],
    key=lambda x: x[1],
    reverse=True
)

print("\nImport Profile (Slowest First):")
print("-" * 50)
for module, ms in sorted_results:
    print(f"{module:30s} {ms:6.1f}ms")
```

### Step 2: Make Data-Driven Decisions

```python
# After profiling, classify based on ACTUAL data
for module, ms in results.items():
    if ms < 10:
        print(f"{module}: MODULE_LEVEL (fast)")
    elif ms > 100:
        print(f"{module}: FUNCTION_LEVEL (slow)")
    else:
        usage = get_usage_percent(module)
        if usage > 80:
            print(f"{module}: MODULE_LEVEL (hot path)")
        else:
            print(f"{module}: FUNCTION_LEVEL (cold path)")
```

### Step 3: Verify Results

```python
# Measure before and after
baseline = measure_cold_start()  # Before optimization

# Apply optimizations based on profiling
implement_lmms_optimizations()

optimized = measure_cold_start()  # After optimization

improvement = (baseline - optimized) / baseline
print(f"Improvement: {improvement:.1%}")

# Should see >40% improvement
```

## PREVENTION

### Requirement: Always Profile

```python
# Add to development checklist
"""
Before any LMMS optimization:
[ ] Profile all imports
[ ] Measure usage frequency
[ ] Document results
[ ] Make data-driven decisions
[ ] Verify improvement
"""
```

### Automated Profiling

```python
# CI/CD integration
def test_import_performance():
    """Automatically profile imports in CI."""
    results = profile_all_imports(all_modules)
    
    slow_imports = [
        (m, ms) for m, ms in results.items()
        if ms > 500
    ]
    
    if slow_imports:
        print("WARNING: Slow imports detected:")
        for module, ms in slow_imports:
            print(f"  {module}: {ms}ms")
```

### Monthly Reviews

```python
# Schedule profiling reviews
"""
Monthly tasks:
1. Re-profile all imports
2. Check for new slow imports
3. Verify classifications still valid
4. Update fast_path.py if needed
"""
```

## KEY INSIGHTS

1. **Assumptions fail:** Import performance is non-obvious
2. **Dependencies hidden:** Import time includes entire tree
3. **Profiling is fast:** Takes 1 hour, saves days
4. **Data wins:** Objective measurement beats intuition
5. **Profile regularly:** Performance changes with updates

## RELATED ISSUES

- LMMS-DEC-03: Import Profiling Required
- LESS-02: Measure Don't Guess
- LMMS-AP-01: Premature Optimization
- LMMS-AP-03: Ignoring Metrics

## KEYWORDS

profiling, measurement, data-driven decisions, assumptions, import performance, optimization

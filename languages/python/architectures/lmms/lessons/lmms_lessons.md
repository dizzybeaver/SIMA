# LMMS Lesson Files (Batch)

**Contains:** 4 lesson files  
**Date:** 2025-11-08  
**Architecture:** LMMS

---

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

---

# LMMS-LESS-02-Measure-Impact-Always.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Lesson on measuring optimization impact  
**Architecture:** LMMS (Python)

## LESSON LEARNED

**Always measure cold start improvement after LMMS changes. Unmeasured optimizations often have zero or negative impact.**

## CONTEXT

After implementing LMMS optimizations in LEE project, we initially didn't measure the actual impact. When we finally did, we discovered surprising results.

### What Happened

**Initial Optimization**
```python
# Moved 5 imports to function level
# Assumed this would help
# Deployed without measuring
```

**User Reports: "Still slow"**

**Actual Measurement (3 weeks later)**
```python
baseline = measure_cold_start()
# Result: 5.1 seconds

# Only 0.1 second improvement!
# Expected: 1-2 seconds
# Why so small?
```

**Investigation revealed:**
- Moved wrong imports (fast ones)
- Kept slow imports (unmeasured)
- Added overhead (lazy loading)
- Net effect: Minimal

## ROOT CAUSE

### Problem 1: No Baseline

Without baseline measurement:
- Can't calculate improvement
- Can't verify expectations
- Can't detect regressions

```python
# What we did (wrong):
"Optimized imports, should be faster now"
# No numbers, no verification

# What we should have done:
baseline = measure_cold_start()  # 5.2s
# optimize
optimized = measure_cold_start()  # 2.1s
improvement = (5.2 - 2.1) / 5.2  # 60%
```

### Problem 2: No Target

Without performance targets:
- Don't know if "done"
- Can't prioritize efforts
- Can't communicate progress

```python
# Wrong: "Make it faster"
# Right: "Target <2 seconds cold start"
```

### Problem 3: No Verification

Without verification:
- Regressions go unnoticed
- False improvements accepted
- Waste time on ineffective changes

## CORRECT APPROACH

### Step 1: Establish Baseline

```python
def measure_cold_start_baseline():
    """Measure current cold start performance."""
    import time
    import sys
    
    # Clear module cache (simulate cold start)
    modules_to_clear = [
        m for m in sys.modules
        if m.startswith('ha_') or m == 'boto3'
    ]
    for m in modules_to_clear:
        del sys.modules[m]
    
    # Measure import time
    start = time.time()
    from lambda_function import *
    import_time = (time.time() - start) * 1000
    
    # Measure initialization time
    start = time.time()
    handler = get_lambda_handler()
    init_time = (time.time() - start) * 1000
    
    return {
        'total_ms': import_time + init_time,
        'import_ms': import_time,
        'init_ms': init_time,
        'module_count': len(sys.modules)
    }

# Record baseline
baseline = measure_cold_start_baseline()
print(f"Baseline cold start: {baseline['total_ms']}ms")

# Save for comparison
with open('baseline.json', 'w') as f:
    json.dump(baseline, f)
```

### Step 2: Set Targets

```python
# Define performance targets
TARGETS = {
    'cold_start_ms': 2000,      # <2 seconds total
    'import_ms': 500,            # <500ms imports
    'init_ms': 1500,             # <1.5s init
    'improvement_pct': 40        # >40% from baseline
}

print("Performance Targets:")
for metric, target in TARGETS.items():
    print(f"  {metric}: {target}")
```

### Step 3: Implement Changes

```python
# Make LMMS optimizations
# Document each change
changes = []

# Change 1: Move boto3 to function level
changes.append({
    'module': 'boto3',
    'before': 'module_level',
    'after': 'function_level',
    'expected_improvement_ms': 350
})

# Change 2: Move pandas to function level
changes.append({
    'module': 'pandas',
    'before': 'module_level',
    'after': 'function_level',
    'expected_improvement_ms': 1200
})
```

### Step 4: Measure After Each Change

```python
# Measure after EACH change
for change in changes:
    # Apply change
    apply_optimization(change)
    
    # Measure impact
    result = measure_cold_start()
    improvement_ms = baseline['total_ms'] - result['total_ms']
    improvement_pct = improvement_ms / baseline['total_ms'] * 100
    
    # Record
    change['actual_improvement_ms'] = improvement_ms
    change['actual_improvement_pct'] = improvement_pct
    
    # Report
    print(f"\nChange: {change['module']}")
    print(f"Expected: {change['expected_improvement_ms']}ms")
    print(f"Actual: {improvement_ms:.0f}ms")
    print(f"% Improvement: {improvement_pct:.1f}%")
```

### Step 5: Verify Targets Met

```python
# Final verification
final = measure_cold_start()

print("\nFinal Results:")
print(f"Baseline: {baseline['total_ms']}ms")
print(f"Final: {final['total_ms']}ms")
print(f"Improvement: {(baseline['total_ms'] - final['total_ms']):.0f}ms")

# Check targets
for metric, target in TARGETS.items():
    actual = final.get(metric, 0)
    met = "✓" if actual <= target else "✗"
    print(f"{met} {metric}: {actual} (target: {target})")
```

## MEASUREMENT TOOLS

### Tool 1: Simple Timer

```python
import time

def measure_simple():
    """Quick cold start measurement."""
    start = time.time()
    import lambda_function
    duration = (time.time() - start) * 1000
    print(f"Cold start: {duration:.1f}ms")
```

### Tool 2: Detailed Profiler

```python
# performance_benchmark.py
def benchmark_cold_start():
    """Detailed cold start profiling."""
    import time
    import sys
    
    results = {
        'phases': [],
        'module_times': {}
    }
    
    # Phase 1: Core imports
    start = time.time()
    import json, time as time_mod, logging
    results['phases'].append({
        'name': 'core_imports',
        'ms': (time.time() - start) * 1000
    })
    
    # Phase 2: Gateway imports
    start = time.time()
    import gateway
    results['phases'].append({
        'name': 'gateway',
        'ms': (time.time() - start) * 1000
    })
    
    # Phase 3: Interface imports
    start = time.time()
    import interface_cache, interface_logging
    results['phases'].append({
        'name': 'interfaces',
        'ms': (time.time() - start) * 1000
    })
    
    # Total
    results['total_ms'] = sum(p['ms'] for p in results['phases'])
    
    return results
```

### Tool 3: CloudWatch Integration

```python
import boto3

def report_cold_start_metric(duration_ms):
    """Report to CloudWatch."""
    cloudwatch = boto3.client('cloudwatch')
    
    cloudwatch.put_metric_data(
        Namespace='Lambda/Performance',
        MetricData=[{
            'MetricName': 'ColdStartTime',
            'Value': duration_ms,
            'Unit': 'Milliseconds',
            'Dimensions': [{
                'Name': 'FunctionName',
                'Value': 'lee-lambda'
            }]
        }]
    )
```

## IMPACT

### Before Measurement Discipline

```
Optimizations: 5 changes
Time spent: 12 hours
Actual improvement: 0.1 seconds (2%)
Wasted effort: 10 hours
```

### After Measurement Discipline

```
Optimizations: 3 changes (data-driven)
Time spent: 8 hours (including profiling)
Actual improvement: 3.1 seconds (60%)
Wasted effort: 0 hours
```

## PREVENTION

### Always Measure Checklist

```
[ ] Baseline established
[ ] Targets defined
[ ] Each change measured
[ ] Cumulative impact tracked
[ ] Final results verified
[ ] Targets met or explained
```

### Continuous Monitoring

```python
# Add to deployment pipeline
def verify_performance():
    """CI/CD performance check."""
    current = measure_cold_start()
    
    # Load historical baseline
    with open('baseline.json') as f:
        baseline = json.load(f)
    
    # Check for regression
    if current['total_ms'] > baseline['total_ms'] * 1.1:  # 10% tolerance
        raise Exception(f"Performance regression detected!")
```

## KEY INSIGHTS

1. **Baseline required:** Can't improve what you don't measure
2. **Measure each change:** Know what helps, what doesn't
3. **Set targets:** Clear goals drive progress
4. **Verify continuously:** Catch regressions early
5. **Data over intuition:** Measurements beat assumptions

## RELATED ISSUES

- LMMS-LESS-01: Profile First Always
- LESS-02: Measure Don't Guess
- LMMS-DEC-03: Import Profiling Required

## KEYWORDS

measurement, verification, baselines, targets, performance tracking, cold start, impact analysis

---

# LMMS-LESS-03-Hot-Path-Worth-Cost.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Lesson on hot path exception trade-offs  
**Architecture:** LMMS (Python)

## LESSON LEARNED

**Module-level imports for frequently-used heavy modules (hot path exceptions) significantly improve total performance, even at the cost of longer cold starts.**

## CONTEXT

During LEE project optimization, we strictly applied LMMS rules: all >100ms imports became function-level. This improved cold start but hurt request latency.

### What Happened

**Strict LMMS Implementation**
```python
# All >100ms imports → function level
def handle_request(event):
    import boto3  # 350ms, used 90% of requests
    import requests  # 180ms, used 85% of requests
    return process_with_libs(event)
```

**Results:**
- Cold start: 2.1 seconds ✓ (Good)
- P50 latency: 1.2 seconds ✗ (Bad)
- P95 latency: 1.8 seconds ✗ (Very bad)
- User complaints: Many

**Analysis:**
```
Daily requests: 10,000
Cold starts: 1,000 (10%)
Hot starts: 9,000 (90%)

Lazy import overhead per request: ~0.5ms

Cold start cost:
- Module level: 350ms × 1,000 = 350,000ms
- Function level: 0 (deferred)
- Savings: 350,000ms

Hot start cost:
- Module level: 0 (already imported)
- Function level: 350ms × 9,000 = 3,150,000ms
- Overhead: 3,150,000ms

Net impact: -2,800,000ms (worse!)
```

### What We Changed

**Hot Path Exception for High-Usage Modules**
```python
# Hot path exception: >80% usage → module level
import boto3     # 350ms but 90% usage
import requests  # 180ms but 85% usage

def handle_request(event):
    # No lazy import needed
    return process_with_libs(event)
```

**New Results:**
- Cold start: 2.6 seconds (0.5s slower)
- P50 latency: 0.7 seconds (0.5s faster) ✓
- P95 latency: 1.1 seconds (0.7s faster) ✓
- User complaints: Zero

## ROOT CAUSE

### Misconception: Cold Start is Everything

Initially thought cold start was the only metric that mattered:

```python
# Wrong thinking:
"Faster cold start = better performance"

# Reality:
"Better OVERALL performance = better user experience"
```

### The Math of Hot Path

For high-usage modules, total latency matters more than cold start:

```python
def calculate_total_impact(import_ms, usage_pct, daily_requests):
    """Calculate total latency impact."""
    cold_starts = daily_requests * 0.1  # 10% cold start rate
    hot_starts = daily_requests * 0.9   # 90% already warm
    
    # Module level: Pay on cold starts only
    module_level_cost = import_ms * cold_starts
    
    # Function level: Pay on every usage
    function_level_cost = import_ms * (usage_pct / 100) * daily_requests
    
    return {
        'module_level_ms': module_level_cost,
        'function_level_ms': function_level_cost,
        'better': 'module' if module_level_cost < function_level_cost else 'function'
    }

# boto3 example
impact = calculate_total_impact(
    import_ms=350,
    usage_pct=90,
    daily_requests=10000
)
# module_level_ms: 350,000
# function_level_ms: 3,150,000
# better: module (9× better!)
```

## DECISION FRAMEWORK

### Hot Path Threshold

**A module qualifies for hot path exception when:**

```python
def is_hot_path(import_ms, usage_pct):
    """Determine if module qualifies for hot path exception."""
    # Rule 1: Very heavy and frequently used
    if import_ms > 500 and usage_pct > 70:
        return True
    
    # Rule 2: Moderately heavy but very frequently used
    if import_ms > 100 and usage_pct > 80:
        return True
    
    # Rule 3: Used on critical path
    # (manual decision based on architecture)
    
    return False
```

### Calculate Break-Even Point

```python
def break_even_usage(import_ms, daily_requests):
    """Calculate usage % where module level becomes better."""
    cold_starts = daily_requests * 0.1
    
    # Module level cost = import_ms × cold_starts
    # Function level cost = import_ms × usage × daily_requests
    # 
    # Break even when:
    # import_ms × cold_starts = import_ms × usage × daily_requests
    # cold_starts = usage × daily_requests
    # usage = cold_starts / daily_requests
    # usage = 0.1 (10%)
    
    # So break-even is always ~10% with 10% cold start rate
    # But we want margin, so use 80% threshold
    
    return 0.8  # 80% usage threshold
```

## IMPLEMENTATION

### Example: boto3 Hot Path Exception

```python
# boto3 analysis
import_time = 350  # ms
usage_pct = 90     # % of requests

# Calculate impact
module_cost = 350 * 1000  # 350,000ms per day
function_cost = 350 * 9000  # 3,150,000ms per day

# Decision: Module level (9× better)
import boto3  # HOT PATH EXCEPTION: 350ms but 90% usage
```

### Example: pandas Cold Path

```python
# pandas analysis
import_time = 1200  # ms
usage_pct = 5       # % of requests

# Calculate impact
module_cost = 1200 * 1000  # 1,200,000ms per day
function_cost = 1200 * 500  # 600,000ms per day

# Decision: Function level (2× better)
def use_pandas():
    import pandas  # Stay lazy: only 5% usage
```

## MEASURED RESULTS

### Before Hot Path Exceptions

```
Cold start: 2.1s
P50 latency: 1.2s
P95 latency: 1.8s
Daily total latency: 12,000s
User satisfaction: 72%
```

### After Hot Path Exceptions

```
Cold start: 2.6s (0.5s worse)
P50 latency: 0.7s (0.5s better)
P95 latency: 1.1s (0.7s better)
Daily total latency: 7,000s (42% better)
User satisfaction: 91% (19% improvement)
```

### Trade-Off Summary

```
Gave up:
- 0.5s cold start (once per cold start)
- 500ms × 1,000 = 500,000ms daily

Gained:
- 0.5s per request latency (9,000 warm starts)
- 500ms × 9,000 = 4,500,000ms daily

Net benefit: 4,000,000ms (4000 seconds) daily
```

## PREVENTION

### Calculate Before Deciding

```python
# Always calculate total impact
for module in modules:
    impact = calculate_total_impact(
        module.import_ms,
        module.usage_pct,
        10000  # daily requests
    )
    
    if impact['better'] == 'module':
        print(f"{module}: HOT PATH EXCEPTION")
    else:
        print(f"{module}: FUNCTION LEVEL")
```

### Monitor Usage Patterns

```python
# Track actual usage
usage_counter = {}

def track_module_usage(module_name):
    usage_counter[module_name] = usage_counter.get(module_name, 0) + 1

# Monthly review
def review_hot_path():
    total = sum(usage_counter.values())
    for module, count in usage_counter.items():
        pct = count / total * 100
        if pct > 80:
            print(f"{module}: {pct:.1f}% usage - HOT PATH")
```

## KEY INSIGHTS

1. **Total latency > Cold start:** Optimize for overall performance
2. **Usage matters:** High-usage modules worth module-level cost
3. **Math wins:** Calculate impact, don't guess
4. **User experience:** P95 latency matters more than cold start
5. **Trade-offs exist:** Sometimes slower cold start = better overall

## RELATED ISSUES

- LMMS-DEC-02: Hot Path Exceptions
- LMMS-LESS-02: Measure Impact Always
- LESS-02: Measure Don't Guess

## KEYWORDS

hot path, trade-offs, total performance, user experience, optimization, latency, cold start, usage patterns

---

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

---

**END OF BATCH**

**Files Created:** 4  
**Total Lines:** ~350 per file (within limit)  
**Status:** Complete

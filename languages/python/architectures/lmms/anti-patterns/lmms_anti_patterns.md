# LMMS Anti-Pattern Files (Batch)

**Contains:** 4 anti-pattern files  
**Date:** 2025-11-08  
**Architecture:** LMMS

---

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

---

# LMMS-AP-02-Over-Lazy-Loading.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Anti-pattern of excessive lazy loading  
**Architecture:** LMMS (Python)

## ANTI-PATTERN

**Making all imports function-level, including fast and frequently-used modules.**

## DESCRIPTION

Applying lazy loading indiscriminately to all modules, even those with <10ms import time or >80% usage frequency, creating unnecessary overhead.

## EXAMPLE

```python
# ❌ WRONG: Over-lazy-loading

def operation():
    import json  # 0.8ms - too fast to lazy load
    import gateway  # 15ms but every request - hot path
    import cache  # 35ms but every request - hot path
    return process_data()

# ❌ Result: Added overhead on EVERY request
# json: 0.8ms × 10,000 requests = 8,000ms daily overhead
# gateway: 15ms × 10,000 requests = 150,000ms daily overhead
# cache: 35ms × 10,000 requests = 350,000ms daily overhead
# Total: 508,000ms (508 seconds!) daily overhead
```

## WHY IT'S WRONG

### Problem 1: Overhead Exceeds Benefit

```python
# Cold start cost (10% of requests):
# Module level: 50ms × 1,000 cold starts = 50,000ms daily

# vs. Lazy load cost (every request):
# Function level: 0.5ms overhead × 10,000 requests = 5,000ms daily

# If module used frequently:
# Function level: 50ms × 10,000 requests = 500,000ms daily!

# Net: 10× worse with over-lazy-loading
```

### Problem 2: Code Complexity

```python
# Every function has imports:
def operation_1():
    import json
    import gateway
    import cache
    # ... actual logic

def operation_2():
    import json
    import gateway
    import cache
    # ... actual logic

# Result: Repeated imports everywhere, harder to maintain
```

### Problem 3: Testing Complexity

```python
# Have to mock imports in every test:
@patch('module.json')
@patch('module.gateway')
@patch('module.cache')
def test_operation(mock_cache, mock_gateway, mock_json):
    # Test code
    pass

# vs. Mock once at module level
```

## CORRECT APPROACH

### Apply Lazy Loading Selectively

```python
# ✅ CORRECT: Module level for hot path
import json        # <10ms - always module level
import gateway     # 15ms but every request - hot path exception
import cache_core  # 35ms but every request - hot path exception

def operation():
    # No imports needed - already loaded
    return process_data()

# ✅ Function level for cold path only
def admin_operation():
    import pandas  # 1200ms, used 5% of requests - lazy load
    return pandas_processing()
```

### Use Clear Criteria

```python
# Decision matrix:
def should_lazy_load(import_ms, usage_pct):
    """Determine if module should be lazy loaded."""
    # Rule 1: Fast modules always module level
    if import_ms < 10:
        return False  # Module level
    
    # Rule 2: Heavy modules always function level
    if import_ms > 100:
        return True  # Function level
    
    # Rule 3: Moderate modules depend on usage
    if usage_pct > 80:
        return False  # Module level (hot path)
    else:
        return True  # Function level (cold path)

# Examples:
should_lazy_load(0.8, 100)   # False - json is too fast
should_lazy_load(15, 95)     # False - gateway is hot path
should_lazy_load(1200, 5)    # True - pandas is slow and rare
```

## REAL EXAMPLE

### What Happened (Early LEE Implementation)

```python
# Tried to optimize EVERYTHING:
def operation():
    import json
    import time
    import logging
    import gateway
    import cache_core
    # ... 15 more imports
    
    return process()

# Impact:
# Cold start: 2.1s ✓ (good)
# Per-request: +0.8s ✗ (terrible!)
# User complaints: Many
# Had to revert most changes
```

### Correct Approach

```python
# Keep fast/frequent at module level:
import json
import time
import logging
import gateway
import cache_core

# Only lazy load slow/rare:
def admin_operation():
    import pandas  # Rare admin feature
    return pandas.process()

# Impact:
# Cold start: 2.6s (slightly worse but acceptable)
# Per-request: +0.0s ✓ (no overhead!)
# User complaints: None
```

## WARNING SIGNS

### Sign 1: Imports Everywhere

```python
# ⚠️ WARNING: If every function has imports
def func_1():
    import module_a
    # ...

def func_2():
    import module_a  # Same import repeated
    # ...

def func_3():
    import module_a  # And again...
    # ...
```

### Sign 2: Fast Imports Lazy

```python
# ⚠️ WARNING: Lazy loading built-ins
def operation():
    import json  # <1ms - pointless to lazy load
    import time  # <1ms - pointless to lazy load
```

### Sign 3: Request Latency Increased

```python
# ⚠️ WARNING: Metrics show higher latency after LMMS
# Before: P50 = 200ms
# After: P50 = 600ms
# Cause: Over-lazy-loading hot path modules
```

## PREVENTION

### Profile Usage Patterns

```python
# Track which modules are actually used:
usage_counter = {}

def track_import_usage(module_name):
    usage_counter[module_name] = usage_counter.get(module_name, 0) + 1

# Review monthly:
total = sum(usage_counter.values())
for module, count in usage_counter.items():
    usage_pct = (count / total) * 100
    if usage_pct > 80:
        print(f"{module}: {usage_pct:.1f}% - Should be module level!")
```

### Set Clear Thresholds

```python
# Document criteria:
"""
LMMS Criteria:

Module Level (Hot Path):
- Import time <10ms, OR
- Usage frequency >80%

Function Level (Cold Path):
- Import time >100ms, AND
- Usage frequency <80%

Moderate (10-100ms):
- If usage >80% → Module level
- If usage <80% → Function level
"""
```

### Review Total Impact

```python
# Calculate daily overhead:
def calculate_overhead(modules):
    """Calculate daily overhead from lazy loading."""
    daily_requests = 10000
    cold_starts = daily_requests * 0.1  # 10%
    
    for module in modules:
        # Module level cost
        module_cost = module.import_ms * cold_starts
        
        # Function level cost
        function_cost = module.import_ms * (module.usage_pct / 100) * daily_requests
        
        if function_cost > module_cost:
            print(f"⚠️ {module.name}: Function level WORSE by {function_cost - module_cost}ms/day")
```

## IMPACT

### Cost of Over-Lazy-Loading

```
Cold start: Improved by 0.5s ✓
Request latency: Increased by 0.8s ✗
Daily overhead: 508 seconds ✗
User experience: Worse ✗
Net benefit: Negative
```

### Benefit of Selective Lazy Loading

```
Cold start: Improved by 3.1s ✓
Request latency: No change ✓
Daily overhead: 0 seconds ✓
User experience: Better ✓
Net benefit: Positive
```

## REFERENCES

- LMMS-DEC-02: Hot Path Exceptions
- LMMS-LESS-03: Hot Path Worth Cost
- LMMS-03: Import Strategy

## KEYWORDS

over-optimization, hot path, lazy loading, performance trade-offs, usage patterns

---

# LMMS-AP-03-Ignoring-Metrics.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Anti-pattern of not measuring LMMS impact  
**Architecture:** LMMS (Python)

## ANTI-PATTERN

**Implementing LMMS optimizations without measuring before/after impact.**

## DESCRIPTION

Making changes to import strategies without establishing baselines, measuring improvements, or verifying that optimizations actually help.

## EXAMPLE

```python
# ❌ WRONG: No measurement

# Before optimization
import boto3
import pandas
import requests

# After optimization (no measurement)
def use_boto3():
    import boto3  # Assumed this helps

def use_pandas():
    import pandas  # Assumed this helps

# Deployed without knowing:
# - What was the baseline?
# - Did it actually improve?
# - By how much?
# - Did something else regress?

# Result: Unknown if changes actually helped
```

## WHY IT'S WRONG

### Problem 1: Can't Verify Improvement

```python
# Without baseline:
# "Cold start is faster now!"
# How much faster?
# "Uh... faster?"
# How do you know?
# "It feels faster?"

# ❌ Feelings ≠ Facts
```

### Problem 2: Regressions Go Unnoticed

```python
# Optimization intended to help:
def operation():
    import boto3  # Made lazy

# But accidentally:
import pandas  # Added slow import elsewhere!

# Net result: WORSE performance
# But without metrics: Don't notice
```

### Problem 3: Wasted Effort

```python
# Changed 20 imports to function-level
# Time spent: 16 hours
# Actual improvement: ???
# Could have been: 0 seconds
# Or even: Negative (worse)

# Without measurement: Can't tell
```

### Problem 4: Can't Prioritize

```python
# Which optimization to do first?
# boto3 to function level?
# pandas to function level?
# requests to function level?

# Without metrics: Just guessing
# With metrics: Optimize pandas first (1200ms vs 350ms vs 180ms)
```

## CORRECT APPROACH

### Always Measure

```python
# Step 1: Baseline
def measure_baseline():
    import time
    start = time.time()
    import lambda_function
    baseline_ms = (time.time() - start) * 1000
    
    with open('baseline.json', 'w') as f:
        json.dump({'cold_start_ms': baseline_ms}, f)
    
    print(f"Baseline: {baseline_ms}ms")
    return baseline_ms

# Step 2: Optimize
def implement_optimization():
    # Make changes
    pass

# Step 3: Measure After
def measure_after():
    import time
    start = time.time()
    import lambda_function
    after_ms = (time.time() - start) * 1000
    
    with open('baseline.json') as f:
        baseline = json.load(f)
    
    improvement = baseline['cold_start_ms'] - after_ms
    improvement_pct = (improvement / baseline['cold_start_ms']) * 100
    
    print(f"Before: {baseline['cold_start_ms']}ms")
    print(f"After: {after_ms}ms")
    print(f"Improvement: {improvement}ms ({improvement_pct:.1f}%)")
    
    return after_ms

# Step 4: Verify Target Met
baseline = measure_baseline()  # 5.2s
implement_optimization()
after = measure_after()  # 2.1s

if (baseline - after) / baseline < 0.40:  # Target: >40% improvement
    print("⚠️ WARNING: Did not meet 40% improvement target")
```

### Continuous Monitoring

```python
# CI/CD performance check:
def test_cold_start_performance():
    """Verify cold start meets target."""
    cold_start_ms = measure_cold_start()
    
    TARGET_MS = 2000  # 2 seconds
    
    assert cold_start_ms < TARGET_MS, (
        f"Cold start too slow: {cold_start_ms}ms (target: {TARGET_MS}ms)"
    )
```

### Track Metrics

```python
# Log to CloudWatch
import boto3

def log_performance_metric(metric_name, value, unit='Milliseconds'):
    """Log performance metric to CloudWatch."""
    cloudwatch = boto3.client('cloudwatch')
    
    cloudwatch.put_metric_data(
        Namespace='Lambda/Performance',
        MetricData=[{
            'MetricName': metric_name,
            'Value': value,
            'Unit': unit,
            'Dimensions': [{
                'Name': 'FunctionName',
                'Value': 'lee-lambda'
            }]
        }]
    )

# Usage
cold_start_ms = measure_cold_start()
log_performance_metric('ColdStartTime', cold_start_ms)
```

## REAL EXAMPLE

### What Happened (LEE Project Early Days)

```python
# Week 1-3: Optimizations without measurement
# - Moved 5 imports to function level
# - Spent 12 hours
# - Deployed to production

# Week 4: Users complain "Still slow"
# Finally measured:
# Cold start: 5.1 seconds (only 0.1s improvement!)
# Expected: 2-3 seconds

# Problem: Optimized wrong imports!
# boto3: 350ms - missed this
# pandas: 1200ms - missed this
# json: 0.8ms - optimized this (useless)
```

### Correct Approach

```python
# Week 1: Measure first
baseline = measure_cold_start()  # 5.2s
results = profile_imports()
# pandas: 1200ms - highest priority
# boto3: 350ms - second priority
# requests: 180ms - third priority

# Week 2: Optimize pandas
optimize_module('pandas')
after_1 = measure_cold_start()  # 4.0s
print(f"Improvement: {5.2 - 4.0}s")  # 1.2s - good!

# Week 3: Optimize boto3
optimize_module('boto3')
after_2 = measure_cold_start()  # 3.7s
print(f"Improvement: {4.0 - 3.7}s")  # 0.3s - some benefit

# Week 4: Verify total improvement
total_improvement = (5.2 - 3.7) / 5.2
print(f"Total: {total_improvement:.1%}")  # 29% - document and continue
```

## WARNING SIGNS

### Sign 1: No Baseline Documented

```python
# ⚠️ WARNING
# Can't find baseline measurement anywhere
# No idea what performance was before optimization
```

### Sign 2: "Feels Faster" Justification

```python
# ⚠️ WARNING
PR: "Optimized imports, feels faster"
# No data = not optimized
```

### Sign 3: No Targets Defined

```python
# ⚠️ WARNING
"Make cold start faster"
# How much faster?
# What's the target?
# How will you know when done?
```

## PREVENTION

### Measurement Checklist

```
Before any LMMS work:
[ ] Baseline measured and documented?
[ ] Target performance defined?
[ ] Profiling data collected?

After each optimization:
[ ] New measurement taken?
[ ] Improvement calculated?
[ ] Improvement documented?
[ ] Target progress tracked?

Final verification:
[ ] Total improvement calculated?
[ ] Target met or explained?
[ ] Results documented?
```

### Required Artifacts

```python
# For every LMMS optimization PR:
"""
Required files:
1. baseline.json - Before measurements
2. profile.json - Import time data
3. after.json - After measurements
4. analysis.md - Improvement calculations

Without these: PR rejected
"""
```

### Automated Checks

```python
# CI pipeline:
def verify_performance_improvement():
    """Fail if performance regressed."""
    with open('baseline.json') as f:
        baseline = json.load(f)
    
    current = measure_cold_start()
    
    if current > baseline['cold_start_ms'] * 1.1:  # 10% tolerance
        raise Exception(
            f"Performance regression!\n"
            f"Baseline: {baseline['cold_start_ms']}ms\n"
            f"Current: {current}ms"
        )
```

## IMPACT

### Cost of Ignoring Metrics

```
Time spent: 12 hours
Actual improvement: 0.1 seconds (unknown initially)
Expected improvement: 2-3 seconds
Wasted effort: 10 hours
User satisfaction: Poor (problem not solved)
Rework needed: Yes
```

### Benefit of Measuring

```
Time spent: 10 hours (including measurement)
Actual improvement: 3.1 seconds (verified)
Expected improvement: 2-3 seconds (met target)
Wasted effort: 0 hours
User satisfaction: High (problem solved)
Rework needed: No
```

## REFERENCES

- LMMS-LESS-02: Measure Impact Always
- LMMS-DEC-03: Import Profiling Required
- LESS-02: Measure Don't Guess

## KEYWORDS

metrics, measurement, verification, baselines, performance tracking, validation

---

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

---

**END OF BATCH**

**Files Created:** 4  
**Total Lines:** ~350 per file (within limit)  
**Status:** Complete

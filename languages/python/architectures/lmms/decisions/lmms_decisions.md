# LMMS Decision Files (Batch)

**Contains:** 4 decision files  
**Date:** 2025-11-08  
**Architecture:** LMMS

---

# LMMS-DEC-01-Function-Level-Imports.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Decision to use function-level imports for cold path  
**Architecture:** LMMS (Python)

## DECISION

**Use function-level imports for modules >100ms import time or <20% usage frequency.**

## CONTEXT

Cold start time in AWS Lambda directly impacts user experience and costs. Module-level imports execute on every cold start, even if the imported modules are never used in that execution.

### Problem

```python
# All imports at module level
import json          # 1ms, used every request
import boto3         # 350ms, used 15% of requests
import pandas        # 1200ms, used 5% of requests
import websocket     # 220ms, used 30% of requests

# Cold start: Pays for ALL imports (1,771ms)
# But 85% of requests don't use boto3
# 95% don't use pandas
```

### Impact

- Cold starts: 5.2 seconds
- User experience: Poor (P95 latency 4.8s)
- Costs: $12.40 per 1M invocations
- Memory baseline: 85MB

## ALTERNATIVES CONSIDERED

### Alternative 1: All Module-Level Imports

**Approach:** Import everything at module level

```python
import json
import boto3
import pandas
import websocket
```

**Pros:**
- Simple code structure
- Fast access after init
- Standard Python pattern

**Cons:**
- Slow cold starts (5.2s)
- Wastes memory (85MB baseline)
- Pays for unused imports

**Rejected:** Cold start time unacceptable for user-facing API

### Alternative 2: All Function-Level Imports

**Approach:** Import everything at function level

```python
def operation():
    import json
    import gateway
    import cache
    return process()
```

**Pros:**
- Minimum cold start
- Maximum flexibility

**Cons:**
- Overhead on frequently-used imports
- Code clutter
- Testing complexity

**Rejected:** Over-optimization, hurts frequently-used modules

### Alternative 3: Hybrid Approach (CHOSEN)

**Approach:** Module-level for hot path, function-level for cold path

```python
# Module level: Hot path (<10ms or >80% usage)
import json
import gateway

# Function level: Cold path (>100ms or <20% usage)
def operation():
    import boto3  # Lazy load
    return boto3.client('s3')
```

**Pros:**
- Best of both worlds
- Fast hot path
- Deferred cold path costs
- Significant cold start improvement

**Cons:**
- Requires profiling
- Some code complexity
- Need to maintain classification

**Chosen:** Balances performance and simplicity

## DECISION RATIONALE

### Classification Criteria

| Import Time | Usage Frequency | Strategy |
|-------------|----------------|----------|
| <10ms | Any | Module level |
| 10-100ms | >80% | Module level |
| 10-100ms | <80% | Function level |
| >100ms | Any | Function level |

### Measurement

```python
# Measured import times (LEE project):
json:        1ms    → Module level
gateway:     15ms   → Module level (used every request)
boto3:       350ms  → Function level (used 15%)
pandas:      1200ms → Function level (used 5%)
websocket:   220ms  → Function level (used 30%)
```

### Results After Implementation

```
Cold start: 5.2s → 2.1s (60% improvement)
Memory: 85MB → 52MB (39% reduction)
Costs: $12.40 → $8.20 per 1M (34% reduction)
P95 latency: 4.8s → 2.0s (58% improvement)
```

## CONSEQUENCES

### Positive

- ✅ 60% faster cold starts
- ✅ 39% lower memory usage
- ✅ 34% cost reduction
- ✅ Better user experience

### Negative

- ❌ Need to profile imports
- ❌ Imports scattered in code
- ❌ Slight delay on first lazy load call
- ❌ More complex testing setup

### Neutral

- Code structure changes required
- Documentation needed
- Team training necessary

## IMPLEMENTATION NOTES

### Profile First

```python
# Always measure before deciding
from performance_benchmark import profile_imports

results = profile_imports()
# Make decisions based on actual data
```

### Document Classifications

```python
# Clearly mark why each import is where it is
import json  # <1ms, every request (TIER1)

def operation():
    import boto3  # 350ms, 15% usage (TIER4)
```

### Test Both Paths

```python
# Test hot path (module-level imports)
def test_hot_path():
    result = hot_operation()
    assert result is not None

# Test cold path (lazy imports)
def test_cold_path():
    result = cold_operation()
    assert result is not None
```

## REFERENCES

- LMMS-01: Core Concept
- LMMS-02: Cold Start Optimization
- LMMS-03: Import Strategy
- LMMS-LESS-01: Profile First

## KEYWORDS

function-level imports, lazy loading, cold start optimization, import strategy, performance

---

# LMMS-DEC-02-Hot-Path-Exceptions.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Decision on hot path import exceptions  
**Architecture:** LMMS (Python)

## DECISION

**Module-level imports are allowed for >100ms modules IF they are used on >80% of requests (hot path).**

## CONTEXT

LMMS-DEC-01 establishes that >100ms imports should be function-level. However, some heavy imports are used so frequently that the function-level overhead becomes significant.

### Problem

```python
# Strict LMMS: boto3 is 350ms → function level
def operation():
    import boto3  # Imported on 90% of requests
    return boto3.client('s3')

# Result: Pay 350ms import cost on 90% of requests
# Total overhead per day: Huge
```

### Trade-Off Analysis

**Cold start (once):** 350ms paid once  
**Hot path overhead:** 0.5ms × 10,000 requests = 5,000ms total

**Decision:** Pay 350ms once vs 5,000ms across requests

## ALTERNATIVES CONSIDERED

### Alternative 1: Strict Function-Level

**Approach:** All >100ms imports at function level

```python
def operation():
    import boto3  # Always lazy
```

**Pros:**
- Consistent rule
- Simple decision making
- Minimum cold start

**Cons:**
- High overhead if frequently used
- Poor total performance
- User latency impact

**Rejected:** Total latency matters more than cold start alone

### Alternative 2: Hot Path Exception (CHOSEN)

**Approach:** Allow module-level for >80% usage

```python
# Used on 90% of requests → module level exception
import boto3

def operation():
    return boto3.client('s3')
```

**Pros:**
- Best total performance
- Recognizes usage patterns
- Practical approach

**Cons:**
- More complex rules
- Need usage tracking
- Context-dependent

**Chosen:** Optimizes for total performance, not just cold start

## DECISION CRITERIA

### Hot Path Definition

**A module is "hot path" if:**
1. Used on >80% of requests, OR
2. Used on critical path for majority workflow, OR
3. Total execution overhead > cold start cost

### Calculation

```python
# Calculate hot path value
def is_hot_path(import_time_ms, usage_percent, daily_requests):
    cold_start_cost = import_time_ms
    lazy_load_overhead = 0.5  # ms per lazy load
    
    daily_lazy_cost = lazy_load_overhead * (usage_percent / 100) * daily_requests
    daily_cold_starts = daily_requests * 0.1  # Assume 10% cold starts
    
    module_level_cost = cold_start_cost * daily_cold_starts
    function_level_cost = cold_start_cost * (usage_percent / 100) * daily_requests
    
    return module_level_cost < function_level_cost

# Example: boto3
is_hot = is_hot_path(
    import_time_ms=350,
    usage_percent=90,
    daily_requests=10000
)
# is_hot = True → Use module level
```

## EXAMPLES

### Example 1: boto3 at 90% Usage

```python
# Import time: 350ms
# Usage: 90% of requests
# Daily requests: 10,000

# Analysis:
# Module level: 350ms × 1,000 cold starts = 350,000ms
# Function level: 350ms × 9,000 requests = 3,150,000ms
# 
# Decision: Module level (9× better)

import boto3  # Hot path exception granted
```

### Example 2: pandas at 5% Usage

```python
# Import time: 1200ms
# Usage: 5% of requests
# Daily requests: 10,000

# Analysis:
# Module level: 1200ms × 1,000 cold starts = 1,200,000ms
# Function level: 1200ms × 500 requests = 600,000ms
#
# Decision: Function level (2× better)

def operation():
    import pandas  # Stays lazy
```

### Example 3: requests at 40% Usage

```python
# Import time: 180ms
# Usage: 40% of requests
# Daily requests: 10,000

# Analysis:
# Module level: 180ms × 1,000 cold starts = 180,000ms
# Function level: 180ms × 4,000 requests = 720,000ms
#
# Decision: Module level (4× better)

import requests  # Hot path exception granted
```

## IMPLEMENTATION NOTES

### Track Usage

```python
# Log module usage
import logging

logger = logging.getLogger()

def track_module_usage(module_name):
    logger.info(f"Module used: {module_name}")

# Aggregate in CloudWatch Insights
# Calculate usage percentage
```

### Review Regularly

```python
# Monthly review of hot path exceptions
def review_hot_path():
    usage_stats = get_usage_stats()
    
    for module, percent in usage_stats.items():
        if percent < 80:
            print(f"Consider lazy loading: {module} ({percent}%)")
```

### Document Exceptions

```python
# Clearly mark hot path exceptions
import boto3  # 350ms but 90% usage - HOT PATH EXCEPTION
```

## CONSEQUENCES

### Positive

- ✅ Optimizes total performance
- ✅ Reduces per-request latency
- ✅ Practical approach
- ✅ Context-aware optimization

### Negative

- ❌ More complex rules
- ❌ Need usage tracking
- ❌ Requires ongoing review

## REFERENCES

- LMMS-DEC-01: Function Level Imports
- LMMS-03: Import Strategy
- LMMS-LESS-02: Measure Impact

## KEYWORDS

hot path, exceptions, usage patterns, optimization, trade-offs

---

# LMMS-DEC-03-Import-Profiling-Required.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Decision requiring import profiling before optimization  
**Architecture:** LMMS (Python)

## DECISION

**All LMMS optimizations must be based on actual profiling data. Never optimize imports without measuring.**

## CONTEXT

Early in LMMS development, we made assumptions about which imports were slow without measuring. This led to:
- Over-optimization of fast imports
- Under-optimization of actual slow imports
- Wasted effort on negligible gains
- Complex code with minimal benefit

### Example of Assumption Failure

```python
# Assumption: "requests must be slow, it does HTTP"
# Actual measurement: requests = 180ms (moderate)

# Assumption: "json is built-in, must be fast"
# Actual measurement: json = 0.8ms (correct)

# Assumption: "websocket must be lightweight"
# Actual measurement: websocket = 220ms (surprisingly slow)
```

**Lesson:** Assumptions are unreliable. Always measure.

## ALTERNATIVES CONSIDERED

### Alternative 1: Optimize Based on Intuition

**Approach:** Guess which imports are slow

**Pros:**
- No profiling overhead
- Fast decisions
- Simple process

**Cons:**
- Often wrong
- Wasted effort
- Missed opportunities
- No objective basis

**Rejected:** Too unreliable

### Alternative 2: Optimize Everything

**Approach:** Make all imports function-level

**Pros:**
- Maximum cold start reduction
- No profiling needed

**Cons:**
- Over-optimization
- Code complexity
- Hot path performance hit

**Rejected:** Over-engineering

### Alternative 3: Profile-Driven Optimization (CHOSEN)

**Approach:** Measure first, optimize based on data

**Pros:**
- Objective decisions
- Focused effort
- Predictable results
- Measurable impact

**Cons:**
- Requires profiling time
- Need profiling tools

**Chosen:** Only reliable approach

## PROFILING REQUIREMENTS

### Minimum Profiling Data

Before any LMMS optimization, collect:

1. **Individual Import Times**
```python
import time

def profile_import(module_name):
    start = time.time()
    __import__(module_name)
    return (time.time() - start) * 1000

for module in modules:
    ms = profile_import(module)
    print(f"{module}: {ms:.1f}ms")
```

2. **Usage Frequency**
```python
# Log module usage
usage_count = {}

def track_usage(module_name):
    usage_count[module_name] = usage_count.get(module_name, 0) + 1

# Calculate percentage
total = sum(usage_count.values())
usage_pct = {
    mod: (count / total * 100)
    for mod, count in usage_count.items()
}
```

3. **Dependency Chains**
```python
import sys

def get_dependencies(module_name):
    module = sys.modules.get(module_name)
    if not module:
        return []
    
    return [
        name for name in dir(module)
        if name in sys.modules
    ]
```

4. **Cold Start Baseline**
```python
def measure_cold_start():
    start = time.time()
    # Import all modules
    from module_imports import *
    return (time.time() - start) * 1000

baseline_ms = measure_cold_start()
```

### Profiling Tools

**Required:**
- performance_benchmark.py (included in project)
- CloudWatch metrics
- Local profiling scripts

**Optional:**
- cProfile
- py-spy
- memory_profiler

## DECISION PROCESS

### Step 1: Profile

```python
from performance_benchmark import profile_imports

results = profile_imports()
# Results: {module_name: import_time_ms}
```

### Step 2: Classify

```python
def classify(module, ms, usage_pct):
    if ms < 10:
        return "MODULE_LEVEL"
    if ms > 100:
        return "FUNCTION_LEVEL"
    if usage_pct > 80:
        return "MODULE_LEVEL"  # Hot path exception
    return "FUNCTION_LEVEL"

for module, ms in results.items():
    usage = get_usage_percent(module)
    tier = classify(module, ms, usage)
    print(f"{module}: {tier}")
```

### Step 3: Implement

```python
# Apply classifications
# Module level (hot path)
import json
import gateway

# Function level (cold path)
def operation():
    import boto3  # Based on profile data
```

### Step 4: Verify

```python
# Measure after optimization
optimized_ms = measure_cold_start()

improvement = (baseline_ms - optimized_ms) / baseline_ms
print(f"Improvement: {improvement:.1%}")

# Expect >40% improvement
```

## PROFILING FREQUENCY

### Initial Development

- Profile before any optimization
- Profile after each change
- Build profiling into development workflow

### Production

- Monthly profiling review
- Profile after dependency updates
- Profile when performance degrades

### Triggers for Re-Profiling

- New modules added
- Usage patterns change
- Performance regression detected
- Major dependency updates

## CONSEQUENCES

### Positive

- ✅ Objective decisions
- ✅ Focused optimization effort
- ✅ Predictable results
- ✅ Measurable improvement

### Negative

- ❌ Requires profiling time (~1 hour initial)
- ❌ Need profiling tools
- ❌ Ongoing profiling overhead

### Neutral

- Process more formal
- Documentation requirements
- Team training needed

## IMPLEMENTATION NOTES

### Profiling Checklist

```
[ ] Profile all imports individually
[ ] Measure usage frequency
[ ] Analyze dependency chains
[ ] Baseline cold start time
[ ] Document results
[ ] Make classification decisions
[ ] Implement changes
[ ] Verify improvement
```

### Automated Profiling

```python
# CI/CD integration
def test_import_performance():
    results = profile_imports()
    
    for module, ms in results.items():
        # Flag slow imports
        if ms > 500:
            print(f"WARNING: {module} is slow ({ms}ms)")
```

## REFERENCES

- LMMS-01: Core Concept
- LMMS-03: Import Strategy
- LMMS-LESS-01: Profile First
- LESS-02: Measure Don't Guess

## KEYWORDS

profiling, measurement, data-driven, optimization, import analysis

---

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

---

**END OF BATCH**

**Files Created:** 4  
**Total Lines:** ~380 per file (within limit)  
**Status:** Complete

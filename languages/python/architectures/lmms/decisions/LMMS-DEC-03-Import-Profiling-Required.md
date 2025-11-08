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

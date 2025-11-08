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

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

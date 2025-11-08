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

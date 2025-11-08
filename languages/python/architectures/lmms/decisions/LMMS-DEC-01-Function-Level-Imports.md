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

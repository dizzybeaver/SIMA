# AWS-Lambda-DEC-03-Timeout-Limits.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Decision documentation for AWS Lambda timeout configuration  
**Category:** Platform - AWS Lambda Decision

---

## Decision

**DEC-03: Configure Appropriate Timeout Limits for Lambda Functions**

**Status:** Accepted  
**Date:** 2025-11-08  
**Context:** AWS Lambda functions require explicit timeout configuration to prevent runaway executions

---

## Context

AWS Lambda enforces maximum execution time per invocation:
- **Default:** 3 seconds
- **Range:** 1 second to 900 seconds (15 minutes)
- **Billing:** Charged for actual runtime, rounded up to nearest 1ms

**Problem:** Functions that exceed timeout are forcefully terminated with no graceful shutdown

---

## Decision

Set function-specific timeouts based on:
1. Expected execution duration
2. Worst-case scenario timing
3. Cost optimization
4. User experience requirements

**Formula:**
```
Timeout = (P95_Duration × 1.5) + Safety_Margin

Where:
- P95_Duration: 95th percentile execution time
- 1.5x multiplier: Account for variance
- Safety_Margin: 1-3 seconds buffer
```

---

## Rationale

### Why Not Use Maximum (900s)?

**Problems:**
- Extended billing for stuck functions
- Delayed error detection
- Resource held unnecessarily
- Poor user experience (long wait before failure)

### Why Not Use Minimum (1s)?

**Problems:**
- Frequent timeouts under normal load
- No room for variance
- Cold starts may exceed limit
- Retry storms from premature termination

### Why P95-Based Configuration?

**Benefits:**
- Covers 95% of normal cases
- Allows for acceptable variance
- Identifies anomalies (>P95 triggers investigation)
- Balances cost and reliability

---

## Implementation Guidelines

### Step 1: Profile Function

**Collect metrics:**
```
Duration samples over 7 days:
P50: 450ms
P75: 680ms
P90: 920ms
P95: 1100ms
P99: 2800ms (outlier, investigate)
Max: 4200ms (cold start)
```

### Step 2: Calculate Timeout

**For LEE example:**
```
P95_Duration: 1100ms
× 1.5: 1650ms
+ Safety_Margin (2s): 3650ms
→ Configure: 4000ms (4 seconds)
```

**Rationale:**
- Covers 95% of invocations comfortably
- Cold starts (2-3s) fit within limit
- Anomalies (>4s) fail fast
- User sees result or error within 4s

### Step 3: Set Alarms

**Monitor:**
- Invocations approaching timeout (>80% of limit)
- Timeout errors (function killed)
- Duration trends (increasing over time)

**Alert thresholds:**
- Duration >80% of timeout: Warning
- Timeout errors >1%: Critical
- P95 duration increasing: Investigate

---

## Configuration Examples

### API Gateway Integration

**Type:** Synchronous, user-facing  
**Requirements:** Fast response, good UX

```yaml
Function: api-handler
Timeout: 5000ms (5 seconds)
Rationale:
  - P95 duration: 1200ms
  - 1.5x buffer: 1800ms
  - Safety margin: 2s
  - API Gateway timeout: 29s (well under)
```

### Background Processing

**Type:** Asynchronous, batch job  
**Requirements:** Complete processing, cost-efficient

```yaml
Function: batch-processor
Timeout: 120000ms (2 minutes)
Rationale:
  - P95 duration: 45s
  - 1.5x buffer: 67s
  - Safety margin: 30s
  - Processing can be lengthy
```

### WebSocket Handler

**Type:** Real-time, connection-aware  
**Requirements:** Keep connection alive

```yaml
Function: websocket-handler
Timeout: 10000ms (10 seconds)
Rationale:
  - P95 duration: 3s
  - 1.5x buffer: 4.5s
  - Safety margin: 3s
  - WebSocket idle timeout: 10 minutes
```

---

## LEE Project Configuration

### Main Handler

```yaml
Function: LEE-main-handler
Timeout: 4000ms (4 seconds)

Breakdown:
  P95_Duration: 1100ms
  Cold_Start_Max: 2100ms
  Buffer: 1.5x
  Safety: 2000ms
  Total: 4000ms

Monitors:
  - Duration >3200ms (80% threshold)
  - Timeout errors
  - Cold start frequency
```

### Rationale for 4s

**Covers:**
- ✅ 95% of warm invocations (<1.1s)
- ✅ All cold starts (<2.1s)
- ✅ Home Assistant API latency spikes
- ✅ WebSocket reconnection time

**Fails Fast:**
- ❌ Stuck operations (>4s = error)
- ❌ Runaway loops
- ❌ Network hangs

**User Experience:**
- Alexa response: 4s acceptable
- Device control: 4s acceptable
- Error feedback: Better than 30s hang

---

## Timeout Handling Pattern

### Graceful Degradation

```python
import signal
import time

# Set up timeout handler
def timeout_handler(signum, frame):
    raise TimeoutError("Function execution time limit exceeded")

def handler(event, context):
    # Calculate remaining time
    remaining_ms = context.get_remaining_time_in_millis()
    
    # Set internal timeout (80% of Lambda timeout)
    internal_timeout = remaining_ms * 0.8 / 1000
    
    signal.signal(signal.SIGALRM, timeout_handler)
    signal.alarm(int(internal_timeout))
    
    try:
        result = process(event)
        signal.alarm(0)  # Cancel alarm
        return result
    except TimeoutError:
        # Graceful degradation
        return {
            'statusCode': 504,
            'body': 'Operation timed out, please retry'
        }
```

### Early Exit Strategy

```python
def handler(event, context):
    start_time = time.time()
    
    for item in large_batch:
        # Check time periodically
        if time.time() - start_time > 3.0:  # 1s before timeout
            # Save progress, return partial result
            save_checkpoint(processed_items)
            return {
                'statusCode': 206,  # Partial Content
                'body': 'Partial processing complete',
                'checkpoint': processed_items
            }
        
        process(item)
```

---

## Common Timeout Issues

### Issue 1: Premature Timeout

**Symptom:** Function times out during normal operation

**Causes:**
- Timeout too aggressive
- External API slow
- Database query slow
- Network latency

**Solutions:**
- Increase timeout to P95 + buffer
- Optimize slow operations
- Add retry logic for external calls
- Cache frequently accessed data

### Issue 2: Stuck Functions

**Symptom:** Function runs until maximum timeout

**Causes:**
- Infinite loop
- Blocking operation
- Network hang
- Deadlock

**Solutions:**
- Reduce timeout to fail faster
- Add internal timeout checks
- Implement circuit breakers
- Monitor and alert on long durations

### Issue 3: Inconsistent Duration

**Symptom:** Wide variance in execution time

**Causes:**
- Cold vs warm starts
- Variable payload sizes
- External API variability
- Database performance

**Solutions:**
- Profile separately (cold vs warm)
- Set timeout for worst case
- Optimize cold start (LMMS)
- Add request timeout limits

---

## Monitoring and Alerting

### CloudWatch Metrics

**Track:**
- `Duration`: Actual execution time
- `Timeout`: Configured timeout value
- `Errors`: Functions terminated by timeout

**Calculate:**
- Timeout ratio: Errors / Invocations
- Duration utilization: Duration / Timeout
- P95 trend: Increasing duration over time

### Alarms

```yaml
Alarm: High-Duration-Warning
Metric: Duration
Threshold: 3200ms (80% of 4000ms)
Action: Notify team, investigate

Alarm: Timeout-Errors-Critical
Metric: Errors (timeout)
Threshold: >1% of invocations
Action: Page on-call, immediate action

Alarm: Duration-Trend-Increase
Metric: P95 Duration
Threshold: >20% increase over 7 days
Action: Performance review
```

---

## Related Decisions

**Dependencies:**
- DEC-01: Single-Threaded Execution
- DEC-02: Memory Constraints
- DEC-04: Stateless Design

**Influenced By:**
- Cold start optimization (LMMS)
- Memory allocation
- External API latency
- User experience requirements

**Influences:**
- Retry strategy
- Error handling
- Circuit breaker configuration
- API Gateway timeout

---

## References

**Architecture:**
- AWS-Lambda-Core-Concepts.md
- AWS-Lambda-Execution-Model.md

**Lessons:**
- AWS-Lambda-LESS-03-Timeout-Management.md
- LESS-09-Systematic-Investigation.md

**Anti-Patterns:**
- AWS-Lambda-AP-03-Ignoring-Timeouts.md

**Project:**
- LEE-DEC-04-Timeout-Configuration.md

---

## Key Takeaways

**Set timeout based on actual metrics:**
Use P95 duration as baseline, not guesses

**Balance fail-fast and reliability:**
Too short = false failures, too long = stuck functions

**Monitor continuously:**
Duration trends indicate needed adjustments

**Implement graceful degradation:**
Handle timeouts explicitly, don't rely on termination

**Consider cold starts:**
Timeout must accommodate initialization time

---

**Decision ID:** DEC-03  
**Keywords:** timeout configuration, execution limits, performance monitoring, error handling  
**Related Topics:** Cold start optimization, error handling, monitoring, user experience, cost optimization

---

**END OF FILE**

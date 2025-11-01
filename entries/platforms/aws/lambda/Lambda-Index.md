# Lambda-Index.md

**Version:** 4.0.0  
**Date:** 2025-11-01  
**Purpose:** Category index for Lambda patterns

**Category:** AWS Lambda  
**Patterns:** 3 (AWS-LESS-03, 11, 12)  
**Priority:** Critical foundation for serverless development  
**Location:** `/sima/aws/lambda/`

---

## Overview

Lambda-specific patterns covering stateless execution model, cost optimization strategy, and timeout management. These patterns are fundamental to serverless development and must be understood before Lambda implementation.

---

## Patterns in This Category

### AWS-LESS-03: Stateless Execution Patterns

**File:** `AWS-Lambda-StatelessExecution_AWS-LESS-03.md`  
**Priority:** 🟠 High  
**Status:** Active

**Summary:** Ephemeral execution environments require Initialize → Process → Commit pattern. Never rely on in-memory state persisting across invocations. Fetch state from durable storage, process with local variables, commit before returning.

**Core Principle:** Treat each invocation as potentially the last.

**Use When:**
- Designing Lambda functions
- Managing state in serverless
- Understanding container lifecycle
- Implementing initialization patterns

**Key Patterns:**
- Initialize infrastructure outside handler (connection pools, configs)
- Fetch mutable state inside handler (from database/cache)
- Process with local variables only
- Commit to durable storage before exit
- Never use global mutable state

**Cross-Reference:**
- Project: NM01/ARCH-07 (LMMS implementation)
- Project: NM04/DEC-01 (SUGA pattern with similar initialization)

---

### AWS-LESS-11: Pay-Per-Use Cost Model

**File:** `AWS-Lambda-CostModel_AWS-LESS-11.md`  
**Priority:** 🟠 High  
**Status:** Active

**Summary:** Pay-per-use pricing fundamentally changes optimization strategy. Traditional: maximize server utilization. Serverless: minimize per-request execution time. Different model, different goals.

**Core Principle:** Optimize for per-request cost, not server utilization.

**Use When:**
- Cost optimization decisions
- Performance tuning strategies
- Resource allocation planning
- Comparing serverless vs traditional

**Key Insights:**
- Traditional: Pack more requests per server (high utilization good)
- Serverless: Faster requests, lower memory (low per-request cost good)
- Zero usage = zero cost (idle servers waste money)
- Batch processing becomes anti-pattern
- Memory × Time = Cost formula

**Cost Break-Even:**
- < 500M requests/month: Serverless usually cheaper
- > 500M requests/month: Traditional might win
- Spiky traffic: Serverless dramatically cheaper

**Cross-Reference:**
- Project: NM06/LESS-17 (Performance optimization)
- AWS: AWS-LESS-12 (Timeout constraints affect cost)

---

### AWS-LESS-12: Function Timeout Constraints

**File:** `AWS-Lambda-TimeoutConstraints_AWS-LESS-12.md`  
**Priority:** 🔴 Critical  
**Status:** Active

**Summary:** Short-lived execution environments impose hard time limits (15 minutes maximum). Design functions to complete quickly or use chunking, continuation, or external orchestration patterns. No partial credit for timeouts.

**Core Principle:** Design for hard deadlines. Chunk work or fail.

**Use When:**
- Long-running work (> 1 minute)
- Batch processing
- File processing
- Variable-duration work
- Unpredictable execution time

**Three Solutions:**
1. **Chunking:** Divide work, process batches, track progress
2. **Continuation:** Function invokes itself with remaining work
3. **Orchestration:** External workflow engine (Step Functions)

**Time Budget Planning:**
- Max: 15 minutes
- Safe: 12 minutes (leave margin)
- Per-operation: 720s / operation_count

**Cross-Reference:**
- Project: NM01/ARCH-07 (Initialization optimization)
- AWS: AWS-LESS-03 (Stateless execution model)
- AWS: AWS-LESS-11 (Cost implications of timeouts)

---

## Quick Decision Guide

### Scenario 1: New Lambda Function

**Design Checklist:**
1. Apply AWS-LESS-03: Stateless pattern
   - Initialize: Outside handler (connections, configs)
   - Fetch: Inside handler (current state from storage)
   - Process: Local variables only
   - Commit: Save to storage before return

2. Consider AWS-LESS-11: Cost optimization
   - Minimize execution time per request
   - Right-size memory allocation
   - Don't batch if not needed
   - Test memory vs speed trade-offs

3. Plan for AWS-LESS-12: Timeout limits
   - Estimate worst-case duration
   - If > 1 minute: Consider chunking
   - If > 15 minutes: Must chunk/orchestrate
   - Build timeout monitoring

### Scenario 2: Lambda Performance Issues

**Diagnosis Path:**
```
Problem: High costs
→ AWS-LESS-11: Check per-request optimization
  - Reduce execution time?
  - Lower memory allocation?
  - Remove unnecessary work?

Problem: Timeouts
→ AWS-LESS-12: Implement chunking
  - Divide work into batches
  - Track progress externally
  - Use continuation pattern

Problem: Inconsistent state
→ AWS-LESS-03: Check stateless pattern
  - Fetching state from storage?
  - Committing before return?
  - Not relying on global state?
```

### Scenario 3: Cost Optimization

**Analysis Framework:**
```
1. Measure current costs (AWS-LESS-11)
   - Execution time per request
   - Memory allocation used
   - Request volume

2. Identify opportunities
   - Reduce execution time (AWS-LESS-11)
   - Optimize initialization (AWS-LESS-03)
   - Right-size memory allocation

3. Validate improvements
   - Test memory vs speed trade-offs
   - Measure actual savings
   - Monitor user experience impact
```

---

## Common Patterns

### Pattern: Initialize Once, Process Many

**From:** AWS-LESS-03

```python
# Outside handler (once per container)
db_pool = create_connection_pool()
config = load_config()

# Inside handler (per invocation)
def handler(event, context):
    # Fetch state
    user = db_pool.get(event['userId'])
    
    # Process
    result = process_user(user)
    
    # Commit
    db_pool.save(user_id, result)
    
    return result
```

### Pattern: Time-Bounded Processing

**From:** AWS-LESS-12

```python
def handler(event, context):
    start_time = current_time()
    safe_timeout = 12 * 60  # 12 minutes
    
    while (current_time() - start_time) < safe_timeout:
        chunk = get_next_chunk()
        if chunk is None:
            return {"status": "complete"}
        
        process(chunk)
    
    # Timeout approaching
    invoke_continuation()
    return {"status": "continuing"}
```

### Pattern: Cost-Optimized Configuration

**From:** AWS-LESS-11

```
Test configurations:

128MB @ 200ms = $0.0000004
256MB @ 100ms = $0.0000004 (same cost, 2x faster!)
512MB @ 60ms  = $0.00000048 (20% more, 3x faster!)

Recommendation: 256MB or 512MB
- Better user experience
- Same or slightly higher cost
- Worth the trade-off
```

---

## Anti-Patterns to Avoid

**From AWS-LESS-03:**
- ❌ Global mutable state (counters, sessions)
- ❌ Unbounded caching without TTL
- ❌ Assuming container persists
- ❌ Not committing state before return

**From AWS-LESS-11:**
- ❌ Traditional batching (increases latency and cost)
- ❌ Keep-warm pings (98% waste)
- ❌ Over-allocating memory (23x over-allocation)
- ❌ Optimizing for server utilization

**From AWS-LESS-12:**
- ❌ Ignoring timeout constraints
- ❌ Processing variable-size batches without chunking
- ❌ Waiting for external APIs indefinitely
- ❌ Single-function large file processing

---

## Related Content

### Cross-References to Other AWS Categories

**aws/general/**
- AWS-LESS-01: Processing patterns (choose sync/async/stream)

**aws/api-gateway/**
- AWS-LESS-09: Proxy integration (affects Lambda interface)

### Cross-References to Project Maps

**NM01 - Architecture:**
- ARCH-07 (LMMS): Implements AWS-LESS-03 patterns

**NM04 - Decisions:**
- DEC-01 (SUGA): Similar gateway initialization pattern

**NM06 - Lessons:**
- LESS-02: Measure don't guess (complements AWS-LESS-11)
- LESS-17: Performance optimization (uses AWS-LESS-11 principles)

---

## Keywords

Lambda, serverless, stateless, pay-per-use, timeout, cost optimization, execution model, ephemeral compute, chunking, continuation, initialization

---

## Navigation

- **Up:** AWS-Master-Index.md
- **Siblings:** 
  - aws/general/General-Index.md
  - aws/dynamodb/DynamoDB-Index.md
  - aws/api-gateway/APIGateway-Index.md
- **Quick Index:** AWS-Quick-Index.md

**Total Patterns:** 3  
**All Critical/High Priority:** ✅  
**SIMAv4 Compliant:** ✅

**End of Index**

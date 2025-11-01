# AWS-Lambda-CostModel_AWS-LESS-11.md

**Version:** 1.0.0  
**Date:** 2025-11-01  
**Purpose:** Pay-per-use cost model optimization strategies

**REF-ID:** AWS-LESS-11  
**Category:** AWS Lambda  
**Type:** LESS (Lesson Learned)  
**Priority:** 🟠 High  
**Status:** Active  
**Source:** AWS Serverless Developer Guide (Genericized)

---

## Summary

Pay-per-use pricing fundamentally changes optimization strategy. Always-on systems optimize for maximum throughput per server. Pay-per-use systems optimize for minimum execution time and memory per request. Different pricing model, different optimization goals.

**Core Principle:** Traditional server optimization (maximize utilization) becomes anti-pattern in pay-per-use. Instead: minimize per-request cost, accept zero utilization during idle periods.

---

## The Cost Model Shift

**Traditional: Fixed Capacity (Always-On)**
```
Billing Model:
  Pay for: Server capacity (running 24/7)
  Cost: $X per server-hour
  Reality: Pay even when idle
  
Optimization Goal:
  Maximize: Requests per server
  Metric: CPU/Memory utilization (want high)
  Strategy: Keep servers busy
```

**Serverless: Variable Usage (Pay-Per-Use)**
```
Billing Model:
  Pay for: Actual execution time + memory
  Cost: $X per GB-second of compute
  Reality: Zero usage = zero cost
  
Optimization Goal:
  Minimize: Time × Memory per request
  Metric: Per-request cost (want low)
  Strategy: Make requests fast and lean
```

---

## Cost Calculation Comparison

**Traditional Server Costs:**
```
Server: $100/month (720 hours)
Capacity: 1000 requests/hour max

Scenario 1: Low Usage (100 requests/month)
  Cost: $100 (server always running)
  Per-request: $1.00

Scenario 2: Medium Usage (100K requests/month)
  Cost: $100 (same server)
  Per-request: $0.001

Scenario 3: High Usage (1M requests/month)
  Cost: $100 (same server)
  Per-request: $0.0001
  
Pattern: More requests = cheaper per request
Goal: Pack more requests per server
```

**Pay-Per-Use Costs:**
```
Function: 128MB memory, variable execution time
Cost: $0.0000002 per 100ms execution

Scenario 1: Low Usage (100 requests, 100ms each)
  Cost: 100 × $0.0000002 = $0.00002
  Per-request: $0.0000002

Scenario 2: Medium Usage (100K requests, 100ms each)
  Cost: 100,000 × $0.0000002 = $0.02
  Per-request: $0.0000002 (same!)

Scenario 3: High Usage (1M requests, 100ms each)
  Cost: 1,000,000 × $0.0000002 = $0.20
  Per-request: $0.0000002 (still same!)
  
Pattern: Per-request cost is constant
Goal: Minimize execution time per request
```

---

## The Optimization Inversion

**Traditional Optimization:**
```
Goal: Maximize server utilization
  ✅ Batch requests together
  ✅ Keep connections alive
  ✅ Cache aggressively (memory is free)
  ✅ Tolerate some latency for efficiency
  ❌ Don't worry about per-request time
  
Example:
  10 requests @ 100ms each = 1 second total
  vs
  1 batch @ 1000ms = 1 second total
  → Same utilization, batch preferred (fewer context switches)
```

**Serverless Optimization:**
```
Goal: Minimize per-request execution time
  ✅ Process requests individually (don't batch)
  ✅ Minimize cold starts (initialization cost)
  ✅ Cache judiciously (memory costs per-request)
  ✅ Optimize for speed over utilization
  ❌ Don't batch (increases latency AND cost)
  
Example:
  10 requests @ 10ms each = 100ms total cost
  vs
  1 batch @ 100ms = 100ms per request × 10 = 1000ms total cost
  → Individual processing 10x cheaper!
```

---

## Volume-Based Break-Even Analysis

**Break-Even Calculation:**
```
Traditional Server:
  Fixed: $100/month
  Variable: $0/request
  
Serverless (100ms execution, 128MB):
  Fixed: $0/month
  Variable: $0.0000002/request

Break-Even Point:
  $100 = X requests × $0.0000002
  X = 500,000,000 requests
  
Conclusion:
  < 500M requests/month → Serverless cheaper
  > 500M requests/month → Server might be cheaper
```

**Volume-Based Recommendations:**
```
Very Low Volume (< 1K requests/day):
  Traditional: $100/month (idle 99.9% of time)
  Serverless: $0.06/month
  Winner: Serverless (1667x cheaper)
  
Low Volume (< 10K requests/day):
  Traditional: $100/month (idle 99% of time)
  Serverless: $0.60/month
  Winner: Serverless (167x cheaper)
  
Medium Volume (100K requests/day):
  Traditional: $100-200/month (1-2 servers)
  Serverless: $60/month (3M requests)
  Winner: Depends on execution time
  
High Volume (1M requests/day):
  Traditional: $500/month (5 servers)
  Serverless: $600/month (30M requests)
  Winner: Traditional (if requests small)
  
Very High Volume (10M requests/day):
  Traditional: $2000/month (20 servers)
  Serverless: $6000/month (300M requests)
  Winner: Traditional
  
Pattern: Serverless wins at low-medium volume,
         Traditional wins at very high volume
```

---

## Spiky Traffic Economics

**Traditional Approach (Over-Provisioning):**
```
Traffic Pattern:
  Normal: 100 requests/min
  Peak: 10,000 requests/min (100x spike)
  
Server Provisioning:
  Must provision for: Peak (10,000 req/min)
  Servers needed: 20 servers
  Cost: $2000/month
  
Utilization:
  Peak (1 hour/day): 100% utilized
  Normal (23 hours/day): 1% utilized
  Average: ~5% utilization (wasted 95%)
  
Waste: $1900/month paying for idle capacity
```

**Serverless Approach (Automatic Scaling):**
```
Traffic Pattern:
  Normal: 100 requests/min
  Peak: 10,000 requests/min (100x spike)
  
Provisioning:
  Automatic: Scales 0 → 10,000 concurrent
  Cost: Pay for actual usage only
  
Cost Calculation:
  Normal (23 hours): 100 req/min × 1380 min = 138K requests
  Peak (1 hour): 10,000 req/min × 60 min = 600K requests
  Total: 738K requests/day × 30 days = 22.1M requests
  
  Cost: 22.1M × $0.0000002 = $4.42/month
  
Savings: $1995.58/month (99.8% cheaper)
```

---

## Memory vs Speed Trade-off

**Serverless Pricing: Time × Memory**
```
Cost Formula:
  Cost = Execution_Time × Memory_Allocation × Rate
  
Example Pricing:
  128MB @ 100ms = $0.0000002
  256MB @ 50ms = $0.0000002 (same cost!)
  512MB @ 25ms = $0.0000002 (same cost!)
  1024MB @ 12.5ms = $0.0000002 (same cost!)
  
Insight: 2x memory → 2x speed → Same cost
         (if operation is CPU-bound)
```

**Optimization Strategy:**
```
Test configuration options:

Config A: 128MB, 200ms execution
  Cost: $0.0000004 per request
  
Config B: 256MB, 100ms execution (faster CPU)
  Cost: $0.0000004 per request (same!)
  Latency: 2x faster → Better UX
  
Config C: 512MB, 60ms execution
  Cost: $0.00000048 per request (20% more)
  Latency: 3.3x faster → Much better UX
  
Recommendation: Config B or C
  - Same or slightly higher cost
  - Much better performance
  - Happy users worth 20% cost increase
```

---

## Cost Anti-Patterns

**Anti-Pattern 1: Traditional Batching**
```
❌ WRONG (Traditional Thinking):
  Batch 100 requests together
  Process in one function call
  "Saves overhead!"
  
Reality:
  Request 1: Waits 99 requests (bad UX)
  Function: Runs 100x longer (costs 100x more)
  Total cost: Same or worse
  Latency: 100x worse
  
✅ RIGHT (Serverless Thinking):
  Process each request individually
  100 parallel function invocations
  Total cost: Same
  Latency: 100x better
```

**Anti-Pattern 2: Keeping Functions Warm**
```
❌ WRONG:
  Schedule pings every 5 minutes
  Keep function container alive
  "Avoid cold starts!"
  
Cost:
  288 pings/day (every 5 min)
  30 days = 8,640 pings/month
  Cost: ~$0.17/month per function
  
Reality:
  Real requests: 100/month
  Warmup pings: 8,640/month
  Paying for: 98.8% waste
  
✅ RIGHT:
  Accept occasional cold starts
  Optimize initialization (LMMS pattern)
  Let platform manage containers
```

**Anti-Pattern 3: Over-Allocating Memory**
```
❌ WRONG:
  "More memory is faster, allocate max!"
  Function uses 128MB
  Allocated: 3008MB (max)
  
Cost Impact:
  Paying for: 3008MB
  Using: 128MB
  Waste: 23.5x over-allocation
  
✅ RIGHT:
  Test actual memory usage
  Allocate slightly above peak
  Example: 128MB used → allocate 192MB (buffer)
```

**Anti-Pattern 4: Long-Running Processes**
```
❌ WRONG:
  Run batch job in single function
  Duration: 14 minutes
  Cost: 14 min × high memory
  
✅ RIGHT:
  Chunk into 1-minute segments
  Process in parallel
  Duration: 1 minute
  Cost: Same total, but faster and more resilient
```

---

## When Traditional Servers Win

**High-Volume, Small-Request Scenarios:**
```
Characteristics:
  - Millions of requests per day
  - Very fast execution (< 50ms)
  - Predictable, steady load
  - Simple processing

Example: Image CDN
  - 10M requests/day
  - 20ms average response
  - Always-on server: $500/month
  - Serverless: $2000/month
  
Winner: Traditional (4x cheaper)
```

**Long-Running, Always-On Services:**
```
Characteristics:
  - WebSocket servers
  - Real-time data processing
  - Continuous connections
  - Stateful services

Example: Chat server
  - 1000 concurrent connections
  - Always connected
  - Server: $200/month
  - Serverless: Impossible (timeout limits)
  
Winner: Traditional (only option)
```

**When Serverless Wins:**
```
Characteristics:
  - Low to medium volume
  - Spiky, unpredictable traffic
  - Infrequent usage
  - Variable execution time
  - Event-driven processing

Example: Webhook processor
  - 10K requests/month (variable)
  - Some hours: 0 requests
  - Peak hours: 1000 requests/hour
  - Server: $100/month (mostly idle)
  - Serverless: $2/month (actual usage)
  
Winner: Serverless (50x cheaper)
```

---

## Optimization Checklist

**For Pay-Per-Use Systems:**
```
Performance Optimization:
  ✅ Minimize execution time per request
  ✅ Remove unnecessary processing
  ✅ Optimize cold start time (LMMS)
  ✅ Use appropriate memory allocation
  ✅ Parallel processing where possible
  
Cost Optimization:
  ✅ Right-size memory allocation
  ✅ Eliminate warmup strategies
  ✅ Process individually, not in batches
  ✅ Remove unused dependencies
  ✅ Cache intelligently (cost/benefit)
  
Avoid:
  ❌ Traditional batching patterns
  ❌ Keep-alive pings
  ❌ Over-allocated memory
  ❌ Long-running processes
```

---

## Why This Matters

**Mental Model Shift:**
- Traditional: Server utilization is success metric
- Serverless: Per-request cost is success metric
- Using traditional optimization → Wastes money

**Cost Predictability:**
- Traditional: Fixed cost, variable efficiency
- Serverless: Variable cost, but proportional to usage
- Serverless better for unpredictable loads

**Business Impact:**
- Wrong model = 10-100x cost difference
- Low volume: Serverless wins dramatically
- High volume: Traditional may be better

---

## When to Apply

**Initial Design:**
- ✅ Estimate request volume
- ✅ Measure execution time
- ✅ Calculate break-even point
- ✅ Consider traffic patterns

**Cost Optimization:**
- 📊 Profile actual memory usage
- 📊 Measure execution time distribution
- 📊 Identify expensive functions
- 📊 Test memory/speed trade-offs

**Architecture Review:**
- 🔍 Look for traditional optimization patterns
- 🔍 Check for batching (remove it)
- 🔍 Verify memory allocation
- 🔍 Question keep-warm strategies

---

## Cross-References

**AWS Patterns:**
- AWS-Lambda-TimeoutConstraints_AWS-LESS-12.md - Time limits impact cost
- AWS-General-ProcessingPatterns_AWS-LESS-01.md - Pattern selection affects cost

**Project Maps:**
- /sima/entries/core/ARCH-LMMS_Lambda Memory Management System.md - Memory optimization strategies
- /sima/entries/lessons/performance/LESS-20.md - Performance measurement
- /sima/entries/lessons/performance/LESS-21.md - Optimization strategies

---

## Keywords

pay-per-use, serverless pricing, cost optimization, resource allocation, execution time, memory allocation, billing model, utilization

---

**Location:** `/sima/aws/lambda/`  
**Total Lines:** 390 (≤400 for SIMAv4)  
**SIMAv4 Compliant:** ✅

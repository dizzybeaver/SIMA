# AWS-General-ProcessingPatterns_AWS-LESS-01.md

**Version:** 1.0.0  
**Date:** 2025-11-01  
**Purpose:** Three fundamental serverless data processing patterns

**REF-ID:** AWS-LESS-01  
**Category:** AWS General  
**Type:** LESS (Lesson Learned)  
**Priority:** 🔴 High  
**Status:** Active  
**Source:** AWS Serverless Developer Guide (Genericized)

---

## Summary

Serverless workloads fall into three fundamental patterns: synchronous (request-response), asynchronous (queued processing), and streaming (continuous data flow). Each pattern has distinct characteristics for latency, scaling, error handling, and complexity.

**Core Principle:** Choose processing pattern based on user expectations and business requirements, not technical convenience. Wrong pattern leads to poor UX and wasted resources.

---

## The Three Patterns

### Pattern 1: Synchronous (Request-Response)

**Characteristics:**
- Client waits for response
- Latency: < 1 second required
- User Experience: Immediate feedback expected
- Error Handling: Simple (return error to client)

**Flow:**
```
Client → Request → Server → Process → Response → Client
(Client blocks and waits)
```

**When to Use:**
```
✅ User expects immediate response
✅ Operation completes quickly (< 1 second)
✅ Result needed before next action
✅ Interactive workflows

Examples:
  - API queries (GET /user/123)
  - Form submissions (POST /order)
  - Search requests
  - Shopping cart retrieval
  - User login/authentication
```

**Advantages:**
- Simple mental model (request → response)
- Easy error propagation
- Straightforward debugging
- No state tracking needed

**Disadvantages:**
- Client blocked during processing
- Timeout constraints (< 30 seconds typically)
- Cannot handle long-running work
- User waits for all processing

### Pattern 2: Asynchronous (Queued Processing)

**Characteristics:**
- Fire-and-forget with status tracking
- Latency: Seconds to minutes acceptable
- User Experience: Status polling or callbacks
- Error Handling: Complex (retries, dead letter queues)

**Flow:**
```
Client → Request → Queue → (Client receives job ID)
Background → Dequeue → Process → Update Status
Client → Poll Status → Get Result
```

**When to Use:**
```
✅ Operation takes > 1 second
✅ User doesn't need immediate result
✅ Background processing acceptable
✅ Retry logic valuable

Examples:
  - Image/video processing
  - File conversion
  - Report generation
  - Batch data imports
  - Email sending
```

**Advantages:**
- No client blocking
- Independent scaling of components
- Built-in retry mechanisms
- Load leveling (queue buffers spikes)
- Can handle variable processing time

**Disadvantages:**
- Complex state tracking required
- Status polling overhead
- Eventual consistency
- Harder to debug (distributed)
- More moving parts

### Pattern 3: Streaming (Continuous Flow)

**Characteristics:**
- Continuous data flow processing
- Latency: Near real-time (< 100ms)
- User Experience: Live updates, real-time analytics
- Error Handling: Very complex (checkpointing, ordering)

**Flow:**
```
Source → Stream → Process → Output Stream → Sink
(Continuous, never-ending flow)
```

**When to Use:**
```
✅ Continuous data generation
✅ Real-time analytics needed
✅ Event ordering important
✅ High throughput required

Examples:
  - IoT sensor data processing
  - Log aggregation and analysis
  - Real-time metrics dashboards
  - Stock price updates
  - Social media feeds
```

**Advantages:**
- True real-time processing
- High throughput possible
- Ordered event processing
- Automatic checkpointing
- Replay capability

**Disadvantages:**
- Most complex pattern
- Requires stream infrastructure
- Complex state management
- Ordering guarantees difficult
- Debugging very challenging

---

## Pattern Selection Framework

**Decision Tree:**
```
Q1: Does user expect immediate result?
    YES → Synchronous
    NO → Go to Q2

Q2: Is operation time predictable and < 1 second?
    YES → Synchronous
    NO → Go to Q3

Q3: Is this continuous data flow?
    YES → Streaming
    NO → Asynchronous
```

**Trade-off Matrix:**

| Factor | Synchronous | Asynchronous | Streaming |
|--------|-------------|--------------|-----------|
| **Latency** | < 1s | Seconds-Minutes | < 100ms |
| **Complexity** | Low | Medium | High |
| **Error Handling** | Simple | Complex | Very Complex |
| **Debugging** | Easy | Medium | Hard |
| **Scaling** | Request-based | Queue-based | Stream-based |
| **State Management** | None | External DB | Checkpoints |
| **User Experience** | Immediate | Polling | Live Updates |

---

## Anti-Patterns

**Anti-Pattern 1: Synchronous for Long Operations**
```
❌ WRONG:
  def upload_and_process(file):
      upload(file)           # 10 seconds
      transcode(file)        # 120 seconds
      generate_thumbnails()  # 30 seconds
      return result
  
  Problem: 160-second response time! Timeout!

✅ RIGHT:
  def upload_and_queue(file):
      job_id = create_job()
      upload(file)           # 10 seconds
      queue_for_processing(job_id)
      return {"job_id": job_id, "status": "queued"}
  
  Result: 10-second response, background processing
```

**Anti-Pattern 2: Asynchronous for Quick Operations**
```
❌ WRONG:
  def get_user(user_id):
      job_id = queue_lookup(user_id)
      return {"job_id": job_id}
  
  Problem: User must poll for 50ms lookup result!

✅ RIGHT:
  def get_user(user_id):
      user = db.get(user_id)  # 5ms
      return user
  
  Result: Immediate response, better UX
```

**Anti-Pattern 3: Streaming for Batch Processing**
```
❌ WRONG:
  Use streaming for daily report generation
  
  Problem: Overkill, stream never ends

✅ RIGHT:
  Use asynchronous batch job
  Scheduled trigger → Process → Complete
```

---

## Real-World Examples

**Example 1: E-commerce Order**
- Synchronous: Validate cart, calculate total (< 500ms)
- Asynchronous: Process payment, send email (5-10 seconds)
- Streaming: Update real-time inventory dashboard

**Example 2: Video Platform**
- Synchronous: Upload request, get pre-signed URL (< 100ms)
- Asynchronous: Transcode video, generate thumbnails (minutes)
- Streaming: Track viewer engagement metrics (real-time)

**Example 3: IoT System**
- Synchronous: Device registration, settings retrieval (< 1s)
- Asynchronous: Firmware updates (minutes)
- Streaming: Sensor data ingestion, anomaly detection (< 100ms)

---

## Why This Matters

**User Experience:**
- Wrong pattern = frustrated users
- Synchronous timeout = error, retry, frustration
- Asynchronous when synchronous possible = unnecessary complexity
- Streaming for batch = wasted resources

**Cost Optimization:**
- Synchronous: Pay per request duration
- Asynchronous: Pay per job, queue costs
- Streaming: Pay per throughput, highest cost
- Use cheapest pattern that meets requirements

**System Reliability:**
- Synchronous: Simple, fewer failure modes
- Asynchronous: Better fault tolerance
- Streaming: Complex but highest availability

---

## When to Apply

**Design Phase:**
- ✅ Map each operation to a pattern
- ✅ Identify latency requirements
- ✅ Consider user expectations
- ✅ Plan error handling strategy

**Code Review:**
- 🔍 Verify pattern matches operation duration
- 🔍 Check timeout budgets for synchronous
- 🔍 Validate retry logic for asynchronous
- 🔍 Review checkpoint strategy for streaming

**Performance Tuning:**
- 📊 Measure actual latency vs pattern limits
- 📊 Convert slow synchronous → asynchronous
- 📊 Optimize asynchronous job throughput

---

## Cross-References

**AWS Patterns:**
- AWS-Lambda-CostModel_AWS-LESS-11.md - Cost implications of patterns
- AWS-Lambda-TimeoutConstraints_AWS-LESS-12.md - Synchronous time limits

**Project Maps:**
- /sima/entries/decisions/architecture/DEC-05.md - Sentinel sanitization at boundaries
- /sima/entries/core/ARCH-SUGA_Single Universal Gateway Architecture.md - Similar flow concepts

---

## Keywords

synchronous, asynchronous, streaming, request-response, event-driven, queue, processing patterns, serverless architecture, latency requirements

---

**Location:** `/sima/aws/general/`  
**Total Lines:** 298 (≤400 for SIMAv4)  
**SIMAv4 Compliant:** ✅

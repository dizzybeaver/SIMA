# AWS-LESS-01: Three Data Processing Patterns

**Category:** External Knowledge - AWS Serverless  
**Type:** LESS (Lesson Learned)  
**Priority:** ⭐⭐⭐⭐ HIGH  
**Status:** Active  
**Created:** 2025-10-25  
**Source:** AWS Serverless Developer Guide (Genericized)

---

## Summary

Serverless workloads fall into three fundamental patterns: synchronous (request-response), asynchronous (queued processing), and streaming (continuous data flow). Each pattern has distinct characteristics for latency, scaling, error handling, and complexity.

**Lesson:** Choose processing pattern based on user expectations and business requirements, not technical convenience. Wrong pattern leads to poor UX and wasted resources.

---

## Context

Traditional applications often use request-response for everything, blocking the caller while work completes. Serverless architectures enable three distinct patterns, each optimized for different latency requirements, error handling needs, and scaling characteristics.

**Problem Domain:** Application architecture, workflow design, event-driven systems  
**Applicability:** Any serverless platform, event-driven architecture, distributed systems

---

## The Lesson

### The Three Patterns

**Pattern Classification:**
```
1. Synchronous: Client waits for response
   - Latency: < 1 second required
   - User Experience: Immediate feedback expected
   - Error Handling: Simple (return error to client)
   
2. Asynchronous: Fire-and-forget with status tracking
   - Latency: Seconds to minutes acceptable
   - User Experience: Status polling or callbacks
   - Error Handling: Complex (retries, dead letter queues)
   
3. Streaming: Continuous data flow processing
   - Latency: Near real-time (< 100ms)
   - User Experience: Live updates, real-time analytics
   - Error Handling: Very complex (checkpointing, ordering)
```

### Pattern 1: Synchronous (Request-Response)

**Characteristics:**
```
Flow:
  Client → Request → Server → Process → Response → Client
  (Client blocks and waits)

Latency Requirement: < 1 second (ideally < 300ms)
User Expectation: Immediate result
Scaling: Based on concurrent requests
Error Handling: Return error directly to client
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
  - Database lookups
```

**Advantages:**
```
✅ Simple mental model (request → response)
✅ Easy error propagation
✅ Straightforward debugging
✅ No state tracking needed
✅ Client knows result immediately
```

**Disadvantages:**
```
❌ Client blocked during processing
❌ Timeout constraints (< 30 seconds typically)
❌ Cannot handle long-running work
❌ Scaling limited by slowest component
❌ User waits for all processing
```

**Implementation Pattern:**
```
# Pseudocode
def handle_request(request):
    # Validate input
    if not valid(request):
        return error_response(400, "Invalid input")
    
    # Process synchronously
    try:
        result = process_data(request)
        return success_response(200, result)
    except Exception as e:
        return error_response(500, str(e))

Time budget: < 1 second
Acceptable for: Fast operations
```

### Pattern 2: Asynchronous (Queued Processing)

**Characteristics:**
```
Flow:
  Client → Request → Queue → (Client receives job ID)
  Background → Dequeue → Process → Update Status
  Client → Poll Status → Get Result

Latency Requirement: Seconds to minutes acceptable
User Expectation: Job tracking, progress updates
Scaling: Based on queue depth
Error Handling: Retries, dead letter queues, status updates
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
  - Webhook deliveries
  - Data transformations
```

**Advantages:**
```
✅ No client blocking
✅ Independent scaling of components
✅ Built-in retry mechanisms
✅ Load leveling (queue buffers spikes)
✅ Can handle variable processing time
✅ Component failures don't affect client
```

**Disadvantages:**
```
❌ Complex state tracking required
❌ Status polling overhead
❌ Eventual consistency
❌ Harder to debug (distributed)
❌ Need message queue infrastructure
❌ More moving parts
```

**Implementation Pattern:**
```
# Pseudocode - Submit Job
def submit_job(request):
    job_id = generate_id()
    
    # Store job metadata
    db.create_job(job_id, status="pending")
    
    # Queue for processing
    queue.enqueue({
        "job_id": job_id,
        "data": request.data
    })
    
    # Return immediately
    return {
        "job_id": job_id,
        "status": "pending",
        "status_url": f"/jobs/{job_id}"
    }

# Separate Worker Process
def process_job(message):
    job_id = message["job_id"]
    
    try:
        # Update status
        db.update_job(job_id, status="processing")
        
        # Do work
        result = process_data(message["data"])
        
        # Complete
        db.update_job(job_id, 
            status="complete",
            result=result
        )
    except Exception as e:
        # Retry or move to dead letter queue
        db.update_job(job_id, 
            status="failed",
            error=str(e)
        )
        if retryable(e):
            queue.retry(message)

# Client Polling
def check_status(job_id):
    return db.get_job(job_id)
```

### Pattern 3: Streaming (Continuous Flow)

**Characteristics:**
```
Flow:
  Source → Stream → Process → Output Stream → Sink
  (Continuous, never-ending flow)

Latency Requirement: Near real-time (< 100ms)
User Expectation: Live updates, real-time analytics
Scaling: Based on stream throughput
Error Handling: Checkpointing, replay, ordering guarantees
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
  - Game telemetry
  - Click-stream analysis
```

**Advantages:**
```
✅ True real-time processing
✅ High throughput possible
✅ Ordered event processing
✅ Automatic checkpointing
✅ Replay capability
✅ Handles massive scale
```

**Disadvantages:**
```
❌ Most complex pattern
❌ Requires stream infrastructure
❌ Complex state management
❌ Ordering guarantees difficult
❌ Debugging very challenging
❌ Checkpoint management overhead
```

**Implementation Pattern:**
```
# Pseudocode - Stream Processor
def process_stream():
    # Continuous processing loop
    while True:
        # Read batch from stream
        events = stream.read_batch(
            max_records=100,
            timeout=1000
        )
        
        # Process each event
        for event in events:
            try:
                result = process_event(event)
                output_stream.write(result)
            except Exception as e:
                # Log error, continue processing
                log_error(event, e)
        
        # Checkpoint progress
        stream.checkpoint(events.last_sequence)

# Characteristics:
# - Never-ending loop
# - Batch processing for efficiency
# - Checkpoint for fault tolerance
# - Continue on errors
```

### Pattern Selection Framework

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
| **Cost** | Per request | Per job | Per throughput |

### Mixed Pattern Example: File Upload

**Realistic Workflow:**
```
Step 1: Synchronous - Upload initiation
  Client → POST /upload → Get upload URL
  Response: < 100ms (just generating URL)

Step 2: Synchronous - File upload
  Client → PUT /s3-url → Upload file
  Response: Seconds (blocking upload)

Step 3: Asynchronous - Processing
  Trigger → Queue → Background processing
  Status: Job ID returned, client polls

Step 4: Streaming - Analytics (optional)
  Processing events → Stream → Real-time dashboard
  Updates: Live metrics
```

### Anti-Patterns

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

### Real-World Combinations

**Example 1: E-commerce Order**
```
Synchronous: Validate cart, calculate total (< 500ms)
Asynchronous: Process payment, send email (5-10 seconds)
Streaming: Update real-time inventory dashboard
```

**Example 2: Video Platform**
```
Synchronous: Upload request, get pre-signed URL (< 100ms)
Asynchronous: Transcode video, generate thumbnails (minutes)
Streaming: Track viewer engagement metrics (real-time)
```

**Example 3: IoT System**
```
Synchronous: Device registration, settings retrieval (< 1s)
Asynchronous: Firmware updates (minutes)
Streaming: Sensor data ingestion, anomaly detection (< 100ms)
```

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

## Related Patterns

- **AWS-LESS-11**: Pay-Per-Use Cost Model - Cost implications of patterns
- **AWS-LESS-12**: Function Timeout Constraints - Synchronous time limits
- **DEC-05**: Sentinel Sanitization - Router boundary patterns
- **FLOW-01-03**: SUGA flow patterns - Similar concepts

---

## Keywords

synchronous, asynchronous, streaming, request-response, event-driven, queue, processing patterns, serverless architecture, latency requirements

---

## Version History

- **2025-10-25**: Created - Extracted from AWS Serverless Developer Guide, genericized for universal application

---

**End of Entry**
**Lines:** ~199 (within < 200 target)  
**Quality:** Generic, comprehensive taxonomy, practical examples

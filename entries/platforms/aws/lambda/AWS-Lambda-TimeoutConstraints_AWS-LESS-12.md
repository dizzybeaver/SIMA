# AWS-Lambda-TimeoutConstraints_AWS-LESS-12.md

**Version:** 1.0.0  
**Date:** 2025-11-01  
**Purpose:** Function timeout constraint patterns and solutions

**REF-ID:** AWS-LESS-12  
**Category:** AWS Lambda  
**Type:** LESS (Lesson Learned)  
**Priority:** 🔴 High  
**Status:** Active  
**Source:** AWS Serverless Developer Guide (Genericized)

---

## Summary

Short-lived execution environments impose hard time limits (typically 15 minutes maximum). Design functions to complete quickly or use patterns for long-running work: chunking, continuation, or external orchestration. Never assume unlimited execution time.

**Core Principle:** Ephemeral compute has hard deadlines. Design for quick completion or decompose work into time-bounded chunks.

---

## The Hard Reality

**Timeout Characteristics:**
```
Hard Limit: 15 minutes (typical platform maximum)
Enforcement: Forcible termination at timeout
No Grace Period: No cleanup opportunity
No Partial Credit: Work done before timeout is lost
No Retry: Timeout is not automatically retried
```

**What Happens at Timeout:**
```
1. Function execution stopped immediately
2. No cleanup code runs
3. Resources may be left in inconsistent state
4. Transaction not committed → data loss
5. Partial work discarded
6. Error returned to caller
```

**Example Failure:**
```
Task: Process 10,000 records (20 seconds each)
Time needed: 10,000 * 20s = 200,000s = 55 hours
Function timeout: 15 minutes = 900 seconds

Result: After 900 seconds:
  - Processed 45 records (900s / 20s)
  - Function terminated
  - 9,955 records unprocessed
  - All work lost (no checkpoint)
  - Must start over from beginning
```

---

## Pattern 1: Chunking (Divide Work)

**Concept:** Break work into small chunks that fit within timeout. Process one chunk per invocation. Track progress externally. Continue until complete.

**Implementation:**
```
1. Divide dataset into batches
2. Process one batch per function call
3. Store progress in external state (database/queue)
4. Invoke next batch until complete

Example: Process 1M records
  ❌ Bad: Process all 1M in one function (timeout!)
  ✅ Good: Process 10K per function (100 invocations)
```

**Code Pattern (Pseudocode):**
```python
def process_batch(batch_id, start_idx, batch_size):
    # Load batch
    records = load_records(start_idx, batch_size)
    
    # Process batch (< timeout)
    for record in records:
        process_record(record)
    
    # Save progress
    save_checkpoint(batch_id, start_idx + batch_size)
    
    # Check if done
    if start_idx + batch_size < total_records:
        # Trigger next batch
        invoke_next_batch(batch_id, start_idx + batch_size)
    else:
        # Mark complete
        mark_complete(batch_id)

Timeout Budget:
  Max: 15 minutes
  Safe: 12 minutes (leave margin)
  Per-record: 12min / 10,000 records = 72ms
  
  If record takes > 72ms → reduce batch size!
```

**Benefits:**
```
✅ Fault tolerant (resume from checkpoint)
✅ Parallel processing possible
✅ Stays within timeout limits
✅ Progress visible
✅ Can cancel mid-processing
```

**Trade-offs:**
```
- Requires external state tracking
- More complex orchestration
- Overhead per invocation
- Eventual consistency
```

**Real Example: CSV Processing**
```
Task: Process 100K-row CSV file

Chunking Approach:
  1. Upload CSV to storage
  2. Trigger function: "process rows 0-10,000"
  3. Function:
     - Reads rows 0-10,000
     - Processes each row
     - Saves checkpoint "processed up to 10,000"
     - Invokes next: "process rows 10,000-20,000"
  4. Repeat until all rows processed
  
Time per batch: 5 minutes (within 15-min limit)
Total batches: 10
Total time: 50 minutes (parallelizable to 5 min)
```

---

## Pattern 2: Continuation Passing

**Concept:** Function invokes itself with remaining work. Each invocation processes what it can within timeout. Chain continues until work complete.

**Implementation:**
```
1. Process for N minutes (leave margin for safety)
2. Check if work remains
3. If yes: Invoke new instance with remaining work
4. If no: Mark complete

Example: Large file processing
  Invocation 1: Process bytes 0-100MB, invoke next
  Invocation 2: Process bytes 100MB-200MB, invoke next
  Invocation N: Process remaining bytes, done
```

**Code Pattern (Pseudocode):**
```python
def process_large_file(file_id, offset=0):
    # Calculate safe processing window
    max_time = 12 * 60  # 12 minutes in seconds
    start_time = current_time()
    
    # Process until timeout approaches
    while (current_time() - start_time) < max_time:
        chunk = read_file_chunk(file_id, offset, chunk_size)
        
        if chunk is None:  # File complete
            mark_complete(file_id)
            return {"status": "complete"}
        
        process_chunk(chunk)
        offset += chunk_size
    
    # Timeout approaching, invoke continuation
    invoke_function_async({
        "file_id": file_id,
        "offset": offset
    })
    
    return {"status": "continuing", "processed_up_to": offset}
```

**Benefits:**
```
✅ Simple implementation
✅ No external orchestrator needed
✅ Automatic progress tracking
✅ Self-managing workflow
```

**Trade-offs:**
```
- Sequential processing (no parallelism)
- Cost increases with failures (wasted retries)
- Harder to cancel or monitor overall progress
- Risk of infinite loops if not careful
```

**Real Example: Video Processing**
```
Task: Transcode 2-hour video (would take 3 hours)

Continuation Approach:
  1. Function starts transcoding
  2. After 12 minutes: 24 minutes of video transcoded
  3. Save checkpoint, invoke continuation
  4. Next function: Continues from 24-minute mark
  5. Repeat until complete (10 invocations)
  
Each invocation: 12 minutes processing
Total invocations: 10
Total time: ~15 minutes (sequential)
```

---

## Pattern 3: External Orchestration

**Concept:** Use workflow engine to manage tasks. Functions do small work units. Orchestrator handles state, coordination, and error handling.

**Implementation:**
```
1. Orchestrator divides work into tasks
2. Each task invokes a function
3. Function completes task, reports back
4. Orchestrator tracks overall progress
5. Handles failures, retries, fan-out

Example: Step Functions, Airflow, Temporal
  Orchestrator: "Process these 100 files"
  Functions: Process one file each (parallel)
  Orchestrator: Wait for all, handle failures
```

**Architecture:**
```
Workflow Engine (Orchestrator)
  ↓ invoke
Function 1 (Task A) ← parallel
Function 2 (Task B) ← parallel
Function 3 (Task C) ← parallel
  ↓ results
Workflow Engine
  ↓ next step
Function 4 (Aggregate)
```

**Benefits:**
```
✅ Parallel execution possible
✅ Sophisticated error handling
✅ Visual workflow tracking
✅ Built-in retry logic
✅ State management handled
✅ Conditional branching
✅ Human approval steps
```

**Trade-offs:**
```
- Additional service/cost
- More complex architecture
- Learning curve
- Platform-specific
```

**Real Example: Order Processing**
```
Workflow:
  1. Validate order (Function 1) - 10s
  2. Parallel:
     - Check inventory (Function 2a) - 30s
     - Validate payment (Function 2b) - 45s
     - Calculate shipping (Function 2c) - 20s
  3. If all pass:
     - Reserve inventory (Function 3) - 15s
     - Charge payment (Function 4) - 30s
     - Create shipment (Function 5) - 25s
  4. Send confirmation (Function 6) - 5s

Total time: ~2 minutes (with parallelism)
Without orchestration: Sequential 3+ minutes
Each function: < 1 minute (well within timeout)
```

---

## Timeout Budget Planning

**Formula:**
```
Max Timeout: 15 minutes = 900 seconds
Safe Timeout: 12 minutes = 720 seconds (leave margin)

Per-Operation Time Budget:
  720 seconds / number_of_operations

Example: 100 database queries
  720s / 100 = 7.2 seconds per query
  
  If query takes 10 seconds → REDUCE BATCH SIZE
  If query takes 1 second → Comfortably within budget
```

**Warning Signs:**
```
❌ "We'll just increase timeout" 
   → Already at max, can't increase!
   
❌ "It usually finishes in time"
   → Unreliable, will fail on edge cases
   
❌ "Process everything in one go"
   → No chunking = timeout risk
   
❌ "We can retry if it fails"
   → Timeout not retryable, must redesign
```

---

## Design Guidelines by Use Case

**Quick Operations (< 1 minute):**
```
✅ Single function invocation
✅ No special handling needed
✅ Direct processing

Examples:
  - API request/response
  - Database query
  - File upload/download
  - Image resize
```

**Medium Operations (1-15 minutes):**
```
✅ Single function with timeout monitoring
✅ Early exit if approaching timeout
✅ Progress checkpointing

Examples:
  - Large file processing
  - Batch database updates
  - Report generation
  - Data transformation
```

**Long Operations (> 15 minutes):**
```
❌ Single function WILL FAIL
✅ Must use chunking OR continuation OR orchestration

Examples:
  - Video encoding
  - Large dataset processing
  - ML model training
  - Data migration
```

---

## Anti-Patterns

**Anti-Pattern 1: Ignoring Timeout**
```
❌ WRONG:
def process_all_records():
    for record in all_million_records:  # Takes hours!
        process(record)
    return "done"

Result: Timeout after 15 minutes, 99% of work lost
```

**Anti-Pattern 2: Batch Without Chunking**
```
❌ WRONG:
def daily_batch_job():
    records = get_yesterdays_records()  # Variable size!
    for record in records:
        process(record)

Problem: Size grows over time, eventually exceeds timeout
```

**Anti-Pattern 3: External API Wait**
```
❌ WRONG:
def wait_for_external_api():
    result = call_api()
    while not result.complete:
        time.sleep(60)  # Poll every minute
        result = check_status()
    return result.data

Problem: API might take hours, function times out
```

**Anti-Pattern 4: Large File Processing**
```
❌ WRONG:
def process_entire_file(file_url):
    content = download_file(file_url)  # 10GB file!
    for line in content:
        process(line)

Problem: Download + processing exceeds timeout
```

---

## Alternative Solutions

**For Truly Long-Running Work:**
```
Hours to Days of Processing:

✅ Use container platforms instead:
   - ECS/Fargate
   - Kubernetes Jobs
   - Batch computing services

✅ Use batch job systems:
   - AWS Batch
   - Apache Spark
   - Data pipeline services

✅ Use dedicated compute:
   - EC2 instances for specific tasks
   - Reserved capacity for long jobs

Serverless functions are for:
  ✅ Quick operations (< 1 min ideal)
  ✅ Chunkable work (< 15 min per chunk)
  ✅ Event-driven processing
  
NOT for:
  ❌ Long-running batch jobs
  ❌ Continuous processing
  ❌ Always-on services
```

---

## Why This Matters

**Reliability:**
- Timeouts cause complete failures
- No partial success or graceful degradation
- Must design for constraint from start

**Cost:**
- Failed functions still cost money
- Retrying failed functions multiplies cost
- Proper chunking optimizes cost

**User Experience:**
- Timeouts cause request failures
- No status updates during long operations
- Poor UX if not handled properly

---

## When to Apply

**Design Phase:**
- ✅ Estimate time for worst-case inputs
- ✅ Choose chunking strategy upfront
- ✅ Build timeout monitoring into design

**Red Flags:**
- ❌ Variable-size batch processing
- ❌ External API with unknown response time
- ❌ File size not limited by design
- ❌ Growing dataset without chunking

**Testing:**
- 🧪 Test with worst-case data size
- 🧪 Verify timeout handling
- 🧪 Test continuation/chunking logic

---

## Cross-References

**AWS Patterns:**
- AWS-Lambda-StatelessExecution_AWS-LESS-03.md - Container lifecycle patterns
- AWS-General-ProcessingPatterns_AWS-LESS-01.md - Async processing for long work

**Project Maps:**
- /sima/entries/core/ARCH-LMMS_Lambda Memory Management System.md - Cold start optimization
- /sima/entries/decisions/architecture/DEC-04.md - No threading in single execution
- /sima/entries/lessons/performance/LESS-17.md - Cold start pathway patterns

---

## Keywords

timeout, execution limit, serverless constraints, chunking, continuation, orchestration, workflow, FaaS limitations, time-bounded execution

---

**Location:** `/sima/aws/lambda/`  
**Total Lines:** 390 (≤400 for SIMAv4)  
**SIMAv4 Compliant:** ✅

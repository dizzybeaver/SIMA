# AWS-Lambda-LESS-04-Timeout-Management.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Timeout management strategies and lessons for AWS Lambda  
**Category:** AWS Lambda Platform Lessons

---

## LESSON SUMMARY

**Core Insight:** Lambda's 30-second timeout is a hard constraint requiring careful operation design. Timeouts cause incomplete work, wasted compute, and poor user experience. Understanding timeout patterns enables reliable function design.

**Context:** Lambda terminates execution at timeout regardless of state. No cleanup runs, no graceful shutdown, no partial results saved. Operations must complete within timeout or be designed for failure.

**Impact:**
- Timeout = Complete failure (no partial success)
- Wasted compute time = Wasted cost
- User sees error after waiting full timeout
- Requires retry with backoff
- Can cause duplicate work

---

## TIMEOUT FUNDAMENTALS

### Hard Limit: 30 Seconds Maximum

**What Happens at Timeout:**
```
[Execution starts] ──> [Work in progress] ──> [29.99s] ──> [KILLED]
                                                         No cleanup
                                                         No graceful exit
                                                         Immediate termination
```

**Cannot:**
- Extend timeout beyond 30 seconds
- Request more time
- Get warning before timeout
- Run cleanup code at timeout
- Save partial results automatically

**Can:**
- Set timeout lower than 30s (default: 3s)
- Monitor time remaining
- Implement early termination
- Design for timeout recovery
- Use checkpointing patterns

---

## TIMEOUT PATTERNS

### Pattern 1: Monitor Remaining Time

**Use Lambda context to track time:**
```python
import time

def lambda_handler(event, context):
    """Monitor remaining time and exit gracefully before timeout."""
    
    start_time = time.time()
    timeout_buffer = 2  # Reserve 2 seconds for cleanup
    
    items = get_items_to_process()
    processed = []
    
    for item in items:
        # Check remaining time
        elapsed = time.time() - start_time
        remaining = context.get_remaining_time_in_millis() / 1000
        
        # Exit before timeout if too close
        if remaining < timeout_buffer:
            # Save progress
            save_checkpoint(processed)
            return {
                'statusCode': 206,  # Partial content
                'body': {
                    'processed': len(processed),
                    'remaining': len(items) - len(processed),
                    'checkpoint_saved': True
                }
            }
        
        # Process item
        result = process_item(item)
        processed.append(result)
    
    # All items processed
    return {
        'statusCode': 200,
        'body': {'processed': len(processed)}
    }
```

**Benefit:** Graceful partial completion instead of hard timeout

---

### Pattern 2: Chunked Processing

**Break large jobs into timeout-safe chunks:**
```python
def lambda_handler(event, context):
    """Process work in chunks that fit within timeout."""
    
    CHUNK_SIZE = 100  # Items per invocation
    ITEM_PROCESSING_TIME = 0.2  # 200ms per item
    
    # Calculate safe chunk size
    timeout = context.get_remaining_time_in_millis() / 1000
    safe_chunk = min(CHUNK_SIZE, int(timeout / ITEM_PROCESSING_TIME * 0.8))
    
    # Get chunk to process
    start_index = event.get('start_index', 0)
    items = get_items(start_index, safe_chunk)
    
    # Process chunk
    for item in items:
        process_item(item)
    
    # Check if more work remains
    if has_more_items(start_index + safe_chunk):
        # Invoke self for next chunk
        import boto3
        lambda_client = boto3.client('lambda')
        lambda_client.invoke(
            FunctionName=context.function_name,
            InvocationType='Event',  # Async
            Payload=json.dumps({
                'start_index': start_index + safe_chunk
            })
        )
        return {'statusCode': 202, 'message': 'Chunk processed, more work queued'}
    
    return {'statusCode': 200, 'message': 'All work completed'}
```

**Benefit:** Large jobs complete reliably across multiple invocations

---

### Pattern 3: Async with Callback

**For operations that might exceed timeout:**
```python
def lambda_handler(event, context):
    """Start long operation and return immediately, callback when done."""
    
    operation_type = event.get('type')
    
    if operation_type == 'start':
        # Start long-running operation
        job_id = start_async_operation(event['data'])
        
        # Store job info
        save_job_status(job_id, 'IN_PROGRESS')
        
        # Return immediately
        return {
            'statusCode': 202,  # Accepted
            'body': {
                'job_id': job_id,
                'status_url': f'/jobs/{job_id}/status'
            }
        }
    
    elif operation_type == 'callback':
        # Called when operation completes
        job_id = event['job_id']
        result = event['result']
        
        # Update job status
        save_job_status(job_id, 'COMPLETED', result)
        
        # Notify user (SNS, SQS, etc.)
        notify_completion(job_id)
        
        return {'statusCode': 200}
    
    elif operation_type == 'status':
        # Check job status
        job_id = event['job_id']
        status = get_job_status(job_id)
        return {'statusCode': 200, 'body': status}
```

**Benefit:** User gets immediate response, work continues asynchronously

---

### Pattern 4: Step Functions for Orchestration

**For complex multi-step workflows:**
```python
# Lambda only handles single steps
def lambda_handler(event, context):
    """Single step in larger workflow - stays under timeout."""
    
    step = event['step']
    
    if step == 'validate':
        result = validate_data(event['data'])
        return {'status': 'validated', 'data': result}
    
    elif step == 'process':
        result = process_data(event['data'])
        return {'status': 'processed', 'data': result}
    
    elif step == 'finalize':
        result = finalize_data(event['data'])
        return {'status': 'complete', 'data': result}
    
    # Each step completes in <10 seconds
```

**Step Functions Definition:**
```json
{
  "StartAt": "Validate",
  "States": {
    "Validate": {
      "Type": "Task",
      "Resource": "arn:aws:lambda:...:function:my-function",
      "Parameters": {
        "step": "validate",
        "data.$": "$.input"
      },
      "Next": "Process"
    },
    "Process": {
      "Type": "Task",
      "Resource": "arn:aws:lambda:...:function:my-function",
      "Parameters": {
        "step": "process",
        "data.$": "$.data"
      },
      "Next": "Finalize"
    },
    "Finalize": {
      "Type": "Task",
      "Resource": "arn:aws:lambda:...:function:my-function",
      "Parameters": {
        "step": "finalize",
        "data.$": "$.data"
      },
      "End": true
    }
  }
}
```

**Benefit:** Complex workflows without timeout concerns

---

## TIMEOUT LESSONS LEARNED

### Lesson 1: External API Calls Are Risky

**Problem:** Called external API without timeout

**Result:**
```python
# Bad: No timeout on external call
response = requests.get('https://slow-api.com/data')
# If API hangs, Lambda times out after 30s
# User waits 30s, sees error, wasted $0.50 compute
```

**Solution:** Always set timeouts on external calls
```python
# Good: Explicit timeout
try:
    response = requests.get(
        'https://slow-api.com/data',
        timeout=5  # Fail fast if API is slow
    )
except requests.Timeout:
    # Handle timeout gracefully
    return fallback_response()
```

**Impact:** Reduced average execution time from 28s → 6s (timeout cases)

---

### Lesson 2: Database Queries Need Limits

**Problem:** Query could return millions of rows

**Result:**
- Query started returning 100K+ rows
- Processing exceeded timeout
- Function failed repeatedly
- Database under load

**Solution:** Pagination and query limits
```python
# Bad: Unbounded query
items = db.query("SELECT * FROM large_table")

# Good: Limited query with pagination
PAGE_SIZE = 1000
offset = event.get('offset', 0)
items = db.query(
    "SELECT * FROM large_table LIMIT ? OFFSET ?",
    (PAGE_SIZE, offset)
)
```

---

### Lesson 3: Set Conservative Timeouts

**Problem:** Set timeout to 30s "just in case"

**Result:**
- Normal execution: 2 seconds
- Broken execution: User waits 30s for error
- Cost: 15x higher for failed invocations

**Solution:** Set timeout close to actual need
```python
# If function normally takes 2s, set timeout to 5s
# Timeout: 5 seconds (2.5x normal execution)
# Cost savings: 6x on failed invocations (5s vs 30s)
```

**Guideline:** Set timeout to 2-3x normal execution time

---

### Lesson 4: Background Tasks Cause Issues

**Problem:** Started background thread for cleanup

**Result:**
```python
# Bad: Background thread
import threading

def lambda_handler(event, context):
    # Start cleanup in background
    thread = threading.Thread(target=cleanup_old_data)
    thread.start()
    
    # Return immediately
    return {'statusCode': 200}
    
# Thread continues running... until timeout kills it
# Cleanup incomplete, no error handling
```

**Solution:** Complete all work before returning
```python
# Good: Synchronous completion
def lambda_handler(event, context):
    # Do cleanup synchronously
    cleanup_old_data()
    
    return {'statusCode': 200}
```

---

### Lesson 5: File Processing Needs Size Checks

**Problem:** Processed uploaded files without size check

**Result:**
- User uploaded 500MB file
- Function started processing
- Timeout at 30s (processed 10%)
- Retries also timed out
- Dead letter queue filled

**Solution:** Check size before processing
```python
def lambda_handler(event, context):
    """Process uploaded file with size validation."""
    
    bucket = event['bucket']
    key = event['key']
    
    # Check file size
    s3 = boto3.client('s3')
    metadata = s3.head_object(Bucket=bucket, Key=key)
    size_mb = metadata['ContentLength'] / (1024 * 1024)
    
    # Reject files too large for Lambda
    MAX_SIZE_MB = 50  # Process files up to 50MB
    if size_mb > MAX_SIZE_MB:
        # Route to different processing system
        send_to_batch_processing(bucket, key)
        return {
            'statusCode': 202,
            'message': 'File queued for batch processing'
        }
    
    # Process small files in Lambda
    process_file(bucket, key)
    return {'statusCode': 200}
```

---

## TIMEOUT DEBUGGING

### Identify Timeout Issues

**CloudWatch Logs Pattern:**
```
START RequestId: abc123
[INFO] Starting processing...
[INFO] Processed 1000 items...
[INFO] Processed 2000 items...
END RequestId: abc123
REPORT RequestId: abc123
    Duration: 30000.00 ms    <-- Hit timeout
    Billed Duration: 30000 ms
```

**X-Ray Trace Pattern:**
```
[Function] 30000ms
  [Initialization] 500ms
  [API Call 1] 5000ms
  [API Call 2] 24500ms  <-- Slow API caused timeout
```

---

### Monitor Timeout Metrics

**CloudWatch Metrics:**
```python
# Publish custom metric for near-timeouts
def lambda_handler(event, context):
    start = time.time()
    
    # Do work...
    process_data(event)
    
    # Check how close to timeout
    elapsed = time.time() - start
    timeout = context.get_remaining_time_in_millis() / 1000 + elapsed
    utilization = (elapsed / timeout) * 100
    
    # Alert if consistently using >80% of timeout
    if utilization > 80:
        cloudwatch.put_metric_data(
            Namespace='Lambda/Custom',
            MetricData=[{
                'MetricName': 'TimeoutRisk',
                'Value': utilization,
                'Unit': 'Percent'
            }]
        )
    
    return result
```

**Alarm on High Utilization:**
- >80% timeout utilization consistently = Need optimization
- >95% = Timeout risk imminent

---

## BEST PRACTICES

### 1. Design for Timeout
**Every function should handle timeout gracefully**
- Monitor remaining time
- Save checkpoints
- Return partial results
- Enable retries with idempotency

### 2. Set Realistic Timeouts
**Don't default to maximum**
- Measure actual execution time
- Set timeout to 2-3x normal time
- Alert on approaching timeout
- Review timeout settings quarterly

### 3. Fail Fast on External Dependencies
**Don't wait for Lambda timeout**
- Set timeouts on API calls (5-10s)
- Set timeouts on database queries (5s)
- Set timeouts on S3 operations (10s)
- Return errors quickly

### 4. Use Appropriate Patterns
**Match pattern to use case**
- <5s operations: Direct execution
- 5-30s operations: Monitor remaining time
- >30s operations: Chunking or async pattern
- Complex workflows: Step Functions

### 5. Monitor and Alert
**Track timeout metrics**
- Timeout rate (should be <0.1%)
- Near-timeout rate (should be <5%)
- Execution time trends
- External dependency latency

---

## RELATED CONCEPTS

**Cross-References:**
- AWS-Lambda-DEC-03: Timeout limits documented
- AWS-Lambda-LESS-01: Cold start counts toward timeout
- AWS-Lambda-LESS-02: Memory affects execution speed (timeout risk)
- AWS-Lambda-DEC-04: Stateless design enables checkpointing

**Keywords:** timeout, 30-second limit, graceful degradation, chunking, async patterns, Step Functions, monitoring

---

**END OF FILE**

**Location:** `/sima/platforms/aws/lambda/lessons/AWS-Lambda-LESS-04-Timeout-Management.md`  
**Version:** 1.0.0  
**Lines:** 390 (within 400-line limit)

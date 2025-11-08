# AWS-Lambda-Execution-Model.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Lambda execution model and invocation patterns  
**Category:** Platform - AWS Lambda  
**Type:** Core

---

## OVERVIEW

AWS Lambda's execution model defines how functions are invoked, how they process events, and how they scale. Understanding the execution model is critical for building reliable serverless applications.

---

## INVOCATION TYPES

### Synchronous Invocation

**RequestResponse - Caller waits for result**

**Pattern:**
```
Client → Lambda (executes) → Response → Client
                 ↓
            (waits for completion)
```

**Characteristics:**
- Client blocks until function completes or times out
- Response returned directly to caller
- Errors returned to caller immediately
- No automatic retries
- Client responsible for error handling

**Used By:**
- API Gateway
- Application Load Balancer
- AWS SDK invoke() calls
- AWS CLI with --invocation-type RequestResponse

**Example:**
```python
# API Gateway invokes Lambda synchronously
def lambda_handler(event, context):
    # API Gateway waits for this response
    return {
        'statusCode': 200,
        'body': json.dumps({'message': 'Success'})
    }
```

**Error Handling:**
```python
def lambda_handler(event, context):
    try:
        result = process_data(event)
        return {'statusCode': 200, 'body': json.dumps(result)}
    except ValueError as e:
        # Error returned directly to API Gateway
        return {'statusCode': 400, 'body': str(e)}
    except Exception as e:
        # Internal error
        return {'statusCode': 500, 'body': 'Internal error'}
```

**Timeout Behavior:**
- Function must complete within timeout
- If timeout exceeds, Lambda terminates execution
- Client receives timeout error
- Partial work may be lost

---

### Asynchronous Invocation

**Event - Lambda queues and processes later**

**Pattern:**
```
Client → Lambda Queue → Lambda (executes)
            ↓
     (immediate 202 Accepted)
```

**Characteristics:**
- Client receives immediate acceptance (202 status)
- Lambda queues event for processing
- Function executes asynchronously
- Automatic retries (2 attempts) on error
- Dead Letter Queue (DLQ) for failed events

**Used By:**
- S3 event notifications
- SNS topic subscriptions
- CloudWatch Events/EventBridge
- AWS SDK invoke() with --invocation-type Event

**Example:**
```python
# S3 triggers Lambda asynchronously
def lambda_handler(event, context):
    # S3 doesn't wait for response
    # Process uploaded file
    for record in event['Records']:
        bucket = record['s3']['bucket']['name']
        key = record['s3']['object']['key']
        process_s3_object(bucket, key)
    
    # Return value not sent to S3
    return {'status': 'processed'}
```

**Retry Behavior:**
```
Invocation 1 (immediate):
    ↓ (error)
Wait ~1 minute
    ↓
Invocation 2 (retry 1):
    ↓ (error)
Wait ~2 minutes
    ↓
Invocation 3 (retry 2):
    ↓ (error)
Send to Dead Letter Queue (if configured)
```

**Dead Letter Queue Configuration:**
```python
# CloudFormation example
DLQ:
  Type: AWS::SQS::Queue

LambdaFunction:
  Type: AWS::Lambda::Function
  Properties:
    DeadLetterConfig:
      TargetArn: !GetAtt DLQ.Arn
```

**Idempotency Required:**
```python
import hashlib
import time

processed_events = set()  # In practice, use DynamoDB

def lambda_handler(event, context):
    # Generate event ID
    event_id = hashlib.sha256(
        json.dumps(event, sort_keys=True).encode()
    ).hexdigest()
    
    # Check if already processed
    if event_id in processed_events:
        print(f"Event {event_id} already processed, skipping")
        return {'status': 'duplicate'}
    
    # Process event
    result = process_event(event)
    
    # Mark as processed
    processed_events.add(event_id)
    
    return result
```

---

### Stream-Based Invocation

**Lambda polls stream for records**

**Pattern:**
```
DynamoDB Stream / Kinesis → Lambda (polls) → Batch Processing
                              ↑
                         (continuous polling)
```

**Characteristics:**
- Lambda polls stream for new records
- Processes records in batches
- Sequential processing per shard
- Automatic retry until success or expiration
- Can configure batch size and window

**Used By:**
- DynamoDB Streams
- Kinesis Data Streams
- Amazon MQ
- Apache Kafka (MSK)
- SQS queues (technically polling, similar pattern)

**Example:**
```python
def lambda_handler(event, context):
    # Batch of records from stream
    for record in event['Records']:
        # DynamoDB Stream record
        if 'dynamodb' in record:
            process_dynamodb_record(record)
        # Kinesis record
        elif 'kinesis' in record:
            process_kinesis_record(record)
    
    # Successful processing
    return {'processed': len(event['Records'])}
```

**Batch Processing:**
```python
def lambda_handler(event, context):
    successful = []
    failed = []
    
    for record in event['Records']:
        try:
            result = process_record(record)
            successful.append(record['eventID'])
        except Exception as e:
            failed.append({
                'itemIdentifier': record['eventID'],
                'errorCode': type(e).__name__,
                'errorMessage': str(e)
            })
    
    # Report partial batch failure
    if failed:
        return {
            'batchItemFailures': [
                {'itemIdentifier': item['itemIdentifier']}
                for item in failed
            ]
        }
    
    return {'status': 'success'}
```

**Configuration:**
```
Batch Size: 1 - 10,000 records
Batch Window: 0 - 300 seconds
Parallelization Factor: 1 - 10 (Kinesis only)
Max Retry Attempts: -1 (infinite) to 10,000
Max Record Age: -1 (infinite) to 604,800 seconds (7 days)
```

---

## EVENT SOURCE MAPPING

### Configuration

**Maps event source to Lambda:**
```
Event Source → Event Source Mapping → Lambda Function
               (polls and batches)
```

**Key Settings:**

**Batch Size:**
- Number of records per invocation
- Larger = fewer invocations, longer processing
- Smaller = more invocations, faster processing

**Batch Window:**
- Maximum time to gather batch
- Lambda invokes when batch full OR window expires
- Useful for cost optimization

**Starting Position:**
```
LATEST:          Process new records only
TRIM_HORIZON:    Process from oldest available record
AT_TIMESTAMP:    Process from specific timestamp
```

**Error Handling:**
```
On-Failure Destination:    Send failed batches to SQS/SNS
Bisect on Error:           Split failed batch and retry smaller batches
Maximum Retry Attempts:    Stop after N failures
Maximum Record Age:        Discard records older than N seconds
```

---

## CONCURRENCY AND SCALING

### Concurrent Executions

**Lambda creates one execution per concurrent request:**

**Example Scaling:**
```
Time    Requests    Concurrent Executions
10:00   100/sec     100
10:01   500/sec     500
10:02   200/sec     200 (scaled down)
10:05   0/sec       0-5 (may keep some warm)
```

**Account Concurrency Limit:**
- Default: 1,000 concurrent executions
- Shared across all functions in region
- Can request increase to tens of thousands
- Burst limit: 3,000 initial, then +500/minute

### Scaling Behavior

**Synchronous Invocation:**
```
Request arrives → Check for warm container
    ↓ No warm container
Create new execution environment (cold start)
    ↓
Execute function
    ↓
Keep warm for ~15 minutes
```

**Burst Scaling:**
```
Time 0:     10 requests    → 10 executions (immediate)
Time 0.1s:  100 requests   → 100 executions (burst)
Time 0.2s:  5000 requests  → 3000 executions (burst limit)
Time 1m:    5000 requests  → 3500 executions (+500/min)
Time 2m:    5000 requests  → 4000 executions (+500/min)
Time 3m:    5000 requests  → 4500 executions (+500/min)
```

**Stream-Based Scaling:**
```
1 shard  = 1 concurrent invocation (default)
10 shards = 10 concurrent invocations

With parallelization factor of 10:
1 shard  = up to 10 concurrent invocations
10 shards = up to 100 concurrent invocations
```

### Reserved Concurrency

**Guarantee minimum concurrent executions:**

**Without Reserved Concurrency:**
```
Function A: Uses 0-1000 executions (account limit)
Function B: May be throttled if A uses all 1000
Function C: May be throttled if A uses all 1000
```

**With Reserved Concurrency:**
```
Function A: Reserved 500 (guaranteed, max 500)
Function B: Reserved 300 (guaranteed, max 300)
Function C: Unreserved 200 (shared pool)
```

**Trade-offs:**
```
Benefits:
+ Guarantee capacity for critical functions
+ Prevent one function from starving others
+ Predictable scaling

Costs:
- Reduces available concurrency for other functions
- Reserved capacity counts even when unused
- May need to increase account limit
```

**Example Configuration:**
```python
# Via CloudFormation
MyFunction:
  Type: AWS::Lambda::Function
  Properties:
    ReservedConcurrentExecutions: 100
```

---

## PROVISIONED CONCURRENCY

### Eliminate Cold Starts

**Pre-warmed execution environments:**

**Without Provisioned Concurrency:**
```
Request → Cold Start (1-5 seconds) → Execute (50ms) = 1.05s total
```

**With Provisioned Concurrency:**
```
Request → Execute (50ms) = 50ms total
(No cold start)
```

**Configuration:**
```
Provisioned Concurrency: 10 pre-warmed environments
Auto Scaling: Scale 10-100 based on utilization
Schedule: Scale up before peak hours
```

**Use Cases:**
- Latency-sensitive APIs (API Gateway)
- Real-time data processing
- User-facing applications
- Functions with heavy init (>1s)

**Cost Considerations:**
```
Standard Lambda:
- Pay for invocations + duration
- Cold starts free (but slow)

Provisioned Concurrency:
- Pay for provisioned capacity (always)
- Pay for invocations + duration
- No cold starts

Example:
Function: 1024 MB, 100ms average
Without PC: 1M requests/month = $20
With PC (10 concurrent): $292/month
```

**When to Use:**
```
✅ Use if:
- Consistent traffic
- Low latency critical (<100ms p99)
- Cold start >500ms
- Cost justified by user experience

❌ Don't use if:
- Sporadic traffic
- Latency tolerant
- Fast cold start (<200ms)
- Cost-sensitive
```

---

## THROTTLING AND ERRORS

### Throttling Behavior

**When concurrency limit reached:**

**Synchronous:**
```
Request → Check concurrency
    ↓ Over limit
429 TooManyRequestsException → Client
    ↓
Client should implement exponential backoff
```

**Asynchronous:**
```
Request → Queued (up to 6 hours)
    ↓ Still over limit after queue full
Event dropped or sent to DLQ
```

**Stream-Based:**
```
Batch → Retry until success or max retries
    ↓ Max retries reached
Move to next batch (record age determines retention)
```

### Error Handling Strategies

**Synchronous - Client Retry:**
```python
import time
import random

def invoke_with_retry(lambda_client, function_name, payload):
    max_retries = 3
    base_delay = 1
    
    for attempt in range(max_retries):
        try:
            response = lambda_client.invoke(
                FunctionName=function_name,
                InvocationType='RequestResponse',
                Payload=json.dumps(payload)
            )
            return json.loads(response['Payload'].read())
        
        except lambda_client.exceptions.TooManyRequestsException:
            if attempt < max_retries - 1:
                # Exponential backoff with jitter
                delay = (base_delay * 2 ** attempt) + random.uniform(0, 1)
                time.sleep(delay)
            else:
                raise
```

**Asynchronous - DLQ:**
```python
# Function code doesn't change
# Configure DLQ in Lambda settings

# Separate function to process DLQ
def dlq_handler(event, context):
    # Log failed events
    for record in event['Records']:
        failed_payload = json.loads(record['body'])
        log_failure(failed_payload)
        
        # Optionally retry with different logic
        if should_retry(failed_payload):
            retry_with_adjusted_params(failed_payload)
```

**Stream-Based - Partial Batch Failure:**
```python
def lambda_handler(event, context):
    failed_record_ids = []
    
    for record in event['Records']:
        try:
            process_record(record)
        except Exception as e:
            # Mark this record as failed
            failed_record_ids.append(record['eventID'])
            log_error(record, e)
    
    # Lambda retries only failed records
    if failed_record_ids:
        return {
            'batchItemFailures': [
                {'itemIdentifier': record_id}
                for record_id in failed_record_ids
            ]
        }
```

---

## DESTINATIONS

### On-Success and On-Failure

**Route results to different destinations:**

```
Lambda Function
    ↓ Success
SNS Topic (Success Destination)
    ↓ Failure
SQS Queue (Failure Destination)
```

**Configuration:**
```python
# Via CloudFormation
MyFunction:
  Type: AWS::Lambda::Function
  Properties:
    FunctionName: MyFunction
    EventInvokeConfig:
      DestinationConfig:
        OnSuccess:
          Destination: !GetAtt SuccessTopic.Arn
        OnFailure:
          Destination: !GetAtt FailureQueue.Arn
```

**Applies to:**
- Asynchronous invocations
- Stream-based invocations

**Use Cases:**
```
On-Success:
- Trigger downstream processing
- Send notifications
- Update dashboards
- Chain functions

On-Failure:
- Alert on failures
- Store failed events
- Trigger remediation
- Human review queue
```

---

## KEY TAKEAWAYS

**Invocation Types:**
- Synchronous: Caller waits, no auto-retry
- Asynchronous: Queued, 2 auto-retries, DLQ support
- Stream-based: Polling, batching, retry until success

**Scaling:**
- Automatic based on request volume
- Burst: 3000 initial, +500/minute
- Account limit: 1000 default (can increase)
- Reserved concurrency guarantees capacity

**Provisioned Concurrency:**
- Eliminates cold starts
- Always-on cost
- Use for latency-critical functions

**Error Handling:**
- Sync: Client retries with exponential backoff
- Async: Auto-retry + DLQ
- Stream: Partial batch failure support

**Destinations:**
- Route success/failure to different targets
- Enables event-driven workflows
- Better than DLQ for complex routing

---

## RELATED

**Core:**
- AWS-Lambda-Core-Concepts.md
- AWS-Lambda-Runtime-Environment.md

**Decisions:**
- AWS-Lambda-DEC-04: Stateless Design
- AWS-Lambda-DEC-03: Timeout Limits

**Lessons:**
- AWS-Lambda-LESS-03: Timeout Management
- AWS-Lambda-LESS-04: Cost Monitoring

---

**END OF FILE**

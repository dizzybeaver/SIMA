# AWS-Lambda-DT-14.md

**Version:** 1.0.0  
**Date:** 2025-11-10  
**Purpose:** Async processing decision tree for Lambda workloads  
**Category:** Platform - AWS Lambda - Decision Tree  
**Priority:** OPTIONAL (Future extensibility pattern)

---

## DECISION TREE

**DT-14: Async Processing Pattern Selection**

**Purpose:** Choose appropriate async processing pattern based on task duration and requirements  
**Status:** REFERENCE - Not currently used in LEE project  
**Complexity:** MEDIUM

---

## OVERVIEW

AWS Lambda supports multiple async processing patterns for tasks that exceed direct invocation constraints or require decoupled execution. This decision tree guides pattern selection based on workload characteristics.

**Key patterns:**
1. Synchronous invocation (RequestResponse)
2. Asynchronous invocation (Event)
3. Message queue + Lambda polling
4. Step Functions orchestration
5. EventBridge scheduling

**Source:** Srivastava et al. (2023), Lloyd et al. (2018) on serverless orchestration

---

## DECISION TREE

```
START: Task Duration?

├─ < 30 seconds
│  └─ Synchronous Invocation
│     ├─ API Gateway: RequestResponse
│     ├─ Direct invoke: wait for result
│     └─ User-facing: immediate response
│
├─ 30 seconds - 5 minutes
│  └─ Immediate response needed?
│     ├─ YES: Synchronous with timeout handling
│     │  └─ Return continuation token
│     └─ NO: Which pattern?
│        ├─ Simple fire-and-forget
│        │  └─ Asynchronous Invocation
│        │     └─ Lambda Event invoke
│        │
│        ├─ Reliable processing required
│        │  └─ Message Queue Pattern
│        │     ├─ SQS → Lambda
│        │     ├─ Retry built-in
│        │     └─ DLQ for failures
│        │
│        └─ Multiple steps/dependencies
│           └─ Step Functions (Express)
│              ├─ Fast execution
│              └─ 5-minute limit
│
├─ 5 minutes - 15 minutes
│  └─ Processing pattern?
│     ├─ Single task: Asynchronous Invocation
│     │  ├─ Lambda Event invoke
│     │  └─ 15-minute timeout
│     │
│     ├─ Multiple steps: Step Functions (Standard)
│     │  ├─ Workflow coordination
│     │  └─ State management
│     │
│     └─ Batch of independent items: Fan-out Pattern
│        ├─ Parent Lambda fans out
│        ├─ Child Lambdas process in parallel
│        └─ Aggregate results
│
└─ > 15 minutes (Exceeds Lambda timeout)
   └─ Decomposition required
      ├─ Step Functions Orchestration
      │  ├─ Break into <15-minute subtasks
      │  ├─ State machine coordinates
      │  └─ 1-year execution limit
      │
      ├─ Message Queue Chaining
      │  ├─ Lambda processes chunk
      │  ├─ Writes continuation to queue
      │  └─ Next Lambda picks up
      │
      └─ Alternative: ECS Fargate
         ├─ Long-running tasks (hours/days)
         ├─ Not Lambda (wrong tool)
         └─ Container-based execution
```

---

## PATTERN DETAILS

### Pattern 1: Synchronous Invocation

**When to use:**
- Task duration: < 30 seconds
- User-facing operations
- Immediate response required
- Simple request-response

**Implementation:**

```python
# API Gateway integration
def lambda_handler(event, context):
    result = process_request(event)
    return {
        'statusCode': 200,
        'body': json.dumps(result)
    }
```

**Characteristics:**
- **Timeout:** API Gateway 30s, ALB 60s
- **Retry:** Caller responsibility
- **Error handling:** Return error response
- **Cost:** Charged for actual duration

**Best for:** APIs, webhooks, user-triggered actions

### Pattern 2: Asynchronous Invocation

**When to use:**
- Task duration: 30s - 15 minutes
- Fire-and-forget acceptable
- No immediate response needed
- Retry on failure desired

**Implementation:**

```python
# Invoke Lambda asynchronously
import boto3

lambda_client = boto3.client('lambda')

response = lambda_client.invoke(
    FunctionName='my-function',
    InvocationType='Event',  # Async
    Payload=json.dumps(event_data)
)

# Returns immediately (202 Accepted)
# Lambda processes in background
```

**Handler:**

```python
def lambda_handler(event, context):
    # Processes event asynchronously
    result = long_running_task(event)
    
    # Store result in S3/DynamoDB
    save_result(result)
    
    # No return value needed
```

**Characteristics:**
- **Timeout:** 15 minutes max
- **Retry:** Automatic (2 retries)
- **DLQ:** Failed events to SQS/SNS
- **Error handling:** Lambda manages retries

**Best for:** Background jobs, event processing, notifications

### Pattern 3: Message Queue Pattern

**When to use:**
- Reliable processing required
- Rate limiting needed
- Backpressure management
- Retry with delays

**Implementation:**

```python
# Producer: Send to SQS
import boto3

sqs = boto3.client('sqs')

sqs.send_message(
    QueueUrl='https://sqs.us-east-1.amazonaws.com/123456789012/my-queue',
    MessageBody=json.dumps(task_data)
)
```

**Consumer:**

```python
# Lambda polls SQS
def lambda_handler(event, context):
    for record in event['Records']:
        body = json.loads(record['body'])
        
        try:
            process_task(body)
            # Success: message deleted automatically
        except Exception as e:
            # Failure: message returned to queue
            raise  # Trigger retry
```

**Configuration:**

```yaml
SQS Queue:
  VisibilityTimeout: 300  # 5 minutes (longer than Lambda timeout)
  ReceiveMessageWaitTimeSeconds: 20  # Long polling
  MaxReceiveCount: 3  # Max retries
  RedrivePolicy:
    deadLetterTargetArn: arn:aws:sqs:...:my-dlq

Lambda:
  BatchSize: 10  # Process 10 messages per invocation
  MaximumBatchingWindowInSeconds: 5  # Wait up to 5s for batch
```

**Characteristics:**
- **Timeout:** Function timeout + visibility timeout
- **Retry:** Automatic with delays
- **DLQ:** After max retries
- **Backpressure:** Queue buffers overflow

**Best for:** Task queues, job processing, rate-limited operations

### Pattern 4: Step Functions (Express)

**When to use:**
- Task duration: < 5 minutes
- Multiple coordinated steps
- Fast workflow execution
- High volume (>10K/sec)

**Implementation:**

```json
{
  "Comment": "Fast workflow",
  "StartAt": "Process",
  "States": {
    "Process": {
      "Type": "Task",
      "Resource": "arn:aws:states:::lambda:invoke",
      "Parameters": {
        "FunctionName": "my-function",
        "Payload.$": "$"
      },
      "Next": "Transform"
    },
    "Transform": {
      "Type": "Task",
      "Resource": "arn:aws:states:::lambda:invoke",
      "Parameters": {
        "FunctionName": "transform-function",
        "Payload.$": "$.Payload"
      },
      "End": true
    }
  }
}
```

**Characteristics:**
- **Timeout:** 5 minutes max
- **Execution history:** Not persisted (fast!)
- **Cost:** ~$1 per 1M executions
- **Throughput:** >100K executions/sec

**Best for:** Stream processing, IoT data, high-volume workflows

### Pattern 5: Step Functions (Standard)

**When to use:**
- Task duration: > 5 minutes, < 1 year
- Long-running workflows
- Human approval steps
- Complex orchestration

**Implementation:**

```json
{
  "Comment": "Long-running workflow",
  "StartAt": "Process",
  "States": {
    "Process": {
      "Type": "Task",
      "Resource": "arn:aws:states:::lambda:invoke",
      "Parameters": {
        "FunctionName": "process-function",
        "Payload.$": "$"
      },
      "Next": "WaitForApproval"
    },
    "WaitForApproval": {
      "Type": "Task",
      "Resource": "arn:aws:states:::lambda:invoke.waitForTaskToken",
      "Parameters": {
        "FunctionName": "send-approval-request",
        "Payload": {
          "TaskToken.$": "$$.Task.Token",
          "Data.$": "$"
        }
      },
      "Next": "ProcessApproval"
    },
    "ProcessApproval": {
      "Type": "Task",
      "Resource": "arn:aws:states:::lambda:invoke",
      "Parameters": {
        "FunctionName": "process-approval",
        "Payload.$": "$"
      },
      "End": true
    }
  }
}
```

**Characteristics:**
- **Timeout:** 1 year max
- **Execution history:** Full history persisted
- **Cost:** ~$25 per 1M state transitions
- **Throughput:** Moderate

**Best for:** Order processing, approval workflows, ETL pipelines

### Pattern 6: Fan-out Pattern

**When to use:**
- Batch of independent tasks
- Parallel processing desired
- N × speedup achievable
- Results aggregation needed

**Implementation:**

```python
# Parent Lambda: Fan out
import boto3

lambda_client = boto3.client('lambda')

def fan_out_handler(event, context):
    items = event['items']  # 1,000 items
    chunk_size = 100
    
    # Fan out to child Lambdas
    invocation_ids = []
    for i in range(0, len(items), chunk_size):
        chunk = items[i:i+chunk_size]
        
        response = lambda_client.invoke(
            FunctionName='child-processor',
            InvocationType='Event',  # Async
            Payload=json.dumps({
                'chunk_id': i // chunk_size,
                'items': chunk
            })
        )
        invocation_ids.append(response['RequestId'])
    
    # Store invocation IDs for tracking
    return {
        'total_items': len(items),
        'chunks': len(invocation_ids),
        'invocation_ids': invocation_ids
    }
```

**Child Lambda:**

```python
# Child Lambda: Process chunk
def child_processor_handler(event, context):
    chunk_id = event['chunk_id']
    items = event['items']
    
    results = []
    for item in items:
        result = process_item(item)
        results.append(result)
    
    # Store results in S3/DynamoDB
    save_chunk_results(chunk_id, results)
    
    return {'chunk_id': chunk_id, 'count': len(results)}
```

**Characteristics:**
- **Parallelism:** N concurrent Lambdas
- **Speedup:** Near-linear (NÃ—) for independent tasks
- **Limit:** Account concurrency limit (1,000 default)
- **Aggregation:** Collect results from S3/DynamoDB

**Best for:** Batch processing, image resizing, data transformations

---

## DECISION FACTORS

### Factor 1: Task Duration

| Duration | Recommended Pattern |
|----------|-------------------|
| < 30s | Synchronous invocation |
| 30s - 5min | Async invocation OR Message queue |
| 5min - 15min | Async invocation OR Step Functions |
| > 15min | Step Functions (decompose tasks) |

### Factor 2: Response Requirements

| Requirement | Pattern |
|-------------|---------|
| Immediate response | Synchronous |
| Background processing | Asynchronous |
| Status tracking | Step Functions |
| Eventual consistency | Message queue |

### Factor 3: Error Handling

| Needs | Pattern |
|-------|---------|
| Automatic retry | Async OR Message queue |
| Custom retry logic | Message queue |
| Complex error workflows | Step Functions |
| Simple error response | Synchronous |

### Factor 4: Scale Requirements

| Throughput | Pattern |
|-----------|---------|
| < 100/sec | Any pattern |
| 100-1K/sec | Async OR Express Step Functions |
| 1K-10K/sec | Express Step Functions |
| > 10K/sec | Kinesis OR Kafka (not Lambda-only) |

---

## EXAMPLES

### Example 1: Image Processing API

**Requirements:**
- User uploads image
- Resize to 3 sizes (thumbnail, medium, large)
- Return immediately with job ID
- User polls for status

**Solution: Async Invocation**

```python
# API: Accept upload
def upload_handler(event, context):
    job_id = generate_job_id()
    image_data = event['body']
    
    # Store original in S3
    s3_key = f'uploads/{job_id}/original.jpg'
    s3.put_object(Bucket='my-bucket', Key=s3_key, Body=image_data)
    
    # Invoke processor asynchronously
    lambda_client.invoke(
        FunctionName='image-processor',
        InvocationType='Event',
        Payload=json.dumps({'job_id': job_id, 's3_key': s3_key})
    )
    
    return {'jobId': job_id, 'status': 'processing'}

# Processor: Resize images
def processor_handler(event, context):
    job_id = event['job_id']
    s3_key = event['s3_key']
    
    # Download original
    original = s3.get_object(Bucket='my-bucket', Key=s3_key)['Body'].read()
    
    # Resize to 3 sizes
    for size in ['thumbnail', 'medium', 'large']:
        resized = resize_image(original, size)
        s3.put_object(
            Bucket='my-bucket',
            Key=f'uploads/{job_id}/{size}.jpg',
            Body=resized
        )
    
    # Update status
    dynamodb.put_item(
        TableName='jobs',
        Item={'jobId': job_id, 'status': 'complete'}
    )
```

**Duration:** 2-5 seconds  
**Pattern:** Async invocation (perfect fit)

### Example 2: Order Fulfillment Workflow

**Requirements:**
- Process order (1 minute)
- Wait for payment (variable, hours)
- Ship items (30 seconds)
- Send confirmation (5 seconds)

**Solution: Step Functions (Standard)**

```json
{
  "StartAt": "ProcessOrder",
  "States": {
    "ProcessOrder": {
      "Type": "Task",
      "Resource": "arn:aws:states:::lambda:invoke",
      "Parameters": {
        "FunctionName": "process-order"
      },
      "Next": "WaitForPayment"
    },
    "WaitForPayment": {
      "Type": "Task",
      "Resource": "arn:aws:states:::lambda:invoke.waitForTaskToken",
      "Parameters": {
        "FunctionName": "initiate-payment",
        "Payload": {
          "TaskToken.$": "$$.Task.Token"
        }
      },
      "Next": "ShipItems"
    },
    "ShipItems": {
      "Type": "Task",
      "Resource": "arn:aws:states:::lambda:invoke",
      "Parameters": {
        "FunctionName": "ship-items"
      },
      "Next": "SendConfirmation"
    },
    "SendConfirmation": {
      "Type": "Task",
      "Resource": "arn:aws:states:::lambda:invoke",
      "Parameters": {
        "FunctionName": "send-email"
      },
      "End": true
    }
  }
}
```

**Duration:** Minutes to hours (variable)  
**Pattern:** Step Functions Standard (handles wait states)

### Example 3: Data Pipeline

**Requirements:**
- Extract 1M records from database (5 minutes)
- Transform each record (independent)
- Load to data warehouse (5 minutes)

**Solution: Fan-out Pattern**

```python
# Extract: Get all records
def extract_handler(event, context):
    records = fetch_all_records()  # 1M records
    
    # Split into 100 chunks
    chunk_size = 10000
    for i in range(0, len(records), chunk_size):
        chunk = records[i:i+chunk_size]
        
        # Fan out to transform Lambdas
        lambda_client.invoke(
            FunctionName='transform-function',
            InvocationType='Event',
            Payload=json.dumps({'chunk_id': i, 'records': chunk})
        )
    
    return {'status': 'processing', 'total_records': len(records)}

# Transform: Process chunk
def transform_handler(event, context):
    chunk_id = event['chunk_id']
    records = event['records']
    
    transformed = [transform_record(r) for r in records]
    
    # Store in S3 for loading
    s3.put_object(
        Bucket='etl-bucket',
        Key=f'transformed/chunk_{chunk_id}.json',
        Body=json.dumps(transformed)
    )

# Load: When all chunks complete (EventBridge rule)
def load_handler(event, context):
    # List all transformed chunks
    chunks = s3.list_objects(Bucket='etl-bucket', Prefix='transformed/')
    
    # Load to data warehouse
    for chunk in chunks:
        data = s3.get_object(Bucket='etl-bucket', Key=chunk['Key'])
        load_to_warehouse(data)
```

**Duration:** 10-15 minutes total  
**Pattern:** Fan-out (100x parallelism)  
**Speedup:** 100 minutes → 10 minutes

---

## ANTI-PATTERNS

### ❌ Anti-Pattern 1: Sync for Long Tasks

**Wrong:**
```python
def api_handler(event, context):
    # This takes 5 minutes!
    result = long_running_task(event)
    return result
```

**Problem:** API Gateway times out at 30 seconds

**Right:** Use async pattern

### ❌ Anti-Pattern 2: Polling in Lambda

**Wrong:**
```python
def handler(event, context):
    while not task_complete():
        time.sleep(1)  # Polling!
```

**Problem:** Wastes Lambda execution time

**Right:** Use Step Functions wait states

### ❌ Anti-Pattern 3: No Error Handling

**Wrong:**
```python
lambda_client.invoke(
    FunctionName='my-function',
    InvocationType='Event',
    Payload=payload
)
# Fire and forget - no error tracking!
```

**Problem:** Silent failures

**Right:** Use DLQ or Step Functions

---

## RELATED

**Decisions:**
- DEC-03: Timeout Limits
- DEC-04: Stateless Design

**Lessons:**
- LESS-04: Timeout Management
- LESS-07: Error Handling Patterns
- LESS-14: Step Functions Orchestration (HIGH-priority lesson)

**Anti-Patterns:**
- AP-04: Ignoring Timeout

---

## CONCLUSION

Async processing patterns enable Lambda to handle workloads of any duration by:
1. Decoupling invocation from execution
2. Breaking long tasks into smaller chunks
3. Coordinating workflows with Step Functions
4. Handling failures with queues and retries

**Current LEE status:** Not using async patterns (all sync)  
**Future consideration:** If adding background jobs or long-running tasks

---

## VERSION HISTORY

**v1.0.0 (2025-11-10):**
- Initial decision tree documentation
- 6 async patterns documented
- Decision factors matrix
- 3 real-world examples
- Anti-patterns identified
- Integration with existing lessons

---

**END OF FILE**

**Status:** REFERENCE - Available for future use  
**Priority:** LOW for LEE project (all current tasks < 30s)  
**Application:** Consider when adding background processing features

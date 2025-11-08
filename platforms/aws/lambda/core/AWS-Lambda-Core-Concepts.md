# AWS-Lambda-Core-Concepts.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Core concepts and fundamentals of AWS Lambda  
**Category:** Platform - AWS Lambda  
**Type:** Core

---

## OVERVIEW

AWS Lambda is a serverless compute service that runs code in response to events and automatically manages compute resources. Understanding Lambda's core concepts is essential for building efficient serverless applications.

---

## EXECUTION MODEL

### Event-Driven Architecture

**Lambda executes in response to events:**
- API Gateway HTTP requests
- S3 object uploads
- DynamoDB stream updates
- CloudWatch scheduled events
- Custom application events

### Invocation Types

**Synchronous (RequestResponse):**
- Caller waits for function completion
- Returns response directly
- Used by: API Gateway, AWS SDK direct invokes
- Timeout: Up to 15 minutes (900 seconds)

**Asynchronous (Event):**
- Caller receives immediate acceptance
- Lambda queues event for processing
- Used by: S3, SNS, CloudWatch Events
- Built-in retry logic (2 automatic retries)

**Stream-Based:**
- Lambda polls stream for records
- Batch processing
- Used by: DynamoDB Streams, Kinesis
- Configurable batch size

---

## RUNTIME ENVIRONMENT

### Execution Context

**Each invocation runs in an execution context:**
```
Execution Context (Container)
├── Lambda Runtime (Node.js, Python, etc.)
├── Your Code
├── /tmp directory (512 MB - 10 GB)
├── Environment Variables
├── AWS SDK
└── Network Connectivity
```

**Context Reuse:**
- Lambda may reuse execution contexts
- Connections, file handles persist between invocations
- Enables optimization (connection pooling, caching)
- Not guaranteed - always assume cold start possible

### Resource Limits

**Memory:** 128 MB to 10,240 MB (in 1 MB increments)  
**Timeout:** 1 second to 900 seconds (15 minutes)  
**Ephemeral Storage (/tmp):** 512 MB to 10,240 MB  
**Payload Size:** 6 MB synchronous, 256 KB asynchronous  
**Concurrency:** 1,000 concurrent executions (can request increase)  
**Deployment Package:** 50 MB (zipped), 250 MB (unzipped)

---

## COLD START VS WARM START

### Cold Start

**Occurs when:**
- First invocation after deployment
- No warm execution contexts available
- Function hasn't been invoked recently (>15 minutes typically)

**Cold Start Phases:**
```
1. Download Code (from S3)         ~100-300ms
2. Start Execution Environment     ~100-400ms
3. Bootstrap Runtime               ~50-200ms
4. Initialize Code (imports, etc.) Variable (0-5000ms+)
5. Execute Handler                 Variable
```

**Total Cold Start:** Typically 200-2000ms, can be 5000ms+ with heavy dependencies

### Warm Start

**Occurs when:**
- Execution context is reused
- Recent invocation (within last 15 minutes)
- Available warm containers

**Warm Start Phases:**
```
1. Execute Handler                 Variable (only phase executed)
```

**Total Warm Start:** Typically 1-50ms for handler execution

**Optimization Impact:**
- Cold: 2000ms total (1950ms overhead + 50ms handler)
- Warm: 50ms total (0ms overhead + 50ms handler)
- **40x performance difference**

---

## CONCURRENCY MODEL

### Concurrent Executions

**Lambda creates one execution context per concurrent request:**
- 10 simultaneous requests = 10 concurrent executions
- Each execution is isolated
- No shared state between executions
- Account-level concurrency limit (default 1000)

### Scaling Behavior

**Automatic Scaling:**
```
Requests/second: 100 → Lambda creates 100 concurrent executions
Requests/second: 500 → Lambda creates 500 concurrent executions
Requests/second: 50  → Lambda scales down to 50
```

**Burst Limits:**
- Initial burst: 3000 concurrent executions
- Then: 500 additional executions per minute
- After burst: Limited by account concurrency limit

### Reserved Concurrency

**Purpose:** Guarantee minimum concurrent executions  
**Effect:** Reserves portion of account concurrency  
**Use Case:** Critical functions that must not be throttled  
**Trade-off:** Reduces available concurrency for other functions

---

## SINGLE-THREADED EXECUTION

### Execution Model

**Critical Constraint:**
- Lambda executes code in **single-threaded** environment
- Python Global Interpreter Lock (GIL) applies
- JavaScript is single-threaded by nature
- No benefit from threading primitives

**Implications:**
```python
# ❌ WRONG - Threading doesn't help in Lambda
import threading
lock = threading.Lock()  # Unnecessary, only one thread

# ✅ CORRECT - Use async/await for concurrency
import asyncio
async def fetch_data():
    # Concurrent I/O operations
    results = await asyncio.gather(
        fetch_api1(),
        fetch_api2()
    )
```

**Best Practices:**
- Use async I/O for concurrent operations
- Avoid threading locks and semaphores
- Use multiprocessing only if worth cold start cost
- Leverage Lambda's concurrency model (multiple invocations)

---

## STATELESS DESIGN

### Core Principle

**Lambda functions must be stateless:**
- No persistent local state between invocations
- Can't rely on in-memory state
- Must use external storage for persistence

**Storage Options:**
```
DynamoDB      - Fast key-value and document storage
S3            - Object storage for larger data
RDS           - Relational database (use with connection pooling)
ElastiCache   - In-memory cache (Redis, Memcached)
SSM Parameter - Configuration and secrets
```

### Context Reuse Patterns

**Can leverage context reuse for optimization:**
```python
# Module-level (outside handler) - Persists if context reused
db_connection = None

def lambda_handler(event, context):
    global db_connection
    
    # Reuse connection if available (warm start)
    if db_connection is None:
        db_connection = create_db_connection()  # Cold start only
    
    # Use connection
    result = db_connection.query(...)
    return result
```

**But always handle cold start:**
- Initialize connections outside handler when possible
- Always check and recreate if needed
- Don't assume context will be reused

---

## NETWORKING

### VPC Configuration

**Lambda can run in VPC:**
- Access private resources (RDS, ElastiCache, internal APIs)
- Uses Elastic Network Interfaces (ENI)
- ENI creation adds cold start time (~10-30 seconds historically)
- **Improvement:** ENI pre-creation (2019+) reduced this significantly

**Trade-offs:**
```
Non-VPC Lambda:
+ Faster cold start
+ Simpler configuration
- No access to private resources
- Must use public endpoints

VPC Lambda:
+ Access to private resources
+ Enhanced security
- Slightly longer cold start (now minimal)
- Requires VPC configuration
```

### Internet Access

**Non-VPC Lambda:** Automatic internet access  
**VPC Lambda:** Requires NAT Gateway for internet access  
- Place Lambda in private subnets
- Route through NAT Gateway in public subnet
- Additional cost for NAT Gateway

---

## IAM PERMISSIONS

### Execution Role

**Lambda needs IAM role with two types of permissions:**

**Trust Policy (who can assume role):**
```json
{
  "Version": "2012-10-17",
  "Statement": [{
    "Effect": "Allow",
    "Principal": { "Service": "lambda.amazonaws.com" },
    "Action": "sts:AssumeRole"
  }]
}
```

**Permissions Policy (what role can do):**
```json
{
  "Version": "2012-10-17",
  "Statement": [{
    "Effect": "Allow",
    "Action": [
      "logs:CreateLogGroup",
      "logs:CreateLogStream",
      "logs:PutLogEvents"
    ],
    "Resource": "arn:aws:logs:*:*:*"
  }]
}
```

**Principle of Least Privilege:**
- Grant only necessary permissions
- Be specific with resources
- Avoid wildcards (*) when possible

---

## ENVIRONMENT VARIABLES

### Configuration

**Environment variables provide runtime configuration:**
- Set via Lambda console, CLI, or Infrastructure as Code
- Available as OS environment variables
- Can be encrypted with KMS
- Maximum 4 KB total size

**Common Uses:**
```python
import os

# Database connection
DB_HOST = os.environ['DB_HOST']
DB_NAME = os.environ['DB_NAME']

# Feature flags
FEATURE_X_ENABLED = os.environ.get('FEATURE_X_ENABLED', 'false') == 'true'

# API keys (use SSM Parameter Store for secrets instead)
API_KEY = os.environ.get('API_KEY')
```

**Best Practices:**
- Use for non-sensitive configuration
- Use SSM Parameter Store or Secrets Manager for secrets
- Provide defaults for optional settings
- Validate environment variables at initialization

---

## ERROR HANDLING

### Error Types

**Lambda distinguishes between error types:**

**Retryable Errors:**
- Throttles (TooManyRequestsException)
- Service errors (500-series)
- Timeout errors

**Non-Retryable Errors:**
- Invalid permissions (403)
- Invalid request payload (400)
- Function errors (unhandled exceptions)

### Retry Behavior

**Synchronous Invocations:**
- No automatic retries
- Client responsible for retry logic

**Asynchronous Invocations:**
- 2 automatic retries
- Exponential backoff between retries
- Can configure Dead Letter Queue (DLQ)

**Stream-Based Invocations:**
- Retries until data expires or succeeds
- Can configure maximum retry attempts
- Can configure error destinations

---

## MONITORING

### CloudWatch Integration

**Automatic Metrics:**
- Invocations (count)
- Duration (milliseconds)
- Errors (count)
- Throttles (count)
- Concurrent Executions (count)
- Dead Letter Queue Errors (count)

**Automatic Logs:**
- Function logs via console.log / print statements
- START/END/REPORT lines for each invocation
- Errors and stack traces
- Custom structured logs (JSON recommended)

**Custom Metrics:**
```python
import boto3
cloudwatch = boto3.client('cloudwatch')

cloudwatch.put_metric_data(
    Namespace='MyApp/Lambda',
    MetricData=[{
        'MetricName': 'CacheHitRate',
        'Value': hit_rate,
        'Unit': 'Percent'
    }]
)
```

---

## VERSIONING AND ALIASES

### Versions

**Lambda versions are immutable:**
- Each publish creates new version ($LATEST is mutable)
- Version ARN includes version number
- Can't modify published version
- Useful for rollback and blue/green deployments

### Aliases

**Aliases are pointers to versions:**
```
Alias: "prod" → Version 3
Alias: "dev"  → Version 5 (or $LATEST)
```

**Use Cases:**
- Blue/green deployments (shift alias from v1 to v2)
- Weighted traffic shifting (canary deployments)
- Environment separation (dev, staging, prod)

**Traffic Shifting:**
```
Alias "prod": 90% → Version 3, 10% → Version 4
(Gradual migration to new version)
```

---

## COST MODEL

### Billing Factors

**Two Primary Factors:**

**1. Number of Requests:**
- $0.20 per 1 million requests
- First 1 million requests/month are free

**2. Duration:**
- Charged per GB-second
- $0.0000166667 per GB-second
- $0.10 per GB-hour (approximate)
- First 400,000 GB-seconds/month are free

### Cost Calculation

**Example:**
```
Function: 512 MB memory
Executions: 3 million/month
Avg Duration: 200 ms

Memory Cost:
= (512 MB / 1024 GB) × 0.2 seconds × 3,000,000 executions
= 0.5 GB × 0.2 s × 3M
= 300,000 GB-seconds
= $5.00 (300,000 × $0.0000166667)

Request Cost:
= 3,000,000 requests
= $0.40 (2M billable × $0.20/million, minus free tier)

Total: $5.40/month
```

**Cost Optimization:**
- Right-size memory allocation
- Minimize cold starts
- Use reserved concurrency carefully
- Monitor and optimize duration

---

## KEY TAKEAWAYS

**Execution Model:**
- Event-driven, serverless compute
- Three invocation types (sync, async, stream)
- Cold starts vs warm starts critical for performance

**Resource Constraints:**
- Memory: 128 MB - 10 GB
- Timeout: 1 second - 15 minutes
- Ephemeral storage: 512 MB - 10 GB

**Concurrency:**
- Automatic scaling
- One execution per concurrent request
- Account concurrency limits apply

**Design Principles:**
- Stateless by design
- Single-threaded execution
- Idempotent operations preferred
- External storage for persistence

**Monitoring:**
- CloudWatch metrics and logs automatic
- Custom metrics available
- X-Ray for distributed tracing

---

## RELATED

**Decisions:**
- AWS-Lambda-DEC-01: Single-Threaded Execution
- AWS-Lambda-DEC-02: Memory Constraints
- AWS-Lambda-DEC-03: Timeout Limits
- AWS-Lambda-DEC-04: Stateless Design

**Lessons:**
- AWS-Lambda-LESS-01: Cold Start Impact
- AWS-Lambda-LESS-02: Memory-Performance Trade-off
- AWS-Lambda-LESS-03: Timeout Management

**Anti-Patterns:**
- AWS-Lambda-AP-01: Threading Primitives
- AWS-Lambda-AP-02: Stateful Operations

---

**END OF FILE**

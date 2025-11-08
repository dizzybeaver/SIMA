# AWS-Lambda-Runtime-Environment.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Lambda runtime environment details and lifecycle  
**Category:** Platform - AWS Lambda  
**Type:** Core

---

## OVERVIEW

Understanding Lambda's runtime environment is essential for writing efficient serverless functions. The runtime environment determines how your code executes, what resources are available, and how to optimize performance.

---

## EXECUTION ENVIRONMENT LIFECYCLE

### Phases

**Lambda execution environment goes through distinct phases:**

```
1. INIT Phase (Cold Start Only)
   ├─ Download code from S3
   ├─ Create execution environment
   ├─ Load runtime
   ├─ Load extensions
   ├─ Initialize code (module-level)
   └─ Run init handler (if defined)

2. INVOKE Phase (Every Invocation)
   ├─ Receive event
   ├─ Execute handler function
   └─ Return response

3. SHUTDOWN Phase (Environment Termination)
   ├─ Run shutdown handlers
   ├─ Clean up resources
   └─ Terminate environment
```

### Init Phase Details

**Occurs only on cold start:**

**1. Environment Preparation (100-400ms):**
- Download deployment package from S3
- Extract code and dependencies
- Set up execution environment
- Provision compute resources

**2. Runtime Initialization (50-200ms):**
- Start language runtime (Python, Node.js, etc.)
- Load AWS SDK and Lambda runtime
- Initialize runtime components

**3. Code Initialization (Variable, 0-5000ms+):**
```python
# Module-level code runs ONCE per execution context
import boto3  # Loaded once during INIT

# Connection created ONCE and reused
dynamodb = boto3.resource('dynamodb')
table = dynamodb.Table('my-table')

def lambda_handler(event, context):
    # This runs every invocation (INVOKE phase)
    return table.get_item(Key={'id': event['id']})
```

**Optimization Opportunity:**
- Heavy operations in module-level code = one-time cost
- Reused across warm invocations
- But increases cold start time

---

## RUNTIME OPTIONS

### Supported Runtimes

**Managed Runtimes (AWS maintains):**
- Python: 3.8, 3.9, 3.10, 3.11, 3.12
- Node.js: 16.x, 18.x, 20.x
- Java: 8, 11, 17, 21
- .NET: 6, 8
- Go: 1.x
- Ruby: 3.2, 3.3

**Custom Runtimes:**
- Provide your own runtime
- Use Runtime API
- Package everything in deployment
- Example: Rust, C++, PHP (via custom runtime)

**Container Image Support:**
- Package function as container image
- Base image from AWS or custom
- Up to 10 GB image size
- Slower cold start than zip deployment

### Runtime Selection Trade-offs

```
Python:
+ Easy to learn and use
+ Rich ecosystem (packages)
+ Fast warm start
- Slower than compiled languages
- GIL limits threading

Node.js:
+ Very fast cold start
+ Async I/O native
+ Huge npm ecosystem
- Callback hell (mitigated by async/await)
- Single-threaded

Java:
+ High performance
+ Strong typing
+ Extensive enterprise libs
- Slow cold start (JVM initialization)
- Higher memory usage

Go:
+ Very fast execution
+ Fast cold start
+ Low memory footprint
+ Compiled binary
- Less ecosystem than Python/Node
- Steeper learning curve
```

---

## EXECUTION CONTEXT

### Environment Structure

**Each execution context includes:**

```
/var/task/        - Your code and dependencies (read-only)
/tmp/             - Writable temporary storage (512 MB - 10 GB)
/opt/             - Lambda layers (read-only)
/var/runtime/     - Lambda runtime files (read-only)

Environment Variables:
- AWS_LAMBDA_FUNCTION_NAME
- AWS_LAMBDA_FUNCTION_VERSION
- AWS_LAMBDA_FUNCTION_MEMORY_SIZE
- AWS_LAMBDA_LOG_GROUP_NAME
- AWS_LAMBDA_LOG_STREAM_NAME
- AWS_REGION
- AWS_EXECUTION_ENV (runtime identifier)
- (Plus custom environment variables)
```

### Context Reuse

**Lambda may reuse execution contexts:**

**Reuse Benefits:**
```python
# Global connections persist across invocations
import psycopg2

# Created ONCE on cold start
connection = None

def lambda_handler(event, context):
    global connection
    
    # Reuse if warm
    if connection is None or connection.closed:
        connection = psycopg2.connect(...)  # Cold start only
    
    # Use existing connection (warm start)
    cursor = connection.cursor()
    cursor.execute("SELECT * FROM users WHERE id = %s", (event['id'],))
    return cursor.fetchone()
```

**Reuse Considerations:**
- Not guaranteed - always handle cold start
- Typical reuse: 15-60 minutes
- After idle period: new execution context
- Scaling up: new execution contexts

**What Persists:**
- Module-level variables
- Open connections and file handles
- /tmp directory contents
- Compiled code (bytecode)

**What Doesn't Persist:**
- Handler-level variables
- Function return values
- Request-specific data

---

## MEMORY AND CPU ALLOCATION

### Memory Configuration

**Memory range: 128 MB to 10,240 MB (10 GB)**
- Increment: 1 MB
- Default: 128 MB (not recommended for production)

**CPU Allocated Proportionally:**
```
Memory    CPU Power         Approximate vCPU
128 MB    0.08 vCPU         Very slow
512 MB    0.33 vCPU         Slow
1024 MB   0.67 vCPU         Moderate
1769 MB   1.00 vCPU         Full core (recommended minimum)
3008 MB   1.70 vCPU         Fast
10240 MB  6.00 vCPU         Very fast
```

**Performance Impact:**
```
Test: Process 1000 API calls

256 MB:   45 seconds  ($0.09)
512 MB:   22 seconds  ($0.09)
1024 MB:  11 seconds  ($0.09)
1769 MB:  7 seconds   ($0.10)
3008 MB:  5 seconds   ($0.12)

Sweet spot often 1769-2048 MB (full vCPU at reasonable cost)
```

### Memory Management

**Best Practices:**

**1. Profile Memory Usage:**
```python
import os
import psutil

def lambda_handler(event, context):
    # Check memory usage
    process = psutil.Process(os.getpid())
    memory_mb = process.memory_info().rss / 1024 / 1024
    
    print(f"Memory used: {memory_mb:.2f} MB")
    print(f"Memory limit: {context.memory_limit_in_mb} MB")
```

**2. Right-Size Memory:**
- Start with 1024 MB
- Profile actual usage
- Increase if near limit
- Decrease if using <50%

**3. Monitor and Optimize:**
```
CloudWatch Metrics:
- Max memory used (REPORT line)
- Duration (execution time)
- Cost analysis
```

---

## /TMP DIRECTORY

### Ephemeral Storage

**/tmp is writable temporary storage:**
- Size: 512 MB (default) to 10,240 MB
- Persists across invocations in same execution context
- Cleared when execution context terminated
- Can be used for caching, temporary files

### Use Cases

**File Download and Processing:**
```python
import boto3
import os

s3 = boto3.client('s3')

def lambda_handler(event, context):
    # Download to /tmp
    local_file = '/tmp/data.csv'
    
    # Check if already downloaded (warm start)
    if not os.path.exists(local_file):
        s3.download_file('bucket', 'data.csv', local_file)
    
    # Process file
    with open(local_file, 'r') as f:
        data = f.read()
    
    return process_data(data)
```

**Caching:**
```python
import os
import json

CACHE_FILE = '/tmp/cache.json'

def lambda_handler(event, context):
    # Check cache (persists if warm)
    if os.path.exists(CACHE_FILE):
        with open(CACHE_FILE, 'r') as f:
            cached_data = json.load(f)
        if is_cache_valid(cached_data):
            return cached_data
    
    # Fetch fresh data
    data = fetch_data()
    
    # Cache for next invocation
    with open(CACHE_FILE, 'w') as f:
        json.dump(data, f)
    
    return data
```

**Best Practices:**
- Always check if file exists (warm start)
- Clean up large files when done
- Monitor /tmp usage
- Don't rely on /tmp for persistent storage

---

## ENVIRONMENT VARIABLES

### Configuration

**Set via multiple methods:**
- Lambda console
- AWS CLI
- CloudFormation / SAM
- Terraform
- CDK

**Accessed in code:**
```python
import os

# Required configuration
DATABASE_URL = os.environ['DATABASE_URL']

# Optional with default
LOG_LEVEL = os.environ.get('LOG_LEVEL', 'INFO')

# Boolean
DEBUG_MODE = os.environ.get('DEBUG', 'false').lower() == 'true'

# Numeric
MAX_RETRIES = int(os.environ.get('MAX_RETRIES', '3'))
```

### Encryption

**KMS Encryption:**
```python
import boto3
import os
from base64 import b64decode

# Encrypted at rest
ENCRYPTED_PASSWORD = os.environ['ENCRYPTED_PASSWORD']

# Decrypt on first use
kms = boto3.client('kms')
decrypted = kms.decrypt(
    CiphertextBlob=b64decode(ENCRYPTED_PASSWORD)
)
PASSWORD = decrypted['Plaintext'].decode('utf-8')
```

**Better Alternative - SSM Parameter Store:**
```python
import boto3
import os

ssm = boto3.client('ssm')

# Fetch from Parameter Store (cached in warm starts)
def get_secret(param_name):
    response = ssm.get_parameter(
        Name=param_name,
        WithDecryption=True
    )
    return response['Parameter']['Value']

DATABASE_PASSWORD = get_secret('/app/db/password')
```

### Size Limit

**4 KB total size for all environment variables:**
- Name + value count toward limit
- Encryption adds overhead
- Use SSM/Secrets Manager for large configs

---

## LAMBDA CONTEXT OBJECT

### Available Information

**context object provides runtime information:**

```python
def lambda_handler(event, context):
    print(f"Function name: {context.function_name}")
    print(f"Function version: {context.function_version}")
    print(f"Request ID: {context.aws_request_id}")
    print(f"Log group: {context.log_group_name}")
    print(f"Log stream: {context.log_stream_name}")
    print(f"Memory limit: {context.memory_limit_in_mb} MB")
    
    # Time remaining
    remaining_ms = context.get_remaining_time_in_millis()
    print(f"Time remaining: {remaining_ms} ms")
```

### Timeout Management

**Use remaining time to gracefully handle timeouts:**
```python
def lambda_handler(event, context):
    items_processed = 0
    
    for item in large_dataset:
        # Check if enough time remains
        remaining_ms = context.get_remaining_time_in_millis()
        if remaining_ms < 5000:  # Less than 5 seconds
            print(f"Timeout approaching, processed {items_processed} items")
            return {
                'processed': items_processed,
                'continuation_token': item.id
            }
        
        process_item(item)
        items_processed += 1
    
    return {'processed': items_processed, 'complete': True}
```

---

## EXTENSIONS

### Lambda Extensions

**Extensions run alongside function:**
- Monitoring and observability
- Security agents
- Configuration management
- Custom logic

**Types:**

**Internal Extensions:**
- Run in same process as function
- Modify runtime behavior
- Language-specific

**External Extensions:**
- Separate processes
- Independent lifecycle
- Can outlive function invocation

**Popular Extensions:**
- AWS AppConfig Agent
- AWS Parameters and Secrets
- Datadog Agent
- New Relic Agent

**Trade-offs:**
- Extensions add memory overhead
- May add latency
- Can improve observability
- Consider cost vs benefit

---

## COLD START OPTIMIZATION

### Strategies

**1. Right-Size Memory:**
```
Test reveals optimal memory:
512 MB:  2.5s cold start
1024 MB: 1.8s cold start (-28%)
2048 MB: 1.5s cold start (-40%)
```

**2. Minimize Dependencies:**
```python
# ❌ SLOW - Heavy imports
import pandas as pd
import numpy as np
import tensorflow as tf

# ✅ FAST - Minimal imports
import json
import boto3
```

**3. Lazy Loading:**
```python
# Don't load at module level if not always needed
def lambda_handler(event, context):
    if event['type'] == 'ml_inference':
        # Only load if needed
        import tensorflow as tf
        return run_model(tf, event)
    
    return standard_response(event)
```

**4. Provisioned Concurrency:**
```
Pre-warmed execution environments:
- Eliminates cold starts
- Additional cost
- Use for critical low-latency functions
```

**5. Lambda SnapStart (Java):**
- Caches initialized state
- Restores from cache on cold start
- Significantly faster Java cold starts

---

## KEY TAKEAWAYS

**Environment Lifecycle:**
- INIT phase (cold start only)
- INVOKE phase (every invocation)
- SHUTDOWN phase (environment termination)

**Execution Context:**
- May be reused across invocations
- Not guaranteed - handle cold starts
- Persists connections, /tmp, module-level vars

**Memory and CPU:**
- Memory determines CPU allocation
- 1769 MB = 1 full vCPU
- Profile and right-size

**/tmp Storage:**
- 512 MB - 10 GB ephemeral storage
- Persists in warm starts
- Not for persistent data

**Environment Variables:**
- 4 KB size limit
- KMS encryption available
- Use SSM for sensitive data

**Optimization:**
- Minimize dependencies
- Use lazy loading
- Consider provisioned concurrency
- Monitor cold start frequency

---

## RELATED

**Core:**
- AWS-Lambda-Core-Concepts.md

**Decisions:**
- AWS-Lambda-DEC-02: Memory Constraints
- AWS-Lambda-DEC-03: Timeout Limits

**Lessons:**
- AWS-Lambda-LESS-01: Cold Start Impact
- AWS-Lambda-LESS-02: Memory-Performance Trade-off

---

**END OF FILE**

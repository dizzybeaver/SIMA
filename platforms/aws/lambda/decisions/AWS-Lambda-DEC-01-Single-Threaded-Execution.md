# AWS-Lambda-DEC-01-Single-Threaded-Execution.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Lambda single-threaded execution constraint  
**Category:** Platform - AWS Lambda  
**Type:** Decision

---

## DECISION

**Never use threading primitives in AWS Lambda functions.**

---

## CONTEXT

AWS Lambda executes code in a single-threaded environment. Python's Global Interpreter Lock (GIL), JavaScript's single-threaded event loop, and Lambda's execution model make threading primitives ineffective or counterproductive.

---

## RATIONALE

### Lambda Execution Model

**Lambda runs code in single thread:**
```
Request → Single Python Process
              ↓
         Single Thread
              ↓
         Your Handler Code
```

**No benefit from threading:**
- Python GIL prevents true parallel execution
- Only one thread executes Python bytecode at a time
- Threading adds overhead without parallelism benefit
- JavaScript is inherently single-threaded

### Threading Primitives Don't Work

**Threading locks are meaningless:**
```python
# ❌ WRONG - Lock serves no purpose
import threading

lock = threading.Lock()

def lambda_handler(event, context):
    with lock:  # Only one thread anyway!
        # Process data
        result = process_data(event)
    return result
```

**Why it's wrong:**
- Lock protects against concurrent access
- But Lambda only has one thread per execution
- Lock adds overhead, provides no protection
- False sense of thread safety

**Thread pools don't help:**
```python
# ❌ WRONG - No parallelism in Lambda
from concurrent.futures import ThreadPoolExecutor

executor = ThreadPoolExecutor(max_workers=10)

def lambda_handler(event, context):
    # Creates 10 threads, but only 1 executes at a time!
    futures = [executor.submit(process, item) for item in items]
    results = [f.result() for f in futures]
    return results
```

**Why it's wrong:**
- Creates 10 threads
- GIL ensures only one executes Python code
- Actually slower due to thread switching overhead
- Memory overhead from thread stack allocation

---

## CONSEQUENCES

### Positive

**Using async/await instead:**
```python
# ✅ CORRECT - Async I/O for concurrency
import asyncio
import aiohttp

async def lambda_handler(event, context):
    async with aiohttp.ClientSession() as session:
        # Concurrent I/O operations (not threading)
        tasks = [fetch_data(session, url) for url in urls]
        results = await asyncio.gather(*tasks)
    return results

async def fetch_data(session, url):
    async with session.get(url) as response:
        return await response.json()
```

**Benefits:**
- True concurrency for I/O-bound operations
- No GIL contention
- Lower memory overhead
- Faster execution

**Leveraging Lambda's concurrency model:**
```python
# ✅ CORRECT - Use Lambda concurrency, not threading
import boto3

lambda_client = boto3.client('lambda')

def lambda_handler(event, context):
    # Fan out to multiple Lambda invocations
    for item in event['items']:
        lambda_client.invoke(
            FunctionName='ProcessItem',
            InvocationType='Event',  # Async
            Payload=json.dumps(item)
        )
    
    return {'status': 'dispatched'}
```

**Benefits:**
- True parallelism (separate Lambda invocations)
- Each invocation has full resources
- Automatic scaling
- No GIL limitations

### Negative

**Cannot use threading for parallelism:**
- CPU-bound work stays single-threaded
- Must use alternative approaches
- More complex patterns (async, Lambda fan-out)

**Learning curve:**
- Developers coming from threaded environments
- Need to learn async/await patterns
- Need to understand Lambda concurrency model

---

## ALTERNATIVES CONSIDERED

### Alternative 1: Use Threading Anyway

**Rejected because:**
- Provides no actual parallelism
- Adds overhead
- Creates false sense of thread safety
- Misleading to developers

### Alternative 2: Use Multiprocessing

**Considered but limited:**
```python
# ⚠️ POSSIBLE but usually not worth it
from multiprocessing import Pool

def lambda_handler(event, context):
    with Pool(processes=4) as pool:
        results = pool.map(process_item, items)
    return results
```

**Why limited:**
- Cold start penalty (spawn processes)
- Memory overhead (duplicate Python interpreter)
- Process communication overhead
- Only beneficial for CPU-intensive work
- Lambda concurrency model usually better

**When to use:**
- CPU-bound operations (heavy computation)
- Large dataset processing
- When vertical scaling (more Lambda concurrency) not viable

### Alternative 3: Async/Await (Chosen)

**Why chosen:**
- True concurrency for I/O operations
- No GIL limitations
- Lower overhead than threading
- Natural fit for Lambda use cases (API calls, DB queries)

---

## IMPLEMENTATION

### Pattern 1: Async HTTP Requests

```python
import asyncio
import aiohttp
import json

async def fetch_api(session, url):
    async with session.get(url) as response:
        return await response.json()

async def lambda_handler(event, context):
    urls = event['urls']
    
    async with aiohttp.ClientSession() as session:
        # Concurrent requests
        tasks = [fetch_api(session, url) for url in urls]
        results = await asyncio.gather(*tasks, return_exceptions=True)
    
    # Handle errors
    successful = [r for r in results if not isinstance(r, Exception)]
    failed = [str(r) for r in results if isinstance(r, Exception)]
    
    return {
        'successful': len(successful),
        'failed': len(failed),
        'results': successful
    }
```

### Pattern 2: Lambda Fan-Out

```python
import boto3
import json

lambda_client = boto3.client('lambda')

def lambda_handler(event, context):
    items = event['items']
    
    # Fan out to multiple concurrent Lambda invocations
    for item in items:
        lambda_client.invoke(
            FunctionName='ProcessItemFunction',
            InvocationType='Event',  # Async, non-blocking
            Payload=json.dumps(item)
        )
    
    return {
        'dispatched': len(items),
        'status': 'processing'
    }

# ProcessItemFunction handler
def process_item_handler(event, context):
    # Each item processed in separate Lambda invocation
    # True parallelism - separate containers
    result = heavy_processing(event)
    
    # Store result
    save_to_dynamodb(result)
    
    return {'status': 'completed'}
```

### Pattern 3: Step Functions for Complex Workflows

```python
import boto3
import json

sfn_client = boto3.client('stepfunctions')

def lambda_handler(event, context):
    # Start Step Functions execution
    execution = sfn_client.start_execution(
        stateMachineArn='arn:aws:states:...',
        input=json.dumps(event)
    )
    
    return {
        'executionArn': execution['executionArn'],
        'status': 'started'
    }

# Step Functions handles parallel execution
# No threading needed in Lambda code
```

---

## EXCEPTIONS

### When Threading Might Be Acceptable

**1. Third-Party Libraries:**
```python
# Library uses threading internally
# You don't control this
import some_library

def lambda_handler(event, context):
    # Library handles threading (if at all)
    result = some_library.process(event)
    return result
```

**Acceptable because:**
- You don't control library implementation
- Library authors know limitations
- Usually minimal threading if any

**2. Backward Compatibility:**
```python
# Existing code uses threading
# Too costly to rewrite immediately

def lambda_handler(event, context):
    # Legacy code with threading
    # Document as tech debt
    # Plan migration to async
    result = legacy_threaded_function(event)
    return result
```

**Acceptable temporarily because:**
- Migration cost/risk too high
- Document as technical debt
- Plan eventual migration

**But:**
- Don't add new threading code
- Migrate when feasible
- Document limitations

---

## RELATED PATTERNS

**Python Architectures:**
- SUGA: Single-threaded gateway pattern
- LMMS: Lazy imports avoid thread initialization overhead

**Anti-Patterns:**
- AWS-Lambda-AP-01: Threading Primitives
- AP-08: Threading locks (generic anti-pattern)

**Lessons:**
- AWS-Lambda-LESS-05: Async I/O Performance

---

## VERIFICATION

### Code Review Checklist

```
❌ Reject if code contains:
- threading.Lock()
- threading.Semaphore()
- threading.Thread()
- concurrent.futures.ThreadPoolExecutor
- threading.Event()
- threading.Condition()

✅ Accept alternatives:
- asyncio.gather()
- aiohttp
- Lambda invocations for fan-out
- multiprocessing (if justified for CPU-bound work)
```

### Static Analysis

```python
# check_threading.py
import ast
import sys

class ThreadingChecker(ast.NodeVisitor):
    def __init__(self):
        self.violations = []
    
    def visit_Import(self, node):
        for alias in node.names:
            if alias.name == 'threading':
                self.violations.append(
                    f"Line {node.lineno}: Imports 'threading' module"
                )
    
    def visit_ImportFrom(self, node):
        if node.module == 'threading':
            self.violations.append(
                f"Line {node.lineno}: Imports from 'threading' module"
            )
        elif node.module == 'concurrent.futures':
            for alias in node.names:
                if alias.name == 'ThreadPoolExecutor':
                    self.violations.append(
                        f"Line {node.lineno}: Imports ThreadPoolExecutor"
                    )

# Usage in CI/CD
with open('lambda_function.py') as f:
    tree = ast.parse(f.read())
    checker = ThreadingChecker()
    checker.visit(tree)
    
    if checker.violations:
        print("Threading violations found:")
        for v in checker.violations:
            print(f"  {v}")
        sys.exit(1)
```

---

## KEY TAKEAWAYS

**Decision:**
- Never use threading primitives in Lambda
- Single-threaded execution makes them useless
- Adds overhead without benefit

**Alternatives:**
- Use async/await for I/O concurrency
- Use Lambda fan-out for true parallelism
- Use Step Functions for complex workflows
- Use multiprocessing only if CPU-bound and justified

**Exceptions:**
- Third-party libraries (not your code)
- Backward compatibility (temporary, documented)

**Verification:**
- Code review checks
- Static analysis tools
- CI/CD validation

---

## RELATED

**Core:**
- AWS-Lambda-Core-Concepts.md
- AWS-Lambda-Execution-Model.md

**Decisions:**
- AWS-Lambda-DEC-04: Stateless Design

**Anti-Patterns:**
- AWS-Lambda-AP-01: Threading Primitives

**Lessons:**
- AWS-Lambda-LESS-05: Async I/O Performance

---

**END OF FILE**

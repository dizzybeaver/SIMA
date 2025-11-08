# AWS-Lambda-Index.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Master index for AWS Lambda platform knowledge  
**Category:** Platform - AWS Lambda  
**Type:** Index

---

## OVERVIEW

This index organizes all AWS Lambda platform-specific knowledge. Lambda is a serverless compute service with unique constraints and optimization patterns.

**Directory:** `/sima/platforms/aws/lambda/`

---

## QUICK NAVIGATION

**Core Concepts:**
- [Core Concepts](#core-concepts) - Fundamentals and execution model
- [Decisions](#decisions) - Platform-specific design decisions
- [Lessons](#lessons) - Performance and optimization lessons
- [Anti-Patterns](#anti-patterns) - Common mistakes to avoid

**Key Topics:**
- [Cold Starts](#cold-starts) - Optimization strategies
- [Memory Management](#memory-management) - Right-sizing and constraints
- [Concurrency](#concurrency) - Scaling and limitations
- [Error Handling](#error-handling) - Retry and failure patterns

---

## CORE CONCEPTS

### AWS-Lambda-Core-Concepts.md
**Purpose:** Lambda fundamentals  
**Topics:**
- Execution model (sync, async, stream-based)
- Resource limits (memory, timeout, payload)
- Cold start vs warm start
- Concurrency model
- Single-threaded execution
- Stateless design
- Cost model

**Key Takeaways:**
- Lambda is event-driven, serverless compute
- Memory: 128 MB - 10 GB (affects CPU allocation)
- Timeout: 1 second - 15 minutes
- Single-threaded execution (no threading benefit)
- Automatic scaling with concurrency limits

### AWS-Lambda-Runtime-Environment.md
**Purpose:** Runtime environment and lifecycle  
**Topics:**
- Environment lifecycle (INIT, INVOKE, SHUTDOWN)
- Supported runtimes (Python, Node.js, Java, Go, etc.)
- Execution context and reuse
- Memory and CPU allocation
- /tmp directory (512 MB - 10 GB)
- Environment variables
- Lambda context object
- Extensions

**Key Takeaways:**
- Context may be reused (warm starts)
- Module-level code runs once (cold start only)
- /tmp persists in warm starts
- Memory determines CPU allocation
- 1769 MB = 1 full vCPU

### AWS-Lambda-Execution-Model.md
**Purpose:** Invocation patterns and scaling  
**Topics:**
- Invocation types (sync, async, stream)
- Event source mapping
- Concurrency and scaling
- Reserved concurrency
- Provisioned concurrency
- Throttling and errors
- Destinations (on-success, on-failure)

**Key Takeaways:**
- Three invocation types with different retry behavior
- Automatic scaling with burst limits
- Provisioned concurrency eliminates cold starts
- Destinations enable event-driven workflows

---

## DECISIONS

### AWS-Lambda-DEC-01: Single-Threaded Execution
**Decision:** Never use threading primitives in Lambda  
**Rationale:** Single-threaded environment, Python GIL  
**Alternatives:** Async/await, Lambda fan-out, Step Functions  
**Impact:** Prevents ineffective threading overhead

### AWS-Lambda-DEC-02: Memory Constraints
**Decision:** Design for memory constraints (128 MB - 10 GB)  
**Rationale:** Memory affects both cost and performance (CPU)  
**Strategy:** Profile usage, right-size with headroom  
**Sweet Spot:** Often 1769-2048 MB (full vCPU)

### AWS-Lambda-DEC-03: Timeout Limits
**Decision:** Functions must complete within 15 minute timeout  
**Rationale:** Lambda enforces maximum execution time  
**Strategy:** Break long tasks into smaller chunks  
**Patterns:** Continuation tokens, Step Functions

### AWS-Lambda-DEC-04: Stateless Design
**Decision:** Lambda functions must be stateless  
**Rationale:** No persistent local state between invocations  
**Storage:** Use DynamoDB, S3, RDS for persistence  
**Exception:** Can leverage context reuse for optimization

### AWS-Lambda-DEC-05: Cost Optimization
**Decision:** Optimize for cost-performance balance  
**Rationale:** Charged for memory × duration  
**Strategy:** Right-size memory, minimize duration  
**Monitoring:** CloudWatch metrics, cost analysis

---

## LESSONS

### AWS-Lambda-LESS-01: Cold Start Impact
**Lesson:** Cold starts add 200-5000ms+ latency  
**Discovery:** Heavy imports cause 71% of cold start time  
**Solution:** Lazy loading, separate functions, provisioned concurrency  
**Impact:** Reduced P99 from 5,100ms to 1,100ms (78% improvement)

### AWS-Lambda-LESS-02: Memory-Performance Trade-off
**Lesson:** Higher memory = faster execution = potentially lower cost  
**Discovery:** 1769 MB optimal for many workloads  
**Pattern:** CPU-intensive benefits from more memory  
**Example:** 3008 MB: 6s execution, lower total cost than 1024 MB: 15s

### AWS-Lambda-LESS-03: Timeout Management
**Lesson:** Use context.get_remaining_time_in_millis() for graceful timeouts  
**Pattern:** Check remaining time periodically  
**Strategy:** Return continuation token when approaching timeout  
**Benefit:** Avoid losing partial work

### AWS-Lambda-LESS-04: Cost Monitoring
**Lesson:** Monitor cost per invocation, not just total cost  
**Discovery:** Unoptimized functions can be 10x more expensive  
**Metrics:** Cost per million invocations  
**Optimization:** Right-size memory, reduce duration

### AWS-Lambda-LESS-05: Async I/O Performance
**Lesson:** Async/await provides true I/O concurrency  
**Pattern:** Use aiohttp for concurrent HTTP requests  
**Impact:** 6-10x faster than sequential I/O  
**Example:** 10 API calls: 2.0s sequential vs 0.3s async

---

## ANTI-PATTERNS

### AWS-Lambda-AP-01: Threading Primitives
**Anti-Pattern:** Using threading locks, Thread objects, ThreadPoolExecutor  
**Why Wrong:** Single-threaded environment, no parallelism benefit  
**Impact:** Adds overhead, slower execution  
**Alternative:** Async/await, Lambda fan-out

### AWS-Lambda-AP-02: Stateful Operations
**Anti-Pattern:** Relying on in-memory state between invocations  
**Why Wrong:** Context may not be reused  
**Impact:** Data loss, inconsistent behavior  
**Alternative:** External storage (DynamoDB, S3)

### AWS-Lambda-AP-03: Heavy Dependencies
**Anti-Pattern:** Including large libraries not always needed  
**Why Wrong:** Increases cold start time significantly  
**Impact:** 200-2000ms+ cold start overhead  
**Alternative:** Lazy loading, separate functions

### AWS-Lambda-AP-04: Ignoring Timeout
**Anti-Pattern:** Not checking remaining time  
**Why Wrong:** Loses partial work on timeout  
**Impact:** Wasted execution cost, failed operations  
**Alternative:** context.get_remaining_time_in_millis()

### AWS-Lambda-AP-05: Over-Provisioning Memory
**Anti-Pattern:** Allocating more memory than needed  
**Why Wrong:** Unnecessary cost  
**Impact:** 2-10x higher cost  
**Alternative:** Profile actual usage, right-size

---

## TOPICS

### Cold Starts

**Related Files:**
- Core: AWS-Lambda-Core-Concepts.md (Cold start phases)
- Core: AWS-Lambda-Runtime-Environment.md (Optimization strategies)
- Decision: AWS-Lambda-DEC-02 (Memory affects cold start)
- Lesson: AWS-Lambda-LESS-01 (Cold Start Impact)
- Anti-Pattern: AWS-Lambda-AP-03 (Heavy Dependencies)

**Key Strategies:**
1. Minimize dependencies
2. Use lazy loading
3. Right-size memory (more = faster initialization)
4. Separate functions by use case
5. Consider provisioned concurrency

**Typical Cold Start Times:**
- Minimal dependencies: 200-500ms
- Moderate dependencies: 500-1500ms
- Heavy dependencies (ML, Pandas): 2000-5000ms

---

### Memory Management

**Related Files:**
- Core: AWS-Lambda-Core-Concepts.md (Resource limits)
- Core: AWS-Lambda-Runtime-Environment.md (Memory allocation)
- Decision: AWS-Lambda-DEC-02 (Memory Constraints)
- Lesson: AWS-Lambda-LESS-02 (Memory-Performance Trade-off)
- Anti-Pattern: AWS-Lambda-AP-05 (Over-Provisioning)

**Key Strategies:**
1. Profile actual usage (CloudWatch max memory)
2. Add 20-100% headroom
3. Test performance at different memory levels
4. Balance cost vs performance

**Memory → CPU Mapping:**
- 128 MB → 0.08 vCPU
- 512 MB → 0.33 vCPU
- 1024 MB → 0.67 vCPU
- 1769 MB → 1.00 vCPU ✅ Recommended minimum
- 3008 MB → 1.70 vCPU
- 10240 MB → 6.00 vCPU

---

### Concurrency

**Related Files:**
- Core: AWS-Lambda-Core-Concepts.md (Concurrency model)
- Core: AWS-Lambda-Execution-Model.md (Scaling behavior)
- Decision: AWS-Lambda-DEC-01 (Single-Threaded)

**Key Concepts:**
- One execution per concurrent request
- Account limit: 1,000 default (can increase)
- Burst limit: 3,000 initial, then +500/minute
- Reserved concurrency: Guarantee capacity
- Provisioned concurrency: Pre-warmed containers

**Scaling Patterns:**
- Synchronous: Immediate scaling
- Asynchronous: Queued, gradual scaling
- Stream-based: Per shard, configurable parallelization

---

### Error Handling

**Related Files:**
- Core: AWS-Lambda-Execution-Model.md (Error handling)
- Lesson: AWS-Lambda-LESS-03 (Timeout Management)

**Patterns by Invocation Type:**

**Synchronous:**
- No automatic retries
- Client implements retry logic
- Exponential backoff recommended

**Asynchronous:**
- 2 automatic retries
- Dead Letter Queue (DLQ) for failures
- Destinations for success/failure routing

**Stream-Based:**
- Retry until success or max retries
- Partial batch failure support
- On-failure destination

---

## RELATED ARCHITECTURE PATTERNS

### Python Architectures

**SUGA (Single Universal Gateway Architecture):**
- Three-layer pattern works in Lambda
- Gateway → Interface → Core
- All layers must fit in memory limit

**LMMS (Lazy Module Management System):**
- Especially important in Lambda
- Minimizes cold start time
- Function-level imports for rarely-used modules

**DD-1 (Dictionary Dispatch):**
- O(1) routing for interface operations
- Efficient in Lambda's single-threaded environment

**DD-2 (Dependency Disciplines):**
- Prevents circular imports
- Reduces cold start complexity

---

## QUICK REFERENCE

### Common Constraints

```
Memory:           128 MB - 10 GB
Timeout:          1 second - 15 minutes
Payload (sync):   6 MB
Payload (async):  256 KB
/tmp:             512 MB - 10 GB
Concurrency:      1,000 (default, can increase)
Deployment:       50 MB (zipped), 250 MB (unzipped)
```

### Performance Targets

```
Cold Start:       < 3 seconds (typical)
Warm Start:       < 100ms
P99 Latency:      < 1 second (user-facing)
Cost:             < $0.20 per million invocations
```

### Optimization Checklist

```
[√] Minimize dependencies
[√] Use lazy loading for heavy imports
[√] Right-size memory (profile actual usage)
[√] Implement timeout handling
[√] Use async/await for I/O
[√] Monitor cold start frequency
[√] Separate functions by use case
[√] Consider provisioned concurrency for critical APIs
```

---

## CROSS-REFERENCES

**Generic Patterns:** /sima/entries/  
**Python Architectures:** /sima/languages/python/architectures/  
**LEE Project:** /sima/projects/lee/ (Lambda implementation)  
**AWS Platform:** /sima/platforms/aws/ (Other AWS services)

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial AWS Lambda platform index
- Core concepts, decisions, lessons documented
- Anti-patterns identified
- Topics organized
- Cross-references to Python architectures

---

**END OF FILE**

**Files Indexed:** 10+  
**Topics Covered:** Cold starts, memory, concurrency, error handling  
**Next:** LEE project-specific Lambda implementation patterns

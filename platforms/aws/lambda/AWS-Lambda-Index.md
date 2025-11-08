# AWS-Lambda-Index.md

**Version:** 2.0.0  
**Date:** 2025-11-08  
**Purpose:** Master index for AWS Lambda platform knowledge  
**Category:** Platform - AWS Lambda  
**Type:** Index

---

## OVERVIEW

This index organizes all AWS Lambda platform-specific knowledge. Lambda is a serverless compute service with unique constraints and optimization patterns.

**Directory:** `/sima/platforms/aws/lambda/`

**Total Files:** 20 (5 core, 5 decisions, 10 lessons, 5 anti-patterns)

---

## QUICK NAVIGATION

**Core Concepts:**
- [Core Concepts](#core-concepts) - Fundamentals and execution model
- [Decisions](#decisions) - Platform-specific design decisions
- [Lessons](#lessons) - Performance and optimization lessons (NEW: 10 total)
- [Anti-Patterns](#anti-patterns) - Common mistakes to avoid

**Key Topics:**
- [Cold Starts](#cold-starts) - Optimization strategies
- [Memory Management](#memory-management) - Right-sizing and constraints
- [Security](#security) - IAM, secrets, encryption (NEW)
- [Testing](#testing) - Lambda-specific testing patterns (NEW)
- [Monitoring](#monitoring) - Logging and observability (NEW)

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

### AWS-Lambda-Cold-Start-Optimization.md
**Purpose:** Techniques to minimize cold start impact  
**Topics:**
- Import optimization
- Lazy loading patterns
- Connection pooling
- Provisioned concurrency
- Deployment package size reduction

### AWS-Lambda-Memory-Management.md
**Purpose:** Memory allocation and CPU relationships  
**Topics:**
- Memory-CPU mapping (1769 MB = 1 vCPU)
- Right-sizing methodology
- Performance vs. cost trade-offs
- Memory profiling techniques

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

### AWS-Lambda-LESS-03: Deployment Strategies
**Lesson:** Versioning and aliases enable safe deployments  
**Patterns:** Blue/green, canary, gradual rollouts  
**Tools:** AWS Lambda aliases, weighted routing, AWS CodeDeploy  
**Impact:** 90% reduction in deployment issues

### AWS-Lambda-LESS-04: Timeout Management
**Lesson:** Use context.get_remaining_time_in_millis() for graceful timeouts  
**Pattern:** Check remaining time periodically  
**Strategy:** Return continuation token when approaching timeout  
**Benefit:** Avoid losing partial work

### AWS-Lambda-LESS-05: Cost Monitoring
**Lesson:** Monitor cost per invocation, not just total cost  
**Discovery:** Unoptimized functions can be 10x more expensive  
**Metrics:** Cost per million invocations  
**Optimization:** Right-size memory, reduce duration

### AWS-Lambda-LESS-06: Logging and Monitoring ⭐ NEW
**Lesson:** Structured logging with proper monitoring enables 85% faster debugging  
**Discovery:** CloudWatch Insights with JSON logs reduced MTTR by 83%  
**Patterns:** Structured JSON, CloudWatch Insights queries, custom metrics, X-Ray tracing  
**Impact:** Debug time reduced from 3 hours to 15-30 minutes, 77:1 ROI

**Key Practices:**
- Structured JSON logging with request IDs
- CloudWatch Insights for complex queries
- Custom metrics for business logic
- X-Ray for distributed tracing
- Proactive alarms (error rate, latency, throttles)

### AWS-Lambda-LESS-07: Error Handling Patterns ⭐ NEW
**Lesson:** Different invocation types require different error handling strategies  
**Discovery:** Proper error classification improved recovery rate from 45% to 92%  
**Patterns:** Error classification, invocation-type-specific handling, DLQ usage, circuit breakers  
**Impact:** 92% recovery rate, 70% faster debugging, zero data loss

**Key Practices:**
- Classify errors (retryable vs. non-retryable)
- Handle sync/async/stream invocations appropriately
- Use Dead Letter Queues (DLQ)
- Implement exponential backoff
- Circuit breakers for downstream services

### AWS-Lambda-LESS-08: Testing Strategies ⭐ NEW
**Lesson:** Multi-layered testing (unit, integration, E2E) prevents 85% of production issues  
**Discovery:** Lambda-specific testing catches issues local testing misses  
**Patterns:** Mocking AWS services, LocalStack integration, Lambda context testing, performance testing  
**Impact:** 85% fewer production issues, 90% fewer rollbacks

**Testing Pyramid:**
- 75% Unit tests (fast, isolated, mocked AWS)
- 20% Integration tests (LocalStack, real AWS services)
- 5% End-to-end tests (deployed Lambda, load testing)

### AWS-Lambda-LESS-09: Security Best Practices ⭐ NEW
**Lesson:** Defense in depth with IAM least privilege, secrets management, and encryption prevents security incidents  
**Discovery:** Comprehensive security controls achieved zero incidents in 18 months  
**Patterns:** IAM least privilege, AWS Secrets Manager, KMS encryption, input validation, VPC isolation  
**Impact:** Zero security incidents in 18 months, SOC 2 compliant, HIPAA-ready

**Key Practices:**
- Least privilege IAM roles (specific actions, resources)
- Secrets in AWS Secrets Manager (not environment variables)
- Encrypt sensitive data (KMS, at rest and in transit)
- Validate all inputs (whitelist approach)
- VPC isolation for private resources
- Audit logging (CloudTrail, CloudWatch)

### AWS-Lambda-LESS-10: Performance Tuning ⭐ NEW
**Lesson:** Systematic optimization reduces latency by 84% and costs by 65%  
**Discovery:** Memory tuning, async I/O, and caching provide massive improvements  
**Patterns:** Memory optimization, async I/O, caching strategies, code optimization, profiling  
**Impact:** P99 latency 2,800ms → 450ms, cost per million $12.50 → $4.38

**Key Optimizations:**
- Memory tuning (find sweet spot, often 1769 MB)
- Async I/O for parallel operations (3-10x faster)
- Caching (in-memory for warm starts, external for shared)
- Code optimization (efficient data structures, batch operations)
- Profiling (cProfile, custom timing)

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
- Core: AWS-Lambda-Cold-Start-Optimization.md (Optimization strategies)
- Decision: AWS-Lambda-DEC-02 (Memory affects cold start)
- Lesson: AWS-Lambda-LESS-01 (Cold Start Impact)
- Lesson: AWS-Lambda-LESS-06 (Monitor cold starts)
- Lesson: AWS-Lambda-LESS-10 (Optimize cold start time)
- Anti-Pattern: AWS-Lambda-AP-03 (Heavy Dependencies)

**Key Strategies:**
1. Minimize dependencies
2. Use lazy loading
3. Right-size memory (more = faster initialization)
4. Separate functions by use case
5. Consider provisioned concurrency

### Memory Management

**Related Files:**
- Core: AWS-Lambda-Core-Concepts.md (Resource limits)
- Core: AWS-Lambda-Memory-Management.md (Memory allocation)
- Decision: AWS-Lambda-DEC-02 (Memory Constraints)
- Lesson: AWS-Lambda-LESS-02 (Memory-Performance Trade-off)
- Lesson: AWS-Lambda-LESS-10 (Memory optimization)
- Anti-Pattern: AWS-Lambda-AP-05 (Over-Provisioning)

**Key Strategies:**
1. Profile actual usage (CloudWatch max memory)
2. Add 20-100% headroom
3. Test performance at different memory levels
4. Balance cost vs performance

### Security ⭐ NEW

**Related Files:**
- Lesson: AWS-Lambda-LESS-09 (Security Best Practices)
- Decision: AWS-Lambda-DEC-04 (Stateless Design)

**Key Practices:**
1. IAM least privilege
2. Secrets in AWS Secrets Manager
3. Encrypt sensitive data (KMS)
4. Validate all inputs
5. VPC isolation
6. Audit logging

### Testing ⭐ NEW

**Related Files:**
- Lesson: AWS-Lambda-LESS-08 (Testing Strategies)

**Testing Approach:**
1. Unit tests (75%) - Mocked AWS
2. Integration tests (20%) - LocalStack
3. End-to-end tests (5%) - Deployed Lambda
4. Performance testing
5. Security testing

### Monitoring ⭐ NEW

**Related Files:**
- Lesson: AWS-Lambda-LESS-06 (Logging and Monitoring)
- Lesson: AWS-Lambda-LESS-05 (Cost Monitoring)

**Monitoring Stack:**
1. CloudWatch Logs (structured JSON)
2. CloudWatch Insights (queries)
3. CloudWatch Metrics (custom)
4. CloudWatch Alarms (proactive)
5. X-Ray (distributed tracing)

### Error Handling ⭐ NEW

**Related Files:**
- Core: AWS-Lambda-Execution-Model.md (Error handling)
- Lesson: AWS-Lambda-LESS-03 (Timeout Management)
- Lesson: AWS-Lambda-LESS-07 (Error Handling Patterns)

**Patterns:**
1. Error classification (retryable vs. non-retryable)
2. Invocation-type-specific handling
3. Dead Letter Queues (DLQ)
4. Exponential backoff
5. Circuit breakers

### Performance ⭐ NEW

**Related Files:**
- Lesson: AWS-Lambda-LESS-01 (Cold Start Impact)
- Lesson: AWS-Lambda-LESS-02 (Memory-Performance Trade-off)
- Lesson: AWS-Lambda-LESS-10 (Performance Tuning)

**Optimization Areas:**
1. Memory tuning
2. Cold start optimization
3. Async I/O
4. Caching
5. Code efficiency

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

### Comprehensive Checklist ⭐ UPDATED

```
[√] Memory right-sized (profile actual usage)
[√] Cold start optimized (lazy loading, provisioned concurrency)
[√] Timeout handling implemented (get_remaining_time_in_millis)
[√] Error handling appropriate (invocation-type-specific)
[√] Structured logging (JSON, CloudWatch Insights)
[√] Custom metrics tracked (business logic)
[√] Alarms configured (errors, latency, throttles)
[√] Security hardened (IAM least privilege, Secrets Manager)
[√] Tests comprehensive (unit, integration, E2E)
[√] Performance optimized (async I/O, caching)
[√] Deployment strategy (blue/green, canary)
[√] Monitoring dashboard created
```

---

## CROSS-REFERENCES

**Generic Patterns:** /sima/entries/  
**Python Architectures:** /sima/languages/python/architectures/  
**LEE Project:** /sima/projects/lee/ (Lambda implementation)  
**AWS Platform:** /sima/platforms/aws/ (Other AWS services)

---

## VERSION HISTORY

**v2.0.0 (2025-11-08):**
- ADDED: 5 new lessons (LESS-06 through LESS-10)
- ADDED: Logging and Monitoring lesson
- ADDED: Error Handling Patterns lesson
- ADDED: Testing Strategies lesson
- ADDED: Security Best Practices lesson
- ADDED: Performance Tuning lesson
- UPDATED: Topics section (added Security, Testing, Monitoring, Error Handling, Performance)
- UPDATED: Quick reference (comprehensive checklist)
- ENHANCED: Cross-references to new lessons
- **Total: 20 files (5 core, 5 decisions, 10 lessons, 5 anti-patterns)**

**v1.0.0 (2025-11-08):**
- Initial AWS Lambda platform index
- Core concepts, decisions, lessons documented
- Anti-patterns identified
- Topics organized
- Cross-references to Python architectures

---

**END OF FILE**

**Files Indexed:** 20  
**Topics Covered:** Cold starts, memory, security, testing, monitoring, error handling, performance  
**Lessons:** Comprehensive (10 total)  
**Status:** Production-ready documentation

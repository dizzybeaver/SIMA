# AWS-Lambda-Index.md

**Version:** 2.3.0  
**Date:** 2025-11-10  
**Purpose:** Master index for AWS Lambda platform knowledge  
**Category:** Platform - AWS Lambda  
**Type:** Index

---

## OVERVIEW

This index organizes all AWS Lambda platform-specific knowledge. Lambda is a serverless compute service with unique constraints and optimization patterns.

**Directory:** `/sima/platforms/aws/lambda/`

**Total Files:** 34 (5 core + 7 decisions + 13 lessons + 6 anti-patterns + 1 index + 2 decision trees)

---

## QUICK NAVIGATION

**Core Concepts:**
- [Core Concepts](#core-concepts) - Fundamentals and execution model
- [Decisions](#decisions) - Platform-specific design decisions (7 total âœ…)
- [Lessons](#lessons) - Performance and optimization lessons (13 total âœ…)
- [Anti-Patterns](#anti-patterns) - Common mistakes to avoid (6 total âœ…)
- [Decision Trees](#decision-trees) - Choice frameworks (2 total âœ…)
- [Frameworks](#frameworks) - Optional advanced patterns (3 total âœ…)

**Key Topics:**
- [Cold Starts](#cold-starts) - Optimization strategies
- [Memory Management](#memory-management) - Right-sizing and constraints
- [Container Reuse](#container-reuse) - Performance optimization ⭐ NEW
- [Async Processing](#async-processing) - Background and long-running tasks ⭐ NEW
- [Predictive Planning](#predictive-planning) - ML-based optimization (optional) ⭐ NEW
- [VPC Configuration](#vpc-configuration) - VPC vs non-VPC decision
- [Environment Variables](#environment-variables) - Configuration management
- [API Gateway Integration](#api-gateway-integration) - Integration patterns
- [Lambda Layers](#lambda-layers) - Code reuse and deployment
- [Security](#security) - IAM, secrets, encryption
- [Testing](#testing) - Lambda-specific testing patterns
- [Monitoring](#monitoring) - Logging and observability

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
**Target:** 30-50% cost reduction from baseline

### AWS-Lambda-DEC-06: VPC Configuration
**Decision:** Use VPC only when accessing private resources (RDS, ElastiCache)  
**Rationale:** VPC adds 1-2s cold start overhead and NAT Gateway costs  
**Strategy:** No VPC for AWS services or public internet, VPC only for private resources  
**Impact:** 66% faster cold starts for non-VPC functions  
**Trade-offs:** Private resource access vs performance and cost

### AWS-Lambda-DEC-07: Provisioned Concurrency vs Cold Start Optimization
**Decision:** Choose based on latency SLA and cost tolerance  
**Rationale:** Provisioned concurrency costs $35-350/month, cold start optimization saves 91%  
**Strategy:** Provisioned for <500ms SLA, optimization for cost-sensitive  
**Hybrid:** 20% provisioned (critical) + 80% optimized (non-critical)  
**Impact:** Decision framework based on requirements

---

## DECISION TREES

### DT-13: Architecture Pattern Selection
**Purpose:** Choose between SUGA, microservices, monolith  
**Factors:** Team size, complexity, scale  
**Status:** Production

### DT-14: Async Processing Pattern Selection ⭐ NEW
**Purpose:** Choose async pattern based on duration and requirements  
**Factors:** Task duration, response needs, error handling, scale  
**Patterns:** Synchronous, asynchronous, message queue, Step Functions, fan-out  
**Status:** REFERENCE (Future extensibility)

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

### AWS-Lambda-LESS-06: Logging and Monitoring
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

### AWS-Lambda-LESS-07: Error Handling Patterns
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

### AWS-Lambda-LESS-08: Testing Strategies
**Lesson:** Multi-layered testing (unit, integration, E2E) prevents 85% of production issues  
**Discovery:** Lambda-specific testing catches issues local testing misses  
**Patterns:** Mocking AWS services, LocalStack integration, Lambda context testing, performance testing  
**Impact:** 85% fewer production issues, 90% fewer rollbacks

**Testing Pyramid:**
- 75% Unit tests (fast, isolated, mocked AWS)
- 20% Integration tests (LocalStack, real AWS services)
- 5% End-to-end tests (deployed Lambda, load testing)

### AWS-Lambda-LESS-09: Security Best Practices
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

### AWS-Lambda-LESS-10: Performance Tuning
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

### AWS-Lambda-LESS-11: API Gateway Integration Patterns
**Lesson:** HTTP adapter pattern separates API Gateway concerns from business logic, enabling 85% faster development  
**Discovery:** Mixed HTTP/business logic created testing difficulties and inconsistent responses  
**Patterns:** HTTP adapter layer, proxy integration response format, event parsing, CORS handling  
**Impact:** 85% faster development, 85% fewer bugs, 100% code reuse

**Key Practices:**
- HTTP adapter isolates API Gateway specifics
- Business logic remains pure and testable
- Centralized error handling
- CORS at API Gateway (not Lambda)
- Consistent response formatting

### AWS-Lambda-LESS-12: Environment Variable Management
**Lesson:** Hierarchical config strategy (env vars, SSM, runtime config, feature flags) achieves 100% secret protection  
**Discovery:** Environment variables for everything exposed 75% of secrets and caused deployment issues  
**Patterns:** Non-sensitive in env vars, secrets in SSM, dynamic config in DynamoDB, feature flags  
**Impact:** 100% secret protection, 94% performance improvement, 90% cost reduction

**Configuration Hierarchy:**
1. Environment Variables: Non-sensitive, static config
2. SSM Parameter Store: API keys, passwords, encrypted secrets
3. Runtime Config (DynamoDB): Feature flags, dynamic values
4. Feature Flags: A/B testing, gradual rollouts

### AWS-Lambda-LESS-13: Runtime Selection Impact on Performance
**Lesson:** Runtime choice significantly impacts cold start time and cost  
**Discovery:** Python/Node.js 20-50% faster cold starts vs Java/.NET, Go 60% faster execution  
**Pattern:** Choose lightweight runtimes for serverless  
**Impact:** Runtime compounds with cold start frequency  
**Recommendation:** Python or Node.js for 80% of Lambda workloads

### AWS-Lambda-LESS-14: Step Functions Orchestration for Long-Running Tasks
**Lesson:** Step Functions enables tasks exceeding Lambda 15-minute timeout  
**Discovery:** Workflow orchestration solves previously impossible problems  
**Pattern:** Decompose into subtasks, state machine coordinates, parallel execution  
**Impact:** Enables workloads >15 minutes, N× speedup via parallelism

### AWS-Lambda-LESS-15: Resource Variability Exploitation
**Lesson:** Lambda server performance varies 10-15% within same configuration  
**Discovery:** Keep-alive warm-up prefers faster servers  
**Pattern:** Exploit resource heterogeneity for optimization  
**Impact:** 4-13% cost savings at scale (>1M invocations/month)

### AWS-Lambda-LESS-16: Container Reuse Strategy ⭐ NEW
**Lesson:** Initialize expensive resources outside handler for 20-95% performance gain on warm starts  
**Discovery:** Module-level initialization runs once per container, not per invocation  
**Pattern:** Lazy initialization, connection pooling, /tmp caching  
**Impact:** 240ms → 12ms (95% reduction) for warm invocations  
**Implementation:** LEE project lambda_preload.py (production-tested)

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
**Anti-Pattern:** Not checking remaining execution time  
**Why Wrong:** Loses partial work on timeout  
**Impact:** Wasted execution cost, failed operations, data loss  
**Alternative:** Monitor `context.get_remaining_time_in_millis()` and return continuation tokens

### AWS-Lambda-AP-05: Over-Provisioning Memory
**Anti-Pattern:** Allocating more memory than needed  
**Why Wrong:** Unnecessary cost with no performance benefit  
**Impact:** 2-10x higher cost, wasted resources  
**Alternative:** Profile actual usage, right-size with appropriate headroom

### AWS-Lambda-AP-06: Not Using Lambda Layers
**Anti-Pattern:** Duplicating shared code and dependencies across all Lambda functions  
**Why Wrong:** Wasted storage, slower deployments, version inconsistencies  
**Impact:** 78% wasted storage, 96% slower deployments, version conflict bugs  
**Alternative:** Extract shared dependencies and utilities into Lambda Layers

---

## FRAMEWORKS

### FW-01: Configuration Tiering Framework
**Purpose:** Manual resource configuration tiers  
**Status:** Production (LEE project)  
**Complexity:** LOW  
**Application:** All scale levels

### FW-02: Performance Profiling Framework
**Purpose:** Systematic performance measurement  
**Status:** Production  
**Complexity:** MEDIUM  
**Application:** Optimization efforts

### FW-03: Predictive Resource Planning Framework ⭐ NEW
**Purpose:** ML-based automatic resource optimization  
**Status:** OPTIONAL (Future enhancement)  
**Complexity:** HIGH  
**Accuracy:** 99.2% memory, 98.7% duration (research)  
**ROI:** Profitable only at >100M invocations/month  
**Implementation:** 160 hours, break-even 16 months at 500M invocations  
**Recommendation:** Manual tuning sufficient for <100M invocations  
**Alternative:** AWS Compute Optimizer (free, simpler)

---

## TOPICS

### Cold Starts

**Related Files:**
- Core: AWS-Lambda-Core-Concepts.md (Cold start phases)
- Core: AWS-Lambda-Cold-Start-Optimization.md (Optimization strategies)
- Decision: AWS-Lambda-DEC-02 (Memory affects cold start)
- Decision: AWS-Lambda-DEC-06 (VPC adds cold start overhead)
- Decision: AWS-Lambda-DEC-07 (Provisioned concurrency vs optimization)
- Lesson: AWS-Lambda-LESS-01 (Cold Start Impact)
- Lesson: AWS-Lambda-LESS-06 (Monitor cold starts)
- Lesson: AWS-Lambda-LESS-10 (Optimize cold start time)
- Lesson: AWS-Lambda-LESS-13 (Runtime selection) ⭐ NEW
- Lesson: AWS-Lambda-LESS-16 (Container reuse) ⭐ NEW
- Anti-Pattern: AWS-Lambda-AP-03 (Heavy Dependencies)

**Key Strategies:**
1. Minimize dependencies
2. Use lazy loading
3. Right-size memory (more = faster initialization)
4. Separate functions by use case
5. Consider provisioned concurrency
6. Avoid VPC when possible (1-2s overhead)
7. Use Lambda Layers for shared code
8. Choose lightweight runtimes (Python, Node.js)
9. Leverage container reuse (module-level init)

### Memory Management

**Related Files:**
- Core: AWS-Lambda-Core-Concepts.md (Resource limits)
- Core: AWS-Lambda-Memory-Management.md (Memory allocation)
- Decision: AWS-Lambda-DEC-02 (Memory Constraints)
- Decision: AWS-Lambda-DEC-05 (Cost Optimization)
- Lesson: AWS-Lambda-LESS-02 (Memory-Performance Trade-off)
- Lesson: AWS-Lambda-LESS-10 (Memory optimization)
- Anti-Pattern: AWS-Lambda-AP-05 (Over-Provisioning)
- Framework: FW-03 (Predictive planning - optional) ⭐ NEW

**Key Strategies:**
1. Profile actual usage (CloudWatch max memory)
2. Add 20-100% headroom
3. Test performance at different memory levels
4. Balance cost vs performance
5. Consider ML-based prediction (>100M invocations only)

### Container Reuse ⭐ NEW

**Related Files:**
- Lesson: AWS-Lambda-LESS-16 (Container Reuse Strategy) ⭐ NEW
- Core: AWS-Lambda-Runtime-Environment.md (Execution context reuse)
- Decision: AWS-Lambda-DEC-04 (Stateless design - reuse is optimization)
- Lesson: AWS-Lambda-LESS-01 (Cold start optimization)

**Key Strategies:**
1. Initialize AWS clients at module level
2. Use lazy initialization for optional resources
3. Cache configuration and secrets (with TTL)
4. Check connection health before reuse
5. Use /tmp for downloaded files (512 MB - 10 GB)
6. Don't store request-specific state at module level
7. Monitor warm start percentage

**Performance Impact:**
- Cold start: No change
- Warm start: 20-95% faster (depends on resources)
- LEE example: 240ms → 12ms (95% improvement)

### Async Processing ⭐ NEW

**Related Files:**
- Decision Tree: DT-14 (Async Processing Pattern Selection) ⭐ NEW
- Lesson: AWS-Lambda-LESS-04 (Timeout management)
- Lesson: AWS-Lambda-LESS-07 (Error handling patterns)
- Lesson: AWS-Lambda-LESS-14 (Step Functions orchestration)
- Decision: AWS-Lambda-DEC-03 (Timeout limits)

**Patterns:**
1. Synchronous invocation (<30s, user-facing)
2. Asynchronous invocation (30s-15min, fire-and-forget)
3. Message queue (reliable processing, rate limiting)
4. Step Functions Express (<5min, high volume)
5. Step Functions Standard (>5min, <1 year, complex workflows)
6. Fan-out (batch processing, parallel execution)

**Decision Factors:**
- Task duration
- Response requirements (immediate vs eventual)
- Error handling needs
- Scale requirements (throughput)

### Predictive Planning ⭐ NEW

**Related Files:**
- Framework: FW-03 (Predictive Resource Planning Framework) ⭐ NEW
- Decision: AWS-Lambda-DEC-05 (Manual cost optimization)
- Lesson: AWS-Lambda-LESS-02 (Memory-performance trade-off)
- Lesson: AWS-Lambda-LESS-05 (Cost monitoring)

**When to Consider:**
- >100M invocations/month
- >50 different functions
- >$1,000/month Lambda spend
- Frequently changing workload patterns

**When to Skip:**
- <10M invocations/month (manual sufficient)
- Stable workloads (set and forget)
- Small teams (<5 engineers)
- Simple architectures (<10 functions)

**Alternatives:**
1. Manual tuning (LEE approach, sufficient for most)
2. AWS Compute Optimizer (free, no ML)
3. Predictive framework (only at very high scale)

### VPC Configuration

**Related Files:**
- Decision: AWS-Lambda-DEC-06 (VPC Configuration)
- Lesson: AWS-Lambda-LESS-01 (Cold start impact)

**Key Strategies:**
1. Use VPC only for private resource access (RDS, ElastiCache)
2. No VPC for AWS services (use IAM)
3. VPC endpoints for AWS services (free for S3/DynamoDB)
4. NAT Gateway only if public internet required ($35/month)
5. Provisioned concurrency for critical VPC functions

### Environment Variables

**Related Files:**
- Lesson: AWS-Lambda-LESS-12 (Environment Variable Management)
- Lesson: AWS-Lambda-LESS-09 (Security best practices)

**Configuration Strategy:**
1. Env vars: Non-sensitive, static configuration
2. SSM Parameter Store: Secrets, API keys (encrypted)
3. DynamoDB: Dynamic config, feature flags (runtime)
4. Feature flags: A/B testing, gradual rollouts

### API Gateway Integration

**Related Files:**
- Lesson: AWS-Lambda-LESS-11 (API Gateway Integration Patterns)
- Lesson: AWS-Lambda-LESS-07 (Error handling)
- AWS-APIGateway-LESS-09 (Proxy integration)

**Integration Patterns:**
1. HTTP adapter layer (separate concerns)
2. Proxy integration response format
3. Event parsing patterns
4. CORS at API Gateway (not Lambda)
5. Consistent error responses

### Lambda Layers

**Related Files:**
- Anti-Pattern: AWS-Lambda-AP-06 (Not Using Lambda Layers)
- Lesson: AWS-Lambda-LESS-01 (Cold start - layers help)

**Layer Strategy:**
1. Shared dependencies in layers
2. Custom utilities in layers
3. Organize by update frequency
4. Version layers properly (pin production)
5. Max 5 layers per function, 250 MB total

### Security

**Related Files:**
- Lesson: AWS-Lambda-LESS-09 (Security Best Practices)
- Lesson: AWS-Lambda-LESS-12 (Environment variable security)
- Decision: AWS-Lambda-DEC-04 (Stateless Design)
- Decision: AWS-Lambda-DEC-06 (VPC isolation)

**Key Practices:**
1. IAM least privilege
2. Secrets in SSM Parameter Store (encrypted)
3. Encrypt sensitive data (KMS)
4. Validate all inputs
5. VPC isolation for private resources
6. Audit logging

### Testing

**Related Files:**
- Lesson: AWS-Lambda-LESS-08 (Testing Strategies)
- Lesson: AWS-Lambda-LESS-11 (HTTP adapter testability)

**Testing Approach:**
1. Unit tests (75%) - Mocked AWS, pure business logic
2. Integration tests (20%) - LocalStack
3. End-to-end tests (5%) - Deployed Lambda
4. Performance testing
5. Security testing

### Monitoring

**Related Files:**
- Lesson: AWS-Lambda-LESS-06 (Logging and Monitoring)
- Lesson: AWS-Lambda-LESS-05 (Cost Monitoring)
- Lesson: AWS-Lambda-LESS-16 (Container reuse monitoring) ⭐ NEW

**Monitoring Stack:**
1. CloudWatch Logs (structured JSON)
2. CloudWatch Insights (queries)
3. CloudWatch Metrics (custom)
4. CloudWatch Alarms (proactive)
5. X-Ray (distributed tracing)

**Custom Metrics:**
- Warm start percentage
- Container reuse rate
- Resource cache hit rate
- Cost per invocation

### Error Handling

**Related Files:**
- Core: AWS-Lambda-Execution-Model.md (Error handling)
- Lesson: AWS-Lambda-LESS-04 (Timeout Management)
- Lesson: AWS-Lambda-LESS-07 (Error Handling Patterns)
- Lesson: AWS-Lambda-LESS-11 (HTTP adapter error handling)
- Decision Tree: DT-14 (Async processing patterns) ⭐ NEW
- Anti-Pattern: AWS-Lambda-AP-04 (Ignoring Timeout)

**Patterns:**
1. Error classification (retryable vs. non-retryable)
2. Invocation-type-specific handling
3. Dead Letter Queues (DLQ)
4. Exponential backoff
5. Circuit breakers

### Performance

**Related Files:**
- Lesson: AWS-Lambda-LESS-01 (Cold Start Impact)
- Lesson: AWS-Lambda-LESS-02 (Memory-Performance Trade-off)
- Lesson: AWS-Lambda-LESS-10 (Performance Tuning)
- Lesson: AWS-Lambda-LESS-11 (API Gateway efficiency)
- Lesson: AWS-Lambda-LESS-12 (Config caching)
- Lesson: AWS-Lambda-LESS-13 (Runtime selection) ⭐ NEW
- Lesson: AWS-Lambda-LESS-16 (Container reuse) ⭐ NEW

**Optimization Areas:**
1. Memory tuning
2. Cold start optimization
3. Async I/O
4. Caching (in-memory, external)
5. Code efficiency
6. VPC avoidance when possible
7. Lambda Layers for shared code
8. Runtime selection (Python, Node.js)
9. Container reuse (module-level init)

### Cost Optimization

**Related Files:**
- Decision: AWS-Lambda-DEC-05 (Cost Optimization)
- Decision: AWS-Lambda-DEC-06 (VPC costs)
- Decision: AWS-Lambda-DEC-07 (Provisioned concurrency costs)
- Lesson: AWS-Lambda-LESS-05 (Cost Monitoring)
- Lesson: AWS-Lambda-LESS-12 (SSM API cost reduction)
- Lesson: AWS-Lambda-LESS-15 (Resource variability) ⭐ NEW
- Lesson: AWS-Lambda-LESS-16 (Container reuse cost savings) ⭐ NEW
- Anti-Pattern: AWS-Lambda-AP-05 (Over-Provisioning)
- Anti-Pattern: AWS-Lambda-AP-06 (Deployment inefficiency)
- Framework: FW-03 (Predictive planning - high scale only) ⭐ NEW

**Strategies:**
1. Right-size memory (profile actual usage)
2. Optimize code efficiency
3. Implement caching (reduce duration)
4. Batch operations
5. Monitor cost per invocation
6. Avoid VPC when possible (NAT Gateway costs)
7. Use Lambda Layers (reduce deployment size)
8. Leverage container reuse (reduce warm start duration)
9. Consider ML optimization (>100M invocations only)

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
Layers:           Max 5, 250 MB total
VPC Cold Start:   +1-2 seconds
```

### Performance Targets

```
Cold Start:       < 3 seconds (typical)
Warm Start:       < 100ms (< 20ms with container reuse)
P99 Latency:      < 1 second (user-facing)
Cost:             < $0.20 per million invocations
Container Reuse:  >80% warm starts (good)
```

### Comprehensive Checklist

```
[√] Memory right-sized (profile actual usage)
[√] Cold start optimized (lazy loading, provisioned concurrency)
[√] Container reuse implemented (module-level init) ⭐ NEW
[√] Runtime selected (Python/Node.js for most cases) ⭐ NEW
[√] VPC only if needed (private resources only)
[√] Timeout handling implemented (get_remaining_time_in_millis)
[√] Async processing pattern chosen (if needed) ⭐ NEW
[√] Error handling appropriate (invocation-type-specific)
[√] Structured logging (JSON, CloudWatch Insights)
[√] Custom metrics tracked (business logic, warm starts) ⭐ NEW
[√] Alarms configured (errors, latency, throttles, cost)
[√] Security hardened (IAM least privilege, SSM for secrets)
[√] Tests comprehensive (unit, integration, E2E)
[√] Performance optimized (async I/O, caching, container reuse) ⭐ NEW
[√] Deployment strategy (blue/green, canary)
[√] Monitoring dashboard created
[√] Cost monitoring active (per invocation tracking)
[√] Lambda Layers for shared code
[√] API Gateway integration clean (adapter pattern)
[√] Environment variables secure (SSM for secrets)
[√] Predictive planning considered (>100M only) ⭐ NEW
```

---

## CROSS-REFERENCES

**Generic Patterns:** /sima/entries/  
**Python Architectures:** /sima/languages/python/architectures/  
**LEE Project:** /sima/projects/lee/ (Lambda implementation)  
**AWS Platform:** /sima/platforms/aws/ (Other AWS services)  
**API Gateway:** /sima/platforms/aws/api-gateway/ (Integration patterns)

---

## VERSION HISTORY

**v2.3.0 (2025-11-10):** ⭐ MEDIUM-PRIORITY ENHANCEMENTS (OPTIONAL)
- ADDED: 3 new files (LESS-16, DT-14, FW-03)
- ADDED: LESS-16 (Container Reuse Strategy - LEE implementation documented)
- ADDED: DT-14 (Async Processing Pattern Selection - future reference)
- ADDED: FW-03 (Predictive Resource Planning - high-scale optional)
- UPDATED: Total file count (30 → 34 files)
- ENHANCED: Topics section (Container Reuse, Async Processing, Predictive Planning)
- ENHANCED: Cross-references to new files
- ENHANCED: Quick reference checklist (4 new items)
- ENHANCED: Performance targets (warm start with container reuse)
- **Status:** COMPREHENSIVE + OPTIONAL PATTERNS - All scales covered (LEE to enterprise)

**v2.2.0 (2025-11-08):** ⭐ OPTIONAL ENHANCEMENTS
- ADDED: 4 new files (DEC-06, LESS-11, LESS-12, AP-06)
- ADDED: DEC-06 (VPC Configuration decision)
- ADDED: LESS-11 (API Gateway Integration Patterns)
- ADDED: LESS-12 (Environment Variable Management)
- ADDED: AP-06 (Not Using Lambda Layers anti-pattern)
- UPDATED: Total file count (26 → 30 files)
- ENHANCED: Topics section (added VPC Configuration, Environment Variables, API Gateway Integration, Lambda Layers)

**v2.1.0 (2025-11-08):**
- ADDED: 3 new files (DEC-05, AP-04, AP-05)
- ADDED: DEC-05 (Cost Optimization decision)
- ADDED: AP-04 (Ignoring Timeout anti-pattern)
- ADDED: AP-05 (Over-Provisioning Memory anti-pattern)
- UPDATED: Total file count (23 → 26 files)

**v2.0.0 (2025-11-08):**
- ADDED: 5 new lessons (LESS-06 through LESS-10)
- UPDATED: Topics section (added Security, Testing, Monitoring, Error Handling, Performance)

**v1.0.0 (2025-11-08):**
- Initial AWS Lambda platform index
- Core concepts, decisions, lessons documented

---

**END OF FILE**

**Files Indexed:** 34 (COMPREHENSIVE + OPTIONAL) âœ…  
**Topics Covered:** Cold starts, memory, container reuse, async processing, predictive planning, VPC, environment variables, API Gateway, layers, security, testing, monitoring, error handling, performance, cost  
**Lessons:** Comprehensive (13 total) âœ…  
**Decisions:** Complete (7 total) âœ…  
**Anti-Patterns:** Complete (6 total) âœ…  
**Decision Trees:** 2 (Architecture, Async Processing) âœ…  
**Frameworks:** 3 (Config Tiers, Profiling, Predictive Planning) âœ…  
**Status:** Production-ready comprehensive documentation covering all scale levels from LEE (50K invocations) to enterprise (500M+ invocations)

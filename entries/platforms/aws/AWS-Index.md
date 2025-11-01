# AWS-Index.md

**Version:** 1.0.0  
**Date:** 2025-11-01  
**Purpose:** Index for AWS-specific patterns and knowledge  
**Location:** `/sima/entries/platforms/aws/`

---

## OVERVIEW

Amazon Web Services specific patterns, constraints, optimizations, and lessons. Covers Lambda, DynamoDB, API Gateway, CloudWatch, Parameter Store, IAM, and other AWS services.

**Scope:** AWS-specific implementation details that don't fully genericize to other platforms.

---

## AWS ENTRIES BY SERVICE

### Lambda
**Count:** 0  
**Topics:** Cold starts, memory optimization, execution context, concurrency, layers, runtime constraints

**Entries:**
- (None yet)

---

### DynamoDB
**Count:** 0  
**Topics:** Single-table design, partition keys, GSI patterns, capacity modes, transactions, streams

**Entries:**
- (None yet)

---

### API Gateway
**Count:** 0  
**Topics:** Integration types, mapping templates, throttling, caching, custom domains, authorizers

**Entries:**
- (None yet)

---

### CloudWatch
**Count:** 0  
**Topics:** Metrics, logs, alarms, dashboards, insights, embedded metrics format

**Entries:**
- (None yet)

---

### Parameter Store / Secrets Manager
**Count:** 0  
**Topics:** Caching strategies, access patterns, encryption, rotation, performance

**Entries:**
- (None yet)

---

### IAM
**Count:** 0  
**Topics:** Least privilege, role assumption, policy optimization, resource-based policies

**Entries:**
- (None yet)

---

### Other Services
**Count:** 0  
**Topics:** SNS, SQS, EventBridge, Step Functions, S3, etc.

**Entries:**
- (None yet)

---

## ENTRY NAMING CONVENTION

Format: `PLAT-AWS-##_[Service]-[Topic].md`

**Examples:**
- `PLAT-AWS-01_Lambda-Cold-Start-Optimization.md`
- `PLAT-AWS-02_DynamoDB-Single-Table-Design.md`
- `PLAT-AWS-03_API-Gateway-Integration-Patterns.md`
- `PLAT-AWS-04_CloudWatch-Embedded-Metrics-Format.md`
- `PLAT-AWS-05_Parameter-Store-Caching-Strategy.md`

**REF-ID:** `PLAT-AWS-##` (sequential numbering)

---

## WHEN TO CREATE AWS ENTRY

### Create Entry When:

**Service Constraints:**
- [OK] Lambda 128MB package size limit workarounds
- [OK] Lambda 15-minute timeout handling
- [OK] Lambda cold start mitigation techniques
- [OK] DynamoDB 400KB item size constraints
- [OK] API Gateway 29-second timeout handling

**Service-Specific Patterns:**
- [OK] DynamoDB single-table design patterns
- [OK] Lambda layer optimization strategies
- [OK] CloudWatch Embedded Metrics Format usage
- [OK] Parameter Store hierarchical naming
- [OK] IAM policy size optimization

**AWS-Specific Optimizations:**
- [OK] Lambda memory vs CPU tradeoffs
- [OK] DynamoDB capacity mode selection
- [OK] API Gateway caching strategies
- [OK] CloudWatch log retention optimization

**Integration Patterns:**
- [OK] Lambda + API Gateway error handling
- [OK] Lambda + DynamoDB connection pooling
- [OK] EventBridge + Lambda fan-out patterns

### Don't Create Entry When:

**Generic Patterns:**
- [X] Pattern applies to any serverless platform -> Use core lessons
- [X] Database pattern works on any NoSQL DB -> Genericize
- [X] API design principle is universal -> Use generic entries
- [X] Logging/monitoring pattern is platform-agnostic -> Use generic

---

## CROSS-REFERENCES

### Related Core Architecture
- [ARCH-SUGA](../core/ARCH-SUGA_Single-Universal-Gateway-Architecture.md) - Gateway pattern (Lambda implementation)
- [ARCH-ZAPH](../core/ARCH-ZAPH_Zero-Abstraction-Path-for-Hot-Operations.md) - Hot path optimization (Lambda)
- [ARCH-LMMS](../core/ARCH-LMMS_Lambda-Memory-Management-System.md) - Lambda-specific memory management

### Related Interfaces
- [INT-04 HTTP](../../interfaces/INT-04-HTTP-Interface.md) - AWS API calls
- [INT-06 Config](../../interfaces/INT-06-Config-Interface.md) - Parameter Store integration
- [INT-07 Metrics](../../interfaces/INT-07-Metrics-Interface.md) - CloudWatch integration
- [INT-11 WebSocket](../../interfaces/INT-11-WebSocket-Interface.md) - API Gateway WebSocket

### Related Lessons
- [LESS-02](../../lessons/performance/LESS-02.md) - Lazy loading (Lambda cold start optimization)
- [LESS-09](../../lessons/operations/LESS-09.md) - Resource management (Lambda context)
- [LESS-25](../../lessons/optimization/LESS-25.md) - Lambda execution context

### Related Anti-Patterns
- [AP-08](../../anti-patterns/concurrency/AP-08.md) - Threading in Lambda
- [AP-12](../../anti-patterns/performance/AP-12.md) - Heavy libraries in Lambda

### Related Decisions
- [DEC-02](../../decisions/architecture/DEC-02.md) - Serverless architecture choice
- [DT-12](../../decisions/deployment/DT-12.md) - Lambda deployment strategies

---

## QUALITY STANDARDS

### AWS Entry Requirements

**Content:**
- [OK] AWS service explicitly identified
- [OK] Service constraint or limitation documented
- [OK] Platform-specific pattern described
- [OK] Why AWS-specific (not generic) explained
- [OK] Quantified impact when possible
- [OK] Working example (AWS API calls shown)

**Format:**
- [OK] Filename: `PLAT-AWS-##_[Service]-[Topic].md`
- [OK] REF-ID: `PLAT-AWS-##`
- [OK] Version: 1.0.0
- [OK] Date: 2025-11-01
- [OK] <=400 lines (SIMAv4)
- [OK] 4-8 keywords (include AWS service name)
- [OK] 3-7 related topics

**Genericization Balance:**
- [OK] Keep AWS-specific implementation details
- [OK] Note if pattern has generic equivalent
- [OK] Link to generic entries when applicable
- [X] Don't include fully generic patterns here

---

## EXTRACTION WORKFLOW

### Creating AWS Entry

```
1. Identify AWS-specific pattern/constraint

2. Check duplicates:
   project_knowledge_search: "[aws service] [topic]"
   Check: Already documented?

3. Assess genericization:
   - Can this be fully genericized? -> Use core lessons instead
   - Is this AWS-specific? -> Continue

4. Extract (BRIEF):
   - Service: Which AWS service(s)
   - Constraint: What AWS-specific limitation
   - Pattern: AWS-specific approach
   - Impact: Quantified
   - Example: AWS API calls (2-3 lines)

5. Assign PLAT-AWS-## (next available)

6. Create file as artifact:
   - Location: /sima/entries/platforms/aws/
   - Format: PLAT-AWS-##_[Service]-[Topic].md
   - Filename in header
   - <=400 lines
   - Complete entry

7. Update this index (add to appropriate service section)
```

---

## MAINTENANCE

**Last Updated:** 2025-11-01  
**Total Entries:** 0  
**Next REF-ID:** PLAT-AWS-01

### Update Checklist

When adding new AWS entry:
- [OK] Create file: `PLAT-AWS-##_[Service]-[Topic].md`
- [OK] Update service section in this index
- [OK] Increment total entries count
- [OK] Update next REF-ID
- [OK] Add cross-references to related entries
- [OK] Update Platforms-Master-Index.md

---

## USAGE EXAMPLES

### Good AWS Entries (Would Create)

**Example 1:**
```
PLAT-AWS-01_Lambda-Cold-Start-Optimization.md
- Lambda-specific: 128MB package size limit
- Pattern: Lazy loading + code splitting
- AWS constraint: Cold start performance impact
- Quantified: Reduced cold start 1200ms -> 450ms
```

**Example 2:**
```
PLAT-AWS-02_DynamoDB-Single-Table-Design.md
- DynamoDB-specific: Access pattern modeling
- Pattern: Partition key overloading
- AWS constraint: GSI limits, query patterns
- Quantified: Reduced tables 12 -> 1, saved 40% costs
```

### Bad AWS Entries (Shouldn't Create)

**Example 1 (Too Generic):**
```
❌ "Always validate input before processing"
-> Generic pattern, use LESS-## instead
-> Not AWS-specific
```

**Example 2 (Already Documented):**
```
❌ "Use gateway pattern for imports"
-> Already ARCH-SUGA
-> Not AWS-specific
```

---

## FUTURE EXPANSION

### Planned Service Coverage

**High Priority:**
- Lambda performance optimization
- DynamoDB data modeling patterns
- API Gateway integration best practices
- CloudWatch metrics and logging

**Medium Priority:**
- Parameter Store caching strategies
- IAM policy optimization
- SNS/SQS messaging patterns
- EventBridge routing patterns

**Low Priority:**
- S3 integration patterns
- Step Functions orchestration
- Secrets Manager rotation
- X-Ray tracing patterns

---

**END OF INDEX**

**Statistics:**
- Total Entries: 0
- Services Covered: 0
- Next REF-ID: PLAT-AWS-01

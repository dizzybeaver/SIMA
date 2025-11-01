# AWS-Quick-Index.md

**Version:** 4.0.0  
**Date:** 2025-11-01  
**Purpose:** Fast keyword routing for AWS knowledge (SIMAv4)

**Type:** Gateway Layer  
**Routing Time:** < 5 seconds  
**Total Entries:** 10 patterns

---

## How to Use

**For AWS/serverless questions:**
1. Look up keyword in tables below
2. Navigate to indicated pattern
3. Read complete entry
4. Cross-reference project maps for implementation

**Routing Speed:** ~5 seconds

---

## Keyword Tables

### Table 1: Lambda & Serverless Fundamentals

| Keyword | Pattern | Category | Priority |
|---------|---------|----------|----------|
| lambda | AWS-LESS-03, 11, 12 | aws/lambda/ | 🔴 Critical |
| serverless | AWS-LESS-01, 03 | aws/general/, aws/lambda/ | 🟠 High |
| stateless | AWS-LESS-03 | aws/lambda/ | 🟠 High |
| cold start | AWS-LESS-03 | aws/lambda/ | 🟠 High |
| timeout | AWS-LESS-12 | aws/lambda/ | 🔴 Critical |
| cost optimization | AWS-LESS-11 | aws/lambda/ | 🟠 High |
| pay-per-use | AWS-LESS-11 | aws/lambda/ | 🟠 High |
| chunking | AWS-LESS-12 | aws/lambda/ | 🔴 Critical |

### Table 2: Processing Patterns

| Keyword | Pattern | Category | Priority |
|---------|---------|----------|----------|
| synchronous | AWS-LESS-01 | aws/general/ | 🟠 High |
| asynchronous | AWS-LESS-01 | aws/general/ | 🟠 High |
| streaming | AWS-LESS-01 | aws/general/ | 🟠 High |
| request-response | AWS-LESS-01 | aws/general/ | 🟠 High |
| event-driven | AWS-LESS-01 | aws/general/ | 🟠 High |
| queue | AWS-LESS-01 | aws/general/ | 🟠 High |

### Table 3: DynamoDB & NoSQL

| Keyword | Pattern | Category | Priority |
|---------|---------|----------|----------|
| DynamoDB | AWS-LESS-05, 06, 07, 08 | aws/dynamodb/ | 🔴 Critical |
| partition key | AWS-LESS-05 | aws/dynamodb/ | 🟠 High |
| sort key | AWS-LESS-05 | aws/dynamodb/ | 🟠 High |
| primary key | AWS-LESS-05 | aws/dynamodb/ | 🟠 High |
| query | AWS-LESS-07 | aws/dynamodb/ | 🔴 Critical |
| scan | AWS-LESS-07 | aws/dynamodb/ | 🔴 Critical |
| GSI | AWS-LESS-06 | aws/dynamodb/ | 🟡 Medium |
| LSI | AWS-LESS-06 | aws/dynamodb/ | 🟡 Medium |
| secondary index | AWS-LESS-06 | aws/dynamodb/ | 🟡 Medium |
| item collection | AWS-LESS-08 | aws/dynamodb/ | 🟡 Medium |
| NoSQL | AWS-LESS-05, 06, 07, 08 | aws/dynamodb/ | 🟠 High |

### Table 4: API Gateway & Integration

| Keyword | Pattern | Category | Priority |
|---------|---------|----------|----------|
| API Gateway | AWS-LESS-09, 10 | aws/api-gateway/ | 🔴 Critical |
| proxy | AWS-LESS-09 | aws/api-gateway/ | 🔴 Critical |
| non-proxy | AWS-LESS-09 | aws/api-gateway/ | 🔴 Critical |
| integration | AWS-LESS-09 | aws/api-gateway/ | 🔴 Critical |
| transformation | AWS-LESS-10 | aws/api-gateway/ | 🟡 Medium |
| boundary | AWS-LESS-10 | aws/api-gateway/ | 🟡 Medium |
| pass-through | AWS-LESS-09 | aws/api-gateway/ | 🔴 Critical |

---

## Decision Trees

### Tree 1: "How to optimize Lambda?"

```
1. Identify issue:
   ├─> High cost? → AWS-LESS-11 (Pay-per-use model)
   ├─> Timeouts? → AWS-LESS-12 (Chunking strategies)
   ├─> Slow start? → AWS-LESS-03 (Stateless optimization)
   └─> Wrong pattern? → AWS-LESS-01 (Pattern selection)

2. Cross-reference:
   └─> Project: NM01/ARCH-07 (LMMS implementation)
   └─> Lessons: NM06/LESS-02 (Measure don't guess)

3. Apply pattern + verify
```

### Tree 2: "How to design DynamoDB schema?"

```
1. Start with access patterns:
   └─> AWS-LESS-05 (Primary key design)

2. Optimize queries:
   ├─> Using primary key? → AWS-LESS-07 (Query patterns)
   ├─> Need alternate access? → AWS-LESS-06 (Secondary indexes)
   └─> 1:many relationships? → AWS-LESS-08 (Item collections)

3. Validate:
   └─> Check: Are you avoiding scans? (AWS-LESS-07)

4. Implement with project patterns
```

### Tree 3: "How to design API integration?"

```
1. Determine integration type:
   └─> AWS-LESS-09 (Proxy vs non-proxy decision)

2. If transformation needed:
   └─> AWS-LESS-10 (Transformation strategies)

3. Check processing pattern:
   └─> AWS-LESS-01 (Sync/async/stream)

4. Implement
```

---

## Fast Paths (Instant Answers)

### Lambda Questions

**"Lambda keeps timing out?"**
→ AWS-LESS-12: Function Timeout Constraints
- Solution: Chunking, continuation, or orchestration

**"Lambda costs too high?"**
→ AWS-LESS-11: Pay-Per-Use Cost Model
- Key: Minimize per-request cost, not server utilization

**"How to handle state in Lambda?"**
→ AWS-LESS-03: Stateless Execution Patterns
- Pattern: Initialize → Process → Commit

### DynamoDB Questions

**"Query too slow?"**
→ AWS-LESS-07: Query vs Scan Patterns
- Check: Are you scanning instead of querying?
- Fix: Design keys for O(1) queries

**"How to design partition key?"**
→ AWS-LESS-05: Primary Key Design Patterns
- Rule: High cardinality, even distribution

**"Need alternate query path?"**
→ AWS-LESS-06: Secondary Index Strategies
- Decision: GSI (different partition) vs LSI (same partition)

**"How to model 1:many relationships?"**
→ AWS-LESS-08: Item Collection Patterns
- Pattern: Shared partition key, typed sort keys

### API Questions

**"Proxy or non-proxy?"**
→ AWS-LESS-09: Proxy vs Non-Proxy Integration
- Proxy: Simple gateway, complex backend
- Non-proxy: Complex gateway, simple backend

**"Where to transform data?"**
→ AWS-LESS-10: API Transformation Strategies
- Rule: Pure transformation at boundary, logic in application

### Architecture Questions

**"Which processing pattern?"**
→ AWS-LESS-01: Three Data Processing Patterns
- < 1s response needed: Synchronous
- Seconds-minutes OK: Asynchronous
- Continuous flow: Streaming

---

## Cross-Reference Guide

### When to Use AWS vs Project Maps

**Use AWS maps for:**
- Universal serverless patterns
- AWS-specific best practices
- Industry standards
- External validation

**Use Project maps (NM##) for:**
- SUGA-ISP specific implementation
- Internal decisions and rationale
- Project-specific bugs and lessons
- Architecture details

**Use Both for:**
- Complete understanding
- Implementation with best practices
- Validation of approaches

### Key Cross-References

**AWS-LESS-03 ↔ NM01/ARCH-07**
- AWS: Generic stateless pattern
- Project: LMMS implementation

**AWS-LESS-09 ↔ NM04/DEC-##**
- AWS: Universal proxy pattern
- Project: Integration decisions

**AWS-LESS-10 ↔ NM04/DEC-05**
- AWS: Transformation strategies
- Project: Sentinel sanitization at boundaries

**AWS-LESS-11 ↔ NM06/LESS-17**
- AWS: Pay-per-use optimization
- Project: Performance lessons

---

## Category Quick Reference

### aws/general/ (1 pattern)
**Purpose:** Cross-platform serverless patterns  
**Top item:** AWS-LESS-01 (Processing patterns)  
**When:** Architecture decisions, pattern selection

### aws/lambda/ (3 patterns)
**Purpose:** Lambda-specific optimization  
**Top items:**
- AWS-LESS-03 (Stateless execution)
- AWS-LESS-11 (Cost model)
- AWS-LESS-12 (Timeout handling)
**When:** Lambda development, optimization

### aws/dynamodb/ (4 patterns)
**Purpose:** NoSQL data modeling  
**Top items:**
- AWS-LESS-05 (Primary keys)
- AWS-LESS-07 (Query vs scan)
**When:** Database design, query optimization

### aws/api-gateway/ (2 patterns)
**Purpose:** API integration  
**Top items:**
- AWS-LESS-09 (Proxy vs non-proxy)
**When:** API design, integration decisions

---

## Search Strategy

**Level 1: This Quick Index (90%)**
1. Look up keyword in tables
2. Navigate to pattern
3. Check cross-references
**Time: ~5 seconds**

**Level 2: Master Index (8%)**
1. Use AWS-Master-Index.md
2. Browse by category
**Time: ~10 seconds**

**Level 3: Direct Browse (2%)**
1. Browse category directory
2. Read specific file
**Time: ~15 seconds**

---

## Navigation

- **Master Index:** AWS-Master-Index.md
- **Category Indexes:**
  - aws/general/General-Index.md
  - aws/lambda/Lambda-Index.md
  - aws/dynamodb/DynamoDB-Index.md
  - aws/api-gateway/APIGateway-Index.md
- **Project Maps:** /sima/entries/ (NM00-Quick-Index.md)

---

**Total Patterns:** 10  
**Coverage:** 100% of AWS external knowledge  
**Average Routing Time:** ~5 seconds  
**SIMAv4 Compliant:** ✅

**End of Quick Index**

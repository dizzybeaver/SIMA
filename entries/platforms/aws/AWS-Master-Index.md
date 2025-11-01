# AWS-Master-Index.md

**Version:** 4.0.0  
**Date:** 2025-11-01  
**Purpose:** Master index for AWS external knowledge (SIMAv4)

**Type:** Gateway Layer  
**Categories:** 4 (General, Lambda, DynamoDB, API Gateway)  
**Total Files:** 10 LESS entries  
**Last Updated:** 2025-11-01

---

## Overview

AWS external knowledge neural maps provide universal serverless patterns extracted from AWS documentation and genericized for broad application. These patterns supplement internal project maps with industry best practices.

**Structure:**
```
AWS Master Index (this file)
    ↓
Category Directories
    ↓
Individual Pattern Files (AWS-LESS-##)
```

**Integration:** AWS maps = universal patterns. Project maps (NM##) = specific implementations.

---

## Directory Structure

### aws/general/ (1 file)
**Purpose:** Cross-platform serverless patterns  
**Files:** 1 pattern  
**When to use:** Architecture decisions, pattern selection

**Entries:**
- **AWS-LESS-01:** Three Data Processing Patterns (Synchronous, Asynchronous, Streaming)

---

### aws/lambda/ (3 files)
**Purpose:** Lambda-specific patterns and constraints  
**Files:** 3 patterns  
**When to use:** Lambda optimization, cost management, execution design

**Entries:**
- **AWS-LESS-03:** Stateless Execution Patterns (Initialize → Process → Commit)
- **AWS-LESS-11:** Pay-Per-Use Cost Model (Optimize per-request, not utilization)
- **AWS-LESS-12:** Function Timeout Constraints (Chunking, continuation, orchestration)

---

### aws/dynamodb/ (4 files)
**Purpose:** DynamoDB data modeling and query optimization  
**Files:** 4 patterns  
**When to use:** NoSQL design, query optimization, data modeling

**Entries:**
- **AWS-LESS-05:** Primary Key Design Patterns (Partition + sort key selection)
- **AWS-LESS-06:** Secondary Index Strategies (GSI vs LSI, cost vs flexibility)
- **AWS-LESS-07:** Query vs Scan Patterns (O(1) vs O(n), when to use each)
- **AWS-LESS-08:** Item Collection Patterns (1:many relationships, data locality)

---

### aws/api-gateway/ (2 files)
**Purpose:** API Gateway integration patterns  
**Files:** 2 patterns  
**When to use:** API design, transformation decisions, boundary patterns

**Entries:**
- **AWS-LESS-09:** Proxy vs Non-Proxy Integration (Where to transform data)
- **AWS-LESS-10:** API Transformation Strategies (Pure transformation vs business logic)

---

## Quick Reference by Topic

### Architecture & Design
- **AWS-LESS-01:** Processing pattern selection (sync/async/stream)
- **AWS-LESS-03:** Stateless execution model
- **AWS-LESS-09:** API integration patterns
- **AWS-LESS-10:** Boundary transformation

### Performance & Optimization
- **AWS-LESS-05:** Key design for O(1) queries
- **AWS-LESS-06:** Index strategies for performance
- **AWS-LESS-07:** Query optimization (avoid scans)
- **AWS-LESS-11:** Cost optimization (minimize per-request cost)
- **AWS-LESS-12:** Timeout management (chunking strategies)

### Data Modeling
- **AWS-LESS-05:** Partition key selection
- **AWS-LESS-06:** Secondary index design
- **AWS-LESS-07:** Access pattern optimization
- **AWS-LESS-08:** Relationship modeling (item collections)

### Cost Management
- **AWS-LESS-06:** Index cost vs benefit
- **AWS-LESS-07:** Query cost vs scan cost
- **AWS-LESS-11:** Pay-per-use optimization

---

## Priority Guidance

### 🔴 Critical (Read First)
- **AWS-LESS-07:** Query vs Scan (10-100x performance difference)
- **AWS-LESS-09:** Proxy vs Non-Proxy (fundamental integration decision)
- **AWS-LESS-12:** Timeout Constraints (hard limits, must design for)

### 🟠 High Priority
- **AWS-LESS-01:** Processing Patterns (architecture foundation)
- **AWS-LESS-03:** Stateless Execution (serverless requirement)
- **AWS-LESS-05:** Primary Key Design (foundation for queries)
- **AWS-LESS-11:** Cost Model (optimization strategy shift)

### 🟡 Medium Priority
- **AWS-LESS-06:** Secondary Indexes (when needed)
- **AWS-LESS-08:** Item Collections (1:many modeling)
- **AWS-LESS-10:** Transformation Strategies (boundary patterns)

---

## Cross-References to Project Maps

### AWS → NM01 (Architecture)
- **AWS-LESS-03** → ARCH-07 (LMMS): Stateless execution implementation
- **AWS-LESS-09** → Interface patterns: Proxy/transformation decisions

### AWS → NM04 (Decisions)
- **AWS-LESS-01** → Processing pattern decisions
- **AWS-LESS-09** → Integration pattern decisions
- **AWS-LESS-10** → DEC-05 (Sentinel sanitization at boundaries)

### AWS → NM06 (Lessons)
- **AWS-LESS-03** → LESS-09: Configuration management
- **AWS-LESS-07** → LESS-02: Measure don't guess
- **AWS-LESS-11** → LESS-17: Performance optimization

---

## Usage Patterns

### For Lambda Development
**Read in order:**
1. AWS-LESS-03 (Stateless model)
2. AWS-LESS-11 (Cost optimization)
3. AWS-LESS-12 (Timeout handling)
4. AWS-LESS-01 (Pattern selection)

### For DynamoDB Design
**Read in order:**
1. AWS-LESS-05 (Primary keys)
2. AWS-LESS-07 (Query vs scan)
3. AWS-LESS-06 (Secondary indexes)
4. AWS-LESS-08 (Item collections)

### For API Design
**Read in order:**
1. AWS-LESS-09 (Proxy vs non-proxy)
2. AWS-LESS-10 (Transformation strategies)
3. AWS-LESS-01 (Processing patterns)

---

## Navigation

**Quick Index:** AWS-Quick-Index.md (keyword routing)  
**Category Indexes:**
- aws/general/General-Index.md
- aws/lambda/Lambda-Index.md
- aws/dynamodb/DynamoDB-Index.md
- aws/api-gateway/APIGateway-Index.md

**Project Maps:** /sima/entries/ (project-specific implementation)

---

## Keywords

AWS, serverless, Lambda, DynamoDB, API Gateway, patterns, best practices, external knowledge, optimization, cost management, performance, data modeling

---

## Statistics

**Total Patterns:** 10  
**By Category:**
- General: 1
- Lambda: 3
- DynamoDB: 4
- API Gateway: 2

**By Priority:**
- Critical: 3
- High: 4
- Medium: 3

**Coverage:**
- Architecture: 5 patterns
- Performance: 5 patterns
- Data Modeling: 4 patterns
- Cost Management: 3 patterns

---

## Version History

- **4.0.0 (2025-11-01):** Reorganized to SIMAv4 structure with proper categorization
- **3.0.0 (2025-10-25):** Original AWS06 structure (all in one category)

---

**Location:** `/sima/aws/`  
**Total Lines:** ~240  
**SIMAv4 Compliant:** ✅

**End of Master Index**

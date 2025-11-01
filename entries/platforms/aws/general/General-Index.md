# General-Index.md

**Version:** 4.0.0  
**Date:** 2025-11-01  
**Purpose:** Category index for general serverless patterns

**Category:** AWS General  
**Patterns:** 1 (AWS-LESS-01)  
**Priority:** High - Architecture foundation  
**Location:** `/sima/aws/general/`

---

## Overview

Cross-platform serverless patterns applicable to any FaaS platform. These patterns are fundamental architecture decisions that affect entire system design.

---

## Patterns in This Category

### AWS-LESS-01: Three Data Processing Patterns

**File:** `AWS-General-ProcessingPatterns_AWS-LESS-01.md`  
**Priority:** 🟠 High  
**Status:** Active

**Summary:** Serverless workloads fall into three patterns: synchronous (request-response), asynchronous (queued), and streaming (continuous). Each has distinct latency, scaling, and error handling characteristics. Choose based on user expectations, not technical convenience.

**The Three Patterns:**

**Synchronous:**
- User waits for response
- < 1 second required
- Simple error handling
- Use: API queries, form submissions

**Asynchronous:**
- Fire-and-forget with status tracking
- Seconds-minutes acceptable
- Complex error handling (retries, DLQ)
- Use: Image processing, batch jobs

**Streaming:**
- Continuous data flow
- < 100ms near real-time
- Very complex error handling
- Use: IoT sensors, log aggregation

**Decision Tree:**
```
User expects immediate result? → Synchronous
Operation < 1 second? → Synchronous
Continuous data flow? → Streaming
Otherwise → Asynchronous
```

**Cross-Reference:**
- AWS: aws/lambda/AWS-LESS-12.md (Timeout constraints)
- AWS: aws/api-gateway/AWS-LESS-09.md (API patterns)
- Project: /sima/entries/lessons/operations/ (Operations patterns)

---

## Related Content

**Other AWS Categories:**
- aws/lambda/ (Implementation details)
- aws/api-gateway/ (API design)
- aws/dynamodb/ (Data persistence)

**Project Maps:**
- /sima/entries/decisions/architecture/ (Architecture decisions)
- /sima/entries/core/ (SUGA architecture patterns)

---

## Navigation

- **Up:** AWS-Master-Index.md
- **Siblings:** aws/lambda/, aws/dynamodb/, aws/api-gateway/
- **Quick Index:** AWS-Quick-Index.md

**Total Patterns:** 1  
**SIMAv4 Compliant:** ✅

**End of Index**

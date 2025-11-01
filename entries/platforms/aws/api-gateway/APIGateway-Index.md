# APIGateway-Index.md

**Version:** 4.0.0  
**Date:** 2025-11-01  
**Purpose:** Category index for API Gateway patterns

**Category:** AWS API Gateway  
**Patterns:** 2 (AWS-LESS-09, 10)  
**Priority:** Critical for API design decisions  
**Location:** `/sima/aws/api-gateway/`

---

## Overview

API Gateway integration patterns covering proxy vs non-proxy integration decisions and boundary transformation strategies. These patterns define where transformation logic belongs and how to separate concerns at API boundaries.

---

## Patterns in This Category

### AWS-LESS-09: Proxy vs Non-Proxy Integration

**File:** `AWS-APIGateway-ProxyIntegration_AWS-LESS-09.md`  
**Priority:** 🔴 Critical  
**Status:** Active

**Summary:** Choose proxy (pass-through) for simple gateway/complex backend, or non-proxy (transformation) for complex gateway/simple backend. Decision based on team structure and where transformation belongs.

**Decision Framework:**
- Unified team + rapid iteration → Proxy
- Multiple clients + contracts → Non-Proxy
- Legacy integration → Non-Proxy

**Cross-Reference:**
- AWS: AWS-LESS-10 (Transformation strategies)
- Project: /sima/entries/decisions/architecture/DEC-05.md (Boundary concepts)

---

### AWS-LESS-10: API Transformation Strategies

**File:** `AWS-APIGateway-Transformation_AWS-LESS-10.md`  
**Priority:** 🟡 Medium  
**Status:** Active

**Summary:** Transform data at boundaries using pure functions. Transformation = reshape format. Business logic = decide meaning. Never mix.

**Core Rule:** Separate transformation (structure) from logic (decisions).

**Cross-Reference:**
- AWS: AWS-LESS-09 (Where to transform)
- Project: /sima/entries/decisions/architecture/DEC-05.md (Sentinel sanitization)

---

## Related Content

**Project Maps:**
- /sima/entries/decisions/architecture/ (Integration decisions)
- /sima/entries/lessons/operations/ (API patterns)

**Other AWS:**
- aws/lambda/ (Backend implementation)
- aws/general/ (Processing patterns)

---

## Navigation

- **Up:** AWS-Master-Index.md
- **Siblings:** aws/general/, aws/lambda/, aws/dynamodb/
- **Quick Index:** AWS-Quick-Index.md

**Total Patterns:** 2  
**SIMAv4 Compliant:** ✅

**End of Index**

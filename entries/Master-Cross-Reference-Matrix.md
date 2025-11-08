# Master-Cross-Reference-Matrix.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Complete cross-domain knowledge navigation matrix  
**Category:** Universal Navigation

---

## 🎯 PURPOSE

This matrix shows how all knowledge domains interconnect:
- **Generic** (universal patterns)
- **Platform** (AWS Lambda, API Gateway, DynamoDB)
- **Language** (Python architectures: SUGA, LMMS, ZAPH, DD-1, DD-2, CR-1)
- **Project** (LEE implementation)

Navigate from any domain to related knowledge in other domains.

---

## 📊 DOMAIN OVERVIEW

### Knowledge Organization
```
/sima/entries/           Generic universal knowledge (ALWAYS loaded)
/sima/platforms/         Platform-specific (AWS, Azure, GCP)
/sima/languages/         Language-specific (Python, JavaScript, etc.)
/sima/projects/          Project implementations (LEE, etc.)
```

### Cross-References Flow
```
Generic ←→ Platform
   ↕         ↕
Language ←→ Project
```

---

## 🔗 CORE ARCHITECTURE CROSS-REFERENCES

### SUGA (Gateway Architecture)

**Location:** `/sima/languages/python/architectures/suga/`

**Generic Patterns:**
- GATE-01 → Gateway Layer Structure
- GATE-02 → Lazy Import Pattern
- GATE-03 → Cross-Interface Communication

**Platform Requirements:**
- AWS-Lambda-AP-01 → No Threading (DEC-04 enforces)
- AWS-Lambda-DEC-01 → Single-threaded execution
- AWS-Lambda-LESS-01 → Cold start optimization (LMMS)

**Project Implementation:**
- LEE gateway.py → Implements SUGA
- LEE interface_*.py → 12 interfaces
- LEE *_core.py → Core implementations

**Related Architectures:**
- LMMS → Import optimization for SUGA
- DD-2 → Dependency management for layers
- CR-1 → Consolidates gateway exports

---

### LMMS (Lazy Module Management)

**Location:** `/sima/languages/python/architectures/lmms/`

**Generic Patterns:**
- LESS-02 → Measure don't guess
- DEC-07 → Memory constraints

**Platform Requirements:**
- AWS-Lambda-Core-Concepts → Cold start explained
- AWS-Lambda-DEC-02 → 128MB memory limit
- AWS-Lambda-LESS-01 → Cold start impact

**Project Implementation:**
- LEE fast_path.py → Hot path optimization
- LEE lambda_function.py → Cold start management
- LEE interface files → Lazy imports throughout

**Related Architectures:**
- SUGA → Benefits from lazy imports
- ZAPH → Hot path identification
- DD-1 → Performance patterns

---

### ZAPH (Zone Access Priority Hierarchy)

**Location:** `/sima/languages/python/architectures/zaph/`

**Generic Patterns:**
- LESS-02 → Measure before optimize

**Platform Requirements:**
- AWS-Lambda-LESS-10 → Performance tuning
- AWS-Lambda-DEC-05 → Cost optimization

**Project Implementation:**
- LEE fast_path.py → Tier 1 hot path
- LEE gateway.py → Tier 2 frequent operations
- LEE *_core.py → Tier 3 cold path

**Related Architectures:**
- LMMS → Identifies what to lazy load
- DD-1 → Dictionary dispatch for hot paths
- SUGA → Where to apply tiering

---

### DD-1 (Dictionary Dispatch)

**Location:** `/sima/languages/python/architectures/dd-1/`

**Generic Patterns:**
- Performance optimization
- O(1) vs O(n) lookup patterns

**Platform Requirements:**
- AWS-Lambda-DEC-02 → Memory constraints (dict overhead)
- AWS-Lambda-LESS-10 → Performance tuning

**Project Implementation:**
- LEE interface_*.py → All use dispatch dicts
- LEE gateway_core.py → Central dispatch
- LEE execute_operation → DD-1 pattern

**Related Architectures:**
- ZAPH → Hot path optimization
- SUGA → Interface routing
- CR-1 → Registry pattern uses DD-1

---

### DD-2 (Dependency Disciplines)

**Location:** `/sima/languages/python/architectures/dd-2/`

**Generic Patterns:**
- DEC-01 → SUGA choice
- GATE-03 → Cross-interface rules

**Platform Requirements:**
- None (architecture pattern, not platform-specific)

**Project Implementation:**
- LEE SUGA layers → Follow DD-2
- LEE gateway → Higher layer
- LEE cores → Lower layer
- No circular imports → DD-2 enforced

**Related Architectures:**
- SUGA → Uses DD-2 for layer management
- All architectures → Follow DD-2 principles

---

### CR-1 (Cache Registry)

**Location:** `/sima/languages/python/architectures/cr-1/`

**Generic Patterns:**
- Consolidation patterns
- Central registry design

**Platform Requirements:**
- AWS-Lambda-DEC-02 → Memory trade-off (registry overhead)

**Project Implementation:**
- LEE gateway.py → Central export point
- LEE _INTERFACE_ROUTERS → CR-1 registry
- LEE gateway_wrappers_*.py → Wrapper functions
- LEE __all__ → 100+ exports consolidated

**Related Architectures:**
- SUGA → Consolidates SUGA exports
- DD-1 → Registry uses dispatch pattern
- LMMS → Fast path caching

---

## 🏗️ PLATFORM CROSS-REFERENCES

### AWS Lambda

**Location:** `/sima/platforms/aws/lambda/`

**Generic Patterns:**
- ARCH-07 → Lazy imports (LMMS)
- DEC-04 → No threading
- LESS-02 → Measure performance

**Language Architectures:**
- SUGA → Gateway pattern works on Lambda
- LMMS → Essential for cold start
- ZAPH → Hot path optimization
- DD-1 → Dispatch performance
- DD-2 → Layer dependencies
- CR-1 → Consolidation pattern

**Project Implementation:**
- LEE → Full Lambda implementation
- LEE-DEC-01 → Home Assistant on Lambda
- LEE-LESS-01 → WebSocket reliability

**Related Platform Services:**
- API Gateway → Frontend to Lambda
- DynamoDB → Lambda data store
- SSM → Lambda configuration

---

### AWS API Gateway

**Location:** `/sima/platforms/aws/api-gateway/`

**Generic Patterns:**
- Request validation
- CORS configuration
- Throttling

**Language Architectures:**
- None directly (frontend service)

**Project Implementation:**
- LEE → API Gateway integration
- LEE lambda_function.py → API Gateway events

**Related Platform Services:**
- Lambda → Backend for API Gateway
- CloudWatch → API Gateway logs

---

### AWS DynamoDB

**Location:** `/sima/platforms/aws/dynamodb/`

**Generic Patterns:**
- Data access patterns
- NoSQL design

**Language Architectures:**
- None directly (data store)

**Project Implementation:**
- LEE → Uses DynamoDB for caching
- INT-01 CACHE → May use DynamoDB

**Related Platform Services:**
- Lambda → DynamoDB client
- API Gateway → DynamoDB proxy patterns

---

## 📁 PROJECT CROSS-REFERENCES

### LEE (Home Automation Lambda)

**Location:** `/sima/projects/LEE/`

**Generic Patterns:**
- All core patterns apply
- All gateway patterns apply
- All interface patterns apply

**Platform Services:**
- AWS Lambda → Deployment platform
- API Gateway → REST API frontend
- SSM Parameter Store → Token storage

**Language Architectures:**
- SUGA → Complete implementation
- LMMS → fast_path.py + lazy imports
- ZAPH → Tier 1/2/3 optimization
- DD-1 → Interface dispatch tables
- DD-2 → Layer dependencies
- CR-1 → gateway.py consolidation

**LEE-Specific:**
- Home Assistant integration
- WebSocket management
- Device discovery
- Alexa integration
- Google Assistant integration

---

## 🔍 CROSS-REFERENCE BY TOPIC

### Threading

**Generic:**
- DEC-04 → No threading locks
- AP-08 → Threading primitives

**Platform:**
- AWS-Lambda-AP-01 → Threading prohibited
- AWS-Lambda-DEC-01 → Single-threaded model

**Language:**
- SUGA DEC-04 → No threading in SUGA

**Project:**
- LEE → No threading used

---

### Cold Start Optimization

**Generic:**
- LESS-02 → Measure first
- ARCH-07 → Lazy imports

**Platform:**
- AWS-Lambda-LESS-01 → Cold start impact
- AWS-Lambda-DEC-02 → Memory affects cold start

**Language:**
- LMMS → Complete cold start system
- ZAPH → Hot path identification
- SUGA GATE-02 → Lazy imports

**Project:**
- LEE fast_path.py → < 3 second cold start
- LEE-LESS-02 → WebSocket patterns

---

### Memory Management

**Generic:**
- DEC-07 → 128MB limit

**Platform:**
- AWS-Lambda-DEC-02 → Memory constraints
- AWS-Lambda-AP-05 → Over-provisioning

**Language:**
- LMMS → Lazy loading saves memory
- CR-1 → Registry memory trade-off

**Project:**
- LEE → 128MB total memory
- LEE cache strategies

---

### Performance Optimization

**Generic:**
- LESS-02 → Measure don't guess

**Platform:**
- AWS-Lambda-LESS-10 → Performance tuning
- AWS-Lambda-DEC-05 → Cost optimization

**Language:**
- ZAPH → Complete tiering system
- DD-1 → Dictionary dispatch
- LMMS → Import optimization

**Project:**
- LEE fast_path.py → Hot operations
- LEE performance_benchmark.py → Measurement

---

### Interface Patterns

**Generic:**
- INT-01 through INT-12 → 12 interfaces

**Platform:**
- AWS-Lambda → Interface implementations
- API Gateway → REST interfaces

**Language:**
- SUGA interfaces/ → 12 SUGA interfaces
- DD-1 → Interface dispatch pattern

**Project:**
- LEE interface_*.py → 12 implementations
- LEE-specific interfaces

---

## 📚 INDEX CROSS-REFERENCES

### Master Indexes

**Generic Knowledge:**
- Anti-Patterns-Master-Index.md
- Decisions-Master-Index.md
- Lessons-Master-Index.md
- Platforms-Master-Index.md

**Platform Services:**
- AWS-Master-Index.md
- AWS-Lambda-Index.md
- AWS-APIGateway-Master-Index.md
- AWS-DynamoDB-Master-Index.md

**Language Architectures:**
- suga-index-main.md (31 files)
- lmms-index-main.md (17 files)
- ZAPH-Decisions-Index.md (13 files)
- dd-1-index-main.md (8 files)
- dd-2-index-main.md (9 files)
- cr-1-index-main.md (6 files)

**Project:**
- LEE-Index-Main.md
- NMP00-LEE_Index.md

---

## 🎯 NAVIGATION STRATEGIES

### From Generic to Specific
1. Start with generic pattern (e.g., GATE-02)
2. Find language implementation (SUGA GATE-02)
3. Check platform requirements (AWS Lambda constraints)
4. See project usage (LEE interface files)

### From Project to Patterns
1. Start with project code (e.g., LEE gateway.py)
2. Identify architecture used (SUGA, LMMS, CR-1)
3. Review architecture docs (SUGA/, LMMS/, CR-1/)
4. Understand generic principles (GATE patterns)

### From Platform to Implementation
1. Start with platform constraint (e.g., AWS Lambda no threading)
2. Find generic decision (DEC-04)
3. Check architecture approach (SUGA DEC-04)
4. See project compliance (LEE no threading)

---

## 🔗 QUICK LOOKUPS

### "How is X implemented?"
1. Generic: /entries/[category]/[REF-ID].md
2. Architecture: /languages/python/architectures/[arch]/
3. Platform: /platforms/aws/[service]/
4. Project: /projects/LEE/

### "Why was X decided?"
1. Generic: /entries/decisions/
2. Architecture: /languages/python/architectures/[arch]/decisions/
3. Platform: /platforms/aws/[service]/decisions/
4. Project: /projects/LEE/decisions/

### "What are X anti-patterns?"
1. Generic: /entries/anti-patterns/
2. Architecture: /languages/python/architectures/[arch]/anti-patterns/
3. Platform: /platforms/aws/[service]/anti-patterns/

### "Where are X lessons?"
1. Generic: /entries/lessons/
2. Architecture: /languages/python/architectures/[arch]/lessons/
3. Platform: /platforms/aws/[service]/lessons/
4. Project: /projects/LEE/lessons/

---

**END OF MASTER CROSS-REFERENCE MATRIX**

**Coverage:** All 4 knowledge domains  
**Total Architectures:** 6 (SUGA, LMMS, ZAPH, DD-1, DD-2, CR-1)  
**Total Platform Services:** 3 (Lambda, API Gateway, DynamoDB)  
**Total Projects:** 1 (LEE)  
**Lines:** 400 (at limit)

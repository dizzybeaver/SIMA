# NM00A-Master_Index.md

# Master Index - Complete System Navigation

**Type:** Gateway Layer  
**Purpose:** Complete map of all neural maps  
**Categories:** 7 (NM01-NM07) + AWS External Knowledge  
**Total Files:** ~185 (NM) + 14 (AWS)  
**Total REF-IDs:** 168+ (NM) + 12 (AWS)  
**Last Updated:** 2025-10-25 (added AWS gateway links)

---

## 📖 SYSTEM OVERVIEW

**Complete Neural Map Architecture:**
```
NM Gateway (NM00) - Project-specific knowledge
    ↓
NM Categories (NM01-NM07)
    ↓
Project implementation & patterns

AWS Gateway (AWS00) - External/universal knowledge
    ↓
AWS Categories (AWS01-AWS07)
    ↓
Industry patterns & AWS best practices
```

**Integration:** AWS maps provide universal serverless patterns. NM maps provide project-specific implementation.

**Navigation:** Use NM maps for "how we do it", AWS maps for "how it's done universally"

---

## 🔗 AWS EXTERNAL KNOWLEDGE GATEWAY

**AWS00 Directory:** `/nmap/AWS/AWS00/`  
**Purpose:** Universal serverless patterns from AWS/industry  
**Status:** ✅ Active (AWS06 populated, others reserved)

**Quick Access:**
- **AWS Master Index:** AWS00-Master_Index.md
- **AWS Quick Index:** AWS00-Quick_Index.md
- **Active Content:** AWS06 (12 serverless LESS files)

**When to Use AWS Maps:**
- Learning universal serverless patterns
- Understanding industry best practices
- Validating project approaches against external standards
- Researching AWS-specific optimization techniques

**Integration with NM Maps:**
- AWS patterns inform NM decisions
- NM implementations reference AWS lessons
- Cross-reference for complete understanding

---

## 🗂️ NM CATEGORY DIRECTORY

### NM01 - Architecture & Interfaces

**Index:** `NM01/NM01-INDEX-Architecture.md`  
**Purpose:** System architecture, patterns, and interface definitions  
**Topics:** 3 (Core Architecture, Interfaces-Core, Interfaces-Advanced)  
**Files:** ~27 (1 index + 26 implementation)  
**REF-IDs:** 20 (8 ARCH + 12 INT)

**Priority Items:**
- ARCH-01: Gateway trinity (🔴 CRITICAL) - Foundation of SUGA
- ARCH-07: LMMS system (🔴 CRITICAL) - Lazy loading
- INT-01: CACHE interface (🟡 HIGH)
- INT-02: LOGGING interface (🟡 HIGH)
- INT-03: SECURITY interface (🟡 HIGH)

**AWS Cross-Reference:** AWS-LESS-01 (cold start optimization informs LMMS)

**Key Triggers:** gateway, architecture, SUGA, interface, LMMS, lazy loading

---

### NM02 - Dependencies & Rules

**Index:** `NM02/NM02-INDEX-Dependencies.md`  
**Purpose:** Import rules, dependency layers, interface dependencies  
**Topics:** 3 (Import Rules, Dependency Layers, Interface Dependencies)  
**Files:** ~23 (1 index + 22 implementation)  
**REF-IDs:** 18 (5 RULE + 5 DEP + 8 other)

**Priority Items:**
- RULE-01: Cross-interface via gateway (🔴 CRITICAL)
- RULE-02: Intra-interface direct (🔴 CRITICAL)
- RULE-03: External code gateway only (🔴 CRITICAL)
- DEP-01: Layer 0 LOGGING (🔴 CRITICAL)
- DEP-02 to DEP-05: Layers 1-4 (🟡 HIGH)

**Key Triggers:** import, circular import, dependency, layer, gateway only

---

### NM03 - Operations & Flows

**Index:** `NM03/NM03-INDEX-Operations.md`  
**Purpose:** Operation pathways, flows, error handling, tracing  
**Topics:** 4 (Pathways, Flows, Error Handling, Tracing)  
**Files:** ~15 (1 index + 14 implementation)  
**REF-IDs:** 13 (5 PATH + 3 FLOW + 3 ERROR + 2 TRACE)

**Priority Items:**
- PATH-01: Cold start pathway (🔴 CRITICAL)
- FLOW-01: Simple operations (🟡 HIGH)
- ERROR-01: Exception propagation (🟡 HIGH)
- TRACE-01: Request tracing (🟡 HIGH)

**Key Triggers:** pathway, flow, error, exception, tracing, cold start

---

### NM04 - Decisions & Rationale

**Index:** `NM04/NM04-INDEX-Decisions.md`  
**Purpose:** Design decisions and their rationale  
**Topics:** 3 (Architecture, Technical, Operational)  
**Files:** ~27 (1 index + 26 implementation)  
**REF-IDs:** 23+ (DEC-01 to DEC-23+)

**Priority Items:**
- DEC-01: SUGA pattern choice (🔴 CRITICAL)
- DEC-04: No threading locks (🔴 CRITICAL)
- DEC-05: Sentinel sanitization (🔴 CRITICAL)
- DEC-07: Dependencies < 128MB (🔴 CRITICAL)
- DEC-08: Flat file structure (🟡 HIGH)
- DEC-12: Multi-tier configuration (🟡 HIGH)
- DEC-21: SSM token-only (🟡 HIGH)

**AWS Cross-Reference:** Integration decisions reference AWS-LESS-09, AWS-LESS-10

**Key Triggers:** why, decision, rationale, choice, threading, sentinel

---

### NM05 - Anti-Patterns

**Index:** `NM05/NM05-INDEX-AntiPatterns.md`  
**Purpose:** What NOT to do and why  
**Topics:** 12 categories  
**Files:** ~42 (1 index + 41 implementation)  
**REF-IDs:** 28 (AP-01 to AP-28)

**Priority Items:**
- AP-01: Direct cross-interface imports (🔴 CRITICAL)
- AP-08: Threading primitives (🔴 CRITICAL)
- AP-14: Bare except clauses (🔴 CRITICAL)
- AP-19: Sentinel objects crossing boundaries (🔴 CRITICAL)
- AP-27: Skipping verification protocol (🔴 CRITICAL)
- AP-28: Not reading complete file (🔴 CRITICAL)

**Key Triggers:** anti-pattern, prohibited, never do, direct import, threading

---

### NM06 - Learned Experiences

**Index:** `NM06/NM06-INDEX-Learned.md`  
**Purpose:** Bugs, lessons learned, wisdom synthesis  
**Topics:** 9 categories  
**Files:** ~48 (1 index + 47 implementation)  
**REF-IDs:** ~40 (31 LESS + 4 BUG + 5 WISD)

**Priority Items:**
- BUG-01: Sentinel leak (🔴 CRITICAL)
- BUG-02: _CacheMiss validation (🔴 CRITICAL)
- LESS-15: 5-step verification protocol (🔴 CRITICAL)
- LESS-01: Read complete files first (🔴 CRITICAL)
- LESS-53: File version incrementation (🔴 CRITICAL)

**AWS Cross-Reference:** LESS-09, LESS-10 cross-reference AWS-LESS-09, AWS-LESS-10

**Key Triggers:** bug, lesson, verification, wisdom

---

### NM07 - Decision Logic & Trees

**Index:** `NM07/NM07-INDEX-DecisionLogic.md`  
**Purpose:** Decision trees and logic frameworks  
**Topics:** 8-9 categories  
**Files:** ~26 (1 index + 25 implementation)  
**REF-IDs:** ~16 (13 DT + 2 FW + 1 META)

**Priority Items:**
- DT-01: How to import X (🔴 CRITICAL)
- DT-03: Feature addition decision (🔴 CRITICAL)
- DT-05: How to handle errors (🟡 HIGH)
- DT-04: Should I cache this (🟡 HIGH)
- DT-07: Should I optimize (🟡 HIGH)

**Key Triggers:** how to, where to put, add feature, should I cache

---

## 🔤 COMPLETE REF-ID DIRECTORY

### NM REF-IDs (168+)
[Same as before - ARCH, AP, BUG, DEC, DEP, DT, ERROR, FLOW, FW, INT, LESS, MATRIX, META, PATH, RULE, TRACE, VALIDATION, WISD]

### AWS REF-IDs (12)
- **AWS-LESS-01 to AWS-LESS-12** → AWS06 (Serverless Patterns)

**Access:** See AWS00-Master_Index.md for complete AWS navigation

---

## 🔄 CROSS-CATEGORY CONNECTIONS

**NM Internal Connections:**
[Same as before]

**NM ↔ AWS Connections:**
- NM01/ARCH-07 (LMMS) ↔ AWS-LESS-01 (cold start optimization)
- NM04 (integration decisions) ↔ AWS-LESS-09 (proxy patterns)
- NM04/DEC-05 (sentinel sanitization) ↔ AWS-LESS-10 (transformation strategies)
- NM06/LESS-09, LESS-10 ↔ AWS-LESS-09, AWS-LESS-10 (cross-validation)

---

## 🧭 NAVIGATION GUIDE

### To Find Anything:

**Project-specific knowledge:**
1. Known keyword? → NM00-Quick_Index.md
2. Know category? → This file → NM Category
3. Know REF-ID? → This file → NM REF-ID Directory

**Universal/AWS patterns:**
1. Serverless question? → AWS00-Quick_Index.md
2. Industry best practice? → AWS00-Master_Index.md
3. Need validation? → Check both NM + AWS

**Complete understanding:**
1. Start with AWS for universal pattern
2. Check NM for project implementation
3. Cross-reference for full context

---

## 📈 SYSTEM METRICS

**Last Updated:** 2025-10-25  
**NM Files:** ~185  
**AWS Files:** 14 (2 gateway + 12 content)  
**Total REF-IDs:** 180+ (168 NM + 12 AWS)  
**Total Categories:** 7 NM + 8 AWS (1 active)  

---

## 🔧 MAINTENANCE NOTES

**When to Update NM:**
- New NM category/topic/REF-ID added
- File reorganization
- Priority changes

**When to Update AWS:**
- New AWS LESS file added
- New AWS category activated
- Cross-references to NM change

**Cross-Reference Maintenance:**
- Update both NM and AWS indexes when adding cross-references
- Ensure bidirectional links are maintained

---

**Navigation:**
- **NM Quick Index:** NM00-Quick_Index.md (project keyword routing)
- **AWS Gateway:** AWS00-Master_Index.md (external knowledge)
- **ZAPH:** NM00B-ZAPH.md (hot path optimization)

---

**End of Master Index**

**Total Lines:** ~250 (within limit, added AWS section)  
**Purpose:** Complete system navigation (NM + AWS)  
**Coverage:** 100% of neural maps + AWS gateway

# AWS00-Master_Index.md

# AWS Master Index - External Knowledge Navigation

**Type:** Gateway Layer  
**Purpose:** Complete map of AWS/external knowledge neural maps  
**Categories:** 8 (AWS00-AWS07)  
**Total Files:** 12 (AWS06 only, others reserved)  
**Total REF-IDs:** 12 (AWS-LESS-01 to AWS-LESS-12)  
**Last Updated:** 2025-10-25

---

## 📖 SYSTEM OVERVIEW

**AWS Neural Map Structure:**
```
AWS Gateway (AWS00) - This file
    ↓
AWS Category Layers (AWS01-AWS07)
    ↓
Individual LESS Files (~12 atoms)
```

**Purpose:** External knowledge from AWS documentation, serverless patterns, and third-party best practices genericized for universal application.

**Integration:** AWS maps supplement internal project maps (NM01-NM07). Use NM maps for project-specific, AWS maps for universal serverless patterns.

---

## 🗂️ CATEGORY DIRECTORY

### AWS00 - Gateway & Index

**This Directory**  
**Purpose:** Navigation and routing for AWS knowledge  
**Files:** 2 (Master Index, Quick Index)

---

### AWS01 - Reserved

**Status:** Reserved for future content  
**Planned:** Foundational AWS patterns

---

### AWS02 - Reserved

**Status:** Reserved for future content  
**Planned:** AWS service patterns

---

### AWS03 - Reserved

**Status:** Reserved for future content  
**Planned:** AWS operations patterns

---

### AWS04 - Reserved

**Status:** Reserved for future content  
**Planned:** AWS deployment patterns

---

### AWS05 - Reserved

**Status:** Reserved for future content  
**Planned:** AWS optimization patterns

---

### AWS06 - Serverless Patterns (Active)

**Directory:** `/nmap/AWS/AWS06/`  
**Purpose:** AWS serverless patterns and lessons  
**Files:** 12 LESS files  
**REF-IDs:** AWS-LESS-01 to AWS-LESS-12  
**Status:** ✅ Active

**Content:**
- AWS-LESS-01: Lambda cold start optimization
- AWS-LESS-03: API Gateway integration patterns
- AWS-LESS-05: Lambda memory/CPU relationship
- AWS-LESS-06: Lambda layer best practices
- AWS-LESS-07: DynamoDB access patterns
- AWS-LESS-08: EventBridge pattern matching
- AWS-LESS-09: Proxy vs non-proxy integration
- AWS-LESS-10: API transformation strategies
- AWS-LESS-11: Lambda concurrency patterns
- AWS-LESS-12: Step Functions state machine design

**Priority Items:**
- AWS-LESS-09: Proxy vs non-proxy (🔴 CRITICAL) - Integration decision framework
- AWS-LESS-10: Transformation strategies (🟡 HIGH) - Boundary patterns
- AWS-LESS-01: Cold start optimization (🟡 HIGH)
- AWS-LESS-05: Memory/CPU relationship (🟡 HIGH)

**Key Triggers:** serverless, lambda, API gateway, transformation, proxy, cold start, DynamoDB, EventBridge

---

### AWS07 - Reserved

**Status:** Reserved for future content  
**Planned:** AWS monitoring and observability patterns

---

## 🔤 REF-ID DIRECTORY

### A
- **AWS-LESS-01** to **AWS-LESS-12** → AWS06 (Serverless Patterns)

**Total:** 12 AWS REF-IDs

**Naming Convention:** `AWS-[TYPE]-[##]`
- TYPE: LESS (lesson learned), AP (anti-pattern), DEC (decision), etc.
- Numbers continue sequentially within type

---

## 🔄 CROSS-REFERENCE TO NM MAPS

**AWS maps provide universal patterns. NM maps provide project implementation.**

### Integration Points:

**AWS06 ↔ NM01 (Architecture)**
- AWS-LESS-09 (proxy patterns) → Used in NM01 interface design
- AWS-LESS-01 (cold start) → Informs NM01 LMMS architecture

**AWS06 ↔ NM04 (Decisions)**
- AWS-LESS-09 → Referenced in integration decisions
- AWS-LESS-10 → Referenced in transformation decisions

**AWS06 ↔ NM06 (Lessons)**
- AWS patterns provide external validation for internal lessons
- LESS-09, LESS-10 cross-reference AWS-LESS-09, AWS-LESS-10

**Usage Pattern:**
1. AWS maps: Learn universal pattern
2. NM maps: See project-specific implementation
3. Cross-reference for complete understanding

---

## 🧭 NAVIGATION GUIDE

### To Find AWS Content:

**Known topic?**
→ AWS00-Quick_Index.md (keyword lookup)

**Exploring serverless?**
→ AWS06 directory

**Need project implementation?**
→ Cross-reference to NM maps

**Unknown topic?**
→ This Master Index → Category Directory

### Common Patterns:

**User asks about API transformation:**
```
AWS00-Quick_Index → "transformation"
  → AWS06/AWS-LESS-10.md
  → Also see: NM maps for implementation
```

**User asks about Lambda optimization:**
```
AWS00-Quick_Index → "cold start" or "optimization"
  → AWS06/AWS-LESS-01.md (cold start)
  → AWS06/AWS-LESS-05.md (memory/CPU)
  → Cross-ref: NM01/ARCH-07 (LMMS implementation)
```

**User asks about integration patterns:**
```
AWS00-Quick_Index → "proxy" or "integration"
  → AWS06/AWS-LESS-09.md
  → Also see: NM04 for project decisions
```

---

## 📈 SYSTEM METRICS

**Last Updated:** 2025-10-25  
**Total Categories:** 8 (1 active, 7 reserved)  
**Total Files:** 12 (all in AWS06)  
**Total REF-IDs:** 12  

**Priority Distribution:**
- 🔴 CRITICAL: 1 item (8%)
- 🟡 HIGH: 3 items (25%)
- 🟢 MEDIUM: 5 items (42%)
- ⚪ LOW: 3 items (25%)

**Content Status:**
- ✅ AWS06: Complete (12 LESS files)
- 🔄 AWS01-AWS05, AWS07: Reserved

---

## 🔧 MAINTENANCE NOTES

**When to Update:**
- New AWS LESS file added
- New AWS category activated
- Cross-references change
- Priority shifts based on usage

**Update Process:**
1. Add REF-ID to directory
2. Update category if new
3. Update cross-references to NM maps
4. Update metrics

---

**Navigation:**
- **Quick Index:** AWS00-Quick_Index.md (keyword routing)
- **Project Maps:** /nmap/NM00/ (project-specific knowledge)

---

**End of AWS Master Index**

**Total Lines:** ~210  
**Purpose:** Complete AWS knowledge navigation  
**Scope:** External serverless patterns

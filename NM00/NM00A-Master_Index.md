# NM00A-Master_Index.md

# Neural Maps - Master Index (Complete System)

**Version:** 2.0.0  
**Date:** 2025-10-26  
**Total Systems:** 3 (NM, AWS, NMP)  
**Total Categories:** 8 (NM) + 8 (AWS) + Project-based (NMP)  
**Total Files:** 270+  
**Status:** Active - Three-Tier Knowledge System

---

## ðŸ"Š Three-Tier Knowledge Architecture

### System Philosophy

```
┌─────────────────────────────────────────────────┐
│ NM## - GENERIC (Transferable Everywhere)        │
│ • SUGA/SIMA architectural patterns              │
│ • Generic lessons learned (LESS-##)             │
│ • Universal anti-patterns (AP-##)               │
│ • Design decisions (DEC-##)                     │
│ • Transferable wisdom (WISD-##)                 │
└─────────────────────────────────────────────────┘
                      ↓ Specializes
┌─────────────────────────────────────────────────┐
│ AWS## - CLOUD PROVIDER (AWS-Specific)           │
│ • Lambda optimization patterns                  │
│ • DynamoDB access patterns                      │
│ • SSM integration best practices                │
│ • API Gateway integration                       │
│ • Serverless architecture patterns              │
└─────────────────────────────────────────────────┘
                      ↓ Implements
┌─────────────────────────────────────────────────┐
│ NMP## - PROJECT-SPECIFIC (This Project Only)    │
│ • Lambda Execution Engine specifics             │
│ • Home Assistant integration details            │
│ • Project-specific design decisions             │
│ • Implementation lessons from this codebase     │
└─────────────────────────────────────────────────┘
```

**Use Pattern:**
1. **Learning generic patterns?** → Start with NM##
2. **Learning AWS best practices?** → Check AWS##
3. **Understanding this project?** → Review NMP##
4. **Building feature?** → Use all three (NM→AWS→NMP)

---

## ðŸ—‚ï¸ NM## - Generic Neural Maps

### NM00 - Gateway Layer (Master Indexes & Fast Path)

**Purpose:** Navigation hub and performance-critical routing  
**Files:** 7  
**Key Content:**
- NM00A-Master_Index.md (this file)
- NM00-Quick_Index.md (keyword routing)
- NM00B-ZAPH*.md (4 files - hot path optimization)

**When to use:** Starting point for all searches

---

### NM01 - Core Architecture (SUGA Pattern)

**Purpose:** System architecture, interfaces, design patterns  
**Files:** 21  
**Key REF-IDs:** ARCH-01 to ARCH-09, INT-01 to INT-12  
**Index:** `NM01/NM01-INDEX-Architecture.md`

**Top content:**
- ARCH-01: Gateway Trinity (Gateway→Interface→Core)
- ARCH-07: LMMS (Lambda Memory Management System)
- INT-01 to INT-12: 12 interface specifications

**When to use:** 
- Understanding system architecture
- Working with interfaces
- Adding new features
- Design decisions

**Cross-references:**
- AWS-LESS-01 (Lambda cold start) → ARCH-07 (LMMS)
- NMP01-LEE-01 (HA caching) → INT-01 (CACHE interface)

---

### NM02 - Dependencies (Import Rules & Layers)

**Purpose:** Dependency management, import rules, layer boundaries  
**Files:** 18  
**Key REF-IDs:** RULE-01 to RULE-04, DEP-01 to DEP-05  
**Index:** `NM02/NM02-Dependencies_Index.md`

**Top content:**
- RULE-01: Gateway-only imports (prevent circular dependencies)
- DEP-01 to DEP-05: Layer dependency rules

**When to use:**
- Import errors
- Circular dependency issues
- Understanding layer boundaries
- Adding new modules

---

### NM03 - Operations (Flows, Pathways, Error Handling)

**Purpose:** Runtime behavior, execution flows, error handling  
**Files:** 5  
**Key REF-IDs:** PATH-01, FLOW-01, ERR-01  
**Index:** `NM03/NM03-Operations_Index.md`

**Top content:**
- Cold start sequence
- Request pathways
- Error handling strategies
- Tracing and debugging

**When to use:**
- Debugging runtime issues
- Understanding execution flow
- Troubleshooting errors
- Performance analysis

---

### NM04 - Decisions (Architecture, Technical, Operational)

**Purpose:** Design decisions with rationale  
**Files:** 23  
**Key REF-IDs:** DEC-01 to DEC-23  
**Index:** `NM04/NM04-Decisions_Index.md`

**Top content:**
- DEC-01: Why SUGA pattern?
- DEC-04: Why no threading locks?
- DEC-05: Why sanitize sentinels?
- DEC-21: SSM vs environment variables

**When to use:**
- "Why was X done this way?"
- Understanding design rationale
- Making similar decisions
- Evaluating trade-offs

**Cross-references:**
- AWS-LESS-09 (Proxy patterns) → Referenced in integration decisions
- NMP01-LEE-01 (HA caching) → Implements DEC-01 (SUGA)

---

### NM05 - Anti-Patterns (What NOT to Do)

**Purpose:** Common mistakes and how to avoid them  
**Files:** 41  
**Key REF-IDs:** AP-01 to AP-28  
**Index:** `NM05/NM05-AntiPatterns_Index.md`

**Top content:**
- AP-01: Direct core imports
- AP-08: Threading locks in Lambda
- AP-10: Sentinel objects crossing boundaries
- AP-14: Bare except clauses

**When to use:**
- Code reviews
- Before implementing new features
- Debugging unexpected behavior
- Learning what to avoid

---

### NM06 - Lessons Learned (Bugs, Wisdom, Experiences)

**Purpose:** Knowledge from experience, bugs discovered, wisdom gained  
**Files:** 40  
**Key REF-IDs:** LESS-01 to LESS-21, BUG-01 to BUG-04, WISD-01 to WISD-05  
**Index:** `NM06/NM06-Lessons_Index.md`

**Top content:**
- LESS-08: ISP Principle in practice
- BUG-01: Sentinel object leak (critical bug)
- WISD-02: Measure before optimizing

**When to use:**
- Learning from past mistakes
- Understanding bug history
- Gaining implementation wisdom
- Avoiding known pitfalls

**Cross-references:**
- LESS-08 (ISP) ← NMP01-LEE-01 (implements ISP in HA caching)
- AWS-LESS-01 (Cold start) → LESS-02 (Measurement)

---

### NM07 - Decision Logic (Decision Trees & Workflows)

**Purpose:** Systematic decision-making frameworks  
**Files:** 26  
**Key REF-IDs:** DT-01 to DT-13, FW-01 to FW-02  
**Index:** `NM07/NM07-DecisionLogic_Index.md`

**Top content:**
- DT-01: Interface selection decision tree
- FW-01: Feature addition workflow
- META-01: When to create new neural map

**When to use:**
- Making systematic decisions
- Following established workflows
- Choosing between alternatives
- Adding new content

---

## ☁️ AWS## - Cloud Provider Neural Maps

### AWS00 - Gateway Layer (Master Indexes)

**Purpose:** Navigation for AWS-specific knowledge  
**Files:** 2  
**Index:** `AWS/AWS00/AWS00-Master_Index.md`

**Content:**
- AWS00-Master_Index.md (AWS knowledge navigator)
- AWS00-Quick_Index.md (keyword routing for AWS)

**When to use:** Starting point for AWS-specific questions

---

### AWS01-AWS05, AWS07 - Reserved

**Status:** Reserved for future AWS content  
**Planned:** Foundational patterns, services, operations, deployment, monitoring

---

### AWS06 - Serverless Patterns (Active)

**Purpose:** AWS Lambda, API Gateway, serverless best practices  
**Files:** 12  
**Key REF-IDs:** AWS-LESS-01 to AWS-LESS-12  
**Index:** `AWS/AWS06/`

**Top content:**
- **AWS-LESS-01**: Lambda cold start optimization
- **AWS-LESS-05**: Memory/CPU relationship
- **AWS-LESS-09**: Proxy vs non-proxy integration (CRITICAL)
- **AWS-LESS-10**: API transformation strategies

**When to use:**
- Lambda optimization questions
- API Gateway integration
- Serverless architecture decisions
- AWS best practices

**Cross-references:**
- AWS-LESS-01 → NM01/ARCH-07 (LMMS implementation)
- AWS-LESS-09 → NM04 (Integration decisions)

---

## ðŸ"§ NMP## - Project-Specific Neural Maps

### NMP00 - Gateway Layer (Master Indexes)

**Purpose:** Navigation for project-specific knowledge  
**Files:** 2  
**Index:** `NMP/NMP00/NMP00A-Master_Index.md`

**Content:**
- NMP00A-Master_Index.md (project knowledge navigator)
- NMP00-Quick_Index.md (keyword routing for projects)

**When to use:** Starting point for project-specific questions

---

### NMP01 - Lambda Execution Engine (SUGA-ISP)

**Purpose:** LEE implementation specifics, HA integration  
**Files:** 1 (growing to 25+)  
**Project Code:** LEE  
**Index:** `NMP/NMP01/NMP01-LEE_Index.md`

**Current files:**
- **NMP01-LEE-01**: HA Cache Functions - Application vs Infrastructure

**Planned topics:**
- Interface function catalogs (12 interfaces)
- Gateway optimization specifics
- Home Assistant integration patterns
- Project-specific design decisions

**When to use:**
- Understanding LEE implementation
- HA integration questions
- Project-specific patterns
- Real-world examples of generic patterns

**Cross-references:**
- NMP01-LEE-01 → LESS-08 (ISP), INT-01 (CACHE)
- NMP01-LEE-01 → AWS-LESS-01 (Lambda optimization)

---

### NMP02-NMP99 - Future Projects

**Status:** Reserved  
**Examples:**
- NMP02: Weather Service Lambda
- NMP03: IoT Device Manager
- NMP04: Analytics Pipeline

**Structure:** Each project gets ONE flat folder (no sub-categories)

---

## ðŸ§­ Navigation Strategy

### For Generic Questions

```
1. Start: NM00-Quick_Index.md (keyword lookup)
2. Route to: Appropriate NM## category
3. Check: Related AWS## patterns
4. Example: NMP## for implementation
```

### For AWS Questions

```
1. Start: AWS00-Quick_Index.md (keyword lookup)
2. Route to: AWS06 (serverless patterns)
3. Cross-ref: NM## for generic patterns
4. Example: NMP## for project usage
```

### For Project Questions

```
1. Start: NMP00-Quick_Index.md (keyword lookup)
2. Route to: NMP01 (Lambda Execution Engine)
3. Cross-ref: NM## for generic pattern
4. Cross-ref: AWS## for cloud pattern
```

---

## 📊 System Statistics

### NM## (Generic)
**Categories:** 8 (NM00-NM07)  
**Files:** ~200  
**REF-IDs:** ~150  
**Scope:** Universal, transferable

### AWS## (Cloud Provider)
**Categories:** 8 (AWS00-AWS07, 1 active)  
**Files:** 12 (AWS06 only)  
**REF-IDs:** 12 (AWS-LESS-01 to AWS-LESS-12)  
**Scope:** AWS-specific, transferable across AWS projects

### NMP## (Project-Specific)
**Projects:** 1 active (NMP01)  
**Files:** 1 (growing to 25+ per project)  
**REF-IDs:** 1 (NMP01-LEE-01)  
**Scope:** Project-specific, adaptable to other projects

**Total System:**
- **Files:** 270+
- **REF-IDs:** 163+
- **Categories:** 16
- **Knowledge Tiers:** 3

---

## ðŸ"— Cross-System Integration Examples

### Example 1: Cache Implementation

**Generic Pattern (NM):**
- INT-01: CACHE interface specification
- LESS-08: ISP Principle

**AWS Pattern:**
- AWS-LESS-01: Lambda cold start (why caching matters)
- AWS-LESS-05: Memory considerations

**Project Implementation (NMP):**
- NMP01-LEE-01: HA cache warming specifics
- TTL decisions (600s config, 60s states)
- Domain-specific invalidation patterns

**Flow:** NM (interface) → AWS (optimization) → NMP (implementation)

### Example 2: API Integration

**Generic Pattern (NM):**
- DEC-05: Boundary sanitization
- AP-10: Don't leak sentinels

**AWS Pattern:**
- AWS-LESS-09: Proxy vs non-proxy integration
- AWS-LESS-10: Transformation strategies

**Project Implementation (NMP):**
- NMP01-LEE-17 (planned): HA API integration
- Specific transformation choices
- Error handling for HA API

**Flow:** NM (principle) → AWS (pattern) → NMP (implementation)

---

## 🔄 Maintenance Guidelines

### When to Update

**NM## (Generic):**
- New universal pattern discovered
- New anti-pattern identified
- Generic lesson learned

**AWS## (Cloud Provider):**
- New AWS service integrated
- AWS best practice changes
- New serverless pattern discovered

**NMP## (Project-Specific):**
- Project code significantly changes
- New feature implemented
- Project-specific lesson learned

### Update Process

1. **Determine tier**: Generic (NM)? AWS-specific (AWS)? Project-specific (NMP)?
2. **Check duplicates**: Search existing content
3. **Create entry**: Use appropriate template
4. **Update indexes**: Add to category and master indexes
5. **Cross-reference**: Link between tiers if applicable

---

## âš ï¸ Key Architectural Differences

### NM## Structure
**Path:** `nmap/NM##/`  
**Organization:** 7 categories (NM01-NM07) with sub-topics  
**Example:** `nmap/NM06/NM06-Lessons-Critical_LESS-08.md`

### AWS## Structure  
**Path:** `nmap/AWS/AWS##/`  
**Organization:** Mirrors NM structure (AWS01-AWS07)  
**Example:** `nmap/AWS/AWS06/AWS-LESS-01.md`  
**Note:** Parallel structure to NM for consistency

### NMP## Structure
**Path:** `nmap/NMP/NMP##/`  
**Organization:** Flat per project (NMP01, NMP02, ...)  
**Example:** `nmap/NMP/NMP01/NMP01-LEE-01.md`  
**Note:** One folder per project, no sub-categories

**Why Different?**
- **NM/AWS**: Knowledge organized by category (architecture, lessons, etc.)
- **NMP**: Knowledge organized by project (each project isolated)

---

## 📚 Quick Reference Links

**Master Indexes:**
- NM: `nmap/NM00/NM00A-Master_Index.md` (this file)
- AWS: `nmap/AWS/AWS00/AWS00-Master_Index.md`
- NMP: `nmap/NMP/NMP00/NMP00A-Master_Index.md`

**Quick Indexes:**
- NM: `nmap/NM00/NM00-Quick_Index.md`
- AWS: `nmap/AWS/AWS00/AWS00-Quick_Index.md`
- NMP: `nmap/NMP/NMP00/NMP00-Quick_Index.md`

**Project Index:**
- NMP01: `nmap/NMP/NMP01/NMP01-LEE_Index.md`

---

## 🎯 Usage Patterns

### Pattern 1: "Can I use X?"
```
1. Check: NM05 (Anti-Patterns) - Is X forbidden?
2. Check: NM04 (Decisions) - Why/why not?
3. Check: AWS## - AWS-specific considerations?
4. Check: NMP## - How is it used in project?
```

### Pattern 2: "How do I implement Y?"
```
1. Check: NM01 (Architecture) - Which interface?
2. Check: AWS## - AWS best practices?
3. Check: NMP## - Project examples?
4. Check: NM07 (Workflows) - Step-by-step process?
```

### Pattern 3: "Why was Z decided?"
```
1. Check: NM04 (Decisions) - Generic rationale?
2. Check: AWS## - AWS-specific reasons?
3. Check: NMP## - Project-specific context?
```

---

**End of Master Index**

**Version:** 2.0.0  
**Major Update:** Added three-tier knowledge system (NM/AWS/NMP)  
**Last Updated:** 2025-10-26  
**Next Review:** When NMP01 reaches 10 files

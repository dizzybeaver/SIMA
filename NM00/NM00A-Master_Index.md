# NMP00A-Master_Index.md

# Neural Maps Project-Specific - Master Index

**Version:** 1.0.0  
**Date:** 2025-10-26  
**Total Projects:** 1  
**Total Files:** 1  
**Status:** Active - Initial Setup Complete

---

## ðŸ"Š System Overview

### What is NMP?

**NMP = Neural Maps Project-specific**

Knowledge specific to individual projects that doesn't transfer generically. Each project maintains its own folder with flat structure (no sub-categories).

### Architecture Philosophy

**Three-Tier Knowledge System:**

```
┌─────────────────────────────────────────────────┐
│ NM## - Generic (Transferable Everywhere)        │
│ • Architectural patterns (SUGA, SIMA)           │
│ • Generic lessons (LESS-##)                     │
│ • Anti-patterns (AP-##)                         │
│ • Universal principles                          │
└─────────────────────────────────────────────────┘
                      ↓ Specializes
┌─────────────────────────────────────────────────┐
│ AWS## - Cloud Provider (AWS Projects)           │
│ • Lambda optimization                           │
│ • DynamoDB patterns                             │
│ • SSM best practices                            │
│ • AWS service integration                       │
└─────────────────────────────────────────────────┘
                      ↓ Implements
┌─────────────────────────────────────────────────┐
│ NMP## - Project-Specific (This Project Only)    │
│ • Implementation details                        │
│ • Project-specific decisions                    │
│ • Integration patterns                          │
│ • Codebase lessons                              │
└─────────────────────────────────────────────────┘
```

---

## ðŸ"‚ Directory Structure

### File Organization

```
nmap/NMP/
│
├── NMP00/ (Indexes)
│   ├── NMP00-Quick_Index.md
│   └── NMP00A-Master_Index.md (this file)
│
├── NMP01/ (Lambda Execution Engine)
│   ├── NMP01-LEE_Index.md
│   └── NMP01-LEE-01.md
│
├── NMP02/ (Future Project)
│   └── [Future files]
│
└── NMP03/ (Future Project)
    └── [Future files]
```

**Key Difference:** One flat folder per project (no sub-categories like NM)

---

## ðŸ—‚ï¸ Active Projects

### NMP01: Lambda Execution Engine (SUGA-ISP)

**Status:** âœ… Active  
**Files:** 1  
**Index:** `NMP01/NMP01-LEE_Index.md`

**Purpose:**  
Document Lambda Execution Engine implementation specifics, including Home Assistant integration, caching strategies, and project-specific design decisions.

**Current Files:**
1. **NMP01-LEE-01**: HA Cache Functions - Application vs Infrastructure
   - Priority: 🟢 HIGH
   - Teaching example: Application logic vs infrastructure
   - Keywords: application-logic, cache-strategies, ISP

**Planned Topics:**
- Interface function catalogs (12 interfaces)
- Gateway optimization specifics
- Home Assistant integration patterns
- Failsafe independence rationale
- Diagnostic tool usage

---

## ðŸ"‹ All Files by REF-ID

### LEE (Lambda Execution Engine)

| REF-ID | Title | Priority | File |
|--------|-------|----------|------|
| NMP01-LEE-01 | HA Cache Functions - Application vs Infrastructure | 🟢 HIGH | NMP01-LEE-01.md |

**Count:** 1 file

---

## 🎯 Content Guidelines

### What Goes in NMP?

**âœ… INCLUDE:**
- Project-specific implementation details
- "Why we did it this way in THIS project"
- Integration patterns unique to this codebase
- Lessons from this specific project's evolution
- Function catalogs for this project's interfaces
- This project's architecture decisions

**❌ EXCLUDE (Use NM Instead):**
- Generic patterns transferable to other projects
- Universal lessons applicable everywhere
- Generic anti-patterns
- SUGA/SIMA architecture principles
- Standard design patterns

**❌ EXCLUDE (Use AWS Instead):**
- AWS service best practices
- Lambda optimization (generic)
- DynamoDB patterns
- SSM usage patterns

### Example Classification

**Scenario:** HA cache warming function

**Generic lesson (→ NM):**  
"Application logic should use infrastructure primitives, not duplicate them"

**Project-specific lesson (→ NMP):**  
"In Lambda Execution Engine, HA cache warming pre-loads config (600s TTL) and states (60s TTL) because states are accessed in 80% of HA requests"

---

## 🔗 Cross-System Navigation

### From NMP to NM (Generic Knowledge)

NMP files should reference generic patterns:
- DEC-01 (SUGA Pattern)
- LESS-08 (ISP Principle)
- INT-01 (CACHE interface)

### From NMP to AWS (Cloud Provider)

NMP files should reference AWS patterns:
- AWS06-LESS-01 (Lambda cold start)
- AWS optimization patterns
- SSM integration

### From NM to NMP (Examples)

Generic lessons can link to project examples:
- LESS-08 → "See NMP01-LEE-01 for real example"
- DEC-01 → "Implemented in NMP01 Lambda project"

---

## ðŸ"Š Statistics

### Current State

**Projects:** 1 active  
**Files:** 1 total  
**Categories:** LEE (Lambda Execution Engine)  
**Growth Rate:** ~5-10 files per project

### Growth Projections

**Short-term (3 months):**
- NMP01: ~15 files (interface catalogs, integration patterns)
- Projects: 1-2

**Medium-term (1 year):**
- NMP01: ~30 files (mature documentation)
- Projects: 2-4

**Long-term (2 years):**
- Projects: 5-10 (different Lambda functions, services)
- Files per project: 20-40
- Total: 100-400 files

---

## 🎓 When to Create New NMP Project

**Create new NMP## folder when:**

1. **New distinct project/service**
   - Different Lambda function
   - Different microservice
   - Different application

2. **Significant architectural differences**
   - Different tech stack
   - Different integration patterns
   - Different domain logic

3. **Separate deployment**
   - Independent deployment pipeline
   - Separate AWS account/region
   - Different team ownership

**Example:**
- NMP01: SUGA-ISP Lambda (Home Assistant)
- NMP02: Weather Service Lambda
- NMP03: IoT Device Manager Lambda
- NMP04: Analytics Pipeline

---

## 🔄 Maintenance

### Update Frequency

**Per file:** Update when code changes significantly  
**Indexes:** Update when new files added  
**Cross-references:** Update when new related content added

### Quality Standards

All NMP files must have:
- âœ… Clear project context
- âœ… Code references (file, line numbers)
- âœ… REF-ID assignment
- âœ… 4-8 keywords
- âœ… 3-7 related topics
- âœ… Version history

---

## 🚀 Getting Started

### Adding New NMP File

1. **Choose project**: Which NMP## folder?
2. **Assign REF-ID**: NMP##-[PROJECT]-##
3. **Create file**: Use SIMA v3 template
4. **Update index**: Add to project index
5. **Cross-reference**: Link from NM/AWS if relevant

### Example Workflow

```
1. Write code in Lambda project
2. Discover project-specific lesson
3. Check: Generic (NM) or project-specific (NMP)?
4. Create NMP01-LEE-02.md with lesson
5. Update NMP01-LEE_Index.md
6. Link from generic lesson in NM if applicable
```

---

## 📚 Complete File Listing

### NMP01 - Lambda Execution Engine

**Project Index:** `NMP01/NMP01-LEE_Index.md`

#### Architecture Lessons
1. **NMP01-LEE-01**: HA Cache Functions - Application vs Infrastructure
   - Application logic vs infrastructure separation
   - ISP principle in practice
   - Scalability through proper layering

---

## 🔗 Navigation Links

**Quick Index:** NMP00-Quick_Index.md (keyword search)  
**Base Neural Maps:** NM00A-Master_Index.md  
**AWS Maps:** AWS00A-Master_Index.md  

**Project Indexes:**
- NMP01: Lambda Execution Engine (`NMP01/NMP01-LEE_Index.md`)

---

## âš ï¸ Important Notes

### File Size Limits
- Individual files: < 200 lines
- Project indexes: < 300 lines
- Master index: < 400 lines

### Naming Convention
```
Format: NMP##-[PROJECT]-##.md

Examples:
✅ NMP01-LEE-01.md (Lambda Execution Engine, file 1)
✅ NMP01-LEE-15.md (Lambda Execution Engine, file 15)
✅ NMP02-WEATHER-01.md (Weather Service, file 1)

Project codes:
- LEE = Lambda Execution Engine
- WEATHER = Weather Service Lambda
- IOT = IoT Device Manager
- ANALYTICS = Analytics Pipeline
```

---

## 📊 Health Metrics

**Current Health:** âœ… Excellent

- âœ… Structure established
- âœ… First file created
- âœ… Indexes complete
- âœ… Integration with NM complete
- âœ… Quality standards met

**Readiness:** Ready for growth

---

**End of Master Index**

**Version:** 1.0.0  
**Last Updated:** 2025-10-26  
**Next Review:** When NMP01 reaches 10 files

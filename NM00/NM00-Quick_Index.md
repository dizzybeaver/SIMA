# NMP00-Quick_Index.md

# Neural Maps Project-Specific - Quick Index

**Version:** 1.0.0  
**Date:** 2025-10-26  
**Purpose:** Fast keyword routing for project-specific knowledge  
**Projects:** 1 active (Lambda Execution Engine)

---

## ðŸš€ Quick Access

**Looking for:**
- Generic patterns? → Use **NM00-Quick_Index.md** (base neural maps)
- AWS-specific? → Use **AWS00-Quick_Index.md** (AWS neural maps)
- Project-specific? → You're in the right place!

---

## ðŸ"Š Architecture Overview

**NMP = Neural Maps Project-specific**

Project-specific knowledge that doesn't transfer across projects. Each project gets ONE folder (NMP01, NMP02, etc.).

**Current Projects:**
- **NMP01**: Lambda Execution Engine (SUGA-ISP)

---

## ðŸ" Search by Keyword

### Application Logic
- **application-vs-infrastructure** → NMP01-LEE-01
- **cache-strategies** → NMP01-LEE-01
- **domain-knowledge** → NMP01-LEE-01

### Home Assistant
- **ha-caching** → NMP01-LEE-01
- **entity-invalidation** → NMP01-LEE-01
- **domain-invalidation** → NMP01-LEE-01
- **cache-warming** → NMP01-LEE-01

### Architecture Patterns
- **separation-of-concerns** → NMP01-LEE-01
- **ISP-principle** → NMP01-LEE-01
- **layer-separation** → NMP01-LEE-01

---

## ðŸ—‚ï¸ Browse by Project

### NMP01: Lambda Execution Engine

**Master Index:** `NMP01/NMP01-LEE_Index.md`

**Files:** 1
- NMP01-LEE-01: HA Cache Functions - Application vs Infrastructure

**Topics:**
- Architecture Lessons
- Design Patterns
- Integration Examples

---

## ðŸ"— Navigation

- **Master Index:** NMP00A-Master_Index.md
- **Base Neural Maps:** NM00-Quick_Index.md
- **AWS Maps:** AWS00-Quick_Index.md

---

## âš¡ What Belongs in NMP?

**YES - Project-Specific:**
- Implementation details unique to this project
- Project-specific design decisions
- Integration patterns for this project
- Lessons from this specific codebase

**NO - Use NM Instead:**
- Generic architectural patterns (SUGA, SIMA)
- Universal lessons (LESS-##)
- Generic anti-patterns (AP-##)
- Transferable principles

**NO - Use AWS Instead:**
- AWS service-specific knowledge
- Lambda-specific but transferable patterns
- AWS best practices

---

## ðŸ"„ File Structure

```
nmap/NMP/
├── NMP00/
│   ├── NMP00-Quick_Index.md (this file)
│   └── NMP00A-Master_Index.md
│
└── NMP01/ (Lambda Execution Engine)
    ├── NMP01-LEE_Index.md
    └── NMP01-LEE-01.md (Application vs Infrastructure)
```

**One folder per project** - flat structure, no sub-categories like NM.

---

## 🎯 Key Differences: NM vs AWS vs NMP

### NM (Neural Maps - Generic)
**Path:** `nmap/NM##/`  
**Structure:** Multi-category hierarchy (NM01-NM07)  
**Content:** Transferable across all projects  
**Example:** SUGA pattern, anti-patterns, generic lessons

### AWS (Amazon Web Services - Cloud Provider)
**Path:** `nmap/AWS/AWS##/`  
**Structure:** Mirrors NM hierarchy (AWS01-AWS07)  
**Content:** AWS service-specific, transferable across AWS projects  
**Example:** Lambda optimization, DynamoDB patterns, SSM usage

### NMP (Neural Maps Project - This Project Only)
**Path:** `nmap/NMP/NMP##/`  
**Structure:** Flat per project (NMP01, NMP02, ...)  
**Content:** Specific to one project, adapted if reused elsewhere  
**Example:** LEE HA caching, this project's integration patterns

---

**End of Quick Index**

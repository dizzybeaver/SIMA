# NM00-Quick_Index.md

# Neural Maps - Quick Index (All Systems)

**Version:** 2.0.0  
**Date:** 2025-10-26  
**Purpose:** Fast keyword routing across all three knowledge systems  
**Systems:** NM (Generic), AWS (Cloud), NMP (Project)  
**Routing Time:** < 5 seconds

---

## âš¡ System Selection Guide

**Which system should I search?**

| Question Type | System | Index File |
|--------------|--------|------------|
| Generic pattern, architecture question | NM## | This file |
| AWS/Lambda/Serverless question | AWS## | AWS00-Quick_Index.md |
| LEE project implementation question | NMP## | NMP00-Quick_Index.md |
| "Can I use X?" | NM## | This file (check anti-patterns) |
| "How to optimize Lambda?" | AWS## | AWS00-Quick_Index.md |
| "How does HA caching work?" | NMP## | NMP00-Quick_Index.md |

**Pro tip:** When in doubt, start here (NM) for generic patterns, then cross-reference to AWS/NMP for specifics.

---

## ðŸ"‹ KEYWORD TABLES (NM - Generic)

### Table 1: Core Architecture

| Keyword | Category | REF-ID | Cross-Reference |
|---------|----------|--------|-----------------|
| SUGA | NM01 | ARCH-01, DEC-01 | NMP01-LEE-01 (example) |
| gateway | NM01 | ARCH-01, ARCH-02 | AWS-LESS-01 (optimization) |
| interface | NM01 | INT-01 to INT-12 | NMP01-LEE-01 (usage) |
| ISP | NM01 | ARCH-01, INT-* | NMP01-LEE-01 (example) |
| LMMS | NM01 | ARCH-07 | AWS-LESS-01, AWS-LESS-05 |
| layer separation | NM01 | ARCH-01, DEC-01 | NMP01-LEE-01 (example) |

### Table 2: Interfaces

| Keyword | Category | REF-ID | Cross-Reference |
|---------|----------|--------|-----------------|
| cache, caching | NM01 | INT-01 | NMP01-LEE-01 (HA caching), AWS-LESS-01 |
| logging | NM01 | INT-02 | - |
| security | NM01 | INT-03 | - |
| metrics | NM01 | INT-04 | - |
| config | NM01 | INT-05 | AWS-LESS-05 (SSM) |
| validation | NM01 | INT-06 | - |
| persistence | NM01 | INT-07 | AWS-LESS-07 (DynamoDB) |
| communication | NM01 | INT-08 | AWS-LESS-03 (API Gateway) |
| transformation | NM01 | INT-09 | AWS-LESS-10 (transformations) |
| scheduling | NM01 | INT-10 | AWS-LESS-08 (EventBridge) |
| monitoring | NM01 | INT-11 | - |
| error handling | NM01 | INT-12 | - |

### Table 3: Dependencies & Imports

| Keyword | Category | REF-ID | Cross-Reference |
|---------|----------|--------|-----------------|
| import | NM02 | RULE-01 to RULE-04 | - |
| circular dependency | NM02 | RULE-01, AP-01 | - |
| gateway-only imports | NM02 | RULE-01 | - |
| layer boundaries | NM02 | DEP-01 to DEP-05 | - |
| dependency rules | NM02 | DEP-* | - |

### Table 4: Operations & Flows

| Keyword | Category | REF-ID | Cross-Reference |
|---------|----------|--------|-----------------|
| cold start | NM03 | PATH-01 | AWS-LESS-01 (optimization), ARCH-07 |
| execution flow | NM03 | FLOW-01 | - |
| error handling | NM03 | ERR-01 | INT-12 |
| tracing | NM03 | TRACE-01 | - |
| pathways | NM03 | PATH-* | - |

### Table 5: Decisions

| Keyword | Category | REF-ID | Cross-Reference |
|---------|----------|--------|-----------------|
| why SUGA | NM04 | DEC-01 | NMP01-LEE-01 (example) |
| why no threading | NM04 | DEC-04, AP-08 | AWS-LESS-11 (concurrency) |
| why sanitize sentinels | NM04 | DEC-05, BUG-01 | AWS-LESS-10 (boundaries) |
| SSM vs environment | NM04 | DEC-21 | AWS## (SSM patterns) |
| configuration strategy | NM04 | DEC-20 to DEC-23 | - |

### Table 6: Anti-Patterns (What NOT to Do)

| Keyword | Category | REF-ID | Cross-Reference |
|---------|----------|--------|-----------------|
| direct core imports | NM05 | AP-01, RULE-01 | - |
| threading locks | NM05 | AP-08, DEC-04 | AWS-LESS-11 |
| sentinel objects | NM05 | AP-10, BUG-01 | AWS-LESS-10 |
| bare except | NM05 | AP-14 | - |
| heavy libraries | NM05 | AP-* | AWS-LESS-06 (layers) |

### Table 7: Lessons Learned

| Keyword | Category | REF-ID | Cross-Reference |
|---------|----------|--------|-----------------|
| ISP principle | NM06 | LESS-08 | NMP01-LEE-01 (HA caching) |
| measure before optimize | NM06 | WISD-02 | AWS-LESS-01, AWS-LESS-05 |
| sentinel leak bug | NM06 | BUG-01 | DEC-05, AP-10 |
| application vs infrastructure | NM06 | LESS-* | NMP01-LEE-01 (perfect example) |

### Table 8: Workflows & Decision Trees

| Keyword | Category | REF-ID | Cross-Reference |
|---------|----------|--------|-----------------|
| add feature | NM07 | FW-01, DT-01 | - |
| interface selection | NM07 | DT-01 | INT-* |
| modify code | NM07 | FW-02 | - |
| when to create neural map | NM07 | META-01 | - |

---

## ðŸŒ² DECISION TREES (NM - Generic)

### Tree 1: "Can I use X feature?"

```
1. Check anti-patterns:
   â"œâ"€> Threading locks? â†' AP-08, DEC-04 (NO - Lambda single-threaded)
   â"œâ"€> Direct core imports? â†' AP-01, RULE-01 (NO - use gateway)
   â"œâ"€> Bare except? â†' AP-14 (NO - use specific exceptions)
   â""â"€> Heavy library? â†' Check size limits, AP-* (MAYBE - justify)

2. Check decisions:
   â""â"€> NM04 (why certain things were chosen/avoided)

3. Cross-reference AWS:
   â""â"€> AWS constraints? â†' AWS00-Quick_Index.md

4. Check project examples:
   â""â"€> How is it used? â†' NMP00-Quick_Index.md
```

### Tree 2: "How do I implement Y?"

```
1. Determine interface:
   â""â"€> NM01/INT-* or NM07/DT-01 (interface selection tree)

2. Check dependencies:
   â""â"€> NM02/RULE-01 (gateway-only imports)

3. Review patterns:
   â"œâ"€> Generic: NM01/ARCH-* (architecture patterns)
   â"œâ"€> AWS-specific: AWS00-Quick_Index.md
   â""â"€> Project example: NMP00-Quick_Index.md

4. Follow workflow:
   â""â"€> NM07/FW-01 (add feature workflow)

5. Verify against anti-patterns:
   â""â"€> NM05/AP-* (what to avoid)
```

### Tree 3: "Why was Z decided?"

```
1. Check decisions:
   â""â"€> NM04/DEC-* (design rationale)

2. Check lessons:
   â""â"€> NM06/LESS-*, WISD-* (what was learned)

3. Cross-reference:
   â"œâ"€> AWS influence? â†' AWS00-Quick_Index.md
   â""â"€> Project context? â†' NMP00-Quick_Index.md
```

---

## 🔗 Cross-System Navigation

### To AWS Knowledge

**When to use AWS index:**
- Lambda optimization questions
- Serverless architecture patterns
- API Gateway integration
- AWS service best practices
- DynamoDB, EventBridge, Step Functions

**How to get there:**
- `AWS00-Quick_Index.md` (keyword routing)
- `AWS00-Master_Index.md` (complete navigator)

### To Project Knowledge

**When to use NMP index:**
- LEE implementation specifics
- Home Assistant integration
- Project-specific design decisions
- Real-world implementation examples
- "How is X actually used in our project?"

**How to get there:**
- `NMP00-Quick_Index.md` (keyword routing)
- `NMP00A-Master_Index.md` (complete navigator)

---

## ðŸ"Š System Comparison

### Knowledge Scope

| System | Scope | Examples |
|--------|-------|----------|
| **NM##** | Universal, transferable | SUGA pattern, ISP, anti-patterns |
| **AWS##** | AWS-specific, transferable | Lambda optimization, API Gateway |
| **NMP##** | Project-specific | LEE HA caching, this project's decisions |

### Use Pattern

```
GENERIC QUESTION
  â†' Start: NM## (this index)
  â†' Example: "What is ISP?" â†' LESS-08

AWS QUESTION
  â†' Start: AWS## (AWS00-Quick_Index.md)
  â†' Example: "How to optimize Lambda?" â†' AWS-LESS-01

PROJECT QUESTION
  â†' Start: NMP## (NMP00-Quick_Index.md)
  â†' Example: "How does HA caching work?" â†' NMP01-LEE-01

BUILDING FEATURE
  â†' Use all three:
     1. NM## - Generic pattern
     2. AWS## - Cloud best practices
     3. NMP## - Project example
```

---

## ðŸš€ Fast Routing Examples

### Example 1: Caching Question

**Query:** "How should I implement caching for Home Assistant?"

**Routing:**
```
1. Generic interface: NM## â†' INT-01 (CACHE interface spec)
2. ISP principle: NM## â†' LESS-08 (interface segregation)
3. Lambda optimization: AWS## â†' AWS-LESS-01 (cold start)
4. Project example: NMP## â†' NMP01-LEE-01 (HA caching)
```

**Answer builds from:** INT-01 + LESS-08 + AWS-LESS-01 + NMP01-LEE-01

---

### Example 2: Import Error

**Query:** "Getting circular import error"

**Routing:**
```
1. Import rules: NM## â†' RULE-01 (gateway-only imports)
2. Anti-pattern: NM## â†' AP-01 (direct core imports)
3. Layer boundaries: NM## â†' DEP-01 to DEP-05
```

**Answer builds from:** RULE-01 + AP-01 + DEP-*

---

### Example 3: Performance Optimization

**Query:** "Lambda cold start is slow"

**Routing:**
```
1. Generic patterns: NM## â†' ARCH-07 (LMMS system)
2. Measure first: NM## â†' WISD-02 (measure before optimize)
3. AWS patterns: AWS## â†' AWS-LESS-01 (cold start optimization)
4. AWS memory: AWS## â†' AWS-LESS-05 (memory/CPU relationship)
```

**Answer builds from:** ARCH-07 + WISD-02 + AWS-LESS-01 + AWS-LESS-05

---

### Example 4: Design Decision

**Query:** "Why no threading locks?"

**Routing:**
```
1. Decision rationale: NM## â†' DEC-04 (threading decision)
2. Anti-pattern: NM## â†' AP-08 (threading in Lambda)
3. AWS context: AWS## â†' AWS-LESS-11 (Lambda concurrency)
```

**Answer builds from:** DEC-04 + AP-08 + AWS-LESS-11

---

## 📚 Top 20 Most-Referenced Items

### Across All Systems

| Rank | REF-ID | Title | System | Access Freq |
|------|--------|-------|--------|-------------|
| 1 | ARCH-01 | Gateway Trinity | NM | 95% |
| 2 | RULE-01 | Gateway-only imports | NM | 85% |
| 3 | INT-01 | CACHE interface | NM | 80% |
| 4 | DEC-04 | No threading locks | NM | 75% |
| 5 | AP-08 | Threading anti-pattern | NM | 75% |
| 6 | ARCH-07 | LMMS system | NM | 70% |
| 7 | LESS-08 | ISP Principle | NM | 65% |
| 8 | AWS-LESS-01 | Lambda cold start | AWS | 65% |
| 9 | BUG-01 | Sentinel leak | NM | 60% |
| 10 | DEC-05 | Sanitize sentinels | NM | 60% |
| 11 | AWS-LESS-09 | Proxy integration | AWS | 55% |
| 12 | INT-02 | LOGGING interface | NM | 50% |
| 13 | AP-01 | Direct core imports | NM | 50% |
| 14 | NMP01-LEE-01 | HA caching example | NMP | 45% |
| 15 | AWS-LESS-05 | Memory/CPU | AWS | 45% |
| 16 | DEC-01 | Why SUGA | NM | 40% |
| 17 | AWS-LESS-10 | Transformations | AWS | 40% |
| 18 | WISD-02 | Measure first | NM | 35% |
| 19 | INT-05 | CONFIG interface | NM | 35% |
| 20 | DEC-21 | SSM strategy | NM | 30% |

**Note:** Access frequency based on query patterns and cross-references

---

## ⚡ ZAPH Fast Path (Top 10 Instant Answers)

For ultra-fast routing of most common questions:

| # | Question Pattern | Direct Answer | REF-ID |
|---|------------------|---------------|--------|
| 1 | "Can I use threading locks?" | NO - Lambda single-threaded | DEC-04, AP-08 |
| 2 | "How to import?" | Via gateway only | RULE-01 |
| 3 | "Getting circular import" | Use gateway pattern | RULE-01, AP-01 |
| 4 | "What is SUGA?" | Gateway→Interface→Core | ARCH-01 |
| 5 | "Which interface for X?" | See interface decision tree | DT-01, INT-* |
| 6 | "Cold start slow?" | Check LMMS system | ARCH-07, AWS-LESS-01 |
| 7 | "Sentinel object issue?" | Sanitize at boundaries | DEC-05, BUG-01 |
| 8 | "HA caching example?" | See project neural map | NMP01-LEE-01 |
| 9 | "Lambda optimization?" | See AWS patterns | AWS-LESS-01, 05, 11 |
| 10 | "Why no [X]?" | Check decisions/anti-patterns | NM04/*, NM05/* |

---

## 🔍 Search Strategy

### Level 1: This Quick Index (90% of queries)
1. Look up keyword in tables above
2. Navigate to REF-ID
3. Check cross-references if needed
**Time: ~5 seconds**

### Level 2: System-Specific Quick Index (8% of queries)
1. Determine system (NM/AWS/NMP)
2. Use system-specific quick index
3. Follow system routing
**Time: ~10 seconds**

### Level 3: Master Index (2% of queries)
1. Use NM00A-Master_Index.md
2. Browse category
3. Review detailed content
**Time: ~20 seconds**

---

## 📖 Related Navigation

**This file:** NM00-Quick_Index.md (NM Generic - Fast Routing)  
**Master Index:** NM00A-Master_Index.md (All Systems)  
**AWS Quick:** AWS00-Quick_Index.md (AWS-Specific Routing)  
**NMP Quick:** NMP00-Quick_Index.md (Project-Specific Routing)

---

**End of Quick Index**

**Version:** 2.0.0  
**Major Update:** Integrated three-tier system (NM/AWS/NMP)  
**Last Updated:** 2025-10-26  
**Coverage:** 100% of NM content + cross-references to AWS/NMP  
**Average Routing Time:** ~5 seconds

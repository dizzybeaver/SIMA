# NM00-Quick_Index.md

# Quick Index - Fast Keyword Routing

**Type:** Gateway Layer  
**Purpose:** Route 90% of queries in < 5 seconds  
**Last Updated:** 2025-10-25 (added AWS routing)  
**Total REF-IDs:** 180+ (168 NM + 12 AWS)

---

## ⚡ How to Use This Index

**If user asks about:**
1. Look up keyword in tables below
2. Route to NM (project-specific) or AWS (universal patterns)
3. Go to indicated category/topic/REF-ID
4. Read complete section
5. Respond with full context

**Routing Speed:** ~5 seconds for known keywords

---

## 🔀 NM vs AWS ROUTING

**Use NM maps when:**
- Project-specific implementation questions
- SUGA architecture details
- Internal decisions and bugs
- "How do WE do X?"

**Use AWS maps when:**
- Universal serverless patterns
- Industry best practices
- AWS-specific optimization
- "How is X done generally?"

**Use BOTH when:**
- Need complete understanding
- Validating project approach
- Learning + implementing

---

## 📋 KEYWORD TABLES

### Table 0: AWS/External Knowledge Routing

| Keyword | Route To | REF-ID | Notes |
|---------|----------|--------|-------|
| AWS | AWS00-Quick_Index | (all) | External knowledge gateway |
| serverless patterns | AWS00-Quick_Index | AWS-LESS-* | Universal patterns |
| lambda optimization | AWS00-Quick_Index | AWS-LESS-01, 05 | Then see NM01/ARCH-07 |
| cold start generic | AWS00-Quick_Index | AWS-LESS-01 | Universal approach |
| proxy integration | AWS00-Quick_Index | AWS-LESS-09 | Integration patterns |
| API transformation | AWS00-Quick_Index | AWS-LESS-10 | Boundary patterns |
| DynamoDB patterns | AWS00-Quick_Index | AWS-LESS-07 | Data access |
| Step Functions | AWS00-Quick_Index | AWS-LESS-12 | Orchestration |
| EventBridge | AWS00-Quick_Index | AWS-LESS-08 | Event patterns |

### Table 1: Architecture & Patterns (NM)

| Keyword | Category | Topic | REF-ID |
|---------|----------|-------|--------|
| gateway | NM01 | Core Architecture | ARCH-01, ARCH-02 |
| SUGA | NM01 | Core Architecture | ARCH-01, DEC-01 |
| ISP | NM01 | Core Architecture | ARCH-01 |
| trinity | NM01 | Core Architecture | ARCH-01 |
| execution engine | NM01 | Core Architecture | ARCH-02 |
| router pattern | NM01 | Core Architecture | ARCH-03 |
| dispatch | NM01 | Core Architecture | ARCH-02, ARCH-03 |
| LMMS | NM01 | Core Architecture | ARCH-07 |
| LIGS | NM01 | Core Architecture | ARCH-07 |
| LUGS | NM01 | Core Architecture | ARCH-07 |
| ZAPH | NM01 | Core Architecture | ARCH-07 |
| lazy loading | NM01 | Core Architecture | ARCH-07 |
| cold start (project) | NM03 | Pathways | PATH-01, see AWS-LESS-01 |
| fast path | NM01 | Core Architecture | ARCH-07 |

### Table 2: Interfaces (NM)

| Keyword | Category | Topic | REF-ID |
|---------|----------|-------|--------|
| cache | NM01 | Interfaces-Core | INT-01 |
| logging | NM01 | Interfaces-Core | INT-02 |
| security | NM01 | Interfaces-Core | INT-03 |
| metrics | NM01 | Interfaces-Core | INT-04 |
| config | NM01 | Interfaces-Core | INT-05 |
| singleton | NM01 | Interfaces-Core | INT-06 |
| initialization | NM01 | Interfaces-Advanced | INT-07 |
| HTTP | NM01 | Interfaces-Advanced | INT-08 |
| websocket | NM01 | Interfaces-Advanced | INT-09 |
| circuit breaker | NM01 | Interfaces-Advanced | INT-10 |
| utility | NM01 | Interfaces-Advanced | INT-11 |
| debug | NM01 | Interfaces-Advanced | INT-12 |

### Table 3: Import & Dependencies (NM)

| Keyword | Category | Topic | REF-ID |
|---------|----------|-------|--------|
| import | NM02 | Import Rules | RULE-01 to RULE-05 |
| circular | NM02 | Import Rules | RULE-02, AP-02 |
| dependency | NM02 | Dependency Layers | DEP-01 to DEP-05 |
| cross-interface | NM02 | Import Rules | RULE-01 |
| gateway only | NM02 | Import Rules | RULE-01 |
| direct import | NM05 | Anti-Patterns | AP-01 |
| base layer | NM02 | Dependency Layers | DEP-01 |
| layer | NM02 | Dependency Layers | DEP-01 to DEP-05 |

### Table 4: Operations & Flows (NM)

| Keyword | Category | Topic | REF-ID |
|---------|----------|-------|--------|
| operation | NM03 | Operations | FLOW-01 to FLOW-03 |
| pathway | NM03 | Pathways | PATH-01 to PATH-05 |
| flow | NM03 | Flows | FLOW-01 to FLOW-03 |
| error | NM03 | Error Handling | ERROR-01 to ERROR-03 |
| propagation | NM03 | Error Handling | ERROR-02 |
| graceful | NM03 | Error Handling | ERROR-02 |
| degradation | NM03 | Error Handling | ERROR-02 |
| trace | NM03 | Operations | TRACE-01, TRACE-02 |

### Table 5: Decisions & Rationale (NM)

| Keyword | Category | Topic | REF-ID |
|---------|----------|-------|--------|
| why | NM04 | Decisions | (check specific topic) |
| decision | NM04 | Decisions | DEC-01 to DEC-23+ |
| rationale | NM04 | Decisions | (check specific topic) |
| threading | NM04 | Technical | DEC-04 |
| locks | NM04 | Technical | DEC-04 |
| sentinel | NM04 | Architecture | DEC-05, see AWS-LESS-10 |
| sanitization | NM04 | Architecture | DEC-05 |
| flat file | NM04 | Architecture | DEC-08 |
| subdirectories | NM04 | Architecture | DEC-08 |
| SSM | NM04 | Operational | DEC-21 |
| token-only | NM04 | Operational | DEC-21 |
| 128MB | NM04 | Technical | DEC-07 |
| dependencies | NM04 | Technical | DEC-07 |

### Table 6: Anti-Patterns & RED FLAGS (NM)

| Keyword | Category | Topic | REF-ID |
|---------|----------|-------|--------|
| anti-pattern | NM05 | Anti-Patterns | AP-01 to AP-28 |
| never do | NM05 | Anti-Patterns | (see RED FLAGS) |
| prohibited | NM05 | Anti-Patterns | (see RED FLAGS) |
| bare except | NM05 | Error Handling | AP-14 |
| swallow exception | NM05 | Error Handling | AP-15 |
| logging sensitive | NM05 | Security | AP-24 |
| skip verification | NM05 | Process | AP-27 |
| not reading complete | NM05 | Process | AP-28 |

### Table 7: Bugs & Issues (NM)

| Keyword | Category | Topic | REF-ID |
|---------|----------|-------|--------|
| bug | NM06 | Bugs | BUG-01 to BUG-04 |
| sentinel leak | NM06 | Bugs | BUG-01 |
| 535ms | NM06 | Bugs | BUG-01 |
| CacheMiss | NM06 | Bugs | BUG-02 |
| cascading failure | NM06 | Bugs | BUG-03 |

### Table 8: Lessons & Best Practices (NM)

| Keyword | Category | Topic | REF-ID |
|---------|----------|-------|--------|
| lesson | NM06 | Lessons | LESS-01 to LESS-54 |
| best practice | NM06 | Lessons/Wisdom | (multiple) |
| verification | NM06 | Lessons-Operations | LESS-15 |
| 5-step | NM06 | Lessons-Operations | LESS-15 |
| read complete | NM06 | Lessons-Core | LESS-01 |
| measure | NM06 | Lessons-Core | LESS-02 |

### Table 9: Decision Logic (NM)

| Keyword | Category | Topic | REF-ID |
|---------|----------|-------|--------|
| how to | NM07 | Decision Logic | (check specific) |
| where to put | NM07 | Decision Logic | DT-02 |
| add feature | NM07 | Feature Addition | DT-03 |
| should I cache | NM07 | Feature Addition | DT-04 |
| handle error | NM07 | Error Handling | DT-05 |
| should I optimize | NM07 | Optimization | DT-07 |
| what to test | NM07 | Testing | DT-08 |

---

## 🌲 DECISION TREES (Common Patterns)

### Tree 0: AWS vs NM Routing

**Pattern:** Determine if question is project-specific or universal

```
Query Analysis:
├─> Contains "how does SUGA/our project" → NM maps
├─> Contains "AWS best practice/industry pattern" → AWS maps
├─> Contains "Lambda/DynamoDB/EventBridge generic" → AWS maps first
├─> Contains "our implementation/why we chose" → NM maps
└─> Contains "optimization generic" → AWS maps, then NM for implementation

Result: Route to appropriate gateway
```

### Tree 1: "Why no [X]?" Queries (NM)

**Pattern:** User asks why something is prohibited

```
1. Check RED FLAGS section (below)
2. If in RED FLAGS → Provide explanation + REF-ID
3. If not in RED FLAGS:
   └─> Search NM05 (Anti-Patterns)
   └─> Search NM04 (Decisions)
4. Return: Complete rationale + alternatives
```

### Tree 2: "Can I [X]?" Queries (NM)

**Pattern:** User asks if something is allowed

```
1. Check RED FLAGS first (immediate NO if matches)
2. If RED FLAG → NO with explanation + alternative
3. If not RED FLAG:
   └─> Search NM05 (Anti-Patterns)
   └─> Search NM04 (Decisions)
4. Return: YES/NO + reasoning + best practice
```

### Tree 3: "How do I [X]?" Queries (Both)

**Pattern:** User asks implementation guidance

```
1. Determine scope:
   ├─> Universal pattern? → AWS maps first (AWS-LESS-*)
   ├─> Project implementation? → NM maps
   └─> Both needed? → AWS for pattern, NM for implementation

2. If AWS: Route to AWS00-Quick_Index
3. If NM: Continue normal routing (INT, DT, etc.)
4. If Both: Provide AWS pattern + NM implementation
```

### Tree 4: "What happened with [X]?" Queries (NM)

**Pattern:** User references bugs or past issues

```
1. Search NM06-Bugs (BUG-01 to BUG-04)
2. If found:
   └─> Return: Bug details + root cause + fix + REF-ID
3. If not found:
   └─> Search NM06-Lessons (similar issues)
4. Return: Complete context + prevention tips
```

### Tree 5: Architecture Questions (Both)

**Pattern:** User asks about structure or design

```
1. Determine scope:
   ├─> SUGA/project-specific? → NM01
   ├─> Serverless patterns generic? → AWS maps
   └─> Both? → AWS for pattern, NM01 for our implementation

2. Route accordingly
3. Provide cross-references if applicable
```

---

## 🚨 RED FLAGS (Never Suggest These)

[Same as before - no changes needed to this section]

---

## 🎯 TOP 20 MOST ACCESSED (ZAPH Tier 1)

**Critical References (Access 50+ times/30 days):**

[Same as before - all NM references]

**For AWS patterns, see:** AWS00-Quick_Index.md (Top priorities section)

---

## 📂 CATEGORY QUICK REFERENCE

### NM Categories (Project-Specific)

[Same as before - NM01 through NM07]

### AWS Categories (External Knowledge)

**AWS06 - Serverless Patterns**  
**Purpose:** Universal Lambda, API Gateway, serverless patterns  
**When to use:** Industry best practices, AWS optimization, external validation  
**Access:** AWS00-Quick_Index.md → AWS06  
**Top items:** AWS-LESS-09 (proxy), AWS-LESS-10 (transformation), AWS-LESS-01 (cold start)

---

## ⚡ FAST PATHS (Common Queries)

### Instant Answers (No search needed)

**"How does cache work?"** (NM)  
→ NM01-Interfaces-Core_INT-01.md

**"How to optimize Lambda cold start?"** (Both)  
→ AWS-LESS-01 (universal approach)  
→ NM01/ARCH-07 (our LMMS implementation)

**"Why no threading?"** (NM)  
→ NM04-Decisions-Technical_DEC-04.md

**"Proxy or non-proxy integration?"** (AWS)  
→ AWS-LESS-09 (decision framework)  
→ See NM04 for our choice

**"How to transform API data?"** (Both)  
→ AWS-LESS-10 (universal strategies)  
→ NM04/DEC-05 (our sentinel sanitization)

**"How to avoid sentinel leak?"** (NM)  
→ NM06-Bugs-Critical_BUG-01.md + DEC-05  
→ Also see AWS-LESS-10 for transformation best practices

[Rest of fast paths same as before]

---

## 🔍 SEARCH STRATEGY

**Level 1: This Quick Index (90% of queries)**
1. Check if AWS or NM question
2. Look up keyword in appropriate tables
3. Follow routing to category/REF-ID
4. Check cross-references if needed
**Time: ~5 seconds**

**Level 2: Master Indexes (8% of queries)**
1. If keyword not in Quick Index
2. Search NM00A-Master_Index.md or AWS00-Master_Index.md
3. Navigate to detailed file
**Time: ~10-15 seconds**

**Level 3: Direct Search (2% of queries)**
1. If not in Quick or Master
2. Search specific category files
3. Deep dive into content
**Time: ~20-30 seconds**

---

## 📝 MAINTENANCE NOTES

**When to Update:**
- New common query pattern identified (NM or AWS)
- New REF-ID becomes frequently accessed
- New category added
- RED FLAGS change
- Top 20 list changes (ZAPH updates)
- AWS maps added or modified

**Update Process:**
1. Add keyword to appropriate table (Table 0 for AWS)
2. Update decision trees if new pattern
3. Update routing logic (AWS vs NM)
4. Update fast paths if common enough
5. Ensure cross-references are bidirectional

---

**Navigation:**
- **NM Master Index:** NM00A-Master_Index.md (complete NM map)
- **AWS Master Index:** AWS00-Master_Index.md (complete AWS map)
- **ZAPH:** NM00B-ZAPH.md (hot path with embedded content)

---

**End of Quick Index**

**Total Lines:** ~420 (added AWS routing, within limits)  
**Coverage:** ~90% of NM queries + AWS gateway routing  
**Average Routing Time:** ~5 seconds

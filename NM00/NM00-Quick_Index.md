# NM00-Quick_Index.md

# Quick Index - Fast Keyword Routing

**Type:** Gateway Layer  
**Purpose:** Route 90% of queries in < 5 seconds  
**Last Updated:** 2025-10-24  
**Total REF-IDs:** 159+

---

## ⚡ How to Use This Index

**If user asks about:**
1. Look up keyword in tables below
2. Go to indicated category/topic
3. Find specific REF-ID if listed
4. Read complete section
5. Respond with full context

**Routing Speed:** ~5 seconds for known keywords

---

## 📋 KEYWORD TABLES

### Table 1: Architecture & Patterns

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
| cold start | NM03 | Pathways | PATH-01 |
| fast path | NM01 | Core Architecture | ARCH-07 |

### Table 2: Interfaces

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

### Table 3: Import & Dependencies

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

### Table 4: Operations & Flows

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

### Table 5: Decisions & Rationale

| Keyword | Category | Topic | REF-ID |
|---------|----------|-------|--------|
| why | NM04 | Decisions | (check specific topic) |
| decision | NM04 | Decisions | DEC-01 to DEC-23+ |
| rationale | NM04 | Decisions | (check specific topic) |
| threading | NM04 | Technical | DEC-04 |
| locks | NM04 | Technical | DEC-04 |
| sentinel | NM04 | Architecture | DEC-05 |
| sanitization | NM04 | Architecture | DEC-05 |
| flat file | NM04 | Architecture | DEC-08 |
| subdirectories | NM04 | Architecture | DEC-08 |
| SSM | NM04 | Operational | DEC-21 |
| token-only | NM04 | Operational | DEC-21 |
| 128MB | NM04 | Technical | DEC-07 |
| dependencies | NM04 | Technical | DEC-07 |

### Table 6: Anti-Patterns & RED FLAGS

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

### Table 7: Bugs & Issues

| Keyword | Category | Topic | REF-ID |
|---------|----------|-------|--------|
| bug | NM06 | Bugs | BUG-01 to BUG-04 |
| sentinel leak | NM06 | Bugs | BUG-01 |
| 535ms | NM06 | Bugs | BUG-01 |
| CacheMiss | NM06 | Bugs | BUG-02 |
| cascading failure | NM06 | Bugs | BUG-03 |

### Table 8: Lessons & Best Practices

| Keyword | Category | Topic | REF-ID |
|---------|----------|-------|--------|
| lesson | NM06 | Lessons | LESS-01 to LESS-21 |
| best practice | NM06 | Lessons/Wisdom | (multiple) |
| verification | NM06 | Lessons-2025.10.20 | LESS-15 |
| 5-step | NM06 | Lessons-2025.10.20 | LESS-15 |
| read complete | NM06 | Lessons-Core | LESS-01 |
| measure | NM06 | Lessons-Core | LESS-02 |
| file headers | NM06 | Lessons-Recent | LESS-XX |

### Table 9: Decision Logic

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

### Tree 1: "Why no [X]?" Queries

**Pattern:** User asks why something is prohibited

```
1. Check RED FLAGS section (below)
2. If in RED FLAGS → Provide explanation + REF-ID
3. If not in RED FLAGS:
   └─> Search NM05 (Anti-Patterns)
   └─> Search NM04 (Decisions)
4. Return: Complete rationale + alternatives
```

**Examples:**
- "Why no threading locks?" → DEC-04 (Lambda single-threaded)
- "Why no direct imports?" → RULE-01, AP-01 (circular imports)
- "Why no subdirectories?" → DEC-08 (proven simple)

### Tree 2: "Can I [X]?" Queries

**Pattern:** User asks if something is allowed

```
1. Check RED FLAGS first (immediate NO if matches)
2. If RED FLAG → NO with explanation + alternative
3. If not RED FLAG:
   └─> Search NM05 (Anti-Patterns)
   └─> Search NM04 (Decisions)
4. Return: YES/NO + reasoning + best practice
```

**Examples:**
- "Can I use threading locks?" → NO (AP-08, DEC-04)
- "Can I import cache_core directly?" → NO (AP-01, RULE-01)
- "Can I use bare except?" → NO (AP-14)
- "Can I skip verification?" → NO (AP-27, LESS-15)

### Tree 3: "How do I [X]?" Queries

**Pattern:** User asks implementation guidance

```
1. Identify operation type:
   ├─> Interface operation → NM01 (INT-##)
   ├─> Import question → NM07 (DT-01) + NM02 (RULE-01)
   ├─> Feature addition → NM07 (DT-03)
   └─> General pattern → NM07 (Decision Logic)
2. Search relevant category
3. Return: Implementation steps + examples + REF-IDs
```

**Examples:**
- "How do I cache data?" → INT-01 (CACHE interface)
- "How do I import X?" → DT-01, RULE-01
- "How do I add a feature?" → DT-03
- "How do I handle errors?" → DT-05, ERROR-01

### Tree 4: "What happened with [X]?" Queries

**Pattern:** User references bugs or past issues

```
1. Search NM06-Bugs (BUG-01 to BUG-04)
2. If found:
   └─> Return: Bug details + root cause + fix + REF-ID
3. If not found:
   └─> Search NM06-Lessons (similar issues)
4. Return: Complete context + prevention tips
```

**Examples:**
- "What happened with sentinel?" → BUG-01 (535ms leak)
- "What happened with CacheMiss?" → BUG-02 (validation)
- "What happened with cascading?" → BUG-03 (interface failures)

### Tree 5: Architecture Questions

**Pattern:** User asks about structure or design

```
1. Check keyword in Table 1 (Architecture)
2. Route to NM01 (Architecture):
   ├─> Gateway questions → ARCH-01, ARCH-02
   ├─> Interface questions → INT-## (1-12)
   └─> LMMS questions → ARCH-07
3. If "why" question → Also check NM04 (Decisions)
4. Return: Architecture details + rationale + examples
```

**Examples:**
- "How does gateway work?" → ARCH-01, ARCH-02
- "What interfaces exist?" → INT-01 to INT-12
- "Why SUGA pattern?" → DEC-01

---

## 🚨 RED FLAGS (Never Suggest These)

**Critical (Never Do):**

1. **Threading locks** - Lambda is single-threaded (DEC-04, AP-08)
   - Why: Lambda executes ONE invocation at a time per container
   - Alternative: Use sequential execution, Step Functions for parallelism

2. **Direct core imports** - Always use gateway (RULE-01, AP-01)
   - Why: Prevents circular imports, enables LIGS lazy loading
   - Alternative: `import gateway` then `gateway.operation()`

3. **Bare except clauses** - Use specific exceptions (AP-14, ERROR-02)
   - Why: Masks bugs, catches system exits
   - Alternative: `except SpecificException:`

4. **Sentinel objects crossing boundaries** - Sanitize at router (DEC-05, AP-19, BUG-01)
   - Why: 535ms performance penalty, validation failures
   - Alternative: Sanitize sentinels at router layer, return None

5. **Heavy libraries without justification** - 128MB limit (DEC-07, AP-06)
   - Why: Cold start impact, memory constraints
   - Alternative: Lightweight alternatives, lazy loading

6. **Subdirectories** - Keep flat structure except home_assistant/ (DEC-08, AP-05)
   - Why: Simple imports, proven at scale
   - Alternative: Flat file structure with clear naming

7. **Skipping verification protocol** - Always use 5-step checklist (LESS-15, AP-27)
   - Why: Prevents 90% of common mistakes
   - Alternative: Complete LESS-15 checklist before any change

8. **Modifying without reading complete file** - Read entire file first (LESS-01, AP-28)
   - Why: Missing context causes bugs
   - Alternative: Read complete file, understand dependencies

**When user suggests ANY of these:** 
→ Immediately explain why NO + provide better alternative + cite REF-IDs

---

## 🎯 TOP 20 MOST ACCESSED (ZAPH Tier 1)

**Critical References (Access 50+ times/30 days):**

1. **DEC-04**: No threading locks → NM04-Decisions-Technical_DEC-04.md
2. **RULE-01**: Gateway-only imports → NM02-Dependencies-Import_RULE-01.md
3. **BUG-01**: Sentinel leak → NM06-Bugs-Critical_BUG-01.md
4. **LESS-15**: 5-step verification → NM06-Lessons-Operations_LESS-15.md
5. **LESS-01**: Read complete files → NM06-Lessons-CoreArchitecture_LESS-01.md
6. **AP-01**: Direct cross-interface imports → NM05-AntiPatterns-Import_AP-01.md
7. **AP-08**: Threading primitives → NM05-AntiPatterns-Concurrency_AP-08.md
8. **AP-14**: Bare except clauses → NM05-AntiPatterns-ErrorHandling_AP-14.md
9. **DEC-01**: SUGA pattern choice → NM04-Decisions-Architecture_DEC-01.md
10. **INT-01**: CACHE interface → NM01-Interfaces-Core_INT-01.md
11. **ARCH-01**: Gateway trinity → NM01-Architecture-CoreArchitecture_ARCH-01.md
12. **ARCH-07**: LMMS system → NM01-Architecture-CoreArchitecture_ARCH-07.md
13. **LESS-02**: Measure don't guess → NM06-Lessons-Performance_LESS-02.md
14. **DEC-05**: Sentinel sanitization → NM04-Decisions-Architecture_DEC-05.md
15. **DEC-08**: Flat file structure → NM04-Decisions-Architecture_DEC-08.md
16. **BUG-02**: _CacheMiss validation → NM06-Bugs-Critical_BUG-02.md
17. **PATH-01**: Cold start pathway → NM03-Operations-Pathways_PATH-01.md
18. **ERROR-02**: Graceful degradation → NM03-Operations-ErrorHandling_ERROR-02.md
19. **DEC-21**: SSM token-only → NM04-Decisions-Operational_DEC-21.md
20. **AP-27**: Skipping verification → NM05-AntiPatterns-Process_AP-27.md

**Usage:** These are in ZAPH (NM00B-ZAPH.md) with complete embedded content for instant access

---

## 📂 CATEGORY QUICK REFERENCE

### NM01 - Architecture & Interfaces
**Purpose:** Core SUGA patterns and 12 interfaces  
**When to use:** Architecture questions, interface usage, LMMS/ZAPH  
**Top items:** ARCH-01, ARCH-07, INT-01, INT-02, INT-03  
**Index:** NM01-INDEX-Architecture.md

### NM02 - Dependencies & Import Rules
**Purpose:** Import rules and dependency layers  
**When to use:** Import questions, circular dependency issues  
**Top items:** RULE-01, DEP-01, RULE-02  
**Index:** NM02-INDEX-Dependencies.md

### NM03 - Operations & Pathways
**Purpose:** Operation flows and system pathways  
**When to use:** Understanding operations, error handling, tracing  
**Top items:** PATH-01, ERROR-01, ERROR-02  
**Index:** NM03-INDEX-Operations.md

### NM04 - Design Decisions
**Purpose:** Design decisions and rationale  
**When to use:** "Why" questions, understanding design choices  
**Top items:** DEC-04, DEC-01, DEC-05, DEC-08, DEC-21  
**Index:** NM04-INDEX-Decisions.md

### NM05 - Anti-Patterns
**Purpose:** What NOT to do  
**When to use:** Before suggesting solutions, "can I" questions  
**Top items:** AP-01, AP-08, AP-14, AP-27, AP-28  
**Index:** NM05-INDEX-AntiPatterns.md

### NM06 - Learned Experiences
**Purpose:** Bugs, lessons, wisdom  
**When to use:** Bug reports, learning from past, best practices  
**Top items:** BUG-01, BUG-02, LESS-15, LESS-01, LESS-02  
**Index:** NM06-INDEX-Learned.md

### NM07 - Decision Logic
**Purpose:** Decision trees and logic frameworks  
**When to use:** Implementation guidance, "how to" questions  
**Top items:** DT-01, DT-03, DT-05, DT-07  
**Index:** NM07-INDEX-DecisionLogic.md

---

## ⚡ FAST PATHS (Common Queries)

### Instant Answers (No search needed)

**"How does cache work?"**
→ NM01-Interfaces-Core_INT-01.md
Use: `gateway.cache_set(key, value, ttl)`, `gateway.cache_get(key)`

**"Why no threading?"**
→ NM04-Decisions-Technical_DEC-04.md
Reason: Lambda is single-threaded, locks add overhead with no benefit

**"How to avoid sentinel leak?"**
→ NM06-Bugs-Critical_BUG-01.md + DEC-05
Solution: Sanitize sentinels at router layer, never let them cross boundaries

**"What's the verification protocol?"**
→ NM06-Lessons-Operations_LESS-15.md
5 steps: Read complete, verify SUGA, check anti-patterns, verify deps, cite sources

**"How to import X?"**
→ NM07-DecisionLogic-Import_DT-01.md + RULE-01
Always: `import gateway` then use gateway functions

**"Can I use bare except?"**
→ NO - AP-14, use specific exceptions instead

**"What interfaces exist?"**
→ NM01-Interfaces-Core_INT-01 to INT-06 (Core: CACHE, LOGGING, SECURITY, METRICS, CONFIG, SINGLETON)
→ NM01-Interfaces-Advanced_INT-07 to INT-12 (Advanced: INITIALIZATION, HTTP, WEBSOCKET, CIRCUIT_BREAKER, UTILITY, DEBUG)

**"Why SUGA pattern?"**
→ NM04-Decisions-Architecture_DEC-01.md
Reason: Prevents circular imports, enables LIGS/LMMS, centralized control

**"How to add feature?"**
→ NM07-DecisionLogic-FeatureAddition_DT-03.md
Follow: Gateway → Interface → Implementation pattern

**"Why flat file structure?"**
→ NM04-Decisions-Architecture_DEC-08.md
Reason: Simple imports, proven at scale, no subdirectory complexity

**"What's LMMS?"**
→ NM01-Architecture-CoreArchitecture_ARCH-07.md
Lambda Memory Management System: LIGS + LUGS + ZAPH = 60% faster cold starts

**"How to handle errors?"**
→ NM07-DecisionLogic-ErrorHandling_DT-05.md + ERROR-01
Pattern: Specific exceptions, graceful degradation, log + handle

**"What's sentinel leak bug?"**
→ NM06-Bugs-Critical_BUG-01.md
Issue: 535ms performance penalty when sentinel objects cross boundaries

**"What's gateway trinity?"**
→ NM01-Architecture-CoreArchitecture_ARCH-01.md
Three files: gateway.py, gateway_core.py, gateway_wrappers.py

**"Why SSM token-only?"**
→ NM04-Decisions-Operational_DEC-21.md
Reason: Simplified config, reduced complexity, easier maintenance

---

## 🔍 SEARCH STRATEGY

**Level 1: This Quick Index (90% of queries)**
1. Look up keyword in tables above
2. Follow routing to category/REF-ID
3. Read section → respond
**Time: ~5 seconds**

**Level 2: Master Index (8% of queries)**
1. If keyword not in Quick Index
2. Search NM00A-Master_Index.md
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
- New common query pattern identified
- New REF-ID becomes frequently accessed
- New category added
- RED FLAGS change
- Top 20 list changes (ZAPH updates)

**Update Process:**
1. Add keyword to appropriate table
2. Update decision trees if new pattern
3. Update RED FLAGS if new prohibition
4. Update Top 20 if access patterns change
5. Add to fast paths if common enough

---

**Navigation:**
- **Master Index:** NM00A-Master_Index.md (complete system map)
- **ZAPH:** NM00B-ZAPH.md (hot path with embedded content)

---

**End of Quick Index**

**Total Lines:** ~375  
**Coverage:** ~90% of queries  
**Average Routing Time:** ~5 seconds

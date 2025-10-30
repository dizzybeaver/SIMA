# AWS00-Quick_Index.md

# AWS Quick Index - Fast Keyword Routing

**Type:** Gateway Layer  
**Purpose:** Route AWS knowledge queries in < 5 seconds  
**Last Updated:** 2025-10-25  
**Total REF-IDs:** 12

---

## ⚡ How to Use

**For AWS/serverless questions:**
1. Look up keyword in tables
2. Navigate to indicated REF-ID
3. Read complete section
4. Cross-reference NM maps for project implementation

**Routing Speed:** ~5 seconds

---

## 📋 KEYWORD TABLES

### Table 1: Lambda & Serverless

| Keyword | Category | REF-ID | Cross-Reference |
|---------|----------|--------|-----------------|
| lambda | AWS06 | AWS-LESS-01, 05, 11 | NM01/ARCH-07 |
| cold start | AWS06 | AWS-LESS-01 | NM01/ARCH-07 |
| serverless | AWS06 | (all) | NM maps |
| memory | AWS06 | AWS-LESS-05 | NM01/ARCH-07 |
| CPU | AWS06 | AWS-LESS-05 | - |
| concurrency | AWS06 | AWS-LESS-11 | NM04/DEC-04 |
| layer | AWS06 | AWS-LESS-06 | - |

### Table 2: API & Integration

| Keyword | Category | REF-ID | Cross-Reference |
|---------|----------|--------|-----------------|
| API Gateway | AWS06 | AWS-LESS-03, 09 | - |
| proxy | AWS06 | AWS-LESS-09 | NM04 decisions |
| non-proxy | AWS06 | AWS-LESS-09 | NM04 decisions |
| integration | AWS06 | AWS-LESS-03, 09 | - |
| transformation | AWS06 | AWS-LESS-10 | NM06/LESS-09 |
| boundary | AWS06 | AWS-LESS-10 | NM04/DEC-05 |
| pass-through | AWS06 | AWS-LESS-09 | - |

### Table 3: Data & Storage

| Keyword | Category | REF-ID | Cross-Reference |
|---------|----------|--------|-----------------|
| DynamoDB | AWS06 | AWS-LESS-07 | - |
| access patterns | AWS06 | AWS-LESS-07 | - |
| data modeling | AWS06 | AWS-LESS-07 | - |

### Table 4: Orchestration & Events

| Keyword | Category | REF-ID | Cross-Reference |
|---------|----------|--------|-----------------|
| EventBridge | AWS06 | AWS-LESS-08 | - |
| pattern matching | AWS06 | AWS-LESS-08 | - |
| Step Functions | AWS06 | AWS-LESS-12 | - |
| state machine | AWS06 | AWS-LESS-12 | - |
| workflow | AWS06 | AWS-LESS-12 | - |

---

## 🌲 DECISION TREES

### Tree 1: "How to optimize Lambda?"

```
1. Identify bottleneck:
   ├─> Cold start? → AWS-LESS-01
   ├─> Memory/CPU? → AWS-LESS-05
   ├─> Concurrency? → AWS-LESS-11
   └─> Dependencies? → AWS-LESS-06

2. Cross-reference project implementation:
   └─> NM01/ARCH-07 (LMMS)
   └─> NM06/LESS-02 (measure)

3. Apply pattern + verify
```

### Tree 2: "How to design API integration?"

```
1. Determine integration type needed:
   ├─> Simple pass-through? → AWS-LESS-09 (proxy)
   ├─> Transform at boundary? → AWS-LESS-09 (non-proxy)
   └─> Complex transformation? → AWS-LESS-10

2. Check project decisions:
   └─> NM04 (integration decisions)

3. Implement pattern
```

### Tree 3: "How to handle data access?"

```
1. Storage type:
   ├─> DynamoDB? → AWS-LESS-07
   └─> Other? → (future AWS content)

2. Access pattern:
   └─> Read AWS-LESS-07 for patterns

3. Implement with project patterns
```

---

## ⚡ FAST PATHS

### Instant Answers

**"How to optimize cold start?"**
→ AWS-LESS-01: Cold start optimization
→ Also: NM01/ARCH-07 (LMMS implementation)

**"Proxy or non-proxy integration?"**
→ AWS-LESS-09: Decision framework
→ Pattern: Proxy = simple gateway, Non-proxy = complex gateway

**"How to transform API data?"**
→ AWS-LESS-10: Transformation strategies
→ Key: Separate transformation from business logic

**"How memory affects Lambda performance?"**
→ AWS-LESS-05: Memory/CPU relationship
→ Principle: More memory = more CPU

**"How to design Step Functions?"**
→ AWS-LESS-12: State machine design
→ Pattern: Keep states focused, minimize transitions

**"How to use EventBridge patterns?"**
→ AWS-LESS-08: Pattern matching
→ Practice: Specific patterns better than wildcards

**"Lambda concurrency best practices?"**
→ AWS-LESS-11: Concurrency patterns
→ Warning: Cross-ref NM04/DEC-04 (no threading in single execution)

---

## 🔗 CROSS-REFERENCES TO NM MAPS

### When to Use AWS vs NM:

**Use AWS maps for:**
- Universal serverless patterns
- AWS-specific best practices
- External validation of approaches
- Learning industry standards

**Use NM maps for:**
- Project-specific implementation
- SUGA architecture details
- Internal decisions and rationale
- Project bugs and lessons

**Use Both for:**
- Complete understanding
- Implementation with best practices
- Validation of approaches

### Key Cross-References:

**AWS-LESS-09 ↔ NM04**
- AWS: Universal proxy pattern
- NM: Project integration decisions

**AWS-LESS-10 ↔ NM04/DEC-05**
- AWS: Transformation strategies
- NM: Sentinel sanitization at boundaries

**AWS-LESS-01 ↔ NM01/ARCH-07**
- AWS: Generic cold start optimization
- NM: LMMS implementation (LIGS, LUGS, ZAPH)

---

## 🎯 TOP PRIORITIES

**Most Referenced AWS Content:**

1. **AWS-LESS-09** (Proxy vs non-proxy) - Integration pattern decision
2. **AWS-LESS-10** (Transformations) - Boundary data handling
3. **AWS-LESS-01** (Cold start) - Performance optimization
4. **AWS-LESS-05** (Memory/CPU) - Resource optimization

**Use these first for most serverless questions.**

---

## 📂 CATEGORY QUICK REFERENCE

### AWS06 - Serverless Patterns (Only Active Category)

**Purpose:** AWS Lambda, API Gateway, serverless patterns  
**Files:** 12 LESS files  
**When to use:** Serverless architecture, AWS best practices  
**Directory:** `/nmap/AWS/AWS06/`

**Top items:**
- AWS-LESS-09 (proxy/non-proxy)
- AWS-LESS-10 (transformations)
- AWS-LESS-01 (cold start)
- AWS-LESS-05 (memory/CPU)

---

## 🔍 SEARCH STRATEGY

**Level 1: This Quick Index (90%)**
1. Look up keyword
2. Navigate to REF-ID
3. Check cross-references
**Time: ~5 seconds**

**Level 2: Master Index (8%)**
1. Use AWS00-Master_Index.md
2. Navigate category
**Time: ~10 seconds**

**Level 3: Direct File (2%)**
1. Browse AWS06 directory
2. Read specific file
**Time: ~15 seconds**

---

**Navigation:**
- **Master Index:** AWS00-Master_Index.md
- **Project Maps:** /nmap/NM00/ (NM00-Quick_Index.md)

---

**End of AWS Quick Index**

**Total Lines:** ~220  
**Coverage:** 100% of AWS content (12 LESS files)  
**Average Routing Time:** ~5 seconds

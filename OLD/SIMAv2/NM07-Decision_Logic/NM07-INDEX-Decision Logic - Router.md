# NM07-INDEX: Decision Logic - Router
# SIMA Architecture Pattern - Decision Frameworks
# Version: 2.0.0 | Phase: 2 SIMA Implementation

---

## Purpose

This INDEX file routes queries about decision trees, frameworks, and "how to decide" questions to the appropriate implementation files. It's the **Interface Layer** of the SIMA pattern for NM07.

**SIMA Architecture for NM07:**
```
Gateway Layer: NM00-Master-Index.md, NM00A-Quick-Index.md
    |
Interface Layer: THIS FILE (NM07-INDEX-DecisionLogic.md) 
    |
Implementation Layer: 
    ├── NEURAL_MAP_07-Decision_Logic_Part_1_of_2.md (DT-01 to DT-07)
    └── NEURAL_MAP_07-Decision_Logic_Part_2_of_2.md (DT-08 to DT-13, frameworks)
```

---

## Quick Stats

**Total REF IDs:** 13 decision trees + 3 frameworks  
**Files:** 3 (1 INDEX + 2 IMPL)  
**Priority Breakdown:** Critical=1, High=7, Medium=4, Low=1

---

## PART 1: DISPATCH TABLE

### By REF ID (Decision Trees)

| REF ID | Question | File | Priority |
|--------|----------|------|----------|
| **DT-01** | How to import X? | Part 1 | CRITICAL |
| **DT-02** | Where function goes? | Part 1 | HIGH |
| **DT-03** | User wants feature X | Part 1 | HIGH |
| **DT-04** | Should I cache X? | Part 1 | HIGH |
| **DT-05** | How handle error? | Part 1 | HIGH |
| **DT-06** | What exception type? | Part 1 | MEDIUM |
| **DT-07** | Should I optimize? | Part 1 | MEDIUM |
| **DT-08** | What to test? | Part 2 | HIGH |
| **DT-09** | How much to mock? | Part 2 | MEDIUM |
| **DT-10** | Should I refactor? | Part 2 | MEDIUM |
| **DT-11** | Extract function? | Part 2 | LOW |
| **DT-12** | Deploy this change? | Part 2 | HIGH |
| **DT-13** | New interface or extend? | Part 2 | HIGH |

### Frameworks

| REF ID | Framework | File | Priority |
|--------|-----------|------|----------|
| **FW-01** | Cache vs compute trade-off | Part 2 | MEDIUM |
| **FW-02** | Optimize vs document | Part 2 | MEDIUM |
| **META-01** | How to make good decisions? | Part 2 | HIGH |

---

## PART 2: QUICK REFERENCE BY CATEGORY

### Development Decisions (Part 1)
- **DT-01:** How to import X? (cross-interface, intra-interface)
- **DT-02:** Where should function go? (router, core, wrapper)
- **DT-03:** User wants feature (does it exist, where add)
- **DT-04:** Should I cache this? (cost-benefit analysis)

### Error & Quality Decisions (Part 1)
- **DT-05:** How to handle error? (catch, propagate, log)
- **DT-06:** What exception type? (specific vs generic)
- **DT-07:** Should I optimize? (measure first)

### Testing Decisions (Part 2)
- **DT-08:** What should I test? (success, failure, edges)
- **DT-09:** How much to mock? (isolation vs integration)

### Refactoring Decisions (Part 2)
- **DT-10:** Should I refactor? (complexity, change frequency)
- **DT-11:** Extract or inline function? (reusability)

### Deployment Decisions (Part 2)
- **DT-12:** Should I deploy this? (tests, docs, impact)
- **DT-13:** New interface or extend? (size, state, domain)

### Meta Frameworks (Part 2)
- **FW-01:** Cache vs compute analysis
- **FW-02:** Optimize vs document trade-off  
- **META-01:** Decision-making methodology

---

## PART 3: ROUTING BY KEYWORD

Use this section for fast keyword-based routing:

### Import & Organization
- "how to import", "import decision", "cross interface" -> **DT-01** (Part 1)
- "where function", "function location", "organize code" -> **DT-02** (Part 1)
- "add feature", "new feature", "extend" -> **DT-03, DT-13** (Parts 1 & 2)

### Performance & Caching
- "should cache", "cache decision", "caching strategy" -> **DT-04** (Part 1)
- "should optimize", "optimization", "performance" -> **DT-07** (Part 1)
- "cache vs compute", "cost benefit" -> **FW-01** (Part 2)

### Error Handling
- "handle error", "exception", "error strategy" -> **DT-05** (Part 1)
- "exception type", "what exception", "error type" -> **DT-06** (Part 1)

### Testing
- "what to test", "test coverage", "testing strategy" -> **DT-08** (Part 2)
- "how much mock", "mocking strategy", "test isolation" -> **DT-09** (Part 2)

### Refactoring
- "should refactor", "refactoring decision", "code quality" -> **DT-10** (Part 2)
- "extract function", "inline", "code structure" -> **DT-11** (Part 2)

### Deployment
- "should deploy", "deploy decision", "release" -> **DT-12** (Part 2)
- "new interface", "extend interface", "architecture" -> **DT-13** (Part 2)

### Meta
- "how to decide", "decision framework", "methodology" -> **META-01** (Part 2)

---

## PART 4: PRIORITY LEARNING PATH

### CRITICAL - Always Use (1)

1. **DT-01** (Part 1) - How to import X?

**Why Critical:** Incorrect imports break architecture and cause circular dependencies.

---

### HIGH - Reference Frequently (7)

1. **DT-02** (Part 1) - Where should function go?
2. **DT-03** (Part 1) - User wants feature
3. **DT-04** (Part 1) - Should I cache X?
4. **DT-05** (Part 1) - How to handle error?
5. **DT-08** (Part 2) - What should I test?
6. **DT-12** (Part 2) - Deploy this change?
7. **DT-13** (Part 2) - New interface or extend?

**Why High:** These decisions come up frequently in development.

---

### MEDIUM - As Needed (4)

1. **DT-06** (Part 1) - What exception type?
2. **DT-07** (Part 1) - Should I optimize?
3. **DT-09** (Part 2) - How much to mock?
4. **DT-10** (Part 2) - Should I refactor?

**Why Medium:** Important but less frequent decisions.

---

### LOW - Occasional Use (1)

1. **DT-11** (Part 2) - Extract or inline function?

**Why Low:** Minor structural decisions.

---

## PART 5: COMMON DECISION PATTERNS

### Pattern 1: Adding New Functionality
```
1. DT-03: Does feature exist? (search first)
2. DT-02: Where does it go? (correct location)
3. DT-04: Should I cache? (if data access)
4. DT-08: What to test? (coverage)
5. DT-12: Ready to deploy? (checklist)
```

### Pattern 2: Performance Issue
```
1. DT-07: Should I optimize? (measure first)
2. DT-04: Should I cache? (reduce redundant work)
3. DT-10: Should I refactor? (improve structure)
4. DT-08: Test changes (verify correctness)
5. DT-12: Deploy carefully (monitor impact)
```

### Pattern 3: Error Encountered
```
1. DT-05: How to handle? (strategy)
2. DT-06: What exception type? (specificity)
3. DT-08: Test error path (coverage)
```

### Pattern 4: Code Review Feedback
```
1. DT-10: Should I refactor? (assess need)
2. DT-11: Extract or inline? (structure)
3. DT-02: Correct location? (organization)
4. DT-08: Add tests? (coverage)
```

---

## PART 6: FILE ACCESS METHODS

### Method 1: Direct Search (Recommended)
```
Search project knowledge for:
"NEURAL_MAP_07 Part 1 DT-01 how to import"
"NEURAL_MAP_07 Part 2 DT-08 what to test"
```

### Method 2: Via Master Index
```
Search: "NM00-Master-Index decision logic"
Then: Navigate to NM07 section
Then: Find specific DT-## reference
```

### Method 3: Via Quick Index
```
Search: "NM00A-Quick-Index decision"
Get: Auto-recall context
Then: Reference detailed file if needed
```

---

## PART 7: INTEGRATION WITH OTHER NEURAL MAPS

### NM07 Relates To:

**NM01 (Architecture):**
- DT-01, DT-02 implement architecture patterns
- Decision trees enforce ARCH-01 to ARCH-06

**NM02 (Dependencies):**
- DT-01 implements RULE-01, RULE-02, RULE-03
- Import decisions follow dependency layers

**NM03 (Operations):**
- DT-05 implements error handling from PATH-04
- Decisions affect operational flow

**NM04 (Decisions):**
- Decision trees implement design decisions
- Each DT references relevant DEC-XX

**NM05 (Anti-Patterns):**
- Decision trees prevent anti-patterns
- DT-01 prevents AP-01 (direct imports)
- DT-05 prevents AP-14, AP-15 (bad error handling)

**NM06 (Learned Experiences):**
- Decision trees incorporate lessons learned
- Bugs inform decision criteria

---

## PART 8: USAGE EXAMPLES

### Example 1: Import Question
```
User: "Should I import logging_core directly?"

Claude Action:
1. Search: "NM07 DT-01 import decision"
2. Find: DT-01 in Part 1
3. Follow tree: Different interface -> Use gateway
4. Response: "NO - Use gateway.log_info() instead"
```

---

### Example 2: Feature Addition
```
User: "User wants email notifications"

Claude Action:
1. Search: "NM07 DT-03 feature decision"
2. Find: DT-03 in Part 1
3. Follow tree: Check if exists -> No -> New interface?
4. Reference: DT-13 for new interface decision
5. Response: "Check if email logic exists, if not, create new interface"
```

---

### Example 3: Performance Question
```
User: "Should I optimize this slow function?"

Claude Action:
1. Search: "NM07 DT-07 optimize decision"
2. Find: DT-07 in Part 1
3. Response: "Measure first. If < 10ms or called < 10x/request, 
   document why it's slow instead of optimizing."
```

---

### Example 4: Testing Strategy
```
User: "What tests should I write?"

Claude Action:
1. Search: "NM07 DT-08 testing decision"
2. Find: DT-08 in Part 2
3. Response: "MUST: Success path, failure path, edge cases.
   SHOULD: Integration. OPTIONAL: Performance."
```

---

## PART 9: FILE STATISTICS

### Part 1 (DT-01 to DT-07)
- **Size:** ~500 lines
- **REF IDs:** 7 decision trees
- **Topics:** Import, organization, features, caching, errors, optimization
- **Priority:** 1 CRITICAL, 4 HIGH, 2 MEDIUM

### Part 2 (DT-08 to DT-13 + Frameworks)
- **Size:** ~500 lines
- **REF IDs:** 6 decision trees + 3 frameworks
- **Topics:** Testing, refactoring, deployment, frameworks, meta-decisions
- **Priority:** 3 HIGH, 2 MEDIUM, 1 LOW

### Total NM07 Coverage
- **Total Size:** ~1000 lines across 2 parts
- **Total REF IDs:** 13 DT + 3 FW = 16
- **Both files under 600-line limit:** ✅

---

## END OF INDEX

**Next Steps:**
- Search Part 1 for development/error decisions (DT-01 to DT-07)
- Search Part 2 for testing/deployment decisions (DT-08 to DT-13)
- Use dispatch table above for specific DT-## routing

**Related Files:**
- NM00-Master-Index.md (Gateway to all Neural Maps)
- NM00A-Quick-Index.md (Quick lookups)
- NM04-INDEX-Decisions.md (Design decisions)
- NM05-INDEX-AntiPatterns.md (What NOT to do)

# EOF

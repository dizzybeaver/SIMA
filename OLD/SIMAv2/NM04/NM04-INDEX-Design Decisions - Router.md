# NM04-INDEX: Design Decisions - Router
# SIMA Architecture Pattern - Why We Built It This Way
# Version: 2.0.0 | Phase: 2 SIMA Implementation

---

## Purpose

This INDEX file routes queries about design decisions, architectural choices, and trade-off rationales to the appropriate implementation files. It's the **Interface Layer** of the SIMA pattern for NM04.

**SIMA Architecture for NM04:**
```
Gateway Layer: NM00-Master-Index.md, NM00A-Quick-Index.md
    |
Interface Layer: THIS FILE (NM04-INDEX-Decisions.md) 
    |
Implementation Layer: 
    ├── NM04-ARCHITECTURE-Decisions.md (DEC-01 to DEC-11)
    ├── NM04-TECHNICAL-Decisions.md (DEC-12 to DEC-19)
    └── NM04-OPERATIONAL-Decisions.md (DEC-20 to DEC-23)
```

---

## Quick Stats

**Total REF IDs:** 23 design decisions  
**Files:** 4 (1 INDEX + 3 IMPL)  
**Priority Breakdown:** Critical=7, High=6, Medium=4, Unknown=6

---

## PART 1: DISPATCH TABLE

### By REF ID (Complete List)

| REF ID | Decision | File | Priority |
|--------|----------|------|----------|
| **DEC-01** | SIMA pattern choice | ARCHITECTURE | CRITICAL |
| **DEC-02** | Gateway centralization | ARCHITECTURE | HIGH |
| **DEC-03** | Dispatch dictionary pattern | ARCHITECTURE | CRITICAL |
| **DEC-04** | No threading locks | ARCHITECTURE | CRITICAL |
| **DEC-05** | Sentinel sanitization | ARCHITECTURE | HIGH |
| **DEC-06** | (Reserved) | ARCHITECTURE | TBD |
| **DEC-07** | (Reserved) | ARCHITECTURE | TBD |
| **DEC-08** | (Reserved) | ARCHITECTURE | TBD |
| **DEC-09** | (Reserved) | ARCHITECTURE | TBD |
| **DEC-10** | (Reserved) | ARCHITECTURE | TBD |
| **DEC-11** | (Reserved) | ARCHITECTURE | TBD |
| **DEC-12** | Multi-tier configuration | TECHNICAL | MEDIUM |
| **DEC-13** | Fast path caching | TECHNICAL | MEDIUM |
| **DEC-14** | Lazy loading interfaces | TECHNICAL | HIGH |
| **DEC-15** | Router-level exception catching | TECHNICAL | HIGH |
| **DEC-16** | Import error protection | TECHNICAL | HIGH |
| **DEC-17** | Home Assistant as Mini-ISP | TECHNICAL | MEDIUM |
| **DEC-18** | Interface-level mocking | TECHNICAL | MEDIUM |
| **DEC-19** | Neural map synthetic memory | TECHNICAL | CRITICAL |
| **DEC-20** | LAMBDA_MODE over LEE_FAILSAFE | OPERATIONAL | CRITICAL |
| **DEC-21** | SSM token-only | OPERATIONAL | CRITICAL |
| **DEC-22** | DEBUG_MODE flow visibility | OPERATIONAL | HIGH |
| **DEC-23** | DEBUG_TIMINGS performance | OPERATIONAL | TBD |

---

## PART 2: QUICK REFERENCE BY TOPIC

### Architecture Decisions
```
Pattern Choice → NM04-ARCHITECTURE (DEC-01: SIMA)
Centralization → NM04-ARCHITECTURE (DEC-02: Gateway)
Dispatch Pattern → NM04-ARCHITECTURE (DEC-03: Dictionary)
Concurrency → NM04-ARCHITECTURE (DEC-04: No locks)
Sanitization → NM04-ARCHITECTURE (DEC-05: Sentinel)
```

### Performance Decisions
```
Caching → NM04-TECHNICAL (DEC-13: Fast path)
Initialization → NM04-TECHNICAL (DEC-14: Lazy loading)
```

### Robustness Decisions
```
Error Handling → NM04-TECHNICAL (DEC-15: Router exceptions)
Import Safety → NM04-TECHNICAL (DEC-16: Protected imports)
Testing → NM04-TECHNICAL (DEC-18: Interface mocks)
```

### Configuration Decisions
```
Config Tiers → NM04-TECHNICAL (DEC-12: Multi-tier)
Operational Modes → NM04-OPERATIONAL (DEC-20: LAMBDA_MODE)
Secrets Management → NM04-OPERATIONAL (DEC-21: SSM token-only)
```

### Debugging Decisions
```
Flow Visibility → NM04-OPERATIONAL (DEC-22: DEBUG_MODE)
Performance Tracking → NM04-OPERATIONAL (DEC-23: DEBUG_TIMINGS)
```

### Documentation Decisions
```
Knowledge Preservation → NM04-TECHNICAL (DEC-19: Neural maps)
```

---

## PART 3: KEYWORD ROUTING

### Architecture Keywords
```
"SIMA" → DEC-01 (Architecture pattern)
"gateway pattern" → DEC-01, DEC-02
"why gateway" → DEC-01, DEC-02
"centralization" → DEC-02
"dispatch" → DEC-03
"dictionary vs if/elif" → DEC-03
"router pattern" → DEC-03
```

### Concurrency Keywords
```
"threading" → DEC-04
"locks" → DEC-04
"why no locks" → DEC-04
"async" → DEC-04
"single-threaded" → DEC-04
"concurrency" → DEC-04
```

### Performance Keywords
```
"fast path" → DEC-13
"optimization" → DEC-13
"lazy loading" → DEC-14
"cold start" → DEC-14, DEC-21
"performance" → DEC-13, DEC-14, DEC-23
```

### Error Handling Keywords
```
"exception" → DEC-15
"error handling" → DEC-15, DEC-16
"try/except" → DEC-15, DEC-16
"graceful degradation" → DEC-16
"import errors" → DEC-16
```

### Security Keywords
```
"sentinel" → DEC-05
"sanitization" → DEC-05
"validation" → DEC-05
"security" → DEC-05
```

### Configuration Keywords
```
"config" → DEC-12, DEC-20, DEC-21
"configuration tiers" → DEC-12
"LAMBDA_MODE" → DEC-20
"failsafe mode" → DEC-20
"SSM" → DEC-21
"Parameter Store" → DEC-21
"secrets" → DEC-21
```

### Debugging Keywords
```
"DEBUG_MODE" → DEC-22
"debugging" → DEC-22, DEC-23
"flow visibility" → DEC-22
"timings" → DEC-23
"performance tracking" → DEC-23
```

### Testing Keywords
```
"mocking" → DEC-18
"test isolation" → DEC-18
"unit tests" → DEC-18
```

### Extension Keywords
```
"Home Assistant" → DEC-17
"extension" → DEC-17
"Mini-ISP" → DEC-17
```

### Documentation Keywords
```
"neural maps" → DEC-19
"documentation" → DEC-19
"knowledge base" → DEC-19
"synthetic memory" → DEC-19
```

---

## PART 4: PRIORITY LEARNING PATH

### 🔴 CRITICAL - Learn These First (7 decisions)

**Why These Matter Most:**
These decisions define the core architecture and operational model. Understanding these is essential for working with the system.

1. **DEC-01** (ARCHITECTURE) - SIMA pattern choice
   - *Why:* Foundation of entire architecture
   - *Impact:* Affects every file and interface
   - *Keywords:* SIMA, gateway pattern, architecture

2. **DEC-03** (ARCHITECTURE) - Dispatch dictionary pattern
   - *Why:* How all routing works
   - *Impact:* O(1) vs O(n) performance
   - *Keywords:* dispatch, dictionary, router

3. **DEC-04** (ARCHITECTURE) - No threading locks
   - *Why:* Simplicity and performance
   - *Impact:* No lock overhead, no deadlocks
   - *Keywords:* threading, locks, Lambda

4. **DEC-19** (TECHNICAL) - Neural map synthetic memory
   - *Why:* Knowledge preservation system
   - *Impact:* How Claude learns the system
   - *Keywords:* neural maps, documentation

5. **DEC-20** (OPERATIONAL) - LAMBDA_MODE over LEE_FAILSAFE
   - *Why:* Operational mode flexibility
   - *Impact:* Breaking change, new config pattern
   - *Keywords:* LAMBDA_MODE, failsafe, operational modes

6. **DEC-21** (OPERATIONAL) - SSM token-only
   - *Why:* 92% cold start improvement
   - *Impact:* Major performance gain, breaking change
   - *Keywords:* SSM, Parameter Store, cold start

7. **DEC-XX** (ARCHITECTURE) - (Reserved for future critical decisions)

---

### 🟡 HIGH - Reference Frequently (6 decisions)

**Why These Matter:**
These decisions affect daily development work and system behavior.

1. **DEC-02** (ARCHITECTURE) - Gateway centralization
   - *Why:* All cross-interface calls route through gateway
   - *Keywords:* centralization, gateway

2. **DEC-05** (ARCHITECTURE) - Sentinel sanitization
   - *Why:* Prevents 535ms performance penalty
   - *Keywords:* sentinel, sanitization, router layer

3. **DEC-14** (TECHNICAL) - Lazy loading interfaces
   - *Why:* 60ms cold start savings
   - *Keywords:* lazy loading, cold start

4. **DEC-15** (TECHNICAL) - Router-level exception catching
   - *Why:* Guaranteed error logging
   - *Keywords:* exceptions, error handling

5. **DEC-16** (TECHNICAL) - Import error protection
   - *Why:* Graceful degradation
   - *Keywords:* import errors, robustness

6. **DEC-22** (OPERATIONAL) - DEBUG_MODE flow visibility
   - *Why:* Critical for troubleshooting
   - *Keywords:* DEBUG_MODE, debugging

---

### 🟢 MEDIUM - As Needed (4 decisions)

**Why These Matter:**
Important for specific features and optimization work.

1. **DEC-12** (TECHNICAL) - Multi-tier configuration
   - *Why:* Flexible deployment options
   - *Keywords:* configuration tiers, deployment

2. **DEC-13** (TECHNICAL) - Fast path caching
   - *Why:* 40% performance gain for hot paths
   - *Keywords:* fast path, optimization

3. **DEC-17** (TECHNICAL) - Home Assistant as Mini-ISP
   - *Why:* Extension architecture pattern
   - *Keywords:* Home Assistant, extension, Mini-ISP

4. **DEC-18** (TECHNICAL) - Interface-level mocking
   - *Why:* Test isolation and speed
   - *Keywords:* mocking, testing

---

## PART 5: DECISION THEMES

### Theme 1: Simplicity Over Complexity
```
DEC-04: No threading locks (Lambda is single-threaded)
DEC-21: SSM token-only (one parameter vs 13)
DEC-20: LAMBDA_MODE enum (clear intent)
→ Lesson: Remove unnecessary complexity
```

### Theme 2: Performance Through Architecture
```
DEC-01: SIMA pattern (clean separation)
DEC-03: Dispatch dictionaries (O(1) lookup)
DEC-13: Fast path caching (40% gain)
DEC-14: Lazy loading (60ms savings)
DEC-21: SSM token-only (92% reduction)
→ Lesson: Architecture enables performance
```

### Theme 3: Robustness Through Isolation
```
DEC-02: Gateway centralization (single entry point)
DEC-05: Sentinel sanitization (router layer)
DEC-15: Router exceptions (guaranteed logging)
DEC-16: Import error protection (graceful degradation)
→ Lesson: Isolation prevents cascading failures
```

### Theme 4: Knowledge Preservation
```
DEC-19: Neural maps (synthetic memory)
All decisions documented with:
  - What: The decision
  - Why: 3-5 reasons with evidence
  - Trade-offs: Pros and cons
  - Alternatives: What was considered
  - Rationale: Final reasoning
→ Lesson: Document the "why" not just the "what"
```

---

## PART 6: CROSS-REFERENCES

### Most Referenced Decisions

1. **DEC-04** (No threading) → Referenced by:
   - NM05-AP-08 (Threading anti-pattern)
   - NM06-LESS-04 (Consistency lesson)

2. **DEC-01** (SIMA pattern) → Referenced by:
   - NM02-RULE-01 (Import rules)
   - NM06-BUG-02 (Circular imports)
   - NM05-AP-01 (Direct import anti-pattern)
   - NM06-LESS-01 (Gateway pattern lesson)

3. **DEC-03** (Dispatch pattern) → Referenced by:
   - NM01-ARCH-03 (Router pattern)
   - NM06-LESS-04 (Consistency)

4. **DEC-21** (SSM token-only) → Referenced by:
   - NM06-LESS-17 (SSM simplification)
   - NM06-BUG-04 (Config mismatch)

5. **DEC-20** (LAMBDA_MODE) → Referenced by:
   - NM06-LESS-17 (Operational modes)

---

## PART 7: USAGE PATTERNS

### Pattern 1: Architecture Question
```
User: "Why use gateway pattern instead of direct imports?"

Claude Action:
1. Search: "NM04 gateway pattern SIMA"
2. Route to: NM04-ARCHITECTURE-Decisions.md
3. Find: DEC-01 (SIMA pattern), DEC-02 (Gateway centralization)
4. Response: [Explain 5 reasons for SIMA + benefits]
```

### Pattern 2: Performance Question
```
User: "Why no threading locks?"

Claude Action:
1. Search: "NM04 threading locks Lambda"
2. Route to: NM04-ARCHITECTURE-Decisions.md
3. Find: DEC-04 (No threading locks)
4. Response: [Explain 4 reasons: Lambda, simplicity, performance, correctness]
```

### Pattern 3: Configuration Question
```
User: "Why is SSM only used for tokens now?"

Claude Action:
1. Search: "NM04 SSM token-only Parameter Store"
2. Route to: NM04-OPERATIONAL-Decisions.md
3. Find: DEC-21 (SSM token-only)
4. Response: [Explain 92% cold start improvement, before/after comparison]
```

### Pattern 4: Design Rationale Question
```
User: "Why dispatch dictionaries instead of if/elif chains?"

Claude Action:
1. Search: "NM04 dispatch dictionary router"
2. Route to: NM04-ARCHITECTURE-Decisions.md
3. Find: DEC-03 (Dispatch dictionary pattern)
4. Response: [Explain O(1) vs O(n), 90% code reduction, maintainability]
```

---

## PART 8: INTEGRATION WITH OTHER NEURAL MAPS

### NM04 Relates To:

**NM01 (Architecture):**
- DEC-01, DEC-02, DEC-03 define patterns used in NM01
- All ARCH-## references implement decisions from NM04

**NM02 (Dependencies):**
- DEC-01 (SIMA) → Why RULE-01 exists (cross-interface via gateway)
- DEC-05 (Sentinel) → Router-layer responsibilities

**NM03 (Operations):**
- DEC-14 (Lazy loading) → Affects PATH-01 cold start
- DEC-13 (Fast path) → Affects FLOW-01 performance

**NM05 (Anti-Patterns):**
- DEC-04 → Why AP-08 (threading) is wrong
- DEC-01 → Why AP-01 (direct imports) is wrong

**NM06 (Learned Experiences):**
- Bugs led to decisions:
  - BUG-01 → DEC-05 (Sentinel sanitization)
  - BUG-02 → DEC-01 (SIMA pattern)
  - BUG-04 → DEC-21 (SSM simplification)
- Lessons reinforce decisions:
  - LESS-01 → DEC-01, DEC-02 (Gateway pattern)
  - LESS-04 → DEC-03, DEC-04 (Consistency)

**NM07 (Decision Logic):**
- Decision trees implement design decisions
- DT-01 → Implements DEC-01 (import via gateway)

---

## PART 9: FILE ACCESS METHODS

### Method 1: Direct Search (Recommended)
```
Search project knowledge for:
"NM04-ARCHITECTURE DEC-01 SIMA pattern"
"NM04-TECHNICAL DEC-13 fast path"
"NM04-OPERATIONAL DEC-21 SSM token"
```

### Method 2: Via Master Index
```
Search: "NM00-Master-Index design decisions"
Then: Navigate to NM04 section
Then: Find specific DEC-## reference
```

### Method 3: Via Quick Index
```
Search: "NM00A-Quick-Index threading"
Get: Keyword trigger → NM04-DEC-04
Then: Reference detailed file
```

---

## PART 10: DECISION TEMPLATE

All decisions in NM04 follow this structure:

```markdown
### Decision: [Short Name]
**REF:** NM04-DEC-##
**PRIORITY:** 🔴 CRITICAL / 🟡 HIGH / 🟢 MEDIUM
**TAGS:** keyword1, keyword2, keyword3
**KEYWORDS:** search terms
**RELATED:** Other REF IDs
**DATE:** YYYY.MM.DD (if recent)

**What:** One-sentence summary

**Why:**
1. **Reason 1**
   - Explanation
   - Evidence

2. **Reason 2**
   - Explanation
   - Evidence

3. **Reason 3**
   - Explanation  
   - Evidence

**Trade-offs:**
- Pro: Benefits
- Con: Costs
- **Decision:** Final reasoning

**Measurement:** (if applicable)
- Metrics
- Before/after comparison

**Alternatives Considered:**
- Option 1: Rejected because...
- Option 2: Rejected because...

**Rationale:** Why this decision is best

**REAL-WORLD USAGE:**
User: "Example question"
Claude Action: [How to use this decision]
Response: [What to tell user]
```

---

## END NOTES

**Key Takeaways:**
1. 23 design decisions documented (DEC-01 to DEC-23)
2. Each decision has complete rationale (what, why, trade-offs)
3. 7 CRITICAL, 6 HIGH, 4 MEDIUM, 6 TBD
4. Architecture decisions enable performance and robustness
5. All decisions cross-referenced with other neural maps

**File Statistics:**
- Total REF IDs: 23 design decisions
- Total lines: ~250 (INDEX), ~1,350 (IMPL)
- Priority: 7 CRITICAL, 6 HIGH, 4 MEDIUM

**Related Files:**
- NM04-ARCHITECTURE-Decisions.md (Foundation patterns)
- NM04-TECHNICAL-Decisions.md (Implementation choices)
- NM04-OPERATIONAL-Decisions.md (Runtime decisions)

# EOF

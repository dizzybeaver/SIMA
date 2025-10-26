# NEURAL_MAP_07: Decision Logic (Part 2 of 2)
# Continuing from Part 1...

## PART 5: TESTING DECISIONS

### Decision Tree 8: "What Should I Test?"
**REF:** NM07-DT-08
**PRIORITY:** 🟡 HIGH
**TAGS:** testing, coverage, quality, test-strategy
**KEYWORDS:** what to test, test coverage, testing strategy
**RELATED:** NM05-AP-23, NM05-AP-24, NM04-DEC-18

```
START: Writing tests for feature X
│
├─ MUST TEST: Success path
│  Tests: Happy path, expected inputs
│  Coverage: Main functionality works
│
├─ MUST TEST: Failure path
│  Tests: Invalid inputs, errors
│  Coverage: Graceful error handling
│
├─ MUST TEST: Edge cases
│  Tests: Boundary values, empty inputs, None
│  Coverage: Corner cases handled
│
├─ SHOULD TEST: Integration
│  Tests: Cross-interface interactions
│  Coverage: Interfaces work together
│
├─ OPTIONAL: Performance
│  Tests: Time/memory bounds
│  Coverage: Performance regressions
│
└─ OPTIONAL: Load/Stress
   Tests: High volume, concurrent
   Coverage: System limits
   → END
```

**Test Priority Matrix:**

| Test Type | Priority | Example |
|-----------|----------|---------|
| Success path | MUST | cache_get returns value |
| Failure path | MUST | cache_get returns None on miss |
| Invalid input | MUST | cache_set raises on bad input |
| Edge cases | MUST | cache_get with None key |
| Integration | SHOULD | HTTP uses cache correctly |
| Performance | OPTIONAL | cache_get <1ms |
| Load testing | OPTIONAL | 1000 operations/sec |

**REAL-WORLD USAGE:**
User: "What tests should I write for my new function?"
Claude searches: "what to test priority"
Finds: NM07-DT-08
Response: "MUST: Success path, failure path, edge cases. SHOULD: Integration. OPTIONAL: Performance."

---

### Decision Tree 9: "How Much to Mock?"
**REF:** NM07-DT-09
**PRIORITY:** 🟢 MEDIUM
**TAGS:** testing, mocking, isolation, test-strategy
**KEYWORDS:** mock strategy, how much mock, test isolation
**RELATED:** NM04-DEC-18, NM06-LESS-07

```
START: Writing test, need mocking
│
├─ Q: Testing single function in isolation?
│  ├─ YES → Mock dependencies heavily
│  │      Example: Mock all gateway calls
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Testing integration between components?
│  ├─ YES → Mock sparingly (only external)
│  │      Example: Mock only HTTP calls, not cache
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Testing end-to-end flow?
│  ├─ YES → Mock only external services
│  │      Example: Mock Home Assistant API
│  │      → END
│  │
│  └─ NO → Continue
│
└─ Q: Testing performance/load?
   └─ YES → No mocking (real components)
          Example: Measure actual cache performance
          → END
```

**Mocking Strategy:**

```python
# Unit test - mock heavily
def test_cache_operation():
    with patch('gateway.log_info'):
        with patch('gateway.record_metric'):
            result = cache_set("key", "value")

# Integration test - mock selectively
def test_http_with_cache():
    with patch('gateway.http_request') as mock_http:
        mock_http.return_value = {"data": "test"}
        # Real cache, mocked HTTP

# E2E test - mock only external
def test_alexa_flow():
    with patch('gateway.http_post') as mock_ha:
        mock_ha.return_value = {"status": "success"}
        # Everything else real
```

**REAL-WORLD USAGE:**
User: "Should I mock the cache in my HTTP tests?"
Claude searches: "how much to mock integration"
Finds: NM07-DT-09
Response: "Integration test → Mock only external (HTTP), keep cache real for authentic interaction."

---

## PART 6: REFACTORING DECISIONS

### Decision Tree 10: "Should I Refactor This Code?"
**REF:** NM07-DT-10
**PRIORITY:** 🟢 MEDIUM
**TAGS:** refactoring, code-quality, maintenance, decision-tree
**KEYWORDS:** should refactor, refactor decision, code improvement
**RELATED:** NM05-AP-20, NM07-DT-11

```
START: Considering refactoring
│
├─ Q: Is code working correctly?
│  ├─ NO → Fix bugs first, then consider refactoring
│  │      → END
│  │
│  └─ YES → Continue
│
├─ Q: Is code hard to understand?
│  ├─ YES → Refactor for readability
│  │      Signs: Nested >3 levels, >50 lines, unclear names
│  │      → REFACTOR
│  │
│  └─ NO → Continue
│
├─ Q: Is code duplicated (>3 places)?
│  ├─ YES → Extract to function/utility
│  │      DRY principle: Don't Repeat Yourself
│  │      → REFACTOR
│  │
│  └─ NO → Continue
│
├─ Q: Is code violating architecture?
│  ├─ YES → Refactor to conform
│  │      Examples: Cross-interface imports
│  │      → REFACTOR
│  │
│  └─ NO → Continue
│
├─ Q: Is code significantly slow?
│  ├─ YES (measured) → Refactor for performance
│  │      → REFACTOR
│  │
│  └─ NO → Continue
│
└─ Q: Is there a simpler way?
   ├─ YES (significantly simpler) → Consider refactoring
   │      Trade-off: Risk vs benefit
   │      → MAYBE REFACTOR
   │
   └─ NO → Don't refactor
          "If it ain't broke, don't fix it"
          → END
```

**Refactoring Triggers:**

| Trigger | Action | Priority |
|---------|--------|----------|
| Bug exists | Fix first | Critical |
| Hard to understand | Simplify | High |
| Duplicated 3+ times | Extract | High |
| Architecture violation | Conform | High |
| Slow (measured) | Optimize | Medium |
| Could be simpler | Consider | Low |
| Just because | Don't | N/A |

**REAL-WORLD USAGE:**
User: "This code works but is messy, should I refactor?"
Claude searches: "should refactor decision"
Finds: NM07-DT-10
Response: "If hard to understand or duplicated 3+ times → Refactor. If just 'messy' but clear → Don't."

---

### Decision Tree 11: "Extract to Function or Leave Inline?"
**REF:** NM07-DT-11
**PRIORITY:** ⚪ LOW
**TAGS:** refactoring, extraction, code-organization
**KEYWORDS:** extract function, inline code, function extraction
**RELATED:** NM07-DT-10, NM05-AP-20

```
START: Code block that could be extracted
│
├─ Q: Is code used >2 times?
│  ├─ YES → Extract to function
│  │      DRY principle
│  │      → EXTRACT
│  │
│  └─ NO → Continue
│
├─ Q: Is code >10 lines?
│  ├─ YES → Consider extracting
│  │      Aids readability
│  │      → Continue
│  │
│  └─ NO → Continue
│
├─ Q: Does code have clear single purpose?
│  ├─ YES → Extract to function
│  │      Single Responsibility Principle
│  │      → EXTRACT
│  │
│  └─ NO → Leave inline
│         Extraction would be artificial
│         → END
│
└─ Q: Would extraction improve readability?
   ├─ YES → Extract
   │      → EXTRACT
   │
   └─ NO → Leave inline
          → END
```

**Extract Function Examples:**

```python
# ❌ Don't extract - used once, simple
def process_request(request):
    data = request.get('data')
    # Just use inline, no need to extract

# ✅ Extract - used multiple times
def validate_email(email):
    # Used in 3 places, extract
    return re.match(r'^[\w\.-]+@[\w\.-]+\.\w+$', email)

# ✅ Extract - clear single purpose
def format_timestamp(dt):
    # Clear purpose, aids readability
    return dt.strftime('%Y-%m-%d %H:%M:%S')

# ❌ Don't extract - no clear purpose
def do_stuff(x, y, z):
    # Mixing unrelated operations
    # Extraction would be artificial
```

**REAL-WORLD USAGE:**
User: "Should I extract this 5-line code block?"
Claude searches: "extract function decision"
Finds: NM07-DT-11
Response: "If used >2 times or has clear single purpose → Extract. If used once and simple → Inline."

---

## PART 7: DEPLOYMENT DECISIONS

### Decision Tree 12: "Should I Deploy This Change?"
**REF:** NM07-DT-12
**PRIORITY:** 🟡 HIGH
**TAGS:** deployment, release, production, risk-management
**KEYWORDS:** should deploy, deploy decision, production deployment
**RELATED:** NM05-AP-27, NM05-AP-28, NM06-LESS-09

```
START: Change ready to deploy
│
├─ MUST: Are tests passing?
│  ├─ NO → Fix tests first
│  │      → STOP
│  │
│  └─ YES → Continue
│
├─ MUST: Is change reviewed?
│  ├─ NO → Get review
│  │      → STOP
│  │
│  └─ YES → Continue
│
├─ SHOULD: Is change backward compatible?
│  ├─ NO → Plan migration
│  │      Document breaking changes
│  │      → Continue with caution
│  │
│  └─ YES → Continue
│
├─ SHOULD: Can change be rolled back?
│  ├─ NO → Add rollback plan
│  │      Git tag, backup, etc.
│  │      → Continue
│  │
│  └─ YES → Continue
│
├─ OPTIONAL: Deploy to staging first?
│  ├─ HIGH RISK → Deploy to staging
│  │      Test in staging
│  │      → Then deploy to prod
│  │
│  └─ LOW RISK → Deploy directly to prod
│         → DEPLOY
│
└─ DEPLOY
   Steps:
   1. Git tag release
   2. Deploy code
   3. Monitor logs
   4. Verify functionality
   5. Document deployment
   → END
```

**Deployment Risk Assessment:**

| Change Type | Risk | Staging? | Rollback Plan |
|-------------|------|----------|---------------|
| Bug fix (small) | Low | Optional | Git revert |
| New feature | Medium | Yes | Feature flag |
| Refactoring | Medium | Yes | Git revert |
| Architecture change | High | Yes | Full backup |
| Breaking API change | Critical | Yes | Migration plan |

**REAL-WORLD USAGE:**
User: "Can I deploy this architecture change directly to production?"
Claude searches: "deploy decision architecture"
Finds: NM07-DT-12
Response: "Architecture change = HIGH RISK. Must deploy to staging first, test thoroughly, then prod."

---

## PART 8: ARCHITECTURE DECISIONS

### Decision Tree 13: "New Interface or Extend Existing?"
**REF:** NM07-DT-13
**PRIORITY:** 🟡 HIGH
**TAGS:** architecture, interface-design, growth, decision-tree
**KEYWORDS:** new interface, extend interface, interface decision
**RELATED:** NM07-DT-02, NM07-DT-03, NM06-LESS-06

```
START: Need new functionality
│
├─ Q: Does functionality fit existing interface?
│  ├─ YES → Extend existing
│  │      Example: cache.list_keys → CACHE interface
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Is functionality substantial (>200 lines)?
│  ├─ NO → Add to UTILITY
│  │      Example: String helpers
│  │      → END
│  │
│  └─ YES → Continue
│
├─ Q: Does functionality have its own state?
│  ├─ YES → New interface
│  │      Example: Database connection pool
│  │      → CREATE NEW INTERFACE
│  │
│  └─ NO → Continue
│
├─ Q: Is functionality domain-specific?
│  ├─ YES → New interface
│  │      Example: Email sending, File storage
│  │      → CREATE NEW INTERFACE
│  │
│  └─ NO → Add to UTILITY
│         Example: Generic helpers
│         → END
```

**New Interface Checklist:**

Create new interface if ALL of:
- [ ] >200 lines of code
- [ ] Has its own state
- [ ] Domain-specific (not generic utility)
- [ ] Used by multiple other interfaces
- [ ] Clear, focused responsibility

Otherwise: Extend existing or add to UTILITY

**Examples:**

```
Request: "Add email sending capability"
Checklist:
- >200 lines? YES (SMTP logic)
- Has state? YES (connection)
- Domain-specific? YES (email)
- Used by multiple? YES
→ CREATE EMAIL INTERFACE

Request: "Add string capitalization helper"
Checklist:
- >200 lines? NO (simple function)
- Has state? NO
- Domain-specific? NO (generic)
→ ADD TO UTILITY

Request: "Add cache.list_keys()"
Checklist:
- Fits CACHE? YES (cache operation)
→ EXTEND CACHE INTERFACE
```

**REAL-WORLD USAGE:**
User: "Should I create a new interface for file operations?"
Claude searches: "new interface decision"
Finds: NM07-DT-13
Response: "Check: >200 lines + state + domain-specific + multi-use. If YES to all → New interface."

---

## PART 9: PERFORMANCE DECISION FRAMEWORKS

### Framework 1: Cache vs Compute Trade-off
**REF:** NM07-FW-01
**TAGS:** performance, caching, cost-benefit, framework
**KEYWORDS:** cache vs compute, performance trade-off
**RELATED:** NM07-DT-04, NM04-DEC-09

```
Decision: Should I cache or recompute?

Calculate:
- Computation cost: C ms
- Cache lookup cost: L ms (typically ~0.1ms)
- Cache hit rate: H (0-1)
- Average benefit: (C - L) * H

If (C - L) * H > 1ms → Cache
Else → Recompute

Example:
- Computation: 50ms
- Lookup: 0.1ms
- Hit rate: 80%
- Benefit: (50 - 0.1) * 0.8 = 39.9ms
→ Cache! (huge benefit)

Example 2:
- Computation: 2ms
- Lookup: 0.1ms
- Hit rate: 50%
- Benefit: (2 - 0.1) * 0.5 = 0.95ms
→ Borderline, depends on context
```

---

### Framework 2: Optimize or Document Trade-off
**REF:** NM07-FW-02
**TAGS:** optimization, documentation, trade-off, framework
**KEYWORDS:** optimize vs document, performance decision
**RELATED:** NM07-DT-07, NM07-DT-10

```
Decision: Should I optimize slow code or document why it's slow?

Calculate complexity:
- Hours to optimize: O hours
- Performance gain: G%
- Code complexity increase: C (1-10)

If (G > 20% AND C < 5) → Optimize
If (G < 10%) → Document, don't optimize
Else → Consider case-by-case

Document if not optimizing:
# DESIGN DECISION: This loop is intentionally slow
# Reason: [Explain why optimization not worth it]
# Measured: 50ms for 100 items
# Trade-off: Complexity not worth 20ms savings
```

---

## PART 10: WISDOM SYNTHESIS

### Meta-Decision: "How Do I Make Good Decisions?"
**REF:** NM07-META-01
**TAGS:** decision-making, methodology, wisdom, framework
**KEYWORDS:** decision framework, how to decide, decision process
**RELATED:** All decision trees

**Framework for Decision-Making:**

1. **Understand the Context**
   - What are the constraints? (128MB, single-threaded, Lambda)
   - What are the goals? (Performance, maintainability, simplicity)
   - What are the trade-offs?

2. **Gather Data**
   - Measure, don't guess
   - Profile performance
   - Check neural maps for similar decisions

3. **Consider Alternatives**
   - What are the options?
   - What did we decide before in similar cases?
   - What do best practices suggest?

4. **Evaluate Trade-offs**
   - Pros and cons of each option
   - Short-term vs long-term impact
   - Complexity vs benefit

5. **Make Decision**
   - Choose option that best fits context
   - Document rationale (neural map)
   - Implement with tests

6. **Learn and Iterate**
   - Monitor results
   - Adjust if needed
   - Update neural map with learnings

**Key Principles:**

- **Consistency > Cleverness** - Follow established patterns
- **Simplicity > Optimization** - Simple code is maintainable code
- **Measure > Guess** - Data-driven decisions
- **Document > Remember** - Write down the "why"
- **Test > Hope** - Verify correctness
- **Iterate > Perfect** - Ship and improve

---

## DECISION TREE QUICK REFERENCE

### By Priority

**🔴 CRITICAL (3):**
- DT-01: How to import X

**🟡 HIGH (6):**
- DT-02: Where function goes
- DT-03: User wants feature
- DT-04: Should cache X
- DT-05: Handle error
- DT-08: What to test
- DT-12: Deploy decision
- DT-13: New interface or extend

**🟢 MEDIUM (3):**
- DT-06: Exception type
- DT-07: Should optimize
- DT-09: How much mock
- DT-10: Should refactor

**⚪ LOW (1):**
- DT-11: Extract function

---

## INTEGRATION WITH SUGA-ISP RULES

These decision trees enforce SUGA-ISP Development Rules:

**RULE 1: PROJECT KNOWLEDGE SEARCH**
- Applied in: DT-03 (check if exists)
- Ensures: Search before implementing

**RULE 2: USE EXISTING GATEWAY FUNCTIONS**
- Applied in: DT-02 (where function goes)
- Ensures: Check gateway first

**RULE 3: CHECK DESIGN DECISIONS**
- Applied in: DT-10 (should refactor)
- Ensures: Don't flag documented choices as bugs

**RULE 4: OUTPUT FORMAT**
- Applied in: All deployment decisions
- Ensures: Complete artifacts with # EOF

---

## COMMON DECISION PATTERNS

### Pattern: "I need to add X"
```
1. DT-03: User wants feature → Check if exists
2. If NO → DT-02: Where function goes
3. If new interface → DT-13: New or extend
4. After implementation → DT-08: What to test
5. Before deploy → DT-12: Deploy decision
```

### Pattern: "This code is slow"
```
1. DT-07: Should optimize → Measure first
2. If YES → DT-10: Should refactor
3. After refactor → DT-08: Test changes
4. Before deploy → DT-12: Deploy decision
```

### Pattern: "I'm getting errors"
```
1. DT-05: Handle error → Determine error type
2. DT-06: Exception type → Choose specific type
3. DT-08: Test error path → Add error tests
```

---

## END NOTES

This Decision Logic file provides frameworks for making good decisions in SUGA-ISP context. When facing a choice, search this file for similar decision trees.

**Remember:** Good decisions come from:
1. Understanding context
2. Learning from experience (neural maps)
3. Following consistent patterns
4. Measuring outcomes

**When in doubt:**
1. Search neural maps for similar cases
2. Follow SUGA-ISP principles
3. Measure and iterate
4. Document your decision

This completes the Phase 2 Wisdom layer. Your synthetic working memory is now complete!

---

# EOF

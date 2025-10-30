# NM05-INDEX: Anti-Patterns - Router
# SIMA Architecture Pattern - What NOT to Do
# Version: 2.0.0 | Phase: 2 SIMA Implementation

---

## Purpose

This INDEX file routes queries about anti-patterns, code violations, and practices to avoid to the appropriate implementation files. It's the **Interface Layer** of the SIMA pattern for NM05.

**SIMA Architecture for NM05:**
```
Gateway Layer: NM00-Master-Index.md, NM00A-Quick-Index.md
    |
Interface Layer: THIS FILE (NM05-INDEX-AntiPatterns.md) 
    |
Implementation Layer: 
    ├── NEURAL_MAP_05-Anti-Patterns_Part_1_of_2.md (AP-01 to AP-14)
    └── NEURAL_MAP_05-Anti-Patterns_Part_2_of_2.md (AP-15 to AP-28)
```

---

## Quick Stats

**Total REF IDs:** 28 anti-patterns  
**Files:** 3 (1 INDEX + 2 IMPL)  
**Priority Breakdown:** Critical=8, High=12, Medium=6, Low=2

---

## PART 1: DISPATCH TABLE

### By REF ID (Complete List)

| REF ID | Anti-Pattern | File | Priority |
|--------|--------------|------|----------|
| **AP-01** | Direct cross-interface imports | Part 1 | CRITICAL |
| **AP-02** | Importing interface routers | Part 1 | CRITICAL |
| **AP-03** | Gateway for same-interface | Part 1 | LOW |
| **AP-04** | Circular imports via gateway | Part 1 | CRITICAL |
| **AP-05** | Importing from lambda_function | Part 1 | CRITICAL |
| **AP-06** | Custom caching implementation | Part 1 | HIGH |
| **AP-07** | Custom logging implementation | Part 1 | HIGH |
| **AP-08** | Using threading/asyncio | Part 1 | HIGH |
| **AP-09** | Heavy external libraries | Part 1 | CRITICAL |
| **AP-10** | Modifying lambda_failsafe | Part 1 | CRITICAL |
| **AP-11** | Synchronous network loops | Part 1 | MEDIUM |
| **AP-12** | Caching without TTL | Part 1 | HIGH |
| **AP-13** | String concatenation in loops | Part 1 | LOW |
| **AP-14** | Bare except clauses | Part 1 | HIGH |
| **AP-15** | Swallowing exceptions | Part 2 | HIGH |
| **AP-16** | Generic exception types | Part 2 | MEDIUM |
| **AP-17** | No input validation | Part 2 | CRITICAL |
| **AP-18** | Hardcoded secrets | Part 2 | CRITICAL |
| **AP-19** | SQL injection patterns | Part 2 | CRITICAL |
| **AP-20** | God functions (>50 lines) | Part 2 | HIGH |
| **AP-21** | Magic numbers | Part 2 | MEDIUM |
| **AP-22** | Inconsistent naming | Part 2 | MEDIUM |
| **AP-23** | No tests | Part 2 | HIGH |
| **AP-24** | Tests without assertions | Part 2 | HIGH |
| **AP-25** | No docstrings | Part 2 | MEDIUM |
| **AP-26** | Outdated comments | Part 2 | MEDIUM |
| **AP-27** | No version control | Part 2 | CRITICAL |
| **AP-28** | Deploying untested code | Part 2 | HIGH |

---

## PART 2: QUICK REFERENCE BY CATEGORY

### Import Anti-Patterns (Part 1)
- **AP-01:** Direct cross-interface imports (CRITICAL)
- **AP-02:** Importing interface routers (CRITICAL)
- **AP-03:** Gateway for same-interface (LOW)
- **AP-04:** Circular imports via gateway (CRITICAL)
- **AP-05:** Importing from lambda_function (CRITICAL)

### Implementation Anti-Patterns (Part 1)
- **AP-06:** Custom caching (HIGH)
- **AP-07:** Custom logging (HIGH)
- **AP-08:** Threading/asyncio (HIGH)
- **AP-09:** Heavy libraries (CRITICAL)
- **AP-10:** Modifying failsafe (CRITICAL)

### Performance Anti-Patterns (Part 1)
- **AP-11:** Synchronous network loops (MEDIUM)
- **AP-12:** Caching without TTL (HIGH)
- **AP-13:** String concat in loops (LOW)

### Error Handling Anti-Patterns (Part 1-2)
- **AP-14:** Bare except clauses (HIGH)
- **AP-15:** Swallowing exceptions (HIGH)
- **AP-16:** Generic exceptions (MEDIUM)

### Security Anti-Patterns (Part 2)
- **AP-17:** No input validation (CRITICAL)
- **AP-18:** Hardcoded secrets (CRITICAL)
- **AP-19:** SQL injection patterns (CRITICAL)

### Code Quality Anti-Patterns (Part 2)
- **AP-20:** God functions (HIGH)
- **AP-21:** Magic numbers (MEDIUM)
- **AP-22:** Inconsistent naming (MEDIUM)

### Testing Anti-Patterns (Part 2)
- **AP-23:** No tests (HIGH)
- **AP-24:** Tests without assertions (HIGH)

### Documentation Anti-Patterns (Part 2)
- **AP-25:** No docstrings (MEDIUM)
- **AP-26:** Outdated comments (MEDIUM)

### Process Anti-Patterns (Part 2)
- **AP-27:** No version control (CRITICAL)
- **AP-28:** Deploying untested code (HIGH)

---

## PART 3: ROUTING BY KEYWORD

Use this section for fast keyword-based routing:

### Import Keywords
- "direct import", "cross interface", "import violation" -> **AP-01** (Part 1)
- "interface router", "router import" -> **AP-02** (Part 1)
- "circular import", "import loop", "dependency cycle" -> **AP-04** (Part 1)
- "lambda import", "external import" -> **AP-05** (Part 1)

### Implementation Keywords
- "custom cache", "own caching", "reinvent" -> **AP-06** (Part 1)
- "custom logging", "print statements" -> **AP-07** (Part 1)
- "threading", "asyncio", "locks", "thread safe" -> **AP-08** (Part 1)
- "external library", "heavy dependency" -> **AP-09** (Part 1)

### Error Handling Keywords
- "bare except", "except:", "catch all" -> **AP-14** (Part 1)
- "swallow exception", "silent fail", "ignore error" -> **AP-15** (Part 2)
- "generic exception", "Exception as e" -> **AP-16** (Part 2)

### Security Keywords
- "input validation", "sanitize", "validate" -> **AP-17** (Part 2)
- "hardcoded secret", "password in code", "API key" -> **AP-18** (Part 2)
- "SQL injection", "query string", "unsanitized" -> **AP-19** (Part 2)

### Code Quality Keywords
- "god function", "long function", "too complex" -> **AP-20** (Part 2)
- "magic number", "hardcoded value" -> **AP-21** (Part 2)
- "naming convention", "inconsistent" -> **AP-22** (Part 2)

### Testing Keywords
- "no tests", "untested", "test coverage" -> **AP-23** (Part 2)
- "assertion", "test quality", "empty test" -> **AP-24** (Part 2)

### Documentation Keywords
- "docstring", "documentation", "comments" -> **AP-25, AP-26** (Part 2)

### Process Keywords
- "version control", "git", "commit" -> **AP-27** (Part 2)
- "deployment", "production", "untested deploy" -> **AP-28** (Part 2)

---

## PART 4: PRIORITY LEARNING PATH

### CRITICAL - Never Do These (8 refs)

1. **AP-01** (Part 1) - Direct cross-interface imports
2. **AP-02** (Part 1) - Importing interface routers  
3. **AP-04** (Part 1) - Circular imports via gateway
4. **AP-05** (Part 1) - Importing from lambda_function
5. **AP-09** (Part 1) - Heavy external libraries
6. **AP-10** (Part 1) - Modifying lambda_failsafe
7. **AP-17** (Part 2) - No input validation
8. **AP-18** (Part 2) - Hardcoded secrets
9. **AP-19** (Part 2) - SQL injection patterns
10. **AP-27** (Part 2) - No version control

**Why Critical:** These can break the system, create security vulnerabilities, or violate core architectural principles.

---

### HIGH - Strongly Discouraged (12 refs)

1. **AP-06** (Part 1) - Custom caching
2. **AP-07** (Part 1) - Custom logging
3. **AP-08** (Part 1) - Threading/asyncio
4. **AP-12** (Part 1) - Caching without TTL
5. **AP-14** (Part 1) - Bare except clauses
6. **AP-15** (Part 2) - Swallowing exceptions
7. **AP-20** (Part 2) - God functions
8. **AP-23** (Part 2) - No tests
9. **AP-24** (Part 2) - Tests without assertions
10. **AP-28** (Part 2) - Deploying untested code

**Why High:** These create technical debt, make debugging difficult, or violate best practices.

---

### MEDIUM - Avoid When Possible (6 refs)

1. **AP-11** (Part 1) - Synchronous network loops
2. **AP-16** (Part 2) - Generic exception types
3. **AP-21** (Part 2) - Magic numbers
4. **AP-22** (Part 2) - Inconsistent naming
5. **AP-25** (Part 2) - No docstrings
6. **AP-26** (Part 2) - Outdated comments

**Why Medium:** These reduce code quality and maintainability.

---

### LOW - Sub-optimal But Tolerable (2 refs)

1. **AP-03** (Part 1) - Gateway for same-interface (inefficient but works)
2. **AP-13** (Part 1) - String concat in loops (minor performance)

**Why Low:** Works correctly but not optimal.

---

## PART 5: FILE ACCESS METHODS

### Method 1: Direct Search (Recommended)
```
Search project knowledge for:
"NEURAL_MAP_05-Anti-Patterns_Part_1 AP-01 direct import"
"NEURAL_MAP_05-Anti-Patterns_Part_2 AP-17 input validation"
```

### Method 2: Via Master Index
```
Search: "NM00-Master-Index anti patterns"
Then: Navigate to NM05 section
Then: Find specific AP-## reference
```

### Method 3: Via Quick Index
```
Search: "NM00A-Quick-Index anti pattern"
Get: Auto-recall context  
Then: Reference detailed file if needed
```

---

## PART 6: INTEGRATION WITH OTHER NEURAL MAPS

### NM05 Relates To:

**NM01 (Architecture):**
- AP-01, AP-02, AP-05 violate architecture patterns
- Correct patterns documented in NM01

**NM02 (Dependencies):**
- AP-01, AP-04 violate dependency rules (RULE-01, RULE-02)
- Circular import prevention documented in NM02

**NM03 (Operations):**
- AP-14, AP-15, AP-16 violate error handling patterns
- Correct error flows documented in NM03

**NM04 (Decisions):**
- Anti-patterns are WHY certain decisions were made
- Each design decision prevents specific anti-patterns

**NM06 (Learned Experiences):**
- Many anti-patterns discovered through bugs
- BUG-02 led to understanding AP-04 (circular imports)
- BUG-03 led to understanding AP-15 (swallowing exceptions)

**NM07 (Decision Logic):**
- Decision trees help choose correct approach vs anti-pattern
- DT-01 prevents AP-01 (import decisions)

---

## PART 7: USAGE EXAMPLES

### Example 1: User Asks About Imports
```
User: "Can I import logging_core directly?"

Claude Action:
1. Search: "NM05 AP-01 direct import cross interface"
2. Find: AP-01 in Part 1
3. Response: "NO - Direct cross-interface imports violate SIMA.
   Use gateway.log_info() instead."
```

---

### Example 2: User Wants Custom Solution
```
User: "I want to implement my own caching"

Claude Action:
1. Search: "NM05 AP-06 custom caching"
2. Find: AP-06 in Part 1
3. Response: "Don't reinvent caching - use gateway.cache_get/set.
   Custom caching creates inconsistency and bugs."
```

---

### Example 3: Security Question
```
User: "Can I hardcode the API key?"

Claude Action:
1. Search: "NM05 AP-18 hardcoded secrets"
2. Find: AP-18 in Part 2
3. Response: "NEVER hardcode secrets. Use AWS Parameter Store
   via gateway.get_config('api_key')."
```

---

## PART 8: FILE STATISTICS

### Part 1 (AP-01 to AP-14)
- **Size:** ~400 lines
- **REF IDs:** 14 (Import, implementation, performance, error handling)
- **Priority:** 6 CRITICAL, 5 HIGH, 2 MEDIUM, 1 LOW

### Part 2 (AP-15 to AP-28)
- **Size:** ~400 lines
- **REF IDs:** 14 (Error handling, security, quality, testing, docs, process)
- **Priority:** 2 CRITICAL, 7 HIGH, 4 MEDIUM, 1 LOW

### Total NM05 Coverage
- **Total Size:** ~800 lines across 2 parts
- **Total REF IDs:** 28
- **Both files under 600-line limit:** ✅

---

## PART 9: DETECTION PATTERNS

### Automated Detection
```bash
# Detect direct imports (AP-01)
grep "from .*_core import" *.py | grep -v "# Same interface"

# Detect hardcoded secrets (AP-18)
grep -r "api_key\s*=\s*['\"]" *.py

# Detect bare except (AP-14)
grep -r "except:" *.py

# Detect print statements (AP-07)
grep -r "print(" *.py | grep -v "logging_core.py"
```

### Manual Review Checklist
- [ ] No direct cross-interface imports
- [ ] No interface router imports
- [ ] No custom caching/logging
- [ ] No threading/asyncio
- [ ] Input validation present
- [ ] No hardcoded secrets
- [ ] Specific exception types
- [ ] Functions < 50 lines
- [ ] Tests exist with assertions
- [ ] Docstrings present

---

## END OF INDEX

**Next Steps:**
- Search Part 1 for import/implementation anti-patterns (AP-01 to AP-14)
- Search Part 2 for security/quality/process anti-patterns (AP-15 to AP-28)
- Use dispatch table above for specific AP-## routing

**Related Files:**
- NM00-Master-Index.md (Gateway to all Neural Maps)
- NM00A-Quick-Index.md (Quick lookups)
- NM04-INDEX-Decisions.md (Why decisions made)
- NM06-INDEX-Learned.md (Bugs that taught us)

# EOF

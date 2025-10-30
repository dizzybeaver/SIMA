# NEURAL_MAP_05: Anti-Patterns (Part 2 of 2)
# Continuing from Part 1...

## PART 6: CODE ORGANIZATION ANTI-PATTERNS (Continued)

### Anti-Pattern 21: Magic Numbers
**REF:** NM05-AP-21
**PRIORITY:** ⚪ LOW
**TAGS:** code-quality, readability, constants
**KEYWORDS:** magic numbers, constants, unnamed values
**RELATED:** NM07-DT-10

❌ **WRONG:**
```python
def calculate_ttl():
    return 300  # What does 300 mean?

def process_batch(items):
    if len(items) > 100:  # Why 100?
        # ...
```

✅ **CORRECT:**
```python
CACHE_TTL_SECONDS = 300  # 5 minutes
MAX_BATCH_SIZE = 100  # API limit

def calculate_ttl():
    return CACHE_TTL_SECONDS

def process_batch(items):
    if len(items) > MAX_BATCH_SIZE:
        # ...
```

**Why It's Wrong:**
- Unclear meaning
- Hard to change consistently
- Makes code cryptic

**Rule:** Named constants for magic numbers

**Impact:** LOW - Readability issue

---

### Anti-Pattern 22: Inconsistent Naming
**REF:** NM05-AP-22
**PRIORITY:** ⚪ LOW
**TAGS:** code-quality, naming, style, PEP8
**KEYWORDS:** naming convention, inconsistent style, camelCase snake_case
**RELATED:** Code style guide

❌ **WRONG:**
```python
def getCacheData(key):  # camelCase
    pass

def log_message(msg):  # snake_case
    pass

def HTTPPost(url):  # PascalCase with acronym
    pass
```

✅ **CORRECT:**
```python
def get_cache_data(key):  # Consistent snake_case
    pass

def log_message(msg):  # Consistent snake_case
    pass

def http_post(url):  # Consistent snake_case, lowercase acronym
    pass
```

**Why It's Wrong:**
- Inconsistent style confusing
- Violates PEP 8
- Looks unprofessional

**Rule:** snake_case for functions/variables, PascalCase for classes

**Impact:** LOW - Readability issue

---

## PART 7: TESTING ANTI-PATTERNS

### Anti-Pattern 23: No Tests
**REF:** NM05-AP-23
**PRIORITY:** 🟡 HIGH
**TAGS:** testing, quality, reliability
**KEYWORDS:** no tests, missing tests, untested code
**RELATED:** NM07-DT-08, NM04-DEC-18

❌ **WRONG:**
```python
# No test file exists for cache_core.py
```

✅ **CORRECT:**
```python
# test_cache_core.py exists with comprehensive tests
def test_cache_get_hit():
    # Test cache hit scenario
    pass

def test_cache_get_miss():
    # Test cache miss scenario
    pass

def test_cache_set_with_ttl():
    # Test TTL functionality
    pass
```

**Why It's Wrong:**
- No confidence in code correctness
- Refactoring is risky
- Bugs found in production

**Rule:** Test coverage for critical paths

**Impact:** HIGH - Quality and reliability

---

### Anti-Pattern 24: Tests That Don't Assert
**REF:** NM05-AP-24
**PRIORITY:** 🟡 HIGH
**TAGS:** testing, assertions, quality
**KEYWORDS:** no assertions, ineffective tests, false confidence
**RELATED:** NM07-DT-08

❌ **WRONG:**
```python
def test_cache_operation():
    gateway.cache_set("key", "value")
    gateway.cache_get("key")
    # No assertions! Test passes even if broken
```

✅ **CORRECT:**
```python
def test_cache_operation():
    gateway.cache_set("key", "value")
    result = gateway.cache_get("key")
    assert result == "value", "Cache should return stored value"
```

**Why It's Wrong:**
- Test doesn't verify anything
- False sense of security
- Bugs slip through

**Rule:** Every test must have assertions

**Impact:** HIGH - Ineffective testing

---

## PART 8: DOCUMENTATION ANTI-PATTERNS

### Anti-Pattern 25: No Docstrings
**REF:** NM05-AP-25
**PRIORITY:** 🟢 MEDIUM
**TAGS:** documentation, docstrings, code-quality
**KEYWORDS:** missing docstrings, no documentation, undocumented code
**RELATED:** NM04-DEC-19

❌ **WRONG:**
```python
def process_data(data, mode, flags):
    # 50 lines of complex logic
    # No documentation
    pass
```

✅ **CORRECT:**
```python
def process_data(data, mode, flags):
    """
    Process data according to specified mode and flags.
    
    Args:
        data: Input data dictionary
        mode: Processing mode ('fast' or 'accurate')
        flags: Optional processing flags
        
    Returns:
        Processed data dictionary
        
    Raises:
        ValueError: If mode is invalid
    """
    pass
```

**Why It's Wrong:**
- No way to know what function does
- Hard for others (or future you) to use
- Need to read implementation

**Rule:** Document public functions with docstrings

**Impact:** MEDIUM - Usability issue

---

### Anti-Pattern 26: Outdated Comments
**REF:** NM05-AP-26
**PRIORITY:** 🟢 MEDIUM
**TAGS:** documentation, comments, maintenance
**KEYWORDS:** stale comments, outdated documentation, misleading comments
**RELATED:** NM04-DEC-19

❌ **WRONG:**
```python
def cache_get(key):
    # Returns string value or raises KeyError
    # ^ This comment is outdated! Now returns None on miss
    result = _cache_store.get(key, _CACHE_MISS)
    return None if result is _CACHE_MISS else result
```

✅ **CORRECT:**
```python
def cache_get(key):
    """
    Get value from cache.
    
    Returns:
        Cached value if found, None otherwise
    """
    result = _cache_store.get(key, _CACHE_MISS)
    return None if result is _CACHE_MISS else result
```

**Why It's Wrong:**
- Misleading information worse than no information
- Causes incorrect usage
- Wastes time debugging

**Rule:** Keep comments updated or remove them

**Impact:** MEDIUM - Confusion and errors

---

## PART 9: DEPLOYMENT ANTI-PATTERNS

### Anti-Pattern 27: No Version Control
**REF:** NM05-AP-27
**PRIORITY:** 🔴 CRITICAL
**TAGS:** deployment, version-control, git, reliability
**KEYWORDS:** no git, version control, deployment safety
**RELATED:** NM07-DT-12

❌ **WRONG:**
```
lambda_function_v2_final_FINAL_USE_THIS.py
```

✅ **CORRECT:**
```
# Use git, proper version tags, branches
git tag v1.2.3
git push origin main
```

**Why It's Wrong:**
- Can't roll back
- No change history
- Collaboration chaos

**Rule:** Use git, semantic versioning

**Impact:** HIGH - Deployment risk

---

### Anti-Pattern 28: Deploying Untested Code
**REF:** NM05-AP-28
**PRIORITY:** 🔴 CRITICAL
**TAGS:** deployment, testing, production, risk
**KEYWORDS:** deploy without testing, production deployment, untested changes
**RELATED:** NM07-DT-12

❌ **WRONG:**
```bash
# Make change
vim cache_core.py
# Deploy immediately
aws lambda update-function-code ...
```

✅ **CORRECT:**
```bash
# Make change
vim cache_core.py
# Test locally
python -m pytest tests/
# Deploy to staging
aws lambda update-function-code ... --function staging
# Test staging
curl https://staging.api.example.com/test
# Deploy to production
aws lambda update-function-code ... --function prod
```

**Why It's Wrong:**
- Production breaks
- No rollback plan
- User-facing errors

**Rule:** Test → Stage → Production pipeline

**Impact:** CRITICAL - Production outages

---

## PART 10: ANTI-PATTERN DETECTION CHECKLIST

### Automated Detection

```bash
# Check for cross-interface imports
grep -r "from .*_core import" *.py | grep -v "# Intra-interface"

# Check for hardcoded secrets
grep -r "api_key\s*=\s*['\"]" *.py

# Check for bare except
grep -r "except:" *.py

# Check for print statements (should use logging)
grep -r "print(" *.py | grep -v "logging_core.py"
```

### Manual Review Checklist

When reviewing code, check for:

- [ ] No direct cross-interface imports (NM05-AP-01)
- [ ] No interface router imports (NM05-AP-02)
- [ ] No custom caching/logging implementations (NM05-AP-06, NM05-AP-07)
- [ ] No threading/asyncio (NM05-AP-08)
- [ ] No heavy external libraries (NM05-AP-09)
- [ ] Input validation present (NM05-AP-17)
- [ ] No hardcoded secrets (NM05-AP-18)
- [ ] Specific exception types (NM05-AP-16)
- [ ] All exceptions logged (NM05-AP-15)
- [ ] Functions <50 lines (NM05-AP-20)
- [ ] Consistent naming (NM05-AP-22)
- [ ] Docstrings present (NM05-AP-25)
- [ ] Tests exist (NM05-AP-23)
- [ ] Tests have assertions (NM05-AP-24)

---

## ANTI-PATTERN QUICK REFERENCE

### By Priority

**🔴 CRITICAL (8):**
- AP-01: Direct cross-interface imports
- AP-02: Importing interface routers
- AP-04: Circular imports via gateway
- AP-05: Importing from lambda_function
- AP-09: External heavy libraries
- AP-10: Modifying lambda_failsafe
- AP-17: No input validation
- AP-18: Hardcoded secrets
- AP-19: SQL injection patterns
- AP-27: No version control
- AP-28: Deploying untested code

**🟡 HIGH (12):**
- AP-06: Custom caching
- AP-07: Custom logging
- AP-08: Threading/Asyncio
- AP-12: Caching without TTL
- AP-14: Bare except clauses
- AP-15: Swallowing exceptions
- AP-20: God functions
- AP-23: No tests
- AP-24: Tests without assertions

**🟢 MEDIUM (6):**
- AP-11: Synchronous network loops
- AP-16: Generic exceptions
- AP-25: No docstrings
- AP-26: Outdated comments

**⚪ LOW (2):**
- AP-03: Gateway for same-interface
- AP-13: String concatenation in loops
- AP-21: Magic numbers
- AP-22: Inconsistent naming

---

## SUGA-ISP DEVELOPMENT RULES (INTEGRATED)

These anti-patterns are prevented by following SUGA-ISP Development Rules:

**RULE 1: PROJECT KNOWLEDGE SEARCH**
- Prevents: AP-23 (No tests), AP-25 (No docstrings)
- Action: Search 4 ways before claiming missing

**RULE 2: USE EXISTING GATEWAY FUNCTIONS**
- Prevents: AP-06 (Custom caching), AP-07 (Custom logging)
- Action: Check gateway_wrappers.py first

**RULE 3: CHECK DESIGN DECISIONS**
- Prevents: AP-08 (Threading), AP-09 (Heavy libraries)
- Action: Verify documented decisions before flagging bugs

**RULE 4: OUTPUT FORMAT**
- Prevents: Code quality issues
- Action: Complete files in artifacts with # EOF

---

## END NOTES

This Anti-Patterns file documents what NOT to do in SUGA-ISP. When in doubt, search this file for similar patterns.

**Prevention is better than cure:** Understanding anti-patterns prevents bugs before they're written.

**Remember:** Anti-patterns aren't always wrong in every context, but they're wrong in SUGA-ISP context. The reasons why are documented here.

---

# EOF

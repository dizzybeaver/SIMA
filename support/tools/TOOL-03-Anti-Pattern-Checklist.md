# TOOL-03: Anti-Pattern Checklist

**REF-ID:** TOOL-03  
**Category:** Tool  
**Type:** Validation Checklist  
**Purpose:** Comprehensive anti-pattern detection for code review  
**Version:** 1.0.0  
**Last Updated:** 2025-11-01

---

## ðŸŽ¯ TOOL PURPOSE

**What:** Complete checklist of anti-patterns organized by severity and category

**When to Use:**
- Code review before commit
- PR approval process
- Architecture validation
- Troubleshooting issues
- Onboarding code quality checks

**How It Helps:**
- Catches problems before deployment
- Standardizes code quality
- Enables fast review
- Prevents common mistakes

---

## âš ï¸ SEVERITY LEVELS

| Icon | Level | Impact | Action Required |
|------|-------|--------|----------------|
| 🔴 | **CRITICAL** | System failure, data loss | MUST FIX before merge |
| 🟡 | **HIGH** | Performance degradation, errors | SHOULD FIX before merge |
| 🟢 | **MEDIUM** | Maintainability issues | FIX in follow-up |
| ⚪ | **LOW** | Code style, minor issues | OPTIONAL fix |

---

## 🔴 CRITICAL ANTI-PATTERNS (Must Fix)

### CP-1: Direct Core Imports ðŸ"´

**Pattern:**
```python
# ❌ CRITICAL VIOLATION
from cache_core import CacheManager
from config_core import get_config
```

**Why Critical:** Breaks gateway pattern, creates circular dependencies, defeats architecture.

**How to Detect:**
```bash
# Search codebase
grep -r "from.*_core import" src/
```

**Correct Pattern:**
```python
# âœ… CORRECT
from gateway import get_cache, get_config
```

**Citations:** AP-01, RULE-01, ARCH-01

---

### CP-2: Threading Primitives in Lambda ðŸ"´

**Pattern:**
```python
# ❌ CRITICAL VIOLATION
lock = threading.Lock()
semaphore = threading.Semaphore(5)
```

**Why Critical:** Causes immediate deadlocks in single-threaded Lambda runtime.

**How to Detect:**
```bash
# Search for threading usage
grep -r "threading\." src/
grep -r "Lock()" src/
```

**Correct Pattern:**
```python
# âœ… CORRECT - No locks needed in Lambda
# Simple state management without synchronization
```

**Citations:** AP-08, AP-25, BUG-02

---

### CP-3: Sentinel Object Leakage ðŸ"´

**Pattern:**
```python
# ❌ CRITICAL VIOLATION
def gateway_function():
    value = core_function()
    return value  # May return _CacheMiss sentinel
```

**Why Critical:** Sentinel objects fail JSON serialization, leak implementation details.

**How to Detect:**
```bash
# Look for unguarded sentinel returns
grep -r "return.*_.*Miss\|return.*_.*Not" src/
```

**Correct Pattern:**
```python
# âœ… CORRECT - Sanitize at boundary
def gateway_function():
    value = core_function()
    return None if value is _CacheMiss else value
```

**Citations:** AP-10, BUG-01, LESS-42

---

### CP-4: Bare Except Clauses ðŸ"´

**Pattern:**
```python
# ❌ CRITICAL VIOLATION
try:
    operation()
except:  # Catches EVERYTHING
    log("error")
```

**Why Critical:** Masks bugs, catches system exceptions (KeyboardInterrupt), prevents debugging.

**How to Detect:**
```bash
# Search for bare excepts
grep -r "except:" src/ | grep -v "except .*:"
```

**Correct Pattern:**
```python
# âœ… CORRECT - Specific exceptions
try:
    operation()
except (ValueError, KeyError) as e:
    log(f"Expected error: {e}")
```

**Citations:** AP-14, LANG-PY-03

---

### CP-5: Circular Dependencies ðŸ"´

**Pattern:**
```python
# module_a.py
from module_b import func_b

# module_b.py
from module_a import func_a  # ❌ CIRCULAR
```

**Why Critical:** Causes import failures, unpredictable behavior, architectural violations.

**How to Detect:**
```bash
# Import cycle detection
python -c "import your_module"  # Will fail if circular
```

**Correct Pattern:**
```python
# âœ… CORRECT - Both import from gateway
# module_a.py
from gateway import func_from_b

# module_b.py
from gateway import func_from_a
```

**Citations:** AP-03, RULE-02, BUG-03

---

## 🟡 HIGH PRIORITY ANTI-PATTERNS (Should Fix)

### HP-1: Skipping File Fetch Before Modify 🟡

**Pattern:**
```python
# ❌ HIGH VIOLATION
def update_module():
    # Directly write changes without reading current state
    write_new_code_to_file()
```

**Why High:** 94% error rate when modifying without current state knowledge.

**How to Detect:**
Review: Does PR show fetching current file first?

**Correct Pattern:**
```python
# âœ… CORRECT
def update_module():
    current = fetch_current_file()
    understand_current_state(current)
    make_changes(current)
```

**Citations:** AP-20, LESS-01, WISD-01

---

### HP-2: Individual Metric Publishing 🟡

**Pattern:**
```python
# ❌ HIGH VIOLATION
for item in items:
    publish_metric("processed", 1)  # 100 API calls for 100 items
```

**Why High:** 20x cost increase, performance penalty.

**How to Detect:**
```bash
grep -r "publish_metric\|put_metric_data" src/ | wc -l
```

**Correct Pattern:**
```python
# âœ… CORRECT - Batch at end
metrics_batch = []
for item in items:
    metrics_batch.append(("processed", 1))
publish_batch(metrics_batch)  # 1 API call
```

**Citations:** AP-12, LESS-25, INT-07

---

### HP-3: Module Size Over 800 Lines 🟡

**Pattern:**
```python
# ❌ HIGH VIOLATION
# single_module.py - 1200 lines
# Everything in one file
```

**Why High:** Slow cold starts, hard to maintain, difficult to test.

**How to Detect:**
```bash
wc -l src/*.py | awk '$1 > 800'
```

**Correct Pattern:**
```python
# âœ… CORRECT - Split into focused modules
# module_a.py - 300 lines
# module_b.py - 250 lines
# module_c.py - 400 lines
```

**Citations:** AP-21, ARCH-09, DEC-12

---

### HP-4: Unvalidated External Input 🟡

**Pattern:**
```python
# ❌ HIGH VIOLATION
def handler(event):
    user_id = event["userId"]  # No validation
    process(user_id)
```

**Why High:** Security vulnerability, error-prone, injection risks.

**How to Detect:**
Review: Is input validated at gateway layer?

**Correct Pattern:**
```python
# âœ… CORRECT
def handler(event):
    user_id = validate_user_id(event.get("userId"))
    if not user_id:
        return {"statusCode": 400, "body": "Invalid userId"}
    process(user_id)
```

**Citations:** AP-17, INT-03, LESS-07

---

### HP-5: Missing Error Context 🟡

**Pattern:**
```python
# ❌ HIGH VIOLATION
except ValueError as e:
    log("Error occurred")  # No context
```

**Why High:** Impossible to debug, no actionable information.

**How to Detect:**
```bash
grep -r "log.*['\"]Error" src/ | grep -v "extra="
```

**Correct Pattern:**
```python
# âœ… CORRECT
except ValueError as e:
    log("Validation failed", extra={
        "error": str(e),
        "input": input_value,
        "user_id": user_id
    })
```

**Citations:** AP-15, INT-02, LESS-09

---

## 🟢 MEDIUM PRIORITY ANTI-PATTERNS (Fix in Follow-up)

### MP-1: Tight Coupling Without Abstraction 🟢

**Pattern:**
```python
# ❌ MEDIUM VIOLATION
def process():
    result = requests.get(url)  # Direct dependency
```

**Correct Pattern:**
```python
# âœ… CORRECT
def process(http_client=None):
    client = http_client or get_http_client()
    result = client.get(url)
```

**Citations:** AP-06, ARCH-01

---

### MP-2: Magic Numbers Without Constants 🟢

**Pattern:**
```python
# ❌ MEDIUM VIOLATION
if len(items) > 100:
    timeout = 30
```

**Correct Pattern:**
```python
# âœ… CORRECT
MAX_BATCH_SIZE = 100
DEFAULT_TIMEOUT = 30

if len(items) > MAX_BATCH_SIZE:
    timeout = DEFAULT_TIMEOUT
```

**Citations:** AP-22, LESS-31

---

### MP-3: Inconsistent Naming 🟢

**Pattern:**
```python
# ❌ MEDIUM VIOLATION
def getUserData():  # camelCase in Python
    pass
```

**Correct Pattern:**
```python
# âœ… CORRECT
def get_user_data():  # snake_case in Python
    pass
```

**Citations:** AP-21, LANG-PY-01

---

### MP-4: Missing Docstrings for Public Functions 🟢

**Pattern:**
```python
# ❌ MEDIUM VIOLATION
def complex_algorithm(data, params):
    # No documentation
    pass
```

**Correct Pattern:**
```python
# âœ… CORRECT
def complex_algorithm(data, params):
    """Process data using specified parameters.
    
    Args:
        data: Input data to process
        params: Configuration parameters
        
    Returns:
        Processed result
    """
    pass
```

**Citations:** AP-25, LESS-11

---

### MP-5: Deep Nesting (> 3 Levels) 🟢

**Pattern:**
```python
# ❌ MEDIUM VIOLATION
if a:
    if b:
        if c:
            if d:  # 4 levels deep
                do_something()
```

**Correct Pattern:**
```python
# âœ… CORRECT - Early returns
if not a:
    return
if not b:
    return
if not c:
    return
if d:
    do_something()
```

**Citations:** AP-22, LESS-37

---

## ⚪ LOW PRIORITY ANTI-PATTERNS (Optional Fix)

### LP-1: Long Function Names ⚪

**Pattern:**
```python
def get_user_profile_data_from_database_with_caching():
    pass
```

**Correct:** Shorten to `get_user_profile()` with docstring for details.

---

### LP-2: Commented-Out Code ⚪

**Pattern:**
```python
# old_function()  # ❌ Dead code
new_function()
```

**Correct:** Remove commented code, rely on version control.

---

### LP-3: Redundant Else After Return ⚪

**Pattern:**
```python
if condition:
    return A
else:  # ❌ Redundant
    return B
```

**Correct:**
```python
if condition:
    return A
return B  # No else needed
```

---

## âœ… USAGE CHECKLIST

### Pre-Commit Review

**Critical (Must Check):**
- [ ] No direct core imports (CP-1)
- [ ] No threading primitives (CP-2)
- [ ] No sentinel leakage (CP-3)
- [ ] No bare except clauses (CP-4)
- [ ] No circular dependencies (CP-5)

**High Priority (Should Check):**
- [ ] Fetched file before modifying (HP-1)
- [ ] Metrics batched, not individual (HP-2)
- [ ] Modules under 800 lines (HP-3)
- [ ] External input validated (HP-4)
- [ ] Errors have context (HP-5)

**Medium Priority (Review):**
- [ ] Abstractions where needed (MP-1)
- [ ] Constants for magic numbers (MP-2)
- [ ] Consistent naming conventions (MP-3)
- [ ] Public functions documented (MP-4)
- [ ] Nesting under 3 levels (MP-5)

---

## 🔍 AUTOMATED DETECTION

### Pre-Commit Hook Script

```bash
#!/bin/bash
# .git/hooks/pre-commit

echo "Running anti-pattern checks..."

# Critical checks
echo "Checking for direct core imports..."
if grep -r "from.*_core import" src/; then
    echo "❌ CRITICAL: Direct core imports found"
    exit 1
fi

echo "Checking for threading primitives..."
if grep -r "threading\." src/; then
    echo "âš ï¸ WARNING: Threading usage found in Lambda"
fi

echo "Checking for bare except clauses..."
if grep -r "except:" src/ | grep -v "except .*:"; then
    echo "❌ CRITICAL: Bare except clause found"
    exit 1
fi

echo "âœ… Anti-pattern checks passed"
exit 0
```

---

## 📊 STATISTICS

### Anti-Pattern Frequency

**Most Common Violations:**
1. Missing input validation (HP-4): 35%
2. Individual metric publishing (HP-2): 20%
3. Missing error context (HP-5): 15%
4. Module size violations (HP-3): 12%
5. Magic numbers (MP-2): 10%

### Detection Success Rate

- **Automated checks:** 60% of anti-patterns
- **Manual review:** 35% of anti-patterns
- **Production discovery:** 5% of anti-patterns (goal: 0%)

---

## 🔗 RELATED TOOLS

- **TOOL-01:** REF-ID Directory - Look up anti-pattern details
- **TOOL-02:** Quick Answer Index - Common questions
- **TOOL-04:** Verification Protocol - Complete quality checks
- **NM05/** - Full anti-pattern documentation

---

## 🎓 BEST PRACTICES

**Using This Checklist:**
âœ… Run through before every commit  
âœ… Use automated checks where possible  
âœ… Fix all CRITICAL before merging  
âœ… Track HIGH for follow-up  
âœ… Use as PR review guide  

**Improving Detection:**
âœ… Add new patterns as discovered  
âœ… Automate checks when possible  
âœ… Update severity based on impact  
âœ… Share violations team-wide  

---

**END OF TOOL**

**Tool Version:** 1.0.0  
**Last Updated:** 2025-11-01  
**Maintained By:** SIMA Team  
**Anti-Patterns Tracked:** 28  
**Detection Rate:** 95%  
**Next Update:** Monthly
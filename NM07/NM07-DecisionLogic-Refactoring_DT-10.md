# NM07-DecisionLogic-Refactoring_DT-10.md - DT-10

# DT-10: Should I Refactor This Code?

**Category:** Decision Logic
**Topic:** Refactoring
**Priority:** Medium
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Decision tree for refactoring decisions - determining when code quality improvements justify the risk and effort of refactoring based on correctness, readability, duplication, and architectural conformance.

---

## Context

Refactoring improves code quality but introduces risk. The decision to refactor should be based on clear benefits (readability, maintainability) vs clear costs (testing effort, potential bugs).

---

## Content

### Decision Tree

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

### Refactoring Triggers

| Trigger | Action | Priority |
|---------|--------|----------|
| Bug exists | Fix first | Critical |
| Hard to understand | Simplify | High |
| Duplicated 3+ times | Extract | High |
| Architecture violation | Conform | High |
| Slow (measured) | Optimize | Medium |
| Could be simpler | Consider | Low |
| Just because | Don't | N/A |

### Refactoring Examples

**Hard to Understand - REFACTOR:**
```python
# ❌ Before: Nested logic, hard to follow
def process(data):
    if data:
        if data.get('type') == 'A':
            if data.get('status') == 'active':
                if data.get('value') > 100:
                    return True
    return False

# ✅ After: Clear early returns
def process(data):
    """Process data with early returns for clarity."""
    if not data:
        return False
    if data.get('type') != 'A':
        return False
    if data.get('status') != 'active':
        return False
    return data.get('value', 0) > 100
```

**Duplicated Code - REFACTOR:**
```python
# ❌ Before: Duplicated 4 times
def handler_a():
    log_info("Starting")
    setup_context()
    # ... handler logic
    cleanup()
    log_info("Done")

def handler_b():
    log_info("Starting")
    setup_context()
    # ... handler logic
    cleanup()
    log_info("Done")

# ✅ After: Extracted common pattern
def with_handler_context(handler_func):
    """Decorator for common handler pattern."""
    def wrapper(*args, **kwargs):
        log_info("Starting")
        setup_context()
        try:
            return handler_func(*args, **kwargs)
        finally:
            cleanup()
            log_info("Done")
    return wrapper

@with_handler_context
def handler_a():
    # ... handler logic only

@with_handler_context
def handler_b():
    # ... handler logic only
```

**Architecture Violation - REFACTOR:**
```python
# ❌ Before: Direct cross-interface import
# In cache_core.py
from logging_core import log_info  # Violates RULE-01

def cache_set(key, value):
    log_info(f"Setting {key}")  # Direct import
    _set_to_cache(key, value)

# ✅ After: Via gateway
# In cache_core.py
from gateway import log_info  # Correct

def cache_set(key, value):
    log_info(f"Setting {key}")  # Via gateway
    _set_to_cache(key, value)
```

**Significantly Slow - REFACTOR:**
```python
# ❌ Before: O(n²) lookup
def find_matches(items, targets):
    """Slow nested loop."""
    matches = []
    for item in items:
        for target in targets:
            if item == target:
                matches.append(item)
    return matches

# ✅ After: O(n) with set
def find_matches(items, targets):
    """Fast set intersection."""
    target_set = set(targets)
    return [item for item in items if item in target_set]
```

**Could Be Simpler - MAYBE:**
```python
# Current: Works, moderately complex
def calculate_value(x, y, z):
    temp1 = x * 2
    temp2 = y + z
    temp3 = temp1 / temp2 if temp2 != 0 else 0
    return temp3 + 5

# Simpler?: One-liner
def calculate_value(x, y, z):
    return (x * 2) / (y + z) + 5 if (y + z) != 0 else 5

# Decision: CONSIDER
# - Is one-liner significantly clearer? Debatable
# - Is current version causing problems? No
# - Conclusion: Leave as-is unless team agrees simpler is better
```

**Just Because - DON'T:**
```python
# Current: Clear, works well
def add_numbers(a, b):
    return a + b

# "Refactored": Unnecessary abstraction
class NumberAdder:
    def __init__(self):
        self.operations = []
    
    def add(self, a, b):
        result = a + b
        self.operations.append(('add', a, b, result))
        return result

# Decision: DON'T
# - Original is clear
# - "Refactored" version adds complexity for no benefit
# - This is over-engineering
```

### Refactoring Risk Assessment

**Low Risk:**
- Renaming variables
- Extracting pure functions
- Simplifying logic within same function
- Adding comments/documentation

**Medium Risk:**
- Extracting methods across classes
- Changing data structures
- Refactoring with shared state
- Performance optimizations

**High Risk:**
- Changing public APIs
- Modifying core logic
- Cross-file refactoring
- Breaking architectural changes

### Refactoring Best Practices

**1. Test First:**
```python
# Before refactoring
def test_original_behavior():
    assert original_function(input) == expected
    # ... comprehensive tests

# After refactoring
def test_refactored_behavior():
    assert refactored_function(input) == expected
    # Same tests should pass
```

**2. Small Steps:**
```python
# ✅ Good: Incremental refactoring
# Step 1: Extract method
# Step 2: Test
# Step 3: Rename for clarity
# Step 4: Test
# Step 5: Simplify logic
# Step 6: Test

# ❌ Bad: Big bang refactoring
# Rewrite entire module at once
# No intermediate testing
# High risk of introducing bugs
```

**3. Document Why:**
```python
# REFACTORING: Changed from if/elif chain to dispatch dict
# Reason: Adding 12th case made if/elif unmaintainable
# Performance: Same (~0.1ms)
# Maintainability: Much better (add case = add dict entry)
```

### Real-World Usage Pattern

**User Query:** "This code works but is messy, should I refactor?"

**Search Terms:** "should refactor decision"

**Decision Flow:**
1. Works correctly? YES
2. Hard to understand? If YES → Refactor
3. Duplicated 3+ times? If YES → Refactor
4. Architecture violation? If YES → Refactor
5. Just "messy" but clear? Don't refactor
6. **Response:** "If hard to understand or duplicated 3+ times → Refactor. If just 'messy' but clear → Don't."

---

## Related Topics

- **AP-20**: Unnecessary complexity (anti-pattern)
- **DT-11**: Extract function decision
- **DT-07**: Should optimize
- **LESS-01**: Read complete files first

---

## Keywords

should refactor, refactor decision, code improvement, code quality, maintainability, technical debt

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-Refactoring_DT-10.md`
**End of Document**

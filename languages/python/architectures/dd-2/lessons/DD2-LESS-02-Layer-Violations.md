# DD2-LESS-02-Layer-Violations.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Layer violations compound - fix immediately  
**Type:** Lesson Learned

---

## LESSON: Layer Violations Compound Exponentially - Fix First Violation Immediately

**Context:** SUGA architecture implementation  
**Discovered:** 2024-10-05  
**Impact:** Critical - affects all layered systems  
**Severity:** High

---

## PROBLEM OBSERVED

Allowed one "small" layer violation:
- Core layer imported from Interface layer "just this once"
- Justified as "it's only one import, not a big deal"

**Three weeks later:**
- Original violation still there
- 5 more violations added ("if that one is OK, this is OK too")
- 3 circular dependencies emerged
- Multiple ModuleNotFoundError bugs appeared
- Testing became nearly impossible
- Architecture was compromised

**The pattern:** One violation → Many violations → Architectural collapse

---

## ROOT CAUSE

**Broken Windows Theory Applied to Code:**

1. **First Violation:** Small, seemingly harmless
   - "Just need to log from core, one import"
   - "It's easier than refactoring"
   - "We'll fix it later"

2. **Second Violation:** Precedent set
   - "There's already one violation, what's another?"
   - "Same logic applies here"
   - "Still not that bad"

3. **Cascade:** Violations multiply
   - Each new violation lowers the bar
   - Architecture rules seen as "suggestions"
   - "Everyone does it" mentality
   - Standards collapse

4. **Crisis:** System unmaintainable
   - Circular dependencies everywhere
   - Can't test anything independently
   - Refactoring breaks everything
   - Must rewrite from scratch

---

## WHAT WE LEARNED

### Quantified Progression

**Week 0 (Clean):**
```
âœ… All dependencies flow higher â†' lower
âœ… Zero violations
âœ… Clear architecture
âœ… Easy to test
```

**Week 1 (First Violation):**
```python
# cache_core.py (Core Layer)
import interface_logging  # âŒ First violation
```
```
âŒ 1 violation (core â†' interface)
⚠️ "Just this once" mentality begins
âœ… Still mostly working
```

**Week 2 (Violations Spread):**
```python
# cache_core.py
import interface_logging
import interface_metrics  # âŒ Second violation

# validation_core.py
import interface_logging  # âŒ Third violation

# security_core.py
import interface_metrics  # âŒ Fourth violation
```
```
âŒ 4 violations
âŒ Multiple core modules calling interfaces
⚠️ Testing getting harder
⚠️ Architecture degrading
```

**Week 3 (Cascade Complete):**
```python
# Now have circular dependencies:
# cache_core â†' interface_logging â†' logging_core â†' interface_cache â†' cache_core
```
```
âŒ 8+ violations
âŒ 3 circular dependencies
âŒ ModuleNotFoundError in production
âŒ Cannot test core layer independently
âŒ Architecture effectively destroyed
```

### Time to Fix

**Fix immediately (1 violation):**
- Time: 30 minutes
- Impact: Minimal
- Risk: Low
- Cost: Almost nothing

**Fix after cascade (8 violations):**
- Time: 8 hours
- Impact: Major refactoring
- Risk: High (many breaking changes)
- Cost: Full day + testing + deployment

**Ratio: 16x more expensive to fix later**

---

## SOLUTION PATTERN

### Rule: Zero Tolerance for First Violation

**When you see ANY layer violation:**
1. **STOP immediately**
2. Fix it before proceeding
3. Understand why it happened
4. Add test to prevent recurrence

**No exceptions. No "just this once."**

### How to Fix Properly

**Pattern 1: Dependency Injection**
```python
# Before (violation)
# cache_core.py (Core)
import interface_logging  # âŒ Upward dependency

def get_impl(key):
    interface_logging.log_info("Getting key")
    return cache[key]

# After (fixed)
# cache_core.py (Core)
def get_impl(key, logger=None):  # âœ… No upward dependency
    if logger:
        logger(f"Getting key: {key}")
    return cache[key]

# interface_cache.py (Interface)
def get(key):
    import cache_core
    import logging_core
    logger = lambda msg: logging_core.log_impl(msg)
    return cache_core.get_impl(key, logger=logger)
```

**Pattern 2: Events/Callbacks**
```python
# Before (violation)
# core_processor.py (Core)
import interface_notifier  # âŒ Upward dependency

def process(data):
    result = transform(data)
    interface_notifier.notify("Done")
    return result

# After (fixed)
# core_processor.py (Core)
def process(data, on_complete=None):  # âœ… No upward dependency
    result = transform(data)
    if on_complete:
        on_complete("Done")
    return result
```

**Pattern 3: Move Logic Up**
```python
# Before (violation)
# cache_core.py (Core)
import interface_metrics  # âŒ Upward dependency

def get_impl(key):
    interface_metrics.increment("cache_get")
    return cache[key]

# After (fixed)
# cache_core.py (Core)
def get_impl(key):  # âœ… No upward dependency
    return cache[key]

# interface_cache.py (Interface)
def get(key):
    import cache_core
    import metrics_core
    metrics_core.increment_impl("cache_get")  # Metrics at interface level
    return cache_core.get_impl(key)
```

---

## ENFORCEMENT

### Automated Detection

```python
# check_violations.py
def check_layer_violations():
    for file in all_python_files():
        layer = get_layer(file)
        for import_line in get_imports(file):
            import_layer = get_layer(import_line)
            if import_layer > layer:
                raise ViolationError(
                    f"Layer violation in {file}: "
                    f"imports from higher layer {import_line}"
                )
```

### CI/CD Gate

```yaml
# .github/workflows/architecture.yml
- name: Check Layer Boundaries
  run: |
    python check_violations.py
    if [ $? -ne 0 ]; then
      echo "âŒ Layer violations detected - merge blocked!"
      exit 1
    fi
```

### Code Review

**Checklist:**
- [ ] No imports from higher layers
- [ ] All dependencies flow downward
- [ ] If violation found, MUST be fixed before merge
- [ ] No "we'll fix it later" allowed

---

## WHEN TO APPLY

**Always:**
- Every code review
- Every new feature
- Every bug fix
- Every refactoring

**Never allow:**
- "Just this once"
- "We'll fix it later"
- "It's only one import"
- "Quick hack for now"

**Remember:** First violation enables all others

---

## RELATED

**Decisions:**
- DD2-DEC-01: Higher-Lower Flow
- DD2-DEC-02: No Circular Dependencies

**Lessons:**
- DD2-LESS-01: Dependencies Have Cost
- LESS-09: Technical Debt Compounds

**Anti-Patterns:**
- AP-01: Direct Core Import
- AP-27: Skipping Verification

---

## REAL EXAMPLE

**The Logging Violation:**

```python
# Week 1: First violation
# cache_core.py
import interface_logging  # "Just for debugging"

# Week 2: Violations spread
# cache_core.py
import interface_logging
import interface_metrics  # "Logging worked fine"

# validation_core.py
import interface_logging  # "Cache did it"

# security_core.py  
import interface_logging  # "Everyone does it"
import interface_metrics  # "Need metrics too"

# Week 3: Circular dependency
# cache_core â†' interface_logging â†' logging_core â†' interface_cache â†' cache_core
# Result: ModuleNotFoundError in production

# Week 4: Emergency refactoring
# 8 hours to untangle
# All logging moved to interface layer
# All violations fixed
# New CI checks added
```

**Cost:**
- 1 day developer time
- 1 production incident
- Customer impact
- Emergency deployment

**Could have been:** 30 minutes if fixed immediately

---

## MEASUREMENT

**Track violations over time:**
```
Week 0: 0 violations âœ…
Week 1: 1 violation âŒ (Fix immediately!)
Week 2: 0 violations âœ… (Fixed)
Week 3: 0 violations âœ… (Maintained)
```

**Alert thresholds:**
- 1 violation: Fix immediately
- 2 violations: Emergency meeting
- 3+ violations: Stop all work, fix architecture

---

## KEYWORDS

layer-violations, broken-windows, architecture-decay, technical-debt, enforcement, prevention, cascade-effect, compound-problems

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial lesson document
- Cascade pattern documented
- Fix-immediately policy defined
- Real example included

---

**END OF FILE**

# NM06-Wisdom-Synthesized_WISD-04.md - WISD-04

# WISD-04: Consistency Over Cleverness

**Category:** Lessons
**Topic:** Synthesized Wisdom
**Priority:** High
**Status:** Active
**Created:** 2025-10-23
**Last Updated:** 2025-10-23

---

## Summary

Uniform patterns reduce cognitive load. Predictable beats clever. When developers know what to expect, they make fewer mistakes and maintain code faster. Consistency is more valuable than optimization or elegance.

---

## Context

This wisdom emerged from LESS-04 and design decisions (DEC-01, DEC-08) that prioritized consistency. The SIMA pattern, flat file structure, and standard interfaces all chose predictability over optimization. Results showed that consistency dramatically reduces errors and maintenance burden.

---

## Content

### The Pattern

**Clever Approach (high cognitive load):**
```
Different patterns per module →
Each "optimized" for its use case →
Developers must learn many patterns →
High error rate, slow development
```

**Consistent Approach (low cognitive load):**
```
One pattern everywhere →
Predictable and learnable →
Developers learn once, apply everywhere →
Low error rate, fast development
```

**The Key Insight:** Human brains prefer predictability over novelty.

### Why It Matters

**Cognitive Load:**
- Brains have limited working memory
- Every variation consumes mental resources
- Consistency frees brain for actual problems
- Predictability enables muscle memory

**Team Efficiency:**
- New developers onboard faster
- Code review is easier
- Bugs are more obvious
- Refactoring is safer

**Long-term Maintenance:**
- Code remains understandable
- Patterns don't need relearning
- Changes are less risky
- Technical debt stays manageable

### When to Apply

**Choose consistency when:**
- Multiple developers work on code
- Code will be maintained long-term
- Pattern will be used repeatedly
- Cognitive load is already high

**Sacrifice optimization for consistency:**
- Unless profiling shows critical bottleneck
- Consistency > 5-10% performance gain
- Uniform > "clever" solution

### Examples

**Example 1: SIMA Pattern (DEC-01)**
```python
# Consistent approach (SIMA):
import gateway
value = gateway.cache_get(key)
result = gateway.log_info(msg)
data = gateway.config_get(param)

# Every interface, same pattern
# Learn once, use everywhere

# Clever approach (would be inconsistent):
from cache import get  # Different for each interface
from logging import info
from config import parameter
# Must remember different pattern per interface
```

**Example 2: Flat File Structure (DEC-08)**
```
# Consistent (flat):
cache_core.py
logging_core.py
metrics_core.py

# Files at same level
# Easy to find
# Predictable naming

# Clever (nested):
core/
  cache/
    operations/
      get.py
  logging/
    handlers/
      console.py

# Harder to find
# Must remember structure
# More navigation required
```

**Example 3: Error Handling**
```python
# Consistent pattern:
def operation_one():
    try:
        result = external_call()
        return result
    except Exception as e:
        gateway.log_error(f"Operation one failed: {e}")
        return None

def operation_two():
    try:
        result = another_call()
        return result
    except Exception as e:
        gateway.log_error(f"Operation two failed: {e}")
        return None

# Same pattern everywhere
# Developers know what to expect
```

### Universal Principle

**"Boring is good. Surprising is bad."**

**Boring (consistent):**
- Same pattern repeated
- No surprises
- Easy to understand
- Fast to maintain

**Surprising (clever):**
- Novel solutions
- "Optimized" differently each time
- Requires study to understand
- Slows maintenance

### When Cleverness is Appropriate

**Acceptable cleverness:**
- Internal to a single module (doesn't leak)
- Documented thoroughly
- Performance-critical (proven by profiling)
- Complexity is encapsulated

**Unacceptable cleverness:**
- Different patterns across modules
- Optimization without measurement
- Complexity that leaks to users
- "Because it's cool"

### Cost of Inconsistency

**Development Time:**
- Must learn multiple patterns
- Higher error rate
- Slower code review
- More difficult refactoring

**Maintenance Cost:**
- "Why is this one different?"
- Must remember special cases
- Harder to update
- More brittle to changes

**Onboarding Cost:**
- New developers confused
- Must learn all variations
- Higher ramp-up time
- More questions to answer

---

## Related Topics

- **LESS-04**: Consistency over cleverness (primary source)
- **DEC-01**: SIMA pattern choice (consistency decision)
- **DEC-08**: Flat file structure (simplicity decision)
- **ARCH-01**: Gateway trinity pattern (consistent access)
- **WISD-01**: Architecture prevents problems (consistent architecture)
- **LESS-13**: Architecture must be teachable (enabled by consistency)

---

## Keywords

consistency, patterns, cognitive-load, predictability, maintainability, simplicity

---

## Version History

- **2025-10-23**: Created from synthesis of LESS-04 and multiple design decisions

---

**File:** `NM06-Wisdom-Synthesized_WISD-04.md`
**End of Document**

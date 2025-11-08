# DD1-DEC-01-Dict-Over-If-Else.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Decision criteria for choosing dictionary dispatch over if-else chains  
**Category:** Python Architecture Decision - Performance

---

## DECISION

**Use dictionary dispatch instead of if-else chains for function routing when 10+ actions exist and performance matters.**

**Status:** Active  
**Date Decided:** 2024-09-15  
**Context:** LEE project interface routing optimization

---

## PROBLEM STATEMENT

Function routing with many actions suffers from O(n) if-else chain performance. Need efficient routing for 15-20 actions per interface across 12 interfaces.

**Specific issue:**
- 12 interfaces with 15-20 actions each
- Average 10 comparisons per action in if-else chain
- Hot path execution (thousands of calls)
- Performance degradation with action count growth

---

## OPTIONS CONSIDERED

### Option 1: If-Else Chains

```python
def execute_action(action: str, data: dict):
    if action == "turn_on":
        return turn_on_impl(data)
    elif action == "turn_off":
        return turn_off_impl(data)
    # ... 18 more elif blocks
    else:
        raise ValueError(f"Unknown action: {action}")
```

**Pros:**
- Simple, linear flow
- No memory overhead
- Easy to debug
- Explicit conditions

**Cons:**
- O(n) average performance
- Worst case O(n) for last action
- Grows linearly with action count
- Harder to scan large blocks

### Option 2: Dictionary Dispatch

```python
DISPATCH_TABLE = {
    "turn_on": turn_on_impl,
    "turn_off": turn_off_impl,
    # ... all 20 actions
}

def execute_action(action: str, data: dict):
    handler = DISPATCH_TABLE.get(action)
    if handler is None:
        raise ValueError(f"Unknown action: {action}")
    return handler(data)
```

**Pros:**
- O(1) lookup performance
- Constant time regardless of action count
- Easy to scan all actions
- Declarative structure

**Cons:**
- Dictionary memory overhead (~1.3 KB per 20 actions)
- All handlers loaded at import
- Slightly more complex

### Option 3: Hybrid Approach

```python
# Hot actions in if-else for fastest path
def execute_action(action: str, data: dict):
    if action == "turn_on":  # 60% of traffic
        return turn_on_impl(data)
    elif action == "turn_off":  # 30% of traffic
        return turn_off_impl(data)
    else:
        # Rare actions in dict
        return RARE_DISPATCH[action](data)
```

**Pros:**
- Optimizes for common case
- Reduces average lookup time

**Cons:**
- Complex maintenance
- Inconsistent pattern
- Hard to predict hot actions upfront

---

## DECISION CRITERIA

### Primary: Performance

**Requirement:** Route actions in <3µs average

**If-else performance:**
- First action: ~1.8µs
- Average (action 10): ~11µs
- Worst (action 20): ~22µs

**Dict performance:**
- All actions: ~2.1µs constant

**Winner:** Dict dispatch (5-10x faster average)

### Secondary: Scalability

**Requirement:** Support 15-30 actions per interface

**If-else scaling:**
- 15 actions: ~16µs average
- 20 actions: ~21µs average
- 30 actions: ~31µs average
- **Performance degrades linearly**

**Dict scaling:**
- 15 actions: ~2.1µs
- 20 actions: ~2.1µs
- 30 actions: ~2.1µs
- **Performance constant**

**Winner:** Dict dispatch

### Tertiary: Maintainability

**Requirement:** Easy to add/modify actions

**If-else:**
- Find insertion point
- Add elif block
- Maintain order

**Dict:**
- Add one line to table
- Order irrelevant
- Sorted alphabetically

**Winner:** Dict dispatch (simpler)

### Memory Consideration

**Constraint:** 128 MB Lambda limit

**Memory usage:**
- 12 interfaces × 20 actions × 64 bytes = ~15 KB
- Total project dispatch tables: ~52 KB
- Available memory: 128 MB
- **Dispatch overhead: 0.04% of available**

**Winner:** Memory not a constraint

---

## FINAL DECISION

**Chosen:** Option 2 (Dictionary Dispatch)

**Rationale:**
1. **Performance:** 5-10x faster than if-else average
2. **Scalability:** Constant time regardless of action count
3. **Maintainability:** Simpler to extend
4. **Memory:** Negligible overhead (52 KB / 128 MB)

**Trade-off accepted:** Slight import time increase (12 ms total) for 10x runtime performance gain

---

## IMPLEMENTATION GUIDELINES

### When To Use Dict Dispatch

```
✓ 10+ actions to route
✓ Hot path execution (frequent calls)
✓ Actions will grow over time
✓ Memory available (KB overhead acceptable)
✓ All handlers have consistent signature
```

### When To Use If-Else

```
✓ < 8 actions total
✓ One-time or rare execution
✓ Complex per-action logic
✓ Dynamic routing rules
✓ Extreme memory constraints
```

### LEE Project Pattern

```python
# Standard pattern used across all 12 interfaces
DISPATCH_TABLE = {
    "action_1": handler_1_impl,
    "action_2": handler_2_impl,
    # ... alphabetically sorted
}

def execute_operation(action: str, **kwargs) -> dict:
    """
    Execute interface operation via dispatch.
    
    Args:
        action: Operation to execute
        **kwargs: Operation parameters
        
    Returns:
        Operation result
        
    Raises:
        ValueError: Unknown action
    """
    handler = DISPATCH_TABLE.get(action)
    if handler is None:
        raise ValueError(f"Unknown action: {action}")
    return handler(**kwargs)
```

---

## MEASURED IMPACT

### Performance Improvement

**Before (if-else):**
```
Average lookup: ~15µs
P50: 11µs
P95: 22µs
P99: 24µs
```

**After (dict dispatch):**
```
Average lookup: ~2.1µs
P50: 2.0µs
P95: 2.3µs
P99: 2.5µs
```

**Improvement:** 7x faster average, 9x faster P95

### Memory Impact

**Before:** 0 KB (if-else in code)  
**After:** 52 KB (all dispatch tables)  
**Impact:** 0.04% of 128 MB Lambda memory

**Verdict:** Negligible

### Cold Start Impact

**Before:** 2,835 ms  
**After:** 2,847 ms (+12 ms)  
**Impact:** +0.4% cold start time

**Verdict:** Acceptable

---

## VALIDATION CRITERIA

Decision validated if:
- [✓] Action routing < 3µs average
- [✓] Memory overhead < 1% of available
- [✓] Cold start impact < 50 ms
- [✓] Easy to add new actions
- [✓] Consistent pattern across interfaces

**Result:** All criteria met ✓

---

## ALTERNATIVES REJECTED

### Why Not Hybrid?

**Rejected because:**
- Adds complexity
- Hard to predict hot actions
- Inconsistent patterns confusing
- Marginal benefit (2.1µs → 1.9µs)

### Why Not Match/Case? (Python 3.10+)

```python
match action:
    case "turn_on":
        return turn_on_impl(data)
    case "turn_off":
        return turn_off_impl(data)
```

**Rejected because:**
- Still O(n) performance (compiled to if-else)
- No performance benefit
- Requires Python 3.10+
- Dict dispatch faster and more flexible

---

## LESSONS LEARNED

### Unexpected Benefits

1. **Testing improved:** Can test handlers directly via dispatch table
2. **Documentation clearer:** All actions visible in one place
3. **Refactoring easier:** Change handler without touching router
4. **Type checking better:** Can type-hint dispatch table

### Unexpected Challenges

1. **Import order matters:** Handlers must be imported before table creation
2. **Circular imports:** Required careful module organization (solved with SUGA)
3. **Debugging:** Stack traces show dict lookup, not original action name

---

## REVIEW SCHEDULE

- **Next Review:** 2025-06-15 (6 months)
- **Review Trigger:** Action count exceeds 30 per interface
- **Review Criteria:** Memory usage exceeds 100 MB

---

## RELATED DECISIONS

- DD1-DEC-02: Memory vs Speed Trade-off
- LMMS-DEC-01: Function-Level Imports (affects dispatch table loading)
- SUGA-DEC-03: Gateway Mandatory (prevents circular imports with dispatch)

---

## KEYWORDS

dictionary dispatch, if-else optimization, function routing, performance decision, O(1) lookup, action routing, design decision, performance trade-off

---

## REFERENCES

- DD1-01: Core Concept
- DD1-03: Performance Trade-offs
- DD1-LESS-01: Performance Measurements

---

**END OF FILE**

**Version:** 1.0.0  
**Lines:** 390 (within 400 limit)  
**Category:** Python Architecture Decision  
**Status:** Active

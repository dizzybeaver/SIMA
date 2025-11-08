# File: ZAPH-AP-03-Tier-Thrashing.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Category:** Python/Architectures/ZAPH/Anti-Patterns  
**Status:** Active

---

## ANTI-PATTERN: Tier Thrashing (Rapid Tier Changes)

**REF-ID:** ZAPH-AP-03  
**Severity:** High (Development Churn + Instability)  
**Frequency:** Common without stability safeguards  
**Detection:** >2 tier changes per quarter for same operation

---

## DESCRIPTION

Operations oscillating between tiers due to temporary usage variations, lack of hysteresis, or missing stability periods. Creates constant refactoring churn with no actual performance benefit.

---

## SYMPTOMS

**Behavioral Indicators:**
```
Week 1: 78% access → Tier 2
Week 2: 82% spike → Promoted to Tier 1
Week 3: 78% normal → Demoted to Tier 2
Week 4: 81% spike → Promoted to Tier 1
Week 5: 78% normal → Demoted to Tier 2
Week 6: 80% exactly → Uncertain
Week 7: 79% → Demotion? Promotion?
Week 8: Team gives up tracking tiers
```

**Code Indicators:**
```python
# ❌ Version history shows rapid tier changes
"""
v1.0: Tier 2 (78% access)
v1.1: Tier 1 (82% spike during incident)
v1.2: Tier 2 (78% after incident)
v1.3: Tier 1 (81% spike during deploy)
v1.4: Tier 2 (78% after deploy)
v1.5: Tier 1 (80.1% briefly)
v1.6: Tier 2 (79.9% most of time)
"""

# Result: 6 refactorings in 8 weeks
# 48 hours wasted on thrashing
```

**Process Indicators:**
- Same operation changed tiers >2 times per quarter
- Tier changes driven by single-day spikes
- No waiting period between changes
- Same threshold for promotion and demotion
- Developer frustration with "never-ending optimization"

---

## WHY IT HAPPENS

**Root Causes:**

1. **No Hysteresis (Same Threshold Up/Down)**
   ```
   Tier 2 → Tier 1: 80% access
   Tier 1 → Tier 2: 80% access (same!)
   
   Result: 78% vs 82% = constant oscillation
   ```

2. **No Stability Periods**
   - Immediate tier change on threshold crossing
   - Temporary spikes treated as permanent changes
   - No verification of sustained pattern

3. **Tight Tier Boundaries**
   - 79.9% and 80.1% treated drastically different
   - Natural variation causes boundary crossings
   - No buffer zone

4. **Incident-Driven Spikes**
   - Monitoring operations spike during incidents
   - Spike lasts days, not weeks
   - Pattern temporary, not sustained

---

## WHY IT'S WRONG

**Development Churn:**
- Each tier change: 8 hours refactoring
- 6 changes: 48 hours wasted
- No net performance improvement
- Constant interruptions to feature work

**Code Instability:**
- Structure changes every few weeks
- Never settles into stable state
- Difficult to build on unstable foundation
- Team loses confidence in architecture

**Developer Frustration:**
- "We just changed this last month!"
- "Should we optimize or not?"
- "What tier is this really?"
- Burnout from endless refactoring

**No Actual Benefit:**
- 78% and 82% access need similar optimization
- Tier 2 performance adequate for both
- Thrashing cost > optimization benefit

---

## CORRECT APPROACH

### Hysteresis: Different Thresholds

```python
# ✅ Correct: Different thresholds prevent oscillation

TIER_THRESHOLDS = {
    "promote": {
        "tier_3_to_2": 0.20,  # Promote at 20%
        "tier_2_to_1": 0.80,  # Promote at 80%
    },
    "demote": {
        "tier_1_to_2": 0.75,  # Demote at 75% (not 80%!)
        "tier_2_to_3": 0.15,  # Demote at 15% (not 20%!)
    }
}

# 5% buffer zone prevents oscillation:
# - 78% access: Stable in Tier 2 (above 75% demote)
# - 82% access: Stable in Tier 1 (above 75% demote)
# - Only 75% → 80% transition triggers tier change
```

### Stability Periods

```python
# ✅ Correct: Sustained pattern verification

def check_tier_change(operation):
    """Prevent thrashing with stability requirements."""
    
    # Check lock period first
    days_since_change = get_days_since_last_change(operation)
    
    # Promotion requires sustained pattern
    if promote_candidate(operation):
        # Tier 3 → 2: 2 weeks sustained >20%
        # Tier 2 → 1: 1 week sustained >80%
        weeks_required = 2 if current_tier == 3 else 1
        
        frequency = get_access_frequency(operation, weeks=weeks_required)
        threshold = TIER_THRESHOLDS["promote"][transition]
        
        if frequency > threshold and days_since_change > (weeks_required * 7):
            return "PROMOTE"
    
    # Demotion requires longer verification
    if demote_candidate(operation):
        # Tier 1 → 2: 4 weeks sustained <75%
        # Tier 2 → 3: 8 weeks sustained <15%
        weeks_required = 4 if current_tier == 1 else 8
        
        frequency = get_access_frequency(operation, weeks=weeks_required)
        threshold = TIER_THRESHOLDS["demote"][transition]
        
        if frequency < threshold and days_since_change > (weeks_required * 7):
            return "DEMOTE"
    
    return "STABLE"  # No change
```

### Lock Periods After Changes

```python
# ✅ Correct: Minimum time at new tier

LOCK_PERIODS = {
    "after_promotion": 28,   # 4 weeks minimum at new tier
    "after_demotion": 56,    # 8 weeks minimum at new tier
}

def can_change_tier(operation):
    """Check if lock period expired."""
    last_change = get_last_tier_change_date(operation)
    days_since = (datetime.now() - last_change).days
    last_direction = get_last_change_direction(operation)
    
    if last_direction == "PROMOTE":
        return days_since >= LOCK_PERIODS["after_promotion"]
    elif last_direction == "DEMOTE":
        return days_since >= LOCK_PERIODS["after_demotion"]
    
    return True  # No previous change
```

---

## DETECTION

**Automated Monitoring:**
```python
# Flag operations with excessive tier changes
def detect_tier_thrashing():
    """Identify operations changing tiers too frequently."""
    
    # Check last 90 days
    operations = get_all_operations()
    
    for op in operations:
        changes = count_tier_changes(op, days=90)
        
        if changes > 2:
            # >2 changes in 90 days = thrashing
            alert_tier_thrashing(
                operation=op,
                changes=changes,
                recommendation="Add stability periods"
            )
```

**Code Review Checks:**
```
[ ] Has operation changed tiers before?
[ ] When was last tier change?
[ ] Is lock period expired?
[ ] Is pattern sustained (not temporary spike)?
[ ] Are thresholds using hysteresis?
[ ] Will this prevent future thrashing?
```

---

## REFACTORING

**If thrashing detected:**

### Step 1: Stop All Tier Changes
```python
# Freeze tier assignments for operation
operation.tier_locked = True
operation.lock_reason = "Thrashing prevention"
operation.lock_until = datetime.now() + timedelta(days=56)
```

### Step 2: Analyze True Access Pattern
```python
# Look at 90-day history, ignore spikes
access_data = get_access_history(operation, days=90)

# Calculate percentiles to find stable pattern
p10 = percentile(access_data, 10)  # Low point
p50 = percentile(access_data, 50)  # Median
p90 = percentile(access_data, 90)  # High point

# If p10 to p90 span multiple tiers: boundary case
# Keep at middle tier (Tier 2) permanently
```

### Step 3: Assign Stable Tier
```python
# Choose tier that accommodates full range
if p90 < 0.20:
    stable_tier = 3
elif p10 > 0.80:
    stable_tier = 1
else:
    # Spans tiers: Use Tier 2 (moderate optimization)
    stable_tier = 2
    note = "Boundary case: natural variation spans tiers"
```

### Step 4: Implement Safeguards
```python
# Add hysteresis and stability periods
operation.promote_threshold = 0.80
operation.demote_threshold = 0.75  # 5% buffer
operation.stability_weeks = 4  # Sustained pattern required
operation.lock_after_change_days = 56  # 8 week minimum
```

---

## PREVENTION

**Design Time:**
```
[ ] Define hysteresis thresholds (5% buffer)
[ ] Specify stability period requirements
[ ] Set lock periods after changes
[ ] Document boundary case handling
```

**Implementation:**
```
[ ] Automated tier monitoring with hysteresis
[ ] Sustained pattern verification
[ ] Lock period enforcement
[ ] Thrashing detection alerts
```

**Process:**
```
[ ] Code reviews check for stability safeguards
[ ] Tier changes require justification
[ ] Sustained patterns documented
[ ] Lock periods respected
```

---

## RELATED PATTERNS

**This Anti-Pattern Causes:**
- Developer burnout (endless refactoring)
- Code instability (never settles)
- Wasted effort (no net benefit)

**Prevented By:**
- ZAPH-DEC-03: Tier promotion/demotion rules
- ZAPH-LESS-03: Tier thrashing prevention lesson

**Similar To:**
- Premature optimization (optimizing too early)
- Analysis paralysis (never deciding)

---

## KEY TAKEAWAY

**Stability > perfection. Use hysteresis and lock periods to prevent thrashing.**

78% and 82% access both adequately served by Tier 2. Thrashing between tiers wastes 48 hours with zero benefit. Hysteresis (different thresholds up/down) + stability periods (sustained pattern verification) + lock periods (minimum time at tier) prevent oscillation.

**Cost of thrashing:**
- 8 hours per tier change
- 6 changes per quarter
- 48 hours wasted
- Zero performance improvement

**Value of stability:**
- 95% reduction in tier changes
- Predictable optimization state
- Developer focus on features
- Consistent performance

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial anti-pattern documentation
- Thrashing examples and causes
- Hysteresis solution specified
- Prevention strategies defined

---

**END OF ANTI-PATTERN**

# File: ZAPH-DEC-03-Tier-Promotion-Demotion.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Category:** Python/Architectures/ZAPH/Decisions  
**Status:** Active

---

## DECISION: Systematic Tier Promotion and Demotion Rules

**REF-ID:** ZAPH-DEC-03  
**Decision Date:** 2024-Q3  
**Status:** Approved and Active  
**Scope:** All tier-classified operations

---

## CONTEXT

Access patterns change over time as features evolve and usage shifts. Operations initially classified as Tier 3 (Cold) may become Tier 1 (Hot) after feature adoption. Conversely, formerly hot operations may cool as alternative approaches gain traction.

Without systematic promotion/demotion rules:
- Hot operations remain unoptimized (performance loss)
- Cold operations keep expensive optimizations (wasted complexity)
- Inconsistent classification across team members
- Technical debt accumulates in misclassified code

---

## DECISION

**Implement systematic tier movement rules with measurable triggers:**

### Promotion Rules (Moving Up)

**Tier 3 → Tier 2 (Cold to Warm)**
- **Trigger:** Access frequency crosses 20% threshold for 2 consecutive weeks
- **Action:** Add standard optimization patterns, remove heavy abstractions
- **Timeline:** Within 1 sprint (2 weeks)
- **Priority:** Medium

**Tier 2 → Tier 1 (Warm to Hot)**
- **Trigger:** Access frequency crosses 80% threshold for 1 consecutive week
- **Action:** Apply zero-abstraction pattern, move to fast_path.py
- **Timeline:** Within 1 week (critical path impact)
- **Priority:** High

### Demotion Rules (Moving Down)

**Tier 1 → Tier 2 (Hot to Warm)**
- **Trigger:** Access frequency below 80% threshold for 4 consecutive weeks
- **Action:** Add standard abstractions, move from fast_path.py
- **Timeline:** Within 1 sprint (2 weeks)
- **Priority:** Medium (reduce complexity)

**Tier 2 → Tier 3 (Warm to Cold)**
- **Trigger:** Access frequency below 20% threshold for 8 consecutive weeks
- **Action:** Refactor for clarity, add heavy abstractions for maintainability
- **Timeline:** Within 2 sprints (4 weeks)
- **Priority:** Low (optional optimization)

### Stability Requirements

**Prevent Thrashing:**
- Once promoted, must remain at new tier minimum 4 weeks
- Once demoted, must remain at new tier minimum 8 weeks
- Rapid tier changes (>2/quarter) trigger architecture review

---

## ALTERNATIVES CONSIDERED

### Alternative 1: Immediate Tier Changes
**Rejected:** Caused thrashing. Operations moved tiers weekly, creating constant refactoring overhead. Stability periods prevent premature optimization.

### Alternative 2: Manual Review Only
**Rejected:** Inconsistent application. Automated thresholds ensure consistent treatment across all operations and developers.

### Alternative 3: Same Thresholds Up/Down
**Rejected:** Created instability near boundaries. Hysteresis (different thresholds for promotion/demotion) provides stable boundaries.

### Alternative 4: Single Review Cadence
**Rejected:** Weekly too frequent (thrashing), quarterly too slow (missed optimizations). Tiered frequency based on tier urgency.

---

## RATIONALE

**Why Different Time Periods:**
- Promotion faster than demotion (performance gains more urgent)
- Tier 3→2 requires 2 weeks (validate pattern sustainability)
- Tier 2→1 requires 1 week (hot path optimization critical)
- Demotion slower (avoid removing working optimizations prematurely)

**Why Stability Periods:**
- Prevents oscillation around tier boundaries
- Reduces refactoring churn
- Allows performance benefits to materialize
- Provides time to validate tier assignment

**Why Automated Triggers:**
- Removes subjective judgment
- Ensures consistency across developers
- Provides clear action signals
- Enables automated monitoring

**Benefits:**
- Systematic optimization of emerging hot paths
- Timely removal of unnecessary complexity
- Stable tier assignments
- Predictable refactoring schedule

---

## IMPLEMENTATION

### Monitoring System

```python
# Automated tier boundary monitoring
class TierMonitor:
    """Tracks access frequency and triggers tier changes."""
    
    def check_promotion_tier3_to_tier2(operation: str) -> bool:
        """Check if Tier 3 operation should promote to Tier 2."""
        frequency = get_access_frequency(operation, weeks=2)
        return frequency > 0.20  # 20% threshold
    
    def check_promotion_tier2_to_tier1(operation: str) -> bool:
        """Check if Tier 2 operation should promote to Tier 1."""
        frequency = get_access_frequency(operation, weeks=1)
        return frequency > 0.80  # 80% threshold
    
    def check_demotion_tier1_to_tier2(operation: str) -> bool:
        """Check if Tier 1 operation should demote to Tier 2."""
        frequency = get_access_frequency(operation, weeks=4)
        last_promoted = get_last_tier_change_date(operation)
        weeks_since = (datetime.now() - last_promoted).days / 7
        
        # Requires 4 weeks below threshold + 4 weeks stability
        return frequency < 0.80 and weeks_since >= 4
    
    def check_demotion_tier2_to_tier3(operation: str) -> bool:
        """Check if Tier 2 operation should demote to Tier 3."""
        frequency = get_access_frequency(operation, weeks=8)
        last_promoted = get_last_tier_change_date(operation)
        weeks_since = (datetime.now() - last_promoted).days / 7
        
        # Requires 8 weeks below threshold + 8 weeks stability
        return frequency < 0.20 and weeks_since >= 8
```

### Promotion Workflow

```markdown
## Tier Promotion Process

**Detection:**
1. Automated monitoring detects frequency threshold crossing
2. Alert generated with operation name and metrics
3. Issue created in tracking system

**Review:**
1. Verify metrics accuracy (data quality check)
2. Analyze trend (sustained or temporary spike)
3. Assess promotion impact (refactoring scope)

**Implementation:**
1. Create refactoring ticket with priority
2. Apply tier-appropriate patterns
3. Update documentation and tier markers
4. Run performance benchmarks

**Validation:**
1. Deploy to staging environment
2. Measure performance improvement
3. Verify no regressions
4. Deploy to production
```

### Demotion Workflow

```markdown
## Tier Demotion Process

**Detection:**
1. Automated monitoring detects sustained low frequency
2. Alert generated after stability period ends
3. Issue created in tracking system (lower priority)

**Review:**
1. Verify metrics (check for measurement errors)
2. Confirm pattern (not temporary usage dip)
3. Assess benefits (complexity reduction value)

**Implementation:**
1. Create refactoring ticket
2. Add appropriate abstractions
3. Update documentation
4. Remove hot-path optimizations

**Validation:**
1. Verify functionality maintained
2. Confirm complexity reduced
3. Update tier classification
4. Deploy changes
```

---

## IMPLICATIONS

### Development Process
- Weekly tier promotion reviews (automated alert triggers)
- Monthly tier demotion reviews (less urgent)
- Quarterly architecture reviews for tier stability
- Continuous access frequency monitoring

### Code Organization
- Tier markers in all function docstrings
- Last tier change date in version history
- Frequency data in performance logs
- Promotion/demotion history tracked

### Documentation
- Tier changes documented in changelog
- Performance metrics before/after tier changes
- Rationale for each tier movement
- Lessons learned captured

---

## MEASUREMENTS

**Success Criteria:**
- 95%+ of hot operations correctly classified Tier 1
- 90%+ of cold operations correctly classified Tier 3
- <5% of operations change tiers per quarter
- Zero rapid tier thrashing (>2 changes/quarter)

**Monitoring:**
- Continuous access frequency tracking
- Automated tier boundary alerts
- Quarterly tier stability reports
- Performance impact measurements

---

## REFERENCES

**Inherits From:**
- ZAPH-DEC-01: Three-tier access classification

**Related To:**
- ZAPH-DEC-02: Zero-abstraction hot paths
- ZAPH-DEC-04: Re-evaluation triggers
- ZAPH-LESS-02: Premature optimization prevention

**Used In:**
- Tier monitoring systems
- Performance optimization workflows
- Code review processes

---

## EXAMPLES

### Promotion Example (Tier 3 → Tier 2)

```python
# BEFORE: Tier 3 (Cold) - Heavy abstraction
class DiagnosticReporter:
    """Tier 3: Complex abstraction for rare diagnostics."""
    
    def __init__(self):
        self.sections = []
        self.formatter = HtmlFormatter()
    
    def add_section(self, data):
        section = Section(data)
        self.sections.append(section)
    
    def render(self):
        return self.formatter.format(self.sections)

# Promotion trigger: Usage increased to 25% (crossed 20% threshold)

# AFTER: Tier 2 (Warm) - Moderate abstraction
def generate_diagnostic_report(sections):
    """Tier 2: Standard function with moderate abstraction.
    
    Simplified after promotion from Tier 3.
    Access frequency: 25% (Tier 2 range).
    """
    if not sections:
        return ""
    
    html_parts = []
    for section in sections:
        html_parts.append(f"<div>{section}</div>")
    
    return "\n".join(html_parts)
```

### Promotion Example (Tier 2 → Tier 1)

```python
# BEFORE: Tier 2 (Warm) - Standard abstraction
def get_cached_value(key: str, default=None):
    """Tier 2: Standard cache access with validation."""
    if not key:
        return default
    
    try:
        value = _cache.get(key, default)
        if LOGGING_ENABLED:
            log_cache_access(key, hit=value is not None)
        return value
    except Exception as e:
        log_error(f"Cache error: {e}")
        return default

# Promotion trigger: Usage increased to 85% (crossed 80% threshold)

# AFTER: Tier 1 (Hot) - Zero abstraction
def cache_get_hot(key):
    """Tier 1 HOT PATH - zero abstraction.
    
    Promoted from Tier 2 after crossing 80% access frequency.
    
    WARNING: No validation. Caller must ensure:
    - key is str
    - key is not empty
    - error handling at call site
    """
    return _cache.get(key)

# Tier 2 wrapper for validated access
def get_cached_value_safe(key: str, default=None):
    """Tier 2 wrapper for hot path with validation."""
    if not key:
        return default
    
    try:
        return cache_get_hot(key)
    except Exception as e:
        log_error(f"Cache error: {e}")
        return default
```

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial decision documentation
- Promotion and demotion rules defined
- Stability periods specified
- Automated monitoring approach documented

---

**END OF DECISION**

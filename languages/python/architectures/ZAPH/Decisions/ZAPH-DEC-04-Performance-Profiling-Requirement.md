# File: ZAPH-DEC-04-Performance-Profiling-Requirement.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Category:** Python/Architectures/ZAPH/Decisions  
**Status:** Active

---

## DECISION: Mandatory Performance Profiling Before Tier Assignment

**REF-ID:** ZAPH-DEC-04  
**Decision Date:** 2024-Q3  
**Status:** Approved and Active  
**Scope:** All new operations and tier reassignments

---

## CONTEXT

Tier assignments drive optimization decisions with significant development cost. Wrong tier assignment leads to:
- **Over-optimization:** Wasted effort on cold operations (Tier 3 treated as Tier 1)
- **Under-optimization:** Performance issues from hot operations (Tier 1 treated as Tier 3)
- **Inconsistent classification:** Developers guess tiers based on intuition, not data

Early implementation attempts relied on developer estimates, resulting in 40% misclassification rate. Hot paths remained slow while rarely-used code received expensive optimizations.

---

## DECISION

**All tier assignments must be based on profiling data from production-like load:**

### Required Before Initial Assignment

**Minimum Profiling Requirements:**
- 1,000+ requests minimum sample size
- Production-like traffic patterns (not synthetic load)
- Full request lifecycle measurement
- Access frequency calculation across all operations
- Statistical confidence level >95%

### Required Before Tier Changes

**Re-profiling Triggers:**
- Any tier promotion or demotion
- Quarterly tier validation reviews
- After significant feature changes
- When performance regression detected
- Access pattern anomalies observed

### Profiling Standards

**Acceptable Data Sources (in priority order):**
1. **Production metrics** (preferred - real usage patterns)
2. **Staging with production replay** (realistic load)
3. **Load testing with representative scenarios** (acceptable minimum)
4. **Synthetic benchmarks** (not acceptable for tier assignment)

---

## ALTERNATIVES CONSIDERED

### Alternative 1: Developer Estimates
**Rejected:** 40% misclassification rate in pilot. Developers consistently overestimated cold path importance and underestimated hot path criticality.

### Alternative 2: Static Code Analysis
**Rejected:** Cannot predict runtime access patterns. Call frequency depends on user behavior, not code structure.

### Alternative 3: Small Sample Sizes (<100 requests)
**Rejected:** Insufficient statistical confidence. Access patterns varied significantly with larger samples, invalidating small-sample classifications.

### Alternative 4: Post-deployment Profiling Only
**Rejected:** Created deployment churn. Operations required immediate re-optimization after discovering misclassification.

---

## RATIONALE

**Why Profiling Mandatory:**
- Data-driven decisions eliminate guesswork
- Prevents optimization of wrong code paths
- Validates tier assignments objectively
- Enables ROI calculation for optimizations

**Why 1,000+ Requests:**
- Statistical significance requires large sample
- Rare operations need large sample to show patterns
- Edge cases appear in larger samples
- Confidence level >95% achieved at 1,000+ requests

**Why Production-like Load:**
- Synthetic load misses real usage patterns
- User behavior differs from developer assumptions
- Production patterns reveal actual hot paths
- Representative load critical for accurate classification

**Benefits:**
- 95%+ tier classification accuracy (vs. 60% with estimates)
- Optimization efforts focused on actual hot paths
- Measurable performance improvements
- Reduced wasted development time

---

## IMPLEMENTATION

### Profiling Infrastructure

```python
# Automated access frequency profiling
class AccessProfiler:
    """Tracks operation access frequency for tier assignment."""
    
    def __init__(self):
        self.operation_counts = {}
        self.total_requests = 0
    
    def record_access(self, operation: str):
        """Record single operation access."""
        self.operation_counts[operation] = \
            self.operation_counts.get(operation, 0) + 1
        self.total_requests += 1
    
    def get_access_frequency(self, operation: str) -> float:
        """Calculate access frequency as percentage of requests."""
        if self.total_requests == 0:
            return 0.0
        
        count = self.operation_counts.get(operation, 0)
        return (count / self.total_requests) * 100
    
    def recommend_tier(self, operation: str) -> str:
        """Recommend tier based on profiling data."""
        if self.total_requests < 1000:
            raise ValueError(
                f"Insufficient data: {self.total_requests} < 1000 requests"
            )
        
        frequency = self.get_access_frequency(operation)
        
        if frequency > 80:
            return "Tier 1 (Hot)"
        elif frequency > 20:
            return "Tier 2 (Warm)"
        else:
            return "Tier 3 (Cold)"
    
    def generate_report(self) -> dict:
        """Generate profiling report for all operations."""
        report = {
            "total_requests": self.total_requests,
            "operations": {}
        }
        
        for operation, count in self.operation_counts.items():
            frequency = (count / self.total_requests) * 100
            report["operations"][operation] = {
                "access_count": count,
                "access_frequency_pct": frequency,
                "recommended_tier": self.recommend_tier(operation)
            }
        
        return report
```

### Profiling Workflow

```markdown
## New Operation Profiling Process

**Phase 1: Implement with Instrumentation**
1. Add operation to codebase
2. Add profiling instrumentation
3. Deploy to staging environment
4. Mark as "Tier Unassigned - Profiling"

**Phase 2: Collect Data**
1. Run production replay or load tests
2. Collect 1,000+ request sample
3. Monitor for statistical confidence
4. Validate data quality

**Phase 3: Analyze Results**
1. Calculate access frequency
2. Apply tier assignment rules
3. Compare against similar operations
4. Validate classification makes sense

**Phase 4: Assign Tier**
1. Document profiling data source
2. Assign tier based on frequency
3. Apply tier-appropriate patterns
4. Update documentation
5. Deploy optimization

**Phase 5: Validate**
1. Measure performance post-optimization
2. Confirm tier assignment correct
3. Monitor for pattern changes
4. Schedule quarterly review
```

---

## MEASUREMENTS

**Success Criteria:**
- 100% of tier assignments backed by profiling data
- <5% tier reassignments required post-deployment
- 95%+ statistical confidence in all classifications
- Profiling data <90 days old for all tiers

**Monitoring:**
- Track sample sizes for all classifications
- Monitor data freshness (profiling age)
- Track tier reassignment frequency
- Measure classification accuracy

**Quality Gates:**
- New operations blocked without profiling data
- Tier changes require re-profiling approval
- Quarterly audit of profiling data freshness
- Statistical confidence validation required

---

## IMPLICATIONS

### Development Process
- Profiling required before feature complete
- Staging environment must support production replay
- Load testing infrastructure required
- Profiling data review in code reviews

### Deployment Process
- Operations cannot reach production without tier assignment
- Tier assignment cannot occur without profiling data
- Profiling data included in deployment documentation
- Post-deployment validation required

### Documentation Requirements
- Every operation documents profiling data source
- Sample size and confidence level recorded
- Profiling date tracked for freshness
- Re-profiling schedule documented

---

## REFERENCES

**Inherits From:**
- ZAPH-DEC-01: Three-tier access classification

**Related To:**
- ZAPH-DEC-02: Zero-abstraction hot paths
- ZAPH-DEC-03: Tier promotion/demotion
- ZAPH-LESS-01: Measure don't guess

**Used In:**
- All tier assignment processes
- Performance optimization workflows
- Code review checklists
- Deployment validation

**Influenced By:**
- Statistical analysis best practices
- Production performance monitoring
- Lessons from misclassified operations

---

## EXAMPLES

### Profiling Report Example

```python
# Generate profiling report
profiler = AccessProfiler()

# After 1,000+ requests collected...
report = profiler.generate_report()

# Example output:
{
    "total_requests": 1547,
    "operations": {
        "cache_get": {
            "access_count": 1421,
            "access_frequency_pct": 91.85,
            "recommended_tier": "Tier 1 (Hot)"
        },
        "param_get": {
            "access_count": 703,
            "access_frequency_pct": 45.44,
            "recommended_tier": "Tier 2 (Warm)"
        },
        "generate_diagnostic": {
            "access_count": 31,
            "access_frequency_pct": 2.00,
            "recommended_tier": "Tier 3 (Cold)"
        }
    }
}
```

### Tier Assignment Documentation

```python
def cache_get_hot(key):
    """Tier 1 hot path - zero abstraction.
    
    TIER ASSIGNMENT DATA:
    - Profiling Date: 2024-08-15
    - Sample Size: 1,547 requests
    - Access Frequency: 91.85%
    - Data Source: Production metrics (30-day window)
    - Confidence Level: 99%
    - Next Review: 2024-11-15 (quarterly)
    
    PERFORMANCE CHARACTERISTICS:
    - Average execution: 340ns
    - P99 execution: 520ns
    - Target: <500ns
    
    WARNING: No validation. Caller responsible for:
    - key is str
    - key not empty
    - error handling
    """
    return _cache.get(key)
```

---

## ANTI-PATTERNS

**❌ Wrong: Guessing Tier Based on Intuition**
```python
def my_new_operation():
    """Anti-pattern: No profiling data, guessed Tier 1."""
    # ❌ Assigned Tier 1 because "seems important"
    # ❌ Actually accessed <5% (should be Tier 3)
    # ❌ Wasted optimization effort
    return expensive_hot_path_optimization()
```

**✅ Right: Profile First, Then Assign**
```python
def my_new_operation():
    """Correct: Tier assigned after profiling.
    
    PROFILING DATA:
    - Sample: 1,200 requests
    - Frequency: 3.4%
    - Assigned: Tier 3 (Cold)
    - Source: Load testing with production replay
    """
    # Tier 3 appropriate abstraction
    return maintainable_implementation()
```

**❌ Wrong: Small Sample Size**
```python
# Anti-pattern: Only 50 requests profiled
{
    "total_requests": 50,  # ❌ < 1,000 minimum
    "operation": {
        "access_frequency_pct": 80.0,  # Unreliable
        "recommended_tier": "Tier 1"  # Premature
    }
}
```

**✅ Right: Adequate Sample Size**
```python
# Correct: 1,000+ requests profiled
{
    "total_requests": 1547,  # ✅ > 1,000 minimum
    "operation": {
        "access_frequency_pct": 45.4,  # Reliable
        "recommended_tier": "Tier 2"  # Confident
    }
}
```

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial decision documentation
- Profiling requirements specified
- Sample size thresholds defined
- Profiling workflow documented

---

**END OF DECISION**

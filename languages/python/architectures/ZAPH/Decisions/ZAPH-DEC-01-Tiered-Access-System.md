# File: ZAPH-DEC-01-Tiered-Access-System.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Category:** Python/Architectures/ZAPH/Decisions  
**Status:** Active

---

## DECISION: Implement Three-Tier Access Classification

**REF-ID:** ZAPH-DEC-01  
**Decision Date:** 2024-Q3  
**Status:** Approved and Active  
**Scope:** All hot-path optimization implementations

---

## CONTEXT

Performance-critical paths require different optimization strategies than rarely-used code. Without classification system, developers either:
- Over-optimize everything (wasted effort)
- Under-optimize hot paths (performance loss)
- Apply inconsistent strategies (unpredictable results)

Traditional profiling shows execution time but not access frequency patterns.

---

## DECISION

Implement three-tier access classification for all code paths:

**Tier 1 (Hot): >80% Access Frequency**
- Critical operations accessed almost every request
- Zero abstraction tolerance
- Direct implementation required
- Micro-optimizations justified

**Tier 2 (Warm): 20-80% Access Frequency**
- Regular operations with moderate usage
- Light abstraction acceptable
- Standard optimization patterns
- Balance readability and performance

**Tier 3 (Cold): <20% Access Frequency**
- Rarely accessed operations
- Heavy abstraction encouraged
- Prioritize maintainability
- Performance secondary consideration

**Classification Method:**
1. Profile access frequency over 1000+ requests
2. Calculate percentage of requests accessing each path
3. Assign tier based on thresholds
4. Re-evaluate quarterly or after major changes

---

## ALTERNATIVES CONSIDERED

### Alternative 1: Binary Classification (Hot/Cold)
**Rejected:** Too coarse-grained. 50% of code fell into "medium" category with unclear optimization strategy.

### Alternative 2: Five-Tier System
**Rejected:** Over-complicated. Three tiers provide sufficient granularity without excessive classification overhead.

### Alternative 3: Dynamic Thresholds
**Rejected:** Inconsistent classification across projects. Fixed thresholds enable cross-project comparison and shared patterns.

### Alternative 4: Execution Time Based
**Rejected:** Long-running cold operations would be over-prioritized. Access frequency better predicts overall system impact.

---

## RATIONALE

**Why Three Tiers:**
- Tier 1 (Hot): Justifies aggressive optimization effort
- Tier 2 (Warm): Standard practices sufficient  
- Tier 3 (Cold): Focus on clarity over performance

**Why These Thresholds:**
- 80% threshold: Captures truly critical paths (≤10-15 operations typically)
- 20% threshold: Separates regular from rare operations
- Middle tier: Accommodates most code without special treatment

**Why Access Frequency:**
- Cumulative impact matters more than individual execution time
- 100ms operation accessed 95% of requests > 500ms operation accessed 2% of requests
- Enables targeted optimization of highest-impact paths

**Benefits:**
- Clear optimization priorities
- Consistent approach across developers
- Measurable performance improvements
- Efficient resource allocation

---

## IMPLICATIONS

### Development Process
- All new features must include tier classification
- Performance reviews focus on Tier 1 operations first
- Refactoring priorities guided by tier boundaries

### Code Organization
- Tier 1 code in dedicated hot-path files
- Tier 2 code in standard organization
- Tier 3 code grouped by feature (not performance)

### Testing Strategy
- Tier 1: Heavy performance testing, micro-benchmarks
- Tier 2: Standard functional testing
- Tier 3: Comprehensive edge case testing

### Documentation
- Each operation documented with tier assignment
- Tier changes tracked in version history
- Quarterly tier review required

---

## MEASUREMENTS

**Success Criteria:**
- 85%+ of critical paths in Tier 1
- 10-15 operations typically in Tier 1 (manageable scope)
- Tier classifications stable quarter-over-quarter
- Performance improvements measurable in Tier 1

**Monitoring:**
- Track access frequency distributions
- Monitor tier boundary movements
- Measure optimization ROI by tier
- Review quarterly for threshold adjustments

---

## REFERENCES

**Inherits From:**
- None (foundational decision)

**Related To:**
- ZAPH-DEC-02: Hot path optimization rules
- ZAPH-DEC-03: Tier boundary management
- ZAPH-DEC-04: Re-evaluation triggers

**Used In:**
- All ZAPH-pattern implementations
- Performance optimization workflows
- Code review checklists

**Influenced By:**
- LMMS lazy loading patterns (tier-based loading)
- Profiling data from production systems
- 80/20 rule (Pareto principle)

---

## EXAMPLES

### Tier 1 (Hot) Classification
```python
# Cache retrieval - accessed 95% of requests
def cache_get_hot(key: str):
    """Tier 1: Direct dict access, no abstraction."""
    return _cache.get(key)  # No validation, no logging, pure speed
```

### Tier 2 (Warm) Classification
```python
# Parameter retrieval - accessed 45% of requests
def param_get_warm(key: str, default=None):
    """Tier 2: Standard implementation with reasonable abstraction."""
    if not key:
        return default
    value = _params.get(key, default)
    log_access(key) if LOGGING_ENABLED else None
    return value
```

### Tier 3 (Cold) Classification
```python
# Diagnostic report - accessed 2% of requests
def generate_diagnostic_report_cold():
    """Tier 3: Heavy abstraction, prioritize clarity."""
    report = DiagnosticReport()
    report.add_section(system_info())
    report.add_section(cache_stats())
    report.add_section(performance_metrics())
    report.format_html()
    return report.render()
```

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial decision documentation
- Three-tier system specification
- Thresholds and criteria defined
- Classification methodology documented

---

**END OF DECISION**

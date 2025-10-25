# NM04-Decisions-Technical_DEC-12.md - DEC-12

# DEC-12: Multi-Tier Configuration

**Category:** Decisions
**Topic:** Technical
**Priority:** 🟢 Medium
**Status:** Active
**Date Decided:** 2024-06-05
**Created:** 2025-10-23
**Last Updated:** 2025-10-23

---

## Summary

Four configuration tiers (minimum, standard, maximum, user) provide flexible deployment options with sensible defaults for different environments, enabling right-sized resource allocation without requiring configuration for most use cases.

---

## Context

Lambda deployment environments vary significantly: development (fast iteration, low resource), production (balanced, reliable), high-traffic (optimized throughput). A single configuration approach wouldn't serve all needs well. Need flexible system with good defaults.

---

## Content

### The Decision

**What We Chose:**
Four configuration tiers with clear use cases:
- **minimum**: Development/testing (2MB cache, 60s TTL, low timeouts)
- **standard**: Production default (8MB cache, 300s TTL, balanced)
- **maximum**: High-traffic optimization (24MB cache, 600s TTL, aggressive)
- **user**: Custom overrides (environment variable driven)

**Implementation:**
```python
CONFIG_TIERS = {
    "minimum": {
        "cache_size_mb": 2,
        "cache_ttl_seconds": 60,
        "http_timeout": 10,
        "max_retries": 1,
    },
    "standard": {
        "cache_size_mb": 8,
        "cache_ttl_seconds": 300,
        "http_timeout": 30,
        "max_retries": 3,
    },
    "maximum": {
        "cache_size_mb": 24,
        "cache_ttl_seconds": 600,
        "http_timeout": 60,
        "max_retries": 5,
    },
    "user": {
        # Load from environment variables
        # Overrides tier defaults
    }
}

# Usage
tier = os.environ.get('CONFIG_TIER', 'standard')
config = load_config_tier(tier)
```

### Rationale

**Why We Chose This:**

1. **Deployment Flexibility**
   - Different environments need different configs
   - Development: fast, low resource (minimize cost)
   - Production: balanced (proven reliable)
   - High-traffic: optimized (handle load)
   - **Result:** Same code, different optimization profiles

2. **Sensible Defaults**
   - Most users use standard tier (no config needed)
   - Works out of box
   - Can tune when needed
   - Progressive enhancement approach
   - **Result:** Zero-config for 90% of deployments

3. **Resource Optimization**
   - Right-size resources for use case
   - Don't waste memory in development
   - Don't under-provision production
   - Clear upgrade path (minimum → standard → maximum)
   - **Result:** Efficient resource utilization

4. **Clear Intent**
   - Tier names describe purpose (not arbitrary)
   - Easy to explain: "use standard for production"
   - Documentation straightforward
   - **Result:** Reduced support questions

### Alternatives Considered

**Alternative 1: Single Configuration**
- **Description:** One config for all environments
- **Pros:**
  - Simplest possible
  - No tier selection needed
  - Less code
- **Cons:**
  - Either over-provisions dev or under-provisions production
  - No flexibility
  - Same cache size everywhere (wasteful)
- **Why Rejected:** One-size-fits-all doesn't fit any well

**Alternative 2: Infinite Granularity (Every Param Configurable)**
- **Description:** Environment variable for every setting
- **Pros:**
  - Maximum flexibility
  - No tiers needed
- **Cons:**
  - Configuration explosion (50+ variables)
  - No sensible defaults (must configure everything)
  - Easy to misconfigure
  - No documented best practices
- **Why Rejected:** Too much complexity, no guidance

**Alternative 3: Auto-Detection Based on Lambda Memory**
- **Description:** Detect Lambda memory setting, scale config automatically
- **Pros:**
  - Zero configuration
  - Automatic optimization
- **Cons:**
  - Opaque (how did it choose values?)
  - Can't override (what if detection wrong?)
  - Lambda memory might not correlate with needs
- **Why Rejected:** Too magical, hard to control

### Trade-offs

**Accepted:**
- Four configurations to maintain (vs one)
- Need to document when to use which tier
- User might pick wrong tier (mitigated by good defaults)

**Benefits:**
- Development 4x faster cold start (2MB vs 8MB cache)
- Production balanced (proven reliable)
- High-traffic can handle 3x load (24MB cache)
- 90% of users use default (zero config)

**Net Assessment:**
The small maintenance burden (4 configs) is worth the flexibility and optimization. The standard tier works for almost all deployments, and having minimum/maximum provides clear upgrade/downgrade paths.

### Impact

**On Architecture:**
- Config loading system supports tiered approach
- Clear configuration boundaries
- Environment-specific optimizations possible

**On Development:**
- Developers use minimum tier (fast iteration)
- CI/CD uses standard tier (realistic testing)
- No configuration needed for most work

**On Performance:**
- Development: 2MB cache = faster startup
- Production: 8MB cache = good hit rate
- High-traffic: 24MB cache = optimal throughput

**On Maintenance:**
- Clear documentation: "Use standard for production"
- Support questions reduced (default works well)
- Easy to diagnose: "What tier are you using?"

### Future Considerations

**When to Revisit:**
- If need more than 4 tiers (hasn't happened)
- If user tier insufficient (custom needs)
- If tier differences cause confusion

**Potential Evolution:**
- Auto-recommend tier based on traffic patterns
- Validation: warn if tier seems wrong for usage
- Dynamic tier switching based on load

**Monitoring Needs:**
- Track which tiers are used
- Correlate tier with performance
- Alert on misconfiguration (minimum in production)

---

## Related Topics

- **INT-05**: CONFIG interface (implements tier loading)
- **DEC-20**: LAMBDA_MODE (similar tier pattern)
- **LESS-17**: Configuration lessons (informed this decision)

---

## Keywords

configuration tiers, deployment modes, resource optimization, sensible defaults, flexibility, environment-specific, cache sizing

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2024-06-05**: Original decision documented in NM04-TECHNICAL-Decisions.md

---

**File:** `NM04-Decisions-Technical_DEC-12.md`
**End of Document**

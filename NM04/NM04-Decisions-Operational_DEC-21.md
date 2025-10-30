# NM04-Decisions-Operational_DEC-21.md - DEC-21

# DEC-21: SSM Token-Only Configuration

**Category:** Decisions
**Topic:** Operational
**Priority:** Critical
**Status:** Active
**Date Decided:** 2025-10-20
**Created:** 2025-10-23
**Last Updated:** 2025-10-23

---

## Summary

SSM Parameter Store stores ONLY Home Assistant token. All other configuration moved to Lambda environment variables, resulting in 92% reduction in cold start overhead (3,000ms savings).

---

## Context

The Lambda Execution Engine initially stored 13 configuration parameters in SSM Parameter Store (all Home Assistant settings, timeouts, URLs, log levels, etc.). SSM retrieval requires API call averaging 250ms per parameter.

Cold start sequence: Import modules → Initialize → Fetch SSM parameters → Ready

With 13 parameters: 13 × 250ms = 3,250ms just for configuration retrieval. This massive overhead dominated cold start time, making the Lambda unresponsive for over 3 seconds on cold starts.

Analysis revealed only ONE parameter truly needs encryption: the Home Assistant long-lived access token. All other config (URLs, timeouts, log levels) is non-sensitive and could live in environment variables.

---

## Content

### The Decision

**What We Chose:**
Store ONLY the Home Assistant token in SSM Parameter Store (as SecureString). Move all other configuration to Lambda environment variables.

**Before:**
- 13 SSM parameters (token, URL, timeout, log level, etc.)
- 3,250ms cold start overhead
- Configuration split across SSM and environment

**After:**
- 1 SSM parameter (token only)
- 250ms cold start overhead (first call) or 0ms (cached)
- All other config in environment variables

**Configuration:**
```bash
# SSM Parameter Store (encrypted)
/lambda-execution-engine/home_assistant/token = "eyJhbGc..."

# Environment Variables (plain text - not sensitive)
HOME_ASSISTANT_URL=https://homeassistant.local:8123
HOME_ASSISTANT_TIMEOUT=30
LOG_LEVEL=CRITICAL
USE_PARAMETER_STORE=true
PARAMETER_PREFIX=/lambda-execution-engine
```

### Rationale

**Why We Chose This:**

1. **Massive Performance Improvement (92% reduction):**
   - **Cold start overhead:** 3,250ms → 250ms (92% improvement)
   - **Warm container:** 3,250ms → 0ms (token cached, 5min TTL)
   - **User experience:** From "sluggish" to "responsive"
   - **Critical for Lambda:** Cold start time directly impacts UX

2. **Right-Sized Security:**
   - Token needs encryption (credentials) → SSM SecureString ✅
   - URL doesn't need encryption (not sensitive) → Environment variable ✅
   - Timeout doesn't need encryption (just a number) → Environment variable ✅
   - Apply encryption only where needed, not everywhere

3. **Operational Simplicity:**
   - Single SSM parameter to manage (token)
   - Other config in one place (Lambda environment variables)
   - No multiple-location config search
   - Easier deployment (fewer SSM parameters to create)

4. **Cost Optimization:**
   - SSM API calls: 13/request → 1/request
   - Data transfer: 13× reduction
   - API costs: Negligible but measurable savings
   - Better AWS resource utilization

### Alternatives Considered

**Alternative 1: All Config in SSM (Original)**
- **Description:** Store all 13 parameters in SSM
- **Pros:**
  - Centralized configuration
  - All config encrypted
  - Version history for all config
- **Cons:**
  - 3,250ms cold start overhead
  - Massive performance penalty
  - Over-application of encryption
  - API call explosion
- **Why Rejected:** 92% of overhead is unnecessary

**Alternative 2: All Config in Environment Variables**
- **Description:** Move everything including token to environment variables
- **Pros:**
  - Zero SSM overhead
  - Fastest possible
  - Simplest configuration
- **Cons:**
  - **Token visible in Lambda console** (security issue)
  - Can't rotate token without redeployment
  - Token in CloudFormation/Terraform (bad practice)
  - Violates security best practices
- **Why Rejected:** Unacceptable security trade-off

**Alternative 3: Secrets Manager**
- **Description:** Use AWS Secrets Manager instead of SSM
- **Pros:**
  - Purpose-built for secrets
  - Automatic rotation support
  - Better secret management features
- **Cons:**
  - **Higher cost** ($0.40/secret/month vs SSM free tier)
  - Similar latency to SSM (~250ms)
  - More complex setup
  - Overkill for single token
- **Why Rejected:** SSM adequate, Secrets Manager adds cost/complexity

**Alternative 4: Token in Lambda Layer**
- **Description:** Package token in Lambda layer
- **Pros:**
  - No API calls (instant access)
  - Fastest possible
- **Cons:**
  - Token baked into layer (can't rotate without redeploy)
  - Token in deployment artifact (security issue)
  - Layer immutability prevents rotation
  - Terrible security practice
- **Why Rejected:** Security nightmare

### Trade-offs

**Accepted:**
- **First call pays 250ms:** Initial token retrieval takes 250ms
  - But subsequent calls cached (0ms)
  - Cache TTL 5 minutes (configurable)
  - Acceptable one-time cost per container

- **Non-sensitive config visible in console:** URLs, timeouts visible
  - But they're not sensitive (not credentials)
  - Same as any Lambda environment variable
  - Standard practice

**Benefits:**
- **3,000ms cold start savings:** 92% reduction in SSM overhead
- **Warm container:** Token cached, 0ms overhead after first call
- **Right-sized security:** Encryption applied appropriately
- **Simpler operations:** Fewer SSM parameters to manage

**Net Assessment:**
This is one of the highest-impact decisions in the project. 3,000ms cold start improvement transformed Lambda from "problematically slow" to "acceptably fast." The security trade-off is proper (only encrypt what needs encryption), and the operational simplicity is a bonus.

### Impact

**On Architecture:**
- Establishes pattern: SSM for secrets, environment for config
- Demonstrates right-sizing security
- Sets example for other Lambda projects

**On Development:**
- Faster development cycle (faster cold starts in dev/test)
- Easier configuration management
- Simpler deployment scripts

**On Performance:**
- **Cold start:** 3,250ms → 250ms (92% improvement)
- **Warm execution:** 3,250ms → 0ms (token cached)
- **User experience:** Dramatically improved responsiveness
- **Throughput:** Higher (less time blocked on SSM)

**On Maintenance:**
- **Migration Required:** One-time migration
  ```bash
  # Keep only token in SSM
  /lambda-execution-engine/home_assistant/token
  
  # Move to environment variables
  HOME_ASSISTANT_URL=...
  HOME_ASSISTANT_TIMEOUT=30
  LOG_LEVEL=CRITICAL
  
  # Delete old SSM parameters
  aws ssm delete-parameter --name /lambda-execution-engine/home_assistant/url
  aws ssm delete-parameter --name /lambda-execution-engine/home_assistant/timeout
  # ... (delete other non-token parameters)
  ```
- **Token rotation:** Still supported (update SSM parameter, cache TTL expires)
- **Config changes:** Faster (update environment variable, redeploy)

### Future Considerations

**When to Revisit:**
- If additional secrets needed (API keys, etc.) - add to SSM
- If token rotation becomes frequent (consider Secrets Manager)
- If need config version history (consider additional tooling)

**Potential Evolution:**
- **Secrets Manager migration:** If rotation becomes requirement
- **Cache optimization:** Longer TTL, smarter invalidation
- **Multi-token support:** If additional integrations added
- **Configuration service:** If config becomes very complex

**Monitoring:**
- Track token retrieval latency
- Monitor cache hit rate
- Alert on token retrieval failures
- Measure cold start time improvements

---

## Related Topics

- **LESS-17**: SSM simplification lesson - this implements the lesson
- **BUG-04**: Config mismatch - problem that led to this analysis
- **PATH-01**: Cold start pathway - dramatically improved by this
- **DEC-20**: LAMBDA_MODE - companion operational decision (same date)
- **DEC-07**: Dependencies < 128MB - both are performance decisions
- **LESS-02**: Measure don't guess - profiling revealed this opportunity

---

## Keywords

SSM, parameter-store, performance, cold-start, security, configuration, optimization, secrets-management, 92-percent-improvement

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format with complete template
- **2025-10-20**: Decision documented in NM04-OPERATIONAL-Decisions.md

---

**File:** `NM04-Decisions-Operational_DEC-21.md`
**End of Document**

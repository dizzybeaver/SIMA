# File: DEC-21.md

**REF-ID:** DEC-21  
**Category:** Operational Decision  
**Priority:** Critical  
**Status:** Active  
**Date Decided:** 2025-10-20  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-30 (SIMAv4 migration)

---

## 📋 SUMMARY

SSM Parameter Store stores ONLY Home Assistant token. All other configuration moved to Lambda environment variables, resulting in 92% reduction in cold start overhead (3,000ms savings, from 3,250ms to 250ms).

**Decision:** SSM token-only configuration  
**Impact Level:** Critical  
**Reversibility:** Low (performance benefits too significant)

---

## 🎯 CONTEXT

### Problem Statement
The Lambda initially stored 13 configuration parameters in SSM Parameter Store (Home Assistant URL, timeout, log level, token, etc.). SSM retrieval requires API call averaging 250ms per parameter, resulting in 3,250ms cold start overhead just for configuration. Analysis revealed only ONE parameter needs encryption: the access token.

### Background
- Cold start: Import → Initialize → Fetch SSM (3,250ms) → Ready
- 13 SSM parameters × 250ms = 3,250ms overhead
- Only token requires encryption
- URLs, timeouts, log levels are non-sensitive
- Configuration split caused complexity

### Requirements
- Maintain security for sensitive data (token)
- Minimize cold start overhead
- Simplify configuration management
- Enable fast iteration

---

## 💡 DECISION

### What We Chose
Store ONLY the Home Assistant token in SSM Parameter Store (as SecureString). Move all other configuration to Lambda environment variables.

### Implementation
**Before (13 SSM parameters):**
```bash
/lee/home_assistant/url
/lee/home_assistant/token
/lee/home_assistant/timeout
/lee/config/log_level
/lee/config/debug_mode
# ... 8 more parameters
# Cold start: 3,250ms for SSM retrieval
```

**After (1 SSM parameter):**
```bash
# SSM Parameter Store (encrypted SecureString)
/lambda-execution-engine/home_assistant/token = "eyJhbGc..."

# Environment Variables (plain text - not sensitive)
HOME_ASSISTANT_URL=https://homeassistant.local:8123
HOME_ASSISTANT_TIMEOUT=30
LOG_LEVEL=CRITICAL
USE_PARAMETER_STORE=true
PARAMETER_PREFIX=/lambda-execution-engine

# Cold start: 250ms first call, 0ms cached (5min TTL)
```

**Code Implementation:**
```python
# Cache token with 5-minute TTL
@cache_with_ttl(ttl=300)
def get_ha_token():
    """Fetch HA token from SSM (only sensitive data)."""
    return ssm_client.get_parameter(
        Name=f"{PARAMETER_PREFIX}/home_assistant/token",
        WithDecryption=True
    )['Parameter']['Value']

# Non-sensitive config from environment
HA_URL = os.environ.get('HOME_ASSISTANT_URL')
HA_TIMEOUT = int(os.environ.get('HOME_ASSISTANT_TIMEOUT', '30'))
LOG_LEVEL = os.environ.get('LOG_LEVEL', 'CRITICAL')
```

### Rationale
1. **Massive Performance Improvement (92% reduction)**
   - Cold start overhead: 3,250ms → 250ms (92% improvement)
   - Warm container: 3,250ms → 0ms (token cached, 5min TTL)
   - User experience: From "sluggish" to "responsive"
   - Critical for Lambda: Cold start directly impacts UX

2. **Right-Sized Security**
   - Token needs encryption (credentials) → SSM SecureString ✅
   - URL doesn't need encryption (not sensitive) → Environment variable ✅
   - Timeout doesn't need encryption (just a number) → Environment variable ✅
   - Apply encryption only where needed

3. **Operational Simplicity**
   - Single SSM parameter to manage (token)
   - Other config in one place (Lambda environment)
   - No multiple-location config search
   - Easier deployment (fewer SSM parameters)

4. **Configuration Clarity**
   - Clear separation: Sensitive vs non-sensitive
   - Easy to see all configuration (environment variables)
   - Token hidden (SSM only)
   - Reduced cognitive load

---

## 🔄 ALTERNATIVES CONSIDERED

### Alternative 1: Keep All Config in SSM
**Approach:** Maintain all 13 parameters in SSM Parameter Store.

**Pros:**
- Centralized configuration
- All encrypted
- No migration needed

**Cons:**
- 3,250ms cold start overhead
- Slow user experience
- Over-applies encryption to non-sensitive data
- Complex deployment

**Why Not Chosen:** Performance cost too high for marginal security benefit.

### Alternative 2: All Config in Environment Variables
**Approach:** Move everything including token to environment variables.

**Pros:**
- Zero SSM overhead
- Simplest configuration
- Fastest cold start

**Cons:**
- Token exposed in plaintext
- Security best practice violation
- Compliance issues (token is credential)

**Why Not Chosen:** Unacceptable security trade-off.

### Alternative 3: Caching All SSM Parameters
**Approach:** Keep all in SSM but cache aggressively (longer TTL).

**Pros:**
- Maintains SSM-based configuration
- Reduces warm container overhead

**Cons:**
- Still 3,250ms cold start penalty
- Doesn't solve core problem
- Cache invalidation complexity

**Why Not Chosen:** Doesn't address cold start, only warm container.

### Alternative 4: AWS Secrets Manager
**Approach:** Use Secrets Manager instead of SSM Parameter Store.

**Pros:**
- Purpose-built for secrets
- Automatic rotation

**Cons:**
- Higher cost ($0.40/secret/month vs $0.05/param)
- Still requires API call (similar latency)
- Doesn't solve 13-parameter problem

**Why Not Chosen:** Doesn't solve performance issue, adds cost.

---

## ⚖️ TRADE-OFFS

### Benefits
- **Performance:** 92% cold start improvement (-3,000ms)
- **Simplicity:** Single SSM parameter vs 13
- **Security:** Appropriate encryption (token only)
- **Cost:** Reduced SSM API calls
- **Maintainability:** Easier configuration management

### Costs
- **Migration:** One-time SSM → environment variable transfer
- **Split configuration:** Two locations (SSM + environment)
- **Documentation:** Update deployment guides

### Net Assessment
The 3,000ms performance gain (92% improvement) far outweighs the minor complexity of split configuration. This is the right security-performance trade-off.

---

## 📊 IMPACT ANALYSIS

### On Architecture
- **Impact Level:** Low
- **Description:** Configuration source change only
- **Affected Components:** Config loading, initialization
- **Migration Required:** Move 12 parameters to environment variables

### On Development
- **Impact Level:** Low
- **Description:** Simplified configuration code
- **Code Changes:** Update config loading to check environment first
- **Testing:** Verify token still encrypted, other config works

### On Performance
- **Impact Level:** Critical (positive)
- **Description:** 92% cold start improvement
- **Metrics:**
  - Cold start: 3,250ms → 250ms (-3,000ms)
  - Warm container: 3,250ms → 0ms (cached)
  - P99 latency: Significantly improved

### On Security
- **Impact Level:** None (maintained)
- **Description:** Token remains encrypted in SSM
- **Non-sensitive data:** Appropriately in environment variables
- **Best practice:** Right-sized security

### On Operations
- **Impact Level:** Low (positive)
- **Description:** Easier deployment
- **Configuration:** Simpler (1 SSM param vs 13)
- **Troubleshooting:** Faster (less to check)

---

## 🔮 FUTURE CONSIDERATIONS

### When to Revisit
- If additional sensitive parameters needed (add to SSM)
- If token rotation required (Secrets Manager migration)
- If configuration becomes complex (config service)

### Monitoring
- Track SSM API call latency
- Monitor cache hit rates (token caching)
- Alert on SSM parameter changes
- Measure cold start improvements

### Potential Evolution
- **Automatic token rotation:** Migrate to AWS Secrets Manager
- **Config service:** If many parameters need versioning
- **Multi-environment:** Different SSM paths per environment

---

## 🔗 RELATED

### Related Decisions
- **DEC-20**: LAMBDA_MODE - Companion operational decision
- **DEC-13**: Fast Path Caching - Performance optimization pattern
- **DEC-12**: Multi-Tier Configuration - Configuration management approach

### Related Lessons
- **LESS-17**: SSM simplification lessons
- **LESS-02**: Performance optimization patterns

### Related Bugs
- **BUG-04**: Configuration mismatch - This decision solves the issue

### Related Architecture
- **INT-02**: Config Interface - Configuration management
- **ARCH-01**: SUGA Pattern - Initialization and configuration

---

## 🏷️ KEYWORDS

ssm, parameter-store, configuration, cold-start, performance, optimization, security, secrets-management, environment-variables, lambda-performance

---

## 📚 VERSION HISTORY

- **2025-10-30**: Migrated to SIMAv4 format
- **2025-10-23**: Migrated to SIMA v3 individual file format
- **2025-10-20**: Original decision documented

---

**File:** `DEC-21.md`  
**Path:** `/sima/entries/decisions/operational/DEC-21.md`  
**End of Document**

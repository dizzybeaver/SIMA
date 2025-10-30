# NEURAL_MAP_04 ADDENDUM: 2025.10.20 Design Decisions
# New Decisions: SSM, Debug System, Configuration
# Version: 2.0.0 Addendum | Date: 2025.10.20

---

**ADDENDUM PURPOSE:**
Document 4 major design decisions made on 2025.10.20:
- LAMBDA_MODE replacing LEE_FAILSAFE_ENABLED
- SSM token-only simplification
- DEBUG_MODE and DEBUG_TIMINGS system
- Enhanced configuration patterns

**INTEGRATION:** These should be added to main NEURAL_MAP_04 as DEC-20 through DEC-23

---

## Decision 20: LAMBDA_MODE Over LEE_FAILSAFE_ENABLED
**REF:** NM04-DEC-20
**PRIORITY:** 🔴 CRITICAL
**TAGS:** configuration, lambda-mode, failsafe, extensibility
**KEYWORDS:** LAMBDA_MODE, operational modes, failsafe mode
**RELATED:** NM06-LESS-17, NM04-DEC-21
**DATE:** 2025.10.20

**What:** Replace `LEE_FAILSAFE_ENABLED=true` with `LAMBDA_MODE=failsafe`

**Why:**
1. **More Extensible**
   - Old: Binary (enabled/disabled)
   - New: Enumerated (normal, failsafe, diagnostic, etc.)
   - Easy to add new modes

2. **Clearer Intent**
   - Old: "LEE_FAILSAFE_ENABLED" - double negative confusion
   - New: "LAMBDA_MODE" - describes what Lambda does
   - Self-documenting configuration

3. **Consistent Pattern**
   - Matches industry standard configuration
   - Similar to LOG_LEVEL, ENVIRONMENT patterns
   - Predictable for users

4. **Future-Proof**
   - Can add diagnostic mode
   - Can add test mode
   - Can add performance modes
   - No breaking changes needed

**Migration:**
```bash
# Old (deprecated)
LEE_FAILSAFE_ENABLED=true

# New (current)
LAMBDA_MODE=failsafe
```

**Supported values:**
- `normal` (default) - Full LEE/SUGA operation
- `failsafe` - Emergency bypass mode
- `diagnostic` (future) - Enhanced troubleshooting

**Trade-offs:**
- Pro: Flexible, extensible, clear intent
- Pro: Standard configuration pattern
- Con: Breaking change (requires migration)
- Con: Must update documentation
- **Decision: Extensibility and clarity worth migration**

**Implementation:**
```python
# lambda_function.py
lambda_mode = os.environ.get('LAMBDA_MODE', 'normal').lower()

if lambda_mode == 'failsafe':
    from lambda_failsafe import handler as failsafe_handler
    return failsafe_handler(event, context)
elif lambda_mode == 'normal':
    # Normal LEE operation
    ...
```

**Related documentation:**
- BREAKING CHANGE - LEE_FAILSAFE_ENABLED to LAMBDA_MODE.md
- Lambda Configuration Scenarios.md

---

## Decision 21: SSM Token-Only (All Other Config in Environment)
**REF:** NM04-DEC-21
**PRIORITY:** 🔴 CRITICAL
**TAGS:** SSM, configuration, performance, security, simplification
**KEYWORDS:** SSM token only, parameter store, environment variables
**RELATED:** NM06-LESS-17, NM06-BUG-02, NM04-DEC-20
**DATE:** 2025.10.20

**What:** SSM Parameter Store stores ONLY the Home Assistant token. All other configuration in Lambda environment variables.

**Why:**
1. **Performance**
   - Old: 13 parameters × 250ms = 3250ms SSM overhead
   - New: 1 parameter × 250ms = 250ms SSM overhead
   - Savings: ~3000ms (92% reduction) per cold start

2. **Simplicity**
   - Single-purpose SSM: secrets only
   - All visible config in one place (Lambda console)
   - Easier to understand and debug

3. **Security**
   - Token still encrypted at rest (SecureString)
   - Token still encrypted in transit (TLS)
   - Non-sensitive config doesn't need SSM

4. **Operational Efficiency**
   - Fewer SSM API calls = lower costs
   - Faster cold starts = better UX
   - Simpler IAM permissions (one parameter)

**Before:**
```
SSM Parameters (13):
â"œâ"€ /lambda/log_level
â"œâ"€ /lambda/environment  
â"œâ"€ /lambda/home_assistant/url
â"œâ"€ /lambda/home_assistant/token
â"œâ"€ /lambda/home_assistant/timeout
â"œâ"€ ... (8 more)

Lambda Environment: (minimal)

Cost: 13 SSM API calls × 250ms
```

**After:**
```
SSM Parameters (1):
â""â"€ /lambda/home_assistant/token (SecureString)

Lambda Environment Variables:
â"œâ"€ HOME_ASSISTANT_URL=http://...
â"œâ"€ HOME_ASSISTANT_TIMEOUT=30
â"œâ"€ HOME_ASSISTANT_VERIFY_SSL=true
â"œâ"€ ... (all other config)

Cost: 1 SSM API call × 250ms (only when needed)
```

**Trade-offs:**
- Pro: 1000-2250ms cold start improvement
- Pro: Simpler architecture
- Pro: Lower AWS costs
- Pro: Easier configuration management
- Con: Token must be in environment for non-SSM deployments
- Con: Breaking change (requires migration)
- **Decision: Performance and simplicity worth migration**

**Implementation files:**
```
config_param_store.py  - Simplified to get_ha_token() only
ha_config.py          - Environment-first, SSM for token
lambda_failsafe.py    - Token-only SSM support
```

**Pattern:**
```python
# Load from environment first
config = load_from_environment()

# Only load token from SSM if USE_PARAMETER_STORE=true
if os.getenv('USE_PARAMETER_STORE', 'false').lower() == 'true':
    config['token'] = get_ha_token()  # Single SSM call
else:
    config['token'] = os.getenv('HOME_ASSISTANT_TOKEN')

return config
```

**Cache behavior:**
```
First call: SSM API 250ms → cache (TTL 300s)
Subsequent calls: <2ms (from cache)
After 300s: SSM API again
```

**Related documentation:**
- MIGRATION GUIDE - SSM Simplification (Token Only).md
- SUMMARY - SSM Simplification and Debug System.md
- Lambda Configuration Scenarios.md

---

## Decision 22: DEBUG_MODE - Flow Visibility
**REF:** NM04-DEC-22
**PRIORITY:** 🟡 HIGH
**TAGS:** debugging, observability, diagnostics, troubleshooting
**KEYWORDS:** DEBUG_MODE, debug output, operation flow
**RELATED:** NM04-DEC-23, NM06-LESS-18
**DATE:** 2025.10.20

**What:** Environment variable `DEBUG_MODE=true` enables operation flow visibility

**Why:**
1. **Troubleshooting Visibility**
   - See operation routing decisions
   - Track cache hit/miss events
   - Monitor configuration loading
   - Identify error conditions

2. **No Code Changes**
   - Toggle via environment variable
   - No redeployment needed
   - Safe for production (temporarily)

3. **CloudWatch Integration**
   - Uses print() → CloudWatch Logs
   - Standard Lambda logging pattern
   - Easy to query with Insights

4. **Selective Debugging**
   - Can enable per-component
   - Minimal performance overhead (<1ms)
   - Clear output format

**Output examples:**
```
[SSM_DEBUG] Retrieving Home Assistant token from SSM
[HA_CONFIG_DEBUG] Loading HA config (force_refresh=false)
[CACHE_DEBUG] cache_get: key=ha_config, hit=True
[GATEWAY_DEBUG] execute_operation: interface=CACHE, operation=get
[FAILSAFE_DEBUG] Failsafe mode handler invoked
```

**Pattern used throughout:**
```python
def _is_debug_mode() -> bool:
    """Check if DEBUG_MODE is enabled."""
    return os.getenv('DEBUG_MODE', 'false').lower() == 'true'

def _print_debug(msg: str):
    """Print debug message only if DEBUG_MODE=true."""
    if _is_debug_mode():
        print(f"[COMPONENT_DEBUG] {msg}")

# Usage
_print_debug(f"Operation: {operation}, params: {params}")
```

**When to use:**
- Debugging execution flow
- Understanding routing decisions
- Investigating configuration issues
- Troubleshooting API failures
- Extension behavior analysis

**Trade-offs:**
- Pro: No code changes needed
- Pro: Toggle on/off instantly
- Pro: Minimal overhead
- Con: 3-5x log volume increase
- Con: CloudWatch costs increase (~$0.50-$1.00 per million requests)
- **Decision: Diagnostic value worth temporary cost**

**Best practices:**
```bash
# Enable temporarily
aws lambda update-function-configuration \
  --function-name my-lambda \
  --environment Variables="{DEBUG_MODE=true,...}"

# Diagnose issue
# ... trigger Lambda, check logs ...

# Disable immediately
aws lambda update-function-configuration \
  --function-name my-lambda \
  --environment Variables="{DEBUG_MODE=false,...}"
```

**Cost mitigation:**
- Enable only when troubleshooting
- Set CloudWatch retention to 7 days for debug logs
- Disable immediately after diagnosis
- Never leave enabled long-term in production

**Files with DEBUG_MODE support:**
- config_param_store.py
- ha_config.py
- lambda_failsafe.py
- (more to be added)

**Related documentation:**
- REMINDER - Debug Trapping and Performance Analysis.md

---

## Decision 23: DEBUG_TIMINGS - Performance Visibility
**REF:** NM04-DEC-23
**PRIORITY:** 🟡 HIGH
**TAGS:** performance, debugging, profiling, measurement
**KEYWORDS:** DEBUG_TIMINGS, performance measurement, timing analysis
**RELATED:** NM04-DEC-22, NM06-LESS-18, NM06-LESS-02
**DATE:** 2025.10.20

**What:** Environment variable `DEBUG_TIMINGS=true` enables performance measurements

**Why:**
1. **Performance Analysis**
   - Identify bottlenecks
   - Measure SSM API latency
   - Track cache performance
   - Find slow operations

2. **Step-by-Step Breakdowns**
   - See timing within complex operations
   - Understand where time is spent
   - Optimize based on data, not guesses

3. **No Code Changes**
   - Toggle via environment variable
   - Can combine with DEBUG_MODE
   - Safe for production (temporarily)

4. **CloudWatch Integration**
   - Parse timing data with Insights
   - Generate performance reports
   - Track improvements over time

**Output examples:**
```
[SSM_TIMING] ===== GET_HA_TOKEN START =====
[SSM_TIMING] Step 1: Default sanitized (elapsed: 0.12ms)
[SSM_TIMING] Step 2: Checking cache... (elapsed: 0.45ms)
[SSM_TIMING] *** CACHE HIT: 0.78ms (total: 1.23ms) ***

[HA_CONFIG_TIMING] ===== LOAD_HA_CONFIG START =====
[HA_CONFIG_TIMING] Checking cache... (elapsed: 0.12ms)
[HA_CONFIG_TIMING] ===== COMPLETE (CACHE HIT): 1.45ms =====

[FAILSAFE_TIMING] ===== FORWARD_TO_HOME_ASSISTANT START =====
[FAILSAFE_TIMING] HTTP request: 150ms
[FAILSAFE_TIMING] ===== COMPLETE: 152ms =====
```

**Pattern used throughout:**
```python
import time

def _is_debug_timings() -> bool:
    """Check if DEBUG_TIMINGS is enabled."""
    return os.getenv('DEBUG_TIMINGS', 'false').lower() == 'true'

def _print_timing(msg: str):
    """Print timing message only if DEBUG_TIMINGS=true."""
    if _is_debug_timings():
        print(f"[COMPONENT_TIMING] {msg}")

# Usage
start = time.time()
result = expensive_operation()
elapsed_ms = (time.time() - start) * 1000
_print_timing(f"Operation complete: {elapsed_ms:.2f}ms")
```

**When to use:**
- Identifying performance bottlenecks
- Optimizing cold starts
- Analyzing slow operations
- Measuring SSM latency
- Validating optimizations

**CloudWatch Insights queries:**
```
# Find slow operations (>1 second)
fields @timestamp, @message
| filter @message like /\[.*_TIMING\]/
| filter @message like /elapsed:/
| parse @message '*elapsed: *ms*' as component, elapsed
| filter elapsed > 1000
| sort elapsed desc

# SSM performance tracking
fields @timestamp, @message
| filter @message like /\[SSM_TIMING\]/
| filter @message like /SSM API:/
| parse @message '*SSM API: *ms*' as label, duration
| stats avg(duration), max(duration), count() as calls
```

**Performance baselines:**
```
Cold start targets:
- Total: <500ms
- Module imports: <200ms
- Gateway init: <50ms
- Extension load: <100ms

Operation targets:
- Cache get (hit): <1ms
- Cache get (miss): <5ms
- SSM call (cached): <2ms
- SSM call (API): <250ms
- Gateway dispatch: <1ms
- HTTP request: <200ms
```

**Trade-offs:**
- Pro: Data-driven optimization
- Pro: Minimal overhead (<1ms)
- Pro: Clear bottleneck identification
- Con: 2-3x log volume increase
- Con: CloudWatch costs increase (~$0.30-$0.60 per million requests)
- **Decision: Performance insights worth temporary cost**

**Combined with DEBUG_MODE:**
```bash
# Both enabled for comprehensive diagnostics
DEBUG_MODE=true
DEBUG_TIMINGS=true

# Log volume: 5-8x increase
# Cost: ~$0.80-$1.60 per million requests
# Use: Temporarily for deep troubleshooting
```

**Files with DEBUG_TIMINGS support:**
- config_param_store.py
- ha_config.py
- lambda_failsafe.py
- (more to be added)

**Related documentation:**
- REMINDER - Debug Trapping and Performance Analysis.md
- Lambda Configuration Scenarios.md (Debug Mode section)

---

## Integration Notes

### How to Add to Main NM04

These 4 decisions should be integrated into the main NEURAL_MAP_04 file as:

```
PART 4: CONFIGURATION & OPERATIONS DECISIONS
â"œâ"€ Decision 20: LAMBDA_MODE Over LEE_FAILSAFE_ENABLED
â"œâ"€ Decision 21: SSM Token-Only
â"œâ"€ Decision 22: DEBUG_MODE - Flow Visibility
â""â"€ Decision 23: DEBUG_TIMINGS - Performance Visibility
```

### Cross-References to Update

**In NM06 (Learned Experiences):**
- NM06-LESS-17 references NM04-DEC-21 (SSM token-only)
- NM06-LESS-18 references NM04-DEC-22, NM04-DEC-23 (debug system)
- NM06-BUG-02 references NM04-DEC-21 (SSM simplification)

**In NM00 (Quick Index):**
- Add triggers for: SSM, debug, LAMBDA_MODE, configuration
- Update decision quick-paths

**In NM05 (Anti-Patterns):**
- Add AP for not using debug modes when troubleshooting
- Add AP for leaving debug modes enabled in production

---

## Summary

**4 Major Design Decisions Added:**

1. **LAMBDA_MODE** - Flexible operation mode system
   - Replaces binary failsafe flag
   - Extensible for future modes
   - Industry-standard pattern

2. **SSM Token-Only** - Massive performance improvement
   - 92% reduction in SSM overhead
   - 1000-2250ms cold start savings
   - Simpler architecture

3. **DEBUG_MODE** - Operation flow visibility
   - Toggle without code changes
   - Troubleshooting visibility
   - CloudWatch integration

4. **DEBUG_TIMINGS** - Performance analysis
   - Data-driven optimization
   - Bottleneck identification
   - Minimal overhead

**Impact:** These 4 decisions collectively improve system performance, observability, and maintainability significantly.

---

# EOF

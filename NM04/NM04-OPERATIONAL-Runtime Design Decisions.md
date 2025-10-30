# NM04-OPERATIONAL: Runtime Design Decisions
# SIMA Pattern Implementation - Operational Choices
# Version: 2.0.0 | Phase: 2 SIMA Implementation | Date: 2025.10.20

---

## Purpose

This file documents **operational design decisions** made on 2025.10.20 that affect how the SUGA-ISP Lambda runs in production. These decisions focus on configuration, debugging, and runtime behavior.

**File Contents:**
- DEC-20: LAMBDA_MODE over LEE_FAILSAFE_ENABLED (CRITICAL)
- DEC-21: SSM token-only (CRITICAL)
- DEC-22: DEBUG_MODE flow visibility (HIGH)
- DEC-23: DEBUG_TIMINGS performance tracking (HIGH)

---

## PART 1: CONFIGURATION DECISIONS

### Decision 20: LAMBDA_MODE Over LEE_FAILSAFE_ENABLED
**REF:** NM04-DEC-20
**PRIORITY:** 🔴 CRITICAL
**TAGS:** configuration, lambda-mode, failsafe, extensibility, breaking-change
**KEYWORDS:** LAMBDA_MODE, operational modes, failsafe mode, LEE_FAILSAFE_ENABLED
**RELATED:** NM06-LESS-17, NM04-DEC-21
**DATE:** 2025.10.20

**What:** Replace `LEE_FAILSAFE_ENABLED=true` with `LAMBDA_MODE=failsafe`

**Why:**

1. **More Extensible**
   - Old: Binary (enabled/disabled)
   - New: Enumerated (normal, failsafe, diagnostic, test, etc.)
   - Easy to add new modes without breaking changes
   - Future-proof design

2. **Clearer Intent**
   - Old: "LEE_FAILSAFE_ENABLED" - double negative confusion
   - New: "LAMBDA_MODE" - describes what Lambda does
   - Self-documenting configuration
   - Matches industry patterns

3. **Consistent Pattern**
   - Matches standard configuration practices
   - Similar to LOG_LEVEL, ENVIRONMENT patterns
   - Predictable for operators
   - Reduces cognitive load

4. **Future-Proof**
   - Can add diagnostic mode (enhanced logging)
   - Can add test mode (mock external calls)
   - Can add performance mode (optimizations)
   - No breaking changes needed for new modes

**Migration:**

```bash
# Old (deprecated)
LEE_FAILSAFE_ENABLED=true

# New (current)
LAMBDA_MODE=failsafe
```

**Supported Values:**

- `normal` (default) - Full Lambda Execution Engine (LEE) operation
- `failsafe` - Emergency bypass mode (skip LEE, direct Lambda)
- `diagnostic` (future) - Enhanced troubleshooting with verbose logging
- `test` (future) - Mock external calls for testing
- `performance` (future) - Aggressive optimizations

**Trade-offs:**
- Pro: Flexible, extensible, clear intent, future-proof
- Pro: Standard configuration pattern
- Con: Breaking change (requires migration)
- Con: Must update documentation
- **Decision:** Extensibility and clarity worth migration**

**Implementation:**

```python
# lambda_function.py
lambda_mode = os.environ.get('LAMBDA_MODE', 'normal').lower()

if lambda_mode == 'failsafe':
    from lambda_failsafe import handler as failsafe_handler
    return failsafe_handler(event, context)
    
elif lambda_mode == 'diagnostic':
    # Future: Enhanced logging mode
    os.environ['LOG_LEVEL'] = 'DEBUG'
    os.environ['DEBUG_MODE'] = 'true'
    # Continue to normal operation
    
elif lambda_mode == 'normal':
    # Normal LEE operation
    from gateway import execute_operation
    return execute_operation(event, context)
    
else:
    raise ValueError(f"Unknown LAMBDA_MODE: {lambda_mode}")
```

**Benefits:**

- **Clarity:** `LAMBDA_MODE=failsafe` is immediately understandable
- **Extensibility:** Adding `diagnostic` mode requires no config changes
- **Standards:** Follows industry best practices
- **Self-documenting:** Code explains itself

**Migration Guide:**

```bash
# Step 1: Update Lambda environment variable
# AWS Console → Lambda → Configuration → Environment variables
# Change: LEE_FAILSAFE_ENABLED=true
# To: LAMBDA_MODE=failsafe

# Step 2: Update deployment scripts
# Old:
aws lambda update-function-configuration \
  --environment Variables="{LEE_FAILSAFE_ENABLED=true}"

# New:
aws lambda update-function-configuration \
  --environment Variables="{LAMBDA_MODE=failsafe}"

# Step 3: Update documentation
# Update all references to LEE_FAILSAFE_ENABLED
```

**Related Documentation:**
- BREAKING CHANGE - LEE_FAILSAFE_ENABLED to LAMBDA_MODE.md
- Lambda Configuration Scenarios.md
- NM06-LESS-17 (Lessons learned from config changes)

**Rationale:**
The enumerated LAMBDA_MODE pattern provides clear intent and extensibility. While it's a breaking change, the long-term benefits of having a flexible mode system outweigh the one-time migration cost.

---

### Decision 21: SSM Token-Only (All Other Config in Environment)
**REF:** NM04-DEC-21
**PRIORITY:** 🔴 CRITICAL
**TAGS:** SSM, configuration, performance, security, simplification, cold-start
**KEYWORDS:** SSM token only, parameter store, environment variables, cold start
**RELATED:** NM06-LESS-17, NM06-BUG-04, NM04-DEC-20, NM03-PATH-01
**DATE:** 2025.10.20

**What:** SSM Parameter Store stores ONLY the Home Assistant token. All other configuration in Lambda environment variables.

**Why:**

1. **Massive Performance Improvement**
   - Old: 13 parameters × 250ms = 3,250ms SSM overhead
   - New: 1 parameter × 250ms = 250ms SSM overhead
   - **Savings: 3,000ms (92% reduction) per cold start**
   - Critical for user experience

2. **Simplicity**
   - Single-purpose SSM: secrets only
   - All visible config in one place (Lambda console)
   - Easier to understand and debug
   - Clearer separation of concerns

3. **Security Still Maintained**
   - Token still encrypted at rest (SecureString)
   - Token still encrypted in transit (TLS)
   - Non-sensitive config doesn't need SSM
   - Right-sized security approach

4. **Operational Efficiency**
   - Fewer SSM API calls = lower AWS costs
   - Faster cold starts = better UX
   - Simpler IAM permissions (one parameter)
   - Easier deployment (environment vars standard)

**Before (13 SSM Parameters):**

```
SSM Parameters:
├── /lambda-execution-engine/log_level
├── /lambda-execution-engine/environment  
├── /lambda-execution-engine/home_assistant/url
├── /lambda-execution-engine/home_assistant/token
├── /lambda-execution-engine/home_assistant/timeout
├── /lambda-execution-engine/home_assistant/verify_ssl
├── /lambda-execution-engine/cache/size_mb
├── /lambda-execution-engine/cache/ttl_seconds
└── ... (5 more)

Lambda Environment: (minimal)

Cold start cost: 13 × 250ms = 3,250ms
```

**After (1 SSM Parameter):**

```
SSM Parameters:
└── /lambda-execution-engine/home_assistant/token (SecureString only)

Lambda Environment Variables:
├── HOME_ASSISTANT_URL=https://homeassistant.02011978.xyz
├── HOME_ASSISTANT_TIMEOUT=30
├── HOME_ASSISTANT_VERIFY_SSL=true
├── LOG_LEVEL=CRITICAL
├── LAMBDA_MODE=normal
├── DEBUG_MODE=false
├── USE_PARAMETER_STORE=true
└── PARAMETER_PREFIX=/lambda-execution-engine

Cold start cost: 1 × 250ms = 250ms (only when needed)
```

**Trade-offs:**
- Pro: 3,000ms cold start improvement (92% reduction)
- Pro: Simpler architecture, lower costs
- Pro: Easier configuration management
- Con: Token must be in environment for non-SSM deployments
- Con: Breaking change (requires migration)
- **Decision:** Performance and simplicity worth migration**

**Implementation:**

```python
# config_param_store.py - Simplified to token only
def get_ha_token():
    """Get Home Assistant token from SSM (only if enabled)."""
    if not use_parameter_store():
        return os.environ.get('HOME_ASSISTANT_TOKEN')
    
    param_name = f"{PARAMETER_PREFIX}/home_assistant/token"
    
    # Check cache first (TTL 300s)
    cached = _token_cache.get(param_name)
    if cached and time.time() - cached['timestamp'] < 300:
        return cached['value']
    
    # Fetch from SSM (250ms)
    response = ssm_client.get_parameter(
        Name=param_name,
        WithDecryption=True
    )
    token = response['Parameter']['Value']
    
    # Cache for 5 minutes
    _token_cache[param_name] = {
        'value': token,
        'timestamp': time.time()
    }
    
    return token

# ha_config.py - Environment-first approach
def load_ha_config():
    """Load Home Assistant configuration."""
    return {
        'url': os.environ.get('HOME_ASSISTANT_URL'),
        'timeout': int(os.environ.get('HOME_ASSISTANT_TIMEOUT', '30')),
        'verify_ssl': os.environ.get('HOME_ASSISTANT_VERIFY_SSL', 'true').lower() == 'true',
        'token': get_ha_token(),  # Only this from SSM
    }
```

**Cache Behavior:**

```
First call: SSM API 250ms → cache (TTL 300s)
Subsequent calls within 5 min: < 2ms (from cache)
After 300s: SSM API again
Container reuse: Cache persists, no SSM call needed
```

**Performance Impact:**

```
Scenario 1: Cold start with SSM
- Old: 3,250ms SSM overhead
- New: 250ms SSM overhead
- Improvement: 3,000ms (12x faster)

Scenario 2: Warm start (container reuse)
- Old: 3,250ms SSM overhead (no cache before)
- New: 0ms (cached token, 5min TTL)
- Improvement: 3,250ms (infinite improvement)

Scenario 3: Cold start without SSM (env var token)
- Old: 3,250ms SSM overhead
- New: 0ms (no SSM call)
- Improvement: 3,250ms (infinite improvement)
```

**Migration Guide:**

```bash
# Step 1: Keep token in SSM (already exists)
# /lambda-execution-engine/home_assistant/token (SecureString)

# Step 2: Move other config to environment variables
aws lambda update-function-configuration \
  --environment Variables="{
    HOME_ASSISTANT_URL=https://homeassistant.02011978.xyz,
    HOME_ASSISTANT_TIMEOUT=30,
    HOME_ASSISTANT_VERIFY_SSL=true,
    LOG_LEVEL=CRITICAL,
    USE_PARAMETER_STORE=true,
    PARAMETER_PREFIX=/lambda-execution-engine
  }"

# Step 3: Delete old SSM parameters (AFTER verifying)
aws ssm delete-parameter --name /lambda-execution-engine/log_level
aws ssm delete-parameter --name /lambda-execution-engine/home_assistant/url
# ... (delete other 11 parameters)

# Step 4: Keep only token parameter
# /lambda-execution-engine/home_assistant/token
```

**Related Documentation:**
- MIGRATION GUIDE - SSM Simplification (Token Only).md
- SUMMARY - SSM Simplification and Debug System.md
- Lambda Configuration Scenarios.md
- NM06-BUG-04 (Config mismatch bug that led to this decision)

**Rationale:**
The 3,000ms (92%) cold start improvement dramatically improves user experience while maintaining security for the sensitive token. Non-sensitive configuration doesn't need SSM's overhead, and environment variables are the standard way to configure Lambda functions.

---

## PART 2: DEBUGGING DECISIONS

### Decision 22: DEBUG_MODE - Flow Visibility
**REF:** NM04-DEC-22
**PRIORITY:** 🟡 HIGH
**TAGS:** debugging, observability, diagnostics, troubleshooting, development
**KEYWORDS:** DEBUG_MODE, debug output, operation flow, troubleshooting
**RELATED:** NM04-DEC-23, NM06-LESS-18
**DATE:** 2025.10.20

**What:** Environment variable `DEBUG_MODE=true` enables operation flow visibility

**Why:**

1. **Development Troubleshooting**
   - See exactly what operations are called
   - Understand execution flow
   - Identify where errors occur
   - Debug integration issues

2. **Production Diagnostics**
   - Enable temporarily in production
   - Diagnose customer issues
   - No code deployment needed
   - Quick enable/disable

3. **Performance Analysis**
   - See operation sequence
   - Identify unnecessary operations
   - Optimize hot paths
   - Reduce redundant calls

4. **Learning Tool**
   - New developers see system behavior
   - Understand how operations flow
   - Learn architecture by observation
   - Self-documenting system

**Trade-offs:**
- Pro: Excellent debugging, no code changes, instant enable
- Con: Increased log volume (more CloudWatch costs)
- Con: May slow down execution slightly (~10ms per request)
- **Decision:** Debugging capability worth the cost when enabled

**Implementation:**

```python
# gateway_core.py
def execute_operation(interface, operation, **kwargs):
    # Debug logging if enabled
    if os.environ.get('DEBUG_MODE', 'false').lower() == 'true':
        log_info(
            f"[DEBUG] Executing: {interface}.{operation}",
            interface=interface,
            operation=operation,
            kwargs_keys=list(kwargs.keys())
        )
    
    # Normal operation
    router = _get_interface_router(interface)
    result = router.execute_operation(operation, **kwargs)
    
    # Debug result if enabled
    if os.environ.get('DEBUG_MODE', 'false').lower() == 'true':
        log_info(
            f"[DEBUG] Completed: {interface}.{operation}",
            result_type=type(result).__name__,
            result_size=len(str(result)) if result else 0
        )
    
    return result
```

**Example Output:**

```
Normal mode (DEBUG_MODE=false):
[INFO] Cache get: user_123
[INFO] Cache hit: user_123

Debug mode (DEBUG_MODE=true):
[DEBUG] Executing: cache.get, kwargs_keys=['key']
[INFO] Cache get: user_123
[DEBUG] Completed: cache.get, result_type=dict, result_size=156
[DEBUG] Executing: logging.info, kwargs_keys=['message', 'context']
[INFO] Cache hit: user_123
[DEBUG] Completed: logging.info, result_type=NoneType, result_size=0
```

**Use Cases:**

**Development:**
```bash
# Local testing with debug mode
DEBUG_MODE=true python lambda_function.py
```

**Production Diagnosis:**
```bash
# Temporarily enable for specific investigation
aws lambda update-function-configuration \
  --environment Variables="{DEBUG_MODE=true,...}"

# Investigate issue in CloudWatch logs
# Disable after diagnosis
aws lambda update-function-configuration \
  --environment Variables="{DEBUG_MODE=false,...}"
```

**Performance Analysis:**
```
[DEBUG] Executing: cache.get
[DEBUG] Executing: http.post  
[DEBUG] Executing: cache.set
[DEBUG] Executing: cache.get  ← Redundant? Just set it!
```

**Cost Analysis:**

- Normal mode: ~5 log messages per request
- Debug mode: ~20 log messages per request
- CloudWatch cost: ~$0.50 per GB
- Impact: ~$0.03 per 100k requests
- **Acceptable cost for debugging capability**

**Rationale:**
DEBUG_MODE provides instant visibility into system behavior without code changes. The ability to enable/disable via environment variable makes it practical for both development and production debugging.

---

### Decision 23: DEBUG_TIMINGS - Performance Tracking
**REF:** NM04-DEC-23
**PRIORITY:** 🟡 HIGH
**TAGS:** performance, timing, profiling, optimization, metrics
**KEYWORDS:** DEBUG_TIMINGS, performance tracking, operation timing, profiling
**RELATED:** NM04-DEC-22, NM06-LESS-02
**DATE:** 2025.10.20

**What:** Environment variable `DEBUG_TIMINGS=true` enables operation timing measurements

**Why:**

1. **Performance Optimization**
   - Measure actual operation times
   - Identify slow operations
   - Prioritize optimization work
   - Validate improvements

2. **Regression Detection**
   - Compare timings before/after changes
   - Catch performance regressions
   - Prove optimizations work
   - Maintain performance standards

3. **Resource Planning**
   - Understand where time is spent
   - Right-size Lambda timeout
   - Plan cold start improvements
   - Optimize critical paths

4. **Evidence-Based Decisions**
   - "Measure, don't guess" (NM06-LESS-02)
   - Data-driven optimization
   - Prove hypotheses
   - Avoid premature optimization

**Trade-offs:**
- Pro: Precise measurements, identifies bottlenecks, proves optimizations
- Con: Small overhead (~5-10μs per operation)
- Con: More log volume
- **Decision:** Performance insights worth minimal overhead

**Implementation:**

```python
# gateway_core.py
import time

def execute_operation(interface, operation, **kwargs):
    # Start timing if enabled
    if os.environ.get('DEBUG_TIMINGS', 'false').lower() == 'true':
        start_time = time.perf_counter()
    
    # Execute operation
    router = _get_interface_router(interface)
    result = router.execute_operation(operation, **kwargs)
    
    # Log timing if enabled
    if os.environ.get('DEBUG_TIMINGS', 'false').lower() == 'true':
        elapsed_ms = (time.perf_counter() - start_time) * 1000
        log_info(
            f"[TIMING] {interface}.{operation}: {elapsed_ms:.2f}ms",
            interface=interface,
            operation=operation,
            elapsed_ms=elapsed_ms
        )
    
    return result
```

**Example Output:**

```
[TIMING] cache.get: 0.85ms
[TIMING] http.post: 125.43ms
[TIMING] cache.set: 1.02ms
[TIMING] logging.info: 0.15ms

Summary:
- Total request time: 127.45ms
- HTTP dominated: 98.4% of time
- Cache operations fast: < 2ms total
```

**Use Cases:**

**Optimization Work:**
```bash
# Before optimization
DEBUG_TIMINGS=true python lambda_function.py
[TIMING] cache.get: 12.50ms  ← Slow!

# After optimization (added index)
[TIMING] cache.get: 0.85ms   ← 14.7x faster!
```

**Regression Detection:**
```bash
# Version 1.0
[TIMING] process_request: 150ms

# Version 1.1 (after refactor)
[TIMING] process_request: 280ms  ← Regression detected!
```

**Cold Start Analysis:**
```
[TIMING] load_interface_cache: 8.2ms
[TIMING] load_interface_logging: 7.8ms
[TIMING] load_interface_http: 9.1ms
Total cold start overhead: 25.1ms
Target: < 30ms ✓
```

**Combined with DEBUG_MODE:**

```bash
# See flow AND timings
DEBUG_MODE=true DEBUG_TIMINGS=true

[DEBUG] Executing: cache.get
[TIMING] cache.get: 0.85ms
[DEBUG] Completed: cache.get
[DEBUG] Executing: http.post
[TIMING] http.post: 125.43ms
[DEBUG] Completed: http.post
```

**Performance Impact:**

- time.perf_counter() call: ~200ns
- Two calls per operation: ~400ns
- String formatting and logging: ~5μs
- **Total overhead: ~5-10μs per operation**
- Negligible compared to operation times (1000μs+)

**Rationale:**
DEBUG_TIMINGS enables data-driven performance optimization. The "measure, don't guess" principle (NM06-LESS-02) requires measurement tools. The ~10μs overhead is negligible compared to the insights gained.

---

## PART 3: COMBINED USAGE PATTERNS

### Pattern 1: Development Mode

```bash
# Full debugging during development
export DEBUG_MODE=true
export DEBUG_TIMINGS=true
export LOG_LEVEL=DEBUG

# Result: Complete visibility
[DEBUG] Executing: cache.get, kwargs_keys=['key']
[INFO] Cache get: user_123
[TIMING] cache.get: 0.85ms
[DEBUG] Completed: cache.get, result_type=dict
```

### Pattern 2: Production Diagnosis

```bash
# Minimal impact production debugging
export DEBUG_MODE=true
export DEBUG_TIMINGS=false  # Reduce log volume
export LOG_LEVEL=INFO

# Result: Flow visibility without timing overhead
[DEBUG] Executing: cache.get
[INFO] Cache get: user_123
[DEBUG] Completed: cache.get
```

### Pattern 3: Performance Investigation

```bash
# Focus on performance only
export DEBUG_MODE=false
export DEBUG_TIMINGS=true
export LOG_LEVEL=INFO

# Result: Clean timing data
[INFO] Cache get: user_123
[TIMING] cache.get: 0.85ms
[INFO] HTTP POST: /api/services
[TIMING] http.post: 125.43ms
```

### Pattern 4: Normal Production

```bash
# Minimal logging for normal operation
export DEBUG_MODE=false
export DEBUG_TIMINGS=false
export LOG_LEVEL=CRITICAL

# Result: Only critical errors logged
[CRITICAL] HTTP connection failed: timeout
```

---

## INTEGRATION NOTES

### Cross-Reference with Other Neural Maps

**NM01 (Architecture):**
- DEC-20, DEC-21 affect Lambda entry point (ARCH-06)
- Configuration changes impact initialization

**NM03 (Operations):**
- DEC-21 dramatically improves PATH-01 (cold start: -3000ms)
- DEC-22, DEC-23 provide visibility into FLOW-01, FLOW-02

**NM06 (Learned Experiences):**
- DEC-20, DEC-21 implement lessons from LESS-17
- DEC-22, DEC-23 implement "measure, don't guess" (LESS-02)
- BUG-04 led to DEC-21

**NM07 (Decision Logic):**
- DEC-22, DEC-23 support DT-07 (should I optimize?)
- Measurement enables data-driven decisions

---

## END NOTES

**Key Takeaways:**
1. LAMBDA_MODE provides extensible operational modes
2. SSM token-only reduces cold start by 92% (3,000ms)
3. DEBUG_MODE enables instant troubleshooting
4. DEBUG_TIMINGS enables performance optimization
5. All changes maintain security while improving performance

**File Statistics:**
- Total REF IDs: 4 operational decisions
- Total lines: ~450
- Priority: 2 CRITICAL, 2 HIGH
- Date: All decisions from 2025.10.20

**Impact Summary:**
- Cold start: -3,000ms (92% improvement)
- Configuration: Simplified from 13 SSM to 1 SSM
- Debugging: Instant enable/disable via environment
- Cost: Reduced SSM API calls, lower AWS costs

**Related Files:**
- NM04-INDEX-Decisions.md (Router to this file)
- NM04-ARCHITECTURE-Decisions.md (Foundation patterns)
- NM04-TECHNICAL-Decisions.md (Implementation choices)

# EOF

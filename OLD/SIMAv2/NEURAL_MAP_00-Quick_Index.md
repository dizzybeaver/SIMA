# NEURAL_MAP_00-Quick_Index.md
# Quick Index - Fast Keyword Triggers
# SIMA (Synthetic Integrated Memory Architecture) - Gateway Layer
# Version: 2.1.0 | Phase: 1 Foundation | Updated: 2025.10.21

---

## Purpose

This is the **Quick Index** - the fast entry point for ~90% of common queries. It provides:
- Instant keyword → file routing
- Quick lookup tables for common operations
- Decision quick-paths (IF/THEN trees)
- Auto-recall of frequently referenced patterns

**Think of this as gateway_wrappers.py for knowledge - fast, direct access.**

---

## SECTION 1: KEYWORD TRIGGERS

### Cache & Performance Keywords
```
"cache" → NM01-INT-01 (CACHE interface)
"cache_get" → NM01-INT-01 + NM00-TBL-C
"cache_set" → NM01-INT-01 + NM00-TBL-C
"TTL" → NM01-INT-01
"sentinel" → NM06-BUG-01 (Sentinel leak), NM06-BUG-02 (_CacheMiss) ✅ NEW
"_CacheMiss" → NM06-BUG-02 (_CacheMiss sentinel validation) ✅ NEW
"535ms" → NM06-BUG-01 (Sentinel leak penalty) ✅ NEW
"cold start" → NM03-PATH-01 + NM06-BUG-01 + NM06-LESS-10 ✅ UPDATED
"performance" → NM03-PATH-01 + NM04-DEC-04 + NM06-LESS-02 ✅ UPDATED
"optimization" → NM04-DEC-07 + NM06-LESS-02 ✅ UPDATED
"measure" → NM06-LESS-02 (Measure, don't guess) ✅ NEW
"monitoring" → NM06-LESS-10 (Cold start monitoring) ✅ NEW
"LMMS" → NM01-ARCH-07 (Memory management system)
"LIGS" → NM01-ARCH-07 (Lazy import system)
"LUGS" → NM01-ARCH-07 (Lazy unload system) + NM06-LESS-18 ✅ UPDATED
"ZAPH" → NM01-ARCH-07 (Fast path system)
"lazy loading" → NM01-ARCH-07
"memory management" → NM01-ARCH-07 + NM06-LESS-20 ✅ UPDATED
"fast path" → NM01-ARCH-07
"FTPMS" → NM01-ARCH-08 (Future: Free tier protection)
"OFB" → NM01-ARCH-08 (Future: Operation batching)
"MDOE" → NM01-ARCH-08 (Future: Metadata-driven)
```

### Metrics & Monitoring Keywords ✅ NEW
```
"metrics" → NM01-INT-04 + NM06-LESS-17 to LESS-21
"metric validation" → NM06-LESS-19 (Security validations)
"metric injection" → NM06-LESS-19 (Injection attack prevention)
"rate limiting" → NM06-LESS-21 (Rate limiting essential)
"memory limits" → NM06-LESS-20 (Memory limits prevent DoS)
"FIFO eviction" → NM06-LESS-20 (FIFO over LRU)
"sliding window" → NM06-LESS-21 (Sliding window rate limiter)
"DoS protection" → NM06-LESS-20, NM06-LESS-21
"unbounded growth" → NM06-LESS-20 (Memory limits)
"validate_metric_name" → NM06-LESS-19
"validate_dimension_value" → NM06-LESS-19
"validate_metric_value" → NM06-LESS-19
```

### Future Architecture Keywords
"FTPMS" → NM01-ARCH-08 (Future: Free tier protection)
"OFB" → NM01-ARCH-08 (Future: Operation batching)
"MDOE" → NM01-ARCH-08 (Future: Metadata-driven)
"future architectures" → NM01-ARCH-08

### Import & Architecture Keywords
```
"import" → NM02-RULE-01 + NM07-DT-01
"circular import" → NM05-AP-02 + NM06-BUG-02 (historical) ✅ UPDATED
"cross-interface" → NM02-RULE-01
"gateway" → NM01-ARCH-02 + NM04-DEC-01 + NM06-LESS-01 ✅ UPDATED
"SIMA" → NM01-ARCH-01 + NM04-DEC-01
"ISP" → NM01-ARCH-01
"interface" → NM01 (all INT references)
"router" → NM01-ARCH-03
"core" → NM01-ARCH-04
"gateway pattern" → NM06-LESS-01 (Gateway pattern prevents problems) ✅ NEW
"infrastructure" → NM06-LESS-03 (Infrastructure vs business logic) ✅ NEW
"base layer" → NM06-LESS-07 (Base layers have no dependencies) ✅ NEW
```

### Threading & Concurrency Keywords ✅ UPDATED
```
"threading" → NM04-DEC-04 + NM06-LESS-17
"locks" → NM04-DEC-04 + NM06-LESS-17
"threading locks" → NM06-LESS-17 (Unnecessary in Lambda)
"single-threaded" → NM04-DEC-04 + NM06-LESS-17
"thread-safe" → NM04-DEC-04 + NM06-LESS-17
"Lambda execution model" → NM06-LESS-17
"concurrency" → NM04-DEC-04 + NM06-LESS-17
"consistency" → NM06-LESS-04 (Consistency over cleverness) ✅ NEW
```

### SINGLETON & Lifecycle Keywords ✅ UPDATED
```
"SINGLETON" → NM01-INT-06 + NM06-LESS-18
"lifecycle management" → NM06-LESS-18
"get_X_manager" → NM06-LESS-18 (SINGLETON pattern)
"get_metrics_manager" → NM06-LESS-18 (Example pattern)
"memory tracking" → NM06-LESS-18 (SINGLETON enables)
```

### Error Handling Keywords
```
"error" → NM03-PATH-04
"exception" → NM07-DT-06
"try-except" → NM03-PATH-04
"graceful degradation" → NM06-LESS-05 (Graceful degradation) ✅ NEW
"error handling" → NM06-LESS-08 (Test failure paths) ✅ NEW
"cascading failure" → NM06-BUG-03 (Cascading interface failures) ✅ NEW
"failure path" → NM06-LESS-08 (Test failure paths) ✅ NEW
```

### Deployment & Operations Keywords
```
"deploy" → NM07-DT-12 + NM06-LESS-09 ✅ UPDATED
"deployment" → NM06-LESS-09 (Partial deployment danger) ✅ NEW
"partial deployment" → NM06-LESS-09 (Atomic deployment required) ✅ NEW
"atomic deployment" → NM06-LESS-09 ✅ NEW
"verification" → NM06-LESS-15 (File verification mandatory) ✅ NEW
"file verification" → NM06-LESS-15 (5-step verification protocol) ✅ NEW
"truncation" → NM06-LESS-15 + NM06-BUG-03 ✅ NEW
```

### Documentation & Learning Keywords
```
"document" → NM06-LESS-11 (Design decisions must be documented) ✅ NEW
"comments" → NM06-LESS-12 (Code comments vs external docs) ✅ NEW
"teachable" → NM06-LESS-13 (Architecture must be teachable) ✅ NEW
"neural maps" → NM06-LESS-11 + NM00 (this system) ✅ NEW
"evolution" → NM06-LESS-14 (Evolution is normal) ✅ NEW
"adaptation" → NM06-LESS-16 (Adaptation over rewriting) ✅ NEW
```

### Wisdom & Principles Keywords ✅ NEW
```
"architecture prevents" → NM06-WISD-01 (Architecture prevents problems)
"measure don't guess" → NM06-WISD-02 (Measure, don't guess)
"small costs early" → NM06-WISD-03 (Small costs early prevent large costs later)
"consistency cleverness" → NM06-WISD-04 (Consistency over cleverness)
"document everything" → NM06-WISD-05 (Document everything)
```

### Logging Keywords
```
"log" → NM01-INT-02
"log_info" → NM01-INT-02 + NM00-TBL-C
"log_error" → NM01-INT-02
"logging" → NM01-INT-02 + NM02-DEP-01
```

### Security Keywords ✅ UPDATED
```
"validate" → NM01-INT-03 + NM06-LESS-19
"sanitize" → NM01-INT-03 + NM06-BUG-01 ✅ UPDATED
"security" → NM01-INT-03 + NM06-LESS-19
"injection" → NM01-INT-03 + NM06-LESS-19
"injection attack" → NM06-LESS-19 (Metric name injection)
"path traversal" → NM06-LESS-19 (Dimension validation)
"NaN validation" → NM06-LESS-19 (Metric value validation)
"control characters" → NM06-LESS-19 (Dimension validation)
"SSRF" → NM01-INT-03
"sanitization" → NM06-BUG-01 (Sentinel sanitization at router layer) ✅ NEW
"CVE-SUGA-2025-001" → NM06-BUG-01 + NM06-LESS-19
```

### Configuration Keywords
```
"config" → NM01-INT-05
"parameter" → NM01-INT-05 + NM06-BUG-04 ✅ UPDATED
"SSM" → NM01-INT-05 + NM06-BUG-02 + NM06-BUG-04 ✅ UPDATED
"token" → NM06-BUG-02 + NM06-BUG-04 (Token-only config) ✅ NEW
"configuration" → NM06-BUG-04 (Configuration parameter mismatch) ✅ NEW
```

### Home Assistant Keywords
```
"Home Assistant" → NM01-ARCH-05
"HA" → NM01-ARCH-05
"webhook" → NM01-ARCH-05
"alexa" → NM01-ARCH-05 + NM06-BUG-03 ✅ UPDATED
```

### File Access Keywords
```
"GitHub" → NM00-TBL-E (File Access Methods)
"raw URL" → NM00-TBL-E
"project knowledge" → NM00-TBL-E (primary method)
"web_fetch" → NM00-TBL-E
"file access" → NM00-TBL-E
```

---

## SECTION 2: QUICK LOOKUP TABLES

### Table A: All 12 Interfaces

| Interface | Purpose | Gateway Function | Priority |
|-----------|---------|------------------|----------|
| CACHE | In-memory caching | `gateway.cache_get()`, `gateway.cache_set()` | 🔴 CRITICAL |
| LOGGING | Structured logging | `gateway.log_info()`, `gateway.log_error()` | 🔴 CRITICAL |
| SECURITY | Input validation | `gateway.validate_string()`, `gateway.sanitize_input()` | 🔴 CRITICAL |
| METRICS | Performance tracking | `gateway.track_metric()` | 🟡 HIGH |
| CONFIG | Configuration | `gateway.get_config()` | 🟡 HIGH |
| SINGLETON | Shared instances | `gateway.get_singleton()` | 🟡 HIGH |
| INITIALIZATION | Lazy loading | `gateway.ensure_initialized()` | 🟡 HIGH |
| HTTP_CLIENT | External HTTP | `gateway.http_get()`, `gateway.http_post()` | 🟡 HIGH |
| WEBSOCKET | WebSocket connections | `gateway.websocket_connect()` | 🟢 MEDIUM |
| CIRCUIT_BREAKER | Fault tolerance | `gateway.with_circuit_breaker()` | 🟢 MEDIUM |
| UTILITY | Helper functions | `gateway.format_response()` | 🟢 MEDIUM |
| DEBUG | Development tools | `gateway.debug_inspect()` | ⚪ LOW |

### Table B: Import Rules (Fast Reference)

| From | To | How | Example |
|------|-----|-----|---------|
| Router | Gateway | `import gateway` | `result = gateway.cache_get(key)` |
| Router | Core (same) | Direct import | `from cache_core import _execute_get_implementation` |
| Core | Gateway | `import gateway` | `gateway.log_info("message")` |
| Core | Core (different) | Via gateway | `gateway.cache_get(key)` NOT `import cache_core` |

### Table C: Common Operations

| I want to... | Use this... | Example |
|--------------|-------------|---------|
| Cache data | `gateway.cache_set()` | `gateway.cache_set("key", data, ttl=300)` |
| Get cached data | `gateway.cache_get()` | `result = gateway.cache_get("key")` |
| Log message | `gateway.log_info()` | `gateway.log_info("Processing request")` |
| Log error | `gateway.log_error()` | `gateway.log_error("Failed", exc_info=True)` |
| Validate string | `gateway.validate_string()` | `gateway.validate_string(user_input, max_length=100)` |
| Track metric | `gateway.track_metric()` | `gateway.track_metric("api_calls", 1)` |
| Get config | `gateway.get_config()` | `value = gateway.get_config("api_key")` |
| HTTP request | `gateway.http_get()` | `response = gateway.http_get(url, params)` |

### Table D: Dependency Layers

| Layer | Can Import From | Cannot Import From |
|-------|-----------------|---------------------|
| Layer 0 (LOGGING) | Nothing | Everything |
| Layer 1 (CACHE, SECURITY) | Layer 0 | Layer 1, 2, 3, 4 |
| Layer 2 (METRICS, CONFIG, SINGLETON, INIT) | Layers 0-1 | Layer 2, 3, 4 |
| Layer 3 (HTTP, WEBSOCKET) | Layers 0-2 | Layer 3, 4 |
| Layer 4 (CIRCUIT_BREAKER, UTILITY, DEBUG) | Layers 0-3 | Layer 4 |

### Table E: File Access Methods

| Method | When to Use | How to Use |
|--------|-------------|------------|
| 🔴 Project Knowledge | **PRIMARY** - Always try first | Use `project_knowledge_search` tool |
| 🟡 GitHub Raw URL | **SECONDARY** - If not in project | Ask user for raw URL, use `web_fetch` tool |
| 🟢 User Upload | **FALLBACK** - Neither works | Ask user to upload file directly |

---

## SECTION 3: DECISION QUICK-PATHS

### Quick Path 1: How Do I Import X?
```
IF importing from gateway:
  → Use: import gateway
  → Use: gateway.function_name()

ELSE IF in router importing from core (same interface):
  → Use: from core_file import function
  → Direct import is OK

ELSE IF in core importing from different core:
  → Use: import gateway
  → Use: gateway.function_name()
  → NEVER direct import across cores

ELSE IF in core importing from router:
  → STOP - This is FORBIDDEN
  → Routers import cores, not reverse
```

### Quick Path 2: Where Does This Function Go?
```
IF function validates/sanitizes/routes:
  → Router layer (interface_*.py)

ELSE IF function implements business logic:
  → Core layer (*_core.py)

ELSE IF function coordinates multiple interfaces:
  → Gateway (gateway.py or gateway_wrappers.py)

ELSE IF unsure:
  → Start in core, promote to router if routing needed
```

### Quick Path 3: How Do I Handle Errors?
```
IF error in router:
  → Log with gateway.log_error()
  → Sanitize error message
  → Return user-friendly response

ELSE IF error in core:
  → Raise exception with context
  → Let router layer catch and log
  → Don't suppress errors

ELSE IF error in gateway:
  → Log to CloudWatch
  → Return HTTP 500 with safe message
  → Never expose internal details
```

### Quick Path 4: Should I Cache This? ✅ UPDATED
```
IF data expensive to compute (>100ms):
  → YES - cache it
  → Use appropriate TTL
  → Remember: sentinel sanitization at router! (NM06-BUG-01)

ELSE IF data changes frequently (<1 minute):
  → NO - don't cache
  → Real-time data better

ELSE IF data rarely changes:
  → YES - cache with long TTL
  → Consider eternal cache
```

### Quick Path 5: Deployment Verification ✅ NEW
```
IF deploying ANY file:
  → MUST verify 5 steps (NM06-LESS-15):
    1. Compare line counts (deployed vs source)
    2. Check file has # EOF marker
    3. Verify key functions present (search for function names)
    4. Test imports work (Lambda test execution)
    5. Monitor first real execution (CloudWatch logs)

ELSE IF skipping verification:
  → STOP - High risk of cascading failure (NM06-BUG-03)
  → Always verify, no exceptions
```

### Quick Path 6: Should I Use Threading Locks? ✅ NEW
```
IF in Lambda:
  → NO - NEVER use threading locks
  → Reason: Lambda is single-threaded
  → Each container handles ONE request at a time
  → No thread-level concurrency within container

ELSE IF you think you need concurrency:
  → STOP - Rethink the design
  → Lambda runtime handles container-level concurrency
  → Single-threaded execution is guaranteed

ELSE IF coming from multi-threaded server code:
  → Remove all threading.Lock(), threading.RLock(), etc.
  → Direct access is safe in Lambda
  → Performance: +50ns per operation, -500 bytes memory

REF: NM04-DEC-04, NM06-LESS-17, AP-08
```

### Quick Path 7: How Do I Access Manager Singletons? ✅ NEW
```
IF accessing metrics/cache/logging manager:
  → Use get_X_manager() pattern (SINGLETON interface)
  → DON'T: from X_core import _MANAGER

Pattern:
def get_X_manager():
    try:
        # Preferred: SINGLETON registry
        from gateway import execute_operation, GatewayInterface
        return execute_operation(
            GatewayInterface.SINGLETON,
            'get',
            resource_type='X',
            factory_func=lambda: __import__('X_core')._MANAGER
        )
    except:
        # Fallback: Direct import
        from X_core import _MANAGER
        return _MANAGER

Benefits:
- Memory tracking by singleton_core
- Lifecycle control by LUGS
- Lazy loading
- Graceful degradation

REF: NM06-LESS-18, INT-06
```

### Quick Path 8: Do I Need to Validate Metric Names? ✅ NEW
```
IF accepting user input for metric names:
  → YES - ALWAYS validate

Validation:
from gateway import validate_metric_name
validate_metric_name(name)

Checks:
- Length: 1-200 characters
- Characters: [a-zA-Z0-9_.-] only
- No path separators (/, \)
- No control characters
- Cannot start/end with . or -

Prevents:
- Path traversal: ../../etc/passwd
- Injection: \x00\x01\x02
- Memory exhaustion: "x" * 10000

Overhead: ~5μs per call
Trade-off: Security > Performance

REF: NM06-LESS-19, Finding 5.1
```

### Quick Path 9: Should Metrics Have Memory Limits? ✅ NEW
```
IF storing metrics in lists:
  → YES - ALWAYS use memory limits

Method: FIFO eviction
MAX_VALUES = int(os.getenv('MAX_VALUES_PER_METRIC', '1000'))

def _apply_memory_limit(values: List[float]):
    while len(values) > MAX_VALUES:
        values.pop(0)  # Remove oldest (FIFO)

Usage:
self._metrics[key].append(value)
self._apply_memory_limit(self._metrics[key])

Why FIFO not LRU:
- Simpler (no access tracking)
- Faster (O(1) operations)
- Appropriate for time-series data
- Predictable behavior

Prevents:
- Unbounded memory growth
- OOM crashes
- DoS attacks

REF: NM06-LESS-20, Finding 5.2
```

### Quick Path 10: Do Metrics Need Rate Limiting? ✅ NEW
```
IF implementing metrics interface:
  → YES - rate limiting is ESSENTIAL

Method: Sliding window
RATE_LIMIT = int(os.getenv('RATE_LIMIT_CALLS_PER_SECOND', '1000'))

from collections import deque
rate_limiter = deque(maxlen=RATE_LIMIT)
window_ms = 1000  # 1 second

def _check_rate_limit():
    now = time.time() * 1000
    # Clean old timestamps
    while rate_limiter and (now - rate_limiter[0]) > window_ms:
        rate_limiter.popleft()
    # Check limit
    if len(rate_limiter) >= RATE_LIMIT:
        return False  # Rate limited
    rate_limiter.append(now)
    return True

Behavior:
- Calls 1-1000: ✅ Accepted
- Call 1001: ❌ Dropped (silent failure)
- After 1 second: Window slides, new 1000 allowed

Prevents:
- DoS attacks
- Spam
- Lambda timeout

Overhead: ~1μs per call
Trade-off: Essential for production

REF: NM06-LESS-21, Finding 5.4
```

---

## SECTION 4: AUTO-RECALL (Most Frequently Referenced)

### 🔴 CRITICAL: Gateway Pattern Principles
```
1. All cross-interface calls MUST go through gateway
   WHY: Prevents circular imports, maintains ISP topology
   REF: NM02-RULE-01, NM06-LESS-01

2. Router layer sanitizes sentinels before returning
   WHY: 535ms penalty if sentinels leak to user code
   REF: NM06-BUG-01

3. No threading primitives (locks, semaphores, etc.)
   WHY: Lambda is single-threaded, locks unnecessary and harmful
   REF: NM04-DEC-04, NM06-LESS-17

4. Base layers (LOGGING) have zero dependencies
   WHY: Prevents dependency deadlocks
   REF: NM02-DEP-01, NM06-LESS-07
```

### 🔴 CRITICAL: METRICS Security & Performance ✅ NEW
```
1. ALWAYS validate metric names, dimension values, and numeric values
   WHY: Prevents injection attacks, memory exhaustion, data corruption
   REF: NM06-LESS-19, Finding 5.1, 5.3, 5.5

2. ALWAYS use memory limits with FIFO eviction
   WHY: Prevents unbounded growth, OOM crashes, DoS attacks
   REF: NM06-LESS-20, Finding 5.2

3. ALWAYS use rate limiting (sliding window, 1000 calls/sec default)
   WHY: Prevents DoS attacks, spam, Lambda timeout
   REF: NM06-LESS-21, Finding 5.4

4. NEVER use threading locks in Lambda
   WHY: Single-threaded execution, locks unnecessary (+50ns overhead)
   REF: NM06-LESS-17, DEC-04, AP-08

5. ALWAYS use SINGLETON pattern for manager access
   WHY: Enables memory tracking, lifecycle control, lazy loading
   REF: NM06-LESS-18, INT-06
```

### 🔴 CRITICAL: File Deployment Rules ✅ NEW
```
1. NEVER deploy without verification (5-step protocol)
   WHY: Incomplete files cause cascading failures
   REF: NM06-LESS-15, NM06-BUG-03

2. ALWAYS check for # EOF marker
   WHY: Indicates file completeness
   REF: NM06-LESS-15

3. NEVER partial deployment of related files
   WHY: Interface mismatches break system
   REF: NM06-LESS-09

4. ALWAYS monitor first execution after deploy
   WHY: Catches truncation/import errors early
   REF: NM06-LESS-10
```

### 🟡 HIGH: Common Patterns
```
1. Measure before optimizing
   WHY: Guessing wastes time, measurement finds real issues
   REF: NM06-LESS-02, NM06-WISD-02

2. Infrastructure concerns at router layer
   WHY: Keep business logic clean
   REF: NM06-LESS-03

3. Graceful degradation over hard failures
   WHY: Partial functionality better than none
   REF: NM06-LESS-05

4. Pay small costs early to avoid large costs later
   WHY: 0.5ms prevention vs 535ms bug fix
   REF: NM06-LESS-06, NM06-WISD-03
```

### 🟡 HIGH: METRICS Implementation Patterns ✅ NEW
```
Pattern 1: Three-Layer Security Validation
from gateway import validate_metric_name, validate_dimension_value, validate_metric_value
validate_metric_name(name)           # Length, chars, path traversal
validate_dimension_value(value)      # Printable, no control chars
validate_metric_value(numeric_value) # No NaN, no Infinity

Pattern 2: SINGLETON Manager Access
def get_metrics_manager():
    try:
        from gateway import execute_operation, GatewayInterface
        return execute_operation(SINGLETON, 'get', 
            resource_type='metrics',
            factory_func=lambda: __import__('metrics_core')._MANAGER)
    except:
        from metrics_core import _MANAGER
        return _MANAGER

Pattern 3: FIFO Memory Limits
MAX_VALUES = 1000
def _apply_memory_limit(values: List[float]):
    while len(values) > MAX_VALUES:
        values.pop(0)  # Remove oldest

Pattern 4: Sliding Window Rate Limiter
rate_limiter = deque(maxlen=1000)
window_ms = 1000
def _check_rate_limit():
    now = time.time() * 1000
    while rate_limiter and (now - rate_limiter[0]) > window_ms:
        rate_limiter.popleft()
    if len(rate_limiter) >= 1000:
        return False
    rate_limiter.append(now)
    return True

Pattern 5: Lambda Threading Model
NO locks needed:
- ONE container per concurrent request
- ONE request at a time per container
- NO thread-level concurrency within container
- Container-level concurrency = Lambda runtime's job

REF: NM06-LESS-17 to LESS-21
```

### 🟢 MEDIUM: Wisdom Principles ✅ NEW
```
1. Architecture prevents problems
   WHY: Gateway pattern stopped circular imports
   REF: NM06-WISD-01

2. Consistency over cleverness
   WHY: Predictable code easier to maintain
   REF: NM06-WISD-04, NM06-LESS-04

3. Document design decisions
   WHY: Prevents re-learning and mistakes
   REF: NM06-WISD-05, NM06-LESS-11

4. Evolution is normal, not failure
   WHY: SIMA itself evolved from problems
   REF: NM06-LESS-14, NM06-LESS-16
```

---

## SECTION 5: ANTI-PATTERN QUICK WARNINGS

### ❌ NEVER Do These
```
1. ❌ Import core directly from different core
   ✅ INSTEAD: Use gateway.function_name()
   REF: NM05-AP-01

2. ❌ Use threading.Lock or similar in Lambda
   ✅ INSTEAD: Rely on single-threaded execution
   REF: NM05-AP-04, NM04-DEC-04, NM06-LESS-17

3. ❌ Return sentinel objects to users
   ✅ INSTEAD: Sanitize at router layer
   REF: NM05-AP-XX (TBD), NM06-BUG-01

4. ❌ Deploy without verification
   ✅ INSTEAD: Follow 5-step verification protocol
   REF: NM06-BUG-03, NM06-LESS-15

5. ❌ Guess at performance problems
   ✅ INSTEAD: Measure with timing logs
   REF: NM06-LESS-02

6. ❌ Partial deployment of interface changes
   ✅ INSTEAD: Atomic deployment of all related files
   REF: NM06-LESS-09

7. ❌ Assume _CacheMiss is None
   ✅ INSTEAD: Explicitly check type(cached).__name__
   REF: NM06-BUG-02

8. ❌ Accept unvalidated metric names/values ✅ NEW
   ✅ INSTEAD: Always validate via gateway
   REF: NM06-LESS-19, Finding 5.1, 5.3, 5.5

9. ❌ Store metrics in unbounded lists ✅ NEW
   ✅ INSTEAD: Use FIFO eviction at MAX_VALUES
   REF: NM06-LESS-20, Finding 5.2

10. ❌ No rate limiting on metrics operations ✅ NEW
    ✅ INSTEAD: Sliding window rate limiter (1000/sec)
    REF: NM06-LESS-21, Finding 5.4

11. ❌ Direct import of _MANAGER from core ✅ NEW
    ✅ INSTEAD: Use get_X_manager() via SINGLETON
    REF: NM06-LESS-18, INT-06
```

---

## SECTION 6: ROUTING TO LEARNED EXPERIENCES (NM06) ✅ UPDATED

### Bug Keywords → Files
```
"sentinel" → NM06-INDEX → NM06-BUGS-Critical.md (BUG-01)
"_CacheMiss" → NM06-INDEX → NM06-BUGS-Critical.md (BUG-02)
"cascading failure" → NM06-INDEX → NM06-BUGS-Critical.md (BUG-03)
"configuration mismatch" → NM06-INDEX → NM06-BUGS-Critical.md (BUG-04)
"535ms" → NM06-INDEX → NM06-BUGS-Critical.md (BUG-01)
```

### Lesson Keywords → Files
```
"gateway pattern" → NM06-INDEX → NM06-LESSONS-Core.md (LESS-01)
"measure don't guess" → NM06-INDEX → NM06-LESSONS-Core.md (LESS-02)
"infrastructure business" → NM06-INDEX → NM06-LESSONS-Core.md (LESS-03)
"consistency cleverness" → NM06-INDEX → NM06-LESSONS-Core.md (LESS-04)
"graceful degradation" → NM06-INDEX → NM06-LESSONS-Core.md (LESS-05)
"small costs early" → NM06-INDEX → NM06-LESSONS-Core.md (LESS-06)
"base layer dependencies" → NM06-INDEX → NM06-LESSONS-Core.md (LESS-07)
"test failure paths" → NM06-INDEX → NM06-LESSONS-Core.md (LESS-08)

"partial deployment" → NM06-INDEX → NM06-LESSONS-Deployment.md (LESS-09)
"cold start monitoring" → NM06-INDEX → NM06-LESSONS-Deployment.md (LESS-10)

"document decisions" → NM06-INDEX → NM06-LESSONS-Documentation.md (LESS-11)
"code comments" → NM06-INDEX → NM06-LESSONS-Documentation.md (LESS-12)
"architecture teachable" → NM06-INDEX → NM06-LESSONS-Documentation.md (LESS-13)

"evolution normal" → NM06-INDEX → NM06-LESSONS-2025.10.20.md (LESS-14)
"file verification" → NM06-INDEX → NM06-LESSONS-2025.10.20.md (LESS-15)
"adaptation rewriting" → NM06-INDEX → NM06-LESSONS-2025.10.20.md (LESS-16)

"threading locks" → NM06-INDEX → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-17) ✅ NEW
"SINGLETON pattern" → NM06-INDEX → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-18) ✅ NEW
"metric validation" → NM06-INDEX → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-19) ✅ NEW
"memory limits" → NM06-INDEX → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-20) ✅ NEW
"rate limiting" → NM06-INDEX → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-21) ✅ NEW
```

### Wisdom Keywords → Files
```
"architecture prevents" → NM06-INDEX → NM06-WISDOM-Synthesis.md (WISD-01)
"measure not guess" → NM06-INDEX → NM06-WISDOM-Synthesis.md (WISD-02)
"costs early late" → NM06-INDEX → NM06-WISDOM-Synthesis.md (WISD-03)
"consistency not cleverness" → NM06-INDEX → NM06-WISDOM-Synthesis.md (WISD-04)
"document all" → NM06-INDEX → NM06-WISDOM-Synthesis.md (WISD-05)
```

---

## SECTION 7: COMMON QUESTIONS → INSTANT ANSWERS

### "Why no threading locks?" ✅ UPDATED
**ANSWER:** Lambda execution is single-threaded per container. Each container handles ONE request at a time. Threading primitives like locks are unnecessary and add overhead (~50ns per operation, ~500 bytes memory). Single-threaded execution guarantees atomicity without synchronization.
**DETAIL:** NM04-DEC-04, NM06-LESS-17

### "How do I import across interfaces?"
**ANSWER:** Always use `import gateway` and call `gateway.function_name()`. Never import core files directly across interfaces. This maintains ISP topology and prevents circular imports.
**DETAIL:** NM02-RULE-01, NM07-DT-01

### "What was the sentinel bug?"
**ANSWER:** Sentinel object leaked to user code, causing 535ms cold start penalty. User code patterns like `if cached is not None` didn't work because sentinel is not None. Fixed by sanitizing sentinels at router layer before returning to users.
**DETAIL:** NM06-BUG-01

### "How do I verify deployment?" ✅ NEW
**ANSWER:** Follow 5-step protocol: (1) Compare line counts, (2) Check # EOF marker, (3) Verify key functions present, (4) Test imports, (5) Monitor first execution. Never skip verification - incomplete files cause cascading failures.
**DETAIL:** NM06-LESS-15, NM06-BUG-03

### "Why measure instead of guess?" ✅ NEW
**ANSWER:** Measurement finds actual problems in minutes. Guessing can waste hours on wrong optimization. Example: Measured 535ms penalty, found sentinel bug in 15 minutes. Would have guessed wrong problem.
**DETAIL:** NM06-LESS-02, NM06-WISD-02

### "What is SIMA architecture?" ✅ NEW
**ANSWER:** Synthetic Integrated Memory Architecture - knowledge management using Gateway → Interface → Implementation pattern, same as Lambda code. Small files (200-600 lines), dispatch tables, REF IDs. Makes accessing memory fast, scalable, and familiar.
**DETAIL:** NM01-ARCH-01, NM06-LESS-14, NM06-LESS-16

### "Do I need to validate metric names?" ✅ NEW
**ANSWER:** YES - ALWAYS. Call `validate_metric_name(name)` via gateway. Validates length (1-200 chars), characters ([a-zA-Z0-9_.-]), no path separators, no control characters. Prevents path traversal (../../etc/passwd), injection (\x00), memory exhaustion ("x"*10000). Overhead: ~5μs. Security > Performance.
**DETAIL:** NM06-LESS-19, Finding 5.1

### "How do I access the metrics manager?" ✅ NEW
**ANSWER:** Use `get_metrics_manager()` pattern via SINGLETON interface. DON'T use `from metrics_core import _MANAGER`. SINGLETON enables memory tracking, lifecycle control (LUGS), lazy loading, and graceful degradation. Pattern works for all managers (cache, logging, metrics, etc.).
**DETAIL:** NM06-LESS-18, INT-06

### "Should metrics have memory limits?" ✅ NEW
**ANSWER:** YES - ALWAYS use FIFO eviction. Unbounded lists cause OOM crashes and DoS attacks. Default: 1000 values per metric key. FIFO is simpler, faster (O(1)), and appropriate for time-series data. Prevents unbounded memory growth.
**DETAIL:** NM06-LESS-20, Finding 5.2

### "Do metrics need rate limiting?" ✅ NEW
**ANSWER:** YES - ESSENTIAL for production. Use sliding window rate limiter (default: 1000 calls/sec). Prevents DoS attacks, spam, Lambda timeout. Silent failures (metrics don't crash app). Overhead: ~1μs per call. Monitor rate_limited_count for attacks.
**DETAIL:** NM06-LESS-21, Finding 5.4

---

## SECTION 8: PRIORITY REFERENCES ✅ UPDATED

### 🔴 CRITICAL References (Learn These First)
```
NM01-ARCH-01: Gateway Trinity
NM01-ARCH-02: Gateway execution engine
NM02-RULE-01: Cross-interface via gateway only
NM04-DEC-01: SIMA pattern choice
NM04-DEC-04: No threading locks
NM06-BUG-01: Sentinel leak (535ms penalty)
NM06-BUG-02: _CacheMiss sentinel validation
NM06-BUG-03: Cascading interface failures
NM06-LESS-01: Gateway pattern prevents problems
NM06-LESS-15: File verification is mandatory
NM06-LESS-17: Threading locks unnecessary in Lambda ✅ NEW
NM06-LESS-19: Security validations prevent injection ✅ NEW
NM06-LESS-20: Memory limits prevent DoS ✅ NEW
NM06-LESS-21: Rate limiting is essential ✅ NEW
```

### 🟡 HIGH References (Reference Frequently)
```
NM01-INT-01 through NM01-INT-08: Core interfaces
NM02-DEP-01 through NM02-DEP-05: Dependency layers
NM03-PATH-01: Cold start sequence
NM04-DEC-02: Gateway centralization
NM04-DEC-03: Flat file structure
NM06-LESS-02: Measure, don't guess
NM06-LESS-03: Infrastructure vs business logic
NM06-LESS-09: Partial deployment danger
NM06-LESS-18: SINGLETON pattern for lifecycle ✅ NEW
NM07-DT-01: How to import X
```

### 🟢 MEDIUM References (As Needed)
```
NM05-AP-01 through NM05-AP-05: Common anti-patterns
NM06-LESS-05: Graceful degradation
NM06-LESS-06: Pay small costs early
NM06-WISD-01 through NM06-WISD-05: Wisdom synthesis
NM07-DT-04 through NM07-DT-13: Decision trees
```

---

## SECTION 9: FILE MAINTENANCE

### When to Update This File
- New keyword pattern identified → Add to Section 1
- New common operation → Add to Table C
- New decision pattern → Add to Section 3
- New anti-pattern → Add to Section 5
- New frequently asked question → Add to Section 7
- **New NM06 content added → Update Section 6** ✅
- **New METRICS patterns → Update Section 4** ✅

### When to Use This vs Master Index
```
Use Quick Index (THIS FILE):
- Fast keyword lookup
- Common operations
- Quick decisions
- Frequently asked questions
- 90% of queries

Use Master Index:
- Complete file inventory
- All REF IDs
- Cross-reference mapping
- Priority breakdown
- 10% of queries (deep dives)
```

---

## END NOTES

This Quick Index provides instant context recall and fast lookup for common queries. It's designed to minimize search overhead and provide immediate answers to ~90% of questions.

For deeper understanding, refer to detailed neural map files listed in NM00-Master-Index.md.

**Remember:** This is synthetic working memory - knowledge + logic + relationships, not just facts.

**GitHub Access:** Claude can fetch files from GitHub when provided with raw URLs, but should always search project knowledge first.

---

**Version:** 2.1.0  
**Updated:** 2025-10-21  
**New Content:** METRICS Phase 1 integration (LESS-17 to LESS-21)  
**Total REF-IDs:** 21 lessons, 4 bugs, 5 wisdom

# EOF

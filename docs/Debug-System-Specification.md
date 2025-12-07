# Debug-System-Specification.md

**Version:** 1.0.0  
**Date:** 2025-12-07  
**Purpose:** Universal debug infrastructure specification  
**Category:** Specifications  
**Project:** LEE (Lambda Execution Engine)

---

## PURPOSE

Define universal debug infrastructure for hierarchical debug control with master switches and scope-specific flags.

---

## OVERVIEW

### Problem Solved

**Before:**
- Debug checks scattered throughout codebase
- Repeated environment variable lookups
- 5-10 lines of boilerplate per debug statement
- No centralized control mechanism
- Hard to enable/disable debug for specific components

**After:**
- Single-line debug calls
- Hierarchical control (master + scope flags)
- Centralized debug logic
- Granular component control
- Minimal overhead when disabled

---

## ARCHITECTURE

### Three-Layer Control

```
DEBUG_MODE (master switch)
    ↓
{SCOPE}_DEBUG_MODE (scope-specific debug)
{SCOPE}_DEBUG_TIMING (scope-specific timing)
```

### Logic Flow

1. If `DEBUG_MODE=false` → All debug calls no-op (instant return)
2. If `DEBUG_MODE=true` → Check scope-specific flags
3. If scope debug enabled → Log message
4. If scope timing enabled → Include timing information

### Implementation Layers

**Files:**
- `debug_config.py` (40 lines) - Debug state holder
- `debug_core.py` (120 lines) - Core debug logic
- `interface_debug.py` (25 lines) - Debug interface layer

**Integration:**
- `lambda_function.py` - Initialize debug in preload
- `gateway.py` - Export `debug_log()` function
- All modules - Use `debug_log()` calls

---

## DEBUG SCOPES

### LEE Interface Scopes

```
ALEXA          - Alexa Smart Home integration
HA             - Home Assistant general
DEVICES        - HA device operations
CACHE          - Cache operations
HTTP           - HTTP client requests
CONFIG         - Configuration management
SECURITY       - Security operations
METRICS        - Metrics collection
CIRCUIT_BREAKER - Circuit breaker operations
SINGLETON      - Singleton management
GATEWAY        - Gateway routing
INIT           - Initialization
WEBSOCKET      - WebSocket operations
LOGGING        - Logging framework (internal)
```

### Scope Naming

**Pattern:** `{COMPONENT}_{TYPE}`

**Types:**
- `DEBUG_MODE` - Enable debug logging
- `DEBUG_TIMING` - Enable timing measurements

**Examples:**
- `ALEXA_DEBUG_MODE=true`
- `CACHE_DEBUG_TIMING=true`

---

## ENVIRONMENT VARIABLES

### Master Control

```bash
DEBUG_MODE=true|false          # Master switch (required for any output)
```

**Default:** `false`  
**Effect:** When `false`, all debug calls instant return

### Scope-Specific Flags

**Pattern:**
```bash
{SCOPE}_DEBUG_MODE=true|false    # Enable scope debug logs
{SCOPE}_DEBUG_TIMING=true|false  # Include timing in scope logs
```

**Examples:**
```bash
ALEXA_DEBUG_MODE=true          # Show Alexa debug logs
ALEXA_DEBUG_TIMING=true        # Include timing in Alexa logs

HA_DEBUG_MODE=true             # Show HA debug logs
HA_DEBUG_TIMING=false          # No timing in HA logs

CACHE_DEBUG_MODE=true          # Show cache debug logs
CACHE_DEBUG_TIMING=true        # Include cache timing
```

**Default Behavior:**
- Unset variables default to `false`
- Initialization happens during lambda preload
- All scopes configured once at cold start

---

## USAGE PATTERNS

### Basic Debug Log

**Before (verbose):**
```python
DEBUG_MODE = os.environ.get('DEBUG_MODE', 'false').lower() == 'true'

if DEBUG_MODE:
    log_debug(f"[{correlation_id}] [ALEXA-DEBUG] Starting enrichment (entity_id={entity_id})")
```

**After (one line):**
```python
debug_log(corr_id, "ALEXA", "Starting enrichment", entity_id=entity_id)
```

### Debug with Timing

**Before (verbose):**
```python
DEBUG_MODE = os.environ.get('DEBUG_MODE', 'false').lower() == 'true'
DEBUG_TIMINGS = os.environ.get('DEBUG_TIMINGS', 'false').lower() == 'true'

if DEBUG_TIMINGS:
    start_time = time.perf_counter()

# ... operation ...

if DEBUG_TIMINGS:
    duration_ms = (time.perf_counter() - start_time) * 1000
    log_info(f"[{correlation_id}] [ALEXA-TIMING] enrichment: {duration_ms:.2f}ms")
```

**After (one line with context manager):**
```python
with debug_log(corr_id, "ALEXA", "enrichment", timing=True):
    # ... operation ...
```

### Line Reduction

**Impact:** 10+ lines → 1 line per debug statement

**Example:**
- 500-line file with 20 debug points
- Before: 700+ lines (with debug code)
- After: 520 lines (20 one-liners)
- Reduction: 180 lines (26%)

---

## OUTPUT FORMAT

### Debug Log Format

**Standard:**
```
[{correlation_id}] [{SCOPE}-DEBUG] {message}
```

**Example:**
```
[7167393a-ceca] [ALEXA-DEBUG] Directive received (namespace=Alexa.PowerController, name=TurnOn)
```

### Debug with Context

**Format:**
```
[{correlation_id}] [{SCOPE}-DEBUG] {message} ({key1}={value1}, {key2}={value2})
```

**Example:**
```
[7167393a-ceca] [HA-DEBUG] Fetching state (entity_id=light.bedroom, use_cache=true)
```

### Timing Log Format

**Format:**
```
[{correlation_id}] [{SCOPE}-TIMING] {operation}: {duration}ms
```

**Example:**
```
[7167393a-ceca] [ALEXA-TIMING] state_enrichment: 78.45ms
```

### Combined Debug + Timing

**Example:**
```
[7167393a-ceca] [CACHE-DEBUG] Cache miss (key=ha_state_light.bedroom)
[7167393a-ceca] [CACHE-TIMING] cache_get: 0.85ms
```

---

## IMPLEMENTATION DETAILS

### debug_config.py

**Purpose:** Hold debug state configuration

**Contents:**
- Master debug flag
- Per-scope debug flags
- Per-scope timing flags
- Initialization function

**Size:** ~40 lines

### debug_core.py

**Purpose:** Core debug logic implementation

**Functions:**
- `debug_log()` - Main debug logging
- `debug_timing()` - Timing context manager
- Helper functions

**Size:** ~120 lines

### interface_debug.py

**Purpose:** Debug interface layer

**Function:**
- `execute_debug_operation()` - Route debug calls

**Size:** ~25 lines

---

## PERFORMANCE

### Overhead When Disabled

**Master switch off:**
- Cost: ~1μs per call (instant return)
- No environment lookups
- No string formatting
- No logging calls

**Impact:** Negligible (<0.1% overhead)

### Overhead When Enabled

**Per debug log:**
- Scope check: ~5μs
- String formatting: ~50μs
- CloudWatch log: ~500μs
- **Total:** ~555μs

**Timing measurement:**
- Start/stop: ~10μs
- Formatting: ~50μs
- **Total:** ~60μs (plus operation time)

**Impact:** Acceptable for debugging

---

## CONFIGURATION EXAMPLES

### Production (Debug Off)

```bash
DEBUG_MODE=false
```

**Result:** All debug calls no-op

### Development (All Debug On)

```bash
DEBUG_MODE=true
ALEXA_DEBUG_MODE=true
ALEXA_DEBUG_TIMING=true
HA_DEBUG_MODE=true
HA_DEBUG_TIMING=true
DEVICES_DEBUG_MODE=true
DEVICES_DEBUG_TIMING=true
CACHE_DEBUG_MODE=true
CACHE_DEBUG_TIMING=true
```

**Result:** Full debug output with timing

### Targeted Debugging (Cache Only)

```bash
DEBUG_MODE=true
CACHE_DEBUG_MODE=true
CACHE_DEBUG_TIMING=true
```

**Result:** Only cache debug output

### Performance Profiling (Timing Only)

```bash
DEBUG_MODE=true
ALEXA_DEBUG_TIMING=true
HA_DEBUG_TIMING=true
DEVICES_DEBUG_TIMING=true
CACHE_DEBUG_TIMING=true
```

**Result:** Timing logs without verbose debug

---

## MIGRATION GUIDE

### Converting Existing Code

**Step 1: Identify Debug Code**
```python
# Old pattern
DEBUG_MODE = os.environ.get('DEBUG_MODE', 'false').lower() == 'true'
if DEBUG_MODE:
    log_debug(f"[{corr_id}] [ALEXA-DEBUG] Message here")
```

**Step 2: Replace with debug_log**
```python
# New pattern
debug_log(corr_id, "ALEXA", "Message here")
```

**Step 3: Convert Timing Code**
```python
# Old pattern
if DEBUG_TIMINGS:
    start = time.perf_counter()
# ... operation ...
if DEBUG_TIMINGS:
    duration_ms = (time.perf_counter() - start) * 1000
    log_info(f"[{corr_id}] [ALEXA-TIMING] operation: {duration_ms:.2f}ms")

# New pattern
with debug_log(corr_id, "ALEXA", "operation", timing=True):
    # ... operation ...
```

**Step 4: Remove Environment Lookups**
```python
# Remove these lines
DEBUG_MODE = os.environ.get('DEBUG_MODE', 'false').lower() == 'true'
DEBUG_TIMINGS = os.environ.get('DEBUG_TIMINGS', 'false').lower() == 'true'
```

---

## TESTING

### Test Scenarios

**Test 1: Master Switch Off**
```bash
DEBUG_MODE=false
ALEXA_DEBUG_MODE=true  # Ignored
```
**Expected:** No debug output

**Test 2: Master On, Scope Off**
```bash
DEBUG_MODE=true
ALEXA_DEBUG_MODE=false
```
**Expected:** No Alexa debug output

**Test 3: Both On**
```bash
DEBUG_MODE=true
ALEXA_DEBUG_MODE=true
```
**Expected:** Alexa debug output

**Test 4: Timing Only**
```bash
DEBUG_MODE=true
ALEXA_DEBUG_MODE=false
ALEXA_DEBUG_TIMING=true
```
**Expected:** Alexa timing output only

---

## BENEFITS

### Code Quality

**Reduction:**
- 26% file size reduction (typical)
- 10+ lines → 1 line per debug point
- Cleaner, more readable code

**Maintainability:**
- Centralized debug logic
- Consistent debug format
- Easy to add new scopes

### Operational

**Control:**
- Granular component debugging
- Enable/disable without deployment
- Production-safe operation

**Performance:**
- Minimal overhead when disabled
- Acceptable overhead when enabled
- Fast environment variable checks

---

## RELATED

### Documentation

- File-Standards.md - File size management
- Artifact-Standards.md - Code standards
- Version-Standards.md - Version format

### Implementation

- debug_config.py - Configuration holder
- debug_core.py - Core logic
- interface_debug.py - Interface layer
- gateway.py - Debug exports

---

## VERSION HISTORY

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | 2025-12-07 | Initial specification |

---

**END OF FILE**

**Summary:** Hierarchical debug system with master + scope flags, single-line calls, minimal overhead, production-safe operation.

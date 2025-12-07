# Universal Debug System & File Standards Overhaul

**Project:** LEE (Lambda Execution Engine)  
**Type:** Architecture Refactoring  
**Priority:** High  
**Effort:** 2-3 days  
**Status:** Design Phase

---

## Executive Summary

Comprehensive overhaul of LEE's debug infrastructure and file organization standards to:
1. Centralize debug/timing logic into single-line calls
2. Implement hierarchical debug control (master + scope-specific flags)
3. Enforce 300-350 line file size limits to prevent truncation
4. Reduce code verbosity by externalizing documentation
5. Standardize version format and file headers

**Impact:** Cleaner codebase, granular debug control, Claude-friendly file sizes, better maintainability.

---

## Problem Statement

### Current Issues

**Debug Code Proliferation:**
- Debug checks scattered throughout codebase
- Repeated environment variable lookups
- 5-10 lines of boilerplate per debug statement
- No centralized control mechanism
- Logging framework's debug noise pollutes CloudWatch

**File Size Problems:**
- Files approaching/exceeding 400 lines get truncated (22% loss minimum)
- Verbose comments consume line count
- Changelogs in headers add 20-50 lines
- Version numbers are arbitrary and unmaintainable

**Documentation Issues:**
- Code files mix implementation with documentation
- Function descriptions buried in code
- Hard to find usage examples
- Comments become outdated

---

## Solution Design

### Part 1: Universal Debug Infrastructure

#### Architecture

**Three-Layer Control:**
```
DEBUG_MODE (master switch)
    ↓
{SCOPE}_DEBUG_MODE (scope-specific debug)
{SCOPE}_DEBUG_TIMING (scope-specific timing)
```

**Logic Flow:**
1. If `DEBUG_MODE=false` → All debug calls no-op (instant return)
2. If `DEBUG_MODE=true` → Check scope-specific flags
3. If scope debug enabled → Log message
4. If scope timing enabled → Include timing information

#### Implementation Files

**New Files:**
- `debug_config.py` (40 lines) - Debug state holder
- `debug_core.py` (120 lines) - Core debug logic
- `interface_debug.py` (25 lines) - Debug interface layer

**Modified Files:**
- `lambda_function.py` - Add debug initialization to preload
- `gateway.py` - Export `debug_log()` function
- ALL implementation files - Replace debug code with `debug_log()` calls

#### Debug Scopes

**All LEE Interfaces:**
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

#### Environment Variables

**Master Control:**
```bash
DEBUG_MODE=true|false          # Master switch (required for any debug output)
```

**Scope-Specific Flags:**
```bash
ALEXA_DEBUG_MODE=true|false    # Enable Alexa debug logs
ALEXA_DEBUG_TIMING=true|false  # Include timing in Alexa logs

HA_DEBUG_MODE=true|false       # Enable HA debug logs
HA_DEBUG_TIMING=true|false     # Include timing in HA logs

CACHE_DEBUG_MODE=true|false    # Enable cache debug logs
CACHE_DEBUG_TIMING=true|false  # Include timing in cache logs

# ... etc for all scopes
```

**Default Behavior:**
- Unset variables default to `false`
- Initialization happens during lambda preload
- All scopes configured once at cold start

#### Usage Pattern

**Before (verbose, scattered):**
```python
# 10+ lines of debug code per statement
DEBUG_MODE = os.environ.get('DEBUG_MODE', 'false').lower() == 'true'
DEBUG_TIMINGS = os.environ.get('DEBUG_TIMINGS', 'false').lower() == 'true'

if DEBUG_MODE:
    log_debug(f"[{correlation_id}] [ALEXA-DEBUG] Starting enrichment (entity_id={entity_id})")

if DEBUG_TIMINGS:
    start_time = time.perf_counter()

# ... operation ...

if DEBUG_TIMINGS:
    duration_ms = (time.perf_counter() - start_time) * 1000
    log_info(f"[{correlation_id}] [ALEXA-TIMING] enrichment: {duration_ms:.2f}ms")
```

**After (one line):**
```python
# Single line for debug
debug_log(corr_id, "ALEXA", "Starting enrichment", entity_id=entity_id)

# Single line with timing context manager
with debug_log(corr_id, "ALEXA", "enrichment", timing=True):
    # ... operation ...
```

**Line Reduction:** 10+ lines → 1 line per debug statement

---

### Part 2: File Size Management

#### Hard Limits

**File Size Constraints:**
- **Maximum:** 350 lines (hard stop)
- **Warning:** 300 lines (consider splitting)
- **Target:** 250-300 lines (optimal)

**Why 350 Lines:**
- Claude truncates files >400 lines (22% minimum loss)
- 350-line buffer prevents accidental truncation
- Files remain fully visible in project knowledge

#### Splitting Strategy

**When to Split:**
1. File reaches 300 lines → Plan split
2. File reaches 350 lines → Split immediately
3. File has distinct logical sections → Consider splitting even if <300 lines

**How to Split:**

**Example: `ha_alexa_core.py` (500 lines) → Split into:**
```
ha_alexa_core.py          (250 lines) - Main handlers
ha_alexa_helpers.py       (150 lines) - Helper functions
ha_alexa_properties.py    (100 lines) - Property builders
```

**Naming Convention:**
- `{module}_core.py` - Primary implementation
- `{module}_helpers.py` - Helper functions
- `{module}_types.py` - Type definitions
- `{module}_config.py` - Configuration
- `{module}_validation.py` - Validation logic

#### Comment Reduction

**Remove from Code Files:**
- ❌ Verbose function docstrings (>3 lines)
- ❌ Implementation explanations
- ❌ Usage examples
- ❌ Changelogs
- ❌ Decision rationales
- ❌ Architecture explanations

**Keep in Code Files:**
- ✅ One-line function purpose
- ✅ Parameter types (type hints)
- ✅ Critical inline comments (why, not what)
- ✅ TODO/FIXME markers

**Before:**
```python
def enrich_response_with_state(response, entity_id, oauth_token, correlation_id):
    """
    Enrich Alexa response with fresh device state in context.properties.
    
    This function fetches the current state from Home Assistant and builds
    Alexa-compliant property objects that are injected into the response's
    context section. This ensures Alexa's UI displays the correct device
    state immediately after a control action.
    
    The function handles multiple device types including lights, switches,
    thermostats, and sensors. Each device type has specific property
    mappings defined in the Alexa Smart Home API specification.
    
    Args:
        response: Original HA response dictionary
        entity_id: Entity ID to fetch state for (normalized format)
        oauth_token: OAuth token for HA API authentication
        correlation_id: Request correlation ID for logging
        
    Returns:
        Response dictionary enriched with context.properties
        
    Raises:
        None - exceptions are caught and logged, original response returned
        
    Example:
        >>> response = {'event': {...}}
        >>> enriched = enrich_response_with_state(response, 'light.bedroom', token, corr_id)
        >>> 'context' in enriched
        True
    """
```

**After:**
```python
def enrich_response_with_state(response: Dict, entity_id: str, 
                               oauth_token: str, correlation_id: str) -> Dict:
    """Enrich response with fresh state in context.properties."""
```

**Line Savings:** 30 lines → 2 lines

---

### Part 3: Companion Documentation Files

#### Structure

**For Every Code File:**
```
ha_alexa_core.py          (300 lines) - Implementation
ha_alexa_core.md          (250 lines) - Documentation
```

**Companion File Contents:**

**Header:**
```markdown
# ha_alexa_core.py

**Version:** 2025-12-06_1  
**Module:** Home Assistant Alexa Integration  
**Layer:** Core Implementation  
**Dependencies:** gateway, ha_interconnect, ha_alexa_templates

---
```

**Sections:**
1. **Purpose** - What this module does
2. **Architecture** - How it fits in LEE
3. **Functions** - Detailed function documentation
4. **Usage Examples** - Code examples
5. **Error Handling** - Error scenarios
6. **Performance** - Timing expectations
7. **Changelog** - Version history (in .md, not .py)

**Example Documentation:**

```markdown
## Functions

### enrich_response_with_state()

**Purpose:** Fetch fresh entity state from HA and inject into Alexa response.

**Signature:**
```python
def enrich_response_with_state(
    response: Dict[str, Any],
    entity_id: str,
    oauth_token: str,
    correlation_id: str
) -> Dict[str, Any]
```

**Parameters:**
- `response` - Original HA response (modified in-place)
- `entity_id` - Normalized entity ID (light.bedroom format)
- `oauth_token` - LWA OAuth token for HA API
- `correlation_id` - Request correlation ID

**Returns:** Response with context.properties added/updated

**Behavior:**
1. Calls `devices_get_by_id()` to fetch fresh state
2. Builds property objects via `_build_context_properties()`
3. Injects into `response['context']['properties']`
4. Returns enriched response
5. On error: logs warning, returns original response unchanged

**Usage:**
```python
enriched = enrich_response_with_state(
    ha_response,
    'light.bedroom',
    oauth_token,
    correlation_id
)
```

**Performance:** ~80ms (includes HA API call)

**Error Scenarios:**
- State fetch fails → Original response returned
- Property building fails → Original response returned
- Invalid entity_id → Logged, original response returned
```

#### File Limits for Documentation

**Companion .md Files:**
- **Maximum:** 350 lines (same as code)
- **Target:** 250-300 lines
- **Split if needed:** Create `{module}_core_part2.md` if >350 lines

---

### Part 4: Version Format Standardization

#### New Format

**Version String:** `YYYY-MM-DD_R`

**Components:**
- `YYYY` - Year (4 digits)
- `MM` - Month (2 digits, zero-padded)
- `DD` - Day (2 digits, zero-padded)
- `R` - Daily revision number (1, 2, 3, ...)

**Examples:**
```
2025-12-06_1    # First revision on Dec 6, 2025
2025-12-06_2    # Second revision same day
2025-12-07_1    # First revision next day
```

**Benefits:**
- Chronological sorting automatic
- Clear deployment timeline
- Multiple revisions per day supported
- No arbitrary version numbers
- No semantic versioning confusion

#### File Header Format

**Before (verbose):**
```python
"""
ha_alexa_core.py - Alexa Core Implementation (INT-HA-01)
Version: 4.3.0
Date: 2025-12-06
Description: Core implementation for Alexa Smart Home integration

CHANGES (4.3.0 - COMPREHENSIVE DEBUGGING):
- ADDED: Full DEBUG_MODE support with detailed pipeline logging
- ADDED: DEBUG_TIMINGS support with performance measurements
- ADDED: Request/response structure logging
- ADDED: State fetch and enrichment detailed traces
- Result: Complete visibility into Alexa request processing

CHANGES (4.2.0 - STATE ENRICHMENT FIX):
- FIXED: Enrich response with fresh state in context.properties
- ADDED: Fetch entity state after successful control
- ADDED: Build Alexa-compliant context.properties

CHANGES (4.1.0 - STATE SYNC FIX):
- FIXED: Cache invalidation after control actions

CHANGES (4.0.0 - LWA MIGRATION):
- MODIFIED: All functions accept oauth_token parameter
- MODIFIED: Pass oauth_token to devices_call_ha_api
- REMOVED: Token loading from config

Copyright 2025 Joseph Hersey
Licensed under Apache 2.0 (see LICENSE).
"""
```

**After (minimal):**
```python
"""
ha_alexa_core.py
Version: 2025-12-06_3
Purpose: Alexa Smart Home integration core handlers
License: Apache 2.0
"""
```

**Line Savings:** 35 lines → 5 lines

**Changelog Location:** Moved to `ha_alexa_core.md` companion file

---

### Part 5: SIMA Documentation Updates

#### New Documentation Standards

**File:** `/sima/standards/File-Standards.md`

**Add Section:**

```markdown
## Code File Standards

### File Size Limits
- **Maximum:** 350 lines (hard limit)
- **Warning threshold:** 300 lines
- **Target range:** 250-300 lines
- **Action at 300 lines:** Plan file split
- **Action at 350 lines:** Split immediately

### Companion Documentation
- Every `.py` file has a `.md` companion
- Companion file name: `{filename}.md`
- Companion file location: Same directory as code file
- Maximum size: 350 lines (same as code)

### Header Format
```python
"""
{filename}.py
Version: YYYY-MM-DD_R
Purpose: One-line description
License: Apache 2.0
"""
```

### Version Format
- **Format:** `YYYY-MM-DD_R`
- **Example:** `2025-12-06_2`
- **R:** Daily revision number (1, 2, 3...)
- **Increment R:** For each change on same day
- **Reset R to 1:** On new day

### Comment Standards
**In Code Files:**
- ✅ One-line function purpose
- ✅ Type hints
- ✅ Critical inline comments (why, not what)
- ❌ Multi-line docstrings
- ❌ Usage examples
- ❌ Changelogs
- ❌ Architecture explanations

**In Companion .md Files:**
- ✅ Detailed function documentation
- ✅ Usage examples
- ✅ Architecture explanations
- ✅ Changelogs
- ✅ Error scenarios
- ✅ Performance notes
```

#### Update Existing Standards

**Files to Update:**
- `/sima/standards/File-Standards.md` - Add new sections
- `/sima/standards/Artifact-Standards.md` - Update header format
- `/sima/standards/Version-Standards.md` - New file for versioning
- `/sima/templates/code-file-header.txt` - New minimal header
- `/sima/templates/companion-file.md` - Template for .md files

---

## Implementation Plan

### Phase 1: Debug Infrastructure (Day 1)

**Tasks:**
1. Create `debug_config.py` (40 lines)
2. Create `debug_core.py` (120 lines)
3. Create `interface_debug.py` (25 lines)
4. Update `lambda_function.py` - Add preload initialization
5. Update `gateway.py` - Export `debug_log()`
6. Test debug infrastructure in isolation

**Deliverables:**
- Working debug infrastructure
- All scopes initialized
- Test cases pass

### Phase 2: Apply Debug System (Day 2)

**Tasks:**
1. Update `ha_alexa_core.py` - Replace debug code with `debug_log()`
2. Update `ha_devices_core.py` - Replace debug code
3. Update `cache_core.py` - Replace debug code
4. Update remaining core files
5. Test each file after conversion
6. Verify debug output with flags

**Deliverables:**
- All files using unified debug system
- Line count reduced significantly
- Debug flags working correctly

### Phase 3: File Splitting & Documentation (Day 3)

**Tasks:**
1. Identify files >300 lines
2. Split files according to strategy
3. Create companion .md files for all .py files
4. Move verbose documentation to .md files
5. Update file headers to new format
6. Remove changelogs from code files

**Deliverables:**
- All files <350 lines
- All files have companion .md files
- New header format applied
- Changelogs in .md files

### Phase 4: SIMA Updates (Day 3 afternoon)

**Tasks:**
1. Create `/sima/standards/Version-Standards.md`
2. Update `/sima/standards/File-Standards.md`
3. Create templates for headers and companions
4. Document debug system in SIMA
5. Update project README with new standards

**Deliverables:**
- SIMA standards updated
- Templates available
- Documentation complete

---

## File Structure After Implementation

### Debug Infrastructure
```
/src/
  debug_config.py              (40 lines)  - Debug state
  debug_core.py                (120 lines) - Debug logic
  interface_debug.py           (25 lines)  - Debug interface
```

### Example Refactored Module
```
/src/home_assistant/
  ha_alexa_core.py             (250 lines) - Handlers
  ha_alexa_core.md             (280 lines) - Documentation
  ha_alexa_helpers.py          (120 lines) - Helper functions
  ha_alexa_helpers.md          (150 lines) - Helper docs
  ha_alexa_properties.py       (100 lines) - Property builders
  ha_alexa_properties.md       (120 lines) - Property docs
```

### SIMA Documentation
```
/sima/standards/
  File-Standards.md            (updated)
  Artifact-Standards.md        (updated)
  Version-Standards.md         (new)

/sima/templates/
  code-file-header.txt         (new)
  companion-file-template.md   (new)
```

---

## Success Metrics

### Code Quality
- ✅ All files <350 lines
- ✅ Debug code reduced from 10+ lines to 1 line per statement
- ✅ 100% of code files have companion .md files
- ✅ New version format applied to all files

### Functionality
- ✅ Debug system works with hierarchical control
- ✅ Master switch disables all output
- ✅ Scope-specific flags work independently
- ✅ Timing context managers work correctly

### Maintainability
- ✅ Documentation separate from implementation
- ✅ Changelogs in .md files, not code files
- ✅ Clear version history by date
- ✅ File splits maintain logical coherence

---

## Risk Assessment

### Risks

**Risk 1: Debug System Performance**
- **Impact:** Medium
- **Probability:** Low
- **Mitigation:** Master switch short-circuits instantly when off

**Risk 2: File Splitting Breaks Imports**
- **Impact:** High
- **Probability:** Medium
- **Mitigation:** Comprehensive testing after each split

**Risk 3: Documentation Drift**
- **Impact:** Medium
- **Probability:** High
- **Mitigation:** Update .md file whenever .py file changes

### Rollback Plan

If issues arise:
1. Keep old files in `/src/backup/`
2. Git commit before each phase
3. Can revert per-file if needed

---

## Appendix A: Debug Log Format Examples

### Standard Debug Log
```
[7167393a-ceca] [ALEXA-DEBUG] Directive received (namespace=Alexa.PowerController, name=TurnOn)
```

### Debug Log with Context
```
[7167393a-ceca] [HA-DEBUG] Fetching state (entity_id=light.bedroom, use_cache=true)
```

### Timing Log
```
[7167393a-ceca] [ALEXA-TIMING] state_enrichment: 78.45ms
```

### Combined Debug + Timing
```
[7167393a-ceca] [CACHE-DEBUG] Cache miss (key=ha_state_light.bedroom)
[7167393a-ceca] [CACHE-TIMING] cache_get: 0.85ms
```

---

## Appendix B: Environment Variable Reference

### Complete List

```bash
# Master control
DEBUG_MODE=true|false

# Alexa
ALEXA_DEBUG_MODE=true|false
ALEXA_DEBUG_TIMING=true|false

# Home Assistant
HA_DEBUG_MODE=true|false
HA_DEBUG_TIMING=true|false

# Devices
DEVICES_DEBUG_MODE=true|false
DEVICES_DEBUG_TIMING=true|false

# Cache
CACHE_DEBUG_MODE=true|false
CACHE_DEBUG_TIMING=true|false

# HTTP
HTTP_DEBUG_MODE=true|false
HTTP_DEBUG_TIMING=true|false

# Config
CONFIG_DEBUG_MODE=true|false
CONFIG_DEBUG_TIMING=true|false

# Security
SECURITY_DEBUG_MODE=true|false
SECURITY_DEBUG_TIMING=true|false

# Metrics
METRICS_DEBUG_MODE=true|false
METRICS_DEBUG_TIMING=true|false

# Circuit Breaker
CIRCUIT_BREAKER_DEBUG_MODE=true|false
CIRCUIT_BREAKER_DEBUG_TIMING=true|false

# Singleton
SINGLETON_DEBUG_MODE=true|false
SINGLETON_DEBUG_TIMING=true|false

# Gateway
GATEWAY_DEBUG_MODE=true|false
GATEWAY_DEBUG_TIMING=true|false

# Initialization
INIT_DEBUG_MODE=true|false
INIT_DEBUG_TIMING=true|false

# WebSocket
WEBSOCKET_DEBUG_MODE=true|false
WEBSOCKET_DEBUG_TIMING=true|false

# Logging (framework internal)
LOGGING_DEBUG_MODE=true|false
LOGGING_DEBUG_TIMING=true|false
```

---

**END OF DESIGN DOCUMENT**

**Status:** Ready for Implementation  
**Approval Required:** Yes  
**Estimated Completion:** 3 days  
**Document Version:** 2025-12-06_1

# DEC-24.md

**Version:** 2.0.0  
**Date:** 2025-11-02  
**REF-ID:** DEC-24  
**Category:** Operational Decisions  
**Priority:** Critical  
**Status:** Active

---

## Decision

**Use fileserver.php to dynamically generate cache-busted URL list, eliminating manual Cache ID generation and ensuring fresh file content every session.**

---

## Context

### The Problem

Anthropic's `web_fetch` tool aggressively caches files for weeks, completely ignoring all server-side cache control headers:

**Evidence:**
- October 20, 2025: gateway_wrappers.py modified
- November 2, 2025: Claude received 13-day-old cached version
- Server-side mitigation attempted:
  - `.htaccess` cache-control headers: Ignored
  - Web server configuration: Ignored  
  - Cloudflare caching disabled: Ignored
  - All marked as uncacheable: Still cached

**Impact:**
- Development against week-old code
- Bugs from outdated files
- Project Knowledge truncates >400-line files (loses 10% of content)
- Manual workarounds required every session
- Collaborative development impossible

### Platform Limitation Discovered

During implementation, discovered critical Anthropic platform behavior:

```
File Server URLs.md contains:
https://claude.dizzybeaver.com/src/gateway.py

Can fetch: ✅ 
https://claude.dizzybeaver.com/src/gateway.py

Cannot fetch: ❌
https://claude.dizzybeaver.com/src/gateway.py?v=7382915046

Error: PERMISSIONS_ERROR - "URL not provided by user"
```

**Key Insight:** URLs with query parameters need explicit permission, BUT URLs from fetch results CAN have query parameters.

---

## Solution: fileserver.php Dynamic Generation

### Architecture

```
User uploads File Server URLs.md:
  https://claude.dizzybeaver.com/fileserver.php

Claude fetches fileserver.php (69ms) →
  Returns ~412 URLs with cache-busting
  Example: gateway.py?v=7382915046

Claude fetches files from list →
  Permission granted (URLs from fetch result)
  Random parameters bypass cache
  Fresh content guaranteed
```

### Implementation

**fileserver.php (PHP 8.2):**
```php
// Scans /src and /sima directories via scandir()
// Generates random 10-digit number per file
// Returns File Server URLs.md format
// Execution: ~69ms for 412 files
```

**Key Features:**
- Filesystem access (not HTTP) = fast
- Random 10-digit parameters = unique per session
- URL encoding (spaces → %20) = proper formatting
- Zero user maintenance = fully automatic

**File Location:**
```
/sima/support/tools/fileserver.php
```

### Session Workflow

```
1. User uploads File Server URLs.md
   Content: https://claude.dizzybeaver.com/fileserver.php

2. Claude fetches fileserver.php
   Result: 412 URLs like:
   https://claude.dizzybeaver.com/src/gateway.py?v=7382915046
   https://claude.dizzybeaver.com/sima/entries/decisions/operational/DEC-24.md?v=5253714240

3. Claude fetches specific files as needed
   Uses URLs from fileserver.php output
   Permission granted (came from fetch result)
   Cache bypassed (unique random parameters)

4. Result: Fresh file content every session
```

---

## Alternatives Considered

### Manual Cache ID Generation (Original Approach)

**Proposed:**
- User generates random 10-digit number
- Provides "Cache ID: XXXXXXXXXX" in message
- Claude manually appends ?v= to each URL

**Why Rejected:**
- Anthropic permissions block query parameters on listed URLs
- Platform limitation makes it impossible
- Discovered during implementation testing

### API Endpoint

**Proposed:**
- Create `/api/get-file?name=X` endpoint
- Returns file content directly

**Why Rejected:**
- Anthropic would cache the API URL itself
- Same cache problem, different URL
- More complex infrastructure

### Pre-generated Static File

**Proposed:**
- Cron job generates cached-file-list.txt
- Update every 5 minutes
- fileserver.php just adds cache-busting to static list

**Why Rejected:**
- Unnecessary complexity
- Filesystem scan is already fast (69ms)
- Extra moving parts to maintain

---

## Decision Rationale

### Why fileserver.php

**Advantages:**
1. **Works Within Platform:** Uses fetch results (permission granted)
2. **Zero User Maintenance:** Auto-generates on access
3. **Fast Execution:** 69ms for 412 files
4. **Reliable:** Filesystem scan (no HTTP timeouts)
5. **Simple:** Single PHP file, no infrastructure changes

**Trade-offs:**
1. Requires PHP on server (already available: PHP 8.2)
2. Adds 69ms to session start (acceptable overhead)
3. Server must have read access to directories (already has)

### Risk Assessment

**Low Risk:**
- Minimal code (200 lines PHP)
- Fast execution (proven 69ms)
- Fallback: Access files directly if fileserver.php fails
- No breaking changes (new system)

**Mitigated Risks:**
- Timeout: 69ms execution well below 10s threshold
- Permissions: Filesystem access already granted
- Maintenance: Zero ongoing maintenance required

---

## Implementation Details

### fileserver.php Configuration

```php
$DOMAIN = 'claude.dizzybeaver.com';  // Configurable
$BASE_PATHS = [__DIR__ . '/src', __DIR__ . '/sima'];
$EXCLUDED_DIRS = ['.git', 'node_modules'];
$EXCLUDED_FILES = ['.htaccess', 'fileserver.php'];
```

### Output Format

```
# SUGA-ISP Lambda - File Server URLs (Cache-Busted)

**Generated:** 2025-11-02 17:15:28 EST
**Purpose:** Dynamic URL inventory with cache-busting
**Total Files:** 412

---

## 📂 PYTHON SOURCE FILES (/src - 103 files)

```
https://claude.dizzybeaver.com/src/gateway.py?v=8228685071
https://claude.dizzybeaver.com/src/gateway_wrappers.py?v=3085732020
...
```

## 📂 ENTRIES (/sima/entries - 215 files)

```
https://claude.dizzybeaver.com/sima/entries/decisions/operational/DEC-24.md?v=5253714240
...
```
```

### Performance

- **Execution Time:** 69ms (412 files)
- **Memory Usage:** Minimal (scandir is efficient)
- **Cache-Bust Range:** 1000000000 to 9999999999 (10-digit)
- **Collision Probability:** Negligible per session

---

## Benefits

### Development Workflow

**Before (Manual Cache IDs):**
- User generates random number
- Provides Cache ID each session
- Claude attempts to add ?v= manually
- Platform permissions block it
- Falls back to cached files
- Result: Week-old code, bugs

**After (fileserver.php):**
- User uploads File Server URLs.md (once)
- Claude fetches fileserver.php automatically
- Receives fresh URL list with cache-busting
- Fetches files as needed
- Result: Current code, reliable development

### User Experience

- **Zero Setup:** Upload one file, done
- **No Manual Steps:** No Cache ID generation
- **Reliable:** Works every session
- **Fast:** 69ms overhead acceptable
- **Transparent:** Happens automatically

### Technical Benefits

- **Bypasses Platform Cache:** Random parameters work
- **Proper Permissions:** URLs from fetch results allowed
- **Maintainable:** Single PHP file, clear purpose
- **Scalable:** Handles file count growth easily
- **Debuggable:** Can test fileserver.php directly

---

## Verification

### Testing

**Test fileserver.php:**
```bash
curl https://claude.dizzybeaver.com/fileserver.php

# Should return:
# - Complete file list
# - Each URL with ?v=XXXXXXXXXX
# - Execution time ~69ms
# - 412 files currently
```

**Test in Claude session:**
```
1. Upload File Server URLs.md (fileserver.php URL)
2. Activate any mode
3. Request file fetch
4. Verify fresh content (check Last-Modified if available)
5. Compare to direct web access
```

### Monitoring

- Track fileserver.php execution time (target <100ms)
- Monitor file count growth (currently 412)
- Watch for timeout errors (none expected)
- Verify cache-busting effectiveness (no stale files)

---

## Rollout

### Phase 1: Infrastructure (Complete)

- [x] Create fileserver.php
- [x] Test locally (69ms execution confirmed)
- [x] Deploy to server
- [x] Verify 412 files returned
- [x] Test cache-busting works

### Phase 2: Documentation (In Progress)

- [x] Update DEC-24.md (this file)
- [ ] Update WISD-06.md
- [ ] Update Custom Instructions
- [ ] Update mode context files
- [ ] Update user documentation

### Phase 3: Deployment (Pending)

- [ ] Update File Server URLs.md (list fileserver.php)
- [ ] Test in General Mode
- [ ] Test in Project Mode
- [ ] Test in Debug Mode
- [ ] Test in Learning Mode

---

## Future Enhancements

### Potential Improvements

**Caching Strategy (If Needed):**
- Cache fileserver.php output for 60 seconds
- Reduce filesystem scans if multiple sessions
- Trade freshness for performance
- Only if 69ms becomes problematic

**Filtering:**
- Add file extension filtering
- Allow subdirectory exclusions
- Support file size limits
- Enable on-demand if needed

**Monitoring:**
- Add execution time logging
- Track cache-bust effectiveness
- Monitor file count growth
- Alert on anomalies

**Not Planned:**
- None of above needed currently
- 69ms acceptable
- 412 files manageable
- System working well

---

## Related Decisions

- **WISD-06:** Platform cache-busting wisdom (complementary)
- **DEC-21:** SSM Token-Only (similar performance optimization)
- **DEC-22:** DEBUG_MODE (similar runtime toggle pattern)
- **LESS-01:** Read Complete Files First (benefits from fresh content)

---

## Keywords

cache-busting, fileserver, dynamic-generation, anthropic-platform, file-retrieval, php, session-workflow, permissions, query-parameters, fresh-content

---

## Version History

- **2025-11-02 (v2.0.0):** Major revision - fileserver.php implementation
  - Changed from manual Cache ID to dynamic generation
  - Added platform limitation discovery
  - Updated architecture and workflow
  - Reflected actual working solution
  
- **2025-11-02 (v1.0.0):** Initial decision (manual Cache ID approach)
  - Proposed user-generated Cache IDs
  - Platform testing revealed limitation
  - Superseded by v2.0.0

---

**File:** `DEC-24.md`  
**Location:** `/sima/entries/decisions/operational/`  
**End of Decision**

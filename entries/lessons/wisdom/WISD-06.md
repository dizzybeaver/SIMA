# File: WISD-06.md

**REF-ID:** WISD-06  
**Type:** Wisdom  
**Category:** Tool Constraints  
**Version:** 1.0.0  
**Created:** 2025-11-02  
**Priority:** Critical

---

## WISD-06: Session-Level Cache-Busting for AI Tool Constraints

### Core Insight

When platform tools have immutable aggressive caching beyond configuration control, implement session-level cache-busting via unique identifiers in requests. Simple query parameters bypass platform caches while maintaining clean source data.

**Universal Principle:** Work around uncontrollable platform constraints with minimal-cost session identifiers rather than fighting the platform.

---

## The Wisdom

**When platform caching is:**
- Aggressive beyond control (ignores all cache headers)
- Immutable (no API to clear/bypass)
- Breaking workflows (serves stale data)

**Then apply session-level cache-busting:**
1. User provides unique session identifier (timestamp/UUID)
2. Append identifier to all fetch URLs as query parameter
3. Platform treats each session as distinct (fresh fetches)
4. Source data stays clean (no cache-busting in files)

**ROI:** 5 seconds per session → Always fresh data

---

## Problem Space

### Symptom
AI tools with web_fetch capability serve cached content (days/weeks old) despite:
- Server-side cache control headers (Cache-Control: no-cache)
- CDN cache purges
- Manual file updates
- All standard HTTP cache mechanisms

### Root Cause
Platform-level aggressive caching optimizes for:
- Performance (reduce external requests)
- Cost control (minimize bandwidth)
- Stability (survive upstream failures)

But ignores developer needs for fresh content during active development.

### Impact
- Week-old code fetched during development
- Changes invisible to AI assistant
- Collaborative development impossible
- Debugging uses stale state
- Documentation updates not reflected

**Measured Cost:** 3+ hours debugging phantom issues caused by cached files

---

## Solution Pattern

### Approach
Session-scoped cache invalidation via URL query parameters

**User Side:**
```bash
# Generate unique session ID (Unix timestamp)
date +%s
# Output: 1730486400

# Provide at session start
Cache ID: 1730486400
```

**AI Side (Automatic):**
```
User provides:
https://server.com/src/gateway.py

AI fetches:
https://server.com/src/gateway.py?v=1730486400

Server: Ignores ?v parameter (serves file normally)
AI Platform: Treats as unique URL (bypasses cache)
```

### Benefits
- ✅ One timestamp per session (~5 seconds)
- ✅ Source files unchanged (clean URLs)
- ✅ No infrastructure changes needed
- ✅ Platform cache bypassed automatically
- ✅ Always fresh content
- ✅ Easy to debug (session ID traceable)

### Costs
- Negligible: <1ms per request overhead
- One-time: 5 seconds to generate timestamp per session

**ROI:** 5 seconds → Eliminates hours of stale-cache debugging

---

## Implementation

### Step 1: User Workflow
```
Session Start:
1. Generate timestamp: `date +%s`
2. Provide to AI: "Cache ID: 1730486400"
3. AI stores for session
4. Continue normally
```

### Step 2: AI Integration
```
URL Transformation (Automatic):
- Intercept all web_fetch calls
- Append ?v=[cache_id] to URLs
- Apply to ALL file types
- No exceptions

Example:
web_fetch(url) → web_fetch(f"{url}?v={cache_id}")
```

### Step 3: Verification
```
AI Confirms:
"✅ Cache ID: 1730486400 registered
   All fetches use cache-busting"

During Fetch:
"Fetching gateway.py with cache-busting..."
```

---

## Generic Applications

This pattern applies beyond AI tools to any scenario with:

### Scenario 1: Aggressive Platform Caching
- CDNs with vendor-level caching
- Enterprise proxies
- Corporate gateways
- Shared infrastructure

**Solution:** Client-side cache-busting via session IDs

### Scenario 2: Multi-Tenant Systems
- Shared caches between users
- Isolation requirements
- Data freshness guarantees

**Solution:** Tenant-scoped session identifiers

### Scenario 3: Development vs Production
- Development needs fresh data
- Production benefits from caching
- Same infrastructure

**Solution:** Environment-specific cache-busting (dev only)

### Scenario 4: Collaborative Tools
- Multiple users editing same files
- Real-time collaboration
- Stale cache breaks workflow

**Solution:** Session-based or user-based cache keys

---

## Key Principles

### Principle 1: Work With Platform Constraints
**Don't fight immutable platform behavior**
- Platform caches aggressively? Assume permanent
- No cache control API? Won't change
- Design around constraint, not against it

### Principle 2: Minimal Session Overhead
**User provides one value per session**
- Not per-file, not per-request
- One timestamp, stored for session
- Automatic application thereafter

### Principle 3: Preserve Source Data Integrity
**Cache-busting in transport, not storage**
- Source files: Clean URLs
- Fetch URLs: Cache-busting appended
- Server: Ignores query parameters
- Platform: Sees unique URLs

### Principle 4: Transparent to Server
**Server needs no changes**
- Query parameter ignored
- Standard file serving
- Works with existing infrastructure
- No server-side cache-busting logic

---

## Anti-Patterns

### ❌ Anti-Pattern 1: Server-Side Only
**Problem:** Relying on server cache headers when platform ignores them

**Why Wrong:** Platform-level caching bypasses server directives

**Cost:** Wasted time configuring cache headers that don't work

**Alternative:** Client/session-level cache-busting

### ❌ Anti-Pattern 2: Pollute Source Files
**Problem:** Adding cache-busting to source URLs

```
❌ File-Server-URLs.md:
https://server.com/src/gateway.py?v=1730486400
```

**Why Wrong:** Source files become unmaintainable with timestamps

**Alternative:** Clean source URLs + runtime cache-busting

### ❌ Anti-Pattern 3: Per-File Cache IDs
**Problem:** Different cache-busting value per file

**Why Wrong:** User burden scales with file count

**Alternative:** One session ID, apply to all files

### ❌ Anti-Pattern 4: Skip Cache-Busting Conditionally
**Problem:** "This file unlikely to change, skip cache-busting"

**Why Wrong:** Assumptions about change frequency fail

**Alternative:** Apply to ALL files, no exceptions

---

## Trade-Offs

### Benefits
- ✅ Minimal user friction (one timestamp/session)
- ✅ No infrastructure changes
- ✅ Guaranteed fresh content
- ✅ Works with existing setup
- ✅ Debuggable (session ID traceable)
- ✅ Scales to any file count

### Costs
- ⚠️ 5 seconds user time per session
- ⚠️ Slight URL overhead (<50 bytes/request)
- ⚠️ Platform cache less effective (fresh fetches)

### Acceptable Trade-Off?
**YES** - Benefits vastly outweigh costs for collaborative development

---

## When to Apply

### Use When:
✅ Platform caching is aggressive and immutable
✅ Server-side controls ineffective
✅ Development requires fresh data
✅ Minimal user friction acceptable (one value/session)
✅ No server-side changes possible/desired

### Don't Use When:
❌ Platform respects cache headers (use standard HTTP caching)
❌ Have API to clear platform cache (use that)
❌ Production-only (caching beneficial)
❌ Read-only data (never changes)

---

## Measurement

### Before Cache-Busting
- Server: All cache headers set correctly
- Platform: Ignores headers, serves week-old content
- Development: 3+ hours debugging phantom issues
- Collaboration: Impossible (stale data)

### After Cache-Busting
- User: 5 seconds to generate timestamp
- Platform: Fetches fresh content every session
- Development: Always current code
- Collaboration: Seamless (everyone sees latest)

**ROI:** 5 seconds → Eliminates 3+ hour debugging cycles

---

## Related Patterns

### Pattern 1: Content Hashing
**Similar:** Unique identifiers for cache-busting
**Different:** Hash based on content, not session
**Use When:** Content-addressable storage needed

### Pattern 2: ETags
**Similar:** Cache validation
**Different:** Server-driven, requires support
**Use When:** Platform respects HTTP cache validation

### Pattern 3: Versioned URLs
**Similar:** Unique URLs for cache-busting
**Different:** Version in path, not query param
**Use When:** Server controls URL structure

### Pattern 4: Cache-Control Headers
**Similar:** Control caching behavior
**Different:** Server-side directives
**Use When:** Platform respects HTTP headers (this case: doesn't)

---

## Success Criteria

### Implementation Successful When:
- ✅ User provides one cache ID per session
- ✅ AI appends to all fetch URLs automatically
- ✅ Platform fetches fresh content every session
- ✅ Source files remain clean (no cache-busting URLs)
- ✅ No changes needed to server infrastructure
- ✅ Stale content issues eliminated
- ✅ Collaborative development possible

### Failure Modes:
- ❌ Cache ID forgotten → AI prompts for it
- ❌ Same cache ID reused across sessions → Fresh timestamp needed
- ❌ Cache-busting skipped for some files → Apply to ALL files

---

## Evolution

**How This Wisdom Emerged:**
1. Exhausted all server-side caching controls
2. Confirmed platform ignores cache headers (tested weeks)
3. Identified platform-level caching as immutable constraint
4. Designed minimal-cost workaround (session IDs)
5. Tested and validated (confirmed fresh fetches)
6. Genericized to universal pattern

**Key Realization:** Can't change platform → Change approach

---

## Keywords

cache-busting, platform-constraints, session-identifiers, immutable-caching, workaround-patterns, tool-limitations, fresh-data

---

## Cross-References

**Applies To:**
- Any AI assistant with web_fetch capability
- CDN-backed content
- Enterprise proxies
- Collaborative development tools
- Multi-tenant systems

**Related Wisdom:**
- WISD-01: Architecture prevents problems (design around constraints)
- WISD-02: Measure don't guess (confirmed platform behavior)
- WISD-03: Small costs early (5s prevents 3+ hour debug)
- WISD-04: Consistency over cleverness (simple timestamp > complex schemes)

**Related Decisions:**
- Use of query parameters (standard, server-agnostic)
- Session-scope vs per-file (minimal user burden)
- Automatic application (reduce error)

---

**End of Entry**

**Summary:** When platform caching is immutable, session-level cache-busting via query parameters provides guaranteed fresh content with minimal user friction (5s per session) and no infrastructure changes.

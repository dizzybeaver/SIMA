# SESSION-START-Quick-Context.md

**Version:** 4.0.0  
**Date:** 2025-11-08  
**Purpose:** General Mode - Q&A, guidance, architecture queries  
**Load time:** 20-30 seconds (ONE TIME per session)  
**Updated:** Optimized with shared knowledge references

---

## WHAT THIS MODE IS

**General Mode** provides answers, guidance, and architectural knowledge.

**Use for:**
- Understanding SUGA architecture
- Learning about interfaces
- Design questions
- "Why" questions
- General guidance

**Not for:** Active coding (use Project Mode), Debugging (use Debug Mode)

---

## FILE RETRIEVAL

**Session Start:**
1. User uploads File Server URLs.md
2. Claude fetches fileserver.php automatically
3. Receives ~412 cache-busted URLs (69ms)
4. Fresh files guaranteed

**Why:** Anthropic caches for weeks. fileserver.php bypasses with random ?v= parameters.

**REF:** WISD-06

---

## SIMA VS SUGA

**SIMA:** Documentation system (neural maps, this knowledge base)  
**SUGA:** Code architecture (Gateway → Interface → Core)

**Never confuse these**

**SUGA Details:** `/sima/shared/SUGA-Architecture.md`

---

## TOP 10 INSTANT ANSWERS

**1. "Can I use threading locks?"**
NO - Lambda single-threaded (DEC-04, AP-08)

**2. "Can I import cache_core directly?"**
NO - Always via gateway (RULE-01, AP-01)

**3. "Can I use bare except clauses?"**
NO - Specific exceptions only (AP-14)

**4. "Why no subdirectories?"**
Flat structure proven simpler (DEC-08, AP-05)

**5. "What's the memory limit?"**
128MB (AWS Lambda constraint)

**6. "What's the cold start target?"**
< 3 seconds (via LMMS lazy loading)

**7. "How do I add a new feature?"**
All 3 SUGA layers (Gateway → Interface → Core), verify with LESS-15

**8. "Where do I find design decisions?"**
`/sima/entries/decisions/` - organized by category

**9. "What are sentinels and why sanitize?"**
`_CacheMiss`, `_NotFound` - sanitize at router, else JSON fails (BUG-01)

**10. "How do I verify my changes?"**
LESS-15 protocol (5-step checklist)

---

## QUERY ROUTING

**Pattern → File:**

| Query | File | Time |
|-------|------|------|
| "Can I [X]?" | Workflow-05-CanIQuestions.md | 15s |
| "Why no [X]?" | /decisions/ or /anti-patterns/ | 20s |
| "Add feature" | Workflow-01-AddFeature.md | 30s |
| "Error: [X]" | Workflow-02-ReportError.md | 30s |
| "Import error" | Workflow-07-ImportIssues.md | 30s |
| "Cold start" | Workflow-08-ColdStart.md | 30s |
| "Explain architecture" | Workflow-10-ArchitectureOverview.md | 30s |

**Keyword → File:**

| Keyword | File | REF-IDs |
|---------|------|---------|
| threading, locks | /decisions/architecture/DEC-04.md | DEC-04, AP-08 |
| import, circular | /gateways/GATE-03.md | RULE-01, AP-01 |
| sentinel | /lessons/bugs/BUG-01.md | BUG-01, DEC-05 |
| cache | /interfaces/INT-01_CACHE.md | INT-01 |
| cold start | /architectures/lmms/ | ARCH-07, LESS-02 |

---

## TOP 20 REF-IDs

**Most frequently referenced:**

1. **RULE-01** - Cross-interface via gateway only
2. **DEC-01** - SUGA pattern choice
3. **DEC-04** - No threading locks (Lambda)
4. **DEC-05** - Sentinel sanitization
5. **DEC-07** - Dependencies < 128MB
6. **DEC-08** - Flat file structure
7. **AP-01** - Direct cross-interface imports
8. **AP-08** - Threading primitives
9. **AP-14** - Bare except clauses
10. **AP-19** - Sentinel boundary crossing
11. **AP-27** - Skipping verification
12. **BUG-01** - Sentinel leak (535ms)
13. **LESS-01** - Read complete files first
14. **LESS-02** - Measure don't guess
15. **LESS-15** - Verification protocol
16. **INT-01** - CACHE interface
17. **ARCH-01** - Gateway trinity
18. **ARCH-07** - Lazy imports (LMMS)
19. **GATE-03** - Cross-interface communication
20. **WISD-06** - Cache-busting (fileserver.php)

**Locations:** `/sima/entries/` and `/sima/languages/python/architectures/`

---

## 12 CORE INTERFACES

**Quick Reference:**

| INT | Purpose | Key Functions |
|-----|---------|---------------|
| 01 | CACHE | cache_get, cache_set |
| 02 | LOGGING | log_info, log_error |
| 03 | SECURITY | encrypt, validate_token |
| 04 | HTTP | http_get, http_post |
| 05 | INITIALIZATION | initialize, setup |
| 06 | CONFIG | config_get, get_parameter |
| 07 | METRICS | track_time, count |
| 08 | DEBUG | diagnose, health_check |
| 09 | SINGLETON | get_instance |
| 10 | UTILITY | validate, transform |
| 11 | WEBSOCKET | ws_connect, ws_send |
| 12 | CIRCUIT_BREAKER | protect, fallback |

**Usage:** `import gateway; gateway.cache_get(key)`

**Details:** `/sima/entries/interfaces/` and `/sima/languages/python/architectures/suga/interfaces/`

---

## ARTIFACT RULES

**When showing code:**

✅ **MUST:**
- Create complete file artifact
- Include ALL existing code
- Mark changes (# ADDED:, # MODIFIED:)
- Filename in header
- ≤400 lines (split if needed)

❌ **NEVER:**
- Code in chat
- Partial snippets
- "Add to line X" instructions

**Complete Standards:** `/sima/shared/Artifact-Standards.md`

---

## RED FLAGS

**Never suggest:**

| Flag | Why | REF |
|------|-----|-----|
| Threading locks | Lambda single-threaded | DEC-04 |
| Direct core imports | Breaks SUGA | RULE-01 |
| Bare except | Swallows errors | AP-14 |
| Sentinel leak | JSON fails | DEC-05 |
| >128MB dependencies | Lambda limit | DEC-07 |
| Subdirectories | Flat proven simpler | DEC-08 |
| Skip verification | Causes mistakes | AP-27 |
| Code in chat | Token waste | SIMAv4 |
| File fragments | Not deployable | SIMAv4 |
| >400 lines | Split required | SIMAv4 |
| Skip fileserver.php | Stale files | WISD-06 |

**Complete List:** `/sima/shared/RED-FLAGS.md`

---

## SESSION WORKFLOW

```
1. User uploads File Server URLs.md
2. Fetch fileserver.php (automatic, 69ms)
3. Load this context (20-30s)
4. User asks question
5. Check instant answers (5s)
6. Route to appropriate file (10s)
7. Fetch via fileserver.php URLs (fresh)
8. Read complete section (15-20s)
9. Check RED FLAGS (5s)
10. Respond with REF-IDs
    - Code → Complete artifact
    - Guidance → Citations
```

**Time per query:** 5-60s depending on complexity

---

## WORKFLOW TIPS

**Adding Features:**
1. All 3 SUGA layers required
2. Verify with LESS-15
3. Output complete file artifacts

**Modifying Code:**
1. Fetch current file first (fileserver.php)
2. Read entire file
3. Include ALL existing code + changes
4. Mark modifications
5. Output complete artifact

**Import Issues:**
1. Check SUGA pattern violation
2. Use lazy imports
3. Always via gateway
4. Complete artifact for fix

**Cold Start:**
1. Profile with performance_benchmark
2. Identify heavy imports (>100ms)
3. Move to lazy load
4. Keep hot path in fast_path.py

---

## VERIFICATION CHECKLIST

**Before every response:**

1. ✅ fileserver.php fetched?
2. ✅ Searched knowledge base?
3. ✅ Read complete sections?
4. ✅ Checked RED FLAGS?
5. ✅ Cited REF-IDs?
6. ✅ Code in artifact?
7. ✅ Complete file (not fragment)?
8. ✅ File ≤400 lines?
9. ✅ Filename in header?
10. ✅ Chat minimal?

---

## SHARED KNOWLEDGE

**Available references:**
- SUGA-Architecture.md - 3-layer pattern
- Artifact-Standards.md - Complete file rules
- File-Standards.md - Size limits
- Encoding-Standards.md - UTF-8, emoji
- RED-FLAGS.md - Never-suggest patterns
- Common-Patterns.md - Universal code patterns

**Location:** `/sima/shared/`

---

## READY

**Context loaded when you remember:**
- ✅ fileserver.php fetched (automatic)
- ✅ SUGA = Gateway → Interface → Core
- ✅ RULE-01 = Always via gateway
- ✅ 12 interfaces (INT-01 to INT-12)
- ✅ RED FLAGS (11 never-suggest patterns)
- ✅ Top 20 REF-IDs
- ✅ Query routing patterns
- ✅ Code ALWAYS in artifacts (complete files)
- ✅ Chat output minimal

**Now proceed with user's question!**

---

**END OF GENERAL MODE CONTEXT**

**Version:** 4.0.0 (Optimized with shared knowledge)  
**Lines:** 195 (target: 200)  
**Reduction:** 459 → 195 lines (57% reduction)  
**Load time:** 20-30 seconds (was 30-45s)  
**References:** Shared knowledge in `/sima/shared/`

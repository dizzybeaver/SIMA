# File: NMP01-LEE-Quick-Index.md

# NMP01-LEE Quick Index

**Project:** Lambda Execution Engine (SUGA-ISP)  
**Project Code:** LEE  
**Version:** 1.0.0  
**Purpose:** Fast lookup for SUGA-ISP project-specific knowledge  
**Target Time:** < 30 seconds to find any entry

---

## ðŸŽ¯ QUICK LOOKUP BY PROBLEM

### Problem 1: "How do I cache HA entity states?"
**Answer:** NMP01-LEE-02 (CACHE Function Catalog) + NMP01-LEE-17 (HA Core)  
**Pattern:** Entity state caching with 5-minute TTL  
**Key Format:** `ha_entity_{entity_id}`  
**Performance:** 50-200x speedup

---

### Problem 2: "How do I log operations in this project?"
**Answer:** NMP01-LEE-03 (LOGGING Function Catalog)  
**Functions:** log_info(), log_error(), log_debug(), log_warning(), log_critical()  
**Format:** JSON structured logging to CloudWatch  
**Performance:** < 0.5ms overhead

---

### Problem 3: "How do I validate entity IDs?"
**Answer:** NMP01-LEE-04 (SECURITY Function Catalog)  
**Function:** validate_entity_id()  
**Pattern:** `domain.object_id` format check  
**Performance:** < 0.1ms

---

### Problem 4: "How does gateway dispatch work?"
**Answer:** NMP01-LEE-14 (Gateway Core)  
**Function:** execute_operation()  
**Pattern:** Lazy import + dispatch + error handling  
**Performance:** < 0.1ms (cached), 5-20ms (first call)

---

### Problem 5: "How do I optimize cold start?"
**Answer:** NMP01-LEE-16 (Fast Path Optimization)  
**Pattern:** ZAPH - preload critical interfaces + warm cache  
**Target:** < 300ms preload, < 3s cold start  
**Tiers:** Tier 1 (< 50ms) → Tier 2 (< 100ms) → Tier 3 (lazy)

---

### Problem 6: "How do I call HA API?"
**Answer:** NMP01-LEE-17 (HA Core - API Integration)  
**Functions:** get_entity_state(), call_service(), get_all_states()  
**Pattern:** Authentication + caching + error handling  
**Performance:** < 1ms (cached), 50-200ms (uncached)

---

### Problem 7: "How do I protect against HA API failures?"
**Answer:** NMP01-LEE-23 (Circuit Breaker)  
**Pattern:** Detect failures → Open circuit → Fast fail → Test recovery  
**States:** CLOSED → OPEN → HALF_OPEN  
**Performance:** < 1ms (fast fail when open)

---

### Problem 8: "What functions does CACHE interface have?"
**Answer:** NMP01-LEE-02 (CACHE Function Catalog)  
**Core:** cache_set(), cache_get(), cache_delete(), cache_clear()  
**Utility:** cache_has(), cache_get_stats()  
**Use Cases:** Entity caching, domain caching, config caching

---

### Problem 9: "How do I structure CloudWatch logs?"
**Answer:** NMP01-LEE-03 (LOGGING Function Catalog)  
**Format:** JSON with timestamp, level, message, **kwargs  
**Searchable:** All **kwargs fields searchable in CloudWatch  
**Levels:** DEBUG < INFO < WARNING < ERROR < CRITICAL

---

### Problem 10: "How do I detect sentinel objects?"
**Answer:** NMP01-LEE-04 (SECURITY Function Catalog)  
**Function:** is_sentinel()  
**Critical:** Prevents BUG-01 (sentinel leak)  
**Usage:** Check before caching or returning values

---

## ðŸ"' ALPHABETICAL KEYWORD LOOKUP

### A
**API integration** â†' NMP01-LEE-17 (HA Core)  
**Authentication** â†' NMP01-LEE-17 (HA token management)

### C
**Cache hit rates** â†' NMP01-LEE-02 (75-85% entity, 80-90% domain)  
**Cache invalidation** â†' NMP01-LEE-02 (invalidate_entity_cache)  
**Cache warming** â†' NMP01-LEE-16 (warm_cache)  
**Circuit breaker** â†' NMP01-LEE-23 (resilience)  
**CloudWatch** â†' NMP01-LEE-03 (logging integration)  
**Cold start** â†' NMP01-LEE-16 (< 3s target)

### D
**Dispatch** â†' NMP01-LEE-14 (execute_operation)

### E
**Entity caching** â†' NMP01-LEE-02 + NMP01-LEE-17  
**Entity ID validation** â†' NMP01-LEE-04 (validate_entity_id)  
**Error handling** â†' NMP01-LEE-14 (gateway), NMP01-LEE-17 (HA API)  
**execute_operation** â†' NMP01-LEE-14 (gateway core)

### F
**Fast fail** â†' NMP01-LEE-23 (circuit breaker)  
**Fast path** â†' NMP01-LEE-16 (ZAPH optimization)

### G
**Gateway** â†' NMP01-LEE-14 (core dispatch)

### H
**Home Assistant** â†' NMP01-LEE-17 (API integration)

### J
**JSON logging** â†' NMP01-LEE-03 (structured format)

### L
**Lazy loading** â†' NMP01-LEE-14 (module cache)  
**Logging** â†' NMP01-LEE-03 (function catalog)

### P
**Performance** â†' NMP01-LEE-02 (cache), NMP01-LEE-16 (cold start)  
**Preloading** â†' NMP01-LEE-16 (fast path)

### R
**Resilience** â†' NMP01-LEE-23 (circuit breaker)

### S
**Security** â†' NMP01-LEE-04 (validation, sentinel detection)  
**Sentinel** â†' NMP01-LEE-04 (is_sentinel)  
**Service calls** â†' NMP01-LEE-17 (call_service)

### T
**Token management** â†' NMP01-LEE-17 (HA token caching)

### V
**Validation** â†' NMP01-LEE-04 (entity ID, tokens)

### Z
**ZAPH** â†' NMP01-LEE-16 (fast path pattern)

---

## ðŸš€ QUICK START GUIDES

### Quick Start 1: Understand Gateway (10 minutes)
**Goal:** Understand central dispatch mechanism

**Steps:**
1. Read NMP01-LEE-14 (Gateway Core)
2. Understand execute_operation() flow
3. See lazy import pattern
4. Understand error handling

**Result:** Know how all operations are routed

---

### Quick Start 2: Implement HA Caching (20 minutes)
**Goal:** Add caching for HA entities

**Steps:**
1. Read NMP01-LEE-02 (CACHE catalog)
2. Read NMP01-LEE-17 (HA Core patterns)
3. Use entity state caching pattern
4. Monitor cache hit rates

**Result:** 50-200x speedup for repeated access

---

### Quick Start 3: Optimize Cold Start (30 minutes)
**Goal:** Achieve < 3s cold start

**Steps:**
1. Read NMP01-LEE-16 (Fast Path)
2. Identify Tier 1 interfaces (< 50ms)
3. Implement preload_all()
4. Measure cold start timing

**Result:** Cold start < 300ms

---

### Quick Start 4: Add Circuit Breaker (30 minutes)
**Goal:** Protect against HA API failures

**Steps:**
1. Read NMP01-LEE-23 (Circuit Breaker)
2. Create CircuitBreaker instance
3. Wrap HA API calls
4. Implement fallback strategy

**Result:** Fast fail when HA unavailable

---

## ðŸ"Š DECISION MATRICES

### Matrix 1: When to Use Which Entry?

| Need | Entry | Why |
|------|-------|-----|
| Understand caching | NMP01-LEE-02 | Complete function catalog |
| Call HA API | NMP01-LEE-17 | Authentication + patterns |
| Optimize cold start | NMP01-LEE-16 | ZAPH implementation |
| Add resilience | NMP01-LEE-23 | Circuit breaker pattern |
| Validate input | NMP01-LEE-04 | Security functions |
| Understand gateway | NMP01-LEE-14 | Core dispatch |

---

### Matrix 2: Performance Lookup

| Operation | Entry | Cached | Uncached | Speedup |
|-----------|-------|--------|----------|---------|
| Entity state | NMP01-LEE-17 | < 1ms | 50-200ms | 50-200x |
| Domain list | NMP01-LEE-17 | < 1ms | 100-500ms | 100-500x |
| Gateway operation | NMP01-LEE-14 | < 0.1ms | 5-20ms | 50-200x |
| Circuit breaker | NMP01-LEE-23 | N/A | < 1ms (fast fail) | 50-200x |

---

### Matrix 3: Interface Dependencies

| Interface | Depends On | Entry |
|-----------|-----------|-------|
| LOGGING | None | NMP01-LEE-03 |
| SECURITY | LOGGING | NMP01-LEE-04 |
| CACHE | LOGGING, SECURITY | NMP01-LEE-02 |
| HA Core | CACHE, HTTP, LOGGING, SECURITY | NMP01-LEE-17 |

---

## ðŸ"„ COMMON WORKFLOWS

### Workflow 1: Add HA Feature

```
1. Check NMP01-LEE-17 (HA Core patterns)
   â†' Understand authentication, caching, service calls

2. Use NMP01-LEE-02 (CACHE)
   â†' Cache entity states (5 min TTL)
   â†' Cache domain lists (10 min TTL)

3. Add NMP01-LEE-23 (Circuit Breaker)
   â†' Protect API calls
   â†' Implement fallback

4. Log with NMP01-LEE-03
   â†' Structured JSON logs
   â†' Track performance
```

---

### Workflow 2: Debug Performance Issue

```
1. Check cold start: NMP01-LEE-16
   â†' Is fast path < 300ms?
   â†' Are Tier 1 interfaces preloaded?

2. Check cache: NMP01-LEE-02
   â†' Hit rate > 75%?
   â†' TTL appropriate?

3. Check HA API: NMP01-LEE-17
   â†' Latency < 200ms?
   â†' Circuit breaker working?

4. Check logs: NMP01-LEE-03
   â†' Any errors?
   â†' Performance metrics?
```

---

### Workflow 3: Implement New Interface

```
1. Read generic pattern (INT-##)
   â†' Understand interface purpose
   â†' See operation categories

2. Check existing catalogs
   â†' NMP01-LEE-02 (CACHE example)
   â†' NMP01-LEE-03 (LOGGING example)

3. Follow three-layer pattern
   â†' Gateway wrapper
   â†' Interface router  
   â†' Core implementation

4. Document in NMP01-LEE-##
   â†' Function catalog
   â†' Usage patterns
   â†' Performance characteristics
```

---

## 🔗 RELATED INDEXES

### Project Indexes
- **NMP01-LEE_Index.md** - Complete project index with all planned entries
- **NMP01-LEE-Cross-Reference-Matrix.md** - Relationships to base SIMA

### Base SIMA Indexes
- **NM00-Quick_Index.md** - Generic patterns
- **AWS00-Quick_Index.md** - AWS patterns
- **INT-Quick-Index.md** - Interface patterns

---

## ðŸŽ¯ ONE-LINERS (Ultra-Fast Lookup)

| Entry | One-Liner |
|-------|-----------|
| NMP01-LEE-02 | CACHE functions: set/get/delete/clear with HA entity caching (5min TTL) |
| NMP01-LEE-03 | LOGGING functions: info/error/debug/warning/critical with JSON CloudWatch |
| NMP01-LEE-04 | SECURITY functions: validate/sanitize/is_sentinel for entity/token validation |
| NMP01-LEE-14 | Gateway execute_operation(): lazy import + dispatch + error handling |
| NMP01-LEE-16 | Fast path ZAPH: preload Tier 1/2 interfaces + warm cache < 300ms |
| NMP01-LEE-17 | HA API integration: authentication + caching + entity/service operations |
| NMP01-LEE-23 | Circuit breaker: CLOSED → OPEN (fast fail) → HALF_OPEN (test) → CLOSED |

---

## Keywords

quick-index, NMP01, LEE, SUGA-ISP, fast-lookup, problem-based, keyword-lookup, workflows, decision-matrices, performance, project-specific

---

**END OF FILE**

# File: DEC-13.md

**REF-ID:** DEC-13  
**Category:** Technical Decision  
**Priority:** Medium  
**Status:** Active  
**Date Decided:** 2024-06-12  
**Created:** 2024-06-12  
**Last Updated:** 2025-10-29 (SIMAv4 migration)

---

## 📋 SUMMARY

Cache frequently-called operation routes to bypass dictionary lookups, providing 40% performance improvement for hot operations with automatic enablement after 10 calls.

**Decision:** Automatic fast path caching after 10 calls  
**Impact Level:** Medium  
**Reversibility:** Easy

---

## 🎯 CONTEXT

### Problem Statement
Gateway routing uses dispatch dictionaries for O(1) lookup, but even O(1) has overhead (~100ns per lookup). Profiling revealed some operations (cache_get, log_info) called hundreds of times per request.

### Background
- Dictionary dispatch: ~150ns (5 calls + 2 dict lookups)
- Hot operations: cache_get (~100x/request), log_info (~50x/request)
- Opportunity: Cache function reference after first lookup

### Requirements
- Transparent to users (no API changes)
- Automatic activation
- Minimal memory overhead
- Measurable performance gain

---

## 💡 DECISION

### What We Chose
After 10 calls to an operation, cache the handler function reference to bypass dispatch dictionary lookup.

### Implementation
```python
# gateway_core.py
_fast_path_cache = {}
_operation_call_count = {}

def execute_operation(interface, operation, **kwargs):
    # Check fast path cache first
    cache_key = (interface, operation)
    if cache_key in _fast_path_cache:
        handler = _fast_path_cache[cache_key]
        return handler(**kwargs)  # Direct call!
    
    # Normal path: Load interface router
    router = _get_interface_router(interface)
    handler = router.execute_operation(operation, **kwargs)
    
    # Track calls, add to fast path after threshold
    _operation_call_count[cache_key] = _operation_call_count.get(cache_key, 0) + 1
    if _operation_call_count[cache_key] >= 10:
        _fast_path_cache[cache_key] = handler
    
    return handler
```

### Rationale
1. **Hot Path Optimization**
   - Normal path: ~150ns overhead
   - Fast path: ~90ns overhead
   - Savings: 60ns per call (40% faster)
   - For 100 calls: 6µs total savings

2. **Transparent to Users**
   - Automatic activation
   - No code changes required
   - No configuration needed
   - Pure optimization

3. **Measured Impact**
   - cache_get: 1000ns → 600ns (40% faster)
   - log_info: 800ns → 500ns (38% faster)
   - Memory: ~1KB per cached route (negligible)

4. **Low Threshold**
   - 10 calls = warm container reuse indicator
   - Hot operations reach threshold quickly
   - Cold path operations never cached (no waste)

---

## 📄 ALTERNATIVES CONSIDERED

### Alternative 1: Always Use Fast Path
**Pros:**
- Maximum performance

**Cons:**
- Cache all operations (memory waste)
- Cold operations cached unnecessarily

**Why Rejected:** Wastes memory on cold path operations.

---

### Alternative 2: Manual Fast Path Configuration
**Pros:**
- Developer control

**Cons:**
- Requires knowledge of hot operations
- Manual maintenance
- Easy to misconfigure

**Why Rejected:** Auto-detection better than manual.

---

### Alternative 3: Higher Threshold (100 calls)
**Pros:**
- More selective caching

**Cons:**
- Takes longer to activate
- Less benefit for warm containers

**Why Rejected:** 10 calls is good balance.

---

## ⚖️ TRADE-OFFS

### What We Gained
- 40% faster hot operations
- Automatic optimization
- Zero developer effort
- Minimal memory cost (~1KB per route)

### What We Accepted
- Counter tracking overhead (first 10 calls)
- Small memory for cache and counters
- Slight complexity in gateway routing

---

## 📊 IMPACT ANALYSIS

### Technical Impact
- **Performance:** 40% faster for hot operations
- **Memory:** ~1KB per cached route (10-15 routes typical)
- **Complexity:** Small increase in gateway code
- **Compatibility:** 100% backward compatible

### Operational Impact
- **Monitoring:** Can track cache hit rates
- **Debugging:** Fast path transparent in logs
- **Tuning:** Threshold adjustable if needed

---

## 🔮 FUTURE CONSIDERATIONS

### When to Revisit
- If memory becomes constrained (>100 routes cached)
- If overhead becomes measurable
- Never triggered in 6+ months

### Evolution Path
- Adaptive threshold based on usage patterns
- Preload known hot operations
- Metrics dashboard for cache effectiveness

---

## 🔗 RELATED

### Related Decisions
- DEC-03 - Dispatch Dictionary (foundation for this)
- DEC-14 - Lazy Loading (complementary optimization)

### SIMA Entries
- GATE-01 - Three-File Structure (where fast path lives)
- LESS-02 - Measure Don't Guess (this was measured)

---

## 🏷️ KEYWORDS

`fast-path`, `caching`, `hot-operations`, `performance`, `optimization`, `automatic`, `transparent`

---

## 📝 VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 3.0.0 | 2025-10-29 | Migration | SIMAv4 migration |
| 2.0.0 | 2025-10-23 | System | SIMA v3 format |
| 1.0.0 | 2024-06-12 | Original | Decision made |

---

**END OF DECISION**

**Status:** Active - 10-15 routes cached in typical workload  
**Effectiveness:** 40% improvement for hot operations

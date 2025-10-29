# Architecture Quick Index

**Version:** 1.0.0  
**Purpose:** Fast lookup guide for architecture patterns  
**Last Updated:** 2025-10-29  
**Total Architectures:** 4

---

## 🎯 QUICK LOOKUP BY PROBLEM

### "I have circular import problems"
**Architecture:** ARCH-SUGA (Single Universal Gateway Architecture)  
**Solution:** Three-layer pattern with centralized gateway  
**Key Benefit:** Eliminates circular dependencies completely  
**Implementation Time:** 2-4 weeks for medium system

---

### "Cold start is too slow (serverless)"
**Architecture:** ARCH-LMMS (Lambda Memory Management System)  
**Solution:** Lazy imports, lazy unloads, hot path optimization  
**Key Benefit:** 60-70% faster cold starts, 82% cost reduction  
**Implementation Time:** 1-2 weeks on top of SUGA

---

### "Routing is slow (O(n) if/elif chains)"
**Architecture:** ARCH-DD (Dispatch Dictionary Pattern)  
**Solution:** Dictionary-based O(1) operation dispatch  
**Key Benefit:** 10-25x faster routing, 90% code reduction  
**Implementation Time:** 1-2 days per interface

---

### "Hot path latency is too high"
**Architecture:** ARCH-ZAPH (Zero-Abstraction Path for Hot Operations)  
**Solution:** Pre-computed fast paths for top 10-20% operations  
**Key Benefit:** 97% faster (32x) for hot operations  
**Implementation Time:** 2-3 days after profiling

---

## 📋 ARCHITECTURE SUMMARY TABLE

| REF-ID | Name | Type | Primary Benefit | Applies To |
|--------|------|------|-----------------|------------|
| **ARCH-SUGA** | Single Universal Gateway | Structural | Eliminates circular dependencies | Any modular system |
| **ARCH-LMMS** | Lambda Memory Management | Performance | 60-70% faster cold starts | Serverless environments |
| **ARCH-DD** | Dispatch Dictionary | Routing | O(1) routing, 90% code reduction | Any routing scenario |
| **ARCH-ZAPH** | Zero-Abstraction Path | Performance | 97% faster hot operations | High-throughput systems |

---

## 🔑 KEYWORDS TO ARCHITECTURE MAP

### A-C
- **Abstraction overhead** → ARCH-ZAPH
- **AWS Lambda** → ARCH-LMMS
- **Circular dependencies** → ARCH-SUGA
- **Cold start** → ARCH-LMMS
- **Cost optimization** → ARCH-LMMS (82% reduction)

### D-G
- **Dependencies** → ARCH-SUGA
- **Dispatch** → ARCH-DD
- **Fast path** → ARCH-ZAPH
- **Gateway** → ARCH-SUGA
- **Google Cloud Functions** → ARCH-LMMS

### H-L
- **Hot operations** → ARCH-ZAPH
- **if/elif chains** → ARCH-DD (replaces with O(1) dictionary)
- **Import errors** → ARCH-SUGA (prevents circular imports)
- **Latency** → ARCH-ZAPH (reduces by 97%)
- **Lazy loading** → ARCH-LMMS (LIGS subsystem)

### M-R
- **Memory optimization** → ARCH-LMMS (70-82% reduction)
- **O(1) routing** → ARCH-DD
- **Operation routing** → ARCH-DD
- **Performance** → ARCH-LMMS or ARCH-ZAPH (depending on bottleneck)
- **Routing** → ARCH-DD

### S-Z
- **Serverless** → ARCH-LMMS
- **Three-layer architecture** → ARCH-SUGA
- **Unloading** → ARCH-LMMS (LUGS subsystem)
- **Zero-abstraction** → ARCH-ZAPH

---

## 🎓 LEARNING ORDER

### Beginner: Start Here
1. **ARCH-SUGA** - Foundation pattern for clean architecture
2. **ARCH-DD** - Routing pattern used by SUGA interfaces

**Time Investment:** 4-6 hours reading + 1-2 weeks implementing  
**When Ready:** You can build clean, maintainable systems

---

### Intermediate: Add Performance
3. **ARCH-LMMS** - Serverless optimization (if applicable)
4. **ARCH-ZAPH** - Hot path optimization (if needed)

**Time Investment:** 3-4 hours reading + 1-2 weeks implementing  
**When Ready:** You can optimize critical performance bottlenecks

---

## 🚀 QUICK START GUIDES

### For New Projects

**Minimal Setup (Day 1-7):**
1. Implement ARCH-SUGA structure
   - Create gateway.py (entry point)
   - Create interface_*.py (routing layer)
   - Create *_core.py (implementation layer)
2. Use ARCH-DD in all interfaces
   - Create _OPERATION_DISPATCH dictionaries
   - Implement execute_{interface}_operation functions

**Result:** Clean, working system

**Optimization (Day 8-14, if needed):**
3. If serverless: Add ARCH-LMMS
   - Convert to function-level imports (LIGS)
   - Profile and confirm improvement
4. If high-throughput: Add ARCH-ZAPH
   - Profile to identify hot operations
   - Add hot path for top 3-5 operations

**Result:** Optimized system

---

### For Existing Projects

**Assessment (Week 1):**
1. Check for circular dependencies → Need ARCH-SUGA
2. Profile routing performance → Need ARCH-DD if O(n)
3. Measure cold start (if serverless) → Need ARCH-LMMS if > 500ms
4. Profile hot path latency → Need ARCH-ZAPH if > 100ms

**Implementation (Week 2-4):**
1. Refactor to ARCH-SUGA (if needed)
2. Convert routing to ARCH-DD (if needed)
3. Add ARCH-LMMS (if serverless and slow)
4. Add ARCH-ZAPH (if hot path slow)

**Result:** Optimized existing system

---

## 📊 DECISION MATRIX

### Question-Based Selection

**Q1: Do you have circular import errors?**
- Yes → Use **ARCH-SUGA**
- No → Continue to Q2

**Q2: Is routing performance O(n)?**
- Yes → Use **ARCH-DD**
- No → Continue to Q3

**Q3: Is this serverless with cold start issues?**
- Yes → Use **ARCH-LMMS**
- No → Continue to Q4

**Q4: Do hot operations need < 100ms latency?**
- Yes → Use **ARCH-ZAPH**
- No → Done, no optimization needed

---

## 🎯 COMMON SCENARIOS

### Scenario 1: New Web API (Not Serverless)
**Architectures:** ARCH-SUGA + ARCH-DD  
**Skip:** ARCH-LMMS (not serverless)  
**Maybe:** ARCH-ZAPH (if high throughput)

---

### Scenario 2: AWS Lambda Function
**Architectures:** ARCH-SUGA + ARCH-DD + ARCH-LMMS  
**Result:** 60-70% faster cold start, 82% cost reduction

---

### Scenario 3: Real-Time Trading System
**Architectures:** ARCH-SUGA + ARCH-DD + ARCH-ZAPH  
**Skip:** ARCH-LMMS (not serverless)  
**Result:** Sub-millisecond hot path latency

---

### Scenario 4: Cost-Critical Serverless App
**Architectures:** All 4 (SUGA + DD + LMMS + ZAPH)  
**Result:** Maximum optimization (82% cost reduction + 97% hot path speedup)

---

## 📈 EXPECTED METRICS

### After Implementing ARCH-SUGA
- Circular dependencies: **0** (from variable)
- Import errors: **0** (from variable)
- Testability: **High** (mockable gateway)
- Maintainability: **High** (clear structure)

---

### After Implementing ARCH-DD
- Routing performance: **O(1)** (from O(n))
- Routing code: **-90%** lines (compared to if/elif)
- Time to add operation: **30 seconds** (add 1 dict entry)

---

### After Implementing ARCH-LMMS
- Cold start time: **-60-70%** (e.g., 850ms → 320ms)
- Initial memory: **-70%** (e.g., 180MB → 55MB)
- Runtime memory: **-82%** (after LUGS, e.g., 180MB → 32MB)
- GB-seconds: **-82%** (cost savings)

---

### After Implementing ARCH-ZAPH
- Hot path latency: **-97%** (e.g., 800ns → 25ns)
- Hot operations: **3-5** optimized (top 20% by count)
- Traffic optimized: **80-90%** (due to access frequency)
- Cold path impact: **< 5%** overhead

---

## 🔍 ARCHITECTURE COMPARISON

### Structural vs Performance

**Structural Architectures:**
- **ARCH-SUGA**: System organization, dependency management
- **ARCH-DD**: Routing organization, extensibility

**Performance Architectures:**
- **ARCH-LMMS**: Cold start, memory, serverless optimization
- **ARCH-ZAPH**: Hot path latency, throughput optimization

---

### Required vs Optional

**Required (for clean architecture):**
- **ARCH-SUGA**: Prevents circular dependencies
- **ARCH-DD**: Enables scalable routing

**Optional (for optimization):**
- **ARCH-LMMS**: Only if serverless cold start is problem
- **ARCH-ZAPH**: Only if hot path latency is problem

---

## 🛠️ IMPLEMENTATION CHECKLIST

### ARCH-SUGA Implementation
- [ ] Create gateway.py entry point
- [ ] Create gateway_core.py router
- [ ] Create gateway_wrappers.py helpers
- [ ] Create interface_*.py for each domain
- [ ] Create *_core.py for each domain
- [ ] All cross-component calls go through gateway
- [ ] No circular dependencies remain

---

### ARCH-DD Implementation
- [ ] Create _OPERATION_DISPATCH dictionary per interface
- [ ] Convert if/elif chains to dictionary lookups
- [ ] Handlers are private (_execute_* naming)
- [ ] Explicit errors for unknown operations
- [ ] Dictionary defined at module level
- [ ] Consistent pattern across all interfaces

---

### ARCH-LMMS Implementation
- [ ] Convert to function-level imports (LIGS)
- [ ] Profile cold start improvement
- [ ] Add usage tracking (LUGS)
- [ ] Implement unload logic (LUGS)
- [ ] Profile memory reduction
- [ ] Identify hot operations (ZAPH)
- [ ] Implement hot paths (ZAPH)
- [ ] Profile hot path speedup

---

### ARCH-ZAPH Implementation
- [ ] Profile to identify hot operations
- [ ] Build Tier 1 hot path (top 3 operations)
- [ ] Implement hot path router
- [ ] Maintain fallback to normal path
- [ ] Verify correctness (hot = cold functionality)
- [ ] Measure speedup (should be ~97%)
- [ ] Add Tier 2 if beneficial (next 7 operations)
- [ ] Implement periodic hot path updates

---

## 📞 SUPPORT MATRIX

### "Which architecture solves X?"

| Problem | Architecture | Time to Implement |
|---------|-------------|------------------|
| Circular dependencies | ARCH-SUGA | 2-4 weeks |
| Slow routing (O(n)) | ARCH-DD | 1-2 days |
| Slow cold start | ARCH-LMMS | 1-2 weeks |
| High hot path latency | ARCH-ZAPH | 2-3 days |
| High costs (serverless) | ARCH-LMMS | 1-2 weeks |
| Low throughput | ARCH-ZAPH | 2-3 days |

---

## 🎯 ONE-LINER SUMMARIES

### When to Use (One Sentence Each)

**ARCH-SUGA:** Use when you have or want to prevent circular dependencies between components.

**ARCH-DD:** Use when you need to route string operations to handlers with O(1) performance.

**ARCH-LMMS:** Use in serverless environments when cold start time or memory usage is a problem.

**ARCH-ZAPH:** Use when your system has identifiable hot operations (top 20% = 80%+ traffic) that need sub-millisecond latency.

---

## 📚 FURTHER READING

### Full Documentation
- **ARCH-SUGA:** `/sima/entries/architectures/ARCH-SUGA.md`
- **ARCH-DD:** `/sima/entries/architectures/ARCH-DD.md`
- **ARCH-LMMS:** `/sima/entries/architectures/ARCH-LMMS.md`
- **ARCH-ZAPH:** `/sima/entries/architectures/ARCH-ZAPH.md`

### Cross-References
- **Architecture Cross-Reference Matrix:** See relationships and combinations
- **Architecture Master Index:** Complete navigation

### Related Topics
- Constraints: Platform limitations affecting architectures
- Combinations: How architectures combine in projects
- Lessons: Real-world experiences using architectures
- Decisions: Why these architectures were chosen

---

**END OF QUICK INDEX**

**Version:** 1.0.0  
**Total Architectures:** 4  
**Last Updated:** 2025-10-29  
**Maintained By:** SIMA v4 Documentation

# Gateway Patterns Quick Index
# File: Gateway-Quick-Index.md

**Version:** 1.0.0  
**Created:** 2025-10-29  
**Last Updated:** 2025-10-29  
**Purpose:** Fast lookup for gateway patterns

---

## 🎯 QUICK LOOKUP BY PROBLEM

### Problem 1: "How do I organize my gateway?"
**Answer:** GATE-01 (Gateway Layer Structure)

**Quick Facts:**
- Three files: gateway.py, gateway_core.py, gateway_wrappers.py
- gateway.py: Exports only
- gateway_core.py: Routing only
- gateway_wrappers.py: Helper functions only

**One-Liner:** Use three-file structure for clear organization.

---

### Problem 2: "My cold start is too slow"
**Answer:** GATE-02 (Lazy Import Pattern)

**Quick Facts:**
- Import interfaces inside functions, not at module level
- 60-70% cold start reduction
- Pay-per-use memory model

**One-Liner:** Import interfaces at function level, not module level.

---

### Problem 3: "I have circular import errors"
**Answer:** GATE-03 (Cross-Interface Communication Rule)

**Quick Facts:**
- All cross-interface calls MUST use gateway
- Never import other interfaces directly
- Mathematically prevents cycles

**One-Liner:** Cross-interface via gateway, never direct imports.

---

### Problem 4: "Gateway API is too complex"
**Answer:** GATE-04 (Gateway Wrapper Functions)

**Quick Facts:**
- Create simple wrappers for common operations
- Provide defaults and parameter transformation
- Comprehensive documentation

**One-Liner:** Wrap common operations in simple, well-documented functions.

---

### Problem 5: "When can I use direct imports?"
**Answer:** GATE-05 (Intra vs Cross-Interface Imports)

**Quick Facts:**
- Same interface: Direct imports (efficient)
- Different interface: Via gateway (safe)
- Check file prefix to determine interface

**One-Liner:** Direct within interface, gateway between interfaces.

---

## 📊 PATTERN SUMMARY TABLE

| REF-ID | Problem Solved | Key Rule | Benefit |
|--------|----------------|----------|---------|
| GATE-01 | Organization | 3-file structure | Clear boundaries |
| GATE-02 | Performance | Function-level imports | 60-70% faster cold start |
| GATE-03 | Circular deps | Gateway-only cross-interface | Zero circular errors |
| GATE-04 | Complexity | Wrapper functions | Clean API |
| GATE-05 | Optimization | Direct intra-interface | 13x faster calls |

---

## 🔤 KEYWORDS TO PATTERNS

### A-C
- **API Design** → GATE-04 (Wrappers)
- **Architecture** → GATE-01 (Structure), GATE-03 (Communication)
- **Boundaries** → GATE-03 (Cross-interface), GATE-05 (Intra vs cross)
- **Circular Dependencies** → GATE-03 (Prevention)
- **Cold Start** → GATE-02 (Lazy imports)
- **Complexity** → GATE-04 (Wrappers simplify)
- **Cross-Interface** → GATE-03 (Rule), GATE-05 (Distinction)

### D-F
- **Dependencies** → GATE-03 (Management)
- **Developer Experience** → GATE-04 (Wrappers)
- **Direct Imports** → GATE-05 (When allowed)
- **Efficiency** → GATE-02 (Lazy), GATE-05 (Direct intra)
- **Entry Point** → GATE-01 (gateway.py)
- **Files** → GATE-01 (Three-file structure)
- **Function-Level Imports** → GATE-02 (Lazy loading)

### G-L
- **Gateway Core** → GATE-01 (gateway_core.py)
- **Gateway Wrappers** → GATE-01 (gateway_wrappers.py), GATE-04 (Pattern)
- **Imports** → GATE-02 (Lazy), GATE-03 (Cross), GATE-05 (Intra vs cross)
- **Interface Boundaries** → GATE-03 (Communication), GATE-05 (Distinction)
- **Intra-Interface** → GATE-05 (Direct imports allowed)
- **Lazy Loading** → GATE-02 (Pattern)

### M-P
- **Memory** → GATE-02 (Pay-per-use)
- **Module-Level Imports** → GATE-02 (Avoid)
- **Organization** → GATE-01 (Structure)
- **Optimization** → GATE-02 (Cold start), GATE-05 (Hot path)
- **Performance** → GATE-02 (Lazy), GATE-05 (Direct intra)

### Q-S
- **Routing** → GATE-01 (gateway_core.py)
- **Safety** → GATE-03 (Prevents cycles)
- **Serverless** → GATE-02 (Critical for Lambda)
- **Structure** → GATE-01 (Organization)

### T-Z
- **Three Files** → GATE-01 (Structure)
- **Usability** → GATE-04 (Wrappers)
- **Wrappers** → GATE-04 (Pattern)

---

## 🚀 QUICK START GUIDES

### Quick Start 1: New Gateway Project

```markdown
1. Create structure (GATE-01)
   - gateway.py (exports)
   - gateway_core.py (routing)
   - gateway_wrappers.py (helpers)

2. Enforce cross-interface rule (GATE-03)
   - All interfaces import gateway only
   - No direct cross-interface imports

3. Add lazy loading (GATE-02)
   - Import interfaces inside functions
   - Test cold start improvement

4. Create wrappers (GATE-04)
   - Wrap 10-20 most common operations
   - Document well

Time: 1-2 weeks
Result: Production-ready gateway
```

---

### Quick Start 2: Migrate Existing Code

```markdown
1. Audit current imports
   - Find all cross-interface direct imports
   - Identify interface boundaries

2. Create gateway structure (GATE-01)
   - Move exports to gateway.py
   - Move routing to gateway_core.py
   - Move helpers to gateway_wrappers.py

3. Replace direct imports (GATE-03)
   - Change all cross-interface to gateway
   - Keep intra-interface direct (GATE-05)

4. Measure improvements
   - Cold start time
   - Code reduction
   - Import errors

Time: 2-4 weeks
Result: Migrated to gateway pattern
```

---

### Quick Start 3: Optimize Existing Gateway

```markdown
1. Measure current performance
   - Cold start time
   - Hot path performance

2. Add lazy loading (GATE-02)
   - Convert module-level to function-level
   - Measure cold start improvement

3. Optimize intra-interface (GATE-05)
   - Change intra-interface to direct imports
   - Measure hot path improvement

4. Improve wrappers (GATE-04)
   - Add missing wrappers
   - Better defaults
   - More documentation

Time: 1-2 weeks
Result: Optimized gateway
```

---

## 🎓 LEARNING ORDER

### Path 1: Beginner (Understand Basics)
1. **Week 1:** GATE-01 (Structure)
   - Learn three-file organization
   - Understand each file's role
   - Create basic gateway

2. **Week 1:** GATE-03 (Cross-interface)
   - Understand circular dependency problem
   - Learn gateway-only rule
   - Apply in code reviews

**Goal:** Safe, organized gateway

---

### Path 2: Intermediate (Add Optimization)
1. **Week 2:** GATE-02 (Lazy imports)
   - Understand cold start problem
   - Implement lazy loading
   - Measure improvement

2. **Week 2-3:** GATE-04 (Wrappers)
   - Create convenience functions
   - Add defaults
   - Document well

**Goal:** Fast, usable gateway

---

### Path 3: Advanced (Master Patterns)
1. **Week 3:** GATE-05 (Intra vs cross)
   - Profile hot paths
   - Optimize intra-interface
   - Balance safety vs performance

2. **Week 4:** Integration
   - Combine all patterns
   - Teach team
   - Create guidelines

**Goal:** Expert-level gateway implementation

---

## 🎯 DECISION MATRICES

### Matrix 1: Which Pattern Do I Need?

**Q1: Do I have a gateway?**
- No → Start with GATE-01
- Yes → Q2

**Q2: Do I have circular import errors?**
- Yes → Apply GATE-03
- No → Q3

**Q3: Is cold start > 500ms?**
- Yes → Apply GATE-02
- No → Q4

**Q4: Is API too complex?**
- Yes → Apply GATE-04
- No → Q5

**Q5: Are hot paths slow?**
- Yes → Apply GATE-05
- No → Done!

---

### Matrix 2: Can I Import X Directly?

**Q1: Same file?**
- Yes → Direct call (no import)
- No → Q2

**Q2: Same interface?**
- Yes → Direct import (from X import Y)
- No → Q3

**Q3: Entry point?**
- Yes → Import gateway only
- No → Q4

**Q4: Core module?**
- Yes → Import gateway for cross-interface
- No → Consult GATE patterns

---

### Matrix 3: How Many Patterns Do I Need?

**Scenario A: Solo dev, small project**
- Minimum: GATE-01 + GATE-03
- Recommended: Add GATE-04

**Scenario B: Team, serverless**
- Minimum: GATE-01 + GATE-02 + GATE-03
- Recommended: Add GATE-04 + GATE-05

**Scenario C: Large team, complex system**
- Minimum: All 5 patterns
- Recommended: All 5 + custom patterns

**Scenario D: Performance-critical**
- Minimum: GATE-01 + GATE-02 + GATE-05
- Recommended: Add GATE-03

---

## 📊 COMMON SCENARIOS

### Scenario 1: "I need logging in my cache module"

**Question:** How do I add logging to cache_core.py?

**Answer:** Use gateway (GATE-03)

**Code:**
```python
# cache_core.py
import gateway

def cache_set(key, value):
    gateway.log_info(f"Cache set: {key}")  # Via gateway
    _CACHE_STORE[key] = value
```

**Why:** Logging is different interface from cache.

---

### Scenario 2: "My cache helper needs cache validation"

**Question:** How do I call validation from helper?

**Answer:** Direct import (GATE-05)

**Code:**
```python
# cache_helper.py
from cache_validation import validate_key  # Direct

def safe_cache_set(key, value):
    validate_key(key)  # Same interface, direct call
    # ...
```

**Why:** Both are in cache interface.

---

### Scenario 3: "Cold start is 2 seconds"

**Question:** How do I fix cold start?

**Answer:** Lazy loading (GATE-02)

**Code:**
```python
# gateway_wrappers.py
def cache_get(key):
    import interface_cache  # Function-level import!
    return interface_cache.execute_operation('get', {'key': key})
```

**Why:** Defers loading until first use.

---

### Scenario 4: "Gateway API is too verbose"

**Question:** How do I simplify usage?

**Answer:** Wrappers (GATE-04)

**Code:**
```python
# Before
result = gateway_core.execute_operation('cache', 'get', {
    'key': 'user_123',
    'default': None
})

# After (with wrapper)
result = cache_get('user_123')  # Simple!
```

**Why:** Wrappers hide complexity.

---

## 📈 EXPECTED METRICS

### By Pattern

**GATE-01 (Structure):**
- Merge conflicts: -80%
- Code organization: Clear
- Team velocity: +20%

**GATE-02 (Lazy Loading):**
- Cold start: -60-70%
- Memory initial: -60%
- First-call overhead: +0-200ms (one-time)

**GATE-03 (Cross-Interface):**
- Circular imports: 0
- Import errors: 0
- Architecture violations: 0

**GATE-04 (Wrappers):**
- Application code: -50-70%
- API complexity: Low
- Developer onboarding: Faster

**GATE-05 (Intra vs Cross):**
- Hot path calls: 13x faster
- Intra-interface: ~10ns vs ~130ns
- Hot loop (1000 calls): 120µs savings

---

## 🔗 REFERENCE SUMMARY

### Pattern Files
- GATE-01: `GATE-01_Gateway-Layer-Structure.md`
- GATE-02: `GATE-02_Lazy-Import-Pattern.md`
- GATE-03: `GATE-03_Cross-Interface-Communication-Rule.md`
- GATE-04: `GATE-04_Gateway-Wrapper-Functions.md`
- GATE-05: `GATE-05_Intra-Interface-vs-Cross-Interface-Imports.md`

### Support Documents
- Cross-Reference: `Gateway-Cross-Reference-Matrix.md`
- Quick Index: `Gateway-Quick-Index.md` (this file)

### Related Architecture
- ARCH-SUGA: Uses all gateway patterns
- ARCH-LMMS: Uses GATE-02 (lazy loading)
- ARCH-DD: Used by GATE-01 (dispatch dictionary in gateway_core)

---

## 🎯 ONE-LINER SUMMARIES

**GATE-01:** Three files (gateway.py, gateway_core.py, gateway_wrappers.py) for clear organization.

**GATE-02:** Import interfaces inside functions for 60-70% faster cold start.

**GATE-03:** Cross-interface calls via gateway only to prevent circular dependencies.

**GATE-04:** Create simple wrapper functions with good defaults and documentation.

**GATE-05:** Direct imports within interface (fast), gateway between interfaces (safe).

---

## 📞 SUPPORT MATRIX

| Need Help With | See Pattern | Key File |
|----------------|-------------|----------|
| File organization | GATE-01 | All |
| Cold start optimization | GATE-02 | gateway_core.py |
| Import errors | GATE-03 | All interfaces |
| API simplification | GATE-04 | gateway_wrappers.py |
| Performance tuning | GATE-05 | Interface cores |

---

## 🤝 CONTRIBUTORS

**Document Author:** SIMAv4 Phase 2.0  
**Pattern Authors:** SUGA-ISP Project Team  
**Last Updated:** 2025-10-29

---

## 📝 CHANGE LOG

### [1.0.0] - 2025-10-29
- Initial quick index
- 5 gateway patterns indexed
- Multiple access paths (problem, keyword, scenario)
- Quick start guides
- Decision matrices

---

**END OF QUICK INDEX**

**Patterns Indexed:** 5  
**Access Methods:** 4 (problem, keyword, scenario, decision)  
**Quick Starts:** 3  
**Decision Matrices:** 3  
**Average Lookup Time:** < 30 seconds

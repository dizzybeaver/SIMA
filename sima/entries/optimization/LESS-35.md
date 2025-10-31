# LESS-35.md

# Throughput Scales with Complexity Understanding

**REF-ID:** LESS-35  
**Category:** Lessons → Optimization  
**Priority:** 🟡 HIGH  
**Created:** 2025-10-30  
**Status:** Active

---

## Summary

With pattern mastery, throughput scales based on understanding component complexity—4 simple components complete in the same time as 2 complex ones. Key: accurate complexity assessment enables optimal batching.

---

## Context

**Universal Pattern:**
Initial work suggests fixed throughput (e.g., "2 per session"). Experience reveals throughput varies dramatically based on component complexity and pattern familiarity.

**Why This Matters:**
Understanding complexity-throughput relationship enables accurate planning, optimal batching, and maximum efficiency without quality trade-offs.

---

## Content

### The Complexity-Throughput Relationship

**Observed Pattern:**

| Complexity | Components/Session | Time/Component | Total Time |
|------------|-------------------|----------------|------------|
| High | 1 | 2h | 2h |
| Medium-High | 2 | 1h | 2h |
| Medium | 3-4 | 0.5-0.67h | 2h |
| Low | 4-5 | 0.4-0.5h | 2h |

**Key Insight:** Session time stays constant (~2h), but component count varies 5× based on complexity.

### Why Throughput Varies

**High Complexity (1 component):**
- New patterns to learn
- Unknown violation patterns
- Complex dependencies
- Deep analysis required
- Custom solutions needed
- 2 hours fully consumed

**Medium Complexity (3-4 components):**
- Known patterns applied
- Expected violations (quick fixes)
- Standard dependencies
- Template-based implementation
- Batch similar work
- 30-40 min per component

**Key Difference:** Complexity determines work expansion, not skill level.

### Complexity Assessment Factors

**Component Complexity Score:**

```
Score = Novelty + Dependencies + Violations + State_Mgmt

Novelty (0-3):
- 0: Pure template copy
- 1: Template with minor variations
- 2: Some new patterns needed
- 3: Novel architecture required

Dependencies (0-3):
- 0: Self-contained
- 1: Standard dependencies only
- 2: External service integrations
- 3: Complex cross-system dependencies

Violations (0-2):
- 0: Expected to be compliant
- 1: Known violations (threading locks)
- 2: Unknown violation count/type

State Management (0-2):
- 0: Stateless operations
- 1: Simple state (cache, counters)
- 2: Complex state (transactions, consistency)

Total Score:
- 0-2: Low complexity (4-5 per session)
- 3-5: Medium complexity (3-4 per session)
- 6-7: Medium-High complexity (2 per session)
- 8-10: High complexity (1 per session)
```

### Batching Strategy

**Optimal Batching:**

```
IF all components score 0-2:
    batch 4-5 together
    sustained flow state possible
    minimal context switching

ELIF all components score 3-5:
    batch 3-4 together
    similar patterns enable efficiency
    moderate focus needed

ELIF all components score 6-7:
    batch 2 together
    different enough to require reset
    high focus per component

ELIF any component scores 8-10:
    do alone
    unique patterns require full attention
    no batching possible
```

**Anti-Pattern:** Batching high + low complexity together
- Context switching overhead
- Can't sustain flow state
- High component blocks simple ones
- Wastes capacity

### Real Examples

**Good Batching (Medium Complexity):**
```
Session: 4 infrastructure components
- All score 4 (medium complexity)
- All infrastructure-level
- All have threading locks (known violation)
- All use same templates

Result: 4 completed in 2 hours
Throughput: 4 components
Efficiency: High (sustained flow)
```

**Poor Batching (Mixed Complexity):**
```
Session: 2 components
- Component 1: Score 9 (novel architecture)
- Component 2: Score 2 (template copy)

Result: 2.5 hours total
- Component 1: 2 hours (deserved)
- Component 2: 30 min (should have been batched with others)

Efficiency: Low (wasted capacity)
```

### Throughput Optimization

**Pre-Session:**
1. **Assess all components** (5 min total)
2. **Score complexity** (use formula above)
3. **Group by score** (similar complexity together)
4. **Plan batches** (2h sessions)
5. **Communicate scope** (set expectations)

**During Session:**
```
IF ahead of schedule and have similar-complexity component:
    add to batch (opportunity!)
    
IF behind schedule:
    complete current batch only
    don't force additional work
    
IF unexpected complexity:
    reduce batch size immediately
    quality over throughput
```

**Post-Session:**
```
Track: Estimated complexity vs Actual complexity
Calibrate: Adjust scoring for future sessions
Learn: Which factors most predictive
Improve: Refine batching strategy
```

### Key Metrics to Track

**Batch Efficiency:**
```
Efficiency = Actual_Throughput / Expected_Throughput

Expected (by complexity score):
- Score 0-2: 4-5 components
- Score 3-5: 3-4 components
- Score 6-7: 2 components
- Score 8-10: 1 component

If efficiency < 0.8: Complexity underestimated
If efficiency > 1.2: Complexity overestimated
```

**Sweet Spot Identification:**
```
Track which complexity scores have highest efficiency:
- Usually score 3-5 (medium complexity)
- Known patterns + manageable scope
- Not trivial, not overwhelming
- Optimal flow state achievable
```

### Application Guidelines

**For Planning:**
- Always assess complexity before batching
- Batch similar complexity together
- Don't mix high + low in same session
- Reserve separate sessions for high complexity
- Use pattern mastery to enable batching

**For Execution:**
- Start with lowest complexity in batch (warm-up)
- End with highest complexity (peak focus)
- Take breaks between different patterns
- Maintain quality gates for all complexities
- Don't sacrifice quality for throughput

**For Estimation:**
```
Project estimate = Sum(component_estimates_by_complexity)

Where component_estimate:
- Low (score 0-2): 0.4h × mastery_factor
- Medium (score 3-5): 0.6h × mastery_factor
- Medium-High (score 6-7): 1.0h × mastery_factor
- High (score 8-10): 2.0h × mastery_factor

Mastery_factor:
- Learning phase: 1.0×
- Applying phase: 0.75×
- Mastery phase: 0.5×
```

### Key Insights

**Insight 1:**
Throughput varies 5× (1 to 5 components per session) based on complexity, not skill. Assess accurately to plan realistically.

**Insight 2:**
Batching works ONLY for similar complexity. Mixing high + low wastes capacity and disrupts flow.

**Insight 3:**
With pattern mastery, medium complexity (score 3-5) achieves highest throughput—3-4 components sustainably.

---

## Related Topics

- **LESS-26**: Session size based on complexity
- **LESS-28**: Pattern mastery accelerates development
- **LESS-37**: Muscle memory after ~10 applications
- **LESS-40**: Velocity and quality both improve
- **LESS-43**: Estimation adjusts with complexity understanding
- **LESS-50**: Starting points vary dramatically

---

## Usage Guidelines

**When to Apply:**
- Multi-component planning
- Sprint planning
- Resource allocation
- Velocity forecasting
- Team capacity planning

**How to Use:**
1. Score component complexity (0-10 scale)
2. Group similar scores together
3. Plan batches by complexity
4. Track actual vs expected
5. Calibrate scoring over time

---

## Keywords

throughput-optimization, complexity-assessment, batching-strategy, velocity-planning, flow-state, capacity-planning, efficiency-metrics

---

## Version History

- **2025-10-30**: Migrated to SIMAv4 format, split from LESS-26-35 (Phase 10.3 Session 4)
- **2025-10-25**: Created - Combined LESS-26 and LESS-35 insights

---

**File:** `/sima/entries/lessons/optimization/LESS-35.md`  
**Lines:** ~320  
**Status:** Complete  
**Next:** LESS-37

---

**END OF DOCUMENT**

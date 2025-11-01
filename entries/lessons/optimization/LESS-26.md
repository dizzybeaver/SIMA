# LESS-26.md

# Session Size Based on Complexity

**REF-ID:** LESS-26  
**Category:** Lessons → Optimization  
**Priority:** 🟡 HIGH  
**Created:** 2025-10-30  
**Status:** Active

---

## Summary

Session size should be based on component complexity, not fixed numbers. With pattern mastery, 2 interfaces per session is optimal for medium-high complexity, but 4 moderate-complexity components can be completed in the same time frame.

---

## Context

**Universal Pattern:**
Early sessions suggested "2 interfaces per session" as optimal, but later sessions revealed this depends on complexity. With pattern mastery, session size scales with component difficulty.

**Why This Matters:**
Fixed session sizes waste capacity on simple components or overload on complex ones. Complexity-based planning optimizes throughput without sacrificing quality.

---

## Content

### The Discovery

**Initial Finding:**
- 2 interfaces in 2 hours (medium-high complexity)
- With violations present (threading locks)
- Conclusion: "2 interfaces per session is optimal"

**Later Refinement:**
- 4 interfaces in 2 hours (medium complexity)
- Similar infrastructure components
- Pattern mastery achieved
- Conclusion: "4 interfaces possible for moderate complexity"

**Key Insight:**
The initial finding was correct FOR THAT COMPLEXITY LEVEL. Later work revealed the real variable: complexity, not count.

### Complexity-Based Session Planning

| Component Complexity | Interfaces Per Session | Time | Per Interface |
|---------------------|------------------------|------|---------------|
| **High** (new pattern, critical) | 1 | 1.5-2h | 1.5-2h |
| **Medium-High** (some unknowns) | 2 | 2h | 1h |
| **Medium** (known pattern) | 3-4 | 2h | 0.5-0.67h |
| **Low** (simple, template) | 4-5 | 2h | 0.4-0.5h |

### Factors Determining Complexity

**Component Characteristics:**

**High Complexity:**
- New architectural pattern
- Critical system dependencies
- Complex state management
- Unknown violation count
- Requires deep analysis

**Medium-High Complexity:**
- Known pattern, some variations
- Expected violations (threading locks)
- Moderate dependencies
- Standard architecture

**Medium Complexity:**
- Well-understood pattern
- Similar to previous work
- Infrastructure-level (predictable)
- Template-based implementation
- Expected violations minimal

**Low Complexity:**
- Pure template application
- No violations expected
- Minimal dependencies
- Routine implementation

### Real Examples

**Medium-High Complexity Session:**
```
Component 1:
- External service integration
- Network complexity
- No violations (faster)
- 45 min

Component 2:
- Infrastructure pattern
- Threading violations found
- Fix required
- 75 min

Total: 2 interfaces, 2 hours
Complexity: Medium-High
```

**Medium Complexity Session:**
```
Component 1:
- Infrastructure
- Threading violation (expected)
- Quick fix
- 30 min

Component 2:
- Bootstrap logic
- Threading violation (expected)
- Quick fix
- 30 min

Component 3:
- Cross-cutting concerns
- Threading violation (expected)
- Quick fix
- 30 min

Component 4:
- Consolidation work
- No violations
- Template-based
- 30 min

Total: 4 interfaces, 2 hours
Complexity: Medium (infrastructure, similar pattern)
```

### Session Structure Examples

**2-Interface Session (Medium-High):**
```
0:00 - 0:05  Context loading (5 min)
0:05 - 0:55  Interface 1 (50 min)
0:55 - 1:00  Break (5 min)
1:00 - 1:50  Interface 2 (50 min)
1:50 - 2:00  Wrap-up (10 min)
Total: 2 hours, 2 interfaces
```

**4-Interface Session (Medium):**
```
0:00 - 0:05  Context loading (5 min)
0:05 - 0:35  Interface 1 (30 min)
0:35 - 1:05  Interface 2 (30 min)
1:05 - 1:10  Break (5 min)
1:10 - 1:40  Interface 3 (30 min)
1:40 - 2:10  Interface 4 (30 min)
2:10 - 2:15  Wrap-up (5 min)
Total: 2 hours, 4 interfaces
```

### Why 4-Component Sessions Possible

**Pattern Mastery:**
- After 8 interfaces, pattern automatic
- No reference lookups needed
- Decisions instant
- Template execution fast

**Component Similarity:**
- All infrastructure-level
- All had same violation (threading locks)
- All same architecture pattern
- All used same template

**Expected Surprises:**
- Threading locks anticipated
- Fix procedure known
- No unknown issues
- Smooth execution

**Sustained Flow:**
- No context switching
- Similar mental model
- Repetitive pattern
- Muscle memory active

### Planning Guidelines

**Pre-Session Assessment:**

```
For each component:
1. Assess complexity (High/Medium-High/Medium/Low)
2. Check for known violations
3. Estimate dependencies
4. Review architectural patterns

Session planning:
IF all High: 1 per session
ELIF mix High+Medium-High: 1-2 per session
ELIF all Medium-High: 2 per session
ELIF all Medium: 3-4 per session
ELIF all Low: 4-5 per session
```

**During Session:**

```
IF ahead of schedule:
    Consider adding similar component
    
IF behind schedule:
    Don't force completion
    Better to do 1 well than 2 poorly
    
IF unexpected complexity:
    Reduce scope immediately
    Quality over quantity
```

### Key Factors for Multi-Component Sessions

**✅ Enables 3-4 in one session:**
- Pattern mastery achieved (10+ components done)
- Components similar in nature
- Known violation patterns
- Template-based implementation
- Sustained focus capability
- No major surprises expected

**❌ Prevents multi-component sessions:**
- New patterns being learned
- Components vary significantly
- Unknown violation counts
- Novel architecture required
- Complex dependencies
- High uncertainty

### Session Size Anti-Patterns

**❌ Wrong: Fixed number regardless of complexity**
```
"Always 2 per session"
→ Wastes capacity on simple components
→ Overloads on complex components
```

**❌ Wrong: Maximize count regardless of quality**
```
"Do as many as possible"
→ Quality suffers
→ Violations missed
→ Technical debt created
```

**✅ Right: Complexity-based with quality gates**
```
"2-4 based on complexity, maintaining zero violations"
→ Optimizes throughput
→ Maintains quality
→ Adapts to reality
```

### Revised Velocity Expectations

| Session # | Mastery Level | Medium Complexity | Medium-High Complexity |
|-----------|--------------|-------------------|------------------------|
| 1-2 | Learning | 1 interface | 1 interface |
| 3-4 | Applying | 2 interfaces | 1-2 interfaces |
| 5-6 | Mastery | 3-4 interfaces | 2 interfaces |
| 7+ | Expert | 4-5 interfaces | 2-3 interfaces |

### Key Insight

**The initial finding wasn't wrong. It was correct for its complexity level.**

Later work revealed the deeper truth: complexity, not count, determines optimal session size. With pattern mastery, simple components can be batched (3-4), while complex ones still need focus (1-2).

The lesson isn't "2 is optimal" or "4 is optimal"—it's "match session size to complexity level."

---

## Related Topics

- **LESS-28**: Pattern mastery accelerates development
- **LESS-35**: Throughput optimization through complexity batching
- **LESS-40**: Velocity and quality both improve with mastery
- **LESS-43**: Estimation adjusts after pattern mastery
- **Velocity**: LESS-47 on improvement milestones

---

## Usage Guidelines

**When to Apply:**
- Planning session scope
- Estimating completion time
- Batching similar work
- Training session design
- Resource allocation

**How to Use:**
1. Assess component complexity first
2. Check pattern mastery level
3. Plan session size accordingly
4. Monitor actual vs expected
5. Adjust future sessions

---

## Keywords

session-planning, complexity-based, pattern-mastery, velocity-optimization, throughput, batching, adaptive-planning, quality-maintenance

---

## Version History

- **2025-10-30**: Migrated to SIMAv4 format, split from LESS-26-35 (Phase 10.3 Session 4)
- **2025-10-25**: Created - Combined LESS-26 and LESS-35 insights

---

**File:** `/sima/entries/lessons/optimization/LESS-26.md`  
**Lines:** ~290  
**Status:** Complete  
**Next:** LESS-28

---

**END OF DOCUMENT**

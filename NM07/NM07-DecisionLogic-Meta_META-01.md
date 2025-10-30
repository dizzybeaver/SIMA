# NM07-DecisionLogic-Meta_META-01.md - META-01

# META-01: How to Make Good Decisions

**Category:** Decision Logic
**Topic:** Meta Decision-Making
**Priority:** Framework
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Comprehensive framework for making good decisions - understanding context, gathering data, evaluating alternatives, and learning from outcomes.

---

## Context

Good decisions follow a consistent methodology. This meta-framework applies across all decision types in SUGA-ISP development.

---

## Content

### Decision-Making Framework

**6-Step Process:**

1. **Understand the Context**
2. **Gather Data**
3. **Consider Alternatives**
4. **Evaluate Trade-offs**
5. **Make Decision**
6. **Learn and Iterate**

---

### Step 1: Understand the Context

**Ask:**
- What are the constraints? (128MB, single-threaded, Lambda)
- What are the goals? (Performance, maintainability, simplicity)
- What are the trade-offs?
- Who is affected by this decision?
- How urgent is this decision?

**Example:**
```
Decision: Should I add caching for API responses?

Context:
- Constraint: 128MB Lambda memory limit
- Goal: Reduce API calls, improve latency
- Trade-off: Memory vs performance
- Affected: All API consumers
- Urgency: Medium (performance issue, not critical)
```

---

### Step 2: Gather Data

**Methods:**
- **Measure:** Profile performance, count occurrences
- **Search:** Check neural maps for similar decisions
- **Research:** Look for best practices, patterns
- **Ask:** Consult team, review past decisions

**Example:**
```
Data gathering:
- Measure: API calls take 50ms, called 5x per request
- Search: Found DEC-09 (cache design decisions)
- Research: Cache hit rates typically 70-90%
- Past decisions: Cache used successfully for config
```

**Critical:** Never guess when you can measure.

---

### Step 3: Consider Alternatives

**For every decision, identify options:**

**Example - Caching Decision:**
```
Alternative 1: In-memory cache (current approach)
  + Fast (0.1ms lookup)
  + Simple implementation
  - Limited to 128MB
  - Lost on cold start

Alternative 2: DynamoDB cache
  + Persistent across invocations
  + No memory limit
  - Slower (10-20ms lookup)
  - Additional AWS service
  - Cost

Alternative 3: No cache
  + Simple (no cache logic)
  + No memory pressure
  - Slow (50ms API calls)
  - High API costs

Alternative 4: Conditional cache (smart caching)
  + Cache only frequent items
  + Balanced memory usage
  - More complex logic
```

---

### Step 4: Evaluate Trade-offs

**Consider:**
- **Short-term vs Long-term**
- **Performance vs Complexity**
- **Cost vs Benefit**
- **Risk vs Reward**

**Trade-off Matrix:**

| Alternative | Performance | Complexity | Cost | Risk |
|-------------|-------------|------------|------|------|
| In-memory cache | High | Low | Low | Low |
| DynamoDB cache | Medium | Medium | Medium | Low |
| No cache | Low | Low | Low | None |
| Conditional cache | High | High | Low | Medium |

**Decision Criteria:**
1. Must meet minimum requirements
2. Balance multiple factors
3. Consider team capabilities
4. Assess maintenance burden

---

### Step 5: Make Decision

**Document:**
- What was decided
- Why this alternative chosen
- What trade-offs accepted
- How to implement
- How to verify success

**Example:**
```
DECISION: Implement in-memory cache with TTL

WHY:
- Performance gain: 50ms → 0.1ms (99.8% improvement)
- Simple implementation (existing cache interface)
- Low risk (proven pattern)
- Memory usage acceptable (<10MB for typical data)

TRADE-OFFS ACCEPTED:
- Lost on cold start (acceptable for our use case)
- 128MB limit (monitoring shows we use <50MB)

IMPLEMENTATION:
- Use existing gateway.cache_set/get
- TTL: 300s (5 minutes)
- Cache key: f"api:{endpoint}"

VERIFICATION:
- Monitor cache hit rate (target >70%)
- Measure latency improvement
- Track memory usage
- Review after 1 week
```

**Record in Neural Maps:**
- Add to NM04-Decisions if architectural
- Add to NM06-Lessons when learning occurs
- Update relevant decision trees

---

### Step 6: Learn and Iterate

**Monitor Results:**
- Did decision achieve goals?
- What worked well?
- What could be improved?
- What was unexpected?

**Example:**
```
After 1 week:
- Cache hit rate: 85% (exceeded target)
- Latency: 50ms → 0.5ms (90% improvement)
- Memory: 8MB used (well under limit)
- Unexpected: Cold starts slightly longer (acceptable)

Learning:
- In-memory caching very effective for this use case
- TTL of 300s appropriate (few stale data reports)
- Could extend to other API endpoints

Action:
- Document success in LESS-## 
- Recommend pattern for similar cases
- Update DT-04 examples
```

**Update Documentation:**
- Add lessons learned to NM06
- Update decision trees with real data
- Share knowledge with team

---

## Key Principles

### Consistency > Cleverness
Follow established patterns. Don't reinvent unless clear benefit.

```
❌ "I have a clever new way to structure the code"
✅ "Let me check NM07 decision trees for the standard approach"
```

### Simplicity > Optimization
Simple code is maintainable code. Optimize only when measured necessary.

```
❌ Premature micro-optimization
✅ Clear, readable code first; optimize hot paths if needed
```

### Measure > Guess
Data-driven decisions. Profile before optimizing.

```
❌ "This probably causes slowness"
✅ "Profiler shows this takes 80% of time"
```

### Document > Remember
Write down the "why". Future you will thank you.

```
❌ Trust memory for important decisions
✅ Document in neural maps with rationale
```

### Test > Hope
Verify correctness. Tests catch bugs.

```
❌ "It should work"
✅ "Tests confirm it works"
```

### Iterate > Perfect
Ship and improve. Perfect is enemy of good.

```
❌ Endlessly refining before shipping
✅ Ship working version, gather feedback, improve
```

---

## Common Decision Patterns

### Pattern: Import Decision
```
1. Context: Need to use function X
2. Data: Check if same interface or cross-interface
3. Alternatives: Direct import vs gateway import
4. Trade-off: Convenience vs architecture rules
5. Decision: Use DT-01 (import decision tree)
6. Learn: Did it work? Any import issues?
```

### Pattern: Performance Issue
```
1. Context: Operation is slow
2. Data: Profile and measure (don't guess!)
3. Alternatives: Cache, optimize algorithm, accept slowness
4. Trade-off: Performance vs complexity vs maintainability
5. Decision: Use DT-07 (should optimize)
6. Learn: Did optimization help? Worth the effort?
```

### Pattern: New Feature Request
```
1. Context: User wants feature X
2. Data: Check if already exists, measure scope
3. Alternatives: New interface, extend existing, utility
4. Trade-off: Complexity vs capability
5. Decision: Use DT-03 and DT-13
6. Learn: Is interface structure working well?
```

---

## Decision Quality Checklist

**Before deciding, verify:**
- [ ] Understood context and constraints
- [ ] Gathered data (measured, not guessed)
- [ ] Considered multiple alternatives
- [ ] Evaluated trade-offs explicitly
- [ ] Documented decision and rationale
- [ ] Plan to monitor and learn from outcome

**After deciding, ensure:**
- [ ] Decision documented in neural maps
- [ ] Implementation plan clear
- [ ] Success criteria defined
- [ ] Monitoring in place
- [ ] Team informed
- [ ] Review scheduled

---

## When to Re-evaluate Decisions

**Triggers:**
- Context changes (new constraints, different goals)
- New data available (measurements, user feedback)
- Decision not achieving goals
- Better alternatives emerge
- Technology/platform changes

**Re-evaluation Process:**
1. Review original decision and rationale
2. Assess current effectiveness
3. Consider if context has changed
4. Identify new alternatives
5. Decide: keep, modify, or reverse
6. Document the re-evaluation

---

## Meta-Decision Anti-Patterns

**❌ Analysis Paralysis:**
```
Spending weeks analyzing perfect solution
→ Make best decision with available data, iterate
```

**❌ Gut-Feel Decisions:**
```
"I feel like this is right"
→ Gather data, use decision frameworks
```

**❌ Following Trends Blindly:**
```
"Everyone uses microservices, we should too"
→ Evaluate for your specific context
```

**❌ Not Learning from Mistakes:**
```
Making same error repeatedly
→ Document lessons, update decision trees
```

**❌ Undocumented Decisions:**
```
"I'll remember why I did this"
→ Document rationale in neural maps
```

---

## Real-World Usage

**User Query:** "How should I decide about [X]?"

**Response Pattern:**
```
1. Check relevant decision tree (DT-##)
2. If no exact match, use META-01 framework:
   - Understand context
   - Gather data
   - Consider alternatives
   - Evaluate trade-offs
   - Make decision
   - Learn and iterate
3. Document decision in neural maps
4. Monitor and learn from outcome
```

---

## Summary

**Good decisions come from:**
- Understanding context deeply
- Gathering data (not guessing)
- Considering alternatives
- Evaluating trade-offs explicitly
- Documenting rationale
- Learning from outcomes

**Use this framework when:**
- Facing new decision types
- Existing decision trees don't apply
- Need to create new decision tree
- Training team on decision-making
- Reviewing past decisions

**Remember:** Consistency, simplicity, measurement, documentation, testing, and iteration are key to sustainable development.

---

## Related Topics

- All DT-## (specific decision trees)
- All FW-## (decision frameworks)
- **NM04-Decisions**: Recording architectural decisions
- **NM06-Lessons**: Learning from outcomes
- **NM05-AntiPatterns**: What not to do

---

## Keywords

decision framework, how to decide, decision process, trade-offs, methodology, meta-decisions

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-Meta_META-01.md`
**End of Document**

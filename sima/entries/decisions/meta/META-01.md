# META-01.md

**REF-ID:** META-01  
**Category:** Decision Logic  
**Subcategory:** Meta  
**Name:** Meta Decision-Making Framework  
**Priority:** Framework  
**Status:** Active  
**Created:** 2024-10-30  
**Updated:** 2024-10-30

---

## Summary

Comprehensive framework for making good decisions - understanding context, gathering data, evaluating alternatives, documenting rationale, and learning from outcomes.

---

## Problem

Teams need consistent methodology for making decisions across different domains. Without framework, decisions are inconsistent, poorly documented, and lessons aren't captured for future reference.

---

## Decision Tree

```
START: Need to make a decision
│
├─ Step 1: Understand Context
│  â"œâ"€ What are constraints? (technical, time, resources)
│  â"œâ"€ What are goals? (performance, maintainability, simplicity)
│  â"œâ"€ What are trade-offs?
│  â""â"€ Who is affected?
│
├─ Step 2: Gather Data
│  â"œâ"€ Measure (don't guess)
│  â"œâ"€ Search neural maps for similar decisions
│  â"œâ"€ Research best practices
│  â""â"€ Consult team and past decisions
│
├─ Step 3: Consider Alternatives
│  â"œâ"€ List all options (minimum 3)
│  â"œâ"€ Include "do nothing" option
│  â""â"€ Consider hybrid approaches
│
├─ Step 4: Evaluate Trade-offs
│  â"œâ"€ Performance vs Maintainability
│  â"œâ"€ Simplicity vs Capability
│  â"œâ"€ Short-term vs Long-term
│  â""â"€ Cost vs Benefit
│
├─ Step 5: Make Decision
│  â"œâ"€ Choose based on context and data
│  â"œâ"€ Document rationale
│  â""â"€ Set success criteria
│
└─ Step 6: Learn and Iterate
   â"œâ"€ Monitor outcome
   â"œâ"€ Compare to success criteria
   â"œâ"€ Document lessons learned
   └─ Update decision trees if needed
```

---

## Examples

### Example 1: Cache Decision Using Framework

**Context:**
```
Decision: Should I add caching for API responses?

Constraints:
- Lambda 128MB memory limit
- Single-threaded execution
- No persistent storage

Goals:
- Reduce API calls
- Improve latency
- Stay under memory limit

Trade-offs:
- Memory vs Performance
- Simplicity vs Speed
```

**Data Gathering:**
```
Measurements:
- API call: 50ms per request
- Called: 5 times per invocation
- Total API time: 250ms (40% of request time)

Research:
- Found DT-04: Should This Be Cached
- Found FW-01: Cache vs Compute Trade-off
- Hit rate estimate: 70% (based on access patterns)

Calculation (FW-01):
- Benefit = (50ms - 0.1ms) × 0.7 = 34.9ms per call
- Total benefit: 174.5ms (70% improvement)
```

**Alternatives:**
```
1. Cache all responses (highest performance)
2. Cache only frequent requests (balanced)
3. No caching (simplest)
4. Lazy loading without cache (hybrid)
```

**Trade-off Evaluation:**
```
Cache all responses:
✅ Best performance (174.5ms saved)
❌ Highest memory usage
✅ Moderate complexity

Cache selective:
âœ… Good performance (122ms saved @ 50% coverage)
âœ… Lower memory usage
❌ More complex (selection logic)

No caching:
❌ No performance gain
âœ… Lowest memory
âœ… Simplest

Decision: Cache all responses
- High benefit (70% improvement)
- Acceptable memory (< 10MB estimated)
- Moderate complexity acceptable
```

**Implementation & Learning:**
```
Implemented: Cache with 300s TTL
Monitored: Hit rate 75%, 165ms average saved
Learned: Cache working well, TTL appropriate
Documented: Added to DT-04 as successful example
```

### Example 2: Refactoring Decision

**Context:**
```
Decision: Should I refactor this 150-line function?

Constraints:
- Code is working correctly
- Tests passing
- Time available: 4 hours

Goals:
- Improve readability
- Reduce future maintenance
- Don't break functionality
```

**Data Gathering:**
```
Measurements:
- Function: 150 lines
- Nesting: 4 levels deep
- Cyclomatic complexity: 12
- Test coverage: 85%

Research:
- Found DT-10: Should I Refactor This Code
- Found similar refactoring in LESS-13

Decision tree check (DT-10):
âœ… Code working correctly
âœ… Hard to understand (4 levels nesting)
❌ Not duplicated
❌ No architecture violation
```

**Alternatives:**
```
1. Full refactoring (extract 5 functions)
2. Partial refactoring (reduce nesting only)
3. Add comments (document complexity)
4. Leave as-is (if it ain't broke)
```

**Trade-off Evaluation:**
```
Full refactoring:
âœ… Best long-term maintainability
❌ 4 hours effort
❌ Risk of introducing bugs
âœ… Tests provide safety net

Partial refactoring:
âœ… Moderate improvement
âœ… 2 hours effort
âœ… Lower risk

Add comments:
❌ No structural improvement
âœ… 30 minutes
âœ… Zero risk

Decision: Partial refactoring
- Good improvement for reasonable effort
- Lower risk than full refactoring
- Can do full refactoring later if needed
```

### Example 3: New Feature Architecture

**Context:**
```
Decision: Email sending capability - new interface or extend existing?

Constraints:
- Must integrate with existing architecture
- Lambda environment (no long-running connections)
- Need SMTP support

Goals:
- Clean architecture
- Reusable across system
- Easy to test
```

**Data Gathering:**
```
Scope analysis:
- SMTP connection management: ~100 lines
- Email templates: ~80 lines
- Delivery tracking: ~50 lines
- Total: ~230 lines

Research:
- Found DT-13: New Interface or Extend Existing
- No existing email interface
- Has state (connection pool, config)
- Domain-specific (email operations)
```

**Alternatives:**
```
1. New EMAIL interface (dedicated)
2. Extend HTTP interface (reuse connection logic)
3. Add to UTILITY (if simple enough)
4. External service (simplest integration)
```

**Trade-off Evaluation:**
```
New EMAIL interface:
âœ… Clean separation
âœ… Focused responsibility
❌ More files to maintain
âœ… Easier to test

Extend HTTP:
❌ Mixes concerns
❌ HTTP not natural fit for SMTP
âœ… Reuses connection logic

Add to UTILITY:
❌ Too substantial (230 lines)
❌ Has state (doesn't fit utility pattern)

External service:
âœ… Simplest integration
❌ External dependency
❌ Additional cost
âœ… Most reliable

Decision: New EMAIL interface
- Meets all criteria from DT-13
- Clean architecture
- Worth the maintenance overhead
```

### Example 4: Deployment Decision

**Context:**
```
Decision: Deploy architecture refactoring to production?

Constraints:
- Friday afternoon
- Affects 20+ files
- Breaking changes to import paths

Goals:
- Get refactoring into production
- Minimize risk
- Maintain uptime
```

**Data Gathering:**
```
Testing status:
âœ… All 150 tests passing
âœ… Code review approved
âœ… Staged for 48 hours
âš ï¸ Breaking changes (import paths)

Risk assessment (DT-12):
- Scope: HIGH (20+ files)
- Impact: HIGH (breaking changes)
- Rollback: Ready (git tag + migration scripts)
- Timing: BAD (Friday afternoon)
```

**Alternatives:**
```
1. Deploy now (Friday 4 PM)
2. Deploy Monday morning (safer timing)
3. Phased rollout (gradual migration)
4. Wait for next sprint
```

**Trade-off Evaluation:**
```
Deploy Friday:
❌ High risk timing
❌ Limited support availability
âœ… Gets feature out sooner

Deploy Monday:
âœ… Better timing
âœ… Full team available
âœ… Better monitoring window
❌ Delays by 3 days

Decision: Deploy Monday morning
- DT-12 guidance: HIGH RISK → careful timing
- Team availability more important than 3-day delay
- Better to be safe
```

### Example 5: When Decision Trees Don't Apply

**Context:**
```
Decision: Choose between two new experimental approaches for feature X

Situation:
- No existing decision tree covers this
- Novel approach for the system
- Limited data available
```

**Framework Application:**
```
1. Context: Understand constraints and goals
2. Data: Gather what's available (even if limited)
3. Alternatives: List both approaches + hybrid
4. Trade-offs: Evaluate explicitly
5. Decision: Make best choice with available info
6. Learn: Document extensively, create new decision tree

Result: New decision tree created (DT-14?)
- Documents decision process
- Helps future similar decisions
- Contributes to neural maps
```

---

## Related Patterns

**All Decision Trees:**
- **DT-01** through **DT-13**: Specific decision trees
- **FW-01** through **FW-02**: Decision frameworks

**Related Categories:**
- **NM04-Decisions**: Recording architectural decisions
- **NM06-Lessons**: Learning from outcomes
- **NM05-AntiPatterns**: What not to do

---

## Keywords

decision framework, decision methodology, decision-making process, trade-off analysis, context understanding, data gathering, alternative evaluation, learning from decisions, meta-framework, systematic decisions

---

## Version History

- **2024-10-30:** Migrated to SIMAv4 format from NM07 v3
- **2024-10-24:** Created in SIMAv3 format

---

**File:** `META-01.md`  
**Location:** `/sima/entries/decisions/meta/`  
**End of Document**

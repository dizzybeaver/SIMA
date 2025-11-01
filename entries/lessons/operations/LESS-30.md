# LESS-30.md

# LESS-30: Optimization Tools Reduce Query Response Time

**Category:** Lessons  
**Topic:** Operations & Efficiency  
**Priority:** MEDIUM  
**Status:** Active  
**Created:** 2025-10-30  
**Path:** `/sima/entries/lessons/operations/LESS-30.md`

---

## Summary

Pre-loading critical context once and using fast lookup tools reduces query response time from 30-60 seconds to 5-15 seconds (80% reduction). Tools provide 4-6 minutes savings per session across 10 queries.

---

## Pattern

### The Problem

**Repeated Documentation Searches:**
```
Every query:
1. Start broad search (15 sec)
2. Navigate to relevant section (15 sec)
3. Read context (20 sec)
4. Find specific answer (10 sec)
Total: 60 sec per query

10 queries = 10 minutes wasted
```

**Better Approach:**
```
Session start:
1. Load critical context once (45 sec)
2. Create fast lookup tools

Per query:
1. Check tools (5-15 sec)
Total: 5-15 sec per query

10 queries = 2 minutes (80% faster)
```

---

## Solution

### Tool Categories

**1. Bootstrap Context** (load once per session)
- Critical patterns and principles
- Instant answers for common questions
- 30-60 second load time, infinite reuse

**2. Quick Reference** (5-10 second lookups)
- Anti-pattern checklists
- Reference directories
- Critical violations

**3. Decision Trees** (15-30 second workflows)
- Pre-mapped common scenarios
- Step-by-step guidance
- Consistent decision-making

**4. Complete Specification** (deep dives when needed)
- Full documentation
- Rare use (5% of queries)
- 60-90 second deep reads

### Time Savings Analysis

**Per Query Type:**

| Query Type | Without Tools | With Tools | Savings | Improvement |
|-----------|---------------|------------|---------|-------------|
| Instant answer | 30 sec | 5 sec | 25 sec | 83% faster |
| Reference lookup | 45 sec | 10 sec | 35 sec | 78% faster |
| Workflow pattern | 90 sec | 15 sec | 75 sec | 83% faster |
| Anti-pattern check | 30 sec | 5 sec | 25 sec | 83% faster |

**Per Session (10 queries typical):**

```
Query breakdown:
- 4 instant answers: 4 × 25 sec = 100 sec
- 3 reference lookups: 3 × 35 sec = 105 sec
- 2 workflow patterns: 2 × 75 sec = 150 sec
- 1 anti-pattern check: 1 × 25 sec = 25 sec
Total savings: 380 sec (6.3 minutes) per session
```

### Real Tool Examples

**Tool 1: Bootstrap Context**
```
Quick-Start Context Document:
- Top 10 instant answers
- Routing patterns
- Critical references
- Anti-pattern overview

Load time: 45 sec (once per session)
Queries answered immediately: 40%
ROI: After 2-3 instant answer uses
```

**Tool 2: Anti-Pattern Checklist**
```
Critical Anti-Patterns Checklist:
- 20-30 anti-patterns, condensed
- Organized by category
- Quick scan format

Lookup time: 5 sec
Without tool: 30 sec (search full docs)
Savings: 25 sec per check × 5-10 checks = 125-250 sec
```

**Tool 3: Reference Directory**
```
Reference ID Directory:
- All references listed
- One-line summaries
- File locations

Lookup time: 10 sec
Without tool: 45 sec (search + navigate)
Savings: 35 sec per lookup × 8 lookups = 280 sec
```

**Tool 4: Workflow Playbook**
```
Common Workflows:
- Decision tree for typical tasks
- Step-by-step verification
- Pre-mapped paths

Follow time: 15 sec
Without tool: 90 sec (figure out steps)
Savings: 75 sec per use × 2 uses = 150 sec
```

---

## The Pattern: Load Once, Use Many

**Traditional Approach:**
```
For each query:
1. Start broad search (15 sec)
2. Navigate to relevant section (15 sec)
3. Read context (20 sec)
4. Find specific answer (10 sec)
Total: 60 sec per query

10 queries = 600 sec (10 minutes)
```

**Optimized Approach:**
```
Session start:
1. Load bootstrap context (45 sec, ONE TIME)
2. Internalize routing patterns
3. Tools ready

For each query:
1. Check instant answers (5 sec)
   OR use specific tool (10-15 sec)
Total: 5-15 sec per query

10 queries = 45 sec load + 100 sec queries = 145 sec (2.4 min)
Savings: 7.6 minutes (76%)
```

---

## Tool Effectiveness Metrics

| Tool Type | Queries Handled | Avg Time | Without Tools |
|-----------|----------------|----------|---------------|
| Instant answers | 40% | 5 sec | 30 sec |
| Quick reference | 30% | 10 sec | 45 sec |
| Workflows | 20% | 15 sec | 90 sec |
| Deep reads | 10% | 60 sec | 90 sec |
| **Total** | **10 queries** | **14 sec avg** | **52 sec avg** |

**Improvement:** 73% faster (385 sec saved per session)

---

## Best Practices

### Session Start

```
1. Load bootstrap context (45 sec, mandatory)
2. Identify likely tool needs (15 sec)
3. Mental bookmark relevant tools
4. Begin work

Total overhead: 60 sec
Queries answered faster: All of them
```

### Query Strategy

```
1. Check instant answers first (5 sec)
2. If not found, identify tool type:
   - Anti-pattern? → Use checklist
   - Reference? → Use directory
   - Decision? → Use workflow
3. Use specific tool (10-15 sec)
4. Fallback to full docs only if needed

Most queries: 5-15 sec
Rare queries: 60 sec (acceptable)
```

### Tool Selection

```
Question pattern → Tool choice:
- "Can I...?" → Anti-pattern checklist
- "Where is...?" → Reference directory  
- "How do I...?" → Workflow playbook
- "Why...?" → Design decision docs
- "What is...?" → Instant answers
```

---

## Key Insights

**Insight 1:**
80% of queries answered by 20% of documentation. Pre-load that 20%, get 80% instant answers.

**Insight 2:**
Time saved compounds. 6 min/session × 6 sessions = 36 min saved, but also: reduced frustration, maintained flow, better decisions.

**Insight 3:**
Tools enable consistency. Same question tomorrow gets same high-quality answer in same time.

---

## Universal Applicability

**This pattern works for:**
- Technical documentation
- Operations runbooks
- Compliance frameworks
- Design systems
- API references
- Any large knowledge base with repeated access patterns

**Core Principle:**
Analyze query patterns, extract common needs, create targeted tools, load once, use many times.

---

## Related Topics

- **Pattern Mastery**: Reduces reference needs over time
- **Systematic Verification**: Uses quick tools
- **Knowledge Management**: Organizing information for access
- **Workflow Optimization**: Reducing repeated work

---

## Keywords

optimization-tools, query-efficiency, time-savings, knowledge-access, bootstrap-context, quick-reference, decision-trees, roi-calculation

---

## Version History

- **2025-10-30**: Genericized for SIMAv4 - Removed project-specific details
- **2025-10-25**: Created - Documented tool usage analysis

---

**File:** `LESS-30.md`  
**Location:** `/sima/entries/lessons/operations/`  
**Status:** Active

---

**End of Document**

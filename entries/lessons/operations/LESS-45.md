# Filename: LESS-45.md

# LESS-45: Efficient Context Loading

**REF-ID:** LESS-45  
**Category:** Operations/Performance  
**Type:** Lesson Learned  
**Created:** 2025-11-01  
**Last Updated:** 2025-11-01  
**Status:** Active  
**Priority:** HIGH

---

## Summary

Session context loading time directly impacts productivity. Optimize load time through focused content, hierarchical structure, and load-once patterns. Target 30-60 seconds for context files, enable instant recall thereafter.

---

## Context

AI-assisted development sessions require loading project context - architecture patterns, decisions, lessons learned. Inefficient context loading wastes 5-10 minutes per session, disrupts flow, discourages context use.

---

## The Lesson

### Context Load Time Impact

**Time cost:**
```
Good context (30-60s):
- Load once per session
- Instant recall after load
- Enables productive work immediately
- ROI: 1 minute investment, hours of benefit

Poor context (5-10 min):
- Too large, unfocused
- User skips loading
- Constant reference lookups
- Lost productivity: 10-20 min per session
```

**Productivity impact:**
```
Session 1: 5 min context load
Session 2: 5 min context load
Session 3: Skip loading (frustration)
Session 4-10: Working without context

Result: Poor decisions, missed patterns, duplicated work
```

### Optimal Context Size

**Target metrics:**
```
Load time: 30-60 seconds
File size: 15-25KB
Lines: 400-800
Sections: 5-10 focused areas
```

**Why these limits:**
```
30-60s: Short enough users will load every session
400-800 lines: Fits working memory, enables quick recall
15-25KB: Fast network transfer, quick parsing
5-10 sections: Manageable structure, easy navigation
```

### Context Structuring

**Hierarchical loading:**
```
Level 1: Bootstrap (30s)
- Top 10 patterns
- Critical rules
- Common workflows
- Essential references

Level 2: On-demand (per query)
- Detailed documentation
- Specific examples
- Deep dives
- Historical context
```

**Load once, reference many:**
```
Session start:
1. Load bootstrap context (30-60s)
2. Get top-level patterns in memory
3. Work with instant recall

During session:
- Reference specific details as needed
- No reloading required
- Deep links for exploration
```

### Content Optimization

**Focus on high-frequency needs:**
```
Include:
âœ… Top 10 patterns (80% of queries)
âœ… Critical rules (RED FLAGS)
âœ… Common workflows (daily use)
âœ… Quick reference (instant answers)

Exclude:
âŒ Rare edge cases
âŒ Historical context (link instead)
âŒ Detailed examples (link instead)
âŒ Complete specifications (link instead)
```

**Example - Before:**
```markdown
# Session Context (2000 lines, 10 min load)

## Architecture (500 lines)
- Every detail of every pattern
- Complete history
- All variations
- Every example

## Decisions (600 lines)
- All 50 decisions
- Full rationale for each
- Complete alternatives analysis

## Lessons (900 lines)
- All 60 lessons
- Every example
- Complete context
```

**Example - After:**
```markdown
# Session Context (500 lines, 45s load)

## Top 10 Patterns (100 lines)
- Essential patterns only
- Brief descriptions
- Quick examples
- Links to details

## Critical Rules (50 lines)
- RED FLAGS checklist
- Common mistakes
- Quick verification

## Common Workflows (150 lines)
- Daily use patterns
- Decision trees
- Quick routing

## Quick Reference (100 lines)
- Instant answers
- REF-ID directory top 20
- Tool catalog

## On-Demand Links (100 lines)
- Deep dive references
- Detailed docs
- Historical context
```

### Mode-Specific Loading

**Different modes, different contexts:**
```
General Mode (SESSION-START-Quick-Context.md)
- 30-45s load
- Q&A patterns
- Architecture overview
- Workflow routing

Learning Mode (SIMA-LEARNING-SESSION-START-Quick-Context.md)
- 45-60s load
- Extraction patterns
- Quality standards
- Template structures

Project Mode (PROJECT-MODE-Context.md)
- 30-45s load
- Implementation patterns
- Verification checklists
- Code standards

Debug Mode (DEBUG-MODE-Context.md)
- 30-45s load
- Investigation patterns
- Known bugs
- Trace methods
```

### Caching Strategy

**Context persistence:**
```python
# Session-level caching
class SessionContext:
    def __init__(self):
        self._loaded = False
        self._patterns = None
        self._rules = None
    
    def load(self):
        if not self._loaded:
            # Load once
            self._patterns = load_patterns()
            self._rules = load_rules()
            self._loaded = True
    
    def get_pattern(self, name):
        # Instant recall after load
        return self._patterns[name]
```

**Incremental loading:**
```
Initial load (30s):
- Core patterns
- Essential rules
- Common workflows

On-demand (instant):
- Specific examples
- Detailed docs
- Related topics
```

### Load Time Optimization

**Technique 1: Hierarchical structure**
```markdown
# Quick Overview (loads first)
- Summary of key concepts
- Navigation guide

## Section 1: Essentials (loads second)
- Must-know patterns

## Section 2: Common Use (loads third)
- Frequent references

## Section 3: Advanced (link only)
- Deep dive → separate file
```

**Technique 2: Reference linking**
```markdown
# Context File (fast load)
- Pattern summary + link to details
- Rule checklist + link to rationale
- Workflow overview + link to examples

# Detail Files (load on demand)
- Complete pattern documentation
- Full decision rationale
- Comprehensive examples
```

**Technique 3: Progressive disclosure**
```
Load 1: Essentials (30s)
→ Enables 80% of work

Load 2: Specifics (on-demand, instant)
→ Handles remaining 20%

Never load 3: Rare cases
→ Point to documentation
```

### Quality Metrics

**Context file scorecard:**
```
✅ Load time < 60s
✅ Size < 25KB
✅ Lines < 800
✅ Enables 80% of queries
✅ Clear navigation
✅ Links to details
✅ Updated regularly
✅ User satisfaction high
```

**User experience:**
```
Good context:
- "Quick to load"
- "Everything I need"
- "Easy to find things"
- "Use it every session"

Poor context:
- "Takes forever"
- "Too much information"
- "Can't find anything"
- "I skip loading it"
```

### Maintenance Strategy

**Regular optimization:**
```
Monthly review:
1. Check load time metrics
2. Analyze query patterns
3. Identify unused sections
4. Remove bloat
5. Add high-frequency needs
6. Test load time
```

**Content evolution:**
```
Add:
- New high-frequency patterns
- Recent critical rules
- Commonly-needed workflows

Remove:
- Rare use cases (link instead)
- Outdated patterns
- Redundant content
```

### Anti-Patterns

**âŒ Everything in one file:**
```
Result: 10 min load time
Effect: Users skip loading
```

**âŒ No structure:**
```
Result: Can't find anything
Effect: Constant re-searching
```

**âŒ Stale content:**
```
Result: Outdated patterns
Effect: Lost trust in context
```

**âŒ No load time target:**
```
Result: Gradual bloat
Effect: Unusable context
```

### Success Pattern

**Optimal approach:**
```
1. Target 30-60s load time
2. Focus on high-frequency needs (80/20)
3. Hierarchical structure (quick → deep)
4. Link to details (don't inline everything)
5. Mode-specific contexts (not one-size-fits-all)
6. Regular optimization (monthly review)
7. User feedback (measure satisfaction)
```

---

## Related Topics

- **INT-05**: Configuration Interface (loading patterns)
- **LESS-08**: Memory Management (caching strategies)
- **LESS-28**: Pattern Mastery (reduces lookups over time)
- **LESS-30**: Optimization Tools (query efficiency)
- **Mode Selection**: Different modes need different contexts

---

## Keywords

context-loading, load-time-optimization, session-bootstrap, hierarchical-structure, progressive-disclosure, caching-strategy, user-experience, productivity

---

## Version History

- **2025-11-01**: Created for SIMAv4 Priority 4
- **Source**: Genericized from context loading optimization

---

**File:** `sima/entries/lessons/operations/LESS-45.md`  
**Lines:** ~390  
**Status:** Complete  
**Next:** LESS-48

---

**END OF DOCUMENT**
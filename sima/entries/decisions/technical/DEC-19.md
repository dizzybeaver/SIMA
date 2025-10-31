# File: DEC-19.md

**REF-ID:** DEC-19  
**Category:** Technical Decision  
**Priority:** Critical  
**Status:** Active  
**Date Decided:** 2024-08-01  
**Created:** 2024-08-01  
**Last Updated:** 2025-10-30 (SIMAv4 migration)

---

## 📋 SUMMARY

Use SIMA (Synthetic Integrate Memory Architecture) neural maps as documentation system to preserve design knowledge including decisions, rationale, alternatives, and trade-offs.

**Decision:** Comprehensive neural map documentation system  
**Impact Level:** Critical  
**Reversibility:** Difficult (knowledge loss)

---

## 🎯 CONTEXT

### Problem Statement
Six months after making design decisions, team couldn't remember WHY choices were made. "Why no threading?" "Why gateway pattern?" became mysteries. Traditional documentation failed to capture full context.

### Background
- Traditional docs (README, comments) insufficient
- Lost alternatives considered
- Lost trade-offs accepted
- Lost decision context
- Knowledge tribal (only in heads)
- Re-discovery expensive

### Requirements
- Preserve decision rationale
- Document alternatives considered
- Capture trade-offs
- Enable Claude to answer "why"
- Support future decision-making

---

## 💡 DECISION

### What We Chose
Create comprehensive SIMA neural map system capturing decisions, bugs, lessons, patterns with full context. Structure as interconnected knowledge base Claude can search.

### Structure
```
NM01: Architecture (patterns, interfaces, core)
NM02: Dependencies (layers, rules, relationships)
NM03: Operations (flows, pathways, execution)
NM04: Decisions (this category!)
NM05: Anti-Patterns (what NOT to do)
NM06: Lessons/Bugs/Wisdom (insights gained)
NM07: Decision Logic (algorithms, trees)
```

### Rationale
1. **Preserves Institutional Knowledge**
   - Rationale doesn't disappear
   - Future developers understand context
   - Survives team changes
   - Prevents historical amnesia

2. **Enables Claude Effectiveness**
   - Can search for design rationale
   - Explains trade-offs
   - Provides context-rich answers
   - Genuinely helpful for architecture

3. **Captures Full Decision Context**
   - WHAT was decided
   - WHY (rationale with evidence)
   - ALTERNATIVES considered/rejected
   - TRADE-OFFS accepted
   - IMPACT analysis
   - FUTURE considerations

4. **Structured Knowledge Base**
   - REF-ID system (DEC-01, LESS-02, etc.)
   - Cross-references between topics
   - Searchable keywords
   - Hierarchical organization

---

## 📄 ALTERNATIVES CONSIDERED

### Alternative 1: Traditional Documentation
**Pros:**
- Familiar approach
- Simple tools

**Cons:**
- Doesn't capture "why"
- No alternatives documented
- No trade-off analysis
- Hard to maintain

**Why Rejected:** Insufficient context preservation.

---

### Alternative 2: Decision Log (ADR)
**Pros:**
- Structured format
- Decision focus

**Cons:**
- Only decisions, not bugs/lessons
- No searchable REF-IDs
- Limited cross-references
- Not Claude-optimized

**Why Rejected:** Too narrow, missing key knowledge types.

---

### Alternative 3: Wiki System
**Pros:**
- Easy to edit
- Good search

**Cons:**
- Unstructured
- No enforced format
- Hard to maintain consistency
- Claude integration harder

**Why Rejected:** Lacks structure and consistency.

---

## ⚖️ TRADE-OFFS

### What We Gained
- Knowledge preservation (nothing forgotten)
- Claude effectiveness (accurate "why" answers)
- Faster onboarding (context available)
- Better decisions (full context)
- Reduced mistakes (anti-patterns documented)

### What We Accepted
- Significant documentation effort
- Learning curve for team
- File proliferation (130+ files)
- Maintenance burden

---

## 📊 IMPACT ANALYSIS

### Technical Impact
- **Architecture:** Documented rationale for all choices
- **Development:** Understand "why" not just "what"
- **Code Review:** More informed
- **Testing:** No runtime impact

### Operational Impact
- **Knowledge Loss:** Dramatically reduced
- **Maintainability:** Long-term sustainability
- **Refactoring:** Confident changes
- **AI Assistance:** Claude genuinely helpful

### Metrics
- Time-to-answer "why": 2 mins (was hours)
- Re-litigation events: ~0 (was 2-3/month)
- Onboarding speed: 2-3× faster

---

## 🔮 FUTURE CONSIDERATIONS

### When to Revisit
- If maps become too large (atomization)
- If cross-references unmanageable (tooling)
- If maintenance burden grows

### Evolution Path
- SIMA v4 (current): Under 400 lines per file
- Interactive visualization
- Automated updates from code
- Template enforcement
- Impact analysis tools

---

## 🔗 RELATED

### Related Decisions
- **ALL DEC files:** This decision enables them all

### SIMA Entries
- **LESS-11:** Document decisions
- **WISD-05:** Write why down
- **ARCH-01:** Gateway trinity (documented in NM01)

### Cross-References
- **NM00:** Quick Index (navigation)
- **NM00A:** Master Index (complete map)

---

## 🏷️ KEYWORDS

`neural-maps`, `SIMA`, `documentation`, `knowledge-preservation`, `institutional-knowledge`, `decision-records`, `ai-collaboration`, `why-documentation`

---

## 📝 VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 3.0.0 | 2025-10-30 | Migration | SIMAv4 migration, under 400 lines |
| 2.0.0 | 2025-10-23 | System | SIMA v3 format |
| 1.0.0 | 2024-08-01 | Original | Meta-decision creating this system |

---

**END OF DECISION**

**Status:** Active - System documenting itself  
**Effectiveness:** Possibly most important decision made, transforms docs from burden to asset

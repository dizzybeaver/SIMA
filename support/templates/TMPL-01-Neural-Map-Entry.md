# TMPL-01: Neural Map Entry Template

**REF-ID:** TMPL-01  
**Category:** Template  
**Type:** Neural Map Entry Creation  
**Purpose:** Standard template for creating new neural map entries  
**Version:** 1.0.0  
**Last Updated:** 2025-11-01

---

## ðŸŽ¯ TEMPLATE PURPOSE

**What:** Standardized structure for all neural map entries (LESS, AP, DEC, BUG, WISD, etc.)

**When to Use:**
- Creating any new neural map entry
- Documenting lessons learned
- Recording anti-patterns
- Capturing decisions
- Documenting bugs

**Who Uses:**
- Developers documenting knowledge
- SIMA Learning Mode
- Project teams capturing patterns

---

## 📋 TEMPLATE STRUCTURE

### Section 1: Header Block

```markdown
# {TYPE}-{##}: {Brief Title}

**REF-ID:** {TYPE}-{##}
**Category:** {Gateway/Category/Topic}
**Type:** {Entry Type Full Name}
**Created:** {YYYY-MM-DD}
**Last Updated:** {YYYY-MM-DD}
**Status:** Active | Deprecated | Superseded
**Priority:** 🔴 Critical | 🟡 High | 🟢 Medium | ⚪ Low
```

**Field Descriptions:**

| Field | Required | Description | Example |
|-------|----------|-------------|---------|
| REF-ID | âœ… YES | Unique identifier | LESS-42 |
| Category | âœ… YES | Entry classification | Architecture/CoreArchitecture |
| Type | âœ… YES | Full type name | Lesson Learned |
| Created | âœ… YES | Creation date | 2025-11-01 |
| Last Updated | âœ… YES | Last modification date | 2025-11-01 |
| Status | âœ… YES | Entry lifecycle state | Active |
| Priority | â­ OPTIONAL | Importance level | 🔴 Critical |

---

### Section 2: Overview

```markdown
## ðŸ"Š OVERVIEW

**Summary:** {2-3 sentence summary of the entry}

**Context:** {1-2 sentences on when/why this matters}

**Impact:** {Quantified impact if applicable}
```

**Guidelines:**
- **Summary:** Essential information in 2-3 sentences max
- **Context:** Situational applicability
- **Impact:** Use metrics when possible (time saved, errors prevented, performance gain)

**Example:**
```markdown
## ðŸ"Š OVERVIEW

**Summary:** Sanitize implementation sentinels at layer boundaries before serialization. Internal sentinel objects fail JSON serialization and leak implementation details.

**Context:** Applies when passing data across architectural boundaries (Gateway â†' Interface â†' Core) or to external systems.

**Impact:** Prevents 535ms serialization penalties and JSON encoding failures. Reduces error rate by 94%.
```

---

### Section 3: Core Content

**For LESSONS (LESS-##):**
```markdown
## ðŸ"š LESSON CONTENT

### Problem
{What failed or what challenge was encountered}

### Discovery
{What was learned or realized}

### Principle
{Universal rule or pattern extracted}

### Application
{How and when to apply this lesson}

### Prevention
{How to avoid the problem in future}
```

**For ANTI-PATTERNS (AP-##):**
```markdown
## âš ï¸ ANTI-PATTERN

### Pattern
{What NOT to do - describe the bad pattern}

### Why Wrong
{Root cause of why this pattern fails}

### Consequences
{What happens when this pattern is used}

### Correct Approach
{The RIGHT way to solve this}

### Detection
{How to identify this anti-pattern in code}
```

**For DECISIONS (DEC-##):**
```markdown
## ðŸŽ¯ DECISION

### Decision Made
{What was chosen}

### Context
{What prompted this decision}

### Alternatives Considered
{Other options evaluated}

### Rationale
{Why this choice was made}

### Tradeoffs
{What was gained vs what was sacrificed}

### Outcome
{Results of the decision}
```

**For BUGS (BUG-##):**
```markdown
## ðŸ› BUG DETAILS

### Symptom
{What appeared broken}

### Root Cause
{Actual underlying issue}

### Impact
{Severity and scope of the bug}

### Fix
{How it was resolved}

### Prevention
{How to prevent recurrence}

### Detection
{How to identify this bug pattern}
```

**For WISDOM (WISD-##):**
```markdown
## ðŸ'¡ WISDOM

### Core Insight
{The profound realization - 1-2 sentences}

### Origin
{How this wisdom was discovered}

### Implications
{What this changes or clarifies}

### Applications
{Where this wisdom applies}

### Related Patterns
{Connected concepts or principles}
```

---

### Section 4: Examples

```markdown
## 📚 EXAMPLES

### Example 1: {Scenario Name}

**Situation:**
{Brief context}

**Implementation:**
```{language}
{Code example - 2-5 lines}
```

**Result:**
{Outcome and why it works}

### Example 2: {Anti-Example}

**Situation:**
{Brief context}

**Wrong Approach:**
```{language}
{Bad code example - 2-5 lines}
```

**Why It Fails:**
{Explanation}

**Correct Approach:**
```{language}
{Good code example - 2-5 lines}
```
```

**Guidelines:**
- 2-3 examples max
- Code examples: 2-5 lines each
- Always include "why" explanation
- Show both good and bad when applicable

---

### Section 5: Keywords & Cross-References

```markdown
## ðŸ"– KEYWORDS

{keyword1}, {keyword2}, {keyword3}, {keyword4}, {keyword5}, {keyword6}

**Count:** 4-8 keywords optimal

## 🔗 RELATED TOPICS

- {REF-ID-1}: {Brief context}
- {REF-ID-2}: {Brief context}
- {REF-ID-3}: {Brief context}

**See Also:** {REF-ID-4}, {REF-ID-5}, {REF-ID-6}

## 📋 CROSS-REFERENCES

**Architecture:** {ARCH-##}  
**Interfaces:** {INT-##}, {INT-##}  
**Patterns:** {PAT-##}  
**Decisions:** {DEC-##}  
**Anti-Patterns:** {AP-##}  
**Lessons:** {LESS-##}
```

**Guidelines:**
- **Keywords:** 4-8 searchable terms
- **Related Topics:** 3-7 with brief context
- **See Also:** Additional references without context
- **Cross-References:** Organized by category

---

## âœ… QUALITY CHECKLIST

### Before Submission

**Structure:**
- [ ] REF-ID follows format: {TYPE}-{##}
- [ ] All required sections present
- [ ] Headers use correct emoji indicators
- [ ] File named correctly: NM##-{Category}-{Topic}_{REF-ID}.md

**Content:**
- [ ] Summary is 2-3 sentences
- [ ] Content is genericized (no unnecessary project-specifics)
- [ ] Examples are 2-5 lines each
- [ ] Keywords: 4-8 present
- [ ] Related topics: 3-7 present
- [ ] Cross-references valid

**Quality:**
- [ ] Total length < 400 lines
- [ ] No duplicate of existing entry
- [ ] Actionable and clear
- [ ] Verifiable/testable
- [ ] Brief and focused

**Metadata:**
- [ ] Created date present
- [ ] Last Updated date present
- [ ] Status set correctly
- [ ] Priority assigned (if applicable)

---

## 📚 COMPLETE EXAMPLES

### Example 1: LESSON Entry

```markdown
# LESS-42: Sanitize Sentinels at Boundaries

**REF-ID:** LESS-42
**Category:** Architecture/Patterns
**Type:** Lesson Learned
**Created:** 2025-11-01
**Last Updated:** 2025-11-01
**Status:** Active
**Priority:** 🔴 Critical

## ðŸ"Š OVERVIEW

**Summary:** Sanitize implementation sentinels at layer boundaries before serialization. Internal sentinel objects fail JSON serialization and leak implementation details.

**Context:** Applies when passing data across architectural boundaries (Gateway â†' Interface â†' Core) or to external systems.

**Impact:** Prevents 535ms serialization penalties and JSON encoding failures. Reduces error rate by 94%.

## ðŸ"š LESSON CONTENT

### Problem
Sentinel objects used internally (e.g., _CacheMiss, _NotFound) leaked across boundaries causing JSON serialization failures.

### Discovery
After debugging, found that JSON encoder cannot serialize custom sentinel objects. They must be converted to standard types at boundaries.

### Principle
Implementation details must be sanitized at architectural boundaries. Never let internal representations cross into external interfaces.

### Application
At every boundary (Gateway/Interface/External), check for and convert sentinel objects to appropriate standard types (None, empty dict, etc.).

### Prevention
Add boundary sanitization layer. Use type checking at boundaries. Document sentinel usage clearly.

## 📚 EXAMPLES

### Example 1: Correct Sanitization

**Situation:**
Cache interface returns sentinel _CacheMiss

**Implementation:**
```python
def get_cached(key):
    value = cache.get(key)
    return None if value is _CacheMiss else value  # Sanitize at boundary
```

**Result:**
External code receives standard None, JSON serializes correctly.

## ðŸ"– KEYWORDS

sentinels, boundaries, serialization, sanitization, architecture, JSON, implementation-details, type-conversion

## 🔗 RELATED TOPICS

- ARCH-01: Three-layer architecture and boundaries
- INT-01: Cache interface pattern
- AP-10: Leaking implementation details

**See Also:** LESS-03, DEC-02, BUG-03

## 📋 CROSS-REFERENCES

**Architecture:** ARCH-01  
**Interfaces:** INT-01  
**Anti-Patterns:** AP-10  
**Lessons:** LESS-03
```

---

### Example 2: ANTI-PATTERN Entry

```markdown
# AP-25: Threading Locks in Single-Threaded Runtime

**REF-ID:** AP-25
**Category:** Concurrency/Patterns
**Type:** Anti-Pattern
**Created:** 2025-11-01
**Last Updated:** 2025-11-01
**Status:** Active
**Priority:** 🔴 Critical

## ðŸ"Š OVERVIEW

**Summary:** Using threading primitives (locks, semaphores) in single-threaded execution environments causes deadlocks and resource waste without providing any benefit.

**Context:** Applies to any single-threaded runtime (Lambda, some WASM, single-threaded event loops).

**Impact:** Causes deadlocks, wastes memory, adds unnecessary complexity, provides zero concurrency benefit.

## âš ï¸ ANTI-PATTERN

### Pattern
Adding threading locks, mutexes, or semaphores to coordinate access in runtime that executes single-threaded.

### Why Wrong
Threading primitives require multiple threads to be useful. In single-threaded runtime, locks either do nothing or cause immediate deadlock.

### Consequences
- Deadlocks on first access
- Memory waste (~40 bytes per lock)
- False sense of thread safety
- Confused maintainers

### Correct Approach
Verify runtime model first. In single-threaded runtimes, use simple state management. In multi-threaded, use appropriate concurrency primitives.

### Detection
Search for: threading.Lock(), threading.Semaphore(), mutex patterns in single-threaded codebases.

## 📚 EXAMPLES

### Example 1: Wrong Approach

**Situation:**
Lambda function using threading lock

**Wrong Approach:**
```python
lock = threading.Lock()  # Useless in Lambda!
with lock:
    process_data()
```

**Why It Fails:**
Lambda is single-threaded. Lock provides no benefit and may deadlock.

**Correct Approach:**
```python
# No lock needed - Lambda is single-threaded
process_data()
```

## ðŸ"– KEYWORDS

threading, locks, single-threaded, deadlock, concurrency, runtime-model, lambda, synchronization

## 🔗 RELATED TOPICS

- ARCH-02: Runtime environment constraints
- DEC-05: Runtime model verification
- LESS-08: Verify assumptions before implementation

**See Also:** AP-08, AP-11, BUG-02

## 📋 CROSS-REFERENCES

**Architecture:** ARCH-02  
**Decisions:** DEC-05  
**Lessons:** LESS-08  
**Anti-Patterns:** AP-08, AP-11
```

---

## 🎓 USAGE TIPS

**Tip 1: Genericize First**
Strip project-specific details. Extract universal principle. Make transferable.

**Tip 2: Be Brief**
Every word must earn its place. 2-3 sentence summaries. 2-5 line examples.

**Tip 3: Check Duplicates**
Always search before creating. Update existing entry instead of creating duplicate.

**Tip 4: Quantify Impact**
Use metrics: "535ms penalty", "94% error reduction", "3x faster". Makes lessons concrete.

**Tip 5: Cross-Reference Heavily**
Link related entries. Build knowledge graph. Enable discovery.

---

## 🔧 MAINTENANCE

**Update Frequency:** When entry content changes significantly

**Who Updates:** Entry creator or knowledge maintainers

**Update Process:**
1. Edit entry content
2. Update "Last Updated" date
3. Add update note if major change
4. Verify cross-references still valid
5. Update indexes if category/topic changed

---

## 📋 VALIDATION STEPS

**Pre-Creation:**
1. Search for duplicates
2. Confirm uniqueness
3. Verify REF-ID available

**Post-Creation:**
1. Run through quality checklist
2. Verify all cross-references valid
3. Confirm file location correct
4. Update relevant indexes
5. Test searchability with keywords

---

## ðŸ"— RELATED TEMPLATES

- **TMPL-02:** Project Documentation Template
- **TOOL-01:** REF-ID Directory
- **TOOL-02:** Quick Answer Index
- **Workflow-05:** Create NMP Entry

---

**END OF TEMPLATE**

**Template Version:** 1.0.0  
**Last Updated:** 2025-11-01  
**Maintained By:** SIMA Team  
**Usage Count:** Template (not tracked)
# File: Utility-01-NM-to-NMP-Migration.md

**REF-ID:** UTIL-01  
**Version:** 1.0.0  
**Category:** Support Tools  
**Type:** Migration Utility  
**Purpose:** Guide for migrating generic NM## entries to project-specific NMP## entries

---

## 📋 OVERVIEW

**Use when:** Need to create project-specific version of generic neural map entry

**Time:** 15-30 minutes per entry  
**Complexity:** Medium  
**Output:** New NMP## entry with project-specific implementation

---

## 🎯 WHEN TO MIGRATE

### Criteria for Migration

**Migrate when you have:**
- ✅ Concrete implementation of generic pattern
- ✅ Project-specific configuration or usage
- ✅ Real code examples from project
- ✅ Performance data from project
- ✅ Integration details specific to project

**Don't migrate if:**
- ❌ No project-specific details to add
- ❌ Would just duplicate generic content
- ❌ Pattern not yet implemented in project
- ❌ Better suited as update to generic entry

---

## 🔄 MIGRATION WORKFLOW

### Phase 1: Analysis (5 minutes)

**Step 1.1: Identify Source Entry**
```
Source: NM## generic entry
Example: INT-01 CACHE Interface
```

**Step 1.2: Check for Existing NMP**
```
Search: project_knowledge_search "[topic] NMP"
Verify: No duplicate NMP entry exists
```

**Step 1.3: Assess Project-Specific Content**
```
What to migrate:
- Actual function calls used
- Configuration values
- Performance measurements
- Integration patterns
- Error handling specifics
- Real code examples

What NOT to migrate:
- Generic pattern descriptions
- Theoretical examples
- Universal principles
- Architecture concepts
```

**Step 1.4: Assign NMP REF-ID**
```
Format: NMP##-[PROJECT]-##

Check: NMP Quick Index for next available number
Example: NMP01-LEE-17 (LEE project, entry 17)
```

---

### Phase 2: Content Extraction (10-15 minutes)

**Step 2.1: Copy Template**
```markdown
# File: NMP##-[PROJECT]-##_[Title].md

**REF-ID:** NMP##-[PROJECT]-##  
**Version:** 1.0.0  
**Category:** [Category from source]  
**Type:** [Project Pattern | Function Catalog | Integration Guide]  
**Project:** [PROJECT NAME]  
**Purpose:** [Project-specific purpose]

---

## 📋 OVERVIEW

**Context:** [Why this project-specific doc exists]  
**Scope:** [What aspects of source NM## this covers]  
**Base Pattern:** [Link to source NM##]

---

## 🎯 [MAIN CONTENT]

[Project-specific implementation details]

---

## 💡 CODE EXAMPLES

[Real examples from project]

---

## ⚠️ CONSIDERATIONS

[Project-specific gotchas, performance notes]

---

## 🔗 RELATED RESOURCES

**Base SIMA:**
- [Source NM##]
- [Related NM##]

**Project Patterns:**
- [Related NMP##]

**Implementation Tiers:**
- [ARCH/GATE/INT references]

---

**Keywords:** [4-8 keywords]  
**Related Topics:** [3-7 topics]
```

**Step 2.2: Extract Project-Specific Content**

**From Generic Entry:**
```markdown
# INT-01 CACHE Interface

## Functions
- get(key) → Retrieve cached value
- set(key, value, ttl) → Store value
```

**To Project-Specific:**
```markdown
# NMP01-LEE-02 INT-01 CACHE Function Catalog

## Functions Used in LEE

### cache_get
```python
from gateway import cache_get

result = cache_get(
    key="ha_entities",
    timeout=5,
    default={}
)
```

**Usage:** 8 locations in LEE
**Performance:** ~2ms average, 87% hit rate
**Cache TTL:** 30 seconds for HA entities
```

**Step 2.3: Add Real Code Examples**
```
Source: Actual project codebase
Include:
- Real function calls
- Actual parameters used
- Real error handling
- Production configurations
```

**Step 2.4: Add Performance Data**
```
If available:
- Execution times
- Cache hit rates
- Memory usage
- API call reduction
- Cold start impact
```

**Step 2.5: Add Integration Details**
```
Project-specific:
- How this integrates with other systems
- Configuration requirements
- Deployment considerations
- Known issues or workarounds
```

---

### Phase 3: Cross-Referencing (3-5 minutes)

**Step 3.1: Link to Source**
```markdown
## 🔗 RELATED RESOURCES

**Base SIMA:**
- INT-01: CACHE Interface (generic pattern)
- ARCH-01: SUGA Pattern (architecture)
```

**Step 3.2: Link to Related Project Entries**
```markdown
**Other Project Patterns:**
- NMP01-LEE-14: Gateway Execute Operation
- NMP01-LEE-16: Fast Path Optimization
```

**Step 3.3: Update Cross-Reference Matrix**
```
File: NMP##-[PROJECT]-Cross-Reference-Matrix.md

Add entry showing:
- NMP## ID
- Source NM## 
- Related NMP##
- Implementation tier (ARCH/GATE/INT)
```

**Step 3.4: Update Quick Index**
```
File: NMP##-[PROJECT]-Quick-Index.md

Add problem-based lookup:
"CACHE interface usage in LEE → NMP01-LEE-02"
"INT-01 function catalog → NMP01-LEE-02"
```

---

### Phase 4: Quality Check (2-3 minutes)

**Step 4.1: Verify Separation**
```
Check:
- [ ] No duplication of generic content
- [ ] All content is project-specific
- [ ] Real code examples (not theoretical)
- [ ] Links to base NM## present
- [ ] Clear why this is separate from NM##
```

**Step 4.2: Verify Completeness**
```
Check:
- [ ] File header with filename
- [ ] REF-ID assigned
- [ ] All sections present
- [ ] Code examples complete
- [ ] Cross-references added
- [ ] Keywords relevant (4-8)
- [ ] Related topics listed (3-7)
```

**Step 4.3: Verify Usefulness**
```
Ask:
- Can someone implement from this?
- Does this add value beyond generic entry?
- Are examples copy-paste ready?
- Is performance data included?
```

---

## 📊 MIGRATION MATRIX

### Source → Target Mapping

| Source Type | Target Type | Example |
|-------------|-------------|---------|
| ARCH-## (Pattern) | NMP-## Pattern | ARCH-04 → NMP01-LEE-16 (ZAPH impl) |
| GATE-## (Gateway) | NMP-## Pattern | GATE-01 → NMP01-LEE-14 (Gateway dispatch) |
| INT-## (Interface) | NMP-## Catalog | INT-01 → NMP01-LEE-02 (CACHE catalog) |
| LESS-## (Lesson) | NMP-## Lesson | LESS-## → NMP01-LEE-## (Project lesson) |
| BUG-## (Bug) | NMP-## Bug | BUG-## → NMP01-LEE-## (Project bug) |

### Content Transformation

| Generic Content | Project-Specific Content |
|-----------------|--------------------------|
| Theoretical example | Real code from project |
| "Use cache_get()" | Actual usage: cache_get("ha_entities", timeout=5) |
| "Improves performance" | "Reduces API calls 87%, latency from 200ms to 5ms" |
| "Configure as needed" | "Use TTL=30s for HA entities, 300s for weather" |
| Generic error handling | Actual error patterns encountered |

---

## 🎓 MIGRATION EXAMPLES

### Example 1: Interface to Function Catalog

**Source: INT-01 CACHE Interface**
```markdown
# INT-01: CACHE Interface

Generic description of CACHE interface...

## Functions
- get(key) → Retrieve value
- set(key, value, ttl) → Store value
- clear() → Clear cache
```

**Target: NMP01-LEE-02 CACHE Function Catalog**
```markdown
# File: NMP01-LEE-02_INT-01-CACHE-Function-Catalog.md

**REF-ID:** NMP01-LEE-02  
**Project:** LEE (Lambda Execution Environment)

## Functions Used in LEE

### cache_get
```python
from gateway import cache_get

result = cache_get(
    key="ha_entities",
    timeout=5,
    default={}
)
```

**Used in:** 8 locations
**Performance:** ~2ms, 87% hit rate
**Common keys:**
- "ha_entities" (TTL: 30s)
- "ha_config" (TTL: 300s)
- "api_response_{id}" (TTL: 60s)

### cache_set
```python
from gateway import cache_set

cache_set(
    key="ha_entities",
    value=entities,
    ttl=30
)
```

**Used in:** 6 locations
**TTL Strategy:**
- Dynamic data: 30s
- Static config: 300s
- API responses: 60-120s
```

---

### Example 2: Architecture Pattern to Implementation

**Source: ARCH-04 ZAPH Pattern**
```markdown
# ARCH-04: ZAPH (Zero-cost Abstraction Performance Helper)

Generic description of ZAPH pattern for performance...

## Concept
Defer heavy imports until needed...

## Benefits
- Reduces cold start
- Lazy loading
```

**Target: NMP01-LEE-16 ZAPH Implementation**
```markdown
# File: NMP01-LEE-16_Fast-Path-Optimization-ZAPH-Pattern.md

**REF-ID:** NMP01-LEE-16  
**Project:** LEE

## ZAPH Implementation in LEE

### Cold Start Optimization
**Before ZAPH:** 3.2s cold start  
**After ZAPH:** 890ms cold start  
**Improvement:** 72% reduction

### Heavy Modules Deferred
```python
# NOT imported at module level:
# - home_assistant (45MB)
# - numpy (82MB)
# - cryptography (35MB)

# Imported only when needed:
def ha_operation():
    import home_assistant  # Lazy
    return home_assistant.operation()
```

### Performance Measurements
- Fast path (no HA): 125ms average
- HA path (first call): 890ms
- HA path (subsequent): 145ms

### Implementation Details
Gateway lazy loading + conditional imports...
[Real code from LEE]
```

---

## ⚠️ MIGRATION PITFALLS

### Pitfall 1: Duplicating Generic Content
```
❌ WRONG:
Copy entire NM## entry and call it NMP##

✅ CORRECT:
Reference NM## and add only project-specific details
```

### Pitfall 2: No Real Examples
```
❌ WRONG:
"Use cache_get to retrieve values"

✅ CORRECT:
from gateway import cache_get
result = cache_get("ha_entities", timeout=5, default={})
# Used in: ha_operations.py line 45
```

### Pitfall 3: Missing Performance Data
```
❌ WRONG:
"This improves performance"

✅ CORRECT:
"Reduces API calls from 150/min to 20/min (87% reduction)
Latency: 200ms → 5ms average"
```

### Pitfall 4: No Cross-References
```
❌ WRONG:
Create isolated NMP entry

✅ CORRECT:
Link to source NM##, related NMP##, and implementation tiers
```

---

## 📚 MIGRATION CHECKLIST

### Pre-Migration
- [ ] Source NM## entry identified
- [ ] Project-specific content available
- [ ] No existing NMP duplicate
- [ ] Migration justified (adds value)
- [ ] Next NMP REF-ID determined

### During Migration
- [ ] Template copied
- [ ] Project-specific content extracted
- [ ] Real code examples added
- [ ] Performance data included
- [ ] Integration details documented
- [ ] No generic duplication

### Post-Migration
- [ ] Cross-references to source NM##
- [ ] Links to related NMP##
- [ ] Cross-reference matrix updated
- [ ] Quick index updated
- [ ] Quality check passed
- [ ] File header includes filename

---

## 🔗 RELATED RESOURCES

**Migration Process:**
- WF-05: Create NMP Entry (detailed workflow)
- CHK-03: Documentation Quality (quality standards)

**Project Documentation:**
- NMP##-[PROJECT]-Cross-Reference-Matrix
- NMP##-[PROJECT]-Quick-Index

**Source Patterns:**
- ARCH-## (Architecture patterns)
- GATE-## (Gateway patterns)
- INT-## (Interface patterns)
- LANG-## (Language patterns)

**Examples:**
- NMP01-LEE-02 (INT-01 migration)
- NMP01-LEE-16 (ARCH-04 migration)
- NMP01-LEE-17 (HA integration)

---

## 🎯 SUCCESS CRITERIA

Migration successful when:
- ✅ Clear separation from source NM##
- ✅ Project-specific implementation documented
- ✅ Real code examples present
- ✅ Performance data included (if available)
- ✅ Cross-references complete
- ✅ No generic content duplication
- ✅ Adds value beyond source entry
- ✅ Indexes updated
- ✅ Filename in header

---

**END OF UTILITY-01**

**Related tools:** WF-05 (Create NMP Entry), CHK-03 (Documentation Quality)

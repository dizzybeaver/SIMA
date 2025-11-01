# File: Workflow-05-Create-NMP-Entry.md

**REF-ID:** WF-05  
**Version:** 1.0.0  
**Category:** Support Tools  
**Type:** Workflow Template  
**Purpose:** Create project-specific neural map entries

---

## 📋 WORKFLOW OVERVIEW

**Use when:** Documenting project-specific implementation patterns  
**Time:** 15-30 minutes  
**Complexity:** Medium  
**Prerequisites:** Implementation complete, pattern identified

---

## ✅ PRE-WORK CHECKLIST

Before starting:
- [ ] Implementation tested and working
- [ ] Pattern is project-specific (not generic)
- [ ] No duplicate NMP entry exists
- [ ] Related NM## entries identified
- [ ] Cross-references prepared

---

## 🎯 PHASE 1: PLANNING (5 minutes)

### Step 1.1: Verify Project-Specificity
```
Ask: Is this truly project-specific?

✅ PROJECT-SPECIFIC (NMP):
- Specific implementation details
- Function catalogs for this project
- Integration patterns with project systems
- Performance characteristics in this project
- Project-specific configurations

❌ GENERIC (belongs in NM##):
- Universal patterns
- Architecture concepts
- Language-agnostic principles
- Transferable lessons
```

### Step 1.2: Check for Duplicates
```
Search existing NMP entries:
project_knowledge_search: "[topic] NMP"

Check:
- Similar implementation already documented?
- Can existing entry be updated instead?
- Is this truly new knowledge?
```

### Step 1.3: Assign REF-ID
```
Format: NMP##-[PROJECT]-##

Examples:
NMP01-LEE-02   → LEE project, entry 02
NMP01-LEE-14   → LEE project, entry 14
NMP02-SIMA-01  → SIMA project, entry 01

Check next available number in NMP Quick Index.
```

### Step 1.4: Identify Category
```
Common NMP categories:
- Function Catalogs (interface usage)
- Integration Patterns (external systems)
- Gateway Patterns (dispatch logic)
- Performance Patterns (optimizations)
- Configuration Patterns (setup)
- Resilience Patterns (error handling)
```

---

## 🔧 PHASE 2: CONTENT CREATION (15-20 minutes)

### Step 2.1: Use Standard Template
```markdown
# File: NMP##-[PROJECT]-##_[Title].md

**REF-ID:** NMP##-[PROJECT]-##  
**Version:** 1.0.0  
**Category:** [Category]  
**Type:** [Project Pattern | Function Catalog | Integration Guide]  
**Project:** [PROJECT NAME]  
**Purpose:** [One sentence purpose]

---

## 📋 OVERVIEW

**Context:** [Why this exists]  
**Scope:** [What this covers]  
**Related Base Patterns:** [NM## references]

---

## 🎯 PATTERN / IMPLEMENTATION

[Main content - specific implementation details]

### Key Characteristics

[Bullet points of key features]

### Usage in Project

[How this is used in the project]

---

## 💡 CODE EXAMPLES

[Concrete examples from the project]

---

## ⚠️ CONSIDERATIONS

[Project-specific gotchas, performance notes, etc.]

---

## 🔗 RELATED RESOURCES

**Base SIMA:**
- [NM## references]

**Other Project Patterns:**
- [NMP## references]

**Implementation Tiers:**
- [ARCH/GATE/INT references]

---

**Keywords:** [4-8 keywords]  
**Related Topics:** [3-7 topics]
```

### Step 2.2: Write Clear Overview
```
Include:
- What this documents
- Why it's project-specific
- Which base patterns it builds on
- Scope boundaries
```

### Step 2.3: Document Implementation Details
```
Be specific:
- Actual function signatures used
- Real parameter values
- Concrete examples from codebase
- Performance measurements
- Configuration specifics

Don't genericize - this is project documentation!
```

### Step 2.4: Add Code Examples
```
Include:
- Real code from project
- Actual usage patterns
- Complete examples (not fragments)
- Error handling shown
- Performance considerations noted
```

### Step 2.5: Add Cross-References
```
Link to:
- Base NM## entries (generic patterns)
- Other NMP## entries (related project patterns)
- INT-## entries (interfaces used)
- ARCH/GATE entries (architecture patterns)
```

---

## 📝 PHASE 3: INTEGRATION (5 minutes)

### Step 3.1: Update Cross-Reference Matrix
```
File: NMP##-[PROJECT]-Cross-Reference-Matrix.md

Add entry showing relationships to:
- Base SIMA (NM##)
- Implementation Tiers (ARCH/GATE/INT)
- Other NMP entries
```

### Step 3.2: Update Quick Index
```
File: NMP##-[PROJECT]-Quick-Index.md

Add problem-based lookup:
"When you need [specific task] → NMP##-[PROJECT]-##"
```

### Step 3.3: Update Project README (if exists)
```
Add entry to project documentation index
Link to new NMP entry
Update entry count
```

---

## ✅ PHASE 4: VALIDATION (5 minutes)

### Step 4.1: Quality Checklist
```
- [ ] Title describes specific implementation
- [ ] REF-ID follows format
- [ ] Overview clearly states why project-specific
- [ ] Implementation details are concrete
- [ ] Code examples are complete and real
- [ ] Cross-references complete
- [ ] Keywords relevant (4-8)
- [ ] Related topics listed (3-7)
- [ ] No generic content (belongs in NM##)
- [ ] File header includes filename
```

### Step 4.2: Separation Verification
```
Verify clear separation from base SIMA:
- Does NOT duplicate NM## generic content
- DOES build on NM## with specifics
- DOES document actual implementation
- DOES include project context
```

### Step 4.3: Completeness Check
```
Entry is complete when:
- Someone can implement from this doc
- Examples are copy-paste ready
- All cross-references valid
- Performance characteristics noted
- Error handling documented
```

---

## ⚠️ COMMON PITFALLS

### Pitfall 1: Generic Content in NMP
```
❌ DON'T:
NMP01-LEE-XX: "Gateway pattern routes requests"

✅ DO:
NMP01-LEE-XX: "LEE gateway.py execute_operation() 
dispatch logic with 12 interfaces"
```

### Pitfall 2: Incomplete Examples
```
❌ DON'T:
"Use cache_get to retrieve values"

✅ DO:
from gateway import cache_get
result = cache_get(
    key="ha_entities",
    timeout=5,
    default={}
)
```

### Pitfall 3: Missing Cross-References
```
❌ DON'T:
Create isolated NMP entry

✅ DO:
Link to:
- Base pattern (NM##)
- Related interfaces (INT-##)
- Related project patterns (NMP##)
```

### Pitfall 4: Duplication
```
❌ DON'T:
Create NMP when similar entry exists

✅ DO:
Search first
Update existing if similar
Create new only if truly unique
```

---

## 🎓 EXAMPLE WALKTHROUGH

### Example: Document CACHE Interface Usage in LEE

**Step 1: Planning**
```
Topic: How LEE project uses INT-01 CACHE
Project-Specific: Yes (actual function calls, configs)
Duplicate Check: No existing CACHE catalog for LEE
REF-ID: NMP01-LEE-02
Category: Function Catalog
```

**Step 2: Content Creation**

**Template Applied:**
```markdown
# File: NMP01-LEE-02_INT-01-CACHE-Function-Catalog.md

**REF-ID:** NMP01-LEE-02  
**Version:** 1.0.0  
**Category:** Interface Patterns  
**Type:** Function Catalog  
**Project:** LEE (Lambda Execution Environment)  
**Purpose:** Complete CACHE interface usage catalog in LEE project

## 📋 OVERVIEW

Documents all CACHE interface functions used in LEE, with:
- Actual usage patterns
- Performance characteristics
- Configuration specifics
- Error handling

**Base Pattern:** INT-01 CACHE (generic interface)

## 🎯 FUNCTIONS USED

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
**Performance:** ~2ms average
**Cache hit rate:** 87%

[... continue with all functions ...]
```

**Step 3: Integration**

**Update Cross-Reference:**
```markdown
| NMP Entry | Base SIMA | Implementation |
|-----------|-----------|----------------|
| NMP01-LEE-02 | INT-01 | CACHE usage in LEE |
```

**Update Quick Index:**
```markdown
"CACHE interface usage in LEE → NMP01-LEE-02"
"INT-01 function catalog → NMP01-LEE-02"
```

**Step 4: Validation**
```
✅ Project-specific (LEE usage patterns)
✅ No duplication (first CACHE catalog)
✅ Complete examples (all functions shown)
✅ Cross-references present (INT-01 linked)
✅ Performance data included (real metrics)
```

---

## 📊 SUCCESS CRITERIA

NMP entry creation complete when:
- ✅ Entry is project-specific (not generic)
- ✅ No duplication (unique contribution)
- ✅ Complete implementation details
- ✅ Code examples are real and complete
- ✅ Cross-references added
- ✅ Indexes updated
- ✅ Quality checklist passed
- ✅ Clear separation from base SIMA
- ✅ Filename in header

---

## 🔗 RELATED RESOURCES

**Project NMPs:**
- NMP01-LEE Quick Index
- NMP01-LEE Cross-Reference Matrix

**Base SIMA:**
- NM## entries (generic patterns)
- INT-## entries (interfaces)
- ARCH/GATE entries (architecture)

**Workflows:**
- WF-01: Add Feature (generates NMP content)
- WF-02: Debug Issue (generates bug NMPs)

---

## 🎯 NMP CATEGORIES

**Function Catalogs:**
- Interface usage (INT-## functions in project)
- Gateway operations (gateway.py specifics)

**Integration Patterns:**
- External API usage (HA, AWS, etc.)
- Third-party library integration

**Performance Patterns:**
- Optimization techniques used
- ZAPH implementation
- Cold start strategies

**Resilience Patterns:**
- Circuit breaker implementation
- Error handling specifics
- Retry logic

**Configuration Patterns:**
- Project config structure
- Environment-specific settings

---

**END OF WORKFLOW-05**

**Related workflows:** WF-01 (Add Feature), WF-02 (Debug Issue)

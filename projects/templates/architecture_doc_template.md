# File: architecture_doc_template.md

**Template Version:** 1.0.0  
**Purpose:** Architecture documentation template  
**Location:** `/sima/projects/templates/`

---

# File: [PROJECT]-ARCH-[Topic].md

**Category:** Project Architecture Documentation  
**Project:** [Project Name] ([PROJECT_CODE])  
**Status:** Active  
**Created:** YYYY-MM-DD  
**Last Updated:** YYYY-MM-DD

---

## ðŸ"‹ OVERVIEW

[2-3 sentence high-level description of the architecture topic]

**Scope:** [What this document covers]  
**Audience:** [Who should read this - developers, architects, ops]

---

## ðŸŽ¯ PURPOSE

### Why This Architecture
[Explanation of why this architectural approach was chosen]

**Key Goals:**
- Goal 1: [Description]
- Goal 2: [Description]
- Goal 3: [Description]

### Problems Solved
- Problem 1: [How architecture solves it]
- Problem 2: [How architecture solves it]
- Problem 3: [How architecture solves it]

---

## ðŸ—ºï¸ ARCHITECTURE OVERVIEW

### High-Level Structure
```
┌─────────────────────────────────────┐
│         [Component/Layer 1]         │
│  [Brief description]                │
└──────────────┬──────────────────────┘
               â"‚
┌──────────────▼──────────────────────┐
│         [Component/Layer 2]         │
│  [Brief description]                │
└──────────────┬──────────────────────┘
               â"‚
┌──────────────▼──────────────────────┐
│         [Component/Layer 3]         │
│  [Brief description]                │
└─────────────────────────────────────┘
```

### Components
1. **[Component 1]:** [Purpose and responsibility]
2. **[Component 2]:** [Purpose and responsibility]
3. **[Component 3]:** [Purpose and responsibility]

---

## ðŸ"¦ DETAILED ARCHITECTURE

### Component 1: [Name]

**Responsibility:** [What it does]

**Structure:**
```
component_1/
â"œâ"€â"€ module_1.py          # [Purpose]
â"œâ"€â"€ module_2.py          # [Purpose]
â""â"€â"€ module_3.py          # [Purpose]
```

**Key Classes/Functions:**
- `ClassName` - [Purpose]
- `function_name()` - [Purpose]

**Dependencies:**
- Internal: [Other components it depends on]
- External: [External libraries/services]

**Interfaces:**
- Input: [What it receives]
- Output: [What it returns]

---

### Component 2: [Name]

**Responsibility:** [What it does]

**Structure:**
```
component_2/
â"œâ"€â"€ module_1.py
â""â"€â"€ module_2.py
```

**Key Classes/Functions:**
- `ClassName` - [Purpose]
- `function_name()` - [Purpose]

**Dependencies:**
- Internal: [Dependencies]
- External: [Dependencies]

**Interfaces:**
- Input: [What it receives]
- Output: [What it returns]

---

## ðŸ"„ DATA FLOW

### Primary Flow
```
User Request â†' [Component 1] â†' [Component 2] â†' [Component 3] â†' Response
              [Processing]      [Transform]      [Execute]
```

### Step-by-Step
1. **Input:** [Component 1] receives [data type]
   - Validates: [What's validated]
   - Transforms: [How data is transformed]

2. **Processing:** [Component 2] processes [data]
   - Applies: [What rules/logic]
   - Produces: [Intermediate result]

3. **Output:** [Component 3] generates [result]
   - Formats: [How result is formatted]
   - Returns: [What's returned]

### Error Flow
```
Error â†' [Error Handler] â†' [Logger] â†' [Response]
         [Classify]        [Record]      [Format]
```

---

## ðŸ"§ CONFIGURATION

### Architecture Parameters
```python
ARCHITECTURE_CONFIG = {
    'param_1': 'value',    # Description
    'param_2': 'value',    # Description
    'param_3': 'value'     # Description
}
```

### Environment Variables
```bash
ARCH_VAR_1=value         # Purpose
ARCH_VAR_2=value         # Purpose
```

### Feature Flags
- `feature_1_enabled` - [Description and default]
- `feature_2_enabled` - [Description and default]

---

## 🎨 DESIGN PATTERNS

### Pattern 1: [Pattern Name]
**Used In:** [Component(s)]  
**Purpose:** [Why this pattern]

**Implementation:**
```python
# Example of pattern usage
class PatternExample:
    def method(self):
        # Implementation
        pass
```

**Benefits:**
- Benefit 1
- Benefit 2

---

### Pattern 2: [Pattern Name]
**Used In:** [Component(s)]  
**Purpose:** [Why this pattern]

**Benefits:**
- Benefit 1
- Benefit 2

---

## âš–ï¸ TRADE-OFFS

### Advantages
1. **Advantage 1:** [Explanation]
2. **Advantage 2:** [Explanation]
3. **Advantage 3:** [Explanation]

### Limitations
1. **Limitation 1:** [Description and impact]
   - Mitigation: [How we handle this]

2. **Limitation 2:** [Description and impact]
   - Mitigation: [How we handle this]

### Alternative Approaches Considered
**Alternative 1:** [Name]
- Why not chosen: [Reason]

**Alternative 2:** [Name]
- Why not chosen: [Reason]

---

## ðŸ"Š PERFORMANCE

### Characteristics
- **Throughput:** [X operations/second]
- **Latency:** [P50: X ms, P95: Y ms, P99: Z ms]
- **Resource Usage:** [Memory, CPU typical loads]

### Optimization Points
1. **Optimization 1:** [Description]
   - Impact: [Performance improvement]
   - Trade-off: [What's accepted]

2. **Optimization 2:** [Description]
   - Impact: [Performance improvement]
   - Trade-off: [What's accepted]

### Bottlenecks
- **Bottleneck 1:** [Description]
  - Mitigation: [How addressed]
  
- **Bottleneck 2:** [Description]
  - Mitigation: [How addressed]

---

## 🔒 SECURITY

### Security Considerations
1. **Concern 1:** [Description]
   - Mitigation: [How addressed]

2. **Concern 2:** [Description]
   - Mitigation: [How addressed]

### Authentication/Authorization
[How security is implemented]

### Data Protection
- Encryption: [What's encrypted and how]
- Access Control: [Who can access what]

---

## ðŸ§ª TESTING

### Test Strategy
**Unit Tests:**
- Coverage: [X%]
- Focus areas: [What's tested]

**Integration Tests:**
- Coverage: [Y%]
- Focus areas: [What's tested]

**System Tests:**
- Scenarios: [What's tested end-to-end]

### Test Locations
- Unit: `tests/unit/[component]/`
- Integration: `tests/integration/`
- System: `tests/system/`

---

## ðŸš€ DEPLOYMENT

### Deployment Architecture
[How this is deployed - single server, distributed, serverless, etc.]

### Scaling Strategy
- **Vertical:** [How to scale up]
- **Horizontal:** [How to scale out]
- **Limits:** [Known scaling limits]

### High Availability
- **Redundancy:** [How redundancy is achieved]
- **Failover:** [How failover works]
- **Recovery:** [Recovery time objectives]

---

## ðŸ"® EVOLUTION

### Current State
[Where we are now with this architecture]

### Planned Improvements
1. **Improvement 1:** [Description]
   - Timeline: [When]
   - Benefit: [Expected outcome]

2. **Improvement 2:** [Description]
   - Timeline: [When]
   - Benefit: [Expected outcome]

### Migration Path
[If architecture needs to evolve, how to get there]

---

## ðŸ"— RELATED DOCUMENTATION

### SIMA Entries
- ARCH-## - [Generic pattern this follows]
- GATE-## - [Gateway pattern used]
- INT-## - [Interface pattern used]

### Project NMPs
- NMP##-PROJECT-## - [Related NMP]
- NMP##-PROJECT-## - [Related NMP]

### External Resources
- [Resource name] - [URL]
- [Resource name] - [URL]

---

## ðŸ·ï¸ KEYWORDS

`architecture`, `[project-code]`, `[pattern]`, `[technology]`, `design`

---

## ðŸ" VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0.0 | YYYY-MM-DD | [Name] | Initial architecture document |
| 1.1.0 | YYYY-MM-DD | [Name] | Added performance section |

---

**END OF ARCHITECTURE DOCUMENTATION**

**Maintenance:**
- Review quarterly or after major changes
- Keep diagrams current with implementation
- Update performance metrics regularly
- Document architectural decisions that modify this

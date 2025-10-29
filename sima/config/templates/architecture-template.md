# [ARCH-CODE]: [ARCHITECTURE_NAME]

**REF-ID:** ARCH-[XX]  
**Version:** 1.0.0  
**Category:** Architecture Pattern  
**Status:** [Active/Proposed/Deprecated]  
**Created:** [YYYY-MM-DD]  
**Last Updated:** [YYYY-MM-DD]

---

## 📋 OVERVIEW

**Architecture Name:** [Full architecture name]  
**Short Code:** [ARCH-CODE]  
**Type:** [Pattern/Framework/Methodology]  
**Scope:** [Module-level/System-level/Enterprise-level]

**One-Line Description:**  
[Single sentence describing the essence of this architecture]

**Primary Purpose:**  
[2-3 sentences explaining why this architecture exists and what problems it solves]

---

## 🎯 APPLICABILITY

### When to Use
✅ Use this architecture when:
- [Condition 1]
- [Condition 2]
- [Condition 3]
- [Condition 4]

### When NOT to Use
❌ Do NOT use this architecture when:
- [Condition 1]
- [Condition 2]
- [Condition 3]
- [Condition 4]

### Best For
- **Project Size:** [Small/Medium/Large/Enterprise]
- **Team Size:** [1-2/3-5/6-10/10+]
- **Complexity:** [Low/Medium/High]
- **Change Frequency:** [Stable/Moderate/High]

---

## 🏗️ STRUCTURE

### Core Components

**Component 1: [COMPONENT_NAME]**
- **Purpose:** [What this component does]
- **Responsibilities:** [Key responsibilities]
- **Dependencies:** [What it depends on]
- **Interface:** [How others interact with it]

**Component 2: [COMPONENT_NAME]**
- **Purpose:** [What this component does]
- **Responsibilities:** [Key responsibilities]
- **Dependencies:** [What it depends on]
- **Interface:** [How others interact with it]

**Component 3: [COMPONENT_NAME]**
- **Purpose:** [What this component does]
- **Responsibilities:** [Key responsibilities]
- **Dependencies:** [What it depends on]
- **Interface:** [How others interact with it]

### Component Diagram

```
┌─────────────────────────────────────┐
│     [COMPONENT_1]                   │
│  [Brief description]                │
└───────────┬─────────────────────────┘
            │
            ↓
┌─────────────────────────────────────┐
│     [COMPONENT_2]                   │
│  [Brief description]                │
└───────────┬─────────────────────────┘
            │
            ↓
┌─────────────────────────────────────┐
│     [COMPONENT_3]                   │
│  [Brief description]                │
└─────────────────────────────────────┘
```

### Information Flow

**Primary Flow:**
```
[Input] → [Component 1] → [Component 2] → [Component 3] → [Output]
```

**Error Flow:**
```
[Error] → [Handler] → [Recovery/Escalation]
```

**Feedback Loop:**
```
[Monitoring] → [Analysis] → [Adjustment] → [Components]
```

---

## ⚙️ IMPLEMENTATION PATTERNS

### Pattern 1: [PATTERN_NAME]

**Context:**
[When this pattern is used within the architecture]

**Implementation:**
```
[Pseudocode or language-agnostic example]

Example:
def pattern_example():
    """Show the pattern structure."""
    initialize_component()
    process_through_layers()
    return_result()
```

**Key Principles:**
1. [Principle 1]
2. [Principle 2]
3. [Principle 3]

### Pattern 2: [PATTERN_NAME]

**Context:**
[When this pattern is used within the architecture]

**Implementation:**
```
[Pseudocode or language-agnostic example]
```

**Key Principles:**
1. [Principle 1]
2. [Principle 2]
3. [Principle 3]

---

## 🔧 CONFIGURATION

### Required Configuration

**Config Item 1: [CONFIG_NAME]**
- **Type:** [String/Integer/Boolean/Object]
- **Required:** Yes/No
- **Default:** [Default value if any]
- **Purpose:** [What this configures]
- **Example:** `[example value]`

**Config Item 2: [CONFIG_NAME]**
- **Type:** [String/Integer/Boolean/Object]
- **Required:** Yes/No
- **Default:** [Default value if any]
- **Purpose:** [What this configures]
- **Example:** `[example value]`

### Optional Configuration

**Config Item 3: [CONFIG_NAME]**
- **Type:** [String/Integer/Boolean/Object]
- **Default:** [Default value]
- **Purpose:** [What this configures]
- **Trade-offs:** [Performance/Memory/Complexity impacts]

### Environment Variables

**ENV_VAR_1**
- **Purpose:** [What it controls]
- **Format:** [Expected format]
- **Example:** `ENV_VAR_1=value`

---

## 📜 RULES & CONSTRAINTS

### Mandatory Rules

**RULE-01: [RULE_NAME]**
- **Statement:** [Clear rule statement]
- **Rationale:** [Why this rule exists]
- **Enforcement:** [How it's enforced]
- **Violation Impact:** [What happens if violated]

**RULE-02: [RULE_NAME]**
- **Statement:** [Clear rule statement]
- **Rationale:** [Why this rule exists]
- **Enforcement:** [How it's enforced]
- **Violation Impact:** [What happens if violated]

### Best Practices

**BEST-01: [PRACTICE_NAME]**
- **Recommendation:** [What to do]
- **Benefit:** [Why it helps]
- **Example:** [Code or scenario example]

**BEST-02: [PRACTICE_NAME]**
- **Recommendation:** [What to do]
- **Benefit:** [Why it helps]
- **Example:** [Code or scenario example]

### Anti-Patterns

**ANTI-01: [ANTI_PATTERN_NAME]**
- **Problem:** [What NOT to do]
- **Why It's Bad:** [Consequences]
- **Instead:** [Correct approach]
- **Related:** [REF-ID to anti-pattern entry if exists]

**ANTI-02: [ANTI_PATTERN_NAME]**
- **Problem:** [What NOT to do]
- **Why It's Bad:** [Consequences]
- **Instead:** [Correct approach]
- **Related:** [REF-ID to anti-pattern entry if exists]

---

## 🌍 LANGUAGE IMPLEMENTATIONS

### Python Implementation

**Compatibility:** Python [X.Y+]

**Key Patterns:**
```python
# Pattern example
class ComponentExample:
    """Example implementation."""
    
    def __init__(self):
        """Initialize component."""
        pass
    
    def process(self, input_data):
        """Process data through architecture."""
        pass
```

**Specific Constraints:**
- [Constraint 1]
- [Constraint 2]
- **See:** `/sima/entries/languages/PYTH/[specific files]`

### JavaScript Implementation

**Compatibility:** ES[X]+ / Node [X.Y+]

**Key Patterns:**
```javascript
// Pattern example
class ComponentExample {
  constructor() {
    // Initialize
  }
  
  process(inputData) {
    // Process
  }
}
```

**Specific Constraints:**
- [Constraint 1]
- [Constraint 2]
- **See:** `/sima/entries/languages/JS/[specific files]`

### Other Languages

**[LANGUAGE_NAME]:** [Brief notes or "See language-specific entry"]  
**[LANGUAGE_NAME]:** [Brief notes or "See language-specific entry"]

---

## 🔗 DEPENDENCIES

### Core Dependencies

**Dependency 1: [DEPENDENCY_NAME]**
- **Type:** [Library/Framework/Pattern]
- **Purpose:** [Why it's needed]
- **Version:** [X.Y+]
- **Optional:** Yes/No

**Dependency 2: [DEPENDENCY_NAME]**
- **Type:** [Library/Framework/Pattern]
- **Purpose:** [Why it's needed]
- **Version:** [X.Y+]
- **Optional:** Yes/No

### Architectural Dependencies

**Required Patterns:**
- [Pattern 1]: [Why required]
- [Pattern 2]: [Why required]

**Compatible With:**
- [ARCH-CODE]: [Architecture name]
- [ARCH-CODE]: [Architecture name]

**Incompatible With:**
- [ARCH-CODE]: [Architecture name] - [Reason]
- [ARCH-CODE]: [Architecture name] - [Reason]

---

## ⚡ PERFORMANCE CHARACTERISTICS

### Time Complexity
- **Initialization:** O([complexity])
- **Operation:** O([complexity])
- **Cleanup:** O([complexity])

### Space Complexity
- **Memory Overhead:** [Description]
- **Storage Requirements:** [Description]

### Scaling Characteristics
- **Horizontal Scaling:** [Easy/Moderate/Difficult] - [Explanation]
- **Vertical Scaling:** [Easy/Moderate/Difficult] - [Explanation]
- **Bottlenecks:** [List potential bottlenecks]

### Benchmarks
- **Small Scale:** [Metrics]
- **Medium Scale:** [Metrics]
- **Large Scale:** [Metrics]

---

## 🛡️ ERROR HANDLING

### Error Strategies

**Strategy 1: [STRATEGY_NAME]**
- **When:** [Conditions when this strategy applies]
- **Action:** [What happens]
- **Recovery:** [How system recovers]

**Strategy 2: [STRATEGY_NAME]**
- **When:** [Conditions when this strategy applies]
- **Action:** [What happens]
- **Recovery:** [How system recovers]

### Common Errors

**ERROR-01: [ERROR_NAME]**
- **Cause:** [What causes this error]
- **Symptom:** [How it manifests]
- **Resolution:** [How to fix]
- **Prevention:** [How to avoid]

**ERROR-02: [ERROR_NAME]**
- **Cause:** [What causes this error]
- **Symptom:** [How it manifests]
- **Resolution:** [How to fix]
- **Prevention:** [How to avoid]

---

## 🧪 TESTING STRATEGIES

### Unit Testing
- **Component Level:** [What to test]
- **Isolation:** [How to isolate components]
- **Mock Requirements:** [What needs mocking]

### Integration Testing
- **Component Interaction:** [What to test]
- **Data Flow:** [How to verify flow]
- **Error Propagation:** [How to test errors]

### Performance Testing
- **Load Tests:** [What to measure]
- **Stress Tests:** [What to measure]
- **Benchmarks:** [What to track]

---

## 📊 METRICS & MONITORING

### Key Metrics

**Metric 1: [METRIC_NAME]**
- **What It Measures:** [Description]
- **Target:** [Target value]
- **Alert Threshold:** [When to alert]

**Metric 2: [METRIC_NAME]**
- **What It Measures:** [Description]
- **Target:** [Target value]
- **Alert Threshold:** [When to alert]

### Health Indicators
- [Indicator 1]: [What it shows]
- [Indicator 2]: [What it shows]
- [Indicator 3]: [What it shows]

### Logging Recommendations
- **Log Level:** [Recommended default]
- **Critical Events:** [What must be logged]
- **Debug Events:** [What to log in debug mode]

---

## 💡 EXAMPLES

### Example 1: [SCENARIO_NAME]

**Scenario:**
[Description of use case]

**Implementation:**
```
[Code example showing architecture in action]
```

**Key Points:**
- [Point 1]
- [Point 2]
- [Point 3]

### Example 2: [SCENARIO_NAME]

**Scenario:**
[Description of use case]

**Implementation:**
```
[Code example showing architecture in action]
```

**Key Points:**
- [Point 1]
- [Point 2]
- [Point 3]

---

## 🔄 EVOLUTION & VERSIONING

### Version History

**v1.0.0** (YYYY-MM-DD)
- Initial architecture definition
- Core patterns established

**v1.1.0** (YYYY-MM-DD)
- [Changes made]
- [New patterns added]

### Future Considerations
- [Potential enhancement 1]
- [Potential enhancement 2]
- [Known limitations to address]

### Deprecation Path
**If This Architecture Is Deprecated:**
- **Reason:** [Why it's being deprecated]
- **Replacement:** [What replaces it]
- **Migration Guide:** [Link or brief steps]
- **Support Timeline:** [How long it's supported]

---

## 📚 REFERENCES

### Internal References
- **Gateway Patterns:** `/sima/gateways/[RELEVANT_GATEWAYS]`
- **Interface Patterns:** `/sima/interfaces/[RELEVANT_INTERFACES]`
- **Related Architectures:** [ARCH-CODES]

### External References
- **Original Paper/Blog:** [URL]
- **Framework Documentation:** [URL]
- **Community Resources:** [URL]

### Related Entries
- **Combinations:** [COMB-REF-IDs that use this architecture]
- **Constraints:** [CONS-REF-IDs specific to this architecture]
- **Lessons:** [LESS-REF-IDs learned from using this]
- **Decisions:** [DEC-REF-IDs about architecture choices]

---

## 🤝 CONTRIBUTORS

**Original Author:** [Name]  
**Major Contributors:**
- [Name] - [Contribution]
- [Name] - [Contribution]

**Last Reviewed By:** [Name]  
**Review Date:** [YYYY-MM-DD]

---

## 📝 CHANGE LOG

### [X.Y.Z] - YYYY-MM-DD
- [Change description]

### [X.Y.Z] - YYYY-MM-DD
- [Change description]

---

**END OF ARCHITECTURE ENTRY**

**REF-ID:** ARCH-[XX]  
**Template Version:** 1.0.0  
**Entry Type:** Architecture Pattern  
**Status:** [Active/Proposed/Deprecated]  
**Maintenance:** Review quarterly or after major changes

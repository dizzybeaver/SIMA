# [LANG-CODE]: [LANGUAGE_NAME] Constraints

**REF-ID:** LANG-[XX]  
**Version:** 1.0.0  
**Category:** Language Constraints  
**Status:** [Active/Updated/Deprecated]  
**Created:** [YYYY-MM-DD]  
**Last Updated:** [YYYY-MM-DD]

---

## 📋 OVERVIEW

**Language:** [Full language name]  
**Short Code:** [LANG-CODE]  
**Version Coverage:** [X.Y+]  
**Paradigm:** [Procedural/OOP/Functional/Multi-paradigm]

**One-Line Description:**  
[Single sentence about what makes this language unique or its primary use case]

**Primary Use Cases:**
- [Use case 1]
- [Use case 2]
- [Use case 3]
- [Use case 4]

---

## 🎯 SCOPE

### What This Entry Covers
✅ This entry documents:
- Language-specific constraints that affect architecture
- Runtime limitations and behaviors
- Idiomatic patterns and anti-patterns
- Standard library considerations
- Common pitfalls and gotchas

### What This Entry Does NOT Cover
❌ This entry does NOT document:
- Basic language syntax (see official docs)
- General programming concepts
- Framework-specific details (see framework entries)
- Domain-specific libraries

---

## ⚠️ CRITICAL CONSTRAINTS

### Constraint 1: [CONSTRAINT_NAME]

**REF-ID:** LANG-[XX]-CONS-01  
**Severity:** 🔴 Critical / 🟡 Important / 🟢 Advisory

**Statement:**
[Clear, concise statement of the constraint]

**Context:**
[When this constraint applies]

**Impact:**
- **Performance:** [Impact description]
- **Architecture:** [How it affects design]
- **Scalability:** [Scaling implications]

**Rationale:**
[Why this constraint exists - language design, runtime behavior, etc.]

**Example:**
```[language]
# BAD - Violates constraint
[code showing violation]

# GOOD - Follows constraint
[code showing correct approach]
```

**Related:**
- **Architectures Affected:** [ARCH-CODES]
- **Common Errors:** [ERROR-CODES if any]
- **See Also:** [Related REF-IDs]

---

### Constraint 2: [CONSTRAINT_NAME]

**REF-ID:** LANG-[XX]-CONS-02  
**Severity:** 🔴 Critical / 🟡 Important / 🟢 Advisory

**Statement:**
[Clear, concise statement of the constraint]

**Context:**
[When this constraint applies]

**Impact:**
- **Memory:** [Impact description]
- **Concurrency:** [Threading/async implications]
- **Resource Management:** [Resource handling]

**Rationale:**
[Why this constraint exists]

**Example:**
```[language]
# BAD - Violates constraint
[code showing violation]

# GOOD - Follows constraint
[code showing correct approach]
```

**Related:**
- **Architectures Affected:** [ARCH-CODES]
- **Common Errors:** [ERROR-CODES if any]
- **See Also:** [Related REF-IDs]

---

## 🏗️ ARCHITECTURAL IMPLICATIONS

### Threading & Concurrency

**Model:** [Threading model description]

**Key Constraints:**
1. **[CONSTRAINT_NAME]**
   - **Limitation:** [What you can't do]
   - **Workaround:** [How to work with it]
   - **Affects:** [Which architectures]

2. **[CONSTRAINT_NAME]**
   - **Limitation:** [What you can't do]
   - **Workaround:** [How to work with it]
   - **Affects:** [Which architectures]

**Best Practices:**
- [Practice 1]
- [Practice 2]
- [Practice 3]

### Memory Management

**Model:** [GC/Manual/Hybrid/Reference Counting]

**Key Constraints:**
1. **[CONSTRAINT_NAME]**
   - **Behavior:** [How memory is managed]
   - **Implication:** [What this means for architecture]
   - **Monitoring:** [How to track]

2. **[CONSTRAINT_NAME]**
   - **Behavior:** [How memory is managed]
   - **Implication:** [What this means for architecture]
   - **Monitoring:** [How to track]

**Optimization Strategies:**
- [Strategy 1]
- [Strategy 2]
- [Strategy 3]

### Error Handling

**Model:** [Exceptions/Error Values/Result Types/Panic]

**Key Constraints:**
1. **[CONSTRAINT_NAME]**
   - **Behavior:** [How errors are handled]
   - **Required Pattern:** [What you must do]
   - **Anti-Pattern:** [What to avoid]

2. **[CONSTRAINT_NAME]**
   - **Behavior:** [How errors are handled]
   - **Required Pattern:** [What you must do]
   - **Anti-Pattern:** [What to avoid]

**Best Practices:**
```[language]
# Recommended error handling pattern
[code example]
```

---

## 🔧 RUNTIME CONSTRAINTS

### Performance Characteristics

**Startup Time:**
- **Cold Start:** [Typical time]
- **Warm Start:** [Typical time]
- **Optimization Options:** [Available options]

**Execution Speed:**
- **Relative Performance:** [Fast/Moderate/Slow compared to compiled languages]
- **Bottlenecks:** [Common performance bottlenecks]
- **Optimization Techniques:** [How to optimize]

### Resource Limits

**Memory:**
- **Default Heap:** [Size if applicable]
- **Max Heap:** [Limit if applicable]
- **Stack Size:** [Size if applicable]

**File Descriptors:**
- **Default Limit:** [Number]
- **Max Limit:** [Number]
- **Implications:** [What this means]

**Network:**
- **Connection Pooling:** [Default behavior]
- **Timeout Defaults:** [Default values]
- **Configuration:** [How to adjust]

---

## 📦 DEPENDENCY MANAGEMENT

### Package Management

**Tool:** [pip/npm/cargo/etc.]  
**Lock Files:** [requirements.txt/package-lock.json/etc.]

**Key Constraints:**
1. **[CONSTRAINT_NAME]**
   - **Issue:** [What the problem is]
   - **Solution:** [How to handle it]
   - **Related:** [REF-IDs]

2. **[CONSTRAINT_NAME]**
   - **Issue:** [What the problem is]
   - **Solution:** [How to handle it]
   - **Related:** [REF-IDs]

### Version Management

**Versioning Scheme:** [SemVer/CalVer/Other]  
**Breaking Changes:** [How they're handled]

**Best Practices:**
- [Practice 1]
- [Practice 2]
- [Practice 3]

### Common Dependency Issues

**Issue 1: [ISSUE_NAME]**
- **Problem:** [Description]
- **Detection:** [How to detect]
- **Resolution:** [How to fix]

**Issue 2: [ISSUE_NAME]**
- **Problem:** [Description]
- **Detection:** [How to detect]
- **Resolution:** [How to fix]

---

## 🎨 IDIOMATIC PATTERNS

### Pattern 1: [PATTERN_NAME]

**Purpose:** [What this pattern achieves]

**Implementation:**
```[language]
# Idiomatic approach
[code example]
```

**Why Idiomatic:**
[Explanation of why this is the language's preferred way]

**Benefits:**
- [Benefit 1]
- [Benefit 2]

**When to Use:**
- [Condition 1]
- [Condition 2]

---

### Pattern 2: [PATTERN_NAME]

**Purpose:** [What this pattern achieves]

**Implementation:**
```[language]
# Idiomatic approach
[code example]
```

**Why Idiomatic:**
[Explanation of why this is the language's preferred way]

**Benefits:**
- [Benefit 1]
- [Benefit 2]

**When to Use:**
- [Condition 1]
- [Condition 2]

---

## 🚫 ANTI-PATTERNS

### Anti-Pattern 1: [ANTI_PATTERN_NAME]

**REF-ID:** LANG-[XX]-ANTI-01  
**Severity:** 🔴 Critical / 🟡 Important / 🟢 Advisory

**Problem:**
[What developers often do wrong]

**Example:**
```[language]
# WRONG - Anti-pattern
[code showing anti-pattern]
```

**Why It's Bad:**
- [Reason 1]
- [Reason 2]
- [Consequence]

**Correct Approach:**
```[language]
# CORRECT - Idiomatic pattern
[code showing correct way]
```

**Detection:**
[How to find this in code - linter rules, code review, etc.]

---

### Anti-Pattern 2: [ANTI_PATTERN_NAME]

**REF-ID:** LANG-[XX]-ANTI-02  
**Severity:** 🔴 Critical / 🟡 Important / 🟢 Advisory

**Problem:**
[What developers often do wrong]

**Example:**
```[language]
# WRONG - Anti-pattern
[code showing anti-pattern]
```

**Why It's Bad:**
- [Reason 1]
- [Reason 2]
- [Consequence]

**Correct Approach:**
```[language]
# CORRECT - Idiomatic pattern
[code showing correct way]
```

**Detection:**
[How to find this in code]

---

## 🔄 VERSION-SPECIFIC CONSTRAINTS

### Version [X.Y]

**Release Date:** [YYYY-MM-DD]  
**EOL Date:** [YYYY-MM-DD or "Active"]

**New Constraints:**
- [New constraint added in this version]
- [Breaking change]

**Deprecated Features:**
- [Feature deprecated]
- [Alternative to use]

**Performance Changes:**
- [Performance improvement or regression]

### Version [X.Y]

**Release Date:** [YYYY-MM-DD]  
**EOL Date:** [YYYY-MM-DD or "Active"]

**New Constraints:**
- [New constraint added in this version]
- [Breaking change]

**Deprecated Features:**
- [Feature deprecated]
- [Alternative to use]

---

## 🧪 TESTING CONSIDERATIONS

### Unit Testing

**Recommended Tools:**
- [Tool 1]: [Purpose]
- [Tool 2]: [Purpose]

**Key Constraints:**
- [Constraint affecting testing]
- [Constraint affecting testing]

**Best Practices:**
```[language]
# Recommended test structure
[code example]
```

### Integration Testing

**Recommended Tools:**
- [Tool 1]: [Purpose]
- [Tool 2]: [Purpose]

**Key Constraints:**
- [Constraint affecting integration tests]
- [Constraint affecting integration tests]

**Common Pitfalls:**
- [Pitfall 1]
- [Pitfall 2]

### Performance Testing

**Recommended Tools:**
- [Tool 1]: [Purpose]
- [Tool 2]: [Purpose]

**Key Metrics:**
- [Metric 1]: [Target]
- [Metric 2]: [Target]

---

## 🛠️ TOOLING CONSTRAINTS

### Development Environment

**IDEs/Editors:**
- [Tool 1]: [Capabilities/Constraints]
- [Tool 2]: [Capabilities/Constraints]

**Debuggers:**
- [Tool]: [Capabilities/Limitations]

**Profilers:**
- [Tool]: [What it measures]

### Build Tools

**Primary Build Tool:** [Tool name]

**Constraints:**
- [Build constraint 1]
- [Build constraint 2]

**Configuration:**
```[language-specific-config]
# Recommended build configuration
[config example]
```

### Linting & Formatting

**Recommended Linters:**
- [Tool]: [Configuration]

**Recommended Formatters:**
- [Tool]: [Configuration]

**Standard Style Guide:**
[Link to official or community style guide]

---

## 🌐 PLATFORM-SPECIFIC CONSTRAINTS

### Linux
- [Constraint or behavior specific to Linux]
- [Constraint or behavior specific to Linux]

### macOS
- [Constraint or behavior specific to macOS]
- [Constraint or behavior specific to macOS]

### Windows
- [Constraint or behavior specific to Windows]
- [Constraint or behavior specific to Windows]

### Containers
- [Constraint when running in containers]
- [Constraint when running in containers]

### Serverless
- [Constraint in serverless environments]
- [Constraint in serverless environments]

---

## 📊 MONITORING & OBSERVABILITY

### Metrics Collection

**Built-in Tools:**
- [Tool]: [What it provides]

**Third-Party Tools:**
- [Tool]: [Integration approach]

**Key Metrics:**
- [Metric]: [How to collect]
- [Metric]: [How to collect]

### Logging

**Standard Library:**
```[language]
# Recommended logging setup
[code example]
```

**Structured Logging:**
- [Approach for structured logs]
- [Recommended libraries]

**Best Practices:**
- [Practice 1]
- [Practice 2]

---

## 🔗 INTEROPERABILITY

### Foreign Function Interface (FFI)

**Capability:** [Yes/Limited/No]

**Constraints:**
- [Constraint 1]
- [Constraint 2]

**Example:**
```[language]
# FFI example if applicable
[code example]
```

### Calling Other Languages

**Can Call:**
- [Language]: [Mechanism]
- [Language]: [Mechanism]

**Called By:**
- [Language]: [Mechanism]
- [Language]: [Mechanism]

**Constraints:**
- [Constraint about interop]
- [Constraint about interop]

---

## 📚 REFERENCES

### Official Documentation
- **Language Spec:** [URL]
- **Standard Library:** [URL]
- **Style Guide:** [URL]

### Community Resources
- **Best Practices:** [URL]
- **Common Pitfalls:** [URL]
- **Performance Tips:** [URL]

### Related SIMA Entries
- **Architectures:** [ARCH-CODES that use this language]
- **Combinations:** [COMB-CODES involving this language]
- **Lessons:** [LESS-CODES learned from this language]

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

**END OF LANGUAGE ENTRY**

**REF-ID:** LANG-[XX]  
**Template Version:** 1.0.0  
**Entry Type:** Language Constraints  
**Status:** [Active/Updated/Deprecated]  
**Maintenance:** Review with each major language version release

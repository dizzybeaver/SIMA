# File: interface_catalog_template.md

**Template Version:** 1.0.0  
**Purpose:** Interface catalog NMP template  
**Location:** `/sima/projects/templates/`

---

# File: NMP##-PROJECT-##-[Interface]-Interface-Functions.md

**REF-ID:** NMP##-PROJECT-##  
**Category:** Project Neural Map - Interface Catalog  
**Project:** [Project Name] ([PROJECT_CODE])  
**Status:** Active  
**Created:** YYYY-MM-DD  
**Last Updated:** YYYY-MM-DD

---

## ðŸ"‹ SUMMARY

Catalog of [Interface Name] interface functions used in [Project Name]. Inherits from generic interface pattern and adds project-specific implementations.

**Interface:** [Interface Name] ([interface_module.py])  
**Generic Pattern:** INT-## ([Interface Name] Interface)  
**Functions Cataloged:** [X] functions  
**Usage:** [Brief usage context]

---

## ðŸŽ¯ PURPOSE

[Why this interface is used in the project - 2-3 sentences]

**Key Benefits:**
- Benefit 1
- Benefit 2
- Benefit 3

---

## 🔗 INHERITANCE

**Inherits From:**
- **REF-ID:** INT-##
- **Entry:** [Interface Name] Interface
- **Inheritance Type:** Full adoption + project extensions
- **Note:** Follows generic pattern, adds project-specific functions

---

## ðŸ"¦ FUNCTION CATALOG

### Core Functions (from INT-##)

#### Function: `function_name()`
```python
def function_name(param1: Type, param2: Type) -> ReturnType:
    """Brief description."""
    pass
```

**Purpose:** [What it does]  
**Parameters:**
- `param1` (Type): Description
- `param2` (Type): Description

**Returns:** Description of return value  
**Raises:** Exception types and conditions  
**Usage Context:** [When to use]

**Example:**
```python
result = function_name(value1, value2)
```

---

### Project-Specific Extensions

#### Function: `project_specific_function()`
```python
def project_specific_function(param: Type) -> ReturnType:
    """Project-specific implementation."""
    pass
```

**Purpose:** [What it does - project-specific]  
**Parameters:**
- `param` (Type): Description

**Returns:** Description  
**Project Context:** [Why project needs this]  
**Added:** YYYY-MM-DD

**Example:**
```python
result = project_specific_function(value)
```

---

## ðŸ—ºï¸ FUNCTION INDEX

### By Category

**[Category 1]:**
- `function_1()` - Brief description
- `function_2()` - Brief description

**[Category 2]:**
- `function_3()` - Brief description
- `function_4()` - Brief description

**Project Extensions:**
- `project_function_1()` - Brief description
- `project_function_2()` - Brief description

---

## ðŸ"Š USAGE PATTERNS

### Pattern 1: [Pattern Name]
```python
# Common usage pattern
from interfaces import [interface]

result = [interface].function_name(args)
```

**When to Use:** [Context]  
**Best Practices:**
- Practice 1
- Practice 2

---

### Pattern 2: [Pattern Name]
```python
# Another common pattern
from interfaces import [interface]

with [interface].context_manager() as resource:
    # Use resource
    pass
```

**When to Use:** [Context]  
**Best Practices:**
- Practice 1
- Practice 2

---

## âš ï¸ CAUTIONS

### Anti-Patterns to Avoid
1. **[Anti-Pattern Name]**
   - âŒ Wrong: [Bad example]
   - âœ… Right: [Good example]

2. **[Anti-Pattern Name]**
   - âŒ Wrong: [Bad example]
   - âœ… Right: [Good example]

### Common Mistakes
- Mistake 1: [Description and how to avoid]
- Mistake 2: [Description and how to avoid]

---

## ðŸ"— INTEGRATION POINTS

### Gateway Usage
**Gateways Using This Interface:**
- `gateway_1.py` - [Usage context]
- `gateway_2.py` - [Usage context]

**Related NMPs:**
- NMP##-PROJECT-15 - Gateway Execute Operation
- NMP##-PROJECT-16 - Gateway Fast Path

---

### Dependencies
**This Interface Depends On:**
- Dependency 1 (version) - Purpose
- Dependency 2 (version) - Purpose

**Other Interfaces Used With:**
- Interface 1 - [Relationship]
- Interface 2 - [Relationship]

---

## 🎨 CUSTOMIZATION

### Project-Specific Configuration
```python
# Configuration example
CONFIG = {
    'setting_1': 'value',
    'setting_2': 'value'
}
```

### Environment Variables
```bash
INTERFACE_VAR_1=value    # Description
INTERFACE_VAR_2=value    # Description
```

---

## ðŸ§ª TESTING

### Test Coverage
- **Core Functions:** [X]% coverage
- **Project Extensions:** [Y]% coverage
- **Integration Tests:** [Z] tests

### Key Test Files
- `test_[interface].py` - Unit tests
- `test_[interface]_integration.py` - Integration tests

---

## ðŸ"Š METRICS

### Usage Statistics
- **Total Calls:** [Metric]
- **Most Used Function:** function_name ([X]% of calls)
- **Average Response Time:** [X]ms

### Performance
- **P50:** [X]ms
- **P95:** [X]ms
- **P99:** [X]ms

---

## ðŸ"š RELATED DOCUMENTATION

**SIMA Generic Entries:**
- INT-## - [Interface Name] Interface (generic pattern)
- GATE-01 - Three-File Structure (how interfaces fit)
- GATE-03 - Cross-Interface Communication

**Project NMPs:**
- NMP##-PROJECT-Quick-Index.md - All project NMPs
- NMP##-PROJECT-Cross-Reference-Matrix.md - Relationships

**External Resources:**
- [Library documentation URL]
- [API reference URL]

---

## ðŸ·ï¸ KEYWORDS

`[interface-name]`, `[project-code]`, `interface-catalog`, `function-reference`, `[domain-keyword]`, `[technology-keyword]`

---

## ðŸ" VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0.0 | YYYY-MM-DD | [Name] | Initial catalog with [X] functions |
| 1.1.0 | YYYY-MM-DD | [Name] | Added project extension functions |

---

**END OF INTERFACE CATALOG**

**Maintenance Notes:**
- Update when new functions added
- Review quarterly for deprecated functions
- Keep examples current with actual usage
- Maintain synchronization with generic INT-## entry

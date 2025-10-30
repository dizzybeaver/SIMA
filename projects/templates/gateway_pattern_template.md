# File: gateway_pattern_template.md

**Template Version:** 1.0.0  
**Purpose:** Gateway pattern NMP template  
**Location:** `/sima/projects/templates/`

---

# File: NMP##-PROJECT-##-Gateway-[Pattern-Name].md

**REF-ID:** NMP##-PROJECT-##  
**Category:** Project Neural Map - Gateway Pattern  
**Project:** [Project Name] ([PROJECT_CODE])  
**Status:** Active  
**Created:** YYYY-MM-DD  
**Last Updated:** YYYY-MM-DD

---

## ðŸ"‹ SUMMARY

[Pattern Name] gateway pattern implementation for [Project Name]. [1-2 sentence description of what this pattern does.]

**Pattern Type:** Gateway Implementation  
**Related Generic:** GATE-## ([Generic Pattern Name])  
**Applies To:** [Which gateways use this]  
**Impact:** [High/Medium/Low] - [Brief impact description]

---

## ðŸŽ¯ PURPOSE

[Detailed explanation of why this pattern exists - 2-3 sentences]

**Solves:**
- Problem 1
- Problem 2
- Problem 3

**Benefits:**
- Benefit 1
- Benefit 2
- Benefit 3

---

## 🔗 INHERITANCE

**Inherits From:**
- **REF-ID:** GATE-##
- **Entry:** [Generic Gateway Pattern Name]
- **Inheritance Type:** [Full/Partial/Extended]
- **Note:** [How this relates to generic pattern]

---

## ðŸ"¦ PATTERN STRUCTURE

### File Organization
```
gateway_name/
â"œâ"€â"€ gateway.py              # External interface
â"œâ"€â"€ interface.py            # Interface layer
â""â"€â"€ core.py                 # Core logic
```

### Pattern Implementation

**Gateway Layer (gateway.py):**
```python
# Pattern: [Pattern Name]
def external_function(params):
    """External entry point."""
    # Pattern-specific logic here
    return _interface.internal_function(params)
```

**Interface Layer (interface.py):**
```python
def internal_function(params):
    """Interface coordination."""
    # Pattern-specific coordination
    return core.core_function(params)
```

**Core Layer (core.py):**
```python
def core_function(params):
    """Core implementation."""
    # Pattern-specific core logic
    return result
```

---

## ðŸ"Š COMPLETE EXAMPLE

### Scenario: [Use Case Name]

**Requirements:**
- Requirement 1
- Requirement 2
- Requirement 3

### Implementation

#### Gateway Layer
```python
# File: gateway_name/gateway.py
from . import _interface

def execute_operation(operation: str, params: dict) -> dict:
    """
    Execute [pattern-specific operation].
    
    Args:
        operation: Operation type
        params: Operation parameters
        
    Returns:
        Operation result
        
    Example:
        result = execute_operation('action', {'key': 'value'})
    """
    # Pattern-specific gateway logic
    return _interface.handle_operation(operation, params)
```

#### Interface Layer
```python
# File: gateway_name/interface.py
from . import core
from interfaces import logging, metrics

def handle_operation(operation: str, params: dict) -> dict:
    """Coordinate operation handling."""
    # Pattern-specific coordination
    logging.info(f"Handling {operation}")
    
    result = core.perform_operation(operation, params)
    
    metrics.record('operation_complete', operation)
    return result
```

#### Core Layer
```python
# File: gateway_name/core.py

def perform_operation(operation: str, params: dict) -> dict:
    """Core operation logic."""
    # Pattern-specific core implementation
    result = {
        'status': 'success',
        'data': process_params(params)
    }
    return result

def process_params(params: dict) -> dict:
    """Helper function."""
    # Implementation
    return processed
```

---

## ðŸ—ºï¸ PATTERN VARIATIONS

### Variation 1: [Variation Name]
**When to Use:** [Context]

```python
# Implementation example
def variation_implementation():
    # Code
    pass
```

**Differences from Standard:**
- Difference 1
- Difference 2

---

### Variation 2: [Variation Name]
**When to Use:** [Context]

```python
# Implementation example
def another_variation():
    # Code
    pass
```

**Differences from Standard:**
- Difference 1
- Difference 2

---

## ðŸ"§ PATTERN CONFIGURATION

### Required Configuration
```python
# Configuration for this pattern
PATTERN_CONFIG = {
    'setting_1': 'value',
    'setting_2': 'value',
    'setting_3': 'value'
}
```

### Environment Variables
```bash
PATTERN_VAR_1=value      # Description
PATTERN_VAR_2=value      # Description
```

---

## ðŸ"„ DATA FLOW

### Flow Diagram
```
Input â†' Gateway Layer â†' Interface Layer â†' Core Layer â†' Output
         [Pattern Logic]   [Coordination]   [Implementation]
```

### Step-by-Step Flow

1. **Gateway Receives Input**
   - Validates input
   - Applies pattern-specific logic
   - Passes to interface

2. **Interface Coordinates**
   - Coordinates resources
   - Manages state
   - Calls core logic

3. **Core Processes**
   - Performs actual work
   - Returns results

4. **Gateway Returns Output**
   - Formats response
   - Returns to caller

---

## âš ï¸ CAUTIONS & ANTI-PATTERNS

### What NOT to Do

**Anti-Pattern 1: [Name]**
- âŒ Wrong Approach:
  ```python
  # Bad example
  def bad_implementation():
      # Wrong way
      pass
  ```
- âœ… Correct Approach:
  ```python
  # Good example
  def good_implementation():
      # Right way
      pass
  ```
- **Why:** [Explanation]

**Anti-Pattern 2: [Name]**
- âŒ Wrong: [Description]
- âœ… Right: [Description]
- **Why:** [Explanation]

---

## ðŸ"— INTEGRATION POINTS

### Gateways Using This Pattern
- `gateway_1.py` - [How it uses pattern]
- `gateway_2.py` - [How it uses pattern]
- `gateway_3.py` - [How it uses pattern]

### Interface Dependencies
**Required Interfaces:**
- Interface 1 - Purpose
- Interface 2 - Purpose

**Optional Interfaces:**
- Interface 3 - Purpose (enhances performance)

---

### Related Patterns
**Works Well With:**
- NMP##-PROJECT-## - [Related Pattern] - [Relationship]
- NMP##-PROJECT-## - [Related Pattern] - [Relationship]

**Conflicts With:**
- [Pattern Name] - [Why/when conflicts occur]

---

## ðŸ§ª TESTING

### Test Coverage
- **Pattern Functions:** [X]% coverage
- **Edge Cases:** [Y] test cases
- **Integration:** [Z] tests

### Key Test Scenarios
1. **Scenario 1:** [Description]
   - Test: [What's tested]
   - Expected: [Expected result]

2. **Scenario 2:** [Description]
   - Test: [What's tested]
   - Expected: [Expected result]

### Test Files
- `test_pattern_[name].py` - Unit tests
- `test_pattern_[name]_integration.py` - Integration tests

---

## ðŸ"Š PERFORMANCE

### Metrics
- **Execution Time:** [Typical: X ms, P95: Y ms]
- **Memory Usage:** [Typical: X MB]
- **Throughput:** [X operations/second]

### Optimization Notes
- Optimization 1: [Description and impact]
- Optimization 2: [Description and impact]

### Bottlenecks
- Potential bottleneck 1: [Mitigation strategy]
- Potential bottleneck 2: [Mitigation strategy]

---

## ðŸ"š RELATED DOCUMENTATION

**SIMA Generic Entries:**
- GATE-## - [Generic Pattern] (base pattern)
- GATE-01 - Three-File Structure
- GATE-03 - Cross-Interface Communication

**Project NMPs:**
- NMP##-PROJECT-Quick-Index.md
- NMP##-PROJECT-Cross-Reference-Matrix.md
- NMP##-PROJECT-## - [Related NMP]

---

## ðŸ·ï¸ KEYWORDS

`[pattern-name]`, `gateway`, `[project-code]`, `implementation-pattern`, `[domain-keyword]`

---

## ðŸ" VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0.0 | YYYY-MM-DD | [Name] | Initial pattern documentation |
| 1.1.0 | YYYY-MM-DD | [Name] | Added variation 2 |

---

**END OF GATEWAY PATTERN**

**Maintenance Notes:**
- Review when gateway structure changes
- Update examples to match current implementations
- Keep performance metrics current
- Document new variations as they emerge

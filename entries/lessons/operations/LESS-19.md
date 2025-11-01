# LESS-19.md

# LESS-19: Input Validation at Boundaries Prevents Injection

**Category:** Lessons  
**Topic:** Operations  
**Priority:** CRITICAL  
**Status:** Active  
**Created:** 2025-10-30  
**Path:** `/sima/entries/lessons/operations/LESS-19.md`

---

## Summary

Input validation at system boundaries prevents injection attacks and malformed data issues. All user inputs must be validated before use - names, values, and parameters all need validation.

---

## Pattern

### The Problem

**Attack Vectors:**

1. **Path Traversal**
```python
# âŒ No validation
metric_name = event.get('metric_name')
# Attacker: "../../etc/passwd" → Security breach
```

2. **Control Character Injection**
```python
# âŒ No validation
value = event.get('value')
# Attacker: "\x00\x01\x02" → Log corruption
```

3. **Numeric Value Injection**
```python
# âŒ No validation
value = float(event.get('value'))
# Attacker: float('nan') → Breaks calculations
```

---

## Solution

### Validation at Boundaries

**Input Validation Pattern:**
```python
def execute_operation(operation: str, **kwargs) -> Any:
    # Validate ALL inputs at boundary
    if operation == 'record':
        metric_name = kwargs.get('metric_name')
        value = kwargs.get('value')
        dimensions = kwargs.get('dimensions', {})
        
        # Validate metric name
        if not validate_metric_name(metric_name):
            log_error(f"Invalid metric name: {metric_name}")
            return False
        
        # Validate numeric value
        if not validate_numeric_value(value):
            log_error(f"Invalid value: {value}")
            return False
        
        # Validate dimensions
        for key, val in dimensions.items():
            if not validate_dimension_value(val):
                log_error(f"Invalid dimension: {key}={val}")
                return False
        
        # Safe to proceed
        return _execute_implementation(**kwargs)
```

### Validation Rules

**1. Name/Identifier Validation:**
- Alphanumeric, hyphens, underscores, periods only
- Length: 1-255 characters
- No path separators (/, \)
- No control characters

**2. Text Value Validation:**
- Printable ASCII only
- Length: 1-1024 characters
- No control characters (\x00-\x1F)
- No path separators

**3. Numeric Value Validation:**
- Must be finite (no NaN, no Infinity)
- Must be in reasonable range
- Type-checked (int or float)

### Implementation

**Name/Identifier Validator:**
```python
import re

def validate_identifier(name: str) -> bool:
    """Validate identifier format"""
    if not name or len(name) > 255:
        return False
    if not re.match(r'^[a-zA-Z0-9._-]+$', name):
        return False
    if '/' in name or '\\' in name:
        return False
    return True
```

**Numeric Validator:**
```python
import math

def validate_numeric_value(value: Any) -> bool:
    """Validate numeric value"""
    if not isinstance(value, (int, float)):
        return False
    if math.isnan(value) or math.isinf(value):
        return False
    return True
```

**Text Validator:**
```python
def validate_text_value(value: str) -> bool:
    """Validate text value"""
    if not value or len(value) > 1024:
        return False
    # Check for control characters
    if any(ord(c) < 32 for c in value):
        return False
    return True
```

### Where to Validate

**At Boundaries Only:**
- âœ… Interface/router layer (validates external inputs)
- âŒ NOT in core layer (trusts validated inputs)
- âŒ NOT in internal functions (already validated)

**Validation Layer:**
```
User Input → [VALIDATE HERE] → System Processing
External → Interface Layer → Core Layer
           â†'
         Validation
         happens here
```

### Testing Validations

**Test Attack Vectors:**
```python
def test_identifier_injection():
    # Path traversal attempt
    assert not validate_identifier("../../etc/passwd")
    
    # Control characters
    assert not validate_identifier("name\x00attack")
    
    # Valid identifier
    assert validate_identifier("valid.name-123")

def test_numeric_injection():
    # NaN attempt
    assert not validate_numeric_value(float('nan'))
    
    # Infinity attempt
    assert not validate_numeric_value(float('inf'))
    
    # Valid number
    assert validate_numeric_value(42.5)

def test_text_injection():
    # Control character attempt
    assert not validate_text_value("text\x00attack")
    
    # Too long
    assert not validate_text_value("x" * 2000)
    
    # Valid text
    assert validate_text_value("valid text value")
```

---

## Impact

### Vulnerabilities Addressed

**Blocked Attacks:**
- Path traversal attempts
- Control character injection
- NaN/Infinity injection
- Memory exhaustion (via length limits)
- DoS attempts (via validation + rate limiting)

**Security Posture:**
```
Before: Multiple critical vulnerabilities
After: 0 known injection vulnerabilities
```

### Real-World Protection

**Example Incident Prevented:**
```
Attack attempt: metric_name="../../config/secrets"
Validation: Rejects (contains path separator)
Result: Attack blocked at boundary
```

**Example Data Corruption Prevented:**
```
Malformed input: value=float('nan')
Validation: Rejects (not finite)
Result: Calculations remain valid
```

---

## Best Practices

### Validation Strategy

**1. Validate Early**
```python
# âœ… Validate at entry point
def api_handler(event):
    if not validate_input(event):
        return error_response("Invalid input")
    return process(event)  # Safe, validated input
```

**2. Fail Securely**
```python
# âœ… Reject invalid input
if not validate(input):
    log_security_event(f"Invalid input: {input}")
    return False  # Don't process
```

**3. Be Specific**
```python
# âœ… Specific validation
if not re.match(r'^[a-zA-Z0-9._-]+$', name):
    return False

# âŒ Too permissive
if len(name) > 0:
    return True  # Allows anything!
```

**4. Log Violations**
```python
# âœ… Track attempted attacks
if not validate(input):
    log_security_event({
        'event': 'validation_failure',
        'input': sanitize_for_log(input),
        'rule_violated': 'identifier_format'
    })
```

### Defense in Depth

**Layer 1: Input Validation** (this lesson)
- Validate at boundaries
- Reject malformed input
- Log violations

**Layer 2: Rate Limiting** (LESS-21)
- Limit attack velocity
- Prevent DoS
- Throttle abusers

**Layer 3: Size Limits** (LESS-20)
- Prevent memory exhaustion
- Cap resource usage
- Enforce boundaries

---

## Common Validation Rules

### Standard Patterns

**Alphanumeric with Delimiters:**
```python
r'^[a-zA-Z0-9._-]+$'
# Letters, numbers, period, underscore, hyphen
```

**Email Address:**
```python
r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
```

**ISO Date:**
```python
r'^\d{4}-\d{2}-\d{2}$'
# YYYY-MM-DD
```

**Positive Integer:**
```python
isinstance(value, int) and value > 0
```

**Finite Float:**
```python
isinstance(value, float) and math.isfinite(value)
```

---

## Anti-Patterns to Avoid

**âŒ Validating After Use**
```python
# âŒ Wrong
result = process(input)
if not validate(input):  # Too late!
    return error
```

**âŒ Trusting Client Validation**
```python
# âŒ Wrong - client can be bypassed
# Client says input is valid, so skip server check
```

**âŒ Insufficient Validation**
```python
# âŒ Too permissive
if len(input) > 0:
    return True  # Allows everything!
```

**âŒ Overly Complex Regex**
```python
# âŒ Hard to maintain
r'^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$'
# Consider splitting into multiple checks
```

---

## Related Topics

- **LESS-20**: Memory Limits (complementary protection)
- **LESS-21**: Rate Limiting (complementary protection)
- **Security Patterns**: Input validation strategies
- **Defense in Depth**: Layered security approach

---

## Keywords

input-validation, injection-prevention, boundary-validation, security, attack-prevention, malformed-data

---

## Version History

- **2025-10-30**: Genericized for SIMAv4 - Removed project-specific details
- **2025-10-21**: Created - Documented input validation requirements

---

**File:** `LESS-19.md`  
**Location:** `/sima/entries/lessons/operations/`  
**Status:** Active

---

**End of Document**

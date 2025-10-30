# NM00-GUIDELINES: Documentation Standards
**Version:** 1.0.0  
**Date:** 2025-10-21  
**Purpose:** Standardize code documentation across SUGA-ISP Lambda project

---

## Overview

Every file, function, and design decision must be documented to specific standards. This ensures:
- New developers onboard quickly
- Design rationale preserved
- AI assistants provide accurate help
- Maintenance remains efficient

---

## Documentation Levels

### Level 1: Minimum (Every Function)

**Requirement:** Every function MUST have at least one docstring line.

```python
def simple_function():
    """Does X and returns Y."""
    pass
```

**Red Flags:**
```python
# ❌ WRONG - No docstring
def simple_function():
    pass

# ❌ WRONG - Comment instead of docstring  
def simple_function():
    # Does X
    pass
```

---

### Level 2: High-Value Functions

**When Required:**
- Functions called externally (gateway wrappers, public APIs)
- Complex logic (>20 lines, multiple branches)
- Performance-critical code
- Functions with non-obvious behavior

**Format:**
```python
def high_value_function(param1: str, param2: int = 10) -> Dict[str, Any]:
    """
    Brief one-line description of what it does.
    
    More detailed explanation if needed (2-3 sentences max).
    Explain WHY this exists, not just WHAT it does.
    
    Args:
        param1: Description of first parameter
        param2: Description of second parameter (default: 10)
        
    Returns:
        Description of return value structure
        
    Raises:
        ValueError: When param1 is empty
        RuntimeError: When operation fails
        
    Example:
        >>> result = high_value_function('test', 20)
        >>> print(result['status'])
        'success'
    """
    pass
```

**When to Include Example:**
- Non-obvious usage patterns
- Complex parameter structures
- Easy to misuse incorrectly

---

### Level 3: Major Design Decisions (File Header)

**When Required:**
- Architectural choices affecting multiple files
- Performance trade-offs
- Deviation from standard patterns
- Singleton patterns
- Threading decisions

**Format:**
```python
"""
filename.py - Brief description
Version: YYYY.MM.DD.##
Description: Detailed description of file purpose

MAJOR DESIGN DECISION - Decision Title:
======================================
Brief explanation of what was decided.

WHY: Core reasoning
- Reason 1 with explanation
- Reason 2 with explanation
- Reason 3 with explanation

TRADE-OFFS:
- Pro: Benefit 1
- Pro: Benefit 2
- Con: Cost 1
- Con: Cost 2
- DECISION: Why pros outweigh cons

REFERENCES:
- DEC-XX: Related design decision
- LESS-YY: Lesson learned from this
- BUG-ZZ: Bug this prevents
- See: External documentation link

CHANGELOG:
- YYYY.MM.DD.##: Most recent change (full detail - 2-3 lines)
- YYYY.MM.DD.##: Previous change (1-line summary)
- YYYY.MM.DD.##: Earlier change (1-line summary)
- YYYY.MM.DD.##: Earlier change (1-line summary)
- YYYY.MM.DD.##: Oldest kept (1-line summary)

Copyright YYYY Joseph Hersey
   [Full Apache 2.0 license block]
"""
```

---

### Level 4: Minor Design Decisions (In-Function)

**When Required:**
- Non-obvious implementation choices
- Performance optimizations
- Workarounds for limitations
- Data structure selections

**Format:**
```python
def process_data():
    # DESIGN: Using list instead of set because order matters (DEC-XX)
    items = []
    
    # PERFORMANCE: Pre-allocate to avoid resizing (saves ~5ms per 1000 items)
    items = [None] * expected_size
    
    # WORKAROUND: Can't use dict.get() because of None ambiguity (LESS-YY)
    if key in cache_dict:
        value = cache_dict[key]
```

---

## Changelog Rules

### LIFO (Last In, First Out) with Expansion

**Maximum Entries:** 5
**Newest Entry:** Full detail (2-3 lines explaining what/why)
**Older Entries:** 1-line summaries
**Replacement:** When adding 6th entry, drop oldest

**Format:**
```python
CHANGELOG:
- 2025.10.21.01: Added DEBUG_MODE support + singleton performance docs
  - All functions now have debug paths at entry/exit points
  - Header documents why module-level singleton chosen for performance
- 2025.10.18.01: Fixed ErrorLogLevel enum usage (HIGH not ERROR)
- 2025.10.17.04: Removed threading locks for Lambda optimization
- 2025.10.17.03: Fixed inconsistent error log limits to 100
- 2025.10.14.01: Added design decision documentation
```

**Consolidation Rule:**
If 5+ changes on same day, consolidate into grouped entry:
```python
- 2025.10.21.01-05: Multiple bug fixes (threading, limits, enum, docs, debug)
```

---

## Version Format

**Standard:** `YYYY.MM.DD.##`

- `YYYY`: 4-digit year (2025)
- `MM`: 2-digit month (01-12)
- `DD`: 2-digit day (01-31)
- `##`: 2-digit sequence (01, 02, 03...)

**Examples:**
```
2025.10.21.01  - First change on Oct 21, 2025
2025.10.21.02  - Second change same day
2025.10.21.15  - 15th change same day
```

**Sequence Reset:**
- Resets to .01 each new day
- Never skip numbers
- Always increment sequentially

---

## Documentation Templates

### Template 1: Simple Function
```python
def simple_helper(value: str) -> str:
    """Converts value to uppercase and strips whitespace."""
    return value.strip().upper()
```

### Template 2: High-Value Function
```python
def process_request(request: Dict[str, Any]) -> Dict[str, Any]:
    """
    Process incoming request with validation and error handling.
    
    Validates request structure, processes data, and returns formatted response.
    Used by gateway router for all incoming Lambda invocations.
    
    Args:
        request: Dict containing 'action', 'params', and optional 'correlation_id'
        
    Returns:
        Dict with 'status', 'result', and 'correlation_id'
        
    Raises:
        ValueError: If request missing required fields
        RuntimeError: If processing fails
        
    Example:
        >>> response = process_request({
        ...     'action': 'cache_get',
        ...     'params': {'key': 'user:123'}
        ... })
        >>> print(response['status'])
        'success'
    """
    pass
```

### Template 3: File Header with Major Decision
```python
"""
module_core.py - Core module implementation
Version: 2025.10.21.01
Description: Implements core functionality for Module interface

MAJOR DESIGN DECISION - Choice Title:
====================================
What was decided and why it matters.

WHY: Performance/Architecture/Correctness reason
- Lambda single-threaded environment (no locks needed)
- Hot-path optimization (called 100+ times per invocation)
- Memory constraints (128MB limit)

TRADE-OFFS:
- Pro: 50% faster execution (10µs → 5µs per call)
- Pro: Simpler code (no lock management)
- Con: Cannot use from multi-threaded contexts (N/A for Lambda)
- DECISION: Performance gain worth the constraint

REFERENCES:
- DEC-04: Lambda threading model
- LESS-06: Hot-path optimization patterns
- See: Performance Analysis (2025-10-20)

CHANGELOG:
- 2025.10.21.01: Major refactoring for performance
- 2025.10.20.03: Added error handling
- 2025.10.20.02: Fixed validation bug
- 2025.10.20.01: Initial implementation
- 2025.10.19.05: Prototype version

Copyright 2025 Joseph Hersey
   [License block]
"""
```

---

## When to Update Documentation

### Always Update:
- Adding new functions
- Changing function signatures
- Modifying major design decisions
- Fixing bugs (add to changelog)
- Performance optimizations
- API changes

### Don't Over-Document:
- Obvious variable names (`user_id = request['user_id']`)
- Standard patterns everyone knows
- Temporary debugging code
- Internal helper functions (<5 lines)

---

## Documentation Anti-Patterns

### AP-DOC-01: Missing Minimum Docstring
```python
# ❌ WRONG
def process(): 
    pass

# ✅ CORRECT
def process():
    """Process the data and return result."""
    pass
```

### AP-DOC-02: Comment Instead of Docstring
```python
# ❌ WRONG
def process():
    # Process the data
    pass

# ✅ CORRECT  
def process():
    """Process the data."""
    pass
```

### AP-DOC-03: Useless Docstring
```python
# ❌ WRONG (tells us nothing)
def get_user(user_id):
    """Gets the user."""
    pass

# ✅ CORRECT (explains what/how)
def get_user(user_id):
    """Retrieve user data from cache or database."""
    pass
```

### AP-DOC-04: Outdated Changelog
```python
# ❌ WRONG (50 entries, impossible to find recent changes)
CHANGELOG:
- 2025.10.21.01: Changed X
- 2025.10.20.15: Changed Y
... (48 more entries)

# ✅ CORRECT (5 entries max, LIFO)
CHANGELOG:
- 2025.10.21.01: Changed X (full detail)
- 2025.10.20.15: Changed Y (1-line)
- 2025.10.20.10: Changed Z (1-line)
- 2025.10.19.05: Changed W (1-line)
- 2025.10.19.01: Initial version (1-line)
```

### AP-DOC-05: Missing Design Rationale
```python
# ❌ WRONG (no explanation why)
"""
cache_core.py - Cache implementation
Uses module-level singleton.
"""

# ✅ CORRECT (explains why)
"""
cache_core.py - Cache implementation

MAJOR DESIGN DECISION - Module-Level Singleton:
Module-level chosen over SINGLETON interface for hot-path performance.
Called 100+ times per invocation, gateway routing adds 1-5µs overhead.
See: DEC-XX for full analysis.
"""
```

---

## Verification Checklist

Before committing any file:

- [ ] Every function has minimum 1-line docstring
- [ ] High-value functions have Args/Returns/Example
- [ ] Major design decisions documented in header
- [ ] Minor design decisions have in-function comments
- [ ] Changelog updated (max 5 entries, LIFO)
- [ ] Version incremented properly (YYYY.MM.DD.##)
- [ ] Copyright/license block present
- [ ] No documentation anti-patterns present

---

## Examples from Project

### Good Example: logging_manager.py
```python
"""
MAJOR DESIGN DECISION - Module-Level Singleton:
LoggingCore uses module-level singleton instead of SINGLETON interface.

WHY: Performance-critical hot-path optimization
- Logging called on EVERY operation (10-100+ times per Lambda invocation)
- SINGLETON interface adds gateway routing overhead (~1-5µs per call)
...
"""

class LoggingCore:
    """Unified logging manager with template optimization."""
    
    def log(self, message: str, level: int = logging.INFO, **kwargs) -> None:
        """Core logging with optional metadata."""
        self.logger.log(level, message, extra=kwargs)
```

**Why This Is Good:**
- ✅ Major decision explained in header
- ✅ Class has clear purpose
- ✅ Function has minimum docstring
- ✅ Performance rationale documented

---

## Related Neural Maps

- **LESS-01:** Read complete files before modifying
- **LESS-15:** 5-step verification protocol
- **LESS-XX:** Header verification (check against original)
- **AP-25:** Undocumented decisions anti-pattern
- **Communication Efficiency Guidelines:** Keep docs concise

---

**END OF DOCUMENTATION STANDARDS**

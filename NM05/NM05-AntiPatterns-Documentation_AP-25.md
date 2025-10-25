# NM05-AntiPatterns-Documentation_AP-25.md - AP-25

# AP-25: No Docstrings

**Category:** NM05 - Anti-Patterns
**Topic:** Documentation
**Priority:** 🟢 MEDIUM
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Omitting docstrings from functions, classes, and modules forces developers to read implementation code to understand purpose, parameters, return values, and side effects.

---

## Context

Docstrings are Python's built-in documentation system. They're accessible via `help()`, show in IDE tooltips, and can be extracted to generate API documentation.

**Problem:** Unclear API contracts, slower onboarding, frequent "what does this do?" questions.

---

## Content

### The Anti-Pattern

```python
# ❌ NO DOCSTRINGS
def process_data(data, options):
    if not data:
        return []
    
    result = []
    for item in data:
        if options.get('filter'):
            if item > options['threshold']:
                result.append(item * options.get('multiplier', 1))
        else:
            result.append(item)
    
    return result

class DataProcessor:
    def __init__(self, config):
        self.config = config
    
    def process(self, items):
        return [self._transform(item) for item in items]
    
    def _transform(self, item):
        return item * 2
```

**Questions developers have:**
- What does process_data do?
- What format should `data` be?
- What options are supported?
- What does it return?
- What are the side effects?
- Can `data` be None?

### Why This Is Wrong

**1. Forces Reading Implementation**
```python
# Without docstring, must read code
def calculate(x, y, z):
    return x * y + z ** 2 - abs(x - y)

# What does this calculate?
# Must read and understand algorithm
# Wastes time, error-prone
```

**2. No IDE Assistance**
```python
# Without docstring:
result = process_data(  # IDE shows: (data, options)
# No hints about what data/options should be!

# With docstring:
result = process_data(  # IDE shows full docstring!
# """Process data with filtering options.
#  Args:
#    data (list): Input data items
#    options (dict): Processing options...
```

**3. No `help()` Documentation**
```python
# Without docstring:
>>> help(process_data)
Help on function process_data in module __main__:
process_data(data, options)
# That's all! Not helpful!

# With docstring:
>>> help(process_data)
Help on function process_data in module __main__:
process_data(data, options)
    Process data with optional filtering.
    
    Args:
        data (list): Input data items
        options (dict): Processing options
    
    Returns:
        list: Processed data items
# Much better!
```

### Correct Approach

**Function Docstrings:**
```python
# ✅ CORRECT - Complete docstring
def process_data(data, options):
    """Process data with optional filtering and transformation.
    
    Filters data items based on threshold if filtering is enabled,
    then applies multiplier to filtered items.
    
    Args:
        data (list): Input data items (numbers)
        options (dict): Processing options with keys:
            - filter (bool): Whether to filter items
            - threshold (float): Minimum value for filtering
            - multiplier (float, optional): Multiplication factor. Default: 1
    
    Returns:
        list: Processed data items
    
    Raises:
        ValueError: If data is not a list
        KeyError: If required options are missing when filter=True
    
    Example:
        >>> process_data([1, 2, 3], {'filter': False})
        [1, 2, 3]
        >>> process_data([1, 2, 3], {'filter': True, 'threshold': 1.5, 'multiplier': 2})
        [4, 6]
    """
    if not isinstance(data, list):
        raise ValueError("data must be a list")
    
    if not data:
        return []
    
    result = []
    for item in data:
        if options.get('filter'):
            if 'threshold' not in options:
                raise KeyError("threshold required when filter=True")
            
            if item > options['threshold']:
                result.append(item * options.get('multiplier', 1))
        else:
            result.append(item)
    
    return result
```

**Class Docstrings:**
```python
# ✅ CORRECT - Class and method docstrings
class DataProcessor:
    """Process and transform data items.
    
    DataProcessor applies configurable transformations to data items
    including filtering, multiplication, and aggregation.
    
    Attributes:
        config (dict): Processing configuration
        processed_count (int): Number of items processed
    
    Example:
        >>> processor = DataProcessor({'multiplier': 2})
        >>> processor.process([1, 2, 3])
        [2, 4, 6]
    """
    
    def __init__(self, config):
        """Initialize processor with configuration.
        
        Args:
            config (dict): Processing configuration with keys:
                - multiplier (float): Multiplication factor
                - filter_enabled (bool, optional): Enable filtering
        
        Raises:
            ValueError: If config is missing required keys
        """
        if 'multiplier' not in config:
            raise ValueError("multiplier required in config")
        
        self.config = config
        self.processed_count = 0
    
    def process(self, items):
        """Process list of items.
        
        Args:
            items (list): Items to process
        
        Returns:
            list: Transformed items
        """
        result = [self._transform(item) for item in items]
        self.processed_count += len(items)
        return result
    
    def _transform(self, item):
        """Transform single item (internal method).
        
        Args:
            item: Item to transform
        
        Returns:
            Transformed item value
        """
        return item * self.config['multiplier']
```

**Module Docstrings:**
```python
# ✅ CORRECT - Module docstring at top of file
"""Cache operations module.

This module provides caching functionality with TTL support,
automatic expiration, and memory management.

Functions:
    get: Retrieve value from cache
    set: Store value in cache with TTL
    delete: Remove value from cache
    clear: Clear all cached values

Example:
    >>> import cache_core
    >>> cache_core.set('key', 'value', ttl=3600)
    >>> cache_core.get('key')
    'value'
"""

import time
from typing import Any, Optional

# Rest of module code...
```

### Docstring Formats

**Google Style (Recommended for SUGA-ISP):**
```python
def function(arg1, arg2):
    """Summary line.
    
    Longer description if needed.
    
    Args:
        arg1 (int): First argument description
        arg2 (str): Second argument description
    
    Returns:
        bool: Return value description
    
    Raises:
        ValueError: When this happens
    """
```

**NumPy Style:**
```python
def function(arg1, arg2):
    """
    Summary line.
    
    Longer description if needed.
    
    Parameters
    ----------
    arg1 : int
        First argument description
    arg2 : str
        Second argument description
    
    Returns
    -------
    bool
        Return value description
    
    Raises
    ------
    ValueError
        When this happens
    """
```

**reStructuredText Style:**
```python
def function(arg1, arg2):
    """Summary line.
    
    Longer description if needed.
    
    :param arg1: First argument description
    :type arg1: int
    :param arg2: Second argument description
    :type arg2: str
    :return: Return value description
    :rtype: bool
    :raises ValueError: When this happens
    """
```

### What to Document

**Always document:**
- [ ] Purpose/what it does
- [ ] Parameters (name, type, description)
- [ ] Return value (type, description)
- [ ] Exceptions raised
- [ ] Side effects (if any)
- [ ] Examples (for complex functions)

**Don't need to document:**
- Private helper functions (optional)
- Obvious getters/setters (unless there's side effects)
- Test functions (test name should be descriptive)

### Real SUGA-ISP Examples

**Wrong (early code):**
```python
# cache_core.py
def get(key):
    if key in _cache:
        entry = _cache[key]
        if entry['expires'] > time.time():
            return entry['value']
        del _cache[key]
    return None
```

**Correct (current code):**
```python
# cache_core.py
def get(key):
    """Retrieve value from cache.
    
    Returns cached value if present and not expired. Automatically
    removes expired entries.
    
    Args:
        key (str): Cache key to retrieve
    
    Returns:
        Any: Cached value if present and valid, None otherwise
    
    Example:
        >>> cache_core.set('user:123', {'name': 'Alice'}, ttl=3600)
        >>> cache_core.get('user:123')
        {'name': 'Alice'}
    """
    if key in _cache:
        entry = _cache[key]
        if entry['expires'] > time.time():
            return entry['value']
        del _cache[key]
    return None
```

### Tools for Docstrings

**Generate documentation:**
```bash
# Sphinx - Generate HTML docs from docstrings
pip install sphinx
sphinx-quickstart
sphinx-build -b html source build

# pdoc - Simpler documentation generator
pip install pdoc3
pdoc --html --output-dir docs src/
```

**Validate docstrings:**
```bash
# pydocstyle - Check docstring conventions
pip install pydocstyle
pydocstyle src/

# darglint - Check docstring matches implementation
pip install darglint
darglint src/*.py
```

---

## Related Topics

- **AP-26**: Outdated Comments - Documentation must stay current
- **AP-22**: Inconsistent Naming - Clear naming helps but isn't enough
- **LESS-11**: Documentation Lessons - Real documentation issues
- **LESS-12**: Living Documentation - Keep docs updated

---

## Keywords

docstrings, documentation, API documentation, function documentation, help(), Sphinx, PEP 257

---

## Version History

- **2025-10-24**: Created - Extracted from Part 2, added docstring formats and SUGA-ISP examples

---

**File:** `NM05-AntiPatterns-Documentation_AP-25.md`
**End of Document**

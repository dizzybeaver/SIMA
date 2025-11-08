# DD1-01-Core-Concept.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Dictionary Dispatch pattern core concept and fundamentals  
**Category:** Python Architecture Pattern - Performance

---

## WHAT IS DICTIONARY DISPATCH?

**Dictionary Dispatch (DD-1)** is a performance optimization pattern that uses Python dictionaries for O(1) function routing instead of O(n) if-else chains.

**Type:** Performance Pattern  
**Domain:** Function Routing  
**Trade-off:** Memory (dispatch table) for Speed (O(1) lookup)

---

## CORE CONCEPT

### Traditional If-Else Routing (O(n))

```python
def handle_action(action: str, data: dict):
    """Route action using if-else chain."""
    if action == "turn_on":
        return turn_on_impl(data)
    elif action == "turn_off":
        return turn_off_impl(data)
    elif action == "set_brightness":
        return set_brightness_impl(data)
    elif action == "set_color":
        return set_color_impl(data)
    # ... 20+ more elif blocks
    else:
        raise ValueError(f"Unknown action: {action}")
```

**Performance:** O(n) - Average n/2 comparisons  
**Problem:** Slow with many actions (20+ branches)

### Dictionary Dispatch (O(1))

```python
# Dispatch table at module level
DISPATCH_TABLE = {
    "turn_on": turn_on_impl,
    "turn_off": turn_off_impl,
    "set_brightness": set_brightness_impl,
    "set_color": set_color_impl,
    # ... all actions
}

def handle_action(action: str, data: dict):
    """Route action using dictionary lookup."""
    handler = DISPATCH_TABLE.get(action)
    if handler is None:
        raise ValueError(f"Unknown action: {action}")
    return handler(data)
```

**Performance:** O(1) - Constant time lookup  
**Benefit:** Fast regardless of action count

---

## WHEN TO USE

### Good Fit (Use DD-1)

**✅ 10+ routing targets**
- Many actions/commands to route
- Performance matters
- Action set grows over time

**✅ Static routing rules**
- Actions known at import time
- No dynamic routing logic
- Clear action-to-handler mapping

**✅ Performance critical path**
- Hot path execution
- High request volume
- Measurable latency impact

### Poor Fit (Don't Use DD-1)

**❌ 2-5 routing targets**
- If-else simpler and clearer
- No measurable performance gain
- Overhead not justified

**❌ Dynamic routing logic**
- Routing depends on runtime state
- Complex conditional routing
- Need if-else logic anyway

**❌ One-time execution**
- Not in performance path
- Called rarely
- Clarity > speed

---

## PERFORMANCE CHARACTERISTICS

### Complexity Analysis

**If-Else Chain:**
- Best case: O(1) - First condition
- Average case: O(n/2) - Middle condition
- Worst case: O(n) - Last condition or no match

**Dictionary Dispatch:**
- All cases: O(1) - Hash table lookup
- Constant regardless of entry count

### Real-World Impact

**LEE Project Measurements:**
```
Interface routing (20 actions):
- If-else chain: ~15µs average (worst: 28µs)
- Dict dispatch: ~2µs all cases
- Improvement: 7.5x faster average, 14x faster worst
```

**Threshold:** Benefit visible at 8-10 actions, significant at 15+

---

## BASIC PATTERN

### Step 1: Define Handlers

```python
def turn_on_impl(data: dict):
    """Turn device on."""
    return {"status": "on"}

def turn_off_impl(data: dict):
    """Turn device off."""
    return {"status": "off"}

def set_brightness_impl(data: dict):
    """Set device brightness."""
    brightness = data.get("brightness", 100)
    return {"brightness": brightness}

# ... more handlers
```

### Step 2: Create Dispatch Table

```python
# Module-level constant
DISPATCH_TABLE = {
    "turn_on": turn_on_impl,
    "turn_off": turn_off_impl,
    "set_brightness": set_brightness_impl,
    # ... all actions
}
```

### Step 3: Implement Router

```python
def execute_action(action: str, data: dict):
    """
    Execute action via dispatch table.
    
    Args:
        action: Action name to execute
        data: Action parameters
        
    Returns:
        Action result
        
    Raises:
        ValueError: Unknown action
    """
    handler = DISPATCH_TABLE.get(action)
    if handler is None:
        raise ValueError(f"Unknown action: {action}")
    
    return handler(data)
```

---

## ADVANCED PATTERNS

### Pattern 1: Nested Dispatch

```python
# Primary dispatch by category
CATEGORY_DISPATCH = {
    "device": device_dispatch,
    "scene": scene_dispatch,
    "automation": automation_dispatch,
}

# Secondary dispatch within category
DEVICE_DISPATCH = {
    "turn_on": turn_on_impl,
    "turn_off": turn_off_impl,
    # ...
}

def execute(category: str, action: str, data: dict):
    """Two-level dispatch."""
    category_handler = CATEGORY_DISPATCH.get(category)
    if category_handler is None:
        raise ValueError(f"Unknown category: {category}")
    
    return category_handler(action, data)
```

### Pattern 2: Parameterized Handlers

```python
from functools import partial

def set_value_impl(field: str, data: dict):
    """Generic setter."""
    value = data.get(field)
    return {field: value}

# Create specialized handlers
DISPATCH_TABLE = {
    "set_brightness": partial(set_value_impl, "brightness"),
    "set_color": partial(set_value_impl, "color"),
    "set_temperature": partial(set_value_impl, "temperature"),
}
```

### Pattern 3: Default Handler

```python
def default_handler(action: str, data: dict):
    """Handle unknown actions."""
    return {"error": f"Unknown action: {action}"}

def execute_action(action: str, data: dict):
    """Execute with fallback."""
    handler = DISPATCH_TABLE.get(action, default_handler)
    return handler(data) if handler != default_handler else handler(action, data)
```

---

## TRADE-OFFS

### Memory Cost

**Dictionary Overhead:**
- Each entry: ~64 bytes (key + value + overhead)
- 20 entries: ~1.3 KB
- 100 entries: ~6.4 KB

**When It Matters:**
- Memory-constrained environments
- Large dispatch tables (1000+ entries)
- Multiple concurrent instances

**When It Doesn't:**
- Typical application (20-100 entries)
- Modern hardware (GB RAM)
- Single instance

### Import Time Cost

**All handlers loaded at import:**
```python
# Handlers imported when module loads
from handlers import (
    turn_on_impl,
    turn_off_impl,
    # ... 20+ imports
)

DISPATCH_TABLE = {...}  # All functions in memory
```

**Solutions:**
- Lazy loading (load on first use)
- Split large tables into modules
- Use functools.lru_cache for hot functions

---

## BEST PRACTICES

### DO: Clear Action Names

```python
# ✅ Good - Clear, descriptive
DISPATCH_TABLE = {
    "turn_on": turn_on_impl,
    "turn_off": turn_off_impl,
    "set_brightness": set_brightness_impl,
}

# ❌ Bad - Cryptic abbreviations
DISPATCH_TABLE = {
    "on": h1,
    "off": h2,
    "brght": h3,
}
```

### DO: Type Hints

```python
# ✅ Good - Typed
HandlerFunc = Callable[[dict], dict]

DISPATCH_TABLE: Dict[str, HandlerFunc] = {
    "turn_on": turn_on_impl,
    # ...
}

# ❌ Bad - Untyped
DISPATCH_TABLE = {
    "turn_on": turn_on_impl,
    # ...
}
```

### DO: Validation

```python
# ✅ Good - Validate at startup
def validate_dispatch_table():
    """Verify all handlers are callable."""
    for action, handler in DISPATCH_TABLE.items():
        if not callable(handler):
            raise TypeError(f"Handler for {action} not callable")

validate_dispatch_table()  # Run at import

# ❌ Bad - No validation
DISPATCH_TABLE = {...}  # Hope handlers exist
```

### DON'T: Mix Patterns

```python
# ❌ Bad - Mixing dispatch with if-else
def execute_action(action: str, data: dict):
    if action in ["turn_on", "turn_off"]:
        # Special handling
        return special_handler(action, data)
    else:
        # Normal dispatch
        return DISPATCH_TABLE[action](data)

# ✅ Good - Consistent dispatch
DISPATCH_TABLE = {
    "turn_on": special_turn_on_impl,  # Special case in handler
    "turn_off": special_turn_off_impl,
    "set_brightness": set_brightness_impl,
}
```

---

## RELATED PATTERNS

**SUGA Gateway Pattern:**
- DD-1 used in interface routing
- Gateway -> Interface -> Core layers
- Each interface uses dispatch table

**ZAPH Hot Path Optimization:**
- Fast path for frequent actions
- Dispatch table in Tier 1 cache
- O(1) lookup critical for hot path

**CR-1 Cache Registry:**
- Central registry uses dispatch pattern
- Interface mapping to routers
- Single lookup for all interfaces

---

## KEYWORDS

dictionary dispatch, function routing, performance pattern, O(1) lookup, dispatch table, hash table, if-else optimization, action routing, command pattern, performance optimization

---

## RELATED TOPICS

- DD1-02: Function Routing Strategies
- DD1-03: Performance Trade-offs Analysis
- DD1-DEC-01: When Dict Over If-Else
- DD1-LESS-01: LEE Interface Implementation
- SUGA INT-XX: Interface patterns using dispatch
- ZAPH: Hot path optimization with dispatch

---

**END OF FILE**

**Version:** 1.0.0  
**Lines:** 377 (within 400 limit)  
**Category:** Python Architecture - Performance Pattern  
**Status:** Complete

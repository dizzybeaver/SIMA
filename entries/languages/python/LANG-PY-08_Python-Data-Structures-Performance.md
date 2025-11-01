# LANG-PY-08: Python Data Structures and Performance

**REF-ID:** LANG-PY-08  
**Category:** Language Patterns  
**Subcategory:** Data Structures & Performance  
**Language:** Python  
**Priority:** 🟡 HIGH  
**Status:** Active  
**Created:** 2025-11-01  
**Last Updated:** 2025-11-01

---

## 📋 SUMMARY

Best practices for using Python data structures efficiently: lists, dicts, sets, tuples, and choosing the right structure for the task.

---

## 🎯 CHOOSING DATA STRUCTURES

### Rule 1: Use the Right Structure

**List** - Ordered, mutable sequence
```python
items = [1, 2, 3, 4, 5]
items.append(6)
items[0] = 10
```

**Tuple** - Ordered, immutable sequence
```python
coordinates = (10, 20)
rgb = (255, 0, 0)
```

**Set** - Unordered, unique items
```python
unique_ids = {1, 2, 3, 4, 5}
unique_ids.add(6)
```

**Dict** - Key-value mapping
```python
user = {'name': 'John', 'age': 30}
user['email'] = 'john@example.com'
```

---

### Rule 2: Performance Characteristics

**List Operations:**
```python
# O(1) - Fast
items[0]           # Access by index
items.append(x)    # Append to end
items.pop()        # Remove from end

# O(n) - Slow
items.insert(0, x) # Insert at beginning
items.pop(0)       # Remove from beginning
x in items         # Check membership
```

**Dict Operations:**
```python
# O(1) - Fast
user['name']       # Get value
user['age'] = 31   # Set value
'name' in user     # Check key exists
user.pop('name')   # Remove key
```

**Set Operations:**
```python
# O(1) - Fast
item in my_set     # Check membership
my_set.add(item)   # Add item
my_set.remove(item)# Remove item

# Set operations are efficient
a | b              # Union
a & b              # Intersection
a - b              # Difference
```

---

## 🔧 BEST PRACTICES

### Practice 1: Use Sets for Membership Testing

**❌ WRONG (Slow for large lists):**
```python
valid_users = [1, 2, 3, 4, 5, ...]  # List
if user_id in valid_users:  # O(n) lookup
    grant_access()
```

**✅ CORRECT (Fast with sets):**
```python
valid_users = {1, 2, 3, 4, 5, ...}  # Set
if user_id in valid_users:  # O(1) lookup
    grant_access()
```

---

### Practice 2: Use dict.get() Instead of Key Check

**❌ WRONG:**
```python
if key in cache:
    value = cache[key]
else:
    value = default
```

**✅ CORRECT:**
```python
value = cache.get(key, default)
```

---

### Practice 3: Use defaultdict for Grouping

**❌ WRONG:**
```python
groups = {}
for item in items:
    category = item['category']
    if category not in groups:
        groups[category] = []
    groups[category].append(item)
```

**✅ CORRECT:**
```python
from collections import defaultdict

groups = defaultdict(list)
for item in items:
    groups[item['category']].append(item)
```

---

### Practice 4: Use Counter for Counting

**❌ WRONG:**
```python
counts = {}
for item in items:
    if item in counts:
        counts[item] += 1
    else:
        counts[item] = 1
```

**✅ CORRECT:**
```python
from collections import Counter

counts = Counter(items)
# Get most common
most_common = counts.most_common(5)
```

---

### Practice 5: Use deque for Queues

**❌ WRONG (Slow):**
```python
queue = []
queue.append(item)    # O(1)
item = queue.pop(0)   # O(n) - Slow!
```

**✅ CORRECT (Fast):**
```python
from collections import deque

queue = deque()
queue.append(item)     # O(1)
item = queue.popleft() # O(1) - Fast!
```

---

### Practice 6: Use namedtuple for Simple Classes

**❌ VERBOSE:**
```python
class Point:
    def __init__(self, x, y):
        self.x = x
        self.y = y
```

**✅ CORRECT (Simpler):**
```python
from collections import namedtuple

Point = namedtuple('Point', ['x', 'y'])
p = Point(10, 20)
print(p.x, p.y)
```

---

### Practice 7: Use dataclass for Data Classes

**✅ CORRECT:**
```python
from dataclasses import dataclass

@dataclass
class User:
    id: int
    name: str
    email: str
    age: int = 0

user = User(id=1, name='John', email='john@example.com')
```

**Benefits:**
- Automatic __init__
- Automatic __repr__
- Automatic __eq__
- Type hints built-in

---

## 🚀 PERFORMANCE PATTERNS

### Pattern 1: List Comprehensions

**✅ CORRECT (Faster):**
```python
squares = [x**2 for x in range(10)]
evens = [x for x in numbers if x % 2 == 0]
```

**❌ SLOWER:**
```python
squares = []
for x in range(10):
    squares.append(x**2)
```

**Why:** List comprehensions are optimized in C and run faster.

---

### Pattern 2: Generator Expressions

**✅ CORRECT (Memory Efficient):**
```python
# Don't load all into memory
sum_squares = sum(x**2 for x in huge_range)

# Process one at a time
for item in (process(x) for x in items):
    handle(item)
```

**❌ MEMORY INTENSIVE:**
```python
# Loads everything into memory
sum_squares = sum([x**2 for x in huge_range])
```

---

### Pattern 3: Use `in` with Dicts and Sets

**✅ CORRECT (Fast):**
```python
# Check dict keys
if key in user_dict:  # O(1)
    value = user_dict[key]

# Check set membership
if user_id in active_users:  # O(1)
    process_user(user_id)
```

---

### Pattern 4: String Concatenation

**❌ WRONG (Slow for many strings):**
```python
result = ""
for s in strings:
    result += s  # Creates new string each time
```

**✅ CORRECT (Fast):**
```python
result = ''.join(strings)
```

---

### Pattern 5: Use `any()` and `all()`

**✅ CORRECT (Efficient):**
```python
# Check if any user is admin
has_admin = any(user.is_admin for user in users)

# Check if all valid
all_valid = all(validate(item) for item in items)
```

**❌ SLOWER:**
```python
has_admin = False
for user in users:
    if user.is_admin:
        has_admin = True
        break
```

---

### Pattern 6: Dict Merge (Python 3.9+)

**✅ CORRECT:**
```python
# Python 3.9+
merged = default_config | user_config

# Python 3.5+
merged = {**default_config, **user_config}
```

---

### Pattern 7: Use `enumerate()` and `zip()`

**✅ CORRECT:**
```python
# enumerate - get index and value
for i, value in enumerate(items):
    print(f"Item {i}: {value}")

# zip - iterate multiple sequences
for name, age in zip(names, ages):
    print(f"{name} is {age} years old")
```

---

## ⚠️ ANTI-PATTERNS

### AP-1: Using Lists for Membership Testing

**❌ WRONG:**
```python
if item in large_list:  # O(n) - Slow!
    process(item)
```

**✅ CORRECT:**
```python
large_set = set(large_list)
if item in large_set:  # O(1) - Fast!
    process(item)
```

---

### AP-2: Repeatedly Indexing Lists

**❌ WRONG:**
```python
for i in range(len(items)):
    process(items[i])
```

**✅ CORRECT:**
```python
for item in items:
    process(item)
```

---

### AP-3: Using + for String Building

**❌ WRONG:**
```python
result = ""
for s in strings:
    result = result + s + ","  # Inefficient
```

**✅ CORRECT:**
```python
result = ','.join(strings)
```

---

### AP-4: Not Using Dict Comprehensions

**❌ VERBOSE:**
```python
result = {}
for item in items:
    result[item.id] = item.name
```

**✅ CORRECT:**
```python
result = {item.id: item.name for item in items}
```

---

## 📚 SPECIALIZED STRUCTURES

### OrderedDict (Python 3.7+ dicts are ordered)

```python
from collections import OrderedDict

# Before Python 3.7, needed for ordered dicts
ordered = OrderedDict([('a', 1), ('b', 2)])

# Python 3.7+ regular dicts maintain insertion order
regular_dict = {'a': 1, 'b': 2}  # Maintains order
```

---

### ChainMap

```python
from collections import ChainMap

# Combine multiple dicts
defaults = {'color': 'blue', 'size': 10}
overrides = {'size': 20}

config = ChainMap(overrides, defaults)
print(config['color'])  # 'blue' from defaults
print(config['size'])   # 20 from overrides
```

---

### lru_cache for Memoization

```python
from functools import lru_cache

@lru_cache(maxsize=128)
def fibonacci(n):
    """Cached fibonacci calculation."""
    if n < 2:
        return n
    return fibonacci(n-1) + fibonacci(n-2)

# First call calculates, subsequent calls use cache
fib_10 = fibonacci(10)  # Calculated
fib_10 = fibonacci(10)  # From cache
```

---

## 📊 EXAMPLES

### Example 1: Efficient Data Grouping

```python
from collections import defaultdict
from typing import List, Dict

def group_by_category(items: List[dict]) -> Dict[str, List[dict]]:
    """Group items by category efficiently."""
    groups = defaultdict(list)
    for item in items:
        groups[item['category']].append(item)
    return dict(groups)

# Usage
items = [
    {'name': 'Apple', 'category': 'Fruit'},
    {'name': 'Carrot', 'category': 'Vegetable'},
    {'name': 'Banana', 'category': 'Fruit'},
]
grouped = group_by_category(items)
```

---

### Example 2: Efficient Deduplication

```python
def deduplicate_preserving_order(items: List[str]) -> List[str]:
    """Remove duplicates while preserving order."""
    seen = set()
    result = []
    for item in items:
        if item not in seen:
            seen.add(item)
            result.append(item)
    return result

# Or using dict (Python 3.7+):
def deduplicate(items: List[str]) -> List[str]:
    return list(dict.fromkeys(items))
```

---

### Example 3: Efficient Caching

```python
from functools import lru_cache
from typing import Optional

class DataService:
    @lru_cache(maxsize=1000)
    def get_user(self, user_id: int) -> Optional[dict]:
        """Get user with automatic caching."""
        # Expensive database call
        return database.query('users', user_id)
    
    def clear_cache(self):
        """Clear the cache when needed."""
        self.get_user.cache_clear()
```

---

### Example 4: Memory-Efficient Processing

```python
def process_large_file(filename: str):
    """Process large file without loading all into memory."""
    with open(filename) as f:
        # Generator - processes one line at a time
        for line in f:
            if line.strip():  # Skip empty lines
                yield process_line(line)

# Usage:
for result in process_large_file('huge.txt'):
    handle_result(result)
```

---

## ✅ VERIFICATION CHECKLIST

Before committing:

- [ ] Use sets for membership testing
- [ ] Use dicts for key-value lookups
- [ ] Use deque for queues (not list)
- [ ] Use list comprehensions when readable
- [ ] Use generator expressions for large data
- [ ] Use `join()` for string concatenation
- [ ] Use `defaultdict` for grouping
- [ ] Use `Counter` for counting
- [ ] Use `any()`/`all()` for checks
- [ ] Use `enumerate()`/`zip()` for iteration
- [ ] Consider memory vs speed tradeoffs

---

## 🔗 CROSS-REFERENCES

### Related Patterns

- **LANG-PY-04**: Function design with data structures
- **LANG-PY-07**: Code quality with data structures

### Performance

- **LESS-25**: Performance optimization lessons
- **LESS-26**: Memory efficiency
- **ARCH-04**: ZAPH pattern for hot paths

### Anti-Patterns

- **AP-12**: Performance anti-patterns

---

## 🤝 CONTRIBUTORS

**Original Author:** SIMAv4 Phase 5.1  
**Source Material:** SUGA-ISP Python standards  
**Extracted From:** LANG-PY-03-through-08 consolidated file  
**Last Reviewed:** 2025-11-01

---

**END OF LANG-PY-08**

**Lines:** ~385  
**REF-ID:** LANG-PY-08  
**Status:** Active  
**Complete:** All 6 language files created
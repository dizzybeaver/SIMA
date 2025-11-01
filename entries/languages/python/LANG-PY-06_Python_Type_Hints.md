# LANG-PY-06: Python Type Hints and Annotations

**REF-ID:** LANG-PY-06  
**Category:** Language Patterns  
**Subcategory:** Type System  
**Language:** Python  
**Priority:** 🟡 HIGH  
**Status:** Active  
**Created:** 2025-11-01  
**Last Updated:** 2025-11-01

---

## 📋 SUMMARY

Comprehensive guide to Python type hints: when to use them, how to use them effectively, and best practices for type annotations.

---

## 🎯 CORE RULES

### Rule 1: Use Type Hints on Public APIs

**✅ CORRECT:**
```python
from typing import List, Dict, Optional

def get_user(user_id: int) -> Optional[Dict[str, Any]]:
    """Get user by ID."""
    return database.get('users', user_id)

def calculate_total(items: List[float], tax: float = 0.08) -> float:
    """Calculate total with tax."""
    return sum(items) * (1 + tax)
```

**Use type hints for:**
- Public functions and methods
- Function parameters
- Return values
- Class attributes

**Optional for:**
- Private functions
- Obvious local variables
- Temporary variables

---

### Rule 2: Use Optional for Nullable Values

**✅ CORRECT:**
```python
from typing import Optional

def find_user(email: str) -> Optional[User]:
    """Find user by email.
    
    Returns:
        User if found, None otherwise
    """
    return user_db.get(email)

# Python 3.10+ alternative:
def find_user(email: str) -> User | None:
    return user_db.get(email)
```

**Not:**
```python
def find_user(email: str) -> User:  # ❌ Can return None!
    return user_db.get(email)
```

---

### Rule 3: Use Union for Multiple Types

**✅ CORRECT:**
```python
from typing import Union

def process_id(user_id: Union[int, str]) -> User:
    """Process user ID as int or string."""
    if isinstance(user_id, str):
        user_id = int(user_id)
    return get_user(user_id)

# Python 3.10+ alternative:
def process_id(user_id: int | str) -> User:
    if isinstance(user_id, str):
        user_id = int(user_id)
    return get_user(user_id)
```

---

### Rule 4: Use Generic Types for Collections

**✅ CORRECT:**
```python
from typing import List, Dict, Set, Tuple

def get_user_names(users: List[User]) -> List[str]:
    """Extract names from users."""
    return [user.name for user in users]

def get_user_map(users: List[User]) -> Dict[int, User]:
    """Create user ID to user mapping."""
    return {user.id: user for user in users}

def get_unique_ids(users: List[User]) -> Set[int]:
    """Get unique user IDs."""
    return {user.id for user in users}

def get_user_tuple(user: User) -> Tuple[int, str, str]:
    """Get user as tuple (id, name, email)."""
    return (user.id, user.name, user.email)
```

---

### Rule 5: Use TypedDict for Dictionaries

**✅ CORRECT:**
```python
from typing import TypedDict

class UserDict(TypedDict):
    id: int
    name: str
    email: str
    age: int

def create_user(data: UserDict) -> User:
    """Create user from dictionary."""
    return User(**data)

# Usage with type checking:
user_data: UserDict = {
    'id': 1,
    'name': 'John',
    'email': 'john@example.com',
    'age': 30
}
```

**Better than:**
```python
def create_user(data: Dict[str, Any]) -> User:  # Less specific
    return User(**data)
```

---

### Rule 6: Use Callable for Functions

**✅ CORRECT:**
```python
from typing import Callable

def apply_discount(
    price: float,
    calculator: Callable[[float], float]
) -> float:
    """Apply discount using calculator function."""
    return calculator(price)

# Usage:
def student_discount(price: float) -> float:
    return price * 0.9

final_price = apply_discount(100.0, student_discount)
```

**Callable signature:**
```python
# Callable[[arg1_type, arg2_type], return_type]
Callable[[int, str], bool]  # Function taking int, str, returning bool
Callable[[], None]          # Function with no args, returns None
Callable[..., Any]          # Function with any args, any return
```

---

### Rule 7: Use dataclass with Type Hints

**✅ CORRECT:**
```python
from dataclasses import dataclass
from typing import List, Optional

@dataclass
class User:
    id: int
    name: str
    email: str
    age: Optional[int] = None
    tags: List[str] = None
    
    def __post_init__(self):
        if self.tags is None:
            self.tags = []

# Type-safe usage:
user = User(
    id=1,
    name='John',
    email='john@example.com'
)
```

---

## 🔧 ADVANCED PATTERNS

### Pattern 1: Generic Types

**✅ CORRECT:**
```python
from typing import TypeVar, Generic, List

T = TypeVar('T')

class Stack(Generic[T]):
    def __init__(self) -> None:
        self.items: List[T] = []
    
    def push(self, item: T) -> None:
        self.items.append(item)
    
    def pop(self) -> T:
        return self.items.pop()

# Usage:
int_stack: Stack[int] = Stack()
int_stack.push(1)
value: int = int_stack.pop()
```

---

### Pattern 2: Protocol (Structural Subtyping)

**✅ CORRECT:**
```python
from typing import Protocol

class Drawable(Protocol):
    def draw(self) -> None:
        ...

def render(obj: Drawable) -> None:
    """Render any object with draw method."""
    obj.draw()

# Any class with draw() method works:
class Circle:
    def draw(self) -> None:
        print("Drawing circle")

render(Circle())  # Type-safe!
```

---

### Pattern 3: Literal Types

**✅ CORRECT:**
```python
from typing import Literal

LogLevel = Literal['debug', 'info', 'warning', 'error']

def log(message: str, level: LogLevel = 'info') -> None:
    """Log message at specified level."""
    print(f"[{level}] {message}")

# Type checker enforces valid values:
log("Hello", level='info')     # ✅ OK
log("Hello", level='invalid')  # ❌ Type error!
```

---

### Pattern 4: Type Aliases

**✅ CORRECT:**
```python
from typing import Dict, List, Union

# Simple alias
UserId = int
UserName = str

# Complex alias
UserData = Dict[str, Union[str, int, List[str]]]

def get_user(user_id: UserId) -> UserData:
    return {
        'name': 'John',
        'age': 30,
        'tags': ['admin', 'user']
    }
```

---

### Pattern 5: Forward References

**✅ CORRECT:**
```python
from __future__ import annotations
from typing import Optional

class Node:
    def __init__(self, value: int, next: Optional[Node] = None):
        self.value = value
        self.next = next

# Or use string annotation:
class Node:
    def __init__(self, value: int, next: Optional['Node'] = None):
        self.value = value
        self.next = next
```

---

### Pattern 6: Overload for Multiple Signatures

**✅ CORRECT:**
```python
from typing import overload, Union

@overload
def process(data: int) -> int: ...

@overload
def process(data: str) -> str: ...

def process(data: Union[int, str]) -> Union[int, str]:
    """Process int or str differently."""
    if isinstance(data, int):
        return data * 2
    return data.upper()
```

---

## ⚠️ ANTI-PATTERNS

### AP-1: Using Any Everywhere

**❌ WRONG:**
```python
from typing import Any

def process(data: Any) -> Any:  # Too vague
    return transform(data)
```

**✅ CORRECT:**
```python
from typing import Union

def process(data: Union[int, str, dict]) -> dict:
    return transform(data)
```

---

### AP-2: Not Using Optional for Nullable

**❌ WRONG:**
```python
def find_user(id: int) -> User:  # Can return None!
    return db.get(id)
```

**✅ CORRECT:**
```python
def find_user(id: int) -> Optional[User]:
    return db.get(id)
```

---

### AP-3: Inconsistent Annotations

**❌ WRONG:**
```python
def process_user(
    user_id: int,
    name,  # Missing annotation
    email: str
) -> dict:  # Use TypedDict instead
    pass
```

**✅ CORRECT:**
```python
def process_user(
    user_id: int,
    name: str,
    email: str
) -> UserDict:
    pass
```

---

## 📚 EXAMPLES

### Example 1: API Response Handler

```python
from typing import TypedDict, Optional, List, Union

class ErrorResponse(TypedDict):
    error: str
    code: int

class SuccessResponse(TypedDict):
    data: dict
    message: str

Response = Union[SuccessResponse, ErrorResponse]

def handle_api_call(endpoint: str) -> Response:
    """Call API and return typed response."""
    try:
        result = api.get(endpoint)
        return {
            'data': result,
            'message': 'Success'
        }
    except Exception as e:
        return {
            'error': str(e),
            'code': 500
        }
```

---

### Example 2: Generic Cache

```python
from typing import TypeVar, Generic, Optional, Dict

K = TypeVar('K')  # Key type
V = TypeVar('V')  # Value type

class Cache(Generic[K, V]):
    def __init__(self) -> None:
        self._store: Dict[K, V] = {}
    
    def get(self, key: K) -> Optional[V]:
        return self._store.get(key)
    
    def set(self, key: K, value: V) -> None:
        self._store[key] = value
    
    def delete(self, key: K) -> bool:
        if key in self._store:
            del self._store[key]
            return True
        return False

# Usage:
user_cache: Cache[int, User] = Cache()
user_cache.set(1, User(id=1, name='John'))
user: Optional[User] = user_cache.get(1)
```

---

### Example 3: Builder Pattern with Types

```python
from typing import TypeVar, Generic, Optional
from dataclasses import dataclass

@dataclass
class Query:
    table: str
    where: Optional[dict] = None
    limit: Optional[int] = None
    order_by: Optional[str] = None

T = TypeVar('T', bound='QueryBuilder')

class QueryBuilder(Generic[T]):
    def __init__(self) -> None:
        self._table: Optional[str] = None
        self._where: Optional[dict] = None
        self._limit: Optional[int] = None
        self._order_by: Optional[str] = None
    
    def table(self: T, name: str) -> T:
        self._table = name
        return self
    
    def where(self: T, conditions: dict) -> T:
        self._where = conditions
        return self
    
    def limit(self: T, count: int) -> T:
        self._limit = count
        return self
    
    def order_by(self: T, field: str) -> T:
        self._order_by = field
        return self
    
    def build(self) -> Query:
        if self._table is None:
            raise ValueError("Table must be specified")
        return Query(
            table=self._table,
            where=self._where,
            limit=self._limit,
            order_by=self._order_by
        )

# Usage with type checking:
query = (QueryBuilder()
    .table('users')
    .where({'status': 'active'})
    .limit(10)
    .build())
```

---

## ✅ VERIFICATION CHECKLIST

Before committing:

- [ ] All public functions have type hints
- [ ] Optional used for nullable returns
- [ ] Specific types over Any
- [ ] Collections have generic types (List[int] not list)
- [ ] TypedDict for structured dicts
- [ ] Type hints match docstrings
- [ ] No inconsistent annotations
- [ ] Complex types use aliases

---

## 🔗 CROSS-REFERENCES

### Related Patterns

- **LANG-PY-03**: Document type expectations
- **LANG-PY-04**: Type hints in function signatures
- **LANG-PY-07**: Type checking in quality standards

### Tools

- **mypy**: Static type checker
- **pyright**: Microsoft's type checker
- **pydantic**: Runtime type validation

---

## 🤝 CONTRIBUTORS

**Original Author:** SIMAv4 Phase 5.1  
**Source Material:** SUGA-ISP Python standards  
**Extracted From:** LANG-PY-03-through-08 consolidated file  
**Last Reviewed:** 2025-11-01

---

**END OF LANG-PY-06**

**Lines:** ~390  
**REF-ID:** LANG-PY-06  
**Status:** Active  
**Next:** LANG-PY-07 (Code Quality)
# LANG-PY-05: Python Import and Module Organization

**REF-ID:** LANG-PY-05  
**Category:** Language Patterns  
**Subcategory:** Module Organization  
**Language:** Python  
**Priority:** 🟡 HIGH  
**Status:** Active  
**Created:** 2025-11-01  
**Last Updated:** 2025-11-01

---

## 📋 SUMMARY

Best practices for organizing imports and Python modules: import order, avoiding circular dependencies, lazy loading, and module structure.

---

## 🎯 CORE RULES

### Rule 1: Standard Import Order

Always organize imports in three groups, separated by blank lines:

**✅ CORRECT:**
```python
# Standard library imports
import os
import sys
from datetime import datetime
from typing import List, Dict, Optional

# Third-party imports
import requests
import boto3
from flask import Flask, jsonify

# Local application imports
from .config import settings
from .models import User, Order
from .utils import format_date
```

**Order:**
1. Standard library
2. Third-party packages
3. Local application/library

**Within each group:**
- `import` statements first
- `from ... import` statements second
- Alphabetically sorted

---

### Rule 2: Never Use Wildcard Imports

**❌ WRONG:**
```python
from module import *
```

**✅ CORRECT:**
```python
from module import specific_function, SpecificClass
```

**Why:**
- Makes it unclear where names come from
- Can overwrite existing names
- Makes static analysis difficult
- Pollutes namespace

---

### Rule 3: Use Absolute Imports

**✅ CORRECT:**
```python
# In src/handlers/user_handler.py
from src.models.user import User
from src.utils.validation import validate_email
```

**🟡 ACCEPTABLE (Relative imports for packages):**
```python
# In src/handlers/user_handler.py
from ..models.user import User
from ..utils.validation import validate_email
```

**Use absolute imports for:**
- Top-level modules
- Cross-package imports
- Better clarity

**Use relative imports for:**
- Within same package
- Refactoring flexibility

---

### Rule 4: Lazy Loading for Heavy Modules

**✅ CORRECT:**
```python
def process_image(image_path):
    """Process image (lazy load PIL)."""
    from PIL import Image  # Only imported when needed
    
    img = Image.open(image_path)
    return img.resize((800, 600))
```

**When to use:**
- Expensive imports (PIL, numpy, tensorflow)
- Conditional imports
- Lambda cold start optimization
- Rarely used functions

**❌ DON'T lazy load:**
- Standard library
- Lightweight modules
- Core dependencies

---

### Rule 5: Avoid Circular Dependencies

**❌ WRONG:**
```python
# models/user.py
from .order import Order

class User:
    def get_orders(self):
        return Order.get_by_user(self.id)

# models/order.py
from .user import User

class Order:
    def get_user(self):
        return User.get_by_id(self.user_id)
```

**✅ CORRECT (Move to Third Module):**
```python
# models/user.py
class User:
    pass

# models/order.py
class Order:
    pass

# services/user_service.py
from models.user import User
from models.order import Order

def get_user_orders(user_id):
    user = User.get_by_id(user_id)
    orders = Order.get_by_user(user_id)
    return orders
```

**✅ CORRECT (Lazy Import):**
```python
# models/user.py
class User:
    def get_orders(self):
        from .order import Order  # Import inside method
        return Order.get_by_user(self.id)
```

---

### Rule 6: Group Related Imports

**✅ CORRECT:**
```python
# Typing imports together
from typing import List, Dict, Optional, Any, Callable

# Related AWS services together
import boto3
from botocore.exceptions import ClientError, BotoCoreError

# HTTP-related together
import requests
from requests.exceptions import Timeout, ConnectionError
```

---

### Rule 7: One Import Per Line (for from imports)

**❌ WRONG:**
```python
from models import User, Order, Product, Category, Tag, Review
```

**✅ CORRECT:**
```python
from models import (
    User,
    Order,
    Product,
    Category,
    Tag,
    Review,
)
```

**For simple imports:**
```python
import os
import sys
import json
```

---

## 🔧 PATTERNS

### Pattern 1: Conditional Imports

**✅ CORRECT:**
```python
# Import based on Python version
try:
    from typing import TypedDict  # Python 3.8+
except ImportError:
    from typing_extensions import TypedDict

# Import based on availability
try:
    import ujson as json
except ImportError:
    import json

# Platform-specific imports
import sys
if sys.platform == 'win32':
    from .windows_utils import get_system_info
else:
    from .unix_utils import get_system_info
```

---

### Pattern 2: Type-Checking Only Imports

**✅ CORRECT:**
```python
from typing import TYPE_CHECKING

if TYPE_CHECKING:
    # Only imported for type checking, not at runtime
    from expensive_module import HeavyClass

def process_data(data: 'HeavyClass') -> str:
    # Use string annotation to avoid runtime import
    return data.process()
```

---

### Pattern 3: Module Aliases

**✅ CORRECT:**
```python
# Standard aliases
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt

# Custom aliases for clarity
from src.services import user_service as user_svc
from src.services import order_service as order_svc
```

**Use aliases when:**
- Standard convention exists (np, pd)
- Module name is too long
- Avoiding name conflicts

---

### Pattern 4: __init__.py for Package Organization

**✅ CORRECT:**
```python
# src/models/__init__.py
from .user import User
from .order import Order
from .product import Product

__all__ = ['User', 'Order', 'Product']

# Now can do:
from src.models import User, Order
```

---

## 📁 MODULE STRUCTURE

### Small Module (< 500 lines)

```python
"""
Module docstring describing purpose.
"""

# Imports (grouped as per rules)
import os
from typing import Optional

# Module-level constants
DEFAULT_TIMEOUT = 30
MAX_RETRIES = 3

# Module-level variables (avoid if possible)
_cache = {}

# Functions and classes
def public_function():
    pass

def _private_function():
    pass

class PublicClass:
    pass

# Main execution (if script)
if __name__ == '__main__':
    main()
```

---

### Large Module (Split into Package)

```
mypackage/
├── __init__.py          # Public API
├── core.py              # Core functionality
├── utils.py             # Utilities
├── models.py            # Data models
├── exceptions.py        # Custom exceptions
└── _internal.py         # Private implementation
```

---

## ⚠️ ANTI-PATTERNS

### AP-1: Star Imports

**❌ WRONG:**
```python
from utils import *
```

### AP-2: Circular Dependencies

**❌ WRONG:**
```python
# a.py imports b.py, b.py imports a.py
```

### AP-3: Runtime Import Errors Ignored

**❌ WRONG:**
```python
try:
    import critical_module
except ImportError:
    pass  # Don't silently fail
```

**✅ CORRECT:**
```python
try:
    import critical_module
except ImportError as e:
    raise RuntimeError(
        "Critical module not available. Install with: pip install critical"
    ) from e
```

### AP-4: Deep Module Nesting

**❌ WRONG:**
```python
from src.services.handlers.users.validators.email import validate
```

**✅ CORRECT:**
```python
# Flatten structure or use __init__.py
from src.services import validate_email
```

---

## 📚 EXAMPLES

### Example 1: Lambda Handler Module

```python
"""
AWS Lambda handler for user operations.
"""

# Standard library
import json
import logging
from datetime import datetime
from typing import Dict, Any

# Third-party
import boto3
from botocore.exceptions import ClientError

# Local - using absolute imports
from src.models.user import User
from src.utils.response import success_response, error_response
from src.config import settings

# Setup logging
logger = logging.getLogger(__name__)

def lambda_handler(event: Dict[str, Any], context: Any) -> Dict[str, Any]:
    """Handle user API requests."""
    try:
        # Lazy load heavy modules
        from src.services.user_service import process_user
        
        result = process_user(event)
        return success_response(result)
    except ValueError as e:
        logger.error(f"Validation error: {e}")
        return error_response(str(e), 400)
    except Exception as e:
        logger.error(f"Unexpected error: {e}", exc_info=True)
        return error_response("Internal error", 500)
```

---

### Example 2: Package __init__.py

```python
"""
User management package.

Provides models and services for user operations.
"""

# Public API
from .models import User, UserProfile
from .services import (
    create_user,
    update_user,
    delete_user,
    get_user,
)
from .exceptions import (
    UserNotFoundError,
    DuplicateUserError,
    InvalidUserError,
)

# Define what's exported with "from package import *"
__all__ = [
    'User',
    'UserProfile',
    'create_user',
    'update_user',
    'delete_user',
    'get_user',
    'UserNotFoundError',
    'DuplicateUserError',
    'InvalidUserError',
]

# Package metadata
__version__ = '1.0.0'
__author__ = 'Team'
```

---

### Example 3: Conditional Heavy Import

```python
"""
Image processing module with lazy imports.
"""

from typing import Optional

# Don't import PIL at module level
# from PIL import Image  # ❌ WRONG

def resize_image(image_path: str, width: int, height: int) -> Optional[str]:
    """Resize image file.
    
    Args:
        image_path: Path to image
        width: Target width
        height: Target height
    
    Returns:
        Path to resized image or None if failed
    """
    try:
        from PIL import Image  # ✅ CORRECT: Import only when needed
    except ImportError:
        raise RuntimeError(
            "PIL not available. Install with: pip install Pillow"
        )
    
    try:
        img = Image.open(image_path)
        img = img.resize((width, height))
        output_path = f"{image_path}_resized.jpg"
        img.save(output_path)
        return output_path
    except Exception as e:
        logger.error(f"Failed to resize image: {e}")
        return None
```

---

## ✅ VERIFICATION CHECKLIST

Before committing:

- [ ] Imports grouped: stdlib, 3rd-party, local
- [ ] No wildcard imports (from x import *)
- [ ] No circular dependencies
- [ ] Heavy modules lazy-loaded
- [ ] Imports alphabetically sorted within groups
- [ ] Unused imports removed
- [ ] Import errors handled appropriately
- [ ] TYPE_CHECKING used for expensive type imports

---

## 🔗 CROSS-REFERENCES

### Related Patterns

- **LANG-PY-01**: Module naming conventions
- **LANG-PY-04**: Function organization
- **LANG-PY-07**: Code organization quality

### Architecture

- **GATE-02**: Lazy loading in gateway layer
- **ARCH-01**: SUGA layer separation

### Anti-Patterns

- **AP-01**: Direct core imports
- **AP-02**: Circular dependencies
- **AP-05**: Wildcard imports

---

## 🤝 CONTRIBUTORS

**Original Author:** SIMAv4 Phase 5.1  
**Source Material:** SUGA-ISP Python standards  
**Extracted From:** LANG-PY-03-through-08 consolidated file  
**Last Reviewed:** 2025-11-01

---

**END OF LANG-PY-05**

**Lines:** ~395  
**REF-ID:** LANG-PY-05  
**Status:** Active  
**Next:** LANG-PY-06 (Type Hints)
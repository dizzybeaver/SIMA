# NM04-ADDENDUM: Failsafe Design Decision
**REF:** NM04-DEC-24  
**DATE:** 2025.10.21  
**PRIORITY:** 🔴 CRITICAL

---

## Decision 24: Lambda Failsafe Independence

**What:** `lambda_failsafe.py` is intentionally isolated from SUGA infrastructure.

**Why:**

1. **Emergency Recovery** - Works when SUGA is broken
2. **1-Variable Switch** - Change `LAMBDA_MODE=failsafe` to activate
3. **Minimal Dependencies** - Only stdlib + boto3 for SSM
4. **Zero Coupling** - Bypasses all SUGA complexity

**Independence Rules:**

```python
# ✅ ALLOWED in lambda_failsafe.py:
import os, time, json, logging
import boto3  # Only for SSM token retrieval
from urllib3 import PoolManager  # Only for HTTP

# ❌ FORBIDDEN in lambda_failsafe.py:
from gateway import *  # NO SUGA dependencies
from config_param_store import *  # Only if it doesn't use gateway
from interface_* import *  # NO interface dependencies
```

**Failsafe Recovery Modes:**

```bash
# Mode 1: SSM token (preferred)
LAMBDA_MODE=failsafe
USE_PARAMETER_STORE=true

# Mode 2: Environment token (if SSM broken)
LAMBDA_MODE=failsafe
USE_PARAMETER_STORE=false
HOME_ASSISTANT_TOKEN=your_token

# Mode 3: Full manual (worst case)
LAMBDA_MODE=failsafe
USE_PARAMETER_STORE=false
HOME_ASSISTANT_TOKEN=your_token
HOME_ASSISTANT_URL=https://...
```

**Performance:**
- Init: ~150ms (minimal imports)
- SSM first call: ~250-5000ms (AWS network dependent)
- SSM cached: <2ms (5min TTL)
- HTTP forward: ~350ms
- Total first call: ~650-5500ms (AWS dependent)
- Total cached: ~500ms (acceptable for emergency mode)

**Cache Strategy:**
- Simple in-memory dict: `{'value': token, 'timestamp': time}`
- TTL: 300 seconds
- NO gateway cache dependency
- Lives in lambda_failsafe.py directly

**Critical Fix (2025.10.21):**
Removed `config_param_store.get_ha_token()` usage because it imports gateway.
Now uses direct boto3.client('ssm') with simple internal caching.

**Related:**
- DEC-20: LAMBDA_MODE operational modes
- DEC-21: SSM token-only design
- BUG-05: SSM client initialization overhead (fixed)

---

## Usage Example

```python
# lambda_function.py
lambda_mode = os.environ.get('LAMBDA_MODE', 'normal')

if lambda_mode == 'failsafe':
    from lambda_failsafe import handler as failsafe_handler
    return failsafe_handler(event, context)  # Bypasses SUGA entirely
```

**Recovery Time:** < 60 seconds to switch Lambda to failsafe mode

---

# EOF

# File: INT-06-Config-Interface.md

**REF-ID:** INT-06  
**Version:** 1.0.0  
**Category:** Interface Pattern  
**Status:** Active  
**Priority:** 🔴 CRITICAL  
**Created:** 2025-11-01  
**Contributors:** SIMA Learning Mode

---

## 📋 OVERVIEW

**Interface Name:** CONFIG  
**Short Code:** CFG  
**Type:** Configuration Management Interface  
**Dependency Layer:** Layer 0 (Foundation)

**One-Line Description:**  
CONFIG interface manages environment variables, feature flags, and application configuration.

**Primary Purpose:**  
Provide centralized, type-safe access to configuration with validation, defaults, and environment-specific overrides.

---

## 🎯 CORE RESPONSIBILITIES

### 1. Configuration Loading
- Load environment variables
- Parse configuration files
- Fetch remote config (SSM, Secrets Manager)
- Apply environment-specific overrides

### 2. Type Safety
- Convert strings to correct types
- Validate configuration values
- Provide type hints
- Handle missing values gracefully

### 3. Default Management
- Define sensible defaults
- Document expected values
- Flag missing required config
- Suggest configuration fixes

### 4. Dynamic Updates
- Support config reloading
- Feature flag evaluation
- Runtime configuration changes
- Cache invalidation

---

## 🔑 KEY RULES

### Rule 1: Never Hardcode Configuration
**What:** All environment-specific values MUST be externalized.

**Examples of Config:**
- âœ… API endpoints
- âœ… Database connection strings
- âœ… Feature flags
- âœ… Timeouts and thresholds
- âœ… API keys and secrets

**Examples NOT Config:**
- âŒ Business logic
- âŒ Algorithms
- âŒ Core functionality

**Example:**
```python
# ❌ DON'T: Hardcode
API_URL = "https://api.production.com"
TIMEOUT = 30

# ✅ DO: Use config
from gateway import get_config

API_URL = get_config("API_URL")
TIMEOUT = get_config("TIMEOUT", default=30, type=int)
```

---

### Rule 2: Validate Configuration Early
**What:** Check all required configuration at startup (initialization), not at runtime.

**Why:** Fail fast. Don't wait for request to discover missing config.

**Example:**
```python
# At module level (runs during cold start)
from gateway import require_config, validate_config

# ✅ These raise exceptions if missing/invalid
API_KEY = require_config("API_KEY")
API_URL = require_config("API_URL")

# Validate format
validate_config("API_URL", pattern=r"^https://.*")
validate_config("TIMEOUT", type=int, min_value=1, max_value=60)

# Now safe to use in handler
def lambda_handler(event, context):
    # API_KEY and API_URL are guaranteed valid
    pass
```

---

### Rule 3: Use Type Conversion
**What:** Environment variables are always strings. Convert to correct type.

**Example:**
```python
from gateway import get_config

# ❌ DON'T: Use string values for non-strings
enabled = os.environ.get("FEATURE_ENABLED")  # "true" string
if enabled:  # WRONG! "false" string is truthy!
    use_feature()

# ✅ DO: Convert to boolean
enabled = get_config("FEATURE_ENABLED", type=bool, default=False)
if enabled:  # Correct boolean comparison
    use_feature()

# ✅ DO: Convert to numbers
timeout = get_config("TIMEOUT", type=int, default=30)
retry_delay = get_config("RETRY_DELAY", type=float, default=1.5)
```

---

### Rule 4: Provide Sensible Defaults
**What:** Every non-secret config should have a documented default.

**Why:** Makes local development easier. Reduces configuration burden.

**Example:**
```python
# ✅ Good defaults
LOG_LEVEL = get_config("LOG_LEVEL", default="INFO")
TIMEOUT = get_config("TIMEOUT", type=int, default=30)
MAX_RETRIES = get_config("MAX_RETRIES", type=int, default=3)

# ❌ No defaults for secrets (fail if missing)
API_KEY = require_config("API_KEY")  # Must be set
DB_PASSWORD = require_config("DB_PASSWORD")  # Must be set
```

---

### Rule 5: Namespace Configuration
**What:** Use prefixes to group related configuration.

**Why:** Prevents naming collisions. Makes purpose clear.

**Example:**
```python
# ✅ DO: Use prefixes
DB_HOST = get_config("DB_HOST")
DB_PORT = get_config("DB_PORT", type=int)
DB_NAME = get_config("DB_NAME")

API_TIMEOUT = get_config("API_TIMEOUT", type=int)
API_MAX_RETRIES = get_config("API_MAX_RETRIES", type=int)

CACHE_TTL = get_config("CACHE_TTL", type=int)
CACHE_SIZE = get_config("CACHE_SIZE", type=int)

# ❌ DON'T: Generic names
HOST = get_config("HOST")  # Host of what?
TIMEOUT = get_config("TIMEOUT")  # Timeout for what?
SIZE = get_config("SIZE")  # Size of what?
```

---

## 🎨 MAJOR BENEFITS

### Benefit 1: Environment Portability
- Same code runs in dev, staging, production
- Change config without code changes
- Easy to test different configurations
- Simplifies deployment

### Benefit 2: Security
- Secrets separate from code
- No credentials in version control
- Easy secret rotation
- Audit trail for config changes

### Benefit 3: Flexibility
- Feature flags for gradual rollouts
- A/B testing support
- Regional configuration
- Customer-specific config

### Benefit 4: Maintainability
- Single source of truth
- Type-safe access
- Self-documenting with defaults
- Early validation catches errors

---

## 📚 CORE FUNCTIONS

### Gateway Functions

```python
# Basic access
get_config(key, default=None, type=str)
require_config(key, type=str)
has_config(key)

# Type conversion
get_int_config(key, default=None, min=None, max=None)
get_float_config(key, default=None, min=None, max=None)
get_bool_config(key, default=None)
get_list_config(key, default=None, separator=",")
get_dict_config(key, default=None)

# Validation
validate_config(key, pattern=None, choices=None, type=None)
validate_url(key, schemes=["https"])
validate_port(key, min=1, max=65535)

# Feature flags
is_feature_enabled(feature_name, default=False)
get_feature_value(feature_name, default=None)

# Secrets (from AWS Secrets Manager/SSM)
get_secret(secret_name, cache_ttl=300)
get_ssm_parameter(parameter_name, decrypt=True)

# Dynamic updates
reload_config()
invalidate_config_cache()
```

---

## 🔄 USAGE PATTERNS

### Pattern 1: Basic Configuration
```python
from gateway import get_config, require_config

# Required (fails if missing)
API_KEY = require_config("API_KEY")

# Optional with default
LOG_LEVEL = get_config("LOG_LEVEL", default="INFO")
TIMEOUT = get_config("TIMEOUT", type=int, default=30)

def lambda_handler(event, context):
    # Use config
    api_url = get_config("API_URL")
```

### Pattern 2: Typed Configuration
```python
from gateway import get_int_config, get_bool_config, get_list_config

# Integer with bounds
MAX_RETRIES = get_int_config("MAX_RETRIES", default=3, min=1, max=10)

# Boolean
DEBUG_MODE = get_bool_config("DEBUG_MODE", default=False)

# List (comma-separated)
ALLOWED_ORIGINS = get_list_config("ALLOWED_ORIGINS", default=["*"])

# Float
RETRY_BACKOFF = get_float_config("RETRY_BACKOFF", default=1.5)
```

### Pattern 3: Feature Flags
```python
from gateway import is_feature_enabled, get_feature_value

def process_request(event):
    # Boolean flag
    if is_feature_enabled("new_algorithm"):
        return new_algorithm(event)
    else:
        return old_algorithm(event)
    
    # Value flag (percentage rollout)
    rollout_pct = get_feature_value("rollout_percentage", default=0)
    if random.random() * 100 < rollout_pct:
        return new_feature(event)
```

### Pattern 4: Secrets Management
```python
from gateway import get_secret, get_ssm_parameter

# From AWS Secrets Manager (cached 5 min)
db_credentials = get_secret("production/database")
db_host = db_credentials["host"]
db_password = db_credentials["password"]

# From SSM Parameter Store (encrypted)
api_key = get_ssm_parameter("/app/api/key", decrypt=True)
```

### Pattern 5: Configuration Object
```python
from gateway import get_config, get_int_config, get_bool_config
from dataclasses import dataclass

@dataclass
class AppConfig:
    api_url: str
    timeout: int
    max_retries: int
    debug: bool
    
    @classmethod
    def load(cls):
        return cls(
            api_url=get_config("API_URL"),
            timeout=get_int_config("TIMEOUT", default=30),
            max_retries=get_int_config("MAX_RETRIES", default=3),
            debug=get_bool_config("DEBUG", default=False)
        )

# Load once at module level
CONFIG = AppConfig.load()

def lambda_handler(event, context):
    # Use typed config object
    response = call_api(CONFIG.api_url, timeout=CONFIG.timeout)
```

---

## ⚠️ ANTI-PATTERNS

### Anti-Pattern 1: Runtime Config Loading ❌
```python
# ❌ DON'T: Load config in handler (every request)
def lambda_handler(event, context):
    api_key = os.environ.get("API_KEY")  # Slow, repeated
    process(api_key)

# ✅ DO: Load at module level (once per container)
API_KEY = get_config("API_KEY")

def lambda_handler(event, context):
    process(API_KEY)  # Fast
```

### Anti-Pattern 2: String Boolean Comparisons ❌
```python
# ❌ DON'T: Treat string as boolean
enabled = os.environ.get("ENABLED", "false")
if enabled:  # WRONG! "false" string is truthy!
    do_thing()

# ✅ DO: Convert to boolean
enabled = get_bool_config("ENABLED", default=False)
if enabled:  # Correct boolean comparison
    do_thing()
```

### Anti-Pattern 3: Hardcoded Values ❌
```python
# ❌ DON'T: Hardcode environment-specific values
if environment == "prod":
    url = "https://api.production.com"
else:
    url = "https://api.staging.com"

# ✅ DO: Use configuration
url = get_config("API_URL")
```

### Anti-Pattern 4: No Validation ❌
```python
# ❌ DON'T: Use config without validation
timeout = int(os.environ.get("TIMEOUT", "30"))
# Could fail at runtime if TIMEOUT="abc"

# ✅ DO: Validate early
timeout = get_int_config("TIMEOUT", default=30, min=1, max=300)
# Fails at startup if invalid
```

---

## 🔗 CROSS-REFERENCES

**Related Architecture:**
- ARCH-01 (SUGA): Config is Layer 0
- ARCH-02 (LMMS): Config affects memory

**Related Interfaces:**
- INT-05 (Initialization): Load config during init
- INT-02 (Logging): Config controls log level
- INT-07 (Metrics): Config enables/disables metrics
- INT-03 (Security): Config for secrets

**Related Patterns:**
- GATE-02 (Lazy Loading): Config loaded on demand
- GATE-05 (Optimization): Cache config values

**Related Lessons:**
- LESS-12 (Config Management): Best practices
- LESS-28 (Secrets): Handle secrets properly
- LESS-34 (Environment Vars): Type conversion

**Related Decisions:**
- DEC-06 (Config Strategy): Environment variables
- DEC-11 (Secrets): Use AWS Secrets Manager

---

## ✅ VERIFICATION CHECKLIST

Before deploying config code:
- [ ] All config loaded at module level
- [ ] Required config validated at startup
- [ ] Type conversion used for non-strings
- [ ] Sensible defaults provided
- [ ] Secrets never hardcoded
- [ ] Config namespaced appropriately
- [ ] Feature flags properly implemented
- [ ] Config documented
- [ ] Error messages helpful
- [ ] Validation rules enforced

---

## 📊 CONFIGURATION CATEGORIES

### Environment Variables (Standard)
```
LOG_LEVEL=INFO
ENVIRONMENT=production
AWS_REGION=us-east-1
```

### Service Configuration
```
API_URL=https://api.example.com
API_TIMEOUT=30
API_MAX_RETRIES=3
```

### Feature Flags
```
FEATURE_NEW_ALGORITHM=true
FEATURE_ROLLOUT_PERCENTAGE=25
```

### Secrets (from Secrets Manager)
```
API_KEY=secret://production/api/key
DB_PASSWORD=secret://production/database/password
```

---

**END OF INTERFACE ENTRY**

**REF-ID:** INT-06  
**Status:** Active  
**Lines:** 390
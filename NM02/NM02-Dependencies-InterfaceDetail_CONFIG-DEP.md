# NM02-Dependencies-InterfaceDetail_CONFIG-DEP.md - CONFIG-DEP

# CONFIG-DEP: CONFIG Interface Dependencies (Deep Dive)

**Category:** NM02 - Dependencies
**Topic:** Interface Detail
**Priority:** 🟡 High
**Status:** Active
**Created:** 2024-04-15
**Last Updated:** 2025-10-24

---

## Summary

Detailed analysis of CONFIG interface dependencies: what CONFIG depends on (LOGGING, CACHE, SECURITY) and what depends on CONFIG (all interfaces). Includes configuration sources, performance metrics, and common config keys.

---

## Context

CONFIG is a Layer 2 interface that provides centralized configuration management. This deep dive examines its dependencies and universal usage across the system.

**Why This Analysis Exists:**
- Shows complete dependency picture
- Documents configuration patterns
- Provides performance baselines
- Guides configuration management

---

## Content

### CONFIG Dependencies

**What CONFIG Depends On:**

```
CONFIG depends on:
├─ LOGGING (for configuration changes)
│   ├─ Used in: interface_config.py, config_core.py
│   ├─ Functions: log_info, log_warning
│   └─ Purpose: Log config loads, changes, errors
│
├─ CACHE (for configuration caching)
│   ├─ Used in: config_core.py
│   ├─ Functions: cache_get, cache_set
│   └─ Purpose: Cache frequently accessed config values
│
└─ SECURITY (for input validation)
    ├─ Used in: config_core.py
    ├─ Functions: validate_string, sanitize_input
    └─ Purpose: Validate config values
```

**Dependency Pattern:**
```python
# In config_core.py
from gateway import (
    log_info,                 # LOGGING (Layer 0)
    cache_get, cache_set,     # CACHE (Layer 2)
    validate_string           # SECURITY (Layer 1)
)

def get_config(key):
    log_info(f"Config lookup: {key}")
    
    # Try cache first (Layer 2)
    cache_key = f"config_{key}"
    value = cache_get(cache_key)
    if value:
        return value
    
    # Load from source
    value = _load_from_source(key)
    
    # Validate (Layer 1)
    if validate_string(value):
        cache_set(cache_key, value)
        return value
    
    return None
```

### CONFIG Used By

**What Depends on CONFIG:**

```
CONFIG is used by:
└─ All interfaces (for configuration)
    ├─ HTTP_CLIENT (URL, timeout, retry settings)
    ├─ CACHE (TTL defaults, size limits)
    ├─ LOGGING (log level, output format)
    ├─ SECURITY (token secrets, encryption keys)
    ├─ WEBSOCKET (connection URLs, timeouts)
    └─ All others (interface-specific settings)
```

**Universal Usage Pattern:**
```python
# Any interface can get config
from gateway import get_config

# Get configuration
setting = get_config("interface_setting")
```

### Configuration Sources

**Priority Order (Highest to Lowest):**

**1. Environment Variables:**
```python
# Highest priority
value = os.environ.get("HOME_ASSISTANT_URL")
# Used for: runtime configuration, deployment-specific settings
```

**2. AWS Parameter Store:**
```python
# Second priority (if enabled)
value = ssm_client.get_parameter(
    Name="/lambda-execution-engine/home_assistant/token"
)
# Used for: secrets, shared configuration
```

**3. Default Values:**
```python
# Fallback
value = DEFAULT_CONFIG.get(key, None)
# Used for: sensible defaults, optional settings
```

### Performance Characteristics

**Configuration Load Times:**
```
First Load (Parameter Store):
- SSM API call: 10-20ms
- Validation: < 1ms
- Cache save: < 2ms
Total: 12-23ms

Cached Load:
- Cache lookup: < 1ms
Total: < 1ms

Speedup: 12-23x faster!
```

**Cache Strategy:**
```
Config values cached indefinitely because:
- Config doesn't change during runtime
- No TTL needed (persistent)
- Reload requires Lambda redeployment

Cache invalidation:
- Only on Lambda cold start
- Or manual cache_clear() call
```

### Common Configuration Keys

**System Configuration:**
```
Key: log_level
- Values: DEBUG, INFO, WARNING, ERROR, CRITICAL
- Source: Environment variable
- Default: INFO
- Usage: Controls logging verbosity

Key: debug_mode
- Values: true, false
- Source: Environment variable
- Default: false
- Usage: Enable/disable debug features
```

**Home Assistant Configuration:**
```
Key: home_assistant_url
- Values: https://ha.example.com
- Source: Environment variable
- Default: None
- Usage: Home Assistant server URL

Key: home_assistant_token
- Values: long_lived_access_token
- Source: AWS Parameter Store (SecureString)
- Default: None
- Usage: Authentication token
```

**HTTP Configuration:**
```
Key: http_timeout
- Values: 10, 30, 60 (seconds)
- Source: Environment variable
- Default: 30
- Usage: HTTP request timeout

Key: http_retry_count
- Values: 0, 1, 2, 3
- Source: Environment variable
- Default: 2
- Usage: Request retry attempts
```

**Cache Configuration:**
```
Key: cache_ttl_default
- Values: 60, 300, 600 (seconds)
- Source: Environment variable
- Default: 300
- Usage: Default cache TTL

Key: cache_max_size
- Values: 100, 500, 1000 (entries)
- Source: Environment variable
- Default: 500
- Usage: Maximum cache entries
```

### Configuration Patterns

**Pattern 1: Required Configuration:**
```python
# Must have value, no default
url = get_config("home_assistant_url")
if not url:
    raise ConfigError("home_assistant_url required")
```

**Pattern 2: Optional with Default:**
```python
# Has sensible default
timeout = get_config("http_timeout") or 30
```

**Pattern 3: Type Conversion:**
```python
# Convert string to int/bool
debug = get_config("debug_mode") == "true"
timeout = int(get_config("http_timeout") or "30")
```

**Pattern 4: Environment-Specific:**
```python
# Different per environment
env = get_config("environment")  # dev, staging, prod
url = get_config(f"{env}_api_url")
```

### Configuration Best Practices

**1. Use Environment Variables for:**
- Runtime configuration
- Non-sensitive settings
- Deployment-specific values
- Feature flags

**2. Use Parameter Store for:**
- Secrets (tokens, keys)
- Shared configuration (multi-Lambda)
- Encrypted values
- Sensitive settings

**3. Use Defaults for:**
- Sensible fallbacks
- Optional settings
- Common values

### Change Impact Analysis

**If CONFIG Changes:**
```
Direct Impact:
- ALL interfaces (universal dependency)
- All configuration consumers

Risk Level: CRITICAL
- System-wide impact
- Config errors can crash Lambda
- Requires careful testing
```

**If CONFIG Dependencies Change:**
```
LOGGING changes:
- Update logging calls
- Low risk

CACHE changes:
- May affect config caching
- Medium risk (performance impact)
- First load becomes slower

SECURITY changes:
- May affect validation
- Medium risk
```

### Configuration Statistics

**Typical Config Usage:**
```
Per Lambda Invocation:
- Config gets: 3-8 calls
- Cache hits: 90-95%
- Parameter Store calls: 0-1 (only on cold start)
- Average lookup time: < 1ms (cached)

Memory Usage:
- Config values: 10-50KB
- Cache overhead: < 100KB
- Total: < 200KB
```

---

## Related Topics

- **DEP-03**: Layer 2 - Storage & Monitoring (CONFIG layer)
- **NM01-INT-05**: CONFIG interface definition
- **CACHE-DEP**: CACHE dependencies (used by CONFIG)
- **DEC-21**: SSM token-only decision

---

## Keywords

CONFIG dependencies, configuration management, Parameter Store, environment variables, config caching, config patterns

---

## Version History

- **2025-10-24**: Atomized from NM02-CORE, migrated to v3.1.0 format
- **2024-04-15**: Original content in NM02-CORE-Dependency Layers

---

**File:** `NM02-Dependencies-InterfaceDetail_CONFIG-DEP.md`
**Location:** `/nmap/NM02/`
**End of Document**

# NM01-Architecture-InterfacesCore_INT-05.md - INT-05

# INT-05: CONFIG Interface

**Category:** NM01 - Architecture  
**Topic:** Interfaces-Core  
**Priority:** 🟡 HIGH  
**Status:** Active  
**Created:** 2025-10-20  
**Last Updated:** 2025-10-24

---

## Summary

Multi-tier configuration management interface with preset support, environment variable loading, and hierarchical resolution (user > env > preset > default).

---

## Context

The CONFIG interface manages all system configuration through a multi-tier priority system, enabling flexible deployment modes (minimum, standard, maximum) and runtime configuration changes.

**Why it exists:** Lambda needs configuration that adapts to different environments and deployment modes without code changes. Multi-tier resolution enables this flexibility.

---

## Content

### Overview

```
Router: interface_config.py
Core: config_core.py
Purpose: Configuration management and parameter storage
Pattern: Dictionary-based dispatch
State: Multi-tier configuration dictionary
Dependency Layer: Layer 2 (Services)
```

### Operations (9 total)

```
├─ get_parameter: Get configuration value
├─ set_parameter: Set configuration value
├─ get_category: Get entire configuration category
├─ reload: Reload configuration from source
├─ switch_preset: Switch configuration preset
├─ get_state: Get current configuration state
├─ load_environment: Load from environment variables
├─ load_file: Load from configuration file
└─ validate_all: Validate all configuration
```

### Gateway Wrappers

```python
# Primary operations
get_config(key: str, default: Any = None) -> Any
set_config(key: str, value: Any) -> bool
get_config_category(category: str) -> Dict

# Management operations
reload_config() -> bool
switch_config_preset(preset: str) -> bool
get_config_state() -> Dict

# Loading operations
load_config_from_env() -> int  # Returns count loaded
load_config_from_file(filepath: str) -> int
validate_config() -> bool
```

### Multi-Tier Configuration

Configuration follows a priority order:

```
1. User Override (highest priority)
   - Set via set_config()
   - Runtime changes
   
2. Environment Variables
   - From AWS Lambda environment
   - Prefix: PARAMETER_PREFIX
   
3. Configuration Preset
   - minimum, standard, maximum
   - Deployment mode settings
   
4. Default Values (lowest priority)
   - Hardcoded fallbacks
```

### Configuration Presets

```python
CONFIG_PRESETS = {
    'minimum': {  # Free tier optimized
        'cache.default_ttl': 300,
        'cache.max_size': 100,
        'http.timeout': 5,
        'http.max_retries': 1
    },
    'standard': {  # Balanced
        'cache.default_ttl': 600,
        'cache.max_size': 1000,
        'http.timeout': 10,
        'http.max_retries': 3
    },
    'maximum': {  # Performance
        'cache.default_ttl': 1800,
        'cache.max_size': 10000,
        'http.timeout': 30,
        'http.max_retries': 5
    }
}
```

### Configuration Categories

```
cache.*         → Cache configuration
logging.*       → Logging configuration
http.*          → HTTP client configuration
security.*      → Security settings
metrics.*       → Metrics configuration
home_assistant.* → Home Assistant integration
```

### Usage Example

```python
from gateway import get_config, set_config, switch_config_preset, get_config_category

# Get single config
timeout = get_config('http.timeout', default=10)
cache_ttl = get_config('cache.default_ttl')

# Set runtime override
set_config('cache.default_ttl', 900)

# Get entire category
cache_config = get_config_category('cache')
# Returns: {'default_ttl': 900, 'max_size': 1000, ...}

# Switch preset (deployment mode)
switch_config_preset('maximum')  # Use maximum performance settings

# Load from environment
loaded = load_config_from_env()
log_info(f"Loaded {loaded} config values from environment")
```

---

## Related Topics

- **DEC-12**: Multi-tier configuration decision
- **INT-01**: CACHE - Uses config for default TTL, max size
- **INT-08**: HTTP_CLIENT - Uses config for timeout, retries
- **DEP-03**: Layer 2 (Services) - CONFIG dependency layer

---

## Keywords

configuration, config, settings, presets, environment variables, multi-tier, deployment modes

---

## Version History

- **2025-10-24**: Atomized from monolithic file (SIMA v3 migration)
- **2025-10-20**: Original content in IMPLEMENTATION_Core.md

---

**File:** `NM01-Architecture-InterfacesCore_INT-05.md`  
**End of Document**

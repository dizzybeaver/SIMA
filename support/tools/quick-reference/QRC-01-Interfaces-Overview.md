# File: QRC-01-Interfaces-Overview.md

**REF-ID:** QRC-01  
**Version:** 1.0.0  
**Category:** Support Tools  
**Type:** Quick Reference Card  
**Purpose:** One-page overview of all 12 interfaces

---

## 📋 12 INTERFACES AT A GLANCE

| Interface | Layer | Purpose | Common Functions | Use When |
|-----------|-------|---------|------------------|----------|
| **INT-01 CACHE** | L0 | Caching | get, set, clear, invalidate | Need temporary storage |
| **INT-02 LOGGING** | L0 | Logging | info, error, debug, warning | Need to log events |
| **INT-03 SECURITY** | L0 | Security | encrypt, decrypt, validate, hash | Need encryption/validation |
| **INT-04 CONFIG** | L0 | Configuration | get_config, set_config, reload | Need app configuration |
| **INT-05 SINGLETON** | L0 | Singletons | get_instance, reset | Need shared state |
| **INT-06 UTILITY** | L1 | Utilities | sanitize, transform, parse | Need data manipulation |
| **INT-07 METRICS** | L1 | Metrics | record, increment, gauge | Need performance tracking |
| **INT-08 WEBSOCKET** | L2 | Websockets | connect, send, receive, close | Need real-time comms |
| **INT-09 DATA** | L2 | Data Ops | transform, validate, format | Need data processing |
| **INT-10 BLE** | L3 | Bluetooth | scan, connect, read, write | Need BLE devices |
| **INT-11 API** | L3 | API Calls | request, post, get, put | Need HTTP requests |
| **INT-12 HA** | L4 | Home Assistant | get_state, call_service, subscribe | Need HA integration |

---

## 🎯 DEPENDENCY LAYERS

### Layer 0 (Foundation) - No Dependencies
```
INT-01 CACHE      ─┐
INT-02 LOGGING    ─┤ Can be used by any interface
INT-03 SECURITY   ─┤ No dependencies on other interfaces
INT-04 CONFIG     ─┤
INT-05 SINGLETON  ─┘
```

### Layer 1 - Depends on L0 Only
```
INT-06 UTILITY    ─┐ May use: CACHE, LOGGING, SECURITY
INT-07 METRICS    ─┘ May use: CONFIG, SINGLETON
```

### Layer 2 - Depends on L0, L1
```
INT-08 WEBSOCKET  ─┐ May use: L0 + UTILITY, METRICS
INT-09 DATA       ─┘
```

### Layer 3 - Depends on L0, L1, L2
```
INT-10 BLE        ─┐ May use: L0 + L1 + WEBSOCKET, DATA
INT-11 API        ─┘
```

### Layer 4 - Depends on L0-L3
```
INT-12 HA         ─── May use: All lower layers
```

**Rule:** Never import higher layers from lower layers

---

## 🔍 QUICK SELECTION GUIDE

### By Use Case

**Need to Store Data?**
- Temporary → INT-01 CACHE
- Persistent → INT-09 DATA
- Configuration → INT-04 CONFIG

**Need to Communicate?**
- Real-time → INT-08 WEBSOCKET
- HTTP API → INT-11 API
- Home Assistant → INT-12 HA

**Need Utilities?**
- Logging → INT-02 LOGGING
- Encryption → INT-03 SECURITY
- Data Transform → INT-06 UTILITY
- Metrics → INT-07 METRICS

**Need Device Control?**
- Bluetooth → INT-10 BLE
- Home Assistant → INT-12 HA

**Need Shared Instance?**
- INT-05 SINGLETON

---

## 📦 IMPORT PATTERNS

### Gateway Import (Correct)
```python
# ALWAYS import via gateway
from gateway import cache_get, cache_set
from gateway import logging_error, logging_info
from gateway import security_encrypt
```

### Direct Import (WRONG)
```python
# NEVER import directly
from cache_operations import get  # ❌ WRONG
from logging_core import _log_message  # ❌ WRONG
```

---

## 🎯 COMMON COMBINATIONS

### Cache + Logging
```python
from gateway import cache_get, logging_info

data = cache_get("key")
logging_info(f"Cache hit: {data is not None}")
```

### Security + Config
```python
from gateway import security_encrypt, config_get

secret_key = config_get("encryption_key")
encrypted = security_encrypt(data, secret_key)
```

### API + Cache + Logging
```python
from gateway import api_get, cache_get, cache_set, logging_error

# Try cache first
cached = cache_get("api_data")
if cached:
    return cached

# Fetch from API
try:
    data = api_get("https://example.com/api")
    cache_set("api_data", data, ttl=300)
    return data
except Exception as e:
    logging_error(f"API failure: {e}")
    return None
```

---

## ⚠️ INTERFACE RULES

### DO:
- ✅ Import via gateway
- ✅ Use appropriate layer dependencies
- ✅ Handle exceptions
- ✅ Validate inputs
- ✅ Log errors

### DON'T:
- ❌ Direct core/interface imports
- ❌ Import higher layers from lower
- ❌ Cross-interface imports
- ❌ Bare except clauses
- ❌ Skip validation

---

## 📊 INTERFACE REFERENCE TABLE

### L0 Interfaces (Foundation)

**INT-01 CACHE**
```python
from gateway import cache_get, cache_set, cache_clear
```
Functions: get, set, clear, invalidate, exists  
Use: Temporary data storage, API response caching

**INT-02 LOGGING**
```python
from gateway import logging_info, logging_error, logging_debug
```
Functions: info, error, debug, warning, critical  
Use: Application logging, error tracking

**INT-03 SECURITY**
```python
from gateway import security_encrypt, security_decrypt, security_hash
```
Functions: encrypt, decrypt, hash, validate, generate_token  
Use: Data encryption, password hashing, token management

**INT-04 CONFIG**
```python
from gateway import config_get, config_set, config_reload
```
Functions: get, set, reload, validate  
Use: Application configuration, environment variables

**INT-05 SINGLETON**
```python
from gateway import singleton_get, singleton_reset
```
Functions: get_instance, reset, exists  
Use: Shared state, connection pools

---

### L1 Interfaces (Utilities)

**INT-06 UTILITY**
```python
from gateway import utility_sanitize, utility_transform
```
Functions: sanitize, transform, parse, format, validate  
Use: Data manipulation, string processing

**INT-07 METRICS**
```python
from gateway import metrics_record, metrics_increment
```
Functions: record, increment, gauge, histogram  
Use: Performance monitoring, business metrics

---

### L2 Interfaces (Communication)

**INT-08 WEBSOCKET**
```python
from gateway import websocket_connect, websocket_send
```
Functions: connect, send, receive, close, subscribe  
Use: Real-time bidirectional communication

**INT-09 DATA**
```python
from gateway import data_transform, data_validate
```
Functions: transform, validate, format, serialize  
Use: Complex data processing, ETL operations

---

### L3 Interfaces (External Services)

**INT-10 BLE**
```python
from gateway import ble_scan, ble_connect, ble_read
```
Functions: scan, connect, read, write, disconnect  
Use: Bluetooth device communication

**INT-11 API**
```python
from gateway import api_get, api_post, api_request
```
Functions: get, post, put, delete, request  
Use: HTTP API calls, REST operations

---

### L4 Interfaces (High-Level Integration)

**INT-12 HA**
```python
from gateway import ha_get_state, ha_call_service
```
Functions: get_state, call_service, subscribe, get_entities  
Use: Home Assistant integration, smart home control

---

## 🎓 USAGE EXAMPLES

### Example 1: API with Caching
```python
from gateway import api_get, cache_get, cache_set, logging_info

def fetch_weather(city: str) -> dict:
    cache_key = f"weather:{city}"
    
    # Check cache
    cached = cache_get(cache_key)
    if cached:
        logging_info(f"Cache hit for {city}")
        return cached
    
    # Fetch from API
    data = api_get(f"https://api.weather.com/{city}")
    
    # Cache result
    cache_set(cache_key, data, ttl=1800)  # 30 minutes
    logging_info(f"Cached weather for {city}")
    
    return data
```

### Example 2: Secure Configuration
```python
from gateway import config_get, security_decrypt, logging_error

def get_api_key() -> str:
    encrypted_key = config_get("api_key_encrypted")
    
    if not encrypted_key:
        logging_error("API key not found in config")
        raise ValueError("Missing API key")
    
    try:
        return security_decrypt(encrypted_key)
    except Exception as e:
        logging_error(f"Decryption failed: {e}")
        raise
```

### Example 3: Home Assistant with Logging
```python
from gateway import ha_get_state, ha_call_service, logging_info, logging_error

def toggle_light(entity_id: str) -> bool:
    try:
        # Get current state
        state = ha_get_state(entity_id)
        logging_info(f"Current state: {state}")
        
        # Toggle
        service = "turn_off" if state == "on" else "turn_on"
        ha_call_service("light", service, {"entity_id": entity_id})
        
        logging_info(f"Toggled {entity_id}")
        return True
        
    except Exception as e:
        logging_error(f"Failed to toggle {entity_id}: {e}")
        return False
```

---

## 🔗 RELATED RESOURCES

**Detailed Documentation:**
- INT-01 through INT-12: Full interface catalogs
- Interface Quick Index: Problem-based navigation
- Interface Cross-Reference Matrix: Dependencies

**Implementation Guides:**
- NMP01-LEE-02: CACHE usage in LEE
- NMP01-LEE-03: LOGGING usage in LEE
- NMP01-LEE-04: SECURITY usage in LEE
- NMP01-LEE-17: HA integration patterns

**Workflows:**
- WF-01: Add Feature (using interfaces)
- WF-03: Update Interface

**Architecture:**
- ARCH-01: SUGA Pattern (interface layer role)
- ARCH-02: LMMS (dependency layers)

---

**END OF QRC-01**

**Related cards:** QRC-02 (Gateway Patterns), QRC-03 (Common Patterns)

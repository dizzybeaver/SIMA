# INT-04-HTTP-Interface.md

**Version:** 1.0.0  
**Date:** 2025-11-07  
**Purpose:** SUGA HTTP client interface pattern  
**Architecture:** SUGA (Gateway Pattern)

---

## INTERFACE OVERVIEW

**Interface:** INT-04 HTTP  
**Category:** Communication  
**Layer Position:** Interface Layer  
**Gateway Required:** Yes

### Purpose

HTTP client operations through SUGA three-layer pattern.

---

## THREE-LAYER IMPLEMENTATION

### Layer 1: Gateway Entry (gateway_wrappers_http_client.py)

```python
def http_get(url, headers=None, timeout=30):
    """HTTP GET via SUGA gateway."""
    import interface_http
    return interface_http.get(url, headers, timeout)

def http_post(url, data=None, headers=None, timeout=30):
    """HTTP POST via SUGA gateway."""
    import interface_http
    return interface_http.post(url, data, headers, timeout)

def http_put(url, data=None, headers=None, timeout=30):
    """HTTP PUT via SUGA gateway."""
    import interface_http
    return interface_http.put(url, data, headers, timeout)

def http_delete(url, headers=None, timeout=30):
    """HTTP DELETE via SUGA gateway."""
    import interface_http
    return interface_http.delete(url, headers, timeout)
```

### Layer 2: Interface Routing (interface_http.py)

```python
def get(url, headers=None, timeout=30):
    """Route GET to core."""
    import http_client_core
    return http_client_core.get_impl(url, headers, timeout)

def post(url, data=None, headers=None, timeout=30):
    """Route POST to core."""
    import http_client_core
    return http_client_core.post_impl(url, data, headers, timeout)

def put(url, data=None, headers=None, timeout=30):
    """Route PUT to core."""
    import http_client_core
    return http_client_core.put_impl(url, data, headers, timeout)

def delete(url, headers=None, timeout=30):
    """Route DELETE to core."""
    import http_client_core
    return http_client_core.delete_impl(url, headers, timeout)
```

### Layer 3: Core Implementation (http_client_core.py)

```python
import urllib.request
import json

def get_impl(url, headers=None, timeout=30):
    """Core HTTP GET implementation."""
    req = urllib.request.Request(url, headers=headers or {})
    
    try:
        with urllib.request.urlopen(req, timeout=timeout) as response:
            return {
                'status': response.status,
                'data': response.read().decode('utf-8'),
                'headers': dict(response.headers)
            }
    except Exception as e:
        return {
            'status': 0,
            'error': str(e)
        }

def post_impl(url, data=None, headers=None, timeout=30):
    """Core HTTP POST implementation."""
    headers = headers or {}
    headers['Content-Type'] = 'application/json'
    
    data_bytes = json.dumps(data).encode('utf-8') if data else None
    req = urllib.request.Request(url, data=data_bytes, headers=headers, method='POST')
    
    try:
        with urllib.request.urlopen(req, timeout=timeout) as response:
            return {
                'status': response.status,
                'data': response.read().decode('utf-8'),
                'headers': dict(response.headers)
            }
    except Exception as e:
        return {
            'status': 0,
            'error': str(e)
        }

def put_impl(url, data=None, headers=None, timeout=30):
    """Core HTTP PUT implementation."""
    headers = headers or {}
    headers['Content-Type'] = 'application/json'
    
    data_bytes = json.dumps(data).encode('utf-8') if data else None
    req = urllib.request.Request(url, data=data_bytes, headers=headers, method='PUT')
    
    try:
        with urllib.request.urlopen(req, timeout=timeout) as response:
            return {
                'status': response.status,
                'data': response.read().decode('utf-8')
            }
    except Exception as e:
        return {
            'status': 0,
            'error': str(e)
        }

def delete_impl(url, headers=None, timeout=30):
    """Core HTTP DELETE implementation."""
    req = urllib.request.Request(url, headers=headers or {}, method='DELETE')
    
    try:
        with urllib.request.urlopen(req, timeout=timeout) as response:
            return {
                'status': response.status
            }
    except Exception as e:
        return {
            'status': 0,
            'error': str(e)
        }
```

---

## USAGE PATTERNS

```python
# CORRECT
import gateway

# GET request
response = gateway.http_get('https://api.example.com/data')

# POST request
response = gateway.http_post(
    'https://api.example.com/users',
    data={'name': 'John'},
    timeout=10
)
```

---

## ANTI-PATTERNS

```python
# WRONG - Direct import
from http_client_core import get_impl

# CORRECT
import gateway
gateway.http_get(url)
```

---

## CROSS-REFERENCES

**Architecture:**
- ARCH-01: Gateway Trinity

**Lessons:**
- LESS-08: Test Failure Paths

---

## KEYWORDS

HTTP, REST, client, requests, gateway, SUGA

---

**END OF FILE**

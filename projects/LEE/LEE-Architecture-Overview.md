# LEE-Architecture-Overview.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** LEE project architecture overview  
**Category:** Project - LEE  
**Type:** Architecture

---

## OVERVIEW

LEE (Lambda Execution Engine) is a Home Assistant integration running on AWS Lambda. It provides Alexa and Google Assistant voice control for home automation through a serverless architecture.

**Directory:** `/sima/projects/lee/`

---

## ARCHITECTURE SUMMARY

### High-Level Components

```
Voice Assistant (Alexa/Google) 
    ↓ HTTPS
API Gateway
    ↓ Lambda Proxy Integration
LEE Lambda Function
    ↓ WebSocket
Home Assistant Instance
    ↓ Local Network
Smart Home Devices
```

**Key Technologies:**
- **Platform:** AWS Lambda (Python 3.12)
- **Gateway:** AWS API Gateway (REST API)
- **Target:** Home Assistant (WebSocket API)
- **Architectures:** SUGA + LMMS + ZAPH + DD-1 + DD-2 + CR-1

---

## PYTHON ARCHITECTURES USED

### SUGA: Single Universal Gateway Architecture

**Purpose:** Three-layer modular pattern  
**Implementation:** Gateway → Interface → Core  

**Benefits:**
- Prevents circular imports
- Clear separation of concerns
- Easy to test and maintain

**LEE Usage:**
```python
# All cross-interface communication via gateway
import gateway

# Cache operations
result = gateway.cache_get(key)

# Logging
gateway.log_info("Processing request")

# Home Assistant API
response = gateway.ha_device_get(device_id)
```

### LMMS: Lazy Module Management System

**Purpose:** Minimize cold start time  
**Implementation:** Function-level imports for rarely-used modules  

**LEE Usage:**
```python
# Fast path (always used) - module-level
import json
import boto3

# Cold path (rarely used) - lazy loaded
def handle_debug_request(event):
    # Only import when debug requested
    import debug_diagnostics
    return debug_diagnostics.run_diagnostics()
```

**Impact:**
- Cold start: 680ms (vs 2,300ms with eager imports)
- Warm start: 45ms

### ZAPH: Zero-Abstraction Path for Hot Operations

**Purpose:** Optimize frequently-accessed code paths  
**Implementation:** Tiered access system  

**LEE Tiers:**
- **Tier 1 (Hot):** Device state queries, Alexa responses (90% of traffic)
- **Tier 2 (Warm):** Device control, scene activation (8% of traffic)
- **Tier 3 (Cold):** Diagnostics, configuration updates (2% of traffic)

**Benefit:** < 50ms response time for 90% of requests

### DD-1: Dictionary Dispatch (Performance)

**Purpose:** Fast function routing in interfaces  
**Implementation:** O(1) lookup for action handlers  

**LEE Usage:**
```python
# interface_devices.py
DEVICE_OPERATIONS = {
    'get': get_device_impl,
    'list': list_devices_impl,
    'control': control_device_impl,
    'query_state': query_state_impl,
}

def execute_devices_operation(operation, **kwargs):
    handler = DEVICE_OPERATIONS.get(operation)
    if handler:
        return handler(**kwargs)
    raise ValueError(f"Unknown operation: {operation}")
```

**Impact:** O(1) vs O(n) for if-else chains

### DD-2: Dependency Disciplines (Architecture)

**Purpose:** Manage layer dependencies  
**Implementation:** Higher → Lower flow only  

**LEE Layers:**
```
Router Layer (lambda_function.py)
    ↓
Gateway Layer (gateway.py, gateway_wrappers.py)
    ↓
Interface Layer (interface_*.py)
    ↓
Core Layer (*_core.py)
    ↓
HA Integration Layer (home_assistant/*.py)
```

**Enforcement:** No upward dependencies, prevents circular imports

### CR-1: Cache Registry (Consolidation)

**Purpose:** Central function registry with consolidated exports  
**Implementation:** Single gateway.py with all 100+ functions  

**LEE Usage:**
```python
# gateway.py consolidates all exports
import gateway

# All functions accessible from single import
gateway.cache_get()
gateway.log_info()
gateway.ha_device_get()
gateway.alexa_response()
# ... 100+ more functions
```

**Benefits:**
- Single import point
- Easy function discovery
- Fast path caching
- 90x improvement in function lookup

---

## CORE COMPONENTS

### 1. Lambda Handler (lambda_function.py)

**Purpose:** Entry point, routes requests  

**Responsibilities:**
- Parse API Gateway event
- Route to appropriate handler (Alexa, Google Assistant, diagnostics)
- Error handling and logging
- Response formatting

**Pattern:**
```python
def lambda_handler(event, context):
    # Parse request type
    request_type = determine_request_type(event)
    
    # Route to handler
    if request_type == 'alexa':
        return handle_alexa(event)
    elif request_type == 'google':
        return handle_google_assistant(event)
    elif request_type == 'diagnostic':
        return handle_diagnostic(event)
```

### 2. Gateway Layer (gateway.py, gateway_wrappers.py)

**Purpose:** Central registry, unified API  

**Components:**
- **gateway.py:** Central exports (100+ functions)
- **gateway_core.py:** Core execution logic
- **gateway_wrappers_*.py:** Wrapper functions per interface

**Pattern:**
```python
# gateway_wrappers_cache.py
def cache_get(key: str):
    import interface_cache
    return interface_cache.execute_cache_operation('get', key=key)

# gateway.py
from gateway_wrappers_cache import cache_get
__all__ = ['cache_get', ...]  # 100+ functions
```

### 3. Interface Layer (interface_*.py)

**Purpose:** Route operations to core implementations  

**12 Interfaces:**
1. **INT-01: Cache** - Caching operations
2. **INT-02: Logging** - Logging operations
3. **INT-03: Security** - Token validation, encryption
4. **INT-04: HTTP** - HTTP client operations
5. **INT-05: Initialization** - Startup logic
6. **INT-06: Config** - Configuration management
7. **INT-07: Metrics** - Performance tracking
8. **INT-08: Debug** - Diagnostics
9. **INT-09: Singleton** - Shared state management
10. **INT-10: Utility** - Data validation, transformation
11. **INT-11: WebSocket** - WebSocket connections
12. **INT-12: Circuit Breaker** - Resilience patterns

**Pattern (DD-1 Dictionary Dispatch):**
```python
# interface_cache.py
CACHE_OPERATIONS = {
    'get': get_impl,
    'set': set_impl,
    'delete': delete_impl,
    'clear': clear_impl,
}

def execute_cache_operation(operation, **kwargs):
    handler = CACHE_OPERATIONS[operation]
    return handler(**kwargs)
```

### 4. Core Layer (*_core.py)

**Purpose:** Implementation logic  

**Examples:**
- **cache_core.py:** In-memory caching
- **logging_core.py:** Structured logging
- **http_client_core.py:** HTTP client with retry
- **websocket_core.py:** WebSocket management

### 5. Home Assistant Integration (home_assistant/*.py)

**Purpose:** Interact with Home Assistant API  

**Key Files:**
- **ha_websocket.py:** WebSocket connection management
- **ha_devices_core.py:** Device operations
- **ha_alexa_core.py:** Alexa skill integration
- **ha_assist_core.py:** Google Assistant integration
- **ha_config.py:** HA connection configuration

---

## REQUEST FLOW

### Alexa Request Example

```
1. User: "Alexa, turn on living room lights"
   ↓
2. Alexa Service → API Gateway
   ↓
3. API Gateway → lambda_function.py
   - Parse Alexa request
   - Extract intent: "TurnOn"
   - Extract device: "living room lights"
   ↓
4. lambda_function.py → handle_alexa()
   ↓
5. gateway.ha_device_control()
   ↓
6. interface_devices.py → execute_devices_operation('control')
   ↓
7. ha_devices_core.py → control_device_impl()
   ↓
8. ha_websocket.py → WebSocket call to Home Assistant
   ↓
9. Home Assistant → Smart Light Device
   ↓
10. Response chain back up
   ↓
11. Alexa response: "Living room lights turned on"
```

**Timing:**
- Cold start: 680ms total
- Warm start: 45ms total
- Home Assistant call: 25ms
- Total user-perceived: 70ms (warm)

---

## DATA FLOW

### Device State Query

```
Request → Cache Check
             ↓ Hit
         Return cached state
             ↓ Miss
         WebSocket → Home Assistant
             ↓
         Update cache
             ↓
         Return state
```

**Cache Strategy:**
- TTL: 30 seconds for device states
- Invalidation: On device control commands
- Hit rate: ~75% (excellent performance)

### Token Management

```
Startup → SSM Parameter Store
            ↓
      Fetch HA token
            ↓
      Cache in memory
            ↓
      Use for WebSocket auth
            ↓
      Token expires (30 days)
            ↓
      Refresh from SSM
```

**Token Storage:** AWS SSM Parameter Store (SecureString)

---

## OPTIMIZATION STRATEGIES

### 1. Lazy Loading (LMMS)

**Heavy Imports Lazy Loaded:**
```python
# Diagnostics (2% of traffic)
# debug_diagnostics.py imported only when needed

# Performance testing (0.1% of traffic)
# performance_benchmark.py imported only when needed
```

**Impact:**
- Cold start reduced by 71%
- Warm start unaffected

### 2. Caching

**Multi-Level Caching:**
```
1. In-Memory Cache (Python dict)
   - Device states: 30s TTL
   - HA capabilities: 1 hour TTL
   
2. Lambda Execution Context
   - WebSocket connection persists
   - Token cached
   - Configuration cached
```

**Cache Hit Rates:**
- Device states: 75%
- Capabilities: 95%
- Configuration: 99%

### 3. Connection Pooling

**WebSocket Connection Reuse:**
```python
# Module-level (persists in warm starts)
_websocket_connection = None

def get_connection():
    global _websocket_connection
    
    if _websocket_connection is None or not _websocket_connection.is_connected():
        _websocket_connection = create_connection()
    
    return _websocket_connection
```

**Benefit:** Eliminates connection overhead on warm starts

### 4. Fast Path Optimization (ZAPH)

**Tier 1 Operations:**
- Direct dictionary dispatch (DD-1)
- Minimal validation
- Cached responses when possible

**Example:**
```python
# 90% of traffic: Simple device queries
# Optimized to < 50ms response time
def query_device_state(device_id):
    # Check cache first (fast)
    cached = cache_get(f"device:{device_id}")
    if cached and not expired(cached):
        return cached
    
    # Fetch from HA (slower)
    state = ha_get_device_state(device_id)
    cache_set(f"device:{device_id}", state, ttl=30)
    return state
```

---

## CONFIGURATION

### Environment Variables

```python
# Lambda environment variables
HA_URL = os.environ['HA_URL']              # Home Assistant URL
HA_TOKEN_SSM = os.environ['HA_TOKEN_SSM']  # SSM parameter name
LOG_LEVEL = os.environ.get('LOG_LEVEL', 'INFO')
CACHE_TTL = int(os.environ.get('CACHE_TTL', '30'))
```

### SSM Parameters

```
/lee/ha/token        - Home Assistant access token (SecureString)
/lee/ha/url          - Home Assistant URL
/lee/config/devices  - Device configuration JSON
```

### Knowledge Configuration

**File:** `/sima/projects/lee/config/knowledge-config.yaml`

```yaml
project:
  name: LEE
  description: Lambda Execution Engine for Home Assistant
  version: 2.0.0

knowledge:
  generic:
    enabled: true
  
  languages:
    python:
      enabled: true
      architectures:
        suga: true
        lmms: true
        zaph: true
        dd-1: true
        dd-2: true
        cr-1: true
  
  platforms:
    aws-lambda:
      enabled: true
```

---

## DEPLOYMENT

### Lambda Configuration

```
Runtime: Python 3.12
Memory: 1769 MB (1 full vCPU)
Timeout: 30 seconds
Handler: lambda_function.lambda_handler
```

### API Gateway

```
Type: REST API
Authorization: IAM
Throttling: 1000 requests/second
```

### VPC Configuration

**Not in VPC:**
- Home Assistant accessed via public URL
- Uses HTTPS for security
- No need for VPC

---

## MONITORING

### CloudWatch Metrics

**Custom Metrics:**
```python
# Track in code
cloudwatch.put_metric_data(
    Namespace='LEE/Performance',
    MetricData=[
        {'MetricName': 'CacheHitRate', 'Value': hit_rate},
        {'MetricName': 'HAResponseTime', 'Value': response_ms},
        {'MetricName': 'ColdStartTime', 'Value': cold_start_ms},
    ]
)
```

**Key Metrics:**
- Invocations per minute
- Duration (P50, P90, P99)
- Errors per minute
- Cold start frequency
- Cache hit rate
- Home Assistant response time

### Alarms

```
- Duration P99 > 1000ms (2 periods)
- Error rate > 1% (5 minutes)
- Cold start rate > 20% (10 minutes)
- HA response time > 500ms (2 periods)
```

---

## SECURITY

### IAM Permissions

**Lambda Execution Role:**
```
- logs:CreateLogGroup
- logs:CreateLogStream  
- logs:PutLogEvents
- ssm:GetParameter (for HA token)
- cloudwatch:PutMetricData
```

### Secrets Management

**Home Assistant Token:**
- Stored in SSM Parameter Store (SecureString)
- KMS encrypted
- Retrieved once per Lambda cold start
- Cached in execution context
- Auto-refreshes on expiration

### API Security

**API Gateway:**
- IAM authorization required
- Throttling enabled (1000 req/sec)
- CloudWatch logging enabled
- AWS WAF (optional)

---

## KEY TAKEAWAYS

**Architecture:**
- Serverless (AWS Lambda)
- Event-driven (API Gateway triggers)
- Home Assistant integration via WebSocket
- Uses 6 Python architecture patterns

**Performance:**
- Cold start: 680ms
- Warm start: 45ms
- P99 latency: < 200ms
- Cache hit rate: 75%

**Optimization:**
- Lazy loading (LMMS)
- Multi-level caching
- Connection pooling
- Fast path (ZAPH)
- Dictionary dispatch (DD-1)

**Reliability:**
- Circuit breaker pattern
- Automatic retries
- Graceful degradation
- Comprehensive logging

---

## RELATED

**Platform:**
- AWS Lambda platform knowledge: /sima/platforms/aws/lambda/

**Architectures:**
- SUGA: /sima/languages/python/architectures/suga/
- LMMS: /sima/languages/python/architectures/lmms/
- ZAPH: /sima/languages/python/architectures/zaph/
- DD-1: /sima/languages/python/architectures/dd-1/
- DD-2: /sima/languages/python/architectures/dd-2/
- CR-1: /sima/languages/python/architectures/cr-1/

**Project Files:**
- Configuration: /sima/projects/lee/config/knowledge-config.yaml
- Interfaces: /sima/projects/lee/interfaces/
- Lessons: /sima/projects/lee/lessons/
- Decisions: /sima/projects/lee/decisions/

---

**END OF FILE**

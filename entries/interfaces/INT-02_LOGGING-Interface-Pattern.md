# INT-02: LOGGING Interface Pattern
# File: INT-02_LOGGING-Interface-Pattern.md

**REF-ID:** INT-02  
**Version:** 1.0.0  
**Category:** Interface Pattern  
**Status:** Active  
**Created:** 2025-10-29  
**Last Updated:** 2025-10-29

---

## 📋 OVERVIEW

**Interface Name:** LOGGING  
**Short Code:** LOG  
**Type:** Base Infrastructure Interface  
**Priority:** 🔴 CRITICAL  
**Dependency Layer:** Layer 0 (No Dependencies)

**One-Line Description:**  
Centralized logging interface providing structured output with multiple severity levels, forming the foundation layer with zero dependencies.

**Primary Purpose:**  
LOGGING provides system-wide observability through structured logs, enabling debugging, monitoring, and audit trails while serving as the dependency-free foundation (Layer 0) that all other interfaces build upon.

---

## 🎯 APPLICABILITY

### When to Use
✅ Use LOGGING interface when:
- Need observability into system behavior
- Debugging production issues
- Creating audit trails
- Tracking operations and errors
- Monitoring system health
- Recording metrics and events
- Providing visibility to operations teams
- ANY interface operation (foundational requirement)

### When NOT to Use
❌ Do NOT use LOGGING interface when:
- Performance-critical tight loop (< 1ms tolerances)
- Logging sensitive data without sanitization
- Recursively logging log operations (infinite loop risk)

### Best For
- **Environment:** Any environment (development, staging, production)
- **Use Case:** Debugging, monitoring, audit trails, observability
- **Dependency Layer:** Layer 0 - Foundation for all other interfaces

---

## 🗺️ STRUCTURE

### Core Components

**Component 1: Router Layer (interface_logging.py)**
- **Purpose:** Operation dispatch
- **Responsibilities:** 
  - Route log level operations to implementations
  - Dispatch dictionary for O(1) routing
  - Operation-level validation
- **Dependencies:** Imports logging_core
- **Interface:** execute_logging_operation(operation, params)

**Component 2: Core Layer (logging_core.py)**
- **Purpose:** Log output implementation
- **Responsibilities:**
  - Format log messages (structured JSON)
  - Output to stdout/stderr
  - Handle log levels (DEBUG, INFO, WARNING, ERROR, CRITICAL)
  - Add timestamps and metadata
  - NO DEPENDENCIES (Layer 0 requirement)
- **Dependencies:** None (pure Python stdlib)
- **State:** Minimal (log level configuration)

**Component 3: Gateway Wrappers (gateway_wrappers.py)**
- **Purpose:** Convenience functions
- **Responsibilities:**
  - Provide log_info(), log_error(), log_debug(), etc.
  - Clean, intuitive API
  - Parameter transformation
- **Dependencies:** Calls gateway_core.execute_operation
- **Interface:** log_info(msg), log_error(msg), etc.

---

## 📐 KEY RULES

### Rule 1: Zero Dependencies (Layer 0 Requirement)
**What:** LOGGING interface must not import from any other interface.

**Why:** LOGGING is Layer 0 (foundation). All other interfaces depend on LOGGING. If LOGGING depended on anything else, circular imports would occur.

**How:**
```python
# ❌ WRONG - Creates circular dependency
# In logging_core.py
from cache_core import cache_get  # NEVER!

# ✅ CORRECT - No interface imports
import sys
import json
from datetime import datetime
# Only stdlib, no custom interfaces
```

**Consequence:** LOGGING must be self-contained. Cannot use CACHE, METRICS, or any other interface.

---

### Rule 2: Structured Output (JSON Format)
**What:** Logs output as structured JSON for easy parsing.

**Why:** Structured logs enable:
- Easy parsing by log aggregation tools
- Filtering and searching
- Automated alerting
- Metric extraction

**How:**
```python
def log_info(message: str, **kwargs):
    """Log info message as JSON."""
    log_entry = {
        'timestamp': datetime.utcnow().isoformat(),
        'level': 'INFO',
        'message': message,
        **kwargs  # Additional context
    }
    print(json.dumps(log_entry))
```

**Output:**
```json
{"timestamp": "2025-10-29T14:30:00.123Z", "level": "INFO", "message": "Cache hit", "key": "user_123"}
```

**Consequence:** Logs are machine-readable and human-readable.

---

### Rule 3: Multiple Severity Levels
**What:** Support DEBUG, INFO, WARNING, ERROR, CRITICAL levels.

**Why:** Different severity for different situations:
- DEBUG: Detailed information for debugging
- INFO: Normal operations
- WARNING: Potential issues
- ERROR: Errors that don't crash system
- CRITICAL: Severe errors requiring immediate attention

**How:**
```python
log_debug("Cache lookup for key: user_123")
log_info("User authenticated successfully")
log_warning("API rate limit approaching")
log_error("Failed to connect to database")
log_critical("Out of memory, shutting down")
```

**Consequence:** Proper severity enables filtering and alerting.

---

### Rule 4: Operation Dispatch Dictionary
**What:** Router uses dictionary for O(1) dispatch.

**Why:** Same as all interfaces - O(1) lookup, self-documenting, easy to extend.

**How:**
```python
_OPERATION_DISPATCH = {
    'debug': _execute_debug,
    'info': _execute_info,
    'warning': _execute_warning,
    'error': _execute_error,
    'critical': _execute_critical,
}
```

**Consequence:** Adding log levels is simple. All operations O(1) dispatch.

---

## 🎁 MAJOR BENEFITS

### Benefit 1: Universal Observability
**What:** Every interface operation can be logged, providing complete visibility.

**Measurement:**
- Interfaces using LOGGING: 100% (all 12 interfaces)
- Operations logged per invocation: 10-50
- **Observability: Complete**

**Impact:**
- Debugging: Can trace any operation
- Monitoring: Can detect anomalies
- Audit: Can prove operations occurred
- Performance: Can identify bottlenecks

**Why It Matters:**
Without logging, debugging production issues is nearly impossible. LOGGING provides the visibility needed to understand system behavior.

---

### Benefit 2: Layer 0 Foundation
**What:** Zero dependencies means LOGGING always works, even when everything else fails.

**Measurement:**
- LOGGING dependencies: 0
- Other interface dependencies on LOGGING: 11 of 11
- **Reliability: Maximum**

**Impact:**
- LOGGING initializes first (cold start)
- LOGGING works even if cache, database, network fail
- Can log errors in other interfaces
- Foundation for debugging other interface failures

**Why It Matters:**
When things go wrong, you need logging to work. Layer 0 status ensures it always does.

---

### Benefit 3: Structured Format
**What:** JSON output enables automated parsing and analysis.

**Measurement:**
- Human-readable: Yes
- Machine-parseable: Yes
- Integration with log aggregators: Trivial
- **Format flexibility: High**

**Impact:**
- CloudWatch Logs Insights: Query logs with SQL-like syntax
- Elasticsearch: Index and search logs
- Datadog/Splunk: Automated dashboards
- Alerting: Automated alerts on specific log patterns

**Why It Matters:**
Structured logs unlock automated monitoring and alerting. Critical for production systems.

---

### Benefit 4: Minimal Overhead
**What:** Logging operation completes in < 0.5ms.

**Measurement:**
- Log output time: 0.1-0.5ms
- Impact on total request: < 1%
- Memory overhead: < 100KB
- **Performance impact: Negligible**

**Impact:**
- Can log liberally without performance concerns
- Debug logging in production feasible
- No need to remove logs for performance

**Why It Matters:**
Low overhead means logging can be comprehensive without sacrificing performance.

---

## ⚠️ COMMON PITFALLS

### Pitfall 1: Logging Sensitive Data
**Problem:** Logging passwords, tokens, or PII without sanitization.

```python
# ❌ WRONG - Sensitive data exposed
log_info(f"User login attempt: {username}, password: {password}")

# ✅ CORRECT - Sanitize sensitive data
log_info(f"User login attempt: {username}, password: [REDACTED]")

# ✅ EVEN BETTER - Use security interface
from gateway import sanitize_for_log
safe_data = sanitize_for_log({'user': username, 'password': password})
log_info(f"User login attempt", **safe_data)
```

**Solution:** Always sanitize sensitive data before logging. Use security interface if available.

---

### Pitfall 2: Logging Inside LOGGING
**Problem:** Creating recursive logging loops.

```python
# ❌ WRONG - Infinite recursion
def log_info(message):
    log_debug(f"About to log: {message}")  # Logs before logging!
    print(f"INFO: {message}")

# ✅ CORRECT - No logging in logging implementation
def log_info(message):
    # Direct output, no recursive calls
    print(f"INFO: {message}")
```

**Solution:** LOGGING implementation must not call logging functions.

---

### Pitfall 3: Excessive Debug Logging in Production
**Problem:** Debug logs flood production logs, obscuring important information.

```python
# ❌ WRONG - Always debug logging
for item in large_list:
    log_debug(f"Processing item: {item}")  # 10,000 debug logs!

# ✅ CORRECT - Conditional debug logging
if LOG_LEVEL == DEBUG:
    for item in large_list:
        log_debug(f"Processing item: {item}")

# ✅ BETTER - Use appropriate log level
log_info(f"Processing {len(large_list)} items")  # One log
```

**Solution:** Use appropriate log levels. Reserve DEBUG for development/troubleshooting.

---

### Pitfall 4: Missing Context
**Problem:** Logs don't include enough context to debug issues.

```python
# ❌ WRONG - No context
log_error("Operation failed")

# ✅ CORRECT - Include context
log_error("Cache set failed", key=key, error=str(e), ttl=ttl)

# ✅ EVEN BETTER - Structured context
log_error("Operation failed", 
    operation="cache_set",
    key=key,
    ttl=ttl,
    error_type=type(e).__name__,
    error_message=str(e),
    stack_trace=traceback.format_exc()
)
```

**Solution:** Include relevant context in every log message. Structured format makes this easy.

---

## 📦 IMPLEMENTATION PATTERNS

### Pattern 1: Basic Logging Interface

**Router (interface_logging.py):**
```python
"""
Logging Interface - Routes logging operations to implementations.
"""

def execute_logging_operation(operation: str, params: dict):
    """
    Execute logging operation.
    
    Args:
        operation: Log level ('debug', 'info', 'warning', 'error', 'critical')
        params: Parameters including 'message' and optional context
        
    Returns:
        None (logs to output)
        
    Raises:
        ValueError: Unknown log level
    """
    _OPERATION_DISPATCH = {
        'debug': _execute_debug,
        'info': _execute_info,
        'warning': _execute_warning,
        'error': _execute_error,
        'critical': _execute_critical,
    }
    
    handler = _OPERATION_DISPATCH.get(operation)
    if not handler:
        raise ValueError(f"Unknown log level: {operation}")
    
    return handler(**params)

def _execute_debug(message: str, **context):
    """Execute debug log."""
    import logging_core
    return logging_core.log_debug(message, **context)

def _execute_info(message: str, **context):
    """Execute info log."""
    import logging_core
    return logging_core.log_info(message, **context)

def _execute_warning(message: str, **context):
    """Execute warning log."""
    import logging_core
    return logging_core.log_warning(message, **context)

def _execute_error(message: str, **context):
    """Execute error log."""
    import logging_core
    return logging_core.log_error(message, **context)

def _execute_critical(message: str, **context):
    """Execute critical log."""
    import logging_core
    return logging_core.log_critical(message, **context)
```

**Core (logging_core.py):**
```python
"""
Logging Core - Structured logging with severity levels.

NO DEPENDENCIES - This is Layer 0.
"""
import sys
import json
from datetime import datetime
from typing import Any, Dict

# Log level constants
DEBUG = 10
INFO = 20
WARNING = 30
ERROR = 40
CRITICAL = 50

# Current log level (can be configured)
_LOG_LEVEL = INFO

def _log_message(level: str, level_num: int, message: str, **context):
    """
    Internal log function - outputs structured JSON.
    
    Args:
        level: Log level string ('DEBUG', 'INFO', etc.)
        level_num: Numeric level for filtering
        message: Log message
        **context: Additional context fields
    """
    # Skip if below current log level
    if level_num < _LOG_LEVEL:
        return
    
    # Build log entry
    log_entry: Dict[str, Any] = {
        'timestamp': datetime.utcnow().isoformat() + 'Z',
        'level': level,
        'message': message,
    }
    
    # Add context fields
    if context:
        log_entry.update(context)
    
    # Output as JSON
    output = json.dumps(log_entry)
    
    # Use stderr for WARNING and above, stdout for others
    stream = sys.stderr if level_num >= WARNING else sys.stdout
    print(output, file=stream, flush=True)

def log_debug(message: str, **context):
    """Log debug message (detailed information for debugging)."""
    _log_message('DEBUG', DEBUG, message, **context)

def log_info(message: str, **context):
    """Log info message (normal operations)."""
    _log_message('INFO', INFO, message, **context)

def log_warning(message: str, **context):
    """Log warning message (potential issues)."""
    _log_message('WARNING', WARNING, message, **context)

def log_error(message: str, **context):
    """Log error message (errors that don't crash system)."""
    _log_message('ERROR', ERROR, message, **context)

def log_critical(message: str, **context):
    """Log critical message (severe errors)."""
    _log_message('CRITICAL', CRITICAL, message, **context)

def set_log_level(level: int):
    """Set minimum log level."""
    global _LOG_LEVEL
    _LOG_LEVEL = level

def get_log_level() -> int:
    """Get current log level."""
    return _LOG_LEVEL
```

**Gateway Wrappers (gateway_wrappers.py):**
```python
"""
Gateway Wrappers - Convenience functions for logging.
"""

def log_debug(message: str, **context):
    """
    Log debug message.
    
    Args:
        message: Log message
        **context: Additional context fields
    """
    from gateway_core import execute_operation, GatewayInterface
    execute_operation(
        GatewayInterface.LOGGING,
        'debug',
        {'message': message, **context}
    )

def log_info(message: str, **context):
    """
    Log info message.
    
    Args:
        message: Log message
        **context: Additional context fields
    """
    from gateway_core import execute_operation, GatewayInterface
    execute_operation(
        GatewayInterface.LOGGING,
        'info',
        {'message': message, **context}
    )

def log_warning(message: str, **context):
    """
    Log warning message.
    
    Args:
        message: Log message
        **context: Additional context fields
    """
    from gateway_core import execute_operation, GatewayInterface
    execute_operation(
        GatewayInterface.LOGGING,
        'warning',
        {'message': message, **context}
    )

def log_error(message: str, **context):
    """
    Log error message.
    
    Args:
        message: Log message
        **context: Additional context fields
    """
    from gateway_core import execute_operation, GatewayInterface
    execute_operation(
        GatewayInterface.LOGGING,
        'error',
        {'message': message, **context}
    )

def log_critical(message: str, **context):
    """
    Log critical message.
    
    Args:
        message: Log message
        **context: Additional context fields
    """
    from gateway_core import execute_operation, GatewayInterface
    execute_operation(
        GatewayInterface.LOGGING,
        'critical',
        {'message': message, **context}
    )
```

---

### Pattern 2: Logging with Contextual Information

**For rich context:**
```python
"""
Logging with automatic context addition.
"""
import os
import socket

def _get_execution_context():
    """Get execution context automatically."""
    return {
        'environment': os.environ.get('ENVIRONMENT', 'unknown'),
        'hostname': socket.gethostname(),
        'process_id': os.getpid(),
    }

def log_with_context(level: str, message: str, **context):
    """Log with automatic context."""
    execution_context = _get_execution_context()
    full_context = {**execution_context, **context}
    
    _log_message(level, message, **full_context)
```

---

### Pattern 3: Logging with Correlation IDs

**For request tracing:**
```python
"""
Logging with correlation IDs for request tracing.
"""
import threading
import uuid

# Thread-local storage for correlation ID
_context = threading.local()

def set_correlation_id(correlation_id: str = None):
    """Set correlation ID for this thread/request."""
    if not correlation_id:
        correlation_id = str(uuid.uuid4())
    _context.correlation_id = correlation_id
    return correlation_id

def get_correlation_id() -> str:
    """Get correlation ID for this thread/request."""
    return getattr(_context, 'correlation_id', 'unknown')

def log_info_with_correlation(message: str, **context):
    """Log with automatic correlation ID."""
    context['correlation_id'] = get_correlation_id()
    log_info(message, **context)
```

---

## 💡 USAGE EXAMPLES

### Example 1: Basic Logging

**Scenario:** Log operations during cache operations.

**Implementation:**
```python
def cache_get(key: str):
    """Get from cache with logging."""
    log_debug(f"Cache lookup", key=key)
    
    value = _CACHE_STORE.get(key)
    
    if value:
        log_info("Cache hit", key=key)
    else:
        log_info("Cache miss", key=key)
    
    return value
```

**Output:**
```json
{"timestamp": "2025-10-29T14:30:00.123Z", "level": "DEBUG", "message": "Cache lookup", "key": "user_123"}
{"timestamp": "2025-10-29T14:30:00.125Z", "level": "INFO", "message": "Cache hit", "key": "user_123"}
```

---

### Example 2: Error Logging with Context

**Scenario:** Log errors with full context for debugging.

**Implementation:**
```python
import traceback

def http_get(url: str):
    """HTTP GET with error logging."""
    log_info("HTTP GET request", url=url)
    
    try:
        response = requests.get(url, timeout=10)
        response.raise_for_status()
        
        log_info("HTTP GET success", 
            url=url, 
            status=response.status_code,
            response_time_ms=response.elapsed.total_seconds() * 1000
        )
        
        return response.json()
        
    except requests.Timeout as e:
        log_error("HTTP request timeout",
            url=url,
            timeout_seconds=10,
            error=str(e)
        )
        raise
        
    except requests.HTTPError as e:
        log_error("HTTP error",
            url=url,
            status_code=e.response.status_code,
            error=str(e),
            response_body=e.response.text[:200]
        )
        raise
        
    except Exception as e:
        log_critical("Unexpected HTTP error",
            url=url,
            error_type=type(e).__name__,
            error=str(e),
            stack_trace=traceback.format_exc()
        )
        raise
```

**Output (error case):**
```json
{
  "timestamp": "2025-10-29T14:30:00.123Z",
  "level": "ERROR",
  "message": "HTTP error",
  "url": "https://api.example.com/data",
  "status_code": 404,
  "error": "404 Client Error: Not Found",
  "response_body": "{\"error\": \"Resource not found\"}"
}
```

---

## 📄 EVOLUTION & VERSIONING

### Version History

**v1.0.0** (2025-10-29)
- Initial LOGGING interface pattern documentation
- Layer 0 foundation pattern
- Structured JSON output
- Multiple severity levels
- Zero dependencies requirement

### Future Considerations
- **Log Sampling:** Sample debug logs in high-traffic scenarios
- **Log Buffering:** Buffer logs for batch output
- **Log Rotation:** Automatic log file rotation (if file-based)
- **Custom Formatters:** Pluggable log formatters (JSON, plain text, etc.)

### Deprecation Path
**If This Pattern Is Deprecated:**
- **Reason:** [Standard logging library adoption]
- **Replacement:** [Python logging library with structured handlers]
- **Migration Guide:** [Replace log_* calls with logger.* calls]
- **Support Timeline:** [Minimum 1 year deprecation notice]

---

## 📚 REFERENCES

### Internal References
- **Related Patterns:** 
  - INT-03 (SECURITY) - Uses LOGGING, provides sanitization
  - INT-04 (METRICS) - Uses LOGGING
  - All other interfaces - Use LOGGING (Layer 0 dependency)

### External References
- **Pattern Origin:** Standard logging patterns (syslog, log4j)
- **Structured Logging:** JSON logging for machine parsing
- **Severity Levels:** Standard log levels (RFC 5424)

### Related Entries
- **Architecture:** ARCH-SUGA (Layer 0 in dependency hierarchy)
- **Dependencies:** DEP-01 (Layer 0 - Base Infrastructure)

---

## 🤝 CONTRIBUTORS

**Original Author:** SIMAv4 Phase 3.0 Documentation  
**Major Contributors:**
- SUGA-ISP Project Team - Production logging implementation
- SIMAv4 Phase 3.0 - Generic pattern extraction

**Last Reviewed By:** Claude  
**Review Date:** 2025-10-29

---

## 📝 CHANGE LOG

### [1.0.0] - 2025-10-29
- Initial LOGGING interface pattern documentation
- Extracted from SUGA-ISP project knowledge
- Generalized for reuse across any project
- Documented Layer 0 requirements and zero dependency constraint

---

**END OF INTERFACE ENTRY**

**REF-ID:** INT-02  
**Template Version:** 1.0.0  
**Entry Type:** Interface Pattern  
**Status:** Active  
**Maintenance:** Review quarterly or after major logging changes

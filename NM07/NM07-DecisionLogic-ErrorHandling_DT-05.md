# NM07-DecisionLogic-ErrorHandling_DT-05.md - DT-05

# DT-05: How Should I Handle This Error?

**Category:** Decision Logic
**Topic:** Error Handling
**Priority:** High
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Decision tree for error handling strategies - determining whether to handle gracefully, retry with backoff, log, or propagate errors based on error type and recoverability.

---

## Context

Different error types require different handling strategies. Expected errors should be handled gracefully, transient errors should be retried, and unexpected errors should propagate with proper logging.

---

## Content

### Decision Tree

```
START: Error occurred in function
│
├─ Q: Is this expected/recoverable error?
│  ├─ YES → Handle gracefully
│  │      Examples:
│  │      - Cache miss → Return None
│  │      - File not found → Return empty
│  │      - Validation failed → Return error dict
│  │      Pattern:
│  │        try:
│  │            result = risky_operation()
│  │        except SpecificError:
│  │            log_warning("Expected error occurred")
│  │            return default_value
│  │      → END
│  │
│  └─ NO (unexpected) → Continue
│
├─ Q: Can operation be retried?
│  ├─ YES → Retry with backoff
│  │      Examples:
│  │      - Network timeout
│  │      - Rate limit
│  │      Pattern:
│  │        for attempt in range(3):
│  │            try:
│  │                return risky_operation()
│  │            except RetryableError:
│  │                time.sleep(2 ** attempt)
│  │        raise  # Failed all retries
│  │      → END
│  │
│  └─ NO → Continue
│
├─ Q: Should error be logged?
│  ├─ NO (sensitive/spammy) → Log at debug level
│  │      gateway.log_debug(f"Error: {e}")
│  │      → Continue
│  │
│  └─ YES → Log at appropriate level
│         gateway.log_error(f"Error: {e}", error=e)
│         → Continue
│
├─ Q: Should error propagate to caller?
│  ├─ YES → Re-raise or return error dict
│  │      Pattern:
│  │        try:
│  │            result = operation()
│  │        except Exception as e:
│  │            log_error(f"Failed: {e}", error=e)
│  │            raise  # Propagate
│  │      → END
│  │
│  └─ NO → Return error indicator
│         Pattern:
│           try:
│               result = operation()
│               return {'success': True, 'data': result}
│           except Exception as e:
│               log_error(f"Failed: {e}", error=e)
│               return {'success': False, 'error': str(e)}
│         → END
```

### Error Handling Decision Matrix

| Error Type | Action | Log Level | Propagate? |
|------------|--------|-----------|------------|
| Cache miss | Return None | No log | No |
| Invalid input | Return error dict | Warning | No |
| Network timeout | Retry 3x | Error (after retries) | Yes (if all fail) |
| Database error | Log + propagate | Error | Yes |
| Syntax error | Fail fast | Error | Yes |
| Configuration missing | Log + default | Warning | No |

### Handling Patterns

**Expected/Recoverable Errors:**
```python
def cache_get(key: str):
    """Handle cache miss gracefully."""
    try:
        return _get_from_cache(key)
    except CacheMissError:
        # Expected - return None
        return None
```

**Retryable Errors:**
```python
def api_call(url: str):
    """Retry transient network errors."""
    for attempt in range(3):
        try:
            return http_request(url)
        except (TimeoutError, RateLimitError) as e:
            if attempt < 2:
                sleep_time = 2 ** attempt  # 1s, 2s, 4s
                time.sleep(sleep_time)
                continue
            else:
                gateway.log_error(f"API call failed after 3 attempts: {e}")
                raise
```

**Log and Propagate:**
```python
def process_data(data: dict):
    """Log unexpected errors and propagate."""
    try:
        validate_data(data)
        return transform_data(data)
    except Exception as e:
        gateway.log_error(f"Data processing failed: {e}", error=e)
        raise  # Let caller handle
```

**Return Error Dict:**
```python
def validate_input(data: dict):
    """Return structured error for validation."""
    try:
        if not data:
            raise ValueError("Data cannot be empty")
        if 'required_field' not in data:
            raise ValueError("Missing required field")
        return {'success': True, 'data': data}
    except ValueError as e:
        gateway.log_warning(f"Validation failed: {e}")
        return {'success': False, 'error': str(e)}
```

### Real-World Usage Pattern

**User Query:** "How should I handle network errors?"

**Search Terms:** "handle error network"

**Decision Flow:**
1. Expected/recoverable? NO (network errors are unexpected)
2. Retryable? YES (transient network issues)
3. **Decision:** Retry with backoff (3 attempts)
4. Log after final failure, propagate
5. **Response:** "Network timeout → Retry with backoff (3 attempts). Log error after final failure."

### Error Logging Levels

**ERROR Level:**
- Unexpected failures
- After all retry attempts
- Database errors
- System errors

**WARNING Level:**
- Expected errors (validation failures)
- Configuration issues with defaults
- Deprecated feature usage

**DEBUG Level:**
- Sensitive information
- Verbose debugging
- Expected operational events

**INFO Level:**
- Normal operations
- Success events
- State changes

---

## Related Topics

- **DEC-15**: Error handling design
- **AP-14**: Bare except clauses (anti-pattern)
- **AP-15**: Silent failures (anti-pattern)
- **DT-06**: Exception type selection
- **ERR-02**: Error propagation patterns

---

## Keywords

error handling, exceptions, retry logic, graceful degradation, error propagation, logging

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-ErrorHandling_DT-05.md`
**End of Document**

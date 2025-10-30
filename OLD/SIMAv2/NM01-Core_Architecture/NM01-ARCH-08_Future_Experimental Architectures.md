# ARCH-08: Future/Experimental Architectures
**REF:** NM01-ARCH-08  
**PRIORITY:** 🟢 MEDIUM  
**TAGS:** future, experimental, planning, ftpms, ofb, mdoe  
**KEYWORDS:** future architectures, experimental systems, planned features  
**RELATED:** ARCH-07 (LMMS - implemented)

---

## Overview

This section documents three architectures that have been designed but are **lightly developed** or **not yet implemented**. They represent future enhancements to the SUGA-ISP Lambda system.

**Status:** Conceptual / Early Development
**Priority:** Medium (nice-to-have, not critical)
**Purpose:** Document vision and design for future work

---

## Architecture 1: FTPMS

### Name
**FTPMS** = **Free Tier Protection & Monitoring System**

### Purpose
Real-time monitoring and automatic throttling to guarantee AWS Free Tier compliance

### Status
🟡 **Lightly Developed** - Basic monitoring in place, protection logic partially implemented

### The Problem
- AWS Free Tier: 400,000 GB-seconds/month, 1M requests/month
- Easy to exceed limits accidentally
- Manual monitoring is error-prone
- Need automatic protection

### The Solution
```
Real-time monitoring:
  ↓
Track GB-seconds usage
  ↓
Predict end-of-month total
  ↓
If approaching limit:
  ├─ Throttle non-critical requests
  ├─ Log warning
  └─ Optional: Block requests

Result: Zero risk of exceeding free tier
```

### Key Features

**1. Real-Time Tracking**
```python
# Track every invocation
current_gb_seconds = memory_mb * duration_s / 1000
monthly_total += current_gb_seconds

# Track requests
monthly_requests += 1
```

**2. Projection Logic**
```python
# Calculate runway
days_into_month = current_day / days_in_month
projected_total = (monthly_total / days_into_month) * days_in_month

if projected_total > 400000:  # Will exceed
    trigger_protection()
```

**3. Protection Mechanisms**
```python
PROTECTION_LEVELS:
    WARN    - 75% of limit (log warnings)
    THROTTLE - 85% of limit (slow down)
    BLOCK    - 95% of limit (emergency stop)
```

### Metrics to Monitor
- GB-seconds used (current month)
- Request count (current month)
- Projected end-of-month usage
- Protection level status
- Throttled request count

### Configuration
```bash
FTPMS_ENABLED=true
FTPMS_GB_SECONDS_LIMIT=400000
FTPMS_REQUEST_LIMIT=1000000
FTPMS_WARN_THRESHOLD=0.75
FTPMS_THROTTLE_THRESHOLD=0.85
FTPMS_BLOCK_THRESHOLD=0.95
```

### Implementation Status
- ✅ Basic CloudWatch metrics collection
- ✅ GB-seconds calculation
- 🟡 Projection logic (partial)
- ❌ Throttling mechanism (not implemented)
- ❌ Request blocking (not implemented)
- ❌ Dashboard/alerts (not implemented)

### Benefits
- **Zero risk** of exceeding free tier
- **Automatic protection** without manual monitoring
- **Predictive alerts** before hitting limits
- **Cost control** for development/testing

### Priority
Medium - Useful for development, less critical for production (different billing)

### Related
- LMMS reduces GB-seconds (82% less), extends free tier capacity
- DEC-07: Dependencies < 128MB (supports free tier)

---

## Architecture 2: OFB

### Name
**OFB** = **Operation Fusion & Batching**

### Purpose
Automatically combine multiple sequential operations into single optimized calls

### Status
🟡 **Lightly Developed** - Pattern identified, basic design complete, not implemented

### The Problem
```python
# Inefficient: 3 separate calls
cache_get('user:123')      # 5ms
cache_get('session:456')   # 5ms  
cache_get('config:789')    # 5ms
# Total: 15ms + 3× gateway overhead

# Each call has overhead:
- Gateway routing: ~1ms
- Validation: ~0.5ms
- Logging: ~0.5ms
- Total overhead: 2ms × 3 = 6ms
```

### The Solution
```python
# Efficient: 1 batched call
cache_get_batch(['user:123', 'session:456', 'config:789'])
# Total: 7ms + 1× gateway overhead = 9ms

# Savings: 15ms + 6ms - 9ms = 12ms (55% faster)
```

### Key Features

**1. Pattern Detection**
```python
# Detect sequential operations to same interface
operations = [
    ('CACHE', 'get', {'key': 'user:123'}),
    ('CACHE', 'get', {'key': 'session:456'}),
    ('CACHE', 'get', {'key': 'config:789'}),
]
# → Batch into single cache_get_batch()
```

**2. Automatic Fusion**
```python
# Transparent to caller
result1 = cache_get('user:123')
result2 = cache_get('session:456')  # Queue for batching
result3 = cache_get('config:789')   # Execute batch now

# Behind scenes:
# → cache_get_batch(['user:123', 'session:456', 'config:789'])
# → Split results to individual calls
```

**3. Operation Types**
- **Batchable:** cache_get, cache_set, validate_*
- **Not batchable:** log_* (order matters), http_request (different endpoints)

### Implementation Approach

**Phase 1: Manual Batching**
```python
# Add batch operations to each interface
def cache_get_batch(keys: List[str]) -> Dict[str, Any]:
    """Get multiple cache keys in single operation."""
    return {key: _internal_get(key) for key in keys}
```

**Phase 2: Automatic Detection**
```python
# Queue operations, execute batch at yield point
class OperationBatcher:
    def queue(self, interface, operation, **kwargs):
        self.pending.append((interface, operation, kwargs))
        
    def flush(self):
        # Group by interface+operation
        # Execute batched calls
        # Return individual results
```

**Phase 3: Transparent Integration**
```python
# Gateway automatically batches when safe
def execute_operation(interface, operation, **kwargs):
    if should_batch(interface, operation):
        queue_for_batch(interface, operation, **kwargs)
        return deferred_result()
    else:
        return execute_immediately()
```

### Benefits
- **3-5x reduction** in gateway overhead
- **20-40% faster** for batch-heavy workloads
- **Transparent** to calling code
- **Automatic** optimization

### Challenges
- Order dependencies (some operations must be sequential)
- Error handling (one failure affects batch)
- Complexity (when to flush queue?)
- Testing (edge cases multiply)

### Implementation Status
- ✅ Design complete
- ✅ Benefit analysis done
- 🟡 Manual batch operations (partial - cache only)
- ❌ Automatic detection (not started)
- ❌ Transparent integration (not started)

### Priority
Medium - Nice optimization, not critical for correctness

### Related
- ZAPH fast path (synergy - batch hot operations)
- LMMS (reduces overhead that batching would save)

---

## Architecture 3: MDOE

### Name
**MDOE** = **Metadata-Driven Operation Engine**

### Purpose
Replace imperative code with declarative metadata interpreted at runtime

### Status
🟠 **Conceptual** - Design outlined, not implemented

### The Problem
```python
# Imperative: Hard-coded logic
def process_user_request(request):
    if not validate_token(request['token']):
        return error('Invalid token')
    
    if not check_rate_limit(request['user_id']):
        return error('Rate limited')
    
    user = cache_get(f"user:{request['user_id']}")
    if not user:
        user = db_get_user(request['user_id'])
        cache_set(f"user:{request['user_id']}", user, ttl=300)
    
    result = execute_action(user, request['action'])
    log_success(request['action'], user['id'])
    return success(result)
```

### The Solution
```yaml
# Declarative: Metadata-driven
operation: process_user_request
pipeline:
  - validate:
      field: token
      method: validate_token
      on_fail: error('Invalid token')
  
  - check:
      method: check_rate_limit
      params: [user_id]
      on_fail: error('Rate limited')
  
  - fetch:
      key: "user:{{user_id}}"
      from: cache
      fallback:
        source: database
        method: get_user
        cache_result:
          ttl: 300
  
  - execute:
      method: execute_action
      params: [user, action]
  
  - log:
      event: success
      data: [action, user.id]
  
  - return:
      type: success
      data: result
```

### Key Features

**1. Declarative Pipeline**
- Define operations as data, not code
- Interpreter executes pipeline
- Easy to modify without code changes

**2. Dynamic Runtime**
```python
# Load pipeline from file/config
pipeline = load_pipeline('user_request.yaml')

# Execute pipeline
result = execute_pipeline(pipeline, request)

# No code changes needed to modify flow
```

**3. Benefits**
- **40-50% memory reduction** (no compiled code for variants)
- **Runtime optimization** (interpreter can optimize)
- **A/B testing** (swap pipelines without redeploy)
- **Non-programmer config** (ops can modify flows)

### Implementation Approach

**Phase 1: Pipeline Engine**
```python
class PipelineEngine:
    def execute(self, pipeline: dict, context: dict) -> Any:
        for step in pipeline['steps']:
            result = self.execute_step(step, context)
            if step.get('on_fail') and is_error(result):
                return handle_error(step['on_fail'])
            context.update(result)
        return context
```

**Phase 2: Step Executors**
```python
STEP_EXECUTORS = {
    'validate': ValidateExecutor(),
    'fetch': FetchExecutor(),
    'execute': ExecuteExecutor(),
    'log': LogExecutor(),
}
```

**Phase 3: Optimization**
```python
# Compile pipelines to bytecode
# Cache compiled pipelines
# JIT optimize hot pipelines
```

### Benefits
- **Flexibility** - Change flows without code changes
- **Memory Efficient** - Single engine, many pipelines
- **A/B Testing** - Easy to test variations
- **Runtime Optimization** - Interpreter can optimize

### Challenges
- **Debugging** - Harder to debug metadata than code
- **Performance** - Interpretation overhead
- **Type Safety** - Lose compile-time checks
- **Complexity** - Learning curve for metadata format

### Implementation Status
- ✅ Concept designed
- ❌ Pipeline engine (not started)
- ❌ Step executors (not started)
- ❌ YAML/JSON format (not started)
- ❌ Optimization (not started)

### Priority
Low - Interesting idea, not needed for current functionality

### Related
- Could complement SUGA (declarative operations)
- Could integrate with OFB (declare batching in metadata)

---

## Implementation Priority

### Immediate (Now)
- None (focus on core SUGA/LMMS)

### Short-Term (3-6 months)
- **FTPMS:** Complete protection logic
- **OFB:** Add manual batch operations

### Medium-Term (6-12 months)
- **FTPMS:** Full dashboard and alerts
- **OFB:** Automatic detection and batching

### Long-Term (12+ months)
- **MDOE:** Evaluate need and implement if valuable

---

## Decision Criteria

**When to implement:**
- Clear performance benefit (>20% improvement)
- Solves real pain point
- Doesn't add excessive complexity
- ROI justifies development time

**When NOT to implement:**
- Premature optimization
- Unclear benefit
- High complexity for small gain
- Maintenance burden too high

---

## Related Architectures

**Implemented:**
- SUGA: Gateway pattern (foundation)
- ISP: Interface isolation (structure)
- LMMS: Memory management (performance)

**Future:**
- FTPMS: Free tier protection (safety)
- OFB: Operation batching (optimization)
- MDOE: Metadata-driven (flexibility)

---

## References

**Source Documents:**
- User requirements (2025-10-21)
- Performance analysis
- Cost optimization studies

**Related Decisions:**
- DEC-13: Fast path (implemented in ZAPH)
- DEC-14: Lazy loading (implemented in LIGS)
- DEC-XX: Future architecture decisions

---

**REAL-WORLD USAGE:**
- User: "What about the other architectures you mentioned?"
- Claude: "FTPMS, OFB, MDOE are lightly developed/conceptual. See ARCH-08 for details on each."

---

**END OF ARCH-08**

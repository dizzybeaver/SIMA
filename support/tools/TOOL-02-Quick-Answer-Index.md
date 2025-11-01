# TOOL-02: Quick Answer Index

**REF-ID:** TOOL-02  
**Category:** Tool  
**Type:** Fast Lookup Reference  
**Purpose:** Instant answers to top 20 most common questions  
**Version:** 1.0.0  
**Last Updated:** 2025-11-01

---

## ðŸŽ¯ TOOL PURPOSE

**What:** Pre-answered common questions with immediate responses and REF-ID citations

**When to Use:**
- Need fast answer to common question
- Before deep research into neural maps
- Quick verification of standard patterns
- Onboarding new team members

**How It Helps:**
- Saves research time (seconds vs minutes)
- Consistent answers across team
- Authoritative citations
- Enables fast decisions

---

## ⚡ TOP 10 INSTANT ANSWERS

### Q1: Can I import directly from core modules?

**Answer:** ❌ NO - Never import directly from core modules (those ending in `_core.py`).

**Reason:** Violates Gateway Pattern. All core access must go through gateway.py.

**Correct Pattern:**
```python
# ❌ WRONG
from cache_core import get_cache

# âœ… CORRECT
from gateway import get_cache
```

**Citations:** ARCH-01, RULE-01, AP-01, LESS-03

---

### Q2: Should I use threading locks in Lambda?

**Answer:** ❌ NO - Lambda is single-threaded. Threading locks provide no benefit and cause deadlocks.

**Reason:** Lambda executes one request at a time. Threading primitives are unnecessary and harmful.

**What to Use Instead:**
- Simple state management
- Module-level variables (if needed)
- No synchronization primitives

**Citations:** ARCH-02, AP-08, AP-25, BUG-02

---

### Q3: How do I handle cache misses?

**Answer:** âœ… Return None or appropriate default at the boundary. Never let sentinel objects leak.

**Pattern:**
```python
def get_from_cache(key):
    value = cache.get(key)  # May return _CacheMiss
    return None if value is _CacheMiss else value  # Sanitize
```

**Reason:** Sentinel objects fail JSON serialization and leak implementation details.

**Citations:** LESS-42, BUG-01, AP-10, INT-01

---

### Q4: What's the maximum file size for Lambda modules?

**Answer:** âœ… Keep modules under 800 lines. Optimal: 200-400 lines.

**Limits:**
- **Soft limit:** 800 lines (consider splitting)
- **Optimal:** 200-400 lines (best maintainability)
- **Hard limit:** 1000 lines (too large, must split)

**Why:** Improves cold start time, maintainability, testability.

**Citations:** ARCH-09, DEC-12, LESS-28

---

### Q5: Do I need to fetch files before modifying?

**Answer:** âœ… YES - Always fetch and read current state before any modification.

**Process:**
1. Fetch current file content
2. Read and understand complete state
3. Make modifications
4. Verify changes
5. Deploy

**Why:** Prevents errors from stale assumptions (94% error reduction).

**Citations:** LESS-01, WISD-01, AP-20

---

### Q6: Can I use bare except clauses?

**Answer:** ❌ NO - Never use bare `except:` clauses. Always specify exception types.

**Pattern:**
```python
# ❌ WRONG
try:
    operation()
except:  # Catches everything including KeyboardInterrupt
    handle()

# âœ… CORRECT
try:
    operation()
except (ValueError, KeyError) as e:  # Specific exceptions only
    handle(e)
```

**Why:** Masks bugs, catches system exceptions, prevents debugging.

**Citations:** AP-14, LANG-PY-03, LESS-19

---

### Q7: How should I structure imports in gateway.py?

**Answer:** âœ… Gateway imports from interfaces and core. Others import only from gateway.

**Structure:**
```python
# gateway.py - imports from everywhere
from interface_cache import get_cache as _get_cache
from cache_core import CacheManager as _CacheManager

def get_cache():  # Public gateway function
    return _get_cache()

# other files - import only from gateway
from gateway import get_cache  # âœ… Correct
```

**Citations:** ARCH-01, GATE-01, RULE-01, DEP-02

---

### Q8: Should I batch metrics or send individually?

**Answer:** âœ… ALWAYS batch metrics. Reduces costs 20x and improves performance.

**Pattern:**
```python
# Collect metrics throughout execution
metrics.add("counter", value)
metrics.add("timer", duration)

# Publish batch at end
metrics.publish_batch()  # 20x cheaper than individual publishes
```

**Why:** CloudWatch charges per API call. Batching reduces cost dramatically.

**Citations:** INT-07, DEC-20, LESS-25, LESS-49

---

### Q9: Where do I initialize expensive resources?

**Answer:** âœ… At module level for warm start reuse. Use singleton pattern.

**Pattern:**
```python
# Module level (outside handler)
_s3_client = None  # Singleton storage

def get_s3_client():
    global _s3_client
    if _s3_client is None:
        _s3_client = boto3.client('s3')  # Initialize once
    return _s3_client

# In handler
def lambda_handler(event, context):
    client = get_s3_client()  # Reused on warm start
```

**Why:** 50-90% faster warm requests. Amortizes initialization cost.

**Citations:** INT-09, INT-05, ARCH-02, LESS-17

---

### Q10: How do I handle errors in Lambda?

**Answer:** âœ… Return structured error response. Log details. Never raise to Lambda runtime unless critical.

**Pattern:**
```python
def lambda_handler(event, context):
    try:
        result = process(event)
        return {"statusCode": 200, "body": result}
    except ValueError as e:
        logger.error("Validation error", extra={"error": str(e)})
        return {"statusCode": 400, "body": {"error": "Invalid input"}}
    except Exception as e:
        logger.exception("Unexpected error")
        return {"statusCode": 500, "body": {"error": "Internal error"}}
```

**Why:** Graceful degradation. Clear error responses. Better debugging.

**Citations:** ARCH-04, LANG-PY-03, LESS-09

---

## 📚 NEXT 10 COMMON QUESTIONS

### Q11: Should I use environment variables or Parameter Store?

**Answer:** âœ… Use environment variables for non-sensitive config. Use Secrets Manager for secrets.

**Pattern:**
- **Environment Variables:** Feature flags, timeouts, non-sensitive config
- **Secrets Manager:** API keys, passwords, credentials
- **Parameter Store:** Shared configuration across services

**Citations:** INT-06, DEC-16, LESS-30

---

### Q12: How do I test Lambda functions locally?

**Answer:** âœ… Use pytest with mocked AWS services. Test layers independently.

**Pattern:**
```python
# test_handler.py
from unittest.mock import Mock, patch

def test_handler():
    with patch('gateway.get_cache') as mock_cache:
        mock_cache.return_value = {"key": "value"}
        result = lambda_handler(event, context)
        assert result["statusCode"] == 200
```

**Citations:** DT-08, LESS-23, LESS-24

---

### Q13: What's the retry strategy for external HTTP calls?

**Answer:** âœ… Use exponential backoff with circuit breaker. Max 3 retries for 5xx errors.

**Pattern:**
- **Retry:** 5xx errors (server failures)
- **Don't Retry:** 4xx errors (client errors)
- **Backoff:** 1s, 2s, 4s (exponential)
- **Circuit Breaker:** Open after 5 consecutive failures

**Citations:** INT-04, INT-12, DEC-18, LESS-21

---

### Q14: How many CloudWatch Logs should I write per request?

**Answer:** âœ… 3-5 log statements per request. More logs = higher costs and noise.

**Pattern:**
- **Always:** Request start, request end, errors
- **Conditionally:** Key decisions, external calls
- **Never:** Debug spam, verbose loops

**Citations:** INT-02, LESS-32, DEC-21

---

### Q15: Should I create subdirectories in src/?

**Answer:** ❌ NO for most cases. Keep flat structure except for major subsystems (like home_assistant/).

**Reason:** Flat structure simplifies imports, reduces complexity, improves discoverability.

**Exception:** Large subsystems (100+ files) may warrant subdirectory.

**Citations:** ARCH-01, DEC-03, LESS-04

---

### Q16: How do I validate input at gateway layer?

**Answer:** âœ… Use explicit validation functions. Return 400 errors for invalid input.

**Pattern:**
```python
def validate_request(event):
    if "body" not in event:
        raise ValueError("Missing body")
    body = json.loads(event["body"])
    if "required_field" not in body:
        raise ValueError("Missing required_field")
    return body
```

**Citations:** ARCH-01, INT-03, LESS-07

---

### Q17: What's the Lambda timeout setting?

**Answer:** âœ… Set based on P99 latency + 20% buffer. Typical: 10-30 seconds.

**Guidelines:**
- **Simple queries:** 10s
- **Complex processing:** 30s
- **Maximum:** 15 minutes (but reconsider design)

**Why:** Prevents runaway executions, controls costs.

**Citations:** ARCH-02, DEC-22, LESS-36

---

### Q18: Should I use async/await in Lambda?

**Answer:** ⚠️ MAYBE - Only if you have truly concurrent I/O operations. Most Lambda functions don't need it.

**Use When:**
- Multiple simultaneous external API calls
- Concurrent database queries
- Parallel processing of independent items

**Don't Use When:**
- Sequential operations
- Single external call
- Simple request/response

**Citations:** LANG-PY-07, DEC-17, LESS-40

---

### Q19: How do I handle cold starts?

**Answer:** âœ… Optimize: (1) Minimize imports, (2) Lazy load heavy resources, (3) Preload critical paths.

**Strategy:**
1. **Module Level:** Only essential imports and lightweight initialization
2. **First Request:** Load heavy resources (databases, large libraries)
3. **Subsequent:** Reuse initialized resources from globals

**Target:** < 1000ms cold start

**Citations:** INT-05, LESS-08, LESS-17, LESS-28

---

### Q20: What libraries should I avoid in Lambda?

**Answer:** âš ï¸ AVOID: Heavy ML libraries (TensorFlow, PyTorch), large numerical libraries (SciPy), GUI libraries.

**Why:** Lambda has 128MB memory limit in Python runtime. Large libraries:
- Increase cold start time (3-10x)
- Increase package size
- May exceed memory limits

**Alternatives:**
- Use Lambda layers for unavoidable large libraries
- Consider ECS/Fargate for ML workloads
- Use lightweight alternatives (e.g., NumPy instead of SciPy)

**Citations:** ARCH-02, DEC-13, LESS-26

---

## 🔍 USAGE GUIDE

### Finding Answers Fast

**Step 1:** Scan question titles above  
**Step 2:** Read full answer if relevant  
**Step 3:** Note citations for deeper dive  
**Step 4:** Follow citations to neural maps if needed

### When This Tool Isn't Enough

If your question isn't here:
1. **Use project_knowledge_search:** Search neural maps
2. **Check workflows:** Common processes documented
3. **Check decision trees:** For decision logic
4. **Ask with context:** Provide specific scenario

---

## 📊 ANSWER USAGE STATISTICS

### Most Referenced Answers

1. **Q1 (Direct Core Imports):** ~40% of queries
2. **Q5 (Fetch Before Modify):** ~20% of queries
3. **Q2 (Threading Locks):** ~15% of queries
4. **Q3 (Cache Misses):** ~10% of queries
5. **Q9 (Resource Init):** ~10% of queries

### Common Question Patterns

- **Import questions:** 35% of queries
- **Performance questions:** 25% of queries
- **Error handling:** 20% of queries
- **Best practices:** 20% of queries

---

## 🔧 MAINTAINING THIS TOOL

### Adding New Quick Answers

**Criteria for inclusion:**
- Asked 5+ times in one month
- Clear, actionable answer exists
- Citable from neural maps
- Genuinely time-saving

**Process:**
1. Identify commonly asked question
2. Draft concise answer with citations
3. Verify all citations valid
4. Add to appropriate section
5. Update usage statistics

### Updating Existing Answers

**When to update:**
- Pattern changes
- Better approach discovered
- Citations become invalid
- Answer proven incomplete

**Process:**
1. Revise answer text
2. Update citations
3. Update "Last Updated" date
4. Note change in version history

---

## ðŸ"— RELATED TOOLS

- **TOOL-01:** REF-ID Directory - Look up citations
- **TOOL-03:** Anti-Pattern Checklist - Validation tool
- **TOOL-04:** Verification Protocol - Quality checks
- **Workflow-04:** Why Questions - Deeper investigation

---

## 🎓 BEST PRACTICES

**Using Quick Answers:**
âœ… Check here FIRST before deep research  
âœ… Trust the citations - they're authoritative  
âœ… Follow citations for deeper understanding  
âœ… Use exact patterns shown in examples  

**Contributing:**
âœ… Track questions you're asked repeatedly  
âœ… Suggest additions to maintainers  
âœ… Verify answers before suggesting  
âœ… Provide usage statistics when available  

---

**END OF TOOL**

**Tool Version:** 1.0.0  
**Last Updated:** 2025-11-01  
**Maintained By:** SIMA Team  
**Quick Answers:** 20  
**Average Query Time:** < 30 seconds  
**Next Update:** Monthly
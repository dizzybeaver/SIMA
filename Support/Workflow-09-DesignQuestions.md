# Workflow-09-DesignQuestions.md
**Design Decisions and Architecture Questions - Step-by-Step**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: Answer design decision and architectural pattern questions

---

## 🎯 TRIGGERS

- "How should I design [X]?"
- "What's the best way to implement [Y]?"
- "Should I use [pattern A] or [pattern B]?"
- "How do I structure [feature]?"
- "What's the SUGA way to do [X]?"
- "Design advice for [problem]"

---

## ⚡ DECISION TREE

```
User asks design question
    ↓
Step 1: Understand Requirements
    → What problem are they solving?
    → What constraints exist?
    → What's the context?
    ↓
Step 2: Check Existing Patterns
    → Search design decisions (NM04)
    → Check similar implementations
    → Find relevant architecture patterns
    ↓
Found pattern? → Explain + adapt to problem
    ↓
Step 3: Check Anti-Patterns
    → What approaches to avoid?
    → What mistakes have we made?
    ↓
Step 4: Apply SIMA Principles
    → How does this fit SIMA?
    → Which interface?
    → Gateway → Interface → Core
    ↓
Step 5: Design Solution
    → Propose architecture
    → Show implementation structure
    → Explain trade-offs
    ↓
Step 6: Provide Example
    → Complete code structure
    → SIMA pattern demonstration
    → Verification checklist
```

---

## 📋 STEP-BY-STEP PROCESS

### Step 1: Understand Requirements (1 minute)

**Essential questions:**

**Functional Requirements:**
- What does it need to do?
- What are the inputs/outputs?
- What's the expected behavior?

**Non-Functional Requirements:**
- Performance constraints?
- Security requirements?
- Reliability needs?

**Context:**
- Hot path or cold path?
- Frequently used or rare?
- Critical or optional?

**Constraints:**
- 128MB memory limit
- < 3s cold start budget
- Single-threaded Lambda
- AWS free tier compliance

**Example:**
```
User: "How should I implement caching for API responses?"

Requirements:
- Cache HTTP responses
- TTL-based expiration
- Memory efficient
- Fast retrieval

Context:
- Hot path (frequent access)
- Critical for performance
- Must be reliable

Constraints:
- < 128MB total memory
- Need fast cold start
```

---

### Step 2: Check Existing Patterns (1 minute)

**Search design decisions:**

**File:** NM04/NM04-Decisions_Index.md

**Common decision categories:**

| Design Area | Search Terms | REF-IDs |
|-------------|--------------|---------|
| Architecture | pattern, structure | DEC-01 to 05 |
| Caching | cache, store, ttl | DEC-05, INT-01 |
| Security | encrypt, auth, token | DEC-21, INT-03 |
| Configuration | config, settings | DEC-20, INT-05 |
| Error handling | error, exception | ERR-01, ERR-02 |

**Search existing implementations:**

**File:** NM01/NM01-Architecture_Index.md

**12 Core Interfaces:**
- Does this fit an existing interface?
- Can existing patterns be reused?
- Any similar implementations?

**Example:**
```
Question: "Cache API responses"

Search: "cache" in NM04
Found: DEC-05 (Sentinel sanitization)
      INT-01 (CACHE interface)

Search: NM01 for CACHE
Found: Complete cache implementation
      Sentinel pattern
      TTL support

Decision: Use existing CACHE interface pattern
```

---

### Step 3: Check Anti-Patterns (30 seconds)

**File:** NM05/NM05-AntiPatterns_Index.md

**What NOT to do:**

**Critical anti-patterns to avoid:**
- ❌ Direct cross-interface imports (AP-01)
- ❌ Threading locks (AP-08)
- ❌ Bare except clauses (AP-14)
- ❌ Sentinel leaks (AP-19)
- ❌ Skipping verification (AP-27)

**Design-specific anti-patterns:**

| Design Area | Anti-Pattern | Instead Do |
|-------------|--------------|------------|
| Imports | Direct import | Via gateway |
| State | Shared state | Stateless or cache |
| Errors | Swallow errors | Specific handling |
| Performance | Premature optimize | Measure first |
| Structure | Subdirectories | Flat structure |

---

### Step 4: Apply SIMA Principles (2 minutes)

**SIMA Pattern Application:**

**Step 4a: Choose Interface**

**12 Interfaces (INT-01 to INT-12):**
```
INT-01: CACHE           → Caching operations
INT-02: LOGGING         → Logging operations
INT-03: SECURITY        → Security operations
INT-04: METRICS         → Metrics operations
INT-05: CONFIG          → Configuration
INT-06: VALIDATION      → Input validation
INT-07: PERSISTENCE     → Data storage
INT-08: COMMUNICATION   → HTTP/WebSocket
INT-09: TRANSFORMATION  → Data transformation
INT-10: SCHEDULING      → Task scheduling
INT-11: MONITORING      → Health checks
INT-12: ERROR_HANDLING  → Error management
```

**Decision matrix:**
```
Feature type → Interface
Storing data → CACHE or PERSISTENCE
Calling API → COMMUNICATION
Processing data → TRANSFORMATION
Checking input → VALIDATION
```

**Step 4b: Design Three Layers**

```
Layer 1: Gateway (gateway_wrappers.py)
    ↓ Lazy import
Layer 2: Interface (interface_*.py)
    ↓ Routes to
Layer 3: Core (*_core.py)
    ↓ Implementation
```

**Example:**
```python
# Feature: Cache API responses with TTL

# Interface: INT-01 CACHE (already exists)
# Gateway: cache_set(key, value, ttl)
# Interface: set_with_ttl(key, value, ttl)
# Core: set_with_ttl_impl(key, value, ttl)

# Fits existing pattern perfectly!
```

---

### Step 5: Design Solution (3-5 minutes)

**Solution template:**

```markdown
## Solution Design

### Overview
[High-level description of solution]

### Architecture
[How it fits into SIMA]

### Components

#### Gateway Layer (gateway_wrappers.py)
```python
def interface_action_object(*args):
    """Gateway function"""
    import interface_module
    return interface_module.action(*args)
```

#### Interface Layer (interface_*.py)
```python
def action(*args):
    """Interface definition"""
    import module_core
    return module_core.action_impl(*args)
```

#### Core Layer (*_core.py)
```python
def action_impl(*args):
    """Implementation"""
    # Logic here
    return result
```

### Trade-offs

**Advantages:**
- [Benefit 1]
- [Benefit 2]

**Disadvantages:**
- [Cost 1]
- [Limitation 1]

### SIMA Compliance
✅ Gateway routing
✅ Interface separation
✅ Core implementation
✅ Lazy imports
✅ No anti-patterns
```

---

### Step 6: Provide Example (5 minutes)

**Complete working example:**

```markdown
## Complete Implementation Example

### Scenario
[Specific use case]

### Code Structure

```python
# === gateway_wrappers.py ===
def cache_api_response(url, response, ttl=300):
    """
    Cache API response with TTL.
    
    Args:
        url: API endpoint (cache key)
        response: Response data
        ttl: Time to live in seconds (default 300)
    
    Example:
        >>> gateway.cache_api_response('/api/devices', data, ttl=60)
    """
    import interface_cache
    return interface_cache.set_with_ttl(url, response, ttl)

def get_cached_api_response(url):
    """
    Get cached API response.
    
    Returns:
        Cached data or None if expired/missing
    """
    import interface_cache
    return interface_cache.get(url)

# === interface_cache.py ===
def set_with_ttl(key, value, ttl):
    """Cache with TTL."""
    import cache_core
    return cache_core.set_with_ttl_impl(key, value, ttl)

def get(key):
    """Get from cache."""
    import cache_core
    return cache_core.get_impl(key)

# === cache_core.py ===
import time

_cache = {}

def set_with_ttl_impl(key, value, ttl):
    """
    Store with expiration time.
    
    Note: Stores (value, expiry_time) tuple
    """
    expiry = time.time() + ttl
    _cache[key] = (value, expiry)
    return True

def get_impl(key):
    """
    Retrieve if not expired.
    
    Returns:
        Cached value or None
    """
    if key not in _cache:
        return None
    
    value, expiry = _cache[key]
    
    # Check expiration
    if time.time() > expiry:
        del _cache[key]
        return None
    
    return value
```

### Usage Example

```python
# Caching API call
import gateway

# Make API call
response = gateway.http_get('https://api.example.com/data')

# Cache the response (5 minute TTL)
gateway.cache_api_response('/api/data', response, ttl=300)

# Later: Try cache first
cached = gateway.get_cached_api_response('/api/data')
if cached:
    return cached  # Use cached (fast!)
else:
    # Make fresh API call
    response = gateway.http_get('https://api.example.com/data')
    gateway.cache_api_response('/api/data', response, ttl=300)
    return response
```

### Verification (LESS-15)

✅ Gateway functions in gateway_wrappers.py
✅ Interface in interface_cache.py
✅ Implementation in cache_core.py
✅ Lazy imports used
✅ No anti-patterns
✅ TTL correctly implemented
✅ Error handling included
```

---

## 💡 COMMON DESIGN PATTERNS

### Pattern 1: Adding New Feature

**When:** New functionality needed

**Template:** See Workflow-01-AddFeature.md

**Quick guide:**
1. Choose interface (INT-01 to INT-12)
2. Add gateway wrapper
3. Add interface function
4. Implement in core
5. Verify LESS-15

---

### Pattern 2: External API Integration

**When:** Need to call external service

**Interface:** INT-08 COMMUNICATION

**Example:**
```python
# Gateway
def external_api_call(endpoint, method='GET', **kwargs):
    import interface_communication
    return interface_communication.http_request(endpoint, method, **kwargs)

# Use existing HTTP client implementation
```

---

### Pattern 3: Data Transformation

**When:** Converting data formats

**Interface:** INT-09 TRANSFORMATION

**Example:**
```python
# Gateway
def transform_device_data(raw_data):
    import interface_transformation
    return interface_transformation.parse_device_data(raw_data)
```

---

### Pattern 4: Input Validation

**When:** Need to validate inputs

**Interface:** INT-06 VALIDATION

**Example:**
```python
# Gateway
def validate_api_token(token):
    import interface_validation
    return interface_validation.check_token_format(token)
```

---

## ⚠️ COMMON MISTAKES TO AVOID

**DON'T:**
- Design without checking existing patterns
- Ignore anti-patterns
- Skip SIMA pattern
- Create new interfaces unnecessarily
- Over-engineer simple solutions
- Forget about constraints (128MB, 3s)

**DO:**
- Check NM04 for existing decisions
- Review similar implementations
- Follow SIMA pattern strictly
- Use existing interfaces when possible
- Keep it simple
- Consider Lambda constraints
- Document design rationale

---

## 🔗 RELATED FILES

**Hub:** WORKFLOWS-PLAYBOOK.md  
**Add Feature:** Workflow-01-AddFeature.md  
**Design Decisions:** NM04/NM04-Decisions_Index.md  
**Architecture:** NM01/NM01-Architecture_Index.md  
**Anti-Patterns:** NM05/NM05-AntiPatterns_Index.md  
**Interfaces:** NM01/NM01-Architecture-InterfacesCore_Index.md

---

## 📊 SUCCESS METRICS

**Workflow succeeded when:**
- ✅ Requirements clearly understood
- ✅ Existing patterns checked
- ✅ SIMA pattern applied correctly
- ✅ Complete implementation provided
- ✅ Trade-offs explained
- ✅ Example code included
- ✅ Verification checklist completed
- ✅ No anti-patterns violated

**Time:** 10-20 minutes for design consultation

---

**END OF WORKFLOW**

**Lines:** ~280 (properly sized)  
**Priority:** MEDIUM (architectural guidance)  
**ZAPH:** Tier 3 (moderate use)

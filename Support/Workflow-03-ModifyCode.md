# Workflow-03-ModifyCode.md
**Modifying Existing Code - Step-by-Step**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: Safe and systematic code modification process

---

## 🎯 TRIGGERS

- "Can you update [function]?"
- "I need to change [feature]"
- "Fix [bug] in [file]"
- "Modify [X] to do [Y]"
- "Change the behavior of [Z]"
- "Update [configuration/logic/validation]"

---

## ⚡ DECISION TREE

```
User requests code modification
    ↓
Step 1: Locate Target Code
    → Which file(s)?
    → Which function(s)?
    → Gateway/Interface/Core?
    ↓
Step 2: Fetch Complete File
    → Use Workflow-11-FetchFiles
    → Read entire file (LESS-01)
    → Understand current state
    ↓
Step 3: Understand Current Implementation
    → What does it do now?
    → Why was it designed this way?
    → Any related dependencies?
    ↓
Step 4: Design Modification
    → What needs to change?
    → Impact on other code?
    → SIMA pattern preserved?
    ↓
Step 5: Verify No Anti-Patterns
    → Check AP-Checklist-Critical
    → Verify design decisions
    → Check constraints
    ↓
Step 6: Implement Change
    → Modify all affected layers
    → Maintain SIMA pattern
    → Update documentation
    ↓
Step 7: Verification (LESS-15)
    → 5-step checklist
    → Test modification
    → Verify no regressions
```

---

## 📋 STEP-BY-STEP PROCESS

### Step 1: Locate Target Code (30 seconds)

**Identify files to modify:**

**Gateway Layer:**
- File: `gateway.py` or `gateway_wrappers.py`
- When: Changing function signature or adding wrapper

**Interface Layer:**
- File: `interface_*.py` (e.g., interface_cache.py)
- When: Changing interface contract

**Core Layer:**
- File: `*_core.py` (e.g., cache_core.py)
- When: Changing implementation logic

**Multiple Layers:**
- When: Signature or behavior changes propagate
- All three files may need updates

**Example:**
```
Request: "Add timeout parameter to http_get"
Files: gateway_wrappers.py (gateway layer)
       interface_http.py (interface layer)
       http_client_core.py (core layer)
```

---

### Step 2: Fetch Complete File (1-2 minutes)

**CRITICAL: Must read complete files before modifying**

**Use:** Workflow-11-FetchFiles.md

**Process:**
```
1. Identify all files to modify
2. Fetch each complete file via web_fetch
3. Read entire file (LESS-01)
4. DO NOT modify based on memory
5. DO NOT assume file structure
```

**Example:**
```python
# Fetch current version
web_fetch("https://claude.dizzybeaver.com/src/gateway_wrappers.py")
web_fetch("https://claude.dizzybeaver.com/src/interface_http.py")
web_fetch("https://claude.dizzybeaver.com/src/http_client_core.py")

# Read completely before making changes
```

**Why this matters:**
- Files change between sessions
- Assumptions lead to errors
- Complete context prevents mistakes
- See LESS-01 for rationale

---

### Step 3: Understand Current Implementation (2 minutes)

**Analyze existing code:**

**Function Purpose:**
- What does it currently do?
- What are the inputs/outputs?
- How is it used?

**Design Rationale:**
- Why was it implemented this way?
- Check neural maps for DEC-## references
- Any related lessons (LESS-##)?

**Dependencies:**
- What calls this function?
- What does this function call?
- Will changes break anything?

**Example Analysis:**
```python
# Current: gateway.http_get(url, headers)
# Purpose: Make HTTP GET requests
# Used by: HA integration, config loader, 5+ other places
# Design: Simple, synchronous, no timeout specified
# Question: Adding timeout - what's default? What breaks?
```

---

### Step 4: Design Modification (2-3 minutes)

**Plan the change:**

**What Changes:**
- Exact code modifications
- New parameters (with defaults!)
- Changed return values
- Updated error handling

**SIMA Pattern Preserved:**
```
✅ Gateway still routes to interface
✅ Interface still routes to core
✅ Lazy imports maintained
✅ No direct cross-interface calls
```

**Backward Compatibility:**
- Will existing code break?
- Need default values?
- Deprecation needed?

**Example Design:**
```python
# BEFORE
def http_get(url, headers=None):
    ...

# AFTER
def http_get(url, headers=None, timeout=30):
    # Added timeout parameter with default
    # Backward compatible - existing calls still work
    # New calls can specify timeout
    ...
```

---

### Step 5: Verify No Anti-Patterns (1 minute)

**Quick anti-pattern check:**

**File:** AP-Checklist-Critical.md

**Critical checks:**
- ✅ Not adding direct imports (AP-01)
- ✅ Not adding threading (AP-08)
- ✅ Not using bare except (AP-14)
- ✅ Not leaking sentinels (AP-19)
- ✅ Not skipping verification (AP-27)

**Design decision check:**

**File:** REF-ID-Directory-DEC.md

```
Does change violate:
- DEC-04: Adding threading?
- DEC-07: Exceeding 128MB?
- DEC-08: Adding subdirectories?
- DEC-21: Changing SSM pattern?
```

**If violations found:** Redesign modification

---

### Step 6: Implement Change (5-10 minutes)

**Modify each layer:**

**Gateway Layer (gateway_wrappers.py):**

```python
def interface_action_object(param1, param2, new_param=default):
    """
    Updated docstring explaining new parameter.
    
    Args:
        param1: Existing parameter
        param2: Existing parameter
        new_param: NEW - Description (default: value)
    
    Returns:
        Same or updated return description
    
    Example:
        >>> # Old usage still works
        >>> result = gateway.action_object(p1, p2)
        >>> # New usage with parameter
        >>> result = gateway.action_object(p1, p2, new_param=value)
    """
    import interface_name
    return interface_name.action_object(param1, param2, new_param)
```

**Interface Layer (interface_*.py):**

```python
def action_object(param1, param2, new_param=default):
    """
    Updated interface documentation.
    
    Args:
        param1: Type and description
        param2: Type and description
        new_param: Type and description (default: value)
    
    Returns:
        Return type and description
    """
    import module_core
    return module_core.action_object_impl(param1, param2, new_param)
```

**Core Layer (*_core.py):**

```python
def action_object_impl(param1, param2, new_param=default):
    """
    Updated implementation with new parameter.
    
    Args:
        param1: Detailed description
        param2: Detailed description
        new_param: NEW - Detailed description (default: value)
    
    Returns:
        Detailed return specification
    
    Note:
        Implementation notes about new parameter
    """
    # Implementation using new parameter
    try:
        # Logic updated to use new_param
        result = do_work(param1, param2, new_param)
        return result
    except SpecificException as e:
        raise CustomException(f"Failed: {e}")
```

**Update all affected code:**
- All three layers if signature changes
- Update tests if they exist
- Update examples in docstrings
- Update configuration if needed

---

### Step 7: Verification Protocol (LESS-15) (2 minutes)

**5-Step Checklist:**

**1. Read Complete File ✅**
- Fetched all affected files
- Read entire current versions
- Understood full context

**2. Verify SIMA Pattern ✅**
- Gateway routes to interface
- Interface routes to core
- Lazy imports maintained
- No direct cross-interface calls

**3. Check Anti-Patterns ✅**
- No AP-01: Direct imports
- No AP-08: Threading
- No AP-14: Bare except
- No AP-19: Sentinel leaks
- No AP-27: Skipped verification

**4. Verify Dependencies ✅**
- No circular imports introduced
- Dependency layers respected
- Total size still < 128MB
- Backward compatibility maintained

**5. Cite Sources ✅**
- REF-IDs referenced if relevant
- Design decisions respected
- File locations documented
- Change rationale explained

---

## 💡 COMPLETE EXAMPLE

**Scenario:** Add retry logic to HTTP requests

**Step 1: Locate**
```
Files: gateway_wrappers.py
       interface_http.py
       http_client_core.py
```

**Step 2: Fetch**
```python
# Fetch all three files completely
web_fetch("https://claude.dizzybeaver.com/src/gateway_wrappers.py")
web_fetch("https://claude.dizzybeaver.com/src/interface_http.py")
web_fetch("https://claude.dizzybeaver.com/src/http_client_core.py")
```

**Step 3: Understand**
```
Current: Simple HTTP GET/POST, no retry
Used by: HA integration, config, multiple services
Issue: Transient failures cause Lambda to fail
Need: Automatic retry with exponential backoff
```

**Step 4: Design**
```
Add parameters: max_retries=3, backoff=1.0
Maintain backward compatibility (defaults)
Implement retry in core layer only
Gateway/interface just pass parameters
```

**Step 5: Verify**
```
✅ No anti-patterns
✅ No design violations
✅ Backward compatible
✅ < 128MB (using built-in time library)
```

**Step 6: Implement**

```python
# === gateway_wrappers.py ===
def http_get(url, headers=None, timeout=30, max_retries=3, backoff=1.0):
    """
    Make HTTP GET request with automatic retry.
    
    Args:
        url: Target URL
        headers: Optional headers dict
        timeout: Request timeout in seconds (default: 30)
        max_retries: Maximum retry attempts (default: 3)
        backoff: Exponential backoff multiplier (default: 1.0)
    
    Returns:
        Response dict with status, headers, body
    
    Example:
        >>> # Simple usage (auto-retry on transient failures)
        >>> result = gateway.http_get('https://api.example.com')
        >>> # Custom retry behavior
        >>> result = gateway.http_get(url, max_retries=5, backoff=2.0)
    """
    import interface_http
    return interface_http.get(url, headers, timeout, max_retries, backoff)

# === interface_http.py ===
def get(url, headers=None, timeout=30, max_retries=3, backoff=1.0):
    """HTTP GET with retry support."""
    import http_client_core
    return http_client_core.get_impl(url, headers, timeout, max_retries, backoff)

# === http_client_core.py ===
import time

def get_impl(url, headers=None, timeout=30, max_retries=3, backoff=1.0):
    """
    HTTP GET implementation with exponential backoff retry.
    
    Retries on: 408, 429, 500, 502, 503, 504
    Does not retry: 4xx (except 408, 429)
    
    Args:
        url: Target URL
        headers: Optional headers
        timeout: Request timeout
        max_retries: Maximum attempts
        backoff: Backoff multiplier (delay = backoff * 2^attempt)
    
    Returns:
        Response dict
    
    Raises:
        HTTPError: After all retries exhausted
    """
    import urllib.request
    import urllib.error
    
    retryable_codes = {408, 429, 500, 502, 503, 504}
    
    for attempt in range(max_retries + 1):
        try:
            # Existing HTTP GET logic
            req = urllib.request.Request(url, headers=headers or {})
            with urllib.request.urlopen(req, timeout=timeout) as response:
                return {
                    'status': response.status,
                    'headers': dict(response.headers),
                    'body': response.read().decode('utf-8')
                }
        
        except urllib.error.HTTPError as e:
            # Retry on specific status codes
            if e.code in retryable_codes and attempt < max_retries:
                delay = backoff * (2 ** attempt)
                time.sleep(delay)
                continue
            raise
        
        except urllib.error.URLError as e:
            # Retry on network errors
            if attempt < max_retries:
                delay = backoff * (2 ** attempt)
                time.sleep(delay)
                continue
            raise HTTPError(f"Failed after {max_retries} retries: {e}")
```

**Step 7: Verification**
```
✅ All three layers updated
✅ Backward compatible (defaults)
✅ SIMA pattern maintained
✅ Proper error handling
✅ No anti-patterns
✅ Documented behavior
```

---

## ⚠️ COMMON MISTAKES TO AVOID

**DON'T:**
- Modify code without fetching current version
- Change only one layer (breaks SIMA)
- Assume you remember file structure
- Break backward compatibility
- Skip verification checklist
- Forget to update docstrings

**DO:**
- Always fetch complete files first (LESS-01)
- Update all affected layers
- Maintain backward compatibility
- Follow LESS-15 verification
- Update documentation
- Test the changes

---

## 🔗 RELATED FILES

**Hub:** WORKFLOWS-PLAYBOOK.md  
**File Fetching:** Workflow-11-FetchFiles.md  
**Verification:** NM06/NM06-Lessons-Operations_LESS-15.md  
**Read Complete:** NM06/NM06-Lessons-CoreArchitecture_LESS-01.md  
**Anti-Patterns:** AP-Checklist-Critical.md  
**SIMA Pattern:** NM01/NM01-Architecture-CoreArchitecture_Index.md

---

## 📊 SUCCESS METRICS

**Workflow succeeded when:**
- ✅ Complete files fetched before modification
- ✅ All affected layers updated
- ✅ SIMA pattern preserved
- ✅ Backward compatibility maintained
- ✅ LESS-15 verification passed
- ✅ Documentation updated
- ✅ No anti-patterns introduced
- ✅ Change tested and working

**Time:** 10-20 minutes for simple modifications

---

**END OF WORKFLOW**

**Lines:** ~285 (properly sized)  
**Priority:** HIGH (common operation)  
**ZAPH:** Tier 2 (frequent use)

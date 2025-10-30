# Workflow-01-AddFeature.md
**Adding New Features - Step-by-Step**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: Systematic process for adding features to SUGA-ISP Lambda

---

## 🎯 TRIGGERS

- "Can you add [feature]?"
- "I need to implement [functionality]"
- "How do I add support for [X]?"
- "Let's add a new [component]"
- "I want to extend [interface] with..."

---

## ⚡ DECISION TREE

```
User requests new feature
    ↓
Step 1: Understand Requirements
    → What problem does it solve?
    → What's the expected behavior?
    → Any constraints/requirements?
    ↓
Step 2: Check Existing Functionality
    → Search gateway functions
    → Check if already implemented
    → Look for similar patterns
    ↓
Already exists? → Show how to use + DONE
    ↓
Step 3: Design SIMA Implementation
    → Which interface? (INT-01 to INT-12)
    → Gateway function name
    → Core implementation location
    ↓
Step 4: Verify No Anti-Patterns
    → Check AP-Checklist-Critical.md
    → Verify constraints (128MB, cold start)
    → Confirm SIMA pattern followed
    ↓
Step 5: Implement All Three Layers
    → Gateway wrapper (gateway.py)
    → Interface definition (interface_*.py)
    → Core implementation (*_core.py)
    ↓
Step 6: Verification (LESS-15)
    → 5-step checklist
    → Test implementation
    → Document if needed
```

---

## 📋 STEP-BY-STEP PROCESS

### Step 1: Understand Requirements (30 seconds)

**Questions to ask:**
- What specific problem are you solving?
- What inputs does it need?
- What should it return?
- Any performance requirements?
- Is this for hot path or cold path?

**Example:**
```
User: "Add support for encrypting API tokens"
Requirement: Encrypt sensitive strings before storage
Input: String (plaintext)
Output: String (encrypted)
Constraint: Must be fast (hot path)
```

---

### Step 2: Check Existing Functionality (15 seconds)

**Search gateway first:**

**File:** gateway.py (via web_fetch)

**Look for:**
- Similar function names
- Related functionality
- Existing patterns

**Example:**
```python
# Search for: encrypt, security, token
# Found: gateway.security_encrypt_value()
# Result: Already implemented! Show usage.
```

**If found:** Provide usage example + DONE

---

### Step 3: Design SIMA Implementation (1 minute)

**Choose correct interface:**

| Feature Type | Interface | Example |
|--------------|-----------|---------|
| Caching | INT-01 CACHE | cache_set, cache_get |
| Logging | INT-02 LOGGING | log_info, log_error |
| Security | INT-03 SECURITY | encrypt, validate |
| Metrics | INT-04 METRICS | track_time, count |
| Config | INT-05 CONFIG | get_config, validate |
| Validation | INT-06 VALIDATION | validate_input |
| Persistence | INT-07 PERSISTENCE | save, load |
| Communication | INT-08 COMMUNICATION | http_get, http_post |
| Transformation | INT-09 TRANSFORMATION | transform, convert |
| Scheduling | INT-10 SCHEDULING | schedule, defer |
| Monitoring | INT-11 MONITORING | health_check |
| Error Handling | INT-12 ERROR_HANDLING | handle_error |

**Naming convention:**
```
Gateway: [interface]_[action]_[object]
Interface: [action]_[object]
Implementation: [action]_[object]_impl
```

**Example:**
```
Feature: Compress large responses
Interface: INT-09 TRANSFORMATION
Gateway: transformation_compress_response(data)
Interface: compress_response(data)
Implementation: compress_response_impl(data)
```

---

### Step 4: Verify No Anti-Patterns (30 seconds)

**Quick checks:**

**File:** AP-Checklist-Critical.md

✅ **Pass if:**
- No direct core imports (AP-01)
- No threading locks (AP-08)
- No bare except (AP-14)
- No sentinel leaks (AP-19)
- Follows verification (AP-27)

❌ **Fail if:**
- Requires > 50MB library (DEC-07)
- Needs threading (DEC-04)
- Creates subdirectories (DEC-08)
- Bypasses gateway (RULE-01)

**If any check fails:** Redesign or reject feature

---

### Step 5: Implement All Three Layers (5-10 minutes)

**Layer 1: Gateway (gateway.py or gateway_wrappers.py)**

```python
def interface_action_object(*args, **kwargs):
    """
    Brief description of what this does.
    
    Args:
        arg1: Description
        arg2: Description
    
    Returns:
        Description of return value
    
    Example:
        >>> result = gateway.interface_action_object(arg1, arg2)
    """
    # Import interface at function level (lazy)
    import interface_name
    return interface_name.action_object(*args, **kwargs)
```

**Layer 2: Interface (interface_*.py)**

```python
def action_object(*args, **kwargs):
    """
    Interface-level documentation.
    
    This is the interface definition that routes to implementation.
    
    Args:
        arg1: Type and description
        arg2: Type and description
    
    Returns:
        Return type and description
    
    Raises:
        ExceptionType: When X happens
    """
    # Import core at function level
    import module_core
    return module_core.action_object_impl(*args, **kwargs)
```

**Layer 3: Implementation (*_core.py)**

```python
def action_object_impl(*args, **kwargs):
    """
    Actual implementation logic.
    
    Implementation details and algorithm explanation.
    
    Args:
        arg1: Detailed type and usage
        arg2: Detailed type and usage
    
    Returns:
        Detailed return specification
    
    Note:
        Implementation details, performance notes, etc.
    """
    # Actual implementation here
    try:
        # Main logic
        result = do_work(args)
        return result
    except SpecificException as e:
        # Proper error handling
        raise CustomException(f"Failed to {action}: {e}")
```

---

### Step 6: Verification Protocol (LESS-15) (2 minutes)

**5-Step Checklist:**

**1. Read Complete File**
- ✅ Fetched current gateway.py
- ✅ Fetched current interface_*.py
- ✅ Fetched current *_core.py
- ✅ Understand full context

**2. Verify SIMA Pattern**
- ✅ Gateway function exists
- ✅ Interface follows pattern
- ✅ Implementation in correct core file
- ✅ Lazy imports used

**3. Check Anti-Patterns**
- ✅ No direct imports (AP-01)
- ✅ No threading (AP-08)
- ✅ No bare except (AP-14)
- ✅ No sentinel leaks (AP-19)

**4. Verify Dependencies**
- ✅ No circular imports
- ✅ Follows dependency layers
- ✅ Total size < 128MB

**5. Cite Sources**
- ✅ REF-IDs referenced
- ✅ File locations included
- ✅ Rationale explained

---

## 💡 COMPLETE EXAMPLE

**Scenario:** Add support for base64 encoding/decoding

**Step 1: Requirements**
```
Need: Encode/decode strings to base64
Input: String
Output: String (encoded or decoded)
Use case: Encoding binary data in JSON responses
```

**Step 2: Check Existing**
```python
# Search gateway.py for: base64, encode, decode
# Result: Not found - need to implement
```

**Step 3: Design**
```
Interface: INT-09 TRANSFORMATION
Gateway: transformation_base64_encode(data)
         transformation_base64_decode(data)
Interface: base64_encode(data)
          base64_decode(data)
Implementation: base64_encode_impl(data)
               base64_decode_impl(data)
```

**Step 4: Verify**
```
✅ No anti-patterns
✅ Uses built-in base64 library (no size impact)
✅ Follows SIMA pattern
✅ No threading needed
```

**Step 5: Implement**

```python
# === gateway_wrappers.py ===
def transformation_base64_encode(data: str) -> str:
    """
    Encode string to base64.
    
    Args:
        data: String to encode
    
    Returns:
        Base64-encoded string
    """
    import interface_transformation
    return interface_transformation.base64_encode(data)

def transformation_base64_decode(data: str) -> str:
    """
    Decode base64 string.
    
    Args:
        data: Base64 string to decode
    
    Returns:
        Decoded string
    """
    import interface_transformation
    return interface_transformation.base64_decode(data)

# === interface_transformation.py ===
def base64_encode(data: str) -> str:
    """Encode string to base64."""
    import utility_core
    return utility_core.base64_encode_impl(data)

def base64_decode(data: str) -> str:
    """Decode base64 string."""
    import utility_core
    return utility_core.base64_decode_impl(data)

# === utility_core.py ===
import base64

def base64_encode_impl(data: str) -> str:
    """
    Encode string to base64.
    
    Args:
        data: String to encode
    
    Returns:
        Base64-encoded string
    
    Note:
        Uses standard base64 encoding (RFC 4648)
    """
    try:
        encoded = base64.b64encode(data.encode('utf-8'))
        return encoded.decode('utf-8')
    except Exception as e:
        raise ValueError(f"Failed to encode base64: {e}")

def base64_decode_impl(data: str) -> str:
    """
    Decode base64 string.
    
    Args:
        data: Base64 string
    
    Returns:
        Decoded string
    
    Raises:
        ValueError: If input is not valid base64
    """
    try:
        decoded = base64.b64decode(data.encode('utf-8'))
        return decoded.decode('utf-8')
    except Exception as e:
        raise ValueError(f"Failed to decode base64: {e}")
```

**Step 6: Verification**
```
✅ Gateway functions added to gateway_wrappers.py
✅ Interface functions added to interface_transformation.py
✅ Implementation in utility_core.py
✅ All layers use lazy imports
✅ Proper error handling
✅ No anti-patterns violated
✅ SIMA pattern followed correctly
```

---

## ⚠️ COMMON MISTAKES TO AVOID

**DON'T:**
- Skip checking if feature already exists
- Implement only gateway without interface/core
- Use direct imports instead of lazy imports
- Add features without verification
- Forget error handling
- Skip documentation

**DO:**
- Search gateway first (save time!)
- Implement all three layers
- Use appropriate interface (INT-01 to INT-12)
- Follow LESS-15 verification
- Include proper error handling
- Add docstrings and examples

---

## 🔗 RELATED FILES

**Hub:** WORKFLOWS-PLAYBOOK.md  
**Verification:** AP-Checklist-Critical.md  
**Interfaces:** NM01/NM01-Architecture-InterfacesCore_Index.md  
**SIMA Pattern:** NM01/NM01-Architecture-CoreArchitecture_Index.md  
**Import Rules:** NM02/NM02-Dependencies-ImportRules_RULE-01.md  
**Verification Protocol:** NM06/NM06-Lessons-Operations_LESS-15.md

---

## 📊 SUCCESS METRICS

**Workflow succeeded when:**
- ✅ Feature implemented in all 3 layers
- ✅ Gateway function exists
- ✅ Interface follows pattern
- ✅ Implementation is complete
- ✅ LESS-15 verification passed
- ✅ No anti-patterns violated
- ✅ Proper error handling included
- ✅ Documentation complete

**Time:** 10-20 minutes for simple features

---

**END OF WORKFLOW**

**Lines:** ~290 (properly sized)  
**Priority:** CRITICAL (most common workflow)  
**ZAPH:** Tier 1 (always in hot path)

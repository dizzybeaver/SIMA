# AWS-APIGateway-Transformation_AWS-LESS-10.md

**Version:** 1.0.0  
**Date:** 2025-11-01  
**Purpose:** API boundary data transformation strategies

**REF-ID:** AWS-LESS-10  
**Category:** AWS API Gateway  
**Type:** LESS (Lesson Learned)  
**Priority:** 🟠 High  
**Status:** Active  
**Source:** AWS Serverless Developer Guide (Genericized)

---

## Summary

Transform data at API boundaries using pure functions that reshape structure without making decisions. Transformation = reshaping. Business logic = deciding. Never mix.

**Core Principle:** Separate concerns. Transformation changes format. Logic changes meaning.

---

## Transformation vs Business Logic

**Pure Transformation (✅ Good):**
```
Input:  {"user_name": "john", "join_date": "2025-01-15"}
Output: {"userName": "john", "joinDate": "2025-01-15"}

Result: Structure changed, data unchanged
        No decisions, no external calls
        Deterministic
```

**Transformation + Logic (❌ Bad):**
```
Input:  {"user_name": "john", "join_date": "2025-01-15"}
Output: {"userName": "john", "memberStatus": "premium"}

Result: Added business logic (premium calculation)
        Made decision based on external state
        Non-deterministic
```

---

## Layer Separation

**Transformation Layer (Boundary):**
- ✅ Reshape structure, rename fields, convert formats
- ✅ Type coercion (string → int)
- ❌ Business decisions, validation, external calls

**Business Logic Layer (Application):**
- ✅ Validation, authorization, calculations, decisions
- ❌ Format conversion, field renaming

---

## Implementation Strategies

**Strategy 1: Declarative Templates**

**When:** Simple mappings, configuration-driven systems, multiple API versions

```json
{
  "mapping": {
    "userName": "user_name",
    "userEmail": "email_address"
  },
  "conversions": {
    "createdAt": "iso8601_to_unix"
  }
}
```

**Pros:** No deployment for changes, version-controlled separately  
**Cons:** Limited logic, debugging harder

**Strategy 2: Imperative Code**

**When:** Complex transformations, type safety critical, performance matters

```python
def transform_user(api_user: dict) -> dict:
    """Pure transformation - deterministic."""
    return {
        "userName": api_user["user_name"],
        "userEmail": api_user["email_address"],
        "createdAt": convert_timestamp(api_user["join_date"])
    }
```

**Pros:** Full language power, familiar debugging  
**Cons:** Requires deployment, temptation to mix concerns

**Strategy 3: Hybrid**

Use templates for simple cases, code for complex cases.

---

## Best Practices

**1. Keep Pure**

```python
# ✅ Good: Pure
def transform(data):
    return {"id": data["user_id"], "name": data["user_name"]}

# ❌ Bad: Impure (external call)
def transform(data):
    return {"id": data["user_id"], "isPremium": check_status(data)}
```

**2. Test Independently**

```python
def test_transformation():
    input_data = {"user_name": "john", "email": "john@example.com"}
    expected = {"userName": "john", "userEmail": "john@example.com"}
    assert transform_user(input_data) == expected
```

**3. Document Purpose**

```markdown
## User Transformation v2
**Purpose:** Map internal model to external API contract
**History:** v1 (2024-01), v2 (2025-01) added premium field
**Systems:** Mobile v2.0+, Web v3.1+
**Remove When:** All clients on v3 API (est. 2026-Q2)
```

**4. Version Transformations**

```
transformation/
  ├── v1_to_v2.py
  ├── v2_to_v3.py
  ├── current.py
  └── deprecated/
```

**5. Minimize Complexity**

**Red flags:** Nested conditionals, external calls, >50 lines, complex state

**Response:** Should backend change instead? Is this really transformation?

---

## Anti-Patterns

**❌ Business Logic in Transformation**

```python
# Problem
def transform(user):
    return {
        "userName": user["name"],
        "isPremium": user["orders"] > 100  # ❌ Business logic!
    }

# Solution: Separate layers
def calculate_status(user):
    """Business layer."""
    return {"isPremium": user["orders"] > 100}

def transform(enriched_user):
    """Transformation layer."""
    return {"userName": enriched_user["name"],
            "isPremium": enriched_user["isPremium"]}
```

**❌ Stateful Transformations**

```python
# Problem
count = 0  # ❌ Global state
def transform(user):
    global count
    count += 1
    return {"userName": user["name"]}

# Solution: Pure function
def transform(user):
    return {"userName": user["name"]}
```

**❌ Complex Nested Logic**

```python
# Problem
def transform(order):
    if order["type"] == "premium":
        if order["status"] == "active":
            return format_premium_active(order)
        # ... more nesting

# Solution: Lookup table
TRANSFORMERS = {
    ("premium", "active"): format_premium_active,
    ("basic", "active"): format_basic_active,
}

def transform(order):
    key = (order["type"], order["status"])
    transformer = TRANSFORMERS.get(key, format_default)
    return transformer(order)
```

---

## Common Scenarios

**Legacy System Integration**

```python
def to_legacy_xml(modern_data: dict) -> str:
    """Pure transformation to legacy format."""
    return f"""<User>
        <ID>{modern_data['userId']}</ID>
        <Name>{modern_data['displayName']}</Name>
    </User>"""

# Business logic separate
enriched = process_user(raw_data)  # Business layer
legacy_xml = to_legacy_xml(enriched)  # Transformation
```

**Multiple API Versions**

```python
def transform_to_v1(user: dict) -> dict:
    """v1 API (deprecated)."""
    return {"id": user["userId"], "name": user["displayName"]}

def transform_to_v2(user: dict) -> dict:
    """v2 API (current)."""
    return {
        "userId": user["userId"],
        "displayName": user["displayName"],
        "email": user["email"]
    }
```

---

## Why This Matters

**Maintainability:**
- Pure transformations = easy to test
- Mixed concerns = brittle, hard to debug
- Separate layers = clear responsibilities

**Scalability:**
- Pure functions = parallelizable
- Stateless = horizontally scalable
- Deterministic = predictable behavior

**Flexibility:**
- Transformation isolated = easy API versioning
- Business logic isolated = reusable across APIs
- Clear boundaries = independent evolution

---

## When to Apply

**Design Phase:**
- ✅ Define transformation layer boundaries
- ✅ Identify pure transformation operations
- ✅ Separate business logic clearly

**Code Review:**
- 🔍 Check for external calls in transformations
- 🔍 Verify transformations are deterministic
- 🔍 Look for business logic creep

**Refactoring:**
- 📊 Extract business logic from transformations
- 📊 Simplify complex transformation logic
- 📊 Add tests for pure transformations

---

## Cross-References

**AWS Patterns:**
- AWS-APIGateway-ProxyIntegration_AWS-LESS-09.md - Where to transform (proxy vs non-proxy)

**Project Maps:**
- /sima/entries/decisions/architecture/DEC-05.md - Sentinel sanitization at boundaries
- /sima/entries/core/ARCH-SUGA_Single Universal Gateway Architecture.md - Gateway transformation patterns

---

## Keywords

transformation, data-mapping, API-boundary, pure-functions, separation-of-concerns, declarative, imperative

---

**Location:** `/sima/aws/api-gateway/`  
**Total Lines:** 255 (≤400 for SIMAv4)  
**SIMAv4 Compliant:** ✅

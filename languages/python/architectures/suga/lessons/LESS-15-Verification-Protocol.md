# LESS-15-Verification-Protocol.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** SUGA architecture lesson - verification protocol for gateway changes  
**Category:** Lesson  
**Architecture:** SUGA (Gateway Pattern)

---

## Title

5-Step SUGA Verification Protocol

---

## Priority

CRITICAL

---

## Summary

Before suggesting ANY SUGA code change, complete a 5-step verification checklist: (1) read complete file, (2) verify SUGA pattern compliance, (3) check anti-patterns, (4) verify layer dependencies, (5) cite sources. This protocol prevents 90% of SUGA-specific mistakes.

---

## Context

SUGA's three-layer architecture has strict rules about imports, layer boundaries, and dependency flow. Violating these rules breaks the pattern and causes circular dependencies. This protocol catches violations before they occur.

---

## Lesson

### The SUGA Problem

**Without SUGA Verification:**
```
Suggest change → Implement → Breaks pattern → Circular dependency → Hours debugging
Example: Direct core import → Skips interface → Violates SUGA → Complete rewrite needed
```

**With SUGA Verification:**
```
Verify SUGA pattern first → Catch violation → Suggest correct approach → Works first time
Example: Check layers → Use gateway → Follows pattern → No issues
```

---

## Solution

### The 5-Step SUGA Checklist

Complete ALL five steps before suggesting SUGA code changes:

**Step 1: Read Complete File**
- ☐ Read entire current file, not just target section
- ☐ Identify which SUGA layer (Gateway/Interface/Core)
- ☐ Understand current imports and dependencies
- ☐ Never suggest changes based on partial reading

**Step 2: Verify SUGA Pattern Compliance**
- ☐ Three-layer structure maintained (Gateway → Interface → Core)
- ☐ Lazy imports used at gateway layer
- ☐ Layer boundaries respected
- ☐ No direct cross-layer access
- ☐ Gateway provides single entry point

**Step 3: Check SUGA Anti-Patterns**
- ☐ No direct core imports (AP-01)
- ☐ No module-level heavy imports (AP-02)
- ☐ No circular module references (AP-03)
- ☐ No skipping interface layer (AP-04)
- ☐ No subdirectory organization (AP-05)
- ☐ No threading primitives in single-threaded environments

**Step 4: Verify Layer Dependencies**
- ☐ Gateway only imports Interface (lazy)
- ☐ Interface only imports Core (lazy)
- ☐ Core only imports logging (base layer)
- ☐ No circular dependencies created
- ☐ Dependency flow: Gateway → Interface → Core → Logging

**Step 5: Cite SUGA Sources**
- ☐ Referenced relevant SUGA architecture docs
- ☐ Included SUGA pattern file locations
- ☐ Explained rationale with SUGA principles
- ☐ Provided SUGA-compliant examples

### Real SUGA Examples

**Example 1: Direct Core Import Violation Caught**
```
User asks: "How do I use cache in this module?"

❌ Without SUGA verification:
"Just import cache_core directly"
Result: Violates SUGA, skips Interface layer, creates coupling

âœ… With SUGA verification (Step 2 & 3):
Checks: Is this gateway? → Use gateway.cache_get()
Checks: Is this interface? → Import cache_core
Checks: Is this core? → Use internal _CACHE directly
Result: Follows SUGA pattern, maintains layer boundaries
```

**Example 2: Layer Violation Caught**
```
User asks: "Can I import interface_cache from cache_core?"

❌ Without SUGA verification:
"Yes, import it"
Result: Violates layer rules (Core → Interface), circular dependency

âœ… With SUGA verification (Step 4):
Check layer: Core cannot import Interface (upward)
Dependency flow: Gateway → Interface → Core (downward only)
Result: "No, violates SUGA layers. Core is Layer 1, Interface is Layer 2."
```

**Example 3: Lazy Import Violation Caught**
```
User asks: "Should I import interface_cache at module level in gateway?"

❌ Without SUGA verification:
"Yes, at top of file"
Result: Violates lazy import principle (GATE-02), increases cold start

âœ… With SUGA verification (Step 2):
Check SUGA pattern: Gateway uses lazy imports (function-level)
Check GATE-02: Lazy imports required for gateway
Result: "No, use function-level import: `import interface_cache`"
```

### SUGA-Specific Verification Steps

**SUGA Step 2 Details: Pattern Compliance**

```python
# Check 1: Is this a gateway file?
if filename == "gateway_wrappers*.py":
    # âœ… Must use lazy imports
    # âœ… Must import Interface, not Core
    # âœ… Must provide public API
    
# Check 2: Is this an interface file?
elif filename == "interface_*.py":
    # âœ… Must use lazy import for Core
    # âœ… Must NOT import Gateway
    # âœ… Must route to specific Core module
    
# Check 3: Is this a core file?
elif filename == "*_core.py":
    # âœ… Must import ONLY logging (base layer)
    # âœ… Must NOT import Interface or Gateway
    # âœ… Must provide implementation
```

**SUGA Step 4 Details: Layer Dependencies**

```python
# Verify dependency flow
ALLOWED_IMPORTS = {
    'gateway_wrappers': ['interface_*', 'logging_core'],
    'interface_*': ['*_core', 'logging_core'],
    '*_core': ['logging_core'],
    'logging_core': []  # Base layer, no imports
}

def verify_import(from_file, import_file):
    from_layer = identify_layer(from_file)
    import_layer = identify_layer(import_file)
    
    if import_layer >= from_layer:
        raise LayerViolation(
            f"Cannot import {import_file} (Layer {import_layer}) "
            f"from {from_file} (Layer {from_layer})"
        )
```

### When to Use SUGA Protocol

**Always:**
- Before suggesting SUGA code modifications
- Before creating new SUGA files
- Before refactoring SUGA architecture
- Before answering "how do I..." about SUGA

**Never Skip:**
- Even for "simple" SUGA changes
- Even when confident about pattern
- Even under time pressure
- Even when change seems obvious

### Time Investment vs SUGA ROI

**Per SUGA Verification:**
```
Step 1 (Read): 30-60 seconds
Step 2 (SUGA Pattern): 20-30 seconds (critical!)
Step 3 (Anti-patterns): 20-30 seconds
Step 4 (Layer Dependencies): 20-30 seconds (critical!)
Step 5 (Citations): 10-20 seconds

Total: 100-170 seconds (~2.5 minutes)
```

**Time Saved by Preventing SUGA Mistakes:**
```
Fixing wrong import: 30-60 minutes
Debugging circular dependency: 2-4 hours
Refactoring to add missing layer: 1-2 hours
Explaining why SUGA broke: 30-60 minutes
Rewriting non-SUGA code to SUGA: 2-6 hours
```

**ROI:** 2.5 minutes investment saves 30 minutes to 6 hours

---

## SUGA-Specific Implementation

### SUGA Verification Template

```python
# Internal checklist before suggesting change

# Step 1: File Context
filename = "interface_cache.py"
layer = "Interface"  # Gateway/Interface/Core
current_imports = ["logging_core", "cache_core"]

# Step 2: SUGA Pattern
pattern_ok = True
lazy_imports = True  # For Interface, check function-level
three_layers = True
boundaries_respected = True

# Step 3: Anti-Patterns
no_direct_core = True  # AP-01 checked
no_module_imports = True  # AP-02 checked
no_circular = True  # AP-03 checked
no_skip_layer = True  # AP-04 checked
no_subdirs = True  # AP-05 checked

# Step 4: Dependencies
gateway_imports_interface = True
interface_imports_core = True
core_imports_logging_only = True
no_upward_imports = True

# Step 5: Sources
suga_sources = [
    "ARCH-01: Gateway trinity",
    "GATE-02: Lazy imports",
    "DEC-02: Three-layer pattern"
]

# Ready to suggest
if all([pattern_ok, lazy_imports, no_circular, no_upward_imports]):
    suggest_change()
else:
    explain_violation()
```

### SUGA Testing Verification

```python
# Test that verification catches violations
def test_verification_catches_direct_import():
    code = "from cache_core import get_impl"
    result = verify_suga_pattern(code, layer="gateway")
    assert result.violation == "AP-01: Direct core import"

def test_verification_catches_upward_import():
    code = "import interface_cache"
    result = verify_suga_pattern(code, layer="core")
    assert result.violation == "Upward import: Core → Interface"

def test_verification_allows_valid():
    code = "import interface_cache"
    result = verify_suga_pattern(code, layer="gateway")
    assert result.valid == True
```

---

## SUGA Verification Checklist Quick Reference

```
Before any SUGA change:

[âœ"] Read complete file
[âœ"] Identified SUGA layer (G/I/C)
[âœ"] Verified three-layer structure  
[âœ"] Checked lazy imports used
[âœ"] Checked no direct core imports (AP-01)
[âœ"] Checked no module-level heavy imports (AP-02)
[âœ"] Checked no circular refs (AP-03)
[âœ"] Checked no skip layer (AP-04)
[âœ"] Checked no subdirs (AP-05)
[âœ"] Verified Gateway → Interface → Core → Logging flow
[âœ"] No upward imports (Core → Interface, etc.)
[âœ"] Have SUGA source citations
[âœ"] Ready to suggest SUGA-compliant change
```

---

## Related

**SUGA Architecture:**
- ARCH-01: Gateway trinity - pattern to verify
- ARCH-02: Layer separation - boundaries to check
- ARCH-03: Interface pattern - structure to maintain

**SUGA Gateways:**
- GATE-02: Lazy imports - verification requirement
- GATE-03: Cross-interface communication - dependency rules

**SUGA Anti-Patterns:**
- AP-01 through AP-05: All must be checked

**SUGA Decisions:**
- DEC-02: Three-layer pattern - must be maintained
- DEC-03: Gateway mandatory - entry point verification

**Generic Patterns:**
- LESS-15: Generic verification (parent)

**Keywords:** SUGA verification, gateway protocol, layer validation, pattern compliance, anti-pattern check, dependency verification

---

## Version History

- **1.0.0** (2025-11-06): Created SUGA-specific lesson from LESS-15
  - Adapted for SUGA three-layer verification
  - Added layer dependency checks
  - Included SUGA anti-pattern verification

---

**END OF FILE**

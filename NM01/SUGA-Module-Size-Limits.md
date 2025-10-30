# SUGA-Module-Size-Limits.md

# SUGA Principle: Module Size Limits and Code Atomization

**Project:** SUGA-ISP Lambda Execution Engine
**Category:** Core Architecture Principles
**Priority:** 🔴 CRITICAL
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Module size limits in SUGA architecture (400 lines for core, 200 for interfaces) aren't arbitrary - they prevent god objects, enable fast comprehension, ensure focused responsibility, and parallel the same atomization principle used in SIMA v3 neural maps.

---

## Context

The SUGA (Single Universal Gateway Architecture) pattern emphasizes atomization at the code level, just as SIMA v3 does for knowledge management. This principle documents why module size limits matter and how they're enforced.

**Shared Philosophy with SIMA v3:**
Both architectures believe in **atomization**: breaking systems into smallest meaningful units that can be independently retrieved, updated, and understood.

---

## Content

### Module Size Guidelines

| Module Type | Max Lines | Typical Lines | Purpose |
|-------------|-----------|---------------|---------|
| **Core Module** | 400 | 200-300 | Single interface implementation |
| **Interface Router** | 200 | 100-150 | Dispatch + routing logic |
| **Gateway** | 300 | 150-250 | Central dispatcher |
| **Utility** | 300 | 150-200 | Helper functions |
| **Extension Facade** | 200 | 100-150 | Pure delegation layer |

### Why These Limits?

#### 1. Prevents God Objects

**Problem:**
```python
# ❌ God object (2000+ lines)
class Everything:
    def handle_cache(self): ...
    def handle_logging(self): ...
    def handle_http(self): ...
    def handle_metrics(self): ...
    def handle_security(self): ...
    # ... 50 more methods
```

**Solution:**
```python
# ✅ Focused modules (200-300 lines each)
# cache_core.py
def get(key): ...
def set(key, value): ...
def delete(key): ...

# logging_core.py  
def info(msg): ...
def error(msg): ...
def debug(msg): ...
```

#### 2. Enables Fast Comprehension

**Human Cognitive Limits:**
- Working memory: ~7 items
- 300-line module: Fits in one mental model
- 2000-line module: Impossible to hold in mind

**Real Impact:**
```
Understanding cache_core.py (280 lines):
├─ Read entire file: 10 minutes
├─ Understand all functions: Clear
├─ See all relationships: Visible
└─ Confidence to modify: High

Understanding everything.py (2000 lines):
├─ Read entire file: 60+ minutes
├─ Understand all functions: Overwhelming
├─ See all relationships: Hidden
└─ Confidence to modify: Low (fear of breaking)
```

#### 3. Ensures Single Responsibility

**One Module = One Purpose:**

```
✅ Good (focused):
cache_core.py       - Cache operations only
logging_core.py     - Logging operations only
security_core.py    - Security operations only

❌ Bad (mixed):
infrastructure.py   - Cache + logging + security + metrics
```

**Benefits:**
- Easy to test (one concern per test suite)
- Easy to modify (changes are localized)
- Easy to understand (clear purpose)
- Easy to reuse (focused functionality)

#### 4. Facilitates Parallel Development

**Team Collaboration:**
```
With size limits:
Developer A: Modifies cache_core.py (250 lines)
Developer B: Modifies logging_core.py (220 lines)
Developer C: Modifies security_core.py (180 lines)
Result: No conflicts, parallel work

Without size limits:
Developer A: Modifies infrastructure.py (2000 lines)
Developer B: Modifies infrastructure.py (2000 lines)  
Developer C: Modifies infrastructure.py (2000 lines)
Result: Merge conflicts, sequential work required
```

### Enforcement Strategies

#### 1. Code Review Checks

**Checklist:**
```
When reviewing code:
[ ] Module under size limit?
[ ] If over, can it be split?
[ ] Clear single responsibility?
[ ] Minimal cross-module coupling?
```

#### 2. Automated Linting

**Example `.pylintrc` rules:**
```ini
[DESIGN]
max-module-lines=400          # Core modules
max-method-lines=50           # Individual methods
max-function-lines=100        # Individual functions
```

#### 3. Refactoring Triggers

**When to split a module:**
```
Trigger 1: Module > 400 lines
Action: Extract focused submodules

Trigger 2: Module has multiple responsibilities
Action: Split by responsibility (cache vs metrics)

Trigger 3: Module difficult to test
Action: Extract testable units

Trigger 4: Team says "this file is too big"
Action: Listen and refactor
```

### Real Examples from SUGA-ISP

#### Good: Atomized Modules

```python
# cache_core.py (280 lines)
"""Cache operations implementation."""
# Focused, single responsibility, under limit

# logging_core.py (220 lines)
"""Logging operations implementation."""
# Focused, single responsibility, under limit

# security_core.py (180 lines)
"""Security operations implementation."""
# Focused, single responsibility, under limit
```

#### Prevented: Monolithic Module

```python
# ❌ What we avoided:
# infrastructure_core.py (2000+ lines)
"""All infrastructure in one file."""
# Would violate size limit
# Would mix responsibilities
# Would be unmaintainable
```

### Parallel to SIMA v3

**Same Principle, Different Domain:**

| Aspect | SIMA v3 (Neural Maps) | SUGA (Code) |
|--------|----------------------|-------------|
| **Unit Size** | 200 lines max | 400 lines max |
| **Purpose** | One concept | One responsibility |
| **Problem Solved** | Truncation, slow retrieval | God objects, complexity |
| **Retrieval** | Direct file access | Clear module boundaries |
| **Updates** | Atomic per concept | Atomic per module |
| **Team** | Clear ownership | Parallel development |

**Cross-Reference:**
- See **ARCH-09** in SIMA v3 neural maps for parallel principle in knowledge management
- Both share atomization philosophy: small, focused, independent units

---

## Related Topics

- **ARCH-09**: File Size Limits and Atomization (SIMA v3 parallel principle)
- **ARCH-01**: Gateway Trinity (three-file gateway structure)
- **DEC-08**: Flat File Structure (prefer flat over nested)
- **LESS-01**: Gateway Pattern Prevents Problems
- **DEC-01**: SUGA Pattern Choice (architectural foundation)

---

## Keywords

module size limits, atomization, god objects, single responsibility, code organization, SUGA principle, maintainability

---

## Integration Notes

**This principle should be:**
1. Referenced in code review checklists
2. Enforced via linting rules
3. Taught during onboarding
4. Monitored during refactoring

**Links to SIMA v3:**
- ARCH-09 documents same principle for neural maps
- Both use atomization to prevent complexity
- Cross-referenced in metadata

---

## Version History

- **2025-10-24**: Created - Documents SUGA module size limits, parallels ARCH-09

---

**File:** `SUGA-Module-Size-Limits.md`  
**Location:** Documentation/Architecture/  
**End of Document**

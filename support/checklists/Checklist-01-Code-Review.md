# File: Checklist-01-Code-Review.md

**REF-ID:** CHK-01  
**Version:** 1.0.0  
**Category:** Support Tools  
**Type:** Verification Checklist  
**Purpose:** Pre-commit code review checklist for SUGA architecture

---

## 📋 OVERVIEW

Use this checklist before committing code to ensure SUGA compliance, quality standards, and architectural integrity.

**Time to complete:** 5-10 minutes  
**Frequency:** Every code change  
**Prerequisite:** Code implementation complete

---

## 🏗️ ARCHITECTURE COMPLIANCE

### SUGA Structure
- [ ] **3-layer structure maintained** (Gateway → Interface → Core)
- [ ] **Core functions are private** (_prefix naming)
- [ ] **Interface functions are public** (no underscore)
- [ ] **Gateway lazy imports** (imports inside functions, not module level)
- [ ] **No cross-layer violations** (no Interface → Gateway imports)

**Reference:** ARCH-01 (SUGA Pattern), GATE-01 (Gateway Pattern)

### Import Rules
- [ ] **All imports via gateway** (no direct core/interface imports from application)
- [ ] **Lazy imports in gateway** (inside function definitions)
- [ ] **No circular dependencies** (gateway doesn't import interfaces at module level)
- [ ] **Interface imports core inside functions** (lazy loading)
- [ ] **No cross-interface imports** (interfaces don't import other interfaces)

**Reference:** RULE-01, GATE-03 (Lazy Loading), GATE-04 (Cross-Interface Rule)

---

## 🐍 PYTHON STANDARDS

### Code Quality
- [ ] **PEP 8 compliant** (formatting, naming, structure)
- [ ] **Type hints present** (function signatures, return types)
- [ ] **Docstrings complete** (purpose, parameters, returns)
- [ ] **No bare except clauses** (specific exception types)
- [ ] **Meaningful variable names** (descriptive, not cryptic)

**Reference:** LANG-PY-01 (PEP 8), LANG-PY-06 (Type Hints)

### Function Design
- [ ] **Single responsibility** (function does one thing)
- [ ] **Return value sanitized** (no sentinel objects across boundaries)
- [ ] **Parameters validated** (check types, ranges, requirements)
- [ ] **Error handling present** (try/except with specific exceptions)
- [ ] **No side effects documented** (or properly documented if necessary)

**Reference:** LANG-PY-04 (Function Design), AP-05 (Exception Handling)

---

## ⚠️ RED FLAGS CHECK

### Critical Violations
- [ ] **NO threading locks** (Lambda is single-threaded)
- [ ] **NO direct core imports** (always via gateway)
- [ ] **NO bare except clauses** (catch specific exceptions)
- [ ] **NO sentinel objects crossing boundaries** (sanitize at router)
- [ ] **NO heavy libraries** (check import size vs 128MB limit)
- [ ] **NO new subdirectories** (flat structure except home_assistant/)
- [ ] **NO global mutable state** (causes memory leaks)

**Reference:** DEC-04 (Threading), AP-08 (Locks), BUG-03 (Memory Leaks)

---

## 🔍 INTERFACE COMPLIANCE

### Interface Layer Checks
- [ ] **Function exported via gateway** (entry in LAZY_IMPORTS)
- [ ] **Signature gateway-friendly** (Dict params, Dict returns preferred)
- [ ] **Dependencies declared** (if L1+, dependencies on lower layers only)
- [ ] **No cross-interface calls** (use utility layer for shared logic)
- [ ] **Return values sanitized** (no internal sentinels exposed)

**Reference:** INT-## entries, Interface Dependency Layers

### Documentation Requirements
- [ ] **Function in interface catalog** (INT-## entry updated)
- [ ] **Usage example provided** (working code example)
- [ ] **Parameters documented** (types, requirements, defaults)
- [ ] **Return format specified** (structure, error cases)
- [ ] **Performance characteristics noted** (if measured)

**Reference:** INT-## Function Catalogs

---

## 🎯 FUNCTIONAL REQUIREMENTS

### Implementation Quality
- [ ] **Feature requirements met** (all acceptance criteria satisfied)
- [ ] **Edge cases handled** (null, empty, invalid inputs)
- [ ] **Error messages helpful** (actionable, not cryptic)
- [ ] **Logging appropriate** (errors logged, debug info available)
- [ ] **Performance acceptable** (no obvious bottlenecks)

### Testing Coverage
- [ ] **Unit tests written** (core logic tested)
- [ ] **Integration tests written** (via gateway tested)
- [ ] **Edge cases tested** (boundary conditions)
- [ ] **Error paths tested** (exception handling verified)
- [ ] **Tests passing** (all tests green)

**Reference:** Testing best practices

---

## 📝 DOCUMENTATION

### Code Documentation
- [ ] **Inline comments for complex logic** (why, not what)
- [ ] **Docstrings complete** (all public functions)
- [ ] **Type hints accurate** (match actual types)
- [ ] **TODO/FIXME with context** (if any temporary code)
- [ ] **Magic numbers explained** (constants documented)

### External Documentation
- [ ] **Neural map entry updated** (INT-## or NMP-## as needed)
- [ ] **Quick index updated** (if new searchable pattern)
- [ ] **Cross-references added** (related entries linked)
- [ ] **Migration guide** (if breaking change)
- [ ] **Changelog entry** (if versioned component)

**Reference:** WF-05 (Create NMP Entry)

---

## 🚀 DEPLOYMENT READINESS

### Pre-Deployment Checks
- [ ] **No debug code** (print statements removed)
- [ ] **No commented-out code** (unless documented TODO)
- [ ] **No hardcoded credentials** (use environment variables)
- [ ] **No local file paths** (environment-agnostic paths)
- [ ] **Environment variables documented** (if new ones added)

### Lambda-Specific
- [ ] **Cold start optimized** (lazy imports, minimal module-level)
- [ ] **Memory efficient** (no unnecessary data retention)
- [ ] **Timeout appropriate** (execution time reasonable)
- [ ] **Layer size acceptable** (under 128MB limit)
- [ ] **Dependencies minimal** (only required packages)

**Reference:** ARCH-04 (ZAPH), NMP01-LEE-16 (Fast Path)

---

## 🎓 USAGE GUIDE

### When to Use

**Required:**
- Before every commit
- Before pull request
- Before deployment

**Optional:**
- During development (continuous verification)
- During code review (reviewer checklist)
- During refactoring (ensure no regressions)

### How to Use

1. **Complete implementation**
2. **Open this checklist**
3. **Go through each section systematically**
4. **Fix any unchecked items**
5. **Re-verify after fixes**
6. **Only commit when all checked**

### Severity Levels

**🔴 Critical (Must Fix):**
- RED FLAGS section
- SUGA Structure violations
- Import Rules violations

**🟡 High Priority (Should Fix):**
- Python Standards violations
- Interface Compliance issues
- Testing Coverage gaps

**🟢 Medium Priority (Nice to Have):**
- Documentation improvements
- Code quality enhancements
- Performance optimizations

---

## 📊 QUICK REFERENCE

### Most Common Issues

1. **Direct core imports** (RULE-01 violation)
   - Fix: Import via gateway instead

2. **Module-level imports in gateway** (GATE-03 violation)
   - Fix: Move imports inside functions

3. **Bare except clauses** (AP-05 violation)
   - Fix: Catch specific exception types

4. **Missing type hints** (LANG-PY-06 violation)
   - Fix: Add type hints to signature

5. **No tests** (testing requirement)
   - Fix: Write unit and integration tests

### Quick Check Commands

```bash
# PEP 8 check
flake8 src/

# Type check
mypy src/

# Test coverage
pytest --cov=src tests/

# Import analysis
python -m scripts.analyze_imports
```

---

## ✅ SIGN-OFF

**Code Reviewer:**
- [ ] All checklist items verified
- [ ] Code quality acceptable
- [ ] Documentation complete
- [ ] Tests passing
- [ ] Ready for commit

**Date:** _______________  
**Reviewer:** _______________  
**Notes:** _______________

---

## 🔗 RELATED RESOURCES

**Architecture:**
- ARCH-01: SUGA Pattern
- GATE-01 to GATE-05: Gateway Patterns
- INT-## entries: Interface Standards

**Standards:**
- LANG-PY-01 to LANG-PY-08: Python Standards
- LESS-15: SUGA Verification

**Anti-Patterns:**
- AP-05: Exception Handling
- AP-08: Threading Locks
- RED FLAGS: Critical violations

**Workflows:**
- WF-01: Add Feature
- WF-02: Debug Issue
- WF-03: Update Interface

---

**END OF CHECKLIST-01**

**Related checklists:** CHK-02 (Deployment Readiness), CHK-03 (Documentation Quality)

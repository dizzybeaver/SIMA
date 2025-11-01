# TOOL-04: Verification Protocol

**REF-ID:** TOOL-04  
**Category:** Tool  
**Type:** Quality Gate Process  
**Purpose:** Comprehensive pre-deployment verification checklist  
**Version:** 1.0.0  
**Last Updated:** 2025-11-01

---

## ðŸŽ¯ PROTOCOL PURPOSE

**What:** Complete verification process with quality gates and sign-off steps

**When to Use:**
- Before committing code
- Before merging PR
- Before deployment
- After major refactor
- Pre-release validation

**How It Helps:**
- Catches issues before production
- Ensures consistent quality
- Reduces rollback rate
- Documents verification completion

---

## 🔄 VERIFICATION PROCESS

### Process Overview

```
â"Œâ"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"
â"‚ STAGE 1: CODE REVIEW │
â""â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"¬â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€
            â"‚ âœ… PASS
            â†"
â"Œâ"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"
â"‚ STAGE 2: ARCHITECTURE  │
â""â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"¬â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€
            â"‚ âœ… PASS
            â†"
â"Œâ"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"
â"‚ STAGE 3: TESTING      │
â""â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"¬â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€
            â"‚ âœ… PASS
            â†"
â"Œâ"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"
â"‚ STAGE 4: DOCUMENTATION │
â""â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"¬â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€
            â"‚ âœ… PASS
            â†"
â"Œâ"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"
â"‚ STAGE 5: SIGN-OFF     │
â""â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"¬â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€â"€
            â"‚ âœ… APPROVED
            â†"
      DEPLOY READY
```

---

## ðŸ" STAGE 1: CODE REVIEW

**Duration:** 10-30 minutes  
**Owner:** Developer + Reviewer  
**Required:** âœ… YES for all changes

### 1.1 Anti-Pattern Check

**Use TOOL-03 checklist:**

**Critical (MUST PASS):**
- [ ] No direct core imports
- [ ] No threading primitives in Lambda
- [ ] No sentinel object leakage
- [ ] No bare except clauses
- [ ] No circular dependencies

**High Priority (SHOULD PASS):**
- [ ] Fetched files before modifying
- [ ] Metrics batched appropriately
- [ ] Modules under 800 lines
- [ ] External input validated
- [ ] Errors include context

**Result:** ❌ FAIL any critical item = STOP  
**Action:** Fix violations, return to 1.1

---

### 1.2 Code Quality

**Standards:**
- [ ] Follows Python style guide (PEP 8)
- [ ] Consistent naming conventions (snake_case)
- [ ] No magic numbers (use constants)
- [ ] Functions under 50 lines
- [ ] Nesting under 3 levels
- [ ] No dead/commented code
- [ ] No debugging print statements

**Result:** 🟡 2+ violations = REVIEW needed  
**Action:** Address major issues

---

### 1.3 Security Check

**Requirements:**
- [ ] No hardcoded credentials
- [ ] No secrets in code
- [ ] Input sanitized at boundaries
- [ ] SQL injection prevented (parameterized queries)
- [ ] XSS prevention (output encoding)
- [ ] CSRF protection (if applicable)

**Result:** ❌ FAIL any security item = STOP  
**Action:** Fix immediately, security review required

---

**STAGE 1 GATE:**  
âœ… All critical anti-patterns fixed  
âœ… No security violations  
âœ… Code quality acceptable  

**Sign-Off:**
- Developer: __________  
- Reviewer: __________  
- Date: __________

---

## ðŸ—ï¸ STAGE 2: ARCHITECTURE REVIEW

**Duration:** 15-45 minutes  
**Owner:** Developer + Architect  
**Required:** âœ… YES for architectural changes

### 2.1 SUGA Pattern Compliance

**Gateway Layer:**
- [ ] All external access through gateway functions
- [ ] Gateway functions wrap interface/core properly
- [ ] No business logic in gateway
- [ ] Error handling at gateway

**Interface Layer:**
- [ ] Clear interface abstractions
- [ ] No direct core access from other modules
- [ ] Consistent interface patterns
- [ ] Proper error propagation

**Core Layer:**
- [ ] Pure business logic
- [ ] No external dependencies
- [ ] Testable in isolation
- [ ] Clear responsibilities

**Result:** ❌ FAIL pattern compliance = REFACTOR  
**Citations:** ARCH-01, LESS-03, LESS-04

---

### 2.2 Import Structure

**Validation:**
```bash
# Run import validation
python scripts/validate_imports.py src/

# Expected output:
# âœ… All imports valid
# âœ… No circular dependencies
# âœ… Gateway pattern maintained
```

**Manual Checks:**
- [ ] No imports skip gateway
- [ ] No circular dependencies
- [ ] Correct import order (stdlib, third-party, local)
- [ ] No wildcard imports

**Result:** ❌ FAIL import rules = REFACTOR  
**Citations:** RULE-01, RULE-02, RULE-03

---

### 2.3 Dependency Audit

**Check:**
- [ ] New dependencies justified
- [ ] Dependencies lightweight (< 10MB)
- [ ] No duplicate dependencies
- [ ] Dependencies in requirements.txt
- [ ] License compatibility verified

**Result:** 🟡 Large dependencies = REVIEW needed  
**Citations:** DEC-13, LESS-26

---

**STAGE 2 GATE:**  
âœ… SUGA pattern compliance verified  
âœ… Import structure validated  
âœ… Dependencies approved  

**Sign-Off:**
- Developer: __________  
- Architect: __________  
- Date: __________

---

## ðŸ§ª STAGE 3: TESTING

**Duration:** 30-90 minutes  
**Owner:** Developer + QA  
**Required:** âœ… YES for all changes

### 3.1 Unit Tests

**Coverage Requirements:**
- [ ] New code: 80%+ coverage
- [ ] Modified code: No coverage decrease
- [ ] Critical paths: 100% coverage
- [ ] Edge cases tested

**Execution:**
```bash
# Run unit tests
pytest tests/unit/ --cov=src --cov-report=html

# Expected:
# âœ… All tests pass
# âœ… Coverage meets requirements
```

**Result:** ❌ FAIL tests = FIX  
**Result:** ❌ Coverage < 80% = ADD TESTS

---

### 3.2 Integration Tests

**Requirements:**
- [ ] Gateway â†' Interface â†' Core flow tested
- [ ] Error paths tested
- [ ] External dependencies mocked
- [ ] Boundary conditions tested

**Execution:**
```bash
# Run integration tests
pytest tests/integration/

# Expected:
# âœ… All integration tests pass
# âœ… External calls properly mocked
```

**Result:** ❌ FAIL integration = FIX  
**Citations:** DT-08, LESS-23

---

### 3.3 Performance Tests

**Benchmarks:**
- [ ] Cold start < 1000ms
- [ ] Warm request < 500ms
- [ ] Memory usage < 100MB
- [ ] No performance regression (< 10% slower)

**Execution:**
```bash
# Run performance benchmarks
pytest tests/performance/ --benchmark-only

# Compare with baseline
python scripts/compare_benchmarks.py
```

**Result:** 🟡 > 10% regression = INVESTIGATE  
**Citations:** LESS-02, LESS-17, LESS-28

---

**STAGE 3 GATE:**  
âœ… All tests pass  
âœ… Coverage requirements met  
âœ… Performance acceptable  

**Sign-Off:**
- Developer: __________  
- QA: __________  
- Date: __________

---

## ðŸ"š STAGE 4: DOCUMENTATION

**Duration:** 15-30 minutes  
**Owner:** Developer  
**Required:** âœ… YES for all changes

### 4.1 Code Documentation

**Requirements:**
- [ ] Public functions have docstrings
- [ ] Complex logic has inline comments
- [ ] Type hints present (Python 3.7+)
- [ ] Examples in docstrings for complex functions
- [ ] README updated if user-facing changes

**Quality Check:**
```bash
# Check docstring coverage
pydocstyle src/

# Check type hint coverage
mypy src/ --strict
```

**Result:** 🟡 Missing docs = ADD BEFORE MERGE

---

### 4.2 Architecture Documentation

**If architectural changes:**
- [ ] Neural map entries created/updated
- [ ] SIMA documentation updated
- [ ] Decision recorded (if applicable)
- [ ] Anti-patterns documented (if found)
- [ ] Lessons learned captured

**Check:**
- New entries have proper REF-IDs
- Cross-references valid
- Examples included
- Quality checklist passed

**Result:** âœ… Architecture changes must be documented  
**Citations:** LESS-11, LESS-12, LESS-13

---

### 4.3 Deployment Documentation

**Requirements:**
- [ ] Deployment steps documented
- [ ] Rollback procedure documented
- [ ] Configuration changes noted
- [ ] Migration steps (if applicable)
- [ ] Monitoring changes (if applicable)

**Result:** âœ… Deployment docs complete

---

**STAGE 4 GATE:**  
âœ… Code documented adequately  
âœ… Architecture docs updated  
âœ… Deployment guide complete  

**Sign-Off:**
- Developer: __________  
- Date: __________

---

## âœ… STAGE 5: FINAL SIGN-OFF

**Duration:** 5-10 minutes  
**Owner:** Team Lead / Release Manager  
**Required:** âœ… YES for deployment

### 5.1 Checklist Review

**Verify all previous stages:**
- [ ] Stage 1: Code Review - COMPLETE
- [ ] Stage 2: Architecture - COMPLETE
- [ ] Stage 3: Testing - COMPLETE
- [ ] Stage 4: Documentation - COMPLETE

**Evidence Required:**
- Test results report
- Coverage report
- Performance benchmark comparison
- Security scan results (if applicable)

---

### 5.2 Risk Assessment

**Evaluate:**
- [ ] Change scope: Small | Medium | Large
- [ ] Risk level: Low | Medium | High
- [ ] Rollback complexity: Easy | Moderate | Complex
- [ ] Impact radius: Single service | Multiple | System-wide

**Decision Matrix:**

| Risk | Impact | Action |
|------|--------|--------|
| Low | Small | Deploy immediately |
| Low | Medium | Deploy with monitoring |
| Medium | Small | Deploy with caution |
| Medium | Medium | Deploy off-peak hours |
| High | Any | Require additional approval |

---

### 5.3 Deployment Approval

**Approvals Required:**

**For Low Risk:**
- [ ] Developer approval
- [ ] Peer reviewer approval

**For Medium Risk:**
- [ ] Developer approval
- [ ] Peer reviewer approval
- [ ] Team lead approval

**For High Risk:**
- [ ] Developer approval
- [ ] Peer reviewer approval
- [ ] Team lead approval
- [ ] Architect approval
- [ ] Release manager approval

---

**STAGE 5 GATE:**  
âœ… All stages verified  
âœ… Risk assessed  
âœ… Approvals obtained  

**Final Sign-Off:**
- Developer: __________  
- Reviewer: __________  
- Team Lead: __________  
- Date: __________  
- Deployment Time: __________

**READY TO DEPLOY:** âœ…

---

## 📊 VERIFICATION METRICS

### Success Criteria

**Quality Gates:**
- Stage 1 pass rate: > 95%
- Stage 2 pass rate: > 90%
- Stage 3 pass rate: > 95%
- Stage 4 completion: 100%
- Stage 5 approval: 100%

**Performance Metrics:**
- Average verification time: 60-120 minutes
- Rollback rate: < 5%
- Production incidents: < 1 per month

---

## 🔧 AUTOMATION

### Automated Checks

**CI/CD Pipeline:**
```yaml
# .github/workflows/verification.yml
name: Verification Protocol

on: [pull_request]

jobs:
  code-review:
    - Run linters (pylint, flake8)
    - Check anti-patterns (TOOL-03)
    - Security scan (bandit)
    
  architecture:
    - Validate imports
    - Check dependencies
    - Verify SUGA compliance
    
  testing:
    - Run unit tests
    - Run integration tests
    - Generate coverage report
    - Run performance benchmarks
    
  documentation:
    - Check docstring coverage
    - Validate type hints
    - Verify README updated
```

**Pre-Commit Hooks:**
```bash
# .git/hooks/pre-commit
- Format code (black)
- Sort imports (isort)
- Check anti-patterns
- Run fast tests
```

---

## 🔗 RELATED TOOLS

- **TOOL-01:** REF-ID Directory
- **TOOL-02:** Quick Answer Index
- **TOOL-03:** Anti-Pattern Checklist ⭐
- **Workflow-03:** Modify Code - Process workflow

---

## 🎓 BEST PRACTICES

**Using This Protocol:**
âœ… Follow all stages sequentially  
âœ… Don't skip gates  
âœ… Automate what's automatable  
âœ… Document all approvals  
âœ… Track metrics for improvement  

**Improving Process:**
âœ… Review failed verifications  
âœ… Update checklist based on incidents  
âœ… Optimize automation  
âœ… Reduce verification time without sacrificing quality  

---

**END OF TOOL**

**Tool Version:** 1.0.0  
**Last Updated:** 2025-11-01  
**Maintained By:** SIMA Team  
**Average Verification Time:** 90 minutes  
**Rollback Prevention Rate:** 95%  
**Next Review:** Quarterly
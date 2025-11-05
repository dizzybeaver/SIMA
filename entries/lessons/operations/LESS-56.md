# LESS-56.md

**Version:** 1.0.0  
**Date:** 2025-11-04  
**Category:** Operations / Code Quality  
**Status:** Active

---

## Lesson: Systematic Audit Reveals Architectural Drift

**Context:** During comprehensive audit of Home Assistant extension (13 files, 43 issues found)

**Discovery:** Even with documented patterns (SUGA, SIMA), extension code drifted from architecture:
- Direct imports bypassing gateway (AP-01 violations)
- Bare except clauses throughout (AP-14 violations)
- Missing input validation at boundaries
- Security issues (token exposure)
- Lack of circuit breaker protection

**Root Cause:** 
- Fast development without audit checkpoints
- No automated enforcement of architectural patterns
- Missing code review checklist for HA-specific code
- Pattern documentation separate from enforcement

**Impact:**
- 8 critical security/stability issues
- 12 high-priority reliability issues
- Technical debt accumulation
- Potential production failures

**Prevention:**
1. **Pre-commit hooks** checking for:
   - Direct imports (not via gateway)
   - Bare except clauses
   - Missing input validation
   - Token logging patterns

2. **Architectural review checklist** before merge:
   - [ ] All imports via gateway (RULE-01)
   - [ ] Specific exception handling (AP-14)
   - [ ] Input validation at boundaries
   - [ ] No security exposure risks
   - [ ] Circuit breaker where needed

3. **Periodic audits** every N commits:
   - Automated: Linter rules for patterns
   - Manual: Architecture compliance review

4. **Documentation proximity**: 
   - Pattern rules in code comments
   - Template files for new modules
   - Self-documenting structure

**Keywords:** audit, architecture-drift, code-quality, technical-debt, prevention, patterns, enforcement

**Related:**
- AP-01: Direct imports
- AP-14: Bare except
- RULE-01: Gateway pattern
- LESS-01: Read complete files
- LESS-15: Verification protocol

**Application:** Any extension or new module added to codebase. Run audit checklist before considering "done".

---

## Quick Wins Identified

**Pattern:** 2-hour investment found issues requiring 60+ hours to fix fully.

**Insight:** Regular small audits prevent massive cleanup efforts.

**Recommendation:** 
- Weekly: Automated checks (5 min)
- Monthly: Quick manual review (30 min)
- Quarterly: Full comprehensive audit (4 hours)

---

**REF:** LESS-56, AP-01, AP-14, RULE-01  
**Impact:** HIGH - Prevents architectural drift  
**Effort:** LOW - Automated tooling + checklists

# Deployment-Index.md

**Category:** Decision Logic  
**Subcategory:** Deployment  
**Files:** 1  
**Last Updated:** 2024-10-30

---

## Overview

Decision tree for deployment decisions - when to deploy changes to production based on testing, review, risk assessment, and rollback planning.

---

## Files in This Category

### DT-12: Should I Deploy This Change

**File:** `DT-12.md`  
**REF-ID:** DT-12  
**Priority:** High  
**Status:** Active

**Summary:** Decision tree for deployment decisions with comprehensive checklist ensuring changes are tested, reviewed, backward compatible, and have rollback plans before production deployment.

**Key Decision Points:**
- Are tests passing? (MUST)
- Is change reviewed? (MUST)
- Is change backward compatible? (SHOULD)
- Can change be rolled back? (SHOULD)
- What is risk level?

**Use When:**
- Before any production deployment
- Assessing deployment risk
- Planning deployment strategy
- Deciding staging vs direct deployment

---

## Quick Decision Guide

### Pre-Deployment Checklist

**MUST Have (Blocking):**
- [ ] All tests passing
- [ ] Code review complete
- [ ] No merge conflicts
- [ ] Branch up to date with main

**SHOULD Have (Recommended):**
- [ ] Backward compatible OR migration plan
- [ ] Rollback plan documented
- [ ] Deployment runbook ready
- [ ] Monitoring configured

**OPTIONAL (Risk-Dependent):**
- [ ] Staging deployment tested
- [ ] Load testing complete
- [ ] Documentation updated
- [ ] Team notified

### Risk Assessment Guide

**LOW RISK → Deploy Directly**
- Small bug fixes
- Documentation updates
- Logging improvements
- Performance optimizations (measured)

**MEDIUM RISK → Stage First**
- New features
- Refactoring existing code
- Dependency updates
- Configuration changes

**HIGH RISK → Stage + Extended Testing**
- Architecture changes
- Core logic modifications
- Database migrations
- Security updates

**CRITICAL RISK → Phased Rollout**
- Breaking API changes
- Data structure changes
- Authentication changes
- Multi-service coordinated deploys

---

## Deployment Decision Flow

```
1. Run Tests
   âœ… Pass → Continue
   ❌ Fail → Fix first

2. Get Review
   âœ… Approved → Continue
   ❌ Not reviewed → Get review

3. Assess Risk
   LOW → Deploy to prod
   MEDIUM → Stage first
   HIGH → Stage + extended test
   CRITICAL → Phased rollout

4. Deploy
   - Git tag
   - Deploy code
   - Monitor logs
   - Verify functionality
   - Watch metrics

5. Post-Deployment
   - Monitor 30+ minutes
   - Check error rates
   - Verify features
   - Document deployment
```

---

## Related Categories

**Within Decision Logic:**
- **Testing** (DT-08: What Should I Test)
- **Refactoring** (DT-10: Should I Refactor This Code)
- **Optimization** (DT-07: Should I Optimize This Code)

**Other REF-IDs:**
- **AP-27**: Skipping Verification Protocol (what NOT to do)
- **AP-28**: Deploy Without Testing (deployment anti-pattern)
- **LESS-09**: Deployment Checklist (operational lessons)
- **LESS-19**: Production Monitoring (post-deployment monitoring)
- **DEC-21**: Deployment Strategy (deployment patterns)
- **DEC-22**: Rollback Procedures (how to rollback)

---

## Common Questions

**Q: Can I skip staging for small bug fixes?**
**A:** See **DT-12** - LOW RISK changes can deploy direct to prod, but must pass tests and review.

**Q: Tests are passing but I haven't gotten review yet, can I deploy?**
**A:** See **DT-12** - NO. Review is MUST-HAVE, always required.

**Q: One test is failing but it's unrelated, can I deploy?**
**A:** See **DT-12** - NO. All tests must pass. Fix or skip the test (with comment why).

**Q: How long should I monitor after deployment?**
**A:** See **DT-12** - Minimum 30 minutes for LOW risk, 1-4 hours for MEDIUM/HIGH, 24+ hours for CRITICAL.

**Q: When should I use staging environment?**
**A:** See **DT-12** - MEDIUM risk and above. Always for architecture changes, new features, refactoring.

---

## Keywords

deployment, production release, deployment checklist, risk assessment, staging environment, rollback plan, code review, testing, deployment strategy, continuous deployment

---

## Navigation

**Parent:** Decision Logic Master Index  
**Siblings:** Import, Feature Addition, Error Handling, Testing, Optimization, Refactoring, Architecture, Meta

**Location:** `/sima/entries/decisions/deployment/`

---

**End of Index**

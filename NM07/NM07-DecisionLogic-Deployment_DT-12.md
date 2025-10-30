# NM07-DecisionLogic-Deployment_DT-12.md - DT-12

# DT-12: Should I Deploy This Change?

**Category:** Decision Logic
**Topic:** Deployment
**Priority:** High
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Decision tree for deployment decisions - comprehensive checklist ensuring changes are tested, reviewed, backward compatible, and have rollback plans before production deployment.

---

## Context

Deploying to production carries risk. This decision tree ensures proper validation and planning before deployment, balancing speed with safety.

---

## Content

### Decision Tree

```
START: Change ready to deploy
│
├─ MUST: Are tests passing?
│  ├─ NO → Fix tests first
│  │      → STOP
│  │
│  └─ YES → Continue
│
├─ MUST: Is change reviewed?
│  ├─ NO → Get review
│  │      → STOP
│  │
│  └─ YES → Continue
│
├─ SHOULD: Is change backward compatible?
│  ├─ NO → Plan migration
│  │      Document breaking changes
│  │      → Continue with caution
│  │
│  └─ YES → Continue
│
├─ SHOULD: Can change be rolled back?
│  ├─ NO → Add rollback plan
│  │      Git tag, backup, etc.
│  │      → Continue
│  │
│  └─ YES → Continue
│
├─ OPTIONAL: Deploy to staging first?
│  ├─ HIGH RISK → Deploy to staging
│  │      Test in staging
│  │      → Then deploy to prod
│  │
│  └─ LOW RISK → Deploy directly to prod
│         → DEPLOY
│
└─ DEPLOY
   Steps:
   1. Git tag release
   2. Deploy code
   3. Monitor logs
   4. Verify functionality
   5. Document deployment
   → END
```

### Deployment Checklist

**MUST (Blocking):**
- [ ] All tests passing
- [ ] Code review complete
- [ ] No merge conflicts
- [ ] Git branch up to date

**SHOULD (Strong recommendation):**
- [ ] Backward compatible OR migration plan
- [ ] Rollback plan documented
- [ ] Deployment runbook ready
- [ ] Monitoring/alerts configured

**OPTIONAL (Risk-dependent):**
- [ ] Staging deployment tested
- [ ] Load testing complete (if performance-critical)
- [ ] Documentation updated
- [ ] Team notified

### Deployment Risk Assessment

| Change Type | Risk | Staging? | Rollback Plan |
|-------------|------|----------|---------------|
| Bug fix (small) | Low | Optional | Git revert |
| New feature | Medium | Yes | Feature flag |
| Refactoring | Medium | Yes | Git revert |
| Architecture change | High | Yes | Full backup |
| Breaking API change | Critical | Yes | Migration plan |

### Risk Levels Explained

**LOW RISK:**
- Small bug fixes
- Documentation updates
- Logging improvements
- Performance optimizations (measured)

**Deployment:** Direct to prod, monitor closely

**MEDIUM RISK:**
- New features
- Refactoring existing code
- Dependency updates
- Configuration changes

**Deployment:** Stage first, then prod with monitoring

**HIGH RISK:**
- Architecture changes
- Core logic modifications
- Database migrations
- Security updates

**Deployment:** Stage extensively, gradual rollout, ready rollback

**CRITICAL RISK:**
- Breaking API changes
- Data structure changes
- Authentication/authorization changes
- Multi-service coordinated deploys

**Deployment:** Stage + load test, migration plan, phased rollout, instant rollback ready

### Deployment Examples

**Low Risk - Bug Fix:**
```
Change: Fix cache key validation
Tests: ✅ Pass (unit + integration)
Review: ✅ Approved
Backward Compatible: ✅ Yes
Rollback: ✅ Git revert

Decision: DEPLOY to prod
- Tag: v1.2.3-bugfix-cache-validation
- Deploy: Direct to prod
- Monitor: CloudWatch logs for 30 min
- Verify: Test cache operations
```

**Medium Risk - New Feature:**
```
Change: Add cache.list_keys() operation
Tests: ✅ Pass (unit + integration + E2E)
Review: ✅ Approved
Backward Compatible: ✅ Yes (new operation)
Rollback: ✅ Feature flag

Decision: DEPLOY via staging
- Stage: Deploy + test in staging
- Production: Deploy with feature flag OFF
- Gradual: Enable for 10% users
- Monitor: 24 hours
- Full rollout: Enable for 100%
```

**High Risk - Architecture Change:**
```
Change: Refactor gateway to lazy loading
Tests: ✅ Pass (comprehensive suite)
Review: ✅ Multiple reviewers
Backward Compatible: ✅ Yes (internal change)
Rollback: ✅ Git tag + backup

Decision: STAGE EXTENSIVELY
- Stage: Deploy + load test 1 week
- Production: Off-hours deployment
- Monitor: Real-time dashboard
- Verify: Performance metrics
- Rollback ready: Previous version tagged
```

**Critical Risk - Breaking API:**
```
Change: Change auth token format
Tests: ✅ Pass
Review: ✅ Approved
Backward Compatible: ❌ NO (breaking change)
Migration: ✅ Plan documented
Rollback: ✅ Multi-phase rollback plan

Decision: PHASED DEPLOYMENT
Phase 1: Deploy backward-compatible endpoint
Phase 2: Migrate clients gradually
Phase 3: Deprecate old endpoint (30 days)
Phase 4: Remove old endpoint

Each phase: Stage → Monitor → Prod → Monitor
```

### Rollback Plans

**Git Revert (Simple):**
```bash
# If deployment fails
git revert <commit-hash>
git push origin main
# Redeploy
```

**Git Tag Rollback (Medium):**
```bash
# Tag before deployment
git tag -a v1.2.3 -m "Pre-deployment backup"
git push --tags

# If needed, rollback
git reset --hard v1.2.3
git push --force origin main
# Redeploy
```

**Feature Flag (Safest):**
```python
# Code with feature flag
if is_feature_enabled('new_cache_operation'):
    return new_cache_list_keys()
else:
    return legacy_behavior()

# Rollback: Just disable flag (no code deploy)
```

**Full Backup (Critical):**
```bash
# Before deployment
aws lambda get-function --function-name suga-isp > backup-config.json
# Download current deployment package
# Store in S3 versioned bucket

# Rollback if needed
aws lambda update-function-code \
  --function-name suga-isp \
  --s3-bucket backups \
  --s3-key lambda-v1.2.2.zip
```

### Deployment Workflow

**Standard Deployment:**
```
1. Create PR
2. Code review
3. CI tests pass
4. Merge to main
5. Tag release (git tag v1.2.3)
6. Deploy to Lambda
7. Monitor logs (15-30 min)
8. Verify functionality
9. Document in changelog
```

**Staged Deployment:**
```
1. Create PR
2. Code review
3. CI tests pass
4. Deploy to staging
5. Test in staging (hours/days based on risk)
6. Merge to main
7. Tag release
8. Deploy to prod
9. Monitor extensively
10. Gradual rollout (if feature flagged)
```

### Real-World Usage Pattern

**User Query:** "Can I deploy this architecture change directly to production?"

**Search Terms:** "deploy decision architecture"

**Decision Flow:**
1. Tests passing? Check
2. Reviewed? Check
3. Architecture change = HIGH RISK
4. **Decision:** Must stage first
5. **Response:** "Architecture change = HIGH RISK. Must deploy to staging first, test thoroughly, then prod."

### Post-Deployment Monitoring

**First 5 Minutes:**
- Watch error logs in real-time
- Check error rate metrics
- Verify basic functionality

**First 30 Minutes:**
- Monitor performance metrics
- Check cache hit rates
- Review cold start times
- Test critical paths

**First 24 Hours:**
- Daily metrics comparison
- User feedback monitoring
- Error rate trends
- Performance regression checks

**If Issues Detected:**
1. Assess severity (blocking? partial? minor?)
2. Check rollback criteria
3. Execute rollback if needed
4. Document issue for postmortem

### Deployment Anti-Patterns

**❌ Deploy Without Tests:**
```
Tests failing but "it'll probably work"
→ Don't deploy, fix tests
```

**❌ Skip Review for "Small" Changes:**
```
"Just a one-line change, don't need review"
→ Always get review, even one line can break things
```

**❌ Deploy Friday Afternoon:**
```
Deploy at 4 PM Friday before weekend
→ Deploy early week when team available to support
```

**❌ No Rollback Plan:**
```
"We'll figure it out if something breaks"
→ Have rollback plan BEFORE deploying
```

---

## Related Topics

- **AP-27**: Skipping verification protocol (anti-pattern)
- **AP-28**: Deploy without testing (anti-pattern)
- **LESS-09**: Deployment checklist lessons
- **DT-08**: What to test before deployment

---

## Keywords

should deploy, deploy decision, production deployment, risk assessment, staging, rollback plan

---

## Version History

- **2025-10-24**: Created - Migrated from NM07 v2 monolith

---

**File:** `NM07-DecisionLogic-Deployment_DT-12.md`
**End of Document**

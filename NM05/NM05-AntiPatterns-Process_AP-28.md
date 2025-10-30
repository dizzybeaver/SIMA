# NM05-AntiPatterns-Process_AP-28.md - AP-28

# AP-28: Deploying Untested Code

**Category:** NM05 - Anti-Patterns
**Topic:** Process
**Priority:** 🟡 HIGH
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Deploying code to production without running automated tests first means bugs, regressions, and breaking changes reach users directly, causing outages, data corruption, and customer impact.

---

## Context

Tests exist to catch problems before they reach production. Skipping tests is like skipping aircraft safety checks before takeoff — the plane might fly fine, but the consequences of problems are catastrophic.

**Problem:** Production outages, data corruption, customer complaints, emergency rollbacks, team stress.

---

## Content

### The Anti-Pattern

```bash
# ❌ DEPLOYING WITHOUT TESTS
# Developer makes changes
vim lambda_function.py

# Commits and pushes
git add .
git commit -m "Quick fix"
git push

# Deploys directly to production
./deploy.sh production

# No tests run!
# No verification!
# Hope it works!
```

**What happens:**
```
15:00 - Deploy to production
15:01 - Users report errors
15:02 - Logs show exceptions
15:03 - Emergency investigation
15:05 - Find the bug
15:10 - Rollback deployment
15:15 - Service restored

30 minutes of downtime
Hundreds of users affected
Emergency stress for team
Reputation damage
```

### Why This Is Critical

**1. Bugs Reach Users Directly**
```python
# Developer changes code:
def calculate_discount(price):
    return price * 0.10  # Changed from 0.05

# Deploys without testing
# Production: All prices now show wrong discount!
# Customers complain
# Financial impact
```

**2. Breaking Changes Undetected**
```python
# Developer refactors:
def get_user(user_id):
    # Changed return format!
    return {'user': user}  # Was: return user

# Deploys without testing
# All code expecting old format breaks!
# Cascade failures across system
```

**3. Regressions Introduced**
```python
# Developer fixes bug A
def process_data(data):
    if not data:  # Fixed null check
        return []
    return transform(data)

# But accidentally breaks feature B
# Forgot feature B relied on old behavior
# No tests to catch regression
# Deploy → Feature B broken in production
```

**4. Emergency Rollbacks**
```
Without tests:
├─ Deploy bad code
├─ Production breaks
├─ Emergency investigation
├─ Identify problem
├─ Emergency rollback
└─ Time wasted: 30+ minutes

With tests:
├─ Run tests
├─ Tests fail
├─ Fix locally
├─ Tests pass
├─ Deploy with confidence
└─ Time saved: 30+ minutes
```

### Correct Approach

**Always Test Before Deploy:**
```bash
# ✅ CORRECT - Test before deploy
# 1. Make changes
vim lambda_function.py

# 2. Run tests locally
pytest
# All tests pass ✓

# 3. Commit
git add .
git commit -m "Fix cache expiration bug"

# 4. Push (triggers CI)
git push

# 5. CI runs tests automatically
# GitHub Actions:
# - Run unit tests
# - Run integration tests
# - Run linting
# All checks pass ✓

# 6. Deploy (only if tests pass)
./deploy.sh production
```

### CI/CD Pipeline

**Automated Testing Pipeline:**
```yaml
# .github/workflows/test-and-deploy.yml
name: Test and Deploy

on:
  push:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      
      - name: Set up Python
        uses: actions/setup-python@v2
        with:
          python-version: 3.11
      
      - name: Install dependencies
        run: pip install -r requirements.txt
      
      - name: Run unit tests
        run: pytest tests/unit/
      
      - name: Run integration tests
        run: pytest tests/integration/
      
      - name: Check code coverage
        run: pytest --cov=src --cov-report=term --cov-fail-under=80
      
      - name: Run linting
        run: pylint src/
  
  deploy:
    needs: test  # Only runs if tests pass!
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main'
    steps:
      - uses: actions/checkout@v2
      
      - name: Deploy to Lambda
        run: ./deploy.sh production
        env:
          AWS_ACCESS_KEY_ID: ${{ secrets.AWS_ACCESS_KEY_ID }}
          AWS_SECRET_ACCESS_KEY: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
```

**Key Points:**
- Tests run automatically on every push
- Deployment only happens if tests pass
- No manual steps to skip
- Impossible to deploy untested code

### Test Stages

**Stage 1: Local Development**
```bash
# Developer runs tests before committing
pytest
pytest --cov=src
pylint src/
```

**Stage 2: Pre-commit Hooks**
```bash
# .git/hooks/pre-commit
#!/bin/bash
echo "Running tests..."
pytest tests/unit/
if [ $? -ne 0 ]; then
    echo "Tests failed! Commit aborted."
    exit 1
fi
```

**Stage 3: CI Pipeline**
```
Push to GitHub
└─> GitHub Actions runs
    ├─> Unit tests
    ├─> Integration tests
    ├─> Coverage check
    ├─> Linting
    └─> All pass? → Allow merge
```

**Stage 4: Deployment Gate**
```
Merge to main
└─> CI pipeline runs again
    ├─> All tests pass?
    └─> Yes → Auto-deploy to production
        No → Block deployment, alert team
```

### Real SUGA-ISP Example

**Wrong (early process):**
```bash
# Manual deployment, no testing
vim lambda_function.py  # Make changes
./deploy.sh             # Deploy immediately
# Hope it works!
```

**Correct (current process):**
```bash
# Tested deployment
vim lambda_function.py  # Make changes

# Local testing
pytest                  # Run tests
pytest --cov=src       # Check coverage

# Commit
git add .
git commit -m "Fix: Resolve cache expiration bug (BUG-01)"
git push

# CI runs automatically:
# ✓ Unit tests pass
# ✓ Integration tests pass
# ✓ Coverage > 80%
# ✓ Linting passes

# Auto-deploy (only if all tests pass)
# Or manual deploy if tests passed:
./deploy.sh production
```

### Test Coverage Requirements

**Minimum Coverage by Code Type:**
```
Critical paths: 100% coverage required
Core business logic: 90%+ coverage
Interface layers: 80%+ coverage
Utility functions: 70%+ coverage
```

**Deployment Blockers:**
```bash
# Block deployment if:
# - Any test fails
# - Coverage drops below threshold
# - Linting errors
# - Security vulnerabilities

pytest --cov=src --cov-fail-under=80
if [ $? -ne 0 ]; then
    echo "Coverage below 80%! Blocking deployment."
    exit 1
fi
```

### Emergency Deployments

**Even emergencies need tests:**
```bash
# Emergency hotfix process:
# 1. Create hotfix branch
git checkout -b hotfix/critical-bug

# 2. Fix the bug
vim lambda_function.py

# 3. Write minimal test
vim test_fix.py

# 4. Run tests
pytest test_fix.py  # At least test the fix!

# 5. If test passes, fast-track deploy
git commit -m "HOTFIX: Critical production bug"
git push

# 6. CI runs tests (fast tracked)
# 7. Deploy immediately after tests pass
```

**Never skip tests, even in emergencies!**

### Monitoring After Deployment

**Even with tests, monitor production:**
```bash
# After deployment:
# 1. Watch error logs
tail -f /var/log/lambda.log

# 2. Monitor metrics
# - Error rate
# - Response time
# - Success rate

# 3. Smoke test production
curl https://api.example.com/health
# {"status": "ok"}

# 4. If issues, rollback immediately
git checkout v1.2.2
./deploy.sh production
```

### Real Impact Stories

**Story 1: The Untested Deployment**
```
What happened:
- Developer made "simple" change
- Skipped tests (in a rush)
- Deployed to production
- Change broke payment processing
- $50,000 in lost revenue (2 hours)
- Emergency rollback
- Customer trust damaged

What should have happened:
- Run tests (2 minutes)
- Tests would have caught bug
- Fix locally
- Deploy with confidence
- No impact
```

**Story 2: The Test That Saved Production**
```
What happened:
- Developer refactored caching
- Ran tests before deploy
- One test failed
- Investigated: race condition found
- Fixed the race condition
- All tests pass
- Deployed safely
- No production impact

Without tests:
- Race condition hits production
- Intermittent cache corruption
- Days of debugging
- Customer impact
```

### Checklist Before Deploy

**Pre-Deployment Checklist:**
- [ ] All unit tests pass locally
- [ ] All integration tests pass
- [ ] Code coverage meets threshold
- [ ] Linting passes
- [ ] CI pipeline green
- [ ] Code reviewed and approved
- [ ] Deployment plan documented
- [ ] Rollback plan ready
- [ ] Monitoring configured
- [ ] On-call engineer notified

---

## Related Topics

- **AP-23**: No Tests - Must have tests to run them
- **AP-24**: Tests Without Assertions - Tests must actually verify
- **AP-27**: No Version Control - Need version control for safe deploys
- **LESS-09**: Deployment Lessons - Real deployment failures
- **LESS-15**: Verification Protocol - Pre-deployment checks

---

## Keywords

deployment, CI/CD, automated testing, production deployment, deployment safety, GitHub Actions, test automation, deployment pipeline

---

## Version History

- **2025-10-24**: Created - Extracted from Part 2, added CI/CD pipeline and real impact stories

---

**File:** `NM05-AntiPatterns-Process_AP-28.md`
**End of Document**

# LESS-09.md

# LESS-09: Atomic Deployment Prevents Cascading Failures

**Category:** Lessons  
**Topic:** Operations  
**Priority:** HIGH  
**Status:** Active  
**Created:** 2025-10-30  
**Path:** `/sima/entries/lessons/operations/LESS-09.md`

---

## Summary

Deploying incomplete changes causes cascading failures. In tightly-coupled systems (like gateway patterns), changes must be coordinated across all affected files and deployed atomically.

---

## Pattern

### The Problem

**Partial Deployment Cascade:**
```
1. Update file A with new signature
2. Forget to update file B (depends on A)
3. Deploy only file A
4. First request crashes (signature mismatch)
5. All subsequent requests fail
6. System-wide outage
```

**Example Incident:**
```
Changed: core.py (added timeout parameter)
Forgot: interface.py (still using old signature)
Deployed: Only core.py
Result: TypeError on missing parameter → 15min outage
```

### Why This Happens

**Tight Coupling in Gateway Patterns:**
```
Gateway ↔↔ Interfaces ↔↔ Cores

These are NOT independent modules.
They are a NETWORK of interdependent components.
Changes ripple across layers.
```

**Unlike Microservices:**
- Microservices: Network boundary allows independent deployment
- Gateway Pattern: Shared container requires atomic deployment

---

## Solution

### 1. Atomic Deployment Strategy

**Package All Changed Files Together:**
```bash
# âœ… CORRECT: All files in single deployment
zip deployment.zip src/*.py
deploy_function --zip-file fileb://deployment.zip
```

**Never Deploy Individually:**
```bash
# âŒ WRONG: Partial deployment
deploy_file core.py        # Deployed
# Forgot interface.py      # Not deployed → BREAKS
```

### 2. Pre-Deployment Verification

**Check All Affected Files Present:**
```bash
#!/bin/bash
required_files=(
    "core.py"
    "interface.py"
    "gateway.py"
)

for file in "${required_files[@]}"; do
    if [ ! -f "src/$file" ]; then
        echo "❌ FAIL: Missing file $file"
        exit 1
    fi
done

echo "âœ… All required files present"
```

### 3. Deployment Checklist

```markdown
### Pre-Deployment
- [ ] All affected files identified
- [ ] Local tests passing
- [ ] File sizes verified
- [ ] Version control commit with tag

### Deployment
- [ ] All files packaged together
- [ ] Single atomic deployment
- [ ] New version published

### Post-Deployment
- [ ] Health check passed
- [ ] Logs reviewed
- [ ] Metrics baseline acceptable
```

### 4. Deployment Patterns

**Pattern 1: Backward Compatible (Safer)**
```python
# Add optional parameter with default
def execute_operation(interface, operation, timeout=30, **kwargs):
    # Old callers work, new callers can specify
```

**Pattern 2: Breaking Changes (Requires Full Coordination)**
```python
# Add required parameter
def execute_operation(interface, operation, timeout, **kwargs):
    # MUST deploy all affected files atomically
```

### 5. Rollback Procedure

**Quick Revert to Previous Version:**
```bash
# 1. Identify previous stable version
list_versions --function-name MyFunction

# 2. Update to previous version
update_alias \
    --function-name MyFunction \
    --name production \
    --function-version 42
```

---

## Impact

**Before Atomic Deployment:**
- Deployment failures: 15%
- Average outage: 15-30 minutes
- Manual coordination required

**After Atomic Deployment:**
- Deployment failures: <1%
- Average outage: 0 minutes (rollback before impact)
- Automated verification

---

## Best Practices

**1. Bundle Related Changes**
- Identify all affected files
- Package together
- Deploy as single unit

**2. Test Locally First**
- Run integration tests
- Verify all interfaces work
- Confirm no missing dependencies

**3. Version Control Discipline**
- Single commit for coordinated changes
- Tag releases
- Clear commit messages

**4. Automated Verification**
- Check file presence
- Validate signatures
- Run health checks

**5. Rollback Plan**
- Previous version identified
- Rollback command ready
- Quick revert capability

---

## Anti-Patterns to Avoid

**âŒ Partial Deployment**
- Deploying subset of changed files
- "I'll deploy the rest later"
- Assuming order doesn't matter

**âŒ Manual Coordination**
- "Did you deploy file X?"
- Verbal communication of changes
- No verification

**âŒ No Rollback Plan**
- Deploy and hope
- No previous version identified
- Panic during issues

**âŒ Individual File Deploys**
- Treating tightly-coupled files as independent
- Sequential deployments
- Time gap between related changes

---

## Related Topics

- **Cascading Failures**: Preventing error propagation
- **Gateway Pattern**: Understanding tight coupling
- **Deployment Safety**: Pre and post-deployment verification
- **Rollback Procedures**: Quick recovery mechanisms

---

## Keywords

atomic-deployment, deployment-safety, coordinated-deployment, partial-deployment-danger, cascading-failures, rollback-procedures

---

## Version History

- **2025-10-30**: Genericized for SIMAv4 - Removed project-specific details
- **2025-10-23**: Created - Migrated from project documentation

---

**File:** `LESS-09.md`  
**Location:** `/sima/entries/lessons/operations/`  
**Status:** Active

---

**End of Document**

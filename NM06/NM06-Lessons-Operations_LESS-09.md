# NM06-Lessons-Operations_LESS-09.md - LESS-09

# LESS-09: Partial Deployment Danger

**Category:** Lessons  
**Topic:** Operations  
**Priority:** HIGH  
**Status:** Active  
**Created:** 2025-10-23  
**Last Updated:** 2025-10-23

---

## Summary

Deploying incomplete changes causes cascading failures. The SUGA pattern creates tight coupling between gateway, interfaces, and cores - changes must be coordinated across all affected files and deployed atomically.

---

## Context

Production outage (2025-09-22, 15 minutes) caused by deploying gateway_core.py with new operation signature but forgetting to update interface_cache.py. Result: TypeError on missing parameter.

---

## Content

### The Incident

**What happened:**
1. Developer updated gateway_core.py (added timeout parameter)
2. Developer forgot to update interface_cache.py
3. Deployed only gateway_core.py
4. First request crashed: TypeError (unexpected keyword timeout)
5. All subsequent requests failed
6. 15 minute outage

### Why This Happened

**The SUGA Pattern Creates Tight Coupling:**
```
Gateway ↔↔ Interfaces ↔↔ Cores

These are NOT independent modules.
They are a NETWORK of interdependent components.
Changes must be coordinated across all layers.
```

### The Solution

**1. Atomic Deployment Strategy**
```bash
# ✅ CORRECT: Deploy all files atomically
zip deployment.zip src/*.py
aws lambda update-function-code --function-name LEE \
    --zip-file fileb://deployment.zip
```

**2. Pre-Deployment Verification**
```bash
#!/bin/bash
# Check that all interface files are present
for file in "${required_files[@]}"; do
    if [ ! -f "src/$file" ]; then
        echo "❌ FAIL: Missing file $file"
        exit 1
    fi
done
```

**3. Deployment Checklist**
```markdown
### Pre-Deployment
- [ ] All affected files identified
- [ ] Local tests passing
- [ ] File sizes verified
- [ ] Git commit with version tag

### Deployment
- [ ] All files in single zip
- [ ] Lambda function updated
- [ ] New version published

### Post-Deployment
- [ ] Health check passed
- [ ] CloudWatch logs reviewed
- [ ] Metrics baseline acceptable
```

### The SUGA Deployment Model

**Unlike traditional microservices:**
- Microservices: Network boundary allows independent deployment
- SUGA: Shared Lambda container requires atomic deployment

### Deployment Patterns

**Pattern 1: Backward Compatible (Safe)**
```python
# Add optional parameter with default
def execute_operation(interface, operation, timeout=30, **kwargs):
    # Old callers work, new callers can specify
```

**Pattern 2: Breaking Changes (Requires Coordination)**
```python
# Add required parameter
def execute_operation(interface, operation, timeout, **kwargs):
    # MUST deploy all files together
```

### Rollback Procedure

```bash
# 1. Identify previous version
aws lambda list-versions-by-function --function-name LEE

# 2. Update alias to previous version
aws lambda update-alias \
    --function-name LEE \
    --name production \
    --function-version 42
```

### Real-World Impact

**Before atomic deployment:**
- Deployment failures: 15%
- Average outage: 15-30 minutes

**After atomic deployment:**
- Deployment failures: <1%
- Average outage: 0 minutes (rollback before impact)

---

## Related Topics

- **BUG-03**: Cascading failure
- **BUG-06**: Config mismatch
- **LESS-15**: File verification

---

## Keywords

partial deployment, atomic deployment, deployment safety, coordinated deployment

---

## Version History

- **2025-10-23**: Created - Migrated to SIMA v3 individual file format
- **2025-10-20**: Original documentation in NM06-LESSONS-Deployment_and_Operations.md

---

**File:** `NM06-Lessons-Operations_LESS-09.md`  
**Directory:** NM06/  
**End of Document**

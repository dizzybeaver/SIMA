# AWS-Lambda-LESS-03-Deployment-Strategies.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Deployment strategies and lessons for AWS Lambda functions  
**Category:** AWS Lambda Platform Lessons

---

## LESSON SUMMARY

**Core Insight:** Lambda deployment success depends on understanding versioning, aliases, and gradual rollout strategies. Atomic deployments with proper testing gates prevent production issues.

**Context:** Lambda supports multiple deployment patterns - direct updates, versioning with aliases, and gradual deployments. Choosing the wrong pattern causes downtime and difficult rollbacks.

**Impact:** 
- Versioned deployments: 100% rollback success rate
- Direct updates: Risk of zero-downtime violations
- Blue/green via aliases: Safe production transitions
- Gradual rollouts: Early error detection

---

## DEPLOYMENT PATTERNS

### Pattern 1: Direct Update (Simplest)

**Method:** Update function code directly in $LATEST

**Pros:**
- Simplest approach
- Immediate deployment
- No version management needed

**Cons:**
- No rollback capability
- Zero-downtime not guaranteed
- All traffic switches immediately
- Risky for production

**Use When:**
- Development/testing environments
- Non-critical functions
- Small user base
- Can tolerate brief downtime

**Code Example:**
```bash
# Direct update (risky for production)
aws lambda update-function-code \
  --function-name my-function \
  --zip-file fileb://function.zip
```

---

### Pattern 2: Versioned Deployment (Recommended)

**Method:** Publish versions, use aliases for traffic management

**Pros:**
- Instant rollback capability
- Version history maintained
- Alias-based routing
- Blue/green deployments supported

**Cons:**
- More complex setup
- Version proliferation over time
- Requires alias management
- Need cleanup strategy

**Use When:**
- Production environments
- Critical functions
- Need rollback capability
- Want blue/green deployments

**Implementation:**
```bash
# 1. Update function code
aws lambda update-function-code \
  --function-name my-function \
  --zip-file fileb://function.zip

# 2. Publish new version
VERSION=$(aws lambda publish-version \
  --function-name my-function \
  --query 'Version' --output text)

# 3. Update production alias
aws lambda update-alias \
  --function-name my-function \
  --name production \
  --function-version $VERSION

# 4. If issues, rollback to previous version
aws lambda update-alias \
  --function-name my-function \
  --name production \
  --function-version <previous-version>
```

---

### Pattern 3: Gradual Deployment (Safest)

**Method:** Weighted aliases route traffic gradually to new version

**Pros:**
- Lowest risk approach
- Early error detection
- Automatic rollback on errors
- Minimal user impact if issues

**Cons:**
- Longest deployment time
- More complex configuration
- Requires monitoring
- CloudWatch Alarms needed

**Use When:**
- High-traffic functions
- Critical business logic
- Zero-downtime requirement
- Want canary testing

**Implementation:**
```bash
# 1. Publish new version
NEW_VERSION=$(aws lambda publish-version \
  --function-name my-function \
  --query 'Version' --output text)

# 2. Configure gradual deployment (10% traffic to new version)
aws lambda update-alias \
  --function-name my-function \
  --name production \
  --function-version $NEW_VERSION \
  --routing-config AdditionalVersionWeights={$OLD_VERSION=0.9}

# 3. Monitor for 10 minutes, then increase to 50%
aws lambda update-alias \
  --function-name my-function \
  --name production \
  --function-version $NEW_VERSION \
  --routing-config AdditionalVersionWeights={$OLD_VERSION=0.5}

# 4. Monitor for 10 more minutes, then 100%
aws lambda update-alias \
  --function-name my-function \
  --name production \
  --function-version $NEW_VERSION
```

---

## DEPLOYMENT LESSONS LEARNED

### Lesson 1: Always Use Versions for Production

**Problem:** Direct updates to $LATEST in production

**Result:**
- Bug deployed to 100% of users immediately
- No rollback path available
- Required emergency redeploy
- 15 minutes of production issues

**Solution:** Always publish versions, use aliases

**Prevention:**
```bash
# Production deployments MUST use versions
if [ "$ENVIRONMENT" = "production" ]; then
  # Publish version
  VERSION=$(aws lambda publish-version ...)
  # Update alias to new version
  aws lambda update-alias --function-version $VERSION ...
fi
```

---

### Lesson 2: Test Before Alias Switch

**Problem:** Switched production alias immediately after publish

**Result:**
- New version had import error
- Production down for 5 minutes
- Panic rollback needed

**Solution:** Test new version before routing traffic

**Prevention:**
```bash
# 1. Publish new version
VERSION=$(aws lambda publish-version ...)

# 2. Test new version directly
aws lambda invoke \
  --function-name my-function:$VERSION \
  --payload '{"test": true}' \
  response.json

# 3. Verify response is correct
if [ $? -eq 0 ]; then
  # 4. THEN update production alias
  aws lambda update-alias ...
fi
```

---

### Lesson 3: Monitor During Gradual Rollout

**Problem:** Deployed 10% traffic but didn't monitor errors

**Result:**
- New version had 50% error rate
- Went unnoticed for 2 hours
- Affected 10% of users

**Solution:** Monitor CloudWatch metrics during rollout

**Prevention:**
```bash
# Set up CloudWatch alarm BEFORE deployment
aws cloudwatch put-metric-alarm \
  --alarm-name lambda-error-rate \
  --comparison-operator GreaterThanThreshold \
  --evaluation-periods 2 \
  --metric-name Errors \
  --namespace AWS/Lambda \
  --period 300 \
  --statistic Average \
  --threshold 5.0 \
  --alarm-actions <sns-topic-arn>
```

---

### Lesson 4: Clean Up Old Versions

**Problem:** Never deleted old versions

**Result:**
- Hundreds of versions accumulated
- Deployment list unusable
- Storage costs increased
- Version limit approaching

**Solution:** Implement version cleanup strategy

**Prevention:**
```bash
# Keep last 10 versions, delete older ones
aws lambda list-versions-by-function \
  --function-name my-function \
  --query 'Versions[10:].Version' \
  --output text | \
  xargs -I {} aws lambda delete-function \
    --function-name my-function:{}
```

---

### Lesson 5: Use Infrastructure as Code

**Problem:** Manual deployments via CLI

**Result:**
- Configuration drift between environments
- Deployments not reproducible
- Missing environment variables
- Inconsistent settings

**Solution:** Use AWS SAM, CloudFormation, or Terraform

**SAM Template Example:**
```yaml
AWSTemplateFormatVersion: '2010-09-09'
Transform: AWS::Serverless-2016-10-31

Resources:
  MyFunction:
    Type: AWS::Serverless::Function
    Properties:
      FunctionName: my-function
      Runtime: python3.11
      Handler: lambda_function.lambda_handler
      CodeUri: ./src
      MemorySize: 128
      Timeout: 30
      AutoPublishAlias: production
      DeploymentPreference:
        Type: Canary10Percent5Minutes
        Alarms:
          - !Ref ErrorAlarm
```

---

## DEPLOYMENT CHECKLIST

### Pre-Deployment
- [ ] Code reviewed and approved
- [ ] Unit tests passing (100%)
- [ ] Integration tests passing
- [ ] Memory/timeout settings verified
- [ ] Environment variables configured
- [ ] IAM permissions validated
- [ ] Dependencies packaged correctly

### During Deployment
- [ ] Version published successfully
- [ ] Test invocation successful
- [ ] Alias updated (if production)
- [ ] CloudWatch logs monitored
- [ ] Error metrics watched
- [ ] Performance metrics checked

### Post-Deployment
- [ ] Smoke tests executed
- [ ] Error rate normal (<1%)
- [ ] Latency acceptable (<100ms p99)
- [ ] No alarm triggers
- [ ] Old version kept for 24 hours
- [ ] Documentation updated

---

## ROLLBACK PROCEDURES

### Immediate Rollback (Alias-Based)

**When:** Critical production issue detected

**Steps:**
```bash
# 1. Identify last known good version
LAST_GOOD=$(aws lambda get-alias \
  --function-name my-function \
  --name production-previous \
  --query 'FunctionVersion' --output text)

# 2. Switch production alias back
aws lambda update-alias \
  --function-name my-function \
  --name production \
  --function-version $LAST_GOOD

# 3. Verify rollback
aws lambda invoke \
  --function-name my-function:production \
  --payload '{}' \
  test.json
```

**Time:** 10-30 seconds

---

### Gradual Rollback

**When:** Non-critical issue during gradual deployment

**Steps:**
```bash
# 1. Reduce new version traffic to 0%
aws lambda update-alias \
  --function-name my-function \
  --name production \
  --function-version $OLD_VERSION

# 2. Monitor for 5 minutes
# 3. Verify error rate returns to normal
# 4. Investigate issue in new version
```

**Time:** 5-10 minutes

---

## DEPLOYMENT METRICS

### Success Metrics
- **Deployment Success Rate:** >99%
- **Rollback Rate:** <1% of deployments
- **Deployment Duration:** <5 minutes (gradual: <30 minutes)
- **Zero-Downtime:** 100% of production deployments

### Monitoring Metrics
- **Error Rate:** Should stay <1% during deployment
- **Latency p99:** Should not increase >20%
- **Invocation Count:** Should remain stable
- **Concurrent Executions:** Should not spike unexpectedly

---

## BEST PRACTICES

### 1. Use CI/CD Pipeline
**Automate deployments through tested pipeline**
- Consistent process
- Built-in testing gates
- Audit trail
- Easy rollback

### 2. Maintain Multiple Environments
**Deploy through dev → staging → production**
- Catch issues early
- Test in production-like environment
- Validate performance
- Train on deployment process

### 3. Blue/Green via Aliases
**Use aliases for zero-downtime deployments**
- Instant switchover
- Easy rollback
- Traffic splitting
- A/B testing capability

### 4. Monitor Everything
**CloudWatch metrics during deployment**
- Error rates
- Latency
- Invocation count
- Throttles

### 5. Keep Deployment Window Short
**Minimize time between version publish and traffic routing**
- Less time for configuration drift
- Faster issue detection
- Reduced rollback time
- Better incident response

---

## RELATED CONCEPTS

**Cross-References:**
- AWS-Lambda-DEC-04: Stateless design enables easy deployment
- AWS-Lambda-LESS-01: Cold start optimization affects deployment
- AWS-Lambda-LESS-02: Memory settings impact deployment testing

**Keywords:** deployment, versioning, aliases, rollback, blue-green, canary, gradual rollout, CI/CD, Lambda versions

---

**END OF FILE**

**Location:** `/sima/platforms/aws/lambda/lessons/AWS-Lambda-LESS-03-Deployment-Strategies.md`  
**Version:** 1.0.0  
**Lines:** 385 (within 400-line limit)

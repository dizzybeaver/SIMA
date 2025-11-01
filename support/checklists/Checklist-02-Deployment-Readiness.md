# File: Checklist-02-Deployment-Readiness.md

**REF-ID:** CHK-02  
**Version:** 1.0.0  
**Category:** Support Tools  
**Type:** Verification Checklist  
**Purpose:** Pre-deployment verification for Lambda deployments

---

## 📋 OVERVIEW

Use this checklist before deploying to staging or production to ensure Lambda function is ready, optimized, and safe.

**Time to complete:** 10-15 minutes  
**Frequency:** Before every deployment  
**Prerequisite:** Code review passed (CHK-01)

---

## 🧪 TESTING VERIFICATION

### Test Suite Status
- [ ] **All unit tests passing** (100% pass rate)
- [ ] **All integration tests passing** (end-to-end flows working)
- [ ] **Performance tests run** (if performance-critical feature)
- [ ] **Load tests conducted** (if high-traffic endpoint)
- [ ] **No test failures in CI/CD** (continuous integration green)

### Test Coverage
- [ ] **Core logic covered** (business logic tested)
- [ ] **Error paths tested** (exception handling verified)
- [ ] **Edge cases tested** (boundary conditions)
- [ ] **Integration points tested** (external API calls mocked/tested)
- [ ] **Gateway functions tested** (import and execution verified)

**Reference:** Testing best practices

---

## 📦 PACKAGE VERIFICATION

### Lambda Package Size
- [ ] **Total size under 128MB** (deployment package + layers)
- [ ] **Only required dependencies** (no dev dependencies)
- [ ] **No unnecessary files** (tests, docs excluded from package)
- [ ] **Optimized imports** (lazy loading, minimal module-level)
- [ ] **Layer structure correct** (if using Lambda layers)

### Dependencies Check
- [ ] **requirements.txt updated** (all dependencies listed)
- [ ] **Version pins present** (specific versions, not ranges)
- [ ] **No security vulnerabilities** (dependency scan clean)
- [ ] **Compatible versions** (no conflicting dependencies)
- [ ] **License compliance** (all dependencies have acceptable licenses)

**Reference:** ARCH-04 (ZAPH), NMP01-LEE-16 (Fast Path)

---

## ⚙️ CONFIGURATION

### Environment Variables
- [ ] **All required vars documented** (in config docs)
- [ ] **No hardcoded values** (all externalized)
- [ ] **Secrets in Secrets Manager** (not in environment vars)
- [ ] **Default values sensible** (fallbacks appropriate)
- [ ] **Environment-specific configs** (dev/staging/prod separated)

### Lambda Configuration
- [ ] **Memory allocation appropriate** (tested and optimized)
- [ ] **Timeout value reasonable** (execution time + buffer)
- [ ] **Handler path correct** (points to lambda_function.lambda_handler)
- [ ] **Runtime version current** (Python 3.11 or latest supported)
- [ ] **Architecture correct** (x86_64 or arm64 as appropriate)

### IAM Permissions
- [ ] **Least privilege principle** (only required permissions)
- [ ] **Execution role correct** (proper trust relationships)
- [ ] **Resource policies reviewed** (if Lambda accesses other resources)
- [ ] **VPC configuration** (if needed for private resources)
- [ ] **Security groups correct** (if VPC-enabled)

**Reference:** Configuration best practices

---

## 🔐 SECURITY

### Code Security
- [ ] **No credentials in code** (all externalized)
- [ ] **No API keys hardcoded** (use environment/secrets)
- [ ] **No debug endpoints** (debug routes disabled in prod)
- [ ] **Input validation present** (all user inputs validated)
- [ ] **SQL injection prevention** (parameterized queries)

### Data Security
- [ ] **Sensitive data encrypted** (in transit and at rest)
- [ ] **PII handling compliant** (GDPR/regulations followed)
- [ ] **Logging sanitized** (no sensitive data in logs)
- [ ] **Error messages safe** (no internal details exposed)
- [ ] **Token handling secure** (proper storage and rotation)

### Network Security
- [ ] **HTTPS only** (no unencrypted communication)
- [ ] **API Gateway configured** (if public endpoint)
- [ ] **Rate limiting enabled** (if applicable)
- [ ] **CORS properly configured** (if needed)
- [ ] **DDoS protection** (CloudFront/WAF if public)

**Reference:** INT-03 (SECURITY), NMP01-LEE-04 (Security Patterns)

---

## 🚀 PERFORMANCE

### Cold Start Optimization
- [ ] **Lazy imports implemented** (gateway pattern followed)
- [ ] **Minimal module-level code** (no heavy initialization)
- [ ] **ZAPH pattern applied** (if applicable)
- [ ] **Connection pooling** (for database/API connections)
- [ ] **Provisioned concurrency** (if consistent low latency required)

### Runtime Performance
- [ ] **Execution time acceptable** (under timeout with buffer)
- [ ] **Memory usage reasonable** (no excessive allocation)
- [ ] **No memory leaks** (resources properly released)
- [ ] **Database queries optimized** (if applicable)
- [ ] **Cache utilization** (appropriate caching strategy)

### Monitoring Setup
- [ ] **CloudWatch logs enabled** (log group created)
- [ ] **Custom metrics defined** (if needed)
- [ ] **Alarms configured** (error rate, duration, throttles)
- [ ] **X-Ray tracing enabled** (if performance monitoring needed)
- [ ] **Dashboards created** (if ongoing monitoring needed)

**Reference:** ARCH-04 (ZAPH), INT-02 (LOGGING), INT-07 (METRICS)

---

## 🔄 RESILIENCE

### Error Handling
- [ ] **All exceptions caught** (no unhandled exceptions)
- [ ] **Retry logic appropriate** (for transient failures)
- [ ] **Circuit breaker implemented** (if calling external services)
- [ ] **Graceful degradation** (fallback behavior defined)
- [ ] **Error logging comprehensive** (context for debugging)

### Integration Points
- [ ] **External API timeouts set** (no infinite waits)
- [ ] **Health checks implemented** (for dependencies)
- [ ] **Fallback data available** (if external service fails)
- [ ] **Idempotency considered** (safe to retry)
- [ ] **Dead letter queue** (for failed async invocations)

**Reference:** NMP01-LEE-23 (Circuit Breaker), NMP01-LEE-17 (HA Integration)

---

## 📝 DOCUMENTATION

### Code Documentation
- [ ] **README updated** (deployment instructions current)
- [ ] **Architecture diagrams current** (if changed)
- [ ] **API documentation updated** (if public API)
- [ ] **Configuration guide complete** (all env vars documented)
- [ ] **Troubleshooting guide available** (common issues documented)

### Operational Documentation
- [ ] **Runbook created/updated** (operational procedures)
- [ ] **Rollback plan documented** (how to revert)
- [ ] **Monitoring guide** (what to watch)
- [ ] **Alert response procedures** (who to contact, how to respond)
- [ ] **Change log updated** (what's in this deployment)

**Reference:** Documentation standards

---

## 🎯 DEPLOYMENT STRATEGY

### Deployment Method
- [ ] **Deployment method chosen** (all-at-once, canary, blue/green)
- [ ] **Rollback strategy defined** (how to revert if issues)
- [ ] **Traffic shifting configured** (if canary/blue-green)
- [ ] **Smoke tests defined** (post-deployment verification)
- [ ] **Deployment window scheduled** (if required)

### Pre-Deployment
- [ ] **Stakeholders notified** (if user-facing changes)
- [ ] **Maintenance window** (if downtime expected)
- [ ] **Backup created** (current version saved)
- [ ] **Feature flags configured** (if using feature toggles)
- [ ] **Database migrations** (if schema changes)

### Post-Deployment
- [ ] **Smoke tests ready** (quick verification tests)
- [ ] **Monitoring plan** (watch for errors, performance)
- [ ] **Communication plan** (success/failure notifications)
- [ ] **Rollback procedure ready** (quick revert if needed)
- [ ] **On-call schedule** (support coverage)

---

## 🧩 INTEGRATION VERIFICATION

### Home Assistant Integration (if applicable)
- [ ] **Token refresh working** (not expiring)
- [ ] **API endpoints validated** (HA API accessible)
- [ ] **Entity states syncing** (data flowing correctly)
- [ ] **Websocket connection stable** (if used)
- [ ] **Error handling for HA failures** (graceful degradation)

**Reference:** NMP01-LEE-17 (HA Core API Integration)

### AWS Services Integration
- [ ] **DynamoDB tables exist** (if using DynamoDB)
- [ ] **S3 buckets accessible** (if using S3)
- [ ] **SNS topics created** (if using notifications)
- [ ] **SQS queues ready** (if using queues)
- [ ] **EventBridge rules configured** (if using events)

---

## 🎓 DEPLOYMENT CHECKLIST

### Critical Path (Must Complete)

**1. Testing** (5 minutes)
- Run full test suite
- Verify all tests pass
- Check coverage acceptable

**2. Package** (3 minutes)
- Build deployment package
- Verify size under 128MB
- Check dependencies included

**3. Configuration** (2 minutes)
- Verify environment variables
- Check IAM permissions
- Validate runtime settings

**4. Deploy** (5 minutes)
- Execute deployment
- Run smoke tests
- Monitor for errors

**5. Verify** (5 minutes)
- Check CloudWatch logs
- Test critical paths
- Confirm metrics reporting

### Post-Deployment Monitoring (24 hours)

- [ ] **Error rate normal** (< baseline)
- [ ] **Latency acceptable** (< SLA)
- [ ] **Throughput sufficient** (handling load)
- [ ] **Memory stable** (no leaks)
- [ ] **No alarms triggered** (everything green)

---

## ⚠️ GO/NO-GO DECISION

### Deployment Blockers (Must Fix)

🔴 **Critical - Do NOT Deploy:**
- Tests failing
- Security vulnerabilities
- Missing required permissions
- Package > 128MB
- No rollback plan

🟡 **High - Fix Before Deploy:**
- Performance degraded
- Missing error handling
- Documentation incomplete
- Monitoring not configured
- Secrets hardcoded

🟢 **Medium - Document and Monitor:**
- Minor optimization opportunities
- Non-critical documentation gaps
- Nice-to-have features missing

### Sign-Off Required

**Technical Lead:**
- [ ] Architecture approved
- [ ] Code reviewed
- [ ] Tests verified
- Signature: _______________

**DevOps Lead:**
- [ ] Deployment strategy approved
- [ ] Infrastructure ready
- [ ] Monitoring configured
- Signature: _______________

**Security Lead:**
- [ ] Security review complete
- [ ] Vulnerabilities addressed
- [ ] Compliance verified
- Signature: _______________

**Date:** _______________  
**Target Environment:** _______________  
**Deployment ID:** _______________

---

## 📊 QUICK REFERENCE

### Pre-Deployment Commands

```bash
# Run tests
pytest tests/ -v

# Check package size
du -sh dist/

# Validate config
python scripts/validate_config.py

# Security scan
safety check

# Deploy (when ready)
./scripts/deploy.sh staging
```

### Post-Deployment Verification

```bash
# Check logs
aws logs tail /aws/lambda/function-name --follow

# Test endpoint
curl https://api.example.com/health

# Check metrics
aws cloudwatch get-metric-statistics --namespace AWS/Lambda \
  --metric-name Errors --dimensions Name=FunctionName,Value=function-name
```

---

## 🔗 RELATED RESOURCES

**Pre-Deployment:**
- CHK-01: Code Review Checklist
- WF-01: Add Feature Workflow
- LESS-15: SUGA Verification

**Performance:**
- ARCH-04: ZAPH Pattern
- NMP01-LEE-16: Fast Path Optimization

**Resilience:**
- NMP01-LEE-23: Circuit Breaker
- BUG-## entries: Known Issues

**Configuration:**
- INT-03: SECURITY Interface
- INT-08: CONFIG Interface

---

**END OF CHECKLIST-02**

**Related checklists:** CHK-01 (Code Review), CHK-03 (Documentation Quality)

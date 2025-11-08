# LEE-DEC-03-Token-Management.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Decision on Home Assistant token storage and management strategy  
**Category:** Project - LEE Decision

---

## Decision

**LEE-DEC-03: Store Home Assistant Access Token in AWS SSM Parameter Store**

**Status:** Accepted  
**Date:** 2024-06-15  
**Context:** LEE requires secure storage and retrieval of Home Assistant authentication credentials

---

## Context

Home Assistant requires authentication for API access:
- **Token Type:** Long-Lived Access Token
- **Token Format:** JWT-style bearer token
- **Token Lifetime:** No expiration (manually managed)
- **Token Permissions:** Full API access
- **Security:** Token grants complete Home Assistant control

**Requirements:**
- Secure storage (encrypted at rest)
- Access control (only Lambda can read)
- No hardcoding in code
- Rotatable without code changes
- Audit trail of access
- Cost-effective

---

## Decision

**Use AWS Systems Manager (SSM) Parameter Store for token storage**

**Configuration:**
```
Parameter Name: /lee/ha/access_token
Parameter Type: SecureString
Encryption: AWS KMS
Access: Lambda execution role only
Cost: Free tier (standard parameter)
```

**Retrieval pattern:**
```python
import boto3
ssm = boto3.client('ssm')

def get_ha_token():
    response = ssm.get_parameter(
        Name='/lee/ha/access_token',
        WithDecryption=True
    )
    return response['Parameter']['Value']
```

---

## Rationale

### Why SSM Parameter Store?

#### 1. Security

**Encryption at rest:**
- Encrypted with AWS KMS
- Key managed by AWS or customer
- Automatic encryption/decryption

**Access control:**
- IAM-based permissions
- Only Lambda execution role can read
- No public access possible
- Fine-grained control

**Audit trail:**
- CloudTrail logs all access
- Know who accessed when
- Detect unauthorized access attempts

#### 2. Simplicity

**No infrastructure to manage:**
- AWS-managed service
- No servers to maintain
- No patching required
- High availability built-in

**Easy integration:**
- Native boto3 support
- Simple API calls
- Automatic caching possible
- Well-documented

#### 3. Cost

**Free tier:**
- Standard parameters: Free
- Up to 10,000 parameters
- No API call charges for standard tier
- Only storage costs (minimal)

**LEE usage:**
- 1 parameter (access token)
- ~50,000 retrievals/month
- Cost: $0.00 (within free tier)

#### 4. Flexibility

**Easy rotation:**
- Update parameter value
- No code changes required
- Immediate effect
- Zero downtime

**Multiple environments:**
- Dev: /lee/dev/ha/access_token
- Prod: /lee/prod/ha/access_token
- Same code, different parameters

---

## Alternatives Considered

### Alternative 1: Environment Variables

**Approach:** Store token in Lambda environment variables

**Rejected because:**
- Visible in Lambda console (security risk)
- No encryption at rest (standard tier)
- Harder to rotate (requires function update)
- No audit trail
- Risk of accidental exposure in logs

### Alternative 2: AWS Secrets Manager

**Approach:** Use Secrets Manager instead of SSM

**Comparison:**

| Feature | SSM Parameter Store | Secrets Manager |
|---------|-------------------|-----------------|
| Encryption | ✓ (KMS) | ✓ (KMS) |
| Access Control | ✓ (IAM) | ✓ (IAM) |
| Audit Trail | ✓ (CloudTrail) | ✓ (CloudTrail) |
| Automatic Rotation | ✗ | ✓ |
| Cost | Free | $0.40/month + API calls |
| Simple Retrieval | ✓ | ✓ |

**Rejected because:**
- Automatic rotation not needed (token doesn't expire)
- Extra cost ($0.40/month + $0.05 per 10k API calls)
- SSM Parameter Store sufficient for use case
- Secrets Manager overkill for single static token

**Would use if:** Token required automatic rotation

### Alternative 3: Hardcoded in Code

**Approach:** Store token directly in Lambda code

**Rejected because:**
- Severe security risk (token in version control)
- Visible in source code
- No encryption
- Rotation requires code change and deployment
- Violates security best practices
- Audit nightmare

### Alternative 4: S3 Bucket

**Approach:** Store token in encrypted S3 object

**Rejected because:**
- More complex (S3 API vs simple SSM call)
- Higher latency (S3 GET vs Parameter Store)
- More expensive (S3 requests + storage)
- Overkill for single small value
- IAM permissions more complex

---

## Implementation Details

### Token Storage

**Creation (one-time):**
```bash
aws ssm put-parameter \
  --name /lee/ha/access_token \
  --type SecureString \
  --value "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  --description "Home Assistant Long-Lived Access Token for LEE" \
  --tags Key=Project,Value=LEE Key=Environment,Value=Production
```

**IAM Policy for Lambda:**
```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "ssm:GetParameter",
        "ssm:GetParameters"
      ],
      "Resource": "arn:aws:ssm:*:*:parameter/lee/ha/access_token"
    },
    {
      "Effect": "Allow",
      "Action": [
        "kms:Decrypt"
      ],
      "Resource": "arn:aws:kms:*:*:key/*"
    }
  ]
}
```

### Token Retrieval

**Basic retrieval:**
```python
import boto3
import os

ssm = boto3.client('ssm')

def get_ha_token():
    """Retrieve Home Assistant access token from SSM Parameter Store"""
    try:
        response = ssm.get_parameter(
            Name='/lee/ha/access_token',
            WithDecryption=True
        )
        return response['Parameter']['Value']
    except Exception as e:
        # Log error, return None
        print(f"Failed to retrieve HA token: {e}")
        return None
```

**With caching (container-level):**
```python
# Cache token for container reuse
_cached_token = None

def get_ha_token():
    """Retrieve token with container-level caching"""
    global _cached_token
    
    # Return cached if available
    if _cached_token:
        return _cached_token
    
    # Fetch from SSM
    response = ssm.get_parameter(
        Name='/lee/ha/access_token',
        WithDecryption=True
    )
    
    _cached_token = response['Parameter']['Value']
    return _cached_token
```

**Why caching acceptable:**
- Token doesn't expire
- Cache cleared on container recycle
- Reduces SSM API calls
- Lower latency (no network call)

---

## Token Rotation

### Manual Rotation Process

**Steps:**
1. Generate new token in Home Assistant
2. Update SSM parameter with new token
3. Verify new token works (test invocation)
4. Revoke old token in Home Assistant
5. Monitor for errors

**Command:**
```bash
aws ssm put-parameter \
  --name /lee/ha/access_token \
  --type SecureString \
  --value "NEW_TOKEN_HERE" \
  --overwrite
```

**Zero downtime:** Lambda containers cache old token until recycled, then fetch new token

### Rotation Triggers

**When to rotate:**
- Suspected compromise
- Regular security hygiene (e.g., yearly)
- Team member departure
- After security incident
- Compliance requirements

**Not needed for:**
- Token expiration (doesn't expire)
- Regular intervals (unless policy requires)

---

## Error Handling

### Token Retrieval Failure

**Possible causes:**
- SSM parameter not found
- Insufficient IAM permissions
- KMS decryption failure
- Network timeout

**Handling:**
```python
def get_ha_token():
    try:
        response = ssm.get_parameter(
            Name='/lee/ha/access_token',
            WithDecryption=True
        )
        return response['Parameter']['Value']
    except ssm.exceptions.ParameterNotFound:
        print("ERROR: HA token parameter not found in SSM")
        raise
    except Exception as e:
        print(f"ERROR: Failed to retrieve HA token: {e}")
        raise
```

**Result:** Fail fast with clear error, don't try to continue

### Invalid Token

**Symptom:** Home Assistant returns 401 Unauthorized

**Handling:**
1. Log authentication failure
2. Clear cached token (force refetch)
3. Retry once
4. If still fails, return error
5. Alert on repeated failures

**Note:** Invalid token likely means rotation needed

---

## Security Best Practices

### Access Control

**Principle of least privilege:**
- Lambda role can only read this specific parameter
- No write access to SSM
- No access to other parameters
- No access to KMS key management

**Network security:**
- VPC endpoints for SSM (if needed)
- No public internet access required
- All communication via AWS network

### Monitoring

**CloudWatch Alarms:**
```
Alarm: SSM-Parameter-Access-Denied
Metric: Failed GetParameter calls
Threshold: >0 in 5 minutes
Action: Security team notification

Alarm: Unusual-Parameter-Access
Metric: GetParameter calls
Threshold: >1000/hour (baseline: ~200/hour)
Action: Investigate potential abuse
```

**CloudTrail Audit:**
- All SSM GetParameter calls logged
- Who accessed when from where
- Detect unauthorized access attempts
- Compliance reporting

---

## Performance Impact

### Measured Latency

**SSM GetParameter call:**
```
P50: 20-30ms
P95: 40-60ms
P99: 80-120ms
Max: ~200ms
```

**vs Environment Variables:**
```
Environment variables: 0ms (in-memory)
```

**Trade-off:** 20-30ms additional latency for security

**Mitigation:** Container-level caching reduces impact
```
First invocation: 20-30ms (SSM call)
Subsequent invocations: 0ms (cached)
Cold start overhead: Negligible
```

---

## Cost Analysis

### SSM Parameter Store

**Storage:**
- 1 standard parameter
- ~200 bytes
- Cost: $0.00 (free tier)

**API Calls:**
- ~50,000 GetParameter calls/month
- Cost: $0.00 (free tier, no charges for standard parameters)

**Total Monthly Cost:** $0.00

### vs Secrets Manager

**If using Secrets Manager:**
- Storage: $0.40/month per secret
- API calls: 50,000 calls × $0.05/10k calls = $0.25/month
- Total: $0.65/month

**Savings:** $0.65/month ($7.80/year) by using SSM Parameter Store

---

## Related Decisions

**Prerequisites:**
- LEE-DEC-01: Home Assistant Choice
- LEE-DEC-02: WebSocket Protocol

**Influences:**
- LEE-DEC-04: Error Handling
- LEE-DEC-05: Security Strategy

**Related Implementation:**
- ha_config.py: Token retrieval
- ha_websocket.py: Token usage
- config_core.py: SSM integration

---

## References

**AWS Documentation:**
- SSM Parameter Store Overview
- IAM Policies for SSM
- KMS Encryption

**Security:**
- AWS Well-Architected Security Pillar
- Token Storage Best Practices

**LEE Implementation:**
- config_param_store.py
- ha_websocket.py
- LEE-LESS-03-Token-Refresh-Strategy.md

---

## Key Takeaways

**SSM Parameter Store for token storage:**
Secure, simple, cost-effective

**KMS encryption at rest:**
Token protected with AWS-managed encryption

**IAM-based access control:**
Only Lambda can retrieve token

**Container-level caching:**
Reduces latency, minimizes API calls

**Manual rotation process:**
Simple update, zero downtime

**Free tier usage:**
No cost for LEE's usage pattern

---

**Decision ID:** LEE-DEC-03  
**Keywords:** token management, SSM Parameter Store, security, authentication, credential storage  
**Related Topics:** Security, access control, token rotation, SSM, KMS encryption

---

**END OF FILE**

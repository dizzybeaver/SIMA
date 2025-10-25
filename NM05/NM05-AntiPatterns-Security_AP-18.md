# NM05-AntiPatterns-Security_AP-18.md - AP-18

# AP-18: Hardcoded Secrets

**Category:** NM05 - Anti-Patterns
**Topic:** Security
**Priority:** 🔴 CRITICAL
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Embedding secrets (API keys, passwords, tokens) directly in source code exposes them to anyone with repository access and makes secrets impossible to rotate without code changes.

---

## Context

Source code is widely distributed (Git, CI/CD, backups, developers' machines) and often eventually made public. Secrets in code are permanently compromised the moment they're committed.

**Problem:** Security breach, unauthorized access, credential theft, financial loss.

---

## Content

### The Anti-Pattern

```python
# ❌ CRITICAL SECURITY VULNERABILITY
# In http_client_core.py
API_KEY = "sk_live_abc123xyz789..."  # Hardcoded secret!
API_SECRET = "secret_key_abc123xyz"  # Hardcoded secret!

def call_external_api():
    headers = {
        "Authorization": f"Bearer {API_KEY}"
    }
    response = requests.post(
        "https://api.example.com/endpoint",
        headers=headers
    )
    return response

# In database_core.py
DB_PASSWORD = "MyS3cr3tP@ssw0rd!"  # Hardcoded secret!

def connect_database():
    conn = psycopg2.connect(
        host="db.example.com",
        user="admin",
        password=DB_PASSWORD  # Exposed in code!
    )
    return conn
```

**This secret is now:**
1. ✅ In Git history forever
2. ✅ On every developer's machine
3. ✅ In CI/CD logs
4. ✅ In build artifacts
5. ✅ In Docker images
6. ✅ Visible to anyone with repo access

### Why This Is Critical

**1. Secrets Are Permanently Compromised**
```bash
# Even if you remove it later:
git log -p | grep "API_KEY"
# Still shows the secret in history!
```

**2. Cannot Rotate Without Code Deploy**
```
Secret compromised?
├─ Must change code
├─ Must rebuild
├─ Must deploy
└─ Takes hours/days (should take seconds)
```

**3. Wide Exposure Surface**
- Every developer has it
- Every CI/CD run logs it
- Every backup contains it
- Every code review shows it
- GitHub search can find it

**4. Automated Scanners Find Them**
```
GitHub secret scanning
GitGuardian
TruffleHog
etc.
```
These tools automatically scan public repos and notify attackers!

### Real Incident Example

**What happened:**
1. Developer commits API key in code
2. Pushes to GitHub
3. GitGuardian scanner finds it
4. Attacker is notified within minutes
5. Attacker uses key to access service
6. Company receives $10,000 bill for API usage
7. Data breach exposes customer information

**Timeline:**
- T+0: Commit pushed to GitHub
- T+2min: Secret scanner detects key
- T+5min: Attacker has key
- T+1hr: Unauthorized access begins
- T+24hr: Fraud detected
- T+48hr: Key rotated, services restored
- Damage: $10k bill + data breach + reputation damage

### Correct Approach

**Option 1: Environment Variables**
```python
# ✅ CORRECT - From environment
import os

def call_external_api():
    api_key = os.environ.get('API_KEY')
    if not api_key:
        raise ValueError("API_KEY environment variable not set")
    
    headers = {"Authorization": f"Bearer {api_key}"}
    response = requests.post(
        "https://api.example.com/endpoint",
        headers=headers
    )
    return response
```

**Option 2: AWS Systems Manager (SSM) Parameter Store**
```python
# ✅ CORRECT - From SSM (SUGA-ISP pattern)
import gateway

def call_external_api():
    # Gateway handles SSM retrieval and caching
    api_key = gateway.config_get('/api/external/key')
    
    headers = {"Authorization": f"Bearer {api_key}"}
    response = requests.post(
        "https://api.example.com/endpoint",
        headers=headers
    )
    return response
```

**Option 3: AWS Secrets Manager**
```python
# ✅ CORRECT - From Secrets Manager
import boto3

def get_secret(secret_name):
    client = boto3.client('secretsmanager')
    response = client.get_secret_value(SecretId=secret_name)
    return json.loads(response['SecretString'])

def call_external_api():
    secrets = get_secret('prod/api/keys')
    api_key = secrets['api_key']
    
    headers = {"Authorization": f"Bearer {api_key}"}
    # ... rest of code
```

### SUGA-ISP Pattern (Recommended)

**DEC-21: SSM Parameter Store for Secrets**
```python
# Configuration in SSM Parameter Store:
# /lambda-execution-engine/home_assistant/token = "actual_token"

# Code uses gateway:
import gateway

# Gateway automatically:
# 1. Retrieves from SSM
# 2. Caches securely
# 3. Handles errors
# 4. Logs access (not value!)
token = gateway.config_get('/home_assistant/token')
```

**Benefits:**
- ✅ Secrets never in code
- ✅ Can rotate without deploy
- ✅ Access is audited (CloudTrail)
- ✅ Encrypted at rest (KMS)
- ✅ Fine-grained IAM permissions

### Secret Detection

**How to Find Hardcoded Secrets:**
```bash
# Check for common patterns
grep -r "password.*=" *.py
grep -r "api_key.*=" *.py
grep -r "secret.*=" *.py
grep -r "token.*=" *.py

# Check for actual secret-looking strings
grep -r "['\"][a-zA-Z0-9]{32,}['\"]" *.py
```

**Use automated tools:**
```bash
# Install truffleHog
pip install truffleHog

# Scan repository
truffleHog --regex --entropy=True .
```

### Rotation Strategy

**With hardcoded secrets (WRONG):**
```
1. Change code
2. Commit and push
3. Build new artifact
4. Deploy to Lambda
5. Hope nothing breaks
Time: Hours to days
```

**With SSM/Secrets Manager (CORRECT):**
```
1. Update value in SSM
2. (Optional) Restart Lambda to clear cache
Time: Seconds
```

### Common Mistakes

**Mistake 1: Secrets in Config Files**
```yaml
# ❌ config.yaml (still committed to Git!)
database:
  password: "MyS3cr3tP@ssw0rd"  # Still exposed!
```

**Mistake 2: Secrets in Comments**
```python
# ❌ Even in comments it's compromised
# API_KEY = "sk_live_abc123..."  # TODO: Remove this
```

**Mistake 3: Base64 Encoding**
```python
# ❌ Base64 is NOT encryption!
API_KEY = base64.b64decode("c2tfbGl2ZV9hYmMxMjM=")  # Still visible!
```

**Mistake 4: .env Files in Git**
```bash
# ❌ .env file committed to Git
API_KEY=sk_live_abc123...
```

### What To Do If Secret Exposed

**Immediate actions:**
1. ⚠️ **Revoke the secret immediately** (don't wait for code deploy)
2. ⚠️ **Generate new secret**
3. ⚠️ **Update in SSM/environment**
4. ⚠️ **Audit access logs** (who used the compromised secret?)
5. ⚠️ **Remove from Git history** (use git-filter-repo)
6. ⚠️ **Notify security team**

**Prevention:**
1. ✅ Use pre-commit hooks (detect-secrets)
2. ✅ Use secret scanning (GitGuardian, GitHub)
3. ✅ Code review checklist includes secret check
4. ✅ Never commit .env files
5. ✅ Add secrets patterns to .gitignore

---

## Related Topics

- **AP-17**: No Input Validation - Another critical security issue
- **AP-19**: SQL Injection - Database security
- **DEC-21**: SSM Token-Only Configuration - SUGA-ISP secret management
- **INT-05**: CONFIG Interface - How to retrieve config/secrets
- **LESS-10**: Security Lessons - Real incidents

---

## Keywords

hardcoded secrets, API keys, passwords, tokens, credentials, security breach, SSM Parameter Store, Secrets Manager, environment variables, secret rotation

---

## Version History

- **2025-10-24**: Created - Extracted from Part 2, added real incident example and SUGA-ISP patterns

---

**File:** `NM05-AntiPatterns-Security_AP-18.md`
**End of Document**

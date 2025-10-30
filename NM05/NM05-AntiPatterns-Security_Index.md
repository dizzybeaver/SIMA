# NM05-AntiPatterns-Security_Index.md

# Anti-Patterns - Security Index

**Category:** NM05 - Anti-Patterns
**Topic:** Security
**Items:** 3
**Last Updated:** 2025-10-24

---

## Topic Overview

**Description:** Critical security anti-patterns covering input validation failures, hardcoded secrets, and SQL injection vulnerabilities. These patterns represent the most common and dangerous security flaws that can compromise system integrity, expose sensitive data, and enable attackers to execute malicious operations.

**Keywords:** security, validation, secrets, SQL injection, authentication, authorization, encryption

**Priority Distribution:** 3 Critical

---

## Individual Files

### AP-17: No Input Validation
- **File:** `NM05-AntiPatterns-Security_AP-17.md`
- **Summary:** Always validate, sanitize, and verify all inputs before processing
- **Priority:** 🔴 Critical
- **Impact:** Enables injection attacks, buffer overflows, unauthorized access, data corruption

### AP-18: Hardcoded Secrets in Code
- **File:** `NM05-AntiPatterns-Security_AP-18.md`
- **Summary:** Never hardcode credentials - use SSM Parameter Store or Secrets Manager
- **Priority:** 🔴 Critical
- **Impact:** Credentials in version control, exposed in logs, easy compromise, credential rotation impossible

### AP-19: SQL Injection Patterns
- **File:** `NM05-AntiPatterns-Security_AP-19.md`
- **Summary:** Use parameterized queries, never string concatenation for SQL
- **Priority:** 🔴 Critical
- **Impact:** Database compromise, data theft, unauthorized modifications, complete system takeover

---

## Common Themes

All three security anti-patterns share a common root cause: **trusting input without verification**. Whether it's user data (AP-17), credentials in source code (AP-18), or SQL parameters (AP-19), the fundamental principle is the same: **Never trust, always verify.**

**Defense in Depth Philosophy:**
Security is implemented in layers:
1. **Input validation** (AP-17) - First line of defense
2. **Secure storage** (AP-18) - Protect sensitive data at rest
3. **Safe operations** (AP-19) - Prevent exploitation during use

**Attack Surface:**
These patterns cover the three most common attack vectors in Lambda applications:
- **Input attacks** - Malicious data injection
- **Credential theft** - Exposed secrets enable impersonation
- **Query manipulation** - SQL injection for unauthorized access

---

## Related Topics

**Within NM05 (Anti-Patterns):**
- AP-10: Sentinel Objects Crossing Boundaries - Related validation concept
- ErrorHandling patterns (AP-14 to AP-16) - Proper error handling prevents information leakage

**Other Categories:**
- **NM01-Architecture** - INT-03 (SECURITY interface)
- **NM04-Decisions** - DEC-21 (SSM token-only approach)
- **NM03-Operations** - ERR-02 (Error propagation without data leaks)
- **NM06-Lessons** - Security-related operational lessons

**External References:**
- OWASP Top 10 security risks
- AWS Security Best Practices
- PEP 249 (DB-API parameterized queries)

---

**Navigation:**
- **Up:** [Category Index - NM05-AntiPatterns_Index.md]
- **Siblings:** [Import_Index, Implementation_Index, Dependencies_Index, Critical_Index, Concurrency_Index, Performance_Index, ErrorHandling_Index, Quality_Index, Testing_Index, Documentation_Index, Process_Index]

---

**End of Index**

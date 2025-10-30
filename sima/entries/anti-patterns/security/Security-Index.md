# File: Security-Index.md

**Category:** Anti-Patterns  
**Topic:** Security  
**Items:** 3  
**Version:** 1.0.0

---

## FILES

### AP-17: Hardcoded Secrets
- **Severity:** 🔴 Critical
- **Problem:** API keys, passwords in code
- **Solution:** Use gateway.config_get() + SSM

### AP-18: Logging Sensitive Data
- **Severity:** 🔴 Critical
- **Problem:** Passwords, PII in logs
- **Solution:** Redact before logging

### AP-19: Sentinel Objects Crossing Boundaries
- **Severity:** 🔴 Critical
- **Problem:** Internal sentinels leak out
- **Solution:** Sanitize at interface layer (DEC-05)
- **Impact:** BUG-01 (535ms penalty)

---

**Common Theme:** Keep internal implementation details internal. Sanitize at boundaries.

---

**Keywords:** security, secrets, PII, sentinel objects

**END OF INDEX**

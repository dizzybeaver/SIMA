# NM06-Lessons-Operations_Index.md

# Lessons - Operations Index

**Category:** NM06 - Lessons  
**Topic:** Operations  
**Items:** 12  
**Last Updated:** 2025-10-25 (added LESS-23, 24, 27-39, 30, 32, 34-38-42, 36)

---

## Topic Overview

**Description:** Deployment procedures, operational practices, monitoring strategies, file management protocols, rate limiting, validation protocols, and systemic issue resolution.

**Keywords:** deployment, operations, monitoring, reliability, verification, version-control, file-management, rate-limiting, validation, systemic-issues

---

## Individual Files

### LESS-09: Partial Deployments Are Dangerous
- **File:** `NM06-Lessons-Operations_LESS-09.md`
- **Summary:** Deploy atomically or risk inconsistent state across Lambda instances
- **Related:** LESS-10, DEC-09, BUG-03
- **Priority:** CRITICAL

### LESS-10: Cold Start Optimization is Worth It
- **File:** `NM06-Lessons-Operations_LESS-10.md`
- **Summary:** 2-second improvement in cold start has cascading benefits
- **Related:** ARCH-07, DEC-07, PATH-01
- **Priority:** HIGH

### LESS-15: File Verification Protocol
- **File:** `NM06-Lessons-Operations_LESS-15.md`
- **Summary:** 5-step protocol prevents deployment and modification errors
- **Related:** AP-28, LESS-01, LESS-09
- **Priority:** CRITICAL

### LESS-19: Security Validations Are Non-Negotiable
- **File:** `NM06-Lessons-Operations_LESS-19.md`
- **Summary:** Token validation must happen before any processing
- **Related:** AP-17, INT-03, DEC-06
- **Priority:** CRITICAL

### LESS-23: Question "Intentional" Design Decisions
- **File:** `NM06-Lessons-Operations_LESS-23.md`
- **Summary:** Documentation can rationalize violations; verify implementation matches principles
- **Related:** LESS-11, LESS-32, AP-25
- **Priority:** CRITICAL
- **Added:** 2025-10-25

### LESS-24: Rate Limit Tuning Per Operational Characteristics
- **File:** `NM06-Lessons-Operations_LESS-24.md`
- **Summary:** Different interfaces need different rate limits based on read/write/compute patterns
- **Related:** LESS-21, INT-01 through INT-12, LESS-37
- **Priority:** HIGH
- **Added:** 2025-10-25

### LESS-27-39: Comprehensive Operations Enable Self-Service
- **File:** `NM06-Lessons-Operations_LESS-27-39.md`
- **Summary:** Complete operational primitives allow users to debug/fix independently
- **Related:** LESS-10, LESS-30, INT-07
- **Priority:** MEDIUM
- **Added:** 2025-10-25

### LESS-30: Optimization Tools Reduce Query Response Time
- **File:** `NM06-Lessons-Operations_LESS-30.md`
- **Summary:** Support tools provide 80% query time reduction vs manual neural map searches
- **Related:** LESS-49, LESS-50, LESS-52
- **Priority:** MEDIUM
- **Added:** 2025-10-25

### LESS-32: Systemic Issues Require Systemic Solutions
- **File:** `NM06-Lessons-Operations_LESS-32.md`
- **Summary:** 4/4 interfaces with same violation indicates architectural gap, not random bugs
- **Related:** LESS-23, LESS-36, LESS-34-38-42
- **Priority:** CRITICAL
- **Added:** 2025-10-25

### LESS-34-38-42: Comprehensive Validation Enables Confident Completion
- **File:** `NM06-Lessons-Operations_LESS-34-38-42.md`
- **Summary:** System-wide verification creates objective completion criteria
- **Related:** LESS-15, LESS-32, LESS-23
- **Priority:** HIGH
- **Added:** 2025-10-25

### LESS-36: Infrastructure Code Has Higher Anti-Pattern Risk
- **File:** `NM06-Lessons-Operations_LESS-36.md`
- **Summary:** Infrastructure modules show 100% violation rate vs 0% in application code
- **Related:** LESS-32, LESS-23, AP-01 through AP-28
- **Priority:** MEDIUM
- **Added:** 2025-10-25

### LESS-53: File Version Incrementation Protocol
- **File:** `NM06-Lessons-Operations_LESS-53.md`
- **Summary:** Always increment version numbers for instant cache detection
- **Related:** LESS-15, LESS-09, LESS-01
- **Priority:** CRITICAL

---

## Cross-Topic Relationships

**Related Topics:**
- CoreArchitecture (deployment affects architecture reliability)
- Performance (cold start optimization)
- Documentation (operational procedures must be documented)
- Evolution (LESS-14 evolution needs careful deployment)
- Optimization (LESS-30 tools, LESS-24 rate limiting)

**Frequently Accessed Together:**
- When deploying: LESS-09, LESS-15, LESS-53
- When optimizing: LESS-10, LESS-30
- When securing: LESS-19, LESS-24
- When managing files: LESS-53, LESS-15
- When validating: LESS-23, LESS-32, LESS-34-38-42
- When debugging systemic issues: LESS-32, LESS-36, LESS-23

---

**Navigation:**
- **Up:** Lessons Index (NM06-Lessons_Index.md)
- **Sibling Topics:** CoreArchitecture, Performance, Documentation, Evolution, Learning, Optimization

---

**End of Index**

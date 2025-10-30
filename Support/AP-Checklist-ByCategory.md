# AP-Checklist-ByCategory.md
**Complete Anti-Pattern Reference Table (v3 Paths)**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: All 28 anti-patterns organized by category

---

## 📊 COMPLETE REFERENCE TABLE

| AP | Category | Severity | Problem | Instead | v3 Location |
|----|----------|----------|---------|---------|-------------|
| **IMPORT & ARCHITECTURE** |
| AP-01 | Import | 🔴 | Direct imports | `import gateway` | NM05/.../Import_AP-01.md |
| AP-02 | Import | 🔴 | Bypass gateway | Gateway only | NM05/.../Import_AP-02.md |
| AP-03 | Import | 🔴 | Circular deps | Follow layers | NM05/.../Import_AP-03.md |
| AP-04 | Import | 🔴 | Break layers | Respect DEP | NM05/.../Import_AP-04.md |
| AP-05 | Import | 🟡 | Subdirectories | Flat structure | NM05/.../Import_AP-05.md |
| **IMPLEMENTATION** |
| AP-06 | Implementation | 🟠 | God objects | Split by interface | NM05/.../Implementation_AP-06.md |
| AP-07 | Implementation | 🟡 | Large modules (>400) | Keep <400 lines | NM05/.../Implementation_AP-07.md |
| **CONCURRENCY** |
| AP-08 | Concurrency | 🔴 | Threading locks | Remove locks | NM05/.../Concurrency_AP-08.md |
| AP-11 | Concurrency | 🟠 | Race conditions | Single-threaded | NM05/.../Concurrency_AP-11.md |
| AP-13 | Concurrency | 🟠 | Multiprocessing | Single process | NM05/.../Concurrency_AP-13.md |
| **DEPENDENCIES** |
| AP-09 | Dependencies | 🔴 | Heavy libs | <128MB total | NM05/.../Dependencies_AP-09.md |
| **CRITICAL** |
| AP-10 | Critical | 🔴 | Mutable defaults | None default | NM05/.../Critical_AP-10.md |
| **PERFORMANCE** |
| AP-12 | Performance | 🟡 | Premature opt | Measure first | NM05/.../Performance_AP-12.md |
| **ERROR HANDLING** |
| AP-14 | ErrorHandling | 🟠 | Bare except | Specific types | NM05/.../ErrorHandling_AP-14.md |
| AP-15 | ErrorHandling | 🟠 | Swallow errors | Log + handle | NM05/.../ErrorHandling_AP-15.md |
| AP-16 | ErrorHandling | 🟡 | No context | Use `from` | NM05/.../ErrorHandling_AP-16.md |
| **SECURITY** |
| AP-17 | Security | 🔴 | Hardcoded secrets | SSM + gateway | NM05/.../Security_AP-17.md |
| AP-18 | Security | 🔴 | Log sensitive | Redact PII | NM05/.../Security_AP-18.md |
| AP-19 | Security | 🔴 | Sentinel leaks | Sanitize router | NM05/.../Security_AP-19.md |
| **QUALITY** |
| AP-20 | Quality | 🟠 | Hardcoded config | gateway.config_* | NM05/.../Quality_AP-20.md |
| AP-21 | Quality | 🟡 | Magic numbers | Named constants | NM05/.../Quality_AP-21.md |
| AP-22 | Quality | 🟡 | Copy-paste | Extract shared | NM05/.../Quality_AP-22.md |
| **TESTING** |
| AP-23 | Testing | 🟠 | No tests | Write tests | NM05/.../Testing_AP-23.md |
| AP-24 | Testing | 🟠 | Success only | Test failures | NM05/.../Testing_AP-24.md |
| **DOCUMENTATION** |
| AP-25 | Documentation | 🟠 | Undocumented | Document DEC | NM05/.../Documentation_AP-25.md |
| AP-26 | Documentation | 🟡 | Stale comments | Sync docs | NM05/.../Documentation_AP-26.md |
| **PROCESS** |
| AP-27 | Process | 🟠 | Skip verification | LESS-15 | NM05/.../Process_AP-27.md |
| AP-28 | Process | 🔴 | Partial read | Read complete | NM05/.../Process_AP-28.md |

**Severity:** 🔴 Critical | 🟠 High | 🟡 Medium

---

## 📁 CATEGORY ROUTING

### Import Violations (AP-01 to AP-05)
**Index:** NM05/NM05-AntiPatterns-Import_Index.md  
**Count:** 5 patterns  
**Most Critical:** AP-01 (direct imports)

### Implementation (AP-06, AP-07)
**Index:** NM05/NM05-AntiPatterns-Implementation_Index.md  
**Count:** 2 patterns  
**Focus:** Module size, god objects

### Concurrency (AP-08, AP-11, AP-13)
**Index:** NM05/NM05-AntiPatterns-Concurrency_Index.md  
**Count:** 3 patterns  
**Key Point:** Lambda is single-threaded

### Dependencies (AP-09)
**Index:** NM05/NM05-AntiPatterns-Dependencies_Index.md  
**Count:** 1 pattern  
**Limit:** 128MB total

### Critical (AP-10)
**Index:** NM05/NM05-AntiPatterns-Critical_Index.md  
**Count:** 1 pattern  
**Python gotcha:** Mutable defaults

### Performance (AP-12)
**Index:** NM05/NM05-AntiPatterns-Performance_Index.md  
**Count:** 1 pattern  
**Rule:** Measure before optimizing

### Error Handling (AP-14, AP-15, AP-16)
**Index:** NM05/NM05-AntiPatterns-ErrorHandling_Index.md  
**Count:** 3 patterns  
**Focus:** Specific exceptions, context

### Security (AP-17, AP-18, AP-19)
**Index:** NM05/NM05-AntiPatterns-Security_Index.md  
**Count:** 3 patterns  
**Critical:** No secrets, sanitize sentinels

### Quality (AP-20, AP-21, AP-22)
**Index:** NM05/NM05-AntiPatterns-Quality_Index.md  
**Count:** 3 patterns  
**Focus:** Clean code practices

### Testing (AP-23, AP-24)
**Index:** NM05/NM05-AntiPatterns-Testing_Index.md  
**Count:** 2 patterns  
**Rule:** Test failures, not just success

### Documentation (AP-25, AP-26)
**Index:** NM05/NM05-AntiPatterns-Documentation_Index.md  
**Count:** 2 patterns  
**Rule:** Document decisions, keep current

### Process (AP-27, AP-28)
**Index:** NM05/NM05-AntiPatterns-Process_Index.md  
**Count:** 2 patterns  
**Critical:** Never skip verification

---

## 🎯 QUICK LOOKUP BY NUMBER

**AP-01 to AP-05:** Import violations  
**AP-06 to AP-07:** Implementation  
**AP-08, AP-11, AP-13:** Concurrency  
**AP-09:** Dependencies  
**AP-10:** Critical Python gotcha  
**AP-12:** Performance  
**AP-14 to AP-16:** Error handling  
**AP-17 to AP-19:** Security  
**AP-20 to AP-22:** Quality  
**AP-23 to AP-24:** Testing  
**AP-25 to AP-26:** Documentation  
**AP-27 to AP-28:** Process  

---

## 📊 STATISTICS

**By Severity:**
- 🔴 Critical: 8 patterns (29%)
- 🟠 High: 10 patterns (36%)
- 🟡 Medium: 10 patterns (36%)

**By Category:**
- Import: 5 (18%)
- Error Handling: 3 (11%)
- Security: 3 (11%)
- Concurrency: 3 (11%)
- Quality: 3 (11%)
- Implementation: 2 (7%)
- Testing: 2 (7%)
- Documentation: 2 (7%)
- Process: 2 (7%)
- Dependencies: 1 (4%)
- Critical: 1 (4%)
- Performance: 1 (4%)

**ZAPH Tier 1:** AP-01, AP-08, AP-14, AP-19 (4 most frequent)

---

## 🔗 RELATED FILES

**Hub:** ANTI-PATTERNS-CHECKLIST.md  
**Critical:** AP-Checklist-Critical.md  
**Scenarios:** AP-Checklist-Scenarios.md  
**Gateway:** NM00/NM00-Quick_Index.md  
**ZAPH:** NM00/NM00B-ZAPH-Tier1.md

---

**END OF CATEGORY TABLE**

**Lines:** ~175 (properly sized)  
**Use:** Quick reference for any anti-pattern  
**Update:** When new pattern added or severity changes

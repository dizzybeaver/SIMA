# ANTI-PATTERNS CHECKLIST (Hub - SIMA v3)
**SUGA-ISP Lambda Project - Quick Reference Hub**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: Fast routing to anti-pattern knowledge (atomized structure)

---

## 🎯 PURPOSE

This hub file routes you to specific anti-pattern knowledge. All content atomized following SIMA v3 principles.

**Time to find anti-pattern:** < 5 seconds via this hub

---

## 🚨 CRITICAL ANTI-PATTERNS (Check These First)

**Before suggesting ANY solution, verify these 4:**

1. **AP-01:** Direct cross-interface imports → Use `import gateway`  
   📍 Location: NM05/NM05-AntiPatterns-Import_AP-01.md  
   🔥 Severity: 🔴 Critical

2. **AP-08:** Threading locks/primitives → Lambda is single-threaded  
   📍 Location: NM05/NM05-AntiPatterns-Concurrency_AP-08.md  
   🔥 Severity: 🔴 Critical

3. **AP-14:** Bare except clauses → Use specific exceptions  
   📍 Location: NM05/NM05-AntiPatterns-ErrorHandling_AP-14.md  
   🔥 Severity: 🟠 High

4. **AP-19:** Sentinel leaks → Sanitize at router layer  
   📍 Location: NM05/NM05-AntiPatterns-Security_AP-19.md  
   🔥 Severity: 🔴 Critical

**Detailed coverage:** See `AP-Checklist-Critical.md`

---

## 📋 COMPONENT FILES

### AP-Checklist-Critical.md
- 4 critical anti-patterns with examples
- Pre-flight checklist (7 items)
- RED FLAGS (instant NO scenarios)
- **Use:** Before every response

### AP-Checklist-ByCategory.md
- All 28 anti-patterns organized by category
- Quick reference table
- v3 file paths
- Severity ratings
- **Use:** When checking specific categories

### AP-Checklist-Scenarios.md
- Common "Can I" scenarios with responses
- Template responses
- Navigation examples
- Integration with other tools
- **Use:** When user asks "Can I do X?"

---

## 🗂️ ANTI-PATTERNS BY CATEGORY

**Quick routing to categories:**

| Category | Count | Key Patterns | File Path |
|----------|-------|--------------|-----------|
| **Import** | 5 | AP-01 to AP-05 | NM05/.../Import_*.md |
| **Concurrency** | 3 | AP-08, AP-11, AP-13 | NM05/.../Concurrency_*.md |
| **ErrorHandling** | 3 | AP-14, AP-15, AP-16 | NM05/.../ErrorHandling_*.md |
| **Security** | 3 | AP-17, AP-18, AP-19 | NM05/.../Security_*.md |
| **Implementation** | 2 | AP-06, AP-07 | NM05/.../Implementation_*.md |
| **Dependencies** | 1 | AP-09 | NM05/.../Dependencies_*.md |
| **Performance** | 1 | AP-12 | NM05/.../Performance_*.md |
| **Quality** | 3 | AP-20, AP-21, AP-22 | NM05/.../Quality_*.md |
| **Testing** | 2 | AP-23, AP-24 | NM05/.../Testing_*.md |
| **Documentation** | 2 | AP-25, AP-26 | NM05/.../Documentation_*.md |
| **Process** | 2 | AP-27, AP-28 | NM05/.../Process_*.md |
| **Critical** | 1 | AP-10 | NM05/.../Critical_*.md |

**Detailed table:** See `AP-Checklist-ByCategory.md`

---

## 🔍 QUICK DECISION TREE

```
User request received
    ↓
Scan AP-Checklist-Critical.md (5 sec)
    ↓
Contains critical violation? → Reject with explanation
    ↓
Check AP-Checklist-ByCategory.md for relevant category
    ↓
Contains violation? → See AP-Checklist-Scenarios.md for response
    ↓
No violations → Proceed with solution
```

---

## 📚 INTEGRATION WITH V3 STRUCTURE

### With Gateway Layer (NM00/)
- **ZAPH Tier 1:** Contains AP-01, AP-08, AP-14, AP-19  
  📍 NM00/NM00B-ZAPH-Tier1.md
  
- **Quick Index:** Routes "anti-pattern" keyword  
  📍 NM00/NM00-Quick_Index.md

- **Master Index:** Complete AP structure  
  📍 NM00/NM00A-Master_Index.md

### With Other Tools
- **Workflows:** Workflow #5 "Can I" uses this checklist  
  📍 WORKFLOWS-PLAYBOOK.md → Workflow-05-CanI.md
  
- **REF-ID Directory:** Cross-references all AP-## items  
  📍 REF-ID-DIRECTORY.md → REF-ID-Directory-AP-BUG.md
  
- **SESSION-START:** RED FLAGS section references critical APs  
  📍 SESSION-START-Quick-Context.md

---

## 💡 USAGE PATTERNS

### Pattern 1: Pre-Response Check
```
Before suggesting solution:
1. Open: AP-Checklist-Critical.md
2. Scan: 7-item checklist (5 seconds)
3. If violation: Stop and redesign
4. If clear: Proceed
```

### Pattern 2: "Can I" Question
```
User asks: "Can I use threading locks?"
1. Open: AP-Checklist-Scenarios.md
2. Find: Scenario for threading
3. Response: Template + rationale + REF-IDs
```

### Pattern 3: Category Check
```
Reviewing code with suspect pattern:
1. Open: AP-Checklist-ByCategory.md
2. Find: Relevant category (e.g., ErrorHandling)
3. Check: AP-14, AP-15, AP-16
4. Navigate: To specific NM05 file if needed
```

---

## 🎯 FILE LOCATIONS

**Tool Files (in /nmap root or /tools):**
- ANTI-PATTERNS-CHECKLIST.md (this hub)
- AP-Checklist-Critical.md
- AP-Checklist-ByCategory.md
- AP-Checklist-Scenarios.md

**Neural Map Files (in /nmap/NM05/):**
- Category indexes (13 files)
- Individual AP-## files (28 files)

---

## 🔄 MAINTENANCE

**When to update:**
- New anti-pattern identified
- Severity change
- New scenario added
- v3 structure changes

**Update process:**
1. Add to NM05/NM05-AntiPatterns-[Category]_AP-##.md
2. Update category index
3. Update AP-Checklist-ByCategory.md table
4. Update AP-Checklist-Scenarios.md if needed
5. Update ZAPH if critical

---

## 📊 QUICK STATS

- **Total Anti-Patterns:** 28 (AP-01 to AP-28)
- **Critical Severity:** 8 patterns (🔴)
- **High Severity:** 10 patterns (🟠)
- **Medium Severity:** 10 patterns (🟡)
- **ZAPH Tier 1:** 4 patterns (most frequent violations)
- **Categories:** 12

---

## ⚡ QUICK ACTIONS

**I need to:**

→ Check before suggesting code → `AP-Checklist-Critical.md`  
→ Find specific anti-pattern → `AP-Checklist-ByCategory.md`  
→ Answer "Can I" question → `AP-Checklist-Scenarios.md`  
→ See all categories → Table above  
→ Get detailed pattern info → Navigate to NM05/... file

---

**END OF HUB**

**Next files:**
- AP-Checklist-Critical.md (detailed critical patterns)
- AP-Checklist-ByCategory.md (complete table)
- AP-Checklist-Scenarios.md (common scenarios)

**Version:** 3.0.0 (Atomized structure)  
**Total Lines:** ~150 (hub only - properly sized!)

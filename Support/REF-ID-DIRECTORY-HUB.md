# REF-ID-DIRECTORY (Hub - SIMA v3)
**Alphabetical Quick Lookup - All 159+ References**

Version: 3.0.0  
Date: 2025-10-24  
Purpose: Instant routing to any REF-ID (atomized structure)

---

## 🎯 PURPOSE

When you see cross-references like "Related: BUG-01, DEC-05", use this directory to find exact file locations instantly.

**Time saved:** 10-20 seconds per lookup

---

## 📂 COMPONENT FILES

Directory split into focused files for fast lookup:

### REF-ID-Directory-ARCH-INT.md
- **ARCH-01 to ARCH-09:** Architecture patterns (9 items)
- **INT-01 to INT-12:** All 12 interfaces
- **Use:** When looking up architecture or interface refs

### REF-ID-Directory-AP-BUG.md
- **AP-01 to AP-28:** All 28 anti-patterns
- **BUG-01 to BUG-04:** Critical bugs
- **Use:** When checking anti-patterns or bugs

### REF-ID-Directory-DEC.md
- **DEC-01 to DEC-23:** All design decisions
- Organized by: Architecture, Technical, Operational
- **Use:** When looking up "why" something was decided

### REF-ID-Directory-LESS-WISD.md
- **LESS-01 to LESS-21:** All lessons learned
- **WISD-01 to WISD-05:** Synthesized wisdom
- **Use:** When looking up lessons or wisdom

### REF-ID-Directory-Others.md
- **DEP-01 to DEP-05:** Dependency layers
- **DT-01 to DT-13:** Decision trees
- **ERR, FLOW, PATH:** Operation patterns
- **RULE-01 to RULE-04:** Foundation rules
- **FW, TRACE, META:** Frameworks and tracing
- **Use:** When looking up other REF-ID types

---

## 🔍 QUICK LOOKUP BY PREFIX

**Know the prefix? Go directly to the right file:**

| Prefix | Count | File | Example |
|--------|-------|------|---------|
| **ARCH** | 9 | ARCH-INT | ARCH-01 |
| **INT** | 12 | ARCH-INT | INT-05 |
| **AP** | 28 | AP-BUG | AP-14 |
| **BUG** | 4 | AP-BUG | BUG-01 |
| **DEC** | 23 | DEC | DEC-04 |
| **LESS** | 21 | LESS-WISD | LESS-15 |
| **WISD** | 5 | LESS-WISD | WISD-02 |
| **DEP** | 5 | Others | DEP-03 |
| **DT** | 13 | Others | DT-07 |
| **ERR** | 3 | Others | ERR-02 |
| **FLOW** | 3 | Others | FLOW-01 |
| **PATH** | 5 | Others | PATH-01 |
| **RULE** | 4 | Others | RULE-01 |
| **FW** | 2 | Others | FW-01 |
| **TRACE** | 2 | Others | TRACE-01 |
| **META** | 1 | Others | META-01 |

---

## 📊 QUICK STATS

**Total REF-IDs:** 159+  
**Categories:** 16 types  
**Files:** 5 component files + this hub

**Most Referenced:**
1. **BUG-01** (Sentinel leak) - 10+ references
2. **DEC-01** (SUGA pattern) - 8+ references
3. **RULE-01** (Gateway imports) - 7+ references
4. **DEC-04** (No threading) - 5+ references

**By Category:**
- Anti-Patterns: 28 (largest)
- Decisions: 23
- Lessons: 21
- Decision Trees: 13
- Interfaces: 12
- Architecture: 9

---

## 💡 USAGE PATTERNS

### Pattern 1: See Cross-Reference
```
"Related: BUG-01, DEC-05"
↓
BUG prefix → REF-ID-Directory-AP-BUG.md
DEC prefix → REF-ID-Directory-DEC.md
↓
Find exact file paths
↓
Search those specific files
```

### Pattern 2: User Cites REF-ID
```
User: "What's DEC-21 about?"
↓
DEC prefix → REF-ID-Directory-DEC.md
↓
Find: DEC-21 → NM04/.../Operational_DEC-21.md
↓
Search and read that section
```

### Pattern 3: Building Response
```
Include REF-IDs in response:
"This is documented in DEC-04 (No threading locks)..."
↓
User can look up exact section if they want details
```

---

## 🔗 INTEGRATION WITH V3

### With Gateway Layer
- **ZAPH:** Top 20 REF-IDs in Tier 1-3  
  📍 NM00/NM00B-ZAPH-*.md
  
- **Quick Index:** Routes by keyword  
  📍 NM00/NM00-Quick_Index.md

- **Master Index:** Complete structure  
  📍 NM00/NM00A-Master_Index.md

### With Other Tools
- **Anti-Patterns:** Cross-refs to AP-##  
  📍 ANTI-PATTERNS-CHECKLIST.md

- **Workflows:** Uses REF-IDs in decision trees  
  📍 WORKFLOWS-PLAYBOOK.md

- **SESSION-START:** Top 20 REF-IDs pre-loaded  
  📍 SESSION-START-Quick-Context.md

---

## 🎯 QUICK ACTIONS

**I need to:**

→ Look up specific REF-ID → Use prefix table above  
→ Find all architecture refs → REF-ID-Directory-ARCH-INT.md  
→ Check anti-pattern details → REF-ID-Directory-AP-BUG.md  
→ Understand a decision → REF-ID-Directory-DEC.md  
→ See lessons learned → REF-ID-Directory-LESS-WISD.md  
→ Find dependency layers → REF-ID-Directory-Others.md

---

## 🔄 MAINTENANCE

**When to update:**
- New REF-IDs added to neural maps
- Files reorganized or renamed
- New categories of references created
- ZAPH tier changes

**Update process:**
1. Add to appropriate component file
2. Update count in this hub
3. Update Quick Stats if needed
4. Update ZAPH if frequently accessed

**Update frequency:** Monthly or when 5+ new REF-IDs added

---

## 📍 FILE LOCATIONS

**Tool Files (in /nmap root or /tools):**
- REF-ID-DIRECTORY.md (this hub)
- REF-ID-Directory-ARCH-INT.md
- REF-ID-Directory-AP-BUG.md
- REF-ID-Directory-DEC.md
- REF-ID-Directory-LESS-WISD.md
- REF-ID-Directory-Others.md

**Neural Map Files:** NM00/ through NM07/ subdirectories

---

**END OF HUB**

**Next:** Open specific component file based on prefix  
**Lines:** ~180 (properly sized hub)  
**Update:** When new REF-IDs added or structure changes

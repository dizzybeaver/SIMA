# Phase-0.5.1-Directory-Structure.md

**Version:** 1.0.0  
**Date:** 2025-10-28  
**Phase:** 0.5 - Project Structure Organization  
**Session:** 0.5.1 - Directory Restructure  
**Status:** Implementation Plan

---

## 🎯 OBJECTIVE

Transform current flat neural map structure into multi-project hierarchy that separates:
- **Base SIMA:** Generic, reusable knowledge
- **Project-Specific:** SUGA-ISP and LEE implementations

---

## 📂 NEW DIRECTORY STRUCTURE

### Root Level

```
/
├─ sima/                          # 🆕 Base SIMA (generic only)
│  ├─ config/
│  │  ├─ SIMA-MAIN-CONFIG.md
│  │  ├─ projects_config.md       # Registry of all projects
│  │  └─ templates/
│  │     ├─ project_template/
│  │     ├─ PROJECT-CONFIG-TEMPLATE.md
│  │     ├─ ARCHITECTURES-TEMPLATE.md
│  │     ├─ LANGUAGE-TEMPLATE.md
│  │     ├─ entry_templates/
│  │     │  ├─ CONSTRAINT-TEMPLATE.md
│  │     │  ├─ COMBINATION-TEMPLATE.md
│  │     │  ├─ LESSON-TEMPLATE.md
│  │     │  └─ DECISION-TEMPLATE.md
│  │     └─ index_templates/
│  │        ├─ NMP00-MASTER-INDEX-TEMPLATE.md
│  │        └─ NMP00-QUICK-INDEX-TEMPLATE.md
│  │
│  ├─ gateways/
│  │  └─ (generic gateway patterns)
│  │
│  ├─ interfaces/
│  │  └─ (generic interface patterns)
│  │
│  ├─ entries/
│  │  ├─ core/                    # Universal concepts
│  │  ├─ architectures/           # SUGA, LMMS, DD, ZAPH
│  │  │  ├─ suga/
│  │  │  ├─ lmms/
│  │  │  ├─ dd/
│  │  │  └─ zaph/
│  │  └─ languages/               # Python, JavaScript, etc.
│  │     └─ python/
│  │
│  ├─ zaph/
│  │  └─ (ZAPH index system files)
│  │
│  ├─ support/
│  │  ├─ SESSION-START-Quick-Context.md
│  │  ├─ REF-ID-Complete-Directory.md
│  │  └─ tools/
│  │
│  ├─ docs/
│  │  └─ (SIMA documentation)
│  │
│  └─ planning/
│     └─ (SIMAv4 planning docs)
│
└─ projects/                      # 🆕 All project-specific content
   │
   ├─ SUGA-ISP/                   # SUGA-ISP Lambda project
   │  ├─ src/                     # Source code
   │  │  ├─ __init__.py
   │  │  ├─ gateway.py
   │  │  ├─ lambda_function.py
   │  │  └─ ... (all Python files)
   │  │
   │  └─ sima/                    # SUGA-ISP SIMA config
   │     ├─ config/
   │     │  ├─ SUGA-ISP-PROJECT-CONFIG.md
   │     │  ├─ SUGA-ISP-ARCHITECTURES.md
   │     │  └─ SUGA-ISP-LANGUAGE-PYTHON.md
   │     │
   │     ├─ nmp/                  # Neural Map entries
   │     │  ├─ NMP00/
   │     │  │  ├─ NMP00-SUGA-ISP-Master-Index.md
   │     │  │  └─ NMP00-SUGA-ISP-Quick-Index.md
   │     │  ├─ constraints/
   │     │  │  └─ LAM-CONST-*.md
   │     │  ├─ combinations/
   │     │  │  └─ LAM-COMB-*.md
   │     │  ├─ lessons/
   │     │  │  └─ LAM-LESS-*.md
   │     │  └─ decisions/
   │     │     └─ LAM-DEC-*.md
   │     │
   │     └─ support/
   │        ├─ SERVER-CONFIG.md
   │        ├─ File-Server-URLs.md
   │        └─ tools/
   │
   └─ LEE/                        # Lambda Execution Engine project
      ├─ src/                     # LEE source code
      │  └─ ... (LEE Python files)
      │
      └─ sima/                    # LEE SIMA config
         ├─ config/
         │  ├─ LEE-PROJECT-CONFIG.md
         │  ├─ LEE-ARCHITECTURES.md
         │  └─ LEE-LANGUAGE-PYTHON.md
         │
         ├─ nmp/
         │  ├─ NMP00/
         │  │  ├─ NMP00-LEE-Master-Index.md
         │  │  └─ NMP00-LEE-Quick-Index.md
         │  ├─ constraints/
         │  ├─ combinations/
         │  ├─ lessons/
         │  └─ decisions/
         │
         └─ support/
            ├─ SERVER-CONFIG.md
            ├─ File-Server-URLs.md
            └─ tools/
```

---

## 🔄 MIGRATION MAPPING

### Current → New Structure

**Base SIMA (Generic Content):**
```
Current: /nmap/NM01/         → New: /sima/entries/architectures/suga/
Current: /nmap/NM02/         → New: /sima/entries/architectures/suga/
Current: /nmap/NM03/         → New: /sima/entries/architectures/suga/
Current: /nmap/Context/      → New: /sima/config/
Current: /nmap/Support/      → New: /sima/support/
Current: /nmap/Docs/         → New: /sima/docs/
```

**SUGA-ISP Project:**
```
Current: /src/               → New: /projects/SUGA-ISP/src/
Current: /nmap/NM04/         → New: /projects/SUGA-ISP/sima/nmp/decisions/
Current: /nmap/NM05/         → New: /projects/SUGA-ISP/sima/nmp/constraints/
Current: /nmap/NM06/         → New: /projects/SUGA-ISP/sima/nmp/lessons/
Current: /nmap/NM07/         → New: /projects/SUGA-ISP/sima/nmp/ (decision logic)
Current: /nmap/AWS/          → New: /projects/SUGA-ISP/sima/nmp/ (AWS-specific)
```

**LEE Project:**
```
New: /projects/LEE/src/      # LEE source code (when created)
New: /projects/LEE/sima/     # LEE SIMA configuration
```

---

## 📋 FILE MOVEMENT PLAN

### Phase 1: Create Directory Structure

**Create base directories:**
```bash
mkdir -p /sima/config/templates/project_template
mkdir -p /sima/config/templates/entry_templates
mkdir -p /sima/config/templates/index_templates
mkdir -p /sima/gateways
mkdir -p /sima/interfaces
mkdir -p /sima/entries/core
mkdir -p /sima/entries/architectures/suga
mkdir -p /sima/entries/architectures/lmms
mkdir -p /sima/entries/architectures/dd
mkdir -p /sima/entries/architectures/zaph
mkdir -p /sima/entries/languages/python
mkdir -p /sima/zaph
mkdir -p /sima/support/tools
mkdir -p /sima/docs
mkdir -p /sima/planning
```

**Create project directories:**
```bash
mkdir -p /projects/SUGA-ISP/src
mkdir -p /projects/SUGA-ISP/sima/config
mkdir -p /projects/SUGA-ISP/sima/nmp/NMP00
mkdir -p /projects/SUGA-ISP/sima/nmp/constraints
mkdir -p /projects/SUGA-ISP/sima/nmp/combinations
mkdir -p /projects/SUGA-ISP/sima/nmp/lessons
mkdir -p /projects/SUGA-ISP/sima/nmp/decisions
mkdir -p /projects/SUGA-ISP/sima/support/tools

mkdir -p /projects/LEE/src
mkdir -p /projects/LEE/sima/config
mkdir -p /projects/LEE/sima/nmp/NMP00
mkdir -p /projects/LEE/sima/nmp/constraints
mkdir -p /projects/LEE/sima/nmp/combinations
mkdir -p /projects/LEE/sima/nmp/lessons
mkdir -p /projects/LEE/sima/nmp/decisions
mkdir -p /projects/LEE/sima/support/tools
```

### Phase 2: Move Base SIMA Files

**Generic Architecture (SUGA patterns):**
```
mv /nmap/NM01/* /sima/entries/architectures/suga/
mv /nmap/NM02/* /sima/entries/architectures/suga/
mv /nmap/NM03/* /sima/entries/architectures/suga/
```

**Configuration:**
```
mv /nmap/Context/* /sima/config/
```

**Support:**
```
mv /nmap/Support/* /sima/support/
```

**Documentation:**
```
mv /nmap/Docs/* /sima/docs/
```

**Planning:**
```
mv /nmap/Testing/* /sima/planning/
```

### Phase 3: Move SUGA-ISP Project Files

**Source code:**
```
mv /src/* /projects/SUGA-ISP/src/
```

**Decisions:**
```
mv /nmap/NM04/* /projects/SUGA-ISP/sima/nmp/decisions/
```

**Anti-Patterns/Constraints:**
```
mv /nmap/NM05/* /projects/SUGA-ISP/sima/nmp/constraints/
```

**Lessons:**
```
mv /nmap/NM06/* /projects/SUGA-ISP/sima/nmp/lessons/
```

**Decision Logic:**
```
mv /nmap/NM07/* /projects/SUGA-ISP/sima/nmp/combinations/
```

**AWS-Specific:**
```
mv /nmap/AWS/* /projects/SUGA-ISP/sima/nmp/lessons/
```

**Master Indexes:**
```
mv /nmap/NM00/* /projects/SUGA-ISP/sima/nmp/NMP00/
```

### Phase 4: Move LEE Project Files

**Currently:** No LEE files exist yet  
**Action:** Create placeholder structure for future use

---

## ✅ VERIFICATION CHECKLIST

### Base SIMA Verification

**Check these directories contain ONLY generic content:**
- [ ] `/sima/entries/architectures/suga/` - No Lambda-specific implementation details
- [ ] `/sima/entries/architectures/` - Only architecture patterns (SUGA, LMMS, DD, ZAPH)
- [ ] `/sima/entries/languages/python/` - Only generic Python knowledge
- [ ] `/sima/entries/core/` - Only universal concepts

**Red Flags (contamination):**
- ❌ References to specific AWS services (Lambda, DynamoDB, S3)
- ❌ References to specific projects (SUGA-ISP, LEE)
- ❌ Implementation-specific code examples
- ❌ Project-specific decisions or lessons

### SUGA-ISP Project Verification

**Check these directories contain ONLY SUGA-ISP content:**
- [ ] `/projects/SUGA-ISP/sima/nmp/decisions/` - All decisions about SUGA-ISP
- [ ] `/projects/SUGA-ISP/sima/nmp/lessons/` - All lessons from SUGA-ISP
- [ ] `/projects/SUGA-ISP/sima/nmp/constraints/` - All SUGA-ISP constraints
- [ ] `/projects/SUGA-ISP/src/` - All Python source files

**Green Flags (correct):**
- ✅ References to AWS Lambda
- ✅ References to interface_* modules
- ✅ References to gateway.py
- ✅ Implementation details
- ✅ Deployment specifics

### LEE Project Verification

**Check LEE structure:**
- [ ] `/projects/LEE/sima/` - Clean structure ready for content
- [ ] `/projects/LEE/src/` - Ready for LEE source code
- [ ] No LEE content in base SIMA
- [ ] No LEE content in SUGA-ISP project

---

## 📊 DELIVERABLES

### Session 0.5.1 Outputs

1. **This Document:** Phase-0.5.1-Directory-Structure.md
2. **File Movement Script:** Phase-0.5.1-File-Movement-Script.sh
3. **Verification Report:** Phase-0.5.1-Verification-Report.md
4. **Updated Master Control:** SIMAv4-Master-Control-Implementation.md (v1.0.3)

---

## ⚠️ CRITICAL NOTES

### Do Not Move

**These files stay at root:**
- `.git/` - Git repository
- `README.md` - Root readme
- `.gitignore` - Git ignore
- Any CI/CD configuration files

### Special Handling

**URL references:** All URLs will need updating in Phase 0.5.2  
**Cross-references:** Will be updated in Phase 0.5.3  
**Indexes:** Will be regenerated in Phase 0.5.4

---

**END OF DIRECTORY STRUCTURE PLAN**

**Status:** Ready for implementation  
**Next:** Create file movement script

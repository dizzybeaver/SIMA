# File: README.md (Projects Directory)

**Version:** 1.0.0  
**Date:** 2025-10-29  
**Purpose:** Multi-project system overview and navigation guide

---

## 🎯 MULTI-PROJECT SYSTEM OVERVIEW

This directory contains project-specific neural maps (NMPs) that complement the base SIMA knowledge system. Each project has its own configuration, documentation, and NMP entries.

---

## 📁 DIRECTORY STRUCTURE

```
projects/
├── projects_config.md          # Project registry
├── README.md                   # This file
│
├── templates/                  # Project templates
│   ├── project_config_template.md
│   ├── project_readme_template.md
│   ├── nmp_entry_template.md
│   ├── interface_catalog_template.md
│   ├── gateway_pattern_template.md
│   ├── decision_log_template.md
│   ├── lesson_learned_template.md
│   ├── bug_report_template.md
│   └── architecture_doc_template.md
│
├── tools/                      # Web-based tools
│   ├── project_configurator.html
│   └── nmp_generator.html
│
└── [PROJECT_ID]/               # Individual projects
    ├── project_config.md
    └── README.md
```

---

## 🚀 QUICK START

### For Users

1. **Find Your Project:** Check `projects_config.md` for project list
2. **Access Project Info:** Go to `/sima/projects/[PROJECT_ID]/`
3. **View NMPs:** Check `/sima/nmp/NMP##-[PROJECT_ID]-*.md`
4. **Use Quick Index:** See project's Quick-Index.md file

### For Contributors

1. **Create Project:** Use templates in `/templates/`
2. **Register Project:** Add entry to `projects_config.md`
3. **Create NMPs:** Use NMP template and naming convention
4. **Update Indexes:** Maintain cross-references

---

## 📋 ACTIVE PROJECTS

### LEE (Lambda Edge Extensions)

**Status:** ✅ Active  
**Location:** `/sima/projects/LEE/`  
**NMPs:** `/sima/nmp/NMP01-LEE-*.md`  
**Count:** 7 entries

**Quick Access:**
- Config: `/sima/projects/LEE/project_config.md`
- NMP Index: `/sima/nmp/NMP01-LEE-Quick-Index.md`
- Cross-Ref: `/sima/nmp/NMP01-LEE-Cross-Reference-Matrix.md`

---

## 🔧 TEMPLATES

### Available Templates (9)

1. **project_config_template.md** - Project configuration
2. **project_readme_template.md** - Project documentation
3. **nmp_entry_template.md** - Neural map entry
4. **interface_catalog_template.md** - Interface function catalog
5. **gateway_pattern_template.md** - Gateway implementation pattern
6. **decision_log_template.md** - Architecture decision record
7. **lesson_learned_template.md** - Project lesson documentation
8. **bug_report_template.md** - Bug tracking entry
9. **architecture_doc_template.md** - Architecture documentation

### Using Templates

```bash
1. Copy template from /templates/
2. Fill in project-specific details
3. Follow naming conventions
4. Update cross-references
5. Add to project registry
```

---

## 🛠️ TOOLS

### Web-Based Tools (2)

**1. Project Configurator** (`project_configurator.html`)
- Generate project configuration
- Validate project structure
- Create initial files

**2. NMP Generator** (`nmp_generator.html`)
- Create NMP entries from template
- Auto-generate REF-IDs
- Validate cross-references

---

## 📖 NAMING CONVENTIONS

### Project IDs

**Format:** 2-4 uppercase letters  
**Examples:** LEE, PROJ, SYS, APP

### NMP Files

**Format:** `NMP##-[PROJECT_ID]-##-Description.md`  
**Examples:**
- `NMP01-LEE-02-Cache-Interface-Functions.md`
- `NMP01-LEE-15-Gateway-Execute-Operation.md`

### Project Files

**Config:** `[PROJECT_ID]/project_config.md`  
**README:** `[PROJECT_ID]/README.md`

---

## 🔍 FINDING INFORMATION

### By Project

**All NMPs:** Search for `NMP##-[PROJECT_ID]-`  
**Project Config:** `/sima/projects/[PROJECT_ID]/project_config.md`  
**Quick Index:** `/sima/nmp/NMP##-[PROJECT_ID]-Quick-Index.md`

### By Category

**Interface Catalogs:** Search for `-Interface-Functions`  
**Gateway Patterns:** Search for `-Gateway-`  
**Integration:** Search for `-Integration` or `-API-`  
**Resilience:** Search for `-Circuit-Breaker` or `-Resilience`

---

## 🎯 BASE SIMA vs PROJECT NMPs

### Separation of Concerns

**Base SIMA** (`/sima/entries/`)
- Generic, reusable patterns
- Language-agnostic where possible
- Architecture patterns
- General best practices
- No project-specific code

**Project NMPs** (`/sima/nmp/`)
- Project-specific implementations
- Actual code examples
- API integrations
- Project decisions
- Bug reports and lessons

### When to Use Each

**Use Base SIMA for:**
- Understanding SUGA architecture
- Learning interface patterns
- Gateway design principles
- Language best practices
- Generic decision logic

**Use Project NMPs for:**
- Specific function implementations
- Project-specific patterns
- Integration details
- Bug tracking
- Project-specific lessons

---

## 📊 STATISTICS

**Total Projects:** 1  
**Total NMPs:** 7  
**Total Templates:** 9  
**Total Tools:** 2

---

## 🔗 RELATED DOCUMENTATION

**Base SIMA:** `/sima/entries/`  
**Support Tools:** `/sima/support/`  
**Documentation:** `/sima/documentation/`  
**Integration:** `/sima/integration/`

---

## 📝 CHANGE LOG

**v1.0.0 (2025-10-29)**
- Initial multi-project system created
- LEE project registered
- 9 templates created
- 2 web tools created

---

**END OF FILE**

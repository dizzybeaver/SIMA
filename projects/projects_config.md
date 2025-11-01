# File: projects_config.md

**Version:** 1.0.0  
**Date:** 2025-10-29  
**Purpose:** Multi-project configuration registry for SIMAv4  
**Status:** Active

---

## 📋 PROJECTS REGISTRY

### Active Projects

**Total Active Projects:** 1

| Project ID | Project Name | Status | Created | Last Updated |
|------------|--------------|--------|---------|--------------|
| LEE | Lambda Edge Extensions | ✅ Active | 2025-10-29 | 2025-10-29 |

---

## 🎯 PROJECT: LEE (Lambda Edge Extensions)

### Basic Information

**Project ID:** LEE  
**Full Name:** Lambda Edge Extensions  
**Code Name:** SUGA-ISP  
**Status:** ✅ Active Development  
**Created:** 2025-10-29  
**Owner:** Primary Development Team  
**Repository:** [Internal]

### Project Description

AWS Lambda-based ISP management system using Home Assistant integration. Implements SUGA architecture pattern with 12 interface layers and gateway-based routing.

### Architecture Patterns

**Primary:** SUGA (Single Unified Gateway Architecture)  
**Secondary:** LMMS (Lambda Memory Management System)  
**Supporting:** DD (Dispatch Dictionary), ZAPH (hierarchical organization)

### Technology Stack

**Runtime:** Python 3.11  
**Platform:** AWS Lambda  
**Integration:** Home Assistant API  
**Storage:** AWS Parameter Store  
**Monitoring:** CloudWatch

### NMP Categories

**Prefix:** NMP01-LEE-  
**Categories:**
- Interface Functions (02, 06, 08)
- Gateway Patterns (15, 16)
- Integration Patterns (20)
- Resilience Patterns (23)

### File Locations

**NMP Files:** `/sima/nmp/NMP01-LEE-*.md`  
**Project Config:** `/sima/projects/LEE/project_config.md`  
**Project README:** `/sima/projects/LEE/README.md`  
**Source Code:** `/src/` (external repository)

### Team Contacts

**Primary:** Development Team  
**Secondary:** Architecture Team  
**Support:** Operations Team

---

## 🔧 PROJECT TEMPLATE

### Adding New Projects

1. Copy project template from `/sima/projects/templates/project_config_template.md`
2. Create project directory: `/sima/projects/[PROJECT_ID]/`
3. Create project_config.md in project directory
4. Create README.md in project directory
5. Add entry to this registry
6. Create initial NMP entries with prefix `NMP##-[PROJECT_ID]-`

### Project Naming Convention

**Project ID:** 2-4 uppercase letters  
**NMP Prefix:** NMP##-[PROJECT_ID]-  
**Example:** NMP01-LEE-02-Cache-Interface-Functions.md

---

## 📊 PROJECT STATISTICS

### Overall Statistics

**Total Projects:** 1  
**Active Projects:** 1  
**Archived Projects:** 0  
**Total NMP Entries:** 7 (LEE)

### By Category

**Interface Catalogs:** 3  
**Gateway Patterns:** 2  
**Integration Patterns:** 1  
**Resilience Patterns:** 1

---

## 🔍 FINDING PROJECT INFORMATION

### Quick Links

**Project LEE:**
- Config: `/sima/projects/LEE/project_config.md`
- README: `/sima/projects/LEE/README.md`
- NMP Index: `/sima/nmp/NMP01-LEE-Quick-Index.md`
- Cross-Reference: `/sima/nmp/NMP01-LEE-Cross-Reference-Matrix.md`

### Search Patterns

**Find all NMPs for project:** `NMP##-[PROJECT_ID]-*`  
**Find project config:** `/sima/projects/[PROJECT_ID]/project_config.md`  
**Find project NMPs:** `/sima/nmp/NMP##-[PROJECT_ID]-*.md`

---

## 📝 CHANGE LOG

**v1.0.0 (2025-10-29)**
- Initial registry created
- LEE project registered
- 7 NMP entries cataloged

---

## 🔗 RELATED DOCUMENTS

**Templates:** `/sima/projects/templates/`  
**Tools:** `/sima/projects/tools/`  
**Base SIMA:** `/sima/entries/`  
**Support:** `/sima/support/`

---

**END OF FILE**

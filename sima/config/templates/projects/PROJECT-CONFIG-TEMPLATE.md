# PROJECT-CONFIG-TEMPLATE.md

**Version:** 1.0.0  
**Date:** [YYYY-MM-DD]  
**Project:** [PROJECT_NAME]  
**Status:** [Active/Development/Maintenance/Archived]

---

## 📋 PROJECT IDENTITY

**Project Name:** [PROJECT_NAME]  
**Project Code:** [SHORT_CODE] (e.g., SUGA-ISP, LEE)  
**Primary Purpose:** [Brief description of what this project does]  
**Domain:** [e.g., Home Automation, Web Services, Data Processing]

**Owner:**
- Primary: [Name/Team]
- Technical Lead: [Name]
- SIMA Curator: [Name]

**Creation Date:** [YYYY-MM-DD]  
**Last Updated:** [YYYY-MM-DD]  
**Current Version:** [X.Y.Z]

---

## 🏗️ ARCHITECTURES

**Primary Architecture:** [Architecture name and code]  
**Pattern File:** `/sima/entries/architectures/[ARCH_CODE]/`

**Architecture Stack:**
1. **[ARCH_NAME_1]** - [Brief description]
   - **Location:** `/sima/entries/architectures/[CODE]/`
   - **Status:** Active/Planned/Deprecated
   - **Applied To:** [Specific modules/components]

2. **[ARCH_NAME_2]** - [Brief description]
   - **Location:** `/sima/entries/architectures/[CODE]/`
   - **Status:** Active/Planned/Deprecated
   - **Applied To:** [Specific modules/components]

**Custom Patterns:**
- Pattern Name: [Description]
- Pattern Name: [Description]

---

## 💻 LANGUAGES & TECHNOLOGIES

### Primary Languages

**Language 1: [LANGUAGE_NAME]**
- **Version:** [X.Y]
- **Purpose:** [Primary use case]
- **Constraints Reference:** `/sima/entries/languages/[LANG_CODE]/`
- **Percentage of Codebase:** [XX%]

**Language 2: [LANGUAGE_NAME]**
- **Version:** [X.Y]
- **Purpose:** [Primary use case]
- **Constraints Reference:** `/sima/entries/languages/[LANG_CODE]/`
- **Percentage of Codebase:** [XX%]

### Frameworks & Libraries

**Framework 1: [FRAMEWORK_NAME]**
- **Version:** [X.Y]
- **Purpose:** [Use case]
- **Constraints:** [Reference to constraint files]

**Library 1: [LIBRARY_NAME]**
- **Version:** [X.Y]
- **Purpose:** [Use case]
- **Critical Constraints:** [List any important constraints]

---

## 🚀 DEPLOYMENT & RUNTIME

**Target Platform:** [AWS Lambda, Cloud Run, Kubernetes, etc.]

**Platform Constraints:**
- Memory: [Amount]
- CPU: [Specification]
- Storage: [Amount]
- Execution Time: [Limit]
- Other: [Any other constraints]

**Deployment Method:**
- **Primary:** [GitHub Actions, Manual, CI/CD tool]
- **Configuration:** [Link to deployment config]
- **Rollback Strategy:** [Brief description]

**Dependencies Management:**
- **Tool:** [pip, npm, etc.]
- **Lock File:** [requirements.txt, package-lock.json, etc.]
- **Update Frequency:** [Weekly, Monthly, As-needed]

---

## 📦 PROJECT STRUCTURE

**Repository Root:** `[/path/to/repo/]`  
**SIMA Location:** `[/projects/PROJECT_NAME/sima/]`

**Directory Layout:**
```
/projects/[PROJECT_NAME]/
├── sima/
│   ├── nmp/
│   │   ├── NMP00/        # Master indexes
│   │   ├── NMP01/        # Constraints
│   │   ├── NMP02/        # Combinations
│   │   ├── NMP03/        # Lessons
│   │   └── NMP04/        # Decisions
│   ├── docs/             # Project documentation
│   ├── support/          # Support tools
│   └── project_config.md # This file
├── src/                  # Source code
├── tests/                # Test files
└── config/               # Configuration files
```

---

## 🗺️ NEURAL MAP STRUCTURE

### NMP00: Master Indexes
- **NMP00-Master-Index.md** - Complete REF-ID directory
- **NMP00-Quick-Index.md** - Fast lookup guide
- **NMP00-Category-Index.md** - Entries by category

### NMP01: Constraints
**Total Entries:** [Number]  
**Categories:** [List categories]  
**Status:** [Active entries count]

**Key Constraints:**
1. [CONS-01]: [Brief description]
2. [CONS-02]: [Brief description]
3. [CONS-03]: [Brief description]

### NMP02: Combinations
**Total Entries:** [Number]  
**Active Patterns:** [Number]  
**Status:** [Health indicator]

**Key Combinations:**
1. [COMB-01]: [Brief description]
2. [COMB-02]: [Brief description]
3. [COMB-03]: [Brief description]

### NMP03: Lessons
**Total Lessons:** [Number]  
**Bugs Documented:** [Number]  
**Wisdom Synthesized:** [Number]

**Critical Lessons:**
1. [LESS-01]: [Brief description]
2. [LESS-02]: [Brief description]
3. [LESS-03]: [Brief description]

### NMP04: Decisions
**Total Decisions:** [Number]  
**Categories:** [List categories]  
**Status:** [Active/Deprecated count]

**Key Decisions:**
1. [DEC-01]: [Brief description]
2. [DEC-02]: [Brief description]
3. [DEC-03]: [Brief description]

---

## 🔗 SIMA INHERITANCE

### Core SIMA Entries Used

**Architectures:**
- [ARCH-CODE]: [Architecture name]
- [ARCH-CODE]: [Architecture name]

**Languages:**
- [LANG-CODE]: [Language name]
- [LANG-CODE]: [Language name]

**Gateways:**
- [GATE-CODE]: [Gateway pattern]
- [GATE-CODE]: [Gateway pattern]

**Interfaces:**
- [INTF-CODE]: [Interface name]
- [INTF-CODE]: [Interface name]

### Project-Specific Combinations

**Combination Patterns:**
1. [ARCH-CODE] + [LANG-CODE] + [PLATFORM]
   - **Result:** [Description of unique constraints]
   - **Documented:** [COMB-REF-ID]

2. [ARCH-CODE] + [INTF-CODE] + [CONSTRAINT]
   - **Result:** [Description]
   - **Documented:** [COMB-REF-ID]

---

## 📊 PROJECT HEALTH METRICS

**Last Assessed:** [YYYY-MM-DD]

### Code Quality
- **Test Coverage:** [XX%]
- **Linting Score:** [Score/Rating]
- **Technical Debt:** [Low/Medium/High]

### SIMA Quality
- **Documentation Completeness:** [XX%]
- **Constraint Verification:** [XX% verified]
- **Cross-Reference Integrity:** [Status]
- **Entry Freshness:** [Average age of entries]

### Maintenance
- **Active Issues:** [Count]
- **Open PRs:** [Count]
- **Last Deployment:** [YYYY-MM-DD]
- **Next Review:** [YYYY-MM-DD]

---

## 🔄 PROJECT LIFECYCLE

**Current Stage:** [Development/Active/Maintenance/Archived]

### Development Stage (if applicable)
- **Start Date:** [YYYY-MM-DD]
- **Target Launch:** [YYYY-MM-DD]
- **Completion:** [XX%]
- **Blockers:** [List any blockers]

### Active Production (if applicable)
- **Launch Date:** [YYYY-MM-DD]
- **User Count:** [Number]
- **Uptime:** [XX.XX%]
- **Last Incident:** [YYYY-MM-DD or "None"]

### Maintenance Mode (if applicable)
- **Entered Maintenance:** [YYYY-MM-DD]
- **Support Level:** [Full/Limited/Emergency Only]
- **End-of-Life Date:** [YYYY-MM-DD or "TBD"]

### Archived (if applicable)
- **Archive Date:** [YYYY-MM-DD]
- **Reason:** [Brief explanation]
- **Successor:** [Project name or "None"]
- **SIMA Preservation:** [Kept/Removed]

---

## 🔧 MAINTENANCE SCHEDULE

### Regular Reviews
- **Weekly:** [Tasks]
- **Monthly:** [Tasks]
- **Quarterly:** [Tasks]
- **Annually:** [Tasks]

### SIMA Updates
- **New Constraints:** [Trigger/Frequency]
- **New Lessons:** [Trigger/Frequency]
- **Decision Reviews:** [Frequency]
- **Index Regeneration:** [Frequency]

### Health Checks
- **Performance Monitoring:** [Frequency]
- **Dependency Updates:** [Frequency]
- **Security Scans:** [Frequency]
- **Documentation Review:** [Frequency]

---

## 🚨 CRITICAL INFORMATION

### Emergency Contacts
- **Technical Issues:** [Contact info]
- **SIMA Questions:** [Contact info]
- **Production Issues:** [Contact info]

### Known Issues
1. **[ISSUE_CODE]**: [Brief description]
   - **Impact:** [Low/Medium/High/Critical]
   - **Workaround:** [Description or "None"]
   - **Fix ETA:** [Date or "Investigating"]

### Important Links
- **Repository:** [URL]
- **Documentation:** [URL]
- **Monitoring Dashboard:** [URL]
- **Issue Tracker:** [URL]

---

## 📝 CHANGE LOG

### Version X.Y.Z (YYYY-MM-DD)
- [Description of changes]
- [New constraints added]
- [Decisions made]

### Version X.Y.Z (YYYY-MM-DD)
- [Description of changes]

---

## 🔐 ISOLATION VERIFICATION

**Last Verified:** [YYYY-MM-DD]

### Base SIMA Contamination Check
- ✅ No project-specific entries in `/sima/entries/`
- ✅ No hardcoded project names in base SIMA
- ✅ No project-specific constraints in core architectures
- ✅ No project-specific constraints in language entries

### Cross-Project Isolation Check
- ✅ No references to other projects
- ✅ No shared mutable state
- ✅ No dependency on other project SIMA entries

### Verification Tools
- **Script:** [Path to verification script]
- **Last Run:** [YYYY-MM-DD]
- **Results:** [Pass/Fail + details]

---

## 📚 REFERENCES

### Internal References
- **SIMA v4 Spec:** `/sima/docs/SIMAv4-Complete-Specification.md`
- **Architecture Docs:** `/sima/entries/architectures/[CODES]/`
- **Language Constraints:** `/sima/entries/languages/[CODES]/`

### External References
- [Reference 1]: [URL]
- [Reference 2]: [URL]

### Related Projects
- [PROJECT_NAME]: [Relationship description]
- [PROJECT_NAME]: [Relationship description]

---

**END OF PROJECT CONFIG**

**Template Version:** 1.0.0  
**Usage:** Copy this template for new projects, fill in all [BRACKETED] sections  
**Maintenance:** Update this config after major changes or quarterly reviews

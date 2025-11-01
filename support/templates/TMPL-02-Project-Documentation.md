# TMPL-02: Project Documentation Template

**REF-ID:** TMPL-02  
**Category:** Template  
**Type:** Project Documentation Structure  
**Purpose:** Standard template for creating new project documentation  
**Version:** 1.0.0  
**Last Updated:** 2025-11-01

---

## ðŸŽ¯ TEMPLATE PURPOSE

**What:** Standardized structure for project-specific documentation (NMP structure)

**When to Use:**
- Starting a new project
- Documenting existing project
- Creating project neural maps (NMP)
- Establishing project standards

**Who Uses:**
- Project leads setting up new projects
- Teams documenting architecture
- Developers creating project guides

---

## 📋 PROJECT STRUCTURE

### Complete Project Layout

```
projects/{PROJECT-NAME}/
â"œâ"€â"€ README.md                          # Project overview
â"œâ"€â"€ src/                               # Source code
â"‚   â"œâ"€â"€ {code files}
â"‚   â""â"€â"€ {subdirectories}
â"‚
â""â"€â"€ sima/                              # SIMA documentation
    â"œâ"€â"€ config/
    â"‚   â"œâ"€â"€ project.md                 # Project config
    â"‚   â"œâ"€â"€ architectures.md           # Architecture config
    â"‚   â""â"€â"€ languages.md               # Language config
    â"‚
    â"œâ"€â"€ nmp/                           # Neural Maps (Project)
    â"‚   â"œâ"€â"€ NMP00/
    â"‚   â"‚   â"œâ"€â"€ NMP00-Master-Index.md
    â"‚   â"‚   â""â"€â"€ NMP00-Quick-Index.md
    â"‚   â"œâ"€â"€ constraints/               # Project constraints
    â"‚   â"œâ"€â"€ combinations/              # Pattern combinations
    â"‚   â"œâ"€â"€ lessons/                   # Project-specific lessons
    â"‚   â""â"€â"€ decisions/                 # Project decisions
    â"‚
    â""â"€â"€ support/
        â"œâ"€â"€ tools/                     # Project-specific tools
        â"œâ"€â"€ workflows/                 # Custom workflows
        â""â"€â"€ checklists/                # Project checklists
```

---

## 📄 FILE TEMPLATES

### Template 1: Project README.md

```markdown
# {PROJECT-NAME}

**Status:** 🟢 Active | ðŸš§ In Progress | 🟡 Maintenance  
**Created:** {YYYY-MM-DD}  
**Team:** {Team name}  
**Tech Stack:** {Languages/frameworks}

---

## ðŸ"Š PROJECT OVERVIEW

### Purpose
{2-3 sentences: What does this project do?}

### Scope
{What's included and what's not}

### Goals
- Goal 1: {Measurable objective}
- Goal 2: {Measurable objective}
- Goal 3: {Measurable objective}

---

## ðŸ—ï¸ ARCHITECTURE

### Architecture Style
{e.g., SUGA Gateway Pattern, Microservices, Monolith}

### Key Components
1. **{Component 1}:** {Purpose}
2. **{Component 2}:** {Purpose}
3. **{Component 3}:** {Purpose}

### Technology Stack
- **Language:** {Primary language}
- **Framework:** {If applicable}
- **Database:** {If applicable}
- **Cloud:** {Cloud provider}
- **Key Libraries:** {Main dependencies}

---

## ðŸš€ GETTING STARTED

### Prerequisites
- Requirement 1
- Requirement 2
- Requirement 3

### Installation
```bash
# Step 1
command1

# Step 2
command2

# Step 3
command3
```

### Configuration
```bash
# Required environment variables
export VAR1=value1
export VAR2=value2
```

### Running Locally
```bash
# Development mode
run-command

# Testing
test-command
```

---

## ðŸ"š DOCUMENTATION

### Project Documentation
- **Architecture:** `/sima/nmp/NMP00/NMP00-Master-Index.md`
- **Constraints:** `/sima/nmp/constraints/`
- **Decisions:** `/sima/nmp/decisions/`
- **Lessons:** `/sima/nmp/lessons/`

### External Documentation
- **API Docs:** {Link}
- **Design Docs:** {Link}
- **User Guide:** {Link}

---

## ðŸ§ª TESTING

### Test Strategy
{Brief description of testing approach}

### Running Tests
```bash
# Unit tests
unit-test-command

# Integration tests
integration-test-command

# E2E tests
e2e-test-command
```

### Test Coverage
**Target:** {e.g., 80% line coverage}  
**Current:** {Current coverage}

---

## ðŸ"§ DEPLOYMENT

### Environments
- **Development:** {URL/details}
- **Staging:** {URL/details}
- **Production:** {URL/details}

### Deployment Process
1. Step 1: {Action}
2. Step 2: {Action}
3. Step 3: {Action}

### Rollback Procedure
{How to rollback if deployment fails}

---

## ðŸ"Š METRICS & MONITORING

### Key Metrics
- **Metric 1:** {Description} - Target: {value}
- **Metric 2:** {Description} - Target: {value}
- **Metric 3:** {Description} - Target: {value}

### Monitoring Tools
- **Logs:** {Tool/location}
- **Metrics:** {Tool/location}
- **Alerts:** {Tool/location}

---

## ðŸ'¥ TEAM

### Current Team
- **Lead:** {Name}
- **Developers:** {Names}
- **QA:** {Names}

### Contact
- **Slack:** {Channel}
- **Email:** {Distribution list}
- **On-Call:** {Contact method}

---

## 📋 MAINTENANCE

### Regular Tasks
- **Daily:** {Tasks}
- **Weekly:** {Tasks}
- **Monthly:** {Tasks}

### Known Issues
{Link to issue tracker}

### Roadmap
{Link to roadmap or brief list of upcoming features}

---

## ðŸ"— RELATED PROJECTS

- **{Project 1}:** {Relationship/why related}
- **{Project 2}:** {Relationship/why related}

---

**END OF PROJECT README**
```

---

### Template 2: project.md (Config)

```markdown
# {PROJECT-NAME} Configuration

**Project ID:** {unique-id}  
**Created:** {YYYY-MM-DD}  
**Last Updated:** {YYYY-MM-DD}  
**Status:** Active

---

## PROJECT METADATA

**Name:** {PROJECT-NAME}  
**Full Name:** {Full project name}  
**Description:** {1-2 sentence description}  
**Team:** {Team name}  
**Owner:** {Owner name}  
**Created:** {YYYY-MM-DD}

---

## FILE LOCATIONS

**Source Code:** `/projects/{PROJECT-NAME}/src/`  
**Documentation:** `/projects/{PROJECT-NAME}/sima/`  
**Neural Maps:** `/projects/{PROJECT-NAME}/sima/nmp/`  
**Configuration:** `/projects/{PROJECT-NAME}/sima/config/`

---

## ARCHITECTURE SELECTION

**Primary Architecture:** {ARCH-ID}  
**Architecture Name:** {Architecture name}  
**Selected:** {YYYY-MM-DD}  
**Rationale:** {Why this architecture}

**Secondary Patterns:**
- {ARCH-ID}: {When used}
- {ARCH-ID}: {When used}

---

## LANGUAGE SELECTION

**Primary Language:** {LANG-ID}  
**Language Name:** {Language name}  
**Version:** {Version}  
**Selected:** {YYYY-MM-DD}

**Additional Languages:**
- {LANG-ID}: {Purpose}
- {LANG-ID}: {Purpose}

---

## CONSTRAINTS

### Project-Specific Constraints

**Technical Constraints:**
- Constraint 1: {Description}
- Constraint 2: {Description}

**Business Constraints:**
- Constraint 1: {Description}
- Constraint 2: {Description}

**Resource Constraints:**
- Constraint 1: {Description}
- Constraint 2: {Description}

---

## STANDARDS

### Code Standards
- **Style Guide:** {Reference}
- **Linting:** {Tool and config}
- **Formatting:** {Tool and config}

### Documentation Standards
- **Format:** {e.g., Markdown, AsciiDoc}
- **Required Sections:** {List}
- **REF-ID Prefix:** {NMP prefix}

### Testing Standards
- **Coverage Target:** {Percentage}
- **Test Types Required:** {List}
- **CI/CD:** {Tool}

---

## NEURAL MAP SCOPE

**NMP Prefix:** NMP{##}  
**Focus Areas:**
- Focus 1: {Description}
- Focus 2: {Description}

**Out of Scope:**
- Item 1: {Why out of scope}
- Item 2: {Why out of scope}

---

## INTEGRATION POINTS

### Uses (Dependencies)
- **{System/Project}:** {Purpose}
- **{System/Project}:** {Purpose}

### Used By (Consumers)
- **{System/Project}:** {Purpose}
- **{System/Project}:** {Purpose}

### Shared Resources
- **{Resource}:** {Access pattern}
- **{Resource}:** {Access pattern}

---

## DEPLOYMENT CONFIG

**Deployment Method:** {Method}  
**Deployment Frequency:** {Frequency}  
**Environments:** {Count}

**Environment Details:**
- **Dev:** {URL/details}
- **Staging:** {URL/details}
- **Prod:** {URL/details}

---

## MAINTENANCE

**Primary Contact:** {Name}  
**Backup Contact:** {Name}  
**Team Channel:** {Slack/Teams channel}  
**On-Call:** {Rotation/contact}

**Update Schedule:**
- **Dependencies:** {Frequency}
- **Documentation:** {Frequency}
- **Security:** {Frequency}

---

**END OF PROJECT CONFIG**
```

---

### Template 3: NMP00-Master-Index.md

```markdown
# NMP00: {PROJECT-NAME} Master Index

**Project:** {PROJECT-NAME}  
**Created:** {YYYY-MM-DD}  
**Last Updated:** {YYYY-MM-DD}  
**Total Entries:** {count}

---

## ðŸ—ºï¸ NEURAL MAP STRUCTURE

### Directory Organization

```
NMP00/   - Master indexes
NMP01/   - Constraints
NMP02/   - Combinations  
NMP03/   - Lessons
NMP04/   - Decisions
NMP05/   - [Project-specific category]
```

---

## 📊 ENTRY COUNTS

| Category | Count | Last Updated |
|----------|-------|--------------|
| Constraints | {##} | {YYYY-MM-DD} |
| Combinations | {##} | {YYYY-MM-DD} |
| Lessons | {##} | {YYYY-MM-DD} |
| Decisions | {##} | {YYYY-MM-DD} |
| **Total** | **{##}** | {YYYY-MM-DD} |

---

## ðŸ" CONSTRAINTS (NMP01)

### Active Constraints

**Technical:**
- {CONST-01}: {Brief description}
- {CONST-02}: {Brief description}

**Business:**
- {CONST-10}: {Brief description}
- {CONST-11}: {Brief description}

**Resource:**
- {CONST-20}: {Brief description}

---

## 🔗 COMBINATIONS (NMP02)

### Pattern Combinations

**Core Combinations:**
- {COMB-01}: {Patterns combined}
- {COMB-02}: {Patterns combined}

**Advanced Combinations:**
- {COMB-10}: {Patterns combined}

---

## ðŸ"š LESSONS (NMP03)

### Project Lessons

**Architecture:**
- {PLESS-01}: {Brief lesson}
- {PLESS-02}: {Brief lesson}

**Implementation:**
- {PLESS-10}: {Brief lesson}

**Operations:**
- {PLESS-20}: {Brief lesson}

---

## ðŸŽ¯ DECISIONS (NMP04)

### Project Decisions

**Architecture:**
- {PDEC-01}: {Decision made}
- {PDEC-02}: {Decision made}

**Technical:**
- {PDEC-10}: {Decision made}

**Operational:**
- {PDEC-20}: {Decision made}

---

## ðŸ"— EXTERNAL REFERENCES

**SIMA Core:**
- Architecture: {ARCH-IDs used}
- Interfaces: {INT-IDs used}
- Languages: {LANG-IDs used}
- Patterns: {PAT-IDs used}

**Related Projects:**
- {Project 1}: {Relationship}
- {Project 2}: {Relationship}

---

## 🔍 QUICK ACCESS

**Most Referenced:**
1. {REF-ID}: {Name}
2. {REF-ID}: {Name}
3. {REF-ID}: {Name}

**Recently Added:**
1. {REF-ID}: {Name} - {Date}
2. {REF-ID}: {Name} - {Date}
3. {REF-ID}: {Name} - {Date}

---

**END OF MASTER INDEX**
```

---

## âœ… QUALITY CHECKLIST

### Project Setup

**Structure:**
- [ ] All required directories created
- [ ] README.md present and complete
- [ ] project.md configured
- [ ] NMP00 indexes created

**Configuration:**
- [ ] Architecture selected and documented
- [ ] Language(s) selected and documented
- [ ] Constraints identified
- [ ] Standards defined

**Documentation:**
- [ ] Getting started guide complete
- [ ] Architecture documented
- [ ] Deployment process documented
- [ ] Team contacts current

**Integration:**
- [ ] File Server URLs updated
- [ ] Cross-references to SIMA core established
- [ ] Project registered in main projects.md

---

## 📚 EXAMPLES

### Example: Small Lambda Project

```markdown
# SmartHome-Lambda

**Status:** ðŸš§ In Progress  
**Created:** 2025-11-01  
**Team:** IoT Team  
**Tech Stack:** Python 3.11, AWS Lambda, DynamoDB

## ðŸ"Š PROJECT OVERVIEW

### Purpose
Process smart home device events from IoT Core and update device states in DynamoDB.

### Scope
- Event processing from IoT Core
- State management in DynamoDB
- Response to Alexa queries
OUT OF SCOPE: Device firmware, mobile app

### Goals
- Goal 1: < 500ms response time (P99)
- Goal 2: 99.9% availability
- Goal 3: Support 10K devices per account

## ðŸ—ï¸ ARCHITECTURE

### Architecture Style
SUGA Gateway Pattern (ARCH-01)

### Key Components
1. **Gateway:** Event routing and validation
2. **Interface:** Cache, logging, metrics
3. **Core:** Business logic, state management

### Technology Stack
- **Language:** Python 3.11
- **Framework:** AWS Lambda
- **Database:** DynamoDB
- **Cloud:** AWS
- **Key Libraries:** boto3, requests
```

---

## 🎓 USAGE TIPS

**Tip 1: Start Simple**
Don't create every file at once. Start with README.md, project.md, and NMP00 indexes.

**Tip 2: Evolve Structure**
Add constraints, combinations, lessons as you discover them. Don't force it.

**Tip 3: Link Early**
Establish cross-references to SIMA core patterns immediately. Prevents rework.

**Tip 4: Keep Updated**
Update indexes weekly. Update project.md monthly. Keep README.md current.

**Tip 5: Use Templates**
Copy from this template. Customize for your needs. Maintain consistency.

---

## 🔧 MAINTENANCE

**Update Frequency:**
- **README:** When significant changes occur
- **project.md:** Monthly or when config changes
- **NMP00 indexes:** Weekly or after adding entries
- **File Server URLs:** When files added/moved

**Who Updates:**
- Project lead: Overall structure
- Team members: Entries and documentation
- Maintainers: Indexes and cross-references

---

## ðŸ"— RELATED TEMPLATES

- **TMPL-01:** Neural Map Entry Template
- **TOOL-01:** REF-ID Directory
- **Workflow-05:** Create NMP Entry
- **SIMAv4 Project Structure Guide**

---

**END OF TEMPLATE**

**Template Version:** 1.0.0  
**Last Updated:** 2025-11-01  
**Maintained By:** SIMA Team  
**Usage Count:** Template (not tracked)
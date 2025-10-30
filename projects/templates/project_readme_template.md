# File: project_readme_template.md

**Template Version:** 1.0.0  
**Purpose:** Project README template  
**Location:** `/sima/projects/templates/`

---

# PROJECT NAME

**Project Code:** PROJECT_CODE  
**Status:** Active | Maintenance | Archived  
**Version:** X.Y.Z  
**Last Updated:** YYYY-MM-DD

---

## ðŸ" OVERVIEW

[2-3 sentence project description. What it does, why it exists, key value proposition.]

**Architecture:** [SUGA, Microservices, etc.]  
**Primary Language:** [Python, JavaScript, etc.]  
**Deployment:** [AWS Lambda, Docker, etc.]

---

## 🎯 QUICK START

### Prerequisites
- Requirement 1 (version)
- Requirement 2 (version)
- Requirement 3 (version)

### Installation
```bash
# Clone repository
git clone [repository-url]

# Install dependencies
[install-command]

# Configure
[config-command]
```

### Running
```bash
# Development
[dev-command]

# Production
[prod-command]
```

---

## ðŸ"š NEURAL MAPS (NMPs)

This project uses SIMA v4 Neural Maps for documentation.

### NMP Location
```
/sima/nmp/[PROJECT_CODE]/
```

### Key NMP Entries

**Interface Catalogs:**
- `NMP##-PROJECT-02` - Cache functions
- `NMP##-PROJECT-06` - Logging functions
- `NMP##-PROJECT-08` - Security functions

**Gateway Patterns:**
- `NMP##-PROJECT-15` - Execute operation pattern
- `NMP##-PROJECT-16` - Fast path optimization

**Integrations:**
- `NMP##-PROJECT-20` - [External system] integration

**Quick Access:**
- `NMP##-PROJECT-Quick-Index.md` - Fast lookup
- `NMP##-PROJECT-Cross-Reference-Matrix.md` - Relationships

---

## ðŸ—ï¸ ARCHITECTURE

### Pattern: [Primary Architecture Pattern]

[Brief description of architecture approach]

**Layers:**
1. **[Layer 1]:** [Description]
2. **[Layer 2]:** [Description]
3. **[Layer 3]:** [Description]

### Directory Structure
```
project-root/
â"œâ"€â"€ src/                    # Source code
â"‚   â"œâ"€â"€ [component]/
â"‚   â""â"€â"€ [component]/
â"œâ"€â"€ tests/                 # Test files
â"œâ"€â"€ docs/                  # Documentation
â"œâ"€â"€ config/                # Configuration
â""â"€â"€ scripts/               # Utility scripts
```

---

## ðŸ"§ CONFIGURATION

### Environment Variables
```bash
# Required
VAR_NAME_1=value          # Description
VAR_NAME_2=value          # Description

# Optional
VAR_NAME_3=value          # Description (default: value)
```

### Configuration Files
- `config/[file].yaml` - [Purpose]
- `config/[file].json` - [Purpose]

---

## ðŸ§ª TESTING

### Running Tests
```bash
# All tests
[test-command]

# Unit tests
[unit-test-command]

# Integration tests
[integration-test-command]
```

### Coverage
- **Current:** [X]%
- **Target:** [Y]%

---

## ðŸš€ DEPLOYMENT

### Environments

**Development:**
- URL: [dev-url]
- Deploy: `[deploy-command-dev]`

**Staging:**
- URL: [staging-url]
- Deploy: `[deploy-command-staging]`

**Production:**
- URL: [prod-url]
- Deploy: `[deploy-command-prod]`

### Deployment Process
1. [Step 1]
2. [Step 2]
3. [Step 3]

---

## ðŸ"Š MONITORING

### Metrics
- [Metric 1]: [Dashboard link]
- [Metric 2]: [Dashboard link]

### Logs
- [Log system]: [Access link]

### Alerts
- [Alert system]: [Management link]

---

## 🤝 CONTRIBUTING

### Development Workflow
1. Create feature branch
2. Make changes
3. Write/update tests
4. Update NMP entries if needed
5. Submit pull request

### Code Style
- Follow [style guide]
- Run linter: `[lint-command]`
- Format code: `[format-command]`

### NMP Updates
When making changes that affect architecture or patterns:
1. Update relevant NMP entries
2. Update cross-reference matrix
3. Note changes in pull request

---

## ðŸ" RESOURCES

### Documentation
- **NMP Entries:** `/sima/nmp/[PROJECT]/`
- **API Docs:** [link]
- **User Guide:** [link]

### External Resources
- **Repository:** [github-url]
- **Issue Tracker:** [issues-url]
- **CI/CD:** [pipeline-url]

---

## ðŸ"ž SUPPORT

### Team Contacts
- **Owner:** [name/email]
- **Lead Developer:** [name/email]
- **DevOps:** [name/email]

### Getting Help
- **Slack:** [channel]
- **Email:** [support-email]
- **On-call:** [pager-link]

---

## ðŸ" LICENSE

[License information]

---

## ðŸ" CHANGELOG

### [Version X.Y.Z] - YYYY-MM-DD
**Added:**
- Feature 1
- Feature 2

**Changed:**
- Change 1
- Change 2

**Fixed:**
- Fix 1
- Fix 2

### [Version X.Y.Z-1] - YYYY-MM-DD
[Previous version notes]

---

**END OF README**

**Template Instructions:**
1. Replace all [placeholders] with actual values
2. Remove sections not applicable to project
3. Add project-specific sections as needed
4. Keep updated as project evolves

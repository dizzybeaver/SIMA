# SPEC-KNOWLEDGE-CONFIG.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** Project knowledge configuration system specification  
**Category:** Specifications

---

## PURPOSE

Define configuration system that allows projects to declare which knowledge domains are relevant, enabling selective loading and preventing knowledge pollution.

---

## PROBLEM STATEMENT

**Without configuration:**
- All projects load all knowledge
- Lambda constraints applied to standard servers
- SUGA patterns enforced on non-SUGA projects
- Inappropriate recommendations
- Slower context loading

**With configuration:**
- Projects load only relevant knowledge
- Platform-appropriate advice
- Architecture-appropriate patterns
- Faster discovery
- Better recommendations

---

## CONFIGURATION FILE

### Location
```
/sima/projects/[project-name]/config/knowledge-config.yaml
```

### Format
YAML for human readability and editability

---

## CONFIGURATION SCHEMA

```yaml
project:
  name: string (required)
  description: string (required)
  version: string (required, format: vYYYY.MM.DD.R)

knowledge:
  generic:
    enabled: boolean (required, must be true)
  
  platforms:
    [platform-name]:
      enabled: boolean
      services:
        [service-name]: boolean
  
  languages:
    [language-name]:
      enabled: boolean
      architectures:
        [architecture-name]: boolean
  
  features:
    [feature-name]:
      enabled: boolean
      note: string (optional)

context:
  constraints: list of strings
  capabilities: list of strings

file_access:
  include_paths: list of glob patterns
  exclude_paths: list of glob patterns
  exclude_ref_ids: list of REF-IDs
```

---

## REQUIRED FIELDS

### project Section
```yaml
project:
  name: "project-name"
  description: "Brief project description"
  version: "v2025.11.06.1"
```

**Rules:**
- name: Lowercase, hyphen-separated
- description: 100 chars max
- version: vYYYY.MM.DD.R format

### knowledge.generic
```yaml
knowledge:
  generic:
    enabled: true  # MUST be true, cannot disable
```

**Rule:** Generic knowledge always enabled (universal patterns)

---

## PLATFORM CONFIGURATION

```yaml
platforms:
  aws:
    enabled: true
    services:
      lambda: true
      api-gateway: true
      dynamodb: false
      s3: false
  azure:
    enabled: false
  gcp:
    enabled: false
```

**Rules:**
- Enable only platforms used
- Enable only services used
- Disabled platforms ignored completely

---

## LANGUAGE CONFIGURATION

```yaml
languages:
  python:
    enabled: true
    architectures:
      suga: true      # Gateway pattern
      lmms: true      # Lazy module management
      zaph: true      # Zone access priority
      dd: true        # Dependency disciplines
  javascript:
    enabled: false
```

**Rules:**
- Enable only languages used
- Architectures under languages
- Disabled architectures = standard patterns

---

## FEATURES CONFIGURATION

```yaml
features:
  multi-threading:
    enabled: false
    note: "Lambda is single-threaded"
  async-patterns:
    enabled: true
  caching:
    enabled: true
    implementation: "In-memory (Lambda)"
  database:
    enabled: true
    type: "DynamoDB"
```

**Purpose:** Declare what project can/cannot use

---

## CONTEXT SECTION

```yaml
context:
  constraints:
    - "AWS Lambda single-threaded execution"
    - "128MB memory limit"
    - "15-minute max execution time"
  
  capabilities:
    - "AWS service integration"
    - "Event-driven architecture"
    - "Automatic scaling"
```

**Purpose:** Document project limitations and strengths

---

## FILE ACCESS CONTROL

```yaml
file_access:
  include_paths:
    - "/sima/entries/**"
    - "/sima/platforms/aws/**"
    - "/sima/languages/python/**"
    - "/sima/projects/lee/**"
  
  exclude_paths:
    - "/sima/platforms/azure/**"
    - "/sima/platforms/gcp/**"
  
  exclude_ref_ids:
    - "AP-08"  # Threading anti-pattern (not applicable)
```

**Rules:**
- include_paths: Glob patterns for allowed files
- exclude_paths: Override includes (explicit exclusions)
- exclude_ref_ids: Specific entries to ignore

---

## VALIDATION

### Required Checks
- [ ] Project name present
- [ ] Generic knowledge enabled (must be true)
- [ ] At least one language enabled
- [ ] No contradiction (included AND excluded)
- [ ] Valid version format
- [ ] Valid YAML syntax

### Python Validator
```python
import yaml

def validate_config(config_path):
    with open(config_path) as f:
        config = yaml.safe_load(f)
    
    errors = []
    
    # Check required fields
    if 'project' not in config:
        errors.append("Missing project section")
    
    # Check generic enabled
    if not config.get('knowledge', {}).get('generic', {}).get('enabled'):
        errors.append("Generic knowledge must be enabled")
    
    # Check languages
    languages = config.get('knowledge', {}).get('languages', {})
    if not any(lang.get('enabled') for lang in languages.values()):
        errors.append("At least one language must be enabled")
    
    return errors
```

---

## USAGE WORKFLOW

### Step 1: Project Creation
Create `/sima/projects/[project-name]/config/knowledge-config.yaml`

### Step 2: Session Start
User uploads knowledge-config.yaml with File Server URLs.md

### Step 3: fileserver.php Enhancement
fileserver.php reads config and filters file list

### Step 4: Context Awareness
Mode context files adapt behavior based on config

### Step 5: Filtered Recommendations
Claude provides only relevant advice

---

## EXAMPLE CONFIGURATIONS

### LEE (Lambda + SUGA)
```yaml
project:
  name: "lee"
  description: "Home automation Lambda using SUGA"
  version: "v2025.11.06.1"

knowledge:
  generic:
    enabled: true
  platforms:
    aws:
      enabled: true
      services:
        lambda: true
        api-gateway: true
  languages:
    python:
      enabled: true
      architectures:
        suga: true
        lmms: true
        zaph: true
        dd: true

features:
  multi-threading:
    enabled: false
    note: "Lambda single-threaded"
```

### Standard Python Server
```yaml
project:
  name: "flask-api"
  description: "Standard Flask REST API"
  version: "v2025.11.06.1"

knowledge:
  generic:
    enabled: true
  platforms:
    generic-server:
      enabled: true
  languages:
    python:
      enabled: true
      architectures: {}  # No special architectures

features:
  multi-threading:
    enabled: true
    note: "Standard Python server"
```

---

## BENEFITS BY PROJECT TYPE

### Serverless (Lambda)
**Enabled:**
- Lambda constraints
- Cold start optimization
- Event-driven patterns
- Serverless best practices

**Disabled:**
- Threading recommendations
- Long-running patterns
- Server-specific advice

### SUGA Architecture
**Enabled:**
- Gateway pattern enforcement
- Lazy import requirements
- 3-layer structure
- Circular import prevention

**Disabled:**
- Direct import patterns
- Standard Python structure

### Standard Python
**Enabled:**
- Standard Python patterns
- Threading allowed
- Direct imports allowed

**Disabled:**
- SUGA gateway requirements
- Lambda constraints
- Serverless patterns

---

## RED FLAGS ADJUSTMENT

### LEE (Lambda + SUGA)
```
RED FLAGS (Active):
❌ Threading locks
❌ Direct core imports
❌ > 128MB dependencies
```

### Standard Python Project
```
RED FLAGS (Adjusted):
✅ Threading allowed
✅ Direct imports allowed
❌ Bare except (universal)
```

---

## MIGRATION PATH

### Adding Configuration to Existing Project

**Step 1:** Create config directory
```bash
mkdir -p /sima/projects/[project]/config
```

**Step 2:** Create knowledge-config.yaml
Use template, customize for project

**Step 3:** Update fileserver.php
Add configuration filtering logic

**Step 4:** Test Configuration
Verify correct knowledge loads

**Step 5:** Update Documentation
Document project-specific knowledge scope

---

## QUALITY CHECKLIST

- [ ] All required fields present
- [ ] Generic knowledge enabled
- [ ] Platforms configured correctly
- [ ] Languages configured correctly
- [ ] Features declared
- [ ] Constraints documented
- [ ] File access paths defined
- [ ] Valid YAML syntax
- [ ] No contradictions
- [ ] Version format correct

---

**Related:**
- Knowledge-Migration-Plan.md
- SPEC-FILE-STANDARDS.md

**Version History:**
- v1.0.0 (2025-11-06): Initial knowledge config spec

---

**END OF FILE**

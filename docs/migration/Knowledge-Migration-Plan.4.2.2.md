# Knowledge-Migration-Plan.md

**Version:** v4.2.3  
**Date:** 2025-11-07  
**Purpose:** Separate platform/project/language-specific knowledge from generic universal knowledge  
**Status:** Planning Phase - COMPLETE & CORRECTED

**Changelog:**
- v4.2.3: ADDED - CR-1 (Cache Registry) architecture as 6th Python architecture
- v4.2.2: CORRECTED - Split DD into DD-1 (Dictionary Dispatch) and DD-2 (Dependency Disciplines)
- v4.2.1: CORRECTED - SUGA/LMMS/ZAPH/DD are Python architectures, not projects
- v4.2.0: Added project knowledge configuration system
- v4.1.2: Added LEE versioning, MD guidelines, continuation protocol, changelog limits, function docs
- v4.1.1: Corrected interfaces to LEE, architectures to Python, added file specifications
- v4.1.0: Enhanced with complete sections, tools, validation

---

## 🎯 MIGRATION GOALS

### Primary Objectives

1. **Separate Concerns**: Generic vs Platform vs Project vs Language vs Architecture
2. **Improve Discoverability**: Knowledge organized by domain
3. **Reduce Confusion**: Clear boundaries between knowledge types
4. **Enable Reuse**: Generic patterns stay universal
5. **Scale Knowledge**: Easy to add new platforms/projects/languages/architectures
6. **Maintain Backward Compatibility**: Old REF-IDs still work
7. **Selective Knowledge Loading**: Projects configure which knowledge domains to use

### Success Criteria

- [OK] Zero platform names in generic knowledge (unless comparing)
- [OK] Zero project names in generic knowledge (unless comparing)
- [OK] Zero language-specific code in generic knowledge
- [OK] Architectures organized under languages
- [OK] Each domain has complete directory structure
- [OK] Each domain has full indexes
- [OK] Cross-references updated and accurate
- [OK] Mode context files updated
- [OK] All file names follow new conventions
- [OK] Backward compatibility maintained (redirects)
- [OK] Search/discovery works across domains
- [OK] All tools updated (fileserver.php, workflows)
- [OK] File specification architecture defined
- [OK] LEE versioning system applied
- [OK] MD guidelines enforced
- [OK] Function reference system created
- [OK] Project knowledge configuration system implemented
- [OK] DD-1 and DD-2 architectures properly separated

---

## 📂 COMPLETE DIRECTORY STRUCTURE

### Full Directory Tree

```
/sima/
├── entries/                          # GENERIC UNIVERSAL KNOWLEDGE (ALWAYS ENABLED)
│   ├── core/
│   ├── gateways/
│   ├── decisions/
│   ├── anti-patterns/
│   ├── lessons/
│   ├── specifications/
│   │   ├── SPEC-FILE-STANDARDS.md
│   │   ├── SPEC-LINE-LIMITS.md
│   │   ├── SPEC-HEADERS.md
│   │   ├── SPEC-NAMING.md
│   │   ├── SPEC-ENCODING.md
│   │   ├── SPEC-STRUCTURE.md
│   │   ├── SPEC-MARKDOWN.md
│   │   ├── SPEC-CHANGELOG.md
│   │   ├── SPEC-FUNCTION-DOCS.md
│   │   ├── SPEC-CONTINUATION.md
│   │   └── SPEC-KNOWLEDGE-CONFIG.md
│   └── indexes/
│
├── platforms/                        # PLATFORM-SPECIFIC KNOWLEDGE
│   ├── aws/
│   │   ├── lambda/
│   │   ├── api-gateway/
│   │   ├── dynamodb/
│   │   ├── s3/
│   │   ├── ssm/
│   │   ├── cloudwatch/
│   │   └── indexes/
│   ├── azure/
│   │   ├── functions/
│   │   ├── app-service/
│   │   └── indexes/
│   ├── gcp/
│   │   ├── cloud-functions/
│   │   ├── app-engine/
│   │   └── indexes/
│   └── generic-server/
│       ├── lessons/
│       ├── decisions/
│       └── indexes/
│
├── languages/                        # LANGUAGE-SPECIFIC KNOWLEDGE
│   ├── python/
│   │   ├── architectures/           # ARCHITECTURE PATTERNS
│   │   │   ├── suga/                # SUGA (Gateway) Architecture
│   │   │   │   ├── core/
│   │   │   │   ├── gateways/
│   │   │   │   ├── interfaces/
│   │   │   │   ├── decisions/
│   │   │   │   ├── anti-patterns/
│   │   │   │   ├── lessons/
│   │   │   │   └── indexes/
│   │   │   ├── lmms/                # Lazy Module Management System
│   │   │   │   ├── core/
│   │   │   │   ├── decisions/
│   │   │   │   ├── lessons/
│   │   │   │   └── indexes/
│   │   │   ├── zaph/                # Zone Access Priority Hierarchy
│   │   │   │   ├── core/
│   │   │   │   ├── decisions/
│   │   │   │   ├── lessons/
│   │   │   │   └── indexes/
│   │   │   ├── dd-1/                # Dictionary Dispatch (Performance)
│   │   │   │   ├── core/
│   │   │   │   ├── decisions/
│   │   │   │   ├── lessons/
│   │   │   │   └── indexes/
│   │   │   └── dd-2/                # Dependency Disciplines (Architecture)
│   │   │       ├── core/
│   │   │       ├── decisions/
│   │   │       ├── lessons/
│   │   │       └── indexes/
│   │   ├── lessons/
│   │   ├── decisions/
│   │   ├── anti-patterns/
│   │   ├── patterns/
│   │   └── indexes/
│   ├── javascript/
│   ├── java/
│   └── indexes/
│
├── projects/                         # ACTUAL PROJECT IMPLEMENTATIONS
│   ├── lee/                          # LEE: Home Automation Lambda
│   │   ├── config/
│   │   │   └── knowledge-config.yaml
│   │   ├── interfaces/              # LEE-specific interfaces
│   │   ├── function-references/
│   │   ├── lessons/
│   │   ├── decisions/
│   │   └── indexes/
│   └── indexes/
│
├── context/
├── support/
└── redirects/
```

---

## 🗝️ ARCHITECTURE KNOWLEDGE ORGANIZATION

### Python Architectures

**Location:** `/sima/languages/python/architectures/`

#### Structure for Each Architecture

Each Python architecture gets its own subdirectory with complete knowledge structure:

```
/sima/languages/python/architectures/
├── suga/                             # SUGA Gateway Architecture
│   ├── core/
│   │   ├── ARCH-01-Gateway-Trinity.md
│   │   ├── ARCH-02-Layer-Separation.md
│   │   └── ARCH-03-Interface-Pattern.md
│   ├── gateways/
│   │   ├── GATE-01-Gateway-Entry.md
│   │   ├── GATE-02-Lazy-Imports.md
│   │   └── GATE-03-Circular-Prevention.md
│   ├── interfaces/
│   │   ├── INT-01-CACHE-Interface.md
│   │   ├── INT-02-LOGGING-Interface.md
│   │   └── [... all 12 interfaces]
│   ├── decisions/
│   │   ├── DEC-01-SUGA-Choice.md
│   │   ├── DEC-02-Three-Layer-Pattern.md
│   │   └── DEC-03-Gateway-Mandatory.md
│   ├── anti-patterns/
│   │   ├── AP-01-Direct-Core-Import.md
│   │   ├── AP-05-Subdirectories.md
│   │   └── AP-XX-Skip-Gateway.md
│   ├── lessons/
│   │   ├── LESS-01-Read-Complete.md
│   │   ├── LESS-15-Verification.md
│   │   └── LESS-XX-Import-Patterns.md
│   └── indexes/
│       ├── suga-index-main.md
│       ├── suga-index-decisions.md
│       └── suga-index-anti-patterns.md
│
├── lmms/                             # Lazy Module Management System
│   ├── core/
│   │   ├── LMMS-01-Core-Concept.md
│   │   ├── LMMS-02-Cold-Start.md
│   │   └── LMMS-03-Import-Strategy.md
│   ├── decisions/
│   │   ├── LMMS-DEC-01-Function-Level.md
│   │   └── LMMS-DEC-02-Hot-Path.md
│   ├── lessons/
│   │   ├── LMMS-LESS-01-Profile-First.md
│   │   └── LMMS-LESS-02-Measure.md
│   └── indexes/
│
├── zaph/                             # Zone Access Priority Hierarchy
│   ├── core/
│   │   ├── ZAPH-01-Tier-System.md
│   │   ├── ZAPH-02-Hot-Paths.md
│   │   └── ZAPH-03-Priority-Rules.md
│   ├── decisions/
│   │   ├── ZAPH-DEC-01-Tier-Assignment.md
│   │   └── ZAPH-DEC-02-Access-Patterns.md
│   ├── lessons/
│   │   └── ZAPH-LESS-01-Discovery.md
│   └── indexes/
│
├── dd-1/                             # Dictionary Dispatch (Performance)
│   ├── core/
│   │   ├── DD1-01-Core-Concept.md
│   │   ├── DD1-02-Function-Routing.md
│   │   └── DD1-03-Performance-Trade-offs.md
│   ├── decisions/
│   │   ├── DD1-DEC-01-Dict-Over-If-Else.md
│   │   └── DD1-DEC-02-Memory-Speed-Trade-off.md
│   ├── lessons/
│   │   ├── DD1-LESS-01-Dispatch-Performance.md
│   │   └── DD1-LESS-02-LEE-Interface-Pattern.md
│   └── indexes/
│
└── dd-2/                             # Dependency Disciplines (Architecture)
    ├── core/
    │   ├── DD2-01-Core-Concept.md
    │   ├── DD2-02-Layer-Rules.md
    │   └── DD2-03-Flow-Direction.md
    ├── decisions/
    │   ├── DD2-DEC-01-Higher-Lower.md
    │   └── DD2-DEC-02-No-Circular.md
    ├── lessons/
    │   └── DD2-LESS-01-Dependencies.md
    └── indexes/
```

### Architecture Descriptions

#### DD-1: Dictionary Dispatch (Performance Pattern)

**Purpose:** Function routing optimization using dictionaries  
**Origin:** LEE project interface implementation  
**Location:** `/sima/languages/python/architectures/dd-1/`

**Core Concept:**
```python
# Instead of if-else chains:
if action == "turn_on":
    return turn_on_impl()
elif action == "turn_off":
    return turn_off_impl()
# ... 20+ more elif blocks

# Use dictionary dispatch:
DISPATCH = {
    "turn_on": turn_on_impl,
    "turn_off": turn_off_impl,
    # ... all actions
}
return DISPATCH[action]()
```

**Benefits:**
- O(1) lookup vs O(n) if-else chain
- Cleaner code
- Easy to extend
- Better performance with many actions

**Trade-offs:**
- Slightly more memory (dictionary overhead)
- All functions loaded at module import
- Works best with 10+ routing targets

**Used In:** LEE project interface files (interface_*.py)

**Key Files:**
- DD1-01: Core concept and dictionary pattern
- DD1-02: Function routing strategies
- DD1-03: Performance vs memory trade-offs
- DD1-DEC-01: When to use dict over if-else
- DD1-DEC-02: Memory-speed trade-off analysis
- DD1-LESS-01: Performance measurements
- DD1-LESS-02: LEE interface implementation

---

#### DD-2: Dependency Disciplines (Architecture Pattern)

**Purpose:** Managing dependencies between layers/modules  
**Origin:** SIMA migration architecture patterns  
**Location:** `/sima/languages/python/architectures/dd-2/`

**Core Concept:**
- Higher layers can depend on lower layers only
- No circular dependencies allowed
- Clear dependency flow direction
- Minimize cross-module dependencies

**Benefits:**
- Prevents circular import errors
- Clearer architecture
- Easier testing
- Better maintainability

**Rules:**
1. Dependencies flow one direction (higher → lower)
2. No bidirectional dependencies
3. No circular import chains
4. Minimize coupling between layers

**Used In:** All SUGA-based projects for layer management

**Key Files:**
- DD2-01: Core dependency discipline concept
- DD2-02: Layer dependency rules
- DD2-03: Dependency flow direction
- DD2-DEC-01: Higher-to-lower flow requirement
- DD2-DEC-02: Circular dependency prevention
- DD2-LESS-01: Cost of dependencies

---

### Architecture Selection

**Projects declare which architectures they use:**

```yaml
# knowledge-config.yaml

languages:
  python:
    enabled: true
    architectures:
      suga: true      # Use SUGA gateway pattern
      lmms: true      # Use lazy module management
      zaph: true      # Use zone access priority
      dd-1: true      # Use dictionary dispatch (performance)
      dd-2: true      # Use dependency disciplines (architecture)
      cr-1: true      # Use cache registry (consolidation)
```

### Why This Organization?

**1. Architectures Are Language-Specific**
- SUGA is a Python architecture pattern
- DD-1 is a Python performance pattern
- DD-2 is a Python architecture pattern
- CR-1 is a Python consolidation pattern
- Different languages might have different patterns
- Keep language-specific patterns together

**2. Complete Knowledge per Architecture**
- Each architecture is self-contained
- All decisions, lessons, anti-patterns in one place
- Easy to understand requirements

**3. Selective Adoption**
- Projects can use SUGA without LMMS
- Projects can use DD-1 without DD-2
- Mix and match as needed

**4. Clear Boundaries**
- Platform knowledge (AWS Lambda) separate from architecture (SUGA)
- Performance patterns (DD-1) separate from architecture patterns (DD-2)
- Architecture patterns don't assume platform

### Example: LEE Project

**LEE Uses:**
- Platform: AWS Lambda
- Language: Python
- Architectures: SUGA + LMMS + ZAPH + DD-1 + DD-2 + CR-1

**Knowledge Loaded:**
```
/sima/entries/**                      (universal patterns)
/sima/platforms/aws/lambda/**         (Lambda specifics)
/sima/languages/python/**             (Python patterns)
/sima/languages/python/architectures/suga/**
/sima/languages/python/architectures/lmms/**
/sima/languages/python/architectures/zaph/**
/sima/languages/python/architectures/dd-1/**  (dictionary dispatch)
/sima/languages/python/architectures/dd-2/**  (dependency disciplines)
/sima/languages/python/architectures/cr-1/**  (cache registry)
/sima/projects/lee/**                 (LEE specifics)
```

### Example: Hypothetical Standard Python Project

**Standard Python Project (no special architectures):**
- Platform: Generic Server
- Language: Python
- Architectures: None (standard Python patterns)

**Knowledge Loaded:**
```
/sima/entries/**                      (universal patterns)
/sima/platforms/generic-server/**     (server patterns)
/sima/languages/python/**             (Python patterns)
                                      (NO architecture patterns)
```

**RED FLAGS Adjusted:**
- ✅ Can use threading (not Lambda)
- ✅ Can use direct imports (no SUGA)
- ✅ Can use subdirectories (no SUGA)
- ✅ Can use if-else chains (no DD-1 requirement)
- ❌ Still avoid bare except (universal)

---

## 📄 MIGRATION DECISION TREE

### For Each Entry Being Migrated

```
START: Analyzing entry [ENTRY-NAME]
  |
  V
Q1: Is this an Interface definition (INT-##)?
  |
  +--> YES --> Is it SUGA-specific or universal?
  |              |
  |              +--> SUGA-specific (gateway pattern required)
  |              |      --> /languages/python/architectures/suga/interfaces/
  |              |
  |              +--> Universal pattern (any language/architecture)
  |                    --> /entries/interfaces/
  |
  +--> NO --> Continue to Q2
  |
  V
Q2: Is this about a specific architecture?
  |
  +--> YES --> Which architecture?
  |              |
  |              +--> SUGA → /languages/python/architectures/suga/
  |              +--> LMMS → /languages/python/architectures/lmms/
  |              +--> ZAPH → /languages/python/architectures/zaph/
  |              +--> DD-1 (Dictionary Dispatch) → /languages/python/architectures/dd-1/
  |              +--> DD-2 (Dependency Disciplines) → /languages/python/architectures/dd-2/
  |              +--> CR-1 (Cache Registry) → /languages/python/architectures/cr-1/
  |
  +--> NO --> Continue to Q3
  |
  V
Q3: Does it mention specific platform (AWS Lambda, Azure, GCP)?
  |
  +--> YES --> Which platform?
  |              |
  |              +--> AWS Lambda → /platforms/aws/lambda/
  |              +--> AWS API Gateway → /platforms/aws/api-gateway/
  |              +--> [other services] → /platforms/[platform]/[service]/
  |
  +--> NO --> Continue to Q4
  |
  V
Q4: Does it mention specific language (Python, JavaScript)?
  |
  +--> YES --> Which language?
  |              |
  |              +--> Python (not architecture) → /languages/python/
  |              +--> JavaScript → /languages/javascript/
  |              +--> [other] → /languages/[language]/
  |
  +--> NO --> Continue to Q5
  |
  V
Q5: Is this specific to LEE project implementation?
  |
  +--> YES --> LEE-specific → /projects/lee/
  |
  +--> NO --> Continue to Q6
  |
  V
Q6: Is this a universal pattern (no platform/language/project specifics)?
  |
  +--> YES --> Which category?
  |              |
  |              +--> Core pattern → /entries/core/
  |              +--> Gateway concept → /entries/gateways/
  |              +--> Decision → /entries/decisions/
  |              +--> Anti-pattern → /entries/anti-patterns/
  |              +--> Lesson → /entries/lessons/
  |
  +--> NO --> ERROR: Unclear classification
  |              |
  |              +--> Document why unclear
  |              +--> Get human decision
  |              +--> Update decision tree
```

### Examples

**Entry: "Dictionary dispatch for function routing"**
```
Q1: Interface? NO
Q2: Architecture? YES - DD-1 (Dictionary Dispatch performance pattern)
--> /languages/python/architectures/dd-1/core/DD1-01-Core-Concept.md
```

**Entry: "Higher layers depend on lower layers only"**
```
Q1: Interface? NO
Q2: Architecture? YES - DD-2 (Dependency Disciplines architecture)
--> /languages/python/architectures/dd-2/core/DD2-02-Layer-Rules.md
```

**Entry: "Central registry consolidates all gateway exports"**
```
Q1: Interface? NO
Q2: Architecture? YES - CR-1 (Cache Registry consolidation)
--> /languages/python/architectures/cr-1/core/CR1-01-Registry-Concept.md
```

**Entry: "No threading locks in Lambda"**
```
Q1: Interface? NO
Q2: Architecture? NO (Lambda constraint, not architecture)
Q3: Platform? YES - AWS Lambda
--> /platforms/aws/lambda/decisions/aws-lambda-dec-threading.md
```

**Entry: "Always import via gateway"**
```
Q1: Interface? NO
Q2: Architecture? YES - SUGA requirement
--> /languages/python/architectures/suga/gateways/GATE-03-Gateway-Mandatory.md
```

**Entry: "Lazy imports reduce cold start"**
```
Q1: Interface? NO
Q2: Architecture? YES - LMMS pattern
--> /languages/python/architectures/lmms/core/LMMS-02-Cold-Start.md
```

**Entry: "Read complete files before modifying"**
```
Q1: Interface? NO
Q2: Architecture? NO
Q3: Platform? NO
Q4: Language? NO (applies to any language)
Q5: Project? NO
Q6: Universal? YES - Lesson
--> /entries/lessons/core-architecture/LESS-01-Read-Complete.md
```

**Entry: "INT-01 CACHE interface pattern"**
```
Q1: Interface? YES
Is it SUGA-specific? YES (requires gateway)
--> /languages/python/architectures/suga/interfaces/INT-01-CACHE-Interface.md
```

---

## 📊 MIGRATION TIMELINE

### Week 1: Setup and Audit

**Monday (Day 1): Directory Structure + Specifications**
- Create all platform/project/language directories
- Create /languages/python/architectures/ directories (including dd-1 and dd-2)
- Create /entries/specifications/ directory
- Create all 11 specification files (SPEC-*.md)
- Create configuration directories for projects
- **Work continuously until <30,000 tokens, then create transition**

**Tuesday (Day 2): Configuration Files + Architecture Organization**
- Create knowledge-config.yaml for LEE project
- Create architecture knowledge files:
  - SUGA architecture files
  - LMMS architecture files
  - ZAPH architecture files
  - DD-1 architecture files (Dictionary Dispatch)
  - DD-2 architecture files (Dependency Disciplines)
- Create all 12 function reference files
- Create index templates
- **Work continuously until <30,000 tokens, then create transition**

**Wednesday (Day 3): Configuration Integration**
- Enhance fileserver.php with configuration filtering
- Update mode context files with configuration awareness
- Create configuration validation tools
- Audit all entries with architecture considerations
- **Work continuously until <30,000 tokens, then create transition**

**Thursday (Day 4): Entry Migration - Generic**
- Migrate entries to /entries/ (universal patterns)
- Create generic indexes
- Update cross-references
- **Work continuously until <30,000 tokens, then create transition**

**Friday (Day 5): Entry Migration - Architectures**
- Migrate SUGA entries to /languages/python/architectures/suga/
- Migrate LMMS entries to /languages/python/architectures/lmms/
- Migrate ZAPH entries to /languages/python/architectures/zaph/
- Migrate DD-1 entries to /languages/python/architectures/dd-1/ (Dictionary Dispatch)
- Migrate DD-2 entries to /languages/python/architectures/dd-2/ (Dependency Disciplines)
- Create CR-1 entries in /languages/python/architectures/cr-1/ (Cache Registry - new)
- **Work continuously until <30,000 tokens, then create transition**

### Week 2: Platform and Project Migration

**Monday (Day 6): Platform Migration**
- Migrate AWS Lambda entries to /platforms/aws/lambda/
- Migrate AWS API Gateway entries
- Migrate AWS DynamoDB entries
- Create platform indexes
- **Work continuously until <30,000 tokens, then create transition**

**Tuesday (Day 7): Language Migration**
- Migrate Python-specific entries to /languages/python/
- Create language indexes
- Update cross-references
- **Work continuously until <30,000 tokens, then create transition**

**Wednesday (Day 8): Project Migration**
- Migrate LEE-specific entries to /projects/lee/
- Create project indexes
- Update function references
- **Work continuously until <30,000 tokens, then create transition**

**Thursday (Day 9): Backward Compatibility**
- Create redirect files in /redirects/
- Update all internal links
- Test redirect system
- **Work continuously until <30,000 tokens, then create transition**

**Friday (Day 10): Testing and Validation**
- Run validation tools
- Test configuration system
- Verify all cross-references
- Test mode context files
- **Work continuously until <30,000 tokens, then create transition**

### Week 3: Finalization

**Monday-Wednesday (Days 11-13): Fixes and Polish**
- Address validation errors
- Update documentation
- Create migration guide
- **Work continuously until <30,000 tokens, then create transition**

**Thursday-Friday (Days 14-15): Deployment**
- Deploy new structure
- Update fileserver.php
- Test all modes
- Document changes
- **Work continuously until <30,000 tokens, then create transition**

---

## 🛠️ TOOLS AND AUTOMATION

### Tool 1: Entry Classifier

```python
# classify-entry.py
# Helps classify entries into correct domain

import re

def classify_entry(entry_path, entry_content):
    """Classify entry into correct migration path."""
    
    classification = {
        'is_interface': False,
        'is_architecture': None,
        'is_platform': None,
        'is_language': None,
        'is_project': False,
        'is_generic': False,
        'suggested_path': None
    }
    
    # Check for interface pattern
    if re.search(r'INT-\d{2}', entry_content):
        classification['is_interface'] = True
        # Check if SUGA-specific
        if 'gateway' in entry_content.lower() and 'import' in entry_content.lower():
            classification['suggested_path'] = '/languages/python/architectures/suga/interfaces/'
        else:
            classification['suggested_path'] = '/entries/interfaces/'
        return classification
    
    # Check for architecture keywords
    if 'SUGA' in entry_content or 'gateway' in entry_content:
        classification['is_architecture'] = 'suga'
        classification['suggested_path'] = '/languages/python/architectures/suga/'
    elif 'LMMS' in entry_content or 'lazy import' in entry_content:
        classification['is_architecture'] = 'lmms'
        classification['suggested_path'] = '/languages/python/architectures/lmms/'
    elif 'ZAPH' in entry_content or 'zone access' in entry_content:
        classification['is_architecture'] = 'zaph'
        classification['suggested_path'] = '/languages/python/architectures/zaph/'
    elif 'dictionary dispatch' in entry_content.lower() or 'dispatch dict' in entry_content.lower():
        classification['is_architecture'] = 'dd-1'
        classification['suggested_path'] = '/languages/python/architectures/dd-1/'
    elif 'dependency discipline' in entry_content.lower() or 'higher lower flow' in entry_content.lower():
        classification['is_architecture'] = 'dd-2'
        classification['suggested_path'] = '/languages/python/architectures/dd-2/'
    elif 'cache registry' in entry_content.lower() or 'central registry' in entry_content.lower() or 'gateway consolidation' in entry_content.lower():
        classification['is_architecture'] = 'cr-1'
        classification['suggested_path'] = '/languages/python/architectures/cr-1/'
    
    # Check for platform keywords
    if 'Lambda' in entry_content or 'AWS Lambda' in entry_content:
        classification['is_platform'] = 'aws-lambda'
        classification['suggested_path'] = '/platforms/aws/lambda/'
    elif 'API Gateway' in entry_content:
        classification['is_platform'] = 'aws-api-gateway'
        classification['suggested_path'] = '/platforms/aws/api-gateway/'
    elif 'DynamoDB' in entry_content:
        classification['is_platform'] = 'aws-dynamodb'
        classification['suggested_path'] = '/platforms/aws/dynamodb/'
    
    # Check for language keywords
    if 'Python' in entry_content and not classification['is_architecture']:
        classification['is_language'] = 'python'
        classification['suggested_path'] = '/languages/python/'
    
    # Check for project keywords
    if 'LEE' in entry_content or 'Home Assistant' in entry_content:
        classification['is_project'] = True
        classification['suggested_path'] = '/projects/lee/'
    
    # If none of the above, it's generic
    if not any([
        classification['is_architecture'],
        classification['is_platform'],
        classification['is_language'],
        classification['is_project']
    ]):
        classification['is_generic'] = True
        classification['suggested_path'] = '/entries/'
    
    return classification

# Usage
with open('some-entry.md', 'r') as f:
    content = f.read()

result = classify_entry('some-entry.md', content)
print(f"Suggested path: {result['suggested_path']}")
```

### Tool 2: Cross-Reference Updater

```python
# update-cross-references.py
# Updates cross-references to use new paths

import os
import re

def update_cross_references(file_path, old_to_new_map):
    """Update cross-references in a file."""
    
    with open(file_path, 'r') as f:
        content = f.read()
    
    updated = content
    
    for old_path, new_path in old_to_new_map.items():
        # Update markdown links
        updated = re.sub(
            rf'\[([^\]]+)\]\({old_path}\)',
            rf'[\1]({new_path})',
            updated
        )
        
        # Update REF-ID references
        updated = re.sub(
            rf'REF: {old_path}',
            f'REF: {new_path}',
            updated
        )
    
    with open(file_path, 'w') as f:
        f.write(updated)

# Usage
old_to_new = {
    '/entries/core/ARCH-SUGA.md': '/languages/python/architectures/suga/core/ARCH-01.md',
    '/entries/core/ARCH-LMMS.md': '/languages/python/architectures/lmms/core/LMMS-01.md',
    '/entries/core/ARCH-DD.md': '/languages/python/architectures/dd-2/core/DD2-01.md',  # DD-2
    # DD-1 is new, no old path
    # ... more mappings
}

update_cross_references('some-file.md', old_to_new)
```

### Tool 3: Index Generator

```python
# generate-indexes.py
# Generates index files for each domain

import os
import glob

def generate_index(directory, title):
    """Generate index file for a directory."""
    
    entries = []
    
    # Find all .md files
    for file_path in glob.glob(f"{directory}/**/*.md", recursive=True):
        if 'index' not in file_path.lower():
            with open(file_path, 'r') as f:
                # Extract title from first line
                first_line = f.readline().strip()
                if first_line.startswith('#'):
                    title = first_line.lstrip('#').strip()
                    entries.append({
                        'title': title,
                        'path': file_path
                    })
    
    # Sort entries
    entries.sort(key=lambda x: x['path'])
    
    # Generate index content
    index_content = f"# {title}\n\n"
    index_content += "## Entries\n\n"
    
    for entry in entries:
        index_content += f"- [{entry['title']}]({entry['path']})\n"
    
    # Write index file
    index_path = os.path.join(directory, 'index.md')
    with open(index_path, 'w') as f:
        f.write(index_content)
    
    print(f"Generated index: {index_path}")

# Usage
generate_index('/languages/python/architectures/suga/', 'SUGA Architecture')
generate_index('/languages/python/architectures/dd-1/', 'DD-1: Dictionary Dispatch')
generate_index('/languages/python/architectures/dd-2/', 'DD-2: Dependency Disciplines')
generate_index('/platforms/aws/lambda/', 'AWS Lambda')
```

### Tool 4: Migration Validator

```python
# validate-migration.py
# Validates migration completeness

import os
import glob
import yaml

def validate_migration(base_dir):
    """Validate migration completeness."""
    
    issues = []
    
    # Check directory structure
    required_dirs = [
        '/entries',
        '/platforms/aws/lambda',
        '/languages/python/architectures/suga',
        '/languages/python/architectures/lmms',
        '/languages/python/architectures/zaph',
        '/languages/python/architectures/dd-1',  # Dictionary Dispatch
        '/languages/python/architectures/dd-2',  # Dependency Disciplines
        '/projects/lee'
    ]
    
    for dir_path in required_dirs:
        full_path = os.path.join(base_dir, dir_path.lstrip('/'))
        if not os.path.exists(full_path):
            issues.append(f"Missing directory: {dir_path}")
    
    # Check for old structure references
    for file_path in glob.glob(f"{base_dir}/**/*.md", recursive=True):
        with open(file_path, 'r') as f:
            content = f.read()
            
            # Check for old paths
            if '/NM##/' in content:
                issues.append(f"Old path reference in {file_path}")
            
            # Check for uncategorized entries
            if 'TODO: categorize' in content:
                issues.append(f"Uncategorized entry in {file_path}")
            
            # Check for ambiguous DD references
            if 'DD-' in content and not ('DD1-' in content or 'DD2-' in content or 'DD-1' in content or 'DD-2' in content):
                issues.append(f"Ambiguous DD reference in {file_path} - should be DD1- or DD2-")
    
    # Check indexes exist
    for dir_path in required_dirs:
        full_path = os.path.join(base_dir, dir_path.lstrip('/'))
        if os.path.exists(full_path):
            index_path = os.path.join(full_path, 'index.md')
            if not os.path.exists(index_path):
                issues.append(f"Missing index in {dir_path}")
    
    return issues

# Usage
issues = validate_migration('/sima')
if issues:
    print("Migration issues found:")
    for issue in issues:
        print(f"  - {issue}")
else:
    print("✅ Migration validation passed")
```

### Tool 5: Configuration Validator

```python
# validate-config.py
# Validates knowledge configuration files

import yaml
import os

def validate_knowledge_config(config_path):
    """Validate knowledge configuration file."""
    
    with open(config_path, 'r') as f:
        config = yaml.safe_load(f)
    
    errors = []
    
    # Check required sections
    required = ['project', 'knowledge', 'context', 'file_access']
    for section in required:
        if section not in config:
            errors.append(f"Missing required section: {section}")
    
    # Check project info
    if 'project' in config:
        if 'name' not in config['project']:
            errors.append("Missing project name")
        if 'description' not in config['project']:
            errors.append("Missing project description")
        if 'version' not in config['project']:
            errors.append("Missing project version")
    
    # Check generic is enabled (cannot disable)
    if config.get('knowledge', {}).get('generic', {}).get('enabled') == False:
        errors.append("Generic knowledge cannot be disabled")
    
    # Check at least one language enabled
    languages = config.get('knowledge', {}).get('languages', {})
    if not any(lang.get('enabled', False) for lang in languages.values()):
        errors.append("At least one language must be enabled")
    
    # Check file paths exist
    include_paths = config.get('file_access', {}).get('include_paths', [])
    if not include_paths:
        errors.append("Must have at least one include path")
    
    # Check for contradictions
    exclude_paths = config.get('file_access', {}).get('exclude_paths', [])
    for path in exclude_paths:
        if path in include_paths:
            errors.append(f"Path both included and excluded: {path}")
    
    # Check for DD-1 and DD-2 distinction
    python_arches = config.get('knowledge', {}).get('languages', {}).get('python', {}).get('architectures', {})
    if python_arches:
        # Check if using old 'dd' key instead of 'dd-1' and 'dd-2'
        if 'dd' in python_arches:
            errors.append("Ambiguous 'dd' architecture - must specify 'dd-1' (Dictionary Dispatch) or 'dd-2' (Dependency Disciplines)")
    
    return errors

# Usage
errors = validate_knowledge_config("/sima/projects/lee/config/knowledge-config.yaml")
if errors:
    print("Validation errors:")
    for error in errors:
        print(f"  - {error}")
else:
    print("✅ Configuration valid")
```

---

## ✅ VALIDATION CHECKLIST

### Directory Structure
- [ ] /entries/ exists with all subdirectories
- [ ] /platforms/ exists with aws/azure/gcp
- [ ] /languages/python/architectures/ exists
- [ ] /languages/python/architectures/suga/ complete
- [ ] /languages/python/architectures/lmms/ complete
- [ ] /languages/python/architectures/zaph/ complete
- [ ] /languages/python/architectures/dd-1/ complete (Dictionary Dispatch)
- [ ] /languages/python/architectures/dd-2/ complete (Dependency Disciplines)
- [ ] /languages/python/architectures/cr-1/ complete (Cache Registry)
- [ ] /projects/lee/ exists with all subdirectories

### Entry Migration
- [ ] All generic entries in /entries/
- [ ] All SUGA entries in /languages/python/architectures/suga/
- [ ] All LMMS entries in /languages/python/architectures/lmms/
- [ ] All ZAPH entries in /languages/python/architectures/zaph/
- [ ] All DD-1 (Dictionary Dispatch) entries in /languages/python/architectures/dd-1/
- [ ] All DD-2 (Dependency Disciplines) entries in /languages/python/architectures/dd-2/
- [ ] All CR-1 (Cache Registry) entries in /languages/python/architectures/cr-1/
- [ ] All AWS Lambda entries in /platforms/aws/lambda/
- [ ] All LEE entries in /projects/lee/
- [ ] No orphaned entries
- [ ] No duplicate entries
- [ ] No ambiguous DD references (all marked DD1- or DD2-)
- [ ] No ambiguous CR references (all marked CR1-)

### Cross-References
- [ ] All internal links updated
- [ ] All REF-IDs updated
- [ ] All indexes generated
- [ ] All cross-reference matrices updated
- [ ] No broken links
- [ ] DD references properly distinguished (DD1- vs DD2-)

### Configuration System
- [ ] SPEC-KNOWLEDGE-CONFIG.md created
- [ ] LEE project configuration created
- [ ] Configuration includes both dd-1 and dd-2
- [ ] Configuration validation tool working
- [ ] fileserver.php supports filtering

### Testing
- [ ] Entry classifier tool working
- [ ] Entry classifier distinguishes DD-1 from DD-2
- [ ] Cross-reference updater working
- [ ] Index generator working
- [ ] Migration validator passing
- [ ] Configuration validator passing
- [ ] All four modes load correctly
- [ ] Search works across domains

### Documentation
- [ ] Migration guide created
- [ ] Architecture organization documented
- [ ] DD-1 and DD-2 distinction documented
- [ ] Configuration examples created
- [ ] Tool usage documented
- [ ] Troubleshooting guide created

---

## 🎯 POST-MIGRATION BENEFITS

### 1. Clear Knowledge Boundaries
**Before:** Everything mixed together, ambiguous DD references
**After:** Clear separation by domain, DD-1 and DD-2 clearly distinguished

**Impact:**
- Faster knowledge discovery
- Reduced confusion between Dictionary Dispatch and Dependency Disciplines
- Better organization

### 2. Scalability
**Before:** Adding new architecture/pattern required reorganizing everything
**After:** Just add new directory under appropriate domain

**Impact:**
- Easy to add new platforms
- Easy to add new languages
- Easy to add new architectures
- Easy to add new projects

### 3. Selective Loading
**Before:** All knowledge loaded for every project
**After:** Only relevant knowledge loaded

**Impact:**
- Faster context loading
- Less noise
- Better recommendations
- Project-appropriate guidance

### 4. Architecture Flexibility
**Before:** SUGA/DD assumed for all Python projects
**After:** Architectures are optional patterns

**Impact:**
- Can use SUGA where beneficial
- Can use DD-1 for performance optimization
- Can use DD-2 for architecture discipline
- Can use standard Python where simpler
- Clear architecture requirements

### 5. Platform Independence
**Before:** Lambda constraints mixed with architecture
**After:** Platform and architecture separate

**Impact:**
- SUGA usable on any platform
- DD-1 usable anywhere Python runs
- DD-2 usable for any architecture
- Platform constraints clear
- No conflation of concerns
- Better decision making

### 6. Better Recommendations
**Before:** Might recommend Lambda patterns for server or confuse DD patterns
**After:** Context-aware recommendations

**Impact:**
- Threading advice appropriate to platform
- Architecture advice appropriate to project
- Performance patterns (DD-1) vs architecture patterns (DD-2) distinguished
- Platform advice appropriate to deployment
- Better development experience

### 7. Backward Compatibility
**Before:** N/A (first migration)
**After:** Old REF-IDs still work via redirects

**Impact:**
- Existing documentation works
- No broken links
- Smooth transition
- Zero disruption

---

**END OF KNOWLEDGE MIGRATION PLAN**

**Version:** v4.2.3  
**Status:** Ready for Execution - COMPLETE & CORRECTED  
**Key Updates:**
- Added CR-1 (Cache Registry) as 6th Python architecture
- Split DD into DD-1 (Dictionary Dispatch) and DD-2 (Dependency Disciplines)
- Updated all directory structures
- Updated all examples
- Updated all tools
- Updated validation checklist
**Next Steps:** Begin execution with corrected 6-architecture structure

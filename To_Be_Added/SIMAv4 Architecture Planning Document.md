# SIMAv4 Architecture Planning Document

**Version:** 4.0.0-DRAFT  
**Date:** 2025-10-27  
**Status:** Planning / Review  
**Purpose:** Multi-tier gatewayed neural map system for multi-project, multi-language support

---

## 🎯 Executive Summary

**Problem:** Current SIMA structure (nm, NMP, AWS) lacks:
- Clear hierarchy for scaling across projects
- Project-specific configuration isolation
- Multi-language support
- Systematic gateway pattern like SUGA-ISP

**Solution:** SIMAv4 implements a 4-tier architecture:
```
Configuration Layer (which maps, which project, which language)
    ↓
Gateway Layer (main indexes - "what exists")
    ↓
Interface Layer (category indexes - "how it's organized")
    ↓
Individual Layer (actual entries - "the knowledge")
```

---

## 📊 SIMAv4 Architecture Overview

### Tier 1: Configuration Layer
**Purpose:** Define what's active and in scope

```
/sima-config/
  ├── SIMA-MAIN-CONFIG.md          # Master config: which map sets enabled
  ├── project-configs/
  │   ├── PROJECT-LAMBDA-CONFIG.md  # Lambda-specific specs
  │   ├── PROJECT-WEB-CONFIG.md     # Web app specs (future)
  │   └── PROJECT-MOBILE-CONFIG.md  # Mobile specs (future)
  └── language-configs/
      ├── LANG-PYTHON-CONFIG.md     # Python patterns/limits
      ├── LANG-JAVASCRIPT-CONFIG.md # JS patterns/limits (future)
      └── LANG-GO-CONFIG.md         # Go patterns/limits (future)
```

### Tier 2: Gateway Layer
**Purpose:** Main entry points - "master indexes of indexes"

```
/sima-gateways/
  ├── GATEWAY-CORE.md              # Core programming concepts (universal)
  ├── GATEWAY-ARCHITECTURE.md      # Architecture patterns (universal)
  ├── GATEWAY-LANGUAGE.md          # Language-specific gateway
  ├── GATEWAY-PROJECT.md           # Project-specific gateway
  └── GATEWAY-SUPPORT.md           # Tools/workflows/utilities
```

### Tier 3: Interface Layer
**Purpose:** Category-specific indexes - "organized collections"

```
/sima-interfaces/
  ├── core/
  │   ├── INT-PATTERNS.md          # Design patterns index
  │   ├── INT-PRINCIPLES.md        # Programming principles index
  │   └── INT-ANTIPATTERNS.md      # Anti-patterns index
  ├── architecture/
  │   ├── INT-GATEWAY-PATTERN.md   # Gateway architecture index
  │   ├── INT-FAAS.md              # FaaS architecture index
  │   └── INT-MICROSERVICES.md     # Microservices index
  ├── languages/
  │   ├── python/
  │   │   ├── INT-PY-CORE.md       # Python core concepts
  │   │   ├── INT-PY-STDLIB.md     # Standard library
  │   │   └── INT-PY-PATTERNS.md   # Python-specific patterns
  │   ├── javascript/
  │   │   └── INT-JS-CORE.md       # (future)
  │   └── go/
  │       └── INT-GO-CORE.md       # (future)
  └── projects/
      ├── lambda/
      │   ├── INT-LAMBDA-CORE.md   # Lambda-specific patterns
      │   ├── INT-LAMBDA-LIMITS.md # Lambda constraints
      │   └── INT-LAMBDA-APIS.md   # Lambda API patterns
      └── web/
          └── INT-WEB-CORE.md      # (future)
```

### Tier 4: Individual Layer
**Purpose:** Actual knowledge entries - "the content"

```
/sima-entries/
  ├── core/
  │   ├── CORE-001-singleton-pattern.md
  │   ├── CORE-002-factory-pattern.md
  │   └── CORE-003-observer-pattern.md
  ├── architecture/
  │   ├── ARCH-001-gateway-layer.md
  │   ├── ARCH-002-interface-layer.md
  │   └── ARCH-003-core-layer.md
  ├── languages/
  │   └── python/
  │       ├── PY-001-list-comprehensions.md
  │       ├── PY-002-generators.md
  │       └── PY-003-context-managers.md
  └── projects/
      └── lambda/
          ├── LAM-001-cold-start-optimization.md
          ├── LAM-002-memory-management.md
          └── LAM-003-timeout-patterns.md
```

---

## 🔧 Configuration File Specifications

### 1. SIMA-MAIN-CONFIG.md
**Purpose:** Master control - which map sets are active

```markdown
# SIMA Main Configuration
Version: 4.0.0
Last Updated: 2025-10-27

## Active Map Sets
- ✅ CORE: Enabled (universal programming concepts)
- ✅ ARCHITECTURE: Enabled (universal architecture patterns)
- ✅ LANGUAGE-PYTHON: Enabled (Python-specific)
- ⏸️ LANGUAGE-JAVASCRIPT: Disabled (not yet needed)
- ⏸️ LANGUAGE-GO: Disabled (not yet needed)
- ✅ PROJECT-LAMBDA: Enabled (current project)
- ⏸️ PROJECT-WEB: Disabled (future project)
- ⏸️ PROJECT-MOBILE: Disabled (future project)

## Active Project
Current: LAMBDA
Config: /sima-config/project-configs/PROJECT-LAMBDA-CONFIG.md

## Active Languages
Primary: PYTHON
Config: /sima-config/language-configs/LANG-PYTHON-CONFIG.md

## Gateway Priority Order
1. PROJECT-LAMBDA (most specific)
2. LANGUAGE-PYTHON (language-specific)
3. ARCHITECTURE (patterns)
4. CORE (universal principles)
```

### 2. PROJECT-LAMBDA-CONFIG.md
**Purpose:** Lambda-specific constraints and specifications

```markdown
# Project Configuration: AWS Lambda (SUGA-ISP)
Version: 1.0.0
Project: SUGA-ISP Lambda Execution Engine

## Runtime Specifications
- **Execution Model:** Single-threaded
- **Memory Limit:** 128 MB
- **Timeout:** 30 seconds max
- **Concurrency:** No threading (stateless per invocation)
- **Storage:** Ephemeral /tmp only (512 MB)

## Language Constraints
- **Primary Language:** Python 3.12
- **Language Config:** /sima-config/language-configs/LANG-PYTHON-CONFIG.md

## Architecture Requirements
- **Pattern:** SUGA (Gateway → Interface → Core)
- **Layer Count:** 3 (mandatory)
- **Import Rules:** No direct core imports from gateway
- **Error Handling:** Specific exceptions only (no bare except)

## Anti-Patterns (Lambda-Specific)
- ❌ Threading/locks (single-threaded runtime)
- ❌ Heavy libraries without justification (128MB limit)
- ❌ File system persistence (ephemeral only)
- ❌ Long-running background tasks (30s timeout)
- ❌ Stateful operations between invocations

## Applicable Neural Map Sets
- CORE: Universal patterns (filtered for Lambda constraints)
- ARCHITECTURE: Gateway pattern, FaaS patterns
- LANGUAGE-PYTHON: Standard library, Lambda-compatible libraries
- PROJECT-LAMBDA: Lambda-specific patterns and optimizations

## Cross-References
- Architecture Gateway: /sima-gateways/GATEWAY-ARCHITECTURE.md
- Language Gateway: /sima-gateways/GATEWAY-LANGUAGE.md
- Project Gateway: /sima-gateways/GATEWAY-PROJECT.md
```

### 3. LANG-PYTHON-CONFIG.md
**Purpose:** Python language-specific patterns and conventions

```markdown
# Language Configuration: Python 3.12
Version: 1.0.0
Language: Python
Min Version: 3.12+

## Language Characteristics
- **Paradigm:** Multi-paradigm (OOP, functional, imperative)
- **Typing:** Dynamic with optional static hints (typing module)
- **Memory Model:** Automatic (garbage collected)
- **Concurrency:** Threading, multiprocessing, asyncio

## Standard Library Emphasis
- Collections: dict, list, set, tuple
- Itertools: Efficient iteration patterns
- Functools: Functional programming utilities
- Contextlib: Context manager utilities
- Dataclasses: Data structure definitions

## Pythonic Patterns (Encouraged)
- ✅ List comprehensions over map/filter
- ✅ Context managers (with statements)
- ✅ Generators for lazy evaluation
- ✅ Duck typing over isinstance checks
- ✅ EAFP (Easier to Ask Forgiveness than Permission)

## Python Anti-Patterns (Discouraged)
- ❌ Bare except clauses
- ❌ Mutable default arguments
- ❌ Global variables without clear justification
- ❌ Using `type()` for type checking (use isinstance)
- ❌ Not using context managers for resources

## Project-Specific Constraints
See: /sima-config/project-configs/PROJECT-LAMBDA-CONFIG.md
- May override/restrict based on project (e.g., no threading for Lambda)

## Applicable Neural Map Sets
- CORE: Universal patterns (Python implementations)
- LANGUAGE-PYTHON: Python-specific patterns
- Filtered by active project constraints

## Cross-References
- Language Gateway: /sima-gateways/GATEWAY-LANGUAGE.md
- Python Interface: /sima-interfaces/languages/python/INT-PY-CORE.md
```

---

## 🔀 Gateway Layer Specifications

### GATEWAY-CORE.md
**Purpose:** Index of universal programming concepts

```markdown
# Core Gateway
Version: 4.0.0
Scope: Universal programming concepts (language-agnostic)

## Interface Indexes
1. INT-PATTERNS.md - Design patterns
2. INT-PRINCIPLES.md - Programming principles (SOLID, DRY, KISS)
3. INT-ANTIPATTERNS.md - Common mistakes and anti-patterns
4. INT-DATASTRUCTURES.md - Universal data structures
5. INT-ALGORITHMS.md - Common algorithms

## Entry Count by Interface
- Patterns: 45 entries
- Principles: 23 entries
- Anti-patterns: 31 entries
- Data Structures: 18 entries
- Algorithms: 27 entries

## Filtering Rules
- All entries are language-agnostic (concepts only)
- Implementation details in language-specific interfaces
- Project constraints don't filter core concepts (only implementations)

## Usage Pattern
```
User asks about: "Singleton pattern"
→ Check GATEWAY-CORE.md → Points to INT-PATTERNS.md
→ Find CORE-001-singleton-pattern.md
→ Shows concept (universal)
→ Also check LANGUAGE gateway for Python implementation
→ Also check PROJECT gateway for Lambda-specific considerations
```
```

### GATEWAY-LANGUAGE.md
**Purpose:** Index of language-specific implementations

```markdown
# Language Gateway
Version: 4.0.0
Scope: Language-specific implementations and patterns

## Active Languages
Based on: /sima-config/SIMA-MAIN-CONFIG.md
- ✅ Python 3.12 (active)
- ⏸️ JavaScript (future)
- ⏸️ Go (future)

## Python Interface Indexes
1. INT-PY-CORE.md - Core Python concepts
2. INT-PY-STDLIB.md - Standard library patterns
3. INT-PY-PATTERNS.md - Pythonic patterns
4. INT-PY-ASYNC.md - Async/await patterns
5. INT-PY-TYPING.md - Type hints and mypy

## Entry Count (Python)
- Core: 67 entries
- Stdlib: 89 entries
- Patterns: 45 entries
- Async: 23 entries
- Typing: 34 entries

## Filtering Rules
- Only show entries for ACTIVE language(s)
- Apply project constraints (e.g., no threading if Lambda)
- Cross-reference with CORE concepts

## Usage Pattern
```
User asks about: "Python generators"
→ Check GATEWAY-LANGUAGE.md → Python active
→ Points to INT-PY-CORE.md
→ Find PY-002-generators.md
→ Also cross-ref CORE-015-lazy-evaluation.md (universal concept)
```
```

### GATEWAY-PROJECT.md
**Purpose:** Index of project-specific patterns

```markdown
# Project Gateway
Version: 4.0.0
Scope: Project-specific patterns, constraints, and optimizations

## Active Project
Based on: /sima-config/SIMA-MAIN-CONFIG.md
Current: LAMBDA (AWS Lambda SUGA-ISP)
Config: /sima-config/project-configs/PROJECT-LAMBDA-CONFIG.md

## Lambda Interface Indexes
1. INT-LAMBDA-CORE.md - Lambda architecture patterns
2. INT-LAMBDA-LIMITS.md - Memory, timeout, cold start optimization
3. INT-LAMBDA-APIS.md - AWS SDK patterns
4. INT-LAMBDA-TESTING.md - Lambda-specific testing

## Entry Count (Lambda)
- Core: 34 entries
- Limits: 28 entries
- APIs: 56 entries
- Testing: 19 entries

## Constraint Filtering
- Filters out CORE concepts that violate Lambda constraints
  - Example: Threading patterns → marked as ❌ (single-threaded)
- Filters out LANGUAGE features not applicable
  - Example: Heavy libraries → marked as ⚠️ (128MB limit)

## Usage Pattern
```
User asks about: "Lambda cold start optimization"
→ Check GATEWAY-PROJECT.md → Lambda active
→ Points to INT-LAMBDA-LIMITS.md
→ Find LAM-002-cold-start-optimization.md
→ Also cross-ref CORE-034-lazy-initialization.md (concept)
→ Also cross-ref PY-045-import-optimization.md (implementation)
```
```

---

## 🗂️ Interface Layer Specifications

### Example: INT-LAMBDA-LIMITS.md
**Purpose:** Index of Lambda constraint-specific patterns

```markdown
# Interface Index: Lambda Limits & Constraints
Version: 1.0.0
Gateway: GATEWAY-PROJECT.md
Project: AWS Lambda (SUGA-ISP)

## Scope
Patterns, optimizations, and workarounds for Lambda's hard limits:
- 128 MB memory (configurable, but project uses 128)
- 30 second timeout
- Single-threaded execution
- Ephemeral storage only
- Cold start latency

## Entry Index
### Memory Management (8 entries)
- LAM-001: Memory-efficient data structures
- LAM-002: Lazy loading strategies
- LAM-003: Generator-based processing
- LAM-004: Memory profiling techniques
- LAM-005: Large payload handling
- LAM-006: Library selection criteria
- LAM-007: Import optimization
- LAM-008: Object lifecycle management

### Timeout Optimization (7 entries)
- LAM-020: Async I/O patterns
- LAM-021: Timeout buffer allocation
- LAM-022: Early timeout detection
- LAM-023: Graceful degradation
- LAM-024: Timeout retry strategies
- LAM-025: Long-running task alternatives
- LAM-026: Time budget tracking

### Cold Start Optimization (6 entries)
- LAM-040: Import deferral patterns
- LAM-041: Connection pooling
- LAM-042: Global scope usage
- LAM-043: Lambda layers strategy
- LAM-044: Warm-up techniques
- LAM-045: Provisioned concurrency patterns

### Concurrency Constraints (7 entries)
- LAM-060: Single-threaded alternatives
- LAM-061: Stateless design patterns
- LAM-062: External coordination (SQS/SNS)
- LAM-063: Lambda-to-Lambda communication
- LAM-064: Concurrent invocation patterns
- LAM-065: Rate limiting strategies
- LAM-066: Fan-out/fan-in patterns

## Cross-References
- Core Concepts: GATEWAY-CORE.md
- Python Implementation: GATEWAY-LANGUAGE.md → INT-PY-CORE.md
- Architecture: GATEWAY-ARCHITECTURE.md → INT-FAAS.md

## Constraint Matrix
| Core Concept | Lambda Applicable? | Alternative/Note |
|--------------|-------------------|------------------|
| Threading | ❌ No | Use async/await or multiple invocations |
| Multiprocessing | ❌ No | Use Step Functions or multiple Lambdas |
| Heavy libraries | ⚠️ Conditional | Must justify against 128MB limit |
| File I/O | ⚠️ Limited | /tmp only, ephemeral |
| Long operations | ⚠️ Limited | < 30s or use Step Functions |
```

---

## 📝 Individual Entry Specifications

### Entry Naming Convention
```
{CATEGORY}-{NUMBER}-{kebab-case-title}.md

Examples:
CORE-001-singleton-pattern.md
PY-067-context-managers.md
LAM-045-cold-start-optimization.md
ARCH-012-gateway-layer.md
```

### Entry Template
```markdown
# {REF-ID}: {Title}
Category: {CORE|LANGUAGE|PROJECT|ARCH}
Subcategory: {Specific area}
Version: 1.0.0
Last Updated: YYYY-MM-DD

## Universal Concept
[If CORE entry - language-agnostic explanation]

## Implementation ({Language})
[If LANGUAGE entry - language-specific code/patterns]

## Project Constraints
[If PROJECT entry - project-specific considerations]
[Reference to PROJECT-{NAME}-CONFIG.md for applicability]

## Pattern
{Description of the pattern/concept}

## When to Use
- Use case 1
- Use case 2

## When NOT to Use
- Anti-pattern scenario 1
- Anti-pattern scenario 2

## Example
```{language}
{Code example}
```

## Project-Specific Notes
[Only if this concept has project-specific implications]
**Lambda:** {Lambda-specific notes}
**Web:** {Web-specific notes - future}

## Cross-References
- Related CORE: {REF-ID}
- Related LANGUAGE: {REF-ID}
- Related PROJECT: {REF-ID}
- Related ARCH: {REF-ID}

## REF-ID
{CATEGORY}-{NUMBER}
```

---

## 🔄 Migration Path from Current Structure

### Current Structure (SIMA v3)
```
NM00 - System overview
NM01 - Architecture gateway
NM02 - Interfaces (12 interfaces)
NM03 - SUGA specifics
NM04 - Individual entries (topics)
NM05 - Lessons learned
NM06 - Bugs and decisions
NM07 - Support tools
```

### Migration Strategy

**Phase 1: Configuration Setup (Week 1)**
1. Create `/sima-config/` directory
2. Create `SIMA-MAIN-CONFIG.md`
3. Create `PROJECT-LAMBDA-CONFIG.md`
4. Create `LANG-PYTHON-CONFIG.md`
5. Test configuration loading

**Phase 2: Gateway Creation (Week 2)**
1. Create `/sima-gateways/` directory
2. Create 5 gateway files (CORE, ARCH, LANGUAGE, PROJECT, SUPPORT)
3. Map existing NM00-NM01 content to new gateways

**Phase 3: Interface Reorganization (Week 3-4)**
1. Create `/sima-interfaces/` directory structure
2. Reorganize NM02 (12 interfaces) into new structure:
   - Core interfaces (universal concepts)
   - Architecture interfaces (patterns)
   - Python interfaces (language-specific)
   - Lambda interfaces (project-specific)

**Phase 4: Entry Migration (Week 5-6)**
1. Create `/sima-entries/` directory structure
2. Migrate NM04 entries to new naming:
   - Categorize each entry (CORE/LANG/PROJECT/ARCH)
   - Rename with new REF-ID format
   - Add project constraint sections
3. Migrate NM05 (LESS entries) → appropriate categories
4. Migrate NM06 (BUG/DEC entries) → PROJECT-LAMBDA entries

**Phase 5: Support Tools (Week 7)**
1. Migrate NM07 tools to `/sima-support/`
2. Update tools to reference new gateway system
3. Create configuration-aware search tools

**Phase 6: Validation & Documentation (Week 8)**
1. Validate all cross-references
2. Test configuration switching
3. Update Custom Instructions to reference SIMAv4
4. Create migration completion report

### Mapping Table: Old → New

| Old Location | New Location | Notes |
|--------------|--------------|-------|
| NM00-00-SIMA-Overview.md | SIMA-MAIN-CONFIG.md | Now configuration |
| NM01-01-Architecture-Gateway.md | GATEWAY-ARCHITECTURE.md | Split into ARCH/PROJECT gateways |
| NM02-01-ISP-Interface.md | INT-LAMBDA-CORE.md | Project-specific |
| NM03-* | PROJECT-LAMBDA-CONFIG.md | Project constraints |
| NM04-* | /sima-entries/projects/lambda/* | Recategorized |
| NM05-* (LESS) | /sima-entries/core/* or projects/* | By applicability |
| NM06-* (BUG) | /sima-entries/projects/lambda/* | Project-specific |
| NM07-* (Support) | /sima-support/* | Tools and workflows |

---

## 🎯 Usage Examples

### Example 1: Query Resolution with Configuration

**User Question:** "Should I use threading for parallel API calls in Lambda?"

**Resolution Path:**
1. **Check Active Config:**
   - SIMA-MAIN-CONFIG.md → PROJECT-LAMBDA active
   - PROJECT-LAMBDA-CONFIG.md → Single-threaded constraint

2. **Gateway Routing:**
   - GATEWAY-CORE.md → Check "threading" concept
   - GATEWAY-LANGUAGE.md → Check Python threading
   - GATEWAY-PROJECT.md → Check Lambda constraints

3. **Interface Lookup:**
   - INT-LAMBDA-LIMITS.md → LAM-060: "Single-threaded alternatives"
   - INT-PY-ASYNC.md → PY-034: "Async/await patterns"

4. **Response:**
   ```
   ❌ No - Threading is not applicable in Lambda (single-threaded runtime).
   
   ✅ Use async/await instead:
   - See LAM-060 (Single-threaded alternatives)
   - See PY-034 (Python async patterns)
   - See CORE-078 (Async I/O concept)
   
   Config Reference: PROJECT-LAMBDA-CONFIG.md (Runtime Specifications)
   ```

### Example 2: Multi-Language Future Query

**User Question:** "How do I implement singleton pattern?"

**Resolution Path (Current - Python only):**
1. Check SIMA-MAIN-CONFIG.md → Python active, others disabled
2. GATEWAY-CORE.md → CORE-001: Singleton pattern (concept)
3. GATEWAY-LANGUAGE.md → INT-PY-PATTERNS.md → PY-023: Python singleton
4. Response includes Python implementation only

**Resolution Path (Future - Python + JavaScript active):**
1. Check SIMA-MAIN-CONFIG.md → Python + JavaScript active
2. GATEWAY-CORE.md → CORE-001: Singleton pattern (concept)
3. GATEWAY-LANGUAGE.md → Offers both:
   - INT-PY-PATTERNS.md → PY-023: Python singleton
   - INT-JS-PATTERNS.md → JS-015: JavaScript singleton
4. Response includes both implementations with language tags

### Example 3: Project-Switching Scenario

**Scenario:** Switch from Lambda project to Web project

**Steps:**
1. Update SIMA-MAIN-CONFIG.md:
   ```markdown
   ## Active Project
   Current: WEB
   Config: /sima-config/project-configs/PROJECT-WEB-CONFIG.md
   ```

2. New constraints automatically apply:
   - Threading: ✅ Now allowed (multi-threaded web server)
   - Memory: No 128MB limit
   - Timeout: Different considerations (request timeout vs function timeout)
   - File I/O: ✅ Persistent storage available

3. Neural map filtering changes:
   - LAM-* entries become inactive
   - WEB-* entries become active
   - CORE entries remain (universal)
   - LANG entries remain (Python still primary)

4. Anti-patterns change:
   - Threading anti-pattern lifted
   - New anti-patterns for web security apply

---

## 🔮 Future Extensibility

### Adding a New Language (Example: Go)

**Step 1:** Create configuration
```bash
/sima-config/language-configs/LANG-GO-CONFIG.md
```

**Step 2:** Create interface structure
```bash
/sima-interfaces/languages/go/
  ├── INT-GO-CORE.md
  ├── INT-GO-STDLIB.md
  └── INT-GO-PATTERNS.md
```

**Step 3:** Create entries
```bash
/sima-entries/languages/go/
  ├── GO-001-goroutines.md
  ├── GO-002-channels.md
  └── ...
```

**Step 4:** Update gateway
```markdown
# GATEWAY-LANGUAGE.md
## Active Languages
- ✅ Python 3.12 (active)
- ✅ Go 1.21 (active)  # NEW
```

**Step 5:** Update main config
```markdown
# SIMA-MAIN-CONFIG.md
## Active Map Sets
- ✅ LANGUAGE-GO: Enabled  # NEW
```

### Adding a New Project (Example: Mobile App)

**Step 1:** Create project config
```bash
/sima-config/project-configs/PROJECT-MOBILE-CONFIG.md
```

**Step 2:** Define project specifications
```markdown
# Project Configuration: Mobile App (React Native)
## Runtime Specifications
- Platform: iOS/Android (React Native)
- Languages: JavaScript/TypeScript
- Memory: Device-dependent (variable)
- Offline capability: Required
- State management: Redux

## Anti-Patterns (Mobile-Specific)
- ❌ Heavy computations on UI thread
- ❌ Large bundle sizes (> 10MB)
- ❌ Network calls without offline fallback
```

**Step 3:** Create interface structure
```bash
/sima-interfaces/projects/mobile/
  ├── INT-MOBILE-CORE.md
  ├── INT-MOBILE-UI.md
  └── INT-MOBILE-OFFLINE.md
```

**Step 4:** Create entries
```bash
/sima-entries/projects/mobile/
  ├── MOB-001-offline-first-patterns.md
  ├── MOB-002-bundle-optimization.md
  └── ...
```

---

## 📊 Configuration Precedence Rules

### Search Priority (Most Specific → Least Specific)
```
1. PROJECT-specific entries (e.g., LAM-001)
   ↓ (if not found)
2. LANGUAGE-specific entries (e.g., PY-023)
   ↓ (if not found)
3. ARCHITECTURE entries (e.g., ARCH-012)
   ↓ (if not found)
4. CORE entries (e.g., CORE-001)
```

### Constraint Application (Most Restrictive Wins)
```
PROJECT constraints (most restrictive)
   ↓
LANGUAGE constraints
   ↓
CORE principles (least restrictive, always applicable)
```

### Example Conflict Resolution

**Question:** "Can I use threading?"

**Resolution:**
1. Check PROJECT-LAMBDA-CONFIG.md: ❌ No (single-threaded)
2. Check LANG-PYTHON-CONFIG.md: ✅ Yes (Python supports threading)
3. **Result:** ❌ No (PROJECT constraint is most restrictive)
4. **Guidance:** See LAM-060 for alternatives (async/await)

**Question:** "Can I use threading?" (Future, after switching to WEB project)

**Resolution:**
1. Check PROJECT-WEB-CONFIG.md: ✅ Yes (multi-threaded web server)
2. Check LANG-PYTHON-CONFIG.md: ✅ Yes (Python supports threading)
3. **Result:** ✅ Yes, with caveats
4. **Guidance:** See PY-045 for thread-safe patterns, WEB-023 for web server threading

---

## 🛠️ Tool Integration

### Search Tool Updates

**Current (v3):**
```python
# Searches flat NM structure
search_neural_maps(query) → [NM04-01, NM04-15, ...]
```

**New (v4):**
```python
# Configuration-aware search
search_neural_maps(
    query="threading",
    active_config="/sima-config/SIMA-MAIN-CONFIG.md"
)

# Internally:
# 1. Load active project/language from config
# 2. Filter entries by applicability
# 3. Apply constraint precedence
# 4. Return prioritized results

→ [
    {
        ref_id: "LAM-060",
        title: "Single-threaded alternatives",
        priority: "HIGH",  # PROJECT-level match
        constraint: "❌ No threading (Lambda single-threaded)",
        alternative: "See PY-034 for async/await"
    },
    {
        ref_id: "PY-045",
        title: "Thread-safe patterns",
        priority: "LOW",  # Not applicable under current config
        note: "Disabled (Lambda project active)"
    }
]
```

### Custom Instruction Integration

**Current (v3):**
```
- Load SESSION-START-Quick-Context.md
- Provides access to NM00-NM07
```

**New (v4):**
```
- Load SIMA-MAIN-CONFIG.md first
- Determine active project/language
- Load relevant gateways based on config
- Filter interfaces by active config
- Provide configuration-aware responses
```

---

## 📈 Benefits of SIMAv4

### Scalability
- ✅ Add new projects without restructuring
- ✅ Add new languages independently
- ✅ Multi-project support (switch configs)
- ✅ Clear separation of concerns

### Maintainability
- ✅ Easier to update project-specific content
- ✅ Universal concepts remain stable
- ✅ Configuration changes don't affect entries
- ✅ Clear ownership (CORE vs LANG vs PROJECT)

### Usability
- ✅ Constraint awareness (automatic filtering)
- ✅ Relevant suggestions only (no inapplicable patterns)
- ✅ Clear precedence rules (no ambiguity)
- ✅ Multi-language support (future-ready)

### Quality
- ✅ Prevents suggesting anti-patterns for active project
- ✅ Configuration-driven validation
- ✅ Cross-reference integrity maintained
- ✅ Search results respect active constraints

---

## ❓ Open Questions for Review

1. **Directory Naming:**
   - Use `/sima-config/` vs `/config/`?
   - Use `/sima-gateways/` vs `/gateways/`?
   - Prefix with "sima-" for clarity or use flat names?

2. **REF-ID Format:**
   - Current proposal: `{CATEGORY}-{NUMBER}`
   - Alternative: `{CATEGORY}-{SUBCATEGORY}-{NUMBER}`?
   - Example: `LAM-LIMITS-001` vs `LAM-001`?

3. **Configuration Format:**
   - Markdown (human-readable, proposed)
   - YAML (machine-parseable)
   - JSON (strict schema)
   - Hybrid (MD with YAML frontmatter)?

4. **Entry Duplication:**
   - If a concept applies to CORE, LANGUAGE, and PROJECT, do we:
     a) Create 3 separate entries? (CORE-001, PY-023, LAM-045)
     b) Create 1 entry with 3 sections?
     c) Create CORE entry + cross-ref from LANG/PROJECT?

5. **Constraint Inheritance:**
   - Should LANGUAGE config inherit from CORE?
   - Should PROJECT config inherit from LANGUAGE?
   - Or keep all configs independent with explicit precedence?

6. **Migration Timeline:**
   - 8-week timeline realistic?
   - Phased migration (gradual cutover) vs hard cutover?
   - Maintain v3 in parallel during migration?

7. **Version Control:**
   - SIMAv4 gets `v4.0.0`
   - What about individual entries? Keep their own versions?
   - Gateway/Interface/Entry versioning strategy?

8. **Neural Map Density:**
   - Target number of entries per interface? (Current: ~25-40)
   - Maximum entry size? (Current: < 200 lines)
   - Minimum entry size? (No mininum currently)

---

## 📝 Next Steps

### Immediate (This Review)
1. Review this architecture document
2. Answer open questions (section above)
3. Propose any structural changes
4. Approve or iterate on design

### After Approval
1. Create `/sima-config/` prototype
2. Create 1-2 gateway prototypes
3. Create 1-2 interface prototypes
4. Create 5-10 entry prototypes (different categories)
5. Test configuration switching
6. Build search tool prototype
7. If prototypes validate design → proceed with full migration

### Migration Execution
1. Follow 8-week phased plan (or revised timeline)
2. Weekly checkpoints and validation
3. Parallel v3 maintenance during migration
4. Hard cutover only after 100% validation

---

## 📚 Appendix A: Terminology

- **Gateway:** Top-level index (master index of interfaces)
- **Interface:** Category-level index (index of entries)
- **Entry:** Individual knowledge document (actual content)
- **Configuration:** Settings that control active map sets
- **Constraint:** Project/language-specific limitation
- **Precedence:** Rule for resolving conflicts (PROJECT > LANG > ARCH > CORE)
- **Applicability:** Whether an entry is relevant under current config
- **REF-ID:** Reference identifier (CATEGORY-NUMBER)
- **Cross-reference:** Link between related entries

---

## 📚 Appendix B: File Size Estimates

### Configuration Layer (~15 files)
- SIMA-MAIN-CONFIG.md: ~100 lines
- Project configs (3-5): ~150 lines each
- Language configs (3-5): ~200 lines each
- **Total:** ~2,500 lines

### Gateway Layer (5 files)
- Each gateway: ~200-300 lines
- **Total:** ~1,250 lines

### Interface Layer (~40 files)
- Each interface: ~150-250 lines
- **Total:** ~7,500 lines

### Entry Layer (~300-500 entries)
- Each entry: ~50-150 lines
- **Total:** ~37,500 lines (assuming 300 entries avg 125 lines)

### Grand Total: ~48,750 lines
(vs current SIMA v3: ~35,000 lines estimated)

**Note:** Size increase is due to:
- Explicit configuration files
- Better separation (some duplication across categories)
- More detailed constraint documentation

---

**END OF SIMAv4 ARCHITECTURE PLAN**

**Status:** DRAFT - Awaiting Review  
**Next Action:** Review open questions, approve/iterate design  
**Once Approved:** Create prototypes for validation

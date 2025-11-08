# SIMAv4.2-Complete-Directory-Structure.md

**Version:** 4.2.2  
**Date:** 2025-11-07  
**Purpose:** Complete directory structure for knowledge migration  
**Status:** Implementation Guide  
**Update:** Added CR-1 (Cache Registry) as 6th Python architecture

---

## ROOT STRUCTURE

```
/sima/
├── entries/                 # Generic universal knowledge
├── platforms/               # Platform-specific knowledge
├── languages/               # Language-specific knowledge
├── projects/                # Project implementations
├── context/                 # Mode context files
├── support/                 # Tools, workflows, templates
├── docs/                    # Documentation
└── integration/             # Integration guides
```

---

## ENTRIES (GENERIC UNIVERSAL KNOWLEDGE)

```
/sima/entries/
├── specifications/                      # NEW: File standards
│   ├── SPEC-FILE-STANDARDS.md          # ✅ Created
│   ├── SPEC-LINE-LIMITS.md             # ✅ Created
│   ├── SPEC-HEADERS.md                 # ✅ Created
│   ├── SPEC-NAMING.md                  # ✅ Created
│   ├── SPEC-ENCODING.md                # ✅ Created
│   ├── SPEC-STRUCTURE.md               # ✅ Created
│   ├── SPEC-MARKDOWN.md                # ✅ Created
│   ├── SPEC-CHANGELOG.md               # ✅ Created
│   ├── SPEC-FUNCTION-DOCS.md           # ✅ Created
│   ├── SPEC-CONTINUATION.md            # ✅ Created
│   └── SPEC-KNOWLEDGE-CONFIG.md        # ✅ Created
├── core/                                # Existing
│   ├── ARCH-DD.md (OLD - migrates to dd-2)
│   ├── ARCH-LMMS.md (migrates to lmms)
│   ├── ARCH-SUGA.md (migrates to suga)
│   ├── ARCH-ZAPH.md (migrates to zaph)
│   └── indexes/
├── gateways/                            # Existing
│   ├── GATE-01.md through GATE-05.md
│   └── indexes/
├── interfaces/                          # Existing (generic only)
│   └── indexes/
├── decisions/                           # Existing
│   ├── architecture/
│   ├── technical/
│   ├── operational/
│   └── indexes/
├── anti-patterns/                       # Existing
│   ├── import/
│   ├── concurrency/
│   ├── error-handling/
│   └── indexes/
└── lessons/                             # Existing
    ├── core-architecture/
    ├── operations/
    ├── performance/
    └── indexes/
```

---

## PLATFORMS (PLATFORM-SPECIFIC KNOWLEDGE)

```
/sima/platforms/
├── aws/
│   ├── lambda/                          # Lambda-specific
│   │   ├── lessons/
│   │   ├── decisions/
│   │   ├── anti-patterns/
│   │   └── indexes/
│   ├── api-gateway/                     # API Gateway-specific
│   │   ├── lessons/
│   │   ├── decisions/
│   │   └── indexes/
│   ├── dynamodb/                        # DynamoDB-specific
│   │   ├── lessons/
│   │   ├── decisions/
│   │   └── indexes/
│   ├── ssm/                             # Parameter Store
│   ├── cloudwatch/                      # Logging/monitoring
│   └── indexes/
├── azure/                               # Future
│   ├── functions/
│   └── indexes/
├── gcp/                                 # Future
│   ├── cloud-functions/
│   └── indexes/
└── generic-server/                      # Standard servers
    ├── lessons/
    ├── decisions/
    └── indexes/
```

---

## LANGUAGES (LANGUAGE-SPECIFIC KNOWLEDGE)

```
/sima/languages/
└── python/
    ├── architectures/                   # NEW: Architecture patterns
    │   ├── suga/                        # SUGA Gateway Architecture
    │   │   ├── core/
    │   │   │   ├── ARCH-01-Gateway-Trinity.md
    │   │   │   ├── ARCH-02-Layer-Separation.md
    │   │   │   └── ARCH-03-Interface-Pattern.md
    │   │   ├── gateways/
    │   │   │   ├── GATE-01-Gateway-Entry.md
    │   │   │   ├── GATE-02-Lazy-Imports.md
    │   │   │   └── GATE-03-Circular-Prevention.md
    │   │   ├── interfaces/
    │   │   │   ├── INT-01-CACHE-Interface.md
    │   │   │   ├── INT-02-LOGGING-Interface.md
    │   │   │   └── [... all 12 interfaces]
    │   │   ├── decisions/
    │   │   │   ├── DEC-01-SUGA-Choice.md
    │   │   │   ├── DEC-02-Three-Layer.md
    │   │   │   └── DEC-03-Gateway-Mandatory.md
    │   │   ├── anti-patterns/
    │   │   │   ├── AP-01-Direct-Core-Import.md
    │   │   │   ├── AP-05-Subdirectories.md
    │   │   │   └── AP-XX-Skip-Gateway.md
    │   │   ├── lessons/
    │   │   │   ├── LESS-01-Read-Complete.md
    │   │   │   ├── LESS-15-Verification.md
    │   │   │   └── LESS-XX-Import-Patterns.md
    │   │   └── indexes/
    │   │       ├── suga-index-main.md
    │   │       ├── suga-index-decisions.md
    │   │       └── suga-index-anti-patterns.md
    │   ├── lmms/                        # Lazy Module Management
    │   │   ├── core/
    │   │   │   ├── LMMS-01-Core-Concept.md
    │   │   │   ├── LMMS-02-Cold-Start.md
    │   │   │   └── LMMS-03-Import-Strategy.md
    │   │   ├── decisions/
    │   │   │   ├── LMMS-DEC-01-Function-Level.md
    │   │   │   └── LMMS-DEC-02-Hot-Path.md
    │   │   ├── lessons/
    │   │   │   ├── LMMS-LESS-01-Profile-First.md
    │   │   │   └── LMMS-LESS-02-Measure.md
    │   │   └── indexes/
    │   ├── zaph/                        # Zone Access Priority
    │   │   ├── core/
    │   │   │   ├── ZAPH-01-Tier-System.md
    │   │   │   ├── ZAPH-02-Hot-Paths.md
    │   │   │   └── ZAPH-03-Priority-Rules.md
    │   │   ├── decisions/
    │   │   │   ├── ZAPH-DEC-01-Tier-Assignment.md
    │   │   │   └── ZAPH-DEC-02-Access-Patterns.md
    │   │   ├── lessons/
    │   │   │   └── ZAPH-LESS-01-Discovery.md
    │   │   └── indexes/
    │   ├── dd-1/                        # Dictionary Dispatch (Performance)
    │   │   ├── core/
    │   │   │   ├── DD1-01-Core-Concept.md
    │   │   │   ├── DD1-02-Function-Routing.md
    │   │   │   └── DD1-03-Performance-Trade-offs.md
    │   │   ├── decisions/
    │   │   │   ├── DD1-DEC-01-Dict-Over-If-Else.md
    │   │   │   └── DD1-DEC-02-Memory-Speed-Trade-off.md
    │   │   ├── lessons/
    │   │   │   ├── DD1-LESS-01-Dispatch-Performance.md
    │   │   │   └── DD1-LESS-02-LEE-Interface-Pattern.md
    │   │   └── indexes/
    │   │       └── dd-1-index-main.md
    │   └── dd-2/                        # Dependency Disciplines (Architecture)
    │       ├── core/
    │       │   ├── DD2-01-Core-Concept.md
    │       │   ├── DD2-02-Layer-Rules.md
    │       │   └── DD2-03-Flow-Direction.md
    │       ├── decisions/
    │       │   ├── DD2-DEC-01-Higher-Lower.md
    │       │   └── DD2-DEC-02-No-Circular.md
    │       ├── lessons/
    │       │   └── DD2-LESS-01-Dependencies.md
    │       └── indexes/
    │           └── dd-2-index-main.md
    ├── lessons/                         # General Python lessons
    ├── decisions/                       # General Python decisions
    ├── anti-patterns/                   # General Python anti-patterns
    └── indexes/
```

---

## ARCHITECTURE DESCRIPTIONS

### DD-1: Dictionary Dispatch (Performance Pattern)

**Purpose:** Function routing optimization using dictionaries  
**Origin:** LEE project interface implementation  
**Used In:** LEE interface files (interface_*.py)

**Core Pattern:**
```python
# Traditional if-else chain (slow with many branches)
def handle_action(action, data):
    if action == "turn_on":
        return turn_on_impl(data)
    elif action == "turn_off":
        return turn_off_impl(data)
    elif action == "set_brightness":
        return set_brightness_impl(data)
    # ... 20+ more elif blocks

# Dictionary dispatch pattern (fast O(1) lookup)
DISPATCH_TABLE = {
    "turn_on": turn_on_impl,
    "turn_off": turn_off_impl,
    "set_brightness": set_brightness_impl,
    # ... all actions
}

def handle_action(action, data):
    handler = DISPATCH_TABLE.get(action)
    if handler:
        return handler(data)
    raise ValueError(f"Unknown action: {action}")
```

**Benefits:**
- O(1) lookup vs O(n) if-else chain
- Cleaner, more maintainable code
- Easy to extend (just add to dict)
- Better performance with 10+ actions
- Clear action registry

**Trade-offs:**
- Slightly more memory (dictionary overhead)
- All handler functions loaded at import time
- Not ideal for 2-3 actions (if-else simpler)

**File Organization:**
- DD1-01: Core concept and pattern explanation
- DD1-02: Function routing strategies
- DD1-03: Performance analysis and trade-offs
- DD1-DEC-01: When to use dict over if-else
- DD1-DEC-02: Memory vs speed considerations
- DD1-LESS-01: Performance measurements in LEE
- DD1-LESS-02: LEE interface implementation details

---

### DD-2: Dependency Disciplines (Architecture Pattern)

**Purpose:** Managing dependencies between architectural layers  
**Origin:** SIMA migration architecture patterns  
**Used In:** All SUGA-based projects for layer management

**Core Principles:**
1. **Unidirectional Dependencies**: Higher layers depend on lower layers only
2. **No Circular Dependencies**: Prevent circular import errors
3. **Clear Dependency Flow**: Dependencies flow one direction
4. **Minimize Coupling**: Reduce cross-module dependencies

**Layer Example:**
```
Presentation Layer (highest)
    ↓ (can depend on)
Business Logic Layer
    ↓ (can depend on)
Data Access Layer (lowest)

❌ WRONG: Data layer depends on Business layer (upward dependency)
✅ CORRECT: Business layer depends on Data layer (downward dependency)
```

**Benefits:**
- Prevents circular import errors
- Clearer architecture
- Easier to test (test lower layers first)
- Better maintainability
- Simpler debugging

**Rules:**
1. Dependencies flow higher → lower (never reverse)
2. No bidirectional dependencies between modules
3. No circular import chains
4. Interfaces at boundaries to reduce coupling

**File Organization:**
- DD2-01: Core dependency discipline concepts
- DD2-02: Layer dependency rules
- DD2-03: Dependency flow direction
- DD2-DEC-01: Higher-to-lower flow requirement
- DD2-DEC-02: Circular dependency prevention
- DD2-LESS-01: Cost and impact of dependencies

---

### CR-1: Cache Registry (Consolidation Pattern)

**Purpose:** Central function registry with consolidated gateway exports  
**Origin:** LEE project gateway implementation  
**Used In:** LEE gateway.py and all interface wrappers

**Core Pattern:**
```python
# Central registry maps interfaces to routers
_INTERFACE_ROUTERS = {
    GatewayInterface.CACHE: ('interface_cache', 'execute_cache_operation'),
    GatewayInterface.LOGGING: ('interface_logging', 'execute_logging_operation'),
    # ... all 12 interfaces
}

# Execute through registry
def execute_operation(interface: GatewayInterface, operation: str, **kwargs):
    module_name, func_name = _INTERFACE_ROUTERS[interface]
    module = importlib.import_module(module_name)
    func = getattr(module, func_name)
    return func(operation, **kwargs)

# Wrappers provide convenience
def cache_get(key: str):
    return execute_operation(GatewayInterface.CACHE, 'get', key=key)

# Gateway consolidates all exports
from gateway_wrappers import cache_get, log_info, ...
__all__ = ['cache_get', 'log_info', ...]  # 100+ functions
```

**Benefits:**
- Single import point: `import gateway`
- All 100+ functions accessible from one module
- Fast path caching for frequent operations
- Clear interface-to-module mapping
- Easy function discovery
- Consolidated API

**Components:**
1. **Central Registry** - `_INTERFACE_ROUTERS` maps interfaces to routers
2. **Wrapper Functions** - Convenience functions per interface (gateway_wrappers_*.py)
3. **Consolidated Gateway** - Single export point (gateway.py)
4. **Fast Path Cache** - Performance optimization for frequent operations

**Trade-offs:**
- More memory (all wrappers loaded)
- Single gateway module is large
- But: Massive convenience and discoverability improvement

**File Organization:**
- CR1-01: Core registry concept and pattern
- CR1-02: Wrapper function pattern
- CR1-03: Consolidation strategy
- CR1-DEC-01: Why central registry chosen
- CR1-DEC-02: Export consolidation benefits
- CR1-LESS-01: Fast path optimization lessons
- CR1-LESS-02: Discovery improvements

---

## PROJECTS (PROJECT IMPLEMENTATIONS)

```
/sima/projects/
└── lee/                                 # LEE: Home Automation Lambda
    ├── config/
    │   └── knowledge-config.yaml        # ✅ Created
    ├── interfaces/                      # LEE-specific interfaces
    │   ├── INT-01-CACHE-LEE.md
    │   ├── INT-02-LOGGING-LEE.md
    │   └── [... all 12 for LEE]
    ├── function-references/             # NEW: Function catalogs
    │   ├── INT-01-CACHE-Functions.md
    │   ├── INT-02-LOGGING-Functions.md
    │   └── [... all 12]
    ├── lessons/
    │   ├── LEE-LESS-01.md
    │   └── [... LEE-specific lessons]
    ├── decisions/
    │   ├── LEE-DEC-01.md
    │   └── [... LEE-specific decisions]
    ├── architecture/
    │   ├── LEE-ARCH-Overview.md
    │   └── LEE-ARCH-Integration.md
    ├── indexes/
    │   ├── lee-index-main.md
    │   └── lee-index-functions.md
    └── README.md
```

---

## CONTEXT (MODE ACTIVATION FILES)

```
/sima/context/
├── Custom Instructions for SUGA-ISP Development.md
├── MODE-SELECTOR.md
├── SESSION-START-Quick-Context.md
├── PROJECT-MODE-Context.md
├── DEBUG-MODE-Context.md
└── SIMA-LEARNING-SESSION-START-Quick-Context.md
```

---

## SUPPORT (TOOLS, WORKFLOWS, TEMPLATES)

```
/sima/support/
├── tools/
│   ├── TOOL-01-REF-ID-Directory.md
│   ├── TOOL-02-Quick-Answer-Index.md
│   ├── generate-urls.py
│   └── neural-map-index-builder.html
├── workflows/
│   ├── Workflow-01-Add-Feature.md
│   ├── Workflow-02-Debug-Issue.md
│   └── Workflow-03-Update-Interface.md
├── templates/
│   ├── TMPL-01-Neural-Map-Entry.md
│   └── TMPL-02-Project-Documentation.md
├── checklists/
│   ├── Checklist-01-Code-Review.md
│   └── Checklist-02-Deployment-Readiness.md
└── quick-reference/
    ├── QRC-01-Interfaces-Overview.md
    ├── QRC-02-Gateway-Patterns.md
    └── QRC-03-Common-Patterns.md
```

---

## MIGRATION TARGETS

### Week 1, Day 1 (Today)
- [✅] Create /sima/entries/specifications/ (11 files created)
- [✅] Create LEE knowledge-config.yaml
- [ ] Create /sima/languages/python/architectures/suga/
- [ ] Create /sima/languages/python/architectures/lmms/
- [ ] Create /sima/languages/python/architectures/zaph/
- [ ] Create /sima/languages/python/architectures/dd-1/
- [ ] Create /sima/languages/python/architectures/dd-2/
- [ ] Create /sima/languages/python/architectures/cr-1/

### Week 1, Day 2
- [ ] Migrate SUGA entries from /entries/core/ to /languages/python/architectures/suga/
- [ ] Migrate LMMS entries
- [ ] Migrate ZAPH entries
- [ ] Create DD-1 entries (new - Dictionary Dispatch)
- [ ] Migrate DD-2 entries (old DD → Dependency Disciplines)
- [ ] Create CR-1 entries (new - Cache Registry)
- [ ] Create architecture indexes

### Week 1, Day 3
- [ ] Create /sima/platforms/aws/lambda/
- [ ] Migrate AWS Lambda entries
- [ ] Create platform indexes

### Week 1, Days 4-5
- [ ] Migrate LEE-specific entries to /projects/lee/
- [ ] Create function reference files (12 files)
- [ ] Create project indexes

---

## FILE COUNTS

**Specifications:** 11 ✅  
**Architecture Dirs:** 6 (SUGA, LMMS, ZAPH, DD-1, DD-2, CR-1)  
**Platform Dirs:** 1+ (AWS Lambda minimum)  
**Project Dirs:** 1 (LEE)  
**Function References:** 12 (one per interface)

**Total New Directories:** ~60  
**Total New Files:** ~120  
**Total Migrated Files:** ~200

---

## ARCHITECTURE KNOWLEDGE ORGANIZATION

### SUGA Architecture (4 sessions complete)
**Status:** ✅ Complete (31 files)

**Location:** `/sima/languages/python/architectures/suga/`

**Structure:**
```
suga/
├── core/                    3 files ✅
├── gateways/                3 files ✅
├── interfaces/              5 files ✅ (12 interfaces total)
├── decisions/               5 files ✅
├── anti-patterns/           5 files ✅
├── lessons/                 8 files ✅
└── indexes/                 2 files ✅ (7 indexes total)
```

### LMMS Architecture (Session 5 - Priority 1)
**Status:** ⏳ Next

**Location:** `/sima/languages/python/architectures/lmms/`

**Estimated Files:** 15-20 files
- Core files (3-4)
- Decision files (3-5)
- Lesson files (4-6)
- Anti-pattern files (3-4)
- Index files (1-2)

### ZAPH Architecture (Session 5 - Priority 2)
**Status:** ⏳ Next

**Location:** `/sima/languages/python/architectures/zaph/`

**Estimated Files:** 10-15 files
- Core files (3)
- Decision files (2-3)
- Lesson files (3-4)
- Index files (1-2)

### DD-1 Architecture (Session 5 - Priority 3)
**Status:** ⏳ NEW - Dictionary Dispatch

**Location:** `/sima/languages/python/architectures/dd-1/`

**Estimated Files:** 8-12 files
- Core files (3): Pattern, routing, trade-offs
- Decision files (2): Dict vs if-else, memory-speed
- Lesson files (2-4): Performance, LEE implementation
- Index files (1-2)

### DD-2 Architecture (Session 5 - Priority 4)
**Status:** ⏳ Migration from old DD

**Location:** `/sima/languages/python/architectures/dd-2/`

**Estimated Files:** 10-15 files
- Core files (3): Concept, layer rules, flow
- Decision files (2-3): Higher-lower, no circular
- Lesson files (3-4): Dependencies cost, refactoring
- Index files (1-2)

### CR-1 Architecture (Session 5 - Priority 5)
**Status:** ⏳ NEW - Cache Registry

**Location:** `/sima/languages/python/architectures/cr-1/`

**Estimated Files:** 8-12 files
- Core files (3): Registry concept, wrapper pattern, consolidation
- Decision files (2): Central registry, export consolidation
- Lesson files (2-4): Fast path, discovery, maintenance
- Index files (1-2)

---

## CREATION STATUS

**✅ Completed:**
- Specification files (11)
- LEE knowledge config (1)
- SUGA architecture (31 files)
- This directory structure doc (1)

**🔄 Next:**
- LMMS architecture files
- ZAPH architecture files
- DD-1 architecture files (NEW)
- DD-2 architecture files (migration)
- Architecture indexes

---

## KEY DISTINCTIONS

### DD-1 vs DD-2

**DD-1: Dictionary Dispatch**
- **Type:** Performance optimization pattern
- **Domain:** Function routing
- **Used For:** Interface dispatching, action handlers
- **Example:** LEE interface files use DD-1
- **Benefit:** O(1) lookup speed
- **Trade-off:** Memory for dispatch table

**DD-2: Dependency Disciplines**
- **Type:** Architecture pattern
- **Domain:** Layer organization
- **Used For:** Managing module dependencies
- **Example:** SUGA layer dependencies use DD-2
- **Benefit:** No circular imports
- **Trade-off:** More structured code required

**Never Confuse:**
- DD-1 is about **performance** (how fast to route)
- DD-2 is about **structure** (how to organize dependencies)

---

## SESSION 5 UPDATED GOALS

**Complete all 6 remaining Python architectures:**

1. **LMMS** - Lazy Module Management System (15-20 files)
2. **ZAPH** - Zone Access Priority Hierarchy (10-15 files)
3. **DD-1** - Dictionary Dispatch (8-12 files) **← NEW**
4. **DD-2** - Dependency Disciplines (10-15 files) **← UPDATED**
5. **CR-1** - Cache Registry (8-12 files) **← NEW**

**Total Estimated:** 51-74 files across 15-20 artifacts

---

**END OF FILE**

**Version:** 4.2.2 (CR-1 Cache Registry added)  
**Date:** 2025-11-07  
**Key Change:** Added CR-1 (Cache Registry) as 6th Python architecture  
**Architecture Count:** 6 total (SUGA ✅, LMMS ⏳, ZAPH ⏳, DD-1 ⏳, DD-2 ⏳, CR-1 ⏳)

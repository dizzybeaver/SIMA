# File: DEC-17.md

**REF-ID:** DEC-17  
**Category:** Technical Decision  
**Priority:** Medium  
**Status:** Active  
**Date Decided:** 2024-04-15  
**Created:** 2024-04-15  
**Last Updated:** 2025-10-30 (SIMAv4 migration)

---

## 📋 SUMMARY

All interfaces and core files in root directory (flat structure) with one exception: home_assistant/ subdirectory. Simple navigation proven effective at scale.

**Decision:** Flat file structure with descriptive naming  
**Impact Level:** Medium  
**Reversibility:** Easy (but proven unnecessary)

---

## 🎯 CONTEXT

### Problem Statement
Traditional project organization uses nested directories (interfaces/, implementations/, utils/). For Lambda with ~90 files, evaluate if subdirectories add value or complexity.

### Background
- Lambda project has 93 Python files
- 12 interfaces, 12 core implementations
- Gateway layer, fast path, diagnostics
- One extension (Home Assistant, 17 files)

### Requirements
- Easy file discovery
- Simple import paths
- Clear organization
- Scalable structure

---

## 💡 DECISION

### What We Chose
Flat file structure in /src root with descriptive filenames. Only exception: home_assistant/ subdirectory for large extension.

### Implementation
```
/src/
├── gateway.py, gateway_core.py, gateway_wrappers.py
├── interface_cache.py, cache_core.py, cache_manager.py
├── interface_logging.py, logging_core.py
├── interface_http.py, http_client_core.py
├── interface_config.py, config_core.py
├── lambda_function.py, fast_path.py
└── home_assistant/          ← ONLY subdirectory
    ├── __init__.py
    ├── ha_core.py
    ├── ha_alexa.py
    └── ha_websocket.py
```

### Rationale
1. **Simple Imports**
   - No directory navigation
   - Clear import paths
   - Easy to understand
   ```python
   # Flat
   from gateway import cache_get
   from cache_core import CacheManager
   
   # Nested (alternative)
   from core.interfaces.cache.gateway import cache_get
   from core.implementations.cache.manager import CacheManager
   ```

2. **Easy File Discovery**
   - Type "cache" → see all cache files
   - Alphabetical grouping natural
   - No guessing which directory

3. **Proven at Scale**
   - Works well with 93 files
   - Clear naming prevents confusion
   - No organizational issues

4. **Descriptive Naming Pattern**
   ```
   interface_<name>.py    - Router layer
   <name>_core.py         - Implementation
   <name>_manager.py      - Management
   gateway_<name>.py      - Gateway functions
   ```

---

## 📄 ALTERNATIVES CONSIDERED

### Alternative 1: Nested Directory Structure
**Pros:**
- Traditional organization
- Separates concerns visually

**Cons:**
- Longer import paths
- More navigation clicks
- Harder to refactor (paths break)
- No clear benefit for 93 files

**Why Rejected:** Complexity without benefit.

---

### Alternative 2: Group by Layer (gateway/, interface/, core/)
**Pros:**
- Separates SUGA layers
- Clear layer boundaries

**Cons:**
- Must navigate directories to find related files
- Import paths more complex
- Refactoring harder

**Why Rejected:** Related files scattered across directories.

---

### Alternative 3: Group by Feature (cache/, http/, config/)
**Pros:**
- All cache files together

**Cons:**
- Must duplicate structure for each feature
- Gateway files isolated
- Import complexity

**Why Rejected:** Gateway layer spans all features.

---

## ⚖️ TRADE-OFFS

### What We Gained
- Simple file discovery (type name, find file)
- Short import paths
- Easy refactoring (no path updates)
- Fast IDE navigation
- Proven effective (93 files, no issues)

### What We Accepted
- All files in one directory
- Must use descriptive names
- Need naming conventions
- One exception (home_assistant/)

---

## 📊 IMPACT ANALYSIS

### Technical Impact
- **Navigation:** Very fast (no directory hierarchy)
- **Imports:** Simplest possible paths
- **Refactoring:** Minimal path updates
- **IDE:** Fast file search

### Developer Impact
- **Learning:** Instantly understand structure
- **Productivity:** Find files immediately
- **Mistakes:** Fewer wrong imports
- **Maintenance:** Clear file purpose from name

### Exception: home_assistant/
- 17 HA-specific files warrant subdirectory
- Logical grouping for large extension
- Still accessed via facade pattern
- Proves flat structure works (needed exception)

---

## 🔮 FUTURE CONSIDERATIONS

### When to Revisit
- If file count exceeds 150
- If clear feature boundaries emerge
- Never needed in 18+ months

### Evolution Path
- Additional extension subdirectories if needed
- Keep main system flat
- Extension pattern for large features

---

## 🔗 RELATED

### Related Decisions
- **DEC-02:** Gateway Centralization (all files access gateway)

### SIMA Entries
- **RULE-04:** Flat File Structure rule
- **AP-05:** Subdirectories anti-pattern
- **GATE-01:** Three-File Structure (naming convention)

---

## 🏷️ KEYWORDS

`flat-structure`, `file-organization`, `simple-imports`, `navigation`, `naming-convention`, `no-subdirectories`

---

## 📝 VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 3.0.0 | 2025-10-30 | Migration | SIMAv4 migration, under 400 lines |
| 2.0.0 | 2025-10-24 | System | SIMA v3 format |
| 1.0.0 | 2024-04-15 | Original | Decision made |

---

**END OF DECISION**

**Status:** Active - Proven effective with 93 files  
**Effectiveness:** Simple navigation, fast development, no issues

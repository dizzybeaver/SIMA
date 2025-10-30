# NM02-Dependencies-InterfaceDetail_Index.md

# Dependencies - Interface Detail Index

**Category:** NM02 - Dependencies
**Topic:** Interface Detail
**Items:** 3
**Last Updated:** 2025-10-24

---

## Topic Overview

**Description:** Deep-dive analysis of individual interface dependencies, showing what each interface depends on and what depends on it. Includes performance metrics, usage patterns, and change impact analysis.

**Keywords:** interface dependencies, dependency analysis, usage patterns, performance metrics, change impact

---

## Individual Files

### CACHE-DEP: CACHE Interface Dependencies
- **File:** `NM02-Dependencies-InterfaceDetail_CACHE-DEP.md`
- **Summary:** CACHE depends on LOGGING + METRICS, used by HTTP_CLIENT + CONFIG + SECURITY for caching
- **Priority:** 🟡 High
- **Key Metrics:** 75-85% hit rate, < 1ms lookup, ~10MB memory

### HTTP-DEP: HTTP_CLIENT Interface Dependencies
- **File:** `NM02-Dependencies-InterfaceDetail_HTTP-DEP.md`
- **Summary:** HTTP_CLIENT depends on LOGGING + SECURITY + CACHE + METRICS, used by homeassistant_extension
- **Priority:** 🟡 High
- **Key Metrics:** 50-200ms uncached, 60-80% cache hit rate, 99.5% success rate

### CONFIG-DEP: CONFIG Interface Dependencies
- **File:** `NM02-Dependencies-InterfaceDetail_CONFIG-DEP.md`
- **Summary:** CONFIG depends on LOGGING + CACHE + SECURITY, used by ALL interfaces
- **Priority:** 🟡 High
- **Key Metrics:** 12-23ms first load, < 1ms cached, 90-95% cache hit rate

---

## Why Interface Detail Matters

**Change Impact:**
Understanding interface dependencies helps predict change impact:
- CACHE change affects HTTP_CLIENT, CONFIG, SECURITY
- HTTP_CLIENT change affects homeassistant_extension
- CONFIG change affects ALL interfaces (critical!)

**Performance Optimization:**
Interface metrics guide optimization:
- CACHE hit rates show caching effectiveness
- HTTP_CLIENT timing reveals bottlenecks
- CONFIG load times impact cold start

**Dependency Management:**
Clear dependency picture prevents:
- Circular dependencies
- Unexpected side effects
- Breaking changes

---

## Quick Reference

**Most Complex Dependencies:**
1. **HTTP_CLIENT** - Depends on 4 interfaces (Layer 0, 1, 2)
2. **CONFIG** - Depends on 3 interfaces (Layer 0, 1, 2)
3. **CACHE** - Depends on 2 interfaces (Layer 0, 2)

**Most Used:**
1. **CONFIG** - Used by ALL interfaces
2. **CACHE** - Used by HTTP_CLIENT, CONFIG, SECURITY
3. **HTTP_CLIENT** - Used by extensions, integrations

**Performance Impact:**
- **CACHE** - Biggest performance multiplier (10-100x speedup)
- **CONFIG** - Critical for cold start time
- **HTTP_CLIENT** - External API bottleneck

---

## Related Topics

- **Dependency Layers** - Layer hierarchy (DEP-01 to DEP-05)
- **Import Rules** - How interfaces access each other (RULE-01 to RULE-05)

---

**Navigation:**
- **Up:** NM02-Dependencies_Index.md (Category Index)
- **Siblings:** NM02-Dependencies-Layers_Index.md, NM02-Dependencies-ImportRules_Index.md

---

**End of Index**
